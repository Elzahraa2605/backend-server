<?php

namespace App\Http\Controllers\API\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller 
{
    public function show(Request $request)
    {
        $parent = $request->user();

        return response()->json([
            'status' => true,
            'data' => $parent->makeHidden(['password', 'remember_token'])
        ]);
    }

    public function update(Request $request)
    {
        $parent = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'email',
                Rule::unique('parents', 'email')->ignore($parent->id),
            ],
            'phone' => 'sometimes|nullable|string|max:20',
            'profile_image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048', // تعديل لنوع الصورة
        ]);

        // معالجة رفع صورة الأب
        if ($request->hasFile('profile_image')) {
            // حذف الصورة القديمة إذا وجدت
            if ($parent->profile_image) {
                Storage::disk('public')->delete($parent->profile_image);
            }
            // حفظ الجديدة
            $validated['profile_image'] = $request->file('profile_image')->store('parents_profiles', 'public');
        }

        $parent->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
            'data' => $parent->makeHidden(['password', 'remember_token'])
        ]);
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $parent = $request->user();

        if (!Hash::check($validated['current_password'], $parent->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Current password is incorrect',
            ]);
        }

        $parent->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Password changed successfully'
        ]);
    }

    /**
     * دالة حذف مجموعة العائلة (فوق حذف الحساب)
     */
    public function deleteFamilyGroup(Request $request)
    {
        $parent = $request->user();
        $children = $parent->children; // تأكدي أن العلاقة معرفة في موديل Parent

        if ($children->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No family group found to delete'
            ], 404);
        }

        foreach ($children as $child) {
            // حذف صور الأطفال من السيرفر
            if ($child->profile_image) {
                Storage::disk('public')->delete($child->profile_image);
            }
        }

        // حذف الأطفال من الداتابيز
        $parent->children()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Family group deleted successfully'
        ], 200);
    }
}