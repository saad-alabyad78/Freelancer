<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Company\JobOfferController;
use App\Http\Controllers\Company\JobOfferQueryController;
use App\Http\Controllers\Company\JobOfferStatusController;
use App\Http\Controllers\Company\GetAllJobOfferForCompanyQuery;

Route::group(['prefix' => 'company'] , function()
{
    Route::get('/{company:id}' , [CompanyController::class , 'show']) ;
    
    Route::get('job_offer/list-job-offer' , [JobOfferQueryController::class , 'ForGuest']) ;
    Route::post('job_offer/list-job-offer' , [JobOfferQueryController::class , 'ForFreelancer'])
        ->middleware([
            'auth:sanctum' ,
            'verify_email' ,
            'role:freelancer' ,
            ]) ; 

    
    Route::group(
        [
            'middleware' => [
                'auth:sanctum' ,
                'verify_email' ,
                'role:company' ,
            ],
        ],
        function(){
            Route::post('store' , [CompanyController::class , 'store'])
                ->withoutMiddleware('role:company')
                ->middleware('role:no_role') ;
                
            Route::put('' , [CompanyController::class , 'update']) ;
            Route::delete('' , [CompanyController::class , 'delete']) ;    
        });
    
    Route::group(
        [
            'prefix' => 'job_offer' ,
    
            'middleware' => [
                'auth:sanctum' ,
                'verify_email' ,
                'role:company' ,
            ],
        ],
        function(){
            Route::post('store' , [JobOfferController::class , 'store']);
            Route::put('' ,  [JobOfferController::class , 'update']);
            Route::delete('' ,  [JobOfferController::class , 'delete']);
            
            //todo : test 
            Route::post('my-job-offers' , [JobOfferQueryController::class , 'ForOwner']) ;

            //todo : test
            Route::post('status/change' , [JobOfferStatusController::class , 'change']);
        });
});