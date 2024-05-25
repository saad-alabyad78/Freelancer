<?php

namespace App\Observers;

use App\Models\JobOffer;

class JobOfferObserver
{
 
    public function deleting(JobOffer $jobOffer): void
    {
        var_dump('deleting job offer observer') ;

        $jobOffer->skills()->detach() ;
    }

  
}
