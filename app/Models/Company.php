<?php

namespace App\Models;

use App\Constants\Defaults;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $fillable = 
    [
        'profile_iamge' ,
        'background_image' ,
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
    public function gallery_images():HasMany
    {
        return $this->hasMany(GalleryImage::class) ;
    }

    public function profileImage():Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes['profile_image'] ?? Defaults::COMPANY_PROFILE_IMAGE ,
        );
    }

    public function backgroundImage():Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes['background_image'] ?? Defaults::COMPANY_PROFILE_IMAGE ,
        );
    }

    public function profileImageUrl():Attribute
    {
        return Attribute::get(fn()=>
            config('app.url') . 'api/company/image/'.$this->attributes['username'].'/profile'
        );
    }
    public function backgroundImageUrl():Attribute
    {
        return Attribute::get(fn()=>
            config('app.url') . 'api/company/image/'.$this->attributes['username'].'/background'
        );
    }
}
