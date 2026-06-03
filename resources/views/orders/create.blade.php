@extends('layouts.customer')

@section('content')
<div class="py-4">
    <div class="w-full px-4 max-w-2xl mx-auto">
        
        <div class="mb-6">
            <h1 class="text-2xl font-bold" style="color: white;">Create New Order</h1>
            <p class="text-sm" style="color: rgba(255,255,255,0.5);">Fill out the form below</p>
        </div>

        <div class="glass-card">
            <form method="POST" action="{{ route('orders.store') }}" id="orderForm">
                @csrf
                
                <!-- Pickup Location -->
                <div class="mb-4">
                    <label class="block text-xs mb-1" style="color: rgba(255,255,255,0.7);">Pickup Location</label>
                    <input type="text" name="pickup_location" id="pickup_location" required 
                           class="w-full px-3 py-2 rounded-lg" 
                           style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
                    <button type="button" onclick="getCurrentLocation('pickup')" 
                            class="mt-1 text-xs text-green-400 hover:text-green-300">
                        📍 Use my current location
                    </button>
                </div>
                
                <!-- Dropoff Location -->
                <div class="mb-4">
                    <label class="block text-xs mb-1" style="color: rgba(255,255,255,0.7);">Dropoff Location</label>
                    <input type="text" name="dropoff_location" id="dropoff_location" required 
                           class="w-full px-3 py-2 rounded-lg" 
                           style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
                </div>
                
                <!-- Map Container - Fixed height with proper z-index -->
                <div class="mb-4" style="position: relative; z-index: 1;">
                    <div id="map" style="height: 300px; width: 100%; border-radius: 12px; overflow: hidden; z-index: 1;"></div>
                </div>
                
                <!-- Item Description -->
                <div class="mb-4">
                    <label class="block text-xs mb-1" style="color: rgba(255,255,255,0.7);">Item Description</label>
                    <textarea name="item_description" required rows="2" 
                              class="w-full px-3 py-2 rounded-lg" 
                              style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;"></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <label class="block text-xs mb-1" style="color: rgba(255,255,255,0.7);">Weight (kg)</label>
                        <input type="number" name="estimated_weight_kg" id="weight" step="0.1" 
                               class="w-full px-3 py-2 rounded-lg" 
                               style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
                    </div>
                    <div>
                        <label class="block text-xs mb-1" style="color: rgba(255,255,255,0.7);">Size (cm)</label>
                        <input type="number" name="estimated_size_cm" id="size" step="0.1" 
                               class="w-full px-3 py-2 rounded-lg" 
                               style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs mb-1" style="color: rgba(255,255,255,0.7);">Vehicle Type</label>
                    <select name="required_vehicle_type" id="vehicle_type" required 
                            class="w-full px-3 py-2 rounded-lg" 
                            style="background: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: white;">
                        <option value="motor">Motorcycle</option>
                        <option value="car">Car</option>
                        <option value="van">Van</option>
                        <option value="L300">L300</option>
                        <option value="truck">Truck</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs mb-1" style="color: rgba(255,255,255,0.7);">Delivery Instructions</label>
                    <textarea name="delivery_instructions" rows="2" 
                              class="w-full px-3 py-2 rounded-lg" 
                              style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;" 
                              placeholder="Special instructions for the rider..."></textarea>
                </div>

                <!-- Delivery Fee Display -->
                <div class="mb-4 p-3 rounded-lg" style="background: rgba(56,189,85,0.1); border: 1px solid rgba(56,189,85,0.2);">
                    <div class="flex justify-between items-center">
                        <span class="text-sm" style="color: rgba(255,255,255,0.7);">Distance:</span>
                        <span id="distance_display" class="text-sm text-white">-- km</span>
                    </div>
                    <div class="flex justify-between items-center mt-1">
                        <span class="text-sm" style="color: rgba(255,255,255,0.7);">Estimated Delivery Fee:</span>
                        <span id="fee_display" class="text-lg font-bold text-green-500">₱--</span>
                    </div>
                </div>

                <!-- Hidden fields -->
                <input type="hidden" name="distance_km" id="distance_km" value="">
                <input type="hidden" name="calculated_fee" id="calculated_fee" value="">
                <input type="hidden" name="rider_earnings" id="rider_earnings" value="">
                <input type="hidden" name="admin_earnings" id="admin_earnings" value="">
                
                <div class="flex gap-3">
                    <a href="{{ route('dashboard') }}" class="flex-1 text-center py-2 rounded-lg" 
                       style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; text-decoration: none;">Cancel</a>
                    <button type="submit" class="flex-1 py-2 rounded-lg font-semibold" 
                            style="background: linear-gradient(135deg, #38bd55, #2a9e46); color: white; border: none; cursor: pointer;">
                        Continue to Payment
                    </button>
                </div>
            </form>
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
    input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: #38bd55 !important;
    }
    select option {
        background: #2a2a2a;
        color: white;
    }
    
    /* Fix map position */
    #map {
        position: relative;
        z-index: 1;
    }
    .leaflet-control-container {
        z-index: 1;
    }
    .leaflet-top {
        z-index: 1;
    }
    .leaflet-bottom {
        z-index: 1;
    }
