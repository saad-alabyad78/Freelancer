<?php

namespace App\Observers;

use App\Models\Freelancer;
use App\Jobs\DeleteCloudinaryAssetsJob;

class FreelancerObserver
{
    public function deleted(Freelancer $freelancer): void
    {
        var_dump('deleting freelancer observer');
        
        DeleteCloudinaryAssetsJob::dispatchIf(
            $freelancer->profile_image_public_id 
            ||
            $freelancer->background_image_public_id 
        ,[
            $freelancer->profile_image_public_id ,
            $freelancer->background_image_public_id ,
        ]);

        $freelancer->skills()->detach() ;
        $freelancer->user()->delete() ;
    }
}
