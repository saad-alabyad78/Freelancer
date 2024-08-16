<?php

namespace App\Http\Controllers\Auth\Otp;

use App\Models\User;
use App\Mail\WelcomeMail;
use App\Traits\GmailVerifyOtp;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\Auth\UserResource;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResendOtpRequest;
use App\Http\Requests\Auth\VerifyEmailRequest;

/**
 * @group Auth Management
 **/
class otpRegisterController extends Controller
{
    use GmailVerifyOtp;

    public function register(RegisterRequest $request)
    {
        //password hashed in the model
        $user = User::create($request->validated());

        $this->fulfill($user , ' this is your verification code ');

        return response()->json([
            'message' => 'otp verification code has been sent to ' . $user->email ,
            'user' => UserResource::make($user)
            ] , 201);
    }

    public function sendOtpCode(ResendOtpRequest $request)
    {
        $user = User::where('email' , $request->validated()['email'])->first() ;

        $this->fulfill($user , ' this is your verification code ');

        return response()->json([
            'message' => 'otp verification code has been sent to ' . $user->email
            ] , 201);
    }

    public function verify(VerifyEmailRequest $request)
    {
        $validated = $request->validated();

        $user = User::where('email' , $validated['email'])->first() ;

        $ok = $this->verifyOtp($user , $validated['email_otp_code']);

        if(!$ok)
        {
            return response()->json([
                'error' => ' try again please' ,
            ] , 401);
        }

        $device = substr($request->userAgent() ?? '' , 0 , 255) ;

        Mail::to($user)->send(new WelcomeMail($user->full_name ?? " no name ")) ;

        return response()->json([
            'message' => 'user email has been verified successfully ' ,
            'access_token' => $user->createToken($device)->plainTextToken ,
        ]) ;
    }
}
