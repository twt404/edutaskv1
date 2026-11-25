<style>
  .page-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; margin-bottom: 24px; flex-wrap: wrap; }
  .eyebrow { display: inline-flex; align-items: center; gap: 8px; padding: 5px 12px; border-radius: 999px; background: var(--primary-light); color: var(--primary-dark); font-size: 0.7rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 10px; }
  .eyebrow span { width: 6px; height: 6px; border-radius: 50%; background: var(--primary-dark); }
  .page-head h2 { font-size: 1.85rem; line-height: 1.15; font-weight: 800; letter-spacing: -0.02em; color: var(--text-main); margin-bottom: 7px; }
  .page-head p { color: var(--text-muted); font-size: 0.92rem; max-width: 620px; }
  .class-stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; margin-bottom: 24px; }
  .class-stat { background: #FFFFFF; border: 1px solid var(--card-border); border-radius: 16px; padding: 18px 20px; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03); display: flex; align-items: center; justify-content: space-between; }
  .class-stat .label { font-size: 0.72rem; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; }
  .class-stat .value { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.75rem; font-weight: 800; line-height: 1; color: var(--text-main); }
  .class-stat .icon { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: var(--primary-light); color: var(--primary-dark); font-size: 1rem; }
  .class-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; }
  .class-card { background: #FFFFFF; border: 1px solid var(--card-border); border-radius: 18px; padding: 22px; display: flex; flex-direction: column; justify-content: space-between; min-height: 220px; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03); transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease; }
  .class-card:hover { transform: translateY(-2px); border-color: #CBD5E1; box-shadow: 0 10px 28px -18px rgba(15, 23, 42, 0.35); }
  .class-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 16px; }
  .class-code { display: inline-flex; align-items: center; gap: 7px; background: #EFF4FF; color: var(--primary-dark); border: 1px solid #DCE9FF; border-radius: 999px; padding: 5px 11px; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.04em; }
  .class-actions { display: flex; gap: 6px; }
  .icon-action { width: 32px; height: 32px; border-radius: 9px; display: inline-flex; align-items: center; justify-content: center; background: #F8F9FF; border: 1px solid var(--card-border); color: #434655; font-size: 0.78rem; transition: all 0.2s; }
  .icon-action:hover { background: var(--primary-light); color: var(--primary-dark); border-color: #D5E3FC; }
  .icon-action.danger:hover { background: #FFE4E6; color: #BE123C; border-color: #FECDD3; }
  .class-title { font-size: 1.08rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px; line-height: 1.35; }
  .class-title a { color: inherit; }
  .class-title a:hover { color: var(--primary-dark); }
  .class-rombel { display: inline-flex; align-items: center; gap: 6px; background: #EEF2FF; color: #3730A3; border-radius: 999px; padding: 3px 10px; font-size: 0.72rem; font-weight: 700; margin-bottom: 10px; }
  .class-rombel.empty { background: #F1F5F9; color: #64748B; }
  .class-desc { color: var(--text-muted); font-size: 0.86rem; line-height: 1.6; margin-bottom: 20px; min-height: 42px; }
  .class-footer { border-top: 1px solid #F1F5F9; padding-top: 15px; display: flex; align-items: center; justify-content: space-between; gap: 12px; }
  .class-meta { display: flex; align-items: center; gap: 7px; color: var(--text-muted); font-size: 0.8rem; }
  .class-meta i { color: var(--primary-dark); }
  .class-meta strong { color: var(--text-main); }
  .class-enter { display: inline-flex; align-items: center; gap: 8px; color: var(--primary-dark); font-size: 0.8rem; font-weight: 700; padding: 7px 11px; border-radius: 9px; transition: background 0.2s; }
  .class-enter:hover { background: var(--primary-light); }
  @media (max-width: 760px) { .class-stats { grid-template-columns: 1fr; } .page-head h2 { font-size: 1.5rem; } }
</style>

<?php
  $total_siswa = 0;
  foreach ($mapel_list as $mapel_item) {
      $total_siswa += (int) $mapel_item->jumlah_siswa;
  }
  $total_kelas = count(array_unique(array_map(function ($m) { return $m->kelas_id; }, $mapel_list)));
?>

<div class="page-head">
  <div>
    <div class="eyebrow"><span></span> Mata Pelajaran</div>
    <h2>Mapel Saya</h2>
    <p>Kelola mata pelajaran, materi, tugas, dan siswa pada setiap kelas yang Anda ampu.</p>
  </div>
  <a href="<?= site_url('guru/tambah_mapel') ?>" class="btn btn-primary">
    <i class="fa-solid fa-plus"></i>
    <span>Tambah Mapel</span>
  </a>
</div>

<div class="class-stats">
  <div class="class-stat">
    <div>
      <div class="label">Total Mapel</div>
      <div class="value"><?= count($mapel_list) ?></div>
    </div>
    <div class="icon"><i class="fa-solid fa-book-open"></i></div>
  </div>
  <div class="class-stat">
    <div>
      <div class="label">Total Siswa</div>
      <div class="value"><?= $total_siswa ?></div>
    </div>
    <div class="icon"><i class="fa-solid fa-user-graduate"></i></div>
  </div>
  <div class="class-stat">
    <div>
      <div class="label">Kelas Terhubung</div>
      <div class="value"><?= $total_kelas ?></div>
    </div>
    <div class="icon"><i class="fa-solid fa-people-group"></i></div>
  </div>
</div>

<?php if (!empty($mapel_list)): ?>
  <div class="class-grid">
    <?php foreach ($mapel_list as $m): ?>
      <div class="class-card">
        <div>
          <div class="class-top">
            <span class="class-code">
              <i class="fa-solid fa-key"></i>
              <?= html_escape($m->kode_mapel) ?>
            </span>
            <div class="class-actions">
              <a href="<?= site_url('guru/edit_mapel/' . $m->id) ?>" class="icon-action" title="Edit Mapel">
                <i class="fa-solid fa-pen"></i>
              </a>
              <a href="<?= site_url('guru/hapus_mapel/' . $m->id) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus mapel ini? Semua data materi dan tugas terkait akan terhapus.');" class="icon-action danger" title="Hapus Mapel">
                <i class="fa-solid fa-trash"></i>
              </a>
            </div>
          </div>

          <h3 class="class-title">
            <a href="<?= site_url('guru/detail_mapel/' . $m->id) ?>"><?= html_escape($m->nama_mapel) ?></a>
          </h3>

          <?php if (!empty($m->nama_kelas)): ?>
            <div class="class-rombel">
              <i class="fa-solid fa-people-group"></i>
              <?= html_escape($m->nama_kelas) ?><?= !empty($m->jurusan) ? ' · ' . html_escape($m->jurusan) : '' ?>
            </div>
          <?php else: ?>
            <div class="class-rombel empty">
              <i class="fa-solid fa-circle-exclamation"></i>
              Belum ada kelas
            </div>
          <?php endif; ?>

          <p class="class-desc">
            <?= !empty($m->deskripsi) ? html_escape(character_limiter($m->deskripsi, 110)) : '<em>Belum ada deskripsi mapel.</em>' ?>
          </p>
        </div>

        <div class="class-footer">
          <div class="class-meta">
            <i class="fa-solid fa-users"></i>
            <span><strong><?= (int) $m->jumlah_siswa ?></strong> Siswa</span>
          </div>
          <a href="<?= site_url('guru/detail_mapel/' . $m->id) ?>" class="class-enter">
            <span>Masuk Mapel</span>
            <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php else: ?>
  <div class="card">
    <div class="empty-state">
      <i class="fa-solid fa-book-open"></i>
      <h4>Belum Ada Mapel</h4>
      <p>Anda belum membuat mapel apapun. Silakan buat mapel baru untuk mulai mengajar dan membagikan materi.</p>
      <a href="<?= site_url('guru/tambah_mapel') ?>" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i>
        <span>Tambah Mapel Baru</span>
      </a>
    </div>
  </div>
<?php endif; ?>
