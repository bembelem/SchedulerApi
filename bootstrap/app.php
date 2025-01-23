<?php

use App\Exceptions\AlreadyAuthenticatedException;
use App\Exceptions\ExceptionHandler;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


$handler = new ExceptionHandler;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('api')
                ->name('api.')
                ->group(base_path('routes/guest.php'))
                ->group(base_path('routes/auth.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->redirectUsersTo(function (Request $request) {
            if ($request->expectsJson()) {
                throw new AlreadyAuthenticatedException();
            }

            return '/';
        });

        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);

        $middleware->validateCsrfTokens(except: [ //Отключение проверки csrf токена
            'api/*', // Исключение всех API-роутов
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) use ($handler) {

        $exceptions->render(function (Throwable $e, Request $request) use ($handler) {
                return $handler->handleApiExceptions($e);
        });
    })->create();
