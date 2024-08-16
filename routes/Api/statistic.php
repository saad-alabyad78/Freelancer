<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatisticController;

Route::prefix('statistics')->group(function(){
    Route::get('counts' , [StatisticController::class , 'rolesCount']) ;
}) ;