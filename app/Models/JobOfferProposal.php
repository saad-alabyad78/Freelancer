<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Factories\BelongsToRelationship;

class JobOfferProposal extends BaseModel
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
    public function company():HasOneThrough
    {
        return $this->hasOneThrough(Company::class , JobOffer::class);
    }
    public function freelancer():BelongsTo
    {
        return $this->belongsTo(Freelancer::class);
    }
    public function scopeFilterByJobOfferId($query, $jobOfferId)
    {
        if ($jobOfferId) {
            return $query->where('job_offer_id', $jobOfferId);
        }
        return $query;
    }
}
