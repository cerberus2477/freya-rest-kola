<?php

use App\Http\Middleware\Abilities;
use App\Http\Middleware\OwnerOrAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Exceptions\Renderers\AuthenticationExceptionRenderer;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'abilities' => Abilities::class,
            'ownerOrAdmin' => OwnerOrAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //$exceptions->AuthenticationExceptionRenderer();
    })->create();
