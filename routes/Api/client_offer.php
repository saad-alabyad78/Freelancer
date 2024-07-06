<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Category\SkillController;
use App\Http\Controllers\Category\JobRoleController;

use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Category\IndustryController;
use App\Http\Controllers\Category\SubCategoryController;
use App\Http\Controllers\ClientOffer\ClientOfferController;




Route::group([
    'prefix' => 'client-offer'
] , function(){
    Route::group([
        'middlware' =>
        [
            'auth:sanctum' ,
            'verify_email' ,
            'role:client' ,
        ]
    ],function(){
        Route::post('filter' , [ClientOfferController::class , 'filter']) ;
        Route::post( 'store' , [ClientOfferController::class , 'store']) ;
        Route::get( '{client_offer}' , [ClientOfferController::class , 'show']) ;
        Route::put( '' , [ClientOfferController::class , 'update']) ;
        Route::delete( '{client_offer}' , [ClientOfferController::class , 'destroy']) ;
    });
});