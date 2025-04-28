<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use illuminate\Support\Facades\DB;
use App\Models\Article;
use App\Http\Requests\ArticleRequest;
use App\Http\Requests\ArticleImageRequest;
use Carbon\Carbon;
use App\Helpers\StorageHelper;

class ArticleController extends BaseController
{
    private function baseQuery()
    {
        return DB::table('articles')
            ->leftJoin('plants', 'articles.plant_id', '=', 'plants.id')
            ->leftJoin('users', 'articles.author_id', '=', 'users.id')
            ->leftJoin('types', 'plants.type_id', '=', 'types.id')
            ->leftJoin('categories', 'articles.category_id', '=', 'categories.id')
            ->select(
                'articles.id',
                'articles.title',
                'categories.name as category',
                'articles.description',
                DB::raw('DATE(articles.updated_at) as updated_at'), // Extract only the date
                'plants.name as plant_name',
                'types.name as type',
                'users.username as author'
            )
            ->orderByDesc('articles.created_at'); // Sort by newest first
    } 

    public function search(Request $request)
        {
            $cacheKey = 'articles_search_' . md5($request->fullUrl());
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            $query = $this->baseQuery();

            //search by title, plant, optionally in content and description
            $q = $request->query('q', '');
            if (!empty($q)) {
                $query->where(function ($query) use ($q, $request) {
                    $query->where('articles.title', 'LIKE', "%$q%")
                        ->orWhere('plants.name', 'LIKE', "%$q%");
                    if ($request->has("deep")) {
                        $query->orWhere('articles.content', 'LIKE', "%$q%")
                            ->orwhere('articles.description', 'LIKE', "%$q%");
                    }
                });
            }

            //filters
            $filters = [
                'author' => 'users.username', 
                'plant' => 'plants.name', 
                'type' => 'types.name', 
                'category' => 'categories.name'
            ];

            foreach ($filters as $param => $column) {
                if ($value = $request->query($param)) {

                    //returning articles with no plants
                    if ($value == "null") {
                        $query->whereNull('plants.name');
                    }else{
                        $query->where($column, '=', $value);
                    }
                }
            }

            if ($before = $request->query('before')) {
                $query->where('articles.updated_at', '<=', $before);
            }
            if ($after = $request->query('after')) {
                $query->where('articles.updated_at', '>=', $after);
            }

            //return matching results and cache
            $pageSize = $request->query('pageSize', 5);
            if ($pageSize === "all") {
                $articles = $query->get();
            }
            else {
                $pageSize = intval($pageSize);
                $page = $request->query('page', 1);
                $articles = $query->paginate($pageSize, ['*'], 'page', $page);
            }

            $response = $this->jsonResponse(200, 'Articles retrieved successfully', $articles);
            Cache::put($cacheKey, $response, Carbon::now()->addMinutes(60));
            return $response;
        }

    
    public function show($title)
    {
        $title = urldecode($title);

        $cacheKey = 'articles_show_' . md5($title);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $article = $this->baseQuery()
            ->addSelect('articles.source', 'articles.content', DB::raw('DATE(articles.created_at) as created_at'))
            ->where('articles.title', '=', $title)
            ->first();

        if (!$article) {
            return $this->jsonResponse(404, "Article not found");
        }
        
        $response = $this->jsonResponse(200, "\"$title\" article found", $article);
        Cache::put($cacheKey, $response, Carbon::now()->addMinutes(60));
        return $response;
    }



     public function create(ArticleRequest $request)
     {

        $listing = Article::create($request->validated());
        return $this->jsonResponse(201, 'Article succesfully created', $listing);
     }



    public function update(ArticleRequest $request, $title)
    {
        //should have a way to check unused images and delete them, either in frontend or here
        $article = Article::where('title', $title)->firstOrFail();

        $article->update($request->validated());
        return $this->jsonResponse(200, 'Article succesfully updated', $article);
    }

     //TODO: test listings, if listings work, implement destroy, update, create image handling similarly
     public function destroy($title)
     {
         $article = Article::where('title', $title)->firstOrFail();
     
         //TODO: put regex in a seperate function
         // Extract image URLs from markdown content
         preg_match_all('/!\[.*?\]\((.*?)\)/', $article->content, $matches);
         
         // Convert URLs to filenames
         $filenames = array_map(function ($url) {
             return basename(parse_url($url, PHP_URL_PATH));
         }, $matches[1] ?? []);
     
         // Delete images if any were found
         if (!empty($filenames)) {
             StorageHelper::deleteMedia($filenames, 'articles');
         }
     
         // Delete the article from the database
         $article->delete();
     
         return $this->jsonResponse(201, 'Article deleted successfully');
     }
     

