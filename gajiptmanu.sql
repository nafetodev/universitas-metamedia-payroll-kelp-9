CREATE DATABASE IF NOT EXISTS `gajiptmanu`;
USE `gajiptmanu`;

-- 1. TABEL USER (Untuk Login Aplikasi)
CREATE TABLE `user` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data Awal untuk Login (Username: admin, Password: admin123)
INSERT INTO `user` (`username`, `password`, `nama_lengkap`) VALUES
('admin', '$2y$10$TFjE8S/.gAY0KCQd3H7Hheuj03WjPXjlGu8q4NWcqyOnncY3J2jJG', 'Administrator');


-- 2. TABEL GAJI POKOK (Master Aturan Pendidikan)
CREATE TABLE `gaji_pokok` (
  `pendidikan` varchar(10) NOT NULL,
  `gaji_pokok` int NOT NULL,
  PRIMARY KEY (`pendidikan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Mengisi aturan Master Gaji Pokok sesuai dokumen soal
INSERT INTO `gaji_pokok` (`pendidikan`, `gaji_pokok`) VALUES
('SLTP', 2500000),
('SLTA', 2700000),
('S1', 4000000);


-- 3. TABEL KARYAWAN
CREATE TABLE `karyawan` (
  `nik` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `pendidikan` varchar(10) NOT NULL,
  `jekel` enum('LK','PR') NOT NULL, -- LK = Laki-laki, PR = Perempuan
  PRIMARY KEY (`nik`),
  FOREIGN KEY (`pendidikan`) REFERENCES `gaji_pokok` (`pendidikan`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Mengisi 4 Data Karyawan Awal dari Studi Kasus Soal
INSERT INTO `karyawan` (`nik`, `nama`, `pendidikan`, `jekel`) VALUES
('20011', 'Q. Syifa', 'S1', 'PR'),
('20012', 'Gophar', 'SLTA', 'LK'),
('203', 'Rina Yusuf', 'S1', 'PR'),
('204', 'Ahmad Kamal', 'SLTP', 'LK');


-- 4. TABEL PENGGAJIAN (Transaksi Bulanan)
CREATE TABLE `penggajian` (
  `id_penggajian` int NOT NULL AUTO_INCREMENT,
  `nik` varchar(20) NOT NULL,
  `periode` varchar(20) NOT NULL, -- Contoh: 'Mei-2026'
  `gaji_pokok` int NOT NULL,
  `tunjangan_keluarga` int NOT NULL,
  `gaji_bersih` int NOT NULL,
  `tanggal_hitung` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_penggajian`),
  FOREIGN KEY (`nik`) REFERENCES `karyawan` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE OR REPLACE VIEW v_laporan_gaji AS
SELECT 
    p.id_penggajian,
    k.nik,
    k.nama AS nama_karyawan,
    k.pendidikan,
    k.jekel AS jenis_kelamin,
    p.periode,
    p.gaji_pokok,
    p.tunjangan_keluarga,
    p.gaji_bersih
FROM penggajian p
JOIN karyawan k ON p.nik = k.nik;