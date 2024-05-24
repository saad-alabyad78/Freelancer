<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\Query\GetCompanyQuery;
use App\Http\Controllers\Company\Query\CompanyImageQuery;
use App\Http\Controllers\Company\Query\GalleryImageQuery;
use App\Http\Controllers\Company\Commands\CreateCompanyCommand;
use App\Http\Controllers\Company\Commands\DeleteCompanyCommand;
use App\Http\Controllers\Company\Commands\UpdateCompanyCommand;
use App\Http\Controllers\Company\Commands\UpdateJobOfferCommand;
use App\Http\Controllers\Company\Commands\CreateJob_OfferCommand;
use App\Http\Controllers\Company\Commands\DeleteJob_OfferCommand;
use App\Http\Controllers\Company\Commands\CreateCompanyImageCommand;
use App\Http\Controllers\Company\Commands\CreateGalleryImageCommand;
use App\Http\Controllers\Company\Commands\DeleteCompanyImageCommand;
use App\Http\Controllers\Company\Commands\DeleteGalleryImageCommand;
use App\Http\Controllers\Company\Query\GetAllJob_OfferForCompanyQuery;
use App\Http\Controllers\Company\Query\GetAllJob_OfferQueryForCompany;


Route::group(['prefix' => 'company'] , function()
{
    Route::get('/{company:id}' , GetCompanyQuery::class) ;
    
    Route::group(
        [
            'middleware' => [
                'auth:sanctum' ,
                'verify_email' ,
                'role:company' ,
            ],
        ],
        function(){
            Route::post('/store' , CreateCompanyCommand::class)->name('create-company')
                    ->withoutMiddleware('role:company') 
                           ->middleware('role:no_role');
                           
            Route::delete('/' , DeleteCompanyCommand::class) ;
            Route::put('/' , UpdateCompanyCommand::class) ; 

            Route::post('image/profile' , [CreateCompanyImageCommand::class , 'profile_image']);
            Route::post('image/background' , [CreateCompanyImageCommand::class , 'background_image']);
            Route::post('image/gallery' , CreateGalleryImageCommand::class) ;

            Route::delete('image/profile' , [DeleteCompanyImageCommand::class , 'profile_image']);
            Route::delete('image/background' , [DeleteCompanyImageCommand::class , 'background_image']);
            Route::delete('image/gallery' , DeleteGalleryImageCommand::class) ;
    
            
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
            Route::post('store' , CreateJob_OfferCommand::class) ;
            Route::put('' , UpdateJobOfferCommand::class);
            Route::delete('' , DeleteJob_OfferCommand::class);
            Route::get('my-job-offers' , GetAllJob_OfferForCompanyQuery::class) ;
        });
});