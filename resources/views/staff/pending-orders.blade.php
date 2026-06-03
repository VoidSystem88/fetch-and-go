@extends('layouts.staff')

@section('content')
<div class="py-4">
    <div class="w-full px-4">
        
        <div class="mb-5 text-center">
            <h1 class="text-xl font-bold text-gray-800">
                <i class="fas fa-clock mr-2 text-blue-600"></i> Pending Orders
            </h1>
            <p class="text-gray-500 text-sm">Orders waiting to be sent to riders</p>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-2 text-sm rounded">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif

        @if($pendingOrders->count() > 0)
            <div class="space-y-4">
                @foreach($pendingOrders as $order)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden max-w-3xl mx-auto">
                        <!-- Card Header -->
                        <div class="bg-gray-50 px-6 py-3 border-b border-gray-100 flex justify-between items-center">
                            <span class="text-sm font-mono bg-gray-200 px-3 py-1 rounded">#{{ $order->id }}</span>
                            <span class="text-xs bg-yellow-100 text-yellow-700 px-3 py-1 rounded">Pending</span>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="p-6">
                            <div class="mb-4">
                                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Item Description</p>
                                <p class="text-base text-gray-800 font-medium">{{ $order->item_description }}</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Pickup Location</p>
                                    <p class="text-sm text-gray-700 bg-gray-50 p-2 rounded">{{ $order->pickup_location }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Dropoff Location</p>
                                    <p class="text-sm text-gray-700 bg-gray-50 p-2 rounded">{{ $order->dropoff_location }}</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-6 mb-4">
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Estimated Weight</p>
                                    <p class="text-sm text-gray-700">{{ $order->estimated_weight_kg ?? 'N/A' }} kg</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Vehicle Required</p>
                                    <p class="text-sm text-gray-700 capitalize">{{ $order->required_vehicle_type }}</p>
                                </div>
                            </div>
                            
                            @if($order->delivery_instructions)
                                <div class="mt-4 p-4 bg-yellow-50 rounded-lg border border-yellow-100">
                                    <p class="text-xs text-yellow-700 mb-1">
                                        <i class="fas fa-info-circle mr-1"></i> 
                                        <span class="font-semibold">Delivery Instructions:</span>
                                    </p>
                                    <p class="text-sm text-yellow-800">{{ $order->delivery_instructions }}</p>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Card Footer - Send Form (same line) -->
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                            <form method="POST" action="{{ route('staff.send-to-rider', $order) }}">
                                @csrf
                                <div class="flex flex-row gap-3 items-center">
                                    <select name="rider_id" required class="flex-1 text-sm border border-gray-300 rounded-lg px-4 py-2.5 bg-white focus:ring-1 focus:ring-blue-500">
                                        <option value="">-- Select Rider --</option>
                                        @foreach($availableRiders as $rider)
                                            <option value="{{ $rider->id }}">
                                                {{ $rider->user->name }} (⭐{{ $rider->rating }} | {{ $rider->total_deliveries }} deliveries)
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700 transition whitespace-nowrap">
                                        <i class="fas fa-paper-plane mr-2"></i> Send
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-4 text-center">
                <p class="text-xs text-gray-400">Total: {{ $pendingOrders->count() }} pending order(s)</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center max-w-2xl mx-auto">
                <i class="fas fa-inbox text-5xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">No pending orders</p>
            </div>
        @endif
        
    </div>
</div>
@endsection