<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\Commands\CreateCompany;

Route::group(
    [
        'prefix' => 'company' ,
        'middleware' => [
            'auth:sanctum' ,
            'verified' ,
            'role:no_role' ,
        ]
    ],//TODO : middlewares 
    function(){
        Route::post('store' , CreateCompany::class) ;
    });