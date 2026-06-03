<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->id() }}">
    <meta name="user-role" content="{{ auth()->user()->role }}">
    <title>{{ config('app.name', 'Fetch and Go') }} - Admin</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <style>
        /* Dark & Dirty White Color Scheme */
        body {
            background-color: #1a1a1a !important;
        }
        .bg-white {
            background-color: #2a2a2a !important;
        }
        .bg-gray-50 {
            background-color: #222222 !important;
        }
        .bg-gray-100 {
            background-color: #333333 !important;
        }
        .shadow, .shadow-md, .shadow-lg, .shadow-xl {
            box-shadow: 0 1px 3px 0 rgba(0,0,0,0.3), 0 1px 2px 0 rgba(0,0,0,0.2) !important;
        }
        .border, .border-b, .border-t, .border-l, .border-r, 
        .border-gray-100, .border-gray-200, .border-gray-300,
        .divide-y .divide-gray-200 {
            border-color: #3a3a3a !important;
        }
        .text-gray-900, .text-gray-800, .text-gray-700 {
            color: #f5f0e0 !important;
        }
        .text-gray-600, .text-gray-500, .text-gray-400 {
            color: #c4b89a !important;
        }
        .text-blue-600 {
            color: #d4a000 !important;
        }
        .border-blue-400, .border-blue-500 {
            border-color: #d4a000 !important;
        }
        .bg-blue-600 {
            background-color: #c49b00 !important;
        }
        .bg-yellow-100 {
            background-color: #4a3a1a !important;
        }
        .text-yellow-800 {
            color: #f5d742 !important;
        }
        .bg-green-100 {
            background-color: #1a3a1a !important;
        }
        .text-green-800 {
            color: #6fbf6f !important;
        }
        .bg-red-100 {
            background-color: #3a1a1a !important;
        }
        .text-red-800 {
            color: #ef7e7e !important;
        }
        .bg-blue-100 {
            background-color: #1a2a3a !important;
        }
        .text-blue-800 {
            color: #7eb5ef !important;
        }
        .bg-purple-100 {
            background-color: #2a1a3a !important;
        }
        .text-purple-800 {
            color: #c47eef !important;
        }
        .bg-indigo-100 {
            background-color: #1a2a4a !important;
        }
        .text-indigo-800 {
            color: #8f9eef !important;
        }
        .hover\:bg-gray-50:hover, .hover\:bg-gray-100:hover {
            background-color: #3a3a3a !important;
        }
        .hover\:text-gray-900:hover {
            color: #f5f0e0 !important;
        }
        button.text-gray-500 {
            color: #c4b89a !important;
        }
        button.text-gray-500:hover {
            color: #f5f0e0 !important;
        }
        a.text-gray-500 {
            color: #c4b89a !important;
        }
        a.text-gray-500:hover {
            color: #f5f0e0 !important;
        }
        .bg-gradient-to-r {
            background: linear-gradient(135deg, #c49b00 0%, #a07800 100%) !important;
        }
    </style>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen">
        <!-- Navigation Bar -->
        <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                            <div class="text-2xl">🚚</div>
                            <h1 class="text-xl font-bold text-gray-800">Fetch and Go <span class="text-blue-600">Admin</span></h1>
                        </a>
                    </div>
                    
                    <div class="hidden md:flex items-center space-x-6">
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-gray-800 hover:bg-gray-100' }}">
                            📊 Dashboard
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white' : 'text-gray-800 hover:bg-gray-100' }}">
                            👥 Users
                        </a>
                        <a href="{{ route('admin.vehicles.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.vehicles.*') ? 'bg-blue-600 text-white' : 'text-gray-800 hover:bg-gray-100' }}">
                            🚗 Vehicles
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.reports.*') ? 'bg-blue-600 text-white' : 'text-gray-800 hover:bg-gray-100' }}">
                            📈 Reports
                        </a>
                        <a href="{{ route('admin.audit-logs.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.audit-logs.*') ? 'bg-blue-600 text-white' : 'text-gray-800 hover:bg-gray-100' }}">
                            📝 Audit Logs
                        </a>
                        <a href="{{ route('admin.recent-orders.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.recent-orders.*') ? 'bg-blue-600 text-white' : 'text-gray-800 hover:bg-gray-100' }}">
                            📋 Orders
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button onclick="toggleDropdown()" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="hidden md:inline text-sm font-medium text-gray-800">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                                <div class="px-4 py-2 border-b">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        🚪 Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        
        <main>
            @yield('content')
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
            if (!button || button.onclick.toString().indexOf('toggleDropdown') === -1) {
                if (dropdown && !dropdown.classList.contains('hidden')) {
                    dropdown.classList.add('hidden');
                }
            }
        });
    </script>
    
    @vite(['resources/js/app.js'])
</body>
</html>