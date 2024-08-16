<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rate extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_id' ,
        'model_type' ,
        'description' ,
        'number' ,
        'user_id' ,
    ] ;

    public function model():MorphTo
    {
        return $this->morphTo() ;
    }
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class) ;
    }
}
