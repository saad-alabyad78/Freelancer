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

    Route::get('{freelancer:id}' , GetFreelancerQuery::class) ;
    
    Route::group([
        'middleware' => [
            'auth:sanctum' ,
            'verify_email' , 
            'role:freelancer' ,
        ],
    ] , function(){
     Route::post('store' , CreateFreelancerCommand::class)
        ->withoutMiddleware('role:freelancer')
        ->withoutMiddleware('role:no_role');
        
    Route::put('' , UpdateFreelancerCommand::class);
    
    Route::post('image/profile' , [CreateFreelancerImageCommand::class , 'profile_image']);
    Route::post('image/background' , [CreateFreelancerImageCommand::class , 'background_image']);

    Route::delete('image/profile' , [DeleteFreelancerImageCommand::class , 'profile_image']);
    Route::delete('image/background' , [DeleteFreelancerImageCommand::class , 'background_image']);
       
    });


    Route::group([
        'prefix'=>'portfolio' , 

        'middleware' => [
            'auth:sanctum' ,
            'verify_email' , 
            'role:freelancer' ,
            
        ],] , function(){
        //get portfolio // no middlewares
        Route::get('{portfolio:id}' , GetPortfolioQuery::class)
        ->withoutMiddleware(['auth:sanctum' , 'role:freelancer' , 'verify_email']) ;
        Route::post('store' , CreatePortfolioCommand::class) ;
        Route::put('' , UpdatePortfolioCommand::class) ;
        Route::delete('' , DeletePortfolioCommand::class) ;

        //create file
        Route::post('file/create' , CreatePortfolioFileCommand::class) ;
        //delete file
        Route::delete('file', DeletePortfolioFileCommand::class);
        //create image
        Route::post('image/create' , CreatePortfolioImageCommand::class);
        //delete image
        Route::delete('image' , DeletePortfolioImageCommand::class);
    });
    
});