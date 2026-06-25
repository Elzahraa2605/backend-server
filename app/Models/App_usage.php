<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class App_usage extends Model
{
    use HasFactory;
    protected $table = 'app_usages';
    protected $fillable = [
        'uuid',
        'child_id',
        'app_name',
        'package_name',
        'category',
        'duration',
        'usage_date',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'usage_date' => 'date',
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
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
}
