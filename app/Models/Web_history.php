<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Web_history extends Model
{
    use HasFactory;

    protected $table = 'web_histories';

    protected $fillable = [
        'uuid',
        'child_id',
        'url',
        'domain',
        'title',
        'category',
        'is_blocked',
        'visited_at',
    ];

    protected $casts = [
        'is_blocked' => 'boolean',
        'visited_at' => 'datetime',
    ];

    /**
     * Category constants
     */
    public const CATEGORY_SOCIAL        = 'social';
    public const CATEGORY_EDUCATIONAL   = 'educational';
    public const CATEGORY_ENTERTAINMENT = 'entertainment';
    public const CATEGORY_SHOPPING      = 'shopping';
    public const CATEGORY_UNKNOWN       = 'unknown';

    /**
     * Auto-generate UUID
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = $model->uuid ?? Str::uuid();
        });
    }

    /**
     * Relations
     */
    public function child()
    {
        return $this->belongsTo(Children::class, 'child_id');
    }
    public function scopeBlocked($query)
    {
        return $query->where('is_blocked', true);
    }

    public function scopeAllowed($query)
    {
        return $query->where('is_blocked', false);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
