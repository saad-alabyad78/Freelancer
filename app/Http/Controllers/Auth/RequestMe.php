<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\Auth\UserResource;
/**
 * @group Auth Managment
 **/
class RequestMe extends Controller
{
    /**
     * Who Am I
     * 
     * @authenticated
     * 
     * @apiResource App\Http\Resources\Auth\UserResource
     * @apiResourceModel App\Models\User
     * 
     * @return \Illuminate\Http\JsonResponse
     **/
    public function __invoke()
    {
        $userId = auth()->id();
        $userRole = auth()->user()->role; 

        // Generate a unique cache key based on the user's ID
        $cacheKey =  $userId . '_' . $userRole;

        // Remember the response in the cache for a given time, e.g., 60 seconds
        $response = Cache::remember($cacheKey, 60*60*24*7, function () {
            return UserResource::make(auth()->user());
        });

        // Return the response with the necessary headers
        return $response->response()->withHeaders(['Accept' => 'application/json']);
    }
}
