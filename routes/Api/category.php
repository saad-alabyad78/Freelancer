<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Category\Skill\Query\GetAllSkillQuery;
use App\Http\Controllers\Category\Industry\Query\GetAllIndustryQuery;


Route::group([
    'prefix' => 'category'
] , function(){
    Route::get('industry' , GetAllIndustryQuery::class) ;
    Route::post('skills/search' , GetAllSkillQuery::class) ;
});