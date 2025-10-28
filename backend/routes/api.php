<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

//Rutas API
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//Rutas protegidas
Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});
