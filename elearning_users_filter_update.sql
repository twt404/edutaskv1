-- Tambah kolom tingkat dan jurusan ke tabel users
ALTER TABLE `users` ADD COLUMN `tingkat` VARCHAR(10) DEFAULT NULL AFTER `foto`;
ALTER TABLE `users` ADD COLUMN `jurusan` VARCHAR(50) DEFAULT NULL AFTER `tingkat`;
