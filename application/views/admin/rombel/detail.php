<style>
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-top: 4px;
    }

    .detail-item .label {
        font-size: 12px;
        color: var(--text-muted);
        font-weight: 500;
        margin-bottom: 4px;
    }

    .detail-item .value {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-main);
    }

    .kode-chip {
        font-family: monospace;
        font-size: 14px;
        background: #F1F5F9;
        padding: 3px 10px;
        border-radius: 6px;
        color: #475569;
        font-weight: 700;
        letter-spacing: 0.03em;
    }

    .tingkat-chip {
        display: inline-flex;
        align-items: center;
        background: #EEF2FF;
        color: #3730A3;
        border-radius: 999px;
        padding: 3px 12px;
        font-size: 13px;
        font-weight: 700;
    }

    .two-col {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 24px;
        align-items: start;
    }

    @media (max-width: 1000px) {
        .two-col { grid-template-columns: 1fr; }
    }

    .add-student-form {
        display: flex;
        gap: 10px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .add-student-form select {
        flex: 1;
        min-width: 180px;
        padding: 10px 12px;
        border: 1px solid var(--card-border);
        border-radius: 10px;
        font-size: 14px;
        font-family: inherit;
        background: #FFFFFF;
        color: var(--text-main);
    }

    .add-student-form button {
        border: none;
        background: var(--primary-dark);
        color: #FFFFFF;
        border-radius: 10px;
        padding: 10px 18px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
    }

    .add-student-form button:hover {
        background: #003EA8;
    }

    .empty-note {
        text-align: center;
        padding: 28px;
        color: var(--text-muted);
        font-size: 14px;
    }

    .muted {
        color: var(--text-muted);
        font-size: 13px;
    }
</style>

<div class="card">
    <div class="card-header-flex">
        <div>
            <h3 style="font-size: 20px; font-weight: 700;"><?= htmlspecialchars($rombel->nama_rombel) ?></h3>
            <p style="font-size: 13px; color: var(--text-muted);">Detail rombel, anggota siswa, dan mapel/kelas terkait.</p>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="<?= site_url('admin/rombel') ?>" class="btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
            <a href="<?= site_url('admin/edit_rombel/' . $rombel->id) ?>" class="btn-primary">
                <i class="fa-solid fa-pen"></i> Edit
            </a>
            <a href="<?= site_url('admin/hapus_rombel/' . $rombel->id) ?>" class="btn-danger-light" onclick="return confirm('Hapus rombel <?= htmlspecialchars(addslashes($rombel->nama_rombel)) ?>?')">
                <i class="fa-solid fa-trash-can"></i> Hapus
            </a>
        </div>
    </div>

    <div class="detail-grid">
        <div class="detail-item">
            <div class="label">Tingkat</div>
            <div class="value"><span class="tingkat-chip"><?= htmlspecialchars($rombel->tingkat) ?></span></div>
        </div>
        <div class="detail-item">
            <div class="label">Jurusan</div>
            <div class="value"><?= htmlspecialchars($rombel->jurusan ?: '-') ?></div>
        </div>
        <div class="detail-item">
            <div class="label">Kode Rombel</div>
            <div class="value"><span class="kode-chip"><?= htmlspecialchars($rombel->kode_rombel) ?></span></div>
        </div>
        <div class="detail-item">
            <div class="label">Jumlah Siswa</div>
            <div class="value"><?= number_format($rombel->jumlah_siswa) ?> siswa</div>
        </div>
        <div class="detail-item">
            <div class="label">Mapel/Kelas</div>
            <div class="value"><?= number_format($rombel->jumlah_kelas) ?> mapel</div>
        </div>
    </div>
</div>

<div class="two-col">
    <!-- Anggota -->
    <div class="card">
        <div class="card-header-flex">
            <div>
                <h3 style="font-size: 17px; font-weight: 700;">Anggota Rombel</h3>
                <p class="muted">Siswa hanya dapat berada pada satu rombel.</p>
            </div>
        </div>

        <?php if (!empty($kandidat)): ?>
            <form class="add-student-form" method="post" action="<?= site_url('admin/tambah_siswa_rombel/' . $rombel->id) ?>">
                <select name="email" required>
                    <option value="">— Pilih siswa —</option>
                    <?php foreach ($kandidat as $s): ?>
                        <option value="<?= htmlspecialchars($s->email) ?>">
                            <?= htmlspecialchars($s->nama) ?> (<?= htmlspecialchars($s->email) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">
                    <i class="fa-solid fa-user-plus"></i> Tambah
                </button>
            </form>
        <?php else: ?>
            <div class="empty-note" style="padding: 12px 0 18px;">
                <i class="fa-solid fa-circle-info"></i>
                Tidak ada siswa tanpa rombel. Tambahkan siswa baru di menu Pengguna.
            </div>
        <?php endif; ?>

        <div class="data-table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($siswa_list)): ?>
                        <?php foreach ($siswa_list as $s): ?>
                            <tr>
                                <td style="font-weight: 600;"><?= htmlspecialchars($s->nama) ?></td>
                                <td class="muted"><?= htmlspecialchars($s->email) ?></td>
                                <td style="text-align: center;">
                                    <a href="<?= site_url('admin/hapus_siswa_rombel/' . $rombel->id . '/' . $s->id) ?>"
                                       class="btn-danger-light"
                                       onclick="return confirm('Keluarkan <?= htmlspecialchars(addslashes($s->nama)) ?> dari rombel ini?')">
                                        <i class="fa-solid fa-user-minus"></i> Keluarkan
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center; color: var(--text-muted); padding: 28px;">
                                Belum ada siswa di rombel ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mapel / kelas -->
    <div class="card">
        <div class="card-header-flex">
            <div>
                <h3 style="font-size: 17px; font-weight: 700;">Mapel / Kelas</h3>
                <p class="muted">Kelas yang ditetapkan ke rombel ini.</p>
            </div>
            <a href="<?= site_url('admin/kelas') ?>" class="btn-secondary" style="padding: 6px 12px; font-size: 12px;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Kelola
            </a>
        </div>

        <div class="data-table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mapel</th>
                        <th>Guru</th>
                        <th style="text-align: center;">Siswa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($kelas_list)): ?>
                        <?php foreach ($kelas_list as $k): ?>
                            <tr>
                                <td style="font-weight: 600;"><?= htmlspecialchars($k->nama_kelas) ?></td>
                                <td class="muted"><?= htmlspecialchars($k->nama_guru ?: '-') ?></td>
                                <td style="text-align: center;"><?= number_format($k->jumlah_siswa) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center; color: var(--text-muted); padding: 28px;">
                                Belum ada mapel/kelas pada rombel ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
