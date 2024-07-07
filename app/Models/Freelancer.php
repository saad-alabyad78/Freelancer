<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function Age():Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->date_of_birth)->age
        );
    }

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
    public function job_offers():BelongsToMany
    {
        return $this->belongsToMany(JobOffer::class , 'job_offer_proposals') ;
    }
    public function client_offer_proposals():HasMany
    {
        return $this->hasMany(ClientOfferProposal::class) ;
    }
}