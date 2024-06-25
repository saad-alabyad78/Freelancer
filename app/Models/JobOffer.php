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
    
    /*
    'military_service_required' ,
    */
    protected $Filters = 
    [
        'status'=> '='  ,
        'location_type'=> '='  ,
        'attendence_type'=> '='  ,
        'gender'=> '='  ,
        'industry_name'=> '='  ,
        'company_id'=> '='  ,
        'job_role_id'=> '='  ,
        'transportation' => '=' ,
        'health_insurance' => '=' ,
        'military_service' => '=' ,
        'max_sallary' => '=<' ,
        'min_salary' => '>=' ,
        'max_age' => '=<' ,
        'min_age' => '>=' ,
    ];
    
    public function scopeFilter(Builder $builder , array $filters , Freelancer $freelancer = null)
    {

        foreach($filters as $key => $value)
        {
            if($value === null || !array_key_exists($key , $this->Filters))continue ;
            
            $builder->where( $key , $this->Filters[$key] ,$value) ;
        }

        if($freelancer != null)
        {
            
            //if 'age_required' , offer.max_age >= free.age >= offer.min_age  ,
            if($filters['age_required'] ?? false)
            {
                
                //todo
                //what if max min age null
                //chick the query operator (or and)  
                $builder->whereNull(['max_age' , 'min_age'])
                        ->orWhere('max_age' , '>=' , $freelancer->age)
                        ->where('min_age' , '<=' , $freelancer->age) ;
            }
            if($filters['gender_required'] ?? false)
            {
                //if 'gender_required' offer.gender == free.gender (exception := offer.gender == null),
                $builder->whereNull('gender')->orWhere('gender' , $freelancer->gender) ;
            }
            
            //'military_service_required' idk wtf i will do,

            // if()

            //every skils required => freelancer must have it
            
        }

        var_dump($builder->getQuery()) ;
        
        return $builder ;
    }
}
