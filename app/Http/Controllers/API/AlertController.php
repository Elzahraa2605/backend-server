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
        
        // 🎯 جلب الأب الحالي تلقائياً من التوكن كقيمة افتراضية
        $parentId = auth('parent')->id() ?? auth()->id() ?? 1;

        // 👶 1. حماية مسار الرضيع (الهاردوير):
        if ($type === 'crying_detected' || $type === 'Cry') {
            // إذا كان بكاء، يُجبر الـ child_id يكون null ليروح لغرفة الرضيع مباشرة
            $finalChildId = null;
        } else {
            // 👦 2. مسار تابلت الأطفال الكبار (التنبيهات الأمنية والويب):
            
            // 🔥 الحل القاطع: نحاول نجيب الطفل المصادق عليه أولاً من التوكن (الأضمن والأقوى)
            $childUser = auth('child')->user();
            
            if ($childUser) {
                // لو الطلب جاي وتوكن الطفل شغال، بناخد بياناته فوراً ومستحيل يحصل تداخل
                $finalChildId = $childUser->id;
                $parentId = $childUser->parent_id;
            } else {
                // لو مش جاي بتوكن طفل (زي طلبات داخلية أو اختبارات)، بنقرا الـ child_id المبعوث في الـ Body
                $childId = $request->child_id;
                if (is_string($childId) && preg_match('/\d+/', $childId, $matches)) {
                    $childId = (int) $matches[0];
                }

                $tableName = Schema::hasTable('childrens') ? 'childrens' : 'children';
                $child = DB::table($tableName)->where('id', $childId)->first();

                if ($child) {
                    $finalChildId = $child->id;
                    $parentId = $child->parent_id;
                } else {
                    // 🚨 لو مفيش توكن والـ child_id تائه، ارميه لأول طفل مربوط بالأب ده بدل ما ترميه لأحدث طفل عشوائي
                    $firstChildOfParent = DB::table($tableName)->where('parent_id', $parentId)->first();
                    $finalChildId = $firstChildOfParent ? $firstChildOfParent->id : null;
                }
            }
        }
        
        $title = $request->title;
        $message = $request->message;

        // تأمين وتوحيد العناوين والرسائل
        if ($type === 'threat_blocked' || str_contains($title, 'حظر') || str_contains($title, 'blocked')) {
            $title = "Threat Blocked";
            $message = "Security system prevented downloading a suspicious file on child device. Reason: Flagged as malware.";
        } elseif ($type === 'content_blocked') {
            $title = "Restricted Website Blocked";
            $message = "Security engine intercepted a restricted webview navigation request.";
        }

        $alert = Alert::create([
            'parent_id'         => $parentId,
            'child_id'          => $finalChildId, // هينزل برقم الطفل الفعلي اللي عمل الأكشن
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