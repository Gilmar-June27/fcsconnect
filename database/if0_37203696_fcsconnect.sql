-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql105.infinityfree.com
-- Generation Time: Jun 06, 2025 at 12:07 AM
-- Server version: 10.6.21-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_37203696_fcsconnect`
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

-- --------------------------------------------------------

--
-- Table structure for table `blocked_users`
--

CREATE TABLE `blocked_users` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `blocked_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `admin_id` int(11) NOT NULL,
  `sizes` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `name`, `price`, `quantity`, `image`, `admin_id`, `sizes`) VALUES
(577, 59, 'Corn', 22, 5, 'download (3) - Copy.jfif', 58, 'kg'),
(579, 60, 'Corn', 22, 1, 'download (3) - Copy.jfif', 58, 'kg'),
(582, 79, 'Banana karnaba', 25, 1, 'Saba_Bananas.jpg', 73, 'kg'),
(583, 80, 'Banana karnaba', 25, 1, 'Saba_Bananas.jpg', 73, 'kg'),
(585, 49, 'Fish', 180, 1, 'IMG20200512142213_2048x.jpg', 74, 'kg'),
(592, 81, 'Kamote', 30, 3, 'inbound4599011737851575547.jpg', 54, 'kg');

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
  `is_read` tinyint(1) NOT NULL,
  `qr_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `user_id`, `name`, `email`, `number`, `message`, `created_at`, `admin_id`, `image`, `is_read`, `qr_code`) VALUES
(290, 49, 'Jeeg TuÃ±acao ', 'jeeg99@gmail.com', '09463478938', 'Teh pagadd ug product', '2024-11-12 10:36:02', 48, '', 1, ''),
(291, 49, 'Jeeg TuÃ±acao ', 'jeeg99@gmail.com', '09463478938', 'pre', '2024-11-14 10:13:04', 58, '', 1, ''),
(292, 49, 'Jeeg TuÃ±acao ', 'jeeg99@gmail.com', '09463478938', 'Helo teh what up', '2025-01-21 03:34:57', 48, '', 1, ''),
(293, 82, 'grexie', 'cabarrubiasgrexie@gmail.com', '09519317955', 'hiii\r\n', '2025-02-04 08:23:42', 48, '', 1, ''),
(294, 81, 'Gilmar', 'aparecegilmar1@gmail.com', '09463478938', 'yo whats up', '2025-02-14 07:02:57', 75, '', 1, ''),
(295, 81, 'Gilmar', 'aparecegilmar1@gmail.com', '09463478938', '', '2025-02-14 07:03:34', 75, 'images/Untitled-2.png', 1, ''),
(296, 81, 'Gilmar', 'aparecegilmar1@gmail.com', '09463478938', 'new ai version', '2025-02-14 07:04:40', 75, 'images/af84f73e228e2a6d7b776660a98d2e3a.jpg', 1, ''),
(297, 81, 'Gilmar', 'aparecegilmar1@gmail.com', '09463478938', 'ghf', '2025-02-15 07:41:37', 48, '', 1, ''),
(298, 81, 'Gilmar', 'aparecegilmar1@gmail.com', '09463478938', 'cvbvbbv', '2025-02-26 06:08:18', 48, '', 1, ''),
(299, 81, 'Gilmar', 'aparecegilmar1@gmail.com', '09463478938', '', '2025-02-26 06:08:29', 48, 'images/WIN_20241125_06_05_07_Pro.jpg', 1, ''),
(300, 81, 'Gilmar', 'aparecegilmar1@gmail.com', '09463478938', 'hello', '2025-06-03 07:03:30', 50, '', 1, ''),
(301, 81, 'Gilmar', 'aparecegilmar1@gmail.com', '09463478938', '', '2025-06-03 07:03:39', 50, 'images/Screenshot_2025-03-23-09-24-07-65_ccc4ff946bf847a7c199bff6d87da37a.jpg', 1, '');

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
  `is_hidden` tinyint(1) NOT NULL,
  `image` text NOT NULL,
  `order_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `shipping_fee` varchar(255) NOT NULL,
  `qr_code_value` decimal(10,2) NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`, `top_sales`, `top_sales_percentage`, `admin_id`, `is_hidden`, `image`, `order_at`, `shipping_fee`, `qr_code_value`, `paid_amount`) VALUES
(297, 52, 'Vincent Bautista Sumalinog ', '09462165899', 'vince22sumalinog@gmail.com', 'cod', 'flat no. Purok 2 , langga Bugaong , Buenavista , Bohol, Philippines  - 6333', 'Petchay (1)', 30, '12-Nov-2024', 'pending', 'Petchay (5.00%)', '5.00', 50, 0, '', '2024-11-12 12:19:13', '0', '0.00', '0.00'),
(298, 55, 'Cleofa B. Sumalinog', '09702898797', 'cleofa@gmail.com', 'cod', 'flat no. Purok 2, Mangga, Buga-ong, Buenavista, Bohol, Philippines - 6333', 'Kamote (2)', 60, '12-Nov-2024', 'pending', 'Kamote (10.00%)', '10.00', 54, 0, '', '2024-11-12 22:34:03', '0', '0.00', '0.00'),
(299, 56, 'Shyne Alexis Baricuatro', '09854131557', 'shynealexisbaricuatro@gmail.com', 'cod', 'flat no. Purok 2 manga, Buenavista, Bohol, Philippines - 6333', 'Petchay (1)', 30, '12-Nov-2024', 'pending', 'Petchay (5.00%)', '5.00', 50, 0, '', '2024-11-12 22:41:07', '0', '0.00', '0.00'),
(300, 49, 'Jeeg TuÃ±acao ', '09463478938', 'jeeg99@gmail.com', 'cod', 'flat no. Purok 2 Western Cabul an, Purok 7 sweetland, Tagbilaran city, Philippines - 6333', 'Upo (1)', 40, '14-Nov-2024', 'pending', 'Upo (5.00%)', '5.00', 57, 0, '', '2024-11-14 10:11:40', '0', '0.00', '0.00'),
(301, 78, 'Allan', '09858344189', 'allanabayan@gmail.com', 'cod', 'flat no. 5 setio mansanas dait norte., Buenavista, Tagbilaran, Philippines  - 6333', 'Camote (1)', 25, '02-Dec-2024', 'pending', 'Camote (5.00%)', '5.00', 71, 0, '', '2024-12-02 06:21:16', '0', '0.00', '0.00'),
(302, 49, 'Jeeg TuÃ±acao ', '09463478938', 'jeeg99@gmail.com', 'cod', 'flat no. Purok 2 Western Cabul an, Purok 7 sweetland, Tagbilaran city, Philippines - 6333', 'Upo (1)', 40, '20-Jan-2025', 'pending', 'Upo (5.00%)', '5.00', 57, 0, '', '2025-01-21 03:29:44', '0', '0.00', '0.00'),
(303, 49, 'Jeeg TuÃ±acao ', '09463478938', 'jeeg99@gmail.com', 'cod', 'flat no. Purok 2 Western Cabul an, Purok 7 sweetland, Tagbilaran city, Philippines - 6333', 'Fish (5)', 900, '20-Jan-2025', 'pending', 'Fish (25.00%)', '25.00', 74, 0, '', '2025-01-21 03:31:55', '0', '0.00', '0.00'),
(306, 83, '22', '09463478938', 'aparecegilmar1@gmail.com', 'cod', 'flat no. sweetland buenavista bohol, ICT Building, CTU-Main,, Cebu City, Philippines - 6000', 'Upo (1)', 40, '31-May-2025', 'pending', 'Upo (5.00%)', '5.00', 57, 0, '', '2025-06-01 02:30:06', '0', '0.00', '0.00'),
(307, 81, 'Gilmar', '09463478938', 'aparecegilmar1@gmail.com', 'cod', 'flat no. Cangawa,Buenavista,Bohol, Buenavista, Tagbilaran , Philippines - 6333', 'Petchay (1)', 30, '03-Jun-2025', 'pending', 'Kamote (15.00%)', '15.00', 50, 0, '', '2025-06-03 07:04:32', '0', '0.00', '0.00'),
(308, 81, 'Gilmar', '09463478938', 'aparecegilmar1@gmail.com', 'cod', 'flat no. Cangawa,Buenavista,Bohol, Buenavista, Tagbilaran , Philippines - 6333', 'Banana (1)', 25, '03-Jun-2025', 'pending', 'Kamote (15.00%)', '15.00', 69, 0, '', '2025-06-03 07:04:32', '0', '0.00', '0.00'),
(309, 81, 'Gilmar', '09463478938', 'aparecegilmar1@gmail.com', 'cod', 'flat no. Cangawa,Buenavista,Bohol, Buenavista, Tagbilaran , Philippines - 6333', 'Kamote (3)', 90, '03-Jun-2025', 'pending', 'Kamote (15.00%)', '15.00', 54, 0, '', '2025-06-03 07:04:32', '0', '0.00', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `order_comments`
--

CREATE TABLE `order_comments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `blocked_by_admin_id` int(110) NOT NULL,
  `available_kilo` int(110) NOT NULL,
  `images` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `sizes` varchar(255) NOT NULL,
  `rejection_message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `category`, `descriptions`, `havest_status`, `admin_id`, `status`, `harvest_date`, `blocked_by_admin_id`, `available_kilo`, `images`, `created_at`, `sizes`, `rejection_message`) VALUES
(85, 'Petchay', 30, '', 'vegetables', 'Fresh harvest', 'harvested', 50, 'approved', '0000-00-00', 0, 7, 'BOKCHOY-1.jpg,pechaynative_300x300.png,bok-choy-1.jpg', '2024-11-12 12:12:28', 'kg', ''),
(86, 'Kamote', 30, '', 'vegetables', 'Good quality', 'harvested', 54, 'approved', '0000-00-00', 0, 5, 'inbound4599011737851575547.jpg,inbound2357663447089293528.jpg,inbound3395461622842816641.jpg', '2024-11-12 22:26:50', 'kg', ''),
(87, 'Upo', 40, '', 'vegetables', 'Fresh', 'ready_to_harvest', 57, 'approved', '2024-11-29', 0, 27, 'inbound4543758172443111400.jpg,inbound6251759860051636312.jpg', '2024-11-13 00:07:07', 'kg', ''),
(88, 'Corn', 22, '', 'crops', 'high quality', 'harvested', 58, 'approved', '0000-00-00', 0, 50, 'download (3) - Copy.jfif,download (3).jfif,download (4) - Copy.jfif', '2024-11-13 02:47:23', 'kg', ''),
(89, 'Banana', 25, '', 'fruits', 'Dagku', 'ready_to_harvest', 69, 'approved', '2024-12-10', 0, 26, 'inbound4365645893759268198.jpg,inbound1197791655132673479.jpg,inbound7721984586734385508.jpg', '2024-12-01 07:25:21', 'kg', ''),
(90, 'Camote', 25, '', 'crops', 'Yellow', 'harvested', 71, 'approved', '0000-00-00', 0, 29, 'inbound7759331444561493116.jpg,inbound2036507344037574052.jpg', '2024-12-01 07:44:08', 'kg', ''),
(91, 'Humay', 22, '', 'crops', 'Puti', 'harvested', 72, 'approved', '0000-00-00', 0, 100, 'IMG_20241201_160420.jpg,IMG_20241201_160341.jpg', '2024-12-01 08:05:01', 'kg', ''),
(92, 'Banana karnaba', 25, '', 'crops', 'Guwang', 'ready_to_harvest', 73, 'approved', '2024-12-08', 0, 40, 'Saba_Bananas.jpg,Saba_banana_tree.jpg', '2024-12-01 08:41:06', 'kg', ''),
(93, 'Fish', 180, '', 'sea_foods', 'Lab as', 'ready_to_pick_up', 74, 'approved', '0000-00-00', 0, 1, 'IMG20200512142213_2048x.jpg,alumahan.jpg,fresh-fish-on-display-within-the-public-market-of-general-santos-cityphilippines-WP43Y8.jpg', '2024-12-01 09:03:39', 'kg', ''),
(94, 'Rice', 55, '', 'crops', 'White', 'ready_to_pick_up', 75, 'approved', '0000-00-00', 0, 60, 'IMG_20241201_160420.jpg', '2024-12-01 09:34:01', 'kg', ''),
(95, 'Batong', 25, '', 'vegetables', 'Tag-as', 'harvested', 76, 'approved', '0000-00-00', 0, 30, 'sitaw-clipart-2_grande.jpg,batong.jpg', '2024-12-01 23:43:53', 'kg', ''),
(96, 'Kalabasa', 30, '', 'vegetables', 'Labo', 'ready_to_harvest', 77, 'approved', '2024-12-22', 0, 20, 'winter-squash-kabocha-common-pumpkin-260nw-2167361527.jpg,pumpkin-field.jpg,squash_round-zucchini_2.jpg', '2024-12-02 00:07:15', 'kg', '');

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `user_id`, `order_id`, `product_id`, `rating`, `comment`, `created_at`, `admin_id`) VALUES
(22, 34, 293, 0, 5, '', '2024-11-12 06:14:26', 0);

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
(48, 49, 48, '', 'Hdhd', 'pending', 0, '2024-12-05 22:35:14', NULL),
(49, 49, 48, '', 'has been blocked', 'pending', 0, '2024-12-05 22:35:20', '2024-12-05 17:35:20');

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
(167, 81, 'Petchay', '2025-02-14 07:05:24'),
(168, 81, 'Petchay', '2025-02-14 07:05:43'),
(169, 81, '', '2025-02-14 07:13:26');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_fee`
--

