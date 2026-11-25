<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; gap: 16px; flex-wrap: wrap;">
  <div>
    <h2 style="font-size: 1.4rem; font-weight: 800; color: #0F172A;">Kelas Saya</h2>
    <p style="color: var(--text-muted); font-size: 0.9rem;">
      <?php if (!empty($kelas)): ?>
        Kelas Anda: <strong><?= html_escape($kelas->nama_kelas) ?></strong><?= !empty($kelas->jurusan) ? ' · ' . html_escape($kelas->jurusan) : '' ?>
      <?php else: ?>
        Anda belum bergabung ke kelas manapun
      <?php endif; ?>
    </p>
  </div>
  <a href="<?= site_url('siswa/gabung_kelas') ?>" class="btn btn-primary">
    <i class="fa-solid fa-people-group"></i> Gabung Kelas
  </a>
</div>

<?php if (empty($kelas)): ?>
  <div class="card">
    <div class="empty-state">
      <i class="fa-solid fa-graduation-cap"></i>
      <h4>Belum Terdaftar di Kelas</h4>
      <p>Anda belum dimasukkan ke kelas manapun. Masukkan kode kelas dari Admin untuk mulai bergabung.</p>
      <a href="<?= site_url('siswa/gabung_kelas') ?>" class="btn btn-primary">
        <i class="fa-solid fa-people-group"></i> Gabung Kelas dengan Kode
      </a>
    </div>
  </div>
<?php else: ?>
<div class="hero-panel" style="padding: 22px 24px; border-radius: 20px; margin-bottom: 24px;">
    <div style="position: relative; z-index: 1; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
      <div style="display: flex; align-items: center; gap: 14px;">
        <div style="width: 48px; height: 48px; border-radius: 15px; background: rgba(255, 255, 255, 0.22); border: 1px solid rgba(255, 255, 255, 0.3); color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
          <i class="fa-solid fa-people-group"></i>
        </div>
        <div>
          <div style="font-size: 1.05rem; font-weight: 800; color: #FFFFFF;"><?= html_escape($kelas->nama_kelas) ?></div>
          <div style="font-size: 0.82rem; color: rgba(255, 255, 255, 0.84); margin-top: 2px;">
            Kode: <strong style="font-family: monospace; color: #DFFFF4;"><?= html_escape($kelas->kode_kelas) ?></strong>
            &middot; <?= (int) $kelas->total_siswa ?> siswa
            &middot; <?= count($mapel_list) ?> mapel
          </div>
        </div>
      </div>
      <span style="display: inline-flex; align-items: center; gap: 6px; background: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.3); color: #FFFFFF; border-radius: 999px; padding: 6px 13px; font-size: 0.76rem; font-weight: 700;">
        <i class="fa-solid fa-layer-group"></i> Tingkat <?= html_escape($kelas->tingkat ?: '-') ?>
      </span>
    </div>
  </div>

  <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-main); margin-bottom: 14px;">Mata Pelajaran</h3>

  <?php if (empty($mapel_list)): ?>
    <div class="card">
      <div class="empty-state">
        <i class="fa-solid fa-book-open"></i>
        <h4>Belum Ada Mapel</h4>
        <p>Guru belum menambahkan mata pelajaran untuk kelas Anda.</p>
      </div>
    </div>
  <?php else: ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;">
      <?php foreach ($mapel_list as $m): ?>
        <div class="card" style="margin-bottom: 0; display: flex; flex-direction: column; justify-content: space-between; border-radius: 14px;">
          <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
              <span style="background: #E0F5FF; color: var(--primary-dark); font-size: 0.78rem; font-weight: 700; padding: 3px 8px; border-radius: 6px; font-family: monospace;">
                <?= html_escape($m->kode_mapel) ?>
              </span>
              <span style="font-size: 0.8rem; color: var(--text-muted);">
                <i class="fa-solid fa-users"></i> <?= (int) $m->total_siswa ?> Siswa
              </span>
            </div>

            <h3 style="font-size: 1.15rem; font-weight: 700; color: #1E293B; margin-bottom: 8px;">
              <a href="<?= site_url('siswa/detail_mapel/' . $m->id) ?>" style="color: inherit;">
                <?= html_escape($m->nama_mapel) ?>
              </a>
            </h3>

            <?php if (!empty($m->nama_kelas)): ?>
              <div style="display: inline-flex; align-items: center; gap: 6px; background: #E8FFF6; color: #047857; border-radius: 999px; padding: 3px 10px; font-size: 0.75rem; font-weight: 700; margin-bottom: 12px;">
                <i class="fa-solid fa-chalkboard"></i>
                <?= html_escape($m->nama_kelas) ?>
              </div>
            <?php endif; ?>

            <p style="font-size: 0.86rem; color: var(--text-muted); margin-bottom: 16px; line-height: 1.5;">
              <?= !empty($m->deskripsi) ? character_limiter(strip_tags($m->deskripsi), 100) : 'Tidak ada deskripsi untuk mapel ini.' ?>
            </p>

            <div style="font-size: 0.85rem; color: #334155; margin-bottom: 16px;">
              <i class="fa-solid fa-chalkboard-user" style="color: var(--primary); margin-right: 6px;"></i>
              Guru: <strong><?= html_escape($m->nama_guru ?: '-') ?></strong>
            </div>
          </div>

          <div style="padding-top: 14px; border-top: 1px solid var(--card-border);">
            <a href="<?= site_url('siswa/detail_mapel/' . $m->id) ?>" class="btn btn-primary" style="width: 100%; justify-content: center;">
              <i class="fa-solid fa-arrow-right-to-bracket"></i> Masuk Mapel
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
<?php endif; ?>
