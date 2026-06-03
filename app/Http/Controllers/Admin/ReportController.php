<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Payment;
use App\Models\Rider;
use Carbon\Carbon;

class ReportController extends Controller
{
    // Remove __construct
    
    public function index()
{
    $todayOrders = Order::whereDate('created_at', today())->count();
    $todayDeliveries = Order::whereDate('delivered_at', today())->count();
    $todayRevenue = Payment::whereDate('created_at', today())->where('status', 'paid')->sum('amount');
    
    // ADD THESE LINES
    $todayDeliveryFees = Order::whereDate('delivered_at', today())->sum('delivery_fee');
    $todayRiderEarnings = Order::whereDate('delivered_at', today())->sum('rider_earnings');
    $todayAdminEarnings = Order::whereDate('delivered_at', today())->sum('admin_earnings');
    
    $weekOrders = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
    $weekRevenue = Payment::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->where('status', 'paid')
        ->sum('amount');
    
    // ADD THESE LINES
    $weekDeliveryFees = Order::whereBetween('delivered_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('delivery_fee');
    $weekRiderEarnings = Order::whereBetween('delivered_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('rider_earnings');
    $weekAdminEarnings = Order::whereBetween('delivered_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('admin_earnings');
    
    $monthOrders = Order::whereMonth('created_at', now()->month)->count();
    $monthRevenue = Payment::whereMonth('created_at', now()->month)
        ->where('status', 'paid')
        ->sum('amount');
    
    // ADD THESE LINES
    $monthDeliveryFees = Order::whereMonth('delivered_at', now()->month)->sum('delivery_fee');
    $monthRiderEarnings = Order::whereMonth('delivered_at', now()->month)->sum('rider_earnings');
    $monthAdminEarnings = Order::whereMonth('delivered_at', now()->month)->sum('admin_earnings');
    
    $topRiders = Rider::with('user')
        ->orderBy('total_deliveries', 'desc')
        ->limit(5)
        ->get();
    
    $topEarningRiders = Rider::with('user')
        ->orderBy('total_earnings', 'desc')
        ->limit(5)
        ->get();
    
    $ordersByStatus = [
        'pending' => Order::where('status', 'pending')->count(),
        'sent' => Order::where('status', 'sent')->count(),
        'accepted' => Order::where('status', 'accepted')->count(),
        'approved' => Order::where('status', 'approved')->count(),
        'assigned' => Order::where('status', 'assigned')->count(),
        'picked_up' => Order::where('status', 'picked_up')->count(),
        'delivered' => Order::where('status', 'delivered')->count(),
        'cancelled' => Order::where('status', 'cancelled')->count(),
    ];
    
    $totalDeliveryFees = Order::sum('delivery_fee');
    $totalRiderEarnings = Order::sum('rider_earnings');
    $totalAdminEarnings = Order::sum('admin_earnings');
    $averageDeliveryFee = Order::avg('delivery_fee') ?? 0;
    
    return view('admin.reports.index', compact(
        'todayOrders', 'todayDeliveries', 'todayRevenue',
        'todayDeliveryFees', 'todayRiderEarnings', 'todayAdminEarnings',
        'weekOrders', 'weekRevenue', 'weekDeliveryFees', 'weekRiderEarnings', 'weekAdminEarnings',
        'monthOrders', 'monthRevenue', 'monthDeliveryFees', 'monthRiderEarnings', 'monthAdminEarnings',
        'topRiders', 'topEarningRiders', 'ordersByStatus',
        'totalDeliveryFees', 'totalRiderEarnings', 'totalAdminEarnings', 'averageDeliveryFee'
    ));
}
    
    public function orders(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30));
        $endDate = $request->get('end_date', Carbon::now());
        
        $orders = Order::with(['customer', 'assignedRider.user'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(50);
            
        return view('admin.reports.orders', compact('orders', 'startDate', 'endDate'));
    }
    
    public function earnings(Request $request)
{
    $startDate = $request->get('start_date', Carbon::now()->subDays(30));
    $endDate = $request->get('end_date', Carbon::now());
    
    // Delivery fee earnings (from orders)
    $deliveryEarnings = Order::whereBetween('delivered_at', [$startDate, $endDate])
        ->selectRaw('SUM(delivery_fee) as total_fees, SUM(rider_earnings) as rider_earnings, SUM(admin_earnings) as admin_earnings')
        ->first();
    
    // Payment records
    $payments = Payment::with(['order', 'customer', 'rider.user'])
        ->whereBetween('created_at', [$startDate, $endDate])
        ->orderBy('created_at', 'desc')
        ->paginate(50);
    
    $totalEarnings = Payment::whereBetween('created_at', [$startDate, $endDate])
        ->where('status', 'paid')
        ->sum('amount');
    
    // Rider earnings summary
    $riderEarningsSummary = Rider::with('user')
        ->where('total_earnings', '>', 0)
        ->orderBy('total_earnings', 'desc')
        ->take(10)
        ->get();
    
    // CHART DATA - Daily earnings for last 7 days
    $chartLabels = [];
    $chartDeliveryFees = [];
    $chartRiderEarnings = [];
    $chartAdminEarnings = [];
    
    for ($i = 6; $i >= 0; $i--) {
        $date = Carbon::now()->subDays($i);
        $chartLabels[] = $date->format('M d');
        
        $dailyDeliveryFees = Order::whereDate('delivered_at', $date)->sum('delivery_fee');
        $dailyRiderEarnings = Order::whereDate('delivered_at', $date)->sum('rider_earnings');
        $dailyAdminEarnings = Order::whereDate('delivered_at', $date)->sum('admin_earnings');
        
        $chartDeliveryFees[] = round($dailyDeliveryFees, 2);
        $chartRiderEarnings[] = round($dailyRiderEarnings, 2);
        $chartAdminEarnings[] = round($dailyAdminEarnings, 2);
    }
    
    // Monthly earnings chart (last 6 months)
    $monthlyLabels = [];
    $monthlyDeliveryFees = [];
    $monthlyRiderEarnings = [];
    $monthlyAdminEarnings = [];
    
    for ($i = 5; $i >= 0; $i--) {
        $month = Carbon::now()->subMonths($i);
        $monthlyLabels[] = $month->format('M Y');
        
        $monthlyDeliveryFees[] = Order::whereMonth('delivered_at', $month->month)
            ->whereYear('delivered_at', $month->year)
            ->sum('delivery_fee');
        $monthlyRiderEarnings[] = Order::whereMonth('delivered_at', $month->month)
            ->whereYear('delivered_at', $month->year)
            ->sum('rider_earnings');
        $monthlyAdminEarnings[] = Order::whereMonth('delivered_at', $month->month)
            ->whereYear('delivered_at', $month->year)
            ->sum('admin_earnings');
    }
    
    return view('admin.reports.earnings', compact(
        'payments', 'totalEarnings', 'startDate', 'endDate',
        'deliveryEarnings', 'riderEarningsSummary',
        'chartLabels', 'chartDeliveryFees', 'chartRiderEarnings', 'chartAdminEarnings',
        'monthlyLabels', 'monthlyDeliveryFees', 'monthlyRiderEarnings', 'monthlyAdminEarnings'
    ));
}
}