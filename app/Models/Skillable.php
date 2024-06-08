<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Skillable extends Model
{
    use HasFactory;
    protected $fillable = 
    [
        'skill_id' ,
        'skillable_id' ,
        'skillable_type' ,
        'required' ,
    ] ;
}
