<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Rider;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use App\Events\OrderStatusUpdated;
use App\Events\NewOrderNotification;

class OrderController extends Controller
{
    // Show create order form
    public function create()
    {
        $this->authorizeRole(['customer']);
        
        $vehicleTypes = Order::VEHICLE_TYPES;
        
        return view('orders.create', compact('vehicleTypes'));
    }
    
    // Store new order with pricing - Redirect to payment page
    public function store(Request $request)
    {
        $this->authorizeRole(['customer']);
        
        $validated = $request->validate([
            'pickup_location' => 'required|string|max:255',
            'dropoff_location' => 'required|string|max:255',
            'item_description' => 'required|string',
            'estimated_weight_kg' => 'nullable|numeric|min:0',
            'estimated_size_cm' => 'nullable|numeric|min:0',
            'required_vehicle_type' => 'required|in:motor,car,truck,van,L300',
            'delivery_instructions' => 'nullable|string|max:500',
            'distance_km' => 'nullable|numeric',
            'calculated_fee' => 'nullable|numeric',
            'rider_earnings' => 'nullable|numeric',
            'admin_earnings' => 'nullable|numeric',
            'discount_code' => 'nullable|string',
        ]);
        
        // Geocode pickup and dropoff locations (optional - continue even if fails)
        $pickupCoords = null;
        $dropoffCoords = null;
        
        try {
            $pickupCoords = $this->geocodeAddress($validated['pickup_location']);
            $dropoffCoords = $this->geocodeAddress($validated['dropoff_location']);
        } catch (\Exception $e) {
            \Log::warning('Geocoding failed: ' . $e->getMessage());
            // Continue without coordinates
        }
        
        // Calculate delivery fee if not provided
        $deliveryFee = $validated['calculated_fee'] ?? 50;
        $riderEarnings = $validated['rider_earnings'] ?? ($deliveryFee * 0.7);
        $adminEarnings = $validated['admin_earnings'] ?? ($deliveryFee * 0.3);
        
        // Apply discount if code exists
        $discountAmount = 0;
        $appliedDiscountCode = null;
        
        if ($request->discount_code) {
            $discount = \App\Models\Discount::where('code', $request->discount_code)
                ->where('status', 'active')
                ->where('expires_at', '>', now())
                ->first();
            
            if ($discount && $discount->user_id === Auth::id()) {
                if ($deliveryFee >= $discount->min_spend) {
                    $discountAmount = $discount->discount_amount;
                    $deliveryFee = max(0, $deliveryFee - $discountAmount);
                    $appliedDiscountCode = $request->discount_code;
                    $discount->markAsUsed();
                }
            }
        }
        
        $order = Order::create([
            'customer_id' => Auth::id(),
            'pickup_location' => $validated['pickup_location'],
            'dropoff_location' => $validated['dropoff_location'],
            'item_description' => $validated['item_description'],
            'estimated_weight_kg' => $validated['estimated_weight_kg'] ?? null,
            'estimated_size_cm' => $validated['estimated_size_cm'] ?? null,
            'required_vehicle_type' => $validated['required_vehicle_type'],
            'delivery_instructions' => $validated['delivery_instructions'] ?? null,
            'distance_km' => $validated['distance_km'] ?? null,
            'delivery_fee' => $deliveryFee,
            'rider_earnings' => $riderEarnings,
            'admin_earnings' => $adminEarnings,
            'status' => Order::STATUS_PENDING,
            'payment_status' => 'unpaid',
            'discount_code' => $appliedDiscountCode,
            'discount_amount' => $discountAmount,
            'pickup_lat' => $pickupCoords['lat'] ?? null,
            'pickup_lng' => $pickupCoords['lng'] ?? null,
            'dropoff_lat' => $dropoffCoords['lat'] ?? null,
            'dropoff_lng' => $dropoffCoords['lng'] ?? null,
        ]);
        
        $order->logStatusChange(Order::STATUS_PENDING, Auth::id(), 'Order created by customer');
        
        try {
            broadcast(new NewOrderNotification($order))->toOthers();
        } catch (\Exception $e) {
            \Log::error('Broadcast failed: ' . $e->getMessage());
        }
        
        return redirect()->route('payment.show', $order)
            ->with('success', "Order created! Please complete your payment. Total: ₱{$deliveryFee}");
    }
    
    // Send to rider (Staff only)
    public function sendToRider(Request $request, Order $order)
    {
        $this->authorizeRole(['staff', 'admin']);
        
        $validated = $request->validate([
            'rider_id' => 'required|exists:riders,id',
        ]);
        
        if ($order->status !== Order::STATUS_PENDING) {
            return back()->with('error', 'Only pending orders can be sent.');
        }
        
        $order->sendToRider($validated['rider_id'], Auth::id());
        
        return back()->with('success', 'Order sent to rider for acceptance.');
    }
    
    // Rider accepts order
    public function acceptOrder(Order $order)
    {
        $this->authorizeRole(['rider']);
        
        $rider = Auth::user()->rider;
        
        if ($order->assigned_rider_id !== $rider->id) {
            abort(403);
        }
        
        if ($order->status !== Order::STATUS_SENT) {
            return back()->with('error', 'This order is no longer available.');
        }
        
        $order->acceptByRider($rider->id);
        
        return redirect()->route('rider.deliveries')->with('success', 'Order accepted! Waiting for staff approval.');
    }
    
