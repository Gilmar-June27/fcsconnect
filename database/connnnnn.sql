-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 27, 2024 at 08:33 AM
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
-- Database: `connnnnn`
--

-- --------------------------------------------------------

--
-- Table structure for table `added_admins`
--

CREATE TABLE `added_admins` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `added_admins`
--

INSERT INTO `added_admins` (`id`, `user_id`, `admin_id`, `created_at`) VALUES
(1, 9, 10, '2024-07-30 16:23:16'),
(2, 9, 14, '2024-08-01 10:25:19'),
(3, 9, 16, '2024-08-01 10:26:26'),
(0, 15, 10, '2024-08-23 04:57:54'),
(0, 17, 10, '2024-08-24 14:13:59'),
(0, 6, 10, '2024-08-27 06:27:38');

-- --------------------------------------------------------

--
-- Table structure for table `breakdowns`
--

CREATE TABLE `breakdowns` (
  `id` int(11) NOT NULL,
  `admin_id` int(100) NOT NULL,
  `breakdown` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `money_invest` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `breakdowns`
--

INSERT INTO `breakdowns` (`id`, `admin_id`, `breakdown`, `created_at`, `money_invest`) VALUES
(7, 10, 'seedling ', '2024-07-30 09:28:20', '1500'),
(8, 10, 'nanaanan', '2024-07-30 10:08:17', '233'),
(9, 10, 'new', '2024-07-30 13:30:06', '12313'),
(10, 10, 'dgdf', '2024-08-15 14:23:52', '5435');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `quantity` int(100) NOT NULL,
  `image` varchar(100) NOT NULL,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `name`, `price`, `quantity`, `image`, `admin_id`) VALUES
(57, 1, 'pechay', 12, 1, '2.jpg', 0),
(61, 0, '', 0, 0, '', 0),
(65, 6, 'oiukuy', 66, 1, 'p-1.jpg', 0),
(66, 6, 'sdeswaf', 5555, 1, 'p-2.jpg', 0),
(85, 18, 'fgd', 1, 3, '1.jpg', 0),
(86, 18, 'qwdwd', 34, 1, 'banner1.png', 0),
(87, 18, 'oiukuyee', 66, 3, '1.jpg', 0),
(88, 8, 'qwdwd', 34, 3, 'banner1.png', 0),
(137, 0, 'WEFWEF', 55, 1, '_15ee6cf6-0dab-4c69-b5d4-4b6b7038dace.jpg', 0),
(360, 15, 'ewrew', 55, 1, 'promo3.jpg', 14),
(361, 15, 'redtged', 456, 1, '15.jpg', 14),
(373, 6, 'ewrew', 55, 1, 'promo3.jpg', 14),
(374, 0, 'ewrew', 55, 1, 'promo3.jpg', 14),
(375, 6, 'ako', 546, 1, 'promo2.jpg', 10);

-- --------------------------------------------------------

--
-- Table structure for table `investments`
--

