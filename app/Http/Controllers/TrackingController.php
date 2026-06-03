<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Rider;

class TrackingController extends Controller
{
    public function show(Order $order)
    {
        // Check if order belongs to customer
        if ($order->customer_id !== auth()->id()) {
            abort(403);
        }
        
        $rider = $order->assignedRider;
        
        return view('tracking.show', compact('order', 'rider'));
    }
    
    public function getRiderLocation(Rider $rider)
    {
        return response()->json([
            'lat' => $rider->current_lat,
            'lng' => $rider->current_lng,
            'last_update' => $rider->updated_at->diffForHumans()
        ]);
    }
}