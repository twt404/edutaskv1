<style>
    .filter-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .search-form {
        display: flex;
        gap: 12px;
        flex: 1;
        max-width: 600px;
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--card-border);
        border-radius: 10px;
        font-family: inherit;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        border-color: var(--primary);
    }

    .form-select {
        padding: 10px 14px;
        border: 1px solid var(--card-border);
        border-radius: 10px;
        font-family: inherit;
        font-size: 14px;
        background-color: #FFF;
        outline: none;
        cursor: pointer;
    }

    .action-links {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-edit {
        background-color: #F1F5F9;
        color: #1E293B;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: background 0.2s;
    }

    .btn-edit:hover {
        background-color: #E2E8F0;
    }

    .pagination-wrapper {
        margin-top: 24px;
        display: flex;
        justify-content: flex-end;
    }

    .pagination-wrapper a, .pagination-wrapper strong {
        padding: 8px 14px;
        margin-left: 4px;
        border: 1px solid var(--card-border);
        border-radius: 8px;
        font-size: 13px;
        text-decoration: none;
        color: var(--text-main);
    }

    .pagination-wrapper strong {
        background-color: var(--primary);
        color: #FFFFFF;
        border-color: var(--primary);
    }

    .bulk-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        padding: 12px 14px;
        margin-bottom: 14px;
        border: 1px solid #FECACA;
        border-radius: 12px;
        background: #FFF7F7;
    }

    .bulk-info {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #7F1D1D;
    }

    .bulk-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .bulk-count {
        font-size: 13px;
        font-weight: 600;
        color: #B91C1C;
    }

    .btn-danger {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 14px;
        border: none;
        border-radius: 9px;
        background: #DC2626;
        color: #FFFFFF;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-danger:hover:not(:disabled) {
        background: #B91C1C;
    }

    .btn-danger:disabled {
        opacity: 0.45;
        cursor: not-allowed;
    }

    .btn-danger-outline {
        background: #FFF1F2;
        color: #B91C1C;
        border: 1px solid #FECDD3;
    }

    .btn-danger-outline:hover:not(:disabled) {
        background: #FFE4E6;
    }

    .user-check {
        width: 16px;
        height: 16px;
        accent-color: #DC2626;
        cursor: pointer;
        vertical-align: middle;
    }

    .user-check:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }
</style>

<div class="card">
    <div class="card-header-flex">
        <div>
            <h3 style="font-size: 18px; font-weight: 700;">Daftar Pengguna</h3>
            <p style="font-size: 13px; color: var(--text-muted);">Total <?= number_format($total_rows ?? 0) ?> pengguna terdaftar di platform</p>
        </div>
        <a href="<?= site_url('admin/tambah_pengguna') ?>" class="btn-primary">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Pengguna</span>
        </a>
    </div>

    <!-- Filter Bar -->
    <form method="GET" action="<?= site_url('admin/pengguna') ?>" class="filter-bar">
        <div class="search-form">
            <input type="text" name="search" class="form-control" placeholder="Cari nama atau email pengguna..." value="<?= htmlspecialchars($search ?? '') ?>">
            <select name="role" class="form-select">
                <option value="">Semua Role</option>
                <option value="admin" <?= (isset($role_filter) && $role_filter === 'admin') ? 'selected' : '' ?>>Admin</option>
                <option value="guru" <?= (isset($role_filter) && $role_filter === 'guru') ? 'selected' : '' ?>>Guru</option>
                <option value="siswa" <?= (isset($role_filter) && $role_filter === 'siswa') ? 'selected' : '' ?>>Siswa</option>
            </select>
            <select name="tingkat" class="form-select">
                <option value="">Semua Tingkat</option>
                <option value="X" <?= (isset($tingkat_filter) && $tingkat_filter === 'X') ? 'selected' : '' ?>>X</option>
                <option value="XI" <?= (isset($tingkat_filter) && $tingkat_filter === 'XI') ? 'selected' : '' ?>>XI</option>
                <option value="XII" <?= (isset($tingkat_filter) && $tingkat_filter === 'XII') ? 'selected' : '' ?>>XII</option>
            </select>
            <button type="submit" class="btn-secondary">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span>Filter</span>
            </button>
        </div>
        <?php if (!empty($search) || !empty($role_filter) || !empty($tingkat_filter)): ?>
            <a href="<?= site_url('admin/pengguna') ?>" class="btn-secondary" style="font-size: 13px;">
                <i class="fa-solid fa-rotate-left"></i>
                <span>Reset</span>
            </a>
        <?php endif; ?>
    </form>

    <form method="post" action="<?= site_url('admin/hapus_pengguna_batch') ?>" id="bulkUserForm">
        <div class="bulk-toolbar">
            <div class="bulk-info">
                <i class="fa-solid fa-circle-info"></i>
                <span>Guru yang masih mengampu mapel dilewati pada hapus biasa. Gunakan Hapus Paksa untuk menghapus akun beserta mapel dan data terkait.</span>
            </div>
            <div class="bulk-actions">
                <span class="bulk-count" id="bulkCount">0 dipilih</span>
                <input type="hidden" name="force" id="bulkForceInput" value="0">
                <button type="submit" class="btn-danger" id="bulkDeleteBtn" disabled>
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Hapus Terpilih</span>
                </button>
                <button type="button" class="btn-danger btn-danger-outline" id="bulkForceBtn" disabled>
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Hapus Paksa</span>
                </button>
            </div>
        </div>

        <div class="data-table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 44px;">
                            <input type="checkbox" id="selectAllUsers" class="user-check" aria-label="Pilih semua pengguna di halaman ini">
                        </th>
                        <th style="width: 50px;">No</th>
                        <th>Nama Lengkap</th>
                        <th>Alamat Email</th>
                        <th>Role</th>
                        <th>Tingkat/Jurusan</th>
                        <th>Tanggal Daftar</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php 
                        $page = (int)$this->input->get('page');
                        $no = ($page > 1 ? ($page - 1) * 15 : 0) + 1;
                        ?>
                        <?php foreach ($users as $u): ?>
                            <?php
                            $mapel_total = (int) ($mapel_counts[$u->id] ?? 0);
                            $is_self = $this->session->userdata('user_id') == $u->id;
                            ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="user_ids[]" value="<?= (int) $u->id ?>" class="user-check user-checkbox"
                                        data-mapel="<?= $mapel_total ?>"
                                        <?= $is_self ? 'disabled title="Akun Anda sendiri tidak dapat dihapus"' : '' ?>>
                                </td>
                                <td><?= $no++ ?></td>
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
                                    <?php if ($mapel_total > 0): ?>
                                        <span style="display: block; margin-top: 6px; font-size: 11px; font-weight: 600; color: #B45309;">
                                            <i class="fa-solid fa-book"></i> <?= $mapel_total ?> mapel
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($u->role === 'siswa'): ?>
                                        <span style="font-size: 13px; font-weight: 600; color: var(--text-main);">
                                            <?= htmlspecialchars($u->tingkat ?: '-') ?>
                                        </span>
                                        <span style="font-size: 12px; color: var(--text-muted); margin-left: 4px;">
                                            <?= htmlspecialchars($u->jurusan ?: '-') ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td style="color: var(--text-muted); font-size: 13px;">
                                    <?= date('d M Y, H:i', strtotime($u->created_at)) ?>
                                </td>
                                <td style="text-align: right;">
                                    <div class="action-links" style="justify-content: flex-end;">
                                        <a href="<?= site_url('admin/edit_pengguna/' . $u->id) ?>" class="btn-edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>Edit</span>
                                        </a>
                                        <?php if (!$is_self): ?>
                                            <?php if ($mapel_total > 0): ?>
                                                <a href="<?= site_url('admin/hapus_pengguna/' . $u->id . '?force=1') ?>" class="btn-danger-light" onclick="return confirm('Hapus paksa akun <?= htmlspecialchars(addslashes($u->nama)) ?>? <?= $mapel_total ?> mapel beserta materi, tugas, kelompok, dan nilai terkait akan ikut terhapus permanen.')">
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                    <span>Hapus Paksa</span>
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= site_url('admin/hapus_pengguna/' . $u->id) ?>" class="btn-danger-light" onclick="return confirm('Apakah Anda yakin ingin menghapus akun <?= htmlspecialchars(addslashes($u->nama)) ?>?')">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                    <span>Hapus</span>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 36px;">
                                Tidak ada pengguna ditemukan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </form>

    <?php if (!empty($pagination)): ?>
        <div class="pagination-wrapper">
            <?= $pagination ?>
        </div>
    <?php endif; ?>
