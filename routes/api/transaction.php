<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;



Route::middleware(['auth:sanctum'])->group(function () {

    // Route::get('/transaction/count/DataDashboard', [TransactionController::class, 'DataDashboard']);
    // Route::post('/transaction/count/showAllItemsCount', [TransactionController::class, 'showAllItemsCount']);
    Route::post('/card/transactions', [TransactionController::class, 'showAllItemsCount']);

    Route::get('/card/transaction/show/{id}', [TransactionController::class, 'showOne']);

    Route::post('/card/transaction/create', [TransactionController::class, 'createItemCount']);

    Route::delete('/transaction/count/delete/{id}', [TransactionController::class, 'destory']);
    Route::post('/card/transaction/update', [TransactionController::class, 'editItemCount']);

    Route::post('/card/transaction/card', [TransactionController::class, 'transactionBetweenCards']);

    // Route::post('/items/deleteAll', [TransactionController::class, 'deleteItemsOfCard']);
    Route::post('card/transaction/clear', [TransactionController::class, 'deleteItemsOfCard']);
});
