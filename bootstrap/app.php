<?php

use App\Http\Middleware\Authenticate;
use Illuminate\Foundation\Application;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Session\Middleware\StartSession;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware (সব রিকুয়েস্টে প্রয়োগ হবে)
        $middleware->append([
            TrustProxies::class,
            HandleCors::class,             // CORS middleware
            CheckForMaintenanceMode::class,
        ]);

        // Web middleware group (web.php রাউটে প্রয়োগ)
        $middleware->group('web', [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            ValidateCsrfToken::class,     // CSRF Token ভেরিফিকেশন
            SubstituteBindings::class,
            HandleCors::class,
            SubstituteBindings::class,
        ]);

        // API middleware group (api.php রাউটে প্রয়োগ)
        $middleware->group('api', [
            HandleCors::class,
            SubstituteBindings::class,
            
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Exception handling config যদি থাকে
    })->create();
