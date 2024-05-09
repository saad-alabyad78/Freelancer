<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\Query\CompanyImageQuery;
use App\Http\Controllers\Company\Query\GalleryImageQuery;
use App\Http\Controllers\Company\Commands\CreateCompanyCommand;
use App\Http\Controllers\Company\Commands\DeleteCompanyCommand;
use App\Http\Controllers\Company\Commands\CreateJob_OfferCommand;
use App\Http\Controllers\Company\Commands\CreateCompanyImageCommand;
use App\Http\Controllers\Company\Commands\DeleteCompanyImageCommand;


Route::group(['prefix' => 'company'] , function()
{
    Route::group(
        [
            'middleware' => [
                'auth:sanctum' ,
                'verified' ,
                'role:company' ,
            ],
        ],
        function(){
            Route::post('/store/{industry}' , CreateCompanyCommand::class)
                ->withoutMiddleware('role:company') 
                ->middleware('role:no_role');

            Route::post('/{company:username}/job_offer/store/{industry}' , CreateJob_OfferCommand::class) ;
            Route::delete('{company:username}' , DeleteCompanyCommand::class) ;
        });
    
    Route::get('gallery' , GalleryImageQuery::class) ;

    Route::get('image/{company:username}/profile' , [CompanyImageQuery::class , 'profile_image']);
    Route::get('image/{company:username}/background' , [CompanyImageQuery::class , 'background_image']);

    Route::post('image/{company:username}/profile' , [CreateCompanyImageCommand::class , 'profile_image']);
    Route::post('image/{company:username}/background' , [CreateCompanyImageCommand::class , 'background_image']);

    Route::delete('image/{company:username}/profile' , [DeleteCompanyImageCommand::class , 'profile_image']);
    Route::delete('image/{company:username}/background' , [DeleteCompanyImageCommand::class , 'background_image']);
});