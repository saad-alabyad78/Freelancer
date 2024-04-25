<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $guarded = [] ;

    protected $with = ['user' , 'contact_links'] ;

    public function user():MorphOne
    {
        return $this->morphOne(User::class,"role") ;
    }

    public function company_phones():HasMany
    {
        return $this->hasMany(CompanyPhone::class , 'company_id') ;
    }
    public function contact_links():HasMany
    {
        return $this->hasMany(ContactLink::class , 'company_id') ;
    }
    public function gallery_images():HasMany
    {
        return $this->hasMany(GalleryImage::class , 'company_id') ;
    }
}
