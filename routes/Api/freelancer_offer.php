<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FreelancerOffer\FreelancerOfferController;
use App\Http\Controllers\FreelancerOffer\FreelancerOfferAdminController;
use App\Http\Controllers\FreelancerOffer\FreelancerOfferClientController;





Route::group([
    'prefix' => 'freelancer-offer/freelancer'
] , function(){
    Route::group([
        'middleware' =>
        [
            'auth:sanctum' ,
            'verify_email' ,
            'role:freelancer' ,
        ]
    ],function(){
        Route::post('freelancer-filter' , [FreelancerOfferController::class , 'freelancerFilter']) ;
        Route::post( 'store' , [FreelancerOfferController::class , 'store']) ;
        Route::get( '{freelancer_offer}' , [FreelancerOfferController::class , 'show']) ;
        Route::put( '' , [FreelancerOfferController::class , 'update']) ;
        Route::delete( '{freelancer_offer}' , [FreelancerOfferController::class , 'destroy']) ;
    });
});

Route::group([
    'prefix' => 'freelancer-offer/admin'
] , function(){
    Route::group([
        'middleware' =>
        [
            'auth:sanctum' ,
            'verify_email' ,
            'role:admin' ,
        ]
    ],function(){
        Route::post('admin-filter' , [FreelancerOfferAdminController::class , 'adminFilter']) ;
        Route::post('{freelancer_offer}/accept' , [FreelancerOfferAdminController::class , 'accept']) ;
        Route::post('{freelancer_offer}/reject' , [FreelancerOfferAdminController::class , 'reject']) ;
        Route::delete('{freelancer_offer}' , [FreelancerOfferAdminController::class , 'delete']) ;
    });
});

//todo need testing
Route::group([
    'prefix' => 'freelancer-offer/client'
] , function(){
    Route::group([
        'middleware' =>
        [
            'auth:sanctum' ,
            'verify_email' ,
            'role:client' 
        ]
    ],function(){
        Route::post('client-filter' , [FreelancerOfferClientController::class , 'clientFilter']) ;
        Route::get( '{freelancer_offer}' , [FreelancerOfferClientController::class , 'showFreelancerOffer']) ;
        Route::post('proposal/store' , [FreelancerOfferClientController::class , 'createProposal']) ;
        Route::put('proposal' , [FreelancerOfferClientController::class , 'updateProposal']) ;
        Route::delete('proposal/{freelancer_offer_proposal}' , [FreelancerOfferClientController::class , 'deleteProposal']) ;

    });
});