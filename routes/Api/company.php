<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Company\JobOfferController;
use App\Http\Controllers\Company\JobOfferQueryController;
use App\Http\Controllers\Company\VerifyCompanyController;
use App\Http\Controllers\Company\JobOfferStatusController;
use App\Http\Controllers\Company\GetAllJobOfferForCompanyQuery;

Route::get('company/job_offer/{job_offer}' , [JobOfferController::class , 'show']) ;


Route::group(['prefix' => 'company'] , function()
{
    Route::get('/' , [CompanyController::class , 'index']) ;
    Route::get('/{company:id}' , [CompanyController::class , 'show']) ;
    
    Route::get('job_offers/list-job-offer/guests' , [JobOfferQueryController::class , 'ForGuest']) ;
    Route::post('job_offers/list-job-offer' , [JobOfferQueryController::class , 'ForFreelancer']) ;

    
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


    Route::prefix('verifications')->group(function(){
        Route::middleware([
            'auth:sanctum' ,
            'verify_email' ,
            'role:admin' ,
        ])->group(function(){
            Route::post('/{verification}/accept' , [VerifyCompanyController::class , 'accept']);
            Route::post('/{verification}/reject' , [VerifyCompanyController::class , 'reject']);
        }) ;

        Route::middleware([
            'auth:sanctum' ,
            'verify_email' ,
            'role:admin,company' ,
        ])->group(function(){
            Route::get('/all' , [VerifyCompanyController::class , 'index']);
            Route::get('{verification}' , [VerifyCompanyController::class , 'show']);
            Route::post('/' , [VerifyCompanyController::class , 'store']);
        }) ;
    }) ;
    
});