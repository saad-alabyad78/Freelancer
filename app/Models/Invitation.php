<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
class Invitation extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id', 'freelancer_id', 'accepted_at', 'rejected_at',
    ];



    protected $dates = ['accepted_at', 'rejected_at'];


    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function jobOffer()
    {
        return $this->belongsTo(JobOffer::class, 'job_offer_id');
    }
}
