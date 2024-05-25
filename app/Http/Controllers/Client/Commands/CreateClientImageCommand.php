<?php

namespace App\Http\Controllers\Client\Commands;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Constants\CloudFolders;
use App\Http\Controllers\Controller;
use App\Jobs\DeleteCloudinaryAssetsJob;
use App\Http\Requests\Client\CreateClientImageRequest;
/**
 * @group Client Managment
 * 
 **/
class CreateClientImageCommand extends Controller
{
    /**
     *create/update profile image 
     *
     * @return \Illuminate\Http\JsonResponse 
     **/
    public function profile_image(CreateClientImageRequest $request)
    {
        $client = Client::findOrFail(auth()->user()->role_id) ;
        
        //delete the old image 
        DeleteCloudinaryAssetsJob::dispatchIf(
            $client->profile_image_public_id != null ,
            [
                $client->profile_image_public_id ,
            ]
        ) ;
        

        $cloudinaryImage = $request->file('image')->storeOnCloudinary(CloudFolders::CLIENT) ;

        $client->update([
            'profile_image_url' => $cloudinaryImage->getSecurePath() ,
            'profile_image_public_id' => $cloudinaryImage->getPublicId() ,
        ]) ;

        return response()->json([
            'profile_image_url' => $client->profile_image_url ,
        ],201);
    }
    /**
     *create/update background image 
     *
     * @return \Illuminate\Http\JsonResponse
     **/
    public function background_image(CreateClientImageRequest $request)
    {
        $client = Client::findOrFail(auth()->user()->role_id) ;
        
        //delete the old image 
        DeleteCloudinaryAssetsJob::dispatchIf(
            $client->background_image_public_id != null ,
            [
                $client->background_image_public_id ,
            ]
        ) ;
        

        $cloudinaryImage = $request->file('image')->storeOnCloudinary(CloudFolders::CLIENT) ;

        $client->update([
            'background_image_url' => $cloudinaryImage->getSecurePath() ,
            'background_image_public_id' => $cloudinaryImage->getPublicId() ,
        ]) ;

        return response()->json([
            'background_image_url' => $client->background_image_url ,
        ],201);
    }
}
