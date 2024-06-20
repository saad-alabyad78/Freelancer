<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'id' ,
        'public_id' ,
        'url' ,
        'imagable_id' ,
        'imagable_type' ,
        'deleted' ,
    ] ;

    public function imagable()
    {
        return $this->morphTo();
    }
}
