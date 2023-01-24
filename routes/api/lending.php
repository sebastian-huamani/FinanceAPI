<?php

use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;



Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/lending/create', [LandingController::class, 'create']);
    Route::get('/lending/showAllActives', [LandingController::class, 'showAllActives']);
    Route::post('/lending/showAllDesactives', [LandingController::class, 'showAllDesactives']);
    Route::delete('/lending/destroy/{id}', [LandingController::class, 'destroy']);
    Route::post('/lending/edit', [LandingController::class, 'edit']);
    Route::get('/lending/showOne/{id}', [LandingController::class, 'showOne']);
    Route::post('/lending/updateState/{id}', [LandingController::class, 'updateState']);

    
});