<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Phone extends Model
{
    use HasFactory;

    protected $fillable = ['user_id' , 'phone_number'] ;
    
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class) ;
    }
}
