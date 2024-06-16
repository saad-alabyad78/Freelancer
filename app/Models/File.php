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
        'id' ,
        'title' ,   
        'public_id' ,
        'url' ,
        'size' , 
        'filable_id' ,
        'filable_type' ,
        'extention' ,
        'deleted' ,
    ] ;

    public function protfolio():MorphTo
    {
        return $this->morphTo(Portfolio::class , 'filable') ;
    }
}
