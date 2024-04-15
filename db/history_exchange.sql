-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2023 at 08:26 AM
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
-- Table structure for table `history_exchange`
--

CREATE TABLE `history_exchange` (
  `id_hs_ex` int(11) NOT NULL,
  `transactions` double NOT NULL,
  `exchange_plan` double NOT NULL,
  `exchange_data` double NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history_exchange`
--

INSERT INTO `history_exchange` (`id_hs_ex`, `transactions`, `exchange_plan`, `exchange_data`, `date`, `user_id`) VALUES
(1, 1, 5, 5000, '2023-09-19 12:35:29', 1),
(2, 1, 8, 8000, '2023-09-16 12:36:33', 1),
(3, 1, 8, 8000, '2023-09-17 00:36:24', 2),
(4, 1, 8, 8000, '2023-09-17 02:11:33', 2),
(5, 1, 8, 8000, '2023-09-18 02:48:50', 2),
(6, 1, 5, 5000, '2023-09-18 09:16:42', 2),
(7, 1, 5, 5000, '2023-09-18 13:11:41', 2),
(8, 1, 8, 8000, '2023-09-19 06:21:59', 1),
(9, 1, 5, 5000, '2023-09-19 07:40:52', 1),
(10, 1, 8, 8000, '2023-09-20 07:47:37', 2),
(11, 1, 5, 5000, '2023-10-07 01:36:04', 3),
(12, 1, 5, 5000, '2023-10-16 13:24:14', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `history_exchange`
--
ALTER TABLE `history_exchange`
  ADD PRIMARY KEY (`id_hs_ex`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `history_exchange`
--
ALTER TABLE `history_exchange`
  MODIFY `id_hs_ex` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `history_exchange`
--
ALTER TABLE `history_exchange`
  ADD CONSTRAINT `history_exchange_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `register` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
