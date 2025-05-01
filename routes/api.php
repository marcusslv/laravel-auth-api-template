<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/auth/login', [\App\Http\Controllers\Auth\AuthController::class, 'login'])
    ->name('auth.login')
    ->middleware('guest')
    ->withoutMiddleware([\Illuminate\Routing\Middleware\SubstituteBindings::class]);

Route::post('/auth/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout'])
    ->name('auth.logout')
    ->middleware('auth:sanctum')
    ->withoutMiddleware([\Illuminate\Routing\Middleware\SubstituteBindings::class]);

Route::apiResource('users', \App\Http\Controllers\User\UserController::class)
    ->only(['index', 'show', 'update', 'destroy', 'store'])
    ->middleware('auth:sanctum')
    ->withoutMiddleware([\Illuminate\Routing\Middleware\SubstituteBindings::class]);

Route::apiResource('roles', \App\Http\Controllers\Role\RoleController::class)
    ->only(['index', 'show', 'update', 'store'])
    ->middleware('auth:sanctum');
