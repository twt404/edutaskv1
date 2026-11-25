-- Status publikasi tugas
-- draft     : hanya terlihat guru
-- published : langsung terlihat siswa
-- scheduled : terlihat siswa setelah publish_at

ALTER TABLE `tugas`
  ADD COLUMN `status` ENUM('draft','published','scheduled') NOT NULL DEFAULT 'published' AFTER `file_tugas`,
  ADD COLUMN `publish_at` DATETIME DEFAULT NULL AFTER `status`,
  ADD INDEX `idx_status_publish` (`status`, `publish_at`);
