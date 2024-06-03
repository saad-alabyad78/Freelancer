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

        $company->user()->delete() ;
        $company->company_phones()->delete() ;
        $company->contact_links()->delete() ;
      
        $company->gallery_images()->update([
            'deleted' => true ,
            'imagable_id' => null , 
            'imagable_type' => null ,
        ]) ;
    }
}
