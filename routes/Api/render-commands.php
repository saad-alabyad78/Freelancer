<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

/**
 * @group Ducker Image Commands
 * 
 **/

Route::get('command/{command:string}' , function(string $command){
    Artisan::call($command) ;
    return Artisan::output() ;
});

/**
 * @group Ducker Image Commands
 * 
 **/
Route::get('command/database/fresh' , function(){
    Artisan::call('migrate:fresh') ;
    Artisan::output() ;
});

/**
 * @group Ducker Image Commands
 * 
 **/
Route::get('command/database/seed' , function(){
    Artisan::call('db:seed') ;
    return Artisan::output() ;
});

/**
 * @group Ducker Image Commands
 * 
 **/

Route::delete('command/storage/delete' , function(){

    $directories = ['company'] ;

    $total_size = 0 ;
    foreach($directories as $directory)
    {
        $files = Storage::files($directory) ;

        foreach($files as $file)
        {
            $total_size += Storage::size($file) ;

            Storage::delete($file) ;
        }
    }
    return $total_size . ' KB are freed from the storage' ;
});