<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Alert;

class BabyMonitorController extends Controller
{
    

    public function heartbeat()
{
    // تم زيادة الوقت إلى 30 ثانية لمنع تذبذب الـ Online/Offline
    Cache::put('hardware_online_status', true, now()->addSeconds(30));

    $isLightOn = Cache::get('smart_light_status', 0);

    return response()->json([
        'is_light_on' => (int) $isLightOn,
        'is_online' => true
    ], 200);
}

    
    public function receiveCryAlert(Request $request)
{
    // التأكد من أن الإشارة القادمة قيمتها 1 (يوجد بكاء)
    if ($request->input('status') == 1) {
        
        // ربط التنبيه مباشرة بحساب الأب (مثلاً الأب رقم 1 أو أول أب في الداتابيز)
        // يمكنك تعديلها لاحقاً لتاخذ ID الأب الديناميكي لو أردت
        $parentId = 1; 

        $alert = Alert::create([
            'parent_id'         => $parentId,
            'child_id'          => null, // تركناه null تماماً لأن الرضيع ليس له حساب
            'type'              => Alert::TYPE_CRYING_DETECTED, 
            'title'             => "Crying Detected 🚨",
            'message'           => "Your baby is crying in the infant room, please check on them.",
            'is_read'           => false,
            'notification_sent' => false
        ]);

        return response()->json(['success' => true, 'alert' => $alert], 201);
    }

    return response()->json(['success' => true, 'message' => 'Status is 0']);
}
    
    public function getLightStatus()
    {
        $isLightOn = Cache::get('smart_light_status', 0);
        $isOnline = Cache::has('hardware_online_status');

        return response()->json([
            'is_light_on' => (int) $isLightOn,
            'is_online' => $isOnline
        ], 200);
    }

    
    public function toggleLight(Request $request)
    {
        $request->validate([
            'status' => 'required|in:0,1'
        ]);

        $status = $request->input('status');

        Cache::put('smart_light_status', $status);

        return response()->json([
            'success' => true,
            'is_light_on' => (int) $status
        ], 200);
    }
}