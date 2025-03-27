<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OwnerOrAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $modelParam): Response
    {
        $user = $request->user();
        $model = $request->route($modelParam);

        // Check if model exists and user is owner or admin
        if (!$model || !$user->canModify($model)) {
            return response()->json([
                'status' => 403,
                'message' => 'You must be the owner or admin to perform this action',
                'data' => [],
            ], 403);
        }

        return $next($request);
    }
}
