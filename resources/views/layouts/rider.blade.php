<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Fetch and Go') }} - Rider Panel</title>
    
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
            font-family: 'Figtree', sans-serif;
            background-color: #F3F4F6;
        }
        
        /* Sidebar Styles - Mobile First */
        .sidebar {
            position: fixed;
            top: 0;
            left: -280px;
            width: 280px;
            height: 100%;
            background: linear-gradient(180deg, #FF6B35 0%, #E85D2C 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            transition: left 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .sidebar.active {
            left: 0;
        }
        
        .sidebar-header {
            padding: 30px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            text-align: center;
        }
        
        .sidebar-header i {
            font-size: 48px;
            color: white;
        }
        
        .sidebar-header h3 {
            margin-top: 12px;
            font-size: 20px;
            font-weight: bold;
            color: white;
        }
        
        .sidebar-header p {
            font-size: 12px;
            color: rgba(255,255,255,0.8);
            margin-top: 4px;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 14px 24px;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            transition: all 0.2s;
            gap: 14px;
            font-size: 15px;
        }
        
        .sidebar-item i {
            width: 24px;
            font-size: 18px;
        }
        
        .sidebar-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar-item.active {
            background: rgba(255,255,255,0.2);
            color: white;
            border-right: 3px solid white;
        }
        
        /* Overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            display: none;
        }
        
        .overlay.active {
            display: block;
        }
        
        /* Top Bar */
        .top-bar {
            background: white;
            border-bottom: 1px solid #E5E7EB;
            padding: 12px 16px;
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .menu-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #FF6B35;
            padding: 8px;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .logo i {
            font-size: 24px;
            color: #FF6B35;
        }
        
        .logo span {
            font-weight: bold;
            font-size: 18px;
            background: linear-gradient(135deg, #FF6B35, #E85D2C);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FF6B35, #E85D2C);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }
        
        /* Main Content */
        .main-content {
            transition: margin-left 0.3s;
            min-height: 100vh;
        }
        
        /* Desktop Styles */
        @media (min-width: 769px) {
            .sidebar {
                left: 0 !important;
            }
            .main-content {
                margin-left: 280px !important;
            }
            .menu-btn {
                display: none !important;
            }
            .overlay {
                display: none !important;
            }
        }
        
        /* Mobile Styles */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0 !important;
            }
            .menu-btn {
                display: block !important;
            }
        }
        
        /* Button Styles */
        button, .btn, [type="submit"] {
            background-color: #FF6B35 !important;
            color: white !important;
            padding: 8px 16px !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            font-size: 13px !important;
            border: none !important;
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            transition: all 0.2s !important;
        }
        
        button:hover, .btn:hover, [type="submit"]:hover {
            background-color: #E85D2C !important;
        }
        
        /* Form Elements */
        select, input, textarea {
            background-color: white !important;
            color: #1F2937 !important;
            border: 1px solid #D1D5DB !important;
            border-radius: 8px !important;
            padding: 8px 12px !important;
            font-size: 13px !important;
        }
        
        /* Status Badges */
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }
        
        /* Table Styles */
        .table-container {
            overflow-x: auto;
            background: white;
            border-radius: 12px;
            border: 1px solid #E5E7EB;
        }
        
        /* Modal */
        .modal {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background: white;
            border-radius: 16px;
            padding: 24px;
            max-width: 500px;
            width: 90%;
        }
        /* ========== TEXT COLORS - ENSURE VISIBILITY ========== */
.text-gray-900, .text-gray-800, .text-gray-700, 
h1, h2, h3, h4, .font-bold, .font-semibold {
    color: #1F2937 !important;
}

.text-gray-600, .text-gray-500, .text-gray-400,
p, .text-sm, .text-xs {
    color: #6B7280 !important;
}

.text-white {
    color: white !important;
}

.text-orange-500, .text-orange-600 {
    color: #FF6B35 !important;
}

.text-green-600, .text-green-500 {
    color: #10B981 !important;
}

.text-blue-600, .text-blue-500 {
    color: #3B82F6 !important;
}

.text-red-600, .text-red-500 {
    color: #EF4444 !important;
}

.text-yellow-600, .text-yellow-500 {
    color: #F59E0B !important;
}

/* Card text */
.bg-white .text-gray-800,
.bg-white .text-gray-900,
.bg-white .font-semibold,
.bg-white .font-bold {
    color: #1F2937 !important;
}

.bg-white .text-gray-500,
.bg-white .text-gray-600,
.bg-white .text-gray-400 {
    color: #6B7280 !important;
}

/* Gradient cards text */
.bg-gradient-to-r .text-white,
.bg-gradient-to-r p,
.bg-gradient-to-r .text-sm,
.bg-gradient-to-r .text-xs {
    color: white !important;
}

/* Labels */
label, .label {
    color: #374151 !important;
}

/* Modal text */
.modal-content .text-gray-800,
.modal-content .text-gray-700 {
    color: #1F2937 !important;
}
/* Gradient Cards - Force white text */
.bg-gradient-to-r,
.bg-gradient-to-r *,
.bg-gradient-to-r p,
.bg-gradient-to-r span,
.bg-gradient-to-r div,
.bg-gradient-to-r .text-sm,
.bg-gradient-to-r .text-xs,
.bg-gradient-to-r .text-lg,
.bg-gradient-to-r .text-xl,
.bg-gradient-to-r .font-bold,
.bg-gradient-to-r .font-semibold,
.bg-gradient-to-r .opacity-90,
.bg-gradient-to-r .opacity-80,
.bg-gradient-to-r .opacity-70,
.bg-gradient-to-r .opacity-60 {
    color: white !important;
}

/* Specific for earnings cards */
.bg-gradient-to-r p,
.bg-gradient-to-r .text-sm,
.bg-gradient-to-r .text-xs,
.bg-gradient-to-r .text-xl,
.bg-gradient-to-r .text-2xl,
.bg-gradient-to-r .text-3xl,
.bg-gradient-to-r .font-bold {
    color: white !important;
}

/* Override any conflicting styles */
.bg-gradient-to-r .text-gray-500,
.bg-gradient-to-r .text-gray-600,
.bg-gradient-to-r .text-gray-700,
.bg-gradient-to-r .text-gray-800,
.bg-gradient-to-r .text-gray-900 {
    color: white !important;
}
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-motorcycle"></i>
            <h3>Fetch and Go</h3>
            <p>Rider Portal</p>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('rider.dashboard') }}" class="sidebar-item {{ request()->routeIs('rider.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('rider.pending-orders') }}" class="sidebar-item {{ request()->routeIs('rider.pending-orders') ? 'active' : '' }}">
                <i class="fas fa-clock"></i>
                <span>Pending Offers</span>
            </a>
            <a href="{{ route('rider.deliveries') }}" class="sidebar-item {{ request()->routeIs('rider.deliveries') ? 'active' : '' }}">
                <i class="fas fa-truck"></i>
                <span>Current Deliveries</span>
            </a>
            <a href="{{ route('rider.history') }}" class="sidebar-item {{ request()->routeIs('rider.history') ? 'active' : '' }}">
                <i class="fas fa-history"></i>
                <span>Delivery History</span>
            </a>
            <a href="{{ route('rider.earnings') }}" class="sidebar-item {{ request()->routeIs('rider.earnings') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>My Earnings</span>
            </a>
            <a href="{{ route('rider.profile') }}" class="sidebar-item {{ request()->routeIs('rider.profile') ? 'active' : '' }}">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
        </div>
        <div class="sidebar-menu" style="border-top: 1px solid rgba(255,255,255,0.2); margin-top: 20px; padding-top: 20px;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-item" style="width: 100%; background: none !important; color: rgba(255,255,255,0.85) !important;">
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
        <div class="top-bar">
            <button class="menu-btn" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="logo">
                <i class="fas fa-motorcycle"></i>
                <span>Fetch and Go Rider</span>
            </div>
            <a href="{{ route('rider.profile') }}" class="user-avatar">
                {{ substr(Auth::user()->name, 0, 1) }}
            </a>
        </div>

        <main style="padding: 20px 16px;">
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
        
        // Close sidebar when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const isMobile = window.innerWidth <= 768;
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickOnMenuBtn = event.target.closest('.menu-btn');
            
            if (isMobile && overlay.classList.contains('active') && !isClickInsideSidebar && !isClickOnMenuBtn) {
                closeSidebar();
            }
        });
    </script>
</body>
</html>