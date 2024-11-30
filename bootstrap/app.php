<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin.auth' => \App\Http\Middleware\AdminAuth::class,
            'guard.auth' => \App\Http\Middleware\GuardAuth::class,
            'treasurer.auth' => \App\Http\Middleware\TreasurerAuth::class,

        ]);
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
