<?php
use App\Http\Controllers\NotificationController;

Route::prefix('notifications')->group(function(){
    Route::get('/{user}' , [NotificationController::class , 'index']) ;
}) ;
