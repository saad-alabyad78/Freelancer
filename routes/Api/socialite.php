<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ProviderController;

Route::group(
    [
        'middleware' => ['guest'],
        'prefix' => 'provider',
        'as' => 'provider.',
    ],
    function () {
        Route::get('google' , [ProviderController::class , 'google']) ;
    }
);
