<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class ClientOffer extends BaseModel
{
    protected $fillable =
    [
        'id' ,
        'client_id' ,
        'freelancer_id' ,
        'sub_category_id' ,
        'title' ,
        'status' ,
        'description' ,
        'min_price' ,
        'max_price' ,
        'days' ,
        'posted_at' ,
        'proposals_count' ,
    ];

    public function skills():MorphToMany
    {
        return $this->morphToMany(Skill::class , 'skillable') ;
    }
    public function files():MorphMany
    {
        return $this->morphMany(File::class , 'filable')->where('deleted' , false)->orWhereNull('deleted') ;
    }
    public function sub_category():BelongsTo
    {
        return $this->belongsTo(SubCategory::class) ;
    }
    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class) ;
    }
    public function freelancer():BelongsTo
    {
        return $this->belongsTo(Freelancer::class) ;
    }
    public function project():HasOne
    {
        return $this->hasOne(Project::class) ;
    }

    public function scopeFilter(Builder $builder , array $filters)
    {
        if($filters['status']??false)
        {
            $builder->where('status' , $filters['status']) ;
        }
        if($filters['sub_category_id']??false)
        {
            $builder->where('sub_category_id' , $filters['sub_category_id']) ;
        }
        if($filters['min_days']??false)
        {
            $builder->where('days' , '>=' ,$filters['min_days']) ;
        }
        if($filters['max_days']??false)
        {
            $builder->where('days' , '<=' , $filters['max_days']) ;
        }
        if($filters['min_price']??false)
        {
            $builder->where('min_price' , '>=' , $filters['min_price']) ;
        }
        if($filters['max_price']??false)
        {
            $builder->where('max_price' , '<=' ,$filters['max_price']) ;
        }
        if($filters['skill_ids']??false)
        {
            $filtersSkillIds = $filters['skill_ids']; 
            
            $builder->leftJoin('skillables',function($join){
                $join->on('client_offers.id' , '=' , 'skillables.skillable_id')
                    ->where('skillables.skillable_type' , ClientOffer::class) ;
            })->where(function($query)use($filtersSkillIds){
                $query->whereIn('skillables.skill_id' , $filtersSkillIds) ;
            })->select('client_offers.*')
              ->groupBy('client_offers.id')
              ->havingRaw('COUNT(skillables.skill_id) = ?' , [count($filtersSkillIds)]);
        }
        
        return $builder ;
    }
}
