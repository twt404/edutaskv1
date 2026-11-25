-- ============================================================
-- EduTask E-Learning System — Full Database Schema + Seed Data
-- Engine: MySQL / MariaDB | charset: utf8mb4
-- Konsep:
--   kelas       = tingkat/rombongan belajar (contoh: XI PPLG 1)
--   mapel       = mata pelajaran di dalam kelas (contoh: Pemrograman Web)
-- ============================================================

CREATE DATABASE IF NOT EXISTS `elearning`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `elearning`;

SET FOREIGN_KEY_CHECKS=0;

-- ------------------------------------------------------------
-- Table: users
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id`         INT           NOT NULL AUTO_INCREMENT,
  `nama`       VARCHAR(100)  NOT NULL,
  `email`      VARCHAR(100)  NOT NULL,
  `password`   VARCHAR(255)  NOT NULL,
  `role`       ENUM('admin','guru','siswa') NOT NULL DEFAULT 'siswa',
  `foto`       VARCHAR(255)  NULL DEFAULT NULL,
  `tingkat`    VARCHAR(10)   NULL DEFAULT NULL,
  `jurusan`    VARCHAR(50)   NULL DEFAULT NULL,
  `created_at` TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: kelas (tingkat/rombongan belajar, mis. XI PPLG 1)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `kelas`;
CREATE TABLE `kelas` (
  `id`          INT          NOT NULL AUTO_INCREMENT,
  `nama_kelas`  VARCHAR(100) NOT NULL,
  `tingkat`     ENUM('X','XI','XII') NOT NULL,
  `jurusan`     VARCHAR(50)  NULL DEFAULT NULL,
  `kode_kelas`  VARCHAR(20)  NOT NULL,
  `created_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_kelas_kode` (`kode_kelas`),
  KEY `idx_kelas_tingkat` (`tingkat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: kelas_siswa (satu siswa hanya satu kelas)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `kelas_siswa`;
