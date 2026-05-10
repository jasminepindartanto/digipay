<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') — EduPay</title>

            <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --topbar-height: 64px;

            --primary:       #2563eb;
            --primary-soft:  #eff6ff;
            --primary-dark:  #1d4ed8;

            --success:       #16a34a;
            --success-soft:  #f0fdf4;
            --warning:       #d97706;
            --warning-soft:  #fffbeb;
            --danger:        #dc2626;
            --danger-soft:   #fef2f2;
            --info:          #0891b2;
            --info-soft:     #ecfeff;

            --bg:            #f1f5f9;
            --surface:       #ffffff;
            --border:        #e2e8f0;
            --text:          #0f172a;
            --muted:         #64748b;
            --sidebar-bg:    #0f172a;
            --sidebar-text:  #94a3b8;
            --sidebar-hover: #1e293b;
            --sidebar-active:#2563eb;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
            overflow-x: hidden;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform .3s ease;
        }

        .sidebar-brand {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 20px;
            border-bottom: 1px solid rgba(255,255,255,.06);
        }

        .sidebar-brand .brand-icon {
            width: 36px; height: 36px;
            background: var(--primary);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-size: 18px;
            flex-shrink: 0;
        }

        .sidebar-brand span {
            font-weight: 800;
            font-size: 1.15rem;
            color: #fff;
            letter-spacing: -.3px;
        }

        .sidebar-brand small {
            display: block;
            font-size: .65rem;
            color: var(--sidebar-text);
            font-weight: 400;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        .sidebar-nav {
            padding: 16px 12px;
            flex: 1;
            overflow-y: auto;
        }

        .nav-section-label {
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #475569;
            padding: 8px 10px 6px;
            margin-top: 8px;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 9px;
            color: var(--sidebar-text);
            font-size: .875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all .18s;
            margin-bottom: 2px;
        }

        .sidebar-nav .nav-link i {
            font-size: 1rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar-nav .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .sidebar-nav .nav-link.active {
            background: var(--primary);
            color: #fff;
        }

        .sidebar-nav .nav-link .badge {
            margin-left: auto;
            font-size: .65rem;
            padding: 2px 7px;
        }

        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid rgba(255,255,255,.06);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 10px;
            background: var(--sidebar-hover);
        }

        .user-card .avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: var(--primary);
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: .875rem;
            flex-shrink: 0;
        }

        .user-card .user-info span {
            display: block;
            font-size: .8rem;
            font-weight: 600;
            color: #fff;
            line-height: 1.3;
        }

        .user-card .user-info small {
            font-size: .7rem;
            color: var(--sidebar-text);
        }

        .user-card .btn-logout {
            margin-left: auto;
            color: var(--sidebar-text);
            font-size: 1rem;
            background: none;
            border: none;
            cursor: pointer;
            transition: color .2s;
        }

        .user-card .btn-logout:hover { color: var(--danger); }

        /* ── TOPBAR ── */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 12px;
            z-index: 900;
        }

        .topbar .page-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text);
            margin: 0;
        }

        .topbar .breadcrumb {
            font-size: .75rem;
            color: var(--muted);
            margin: 0;
        }

        .topbar .breadcrumb-item + .breadcrumb-item::before { color: var(--muted); }

        .topbar .ms-auto { margin-left: auto !important; }

        .topbar-actions { display: flex; align-items: center; gap: 8px; }

        .topbar-btn {
            width: 38px; height: 38px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--muted);
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
            cursor: pointer;
            position: relative;
            transition: all .18s;
        }

        .topbar-btn:hover {
            background: var(--bg);
            color: var(--text);
        }

        .topbar-btn .notif-dot {
            position: absolute;
            top: 7px; right: 7px;
            width: 7px; height: 7px;
            border-radius: 50%;
            background: var(--danger);
            border: 1.5px solid #fff;
        }

        /* ── MAIN CONTENT ── */
        .main-content {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
            min-height: 100vh;
        }

        .content-area {
            padding: 28px 28px 40px;
        }

        /* ── STAT CARDS ── */
        .stat-card {
            background: var(--surface);
            border-radius: 16px;
            padding: 22px 24px;
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
            transition: transform .2s, box-shadow .2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0,0,0,.08);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            border-radius: 16px 16px 0 0;
        }

        .stat-card.primary::after  { background: var(--primary); }
        .stat-card.success::after  { background: var(--success); }
        .stat-card.danger::after   { background: var(--danger); }
        .stat-card.warning::after  { background: var(--warning); }

        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.35rem;
            margin-bottom: 14px;
        }

        .stat-icon.primary { background: var(--primary-soft); color: var(--primary); }
        .stat-icon.success { background: var(--success-soft); color: var(--success); }
        .stat-icon.danger  { background: var(--danger-soft);  color: var(--danger); }
        .stat-icon.warning { background: var(--warning-soft); color: var(--warning); }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -1px;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: .8rem;
            color: var(--muted);
            font-weight: 500;
        }

        .stat-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: .72rem;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 20px;
            margin-top: 10px;
        }

        .stat-badge.up   { background: var(--success-soft); color: var(--success); }
        .stat-badge.down { background: var(--danger-soft);  color: var(--danger); }
        .stat-badge.neutral { background: #f1f5f9; color: var(--muted); }

        /* Progress Card */
        .progress-card {
            background: var(--surface);
            border-radius: 16px;
            padding: 24px;
            border: 1px solid var(--border);
        }

        .progress-card .progress {
            height: 8px;
            border-radius: 99px;
            background: var(--bg);
        }

        .progress-card .progress-bar {
            border-radius: 99px;
        }

        /* Data table */
        .data-card {
            background: var(--surface);
            border-radius: 16px;
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .data-card .card-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
            background: transparent;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .data-card .card-title {
            font-size: .95rem;
            font-weight: 700;
            margin: 0;
        }

        .table { margin: 0; }

        .table thead th {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: var(--muted);
            padding: 12px 22px;
            border-bottom: 1px solid var(--border);
            background: #f8fafc;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 13px 22px;
            font-size: .875rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border);
        }

        .table tbody tr:last-child td { border-bottom: none; }

        .table tbody tr:hover td { background: #f8fafc; }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 600;
        }

        .status-badge::before {
            content: '';
            width: 6px; height: 6px;
            border-radius: 50%;
        }

        .status-badge.paid { background: var(--success-soft); color: var(--success); }
        .status-badge.paid::before { background: var(--success); }
        .status-badge.unpaid { background: var(--danger-soft); color: var(--danger); }
        .status-badge.unpaid::before { background: var(--danger); }
        .status-badge.pending { background: var(--warning-soft); color: var(--warning); }
        .status-badge.pending::before { background: var(--warning); }

        /* Responsive */
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .topbar, .main-content { left: 0; margin-left: 0; }
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
            <div>
                <span>EduPay</span>
                <small>Admin Panel</small>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-label">Main</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
            <a href="{{ route('registrations.index') }}" class="nav-link {{ request()->routeIs('registrations.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard-check-fill"></i> Pendaftaran
                @if($pendingCount > 0)
                <span class="badge bg-danger rounded-pill ms-auto">
                    {{ $pendingCount }}
                </span>
            @endif
            </a>
            <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Data Siswa
            </a>
            <a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('pembayaran.*') ? 'active' : '' }}">
                <i class="bi bi-credit-card-fill"></i> Pembayaran
                @if(isset($belumBayarCount) && $belumBayarCount > 0)
                    <span class="badge bg-danger rounded-pill">{{ $belumBayarCount }}</span>
                @endif
            </a>
            <a href="#" class="nav-link">
                <i class="bi bi-bar-chart-fill"></i> Laporan
            </a>

            <div class="nav-section-label">Pengaturan</div>
            <a href="#" class="nav-link">
                <i class="bi bi-gear-fill"></i> Pengaturan
            </a>
            <a href="#" class="nav-link">
                <i class="bi bi-person-badge-fill"></i> Pengguna
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="avatar">A</div>
                <div class="user-info">
                    <span>Admin</span>
                    <small>admin@edupay.id</small>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn-logout" title="Logout"><i class="bi bi-box-arrow-right"></i></button>
                </form>
            </div>
        </div>
    </aside>

    <!-- TOPBAR -->
    <header class="topbar">
        <button class="topbar-btn d-lg-none" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>

        <div>
            <p class="page-title">@yield('page-title', 'Dashboard')</p>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none" style="color:var(--muted)">Home</a></li>
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>

        <div class="topbar-actions ms-auto">
            <button class="topbar-btn">
                <i class="bi bi-bell"></i>
                <span class="notif-dot"></span>
            </button>
            <button class="topbar-btn">
                <i class="bi bi-question-circle"></i>
            </button>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="content-area">
            @if(session('success'))
                <div id="alertMessage" class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', () => {
            document.getElementById('sidebar').classList.toggle('open');
        });
    </script>
    @stack('scripts')
    <script>
    setTimeout(() => {
        let alert = document.getElementById('alertMessage');
        if (alert) {
            let bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 3000); // hilang setelah 3 detik
</script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

@stack('scripts')
</body>
</html>