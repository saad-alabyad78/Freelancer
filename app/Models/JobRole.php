<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class JobRole extends BaseModel
{
    use HasFactory;

    public function job_offers():HasMany
    {
        return $this->hasMany(JobOffer::class) ;
    }

    public function skills():MorphToMany
    {
        return $this->morphToMany(Skill::class , 'skillable');
    }
}
