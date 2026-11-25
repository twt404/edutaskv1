<?php
  $mode_label = [
    'acak'  => 'Acak Otomatis',
    'guru'  => 'Ditentukan Guru',
    'siswa' => 'Siswa Memilih',
  ];
  $mode_text = isset($mode_label[$tugas->mode_kelompok]) ? $mode_label[$tugas->mode_kelompok] : '-';
?>

<div style="margin-bottom: 24px;">
  <a href="<?= site_url('guru/detail_tugas/' . $tugas->id) ?>" style="display: inline-flex; align-items: center; gap: 8px; color: var(--text-muted); font-size: 0.88rem; font-weight: 500; margin-bottom: 12px;">
    <i class="fa-solid fa-arrow-left"></i>
    <span>Kembali ke Detail Tugas</span>
  </a>

  <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
    <div>
      <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-main); margin-bottom: 8px;">
        Kelola Kelompok
      </h2>
      <p style="color: var(--text-muted); font-size: 0.9rem;">
        <?= html_escape($mapel->nama_mapel) ?><?= !empty($mapel->nama_kelas) ? ' · ' . html_escape($mapel->nama_kelas) : '' ?> &middot; <?= html_escape($tugas->judul) ?>
      </p>
    </div>

    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
      <?php if ($tugas->mode_kelompok === 'acak' && ! $has_submission): ?>
        <?= form_open('guru/acak_ulang_kelompok/' . $tugas->id, ['style' => 'display:inline;']) ?>
          <button type="submit" class="btn btn-secondary btn-sm" onclick="return confirm('Acak ulang seluruh anggota kelompok?');">
            <i class="fa-solid fa-shuffle"></i>
            <span>Acak Ulang</span>
          </button>
        <?= form_close() ?>
      <?php endif; ?>

      <?php if ($tugas->mode_kelompok === 'siswa'): ?>
        <?= form_open('guru/kunci_kelompok/' . $tugas->id, ['style' => 'display:inline;']) ?>
          <button type="submit" class="btn <?= $tugas->kelompok_locked ? 'btn-success' : 'btn-secondary' ?> btn-sm">
            <i class="fa-solid <?= $tugas->kelompok_locked ? 'fa-lock-open' : 'fa-lock' ?>"></i>
            <span><?= $tugas->kelompok_locked ? 'Buka Kunci' : 'Kunci Kelompok' ?></span>
          </button>
        <?= form_close() ?>
      <?php endif; ?>

      <?= form_open('guru/tambah_kelompok/' . $tugas->id, ['style' => 'display:inline;']) ?>
        <button type="submit" class="btn btn-primary btn-sm">
          <i class="fa-solid fa-plus"></i>
          <span>Tambah Kelompok</span>
        </button>
      <?= form_close() ?>
    </div>
  </div>
</div>

