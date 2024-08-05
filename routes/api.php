<?php

use App\Models\User;
use App\Models\Company;
use App\Models\JobRole;
use App\Constants\Disks;
use App\Models\JobOffer;
use App\Models\Freelancer;
use App\Constants\Defaults;
use App\Helpers\ChunkHelper;
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

require 'Api/category.php' ;

require 'Api/storage.php' ;

require 'Api/company.php' ;

require 'Api/client.php' ;

require 'Api/freelancer.php' ;

require 'Api/job-offer-proposal.php' ;

require 'Api/chat.php' ;

require 'Api/invitation.php' ;

require 'Api/client_offer.php' ;

require 'Api/freelancer_offer.php' ;

require 'Api/contact_message.php' ;

require 'Api/product.php' ;

require 'Api/home.php' ;

Route::post('test' , function(Request $request){
  return 'hi' ;
});

Route::get('test' , function(){
  //dd(Route::getRoutes()) ;
 return 'hi salah' ;
})->middleware('auth:sanctum');


