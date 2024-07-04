<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class View extends Model
{
    protected $fillable = [
        'user_id' ,
        'viewable_id' ,
        'viewable_type' ,
    ] ;
    use HasFactory;
}
