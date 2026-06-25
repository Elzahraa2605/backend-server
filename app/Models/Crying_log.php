<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Crying_log extends Model
{
    use HasFactory;
    protected $table = 'crying_logs';

    protected $fillable = [
        'uuid',
        'child_id',
        'duration_seconds',
        'intensity',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'intensity'        => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = $model->uuid ?? Str::uuid();
        });
    }

    public function child()
    {
        return $this->belongsTo(Children::class, 'child_id');
    }

    public function getDurationInMinutesAttribute()
    {
        return round($this->duration_seconds / 60, 2);
    }
}
