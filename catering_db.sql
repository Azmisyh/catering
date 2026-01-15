-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 15, 2026 at 02:28 AM
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
-- Database: `catering_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `pesan` text NOT NULL,
  `pengirim` enum('user','admin') NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id`, `user_id`, `pesan`, `pengirim`, `is_read`, `created_at`) VALUES
(1, 2, 'tes', 'user', 0, '2026-01-06 15:35:35'),
(2, 2, 'tes', 'user', 0, '2026-01-06 15:35:40'),
(3, 2, 'tes', 'user', 0, '2026-01-06 15:35:45'),
(4, 2, 'tes', 'user', 0, '2026-01-06 15:35:50'),
(5, 4, 'tes', 'user', 1, '2026-01-06 15:46:38'),
(6, 4, 'tes', 'user', 1, '2026-01-06 15:46:43'),
(7, 4, 'tes', 'user', 1, '2026-01-06 15:46:49'),
(8, 4, 'tes', 'user', 1, '2026-01-06 15:46:55'),
(9, 4, 'tes', 'user', 1, '2026-01-06 15:47:01'),
(10, 4, 'tes', 'user', 1, '2026-01-06 15:47:07'),
(11, 4, 'tes', 'user', 1, '2026-01-06 15:47:13'),
(12, 4, 'tes', 'user', 1, '2026-01-06 15:47:18'),
(13, 4, 'tes', 'user', 1, '2026-01-06 15:47:24'),
(14, 4, 'tes', 'user', 1, '2026-01-06 15:47:30'),
(15, 4, 'tes', 'user', 1, '2026-01-06 15:47:36'),
(16, 4, 'tes', 'user', 1, '2026-01-06 15:47:42'),
(17, 4, 'tes', 'user', 1, '2026-01-06 15:47:48'),
(18, 4, 'tes', 'user', 1, '2026-01-06 15:47:54'),
(19, 4, 'tes', 'user', 1, '2026-01-06 15:48:00'),
(20, 4, 'tes', 'user', 1, '2026-01-06 15:48:06'),
(21, 4, 'tes', 'user', 1, '2026-01-06 15:48:12'),
(22, 4, 'tes', 'user', 1, '2026-01-06 15:48:18'),
(23, 4, 'tes', 'user', 1, '2026-01-06 15:50:35'),
(24, 5, 'tes', 'user', 1, '2026-01-06 15:52:58'),
(25, 5, 'halo', 'user', 1, '2026-01-06 15:53:49'),
(26, 4, 'halo', 'admin', 1, '2026-01-06 16:02:49'),
(27, 5, 'tes', 'user', 1, '2026-01-06 16:06:13'),
(28, 5, 'halo', 'admin', 1, '2026-01-06 16:06:22'),
(29, 5, 'tes', 'user', 1, '2026-01-06 16:06:34'),
(30, 5, 'tes', 'user', 1, '2026-01-06 16:06:37'),
(31, 5, 'halo', 'admin', 1, '2026-01-06 16:06:50'),
(32, 5, 'tes', 'user', 1, '2026-01-06 16:07:54'),
(33, 5, 'halo', 'admin', 0, '2026-01-06 16:07:59'),
(34, 5, 'halo', 'admin', 0, '2026-01-06 16:08:03'),
(35, 5, 'halo', 'admin', 0, '2026-01-06 16:08:05'),
(36, 5, 'tes', 'admin', 0, '2026-01-06 16:21:04'),
(37, 5, 'tes', 'admin', 0, '2026-01-06 16:21:07'),
(38, 5, 'tes', 'admin', 0, '2026-01-06 16:22:43'),
(39, 5, 'pas', 'admin', 0, '2026-01-06 16:22:48'),
(40, 3, 'tes', 'user', 1, '2026-01-06 16:23:15'),
(41, 3, 'tes', 'admin', 1, '2026-01-06 16:23:29'),
(42, 4, 'halo kak', 'user', 1, '2026-01-07 13:37:47'),
(43, 4, 'halo', 'admin', 1, '2026-01-07 13:48:43'),
(44, 5, 'okey', 'admin', 0, '2026-01-07 13:48:50'),
(45, 3, 'tes', 'admin', 0, '2026-01-15 01:22:58');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`, `deskripsi`) VALUES
(1, 'Nasi Box', 'Paket nasi box praktis untuk berbagai acara'),
(2, 'Prasmanan', 'Menu prasmanan untuk acara besar'),
(3, 'Snack', 'Aneka kue dan snack'),
(4, 'Minuman', 'Berbagai pilihan minuman');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `status` enum('tersedia','habis') DEFAULT 'tersedia',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `kategori_id`, `nama_menu`, `deskripsi`, `harga`, `gambar`, `status`, `created_at`) VALUES
(1, 1, 'Nasi Box Ayam Goreng', 'Nasi putih, ayam goreng, sambal, lalapan', 25000, NULL, 'tersedia', '2026-01-05 14:35:49'),
(2, 1, 'Nasi Box Rendang', 'Nasi putih, rendang sapi, sambal, lalapan', 30000, NULL, 'tersedia', '2026-01-05 14:35:49'),
(3, 2, 'Nasi Kuning', 'Nasi kuning dengan lauk pauk lengkap', 20000, NULL, 'tersedia', '2026-01-05 14:35:49'),
(4, 2, 'Soto Ayam', 'Soto ayam dengan kuah gurih', 18000, NULL, 'tersedia', '2026-01-05 14:35:49'),
(5, 3, 'Risoles Mayo', 'Risoles isi daging dan sayuran', 3000, NULL, 'tersedia', '2026-01-05 14:35:49'),
(6, 3, 'Brownies Coklat', 'Brownies lembut rasa coklat', 5000, NULL, 'tersedia', '2026-01-05 14:35:49'),
(7, 4, 'Es Teh Manis', 'Teh manis segar dingin', 3000, NULL, 'tersedia', '2026-01-05 14:35:49'),
(8, 4, 'Jus Jeruk', 'Jus jeruk segar', 5000, NULL, 'tersedia', '2026-01-05 14:35:49'),
(13, 4, 'tes', 'ytes', 10000, NULL, 'tersedia', '2026-01-06 15:34:08'),
(14, 1, 'Paket Ayam goyeng', 'a', 20000, 'uploads/menu/1768437260_6968360cbfaaa.jpg', 'tersedia', '2026-01-15 00:34:20');

-- --------------------------------------------------------

--
-- Table structure for table `paket`
--

CREATE TABLE `paket` (
  `id` int(11) NOT NULL,
  `nama_paket` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL,
  `min_porsi` int(11) DEFAULT 10,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paket`
