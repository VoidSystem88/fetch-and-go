@extends('layouts.customer')

@section('content')
<div class="py-4">
    <div class="w-full px-4 max-w-2xl mx-auto">
        
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold" style="color: white;">My Discounts</h1>
            <p class="text-sm" style="color: rgba(255,255,255,0.5);">View and manage your discount codes</p>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex gap-2 mb-4">
            <a href="{{ route('customer.points.index') }}" class="flex-1 text-center px-3 py-2 rounded-lg text-sm font-medium transition" 
               style="background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.7);">
                <i class="fas fa-coins mr-1"></i> My Points
            </a>
            <a href="{{ route('customer.discounts.index') }}" class="flex-1 text-center px-3 py-2 rounded-lg text-sm font-medium transition" 
               style="background: rgba(56,189,85,0.2); color: #38bd55;">
                <i class="fas fa-tag mr-1"></i> My Discounts
            </a>
        </div>

        @if(session('success'))
            <div class="glass-card mb-4" style="background: rgba(56,189,85,0.1); border-color: rgba(56,189,85,0.3);">
                <i class="fas fa-check-circle" style="color: #38bd55;"></i> {!! session('success') !!}
            </div>
        @endif

        @if(session('error'))
            <div class="glass-card mb-4" style="background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.3);">
                <i class="fas fa-exclamation-circle" style="color: #f87171;"></i> {{ session('error') }}
            </div>
        @endif

        @if($discounts->count() > 0)
            <div class="space-y-3">
                @foreach($discounts as $discount)
                    @php
                        $isExpired = $discount->expires_at && $discount->expires_at < now();
                        $isUsed = $discount->status == 'used';
                        $isActive = $discount->status == 'active' && !$isExpired;
                    @endphp
                    <div class="glass-card">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="text-lg font-mono font-bold" style="color: #38bd55;">{{ $discount->code }}</p>
                                <p class="text-xs" style="color: rgba(255,255,255,0.4);">Redeemed on {{ $discount->created_at->format('M d, Y') }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($isActive) bg-green-500/20 text-green-400
                                @elseif($isUsed) bg-gray-500/20 text-gray-400
                                @elseif($isExpired) bg-red-500/20 text-red-400
                                @else bg-gray-500/20 text-gray-400
                                @endif">
                                @if($isActive)
                                    <i class="fas fa-check-circle mr-1"></i> Active
                                @elseif($isUsed)
                                    <i class="fas fa-check-double mr-1"></i> Used
                                @elseif($isExpired)
                                    <i class="fas fa-hourglass-end mr-1"></i> Expired
                                @else
                                    {{ ucfirst($discount->status) }}
                                @endif
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm mt-2">
                            <div>
                                <p class="text-xs" style="color: rgba(255,255,255,0.4);">Discount</p>
                                <p class="font-semibold" style="color: white;">₱{{ number_format($discount->discount_amount, 2) }} OFF</p>
                            </div>
                            <div>
                                <p class="text-xs" style="color: rgba(255,255,255,0.4);">Min. Spend</p>
                                <p class="font-semibold" style="color: white;">₱{{ number_format($discount->min_spend, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-xs" style="color: rgba(255,255,255,0.4);">Points Used</p>
                                <p class="font-semibold" style="color: #e07c34;">{{ number_format($discount->points_used) }} pts</p>
                            </div>
                            <div>
                                <p class="text-xs" style="color: rgba(255,255,255,0.4);">Valid Until</p>
                                <p class="font-semibold" style="color: white;">{{ $discount->expires_at ? $discount->expires_at->format('M d, Y') : 'No expiry' }}</p>
                            </div>
                        </div>
                        
                        @if($isUsed)
                            <div class="mt-3 pt-2 border-t" style="border-color: rgba(255,255,255,0.05);">
                                <div class="flex items-center gap-2 text-xs" style="color: #9ca3af;">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Used on {{ $discount->used_at ? $discount->used_at->format('M d, Y') : 'a previous order' }}</span>
                                </div>
                            </div>
                        @elseif($isExpired)
                            <div class="mt-3 pt-2 border-t" style="border-color: rgba(255,255,255,0.05);">
                                <div class="flex items-center gap-2 text-xs" style="color: #f87171;">
                                    <i class="fas fa-hourglass-end"></i>
                                    <span>Expired on {{ $discount->expires_at ? $discount->expires_at->format('M d, Y') : 'N/A' }}</span>
                                </div>
                            </div>
                        @elseif($isActive)
                            <div class="mt-3 pt-2 border-t" style="border-color: rgba(255,255,255,0.05);">
                                <div class="flex items-center gap-2 text-xs" style="color: #38bd55;">
                                    <i class="fas fa-clock"></i>
                                    <span>Valid until {{ $discount->expires_at ? $discount->expires_at->format('M d, Y') : 'No expiry' }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $discounts->links() }}
            </div>
        @else
            <div class="glass-card text-center py-8">
                <i class="fas fa-tag text-4xl mb-2" style="color: rgba(255,255,255,0.3);"></i>
                <p class="text-sm" style="color: rgba(255,255,255,0.5);">No discounts yet</p>
                <a href="{{ route('customer.points.index') }}" class="text-sm mt-2 inline-block" style="color: #38bd55;">Redeem points for discounts →</a>
            </div>
        @endif
        
    </div>
</div>

<style>
    .glass-card {
        background: #1e1e1e;
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,0.05);
        padding: 16px;
        transition: all 0.2s;
    }
    
    .glass-card:hover {
        border-color: rgba(56,189,85,0.3);
    }
</style>
@endsection