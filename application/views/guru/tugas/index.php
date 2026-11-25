<style>
  .page-head-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 24px;
    flex-wrap: wrap;
  }
  .add-task-menu { position: relative; }
  .add-task-menu summary {
    list-style: none;
    cursor: pointer;
  }
  .add-task-menu summary::-webkit-details-marker { display: none; }
  .add-task-dropdown {
    position: absolute;
    right: 0;
    top: calc(100% + 8px);
    width: min(300px, calc(100vw - 32px));
    background: #FFFFFF;
    border: 1px solid var(--card-border);
    border-radius: 14px;
    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.14);
    padding: 8px;
    z-index: 30;
  }
  .dropdown-label {
    font-size: 0.68rem;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--text-muted);
    padding: 8px 10px 6px;
  }
  .add-task-dropdown a {
    display: block;
    padding: 10px;
    border-radius: 9px;
    text-decoration: none;
    transition: background 0.15s;
  }
  .add-task-dropdown a:hover { background: #EFF4FF; }
  .add-task-dropdown a span {
    display: block;
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--text-main);
  }
  .add-task-dropdown a small {
    display: block;
    font-size: 0.68rem;
    color: var(--text-muted);
    margin-top: 2px;
  }
  .dropdown-empty {
    padding: 12px 10px;
    font-size: 0.78rem;
    color: var(--text-muted);
    line-height: 1.5;
  }
  .dropdown-empty a { color: var(--primary-dark); font-weight: 700; }
</style>

<div class="page-head-row">
  <div>
    <h2 style="font-size: 1.4rem; font-weight: 700; color: var(--text-main);">Daftar Tugas</h2>
    <p style="color: var(--text-muted); font-size: 0.9rem;">Kelola tugas, asesmen, dan evaluasi hasil belajar siswa.</p>
  </div>
  <details class="add-task-menu">
    <summary class="btn btn-primary">
      <i class="fa-solid fa-plus"></i>
      <span>Tambah Tugas</span>
    </summary>
    <div class="add-task-dropdown">
      <div class="dropdown-label">Pilih Mapel</div>
      <?php if (!empty($mapel_list)): ?>
        <?php foreach ($mapel_list as $mp): ?>
          <a href="<?= site_url('guru/tambah_tugas/' . $mp->id) ?>">
            <span><?= html_escape($mp->nama_mapel) ?></span>
            <small><?= html_escape($mp->nama_kelas ?: 'Tanpa kelas') ?> &middot; <?= (int) $mp->jumlah_siswa ?> siswa</small>
          </a>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="dropdown-empty">
          Belum ada mapel. <a href="<?= site_url('guru/mapel') ?>">Buat mapel terlebih dahulu</a>.
        </div>
      <?php endif; ?>
    </div>
  </details>
</div>

<script>
  document.addEventListener('click', function (e) {
    document.querySelectorAll('details.add-task-menu[open]').forEach(function (menu) {
      if (!menu.contains(e.target)) menu.removeAttribute('open');
    });
  });
</script>

<div class="card">
  <?php if (!empty($tugas_list)): ?>
    <div class="table-container">
      <table class="table">
        <thead>
          <tr>
            <th>Judul Tugas</th>
            <th>Mapel</th>
            <th>Jenis</th>
            <th>Deadline</th>
            <th>Pengumpulan</th>
            <th>Status Deadline</th>
            <th style="text-align: right;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($tugas_list as $t): ?>
            <?php 
              $is_expired = (strtotime($t->deadline) < time());
            ?>
            <tr>
              <td>
                <a href="<?= site_url('guru/detail_tugas/' . $t->id) ?>" style="font-weight: 600; color: var(--text-main);">
                  <?= html_escape($t->judul) ?>
                </a>
                <?php if (!empty($t->status) && $t->status === 'draft'): ?>
                  <div style="margin-top: 4px;"><span class="badge badge-pending" style="font-size: 0.68rem;"><i class="fa-solid fa-eye-slash"></i> Draft</span></div>
                <?php elseif (!empty($t->status) && $t->status === 'scheduled'): ?>
                  <div style="margin-top: 4px;"><span class="badge badge-individu" style="font-size: 0.68rem;"><i class="fa-regular fa-clock"></i> Terjadwal <?= !empty($t->publish_at) ? date('d M H:i', strtotime($t->publish_at)) : '' ?></span></div>
                <?php endif; ?>
              </td>
              <td>
                <span class="badge" style="background: #F1F5F9; color: #334155;">
                  <?= html_escape($t->nama_mapel) ?><?= !empty($t->nama_kelas) ? ' · ' . html_escape($t->nama_kelas) : '' ?>
                </span>
              </td>
              <td>
                <?php if ($t->jenis === 'kelompok'): ?>
                  <span class="badge badge-kelompok">Kelompok</span>
                <?php else: ?>
                  <span class="badge badge-individu">Individu</span>
                <?php endif; ?>
              </td>
              <td>
                <span style="font-size: 0.85rem; color: var(--text-muted);">
                  <?= date('d M Y, H:i', strtotime($t->deadline)) ?>
                </span>
              </td>
              <td>
                <div style="display: inline-flex; align-items: center; gap: 6px; font-weight: 600; font-size: 0.88rem; color: var(--text-main);">
                  <i class="fa-solid fa-file-arrow-up" style="color: var(--primary);"></i>
                  <span><?= (int) $t->jumlah_pengumpulan ?> Terkumpul</span>
                </div>
              </td>
              <td>
                <?php if ($is_expired): ?>
                  <span class="badge badge-overdue">Selesai / Ditutup</span>
                <?php else: ?>
                  <span class="badge badge-graded">Aktif</span>
                <?php endif; ?>
              </td>
              <td style="text-align: right;">
                <div style="display: inline-flex; gap: 6px;">
                  <a href="<?= site_url('guru/detail_tugas/' . $t->id) ?>" class="btn btn-primary btn-sm" title="Lihat Submisi">
                    <i class="fa-solid fa-eye"></i>
                    <span>Detail</span>
                  </a>
                  <?php if ($t->jenis === 'kelompok'): ?>
                    <a href="<?= site_url('guru/kelola_kelompok/' . $t->id) ?>" class="btn btn-secondary btn-sm" title="Kelola Kelompok">
                      <i class="fa-solid fa-users-gear"></i>
                    </a>
                  <?php endif; ?>
                  <a href="<?= site_url('guru/edit_tugas/' . $t->id) ?>" class="btn btn-secondary btn-sm" title="Edit">
                    <i class="fa-solid fa-pen"></i>
                  </a>
                  <a href="<?= site_url('guru/hapus_tugas/' . $t->id) ?>" onclick="return confirm('Hapus tugas ini beserta seluruh pengumpulannya?');" class="btn btn-danger btn-sm" title="Hapus">
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
      <i class="fa-solid fa-list-check"></i>
      <h4>Belum Ada Tugas</h4>
      <p>Buka detail mapel untuk menambahkan tugas baru bagi siswa Anda.</p>
      <a href="<?= site_url('guru/mapel') ?>" class="btn btn-primary">
        <span>Buka Mapel Saya</span>
      </a>
    </div>
  <?php endif; ?>
</div>
