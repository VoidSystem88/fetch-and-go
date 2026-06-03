@extends('layouts.customer')

@section('content')
<div class="py-4">
    <div class="w-full px-4 max-w-3xl mx-auto">
        
        <div class="mb-4">
            <a href="{{ route('customer.orders') }}" class="text-sm" style="color: rgba(255,255,255,0.7);">
                ← Back to Orders
            </a>
        </div>

        <div class="glass-card">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h1 class="text-xl font-bold" style="color: white;">Order #{{ $order->id }}</h1>
                    <p class="text-xs" style="color: rgba(255,255,255,0.5);">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <span class="px-3 py-1 text-sm rounded-full status-{{ $order->status }}">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
            </div>

            <!-- Order Details -->
            <div class="space-y-4">
                <div>
                    <p class="text-xs" style="color: rgba(255,255,255,0.5);">Item Description</p>
                    <p class="text-sm" style="color: white;">{{ $order->item_description }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs" style="color: rgba(255,255,255,0.5);">Pickup Location</p>
                        <p class="text-sm" style="color: white;">{{ $order->pickup_location }}</p>
                    </div>
                    <div>
                        <p class="text-xs" style="color: rgba(255,255,255,0.5);">Dropoff Location</p>
                        <p class="text-sm" style="color: white;">{{ $order->dropoff_location }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs" style="color: rgba(255,255,255,0.5);">Estimated Weight</p>
                        <p class="text-sm" style="color: white;">{{ $order->estimated_weight_kg ?? 'N/A' }} kg</p>
                    </div>
                    <div>
                        <p class="text-xs" style="color: rgba(255,255,255,0.5);">Vehicle Required</p>
                        <p class="text-sm" style="color: white; text-transform: capitalize;">{{ $order->required_vehicle_type }}</p>
                    </div>
                </div>

                @if($order->delivery_instructions)
                    <div class="p-3 rounded-lg" style="background: rgba(245,158,11,0.2); border: 1px solid rgba(245,158,11,0.3);">
                        <p class="text-xs mb-1" style="color: rgba(255,255,255,0.7);">Delivery Instructions</p>
                        <p class="text-sm" style="color: #fef3c7;">{{ $order->delivery_instructions }}</p>
                    </div>
                @endif

                @if($order->assignedRider)
                    <div class="p-3 rounded-lg" style="background: rgba(59,130,246,0.2); border: 1px solid rgba(59,130,246,0.3);">
                        <p class="text-xs mb-1" style="color: rgba(255,255,255,0.7);">Rider Information</p>
                        <p class="text-sm" style="color: white;">{{ $order->assignedRider->user->name }}</p>
                        <p class="text-xs" style="color: rgba(255,255,255,0.5);">⭐ {{ $order->assignedRider->rating }}</p>
                    </div>
                @endif

                @if(in_array($order->status, ['pending', 'approved']))
                    <div class="pt-2">
                        <form method="POST" action="{{ route('orders.cancel', $order) }}" onsubmit="return confirm('Cancel this order?')">
                            @csrf
                            <input type="hidden" name="reason" value="Cancelled by customer">
                            <button type="submit" class="px-4 py-2 rounded-lg text-sm" style="background: rgba(239,68,68,0.8); color: white;">
                                Cancel Order
                            </button>
                        </form>
                    </div>
                @endif

                @if($order->status == 'delivered' && !$order->hasRating())
                    <div class="pt-2">
                        <a href="{{ route('ratings.create', $order) }}" class="px-4 py-2 rounded-lg text-sm inline-block" style="background: linear-gradient(135deg, #FF6B35, #1E88E5); color: white;">
                            Rate this Delivery
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
    </div>
</div>

<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        border: 1px solid rgba(255, 255, 255, 0.25);
        padding: 20px;
    }
    
    .status-pending { background: rgba(245,158,11,0.3); color: #fef3c7; }
    .status-approved { background: rgba(59,130,246,0.3); color: #dbeafe; }
    .status-assigned { background: rgba(139,92,246,0.3); color: #ede9fe; }
    .status-picked_up { background: rgba(6,182,212,0.3); color: #cffafe; }
    .status-delivered { background: rgba(16,185,129,0.3); color: #d1fae5; }
    .status-cancelled { background: rgba(239,68,68,0.3); color: #fee2e2; }
</style>
@endsection