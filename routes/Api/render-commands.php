<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('command/{command:string}' , function(string $command){
    return Artisan::call($command) ;
});

Route::get('command/database/fresh' , function(){
    return Artisan::call('migrate:fresh') ;
});

Route::get('command/database/seed' , function(){
    return Artisan::call('db:seed') ;
});