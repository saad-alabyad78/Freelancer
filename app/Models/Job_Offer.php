<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Job_Offer extends Model
{
    use HasFactory;

    protected $table = 'job_offers';
    protected $fillable = [
        'status',
        'type',
        'max_sallary',
        'min_salary',
        'transportation',
        'health_insurance',
        'military_service',
        'max_age',
        'min_age',
        'gender',
        'industry_name',
        'company_id',
        'job_role_id',
    ];

    public function industry(): HasOne
    {
        return $this->hasOne(Industry::class, 'industry_name', 'name');
    }

    public function skills(): MorphToMany
    {
        return $this->morphToMany(Skill::class, 'skillable');
    }
    public function job_role():HasOne
    {
        return $this->hasOne(Job_Role::class);
    }
    public function company():HasOne
    {
        return $this->hasOne(Company::class) ;
    }
}
