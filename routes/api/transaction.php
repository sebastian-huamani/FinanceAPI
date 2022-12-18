<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;



Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/transaction/count/showAllItemsCount', [TransactionController::class, 'showAllItemsCount']);
    Route::get('/transaction/count/showAllItemsUser', [TransactionController::class, 'showAllItemsUser']);
    Route::get('/transaction/count/showOne/{id}', [TransactionController::class, 'showOne']);

    Route::post('/transaction/count/createItem', [TransactionController::class, 'createItemCount']);
    Route::post('/transaction/count/createItem', [TransactionController::class, 'createItemCount']);

    Route::delete('/transaction/count/delete/{id}', [TransactionController::class, 'destory']);

    Route::put('/transaction/count/update', [TransactionController::class, 'editItemCount']);

    Route::get('/transaction/count/showAllItemsHistory', [TransactionController::class, 'showAllItemsHistory']);

    
});
