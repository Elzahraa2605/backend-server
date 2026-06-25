<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Children extends Authenticatable implements JWTSubject
{
    use SoftDeletes;
    protected $table = 'childrens';

    protected $fillable = [
        'uuid',
        'parent_id',
        'name',
        'email',
        'password',
        'type',
        'date_of_birth',
        'profile_image',
        'is_active',
        // 'pairing_code',
    ];
    protected $hidden = ['password'];
    protected $casts = [
        'date_of_birth' => 'date',
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

    // Relationships
    public function devices()
    {
        return $this->hasMany(Device::class, 'child_id');
    }
    public function rules()
    {
        return $this->hasMany(Rule::class,'child_id');
    }
    public function appUsage()
    {
        return $this->hasMany(App_usage::class);
    }
    public function parent()
    {
        return $this->belongsTo(Parents::class, 'parent_id');
    }

    public function locations()
    {
        return $this->hasMany(Location::class, 'child_id');
    }

    public function latestLocation()
    {
        return $this->hasOne(Location::class, 'child_id')->latestOfMany();
    }
    public function appRequests()
    {
        return $this->hasMany(App_request::class);
    }
    public function cryingLogs()
    {
        return $this->hasMany(Crying_log::class);
    }
    public function webHistory()
    {
        return $this->hasMany(Web_history::class);
    }
    public function pairingSessions()
    {
        return $this->hasMany(Pairing_session::class, 'child_id');
    }
    // Helper Methods
    public function getAge()
    {
        return $this->date_of_birth->age;
    }
    public function isTeen()
    {
        return $this->type === 'teen';
    }

    public function getActiveDevice()
    {
        return $this->devices()->where('status', 'active')->first();
    }
    public function getTodayScreenTime()
    {
        return $this->appUsage()
            ->whereDate('usage_date', today())
            ->sum('duration');
    }
}
