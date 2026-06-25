<?php

namespace App\Http\Controllers\API\Parent;

use App\Http\Controllers\Controller;
use App\Models\Children; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ChildManagementController extends Controller
{
    /**
     * دالة مساعدة للبحث عن طفل تابع للأب الحالي فقط (توفر أمان عالي لمنع تسريب البيانات)
     */
    private function findChild(Request $request, $id)
    {
        return $request->user()
            ->children() 
            ->findOrFail($id);
    }

    /**
     * جلب قائمة الأطفال
     */
    public function index(Request $request)
    {
        $children = $request->user()->children; 

        return response()->json([
            'status' => true,
            'data' => $children
        ], 200);
    }

    /**
     * جلب بيانات طفل محدد
     */
    public function show(Request $request, $id)
    {
        $child = $this->findChild($request, $id);

        return response()->json([
            'status' => true,
            'data' => $child
        ], 200);
    }

    /**
     * إضافة طفل جديد مع صورة الشخصية
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:children,email',
            'password'      => 'required|string|min:6|confirmed',
            'date_of_birth' => 'required|date|before:today',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $imagePath = null;
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('children_profiles', 'public');
        }

        $child = Children::create([
            'uuid'          => (string) Str::uuid(),
            'parent_id'     => $request->user()->id,
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'password'      => Hash::make($validated['password']),
            'date_of_birth' => $validated['date_of_birth'],
            'profile_image' => $imagePath, 
            'is_active'     => true,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Child created successfully',
            'data'    => $child->makeHidden(['password'])
        ], 201);
    }

    /**
     * تحديث بيانات الطفل والصورة الشخصية
     */
    public function update(Request $request, $id)
    {
        $child = $this->findChild($request, $id);

        $validated = $request->validate([
            'name'          => 'sometimes|string|max:255',
            'email'         => [
                'sometimes',
                'email',
                Rule::unique('children', 'email')->ignore($child->id),
            ],
            'date_of_birth' => 'sometimes|date|before:today',
            'profile_image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active'     => 'sometimes|boolean',
        ]);

        if ($request->hasFile('profile_image')) {
            if ($child->profile_image) {
                Storage::disk('public')->delete($child->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('children_profiles', 'public');
        }

        $child->update($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Child updated successfully',
            'data'    => $child->makeHidden(['password'])
        ], 200);
    }

    /**
     * تغيير كلمة مرور الطفل من لوحة التحكم للأب
     */
    public function changeChildPassword(Request $request, $id)
    {
        $validated = $request->validate([
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $child = $this->findChild($request, $id);

        $child->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Child password changed successfully'
        ], 200);
    }

    /**
     * حذف طفل بشكل نهائي مع ملف صورته
     */
    public function destroy(Request $request, $id)
    {
        $child = $this->findChild($request, $id);
        
        if ($child->profile_image) {
            Storage::disk('public')->delete($child->profile_image);
        }

        $child->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Child deleted successfully'
        ], 200);
    }

    /**
     * جلب طلبات التطبيقات المعلقة والمرفوضة الخاصة بطفل محدد للأب
     */
    public function getPendingRequests(Request $request, $child_id)
{
    $child = $this->findChild($request, $child_id); //[cite: 11]

    // جلب طلبات هذا الطفل فقط وعمل ربط مستقل للحصول على أيقونته الخاصة المرفوعة
    $requests = \App\Models\App_request::where('app_requests.child_id', $child->id)
        ->whereIn('app_requests.status', ['pending', 'rejected'])
        ->leftJoin('child_apps', function($join) {
            $join->on('app_requests.package_name', '=', 'child_apps.package_name')
                 ->on('app_requests.child_id', '=', 'child_apps.child_id');
        })
        ->select('app_requests.*', 'child_apps.app_icon') // 👈 جلب الحقل بشكل منعزل ومستقل تماماً
        ->get();

    return response()->json([
        'status' => 'success',
        'data' => $requests
    ], 200);
}
    /**
     * 🔥 تحديث حالة الطلب بشكل آمن ومحمي 100% مع معالجة فك الحظر اللحظي
     */
    public function updateRequestStatus(Request $request)
    {
        $validated = $request->validate([
            'request_id' => 'required|integer',
            'status'     => 'required|string|in:approved,rejected',
        ]);

        // 🌟 حل ثغرة الأمن: جلب طلب التطبيق مع التأكد التام أنه يخص أطفال هذا الأب فقط لمنع التلاعب بالـ IDs
        $childIds = $request->user()->children()->pluck('id')->toArray();

        $appRequest = \App\Models\App_request::whereIn('child_id', $childIds)
            ->findOrFail($validated['request_id']);
        
        // 2. تحديث الحقل الرئيسي للطلب بنجاح
        $appRequest->update([
            'status' => $validated['status']
        ]);

        // 3. إذا قام الأب بالموافقة (Approved)، يتم فك الحظر الفوري وتطهير الكاش
        if ($validated['status'] === 'approved') {
            try {
                // 🌟 جعل الحظر 0 صراحة لضمان معالجة دقيقة في الأندرويد لفك الحظر اللحظي
                DB::table('child_apps')
                    ->where('child_id', $appRequest->child_id)
                    ->where('package_name', $appRequest->package_name)
                    ->update([
                        'is_blocked' => 0, 
                        'status'     => 'allowed', 
                        'updated_at' => now()
                    ]);

                // ⚡ ميزة إضافية: مسح أو إلغاء أي طلبات تكرارية معلقة لنفس الحزمة لضمان نظافة لوحة التحكم
                DB::table('app_requests')
                    ->where('child_id', $appRequest->child_id)
                    ->where('package_name', $appRequest->package_name)
                    ->where('id', '!=', $appRequest->id)
                    ->delete();

                \Log::info("✅ [Request Approved & Synced]: " . $appRequest->package_name . " for child ID: " . $appRequest->child_id);

            } catch (\Exception $e) {
                \Log::error("Child apps update integration failed: " . $e->getMessage());
            }
        }

        // 4. إرجاع الرد النهائي السليم فوراً للأب لتحديث واجهة الـ React Native بدون أخطاء
        return response()->json([
            'status' => 'success',
            'message' => 'App request updated to ' . $validated['status'],
            'data' => $appRequest
        ], 200);
    }
}