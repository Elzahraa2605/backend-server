<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Notification_setting extends Model
{
    use HasFactory;
    protected $table = 'notification_settings';

    protected $fillable = [
        'uuid',
        'parent_id',

        // Push
        'push_screen_time',
        'push_new_app',
        'push_content_blocked',
        'push_location_alerts',
        'push_pairing_success',
        'push_crying_detected',
        'push_unknown_contact',
        'push_threat_blocked',

        // Email
        'email_weekly_reports',
        'email_critical_alerts',
        'report_frequency',
        'report_time',
    ];

    protected $casts = [
        // Push
        'push_screen_time'        => 'boolean',
        'push_new_app'            => 'boolean',
        'push_content_blocked'    => 'boolean',
        'push_location_alerts'    => 'boolean',
        'push_pairing_success'    => 'boolean',
        'push_crying_detected'    => 'boolean',
        'push_unknown_contact'    => 'boolean',
        'push_threat_blocked'     => 'boolean',

        // Email
        'email_weekly_reports'    => 'boolean',
        'email_critical_alerts'   => 'boolean',
        'report_time'             => 'datetime:H:i',
    ];

    /**
     * Report Frequency constants
     */
    public const REPORT_DAILY   = 'daily';
    public const REPORT_WEEKLY  = 'weekly';
    public const REPORT_MONTHLY = 'monthly';

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
    public function parent()
    {
        return $this->belongsTo(Parents::class, 'parent_id');
    }
}
