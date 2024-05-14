<?php

namespace App\Models;

use App\Constants\ImageUrls;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GalleryImage extends BaseModel
{
    use HasFactory;

    protected $fillable = ['id' , 'url' , 'public_id' , 'company_id'] ;
    
    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
