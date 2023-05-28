<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('home'); })->name('home');
Route::get('/register', function () { return view('session.register'); })->name('register');
Route::get('/login', function () { return view('session.login'); })->name('login');

// Route::get('/documentacion-api', function () { return view('apidoc'); })->name('apidoc');
// Route::get('/user/login', function () { return view('user.login'); })->name('user.login');
// Route::get('/user/register', function () { return view('user.register'); })->name('user.register');
// Route::get('/user/logout', function () { return view('user.logout'); })->name('user.logout');
// Route::get('/user/perfil', function () { return view('user.perfil'); })->name('user.perfil');
// Route::get('/user/update', function () { return view('user.update'); })->name('user.update');


// Route::get('/card/debit', function () { return view('card.debit'); })->name('card.debit');
// Route::get('/card/credit', function () { return view('card.credit'); })->name('card.credit');
// Route::get('/card/show', function () { return view('card.show'); })->name('card.show');
// Route::get('/card/showAll', function () { return view('card.showAll'); })->name('card.showAll');
// Route::get('/card/update', function () { return view('card.update'); })->name('card.update');
// Route::get('/card/state', function () { return view('card.state'); })->name('card.state');


// Route::get('/card/transaction', function () { return view('transaccions.transaccions-date'); })->name('card.transaccions-date');
// Route::get('/card/transaction/show', function () { return view('transaccions.show'); })->name('card.show');
// Route::get('/card/transaction/create', function () { return view('transaccions.create'); })->name('card.create');


