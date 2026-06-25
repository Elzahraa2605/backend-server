<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Alert extends Model
{
    use HasFactory;

    protected $table = 'child_alerts';

    protected $fillable = [
        'uuid',
        'parent_id',
        'child_id',
        'type',
        'title',
        'message',
        'is_read',
        'notification_sent',
        'notification_type',
    ];

    protected $casts = [
        'is_read'           => 'boolean',
        'notification_sent' => 'boolean',
    ];

    public const TYPE_SCREEN_LIMIT_REACHED = 'screen_limit_reached';
    public const TYPE_DOWNTIME_VIOLATION   = 'downtime_violation';
    public const TYPE_NEW_APP_INSTALLED    = 'new_app_installed';
    public const TYPE_CONTENT_BLOCKED      = 'content_blocked';
    public const TYPE_LOCATION_ALERT       = 'location_alert';
    public const TYPE_PAIRING_SUCCESS      = 'pairing_success';
    public const TYPE_CRYING_DETECTED      = 'crying_detected';
    public const TYPE_UNKNOWN_CONTACT      = 'unknown_contact';
    public const TYPE_THREAT_BLOCKED       = 'threat_blocked';
    public const TYPE_DEVICE_OFFLINE       = 'device_offline';

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = $model->uuid ?? (string) Str::uuid();
        });
    }

    public function parent()
    {
        return $this->belongsTo(Parents::class, 'parent_id');
    }

    public function child()
    {
        return $this->belongsTo(Children::class, 'child_id');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }
}