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
            ->join('types', 'plants.type_id', '=', 'types.id')
            ->select(
                'articles.id',
                'articles.title',
                'articles.content',
                'articles.updated_at',
                'plants.name as plant_name',
                'users.username as author'
            );
    }

    // GET /api/articles?all
    public function index(Request $request)
    {
        if ($request->query('all') === 'true') {
            $articles = $this->baseQuery()->get();
            return $this->jsonResponse(200, 'All articles retrieved', $articles);
        }
        
        $pageSize = $request->query('pageSize', 5);
        $page = $request->query('page', 1);
        $articles = $this->baseQuery()->paginate($pageSize, ['*'], 'page', $page);

        return $this->jsonResponse(200, 'Articles retrieved successfully', $articles);
    }

    // GET /api/articles/search?q=&author=&plant=&type=&before=&after=&all
    public function search(Request $request)
    {
        if ($request->has("all")) {
            $articles = $this->baseQuery()->get();
            return $this->jsonResponse(200, 'All articles retrieved', $articles);
        }

        $query = $this->baseQuery();
        $q = $request->query('q', '');
        $inContent = $request->query('incontent');

        if (!empty($q)) {
            $query->where(function ($query) use ($q, $inContent) {
                $query->where('articles.title', 'LIKE', "%$q%")
                    ->orWhere('plants.name', 'LIKE', "%$q%");
                if ($inContent === 'true') {
                    $query->orWhere('articles.content', 'LIKE', "%$q%");
                }
            });
        }

        // Filters
        //TODO: add article type to db, to query result, to filter
        $filters = ['author' => 'users.username', 'plant' => 'plants.name', 'type' => 'types.name'];
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

        $pageSize = $request->query('pageSize', 5);
        $page = $request->query('page', 1);
        $articles = $query->paginate($pageSize, ['*'], 'page', $page);

        return $this->jsonResponse(200, 'Articles retrieved successfully', $articles);
    }

    // GET /api/articles/{title}
    public function show($title)
    {
        $article = $this->baseQuery()
            ->addSelect('articles.source', 'articles.created_at')
            ->where('articles.title', '=', $title)
            ->first();

        if (!$article) {
            return response()->json(['status' => 404, 'message' => 'Article not found', 'data' => []], 404);
        }

        return $this->jsonResponse(200, 'Article found', $article);
    }

}