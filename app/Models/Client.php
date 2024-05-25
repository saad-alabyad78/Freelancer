<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends BaseModel
{
    use HasFactory;

    protected $fillable = 
    [
        'profile_image_url' ,
        'background_image_url',
        'profile_image_public_id',
        'background_image_public_id',
        'gender',
        'date_of_birth',
        'city',
        'username' ,
    ];

    public function user():MorphOne
    {
        return $this->morphOne(User::class,"role");
    }
}
