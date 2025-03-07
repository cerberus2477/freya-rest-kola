<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use illuminate\Support\Facades\DB;
use App\Models\Article;
use App\Http\Requests\ArticleRequest;

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
    // GET /api/articles?all

    /**
     * @api {get} /api/articles Retrieve articles
     * @apiName GetArticles
     * @apiGroup Articles
     * @apiDescription Retrieve a paginated list of articles or all articles if `all` is passed. Return most important datapoints.
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
     * @apiSuccess {String} data.plant_name Plant name related to article. Can be null.
     * @apiSuccess {String} data.plant_type Type of plant. Can be null.
     * @apiSuccess {String} data.author Author username.
     *
     * @apiSuccess {Object[]} data List of articles. (depending on pagination) Only select datapoints are shown. 
     *
     * 
     * @apiSuccessExample {json} Success Response:
     * HTTP/1.1 200 OK
     * {
     *   "status": 200,
     *   "message": "Articles retrieved successfully",
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Article 1",
     *       "category": "Gardening",
     *       "description": "Description here",
     *       "updated_at": "2024-03-06",
     *       "plant_name": "Rose",
     *       "plant_type": "Flower",
     *       "author": "JohnDoe"
     *      }
     *    ]
     *    "pagination": {
     *          "total": 1,
     *          "page":1,
     *          "pageSize":5,
     *          "totalPages":1}
     *      }
     * }
     */


    //  this is an example response. make sure to include all fields. this one might already be correct
    // {"status":200,"message":"Articles retrieved successfully","data":[{"id":5,"title":"Magni nisi accusantium vel.","category":"hasznos tippek","description":"Sunt ut ipsum non. Officiis cupiditate dolorem non unde tempora et deleniti.","updated_at":"2025-03-04","plant_name":null,"plant_type":null,"author":"luettgen.selina"},{"id":6,"title":"Et esse ratione nemo eveniet.","category":"alkalmaz\u00e1s haszn\u00e1lata","description":"Culpa libero itaque laborum quod tempora vero officiis. Dolores optio fuga sit error hic quo.","updated_at":"2025-03-04","plant_name":null,"plant_type":null,"author":"juanita75"}],"pagination":{"total":100,"page":3,"pageSize":2,"totalPages":50}}

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
        Cache::put($cacheKey, $response, now()->addMinutes(10));
        return $response;
    }

    // GET /api/articles/search?q=&deep?&author=&plant=&type=&category=&before=&after=&all
    
    /**
     * @api {get} /api/articles/search Search articles
     * @apiName SearchArticles
     * @apiGroup Articles
     * @apiDescription Search articles by title, plant name, author, plant type, category, or date range. Return most important datapoints. paginated list of articles or all articles if `all` is passed.
     *
     * @apiParam {String} [q] Search term for title or plant name. Approxiamate search.
     * @apiParam {Boolean} [deep] If present, also searches in content and description.
     * @apiParam {String} [author] Filter by author username. Filters have to match the value exactly.
     * @apiParam {String} [plant] Filter by plant name.
     * @apiParam {String} [type] Filter by type of plant.
     * @apiParam {String} [category] Filter by category name.
     * @apiParam {Date} [before] Filter articles updated before this date.
     * @apiParam {Date} [after] Filter articles updated after this date.
     * @apiParam {Boolean} [all] If present, retrieves all matching articles without pagination. No pagination data is returned.
     * @apiParam {Number} [pageSize=5] Number of articles per page.
     * @apiParam {Number} [page=1] Page number.
     *
     * @apiSuccess {Object[]} data List of articles matching the search criteria (depending on pagination). Only select datapoints are shown. 
     *
     * @apiSuccessExample {json} Success Response:
     * HTTP/1.1 200 OK
     * {
     *   "status": 200,
     *   "message": "Articles retrieved successfully",
     *   "data": [
     * {"id":1,"title":"Occaecati nostrum aliquid ipsum earum consequuntur.","category":null,"description":"Illum cupiditate qui tempore placeat sint voluptas omnis. Soluta blanditiis sed dolorem nisi fugiat non. Ullam dicta cum reiciendis rerum. Non enim vel sed consequatur autem qui.","updated_at":"2025-03-04","plant_name":"M\u00e1lna","plant_type":"gy\u00fcm\u00f6lcs","author":"juanita75"}
     * ]
     *   "pagination": {
     *          "total": 1,
     *          "page":1,
     *          "pageSize":5,
     *          "totalPages":1}
     *      }
     * }
     */

    //  this is an example response. make sure to include all fields. 
    // {"status":200,"message":"Articles retrieved successfully","data":[{"id":32,"title":"Deserunt repudiandae ut qui velit.","category":null,"description":"Veritatis eaque nemo non et. Ut optio ad incidunt quibusdam. Libero voluptatem consequatur nihil et. Ut excepturi tenetur vel consequuntur et ipsum beatae enim.","updated_at":"2025-03-04","plant_name":"Alma","plant_type":"gy\u00fcm\u00f6lcs","author":"oberbrunner.gianni"},{"id":44,"title":"Non perspiciatis et aut et.","category":"n\u00f6v\u00e9nyek gondoz\u00e1sa","description":"Quos et cupiditate voluptas maiores. Dolore totam commodi dolor laborum. Voluptatem magnam sed nulla est et.","updated_at":"2025-03-04","plant_name":"Alma","plant_type":"gy\u00fcm\u00f6lcs","author":"oberbrunner.gianni"},{"id":94,"title":"Ratione veritatis nihil rerum aut alias non.","category":"n\u00f6v\u00e9nyek gondoz\u00e1sa","description":"Optio ut aut sit labore. Qui qui in at eius nostrum nam placeat. Tenetur nobis rerum doloribus nihil et.","updated_at":"2025-03-04","plant_name":"Alma","plant_type":"gy\u00fcm\u00f6lcs","author":"freeman72"}],"pagination":{"total":3,"page":1,"pageSize":5,"totalPages":1}}
    public function search(Request $request)
    {
        $cacheKey = 'articles_seacrh_' . md5($request->fullUrl());
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
        Cache::put($cacheKey, $response, now()->addMinutes(10));
        return $response;
    }

    // GET /api/articles/{title}

        /**
     * @api {get} /api/articles/:title Get article by title
     * @apiName GetArticle
     * @apiGroup Articles
     * @apiDescription Retrieve a single article by its title. All datapoints are returned.
     *
     * @apiParam {String} title Article title (URL encoded).
     *
     * @apiSuccess {Number} id Article ID.
     * @apiSuccess {String} title Article title.
     * @apiSuccess {String} category Category name.
     * @apiSuccess {String} description Article description.
     * @apiSuccess {String} content Full article content.
     * @apiSuccess {String} source Article source URL.
     * @apiSuccess {Date} created_at Date article was created.
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
     *     "content": "Full content here in markdown format...",
     *     "source": "https://example.com/article",
     *     "created_at": "2024-03-06"
     *   }
     * }
     */

     //this is the atual response. make sure to include all fields.
    //  {"status":200,"message":"\"Occaecati nostrum aliquid ipsum earum consequuntur.\" article found","data":{"id":1,"title":"Occaecati nostrum aliquid ipsum earum consequuntur.","category":null,"description":"Illum cupiditate qui tempore placeat sint voluptas omnis. Soluta blanditiis sed dolorem nisi fugiat non. Ullam dicta cum reiciendis rerum. Non enim vel sed consequatur autem qui.","updated_at":"2025-03-04","plant_name":"M\u00e1lna","plant_type":"gy\u00fcm\u00f6lcs","author":"juanita75","source":"http:\/\/rodriguez.com\/dolor-similique-fuga-quis-fugit.html","content":"1. Asperiores molestiae consequatur molestias ut.\n2. Quia dolorem aut quia cupiditate beatae eligendi.\n3. Sit error et velit non libero quia sunt.\n1. Asperiores molestiae consequatur molestias ut.\n2. Quia dolorem aut quia cupiditate beatae eligendi.\n3. Sit error et velit non libero quia sunt.\n```\nAut maxime minus doloribus reprehenderit. Tenetur nemo dicta repudiandae. Et nihil alias dignissimos sunt earum est molestiae quis.\n```\n* Non eum odit id omnis cum.\n# Maiores quaerat voluptatibus eum quisquam quis fuga voluptas.\n\n* Non aut quidem possimus velit est.\n### Minima sit quaerat optio ut a nam.\n\n# Maiores quaerat voluptatibus eum quisquam quis fuga voluptas.\n\n### Minima sit quaerat optio ut a nam.\n\n- Omnis voluptate voluptate sed.\n- Voluptatem minima magnam rerum sed.\n- Unde illo quidem reiciendis consequatur.\n* Non eum odit id omnis cum.\n# Maiores quaerat voluptatibus eum quisquam quis fuga voluptas.\n\n## Sit repellat aut recusandae laudantium.\n\n* Non eum odit id omnis cum.\n### Minima sit quaerat optio ut a nam.\n\n1. Asperiores molestiae consequatur molestias ut.\n2. Quia dolorem aut quia cupiditate beatae eligendi.\n3. Sit error et velit non libero quia sunt.\n**Labore nam sed fugiat deleniti ex.**\n\n> Dolore est ipsum in omnis fugit ab.\n\n```\nAut maxime minus doloribus reprehenderit. Tenetur nemo dicta repudiandae. Et nihil alias dignissimos sunt earum est molestiae quis.\n```\n**Labore nam sed fugiat deleniti ex.**\n\n1. Asperiores molestiae consequatur molestias ut.\n2. Quia dolorem aut quia cupiditate beatae eligendi.\n3. Sit error et velit non libero quia sunt.\n### Minima sit quaerat optio ut a nam.\n\n1. Asperiores molestiae consequatur molestias ut.\n2. Quia dolorem aut quia cupiditate beatae eligendi.\n3. Sit error et velit non libero quia sunt.\n> Dolore est ipsum in omnis fugit ab.\n\n```\nAut maxime minus doloribus reprehenderit. Tenetur nemo dicta repudiandae. Et nihil alias dignissimos sunt earum est molestiae quis.\n```\n1. Asperiores molestiae consequatur molestias ut.\n2. Quia dolorem aut quia cupiditate beatae eligendi.\n3. Sit error et velit non libero quia sunt.\n* Non eum odit id omnis cum.\n> Dolore est ipsum in omnis fugit ab.\n\n## Sit repellat aut recusandae laudantium.\n\n> Dolore est ipsum in omnis fugit ab.\n\n```\nAut maxime minus doloribus reprehenderit. Tenetur nemo dicta repudiandae. Et nihil alias dignissimos sunt earum est molestiae quis.\n```\n* Non aut quidem possimus velit est.\n### Minima sit quaerat optio ut a nam.\n\n1. Asperiores molestiae consequatur molestias ut.\n2. Quia dolorem aut quia cupiditate beatae eligendi.\n3. Sit error et velit non libero quia sunt.\n- Omnis voluptate voluptate sed.\n- Voluptatem minima magnam rerum sed.\n- Unde illo quidem reiciendis consequatur.\n```\nAut maxime minus doloribus reprehenderit. Tenetur nemo dicta repudiandae. Et nihil alias dignissimos sunt earum est molestiae quis.\n```\n> Dolore est ipsum in omnis fugit ab.\n\n* Non eum odit id omnis cum.\n**Labore nam sed fugiat deleniti ex.**\n\n**Labore nam sed fugiat deleniti ex.**\n\n1. Asperiores molestiae consequatur molestias ut.\n2. Quia dolorem aut quia cupiditate beatae eligendi.\n3. Sit error et velit non libero quia sunt.\n- Omnis voluptate voluptate sed.\n- Voluptatem minima magnam rerum sed.\n- Unde illo quidem reiciendis consequatur.\n* Non aut quidem possimus velit est.\n- Omnis voluptate voluptate sed.\n- Voluptatem minima magnam rerum sed.\n- Unde illo quidem reiciendis consequatur.\n### Minima sit quaerat optio ut a nam.\n\n1. Asperiores molestiae consequatur molestias ut.\n2. Quia dolorem aut quia cupiditate beatae eligendi.\n3. Sit error et velit non libero quia sunt.\n* Non aut quidem possimus velit est.\n## Sit repellat aut recusandae laudantium.\n\n- Omnis voluptate voluptate sed.\n- Voluptatem minima magnam rerum sed.\n- Unde illo quidem reiciendis consequatur.\n**Labore nam sed fugiat deleniti ex.**\n\n1. Asperiores molestiae consequatur molestias ut.\n2. Quia dolorem aut quia cupiditate beatae eligendi.\n3. Sit error et velit non libero quia sunt.\n### Minima sit quaerat optio ut a nam.\n\n> Dolore est ipsum in omnis fugit ab.\n\n```\nAut maxime minus doloribus reprehenderit. Tenetur nemo dicta repudiandae. Et nihil alias dignissimos sunt earum est molestiae quis.\n```\n# Maiores quaerat voluptatibus eum quisquam quis fuga voluptas.\n\n# Maiores quaerat voluptatibus eum quisquam quis fuga voluptas.\n\n## Sit repellat aut recusandae laudantium.\n\n**Labore nam sed fugiat deleniti ex.**\n\n* Non eum odit id omnis cum.\n```\nAut maxime minus doloribus reprehenderit. Tenetur nemo dicta repudiandae. Et nihil alias dignissimos sunt earum est molestiae quis.\n```\n# Maiores quaerat voluptatibus eum quisquam quis fuga voluptas.\n\n```\nAut maxime minus doloribus reprehenderit. Tenetur nemo dicta repudiandae. Et nihil alias dignissimos sunt earum est molestiae quis.\n```\n```\nAut maxime minus doloribus reprehenderit. Tenetur nemo dicta repudiandae. Et nihil alias dignissimos sunt earum est molestiae quis.\n```\n1. Asperiores molestiae consequatur molestias ut.\n2. Quia dolorem aut quia cupiditate beatae eligendi.\n3. Sit error et velit non libero quia sunt.\n- Omnis voluptate voluptate sed.\n- Voluptatem minima magnam rerum sed.\n- Unde illo quidem reiciendis consequatur.\n- Omnis voluptate voluptate sed.\n- Voluptatem minima magnam rerum sed.\n- Unde illo quidem reiciendis consequatur.\n## Sit repellat aut recusandae laudantium.\n\n* Non aut quidem possimus velit est.\n> Dolore est ipsum in omnis fugit ab.\n\n> Dolore est ipsum in omnis fugit ab.\n\n- Omnis voluptate voluptate sed.\n- Voluptatem minima magnam rerum sed.\n- Unde illo quidem reiciendis consequatur.\n```\nAut maxime minus doloribus reprehenderit. Tenetur nemo dicta repudiandae. Et nihil alias dignissimos sunt earum est molestiae quis.\n```\n* Non eum odit id omnis cum.\n1. Asperiores molestiae consequatur molestias ut.\n2. Quia dolorem aut quia cupiditate beatae eligendi.\n3. Sit error et velit non libero quia sunt.\n### Minima sit quaerat optio ut a nam.\n\n# Maiores quaerat voluptatibus eum quisquam quis fuga voluptas.\n\n### Minima sit quaerat optio ut a nam.\n\n1. Asperiores molestiae consequatur molestias ut.\n2. Quia dolorem aut quia cupiditate beatae eligendi.\n3. Sit error et velit non libero quia sunt.\n```\nAut maxime minus doloribus reprehenderit. Tenetur nemo dicta repudiandae. Et nihil alias dignissimos sunt earum est molestiae quis.\n```\n**Labore nam sed fugiat deleniti ex.**\n\n```\nAut maxime minus doloribus reprehenderit. Tenetur nemo dicta repudiandae. Et nihil alias dignissimos sunt earum est molestiae quis.\n```\n## Sit repellat aut recusandae laudantium.\n\n## Sit repellat aut recusandae laudantium.\n\n1. Asperiores molestiae consequatur molestias ut.\n2. Quia dolorem aut quia cupiditate beatae eligendi.\n3. Sit error et velit non libero quia sunt.\n```\nAut maxime minus doloribus reprehenderit. Tenetur nemo dicta repudiandae. Et nihil alias dignissimos sunt earum est molestiae quis.\n```\n# Maiores quaerat voluptatibus eum quisquam quis fuga voluptas.\n\n## Sit repellat aut recusandae laudantium.\n\n* Non aut quidem possimus velit est.\n**Labore nam sed fugiat deleniti ex.**\n\n1. Asperiores molestiae consequatur molestias ut.\n2. Quia dolorem aut quia cupiditate beatae eligendi.\n3. Sit error et velit non libero quia sunt.\n# Maiores quaerat voluptatibus eum quisquam quis fuga voluptas.\n\n# Maiores quaerat voluptatibus eum quisquam quis fuga voluptas.\n\n> Dolore est ipsum in omnis fugit ab.\n\n- Omnis voluptate voluptate sed.\n- Voluptatem minima magnam rerum sed.\n- Unde illo quidem reiciendis consequatur.\n**Labore nam sed fugiat deleniti ex.**\n\n> Dolore est ipsum in omnis fugit ab.\n\n# Maiores quaerat voluptatibus eum quisquam quis fuga voluptas.\n\n### Minima sit quaerat optio ut a nam.\n\n* Non eum odit id omnis cum.\n1. Asperiores molestiae consequatur molestias ut.\n2. Quia dolorem aut quia cupiditate beatae eligendi.\n3. Sit error et velit non libero quia sunt.\n","created_at":"2025-03-04"}}
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
            return $this->jsonResponse(404, "\"$title\" article not found");
        }
        
        $response = $this->jsonResponse(200, "\"$title\" article found", $article);
        Cache::put($cacheKey, $response, now()->addMinutes(10));
        return $response;
    }

    /**
     * create new article
     */
    public function create(ArticleRequest $request)
    {
        $article = Article::create($request->validated());
        return $this->jsonResponse(201, 'Cikk sikeresen létrehozva', $article);
    }

    /**
     * Update an existing article.
     */
    public function update(ArticleRequest $request, $title)
    {
        $article = Article::where('title', $title)->firstOrFail();
        $article->update($request->validated());
        return $this->jsonResponse(200, 'Cikk sikeresen frissítve', $article);
    }

    /**
     * Delete an article.
     */
    public function delete($title)
    {
        $article = Article::where('title', $title)->firstOrFail();
        $article->delete();
        return $this->jsonResponse(200, 'Cikk sikeresen törölve');
    }

}