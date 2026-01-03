-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2025 at 07:45 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `app_kegiatan_guru`
--

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `id_guru` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `nuptk` varchar(20) DEFAULT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `foto` varchar(255) DEFAULT 'default-avatar.png',
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `agama` enum('Islam','Kristen','Katolik','Hindu','Buddha','Konghucu') NOT NULL,
  `status_perkawinan` enum('Belum Menikah','Menikah','Cerai Hidup','Cerai Mati') DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_telepon` varchar(15) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `rt` varchar(5) DEFAULT NULL,
  `rw` varchar(5) DEFAULT NULL,
  `kelurahan` varchar(50) DEFAULT NULL,
  `kecamatan` varchar(50) DEFAULT NULL,
  `kabupaten_kota` varchar(50) DEFAULT NULL,
  `provinsi` varchar(50) DEFAULT NULL,
  `kode_pos` varchar(10) DEFAULT NULL,
  `status_kepegawaian` enum('PNS','PPPK','GTY','GTT','Honorer') NOT NULL,
  `nip_lama` varchar(20) DEFAULT NULL,
  `sk_pengangkatan` varchar(100) DEFAULT NULL,
  `tmt_pengangkatan` date DEFAULT NULL COMMENT 'Tanggal Mulai Tugas',
  `lembaga_pengangkat` varchar(100) DEFAULT NULL,
  `golongan` varchar(10) DEFAULT NULL COMMENT 'Contoh: III/a, IV/b',
  `pangkat` varchar(50) DEFAULT NULL COMMENT 'Contoh: Penata Muda, Pembina',
  `tmt_golongan` date DEFAULT NULL COMMENT 'TMT Golongan Terakhir',
  `jabatan` varchar(50) DEFAULT NULL COMMENT 'Contoh: Guru Mata Pelajaran, Kepala Sekolah, Wakil Kepala Sekolah',
  `jenis_ptk` varchar(50) DEFAULT NULL COMMENT 'Jenis Pendidik dan Tenaga Kependidikan',
  `tugas_tambahan` varchar(100) DEFAULT NULL COMMENT 'Contoh: Wali Kelas, Pembina OSIS',
  `pendidikan_terakhir` enum('SMA/SMK','D1','D2','D3','D4','S1','S2','S3') NOT NULL,
  `jurusan_pendidikan` varchar(100) DEFAULT NULL,
  `tahun_lulus_pendidikan` year(4) DEFAULT NULL,
  `universitas` varchar(100) DEFAULT NULL,
  `bidang_studi` varchar(100) NOT NULL COMMENT 'Mata pelajaran yang diampu',
  `sertifikasi_pendidik` enum('Sudah','Belum') DEFAULT 'Belum',
  `no_sertifikat_pendidik` varchar(50) DEFAULT NULL,
  `tahun_sertifikasi` year(4) DEFAULT NULL,
  `npwp` varchar(20) DEFAULT NULL,
  `nama_wajib_pajak` varchar(100) DEFAULT NULL,
  `no_rekening` varchar(30) DEFAULT NULL,
  `nama_bank` varchar(50) DEFAULT NULL,
  `atas_nama_rekening` varchar(100) DEFAULT NULL,
  `nama_ibu_kandung` varchar(100) DEFAULT NULL,
  `nama_pasangan` varchar(100) DEFAULT NULL,
  `pekerjaan_pasangan` varchar(50) DEFAULT NULL,
  `status_aktif` enum('Aktif','Tidak Aktif','Cuti','Pensiun') DEFAULT 'Aktif',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_kegiatan`
--

