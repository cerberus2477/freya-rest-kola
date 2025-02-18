<?php
namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    // Search and filter articles
    public function search(Request $request)
    {
        $query = Article::query();

        // Filter by search term (title or plant name)
        if ($request->has('q')) {
            $searchTerm = $request->input('q');
            $query->where('title', 'like', "%$searchTerm%")
                  ->orWhere('plant_name', 'like', "%$searchTerm%");
        }

        // Sorting option
        if ($request->has('sortby') && in_array($request->input('sortby'), ['created_at', 'modified_at'])) {
            $query->orderBy($request->input('sortby'), 'desc');
        } else {
            $query->orderBy('created_at', 'desc'); // Default sorting
        }

        return response()->json($query->get(['id', 'title', 'author', 'plant_name', 'updated_at']));
    }

    // Show details of a specific article
    public function show($title)
    {
        $article = Article::where('title', $title)->first();

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        return response()->json($article);
    }
}