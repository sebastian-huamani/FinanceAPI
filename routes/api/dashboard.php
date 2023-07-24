<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboarController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;



Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/currentmoney', [TransactionController::class, 'ActualCurrentMoney']);
    Route::get('/dataMonth', [TransactionController::class, 'dataxMonth']);
    Route::get('/lendings', [TransactionController::class, 'Lendings']);
    Route::get('/data_info_users', [ AuthController::class, 'data_info_users']);
    Route::get('/transactions', [ AuthController::class, 'allTransaction']);
    Route::get('/transactions/{month}/{year}', [ AuthController::class, 'trasactionMothYear']);
    Route::get('/transactions/{from}/{to}/data', [ AuthController::class, 'transactionsFromTo']);
    Route::get('/flowMoney/{date}', [ DashboarController::class, 'general']);
    Route::get('/dataMonthTemplate/{date}/{type_card}', [ DashboarController::class, 'dataxMonthTemplate']);
});
