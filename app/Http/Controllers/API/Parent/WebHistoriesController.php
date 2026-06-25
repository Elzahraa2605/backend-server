<?php

namespace App\Http\Controllers\API\Parent;

use App\Http\Controllers\Controller;
use App\Models\Web_history;
use Illuminate\Http\Request;

class WebHistoriesController extends Controller
{
    public function index(Request $request)
    {
        $parent = auth('parent')->user();
        $childrenIds = $parent->childrens()->pluck('id');

        $query = Web_history::whereIn('child_id', $childrenIds);

        if ($request->child_id) {
            $query->where('child_id', $request->child_id);
        }
        if ($request->category) {
            $query->byCategory($request->category);
        }
        if ($request->blocked === '1') {
            $query->blocked();
        }
        if ($request->blocked === '0') {
            $query->allowed();
        }
        if ($request->from && $request->to) {
            $query->whereBetween('visited_at', [$request->from, $request->to]);
        }

        $histories = $query->latest()->paginate(20);
        return response()->json([
            'data' => $histories,
            'message' => 'تم'
        ]);
    }

    public function show($uuid)
    {
        $parent = auth('parent')->user();
        $childrenIds = $parent->childrens()->pluck('id');

        $history = Web_history::where('uuid', $uuid)
            ->whereIn('child_id', $childrenIds)
            ->firstOrFail();

        return response()->json([
            'data' => $history,
            'message' => 'تم'
        ]);
    }

    public function destroy($uuid)
    {
        $parent = auth('parent')->user();
        $childrenIds = $parent->childrens()->pluck('id');

        $history = Web_history::where('uuid', $uuid)
            ->whereIn('child_id', $childrenIds)
            ->firstOrFail();

        $history->delete();

        return response()->json([
            'message' => 'تم الحذف بنجاح'
        ]);
    }
    public function toggleBlock($uuid)
    {
        $parent = auth('parent')->user();
        $childrenIds = $parent->childrens()->pluck('id');

        $history = Web_history::where('uuid', $uuid)
            ->whereIn('child_id', $childrenIds)
            ->firstOrFail();

        $history->update([
            'is_blocked' => !$history->is_blocked
        ]);

        return response()->json([
            'data' => $history,
            'message' => $history->is_blocked ? 'تم الحظر' : 'تم إلغاء الحظر'
        ]);
    }
}
