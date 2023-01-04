<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;



Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/transaction/count/showAllItemsCount', [TransactionController::class, 'showAllItemsCount']);
    Route::get('/transaction/count/DataDashboard', [TransactionController::class, 'DataDashboard']);
    Route::get('/transaction/count/showOne/{id}', [TransactionController::class, 'showOne']);

    Route::post('/transaction/count/createItem', [TransactionController::class, 'createItemCount']);
    Route::post('/transaction/count/createItem', [TransactionController::class, 'createItemCount']);

    Route::delete('/transaction/count/delete/{id}', [TransactionController::class, 'destory']);

    Route::post('/transaction/count/update', [TransactionController::class, 'editItemCount']);

    Route::get('/transaction/count/showAllItemsHistory', [TransactionController::class, 'showAllItemsHistory']);

    
});
