<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactLink extends Model
{
    use HasFactory;
    
    protected $fillable = ['name'] ;

    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
