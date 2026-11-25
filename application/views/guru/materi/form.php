<div style="max-width: 680px; margin: 0 auto;">
  <div style="margin-bottom: 20px;">
    <a href="<?= site_url('guru/detail_mapel/' . $mapel->id) ?>" style="display: inline-flex; align-items: center; gap: 8px; color: var(--text-muted); font-size: 0.88rem; font-weight: 500;">
      <i class="fa-solid fa-arrow-left"></i>
      <span>Kembali ke Mapel <?= html_escape($mapel->nama_mapel) ?></span>
    </a>
  </div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title"><?= isset($materi) ? 'Edit Materi' : 'Tambah Materi Baru' ?></h3>
      <span class="badge" style="background: #EFF6FF; color: var(--primary);">
        Mapel: <?= html_escape($mapel->nama_mapel) ?>
      </span>
    </div>

    <?= form_open_multipart(isset($materi) ? 'guru/edit_materi/' . $materi->id : 'guru/tambah_materi/' . $mapel->id) ?>
      <div class="form-group">
        <label class="form-label">Judul Materi <span style="color: #EF4444;">*</span></label>
        <input type="text" name="judul" class="form-control" placeholder="Contoh: Modul 1 - Pengenalan Basis Data" value="<?= set_value('judul', isset($materi) ? $materi->judul : '') ?>" required>
        <?= form_error('judul', '<div class="form-text" style="color: #EF4444;">', '</div>') ?>
      </div>

      <?php if (isset($materi)): ?>
        <div class="form-group">
          <label class="form-label">Nomor Urutan</label>
          <input type="number" name="urutan" class="form-control" value="<?= set_value('urutan', $materi->urutan) ?>" style="max-width: 140px;" min="1">
        </div>
      <?php endif; ?>

      <div class="form-group">
        <label class="form-label">Deskripsi / Rangkuman Singkat</label>
        <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan ringkasan materi atau instruksi bacaan..."><?= set_value('deskripsi', isset($materi) ? $materi->deskripsi : '') ?></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Berkas Materi (PDF, DOCX, PPTX, ZIP, Gambar)</label>
        <?php if (isset($materi) && !empty($materi->file_materi)): ?>
          <div style="margin-bottom: 8px; font-size: 0.85rem; color: var(--text-muted);">
            Berkas saat ini: <a href="<?= base_url('uploads/materi/' . $materi->file_materi) ?>" target="_blank" style="color: var(--primary); font-weight: 600;"><?= html_escape($materi->file_materi) ?></a>
          </div>
        <?php endif; ?>
        <input type="file" name="file_materi" class="form-control" style="height: auto; padding: 10px;">
        <div class="form-text">Maksimal ukuran berkas: 20MB. Format yang didukung: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, ZIP, Gambar.</div>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 28px;">
        <a href="<?= site_url('guru/detail_mapel/' . $mapel->id) ?>" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-floppy-disk"></i>
          <span><?= isset($materi) ? 'Perbarui Materi' : 'Simpan Materi' ?></span>
        </button>
      </div>
    <?= form_close() ?>
  </div>
</div>
