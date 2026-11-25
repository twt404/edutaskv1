USE `elearning`;

ALTER TABLE `pengumpulan_tugas`
  MODIFY `status` ENUM('menunggu','dinilai','terlambat','revisi') NOT NULL DEFAULT 'menunggu',
  ADD COLUMN `nilai_sebelumnya` DECIMAL(5,2) NULL DEFAULT NULL AFTER `nilai`,
  ADD COLUMN `feedback_sebelumnya` TEXT NULL DEFAULT NULL AFTER `feedback`,
  ADD COLUMN `dinilai_at_sebelumnya` DATETIME NULL DEFAULT NULL AFTER `dinilai_at`;
