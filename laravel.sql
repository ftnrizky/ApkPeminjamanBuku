-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 23, 2026 at 03:01 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activity_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `related_model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `related_id` bigint UNSIGNED DEFAULT NULL,
  `data` json DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `user_role`, `activity_type`, `activity_description`, `related_model`, `related_id`, `data`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 1, 'Fathan Rizkyansyah', 'peminjam', 'register', 'Akun baru terdaftar: Fathan Rizkyansyah (fathanrizky80@gmail.com) sebagai peminjam.', 'User', 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 04:16:05', '2026-04-22 04:16:05'),
(2, 2, 'gibran', 'peminjam', 'register', 'Akun baru terdaftar: gibran (gibran@gmail.com) sebagai peminjam.', 'User', 2, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-22 04:22:11', '2026-04-22 04:22:11'),
(3, 1, 'Fathan Rizkyansyah', 'admin', 'logout', 'Fathan Rizkyansyah logout dari sistem.', 'User', 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 04:22:52', '2026-04-22 04:22:52'),
(4, 1, 'Fathan Rizkyansyah', 'admin', 'login', 'Fathan Rizkyansyah berhasil login ke sistem.', 'User', 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 04:23:02', '2026-04-22 04:23:02'),
(5, 3, 'ibnu', 'peminjam', 'register', 'Akun baru terdaftar: ibnu (ibnu@gmail.com) sebagai peminjam.', 'User', 3, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 04:24:52', '2026-04-22 04:24:52'),
(6, 1, 'Fathan Rizkyansyah', 'admin', 'edit_alat', 'Admin Fathan Rizkyansyah memperbarui data user: \'ibnu\' (ibnu@gmail.com), role: petugas.', 'User', 3, '{\"name\": \"ibnu\", \"role\": \"petugas\", \"email\": \"ibnu@gmail.com\", \"is_blacklisted\": 0}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 04:25:12', '2026-04-22 04:25:12'),
(7, 3, 'ibnu', 'petugas', 'logout', 'ibnu logout dari sistem.', 'User', 3, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 04:25:19', '2026-04-22 04:25:19'),
(8, 3, 'ibnu', 'petugas', 'login', 'ibnu berhasil login ke sistem.', 'User', 3, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 04:25:32', '2026-04-22 04:25:32'),
(9, 1, 'Fathan Rizkyansyah', 'admin', 'login', 'Fathan Rizkyansyah berhasil login ke sistem.', 'User', 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 04:31:15', '2026-04-22 04:31:15'),
(10, 2, 'gibran', 'peminjam', 'login', 'gibran berhasil login ke sistem.', 'User', 2, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-22 04:31:40', '2026-04-22 04:31:40'),
(11, 3, 'ibnu', 'petugas', 'login', 'ibnu berhasil login ke sistem.', 'User', 3, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 04:33:12', '2026-04-22 04:33:12'),
(12, 1, 'Fathan Rizkyansyah', 'admin', 'tambah_alat', 'Admin Fathan Rizkyansyah menambahkan alat baru: \'laskar\' (Kategori: {\"id\":1,\"nama\":\"belajar\",\"icon\":\"fa-feather\",\"deskripsi\":null,\"warna\":\"red\",\"created_at\":\"2026-04-22T06:52:01.000000Z\",\"updated_at\":\"2026-04-22T06:52:01.000000Z\"}, Stok: 10).', 'Alat', 1, '{\"kondisi\": \"baik\", \"kategori\": {\"id\": 1, \"icon\": \"fa-feather\", \"nama\": \"belajar\", \"warna\": \"red\", \"deskripsi\": null, \"created_at\": \"2026-04-22T06:52:01.000000Z\", \"updated_at\": \"2026-04-22T06:52:01.000000Z\"}, \"nama_alat\": \"laskar\", \"harga_sewa\": \"80000\", \"stok_total\": \"10\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 06:52:52', '2026-04-22 06:52:52'),
(13, 2, 'gibran', 'peminjam', 'login', 'gibran berhasil login ke sistem.', 'User', 2, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-22 06:53:44', '2026-04-22 06:53:44'),
(14, 2, 'gibran', 'peminjam', 'pinjam', 'gibran mengajukan peminjaman \'laskar\' sebanyak 4 unit. Tgl kembali: 2026-04-22 00:00:00.', 'Peminjaman', 1, '{\"alat\": \"laskar\", \"jumlah\": \"4\", \"tujuan\": \"ye\", \"tgl_pinjam\": \"2026-04-21T17:00:00.000000Z\", \"tgl_kembali\": \"2026-04-21T17:00:00.000000Z\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-22 06:56:38', '2026-04-22 06:56:38'),
(15, 3, 'ibnu', 'petugas', 'login', 'ibnu berhasil login ke sistem.', 'User', 3, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 06:57:00', '2026-04-22 06:57:00'),
(16, 3, 'ibnu', 'petugas', 'setujui_pinjam', 'ibnu menyetujui peminjaman \'laskar\' untuk gibran sebanyak 2 unit.', 'Peminjaman', 1, '{\"alat\": \"laskar\", \"peminjam\": \"gibran\", \"jumlah_disetujui\": 2}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 07:00:15', '2026-04-22 07:00:15'),
(17, 2, 'gibran', 'peminjam', 'pinjam', 'gibran mengajukan peminjaman \'laskar\' sebanyak 2 unit. Tgl kembali: 2026-04-22 00:00:00.', 'Peminjaman', 2, '{\"alat\": \"laskar\", \"jumlah\": \"2\", \"tujuan\": \"ssad\", \"tgl_pinjam\": \"2026-04-21T17:00:00.000000Z\", \"tgl_kembali\": \"2026-04-21T17:00:00.000000Z\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-22 07:05:43', '2026-04-22 07:05:43'),
(18, 3, 'ibnu', 'petugas', 'setujui_pinjam', 'ibnu menyetujui peminjaman \'laskar\' untuk gibran sebanyak 1 unit.', 'Peminjaman', 2, '{\"alat\": \"laskar\", \"peminjam\": \"gibran\", \"jumlah_disetujui\": 1}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 07:06:32', '2026-04-22 07:06:32'),
(19, 2, 'gibran', 'peminjam', 'kembali', 'gibran mengajukan pengembalian \'laskar\'.', 'Peminjaman', 1, '{\"alat\": \"laskar\", \"jumlah\": 2}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-22 07:36:36', '2026-04-22 07:36:36'),
(20, 3, 'ibnu', 'petugas', 'login', 'ibnu berhasil login ke sistem.', 'User', 3, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-22 23:48:47', '2026-04-22 23:48:47'),
(21, 2, 'gibran', 'peminjam', 'login', 'gibran berhasil login ke sistem.', 'User', 2, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-22 23:58:08', '2026-04-22 23:58:08'),
(22, 2, 'gibran', 'peminjam', 'pinjam', 'gibran mengajukan peminjaman \'laskar\' sebanyak 10 unit. Tgl kembali: 2026-04-23 00:00:00.', 'Peminjaman', 3, '{\"alat\": \"laskar\", \"jumlah\": \"10\", \"tujuan\": \"test\", \"tgl_pinjam\": \"2026-04-22T17:00:00.000000Z\", \"tgl_kembali\": \"2026-04-22T17:00:00.000000Z\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-23 00:06:15', '2026-04-23 00:06:15'),
(23, 3, 'ibnu', 'petugas', 'setujui_kembali', 'ibnu mengkonfirmasi pengembalian \'laskar\' dari gibran. Total denda: Rp 65.000.', 'Peminjaman', 1, '{\"alat\": \"laskar\", \"kondisi\": \"Baik:0, Lecet:1, Rusak:0, Hilang:0, Lainnya:1\", \"peminjam\": \"gibran\", \"total_denda\": 65000}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 00:38:22', '2026-04-23 00:38:22'),
(24, 3, 'ibnu', 'petugas', 'setujui_pinjam', 'ibnu menyetujui peminjaman \'laskar\' untuk gibran sebanyak 1 unit.', 'Peminjaman', 3, '{\"alat\": \"laskar\", \"peminjam\": \"gibran\", \"jumlah_disetujui\": 1}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 00:38:50', '2026-04-23 00:38:50'),
(25, 2, 'gibran', 'peminjam', 'kembali', 'gibran mengajukan pengembalian \'laskar\'.', 'Peminjaman', 2, '{\"alat\": \"laskar\", \"jumlah\": 1}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-23 00:39:07', '2026-04-23 00:39:07'),
(26, 3, 'ibnu', 'petugas', 'setujui_kembali', 'ibnu mengkonfirmasi pengembalian \'laskar\' dari gibran. Total denda: Rp 25.000.', 'Peminjaman', 2, '{\"alat\": \"laskar\", \"kondisi\": \"Baik:0, Lecet:0, Rusak:0, Hilang:0, Lainnya:1\", \"peminjam\": \"gibran\", \"total_denda\": 25000}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 00:39:30', '2026-04-23 00:39:30'),
(27, 1, 'Fathan Rizkyansyah', 'admin', 'login', 'Fathan Rizkyansyah berhasil login ke sistem.', 'User', 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 00:58:55', '2026-04-23 00:58:55'),
(28, 3, 'ibnu', 'petugas', 'logout', 'ibnu logout dari sistem.', 'User', 3, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 00:59:40', '2026-04-23 00:59:40'),
(29, 1, 'Fathan Rizkyansyah', 'admin', 'login', 'Fathan Rizkyansyah berhasil login ke sistem.', 'User', 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 00:59:51', '2026-04-23 00:59:51'),
(30, 3, 'ibnu', 'petugas', 'login', 'ibnu berhasil login ke sistem.', 'User', 3, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 02:00:15', '2026-04-23 02:00:15'),
(31, 1, 'Fathan Rizkyansyah', 'admin', 'login', 'Fathan Rizkyansyah berhasil login ke sistem.', 'User', 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 02:02:25', '2026-04-23 02:02:25'),
(32, 1, 'Fathan Rizkyansyah', 'admin', 'tambah_alat', 'Admin Fathan Rizkyansyah menambahkan alat baru: \'harry\' (Kategori: {\"id\":3,\"nama\":\"Dongeng\",\"icon\":\"fa-book\",\"deskripsi\":null,\"warna\":\"orange\",\"created_at\":\"2026-04-23T02:10:01.000000Z\",\"updated_at\":\"2026-04-23T02:10:01.000000Z\"}, Stok: 10).', 'Alat', 2, '{\"kondisi\": \"baik\", \"kategori\": {\"id\": 3, \"icon\": \"fa-book\", \"nama\": \"Dongeng\", \"warna\": \"orange\", \"deskripsi\": null, \"created_at\": \"2026-04-23T02:10:01.000000Z\", \"updated_at\": \"2026-04-23T02:10:01.000000Z\"}, \"nama_alat\": \"harry\", \"harga_sewa\": \"48000\", \"stok_total\": \"10\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 02:10:50', '2026-04-23 02:10:50'),
(33, 1, 'Fathan Rizkyansyah', 'admin', 'tambah_alat', 'Admin Fathan Rizkyansyah menambahkan alat baru: \'test\' (Kategori: {\"id\":2,\"nama\":\"sastra\",\"icon\":\"fa-feather\",\"deskripsi\":null,\"warna\":\"blue\",\"created_at\":\"2026-04-23T02:09:23.000000Z\",\"updated_at\":\"2026-04-23T02:09:23.000000Z\"}, Stok: 2).', 'Alat', 3, '{\"kondisi\": \"baik\", \"kategori\": {\"id\": 2, \"icon\": \"fa-feather\", \"nama\": \"sastra\", \"warna\": \"blue\", \"deskripsi\": null, \"created_at\": \"2026-04-23T02:09:23.000000Z\", \"updated_at\": \"2026-04-23T02:09:23.000000Z\"}, \"nama_alat\": \"test\", \"harga_sewa\": \"40000\", \"stok_total\": \"2\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 02:12:55', '2026-04-23 02:12:55'),
(34, 1, 'Fathan Rizkyansyah', 'admin', 'edit_alat', 'Admin Fathan Rizkyansyah memperbarui data alat: \'test\'.', 'Alat', 3, '{\"kondisi\": \"baik\", \"kategori\": {\"id\": 2, \"icon\": \"fa-feather\", \"nama\": \"sastra\", \"warna\": \"blue\", \"deskripsi\": null, \"created_at\": \"2026-04-23T02:09:23.000000Z\", \"updated_at\": \"2026-04-23T02:09:23.000000Z\"}, \"nama_alat\": \"test\", \"harga_sewa\": \"49000\", \"stok_total\": \"2\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 02:18:58', '2026-04-23 02:18:58'),
(35, 1, 'Fathan Rizkyansyah', 'admin', 'edit_alat', 'Admin Fathan Rizkyansyah memperbarui data alat: \'test\'.', 'Alat', 3, '{\"kondisi\": \"baik\", \"kategori\": {\"id\": 2, \"icon\": \"fa-feather\", \"nama\": \"sastra\", \"warna\": \"blue\", \"deskripsi\": null, \"created_at\": \"2026-04-23T02:09:23.000000Z\", \"updated_at\": \"2026-04-23T02:09:23.000000Z\"}, \"nama_alat\": \"test\", \"harga_sewa\": \"4000\", \"stok_total\": \"2\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 02:23:20', '2026-04-23 02:23:20'),
(36, 4, 'ibe', 'peminjam', 'register', 'Akun baru terdaftar: ibe (ibe@gmail.com) sebagai peminjam.', 'User', 4, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 02:27:40', '2026-04-23 02:27:40'),
(37, 4, 'ibe', 'peminjam', 'pinjam', 'ibe mengajukan peminjaman \'harry\' sebanyak 9 unit. Tgl kembali: 2026-04-24 00:00:00.', 'Peminjaman', 4, '{\"alat\": \"harry\", \"jumlah\": \"9\", \"tujuan\": \"nonton bola\", \"tgl_pinjam\": \"2026-04-22T17:00:00.000000Z\", \"tgl_kembali\": \"2026-04-23T17:00:00.000000Z\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 02:28:26', '2026-04-23 02:28:26'),
(38, 3, 'ibnu', 'petugas', 'setujui_pinjam', 'ibnu menyetujui peminjaman \'harry\' untuk ibe sebanyak 4 unit.', 'Peminjaman', 4, '{\"alat\": \"harry\", \"peminjam\": \"ibe\", \"jumlah_disetujui\": 4}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 02:29:42', '2026-04-23 02:29:42'),
(39, 4, 'ibe', 'peminjam', 'kembali', 'ibe mengajukan pengembalian \'harry\'.', 'Peminjaman', 4, '{\"alat\": \"harry\", \"jumlah\": 4}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 02:30:27', '2026-04-23 02:30:27'),
(40, 3, 'ibnu', 'petugas', 'setujui_kembali', 'ibnu mengkonfirmasi pengembalian \'harry\' dari ibe. Total denda: Rp 569.000.', 'Peminjaman', 4, '{\"alat\": \"harry\", \"kondisi\": \"Baik:1, Lecet:1, Rusak:0, Hilang:1, Lainnya:1\", \"peminjam\": \"ibe\", \"total_denda\": 569000}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 02:31:09', '2026-04-23 02:31:09'),
(41, 1, 'Fathan Rizkyansyah', 'admin', 'tambah_alat', 'Admin Fathan Rizkyansyah menambahkan alat baru: \'harry\' (Kategori: {\"id\":1,\"nama\":\"belajar\",\"icon\":\"fa-feather\",\"deskripsi\":null,\"warna\":\"red\",\"created_at\":\"2026-04-22T06:52:01.000000Z\",\"updated_at\":\"2026-04-22T06:52:01.000000Z\"}, Stok: 2).', 'Alat', 4, '{\"kondisi\": \"baik\", \"kategori\": {\"id\": 1, \"icon\": \"fa-feather\", \"nama\": \"belajar\", \"warna\": \"red\", \"deskripsi\": null, \"created_at\": \"2026-04-22T06:52:01.000000Z\", \"updated_at\": \"2026-04-22T06:52:01.000000Z\"}, \"nama_alat\": \"harry\", \"harga_sewa\": \"40000\", \"stok_total\": \"2\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 02:41:29', '2026-04-23 02:41:29'),
(42, 1, 'Fathan Rizkyansyah', 'admin', 'edit_alat', 'Admin Fathan Rizkyansyah memperbarui data alat: \'harry\'.', 'Alat', 4, '{\"kondisi\": \"baik\", \"kategori\": {\"id\": 1, \"icon\": \"fa-feather\", \"nama\": \"belajar\", \"warna\": \"red\", \"deskripsi\": null, \"created_at\": \"2026-04-22T06:52:01.000000Z\", \"updated_at\": \"2026-04-22T06:52:01.000000Z\"}, \"nama_alat\": \"harry\", \"harga_sewa\": \"4000\", \"stok_total\": \"2\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 02:42:25', '2026-04-23 02:42:25');

-- --------------------------------------------------------

--
-- Table structure for table `alats`
--

CREATE TABLE `alats` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_alat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori_id` bigint UNSIGNED DEFAULT NULL,
  `stok_total` int NOT NULL DEFAULT '0',
  `stok_tersedia` int NOT NULL DEFAULT '0',
  `harga_sewa` int NOT NULL DEFAULT '0',
  `kondisi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `alats`
--

INSERT INTO `alats` (`id`, `nama_alat`, `slug`, `kategori_id`, `stok_total`, `stok_tersedia`, `harga_sewa`, `kondisi`, `deskripsi`, `foto`, `created_at`, `updated_at`) VALUES
(1, 'laskar', 'laskar-IjJzS', 1, 10, 43, 80000, 'baik', NULL, 'alats/rEI7gzCwUBUHjLAogs6Y3A6BQFEFQnRnwL77zFkE.png', '2026-04-22 06:52:52', '2026-04-23 00:39:30'),
(2, 'harry', 'harry-DjFw6', 3, 10, 9, 48000, 'baik', 'test', 'alats/KUQzJiNENPXC6sptG2VsfptaNWxHeIsFm9sx0yya.jpg', '2026-04-23 02:10:49', '2026-04-23 02:31:09'),
(3, 'test', 'test-uHzP6', 2, 2, 2, 4000, 'baik', NULL, 'alats/pmLauGzPWtJcPhkbtClH1jUOOpMj50uKDRqSvHIB.jpg', '2026-04-23 02:12:55', '2026-04-23 02:23:20'),
(4, 'harry', 'harry-eMfoi', 1, 2, 2, 4000, 'baik', NULL, 'alats/Wn6qg3c5K6WJzJ1rv0vyxrlNx7tJUIY8McZSkyqx.jpg', '2026-04-23 02:41:29', '2026-04-23 02:42:25');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategoris`
--

