<?php

namespace App\Http\Controllers\API\Parent;

use App\Http\Controllers\Controller;
use App\Models\Children;
use Illuminate\Http\Request;

class ParentLocationController extends Controller
{
    /**
     * جلب قائمة الأطفال مع أحدث موقع لكل طفل
     */
    public function index()
    {
        $parent = auth('parent')->user();

        $children = Children::where('parent_id', $parent->id)
            ->with(['locations' => function ($query) {
                $query->where('is_latest', true); // جلب الموقع الأحدث فقط
            }])
            ->get();

        return response()->json([
            'status' => true,
            'data' => $children
        ]);
    }

    /**
     * جلب أحدث موقع لطفل معين فقط
     */
    public function show($child_id)
    {
        $parent = auth('parent')->user();

        $child = Children::where('id', $child_id)
            ->where('parent_id', $parent->id)
            ->with(['locations' => function ($query) {
                $query->where('is_latest', true); // جلب الموقع الأحدث فقط
            }])
            ->firstOrFail();

        // نرسل الموقع الأحدث ككائن مباشرة لتسهيل استخدامه في الفرونت إند
        return response()->json([
            'status' => true,
            'data' => [
                'child_name' => $child->name,
                'location'   => $child->locations->first() ?? null 
            ]
        ]);
    }
}