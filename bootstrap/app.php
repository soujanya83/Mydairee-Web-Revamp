<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $exception, Request $request) {
            if (!($request->is('api') || $request->is('api/*'))) {
                return null;
            }

            $bearerToken = $request->bearerToken();

            return response()->json([
                'status' => 'error',
                'reason' => blank($bearerToken) ? 'login_issue' : 'token_issue',
                'message' => blank($bearerToken)
                    ? 'User is not logged in. Bearer token is missing.'
                    : 'Bearer token is invalid, expired, or revoked.',
            ], 401);
        });

            $exceptions->render(function (NotFoundHttpException $exception, Request $request) {
                if (!($request->is('api') || $request->is('api/*'))) {
                    return null;
                }

                return response()->json([
                    'status' => 'error',
                    'reason' => 'route_not_found',
                    'message' => 'API route not found.',
                ], 404);
            });
    })->create();
