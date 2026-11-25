<style>
    .profile-container {
        max-width: 650px;
    }

    .avatar-preview-wrap {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
    }

    .profile-avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: #E2E8F0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: #475569;
        font-weight: 700;
        overflow: hidden;
        border: 2px solid var(--card-border);
    }

    .profile-avatar-large img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<div class="card profile-container">
    <div class="card-header-flex">
        <div>
            <h3 style="font-size: 18px; font-weight: 700;">Pengaturan Profil Admin</h3>
            <p style="font-size: 13px; color: var(--text-muted);">Perbarui detail profil akun administrator Anda</p>
        </div>
    </div>

    <?php if (validation_errors()): ?>
        <div class="alert alert-danger" style="margin-bottom: 20px; display: block;">
            <?= validation_errors('<div style="margin-bottom: 4px;">• ', '</div>') ?>
        </div>
    <?php endif; ?>

    <?= form_open_multipart('admin/profil') ?>
        <div class="avatar-preview-wrap">
            <div class="profile-avatar-large">
                <?php if (!empty($user->foto)): ?>
                    <img src="<?= base_url('uploads/foto/' . $user->foto) ?>" alt="Foto Profil">
                <?php else: ?>
                    <i class="fa-solid fa-user"></i>
                <?php endif; ?>
            </div>
            <div>
                <label class="form-label" for="foto" style="margin-bottom: 4px;">Foto Profil</label>
                <input type="file" id="foto" name="foto" accept="image/*" class="form-control" style="font-size: 13px; padding: 6px 10px;">
                <div class="form-hint" style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Format JPG, PNG, WEBP. Maks 2MB.</div>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label class="form-label" for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" class="form-control" 
                   value="<?= set_value('nama', $user->nama ?? '') ?>" required>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label class="form-label" for="email">Alamat Email</label>
            <input type="email" id="email" name="email" class="form-control" 
                   value="<?= set_value('email', $user->email ?? '') ?>" required>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label class="form-label" for="password">Ganti Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password baru">
            <div class="form-hint" style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Kosongkan jika tidak ingin mengubah password akun Anda saat ini.</div>
        </div>

        <div class="form-actions" style="margin-top: 32px;">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    <?= form_close() ?>
</div>
