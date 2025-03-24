<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use illuminate\Support\Facades\DB;
use App\Models\Article;
use App\Http\Requests\ArticleRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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


    public function index(Request $request)
    {
        // Attempt to fetch data from cache
        $cacheKey = 'articles_index_' . md5($request->fullUrl());
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $query = $this->baseQuery();
        if ($request->has('all')) {
            $articles = $query->get();
        }
        else {
            $pageSize = $request->query('pageSize', 5);
            $page = $request->query('page', 1);
            $articles = $query->paginate($pageSize, ['*'], 'page', $page);
        }

        $response = $this->jsonResponse(200, 'Articles retrieved successfully', $articles);
        Cache::put($cacheKey, $response, Carbon::now()->addMinutes(10));
        return $response;
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
                $query->where($column, '=', $value);
            }
        }

        if ($before = $request->query('before')) {
            $query->where('articles.updated_at', '<=', $before);
        }
        if ($after = $request->query('after')) {
            $query->where('articles.updated_at', '>=', $after);
        }

        //return matching results and cache
        if ($request->has('all')) {
            $articles = $query->get();
        }
        else {
            $pageSize = $request->query('pageSize', 5);
            $page = $request->query('page', 1);
            $articles = $query->paginate($pageSize, ['*'], 'page', $page);
        }

        $response = $this->jsonResponse(200, 'Matching articles retrieved successfully', $articles);
        Cache::put($cacheKey, $response, Carbon::now()->addMinutes(10));
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
        Cache::put($cacheKey, $response, Carbon::now()->addMinutes(10));
        return $response;
    }



     public function create(ArticleRequest $request)
     {
        // $manager = new ImageManager(new Driver());

        // // Handle image uploads
        // $imagePaths = [];
        // if ($request->hasFile('image')) {
        //     foreach ($request->file('image') as $image) {
        //         // Create an image instance, scale down-if needed, and comress
        //         $imageInstance = $manager->read($image->getRealPath());
        //         $imageInstance->scaleDown(1920, 1080);
        //         $encodedImage = $imageInstance->toJpeg(85);
 
        //         // Generate a unique filename with proper file format, save to public/listings/
        //         $filename = 'article_' . uniqid() . '.webp';
        //         $path = 'public/listings/' . $filename;
        //         Storage::disk('public')->put($path, $encodedImage);
        //         // Store the public URL
        //         $imagePaths[] = Storage::url($path);
        //     }
        // }

        // $data = array_merge($request->validated(), ['image' => $imagePaths]);
        // $listing = Article::create($data);
        // return $this->jsonResponse(201, 'Article succesfully created', $listing);
     }



    public function update(ArticleRequest $request, $title)
    {
        // $manager = new ImageManager(new Driver());

        // $article = Article::where('title', $title)->firstOrFail();
        // // Handle image uploads
        // $imagePaths = [];
        // if ($request->hasFile('image')) {
        //     $images = $article->images;
        //     if ($images) {
        //         foreach ($images as $file) {
        //             $filePath = storage_path('app/public/public/articles/' . $file->filename);
        //             if (file_exists($filePath)) {
        //                 unlink($filePath); // Delete the file from storage
        //             }
        //         }
        //     }

        //     foreach ($request->file('image') as $image) {
        //         // Create an image instance, scale down-if needed, and comress
        //         $imageInstance = $manager->read($image->getRealPath());
        //         $imageInstance->scaleDown(1920, 1080);
        //         $encodedImage = $imageInstance->toJpeg(85);
 
        //         // Generate a unique filename with proper file format, save to public/listings/
        //         $filename = 'article_' . uniqid() . '.webp';
        //         $path = 'public/listings/' . $filename;
        //         Storage::disk('public')->put($path, $encodedImage);
        //         // Store the public URL
        //         $imagePaths[] = Storage::url($path);
        //     }
        // }

        // $data = array_merge($request->validated(), ['image' => $imagePaths]);

        // $article->update($data);
        // return $this->jsonResponse(200, 'Cikk sikeresen frissítve', $article);
    }

    




     //TODO: no.
    public function destroy($title)
    {
        // $article = Article::where('title', $title)->firstOrFail();
        
        // $images = $article->images;
        // if ($images) {
        //     foreach ($images as $file) {
        //         $filePath = storage_path('app/public/pubcli/articles/' . $file->filename);
        //         if (file_exists($filePath)) {
        //             unlink($filePath); // Delete the file from storage
        //         }
        //     }
        // }
        // $article->delete();
        // return $this->jsonResponse(200, 'Article deleted succesfully');
    }

}














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
     *   "message": "Cikk sikeresen törölve"
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
     * @apiBody {String} [plant_name] Name of the related plant.
     * @apiBody {String} [plant_type] Type of the plant.
     * @apiBody {String} [author] Author username.
     *
     * @apiSuccess {Number} id Article ID.
     * @apiSuccess {String} title Title of the article.
     * @apiSuccess {String} category Category of the article.
     * @apiSuccess {String} description Short description of the article.
     * @apiSuccess {String} content Full article content.
     * @apiSuccess {String} source Source URL of the article.
     * @apiSuccess {String} plant_name Name of the related plant.
     * @apiSuccess {String} plant_type Type of the plant.
     * @apiSuccess {String} author Author username.
     * @apiSuccess {Date} created_at Date article was created.
     * @apiSuccess {Date} updated_at Date article was last updated.
     *
     * @apiSuccessExample {json} Success Response:
     * HTTP/1.1 200 OK
     * {
     *   "status": 200,
     *   "message": "Cikk sikeresen frissítve",
     *   "data": {
     *     "id": 1,
     *     "title": "Updated Article",
     *     "category": "Gardening",
     *     "description": "Updated description",
     *     "content": "Updated content here...",
     *     "source": "https://example.com/article",
     *     "plant_name": "Tulip",
     *     "plant_type": "Flower",
     *     "author": "JohnDoe",
     *     "created_at": "2025-03-09",
     *     "updated_at": "2025-03-10"
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
     * @apiBody {String} category Category of the article. Can be null.
     * @apiBody {String} description Short description of the article.
     * @apiBody {String} content Full article content.
     * @apiBody {String} source Source URL of the article. Can be null.
     * @apiBody {String} plant_name Name of the related plant. Can be null.
     * @apiBody {String} plant_type Type of the plant. Can be null.
     * @apiBody {String} author Author username.
     *
     * @apiSuccess {Number} id Article ID.
     * @apiSuccess {String} title Title of the article.
     * @apiSuccess {String} category Category of the article.
     * @apiSuccess {String} description Short description of the article.
     * @apiSuccess {String} content Full article content.
     * @apiSuccess {String} source Source URL of the article.
     * @apiSuccess {String} plant_name Name of the related plant.
     * @apiSuccess {String} plant_type Type of the plant.
     * @apiSuccess {String} author Author username.
     * @apiSuccess {Date} created_at Date article was created.
     * @apiSuccess {Date} updated_at Date article was last updated.
     *
     * @apiSuccessExample {json} Success Response:
     * HTTP/1.1 201 Created
     * {
     *   "status": 201,
     *   "message": "Cikk sikeresen létrehozva",
     *   "data": {
     *     "id": 1,
     *     "title": "New Article",
     *     "category": "Gardening",
     *     "description": "Short description",
     *     "content": "Full content here...",
     *     "source": "https://example.com/article",
     *     "plant_name": "Rose",
     *     "plant_type": "Flower",
     *     "author": "JohnDoe",
     *     "created_at": "2025-03-09",
     *     "updated_at": "2025-03-09"
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
 * @apiSuccess {String} category Category name (can be null).
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
 *   "message": "\"Occaecati nostrum aliquid ipsum earum consequuntur.\" article found",
 *   "data": {
 *     "id": 1,
 *     "title": "Occaecati nostrum aliquid ipsum earum consequuntur.",
 *     "category": null,
 *     "description": "Illum cupiditate qui tempore placeat sint voluptas omnis.",
 *     "updated_at": "2025-03-04",
 *     "plant_name": "Málna",
 *     "plant_type": "gyümölcs",
 *     "author": "juanita75",
 *     "source": "http://rodriguez.com/dolor-similique-fuga-quis-fugit.html",
 *     "content": "Full article content in markdown format...",
 *     "created_at": "2025-03-04"
 *   }
 * }
 */





 
  /**
 * @api {get} /articles Retrieve articles
 * @apiName GetArticles
 * @apiGroup Articles
 * @apiDescription Retrieve a paginated list of articles or all articles if `all` is passed. Returns key datapoints.
 *
 * @apiParam {Boolean} [all] If present, retrieves all articles without pagination.
 * @apiParam {Number} [pageSize=5] Number of articles per page.
 * @apiParam {Number} [page=1] Page number.
 *
 * @apiSuccess {Number} data.id Article ID.
 * @apiSuccess {String} data.title Article title.
 * @apiSuccess {String} data.category Category name. Can be null.
 * @apiSuccess {String} data.description Article description.
 * @apiSuccess {Date} data.updated_at Last updated date.
 * @apiSuccess {String} data.plant_name Plant name related to the article. Can be null.
 * @apiSuccess {String} data.plant_type Type of plant. Can be null.
 * @apiSuccess {String} data.author Author username.
 *
 * @apiSuccess {Object[]} data List of articles (depending on pagination).
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
 *       "id": 5,
 *       "title": "Magni nisi accusantium vel.",
 *       "category": "hasznos tippek",
 *       "description": "Sunt ut ipsum non. Officiis cupiditate dolorem non unde tempora et deleniti.",
 *       "updated_at": "2025-03-04",
 *       "plant_name": null,
 *       "plant_type": null,
 *       "author": "luettgen.selina"
 *     }
 *   ],
 *   "pagination": {
 *     "total": 100,
 *     "page": 3,
 *     "pageSize": 2,
 *     "totalPages": 50
 *   }
 * }
 */



   // GET /api/articles/search?q=&deep?&author=&plant=&type=&category=&before=&after=&all
    
/**
 * @api {get} /articles/search Search articles
 * @apiName SearchArticles
 * @apiGroup Articles
 * @apiDescription Search articles by title, plant name, author, plant type, category, or date range. Returns paginated list of articles or all articles if `all` is passed.
 *
 * @apiParam {String} [q] Search term for title or plant name (approximate search).
 * @apiParam {Boolean} [deep] If present, also searches in content and description.
 * @apiParam {String} [author] Filter by author username (exact match).
 * @apiParam {String} [plant] Filter by plant name.
 * @apiParam {String} [type] Filter by type of plant.
 * @apiParam {String} [category] Filter by category name.
 * @apiParam {Date} [before] Filter articles updated before this date.
 * @apiParam {Date} [after] Filter articles updated after this date.
 * @apiParam {Boolean} [all] If present, retrieves all matching articles without pagination. No pagination data is returned.
 * @apiParam {Number} [pageSize=5] Number of articles per page.
 * @apiParam {Number} [page=1] Page number.
 *
 * @apiSuccess {Object[]} data List of articles matching the search criteria.
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
 *       "id": 32,
 *       "title": "Deserunt repudiandae ut qui velit.",
 *       "category": null,
 *       "description": "Veritatis eaque nemo non et. Ut optio ad incidunt quibusdam.",
 *       "updated_at": "2025-03-04",
 *       "plant_name": "Alma",
 *       "plant_type": "gyümölcs",
 *       "author": "oberbrunner.gianni"
 *     }
 *   ],
 *   "pagination": {
 *     "total": 3,
 *     "page": 1,
 *     "pageSize": 5,
 *     "totalPages": 1
 *   }
 * }
 */