</div>

<script>
(function () {
    var form = document.getElementById('bulkUserForm');
    if (!form) return;

    var selectAll = document.getElementById('selectAllUsers');
    var checkboxes = Array.prototype.slice.call(form.querySelectorAll('.user-checkbox:not(:disabled)'));
    var countLabel = document.getElementById('bulkCount');
    var deleteButton = document.getElementById('bulkDeleteBtn');
    var forceButton = document.getElementById('bulkForceBtn');
    var forceInput = document.getElementById('bulkForceInput');

    function selectedCount() {
        return checkboxes.filter(function (checkbox) {
            return checkbox.checked;
        }).length;
    }

    function selectedMapelCount() {
        return checkboxes.reduce(function (total, checkbox) {
            if (!checkbox.checked) return total;
            return total + (parseInt(checkbox.dataset.mapel, 10) || 0);
        }, 0);
    }

    function selectedLockedCount() {
        return checkboxes.filter(function (checkbox) {
            return checkbox.checked && (parseInt(checkbox.dataset.mapel, 10) || 0) > 0;
        }).length;
    }

    function syncSelection() {
        var count = selectedCount();

        if (countLabel) countLabel.textContent = count + ' dipilih';
        if (deleteButton) deleteButton.disabled = count === 0;
        if (forceButton) forceButton.disabled = count === 0;

        if (selectAll) {
            selectAll.checked = count > 0 && count === checkboxes.length;
            selectAll.indeterminate = count > 0 && count < checkboxes.length;
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(function (checkbox) {
                checkbox.checked = selectAll.checked;
            });
            syncSelection();
        });
    }

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', syncSelection);
    });

    if (forceButton) {
        forceButton.addEventListener('click', function () {
            var count = selectedCount();
            if (count === 0) return;

            var mapel = selectedMapelCount();
            var message = 'Hapus paksa ' + count + ' pengguna terpilih?';

            if (mapel > 0) {
                message += ' ' + mapel + ' mapel beserta materi, tugas, kelompok, dan nilai terkait akan ikut terhapus permanen.';
            }

            message += ' Tindakan ini tidak dapat dibatalkan.';

            if (!confirm(message)) return;

            if (forceInput) forceInput.value = '1';
            form.submit();
        });
    }

    form.addEventListener('submit', function (event) {
        var count = selectedCount();

        if (count === 0) {
            event.preventDefault();
            return;
        }

        if (forceInput) forceInput.value = '0';

        var message = 'Hapus ' + count + ' pengguna terpilih?';
        var locked = selectedLockedCount();

        if (locked > 0) {
            message += ' ' + locked + ' guru yang masih mengampu mapel akan dilewati. Gunakan Hapus Paksa untuk menghapusnya juga.';
        }

        message += ' Tindakan ini tidak dapat dibatalkan.';

        if (!confirm(message)) {
            event.preventDefault();
        }
    });

    syncSelection();
})();
</script>
