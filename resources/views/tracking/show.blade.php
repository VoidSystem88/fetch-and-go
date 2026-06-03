@extends('layouts.customer')

@section('content')
<div class="py-4">
    <div class="w-full px-4 max-w-4xl mx-auto">
        
        <div class="mb-4">
            <a href="{{ route('customer.order.details', $order) }}" class="text-green-400 text-sm">
                ← Back to Order Details
            </a>
        </div>
        
        <div class="glass-card mb-4">
            <div class="flex justify-between items-center mb-3">
                <h1 class="text-xl font-bold text-white">Track Your Delivery</h1>
                <span class="px-2 py-1 text-xs rounded-full status-{{ $order->status }}">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
            </div>
            
            <div class="text-sm text-gray-400 mb-3">
                <p>Order #{{ $order->id }}</p>
                <p>From: {{ $order->pickup_location }}</p>
                <p>To: {{ $order->dropoff_location }}</p>
            </div>
        </div>
        
        <!-- Map -->
        <div class="glass-card p-0 overflow-hidden">
            <div id="map" style="height: 400px; width: 100%;"></div>
        </div>
        
        <!-- Rider Info -->
        @if($rider)
        <div class="glass-card mt-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-green-600 flex items-center justify-center text-white font-bold text-lg">
                    {{ substr($rider->user->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-white font-semibold">{{ $rider->user->name }}</p>
                    <p class="text-xs text-gray-400">⭐ {{ number_format($rider->rating, 1) }} • {{ $rider->total_deliveries }} deliveries</p>
                </div>
                <div class="ml-auto">
                    <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400">
                        <i class="fas fa-circle text-xs mr-1 animate-pulse"></i> On Delivery
                    </span>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Progress Steps -->
        <div class="glass-card mt-4">
            <div class="flex justify-between">
                <div class="text-center flex-1">
                    <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center mb-1
                        {{ $order->created_at ? 'bg-green-500' : 'bg-gray-600' }}">
                        <i class="fas fa-box text-white text-xs"></i>
                    </div>
                    <p class="text-xs text-white">Order Created</p>
                    <p class="text-xs text-gray-500">{{ $order->created_at->format('M d') }}</p>
                </div>
                <div class="flex-1 h-0.5 self-center mx-1 {{ $order->assigned_at ? 'bg-green-500' : 'bg-gray-600' }}"></div>
                <div class="text-center flex-1">
                    <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center mb-1
                        {{ $order->assigned_at ? 'bg-green-500' : 'bg-gray-600' }}">
                        <i class="fas fa-motorcycle text-white text-xs"></i>
                    </div>
                    <p class="text-xs text-white">Rider Assigned</p>
                    <p class="text-xs text-gray-500">{{ $order->assigned_at ? $order->assigned_at->format('M d') : 'Pending' }}</p>
                </div>
                <div class="flex-1 h-0.5 self-center mx-1 {{ $order->picked_up_at ? 'bg-green-500' : 'bg-gray-600' }}"></div>
                <div class="text-center flex-1">
                    <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center mb-1
                        {{ $order->picked_up_at ? 'bg-green-500' : 'bg-gray-600' }}">
                        <i class="fas fa-box-open text-white text-xs"></i>
                    </div>
                    <p class="text-xs text-white">Picked Up</p>
                    <p class="text-xs text-gray-500">{{ $order->picked_up_at ? $order->picked_up_at->format('M d') : 'Pending' }}</p>
                </div>
                <div class="flex-1 h-0.5 self-center mx-1 {{ $order->delivered_at ? 'bg-green-500' : 'bg-gray-600' }}"></div>
                <div class="text-center flex-1">
                    <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center mb-1
                        {{ $order->delivered_at ? 'bg-green-500' : 'bg-gray-600' }}">
                        <i class="fas fa-flag-checkered text-white text-xs"></i>
                    </div>
                    <p class="text-xs text-white">Delivered</p>
                    <p class="text-xs text-gray-500">{{ $order->delivered_at ? $order->delivered_at->format('M d') : 'Pending' }}</p>
                </div>
            </div>
        </div>
        
    </div>
</div>

<style>
    .glass-card {
        background: #1e1e1e;
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,0.05);
        padding: 16px;
    }
    .status-pending { background: rgba(224,124,52,0.15); color: #e07c34; }
    .status-assigned { background: rgba(56,189,85,0.15); color: #38bd55; }
    .status-picked_up { background: rgba(56,189,85,0.15); color: #38bd55; }
    .status-delivered { background: rgba(56,189,85,0.2); color: #38bd55; }
    .animate-pulse {
        animation: pulse 1.5s ease-in-out infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }
</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    let map;
    let riderMarker;
    let routeControl;
    
    function initMap() {
        map = L.map('map').setView([14.5995, 120.9842], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        // Add pickup marker
        @if($order->pickup_lat && $order->pickup_lng)
            L.marker([{{ $order->pickup_lat }}, {{ $order->pickup_lng }}])
                .addTo(map)
                .bindPopup('Pickup Location')
                .openPopup();
        @endif
        
        // Add dropoff marker
        @if($order->dropoff_lat && $order->dropoff_lng)
            L.marker([{{ $order->dropoff_lat }}, {{ $order->dropoff_lng }}])
                .addTo(map)
                .bindPopup('Dropoff Location');
        @endif
        
        // Draw route between pickup and dropoff
        @if($order->pickup_lat && $order->pickup_lng && $order->dropoff_lat && $order->dropoff_lng)
            const pickup = [{{ $order->pickup_lat }}, {{ $order->pickup_lng }}];
            const dropoff = [{{ $order->dropoff_lat }}, {{ $order->dropoff_lng }}];
            
            // Fit bounds to show both markers
            map.fitBounds([pickup, dropoff]);
            
            // Get route from OSRM
            fetch(`https://router.project-osrm.org/route/v1/driving/{{ $order->pickup_lng }},{{ $order->pickup_lat }};{{ $order->dropoff_lng }},{{ $order->dropoff_lat }}?overview=full&geometries=geojson`)
                .then(response => response.json())
                .then(data => {
                    if (data.routes && data.routes.length > 0) {
                        const route = data.routes[0];
                        L.geoJSON(route.geometry, {
                            style: { color: '#38bd55', weight: 4 }
                        }).addTo(map);
                    }
                });
        @endif
        
        // Start tracking rider location
        @if($rider && ($order->status == 'assigned' || $order->status == 'picked_up'))
            startRiderTracking();
        @endif
    }
    
    function startRiderTracking() {
        setInterval(() => {
            fetch('{{ route("tracking.rider.location", $rider->id) }}')
                .then(response => response.json())
                .then(data => {
                    if (data.lat && data.lng) {
                        const riderLocation = [parseFloat(data.lat), parseFloat(data.lng)];
                        
                        if (!riderMarker) {
                            riderMarker = L.marker(riderLocation, {
                                icon: L.divIcon({
                                    html: '<div class="rider-marker">🛵</div>',
                                    className: 'custom-div-icon',
                                    iconSize: [30, 30]
                                })
                            }).addTo(map).bindPopup('Rider is here!').openPopup();
                        } else {
                            riderMarker.setLatLng(riderLocation);
                        }
                        
                        map.setView(riderLocation, 15);
                    }
                });
        }, 5000);
    }
    
    document.addEventListener('DOMContentLoaded', initMap);
</script>

<style>
    .rider-marker {
        background: #38bd55;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        box-shadow: 0 0 0 3px rgba(56,189,85,0.3);
        animation: bounce 0.5s ease infinite;
    }
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
</style>
@endsection