CREATE TABLE `jenis_kegiatan` (
  `id_jenis` int(11) NOT NULL,
  `nama_jenis` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenis_kegiatan`
--

INSERT INTO `jenis_kegiatan` (`id_jenis`, `nama_jenis`) VALUES
(1, 'Mengajar Materi'),
(2, 'Membimbing Tugas'),
(3, 'Mengawasi Ujian'),
(4, 'Rapat Guru');

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan_guru`
--

CREATE TABLE `kegiatan_guru` (
  `id_kegiatan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `id_jenis` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `laporan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kegiatan_guru`
--

INSERT INTO `kegiatan_guru` (`id_kegiatan`, `id_user`, `id_kelas`, `id_jenis`, `tanggal`, `laporan`, `created_at`) VALUES
(2, 3, 2, 2, '2025-08-11', 'Membimbing siswa membuat project IoT', '2025-08-11 02:39:26'),
(8, 3, 2, 2, '2025-09-23', 'cbeudbvcszgvbiyesgfuigsizofgisugvbuiboyeuvfiuoVLuifgsoufbvuosweifuis', '2025-08-28 07:11:02');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int(11) NOT NULL,
  `nama_kelas` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`) VALUES
(1, 'X RPL 1'),
(2, 'X RPL 2'),
(3, 'XI TKJ 1'),
(4, 'XI TKJ 2');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `nis` varchar(20) NOT NULL,
  `nama_siswa` varchar(100) NOT NULL,
  `kelas` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nip_verification` varchar(18) DEFAULT NULL COMMENT 'NIP untuk verifikasi guru',
  `nisn_verification` varchar(10) DEFAULT NULL COMMENT 'NISN untuk verifikasi siswa',
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('admin','guru','siswa') DEFAULT NULL COMMENT 'Role will be assigned by admin after verification',
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `nip_verification`, `nisn_verification`, `nama_lengkap`, `role`, `status`, `created_at`, `email`, `phone`, `alamat`, `kelas_id`, `profile_picture`) VALUES
(1, 'admin', '123456', NULL, NULL, 'hffgg', 'admin', 'approved', '2025-08-11 02:39:25', NULL, NULL, NULL, NULL, 'profile_1_1764139219.png'),
(3, 'guru2', '9310f83135f238b04af729fec041cca8', NULL, NULL, 'Rina Andriani', 'guru', 'approved', '2025-08-11 02:39:25', NULL, NULL, NULL, NULL, NULL),
(4, 'Siswa1', '3afa0d81296a4f17d477ec823261b1ec', NULL, NULL, 'Udin Kemplang', 'siswa', 'approved', '2025-08-11 02:39:25', 'mautidur34@gmail.com', '087725223486', 'Jl. Raden Saleh No.999, RT.001/RW.003, Karang Tengah, Kec. Karang Tengah, Kota Tangerang, Banten 15157', 1, 'profile_4_1758714789.gif'),
(5, 'siswa2', '3afa0d81296a4f17d477ec823261b1ec', NULL, NULL, 'Siti Nurhaliza', 'siswa', 'approved', '2025-08-11 02:39:25', NULL, NULL, NULL, NULL, NULL),
(6, '123', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'qwerty', 'siswa', 'approved', '2025-09-03 04:21:07', NULL, NULL, NULL, NULL, NULL),
(8, 'Bambang', '$2y$10$gAIzfmzUXRBxReRUbpdvwOSEp7vOwRDsonIhfU2gzAGR5qHWUsULG', NULL, NULL, 'qwerty', 'guru', 'approved', '2025-09-23 05:51:44', NULL, NULL, NULL, NULL, NULL),
(9, 'Sutisno Sutanto', '$2y$10$A2ZH6yGLCT60V75cLYTlie6uQX7IFc2J7fzSmpiAT4SuBjyMF4aiC', NULL, NULL, 'qwerty', 'guru', 'approved', '2025-09-23 05:52:20', NULL, NULL, NULL, NULL, NULL),
(10, 'Suka', 'zxcvbn', NULL, NULL, 'Suki', 'guru', 'approved', '2025-09-23 06:08:40', NULL, NULL, NULL, NULL, NULL),
(12, '456', '567890', NULL, NULL, '123', '', 'rejected', '2025-09-25 03:03:31', NULL, NULL, NULL, NULL, NULL),
(13, 'Mamat', 'DDDDDD', NULL, '1231231231', 'Mamat Megalodon', NULL, 'pending', '2025-11-06 13:17:25', 'jawicikiwir@gmail.com', NULL, NULL, NULL, NULL),
(14, 'Udin', 'abcdef', NULL, '123456789', 'Udin Kemplang', NULL, 'pending', '2025-11-09 10:51:45', 'siplikitiw@gmail.com', NULL, NULL, NULL, NULL),
(15, 'Santos', 'wasdwasd', '567587263918263473', NULL, 'Budi Santoso', NULL, 'pending', '2025-11-09 16:01:17', 'Bahlillucu@gmail.com', NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id_guru`),
  ADD UNIQUE KEY `nip` (`nip`),
  ADD UNIQUE KEY `nuptk` (`nuptk`),
  ADD UNIQUE KEY `nik` (`nik`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `idx_nip` (`nip`),
  ADD KEY `idx_nama` (`nama_lengkap`),
  ADD KEY `idx_status` (`status_kepegawaian`),
  ADD KEY `idx_bidang` (`bidang_studi`),
  ADD KEY `idx_status_aktif` (`status_aktif`);

--
-- Indexes for table `jenis_kegiatan`
--
ALTER TABLE `jenis_kegiatan`
  ADD PRIMARY KEY (`id_jenis`);

--
-- Indexes for table `kegiatan_guru`
--
ALTER TABLE `kegiatan_guru`
  ADD PRIMARY KEY (`id_kegiatan`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `id_jenis` (`id_jenis`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD UNIQUE KEY `nis` (`nis`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `unique_email` (`email`),
  ADD KEY `kelas_id` (`kelas_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `id_guru` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_kegiatan`
--
ALTER TABLE `jenis_kegiatan`
  MODIFY `id_jenis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kegiatan_guru`
--
ALTER TABLE `kegiatan_guru`
  MODIFY `id_kegiatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `guru`
--
ALTER TABLE `guru`
  ADD CONSTRAINT `guru_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kegiatan_guru`
--
ALTER TABLE `kegiatan_guru`
  ADD CONSTRAINT `kegiatan_guru_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `kegiatan_guru_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE,
  ADD CONSTRAINT `kegiatan_guru_ibfk_3` FOREIGN KEY (`id_jenis`) REFERENCES `jenis_kegiatan` (`id_jenis`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id_kelas`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
