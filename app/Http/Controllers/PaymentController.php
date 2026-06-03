<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use App\Models\Discount;
class PaymentController extends Controller
{
    public function show(Order $order)
    {
        if ($order->customer_id !== Auth::id()) {
            abort(403);
        }
        
        // Calculate fees for display
        $basePrice = 50;
        $distanceFee = ($order->distance_km ?? 0) * 15;
        $weightFee = ($order->estimated_weight_kg ?? 0) * 10;
        $sizeFee = ($order->estimated_size_cm ?? 0) * 0.5;
        $vehicleFees = ['motor' => 0, 'car' => 50, 'van' => 75, 'L300' => 80, 'truck' => 100];
        $vehicleFee = $vehicleFees[$order->required_vehicle_type] ?? 0;
        
        $subtotal = $basePrice + $distanceFee + $weightFee + $sizeFee + $vehicleFee;
        $total = $subtotal - ($order->discount_amount ?? 0);
        
        return view('payment.show', compact('order', 'subtotal', 'total', 'distanceFee', 'weightFee', 'sizeFee', 'vehicleFee', 'basePrice'));
    }
    
    public function process(Request $request, Order $order)
    {
        if ($order->customer_id !== Auth::id()) {
            abort(403);
        }
        
        Payment::create([
            'order_id' => $order->id,
            'customer_id' => Auth::id(),
            'amount' => $order->delivery_fee ?? 50,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
        ]);
        
        $order->update(['payment_status' => 'paid']);
        
        return redirect()->route('dashboard')->with('success', 'Payment successful!');
    }
    public function applyDiscount(Request $request)
{
    $request->validate([
        'order_id' => 'required|exists:orders,id',
        'discount_id' => 'required|exists:discounts,id',
    ]);
    
    $order = Order::find($request->order_id);
    $discount = \App\Models\Discount::find($request->discount_id);
    
    if ($order->customer_id !== Auth::id()) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }
    
    if ($discount->user_id !== Auth::id()) {
        return response()->json(['success' => false, 'message' => 'This discount is not yours'], 400);
    }
    
    if ($discount->status !== 'active') {
        return response()->json(['success' => false, 'message' => 'Discount already used'], 400);
    }
    
    if ($discount->expires_at && $discount->expires_at < now()) {
        return response()->json(['success' => false, 'message' => 'Discount expired'], 400);
    }
    
    // Calculate current subtotal
    $basePrice = 50;
    $distanceFee = ($order->distance_km ?? 0) * 15;
    $weightFee = ($order->estimated_weight_kg ?? 0) * 10;
    $sizeFee = ($order->estimated_size_cm ?? 0) * 0.5;
    $vehicleFees = ['motor' => 0, 'car' => 50, 'van' => 75, 'L300' => 80, 'truck' => 100];
    $vehicleFee = $vehicleFees[$order->required_vehicle_type] ?? 0;
    
    $subtotal = $basePrice + $distanceFee + $weightFee + $sizeFee + $vehicleFee;
    
    if ($subtotal < $discount->min_spend) {
        return response()->json([
            'success' => false,
            'message' => "Minimum spend of ₱{$discount->min_spend} required. Your total is ₱" . number_format($subtotal, 2)
        ], 400);
    }
    
    $newTotal = $subtotal - $discount->discount_amount;
    if ($newTotal < 0) $newTotal = 0;
    
    $riderShare = $newTotal * 0.7;
    $adminShare = $newTotal * 0.3;
    
    $order->update([
        'discount_id' => $discount->id,
        'discount_code' => $discount->code,
        'discount_amount' => $discount->discount_amount,
        'delivery_fee' => $newTotal,
        'rider_earnings' => $riderShare,
        'admin_earnings' => $adminShare,
    ]);
    
    $discount->update([
        'status' => 'used',
        'used_at' => now(),
    ]);
    
    return response()->json([
        'success' => true,
        'new_total' => $newTotal,
        'discount_amount' => $discount->discount_amount,
        'message' => "₱" . number_format($discount->discount_amount, 2) . " discount applied!"
    ]);
}
}