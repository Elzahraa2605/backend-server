<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notification_setting;
use Carbon\Traits\Week;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NotificationSettingsController extends Controller
{
    public function index()
    {
        $parent = auth('parent')->user();
        $settings = Notification_setting::where('parent_id', $parent->id)->first();

        return response()->json($settings);
    }

    public function update(Request $request)
    {
        $parent = auth('parent')->user();
        $settings = Notification_setting::where('parent_id', $parent->id)->firstOrFail();

        $settings->update($request->only([
            'push_screen_time',
            'push_new_app',
            'push_location_alerts',
            'push_pairing_success',
            'push_crying_detected',
            'push_threat_blocked',
            'push_report_frequency',
            'push_critical_alerts',

        ]));

        return response()->json($settings);
    }
}
