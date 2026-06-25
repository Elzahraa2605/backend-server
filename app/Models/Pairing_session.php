<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Pairing_session extends Model
{
   use HasFactory;

    // 1. إضافة هذا السطر لضمان التوافق التام مع اسم الجدول في قاعدة البيانات
    protected $table = 'pairing_sessions';
    protected $fillable = [
        'uuid',
        'parent_id',
        'code',
        'expires_at',
        'status',
        'child_id',
        'device_info',
    ];
    protected $casts = [
        'expires_at' => 'datetime',
        'device_info' => 'array'
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
