<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Assign Rider - Fetch and Go</title>
    
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
                                <h1 class="text-xl font-bold text-gray-800">Fetch and Go Staff</h1>
                            </a>
                        </div>
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500">
                                Dashboard
                            </a>
                        </div>
                    </div>
                    
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <div class="ml-3 relative">
                            <div>
                                <button type="button" onclick="toggleDropdown()" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none">
                                    {{ Auth::user()->name }}
                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <div id="dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Logout
                                    </button>
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
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Order Details -->
                        <div class="bg-white rounded-lg shadow">
                            <div class="p-6 border-b">
                                <h2 class="text-xl font-semibold text-gray-900">Order #{{ $order->id }}</h2>
                            </div>
                            <div class="p-6 space-y-4">
                                <div>
                                    <p class="text-sm text-gray-500">Customer</p>
                                    <p class="font-medium">{{ $order->customer->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Pickup Location</p>
                                    <p class="font-medium">{{ $order->pickup_location }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Dropoff Location</p>
                                    <p class="font-medium">{{ $order->dropoff_location }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Item Description</p>
                                    <p>{{ $order->item_description }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Required Vehicle</p>
                                    <p class="font-medium">{{ ucfirst($order->required_vehicle_type) }}</p>
                                </div>
                                @if($order->delivery_instructions)
                                    <div class="mt-3 p-2 bg-yellow-50 rounded">
                                        <p class="text-xs text-yellow-700 font-semibold">📝 Delivery Instructions:</p>
                                        <p class="text-sm text-yellow-800">{{ $order->delivery_instructions }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Assign Rider Form -->
                        <div class="bg-white rounded-lg shadow">
                            <div class="p-6 border-b">
                                <h2 class="text-xl font-semibold text-gray-900">Assign a Rider</h2>
                            </div>
                            <div class="p-6">
                                <form method="POST" action="{{ route('orders.assign', $order) }}" class="space-y-4">
                                    @csrf
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Rider</label>
                                        <select name="rider_id" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                            <option value="">Choose a rider...</option>
                                            @foreach($availableRiders as $rider)
                                                <option value="{{ $rider->id }}">
                                                    {{ $rider->user->name }} - ⭐ {{ $rider->rating }} - 📦 {{ $rider->total_deliveries }} deliveries
                                                    @if($rider->vehicle)
                                                        - {{ ucfirst($rider->vehicle->vehicle_type) }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="flex justify-end space-x-3">
                                        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                            Cancel
                                        </a>
                                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                            Assign Order
                                        </button>
                                    </div>
                                </form>
                                
                                @if($availableRiders->count() == 0)
                                    <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                        <p class="text-yellow-800">No available riders at the moment.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
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