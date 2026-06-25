<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChildApp; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ChildAppController extends Controller
{
    public function syncAllApps(Request $request)
    {
        $childId = $request->child_id;
        
        // 👈 التعديل هنا: التقاط المصفوفة سواء جاءت كـ Array جاهز أو نصوص مفككة من الـ FormData
        $apps = $request->apps;
        if ($request->has('apps') && !is_array($apps)) {
            $apps = json_decode($request->apps, true);
        }
        // إذا جاءت مفككة تماماً على هيئة apps[0][package_name]، اللارافيل يجمعها تلقائياً بالسطر التالي:
        if (!$apps) {
            $apps = $request->input('apps');
        }

        // الكود الافتراضي الباقي كما هو تماماً دون أي تغيير:
        if (!$apps || !is_array($apps)) {
            return response()->json(['message' => 'No apps provided'], 400);
        }

        try {
            DB::transaction(function () use ($childId, $apps, $request) {
                $incomingPackageNames = collect($apps)->pluck('package_name')->toArray();

                // حذف التطبيقات القديمة
                ChildApp::where('child_id', $childId)
                        ->whereNotIn('package_name', $incomingPackageNames)
                        ->delete();

                foreach ($apps as $index => $app) {
                    $packageName = $app['package_name'];
                    
                    // جلب الأيقونة القديمة كقيمة افتراضية
                    $existingApp = ChildApp::where('child_id', $childId)
                                            ->where('package_name', $packageName)
                                            ->first();
                    $iconPath = $existingApp ? $existingApp->app_icon : null;

                    $fileKeyByPackage = 'icon_' . str_replace('.', '_', $packageName);
                    $fileKeyByIndex = 'icon_' . $index;

                
                    if ($request->hasFile($fileKeyByPackage)) {
                        $path = $request->file($fileKeyByPackage)->store('app_icons/' . $childId, 'public');
                        // التعديل: توليد رابط مطلق متوافق مع بورت التشغيل الفعلي
                        $iconPath = Storage::disk('public')->url($path); 
                    } 
                    elseif ($request->hasFile($fileKeyByIndex)) {
                        $path = $request->file($fileKeyByIndex)->store('app_icons/' . $childId, 'public');
                        $iconPath = Storage::disk('public')->url($path);
                    }
                    elseif ($request->hasFile($packageName)) {
                        $path = $request->file($packageName)->store('app_icons/' . $childId, $disk);
                        $iconPath = Storage::disk('public')->url($path);
                    }

                    ChildApp::updateOrCreate(
                        [
                            'child_id' => $childId,
                            'package_name' => $packageName
                        ],
                        [
                            'app_name' => $app['app_name'],
                            'app_icon' => $iconPath 
                        ]
                    );
                }
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Apps synced successfully and database refreshed!'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to sync apps: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index($child_id)
    {
        $apps = ChildApp::where('child_id', $child_id)->get();
        return response()->json($apps, 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'is_blocked' => 'sometimes|required',
            'time_limit' => 'sometimes|required|integer',
        ]);

        $app = ChildApp::find($id);

        if (!$app) {
            return response()->json(['message' => 'App not found'], 404);
        }

        $app->update([
            'is_blocked' => $request->has('is_blocked') ? $request->is_blocked : $app->is_blocked,
            'time_limit' => $request->has('time_limit') ? $request->time_limit : $app->time_limit,
        ]);

        return response()->json([
            'status' => 'success', 
            'message' => 'App updated successfully',
            'data' => $app
        ], 200);
    }
    
    public function getBlockedPackagesOnly($childId)
    {
        $blockedPackages = ChildApp::where('child_id', $childId)
                                    ->where('is_blocked', true)
                                    ->pluck('package_name');

        return response()->json($blockedPackages, 200);
    }

    public function storeNativeRequest(Request $request)
    {
        $request->validate([
            'child_id' => 'required',
            'package_name' => 'required',
            'app_name' => 'required',
            'reason' => 'nullable|string'
        ]);

        $exists = DB::table('child_app_requests')
            ->where('child_id', $request->child_id)
            ->where('package_name', $request->package_name)
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'هناك طلب معلق بالفعل لهذا التطبيق'], 400);
        }

        DB::table('child_app_requests')->insert([
            'id' => (string) Str::uuid(),
            'child_id' => $request->child_id,
            'package_name' => $request->package_name,
            'app_name' => $request->app_name,
            'reason' => $request->reason ?? 'يريد الطفل فتح هذا التطبيق',
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'تم إرسال طلبك للأب بنجاح'], 201);
    }

    public function getAllowedApps($childId)
    {
        $allowedPackages = ChildApp::where('child_id', $childId)
                                    ->where('is_blocked', false)
                                    ->pluck('package_name');

        return response()->json($allowedPackages, 200);
    }

    public function getAppConfig(Request $request)
    {
        $childId = $request->query('child_id');
        
        if (!$childId) {
            return response()->json(['message' => 'Child ID is required'], 400);
        }

        $apps = ChildApp::where('child_id', $childId)->get();

        return response()->json([
            'status' => 'success',
            'data' => $apps
        ], 200);
    }

    public function getAppsWithTimers($childId)
    {
        try {
            $appsWithTimers = ChildApp::where('child_id', $childId)
                                      ->pluck('time_limit', 'package_name');

            return response()->json($appsWithTimers, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error retrieving timers: ' . $e->getMessage()], 500);
        }
    }

    public function syncApps(Request $request)
    {
        $request->validate([
            'child_id' => 'required',
            'package_name' => 'required',
            'is_blocked' => 'required',
            'time_limit' => 'nullable|integer'
        ]);

        $app = ChildApp::where('child_id', $request->child_id)
                        ->where('package_name', $request->package_name)
                        ->first();

        if (!$app) {
            return response()->json(['message' => 'App not found in child list'], 404);
        }

        $app->update([
            'is_blocked' => $request->is_blocked,
            'time_limit' => $request->time_limit ?? $app->time_limit
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'App rule updated successfully',
            'data' => $app
        ], 200);  
    }
}