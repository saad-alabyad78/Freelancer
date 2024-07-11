<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientOffer\ClientOfferController;
use App\Http\Controllers\ClientOffer\ClientOfferAdminController;
use App\Http\Controllers\ClientOffer\ClientOfferFreelancerController;




Route::group([
    'prefix' => 'client-offer/client'
] , function(){
    Route::group([
        'middlware' =>
        [
            'auth:sanctum' ,
            'verify_email' ,
            'role:client' ,
        ]
    ],function(){
        Route::post('client-filter' , [ClientOfferController::class , 'clientFilter']) ;
        Route::post( 'store' , [ClientOfferController::class , 'store']) ;
        Route::get( '{client_offer}' , [ClientOfferController::class , 'show']) ;
        Route::put( '' , [ClientOfferController::class , 'update']) ;
        Route::delete( '{client_offer}' , [ClientOfferController::class , 'destroy']) ;
    });
});

Route::group([
    'prefix' => 'client-offer/admin'
] , function(){
    Route::group([
        'middlware' =>
        [
            'auth:sanctum' ,
            'verify_email' ,
            'role:admin' ,
        ]
    ],function(){
        Route::post('admin-filter' , [ClientOfferAdminController::class , 'adminFilter']) ;
        Route::post('{client_offer}/accept' , [ClientOfferAdminController::class , 'accept']) ;
        Route::post('{client_offer}/reject' , [ClientOfferAdminController::class , 'accept']) ;
        Route::delete('{client_offer}' , [ClientOfferAdminController::class , 'delete']) ;
        //Route::post('accept' , )
        //Route::post('reject' , )
    });
});

//todo need testing
Route::group([
    'prefix' => 'client-offer/freelancer'
] , function(){
    Route::group([
        'middlware' =>
        [
            'auth:sanctum' ,
            'verify_email' ,
            'role:freelancer' ,
        ]
    ],function(){
        Route::post('freelancer-filter' , [ClientOfferFreelancerController::class , 'freelancerFilter']) ;
        Route::get( '{client_offer}' , [ClientOfferFreelancerController::class , 'showClientOffer']) ;
        Route::post('proposal/store' , [ClientOfferFreelancerController::class , 'createProposal']) ;
        Route::put('propose' , [ClientOfferFreelancerController::class , 'updateProposal']) ;
        Route::delete('{client_offer_proposal}' , [ClientOfferFreelancerController::class , 'deleteProposal']) ;

    });
});