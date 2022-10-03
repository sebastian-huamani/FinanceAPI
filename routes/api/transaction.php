<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

Route::get('/transaction/count/showAllItemsCount/{id}', [TransactionController::class, 'showAllItemsCount' ]);
Route::get('/transaction/count/showOne/{id}', [TransactionController::class, 'showOne' ]);

Route::post('/transaction/count/createItem', [TransactionController::class, 'createItemCount' ]);
Route::post('/transaction/count/createItem', [TransactionController::class, 'createItemCount' ]);

Route::delete('/transaction/count/delete/{id}', [TransactionController::class, 'destory']);

Route::put('/transaction/count/update', [TransactionController::class, 'editItemCount']);

