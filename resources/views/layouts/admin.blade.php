<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Fetch and Go - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Custom Styles for Admin Panel */
        .sidebar {
            position: fixed;
            top: 0;
            left: -260px;
            width: 260px;
            height: 100%;
            background: white;
            border-right: 1px solid #e5e7eb;
            transition: left 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        }
        
        .sidebar.active {
            left: 0;
        }
        
        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
        }
        
        .sidebar-header i {
            font-size: 40px;
            color: #2563eb;
        }
        
        .sidebar-header h3 {
            margin-top: 10px;
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
        }
        
        .sidebar-header p {
            font-size: 11px;
            color: #6b7280;
            margin-top: 4px;
        }
        
        .sidebar-menu {
            padding: 16px 0;
        }
        
        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: #4b5563;
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
            background: #f3f4f6;
            color: #2563eb;
        }
        
        .sidebar-item.active {
            background: #eff6ff;
            color: #2563eb;
            border-right: 3px solid #2563eb;
        }
        
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
        
        @media (min-width: 768px) {
            .sidebar {
                left: 0 !important;
            }
            .main-content {
                margin-left: 260px !important;
            }
            .menu-btn {
                display: none !important;
            }
        }
        
        @media (max-width: 767px) {
            .menu-btn {
                display: block !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    
    <!-- Sidebar Drawer -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-truck-fast"></i>
            <h3>Fetch and Go</h3>
            <p>Admin Portal</p>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('dashboard') }}" class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="sidebar-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
            <a href="{{ route('admin.vehicles.index') }}" class="sidebar-item {{ request()->routeIs('admin.vehicles.*') ? 'active' : '' }}">
                <i class="fas fa-car"></i>
                <span>Vehicles</span>
            </a>
            <a href="{{ route('admin.reports.index') }}" class="sidebar-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Reports</span>
            </a>
            <a href="{{ route('admin.earnings') }}" class="sidebar-item {{ request()->routeIs('admin.earnings') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i>
                <span>Earnings</span>
            </a>
            <a href="{{ route('admin.audit-logs.index') }}" class="sidebar-item {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}">
                <i class="fas fa-history"></i>
                <span>Audit Logs</span>
            </a>
            <a href="{{ route('admin.recent-orders.index') }}" class="sidebar-item {{ request()->routeIs('admin.recent-orders.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i>
                <span>Orders</span>
            </a>
            <a href="{{ route('admin.applications.index') }}" class="sidebar-item {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i>
                <span>Applications</span>
            </a>
        </div>
        <div class="sidebar-menu" style="border-top: 1px solid #e5e7eb; margin-top: 20px; padding-top: 20px;">
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
    <div class="main-content" style="transition: margin-left 0.3s;">
        <!-- Top Navbar -->
        <nav class="bg-white shadow-sm sticky top-0 z-50 border-b border-gray-200">
            <div class="px-4 py-3 flex justify-between items-center">
                <button class="menu-btn text-gray-600 text-xl" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-truck-fast text-blue-600 text-xl"></i>
                    <span class="text-gray-800 font-semibold text-lg hidden sm:inline">Fetch and Go Admin</span>
                </div>
                <div class="relative">
                    <button onclick="toggleDropdown()" class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span class="text-gray-700 text-sm hidden md:inline">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                    </button>
                    <div id="dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-200">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center space-x-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Page Content -->
        <main class="p-6">
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
        
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdown');
            dropdown.classList.toggle('hidden');
        }
        
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('dropdown');
            const button = event.target.closest('button');
            if (!button && dropdown && !dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
            }
            
            // Close sidebar when clicking outside on mobile
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const isMobile = window.innerWidth <= 767;
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickOnMenuBtn = event.target.closest('.menu-btn');
            
            if (isMobile && overlay.classList.contains('active') && !isClickInsideSidebar && !isClickOnMenuBtn) {
                closeSidebar();
            }
        });
    </script>
</body>
</html>