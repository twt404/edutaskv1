<!-- Greeting Header -->
<div class="hero-panel">
  <div class="hero-illustration" aria-hidden="true">
    <svg viewBox="0 0 280 210" fill="none">
      <circle cx="214" cy="44" r="36" fill="rgba(255,255,255,0.18)"/>
      <circle cx="48" cy="170" r="24" fill="rgba(110,231,183,0.30)"/>
      <rect x="50" y="62" width="174" height="112" rx="24" fill="#FFFFFF"/>
      <rect x="68" y="80" width="138" height="76" rx="15" fill="#F0FBFF"/>
      <path d="M86 100h38M86 114h64M86 128h48" stroke="#BAE6FD" stroke-width="6" stroke-linecap="round"/>
      <rect x="86" y="140" width="26" height="8" rx="4" fill="#6EE7B7"/>
      <g filter="url(#shadow)">
        <path d="M28 50L64 34l36 16-36 16-36-16z" fill="#6EE7B7"/>
        <path d="M42 60v16c0 6 10 10 22 10s22-4 22-10V60" stroke="#DFFFF4" stroke-width="4" stroke-linecap="round"/>
        <path d="M96 50v22" stroke="#DFFFF4" stroke-width="4" stroke-linecap="round"/>
        <circle cx="96" cy="77" r="4" fill="#FBBF24"/>
      </g>
      <g filter="url(#shadow)">
        <rect x="196" y="118" width="52" height="58" rx="13" fill="#0F2A43" transform="rotate(8 196 118)"/>
        <path d="M210 136h20M210 146h20M210 156h12" stroke="#6EE7B7" stroke-width="4" stroke-linecap="round" transform="rotate(8 196 118)"/>
      </g>
      <g filter="url(#shadow)">
        <rect x="194" y="22" width="24" height="62" rx="8" transform="rotate(32 194 22)" fill="#FBBF24"/>
        <path d="M215 23l14 9-8 12-14-9z" fill="#FEF3C7"/>
        <path d="M218 15l10 7" stroke="#FFFFFF" stroke-width="4" stroke-linecap="round"/>
      </g>
      <circle cx="170" cy="40" r="4" fill="#FFFFFF" opacity="0.8"/>
      <circle cx="246" cy="98" r="3" fill="#DFFFF4" opacity="0.9"/>
      <circle cx="118" cy="186" r="4" fill="#FFFFFF" opacity="0.7"/>
      <defs>
        <filter id="shadow" x="-30" y="-30" width="140" height="140" filterUnits="userSpaceOnUse">
          <feDropShadow dx="0" dy="10" stdDeviation="10" flood-color="#064E6C" flood-opacity="0.18"/>
        </filter>
      </defs>
    </svg>
  </div>

  <div class="hero-content">
    <div class="hero-eyebrow">
      <i class="fa-solid fa-graduation-cap"></i>
      Ruang Belajar Siswa
    </div>
    <h2>Halo, <?= html_escape($this->session->userdata('nama')) ?>!</h2>
    <p>Selamat datang kembali di EduTask. Siap untuk belajar dan menyelesaikan tugas hari ini?</p>
    <?php if (!empty($kelas)): ?>
      <span class="hero-chip">
        <i class="fa-solid fa-people-group"></i>
        <?= html_escape($kelas->nama_kelas) ?><?= !empty($kelas->jurusan) ? ' · ' . html_escape($kelas->jurusan) : '' ?>
        <span style="opacity: 0.72;">(<?= html_escape($kelas->tingkat) ?>)</span>
      </span>
    <?php else: ?>
      <a href="<?= site_url('siswa/gabung_kelas') ?>" class="hero-chip warning" style="text-decoration: none;">
        <i class="fa-solid fa-circle-exclamation"></i>
        Belum tergabung di kelas — Gabung sekarang
      </a>
    <?php endif; ?>
  </div>
</div>

