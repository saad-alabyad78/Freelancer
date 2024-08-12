<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pill extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
      'from_id',
      'from_type',
      'to_id',
      'to_type',
      'description',
      'price',  
    ];

    public function from():BelongsTo
    {
        return $this->belongsTo($this->from_type , 'from_id') ;
    }
    public function to():BelongsTo
    {
        return $this->belongsTo($this->to_type , 'to_id') ;
    }
}
