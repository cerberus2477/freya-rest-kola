<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Listing;
use App\Models\User;
use App\Models\Article;
use App\Models\UserPlant;

class OwnerOrAdmin
{
    protected function resolveModelFromRequest(Request $request, ?string $modelType)
{
    $id = $request->route('id');
    if (!$id) {
        $title = $request->route('title');
        if ($modelType === 'article') {
            return Article::where('title', $title)->first();
        }
    }

    switch ($modelType) {
        case 'listing':
            return Listing::find($id);
        case 'article':
            return Article::where('title', $title)->first();
        case 'user-plant':
            return UserPlant::find($id);
        case 'user':
            return User::find($id);
        default:
            return null; // Return null if the model type is not recognized
    }
}

public function handle(Request $request, Closure $next, ?string $modelType): Response
{
    $user = $request->user();
    $model = $this->resolveModelFromRequest($request, $modelType);
    if (!$model || !$user->canModify($model)) {
        return response()->json([
            'status' => 403,
            'message' => 'You must be the owner or admin to perform this action',
            'data' => [],
        ], 403);
    }

    // Attach the model to the request for controller use
    $request->attributes->set('authorized_model', $model);

    return $next($request);
}
}