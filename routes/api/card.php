<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CardController;

// Debit
Route::post('/card/create/DebitCard', [CardController::class , 'createDebitCard' ]);

// credit
Route::post('/card/create/CreditCard', [CardController::class , 'createCreditCard' ]);

Route::get('/card/showOne/{id}', [ CardController::class, 'showOne' ]);
Route::get('/card/showAll', [ CardController::class, 'showAll' ]);
Route::put('/card/update/{id}', [ CardController::class, 'update' ]);
Route::delete('/card/delete/{id}', [ CardController::class, 'destroy' ]);
