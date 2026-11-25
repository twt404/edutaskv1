<?php
  $legacy_files = isset($legacy_files) ? $legacy_files : array();
  $legacy_file  = isset($legacy_file) ? $legacy_file : '';
?>

<?php if (!empty($tugas_items)): ?>
  <div style="display: flex; flex-direction: column; gap: 8px;">
    <?php foreach ($tugas_items as $item): ?>
      <?php
        $item_id = (int) $item->id;
        $files   = isset($item_files[$item_id]) ? (array) $item_files[$item_id] : array();
      ?>
      <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; border-bottom: 1px solid #F1F5F9; padding-bottom: 8px;">
        <div style="min-width: 0;">
          <div style="font-size: 0.82rem; font-weight: 700; color: var(--text-main);"><?= html_escape($item->label) ?></div>
          <?php if (!empty($item->instruksi)): ?>
            <div style="font-size: 0.73rem; color: var(--text-muted); line-height: 1.4;"><?= nl2br(html_escape($item->instruksi)) ?></div>
          <?php endif; ?>
        </div>
        <div style="flex-shrink: 0; text-align: right;">
          <?php if (!empty($files)): ?>
            <div style="display: flex; flex-direction: column; gap: 4px;">
              <?php foreach ($files as $file): ?>
                <?php $file_label = !empty($file->nama_asli) ? $file->nama_asli : $file->nama_file; ?>
                <a href="<?= base_url('uploads/jawaban/' . $file->nama_file) ?>" target="_blank" class="btn btn-secondary btn-sm" style="font-size: 0.74rem; padding: 4px 8px;">
                  <i class="fa-solid fa-download"></i> <?= html_escape($file_label) ?>
                </a>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <span style="font-size: 0.74rem; color: var(--text-muted);">Belum diunggah</span>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php elseif (!empty($legacy_files)): ?>
  <div style="display: flex; flex-direction: column; gap: 6px;">
    <?php foreach ($legacy_files as $file): ?>
      <?php $file_label = !empty($file->nama_asli) ? $file->nama_asli : $file->nama_file; ?>
      <a href="<?= base_url('uploads/jawaban/' . $file->nama_file) ?>" target="_blank" class="btn btn-secondary btn-sm" style="margin-top: 6px;" download>
        <i class="fa-solid fa-download" style="color: var(--primary);"></i> <?= html_escape($file_label) ?>
      </a>
    <?php endforeach; ?>
  </div>
<?php elseif (!empty($legacy_file)): ?>
  <a href="<?= base_url('uploads/jawaban/' . $legacy_file) ?>" target="_blank" class="btn btn-secondary btn-sm" style="margin-top: 6px;" download>
    <i class="fa-solid fa-download" style="color: var(--primary);"></i> Unduh Berkas Jawaban
  </a>
<?php endif; ?>