<!-- Quick Stats Row -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 32px;">
  <div class="stat-card">
    <div class="stat-icon blue">
      <i class="fa-solid fa-book-open"></i>
    </div>
    <div>
      <div class="stat-value"><?= (int) $stats['mapel_count'] ?></div>
      <div class="stat-label">Mapel Diikuti</div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon amber">
      <i class="fa-solid fa-clock-rotate-left"></i>
    </div>
    <div>
      <div class="stat-value"><?= (int) $stats['tugas_aktif'] ?></div>
      <div class="stat-label">Tugas Aktif</div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon green">
      <i class="fa-solid fa-circle-check"></i>
    </div>
    <div>
      <div class="stat-value"><?= (int) $stats['tugas_selesai'] ?></div>
      <div class="stat-label">Tugas Selesai</div>
    </div>
  </div>
</div>

<div class="dashboard-grid">
  <!-- Left Column: Tugas Mendatang -->
  <div>
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fa-solid fa-calendar-day" style="color: var(--primary-dark); margin-right: 8px;"></i>
          Tugas Mendatang
        </h3>
        <a href="<?= site_url('siswa/tugas') ?>" style="font-size: 0.85rem; color: var(--primary-dark); font-weight: 700;">
          Lihat Semua <i class="fa-solid fa-arrow-right" style="font-size: 0.75rem;"></i>
        </a>
      </div>

      <?php if (empty($upcoming_tugas)): ?>
        <div class="empty-state">
          <i class="fa-solid fa-clipboard-check"></i>
          <h4>Tidak ada tugas aktif</h4>
          <p>Semua tugas Anda telah selesai dikerjakan atau belum ada tugas yang diberikan.</p>
        </div>
      <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 14px;">
          <?php foreach ($upcoming_tugas as $tugas): 
            $deadline_time = strtotime($tugas->deadline);
            $now = time();
            $diff_days = ceil(($deadline_time - $now) / 86400);

            if ($tugas->pengumpulan_id) {
              if ($tugas->status_pengumpulan === 'dinilai') {
                $status_badge = '<span class="badge badge-graded"><i class="fa-solid fa-check-double"></i> Dinilai</span>';
              } elseif ($tugas->status_pengumpulan === 'terlambat') {
                $status_badge = '<span class="badge badge-overdue"><i class="fa-solid fa-triangle-exclamation"></i> Terlambat</span>';
              } else {
                $status_badge = '<span class="badge badge-pending"><i class="fa-solid fa-clock"></i> Menunggu Dinilai</span>';
              }
            } else {
              if ($diff_days < 0) {
                $status_badge = '<span class="badge badge-overdue"><i class="fa-solid fa-circle-xmark"></i> Terlewat</span>';
              } else {
                $status_badge = '<span class="badge badge-pending"><i class="fa-regular fa-clock"></i> Belum Kumpul</span>';
              }
            }
          ?>
            <div class="task-row" style="padding: 16px; display: flex; align-items: center; justify-content: space-between; gap: 16px;">
              <div style="flex: 1; min-width: 0;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 7px; flex-wrap: wrap;">
                  <span class="badge <?= $tugas->jenis === 'kelompok' ? 'badge-kelompok' : 'badge-individu' ?>">
                    <i class="fa-solid <?= $tugas->jenis === 'kelompok' ? 'fa-people-group' : 'fa-user' ?>"></i>
                    <?= ucfirst($tugas->jenis) ?>
                  </span>
                  <?= $status_badge ?>
                  <span style="font-size: 0.8rem; color: var(--text-muted);">
                    <i class="fa-solid fa-chalkboard-user"></i> <?= html_escape($tugas->nama_mapel) ?>
                  </span>
                </div>
                <h4 style="font-size: 0.98rem; font-weight: 700; color: var(--text-main); margin-bottom: 5px;">
                  <a href="<?= site_url('siswa/detail_tugas/' . $tugas->id) ?>" style="color: inherit;">
                    <?= html_escape($tugas->judul) ?>
                  </a>
                </h4>
                <div style="font-size: 0.82rem; color: var(--text-muted);">
                  <i class="fa-regular fa-clock" style="margin-right: 4px;"></i>
                  Deadline: <?= date('d M Y, H:i', $deadline_time) ?>
                  <?php if (!$tugas->pengumpulan_id && $diff_days >= 0): ?>
                    <strong style="color: <?= $diff_days <= 1 ? '#BE123C' : '#047857' ?>; margin-left: 6px;">
                      (<?= $diff_days == 0 ? 'Hari ini' : $diff_days . ' hari lagi' ?>)
                    </strong>
                  <?php endif; ?>
                </div>
              </div>

              <div>
                <?php if ($tugas->pengumpulan_id): ?>
                  <a href="<?= site_url('siswa/detail_tugas/' . $tugas->id) ?>" class="btn btn-secondary btn-sm">
                    <i class="fa-solid fa-eye"></i> Detail
                  </a>
                <?php else: ?>
                  <a href="<?= site_url('siswa/detail_tugas/' . $tugas->id) ?>" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-upload"></i> Kumpulkan
                  </a>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Right Column: Materi Terbaru & Kelas Saya Preview -->
  <div style="display: flex; flex-direction: column; gap: 24px;">
    <!-- Materi Terbaru -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fa-solid fa-book-bookmark" style="color: var(--mint-deep); margin-right: 8px;"></i>
          Materi Terbaru
        </h3>
      </div>

      <?php if (empty($materi_terbaru)): ?>
        <p style="color: var(--text-muted); font-size: 0.88rem; text-align: center; padding: 18px 0;">
          Belum ada materi pembelajaran yang diunggah guru.
        </p>
      <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 12px;">
          <?php foreach ($materi_terbaru as $m): ?>
            <div class="soft-panel" style="padding: 13px;">
              <span style="font-size: 0.75rem; color: var(--primary-dark); font-weight: 700;">
                <?= html_escape($m->nama_mapel) ?>
              </span>
              <h5 style="font-size: 0.92rem; font-weight: 700; color: var(--text-main); margin: 4px 0;">
                <a href="<?= site_url('siswa/materi/' . $m->id) ?>" style="color: inherit;">
                  <?= html_escape($m->judul) ?>
                </a>
              </h5>
              <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 8px;">
                <span style="font-size: 0.75rem; color: #7D97AA;">
                  <i class="fa-regular fa-calendar"></i> <?= date('d M Y', strtotime($m->created_at)) ?>
                </span>
                <a href="<?= site_url('siswa/materi/' . $m->id) ?>" class="btn btn-secondary btn-sm" style="padding: 3px 8px; font-size: 0.75rem;">
                  Baca <i class="fa-solid fa-arrow-right" style="font-size: 0.7rem;"></i>
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Mapel Diikuti Preview -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fa-solid fa-chalkboard" style="color: var(--primary-dark); margin-right: 8px;"></i>
          Mapel Saya
        </h3>
        <a href="<?= site_url('siswa/kelas') ?>" style="font-size: 0.82rem; color: var(--primary-dark); font-weight: 700;">
          Semua
        </a>
      </div>

      <?php if (empty($mapel_list)): ?>
        <p style="color: var(--text-muted); font-size: 0.88rem; text-align: center; padding: 18px 0;">
          Anda belum terdaftar dalam mapel manapun.
        </p>
      <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 10px;">
          <?php foreach (array_slice($mapel_list, 0, 3) as $m): ?>
            <a href="<?= site_url('siswa/detail_mapel/' . $m->id) ?>" class="mapel-mini">
              <div style="font-weight: 700; color: var(--text-main); font-size: 0.92rem;"><?= html_escape($m->nama_mapel) ?></div>
              <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 3px;">
                <i class="fa-solid fa-chalkboard-user"></i> <?= html_escape($m->nama_guru) ?>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
