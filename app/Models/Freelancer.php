<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Freelancer extends BaseModel
{
    use HasFactory;
    protected $fillable = 
    [
        'profile_image_url',
        'background_image_url',
        'profile_image_id',
        'background_image_id',
        'headline',
        'description',
        'city',
        'gender',
        'date_of_birth',
        'job_role_id',
        'username' ,
    ];

    public function user():MorphOne
    {
        return $this->morphOne(User::class,"role");
    }
    public function job_role():BelongsTo
    {
        return $this->belongsTo(JobRole::class);
    }
    public function skills():MorphToMany
    {
        return $this->morphToMany(Skill::class , 'skillable');
    }
    public function portfolios():HasMany
    {
        return $this->hasMany(Portfolio::class) ;
    }
}