    // Staff approves after rider accepts
    public function approveAfterAccept(Order $order)
    {
        $this->authorizeRole(['staff', 'admin']);
        
        if ($order->status !== Order::STATUS_ACCEPTED) {
            return back()->with('error', 'Only accepted orders can be approved.');
        }
        
        $order->approveByStaff(Auth::id());
        $order->assignRider($order->assigned_rider_id, Auth::id());
        
        try {
            broadcast(new OrderStatusUpdated($order, 'Your order has been confirmed! Rider will proceed with delivery.'))->toOthers();
        } catch (\Exception $e) {
            \Log::error('Broadcast failed: ' . $e->getMessage());
        }
        
        return back()->with('success', 'Order approved! Rider can now proceed.');
    }
    
    // Mark order as picked up (rider only)
    public function pickUp(Order $order)
    {
        $this->authorizeRole(['rider']);
        
        $rider = Auth::user()->rider;
        
        if ($order->assigned_rider_id !== $rider->id) {
            return back()->with('error', 'You are not assigned to this order.');
        }
        
        if ($order->status !== Order::STATUS_ASSIGNED) {
            return back()->with('error', 'Order must be assigned first.');
        }
        
        $order->markPickedUp($rider->id);
        
        try {
            broadcast(new OrderStatusUpdated($order, 'Your order has been picked up! On the way.'))->toOthers();
        } catch (\Exception $e) {
            \Log::error('Broadcast failed: ' . $e->getMessage());
        }
        
        return back()->with('success', 'Order marked as picked up!');
    }
    
    // Mark order as delivered with proper payment and points
    public function deliver(Order $order)
    {
        $this->authorizeRole(['rider']);
        
        $rider = Auth::user()->rider;
        
        if ($order->assigned_rider_id !== $rider->id) {
            return back()->with('error', 'You are not assigned to this order.');
        }
        
        if ($order->status !== Order::STATUS_PICKED_UP) {
            return back()->with('error', 'Order must be picked up first.');
        }
        
        $order->markDelivered($rider->id);
        
        // Update payment status if COD
        if ($order->payment) {
            $order->payment->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
        }
        
        // Add points based on delivery fee (1 point per ₱10 spent)
        $pointsEarned = floor($order->delivery_fee / 10);
        $customer = $order->customer;
        $customer->addPoints($pointsEarned, 'order_completed', $order->id, "Order #{$order->id} completed. Earned {$pointsEarned} points");
        
        // Update rider's total earnings
        $rider->total_earnings = ($rider->total_earnings ?? 0) + $order->rider_earnings;
        $rider->save();
        
        try {
            broadcast(new OrderStatusUpdated($order, "Your order has been delivered! You earned {$pointsEarned} points! 🎉"))->toOthers();
        } catch (\Exception $e) {
            \Log::error('Broadcast failed: ' . $e->getMessage());
        }
        
        return back()->with('success', "Order delivered successfully! Rider earned ₱{$order->rider_earnings}");
    }
    
    // Cancel order
    public function cancel(Order $order, Request $request)
    {
        $user = Auth::user();
        
        if ($user->role === 'customer' && $order->customer_id !== $user->id) {
            return back()->with('error', 'You can only cancel your own orders.');
        }
        
        if (!in_array($order->status, [Order::STATUS_PENDING, Order::STATUS_APPROVED])) {
            return back()->with('error', 'Only pending or approved orders can be cancelled.');
        }
        
        $reason = $request->input('reason', 'No reason provided');
        $order->cancel($user->id, $reason);
        
        if ($user->role !== 'customer') {
            try {
                broadcast(new OrderStatusUpdated($order, "Your order has been cancelled. Reason: {$reason}"))->toOthers();
            } catch (\Exception $e) {
                \Log::error('Broadcast failed: ' . $e->getMessage());
            }
        }
        
        return back()->with('success', 'Order cancelled successfully.');
    }
    
    // View order details
    public function show(Order $order)
    {
        $user = Auth::user();
        
        if ($user->role === 'customer' && $order->customer_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        
        $order->load(['customer', 'assignedRider.user', 'payment']);
        
        return view('orders.show', compact('order'));
    }
    
    // Helper method for role authorization
    private function authorizeRole($roles)
    {
        if (!in_array(Auth::user()->role, $roles)) {
            abort(403, 'Unauthorized action.');
        }
    }
    
    // Geocode address using cURL with User-Agent (avoids 403 error)
    private function geocodeAddress($address)
    {
        $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($address) . "&limit=1";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'FetchAndGoApp/1.0 (customer@fetchandgo.com)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200 || !$response) {
            \Log::warning('Geocoding failed for address: ' . $address . ' - HTTP Code: ' . $httpCode);
            return null;
        }
        
        $data = json_decode($response, true);
        
        if (!empty($data) && isset($data[0]['lat']) && isset($data[0]['lon'])) {
            return [
                'lat' => $data[0]['lat'],
                'lng' => $data[0]['lon']
            ];
        }
        
        return null;
    }
}