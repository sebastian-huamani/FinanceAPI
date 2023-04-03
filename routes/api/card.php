<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ColorController;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/card/create/debit', [CardController::class, 'createDebitCard']);
    Route::post('/card/create/credit', [CardController::class, 'createCreditCard']);
    Route::get('/card/show/{id}', [CardController::class, 'showOne']);
    Route::get('/cards', [CardController::class, 'showAll']);
    Route::post('/card/update/{id}', [CardController::class, 'update']);
    Route::post('/card/UpdateState/{id}', [CardController::class, 'UpdateState']);
    // Route::delete('/card/delete/{id}', [CardController::class, 'destroy']);
});
