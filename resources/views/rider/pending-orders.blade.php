@extends('layouts.rider')

@section('content')
<div class="py-4">
    <div class="w-full px-4">
        
        <div class="mb-5">
            <h1 class="text-xl font-bold text-gray-800">
                <i class="fas fa-clock mr-2 text-orange-500"></i> Pending Offers
            </h1>
            <p class="text-gray-500 text-sm">Review order details before accepting</p>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-2 text-sm rounded">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif

        @if($pendingOrders->count() > 0)
            <div class="space-y-4">
                @foreach($pendingOrders as $order)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <!-- Card Header -->
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-100 flex justify-between items-center">
                            <div>
                                <span class="text-sm font-mono bg-gray-200 px-2 py-0.5 rounded">#{{ $order->id }}</span>
                                @if($order->timeout_expires_at && now() > $order->timeout_expires_at)
                                    <span class="ml-2 text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded">Expired</span>
                                @else
                                    <span class="ml-2 text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded">Pending</span>
                                @endif
                            </div>
                            <div class="text-right">
                                @if($order->timeout_expires_at && now() < $order->timeout_expires_at)
                                    <p class="text-xs text-orange-500">
                                        <i class="fas fa-hourglass-half mr-1"></i> {{ $order->timeout_expires_at->diffInMinutes(now()) }} min left
                                    </p>
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
                            
                            <!-- Weight & Vehicle -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide">Weight</p>
                                    <p class="text-sm text-gray-700">{{ $order->estimated_weight_kg ?? 'N/A' }} kg</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide">Vehicle</p>
                                    <p class="text-sm text-gray-700 capitalize">{{ $order->required_vehicle_type }}</p>
                                </div>
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
                            <div class="flex gap-2">
                                <button onclick="showDetails({{ $order->id }})" class="flex-1 bg-gray-500 text-white py-2 rounded-lg text-sm font-medium hover:bg-gray-600 transition">
                                    <i class="fas fa-eye mr-1"></i> View Details
                                </button>
                                <form method="POST" action="{{ route('rider.accept-order', $order) }}" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition">
                                        <i class="fas fa-check mr-1"></i> Accept
                                    </button>
                                </form>
                                <button onclick="showDeclineModal({{ $order->id }})" class="flex-1 bg-red-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition">
                                    <i class="fas fa-times mr-1"></i> Decline
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Summary -->
            <div class="mt-4 text-center">
                <p class="text-xs text-gray-400">Total: {{ $pendingOrders->count() }} pending offer(s)</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                <i class="fas fa-inbox text-5xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">No pending offers</p>
                <p class="text-sm text-gray-400">Orders sent to you will appear here</p>
            </div>
        @endif
        
    </div>
</div>

<!-- Details Modal -->
@foreach($pendingOrders as $order)
<div id="details-modal-{{ $order->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center" style="display: none;">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-bold mb-4">Order Details #{{ $order->id }}</h3>
        <div class="space-y-3 text-sm">
            <div>
                <p class="text-xs text-gray-400">Item Description</p>
                <p class="text-gray-800">{{ $order->item_description }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">Pickup Location</p>
                <p class="text-gray-800">{{ $order->pickup_location }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">Dropoff Location</p>
                <p class="text-gray-800">{{ $order->dropoff_location }}</p>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <p class="text-xs text-gray-400">Weight</p>
                    <p class="text-gray-800">{{ $order->estimated_weight_kg ?? 'N/A' }} kg</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Vehicle Required</p>
                    <p class="text-gray-800 capitalize">{{ $order->required_vehicle_type }}</p>
                </div>
            </div>
            @if($order->delivery_instructions)
                <div>
                    <p class="text-xs text-gray-400">Delivery Instructions</p>
                    <p class="text-gray-800">{{ $order->delivery_instructions }}</p>
                </div>
            @endif
        </div>
        <div class="mt-5 flex justify-end">
            <button onclick="closeDetails({{ $order->id }})" class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm">Close</button>
        </div>
    </div>
</div>

<!-- Decline Modal -->
<div id="decline-modal-{{ $order->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center" style="display: none;">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-bold mb-4">Decline Order #{{ $order->id }}</h3>
        <form method="POST" action="{{ route('rider.decline-order', $order) }}">
            @csrf
            <textarea name="reason" rows="3" class="w-full border rounded-lg p-2 mb-4 text-sm" placeholder="Reason for declining (optional)"></textarea>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-red-600 text-white py-2 rounded-lg text-sm">Confirm Decline</button>
                <button type="button" onclick="closeDeclineModal({{ $order->id }})" class="flex-1 bg-gray-500 text-white py-2 rounded-lg text-sm">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endforeach

<script>
function showDetails(orderId) {
    document.getElementById('details-modal-' + orderId).style.display = 'flex';
}

function closeDetails(orderId) {
    document.getElementById('details-modal-' + orderId).style.display = 'none';
}

function showDeclineModal(orderId) {
    document.getElementById('decline-modal-' + orderId).style.display = 'flex';
}

function closeDeclineModal(orderId) {
    document.getElementById('decline-modal-' + orderId).style.display = 'none';
}
</script>
@endsection