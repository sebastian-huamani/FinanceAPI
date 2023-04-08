<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SessionDiviceController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user/perfil', [ AuthController::class, 'infoUser']);
    Route::post('/user/update', [ AuthController::class , 'updateInfoUser']);
    Route::get('/user/divices', [ SessionDiviceController::class, 'showAll']);

});

