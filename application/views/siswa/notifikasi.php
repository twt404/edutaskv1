<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
  <div>
    <h2 style="font-size: 1.4rem; font-weight: 800; color: #0F172A;">Notifikasi</h2>
    <p style="color: var(--text-muted); font-size: 0.9rem;">Informasi dan pengumuman terbaru seputar tugas dan kelas</p>
  </div>
</div>

<?php if (empty($notif_list)): ?>
  <div class="card">
    <div class="empty-state">
      <i class="fa-solid fa-bell-slash"></i>
      <h4>Belum Ada Notifikasi</h4>
      <p>Tidak ada pemberitahuan baru saat ini. Semua tugas dan aktivitas terkini Anda akan muncul di sini.</p>
    </div>
  </div>
<?php else: ?>
  <div class="card" style="padding: 0; overflow: hidden;">
    <div style="display: flex; flex-direction: column;">
      <?php foreach ($notif_list as $notif): ?>
        <div style="padding: 18px 24px; border-bottom: 1px solid var(--card-border); display: flex; align-items: flex-start; gap: 16px; background: <?= $notif->is_read ? '#FFFFFF' : '#F1FBFF' ?>;">
          <div style="width: 40px; height: 40px; border-radius: 50%; background: <?= $notif->is_read ? '#F1F5F9' : '#E0F5FF' ?>; color: <?= $notif->is_read ? '#64748B' : 'var(--primary-dark)' ?>; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0;">
            <i class="fa-solid fa-bell"></i>
          </div>

          <div style="flex: 1; min-width: 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
              <h4 style="font-size: 0.95rem; font-weight: 700; color: #1E293B;">
                <?= html_escape($notif->judul) ?>
              </h4>
              <span style="font-size: 0.78rem; color: #94A3B8;">
                <i class="fa-regular fa-clock"></i> <?= date('d M Y, H:i', strtotime($notif->created_at)) ?>
              </span>
            </div>
            <p style="font-size: 0.88rem; color: #475569; line-height: 1.5;">
              <?= nl2br(html_escape($notif->pesan)) ?>
            </p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>

