<div style="margin-bottom: 24px;">
  <a href="<?= site_url('guru/detail_mapel/' . $mapel->id) ?>" style="display: inline-flex; align-items: center; gap: 8px; color: var(--text-muted); font-size: 0.88rem; font-weight: 500; margin-bottom: 12px;">
    <i class="fa-solid fa-arrow-left"></i>
    <span>Kembali ke Mapel <?= html_escape($mapel->nama_mapel) ?></span>
  </a>

  <!-- TUGAS INFO CARD -->
  <div class="card" style="margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
      <div>
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px; flex-wrap: wrap;">
          <?php if ($tugas->jenis === 'kelompok'): ?>
            <span class="badge badge-kelompok">Tugas Kelompok</span>
            <?php if (!empty($tugas->mode_kelompok)): ?>
              <span class="badge" style="background: #EFF4FF; color: var(--primary-dark);">
                <i class="fa-solid fa-users-gear" style="margin-right: 4px;"></i>
                <?= html_escape(ucfirst($tugas->mode_kelompok)) ?>
              </span>
            <?php endif; ?>
          <?php else: ?>
            <span class="badge badge-individu">Tugas Individu</span>
          <?php endif; ?>
          <?php if (!empty($tugas->status) && $tugas->status === 'draft'): ?>
            <span class="badge badge-pending"><i class="fa-solid fa-eye-slash" style="margin-right: 4px;"></i> Draft &mdash; belum terlihat siswa</span>
          <?php elseif (!empty($tugas->status) && $tugas->status === 'scheduled'): ?>
            <span class="badge badge-individu"><i class="fa-regular fa-clock" style="margin-right: 4px;"></i> Terjadwal: <?= !empty($tugas->publish_at) ? date('d M Y, H:i', strtotime($tugas->publish_at)) : '-' ?></span>
          <?php else: ?>
            <span class="badge badge-graded"><i class="fa-solid fa-bullhorn" style="margin-right: 4px;"></i> Publish</span>
          <?php endif; ?>
          <span class="badge" style="background: #F1F5F9; color: #475569;">
            <i class="fa-regular fa-clock" style="margin-right: 4px;"></i>
            Batas: <?= date('d M Y, H:i', strtotime($tugas->deadline)) ?>
          </span>
        </div>
        <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 10px;"><?= html_escape($tugas->judul) ?></h2>
        <div style="color: #475569; font-size: 0.95rem; line-height: 1.6; max-width: 800px;">
          <?= !empty($tugas->deskripsi) ? nl2br(html_escape($tugas->deskripsi)) : '<em>Tidak ada petunjuk khusus.</em>' ?>
        </div>

        <?php if (!empty($tugas->file_tugas) || !empty($lampiran)): ?>
          <div style="margin-top: 16px; display: flex; flex-direction: column; gap: 6px; align-items: flex-start;">
            <?php if (!empty($tugas->file_tugas)): ?>
              <a href="<?= base_url('uploads/tugas/' . $tugas->file_tugas) ?>" target="_blank" class="btn btn-secondary btn-sm">
                <i class="fa-solid fa-file-arrow-down"></i>
                <span><?= html_escape($tugas->file_tugas) ?></span>
              </a>
            <?php endif; ?>
            <?php foreach ($lampiran as $l): ?>
              <a href="<?= base_url('uploads/tugas/' . $l->nama_file) ?>" target="_blank" class="btn btn-secondary btn-sm">
                <i class="fa-solid fa-file-arrow-down"></i>
                <span><?= html_escape($l->nama_asli) ?></span>
              </a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <?php if ($tugas->jenis === 'individu'): ?>
          <div style="margin-top: 14px; font-size: 0.84rem; color: #475569;">
            <i class="fa-solid fa-user-check" style="color: var(--primary);"></i>
            <strong>Penerima:</strong>
            <?php if (empty($penerima)): ?>
              Semua siswa di mapel ini
            <?php else: ?>
              <?= count($penerima) ?> siswa
              <span style="color: var(--text-muted);">
                (<?= html_escape(implode(', ', array_map(function ($p) { return $p->nama; }, $penerima))) ?>)
              </span>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>

      <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        <?php if ($tugas->jenis === 'kelompok'): ?>
          <a href="<?= site_url('guru/kelola_kelompok/' . $tugas->id) ?>" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-users-gear"></i>
            <span>Kelola Kelompok</span>
          </a>
        <?php endif; ?>
        <a href="<?= site_url('guru/edit_tugas/' . $tugas->id) ?>" class="btn btn-secondary btn-sm">
          <i class="fa-solid fa-pen"></i>
          <span>Edit Tugas</span>
        </a>
      </div>
    </div>
  </div>

  <?php if (!empty($items)): ?>
    <div class="card" style="margin-bottom: 24px;">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fa-solid fa-list-check" style="color: var(--primary); margin-right: 8px;"></i>
          Poin Berkas yang Diminta (<?= count($items) ?>)
        </h3>
      </div>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 10px;">
        <?php foreach ($items as $item): ?>
          <div style="border: 1px solid var(--card-border); border-radius: 10px; padding: 12px 14px; background: #F8FAFC;">
            <div style="font-size: 0.86rem; font-weight: 700; color: var(--text-main);">
              <?= html_escape($item->label) ?>
            </div>
            <?php if (!empty($item->instruksi)): ?>
              <div style="font-size: 0.76rem; color: var(--text-muted); line-height: 1.45; margin-top: 4px;">
                <?= nl2br(html_escape($item->instruksi)) ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>

  <!-- SUBMISSIONS / GROUPS LIST -->
  <div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
      <div>
        <h3 class="card-title">
          <?php if ($tugas->jenis === 'kelompok'): ?>
            Daftar Kelompok (<?= count($groups) ?>)
          <?php else: ?>
            Daftar Pengumpulan Siswa (<?= (int) $total_siswa ?>)
          <?php endif; ?>
        </h3>
        <?php if ($tugas->jenis === 'individu'): ?>
          <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 4px;">
            Sudah mengumpulkan: <strong style="color: #059669;"><?= (int) $submitted_count ?></strong>
            &middot; Belum mengumpulkan: <strong style="color: #B91C1C;"><?= (int) $not_submitted_count ?></strong>
          </div>
        <?php endif; ?>
      </div>
      <?php if ($tugas->jenis === 'individu'): ?>
        <div style="display: flex; align-items: center; gap: 8px;">
          <label for="filter-pengumpulan" style="font-size: 0.82rem; color: var(--text-muted); white-space: nowrap;">Filter:</label>
          <select id="filter-pengumpulan" class="form-control" style="width: auto; min-width: 180px; padding: 7px 10px; font-size: 0.84rem;" onchange="filterPengumpulan(this.value)">
            <option value="all">Semua Siswa</option>
            <option value="submitted">Sudah Mengumpulkan</option>
            <option value="not_submitted">Belum Mengumpulkan</option>
          </select>
        </div>
      <?php endif; ?>
    </div>

    <?php if ($tugas->jenis === 'kelompok'): ?>
      <?php if (empty($groups)): ?>
        <div class="empty-state">
          <i class="fa-solid fa-users"></i>
          <h4>Belum Ada Kelompok</h4>
          <p>Buka halaman Kelola Kelompok untuk membentuk kelompok.</p>
        </div>
      <?php else: ?>
        <?= form_open('guru/bulk_hapus_pengumpulan/' . $tugas->id, ['id' => 'bulk-form-kelompok']) ?>
        <div id="bulk-actions-kelompok" class="card" style="display: none; margin-bottom: 16px; padding: 12px 20px; border-left: 4px solid #B91C1C; display: flex; align-items: center; justify-content: space-between;">
            <div style="font-weight: 600; color: #991B1B;">
                <span id="selected-count-kelompok">0</span> kelompok dipilih
            </div>
            <button type="submit" class="btn btn-secondary btn-sm" style="color: #B91C1C; border-color: #FECACA; background: #FEF2F2;" onclick="return confirm('Hapus pengumpulan kelompok terpilih? Semua berkas anggota akan dihapus.');">
                <i class="fa-solid fa-trash"></i> Hapus Terpilih
            </button>
        </div>
        <div class="table-container">
          <table class="table">
            <thead>
              <tr>
                <th style="width: 40px;"><input type="checkbox" id="check-all-kelompok"></th>
                <th>Kelompok</th>
                <th>Anggota</th>
                <th>Dikumpulkan Oleh</th>
                <th>Waktu</th>
                <th>Berkas</th>
                <th>Status</th>
                <th>Nilai</th>
                <th style="text-align: right; width: 180px;">Penilaian</th>
              </tr>
            </thead>
            <tbody>
               <?php foreach ($groups as $g): ?>
                <?php $sub = isset($sub_by_group[(int) $g->id]) ? $sub_by_group[(int) $g->id] : NULL; ?>
                <tr>
                  <td style="vertical-align: middle;">
                    <?php if ($sub): ?>
                      <input type="checkbox" name="pengumpulan_ids[]" value="<?= $sub->id ?>" class="check-item-kelompok">
                    <?php endif; ?>
                  </td>
                  <td>
                    <div style="font-weight: 700; color: var(--text-main);"><?= html_escape($g->nama_kelompok) ?></div>
                    <div style="font-size: 0.76rem; color: var(--text-muted);"><?= count($g->members) ?> anggota</div>
                  </td>
                  <td>
                    <?php if (empty($g->members)): ?>
                      <span style="color: var(--text-muted); font-size: 0.82rem;">-</span>
                    <?php else: ?>
                      <div style="display: flex; flex-direction: column; gap: 2px;">
                        <?php foreach ($g->members as $m): ?>
                          <span style="font-size: 0.82rem; color: #334155;"><?= html_escape($m->nama) ?></span>
                        <?php endforeach; ?>
                      </div>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if ($sub): ?>
                      <span style="font-size: 0.84rem; color: #334155;"><?= html_escape($sub->nama_siswa) ?></span>
                    <?php else: ?>
                      <span style="color: var(--text-muted); font-size: 0.82rem;">-</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if ($sub): ?>
                      <span style="font-size: 0.82rem; color: var(--text-muted);"><?= date('d M Y, H:i', strtotime($sub->submitted_at)) ?></span>
                    <?php else: ?>
                      <span style="color: var(--text-muted); font-size: 0.82rem;">-</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php
                      $sub_files = ($sub && isset($files_by_pengumpulan[(int) $sub->id]))
                        ? $files_by_pengumpulan[(int) $sub->id]
                        : array();
                    ?>
                    <?php if (!empty($sub_files)): ?>
                      <div style="display: flex; flex-direction: column; gap: 6px;">
                        <?php foreach ($sub_files as $file): ?>
                          <a href="<?= base_url('uploads/jawaban/' . $file->nama_file) ?>" target="_blank" style="font-size: 0.78rem; font-weight: 600; color: var(--primary-dark);">
                            <i class="fa-solid fa-file-arrow-down"></i>
                            <?= html_escape(!empty($file->nama_asli) ? $file->nama_asli : ($file->label ?: 'Berkas')) ?>
                          </a>
                        <?php endforeach; ?>
                      </div>
                    <?php elseif ($sub && !empty($sub->file_jawaban)): ?>
                      <a href="<?= base_url('uploads/jawaban/' . $sub->file_jawaban) ?>" target="_blank" class="btn btn-secondary btn-sm" style="font-size: 0.8rem; padding: 5px 10px;">
                        <i class="fa-solid fa-download"></i>
                        <span>Unduh</span>
                      </a>
                    <?php else: ?>
                      <span style="color: var(--text-muted); font-size: 0.82rem;">-</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if ( ! $sub): ?>
                      <span class="badge badge-pending"><i class="fa-regular fa-clock"></i> Belum Kumpul</span>
                    <?php elseif ($sub->status === 'revisi'): ?>
                      <span class="badge" style="background: #FFF7ED; color: #C2410C;"><i class="fa-solid fa-rotate"></i> Perlu Revisi</span>
                    <?php elseif ($sub->status === 'menunggu'): ?>
                      <span class="badge badge-pending">Menunggu Evaluasi</span>
                    <?php elseif ($sub->status === 'terlambat'): ?>
                      <span class="badge badge-overdue">Terlambat</span>
                    <?php else: ?>
                      <span class="badge badge-graded">Dinilai</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if ($sub && $sub->nilai !== NULL): ?>
                      <div style="font-size: 1.15rem; font-weight: 800; color: var(--primary);">
                        <?= number_format($sub->nilai, 0) ?>
                      </div>
                    <?php else: ?>
                      <span style="color: var(--text-muted); font-size: 0.85rem;">-</span>
                    <?php endif; ?>
                    <?php if ($sub && $sub->nilai_sebelumnya !== NULL): ?>
                      <div style="font-size: 0.72rem; color: var(--text-muted);">
                        Sebelumnya: <?= number_format($sub->nilai_sebelumnya, 0) ?>
                      </div>
                    <?php endif; ?>
                  </td>
                  <td style="text-align: right;">
                    <?php if ($sub): ?>
                      <button type="button" class="btn btn-secondary btn-sm" onclick="toggleNilaiForm(<?= $sub->id ?>)">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span><?= ($sub->nilai !== NULL) ? 'Ubah Nilai' : 'Beri Nilai' ?></span>
                      </button>
                    <?php else: ?>
                      <span style="color: var(--text-muted); font-size: 0.82rem;">-</span>
                    <?php endif; ?>
                  </td>
                </tr>

                <?php if ($sub): ?>
                  <tr id="grading-form-<?= $sub->id ?>" style="display: none; background-color: #F8FAFC;">
                    <td colspan="8" style="padding: 16px 20px;">
                      <div style="max-width: 500px; margin-left: auto;">
                        <?= form_open('guru/beri_nilai/' . $sub->id) ?>
                          <div style="display: flex; gap: 12px; margin-bottom: 10px;">
                            <div style="width: 120px;">
                              <label class="form-label" style="font-size: 0.78rem;">Nilai (0-100) <span style="color: #EF4444;">*</span></label>
                              <input type="number" name="nilai" class="form-control" min="0" max="100" step="0.1" value="<?= set_value('nilai', $sub->nilai) ?>" required placeholder="85">
                            </div>
                            <div style="flex: 1;">
                              <label class="form-label" style="font-size: 0.78rem;">Feedback / Masukan Guru</label>
                              <input type="text" name="feedback" class="form-control" value="<?= set_value('feedback', $sub->feedback) ?>" placeholder="Catatan evaluasi untuk kelompok...">
                            </div>
                          </div>
                          <div style="display: flex; justify-content: flex-end; gap: 8px;">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="toggleNilaiForm(<?= $sub->id ?>)">Batal</button>
                            <button type="submit" class="btn btn-primary btn-sm">
                              <i class="fa-solid fa-check"></i>
                              <span>Simpan Nilai</span>
                            </button>
                          </div>
                        <?= form_close() ?>
                      </div>
                    </td>
                  </tr>
                <?php endif; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?= form_close() ?>
      <?php endif; ?>

    <?php else: ?>
      <?php if (!empty($submission_rows)): ?>
        <?= form_open('guru/bulk_hapus_pengumpulan/' . $tugas->id, ['id' => 'bulk-form']) ?>
        <div id="bulk-actions" class="card" style="display: none; margin-bottom: 16px; padding: 12px 20px; border-left: 4px solid #B91C1C; display: flex; align-items: center; justify-content: space-between;">
            <div style="font-weight: 600; color: #991B1B;">
                <span id="selected-count">0</span> siswa dipilih
            </div>
            <button type="submit" class="btn btn-secondary btn-sm" style="color: #B91C1C; border-color: #FECACA; background: #FEF2F2;" onclick="return confirm('Hapus pengumpulan terpilih? Tindakan ini tidak dapat dibatalkan.');">
                <i class="fa-solid fa-trash"></i> Hapus Terpilih
            </button>
        </div>
        <div class="table-container">
          <table class="table" id="submission-table">
            <thead>
              <tr>
                <th style="width: 40px;"><input type="checkbox" id="check-all"></th>
                <th>Siswa</th>
                <th>Waktu Pengumpulan</th>
                <th>Berkas / Jawaban</th>
                <th>Catatan Siswa</th>
                <th>Status</th>
                <th>Nilai</th>
                <th style="text-align: right; width: 220px;">Penilaian</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($submission_rows as $sub): ?>
                <tr data-submitted="<?= $sub->is_submitted ? '1' : '0' ?>" data-siswa-id="<?= (int) $sub->siswa_id ?>">
                  <td style="vertical-align: middle;">
                    <?php if ($sub->is_submitted): ?>
                      <input type="checkbox" name="pengumpulan_ids[]" value="<?= $sub->id ?>" class="check-item">
                    <?php endif; ?>
                  </td>
                  <td>
                    <div style="font-weight: 600; color: var(--text-main);"><?= html_escape($sub->nama_siswa) ?></div>
                    <div style="font-size: 0.78rem; color: var(--text-muted);"><?= html_escape($sub->email_siswa) ?></div>
                  </td>
                  <td>
                    <span style="font-size: 0.85rem; color: var(--text-muted);">
                      <?= !empty($sub->submitted_at) ? date('d M Y, H:i', strtotime($sub->submitted_at)) : '-' ?>
                    </span>
                  </td>
                  <td>
                    <?php
                      $sub_files = isset($files_by_pengumpulan[(int) $sub->id])
                        ? $files_by_pengumpulan[(int) $sub->id]
                        : array();
                    ?>
                    <?php if (!empty($sub_files)): ?>
                      <div style="display: flex; flex-direction: column; gap: 6px;">
                        <?php foreach ($sub_files as $file): ?>
                          <a href="<?= base_url('uploads/jawaban/' . $file->nama_file) ?>" target="_blank" style="font-size: 0.78rem; font-weight: 600; color: var(--primary-dark);">
                            <i class="fa-solid fa-file-arrow-down"></i>
                            <?= html_escape(!empty($file->nama_asli) ? $file->nama_asli : ($file->label ?: 'Berkas')) ?>
                          </a>
                        <?php endforeach; ?>
                      </div>
                    <?php elseif (!empty($sub->file_jawaban)): ?>
                      <a href="<?= base_url('uploads/jawaban/' . $sub->file_jawaban) ?>" target="_blank" class="btn btn-secondary btn-sm" style="font-size: 0.8rem; padding: 5px 10px;">
                        <i class="fa-solid fa-download"></i>
                        <span>Unduh Berkas</span>
                      </a>
                    <?php else: ?>
                      <span style="color: var(--text-muted); font-size: 0.82rem;"><?= $sub->is_submitted ? 'Tidak ada file' : 'Belum ada berkas' ?></span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <span style="font-size: 0.85rem; color: #475569;">
                      <?= !empty($sub->catatan) ? html_escape(character_limiter($sub->catatan, 50)) : '-' ?>
                    </span>
                  </td>
                  <td>
                    <?php if ( ! $sub->is_submitted): ?>
                      <span class="badge" style="background: #FEF2F2; color: #B91C1C;"><i class="fa-regular fa-circle-xmark"></i> Belum Mengumpulkan</span>
                    <?php elseif ($sub->status === 'revisi'): ?>
                      <span class="badge" style="background: #FFF7ED; color: #C2410C;"><i class="fa-solid fa-rotate"></i> Perlu Revisi</span>
                    <?php elseif ($sub->status === 'menunggu'): ?>
                      <span class="badge badge-pending">Menunggu Evaluasi</span>
                    <?php elseif ($sub->status === 'terlambat'): ?>
                      <span class="badge badge-overdue">Terlambat</span>
                    <?php else: ?>
                      <span class="badge badge-graded">Dinilai</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if ($sub->nilai !== NULL): ?>
                      <div style="font-size: 1.15rem; font-weight: 800; color: var(--primary);">
                        <?= number_format($sub->nilai, 0) ?>
                      </div>
                    <?php else: ?>
                      <span style="color: var(--text-muted); font-size: 0.85rem;">-</span>
                    <?php endif; ?>
                    <?php if ($sub->nilai_sebelumnya !== NULL): ?>
                      <div style="font-size: 0.72rem; color: var(--text-muted);">
                        Sebelumnya: <?= number_format($sub->nilai_sebelumnya, 0) ?>
                      </div>
                    <?php endif; ?>
                  </td>
                  <td style="text-align: right;">
                    <?php if ( ! $sub->is_submitted): ?>
                      <span style="color: var(--text-muted); font-size: 0.82rem;">-</span>
                    <?php else: ?>
                      <div style="display: flex; flex-direction: column; gap: 6px; align-items: flex-end;">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleNilaiForm(<?= $sub->id ?>)">
                          <i class="fa-solid fa-pen-to-square"></i>
                          <span><?= ($sub->nilai !== NULL) ? 'Ubah Nilai' : 'Beri Nilai' ?></span>
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleRevisiForm(<?= $sub->id ?>)" style="color: #C2410C; border-color: #FED7AA;">
                          <i class="fa-solid fa-rotate-left"></i>
                          <span><?= ($sub->status === 'revisi') ? 'Perbarui Revisi' : 'Minta Revisi' ?></span>
                        </button>
                        <form action="<?= site_url('guru/hapus_pengumpulan/' . $sub->id) ?>" method="post" onsubmit="return confirm('Hapus pengumpulan ini? Semua berkas jawaban siswa juga akan dihapus permanen.');" style="margin: 0;">
                          <button type="submit" class="btn btn-secondary btn-sm" style="color: #B91C1C; border-color: #FECACA; background: #FEF2F2;">
                            <i class="fa-solid fa-trash"></i>
                            <span>Hapus</span>
                          </button>
                        </form>
                      </div>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php if ($sub->is_submitted): ?>
                  <!-- INLINE GRADING ROW (COLLAPSIBLE) -->
                  <tr id="grading-form-<?= $sub->id ?>" class="submission-extra-row" style="display: none; background-color: #F8FAFC;">
                  <td colspan="7" style="padding: 16px 20px;">
                    <div style="max-width: 500px; margin-left: auto;">
                      <?= form_open('guru/beri_nilai/' . $sub->id) ?>
                        <div style="display: flex; gap: 12px; margin-bottom: 10px;">
                          <div style="width: 120px;">
                            <label class="form-label" style="font-size: 0.78rem;">Nilai (0-100) <span style="color: #EF4444;">*</span></label>
                            <input type="number" name="nilai" class="form-control" min="0" max="100" step="0.1" value="<?= set_value('nilai', $sub->nilai) ?>" required placeholder="85">
                          </div>
                          <div style="flex: 1;">
                            <label class="form-label" style="font-size: 0.78rem;">Feedback / Masukan Guru</label>
                            <input type="text" name="feedback" class="form-control" value="<?= set_value('feedback', $sub->feedback) ?>" placeholder="Catatan evaluasi untuk siswa...">
                          </div>
                        </div>
                        <div style="display: flex; justify-content: flex-end; gap: 8px;">
                          <button type="button" class="btn btn-secondary btn-sm" onclick="toggleNilaiForm(<?= $sub->id ?>)">Batal</button>
                          <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-check"></i>
                            <span>Simpan Nilai</span>
                          </button>
                        </div>
                      <?= form_close() ?>
                    </div>
                  </td>
                </tr>
                <tr id="revisi-form-<?= $sub->id ?>" class="submission-extra-row" style="display: none; background-color: #FFF7ED;">
                  <td colspan="7" style="padding: 16px 20px;">
                    <div style="max-width: 520px; margin-left: auto;">
                      <?= form_open('guru/minta_revisi/' . $sub->id) ?>
                        <div class="form-group" style="margin-bottom: 10px;">
                          <label class="form-label" style="font-size: 0.78rem;">Instruksi Revisi <span style="color: #EF4444;">*</span></label>
                          <textarea name="feedback" class="form-control" style="min-height: 70px;" required placeholder="Jelaskan bagian yang harus diperbaiki siswa..."><?= set_value('feedback', ($sub->status === 'revisi') ? $sub->feedback : '') ?></textarea>
                          <div class="form-text">Siswa dapat mengunggah ulang setelah permintaan ini dikirim.</div>
                        </div>
                        <div style="display: flex; justify-content: flex-end; gap: 8px;">
                          <button type="button" class="btn btn-secondary btn-sm" onclick="toggleRevisiForm(<?= $sub->id ?>)">Batal</button>
                          <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>Kirim Permintaan</span>
                          </button>
                        </div>
                      <?= form_close() ?>
                    </div>
                  </td>
                </tr>
                <?php endif; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?= form_close() ?>
        <div id="submission-filter-empty" style="display: none; padding: 20px; text-align: center; color: var(--text-muted); font-size: 0.86rem;">
          Tidak ada siswa pada filter ini.
        </div>
      <?php else: ?>
        <div class="empty-state">
          <i class="fa-solid fa-inbox"></i>
          <h4>Belum Ada Siswa</h4>
          <p>Belum ada siswa yang menjadi penerima tugas ini.</p>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>

