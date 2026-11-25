-- Migrasi untuk pendukung tugas multi-item dan multi-file
USE `elearning`;

CREATE TABLE IF NOT EXISTS `tugas_item` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `tugas_id` INT NOT NULL,
  `label` VARCHAR(150) NOT NULL,
  `instruksi` TEXT NULL DEFAULT NULL,
  `urutan` INT NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_ti_tugas` (`tugas_id`),
  CONSTRAINT `fk_ti_tugas` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `pengumpulan_tugas_file` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `pengumpulan_id` INT NOT NULL,
  `tugas_item_id` INT NULL DEFAULT NULL,
  `nama_file` VARCHAR(255) NOT NULL,
  `nama_asli` VARCHAR(255) NULL DEFAULT NULL,
  `ukuran_file` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_ptf_pengumpulan_item` (`pengumpulan_id`, `tugas_item_id`),
  KEY `idx_ptf_pengumpulan` (`pengumpulan_id`),
  KEY `idx_ptf_item` (`tugas_item_id`),
  CONSTRAINT `fk_ptf_pengumpulan` FOREIGN KEY (`pengumpulan_id`) REFERENCES `pengumpulan_tugas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ptf_item` FOREIGN KEY (`tugas_item_id`) REFERENCES `tugas_item` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