     public function uploadArticleImage(ArticleImageRequest $request)
     {
        // Store images in the 'articles' folder
        $imagePaths = StorageHelper::storeRequestImages($request, 'articles');

        $imageUrls = [];
        foreach ($imagePaths as $path) {
            $imageUrls[] = asset("storage/{$path}");
        }
        return $this->jsonResponse(201, 'Image uploaded successfully', [
            'image_paths' => $imageUrls,
        ]);
    }

}
//apidoc

/**
 * @api {get} /articles Search articles
 * @apiName GetArticles
 * @apiGroup Articles
 * @apiDescription Retrieve a paginated list of articles or all articles if `all` is passed. Returns key datapoints.
 *
 * @apiParam {Boolean} [all] If present, retrieves all articles without pagination.
 * @apiParam {Number} [pageSize=5] Number of articles per page.
 * @apiParam {Number} [page=1] Page number.
 * @apiParam {String} [q] Search term for title or plant name (approximate search).
 * @apiParam {Boolean} [deep] If present, also searches in content and description.
 * @apiParam {String} [author] Filter by author username (exact match).
 * @apiParam {String} [plant] Filter by plant name.
 * @apiParam {String} [type] Filter by type of plant.
 * @apiParam {String} [category] Filter by category name.
 * @apiParam {Date} [before] Filter articles updated before this date.
 * @apiParam {Date} [after] Filter articles updated after this date.
 *
 * @apiSuccess {Object[]} data List of articles.
 * @apiSuccess {Number} data.id Article ID.
 * @apiSuccess {String} data.title Article title.
 * @apiSuccess {String} data.category Category name.
 * @apiSuccess {String} data.description Article description.
 * @apiSuccess {Date} data.updated_at Last updated date.
 * @apiSuccess {String} data.plant_name Plant name related to the article (can be null).
 * @apiSuccess {String} data.plant_type Type of plant (can be null).
 * @apiSuccess {String} data.author Author username.
 * @apiSuccess {Object} pagination Pagination information.
 * @apiSuccess {Number} pagination.total Total number of articles.
 * @apiSuccess {Number} pagination.page Current page number.
 * @apiSuccess {Number} pagination.pageSize Number of articles per page.
 * @apiSuccess {Number} pagination.totalPages Total number of pages.
 *
 * @apiSuccessExample {json} Success Response:
 * HTTP/1.1 200 OK
 * {
 *   "status": 200,
 *   "message": "Articles retrieved successfully",
 *   "data": [
 *     {
 *       "id": 1,
 *       "title": "Article Title",
 *       "category": "Gardening",
 *       "description": "Short description",
 *       "updated_at": "2025-03-09",
 *       "plant_name": "Rose",
 *       "plant_type": "Flower",
 *       "author": "JohnDoe"
 *     }
 *   ],
 *   "pagination": {
 *     "total": 10,
 *     "page": 1,
 *     "pageSize": 5,
 *     "totalPages": 2
 *   }
 * }
 */

/**
 * @api {get} /articles/:title Get article by title
 * @apiName GetArticle
 * @apiGroup Articles
 * @apiDescription Retrieve a single article by its title. Returns all available datapoints.
 *
 * @apiParam {String} title Article title (URL encoded).
 *
 * @apiSuccess {Number} id Article ID.
 * @apiSuccess {String} title Article title.
 * @apiSuccess {String} category Category name.
 * @apiSuccess {String} description Article description.
 * @apiSuccess {String} content Full article content in markdown format.
 * @apiSuccess {String} source Article source URL.
 * @apiSuccess {Date} created_at Date article was created.
 * @apiSuccess {Date} updated_at Last updated date.
 * @apiSuccess {String} plant_name Plant name related to the article (can be null).
 * @apiSuccess {String} plant_type Type of plant (can be null).
 * @apiSuccess {String} author Author username.
 *
 * @apiSuccessExample {json} Success Response:
 * HTTP/1.1 200 OK
 * {
 *   "status": 200,
 *   "message": "\"Article Title\" article found",
 *   "data": {
 *     "id": 1,
 *     "title": "Article Title",
 *     "category": "Gardening",
 *     "description": "Short description",
 *     "content": "Full article content in markdown format...",
 *     "source": "https://example.com/article",
 *     "created_at": "2025-03-09",
 *     "updated_at": "2025-03-10",
 *     "plant_name": "Rose",
 *     "plant_type": "Flower",
 *     "author": "JohnDoe"
 *   }
 * }
 */

