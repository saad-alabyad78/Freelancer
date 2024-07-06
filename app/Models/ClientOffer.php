<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
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
        'sub_category_id' ,
        'title' ,
        'status' ,
        'description' ,
        'min_price' ,
        'max_price' ,
        'days' ,
        'posted_at' ,
    ];

    public function skills():MorphToMany
    {
        return $this->morphToMany(Skill::class , 'skillable') ;
    }
    public function files():MorphMany
    {
        return $this->morphMany(File::class , 'filable')->whereNull('deleted') ;
    }
    public function sub_category():BelongsTo
    {
        return $this->belongsTo(SubCategory::class) ;
    }
    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class) ;
    }

    public function scopeFilter(Builder $builder , array $filters)
    {
        if($filters['status']??false)
        {
            $builder->where('status' , $filters['status']) ;
        }
        
        return $builder ;
    }
}
