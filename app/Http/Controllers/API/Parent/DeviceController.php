<?php

namespace App\Http\Controllers\API\Parent;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    // كل أجهزة الأب وأبنائه
    public function index()
    {
        $parent = auth('parent')->user();
        $devices = Device::where('parent_id', $parent->id)->orWhereIn('child_id', $parent->childrens()->pluck('id'))->get();

        return response()->json([
            'data' => $devices,
            'message' => 'تم عرض الأجهزة بنجاح'
        ], 200);
    }

    // إضافة جهاز
    public function store(Request $request)
    {
        $request->validate([
            'device_name' => 'required',
            'device_model' => 'required',
            'os' => 'required',
            'child_id' => 'nullable|exists:childrens,id', // الأب يقدر يضيف لجهاز طفله
            'fcm_token' => 'nullable'
        ]);

        $parent = auth('parent')->user();

        $device = Device::create([
            'parent_id' => $parent->id,
            'child_id' => $request->child_id ?? null,
            'device_name' => $request->device_name,
            'device_model' => $request->device_model,
            'os' => $request->os,
            'fcm_token' => $request->fcm_token,
            'status' => 'pending'
        ]);

        return response()->json([
            'data' => $device,
            'message' => 'تم إضافة الجهاز بنجاح'
        ], 201);
    }

    // تعديل جهاز مرتبط بالأب أو بأبناءه
    public function update(Request $request, $uuid)
    {
        $parent = auth('parent')->user();
        $device = Device::where('uuid', $uuid)
            ->where(function ($q) use ($parent) {
                $q->where('parent_id', $parent->id)
                    ->orWhereIn('child_id', $parent->childrens()->pluck('id'));
            })->firstOrFail();

        $device->update($request->only(['device_name', 'device_model', 'os', 'status', 'fcm_token', 'app_info']));
        return response()->json($device);
    }

    // حذف جهاز مرتبط بالأب أو بأبنائه
    public function destroy($uuid)
    {
        $parent = auth('parent')->user();
        $device = Device::where('uuid', $uuid)
            ->where(function ($q) use ($parent) {
                $q->where('parent_id', $parent->id)
                    ->orWhereIn('child_id', $parent->childrens()->pluck('id'));
            })->firstOrFail();

        $device->delete();
        return response()->json(['message' => 'Device deleted']);
    }
}
