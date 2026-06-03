<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Rider;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function pendingOrders()
    {
        $pendingOrders = Order::where('status', Order::STATUS_PENDING)
            ->with('customer')
            ->latest()
            ->get();
        
        $availableRiders = Rider::where('is_available', true)
            ->with('user', 'vehicle')
            ->get();
        
        return view('staff.pending-orders', compact('pendingOrders', 'availableRiders'));
    }
    
    public function readyToSend()
    {
        $pendingOrders = Order::where('status', Order::STATUS_PENDING)
            ->with('customer')
            ->get();
        
        $availableRiders = Rider::where('is_available', true)
            ->with('user', 'vehicle')
            ->get();
        
        return view('staff.ready-to-send', compact('pendingOrders', 'availableRiders'));
    }
    
    public function sendToRider(Request $request, Order $order)
    {
        $validated = $request->validate([
            'rider_id' => 'required|exists:riders,id',
        ]);
        
        if ($order->status !== Order::STATUS_PENDING) {
            return back()->with('error', 'Only pending orders can be sent to riders.');
        }
        
        $order->update([
            'status' => Order::STATUS_SENT,
            'sent_to_rider_at' => now(),
            'assigned_rider_id' => $validated['rider_id'],
            'assigned_staff_id' => auth()->id(),
            'timeout_expires_at' => now()->addMinutes(30),
        ]);
        
        return back()->with('success', 'Order sent to rider for acceptance.');
    }
    
    public function acceptedOrders()
    {
        $acceptedOrders = Order::where('status', Order::STATUS_ACCEPTED)
            ->with('customer', 'assignedRider.user')
            ->latest()
            ->get();
        
        return view('staff.accepted-orders', compact('acceptedOrders'));
    }
    
    public function approveAccepted(Order $order)
    {
        if ($order->status !== Order::STATUS_ACCEPTED) {
            return back()->with('error', 'Only accepted orders can be approved.');
        }
        
        $order->update([
            'status' => Order::STATUS_APPROVED,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        
        $order->update([
            'status' => Order::STATUS_ASSIGNED,
            'assigned_at' => now(),
        ]);
        
        return back()->with('success', 'Order approved! Rider can now proceed with delivery.');
    }
    
    public function readyToAssign()
    {
        $approvedOrders = Order::where('status', Order::STATUS_APPROVED)
            ->with('customer')
            ->latest()
            ->get();
        
        $availableRiders = Rider::where('is_available', true)
            ->with('user', 'vehicle')
            ->get();
        
        return view('staff.ready-to-assign', compact('approvedOrders', 'availableRiders'));
    }
    
    public function availableRiders()
    {
        $availableRiders = Rider::where('is_available', true)
            ->with('user', 'vehicle')
            ->get();
        
        return view('staff.available-riders', compact('availableRiders'));
    }
}