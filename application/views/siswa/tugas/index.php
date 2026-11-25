<!-- Header & Filter Tabs -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
  <div>
    <h2 style="font-size: 1.4rem; font-weight: 800; color: #0F172A;">Semua Tugas</h2>
    <p style="color: var(--text-muted); font-size: 0.9rem;">Kelola dan pantau seluruh status pengumpulan tugas Anda</p>
  </div>
</div>

<!-- Tabs -->
<div style="display: flex; gap: 8px; border-bottom: 1px solid var(--card-border); margin-bottom: 24px; overflow-x: auto; padding-bottom: 4px;">
  <a href="<?= site_url('siswa/tugas?filter=semua') ?>" 
     class="btn <?= ($filter === 'semua') ? 'btn-primary' : 'btn-secondary' ?> btn-sm" style="border-radius: 20px;">
    Semua
  </a>
  <a href="<?= site_url('siswa/tugas?filter=belum_dikumpulkan') ?>" 
     class="btn <?= ($filter === 'belum_dikumpulkan') ? 'btn-primary' : 'btn-secondary' ?> btn-sm" style="border-radius: 20px;">
    Belum Dikumpulkan
  </a>
  <a href="<?= site_url('siswa/tugas?filter=menunggu') ?>" 
     class="btn <?= ($filter === 'menunggu') ? 'btn-primary' : 'btn-secondary' ?> btn-sm" style="border-radius: 20px;">
    Menunggu Penilaian
  </a>
  <a href="<?= site_url('siswa/tugas?filter=revisi') ?>" 
     class="btn <?= ($filter === 'revisi') ? 'btn-primary' : 'btn-secondary' ?> btn-sm" style="border-radius: 20px;">
    Perlu Revisi
  </a>
  <a href="<?= site_url('siswa/tugas?filter=dinilai') ?>" 
     class="btn <?= ($filter === 'dinilai') ? 'btn-primary' : 'btn-secondary' ?> btn-sm" style="border-radius: 20px;">
    Sudah Dinilai
  </a>
  <a href="<?= site_url('siswa/tugas?filter=terlambat') ?>" 
     class="btn <?= ($filter === 'terlambat') ? 'btn-primary' : 'btn-secondary' ?> btn-sm" style="border-radius: 20px;">
    Terlambat
  </a>
</div>

<!-- Tasks Cards / Grid -->
<?php if (empty($tugas_list)): ?>
  <div class="card">
    <div class="empty-state">
      <i class="fa-solid fa-list-check"></i>
      <h4>Tidak ada tugas ditemukan</h4>
      <p>Tidak ada data tugas yang sesuai dengan kriteria filter yang Anda pilih.</p>
    </div>
  </div>
<?php else: ?>
  <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 20px;">
    <?php foreach ($tugas_list as $t): 
      $deadline_time = strtotime($t->deadline);
      $now = time();
      $is_passed = ($deadline_time < $now);

      // Determine status pill
      if ($t->pengumpulan_id) {
        if ($t->status_pengumpulan === 'dinilai') {
          $status_pill = '<span class="badge badge-graded"><i class="fa-solid fa-check-double"></i> Dinilai</span>';
        } elseif ($t->status_pengumpulan === 'revisi') {
          $status_pill = '<span class="badge" style="background: #FFF7ED; color: #C2410C;"><i class="fa-solid fa-rotate"></i> Perlu Revisi</span>';
        } elseif ($t->status_pengumpulan === 'terlambat') {
          $status_pill = '<span class="badge badge-overdue"><i class="fa-solid fa-triangle-exclamation"></i> Terlambat Kumpul</span>';
        } else {
          $status_pill = '<span class="badge badge-pending"><i class="fa-solid fa-clock"></i> Menunggu Dinilai</span>';
        }
      } else {
        if ($is_passed) {
          $status_pill = '<span class="badge badge-overdue"><i class="fa-solid fa-ban"></i> Terlewat Deadline</span>';
        } else {
          $status_pill = '<span class="badge badge-pending"><i class="fa-regular fa-clock"></i> Belum Kumpul</span>';
        }
      }
    ?>
      <div class="card" style="margin-bottom: 0; display: flex; flex-direction: column; justify-content: space-between; border-radius: 14px;">
        <div>
          <!-- Badges header -->
          <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px; margin-bottom: 12px;">
            <span class="badge <?= $t->jenis === 'kelompok' ? 'badge-kelompok' : 'badge-individu' ?>">
              <i class="fa-solid <?= $t->jenis === 'kelompok' ? 'fa-people-group' : 'fa-user' ?>"></i>
              <?= ucfirst($t->jenis) ?>
            </span>
            <?= $status_pill ?>
          </div>

          <!-- Title & Class -->
          <h3 style="font-size: 1.05rem; font-weight: 700; color: #1E293B; margin-bottom: 6px; line-height: 1.35;">
            <a href="<?= site_url('siswa/detail_tugas/' . $t->id) ?>" style="color: inherit;">
              <?= html_escape($t->judul) ?>
            </a>
          </h3>
          <p style="font-size: 0.84rem; color: var(--text-muted); margin-bottom: 16px; display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
            <i class="fa-solid fa-book-open"></i>
            <?= html_escape($t->nama_mapel) ?>
            <?php if ($t->jenis === 'kelompok' && !empty($t->nama_kelompok)): ?>
              <span class="badge badge-kelompok" style="font-size: 0.7rem; padding: 2px 8px;">
                <i class="fa-solid fa-people-group"></i> <?= html_escape($t->nama_kelompok) ?>
              </span>
            <?php endif; ?>
          </p>

          <!-- Deadline -->
          <div style="font-size: 0.83rem; background: #F8FAFC; padding: 10px 12px; border-radius: 8px; border: 1px solid var(--card-border); margin-bottom: 16px;">
            <div style="color: #64748B; margin-bottom: 2px;">Deadline Pengumpulan:</div>
            <div style="font-weight: 600; color: <?= (!$t->pengumpulan_id && $is_passed) ? '#BE123C' : '#1E293B' ?>;">
              <i class="fa-regular fa-calendar-check" style="margin-right: 4px;"></i>
              <?= date('d M Y, H:i', $deadline_time) ?> WIB
            </div>
          </div>
        </div>

        <!-- Action Footer -->
        <div style="display: flex; align-items: center; justify-content: flex-end; padding-top: 14px; border-top: 1px solid var(--card-border);">
          <div>
            <?php if ($t->pengumpulan_id): ?>
              <a href="<?= site_url('siswa/detail_tugas/' . $t->id) ?>" class="btn btn-secondary btn-sm">
                <i class="fa-solid fa-eye"></i> <?= ($t->status_pengumpulan === 'revisi') ? 'Lihat Revisi' : 'Lihat Detail' ?>
              </a>
            <?php else: ?>
              <?php if (!$is_passed): ?>
                <a href="<?= site_url('siswa/detail_tugas/' . $t->id) ?>" class="btn btn-primary btn-sm">
                  <i class="fa-solid fa-upload"></i> Kumpulkan
                </a>
              <?php else: ?>
                <a href="<?= site_url('siswa/detail_tugas/' . $t->id) ?>" class="btn btn-secondary btn-sm" style="color: #BE123C; border-color: #FECACA;">
                  <i class="fa-solid fa-circle-exclamation"></i> Terlambat
                </a>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

