<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ColorController;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/colors', [ColorController::class, 'showAll']);
});
