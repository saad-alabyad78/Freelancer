<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('command/{command:string}' , function(string $command){
    Artisan::call($command) ;
    return Artisan::output() ;
});

Route::get('command/database/fresh' , function(){
    Artisan::call('migrate:fresh') ;
    Artisan::output() ;
});

Route::get('command/database/seed' , function(){
    Artisan::call('db:seed') ;
    return Artisan::output() ;
});