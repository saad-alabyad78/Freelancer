<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'url' ,
        'public_id' ,
        'size' , 
        'type' ,
        'filable_id' ,
        'filable_type' ,
    ] ;

    public function protfolio():MorphTo
    {
        return $this->morphTo(Portfolio::class , 'filable') ;
    }

    
}
