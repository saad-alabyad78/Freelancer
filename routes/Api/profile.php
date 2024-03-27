<?php


use App\Http\Controllers\Profile\ProfileController;
use Illuminate\Support\Facades\Route;

Route::group(
[
    'middleware' => ['auth:sanctum' , 'verified'] ,
    'prefix' => 'profile' ,
] , function(){
    Route::post('/' , [ProfileController::class , 'store']) ;
});