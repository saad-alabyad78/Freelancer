<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Client\Query\GetClientQuery;
use App\Http\Controllers\Client\Commands\CreateClientCommand;
use App\Http\Controllers\Client\Commands\UpdateClientCommand;
use App\Http\Controllers\Client\Commands\CreateClientImageCommand;
use App\Http\Controllers\Client\Commands\DeleteClientImageCommand;



Route::group([
    'prefix' => 'client'
] , function(){

    Route::get('{client:id}' , [ClientController::class , 'show']) ;

    Route::group([
        'middleware' => [
            'auth:sanctum' ,
            'verify_email' , 
            'role:client' ,
        ],
    ] , function(){
        Route::post('store' , [ClientController::class , 'store'])
            ->withoutMiddleware('role:client')
            ->middleware('role:no_role'); 

        Route::put('' , [ClientController::class , 'update']);
    });
    
});