<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            );
    }

    // GET /api/articles?all
    public function index(Request $request)
    {
        if ($request->has('all')) {
            $articles = $this->baseQuery()->get();
            return $this->jsonResponse(200, 'All articles retrieved', $articles);
        }
        
        $pageSize = $request->query('pageSize', 5);
        $page = $request->query('page', 1);
        $articles = $this->baseQuery()->paginate($pageSize, ['*'], 'page', $page);

        return $this->jsonResponse(200, 'Articles retrieved successfully', $articles);
    }

    // GET /api/articles/search?q=&deep?&author=&plant=&typeofplant=&category=&before=&after=&all
    public function search(Request $request)
    {
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

        //return matching results
        if ($request->has("all")) {
            return $this->jsonResponse(200, 'Articles retrieved successfully', $query->get());
        }

        //paginated
        $pageSize = $request->query('pageSize', 5);
        $page = $request->query('page', 1);
        $paginated = $query->paginate($pageSize, ['*'], 'page', $page);
        return $this->jsonResponse(200, 'Articles retrieved successfully', $paginated);
    }

    // GET /api/articles/{title}
    public function show($title)
    {
        $article = $this->baseQuery()
            ->addSelect('articles.source', 'articles.content', DB::raw('DATE(articles.created_at) as created_at'))
            ->where('articles.title', '=', $title)
            ->first();

        if (!$article) {
            return $this->jsonResponse(404, 'Article not found');
        }

        return $this->jsonResponse(200, 'Article found', $article);
    }

}