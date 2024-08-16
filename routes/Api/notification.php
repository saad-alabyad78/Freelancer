<?php
use App\Http\Controllers\NotificationController;

Route::prefix('notifications')->group(function(){
    Route::post('/push/{user}' , [NotificationController::class , 'store']) ;
}) ;
