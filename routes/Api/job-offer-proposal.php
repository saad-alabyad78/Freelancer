<?php
use App\Models\JobOfferProposal;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobOfferProposalController;

Route::group([
    'prefix' => 'freelancer/job-offer-proposal' ,
    'middleware' => [
        'auth:sanctum' ,
        'verify_email' ,
        'role:freelancer' ,
    ]
] , function(){
    Route::post('store' , [JobOfferProposalController::class , 'create']) ;
});

Route::group([
    'prefix' => 'company/job_offer_proposal' ,
    'middleware' => [
        'auth:sanctum' ,
        'verify_email' ,
        'role:freelancer' ,
    ]
] , function(){});