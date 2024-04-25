<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $fillable = 
    [
        'name' ,
        'size' ,
        'description' ,
        'street_address' ,
        'city' ,
        'region' ,
        'username' ,
    ] ;

    protected $with = ['user' , 'contact_links'] ;

    public function user():MorphOne
    {
        return $this->morphOne(User::class,"role") ;
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
}
