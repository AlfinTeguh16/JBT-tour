<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function store(Request $request, WorkSession $workSession)
    {
        $data = $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        if ($workSession->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $location = $workSession->driverLocations()->create([
            'user_id'     => auth()->id(),
            'latitude'    => $data['latitude'],
            'longitude'   => $data['longitude'],
            'recorded_at' => now(),
        ]);

        return response()->json(['success' => true, 'location' => $location]);
    }

}
