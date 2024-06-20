<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobOfferProposal extends Pivot
{
    protected $fillable = [
        'id' ,
        'freelancer_id' ,
        'job_offer_id' ,
        'rejected_at' ,
        'accepted_at' ,
        'message' ,
    ] ;

    public function job_offer():BelongsTo
    {
        return $this->belongsTo(JobOffer::class) ;
    }
    public function freelancer():BelongsTo
    {
        return $this->belongsTo(Freelancer::class);
    }
}
