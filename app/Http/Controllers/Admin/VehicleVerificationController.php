<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RiderVehicle;

class VehicleVerificationController extends Controller
{
    // Remove __construct
    
    public function index()
    {
        $pendingVehicles = RiderVehicle::where('is_active', false)
            ->with('rider.user')
            ->get();
            
        $verifiedVehicles = RiderVehicle::where('is_active', true)
            ->with('rider.user', 'verifier')
            ->latest()
            ->paginate(20);
            
        return view('admin.vehicles.index', compact('pendingVehicles', 'verifiedVehicles'));
    }
    
    public function verify(RiderVehicle $vehicle)
    {
        $vehicle->verify(auth()->id());
        
        return back()->with('success', 'Vehicle verified successfully!');
    }
    
    public function reject(RiderVehicle $vehicle)
    {
        $vehicle->delete();
        
        return back()->with('success', 'Vehicle registration rejected and deleted.');
    }
}