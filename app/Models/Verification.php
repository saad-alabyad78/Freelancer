<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Verification extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id' ,
        'accepted_at' ,
        'rejected_at' ,
        'response' ,
    ] ;

    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class) ;
    }
    public function file():MorphOne
    {
        return $this->morphOne(File::class , 'filable') ;
    }
}
