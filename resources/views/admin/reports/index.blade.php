@extends('layouts.admin')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <h2 class="text-xl font-semibold text-gray-800">
                    <i class="fas fa-chart-line mr-2 text-blue-600"></i> Reports & Analytics
                </h2>
                <p class="text-sm text-gray-500">Platform performance and earnings</p>
            </div>
            <div class="p-6">
                
                <!-- Today's Stats -->
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Today's Statistics</h3>
                <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-6">
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ number_format($todayOrders) }}</div>
                        <div class="text-xs text-gray-500">Orders</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold text-green-600">{{ number_format($todayDeliveries) }}</div>
                        <div class="text-xs text-gray-500">Deliveries</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold text-yellow-600">₱{{ number_format($todayDeliveryFees, 2) }}</div>
                        <div class="text-xs text-gray-500">Total Fees</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold text-green-600">₱{{ number_format($todayRiderEarnings, 2) }}</div>
                        <div class="text-xs text-gray-500">Rider Earnings</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold text-orange-600">₱{{ number_format($todayAdminEarnings, 2) }}</div>
                        <div class="text-xs text-gray-500">Platform Fee</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold text-purple-600">₱{{ number_format($todayRevenue, 2) }}</div>
                        <div class="text-xs text-gray-500">Total Revenue</div>
                    </div>
                </div>

                <!-- This Week's Stats -->
                <h3 class="text-lg font-semibold text-gray-800 mb-3">This Week</h3>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-xl font-bold text-blue-600">{{ number_format($weekOrders) }}</div>
                        <div class="text-xs text-gray-500">Orders</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-xl font-bold text-yellow-600">₱{{ number_format($weekDeliveryFees, 2) }}</div>
                        <div class="text-xs text-gray-500">Total Fees</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-xl font-bold text-green-600">₱{{ number_format($weekRiderEarnings, 2) }}</div>
                        <div class="text-xs text-gray-500">Rider Earnings</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-xl font-bold text-orange-600">₱{{ number_format($weekAdminEarnings, 2) }}</div>
                        <div class="text-xs text-gray-500">Platform Fee</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-xl font-bold text-purple-600">₱{{ number_format($weekRevenue, 2) }}</div>
                        <div class="text-xs text-gray-500">Revenue</div>
                    </div>
                </div>

                <!-- This Month's Stats -->
                <h3 class="text-lg font-semibold text-gray-800 mb-3">This Month</h3>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-xl font-bold text-blue-600">{{ number_format($monthOrders) }}</div>
                        <div class="text-xs text-gray-500">Orders</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-xl font-bold text-yellow-600">₱{{ number_format($monthDeliveryFees, 2) }}</div>
                        <div class="text-xs text-gray-500">Total Fees</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-xl font-bold text-green-600">₱{{ number_format($monthRiderEarnings, 2) }}</div>
                        <div class="text-xs text-gray-500">Rider Earnings</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-xl font-bold text-orange-600">₱{{ number_format($monthAdminEarnings, 2) }}</div>
                        <div class="text-xs text-gray-500">Platform Fee</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-xl font-bold text-purple-600">₱{{ number_format($monthRevenue, 2) }}</div>
                        <div class="text-xs text-gray-500">Revenue</div>
                    </div>
                </div>

                <!-- Platform Totals -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white mb-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-sm opacity-80">Total Delivery Fees</p>
                            <p class="text-2xl font-bold">₱{{ number_format($totalDeliveryFees, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm opacity-80">Total Rider Earnings</p>
                            <p class="text-2xl font-bold">₱{{ number_format($totalRiderEarnings, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm opacity-80">Total Platform Earnings</p>
                            <p class="text-2xl font-bold">₱{{ number_format($totalAdminEarnings, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm opacity-80">Average Delivery Fee</p>
                            <p class="text-2xl font-bold">₱{{ number_format($averageDeliveryFee, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Top Riders -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-2 border-b">
                            <h4 class="font-semibold text-gray-800">🏆 Top Riders by Deliveries</h4>
                        </div>
                        <div class="divide-y">
                            @foreach($topRiders as $rider)
                            <div class="px-4 py-2 flex justify-between items-center">
                                <span>{{ $rider->user->name }}</span>
                                <span class="font-semibold text-blue-600">{{ $rider->total_deliveries }} deliveries</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="border rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-2 border-b">
                            <h4 class="font-semibold text-gray-800">💰 Top Riders by Earnings</h4>
                        </div>
                        <div class="divide-y">
                            @foreach($topEarningRiders as $rider)
                            <div class="px-4 py-2 flex justify-between items-center">
                                <span>{{ $rider->user->name }}</span>
                                <span class="font-semibold text-green-600">₱{{ number_format($rider->total_earnings, 2) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Orders by Status -->
                <div class="mt-6 border rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-2 border-b">
                        <h4 class="font-semibold text-gray-800">📋 Orders by Status</h4>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 p-4">
                        @foreach($ordersByStatus as $status => $count)
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="capitalize text-sm">{{ $status }}</span>
                            <span class="font-bold">{{ $count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection