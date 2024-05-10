<?php

use App\Models\Company;
use App\Constants\Disks;
use App\Constants\Defaults;
use App\Models\GalleryImage;
use App\Services\xmlService;
use App\Services\imageService;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\Company\CompanyResource;

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

require 'Api/category.php' ;


Route::post('test' , function(){
  
  return  CompanyResource::collection(Company::all() ) ;
  
});

Route::get('test' , function(){
  return 'hi' ;
});