<div class="card" style="margin-bottom: 24px; display: flex; gap: 28px; flex-wrap: wrap;">
  <div>
    <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Mode Pembentukan</div>
    <div style="font-size: 1rem; font-weight: 700; color: var(--text-main); margin-top: 4px;"><?= html_escape($mode_text) ?></div>
  </div>
  <div>
    <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Anggota per Kelompok</div>
    <div style="font-size: 1rem; font-weight: 700; color: var(--text-main); margin-top: 4px;"><?= (int) $tugas->anggota_per_kelompok ?> siswa</div>
  </div>
  <div>
    <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Jumlah Kelompok</div>
    <div style="font-size: 1rem; font-weight: 700; color: var(--text-main); margin-top: 4px;"><?= count($groups) ?></div>
  </div>
  <div>
    <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Status</div>
    <div style="margin-top: 4px;">
      <?php if ($tugas->kelompok_locked): ?>
        <span class="badge badge-overdue"><i class="fa-solid fa-lock"></i> Terkunci</span>
      <?php else: ?>
        <span class="badge badge-pending"><i class="fa-solid fa-lock-open"></i> Terbuka</span>
      <?php endif; ?>
    </div>
  </div>
  <?php if ($has_submission): ?>
    <div style="margin-left: auto; align-self: center; color: #B45309; font-size: 0.82rem; font-weight: 600;">
      <i class="fa-solid fa-triangle-exclamation"></i> Sudah ada pengumpulan, kelompok tidak dapat diacak/dihapus.
    </div>
  <?php endif; ?>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;">
  <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 18px;">
    <?php if (empty($groups)): ?>
      <div class="card">
        <div class="empty-state">
          <i class="fa-solid fa-users"></i>
          <h4>Belum Ada Kelompok</h4>
          <p>Tambahkan kelompok terlebih dahulu, lalu atur anggotanya.</p>
        </div>
      </div>
    <?php else: ?>
      <?php foreach ($groups as $g): ?>
        <?php $member_count = count($g->members); ?>
        <div class="card" style="margin-bottom: 0;">
          <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 14px;">
            <div>
              <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-main); margin-bottom: 4px;">
                <?= html_escape($g->nama_kelompok) ?>
              </h3>
              <span style="font-size: 0.78rem; color: var(--text-muted);">
                <i class="fa-solid fa-user-group"></i>
                <?= $member_count ?> / <?= (int) $tugas->anggota_per_kelompok ?> anggota
              </span>
            </div>
            <?php if ( ! $has_submission): ?>
              <?= form_open('guru/hapus_kelompok/' . $g->id, ['style' => 'display:inline;']) ?>
                <button type="submit" class="btn btn-secondary btn-sm" style="color: #BE123C; border-color: #FECACA;" onclick="return confirm('Hapus kelompok ini?');" title="Hapus kelompok">
                  <i class="fa-solid fa-trash"></i>
                </button>
              <?= form_close() ?>
            <?php endif; ?>
          </div>

          <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 14px;">
            <?php if (empty($g->members)): ?>
              <p style="font-size: 0.82rem; color: var(--text-muted);">Belum ada anggota.</p>
            <?php else: ?>
              <?php foreach ($g->members as $m): ?>
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 8px; padding: 8px 10px; background: #F8FAFC; border-radius: 8px;">
                  <div style="min-width: 0;">
                    <div style="font-size: 0.85rem; font-weight: 600; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= html_escape($m->nama) ?></div>
                    <div style="font-size: 0.72rem; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= html_escape($m->email) ?></div>
                  </div>
                  <?php if ( ! $has_submission): ?>
                    <?= form_open('guru/hapus_anggota_kelompok/' . $g->id . '/' . $m->id, ['style' => 'display:inline;']) ?>
                      <button type="submit" style="background: none; border: 0; color: #94A3B8; cursor: pointer; padding: 4px;" title="Keluarkan dari kelompok" onclick="return confirm('Keluarkan siswa ini dari kelompok?');">
                        <i class="fa-solid fa-xmark"></i>
                      </button>
                    <?= form_close() ?>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>

          <?php if ( ! $has_submission && ! empty($unassigned)): ?>
            <?= form_open('guru/tambah_anggota_kelompok/' . $g->id) ?>
              <div style="display: flex; gap: 8px;">
                <select name="siswa_id" class="form-control" style="height: 36px; font-size: 0.8rem;" required>
                  <option value="">Tambah anggota...</option>
                  <?php foreach ($unassigned as $u): ?>
                    <option value="<?= (int) $u->id ?>"><?= html_escape($u->nama) ?></option>
                  <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-secondary btn-sm" style="height: 36px; white-space: nowrap;">
                  <i class="fa-solid fa-user-plus"></i>
                </button>
              </div>
            <?= form_close() ?>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <div>
    <div class="card">
      <div class="card-header">
        <h3 class="card-title" style="font-size: 1rem;">
          <i class="fa-solid fa-user-clock" style="color: #D97706; margin-right: 6px;"></i>
          Belum Masuk Kelompok (<?= count($unassigned) ?>)
        </h3>
      </div>

      <?php if (empty($unassigned)): ?>
        <p style="font-size: 0.84rem; color: var(--text-muted);">Semua siswa sudah masuk kelompok.</p>
      <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 8px; max-height: 420px; overflow-y: auto;">
          <?php foreach ($unassigned as $u): ?>
            <div style="padding: 10px 12px; background: #F8FAFC; border-radius: 8px;">
              <div style="font-size: 0.86rem; font-weight: 600; color: var(--text-main);"><?= html_escape($u->nama) ?></div>
              <div style="font-size: 0.74rem; color: var(--text-muted);"><?= html_escape($u->email) ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="card" style="background: #EFF4FF; border-color: #D5E3FC;">
      <h3 class="card-title" style="font-size: 0.95rem; margin-bottom: 10px;">
        <i class="fa-solid fa-circle-info" style="color: var(--primary-dark); margin-right: 6px;"></i>
        Catatan
      </h3>
      <ul style="font-size: 0.8rem; color: #334155; line-height: 1.6; padding-left: 18px;">
        <li>Mode <strong>Acak</strong> membagi seluruh siswa secara merata; tombol acak ulang tersedia selama belum ada pengumpulan.</li>
        <li>Mode <strong>Ditentukan Guru</strong> memungkinkan Anda menyusun anggota secara manual di halaman ini.</li>
        <li>Mode <strong>Siswa Memilih</strong> membatasi jumlah anggota sesuai kapasitas; kunci kelompok agar siswa tidak bisa berpindah.</li>
      </ul>
    </div>
  </div>
</div>
