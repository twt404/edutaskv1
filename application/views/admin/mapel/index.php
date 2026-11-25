<style>
    .mapel-table .mapel-name { font-weight: 700; }
    .mapel-table .mapel-code { font-family: monospace; font-size: 12px; background: #F1F5F9; padding: 2px 8px; border-radius: 6px; color: #475569; }
    .mapel-table .class-chip { display: inline-flex; align-items: center; gap: 6px; background: #EEF2FF; color: #3730A3; border-radius: 999px; padding: 3px 10px; font-size: 12px; font-weight: 600; }
    .mapel-table .class-chip.empty { background: #F1F5F9; color: #64748B; }
    .assign-form { display: flex; gap: 6px; align-items: center; }
    .assign-form select { padding: 6px 8px; border: 1px solid var(--card-border); border-radius: 8px; font-size: 12px; font-family: inherit; background: #FFF; color: var(--text-main); max-width: 170px; }
    .assign-form button { border: none; background: var(--primary); color: #FFF; border-radius: 8px; padding: 6px 10px; font-size: 12px; font-weight: 600; cursor: pointer; }
    .assign-form button:hover { background: var(--primary-dark); }
</style>

<div class="card">
    <div class="card-header-flex">
        <div>
            <h3 style="font-size: 18px; font-weight: 700;">Daftar Mapel</h3>
            <p style="font-size: 13px; color: var(--text-muted);">Mata pelajaran dibuat oleh guru dan harus terhubung ke satu kelas.</p>
        </div>
    </div>

    <div class="data-table-container">
        <table class="data-table mapel-table">
            <thead>
                <tr>
                    <th>Mata Pelajaran</th>
                    <th>Kelas</th>
                    <th>Guru Pengajar</th>
                    <th style="text-align: center;">Siswa</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($mapel_list)): ?>
                    <?php foreach ($mapel_list as $m): ?>
                        <tr>
                            <td>
                                <div class="mapel-name"><?= htmlspecialchars($m->nama_mapel) ?></div>
                                <div style="margin-top: 4px;"><span class="mapel-code"><?= htmlspecialchars($m->kode_mapel) ?></span></div>
                            </td>
                            <td>
                                <?php if (!empty($m->nama_kelas)): ?>
                                    <span class="class-chip">
                                        <i class="fa-solid fa-people-group"></i>
                                        <?= htmlspecialchars($m->nama_kelas) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="class-chip empty">
                                        <i class="fa-solid fa-circle-exclamation"></i>
                                        Belum ada kelas
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($m->nama_guru ?: 'Belum ditentukan') ?></td>
                            <td style="text-align: center;"><?= number_format($m->jumlah_siswa) ?></td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 8px; align-items: center;">
                                    <?php if (!empty($kelas_list)): ?>
                                        <form class="assign-form" method="post" action="<?= site_url('admin/tetapkan_kelas_mapel/' . $m->id) ?>">
                                            <select name="kelas_id" required>
                                                <option value="">— Pilih kelas —</option>
                                                <?php foreach ($kelas_list as $k): ?>
                                                    <option value="<?= (int) $k->id ?>" <?= ((int) $k->id === (int) $m->kelas_id) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($k->tingkat . ' · ' . $k->nama_kelas) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit" title="Tetapkan kelas">
                                                <i class="fa-solid fa-floppy-disk"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <a href="<?= site_url('admin/hapus_mapel/' . $m->id) ?>" class="btn-danger-light"
                                       style="padding: 5px 10px; font-size: 12px;"
                                       onclick="return confirm('Hapus mapel <?= htmlspecialchars(addslashes($m->nama_mapel)) ?>?')">
                                        <i class="fa-solid fa-trash-can"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 40px;">
                            Belum ada mapel. Guru dapat menambahkan mapel dari menu Mapel Saya.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
