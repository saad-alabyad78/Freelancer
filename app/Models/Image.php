<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'url' ,
        'public_id' ,
        'size' , 
        'type' ,
        'imagable_id' ,
        'imagable_type' ,
        'extention' ,
    ] ;

    public function protfolio():MorphTo
    {
        return $this->morphTo(Portfolio::class , 'filable') ;
    }
}
