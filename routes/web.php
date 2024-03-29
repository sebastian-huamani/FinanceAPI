<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('home'); })->name('home');
Route::get('/financeMe', function () { return view('financeme'); })->name('financeme');
Route::get('/contact', [DataContactController::class, 'index'])->name('contact');
Route::post('/contact', [DataContactController::class, 'create']);


Route::middleware(['guest'])->group( function(){
    Route::get('/login', function () { return view('session.login'); })->name('login');
    Route::post('/login', [AuthController::class, 'loginBase']);
    
    Route::get('/register', function () { return view('session.register'); })->name('register');
    Route::post('/register', [AuthController::class, 'registerBase']);

    
});
Route::post('/logout', [AuthController::class, 'logoutBase'])->name('logout');

Route::get('/dashboard', function () { return view('welcome'); })->name('dashboard')->middleware('auth');
