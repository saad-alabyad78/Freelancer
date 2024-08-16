<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientOffer\ClientOfferController;
use App\Http\Controllers\ClientOffer\ClientOfferAdminController;
use App\Http\Controllers\ClientOffer\ClientOfferFreelancerController;




Route::group([
    'prefix' => 'client-offer/client'
] , function(){
    Route::group([
        'middleware' =>
        [
            'auth:sanctum' ,
            'verify_email' ,
            'role:client' ,
        ]
    ],function(){
        Route::post('client-filter' , [ClientOfferController::class , 'clientFilter']) ;
        Route::post( 'store' , [ClientOfferController::class , 'store']) ;
        Route::get( '{client_offer}' , [ClientOfferController::class , 'show'])
        ->withoutMiddleware(['role:client']) ;
        Route::put( '' , [ClientOfferController::class , 'update']) ;
        Route::delete( '{client_offer}' , [ClientOfferController::class , 'destroy']) ;
    });
});


Route::group([
    'prefix' => 'client-offer/client'
] , function(){
    Route::group([
        'middleware' =>
        [
            'auth:sanctum' ,
            'verify_email' ,
            'role:client' ,
        ]
    ],function(){
        Route::post('/proposals/accept' , [ClientOfferController::class , 'acceptProposal']) ;
        Route::post('/proposals/reject' , [ClientOfferController::class , 'rejectProposals']) ;
        Route::post('/proposals' , [ClientOfferController::class , 'proposals']) ;
    });
});

Route::group([
    'prefix' => 'client-offer/admin'
] , function(){
    Route::group([
        'middleware' =>
        [
            'auth:sanctum' ,
            'verify_email' ,
            'role:admin' ,
        ]
    ],function(){
        Route::post('admin-filter' , [ClientOfferAdminController::class , 'adminFilter']) ;
        Route::post('{client_offer}/accept' , [ClientOfferAdminController::class , 'accept']) ;
        Route::post('{client_offer}/reject' , [ClientOfferAdminController::class , 'reject']) ;
        Route::delete('{client_offer}' , [ClientOfferAdminController::class , 'delete']) ;
    });
});

Route::group([
    'prefix' => 'client-offer/freelancer'
] , function(){
    Route::group([
        'middleware' =>
        [
            'auth:sanctum' ,
            'verify_email' ,
            'role:freelancer' 
        ]
    ],function(){
        Route::get('my-proposals' , [ClientOfferFreelancerController::class , 'myProposals']) ;
        Route::post('freelancer-filter' , [ClientOfferFreelancerController::class , 'freelancerFilter'])
        ->withoutMiddleware(['role:freelancer']) ;
        Route::get( '{client_offer}' , [ClientOfferFreelancerController::class , 'showClientOffer'])
        ->withoutMiddleware(['role:freelancer']) ;
        Route::post('proposal/store' , [ClientOfferFreelancerController::class , 'createProposal']) ;
        Route::put('proposal' , [ClientOfferFreelancerController::class , 'updateProposal']) ;
        Route::delete('proposal/{client_offer_proposal}' , [ClientOfferFreelancerController::class , 'deleteProposal']) ;

    });
});