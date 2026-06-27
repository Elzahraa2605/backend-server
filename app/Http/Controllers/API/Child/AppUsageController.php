<?php

namespace App\Http\Controllers\API\Child;

use App\Http\Controllers\Controller;
use App\Models\App_usage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AppUsageController extends Controller
{
    /**
     * 1. مزامنة جماعية (Bulk Sync) 
     * تُستخدم من موبايل الطفل لإرسال قائمة التطبيقات كاملة في طلب واحد
     */
    /**
     * 1. مزامنة جماعية (Bulk Sync) المحدثة لحل مشكلة تكرار الـ UUID والـ Duration
     */
    public function syncBulk(Request $request)
    {
        // ✅ التأمين الكامل: مع تفعيل auth:child على هذا الراوت، بقى عندنا هوية
        // الطفل موثوقة 100% من التوكن نفسه. بالتالي نتجاهل child_id القادم من
        // الـ Body تمامًا (حتى لو موجود) ونستخدم هوية الطفل المصادق عليه فقط.
        // كده مستحيل طفل/جهاز يكتب بيانات استخدام باسم طفل تاني، ومستحيل أي
        // طلب من جهاز الأب (أو أي مصدر تاني معندوش توكن طفل صحيح) يعدي خالص.
        $child = auth('child')->user();
        if (!$child) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'apps' => 'required|array',
        ]);

        $childId = $child->id;
        $today = now()->toDateString();

        try {
            foreach ($request->apps as $app) {
                $packageName = $app['package_name'] ?? $app['packageName'] ?? 'unknown.package';
                $usageDate = $app['usage_date'] ?? $today;

                // 1. نبحث أولاً لو السجل ده موجود فعلاً للنهاردة
                $existingUsage = App_usage::where('child_id', $childId)
                                         ->where('package_name', $packageName)
                                         ->whereDate('usage_date', $usageDate)
                                         ->first();

                // 2. ننفذ الـ updateOrCreate بطريقة تحافظ على الـ uuid الثابت
                App_usage::updateOrCreate(
                    [
                        'child_id'     => $childId,
                        'package_name' => $packageName,
                        'usage_date'   => $usageDate,
                    ],
                    [
                        // لو السجل موجود سيب الـ uuid بتاعه زي ما هو، لو مش موجود ولد واحد جديد
                        'uuid'         => $existingUsage ? $existingUsage->uuid : (string) Str::uuid(),
                        'app_name'     => $app['app_name'] ?? 'Unknown App',
                        'duration'     => $app['duration'] ?? 0,
                        'category'     => $app['category'] ?? 'General',
                    ]
                );
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Bulk Sync completed successfully. Today\'s report is strict and dynamic.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    // public function syncBulk(Request $request)
    // {
    //     // التحقق من البيانات المرسلة
    //     $request->validate([
    //         'child_id' => 'required',
    //         'apps' => 'required|array',
    //     ]);

    //     $childId = $request->child_id;
    //     $today = now()->toDateString();

    //     foreach ($request->apps as $app) {
            // استخدام updateOrCreate لمنع التكرار وتحديث الاستهلاك الفعلي
            // App_usage::updateOrCreate(
            //     [
            //         'child_id'     => $childId,
            //         'package_name' => $app['package_name'],
            //         'usage_date'   => $app['usage_date'] ?? $today,
            //     ],
            //     [
            //         'uuid'         => (string) Str::uuid(),
            //         'app_name'     => $app['app_name'] ?? 'Unknown App',
            //         'duration'     => $app['duration'] ?? 0,
            //         'category'     => $app['category'] ?? 'General',
            //     ]
            // );
            // استبدلي جزء الـ syncBulk بهذا التعديل البسيط:
// App_usage::updateOrCreate(
//     [
//         'child_id'     => $childId,
//         // 'package_name' => $app['package_name'],
//         'package_name' => $app['package_name'] ?? $app['packageName'] ?? 'unknown.package',
//         'usage_date'   => $app['usage_date'] ?? $today,
//     ],
//     [
//         // السطر ده معناه: لو السجل موجود سيب الـ uuid زي ما هو، لو جديد كريت واحد
//         'uuid'         => (string) Str::uuid(), 
//         'app_name'     => $app['app_name'] ?? 'Unknown App',
//         'duration'     => $app['duration'] ?? 0,
//         'category'     => $app['category'] ?? 'General',
//     ]
// );
//         }

//         return response()->json([
//             'status' => 'success',
//             'message' => 'Bulk Sync completed successfully'
//         ]);
//     }

    /**
     * 2. عرض استهلاك الطفل (للأب) مع الفلترة الزمنية
     * تُستخدم في صفحة الريبورت عند الأب
     */
    public function getChildUsageForParent(Request $request, $child_id) 
    {
        // استلام الفلتر (today, last7days, last30days)
        $range = $request->query('range', 'today'); 
        $query = App_usage::where('child_id', $child_id);

        // تطبيق المنطق الزمني
        if ($range === 'today') {
            $query->whereDate('usage_date', now()->toDateString());
        } elseif ($range === 'last7days') {
            $query->where('usage_date', '>=', now()->subDays(7)->toDateString());
        } elseif ($range === 'last30days') {
            $query->where('usage_date', '>=', now()->subDays(30)->toDateString());
        }

        // ترتيب النتائج: الأكثر استخداماً أولاً
        $usages = $query->orderBy('duration', 'desc')->get();

        return response()->json([
            'status'     => 'success',
            'data'       => $usages,
            'total_time' => (int)$usages->sum('duration') // إجمالي الدقائق للكارت العلوي
        ]);
    }

    /**
     * 3. عرض استهلاك الطفل لنفسه (تطبيق الطفل)
     */
    public function index()
    {
        $child = auth('child')->user();
        if (!$child) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $usages = App_usage::where('child_id', $child->id)
                           ->whereDate('usage_date', now()->toDateString())
                           ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $usages
        ]);
    }

    /**
     * 4. تحديث سجل واحد (اختياري)
     */
    public function update(Request $request, $uuid)
    {
        $usage = App_usage::where('uuid', $uuid)->firstOrFail();
        $usage->update($request->only([
            'app_name', 'package_name', 'category', 'duration', 'usage_date'
        ]));

        return response()->json(['status' => 'success', 'data' => $usage]);
    }

    /**
     * 5. حذف سجل استهلاك
     */
    public function destroy($uuid)
    {
        $usage = App_usage::where('uuid', $uuid)->firstOrFail();
        $usage->delete();

        return response()->json([
            'status' => 'success', 
            'message' => 'Record deleted'
        ]);
    }
}