<style>
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 18px;
    }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--card-border);
        border-radius: 10px;
        font-size: 14px;
        font-family: inherit;
        background: #FFFFFF;
        color: var(--text-main);
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    .form-hint {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 6px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 24px;
    }

    .validation-errors {
        background: #FDE8E8;
        color: #9B1C1C;
        border: 1px solid #FBD5D5;
        border-radius: 10px;
        padding: 14px 18px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .validation-errors ul {
        margin: 0;
        padding-left: 18px;
    }
</style>

<?php
    $is_edit = ($mode === 'edit');
    $val = function ($field, $fallback = '') use ($is_edit, $rombel) {
        if (set_value($field) !== '') {
            return set_value($field);
        }
        return $is_edit && isset($rombel->$field) ? $rombel->$field : $fallback;
    };
?>

<div class="card">
    <div class="card-header-flex">
        <div>
            <h3 style="font-size: 18px; font-weight: 700;"><?= $is_edit ? 'Edit Rombel' : 'Tambah Rombel' ?></h3>
            <p style="font-size: 13px; color: var(--text-muted);">
                <?= $is_edit ? 'Perbarui informasi rombel. Perubahan berlaku untuk seluruh siswa di dalamnya.' : 'Buat rombel baru untuk mengelompokkan siswa berdasarkan tingkat dan jurusan.' ?>
            </p>
        </div>
        <a href="<?= site_url('admin/rombel') ?>" class="btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <?php if (validation_errors()): ?>
        <div class="validation-errors">
            <?= validation_errors('<div>', '</div>') ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= $is_edit ? site_url('admin/edit_rombel/' . $rombel->id) : site_url('admin/tambah_rombel') ?>">
        <div class="form-grid">
            <div class="form-group">
                <label for="nama_rombel">Nama Rombel</label>
                <input type="text" id="nama_rombel" name="nama_rombel" maxlength="100" required
                       value="<?= htmlspecialchars($val('nama_rombel')) ?>"
                       placeholder="Contoh: XI PPLG 1">
            </div>

            <div class="form-group">
                <label for="tingkat">Tingkat</label>
                <select id="tingkat" name="tingkat" required>
                    <?php $tingkat_val = $val('tingkat'); ?>
                    <option value="X" <?= $tingkat_val === 'X' ? 'selected' : '' ?>>Kelas X</option>
                    <option value="XI" <?= $tingkat_val === 'XI' ? 'selected' : '' ?>>Kelas XI</option>
                    <option value="XII" <?= $tingkat_val === 'XII' ? 'selected' : '' ?>>Kelas XII</option>
                </select>
            </div>

            <div class="form-group">
                <label for="jurusan">Jurusan</label>
                <input type="text" id="jurusan" name="jurusan" maxlength="50"
                       value="<?= htmlspecialchars($val('jurusan')) ?>"
                       placeholder="Contoh: PPLG">
                <div class="form-hint">Opsional. Ditampilkan sebagai keterangan rombel.</div>
            </div>

            <div class="form-group">
                <label for="kode_rombel">Kode Rombel</label>
                <input type="text" id="kode_rombel" name="kode_rombel" maxlength="20"
                       value="<?= htmlspecialchars($val('kode_rombel')) ?>"
                       placeholder="<?= $is_edit ? '' : 'Kosongkan untuk dibuat otomatis' ?>"
                       <?= $is_edit ? 'required' : '' ?>>
                <div class="form-hint">Kode ini dibagikan ke siswa untuk bergabung ke rombel.</div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-floppy-disk"></i>
                <?= $is_edit ? 'Simpan Perubahan' : 'Tambah Rombel' ?>
            </button>
            <a href="<?= site_url('admin/rombel') ?>" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
