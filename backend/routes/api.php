<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

    Route::prefix('v1')->group(function () {
        Route::prefix('auth')->group(function () {
            //Rutas API
            Route::post('/register', [AuthController::class, 'register']);
            Route::post('/login', [AuthController::class, 'login']);

            //Rutas protegidas
            Route::middleware('auth:api')->group(function() {
                
                //Rutas de Proyectos y Tareas
                Route::apiResource('projects', ProjectController::class);
                Route::apiResource('tasks', TaskController::class);

                //Rutas de Autenticacion
                Route::get('/profile', [AuthController::class, 'profile']);
                Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
                Route::post('/refresh', [AuthController::class, 'refresh']);
            });
        });
    });

Route::get('/',[AuthController::class, 'unauthorized']);
