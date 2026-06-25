<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;



class Parents extends Authenticatable implements JWTSubject
{
    use SoftDeletes;

    protected $table = 'parents';

    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
        'phone',
        'profile_image',
        'is_active',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'is_active' => 'boolean'
    ];


    // JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = $model->uuid ?? Str::uuid();
        });
    }

    // public function childrens()
    // {
    //     return $this->hasMany(Children::class);
    // }
    public function children()
    {
        return $this->hasMany(Children::class, 'parent_id');
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function pairingSessions()
    {
        return $this->hasMany(Pairing_session::class);
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }

    public function notificationSettings()
    {
        return $this->hasOne(Notification_setting::class);
    }

    public function activeChildren()
    {
        return $this->children()->where('is_active', true);
    }

    public function getActiveDeviceTokens()
    {
        return $this->devices()
            ->whereNotNull('fcm_token')
            ->where('status', 'active')
            ->pluck('fcm_token')
            ->toArray();
    }
}
