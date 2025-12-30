-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 30 Des 2025 pada 01.28
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

--
-- Dumping data untuk tabel `history_ls`
--

INSERT INTO `history_ls` (`id`, `date_prod`, `part_code`, `qty_end_injection`, `qty_end_assy`, `qty_bk_injection`, `qty_bk_assy`) VALUES
(5, '2025-12-29', 'ABC', 600, 0, 50, 50),
(6, '2025-12-29', 'DEF', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `part`
--

CREATE TABLE `part` (
  `part_code` varchar(32) NOT NULL,
  `part_name` varchar(32) NOT NULL,
  `qty_injection` int(11) NOT NULL,
  `area` enum('WM','AC') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `part`
--

INSERT INTO `part` (`part_code`, `part_name`, `qty_injection`, `area`) VALUES
('ABC', 'Test', 600, 'WM'),
('DEF', 'Test 2', 0, 'AC');

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

--
-- Dumping data untuk tabel `transaction`
--

INSERT INTO `transaction` (`id`, `part_code`, `date_tr`, `shift`, `qty`, `status`) VALUES
(32, 'ABC', '2025-12-29 20:48:37', '2', 500, 'INJECTION'),
(33, 'ABC', '2025-12-29 20:48:54', '2', 200, 'ASSY');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `role` enum('Admin','Injection','Assy') NOT NULL,
  `area` enum('WM','AC','ALL') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`username`, `password`, `role`, `area`) VALUES
('admin01', 'SeidMail01', 'Admin', 'ALL'),
('assy01', 'SeidMail01', 'Assy', 'AC'),
('injection01', 'SeidMail01', 'Injection', 'ALL'),
('wm01', 'SeidMail01', 'Assy', 'WM');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
