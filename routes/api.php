<?php

use App\Models\Company;
use App\Models\Profile;
use App\Models\User;
use Carbon\Carbon;
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
require 'Api/profile.php' ;

Route::post('test' , function(){
    $p = Profile::first() ;
    $p->avatar_image = 'ihihi' ;
    $p->save() ;
    return [$p] ;
});

Route::get('test' , function(){
    dd(Carbon::now()->format('Y-m-d H:i:s'));
});



