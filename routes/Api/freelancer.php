<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Freelancer\FreelancerController;
use App\Http\Controllers\Freelancer\Query\GetPortfolioQuery;
use App\Http\Controllers\Freelancer\Query\GetFreelancerQuery;
use App\Http\Controllers\Freelancer\Commands\CreatePortfolioCommand;
use App\Http\Controllers\Freelancer\Commands\DeletePortfolioCommand;
use App\Http\Controllers\Freelancer\Commands\UpdatePortfolioCommand;
use App\Http\Controllers\Freelancer\Commands\CreateFreelancerCommand;
use App\Http\Controllers\Freelancer\Commands\UpdateFreelancerCommand;
use App\Http\Controllers\Freelancer\Commands\CreatePortfolioFileCommand;
use App\Http\Controllers\Freelancer\Commands\DeletePortfolioFileCommand;
use App\Http\Controllers\Freelancer\Commands\CreatePortfolioImageCommand;
use App\Http\Controllers\Freelancer\Commands\DeletePortfolioImageCommand;
use App\Http\Controllers\Freelancer\Commands\CreateFreelancerImageCommand;
use App\Http\Controllers\Freelancer\Commands\DeleteFreelancerImageCommand;


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
        ->withoutMiddleware('role:no_role');
    Route::put('' , [FreelancerController::class , 'update']);
    });

    //get portfolio // no middlewares 
    Route::get('portfolio/{portfolio:id}' , GetPortfolioQuery::class);
    
    Route::group([
        'prefix'=>'portfolio' , 

        'middleware' => [
            'auth:sanctum' ,
            'verify_email' , 
            'role:freelancer' ,
            
        ],] , function(){
        //create portfolio
        Route::post('store' , CreatePortfolioCommand::class) ;
        //update portfolio
        Route::put('' , UpdatePortfolioCommand::class) ;
        //delete portfolio
        Route::delete('' , DeletePortfolioCommand::class) ;

        //create portfolio file
        Route::post('file/create' , CreatePortfolioFileCommand::class) ;
        //delete portfolio file
        Route::delete('file', DeletePortfolioFileCommand::class);
        Route::post('image/create' , CreatePortfolioImageCommand::class);
        Route::delete('image' , DeletePortfolioImageCommand::class);
    });
    
});