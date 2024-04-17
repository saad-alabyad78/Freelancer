<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Phone;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

trait SmsOtp
{
    protected int $minutes = 60 ;

    protected function fulfill(Phone $phone , string $message){
        $code = $this->generate(4) ;
        $this->set($phone , $code );
        return $this->send($phone , $code , $message );
    }
    protected function verifyOtp(Phone $phone , string $otp){
        if
        (Hash::check($otp , $phone->phone_number_otp_code) and $phone->phone_number_otp_expired_date >= Carbon::now())
        {
            $phone->phone_number_verified_at = Carbon::now() ;
            $phone->save();
            return true;
        }
        return false;
    }

    protected function generate(int $digits){
        return rand(pow(10 , $digits-1) , pow(10 , $digits)-1) ;
    }

    protected function set(Phone $phone , int $otp ){
        $phone->phone_number_otp_code = Hash::make($otp);
        $phone->phone_number_otp_expired_date = Carbon::now()->addMinutes($this->minutes);
        $phone->save();
    }

    protected function send(Phone $phone , int $otp , $message){
        
        $key = config('sms.gateway.api_key');
        $url = config('sms.gateway.url');

            $response = Http::withHeaders([
                'Authorization' => $key,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                // Add any additional headers as needed
            ])->post($url, [
                // Add your request body here
                "message" => "$message $otp" ,
                "to" => $phone->phone_number ,
            ]);

        return $response ;
    }
}


