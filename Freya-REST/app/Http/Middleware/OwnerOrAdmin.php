<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Listing;
use App\Models\User;
use App\Models\Article;
use App\Models\UserPlant;
use Illuminate\Database\Eloquent\Model;

class OwnerOrAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $model = $this->resolveModelFromRequest($request);
        
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

    protected function resolveModelFromRequest(Request $request)
    {
        $route = $request->route();
        $path = $request->path();
        $id = $route->parameter('id');
        
        if (str_contains($path, 'listings')) {
            return Listing::find($id);
        }
        
        if (str_contains($path, 'articles')) {
            return Article::find($id);
        }
        
        if (str_contains($path, 'user-plants')) {
            return UserPlant::find($id);
        }
        
        if (str_contains($path, 'users')) {
            return User::find($id);
        }
        
        return null;
    }
}