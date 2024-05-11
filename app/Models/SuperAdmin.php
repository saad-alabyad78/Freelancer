<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuperAdmin extends BaseModel
{
    use HasFactory;

    protected $with = ['user'] ;

    protected $guarded = [] ;

    public function user():MorphOne
    {
        return $this->morphOne(User::class,"role");
    }
}
