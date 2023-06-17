<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;



Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/lending/create', [LandingController::class, 'create']);
    Route::get('/lending/actives', [LandingController::class, 'showAllActives']);
    Route::post('/lending/desactives', [LandingController::class, 'showAllDesactives']);
    Route::post('/lending/edit', [LandingController::class, 'edit']);
    Route::delete('/lending/delete/{id}', [LandingController::class, 'destroy']);
    Route::get('/lending/show/{id}', [LandingController::class, 'showOne']);
    Route::post('/lending/updateState/{id}', [LandingController::class, 'updateState']);
    Route::get('/lendings', [TransactionController::class, 'Lendings']);
    Route::get('/filter/{state}/{card}/{moth}/{year}', [LandingController::class, 'filter_lending']);
    
});