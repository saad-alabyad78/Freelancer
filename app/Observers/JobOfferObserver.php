<?php

namespace App\Observers;

use App\Models\JobOffer;

class JobOfferObserver
{
 
    public function deleting(JobOffer $jobOffer): void
    {
        $jobOffer->skills()->detach() ;
    }

  
}
