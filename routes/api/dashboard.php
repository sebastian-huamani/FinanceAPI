<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;



Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/currentmoney', [TransactionController::class, 'ActualCurrentMoney']);
    Route::get('/dataMonth', [TransactionController::class, 'dataxMonth']);
    Route::get('/lendings', [TransactionController::class, 'Lendings']);
    Route::get('/data_info_users', [ AuthController::class, 'data_info_users']);

});
