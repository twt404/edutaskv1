<style>
    .summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .mini-card {
        background: #FFFFFF;
        border: 1px solid var(--card-border);
        border-radius: var(--radius-md);
        padding: 16px 20px;
    }

    .mini-card .label {
        font-size: 12px;
        color: var(--text-muted);
        font-weight: 500;
        margin-bottom: 4px;
    }

    .mini-card .val {
        font-size: 24px;
        font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-main);
    }

    /* Alternating row colors */
    table.data-table tbody tr:nth-child(even) {
        background-color: #F8FAFC;
    }
    table.data-table tbody tr:nth-child(odd) {
        background-color: #FFFFFF;
    }
    table.data-table tbody tr:hover {
        background-color: #EFF6FF !important;
    }
</style>

<div class="summary-cards">
    <div class="mini-card">
        <div class="label">Total Siswa Terdaftar</div>
        <div class="val"><?= number_format($grand_siswa ?? 0) ?></div>
    </div>
    <div class="mini-card">
        <div class="label">Total Tugas Dibuat</div>
        <div class="val"><?= number_format($grand_tugas ?? 0) ?></div>
    </div>
    <div class="mini-card">
        <div class="label">Total Pengumpulan</div>
        <div class="val"><?= number_format($grand_pengumpulan ?? 0) ?></div>
    </div>
    <div class="mini-card">
        <div class="label">Rata-rata Nilai Global</div>
        <div class="val" style="color: var(--primary);"><?= number_format($grand_avg_nilai ?? 0, 1) ?></div>
    </div>
</div>

<div class="card">
    <div class="card-header-flex">
        <div>
            <h3 style="font-size: 18px; font-weight: 700;">Rekapitulasi Aktivitas Pembelajaran</h3>
            <p style="font-size: 13px; color: var(--text-muted);">Statistik agregat berdasarkan setiap mapel dan kelas</p>
        </div>
        <button onclick="window.print()" class="btn-secondary" style="font-size: 13px;">
            <i class="fa-solid fa-print"></i>
            <span>Cetak / Ekspor PDF</span>
        </button>
    </div>

    <div class="data-table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Mapel</th>
                    <th>Kelas</th>
                    <th>Guru Pengajar</th>
                    <th style="text-align: center;">Jumlah Siswa</th>
                    <th style="text-align: center;">Total Tugas</th>
                    <th style="text-align: center;">Total Pengumpulan</th>
                    <th style="text-align: center;">Rata-rata Nilai</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($laporan)): ?>
                    <?php foreach ($laporan as $row): ?>
                        <tr>
                            <td style="font-weight: 600;">
                                <?= htmlspecialchars($row->nama_mapel) ?>
                            </td>
                            <td>
                                <?php if (!empty($row->nama_kelas)): ?>
                                    <span class="badge" style="background: #EEF2FF; color: #3730A3;">
                                        <?= htmlspecialchars($row->nama_kelas) ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: var(--text-muted); font-size: 13px;">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-chalkboard-user" style="color: var(--text-muted); font-size: 13px;"></i>
                                    <span><?= htmlspecialchars($row->nama_guru ?: 'Belum ditentukan') ?></span>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge" style="background: #F1F5F9; color: #334155;">
                                    <?= number_format($row->jumlah_siswa) ?>
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge" style="background: #F1F5F9; color: #334155;">
                                    <?= number_format($row->total_tugas) ?>
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge" style="background: #EFF6FF; color: #1D4ED8;">
                                    <?= number_format($row->total_pengumpulan) ?>
                                </span>
                            </td>
                            <td style="text-align: center; font-weight: 700;">
                                <?php if ($row->rata_nilai > 0): ?>
                                    <span style="color: #059669; background-color: #ECFDF5; padding: 4px 10px; border-radius: 6px;">
                                        <?= number_format($row->rata_nilai, 1) ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: var(--text-muted); font-weight: normal; font-size: 13px;">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 36px;">
                            Belum ada data laporan kelas.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
