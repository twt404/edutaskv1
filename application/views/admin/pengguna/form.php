<style>
    .form-container {
        max-width: 650px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--text-main);
    }

    .form-hint {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .form-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 32px;
    }
</style>

<div class="card form-container">
    <div class="card-header-flex">
        <div>
            <h3 style="font-size: 18px; font-weight: 700;">
                <?= ($mode === 'edit') ? 'Edit Data Pengguna' : 'Tambah Pengguna Baru' ?>
            </h3>
            <p style="font-size: 13px; color: var(--text-muted);">
                <?= ($mode === 'edit') ? 'Perbarui informasi dan role akses pengguna' : 'Lengkapi data untuk menambahkan pengguna ke dalam sistem' ?>
            </p>
        </div>
    </div>

    <?php if (validation_errors()): ?>
        <div class="alert alert-danger" style="margin-bottom: 20px; display: block;">
            <?= validation_errors('<div style="margin-bottom: 4px;">• ', '</div>') ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label class="form-label" for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" class="form-control" placeholder="Contoh: Muhammad Ali" 
                   value="<?= set_value('nama', $user->nama ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="email">Alamat Email</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="nama@edutask.id" 
                   value="<?= set_value('email', $user->email ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Minimal 6 karakter"
                   <?= ($mode === 'tambah') ? 'required' : '' ?>>
            <?php if ($mode === 'edit'): ?>
                <div class="form-hint">Kosongkan jika tidak ingin mengubah password akun ini.</div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label class="form-label" for="role">Hak Akses / Peran (Role)</label>
            <select id="role" name="role" class="form-control" required style="cursor: pointer;">
                <?php $current_role = set_value('role', $user->role ?? 'siswa'); ?>
                <option value="siswa" <?= ($current_role === 'siswa') ? 'selected' : '' ?>>Siswa (Student)</option>
                <option value="guru" <?= ($current_role === 'guru') ? 'selected' : '' ?>>Guru (Teacher)</option>
                <option value="admin" <?= ($current_role === 'admin') ? 'selected' : '' ?>>Administrator (Staff)</option>
            </select>
        </div>

        <div id="siswa_fields">
            <div class="form-group">
                <label class="form-label" for="tingkat">Tingkat</label>
                <input type="text" id="tingkat" name="tingkat" class="form-control" placeholder="Contoh: XI" 
                       value="<?= set_value('tingkat', $user->tingkat ?? '') ?>">
                <div class="form-hint">Digunakan untuk mempermudah penugasan kelas (contoh: X, XI, XII).</div>
            </div>

            <div class="form-group">
                <label class="form-label" for="jurusan">Jurusan</label>
                <input type="text" id="jurusan" name="jurusan" class="form-control" placeholder="Contoh: PPLG" 
                       value="<?= set_value('jurusan', $user->jurusan ?? '') ?>">
                <div class="form-hint">Singkatan jurusan siswa (contoh: PPLG, BD, TJKT).</div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Simpan Perubahan</span>
            </button>
            <a href="<?= site_url('admin/pengguna') ?>" class="btn-secondary">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const siswaFields = document.getElementById('siswa_fields');

        function toggleFields() {
            siswaFields.style.display = (roleSelect.value === 'siswa') ? 'block' : 'none';
        }

        roleSelect.addEventListener('change', toggleFields);
        toggleFields();
    });
</script>
