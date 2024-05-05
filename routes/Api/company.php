<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\Commands\CreateCompany;
use App\Http\Controllers\Company\Query\GalleryImageQuery;
use App\Http\Controllers\Company\Commands\CreateJob_Offer;


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
            Route::post('/store/{industry}' , CreateCompany::class)
                ->withoutMiddleware('role:company') 
                ->middleware('role:no_role');

            Route::post('/{company:username}/job_offer/store/{industry}' , CreateJob_Offer::class) ;
            
        });
    
    Route::get('gallery' , GalleryImageQuery::class) ;
});