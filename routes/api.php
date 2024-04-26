<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Company;
use App\Models\Profile;
use App\Services\xmlService;
use Illuminate\Http\Response;
use App\Services\imageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
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
    $image_name = 'o11eAX0H0XYPEYvBpl9L9XMkefUK40hF.png' ;
    $path = 'company/' . $image_name ;

    

    $file = Storage::disk('company')->get($image_name) ;
    return $type = Storage::disk('company')->mimeType($image_name) ;

    return new Response($file , 200 , ['Cotent-Type' => $type]) ;
    
});

Route::get('test' , function(){
    $image_name = 'o11eAX0H0XYPEYvBpl9L9XMkefUK40hF.png' ;
    
    return $file = ( new imageService() )->get_image('company' , $image_name) ;
});



