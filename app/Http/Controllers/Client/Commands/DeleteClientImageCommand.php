<?php

namespace App\Http\Controllers\Client\Commands;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\DeleteCloudinaryAssetsJob;
/**
 * @group Client Managment
 * 
 **/
class DeleteClientImageCommand extends Controller
{
    /**
     * delete profile image
     * 
     * return 404 if the image is null
     * 
     * @return \Illuminate\Http\Response | \Illuminate\Routing\ResponseFactory
     */
    public function profile_image()
    {
        $client = Client::findOrFail(auth()->user()->role_id); 
        
        if($client->profile_image_public_id == null){
            return response()->json([
                'message' => 'no image to delete' , 
            ] , 404);
        }

        DeleteCloudinaryAssetsJob::dispatchAfterResponse([
            $client->profile_image_public_id 
        ]);
        
        $client->update([
            'profile_image_public_id' => null ,
            'profile_image_url' => null ,
        ]); ;
        
        return response()->json([
            'profile_image_url' => $client->profile_image_url ,
        ]);
    }
    
    /**
     * delete background image
     * 
     * return 404 if the image is null
     * 
     * @return \Illuminate\Http\Response | \Illuminate\Routing\ResponseFactory
     */
    public function background_image()
    {
        $client = Client::findOrFail(auth()->user()->role_id); 
        
        if($client->background_image_public_id == null){
            return response()->json([
                'message' => 'no image to delete' , 
            ] , 404);
        }

        DeleteCloudinaryAssetsJob::dispatchAfterResponse([
            $client->background_image_public_id ,
        ]);

        $client->update([
            'background_image_url' => null ,
        ]);
        
        return response()->json([
            'background_image_url' => $client->background_image_url ,
        ]);
    }
}
