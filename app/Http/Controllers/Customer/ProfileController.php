<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        
        $totalOrders = Order::where('customer_id', $user->id)->count();
        $completedOrders = Order::where('customer_id', $user->id)->where('status', 'delivered')->count();
        $pendingOrders = Order::where('customer_id', $user->id)->where('status', 'pending')->count();
        
        // CHANGE THIS LINE
        return view('customer.profile', compact('totalOrders', 'completedOrders', 'pendingOrders'));
    }
    
        public function update(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'birthday' => 'nullable|date',
        ]);
        
        $user->update($validated);
        
        return redirect()->route('customer.profile')->with('success', 'Profile updated successfully!');
    }
    
    public function updatePassword(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);
        
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }
        
        $user->update([
            'password' => Hash::make($validated['password'])
        ]);
        
        return redirect()->route('customer.profile')->with('success', 'Password changed successfully!');
    }
    public function claimBirthday()
{
    $user = auth()->user();
    $result = $user->claimBirthdayDiscount();
    
    if ($result['success']) {
        return redirect()->route('customer.profile')->with('success', $result['message'] . ' Discount code: ' . $result['discount_code']);
    }
    
    return redirect()->route('customer.profile')->with('error', $result['message']);
}
}