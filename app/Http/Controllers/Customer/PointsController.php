<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\PointsHistory;
use App\Models\Discount;
use Illuminate\Http\Request;

class PointsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $pointsHistory = PointsHistory::where('user_id', $user->id)
            ->latest()
            ->paginate(20);
        
        // Get active discounts
        $activeDiscounts = Discount::where('user_id', $user->id)
            ->where('status', 'active')
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->get();
        
        return view('customer.points', compact('user', 'pointsHistory', 'activeDiscounts'));
    }
    
    public function redeem(Request $request)
    {
        try {
            $user = auth()->user();
            $points = (int)$request->points;
            
            $rewards = [
                100 => ['discount' => 20, 'min_spend' => 100, 'code_prefix' => 'SAVE20'],
                250 => ['discount' => 50, 'min_spend' => 250, 'code_prefix' => 'SAVE50'],
                500 => ['discount' => 100, 'min_spend' => 500, 'code_prefix' => 'SAVE100'],
                1000 => ['discount' => 250, 'min_spend' => 1000, 'code_prefix' => 'SAVE250'],
            ];
            
            if (!isset($rewards[$points])) {
                return response()->json([
                    'success' => false, 
                    'error' => 'Invalid reward selection.'
                ], 400);
            }
            
            if ($user->points < $points) {
                return response()->json([
                    'success' => false, 
                    'error' => "You don't have enough points. You have {$user->points} points."
                ], 400);
            }
            
            $reward = $rewards[$points];
            $code = $reward['code_prefix'] . '_' . strtoupper(substr(uniqid(), -6)) . '_' . $user->id;
            
            $discount = Discount::create([
                'user_id' => $user->id,
                'code' => $code,
                'points_used' => $points,
                'discount_amount' => $reward['discount'],
                'min_spend' => $reward['min_spend'],
                'type' => 'fixed',
                'status' => 'active',
                'expires_at' => now()->addDays(30),
            ]);
            
            $user->spendPoints($points, 'discount_redeemed', "Redeemed {$points} points for ₱{$reward['discount']} discount");
            
            return response()->json([
                'success' => true,
                'code' => $code,
                'discount_amount' => $reward['discount'],
                'min_spend' => $reward['min_spend'],
                'expires_at' => $discount->expires_at,
                'points_left' => $user->fresh()->points
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Redeem error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function myDiscounts()
    {
        $user = auth()->user();
        $discounts = Discount::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('customer.my-discounts', compact('discounts'));
    }
    
    public function getAvailableDiscounts()
    {
        $user = auth()->user();
        $discounts = Discount::where('user_id', $user->id)
            ->where('status', 'active')
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->get();
        
        return response()->json(['discounts' => $discounts]);
    }
    
    public function applyDiscount(Request $request)
    {
        $code = $request->code;
        $orderTotal = $request->total ?? 0;
        
        $discount = Discount::where('code', $code)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->first();
        
        if (!$discount) {
            return response()->json(['error' => 'Invalid or expired discount code.'], 400);
        }
        
        if ($discount->user_id !== auth()->id()) {
            return response()->json(['error' => 'This discount code is not yours.'], 400);
        }
        
        if ($orderTotal < $discount->min_spend) {
            return response()->json([
                'error' => "Minimum spend of ₱{$discount->min_spend} required. Your total is ₱{$orderTotal}."
            ], 400);
        }
        
        return response()->json([
            'success' => true,
            'discount_amount' => $discount->discount_amount,
            'code' => $discount->code,
            'message' => "₱{$discount->discount_amount} discount applied!"
        ]);
    }
    public function claimWelcomePoints(Request $request)
{
    $user = auth()->user();
    
    if ($user->welcome_points_claimed) {
        return response()->json(['success' => false, 'message' => 'Points already claimed'], 400);
    }
    
    // Add 500 welcome points
    $user->addPoints(500, 'welcome_bonus', null, 'Welcome bonus points for new user');
    $user->welcome_points_claimed = true;
    $user->save();
    
    return response()->json([
        'success' => true,
        'points' => 500,
        'message' => 'You received 500 welcome points!'
    ]);
}
}