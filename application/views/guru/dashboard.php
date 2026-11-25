<style>
  .dash-head {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 24px;
    margin-bottom: 24px;
    flex-wrap: wrap;
  }
  .dash-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 5px 12px;
    border-radius: 999px;
    background: var(--primary-light);
    color: var(--primary-dark);
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    margin-bottom: 10px;
  }
  .dash-eyebrow span {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--primary-dark);
  }
  .dash-head h2 {
    font-size: 1.9rem;
    line-height: 1.15;
    font-weight: 800;
    letter-spacing: -0.025em;
    color: var(--text-main);
    margin-bottom: 7px;
  }
  .dash-head p {
    color: var(--text-muted);
    font-size: 0.92rem;
    max-width: 680px;
    line-height: 1.6;
  }
  .dash-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }
  .metric-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 24px;
  }
  .metric-card {
    position: relative;
    overflow: hidden;
    background: #FFFFFF;
    border: 1px solid var(--card-border);
    border-radius: 18px;
    padding: 20px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
  }
  .metric-card::after {
    content: '';
    position: absolute;
    width: 90px;
    height: 90px;
    right: -26px;
    top: -26px;
    border-radius: 50%;
    background: #EFF4FF;
  }
  .metric-card.green::after { background: #E7F8F1; }
  .metric-card.amber::after { background: #FFF6E5; }
  .metric-card.teal::after { background: #E6FBFA; }
  .metric-top {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 16px;
  }
  .metric-label {
    font-size: 0.72rem;
    font-weight: 600;
    color: var(--text-muted);
  }
  .metric-icon {
    width: 38px;
    height: 38px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #EFF4FF;
    color: var(--primary-dark);
    font-size: 0.95rem;
  }
  .metric-card.green .metric-icon { background: #E7F8F1; color: #059669; }
  .metric-card.amber .metric-icon { background: #FFF6E5; color: #D97706; }
  .metric-card.teal .metric-icon { background: #E6FBFA; color: #0D9488; }
  .metric-value {
    position: relative;
    z-index: 1;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 2rem;
    font-weight: 800;
    line-height: 1;
    color: var(--text-main);
    margin-bottom: 5px;
  }
  .metric-sub {
    position: relative;
    z-index: 1;
    font-size: 0.74rem;
    color: var(--text-muted);
  }
  .metric-foot {
    position: relative;
    z-index: 1;
    margin-top: 14px;
    padding-top: 12px;
    border-top: 1px solid #F1F5F9;
    font-size: 0.72rem;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .metric-foot a { color: var(--primary-dark); font-weight: 700; margin-left: auto; }
  .dash-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.65fr) minmax(0, 1fr);
    gap: 20px;
    margin-bottom: 24px;
  }
  .panel {
    background: #FFFFFF;
    border: 1px solid var(--card-border);
    border-radius: 18px;
    padding: 22px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
  }
  .panel-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    margin-bottom: 18px;
  }
  .panel-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-main);
  }
  .panel-title .ic {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--primary-light);
    color: var(--primary-dark);
    font-size: 0.85rem;
  }
  .panel-link {
    color: var(--primary-dark);
    font-size: 0.76rem;
    font-weight: 700;
    white-space: nowrap;
  }
  .materi-list { display: flex; flex-direction: column; gap: 10px; }
  .materi-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    border: 1px solid #F1F5F9;
    border-radius: 12px;
    transition: background 0.2s, border-color 0.2s;
  }
  .materi-item:hover { background: #F8F9FF; border-color: #E2E8F0; }
  .materi-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #EFF4FF;
    color: var(--primary-dark);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .materi-info { min-width: 0; flex: 1; }
  .materi-title {
    font-size: 0.84rem;
    font-weight: 700;
    color: var(--text-main);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 3px;
  }
  .materi-meta {
    font-size: 0.7rem;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
  }
  .materi-open {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    background: #F8F9FF;
    flex-shrink: 0;
  }
  .materi-open:hover { background: var(--primary-light); color: var(--primary-dark); }
  .task-list { display: flex; flex-direction: column; gap: 12px; }
  .task-item {
    border: 1px solid #F1F5F9;
    border-radius: 12px;
    padding: 14px;
    background: #F8F9FF;
  }
  .task-item-top {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    flex-wrap: wrap;
  }
  .task-title {
    font-size: 0.84rem;
    font-weight: 700;
    color: var(--text-main);
    line-height: 1.35;
    margin-bottom: 6px;
  }
  .task-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    font-size: 0.7rem;
    color: var(--text-muted);
    flex-wrap: wrap;
  }
  .task-meta strong { color: var(--text-main); }
  .progress {
    height: 7px;
    border-radius: 999px;
    background: #E2E8F0;
    overflow: hidden;
    margin-top: 10px;
  }
  .progress span {
    display: block;
    height: 100%;
    border-radius: inherit;
    background: var(--primary-dark);
  }
  .table-card { background: #FFFFFF; border: 1px solid var(--card-border); border-radius: 18px; padding: 22px; }
  .table-card .panel-head { margin-bottom: 16px; }
  @media (max-width: 1100px) {
    .metric-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
  }
  @media (max-width: 900px) {
    .dash-grid { grid-template-columns: 1fr; }
  }
  @media (max-width: 620px) {
    .metric-grid { grid-template-columns: 1fr; }
    .dash-head h2 { font-size: 1.55rem; }
  }
</style>

<div class="dash-head">
  <div>
    <div class="dash-eyebrow"><span></span> Semester Genap • Aktif Mengajar</div>
    <h2>Selamat datang, <?= html_escape($this->session->userdata('nama')) ?>! 👋</h2>
    <p>Kelola materi, pantau pengumpulan tugas, dan beri penilaian untuk seluruh mapel yang Anda ampu.</p>
  </div>
  <div class="dash-actions">
    <a href="<?= site_url('guru/mapel') ?>" class="btn btn-primary">
      <i class="fa-solid fa-cloud-arrow-up"></i>
      <span>Unggah Materi Baru</span>
    </a>
    <a href="<?= site_url('guru/tugas') ?>" class="btn btn-secondary">
      <i class="fa-solid fa-chart-column"></i>
      <span>Lihat Rekap Tugas</span>
    </a>
  </div>
</div>

<div class="metric-grid">
  <div class="metric-card">
    <div class="metric-top">
      <span class="metric-label">Total Materi Aktif</span>
      <div class="metric-icon"><i class="fa-solid fa-book-open"></i></div>
    </div>
    <div class="metric-value"><?= (int) $total_materi ?></div>
    <div class="metric-sub">Dokumen &amp; video pembelajaran</div>
    <div class="metric-foot">
      <i class="fa-solid fa-arrow-trend-up"></i>
      <span><?= (int) $total_mapel ?> mapel terhubung</span>
    </div>
  </div>

  <div class="metric-card green">
    <div class="metric-top">
      <span class="metric-label">Tugas Sedang Berjalan</span>
      <div class="metric-icon"><i class="fa-regular fa-square-check"></i></div>
    </div>
    <div class="metric-value"><?= count($tugas_aktif) ?></div>
    <div class="metric-sub">Tugas dalam periode aktif</div>
    <div class="metric-foot">
      <span class="badge badge-individu">Individu</span>
      <span class="badge badge-kelompok">Kelompok</span>
    </div>
  </div>

  <div class="metric-card amber">
    <div class="metric-top">
      <span class="metric-label">Perlu Dinilai</span>
      <div class="metric-icon"><i class="fa-solid fa-pen-to-square"></i></div>
    </div>
    <div class="metric-value"><?= (int) $menunggu_penilaian ?></div>
    <div class="metric-sub">Pengumpulan belum diperiksa</div>
    <div class="metric-foot">
      <span>Total <?= (int) $total_pengumpulan ?> terkumpul</span>
      <a href="<?= site_url('guru/tugas') ?>">Nilai →</a>
    </div>
  </div>

  <div class="metric-card teal">
    <div class="metric-top">
      <span class="metric-label">Tingkat Pengumpulan</span>
      <div class="metric-icon"><i class="fa-solid fa-circle-check"></i></div>
    </div>
    <div class="metric-value"><?= html_escape($tingkat_pengumpulan) ?>%</div>
    <div class="metric-sub">Konsistensi seluruh kelas</div>
    <div class="metric-foot">
      <i class="fa-solid fa-users"></i>
      <span><?= (int) $total_siswa ?> siswa terdaftar</span>
    </div>
  </div>
</div>

<div class="dash-grid">
  <div class="panel">
    <div class="panel-head">
      <div class="panel-title"><span class="ic"><i class="fa-solid fa-book-open"></i></span> Materi Terbaru</div>
      <a href="<?= site_url('guru/materi') ?>" class="panel-link">Lihat Semua →</a>
    </div>

    <?php if (!empty($materi_terbaru)): ?>
      <div class="materi-list">
        <?php foreach ($materi_terbaru as $m): ?>
          <div class="materi-item">
            <div class="materi-icon"><i class="fa-solid fa-file-lines"></i></div>
            <div class="materi-info">
              <div class="materi-title"><?= html_escape($m->judul) ?></div>
              <div class="materi-meta">
                <span><?= html_escape($m->nama_mapel) ?><?= !empty($m->nama_kelas) ? ' · ' . html_escape($m->nama_kelas) : '' ?></span>
                <span>•</span>
                <span><?= date('d M Y', strtotime($m->created_at)) ?></span>
              </div>
            </div>
            <?php if (!empty($m->file_materi)): ?>
              <a href="<?= base_url('uploads/materi/' . $m->file_materi) ?>" class="materi-open" target="_blank" title="Buka materi">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
              </a>
            <?php else: ?>
              <span class="materi-open"><i class="fa-regular fa-file"></i></span>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-state" style="padding: 30px 16px;">
        <i class="fa-solid fa-book-open"></i>
        <h4>Belum Ada Materi</h4>
        <p>Unggah materi pertama Anda melalui halaman kelas.</p>
      </div>
    <?php endif; ?>
  </div>

  <div class="panel">
    <div class="panel-head">
      <div class="panel-title"><span class="ic"><i class="fa-regular fa-clock"></i></span> Tugas &amp; Deadline</div>
      <a href="<?= site_url('guru/tugas') ?>" class="panel-link">Semua →</a>
    </div>

    <?php if (!empty($tugas_aktif)): ?>
      <div class="task-list">
        <?php foreach ($tugas_aktif as $t): ?>
          <?php
            $total_target = max(1, (int) $total_siswa);
            $progress = min(100, round(((int) $t->jumlah_pengumpulan / $total_target) * 100));
          ?>
          <div class="task-item">
            <div class="task-item-top">
              <?php if ($t->jenis === 'kelompok'): ?>
                <span class="badge badge-kelompok">Kelompok</span>
              <?php else: ?>
                <span class="badge badge-individu">Individu</span>
              <?php endif; ?>
              <span class="badge badge-pending"><i class="fa-regular fa-clock"></i> <?= date('d M, H:i', strtotime($t->deadline)) ?></span>
            </div>
            <div class="task-title"><?= html_escape($t->judul) ?></div>
            <div class="task-meta">
              <span><?= html_escape($t->nama_mapel) ?><?= !empty($t->nama_kelas) ? ' · ' . html_escape($t->nama_kelas) : '' ?></span>
              <span><strong><?= (int) $t->jumlah_pengumpulan ?></strong> terkumpul</span>
            </div>
            <div class="progress"><span style="width: <?= $progress ?>%"></span></div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-state" style="padding: 30px 16px;">
        <i class="fa-regular fa-calendar-check"></i>
        <h4>Tidak Ada Deadline</h4>
        <p>Belum ada tugas aktif dalam waktu dekat.</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<div class="table-card">
  <div class="panel-head">
    <div class="panel-title"><span class="ic"><i class="fa-solid fa-arrow-down-to-bracket"></i></span> Pengumpulan Tugas Terbaru</div>
    <a href="<?= site_url('guru/tugas') ?>" class="btn btn-secondary btn-sm">
      <span>Lihat Semua Tugas</span>
      <i class="fa-solid fa-arrow-right"></i>
    </a>
  </div>

  <?php if (!empty($recent_pengumpulan)): ?>
    <div class="table-container" style="border: 0;">
      <table class="table">
        <thead>
          <tr>
            <th>Siswa</th>
            <th>Tugas</th>
            <th>Mapel</th>
            <th>Waktu Submit</th>
            <th>Status</th>
            <th style="text-align: right;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($recent_pengumpulan as $item): ?>
            <tr>
              <td>
                <div style="font-weight: 600; color: var(--text-main);"><?= html_escape($item->nama_siswa) ?></div>
              </td>
              <td><?= html_escape($item->judul_tugas) ?></td>
              <td>
                <span style="font-size: 0.85rem; color: var(--text-muted);"><?= html_escape($item->nama_mapel) ?><?= !empty($item->nama_kelas) ? ' · ' . html_escape($item->nama_kelas) : '' ?></span>
              </td>
              <td><?= date('d M Y, H:i', strtotime($item->submitted_at)) ?></td>
              <td>
                <?php if ($item->status === 'menunggu'): ?>
                  <span class="badge badge-pending">Menunggu Evaluasi</span>
                <?php elseif ($item->status === 'terlambat'): ?>
                  <span class="badge badge-overdue">Terlambat</span>
                <?php else: ?>
                  <span class="badge badge-graded">Dinilai</span>
                <?php endif; ?>
              </td>
              <td style="text-align: right;">
                <a href="<?= site_url('guru/detail_tugas/' . $item->tugas_id) ?>" class="btn btn-primary btn-sm">
                  <i class="fa-solid fa-pen-to-square"></i>
                  <span>Nilai</span>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="empty-state">
      <i class="fa-solid fa-clipboard-check"></i>
      <h4>Semua Bersih!</h4>
      <p>Tidak ada pengumpulan tugas baru yang menunggu penilaian saat ini.</p>
    </div>
  <?php endif; ?>
</div>
