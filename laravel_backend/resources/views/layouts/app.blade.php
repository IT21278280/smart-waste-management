<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Smart Waste Management')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Modern Flat Color Palette */
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --success-color: #059669;
            --warning-color: #d97706;
            --danger-color: #dc2626;
            --info-color: #0891b2;
            --light-color: #f8fafc;
            --dark-color: #1e293b;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
        }
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background-color: var(--gray-100);
            min-height: 100vh;
        }
        
        .sidebar {
            min-height: 100vh;
            background-color: var(--gray-900);
            color: white;
            border-right: 1px solid var(--gray-200);
        }
        
        .sidebar .nav-link {
            color: var(--gray-400);
            padding: 15px 25px;
            margin: 6px 12px;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .sidebar-brand {
            background-color: var(--primary-color);
            padding: 20px;
            margin: 20px 15px;
            text-align: center;
            border: 2px solid var(--primary-color);
        }
        
        .main-content {
            background-color: white;
            min-height: 100vh;
        }
        
        .content-wrapper {
            position: relative;
        }
        
        .card {
            border: 1px solid var(--gray-200);
            background-color: white;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            border-color: var(--gray-300);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .stats-card {
            background-color: var(--primary-color);
            color: white;
            border: 2px solid var(--primary-color);
        }
        
        .stats-card.success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }
        .stats-card.warning {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
        }
        .stats-card.danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }
        .stats-card.info {
            background-color: var(--info-color);
            border-color: var(--info-color);
        }
        
        /* Dashboard specific styles */
        .dashboard-card-users {
            border-left: 4px solid var(--primary-color);
        }
        .dashboard-card-reports {
            border-left: 4px solid var(--success-color);
        }
        .dashboard-card-pending {
            border-left: 4px solid var(--warning-color);
        }
        .dashboard-card-processed {
            border-left: 4px solid var(--info-color);
        }
        
        .icon-circle {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }
        
        .dashboard-card-users .icon-circle {
            background-color: var(--primary-color);
        }
        .dashboard-card-reports .icon-circle {
            background-color: var(--success-color);
        }
        .dashboard-card-pending .icon-circle {
            background-color: var(--warning-color);
        }
        .dashboard-card-processed .icon-circle {
            background-color: var(--info-color);
        }
        .tooltip-custom {
            position: relative;
            cursor: help;
        }
        .tooltip-custom:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            background: var(--gray-800);
            color: white;
            padding: 8px 12px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 1000;
            border: 1px solid var(--gray-700);
        }
        .tooltip-custom:hover::before {
            content: '';
            position: absolute;
            bottom: 115%;
            left: 50%;
            transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: var(--gray-800);
            z-index: 1000;
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .flat-text {
            color: var(--primary-color);
            font-weight: 600;
        }
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }
        
        /* Flat button styles */
        .btn {
            border: 2px solid transparent;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        .btn-primary:hover {
            background-color: var(--gray-700);
            border-color: var(--gray-700);
        }
        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
            color: white;
        }
        .btn-success:hover {
            background-color: var(--gray-700);
            border-color: var(--gray-700);
        }
        .btn-warning {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
            color: white;
        }
        .btn-warning:hover {
            background-color: var(--gray-700);
            border-color: var(--gray-700);
        }
        .btn-info {
            background-color: var(--info-color);
            border-color: var(--info-color);
            color: white;
        }
        .btn-info:hover {
            background-color: var(--gray-700);
            border-color: var(--gray-700);
        }
        .btn-outline-primary {
            background-color: transparent;
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        /* Badge styles */
        .badge {
            font-weight: 500;
            border: 1px solid transparent;
        }
        .bg-success {
            background-color: var(--success-color) !important;
            border-color: var(--success-color);
        }
        .bg-warning {
            background-color: var(--warning-color) !important;
            border-color: var(--warning-color);
        }
        .bg-danger {
            background-color: var(--danger-color) !important;
            border-color: var(--danger-color);
        }
        .bg-info {
            background-color: var(--info-color) !important;
            border-color: var(--info-color);
        }
        .bg-primary {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color);
        }
        .bg-secondary {
            background-color: var(--secondary-color) !important;
            border-color: var(--secondary-color);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar p-3">
                    <div class="sidebar-brand">
                        <i class="fas fa-recycle fa-3x mb-3 pulse-animation"></i>
                        <h5 class="fw-bold mb-1">Smart Waste</h5>
                        <small class="opacity-75">Management System</small>
                    </div>
                    
                    <nav class="nav flex-column">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('api-test') }}">
                                <i class="fas fa-code me-2"></i>API Test
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('ml-test') }}">
                                <i class="fas fa-robot me-2"></i>ML Test
                            </a>
                        </li>
                        <a class="nav-link {{ request()->is('reports*') ? 'active' : '' }}" href="/reports">
                            <i class="fas fa-file-alt me-2"></i> Reports
                        </a>
                        <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="/users">
                            <i class="fas fa-users me-2"></i> Users
                        </a>
                        <a class="nav-link {{ request()->is('analytics*') ? 'active' : '' }}" href="/analytics">
                            <i class="fas fa-chart-bar me-2"></i> Analytics
                        </a>
                        <a class="nav-link {{ request()->is('test*') ? 'active' : '' }}" href="/test">
                            <i class="fas fa-flask me-2"></i> API Test
                        </a>
                    </nav>
                
                <!-- Logout Section -->
                <div class="mt-auto pt-3" style="border-top: 2px solid var(--gray-700);">
                    <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link text-white w-100 text-start p-3" style="border: none; background: none; text-decoration: none;">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-0">
                <div class="main-content p-4">
                    <div class="content-wrapper">
                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-center mb-5" data-aos="fade-down">
                            <div>
                                <h2 class="fw-bold flat-text mb-1">@yield('page-title', 'Dashboard')</h2>
                                <p class="text-muted mb-0">@yield('page-description', 'Welcome to Smart Waste Management System')</p>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="status-indicator me-3">
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="fas fa-circle me-2 pulse-animation"></i> API Online
                                    </span>
                                </div>
                                <div class="text-end">
                                    <div class="fw-semibold">{{ now()->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ now()->format('H:i A') }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS animations
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Add loading states to buttons
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (this.type === 'submit' || this.getAttribute('data-loading') === 'true') {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
                    this.disabled = true;
                    
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }, 2000);
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @yield('scripts')
</body>
</html>
