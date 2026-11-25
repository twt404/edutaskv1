-- ============================================================
-- Migration: fitur kelompok tugas (mode acak/guru/siswa)
-- Jalankan pada database `elearning` yang sudah ada.
-- ============================================================

USE `elearning`;

ALTER TABLE `tugas`
  ADD COLUMN `mode_kelompok`        ENUM('acak','guru','siswa') NULL DEFAULT NULL AFTER `jenis`,
  ADD COLUMN `anggota_per_kelompok` TINYINT UNSIGNED NULL DEFAULT NULL AFTER `mode_kelompok`,
  ADD COLUMN `allow_revisi`         TINYINT(1) NOT NULL DEFAULT 1 AFTER `anggota_per_kelompok`,
  ADD COLUMN `wajib_kontribusi`     TINYINT(1) NOT NULL DEFAULT 1 AFTER `allow_revisi`,
  ADD COLUMN `kelompok_locked`      TINYINT(1) NOT NULL DEFAULT 0 AFTER `wajib_kontribusi`;

ALTER TABLE `pengumpulan_tugas`
  ADD UNIQUE KEY `uq_pt_tugas_kelompok` (`tugas_id`, `kelompok_id`);