CREATE TABLE `shipping_fee` (
  `id` int(11) NOT NULL,
  `price` varchar(255) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `from_location` varchar(255) NOT NULL,
  `to_location` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipping_fee`
--

INSERT INTO `shipping_fee` (`id`, `price`, `admin_id`, `from_location`, `to_location`) VALUES
(4, '255', 35, 'cebu', 'bohol'),
(5, '150', 35, 'maribojoc', 'buenavista');

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
  `number` varchar(255) NOT NULL,
  `farmers_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`, `username`, `image`, `status`, `created_at`, `contact`, `address`, `description`, `blocked`, `block`, `farmer_select`, `qr_code`, `number`, `farmers_id`) VALUES
(1, 'Admin', 'admin@gmail.com', '698d51a19d8a121ce581499d7b701668', 'super_admin', 'Admin', 'default-avatar.png', 'activate', '2025-03-31 05:26:47', '', '', '', '', '', '', '', '', ''),
(47, 'Saturnino Sumalinog JR', 'satur@gmail.com', '5d3b6f861962f65941a590b076bd739a', 'user', 'Saturnino', '', 'activate', '2024-11-12 09:40:43', '', 'flat no. Purok 2, Mangga, Buga-ong, Buenavista, Bohol, Philippines  - 6333', '', 'activate', 'no', '', '', '09123456789', ''),
(48, 'Maricris ', 'maricrisbetarmos712@gmail.com', 'd141492474539f6a57840c2e31e475f5', 'admin', 'Mariz', '', 'activate', '2024-11-12 09:46:16', '', 'flat no. Liloan Norte inabanga bohol , Inabanga , Tagbilaran , Phillipines  - 6332', '', 'activate', 'no', '', '', '09946449678 ', '1000017087.jpg'),
(49, 'Jeeg TuÃ±acao ', 'jeeg99@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'user', 'Jeeg', '', 'activate', '2024-11-27 06:29:09', '', 'flat no. Purok 2 Western Cabul an, Purok 7 sweetland, Tagbilaran city, Philippines - 6333', '', 'activate', 'no', '', '', '09463478938', ''),
(50, 'Floriana B. Sumalinog', 'floriana@gmail.com', '598f3817445f6cfdd91586562514ac18', 'admin', 'Floriana', '', 'activate', '2024-11-12 11:58:45', '', 'flat no. Purok 2,Mangga,Bugaong, Buenavista , Bohol, Philippines  - 6333', '', 'activate', 'no', 'farmer1', '', 'O9380929474', 'inbound4037758826123568525.jpg'),
(51, 'Mary', 'mary@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'admin', 'Mary', '', 'activate', '2024-12-10 09:00:41', '', 'flat no. Purok 2 Western Cabul an, Purok 7 sweetland, Tagbilaran city, Philippines - 6333', '', 'activate', 'no', '', '', '09463478938', 'Screenshot_2024-11-11-07-15-14-57_f9ee0578fe1cc94de7482bd41accb329.jpg'),
(52, 'Vincent Bautista Sumalinog ', 'vince22sumalinog@gmail.com', '49518761a474577e7e579ba2f0659e5f', 'user', 'Vince Clark ', '', 'activate', '2024-11-12 12:16:49', '', 'flat no. Purok 2 , langga Bugaong , Buenavista , Bohol, Philippines  - 6333', '', 'activate', 'no', '', '', '09462165899', ''),
(53, 'Catalino A. Ontong', 'catalino@gmail.com', '0a8f973647dec57e099ac1e07bc81506', 'admin', 'Catalino', '', 'activate', '2024-11-12 13:05:26', '', 'flat no. Purok 7, Lumboy, Buga-ong, Buenavista, Bohol, Philippines - 6333', '', 'activate', 'no', '', '', '09079424592', 'inbound9010850611111711974.jpg'),
(54, 'Calixto E. Sumalinog', 'calixto@gmail.com', 'bc4ccbc8872a84a13e38560bd59acf2d', 'admin', 'Calixto', '', 'activate', '2024-11-12 22:23:31', '', 'flat no. Purok 2, Mangga, Buga-ong, Buenavista, Bohol, Philippines - 6333', '', 'activate', 'no', '', '', '09702898797', ''),
(55, 'Cleofa B. Sumalinog', 'cleofa@gmail.com', '63530b5a7637e063bdb557fa95005f2d', 'user', 'Cleofa', '', 'activate', '2024-11-12 22:31:42', '', 'flat no. Purok 2, Mangga, Buga-ong, Buenavista, Bohol, Philippines - 6333', '', 'activate', 'no', '', '', '09702898797', ''),
(56, 'Shyne Alexis Baricuatro', 'shynealexisbaricuatro@gmail.com', 'bbef62da558428f5508206cec4c9331d', 'user', 'Shynealexis', '', 'activate', '2024-11-12 22:38:59', '', 'flat no. Purok 2 manga, Buenavista, Bohol, Philippines - 6333', '', 'activate', 'no', '', '', '09854131557', ''),
(57, 'Gil Baticuatro', 'gil@gmail.com', 'f2276f6a76fe852cf8359d1f618dfaa2', 'admin', 'Gil', '', 'activate', '2024-11-13 00:05:32', '', 'flat no. Purok 1, Mangga, Buga-ong, Buenavista, Bohol, Philippines - 6333', '', 'activate', 'no', '', '', '09079424592', 'inbound2047653199328096557.jpg'),
(58, 'Jay Vincent Petalcorin', 'jayvincent@gmail.com', '25d55ad283aa400af464c76d713c07ad', 'admin', 'Jay', '', 'deactivate', '2024-11-13 02:47:38', '', 'flat no. purok 7, Lumboy, Bugaong, Buenavista, Bohol, Philippines - 6333', '', 'activate', 'no', '', '', '09308049134', 'images.jfif'),
(59, 'Jolery Sevilla', 'jolery@gmail.com', '25d55ad283aa400af464c76d713c07ad', 'user', 'jolery', '', 'activate', '2024-11-13 02:48:27', '', 'flat no. Purok 7, Baas, Lincod, Maribojoc, Bohol, Philippines - 6336', '', 'activate', 'no', '', '', '09123456789', ''),
(60, 'Catalino Ontong', 'janine2@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'user', 'Janine', '', 'activate', '2024-11-26 12:54:34', '', 'flat no. Purok 2, Manga, Buga-ong, Buenavista, Bohol, Philippine - 6333', '', 'activate', 'no', '', '', '09079424592', ''),
(61, 'Jolerys Sevilla', 'jayvincent3@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'user', 'jolerys', '', 'deactivate', '2024-11-27 12:10:03', '', 'flat no. purok 7, Lumboy, Bugaong, Buenavista, Bohol, Philippine - 6333', '', 'activate', 'no', '', '', '09079424592', ''),
(62, 'kapayas', 'janine3232@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'user', 'kapaa', '', 'deactivate', '2024-11-27 12:11:58', '', 'flat no. Purok 7, Baas, Lincod, Maribojoc, Bohol, sdfs - 6333', '', 'activate', 'no', '', '', '09079424592', ''),
(63, 'kapayas', 'janine299@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'user', 'catalinoss', '', 'deactivate', '2024-11-27 12:15:11', '', 'flat no. Purok 2, Manga, Buga-ong, Buenavista, Bohol, sdfs - 6333', '', 'activate', 'no', '', '', '09079424592', ''),
(64, 'Catalino Ontongsssss', 'jayvincent@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'user', 'catalinosssss', '', 'deactivate', '2024-11-27 12:21:04', '', 'flat no. purok 7, Lumboy, Buenavistas, Bohols, sdfs - 6333', '', 'activate', 'no', '', '', '09079424592', ''),
(65, 'Jolery Sevillakjdkhjwhs', 'janinwqdwqe@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'user', 'jolerysaq', '', 'deactivate', '2024-11-27 12:34:57', '', 'flat no. Purok 7, Baas, Lincod, Buenavista, Bohol, Philippines - 6336', '', 'activate', 'no', '', '', '09079424592', ''),
(66, 'Catalino Ontongwew', 'janine21@gmail.com', '202cb962ac59075b964b07152d234b70', 'user', 'aqswq', '', 'deactivate', '2024-11-27 12:38:10', '', 'flat no. purok 7, Lumboy, Bugaong, Maribojoc, Bohol, Philippines - 6333', '', 'activate', 'no', '', '', '09079424592', ''),
(67, 'Catalino Ontongvvvvvv', 'janine0000@gmail.com', '202cb962ac59075b964b07152d234b70', 'user', '0000', '', 'deactivate', '2024-11-27 12:43:36', '', 'flat no. purok 7, Lumboys, Maribojoc, Bohols, Philippines - 6333', '', 'activate', 'no', '', '', '09123456780', ''),
(68, 'Cleofa Sumalinog', 'calixtosumalinog@gmail.com', '0856ad1f3cb577b07d9516412e89d4f1', 'admin', 'Calixto', '', 'deactivate', '2024-12-01 07:08:14', '', 'flat no. Purok 2, Mangga, Bugaong , Buenavista, Tagbilaran, Philippines  - 6333', '', 'activate', 'no', '', '', '09702898797', 'inbound8978778339094632538.jpg'),
(69, 'Mateo Anora', 'mateoanora@gmail.com', '44fa3c480415419a987dc30776972309', 'admin', 'Mateo', '', 'deactivate', '2024-12-01 07:30:18', '', 'flat no. Purok 2, Mangga, Bugaong , Buenavista , Tagbilaran , Philippines  - 6333', '', 'activate', 'no', '', '', '09079424592', 'inbound4884561086601403863.jpg'),
(70, 'Albina Martinez ', 'martina@gmail.com', '1d7487975dd37174cdd6efa784ca320c', 'admin', 'Albina', '', 'deactivate', '2024-12-01 07:33:58', '', 'flat no. Purok 3, Santol, Buga-ong , Buenavista , Tagbilaran , Philippines  - 6333', '', 'activate', 'no', '', '', '09637584800', 'inbound8566134552417703521.jpg'),
(71, 'Albina Martinez ', 'albinamartinez@gmail.com', '1d7487975dd37174cdd6efa784ca320c', 'admin', 'Albina', '', 'activate', '2024-12-01 07:37:28', '', 'flat no. Purok 3,Santol, Buga-ong , Buenavista , Tagbilaran , Philippines  - 6333', '', 'activate', 'no', '', '', '09637584877', 'inbound7113076436146026749.jpg'),
(72, 'Cesaria Anora', 'cesaria@gmail.com', '6c2003583756aa29431b92fe17b138be', 'admin', 'Cesaria ', '', 'deactivate', '2024-12-01 08:08:08', '', 'flat no. Purok 3, Santol, Buga-ong , Buenavista , Tagbilaran, Philippines  - 6333', '', 'activate', 'no', '', '', '09079424592', 'IMG_20241201_155733.jpg'),
(73, 'Dennis Garcelazo', 'dennis@gmail.com', 'd038c3f09429bc375a00a57ca6f7db86', 'admin', 'Dennis', '', 'deactivate', '2024-12-01 08:57:10', '', 'flat no. Purok 3, Santol, Buga-ong , Buenavista , Buenavista , Philippines  - 6333', '', 'activate', 'no', '', '', '09461865702', 'IMG_20241201_163358.jpg'),
(74, 'Gil Baricuatro ', 'gilbaricuatro@gmail.com', 'c232e1db5ed9aae6839a5147c8be2e26', 'admin', 'Baricuatro ', '', 'deactivate', '2024-12-01 09:10:55', '', 'flat no. Purok 2, Mangga, Buga-ong , Buenavista , Buenavista , Philippines  - 6333', '', 'activate', 'no', '', '', '09461865702', 'IMG_20241201_170011.jpg'),
(75, 'Edgar baricuatro', 'edgarbaricuatro@gmail.com', '6b1d24ff83a319070db95c6c84b9be31', 'admin', 'Edgar', '', 'deactivate', '2024-12-01 23:39:59', '', 'flat no. Purok 2, Mangga, Buga-ong , Buenavista , Tagbilaran, Philippines  - 6333', '', 'activate', 'no', '', '', '09851189270', 'IMG_20241201_172743.jpg'),
(76, 'Catalino Ontong', 'catalino@gmail.com', 'e99caba9b05772dd8116909a388bae62', 'admin', 'Catalino', '', 'deactivate', '2024-12-01 23:45:13', '', 'flat no. Purok 7, Lumboy, Buga-ong , Buenavista , Buenavista , Philippines  - 6333', '', 'activate', 'no', '', '', '09079424592', 'Messenger_creation_C220C80E-DFC9-4E1C-B1C8-6C8740588137.jpeg'),
(77, 'Marciano Sumalinog ', 'marciano@gmail.com', '9a18e7fb8527a3a3075df2758885b28e', 'admin', 'Marciano', '', 'deactivate', '2024-12-02 00:07:50', '', 'flat no. Purok 1, Mangga, Buga-ong , Buenavista , Buenavista , Philippines  - 6333', '', 'activate', 'no', '', '', '09325236481', 'IMG_20241202_075151.jpg'),
(78, 'Allan', 'allanabayan@gmail.com', 'f5d8d3a61ebb0edbc8adbda0f4aa055c', 'user', 'Allan Abayan', '', 'deactivate', '2024-12-02 06:23:52', '', 'flat no. 5 setio mansanas dait norte., Buenavista, Tagbilaran, Philippines  - 6333', '', 'activate', 'no', '', '', '09858344189', ''),
(79, 'Hermenigilda Cempron', 'hermenigilda@gmail.com', 'c58edd344de9275db7b8e64ad3e10de7', 'user', 'Hene', '', 'deactivate', '2024-12-02 07:02:19', '', 'flat no. Embornal, Dait Norte, Buenavista , Buenavista , Philippines  - 6333', '', 'activate', 'no', '', '', '09851189270', ''),
(80, 'Jonnavi Logrono', 'jonnavi@gmail.com', 'ea2cb077846492795e91c64136661c52', 'user', 'Jona', '', 'deactivate', '2024-12-02 07:14:04', '', 'flat no. Purok 2, Cangawa , Buenavista , Buenavista , Philippines  - 6333', '', 'activate', 'no', '', '', '09461865702', ''),
(81, 'Gilmar', 'aparecegilmar1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'user', 'Gilmar', 'download (3).jpg', 'activate', '2025-03-21 12:34:26', '', 'flat no. Cangawa,Buenavista,Bohol, Buenavista, Tagbilaran , Philippines - 6333', '', 'activate', 'no', 'farmer1', '', '09463478938', ''),
(82, 'grexie', 'cabarrubiasgrexie@gmail.com', '49d27cf3af0b907c976a8f0a38e48859', 'user', 'GREXIE', '', 'activate', '2025-02-04 08:21:27', '', 'flat no. poblacion ward 3 , Minglanilla, cebu, philippines - 260119', '', 'activate', 'no', '', '', '09519317955', ''),
(83, '22', 'aparecegilmar1@gmail.com', '698d51a19d8a121ce581499d7b701668', 'user', 'aparece', '', 'deactivate', '2025-06-01 14:24:51', '', 'flat no. sweetland buenavista bohol, ICT Building, CTU-Main,, Cebu City, Philippines - 6000', '', 'activate', 'no', '', '', '09463478938', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `added_admins`
--
ALTER TABLE `added_admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blocked_users`
--
ALTER TABLE `blocked_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `blocked_user_id` (`blocked_user_id`);

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
-- Indexes for table `order_comments`
--
ALTER TABLE `order_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

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
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
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
-- Indexes for table `shipping_fee`
--
ALTER TABLE `shipping_fee`
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
-- AUTO_INCREMENT for table `added_admins`
--
ALTER TABLE `added_admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `blocked_users`
--
ALTER TABLE `blocked_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `breakdowns`
--
ALTER TABLE `breakdowns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=593;

--
-- AUTO_INCREMENT for table `investments`
--
ALTER TABLE `investments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=302;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=310;

--
-- AUTO_INCREMENT for table `order_comments`
--
ALTER TABLE `order_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `post_approvals`
--
ALTER TABLE `post_approvals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `search_history`
--
ALTER TABLE `search_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `shipping_fee`
--
ALTER TABLE `shipping_fee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blocked_users`
--
ALTER TABLE `blocked_users`
  ADD CONSTRAINT `blocked_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blocked_users_ibfk_2` FOREIGN KEY (`blocked_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
