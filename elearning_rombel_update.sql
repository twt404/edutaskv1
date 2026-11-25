-- ============================================================
-- Migration: fitur Rombel (XI/XII) + relasi kelas ke rombel
-- Jalankan pada database `elearning` yang sudah ada.
-- ============================================================

USE `elearning`;

CREATE TABLE IF NOT EXISTS `rombel` (
  `id`           INT          NOT NULL AUTO_INCREMENT,
  `nama_rombel`  VARCHAR(100) NOT NULL,
  `tingkat`      ENUM('X','XI','XII') NOT NULL,
  `jurusan`      VARCHAR(50)  NULL DEFAULT NULL,
  `kode_rombel`  VARCHAR(20)  NOT NULL,
  `created_at`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_rombel_kode` (`kode_rombel`),
  KEY `idx_rombel_tingkat` (`tingkat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `rombel_siswa` (
  `id`         INT       NOT NULL AUTO_INCREMENT,
  `rombel_id`  INT       NOT NULL,
  `siswa_id`   INT       NOT NULL,
  `joined_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_rs_siswa` (`siswa_id`),
  KEY `idx_rs_rombel` (`rombel_id`),
  CONSTRAINT `fk_rs_rombel` FOREIGN KEY (`rombel_id`) REFERENCES `rombel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_rs_siswa`  FOREIGN KEY (`siswa_id`)  REFERENCES `users`  (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `kelas`
  ADD COLUMN `rombel_id` INT NULL DEFAULT NULL AFTER `guru_id`,
  ADD UNIQUE KEY `uq_kelas_rombel_nama` (`rombel_id`, `nama_kelas`),
  ADD KEY `idx_kelas_rombel` (`rombel_id`),
  ADD CONSTRAINT `fk_kelas_rombel` FOREIGN KEY (`rombel_id`) REFERENCES `rombel` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

-- ------------------------------------------------------------
-- BACKFILL DATA LAMA (opsional, jalankan manual setelah membuat rombel)
-- ------------------------------------------------------------
-- 1) Buat rombel, contoh:
-- INSERT INTO rombel (nama_rombel, tingkat, jurusan, kode_rombel)
-- VALUES ('XI PPLG 1', 'XI', 'PPLG', 'R11PLG'),
--        ('XII PPLG 1', 'XII', 'PPLG', 'R12PLG');
--
-- 2) Isi anggota rombel (satu siswa satu rombel):
-- INSERT INTO rombel_siswa (rombel_id, siswa_id) VALUES (1, 4), (1, 5), (2, 6);
--
-- 3) Tetapkan kelas lama ke rombel:
-- UPDATE kelas SET rombel_id = 1 WHERE id IN (1, 2);
--
-- 4) Sinkronkan enrollment kelas_siswa dari rombel:
-- INSERT IGNORE INTO kelas_siswa (kelas_id, siswa_id, joined_at)
-- SELECT k.id, rs.siswa_id, NOW()
-- FROM kelas k
-- JOIN rombel_siswa rs ON rs.rombel_id = k.rombel_id
-- WHERE k.rombel_id IS NOT NULL;
--
-- 5) Hapus enrollment lama yang tidak sesuai rombel (hati-hati, cek dulu):
-- DELETE ks FROM kelas_siswa ks
-- JOIN kelas k ON k.id = ks.kelas_id
-- LEFT JOIN rombel_siswa rs ON rs.rombel_id = k.rombel_id AND rs.siswa_id = ks.siswa_id
-- WHERE k.rombel_id IS NOT NULL AND rs.id IS NULL;
