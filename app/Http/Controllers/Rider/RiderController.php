<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class RiderController extends Controller
{
    public function dashboard()
    {
        $rider = auth()->user()->rider;
        
        $currentOrders = Order::where('assigned_rider_id', $rider->id)
            ->whereIn('status', ['assigned', 'picked_up'])
            ->with('customer')
            ->get();
            
        $completedToday = Order::where('assigned_rider_id', $rider->id)
            ->where('status', 'delivered')
            ->whereDate('delivered_at', today())
            ->count();
            
        $totalEarnings = Payment::where('rider_id', $rider->id)
            ->where('status', 'paid')
            ->sum('amount');
            
        return view('rider.dashboard', compact('rider', 'currentOrders', 'completedToday', 'totalEarnings'));
    }
    
    public function deliveries()
    {
        $rider = auth()->user()->rider;
        
        $currentOrders = Order::where('assigned_rider_id', $rider->id)
            ->whereIn('status', ['assigned', 'picked_up'])
            ->with('customer')
            ->get();
            
        return view('rider.deliveries', compact('rider', 'currentOrders'));
    }
    
    public function history()
    {
        $rider = auth()->user()->rider;
        
        $deliveryHistory = Order::where('assigned_rider_id', $rider->id)
            ->where('status', 'delivered')
            ->latest()
            ->paginate(10);
            
        return view('rider.history', compact('rider', 'deliveryHistory'));
    }
    
    public function earnings()
    {
        $rider = auth()->user()->rider;
        
        $totalEarnings = Payment::where('rider_id', $rider->id)
            ->where('status', 'paid')
            ->sum('amount');
            
        $weeklyEarnings = Payment::where('rider_id', $rider->id)
            ->where('status', 'paid')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('amount');
            
        $monthlyEarnings = Payment::where('rider_id', $rider->id)
            ->where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
            
        $recentPayments = Payment::where('rider_id', $rider->id)
            ->where('status', 'paid')
            ->with('order')
            ->latest()
            ->paginate(10);
            
        return view('rider.earnings', compact('rider', 'totalEarnings', 'weeklyEarnings', 'monthlyEarnings', 'recentPayments'));
    }
    
    public function toggleAvailability()
    {
        $rider = auth()->user()->rider;
        $rider->update(['is_available' => !$rider->is_available]);
        
        $status = $rider->is_available ? 'available' : 'unavailable';
        return back()->with('success', "You are now {$status} for deliveries");
    }
    
    public function profile()
{
    $rider = auth()->user()->rider;
    $user = auth()->user();
    
    return view('rider.profile', compact('rider', 'user'));
}
    
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);
        
        $user->update($validated);
        
        return redirect()->route('rider.profile')->with('success', 'Profile updated successfully!');
    }
    public function pendingOrders()
{
    $pendingOrders = Order::where('assigned_rider_id', auth()->user()->rider->id)
        ->where('status', Order::STATUS_SENT)
        ->get();
        
    return view('rider.pending-orders', compact('pendingOrders'));
}

public function acceptOrder(Order $order)
{
    $order->update([
        'status' => Order::STATUS_ACCEPTED,
        'rider_responded_at' => now(),
    ]);
    
    // Notify staff
    return back()->with('success', 'Order accepted! Waiting for staff approval.');
}

public function declineOrder(Request $request, Order $order)
{
    $order->update([
        'status' => Order::STATUS_DECLINED,
        'rider_responded_at' => now(),
        'rider_decline_reason' => $request->reason,
    ]);
    
    return back()->with('success', 'Order declined. Staff will be notified.');
}


public function getOrderDetails(Order $order)
{
    $rider = auth()->user()->rider;
    
    if ($order->assigned_rider_id !== $rider->id) {
        abort(403);
    }
    
    return response()->json([
        'id' => $order->id,
        'pickup_location' => $order->pickup_location,
        'dropoff_location' => $order->dropoff_location,
        'item_description' => $order->item_description,
        'estimated_weight_kg' => $order->estimated_weight_kg,
        'required_vehicle_type' => $order->required_vehicle_type,
        'delivery_instructions' => $order->delivery_instructions,
        'timeout_minutes' => $order->timeout_expires_at ? now()->diffInMinutes($order->timeout_expires_at) : 30,
    ]);
}
}