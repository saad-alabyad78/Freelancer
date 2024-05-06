<?php

namespace App\Observers;

use App\Models\Company;
use App\Constants\Disks;
use App\Constants\Defaults;
use App\Services\imageService;

class CompanyObserver
{
    /**
     * Handle the Company "deleted" event.
     */
    public function deleted(Company $company): void
    {
        $imageServices = new imageService() ;
        
        if($company->profile_image != Defaults::COMPANY_PROFILE_IMAGE){
            $imageServices->delete(Disks::COMPANY , $company->profile_image) ;
        }
        if($company->background_image != Defaults::COMPANY_BACKGROUND_IMAGE){
            $imageServices->delete(Disks::COMPANY , $company->background_image) ;
        }

        $company->company_phones()->delete() ;
        $company->contact_links()->delete() ;
        $company->gallery_images()->delete() ;
    }
}
