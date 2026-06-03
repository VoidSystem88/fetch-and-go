@extends('layouts.staff')

@section('content')
<div class="dashboard-container" style="max-width: 1100px; margin: 0 auto; padding: 0 16px;">
    
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Staff Dashboard</h1>
        <p class="text-gray-500 text-sm">Welcome back, {{ auth()->user()->name }}</p>
    </div>

    <!-- Stats Cards - Responsive Grid -->
    <div class="stats-grid" style="display: grid; gap: 16px; margin-bottom: 24px;">
        
        <div class="stat-card" style="background: white; border-radius: 12px; padding: 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border: 1px solid #e5e7eb;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="color: #6b7280; font-size: 12px;">Pending Orders</p>
                    <p style="font-size: 28px; font-weight: bold; color: #1f2937;">{{ $pendingOrders->count() }}</p>
                </div>
                <div style="width: 40px; height: 40px; background: #fef3c7; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-clock" style="color: #f59e0b;"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card" style="background: white; border-radius: 12px; padding: 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border: 1px solid #e5e7eb;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="color: #6b7280; font-size: 12px;">Sent to Riders</p>
                    <p style="font-size: 28px; font-weight: bold; color: #1f2937;">{{ $sentOrders->count() ?? 0 }}</p>
                </div>
                <div style="width: 40px; height: 40px; background: #dbeafe; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-paper-plane" style="color: #3b82f6;"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card" style="background: white; border-radius: 12px; padding: 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border: 1px solid #e5e7eb;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="color: #6b7280; font-size: 12px;">Accepted by Riders</p>
                    <p style="font-size: 28px; font-weight: bold; color: #1f2937;">{{ $acceptedOrders->count() ?? 0 }}</p>
                </div>
                <div style="width: 40px; height: 40px; background: #f3e8ff; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check-circle" style="color: #9333ea;"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card" style="background: white; border-radius: 12px; padding: 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border: 1px solid #e5e7eb;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="color: #6b7280; font-size: 12px;">Today's Deliveries</p>
                    <p style="font-size: 28px; font-weight: bold; color: #1f2937;">{{ $todayAssignments ?? 0 }}</p>
                </div>
                <div style="width: 40px; height: 40px; background: #dcfce7; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-truck" style="color: #10b981;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons - Responsive Grid -->
    <div class="actions-grid" style="display: grid; gap: 16px; margin-bottom: 24px;">
        <a href="{{ route('staff.ready-to-send') }}" class="action-btn" style="background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 12px; padding: 16px; text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <i class="fas fa-paper-plane" style="font-size: 24px; margin-bottom: 8px;"></i>
                <p style="font-weight: bold;">Send to Rider</p>
                <p style="font-size: 12px; opacity: 0.8;">{{ $pendingOrders->count() }} orders pending</p>
            </div>
            <i class="fas fa-arrow-right"></i>
        </a>
        
        <a href="{{ route('staff.accepted-orders') }}" class="action-btn" style="background: linear-gradient(135deg, #9333ea, #7e22ce); border-radius: 12px; padding: 16px; text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <i class="fas fa-check-circle" style="font-size: 24px; margin-bottom: 8px;"></i>
                <p style="font-weight: bold;">Review Acceptances</p>
                <p style="font-size: 12px; opacity: 0.8;">{{ $acceptedOrders->count() ?? 0 }} waiting</p>
            </div>
            <i class="fas fa-arrow-right"></i>
        </a>
        
        <a href="{{ route('staff.available-riders') }}" class="action-btn" style="background: linear-gradient(135deg, #10b981, #059669); border-radius: 12px; padding: 16px; text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <i class="fas fa-motorcycle" style="font-size: 24px; margin-bottom: 8px;"></i>
                <p style="font-weight: bold;">Available Riders</p>
                <p style="font-size: 12px; opacity: 0.8;">{{ $availableRiders->count() }} online</p>
            </div>
            <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div style="background: #dcfce7; border-left: 4px solid #10b981; padding: 12px; border-radius: 8px;">
            <i class="fas fa-check-circle" style="color: #10b981; margin-right: 8px;"></i> {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div style="background: #fee2e2; border-left: 4px solid #ef4444; padding: 12px; border-radius: 8px;">
            <i class="fas fa-exclamation-circle" style="color: #ef4444; margin-right: 8px;"></i> {{ session('error') }}
        </div>
    @endif
    
</div>

<style>
    /* Desktop */
    @media (min-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(4, 1fr);
        }
        .actions-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    /* Mobile */
    @media (max-width: 767px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        .actions-grid {
            grid-template-columns: 1fr;
        }
        .dashboard-container {
            padding: 0 12px;
        }
    }
</style>
@endsection