<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Quizzer</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom Style -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="admin-body">
    <div class="admin-sidebar-overlay" id="adminSidebarOverlay"></div>
    <div class="admin-sidebar" style="--accent: #6366f1;">
        <h3 class="mb-5"><i class="bi bi-shield-lock-fill me-2 text-primary"></i>Admin</h3>
        <nav class="nav flex-column">
            <a href="{{ route('admin.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('admin.users') }}"
                class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Users
            </a>
            <a href="{{ route('admin.ai-jobs') }}"
                class="nav-link {{ request()->routeIs('admin.ai-jobs') ? 'active' : '' }}">
                <i class="bi bi-cpu"></i> AI Jobs
            </a>
            <a href="{{ route('admin.analytics') }}"
                class="nav-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                <i class="bi bi-graph-up"></i> Analytics
            </a>
            <a href="{{ route('admin.league.settings') }}"
                class="nav-link {{ request()->routeIs('admin.league.settings') ? 'active' : '' }}">
                <i class="bi bi-trophy"></i> League Settings
            </a>
            <a href="{{ route('admin.settings') }}"
                class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <i class="bi bi-gear"></i> Settings
            </a>
        </nav>

        <div class="mt-auto pt-5">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button class="nav-link w-100 bg-transparent border-0 text-start">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <div class="admin-header">
        <div class="d-flex align-items-center">
            <button class="btn btn-light d-lg-none me-3" id="adminSidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <div class="search-bar">
                <strong>System Control Center</strong>
            </div>
        </div>
        <div class="user-profile d-flex align-items-center gap-3">
            <span class="badge bg-danger">ADMIN MODE</span>
            <span>{{ auth()->user()->name }}</span>
        </div>
    </div>

    <div class="admin-main-content">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('adminSidebarToggle');
            const sidebar = document.querySelector('.admin-sidebar');
            const overlay = document.getElementById('adminSidebarOverlay');

            if (toggleBtn && sidebar && overlay) {
                toggleBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                });

                overlay.addEventListener('click', () => {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
