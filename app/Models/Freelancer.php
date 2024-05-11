<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Freelancer extends BaseModel
{
    use HasFactory;

    public function user():MorphOne
    {
        return $this->morphOne(User::class,"role");
    }
}
