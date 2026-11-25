<!-- Back Button & Header -->
<div style="margin-bottom: 20px;">
  <a href="<?= site_url('siswa/kelas') ?>" class="btn btn-secondary btn-sm">
    <i class="fa-solid fa-arrow-left"></i> Kembali ke Kelas Saya
  </a>
</div>

<!-- Mapel Info Card -->
<div class="hero-panel" style="margin-bottom: 24px;">
  <div class="hero-content" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; flex-wrap: wrap; max-width: none;">
    <div style="max-width: 660px;">
      <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px; flex-wrap: wrap;">
        <span style="background: rgba(255, 255, 255, 0.22); border: 1px solid rgba(255, 255, 255, 0.3); color: #FFFFFF; padding: 4px 10px; border-radius: 8px; font-size: 0.8rem; font-weight: 700; font-family: monospace;">
          <?= html_escape($mapel->kode_mapel) ?>
        </span>
        <?php if (!empty($mapel->nama_kelas)): ?>
          <span style="background: rgba(255, 255, 255, 0.16); border: 1px solid rgba(255, 255, 255, 0.24); color: #FFFFFF; padding: 4px 10px; border-radius: 8px; font-size: 0.8rem; font-weight: 700;">
            <i class="fa-solid fa-people-group" style="margin-right: 4px;"></i>
            <?= html_escape($mapel->nama_kelas) ?>
          </span>
        <?php endif; ?>
      </div>
      <h2 style="font-size: 1.65rem; font-weight: 800; color: #FFFFFF; margin-bottom: 8px;">
        <?= html_escape($mapel->nama_mapel) ?>
      </h2>
      <p style="color: rgba(255, 255, 255, 0.86); font-size: 0.92rem; max-width: 650px; line-height: 1.55; margin-bottom: 16px;">
        <?= !empty($mapel->deskripsi) ? html_escape($mapel->deskripsi) : 'Tidak ada deskripsi untuk mapel ini.' ?>
      </p>

      <div style="display: flex; gap: 20px; font-size: 0.88rem; color: rgba(255, 255, 255, 0.9);">
        <div>
          <i class="fa-solid fa-chalkboard-user" style="color: #DFFFF4; margin-right: 6px;"></i>
          Guru Pengajar: <strong><?= html_escape($mapel->nama_guru ?: '-') ?></strong>
        </div>
      </div>
    </div>

    <div class="hero-status">
      <div class="label">Status Siswa</div>
      <div class="value">
        <i class="fa-solid fa-circle-check"></i> Terdaftar Aktif
      </div>
    </div>
  </div>
</div>

<!-- Tabs Navigation -->
<div style="display: flex; gap: 12px; border-bottom: 2px solid var(--card-border); margin-bottom: 24px;">
  <button id="tab-btn-materi" onclick="switchTab('materi')" class="btn" style="border-radius: 0; border: none; border-bottom: 3px solid var(--primary-dark); background: none; color: var(--primary-dark); font-weight: 700; padding: 12px 20px;">
    <i class="fa-solid fa-book-open" style="margin-right: 6px;"></i> Materi Pembelajaran (<?= count($materi_list) ?>)
  </button>
  <button id="tab-btn-tugas" onclick="switchTab('tugas')" class="btn" style="border-radius: 0; border: none; border-bottom: 3px solid transparent; background: none; color: var(--text-muted); font-weight: 600; padding: 12px 20px;">
    <i class="fa-solid fa-list-check" style="margin-right: 6px;"></i> Tugas Mapel (<?= count($tugas_list) ?>)
  </button>
</div>

<!-- TAB CONTENT 1: MATERI -->
<div id="tab-materi">
  <?php if (empty($materi_list)): ?>
    <div class="card">
      <div class="empty-state">
        <i class="fa-solid fa-book-bookmark"></i>
        <h4>Belum ada materi pembelajaran</h4>
        <p>Guru belum menambahkan materi untuk mapel ini.</p>
      </div>
    </div>
  <?php else: ?>
    <div style="display: flex; flex-direction: column; gap: 16px;">
      <?php foreach ($materi_list as $index => $m): ?>
        <div class="card" style="margin-bottom: 0;">
          <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 16px;">
            <div style="display: flex; gap: 16px; align-items: flex-start;">
              <div style="width: 40px; height: 40px; border-radius: 10px; background: #EFF6FF; color: var(--primary-dark); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem; flex-shrink: 0;">
                <?= $m->urutan ? $m->urutan : ($index + 1) ?>
              </div>
              <div>
                <h3 style="font-size: 1.1rem; font-weight: 700; color: #1E293B; margin-bottom: 6px;">
                  <a href="<?= site_url('siswa/materi/' . $m->id) ?>" style="color: inherit;">
                    <?= html_escape($m->judul) ?>
                  </a>
                </h3>
                <p style="font-size: 0.88rem; color: #64748B; line-height: 1.5; margin-bottom: 12px;">
                  <?= !empty($m->deskripsi) ? character_limiter(strip_tags($m->deskripsi), 180) : 'Tidak ada ringkasan materi.' ?>
                </p>

                <div style="display: flex; align-items: center; gap: 16px; font-size: 0.8rem; color: var(--text-muted);">
                  <span><i class="fa-regular fa-calendar"></i> Diposting: <?= date('d M Y', strtotime($m->created_at)) ?></span>
                </div>
              </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 8px; align-items: flex-end; flex-shrink: 0;">
              <a href="<?= site_url('siswa/materi/' . $m->id) ?>" class="btn btn-secondary btn-sm">
                <i class="fa-solid fa-eye"></i> Baca Materi
              </a>
              <?php if (!empty($m->file_materi)): ?>
                <a href="<?= base_url('uploads/materi/' . $m->file_materi) ?>" target="_blank" class="btn btn-primary btn-sm" download>
                  <i class="fa-solid fa-download"></i> Unduh Berkas
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<!-- TAB CONTENT 2: TUGAS -->
<div id="tab-tugas" style="display: none;">
  <?php if (empty($tugas_list)): ?>
    <div class="card">
      <div class="empty-state">
        <i class="fa-solid fa-clipboard-check"></i>
        <h4>Belum ada tugas untuk mapel ini</h4>
        <p>Guru belum mempublikasikan tugas untuk mapel ini.</p>
      </div>
    </div>
  <?php else: ?>
    <div class="table-container">
      <table class="table">
        <thead>
          <tr>
            <th style="width: 40px;">#</th>
            <th>Judul Tugas</th>
            <th>Jenis</th>
            <th>Deadline</th>
            <th>Status Pengumpulan</th>
            <th style="text-align: right;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($tugas_list as $i => $t): 
            $deadline_time = strtotime($t->deadline);
            $is_passed = ($deadline_time < time());

            if ($t->pengumpulan_id) {
              if ($t->status_pengumpulan === 'dinilai') {
                $status_badge = '<span class="badge badge-graded"><i class="fa-solid fa-check-double"></i> Dinilai</span>';
              } elseif ($t->status_pengumpulan === 'revisi') {
                $status_badge = '<span class="badge" style="background: #FFF7ED; color: #C2410C;"><i class="fa-solid fa-rotate"></i> Perlu Revisi</span>';
              } elseif ($t->status_pengumpulan === 'terlambat') {
                $status_badge = '<span class="badge badge-overdue"><i class="fa-solid fa-triangle-exclamation"></i> Terlambat</span>';
              } else {
                $status_badge = '<span class="badge badge-pending"><i class="fa-solid fa-clock"></i> Menunggu Dinilai</span>';
              }
            } else {
              if ($is_passed) {
                $status_badge = '<span class="badge badge-overdue"><i class="fa-solid fa-ban"></i> Terlewat</span>';
              } else {
                $status_badge = '<span class="badge badge-pending"><i class="fa-regular fa-clock"></i> Belum Kumpul</span>';
              }
            }
          ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td>
                <div style="font-weight: 700; color: #1E293B;">
                  <a href="<?= site_url('siswa/detail_tugas/' . $t->id) ?>" style="color: inherit;">
                    <?= html_escape($t->judul) ?>
                  </a>
                </div>
              </td>
              <td>
                <span class="badge <?= $t->jenis === 'kelompok' ? 'badge-kelompok' : 'badge-individu' ?>">
                  <?= ucfirst($t->jenis) ?>
                </span>
              </td>
              <td>
                <div style="font-size: 0.85rem; color: <?= (!$t->pengumpulan_id && $is_passed) ? '#BE123C' : '#334155' ?>; font-weight: 500;">
                  <?= date('d M Y, H:i', $deadline_time) ?>
                </div>
              </td>
              <td><?= $status_badge ?></td>
              <td style="text-align: right;">
                <?php if ($t->pengumpulan_id): ?>
                  <a href="<?= site_url('siswa/detail_tugas/' . $t->id) ?>" class="btn btn-secondary btn-sm">
                    <i class="fa-solid fa-eye"></i> Detail
                  </a>
                <?php else: ?>
                  <a href="<?= site_url('siswa/detail_tugas/' . $t->id) ?>" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-upload"></i> Kumpulkan
                  </a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<script>
function switchTab(tab) {
  const materiTab = document.getElementById('tab-materi');
  const tugasTab = document.getElementById('tab-tugas');
  const btnMateri = document.getElementById('tab-btn-materi');
  const btnTugas = document.getElementById('tab-btn-tugas');

  if (tab === 'materi') {
    materiTab.style.display = 'block';
    tugasTab.style.display = 'none';
    btnMateri.style.borderBottomColor = 'var(--primary-dark)';
    btnMateri.style.color = 'var(--primary-dark)';
    btnMateri.style.fontWeight = '700';

    btnTugas.style.borderBottomColor = 'transparent';
    btnTugas.style.color = 'var(--text-muted)';
    btnTugas.style.fontWeight = '600';
  } else {
    materiTab.style.display = 'none';
    tugasTab.style.display = 'block';
    btnTugas.style.borderBottomColor = 'var(--primary-dark)';
    btnTugas.style.color = 'var(--primary-dark)';
    btnTugas.style.fontWeight = '700';

    btnMateri.style.borderBottomColor = 'transparent';
    btnMateri.style.color = 'var(--text-muted)';
    btnMateri.style.fontWeight = '600';
  }
}
</script>
