<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyPhone extends Model
{
    use HasFactory;

    protected $fillable = ['number'] ;
    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
