<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory , SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'image_id',
        'freelancer_id',
        'price',
    ] ;
    
    protected $appends = ['clientsCount'] ;

    public function getClientsCountAttribute()
    {
        return $this->clients()->count() ;
    }
    public function clients():BelongsToMany
    {
        return $this->belongsToMany(Client::class) ;
    }
    public function freelancer():BelongsTo
    {
        return $this->belongsTo(Freelancer::class) ;
    }
    public function files():MorphMany
    {
        return $this->morphMany(File::class , 'filable') ;
    }
    public function images():MorphMany
    {
        return $this->morphMany(Image::class , 'imagable') ;
    }
    public function image():BelongsTo
    {
        return $this->belongsTo(Image::class) ;
    }
}
