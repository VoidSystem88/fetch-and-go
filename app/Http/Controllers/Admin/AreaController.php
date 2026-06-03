<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\User;

class AreaController extends Controller
{
    // Remove __construct
    
    public function index()
    {
        $areas = Area::with('parent')->get();
        $staff = User::where('role', 'staff')->get();
        
        return view('admin.areas.index', compact('areas', 'staff'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_area_id' => 'nullable|exists:areas,id',
        ]);
        
        Area::create($validated);
        
        return redirect()->route('admin.areas.index')
            ->with('success', 'Area created successfully!');
    }
    
    public function update(Request $request, Area $area)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_area_id' => 'nullable|exists:areas,id',
        ]);
        
        $area->update($validated);
        
        return redirect()->route('admin.areas.index')
            ->with('success', 'Area updated successfully!');
    }
    
    public function destroy(Area $area)
    {
        $area->delete();
        
        return redirect()->route('admin.areas.index')
            ->with('success', 'Area deleted successfully!');
    }
    
    public function assignStaff(Request $request, Area $area)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:users,id',
        ]);
        
        $area->staff()->attach($validated['staff_id'], [
            'is_primary' => $request->has('is_primary')
        ]);
        
        return back()->with('success', 'Staff assigned to area successfully!');
    }
}