<?php
use App\Models\JobOfferProposal;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobOfferProposalController;

//only freelancer
Route::group([
    'prefix' => 'job-offer-proposal' ,
    'middleware' => [
        'auth:sanctum' ,
        'verify_email' ,
        'role:freelancer' ,
    ]
] , function(){
    Route::post('store' , [JobOfferProposalController::class , 'create']) ;
    Route::put('' , [JobOfferProposalController::class , 'update']) ;
    Route::delete('/{jobOfferProposal}' , [JobOfferProposalController::class , 'delete']);
    Route::get('index', [JobOfferProposalController::class, 'index']);
});

//only company
Route::group([
    'prefix' => 'job-offer-proposal' ,
    'middleware' => [
        'auth:sanctum' ,
        'verify_email' ,
        'role:company' ,
    ]
] , function(){
    Route::post('reject', [JobOfferProposalController::class, 'reject']);
    Route::post('accept/{jobOfferProposal}', [JobOfferProposalController::class, 'accept']);
    Route::get('filter', [JobOfferProposalController::class, 'filter']);
});

//both
Route::group([
    'prefix' => 'job-offer-proposal' ,
    'middleware' => [
        'auth:sanctum' ,
        'verify_email' ,
        'role:company,freelancer' ,
    ]
] , function(){
    Route::get('{jobOfferProposal}' , [JobOfferProposalController::class , 'show']) ;
});
