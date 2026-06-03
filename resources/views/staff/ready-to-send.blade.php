@extends('layouts.staff')

@section('content')
<div class="py-4">
    <div class="w-full px-4">
        
        <div class="mb-5">
            <h1 class="text-xl font-bold text-gray-800">
                <i class="fas fa-paper-plane mr-2 text-blue-600"></i> Send to Rider
            </h1>
            <p class="text-gray-500 text-sm">Send pending orders to available riders</p>
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

        @if($pendingOrders->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <!-- Table Header -->
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                    <div class="grid grid-cols-12 gap-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="col-span-1">ID</div>
                        <div class="col-span-3">Item</div>
                        <div class="col-span-2">Pickup</div>
                        <div class="col-span-2">Dropoff</div>
                        <div class="col-span-1">Weight</div>
                        <div class="col-span-3">Send to Rider</div>
                    </div>
                </div>
                
                <!-- Table Rows -->
                <div class="divide-y divide-gray-100">
                    @foreach($pendingOrders as $order)
                        <div class="px-4 py-3 hover:bg-gray-50 transition">
                            <div class="grid grid-cols-12 gap-3 items-center">
                                <!-- ID -->
                                <div class="col-span-1">
                                    <span class="text-sm font-mono bg-gray-100 px-2 py-0.5 rounded">#{{ $order->id }}</span>
                                </div>
                                
                                <!-- Item -->
                                <div class="col-span-3">
                                    <p class="text-sm text-gray-700 truncate" title="{{ $order->item_description }}">
                                        {{ Str::limit($order->item_description, 30) }}
                                    </p>
                                </div>
                                
                                <!-- Pickup -->
                                <div class="col-span-2">
                                    <p class="text-sm text-gray-500 truncate" title="{{ $order->pickup_location }}">
                                        {{ Str::limit($order->pickup_location, 20) }}
                                    </p>
                                </div>
                                
                                <!-- Dropoff -->
                                <div class="col-span-2">
                                    <p class="text-sm text-gray-500 truncate" title="{{ $order->dropoff_location }}">
                                        {{ Str::limit($order->dropoff_location, 20) }}
                                    </p>
                                </div>
                                
                                <!-- Weight -->
                                <div class="col-span-1">
                                    <span class="text-sm text-gray-600">{{ $order->estimated_weight_kg ?? 'N/A' }} kg</span>
                                </div>
                                
                                <!-- Send Form -->
                                <div class="col-span-3">
                                    <form method="POST" action="{{ route('staff.send-to-rider', $order) }}" class="flex gap-2">
                                        @csrf
                                        <select name="rider_id" required class="flex-1 text-sm border border-gray-300 rounded-lg px-2 py-1.5 bg-white focus:ring-1 focus:ring-blue-500">
                                            <option value="">Select Rider</option>
                                            @foreach($availableRiders as $rider)
                                                <option value="{{ $rider->id }}">
                                                    {{ $rider->user->name }} (⭐{{ $rider->rating }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-blue-700 transition whitespace-nowrap">
                                            <i class="fas fa-paper-plane mr-1"></i> Send
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Delivery Instructions (if any) -->
                            @if($order->delivery_instructions)
                                <div class="mt-2 ml-1">
                                    <span class="text-xs text-yellow-600 bg-yellow-50 px-2 py-0.5 rounded">
                                        <i class="fas fa-info-circle mr-1"></i> {{ Str::limit($order->delivery_instructions, 50) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Summary -->
            <div class="mt-3 text-right">
                <p class="text-xs text-gray-400">Total: {{ $pendingOrders->count() }} pending order(s)</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                <i class="fas fa-inbox text-5xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">No pending orders</p>
                <p class="text-sm text-gray-400">All orders have been sent to riders</p>
            </div>
        @endif
        
    </div>
</div>
@endsection