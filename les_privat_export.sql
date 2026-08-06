-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: les_privat
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin','$2y$10$o11fEEO3glVc3e0zh9CR7OSKXdqDnGJ7e7GNoYKRkwAFZ58Bi3c/S','Admin Fase Les','2026-06-22 14:20:48');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bukti_pembayaran`
--

DROP TABLE IF EXISTS `bukti_pembayaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bukti_pembayaran` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pendaftaran_id` int NOT NULL,
  `file_bukti` varchar(255) NOT NULL,
  `nama_pengirim` varchar(100) NOT NULL,
  `jumlah_transfer` int NOT NULL,
  `tanggal_transfer` date NOT NULL,
  `catatan` text,
  `status_verifikasi` enum('pending','diterima','ditolak','kadaluarsa') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'pending',
  `catatan_admin` text,
  `uploaded_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `verified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_bukti_pendaftaran` (`pendaftaran_id`),
  CONSTRAINT `fk_bukti_pendaftaran` FOREIGN KEY (`pendaftaran_id`) REFERENCES `pendaftaran` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bukti_pembayaran`
--

LOCK TABLES `bukti_pembayaran` WRITE;
/*!40000 ALTER TABLE `bukti_pembayaran` DISABLE KEYS */;
INSERT INTO `bukti_pembayaran` VALUES (1,1,'1_1748734200.jpg','Agus Santoso',480000,'2026-06-01','Transfer BCA via m-banking','diterima','Bukti valid, pembayaran diterima.','2026-06-01 13:20:00','2026-06-01 14:05:00'),(2,2,'2_1748907000.jpg','Heri Rahayu',700000,'2026-06-03','Transfer Mandiri','diterima','Bukti valid, pembayaran diterima.','2026-06-03 15:10:00','2026-06-03 16:30:00'),(3,3,'3_1749081600.jpg','Eko Pratama',600000,'2026-06-05','Transfer BNI via ATM','diterima','Bukti valid, pembayaran diterima.','2026-06-05 10:45:00','2026-06-05 11:20:00'),(4,4,'4_1749254400.jpg','Rudi Wijaya',900000,'2026-06-07','Transfer BSI via mobile banking','diterima','Bukti valid, pembayaran diterima.','2026-06-07 12:00:00','2026-06-07 13:15:00'),(8,8,'8_1782484200.jpeg','Beryl',700000,'2026-06-26','Transfer via BCA (data fiktif untuk pengujian)','diterima','Pembayaran terverifikasi.','2026-06-26 14:30:00','2026-06-26 16:05:00'),(9,9,'9_1778838000.jpeg','Caecil',480000,'2026-05-15','Transfer via BCA (data fiktif untuk pengujian)','diterima','Pembayaran terverifikasi.','2026-05-15 09:40:00','2026-05-15 11:20:00'),(10,10,'10_1772532900.jpeg','Keisha',700000,'2026-03-03','Transfer via BCA (data fiktif untuk pengujian)','diterima','Pembayaran terverifikasi.','2026-03-03 10:15:00','2026-03-03 13:40:00'),(11,11,'11_1757157900.jpeg','Naomi',480000,'2025-09-06','Transfer via BCA (data fiktif untuk pengujian)','diterima','Pembayaran terverifikasi.','2025-09-06 11:25:00','2025-09-06 14:00:00'),(12,12,'12_1736418600.jpeg','Khaidar',480000,'2025-01-09','Transfer via BCA (data fiktif untuk pengujian)','diterima','Pembayaran terverifikasi.','2025-01-09 10:30:00','2025-01-09 13:15:00'),(13,13,'13_1781605800.jpeg','Resik',400000,'2026-06-16','Transfer via BCA (data fiktif untuk pengujian)','diterima','Pembayaran terverifikasi.','2026-06-16 10:30:00','2026-06-16 13:15:00'),(14,14,'14_1782383400.jpeg','Cedric',480000,'2026-06-25','Transfer via BCA (data fiktif untuk pengujian)','diterima','Pembayaran terverifikasi.','2026-06-25 10:30:00','2026-06-25 13:15:00'),(15,15,'15_1779532200.jpeg','Gendis',400000,'2026-05-23','Transfer via BCA (data fiktif untuk pengujian)','diterima','Pembayaran terverifikasi.','2026-05-23 10:30:00','2026-05-23 13:15:00'),(16,16,'16_1772965800.jpeg','Damma',480000,'2026-03-08','Transfer via BCA (data fiktif untuk pengujian)','diterima','Pembayaran terverifikasi.','2026-03-08 10:30:00','2026-03-08 13:15:00');
/*!40000 ALTER TABLE `bukti_pembayaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jadwal`
--

DROP TABLE IF EXISTS `jadwal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jadwal` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pendaftaran_id` int NOT NULL,
  `pengajar_id` int NOT NULL,
  `hari` varchar(15) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `jumlah_pertemuan` int NOT NULL DEFAULT '8',
  `pertemuan_selesai` int NOT NULL DEFAULT '0',
  `status` enum('aktif','selesai','dibatalkan') NOT NULL DEFAULT 'aktif',
  `catatan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pendaftaran_id` (`pendaftaran_id`),
  KEY `pengajar_id` (`pengajar_id`),
  CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`pendaftaran_id`) REFERENCES `pendaftaran` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jadwal_ibfk_2` FOREIGN KEY (`pengajar_id`) REFERENCES `pengajar` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jadwal`
