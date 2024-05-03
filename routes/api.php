<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Company;
use App\Models\Profile;
use App\Models\GalleryImage;
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
   $image = 'LLWuJiPSbaeXWC4sm5q9FgpGW3St52rq.png' ;

   return GalleryImage::first()->delete() ;
    
});

Route::get('test' , function(){
    return 'ok' ;
});



