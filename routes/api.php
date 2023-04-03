<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Mail\VerifyEmailMaliable;
use Illuminate\Support\Facades\Mail;


Route::post('/register', [ AuthController::class, 'register']);
Route::post('/login', [ AuthController::class, 'login']);
Route::get('unauthorized',[AuthController::class, 'unauthorized'])->name('api.unauthorized');
Route::post('/logout', [ AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/pruebas', [ AuthController::class, 'pruebas']);


Route::get('/verifyemail', function() {
    try {
        $email = new VerifyEmailMaliable;
        Mail::to('huamanitassara@gmail.com')->send($email);
    
        return response()->json([
            'res' => "Mensaje Enviado"
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'e' => $e->getMessage()
        ]);
    }
});
