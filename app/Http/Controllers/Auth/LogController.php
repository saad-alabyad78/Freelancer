<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LogInRequest;
use App\Http\Resources\Auth\UserResource;
use Illuminate\Support\Facades\Cache;

/**
 * @group Auth Managment
 *
 * APIs to manage the login and logout
 **/
class LogController extends Controller
{

    /**
     * @noauthintication
     *
     */
    public function login(LogInRequest $request)
    {
        //TODO: the otp code must be when loggin in not when register
        //what if log in with not verefied email

        $user = User::where('email' , $request->email)->first();

        if(! $user->email_verified_at){
            return response()->json([
                'error'=> 'you need to verify your email' ,
            ] , 401);
        }

       if(!$user || !Hash::check($request->password , $user->password)){
            return response()->json([
                'error' => 'the provided credentials are incorrect :(' ,
            ] , 401 );
        }

        $device = substr($request->userAgent() ?? '' , 0 , 255) ;

        $user->markOnline();
        broadcast(new UserOnlineStatusUpdated($user->id, true));

        return response()->json([
            'user' => UserResource::make($user) ,
            'access_token' => $user->createToken($device)->plainTextToken ,
        ]);
    }
    /**
     * @authenticated
    **/
    public function logout(Request $request)
    {
        $user = auth('sanctum')->user();
        $user->tokens()->delete();

        $user->markOffline();
        broadcast(new UserOnlineStatusUpdated($user->id, false));

        return response()->json(['message' => 'Logout successful']);
    }
}
