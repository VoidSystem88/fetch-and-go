@extends('layouts.staff')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Available Riders</h1>
            <p class="text-gray-600">Riders ready for delivery assignments</p>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-900">Available Riders</h2>
                <p class="text-sm text-gray-500 mt-1">Riders ready for delivery assignments</p>
            </div>
            <div class="p-6">
                @if($availableRiders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deliveries</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehicle</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($availableRiders as $rider)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $rider->id }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $rider->user->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">⭐ {{ number_format($rider->rating, 1) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $rider->total_deliveries }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            @if($rider->vehicle)
                                                {{ ucfirst($rider->vehicle->vehicle_type) }}
                                            @else
                                                No vehicle
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Available</span>
                                        </td>
                                    </table>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">No available riders at the moment</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection