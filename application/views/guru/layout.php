<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($title) ? html_escape($title) : 'EduTask' ?> — EduTask Guru</title>
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
      --card-bg: #FFFFFF;
      --card-border: #E2E8F0;
      --sidebar-bg: #F8F9FF;
      --sidebar-width: 260px;
      --text-main: #0D1C2E;
      --text-muted: #64748B;
      --badge-individu-bg: #CCFBF1;
      --badge-individu-text: #0F766E;
      --badge-kelompok-bg: #F3E8FF;
      --badge-kelompok-text: #6B21A8;
      --status-graded-bg: #DCFCE7;
      --status-graded-text: #059669;
      --status-pending-bg: #FEF3C7;
      --status-pending-text: #D97706;
      --status-overdue-bg: #FFE4E6;
      --status-overdue-text: #E11D48;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      background-color: var(--bg-body);
      color: var(--text-main);
      -webkit-font-smoothing: antialiased;
    }

    h1, h2, h3, h4, h5, h6, .brand-logo {
      font-family: 'Plus Jakarta Sans', sans-serif;
    }

    a {
      text-decoration: none;
      color: inherit;
    }

    .app-layout {
      display: flex;
      min-height: 100vh;
    }

    /* SIDEBAR */
    .sidebar {
      width: var(--sidebar-width);
      background-color: var(--sidebar-bg);
      color: #E2E8F0;
      display: flex;
      flex-direction: column;
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      z-index: 50;
      transition: all 0.3s ease;
      transform: translateX(0);
    }
    
    @media (max-width: 767px) {
      .sidebar {
        transform: translateX(-100%);
      }
      .sidebar.is-open {
        transform: translateX(0);
        box-shadow: 10px 0 30px rgba(0,0,0,0.1);
      }
      .main-wrapper {
        margin-left: 0 !important;
      }
    }

    .sidebar-header {
      padding: 24px 20px;
      display: flex;
      align-items: center;
      gap: 12px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .brand-icon {
      width: 38px;
      height: 38px;
      background: var(--primary);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #FFF;
      font-size: 1.2rem;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
    }

    .brand-logo {
      font-size: 1.25rem;
      font-weight: 700;
      color: #FFFFFF;
      letter-spacing: -0.02em;
    }

    .brand-role {
      font-size: 0.72rem;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      background: rgba(37, 99, 235, 0.25);
      color: #93C5FD;
      padding: 2px 8px;
      border-radius: 999px;
      margin-left: 4px;
      font-weight: 600;
    }

    .sidebar-nav {
      flex: 1;
      padding: 20px 12px;
      overflow-y: auto;
    }

    .nav-section-title {
      font-size: 0.7rem;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: #64748B;
      padding: 8px 12px;
      font-weight: 600;
    }

    .nav-list {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .nav-item a {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 14px;
      border-radius: 8px;
      font-size: 0.92rem;
      font-weight: 500;
      color: #94A3B8;
      transition: all 0.2s ease;
      position: relative;
    }

    .nav-item a i {
      font-size: 1.05rem;
      width: 20px;
      text-align: center;
    }

    .nav-item a:hover {
      color: #FFFFFF;
      background: rgba(255, 255, 255, 0.05);
    }

    .nav-item.active a {
      color: #FFFFFF;
      background: var(--primary-light);
      font-weight: 600;
      border-left: 3px solid var(--primary);
    }

    .sidebar-footer {
      padding: 16px;
      border-top: 1px solid rgba(255, 255, 255, 0.08);
      background: rgba(0, 0, 0, 0.15);
    }

    .user-profile-summary {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #1E293B;
      border: 2px solid rgba(255, 255, 255, 0.15);
      object-fit: cover;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #94A3B8;
      font-weight: 600;
      font-size: 0.95rem;
    }

    .user-avatar img {
      width: 100%;
      height: 100%;
      border-radius: 50%;
      object-fit: cover;
    }

    .user-meta {
      flex: 1;
      min-width: 0;
    }

    .user-name {
      font-size: 0.88rem;
      font-weight: 600;
      color: #FFFFFF;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .user-role-label {
      font-size: 0.75rem;
      color: #64748B;
    }

    .logout-btn {
      color: #94A3B8;
      padding: 6px;
      border-radius: 6px;
      transition: color 0.2s;
    }

    .logout-btn:hover {
      color: #EF4444;
    }

    /* MAIN CONTENT */
    .main-wrapper {
      flex: 1;
      margin-left: var(--sidebar-width);
      display: flex;
      flex-direction: column;
      min-width: 0;
    }

    .topbar {
      height: 72px;
      background: #FFFFFF;
      border-bottom: 1px solid var(--card-border);
      padding: 0 32px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 40;
    }

    .page-title {
      font-size: 1.35rem;
      font-weight: 700;
      color: var(--text-main);
    }

    .topbar-actions {
      display: flex;
      align-items: center;
      gap: 18px;
    }

    .page-content {
      padding: 32px;
      flex: 1;
    }

    /* FLASH MESSAGES */
    .alert {
      padding: 14px 18px;
      border-radius: 10px;
      margin-bottom: 24px;
      font-size: 0.92rem;
      display: flex;
      align-items: center;
      gap: 12px;
      border: 1px solid transparent;
    }
    .alert-success {
      background: #ECFDF5;
      color: #065F46;
      border-color: #A7F3D0;
    }
    .alert-error {
      background: #FEF2F2;
      color: #991B1B;
      border-color: #FECACA;
    }
    .alert-warning {
      background: #FFFBEB;
      color: #92400E;
      border-color: #FDE68A;
    }

    /* CARDS */
    .card {
      background: var(--card-bg);
      border: 1px solid var(--card-border);
      border-radius: 16px;
      padding: 24px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }

    .card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    .card-title {
      font-size: 1.15rem;
      font-weight: 700;
      color: var(--text-main);
    }

    /* BUTTONS */
    .btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 18px;
      font-size: 0.9rem;
      font-weight: 600;
      border-radius: 8px;
      border: 1px solid transparent;
      cursor: pointer;
      transition: all 0.2s ease;
      text-decoration: none;
      line-height: 1.25;
    }

    .btn-primary {
      background: var(--primary);
      color: #FFFFFF;
    }
    .btn-primary:hover {
      background: var(--primary-hover);
    }

    .btn-secondary {
      background: #FFFFFF;
      color: #334155;
      border-color: #CBD5E1;
    }
    .btn-secondary:hover {
      background: #F1F5F9;
    }

    .btn-danger {
      background: #FEE2E2;
      color: #B91C1C;
    }
    .btn-danger:hover {
      background: #DC2626;
      color: #FFFFFF;
    }

    .btn-sm {
      padding: 6px 12px;
      font-size: 0.82rem;
      border-radius: 6px;
    }

    /* FORMS */
    .form-group {
      margin-bottom: 20px;
    }

    .form-label {
      display: block;
      font-size: 0.88rem;
      font-weight: 600;
      color: #334155;
      margin-bottom: 8px;
    }

    .form-control {
      width: 100%;
      height: 42px;
      padding: 8px 14px;
      font-size: 0.92rem;
      font-family: inherit;
      color: var(--text-main);
      background: #FFFFFF;
      border: 1px solid #CBD5E1;
      border-radius: 8px;
      transition: all 0.2s;
    }

    textarea.form-control {
      height: auto;
      min-height: 100px;
      resize: vertical;
    }

    .form-control:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .form-text {
      font-size: 0.8rem;
      color: var(--text-muted);
      margin-top: 6px;
    }

    /* BADGES */
    .badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 4px 10px;
      font-size: 0.75rem;
      font-weight: 600;
      border-radius: 999px;
      text-transform: capitalize;
    }

    .badge-individu {
      background: var(--badge-individu-bg);
      color: var(--badge-individu-text);
    }

    .badge-kelompok {
      background: var(--badge-kelompok-bg);
      color: var(--badge-kelompok-text);
    }

    .badge-graded {
      background: var(--status-graded-bg);
      color: var(--status-graded-text);
    }

    .badge-pending {
      background: var(--status-pending-bg);
      color: var(--status-pending-text);
    }

    .badge-overdue {
      background: var(--status-overdue-bg);
      color: var(--status-overdue-text);
    }

    /* DATA TABLE */
    .table-container {
      width: 100%;
      overflow-x: auto;
      border-radius: 12px;
      border: 1px solid var(--card-border);
      background: #FFFFFF;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
      font-size: 0.9rem;
    }

    .table th {
      background: #F8FAFC;
      padding: 14px 18px;
      font-weight: 600;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      color: var(--text-muted);
      border-bottom: 1px solid var(--card-border);
    }

    .table td {
      padding: 16px 18px;
      border-bottom: 1px solid var(--card-border);
      color: #334155;
      vertical-align: middle;
    }

    .table tr:last-child td {
      border-bottom: none;
    }

    .table tbody tr:hover {
      background-color: #F8FAFC;
    }

    /* STAT CARD */
    .stat-card {
      background: #FFFFFF;
      border: 1px solid var(--card-border);
      border-radius: 16px;
      padding: 22px;
      display: flex;
      align-items: center;
      gap: 18px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }

    .stat-icon {
      width: 52px;
      height: 52px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
    }

    .stat-icon.blue { background: #EFF6FF; color: #2563EB; }
    .stat-icon.green { background: #ECFDF5; color: #059669; }
    .stat-icon.amber { background: #FFFBEB; color: #D97706; }

    .stat-value {
      font-size: 1.85rem;
      font-weight: 800;
      color: var(--text-main);
      line-height: 1.1;
      font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .stat-label {
      font-size: 0.85rem;
      color: var(--text-muted);
      margin-top: 4px;
      font-weight: 500;
    }

    /* EMPTY STATE */
    .empty-state {
      text-align: center;
      padding: 48px 24px;
      color: var(--text-muted);
    }

    .empty-state i {
      font-size: 3rem;
      color: #CBD5E1;
      margin-bottom: 16px;
    }

    .empty-state h4 {
      font-size: 1.1rem;
      color: var(--text-main);
      margin-bottom: 8px;
    }

    .empty-state p {
      font-size: 0.9rem;
      max-width: 400px;
      margin: 0 auto 20px;
    }

    /* ─── Academic Precision theme ─────────────────────────────────────── */
    .sidebar {
      background: var(--sidebar-bg) !important;
      color: var(--text-main);
      border-right: 1px solid var(--card-border);
    }
    .sidebar-header {
      padding: 20px;
      border-bottom: 1px solid var(--card-border);
    }
    .brand-icon {
      background: #0D1C2E;
      box-shadow: none;
      border-radius: 9px;
      font-size: 1rem;
    }
    .brand-logo {
      color: var(--text-main);
      font-size: 1.12rem;
    }
    .brand-role {
      background: var(--primary-light);
      color: var(--primary-dark);
      font-size: 0.65rem;
    }
    .brand-sub {
      display: block;
      font-size: 0.68rem;
      color: var(--text-muted);
      font-weight: 500;
      margin-top: 1px;
    }
    .sidebar-nav { padding: 18px 16px; }
    .nav-section-title {
      color: #737686;
      font-size: 0.64rem;
      letter-spacing: 0.12em;
      padding: 8px 12px;
    }
    .nav-item a {
      color: #434655;
      border-radius: 12px;
      font-size: 0.9rem;
      padding: 11px 14px;
    }
    .nav-item a:hover {
      color: var(--primary-dark);
      background: var(--primary-light);
    }
    .nav-item.active a {
      color: #FFFFFF;
      background: var(--primary-dark);
      border-left: none;
      box-shadow: 0 4px 12px rgba(0, 74, 198, 0.18);
    }
    .nav-item.active a i { color: #FFFFFF; }
    .sidebar-footer {
      background: transparent;
      border-top: 1px solid var(--card-border);
      padding: 14px 16px 18px;
    }
    .user-avatar {
      background: var(--primary-light);
      border-color: #D5E3FC;
      color: var(--primary-dark);
    }
    .user-name { color: var(--text-main); }
    .user-role-label { color: #737686; }
    .logout-btn { color: #737686; }

    .topbar {
      height: 76px;
      background: rgba(248, 249, 255, 0.94);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid var(--card-border);
      gap: 20px;
      padding: 0 32px;
    }
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
    .topbar-actions { gap: 12px; }
    .icon-btn {
      position: relative;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #FFFFFF;
      border: 1px solid var(--card-border);
      color: #434655;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s;
    }
    .icon-btn:hover { background: #EFF4FF; color: var(--primary-dark); }
    .notif-dot {
      position: absolute;
      top: 9px;
      right: 10px;
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: #E11D48;
      border: 2px solid #FFFFFF;
    }
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
      object-fit: cover;
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
    .page-content {
      padding: 28px 32px 40px;
      width: 100%;
      max-width: 1500px;
      margin: 0 auto;
    }
    .card {
      border-radius: 16px;
      border-color: var(--card-border);
      box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
    }
    .btn { border-radius: 10px; }
    .btn-primary { background: var(--primary-dark); }
    .btn-primary:hover { background: #003EA8; }
    .form-control { border-color: #CBD5E1; border-radius: 10px; }
    .table th { background: #EFF4FF; color: #434655; }
    .table tbody tr:hover { background: #F8F9FF; }

    @media (max-width: 820px) {
      .topbar-search { display: none; }
      .topbar-user .name, .topbar-user .role { display: none; }
      .page-content { padding: 20px 16px 32px; }
    }
  </style>
</head>
<body>
<div class="app-layout">
  <!-- SIDEBAR (260px, fixed, #0D1C2E) -->
  <aside class="sidebar">
    <div class="sidebar-header">
      <div class="brand-icon">
        <i class="fa-solid fa-graduation-cap"></i>
      </div>
      <div>
        <span class="brand-logo">EduTask</span>
        <span class="brand-role">Guru</span>
        <span class="brand-sub">Learning System</span>
      </div>
    </div>

    <div class="sidebar-nav">
      <div class="nav-section-title">Menu Utama</div>
      <?php 
        $current_seg = $this->uri->segment(2);
        if (empty($current_seg)) $current_seg = 'dashboard';
      ?>
      <ul class="nav-list">
        <li class="nav-item <?= ($current_seg == 'dashboard') ? 'active' : '' ?>">
          <a href="<?= site_url('guru/dashboard') ?>">
            <i class="fa-solid fa-table-cells-large"></i>
            <span>Dashboard Guru</span>
          </a>
        </li>
        <li class="nav-item <?= in_array($current_seg, ['mapel', 'tambah_mapel', 'edit_mapel', 'detail_mapel']) ? 'active' : '' ?>">
          <a href="<?= site_url('guru/mapel') ?>">
            <i class="fa-solid fa-book-open-reader"></i>
            <span>Mapel Saya</span>
          </a>
        </li>
        <li class="nav-item <?= in_array($current_seg, ['materi', 'tambah_materi', 'edit_materi']) ? 'active' : '' ?>">
          <a href="<?= site_url('guru/materi') ?>">
            <i class="fa-solid fa-book-open"></i>
            <span>Materi</span>
          </a>
        </li>
        <li class="nav-item <?= in_array($current_seg, ['tugas', 'tambah_tugas', 'edit_tugas', 'detail_tugas']) ? 'active' : '' ?>">
          <a href="<?= site_url('guru/tugas') ?>">
            <i class="fa-regular fa-square-check"></i>
            <span>Tugas &amp; Penilaian</span>
          </a>
        </li>
      </ul>

      <div class="nav-section-title" style="margin-top: 24px;">Pengaturan</div>
      <ul class="nav-list">
        <li class="nav-item <?= ($current_seg == 'profil') ? 'active' : '' ?>">
          <a href="<?= site_url('guru/profil') ?>">
            <i class="fa-solid fa-user-gear"></i>
            <span>Profil</span>
          </a>
        </li>
      </ul>
    </div>

    <div class="sidebar-footer">
      <div class="user-profile-summary">
        <div class="user-avatar">
          <?php if (!empty($this->session->userdata('foto'))): ?>
            <img src="<?= base_url('uploads/foto/' . $this->session->userdata('foto')) ?>" alt="Avatar">
          <?php else: ?>
            <i class="fa-solid fa-user"></i>
          <?php endif; ?>
        </div>
        <div class="user-meta">
          <div class="user-name"><?= html_escape($this->session->userdata('nama')) ?></div>
          <div class="user-role-label">Guru Pengajar</div>
        </div>
        <a href="<?= site_url('auth/logout') ?>" class="logout-btn" title="Keluar">
          <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </a>
      </div>
    </div>
  </aside>

  <!-- MAIN WRAPPER -->
  <div class="main-wrapper">
    <header class="topbar">
      <div class="flex items-center gap-4">
        <button id="sidebarToggle" class="md:hidden text-2xl text-slate-600">
          <i class="fa-solid fa-bars"></i>
        </button>
        <form class="topbar-search hidden md:flex" action="<?= site_url('guru/materi') ?>" method="get">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="search" name="q" placeholder="Cari tugas, materi, atau siswa..." autocomplete="off">
        </form>
      </div>
      <div class="topbar-actions">
        <a href="<?= site_url('guru/notifikasi') ?>" class="icon-btn" title="Notifikasi<?= !empty($notif_unread) ? ' (' . (int) $notif_unread . ')' : '' ?>">
          <i class="fa-regular fa-bell"></i>
          <?php if (!empty($notif_unread)): ?>
            <span class="notif-dot"></span>
          <?php endif; ?>
        </a>
        <div class="topbar-user">
          <div class="avatar">
            <?php if (!empty($this->session->userdata('foto'))): ?>
              <img src="<?= base_url('uploads/foto/' . $this->session->userdata('foto')) ?>" alt="Avatar">
            <?php else: ?>
              <?= html_escape(strtoupper(substr($this->session->userdata('nama') ?: 'G', 0, 1))) ?>
            <?php endif; ?>
          </div>
          <div>
            <div class="name"><?= html_escape($this->session->userdata('nama') ?: 'Guru') ?></div>
            <div class="role">Guru Pengajar</div>
          </div>
        </div>
      </div>
    </header>

    <main class="page-content">
      <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
          <i class="fa-solid fa-circle-check"></i>
          <div><?= $this->session->flashdata('success') ?></div>
        </div>
      <?php endif; ?>

      <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-error">
          <i class="fa-solid fa-circle-exclamation"></i>
          <div><?= $this->session->flashdata('error') ?></div>
        </div>
      <?php endif; ?>

      <?php if ($this->session->flashdata('warning')): ?>
        <div class="alert alert-warning">
          <i class="fa-solid fa-triangle-exclamation"></i>
          <div><?= $this->session->flashdata('warning') ?></div>
        </div>
      <?php endif; ?>

      <?= isset($content_view) ? $content_view : '' ?>
    </main>
  </div>
</div>

<script>
  const toggleBtn = document.getElementById('sidebarToggle');
  const sidebar = document.querySelector('.sidebar');
  
  if (toggleBtn && sidebar) {
    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('is-open');
    });

    document.addEventListener('click', (e) => {
      if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
        sidebar.classList.remove('is-open');
      }
    });
  }
</script>
</body>
</html>
