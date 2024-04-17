<?php

namespace App\Http\Controllers\Profile;

use App\Models\User;
use App\Models\Phone;
use App\Traits\SmsOtp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PhoneNumberRequest;
use App\Http\Requests\Auth\VerifyPhoneNumberRequest;

class PhoneController extends Controller
{
    use SmsOtp;
    public function register(PhoneNumberRequest $request)
    {
        $validated = $request->validated();

        //get the user
        $user = User::where("email", $validated['email'])
        ->first();

        $phone = Phone::Create(
            [
                'user_id' => $user->id , 
                'phone_number' => $validated['phone_number']
            ]
        ) ;

        //$user->phone->save ? 

        //send sms 
        return $this->fulfill($phone , "pleas use the code to verify you phone number ");

        //TODO: dont return the otp code
        //return response()->json($user, 201);
    }

    public function verify(VerifyPhoneNumberRequest $request)
    {
        $validated = $request->validated();

        $user = User::where('email' , $validated['email'])->first() ;

        $phone = Phone::where('user_id' , $user->id)
             ->where('phone_number' , $validated['phone_number'])->first();

        if($phone == null){
            return response()->json([
                'message' => 'no match for the user and the phone number' 
            ]);
        }

        $ok = $this->verifyOtp($phone , $validated['phone_number_otp_code']);

        if(!$ok)
        {
            return response()->json([
                'error' => ' try again please' ,
            ]);
        }

        return response()->json([
            'message' => 'user phone number has been verified successfully ' ,
        ]) ;
    }

    public function delete()
    {
        //TODO:
    }
}
