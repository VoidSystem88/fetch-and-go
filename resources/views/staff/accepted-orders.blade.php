@extends('layouts.staff')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Accepted Orders</h1>
            <p class="text-gray-500 text-sm mt-1">Orders accepted by riders - pending your approval</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-5">
                @if($acceptedOrders->count() > 0)
                    <div class="space-y-4">
                        @foreach($acceptedOrders as $order)
                            <div class="border border-gray-100 rounded-lg p-4">
                                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">#{{ $order->id }}</span>
                                            <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-700">Accepted by Rider</span>
                                        </div>
                                        <p class="text-gray-600 text-sm mb-2">{{ Str::limit($order->item_description, 50) }}</p>
                                        <div class="grid grid-cols-2 gap-2 text-sm">
                                            <div>
                                                <p class="text-xs text-gray-400">Pickup</p>
                                                <p class="text-gray-700">{{ Str::limit($order->pickup_location, 30) }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-400">Dropoff</p>
                                                <p class="text-gray-700">{{ Str::limit($order->dropoff_location, 30) }}</p>
                                            </div>
                                        </div>
                                        <div class="mt-2 p-2 bg-blue-50 rounded">
                                            <p class="text-xs text-blue-700">
                                                <i class="fas fa-motorcycle mr-1"></i> Rider: {{ $order->assignedRider->user->name }}
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        <form method="POST" action="{{ route('staff.approve-accepted', $order) }}">
                                            @csrf
                                            <button type="submit" class="px-5 py-2 bg-green-500 text-white rounded-lg text-sm font-medium hover:bg-green-600">
                                                <i class="fas fa-check-circle mr-1"></i> Approve Order
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No accepted orders waiting for approval</p>
                    </div>
                @endif
            </div>
        </div>
        
    </div>
</div>
@endsection