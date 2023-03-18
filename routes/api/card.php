<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ColorController;

Route::middleware(['auth:sanctum'])->group(function () {

    // Debit
    Route::post('/card/create/DebitCard', [CardController::class, 'createDebitCard']);

    // credit
    Route::post('/card/create/CreditCard', [CardController::class, 'createCreditCard']);

    Route::get('/card/showOne/{id}', [CardController::class, 'showOne']);
    Route::get('/card/showAll', [CardController::class, 'showAll']);
    Route::post('/card/update/{id}', [CardController::class, 'update']);
    Route::post('/card/UpdateState/{id}', [CardController::class, 'UpdateState']);
    Route::delete('/card/delete/{id}', [CardController::class, 'destroy']);
    
    Route::get('/colors', [ColorController::class, 'showAll']);
});
