-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2025 at 05:34 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `seid_ac_pp`
--

-- --------------------------------------------------------

--
-- Table structure for table `history_ls`
--

CREATE TABLE `history_ls` (
  `id` int(11) NOT NULL,
  `date_prod` date NOT NULL,
  `part_code` varchar(32) NOT NULL,
  `qty_end_press` int(11) NOT NULL,
  `qty_end_paint` int(11) NOT NULL,
  `qty_end_assy` int(11) NOT NULL,
  `qty_bk_press` int(11) NOT NULL,
  `qty_bk_paint` int(11) NOT NULL,
  `qty_bk_assy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history_ls`
--

INSERT INTO `history_ls` (`id`, `date_prod`, `part_code`, `qty_end_press`, `qty_end_paint`, `qty_end_assy`, `qty_bk_press`, `qty_bk_paint`, `qty_bk_assy`) VALUES
(1, '2025-12-23', 'CCHS-B829JBTA', 850, 100, 0, 20, 10, 20),
(2, '2025-12-23', 'GCAB-A646JBTA', 790, 80, 0, 10, 10, 10),
(3, '2025-12-23', 'GCAB-A767JBTA', 700, 50, 0, 0, 0, 0),
(4, '2025-12-23', 'PPLT-B282JBTA', 1800, 100, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `part`
--

CREATE TABLE `part` (
  `part_code` varchar(32) NOT NULL,
  `part_name` varchar(32) NOT NULL,
  `qty_press` int(11) NOT NULL,
  `qty_paint` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `part`
--

INSERT INTO `part` (`part_code`, `part_name`, `qty_press`, `qty_paint`) VALUES
('CCHS-B829JBTA', 'Base Pan', 850, 100),
('GCAB-A646JBTA', 'Top Table', 790, 80),
('GCAB-A767JBTA', 'Front Panel', 700, 50),
('PPLT-B282JBTA', 'Side Cover', 1800, 100);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `part_code` varchar(32) NOT NULL,
  `date_tr` datetime NOT NULL,
  `shift` enum('1','2','3') NOT NULL,
  `qty` int(11) NOT NULL,
  `status` enum('PRESS','PAINT','ASSY') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `part_code`, `date_tr`, `shift`, `qty`, `status`) VALUES
(36, 'CCHS-B829JBTA', '2025-12-22 12:19:19', '1', 1000, 'PRESS'),
(37, 'GCAB-A646JBTA', '2025-12-22 12:19:33', '1', 900, 'PRESS'),
(38, 'GCAB-A767JBTA', '2025-12-22 12:19:37', '1', 800, 'PRESS'),
(39, 'PPLT-B282JBTA', '2025-12-22 12:19:41', '1', 2000, 'PRESS'),
(40, 'CCHS-B829JBTA', '2025-12-22 12:20:08', '1', 200, 'PAINT'),
(41, 'CCHS-B829JBTA', '2025-12-22 12:20:19', '1', 200, 'PAINT'),
(42, 'CCHS-B829JBTA', '2025-12-22 12:20:40', '1', 400, 'PAINT'),
(43, 'CCHS-B829JBTA', '2025-12-22 12:23:18', '1', 300, 'ASSY'),
(44, 'CCHS-B829JBTA', '2025-12-22 12:23:27', '1', 200, 'ASSY'),
(45, 'CCHS-B829JBTA', '2025-12-23 09:54:06', '1', 500, 'PRESS'),
(46, 'CCHS-B829JBTA', '2025-12-23 10:04:17', '1', 200, 'ASSY'),
(47, 'CCHS-B829JBTA', '2025-12-23 10:40:30', '1', 200, 'PRESS'),
(48, 'CCHS-B829JBTA', '2025-12-23 11:08:09', '1', 30, 'PAINT'),
(49, 'GCAB-A646JBTA', '2025-12-23 11:08:13', '1', 100, 'PAINT'),
(50, 'GCAB-A767JBTA', '2025-12-23 11:08:16', '1', 100, 'PAINT'),
(51, 'PPLT-B282JBTA', '2025-12-23 11:08:19', '1', 200, 'PAINT'),
(52, 'CCHS-B829JBTA', '2025-12-23 11:08:34', '1', 50, 'ASSY'),
(53, 'GCAB-A646JBTA', '2025-12-23 11:08:37', '1', 50, 'ASSY'),
(54, 'PPLT-B282JBTA', '2025-12-23 11:09:03', '1', 100, 'ASSY'),
(55, 'GCAB-A767JBTA', '2025-12-23 11:09:06', '1', 50, 'ASSY'),
(56, 'GCAB-A646JBTA', '2025-12-23 11:09:09', '1', 50, 'ASSY'),
(57, 'CCHS-B829JBTA', '2025-12-23 11:09:12', '1', 50, 'ASSY');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `role` enum('Admin','Press','Paint','Assy') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `password`, `role`) VALUES
('admin01', 'SeidMail01', 'Admin'),
('assy01', 'SeidMail01', 'Assy'),
('paint01', 'SeidMail01', 'Paint'),
('press01', 'SeidMail01', 'Press');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `history_ls`
--
ALTER TABLE `history_ls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `part`
--
ALTER TABLE `part`
  ADD PRIMARY KEY (`part_code`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `history_ls`
--
ALTER TABLE `history_ls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
