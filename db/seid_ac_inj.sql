-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Des 2025 pada 14.36
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
-- Database: `seid_ac_inj`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `history_ls`
--

CREATE TABLE `history_ls` (
  `id` int(11) NOT NULL,
  `date_prod` date NOT NULL,
  `part_code` varchar(32) NOT NULL,
  `qty_end_injection` int(11) NOT NULL,
  `qty_end_assy` int(11) NOT NULL,
  `qty_bk_injection` int(11) NOT NULL,
  `qty_bk_assy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `part`
--

CREATE TABLE `part` (
  `part_code` varchar(32) NOT NULL,
  `part_name` varchar(32) NOT NULL,
  `qty_injection` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `part`
--

INSERT INTO `part` (`part_code`, `part_name`, `qty_injection`) VALUES
('ABC', 'Test', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `part_code` varchar(32) NOT NULL,
  `date_tr` datetime NOT NULL,
  `shift` enum('1','2','3') NOT NULL,
  `qty` int(11) NOT NULL,
  `status` enum('INJECTION','ASSY') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `role` enum('Admin','Injection','Assy') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`username`, `password`, `role`) VALUES
('admin01', 'SeidMail01', 'Admin'),
('assy01', 'SeidMail01', 'Assy'),
('injection01', 'SeidMail01', 'Injection');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `history_ls`
--
ALTER TABLE `history_ls`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `part`
--
ALTER TABLE `part`
  ADD PRIMARY KEY (`part_code`);

--
-- Indeks untuk tabel `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `history_ls`
--
ALTER TABLE `history_ls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
