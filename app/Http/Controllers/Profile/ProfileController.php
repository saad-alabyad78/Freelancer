<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\ProfileRequest;
use App\Http\Resources\Profile\ProfileResource;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function store(ProfileRequest $request)
    {
        $data = $request->validated();
        $data['avatar_image'] = $this->image($request->avatar_image) ;
        $data['cover_image']  = $this->image($request->cover_image) ;
        $data['user_id'] = Auth::id();

        return ProfileResource::make(Profile::create($data)) ;
        
    }

    public function update(ProfileRequest $request)
    {

    }

    private function image($image , $gender = 'male')
    {
        if(!$image){
            return config('images.profile.avatar.'.$gender) ;
        }

        $image_name = Str::random(32 , ) . '.' .
        $image->getClientOriginalExtension() ;

         Storage::disk('public')
         ->put($image_name , file_get_contents($image));

         return $image_name ;
    } 
}
