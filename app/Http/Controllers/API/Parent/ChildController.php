<?php

namespace App\Http\Controllers\API\Parent;

use Carbon\Carbon;
use App\Models\Children;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pairing_session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChildController extends Controller
{
    // عرض قائمة الأطفال مع حالة تنبيهاتهم
    public function index()
    {
        $childs = Children::with('pairingSessions')
            ->where('parent_id', auth('parent')->user()->id)
            ->orderBy('id', 'desc')
            ->get();

        $childs->map(function($child) {
            $child->alerts_count = DB::table('child_alerts')
                ->where('child_id', $child->id)
                ->where('is_read', false)
                ->count();
            return $child;
        });

        return response()->json($childs);
    }

    // تسجيل طفل جديد وإنشاء جلسة ربط فورية
    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:childrens'],
            'password' => ['required', 'confirmed', 'min:6'],
            'type' => ['required', 'in:child,teen,infant'],
            'date_of_birth' => ['required', 'date']
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        return DB::transaction(function () use ($request) {
            $child = Children::create([
                'parent_id' => auth('parent')->user()->id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'type' => $request->type,
                'date_of_birth' => $request->date_of_birth,
                'is_active' => 0, 
            ]);

            $pairingSession = Pairing_session::create([
                'parent_id' => $child->parent_id,
                'child_id' => $child->id,
                'code' => strval(rand(100000, 999999)),
                'status' => 'pending',
                'expires_at' => Carbon::now()->addMinutes(10),
            ]);

            return response()->json([
                'data' => $child, 
                'pairing_session' => $pairingSession, 
                'message' => 'تم حفظ البيانات بنجاح'
            ]);
        });
    }

    // المنطق الذكي لجلب كود الربط أو التأكد من نجاحه
    public function showPairingCode($childId)
    {
        $pairing = Pairing_session::where('child_id', $childId)
            ->orderBy('id', 'desc')
            ->first();

        if ($pairing && $pairing->status === 'completed') {
            return response()->json([
                'success' => true, 
                'is_paired' => true, 
                'message' => 'Device already connected'
            ]);
        }

        if ($pairing && $pairing->status === 'pending' && $pairing->expires_at > now()) {
            return response()->json([
                'success' => true,
                'is_paired' => false,
                'code' => $pairing->code
            ]);
        }

        Pairing_session::where('child_id', $childId)
            ->where('status', 'pending')
            ->update(['status' => 'expired']);

        $newPairing = Pairing_session::create([
            'parent_id' => auth('parent')->user()->id,
            'child_id' => $childId,
            'code' => strval(rand(100000, 999999)),
            'status' => 'pending',
            'expires_at' => now()->addMinutes(10),
        ]);

        return response()->json([
            'success' => true,
            'is_paired' => false,
            'code' => $newPairing->code
        ]);
    }

    // دالة التحقق من كود الربط (المضافة حديثاً)
    public function verifyPairing(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $session = Pairing_session::where('code', $request->code)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->first();

        if (!$session) {
            return response()->json(['message' => 'Invalid or expired pairing code.'], 404);
        }

        $session->update(['status' => 'completed']);
        
        $child = Children::find($session->child_id);
        if ($child) {
            $child->update(['is_active' => 1]);
        }

        return response()->json([
            'success' => true, 
            'message' => 'Device linked successfully!'
        ]);
    }
}