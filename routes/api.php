<?php

use App\Models\Company;
use App\Constants\Disks;
use App\Constants\Defaults;
use App\Models\GalleryImage;
use App\Services\xmlService;
use Illuminate\Http\Request;
use App\Services\imageService;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\Company\CompanyResource;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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

require 'Api/client.php' ;

require 'Api/category.php' ;


Route::post('test' , function(Request $request){
  return Company::findOrFail(1)->delete();
});

Route::get('test' , function(){
  return 'hi' ;
});