<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewOrderNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function broadcastOn()
    {
        return [new Channel('staff.orders')];
    }

    public function broadcastAs()
    {
        return 'new.order';
    }

    public function broadcastWith()
    {
        return [
            'order_id' => $this->order->id,
            'customer' => $this->order->customer->name,
            'pickup' => $this->order->pickup_location,
            'dropoff' => $this->order->dropoff_location,
        ];
    }
}