<?php

namespace App\Events;

use App\Models\Rider;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RiderLocationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $rider;
    public $lat;
    public $lng;

    public function __construct(Rider $rider, $lat, $lng)
    {
        $this->rider = $rider;
        $this->lat = $lat;
        $this->lng = $lng;
    }

    public function broadcastOn()
    {
        return [new Channel('rider.' . $this->rider->id)];
    }

    public function broadcastAs()
    {
        return 'rider.location.updated';
    }
}