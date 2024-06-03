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

class Company extends BaseModel
{
    use HasFactory;

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

    public function company_phones():HasMany
    {
        return $this->hasMany(CompanyPhone::class ) ;
    }
    public function contact_links():HasMany
    {
        return $this->hasMany(ContactLink::class) ;
    }

    public function gallery_images():MorphMany
    {
        return $this->morphMany(Image::class , 'imagable');
    }

}
