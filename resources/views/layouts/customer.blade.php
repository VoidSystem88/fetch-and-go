<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Fetch and Go') }} - Customer Panel</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        /* Color Variables */
        :root {
            --primary-green: #38bd55;
            --primary-green-dark: #2a9e46;
            --dark-bg: #171717;
            --dark-card: #1e1e1e;
            --orange: #e07c34;
            --text-white: #ffffff;
            --text-gray: #a0a0a0;
        }
        
        body {
            background: var(--dark-bg);
            font-family: 'Figtree', sans-serif;
        }
        
        /* Sidebar Drawer */
        .sidebar {
            position: fixed;
            top: 0;
            left: -280px;
            width: 280px;
            height: 100%;
            background: var(--dark-card);
            border-right: 1px solid rgba(255,255,255,0.05);
            transition: left 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .sidebar.active {
            left: 0;
        }
        
        .sidebar-user {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        
        .sidebar-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-green), var(--orange));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 28px;
            font-weight: bold;
            color: white;
        }
        
        .sidebar-user h4 {
            color: white;
            font-size: 16px;
            margin-bottom: 4px;
        }
        
        .sidebar-user p {
            color: var(--text-gray);
            font-size: 12px;
        }
        
        .sidebar-menu {
            padding: 16px 0;
        }
        
        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 12px 24px;
            color: var(--text-gray);
            text-decoration: none;
            transition: all 0.2s;
            gap: 14px;
            font-size: 14px;
        }
        
        .sidebar-item i {
            width: 24px;
            font-size: 18px;
        }
        
        .sidebar-item:hover {
            background: rgba(56,189,85,0.1);
            color: var(--primary-green);
        }
        
        .sidebar-item.active {
            background: rgba(56,189,85,0.15);
            color: var(--primary-green);
            border-right: 3px solid var(--primary-green);
        }
        
        /* Overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            z-index: 999;
            display: none;
        }
        
        .overlay.active {
            display: block;
        }
        
        /* Top Bar - Profile Info sa Left, Coins sa Right */
        .top-bar {
            background: var(--dark-card);
            border-bottom: 1px solid rgba(255,255,255,0.05);
            padding: 12px 20px;
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .left-section {
            display: flex;
            flex-direction: column;
        }
        
        .greeting {
            font-size: 14px;
            color: var(--text-gray);
        }
        
        .user-name {
            font-size: 18px;
            font-weight: bold;
            color: white;
            margin-top: 2px;
        }
        
        .current-date {
            font-size: 11px;
            color: var(--text-gray);
            margin-top: 2px;
        }
        
        /* Profile Avatar - Sa Left side (katabi ng greeting) */
        .avatar-left {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-green), var(--orange));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.2s;
            margin-right: 12px;
        }
        
        .avatar-left:hover {
            transform: scale(1.05);
        }
        
        .left-header {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        /* Coins Section - Sa Right */
        .coins-section {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(56,189,85,0.1);
            padding: 6px 12px;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }
        
        .coins-section:hover {
            background: rgba(56,189,85,0.2);
        }
        
        .coins-icon {
            font-size: 16px;
            color: var(--primary-green);
        }
        
        .coins-value {
            font-size: 16px;
            font-weight: bold;
            color: white;
        }
        
        .coins-label {
            font-size: 11px;
            color: var(--text-gray);
        }
        
        .right-section {
            display: flex;
            align-items: center;
        }
        
        /* Main Content */
        .main-content {
            transition: margin-left 0.3s;
            min-height: 100vh;
        }
        
        /* Cards */
        .glass-card {
            background: var(--dark-card);
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.05);
            padding: 12px;
            transition: all 0.2s;
        }
        
        .glass-card:hover {
            border-color: rgba(56,189,85,0.3);
        }
        
        /* Status Badges */
        .status-pending { background: rgba(224,124,52,0.15); color: var(--orange); }
        .status-approved { background: rgba(56,189,85,0.15); color: var(--primary-green); }
        .status-assigned { background: rgba(56,189,85,0.15); color: var(--primary-green); }
        .status-picked_up { background: rgba(56,189,85,0.15); color: var(--primary-green); }
        .status-delivered { background: rgba(56,189,85,0.2); color: var(--primary-green); }
        .status-cancelled { background: rgba(239,68,68,0.15); color: #f87171; }
        
        /* Desktop */
        @media (min-width: 769px) {
            .sidebar {
                left: 0 !important;
            }
            .main-content {
                margin-left: 280px !important;
            }
        }
        
        /* Mobile */
        @media (max-width: 768px) {
            .sidebar {
                width: 260px;
            }
            .main-content {
                margin-left: 0;
            }
            .greeting {
                font-size: 11px;
            }
            .user-name {
                font-size: 14px;
            }
            .coins-value {
                font-size: 13px;
            }
            .avatar-left {
                width: 35px;
                height: 35px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

        @php
        // Set timezone to Philippines (Asia/Manila)
        date_default_timezone_set('Asia/Manila');
        $now = new \DateTime();
        $hour = (int)$now->format('H');
        
        if ($hour >= 5 && $hour < 12) {
            $greeting = 'Good morning';
        } elseif ($hour >= 12 && $hour < 18) {
            $greeting = 'Good afternoon';
        } else {
            $greeting = 'Good evening';
        }
        
        $currentTime = $now->format('g:i A');
        $currentDate = $now->format('l, F j, Y');
    @endphp

    <!-- Sidebar Drawer -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <h4>{{ Auth::user()->name }}</h4>
            <p>{{ Auth::user()->email }}</p>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('dashboard') }}" class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('orders.create') }}" class="sidebar-item {{ request()->routeIs('orders.create') ? 'active' : '' }}">
                <i class="fas fa-plus-circle"></i>
                <span>New Order</span>
            </a>
            <a href="{{ route('customer.orders') }}" class="sidebar-item {{ request()->routeIs('customer.orders') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i>
                <span>My Orders</span>
            </a>
            <a href="{{ route('customer.profile') }}" class="sidebar-item {{ request()->routeIs('customer.profile') ? 'active' : '' }}">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
            <a href="{{ route('customer.points.index') }}" class="sidebar-item {{ request()->routeIs('customer.points.*') ? 'active' : '' }}">
                <i class="fas fa-coins"></i>
                <span>My Points</span>
            </a>
            <a href="{{ route('customer.discounts.index') }}" class="sidebar-item {{ request()->routeIs('customer.discounts.*') ? 'active' : '' }}">
                <i class="fas fa-tag"></i>
                <span>My Discounts</span>
            </a>
        </div>
        <div class="sidebar-menu" style="border-top: 1px solid rgba(255,255,255,0.05); margin-top: 20px; padding-top: 20px;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-item" style="width: 100%;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar - Profile Avatar sa Left, Greeting & Name, Coins sa Right -->
        <div class="top-bar">
            <div class="left-header">
                <!-- Profile Avatar - Click to open sidebar -->
                <div class="avatar-left" onclick="toggleSidebar()">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="left-section">
                    <span class="greeting">{{ $greeting }},</span>
                    <span class="user-name">{{ auth()->user()->name }}</span>
                    <span class="current-date">{{ $currentDate }} • {{ $currentTime }}</span>
                </div>
            </div>
            <div class="right-section">
                <!-- Coins - Clickable to points page with dynamic points value -->
                <a href="{{ route('customer.points.index') }}" class="coins-section">
                    <i class="fas fa-coins coins-icon"></i>
                    <span class="coins-value">{{ number_format(auth()->user()->points) }}</span>
                    <span class="coins-label">Points</span>
                </a>
            </div>
        </div>

        <!-- Page Content -->
        <main style="padding: 20px;">
            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }
        
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const isMobile = window.innerWidth <= 768;
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickOnAvatar = event.target.closest('.avatar-left');
            const isClickOnCoins = event.target.closest('.coins-section');
            
            if (isMobile && overlay.classList.contains('active') && !isClickInsideSidebar && !isClickOnAvatar && !isClickOnCoins) {
                closeSidebar();
            }
        });
    </script>
</body>
</html>