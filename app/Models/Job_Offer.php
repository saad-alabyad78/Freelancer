<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Job_Offer extends Model
{
    use HasFactory;

    public function industry():HasOne
    {
        return $this->hasOne(Industry::class , 'industry_name' , 'name');
    }

    public function skills():MorphToMany
    {
        return $this->morphToMany(Skill::class , 'skillable') ;
    }
}
