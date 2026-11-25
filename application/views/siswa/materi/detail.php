<div style="margin-bottom: 20px;">
  <a href="<?= site_url('siswa/detail_mapel/' . $materi->mapel_id) ?>" class="btn btn-secondary btn-sm">
    <i class="fa-solid fa-arrow-left"></i> Kembali ke Mapel
  </a>
</div>

<div class="card">
  <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; margin-bottom: 16px;">
    <div>
      <span style="font-size: 0.8rem; color: var(--primary); font-weight: 700; text-transform: uppercase;">
        <i class="fa-solid fa-book-open"></i> Materi Pembelajaran
      </span>
      <h2 style="font-size: 1.5rem; font-weight: 800; color: #0F172A; margin-top: 4px;">
        <?= html_escape($materi->judul) ?>
      </h2>
    </div>

    <span style="font-size: 0.82rem; color: var(--text-muted); background: #F8FAFC; padding: 6px 12px; border-radius: 8px; border: 1px solid var(--card-border);">
      <i class="fa-regular fa-calendar-check"></i> <?= date('d M Y', strtotime($materi->created_at)) ?>
    </span>
  </div>

  <div style="border-top: 1px solid var(--card-border); padding-top: 20px; margin-top: 10px; margin-bottom: 24px;">
    <h4 style="font-size: 0.95rem; font-weight: 700; color: #1E293B; margin-bottom: 12px;">Isi Materi:</h4>
    <div style="font-size: 0.95rem; color: #334155; line-height: 1.7; background: #FAFAFA; border: 1px solid var(--card-border); padding: 24px; border-radius: 12px; white-space: pre-line;">
      <?= nl2br(html_escape($materi->deskripsi)) ?>
    </div>
  </div>

  <?php if (!empty($materi->file_materi)): ?>
    <div style="background: linear-gradient(135deg, #F7FDFF 0%, #EEFFF8 100%); border: 1px solid #CFE9F4; border-radius: 14px; padding: 18px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
      <div style="display: flex; align-items: center; gap: 14px;">
        <div style="width: 44px; height: 44px; border-radius: 10px; background: var(--primary); color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
          <i class="fa-solid fa-file-arrow-down"></i>
        </div>
        <div>
          <div style="font-weight: 700; color: #1E293B; font-size: 0.92rem;">Lampiran Berkas Materi</div>
          <div style="font-size: 0.8rem; color: #64748B;"><?= html_escape($materi->file_materi) ?></div>
        </div>
      </div>

      <a href="<?= base_url('uploads/materi/' . $materi->file_materi) ?>" target="_blank" class="btn btn-primary btn-sm" download>
        <i class="fa-solid fa-download"></i> Unduh Berkas Materi
      </a>
    </div>
  <?php endif; ?>
</div>

