<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Like extends Model
{
    protected $fillable = [
        'user_id' ,
        'likable_id' ,
        'likable_type' ,
    ] ;
    use HasFactory;
}
