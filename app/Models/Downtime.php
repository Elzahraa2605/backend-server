<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Downtime extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'child_id', // تعديل: غيرنا rule_id لـ child_id
        'name',
        'start_time',
        'end_time',
        'days',
        'block_all',
        'allowed_apps',
    ];

    protected $casts = [
        'days' => 'array',
        'allowed_apps' => 'array',
        'block_all' => 'boolean'
    ];

    /**
     * تعديل: العلاقة الآن مع الطفل مباشرة وليس مع القواعد
     */
    public function child()
    {
        return $this->belongsTo(Children::class, 'child_id');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            // توليد الـ UUID تلقائياً عند الإنشاء
            $model->uuid = $model->uuid ?? (string) Str::uuid();
        });
    }
}