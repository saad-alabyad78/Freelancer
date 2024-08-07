<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Milestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id' ,
        'finished_at' ,
        'client_ok' ,
        'freelancer_ok' ,
        'price' ,
        'deadline' ,
        'description' ,
    ] ;

    public function project():BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
