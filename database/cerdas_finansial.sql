-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Bulan Mei 2025 pada 12.49
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cerdas_finansial`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `tipe` enum('masuk','keluar') DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `tanggal` datetime DEFAULT current_timestamp(),
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id`, `username`, `tipe`, `jumlah`, `tanggal`, `deskripsi`) VALUES
(1, 'Admin', 'masuk', 10000, '2025-05-06 16:57:49', 'Nabung'),
(2, 'Admin', 'keluar', 5000, '2025-05-06 16:58:42', 'Ambil uang'),
(3, 'Admin', 'masuk', 100000, '2025-05-06 17:02:30', 'Nabung'),
(4, 'Admin', 'keluar', 50000, '2025-05-06 17:02:56', 'Tarik'),
(5, 'Admin', 'masuk', 10000, '2025-05-06 17:19:52', 'Nabung'),
(6, 'Admin', 'masuk', 100000, '2025-05-06 17:21:42', 'Nabung'),
(7, 'Admin', 'masuk', 100000, '2025-05-06 17:22:57', 'Nabung'),
(8, 'Admin', 'masuk', 100000, '2025-05-06 17:23:10', 'Nabung'),
(9, 'Admin', 'masuk', 5000000, '2025-05-06 17:23:18', 'Nabung'),
(10, 'Admin', 'keluar', 10000, '2025-05-06 17:35:43', 'Jajan'),
(11, 'Admin', 'keluar', 10000, '2025-05-06 17:39:08', 'Jajan'),
(12, 'Admin', 'keluar', 10000, '2025-05-06 17:39:43', 'Jajan'),
(13, 'Admin', 'keluar', 10000, '2025-05-06 17:39:53', 'Jajan'),
(14, 'Admin', 'keluar', 10000, '2025-05-06 17:40:36', 'Jajan'),
(15, 'Admin', 'keluar', 10000, '2025-05-06 17:42:13', 'Jajan'),
(16, 'Admin', 'keluar', 10000, '2025-05-06 17:42:30', 'Jajan'),
(17, 'Admin', 'keluar', 10000, '2025-05-06 17:43:11', 'Jajan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `saldo` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `nama`, `password`, `saldo`) VALUES
(1, 'Admin', 'Rafif', '$2y$10$bMATNVzwVwfzVVlDACmUde9mUv15J7GExF5dK0LxgFqTLQn1fGq7q', 5285000);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
