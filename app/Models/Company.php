<?php

namespace App\Models;

use App\Constants\Defaults;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Company extends BaseModel
{
    use HasFactory;

    protected $with = ['user'] ;

    protected $fillable = 
    [
        'profile_image_url' ,
        'background_image_url' ,
        'profile_image_id' ,
        'background_image_id' ,
        
        'name' ,
        'size' ,
        'description' ,
        'street_address' ,
        'city' ,
        'region' ,
        'username' ,
        'industry_name' ,
    ] ;

    public function user():MorphOne
    {
        return $this->morphOne(User::class,"role") ;
    }
    public function job_offers():HasMany
    {
        return $this->hasMany(JobOffer::class) ;
    }

    public function industry():HasOne
    {
        return $this->hasOne(Industry::class , 'industry_name' , 'name');
    }

    public function gallery_images():MorphMany
    {
        return $this->morphMany(Image::class , 'imagable');
    }
    public function job_offer_proposals():HasManyThrough
    {
        return $this->hasManyThrough(JobOfferProposal::class , JobOffer::class) ;
    }

}
