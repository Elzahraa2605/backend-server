<?php

namespace App\Http\Controllers\API\Child;

use App\Http\Controllers\Controller;
use App\Models\Web_history;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WebHistoriesController extends Controller
{

    public function index(Request $request)
    {
        $child = auth('child')->user();

        $query = Web_history::where('child_id', $child->id);

        if ($request->category) {
            $query->byCategory($request->category);
        }
        if ($request->blocked === '1') {
            $query->blocked();
        }

        if ($request->blocked === '0') {
            $query->allowed();
        }
        $histories = $query->latest()->paginate(20);

        return response()->json([
            'data' => $histories,
            'message' => 'تم'
        ]);
    }

    public function store(Request $request)
    {
        $child = auth('child')->user();

        $validated = $request->validate([
            'url' => ['required', 'url', 'max:2048'],
            'domain' => ['required', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:500'],
            'category' => [
                'nullable',
                Rule::in([
                    Web_history::CATEGORY_SOCIAL,
                    Web_history::CATEGORY_EDUCATIONAL,
                    Web_history::CATEGORY_ENTERTAINMENT,
                    Web_history::CATEGORY_SHOPPING,
                    Web_history::CATEGORY_UNKNOWN,
                ])
            ],
            'visited_at' => ['required', 'date', 'before_or_equal:now'],
        ]);

        $parsedDomain = parse_url($validated['url'], PHP_URL_HOST);
        if ($parsedDomain !== $validated['domain']) {
            return response()->json([
                'message' => 'الدومين لا يطابق الرابط'
            ], 422);
        }

        $history = Web_history::create([
            'child_id' => $child->id,
            'url' => $validated['url'],
            'domain' => $validated['domain'],
            'title' => $validated['title'] ?? null,
            'category' => $validated['category'] ?? Web_history::CATEGORY_UNKNOWN,
            'visited_at' => $validated['visited_at'],
            'is_blocked' => false
        ]);

        return response()->json([
            'data' => $history,
            'message' => 'تم اضافه بنجاح'
        ], 201);
    }

    public function show($uuid)
    {
        $child = auth('child')->user();

        $history = Web_history::where('uuid', $uuid)
            ->where('child_id', $child->id)
            ->firstOrFail();

        return response()->json([
            'data' => $history,
            'message' => 'تم'
        ]);
    }
}
