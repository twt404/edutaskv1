-- ============================================================
-- MIGRASI: pisahkan KELAS (XI/XII) dan MAPEL (mata pelajaran)
-- Dari skema lama:
--   rombel        = kelas XI/XII
--   rombel_siswa  = anggota kelas
--   kelas         = mata pelajaran
--   kelas_siswa   = enrollment mapel
-- Menjadi:
--   kelas         = XI/XII
--   kelas_siswa   = anggota kelas
--   mapel         = mata pelajaran
--   mapel_siswa   = enrollment mapel
--
-- PENTING:
--   1. Backup database terlebih dahulu.
--   2. Migrasi ini untuk DB yang sudah memakai skema rombel.
--   3. Jalankan seluruh file sekali saja. Jika ada error di tengah,
--      restore backup lalu perbaiki sebelum mengulang.
-- ============================================================

USE `elearning`;

SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- 1. Amankan nama tabel mapel lama
-- ------------------------------------------------------------
RENAME TABLE `kelas`       TO `mapel`;
RENAME TABLE `kelas_siswa` TO `mapel_siswa`;

-- ------------------------------------------------------------
-- 2. Ubah nama tabel rombel menjadi kelas
-- ------------------------------------------------------------
RENAME TABLE `rombel`       TO `kelas`;
RENAME TABLE `rombel_siswa` TO `kelas_siswa`;

-- ------------------------------------------------------------
-- 3. Rename kolom tabel mapel (dahulu tabel kelas)
-- ------------------------------------------------------------
ALTER TABLE `mapel`
  CHANGE `nama_kelas` `nama_mapel` VARCHAR(150) NOT NULL,
  CHANGE `kode_kelas` `kode_mapel` VARCHAR(20)  NOT NULL,
  CHANGE `rombel_id`  `kelas_id`   INT          NULL DEFAULT NULL;

-- ------------------------------------------------------------
-- 4. Rename kolom tabel kelas (dahulu tabel rombel)
-- ------------------------------------------------------------
ALTER TABLE `kelas`
  CHANGE `nama_rombel` `nama_kelas` VARCHAR(100) NOT NULL,
  CHANGE `kode_rombel` `kode_kelas` VARCHAR(20)  NOT NULL;

-- ------------------------------------------------------------
-- 5. Rename kolom pivot enrollment mapel
-- ------------------------------------------------------------
ALTER TABLE `mapel_siswa`
  CHANGE `kelas_id` `mapel_id` INT NOT NULL;

-- ------------------------------------------------------------
-- 6. Rename kolom pivot anggota kelas
-- ------------------------------------------------------------
ALTER TABLE `kelas_siswa`
  CHANGE `rombel_id` `kelas_id` INT NOT NULL;

-- ------------------------------------------------------------
-- 7. Rename kolom mapel pada materi dan tugas
-- ------------------------------------------------------------
ALTER TABLE `materi`
  CHANGE `kelas_id` `mapel_id` INT NOT NULL;

ALTER TABLE `tugas`
  CHANGE `kelas_id` `mapel_id` INT NOT NULL;

SET FOREIGN_KEY_CHECKS = 1;

-- ------------------------------------------------------------
-- 8. Sinkronkan enrollment mapel dari anggota kelas.
--    Siswa yang tergabung di kelas otomatis terdaftar di semua
--    mapel milik kelas tersebut.
-- ------------------------------------------------------------
INSERT IGNORE INTO `mapel_siswa` (`mapel_id`, `siswa_id`, `joined_at`)
SELECT m.`id`, ks.`siswa_id`, NOW()
FROM `mapel` m
JOIN `kelas_siswa` ks ON ks.`kelas_id` = m.`kelas_id`
WHERE m.`kelas_id` IS NOT NULL;

-- ------------------------------------------------------------
-- 9. Hapus enrollment mapel yang siswanya sudah tidak berada
--    di kelas mapel tersebut.
-- ------------------------------------------------------------
DELETE ms
FROM `mapel_siswa` ms
JOIN `mapel` m ON m.`id` = ms.`mapel_id`
LEFT JOIN `kelas_siswa` ks
  ON ks.`kelas_id` = m.`kelas_id`
 AND ks.`siswa_id` = ms.`siswa_id`
WHERE m.`kelas_id` IS NOT NULL
  AND ks.`id` IS NULL;

-- ------------------------------------------------------------
-- 10. Verifikasi (opsional, jalankan manual)
-- ------------------------------------------------------------
-- SELECT * FROM `kelas`;
-- SELECT * FROM `kelas_siswa`;
-- SELECT * FROM `mapel`;
-- SELECT * FROM `mapel_siswa`;
-- SELECT id, judul, mapel_id FROM `materi`;
-- SELECT id, judul, mapel_id FROM `tugas`;
