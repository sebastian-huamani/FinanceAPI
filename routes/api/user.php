<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user/infoUser', [ AuthController::class, 'infoUser']);
    Route::post('/user/updateInfoUser', [ AuthController::class , 'updateInfoUser'] );

});

