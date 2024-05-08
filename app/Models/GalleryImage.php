<?php

namespace App\Models;

use App\Constants\ImageUrls;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GalleryImage extends Model
{
    use HasFactory;

    protected $fillable = ['name'] ;

    public function Url():Attribute
    {
        return Attribute::get(fn () =>
            'api/company/gallery?image='.$this->attributes['name'] ) ;
    }
    
    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
