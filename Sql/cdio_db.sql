-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.42 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for cdio_db
CREATE DATABASE IF NOT EXISTS `cdio_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `cdio_db`;

-- Dumping structure for table cdio_db.admims
CREATE TABLE IF NOT EXISTS `admims` (
  `id_admin` bigint unsigned NOT NULL AUTO_INCREMENT,
  `hoten` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sdt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `diachi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `trangthai` int NOT NULL,
  `matkhau` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_quyen` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.admims: ~0 rows (approximately)

-- Dumping structure for table cdio_db.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.cache: ~0 rows (approximately)

-- Dumping structure for table cdio_db.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.cache_locks: ~0 rows (approximately)

-- Dumping structure for table cdio_db.cham_congs
CREATE TABLE IF NOT EXISTS `cham_congs` (
  `id_chamcong` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_nhanvien` bigint unsigned NOT NULL,
  `ngaylamviec` date NOT NULL,
  `giovao` time NOT NULL,
  `giora` time NOT NULL,
  `trangthai` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_chamcong`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.cham_congs: ~17 rows (approximately)
INSERT INTO `cham_congs` (`id_chamcong`, `id_nhanvien`, `ngaylamviec`, `giovao`, `giora`, `trangthai`, `created_at`, `updated_at`) VALUES
	(2, 1, '2025-12-03', '09:00:00', '17:00:00', 1, NULL, NULL),
	(3, 1, '2025-12-02', '09:03:00', '17:06:00', 1, NULL, NULL),
	(5, 1, '2025-12-01', '09:06:00', '18:00:00', 1, NULL, NULL),
	(6, 2, '2025-12-03', '09:00:00', '17:00:00', 1, NULL, NULL),
	(7, 2, '2025-12-02', '00:00:00', '00:00:00', 0, NULL, NULL),
	(8, 2, '2025-12-01', '09:00:00', '17:00:00', 1, NULL, NULL),
	(9, 3, '2025-12-03', '09:00:00', '17:00:00', 1, NULL, NULL),
	(10, 3, '2025-12-02', '00:00:00', '00:00:00', 0, NULL, NULL),
	(11, 3, '2025-12-01', '00:00:00', '00:00:00', 0, NULL, NULL),
	(12, 6, '2025-12-01', '15:13:00', '17:13:00', 1, NULL, NULL),
	(13, 7, '2025-02-11', '07:00:00', '17:00:00', 1, NULL, NULL),
	(14, 100, '2025-02-04', '07:22:00', '17:00:00', 1, NULL, NULL),
	(17, 100, '2025-02-04', '10:38:00', '02:38:00', 1, NULL, NULL),
	(18, 100, '2025-02-04', '00:00:00', '00:00:00', 0, NULL, NULL),
	(19, 29, '2025-11-07', '19:00:00', '19:00:00', 1, NULL, NULL),
	(20, 29, '2025-11-07', '07:00:00', '17:00:00', 1, NULL, NULL),
	(21, 29, '2025-11-07', '00:00:00', '00:00:00', 0, NULL, NULL);

-- Dumping structure for table cdio_db.chi_tiet_phan_quyens
CREATE TABLE IF NOT EXISTS `chi_tiet_phan_quyens` (
  `id_chitietphanquyen` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_quyen` bigint unsigned NOT NULL,
  `id_chucnang` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_chitietphanquyen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.chi_tiet_phan_quyens: ~0 rows (approximately)

