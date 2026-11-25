<style>
    .filter-pills { display: flex; gap: 8px; margin-bottom: 18px; flex-wrap: wrap; }
    .filter-pills a { padding: 8px 16px; border-radius: 999px; border: 1px solid var(--card-border); background: #FFFFFF; color: #475569; text-decoration: none; font-size: 13px; font-weight: 600; transition: all 0.2s; }
    .filter-pills a:hover { border-color: var(--primary); color: var(--primary); }
    .filter-pills a.active { background: var(--primary-dark); border-color: var(--primary-dark); color: #FFFFFF; }
    .kode-chip { font-family: monospace; font-size: 12px; background: #F1F5F9; padding: 3px 9px; border-radius: 6px; color: #475569; font-weight: 700; letter-spacing: 0.03em; }
    .tingkat-chip { display: inline-flex; align-items: center; background: #EEF2FF; color: #3730A3; border-radius: 999px; padding: 3px 10px; font-size: 12px; font-weight: 700; }
    .action-links { display: flex; gap: 8px; flex-wrap: wrap; }
    .action-links .btn-secondary, .action-links .btn-danger-light { padding: 6px 12px; font-size: 12px; }
</style>

<div class="card">
    <div class="card-header-flex">
        <div>
            <h3 style="font-size: 18px; font-weight: 700;">Daftar Kelas</h3>
            <p style="font-size: 13px; color: var(--text-muted);">Kelola kelas XI/XII dan keanggotaan siswa. Satu siswa hanya berada di satu kelas.</p>
        </div>
        <a href="<?= site_url('admin/tambah_kelas') ?>" class="btn-primary">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Kelas</span>
        </a>
    </div>

    <div class="filter-pills">
        <a href="<?= site_url('admin/kelas') ?>" class="<?= empty($tingkat_filter) ? 'active' : '' ?>">Semua</a>
        <?php foreach (['X', 'XI', 'XII'] as $tk): ?>
            <a href="<?= site_url('admin/kelas?tingkat=' . $tk) ?>" class="<?= ($tingkat_filter === $tk) ? 'active' : '' ?>">
                Kelas <?= $tk ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="data-table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Kelas</th>
                    <th>Tingkat</th>
                    <th>Jurusan</th>
                    <th>Kode</th>
                    <th style="text-align: center;">Siswa</th>
                    <th style="text-align: center;">Mapel</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($kelas_list)): ?>
                    <?php foreach ($kelas_list as $k): ?>
                        <tr>
                            <td style="font-weight: 600;"><?= htmlspecialchars($k->nama_kelas) ?></td>
                            <td><span class="tingkat-chip"><?= htmlspecialchars($k->tingkat) ?></span></td>
                            <td><?= htmlspecialchars($k->jurusan ?: '-') ?></td>
                            <td><span class="kode-chip"><?= htmlspecialchars($k->kode_kelas) ?></span></td>
                            <td style="text-align: center;"><?= number_format($k->jumlah_siswa) ?></td>
                            <td style="text-align: center;"><?= number_format($k->jumlah_mapel) ?></td>
                            <td>
                                <div class="action-links" style="justify-content: center;">
                                    <a href="<?= site_url('admin/detail_kelas/' . $k->id) ?>" class="btn-secondary">
                                        <i class="fa-solid fa-eye"></i> Detail
                                    </a>
                                    <a href="<?= site_url('admin/edit_kelas/' . $k->id) ?>" class="btn-secondary">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </a>
                                    <a href="<?= site_url('admin/hapus_kelas/' . $k->id) ?>" class="btn-danger-light" onclick="return confirm('Hapus kelas <?= htmlspecialchars(addslashes($k->nama_kelas)) ?>?')">
                                        <i class="fa-solid fa-trash-can"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px;">
                            <?php if (!empty($tingkat_filter)): ?>
                                Belum ada kelas untuk tingkat <?= htmlspecialchars($tingkat_filter) ?>.
                            <?php else: ?>
                                Belum ada kelas. Klik "Tambah Kelas" untuk membuat.
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
