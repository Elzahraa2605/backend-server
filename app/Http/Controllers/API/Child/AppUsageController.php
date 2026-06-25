<?php

namespace App\Http\Controllers\API\Child;

use App\Http\Controllers\Controller;
use App\Models\App_usage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AppUsageController extends Controller
{
    
    public function syncBulk(Request $request)
    {
        $request->validate([
            'child_id' => 'required',
            'apps' => 'required|array',
        ]);

        $childId = $request->child_id;
        $today = now()->toDateString();

        try {
            foreach ($request->apps as $app) {
                $packageName = $app['package_name'] ?? $app['packageName'] ?? 'unknown.package';
                $usageDate = $app['usage_date'] ?? $today;

                
                $existingUsage = App_usage::where('child_id', $childId)
                                         ->where('package_name', $packageName)
                                         ->whereDate('usage_date', $usageDate)
                                         ->first();

                
                App_usage::updateOrCreate(
                    [
                        'child_id'     => $childId,
                        'package_name' => $packageName,
                        'usage_date'   => $usageDate,
                    ],
                    [
                        
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
    
    public function getChildUsageForParent(Request $request, $child_id) 
{
    $range = $request->query('range', 'today'); //[cite: 7]
    
    // ربط جدول الاستهلاك بشكل مستقل بجدول التطبيقات لجلب أيقونة كل حزمة مستهلكة
    $query = \App\Models\App_usage::where('app_usages.child_id', $child_id)
        ->leftJoin('child_apps', function($join) {
            $join->on('app_usages.package_name', '=', 'child_apps.package_name')
                 ->on('app_usages.child_id', '=', 'child_apps.child_id');
        })
        ->select('app_usages.*', 'child_apps.app_icon'); // 👈 سحب الأيقونة لتقرير الاستخدام بشكل منعزل

    if ($range === 'today') {
        $query->whereDate('app_usages.usage_date', now()->toDateString()); //[cite: 7]
    } elseif ($range === 'last7days') {
        $query->where('app_usages.usage_date', '>=', now()->subDays(7)->toDateString()); //[cite: 7]
    } elseif ($range === 'last30days') {
        $query->where('app_usages.usage_date', '>=', now()->subDays(30)->toDateString()); //[cite: 7]
    }

    $usages = $query->orderBy('app_usages.duration', 'desc')->get();

    return response()->json([
        'status'     => 'success',
        'data'       => $usages,
        'total_time' => (int)$usages->sum('duration') //[cite: 7]
    ]);
}

    
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

    
    public function update(Request $request, $uuid)
    {
        $usage = App_usage::where('uuid', $uuid)->firstOrFail();
        $usage->update($request->only([
            'app_name', 'package_name', 'category', 'duration', 'usage_date'
        ]));

        return response()->json(['status' => 'success', 'data' => $usage]);
    }

    
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