<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Company;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
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


Route::post('test' , function(){
    return 'ok' ;
});

Route::get('test' , function(){
    return User::all();
});



