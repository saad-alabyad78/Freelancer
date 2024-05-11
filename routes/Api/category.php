<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Category\Skill\Query\SearchAllSkillQuery;
use App\Http\Controllers\Category\Industry\Query\GetAllIndustryQuery;
use App\Http\Controllers\Category\JobRole\Query\SearchAllJobRoleQuery;


Route::group([
    'prefix' => 'category'
] , function(){
    Route::get('industry' , GetAllIndustryQuery::class) ;
    Route::post('skills/search' , SearchAllSkillQuery::class) ;
    Route::post('job_roles/search' , SearchAllJobRoleQuery::class) ;
});