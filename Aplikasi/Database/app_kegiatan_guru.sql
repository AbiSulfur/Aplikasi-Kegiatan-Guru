-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 03, 2025 at 06:17 AM
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
(1, 2, 1, 1, '2025-08-11', 'Mengajar materi dasar HTML & CSS', '2025-08-11 02:39:26'),
(2, 3, 2, 2, '2025-08-11', 'Membimbing siswa membuat project IoT', '2025-08-11 02:39:26'),
(3, 2, 3, 3, '2025-08-10', 'Mengawasi ujian semester genap', '2025-08-11 02:39:26'),
(7, 2, 3, 4, '2027-12-12', 'sbsfbsrhsbdnjtgntfdgnAheretgjngfjnryjndghszsfbvcdvdfg', '2025-08-28 07:10:15'),
(8, 3, 2, 2, '2025-09-23', 'cbeudbvcszgvbiyesgfuigsizofgisugvbuiboyeuvfiuoVLuifgsoufbvuosweifuis', '2025-08-28 07:11:02'),
(9, 2, 1, 2, '2345-12-12', 'afghjfdbfjyrgfxbrtfgdxhdtxdg', '2025-08-29 01:15:56');

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
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('admin','guru','siswa') NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `nama_lengkap`, `role`, `status`, `created_at`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'Administrator', 'admin', 'approved', '2025-08-11 02:39:25'),
(2, 'guru1', '9310f83135f238b04af729fec041cca8', 'Budi Santoso', 'guru', 'approved', '2025-08-11 02:39:25'),
(3, 'guru2', '9310f83135f238b04af729fec041cca8', 'Rina Andriani', 'guru', 'approved', '2025-08-11 02:39:25'),
(4, 'siswa1', '3afa0d81296a4f17d477ec823261b1ec', 'Ahmad Fauzi', 'siswa', 'approved', '2025-08-11 02:39:25'),
(5, 'siswa2', '3afa0d81296a4f17d477ec823261b1ec', 'Siti Nurhaliza', 'siswa', 'approved', '2025-08-11 02:39:25');

--
-- Indexes for dumped tables
--

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
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

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
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kegiatan_guru`
--
ALTER TABLE `kegiatan_guru`
  ADD CONSTRAINT `kegiatan_guru_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `kegiatan_guru_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE,
  ADD CONSTRAINT `kegiatan_guru_ibfk_3` FOREIGN KEY (`id_jenis`) REFERENCES `jenis_kegiatan` (`id_jenis`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
