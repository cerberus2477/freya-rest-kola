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

    return Response()->json([
        'status' => 403,
        'message'=>'The user does not have the required abilities',
        'data'=>[],
    ]);
}
}
