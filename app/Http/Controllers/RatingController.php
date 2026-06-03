<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Rating;
use App\Models\Rider;

class RatingController extends Controller
{
    public function create(Order $order)
    {
        $user = auth()->user();
        
        // Only customer who owns the order can rate
        if ($user->role !== 'customer' || $order->customer_id !== $user->id) {
            abort(403);
        }
        
        // Only delivered orders can be rated
        if ($order->status !== Order::STATUS_DELIVERED) {
            return back()->with('error', 'You can only rate delivered orders.');
        }
        
        // Check if already rated
        if ($order->hasRating()) {
            return back()->with('error', 'You have already rated this order.');
        }
        
        return view('ratings.create', compact('order'));
    }
    
    public function store(Request $request, Order $order)
    {
        $user = auth()->user();
        
        if ($user->role !== 'customer' || $order->customer_id !== $user->id) {
            abort(403);
        }
        
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500',
        ]);
        
        $rating = Rating::create([
            'order_id' => $order->id,
            'customer_id' => $user->id,
            'rider_id' => $order->assigned_rider_id,
            'rating' => $validated['rating'],
            'review' => $validated['review'] ?? null,
        ]);
        
        // Update rider's average rating
        if ($order->assignedRider) {
            $order->assignedRider->updateRatingFromReview();
        }
        
        return redirect()->route('dashboard')
            ->with('success', 'Thank you for your rating!');
    }
    
    public function show(Rider $rider)
    {
        $ratings = $rider->ratings()
            ->with('customer')
            ->latest()
            ->paginate(10);
            
        $averageRating = $rider->average_rating;
        
        return view('ratings.show', compact('rider', 'ratings', 'averageRating'));
    }
}