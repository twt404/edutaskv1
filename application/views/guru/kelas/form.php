<div style="max-width: 680px; margin: 0 auto;">
  <div style="margin-bottom: 20px;">
    <a href="<?= site_url('guru/kelas') ?>" style="display: inline-flex; align-items: center; gap: 8px; color: var(--text-muted); font-size: 0.88rem; font-weight: 500;">
      <i class="fa-solid fa-arrow-left"></i>
      <span>Kembali ke Daftar Kelas</span>
    </a>
  </div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title"><?= isset($kelas) ? 'Edit Kelas' : 'Tambah Kelas Baru' ?></h3>
    </div>

    <?= form_open(isset($kelas) ? 'guru/edit_kelas/' . $kelas->id : 'guru/tambah_kelas') ?>
      <?php if (isset($kelas)): ?>
        <div class="form-group">
          <label class="form-label">Rombel</label>
          <div style="display: inline-flex; align-items: center; gap: 8px; background: #EEF2FF; color: #3730A3; border-radius: 999px; padding: 6px 14px; font-size: 0.85rem; font-weight: 700;">
            <i class="fa-solid fa-people-group"></i>
            <?= html_escape($kelas->nama_rombel ?: 'Belum ada rombel') ?>
          </div>
          <div class="form-text">Rombel hanya dapat diubah oleh Admin melalui menu Rombel.</div>
        </div>
      <?php else: ?>
        <div class="form-group">
          <label class="form-label">Rombel <span style="color: #EF4444;">*</span></label>
          <?php if (!empty($rombel_list)): ?>
            <select name="rombel_id" class="form-control" required>
              <option value="">— Pilih rombel —</option>
              <?php foreach ($rombel_list as $r): ?>
                <option value="<?= (int) $r->id ?>" <?= set_select('rombel_id', (string) $r->id) ?>>
                  <?= html_escape($r->tingkat . ' · ' . $r->nama_rombel) ?><?= !empty($r->jurusan) ? ' (' . html_escape($r->jurusan) . ')' : '' ?>
                </option>
              <?php endforeach; ?>
            </select>
          <?php else: ?>
            <div class="form-text" style="color: #B45309;">
              Belum ada rombel. Hubungi Admin untuk membuat rombel terlebih dahulu.
            </div>
          <?php endif; ?>
          <?= form_error('rombel_id', '<div class="form-text" style="color: #EF4444;">', '</div>') ?>
        </div>
      <?php endif; ?>

      <div class="form-group">
        <label class="form-label">Nama Mata Pelajaran <span style="color: #EF4444;">*</span></label>
        <input type="text" name="nama_kelas" class="form-control" placeholder="Contoh: Pemrograman Web Lanjut" value="<?= set_value('nama_kelas', isset($kelas) ? $kelas->nama_kelas : '') ?>" required>
        <?= form_error('nama_kelas', '<div class="form-text" style="color: #EF4444;">', '</div>') ?>
      </div>

      <div class="form-group">
        <label class="form-label">Deskripsi Mata Pelajaran</label>
        <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan mengenai mata pelajaran, kompetensi, atau tujuan pembelajaran..."><?= set_value('deskripsi', isset($kelas) ? $kelas->deskripsi : '') ?></textarea>
        <?= form_error('deskripsi', '<div class="form-text" style="color: #EF4444;">', '</div>') ?>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 28px;">
        <a href="<?= site_url('guru/kelas') ?>" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary" <?= (!isset($kelas) && empty($rombel_list)) ? 'disabled' : '' ?>>
          <i class="fa-solid fa-floppy-disk"></i>
          <span><?= isset($kelas) ? 'Perbarui Kelas' : 'Simpan Kelas' ?></span>
        </button>
      </div>
    <?= form_close() ?>
  </div>
</div>
