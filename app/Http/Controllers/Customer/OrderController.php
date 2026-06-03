<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('customer_id', auth()->id())
            ->with('assignedRider.user')
            ->latest()
            ->paginate(10);
            
        return view('customer.orders', compact('orders'));
    }
    
    public function show(Order $order)
    {
        if ($order->customer_id !== auth()->id()) {
            abort(403);
        }
        
        // Remove 'statusHistory' from load - it doesn't exist
        $order->load(['assignedRider.user', 'payment']);
        
        return view('customer.order-details', compact('order'));
    }
}