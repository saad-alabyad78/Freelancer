<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LogController;

Route::post('login' , [LogController::class, 'login'])->middleware('guest');

Route::group([
    'middleware' => ['auth:sanctum'] ,
],function(){
    Route::post('logout' , [LogController::class , 'logout']) ;
});
