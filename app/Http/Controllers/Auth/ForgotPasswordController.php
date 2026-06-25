<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    // Send OTP to the verified email
    // Send OTP to the verified email
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $email = trim($request->email);

        $parentExists = DB::table('parents')->where('email', $email)->exists();
        $childExists = DB::table('childrens')->where('email', $email)->exists();

        if (!$parentExists && !$childExists) {
            return response()->json(['message' => 'This email address is not registered.'], 404);
        }

        $otp = rand(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => $otp,
                'created_at' => Carbon::now()
            ]
        );

        // 🚀 التعديل السحري: إرسال الـ OTP للمتغير $email الذي تم إدخاله في الشاشة
        Mail::to($email)->send(new SendOtpMail($otp));

        return response()->json(['message' => 'OTP code has been sent successfully to your email.']);
    }
    // Verify the provided OTP code
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric'
        ]);

        $email = trim($request->email);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $request->otp)
            ->first();

        if (!$reset || Carbon::parse($reset->created_at)->addMinutes(15)->isPast()) {
            return response()->json(['message' => 'Invalid or expired OTP code.'], 400);
        }

        return response()->json(['message' => 'OTP verified successfully. You can now reset your password.']);
    }

    // Reset password for the user account
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $email = trim($request->email);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $request->otp)
            ->first();

        if (!$reset) {
            return response()->json(['message' => 'Invalid request or expired session.'], 400);
        }

        $newPasswordHash = Hash::make($request->password);
        $updated = false;

        if (DB::table('parents')->where('email', $email)->exists()) {
            DB::table('parents')->where('email', $email)->update(['password' => $newPasswordHash]);
            $updated = true;
        } 
        
        if (DB::table('childrens')->where('email', $email)->exists()) {
            DB::table('childrens')->where('email', $email)->update(['password' => $newPasswordHash]);
            $updated = true;
        }

        if (!$updated) {
            return response()->json(['message' => 'Failed to update password. Account not found.'], 404);
        }

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return response()->json(['message' => 'Password has been reset successfully.']);
    }
}