@extends('layouts.customer')

@section('content')
<div class="py-4">
    <div class="w-full px-4 max-w-2xl mx-auto">
        
        <div class="mb-4">
            <a href="{{ route('customer.orders') }}" class="text-sm" style="color: rgba(255,255,255,0.7);">
                ← Back to Orders
            </a>
        </div>

        <!-- Tracking Timeline -->
        <div class="glass-card mb-5">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-base font-semibold" style="color: white;">Track Your Package</h2>
                <span class="text-xs" style="color: rgba(255,255,255,0.5);">Order #{{ $order->id }}</span>
            </div>
            
            <!-- Timeline -->
            <div class="relative">
                <div class="flex items-center justify-between">
                    <!-- Created -->
                    <div class="text-center flex-1">
                        <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center mb-2 
                            {{ $order->created_at ? 'bg-green-500' : 'bg-gray-500' }}" 
                            style="background: {{ $order->created_at ? '#10B981' : '#6B7280' }};">
                            <i class="fas fa-box text-white text-sm"></i>
                        </div>
                        <p class="text-xs font-medium" style="color: white;">Created</p>
                        <p class="text-xs" style="color: rgba(255,255,255,0.5);">{{ $order->created_at->format('M d') }}</p>
                    </div>
                    
                    <!-- Line -->
                    <div class="flex-1 h-0.5 mx-2 rounded" 
                        style="background: {{ in_array($order->status, ['approved','assigned','picked_up','delivered']) ? '#10B981' : '#6B7280' }};"></div>
                    
                    <!-- Approved -->
                    <div class="text-center flex-1">
                        <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center mb-2 
                            {{ in_array($order->status, ['approved','assigned','picked_up','delivered']) ? 'bg-green-500' : 'bg-gray-500' }}" 
                            style="background: {{ in_array($order->status, ['approved','assigned','picked_up','delivered']) ? '#10B981' : '#6B7280' }};">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <p class="text-xs font-medium" style="color: white;">Approved</p>
                        <p class="text-xs" style="color: rgba(255,255,255,0.5);">{{ $order->approved_at ? $order->approved_at->format('M d') : 'Pending' }}</p>
                    </div>
                    
                    <!-- Line -->
                    <div class="flex-1 h-0.5 mx-2 rounded" 
                        style="background: {{ in_array($order->status, ['assigned','picked_up','delivered']) ? '#10B981' : '#6B7280' }};"></div>
                    
                    <!-- Assigned -->
                    <div class="text-center flex-1">
                        <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center mb-2 
                            {{ in_array($order->status, ['assigned','picked_up','delivered']) ? 'bg-green-500' : 'bg-gray-500' }}" 
                            style="background: {{ in_array($order->status, ['assigned','picked_up','delivered']) ? '#10B981' : '#6B7280' }};">
                            <i class="fas fa-motorcycle text-white text-sm"></i>
                        </div>
                        <p class="text-xs font-medium" style="color: white;">Rider Assigned</p>
                        <p class="text-xs" style="color: rgba(255,255,255,0.5);">{{ $order->assigned_at ? $order->assigned_at->format('M d') : 'Waiting' }}</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between mt-4">
                    <!-- Picked Up -->
                    <div class="text-center flex-1">
                        <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center mb-2 
                            {{ in_array($order->status, ['picked_up','delivered']) ? 'bg-green-500' : 'bg-gray-500' }}" 
                            style="background: {{ in_array($order->status, ['picked_up','delivered']) ? '#10B981' : '#6B7280' }};">
                            <i class="fas fa-box-open text-white text-sm"></i>
                        </div>
                        <p class="text-xs font-medium" style="color: white;">Picked Up</p>
                        <p class="text-xs" style="color: rgba(255,255,255,0.5);">{{ $order->picked_up_at ? $order->picked_up_at->format('M d') : 'Pending' }}</p>
                    </div>
                    
                    <!-- Line -->
                    <div class="flex-1 h-0.5 mx-2 rounded" 
                        style="background: {{ $order->status == 'delivered' ? '#10B981' : '#6B7280' }};"></div>
                    
                    <!-- Delivered -->
                    <div class="text-center flex-1">
                        <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center mb-2 
                            {{ $order->status == 'delivered' ? 'bg-green-500' : 'bg-gray-500' }}" 
                            style="background: {{ $order->status == 'delivered' ? '#10B981' : '#6B7280' }};">
                            <i class="fas fa-flag-checkered text-white text-sm"></i>
                        </div>
                        <p class="text-xs font-medium" style="color: white;">Delivered</p>
                        <p class="text-xs" style="color: rgba(255,255,255,0.5);">{{ $order->delivered_at ? $order->delivered_at->format('M d') : 'Pending' }}</p>
                    </div>
                    
                    <div class="flex-1"></div>
                </div>
            </div>
        </div>

        <!-- Order Details Card -->
        <div class="glass-card mb-4">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-sm font-semibold" style="color: white;">Order Details</h3>
                    <p class="text-xs" style="color: rgba(255,255,255,0.5);">Created {{ $order->created_at->format('M d, Y') }}</p>
                </div>
                <span class="px-3 py-1 text-xs rounded-full status-{{ $order->status }}">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
            </div>

            <div class="space-y-3">
                <div>
                    <p class="text-xs" style="color: rgba(255,255,255,0.5);">Item Description</p>
                    <p class="text-sm" style="color: white;">{{ $order->item_description }}</p>
                </div>

                <div>
                    <p class="text-xs" style="color: rgba(255,255,255,0.5);">Pickup Location</p>
                    <p class="text-sm" style="color: white;">{{ $order->pickup_location }}</p>
                </div>

                <div>
                    <p class="text-xs" style="color: rgba(255,255,255,0.5);">Dropoff Location</p>
                    <p class="text-sm" style="color: white;">{{ $order->dropoff_location }}</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-xs" style="color: rgba(255,255,255,0.5);">Weight</p>
                        <p class="text-sm" style="color: white;">{{ $order->estimated_weight_kg ?? 'N/A' }} kg</p>
                    </div>
                    <div>
                        <p class="text-xs" style="color: rgba(255,255,255,0.5);">Vehicle</p>
                        <p class="text-sm" style="color: white; text-transform: capitalize;">{{ $order->required_vehicle_type }}</p>
                    </div>
                </div>

                @if($order->delivery_instructions)
                    <div class="p-2 rounded-lg" style="background: rgba(245,158,11,0.15);">
                        <p class="text-xs" style="color: #fef3c7;">📝 {{ $order->delivery_instructions }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Rider Info Card with Tracking Button -->
        @if($order->assignedRider)
        <div class="glass-card mb-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-r from-orange-500 to-blue-500 flex items-center justify-center text-white font-bold">
                        {{ substr($order->assignedRider->user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium" style="color: white;">{{ $order->assignedRider->user->name }}</p>
                        <p class="text-xs" style="color: rgba(255,255,255,0.5);">⭐ {{ $order->assignedRider->rating }} • {{ $order->assignedRider->total_deliveries }} deliveries</p>
                    </div>
                </div>
                @if(in_array($order->status, ['assigned', 'picked_up']))
                    <a href="{{ route('tracking.show', $order) }}" class="px-3 py-2 rounded-lg text-sm font-semibold" style="background: #38bd55; color: white;">
                        <i class="fas fa-map-marker-alt mr-1"></i> Track Live
                    </a>
                @endif
            </div>
        </div>
        @endif

        <!-- Actions -->
        @if(in_array($order->status, ['pending', 'approved']))
            <div class="glass-card">
                <form method="POST" action="{{ route('orders.cancel', $order) }}" onsubmit="return confirm('Cancel this order?')">
                    @csrf
                    <input type="hidden" name="reason" value="Cancelled by customer">
                    <button type="submit" class="w-full btn-glass" style="background: rgba(239,68,68,0.8);">
                        Cancel Order
                    </button>
                </form>
            </div>
        @endif

        @if($order->status == 'delivered' && !$order->hasRating())
            <div class="glass-card">
                <a href="{{ route('ratings.create', $order) }}" class="w-full btn-glass text-center block">
                    Rate this Delivery
                </a>
            </div>
        @endif
        
    </div>
</div>

<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(15px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 20px;
    }
    
    .btn-glass {
        background: linear-gradient(135deg, #FF6B35, #1E88E5);
        border: none;
        border-radius: 12px;
        padding: 12px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }
    
    .btn-glass:hover {
        transform: translateY(-2px);
        opacity: 0.9;
    }
    
    .status-pending { background: rgba(245,158,11,0.3); color: #fef3c7; }
    .status-approved { background: rgba(59,130,246,0.3); color: #dbeafe; }
    .status-assigned { background: rgba(139,92,246,0.3); color: #ede9fe; }
    .status-picked_up { background: rgba(6,182,212,0.3); color: #cffafe; }
    .status-delivered { background: rgba(16,185,129,0.3); color: #d1fae5; }
    .status-cancelled { background: rgba(239,68,68,0.3); color: #fee2e2; }
</style>
@endsection