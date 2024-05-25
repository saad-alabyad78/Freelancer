<?php

namespace App\Observers;

use App\Models\Company;
use App\Constants\Disks;
use App\Constants\Defaults;
use App\Services\imageService;
use App\Jobs\DeleteCloudinaryAssetsJob;

class CompanyObserver
{
    /**
     * Handle the Company "deleted" event.
     */
    public function deleting(Company $company): void
    {

        var_dump('deleting company observer');
        
        DeleteCloudinaryAssetsJob::dispatchIf(
            $company->profile_image_public_id 
            ||
            $company->background_image_public_id 
        ,[
            $company->profile_image_public_id ,
            $company->background_image_public_id ,
        ]);

        $company->user()->delete() ;
        $company->company_phones()->delete() ;
        $company->contact_links()->delete() ;
      
        $public_ids = $company->gallery_images()->pluck('public_id')->toArray() ;
        
        DeleteCloudinaryAssetsJob::dispatchIf(
            !empty($public_ids) ,
            $public_ids
        ) ;
        
        $company->gallery_images()->delete(); 
    }
}
