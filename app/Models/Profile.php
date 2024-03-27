<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = ['gender','user_id','avatar_image','cover_image'];

    public function avatarImage():Attribute
    {
        return Attribute::make(
            get: fn($value , $attributes) => 
            $value ??  config('images.profile.avatar.'.$attributes['gender'])
        );
    }
    public function coverImage():Attribute
    {
        return Attribute::make(
            get: fn($value , $attributes) => 
            $value ??  config('images.profile.avatar.'.$attributes['gender'])
        );
    }

    /**
     * Get the user that owns the Profile
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
