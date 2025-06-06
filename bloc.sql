-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 11, 2024 at 09:20 AM
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
-- Database: `fcs_connect`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'user',
  `username` varchar(100) NOT NULL,
  `image` varchar(100) NOT NULL DEFAULT 'default-avatar.png',
  `status` varchar(255) NOT NULL DEFAULT 'deactivate',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `contact` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `blocked` enum('activate','blocked') NOT NULL,
  `block` enum('no','yes') NOT NULL,
  `farmer_select` varchar(255) NOT NULL,
  `qr_code` varchar(255) NOT NULL,
  `number` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`, `username`, `image`, `status`, `created_at`, `contact`, `address`, `description`, `blocked`, `block`, `farmer_select`, `qr_code`, `number`) VALUES
(1, 'Maricris Betarmos', 'maricris@gmail.com', '698d51a19d8a121ce581499d7b701668', 'user', 'Ate Maricris', 'default-avatar.png', 'deactivate', '2024-11-05 23:10:29', '08796867567', 'inabanga', '', '', '', '', '', '04243323'),
(2, 'Janine Sumalinog', 'janine@gmail.com', '698d51a19d8a121ce581499d7b701668', 'user', 'Ate Janine', 'default-avatar.png', 'activate', '2024-11-11 08:11:00', '09079424592', 'buenavista', 'sbcksbs1', '', 'no', '', '', '0875876435'),
(3, 'Jolery Sevilla', 'jolery@gmail.com', '698d51a19d8a121ce581499d7b701668', 'admin', 'Ate Jolery', 'default-avatar.png', 'deactivate', '2024-11-11 02:29:34', '0907942459244', 'buenavista', 'sbcksbs1', '', 'no', 'farmer1', '', ''),
(4, 'Jay Vincent Petalcorin', 'jay@gmail.com', '698d51a19d8a121ce581499d7b701668', 'super_admin', 'Jay Vincent', 'default-avatar.png', 'deactivate', '2024-11-11 08:10:54', '', '', '', '', '', '', '', ''),
(5, 'Yestin', 'yestin@gmail.com', '698d51a19d8a121ce581499d7b701668', 'admin', 'Yestin', 'default-avatar.png', 'deactivate', '2024-11-07 04:56:28', '09308049134', '', '', '', 'no', 'farmer1', '', ''),
(6, 'Princess Petalcorin', 'princess@gmail.com', '698d51a19d8a121ce581499d7b701668', 'user', 'Princess123', 'default-avatar.png', 'activate', '2024-09-30 13:27:17', '', '', '', '', '', '', '', ''),
(7, 'Dave Petalcorin', 'dave@gmail.com', '698d51a19d8a121ce581499d7b701668', 'user', 'Dave ', 'default-avatar.png', 'activate', '2024-09-30 13:28:11', '', '', '', '', '', '', '', ''),
(8, 'Renan Jade Petalcorin', 'renan@gmail.com', '698d51a19d8a121ce581499d7b701668', 'user', 'Renan Jade', 'default-avatar.png', 'activate', '2024-09-30 13:29:21', '', '', '', '', '', '', '', ''),
(9, 'Saturnino Sumalinog', 'saturnino@gmail.com', '698d51a19d8a121ce581499d7b701668', 'admin', 'Saturnino', 'default-avatar.png', 'deactivate', '2024-11-11 07:17:34', '', '', '', '', 'no', '', '', ''),
(10, 'Reynaldo Sevilla', 'reynaldo@gmail.com', '698d51a19d8a121ce581499d7b701668', 'admin', 'Reynaldo', 'default-avatar.png', 'deactivate', '2024-11-11 07:19:10', '', '', '', '', '', 'farmer1', 'IMG_20241111_151528.jpg', ''),
(26, 'Janine Sumalinog', 'janinesumalinog19@gmail.com', '202cb962ac59075b964b07152d234b70', 'user', 'janine', '', 'deactivate', '2024-11-07 05:56:55', '', '', '', 'activate', 'yes', '', '', ''),
(27, 'farmers', 'farmers@gmail.com', '202cb962ac59075b964b07152d234b70', 'admin', 'farmers', 'default-avatar.png', 'activate', '2024-10-24 06:25:11', '', '', '', '', '', '', '', ''),
(28, 'werw', 'jolerywe@gmail.com', '698d51a19d8a121ce581499d7b701668', 'user', 'janine@gmail.com', '', 'deactivate', '2024-11-11 07:17:18', '', 'flat no. Purok 7, Baas, Bugaong, Maribojoc, Philippines - 6333', '', 'activate', 'no', '', '', '35435'),
(29, 'Adelina Petalcorin', 'adel@gmail.com', '698d51a19d8a121ce581499d7b701668', 'user', 'Ate Adel', '', 'activate', '2024-11-06 08:35:04', '', 'flat no. Purok 7, Baas, Lincod, Maribojoc, Philippines - 6366', '', 'activate', 'no', '', '', '09308049134'),
(30, 'vincent', 'vincent@gmail.com', '698d51a19d8a121ce581499d7b701668', 'user', 'vincent', '', 'deactivate', '2024-11-07 06:06:32', '', 'flat no. Purok 7, Baas, Bugaong, Maribojoc, Philippines - 6333', '', 'activate', 'no', '', '', '09308049134'),
(31, 'whwhe', 'jolery2@gmail.com', '698d51a19d8a121ce581499d7b701668', 'user', 'sbdb', '', 'deactivate', '2024-11-11 07:44:05', '', 'flat no. Purok 7, Baas, Bugaong, Maribojoc, Philippines - 6333', '', 'activate', 'no', '', '', '07876');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
