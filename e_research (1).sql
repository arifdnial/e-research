-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306:3306
-- Generation Time: Jan 13, 2025 at 02:42 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e_research`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `admin_email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `result_id` int(11) DEFAULT NULL,
  `permission_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_email`, `password`, `result_id`, `permission_id`) VALUES
(2, 'syahmi@gmail.com', '67890', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `extension`
--

CREATE TABLE `extension` (
  `extension_id` int(11) NOT NULL,
  `extension_1` mediumblob NOT NULL,
  `extension_date_1` date DEFAULT NULL,
  `extension_2` mediumblob NOT NULL,
  `extension_date_2` date DEFAULT NULL,
  `extension_3` mediumblob NOT NULL,
  `extension_date_3` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `research_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengajian`
--

CREATE TABLE `pengajian` (
  `kategoripengajian` varchar(50) NOT NULL,
  `peringkatpengajian` varchar(50) NOT NULL,
  `universiti` varchar(50) NOT NULL,
  `alamat` varchar(50) NOT NULL,
  `poskod` int(50) NOT NULL,
  `negeri` varchar(50) NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  `kursuspengajian` varchar(50) NOT NULL,
  `id` int(11) NOT NULL,
  `researcher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengajian`
--

INSERT INTO `pengajian` (`kategoripengajian`, `peringkatpengajian`, `universiti`, `alamat`, `poskod`, `negeri`, `jabatan`, `kursuspengajian`, `id`, `researcher_id`) VALUES
('KAJIAN LUAR', 'DIPLOMA', 'UUM', 'SOMBAN', 70400, 'NS', 'ZAKAT', 'CS', 23, 17),
('tempatan', 'diploma', 'unimas', 'No. 24, Jalan Kemboja Indah', 70400, 'n.sembilan', 'Jabatan Akauntansi', 'Diploma Perniagaan ', 24, 19);

-- --------------------------------------------------------

--
-- Table structure for table `penyelia`
--

CREATE TABLE `penyelia` (
  `penyelia_id` int(11) NOT NULL,
  `namapenyelia` text NOT NULL,
  `jawatan` varchar(70) NOT NULL,
  `universiti` text NOT NULL,
  `nophone` int(15) NOT NULL,
  `researcher_id` int(11) NOT NULL,
  `emailpenyelia` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penyelia`
--

INSERT INTO `penyelia` (`penyelia_id`, `namapenyelia`, `jawatan`, `universiti`, `nophone`, `researcher_id`, `emailpenyelia`) VALUES
(1, 'eeyyy', 'kpp', 'UUM', 123434344, 17, ''),
(3, 'Salmah Binti Ibrahim', 'Pensyarah D50', 'Unimas', 139382993, 19, 'salmah@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `perakuan`
--

CREATE TABLE `perakuan` (
  `id` int(11) NOT NULL,
  `researcher_id` int(11) NOT NULL,
  `agreed` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `permission_id` int(11) NOT NULL,
  `accept_letter` mediumblob NOT NULL,
  `accept_date` date DEFAULT NULL,
  `appoint_date` date DEFAULT NULL,
  `appoint_letter` mediumblob NOT NULL,
  `sign` mediumblob NOT NULL,
  `sign_date` date DEFAULT NULL,
  `researcher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`permission_id`, `accept_letter`, `accept_date`, `appoint_date`, `appoint_letter`, `sign`, `sign_date`, `researcher_id`) VALUES
(7, 0x433a5c78616d70705c6874646f63735c652d72657365617263682f75706c6f6164732f363733643833343132353238335f636f64696e672061726475696e6f2e706e67, '2024-11-14', '2024-11-21', 0x433a5c78616d70705c6874646f63735c652d72657365617263682f75706c6f6164732f363733643833343132356533345f636f64696e672061726475696e6f20312e706e67, 0x433a5c78616d70705c6874646f63735c652d72657365617263682f75706c6f6164732f363733643833343132363831365f636f64696e672061726475696e6f20322e706e67, '2024-11-19', 17),
(8, 0x433a5c78616d70705c6874646f63735c652d72657365617263682f75706c6f6164732f464c4f57204241442e706466, '2025-01-06', '2025-01-06', 0x433a5c78616d70705c6874646f63735c652d72657365617263682f75706c6f6164732f736b7269702e706466, 0x433a5c78616d70705c6874646f63735c652d72657365617263682f75706c6f6164732f54454e54415449462053554b414e2042544d2e706466, '2025-01-06', 19);

-- --------------------------------------------------------

--
-- Table structure for table `research`
--

CREATE TABLE `research` (
  `research_id` int(11) NOT NULL,
  `research_code` varchar(50) NOT NULL,
  `research_category` varchar(20) DEFAULT NULL,
  `research_title` varchar(100) NOT NULL,
  `budget` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `research_period` int(11) DEFAULT NULL,
  `balance_day` int(11) DEFAULT NULL,
  `researcher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `research`
--

INSERT INTO `research` (`research_id`, `research_code`, `research_category`, `research_title`, `budget`, `status`, `start_date`, `end_date`, `research_period`, `balance_day`, `researcher_id`) VALUES
(2, 'PPM', 'KAJIAN DALAMAN', 'yuxuke3', 4333.00, '0', '2024-11-05', '2024-12-24', 49, 0, 17),
(3, 'RFP', 'KAJIAN DALAMAN', 'Pembangunan Ilmiah', 40000.00, '0', '2025-01-06', '2025-05-16', 130, 0, 19);

-- --------------------------------------------------------

--
-- Table structure for table `researcher`
--

CREATE TABLE `researcher` (
  `researcher_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `fakulti` varchar(100) DEFAULT NULL,
  `info` varchar(20) DEFAULT NULL,
  `institusi` varchar(30) DEFAULT NULL,
  `password` varchar(50) NOT NULL,
  `no_daftar` varchar(50) NOT NULL,
  `jenis_permohonan` varchar(20) DEFAULT NULL,
  `kategori` varchar(20) DEFAULT NULL,
  `no_matriks` varchar(20) DEFAULT NULL,
  `tarikh_lahir` date DEFAULT NULL,
  `jantina` varchar(10) DEFAULT NULL,
  `kewarganegaraan` varchar(50) DEFAULT NULL,
  `pembiayaan` varchar(20) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `researcher`
--

INSERT INTO `researcher` (`researcher_id`, `name`, `phone`, `email`, `fakulti`, `info`, `institusi`, `password`, `no_daftar`, `jenis_permohonan`, `kategori`, `no_matriks`, `tarikh_lahir`, `jantina`, `kewarganegaraan`, `pembiayaan`, `profile_picture`) VALUES
(17, 'anney', '0112343455', 'ney@gmail.com', 'sains', 'UUM', 'universiti', '12345', '050902050329', 'kumpulan', 'pensyarah', '78787878787', '2024-11-14', 'lelaki', 'Malaysia', 'pinjaman', NULL),
(18, 'ariff danial bin rusli', '01160984398', 'ariffdanial053@gmail.com', 'engineering', 'ukm', 'university', '123', '05546665487', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 'putri', '01788838355', 'putri@gmail.com', 'engineering', 'unimas', 'university', '123', 'PPM-19-25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `result`
--

CREATE TABLE `result` (
  `result_id` int(11) NOT NULL,
  `report_1` mediumblob NOT NULL,
  `date_1` date DEFAULT NULL,
  `report_2` mediumblob NOT NULL,
  `date_2` date DEFAULT NULL,
  `report_3` mediumblob NOT NULL,
  `date_3` date DEFAULT NULL,
  `final_report` mediumblob NOT NULL,
  `research_id` int(11) NOT NULL,
  `researcher_id` int(11) DEFAULT NULL,
  `extension_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `extension`
--
ALTER TABLE `extension`
  ADD PRIMARY KEY (`extension_id`),
  ADD KEY `research_id` (`research_id`);

--
-- Indexes for table `pengajian`
--
ALTER TABLE `pengajian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `researcher_id` (`researcher_id`);

--
-- Indexes for table `penyelia`
--
ALTER TABLE `penyelia`
  ADD PRIMARY KEY (`penyelia_id`),
  ADD KEY `researcher_id` (`researcher_id`);

--
-- Indexes for table `perakuan`
--
ALTER TABLE `perakuan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `researcher_id` (`researcher_id`);

--
-- Indexes for table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`permission_id`),
  ADD KEY `permission_ibfk_3` (`researcher_id`);

--
-- Indexes for table `research`
--
ALTER TABLE `research`
  ADD PRIMARY KEY (`research_id`),
  ADD KEY `fk_researcher` (`researcher_id`);

--
-- Indexes for table `researcher`
--
ALTER TABLE `researcher`
  ADD PRIMARY KEY (`researcher_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `result`
--
ALTER TABLE `result`
  ADD PRIMARY KEY (`result_id`),
  ADD KEY `research_id` (`research_id`),
  ADD KEY `researcher_id` (`researcher_id`),
  ADD KEY `extension_id` (`extension_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `extension`
--
ALTER TABLE `extension`
  MODIFY `extension_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengajian`
--
ALTER TABLE `pengajian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `penyelia`
--
ALTER TABLE `penyelia`
  MODIFY `penyelia_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `perakuan`
--
ALTER TABLE `perakuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permission`
--
ALTER TABLE `permission`
  MODIFY `permission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `research`
--
ALTER TABLE `research`
  MODIFY `research_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `researcher`
--
ALTER TABLE `researcher`
  MODIFY `researcher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `result`
--
ALTER TABLE `result`
  MODIFY `result_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `extension`
--
ALTER TABLE `extension`
  ADD CONSTRAINT `extension_ibfk_1` FOREIGN KEY (`research_id`) REFERENCES `research` (`research_id`);

--
-- Constraints for table `pengajian`
--
ALTER TABLE `pengajian`
  ADD CONSTRAINT `pengajian_ibfk_1` FOREIGN KEY (`researcher_id`) REFERENCES `researcher` (`researcher_id`);

--
-- Constraints for table `penyelia`
--
ALTER TABLE `penyelia`
  ADD CONSTRAINT `penyelia_ibfk_1` FOREIGN KEY (`researcher_id`) REFERENCES `researcher` (`researcher_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `perakuan`
--
ALTER TABLE `perakuan`
  ADD CONSTRAINT `perakuan_ibfk_1` FOREIGN KEY (`researcher_id`) REFERENCES `researcher` (`researcher_id`);

--
-- Constraints for table `permission`
--
ALTER TABLE `permission`
  ADD CONSTRAINT `permission_ibfk_3` FOREIGN KEY (`researcher_id`) REFERENCES `researcher` (`researcher_id`);

--
-- Constraints for table `research`
--
ALTER TABLE `research`
  ADD CONSTRAINT `fk_researcher` FOREIGN KEY (`researcher_id`) REFERENCES `researcher` (`researcher_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `result`
--
ALTER TABLE `result`
  ADD CONSTRAINT `result_ibfk_1` FOREIGN KEY (`research_id`) REFERENCES `research` (`research_id`),
  ADD CONSTRAINT `result_ibfk_2` FOREIGN KEY (`researcher_id`) REFERENCES `researcher` (`researcher_id`),
  ADD CONSTRAINT `result_ibfk_3` FOREIGN KEY (`extension_id`) REFERENCES `extension` (`extension_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
