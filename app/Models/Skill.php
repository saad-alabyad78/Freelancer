<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Skill extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name'] ;

    public function freelancers():MorphToMany
    {
        return $this->morphToMany(Freelancer::class , 'skillable');
    }

    public function job_roles():MorphToMany
    {
        return $this->morphToMany(JobRole::class , 'skillable');
    }

    public function job_offers():MorphToMany
    {
        return $this->morphToMany(JobOffer::class , 'skillable');
    }
}
