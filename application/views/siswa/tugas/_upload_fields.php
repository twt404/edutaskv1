<?php
  $item_files   = isset($item_files) ? $item_files : array();
  $legacy_files = isset($legacy_files) ? $legacy_files : array();
  $legacy_file  = isset($legacy_file) ? $legacy_file : '';
?>

<?php if (!empty($tugas_items)): ?>
  <div class="form-group">
    <label class="form-label">Berkas yang Diminta</label>
    <div style="display: flex; flex-direction: column; gap: 10px;">
      <?php foreach ($tugas_items as $item): ?>
        <?php
          $item_id        = (int) $item->id;
          $existing_files = isset($item_files[$item_id]) ? (array) $item_files[$item_id] : array();
        ?>
        <div style="border: 1px solid #D9ECF5; border-radius: 13px; padding: 12px; background: linear-gradient(135deg, #F9FEFF 0%, #F1FCF8 100%);">
          <div style="font-size: 0.86rem; font-weight: 700; color: var(--text-main); margin-bottom: 3px;">
            <?= html_escape($item->label) ?>
            <?php if (!empty($existing_files)): ?>
              <span style="font-size: 0.7rem; color: #059669; font-weight: 600; margin-left: 6px;">
                <i class="fa-solid fa-circle-check"></i> Tersimpan
              </span>
            <?php endif; ?>
          </div>
          <?php if (!empty($item->instruksi)): ?>
            <div style="font-size: 0.76rem; color: var(--text-muted); line-height: 1.45; margin-bottom: 8px;">
              <?= nl2br(html_escape($item->instruksi)) ?>
            </div>
          <?php endif; ?>
          <?php if (!empty($existing_files)): ?>
            <div style="font-size: 0.76rem; margin-bottom: 7px; display: flex; flex-direction: column; gap: 3px;">
              <?php foreach ($existing_files as $existing): ?>
                <?php $file_label = !empty($existing->nama_asli) ? $existing->nama_asli : $existing->nama_file; ?>
                <div>
                  <a href="<?= base_url('uploads/jawaban/' . $existing->nama_file) ?>" target="_blank" style="color: var(--primary-dark); font-weight: 600;">
                    <i class="fa-solid fa-paperclip"></i> <?= html_escape($file_label) ?>
                  </a>
                </div>
              <?php endforeach; ?>
              <span style="color: var(--text-muted);">Unggah berkas baru untuk mengganti berkas poin ini.</span>
            </div>
          <?php endif; ?>
          <input type="file" name="item_files_<?= $item_id ?>[]" class="form-control" style="padding-top: 6px;" multiple>
          <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap; margin-top: 8px;">
            <div class="form-text" style="margin: 0;">Bisa pilih beberapa berkas sekaligus untuk poin ini.</div>
            <button type="submit" formaction="<?= site_url('siswa/simpan_item/' . (int) $item->tugas_id . '/' . $item_id) ?>" formmethod="post" class="btn btn-primary btn-sm" onclick="return confirm('Simpan berkas untuk poin ini sekarang?');">
              <i class="fa-solid fa-floppy-disk"></i> Simpan Poin Ini
            </button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="form-text">Setiap poin dapat disimpan sendiri tanpa menunggu poin lain.</div>
  </div>
<?php else: ?>
  <div class="form-group">
    <label class="form-label">Berkas Jawaban</label>
    <?php if (!empty($legacy_files)): ?>
      <div style="font-size: 0.76rem; margin-bottom: 7px; display: flex; flex-direction: column; gap: 3px;">
        <?php foreach ($legacy_files as $existing): ?>
          <?php $file_label = !empty($existing->nama_asli) ? $existing->nama_asli : $existing->nama_file; ?>
          <div>
            <a href="<?= base_url('uploads/jawaban/' . $existing->nama_file) ?>" target="_blank" style="color: var(--primary-dark); font-weight: 600;">
              <i class="fa-solid fa-paperclip"></i> <?= html_escape($file_label) ?>
            </a>
          </div>
        <?php endforeach; ?>
        <span style="color: var(--text-muted);">Unggah berkas baru untuk mengganti berkas lama.</span>
      </div>
    <?php elseif (!empty($legacy_file)): ?>
      <div style="font-size: 0.76rem; margin-bottom: 7px;">
        <a href="<?= base_url('uploads/jawaban/' . $legacy_file) ?>" target="_blank" style="color: var(--primary-dark); font-weight: 600;">
          <i class="fa-solid fa-paperclip"></i> Berkas jawaban sebelumnya
        </a>
        <span style="color: var(--text-muted);">(unggah baru untuk mengganti)</span>
      </div>
    <?php endif; ?>
    <input type="file" name="file_jawaban[]" class="form-control" style="padding-top: 6px;" multiple>
    <div class="form-text">Bisa pilih beberapa berkas sekaligus. Semua jenis berkas diperbolehkan (maksimal 20MB per berkas).</div>
  </div>
<?php endif; ?>