CREATE TABLE `kategoris` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fa-buku',
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `warna` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cyan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategoris`
--

INSERT INTO `kategoris` (`id`, `nama`, `icon`, `deskripsi`, `warna`, `created_at`, `updated_at`) VALUES
(1, 'belajar', 'fa-feather', NULL, 'red', '2026-04-22 06:52:01', '2026-04-22 06:52:01'),
(2, 'sastra', 'fa-feather', NULL, 'blue', '2026-04-23 02:09:23', '2026-04-23 02:09:23'),
(3, 'Dongeng', 'fa-book', NULL, 'orange', '2026-04-23 02:10:01', '2026-04-23 02:10:01');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_05_000000_create_kategoris_table', 1),
(5, '2026_04_06_022103_create_alats_table', 1),
(6, '2026_04_06_022332_create_peminjaman_table', 1),
(7, '2026_04_18_000000_create_notifikasi_table', 1),
(8, '2026_04_18_000001_add_is_blacklisted_to_users_table', 1),
(9, '2026_04_18_000001_drop_harga_asli_from_alats_table', 1),
(10, '2026_04_20_000000_create_activity_logs_table', 1),
(11, '2026_04_23_073545_fix_kondisi_column_to_json', 2);

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'fas fa-bell',
  `action_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifikasi`
--

INSERT INTO `notifikasi` (`id`, `user_id`, `title`, `message`, `type`, `icon`, `action_url`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 1, 'Permintaan Peminjaman Baru', 'Peminjam gibran mengajukan peminjaman laskar x 4 unit.', 'info', 'fas fa-bell', 'http://127.0.0.1:8000/petugas/menyetujui_peminjaman', 0, '2026-04-22 06:56:38', '2026-04-22 06:56:38'),
(2, 3, 'Permintaan Peminjaman Baru', 'Peminjam gibran mengajukan peminjaman laskar x 4 unit.', 'info', 'fas fa-bell', 'http://127.0.0.1:8000/petugas/menyetujui_peminjaman', 1, '2026-04-22 06:56:38', '2026-04-23 02:38:37'),
(3, 2, 'Peminjaman Disetujui', 'Peminjaman laskar telah disetujui. Jumlah: 2 unit.', 'success', 'fas fa-check-circle', 'http://127.0.0.1:8000/peminjam/pengembalian', 1, '2026-04-22 07:00:15', '2026-04-22 07:36:54'),
(4, 1, 'Permintaan Peminjaman Baru', 'Peminjam gibran mengajukan peminjaman laskar x 2 unit.', 'info', 'fas fa-bell', 'http://127.0.0.1:8000/petugas/menyetujui_peminjaman', 0, '2026-04-22 07:05:44', '2026-04-22 07:05:44'),
(5, 3, 'Permintaan Peminjaman Baru', 'Peminjam gibran mengajukan peminjaman laskar x 2 unit.', 'info', 'fas fa-bell', 'http://127.0.0.1:8000/petugas/menyetujui_peminjaman', 1, '2026-04-22 07:05:44', '2026-04-23 02:38:37'),
(6, 2, 'Peminjaman Disetujui', 'Peminjaman laskar telah disetujui. Jumlah: 1 unit.', 'success', 'fas fa-check-circle', 'http://127.0.0.1:8000/peminjam/pengembalian', 1, '2026-04-22 07:06:32', '2026-04-22 07:36:52'),
(7, 1, 'Pengembalian Diajukan', 'Peminjam gibran telah mengajukan pengembalian laskar.', 'info', 'fas fa-undo-alt', 'http://127.0.0.1:8000/petugas/menyetujui_pengembalian', 0, '2026-04-22 07:36:36', '2026-04-22 07:36:36'),
(8, 3, 'Pengembalian Diajukan', 'Peminjam gibran telah mengajukan pengembalian laskar.', 'info', 'fas fa-undo-alt', 'http://127.0.0.1:8000/petugas/menyetujui_pengembalian', 1, '2026-04-22 07:36:36', '2026-04-23 02:38:37'),
(9, 1, 'Permintaan Peminjaman Baru', 'Peminjam gibran mengajukan peminjaman laskar x 10 unit.', 'info', 'fas fa-bell', 'http://127.0.0.1:8000/petugas/menyetujui_peminjaman', 0, '2026-04-23 00:06:15', '2026-04-23 00:06:15'),
(10, 3, 'Permintaan Peminjaman Baru', 'Peminjam gibran mengajukan peminjaman laskar x 10 unit.', 'info', 'fas fa-bell', 'http://127.0.0.1:8000/petugas/menyetujui_peminjaman', 1, '2026-04-23 00:06:15', '2026-04-23 00:38:38'),
(11, 2, 'Pengembalian Dikonfirmasi', 'Pengembalian laskar telah dikonfirmasi. Total denda: Rp 65.000', 'success', 'fas fa-check-circle', 'http://127.0.0.1:8000/peminjam/riwayat', 0, '2026-04-23 00:38:22', '2026-04-23 00:38:22'),
(12, 2, 'Peminjaman Disetujui', 'Peminjaman laskar telah disetujui. Jumlah: 1 unit.', 'success', 'fas fa-check-circle', 'http://127.0.0.1:8000/peminjam/pengembalian', 0, '2026-04-23 00:38:50', '2026-04-23 00:38:50'),
(13, 1, 'Pengembalian Diajukan', 'Peminjam gibran telah mengajukan pengembalian laskar', 'info', 'fas fa-undo-alt', 'http://127.0.0.1:8000/petugas/menyetujui_pengembalian', 0, '2026-04-23 00:39:07', '2026-04-23 00:39:07'),
(14, 3, 'Pengembalian Diajukan', 'Peminjam gibran telah mengajukan pengembalian laskar', 'info', 'fas fa-undo-alt', 'http://127.0.0.1:8000/petugas/menyetujui_pengembalian', 1, '2026-04-23 00:39:07', '2026-04-23 02:38:37'),
(15, 2, 'Pengembalian Dikonfirmasi', 'Pengembalian laskar telah dikonfirmasi. Total denda: Rp 25.000', 'success', 'fas fa-check-circle', 'http://127.0.0.1:8000/peminjam/riwayat', 0, '2026-04-23 00:39:30', '2026-04-23 00:39:30'),
(16, 1, 'Permintaan Peminjaman Baru', 'Peminjam ibe mengajukan peminjaman harry x 9 unit.', 'info', 'fas fa-bell', 'http://127.0.0.1:8000/petugas/menyetujui_peminjaman', 0, '2026-04-23 02:28:26', '2026-04-23 02:28:26'),
(17, 3, 'Permintaan Peminjaman Baru', 'Peminjam ibe mengajukan peminjaman harry x 9 unit.', 'info', 'fas fa-bell', 'http://127.0.0.1:8000/petugas/menyetujui_peminjaman', 1, '2026-04-23 02:28:26', '2026-04-23 02:38:37'),
(18, 4, 'Peminjaman Disetujui', 'Peminjaman harry telah disetujui. Jumlah: 4 unit.', 'success', 'fas fa-check-circle', 'http://127.0.0.1:8000/peminjam/pengembalian', 0, '2026-04-23 02:29:42', '2026-04-23 02:29:42'),
(19, 1, 'Pengembalian Diajukan', 'Peminjam ibe telah mengajukan pengembalian harry', 'info', 'fas fa-undo-alt', 'http://127.0.0.1:8000/petugas/menyetujui_pengembalian', 0, '2026-04-23 02:30:27', '2026-04-23 02:30:27'),
(20, 3, 'Pengembalian Diajukan', 'Peminjam ibe telah mengajukan pengembalian harry', 'info', 'fas fa-undo-alt', 'http://127.0.0.1:8000/petugas/menyetujui_pengembalian', 1, '2026-04-23 02:30:27', '2026-04-23 02:38:37'),
(21, 4, 'Pengembalian Dikonfirmasi', 'Pengembalian harry telah dikonfirmasi. Total denda: Rp 569.000', 'success', 'fas fa-check-circle', 'http://127.0.0.1:8000/peminjam/riwayat', 0, '2026-04-23 02:31:09', '2026-04-23 02:31:09');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `alat_id` bigint UNSIGNED NOT NULL,
  `jumlah` int NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali` date NOT NULL,
  `tgl_dikembalikan` datetime DEFAULT NULL,
  `tujuan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','disetujui','ditolak','selesai','dikembalikan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `kondisi` json DEFAULT NULL,
  `total_denda` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `user_id`, `alat_id`, `jumlah`, `tgl_pinjam`, `tgl_kembali`, `tgl_dikembalikan`, `tujuan`, `status`, `kondisi`, `total_denda`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 2, '2026-04-22', '2026-04-22', '2026-04-23 07:38:22', 'Baik:0, Lecet:1, Rusak:0, Hilang:0, Lainnya:1', 'selesai', '[\"lainnya\", \"lecet\"]', 65000, '2026-04-22 06:56:38', '2026-04-23 00:38:22'),
(2, 2, 1, 1, '2026-04-22', '2026-04-22', '2026-04-23 07:39:30', 'Baik:0, Lecet:0, Rusak:0, Hilang:0, Lainnya:1', 'selesai', '[\"lainnya\"]', 25000, '2026-04-22 07:05:43', '2026-04-23 00:39:30'),
(3, 2, 1, 1, '2026-04-23', '2026-04-23', NULL, 'test', 'disetujui', NULL, 0, '2026-04-23 00:06:15', '2026-04-23 00:38:50'),
(4, 4, 2, 4, '2026-04-23', '2026-04-24', '2026-04-23 09:31:09', 'Baik:1, Lecet:1, Rusak:0, Hilang:1, Lainnya:1', 'selesai', '[\"hilang\", \"lainnya\", \"lecet\", \"baik\"]', 569000, '2026-04-23 02:28:26', '2026-04-23 02:31:09');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('cS1KV22jVDBWFA3H4KsKuEuc9YoLFmIr0vbgr9TH', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTXMwYmdTT3lRNXlvWGhleFlTNkJVYVBQSU45aEd1WENERjl5M3ZKRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wZW1pbmphbS9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTg6InBlbWluamFtLmRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1776831731),
('pv5qITewfSQMLJCEi3AzM4NLH0s1NmnhvKwFVNoC', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiVktRSUpJSzNoWHhkNHRlNmo1MFdkZkFiekZOa0Rvdmk5TG05OE1VciI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MztzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NzY4MzE5MzI7fX0=', 1776831935),
('rQmy02O3Im9odEcCAaLc1NAVssnm283mtv1f18hn', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiaXBPQ3Y3Zlo2OXRyQkNqNVFQUGhOSkRXdnJpWkt6T2NtSXBNa0V1RCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi91c2VycyI7czo1OiJyb3V0ZSI7czoxNzoiYWRtaW4ua2Vsb2xhX3VzZXIiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NDoiYXV0aCI7YToxOntzOjIxOiJwYXNzd29yZF9jb25maXJtZWRfYXQiO2k6MTc3NjgzMTc4Mjt9fQ==', 1776831912);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `role` enum('admin','petugas','peminjam') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'peminjam',
  `is_blacklisted` tinyint(1) NOT NULL DEFAULT '0',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `no_hp`, `email_verified_at`, `role`, `is_blacklisted`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Fathan Rizkyansyah', 'fathanrizky80@gmail.com', '986767787', NULL, 'admin', 0, '$2y$12$fSy/UpG/X.FzImWHNqsypOeqyklM4HHHNUu21fhmG1vkf.hnlifWm', NULL, '2026-04-22 04:16:05', '2026-04-22 04:16:05'),
(2, 'gibran', 'gibran@gmail.com', '0089786577', NULL, 'peminjam', 0, '$2y$12$TC55sDzbm4.9//kSCGf3Q.Y1ffok/uykhS/i6tyd95Kx0Y46sSNeO', NULL, '2026-04-22 04:22:11', '2026-04-22 04:22:11'),
(3, 'ibnu', 'ibnu@gmail.com', '0829381234', NULL, 'petugas', 0, '$2y$12$dobIYWTjOCXnJhHDAN3Vc.4cvIHAPVY1Tih4i24MDNB5tk4LLm7lK', NULL, '2026-04-22 04:24:52', '2026-04-22 04:25:12'),
(4, 'ibe', 'ibe@gmail.com', '0867232526', NULL, 'peminjam', 0, '$2y$12$Car3nvPTLVo15GOATqYXq.2gXTuShKrjLuMZ6FTJpSGk/GoLPkQF2', NULL, '2026-04-23 02:27:40', '2026-04-23 02:27:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_index` (`user_id`),
  ADD KEY `activity_logs_activity_type_index` (`activity_type`),
  ADD KEY `activity_logs_created_at_index` (`created_at`);

--
-- Indexes for table `alats`
--
ALTER TABLE `alats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alats_slug_unique` (`slug`),
  ADD KEY `alats_kategori_id_foreign` (`kategori_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategoris`
--
ALTER TABLE `kategoris`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kategoris_nama_unique` (`nama`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifikasi_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peminjaman_user_id_foreign` (`user_id`),
  ADD KEY `peminjaman_alat_id_foreign` (`alat_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `alats`
--
ALTER TABLE `alats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategoris`
--
ALTER TABLE `kategoris`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alats`
--
ALTER TABLE `alats`
  ADD CONSTRAINT `alats_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategoris` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_alat_id_foreign` FOREIGN KEY (`alat_id`) REFERENCES `alats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peminjaman_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
