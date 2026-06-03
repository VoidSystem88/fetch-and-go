@extends('layouts.admin')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <h2 class="text-xl font-semibold text-gray-800">
                    <i class="fas fa-chart-pie mr-2 text-blue-600"></i> Earnings Report
                </h2>
                <p class="text-sm text-gray-500">Platform earnings and payout summary</p>
            </div>
            <div class="p-6">
                
                <!-- Date Filter -->
                <div class="mb-6 flex justify-between items-center">
                    <div class="flex gap-4">
                        <div>
                            <label class="text-xs text-gray-500">Start Date</label>
                            <input type="date" id="start_date" value="{{ $startDate->format('Y-m-d') }}" class="border rounded px-2 py-1 text-sm">
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">End Date</label>
                            <input type="date" id="end_date" value="{{ $endDate->format('Y-m-d') }}" class="border rounded px-2 py-1 text-sm">
                        </div>
                        <button onclick="filterEarnings()" class="bg-blue-600 text-white px-4 py-1 rounded text-sm">Filter</button>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Total Collected</p>
                        <p class="text-2xl font-bold text-green-600">₱{{ number_format($totalEarnings, 2) }}</p>
                    </div>
                </div>

                <!-- Summary Cards -->
                @if(isset($deliveryEarnings))
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-4 text-white">
                        <p class="text-sm opacity-90">Total Delivery Fees</p>
                        <p class="text-2xl font-bold">₱{{ number_format($deliveryEarnings->total_fees ?? 0, 2) }}</p>
                    </div>
                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                        <p class="text-sm opacity-90">Rider Earnings (70%)</p>
                        <p class="text-2xl font-bold">₱{{ number_format($deliveryEarnings->rider_earnings ?? 0, 2) }}</p>
                    </div>
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                        <p class="text-sm opacity-90">Platform Fee (30%)</p>
                        <p class="text-2xl font-bold">₱{{ number_format($deliveryEarnings->admin_earnings ?? 0, 2) }}</p>
                    </div>
                </div>
                @endif

                <!-- Rider Earnings Summary -->
                @if(isset($riderEarningsSummary) && $riderEarningsSummary->count() > 0)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">💰 Top Earning Riders</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border rounded-lg">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Rider</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Total Deliveries</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Total Earnings</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Rating</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($riderEarningsSummary as $rider)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-sm">{{ $rider->user->name }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $rider->total_deliveries }}</td>
                                    <td class="px-4 py-2 text-sm font-semibold text-green-600">₱{{ number_format($rider->total_earnings, 2) }}</td>
                                    <td class="px-4 py-2 text-sm">⭐ {{ number_format($rider->rating, 1) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Payment Transactions -->
                <h3 class="text-lg font-semibold text-gray-800 mb-3">📋 Payment Transactions</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border rounded-lg">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">ID</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Order</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Customer</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Rider</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Amount</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Status</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($payments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 text-sm">#{{ $payment->id }}</td>
                                <td class="px-4 py-2 text-sm">#{{ $payment->order_id }}</td>
                                <td class="px-4 py-2 text-sm">{{ $payment->customer->name }}</td>
                                <td class="px-4 py-2 text-sm">{{ $payment->rider->user->name ?? 'N/A' }}</td>
                                <td class="px-4 py-2 text-sm font-semibold text-green-600">₱{{ number_format($payment->amount, 2) }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($payment->status == 'paid') bg-green-100 text-green-700
                                        @else bg-yellow-100 text-yellow-700
                                        @endif">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-sm">{{ $payment->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">No payment records found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $payments->links() }}
                </div>
                
            </div>
        </div>
    </div>
</div>

<script>
function filterEarnings() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    window.location.href = '{{ route("admin.earnings") }}?start_date=' + startDate + '&end_date=' + endDate;
}
</script>
@endsection