<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
  <div>
    <h2 style="font-size: 1.4rem; font-weight: 800; color: #0F172A;">Profil Siswa</h2>
    <p style="color: var(--text-muted); font-size: 0.9rem;">Kelola informasi pribadi, foto akun, dan kata sandi Anda</p>
  </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px; align-items: start;">
  <!-- Left Side: Profile Summary Card -->
  <div class="card" style="text-align: center;">
    <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #E0F5FF 0%, #E8FFF6 100%); border: 3px solid #BDEBFF; margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; overflow: hidden; box-shadow: 0 18px 34px -24px rgba(14, 116, 144, 0.55);">
      <?php if (!empty($user_data->foto)): ?>
        <img src="<?= base_url('uploads/foto/' . $user_data->foto) ?>" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover;">
      <?php else: ?>
        <i class="fa-solid fa-user-graduate" style="font-size: 3rem; color: var(--primary-dark);"></i>
      <?php endif; ?>
    </div>

    <h3 style="font-size: 1.2rem; font-weight: 800; color: #0F172A; margin-bottom: 4px;">
      <?= html_escape($user_data->nama) ?>
    </h3>
    <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 12px;">
      <?= html_escape($user_data->email) ?>
    </p>

    <div>
      <span class="badge" style="background: #E8FFF6; color: #047857; font-size: 0.8rem; padding: 6px 14px;">
        <i class="fa-solid fa-id-badge"></i> Siswa / Pelajar
      </span>
    </div>

    <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid var(--card-border); text-align: left; font-size: 0.85rem; color: var(--text-muted); display: flex; flex-direction: column; gap: 8px;">
      <div>
        <i class="fa-regular fa-calendar" style="margin-right: 6px;"></i> Bergabung sejak: 
        <strong style="color: #334155;"><?= date('d M Y', strtotime($user_data->created_at)) ?></strong>
      </div>
    </div>
  </div>

  <!-- Right Side: Edit Form -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        <i class="fa-solid fa-user-pen" style="color: var(--primary-dark); margin-right: 8px;"></i>
        Perbarui Informasi Akun
      </h3>
    </div>

    <form action="<?= site_url('siswa/profil') ?>" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label class="form-label">Nama Lengkap <span style="color: #BE123C;">*</span></label>
        <input type="text" name="nama" class="form-control" value="<?= html_escape($user_data->nama) ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">Alamat Email</label>
        <input type="email" class="form-control" value="<?= html_escape($user_data->email) ?>" disabled style="background: #F8FAFC; color: #64748B; cursor: not-allowed;">
        <div class="form-text">Alamat email dikelola oleh pihak sekolah / administrator.</div>
      </div>

      <div class="form-group">
        <label class="form-label">Foto Profil Baru (Opsional)</label>
        <input type="file" name="foto" class="form-control" style="padding-top: 6px;">
        <div class="form-text">Format: JPG, JPEG, PNG, WEBP (Maksimal 2MB)</div>
      </div>

      <hr style="border: 0; border-top: 1px solid var(--card-border); margin: 24px 0;">

      <h4 style="font-size: 1rem; font-weight: 700; color: #1E293B; margin-bottom: 16px;">
        <i class="fa-solid fa-key" style="color: #D97706; margin-right: 6px;"></i> Ganti Kata Sandi (Opsional)
      </h4>

      <div class="form-group">
        <label class="form-label">Password Lama</label>
        <input type="password" name="password_lama" class="form-control" placeholder="Masukkan password lama jika ingin mengubah">
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <div class="form-group">
          <label class="form-label">Password Baru</label>
          <input type="password" name="password_baru" class="form-control" placeholder="Minimal 6 karakter">
        </div>

        <div class="form-group">
          <label class="form-label">Konfirmasi Password Baru</label>
          <input type="password" name="konfirmasi_password" class="form-control" placeholder="Ulangi password baru">
        </div>
      </div>

      <div style="margin-top: 24px;">
        <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
          <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Profil
        </button>
      </div>
    </form>
  </div>
</div>

