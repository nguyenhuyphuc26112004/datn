<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hệ thống Quản trị') - Admin Panel</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

    <style>
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --primary-light: #d1fae5;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --sidebar-active: #0f172a;
            --sidebar-text: #94a3b8;
            --sidebar-text-active: #ffffff;
            --bg-main: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --radius-md: 12px;
            --radius-lg: 16px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.04);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-main);
            color: var(--text-main);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        .layout-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ==================== SIDEBAR ==================== */
        .sidebar {
            width: 280px;
            background: #1e293b;
            color: #cbd5e1;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            border-right: 1px solid #334155;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-brand img {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.15);
        }

        .sidebar-brand-text {
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand-title {
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.3px;
        }

        .sidebar-brand-sub {
            font-size: 12px;
            color: #94a3b8;
        }

        .sidebar-menu {
            padding: 20px 14px 0 14px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .menu-section-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #64748b;
            font-weight: 700;
            padding: 0 12px;
            margin-bottom: 8px;
        }

        .nav-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .nav-link i {
            font-size: 16px;
            width: 20px;
            text-align: center;
            transition: transform 0.2s ease;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.06);
            color: #ffffff;
            transform: translateX(3px);
        }

        .nav-link:hover i {
            color: #10b981;
        }

        .nav-link.active {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);
        }

        .nav-link.active i {
            color: #ffffff;
        }

        .btn-sidebar-logout {
            background: none;
            border: none;
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            color: #f87171;
            font-size: 14px;
            font-weight: 500;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .btn-sidebar-logout:hover {
            background: rgba(239, 68, 68, 0.12);
            color: #ef4444;
            transform: translateX(3px);
        }

        /* ==================== MAIN CONTENT AREA ==================== */
        .main-container {
            flex: 1;
            padding: 30px 40px;
            overflow-x: auto;
            max-width: 1650px;
        }

        /* Top User Profile Header */
        .top-navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            padding: 16px 24px;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            margin-bottom: 28px;
        }

        .user-welcome-info {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .avatar-circle {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 3px 8px rgba(59, 130, 246, 0.25);
        }

        .user-meta {
            display: flex;
            flex-direction: column;
        }

        .user-meta-name {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-main);
        }

        .user-meta-sub {
            font-size: 12px;
            color: var(--text-muted);
        }

        .top-navbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .current-date-badge {
            background: #f1f5f9;
            color: #475569;
            font-size: 13px;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* Role Badges */
        .role-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .role-admin {
            background-color: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .role-staff {
            background-color: #e0f2fe;
            color: #0284c7;
            border: 1px solid #bae6fd;
        }

        .role-user {
            background-color: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }

        /* Page Header Title */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
        }

        .page-title-group h1 {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-title-group h1 i {
            color: #10b981;
        }

        .page-title-group p {
            color: #64748b;
            font-size: 14px;
            margin-top: 4px;
        }

        .btn-cta-primary {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #ffffff;
            text-decoration: none;
            padding: 11px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            transition: all 0.25s ease;
            border: none;
            cursor: pointer;
        }

        .btn-cta-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
            color: #ffffff;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: #ffffff;
            padding: 18px 20px;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: #cbd5e1;
        }

        .stat-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .stat-content {
            display: flex;
            flex-direction: column;
        }

        .stat-label {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }

        .stat-value {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 2px;
        }

        /* ==================== ALERTS ==================== */
        .alert-box {
            padding: 14px 18px;
            border-radius: var(--radius-md);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 500;
            box-shadow: var(--shadow-sm);
            animation: fadeIn 0.3s ease;
        }

        .alert-box-success {
            background-color: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-box-error {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* ==================== CARD CONTAINER ==================== */
        .card-container {
            background: #ffffff;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .card-header-tools {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            background: #ffffff;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .search-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            max-width: 540px;
        }

        .search-input-box {
            position: relative;
            flex: 1;
        }

        .search-input-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 14px;
        }

        .search-input-box input {
            width: 100%;
            padding: 10px 14px 10px 38px;
            font-size: 14px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            background: #f8fafc;
            color: var(--text-main);
            outline: none;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .search-input-box input:focus {
            background: #ffffff;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
        }

        .btn-search-action {
            background: #0f172a;
            color: #ffffff;
            border: none;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
            font-family: inherit;
            white-space: nowrap;
        }

        .btn-search-action:hover {
            background: #1e293b;
            transform: translateY(-1px);
        }

        .btn-reset-action {
            background: #f1f5f9;
            color: #475569;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .btn-reset-action:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .result-count-badge {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .result-count-badge strong {
            color: #0f172a;
        }

        /* Tabs Filter */
        .filter-tabs-wrapper {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            padding: 12px 24px;
            background: #f8fafc;
            border-bottom: 1px solid var(--border-color);
        }

        .tab-filter-btn {
            text-decoration: none;
            padding: 7px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .tab-filter-btn:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        .tab-filter-btn.active {
            background: #0f172a;
            color: #ffffff;
            border-color: #0f172a;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.25);
        }

        /* ==================== DATA TABLE ==================== */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .custom-table th {
            background-color: #f8fafc;
            color: #475569;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 14px 10px;
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
        }

        .custom-table td {
            padding: 10px 10px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 14px;
            vertical-align: middle;
        }

        .custom-table tr:last-child td {
            border-bottom: none;
        }

        .custom-table tr:hover td {
            background-color: #f8fafc;
        }

        /* Action Buttons */
        .actions-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 7px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
            white-space: nowrap;
        }

        .btn-action i {
            font-size: 12px;
        }

        .btn-action-view {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #dbeafe;
        }
        .btn-action-view:hover {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
            transform: translateY(-1px);
        }

        .btn-action-edit {
            background: #fffbeb;
            color: #b45309;
            border: 1px solid #fef3c7;
        }
        .btn-action-edit:hover {
            background: #d97706;
            color: #ffffff;
            border-color: #d97706;
            transform: translateY(-1px);
        }

        .btn-action-delete {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fee2e2;
        }
        .btn-action-delete:hover {
            background: #dc2626;
            color: #ffffff;
            border-color: #dc2626;
            transform: translateY(-1px);
        }

        .btn-action-lock {
            background: #fff7ed;
            color: #c2410c;
            border: 1px solid #ffedd5;
        }
        .btn-action-lock:hover {
            background: #ea580c;
            color: #ffffff;
            border-color: #ea580c;
            transform: translateY(-1px);
        }

        .btn-action-unlock {
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #dcfce7;
        }
        .btn-action-unlock:hover {
            background: #16a34a;
            color: #ffffff;
            border-color: #16a34a;
            transform: translateY(-1px);
        }

        .btn-action-products {
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }
        .btn-action-products:hover {
            background: #16a34a;
            color: #ffffff;
            border-color: #16a34a;
            transform: translateY(-1px);
        }

        /* Empty State */
        .empty-state {
            padding: 60px 20px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .empty-state-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #f1f5f9;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 16px;
        }

        .empty-state h3 {
            font-size: 18px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 6px;
        }

        .empty-state p {
            font-size: 14px;
            color: #64748b;
            max-width: 380px;
            margin-bottom: 18px;
        }

        /* ==================== PAGINATION ==================== */
        .card-footer-pagination {
            padding: 16px 24px;
            border-top: 1px solid var(--border-color);
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pagination {
            display: flex;
            list-style: none;
            gap: 6px;
            align-items: center;
        }

        .pagination li a,
        .pagination li span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .pagination li a:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #0f172a;
        }

        .pagination li.active span {
            background: #10b981;
            border-color: #10b981;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(16, 185, 129, 0.35);
        }

        .pagination li.disabled span {
            color: #cbd5e1;
            background: #f8fafc;
            border-color: #f1f5f9;
            cursor: not-allowed;
        }

        /* ==================== MODAL POPUP ==================== */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(6px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            padding: 20px;
        }

        .modal-card {
            background: #ffffff;
            padding: 32px 28px;
            border-radius: 20px;
            box-shadow: var(--shadow-xl);
            width: 100%;
            max-width: 480px;
            text-align: center;
            animation: modalPop 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }

        @keyframes modalPop {
            0% { opacity: 0; transform: scale(0.92) translateY(10px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }

        .modal-icon-danger {
            width: 64px;
            height: 64px;
            background: #fef2f2;
            color: #ef4444;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin: 0 auto 16px auto;
            border: 4px solid #fee2e2;
        }

        .modal-card h3 {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .modal-desc {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 18px;
            line-height: 1.5;
        }

        .modal-box-info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 24px;
            font-size: 14px;
            color: #334155;
            line-height: 1.5;
        }

        .modal-btn-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .modal-btn {
            flex: 1;
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .btn-modal-cancel {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }
        .btn-modal-cancel:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .btn-modal-confirm {
            background: #dc2626;
            color: #ffffff;
            box-shadow: 0 3px 8px rgba(220, 38, 38, 0.3);
        }
        .btn-modal-confirm:hover {
            background: #b91c1c;
            transform: translateY(-1px);
        }
        .sidebar-bottom-img {
    text-align: center;
    padding: 0;
    margin-top: 90px; 
}

.sidebar-bottom-img img {
    max-width: 130px; 
    width: 100%;      
    height: auto;     
    object-fit: contain;
}
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 1024px) {
            .layout-wrapper {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-container {
                padding: 20px;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="layout-wrapper">
        <!-- ==================== SIDEBAR CHUNG ==================== -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <img src="{{ asset('images/logo.jpg') }}">
                <div class="sidebar-brand-text">
                    <span class="sidebar-brand-title">Admin Dashboard</span>
                    <span class="sidebar-brand-sub">Hệ thống quản lý</span>
                </div>
            </div>

            <div class="sidebar-menu">
                <div>
                    <div class="menu-section-title">Danh mục quản lý</div>
                    <ul class="nav-list">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                                <i class="fa-solid fa-gauge-high"></i> Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.user.index') }}" class="nav-link {{ request()->routeIs('admin.user*') ? 'active' : '' }}">
                                <i class="fa-solid fa-users"></i> Quản lý người dùng
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.sanpham.index') }}" class="nav-link {{ request()->routeIs('admin.sanpham*') ? 'active' : '' }}">
                                <i class="fa-solid fa-box"></i> Quản lý sản phẩm
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.donhang.index') }}" class="nav-link {{ request()->routeIs('admin.donhang*') ? 'active' : '' }}">
                                <i class="fa-solid fa-cart-shopping"></i> Quản lý đơn hàng
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.khuyenmai.index') }}" class="nav-link {{ request()->routeIs('admin.khuyenmai*') ? 'active' : '' }}">
                                <i class="fa-solid fa-ticket-simple"></i> Quản lý khuyến mãi
                            </a>
                        </li>
                        @if(Auth::check() && Auth::user()->isAdmin())
                        <li>
                            <a href="{{ route('admin.thongKe') }}" class="nav-link {{ request()->routeIs('admin.thongKe*') ? 'active' : '' }}">
                                <i class="fa-solid fa-chart-line"></i> Thống kê
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>

                <div>
                    <div class="menu-section-title">Hệ thống</div>
                    <ul class="nav-list">
                        <li>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-sidebar-logout">
                                    <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                <div class="sidebar-bottom-img">
                    <img src="{{ asset('images/logo2.png') }}" alt="Logo">
                </div>
            </div>
        </aside>

        <main class="main-container">
            <div class="top-navbar">
                <div class="user-welcome-info">
                    @php
                        $userName = Auth::user()->ho_ten ?? Auth::user()->ten_dang_nhap ?? 'Quản trị';
                        $initials = mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8');
                    @endphp
                    <div class="avatar-circle">{{ $initials }}</div>
                    <div class="user-meta">
                        <span class="user-meta-name">Xin chào, {{ $userName }} 👋</span>
                        <span class="user-meta-sub">{{ Auth::user()->email ?? 'Hệ thống Quản trị' }}</span>
                    </div>
                </div>
                <div class="top-navbar-right">
                    <div class="current-date-badge">
                        <i class="fa-regular fa-calendar"></i>
                        <span>{{ date('d/m/Y') }}</span>
                    </div>
                    @if(Auth::check())
                        @if(Auth::user()->id_vai_tro == 2)
                            <span class="role-badge role-admin"><i class="fa-solid fa-shield-halved"></i> Quản trị viên</span>
                        @elseif(Auth::user()->id_vai_tro == 3)
                            <span class="role-badge role-staff"><i class="fa-solid fa-user-tie"></i> Nhân viên</span>
                        @else
                            <span class="role-badge role-user"><i class="fa-solid fa-user"></i> Người dùng</span>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Page Content Injected Here -->
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