-- Dumping structure for table cdio_db.chuc_nangs
CREATE TABLE IF NOT EXISTS `chuc_nangs` (
  `id_chucnang` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenchucnang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_chucnang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.chuc_nangs: ~0 rows (approximately)

-- Dumping structure for table cdio_db.chuc_vus
CREATE TABLE IF NOT EXISTS `chuc_vus` (
  `id_chucvu` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenchucvu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hesoluong` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_chucvu`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.chuc_vus: ~6 rows (approximately)
INSERT INTO `chuc_vus` (`id_chucvu`, `tenchucvu`, `hesoluong`, `created_at`, `updated_at`) VALUES
	(1, 'Nhân viên', 20000, NULL, NULL),
	(2, 'Quản lý', 40000, NULL, NULL),
	(3, 'Trưởng phòng', 60000, NULL, NULL),
	(4, 'Giám đốc', 100000, NULL, NULL),
	(5, 'Quản lý Dự án', 20000, NULL, NULL),
	(6, 'Quản lý Dự án 1', 200, NULL, NULL);

-- Dumping structure for table cdio_db.cong_viecs
CREATE TABLE IF NOT EXISTS `cong_viecs` (
  `id_congviec` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tieude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mota` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ngaybatdau` date NOT NULL,
  `ngayketthuc` date NOT NULL,
  `trangthai` int NOT NULL,
  `id_nhanvien` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_congviec`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.cong_viecs: ~0 rows (approximately)

-- Dumping structure for table cdio_db.danh_gias
CREATE TABLE IF NOT EXISTS `danh_gias` (
  `id_danhgia` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_nhanvien` bigint unsigned NOT NULL,
  `thang` int NOT NULL,
  `nam` int NOT NULL,
  `diemdanhgia` int NOT NULL,
  `nhanxet` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_danhgia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.danh_gias: ~0 rows (approximately)

-- Dumping structure for table cdio_db.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table cdio_db.hop_dongs
CREATE TABLE IF NOT EXISTS `hop_dongs` (
  `id_hopdong` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_nhanvien` bigint unsigned NOT NULL,
  `loaihopdong` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ngaybatdau` date NOT NULL,
  `ngayketthuc` date DEFAULT NULL,
  `trangthai` int NOT NULL,
  `luongcoban` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_hopdong`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.hop_dongs: ~0 rows (approximately)

-- Dumping structure for table cdio_db.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.jobs: ~0 rows (approximately)

-- Dumping structure for table cdio_db.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.job_batches: ~0 rows (approximately)

-- Dumping structure for table cdio_db.luongs
CREATE TABLE IF NOT EXISTS `luongs` (
  `id_luong` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_nhanvien` bigint unsigned NOT NULL,
  `thang` int NOT NULL,
  `nam` int NOT NULL,
  `luongcoban` double NOT NULL,
  `tongphucap` double NOT NULL,
  `tongthuong` double NOT NULL,
  `tongphat` double NOT NULL,
  `thue` double NOT NULL,
  `tongluong` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_luong`)
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.luongs: ~134 rows (approximately)
INSERT INTO `luongs` (`id_luong`, `id_nhanvien`, `thang`, `nam`, `luongcoban`, `tongphucap`, `tongthuong`, `tongphat`, `thue`, `tongluong`, `created_at`, `updated_at`) VALUES
	(1, 1, 12, 2025, 499000, 2500000, 0, 0, 149950, 2849050, NULL, NULL),
	(2, 2, 12, 2025, 960000, 2450000, 0, 0, 170500, 3239500, NULL, NULL),
	(3, 3, 12, 2025, 800000, 5000000, 0, 0, 580000, 5220000, NULL, NULL),
	(4, 4, 12, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(5, 5, 12, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(6, 6, 12, 2025, 200000, 0, 0, 0, 10000, 190000, NULL, NULL),
	(7, 8, 12, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(8, 9, 12, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(9, 12, 12, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(10, 14, 12, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(11, 15, 12, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(12, 19, 12, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(13, 20, 12, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(14, 21, 12, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(15, 22, 12, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(16, 23, 12, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(17, 27, 12, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(18, 28, 12, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(19, 30, 12, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(20, 1, 5, 2025, 0, 2500000, 50000, 0, 127500, 2422500, NULL, NULL),
	(21, 2, 5, 2025, 0, 2450000, 0, 50000, 120000, 2280000, NULL, NULL),
	(22, 3, 5, 2025, 0, 5000000, 100000, 0, 510000, 4590000, NULL, NULL),
	(23, 4, 5, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(24, 5, 5, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(25, 6, 5, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(26, 8, 5, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(27, 9, 5, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(28, 12, 5, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(29, 14, 5, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(30, 15, 5, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(31, 19, 5, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(32, 20, 5, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(33, 21, 5, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(34, 22, 5, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(35, 23, 5, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(36, 27, 5, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(37, 28, 5, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(38, 30, 5, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(39, 1, 10, 2025, 0, 2500000, 0, 0, 125000, 2375000, NULL, NULL),
	(40, 2, 10, 2025, 0, 2450000, 0, 0, 122500, 2327500, NULL, NULL),
	(41, 3, 10, 2025, 0, 5000000, 0, 0, 250000, 4750000, NULL, NULL),
	(42, 4, 10, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(43, 5, 10, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(44, 6, 10, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(45, 8, 10, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(46, 9, 10, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(47, 12, 10, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(48, 14, 10, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(49, 15, 10, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(50, 19, 10, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(51, 20, 10, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(52, 21, 10, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(53, 22, 10, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(54, 23, 10, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(55, 27, 10, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(56, 28, 10, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(57, 30, 10, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(58, 28, 11, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(59, 1, 11, 2025, 0, 2500000, 0, 0, 125000, 2375000, NULL, NULL),
	(60, 2, 11, 2025, 0, 2450000, 0, 0, 122500, 2327500, NULL, NULL),
	(61, 3, 11, 2025, 0, 5000000, 0, 0, 250000, 4750000, NULL, NULL),
	(62, 4, 11, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(63, 5, 11, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(64, 6, 11, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(65, 8, 11, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(66, 9, 11, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(67, 12, 11, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(68, 14, 11, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(69, 15, 11, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(70, 19, 11, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(71, 20, 11, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(72, 21, 11, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(73, 22, 11, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(74, 23, 11, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(75, 27, 11, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(76, 30, 11, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(77, 1, 9, 2025, 0, 2500000, 0, 0, 125000, 2375000, NULL, NULL),
	(78, 2, 9, 2025, 0, 2450000, 0, 0, 122500, 2327500, NULL, NULL),
	(79, 3, 9, 2025, 0, 5000000, 0, 0, 250000, 4750000, NULL, NULL),
	(80, 4, 9, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(81, 5, 9, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(82, 6, 9, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(83, 8, 9, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(84, 9, 9, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(85, 12, 9, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(86, 14, 9, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(87, 15, 9, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(88, 19, 9, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(89, 20, 9, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(90, 21, 9, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(91, 22, 9, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(92, 23, 9, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(93, 27, 9, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(94, 28, 9, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(95, 30, 9, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(96, 28, 3, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(97, 3, 3, 2025, 0, 5000000, 0, 0, 250000, 4750000, NULL, NULL),
	(98, 6, 3, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(99, 22, 3, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(100, 3, 3, 2024, 0, 5000000, 0, 0, 250000, 4750000, NULL, NULL),
	(101, 6, 3, 2024, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(102, 22, 3, 2024, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(103, 3, 1, 2024, 0, 5000000, 0, 0, 250000, 4750000, NULL, NULL),
	(104, 6, 1, 2024, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(105, 22, 1, 2024, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(106, 3, 1, 2025, 0, 5000000, 0, 0, 250000, 4750000, NULL, NULL),
	(107, 6, 1, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(108, 22, 1, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(109, 1, 1, 2025, 0, 2500000, 0, 0, 125000, 2375000, NULL, NULL),
	(110, 5, 1, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(111, 8, 1, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(112, 9, 1, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(113, 14, 1, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(114, 19, 1, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(115, 23, 1, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(116, 28, 1, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(117, 30, 1, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(118, 2, 1, 2025, 0, 2450000, 0, 0, 122500, 2327500, NULL, NULL),
	(119, 4, 1, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(120, 12, 1, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(121, 15, 1, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(122, 20, 1, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(123, 21, 1, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(124, 27, 1, 2025, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(125, 1, 6, 2026, 499000, 2500000, 0, 0, 149950, 2849050, NULL, NULL),
	(126, 2, 6, 2026, 960000, 2450000, 0, 0, 170500, 3239500, NULL, NULL),
	(127, 3, 6, 2026, 800000, 5000000, 0, 0, 580000, 5220000, NULL, NULL),
	(128, 4, 6, 2026, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(129, 5, 6, 2026, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(130, 6, 6, 2026, 200000, 0, 0, 0, 10000, 190000, NULL, NULL),
	(131, 8, 6, 2026, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(132, 9, 6, 2026, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(133, 12, 6, 2026, 0, 0, 0, 0, 0, 0, NULL, NULL),
	(134, 14, 6, 2026, 0, 0, 0, 0, 0, 0, NULL, NULL);

-- Dumping structure for table cdio_db.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.migrations: ~22 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_05_10_154942_create_nhan_viens_table', 1),
	(5, '2025_05_10_154953_create_cong_viecs_table', 1),
	(6, '2025_05_10_155021_create_hop_dongs_table', 1),
	(7, '2025_05_10_155142_create_chuc_vus_table', 1),
	(8, '2025_05_10_155151_create_thuong_phats_table', 1),
	(9, '2025_05_10_155209_create_nghi_pheps_table', 1),
	(10, '2025_05_10_155225_create_nhan_vien_phu_caps_table', 1),
	(11, '2025_05_10_155233_create_nhan_vien_thues_table', 1),
	(12, '2025_05_10_155300_create_phu_caps_table', 1),
	(13, '2025_05_10_155309_create_thue_thu_nhaps_table', 1),
	(14, '2025_05_10_155325_create_danh_gias_table', 1),
	(15, '2025_05_10_155332_create_luongs_table', 1),
	(16, '2025_05_10_155338_create_cham_congs_table', 1),
	(17, '2025_05_10_155353_create_admims_table', 1),
	(18, '2025_05_10_155359_create_quyens_table', 1),
	(19, '2025_05_10_155411_create_chi_tiet_phan_quyens_table', 1),
	(20, '2025_05_10_155423_create_chuc_nangs_table', 1),
	(21, '2025_05_10_164340_create_phong_bans_table', 1),
	(22, '2025_05_10_175017_create_personal_access_tokens_table', 1);

-- Dumping structure for table cdio_db.nghi_pheps
CREATE TABLE IF NOT EXISTS `nghi_pheps` (
  `id_nghiphep` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_nhanvien` bigint unsigned NOT NULL,
  `loainghi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ngaybatdau` date NOT NULL,
  `ngayketthuc` date NOT NULL,
  `lydo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `trangthai` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_nghiphep`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.nghi_pheps: ~0 rows (approximately)

-- Dumping structure for table cdio_db.nhan_viens
CREATE TABLE IF NOT EXISTS `nhan_viens` (
  `id_nhanvien` bigint unsigned NOT NULL AUTO_INCREMENT,
  `hoten` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ngaysinh` date NOT NULL,
  `gioitinh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sdt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `diachi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ngayvaolam` date NOT NULL,
  `id_phongban` bigint unsigned NOT NULL,
  `id_chucvu` bigint unsigned NOT NULL,
  `trangthai` int NOT NULL,
  `matkhau` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_nhanvien`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.nhan_viens: ~19 rows (approximately)
INSERT INTO `nhan_viens` (`id_nhanvien`, `hoten`, `ngaysinh`, `gioitinh`, `email`, `sdt`, `diachi`, `ngayvaolam`, `id_phongban`, `id_chucvu`, `trangthai`, `matkhau`, `created_at`, `updated_at`) VALUES
	(1, 'Nguyễn Văn A', '2004-10-07', 'nam', 'hoangxinhtrai2004@gmail.com', '0909123456', '123 Lý Thường Kiệt', '2020-10-07', 45, 1, 1, '$2y$10$driGEb0qwBwQXv/nNgzOl.t4QoEEM3Bv0TPVuo8tNDmaQlsrMZj62', NULL, NULL),
	(2, 'Bùi Thanh Minh Hoàng', '2000-11-11', 'nam', 'hoangbuibaka@gmail.com', '0912345432', '111 Pastuer', '2021-12-12', 40, 3, 1, '$2y$10$GyIuu68bWtzCozoDC0Gv5uSnoAZbGT.qrrVwcv7Vps28Jl7wq8MxO', NULL, NULL),
	(3, 'Trần Thiên Linh', '1997-12-30', 'nữ', 'linhparadise@gmail.com', '08796564532', '9 Hoàng Diệu', '2016-04-28', 47, 4, 1, '$2y$10$V5qO31IR6M3FN7lCCEqggepuzlwsEjFQ77dkYBtF/etwUXn3QLpQW', NULL, NULL),
	(4, 'Michael Kaiser', '1980-01-02', 'nam', 'kaiserterminater@gmail.com', '0789034516', '1011 Trần Cao Vân', '2003-05-04', 4, 2, 0, '1123', NULL, NULL),
	(5, 'ABC', '2000-10-24', 'nữ', 'ABC2020@gmail.com', '078956743', '999 Lê Lợi', '2023-10-24', 2, 1, 1, '$2y$10$J4/KEAraWwcBct1w5L2o6.JK0pklrJAr7M1ydp1ST3x7H.VFqY1BK', NULL, NULL),
	(6, 'bcd', '2025-05-01', 'nữ', 'bcd@gmail.com', '0989765678', '12313 Lê Duẩn', '2025-05-09', 2, 4, 1, '$2y$10$4vWci5t439hWhlicYQlYA.4/zL7KLLR7jozw9APt.kb05v8InAtWe', NULL, NULL),
	(8, 'Nguyễn Văn B', '1999-01-01', 'nam', 'abc@gmail.com', '0123456789', 'Đà Nẵng', '2015-02-01', 2, 1, 1, '$2y$10$7pA2T5crAYRxptLTl2T7wOGZLsPYY8DWbQX8LBs3RjE/szchbGLXq', NULL, NULL),
	(9, 'zzz', '1999-11-11', 'nam', 'f@gmail.com', '0123456789', 'test111@gmail.com', '1999-01-01', 2, 1, 1, '$2y$10$iK4r9Xtcnox18D.P67nrreaLG78Rj2gNS3HdZ8HggZSqUh3DE2PGK', NULL, NULL),
	(12, 'hoangancut', '1999-01-01', 'nam', 'm@gmail.com', '027868a789', 'sgb@gmail.com', '2000-01-01', 2, 2, 1, '$2y$10$0OTrQDmIVySfkK8FtSHBMeQ0770/GtVtnXR2mdv84Nl0KmoKNYNb.', NULL, NULL),
	(14, 'Đức gay go', '1999-01-01', 'nam', 'f@gmail.com', '0312564534', 'test@gmail.com', '2020-01-01', 40, 1, 1, '$2y$10$YwD5htEcdUuHtEKn5.v7K.bZSR4aOBp2bIINck1yvlUNvA5Ahndau', NULL, NULL),
	(15, 'ádas', '1997-01-01', 'nam', 'f@gmail.com', '0111354535', 'kk', '1115-01-01', 2, 3, 1, '$2y$10$Ji92/tofi//roghtiZOv7OHGPeUbdIHD0xoIa3gzWAgWdEEmFcVpa', NULL, NULL),
	(19, 'a', '2025-11-01', 'nam', 'ngo@m', '02093202', '294 nhqncq', '2025-11-02', 22, 1, 1, '$2y$10$.LnHkKYOKRsi2bjzWuFEMODw2WM88UpN3hEXWY449vIOe8f3s05C6', NULL, NULL),
	(20, 'Phan Tuấn Minh', '2001-03-10', 'nam', 'minh@mai', '0293283838', 'nknfkw9', '2025-11-16', 2, 2, 1, '$2y$10$s4bv/31l9OltipSGhmFo3uOkq..H3AH3wRV5H7kCUqeYwi4EU32WO', NULL, NULL),
	(21, 'Phan Tuấn Minh', '2001-03-10', 'nữ', 'minh@mfajia', '029402', 'i32inv jksfj', '2025-11-01', 20, 2, 1, '$2y$10$NbCHhP3HWlmg5t2l1S8dG.kWxUNDXXt2r3.54TGv1JuaJeOH/ffnq', NULL, NULL),
	(22, 'ngôs', '2025-11-06', 'nam', 'jfijsi@mg', '938392', '', '2025-11-16', 16, 4, 1, '$2y$10$ryUJkBTjte0BOG/Br58Iw.hCCow9RlekRaZ2NjeTjbGyzNOwF/QMi', NULL, NULL),
	(23, 'nsfs', '2025-10-31', 'nam', 'nsjfn@mfka', '0928nfm298', '28  nfos', '2025-11-21', 16, 1, 0, '$2y$10$e.P60RMyVU.ixe5XFf9SR.KJ6bswtI8dendcm6G6FUNT9HCabERsq', NULL, NULL),
	(27, 'ngọc quỳnh ✅', '2025-11-04', 'nữ', 'bnj@nfnw', '299282', 'nfjsf 929892', '2025-11-07', 39, 3, 0, '$2y$10$Qc1KDLNMW0Gla14uazRlcObL8cSS0H6P.3Lk7Ms8/coZiu33.O18O', NULL, NULL),
	(28, 'Ngọc Quỳnh', '2025-11-08', 'nữ', 'ngocquynh@gmail.com', '0123456789', '1 thanh khê', '2025-11-06', 37, 1, 1, '$2y$10$hypo4VissJxfgbp/XRl/hu/Yao7oXGGOrysqQZHxMXQJm2qHyf1hq', NULL, NULL),
	(30, 'omg_ahihih', '2000-01-01', 'nam', 'omg@gmail.com', '11111111', '12danang', '2022-10-01', 38, 1, 1, '$2y$10$YMVu4qPngjlSp3RcBMXE/OpffN2HSH5pcahavuiOejdk1Nepw60Ry', NULL, NULL);

-- Dumping structure for table cdio_db.nhan_vien_phu_caps
CREATE TABLE IF NOT EXISTS `nhan_vien_phu_caps` (
  `id_nhanvien` bigint unsigned NOT NULL,
  `id_phucap` bigint unsigned NOT NULL,
  `ngaybatdau` date NOT NULL,
  `ngayketthuc` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.nhan_vien_phu_caps: ~7 rows (approximately)
INSERT INTO `nhan_vien_phu_caps` (`id_nhanvien`, `id_phucap`, `ngaybatdau`, `ngayketthuc`, `created_at`, `updated_at`) VALUES
	(1, 1, '2020-10-07', '2022-10-07', NULL, NULL),
	(2, 2, '2020-08-13', '2026-12-12', NULL, NULL),
	(3, 4, '2016-04-28', '2021-04-28', NULL, NULL),
	(1, 3, '2020-10-07', '2022-10-07', NULL, NULL),
	(2, 3, '2021-12-12', '2026-12-12', NULL, NULL),
	(3, 3, '2016-04-28', '2021-04-28', NULL, NULL),
	(2, 5, '2021-12-12', '2026-12-12', NULL, NULL);

-- Dumping structure for table cdio_db.nhan_vien_thues
CREATE TABLE IF NOT EXISTS `nhan_vien_thues` (
  `id_nhanvien` bigint unsigned NOT NULL,
  `id_thue` bigint unsigned NOT NULL,
  `ngayapdung` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.nhan_vien_thues: ~0 rows (approximately)

-- Dumping structure for table cdio_db.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table cdio_db.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `page` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `can_view` tinyint(1) DEFAULT '0',
  `can_add` tinyint(1) DEFAULT '0',
  `can_edit` tinyint(1) DEFAULT '0',
  `can_delete` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `permissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.permissions: ~40 rows (approximately)
INSERT INTO `permissions` (`id`, `user_id`, `page`, `can_view`, `can_add`, `can_edit`, `can_delete`) VALUES
	(7, 1, 'employee.php', 1, 0, 0, 0),
	(8, 1, 'pay.php', 1, 0, 0, 0),
	(9, 1, 'subsidy.php', 1, 0, 0, 0),
	(10, 1, 'role.php', 1, 0, 0, 0),
	(11, 1, 'department.php', 1, 0, 0, 0),
	(12, 1, 'attendance.php', 1, 0, 0, 0),
	(13, 2, 'employee.php', 1, 0, 0, 0),
	(14, 2, 'pay.php', 0, 1, 0, 0),
	(15, 2, 'subsidy.php', 0, 0, 1, 0),
	(16, 2, 'role.php', 0, 1, 0, 0),
	(29, 7, 'employee.php', 1, 0, 0, 0),
	(30, 7, 'pay.php', 0, 1, 0, 0),
	(31, 7, 'subsidy.php', 0, 0, 1, 0),
	(32, 7, 'role.php', 0, 0, 0, 1),
	(33, 7, 'department.php', 0, 0, 1, 0),
	(34, 7, 'attendance.php', 0, 1, 0, 0),
	(41, 3, 'employee.php', 1, 0, 0, 0),
	(42, 3, 'pay.php', 1, 0, 0, 0),
	(43, 3, 'subsidy.php', 1, 0, 0, 0),
	(44, 4, 'employee.php', 1, 0, 0, 0),
	(45, 4, 'pay.php', 0, 1, 0, 0),
	(46, 4, 'subsidy.php', 0, 1, 0, 0),
	(47, 4, 'role.php', 0, 0, 1, 0),
	(48, 4, 'department.php', 1, 1, 0, 0),
	(81, 11, 'employee.php', 1, 1, 1, 1),
	(82, 9, 'employee.php', 1, 1, 1, 1),
	(83, 9, 'pay.php', 1, 1, 1, 1),
	(84, 9, 'subsidy.php', 1, 1, 1, 1),
	(85, 9, 'role.php', 1, 1, 1, 1),
	(86, 9, 'department.php', 1, 1, 1, 1),
	(87, 9, 'attendance.php', 1, 1, 1, 1),
	(88, 9, 'chatbot.php', 1, 1, 1, 1),
	(90, 8, 'employee.php', 1, 1, 1, 1),
	(91, 8, 'pay.php', 1, 1, 1, 1),
	(92, 8, 'subsidy.php', 1, 1, 1, 1),
	(93, 8, 'role.php', 1, 1, 1, 1),
	(94, 8, 'department.php', 1, 1, 1, 1),
	(95, 8, 'attendance.php', 1, 1, 1, 1),
	(96, 8, 'chatbot.php', 1, 1, 1, 1),
	(97, 8, 'report.php', 1, 1, 1, 1);

-- Dumping structure for table cdio_db.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.personal_access_tokens: ~0 rows (approximately)

-- Dumping structure for table cdio_db.phong_bans
CREATE TABLE IF NOT EXISTS `phong_bans` (
  `id_phongban` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenphongban` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mota` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_phongban`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.phong_bans: ~5 rows (approximately)
INSERT INTO `phong_bans` (`id_phongban`, `tenphongban`, `mota`, `created_at`, `updated_at`) VALUES
	(37, 'Thiết kế', '', NULL, NULL),
	(40, 'An ninh', 'bảo vệ', NULL, NULL),
	(45, 'HR', 'nhan vi', NULL, NULL),
	(46, 'Bảo mật', '', NULL, NULL),
	(47, 'Tư vấn', '', NULL, NULL);

-- Dumping structure for table cdio_db.phu_caps
CREATE TABLE IF NOT EXISTS `phu_caps` (
  `id_phucap` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenphucap` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sotien` double NOT NULL,
  `ghichu` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_phucap`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.phu_caps: ~6 rows (approximately)
INSERT INTO `phu_caps` (`id_phucap`, `tenphucap`, `sotien`, `ghichu`, `created_at`, `updated_at`) VALUES
	(1, 'Phụ cấp khu vực', 500000, NULL, NULL, NULL),
	(2, 'Phụ cấp trách nhiệm', 150000, '', NULL, '2025-11-22 09:33:14'),
	(3, 'Phụ cấp ăn trưa', 2000000, NULL, NULL, NULL),
	(4, 'Phụ cấp thu hút', 3000000, NULL, NULL, NULL),
	(5, 'Phụ cấp điện thoại', 300000, 'Hỗ trợ chi phí liên lạc', '2025-11-04 01:48:58', NULL),
	(6, 'Phụ cấp điện thoại', 300000, 'Phụ cấp điện thoại', '2025-11-04 02:02:45', NULL);

-- Dumping structure for table cdio_db.quyens
CREATE TABLE IF NOT EXISTS `quyens` (
  `id_quyen` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenquyen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mota` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_quyen`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.quyens: ~2 rows (approximately)
INSERT INTO `quyens` (`id_quyen`, `tenquyen`, `mota`, `created_at`, `updated_at`) VALUES
	(1, 'ADMIN', 'xem-sửa-xóa', NULL, NULL),
	(2, 'Xem-only', 'Xem', NULL, NULL);

-- Dumping structure for table cdio_db.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.sessions: ~1 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('NzG4HEyxYyjB0QmMfPBG35i5QguX8iUkUzYWqp5E', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTDg2YUh1MVBZeVRDczQwWWxsYjB5UURFbTEwWXFrVU9LeEw4dmRIWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1747386458);

-- Dumping structure for table cdio_db.thue_thu_nhaps
CREATE TABLE IF NOT EXISTS `thue_thu_nhaps` (
  `id_thue` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenthue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mucthue` double NOT NULL,
  `mota` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_thue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.thue_thu_nhaps: ~0 rows (approximately)

-- Dumping structure for table cdio_db.thuong_phats
CREATE TABLE IF NOT EXISTS `thuong_phats` (
  `id_thuongphat` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_nhanvien` bigint unsigned NOT NULL,
  `loai` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tieude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sotien` double NOT NULL,
  `ngayapdung` date NOT NULL,
  `ghichu` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_thuongphat`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.thuong_phats: ~3 rows (approximately)
INSERT INTO `thuong_phats` (`id_thuongphat`, `id_nhanvien`, `loai`, `tieude`, `sotien`, `ngayapdung`, `ghichu`, `created_at`, `updated_at`) VALUES
	(101, 1, 'thưởng', 'hết kì', 50000, '2025-05-17', NULL, NULL, NULL),
	(102, 2, 'phạt', 'Trễ deadline1', 50000, '2025-05-22', NULL, NULL, NULL),
	(103, 3, 'thưởng', 'Hoàn thành trước hạn', 100000, '2025-05-22', NULL, NULL, NULL);

-- Dumping structure for table cdio_db.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cdio_db.users: ~11 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'DIAVOLO', 'hoangxinhtrai2004@gmail.com', NULL, '$2y$10$6xPXIq6rWwZ2Hpucc03rAeTH8wHGEWJMZ9F1VSX3YUOIIiXpDPifC', NULL, '2025-05-17 05:00:37', '2025-05-17 05:00:37'),
	(2, 'Đăng Nhập 4h', 'meezus2004@gmail.com', NULL, '$2y$10$wdXpuCfuyqGEdQ07pZB4X.gnkLCIJ/sTjDXmErGVpojHhNDXeECzK', NULL, '2025-05-17 08:54:09', '2025-05-17 08:54:09'),
	(3, 'Minh Hoang', 'asdjkajkdhh@gmail.com', NULL, '$2y$10$bilWDnYa5qgOxzmeCulKTOY5qxdsaPhMU7xgbcbRYKiG10zyaWu5e', NULL, '2025-05-19 08:06:20', '2025-05-19 08:06:20'),
	(4, 'admin', 'top1sever@gmail.com', NULL, '$2y$10$KILw4s5C4XbKUKTz1a1C.Os9asDJhoiI6GelPHOdbMsKcsCKwVT6C', NULL, '2025-05-24 02:36:04', '2025-05-24 02:36:04'),
	(5, 'ABC', 'ABC2020@gmail.com', NULL, '$2y$10$gzGs9TqjwvX7L4L8LLzgZutYtdp0MOULBjJjXjD0WCppjWSIWJ6EG', NULL, '2025-05-24 08:53:12', '2025-05-24 08:53:12'),
	(6, '1234', '123@gmail.com', NULL, '$2y$10$6kVpfxRN/iQbTTUt7RJQFenb2ySLgRleZe1SGSzrGJ01X/rz8VLTG', NULL, '2025-10-04 01:21:50', '2025-10-04 01:21:50'),
	(7, 'ahihi', 'test@gmail.com', NULL, '$2y$10$lTwlkNZqcCW51AyY6lb9f.0.8v36W9XT1jlpKYWA535F3FuMtcJuW', NULL, '2025-11-02 02:08:28', '2025-11-02 02:08:28'),
	(8, 'Test1234', 'test1@gmail.com1', NULL, '$2y$10$EJu1WfSe1W/voOGpPcGFVOLg2wG3FN1clk6AFZwi1qfebJcp849ve', NULL, '2025-11-02 03:10:28', '2025-12-06 07:29:46'),
	(9, 'admin1', 'admin@gmail.com', NULL, '$2y$10$3isWfWXB7GP44xl4Ed35Bek4c6nrM7pHultzeXIyAXmGjD5X0eq3e', NULL, '2025-11-03 04:18:50', '2025-11-03 04:18:50'),
	(10, 'Test12', 'User@gmail.com', NULL, '$2y$10$mmEBo8z9DOyeUCQzh5WMTOap41gxCl9BV5bGffo3X5BEqEsGGbxpe', NULL, '2025-11-07 11:08:50', '2025-11-07 11:08:50'),
	(11, 'omg', 'omg@gmail.com', NULL, '$2y$10$cN3J8FeXz07BpHkkjWN6EuyoKe4FA2bRC5vxJTKRpPWwrdpW4XCN2', NULL, '2025-12-02 09:08:34', '2025-12-02 09:08:34');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