/**
 * @api {post} /articles Create a new article
 * @apiName CreateArticle
 * @apiGroup Articles
 * @apiDescription Create a new article with the given data.
 *
 * @apiBody {String} title Title of the article.
 * @apiBody {String} category Category of the article.
 * @apiBody {String} description Short description of the article.
 * @apiBody {String} content Full article content.
 * @apiBody {String} source Source URL of the article. Can be null.
 * @apiBody {Integer} plant_id ID of the related plant. Can be null.
 * @apiBody {Integer} author_id ID of the author.
 *
 * @apiSuccess {Number} id Article ID.
 * @apiSuccess {String} title Title of the article.
 * @apiSuccess {String} category Category of the article.
 * @apiSuccess {String} description Short description of the article.
 * @apiSuccess {String} content Full article content.
 * @apiSuccess {String} source Source URL of the article.
 * @apiSuccess {Integer} plant_id ID of the related plant.
 * @apiSuccess {Integer} author_id ID of the author.
 * @apiSuccess {Date} created_at Date article was created.
 * @apiSuccess {Date} updated_at Date article was last updated.
 *
 * @apiSuccessExample {json} Success Response:
 * HTTP/1.1 201 Created
 * {
 *   "status": 201,
 *   "message": "Article successfully created",
 *   "data": {
 *     "id": 1,
 *     "title": "New Article",
 *     "category": "Gardening",
 *     "description": "Short description",
 *     "content": "Full content here...",
 *     "source": "https://example.com/article",
 *     "plant_id": 1,
 *     "author_id": 1,
 *     "created_at": "2025-03-09",
 *     "updated_at": "2025-03-09"
 *   }
 * }
 */

/**
 * @api {put} /articles/:title Update an existing article
 * @apiName UpdateArticle
 * @apiGroup Articles
 * @apiDescription Update an existing article by its title.
 *
 * @apiParam {String} title The title of the article to update (URL encoded).
 *
 * @apiBody {String} [title] New title of the article.
 * @apiBody {String} [category] Category of the article.
 * @apiBody {String} [description] Short description of the article.
 * @apiBody {String} [content] Full article content.
 * @apiBody {String} [source] Source URL of the article.
 * @apiBody {Integer} [plant_id] ID of the related plant.
 *
 * @apiSuccess {Number} id Article ID.
 * @apiSuccess {String} title Title of the article.
 * @apiSuccess {String} category Category of the article.
 * @apiSuccess {String} description Short description of the article.
 * @apiSuccess {String} content Full article content.
 * @apiSuccess {String} source Source URL of the article.
 * @apiSuccess {Integer} plant_id ID of the related plant.
 * @apiSuccess {Date} updated_at Date article was last updated.
 *
 * @apiSuccessExample {json} Success Response:
 * HTTP/1.1 200 OK
 * {
 *   "status": 200,
 *   "message": "Article successfully updated",
 *   "data": {
 *     "id": 1,
 *     "title": "Updated Article",
 *     "category": "Gardening",
 *     "description": "Updated description",
 *     "content": "Updated content here...",
 *     "source": "https://example.com/article",
 *     "plant_id": 1,
 *     "updated_at": "2025-03-10"
 *   }
 * }
 */

/**
 * @api {delete} /articles/:title Delete an article
 * @apiName DeleteArticle
 * @apiGroup Articles
 * @apiDescription Delete an article by its title.
 *
 * @apiParam {String} title The title of the article to delete (URL encoded).
 *
 * @apiSuccess {String} message Success message.
 *
 * @apiSuccessExample {json} Success Response:
 * HTTP/1.1 200 OK
 * {
 *   "status": 200,
 *   "message": "Article successfully deleted"
 * }
 */

/**
 * @api {post} /articles/upload-image Upload article images
 * @apiName UploadArticleImage
 * @apiGroup Articles
 * @apiDescription Upload images for articles. The uploaded images can later be referenced in the article content using their URLs.
 *
 * @apiBody {File[]} media Array of image files to upload.
 *
 * @apiSuccess {String[]} image_paths Array of URLs for the uploaded images.
 *
 * @apiSuccessExample {json} Success Response:
 * HTTP/1.1 201 Created
 * {
 *   "status": 201,
 *   "message": "Image uploaded successfully",
 *   "data": {
 *     "image_paths": [
 *       "http://example.com/storage/articles/image1.webp",
 *       "http://example.com/storage/articles/image2.webp"
 *     ]
 *   }
 * }
 */