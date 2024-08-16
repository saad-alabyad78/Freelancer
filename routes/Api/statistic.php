<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatisticController;

Route::prefix('statistics')->group(function(){
    Route::get('roles-count' , [StatisticController::class , 'rolesCount']) ;
}) ;