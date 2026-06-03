@extends('layouts.customer')

@section('content')
<div class="py-4">
    <div class="w-full px-4">
        
        <div class="mb-5">
            <h1 class="text-xl font-bold" style="color: white; text-shadow: 0 1px 3px rgba(0,0,0,0.2);">My Orders</h1>
            <p style="color: rgba(255,255,255,0.7);">View all your delivery orders</p>
        </div>

        @if($orders->count() > 0)
            <div class="glass-table">
                <!-- Mobile Card View (para sa small screens) -->
                <div class="block md:hidden">
                    @foreach($orders as $order)
                        <div class="glass-card mb-3 p-3">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-sm font-mono" style="color: white;">#{{ $order->id }}</span>
                                <span class="px-2 py-1 text-xs rounded-full status-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div class="mb-2">
                                <p class="text-xs" style="color: rgba(255,255,255,0.5);">Pickup</p>
                                <p class="text-sm" style="color: rgba(255,255,255,0.9);">{{ Str::limit($order->pickup_location, 30) }}</p>
                            </div>
                            <div class="mb-2">
                                <p class="text-xs" style="color: rgba(255,255,255,0.5);">Dropoff</p>
                                <p class="text-sm" style="color: rgba(255,255,255,0.9);">{{ Str::limit($order->dropoff_location, 30) }}</p>
                            </div>
                            <div class="flex justify-between items-center mt-2 pt-2 border-t" style="border-color: rgba(255,255,255,0.1);">
                                <span class="text-xs" style="color: rgba(255,255,255,0.5);">{{ $order->created_at->format('M d, Y') }}</span>
                                <a href="{{ route('customer.order.details', $order) }}" class="text-sm" style="color: white;">View →</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase" style="color: rgba(255,255,255,0.8); width: 10%;">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase" style="color: rgba(255,255,255,0.8); width: 25%;">Pickup</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase" style="color: rgba(255,255,255,0.8); width: 25%;">Dropoff</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase" style="color: rgba(255,255,255,0.8); width: 15%;">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase" style="color: rgba(255,255,255,0.8); width: 15%;">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase" style="color: rgba(255,255,255,0.8); width: 10%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr class="hover-row">
                                    <td class="px-4 py-3 text-sm font-mono whitespace-nowrap" style="color: white;">#{{ $order->id }}</td>
                                    <td class="px-4 py-3 text-sm break-words" style="color: rgba(255,255,255,0.8); word-break: break-word;">{{ $order->pickup_location }}</td>
                                    <td class="px-4 py-3 text-sm break-words" style="color: rgba(255,255,255,0.8); word-break: break-word;">{{ $order->dropoff_location }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded-full status-{{ $order->status }} whitespace-nowrap">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm whitespace-nowrap" style="color: rgba(255,255,255,0.7);">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('customer.order.details', $order) }}" class="text-white hover:underline text-sm whitespace-nowrap">
                                            View →
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="px-4 py-3 border-t" style="border-color: rgba(255,255,255,0.1);">
                    {{ $orders->links() }}
                </div>
            </div>
        @else
            <div class="glass-card text-center py-8">
                <i class="fas fa-inbox text-4xl mb-2" style="color: rgba(255,255,255,0.5);"></i>
                <p style="color: rgba(255,255,255,0.7);">No orders yet</p>
                <a href="{{ route('orders.create') }}" class="text-white text-sm mt-2 inline-block underline">Create your first order →</a>
            </div>
        @endif
        
    </div>
</div>

<style>
    .glass-table {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(15px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        overflow: hidden;
    }
    
    .glass-table th {
        background: rgba(0, 0, 0, 0.2);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .glass-table td {
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        vertical-align: middle;
    }
    
    .hover-row:hover td {
        background: rgba(255, 255, 255, 0.1);
    }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(15px);
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 12px;
    }
    
    .status-pending {
        background: rgba(245, 158, 11, 0.3);
        backdrop-filter: blur(5px);
        color: #fef3c7;
    }
    
    .status-delivered {
        background: rgba(16, 185, 129, 0.3);
        backdrop-filter: blur(5px);
        color: #d1fae5;
    }
    
    .status-approved {
        background: rgba(59, 130, 246, 0.3);
        backdrop-filter: blur(5px);
        color: #dbeafe;
    }
    
    .status-cancelled {
        background: rgba(239, 68, 68, 0.3);
        backdrop-filter: blur(5px);
        color: #fee2e2;
    }
    
    .status-assigned {
        background: rgba(139, 92, 246, 0.3);
        backdrop-filter: blur(5px);
        color: #ede9fe;
    }
    
    /* Fix word break */
    .break-words {
        word-break: break-word;
        white-space: normal;
    }
    
    /* Pagination styles */
    .pagination .page-link {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        color: white;
    }
    
    .pagination .page-item.active .page-link {
        background: rgba(255,107,53,0.8);
        border-color: rgba(255,107,53,0.8);
    }
</style>
@endsection