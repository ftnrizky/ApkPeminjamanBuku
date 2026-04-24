<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // Daftarkan alias middleware 'role' → RoleMiddleware
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'blacklist' => \App\Http\Middleware\CheckBlacklist::class,
        ]);

        // Override middleware 'guest' dengan versi role-aware kita
        $middleware->redirectGuestsTo(fn () => route('login'));

        // Pastikan RedirectIfAuthenticated yang dipakai adalah versi fix kita
        // (Laravel 12 otomatis pakai App\Http\Middleware\RedirectIfAuthenticated
        //  selama file-nya ada di folder tersebut)

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
