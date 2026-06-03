@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">🚗 Vehicle Verification</h1>
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            
            <!-- Pending Verification Section -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">⏳ Pending Verification ({{ $pendingVehicles->count() }})</h2>
                
                @if($pendingVehicles->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($pendingVehicles as $vehicle)
                            <div class="border rounded-lg p-4 bg-gray-50">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-xl">
                                        🚗
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $vehicle->rider->user->name }}</p>
                                        <p class="text-xs text-gray-500">Rider ID: #{{ $vehicle->rider_id }}</p>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-sm text-gray-600">🚘 Vehicle: <span class="font-medium">{{ ucfirst($vehicle->vehicle_type) }}</span></p>
                                    <p class="text-sm text-gray-600">📝 Plate #: <span class="font-mono">{{ $vehicle->plate_number }}</span></p>
                                    @if($vehicle->max_weight_kg)
                                        <p class="text-sm text-gray-600">⚖️ Max Weight: {{ $vehicle->max_weight_kg }} kg</p>
                                    @endif
                                </div>
                                <div class="mt-4 flex gap-2">
                                    <form method="POST" action="{{ route('admin.vehicles.verify', $vehicle) }}">
                                        @csrf
                                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition">
                                            ✓ Verify
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.vehicles.reject', $vehicle) }}" onsubmit="return confirm('Are you sure you want to reject this vehicle?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                                            ✗ Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 bg-gray-50 rounded-lg">
                        <div class="text-4xl mb-2">✅</div>
                        <p class="text-gray-500">No pending vehicle verifications.</p>
                        <p class="text-sm text-gray-400">All vehicles have been verified.</p>
                    </div>
                @endif
            </div>
            
            <!-- Verified Vehicles Section -->
            <div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">✅ Verified Vehicles ({{ $verifiedVehicles->total() }})</h2>
                
                @if($verifiedVehicles->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border rounded-lg">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rider</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plate Number</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verified By</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Verified</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($verifiedVehicles as $vehicle)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-sm font-bold text-green-800">
                                                    {{ substr($vehicle->rider->user->name, 0, 1) }}
                                                </div>
                                                <span class="font-medium text-gray-900">{{ $vehicle->rider->user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                                {{ ucfirst($vehicle->vehicle_type) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 font-mono text-sm text-gray-600">{{ $vehicle->plate_number }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $vehicle->verifier->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $vehicle->verified_at ? $vehicle->verified_at->format('M d, Y') : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $verifiedVehicles->links() }}
                    </div>
                @else
                    <div class="text-center py-8 bg-gray-50 rounded-lg">
                        <div class="text-4xl mb-2">🚫</div>
                        <p class="text-gray-500">No verified vehicles yet.</p>
                        <p class="text-sm text-gray-400">Verified vehicles will appear here.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection