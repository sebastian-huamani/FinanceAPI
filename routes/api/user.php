<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user/perfil', [ AuthController::class, 'infoUser']);
    Route::post('/user/update', [ AuthController::class , 'updateInfoUser'] );

});

