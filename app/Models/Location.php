<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Location extends Model
{
    use HasFactory;
    protected $table = 'locations';

    protected $fillable = [
        'uuid',
        'child_id',
        'latitude',
        'longitude',
        'address',
        'is_latest',

    ];

    protected $casts = [
        'latitude'  => 'decimal:8',
        'longitude' => 'decimal:8',
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
    public function getGoogleMapsLinkAttribute()
    {
        return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
    }
}
