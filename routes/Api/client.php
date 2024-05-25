<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\Query\GetClientQuery;
use App\Http\Controllers\Client\Commands\CreateClientCommand;
use App\Http\Controllers\Client\Commands\UpdateClientCommand;
use App\Http\Controllers\Client\Commands\CreateClientImageCommand;
use App\Http\Controllers\Client\Commands\DeleteClientImageCommand;



Route::group([
    'prefix' => 'client'
] , function(){

    Route::get('{client:id}' , GetClientQuery::class) ;

    Route::group([
        'middleware' => [
            'auth:sanctum' ,
            'verify_email' , 
            'role:client' ,
        ],
    ] , function(){
        Route::post('store' , CreateClientCommand::class)
            ->withoutMiddleware('role:client')
            ->middleware('role:no_role'); 

        Route::put('' , UpdateClientCommand::class);
        
        Route::post('image/profile' , [CreateClientImageCommand::class , 'profile_image']);
        Route::post('image/background' , [CreateClientImageCommand::class , 'background_image']);

        Route::delete('image/profile' , [DeleteClientImageCommand::class , 'profile_image']);
        Route::delete('image/background' , [DeleteClientImageCommand::class , 'background_image']);
        
    });
    
});