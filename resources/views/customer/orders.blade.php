@extends('layouts.customer')

@section('content')
<div class="py-4">
    <div class="w-full px-4">
        
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold" style="color: white;">My Orders</h1>
            <p class="text-sm" style="color: rgba(255,255,255,0.5);">View all your delivery orders</p>
        </div>

        @if($orders->count() > 0)
            <!-- Mobile View - Card Style -->
            <div class="block md:hidden space-y-3">
                @foreach($orders as $order)
                    <div class="glass-card p-3">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-sm font-mono" style="color: #38bd55;">#{{ $order->id }}</span>
                            <span class="px-2 py-0.5 text-xs rounded-full status-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="mb-2">
                            <p class="text-xs" style="color: rgba(255,255,255,0.4);">Pickup</p>
                            <p class="text-sm" style="color: rgba(255,255,255,0.8);">{{ Str::limit($order->pickup_location, 40) }}</p>
                        </div>
                        <div class="mb-2">
                            <p class="text-xs" style="color: rgba(255,255,255,0.4);">Dropoff</p>
                            <p class="text-sm" style="color: rgba(255,255,255,0.8);">{{ Str::limit($order->dropoff_location, 40) }}</p>
                        </div>
                        <div class="flex justify-between items-center mt-2 pt-2 border-t" style="border-color: rgba(255,255,255,0.05);">
                            <span class="text-xs" style="color: rgba(255,255,255,0.4);">{{ $order->created_at->format('M d, Y') }}</span>
                            <a href="{{ route('customer.order.details', $order) }}" class="text-sm" style="color: #38bd55;">View →</a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Desktop View - Table Style -->
            <div class="hidden md:block glass-table">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase" style="color: rgba(255,255,255,0.6);">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase" style="color: rgba(255,255,255,0.6);">Pickup</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase" style="color: rgba(255,255,255,0.6);">Dropoff</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase" style="color: rgba(255,255,255,0.6);">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase" style="color: rgba(255,255,255,0.6);">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase" style="color: rgba(255,255,255,0.6);">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr class="hover-row">
                                    <td class="px-4 py-3 text-sm font-mono" style="color: #38bd55;">#{{ $order->id }}</td>
                                    <td class="px-4 py-3 text-sm" style="color: rgba(255,255,255,0.7);">{{ Str::limit($order->pickup_location, 30) }}</td>
                                    <td class="px-4 py-3 text-sm" style="color: rgba(255,255,255,0.7);">{{ Str::limit($order->dropoff_location, 30) }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 text-xs rounded-full status-{{ $order->status }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm" style="color: rgba(255,255,255,0.5);">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('customer.order.details', $order) }}" class="text-sm" style="color: #38bd55;">View →</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 border-t" style="border-color: rgba(255,255,255,0.05);">
                    {{ $orders->links() }}
                </div>
            </div>
        @else
            <div class="glass-card text-center py-8">
                <i class="fas fa-inbox text-4xl mb-2" style="color: rgba(255,255,255,0.3);"></i>
                <p class="text-sm" style="color: rgba(255,255,255,0.5);">No orders yet</p>
                <a href="{{ route('orders.create') }}" class="text-sm mt-2 inline-block" style="color: #38bd55;">Create your first order →</a>
            </div>
        @endif
        
    </div>
</div>

<style>
    .glass-card {
        background: #1e1e1e;
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,0.05);
        padding: 16px;
        transition: all 0.2s;
    }
    
    .glass-card:hover {
        border-color: rgba(56,189,85,0.3);
    }
    
    .glass-table {
        background: #1e1e1e;
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,0.05);
        overflow: hidden;
    }
    
    .glass-table th {
        background: rgba(0, 0, 0, 0.2);
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    
    .glass-table td {
        border-bottom: 1px solid rgba(255,255,255,0.03);
    }
    
    .hover-row:hover td {
        background: rgba(56,189,85,0.05);
    }
    
    .status-pending { background: rgba(224,124,52,0.15); color: #e07c34; }
    .status-approved { background: rgba(56,189,85,0.15); color: #38bd55; }
    .status-assigned { background: rgba(56,189,85,0.15); color: #38bd55; }
    .status-picked_up { background: rgba(56,189,85,0.15); color: #38bd55; }
    .status-delivered { background: rgba(56,189,85,0.2); color: #38bd55; }
    .status-cancelled { background: rgba(239,68,68,0.15); color: #f87171; }
</style>
@endsection