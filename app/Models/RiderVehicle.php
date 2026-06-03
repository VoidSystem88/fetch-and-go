<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiderVehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'rider_id',
        'vehicle_type',
        'plate_number',
        'max_weight_kg',
        'max_size_cm',
        'is_active',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'verified_at' => 'datetime',
        'max_weight_kg' => 'decimal:2',
        'max_size_cm' => 'decimal:2',
    ];

    // Relationships
    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Helper methods
    public function verify($staffId)
    {
        $this->update([
            'is_active' => true,
            'verified_by' => $staffId,
            'verified_at' => now(),
        ]);
    }

    public function canCarryWeight($weightKg)
    {
        if (!$this->max_weight_kg) {
            return true; // No weight limit set
        }
        
        return $weightKg <= $this->max_weight_kg;
    }

    public function canCarrySize($sizeCm)
    {
        if (!$this->max_size_cm) {
            return true; // No size limit set
        }
        
        return $sizeCm <= $this->max_size_cm;
    }
}