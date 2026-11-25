USE `elearning`;

-- Tabel untuk menyimpan siapa saja yang menerima tugas individu tertentu
CREATE TABLE IF NOT EXISTS `tugas_penerima` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `tugas_id` INT NOT NULL,
  `siswa_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_tp_tugas_siswa` (`tugas_id`, `siswa_id`),
  KEY `idx_tp_tugas` (`tugas_id`),
  KEY `idx_tp_siswa` (`siswa_id`),
  CONSTRAINT `fk_tp_tugas` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_tp_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel untuk menyimpan multiple lampiran dari guru
CREATE TABLE IF NOT EXISTS `tugas_lampiran` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `tugas_id` INT NOT NULL,
  `nama_file` VARCHAR(255) NOT NULL,
  `nama_asli` VARCHAR(255) NOT NULL,
  `ukuran_file` INT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tl_tugas` (`tugas_id`),
  CONSTRAINT `fk_tl_tugas` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