CREATE TABLE `investments` (
  `id` int(11) NOT NULL,
  `admin_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `profit_sharing` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `funding` varchar(255) NOT NULL,
  `profit_sharing_percentage` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `investments`
--

INSERT INTO `investments` (`id`, `admin_id`, `name`, `profit_sharing`, `address`, `description`, `funding`, `profit_sharing_percentage`, `created_at`) VALUES
(1, 10, 'JANINE SUMALINOG', '', 'brgy.sweetland buenavista bohol', 'rtuyuhtuj', '0.04', '0.03', '2024-07-30 09:07:19'),
(2, 10, 'JANINE SUMALINOG', '321123214', 'brgy.sweetland buenavista bohol', 'rtuyuhtuj', '0.03', '-0.02', '2024-07-30 09:07:57'),
(3, 10, 'JANINE SUMALINOG', '2000', 'brgy.sweetland buenavista bohol', 'rtuyuhtuj', '111', '222', '2024-07-30 10:20:09'),
(4, 10, 'JANINE SUMALINOG', '222', 'brgy.sweetland buenavista bohol', 'rtuyuhtuj', '333', '111', '2024-07-30 10:20:19'),
(5, 14, 'new admin', '35345', 'fdghfghfghjfgj', 'fgjghjghj', '34534', '2355', '2024-08-01 10:18:10');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `message` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin_id` int(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `is_read` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `user_id`, `name`, `email`, `number`, `message`, `created_at`, `admin_id`, `image`, `is_read`) VALUES
(136, 10, 'JANINE SUMALINOG', 'aaaa@gmail.com', '', 'ikaw ni', '2024-07-26 09:23:39', 9, '', 0),
(137, 9, 'akosdsafdsf', 'tiktoksexiest99@gmail.com', '', 'gferg', '2024-07-26 09:24:46', 10, '', 1),
(138, 9, 'akosdsafdsf', 'tiktoksexiest99@gmail.com', '', 'wqerdweq', '2024-07-26 09:24:59', 10, '', 1),
(139, 10, 'JANINE SUMALINOG', 'aaaa@gmail.com', '', 'rthtrh', '2024-07-26 09:29:24', 9, '', 0),
(140, 10, 'JANINE SUMALINOG', 'aaaa@gmail.com', '', 'erthgyret', '2024-07-26 09:29:46', 9, '', 0),
(141, 10, 'JANINE SUMALINOG', 'aaaa@gmail.com', '', 'wetwre', '2024-07-26 09:30:13', 9, '', 0),
(142, 17, 'mary', 'mary@gmail.com', '', 'retgre', '2024-07-26 09:30:53', 10, '', 1),
(143, 10, 'JANINE SUMALINOG', 'aaaa@gmail.com', '', 'hgjghj', '2024-07-26 09:53:57', 17, '', 0),
(144, 17, 'mary', 'mary@gmail.com', '', 'q', '2024-07-26 09:54:21', 10, 'images/10.jpg', 1),
(145, 10, 'JANINE SUMALINOG', 'aaaa@gmail.com', '', '5y546y4', '2024-07-26 09:55:20', 17, '', 0),
(146, 10, 'JANINE SUMALINOG', 'aaaa@gmail.com', '', 'ree', '2024-07-26 10:04:47', 17, '', 0),
(147, 10, 'JANINE SUMALINOG', 'aaaa@gmail.com', '', 'rt', '2024-07-26 10:17:02', 17, '', 0),
(148, 10, 'JANINE SUMALINOG', 'aaaa@gmail.com', '', 'ryut6y', '2024-07-26 10:41:23', 17, '', 0),
(149, 10, 'JANINE SUMALINOG', 'aaaa@gmail.com', '', 'rtytru', '2024-07-26 10:51:51', 17, '', 0),
(150, 15, 'ako', 'Gilmarst99@gmail.com', '', 'gfd', '2024-07-26 11:07:50', 10, 'images/6.jpg', 1),
(151, 10, 'JANINE SUMALINOG', 'aaaa@gmail.com', '', 'wqdrq', '2024-07-26 11:10:08', 15, 'images/banner2.jpg', 0),
(152, 15, 'ako', 'Gilmarst99@gmail.com', '', 'hgrfgh', '2024-07-26 14:08:47', 14, '', 1),
(153, 14, 'new admin', 'new@new.com', '', 'uyiuyi', '2024-07-26 14:08:58', 15, '', 0),
(154, 14, 'new admin', 'new@new.com', '', 'tr', '2024-07-26 14:09:16', 15, 'images/_f2acaf43-1b8e-4ec0-9e48-f2be2a6a6423.jpg', 0),
(155, 14, 'new admin', 'new@new.com', '', 'ereger', '2024-07-27 12:11:15', 15, 'images/_15ee6cf6-0dab-4c69-b5d4-4b6b7038dace.jpg', 0),
(156, 14, 'new admin', 'new@new.com', '', 'erte', '2024-07-27 12:11:20', 15, '', 0),
(157, 9, 'akosdsafdsf', 'tiktoksexiest99@gmail.com', '', ' hello, this is u', '2024-07-29 09:00:33', 14, 'images/Screenshot 2024-02-09 085547.png', 1),
(158, 14, 'new admin', 'new@new.com', '', 'yes', '2024-07-29 09:01:26', 9, '', 0),
(159, 9, 'akosdsafdsf', 'tiktoksexiest99@gmail.com', '', 'do you know me', '2024-07-29 09:01:47', 14, '', 1),
(160, 14, 'new admin', 'new@new.com', '', 'no', '2024-07-29 09:01:59', 9, '', 0),
(161, 9, 'akosdsafdsf', 'tiktoksexiest99@gmail.com', '', 'reger', '2024-07-29 12:34:44', 14, '', 1),
(162, 14, 'new admin', 'new@new.com', '', 'ftgret', '2024-07-29 12:34:59', 9, '', 0),
(163, 9, 'akosdsafdsf', 'tiktoksexiest99@gmail.com', '', 'ertger', '2024-07-29 12:39:25', 14, '', 1),
(164, 9, 'akosdsafdsf', 'tiktoksexiest99@gmail.com', '', 'haha', '2024-07-29 12:39:30', 14, '', 1),
(165, 9, 'akosdsafdsf', 'tiktoksexiest99@gmail.com', '', 'rtytr', '2024-07-29 12:43:51', 10, '', 1),
(166, 14, 'new admin', 'new@new.com', '', 'erygrety', '2024-07-29 12:51:40', 9, '', 0),
(167, 9, 'akosdsafdsf', 'tiktoksexiest99@gmail.com', '', 'dfgdfg', '2024-07-29 12:54:00', 14, '', 1),
(168, 14, 'new admin', 'new@new.com', '', 'ytrtytryhtrhyrhtr', '2024-07-29 13:02:51', 9, '', 0),
(169, 14, 'new admin', 'new@new.com', '', 'ako ni', '2024-07-29 13:04:02', 9, '', 0),
(170, 9, 'akosdsafdsf', 'tiktoksexiest99@gmail.com', '', 'yrtyrthytruhyh', '2024-07-29 13:04:47', 14, '', 1),
(171, 14, 'new admin', 'new@new.com', '', 'admin', '2024-07-29 13:05:53', 9, '', 0),
(172, 9, 'akosdsafdsf', 'tiktoksexiest99@gmail.com', '', 'si ako ni', '2024-07-29 13:06:25', 14, '', 1),
(173, 14, 'new admin', 'new@new.com', '', 'ergrt', '2024-07-29 13:14:36', 9, '', 0),
(174, 14, 'new admin', 'new@new.com', '', 'brhyt', '2024-07-29 13:14:38', 9, '', 0),
(175, 14, 'new admin', 'new@new.com', '', 'erwfgerg', '2024-07-29 13:18:26', 9, '', 0),
(176, 9, 'akosdsafdsf', 'tiktoksexiest99@gmail.com', '', '5rtyghtt', '2024-07-29 13:18:48', 14, '', 1),
(177, 14, 'new admin', 'new@new.com', '', 'fghrtfh', '2024-07-29 13:18:59', 9, '', 0),
(178, 10, 'JANINE SUMALINOG', 'aaaa@gmail.com', '', 'https://chatgpt.com/c/40e17d92-533e-4a8e-8be3-2fb6d4328d91', '2024-07-31 02:39:05', 9, '', 0),
(179, 9, 'akosdsafdsf', 'tiktoksexiest99@gmail.com', '', 'dfgreyt', '2024-07-31 12:21:18', 14, '', 1),
(180, 9, 'akosdsafdsf', 'tiktoksexiest99@gmail.com', '', 'gg', '2024-07-31 12:21:23', 14, '', 1),
(181, 9, 'akosdsafdsf', 'tiktoksexiest99@gmail.com', '', 'rtyhtr', '2024-07-31 12:50:45', 14, '', 1),
(182, 9, 'akosdsafdsf', 'tiktoksexiest99@gmail.com', '', 'pp', '2024-07-31 12:50:49', 14, '', 1),
(183, 9, 'akosdsafdsf', 'tiktoksexiest99@gmail.com', '', '8oiy', '2024-07-31 12:54:28', 10, '', 1),
(184, 9, 'akosdsafdsf', 'tiktoksexiest99@gmail.com', '', 'dsgf', '2024-07-31 13:22:51', 10, '', 1),
(185, 9, 'akosdsafdsf', 'tiktoksexiest99@gmail.com', '', 'dddd', '2024-07-31 13:23:04', 14, '', 1),
(186, 9, 'akosdsafdsf', 'tiktoksexiest99@gmail.com', '', 'ertre', '2024-07-31 13:29:34', 14, '', 1),
(187, 14, 'new admin', 'new@new.com', '', 'tert', '2024-07-31 13:31:04', 9, '', 0),
(188, 9, 'akosdsafdsf', 'tiktoksexiest99@gmail.com', '', 'wertew4tr', '2024-07-31 13:33:34', 14, '', 1),
(189, 9, 'akosdsafdsf', 'tiktoksexiest99@gmail.com', '', 'qwdqwerdfwe', '2024-07-31 13:37:26', 14, '', 1),
(190, 14, 'new admin', 'new@new.com', '', 'y79i7y', '2024-08-01 00:36:23', 9, '', 0),
(191, 15, 'ako', 'Gilmarst99@gmail.com', '', 'fyhjytju', '2024-08-01 09:09:23', 16, '', 1),
(192, 15, 'ako', 'Gilmarst99@gmail.com', '', 'sdfgdrgdrf', '2024-08-01 09:09:59', 10, '', 1),
(193, 14, 'new admin', 'new@new.com', '', 'fgthrtfgjty', '2024-08-01 09:10:23', 15, '', 0),
(194, 14, 'new admin', 'new@new.com', '', 'ewgferw', '2024-08-01 09:10:26', 15, '', 0),
(195, 9, 'akosdsafdsf', 'tiktoksexiest99@gmail.com', '', 'ertretgretyergyreyreyret', '2024-08-01 09:11:34', 14, '', 1),
(196, 14, 'new admin', 'new@new.com', '', 'hi', '2024-08-01 09:12:12', 9, '', 0),
(197, 9, 'akosdsafdsf', 'tiktoksexiest99@gmail.com', '', 'lo', '2024-08-01 09:12:23', 14, '', 1),
(198, 10, 'JANINE SUMALINOG', 'aaaa@gmail.com', '', 'ddddddddde', '2024-08-14 10:55:49', 15, 'images/15.jpg', 0),
(199, 10, 'JANINE SUMALINOG', 'aaaa@gmail.com', '', 'wdwd', '2024-08-14 10:56:03', 15, 'images/15.jpg', 0),
(200, 6, 'gilmar', 'Gilmarst99@gmail.com', '', 'saxa', '2024-08-23 22:21:45', 17, '', 1),
(201, 17, 'mary', 'mary@gmail.com', '', 'scsd', '2024-08-23 22:23:17', 6, '', 1),
(202, 17, 'mary', 'mary@gmail.com', '', 'wfw', '2024-08-23 22:24:04', 6, 'images/15.jpg', 1),
(203, 17, 'mary', 'mary@gmail.com', '', 'wdwe', '2024-08-23 22:24:27', 6, '', 1),
(204, 17, 'mary', 'mary@gmail.com', '', 'dd', '2024-08-23 22:24:40', 6, '', 1),
(205, 17, 'mary', 'mary@gmail.com', '', 'qdwqq', '2024-08-23 22:24:52', 6, '', 1),
(206, 17, 'mary', 'mary@gmail.com', '', 'wqqwq', '2024-08-23 22:25:02', 6, '', 1),
(207, 17, 'mary', 'mary@gmail.com', '', 'dfgddf', '2024-08-23 22:38:40', 6, '', 1),
(208, 17, 'mary', 'mary@gmail.com', '', 'oist', '2024-08-23 22:59:17', 15, 'images/banner2.jpg', 1),
(209, 17, 'mary', 'mary@gmail.com', '', '', '2024-08-23 23:01:46', 15, 'images/banner1.png', 1),
(210, 6, 'gilmar', 'Gilmarst99@gmail.com', '', 'cdsc', '2024-08-23 23:58:35', 17, '', 1),
(211, 17, 'mary', 'mary@gmail.com', '', 'who', '2024-08-24 13:31:50', 15, '', 1),
(212, 17, 'mary', 'mary@gmail.com', '', 'ass', '2024-08-24 13:32:16', 10, '', 1),
(213, 17, 'mary', 'mary@gmail.com', '', 'yo', '2024-08-24 13:58:44', 13, '', 1),
(214, 10, 'JANINE SUMALINOG', 'aaaa@gmail.com', '', 'dwef', '2024-08-25 02:07:24', 15, '', 0),
(215, 17, 'mary', 'mary@gmail.com', '', '', '2024-08-25 03:15:41', 14, 'images/10.jpg', 1),
(216, 17, 'mary', 'mary@gmail.com', '', '', '2024-08-25 03:20:31', 16, 'images/banner2.jpg', 1),
(217, 17, 'mary', 'mary@gmail.com', '', '', '2024-08-25 03:20:42', 16, 'images/banner1.png', 1),
(218, 17, 'mary', 'mary@gmail.com', '', '', '2024-08-25 03:22:25', 16, 'images/10.jpg', 1),
(219, 17, 'mary', 'mary@gmail.com', '', '', '2024-08-25 03:25:30', 3, 'images/15.jpg', 1),
(220, 3, 'admin', 'admin@business.com', '', 'csdc', '2024-08-25 03:27:18', 17, '', 0),
(221, 3, 'admin', 'admin@business.com', '', 'ncccccgh', '2024-08-25 03:32:05', 17, 'images/_f2acaf43-1b8e-4ec0-9e48-f2be2a6a6423.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `msg` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`, `msg`) VALUES
(34, 9, 'u6tu8 ', 1, '2024-07-23 11:13:21', 'by : JANINE SUMALINOG'),
(35, 9, 'u6tu8 ', 1, '2024-07-23 11:14:34', 'by : JANINE SUMALINOG'),
(36, 9, ' ', 1, '2024-07-23 11:14:38', 'by : JANINE SUMALINOG'),
(37, 9, ' ', 1, '2024-07-23 11:14:39', 'by : JANINE SUMALINOG'),
(38, 9, 'reter', 1, '2024-07-23 11:22:07', 'by: JANINE SUMALINOG'),
(39, 9, 'trr5ryrtyrty', 1, '2024-07-23 11:22:30', 'by: JANINE SUMALINOG'),
(40, 9, 'gilmar', 1, '2024-07-23 11:22:41', 'by: JANINE SUMALINOG'),
(41, 9, 'Your Order has been completed by <span style=\'color: green;\'>JANINE SUMALINOG</span>', 1, '2024-07-23 11:28:08', ''),
(42, 9, 'Your Order has been completed', 1, '2024-07-23 11:36:09', 'by <span style=\'color: green;\'>JANINE SUMALINOG</span>'),
(43, 9, 'wefwe', 1, '2024-07-23 11:36:39', ' JANINE SUMALINOG'),
(44, 9, 'Your Order has been completed', 1, '2024-07-23 11:37:26', 'by <span style=\'color: green;\'>JANINE SUMALINOG</span>'),
(45, 9, 'Your Order has been completed', 1, '2024-07-23 11:38:29', 'JANINE SUMALINOG'),
(46, 9, 'Your Order has been completed', 1, '2024-07-23 11:38:33', 'JANINE SUMALINOG'),
(47, 9, 'Your Order has been completed', 1, '2024-07-23 13:11:30', 'JANINE SUMALINOG'),
(48, 9, 'Your Order has been completed', 1, '2024-07-23 13:14:25', 'JANINE SUMALINOG'),
(49, 9, 'Your Order has been completed', 1, '2024-07-23 13:15:32', 'JANINE SUMALINOG'),
(50, 9, 'Your Order has been completed', 1, '2024-07-23 13:15:56', 'JANINE SUMALINOG'),
(51, 9, 'Your Order has been completed', 1, '2024-07-23 14:09:05', 'JANINE SUMALINOG'),
(52, 9, 'Your Order has been completed', 1, '2024-07-23 14:20:57', 'JANINE SUMALINOG'),
(53, 9, 'ertert', 1, '2024-07-23 14:33:08', ' JANINE SUMALINOG'),
(54, 9, '46t456', 1, '2024-07-24 03:31:14', ' new admin'),
(55, 9, 'Your Order has been completed', 1, '2024-07-24 15:07:53', 'JANINE SUMALINOG'),
(56, 9, 'rtyhtr', 1, '2024-07-24 16:13:31', ' JANINE SUMALINOG'),
(57, 9, 'ewrtert', 1, '2024-07-25 11:13:27', ' JANINE SUMALINOG'),
(58, 9, 'nice', 1, '2024-07-25 11:17:02', ' JANINE SUMALINOG'),
(59, 9, 'tyujty', 1, '2024-07-26 03:04:06', ' JANINE SUMALINOG'),
(60, 17, 'erygtre', 1, '2024-07-26 03:49:25', ' JANINE SUMALINOG'),
(61, 17, 'rthrh', 1, '2024-07-26 11:10:25', ' JANINE SUMALINOG'),
(62, 9, 'tyjyu', 1, '2024-07-26 11:11:13', ' JANINE SUMALINOG'),
(63, 9, 'rtyhr', 1, '2024-07-26 11:12:57', ' JANINE SUMALINOG'),
(64, 9, 'Your Order has been completed', 1, '2024-07-26 11:37:03', 'JANINE SUMALINOG'),
(65, 9, 'Your Order has been completed', 1, '2024-07-26 14:05:44', 'new admin'),
(66, 15, 'Your Order has been completed', 1, '2024-07-26 14:08:11', 'new admin'),
(67, 9, 'fuhyuyt', 1, '2024-07-27 11:32:43', ' new admin'),
(68, 15, 'retr', 1, '2024-07-27 11:39:12', ' new admin'),
(69, 9, 'hytty', 1, '2024-07-27 12:11:45', ' new admin'),
(70, 9, 'Your Order has been completed', 1, '2024-07-27 14:30:39', 'new admin'),
(71, 9, 'tuyy', 1, '2024-08-01 06:53:15', ' JANINE SUMALINOG'),
(72, 9, 'ytutut', 1, '2024-08-01 09:02:07', ' new admin'),
(73, 9, 'Your Order has been completed', 0, '2024-08-01 10:12:04', 'new admin'),
(74, 9, 'Your Order has been completed', 0, '2024-08-14 10:50:44', 'JANINE SUMALINOG'),
(75, 9, 'Your Order has been completed', 0, '2024-08-14 10:50:48', 'JANINE SUMALINOG'),
(76, 9, 'Your Order has been completed', 0, '2024-08-14 10:53:49', 'JANINE SUMALINOG'),
(77, 15, 'Your Order has been completed', 1, '2024-08-20 04:56:45', 'JANINE SUMALINOG'),
(78, 15, 'Your Order has been completed', 1, '2024-08-20 04:57:01', 'JANINE SUMALINOG'),
(79, 15, 'Your Order has been completed', 1, '2024-08-20 04:57:10', 'JANINE SUMALINOG'),
(80, 15, 'Your Order has been completed', 1, '2024-08-20 04:57:50', 'JANINE SUMALINOG'),
(81, 15, 'Your Order has been completed', 1, '2024-08-20 04:57:54', 'JANINE SUMALINOG'),
(82, 0, 'Your Order has been completed', 0, '2024-08-20 05:47:25', 'JANINE SUMALINOG');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `email` varchar(100) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(100) NOT NULL,
  `placed_on` varchar(50) NOT NULL,
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending',
  `top_sales` varchar(255) NOT NULL,
  `top_sales_percentage` decimal(5,2) NOT NULL,
  `admin_id` int(100) NOT NULL,
  `is_hidden` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`, `top_sales`, `top_sales_percentage`, `admin_id`, `is_hidden`) VALUES
(148, 15, 'ako', '08975986', 'Gilmarst99@gmail.com', 'cash on delivery', 'flat no. western cabul-an, western cabul-an, bohol, Philippines - 6333', 'redtged (4)', 1824, '20-Aug-2024', 'pending', 'redtged (100.00%)', 100.00, 14, 0);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `admin_id`, `title`, `content`, `image`, `description`, `status`, `created_at`) VALUES
(2, 10, 'sfsdf', 'sfsddsg', '15.jpg', '', 'approved', '2024-07-31 01:14:01'),
(5, 10, 'tyt', 'ytjt', 'Screenshot 2024-02-09 085547.png', '', 'approved', '2024-07-31 01:16:52'),
(6, 10, 'eafews', 'ergbrtf', '', '', 'approved', '2024-07-31 01:39:57'),
(7, 10, 'csj', 'bfgb', 'banner2.jpg', '', 'approved', '2024-08-24 15:02:46'),
(8, 10, 'efew', 'fr', '', '', 'approved', '2024-08-24 15:03:02'),
(9, 10, 'fd', 'dgdf', '', '', 'approved', '2024-08-24 15:06:27'),
(10, 10, 'efew', 'fr', '', '', 'approved', '2024-08-24 15:08:35'),
(11, 10, 'dgf', 'gfgf', 'banner2.jpg', '', 'approved', '2024-08-24 15:08:54'),
(12, 10, 'fr', 'dgd', '', '', 'approved', '2024-08-24 15:09:08'),
(13, 10, 'fr', 'dgd', '', '', 'approved', '2024-08-24 15:10:15');

-- --------------------------------------------------------

--
-- Table structure for table `post_approvals`
--

CREATE TABLE `post_approvals` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `approver_id` int(11) NOT NULL,
  `approval_status` varchar(50) NOT NULL DEFAULT 'pending',
  `comments` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL DEFAULT 'sea_foods',
  `descriptions` varchar(100) NOT NULL,
  `havest_status` varchar(100) NOT NULL DEFAULT 'ready_to_harvest',
  `admin_id` int(110) NOT NULL,
  `status` varchar(255) NOT NULL,
  `harvest_date` date NOT NULL,
  `blocked_by_admin_id` int(110) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `category`, `descriptions`, `havest_status`, `admin_id`, `status`, `harvest_date`, `blocked_by_admin_id`) VALUES
(15, 'redtged', 456, '15.jpg', 'sea_foods', 'rthhgbty', 'ready_to_harvest', 14, 'approved', '2024-08-31', 0),
(16, 'ewrew', 55, 'promo3.jpg', 'vegetables', 'dfgdgfdgfd', 'harvested', 14, '2024-08-31', '0000-00-00', 0),
(33, 'dfhfg', 645, 'promo1.jpg', 'sea_foods', 'gh', 'harvested', 10, 'approved', '2024-08-19', 0),
(37, 'fdghfdh', 6454, '10.jpg', 'vegetables', 'fghbfh', 'harvested', 10, 'approved', '2024-08-18', 0),
(38, 'ako', 546, 'promo2.jpg', 'sea_foods', 'ert', 'ready_to_harvest', 10, 'approved', '2024-08-31', 0),
(39, 'gdf', 23, 'Screenshot 2024-07-17 140133.jpg', 'sea_foods', 'dfvdf', 'ready_to_harvest', 10, 'approved', '2024-08-28', 0),
(40, 'rfbgb', 343, 'banner2.jpg', 'sea_foods', 'gbgb', 'harvested', 10, 'approved', '0000-00-00', 0),
(41, 'gdf', 34, '6.jpg', 'sea_foods', 'fgr', 'harvested', 10, 'processing', '0000-00-00', 0),
(42, 'Laptops', 55, 'CandUinvitation.jpg', 'sea_foods', 'dfvdf', 'harvested', 10, 'processing', '0000-00-00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `reporter_id` int(11) NOT NULL,
  `reported_user_id` int(11) NOT NULL,
  `report_type` enum('harassment','bullying','scam','other') NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','resolved','dismissed') NOT NULL DEFAULT 'pending',
  `blocked` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `block_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `reporter_id`, `reported_user_id`, `report_type`, `description`, `status`, `blocked`, `created_at`, `block_date`) VALUES
(1, 9, 10, 'harassment', 'fdgfdffh', 'pending', 0, '2024-07-31 12:53:24', NULL),
(5, 9, 10, '', 'User has been blocked.', 'pending', 1, '2024-07-31 13:46:05', NULL),
(6, 9, 10, '', 'User has been blocked.', 'pending', 1, '2024-07-31 13:46:17', NULL),
(9, 9, 14, 'harassment', 'wqdwqe', 'pending', 0, '2024-08-01 01:48:12', NULL),
(10, 9, 3, 'harassment', 'rtuyuytyt', 'pending', 0, '2024-08-01 01:49:21', NULL),
(11, 9, 3, 'harassment', 'rtuyuytyt', 'pending', 0, '2024-08-01 01:54:09', NULL),
(12, 9, 3, 'harassment', 'rtuyuytyt', 'pending', 0, '2024-08-01 01:55:32', NULL),
(13, 9, 3, 'harassment', 'rtuyuytyt', 'pending', 0, '2024-08-01 01:56:17', NULL),
(14, 9, 3, 'harassment', 'rtuyuytyt', 'pending', 0, '2024-08-01 01:56:50', NULL),
(15, 9, 3, 'harassment', 'rtuyuytyt', 'pending', 0, '2024-08-01 01:57:34', NULL),
(16, 9, 3, 'harassment', 'rtuyuytyt', 'pending', 0, '2024-08-01 01:58:16', NULL),
(17, 9, 3, 'harassment', 'rtuyuytyt', 'pending', 0, '2024-08-01 01:58:57', NULL),
(18, 9, 3, 'harassment', 'qwdwqe', 'pending', 0, '2024-08-01 02:29:15', NULL),
(19, 9, 3, 'harassment', 'hahhaaha', 'pending', 0, '2024-08-01 02:29:31', NULL),
(20, 9, 14, 'harassment', 'fsdf', 'pending', 0, '2024-08-01 02:34:07', NULL),
(24, 9, 16, '', ' has been blocked ', 'pending', 1, '2024-08-01 02:59:57', NULL),
(25, 9, 16, '', ' has been blocked ', 'pending', 1, '2024-08-01 03:00:07', NULL),
(26, 15, 14, 'harassment', '4545y56y65y65y', 'pending', 0, '2024-08-01 09:05:23', NULL),
(29, 15, 10, '', ' has been blocked ', 'pending', 1, '2024-08-13 12:53:18', NULL),
(30, 15, 10, '', ' has been blocked ', 'pending', 1, '2024-08-13 12:53:48', NULL),
(31, 15, 14, '', 'has been blocked', 'pending', 1, '2024-08-13 12:55:16', NULL),
(32, 15, 16, '', 'has been blocked', 'pending', 1, '2024-08-13 12:57:46', NULL),
(33, 15, 3, 'bullying', 'vvh', 'pending', 0, '2024-08-23 13:34:00', NULL),
(34, 15, 3, 'bullying', 'vvh', 'pending', 0, '2024-08-23 13:36:02', NULL),
(35, 15, 3, '', 'has been blocked', 'pending', 0, '2024-08-23 13:38:54', NULL),
(36, 15, 3, '', 'has been blocked', 'pending', 0, '2024-08-23 13:40:39', NULL),
(37, 15, 3, '', 'has been blocked', 'pending', 1, '2024-08-23 13:51:41', '2024-08-23 15:51:41'),
(38, 17, 10, '', 'has been blocked', 'pending', 1, '2024-08-23 22:38:10', '2024-08-24 00:38:10'),
(39, 6, 10, '', 'has been blocked', 'pending', 1, '2024-08-25 02:30:13', '2024-08-25 04:30:13');

-- --------------------------------------------------------

--
-- Table structure for table `search_history`
--

CREATE TABLE `search_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `search_term` varchar(255) NOT NULL,
  `search_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `search_history`
--

INSERT INTO `search_history` (`id`, `user_id`, `search_term`, `search_time`) VALUES
(32, 17, 'admin', '2024-08-24 14:06:12'),
(33, 17, 'admin', '2024-08-24 14:07:42'),
(34, 17, 'admin', '2024-08-24 14:09:15'),
(35, 17, 'Laptops', '2024-08-24 14:10:27'),
(36, 17, 'JANINE SUMALINOG', '2024-08-24 14:10:40'),
(37, 17, 'a', '2024-08-24 14:10:46'),
(38, 17, 'a', '2024-08-24 14:11:36'),
(39, 17, 'JANINE SUMALINOG', '2024-08-24 14:12:43'),
(40, 17, 'ako', '2024-08-25 09:09:09'),
(41, 17, 'ako', '2024-08-25 09:09:35'),
(42, 17, 'ako', '2024-08-25 09:10:11'),
(43, 17, 'JANINE SUMALINOG', '2024-08-25 09:19:22'),
(44, 17, 'JANINE SUMALINOG', '2024-08-25 14:52:54'),
(45, 6, 'Laptops', '2024-08-27 06:30:03'),
(46, 6, 'ako', '2024-08-27 06:30:13');

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
  `block` enum('no','yes') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`, `username`, `image`, `status`, `created_at`, `contact`, `address`, `description`, `blocked`, `block`) VALUES
(3, 'admin', 'admin@business.com', '698d51a19d8a121ce581499d7b701668', 'admin', '', 'promo3.jpg', 'activate', '2024-08-25 03:26:55', '', '', '', 'activate', 'no'),
(5, 'ako', 'aaaa@gmail.com', '4124bc0a9335c27f086f24ba207a4912', 'user', '', '', 'deactivate', '2024-07-15 14:00:48', '', '', '', 'activate', 'no'),
(6, 'gilmar', 'Gilmarst99@gmail.com', '698d51a19d8a121ce581499d7b701668', 'user', '', '', 'activate', '2024-08-27 06:27:50', '', '', '', 'activate', 'no'),
(7, 'ako', 'aaaa@gmail.com', '6512bd43d9caa6e02c990b0a82652dca', 'user', '', '', 'deactivate', '2024-07-31 07:54:18', '', '', '', 'blocked', 'no'),
(8, 'Gilma', 'JayAldinsss@gmail.com', '698d51a19d8a121ce581499d7b701668', 'user', 'fdgbd', 'Screenshot 2024-07-14 134159.jpg', 'deactivate', '2024-08-13 12:41:43', '', '', '', 'activate', 'yes'),
(9, 'akosdsafdsf', 'tiktoksexiest99@gmail.com', '698d51a19d8a121ce581499d7b701668', 'user', 'sadad', '15.jpg', 'activate', '2024-08-01 09:11:17', '', '', '', 'blocked', 'no'),
(10, 'JANINE SUMALINOG', 'aaaa@gmail.com', '698d51a19d8a121ce581499d7b701668', 'admin', 'retete', '_15ee6cf6-0dab-4c69-b5d4-4b6b7038dace.jpg', 'activate', '2024-08-22 05:04:39', '09463478938', 'brgy.sweetland buenavista bohol', 'rtuyuhtuj', 'activate', 'no'),
(11, 'adad', 'aa@a.com', '6512bd43d9caa6e02c990b0a82652dca', 'user', 'Gilmarst', 'default-avatar.png', 'deactivate', '2024-07-25 14:00:48', '', '', '', 'activate', 'no'),
(12, 'admin', 'JayAldin@gmail.com', '6512bd43d9caa6e02c990b0a82652dca', 'user', 'aa', '', 'deactivate', '2024-08-01 08:59:11', '', '', '', 'activate', 'yes'),
(13, 'Jay Vincent', 'Jeeg99@gmail.com', '698d51a19d8a121ce581499d7b701668', 'super_admin', 'wef', '_15ee6cf6-0dab-4c69-b5d4-4b6b7038dace.jpg', 'activate', '2024-08-01 01:17:10', '', '', '', 'activate', 'no'),
(14, 'new admin', 'new@new.com', '698d51a19d8a121ce581499d7b701668', 'admin', 'new ', '_15ee6cf6-0dab-4c69-b5d4-4b6b7038dace.jpg', 'activate', '2024-08-01 10:17:55', '867', 'fdghfghfghjfgj', 'fgjghjghj', 'blocked', 'no'),
(15, 'ako', 'Gilmarst99@gmail.com', '698d51a19d8a121ce581499d7b701668', 'user', 'ako', '57-balicasag-island-main-banner.jpg', 'activate', '2024-08-23 14:40:49', '', '', '', 'blocked', 'no'),
(16, 'ikaw', 'Hardware@gmail.com', '698d51a19d8a121ce581499d7b701668', 'admin', 'www', '', 'activate', '2024-08-13 15:13:38', '', '', '', 'activate', 'no'),
(17, 'mary', 'mary@gmail.com', '698d51a19d8a121ce581499d7b701668', 'user', 'mary', '', 'activate', '2024-08-23 23:58:57', '', '', '', 'activate', 'no'),
(18, 'gdf', 'jayner@gmail.com', '698d51a19d8a121ce581499d7b701668', 'users', 'aaaa@gmail.com', '', 'deactivate', '2024-08-13 12:22:44', '', '', '', 'activate', 'no'),
(19, 'gdf', 'joshuabonghanoy@gmail.com', '698d51a19d8a121ce581499d7b701668', 'super_admin', 'aassaa@gmail.com', '', 'deactivate', '2024-08-13 12:23:31', '', '', '', 'activate', 'no'),
(20, 'dfvdfv', 'joshuabssonghanoy@gmail.com', '698d51a19d8a121ce581499d7b701668', 'farmers2', 'aassaa@gmail.com', '', 'deactivate', '2024-08-13 12:24:43', '', '', '', 'activate', 'no');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `breakdowns`
--
ALTER TABLE `breakdowns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `investments`
--
ALTER TABLE `investments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `post_approvals`
--
ALTER TABLE `post_approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `approver_id` (`approver_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reporter_id` (`reporter_id`),
  ADD KEY `reported_user_id` (`reported_user_id`);

--
-- Indexes for table `search_history`
--
ALTER TABLE `search_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `breakdowns`
--
ALTER TABLE `breakdowns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=376;

--
-- AUTO_INCREMENT for table `investments`
--
ALTER TABLE `investments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=222;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `post_approvals`
--
ALTER TABLE `post_approvals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `search_history`
--
ALTER TABLE `search_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `breakdowns`
--
ALTER TABLE `breakdowns`
  ADD CONSTRAINT `breakdowns_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `investments`
--
ALTER TABLE `investments`
  ADD CONSTRAINT `investments_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_approvals`
--
ALTER TABLE `post_approvals`
  ADD CONSTRAINT `post_approvals_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_approvals_ibfk_2` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`reported_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