<script>
  function toggleNilaiForm(id) {
    var row = document.getElementById('grading-form-' + id);
    if (row.style.display === 'none' || row.style.display === '') {
      row.style.display = 'table-row';
    } else {
      row.style.display = 'none';
    }
  }

  function toggleRevisiForm(id) {
    var row = document.getElementById('revisi-form-' + id);
    if (row.style.display === 'none' || row.style.display === '') {
      row.style.display = 'table-row';
    } else {
      row.style.display = 'none';
    }
  }

  function filterPengumpulan(value) {
    var rows = document.querySelectorAll('#submission-table tbody tr[data-submitted]');
    var shown = 0;

    for (var i = 0; i < rows.length; i++) {
      var row = rows[i];
      var submitted = row.getAttribute('data-submitted') === '1';
      var match = value === 'all'
        || (value === 'submitted' && submitted)
        || (value === 'not_submitted' && !submitted);

      row.style.display = match ? '' : 'none';
      if (match) shown++;
    }

    var extras = document.querySelectorAll('#submission-table .submission-extra-row');
    for (var j = 0; j < extras.length; j++) {
      extras[j].style.display = 'none';
    }

    var empty = document.getElementById('submission-filter-empty');
    if (empty) {
      empty.style.display = shown === 0 ? 'block' : 'none';
    }
  }

  // BULK ACTIONS
  const checkAll = document.getElementById('check-all');
  const checkItems = document.querySelectorAll('.check-item');
  const bulkActions = document.getElementById('bulk-actions');
  const selectedCount = document.getElementById('selected-count');

  if (checkAll) {
    checkAll.addEventListener('change', function() {
      const isChecked = this.checked;
      document.querySelectorAll('.check-item').forEach(cb => {
        if (cb.offsetParent !== null) { // Hanya yang terlihat (karena filter)
          cb.checked = isChecked;
        }
      });
      toggleBulkButton();
    });
  }

  document.querySelectorAll('.check-item').forEach(cb => {
    cb.addEventListener('change', toggleBulkButton);
  });

  function toggleBulkButton() {
    const checkedCount = document.querySelectorAll('.check-item:checked').length;
    if (bulkActions) {
      bulkActions.style.display = checkedCount > 0 ? 'flex' : 'none';
    }
    if (selectedCount) {
      selectedCount.innerText = checkedCount;
    }
  }
  // BULK ACTIONS KELOMPOK
  const checkAllKelompok = document.getElementById('check-all-kelompok');
  const bulkActionsKelompok = document.getElementById('bulk-actions-kelompok');
  const selectedCountKelompok = document.getElementById('selected-count-kelompok');

  if (checkAllKelompok) {
    checkAllKelompok.addEventListener('change', function() {
      const isChecked = this.checked;
      document.querySelectorAll('.check-item-kelompok').forEach(cb => {
        cb.checked = isChecked;
      });
      toggleBulkButtonKelompok();
    });
  }

  document.querySelectorAll('.check-item-kelompok').forEach(cb => {
    cb.addEventListener('change', toggleBulkButtonKelompok);
  });

  function toggleBulkButtonKelompok() {
    const checkedCount = document.querySelectorAll('.check-item-kelompok:checked').length;
    if (bulkActionsKelompok) {
      bulkActionsKelompok.style.display = checkedCount > 0 ? 'flex' : 'none';
    }
    if (selectedCountKelompok) {
      selectedCountKelompok.innerText = checkedCount;
    }
  }
</script>
