<?php

namespace App\Http\Controllers\API\Child;

use App\Http\Controllers\Controller;
use App\Models\Crying_log;
use App\Models\CryingLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CryingLogsController extends Controller
{
    public function index()
    {
        $child = auth('child')->user();
        $logs = Crying_log::where('child_id', $child->id)->get();

        return response()->json($logs);
    }

    public function store(Request $request)
    {
        $child = auth('child')->user();

        $request->validate([
            'duration_seconds' => 'required|integer',
            'intensity' => 'nullable|numeric'
        ]);

        $log = Crying_log::create([
            'child_id' => $child->id,
            'duration_seconds' => $request->duration_seconds,
            'intensity' => $request->intensity
        ]);

        return response()->json($log, 201);
    }

    public function show($uuid)
    {
        $log = Crying_log::where('uuid', $uuid)->firstOrFail();
        return response()->json($log);
    }
}
