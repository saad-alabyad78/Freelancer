<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Portfolio extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title' , 
        'url' ,
        'date' ,
        'description' ,
        'freelancer_id' ,
    ] ;
    
    public function skills():MorphMany
    {
        return $this->morphMany(Skill::class , 'skillable');
    }
    
    public function files():MorphMany
    {
        return $this->morphMany(File::class , 'filable') ;
    }
    public function freelancer():BelongsTo
    {
        return $this->belongsTo(Freelancer::class) ;
    }
}
