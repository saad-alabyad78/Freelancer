<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Otp\otpPasswordController;
use App\Http\Controllers\Auth\Otp\otpRegisterController;
use App\Http\Controllers\Auth\Otp\otpPhoneNumberController;

//register with otp code

//reset password with otp


Route::group(['prefix' => 'otp' , 'middleware'=> 'throttle:6,1'] , function(){

 Route::post('register' , [otpRegisterController::class , 'register']);
 Route::post('email/verify' , [otpRegisterController::class , 'verify']);
 Route::post('resend' , [otpRegisterController::class , 'sendOtpCode']);
        Route::post('forgot-password' , [otpPasswordController::class , 'forgotPassword']);
        Route::post('reset-password' , [otpPasswordController::class , 'resetPassword']);
});
