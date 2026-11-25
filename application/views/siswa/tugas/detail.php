<div style="margin-bottom: 20px;">
  <a href="<?= site_url('siswa/tugas') ?>" class="btn btn-secondary btn-sm">
    <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Tugas
  </a>
</div>

<div style="display: grid; grid-template-columns: 3fr 2fr; gap: 24px; align-items: start;">
  <!-- Left Side: Task Info & Description -->
  <div>
    <div class="card">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 16px;">
        <span class="badge <?= $tugas->jenis === 'kelompok' ? 'badge-kelompok' : 'badge-individu' ?>">
          <i class="fa-solid <?= $tugas->jenis === 'kelompok' ? 'fa-people-group' : 'fa-user' ?>"></i>
          Tugas <?= ucfirst($tugas->jenis) ?>
        </span>

        <span style="font-size: 0.82rem; color: var(--text-muted);">
          <i class="fa-solid fa-book-open"></i> <?= html_escape($tugas->nama_mapel) ?> · <?= html_escape($tugas->nama_kelas) ?>
        </span>
      </div>

      <h2 style="font-size: 1.45rem; font-weight: 800; color: #0F172A; margin-bottom: 12px;">
        <?= html_escape($tugas->judul) ?>
      </h2>

      <div style="display: flex; gap: 18px; margin-bottom: 24px; font-size: 0.85rem; color: var(--text-muted); border-bottom: 1px solid var(--card-border); padding-bottom: 16px;">
        <div>
          <i class="fa-solid fa-user-tie" style="color: var(--primary);"></i> Guru: <strong><?= html_escape($tugas->nama_guru) ?></strong>
        </div>
        <div>
          <i class="fa-regular fa-clock" style="color: #BE123C;"></i> Deadline: 
          <strong><?= date('d M Y, H:i', strtotime($tugas->deadline)) ?> WIB</strong>
        </div>
      </div>

      <!-- Description -->
      <div style="margin-bottom: 24px;">
        <h4 style="font-size: 0.95rem; font-weight: 700; color: #1E293B; margin-bottom: 10px;">Deskripsi / Instruksi:</h4>
        <div style="font-size: 0.92rem; color: #334155; line-height: 1.6; white-space: pre-line; background: #F8FAFC; padding: 18px; border-radius: 12px; border: 1px solid var(--card-border);">
          <?= nl2br(html_escape($tugas->deskripsi)) ?>
        </div>
      </div>

      <!-- Attachments -->
      <?php if (!empty($tugas->file_tugas) || !empty($lampiran)): ?>
        <div style="margin-bottom: 20px;">
          <h4 style="font-size: 0.95rem; font-weight: 700; color: #1E293B; margin-bottom: 10px;">Lampiran Berkas Tugas:</h4>
          <div style="display: flex; flex-direction: column; gap: 8px;">
            <?php if (!empty($tugas->file_tugas)): ?>
              <a href="<?= base_url('uploads/tugas/' . $tugas->file_tugas) ?>" target="_blank" class="btn btn-secondary btn-sm" style="justify-content: flex-start;" download>
                <i class="fa-solid fa-paperclip" style="color: var(--primary);"></i> <?= html_escape($tugas->file_tugas) ?>
              </a>
            <?php endif; ?>
            <?php foreach ($lampiran as $l): ?>
              <a href="<?= base_url('uploads/tugas/' . $l->nama_file) ?>" target="_blank" class="btn btn-secondary btn-sm" style="justify-content: flex-start;" download>
                <i class="fa-solid fa-paperclip" style="color: var(--primary);"></i> <?= html_escape($l->nama_asli) ?>
                <span style="font-size: 0.72rem; color: var(--text-muted);">(<?= number_format($l->ukuran_file, 0) ?> KB)</span>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Right Side: Group Info, Submission Status & Upload Form -->
  <div>
    <?php if ($tugas->jenis === 'kelompok'): ?>
      <!-- GROUP INFO -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fa-solid fa-people-group" style="color: var(--primary-dark); margin-right: 8px;"></i>
            Kelompok
          </h3>
          <?php if ($kelompok): ?>
            <span class="badge badge-kelompok"><?= html_escape($kelompok->nama_kelompok) ?></span>
          <?php else: ?>
            <span class="badge badge-pending">Belum Ada Kelompok</span>
          <?php endif; ?>
        </div>

        <?php if ($kelompok): ?>
          <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px;">
            <?php foreach ($kelompok_members as $m): ?>
              <div style="display: flex; align-items: center; gap: 10px; padding: 8px 10px; background: #F8FAFC; border-radius: 8px;">
                <div style="width: 30px; height: 30px; border-radius: 50%; background: #E0F5FF; color: var(--primary-dark); display: flex; align-items: center; justify-content: center; font-size: 0.78rem; font-weight: 700; flex-shrink: 0;">
                  <?= html_escape(strtoupper(substr($m->nama, 0, 1))) ?>
                </div>
                <div style="min-width: 0;">
                  <div style="font-size: 0.86rem; font-weight: 600; color: #1E293B;"><?= html_escape($m->nama) ?></div>
                  <?php if ((int) $m->id === (int) $this->session->userdata('user_id')): ?>
                    <div style="font-size: 0.72rem; color: var(--primary); font-weight: 600;">Anda</div>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <?php if ($tugas->mode_kelompok === 'siswa' && !$tugas->kelompok_locked && !$pengumpulan): ?>
            <?= form_open('siswa/keluar_kelompok/' . $tugas->id) ?>
              <button type="submit" class="btn btn-secondary btn-sm" style="width: 100%; justify-content: center;" onclick="return confirm('Keluar dari kelompok ini?');">
                <i class="fa-solid fa-right-from-bracket"></i> Keluar Kelompok
              </button>
            <?= form_close() ?>
          <?php endif; ?>

        <?php elseif ($tugas->mode_kelompok === 'siswa' && !$tugas->kelompok_locked): ?>
          <p style="font-size: 0.86rem; color: var(--text-muted); margin-bottom: 14px;">
            Pilih salah satu kelompok di bawah ini. Kapasitas maksimal <?= (int) $tugas->anggota_per_kelompok ?> siswa per kelompok.
          </p>

          <?php if (empty($available_groups)): ?>
            <p style="font-size: 0.86rem; color: var(--text-muted);">Belum ada kelompok yang tersedia. Hubungi guru Anda.</p>
          <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 10px;">
              <?php foreach ($available_groups as $g): ?>
                <?php
                  $is_full = $tugas->anggota_per_kelompok && (int) $g->jumlah_anggota >= (int) $tugas->anggota_per_kelompok;
                ?>
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px; padding: 12px; border: 1px solid var(--card-border); border-radius: 10px; background: <?= $is_full ? '#F8FAFC' : '#FFFFFF' ?>;">
                  <div>
                    <div style="font-size: 0.9rem; font-weight: 700; color: #1E293B;"><?= html_escape($g->nama_kelompok) ?></div>
                    <div style="font-size: 0.76rem; color: var(--text-muted);">
                      <i class="fa-solid fa-user-group"></i>
                      <?= (int) $g->jumlah_anggota ?> / <?= (int) $tugas->anggota_per_kelompok ?> anggota
                    </div>
                  </div>
                  <?php if ($is_full): ?>
                    <span class="badge badge-overdue">Penuh</span>
                  <?php else: ?>
                    <?= form_open('siswa/gabung_kelompok/' . $tugas->id) ?>
                      <input type="hidden" name="kelompok_id" value="<?= (int) $g->id ?>">
                      <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-plus"></i> Gabung
                      </button>
                    <?= form_close() ?>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

        <?php else: ?>
          <div class="empty-state" style="padding: 18px 8px;">
            <i class="fa-solid fa-user-clock" style="font-size: 2rem;"></i>
            <p style="font-size: 0.85rem; margin-bottom: 0;">
              <?php if ($tugas->kelompok_locked): ?>
                Pembagian kelompok sudah dikunci. Hubungi guru jika Anda belum mendapatkan kelompok.
              <?php else: ?>
                Anda belum masuk kelompok. Kelompok akan dibentuk oleh guru.
              <?php endif; ?>
            </p>
          </div>
        <?php endif; ?>
      </div>

      <?php if ($kelompok): ?>
        <?php if ($pengumpulan): ?>
          <!-- GROUP SUBMITTED -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fa-solid fa-file-circle-check" style="color: #059669; margin-right: 8px;"></i>
                Status Pengumpulan Kelompok
              </h3>
            <?php if ($pengumpulan->status === 'dinilai'): ?>
              <span class="badge badge-graded"><i class="fa-solid fa-check-double"></i> Sudah Dinilai</span>
            <?php elseif ($pengumpulan->status === 'revisi'): ?>
              <span class="badge" style="background: #FFF7ED; color: #C2410C;"><i class="fa-solid fa-rotate"></i> Perlu Revisi</span>
            <?php elseif ($pengumpulan->status === 'terlambat'): ?>
              <span class="badge badge-overdue"><i class="fa-solid fa-triangle-exclamation"></i> Terlambat</span>
            <?php else: ?>
              <span class="badge badge-pending"><i class="fa-solid fa-clock"></i> Menunggu Dinilai</span>
            <?php endif; ?>
          </div>

          <?php if (!empty($pengumpulan->feedback)): ?>
            <div style="margin-bottom: 20px; background: #F8FAFC; border: 1px solid var(--card-border); border-radius: 10px; padding: 14px;">
              <div style="font-size: 0.82rem; font-weight: 700; color: #1E293B; margin-bottom: 4px;">
                <i class="fa-regular fa-comment-dots" style="color: var(--primary);"></i> <?= ($pengumpulan->status === 'revisi') ? 'Instruksi Revisi dari Guru:' : 'Catatan / Feedback Guru:' ?>
              </div>
              <div style="font-size: 0.88rem; color: #475569; font-style: italic;">
                "<?= nl2br(html_escape($pengumpulan->feedback)) ?>"
              </div>
            </div>
          <?php endif; ?>

            <div style="display: flex; flex-direction: column; gap: 12px; font-size: 0.88rem; color: #334155; margin-bottom: 20px;">
              <div>
                <span style="color: var(--text-muted); display: block; font-size: 0.78rem;">Waktu Pengiriman</span>
                <strong><?= date('d M Y, H:i:s', strtotime($pengumpulan->submitted_at)) ?> WIB</strong>
              </div>

              <div>
                <span style="color: var(--text-muted); display: block; font-size: 0.78rem; margin-bottom: 6px;">Berkas Jawaban Kelompok</span>
                <?= $this->load->view('siswa/tugas/_submitted_files', array(
                  'tugas_items' => $tugas_items,
                  'item_files'  => $item_files,
                  'legacy_files' => $legacy_files,
                  'legacy_file' => $pengumpulan->file_jawaban,
                ), TRUE) ?>
              </div>

              <?php if (!empty($pengumpulan->catatan)): ?>
                <div>
                  <span style="color: var(--text-muted); display: block; font-size: 0.78rem;">Catatan Kelompok</span>
                  <div style="background: #FFFFFF; border: 1px solid var(--card-border); padding: 10px; border-radius: 8px; margin-top: 4px; font-size: 0.85rem;">
                    <?= nl2br(html_escape($pengumpulan->catatan)) ?>
                  </div>
                </div>
              <?php endif; ?>
            </div>

            <?php if ($can_edit_items): ?>
              <div style="background: #EFF4FF; border: 1px solid #D5E3FC; border-radius: 10px; padding: 14px;">
                <div style="font-size: 0.84rem; font-weight: 700; color: var(--primary-dark); margin-bottom: 10px;">
                  <i class="fa-solid fa-rotate"></i> <?= $is_revision_requested ? 'Unggah Revisi' : 'Tambah / Perbarui Poin' ?>
                </div>
                <?= form_open_multipart('siswa/kumpul_tugas/' . $tugas->id) ?>
                <?= $this->load->view('siswa/tugas/_upload_fields', array(
                  'tugas_items' => $tugas_items,
                  'item_files'  => $item_files,
                  'legacy_files' => $legacy_files,
                ), TRUE) ?>
                  <div class="form-group" style="margin-bottom: 12px;">
                    <textarea name="catatan" class="form-control" style="min-height: 60px;" placeholder="Catatan revisi..."><?= html_escape((string) $pengumpulan->catatan) ?></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary btn-sm" style="width: 100%; justify-content: center;" onclick="return confirm('Apakah Anda yakin ingin mengumpulkan tugas ini?');">
                    <i class="fa-solid fa-arrows-rotate"></i> <?= $is_revision_requested ? 'Kirim Revisi' : 'Kirim / Perbarui Tugas' ?>
                  </button>
                <?= form_close() ?>
              </div>
            <?php else: ?>
              <div style="background: #F1F5F9; border-radius: 8px; padding: 10px; text-align: center; font-size: 0.8rem; color: var(--text-muted);">
                <i class="fa-solid fa-lock" style="margin-right: 4px;"></i> Pengumpulan kelompok terkunci.
              </div>
            <?php endif; ?>
          </div>

        <?php elseif ($is_overdue): ?>
          <!-- GROUP OVERDUE -->
          <div class="card" style="border-color: #FECACA;">
            <div style="text-align: center; padding: 20px 8px;">
              <div style="width: 56px; height: 56px; border-radius: 50%; background: #FEE2E2; color: #BE123C; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 16px;">
                <i class="fa-solid fa-clock-slash"></i>
              </div>
              <h3 style="font-size: 1.1rem; font-weight: 700; color: #991B1B; margin-bottom: 8px;">Waktu Pengumpulan Berakhir</h3>
              <p style="font-size: 0.85rem; color: #7F1D1D; line-height: 1.5; margin-bottom: 18px;">
                Deadline telah terlewat pada <?= date('d M Y, H:i', strtotime($tugas->deadline)) ?> WIB. Pengumpulan akan ditandai TERLAMBAT.
              </p>
              <?= form_open_multipart('siswa/kumpul_tugas/' . $tugas->id) ?>
                <div style="text-align: left;">
                  <?= $this->load->view('siswa/tugas/_upload_fields', array(
                    'tugas_items' => $tugas_items,
                    'item_files'  => $item_files,
                  'legacy_files' => $legacy_files,
                  ), TRUE) ?>
                </div>
                <div class="form-group" style="margin-bottom: 12px; text-align: left;">
                  <textarea name="catatan" class="form-control" style="min-height: 60px;" placeholder="Alasan keterlambatan kelompok..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;" onclick="return confirm('Apakah Anda yakin ingin mengumpulkan tugas ini?');">
                  <i class="fa-solid fa-upload"></i> Kumpulkan Terlambat
                </button>
              <?= form_close() ?>
            </div>
          </div>

        <?php else: ?>
          <!-- GROUP NOT SUBMITTED -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fa-solid fa-cloud-arrow-up" style="color: var(--primary); margin-right: 8px;"></i>
                Kumpulkan Tugas Kelompok
              </h3>
            </div>

            <?= form_open_multipart('siswa/kumpul_tugas/' . $tugas->id) ?>
              <?= $this->load->view('siswa/tugas/_upload_fields', array(
                'tugas_items' => $tugas_items,
                'item_files'  => $item_files,
                  'legacy_files' => $legacy_files,
              ), TRUE) ?>

              <div class="form-group">
                <label class="form-label">Catatan Kelompok (Opsional)</label>
                <textarea name="catatan" class="form-control" placeholder="Tuliskan catatan untuk guru..."></textarea>
              </div>

              <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 12px;" onclick="return confirm('Apakah Anda yakin ingin mengumpulkan tugas ini?');">
                <i class="fa-solid fa-paper-plane"></i> Kirimkan Tugas Kelompok
              </button>
            <?= form_close() ?>
          </div>
        <?php endif; ?>
      <?php endif; ?>

    <?php else: ?>
      <?php if ($pengumpulan): ?>
        <!-- SUBMITTED: View status, file, feedback -->
           <div class="card">
             <div class="card-header">
               <h3 class="card-title">
                 <i class="fa-solid fa-file-circle-check" style="color: #059669; margin-right: 8px;"></i>
                 Status Pengumpulan
               </h3>
               <?php if ($pengumpulan->status === 'dinilai'): ?>
                 <span class="badge badge-graded"><i class="fa-solid fa-check-double"></i> Sudah Dinilai</span>
               <?php elseif ($pengumpulan->status === 'revisi'): ?>
                 <span class="badge" style="background: #FFF7ED; color: #C2410C;"><i class="fa-solid fa-rotate"></i> Perlu Revisi</span>
               <?php elseif ($pengumpulan->status === 'terlambat'): ?>
                 <span class="badge badge-overdue"><i class="fa-solid fa-triangle-exclamation"></i> Terlambat</span>
               <?php else: ?>
                 <span class="badge badge-pending"><i class="fa-solid fa-clock"></i> Menunggu Dinilai</span>
               <?php endif; ?>
             </div>
 
             <!-- Feedback Guru -->
             <?php if (!empty($pengumpulan->feedback)): ?>
               <div style="margin-bottom: 20px; background: #F8FAFC; border: 1px solid var(--card-border); border-radius: 10px; padding: 14px;">
                 <div style="font-size: 0.82rem; font-weight: 700; color: #1E293B; margin-bottom: 4px;">
                   <i class="fa-regular fa-comment-dots" style="color: var(--primary);"></i> <?= ($pengumpulan->status === 'revisi') ? 'Instruksi Revisi dari Guru:' : 'Catatan / Feedback Guru:' ?>
                 </div>
                 <div style="font-size: 0.88rem; color: #475569; font-style: italic;">
                   "<?= nl2br(html_escape($pengumpulan->feedback)) ?>"
                 </div>
               </div>
             <?php endif; ?>

           <!-- Submission Info -->
          <div style="display: flex; flex-direction: column; gap: 12px; font-size: 0.88rem; color: #334155; margin-bottom: 20px;">
            <div>
              <span style="color: var(--text-muted); display: block; font-size: 0.78rem;">Waktu Pengiriman</span>
              <strong><?= date('d M Y, H:i:s', strtotime($pengumpulan->submitted_at)) ?> WIB</strong>
            </div>

            <div>
              <span style="color: var(--text-muted); display: block; font-size: 0.78rem; margin-bottom: 6px;">Berkas Jawaban</span>
              <?= $this->load->view('siswa/tugas/_submitted_files', array(
                'tugas_items' => $tugas_items,
                'item_files'  => $item_files,
                  'legacy_files' => $legacy_files,
                'legacy_file' => $pengumpulan->file_jawaban,
              ), TRUE) ?>
            </div>

            <?php if (!empty($pengumpulan->catatan)): ?>
              <div>
                <span style="color: var(--text-muted); display: block; font-size: 0.78rem;">Catatan Siswa</span>
                <div style="background: #FFFFFF; border: 1px solid var(--card-border); padding: 10px; border-radius: 8px; margin-top: 4px; font-size: 0.85rem;">
                  <?= nl2br(html_escape($pengumpulan->catatan)) ?>
                </div>
              </div>
            <?php endif; ?>
          </div>

           <div style="background: #F1F5F9; border-radius: 8px; padding: 10px; text-align: center; font-size: 0.8rem; color: var(--text-muted);">
             <?php if ($can_edit_items): ?>
               <i class="fa-solid fa-circle-info" style="margin-right: 4px;"></i> Anda masih dapat menambah atau memperbarui poin tugas di bawah ini.
             <?php else: ?>
               <i class="fa-solid fa-lock" style="margin-right: 4px;"></i> Tugas telah dikumpulkan dan terkunci.
             <?php endif; ?>
           </div>
         </div>

       <?php if ($can_edit_items): ?>
         <div class="card" style="margin-top: 20px;">
           <div class="card-header">
             <h3 class="card-title">
               <i class="fa-solid fa-rotate" style="color: var(--primary); margin-right: 8px;"></i>
               <?= $is_revision_requested ? 'Unggah Revisi' : 'Tambah / Perbarui Poin' ?>
             </h3>
           </div>
           <?= form_open_multipart('siswa/kumpul_tugas/' . $tugas->id) ?>
             <?= $this->load->view('siswa/tugas/_upload_fields', array(
               'tugas_items' => $tugas_items,
               'item_files'  => $item_files,
                  'legacy_files' => $legacy_files,
             ), TRUE) ?>
             <div class="form-group" style="margin-bottom: 12px;">
               <label class="form-label">Catatan Revisi</label>
               <textarea name="catatan" class="form-control" style="min-height: 70px;" placeholder="Tuliskan catatan revisi Anda..."><?= html_escape((string) $pengumpulan->catatan) ?></textarea>
             </div>
             <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;" onclick="return confirm('Apakah Anda yakin ingin mengumpulkan tugas ini?');">
               <i class="fa-solid fa-paper-plane"></i> <?= $is_revision_requested ? 'Kirim Revisi' : 'Kirim / Perbarui Tugas' ?>
             </button>
           <?= form_close() ?>
         </div>
       <?php endif; ?>

       <?php elseif ($is_overdue): ?>
        <!-- OVERDUE & NOT SUBMITTED -->
        <div class="card" style="border-color: #FECACA;">
          <div style="text-align: center; padding: 24px 12px;">
            <div style="width: 56px; height: 56px; border-radius: 50%; background: #FEE2E2; color: #BE123C; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 16px;">
              <i class="fa-solid fa-clock-slash"></i>
            </div>
            <h3 style="font-size: 1.15rem; font-weight: 700; color: #991B1B; margin-bottom: 8px;">
              Waktu Pengumpulan Telah Berakhir
            </h3>
            <p style="font-size: 0.88rem; color: #7F1D1D; line-height: 1.5; margin-bottom: 20px;">
              Deadline pengumpulan tugas ini telah terlewat pada tanggal <?= date('d M Y, H:i', strtotime($tugas->deadline)) ?> WIB.
            </p>

            <form action="<?= site_url('siswa/kumpul_tugas/' . $tugas->id) ?>" method="post" enctype="multipart/form-data" style="text-align: left; background: #FFF5F5; padding: 16px; border-radius: 10px; border: 1px solid #FECACA;">
              <p style="font-size: 0.82rem; color: #991B1B; font-weight: 600; margin-bottom: 12px;">
                <i class="fa-solid fa-triangle-exclamation"></i> Anda masih dapat mengumpulkan, namun status pengumpulan akan ditandai sebagai <strong>TERLAMBAT</strong>.
              </p>
              <?= $this->load->view('siswa/tugas/_upload_fields', array(
                'tugas_items' => $tugas_items,
                'item_files'  => $item_files,
                  'legacy_files' => $legacy_files,
              ), TRUE) ?>
              <div class="form-group" style="margin-bottom: 14px;">
                <label class="form-label" style="font-size: 0.82rem;">Alasan Keterlambatan / Catatan</label>
                <textarea name="catatan" class="form-control" placeholder="Tulis alasan keterlambatan..." style="min-height: 70px;"></textarea>
              </div>
              <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;" onclick="return confirm('Apakah Anda yakin ingin mengumpulkan tugas ini?');">
                <i class="fa-solid fa-upload"></i> Kumpulkan Terlambat
              </button>
            </form>
          </div>
        </div>

      <?php else: ?>
        <!-- NOT SUBMITTED & DEADLINE ACTIVE: Upload Form -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fa-solid fa-cloud-arrow-up" style="color: var(--primary); margin-right: 8px;"></i>
              Kumpulkan Tugas
            </h3>
          </div>

          <form action="<?= site_url('siswa/kumpul_tugas/' . $tugas->id) ?>" method="post" enctype="multipart/form-data">
            <?= $this->load->view('siswa/tugas/_upload_fields', array(
              'tugas_items' => $tugas_items,
              'item_files'  => $item_files,
                  'legacy_files' => $legacy_files,
            ), TRUE) ?>

            <div class="form-group">
              <label class="form-label">Catatan Tambahan (Opsional)</label>
              <textarea name="catatan" class="form-control" placeholder="Tuliskan catatan untuk guru pengajar jika ada..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 12px;" onclick="return confirm('Apakah Anda yakin ingin mengumpulkan tugas ini?');">
              <i class="fa-solid fa-paper-plane"></i> Kirimkan Tugas Sekarang
            </button>
          </form>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>
