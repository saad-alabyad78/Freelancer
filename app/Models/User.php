<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'first_name',
        'email',
        'password',
        'first_name',
        'provider',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected function RoleName(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::afterLast($this->attributes['role_type'] , '\\')
        );
    }

    protected function slug(): Attribute
    {
        $name =
        $this->attributes['first_name'] . ' ' .
        $this->attributes['last_name'] . ' ' .
        $this->attributes['id'] ;
        return Attribute::make(
            get: fn() => Str::slug($name)
        );
    }

    /**
     * Get the profile associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function role():MorphTo
    {
        return $this->morphTo();
    }
}
