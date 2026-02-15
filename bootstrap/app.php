<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function (){
            Route::middleware(['api'])
                ->prefix('/api')
                ->group(function () {
                    require __DIR__.'/../routes/portal/users.php';
                    require __DIR__.'/../routes/portal/instances.php';
                    require __DIR__.'/../routes/portal/companies.php';
                    require __DIR__.'/../routes/portal/agencies.php';
                    require __DIR__.'/../routes/portal/applications.php';
                    require __DIR__.'/../routes/portal/role.php';
                });
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.api' => \App\Http\Middleware\AuthenticateApi::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle authentication errors
        $exceptions->renderable(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado.',
            ], 401);
        });

        // Handle 404 errors
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()
                    ->json([
                        "success" => false,
                        "data" => null,
                        "message" => "Registro no encontrado.",
                    ], $e->getStatusCode());
            }
            return $e;
        });
    })->create();
