-- Notifikasi pengumpulan tugas untuk guru
-- Satu pengumpulan hanya memiliki satu notifikasi per guru.
-- Penyimpanan berikutnya memperbarui baris yang sama.

ALTER TABLE `notifikasi`
  ADD COLUMN `tugas_id` INT NULL AFTER `user_id`,
  ADD COLUMN `pengumpulan_id` INT NULL AFTER `tugas_id`,
  ADD COLUMN `jenis` VARCHAR(30) NOT NULL DEFAULT 'pengumpulan' AFTER `pengumpulan_id`,
  ADD COLUMN `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `created_at`,
  ADD UNIQUE KEY `uq_notif_ref` (`user_id`, `jenis`, `pengumpulan_id`),
  ADD KEY `idx_notif_user_read` (`user_id`, `is_read`),
  ADD KEY `idx_notif_tugas` (`tugas_id`);

UPDATE `notifikasi` SET `updated_at` = `created_at`;
