USE `elearning`;

-- Multiple berkas jawaban siswa per item/tugas.
-- Hapus unique key lama yang membatasi satu berkas per item.
SET @drop_idx_sql = (
  SELECT IF(
    COUNT(*) > 0,
    'ALTER TABLE `pengumpulan_tugas_file` DROP INDEX `uq_ptf_pengumpulan_item`',
    'SELECT ''index uq_ptf_pengumpulan_item sudah tidak ada'''
  )
  FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'pengumpulan_tugas_file'
    AND INDEX_NAME = 'uq_ptf_pengumpulan_item'
);
PREPARE stmt FROM @drop_idx_sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Simpan nama asli dan ukuran berkas untuk tampilan.
SET @add_nama_asli_sql = (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `pengumpulan_tugas_file` ADD COLUMN `nama_asli` VARCHAR(255) NULL DEFAULT NULL AFTER `nama_file`',
    'SELECT ''kolom nama_asli sudah ada'''
  )
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'pengumpulan_tugas_file'
    AND COLUMN_NAME = 'nama_asli'
);
PREPARE stmt FROM @add_nama_asli_sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @add_ukuran_sql = (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `pengumpulan_tugas_file` ADD COLUMN `ukuran_file` INT NOT NULL DEFAULT 0 AFTER `nama_asli`',
    'SELECT ''kolom ukuran_file sudah ada'''
  )
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'pengumpulan_tugas_file'
    AND COLUMN_NAME = 'ukuran_file'
);
PREPARE stmt FROM @add_ukuran_sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Index pengganti untuk pencarian per item.
SET @add_idx_sql = (
  SELECT IF(
    COUNT(*) = 0,
    'ALTER TABLE `pengumpulan_tugas_file` ADD INDEX `idx_ptf_pengumpulan_item` (`pengumpulan_id`, `tugas_item_id`)',
    'SELECT ''index idx_ptf_pengumpulan_item sudah ada'''
  )
  FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'pengumpulan_tugas_file'
    AND INDEX_NAME = 'idx_ptf_pengumpulan_item'
);
PREPARE stmt FROM @add_idx_sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
