<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Job_Role extends Model
{
    use HasFactory;

    protected $table = 'job_roles' ;

    public function skills():MorphToMany
    {
        return $this->morphToMany(Skill::class , 'skillable');
    }
}
