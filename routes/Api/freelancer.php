<?php

use Illuminate\Support\Facades\Route;
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

    //get freelancer by id 
    Route::get('{freelancer:id}' , GetFreelancerQuery::class) ;
    
    Route::group([
        'middleware' => [
            'auth:sanctum' ,
            'verify_email' , 
            'role:freelancer' ,
        ],
    ] , function(){
    //create freelancer profile
     Route::post('store' , CreateFreelancerCommand::class)
        ->withoutMiddleware('role:freelancer')
        ->withoutMiddleware('role:no_role');
    //update freelancer profile
    Route::put('' , UpdateFreelancerCommand::class);
    //create/update freelancer profile image
    Route::post('image/profile' , [CreateFreelancerImageCommand::class , 'profile_image']);
    //create/update freelancer background image
    Route::post('image/background' , [CreateFreelancerImageCommand::class , 'background_image']);
    //delete freelancer profile image
    Route::delete('image/profile' , [DeleteFreelancerImageCommand::class , 'profile_image']);
    //delete freelancer profile image
    Route::delete('image/background' , [DeleteFreelancerImageCommand::class , 'background_image']);
       
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