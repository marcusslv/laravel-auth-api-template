<?php

use Illuminate\Foundation\Application;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Response;

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
        $exceptions->render(function (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'type' => 'error',
                'status' => 422,
                'message' => $e->validator->getMessageBag()->first(),
                'errors' => $e->validator->errors()->toArray(),
                'show' => false,
            ], 422);
        });

        $exceptions->render(function (AuthenticationException $e) {
            return response()->json([
                'type' => 'error',
                'status' => Response::HTTP_UNAUTHORIZED,
                'message' => $e->getMessage(),
                'show' => false
            ], Response::HTTP_UNAUTHORIZED);
        });
    })->create();
