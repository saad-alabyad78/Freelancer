<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactLink extends BaseModel
{
    use HasFactory;
    
    protected $fillable = ['name' , 'company_id'] ;

    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
