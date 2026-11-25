<div style="max-width: 680px; margin: 0 auto;">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Profil Guru</h3>
    </div>

    <?= form_open_multipart('guru/profil') ?>
      <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid var(--card-border);">
        <div style="width: 72px; height: 72px; border-radius: 50%; background: #EFF6FF; border: 2px solid var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: var(--primary); overflow: hidden; flex-shrink: 0;">
          <?php if (!empty($guru->foto)): ?>
            <img src="<?= base_url('uploads/foto/' . $guru->foto) ?>" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover;">
          <?php else: ?>
            <i class="fa-solid fa-user"></i>
          <?php endif; ?>
        </div>
        <div>
          <label class="form-label" style="margin-bottom: 4px;">Ubah Foto Profil</label>
          <input type="file" name="foto" class="form-control" style="height: auto; padding: 6px 10px; font-size: 0.85rem;">
          <div class="form-text">Maksimal 2MB (JPG, JPEG, PNG, WEBP).</div>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Nama Lengkap <span style="color: #EF4444;">*</span></label>
        <input type="text" name="nama" class="form-control" value="<?= set_value('nama', $guru->nama) ?>" required>
        <?= form_error('nama', '<div class="form-text" style="color: #EF4444;">', '</div>') ?>
      </div>

      <div class="form-group">
        <label class="form-label">Alamat Email <span style="color: #EF4444;">*</span></label>
        <input type="email" name="email" class="form-control" value="<?= set_value('email', $guru->email) ?>" required>
        <?= form_error('email', '<div class="form-text" style="color: #EF4444;">', '</div>') ?>
      </div>

      <div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--card-border);">
        <h4 style="font-size: 1.05rem; font-weight: 700; margin-bottom: 6px;">Ganti Kata Sandi</h4>
        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 16px;">Biarkan kosong jika Anda tidak ingin mengubah kata sandi.</p>

        <div class="form-group">
          <label class="form-label">Kata Sandi Saat Ini</label>
          <input type="password" name="password_lama" class="form-control" placeholder="Masukkan sandi saat ini">
        </div>

        <div class="form-group">
          <label class="form-label">Kata Sandi Baru</label>
          <input type="password" name="password_baru" class="form-control" placeholder="Minimal 6 karakter">
        </div>

        <div class="form-group">
          <label class="form-label">Konfirmasi Kata Sandi Baru</label>
          <input type="password" name="konfirmasi_password" class="form-control" placeholder="Ulangi sandi baru">
        </div>
      </div>

      <div style="display: flex; justify-content: flex-end; margin-top: 28px;">
        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-floppy-disk"></i>
          <span>Simpan Perubahan</span>
        </button>
      </div>
    <?= form_close() ?>
  </div>
</div>
