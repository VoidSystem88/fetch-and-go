<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'pickup_location',
        'dropoff_location',
        'item_description',
        'estimated_weight_kg',
        'estimated_size_cm',
        'required_vehicle_type',
        'delivery_instructions',
        'status',
        'assigned_rider_id',
        'assigned_staff_id',
        'assigned_at',
        'timeout_expires_at',
        'approved_by',
        'approved_at',
        'picked_up_at',
        'delivered_at',
        'sent_to_rider_at',
        'rider_responded_at',
        'rider_decline_reason',
        'pickup_lat',
        'pickup_lng',
        'dropoff_lat',
        'dropoff_lng',
        'distance_km',          // bago
        'delivery_fee',         // bago
        'rider_earnings',       // bago
        'admin_earnings',       // bago
        'discount_code',        // bago
        'discount_amount',      // bago
        'payment_status',       // bago
        'estimated_size_cm',    // bago
    ];

    protected $casts = [
        'estimated_weight_kg' => 'decimal:2',
        'estimated_size_cm' => 'decimal:2',
        'assigned_at' => 'datetime',
        'timeout_expires_at' => 'datetime',
        'approved_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
        'sent_to_rider_at' => 'datetime',
        'rider_responded_at' => 'datetime',
    ];

    // Constants - New Workflow
    const STATUS_PENDING = 'pending';           // Customer created
    const STATUS_SENT = 'sent';                 // Sent to rider (waiting for response)
    const STATUS_ACCEPTED = 'accepted';         // Rider accepted
    const STATUS_DECLINED = 'declined';         // Rider declined
    const STATUS_APPROVED = 'approved';         // Staff approved after accept
    const STATUS_ASSIGNED = 'assigned';         // Final assigned
    const STATUS_PICKED_UP = 'picked_up';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    const VEHICLE_TYPES = ['motor', 'car', 'truck', 'van', 'L300'];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function assignedRider()
    {
        return $this->belongsTo(Rider::class, 'assigned_rider_id');
    }

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);

    }

    // Helper methods - New Workflow
    public function sendToRider($riderId, $staffId)
    {
        $timeout = now()->addMinutes(30); // 30 minutes to respond
        
        $this->update([
            'status' => self::STATUS_SENT,
            'assigned_rider_id' => $riderId,
            'assigned_staff_id' => $staffId,
            'sent_to_rider_at' => now(),
            'timeout_expires_at' => $timeout,
        ]);
        
        $this->logStatusChange(self::STATUS_SENT, $staffId, "Sent to rider #{$riderId}");
    }

    public function acceptByRider($riderId)
    {
        $this->update([
            'status' => self::STATUS_ACCEPTED,
            'rider_responded_at' => now(),
        ]);
        
        $this->logStatusChange(self::STATUS_ACCEPTED, $riderId, "Rider accepted the order");
    }

    public function declineByRider($riderId, $reason = null)
    {
        $this->update([
            'status' => self::STATUS_DECLINED,
            'rider_responded_at' => now(),
            'rider_decline_reason' => $reason,
        ]);
        
        $this->logStatusChange(self::STATUS_DECLINED, $riderId, "Rider declined: {$reason}");
    }

    public function approveByStaff($staffId)
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_by' => $staffId,
            'approved_at' => now(),
        ]);
        
        $this->logStatusChange(self::STATUS_APPROVED, $staffId);
    }

    public function assignRider($riderId, $staffId)
    {
        $this->update([
            'status' => self::STATUS_ASSIGNED,
            'assigned_at' => now(),
        ]);
        
        $this->logStatusChange(self::STATUS_ASSIGNED, $staffId, "Order confirmed to rider #{$riderId}");
    }

    public function markPickedUp($riderId)
    {
        $this->update([
            'status' => self::STATUS_PICKED_UP,
            'picked_up_at' => now(),
        ]);
        
        $this->logStatusChange(self::STATUS_PICKED_UP, $riderId);
    }

    public function markDelivered($riderId)
    {
        $this->update([
            'status' => self::STATUS_DELIVERED,
            'delivered_at' => now(),
        ]);
        
        $this->logStatusChange(self::STATUS_DELIVERED, $riderId);
        
        if ($this->assignedRider) {
            $this->assignedRider->incrementDeliveries();
        }
    }

    public function cancel($userId, $reason = null)
    {
        $this->update(['status' => self::STATUS_CANCELLED]);
        $this->logStatusChange(self::STATUS_CANCELLED, $userId, $reason);
    }

    public function isTimeout()
    {
        if (!$this->timeout_expires_at) {
            return false;
        }
        
        return now()->gt($this->timeout_expires_at);
    }

    public function logStatusChange($status, $userId, $notes = null)
    {
        // TEMPORARILY DISABLED - Status history logging
        return;
    }

    // Status check helpers
    public function canBeSentToRider()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isWaitingForRider()
    {
        return $this->status === self::STATUS_SENT && !$this->isTimeout();
    }

    public function canBeApproved()
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function canBeAssigned()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function canBePickedUp()
    {
        return $this->status === self::STATUS_ASSIGNED;
    }
}