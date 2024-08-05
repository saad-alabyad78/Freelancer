<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;


Route::prefix('home')->group(function(){
    Route::get('/' , [HomeController::class , 'home']) ;
}) ;