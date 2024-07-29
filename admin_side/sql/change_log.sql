-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2024 at 02:17 AM
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
-- Database: `client_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `change_log`
--

CREATE TABLE `change_log` (
  `id` int(11) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `operation` varchar(10) NOT NULL,
  `record_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `change_log`
--

INSERT INTO `change_log` (`id`, `table_name`, `operation`, `record_id`, `admin_id`, `log_time`) VALUES
(1, 'admins', 'DELETE', 8, 2, '2024-07-14 08:42:51'),
(2, 'admins', 'DELETE', 7, 2, '2024-07-14 20:32:57'),
(3, 'admins', 'DELETE', 7, 2, '2024-07-14 20:34:12'),
(4, 'reviews', 'HIDE', 38, 2, '2024-07-14 21:29:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `change_log`
--
ALTER TABLE `change_log`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `change_log`
--
ALTER TABLE `change_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
