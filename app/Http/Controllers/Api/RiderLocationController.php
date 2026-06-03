<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\RiderLocationUpdated;

class RiderLocationController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);
        
        $rider = auth()->user()->rider;
        $rider->updateLocation($request->lat, $request->lng);
        
        broadcast(new RiderLocationUpdated($rider, $request->lat, $request->lng))->toOthers();
        
        return response()->json(['success' => true]);
    }
}