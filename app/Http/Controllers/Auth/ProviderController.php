<?php

namespace App\Http\Controllers\Auth;

use Throwable;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Requests\Auth\ProviderRequest;
/**
 * @group Auth Google
 * 
 * API to sign with google oauth2.
 **/

class ProviderController extends Controller
{

    public function google(ProviderRequest $request)
    {
        try{
            $json = Socialite::driver('google')->userFromToken($request->token) ;

            $user = User::firstOrCreate([
                'email' => $json->email ,
            ],[
                'provider' => 'google' ,
                'first_name' => $json->name ,
                'password' => Hash::make(Str::random(24)),
            ]);

            $user->email_verified_at = Carbon::now()->format('Y-m-d H:i:s');
            $user->save();
            
            return response()->json([
                'access_token' => $user->createToken('google' . '_token')->plainTextToken ,
            ]);

        }catch(Throwable $e){
            return response()->json([
                'provider' => 'google' ,
                'error' => 'failed to authenticate the user with google oauth token ' ,
                'message' => $e->getMessage() ,
            ] , 401 );
        }
    }
}
