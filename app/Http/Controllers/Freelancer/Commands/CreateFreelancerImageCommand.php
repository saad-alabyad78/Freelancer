<?php

namespace App\Http\Controllers\Freelancer\Commands;

use App\Models\Freelancer;
use Illuminate\Http\Request;
use App\Constants\CloudFolders;
use App\Http\Controllers\Controller;
use App\Jobs\DeleteCloudinaryAssetsJob;
use App\Http\Requests\Freelancer\CreateFreelancerImageRequest;
/**
 *@group Freelancer Managment 
 **/
class CreateFreelancerImageCommand extends Controller
{
    /**
     *create/update profile image 
     *
     * @authenticated
     * 
     * @return \Illuminate\Http\JsonResponse 
     **/
    public function profile_image(CreateFreelancerImageRequest $request)
    {
        $freelancer = Freelancer::findOrFail(auth()->user()->role_id) ;
        
        //delete the old image 
        DeleteCloudinaryAssetsJob::dispatchIf(
            $freelancer->profile_image_public_id != null ,
            [
                $freelancer->profile_image_public_id ,
            ]
        ) ;

        $cloudinaryImage = $request->file('image')->storeOnCloudinary(CloudFolders::FREELANCER) ;

        $freelancer->update([
            'profile_image_url' => $cloudinaryImage->getSecurePath() ,
            'profile_image_public_id' => $cloudinaryImage->getPublicId() ,
        ]) ;

        return response()->json([
            'profile_image_url' => $freelancer->profile_image_url ,
        ],201);
    }
    /**
     *create/update background image 
     *
     * @authenticated
     * 
     * @return \Illuminate\Http\JsonResponse
     **/
    public function background_image(CreateFreelancerImageRequest $request)
    {
        $freelancer = Freelancer::findOrFail(auth()->user()->role_id) ;
        
        //delete the old image 
        DeleteCloudinaryAssetsJob::dispatchIf(
            $freelancer->background_image_public_id != null ,
            [
                $freelancer->background_image_public_id ,
            ]
        ) ;
        

        $cloudinaryImage = $request->file('image')->storeOnCloudinary(CloudFolders::FREELANCER) ;

        $freelancer->update([
            'background_image_url' => $cloudinaryImage->getSecurePath() ,
            'background_image_public_id' => $cloudinaryImage->getPublicId() ,
        ]) ;

        return response()->json([
            'background_image_url' => $freelancer->background_image_url ,
        ],201);
    }
}
