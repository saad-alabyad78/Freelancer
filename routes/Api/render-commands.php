<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

/**
 * @group Docker Image Commands
 * 
 **/

Route::get('command/{command:string}' , function(string $command){
    Artisan::call($command) ;
    return Artisan::output() ;
});

/**
 * @group Docker Image Commands
 * 
 **/
Route::get('command/database/fresh' , function(){
    Artisan::call('migrate:fresh') ;
    return Artisan::output() ;
});

/**
 * @group Docker Image Commands
 * 
 **/
Route::get('command/database/seed' , function(){
    Artisan::call('db:seed') ;
    return Artisan::output() ;
});

