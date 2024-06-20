<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JobOffer extends BaseModel
{
    use HasFactory;

    
    protected $fillable = [
        'id' ,
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
        'gender_required' ,
        'proposals_count' ,
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

    public function proposals():BelongsToMany
    {
        return $this->belongsToMany(Freelancer::class , 'job_offer_proposals') ;
    }

    protected $equalFilters = 
    [
        'status',
        'location_type',
        'attendence_type',
        'gender',
        'industry_name',
        'company_id',
        'job_role_id',
    ];
    
    public function scopeFilter($builder , array $filters)
    {

        foreach($filters as $key => $value)
        {
            if($value === null || in_array($key , $this->equalFilters))continue ;
            
            $builder->where($key , $value) ;
        }

        return $builder ;
    }
}
