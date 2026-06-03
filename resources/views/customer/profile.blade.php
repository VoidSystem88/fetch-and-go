@extends('layouts.customer')

@section('content')
<div class="py-4">
    <div class="w-full px-4 max-w-2xl mx-auto">
        
        <div class="mb-6">
            <h1 class="text-2xl font-bold" style="color: white;">My Profile</h1>
            <p class="text-sm" style="color: rgba(255,255,255,0.5);">Manage your account information</p>
        </div>

        @if(session('success'))
            <div class="glass-card mb-4" style="background: rgba(56,189,85,0.1); border-color: rgba(56,189,85,0.3);">
                <i class="fas fa-check-circle" style="color: #38bd55;"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Birthday Banner - if birthday today -->
        @php
            $isBirthday = auth()->user()->isBirthdayToday();
            $hasBirthdayDiscount = auth()->user()->hasBirthdayDiscountThisYear();
        @endphp

        @if($isBirthday && !$hasBirthdayDiscount)
            <div class="glass-card mb-4" style="background: linear-gradient(135deg, rgba(56,189,85,0.1), rgba(224,124,52,0.1)); border-color: rgba(56,189,85,0.3);">
                <div class="flex justify-between items-center">
                    <div>
                        <i class="fas fa-birthday-cake text-2xl mb-1" style="color: #e07c34;"></i>
                        <p class="text-sm font-semibold" style="color: white;">Happy Birthday! 🎂</p>
                        <p class="text-xs" style="color: rgba(255,255,255,0.7);">Claim your special birthday discount!</p>
                    </div>
                    <form method="POST" action="{{ route('customer.birthday.claim') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-lg text-sm" style="background: #38bd55; color: white;">
                            Claim ₱50 OFF
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <!-- Profile Info -->
        <div class="glass-card mb-5">
            <div class="flex items-center gap-4 mb-5">
                <div class="w-16 h-16 rounded-full bg-gradient-to-r from-[#38bd55] to-[#e07c34] flex items-center justify-center text-white text-2xl font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="text-lg font-bold" style="color: white;">{{ auth()->user()->name }}</h3>
                    <p style="color: rgba(255,255,255,0.5);">{{ auth()->user()->email }}</p>
                    <p class="text-xs mt-1" style="color: rgba(255,255,255,0.3);">Member since {{ auth()->user()->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('customer.profile.update') }}">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="block text-xs mb-1" style="color: rgba(255,255,255,0.6);">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" 
                           class="w-full px-3 py-2 rounded-lg" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
                </div>
                
                <div class="mb-3">
                    <label class="block text-xs mb-1" style="color: rgba(255,255,255,0.6);">Email Address</label>
                    <input type="email" value="{{ auth()->user()->email }}" disabled 
                           class="w-full px-3 py-2 rounded-lg" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); color: rgba(255,255,255,0.4);">
                </div>
                
                <div class="mb-3">
                    <label class="block text-xs mb-1" style="color: rgba(255,255,255,0.6);">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" 
                           class="w-full px-3 py-2 rounded-lg" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
                </div>

                <div class="mb-3">
                    <label class="block text-xs mb-1" style="color: rgba(255,255,255,0.6);">Birthday</label>
                    <input type="date" name="birthday" value="{{ old('birthday', auth()->user()->birthday) }}" 
                           class="w-full px-3 py-2 rounded-lg" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
                    <p class="text-xs mt-1" style="color: rgba(255,255,255,0.3);">You'll receive a special discount on your birthday!</p>
                </div>
                
                <button type="submit" class="w-full mt-3 py-2 rounded-lg font-semibold" style="background: linear-gradient(135deg, #38bd55, #2a9e46); color: white; border: none; cursor: pointer;">Save Changes</button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="glass-card">
            <h3 class="text-base font-semibold mb-3" style="color: white;">Change Password</h3>
            <form method="POST" action="{{ route('customer.password.update') }}">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="block text-xs mb-1" style="color: rgba(255,255,255,0.6);">Current Password</label>
                    <input type="password" name="current_password" required class="w-full px-3 py-2 rounded-lg" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
                </div>
                
                <div class="mb-3">
                    <label class="block text-xs mb-1" style="color: rgba(255,255,255,0.6);">New Password</label>
                    <input type="password" name="password" required class="w-full px-3 py-2 rounded-lg" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
                </div>
                
                <div class="mb-3">
                    <label class="block text-xs mb-1" style="color: rgba(255,255,255,0.6);">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="w-full px-3 py-2 rounded-lg" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
                </div>
                
                <button type="submit" class="w-full py-2 rounded-lg font-semibold" style="background: linear-gradient(135deg, #38bd55, #2a9e46); color: white; border: none; cursor: pointer;">Change Password</button>
            </form>
        </div>
        
        <div class="mt-4 text-center">
            <a href="{{ route('dashboard') }}" class="text-sm" style="color: rgba(255,255,255,0.5);">← Back to Dashboard</a>
        </div>
        
    </div>
</div>

<style>
    .glass-card {
        background: #1e1e1e;
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,0.05);
        padding: 20px;
        transition: all 0.2s;
    }
    
    .glass-card:hover {
        border-color: rgba(56,189,85,0.3);
    }
    
    input:focus {
        outline: none;
        border-color: #38bd55 !important;
    }
</style>
@endsection