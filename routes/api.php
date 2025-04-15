<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/auth/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])
    ->name('auth.login')
    ->middleware('guest')
    ->withoutMiddleware([\Illuminate\Routing\Middleware\SubstituteBindings::class]);
