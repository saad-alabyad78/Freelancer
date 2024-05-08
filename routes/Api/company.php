<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\Query\GalleryImageQuery;
use App\Http\Controllers\Company\Commands\CreateCompanyCommand;
use App\Http\Controllers\Company\Commands\CreateJob_OfferCommand;


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
            
        });
    
    Route::get('gallery' , GalleryImageQuery::class) ;
});