--

INSERT INTO `paket` (`id`, `nama_paket`, `deskripsi`, `harga`, `min_porsi`, `gambar`, `created_at`) VALUES
(1, 'Paket Ekonomis', 'Paket hemat untuk acara sederhana', 200000.00, 10, NULL, '2026-01-05 14:35:57'),
(2, 'Paket Standar', 'Paket lengkap dengan menu pilihan', 350000.00, 10, NULL, '2026-01-05 14:35:57'),
(3, 'Paket Premium', 'Paket mewah dengan menu premium', 500000.00, 10, NULL, '2026-01-05 14:35:57');

-- --------------------------------------------------------

--
-- Table structure for table `paket_detail`
--

CREATE TABLE `paket_detail` (
  `id` int(11) NOT NULL,
  `paket_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `kode_pesanan` varchar(50) NOT NULL,
  `tanggal_acara` date NOT NULL,
  `waktu_acara` time NOT NULL,
  `alamat_pengiriman` text NOT NULL,
  `jumlah_porsi` int(11) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `catatan` text DEFAULT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `status` enum('pending','dikonfirmasi','diproses','selesai','dibatalkan') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_pembayaran` enum('belum_bayar','menunggu_verifikasi','lunas') DEFAULT 'belum_bayar'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id`, `user_id`, `kode_pesanan`, `tanggal_acara`, `waktu_acara`, `alamat_pengiriman`, `jumlah_porsi`, `total_harga`, `catatan`, `metode_pembayaran`, `bukti_pembayaran`, `status`, `created_at`, `status_pembayaran`) VALUES
(3, 3, 'ORD-20260107154506663', '2026-01-07', '15:45:06', 'a', 1, 30000.00, 'a', NULL, NULL, 'selesai', '2026-01-07 14:45:06', 'belum_bayar'),
(4, 3, 'ORD-20260107155500835', '2026-01-07', '15:55:00', 'a', 2, 60000.00, 'a', NULL, NULL, 'selesai', '2026-01-07 14:55:00', 'belum_bayar'),
(5, 2, 'ORD-20260115015316511', '2026-01-15', '01:53:16', 'a', 1, 20000.00, 'a', NULL, NULL, 'dibatalkan', '2026-01-15 00:53:16', 'belum_bayar'),
(6, 2, 'ORD-20260115015948368', '2026-01-15', '01:59:48', 'a', 1, 20000.00, 'a', NULL, NULL, 'dibatalkan', '2026-01-15 00:59:48', 'belum_bayar'),
(7, 2, 'ORD-20260115020128350', '2026-01-15', '02:01:28', 'a', 1, 20000.00, 'a', NULL, NULL, 'dibatalkan', '2026-01-15 01:01:28', 'belum_bayar'),
(8, 3, 'ORD-20260115020346819', '2026-01-15', '02:03:46', 'a', 1, 20000.00, 'a', NULL, NULL, 'pending', '2026-01-15 01:03:46', 'belum_bayar'),
(9, 3, 'ORD-20260115020925318', '2026-01-15', '02:09:25', 'a', 1, 20000.00, 'a', 'Transfer Bank', 'bukti_1768439365_315.jpg', 'pending', '2026-01-15 01:09:25', 'menunggu_verifikasi'),
(10, 3, 'ORD-20260115021538616', '2026-01-15', '02:15:38', 'a', 1, 20000.00, 'a', 'Transfer Bank', 'bukti_1768439738_309.jpg', 'pending', '2026-01-15 01:15:38', 'menunggu_verifikasi');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan_detail`
--

CREATE TABLE `pesanan_detail` (
  `id` int(11) NOT NULL,
  `pesanan_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `paket_id` int(11) DEFAULT NULL,
  `nama_item` varchar(100) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan_detail`
--

INSERT INTO `pesanan_detail` (`id`, `pesanan_id`, `menu_id`, `paket_id`, `nama_item`, `harga`, `jumlah`, `subtotal`) VALUES
(1, 3, 2, NULL, 'Nasi Box Rendang', 30000.00, 1, 30000.00),
(2, 4, 2, NULL, 'Nasi Box Rendang', 30000.00, 2, 60000.00),
(5, 5, 14, NULL, 'Paket Ayam goyeng', 20000.00, 1, 20000.00),
(6, 6, 14, NULL, 'Paket Ayam goyeng', 20000.00, 1, 20000.00),
(7, 7, 14, NULL, 'Paket Ayam goyeng', 20000.00, 1, 20000.00),
(8, 8, 14, NULL, 'Paket Ayam goyeng', 20000.00, 1, 20000.00),
(9, 9, 14, NULL, 'Paket Ayam goyeng', 20000.00, 1, 20000.00),
(10, 10, 14, NULL, 'Paket Ayam goyeng', 20000.00, 1, 20000.00);

-- --------------------------------------------------------

--
-- Table structure for table `testimoni`
--

CREATE TABLE `testimoni` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `pesanan_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `komentar` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `role` enum('admin','customer') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `telepon`, `alamat`, `role`, `created_at`) VALUES
(1, 'Administrator', 'admin@catering.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567890', NULL, 'admin', '2026-01-05 14:35:30'),
(2, 'Azmi Syahri Ramadhan', 'azmiramadhan191005@gmail.com', '$2y$10$5mEnBVF7JAezd7PP2yJNg.FoYgMtBcJPxQiHobE75rQWNnE56rO1C', '085745835415', 'GG.ANCOL UTARA 1 NO.113/36D\r\nLengkong Besar', 'admin', '2026-01-05 15:04:16'),
(3, 'Azmi Syahri Ramadhan', 'azmiramadhan19@gmail.com', '$2y$10$hsoxOV63XmZSABBS0BfL4.5tARn06cBf.5li7jm6xNRovnO6hY8mO', '085745835415', 'GG.ANCOL UTARA 1 NO.113/36D\r\nLengkong Besar', 'customer', '2026-01-05 15:04:41'),
(4, 'Azmi Syahri Ramadhan', 'azmirama199@gmail.com', '$2y$10$gloTTpgD9VY14RBO/Nwd7.WbpOlyKrzDszFCoToHHi3h1gy52lzru', '085745835415', 'GG.ANCOL UTARA 1 NO.113/36D\r\nANCOL UTARA 1', 'customer', '2026-01-05 15:06:58'),
(5, 'Azmi Syahri Ramadhan', 'azmisyh@gmail.com', '$2y$10$Fe3pvLzClkCjCcdgDWxO4O1Gvwm9KASHNn3fehPowL6u0gI8MFfSS', '085745835415', 'Jln. Lengkong Kecil No. 53\r\nLengkong Kecil', 'customer', '2026-01-06 15:08:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_chat_user` (`user_id`,`created_at`),
  ADD KEY `idx_chat_read` (`is_read`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Indexes for table `paket`
--
ALTER TABLE `paket`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paket_detail`
--
ALTER TABLE `paket_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paket_id` (`paket_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pesanan` (`kode_pesanan`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pesanan_detail`
--
ALTER TABLE `pesanan_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pesanan_id` (`pesanan_id`),
  ADD KEY `menu_id` (`menu_id`),
  ADD KEY `paket_id` (`paket_id`);

--
-- Indexes for table `testimoni`
--
ALTER TABLE `testimoni`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `pesanan_id` (`pesanan_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `paket`
--
ALTER TABLE `paket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `paket_detail`
--
ALTER TABLE `paket_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pesanan_detail`
--
ALTER TABLE `pesanan_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `testimoni`
--
ALTER TABLE `testimoni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`);

--
-- Constraints for table `paket_detail`
--
ALTER TABLE `paket_detail`
  ADD CONSTRAINT `paket_detail_ibfk_1` FOREIGN KEY (`paket_id`) REFERENCES `paket` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `paket_detail_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `pesanan_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `pesanan_detail`
--
ALTER TABLE `pesanan_detail`
  ADD CONSTRAINT `pesanan_detail_ibfk_1` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pesanan_detail_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`),
  ADD CONSTRAINT `pesanan_detail_ibfk_3` FOREIGN KEY (`paket_id`) REFERENCES `paket` (`id`),
  ADD CONSTRAINT `pesanan_detail_ibfk_4` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pesanan_detail_ibfk_5` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`),
  ADD CONSTRAINT `pesanan_detail_ibfk_6` FOREIGN KEY (`paket_id`) REFERENCES `paket` (`id`);

--
-- Constraints for table `testimoni`
--
ALTER TABLE `testimoni`
  ADD CONSTRAINT `testimoni_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `testimoni_ibfk_2` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
