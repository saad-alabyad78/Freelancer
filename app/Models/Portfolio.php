<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Portfolio extends BaseModel
{
    use HasFactory;
    
    protected $fillable = [
        'title' , 
        'url' ,
        'date' ,
        'description' ,
        'freelancer_id' ,
        'views_count' ,
        'likes_count' ,
        'section' ,
    ] ;
    
    public function skills():MorphToMany
    {
        return $this->morphToMany(Skill::class , 'skillable');
    }
    public function files():MorphMany
    {
        return $this->morphMany(File::class , 'filable') ;
    }
    public function images():MorphMany
    {
        return $this->morphMany(Image::class , 'imagable') ;
    }
    public function freelancer():BelongsTo
    {
        return $this->belongsTo(Freelancer::class) ;
    }
    public function likes():MorphToMany
    {
        return $this->morphToMany(Like::class , 'likable') ;
    }
    public function views():MorphToMany
    {
        return $this->morphToMany(View::class , 'viewable') ;
    }
}