--

LOCK TABLES `jadwal` WRITE;
/*!40000 ALTER TABLE `jadwal` DISABLE KEYS */;
INSERT INTO `jadwal` VALUES (5,4,4,'Senin','00:09:00','00:10:00',8,0,'aktif',NULL,'2026-07-27 17:08:22'),(6,8,8,'Rabu','10:00:00','11:00:00',8,2,'aktif','Pertemuan ke-2 (Rabu, 08 Juli 2026):\n- Reading dan Speaking tentang Self Development\n- Grammar tentang Simple Present, Present Continuous dan Simple Past','2026-06-27 01:00:00'),(7,9,5,'Kamis','11:05:00','12:05:00',8,8,'selesai','Pertemuan ke-7 & 8 (Kamis, 2 Juli 2026):\n- Membaca ensiklopedia mini tubuh manusia, tema otot\n- Menulis dan membaca \'I want to\' (verb Bahasa Inggris)\n- Melingkari profesi yang sesuai','2026-05-16 01:00:00'),(8,10,7,'Senin','09:00:00','11:00:00',8,6,'aktif','Pertemuan ke-5 & 6 (Senin, 06 April 2026):\n- Review Fisika bab Pengukuran\nDurasi: pertemuan ke-5 (1 jam 30 menit) + pertemuan ke-6 (30 menit)','2026-03-04 01:00:00'),(9,11,10,'Sabtu','10:00:00','11:00:00',8,1,'aktif','Pertemuan ke-1 (Sabtu, 13 September 2025):\n- Matematika (MTK)\n- PKn','2025-09-08 01:00:00'),(10,12,7,'Selasa','13:55:00','14:44:00',8,4,'aktif','Pertemuan ke-4 (Selasa, 04 Februari 2025):\n- Matematika (Perkalian)','2025-01-09 08:00:00'),(11,13,10,'Senin','19:15:00','20:15:00',8,4,'aktif','Pertemuan ke-4 (Senin, 13 Juli 2026) - kontrak belajar baru:\n- Fun Learning with Shameeka (Crazy Face Puzzle | Gunting tempel)','2026-06-16 08:00:00'),(12,14,8,'Rabu','09:00:00','10:00:00',8,2,'aktif','Pertemuan ke-2 (Rabu, 08 Juli 2026):\n- Present Continuous\n- Sesi Speaking dan Reading','2026-06-25 08:00:00'),(13,15,5,'Jumat','18:48:00','19:48:00',8,5,'aktif','Pertemuan ke-5 (Jumat, 26 Juni 2026):\n- Membaca, mengaji, menulis huruf Ha (Ø­)\n- Menghafal adab bangun tidur: 1) bangun di awal subuh, 2) membaca doa bangun tidur\n- Muroja\'ah mahfudzhot\n- Menggunting dan menempel','2026-05-23 08:00:00'),(14,16,9,'Sabtu','08:00:00','09:00:00',8,8,'selesai','Pertemuan ke-8 (Sabtu, 02 Mei 2026):\n- Matematika: mengulang materi di buku paket menggunakan kuis\n- Menghafal perkalian','2026-03-08 08:00:00');
/*!40000 ALTER TABLE `jadwal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paket`
--

DROP TABLE IF EXISTS `paket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `paket` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tingkat` enum('TK','SD','SMP','SMA') NOT NULL,
  `tipe_kelas` enum('Privat','Kelompok') NOT NULL,
  `durasi_menit` int NOT NULL,
  `jumlah_pertemuan` int NOT NULL DEFAULT '8',
  `harga` int NOT NULL,
  `is_aktif` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paket`
--

LOCK TABLES `paket` WRITE;
/*!40000 ALTER TABLE `paket` DISABLE KEYS */;
INSERT INTO `paket` VALUES (1,'SD','Privat',60,8,480000,1),(2,'SD','Privat',90,12,700000,1),(3,'SD','Kelompok',60,8,320000,1),(4,'SMP','Privat',90,8,600000,1),(5,'SMP','Privat',120,12,900000,1),(6,'SMP','Kelompok',90,8,400000,1),(7,'SMA','Privat',90,8,700000,1),(8,'SMA','Privat',120,12,1050000,1),(9,'SMA','Kelompok',90,8,480000,1),(10,'TK','Privat',60,8,400000,1),(11,'TK','Kelompok',60,8,280000,1);
/*!40000 ALTER TABLE `paket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pendaftaran`
--

DROP TABLE IF EXISTS `pendaftaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pendaftaran` (
  `id` int NOT NULL AUTO_INCREMENT,
  `no_transaksi` varchar(25) DEFAULT NULL,
  `user_id` int NOT NULL,
  `paket_id` int NOT NULL,
  `jadwal_hari` varchar(15) NOT NULL,
  `jadwal_jam` time NOT NULL,
  `tanggal_daftar` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `no_transaksi` (`no_transaksi`),
  KEY `fk_user` (`user_id`),
  KEY `fk_paket` (`paket_id`),
  CONSTRAINT `fk_paket` FOREIGN KEY (`paket_id`) REFERENCES `paket` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pendaftaran`
--

LOCK TABLES `pendaftaran` WRITE;
/*!40000 ALTER TABLE `pendaftaran` DISABLE KEYS */;
INSERT INTO `pendaftaran` VALUES (1,'FASE-20260601-00001',2,1,'Senin','15:30:00','2026-06-01 09:15:00'),(2,'FASE-20260603-00001',3,2,'Rabu','15:00:00','2026-06-03 10:30:00'),(3,'FASE-20260605-00001',4,4,'Kamis','16:00:00','2026-06-05 08:45:00'),(4,'FASE-20260607-00001',5,5,'Sabtu','09:00:00','2026-06-07 11:00:00'),(8,'FASE-20260625-00001',8,7,'Rabu','10:00:00','2026-06-25 09:20:00'),(9,'FASE-20260514-00001',9,1,'Kamis','11:05:00','2026-05-14 10:05:00'),(10,'FASE-20260302-00001',10,7,'Senin','09:00:00','2026-03-02 08:35:00'),(11,'FASE-20250905-00001',11,1,'Sabtu','10:00:00','2025-09-05 09:15:00'),(12,'FASE-20250108-00001',12,1,'Selasa','13:55:00','2025-01-08 09:10:00'),(13,'FASE-20260615-00001',13,10,'Senin','19:15:00','2026-06-15 09:10:00'),(14,'FASE-20260624-00001',14,1,'Rabu','09:00:00','2026-06-24 09:10:00'),(15,'FASE-20260522-00001',15,10,'Jumat','18:48:00','2026-05-22 09:10:00'),(16,'FASE-20260307-00001',16,1,'Sabtu','08:00:00','2026-03-07 09:10:00');
/*!40000 ALTER TABLE `pendaftaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengajar`
--

DROP TABLE IF EXISTS `pengajar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengajar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(150) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `no_wa` varchar(20) DEFAULT NULL,
  `tingkat_diajar` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_pengajar_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengajar`
--

LOCK TABLES `pengajar` WRITE;
/*!40000 ALTER TABLE `pengajar` DISABLE KEYS */;
INSERT INTO `pengajar` VALUES (4,'Afifah Khoerani','afifah','$2y$10$mH5Qso4gIqmsiKclKFtVS.RwBtVgo00YmQbza.essMu3YIzBpge0G','81234567891','TK,SD','2026-07-19 02:32:59'),(5,'Laila Nurul Hanifah','laila','$2y$10$2I2BrZGdvboNYf0CaPhqReiZm6avKdWCTRefNWXBagQvlojN.pSee','85678901234','TK,SD','2026-07-19 02:32:59'),(6,'Inggia',NULL,NULL,'89012345678','TK,SD,SMP,SMA','2026-07-19 02:32:59'),(7,'Ulil Hikmah Pitasari','ulil','$2y$10$nG9wkmnFx1TlCc8wYnNwbO4GynNH8xK2i43iZTOBGkxZ/yfkTPpiq','81355667788','SD,SMP,SMA','2026-08-06 14:22:46'),(8,'Widia Aurina Machmud','widia','$2y$10$TBaocq90WGwJldrYhxVxH.SsVcEzM3q.CZN9VjPp3coaG4J7gzA5S','81244556677','SD,SMP,SMA','2026-08-06 14:27:36'),(9,'Hazimatul Fathinah Az Zahra','hazimatul','$2y$10$AmXZ5rVkpM5K21/Fwkduqu3Luq.Tdqlqhi86FAVyUwEEU3zfzt7aO','81377889900','SD','2026-08-06 14:31:33'),(10,'Mega Rosalia','mega','$2y$10$.sTylfDEvYr0ySZXX4ZX2uVwpD9lJQHBvQoConO1EjpcbY86Ec6ce','81466778899','TK,SD','2026-08-06 14:32:34');
/*!40000 ALTER TABLE `pengajar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` varchar(15) DEFAULT NULL,
  `alamat` text,
  `asal_sekolah` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nama_ortu` varchar(100) DEFAULT NULL,
  `no_wa_ortu` varchar(20) DEFAULT NULL,
  `pekerjaan_ortu` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,'budi_santoso','budi.santoso@gmail.com','$2y$10$R4LE9gn4cYA05Pt1qnxJT.4dq5kInIRNLZRiEjT7AEoyf1sD53nm6','2026-06-22 21:45:15','Budi Santoso','Subang','2016-09-10','L','Jl. Veteran No. 12, Nagritengah, Purwakarta','SDN 1 Nagritengah','Agus Santoso','081234567801','Karyawan Swasta'),(3,'siti_rahayu','siti.rahayu@gmail.com','$2y$10$R4LE9gn4cYA05Pt1qnxJT.4dq5kInIRNLZRiEjT7AEoyf1sD53nm6','2026-06-22 21:45:15','Siti Rahayu','Karawang','2014-12-03','P','Jl. Ibrahim Singadilaga No. 7, Nagrikaler, Purwakarta','SDN 5 Purwakarta','Heri Rahayu','081234567802','Guru SD'),(4,'dimas_pratama','dimas.pratama@gmail.com','$2y$10$R4LE9gn4cYA05Pt1qnxJT.4dq5kInIRNLZRiEjT7AEoyf1sD53nm6','2026-06-22 21:45:15','Dimas Pratama','Bandung','2012-07-18','L','Jl. RE Martadinata No. 22, Purwamekar, Purwakarta','SMP Negeri 1 Purwakarta','Eko Pratama','081234567803','Wiraswasta'),(5,'anisa_putri','anisa.putri@gmail.com','$2y$10$R4LE9gn4cYA05Pt1qnxJT.4dq5kInIRNLZRiEjT7AEoyf1sD53nm6','2026-06-22 21:45:15','Anisa Putri Wijaya','Purwakarta','2010-10-25','P','Jl. Gandanegara No. 5, Ciseureuh, Purwakarta','SMP Negeri 2 Purwakarta','Rudi Wijaya','081234567804','PNS'),(8,'beryl','beryl@gmail.com','$2y$10$7D/ndCJWJQlCRzZLyZRbHOcgpbkIxMcWetRamksUBpM56UL4HYwJS','2026-06-25 09:15:00','Beryl',NULL,NULL,NULL,NULL,'SMA',NULL,NULL,NULL),(9,'caecil','caecil@gmail.com','$2y$10$aCLw9J2QmATBFvgLiq9qAuQqe0PuDNdZYttKj7KP4BUuFPn.XymlK','2026-05-14 10:00:00','Caecil',NULL,NULL,NULL,NULL,'SD',NULL,NULL,NULL),(10,'keisha','keisha@gmail.com','$2y$10$5ABnXC1Gj2EwYDHVBtJrquYVdzuO.k4m9PFoahEOeniaKiS2hcmWG','2026-03-02 08:30:00','Keisha',NULL,NULL,NULL,NULL,'SMA',NULL,NULL,NULL),(11,'naomi','naomi@gmail.com','$2y$10$Ni.v7pWVQ.K.oSW0Ez5TXeLVYxjfWaPLNAbgDoIWx/3R2xTNEaDia','2025-09-05 09:10:00','Naomi',NULL,NULL,NULL,NULL,'SD',NULL,NULL,NULL),(12,'khaidar','khaidar@gmail.com','$2y$10$nFiwwDjKtbOM56sFRV6.I.ckzKnFGEzgBt0u6DUEGTO3R5wJui4WW','2025-01-08 09:00:00','Khaidar',NULL,NULL,NULL,NULL,'SD',NULL,NULL,NULL),(13,'resik','resik@gmail.com','$2y$10$wBScvADkeQ.0q30jRdeXl.kJl6dt3MVg.Lb3xaN5Yrg8FO2R4pq56','2026-06-15 09:00:00','Resik',NULL,NULL,NULL,NULL,'TK',NULL,NULL,NULL),(14,'cedric','cedric@gmail.com','$2y$10$Wak1TUIOUZg4TM0l5/M5ruf56xIB73dz2Cw8u.yi4Q009iwMsX0PK','2026-06-24 09:00:00','Cedric',NULL,NULL,NULL,NULL,'SD',NULL,NULL,NULL),(15,'gendis','gendis@gmail.com','$2y$10$53xw5985DogbmYTx4YdagO0tBE2gJHPdI7BPaYNPrIeSYsIXcm2w6','2026-05-22 09:00:00','Gendis',NULL,NULL,NULL,NULL,'TK',NULL,NULL,NULL),(16,'damma','damma@gmail.com','$2y$10$T0kZBcRPHR0KaQaoh3bASOe/xXwG1OpLmRmfKQLCHExL7J.HkkRQO','2026-03-07 09:00:00','Damma',NULL,NULL,NULL,NULL,'SD',NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-06 22:04:08
