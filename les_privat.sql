-- phpMyAdmin SQL Dump
-- Database: `les_privat`
-- Dibuat ulang berdasarkan struktur terakhir project FASE Les
--
-- Cara import:
--   1. Buat database baru bernama `les_privat` di phpMyAdmin, ATAU
--   2. Langsung import file ini (sudah ada CREATE DATABASE di bawah)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `les_privat`
--
CREATE DATABASE IF NOT EXISTS `les_privat` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `les_privat`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('siswa','ortu','pengajar','admin') DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
-- Password default untuk admin: "admin123" (hash bcrypt)
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@faseles.com', '$2y$10$PQ.kv4BWBuFflmNbAuTEEun.I0.tC4c/xeVkswYIjy3KVjQYCDimW', 'pengajar', CURRENT_TIMESTAMP);

-- --------------------------------------------------------

--
-- Table structure for table `paket`
--

CREATE TABLE `paket` (
  `id` int NOT NULL,
  `tingkat` enum('TK','SMP','SMA') NOT NULL,
  `tipe_kelas` enum('Privat','Kelompok') NOT NULL,
  `durasi_menit` int NOT NULL,
  `jumlah_pertemuan` int NOT NULL DEFAULT 8,
  `harga` int NOT NULL,
  `is_aktif` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `paket`
--

INSERT INTO `paket` (`id`, `tingkat`, `tipe_kelas`, `durasi_menit`, `jumlah_pertemuan`, `harga`, `is_aktif`) VALUES
(1,  'TK',  'Privat',   45, 8, 330000, 1),
(2,  'TK',  'Privat',   60, 8, 430000, 1),
(3,  'TK',  'Kelompok', 45, 8, 290000, 1),
(4,  'TK',  'Kelompok', 60, 8, 380000, 1),
(5,  'SMP', 'Privat',   45, 8, 365000, 1),
(6,  'SMP', 'Privat',   60, 8, 480000, 1),
(7,  'SMP', 'Kelompok', 45, 8, 330000, 1),
(8,  'SMP', 'Kelompok', 60, 8, 430000, 1),
(9,  'SMA', 'Privat',   45, 8, 440000, 1),
(10, 'SMA', 'Privat',   60, 8, 580000, 1),
(11, 'SMA', 'Kelompok', 45, 8, 405000, 1),
(12, 'SMA', 'Kelompok', 60, 8, 530000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pendaftaran`
--

CREATE TABLE `pendaftaran` (
  `id` int NOT NULL,
  `no_transaksi` varchar(25) DEFAULT NULL,
  `user_id` int NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `tempat_lahir` varchar(50) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` varchar(15) NOT NULL,
  `alamat` text NOT NULL,
  `asal_sekolah` varchar(10) NOT NULL,
  `paket_id` int NOT NULL,
  `jadwal_hari` varchar(15) NOT NULL,
  `jadwal_jam` time NOT NULL,
  `nama_ortu` varchar(100) NOT NULL,
  `no_wa_ortu` varchar(20) NOT NULL,
  `pekerjaan_ortu` varchar(50) NOT NULL,
  `status_pembayaran` enum('pending','lunas','ditolak','kadaluarsa') NOT NULL DEFAULT 'pending',
  `tanggal_daftar` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `paket`
--
ALTER TABLE `paket`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_transaksi` (`no_transaksi`),
  ADD KEY `fk_user` (`user_id`),
  ADD KEY `fk_paket` (`paket_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `paket`
--
ALTER TABLE `paket`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pendaftaran`
--
ALTER TABLE `pendaftaran`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_paket` FOREIGN KEY (`paket_id`) REFERENCES `paket` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- --------------------------------------------------------

--
-- Table structure for table `bukti_pembayaran`
--

CREATE TABLE `bukti_pembayaran` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pendaftaran_id` int NOT NULL,
  `file_bukti` varchar(255) NOT NULL,
  `nama_pengirim` varchar(100) NOT NULL,
  `jumlah_transfer` int NOT NULL,
  `tanggal_transfer` date NOT NULL,
  `catatan` text,
  `status_verifikasi` enum('pending','diterima','ditolak','kadaluarsa') NOT NULL DEFAULT 'pending',
  `catatan_admin` text,
  `uploaded_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `verified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_bukti_pendaftaran` (`pendaftaran_id`),
  CONSTRAINT `fk_bukti_pendaftaran` FOREIGN KEY (`pendaftaran_id`) REFERENCES `pendaftaran` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
