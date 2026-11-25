<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) . ' - EduTask Admin' : 'EduTask Admin' ?></title>
    <!-- Self-hosted fonts/icons (offline LAN) -->
    <link rel="stylesheet" href="<?= base_url('assets/fonts/fonts.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/fontawesome/css/all.min.css') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary: #2563EB;
            --primary-hover: #1D4ED8;
            --primary-dark: #004AC6;
            --primary-light: #E6EEFF;
            --bg-body: #F8F9FF;
            --sidebar-bg: #F8F9FF;
            --text-main: #0D1C2E;
            --text-muted: #64748B;
            --card-border: #E2E8F0;
            --radius-lg: 16px;
            --radius-md: 10px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Sidebar */
        aside.sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            transition: all 0.3s ease;
            transform: translateX(0);
        }

        @media (max-width: 767px) {
            aside.sidebar {
                transform: translateX(-100%);
            }
            aside.sidebar.is-open {
                transform: translateX(0);
                box-shadow: 10px 0 30px rgba(0,0,0,0.1);
            }
            .main-wrapper {
                margin-left: 0 !important;
            }
        }

        .sidebar-brand {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .brand-badge {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: #FFF;
            font-size: 18px;
        }

        .brand-title {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .sidebar-nav {
            padding: 24px 16px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            color: #94A3B8;
            text-decoration: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            color: #FFFFFF;
            background-color: rgba(255, 255, 255, 0.05);
        }

        .nav-link.active {
            color: #FFFFFF;
            background-color: var(--primary);
            font-weight: 600;
        }

        .nav-link i {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .user-widget {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 10px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.03);
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: #1E293B;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #38BDF8;
            overflow: hidden;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-info {
            flex: 1;
            overflow: hidden;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: #F8FAFC;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 11px;
            color: #94A3B8;
            text-transform: capitalize;
        }

        /* Main Content Container */
        .main-wrapper {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header.top-header {
            height: 70px;
            background: #FFFFFF;
            border-bottom: 1px solid var(--card-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .header-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-main);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .btn-logout {
            padding: 8px 16px;
            border-radius: 8px;
            background-color: #FEE2E2;
            color: #B91C1C;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background-color: #DC2626;
            color: #FFFFFF;
        }

        main.content-area {
            padding: 32px;
            flex: 1;
        }

        /* Flash Messages */
        .alert {
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background-color: #DEF7EC;
            color: #03543F;
            border: 1px solid #BCF0DA;
        }

        .alert-danger {
            background-color: #FDE8E8;
            color: #9B1C1C;
            border: 1px solid #FBD5D5;
        }

        /* Reusable Card */
        .card {
            background: #FFFFFF;
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            margin-bottom: 24px;
        }

        .card-header-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #FFF;
            padding: 10px 18px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-secondary {
            background-color: #F1F5F9;
            color: #475569;
            padding: 10px 18px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            border: 1px solid var(--card-border);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-secondary:hover {
            background-color: #E2E8F0;
            color: #0F172A;
        }

        .btn-danger-light {
            background-color: #FEE2E2;
            color: #B91C1C;
            padding: 8px 14px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-danger-light:hover {
            background-color: #DC2626;
            color: #FFFFFF;
        }

        /* Table styles */
        .data-table-container {
            width: 100%;
            overflow-x: auto;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 14px;
        }

        table.data-table th {
            padding: 12px 16px;
            background-color: #F8FAFC;
            color: var(--text-muted);
            font-weight: 600;
            border-bottom: 1px solid var(--card-border);
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.05em;
        }

        table.data-table td {
            padding: 16px;
            border-bottom: 1px solid var(--card-border);
            color: var(--text-main);
            vertical-align: middle;
        }

        table.data-table tbody tr:hover {
            background-color: #F8FAFC;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            text-transform: capitalize;
        }

        .badge-admin {
            background-color: #EDE9FE;
            color: #6D28D9;
        }

        .badge-guru {
            background-color: #E0E7FF;
            color: #3730A3;
        }

        .badge-siswa {
            background-color: #DCFCE7;
            color: #15803D;
        }

        /* ─── Academic Precision theme ─────────────────────────────────── */
        aside.sidebar {
            background: var(--sidebar-bg) !important;
            color: var(--text-main);
            border-right: 1px solid var(--card-border);
        }
        .sidebar-brand {
            border-bottom: 1px solid var(--card-border);
        }
        .brand-badge {
            background: #0D1C2E;
            color: #FFFFFF;
        }
        .brand-title {
            color: var(--text-main);
        }
        .brand-title span { color: #737686 !important; opacity: 1 !important; }
        .sidebar-nav { padding: 18px 16px; }
        .nav-link {
            color: #434655;
            border-radius: 12px;
            padding: 11px 14px;
            font-size: 0.9rem;
        }
        .nav-link:hover {
            color: var(--primary-dark);
            background: var(--primary-light);
        }
        .nav-link.active {
            color: #FFFFFF;
            background: var(--primary-dark);
            box-shadow: 0 4px 12px rgba(0, 74, 198, 0.18);
        }
        .sidebar-footer {
            border-top: 1px solid var(--card-border);
            background: transparent;
        }
        .user-widget {
            background: var(--primary-light);
            border: 1px solid #D5E3FC;
        }
        .user-avatar {
            background: #FFFFFF;
            color: var(--primary-dark);
        }
        .user-name { color: var(--text-main); }
        .user-role { color: #737686; }
        header.top-header {
            height: 76px;
            background: rgba(248, 249, 255, 0.94);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--card-border);
            gap: 20px;
        }
        .header-title { font-size: 1.05rem; color: var(--text-main); }
        .topbar-search {
            flex: 1;
            max-width: 560px;
            display: flex;
            align-items: center;
            gap: 10px;
            background: #EFF4FF;
            border: 1px solid #DCE9FF;
            border-radius: 999px;
            padding: 10px 16px;
            color: #737686;
        }
        .topbar-search input {
            border: 0;
            outline: 0;
            background: transparent;
            font-family: inherit;
            font-size: 0.88rem;
            color: var(--text-main);
            width: 100%;
        }
        .topbar-search input::placeholder { color: #737686; }
        .header-actions { gap: 12px; }
        .topbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-left: 14px;
            border-left: 1px solid var(--card-border);
        }
        .topbar-user .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--primary-dark);
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.82rem;
            overflow: hidden;
        }
        .topbar-user .avatar img { width: 100%; height: 100%; object-fit: cover; }
        .topbar-user .name {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1.2;
            white-space: nowrap;
        }
        .topbar-user .role {
            font-size: 0.7rem;
            color: #737686;
            line-height: 1.4;
        }
        .btn-logout {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            padding: 0;
            justify-content: center;
            background: #FFFFFF;
            border: 1px solid var(--card-border);
            color: #737686;
        }
        .btn-logout:hover { background: #FFE4E6; color: #BE123C; }
        .card { border-radius: 18px; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03); }
        .content-area { padding: 28px 32px 40px; max-width: 1500px; width: 100%; margin: 0 auto; }
        .btn-primary { background: var(--primary-dark); }
        .btn-primary:hover { background: #003EA8; }
        @media (max-width: 920px) {
            .topbar-search { display: none; }
            .topbar-user .name, .topbar-user .role { display: none; }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-badge">E</div>
            <div class="brand-title">EduTask <span style="font-size:12px; opacity:0.6; font-weight:normal;">Admin</span></div>
        </div>

        <nav class="sidebar-nav">
            <a href="<?= site_url('admin/dashboard') ?>" class="nav-link <?= (isset($active) && $active === 'dashboard') ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?= site_url('admin/pengguna') ?>" class="nav-link <?= (isset($active) && $active === 'pengguna') ? 'active' : '' ?>">
                <i class="fa-solid fa-users"></i>
                <span>Pengguna</span>
            </a>
            <a href="<?= site_url('admin/kelas') ?>" class="nav-link <?= (isset($active) && $active === 'kelas') ? 'active' : '' ?>">
                <i class="fa-solid fa-people-group"></i>
                <span>Kelas</span>
            </a>
            <a href="<?= site_url('admin/mapel') ?>" class="nav-link <?= (isset($active) && $active === 'mapel') ? 'active' : '' ?>">
                <i class="fa-solid fa-book-open"></i>
                <span>Mapel</span>
            </a>
            <a href="<?= site_url('admin/laporan') ?>" class="nav-link <?= (isset($active) && $active === 'laporan') ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-column"></i>
                <span>Laporan</span>
            </a>
            <a href="<?= site_url('admin/profil') ?>" class="nav-link <?= (isset($active) && $active === 'profil') ? 'active' : '' ?>">
                <i class="fa-solid fa-user-gear"></i>
                <span>Profil</span>
            </a>
            <a href="<?= site_url('admin/sync') ?>" class="nav-link <?= (isset($active) && $active === 'sync') ? 'active' : '' ?>">
                <i class="fa-solid fa-rotate"></i>
                <span>Sinkronisasi</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-widget">
                <div class="user-avatar">
                    <?php if (!empty($this->session->userdata('foto'))): ?>
                        <img src="<?= base_url('uploads/foto/' . $this->session->userdata('foto')) ?>" alt="Avatar">
                    <?php else: ?>
                        <i class="fa-solid fa-user"></i>
                    <?php endif; ?>
                </div>
                <div class="user-info">
                    <div class="user-name"><?= htmlspecialchars($this->session->userdata('nama') ?: 'Admin') ?></div>
                    <div class="user-role"><?= htmlspecialchars($this->session->userdata('role') ?: 'Administrator') ?></div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <header class="top-header">
            <div class="flex items-center gap-4">
                <button id="sidebarToggle" class="md:hidden text-2xl text-slate-600">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <form class="topbar-search hidden md:flex" action="<?= site_url('admin/pengguna') ?>" method="get">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="search" name="search" placeholder="Cari pengguna, kelas, atau laporan..." autocomplete="off">
                </form>
            </div>
            <div class="header-actions">
                <div class="topbar-user">
                    <div class="avatar">
                        <?php if (!empty($this->session->userdata('foto'))): ?>
                            <img src="<?= base_url('uploads/foto/' . $this->session->userdata('foto')) ?>" alt="Avatar">
                        <?php else: ?>
                            <?= htmlspecialchars(strtoupper(substr($this->session->userdata('nama') ?: 'A', 0, 1))) ?>
                        <?php endif; ?>
                    </div>
                    <div>
                        <div class="name"><?= htmlspecialchars($this->session->userdata('nama') ?: 'Admin') ?></div>
                        <div class="role">Administrator</div>
                    </div>
                </div>
                <a href="<?= site_url('auth/logout') ?>" class="btn-logout" title="Keluar" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </a>
            </div>
        </header>

        <main class="content-area">
            <!-- Flash message notifications -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <span><?= $this->session->flashdata('success') ?></span>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span><?= $this->session->flashdata('error') ?></span>
                </div>
            <?php endif; ?>
