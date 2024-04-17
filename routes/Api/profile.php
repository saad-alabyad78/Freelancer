<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Profile\PhoneController;
use App\Http\Controllers\Profile\ProfileController;


Route::group(
[
    'middleware' => ['auth:sanctum' , 'verified'] ,
    'prefix' => 'profile' ,
] , function(){
  
    Route::post('/' , [ProfileController::class , 'store']) ;
    Route::put('/' , [ProfileController::class , 'update']) ; 

   Route::post('phone/register' , [PhoneController::class , 'register'])
        ->middleware('throttle:2,1'); //توفير وحدات 
   Route::post('phone/verify' , [PhoneController::class ,'verify']);
   Route::delete('phone' , [PhoneController::class , 'delete']);

});