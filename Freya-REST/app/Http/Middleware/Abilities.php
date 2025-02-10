<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Abilities
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $ability)
{
    if ($request->user()->tokenCan($ability)) {
        return $next($request);
    }

    return response()->json(['error' => 'User does not have the right autentication'], 403);
}
}
