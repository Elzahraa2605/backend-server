<?php

namespace App\Http\Controllers\API\Parent;

use App\Http\Controllers\Controller;
use App\Models\App_request;
use App\Models\Children;
use Illuminate\Http\Request;

class ParentAppRequestController extends Controller
{
    // عرض كل طلبات الأبناء
    public function index()
    {
        $parent = auth('parent')->user();

        $requests = App_request::whereHas('child', function ($q) use ($parent) {
            $q->where('parent_id', $parent->id);
        })->get();

        return response()->json([
            'status' => 'success',
            'data' => $requests
        ]);
    }

    // عرض طلب محدد
    public function show($uuid)
    {
        $parent = auth('parent')->user();

        $requestItem = App_request::where('uuid', $uuid)
            ->whereHas('child', function ($q) use ($parent) {
                $q->where('parent_id', $parent->id);
            })->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => $requestItem
        ]);
    }

    // الموافقة أو الرفض على الطلب
    public function update(Request $request, $uuid)
    {
        $parent = auth('parent')->user();

        $requestItem = App_request::where('uuid', $uuid)
            ->whereHas('child', function ($q) use ($parent) {
                $q->where('parent_id', $parent->id);
            })->firstOrFail();

        $validator = validator($request->all(), [
            'status' => ['required', 'in:approved,rejected'],
            'parent_response' => ['nullable', 'string']
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $requestItem->update([
            'status' => $request->status,
            'parent_response' => $request->parent_response
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $requestItem
        ]);
    }
}
