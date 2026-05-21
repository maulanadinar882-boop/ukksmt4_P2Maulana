-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 20, 2026 at 11:30 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_parkiran`
--

-- --------------------------------------------------------

--
-- Table structure for table `shift`
--

CREATE TABLE `shift` (
  `id_shift` int NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_keluar` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `shift`
--

INSERT INTO `shift` (`id_shift`, `jam_masuk`, `jam_keluar`) VALUES
(1, '07:00:00', '15:00:00'),
(2, '15:00:00', '23:00:00'),
(3, '23:00:00', '07:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tb_appeal`
--

CREATE TABLE `tb_appeal` (
  `id_appeal` int NOT NULL,
  `id_user` int NOT NULL,
  `judul` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `status` enum('pending','diproses','selesai','ditolak') DEFAULT 'pending',
  `balasan` text,
  `dibalas_oleh` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_appeal`
--

INSERT INTO `tb_appeal` (`id_appeal`, `id_user`, `judul`, `deskripsi`, `status`, `balasan`, `dibalas_oleh`, `created_at`, `updated_at`) VALUES
(1, 3, 'Perbaikan sistem', 'Rusak', 'selesai', 'sudah selesai', 1, '2026-04-26 11:48:03', '2026-04-26 11:52:13'),
(2, 3, 'Rusak', 'Parkiran A tolong di benarkan karena rusak', 'selesai', 'Sudah di benarkan', 1, '2026-04-26 12:11:55', '2026-04-26 12:12:22');

-- --------------------------------------------------------

--
-- Table structure for table `tb_area_parkir`
--

CREATE TABLE `tb_area_parkir` (
  `id_area` int NOT NULL,
  `nama_area` varchar(50) NOT NULL,
  `kapasitas` int NOT NULL,
  `terisi` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_area_parkir`
--

INSERT INTO `tb_area_parkir` (`id_area`, `nama_area`, `kapasitas`, `terisi`) VALUES
(1, 'Area A (Motor)', 50, 0),
(2, 'Area B (Mobil)', 30, 0),
(3, 'Area C (Truk)', 10, 0),
(5, 'Area D (Pesawat)', 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_kendaraan`
--

CREATE TABLE `tb_kendaraan` (
  `id_kendaraan` int NOT NULL,
  `plat_nomor` varchar(20) NOT NULL,
  `jenis_kendaraan` varchar(30) NOT NULL,
  `warna` varchar(30) DEFAULT NULL,
  `pemilik` varchar(100) DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_kendaraan`
--

INSERT INTO `tb_kendaraan` (`id_kendaraan`, `plat_nomor`, `jenis_kendaraan`, `warna`, `pemilik`, `id_user`, `updated_at`, `deleted_at`) VALUES
(5, 'H 9999 ZA', 'Truk', 'Kuning', 'Bapa Gandi', 4, '2026-05-20 11:09:22', NULL),
(6, 'Z 2089 KZ', 'Motor', 'Merah Marun', 'Dinar', 4, '2026-05-20 11:11:28', NULL),
(7, 'D 2093 PL', 'Motor', 'Hitam', 'Farhan', 4, '2026-05-20 11:12:07', NULL),
(8, 'K 1805 WN', 'Motor', 'Biru tua', 'Gaza', 4, '2026-05-20 11:12:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_log_aktivitas`
--

CREATE TABLE `tb_log_aktivitas` (
  `id_log` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `aktivitas` text NOT NULL,
  `waktu_aktivitas` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_log_aktivitas`
--

INSERT INTO `tb_log_aktivitas` (`id_log`, `id_user`, `aktivitas`, `waktu_aktivitas`) VALUES
(1, 1, 'Login ke sistem', '2026-04-22 11:44:25'),
(2, 1, 'Logout dari sistem', '2026-04-22 11:44:55'),
(3, 1, 'Login ke sistem', '2026-04-22 11:47:24'),
(4, 1, 'Logout dari sistem', '2026-04-22 11:47:39'),
(5, NULL, 'Login ke sistem', '2026-04-22 11:48:00'),
(6, NULL, 'Logout dari sistem', '2026-04-22 11:48:22'),
(7, 3, 'Login ke sistem', '2026-04-22 11:48:38'),
(8, 3, 'Logout dari sistem', '2026-04-22 11:52:50'),
(9, 3, 'Login ke sistem', '2026-04-22 12:03:50'),
(10, 3, 'Logout dari sistem', '2026-04-22 12:03:56'),
(11, 3, 'Login ke sistem', '2026-04-22 12:06:37'),
(12, 3, 'Logout dari sistem', '2026-04-22 12:07:49'),
(13, 3, 'Login ke sistem', '2026-04-22 12:07:54'),
(14, 3, 'Logout dari sistem', '2026-04-22 12:08:09'),
(15, NULL, 'Login ke sistem', '2026-04-22 12:08:37'),
(16, NULL, 'Logout dari sistem', '2026-04-22 12:08:53'),
(17, 1, 'Login ke sistem', '2026-04-22 12:10:18'),
(18, 1, 'Mengupdate user ID: 3', '2026-04-22 12:23:24'),
(19, 1, 'Mengupdate user ID: 3', '2026-04-22 12:23:54'),
(20, 1, 'Menonaktifkan user: owner', '2026-04-22 12:27:04'),
(21, 1, 'Mengaktifkan user: owner', '2026-04-22 12:27:08'),
(22, 1, 'Mengupdate user ID: 2', '2026-04-22 12:36:59'),
(23, 1, 'Mengupdate user ID: 1', '2026-04-22 12:37:07'),
(24, 1, 'Mengupdate user ID: 1', '2026-04-22 12:37:12'),
(25, 1, 'Logout dari sistem', '2026-04-22 12:37:18'),
(26, 3, 'Login ke sistem', '2026-04-22 12:37:30'),
(27, 3, 'Logout dari sistem', '2026-04-22 12:37:45'),
(28, 3, 'Login ke sistem', '2026-04-22 12:39:11'),
(29, 3, 'Logout dari sistem', '2026-04-22 12:39:29'),
(30, 1, 'Login ke sistem', '2026-04-22 12:40:28'),
(31, 1, 'Mengupdate user ID: 2', '2026-04-22 12:41:11'),
(32, 1, 'Menambah user baru: petugas', '2026-04-22 12:42:17'),
(33, 1, 'Menghapus user: petugas1', '2026-04-22 12:43:54'),
(34, 1, 'Logout dari sistem', '2026-04-22 12:44:27'),
(35, 4, 'Login ke sistem', '2026-04-22 12:44:40'),
(36, 4, 'Transaksi masuk: D 2563 ZE', '2026-04-22 12:45:42'),
(37, 4, 'Transaksi keluar: D 2563 ZE - Rp 2,000', '2026-04-22 12:46:37'),
(38, 4, 'Logout dari sistem', '2026-04-22 12:49:26'),
(39, 1, 'Login ke sistem', '2026-04-22 12:49:28'),
(40, 1, 'Logout dari sistem', '2026-04-22 12:57:37'),
(41, 3, 'Login ke sistem', '2026-04-22 12:57:49'),
(42, 3, 'Logout dari sistem', '2026-04-22 12:58:00'),
(43, 1, 'Login ke sistem', '2026-04-22 12:58:11'),
(44, 1, 'Logout dari sistem', '2026-04-22 12:58:36'),
(45, 4, 'Login ke sistem', '2026-04-22 12:58:46'),
(46, 4, 'Logout dari sistem', '2026-04-22 12:59:13'),
(47, 1, 'Login ke sistem', '2026-04-22 13:02:23'),
(48, 1, 'Logout dari sistem', '2026-04-22 13:03:16'),
(49, 4, 'Login ke sistem', '2026-04-22 13:03:27'),
(50, 4, 'Transaksi masuk: D 2563 ZE', '2026-04-22 13:03:54'),
(51, 4, 'Logout dari sistem', '2026-04-22 13:03:59'),
(52, 1, 'Login ke sistem', '2026-04-22 13:04:01'),
(53, 1, 'Mengupdate area parkir: Area A (Motor) -> Area A (Motor)', '2026-04-22 13:04:48'),
(54, 1, 'Mengupdate area parkir: Area A (Motor) -> Area A (Motor)', '2026-04-22 13:04:56'),
(55, 1, 'Logout dari sistem', '2026-04-22 13:13:09'),
(56, 4, 'Login ke sistem', '2026-04-22 13:13:23'),
(57, 4, 'Transaksi keluar: D 2563 ZE - Rp 2,000', '2026-04-22 13:13:31'),
(58, 4, 'Logout dari sistem', '2026-04-22 13:13:56'),
(59, 1, 'Login ke sistem', '2026-04-22 13:13:58'),
(60, 1, 'Login ke sistem', '2026-04-26 09:30:03'),
(61, 1, 'Mengupdate tarif Motor: Rp 2,000 -> Rp 2,500', '2026-04-26 09:46:09'),
(62, 1, 'Mengupdate tarif Motor: Rp 2,500 -> Rp 3,000', '2026-04-26 09:53:19'),
(63, 1, 'Mengupdate tarif Motor: Rp 3,000 -> Rp 2,000', '2026-04-26 09:53:29'),
(64, 1, 'Logout dari sistem', '2026-04-26 09:55:23'),
(65, 4, 'Login ke sistem', '2026-04-26 09:55:46'),
(66, 4, 'Kendaraan masuk: D 2030 DA - Area: Area A (Motor)', '2026-04-26 10:02:06'),
(67, 4, 'Kendaraan keluar: D 2030 DA - Durasi: 1 jam - Bayar: Rp 2,000', '2026-04-26 10:03:44'),
(68, 4, 'Logout dari sistem', '2026-04-26 10:32:17'),
(69, 1, 'Login ke sistem', '2026-04-26 10:32:19'),
(70, 1, 'Logout dari sistem', '2026-04-26 10:43:06'),
(71, 3, 'Login ke sistem', '2026-04-26 10:43:19'),
(72, 3, 'Logout dari sistem', '2026-04-26 10:46:39'),
(73, 1, 'Login ke sistem', '2026-04-26 10:46:42'),
(74, 1, 'Logout dari sistem', '2026-04-26 10:47:02'),
(75, 3, 'Login ke sistem', '2026-04-26 10:47:14'),
(76, 3, 'Logout dari sistem', '2026-04-26 10:47:47'),
(77, 4, 'Login ke sistem', '2026-04-26 10:47:59'),
(78, 4, 'Logout dari sistem', '2026-04-26 10:53:33'),
(79, 1, 'Login ke sistem', '2026-04-26 10:53:36'),
(80, 1, 'Logout dari sistem', '2026-04-26 11:24:45'),
(81, 4, 'Login ke sistem', '2026-04-26 11:25:03'),
(82, 4, 'Logout dari sistem', '2026-04-26 11:40:54'),
(83, 3, 'Login ke sistem', '2026-04-26 11:41:04'),
(84, 3, 'Mengajukan appeal baru: Perbaikan sistem', '2026-04-26 11:48:03'),
(85, 3, 'Logout dari sistem', '2026-04-26 11:48:47'),
(86, 1, 'Login ke sistem', '2026-04-26 11:48:49'),
(87, 1, 'Memproses appeal ID: 1 - Status: diproses', '2026-04-26 11:49:21'),
(88, 1, 'Logout dari sistem', '2026-04-26 11:49:30'),
(89, 3, 'Login ke sistem', '2026-04-26 11:49:38'),
(90, 3, 'Logout dari sistem', '2026-04-26 11:51:44'),
(91, 1, 'Login ke sistem', '2026-04-26 11:51:51'),
(92, 1, 'Memproses appeal ID: 1 - Status: selesai', '2026-04-26 11:52:13'),
(93, 1, 'Logout dari sistem', '2026-04-26 11:52:17'),
(94, 3, 'Login ke sistem', '2026-04-26 11:52:27'),
(95, 3, 'Logout dari sistem', '2026-04-26 11:56:57'),
(96, 1, 'Login ke sistem', '2026-04-26 11:56:59'),
(97, 1, 'Logout dari sistem', '2026-04-26 11:57:07'),
(98, 1, 'Login ke sistem', '2026-04-26 11:57:58'),
(99, 1, 'Logout dari sistem', '2026-04-26 11:58:03'),
(100, 3, 'Login ke sistem', '2026-04-26 11:58:12'),
(101, 3, 'Logout dari sistem', '2026-04-26 12:07:40'),
(102, 1, 'Login ke sistem', '2026-04-26 12:07:43'),
(103, 1, 'Logout dari sistem', '2026-04-26 12:10:50'),
(104, 3, 'Login ke sistem', '2026-04-26 12:11:00'),
(105, 3, 'Mengajukan appeal baru: Rusak', '2026-04-26 12:11:55'),
(106, 3, 'Logout dari sistem', '2026-04-26 12:11:58'),
(107, 1, 'Login ke sistem', '2026-04-26 12:12:01'),
(108, 1, 'Memproses appeal ID: 2 - Status: selesai', '2026-04-26 12:12:22'),
(109, 1, 'Login ke sistem', '2026-05-20 09:46:44'),
(110, 1, 'Login ke sistem', '2026-05-20 09:47:33'),
(111, 1, 'Logout dari sistem', '2026-05-20 09:52:25'),
(112, 3, 'Login ke sistem', '2026-05-20 09:52:41'),
(113, 3, 'Logout dari sistem', '2026-05-20 09:53:03'),
(114, 4, 'Login ke sistem', '2026-05-20 09:53:14'),
(115, 1, 'Menambah area parkir baru: Area D (Pesawat) (Kapasitas: 10)', '2026-05-20 09:53:52'),
(116, 4, 'Kendaraan masuk: D 2030 DA - Area: Area A (Motor)', '2026-05-20 09:59:21'),
(117, 4, 'Kendaraan keluar: D 2030 DA - Durasi: 1 jam - Bayar: Rp 2,000', '2026-05-20 10:00:10'),
(118, 1, 'Menghapus area parkir: Area D (Pesawat)', '2026-05-20 10:01:37'),
(119, 4, 'Logout dari sistem', '2026-05-20 10:02:09'),
(120, 3, 'Login ke sistem', '2026-05-20 10:02:32'),
(121, 3, 'Logout dari sistem', '2026-05-20 10:06:23'),
(122, 4, 'Login ke sistem', '2026-05-20 10:06:38'),
(123, 4, 'Kendaraan masuk: Z 1892 KS - Area: Area B (Mobil)', '2026-05-20 10:10:15'),
(124, 4, 'Kendaraan keluar: Z 1892 KS - Durasi: 1 jam - Bayar: Rp 5,000', '2026-05-20 10:10:30'),
(125, 1, 'Menonaktifkan kendaraan: Z 1892 KS', '2026-05-20 10:27:28'),
(126, 1, 'Menonaktifkan kendaraan: D 2030 DA', '2026-05-20 10:28:06'),
(127, 1, 'Menonaktifkan kendaraan: D 2563 ZE', '2026-05-20 10:29:43'),
(128, 1, 'Menghapus kendaraan: Z 1892 KS beserta riwayat transaksinya', '2026-05-20 10:33:22'),
(129, 1, 'Menghapus kendaraan: D 2030 DA beserta riwayat transaksinya', '2026-05-20 10:33:37'),
(130, 1, 'Menghapus kendaraan: D 2563 ZE beserta riwayat transaksinya', '2026-05-20 10:33:48'),
(131, 4, 'Kendaraan masuk: KA 5083 DE - Area: Area C (Truk)', '2026-05-20 10:34:32'),
(132, 4, 'Kendaraan keluar: KA 5083 DE - Durasi: 1 jam - Bayar: Rp 10,000', '2026-05-20 10:35:42'),
(133, 1, 'Menghapus kendaraan: KA 5083 DE beserta riwayat transaksinya', '2026-05-20 10:38:13'),
(134, 1, 'Menambah tarif baru: Pesawat - Rp 50,000', '2026-05-20 10:54:39'),
(135, 4, 'Logout dari sistem', '2026-05-20 10:55:55'),
(136, 4, 'Login ke sistem', '2026-05-20 10:56:06'),
(137, 1, 'Mengupdate tarif Pesawat: Rp 50,000 -> Rp 45,000', '2026-05-20 11:02:49'),
(138, 1, 'Menambah area parkir baru: Area D (Pesawat) (Kapasitas: 5)', '2026-05-20 11:03:37'),
(139, 4, 'Kendaraan masuk: H 9999 ZA (Truk) - Area: Area C (Truk)', '2026-05-20 18:09:22'),
(140, 4, 'Kendaraan masuk: Z 2089 KZ (Motor) - Area: Area A (Motor)', '2026-05-20 18:11:28'),
(141, 4, 'Kendaraan masuk: D 2093 PL (Motor) - Area: Area A (Motor)', '2026-05-20 18:12:08'),
(142, 4, 'Kendaraan masuk: K 1805 WN (Motor) - Area: Area A (Motor)', '2026-05-20 18:12:37'),
(143, 4, 'Kendaraan keluar: K 1805 WN - Durasi: 1 jam - Bayar: Rp 2,000', '2026-05-20 18:20:43'),
(144, 4, 'Kendaraan keluar: D 2093 PL - Durasi: 1 jam - Bayar: Rp 2,000', '2026-05-20 18:20:45'),
(145, 4, 'Kendaraan keluar: Z 2089 KZ - Durasi: 1 jam - Bayar: Rp 2,000', '2026-05-20 18:20:46'),
(146, 4, 'Kendaraan keluar: H 9999 ZA - Durasi: 1 jam - Bayar: Rp 10,000', '2026-05-20 18:20:49'),
(147, 1, 'Logout dari sistem', '2026-05-20 18:23:49'),
(148, 1, 'Login ke sistem', '2026-05-20 18:24:03'),
(149, 4, 'Logout dari sistem', '2026-05-20 18:24:18'),
(150, 4, 'Login ke sistem', '2026-05-20 18:24:31'),
(151, 4, 'Logout dari sistem', '2026-05-20 18:24:34'),
(152, 3, 'Login ke sistem', '2026-05-20 18:24:43');

-- --------------------------------------------------------

--
-- Table structure for table `tb_tarif`
--

CREATE TABLE `tb_tarif` (
  `id_tarif` int NOT NULL,
  `jenis_kendaraan` varchar(30) NOT NULL,
  `tarif_per_jam` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_tarif`
--

INSERT INTO `tb_tarif` (`id_tarif`, `jenis_kendaraan`, `tarif_per_jam`, `created_at`, `updated_at`) VALUES
(1, 'Motor', '2000.00', '2026-05-20 10:54:30', '2026-05-20 10:54:30'),
(2, 'Mobil', '5000.00', '2026-05-20 10:54:30', '2026-05-20 10:54:30'),
(3, 'Truk', '10000.00', '2026-05-20 10:54:30', '2026-05-20 10:54:30'),
(4, 'Pesawat', '45000.00', '2026-05-20 03:54:39', '2026-05-20 04:02:49');

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id_parkir` int NOT NULL,
  `id_kendaraan` int NOT NULL,
  `waktu_masuk` datetime NOT NULL,
  `waktu_keluar` datetime DEFAULT NULL,
  `id_tarif` int NOT NULL,
  `durasi_jam` decimal(5,2) DEFAULT NULL,
  `biaya_total` decimal(10,2) DEFAULT NULL,
  `status` enum('Masuk','Keluar','Batal') DEFAULT 'Masuk',
  `id_user` int DEFAULT NULL,
  `id_area` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_transaksi`
--

INSERT INTO `tb_transaksi` (`id_parkir`, `id_kendaraan`, `waktu_masuk`, `waktu_keluar`, `id_tarif`, `durasi_jam`, `biaya_total`, `status`, `id_user`, `id_area`) VALUES
(7, 5, '2026-05-20 18:09:22', '2026-05-20 18:20:49', 3, '1.00', '10000.00', 'Keluar', 4, 3),
(8, 6, '2026-05-20 18:11:28', '2026-05-20 18:20:46', 1, '1.00', '2000.00', 'Keluar', 4, 1),
(9, 7, '2026-05-20 18:12:07', '2026-05-20 18:20:45', 1, '1.00', '2000.00', 'Keluar', 4, 1),
(10, 8, '2026-05-20 18:12:37', '2026-05-20 18:20:43', 1, '1.00', '2000.00', 'Keluar', 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Petugas','Owner') NOT NULL,
  `status_aktif` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `nama_lengkap`, `username`, `password`, `role`, `status_aktif`) VALUES
(1, 'Admin Utama', 'admin', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 'Admin', 1),
(3, 'Pemilik Parkir', 'owner', '43a0d17178a9d26c9e0fe9a74b0b45e38d32f27aed887a008a54bf6e033bf7b9', 'Owner', 1),
(4, 'Petugas Satu', 'petugas', '2dad904f71aa0dcf6ea1addaa084a5865ffe448e4d3f900668e1cc7e7b6153d7', 'Petugas', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_shift`
--

CREATE TABLE `user_shift` (
  `id_user_shift` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `id_shift` int DEFAULT NULL,
  `tanggal` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `shift`
--
ALTER TABLE `shift`
  ADD PRIMARY KEY (`id_shift`);

--
-- Indexes for table `tb_appeal`
--
ALTER TABLE `tb_appeal`
  ADD PRIMARY KEY (`id_appeal`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `dibalas_oleh` (`dibalas_oleh`);

--
-- Indexes for table `tb_area_parkir`
--
ALTER TABLE `tb_area_parkir`
  ADD PRIMARY KEY (`id_area`);

--
-- Indexes for table `tb_kendaraan`
--
ALTER TABLE `tb_kendaraan`
  ADD PRIMARY KEY (`id_kendaraan`),
  ADD UNIQUE KEY `plat_nomor` (`plat_nomor`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `idx_kendaraan_deleted_at` (`deleted_at`);

--
-- Indexes for table `tb_log_aktivitas`
--
ALTER TABLE `tb_log_aktivitas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tb_tarif`
--
ALTER TABLE `tb_tarif`
  ADD PRIMARY KEY (`id_tarif`);

--
-- Indexes for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD PRIMARY KEY (`id_parkir`),
  ADD KEY `id_kendaraan` (`id_kendaraan`),
  ADD KEY `id_tarif` (`id_tarif`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_area` (`id_area`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_shift`
--
ALTER TABLE `user_shift`
  ADD PRIMARY KEY (`id_user_shift`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_shift` (`id_shift`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `shift`
--
ALTER TABLE `shift`
  MODIFY `id_shift` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_appeal`
--
ALTER TABLE `tb_appeal`
  MODIFY `id_appeal` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_area_parkir`
--
ALTER TABLE `tb_area_parkir`
  MODIFY `id_area` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tb_kendaraan`
--
ALTER TABLE `tb_kendaraan`
  MODIFY `id_kendaraan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tb_log_aktivitas`
--
ALTER TABLE `tb_log_aktivitas`
  MODIFY `id_log` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT for table `tb_tarif`
--
ALTER TABLE `tb_tarif`
  MODIFY `id_tarif` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  MODIFY `id_parkir` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_shift`
--
ALTER TABLE `user_shift`
  MODIFY `id_user_shift` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_appeal`
--
ALTER TABLE `tb_appeal`
  ADD CONSTRAINT `tb_appeal_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_appeal_ibfk_2` FOREIGN KEY (`dibalas_oleh`) REFERENCES `tb_user` (`id_user`) ON DELETE SET NULL;

--
-- Constraints for table `tb_kendaraan`
--
ALTER TABLE `tb_kendaraan`
  ADD CONSTRAINT `tb_kendaraan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE SET NULL;

--
-- Constraints for table `tb_log_aktivitas`
--
ALTER TABLE `tb_log_aktivitas`
  ADD CONSTRAINT `tb_log_aktivitas_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE SET NULL;

--
-- Constraints for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD CONSTRAINT `tb_transaksi_ibfk_1` FOREIGN KEY (`id_kendaraan`) REFERENCES `tb_kendaraan` (`id_kendaraan`),
  ADD CONSTRAINT `tb_transaksi_ibfk_2` FOREIGN KEY (`id_tarif`) REFERENCES `tb_tarif` (`id_tarif`),
  ADD CONSTRAINT `tb_transaksi_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`),
  ADD CONSTRAINT `tb_transaksi_ibfk_4` FOREIGN KEY (`id_area`) REFERENCES `tb_area_parkir` (`id_area`);

--
-- Constraints for table `user_shift`
--
ALTER TABLE `user_shift`
  ADD CONSTRAINT `user_shift_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`),
  ADD CONSTRAINT `user_shift_ibfk_2` FOREIGN KEY (`id_shift`) REFERENCES `shift` (`id_shift`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
