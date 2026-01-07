<?php

use App\Http\Middleware\LecturerMiddleware;
use App\Http\Middleware\UniversityMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth; 

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'lecturer' => LecturerMiddleware::class,
            'university' => UniversityMiddleware::class,
        ]);

        $middleware->redirectUsersTo(function () {
            $user = Auth::user();
            
            return $user ? route('dashboard', ['profileId' => $user->profileId]) : '/';
        });

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();