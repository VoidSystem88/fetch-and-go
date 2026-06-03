<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rider;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('rider')->paginate(15);
        return view('admin.users.index', compact('users'));
    }
    
    public function create()
    {
        return view('admin.users.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:20',
            'role' => 'required|in:customer,rider,staff,admin',
            'password' => 'required|min:6',
        ]);
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'is_verified' => true,
        ]);
        
        if ($validated['role'] === 'rider') {
            Rider::create([
                'user_id' => $user->id,
                'is_available' => true,
                'rating' => 5.0,
                'total_deliveries' => 0,
            ]);
        }
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully! Password: ' . $validated['password']);
    }
    
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
    
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'role' => 'required|in:customer,rider,staff,admin',
        ]);
        
        $oldRole = $user->role;
        $user->update($validated);
        
        // Handle role change for rider
        if ($validated['role'] === 'rider' && !$user->rider) {
            Rider::create([
                'user_id' => $user->id,
                'is_available' => true,
                'rating' => 5.0,
                'total_deliveries' => 0,
            ]);
        }
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }
    
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }
    
    public function verifyRider(User $user)
    {
        if ($user->role !== 'rider') {
            return back()->with('error', 'User is not a rider.');
        }
        
        $user->update(['is_verified' => true]);
        
        return back()->with('success', 'Rider verified successfully!');
    }
}