<!-- Welcome Points Modal -->
@if(isset($showWelcomeModal) && $showWelcomeModal)
<div id="welcomeModal" class="fixed inset-0 z-50 flex items-center justify-center p-5" style="background: rgba(0,0,0,0.8);">
    <div class="bg-gray-800 rounded-box max-w-[320px] w-full mx-auto shadow-xl overflow-hidden" style="border-radius: 12px; border: 1px solid rgba(255,255,255,0.1);">
        
        <!-- Header with Sunset -->
        <div class="px-4 py-2.5" style="background: linear-gradient(135deg, #FF6B35, #F7931E, #FFD166); border-radius: 12px 12px 0 0;">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <i class="fas fa-gift text-white text-sm"></i>
                    <span class="text-white text-sm font-semibold">Welcome Offer</span>
                </div>
                <button onclick="closeWelcomeModal()" class="text-white/80 hover:text-white">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
        
        <!-- Body -->
        <div class="p-4 bg-gray-800">
            <!-- Icon -->
            <div class="text-center mb-3">
                <div class="w-12 h-12 mx-auto bg-gray-700 rounded-full flex items-center justify-center mb-2">
                    <i class="fas fa-gift text-xl" style="color: #FF8C42;"></i>
                </div>
                <h2 class="text-lg font-bold" style="background: linear-gradient(135deg, #FF6B35, #F7931E, #FFD166); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Welcome!</h2>
                <p class="text-gray-400 text-xs mt-1">Thanks for joining Fetch and Go</p>
            </div>
            
            <!-- Points Box -->
            <div class="bg-gray-700 rounded-lg p-3 mb-3 text-center">
                <div class="flex items-center justify-center gap-1 mb-1">
                    <i class="fas fa-coins text-yellow-500 text-sm"></i>
                    <span class="text-2xl font-bold" style="background: linear-gradient(135deg, #FF6B35, #F7931E, #FFD166); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">500</span>
                    <span class="text-gray-400 text-xs">Points</span>
                </div>
                <p class="text-gray-300 text-xs">You've received <strong class="text-orange-400">500 bonus points</strong> as a welcome gift!</p>
            </div>
            
            <!-- Button -->
            <button onclick="claimWelcomePoints()" class="w-full py-2 text-sm font-medium rounded-lg transition text-white" style="background: linear-gradient(135deg, #FF6B35, #F7931E, #FFD166);">
                <i class="fas fa-gift mr-1 text-xs"></i> Claim My Points
            </button>
            
            <!-- Skip -->
            <div class="text-center mt-2">
                <button onclick="closeWelcomeModal()" class="text-gray-500 hover:text-gray-400 text-xs">
                    No thanks
                </button>
            </div>
        </div>
        
        <!-- Bottom Border -->
        <div class="h-1" style="background: linear-gradient(90deg, #FF6B35, #F7931E, #FFD166); border-radius: 0 0 12px 12px;"></div>
    </div>
</div>

<style>
    /* Extra small screens */
    @media (max-width: 480px) {
        .max-w-\[320px\] {
            max-width: 280px !important;
        }
    }
    
    /* Rounded box class */
    .rounded-box {
        border-radius: 12px;
    }
</style>

<script>
function claimWelcomePoints() {
    fetch('{{ route("customer.claim.welcome") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('welcomeModal').innerHTML = `
                <div class="fixed inset-0 z-50 flex items-center justify-center p-5" style="background: rgba(0,0,0,0.8);">
                    <div class="bg-gray-800 rounded-box max-w-[320px] w-full mx-auto shadow-xl overflow-hidden" style="border-radius: 12px; border: 1px solid rgba(255,255,255,0.1);">
                        <div class="px-4 py-2.5" style="background: linear-gradient(135deg, #FF6B35, #F7931E, #FFD166); border-radius: 12px 12px 0 0;">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-check-circle text-white text-sm"></i>
                                    <span class="text-white text-sm font-semibold">Success!</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 bg-gray-800 text-center">
                            <div class="w-12 h-12 mx-auto bg-gray-700 rounded-full flex items-center justify-center mb-2">
                                <i class="fas fa-check text-xl text-green-500"></i>
                            </div>
                            <h2 class="text-lg font-bold text-white mb-1">Points Added! 🎉</h2>
                            <p class="text-gray-400 text-xs mb-3">You've successfully claimed your 500 welcome points!</p>
                            <div class="bg-gray-700 rounded-lg p-2 mb-3">
                                <div class="flex items-center justify-center gap-1">
                                    <i class="fas fa-coins text-yellow-500 text-sm"></i>
                                    <span class="text-xl font-bold text-white">500</span>
                                    <span class="text-gray-400 text-xs">Points Added</span>
                                </div>
                            </div>
                            <button onclick="location.reload()" class="w-full py-2 text-sm font-medium rounded-lg transition text-white" style="background: linear-gradient(135deg, #FF6B35, #F7931E, #FFD166);">
                                <i class="fas fa-shopping-cart mr-1 text-xs"></i> Start Ordering
                            </button>
                        </div>
                        <div class="h-1" style="background: linear-gradient(90deg, #FF6B35, #F7931E, #FFD166); border-radius: 0 0 12px 12px;"></div>
                    </div>
                </div>
            `;
        } else {
            alert('Failed to claim points. Please try again.');
        }
    })
    .catch(error => {
        alert('An error occurred. Please refresh the page.');
    });
}

function closeWelcomeModal() {
    document.getElementById('welcomeModal').style.display = 'none';
}
</script>
@endif