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
    ->withMiddleware(function (Middleware $middleware): void {
        // Enregistrement des middlewares personnalisés
        $middleware->alias([
            'admin' => \App\Http\Middleware\Custom\Admin::class,
            'super_admin' => \App\Http\Middleware\Custom\SuperAdmin::class,
            'teamSA' => \App\Http\Middleware\Custom\TeamSA::class,
            'teamSAT' => \App\Http\Middleware\Custom\TeamSAT::class,
            'teamAccount' => \App\Http\Middleware\Custom\TeamAccount::class,
            'examIsLocked' => \App\Http\Middleware\Custom\ExamIsLocked::class,
            'my_parent' => \App\Http\Middleware\Custom\MyParent::class,
            'student' => \App\Http\Middleware\Custom\Student::class,
            'librarian' => \App\Http\Middleware\Custom\Librarian::class,
            'accountant' => \App\Http\Middleware\Custom\Accountant::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
