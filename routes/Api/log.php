<?php

use App\Http\Controllers\Auth\LogController;
use Illuminate\Support\Facades\Route;

Route::post('login' , [LogController::class, 'login'])->middleware('guest');

Route::group([
    'middleware' => ['auth:sanctum' , 'verified'] ,
],function(){
    Route::post('logout' , [LogController::class , 'logout']) ;
});
