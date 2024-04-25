<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GalleryImage extends Model
{
    use HasFactory;

    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
