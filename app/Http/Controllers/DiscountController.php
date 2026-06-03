<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DiscountController extends Controller
{
    // Get available discounts for current customer
    public function getAvailableDiscounts()
    {
        $discounts = Discount::where('user_id', Auth::id())
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->orderBy('discount_amount', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'discounts' => $discounts
        ]);
    }
    
    // Apply discount to order
    public function applyDiscount(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'discount_id' => 'required|exists:discounts,id',
        ]);
        
        $order = Order::find($request->order_id);
        $discount = Discount::find($request->discount_id);
        
        // Check ownership
        if ($order->customer_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        if ($discount->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'This discount does not belong to you'], 400);
        }
        
        // Check discount validity
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
        
        // Check minimum spend
        if ($subtotal < $discount->min_spend) {
            return response()->json([
                'success' => false,
                'message' => "Minimum spend of ₱{$discount->min_spend} required. Your total is ₱" . number_format($subtotal, 2)
            ], 400);
        }
        
        // Calculate new total
        $newTotal = $subtotal - $discount->discount_amount;
        if ($newTotal < 0) $newTotal = 0;
        
        $riderShare = $newTotal * 0.7;
        $adminShare = $newTotal * 0.3;
        
        // Update order
        $order->update([
            'discount_id' => $discount->id,
            'discount_code' => $discount->code,
            'discount_amount' => $discount->discount_amount,
            'delivery_fee' => $newTotal,
            'rider_earnings' => $riderShare,
            'admin_earnings' => $adminShare,
        ]);
        
        // Mark discount as used
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