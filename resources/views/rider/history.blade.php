@extends('layouts.rider')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Delivery History</h1>
            <p class="text-gray-600">Your completed deliveries</p>
        </div>

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
                                    <p class="text-xs text-gray-400 mt-1">{{ $delivery->delivered_at->format('M d, Y h:i A') }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Delivered</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        {{ $deliveryHistory->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-history text-5xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No delivery history yet</p>
                        <p class="text-sm text-gray-400">Your completed deliveries will appear here</p>
                    </div>
                @endif
            </div>
        </div>
        
    </div>
</div>
@endsection