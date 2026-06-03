<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vehicle Verification - Fetch and Go Admin</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}">
                                <h1 class="text-xl font-bold text-gray-800">Fetch and Go Admin</h1>
                            </a>
                        </div>
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500">
                                Dashboard
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500">
                                Users
                            </a>
                            <a href="{{ route('admin.vehicles.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.vehicles.*') ? 'border-blue-400 text-gray-900' : 'border-transparent text-gray-500' }} text-sm font-medium leading-5">
                                Vehicles
                            </a>
                            <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500">
                                Reports
                            </a>
                        </div>
                    </div>
                    
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <div class="ml-3 relative">
                            <button type="button" onclick="toggleDropdown()" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700">
                                {{ Auth::user()->name }}
                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div id="dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        
        <main>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <h1 class="text-2xl font-bold text-gray-900 mb-6">Vehicle Verification</h1>
                        
                        @if(session('success'))
                            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <!-- Pending Vehicles -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Pending Verification ({{ $pendingVehicles->count() }})</h2>
                            
                            @if($pendingVehicles->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($pendingVehicles as $vehicle)
                                        <div class="border rounded-lg p-4">
                                            <p class="font-medium text-gray-900">Rider: {{ $vehicle->rider->user->name }}</p>
                                            <p class="text-sm text-gray-600">Vehicle: {{ ucfirst($vehicle->vehicle_type) }}</p>
                                            <p class="text-sm text-gray-600">Plate #: {{ $vehicle->plate_number }}</p>
                                            @if($vehicle->max_weight_kg)
                                                <p class="text-sm text-gray-600">Max Weight: {{ $vehicle->max_weight_kg }} kg</p>
                                            @endif
                                            <div class="mt-3 flex gap-2">
                                                <form method="POST" action="{{ route('admin.vehicles.verify', $vehicle) }}">
                                                    @csrf
                                                    <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">Verify</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.vehicles.reject', $vehicle) }}" onsubmit="return confirm('Reject this vehicle?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">Reject</button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">No pending vehicle verifications.</p>
                            @endif
                        </div>
                        
                        <!-- Verified Vehicles -->
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Verified Vehicles</h2>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rider</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehicle Type</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plate Number</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Verified By</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($verifiedVehicles as $vehicle)
                                            <tr>
                                                <td class="px-6 py-4">{{ $vehicle->rider->user->name }}</td>
                                                <td class="px-6 py-4">{{ ucfirst($vehicle->vehicle_type) }}</td>
                                                <td class="px-6 py-4">{{ $vehicle->plate_number }}</td>
                                                <td class="px-6 py-4">{{ $vehicle->verifier->name ?? 'N/A' }}</td>
                                                <td class="px-6 py-4">{{ $vehicle->verified_at ? $vehicle->verified_at->format('Y-m-d') : 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-4">
                                {{ $verifiedVehicles->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdown');
            dropdown.classList.toggle('hidden');
        }
        
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('dropdown');
            const button = event.target.closest('button');
            if (!button || !button.onclick || button.onclick.toString().indexOf('toggleDropdown') === -1) {
                if (!dropdown.classList.contains('hidden')) {
                    dropdown.classList.add('hidden');
                }
            }
        });
    </script>
</body>
</html>