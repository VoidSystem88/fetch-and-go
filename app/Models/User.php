<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'is_verified',
        'birthday',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_verified' => 'boolean',
        'birthday' => 'date',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    // Relationships
    public function rider()
    {
        return $this->hasOne(Rider::class);
    }

    public function customerOrders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function approvedOrders()
    {
        return $this->hasMany(Order::class, 'approved_by');
    }

    public function assignedStaffOrders()
    {
        return $this->hasMany(Order::class, 'assigned_staff_id');
    }

    public function staffAreas()
    {
        return $this->hasMany(StaffArea::class, 'staff_id');
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class, 'changed_by');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public function verifiedVehicles()
    {
        return $this->hasMany(RiderVehicle::class, 'verified_by');
    }

    public function pointsHistory()
    {
        return $this->hasMany(PointsHistory::class);
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function isRider()
    {
        return $this->role === 'rider';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function hasArea($areaId)
    {
        if ($this->isAdmin()) {
            return true;
        }
        
        return $this->staffAreas()->where('area_id', $areaId)->exists();
    }

    public function addPoints($points, $reason, $orderId = null, $notes = null)
    {
        $this->points += $points;
        $this->total_points_earned += $points;
        $this->save();
        
        PointsHistory::create([
            'user_id' => $this->id,
            'order_id' => $orderId,
            'points' => $points,
            'type' => 'earned',
            'reason' => $reason,
            'notes' => $notes,
        ]);
    }

    public function spendPoints($points, $reason, $notes = null)
    {
        if ($this->points < $points) {
            return false;
        }
        
        $this->points -= $points;
        $this->points_spent += $points;
        $this->save();
        
        PointsHistory::create([
            'user_id' => $this->id,
            'points' => $points,
            'type' => 'spent',
            'reason' => $reason,
            'notes' => $notes,
        ]);
        
        return true;
    }

    // Birthday Methods
    public function isBirthdayToday()
    {
        if (!$this->birthday) {
            return false;
        }
        return $this->birthday->format('m-d') === now()->format('m-d');
    }

    public function hasBirthdayDiscountThisYear()
    {
        return PointsHistory::where('user_id', $this->id)
            ->where('reason', 'birthday_discount')
            ->whereYear('created_at', now()->year)
            ->exists();
    }

    public function claimBirthdayDiscount()
    {
        if (!$this->isBirthdayToday()) {
            return ['success' => false, 'message' => 'Today is not your birthday!'];
        }
        
        if ($this->hasBirthdayDiscountThisYear()) {
            return ['success' => false, 'message' => 'You have already claimed your birthday discount this year.'];
        }
        
        $code = 'BDAY_' . $this->id . '_' . now()->format('Y');
        
        Discount::create([
            'user_id' => $this->id,
            'code' => $code,
            'points_used' => 0,
            'discount_amount' => 50,
            'min_spend' => 200,
            'type' => 'fixed',
            'status' => 'active',
            'expires_at' => now()->addDays(7),
        ]);
        
        PointsHistory::create([
            'user_id' => $this->id,
            'points' => 20,
            'type' => 'earned',
            'reason' => 'birthday_bonus',
            'notes' => 'Happy Birthday! You received 20 bonus points!',
        ]);
        
        $this->points += 20;
        $this->total_points_earned += 20;
        $this->save();
        
        return [
            'success' => true,
            'message' => 'Happy Birthday! You received ₱50 discount and 20 bonus points!',
            'discount_code' => $code
        ];
    }
}