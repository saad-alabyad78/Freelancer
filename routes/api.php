<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Company;
use App\Models\Profile;
use App\Services\xmlService;
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

require 'Api/company.php' ;


Route::post('test' , function(){
    $x = new xmlService("constants/xml/syrian_cities.xml") ;
    return implode(' , ' , $x->toJson($x->xmlContent)->city); 
    $r = [] ;
    foreach($x->xmlContent->city as $c)
    {
        $r = $c ;
    }
    return $c ;
});

Route::get('test' , function(){
    return User::all();
});



