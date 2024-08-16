<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['complainant_id', 'accused_id', 'reason', 'type'];

    public function complainant()
    {
        return $this->belongsTo(User::class, 'complainant_id');
    }

    public function accused()
    {
        return $this->belongsTo(User::class, 'accused_id');
    }
}
