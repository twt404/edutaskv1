<style>
  .form-page-head {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 22px;
    flex-wrap: wrap;
  }
  .form-page-head h2 {
    font-size: 1.75rem;
    font-weight: 800;
    letter-spacing: -0.02em;
    color: var(--text-main);
    margin: 6px 0 6px;
  }
  .form-page-head p {
    color: var(--text-muted);
    font-size: 0.9rem;
    max-width: 620px;
  }
  .crumb {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--text-muted);
    font-size: 0.76rem;
    font-weight: 600;
    margin-bottom: 2px;
  }
  .crumb a { color: var(--primary-dark); }
  .form-layout {
    display: grid;
    grid-template-columns: minmax(0, 1.7fr) minmax(280px, 1fr);
    gap: 20px;
    align-items: start;
  }
  .form-card {
    background: #FFFFFF;
    border: 1px solid var(--card-border);
    border-radius: 18px;
    padding: 22px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
    margin-bottom: 18px;
  }
  .form-card:last-child { margin-bottom: 0; }
  .section-head {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 18px;
  }
  .step-badge {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: var(--primary-dark);
    color: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.78rem;
    font-weight: 700;
    flex-shrink: 0;
  }
  .section-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-main);
    line-height: 1.2;
  }
  .section-sub {
    font-size: 0.76rem;
    color: var(--text-muted);
    margin-top: 3px;
  }
  .form-row {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
  }
  .form-group { margin-bottom: 16px; }
  .form-group:last-child { margin-bottom: 0; }
  .form-label {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--text-main);
    margin-bottom: 7px;
  }
  .required { color: #E11D48; }
  .form-control {
    background: #F8F9FF;
    border-color: #DCE9FF;
    border-radius: 10px;
  }
  .form-control:focus { background: #FFFFFF; }
  .form-text { font-size: 0.73rem; color: var(--text-muted); margin-top: 5px; }
  .readonly-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #EFF4FF;
    border: 1px solid #DCE9FF;
    color: var(--primary-dark);
    padding: 9px 12px;
    border-radius: 10px;
    font-size: 0.82rem;
    font-weight: 700;
  }
  .type-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
  }
  .type-option {
    position: relative;
    border: 1px solid var(--card-border);
    border-radius: 12px;
    padding: 14px;
    background: #FFFFFF;
    transition: all 0.2s;
  }
  .type-option:has(input:checked) {
    border-color: var(--primary-dark);
    background: #EFF4FF;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
  }
  .type-option input { position: absolute; opacity: 0; pointer-events: none; }
  .type-option label {
    display: flex;
    flex-direction: column;
    gap: 4px;
    cursor: pointer;
    color: var(--text-main);
    font-weight: 700;
    font-size: 0.85rem;
  }
  .type-option label span {
    color: var(--text-muted);
    font-size: 0.72rem;
    font-weight: 400;
    line-height: 1.45;
  }
  .group-settings {
    margin-top: 16px;
    background: #EFF4FF;
    border: 1px solid #DCE9FF;
    border-radius: 14px;
    padding: 18px;
  }
  .group-settings-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.88rem;
    font-weight: 700;
    color: var(--text-main);
    margin-bottom: 14px;
  }
  .group-settings-title i { color: var(--primary-dark); }
  .group-label {
    display: block;
    font-size: 0.76rem;
    font-weight: 700;
    color: var(--text-main);
    margin-bottom: 7px;
  }
  .group-modes {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 9px;
    margin-bottom: 16px;
  }
  .group-mode {
    position: relative;
    display: block;
    cursor: pointer;
    background: #FFFFFF;
    border: 1px solid var(--card-border);
    border-radius: 11px;
    padding: 11px 10px 11px 34px;
    transition: all 0.2s;
  }
  .group-mode:has(input:checked) {
    border-color: var(--primary-dark);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
  }
  .group-mode input {
    position: absolute;
    left: 12px;
    top: 13px;
    accent-color: var(--primary-dark);
    margin: 0;
  }
  .group-mode strong {
    display: block;
    font-size: 0.74rem;
    color: var(--text-main);
    margin-bottom: 2px;
  }
  .group-mode span {
    display: block;
    font-size: 0.65rem;
    color: var(--text-muted);
    line-height: 1.4;
  }
  .group-two-col {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
    margin-bottom: 16px;
  }
  .group-field {
    background: #FFFFFF;
    border: 1px solid var(--card-border);
    border-radius: 11px;
    padding: 11px 12px;
  }
  .group-field .group-label { margin-bottom: 6px; }
  .stepper {
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .stepper input {
    width: 54px;
    height: 34px;
    border: 1px solid #DCE9FF;
    border-radius: 8px;
    background: #F8F9FF;
    text-align: center;
    font-family: inherit;
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--text-main);
  }
  .stepper button {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    border: 1px solid #DCE9FF;
    background: #FFFFFF;
    color: var(--primary-dark);
    font-weight: 700;
    cursor: pointer;
  }
  .stepper button:hover { background: #E6EEFF; }
  .stepper small {
    font-size: 0.67rem;
    color: var(--text-muted);
    line-height: 1.3;
  }
  .check-row {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    padding: 10px 12px;
    background: #FFFFFF;
    border: 1px solid var(--card-border);
    border-radius: 10px;
    margin-bottom: 8px;
    cursor: pointer;
  }
  .check-row:last-child { margin-bottom: 0; }
  .check-row input {
    margin-top: 2px;
    accent-color: var(--primary-dark);
  }
  .check-row strong {
    display: block;
    font-size: 0.74rem;
    color: var(--text-main);
    margin-bottom: 1px;
  }
  .check-row span {
    display: block;
    font-size: 0.66rem;
    color: var(--text-muted);
    line-height: 1.4;
  }
  .side-card {
    background: #FFFFFF;
    border: 1px solid var(--card-border);
    border-radius: 18px;
    padding: 20px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
    margin-bottom: 18px;
  }
  .side-title {
    display: flex;
    align-items: center;
    gap: 9px;
    font-size: 0.92rem;
    font-weight: 700;
    color: var(--text-main);
    margin-bottom: 15px;
  }
  .side-title i { color: var(--primary-dark); }
  .summary-list { display: flex; flex-direction: column; gap: 12px; }
  .summary-item {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid #F1F5F9;
  }
  .summary-item:last-child { border-bottom: 0; padding-bottom: 0; }
  .summary-label { font-size: 0.72rem; color: var(--text-muted); }
  .summary-value {
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--text-main);
    text-align: right;
  }
  .rubric-item {
    background: #F8F9FF;
    border: 1px solid #F1F5F9;
    border-radius: 10px;
    padding: 11px 12px;
    margin-bottom: 9px;
  }
  .rubric-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    font-size: 0.76rem;
    font-weight: 700;
    color: var(--text-main);
    margin-bottom: 4px;
  }
  .rubric-top span { color: var(--primary-dark); }
  .rubric-text { font-size: 0.68rem; color: var(--text-muted); line-height: 1.45; }
  .rubric-bar {
    height: 5px;
    background: #E2E8F0;
    border-radius: 999px;
    overflow: hidden;
    margin-top: 7px;
  }
  .rubric-bar span { display: block; height: 100%; background: var(--primary-dark); border-radius: inherit; }
  .form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    padding: 16px 20px;
    background: #FFFFFF;
    border: 1px solid var(--card-border);
    border-radius: 16px;
    position: sticky;
    bottom: 16px;
    box-shadow: 0 10px 30px -20px rgba(15, 23, 42, 0.4);
  }
  .item-row {
    border: 1px solid #DCE9FF;
    background: #F8F9FF;
    border-radius: 12px;
    padding: 14px;
    margin-bottom: 12px;
    position: relative;
  }
  .item-row:last-child { margin-bottom: 0; }
  .item-row .form-control { background: #FFFFFF; }
  .item-row-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 9px;
  }
  .item-row-number {
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--primary-dark);
  }
  .item-remove {
    border: 0;
    background: transparent;
    color: #BE123C;
    font-size: 0.76rem;
    font-weight: 700;
    cursor: pointer;
    padding: 2px 4px;
  }
  .item-add-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    border: 1px dashed #93C5FD;
    background: #EFF6FF;
    color: var(--primary-dark);
    border-radius: 10px;
    padding: 9px 14px;
    font-size: 0.8rem;
    font-weight: 700;
    cursor: pointer;
  }
  .item-add-btn:hover { background: #DBEAFE; }
  .item-empty {
    text-align: center;
    color: var(--text-muted);
    font-size: 0.8rem;
    padding: 16px;
    border: 1px dashed var(--card-border);
    border-radius: 10px;
    margin-bottom: 12px;
  }
  @media (max-width: 980px) {
    .form-layout { grid-template-columns: 1fr; }
  }
  @media (max-width: 640px) {
    .form-row, .type-grid, .group-modes, .group-two-col { grid-template-columns: 1fr; }
    .form-page-head h2 { font-size: 1.4rem; }
  }
</style>

<?php
  $is_edit = isset($tugas);
  $back_url = $is_edit ? site_url('guru/detail_tugas/' . $tugas->id) : site_url('guru/detail_mapel/' . $mapel->id);
?>

<div class="form-page-head">
  <div>
    <div class="crumb">
      <a href="<?= site_url('guru/tugas') ?>">Tugas</a>
      <i class="fa-solid fa-chevron-right" style="font-size: 0.6rem;"></i>
      <span><?= $is_edit ? 'Edit Tugas' : 'Buat Tugas Baru' ?></span>
    </div>
    <h2><?= $is_edit ? 'Edit Tugas' : 'Buat Tugas Baru untuk Mapel' ?></h2>
    <p>Lengkapi informasi tugas, tentukan jenis penugasan, dan atur batas waktu pengumpulan.</p>
  </div>
  <a href="<?= $back_url ?>" class="btn btn-secondary">
    <i class="fa-solid fa-arrow-left"></i>
    <span>Kembali</span>
  </a>
</div>

<?= form_open_multipart($is_edit ? 'guru/edit_tugas/' . $tugas->id : 'guru/tambah_tugas/' . $mapel->id) ?>
  <div class="form-layout">
    <div>
      <div class="form-card">
        <div class="section-head">
          <div class="step-badge">1</div>
          <div>
            <div class="section-title">Informasi Dasar Tugas</div>
            <div class="section-sub">Identitas tugas dan instruksi pengerjaan untuk siswa.</div>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Judul Tugas <span class="required">*</span></label>
          <input type="text" name="judul" class="form-control" placeholder="Contoh: Proyek Akhir CRUD dengan CI3" value="<?= set_value('judul', $is_edit ? $tugas->judul : '') ?>" required>
          <?= form_error('judul', '<div class="form-text" style="color: #E11D48;">', '</div>') ?>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Mapel</label>
            <div class="readonly-chip"><i class="fa-solid fa-book-open"></i> <?= html_escape($mapel->nama_mapel) ?></div>
          </div>
          <div class="form-group">
            <label class="form-label">Kelas</label>
            <div class="readonly-chip"><i class="fa-solid fa-people-group"></i> <?= html_escape($mapel->nama_kelas ?: 'Tanpa kelas') ?></div>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Instruksi &amp; Deskripsi Tugas</label>
          <textarea name="deskripsi" class="form-control" rows="6" placeholder="Tulis instruksi lengkap, kriteria penilaian, dan format pengumpulan..."><?= set_value('deskripsi', $is_edit ? $tugas->deskripsi : '') ?></textarea>
        </div>
      </div>

      <div class="form-card">
        <div class="section-head">
          <div class="step-badge">2</div>
          <div>
            <div class="section-title">Tipe Penugasan</div>
            <div class="section-sub">Tentukan apakah tugas dikerjakan secara individu atau kelompok.</div>
          </div>
        </div>

        <?php if (!$is_edit): ?>
          <div class="type-grid">
            <div class="type-option">
              <input type="radio" name="jenis" id="jenis_individu" value="individu" <?= set_radio('jenis', 'individu', TRUE) ?> onchange="toggleKelompokInput('individu')">
              <label for="jenis_individu">
                <span style="color: var(--text-main); font-size: 0.88rem;"><i class="fa-solid fa-user" style="margin-right: 6px; color: #0D9488;"></i> Tugas Individu</span>
                <span>Siswa mengumpulkan pekerjaan masing-masing secara mandiri.</span>
              </label>
            </div>
            <div class="type-option">
              <input type="radio" name="jenis" id="jenis_kelompok" value="kelompok" <?= set_radio('jenis', 'kelompok') ?> onchange="toggleKelompokInput('kelompok')">
              <label for="jenis_kelompok">
                <span style="color: var(--text-main); font-size: 0.88rem;"><i class="fa-solid fa-users" style="margin-right: 6px; color: #7C3AED;"></i> Tugas Kelompok</span>
                <span>Satu hasil dikumpulkan untuk seluruh anggota kelompok.</span>
              </label>
            </div>
          </div>

          <div class="group-settings" id="kelompok_count_container" style="display: none;">
            <div class="group-settings-title"><i class="fa-solid fa-users-gear"></i> Pengaturan Khusus Kelompok</div>

            <label class="group-label">Mode Pembentukan Kelompok</label>
            <div class="group-modes">
              <label class="group-mode">
                <input type="radio" name="mode_kelompok" value="acak" <?= set_radio('mode_kelompok', 'acak', TRUE) ?>>
                <strong>Acak Otomatis</strong>
                <span>Sistem membagi siswa secara merata.</span>
              </label>
              <label class="group-mode">
                <input type="radio" name="mode_kelompok" value="guru" <?= set_radio('mode_kelompok', 'guru') ?>>
                <strong>Ditentukan Guru</strong>
                <span>Guru menyusun anggota setiap tim.</span>
              </label>
              <label class="group-mode">
                <input type="radio" name="mode_kelompok" value="siswa" <?= set_radio('mode_kelompok', 'siswa') ?>>
                <strong>Siswa Memilih</strong>
                <span>Siswa membentuk kelompok mandiri.</span>
              </label>
            </div>

            <div class="group-two-col">
              <div class="group-field">
                <label class="group-label">Jumlah Anggota per Kelompok</label>
                <div class="stepper">
                  <button type="button" onclick="stepValue('anggota_per_kelompok', -1, 2, 10)" aria-label="Kurangi anggota">−</button>
                  <input type="number" id="anggota_per_kelompok" name="anggota_per_kelompok" value="<?= set_value('anggota_per_kelompok', 5) ?>" min="2" max="10">
                  <button type="button" onclick="stepValue('anggota_per_kelompok', 1, 2, 10)" aria-label="Tambah anggota">+</button>
                  <small>Siswa / kelompok</small>
                </div>
              </div>
              <div class="group-field">
                <label class="group-label">Batas Maksimum Tim di Mapel</label>
                <div class="stepper">
                  <button type="button" onclick="stepValue('jumlah_kelompok', -1, 1, 20)" aria-label="Kurangi kelompok">−</button>
                  <input type="number" id="jumlah_kelompok" name="jumlah_kelompok" value="<?= set_value('jumlah_kelompok', 4) ?>" min="1" max="20">
                  <button type="button" onclick="stepValue('jumlah_kelompok', 1, 1, 20)" aria-label="Tambah kelompok">+</button>
                  <small>Kelompok terdaftar</small>
                </div>
              </div>
            </div>

            <label class="check-row">
              <input type="checkbox" name="allow_revisi" value="1" <?= set_checkbox('allow_revisi', '1', TRUE) ?>>
              <div>
                <strong>Izinkan semua anggota mengunggah berkas revisi</strong>
                <span>Setiap anggota dapat memperbarui berkas sebelum tenggat.</span>
              </div>
            </label>
            <label class="check-row">
              <input type="checkbox" name="wajib_kontribusi" value="1" <?= set_checkbox('wajib_kontribusi', '1', TRUE) ?>>
              <div>
                <strong>Wajibkan cantumkan lembar kontribusi anggota kelompok</strong>
                <span>Lembar kontribusi menjadi bagian dari berkas yang dikumpulkan.</span>
              </div>
            </label>
          </div>

        <?php else: ?>
          <div class="readonly-chip">
            <?php if ($tugas->jenis === 'kelompok'): ?>
              <i class="fa-solid fa-users"></i> Tugas Kelompok
            <?php else: ?>
              <i class="fa-solid fa-user"></i> Tugas Individu
            <?php endif; ?>
          </div>
          <div class="form-text" style="margin-top: 8px;">Jenis tugas tidak dapat diubah setelah tugas dibuat.</div>
          <?php if ($tugas->jenis === 'kelompok'): ?>
            <div class="form-text" style="margin-top: 10px;">
              Mode pembentukan: <strong><?= html_escape(ucfirst((string) $tugas->mode_kelompok)) ?></strong>
              &middot; <?= (int) $tugas->anggota_per_kelompok ?> anggota/kelompok
              &middot; <?= $tugas->kelompok_locked ? 'Terkunci' : 'Terbuka' ?>
              <br>
              <a href="<?= site_url('guru/kelola_kelompok/' . $tugas->id) ?>" style="color: var(--primary-dark); font-weight: 700;">
                <i class="fa-solid fa-users-gear"></i> Kelola Kelompok
              </a>
            </div>
          <?php endif; ?>
        <?php endif; ?>

        <script>
          function toggleKelompokInput(val) {
            var group_el = document.getElementById('kelompok_count_container');
            var individu_el = document.getElementById('individu_siswa_container');
            if (group_el) group_el.style.display = (val === 'kelompok') ? 'block' : 'none';
            if (individu_el) individu_el.style.display = (val === 'individu') ? 'block' : 'none';
            var summary = document.getElementById('summary_jenis');
            if (summary) summary.textContent = (val === 'kelompok') ? 'Kelompok' : 'Individu';
          }

          function selectAllSiswa(status) {
            var checkboxes = document.querySelectorAll('.siswa-checkbox');
            checkboxes.forEach(function(cb) { cb.checked = status; });
          }

          function stepValue(id, delta, min, max) {
            var el = document.getElementById(id);
            var value = parseInt(el.value || min, 10) + delta;
            el.value = Math.max(min, Math.min(max, value));
          }

          document.addEventListener('DOMContentLoaded', function () {
            var checked = document.querySelector('input[name="jenis"]:checked');
            if (checked) toggleKelompokInput(checked.value);
          });
        </script>

        <?php
          $jenis_aktif = $is_edit ? $tugas->jenis : set_value('jenis', 'individu');
          $show_penerima = ($jenis_aktif === 'individu');
        ?>
        <div id="individu_siswa_container" style="margin-top: 16px; background: #F0FDF4; border: 1px solid #DCFCE7; border-radius: 14px; padding: 18px; display: <?= $show_penerima ? 'block' : 'none' ?>;">
          <div class="group-settings-title"><i class="fa-solid fa-user-check"></i> Pilih Siswa Penerima Tugas</div>
          <p class="section-sub" style="margin-bottom: 12px;">Biarkan kosong untuk memberikan tugas kepada seluruh siswa di mapel ini.</p>
          <div style="margin-bottom: 10px;">
            <button type="button" class="btn btn-secondary btn-sm" onclick="selectAllSiswa(true)">Pilih Semua</button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="selectAllSiswa(false)">Hapus Semua</button>
          </div>
          <div style="max-height: 250px; overflow-y: auto; border: 1px solid #D1FAE5; border-radius: 10px; background: #FFFFFF; padding: 10px;">
            <?php if (empty($students)): ?>
              <div style="font-size: 0.82rem; color: var(--text-muted); padding: 6px;">Belum ada siswa di mapel ini.</div>
            <?php else: ?>
              <?php foreach ($students as $s): ?>
                <label class="check-row" style="margin-bottom: 5px; padding: 8px 10px; border-color: #ECFDF5;">
                  <input type="checkbox" name="siswa_ids[]" value="<?= $s->id ?>" 
                    <?= (isset($penerima) && in_array($s->id, $penerima)) ? 'checked' : '' ?> class="siswa-checkbox">
                  <div style="margin-left: 5px;">
                    <strong style="font-size: 0.8rem;"><?= html_escape($s->nama) ?></strong>
                    <span style="font-size: 0.7rem; color: var(--text-muted);"><?= html_escape($s->email) ?></span>
                  </div>
                </label>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="form-card">
        <div class="section-head">
          <div class="step-badge">3</div>
          <div>
            <div class="section-title">Poin Berkas yang Dikumpulkan</div>
            <div class="section-sub">Tentukan bagian/berkas apa saja yang harus diunggah siswa. Semua poin bersifat opsional.</div>
          </div>
        </div>

        <div id="tugas-item-list">
          <?php if (empty($items)): ?>
            <div class="item-empty" id="item-empty-state">
              <i class="fa-regular fa-folder-open"></i>
              Belum ada poin. Klik tombol di bawah untuk menambahkan.
            </div>
          <?php else: ?>
            <?php foreach ($items as $item): ?>
              <div class="item-row">
                <div class="item-row-head">
                  <span class="item-row-number">Poin</span>
                  <button type="button" class="item-remove" onclick="removeItemRow(this)">
                    <i class="fa-solid fa-trash-can"></i> Hapus
                  </button>
                </div>
                <input type="hidden" name="item_id[]" value="<?= (int) $item->id ?>">
                <div class="form-group">
                  <label class="form-label">Nama Poin <span class="required">*</span></label>
                  <input type="text" name="item_label[]" class="form-control" value="<?= html_escape($item->label) ?>" placeholder="Contoh: Laporan Akhir">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                  <label class="form-label">Instruksi Khusus</label>
                  <textarea name="item_instruksi[]" class="form-control" rows="2" placeholder="Contoh: Unggah laporan dalam format PDF."><?= html_escape((string) $item->instruksi) ?></textarea>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <button type="button" class="item-add-btn" onclick="addItemRow()">
          <i class="fa-solid fa-plus"></i>
          <span>Tambah Poin</span>
        </button>
      </div>

      <div class="form-card">
        <div class="section-head">
          <div class="step-badge">4</div>
          <div>
            <div class="section-title">Jadwal, Publikasi &amp; Lampiran</div>
            <div class="section-sub">Atur tenggat waktu, status publikasi, dan berkas pendukung tugas.</div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Batas Waktu Pengumpulan <span class="required">*</span></label>
            <?php
              $deadline_val = '';
              if ($is_edit && !empty($tugas->deadline)) {
                $deadline_val = date('Y-m-d\TH:i', strtotime($tugas->deadline));
              }
            ?>
            <input type="datetime-local" name="deadline" class="form-control" value="<?= set_value('deadline', $deadline_val) ?>" required>
            <?= form_error('deadline', '<div class="form-text" style="color: #E11D48;">', '</div>') ?>
          </div>
          <div class="form-group">
            <label class="form-label">Lampiran Berkas / Template</label>
            <?php if ($is_edit && !empty($tugas->file_tugas)): ?>
              <div class="readonly-chip" style="margin-bottom: 4px; display: flex; justify-content: space-between; width: 100%;">
                <a href="<?= base_url('uploads/tugas/' . $tugas->file_tugas) ?>" target="_blank" style="color: inherit;"><?= html_escape($tugas->file_tugas) ?></a>
              </div>
            <?php endif; ?>
            <?php if ($is_edit && !empty($lampiran)): ?>
              <div style="margin-bottom: 8px;">
                <?php foreach ($lampiran as $l): ?>
                  <div class="readonly-chip" style="margin-bottom: 4px; display: flex; justify-content: space-between; width: 100%;">
                    <span><?= html_escape($l->nama_asli) ?></span>
                    <button type="button" class="btn btn-sm" onclick="hapusLampiran(<?= $l->id ?>)" style="color: #BE123C; padding: 0 5px;"><i class="fa-solid fa-trash"></i></button>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
            <input type="file" name="lampiran[]" class="form-control" style="height: auto; padding: 9px;" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar,.7z,.png,.jpg,.jpeg,.webp" multiple>
            <div class="form-text">Format PDF, DOC/DOCX, PPT/PPTX, XLS/XLSX, ZIP/RAR/7Z, atau gambar. Bisa unggah banyak berkas sekaligus. Maksimal 20MB per berkas.</div>
          </div>
        </div>

        <?php
          $status_val = set_value('status', $is_edit ? (!empty($tugas->status) ? $tugas->status : 'published') : 'published');
          $publish_val = '';
          if ($is_edit && !empty($tugas->publish_at)) {
            $publish_val = date('Y-m-d\TH:i', strtotime($tugas->publish_at));
          }
          $publish_val = set_value('publish_at', $publish_val);
        ?>
        <div class="form-row" style="margin-top: 16px; align-items: start;">
          <div class="form-group">
            <label class="form-label">Status Tugas <span class="required">*</span></label>
            <select name="status" id="status_tugas" class="form-control" onchange="togglePublishAt()">
              <option value="draft" <?= $status_val === 'draft' ? 'selected' : '' ?>>Draft (belum terlihat siswa)</option>
              <option value="published" <?= $status_val === 'published' ? 'selected' : '' ?>>Publish sekarang</option>
              <option value="scheduled" <?= $status_val === 'scheduled' ? 'selected' : '' ?>>Publish pada tanggal</option>
            </select>
            <div class="form-text">Draft hanya terlihat oleh guru. Status publish akan tampil di dashboard siswa.</div>
            <?= form_error('status', '<div class="form-text" style="color: #E11D48;">', '</div>') ?>
          </div>
          <div class="form-group" id="publish_at_group" style="display: none;">
            <label class="form-label">Tanggal &amp; Waktu Publikasi <span class="required">*</span></label>
            <input type="datetime-local" name="publish_at" id="publish_at" class="form-control" value="<?= $publish_val ?>">
            <div class="form-text">Tugas otomatis muncul di dashboard siswa setelah waktu ini.</div>
            <?= form_error('publish_at', '<div class="form-text" style="color: #E11D48;">', '</div>') ?>
          </div>
        </div>
      </div>
    </div>

    <div>
      <script>
        function hapusLampiran(id) {
            if (confirm('Hapus lampiran ini?')) {
                window.location.href = '<?= site_url('guru/hapus_lampiran/') ?>' + id;
            }
        }
      </script>

      <div class="side-card">
        <div class="side-title"><i class="fa-solid fa-clipboard-list"></i> Ringkasan Tugas</div>
        <div class="summary-list">
          <div class="summary-item">
            <span class="summary-label">Status</span>
            <span class="badge badge-pending" id="summary_status" style="font-size: 0.68rem;"><?= $status_val === 'draft' ? 'Draft' : ($status_val === 'scheduled' ? 'Terjadwal' : 'Publish') ?></span>
          </div>
          <div class="summary-item">
            <span class="summary-label">Mapel</span>
            <span class="summary-value"><?= html_escape($mapel->nama_mapel) ?></span>
          </div>
          <div class="summary-item">
            <span class="summary-label">Tipe Penugasan</span>
            <span class="summary-value" id="summary_jenis">
              <?= $is_edit ? ucfirst($tugas->jenis) : 'Individu' ?>
            </span>
          </div>
          <div class="summary-item">
            <span class="summary-label">Format Berkas</span>
            <span class="summary-value">PDF, DOCX, ZIP</span>
          </div>
        </div>
      </div>

      <div class="side-card">
        <div class="side-title"><i class="fa-solid fa-scale-balanced"></i> Panduan Penilaian</div>
        <div class="rubric-item">
          <div class="rubric-top"><span style="color: var(--text-main);">Pemahaman Konsep</span><span>30%</span></div>
          <div class="rubric-text">Ketepatan konsep, kelengkapan analisis, dan relevansi jawaban.</div>
          <div class="rubric-bar"><span style="width: 30%;"></span></div>
        </div>
        <div class="rubric-item">
          <div class="rubric-top"><span style="color: var(--text-main);">Kualitas Pengerjaan</span><span>40%</span></div>
          <div class="rubric-text">Struktur laporan, kerapian, dan kelengkapan dokumentasi.</div>
          <div class="rubric-bar"><span style="width: 40%;"></span></div>
        </div>
        <div class="rubric-item" style="margin-bottom: 0;">
          <div class="rubric-top"><span style="color: var(--text-main);">Ketepatan Waktu</span><span>30%</span></div>
          <div class="rubric-text">Konsistensi pengumpulan sesuai tenggat yang ditentukan.</div>
          <div class="rubric-bar"><span style="width: 30%;"></span></div>
        </div>
      </div>

      <div class="form-actions">
        <a href="<?= $back_url ?>" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-floppy-disk"></i>
          <span><?= $is_edit ? 'Perbarui Tugas' : 'Simpan Tugas' ?></span>
        </button>
      </div>
    </div>
  </div>
<?= form_close() ?>

<script>
  function addItemRow() {
    var list = document.getElementById('tugas-item-list');
    var empty = document.getElementById('item-empty-state');
    if (empty) empty.remove();

    var row = document.createElement('div');
    row.className = 'item-row';
    row.innerHTML =
      '<div class="item-row-head">' +
        '<span class="item-row-number">Poin</span>' +
        '<button type="button" class="item-remove" onclick="removeItemRow(this)"><i class="fa-solid fa-trash-can"></i> Hapus</button>' +
      '</div>' +
      '<input type="hidden" name="item_id[]" value="0">' +
      '<div class="form-group">' +
        '<label class="form-label">Nama Poin <span class="required">*</span></label>' +
        '<input type="text" name="item_label[]" class="form-control" placeholder="Contoh: Laporan Akhir">' +
      '</div>' +
      '<div class="form-group" style="margin-bottom: 0;">' +
        '<label class="form-label">Instruksi Khusus</label>' +
        '<textarea name="item_instruksi[]" class="form-control" rows="2" placeholder="Contoh: Unggah laporan dalam format PDF."></textarea>' +
      '</div>';

    list.appendChild(row);
  }

  function removeItemRow(button) {
    var row = button.closest('.item-row');
    if (row) row.remove();

    var list = document.getElementById('tugas-item-list');
    if (list && !list.querySelector('.item-row')) {
      list.innerHTML = '<div class="item-empty" id="item-empty-state"><i class="fa-regular fa-folder-open"></i> Belum ada poin. Klik tombol di bawah untuk menambahkan.</div>';
    }
  }

  function togglePublishAt() {
    var select = document.getElementById('status_tugas');
    var group = document.getElementById('publish_at_group');
    var input = document.getElementById('publish_at');
    if (!select || !group || !input) return;

    var status = select.value;
    group.style.display = status === 'scheduled' ? 'block' : 'none';
    input.required = status === 'scheduled';

    var summary = document.getElementById('summary_status');
    if (summary) {
      var labels = { draft: 'Draft', published: 'Publish', scheduled: 'Terjadwal' };
      summary.textContent = labels[status] || 'Draft';
      summary.className = 'badge ' + (status === 'draft' ? 'badge-pending' : (status === 'scheduled' ? 'badge-individu' : 'badge-graded'));
    }
  }

  document.addEventListener('DOMContentLoaded', togglePublishAt);
</script>
