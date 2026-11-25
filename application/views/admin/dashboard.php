<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 20px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: #FFFFFF;
        border: 1px solid var(--card-border);
        border-radius: var(--radius-lg);
        padding: 22px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .stat-icon.blue { background-color: #EFF6FF; color: #2563EB; }
    .stat-icon.indigo { background-color: #EEF2FF; color: #4F46E5; }
    .stat-icon.emerald { background-color: #ECFDF5; color: #059669; }
    .stat-icon.amber { background-color: #FFFBEB; color: #D97706; }

    .stat-details .stat-label {
        font-size: 13px;
        color: var(--text-muted);
        font-weight: 500;
        margin-bottom: 4px;
    }

    .stat-details .stat-value {
        font-size: 28px;
        font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-main);
    }

    .action-banner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
        border-radius: var(--radius-lg);
        padding: 24px 32px;
        color: #FFFFFF;
        margin-bottom: 28px;
    }

    .action-banner h2 {
        font-size: 20px;
        margin-bottom: 6px;
    }

    .action-banner p {
        font-size: 14px;
        color: #94A3B8;
    }

    .action-btn-group {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-light {
        background-color: #FFFFFF;
        color: var(--text-main);
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-light:hover {
        background-color: #F1F5F9;
    }

    .btn-accent {
        background-color: var(--primary);
        color: #FFFFFF;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-accent:hover {
        background-color: var(--primary-hover);
    }
</style>

<!-- 5 Stat Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-details">
            <div class="stat-label">Total Pengguna</div>
            <div class="stat-value"><?= number_format($total_pengguna ?? 0) ?></div>
        </div>
        <div class="stat-icon blue">
            <i class="fa-solid fa-users"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-details">
            <div class="stat-label">Total Guru</div>
            <div class="stat-value"><?= number_format($total_guru ?? 0) ?></div>
        </div>
        <div class="stat-icon indigo">
            <i class="fa-solid fa-chalkboard-user"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-details">
            <div class="stat-label">Total Siswa</div>
            <div class="stat-value"><?= number_format($total_siswa ?? 0) ?></div>
        </div>
        <div class="stat-icon emerald">
            <i class="fa-solid fa-user-graduate"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-details">
            <div class="stat-label">Total Kelas</div>
            <div class="stat-value"><?= number_format($total_kelas ?? 0) ?></div>
        </div>
        <div class="stat-icon amber">
            <i class="fa-solid fa-people-group"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-details">
            <div class="stat-label">Total Mapel</div>
            <div class="stat-value"><?= number_format($total_mapel ?? 0) ?></div>
        </div>
        <div class="stat-icon indigo">
            <i class="fa-solid fa-book-open"></i>
        </div>
    </div>
</div>

<!-- Quick Action Banner -->
<div class="action-banner">
    <div>
        <h2>Selamat datang di EduTask Admin</h2>
        <p>Kelola pengguna, pantau aktivitas kelas, dan tinjau rekap laporan pembelajaran.</p>
    </div>
    <div class="action-btn-group">
        <a href="<?= site_url('admin/kelas') ?>" class="btn-light">
            <i class="fa-solid fa-people-group"></i>
            <span>Kelola Kelas</span>
        </a>
        <a href="<?= site_url('admin/mapel') ?>" class="btn-light">
            <i class="fa-solid fa-book-open"></i>
            <span>Kelola Mapel</span>
        </a>
        <a href="<?= site_url('admin/tambah_pengguna') ?>" class="btn-accent">
            <i class="fa-solid fa-user-plus"></i>
            <span>Tambah Pengguna</span>
        </a>
        <a href="<?= site_url('admin/laporan') ?>" class="btn-light">
            <i class="fa-solid fa-chart-line"></i>
            <span>Lihat Laporan</span>
        </a>
    </div>
</div>

<!-- Recent Users Table -->
<div class="card">
    <div class="card-header-flex">
        <div>
            <h3 style="font-size: 18px; font-weight: 700;">Pengguna Terbaru</h3>
            <p style="font-size: 13px; color: var(--text-muted);">10 akun yang baru mendaftar atau ditambahkan ke sistem</p>
        </div>
        <a href="<?= site_url('admin/pengguna') ?>" style="font-size: 13px; color: var(--primary); font-weight: 600; text-decoration: none;">
            Lihat Semua Pengguna <i class="fa-solid fa-arrow-right" style="margin-left: 4px;"></i>
        </a>
    </div>

    <div class="data-table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Tanggal Terdaftar</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($recent_users)): ?>
                    <?php foreach ($recent_users as $u): ?>
                        <tr>
                            <td style="font-weight: 600;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #E2E8F0; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; color: #475569;">
                                        <?= strtoupper(substr($u->nama, 0, 1)) ?>
                                    </div>
                                    <span><?= htmlspecialchars($u->nama) ?></span>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($u->email) ?></td>
                            <td>
                                <span class="badge badge-<?= htmlspecialchars($u->role) ?>">
                                    <?= ucfirst(htmlspecialchars($u->role)) ?>
                                </span>
                            </td>
                            <td style="color: var(--text-muted); font-size: 13px;">
                                <?= date('d M Y, H:i', strtotime($u->created_at)) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 32px;">
                            Belum ada pengguna yang terdaftar.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
