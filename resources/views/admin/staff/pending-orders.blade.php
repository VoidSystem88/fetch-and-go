@extends('layouts.staff')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Pending Orders</h1>
            <p class="text-gray-600">Orders waiting for approval</p>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-900">Pending Orders</h2>
                <p class="text-sm text-gray-500 mt-1">Orders waiting for approval</p>
            </div>
            <div class="p-6">
                @if($pendingOrders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pickup</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dropoff</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($pendingOrders as $order)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $order->customer->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($order->pickup_location, 25) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($order->dropoff_location, 25) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($order->item_description, 20) }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex gap-2">
                                                <form method="POST" action="{{ route('orders.approve', $order) }}">
                                                    @csrf
                                                    <button class="bg-green-600 text-white px-3 py-1 rounded text-sm">Approve</button>
                                                </form>
                                                <form method="POST" action="{{ route('orders.cancel', $order) }}">
                                                    @csrf
                                                    <input type="hidden" name="reason" value="Cancelled by staff">
                                                    <button class="bg-red-600 text-white px-3 py-1 rounded text-sm">Cancel</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">No pending orders</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection