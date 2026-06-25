<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Device extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'parent_id',
        'child_id',
        'device_name',
        'device_model',
        'os',
        'device_token',
        'fcm_token',
        'status',
        'last_active_at',
        'app_info'
    ];
    protected $casts = [
        'last_active_at' => 'datetime',
        'app_info' => 'array'
    ];
    public function parent()
    {
        return $this->belongsTo(Parents::class);
    }
    public function child()
    {
        return $this->belongsTo(Children::class);
    }
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = $model->uuid ?? Str::uuid();
        });
    }
}
