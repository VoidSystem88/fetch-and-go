<?php

namespace App\Services;

use App\Models\Order;

class PricingService
{
    // Base rates
    const BASE_PRICE = 50;
    const DISTANCE_RATE = 15; // per km
    const WEIGHT_RATE = 10; // per kg
    const SIZE_RATE = 0.5; // per cm
    
    // Vehicle multipliers
    const VEHICLE_FEES = [
        'motor' => 0,
        'car' => 50,
        'van' => 75,
        'L300' => 80,
        'truck' => 100,
    ];
    
    // Earnings split
    const RIDER_PERCENTAGE = 70; // 70% to rider
    const ADMIN_PERCENTAGE = 30; // 30% to admin
    
    public function calculateDeliveryFee($distanceKm, $weightKg, $sizeCm, $vehicleType)
    {
        $distanceFee = $distanceKm * self::DISTANCE_RATE;
        $weightFee = $weightKg * self::WEIGHT_RATE;
        $sizeFee = $sizeCm * self::SIZE_RATE;
        $vehicleFee = self::VEHICLE_FEES[$vehicleType] ?? 0;
        
        $total = self::BASE_PRICE + $distanceFee + $weightFee + $sizeFee + $vehicleFee;
        
        return round($total, 2);
    }
    
    public function calculateEarnings($deliveryFee)
    {
        $riderEarnings = ($deliveryFee * self::RIDER_PERCENTAGE) / 100;
        $adminEarnings = ($deliveryFee * self::ADMIN_PERCENTAGE) / 100;
        
        return [
            'rider' => round($riderEarnings, 2),
            'admin' => round($adminEarnings, 2),
            'delivery_fee' => $deliveryFee,
        ];
    }
    
    public function estimateDistance($pickup, $dropoff)
    {
        // For now, return a random distance between 1-20 km
        // In production, use Google Maps Distance Matrix API
        return rand(1, 20);
    }
}