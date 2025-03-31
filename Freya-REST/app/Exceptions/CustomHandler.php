<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomHandler extends ExceptionHandler
{
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Custom unauthorized message here.',
                'success' => false,
            ], 401);
        }
        
        return redirect()->guest($exception->redirectTo(route(Route::post('/login', [UserController::class,'login']))));
    }
}
