<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        return UserResource::make(auth()->user())
            ->response()->withHeaders(['Accept' => 'application/json']);
    }
}
