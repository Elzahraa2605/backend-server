<?php

namespace App\Http\Controllers\API\Child;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    // public function index()
    // {
    //     $child = auth('child')->user();

    //     $devices = Device::where('child_id', $child->id)->get();

    //     return response()->json([
    //         'data' => $devices,
    //         'message' => 'تم عرض أجهزتك بنجاح'
    //     ], 200);
    // }
    public function index()
    {
        $child = auth('child')->user();

        // جلب الأجهزة الخاصة بالطفل
        $devices = Device::where('child_id', $child->id)->get();

        // تعديل الـ status حسب آخر pairing session
        $devices->transform(function ($device) use ($child) {
            $lastSession = $child->pairingSessions()->latest()->first();

            if ($lastSession && $lastSession->status === 'completed') {
                $device->status = 'completed';
            } else {
                $device->status = 'pending';
            }

            return $device;
        });

        return response()->json([
            'data' => $devices,
            'message' => 'تم عرض أجهزتك بنجاح'
        ]);
    }

    // public function show($uuid)
    // {
    //     $child = auth('child')->user();

    //     $device = Device::where('uuid', $uuid)
    //         ->where('child_id', $child->id)
    //         ->firstOrFail();

    //     return response()->json([
    //         'data' => $device,
    //         'message' => 'تم عرض الجهاز بنجاح'
    //     ], 200);
    // }
}