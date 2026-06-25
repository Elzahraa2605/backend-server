<?php

namespace App\Http\Controllers\API\Parent;

use App\Http\Controllers\Controller;
use App\Models\Downtime;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DowntimeController extends Controller
{
    /**
     * عرض كل الـ Downtimes الخاصة بطفل معين مباشرة
     * تم استبدال rule_uuid بـ child_id لتبسيط العملية
     */
    public function index(Request $request)
    {
        $validator = validator($request->all(), [
            'child_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        // جلب المواعيد بناءً على معرف الطفل مباشرة
        $downtimes = Downtime::where('child_id', $request->child_id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $downtimes
        ]);
    }

    /**
     * إنشاء Downtime جديد مرتبط بالطفل مباشرة
     */
    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'child_id' => 'required', // تم التغيير من rule_id لـ child_id
            'name' => ['required', 'string'],
            'start_time' => ['required', 'date_format:H:i:s'],
            'end_time' => ['required', 'date_format:H:i:s'],
            'days' => ['array', 'nullable'],
            'block_all' => ['boolean'],
            'allowed_apps' => ['array', 'nullable']
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $downtime = Downtime::create([
            'uuid' => (string) Str::uuid(),
            'child_id' => $request->child_id, // الحفظ المباشر لمعرف الطفل
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'days' => $request->days ?? [],
            'block_all' => $request->block_all ?? true,
            'allowed_apps' => $request->allowed_apps ?? []
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $downtime
        ], 201);
    }

    /**
     * عرض تفاصيل موعد واحد بناءً على الـ UUID
     */
    public function show($uuid)
    {
        $downtime = Downtime::where('uuid', $uuid)->firstOrFail();
        return response()->json($downtime);
    }

    /**
     * تحديث بيانات موعد التوقف
     */
    public function update(Request $request, $uuid)
    {
        $downtime = Downtime::where('uuid', $uuid)->firstOrFail();
        
        $downtime->update($request->only([
            'name',
            'start_time',
            'end_time',
            'days',
            'block_all',
            'allowed_apps'
        ]));

        return response()->json([
            'status' => 'success',
            'data' => $downtime
        ]);
    }

    /**
     * حذف موعد التوقف
     */
    public function destroy($uuid)
    {
        $downtime = Downtime::where('uuid', $uuid)->firstOrFail();
        $downtime->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Downtime deleted successfully'
        ]);
    }

    /**
     * فحص الحالة لموبايل الطفل (هل الجهاز مقفل الآن؟)
     * تعتمد الآن على child_id مباشرة وتتجاهل جدول الـ Rules تماماً
     */
    public function checkStatus($child_id)
    {
        // جلب كل مواعيد التوقف الخاصة بالطفل
        $downtimes = Downtime::where('child_id', $child_id)->get();

        if ($downtimes->isEmpty()) {
            return response()->json([
                'is_locked' => false,
                'debug' => 'No downtimes found for child ID: ' . $child_id
            ]);
        }

        // توقيت القاهرة لضمان التزامن مع ساعة المستخدم في مصر
        $now = Carbon::now('Africa/Cairo');
        $currentTime = $now->toTimeString(); 
        $currentDayNumber = $now->dayOfWeek; // 0 للأحد، 1 للاثنين...

        foreach ($downtimes as $downtime) {
            // معالجة صيغة الأيام (Array أو JSON)
            $activeDays = is_array($downtime->days) 
                ? $downtime->days 
                : json_decode($downtime->days, true) ?? [];
            
            $activeDays = array_map('intval', $activeDays);

            // التحقق من الوقت واليوم
            $isWithinTime = ($currentTime >= $downtime->start_time && $currentTime <= $downtime->end_time);
            $isTodayActive = in_array((int)$currentDayNumber, $activeDays);

            if ($isWithinTime && $isTodayActive) {
                return response()->json([
                    'is_locked' => true,
                    'message' => 'Downtime active: ' . $downtime->name,
                    'allowed_apps' => $downtime->allowed_apps
                ]);
            }
        }

        return response()->json([
            'is_locked' => false,
            'debug' => [
                'server_time' => $currentTime,
                'server_day' => $currentDayNumber,
                'checked_count' => $downtimes->count()
            ]
        ]);
    }
}