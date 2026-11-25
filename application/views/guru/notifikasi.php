<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
  <div>
    <h2 style="font-size: 1.4rem; font-weight: 800; color: #0F172A;">Notifikasi</h2>
    <p style="color: var(--text-muted); font-size: 0.9rem;">Aktivitas pengumpulan tugas dari siswa</p>
  </div>
</div>

<?php if (empty($notif_list)): ?>
  <div class="card">
    <div class="empty-state">
      <i class="fa-solid fa-bell-slash"></i>
      <h4>Belum Ada Notifikasi</h4>
      <p>Belum ada siswa yang mengumpulkan tugas atau mengirim catatan.</p>
    </div>
  </div>
<?php else: ?>
  <div class="card" style="padding: 0; overflow: hidden;">
    <div style="display: flex; flex-direction: column;">
      <?php foreach ($notif_list as $notif): ?>
        <?php $waktu = !empty($notif->updated_at) ? $notif->updated_at : $notif->created_at; ?>
        <div style="padding: 18px 24px; border-bottom: 1px solid var(--card-border); display: flex; align-items: flex-start; gap: 16px; background: <?= $notif->is_read ? '#FFFFFF' : '#F1FBFF' ?>;">
          <div style="width: 40px; height: 40px; border-radius: 50%; background: <?= $notif->is_read ? '#F1F5F9' : '#E0F5FF' ?>; color: <?= $notif->is_read ? '#64748B' : 'var(--primary-dark)' ?>; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0;">
            <i class="fa-solid fa-bell"></i>
          </div>

          <div style="flex: 1; min-width: 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; gap: 12px;">
              <h4 style="font-size: 0.95rem; font-weight: 700; color: #1E293B;">
                <?= html_escape($notif->judul) ?>
              </h4>
              <span style="font-size: 0.78rem; color: #94A3B8; white-space: nowrap;">
                <i class="fa-regular fa-clock"></i> <?= date('d M Y, H:i', strtotime($waktu)) ?>
              </span>
            </div>
            <p style="font-size: 0.88rem; color: #475569; line-height: 1.5; margin: 0;">
              <?= nl2br(html_escape($notif->pesan)) ?>
            </p>
            <?php if (!empty($notif->tugas_id)): ?>
              <a href="<?= site_url('guru/detail_tugas/' . (int) $notif->tugas_id) ?>" style="display: inline-flex; align-items: center; gap: 6px; margin-top: 10px; font-size: 0.82rem; font-weight: 600; color: var(--primary-dark); text-decoration: none;">
                Lihat tugas <i class="fa-solid fa-arrow-right"></i>
              </a>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>
