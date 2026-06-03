@extends('layouts.rider')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Rider Dashboard</h1>
            <p class="text-gray-500 text-sm mt-1">Welcome back, {{ auth()->user()->name }}!</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-5 text-center border-l-4 border-orange-500">
                <div class="text-2xl md:text-3xl font-bold text-orange-600">{{ $currentOrders->count() }}</div>
                <div class="text-gray-500 text-sm mt-1">Current Deliveries</div>
                <i class="fas fa-truck text-gray-300 text-lg mt-2"></i>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5 text-center border-l-4 border-green-500">
                <div class="text-2xl md:text-3xl font-bold text-green-600">{{ $completedToday }}</div>
                <div class="text-gray-500 text-sm mt-1">Completed Today</div>
                <i class="fas fa-check-circle text-gray-300 text-lg mt-2"></i>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5 text-center border-l-4 border-blue-500">
                <div class="text-2xl md:text-3xl font-bold text-blue-600">₱{{ number_format($totalEarnings, 2) }}</div>
                <div class="text-gray-500 text-sm mt-1">Total Earnings</div>
                <i class="fas fa-wallet text-gray-300 text-lg mt-2"></i>
            </div>
        </div>

        <!-- Availability Toggle -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Availability Status</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Toggle to receive new delivery assignments</p>
                    </div>
                    <i class="fas fa-toggle-on text-gray-300 text-xl"></i>
                </div>
            </div>
            <div class="p-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        @if($rider->is_available)
                            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-green-600 font-medium">Available for Deliveries</span>
                        @else
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <span class="text-red-600 font-medium">Not Available</span>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('rider.toggle-availability') }}">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto px-5 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg text-sm font-medium hover:from-orange-600 hover:to-orange-700 transition shadow-sm">
                            <i class="fas fa-sync-alt mr-2"></i>
                            {{ $rider->is_available ? 'Go Offline' : 'Go Online' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Quick Action Links -->
        <div class="grid grid-cols-2 gap-4 mt-6">
            <a href="{{ route('rider.deliveries') }}" class="bg-white rounded-xl shadow-sm p-4 text-center hover:shadow-md transition">
                <i class="fas fa-truck text-orange-500 text-2xl mb-2 block"></i>
                <span class="text-gray-700 text-sm font-medium">View Deliveries</span>
            </a>
            <a href="{{ route('rider.earnings') }}" class="bg-white rounded-xl shadow-sm p-4 text-center hover:shadow-md transition">
                <i class="fas fa-chart-line text-green-500 text-2xl mb-2 block"></i>
                <span class="text-gray-700 text-sm font-medium">View Earnings</span>
            </a>
        </div>
        
    </div>
</div>
@endsection