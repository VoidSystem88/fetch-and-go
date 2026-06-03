@extends('layouts.customer')

@section('content')
<div class="py-4">
    <div class="w-full px-4 max-w-2xl mx-auto">
        
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold" style="color: white;">My Points</h1>
            <p class="text-sm" style="color: rgba(255,255,255,0.5);">Earn points and redeem discounts</p>
        </div>

        <!-- Points Balance Card -->
        <div class="glass-card text-center mb-6">
            <i class="fas fa-coins text-3xl mb-2" style="color: #38bd55;"></i>
            <p class="text-xs" style="color: rgba(255,255,255,0.5);">Your Points Balance</p>
            <p class="text-4xl font-bold" style="color: #38bd55;">{{ number_format($user->points) }}</p>
            <p class="text-xs mt-2" style="color: rgba(255,255,255,0.4);">Total earned: {{ number_format($user->total_points_earned) }} | Spent: {{ number_format($user->points_spent) }}</p>
        </div>

        <!-- How to Earn Points -->
        <div class="glass-card mb-6">
            <h3 class="text-sm font-semibold mb-3" style="color: white;">How to Earn Points</h3>
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-xs" style="color: rgba(255,255,255,0.6);">Complete a delivery</span>
                    <span class="text-xs font-semibold" style="color: #38bd55;">+10 points</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs" style="color: rgba(255,255,255,0.6);">Rate your rider</span>
                    <span class="text-xs font-semibold" style="color: #38bd55;">+5 points</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs" style="color: rgba(255,255,255,0.6);">Refer a friend</span>
                    <span class="text-xs font-semibold" style="color: #38bd55;">+50 points</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs" style="color: rgba(255,255,255,0.6);">Birthday bonus</span>
                    <span class="text-xs font-semibold" style="color: #38bd55;">+20 points</span>
                </div>
            </div>
        </div>

        <!-- Redeem Rewards with Conditions -->
        <div class="glass-card mb-6">
            <h3 class="text-sm font-semibold mb-3" style="color: white;">Redeem Rewards</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-2 rounded-lg" style="background: rgba(56,189,85,0.05);">
                    <div>
                        <p class="text-sm font-semibold" style="color: white;">₱20 OFF</p>
                        <p class="text-xs" style="color: rgba(255,255,255,0.4);">100 points • Min. spend ₱100</p>
                    </div>
                    <form method="POST" action="{{ route('customer.points.redeem') }}" class="redeem-form">
                        @csrf
                        <input type="hidden" name="points" value="100">
                        <button type="submit" class="redeem-btn px-3 py-1 rounded-lg text-xs" style="background: #38bd55; color: white;" {{ $user->points < 100 ? 'disabled' : '' }} data-points="100" data-discount="20">Redeem</button>
                    </form>
                </div>
                <div class="flex justify-between items-center p-2 rounded-lg" style="background: rgba(56,189,85,0.05);">
                    <div>
                        <p class="text-sm font-semibold" style="color: white;">₱50 OFF</p>
                        <p class="text-xs" style="color: rgba(255,255,255,0.4);">250 points • Min. spend ₱250</p>
                    </div>
                    <form method="POST" action="{{ route('customer.points.redeem') }}" class="redeem-form">
                        @csrf
                        <input type="hidden" name="points" value="250">
                        <button type="submit" class="redeem-btn px-3 py-1 rounded-lg text-xs" style="background: #38bd55; color: white;" {{ $user->points < 250 ? 'disabled' : '' }} data-points="250" data-discount="50">Redeem</button>
                    </form>
                </div>
                <div class="flex justify-between items-center p-2 rounded-lg" style="background: rgba(56,189,85,0.05);">
                    <div>
                        <p class="text-sm font-semibold" style="color: white;">₱100 OFF</p>
                        <p class="text-xs" style="color: rgba(255,255,255,0.4);">500 points • Min. spend ₱500</p>
                    </div>
                    <form method="POST" action="{{ route('customer.points.redeem') }}" class="redeem-form">
                        @csrf
                        <input type="hidden" name="points" value="500">
                        <button type="submit" class="redeem-btn px-3 py-1 rounded-lg text-xs" style="background: #38bd55; color: white;" {{ $user->points < 500 ? 'disabled' : '' }} data-points="500" data-discount="100">Redeem</button>
                    </form>
                </div>
                <div class="flex justify-between items-center p-2 rounded-lg" style="background: rgba(56,189,85,0.05);">
                    <div>
                        <p class="text-sm font-semibold" style="color: white;">₱250 OFF + Free Delivery</p>
                        <p class="text-xs" style="color: rgba(255,255,255,0.4);">1000 points • Min. spend ₱1000</p>
                    </div>
                    <form method="POST" action="{{ route('customer.points.redeem') }}" class="redeem-form">
                        @csrf
                        <input type="hidden" name="points" value="1000">
                        <button type="submit" class="redeem-btn px-3 py-1 rounded-lg text-xs" style="background: #38bd55; color: white;" {{ $user->points < 1000 ? 'disabled' : '' }} data-points="1000" data-discount="250">Redeem</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- My Active Discounts -->
        @if(isset($activeDiscounts) && $activeDiscounts->count() > 0)
        <div class="glass-card mb-6">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-sm font-semibold" style="color: white;">My Active Discounts</h3>
                <a href="{{ route('customer.discounts.index') }}" class="text-xs" style="color: #38bd55;">View all →</a>
            </div>
            <div class="space-y-2">
                @foreach($activeDiscounts->take(3) as $discount)
                    <div class="flex justify-between items-center p-2 rounded-lg" style="background: rgba(56,189,85,0.08);">
                        <div class="flex-1">
                            <p class="text-sm font-mono" style="color: #38bd55;">{{ $discount->code }}</p>
                            <p class="text-xs" style="color: rgba(255,255,255,0.4);">
                                ₱{{ number_format($discount->discount_amount) }} OFF • Min. ₱{{ number_format($discount->min_spend) }}
                            </p>
                            <p class="text-xs" style="color: rgba(255,255,255,0.3);">Valid until {{ $discount->expires_at->format('M d, Y') }}</p>
                        </div>
                        <button onclick="copyToClipboard('{{ $discount->code }}')" class="px-2 py-1 rounded text-xs" style="background: rgba(56,189,85,0.2); color: #38bd55;">
                            Copy
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Points History -->
        <div class="glass-card">
            <h3 class="text-sm font-semibold mb-3" style="color: white;">Points History</h3>
            <div class="space-y-2">
                @forelse($pointsHistory as $history)
                    <div class="flex justify-between items-center p-2 border-b" style="border-color: rgba(255,255,255,0.05);">
                        <div>
                            <p class="text-xs" style="color: rgba(255,255,255,0.7);">
                                @if($history->reason == 'order_completed')
                                    <i class="fas fa-truck mr-1"></i> Order #{{ $history->order_id }} completed
                                @elseif($history->reason == 'rating')
                                    <i class="fas fa-star mr-1"></i> Rated a rider
                                @elseif($history->reason == 'discount_redeemed')
                                    <i class="fas fa-tag mr-1"></i> Redeemed {{ $history->points }} pts
                                @else
                                    {{ $history->reason }}
                                @endif
                            </p>
                            <p class="text-xs" style="color: rgba(255,255,255,0.3);">{{ $history->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div>
                            @if($history->type == 'earned')
                                <span class="text-xs font-semibold" style="color: #38bd55;">+{{ $history->points }}</span>
                            @else
                                <span class="text-xs font-semibold" style="color: #f87171;">-{{ $history->points }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-center py-4" style="color: rgba(255,255,255,0.5);">No points history yet</p>
                @endforelse
            </div>
            <div class="mt-3">
                {{ $pointsHistory->links() }}
            </div>
        </div>
        
        <div class="mt-4 text-center">
            <a href="{{ route('dashboard') }}" class="text-sm" style="color: rgba(255,255,255,0.5);">← Back to Dashboard</a>
        </div>
        
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black bg-opacity-70 z-50 hidden items-center justify-center">
    <div class="glass-card w-full max-w-md mx-4 text-center">
        <div class="mb-4">
            <i class="fas fa-check-circle text-5xl" style="color: #38bd55;"></i>
        </div>
        <h3 class="text-xl font-bold mb-2" style="color: white;">Success!</h3>
        <p id="modalMessage" class="text-sm" style="color: rgba(255,255,255,0.7);"></p>
        <div class="mt-4 pt-3">
            <button onclick="closeModal()" class="w-full py-2 rounded-lg text-sm font-semibold" style="background: linear-gradient(135deg, #38bd55, #2a9e46); color: white;">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div id="errorModal" class="fixed inset-0 bg-black bg-opacity-70 z-50 hidden items-center justify-center">
    <div class="glass-card w-full max-w-md mx-4 text-center">
        <div class="mb-4">
            <i class="fas fa-exclamation-circle text-5xl" style="color: #f87171;"></i>
        </div>
        <h3 class="text-xl font-bold mb-2" style="color: white;">Error ❌</h3>
        <p id="errorMessage" class="text-sm" style="color: rgba(255,255,255,0.7);"></p>
        <div class="mt-4 pt-3">
            <button onclick="closeErrorModal()" class="w-full py-2 rounded-lg text-sm font-semibold" style="background: #f87171; color: white;">
                Close
            </button>
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
    
    button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text);
    showModal('Discount code copied!', text);
}

function showModal(title, message) {
    document.getElementById('modalMessage').innerHTML = `<strong>${title}</strong><br>${message}`;
    document.getElementById('successModal').classList.remove('hidden');
    document.getElementById('successModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('successModal').classList.add('hidden');
    document.getElementById('successModal').classList.remove('flex');
}

function showError(message) {
    document.getElementById('errorMessage').innerHTML = message;
    document.getElementById('errorModal').classList.remove('hidden');
    document.getElementById('errorModal').classList.add('flex');
}

function closeErrorModal() {
    document.getElementById('errorModal').classList.add('hidden');
    document.getElementById('errorModal').classList.remove('flex');
}

// Intercept form submissions
document.querySelectorAll('.redeem-form').forEach(form => {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(form);
        const points = formData.get('points');
        let discountAmount = 0;
        
        if (points == 100) discountAmount = 20;
        else if (points == 250) discountAmount = 50;
        else if (points == 500) discountAmount = 100;
        else if (points == 1000) discountAmount = 250;
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                showModal(`₱${discountAmount} Discount Redeemed!`, 
                    `You redeemed ${points} points for ₱${discountAmount} discount!<br>
                     Discount code: <strong class="text-green-400">${data.code}</strong><br>
                     Min. spend: ₱${data.min_spend}<br>
                     Valid until: ${new Date(data.expires_at).toLocaleDateString()}`);
                
                // Update points balance on page
                setTimeout(() => location.reload(), 3000);
            } else {
                showError(data.error || 'Failed to redeem points');
            }
        } catch (error) {
            showError('An error occurred. Please try again.');
        }
    });
});
</script>
@endsection