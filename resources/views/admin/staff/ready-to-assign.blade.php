@extends('layouts.staff')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Ready to Assign</h1>
            <p class="text-gray-600">Approved orders waiting for rider assignment</p>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-900">Ready to Assign</h2>
                <p class="text-sm text-gray-500 mt-1">Approved orders waiting for rider assignment</p>
            </div>
            <div class="p-6">
                @if($approvedOrders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pickup</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dropoff</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehicle</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($approvedOrders as $order)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $order->customer->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($order->pickup_location, 25) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($order->dropoff_location, 25) }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                                {{ ucfirst($order->required_vehicle_type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('orders.assign-form', $order) }}" class="bg-blue-600 text-white px-3 py-1 rounded text-sm">Assign</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">No approved orders waiting for assignment</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection