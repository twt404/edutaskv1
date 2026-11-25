<style>
    .detail-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-top: 4px; }
    .detail-item .label { font-size: 12px; color: var(--text-muted); font-weight: 500; margin-bottom: 4px; }
    .detail-item .value { font-size: 15px; font-weight: 700; color: var(--text-main); }
    .kode-chip { font-family: monospace; font-size: 14px; background: #F1F5F9; padding: 3px 10px; border-radius: 6px; color: #475569; font-weight: 700; letter-spacing: 0.03em; }
    .tingkat-chip { display: inline-flex; align-items: center; background: #EEF2FF; color: #3730A3; border-radius: 999px; padding: 3px 12px; font-size: 13px; font-weight: 700; }
    .two-col { display: grid; grid-template-columns: 1.2fr 1fr; gap: 24px; align-items: start; }
    @media (max-width: 1000px) { .two-col { grid-template-columns: 1fr; } }
    .add-student-form { display: flex; gap: 10px; margin-bottom: 18px; flex-wrap: wrap; }
    .add-student-form select { flex: 1; min-width: 180px; padding: 10px 12px; border: 1px solid var(--card-border); border-radius: 10px; font-size: 14px; font-family: inherit; background: #FFFFFF; color: var(--text-main); }
    .add-student-form button { border: none; background: var(--primary-dark); color: #FFFFFF; border-radius: 10px; padding: 10px 18px; font-size: 14px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; white-space: nowrap; }
    .add-student-form button:hover { background: #003EA8; }
    .empty-note { text-align: center; padding: 28px; color: var(--text-muted); font-size: 14px; }
    .muted { color: var(--text-muted); font-size: 13px; }
</style>

<div class="card">
    <div class="card-header-flex">
        <div>
            <h3 style="font-size: 20px; font-weight: 700;"><?= htmlspecialchars($kelas->nama_kelas) ?></h3>
            <p style="font-size: 13px; color: var(--text-muted);">Detail kelas, anggota siswa, dan mapel terkait.</p>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="<?= site_url('admin/kelas') ?>" class="btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
            <a href="<?= site_url('admin/edit_kelas/' . $kelas->id) ?>" class="btn-primary">
                <i class="fa-solid fa-pen"></i> Edit
            </a>
            <a href="<?= site_url('admin/hapus_kelas/' . $kelas->id) ?>" class="btn-danger-light" onclick="return confirm('Hapus kelas <?= htmlspecialchars(addslashes($kelas->nama_kelas)) ?>?')">
                <i class="fa-solid fa-trash-can"></i> Hapus
            </a>
        </div>
    </div>

    <div class="detail-grid">
        <div class="detail-item">
            <div class="label">Tingkat</div>
            <div class="value"><span class="tingkat-chip"><?= htmlspecialchars($kelas->tingkat) ?></span></div>
        </div>
        <div class="detail-item">
            <div class="label">Jurusan</div>
            <div class="value"><?= htmlspecialchars($kelas->jurusan ?: '-') ?></div>
        </div>
        <div class="detail-item">
            <div class="label">Kode Kelas</div>
            <div class="value"><span class="kode-chip"><?= htmlspecialchars($kelas->kode_kelas) ?></span></div>
        </div>
        <div class="detail-item">
            <div class="label">Jumlah Siswa</div>
            <div class="value"><?= number_format($kelas->jumlah_siswa) ?> siswa</div>
        </div>
        <div class="detail-item">
            <div class="label">Jumlah Mapel</div>
            <div class="value"><?= number_format($kelas->jumlah_mapel) ?> mapel</div>
        </div>
    </div>
</div>

<div class="two-col">
    <!-- Anggota -->
    <div class="card">
        <div class="card-header-flex">
            <div>
                <h3 style="font-size: 17px; font-weight: 700;">Anggota Kelas</h3>
                <p class="muted">Siswa hanya dapat berada pada satu kelas.</p>
            </div>
        </div>

        <!-- Filter & Search UI -->
        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 12px; margin-bottom: 12px;">
                <div>
                    <label class="label muted" style="display:block; margin-bottom:4px; font-weight:600;">Tingkat</label>
                    <input type="text" id="f_tingkat" class="form-control" placeholder="XI / XII" style="height:38px; font-size:13px;">
                </div>
                <div>
                    <label class="label muted" style="display:block; margin-bottom:4px; font-weight:600;">Jurusan</label>
                    <input type="text" id="f_jurusan" class="form-control" placeholder="PPLG / BD" style="height:38px; font-size:13px;">
                </div>
                <div style="flex:1;">
                    <label class="label muted" style="display:block; margin-bottom:4px; font-weight:600;">Cari Siswa</label>
                    <input type="text" id="f_search" class="form-control" placeholder="Nama atau email..." style="height:38px; font-size:13px;">
                </div>
            </div>
            
            <div id="search-result-container" style="max-height: 300px; overflow-y: auto; border: 1px solid #E2E8F0; border-radius: 10px; background: #FFF; display:none; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">
                <?= form_open('admin/tambah_siswa_kelas_batch/' . $kelas->id, ['id' => 'batch-form']) ?>
                <table class="data-table" style="margin:0; border:none; width:100%;">
                    <thead style="position:sticky; top:0; background:#F8FAFC; z-index:1; border-bottom:1px solid #E2E8F0;">
                        <tr>
                            <th style="width:40px; text-align:center;"><input type="checkbox" id="check-all"></th>
                            <th>Nama Siswa</th>
                            <th>Tingkat/Jurusan</th>
                        </tr>
                    </thead>
                    <tbody id="siswa-list-target">
                        <!-- AJAX Result -->
                    </tbody>
                </table>
                <div style="position:sticky; bottom:0; padding: 12px 16px; border-top: 1px solid #E2E8F0; display:flex; justify-content: space-between; align-items:center; background:#F8FAFC; border-bottom-left-radius:10px; border-bottom-right-radius:10px;">
                    <span id="selected-count" style="font-size:13px; color:var(--text-main); font-weight:700;">0 terpilih</span>
                    <button type="submit" class="btn-primary" style="padding: 7px 18px; font-size:13px; border-radius:8px;">
                        <i class="fa-solid fa-user-plus"></i> Tambahkan ke Kelas
                    </button>
                </div>
                <?= form_close() ?>
            </div>
            <div id="search-placeholder" class="empty-note" style="padding:20px; border: 1px dashed #CBD5E1; border-radius:10px; background:#FFF;">
                <i class="fa-solid fa-magnifying-glass" style="margin-bottom:8px; display:block; font-size:1.2rem; color:#94A3B8;"></i>
                Gunakan filter di atas untuk mencari siswa tanpa kelas.
            </div>
        </div>

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
                                    <a href="<?= site_url('admin/hapus_siswa_kelas/' . $kelas->id . '/' . $s->id) ?>"
                                       class="btn-danger-light"
                                       onclick="return confirm('Keluarkan <?= htmlspecialchars(addslashes($s->nama)) ?> dari kelas ini?')">
                                        <i class="fa-solid fa-user-minus"></i> Keluarkan
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center; color: var(--text-muted); padding: 28px;">
                                Belum ada siswa di kelas ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mapel -->
    <div class="card">
        <div class="card-header-flex">
            <div>
                <h3 style="font-size: 17px; font-weight: 700;">Mapel di Kelas Ini</h3>
                <p class="muted">Mata pelajaran yang ditetapkan ke kelas ini.</p>
            </div>
            <a href="<?= site_url('admin/mapel') ?>" class="btn-secondary" style="padding: 6px 12px; font-size: 12px;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Kelola Mapel
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
                    <?php if (!empty($mapel_list)): ?>
                        <?php foreach ($mapel_list as $m): ?>
                            <tr>
                                <td style="font-weight: 600;"><?= htmlspecialchars($m->nama_mapel) ?></td>
                                <td class="muted"><?= htmlspecialchars($m->nama_guru ?: '-') ?></td>
                                <td style="text-align: center;"><?= number_format($m->jumlah_siswa) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center; color: var(--text-muted); padding: 28px;">
                                Belum ada mapel pada kelas ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fTingkat = document.getElementById('f_tingkat');
    const fJurusan = document.getElementById('f_jurusan');
    const fSearch  = document.getElementById('f_search');
    const container = document.getElementById('search-result-container');
    const placeholder = document.getElementById('search-placeholder');
    const target = document.getElementById('siswa-list-target');
    const checkAll = document.getElementById('check-all');
    const selectedCountEl = document.getElementById('selected-count');
    const batchForm = document.getElementById('batch-form');

    let debounceTimer;

    const performSearch = () => {
        const tingkat = fTingkat.value.trim();
        const jurusan = fJurusan.value.trim();
        const search  = fSearch.value.trim();

        if (tingkat === '' && jurusan === '' && search === '') {
            container.style.display = 'none';
            placeholder.style.display = 'block';
            return;
        }

        const url = `<?= site_url('admin/ajax_get_siswa_tersedia') ?>?tingkat=${encodeURIComponent(tingkat)}&jurusan=${encodeURIComponent(jurusan)}&search=${encodeURIComponent(search)}`;

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.json())
            .then(data => {
                target.innerHTML = '';
                if (data.length === 0) {
                    target.innerHTML = '<tr><td colspan="3" class="empty-note">Tidak ada siswa ditemukan</td></tr>';
                } else {
                    data.forEach(s => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td style="text-align:center;"><input type="checkbox" name="siswa_ids[]" value="${s.id}" class="siswa-checkbox"></td>
                            <td>
                                <div style="font-weight:600; color:var(--text-main); font-size:14px;">${s.nama}</div>
                                <div class="muted" style="font-size:12px;">${s.email}</div>
                            </td>
                            <td>
                                <span class="tingkat-chip" style="font-size:11px; padding:2px 8px;">${s.tingkat || '-'}</span>
                                <span class="muted" style="font-size:11px; margin-left:4px;">${s.jurusan || '-'}</span>
                            </td>
                        `;
                        target.appendChild(tr);
                    });
                }
                container.style.display = 'block';
                placeholder.style.display = 'none';
                updateCount();
            });
    };

    [fTingkat, fJurusan, fSearch].forEach(el => {
        el.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(performSearch, 400);
        });
    });

    checkAll.addEventListener('change', function() {
        document.querySelectorAll('.siswa-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateCount();
    });

    target.addEventListener('change', (e) => {
        if (e.target.classList.contains('siswa-checkbox')) {
            updateCount();
        }
    });

    function updateCount() {
        const count = document.querySelectorAll('.siswa-checkbox:checked').length;
        selectedCountEl.textContent = `${count} terpilih`;
    }

    batchForm.addEventListener('submit', function(e) {
        const count = document.querySelectorAll('.siswa-checkbox:checked').length;
        if (count === 0) {
            e.preventDefault();
            alert('Pilih minimal satu siswa!');
        }
    });
});
</script>
