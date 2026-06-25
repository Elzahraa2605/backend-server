<?php

namespace App\Http\Controllers\API\Child;

use App\Http\Controllers\Controller;
use App\Models\App_request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChildAppRequestController extends Controller
{
    
    public function index()
    {
        $child = auth('child')->user();
        $requests = App_request::where('child_id', $child->id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $requests
        ]);
    }

    
    public function store(Request $request)
    {
        
        $validator = validator($request->all(), [
            'app_name' => ['required', 'string'],
            'package_name' => ['required', 'string'],
            'category' => ['nullable', 'string'], 
            'reason' => ['nullable', 'string'],
            'child_id' => ['nullable', 'integer'] 
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        
        $child = auth('child')->user();
        $childId = $child ? $child->id : $request->child_id;

        
        $exists = App_request::where('child_id', $childId)
            ->where('package_name', $request->package_name)
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'success',
                'message' => 'App is already recorded as pending and remains blocked.'
            ], 200);
        }

        
        $appRequest = App_request::create([
            'child_id' => $childId,
            'app_name' => $request->app_name,
            'package_name' => $request->package_name,
            'category' => $request->category ?? 'general', 
            'reason' => $request->reason ?? 'تم اكتشاف تطبيق جديد وحظره تلقائياً لحين موافقة الأب',
            'status' => 'pending' 
        ]);

        
        if ($child && $child->parent && $child->parent->fcm_token) {
            $this->sendNotification(
                $child->parent->fcm_token,
                "تطبيق جديد قيد الانتظار ⚠️",
                "قام طفلك بتحميل: (" . $request->app_name . ") وتم حظره تلقائياً لحين مراجعتك."
            );
        }

        return response()->json([
            'status' => 'success',
            'message' => 'App logged as pending and blocked automatically',
            'data' => $appRequest
        ], 201);
    }

    
    private function sendNotification($fcmToken, $title, $body)
    {
        
        $SERVER_API_KEY = env('FCM_SERVER_KEY', 'YOUR_FIREBASE_SERVER_KEY_HERE'); 

        $data = [
            "to" => $fcmToken,
            "notification" => [
                "title" => $title,
                "body" => $body,
                "sound" => "default",
                "click_action" => "FLUTTER_NOTIFICATION_CLICK" 
            ],
            "priority" => "high"
        ];

        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    
    public function show($uuid)
    {
        $child = auth('child')->user();
        $requestItem = App_request::where('uuid', $uuid)
            ->where('child_id', $child->id)
            ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => $requestItem
        ]);
    }

    
    public function destroy($uuid)
    {
        $child = auth('child')->user();
        $requestItem = App_request::where('uuid', $uuid)
            ->where('child_id', $child->id)
            ->firstOrFail();

        if ($requestItem->status !== 'pending') {
            return response()->json(['message' => 'Cannot delete a processed request'], 403);
        }

        $requestItem->delete();
        return response()->json(['message' => 'Request deleted']);
    }
}