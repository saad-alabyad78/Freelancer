<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JobOffer extends BaseModel
{
    use HasFactory;

    protected $with = ['skills'] ;
    
    protected $fillable = [
        'id' ,
        'status',
        'location_type',
        'attendance_type',
        'max_salary',
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
    
    /*
    'military_service_required' ,
    */
    protected $Filters = 
    [
        'status'=> '='  ,
        'location_type'=> '='  ,
        'attendance_type'=> '='  ,
        'gender'=> '='  ,
        'industry_name'=> '='  ,
        'company_id'=> '='  ,
        'job_role_id'=> '='  ,
        'transportation' => '=' ,
        'health_insurance' => '=' ,
        'military_service' => '=' ,
        'max_salary' => '<=' ,
        'min_salary' => '>=' ,
        'max_age' => '<=' ,
        'min_age' => '>=' ,
    ];
    
    public function scopeFilter(Builder $builder , array $filters , Freelancer $freelancer = null)
    {

        foreach($filters as $key => $value)
        {
            if($value === null || !array_key_exists($key , $this->Filters))continue ;
             
            $builder->where( $key , $this->Filters[$key] ,$value) ;
        }

        if($freelancer != null and $filters['i_can_apply_for_it'] ?? false )
        {
            //same as my job role 
            $builder->where('job_role_id' , $freelancer->job_role_id) ;

            //same as my gender if gender is required 
            $builder->whereNull('gender_required')
                    ->orWhereNull('gender')
                    ->orWhere('gender' , $freelancer->gender) ;
                    
            //my age is between min and max ,,,,, if age required
            
            $builder->whereNull('age_required')
                    ->orWhereNull(['max_age' , 'min_age'])
                    ->where(function($query)use($freelancer){
                        $query->whereNull('max_age')
                              ->orWhere('max_age' , '>=' , 1) ;
                    })
                    ->where(function($query)use($freelancer){
                        $query->whereNull('min_age')
                              ->orWhere('min_age' , '<=' , $freelancer->age) ;
                    });

            $freelancerSkills = $freelancer->skills()->pluck('id')->toArray() ;

            //the required skills all are in my skills 
            $builder->leftJoin('skillables' , function($join){
                $join->on('job_offers.id' , '=' , 'skillables.skillable_id')
                    ->where('skillable_type' , '=' , JobOffer::class);
            })
            ->where(function($query) use($freelancerSkills){
                $query->where('skillables.required' , true)
                      ->whereNotIn('skillables.skill_id' , $freelancerSkills) ;
            })
            // ->orWhere(function($quere){
            //     $quere->where('skillables.required' , false) ;
            // })
            ->select('job_offers.*')
            ->groupBy('job_offers.id')
            ->havingRaw('COUNT(skillables.skill_id) = 0');
        }

        \Log::warning($builder->getQuery()->toRawSql()) ;
        
        return $builder ;
    }
}
