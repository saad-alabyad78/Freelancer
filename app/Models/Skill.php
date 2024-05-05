<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Skill extends Model
{
    use HasFactory;

    public function job_roles():MorphToMany
    {
        return $this->morphToMany(Job_Role::class , 'skillable');
    }

    public function job_offers():MorphToMany
    {
        return $this->morphToMany(Job_Offer::class , 'skillable');
    }
}
