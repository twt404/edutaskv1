<div style="max-width: 680px; margin: 0 auto;">
  <div style="margin-bottom: 20px;">
    <a href="<?= site_url('guru/mapel') ?>" style="display: inline-flex; align-items: center; gap: 8px; color: var(--text-muted); font-size: 0.88rem; font-weight: 500;">
      <i class="fa-solid fa-arrow-left"></i>
      <span>Kembali ke Daftar Mapel</span>
    </a>
  </div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title"><?= isset($mapel) ? 'Edit Mapel' : 'Tambah Mapel Baru' ?></h3>
    </div>

    <?= form_open(isset($mapel) ? 'guru/edit_mapel/' . $mapel->id : 'guru/tambah_mapel') ?>
      <?php if (isset($mapel)): ?>
        <div class="form-group">
          <label class="form-label">Kelas</label>
          <div style="display: inline-flex; align-items: center; gap: 8px; background: #EEF2FF; color: #3730A3; border-radius: 999px; padding: 6px 14px; font-size: 0.85rem; font-weight: 700;">
            <i class="fa-solid fa-people-group"></i>
            <?= html_escape($mapel->nama_kelas ?: 'Belum ada kelas') ?>
          </div>
          <div class="form-text">Kelas hanya dapat diubah oleh Admin melalui menu Mapel.</div>
        </div>
      <?php else: ?>
        <div class="form-group">
          <label class="form-label">Kelas <span style="color: #EF4444;">*</span></label>
          <?php if (!empty($kelas_list)): ?>
            <select name="kelas_id" class="form-control" required>
              <option value="">— Pilih kelas —</option>
              <?php foreach ($kelas_list as $k): ?>
                <option value="<?= (int) $k->id ?>" <?= set_select('kelas_id', (string) $k->id) ?>>
                  <?= html_escape($k->tingkat . ' · ' . $k->nama_kelas) ?><?= !empty($k->jurusan) ? ' (' . html_escape($k->jurusan) . ')' : '' ?>
                </option>
              <?php endforeach; ?>
            </select>
          <?php else: ?>
            <div class="form-text" style="color: #B45309;">
              Belum ada kelas. Hubungi Admin untuk membuat kelas terlebih dahulu.
            </div>
          <?php endif; ?>
          <?= form_error('kelas_id', '<div class="form-text" style="color: #EF4444;">', '</div>') ?>
        </div>
      <?php endif; ?>

      <div class="form-group">
        <label class="form-label">Nama Mata Pelajaran <span style="color: #EF4444;">*</span></label>
        <input type="text" name="nama_mapel" class="form-control" placeholder="Contoh: Pemrograman Web Lanjut" value="<?= set_value('nama_mapel', isset($mapel) ? $mapel->nama_mapel : '') ?>" required>
        <?= form_error('nama_mapel', '<div class="form-text" style="color: #EF4444;">', '</div>') ?>
      </div>

      <div class="form-group">
        <label class="form-label">Deskripsi Mata Pelajaran</label>
        <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan mengenai mata pelajaran, kompetensi, atau tujuan pembelajaran..."><?= set_value('deskripsi', isset($mapel) ? $mapel->deskripsi : '') ?></textarea>
        <?= form_error('deskripsi', '<div class="form-text" style="color: #EF4444;">', '</div>') ?>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 28px;">
        <a href="<?= site_url('guru/mapel') ?>" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary" <?= (!isset($mapel) && empty($kelas_list)) ? 'disabled' : '' ?>>
          <i class="fa-solid fa-floppy-disk"></i>
          <span><?= isset($mapel) ? 'Perbarui Mapel' : 'Simpan Mapel' ?></span>
        </button>
      </div>
    <?= form_close() ?>
  </div>
</div>
