<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class RecentOrdersController extends Controller
{
    public function index()
    {
        $recentOrders = Order::with('customer')
            ->latest()
            ->paginate(20);
        
        // Debug - check if data is being fetched
        // Uncomment to see if there are orders
        // dd($recentOrders->total());
        
        return view('admin.recent-orders.index', compact('recentOrders'));
    }
}