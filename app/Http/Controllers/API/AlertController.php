<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlertController extends Controller
{
    public function index(Request $request)
    {
        // 🎯 جلب الأب الحالي تلقائياً (أو حساب رقم 1 كاحتياط للداتابيز الجديدة)
        $parentId = auth('parent')->id() ?? auth()->id() ?? 1; 

        // حذف التنبيهات التي مر عليها أكثر من 7 أيام تلقائياً
        Alert::where('parent_id', $parentId)
            ->where('created_at', '<', Carbon::now()->subDays(7))
            ->delete();

        $query = Alert::where('parent_id', $parentId);

        // 🎯 الفصل الحاسم بين الرضيع والأطفال الكبار:
        if ($request->has('child_id') && $request->child_id != null && $request->child_id !== 'null') {
            // لو الموبايل طالب طفل كبير (زي lll)، هات التنبيهات الخاصة بـ الآيدي بتاعه فقط
            $query->where('child_id', $request->child_id);
        } else {
            // 👶 لو مش باعت child_id (غرفة الرضيع/المونيتور)، هات التنبيهات الـ null فقط
            $query->whereNull('child_id');
        }

        // فلترة تاريخ اليوم الصافي لتجنب لغبطة الـ Timezone
        if ($request->has('range') && $request->range === 'today') {
            $query->whereDate('created_at', Carbon::today('Africa/Cairo'));
        }

        $alerts = $query->orderBy('created_at', 'desc')->get();

        // تنسيق الوقت المفرود ليفهمه الأندرويد في الـ React Native فوراً
        $formattedAlerts = $alerts->map(function ($alert) {
            Carbon::setLocale('en'); 
            $createdAt = Carbon::parse($alert->created_at)->timezone('Africa/Cairo'); 

            $alert->formatted_day  = $createdAt->isoFormat('dddd');          
            $alert->formatted_date = $createdAt->isoFormat('LL');            
            $alert->formatted_time = $createdAt->isoFormat('hh:mm A');       
            return $alert;
        });

        return response()->json($formattedAlerts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'     => 'required|string',
            'title'    => 'required|string',
            'message'  => 'required|string'
        ]);

        $type = $request->type;
        $finalChildId = null;
        
        // جلب الأب من التوكن أو الحساب الافتراضي
        $parentId = auth('parent')->id() ?? auth()->id() ?? 1;

        // 👶 1. حماية مسار الرضيع (الهاردوير): يظل null ومستقل 100%
        if ($type === 'crying_detected' || $type === 'Cry') {
            $finalChildId = null;
        } else {
            // 👦 2. مسار الأطفال الكبار (الحظر والويب):
            
            // أولاً: فحص لو الـ child_id مبعوث صراحة في الـ Request (وده الأضمن للـ Emulator)
            $childId = $request->child_id ?? $request->input('child_id');
            
            if (is_string($childId) && preg_match('/\d+/', $childId, $matches)) {
                $childId = (int) $matches[0];
            }

            $tableName = Schema::hasTable('childrens') ? 'childrens' : 'children';
            $child = DB::table($tableName)->where('id', $childId)->first();

            if ($child) {
                $finalChildId = $child->id;
                $parentId = $child->parent_id;
            } else {
                // ثانياً: لو الـ ID مش مبعوث، فتش بتوكن الطفل
                $childUser = auth('child')->user();
                if ($childUser) {
                    $finalChildId = $childUser->id;
                    $parentId = $childUser->parent_id;
                } else {
                    // ثالثاً: حيلة الفحص بالـ package_name لمنع التداخل
                    $packageName = $request->package_name ?? $request->packageName;
                    if ($packageName) {
                        $appRecord = DB::table('child_apps')->where('package_name', $packageName)->first();
                        if ($appRecord) {
                            $finalChildId = $appRecord->child_id;
                            $child = DB::table($tableName)->where('id', $finalChildId)->first();
                            if ($child) { $parentId = $child->parent_id; }
                        }
                    }
                }
            }

            // 🚨 شبكة الأمان المطلقة: لو كل المحاولات فشلت، اربطه بأول طفل مسجل للأب ده (وليس الأحدث عشوائياً!)
            if (!$finalChildId) {
                $firstChildOfParent = DB::table($tableName)->where('parent_id', $parentId)->orderBy('id', 'asc')->first();
                $finalChildId = $firstChildOfParent ? $firstChildOfParent->id : null;
            }
        }
        
        $title = $request->title;
        $message = $request->message;

        if ($type === 'threat_blocked' || str_contains($title, 'حظر') || str_contains($title, 'blocked')) {
            $title = "Threat Blocked";
            $message = "Security system prevented downloading a suspicious file on child device. Reason: Flagged as malware.";
        } elseif ($type === 'content_blocked') {
            $title = "Restricted Website Blocked";
            $message = "Security engine intercepted a restricted webview navigation request.";
        }

        $alert = Alert::create([
            'parent_id'         => $parentId,
            'child_id'          => $finalChildId, 
            'type'              => $type,
            'title'             => $title,
            'message'           => $message,
            'is_read'           => false,
            'notification_sent' => false
        ]);

        return response()->json($alert, 201);
    }

    public function markRead(string $uuid)
    {
        $alert = Alert::where('uuid', $uuid)->firstOrFail();
        $alert->update(['is_read' => true]);
        return response()->json(['success' => true, 'message' => 'Alert marked as read']);
    }

    public function markAllReadForChild(Request $request)
    {
        $request->validate(['child_id' => 'required']);
        $parent = auth('parent')->user();

        Alert::where('parent_id', $parent->id)
            ->where('child_id', $request->child_id)
            ->update(['is_read' => true]);

        return response()->json(['success' => true, 'message' => 'All child alerts marked as read']);
    }

    public function destroy(string $uuid)
    {
        $alert = Alert::where('uuid', $uuid)->firstOrFail();
        $alert->delete();
        return response()->json(['message' => 'Alert deleted']);
    }
}