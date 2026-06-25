<?php

namespace App\Http\Controllers\API\Child;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    
    public function store(Request $request)
    {
        
        $validator = validator($request->all(), [
            'child_id'  => ['required', 'exists:childrens,id'], 
            'latitude'  => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'address'   => ['nullable', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $childId = $request->child_id;

        try {
            
            Location::where('child_id', $childId)
                ->where('is_latest', true)
                ->update(['is_latest' => false]);

            
            $location = Location::create([
                'child_id'  => $childId,
                'latitude'  => $request->latitude,
                'longitude' => $request->longitude,
                'address'   => $request->address,
                'is_latest' => true
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Location updated successfully',
                'data' => $location
            ]);

        } catch (\Exception $e) {
            
            return response()->json([
                'status' => false,
                'message' => 'Server Error: ' . $e->getMessage()
            ], 500);
        }
    }
}