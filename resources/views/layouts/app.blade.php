<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — NexaERP</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #667eea;
            --primary-dark: #764ba2;
            --sidebar-width: 260px;
            --topbar-height: 64px;
            --sidebar-bg: #1e2139;
            --sidebar-hover: #2d3154;
            --sidebar-active: linear-gradient(135deg, #667eea, #764ba2);
        }

        * { font-family: 'Inter', sans-serif; box-sizing: border-box; }
        body { background: #f0f2f5; margin: 0; padding: 0; }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            z-index: 1000;
            overflow-y: auto;
            overflow-x: hidden;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-brand .logo-text {
            font-size: 1.4rem;
            font-weight: 700;
            color: white;
            letter-spacing: -0.5px;
        }

        .sidebar-brand .logo-text span { color: #ffd700; }

        .sidebar-brand .logo-badge {
            font-size: 0.6rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .sidebar-section-title {
            font-size: 0.65rem;
            font-weight: 600;
            color: rgba(255,255,255,0.35);
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 1rem 1.5rem 0.4rem;
        }

        .sidebar-nav { padding: 0.5rem 0; }

        .sidebar-nav .nav-item { margin: 2px 0.75rem; }

        .sidebar-nav .nav-link {
            color: rgba(255,255,255,0.65);
            padding: 0.6rem 0.85rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .sidebar-nav .nav-link:hover {
            background: var(--sidebar-hover);
            color: white;
        }

        .sidebar-nav .nav-link.active {
            background: var(--sidebar-active);
            color: white;
            box-shadow: 0 4px 12px rgba(102,126,234,0.4);
        }

        .sidebar-nav .nav-link i {
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }

        .sidebar-nav .nav-link .badge {
            margin-left: auto;
            font-size: 0.65rem;
        }

        /* Dropdown inside sidebar */
        .sidebar-nav .collapse-item {
            padding: 0.45rem 0.85rem 0.45rem 2.8rem;
            font-size: 0.82rem;
            color: rgba(255,255,255,0.5);
            border-radius: 8px;
            display: block;
            text-decoration: none;
            transition: all 0.2s;
            margin: 1px 0.75rem;
        }

        .sidebar-nav .collapse-item:hover,
        .sidebar-nav .collapse-item.active {
            color: white;
            background: var(--sidebar-hover);
        }

        .sidebar-nav .nav-link[data-bs-toggle="collapse"] .arrow {
            margin-left: auto;
            font-size: 0.75rem;
            transition: transform 0.2s;
        }

        .sidebar-nav .nav-link[aria-expanded="true"] .arrow {
            transform: rotate(90deg);
        }

        /* ── Topbar ── */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: white;
            border-bottom: 1px solid #e8ecf0;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            z-index: 999;
            gap: 1rem;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }

        .topbar .page-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1e2139;
            margin: 0;
        }

        .topbar .topbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .topbar .icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: none;
            background: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            position: relative;
        }

        .topbar .icon-btn:hover { background: #e2e8f0; color: #1e2139; }

        .topbar .notification-dot {
            position: absolute;
            top: 6px; right: 6px;
            width: 8px; height: 8px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid white;
        }

        .topbar .user-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            border: none;
            background: #f0f2f5;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            color: #1e2139;
        }

        .topbar .user-btn:hover { background: #e2e8f0; }

        .topbar .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .topbar .user-info .user-name {
            font-size: 0.85rem;
            font-weight: 600;
            line-height: 1.2;
        }

        .topbar .user-info .user-role {
            font-size: 0.7rem;
            color: #94a3b8;
            line-height: 1.2;
        }

        /* ── Main Content ── */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            padding: 1.5rem;
            min-height: calc(100vh - var(--topbar-height));
        }

        /* ── Stat Cards ── */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            border: 1px solid #f0f2f5;
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 1rem;
        }

        .stat-card .stat-value {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1e2139;
            line-height: 1;
            margin-bottom: 0.3rem;
        }

        .stat-card .stat-label {
            font-size: 0.8rem;
            color: #94a3b8;
            font-weight: 500;
        }

        .stat-card .stat-change {
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .stat-card .stat-change.up { color: #22c55e; }
        .stat-card .stat-change.down { color: #ef4444; }

        /* ── Content Cards ── */
        .content-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            border: 1px solid #f0f2f5;
            overflow: hidden;
        }

        .content-card .card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: white;
        }

        .content-card .card-header h6 {
            margin: 0;
            font-weight: 600;
            font-size: 0.9rem;
            color: #1e2139;
        }

        .content-card .card-body { padding: 1.25rem; }

        /* ── Tables ── */
        .table { margin: 0; }
        .table th {
            font-size: 0.75rem;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #f0f2f5;
            padding: 0.75rem 1rem;
            background: #f8fafc;
        }
        .table td {
            font-size: 0.85rem;
            color: #374151;
            padding: 0.75rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f8fafc;
        }
        .table tbody tr:hover { background: #f8fafc; }
        .table tbody tr:last-child td { border-bottom: none; }

        /* ── Badges ── */
        .badge-status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 600;
        }

        /* ── Mobile ── */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.3rem;
            color: #64748b;
            cursor: pointer;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.open { display: block; }
            .topbar { left: 0; }
            .main-content { margin-left: 0; }
            .sidebar-toggle { display: block; }
        }

        /* ── Alerts/Toast ── */
        .alert { border-radius: 10px; border: none; }
        .alert-success { background: #f0fdf4; color: #166534; }
        .alert-danger  { background: #fef2f2; color: #991b1b; }
        .alert-warning { background: #fffbeb; color: #92400e; }
        .alert-info    { background: #eff6ff; color: #1e40af; }

        /* ── Scrollbar ── */
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }
    </style>

    @stack('styles')
</head>
<body>

{{-- Sidebar Overlay (mobile) --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- ── SIDEBAR ── --}}
<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div>
            <div class="logo-text">Nexa<span>ERP</span></div>
        </div>
        <span class="logo-badge ms-auto">v1.0</span>
    </div>

    <ul class="sidebar-nav list-unstyled mb-0">

        {{-- MAIN --}}
        <li><div class="sidebar-section-title">Main</div></li>

        <li class="nav-item">
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </a>
        </li>

        {{-- CRM --}}
        <li><div class="sidebar-section-title">CRM</div></li>

        <li class="nav-item">
            <a href="#crmMenu" class="nav-link"
               data-bs-toggle="collapse"
               aria-expanded="{{ request()->routeIs('customers.*') || request()->routeIs('leads.*') || request()->routeIs('activities.*') ? 'true' : 'false' }}">
                <i class="bi bi-people"></i>
                CRM
                <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="collapse {{ request()->routeIs('customers.*') || request()->routeIs('leads.*') || request()->routeIs('activities.*') ? 'show' : '' }}"
                 id="crmMenu">
                <a href="{{ route('customers.index') }}"
                   class="collapse-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    <i class="bi bi-person-lines-fill me-1"></i> Customers
                </a>
                <a href="{{ route('leads.index') }}"
                   class="collapse-item {{ request()->routeIs('leads.*') ? 'active' : '' }}">
                    <i class="bi bi-funnel me-1"></i> Leads
                </a>
                <a href="{{ route('activities.index') }}"
                  class="collapse-item {{ request()->routeIs('activities.*') ? 'active' : '' }}">
                    <i class="bi bi-activity me-1"></i> Activities
                </a>
            </div>
        </li>

        {{-- INVENTORY --}}
        <li><div class="sidebar-section-title">Inventory</div></li>

        <li class="nav-item">
            <a href="#inventoryMenu" class="nav-link"
               data-bs-toggle="collapse"
               aria-expanded="{{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('stock-transactions.*') ? 'true' : 'false' }}">
                <i class="bi bi-box-seam"></i>
                Products
                <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="collapse {{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('stock-transactions.*') ? 'show' : '' }}"
                 id="inventoryMenu">
                <a href="{{ route('products.index') }}"
                   class="collapse-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <i class="bi bi-box me-1"></i> Products
                </a>
                <a href="{{ route('categories.index') }}"
                   class="collapse-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags me-1"></i> Categories
                </a>
                <a href="{{ route('stock-transactions.index') }}"
                   class="collapse-item {{ request()->routeIs('stock-transactions.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right me-1"></i> Stock Transactions
                </a>
            </div>
        </li>

        {{-- SALES --}}
        <li><div class="sidebar-section-title">Sales</div></li>

        <li class="nav-item">
            <a href="{{ route('invoices.index') }}"
               class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i>
                Invoices
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('payments.index') }}"
               class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                <i class="bi bi-credit-card"></i>
                Payments
            </a>
        </li>

        {{-- REPORTS --}}
        <li><div class="sidebar-section-title">Reports</div></li>

        <li class="nav-item">
            <a href="#reportMenu" class="nav-link"
               data-bs-toggle="collapse"
               aria-expanded="{{ request()->routeIs('reports.*') ? 'true' : 'false' }}">
                <i class="bi bi-bar-chart-line"></i>
                Reports
                <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="collapse {{ request()->routeIs('reports.*') ? 'show' : '' }}"
                 id="reportMenu">
                <a href="{{ route('reports.sales') }}"
                   class="collapse-item {{ request()->routeIs('reports.sales') ? 'active' : '' }}">
                    <i class="bi bi-receipt me-1"></i> Sales
                </a>
                <a href="{{ route('reports.customers') }}"
                   class="collapse-item {{ request()->routeIs('reports.customers') ? 'active' : '' }}">
                    <i class="bi bi-people me-1"></i> Customers
                </a>
                <a href="{{ route('reports.inventory') }}"
                   class="collapse-item {{ request()->routeIs('reports.inventory') ? 'active' : '' }}">
                    <i class="bi bi-box me-1"></i> Inventory
                </a>
                <a href="{{ route('reports.payments') }}"
                   class="collapse-item {{ request()->routeIs('reports.payments') ? 'active' : '' }}">
                    <i class="bi bi-credit-card me-1"></i> Payments
                </a>
                <a href="{{ route('reports.leads') }}"
                   class="collapse-item {{ request()->routeIs('reports.leads') ? 'active' : '' }}">
                    <i class="bi bi-funnel me-1"></i> Leads
                </a>
            </div>
        </li>

        {{-- ADMIN --}}
        @if(auth()->user()->hasAnyRole(['super-admin', 'admin']))
        <li><div class="sidebar-section-title">Admin</div></li>
        <li class="nav-item">
            <a href="{{ route('users.index') }}"
               class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="bi bi-shield-lock"></i>
                Users & Roles
            </a>
        </li>
        @endif

    </ul>
</nav>

{{-- ── TOPBAR ── --}}
<header class="topbar">
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </button>

    <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>

    <div class="topbar-right">

        {{-- Notifications --}}
        <a href="#" class="icon-btn">
            <i class="bi bi-bell"></i>
            <span class="notification-dot"></span>
        </a>

        {{-- User Dropdown --}}
        <div class="dropdown">
            <button class="user-btn dropdown-toggle" data-bs-toggle="dropdown">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="user-info d-none d-md-block">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ auth()->user()->role->name ?? 'User' }}</div>
                </div>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                style="min-width:200px; border-radius:10px; border:1px solid #f0f2f5;">
                <li>
                    <div class="px-3 py-2 border-bottom">
                        <div style="font-size:0.85rem; font-weight:600;">{{ auth()->user()->name }}</div>
                        <div style="font-size:0.75rem; color:#94a3b8;">{{ auth()->user()->email }}</div>
                    </div>
                </li>
                <li>
                    <a class="dropdown-item py-2" href="#" style="font-size:0.85rem;">
                        <i class="bi bi-person me-2 text-muted"></i> My Profile
                    </a>
                </li>
                <li>
                    <a class="dropdown-item py-2" href="#" style="font-size:0.85rem;">
                        <i class="bi bi-gear me-2 text-muted"></i> Settings
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item py-2 text-danger" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       style="font-size:0.85rem;">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>

{{-- ── MAIN CONTENT ── --}}
<main class="main-content">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</main>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('open');
    }

    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('open');
    }

    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => {
            let alert = bootstrap.Alert.getOrCreateInstance(el);
            alert.close();
        });
    }, 4000);
</script>

@stack('scripts')
</body>
</html>