<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Category\SkillController;

use App\Http\Controllers\Category\JobRoleController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Category\IndustryController;
use App\Http\Controllers\Category\SubCategoryController;




Route::group([
    'prefix' => 'category'
] , function(){
    Route::prefix('industry')->group(function(){
        Route::post('search' , [IndustryController::class , 'search']) ;
        Route::post('chunk/insert' , [IndustryController::class , 'chunkInsert'])
            ->middleware('throttle:200,1');
    });
    Route::prefix('skill')->group(function(){
        Route::post('search' , [SkillController::class , 'search']) ;
        Route::post('chunk/insert' , [SkillController::class , 'chunkInsert'])
            ->middleware('throttle:200,1');
    });
    Route::prefix('job_role')->group(function(){
        Route::post('search' , [JobRoleController::class , 'search']) ;
        Route::post('chunk/insert' , [JobRoleController::class , 'chunkInsert'])
            ->middleware('throttle:200,1');
    });

    //the protection in the requests
    Route::prefix('category')->group(function(){
        Route::get('' , [CategoryController::class , 'index']);
        Route::get('{category}' , [CategoryController::class , 'show']);
        Route::middleware(
        [
            'auth:sanctum' ,
            'verify_email' ,
            'role:admin'
        ])->group(function(){
            Route::post('store' , [CategoryController::class , 'store']);
            Route::put('{category}' , [CategoryController::class , 'update']);
            Route::delete('{category}' , [CategoryController::class , 'destroy']);
        });
    });
    Route::prefix('sub-category')->group(function(){
        Route::get('' , [SubCategoryController::class , 'index']);
        Route::get('{category}' , [SubCategoryController::class , 'show']);
        Route::middleware(
            [
                'auth:sanctum' ,
                'verify_email' ,
                'role:admin'
            ])->group(function(){
                Route::post('store' , [SubCategoryController::class , 'store']);
                Route::put('{sub_category}' , [SubCategoryController::class , 'update']);
                Route::delete('{sub_category}' , [SubCategoryController::class , 'destroy']);
            });
    });
});