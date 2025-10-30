<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;

Route::get('/', function () {
    return response()->json(['error' => 'Inicie sesion para continuar'], Response::HTTP_UNAUTHORIZED);
})->name('login');


