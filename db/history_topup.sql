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
-- Table structure for table `history_topup`
--

CREATE TABLE `history_topup` (
  `id` int(11) NOT NULL,
  `card_number` double NOT NULL,
  `service_by` varchar(255) NOT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `register_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history_topup`
--

INSERT INTO `history_topup` (`id`, `card_number`, `service_by`, `transaction_date`, `register_id`) VALUES
(1, 46413536364775, 'Metfone', '2023-09-16 17:00:00', 1),
(2, 267140043188982, 'Smart', '2023-09-16 17:00:00', 1),
(3, 2035183939, 'Cellcard', '2023-09-16 23:38:05', 1),
(4, 663490232154123, 'Smart', '2023-09-17 00:35:17', 2),
(5, 145980301760755, 'Smart', '2023-09-17 02:11:11', 2),
(6, 31180091079990, 'Smart', '2023-09-17 10:03:44', 2),
(7, 3165862944, 'Cellcard', '2023-09-17 10:05:02', 2),
(8, 440004497585529, 'Smart', '2023-09-17 10:08:52', 2),
(9, 440004497585529, 'Smart', '2023-09-17 10:08:52', 2),
(10, 14850545782348, 'Metfone', '2023-09-17 10:12:32', 2),
(11, 583624898, 'Cellcard', '2023-09-17 10:13:12', 2),
(12, 209933117702075, 'Smart', '2023-09-18 02:48:40', 2),
(13, 322801417239482, 'Smart', '2023-09-18 09:15:15', 2),
(14, 13940506435308, 'Metfone', '2023-09-19 03:48:48', 2),
(15, 947711727962599, 'Smart', '2023-09-19 08:01:11', 1),
(16, 1306459064, 'Cellcard', '2023-09-19 08:22:29', 1),
(17, 989676552119978, 'Smart', '2023-09-19 09:16:05', 1),
(18, 68162645345534, 'Smart', '2023-09-19 12:40:18', 2),
(19, 213583565705686, 'Smart', '2023-09-20 07:47:19', 2),
(20, 116941650173745, 'Smart', '2023-09-20 23:13:10', 2),
(21, 80899037310544, 'Metfone', '2023-09-20 23:13:24', 2),
(22, 8891019921, 'Cellcard', '2023-09-20 23:13:44', 2),
(23, 739348364834891, 'Smart', '2023-09-21 00:36:37', 2),
(24, 2300304309, 'Cellcard', '2023-10-07 01:31:39', 3),
(25, 13595272735724, 'Metfone', '2023-10-07 01:32:44', 3),
(26, 306815172741948, 'Smart', '2023-10-07 01:33:06', 3),
(27, 684346638807559, 'Smart', '2023-10-16 13:23:42', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `history_topup`
--
ALTER TABLE `history_topup`
  ADD PRIMARY KEY (`id`),
  ADD KEY `register_id` (`register_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `history_topup`
--
ALTER TABLE `history_topup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `history_topup`
--
ALTER TABLE `history_topup`
  ADD CONSTRAINT `history_topup_ibfk_1` FOREIGN KEY (`register_id`) REFERENCES `register` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
