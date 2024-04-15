-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2023 at 08:27 AM
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
-- Database: `exchange_validate`
--

-- --------------------------------------------------------

--
-- Table structure for table `main_bland_table`
--

CREATE TABLE `main_bland_table` (
  `id_mb` int(11) NOT NULL,
  `id_number` varchar(50) DEFAULT NULL,
  `main_bland` double DEFAULT NULL,
  `topup_activity` double NOT NULL,
  `num_bland` double DEFAULT NULL,
  `sv_by` varchar(50) NOT NULL,
  `register_id` int(11) DEFAULT NULL,
  `topup_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `main_bland_table`
--

INSERT INTO `main_bland_table` (`id_mb`, `id_number`, `main_bland`, `topup_activity`, `num_bland`, `sv_by`, `register_id`, `topup_date`, `created_at`, `updated_at`) VALUES
(1, '2147483647', 0, 0, 684346638807559, 'Smart', 1, '2023-09-19', '2023-09-16 12:33:38', '2023-10-16 13:23:42'),
(2, '65064996b68c79643', 3, 0, 739348364834891, 'Smart', 2, '2023-09-19', '2023-09-17 00:35:17', '2023-09-21 00:36:37'),
(3, '650b7f2acc8432362', 2, 3, 306815172741948, 'Smart', 3, '2023-10-07', '2023-10-07 01:31:39', '2023-10-07 01:33:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `main_bland_table`
--
ALTER TABLE `main_bland_table`
  ADD PRIMARY KEY (`id_mb`),
  ADD KEY `register_id` (`register_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `main_bland_table`
--
ALTER TABLE `main_bland_table`
  MODIFY `id_mb` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `main_bland_table`
--
ALTER TABLE `main_bland_table`
  ADD CONSTRAINT `main_bland_table_ibfk_1` FOREIGN KEY (`register_id`) REFERENCES `register` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
