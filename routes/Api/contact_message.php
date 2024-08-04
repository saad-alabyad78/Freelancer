<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactMessageController;

Route::prefix('contact-message')->group(function(){
    Route::post('/' , [ContactMessageController::class , 'store']) ;

    Route::middleware(['auth:sanctum' , 'role:admin'])->group(function(){
        Route::get('/' , [ContactMessageController::class , 'index']) ;
        Route::delete('/{contactMessage}' , [ContactMessageController::class , 'delete']) ;
    }) ;
}) ;

