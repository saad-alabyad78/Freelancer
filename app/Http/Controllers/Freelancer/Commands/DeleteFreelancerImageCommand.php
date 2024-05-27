<?php

namespace App\Http\Controllers\Freelancer\Commands;

use App\Models\Freelancer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\DeleteCloudinaryAssetsJob;
/**
 *@group Freelancer Managment 
 **/
class DeleteFreelancerImageCommand extends Controller
{
    /**
     * delete profile image
     * 
     * @authenticated
     * 
     * return 404 if the image is null
     * 
     * @return \Illuminate\Http\Response | \Illuminate\Routing\ResponseFactory
     */
    public function profile_image()
    {
        $freelancer = Freelancer::findOrFail(auth()->user()->role_id); 
        
        if($freelancer->profile_image_public_id == null){
            return response()->json([
                'message' => 'no image to delete' , 
            ] , 404);
        }

        DeleteCloudinaryAssetsJob::dispatchAfterResponse([
            $freelancer->profile_image_public_id 
        ]);
        
        $freelancer->update([
            'profile_image_public_id' => null ,
            'profile_image_url' => null ,
        ]); ;
        
        return response()->json([
            'profile_image_url' => $freelancer->profile_image_url ,
        ]);
    }
    
    /**
     * delete background image
     * 
     * @authenticated
     * 
     * return 404 if the image is null
     * 
     * @return \Illuminate\Http\Response | \Illuminate\Routing\ResponseFactory
     */
    public function background_image()
    {
        $freelancer = Freelancer::findOrFail(auth()->user()->role_id); 
        
        if($freelancer->background_image_public_id == null){
            return response()->json([
                'message' => 'no image to delete' , 
            ] , 404);
        }

        DeleteCloudinaryAssetsJob::dispatchAfterResponse([
            $freelancer->background_image_public_id ,
        ]);

        $freelancer->update([
            'background_image_url' => null ,
        ]);
        
        return response()->json([
            'background_image_url' => $freelancer->background_image_url ,
        ]);
    }
}
