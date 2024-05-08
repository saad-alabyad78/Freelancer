<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Category\Industry\Query\GetAllIndustryQuery;


Route::group([
    'prefix' => 'category'
] , function(){
    Route::get('indusrty' , GetAllIndustryQuery::class) ;
});