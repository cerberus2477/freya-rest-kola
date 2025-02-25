<?php
namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    // GET /api/articles
    public function index(Request $request)
    {
        // Check if 'all' is set to true
        if ($request->query('all') === 'true') {
            $articles = DB::table('articles')
                ->leftJoin('plants', 'articles.plant_id', '=', 'plants.id')
                ->leftJoin('users', 'articles.author_id', '=', 'users.id')
                ->select(
                    'articles.id',
                    'articles.title',
                    'articles.content',
                    'articles.updated_at',
                    'plants.name as plant_name',
                    'users.username as author'
                )
                ->get(); // Get all results without pagination

            return response()->json(['data' => $articles], 200);
        }

        // Query builder with necessary joins
        $query = DB::table('articles')
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

        // Pagination and search parameters
        $pageSize = $request->query('pageSize', 5);
        $page = $request->query('page', 1);

        //approximate search
        $search = $request->query('search', '');
        //whether to search in content too
        $incontent = $request->query('incontent');

        if (!empty($search)) {
            $query->where(function ($q) use ($search, $incontent) {
                $q->where('articles.title', 'LIKE', "%$search%")
                    ->orWhere('plants.name', 'LIKE', "%$search%");
                
                if ($incontent === 'true') {
                    $q->orWhere('articles.content', 'LIKE', "%$search%");
                }
            });
        }
       

        // Filters
        $author = $request->query('author');
        $plant = $request->query('plant');
        $type = $request->query('type');
        $before = $request->query('before');
        $after = $request->query('after');

        if (!empty($author)) {
            $query->where('users.username', '=', $author);
        }
        if (!empty($author)) {
            $query->where('users.username', '=', $author);
        }

        if (!empty($plant)) {
            $query->where('plants.name', '=', $plant);
        }
        if (!empty($type)) {
            $query->where('types.name', '=', $type);
        }

        if (!empty($before)) {
            $query->where('articles.modified_at', '<=', $before);
        }
        if (!empty($after)) {
            $query->where('articles.modified_at', '>=', $after);
        }

        // Paginate results
        $articles = $query->paginate($pageSize, ['*'], 'page', $page);

        return response()->json([
            'status' => 200,
            'data' => $articles->items(),
            'pagination' => [
                'total' => $articles->total(),
                'page' => $articles->currentPage(),
                'pageSize' => $articles->perPage(),
                'totalPages' => $articles->lastPage(),
            ],
        ]);
    }

    // GET /api/articles/{title}
    public function show($title)
    {
        $article = DB::table('articles')
            ->leftJoin('plants', 'articles.plant_id', '=', 'plants.id')
            ->leftJoin('users', 'articles.author_id', '=', 'users.id')
            ->join('types', 'plants.type_id', '=', 'types.id')
            ->select(
                'articles.id',
                'articles.title',
                'articles.source',
                'articles.content',
                'articles.created_at',
                'articles.updated_at',
                'plants.name as plant_name',
                'types.name as type',
                'users.username as author',
            )
            ->where('articles.title', '=', $title)
            ->first();

        if (!$article) {
            return response()->json([
                'status' => 404,
                'message' => 'Article not found',
                'data' => []
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Article found',
            'data' => $article
        ], 200);
    }
}





        // Sorting option
        // if ($request->has('sortby') && in_array($request->input('sortby'), ['created_at', 'modified_at'])) {
        //     $query->orderBy($request->input('sortby'), 'desc');
        // } else {
        //     $query->orderBy('created_at', 'desc'); // Default sorting
        // }

        // return response()->json($query->get(['id', 'title', 'author', 'plant_name', 'updated_at']));

