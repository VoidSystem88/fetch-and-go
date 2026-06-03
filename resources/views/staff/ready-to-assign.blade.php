@extends('layouts.staff')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Ready to Assign</h1>
            <p class="text-gray-500 text-sm mt-1">Send approved orders to riders</p>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-r-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-5">
                @if($approvedOrders->count() > 0)
                    <div class="space-y-4">
                        @foreach($approvedOrders as $order)
                            <div class="border border-gray-100 rounded-lg p-4">
                                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">#{{ $order->id }}</span>
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Approved</span>
                                        </div>
                                        <p class="text-gray-600 text-sm mb-2">{{ Str::limit($order->item_description, 50) }}</p>
                                        <div class="grid grid-cols-2 gap-2 text-sm">
                                            <div>
                                                <p class="text-xs text-gray-400">Weight</p>
                                                <p class="text-gray-700">{{ $order->estimated_weight_kg ?? 'N/A' }} kg</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-400">Vehicle</p>
                                                <p class="text-gray-700 capitalize">{{ $order->required_vehicle_type }}</p>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <p class="text-xs text-gray-400">Pickup</p>
                                            <p class="text-sm text-gray-600">{{ Str::limit($order->pickup_location, 40) }}</p>
                                        </div>
                                        <div class="mt-1">
                                            <p class="text-xs text-gray-400">Dropoff</p>
                                            <p class="text-sm text-gray-600">{{ Str::limit($order->dropoff_location, 40) }}</p>
                                        </div>
                                        @if($order->delivery_instructions)
                                            <div class="mt-2 p-2 bg-yellow-50 rounded">
                                                <p class="text-xs text-yellow-700">📝 {{ Str::limit($order->delivery_instructions, 50) }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="md:w-64">
                                        <form method="POST" action="{{ route('staff.send-to-rider', $order) }}">
                                            @csrf
                                            <label class="block text-xs text-gray-500 mb-1">Select Rider</label>
                                            <select name="rider_id" required class="w-full border rounded-lg text-sm p-2 mb-2">
                                                <option value="">Choose rider...</option>
                                                @foreach($availableRiders as $rider)
                                                    <option value="{{ $rider->id }}">
                                                        {{ $rider->user->name }} - ⭐{{ $rider->rating }} - {{ $rider->total_deliveries }} deliveries
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="w-full bg-teal-500 text-white py-2 rounded-lg text-sm hover:bg-teal-600">
                                                <i class="fas fa-paper-plane mr-1"></i> Send to Rider
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10">
                        <i class="fas fa-check-circle text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No approved orders waiting for assignment</p>
                    </div>
                @endif
            </div>
        </div>
        
    </div>
</div>
@endsection