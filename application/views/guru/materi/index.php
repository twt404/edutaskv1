<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
  <div>
    <h2 style="font-size: 1.4rem; font-weight: 700; color: var(--text-main);">Semua Materi</h2>
    <p style="color: var(--text-muted); font-size: 0.9rem;">Daftar materi pembelajaran yang telah dibagikan di semua mata pelajaran.</p>
  </div>
</div>

<div class="card">
  <?php if (!empty($materi_list)): ?>
    <div class="table-container">
      <table class="table">
        <thead>
          <tr>
            <th style="width: 70px;">Urutan</th>
            <th>Judul Materi</th>
            <th>Mapel</th>
            <th>Berkas Lampiran</th>
            <th>Dibuat</th>
            <th style="text-align: right;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($materi_list as $m): ?>
            <tr>
              <td>
                <span style="font-weight: 700; color: var(--primary);">#<?= (int) $m->urutan ?></span>
              </td>
              <td>
                <div style="font-weight: 600; color: var(--text-main);"><?= html_escape($m->judul) ?></div>
                <?php if (!empty($m->deskripsi)): ?>
                  <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">
                    <?= html_escape(character_limiter($m->deskripsi, 60)) ?>
                  </div>
                <?php endif; ?>
              </td>
              <td>
                <span class="badge" style="background: #F1F5F9; color: #334155;">
                  <?= html_escape($m->nama_mapel) ?><?= !empty($m->nama_kelas) ? ' · ' . html_escape($m->nama_kelas) : '' ?>
                </span>
              </td>
              <td>
                <?php if (!empty($m->file_materi)): ?>
                  <a href="<?= base_url('uploads/materi/' . $m->file_materi) ?>" target="_blank" class="btn btn-secondary btn-sm" style="padding: 4px 8px; font-size: 0.78rem;">
                    <i class="fa-solid fa-file-arrow-down"></i>
                    <span>Unduh</span>
                  </a>
                <?php else: ?>
                  <span style="color: var(--text-muted); font-size: 0.82rem;">Tidak ada berkas</span>
                <?php endif; ?>
              </td>
              <td>
                <span style="font-size: 0.85rem; color: var(--text-muted);">
                  <?= date('d M Y', strtotime($m->created_at)) ?>
                </span>
              </td>
              <td style="text-align: right;">
                <div style="display: inline-flex; gap: 6px;">
                  <a href="<?= site_url('guru/edit_materi/' . $m->id) ?>" class="btn btn-secondary btn-sm" title="Edit">
                    <i class="fa-solid fa-pen"></i>
                  </a>
                  <a href="<?= site_url('guru/hapus_materi/' . $m->id) ?>" onclick="return confirm('Hapus materi ini?');" class="btn btn-danger btn-sm" title="Hapus">
                    <i class="fa-solid fa-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="empty-state">
      <i class="fa-solid fa-book"></i>
      <h4>Belum Ada Materi</h4>
      <p>Buka detail mapel untuk menambahkan materi pembelajaran bagi siswa.</p>
      <a href="<?= site_url('guru/mapel') ?>" class="btn btn-primary">
        <span>Buka Mapel Saya</span>
      </a>
    </div>
  <?php endif; ?>
</div>
