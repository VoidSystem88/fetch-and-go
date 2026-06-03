<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Fetch and Go') }} - Staff Panel</title>
    
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
        
        body {
            background-color: #F3F4F6;
            font-family: 'Figtree', sans-serif;
        }
        
        /* ========== DESKTOP SIDEBAR ========== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100%;
            background: linear-gradient(180deg, #1E3A5F 0%, #0F2B44 100%);
            z-index: 1000;
            overflow-y: auto;
            transition: left 0.3s ease;
        }
        
        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        
        .sidebar-header i {
            font-size: 40px;
            color: #FFB347;
        }
        
        .sidebar-header h3 {
            margin-top: 10px;
            font-size: 18px;
            font-weight: bold;
            color: white;
        }
        
        .sidebar-header p {
            font-size: 11px;
            color: rgba(255,255,255,0.6);
            margin-top: 4px;
        }
        
        .sidebar-menu {
            padding: 16px 0;
        }
        
        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            transition: all 0.2s;
            gap: 12px;
            font-size: 14px;
        }
        
        .sidebar-item i {
            width: 20px;
            font-size: 16px;
        }
        
        .sidebar-item:hover {
            background: rgba(255,255,255,0.08);
            color: white;
        }
        
        .sidebar-item.active {
            background: rgba(255,180,71,0.15);
            color: #FFB347;
            border-right: 3px solid #FFB347;
        }
        
        .sidebar-item.active i {
            color: #FFB347;
        }
        
        /* ========== MAIN CONTENT ========== */
        .main-content {
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }
        
        /* Content Container */
        .content-container {
            width: 100%;
            margin: 0 auto;
        }
        
        /* Top Bar */
        .top-bar {
            background: white;
            border-bottom: 1px solid #E5E7EB;
            padding: 12px 24px;
            position: sticky;
            top: 0;
            z-index: 99;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .menu-btn {
            display: none;
            background: none !important;
            color: #1E3A5F !important;
            font-size: 20px;
            padding: 8px !important;
            width: auto !important;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .logo i {
            font-size: 22px;
            color: #1E3A5F;
        }
        
        .logo span {
            font-weight: 600;
            font-size: 16px;
            color: #1E3A5F;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1E3A5F, #0F2B44);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
            text-decoration: none;
        }
        
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.1);
            z-index: 999;
            display: none;
        }
        
        .overlay.active {
            display: block;
        }
        
        /* ========== DESKTOP MODE (min-width: 1024px) ========== */
        @media (min-width: 1024px) {
            .sidebar {
                left: 0 !important;
            }
            .main-content {
                margin-left: 260px !important;
            }
            .menu-btn {
                display: none !important;
            }
            .content-container {
                max-width: 1200px;
            }
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 20px;
            }
            .actions-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
            }
        }
        
        /* ========== TABLET MODE (768px - 1023px) ========== */
        @media (min-width: 768px) and (max-width: 1023px) {
            .sidebar {
                left: -260px;
            }
            .sidebar.active {
                left: 0;
            }
            .main-content {
                margin-left: 0;
            }
            .menu-btn {
                display: block !important;
            }
            .content-container {
                max-width: 100%;
                padding: 0 16px;
            }
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }
            .actions-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }
        }
        
        /* ========== MOBILE MODE (max-width: 767px) ========== */
        @media (max-width: 767px) {
            .sidebar {
                left: -260px;
                width: 260px;
            }
            .sidebar.active {
                left: 0;
            }
            .main-content {
                margin-left: 0;
            }
            .menu-btn {
                display: block !important;
            }
            .content-container {
                max-width: 100%;
                padding: 0 12px;
            }
            .stats-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 12px;
            }
            .actions-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 12px;
            }
            .top-bar {
                padding: 10px 16px;
            }
            .logo span {
                font-size: 14px;
            }
            main {
                padding: 16px 0 !important;
            }
        }
        
        /* ========== BUTTONS ========== */
        button, .btn, [type="submit"] {
            background-color: #1E3A5F !important;
            color: white !important;
            padding: 8px 16px !important;
            border-radius: 6px !important;
            font-weight: 500 !important;
            font-size: 13px !important;
            border: none !important;
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            transition: all 0.2s !important;
        }
        
        button:hover, .btn:hover, [type="submit"]:hover {
            background-color: #0F2B44 !important;
        }
        
        /* ========== CARDS ========== */
        .stat-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            border: 1px solid #E5E7EB;
            padding: 16px;
        }
        
        .action-card {
            border-radius: 10px;
            padding: 16px;
        }
        
        /* ========== FORMS ========== */
        select, input, textarea {
            background: white;
            color: #1F2937;
            border: 1px solid #D5D5D5;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 13px;
        }
        
        select:focus, input:focus, textarea:focus {
            outline: none;
            border-color: #1E3A5F;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-clipboard-list"></i>
            <h3>Fetch and Go</h3>
            <p>Staff Portal</p>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('dashboard') }}" class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('staff.pending-orders') }}" class="sidebar-item {{ request()->routeIs('staff.pending-orders') ? 'active' : '' }}">
                <i class="fas fa-clock"></i>
                <span>Pending Orders</span>
            </a>
            <a href="{{ route('staff.ready-to-send') }}" class="sidebar-item {{ request()->routeIs('staff.ready-to-send') ? 'active' : '' }}">
                <i class="fas fa-paper-plane"></i>
                <span>Send to Rider</span>
            </a>
            <a href="{{ route('staff.accepted-orders') }}" class="sidebar-item {{ request()->routeIs('staff.accepted-orders') ? 'active' : '' }}">
                <i class="fas fa-check-circle"></i>
                <span>Review Acceptances</span>
            </a>
            <a href="{{ route('staff.available-riders') }}" class="sidebar-item {{ request()->routeIs('staff.available-riders') ? 'active' : '' }}">
                <i class="fas fa-motorcycle"></i>
                <span>Available Riders</span>
            </a>
        </div>
        <div class="sidebar-menu" style="border-top: 1px solid rgba(255,255,255,0.1); margin-top: 20px; padding-top: 20px;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-item" style="width: 100%; background: none !important; color: rgba(255,255,255,0.75) !important;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Overlay (mobile) -->
    <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-bar">
            <button class="menu-btn" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="logo">
                <i class="fas fa-clipboard-list"></i>
                <span>Fetch and Go Staff</span>
            </div>
            <a href="{{ route('customer.profile') }}" class="user-avatar">
                {{ substr(Auth::user()->name, 0, 1) }}
            </a>
        </div>

        <main style="padding: 20px;">
            <div class="content-container">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const isMobile = window.innerWidth <= 767;
            
            if (isMobile) {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            }
        }
        
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        }
        
        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            if (window.innerWidth > 767) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            }
        });
    </script>
</body>
</html>