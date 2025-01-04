<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChirpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

Route::apiResource('chirps', ChirpController::class)
    ->middleware('auth:sanctum');
