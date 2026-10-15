<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | MA Sunan Pandanaran</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --emerald-50: #ecfdf5; --emerald-100: #d1fae5;
            --emerald-500: #10b981; --emerald-600: #059669;
            --emerald-700: #047857; --emerald-800: #065f46;
            --emerald-900: #064e3b;
            --gold-400: #fbbf24; --gold-500: #f59e0b; --gold-600: #d97706;
            --slate-50: #f8fafc; --slate-100: #f1f5f9; --slate-200: #e2e8f0;
            --slate-400: #94a3b8; --slate-500: #64748b;
            --slate-700: #334155; --slate-800: #1e293b; --slate-900: #0f172a;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; background: var(--slate-50); color: var(--slate-800); }
        a { text-decoration: none; color: inherit; }

        /* ── SIDEBAR ─────────────────────────────────────────────────────────── */
        .sidebar {
            position: fixed; top: 0; left: 0; height: 100vh; width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--emerald-900) 0%, var(--emerald-800) 60%, #065f46 100%);
            display: flex; flex-direction: column; z-index: 100;
            box-shadow: 4px 0 24px rgba(0,0,0,0.15);
            overflow-y: auto;
        }
        .sidebar-brand {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 1.5rem 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-logo {
            width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0;
            background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 1rem; color: white;
            box-shadow: 0 4px 12px rgba(245,158,11,0.4);
        }
        .sidebar-brand-text .name { color: white; font-weight: 700; font-size: 0.9rem; }
        .sidebar-brand-text .tagline { color: rgba(255,255,255,0.6); font-size: 0.7rem; }

        .sidebar-section { padding: 1rem 0.875rem 0.25rem; }
        .sidebar-section-label {
            color: rgba(255,255,255,0.4); font-size: 0.65rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1.2px;
            padding: 0 0.5rem; margin-bottom: 0.4rem;
        }
        .sidebar-nav { list-style: none; padding: 0 0.875rem; }
        .sidebar-nav-item a {
            display: flex; align-items: center; gap: 0.7rem;
            padding: 0.6rem 0.875rem; border-radius: 10px; color: rgba(255,255,255,0.72);
            font-size: 0.85rem; font-weight: 500; transition: all 0.2s; margin-bottom: 2px;
        }
        .sidebar-nav-item a:hover { background: rgba(255,255,255,0.1); color: white; }
        .sidebar-nav-item a.active { background: rgba(255,255,255,0.15); color: white; font-weight: 600; box-shadow: inset 3px 0 0 var(--gold-400); }
        .sidebar-nav-item a i { font-size: 1rem; width: 18px; text-align: center; }

        .sidebar-user {
            margin-top: auto; padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-user-info { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; }
        .sidebar-avatar {
            width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
            background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 800; font-size: 0.95rem;
        }
        .sidebar-user-name { color: white; font-size: 0.82rem; font-weight: 600; }
        .sidebar-user-role {
            font-size: 0.68rem; font-weight: 600; text-transform: uppercase;
            padding: 2px 8px; border-radius: 50px; letter-spacing: 0.5px;
        }
        .role-admin      { background: rgba(37,99,235,0.22); color: #60a5fa; font-weight: 700; }
        .role-guru       { background: rgba(14,165,233,0.22); color: #38bdf8; font-weight: 700; }
        .role-siswa      { background: rgba(16,185,129,0.22); color: #34d399; font-weight: 700; }
        .role-bendahara  { background: rgba(20,184,166,0.22); color: #2dd4bf; font-weight: 700; }
        .sidebar-logout {
            display: flex; align-items: center; gap: 0.5rem;
            padding: 0.6rem 0.875rem; border-radius: 8px;
            background: rgba(239,68,68,0.12); color: #fca5a5;
            font-size: 0.82rem; font-weight: 600; cursor: pointer;
            border: none; font-family: inherit; width: 100%; transition: all 0.2s;
        }
        .sidebar-logout:hover { background: rgba(239,68,68,0.2); }

        /* ── MAIN CONTENT ────────────────────────────────────────────────────── */
        .main-content {
            margin-left: var(--sidebar-width); min-height: 100vh;
            display: flex; flex-direction: column;
        }
        .topbar {
            height: 64px; background: white; border-bottom: 1px solid var(--slate-200);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1.75rem; position: sticky; top: 0; z-index: 50;
            box-shadow: 0 1px 8px rgba(0,0,0,0.04);
        }
        .topbar-left { display: flex; align-items: center; gap: 0.75rem; }
        .topbar-left h2 { font-size: 1rem; font-weight: 700; color: var(--slate-800); }
        .topbar-breadcrumb { font-size: 0.78rem; color: var(--slate-400); }
        .topbar-right { display: flex; align-items: center; gap: 0.75rem; }
        .topbar-user {
            display: flex; align-items: center; gap: 0.6rem;
            background: var(--slate-50); border: 1px solid var(--slate-200);
            border-radius: 10px; padding: 0.4rem 0.875rem; text-decoration: none;
            transition: all 0.2s; cursor: pointer;
        }
        .topbar-user:hover { background: var(--emerald-50); border-color: var(--emerald-200); }
        .topbar-user-avatar {
            width: 30px; height: 30px; border-radius: 7px;
            background: linear-gradient(135deg, var(--emerald-600), var(--emerald-700));
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 0.8rem;
        }
        .topbar-user-name { font-size: 0.82rem; font-weight: 600; color: var(--slate-700); }
        .topbar-bell {
            width: 36px; height: 36px; border-radius: 9px; background: var(--slate-50);
            border: 1px solid var(--slate-200); display: flex; align-items: center; justify-content: center;
            color: var(--slate-500); cursor: pointer; font-size: 1.05rem; transition: all 0.2s;
            position: relative;
        }
        .topbar-bell:hover { background: var(--emerald-50); color: var(--emerald-700); border-color: var(--emerald-200); }
        
        .notification-badge {
            position: absolute; top: -4px; right: -4px;
            background: #ef4444; color: white; font-size: 0.65rem; font-weight: 800;
            min-width: 18px; height: 18px; border-radius: 50px; padding: 0 4px;
            display: flex; align-items: center; justify-content: center; border: 2px solid white;
            box-shadow: 0 2px 6px rgba(239,68,68,0.4);
        }
        .notification-dropdown {
            position: absolute; right: 0; top: 46px; width: 340px;
            background: white; border-radius: 16px; border: 1px solid var(--slate-200);
            box-shadow: 0 16px 40px rgba(0,0,0,0.15); display: none; z-index: 200;
            overflow: hidden; animation: notifSlide 0.2s ease;
        }
        @keyframes notifSlide { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
        .notification-dropdown.show { display: block; }
        .notif-header {
            padding: 0.875rem 1.1rem; background: var(--slate-50); border-bottom: 1px solid var(--slate-200);
            display: flex; align-items: center; justify-content: space-between;
        }
        .notif-header h4 { font-size: 0.85rem; font-weight: 700; color: var(--slate-800); margin: 0; }
        .notif-body { max-height: 320px; overflow-y: auto; }
        .notif-item {
            display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.75rem 1.1rem;
            border-bottom: 1px solid #f1f5f9; text-decoration: none; color: inherit; transition: background 0.2s;
        }
        .notif-item:hover { background: #f8fafc; }
        .notif-icon {
            width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; font-size: 0.95rem; color: white;
        }
        .bg-amber { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .bg-blue  { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .bg-emerald { background: linear-gradient(135deg, #10b981, #047857); }
        .notif-title { font-size: 0.8rem; font-weight: 700; color: var(--slate-800); line-height: 1.3; }
        .notif-desc { font-size: 0.72rem; color: var(--slate-500); margin-top: 2px; }

        .page-content { padding: 1.75rem; flex: 1; }

        /* ── FLASH MESSAGES ──────────────────────────────────────────────────── */
        .alert {
            padding: 0.875rem 1.25rem; border-radius: 12px; margin-bottom: 1.25rem;
            font-size: 0.875rem; font-weight: 500; display: flex; align-items: center; gap: 0.6rem;
            animation: slideDown 0.35s ease;
        }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-info    { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
        .alert-warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }

        /* ── STAT CARDS ──────────────────────────────────────────────────────── */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; margin-bottom: 1.75rem; }
        .stat-card {
            background: white; border-radius: 16px; padding: 1.5rem;
            border: 1px solid var(--slate-200); box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            display: flex; align-items: flex-start; gap: 1rem; transition: all 0.3s;
        }
        .stat-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08); transform: translateY(-2px); }
        .stat-icon {
            width: 50px; height: 50px; border-radius: 13px;
            display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;
        }
        .si-emerald { background: var(--emerald-50); color: var(--emerald-700); }
        .si-gold    { background: #fffbeb; color: var(--gold-600); }
        .si-blue    { background: #eff6ff; color: #2563eb; }
        .si-purple  { background: #f5f3ff; color: #7c3aed; }
        .si-red     { background: #fef2f2; color: #dc2626; }
        .stat-body {}
        .stat-num { font-size: 1.75rem; font-weight: 800; color: var(--slate-800); line-height: 1; }
        .stat-label { font-size: 0.78rem; color: var(--slate-400); margin-top: 0.25rem; font-weight: 500; }
        .stat-sub { font-size: 0.72rem; color: var(--emerald-600); margin-top: 0.35rem; font-weight: 600; }

        /* ── CARDS ───────────────────────────────────────────────────────────── */
        .card {
            background: white; border-radius: 16px; border: 1px solid var(--slate-200);
            box-shadow: 0 2px 8px rgba(0,0,0,0.04); overflow: hidden;
        }
        .card-header {
            padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--slate-100);
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-header h3 { font-size: 0.95rem; font-weight: 700; color: var(--slate-800); }
        .card-body { padding: 1.5rem; }
        .cards-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        .cards-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; }

        /* ── TABLES ──────────────────────────────────────────────────────────── */
        .table-wrapper { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
        thead th {
            padding: 0.75rem 1rem; text-align: left; font-weight: 700; font-size: 0.75rem;
            text-transform: uppercase; letter-spacing: 0.5px;
            color: var(--slate-400); background: var(--slate-50); border-bottom: 1px solid var(--slate-200);
        }
        tbody td { padding: 0.875rem 1rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        tbody tr:hover { background: #f8fafc; }
        tbody tr:last-child td { border-bottom: none; }

        /* ── BADGES ──────────────────────────────────────────────────────────── */
        .badge {
            padding: 0.25rem 0.65rem; border-radius: 50px; font-size: 0.7rem; font-weight: 700;
            display: inline-flex; align-items: center; gap: 0.25rem;
        }
        .badge-success  { background: #d1fae5; color: #065f46; }
        .badge-warning  { background: #fef3c7; color: #92400e; }
        .badge-danger   { background: #fee2e2; color: #991b1b; }
        .badge-info     { background: #dbeafe; color: #1e40af; }
        .badge-purple   { background: #f5f3ff; color: #7c3aed; }
        .badge-emerald  { background: var(--emerald-50); color: var(--emerald-700); }

        /* ── BUTTONS ─────────────────────────────────────────────────────────── */
        .btn {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.55rem 1.1rem; border-radius: 9px; font-size: 0.82rem; font-weight: 600;
            border: none; cursor: pointer; font-family: inherit; transition: all 0.2s;
        }
        .btn-primary { background: linear-gradient(135deg, var(--emerald-700), var(--emerald-600)); color: white; }
        .btn-primary:hover { background: linear-gradient(135deg, var(--emerald-800), var(--emerald-700)); transform: translateY(-1px); color: white; }
        .btn-gold { background: linear-gradient(135deg, var(--gold-500), var(--gold-600)); color: white; }
        .btn-gold:hover { transform: translateY(-1px); opacity: 0.9; color: white; }
        .btn-outline { background: white; border: 1.5px solid var(--slate-200); color: var(--slate-700); }
        .btn-outline:hover { border-color: var(--emerald-500); color: var(--emerald-700); }
        .btn-danger { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
        .btn-danger:hover { background: #dc2626; color: white; }
        .btn-sm { padding: 0.35rem 0.75rem; font-size: 0.75rem; }

        /* ── FORMS ───────────────────────────────────────────────────────────── */
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.82rem; font-weight: 600; color: var(--slate-700); margin-bottom: 0.4rem; }
        .form-input {
            width: 100%; padding: 0.7rem 0.875rem; border: 1.5px solid var(--slate-200);
            border-radius: 9px; font-size: 0.875rem; font-family: inherit;
            background: white; color: var(--slate-800); transition: all 0.2s;
        }
        .form-input:focus { outline: none; border-color: var(--emerald-500); box-shadow: 0 0 0 3px rgba(16,185,129,0.1); }
        textarea.form-input { resize: vertical; min-height: 100px; }
        select.form-input { cursor: pointer; }
        .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }

        /* ── COURSE CARDS ────────────────────────────────────────────────────── */
        .course-cards-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; }
        .course-card {
            background: white; border-radius: 16px; border: 1px solid var(--slate-200);
            box-shadow: 0 2px 8px rgba(0,0,0,0.04); overflow: hidden;
            transition: all 0.3s; display: flex; flex-direction: column;
        }
        .course-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.1); }
        .course-card-header {
            padding: 1.25rem; height: 100px;
            background: linear-gradient(135deg, var(--emerald-700), var(--emerald-600));
            display: flex; align-items: flex-end;
        }
        .course-card-header h3 { color: white; font-size: 0.95rem; font-weight: 700; }
        .course-card-body { padding: 1.25rem; flex: 1; }
        .course-meta { display: flex; flex-direction: column; gap: 0.4rem; margin-bottom: 1rem; }
        .course-meta-item { display: flex; align-items: center; gap: 0.4rem; font-size: 0.78rem; color: var(--slate-500); }
        .course-meta-item i { color: var(--emerald-600); }
        .course-card-footer { padding: 1rem 1.25rem; border-top: 1px solid var(--slate-100); }

        /* ── QUIZ TIMER ──────────────────────────────────────────────────────── */
        .quiz-timer-bar {
            background: linear-gradient(135deg, var(--emerald-900), var(--emerald-800));
            color: white; padding: 1rem 1.5rem; border-radius: 14px; margin-bottom: 1.5rem;
            display: flex; align-items: center; justify-content: space-between;
        }
        .timer-display {
            font-size: 2rem; font-weight: 800; color: var(--gold-400);
            font-variant-numeric: tabular-nums; letter-spacing: 2px;
        }
        .timer-display.warning { color: #f87171; animation: pulse 1s ease infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.6; } }

        /* ── PAYMENT CARDS ───────────────────────────────────────────────────── */
        .bill-card {
            background: white; border-radius: 16px; padding: 1.25rem;
            border: 1px solid var(--slate-200); display: flex; align-items: center;
            gap: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.04); transition: all 0.3s;
        }
        .bill-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        .bill-icon { width: 50px; height: 50px; border-radius: 12px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
        .bill-details { flex: 1; }
        .bill-details h4 { font-size: 0.9rem; font-weight: 700; color: var(--slate-800); }
        .bill-details .amount { font-size: 1.1rem; font-weight: 800; color: var(--emerald-700); }
        .bill-details .due   { font-size: 0.75rem; color: var(--slate-400); margin-top: 2px; }

        /* ── RESPONSIVE ──────────────────────────────────────────────────────── */
        @media (max-width: 1200px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s; }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .cards-grid-2, .cards-grid-3, .course-cards-grid { grid-template-columns: 1fr; }
            .form-grid-2, .form-grid-3 { grid-template-columns: 1fr; }
        }
    </style>
    @yield('head')
</head>
<body>

<!-- ─── SIDEBAR ────────────────────────────────────────────────────────────── -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-logo">M</div>
        <div class="sidebar-brand-text">
            <div class="name">MA Sunan Pandanaran</div>
            <div class="tagline">Sistem Informasi Madrasah</div>
        </div>
    </div>

    <!-- Dashboard -->
    <div class="sidebar-section">
        <div class="sidebar-section-label">Utama</div>
    </div>
    <ul class="sidebar-nav">
        <li class="sidebar-nav-item">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
        </li>
    </ul>

    @if(auth()->user()->isAdmin() || auth()->user()->isGuru() || auth()->user()->isSiswa())
    <div class="sidebar-section">
        <div class="sidebar-section-label">E-Learning</div>
    </div>
    <ul class="sidebar-nav">
        <li class="sidebar-nav-item">
            <a href="{{ route('lms.courses') }}" class="{{ request()->routeIs('lms.*') ? 'active' : '' }}">
                <i class="bi bi-mortarboard-fill"></i> Kelas & Mata Pelajaran
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="{{ route('exam_cards.student') }}" class="{{ request()->routeIs('exam_cards.student') ? 'active' : '' }}">
                <i class="bi bi-card-heading"></i> Kartu Ujian (UTS & UAS)
            </a>
        </li>
    </ul>
    @endif

    @if(auth()->user()->isSiswa())
    <div class="sidebar-section">
        <div class="sidebar-section-label">Keuangan</div>
    </div>
    <ul class="sidebar-nav">
        <li class="sidebar-nav-item">
            <a href="{{ route('payment.student.bills') }}" class="{{ request()->routeIs('payment.student.*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Tagihan SPP & Biaya
            </a>
        </li>
    </ul>
    @endif

    @if(auth()->user()->isBendahara() || auth()->user()->isAdmin())
    <div class="sidebar-section">
        <div class="sidebar-section-label">Keuangan & SPP</div>
    </div>
    <ul class="sidebar-nav">
        <li class="sidebar-nav-item">
            <a href="{{ route('payment.finance.index') }}" class="{{ request()->routeIs('payment.finance.index') ? 'active' : '' }}">
                <i class="bi bi-wallet2"></i> Verifikasi Pembayaran
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="{{ route('payment.finance.generate') }}" class="{{ request()->routeIs('payment.finance.generate') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-plus-fill"></i> Generate Tagihan
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="{{ route('payment.finance.report') }}" class="{{ request()->routeIs('payment.finance.report') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line-fill"></i> Laporan Keuangan
            </a>
        </li>
    </ul>
    @endif



    @if(auth()->user()->isAdmin())
    <div class="sidebar-section">
        <div class="sidebar-section-label">Administrasi</div>
    </div>
    <ul class="sidebar-nav">
        <li class="sidebar-nav-item">
            <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Manajemen Pengguna
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="{{ route('admin.posts') }}" class="{{ request()->routeIs('admin.posts*') && !in_array(request('category'), ['prestasi', 'agenda']) ? 'active' : '' }}">
                <i class="bi bi-newspaper"></i> Berita & Pengumuman
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="{{ route('admin.posts', ['category' => 'agenda']) }}" class="{{ request()->routeIs('admin.posts*') && request('category') === 'agenda' ? 'active' : '' }}">
                <i class="bi bi-calendar-event-fill"></i> Kelola Agenda
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="{{ route('admin.posts', ['category' => 'prestasi']) }}" class="{{ request()->routeIs('admin.posts*') && request('category') === 'prestasi' ? 'active' : '' }}">
                <i class="bi bi-trophy-fill"></i> Jejak Keberhasilan
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="{{ route('admin.classes') }}" class="{{ request()->routeIs('admin.classes*') ? 'active' : '' }}">
                <i class="bi bi-building-fill"></i> Kelas & Rombel
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="{{ route('admin.registrations') }}" class="{{ request()->routeIs('admin.registrations*') ? 'active' : '' }}">
                <i class="bi bi-pencil-square"></i> PPDB Pendaftaran
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="{{ route('admin.messages') }}" class="{{ request()->routeIs('admin.messages*') ? 'active' : '' }}">
                <i class="bi bi-envelope-paper-fill"></i> Pesan Masuk
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="{{ route('admin.exam_cards') }}" class="{{ request()->routeIs('admin.exam_cards*') ? 'active' : '' }}">
                <i class="bi bi-card-checklist"></i> Manajemen Kartu Ujian
            </a>
        </li>
    </ul>
    @endif

    <div class="sidebar-section">
        <div class="sidebar-section-label">Lainnya</div>
    </div>
    <ul class="sidebar-nav">
        <li class="sidebar-nav-item">
            <a href="{{ route('landing') }}" target="_blank">
                <i class="bi bi-globe2"></i> Website Madrasah
            </a>
        </li>
    </ul>

    <!-- User Profile & Logout -->
    <div class="sidebar-user">
        <div class="sidebar-user-info">
            <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div style="overflow: hidden;">
                <div class="sidebar-user-name" style="word-break: break-word; line-height: 1.3;">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-role role-{{ auth()->user()->role }}" style="display: inline-block; margin-top: 2px;">
                    {{ ucfirst(auth()->user()->role) }}
                </div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-logout">
                <i class="bi bi-box-arrow-left"></i> Keluar
            </button>
        </form>
    </div>
</aside>

<!-- ─── MAIN CONTENT ───────────────────────────────────────────────────────── -->
<div class="main-content">
    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-left">
            <h2>@yield('page_title', 'Dashboard')</h2>
            @hasSection('breadcrumb')
                <span class="topbar-breadcrumb">/ @yield('breadcrumb')</span>
            @endif
        </div>
        <div class="topbar-right">
            @php
                $user = auth()->user();
                $pendingPPDB = $user->isAdmin() ? \App\Models\Registration::where('status','pending')->count() : 0;
                $messagesCount = $user->isAdmin() ? \App\Models\ContactMessage::count() : 0;
                $pendingPayments = ($user->isAdmin() || $user->isBendahara()) ? \App\Models\Payment::where('status', 'pending')->count() : 0;
                $unpaidBills = $user->isSiswa() ? \App\Models\StudentBill::where('student_id', $user->id)->where('status', 'unpaid')->count() : 0;
                
                $latestPosts = \App\Models\Post::latest()->take(3)->get();
                $totalNotifCount = $pendingPPDB + $messagesCount + $pendingPayments + $unpaidBills + $latestPosts->count();
                $userId = $user->id;
            @endphp

            <div style="position: relative;">
                <div class="topbar-bell" id="bellBtn" onclick="toggleNotifications()" title="Notifikasi & Pengumuman">
                    <i class="bi bi-bell-fill"></i>
                    <span class="notification-badge" id="notifBadge" style="display: none;">{{ $totalNotifCount }}</span>
                </div>

                <!-- Dropdown Modal -->
                <div class="notification-dropdown" id="notifDropdown">
                    <div class="notif-header">
                        <h4><i class="bi bi-bell"></i> Notifikasi & Pengumuman</h4>
                        <span class="badge badge-emerald" id="notifHeaderBadge">{{ $totalNotifCount }} Baru</span>
                    </div>
                    <div class="notif-body">
                        @if(auth()->user()->isAdmin())
                            @if($pendingPPDB > 0)
                            <a href="{{ route('admin.registrations') }}" class="notif-item" data-notif-id="ppdb" onclick="markSingleNotifAsRead('ppdb')">
                                <div class="notif-icon bg-amber"><i class="bi bi-person-plus-fill"></i></div>
                                <div>
                                    <div class="notif-title">Pendaftaran PPDB Baru</div>
                                    <div class="notif-desc">Ada {{ $pendingPPDB }} calon siswa menunggu verifikasi panitia.</div>
                                </div>
                            </a>
                            @endif

                            @if($messagesCount > 0)
                            <a href="{{ route('admin.messages') }}" class="notif-item" data-notif-id="messages" onclick="markSingleNotifAsRead('messages')">
                                <div class="notif-icon bg-blue"><i class="bi bi-envelope-fill"></i></div>
                                <div>
                                    <div class="notif-title">Pesan & Lupa Password</div>
                                    <div class="notif-desc">Ada {{ $messagesCount }} pesan permohonan masuk di Kotak Pesan.</div>
                                </div>
                            </a>
                            @endif
                        @endif

                        @if(auth()->user()->isAdmin() || auth()->user()->isBendahara())
                            @if($pendingPayments > 0)
                            <a href="{{ route('payment.finance.index') }}" class="notif-item" data-notif-id="payments" onclick="markSingleNotifAsRead('payments')">
                                <div class="notif-icon bg-amber"><i class="bi bi-credit-card-fill"></i></div>
                                <div>
                                    <div class="notif-title">Verifikasi Pembayaran</div>
                                    <div class="notif-desc">Ada {{ $pendingPayments }} pembayaran menunggu verifikasi.</div>
                                </div>
                            </a>
                            @endif
                        @endif

                        @if(auth()->user()->isSiswa())
                            @if($unpaidBills > 0)
                            <a href="{{ route('payment.student.bills') }}" class="notif-item" data-notif-id="bills" onclick="markSingleNotifAsRead('bills')">
                                <div class="notif-icon bg-red"><i class="bi bi-exclamation-circle-fill" style="color: white;"></i></div>
                                <div>
                                    <div class="notif-title">Tagihan Belum Lunas</div>
                                    <div class="notif-desc">Anda memiliki {{ $unpaidBills }} tagihan aktif yang belum dibayar.</div>
                                </div>
                            </a>
                            @endif
                        @endif

                        @foreach($latestPosts as $post)
                        <a href="#" target="_blank" class="notif-item" data-notif-id="post-{{ $post->id }}" onclick="markSingleNotifAsRead('post-{{ $post->id }}')" style="cursor:default">
                            <div class="notif-icon bg-emerald"><i class="bi bi-newspaper"></i></div>
                            <div>
                                <div class="notif-title">{{ Str::limit($post->title, 38) }}</div>
                                <div class="notif-desc"><i class="bi bi-clock"></i> {{ $post->published_at ? $post->published_at->diffForHumans() : 'Baru saja' }}</div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <a href="{{ route('profile') }}" class="topbar-user" title="Lihat Profil Saya">
                <div class="topbar-user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <span class="topbar-user-name">{{ Str::words(auth()->user()->name, 2) }}</span>
                <i class="bi bi-person-circle" style="color: var(--slate-400); font-size: 0.9rem; margin-left: 2px;"></i>
            </a>
        </div>
    </div>

    <!-- Modern Pop-up Card Toast Notifications -->
    <div id="toastNotificationContainer" style="
        position: fixed;
        top: 1.25rem;
        right: 1.25rem;
        z-index: 999999;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        max-width: 440px;
        width: calc(100% - 2.5rem);
        pointer-events: none;
    ">
        @if(session('success'))
            @php
                $isPayment = str_contains(strtolower(session('success')), 'pembayaran') || str_contains(strtolower(session('success')), 'verifikasi');
            @endphp
            <div class="popup-toast-card popup-toast-success" style="pointer-events: auto;">
                <div class="toast-card-inner">
                    <div class="toast-card-badge" style="background: linear-gradient(135deg, #059669, #047857);">
                        <i class="bi {{ $isPayment ? 'bi-patch-check-fill' : 'bi-check-circle-fill' }}"></i>
                    </div>
                    <div class="toast-card-content">
                        <div class="toast-card-header">
                            <span class="toast-card-tag" style="background: #ecfdf5; color: #047857;">
                                {{ $isPayment ? '💳 Verifikasi Pembayaran' : 'Sukses' }}
                            </span>
                            <span class="toast-card-time">Baru saja</span>
                        </div>
                        <h4 class="toast-card-title">{{ $isPayment ? 'Verifikasi Berhasil Disimpan!' : 'Berhasil!' }}</h4>
                        <p class="toast-card-text">{{ session('success') }}</p>
                    </div>
                    <button type="button" class="toast-card-close" onclick="dismissToast(this.closest('.popup-toast-card'))" title="Tutup">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="toast-progress-bar"><div class="toast-progress-fill" style="background: #059669;"></div></div>
            </div>
        @endif

        @if(session('error'))
            @php
                $isPayment = str_contains(strtolower(session('error')), 'pembayaran') || str_contains(strtolower(session('error')), 'tolak');
            @endphp
            <div class="popup-toast-card popup-toast-danger" style="pointer-events: auto;">
                <div class="toast-card-inner">
                    <div class="toast-card-badge" style="background: linear-gradient(135deg, #dc2626, #b91c1c);">
                        <i class="bi {{ $isPayment ? 'bi-shield-x' : 'bi-exclamation-octagon-fill' }}"></i>
                    </div>
                    <div class="toast-card-content">
                        <div class="toast-card-header">
                            <span class="toast-card-tag" style="background: #fef2f2; color: #b91c1c;">
                                {{ $isPayment ? '💳 Verifikasi Pembayaran' : 'Pemberitahuan' }}
                            </span>
                            <span class="toast-card-time">Baru saja</span>
                        </div>
                        <h4 class="toast-card-title">{{ $isPayment ? 'Pembayaran Ditolak' : 'Terjadi Kesalahan' }}</h4>
                        <p class="toast-card-text">{{ session('error') }}</p>
                    </div>
                    <button type="button" class="toast-card-close" onclick="dismissToast(this.closest('.popup-toast-card'))" title="Tutup">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="toast-progress-bar"><div class="toast-progress-fill" style="background: #dc2626;"></div></div>
            </div>
        @endif

        @if(session('info'))
            <div class="popup-toast-card popup-toast-info" style="pointer-events: auto;">
                <div class="toast-card-inner">
                    <div class="toast-card-badge" style="background: linear-gradient(135deg, #0284c7, #0369a1);">
                        <i class="bi bi-info-circle-fill"></i>
                    </div>
                    <div class="toast-card-content">
                        <div class="toast-card-header">
                            <span class="toast-card-tag" style="background: #f0f9ff; color: #0369a1;">Informasi</span>
                            <span class="toast-card-time">Baru saja</span>
                        </div>
                        <h4 class="toast-card-title">Informasi Sistem</h4>
                        <p class="toast-card-text">{{ session('info') }}</p>
                    </div>
                    <button type="button" class="toast-card-close" onclick="dismissToast(this.closest('.popup-toast-card'))" title="Tutup">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="toast-progress-bar"><div class="toast-progress-fill" style="background: #0284c7;"></div></div>
            </div>
        @endif

        @if($errors->any())
            <div class="popup-toast-card popup-toast-danger" style="pointer-events: auto;">
                <div class="toast-card-inner">
                    <div class="toast-card-badge" style="background: linear-gradient(135deg, #dc2626, #b91c1c);">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div class="toast-card-content">
                        <div class="toast-card-header">
                            <span class="toast-card-tag" style="background: #fef2f2; color: #b91c1c;">Validasi Gagal</span>
                            <span class="toast-card-time">Baru saja</span>
                        </div>
                        <h4 class="toast-card-title">Periksa Input Anda</h4>
                        <div class="toast-card-text">
                            @foreach($errors->all() as $e)
                                <div>• {{ $e }}</div>
                            @endforeach
                        </div>
                    </div>
                    <button type="button" class="toast-card-close" onclick="dismissToast(this.closest('.popup-toast-card'))" title="Tutup">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="toast-progress-bar"><div class="toast-progress-fill" style="background: #dc2626;"></div></div>
            </div>
        @endif
    </div>

    <style>
        .popup-toast-card {
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.22), 0 0 0 1px rgba(15, 23, 42, 0.06);
            overflow: hidden;
            animation: toastSlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            transition: all 0.3s ease;
            position: relative;
        }
        .popup-toast-card.hide {
            opacity: 0;
            transform: translateX(110%);
            margin-top: -80px;
        }
        .toast-card-inner {
            padding: 1.15rem 1.25rem 1rem;
            display: flex;
            gap: 0.9rem;
            align-items: flex-start;
        }
        .toast-card-badge {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
            box-shadow: 0 8px 18px rgba(0,0,0,0.12);
        }
        .toast-card-content {
            flex: 1;
            min-width: 0;
        }
        .toast-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.25rem;
        }
        .toast-card-tag {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 2px 7px;
            border-radius: 6px;
        }
        .toast-card-time {
            font-size: 0.72rem;
            color: #94a3b8;
        }
        .toast-card-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0 0 0.2rem 0;
            line-height: 1.3;
        }
        .toast-card-text {
            font-size: 0.82rem;
            color: #64748b;
            margin: 0;
            line-height: 1.45;
        }
        .toast-card-close {
            background: transparent;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 0.85rem;
            padding: 4px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s;
        }
        .toast-card-close:hover {
            background: #f1f5f9;
            color: #1e293b;
        }
        .toast-progress-bar {
            height: 3px;
            background: #f1f5f9;
            width: 100%;
            overflow: hidden;
        }
        .toast-progress-fill {
            height: 100%;
            width: 100%;
            animation: toastProgress 5s linear forwards;
        }
        @keyframes toastSlideIn {
            from {
                opacity: 0;
                transform: translateX(60px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }
        @keyframes toastProgress {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>

    <!-- Page Content -->
    <div class="page-content">
        @yield('content')
    </div>
</div>

<script>
    function dismissToast(toastEl) {
        if (!toastEl) return;
        toastEl.classList.add('hide');
        setTimeout(() => {
            toastEl.remove();
        }, 320);
    }

    // Auto dismiss toasts after 5s
    document.querySelectorAll('.popup-toast-card').forEach(toast => {
        setTimeout(() => {
            dismissToast(toast);
        }, 5000);
    });

    const CURRENT_USER_ID = {{ $userId }};
    const READ_ITEMS_KEY = 'lms_read_notif_items_user_' + CURRENT_USER_ID;

    function getReadItems() {
        try {
            return JSON.parse(localStorage.getItem(READ_ITEMS_KEY) || '[]');
        } catch (e) {
            return [];
        }
    }

    function saveReadItems(readArray) {
        localStorage.setItem(READ_ITEMS_KEY, JSON.stringify(readArray));
    }

    function updateNotifBadges() {
        const readArray = getReadItems();
        const items = document.querySelectorAll('.notif-item[data-notif-id]');
        let unreadCount = 0;

        items.forEach(item => {
            const id = item.getAttribute('data-notif-id');
            if (readArray.includes(id)) {
                item.style.opacity = '0.55';
            } else {
                item.style.opacity = '1';
                unreadCount++;
            }
        });

        const badge = document.getElementById('notifBadge');
        const headerBadge = document.getElementById('notifHeaderBadge');

        if (badge) {
            if (unreadCount > 0) {
                badge.textContent = unreadCount;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }

        if (headerBadge) {
            if (unreadCount > 0) {
                headerBadge.textContent = unreadCount + ' Baru';
                headerBadge.style.background = '#d1fae5';
                headerBadge.style.color = '#047857';
            } else {
                headerBadge.textContent = 'Semua Dibaca';
                headerBadge.style.background = '#e2e8f0';
                headerBadge.style.color = '#64748b';
            }
        }
    }

    function markSingleNotifAsRead(notifId) {
        let readArray = getReadItems();
        if (!readArray.includes(notifId)) {
            readArray.push(notifId);
            saveReadItems(readArray);
        }
        updateNotifBadges();
    }

    // Toggle Notification Dropdown
    function toggleNotifications() {
        const dropdown = document.getElementById('notifDropdown');
        dropdown.classList.toggle('show');
    }

    document.addEventListener('click', function(e) {
        const bellBtn = document.getElementById('bellBtn');
        const dropdown = document.getElementById('notifDropdown');
        if (bellBtn && dropdown && !bellBtn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });

    document.addEventListener('DOMContentLoaded', updateNotifBadges);
</script>
@yield('scripts')
</body>
</html>
