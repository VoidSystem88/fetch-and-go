@extends('layouts.customer')

@section('content')
<div class="py-4 pb-20">
    <div class="w-full px-4 max-w-4xl mx-auto">
        
        <!-- Header with User Info -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold" style="color: white;">Good {{ \Carbon\Carbon::now()->format('g:i A') }},</h1>
            <p class="text-lg font-medium" style="color: rgba(255,255,255,0.7);">{{ auth()->user()->name }}</p>
            <p class="text-xs mt-1" style="color: rgba(255,255,255,0.4);">Member since {{ auth()->user()->created_at->format('M d, Y') }}</p>
        </div>

        <!-- Stats Cards -->
        <div class="flex gap-3 mb-4 overflow-x-auto" style="scrollbar-width: none;">
            <div class="glass-card p-3 text-center flex-1 min-w-[100px]">
                <div class="text-xl font-bold" style="color: #38bd55;">{{ $activeOrders->count() }}</div>
                <p class="text-xs" style="color: rgba(255,255,255,0.5);">Active Orders</p>
            </div>
            <div class="glass-card p-3 text-center flex-1 min-w-[100px]">
                <div class="text-xl font-bold" style="color: #38bd55;">{{ $completedOrders }}</div>
                <p class="text-xs" style="color: rgba(255,255,255,0.5);">Completed</p>
            </div>
            <div class="glass-card p-3 text-center flex-1 min-w-[100px]">
                <div class="text-xl font-bold" style="color: #e07c34;">{{ $pendingPayments->count() }}</div>
                <p class="text-xs" style="color: rgba(255,255,255,0.5);">Pending Pay</p>
            </div>
        </div>

        <!-- Quick Access Row -->
        <div class="flex gap-2 mb-6 overflow-x-auto" style="scrollbar-width: none;">
            <div class="glass-card p-2 text-center flex-1 min-w-[80px]">
                <i class="fas fa-tag text-sm" style="color: #38bd55;"></i>
                <p class="text-xs font-semibold mt-1" style="color: white;">Price</p>
                <p class="text-xs" style="color: rgba(255,255,255,0.4);">From ₱50</p>
            </div>
            <div class="glass-card p-2 text-center flex-1 min-w-[80px]">
                <i class="fas fa-coins text-sm" style="color: #38bd55;"></i>
                <p class="text-xs font-semibold mt-1" style="color: white;">Points</p>
                <p class="text-xs" style="color: rgba(255,255,255,0.4);">Earn rewards</p>
            </div>
            <div class="glass-card p-2 text-center flex-1 min-w-[80px]">
                <i class="fas fa-newspaper text-sm" style="color: #38bd55;"></i>
                <p class="text-xs font-semibold mt-1" style="color: white;">News</p>
                <p class="text-xs" style="color: rgba(255,255,255,0.4);">Updates</p>
            </div>
            <div class="glass-card p-2 text-center flex-1 min-w-[80px]">
                <i class="fas fa-info-circle text-sm" style="color: #38bd55;"></i>
                <p class="text-xs font-semibold mt-1" style="color: white;">Info</p>
                <p class="text-xs" style="color: rgba(255,255,255,0.4);">Help center</p>
            </div>
        </div>

        <!-- New Order Button -->
        <a href="{{ route('orders.create') }}" class="glass-card flex items-center justify-between mb-6" style="background: linear-gradient(135deg, #38bd55, #2a9e46); border: none;">
            <div>
                <p class="text-sm font-semibold" style="color: white;">New Delivery</p>
                <p class="text-xs" style="color: rgba(255,255,255,0.8);">Create a new order</p>
            </div>
            <i class="fas fa-arrow-right text-white text-xl"></i>
        </a>

        <!-- Special Discount Banner -->
        <a href="{{ route('customer.points.index') }}" class="glass-card mb-6 flex justify-between items-center" style="background: linear-gradient(135deg, rgba(56,189,85,0.1), rgba(224,124,52,0.1)); text-decoration: none; cursor: pointer;">
            <div>
                <p class="text-sm font-semibold" style="color: white;">Special Discount</p>
                <p class="text-xs" style="color: #38bd55;">Redeem your points!</p>
            </div>
            <div class="px-3 py-1 rounded-full text-xs" style="background: #38bd55; color: white;">
                Redeem Points →
            </div>
        </a>

        @if(session('success'))
            <div class="glass-card mb-4" style="background: rgba(56,189,85,0.1); border-color: rgba(56,189,85,0.3);">
                <i class="fas fa-check-circle" style="color: #38bd55;"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Track Your Package -->
        @if($activeOrders->count() > 0)
            <div class="mb-6">
                <div class="flex justify-between items-center mb-3">
                    <h2 class="text-base font-semibold flex items-center gap-2" style="color: white;">
                        <i class="fas fa-map-marker-alt" style="color: #38bd55;"></i> Track Your Package
                    </h2>
                    <span class="text-xs" style="color: rgba(255,255,255,0.4);">{{ $activeOrders->count() }} active</span>
                </div>
                <div class="space-y-3">
                    @foreach($activeOrders as $order)
                        <div class="glass-card p-3">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="text-xs font-mono" style="color: #38bd55;">#{{ $order->id }}</p>
                                    <p class="text-xs" style="color: rgba(255,255,255,0.4);">{{ $order->created_at->format('M d, Y') }}</p>
                                </div>
                                <span class="px-2 py-0.5 text-xs rounded-full status-{{ $order->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </div>
                            <p class="text-sm mb-2" style="color: rgba(255,255,255,0.7);">{{ Str::limit($order->item_description, 50) }}</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 text-xs">
                                    <i class="fas fa-box" style="color: #38bd55;"></i>
                                    <span style="color: rgba(255,255,255,0.5);">{{ Str::limit($order->pickup_location, 20) }}</span>
                                    <i class="fas fa-arrow-right" style="color: rgba(255,255,255,0.3);"></i>
                                    <span style="color: rgba(255,255,255,0.5);">{{ Str::limit($order->dropoff_location, 20) }}</span>
                                </div>
                                <a href="{{ route('customer.order.details', $order) }}" class="text-xs" style="color: #38bd55;">Track →</a>
                            </div>
                            <div class="mt-2 pt-2 border-t" style="border-color: rgba(255,255,255,0.05);">
                                <div class="flex justify-between text-xs mb-1">
                                    <span style="color: rgba(255,255,255,0.4);">Created</span>
                                    <span style="color: rgba(255,255,255,0.4);">On The Way</span>
                                    <span style="color: rgba(255,255,255,0.4);">Delivered</span>
                                </div>
                                <div class="h-1 rounded-full bg-gray-700 overflow-hidden">
                                    @php
                                        $progress = 0;
                                        if ($order->status == 'delivered') {
                                            $progress = 100;
                                        } elseif (in_array($order->status, ['assigned', 'approved', 'picked_up'])) {
                                            $progress = 50;
                                        } elseif ($order->status == 'pending') {
                                            $progress = 25;
                                        }
                                    @endphp
                                    <div class="h-full rounded-full" style="width: {{ $progress }}%; background: #38bd55;"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Recent Shipping -->
        <div>
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-base font-semibold flex items-center gap-2" style="color: white;">
                    <i class="fas fa-history" style="color: #38bd55;"></i> Recent Shipping
                </h2>
                <a href="{{ route('customer.orders') }}" class="text-xs" style="color: #38bd55;">See all →</a>
            </div>
            <div class="space-y-2">
                @forelse($recentOrders->take(3) as $order)
                    <div class="glass-card p-3">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: rgba(56,189,85,0.15);">
                                    <i class="fas fa-box text-xs" style="color: #38bd55;"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-mono" style="color: white;">ID NUMBER</p>
                                    <p class="text-xs font-mono" style="color: #38bd55;">#{{ $order->id }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs px-2 py-0.5 rounded-full status-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                                <p class="text-xs mt-1" style="color: rgba(255,255,255,0.4);">{{ $order->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="glass-card p-4 text-center">
                        <p class="text-sm" style="color: rgba(255,255,255,0.5);">No orders yet</p>
                        <a href="{{ route('orders.create') }}" class="text-xs" style="color: #38bd55;">Create your first order →</a>
                    </div>
                @endforelse
            </div>
        </div>
        
    </div>
</div>

<style>
    .glass-card {
        background: #1e1e1e;
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,0.05);
        padding: 12px;
        transition: all 0.2s;
    }
    
    .glass-card:hover {
        border-color: rgba(56,189,85,0.3);
    }
    
    .overflow-x-auto::-webkit-scrollbar {
        display: none;
    }
    
    .status-pending { background: rgba(224,124,52,0.15); color: #e07c34; }
    .status-approved { background: rgba(56,189,85,0.15); color: #38bd55; }
    .status-assigned { background: rgba(56,189,85,0.15); color: #38bd55; }
    .status-picked_up { background: rgba(56,189,85,0.15); color: #38bd55; }
    .status-delivered { background: rgba(56,189,85,0.2); color: #38bd55; }
    .status-cancelled { background: rgba(239,68,68,0.15); color: #f87171; }
</style>

<!-- Welcome Modal - Nasa labas ng style tag -->
@if(isset($showWelcomeModal) && $showWelcomeModal)
    @include('components.welcome-modal')
@endif

@endsection