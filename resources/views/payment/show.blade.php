@extends('layouts.customer')

@section('content')
<div class="py-4">
    <div class="w-full px-4 max-w-2xl mx-auto">
        
        <div class="mb-6">
            <h1 class="text-2xl font-bold" style="color: white;">Complete Payment</h1>
            <p class="text-sm" style="color: rgba(255,255,255,0.5);">Order #{{ $order->id }}</p>
        </div>

        <!-- Order Summary Card -->
        <div class="glass-card mb-6">
            <h3 class="text-sm font-semibold mb-3" style="color: white;">Order Summary</h3>
            
            <!-- Delivery Fee Breakdown -->
            @php
                $basePrice = 50;
                $distanceFee = ($order->distance_km ?? 0) * 15;
                $weightFee = ($order->estimated_weight_kg ?? 0) * 10;
                $sizeFee = ($order->estimated_size_cm ?? 0) * 0.5;
                $vehicleFees = ['motor' => 0, 'car' => 50, 'van' => 75, 'L300' => 80, 'truck' => 100];
                $vehicleFee = $vehicleFees[$order->required_vehicle_type] ?? 0;
                
                $subtotal = $basePrice + $distanceFee + $weightFee + $sizeFee + $vehicleFee;
                $discountAmount = $order->discount_amount ?? 0;
                $total = $subtotal - $discountAmount;
                $riderShare = $total * 0.7;
                $adminShare = $total * 0.3;
            @endphp
            
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span style="color: rgba(255,255,255,0.6);">Item Description</span>
                    <span style="color: white;" class="text-right">{{ Str::limit($order->item_description, 40) }}</span>
                </div>
                <div class="flex justify-between">
                    <span style="color: rgba(255,255,255,0.6);">Pickup Location</span>
                    <span style="color: white;" class="text-right">{{ Str::limit($order->pickup_location, 30) }}</span>
                </div>
                <div class="flex justify-between">
                    <span style="color: rgba(255,255,255,0.6);">Dropoff Location</span>
                    <span style="color: white;" class="text-right">{{ Str::limit($order->dropoff_location, 30) }}</span>
                </div>
                
                <div class="border-t pt-2 mt-2" style="border-color: rgba(255,255,255,0.1);"></div>
                
                <div class="flex justify-between text-sm">
                    <span style="color: rgba(255,255,255,0.5);">Base Price</span>
                    <span style="color: white;">₱{{ number_format($basePrice, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span style="color: rgba(255,255,255,0.5);">Distance Fee (₱15/km x {{ $order->distance_km ?? 0 }} km)</span>
                    <span style="color: white;">₱{{ number_format($distanceFee, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span style="color: rgba(255,255,255,0.5);">Weight Fee (₱10/kg x {{ $order->estimated_weight_kg ?? 0 }} kg)</span>
                    <span style="color: white;">₱{{ number_format($weightFee, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span style="color: rgba(255,255,255,0.5);">Size Fee (₱0.5/cm x {{ $order->estimated_size_cm ?? 0 }} cm)</span>
                    <span style="color: white;">₱{{ number_format($sizeFee, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span style="color: rgba(255,255,255,0.5);">Vehicle Fee</span>
                    <span style="color: white;">₱{{ number_format($vehicleFee, 2) }}</span>
                </div>
                
                <div class="border-t pt-2 mt-2" style="border-color: rgba(255,255,255,0.1);">
                    <div class="flex justify-between font-semibold">
                        <span style="color: white;">Subtotal</span>
                        <span style="color: white;">₱{{ number_format($subtotal, 2) }}</span>
                    </div>
                </div>

                <!-- Applied Discount Display -->
                <div id="discount_display" class="border-t pt-2" style="border-color: rgba(255,255,255,0.1); {{ $discountAmount > 0 ? '' : 'display: none;' }}">
                    <div class="flex justify-between">
                        <span style="color: #38bd55;">Discount Applied</span>
                        <span id="discount_amount_display" style="color: #38bd55;">-₱{{ number_format($discountAmount, 2) }}</span>
                    </div>
                </div>

                <!-- Final Total -->
                <div class="border-t pt-2 mt-2" style="border-color: rgba(255,255,255,0.1);">
                    <div class="flex justify-between font-bold">
                        <span style="color: white; font-size: 1.1rem;">TOTAL TO PAY</span>
                        <span id="final_total" style="color: #38bd55; font-size: 1.3rem;">₱{{ number_format($total, 2) }}</span>
                    </div>
                </div>
                
                <!-- DISCOUNT BUTTON - Opens Modal -->
                <div class="pt-3">
                    <button type="button" onclick="openDiscountModal()" class="w-full py-2 rounded-lg text-sm font-semibold" style="background: rgba(56,189,85,0.15); border: 1px solid rgba(56,189,85,0.3); color: #38bd55;">
                        <i class="fas fa-tag mr-2"></i> Apply Discount
                    </button>
                </div>
                
                <div class="flex justify-between text-xs pt-2">
                    <span style="color: rgba(255,255,255,0.4);">Rider earns (70%)</span>
                    <span id="rider_share" style="color: #38bd55;">₱{{ number_format($riderShare, 2) }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span style="color: rgba(255,255,255,0.4);">Platform fee (30%)</span>
                    <span id="admin_share" style="color: #e07c34;">₱{{ number_format($adminShare, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Options -->
        <div class="glass-card">
            <h3 class="text-sm font-semibold mb-3" style="color: white;">Select Payment Method</h3>
            <form method="POST" action="{{ route('payment.process', $order) }}" id="paymentForm">
                @csrf
                <input type="hidden" name="discount_id" id="selected_discount_id" value="{{ $order->discount_id ?? '' }}">
                
                <div class="space-y-3">
                    <label class="flex items-center justify-between p-3 rounded-lg cursor-pointer transition" style="background: rgba(56,189,85,0.05); border: 1px solid rgba(56,189,85,0.2);">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment_method" value="cash" required class="w-4 h-4 accent-green-500">
                            <div>
                                <p class="font-semibold" style="color: white;">Cash on Delivery</p>
                                <p class="text-xs" style="color: rgba(255,255,255,0.5);">Pay when you receive your package</p>
                            </div>
                        </div>
                        <i class="fas fa-money-bill-wave text-2xl" style="color: #38bd55;"></i>
                    </label>
                    
                    <label class="flex items-center justify-between p-3 rounded-lg cursor-pointer transition" style="background: rgba(56,189,85,0.05); border: 1px solid rgba(56,189,85,0.2);">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment_method" value="gcash" class="w-4 h-4 accent-green-500">
                            <div>
                                <p class="font-semibold" style="color: white;">GCash</p>
                                <p class="text-xs" style="color: rgba(255,255,255,0.5);">Pay via GCash</p>
                            </div>
                        </div>
                        <i class="fab fa-gcash text-2xl" style="color: #0078ff;"></i>
                    </label>
                    
                    <label class="flex items-center justify-between p-3 rounded-lg cursor-pointer transition" style="background: rgba(56,189,85,0.05); border: 1px solid rgba(56,189,85,0.2);">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment_method" value="paymaya" class="w-4 h-4 accent-green-500">
                            <div>
                                <p class="font-semibold" style="color: white;">PayMaya</p>
                                <p class="text-xs" style="color: rgba(255,255,255,0.5);">Pay via PayMaya</p>
                            </div>
                        </div>
                        <i class="fas fa-credit-card text-2xl" style="color: #0066b3;"></i>
                    </label>
                </div>
                
                <div class="flex gap-3 mt-6">
                    <a href="{{ route('orders.create') }}" class="flex-1 text-center py-2 rounded-lg" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; text-decoration: none;">Back</a>
                    <button type="submit" class="flex-1 py-2 rounded-lg font-semibold" style="background: linear-gradient(135deg, #38bd55, #2a9e46); color: white;">
                        Confirm Payment
                    </button>
                </div>
            </form>
        </div>
        
    </div>
</div>

<!-- Discount Modal -->
<div id="discountModal" class="fixed inset-0 bg-black bg-opacity-70 z-50 hidden items-center justify-center">
    <div class="glass-card w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold" style="color: white;">My Available Discounts</h3>
            <button onclick="closeDiscountModal()" class="text-gray-400 hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div id="discounts_list" class="space-y-3 max-h-96 overflow-y-auto">
            <div class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-2xl" style="color: #38bd55;"></i>
                <p class="text-sm mt-2" style="color: rgba(255,255,255,0.5);">Loading discounts...</p>
            </div>
        </div>
        
        <div class="mt-4 pt-3 border-t" style="border-color: rgba(255,255,255,0.1);">
            <button onclick="closeDiscountModal()" class="w-full py-2 rounded-lg text-sm" style="background: rgba(255,255,255,0.1); color: white;">Close</button>
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
    input[type="radio"]:checked {
        accent-color: #38bd55;
    }
    .discount-item {
        transition: all 0.2s;
    }
    .discount-item:hover {
        background: rgba(56,189,85,0.1);
        transform: translateX(4px);
    }
</style>

<script>
let subtotal = {{ $subtotal }};
let currentDiscount = {{ $discountAmount }};
let currentTotal = {{ $total }};

function openDiscountModal() {
    document.getElementById('discountModal').classList.remove('hidden');
    document.getElementById('discountModal').classList.add('flex');
    loadDiscounts();
}

function closeDiscountModal() {
    document.getElementById('discountModal').classList.add('hidden');
    document.getElementById('discountModal').classList.remove('flex');
}

function loadDiscounts() {
    fetch('{{ route("customer.discounts.available") }}')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('discounts_list');
            if (data.discounts && data.discounts.length > 0) {
                container.innerHTML = data.discounts.map(discount => `
                    <div class="discount-item p-3 rounded-lg cursor-pointer" style="background: rgba(56,189,85,0.05); border: 1px solid rgba(56,189,85,0.15);" onclick="applyDiscountFromModal(${discount.id}, ${discount.discount_amount})">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-bold" style="color: #38bd55;">₱${discount.discount_amount} OFF</p>
                                <p class="text-xs" style="color: rgba(255,255,255,0.5);">Min. spend ₱${discount.min_spend}</p>
                                <p class="text-xs" style="color: rgba(255,255,255,0.3);">Valid until ${new Date(discount.expires_at).toLocaleDateString()}</p>
                            </div>
                            <button class="px-3 py-1 rounded-lg text-xs" style="background: #38bd55; color: white;">Apply</button>
                        </div>
                    </div>
                `).join('');
            } else {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-tag text-4xl mb-2" style="color: rgba(255,255,255,0.3);"></i>
                        <p class="text-sm" style="color: rgba(255,255,255,0.5);">No available discounts</p>
                        <a href="{{ route('customer.points.index') }}" class="text-sm mt-2 inline-block" style="color: #38bd55;">Redeem points for discounts →</a>
                    </div>
                `;
            }
        })
        .catch(error => {
            document.getElementById('discounts_list').innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-circle text-4xl mb-2" style="color: #f87171;"></i>
                    <p class="text-sm" style="color: rgba(255,255,255,0.5);">Failed to load discounts</p>
                </div>
            `;
        });
}

function applyDiscountFromModal(discountId, discountAmount) {
    fetch('{{ route("payment.apply.discount") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ 
            order_id: {{ $order->id }}, 
            discount_id: discountId 
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentDiscount = data.discount_amount;
            currentTotal = data.new_total;
            
            // Update display
            document.getElementById('final_total').innerHTML = '₱' + currentTotal.toFixed(2);
            document.getElementById('discount_amount_display').innerHTML = '-₱' + currentDiscount.toFixed(2);
            document.getElementById('discount_display').style.display = 'block';
            document.getElementById('selected_discount_id').value = discountId;
            
            // Update rider and admin shares
            let riderShare = currentTotal * 0.7;
            let adminShare = currentTotal * 0.3;
            document.getElementById('rider_share').innerHTML = '₱' + riderShare.toFixed(2);
            document.getElementById('admin_share').innerHTML = '₱' + adminShare.toFixed(2);
            
            closeDiscountModal();
            
            // Show success message
            const messageDiv = document.createElement('div');
            messageDiv.className = 'glass-card mb-4';
            messageDiv.style.background = 'rgba(56,189,85,0.1)';
            messageDiv.style.borderColor = 'rgba(56,189,85,0.3)';
            messageDiv.innerHTML = '<i class="fas fa-check-circle" style="color: #38bd55;"></i> Discount applied! You saved ₱' + data.discount_amount;
            document.querySelector('.glass-card').insertBefore(messageDiv, document.querySelector('.glass-card').firstChild);
            
            setTimeout(() => messageDiv.remove(), 3000);
        } else {
            alert(data.message || 'Failed to apply discount');
        }
    })
    .catch(error => {
        alert('Error applying discount');
    });
}
</script>
@endsection