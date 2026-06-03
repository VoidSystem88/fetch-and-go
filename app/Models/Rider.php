<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rider extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'is_available',
        'current_lat',
        'current_lng',
        'rating',
        'total_deliveries',
        'total_earnings',

    ];

    protected $casts = [
        'is_available' => 'boolean',
        'current_lat' => 'decimal:8',
        'current_lng' => 'decimal:8',
        'rating' => 'decimal:1',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->hasOne(RiderVehicle::class);
    }

    public function assignedOrders()
    {
        return $this->hasMany(Order::class, 'assigned_rider_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Helper methods
    public function updateLocation($lat, $lng)
    {
        $this->update([
            'current_lat' => $lat,
            'current_lng' => $lng,
        ]);
    }

    public function toggleAvailability()
    {
        $this->update(['is_available' => !$this->is_available]);
    }

    public function incrementDeliveries()
    {
        $this->increment('total_deliveries');
    }

    public function updateRating($newRating)
    {
        // Calculate average rating
        $currentTotal = $this->rating * $this->total_deliveries;
        $newTotal = $currentTotal + $newRating;
        $newAverage = $newTotal / ($this->total_deliveries + 1);
        
        $this->update([
            'rating' => round($newAverage, 1),
        ]);
    }

    public function isOnline()
    {
        return $this->is_available;
    }
    public function ratings()
{
    return $this->hasMany(Rating::class);
}

public function getAverageRatingAttribute()
{
    return $this->ratings()->avg('rating') ?? 0;
}

public function updateRatingFromReview()
{
    $avg = $this->ratings()->avg('rating');
    $this->update(['rating' => round($avg, 1)]);
}
}