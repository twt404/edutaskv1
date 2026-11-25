<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
  <div>
    <h2 style="font-size: 1.4rem; font-weight: 800; color: #0F172A;">Gabung Kelas</h2>
    <p style="color: var(--text-muted); font-size: 0.9rem;">Masukkan kode kelas dari Admin untuk terdaftar di seluruh mapel pada kelas tersebut</p>
  </div>
  <a href="<?= site_url('siswa/kelas') ?>" class="btn btn-secondary">
    <i class="fa-solid fa-arrow-left"></i> Kembali
  </a>
</div>

<?php if (!empty($kelas)): ?>
  <div class="card" style="background: linear-gradient(135deg, #F7FDFF 0%, #EEFFF8 100%); border-color: #CFE9F4;">
    <h3 class="card-title" style="margin-bottom: 10px;">
      <i class="fa-solid fa-circle-check" style="color: var(--primary); margin-right: 8px;"></i>
      Anda Sudah Tergabung
    </h3>
    <p style="font-size: 0.95rem; color: #334155; line-height: 1.7;">
      Anda terdaftar pada kelas
      <strong><?= html_escape($kelas->nama_kelas) ?></strong>
      (Tingkat <?= html_escape($kelas->tingkat ?: '-') ?><?= !empty($kelas->jurusan) ? ' · ' . html_escape($kelas->jurusan) : '' ?>).
      Seluruh mata pelajaran pada kelas ini otomatis terdaftar di akun Anda.
    </p>
    <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 10px;">
      <i class="fa-solid fa-circle-info"></i>
      Untuk pindah kelas, silakan hubungi Admin.
    </p>
    <a href="<?= site_url('siswa/kelas') ?>" class="btn btn-primary" style="margin-top: 16px;">
      <i class="fa-solid fa-chalkboard-user"></i> Lihat Kelas Saya
    </a>
  </div>
<?php else: ?>
  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; align-items: start;">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fa-solid fa-people-group" style="color: var(--primary); margin-right: 8px;"></i>
          Masukkan Kode Kelas
        </h3>
      </div>

      <form action="<?= site_url('siswa/gabung_kelas') ?>" method="post" autocomplete="off">
        <div class="form-group">
          <label class="form-label">Kode Kelas <span style="color: #BE123C;">*</span></label>
          <input type="text" name="kode_kelas" class="form-control" placeholder="Contoh: K11PLG" maxlength="20" required
                 style="height: 52px; font-size: 1.2rem; font-weight: 700; letter-spacing: 4px; text-transform: uppercase; font-family: monospace;"
                 value="<?= html_escape(set_value('kode_kelas')) ?>">
          <div class="form-text">Kode kelas terdiri dari kombinasi huruf dan angka.</div>
        </div>

        <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
          <i class="fa-solid fa-right-to-bracket"></i> Gabung Kelas
        </button>
      </form>
    </div>

    <div class="card" style="background: linear-gradient(135deg, #F7FDFF 0%, #EEFFF8 100%); border-color: #CFE9F4;">
      <h3 class="card-title" style="margin-bottom: 12px;">
        <i class="fa-solid fa-circle-info" style="color: var(--primary); margin-right: 8px;"></i>
        Cara Mendapatkan Kode Kelas
      </h3>
      <ul style="list-style: none; display: flex; flex-direction: column; gap: 12px; font-size: 0.9rem; color: #334155; line-height: 1.6;">
        <li style="display: flex; gap: 10px;">
          <i class="fa-solid fa-1" style="color: var(--primary); margin-top: 3px;"></i>
          <span>Hubungi Admin sekolah untuk mendapatkan kode kelas Anda.</span>
        </li>
        <li style="display: flex; gap: 10px;">
          <i class="fa-solid fa-2" style="color: var(--primary); margin-top: 3px;"></i>
          <span>Masukkan kode kelas pada formulir di samping.</span>
        </li>
        <li style="display: flex; gap: 10px;">
          <i class="fa-solid fa-3" style="color: var(--primary); margin-top: 3px;"></i>
          <span>Anda akan otomatis terdaftar di semua mata pelajaran kelas tersebut.</span>
        </li>
      </ul>
    </div>
  </div>
<?php endif; ?>

<style>
  @media (max-width: 820px) {
    .page-content > div[style*="grid-template-columns: 1fr 1fr"] {
      grid-template-columns: 1fr !important;
    }
  }
</style>
