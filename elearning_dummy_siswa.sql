-- Data Dummy Siswa untuk Testing Filter
USE `elearning`;

INSERT INTO `users` (`nama`, `email`, `password`, `role`, `tingkat`, `jurusan`, `created_at`) VALUES
-- Tingkat XI - PPLG
('Andi XI PPLG', 'andi.xi.pplg@example.com', MD5('siswa123'), 'siswa', 'XI', 'PPLG', NOW()),
('Budi XI PPLG', 'budi.xi.pplg@example.com', MD5('siswa123'), 'siswa', 'XI', 'PPLG', NOW()),
('Citra XI PPLG', 'citra.xi.pplg@example.com', MD5('siswa123'), 'siswa', 'XI', 'PPLG', NOW()),

-- Tingkat XI - BD (Bisnis Digital)
('Dewi XI BD', 'dewi.xi.bd@example.com', MD5('siswa123'), 'siswa', 'XI', 'BD', NOW()),
('Eko XI BD', 'eko.xi.bd@example.com', MD5('siswa123'), 'siswa', 'XI', 'BD', NOW()),

-- Tingkat XII - PPLG
('Fani XII PPLG', 'fani.xii.pplg@example.com', MD5('siswa123'), 'siswa', 'XII', 'PPLG', NOW()),
('Gani XII PPLG', 'gani.xii.pplg@example.com', MD5('siswa123'), 'siswa', 'XII', 'PPLG', NOW()),
('Hana XII PPLG', 'hana.xii.pplg@example.com', MD5('siswa123'), 'siswa', 'XII', 'PPLG', NOW()),

-- Tingkat XII - TJKT
('Iwan XII TJKT', 'iwan.xii.tjkt@example.com', MD5('siswa123'), 'siswa', 'XII', 'TJKT', NOW()),
('Joni XII TJKT', 'joni.xii.tjkt@example.com', MD5('siswa123'), 'siswa', 'XII', 'TJKT', NOW());
