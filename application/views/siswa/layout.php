<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($title) ? html_escape($title) : 'EduTask' ?> — EduTask Siswa</title>
  <!-- Self-hosted fonts/icons (offline LAN) -->
  <link rel="stylesheet" href="<?= base_url('assets/fonts/fonts.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendor/fontawesome/css/all.min.css') ?>">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root {
      --primary: #38BDF8;
      --primary-hover: #0EA5E9;
      --primary-dark: #0284C7;
      --primary-light: #E0F5FF;
      --mint: #6EE7B7;
      --mint-deep: #10B981;
      --mint-light: #E8FFF6;
      --bg-body: #F4FCFF;
      --card-bg: #FFFFFF;
      --card-border: #D9ECF5;
      --sidebar-bg: #FFFFFF;
      --sidebar-width: 260px;
      --text-main: #0F2A43;
      --text-muted: #5B7185;
      --badge-individu-bg: #DEFBF1;
      --badge-individu-text: #047857;
      --badge-kelompok-bg: #E8F4FF;
      --badge-kelompok-text: #0369A1;
      --status-graded-bg: #DCFCE7;
      --status-graded-text: #15803D;
      --status-pending-bg: #FEF3C7;
      --status-pending-text: #B45309;
      --status-overdue-bg: #FFE4E6;
      --status-overdue-text: #BE123C;
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
      position: relative;
      z-index: 1;
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
      overflow: hidden;
    }

    .user-avatar img {
      width: 100%;
      height: 100%;
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
      margin-bottom: 24px;
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

    .btn-success {
      background: #10B981;
      color: #FFFFFF;
    }
    .btn-success:hover {
      background: #059669;
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
      border-radius: 18px;
      border-color: var(--card-border);
      box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
    }
    .btn { border-radius: 10px; }
    .btn-primary { background: var(--primary-dark); }
    .btn-primary:hover { background: #003EA8; }
    .table th { background: #EFF4FF; color: #434655; }
    .table tbody tr:hover { background: #F8F9FF; }

    /* ─── Sky & Mint theme ──────────────────────────────────────── */
    body {
      background:
        radial-gradient(circle at 8% 8%, rgba(56, 189, 248, 0.16), transparent 30%),
        radial-gradient(circle at 92% 92%, rgba(110, 231, 183, 0.20), transparent 32%),
        linear-gradient(135deg, #F4FCFF 0%, #EDF9FF 50%, #F2FFFA 100%);
      background-attachment: fixed;
    }
    .layout-decor {
      position: fixed;
      inset: 0;
      overflow: hidden;
      pointer-events: none;
      z-index: 0;
    }
    .layout-decor svg {
      position: absolute;
      opacity: 0.18;
      filter: drop-shadow(0 16px 20px rgba(14, 116, 144, 0.08));
    }
    .layout-decor .ld1 { width: 88px; left: 290px; top: 92px; transform: rotate(-8deg); }
    .layout-decor .ld2 { width: 72px; right: 38px; bottom: 34px; transform: rotate(9deg); }
    .layout-decor .ld3 { width: 58px; left: 302px; bottom: 48px; transform: rotate(-5deg); opacity: 0.12; }
    .sidebar {
      background: rgba(255, 255, 255, 0.84) !important;
      backdrop-filter: blur(16px);
      border-right: 1px solid rgba(217, 236, 245, 0.92);
    }
    .brand-icon {
      background: linear-gradient(135deg, #38BDF8 0%, #22C1C3 55%, #10B981 100%);
      box-shadow: 0 12px 24px -13px rgba(14, 165, 233, 0.9);
    }
    .brand-logo { color: var(--text-main); }
    .brand-role {
      background: var(--mint-light);
      color: #047857;
    }
    .brand-sub { color: var(--text-muted); }
    .nav-item a { color: #4A6579; }
    .nav-item a:hover {
      color: var(--primary-dark);
      background: #EAF8FF;
    }
    .nav-item.active a {
      color: #FFFFFF;
      background: linear-gradient(135deg, #38BDF8 0%, #22C1C3 55%, #10B981 100%);
      border-left: none;
      box-shadow: 0 12px 24px -15px rgba(14, 165, 233, 0.9);
    }
    .sidebar-footer { border-top-color: #E4F3F9; }
    .user-avatar {
      background: var(--primary-light);
      border-color: #BDEBFF;
      color: var(--primary-dark);
    }
    .user-role-label, .logout-btn { color: #6B8599; }
    .topbar {
      background: rgba(255, 255, 255, 0.78);
      backdrop-filter: blur(16px);
      border-bottom: 1px solid rgba(217, 236, 245, 0.92);
    }
    .topbar-search {
      background: #F3FBFF;
      border: 1px solid #D9F1FB;
      color: #6B8599;
    }
    .icon-btn {
      background: #FFFFFF;
      border-color: var(--card-border);
      color: #4A6579;
    }
    .icon-btn:hover {
      background: var(--primary-light);
      color: var(--primary-dark);
    }
    .topbar-user .avatar {
      background: linear-gradient(135deg, #38BDF8 0%, #10B981 100%);
      box-shadow: 0 8px 16px -10px rgba(14, 165, 233, 0.9);
    }
    .card {
      background: rgba(255, 255, 255, 0.96);
      border-radius: 20px;
      border-color: var(--card-border);
      box-shadow: 0 20px 46px -40px rgba(14, 116, 144, 0.5);
    }
    .btn {
      border-radius: 12px;
      font-weight: 700;
    }
    .btn-primary {
      background: linear-gradient(135deg, #38BDF8 0%, #22C1C3 55%, #10B981 100%);
      color: #FFFFFF;
      box-shadow: 0 12px 24px -15px rgba(14, 165, 233, 0.85);
    }
    .btn-primary:hover {
      background: linear-gradient(135deg, #22B8F2 0%, #0EA5E9 55%, #059669 100%);
      transform: translateY(-1px);
    }
    .btn-secondary {
      background: #FFFFFF;
      border-color: #CFE9F4;
      color: #36596F;
    }
    .btn-secondary:hover {
      background: #F3FBFF;
      border-color: #BDEBFF;
      color: var(--primary-dark);
    }
    .btn-success {
      background: linear-gradient(135deg, #6EE7B7 0%, #10B981 100%);
      box-shadow: 0 12px 24px -15px rgba(16, 185, 129, 0.8);
    }
    .btn-success:hover { background: linear-gradient(135deg, #34D399 0%, #059669 100%); }
    .form-control {
      background: #F8FDFF;
      border-color: #D5EAF4;
      border-radius: 12px;
    }
    .form-control:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.16);
    }
    .table-container {
      border-radius: 16px;
      border-color: var(--card-border);
    }
    .table th {
      background: #EAF8FF;
      color: #36596F;
    }
    .table tbody tr:hover { background: #F5FCFF; }
    .stat-card {
      background: rgba(255, 255, 255, 0.96);
      border-radius: 18px;
      border-color: var(--card-border);
      box-shadow: 0 20px 46px -42px rgba(14, 116, 144, 0.55);
    }
    .stat-icon { border-radius: 15px; }
    .stat-icon.blue { background: #E0F5FF; color: #0284C7; }
    .stat-icon.green { background: #E8FFF6; color: #059669; }
    .stat-icon.amber { background: #FFF7E6; color: #D97706; }
    .alert-success {
      background: #E8FFF6;
      border-color: #A7F3D0;
      color: #047857;
    }
    .alert-error {
      background: #FFF1F2;
      border-color: #FECDD3;
      color: #BE123C;
    }
    .alert-warning {
      background: #FFFBEB;
      border-color: #FDE68A;
      color: #92400E;
    }
    .hero-panel {
      position: relative;
      overflow: hidden;
      border-radius: 26px;
      padding: 32px 34px;
      margin-bottom: 28px;
      color: #FFFFFF;
      background:
        radial-gradient(circle at 82% 18%, rgba(255, 255, 255, 0.32), transparent 30%),
        linear-gradient(145deg, #22B8F2 0%, #38BDF8 46%, #34D6C4 100%);
      box-shadow: 0 28px 60px -38px rgba(14, 116, 144, 0.7);
    }
    .hero-panel::before,
    .hero-panel::after {
      content: "";
      position: absolute;
      border-radius: 50%;
      pointer-events: none;
    }
    .hero-panel::before {
      width: 260px;
      height: 260px;
      right: -80px;
      top: -120px;
      background: rgba(255, 255, 255, 0.14);
    }
    .hero-panel::after {
      width: 150px;
      height: 150px;
      left: -60px;
      bottom: -80px;
      background: rgba(110, 231, 183, 0.28);
    }
    .hero-content { position: relative; z-index: 1; max-width: 660px; }
    .hero-eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      font-size: 0.7rem;
      font-weight: 800;
      letter-spacing: 0.09em;
      text-transform: uppercase;
      padding: 7px 12px;
      border-radius: 999px;
      background: rgba(255, 255, 255, 0.18);
      border: 1px solid rgba(255, 255, 255, 0.28);
      color: #FFFFFF;
      margin-bottom: 14px;
    }
    .hero-panel h2 {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(1.55rem, 2.6vw, 2.15rem);
      font-weight: 800;
      letter-spacing: -0.03em;
      line-height: 1.15;
      color: #FFFFFF;
      margin-bottom: 9px;
    }
    .hero-panel p {
      font-size: 0.93rem;
      line-height: 1.65;
      color: rgba(255, 255, 255, 0.88);
      max-width: 520px;
    }
    .hero-chip {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      margin-top: 14px;
      padding: 6px 13px;
      border-radius: 999px;
      background: rgba(255, 255, 255, 0.2);
      border: 1px solid rgba(255, 255, 255, 0.3);
      color: #FFFFFF;
      font-size: 0.78rem;
      font-weight: 700;
    }
    .hero-chip.warning {
      background: rgba(255, 247, 237, 0.95);
      border-color: rgba(254, 215, 170, 0.9);
      color: #B45309;
    }
    .hero-illustration {
      position: absolute;
      right: 18px;
      top: 50%;
      width: 270px;
      transform: translateY(-50%);
      z-index: 1;
      pointer-events: none;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .hero-illustration svg { width: 100%; height: auto; filter: drop-shadow(0 22px 28px rgba(6, 78, 108, 0.2)); }
    .hero-status {
      position: relative;
      z-index: 1;
      flex-shrink: 0;
      text-align: right;
      padding: 16px 20px;
      border-radius: 17px;
      background: rgba(255, 255, 255, 0.16);
      border: 1px solid rgba(255, 255, 255, 0.26);
      backdrop-filter: blur(6px);
    }
    .hero-status .label {
      font-size: 0.72rem;
      font-weight: 700;
      letter-spacing: 0.05em;
      text-transform: uppercase;
      color: rgba(255, 255, 255, 0.78);
    }
    .hero-status .value {
      margin-top: 5px;
      font-size: 1rem;
      font-weight: 800;
      color: #DFFFF4;
    }
    .dashboard-grid {
      display: grid;
      grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);
      gap: 24px;
    }
    .task-row {
      border: 1px solid var(--card-border);
      border-radius: 14px;
      background: linear-gradient(180deg, #FCFFFF 0%, #F5FCFF 100%);
      transition: border-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
    }
    .task-row:hover {
      border-color: #BDEBFF;
      transform: translateY(-1px);
      box-shadow: 0 18px 30px -28px rgba(14, 116, 144, 0.55);
    }
    .soft-panel {
      border: 1px solid #D9ECF5;
      border-radius: 14px;
      background: linear-gradient(180deg, #FBFFFF 0%, #F1FBFF 100%);
    }
    .mapel-mini {
      display: block;
      padding: 12px;
      border-radius: 13px;
      border: 1px solid #D9ECF5;
      background: linear-gradient(135deg, #F7FDFF 0%, #EEFFF8 100%);
      transition: border-color 0.2s ease, transform 0.2s ease;
    }
    .mapel-mini:hover {
      border-color: #9FE7D5;
      transform: translateX(2px);
    }

    @media (max-width: 1080px) {
      .dashboard-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 980px) {
      .hero-illustration { display: none; }
      .hero-panel { padding: 28px 26px; }
    }

    @media (max-width: 820px) {
      .topbar-search { display: none; }
      .topbar-user .name, .topbar-user .role { display: none; }
      .page-content { padding: 20px 16px 32px; }
      .layout-decor { display: none; }
      .hero-status { text-align: left; }
    }
  </style>
</head>
<body>
<div class="layout-decor" aria-hidden="true">
  <svg class="ld1" viewBox="0 0 80 80" fill="none">
    <rect x="10" y="18" width="60" height="46" rx="12" fill="#BAE6FD"/>
    <path d="M18 29h18M18 38h28M18 47h14" stroke="#0EA5E9" stroke-width="4" stroke-linecap="round"/>
    <path d="M52 29h10" stroke="#6EE7B7" stroke-width="4" stroke-linecap="round"/>
  </svg>
  <svg class="ld2" viewBox="0 0 80 80" fill="none">
    <path d="M8 30L40 16l32 14-32 14L8 30z" fill="#6EE7B7"/>
    <path d="M20 36v14c0 5 9 10 20 10s20-5 20-10V36" stroke="#10B981" stroke-width="5" stroke-linecap="round"/>
    <path d="M72 30v20" stroke="#0F2A43" stroke-width="4" stroke-linecap="round"/>
    <circle cx="72" cy="53" r="4" fill="#FBBF24"/>
  </svg>
  <svg class="ld3" viewBox="0 0 80 80" fill="none">
    <rect x="14" y="42" width="52" height="16" rx="8" transform="rotate(-40 14 42)" fill="#38BDF8"/>
    <path d="M24 54l14-14 12 12-14 14z" fill="#E0F2FE"/>
    <path d="M52 20l10 10-8 8-10-10z" fill="#FBBF24"/>
  </svg>
</div>
<div class="app-layout">
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-header">
      <div class="brand-icon">
        <i class="fa-solid fa-graduation-cap"></i>
      </div>
      <div>
        <span class="brand-logo">EduTask</span>
        <span class="brand-role">Siswa</span>
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
          <a href="<?= site_url('siswa/dashboard') ?>">
            <i class="fa-solid fa-chart-pie"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <li class="nav-item <?= in_array($current_seg, ['kelas', 'mapel', 'detail_mapel', 'materi']) ? 'active' : '' ?>">
          <a href="<?= site_url('siswa/kelas') ?>">
            <i class="fa-solid fa-chalkboard-user"></i>
            <span>Kelas Saya</span>
          </a>
        </li>
        <li class="nav-item <?= in_array($current_seg, ['gabung_rombel', 'gabung_kelas']) ? 'active' : '' ?>">
          <a href="<?= site_url('siswa/gabung_kelas') ?>">
            <i class="fa-solid fa-people-group"></i>
            <span>Gabung Kelas</span>
          </a>
        </li>
        <li class="nav-item <?= in_array($current_seg, ['tugas', 'detail_tugas', 'kumpul_tugas']) ? 'active' : '' ?>">
          <a href="<?= site_url('siswa/tugas') ?>">
            <i class="fa-solid fa-list-check"></i>
            <span>Tugas</span>
          </a>
        </li>
        <li class="nav-item <?= ($current_seg == 'notifikasi') ? 'active' : '' ?>">
          <a href="<?= site_url('siswa/notifikasi') ?>">
            <i class="fa-solid fa-bell"></i>
            <span>Notifikasi</span>
          </a>
        </li>
      </ul>

      <div class="nav-section-title" style="margin-top: 24px;">Pengaturan</div>
      <ul class="nav-list">
        <li class="nav-item <?= ($current_seg == 'profil') ? 'active' : '' ?>">
          <a href="<?= site_url('siswa/profil') ?>">
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
          <div class="user-role-label">Siswa Pelajar</div>
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
        <form class="topbar-search hidden md:flex" action="<?= site_url('siswa/tugas') ?>" method="get">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="search" name="q" placeholder="Cari tugas, materi, atau kelas..." autocomplete="off">
        </form>
      </div>
      <div class="topbar-actions">
        <a href="<?= site_url('siswa/notifikasi') ?>" class="icon-btn" title="Notifikasi">
          <i class="fa-regular fa-bell"></i>
          <span class="notif-dot"></span>
        </a>
        <div class="topbar-user">
          <div class="avatar">
            <?php if (!empty($this->session->userdata('foto'))): ?>
              <img src="<?= base_url('uploads/foto/' . $this->session->userdata('foto')) ?>" alt="Avatar">
            <?php else: ?>
              <?= html_escape(strtoupper(substr($this->session->userdata('nama') ?: 'S', 0, 1))) ?>
            <?php endif; ?>
          </div>
          <div>
            <div class="name"><?= html_escape($this->session->userdata('nama') ?: 'Siswa') ?></div>
            <div class="role">Siswa Pelajar</div>
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
  
  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('is-open');
  });

  document.addEventListener('click', (e) => {
    if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
      sidebar.classList.remove('is-open');
    }
  });
</script>
</body>
</html>

