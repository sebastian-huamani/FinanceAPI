<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdministradorController;
use App\Http\Controllers\TemplateController;


Route::middleware(['auth:sanctum'])->group(function(){

    Route::post('/template/create', [ TemplateController::class, 'create' ]);
    
    Route::get('/template/show/{id}', [ TemplateController::class, 'showOne' ]);
    Route::post('/template/update/{id}', [ TemplateController::class, 'update' ]);
    Route::delete('/template/delete/{id}', [ TemplateController::class, 'destroy' ]);
    
    Route::get('/templates', [ TemplateController::class, 'showAll' ]);
});


