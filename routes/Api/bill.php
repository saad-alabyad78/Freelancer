<?php
use App\Http\Controllers\BillController;

Route::prefix('bills')->group(function(){
    Route::middleware([
        'auth:sanctum' ,
        'verify_email' ,
        'role:admin'
    ])->group(function(){
        Route::get('/' , [BillController::class , 'index']) ;
        Route::get('/reports', [BillController::class , 'reports']) ;
    }) ;
}) ;