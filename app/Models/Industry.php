<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Industry extends Model
{
    use HasFactory;

    protected $primaryKey = 'name' ;
    protected $keyType = 'string' ;

    protected $fillable = ['name'] ;

    public function companies():HasMany
    {
        return $this->hasMany(Company::class , 'industry_name' , 'name'); 
    }
    public function job_offers():HasMany
    {
        return $this->hasMany(Job_Offer::class , 'industry_name' , 'name');
    }
}
