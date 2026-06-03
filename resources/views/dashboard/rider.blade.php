@extends('layouts.rider')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Rider Dashboard</h1>
            <p class="text-gray-600">Welcome back, {{ auth()->user()->name }}!</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="text-2xl font-bold text-orange-600">{{ $currentOrders->count() }}</div>
                <div class="text-gray-600 text-sm">Current Deliveries</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $completedToday }}</div>
                <div class="text-gray-600 text-sm">Completed Today</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="text-2xl font-bold text-blue-600">₱{{ number_format($totalEarnings, 2) }}</div>
                <div class="text-gray-600 text-sm">Total Earnings</div>
            </div>
        </div>

        <!-- Current Orders -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-900">Current Deliveries</h2>
                <p class="text-sm text-gray-500 mt-1">Orders assigned to you</p>
            </div>
            <div class="p-6">
                @if($currentOrders->count() > 0)
                    <div class="space-y-4">
                        @foreach($currentOrders as $order)
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium text-gray-900">Order #{{ $order->id }}</p>
                                        <p class="text-sm text-gray-600 mt-1">{{ $order->item_description }}</p>
                                        <p class="text-sm text-gray-500 mt-1">From: {{ $order->pickup_location }}</p>
                                        <p class="text-sm text-gray-500">To: {{ $order->dropoff_location }}</p>
                                        
                                        @if($order->delivery_instructions)
                                            <div class="mt-2 p-2 bg-yellow-50 rounded-lg">
                                                <p class="text-xs text-yellow-700 font-semibold">📝 Instructions:</p>
                                                <p class="text-sm text-yellow-800">{{ $order->delivery_instructions }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    <span class="status-badge status-{{ $order->status }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </div>
                                <div class="mt-3 flex gap-2">
                                    @if($order->status == 'assigned')
                                        <form method="POST" action="{{ route('orders.pickup', $order) }}">
                                            @csrf
                                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                                                Mark as Picked Up
                                            </button>
                                        </form>
                                    @elseif($order->status == 'picked_up')
                                        <form method="POST" action="{{ route('orders.deliver', $order) }}">
                                            @csrf
                                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">
                                                Mark as Delivered
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-5xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No active deliveries</p>
                        <p class="text-sm text-gray-400">You're all caught up!</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Delivery History -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-900">Recent Deliveries</h2>
                <p class="text-sm text-gray-500 mt-1">Your delivery history</p>
            </div>
            <div class="p-6">
                @if($deliveryHistory->count() > 0)
                    <div class="space-y-3">
                        @foreach($deliveryHistory as $delivery)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">Order #{{ $delivery->id }}</p>
                                    <p class="text-sm text-gray-500">{{ $delivery->pickup_location }} → {{ $delivery->dropoff_location }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="status-badge status-delivered">Delivered</span>
                                    <p class="text-xs text-gray-400 mt-1">{{ $delivery->delivered_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">No delivery history yet</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Availability Toggle -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Availability Status</h2>
                    <p class="text-sm text-gray-500">Toggle to receive new delivery assignments</p>
                </div>
                <div>
                    @if($rider->is_available)
                        <span class="px-4 py-2 bg-green-100 text-green-800 rounded-lg inline-flex items-center gap-2">
                            <i class="fas fa-circle text-xs"></i> Available for Deliveries
                        </span>
                    @else
                        <span class="px-4 py-2 bg-red-100 text-red-800 rounded-lg inline-flex items-center gap-2">
                            <i class="fas fa-circle text-xs"></i> Not Available
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection