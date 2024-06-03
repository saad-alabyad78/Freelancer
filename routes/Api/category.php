<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Category\SkillController;
use App\Http\Controllers\Category\JobRoleController;
use App\Http\Controllers\Category\IndustryController;
use App\Http\Controllers\Category\Skill\Query\SearchAllSkillQuery;
use App\Http\Controllers\Category\Industry\Query\GetAllIndustryQuery;
use App\Http\Controllers\Category\JobRole\Query\SearchAllJobRoleQuery;


Route::group([
    'prefix' => 'category'
] , function(){
    Route::prefix('industry')->group(function(){
        Route::post('search' , [IndustryController::class , 'search']) ;
    });
    Route::prefix('skill')->group(function(){
        Route::post('search' , [SkillController::class , 'search']) ;
    });
    Route::prefix('job_role')->group(function(){
        Route::post('search' , [JobRoleController::class , 'search']) ;
    });
});