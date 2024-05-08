<?php

use App\Models\Company;
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
    $company = new Company();
    return $company->background_image ;
});

Route::get('test' , function(){
  $xmlService = new xmlService('dynamics/job_roles.xml') ;

  $job_roles = xmlService::toJson($xmlService->xmlContent)->job_role ;

  $tt = [] ;
  foreach($job_roles as $job_role)
  {
    return $job_role ;
  }
});