CREATE TABLE `kelas_siswa` (
  `id`        INT       NOT NULL AUTO_INCREMENT,
  `kelas_id`  INT       NOT NULL,
  `siswa_id`  INT       NOT NULL,
  `joined_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_ks_siswa` (`siswa_id`),
  KEY `idx_ks_kelas` (`kelas_id`),
  CONSTRAINT `fk_ks_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ks_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: mapel (mata pelajaran di dalam kelas)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `mapel`;
CREATE TABLE `mapel` (
  `id`          INT          NOT NULL AUTO_INCREMENT,
  `nama_mapel`  VARCHAR(150) NOT NULL,
  `kode_mapel`  VARCHAR(20)  NOT NULL,
  `deskripsi`   TEXT         NULL,
  `guru_id`     INT          NOT NULL,
  `kelas_id`    INT          NULL DEFAULT NULL,
  `created_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_mapel_kode` (`kode_mapel`),
  UNIQUE KEY `uq_mapel_kelas_nama` (`kelas_id`, `nama_mapel`),
  KEY `idx_mapel_guru` (`guru_id`),
  KEY `idx_mapel_kelas` (`kelas_id`),
  CONSTRAINT `fk_mapel_guru`  FOREIGN KEY (`guru_id`)  REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_mapel_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: mapel_siswa (enrollment mapel, dicerminkan dari kelas_siswa)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `mapel_siswa`;
CREATE TABLE `mapel_siswa` (
  `id`        INT       NOT NULL AUTO_INCREMENT,
  `mapel_id`  INT       NOT NULL,
  `siswa_id`  INT       NOT NULL,
  `joined_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_ms_mapel_siswa` (`mapel_id`, `siswa_id`),
  KEY `idx_ms_mapel` (`mapel_id`),
  KEY `idx_ms_siswa` (`siswa_id`),
  CONSTRAINT `fk_ms_mapel` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ms_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: materi
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `materi`;
CREATE TABLE `materi` (
  `id`           INT          NOT NULL AUTO_INCREMENT,
  `mapel_id`     INT          NOT NULL,
  `judul`        VARCHAR(200) NOT NULL,
  `deskripsi`    TEXT         NULL,
  `file_materi`  VARCHAR(255) NULL DEFAULT NULL,
  `urutan`       INT          NOT NULL DEFAULT 0,
  `created_at`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_materi_mapel` (`mapel_id`),
  CONSTRAINT `fk_materi_mapel` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: tugas
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `tugas`;
CREATE TABLE `tugas` (
  `id`                    INT          NOT NULL AUTO_INCREMENT,
  `mapel_id`              INT          NOT NULL,
  `judul`                 VARCHAR(200) NOT NULL,
  `deskripsi`             TEXT         NULL,
  `jenis`                 ENUM('individu','kelompok') NOT NULL DEFAULT 'individu',
  `mode_kelompok`         ENUM('acak','guru','siswa') NULL DEFAULT NULL,
  `anggota_per_kelompok`  TINYINT UNSIGNED NULL DEFAULT NULL,
  `allow_revisi`          TINYINT(1)   NOT NULL DEFAULT 1,
  `wajib_kontribusi`      TINYINT(1)   NOT NULL DEFAULT 1,
  `kelompok_locked`       TINYINT(1)   NOT NULL DEFAULT 0,
  `deadline`              DATETIME     NOT NULL,
  `file_tugas`            VARCHAR(255) NULL DEFAULT NULL,
  `status`                ENUM('draft','published','scheduled') NOT NULL DEFAULT 'published',
  `publish_at`            DATETIME     NULL DEFAULT NULL,
  `created_at`            TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tugas_mapel` (`mapel_id`),
  KEY `idx_status_publish` (`status`, `publish_at`),
  CONSTRAINT `fk_tugas_mapel` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: kelompok
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `kelompok`;
CREATE TABLE `kelompok` (
  `id`             INT          NOT NULL AUTO_INCREMENT,
  `tugas_id`       INT          NOT NULL,
  `nama_kelompok`  VARCHAR(100) NOT NULL,
  `created_at`     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_kelompok_tugas` (`tugas_id`),
  CONSTRAINT `fk_kelompok_tugas` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: kelompok_anggota
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `kelompok_anggota`;
CREATE TABLE `kelompok_anggota` (
  `id`           INT NOT NULL AUTO_INCREMENT,
  `kelompok_id`  INT NOT NULL,
  `siswa_id`     INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_kelompok_anggota` (`kelompok_id`, `siswa_id`),
  KEY `idx_ka_kelompok` (`kelompok_id`),
  KEY `idx_ka_siswa`    (`siswa_id`),
  CONSTRAINT `fk_ka_kelompok` FOREIGN KEY (`kelompok_id`) REFERENCES `kelompok` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ka_siswa`    FOREIGN KEY (`siswa_id`)    REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: pengumpulan_tugas
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `pengumpulan_tugas`;
CREATE TABLE `pengumpulan_tugas` (
  `id`            INT            NOT NULL AUTO_INCREMENT,
  `tugas_id`      INT            NOT NULL,
  `siswa_id`      INT            NOT NULL,
  `kelompok_id`   INT            NULL DEFAULT NULL,
  `file_jawaban`  VARCHAR(255)   NOT NULL,
  `catatan`       TEXT           NULL,
  `nilai`         DECIMAL(5,2)   NULL DEFAULT NULL,
  `nilai_sebelumnya` DECIMAL(5,2) NULL DEFAULT NULL,
  `feedback`      TEXT           NULL DEFAULT NULL,
  `feedback_sebelumnya` TEXT     NULL DEFAULT NULL,
  `status`        ENUM('menunggu','dinilai','terlambat','revisi') NOT NULL DEFAULT 'menunggu',
  `submitted_at`  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dinilai_at`    DATETIME       NULL DEFAULT NULL,
  `dinilai_at_sebelumnya` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_pt_tugas_kelompok` (`tugas_id`, `kelompok_id`),
  KEY `idx_pt_tugas`    (`tugas_id`),
  KEY `idx_pt_siswa`    (`siswa_id`),
  KEY `idx_pt_kelompok` (`kelompok_id`),
  CONSTRAINT `fk_pt_tugas`    FOREIGN KEY (`tugas_id`)    REFERENCES `tugas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pt_siswa`    FOREIGN KEY (`siswa_id`)    REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pt_kelompok` FOREIGN KEY (`kelompok_id`) REFERENCES `kelompok` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: tugas_item (checklist poin/berkas dari guru)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `tugas_item`;
CREATE TABLE `tugas_item` (
  `id`         INT          NOT NULL AUTO_INCREMENT,
  `tugas_id`   INT          NOT NULL,
  `label`      VARCHAR(150) NOT NULL,
  `instruksi`  TEXT         NULL DEFAULT NULL,
  `urutan`     INT          NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_ti_tugas` (`tugas_id`),
  CONSTRAINT `fk_ti_tugas` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: pengumpulan_tugas_file (multiple berkas per item)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `pengumpulan_tugas_file`;
CREATE TABLE `pengumpulan_tugas_file` (
  `id`             INT          NOT NULL AUTO_INCREMENT,
  `pengumpulan_id` INT          NOT NULL,
  `tugas_item_id`  INT          NULL DEFAULT NULL,
  `nama_file`      VARCHAR(255) NOT NULL,
  `nama_asli`      VARCHAR(255) NULL DEFAULT NULL,
  `ukuran_file`    INT          NOT NULL DEFAULT 0,
  `created_at`     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_ptf_pengumpulan_item` (`pengumpulan_id`, `tugas_item_id`),
  KEY `idx_ptf_pengumpulan` (`pengumpulan_id`),
  KEY `idx_ptf_item` (`tugas_item_id`),
  CONSTRAINT `fk_ptf_pengumpulan` FOREIGN KEY (`pengumpulan_id`) REFERENCES `pengumpulan_tugas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ptf_item` FOREIGN KEY (`tugas_item_id`) REFERENCES `tugas_item` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: tugas_penerima (siswa terpilih untuk tugas individu)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `tugas_penerima`;
CREATE TABLE `tugas_penerima` (
  `id`       INT NOT NULL AUTO_INCREMENT,
  `tugas_id` INT NOT NULL,
  `siswa_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_tp_tugas_siswa` (`tugas_id`, `siswa_id`),
  KEY `idx_tp_tugas` (`tugas_id`),
  KEY `idx_tp_siswa` (`siswa_id`),
  CONSTRAINT `fk_tp_tugas` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_tp_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: tugas_lampiran (multiple lampiran dari guru)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `tugas_lampiran`;
CREATE TABLE `tugas_lampiran` (
  `id`          INT          NOT NULL AUTO_INCREMENT,
  `tugas_id`    INT          NOT NULL,
  `nama_file`   VARCHAR(255) NOT NULL,
  `nama_asli`   VARCHAR(255) NOT NULL,
  `ukuran_file` INT          NOT NULL,
  `created_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tl_tugas` (`tugas_id`),
  CONSTRAINT `fk_tl_tugas` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: notifikasi
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `notifikasi`;
CREATE TABLE `notifikasi` (
  `id`             INT          NOT NULL AUTO_INCREMENT,
  `user_id`        INT          NOT NULL,
  `tugas_id`       INT          NULL,
  `pengumpulan_id` INT          NULL,
  `jenis`          VARCHAR(30)  NOT NULL DEFAULT 'pengumpulan',
  `judul`          VARCHAR(200) NOT NULL,
  `pesan`          TEXT         NOT NULL,
  `is_read`        TINYINT(1)   NOT NULL DEFAULT 0,
  `created_at`     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_notif_ref` (`user_id`, `jenis`, `pengumpulan_id`),
  KEY `idx_notif_user` (`user_id`),
  KEY `idx_notif_user_read` (`user_id`, `is_read`),
  KEY `idx_notif_tugas` (`tugas_id`),
  CONSTRAINT `fk_notif_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- SEED DATA
-- ============================================================

-- ------------------------------------------------------------
-- Users (1 admin, 2 guru, 5 siswa)
-- ------------------------------------------------------------
INSERT INTO `users` (`nama`, `email`, `password`, `role`) VALUES
  ('Administrator',  'admin@edutask.id',  MD5('admin123'), 'admin'),
  ('Budi Santoso',   'guru@edutask.id',   MD5('guru123'),  'guru'),
  ('Siti Rahayu',    'guru2@edutask.id',  MD5('guru123'),  'guru'),
  ('Ahmad Fauzi',    'siswa1@edutask.id', MD5('siswa123'), 'siswa'),
  ('Budi Prasetyo',  'siswa2@edutask.id', MD5('siswa123'), 'siswa'),
  ('Citra Dewi',     'siswa3@edutask.id', MD5('siswa123'), 'siswa'),
  ('Dewi Lestari',   'siswa4@edutask.id', MD5('siswa123'), 'siswa'),
  ('Eko Wahyudi',    'siswa5@edutask.id', MD5('siswa123'), 'siswa');
-- id mapping: admin=1, guru Budi=2, guru Siti=3, siswa 4..8

-- ------------------------------------------------------------
-- Kelas (tingkat/rombongan belajar)
-- ------------------------------------------------------------
INSERT INTO `kelas` (`nama_kelas`, `tingkat`, `jurusan`, `kode_kelas`) VALUES
  ('XI PPLG 1',  'XI',  'PPLG', 'K11PLG'),
  ('XII PPLG 1', 'XII', 'PPLG', 'K12PLG');
-- id mapping: kelas XI=1, kelas XII=2

-- ------------------------------------------------------------
-- Anggota kelas (satu siswa hanya satu kelas)
-- ------------------------------------------------------------
INSERT INTO `kelas_siswa` (`kelas_id`, `siswa_id`) VALUES
  (1, 4), (1, 5), (1, 6),
  (2, 7), (2, 8);

-- ------------------------------------------------------------
-- Mapel (mata pelajaran di dalam kelas)
-- ------------------------------------------------------------
INSERT INTO `mapel` (`nama_mapel`, `kode_mapel`, `deskripsi`, `guru_id`, `kelas_id`) VALUES
  ('Pemrograman Web',        'MP-PW-XI',   'Mempelajari dasar-dasar HTML, CSS, dan JavaScript.',          2, 1),
  ('Basis Data',             'MP-BD-XI',   'Mempelajari konsep dan implementasi basis data relasional.',  3, 1),
  ('Pemrograman Web Lanjut', 'MP-PWL-XII', 'Pembuatan aplikasi web dinamis dan framework modern.',        2, 2),
  ('Basis Data Lanjut',      'MP-BDL-XII', 'Normalisasi lanjutan, optimasi query, dan administrasi DB.',  3, 2);
-- id mapping: mapel XI PW=1, XI BD=2, XII PWL=3, XII BDL=4

-- ------------------------------------------------------------
-- Enrollment mapel (dicerminkan dari kelas_siswa)
-- ------------------------------------------------------------
INSERT INTO `mapel_siswa` (`mapel_id`, `siswa_id`) VALUES
  (1, 4), (1, 5), (1, 6),
  (2, 4), (2, 5), (2, 6),
  (3, 7), (3, 8),
  (4, 7), (4, 8);

-- ------------------------------------------------------------
-- Materi (2 per mapel)
-- ------------------------------------------------------------
INSERT INTO `materi` (`mapel_id`, `judul`, `deskripsi`, `urutan`) VALUES
  (1, 'Pengenalan HTML',    'Struktur dasar dokumen HTML, tag-tag penting, dan semantic HTML5.',   1),
  (1, 'CSS Fundamental',    'Selector, box model, flexbox, dan grid layout pada CSS.',              2),
  (2, 'Konsep Basis Data',  'Pengertian basis data, DBMS, dan model data relasional.',              1),
  (2, 'SQL Dasar',          'Perintah DDL dan DML: CREATE, INSERT, SELECT, UPDATE, DELETE.',        2),
  (3, 'JavaScript Lanjut',  'DOM, event, asynchronous JavaScript, dan fetch API.',                  1),
  (3, 'Framework Web',      'Pengenalan struktur proyek dan routing pada framework web.',             2),
  (4, 'Normalisasi',        'Proses normalisasi 1NF, 2NF, 3NF beserta contoh kasus.',              1),
  (4, 'Query Lanjutan',     'Join, subquery, indexing, dan optimasi performa query.',                 2);

-- ------------------------------------------------------------
-- Tugas (2 per mapel: 1 individu, 1 kelompok)
-- ------------------------------------------------------------
INSERT INTO `tugas` (`mapel_id`, `judul`, `deskripsi`, `jenis`, `mode_kelompok`, `anggota_per_kelompok`, `deadline`) VALUES
  (1, 'Membuat Halaman Profil',         'Buat halaman HTML+CSS profil diri sendiri dengan layout responsif.',                     'individu', NULL,    NULL, '2024-12-20 23:59:00'),
  (1, 'Proyek Website Kelompok',        'Buat website sederhana bertema bebas secara berkelompok.',                              'kelompok', 'guru',  3,    '2024-12-27 23:59:00'),
  (2, 'ER Diagram',                     'Buat ER diagram untuk sistem perpustakaan dan jelaskan setiap entitas.',                'individu', NULL,    NULL, '2024-12-21 23:59:00'),
  (2, 'Implementasi Database Kelompok', 'Rancang dan implementasikan database toko online secara berkelompok.',                  'kelompok', 'guru',  3,    '2024-12-28 23:59:00'),
  (3, 'Studi Kasus Framework',          'Analisis struktur proyek dan alur routing pada sebuah framework web.',                 'individu', NULL,    NULL, '2024-12-22 23:59:00'),
  (3, 'Proyek Aplikasi Web',            'Bangun aplikasi web sederhana secara berkelompok menggunakan framework.',               'kelompok', 'acak',  2,    '2024-12-29 23:59:00'),
  (4, 'Normalisasi Kasus',              'Normalisasi skema database studi kasus hingga 3NF.',                                   'individu', NULL,    NULL, '2024-12-23 23:59:00'),
  (4, 'Optimasi Query',                 'Optimalkan query lambat pada studi kasus dan jelaskan hasilnya secara berkelompok.',    'kelompok', 'siswa', 2,    '2024-12-30 23:59:00');
-- id mapping: tugas 1-2 mapel XI PW, 3-4 mapel XI BD, 5-6 mapel XII PWL, 7-8 mapel XII BDL

-- ------------------------------------------------------------
-- Kelompok
-- ------------------------------------------------------------
INSERT INTO `kelompok` (`tugas_id`, `nama_kelompok`) VALUES
  (2, 'Kelompok Alpha'),   -- id=1
  (4, 'Kelompok Delta'),   -- id=2
  (6, 'Kelompok Epsilon'), -- id=3
  (8, 'Kelompok Zeta'),    -- id=4
  (8, 'Kelompok Eta');     -- id=5

-- ------------------------------------------------------------
-- Anggota kelompok
-- ------------------------------------------------------------
INSERT INTO `kelompok_anggota` (`kelompok_id`, `siswa_id`) VALUES
  (1, 4), (1, 5), (1, 6),   -- Alpha: XI
  (2, 4), (2, 5), (2, 6),   -- Delta: XI
  (3, 7), (3, 8),           -- Epsilon: XII
  (4, 7),                   -- Zeta: XII
  (5, 8);                   -- Eta: XII

-- ------------------------------------------------------------
-- Pengumpulan tugas
-- ------------------------------------------------------------
INSERT INTO `pengumpulan_tugas`
  (`tugas_id`, `siswa_id`, `kelompok_id`, `file_jawaban`, `catatan`, `nilai`, `feedback`, `status`, `submitted_at`, `dinilai_at`)
VALUES
  -- Tugas individu XI-PW (tugas_id=1)
  (1, 4, NULL, 'ahmad_profil.zip', 'Sudah selesai dikerjakan.',          88.00, 'Bagus! Layout sudah responsif.', 'dinilai',   '2024-12-18 10:30:00', '2024-12-19 09:00:00'),
  (1, 5, NULL, 'budi_profil.zip',  'Menggunakan flexbox untuk layout.',   90.00, 'Sangat baik, desain menarik.',   'dinilai',   '2024-12-18 14:00:00', '2024-12-19 09:30:00'),
  (1, 6, NULL, 'citra_profil.zip', 'Maaf, terlambat pengumpulan.',        NULL,  NULL,                             'terlambat', '2024-12-21 08:00:00', NULL),

  -- Tugas kelompok XI-PW (tugas_id=2): Kelompok Alpha
  (2, 4, 1, 'alpha_website.zip', 'Dikumpulkan oleh ketua kelompok Alpha.', 85.00, 'Kerja tim terlihat baik.', 'dinilai', '2024-12-25 16:00:00', '2024-12-26 10:00:00'),

  -- Tugas individu XI-BD (tugas_id=3)
  (3, 4, NULL, 'ahmad_erd.pdf', 'ER diagram perpustakaan digital.', 92.00, 'ER diagram lengkap dan tepat.', 'dinilai', '2024-12-19 11:00:00', '2024-12-20 08:00:00'),
  (3, 5, NULL, 'budi_erd.pdf',  'Sudah selesai.',                   NULL,  NULL,                            'menunggu', '2024-12-21 09:00:00', NULL),

  -- Tugas kelompok XI-BD (tugas_id=4): Kelompok Delta
  (4, 5, 2, 'delta_db.zip', 'Implementasi MySQL dengan 10 tabel.', NULL, NULL, 'menunggu', '2024-12-28 20:00:00', NULL),

  -- Tugas individu XII-PWL (tugas_id=5)
  (5, 7, NULL, 'dewi_framework.pdf', 'Analisis routing dan middleware.', NULL, NULL, 'menunggu', '2024-12-22 18:00:00', NULL),

  -- Tugas kelompok XII-PWL (tugas_id=6): Kelompok Epsilon
  (6, 7, 3, 'epsilon_web.zip', 'Aplikasi web sederhana dengan CRUD.', NULL, NULL, 'menunggu', '2024-12-29 15:00:00', NULL),

  -- Tugas individu XII-BDL (tugas_id=7)
  (7, 8, NULL, 'eko_normalisasi.pdf', 'Normalisasi hingga 3NF.', NULL, NULL, 'menunggu', '2024-12-23 20:00:00', NULL),

  -- Tugas kelompok XII-BDL (tugas_id=8): Kelompok Zeta
  (8, 7, 4, 'zeta_optimasi.zip', 'Optimasi index dan query join.', NULL, NULL, 'terlambat', '2024-12-31 10:00:00', NULL);

-- ------------------------------------------------------------
-- Notifikasi
-- ------------------------------------------------------------
INSERT INTO `notifikasi` (`user_id`, `judul`, `pesan`, `is_read`) VALUES
  (4, 'Tugas Dinilai',           'Tugas "Membuat Halaman Profil" Anda telah dinilai. Nilai: 88.',             1),
  (4, 'Tugas Dinilai',           'Tugas "Proyek Website Kelompok" kelompok Anda telah dinilai. Nilai: 85.',   1),
  (5, 'Tugas Dinilai',           'Tugas "Membuat Halaman Profil" Anda telah dinilai. Nilai: 90.',             0),
  (6, 'Peringatan Keterlambatan','Tugas "Membuat Halaman Profil" Anda terlambat dikumpulkan.',                0),
  (7, 'Tugas Baru',              'Tugas baru tersedia: "Proyek Aplikasi Web". Deadline 29 Des 2024.',          1),
  (8, 'Tugas Baru',              'Tugas baru tersedia: "Optimasi Query". Deadline 30 Des 2024.',               0),
  (2, 'Pengumpulan Baru',        '3 siswa telah mengumpulkan tugas "Membuat Halaman Profil".',                 1),
  (3, 'Pengumpulan Baru',        '2 kelompok telah mengumpulkan tugas "Implementasi Database Kelompok".',      0);


SET FOREIGN_KEY_CHECKS=1;
