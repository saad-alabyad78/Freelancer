<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Storage\StoreFileController;
use App\Http\Controllers\Storage\StoreImageController;

Route::prefix('storage')->group(function(){
    Route::post('image/store' , StoreImageController::class) ;
    Route::post('file/store' , StoreFileController::class) ;
});