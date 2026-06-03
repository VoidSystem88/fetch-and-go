<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Rider;
use App\Models\User;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // I-redirect based on role
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isStaff()) {
            return $this->staffDashboard();
        } elseif ($user->isRider()) {
            return $this->riderDashboard();
        } else {
            return $this->customerDashboard();
        }
    }
    
    private function adminDashboard()
    {
        $totalUsers = User::count();
        $totalRiders = Rider::count();
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', Order::STATUS_PENDING)->count();
        $completedOrders = Order::where('status', Order::STATUS_DELIVERED)->count();
        $totalRevenue = Payment::where('status', 'paid')->sum('amount');
        
        $recentOrders = Order::with(['customer', 'assignedRider.user'])
            ->latest()
            ->take(10)
            ->get();
        
        $recentRiders = Rider::with('user')
            ->latest()
            ->take(5)
            ->get();
        
        return view('dashboard.admin', compact(
            'totalUsers', 'totalRiders', 'totalOrders', 
            'pendingOrders', 'completedOrders', 'totalRevenue',
            'recentOrders', 'recentRiders'
        ));
    }
    
    private function staffDashboard()
    {
        // Pending orders (need to be sent to rider)
        $pendingOrders = Order::where('status', Order::STATUS_PENDING)
            ->with('customer')
            ->latest()
            ->get();
        
        // Sent to riders (waiting for rider response)
        $sentOrders = Order::where('status', Order::STATUS_SENT)
            ->with('customer', 'assignedRider.user')
            ->latest()
            ->get();
        
        // Accepted by riders (waiting for staff approval)
        $acceptedOrders = Order::where('status', Order::STATUS_ACCEPTED)
            ->with('customer', 'assignedRider.user')
            ->latest()
            ->get();
        
        // Approved orders (ready for assignment - old workflow)
        $approvedOrders = Order::where('status', Order::STATUS_APPROVED)
            ->with('customer')
            ->latest()
            ->get();
        
        // Available riders
        $availableRiders = Rider::where('is_available', true)
            ->with('user', 'vehicle')
            ->get();
        
        // Today's assigned deliveries
        $todayAssignments = Order::where('status', Order::STATUS_ASSIGNED)
            ->whereDate('assigned_at', today())
            ->count();
        
        return view('dashboard.staff', compact(
            'pendingOrders', 'sentOrders', 'acceptedOrders', 'approvedOrders', 
            'availableRiders', 'todayAssignments'
        ));
    }
    
    private function riderDashboard()
    {
        $rider = auth()->user()->rider;
        
        // Orders sent to this rider (waiting for accept/decline)
        $pendingOffers = Order::where('assigned_rider_id', $rider->id)
            ->where('status', Order::STATUS_SENT)
            ->where('timeout_expires_at', '>', now())
            ->with('customer')
            ->get();
        
        // Current assigned orders (accepted and approved)
        $currentOrders = Order::where('assigned_rider_id', $rider->id)
            ->whereIn('status', [Order::STATUS_ASSIGNED, Order::STATUS_PICKED_UP])
            ->with('customer')
            ->latest()
            ->get();
        
        // Completed deliveries today
        $completedToday = Order::where('assigned_rider_id', $rider->id)
            ->where('status', Order::STATUS_DELIVERED)
            ->whereDate('delivered_at', today())
            ->count();
        
        // Delivery history
        $deliveryHistory = Order::where('assigned_rider_id', $rider->id)
            ->where('status', Order::STATUS_DELIVERED)
            ->latest()
            ->take(10)
            ->get();
        
        // Total earnings
        $totalEarnings = Payment::where('rider_id', $rider->id)
            ->where('status', 'paid')
            ->sum('amount');
        
        return view('dashboard.rider', compact(
            'rider', 'pendingOffers', 'currentOrders', 'completedToday', 
            'deliveryHistory', 'totalEarnings'
        ));
    }
    
    private function customerDashboard()
{
    $user = auth()->user();
    
    // Check for welcome points
    $showWelcomeModal = false;
    if (!$user->welcome_points_claimed) {
        $showWelcomeModal = true;
    }
    
    $recentOrders = Order::where('customer_id', $user->id)
        ->latest()
        ->take(10)
        ->get();
    
    $activeOrders = Order::where('customer_id', $user->id)
        ->whereIn('status', ['pending', 'approved', 'assigned', 'picked_up'])
        ->with('assignedRider.user')
        ->latest()
        ->get();
    
    $completedOrders = Order::where('customer_id', $user->id)
        ->where('status', 'delivered')
        ->count();
    
    $pendingPayments = Payment::where('customer_id', $user->id)
        ->where('status', 'pending')
        ->with('order')
        ->get();
    
    return view('dashboard.customer', compact(
        'user', 'recentOrders', 'activeOrders', 'completedOrders', 'pendingPayments', 'showWelcomeModal'
    ));
}
}