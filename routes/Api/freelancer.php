<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Freelancer\PortfolioController;
use App\Http\Controllers\Freelancer\FreelancerController;




Route::group([
    'prefix' => 'freelancer'
] , function(){

    Route::get('{freelancer:id}' , [FreelancerController::class , 'show']) ;
    
    Route::group([
        'middleware' => [
            'auth:sanctum' ,
            'verify_email' , 
            'role:freelancer' ,
        ],
    ] , function(){
     Route::post('store' , [FreelancerController::class , 'store'])
        ->withoutMiddleware('role:freelancer')
        ->middleware('role:no_role');
    Route::put('' , [FreelancerController::class , 'update']);
    });

    //get portfolio // no middlewares 
    Route::get('portfolio/{portfolio:id}' , [PortfolioController::class , 'show']);
    
    Route::group([
        'prefix'=>'portfolio' , 

        'middleware' => [
            'auth:sanctum' ,
            'verify_email' , 
            'role:freelancer' ,
            
        ],] , function(){
        Route::post('store' ,  [PortfolioController::class , 'store']) ;
        Route::put('' ,  [PortfolioController::class , 'update']) ;
        Route::delete('' ,  [PortfolioController::class , 'delete']) ;


        Route::withoutMiddleware('role:freelancer')->middleware('role:company,client')
        ->group(function (){
            Route::get('{portfolio}/liked-by-me' , [PortfolioController::class , 'liked_by_me']);
            Route::get('{portfolio}/like' , [PortfolioController::class , 'like']);
            Route::get('{portfolio}/unlike' , [PortfolioController::class , 'unlike']);
            Route::get('{portfolio}/view' , [PortfolioController::class , 'view']);
        });

    });

    
});