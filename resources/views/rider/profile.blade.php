@extends('layouts.rider')

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                <i class="fas fa-user-circle mr-3 text-orange-500"></i> My Profile
            </h1>
            <p class="text-gray-500 text-sm mt-1">
                <i class="fas fa-sliders-h mr-2"></i> Manage your account information
            </p>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-r-lg">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-r-lg">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                @foreach($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <!-- Profile Information -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-id-card mr-2 text-orange-500"></i> Personal Information
                </h2>
            </div>
            <div class="p-5">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-r from-orange-500 to-orange-600 flex items-center justify-center text-white text-2xl font-bold">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">
                            <i class="fas fa-user mr-2 text-orange-500"></i> {{ $user->name }}
                        </h3>
                        <p class="text-gray-500">
                            <i class="fas fa-envelope mr-2"></i> {{ $user->email }}
                        </p>
                        <p class="text-sm text-gray-400 mt-1">
                            <i class="fas fa-calendar-alt mr-2"></i> Rider since {{ $user->created_at->format('M d, Y') }}
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('rider.profile.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-user mr-1"></i> Full Name
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                               class="w-full border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-800">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-envelope mr-1"></i> Email Address
                        </label>
                        <input type="email" value="{{ $user->email }}" disabled 
                               class="w-full bg-gray-100 border-gray-300 rounded-lg text-gray-500">
                        <p class="text-xs text-gray-400 mt-1">
                            <i class="fas fa-info-circle mr-1"></i> Email cannot be changed
                        </p>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-phone mr-1"></i> Phone Number
                        </label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                               class="w-full border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-800">
                    </div>
                    
                    <button type="submit" style="background-color: #ea580c; color: white; padding: 10px 24px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; transition: background-color 0.2s;">
                        <i class="fas fa-save mr-2"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>

        <!-- Rider Statistics -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-6">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-chart-line mr-2 text-orange-500"></i> Rider Statistics
                </h2>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-truck text-2xl text-orange-500 mb-2 block"></i>
                        <div class="text-2xl font-bold text-orange-600">{{ $rider->total_deliveries }}</div>
                        <div class="text-xs text-gray-500">Total Deliveries</div>
                    </div>
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-star text-2xl text-yellow-500 mb-2 block"></i>
                        <div class="text-2xl font-bold text-yellow-500">{{ number_format($rider->rating, 1) }}</div>
                        <div class="text-xs text-gray-500">Rating</div>
                    </div>
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <i class="fas {{ $rider->is_available ? 'fa-circle text-green-500' : 'fa-circle text-red-500' }} text-2xl mb-2 block"></i>
                        <div class="text-2xl font-bold {{ $rider->is_available ? 'text-green-600' : 'text-red-600' }}">{{ $rider->is_available ? 'Online' : 'Offline' }}</div>
                        <div class="text-xs text-gray-500">Status</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-6 text-center">
            <a href="{{ route('rider.dashboard') }}" class="text-orange-500 hover:text-orange-600 text-sm">
                <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>
        
    </div>
</div>
@endsection