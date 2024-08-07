<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $with = ['freelancer' , 'client' , 'files' , 'milestones'] ;
    protected $fillable = [
        'freelancer_id' , 
        'client_id' ,
        'finished_at' ,
        'price' ,
        'days' ,
    ] ;

    public function files():MorphMany
    {
        return $this->morphMany(File::class , 'filable') ;
    }

    public function milestones():HasMany
    {
        return $this->hasMany(Milestone::class);
    }

    public function freelancer():BelongsTo
    {
        return $this->belongsTo(Freelancer::class) ;
    }

    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class) ;
    }
}
