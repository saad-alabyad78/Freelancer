<?php

use App\Services\xmlService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

require 'Api/log.php' ;
require 'Api/socialite.php' ;
require 'Api/otp.php' ;

require 'Api/render-commands.php';

require 'Api/company.php' ;


Route::post('test' , function(){

    

  return 'hi ' ;
});

Route::get('test' , function(){
    return 'ok' ;
});