</style>

<!-- Leaflet CSS + JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<script>
    let map;
    let pickupMarker = null;
    let dropoffMarker = null;
    let routeLayer = null;
    let currentDistance = 0; // Store current distance globally
    
    const vehicleFees = {
        'motor': 0,
        'car': 50,
        'van': 75,
        'L300': 80,
        'truck': 100
    };
    
    function initMap() {
        const center = [14.5995, 120.9842];
        
        map = L.map('map').setView(center, 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        // Add geocoder
        const geocoder = L.Control.geocoder({
            defaultMarkGeocode: false,
            placeholder: 'Search location...'
        }).on('markgeocode', function(e) {
            const center = e.geocode.center;
            map.setView(center, 15);
            
            if (document.activeElement.id === 'pickup_location') {
                document.getElementById('pickup_location').value = e.geocode.name;
                if (pickupMarker) map.removeLayer(pickupMarker);
                pickupMarker = L.marker(center).addTo(map).bindPopup('Pickup').openPopup();
                calculateRoute();
            } else if (document.activeElement.id === 'dropoff_location') {
                document.getElementById('dropoff_location').value = e.geocode.name;
                if (dropoffMarker) map.removeLayer(dropoffMarker);
                dropoffMarker = L.marker(center).addTo(map).bindPopup('Dropoff').openPopup();
                calculateRoute();
            }
        }).addTo(map);
        
        // Add event listeners - FIXED: Use currentDistance
        document.getElementById('pickup_location').addEventListener('change', calculateRoute);
        document.getElementById('dropoff_location').addEventListener('change', calculateRoute);
        document.getElementById('weight').addEventListener('input', function() {
            if (currentDistance > 0) {
                calculateFee(currentDistance);
            } else {
                updateFeeWithCurrentValues();
            }
        });
        document.getElementById('size').addEventListener('input', function() {
            if (currentDistance > 0) {
                calculateFee(currentDistance);
            } else {
                updateFeeWithCurrentValues();
            }
        });
        document.getElementById('vehicle_type').addEventListener('change', function() {
            if (currentDistance > 0) {
                calculateFee(currentDistance);
            } else {
                updateFeeWithCurrentValues();
            }
        });
    }
    
    function updateFeeWithCurrentValues() {
        // Use default distance if no route yet
        let distance = currentDistance > 0 ? currentDistance : 5; // Default 5km
        calculateFee(distance);
    }
    
    async function geocodeAddress(address) {
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1`;
        try {
            const response = await fetch(url, {
                headers: {
                    'User-Agent': 'FetchAndGoApp/1.0'
                }
            });
            const data = await response.json();
            if (data && data.length > 0) {
                return { lat: parseFloat(data[0].lat), lng: parseFloat(data[0].lon), name: data[0].display_name };
            }
        } catch (error) {
            console.error('Geocoding error:', error);
        }
        return null;
    }
    
    async function calculateRoute() {
        const pickupAddr = document.getElementById('pickup_location').value;
        const dropoffAddr = document.getElementById('dropoff_location').value;
        
        if (!pickupAddr || !dropoffAddr) return;
        
        // Show loading
        document.getElementById('distance_display').innerHTML = 'Calculating...';
        
        const pickup = await geocodeAddress(pickupAddr);
        const dropoff = await geocodeAddress(dropoffAddr);
        
        if (pickup && dropoff) {
            // Update markers
            if (pickupMarker) map.removeLayer(pickupMarker);
            pickupMarker = L.marker([pickup.lat, pickup.lng]).addTo(map).bindPopup('Pickup');
            
            if (dropoffMarker) map.removeLayer(dropoffMarker);
            dropoffMarker = L.marker([dropoff.lat, dropoff.lng]).addTo(map).bindPopup('Dropoff');
            
            map.fitBounds([[pickup.lat, pickup.lng], [dropoff.lat, dropoff.lng]]);
            
            // Get route
            const routeUrl = `https://router.project-osrm.org/route/v1/driving/${pickup.lng},${pickup.lat};${dropoff.lng},${dropoff.lat}?overview=full&geometries=geojson`;
            
            try {
                const response = await fetch(routeUrl);
                const data = await response.json();
                
                if (data.routes && data.routes.length > 0) {
                    if (routeLayer) map.removeLayer(routeLayer);
                    routeLayer = L.geoJSON(data.routes[0].geometry, {
                        style: { color: '#38bd55', weight: 4 }
                    }).addTo(map);
                    
                    currentDistance = data.routes[0].distance / 1000;
                    document.getElementById('distance_display').innerHTML = currentDistance.toFixed(1) + ' km';
                    document.getElementById('distance_km').value = currentDistance.toFixed(2);
                    
                    calculateFee(currentDistance);
                }
            } catch (error) {
                console.error('Route error:', error);
                document.getElementById('distance_display').innerHTML = '-- km';
            }
        }
    }
    
    function calculateFee(distance) {
        let weight = parseFloat(document.getElementById('weight').value) || 0;
        let size = parseFloat(document.getElementById('size').value) || 0;
        let vehicle = document.getElementById('vehicle_type').value;
        
        let basePrice = 50;
        let distanceFee = distance * 15;
        let weightFee = weight * 10;
        let sizeFee = size * 0.5;
        let vehicleFee = vehicleFees[vehicle] || 0;
        
        let total = basePrice + distanceFee + weightFee + sizeFee + vehicleFee;
        let riderShare = total * 0.7;
        let adminShare = total * 0.3;
        
        document.getElementById('fee_display').innerHTML = '₱' + total.toFixed(2);
        document.getElementById('calculated_fee').value = total.toFixed(2);
        document.getElementById('rider_earnings').value = riderShare.toFixed(2);
        document.getElementById('admin_earnings').value = adminShare.toFixed(2);
        
        // Log for debugging
        console.log(`Distance: ${distance}km, Weight: ${weight}kg, Size: ${size}cm, Vehicle: ${vehicle}`);
        console.log(`Total: ₱${total.toFixed(2)} (Base: ₱${basePrice}, Distance: ₱${distanceFee.toFixed(2)})`);
    }
    
    function getCurrentLocation(type) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(async (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`;
                try {
                    const response = await fetch(url, {
                        headers: {
                            'User-Agent': 'FetchAndGoApp/1.0'
                        }
                    });
                    const data = await response.json();
                    if (data && data.display_name) {
                        if (type === 'pickup') {
                            document.getElementById('pickup_location').value = data.display_name;
                            if (pickupMarker) map.removeLayer(pickupMarker);
                            pickupMarker = L.marker([lat, lng]).addTo(map).bindPopup('Pickup').openPopup();
                            map.setView([lat, lng], 15);
                            calculateRoute();
                        }
                    }
                } catch (error) {
                    console.error('Reverse geocoding error:', error);
                }
            });
        } else {
            alert('Geolocation is not supported');
        }
    }
    
    // Initialize map and set default fee
    document.addEventListener('DOMContentLoaded', function() {
        initMap();
        // Set initial default fee
        setTimeout(function() {
            if (currentDistance === 0) {
                calculateFee(5); // Default 5km
            }
        }, 500);
    });
</script>
@endsection