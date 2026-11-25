<div style="margin-bottom: 24px;">
  <a href="<?= site_url('guru/kelas') ?>" style="display: inline-flex; align-items: center; gap: 8px; color: var(--text-muted); font-size: 0.88rem; font-weight: 500; margin-bottom: 12px;">
    <i class="fa-solid fa-arrow-left"></i>
    <span>Kembali ke Kelas Saya</span>
  </a>

  <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
    <div>
      <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 6px;">
        <h2 style="font-size: 1.6rem; font-weight: 700; color: var(--text-main);"><?= html_escape($kelas->nama_kelas) ?></h2>
        <span class="badge" style="background: #EFF6FF; color: #2563EB; font-family: monospace; font-size: 0.85rem;">
          KODE: <?= html_escape($kelas->kode_kelas) ?>
        </span>
        <?php if (!empty($kelas->nama_rombel)): ?>
          <span class="badge" style="background: #EEF2FF; color: #3730A3; font-size: 0.85rem;">
            <i class="fa-solid fa-people-group" style="margin-right: 4px;"></i>
            <?= html_escape($kelas->nama_rombel) ?>
          </span>
        <?php else: ?>
          <span class="badge" style="background: #F1F5F9; color: #64748B; font-size: 0.85rem;">
            Belum ada rombel
          </span>
        <?php endif; ?>
      </div>
      <p style="color: var(--text-muted); font-size: 0.95rem; max-width: 750px;">
        <?= !empty($kelas->deskripsi) ? nl2br(html_escape($kelas->deskripsi)) : '<em>Tidak ada deskripsi.</em>' ?>
      </p>
    </div>

    <div style="display: flex; gap: 10px;">
      <a href="<?= site_url('guru/tambah_materi/' . $kelas->id) ?>" class="btn btn-secondary btn-sm">
        <i class="fa-solid fa-file-circle-plus"></i>
        <span>Tambah Materi</span>
      </a>
      <a href="<?= site_url('guru/tambah_tugas/' . $kelas->id) ?>" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-plus"></i>
        <span>Buat Tugas</span>
      </a>
    </div>
  </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
  <!-- LEFT COLUMN: MATERI & TUGAS -->
  <div style="display: flex; flex-direction: column; gap: 24px;">
    <!-- MATERI SECTION -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fa-solid fa-book-open" style="color: var(--primary); margin-right: 8px;"></i>
          Materi Pembelajaran
        </h3>
        <a href="<?= site_url('guru/tambah_materi/' . $kelas->id) ?>" class="btn btn-secondary btn-sm">
          <i class="fa-solid fa-plus"></i>
          <span>Tambah</span>
        </a>
      </div>

      <?php if (!empty($materi_list)): ?>
        <div style="display: flex; flex-direction: column; gap: 12px;">
          <?php foreach ($materi_list as $m): ?>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px 16px; border: 1px solid var(--card-border); border-radius: 10px; background: #FFF;">
              <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 36px; height: 36px; border-radius: 8px; background: #EFF6FF; color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem;">
                  <?= (int) $m->urutan ?>
                </div>
                <div>
                  <div style="font-weight: 600; font-size: 0.95rem; color: var(--text-main);"><?= html_escape($m->judul) ?></div>
                  <?php if (!empty($m->file_materi)): ?>
                    <a href="<?= base_url('uploads/materi/' . $m->file_materi) ?>" target="_blank" style="font-size: 0.8rem; color: var(--primary); display: inline-flex; align-items: center; gap: 4px; margin-top: 4px;">
                      <i class="fa-solid fa-paperclip"></i>
                      <span>Unduh Berkas</span>
                    </a>
                  <?php endif; ?>
                </div>
              </div>
              <div style="display: flex; gap: 6px;">
                <a href="<?= site_url('guru/edit_materi/' . $m->id) ?>" class="btn btn-secondary btn-sm" title="Edit">
                  <i class="fa-solid fa-pen"></i>
                </a>
                <a href="<?= site_url('guru/hapus_materi/' . $m->id) ?>" onclick="return confirm('Hapus materi ini?');" class="btn btn-danger btn-sm" title="Hapus">
                  <i class="fa-solid fa-trash"></i>
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty-state" style="padding: 24px;">
          <p>Belum ada materi pembelajaran di kelas ini.</p>
        </div>
      <?php endif; ?>
    </div>

    <!-- TUGAS SECTION -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fa-solid fa-list-check" style="color: var(--primary); margin-right: 8px;"></i>
          Tugas & Asesmen
        </h3>
        <a href="<?= site_url('guru/tambah_tugas/' . $kelas->id) ?>" class="btn btn-primary btn-sm">
          <i class="fa-solid fa-plus"></i>
          <span>Buat Tugas</span>
        </a>
      </div>

      <?php if (!empty($tugas_list)): ?>
        <div class="table-container">
          <table class="table">
            <thead>
              <tr>
                <th>Judul</th>
                <th>Jenis</th>
                <th>Deadline</th>
                <th style="text-align: right;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($tugas_list as $t): ?>
                <tr>
                  <td>
                    <a href="<?= site_url('guru/detail_tugas/' . $t->id) ?>" style="font-weight: 600; color: var(--primary);">
                      <?= html_escape($t->judul) ?>
                    </a>
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
                  <td style="text-align: right;">
                    <a href="<?= site_url('guru/detail_tugas/' . $t->id) ?>" class="btn btn-secondary btn-sm" title="Lihat Pengumpulan">
                      <i class="fa-solid fa-eye"></i>
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
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="empty-state" style="padding: 24px;">
          <p>Belum ada tugas untuk kelas ini.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- RIGHT COLUMN: SISWA -->
  <div>
    <div class="card">
      <div class="card-header" style="margin-bottom: 16px;">
        <h3 class="card-title" style="font-size: 1.05rem;">
          Siswa Terdaftar (<?= count($siswa_list) ?>)
        </h3>
      </div>

      <!-- ADD SISWA FORM dipindahkan ke Admin (menu Rombel) -->
      <div style="background: #EFF4FF; border: 1px solid #DCE9FF; border-radius: 10px; padding: 12px 14px; margin-bottom: 20px; font-size: 0.82rem; color: #334155; line-height: 1.5;">
        <i class="fa-solid fa-circle-info" style="color: var(--primary-dark); margin-right: 6px;"></i>
        Keanggotaan siswa dikelola oleh Admin melalui menu <strong>Rombel</strong>. Siswa yang ditambahkan ke rombel otomatis terdaftar di kelas ini.
      </div>

      <?php if (!empty($siswa_list)): ?>
        <div style="display: flex; flex-direction: column; gap: 10px; max-height: 480px; overflow-y: auto;">
          <?php foreach ($siswa_list as $s): ?>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 12px; background: #F8FAFC; border-radius: 8px;">
              <div style="display: flex; align-items: center; gap: 10px; min-width: 0;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: #E2E8F0; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 600; color: #475569; flex-shrink: 0;">
                  <?= strtoupper(substr($s->nama, 0, 1)) ?>
                </div>
                <div style="min-width: 0;">
                  <div style="font-size: 0.88rem; font-weight: 600; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= html_escape($s->nama) ?></div>
                  <div style="font-size: 0.78rem; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= html_escape($s->email) ?></div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty-state" style="padding: 18px 12px;">
          <p style="font-size: 0.85rem;">Belum ada siswa di kelas ini.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
