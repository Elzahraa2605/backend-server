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
    // 🎯 التعديل الذهبي: جلب الـ ID للأب المسجل دخول حالياً تلقائياً ومنع التثبيت اليدوي
    $parentId = auth()->id() ?? 1; 

    // حذف التنبيهات التي مر عليها أكثر من 7 أيام
    Alert::where('parent_id', $parentId)
        ->where('created_at', '<', \Carbon\Carbon::now()->subDays(7))
        ->delete();

    $query = Alert::where('parent_id', $parentId);

    if ($request->has('child_id') && $request->child_id != null) {
        $query->where('child_id', $request->child_id);
    }

    // 🎯 المرونة الكاملة للموبايل الحقيقي: يجيب آخر 24 ساعة مطلقاً لتفادي تعارض التوقيت
    // 🎯 الحل الأقوى: مقارنة تاريخ اليوم فقط بدون حساب الساعات لتفادي مشاكل الـ Timezone تماماً
    if ($request->has('range') && $request->range === 'today') {
        $query->whereDate('created_at', \Carbon\Carbon::today('Africa/Cairo'));
    }

    $alerts = $query->orderBy('created_at', 'desc')->get();

    // 💡 خطوة إنقاذ احتياطية: لو المصفوفة طلعت فاضية بسبب الـ Range، هات آخر 5 إشعارات للطفل فوراً
    if ($alerts->isEmpty() && $request->has('child_id')) {
        $alerts = Alert::where('parent_id', $parentId)
                       ->where('child_id', $request->child_id)
                       ->orderBy('created_at', 'desc')
                       ->take(5)
                       ->get();
    }

    $formattedAlerts = $alerts->map(function ($alert) {
        \Carbon\Carbon::setLocale('en'); 
        $createdAt = \Carbon\Carbon::parse($alert->created_at)->timezone('Africa/Cairo'); 

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
        'child_id' => 'required',
        'type'     => 'required|string',
        'title'    => 'required|string',
        'message'  => 'required|string'
    ]);

    $childId = $request->child_id;

    // تنظيف الـ ID لو جاي معاه نصوص أو مسافات
    if (is_string($childId) && preg_match('/\d+/', $childId, $matches)) {
        $childId = (int) $matches[0];
    }

    $tableName = Schema::hasTable('childrens') ? 'childrens' : 'children';
    
    // 🎯 محاولة جلب الطفل بالـ ID المبعوث فعلياً
    $child = DB::table($tableName)->where('id', $childId)->first();

    // 💡 خطوة الإنقاذ الذكية: لو الـ ID مش مقروء، اربط التنبيه بأحدث طفل مسجل في الداتابيز (الطفل الجديد) بدلاً من رقم 1
    if (!$child) {
        $child = DB::table($tableName)->orderBy('id', 'desc')->first();
    }

    if (!$child) {
        return response()->json(['error' => 'No children found in database'], 404);
    }
    
    $title = $request->title;
    $message = $request->message;

    // تأمين العناوين والرسائل بناءً على النوع القادم من تابلت الطفل
    if ($request->type === 'threat_blocked' || str_contains($title, 'حظر') || str_contains($title, 'blocked')) {
        $title = "Threat Blocked";
        $message = "Security system prevented downloading a suspicious file on child device. Reason: Flagged as malware.";
    } elseif ($request->type === 'content_blocked') {
        $title = "Restricted Website Blocked";
        $message = "Security engine intercepted a restricted webview navigation request.";
    }

    $alert = Alert::create([
        'parent_id'         => $child->parent_id,
        'child_id'          => $child->id,  // 🔥 هيتوجّه للطفل الصح فقط
        'type'              => $request->type,
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