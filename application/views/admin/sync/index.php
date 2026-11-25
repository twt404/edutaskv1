<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Sinkronisasi Data 1-Klik</h1>
        <p class="text-slate-500 text-sm mt-1">Sinkronisasikan Database & File Assets (Uploads) antara Server Remote (Hosting) dan Lokal.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- PULL: Remote ke Lokal -->
    <div class="card bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-lg">
                <i class="fa-solid fa-cloud-arrow-down"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800">Tarik Data (PULL)</h2>
                <p class="text-xs text-slate-500">Hosting &rarr; Lokal</p>
            </div>
        </div>
        <p class="text-sm text-slate-600 mb-6 leading-relaxed">
            Mendownload database terbaru dan seluruh file asset dari server hosting/remote, kemudian melakukan overwrite/restore otomatis di server lokal ini.
        </p>

        <form action="<?= site_url('admin/sync') ?>" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menarik data dari hosting? Data lokal saat ini akan di-overwrite!')">
            <input type="hidden" name="action" value="pull">
            
            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-2">URL Server Remote (Hosting)</label>
                <input type="url" name="remote_url" placeholder="https://elearning-sekolah.com" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>

            <div class="mb-6">
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-2">Secret Auth Key</label>
                <input type="password" name="secret_key" value="SECRET_SYNC_KEY_12345" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>

            <button type="submit" class="w-full btn-primary py-3 justify-center rounded-xl font-semibold shadow-md">
                <i class="fa-solid fa-cloud-arrow-down"></i>
                <span>Tarik Data dari Hosting (Pull)</span>
            </button>
        </form>
    </div>

    <!-- PUSH: Lokal ke Remote -->
    <div class="card bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-lg">
                <i class="fa-solid fa-cloud-arrow-up"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800">Kirim Data (PUSH)</h2>
                <p class="text-xs text-slate-500">Lokal &rarr; Hosting</p>
            </div>
        </div>
        <p class="text-sm text-slate-600 mb-6 leading-relaxed">
            Mengunggah database lokal dan seluruh file asset dari lokal ke server hosting/remote, kemudian server hosting melakukan overwrite/restore otomatis.
        </p>

        <form action="<?= site_url('admin/sync') ?>" method="post" onsubmit="return confirm('Apakah Anda yakin ingin mengirim data ke hosting? Data di hosting akan di-overwrite!')">
            <input type="hidden" name="action" value="push">
            
            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-2">URL Server Remote (Hosting)</label>
                <input type="url" name="remote_url" placeholder="https://elearning-sekolah.com" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>

            <div class="mb-6">
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-2">Secret Auth Key</label>
                <input type="password" name="secret_key" value="SECRET_SYNC_KEY_12345" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3 justify-center rounded-xl font-semibold shadow-md flex items-center gap-2 transition-colors">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <span>Kirim Data ke Hosting (Push)</span>
            </button>
        </form>
    </div>
</div>

<!-- Informasi / Riwayat Sinkronisasi Terakhir -->
<div class="card bg-white border border-slate-200 rounded-2xl p-6 shadow-sm mt-6">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center font-bold text-lg">
            <i class="fa-solid fa-circle-info"></i>
        </div>
        <div>
            <h2 class="text-lg font-bold text-slate-800">Informasi & Riwayat Sinkronisasi</h2>
            <p class="text-xs text-slate-500">Catatan aktivitas sinkronisasi terakhir</p>
        </div>
    </div>

    <?php if (!empty($sync_info)): ?>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200 text-sm">
            <div>
                <span class="block text-xs font-semibold text-slate-400 uppercase">Waktu Sinkronisasi</span>
                <span class="font-bold text-slate-700"><?= htmlspecialchars($sync_info['timestamp']) ?></span>
            </div>
            <div>
                <span class="block text-xs font-semibold text-slate-400 uppercase">Aksi Terakhir</span>
                <span class="font-bold text-blue-600"><?= htmlspecialchars($sync_info['type']) ?></span>
            </div>
            <div>
                <span class="block text-xs font-semibold text-slate-400 uppercase">Status</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                    <?= htmlspecialchars($sync_info['status']) ?>
                </span>
            </div>
            <div>
                <span class="block text-xs font-semibold text-slate-400 uppercase">Keterangan</span>
                <span class="text-slate-600"><?= htmlspecialchars($sync_info['message']) ?></span>
            </div>
        </div>
    <?php else: ?>
        <p class="text-sm text-slate-500 italic bg-slate-50 p-4 rounded-xl border border-slate-200">
            Belum ada riwayat sinkronisasi yang dicatat pada server ini.
        </p>
    <?php endif; ?>
</div>
