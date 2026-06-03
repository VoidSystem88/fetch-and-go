@extends('layouts.rider')

@section('content')
<div class="py-4">
    <div class="w-full px-4">
        
        <div class="mb-5">
            <h1 class="text-xl font-bold text-gray-800">
                <i class="fas fa-truck mr-2 text-orange-500"></i> Current Deliveries
            </h1>
            <p class="text-gray-500 text-sm">Orders assigned to you</p>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-2 text-sm rounded">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-2 text-sm rounded">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
            </div>
        @endif

        @if($currentOrders->count() > 0)
            <div class="space-y-4">
                @foreach($currentOrders as $order)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <!-- Card Header -->
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-100 flex justify-between items-center">
                            <div>
                                <span class="text-sm font-mono bg-gray-200 px-2 py-0.5 rounded">#{{ $order->id }}</span>
                                @if($order->status == 'assigned')
                                    <span class="ml-2 text-xs bg-purple-100 text-purple-700 px-2 py-0.5 rounded">Ready for Pickup</span>
                                @else
                                    <span class="ml-2 text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded">On Delivery</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="p-4 space-y-3">
                            <!-- Item -->
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide">Item Description</p>
                                <p class="text-sm text-gray-800">{{ $order->item_description }}</p>
                            </div>
                            
                            <!-- Pickup & Dropoff -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide">Pickup</p>
                                    <p class="text-sm text-gray-700">{{ Str::limit($order->pickup_location, 40) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide">Dropoff</p>
                                    <p class="text-sm text-gray-700">{{ Str::limit($order->dropoff_location, 40) }}</p>
                                </div>
                            </div>
                            
                            <!-- Customer Info -->
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide">Customer</p>
                                <p class="text-sm text-gray-700">{{ $order->customer->name }}</p>
                                <p class="text-xs text-gray-500">{{ $order->customer->phone }}</p>
                            </div>
                            
                            <!-- Delivery Instructions -->
                            @if($order->delivery_instructions)
                                <div class="bg-yellow-50 p-2 rounded">
                                    <p class="text-xs text-yellow-700">
                                        <i class="fas fa-info-circle mr-1"></i> 
                                        <span class="font-semibold">Instructions:</span> {{ $order->delivery_instructions }}
                                    </p>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Card Footer - Actions -->
                        <div class="bg-gray-50 px-4 py-3 border-t border-gray-100">
                            @if($order->status == 'assigned')
                                <form method="POST" action="{{ route('orders.pickup', $order) }}">
                                    @csrf
                                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                                        <i class="fas fa-box-open mr-1"></i> Mark as Picked Up
                                    </button>
                                </form>
                            @elseif($order->status == 'picked_up')
                                <form method="POST" action="{{ route('orders.deliver', $order) }}">
                                    @csrf
                                    <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition">
                                        <i class="fas fa-check-circle mr-1"></i> Mark as Delivered
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Summary -->
            <div class="mt-4 text-center">
                <p class="text-xs text-gray-400">Total: {{ $currentOrders->count() }} active delivery(ies)</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                <i class="fas fa-check-circle text-5xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">No active deliveries</p>
                <p class="text-sm text-gray-400">You're all caught up!</p>
            </div>
        @endif
        
    </div>
</div>
@endsection