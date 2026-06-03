<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'customer_id',
        'rider_id',
        'amount',
        'service_fee',
        'payment_method',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    const METHOD_CASH = 'cash';
    const METHOD_GCASH = 'gcash';
    const METHOD_PAYMAYA = 'paymaya';

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_REFUNDED = 'refunded';

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }

    // Helper methods
    public function markAsPaid()
    {
        $this->update([
            'status' => self::STATUS_PAID,
            'paid_at' => now(),
        ]);
    }

    public function refund()
    {
        $this->update(['status' => self::STATUS_REFUNDED]);
    }

    public function getTotalAmount()
    {
        return $this->amount + ($this->service_fee ?? 0);
    }
}