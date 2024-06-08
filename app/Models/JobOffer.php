<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class JobOffer extends BaseModel
{
    use HasFactory;

    
    protected $fillable = [
        'status',
        'location_type',
        'attendence_type',
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
        'description' ,
        'military_service_required' ,
        'age_required' ,
    ];


    public function industry(): HasOne
    {
        return $this->hasOne(Industry::class, 'industry_name', 'name');
    }

    public function skills(): MorphToMany
    {
        return $this->morphToMany(Skill::class, 'skillable')
            ->withPivot(['required']);
    }
    public function job_role():BelongsTo
    {
        return $this->belongsTo(JobRole::class);
    }
    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class) ;
    }
}
