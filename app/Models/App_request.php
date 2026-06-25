<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class App_request extends Model
{
    use HasFactory;
    protected $table = 'app_requests';

    protected $fillable = [
        'uuid',
        'child_id',
        'app_name',
        'package_name',
        'reason',
        'status',
        'category',
        'parent_response',
    ];

    protected $casts = [
        'status' => 'string',
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
