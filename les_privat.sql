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
  `role` enum('siswa','ortu','pengajar') DEFAULT NULL,
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
  `kelas` varchar(20) NOT NULL,
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
-- Indexes for table `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_transaksi` (`no_transaksi`),
  ADD KEY `fk_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
