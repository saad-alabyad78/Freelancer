<?php

namespace App\Models;

use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Cache;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;

    protected $fillable = [
        'fcm_token',
        'first_name',
        'email',
        'password',
        'first_name',
        'last_name',
        'provider',
        'last_seen',
        'online',
        'money',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_seen' => 'datetime',
    ];

    protected function RoleName(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::lower(Str::afterLast($this->role_type, '\\'))
        );
    }

    protected function slug(): Attribute
    {
        $name = $this->first_name . ' ' . $this->last_name . ' ' . uniqid();
        return Attribute::make(
            get: fn() => Str::slug($name)
        );
    }
    protected function fullName(): Attribute
    {
        $name = $this->first_name . ' ' . $this->last_name ;
        return Attribute::make(
            get: fn() => $name
        );
    }

    public function role(): MorphTo
    {
        return $this->morphTo();
    }

    public function getAvatarAttribute()
    {
        switch ($this->role_type) {
            case 'App\Models\Company':
                return $this->role->profile_image_url;
            case 'App\Models\Client':
                return $this->role->profile_image_url;
            case 'App\Models\Freelancer':
                return $this->role->profile_image_url;
            default:
                return null;
        }
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversation_user');
    }

    public function sentInvitations()
    {
        return $this->hasMany(Invitation::class, 'company_id');
    }

    public function receivedInvitations()
    {
        return $this->hasMany(Invitation::class, 'freelancer_id');
    }

    public function isOnline()
    {
        return $this->online;
    }

    public function markOnline()
    {
        $this->update(['online' => true, 'last_seen' => now()]);
    }

    public function markOffline()
    {
        $this->update(['online' => false, 'last_seen' => now()]);
    }


    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }
}
