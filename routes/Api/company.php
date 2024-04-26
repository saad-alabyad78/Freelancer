<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\Commands\CreateCompany;
use App\Http\Controllers\Company\Query\GalleryImageQuery;


Route::group(['prefix' => 'company'] , function()
{
    Route::group(
        [
            'middleware' => [
                'auth:sanctum' ,
                'verified' ,
                'role:no_role' ,
            ],
        ],
        function(){
            Route::post('store' , CreateCompany::class) ;
            
        });
    
    Route::get('gallery' , GalleryImageQuery::class) ;
});

