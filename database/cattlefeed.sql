-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 23, 2025 at 09:59 AM
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
-- Database: `foot`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_activity_log`
--

CREATE TABLE `admin_activity_log` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `action_details` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_activity_log`
--

INSERT INTO `admin_activity_log` (`id`, `admin_id`, `action_type`, `action_details`, `ip_address`, `created_at`) VALUES
(548, 3, 'login', 'Admin logged in', '::1', '2025-03-05 19:51:54'),
(549, 3, 'view', 'Viewed dashboard', '::1', '2025-03-05 19:51:54'),
(550, 3, 'view', 'Viewed products list', '::1', '2025-03-05 19:51:56'),
(551, 3, 'view', 'Viewed orders page', '::1', '2025-03-05 19:51:57'),
(552, 3, 'view', 'Viewed products list', '::1', '2025-03-05 19:51:59'),
(553, 3, 'view', 'Viewed products list', '::1', '2025-03-05 19:56:18'),
(554, 3, 'delete', 'Deleted product ID: 22', '::1', '2025-03-05 19:56:36'),
(555, 3, 'view', 'Viewed products list', '::1', '2025-03-05 19:56:36'),
(556, 3, 'view', 'Viewed edit product page for product ID: 25', '::1', '2025-03-05 19:56:39'),
(557, 3, 'view', 'Viewed orders page', '::1', '2025-03-05 19:57:37'),
(558, 3, 'view', 'Viewed users list', '::1', '2025-03-05 19:57:38'),
(559, 3, 'view', 'Viewed users list', '::1', '2025-03-05 19:57:41'),
(560, 3, 'view', 'Viewed orders page', '::1', '2025-03-05 19:57:41'),
(561, 3, 'view', 'Viewed order #12 details', '::1', '2025-03-05 19:57:43'),
(562, 3, 'view', 'Viewed orders page', '::1', '2025-03-05 19:57:43'),
(563, 3, 'login', 'Admin logged in', '::1', '2025-03-20 14:56:12'),
(564, 3, 'view', 'Viewed dashboard', '::1', '2025-03-20 14:56:12'),
(565, 3, 'view', 'Viewed products list', '::1', '2025-03-20 14:56:19'),
(566, 3, 'view', 'Viewed add product page', '::1', '2025-03-20 14:56:28'),
(567, 3, 'create', 'Added new product: Compounded Goat / Sheep Feed (ID: 29)', '::1', '2025-03-20 14:59:14'),
(568, 3, 'view', 'Viewed products list', '::1', '2025-03-20 14:59:14'),
(569, 3, 'view', 'Viewed add product page', '::1', '2025-03-20 15:00:52'),
(570, 3, 'create', 'Added new product: GRAND MASTER GOAT FEED (ID: 30)', '::1', '2025-03-20 15:02:32'),
(571, 3, 'view', 'Viewed products list', '::1', '2025-03-20 15:02:32'),
(572, 3, 'view', 'Viewed products list', '::1', '2025-03-20 15:23:34'),
(573, 3, 'view', 'Viewed products list', '::1', '2025-03-20 15:23:39'),
(574, 3, 'view', 'Viewed edit product page for product ID: 30', '::1', '2025-03-20 15:50:52'),
(575, 3, 'update', 'Updated product: GRAND MASTER GOAT FEED (ID: 30)', '::1', '2025-03-20 15:51:01'),
(576, 3, 'view', 'Viewed products list', '::1', '2025-03-20 15:51:01'),
(577, 3, 'view', 'Viewed add product page', '::1', '2025-03-20 16:28:48'),
(578, 3, 'view', 'Viewed products list', '::1', '2025-03-20 16:28:56'),
(579, 3, 'view', 'Viewed edit product page for product ID: 25', '::1', '2025-03-20 16:29:03'),
(580, 3, 'view', 'Viewed products list', '::1', '2025-03-20 16:29:12'),
(581, 3, 'view', 'Viewed add product page', '::1', '2025-03-20 16:29:15'),
(582, 3, 'create', 'Added new product: Pellet Essar Special Mix Churi Cattle Feed (ID: 31)', '::1', '2025-03-20 16:33:37'),
(583, 3, 'view', 'Viewed products list', '::1', '2025-03-20 16:33:37'),
(584, 3, 'view', 'Viewed products list', '::1', '2025-03-22 13:45:25'),
(585, 3, 'delete', 'Deleted product ID: 21', '::1', '2025-03-22 13:45:32'),
(586, 3, 'view', 'Viewed products list', '::1', '2025-03-22 13:45:32'),
(587, 3, 'delete', 'Deleted product ID: 27', '::1', '2025-03-22 13:45:34'),
(588, 3, 'view', 'Viewed products list', '::1', '2025-03-22 13:45:34'),
(589, 3, 'delete', 'Deleted product ID: 26', '::1', '2025-03-22 13:45:35'),
(590, 3, 'view', 'Viewed products list', '::1', '2025-03-22 13:45:35'),
(591, 3, 'delete', 'Deleted product ID: 25', '::1', '2025-03-22 13:45:36'),
(592, 3, 'view', 'Viewed products list', '::1', '2025-03-22 13:45:36'),
(593, 3, 'delete', 'Deleted product ID: 24', '::1', '2025-03-22 13:45:36'),
(594, 3, 'view', 'Viewed products list', '::1', '2025-03-22 13:45:36'),
(595, 3, 'delete', 'Deleted product ID: 23', '::1', '2025-03-22 13:45:38'),
(596, 3, 'view', 'Viewed products list', '::1', '2025-03-22 13:45:38'),
(597, 3, 'view', 'Viewed products list', '::1', '2025-03-22 13:45:39'),
(598, 3, 'delete', 'Deleted product ID: 20', '::1', '2025-03-22 13:45:40'),
(599, 3, 'view', 'Viewed products list', '::1', '2025-03-22 13:45:40'),
(600, 3, 'view', 'Viewed products list', '::1', '2025-03-22 13:45:43'),
(601, 3, 'delete', 'Deleted product ID: 28', '::1', '2025-03-22 13:45:47'),
(602, 3, 'view', 'Viewed products list', '::1', '2025-03-22 13:45:47'),
(603, 3, 'delete', 'Deleted product ID: 19', '::1', '2025-03-22 13:45:48'),
(604, 3, 'view', 'Viewed products list', '::1', '2025-03-22 13:45:48'),
(605, 3, 'delete', 'Deleted product ID: 29', '::1', '2025-03-22 13:46:02'),
(606, 3, 'view', 'Viewed products list', '::1', '2025-03-22 13:46:02'),
(607, 3, 'view', 'Viewed add footwear product page', '::1', '2025-03-22 14:59:36'),
(608, 3, 'create', 'Added new footwear product: Nike Vomero 18 (ID: 32)', '::1', '2025-03-22 15:00:26'),
(609, 3, 'view', 'Viewed products list', '::1', '2025-03-22 15:00:26'),
(610, 3, 'view', 'Viewed add footwear product page', '::1', '2025-03-22 15:00:55'),
(611, 3, 'view', 'Viewed products list', '::1', '2025-03-22 15:01:11'),
(612, 3, 'view', 'Viewed dashboard', '::1', '2025-03-22 15:01:16'),
(613, 3, 'view', 'Viewed products list', '::1', '2025-03-22 15:01:17'),
(614, 3, 'view', 'Viewed orders page', '::1', '2025-03-22 15:01:20'),
(615, 3, 'view', 'Viewed products list', '::1', '2025-03-22 15:01:25'),
(616, 3, 'view', 'Viewed edit product page for product ID: 32', '::1', '2025-03-22 15:01:27'),
(617, 3, 'view', 'Viewed edit product page for product ID: 32', '::1', '2025-03-22 15:15:34'),
(618, 3, 'view', 'Viewed edit product page for product ID: 32', '::1', '2025-03-22 15:20:08'),
(619, 3, 'view', 'Viewed products list', '::1', '2025-03-22 15:20:37'),
(620, 3, 'view', 'Viewed edit product page for product ID: 32', '::1', '2025-03-22 15:20:47'),
(621, 3, 'view', 'Viewed edit footwear product page for product ID: 32', '::1', '2025-03-22 15:27:00'),
(622, 3, 'update', 'Updated footwear product: Nike Vomero 18 (ID: 32)', '::1', '2025-03-22 15:27:25'),
(623, 3, 'view', 'Viewed products list', '::1', '2025-03-22 15:27:25'),
(624, 3, 'view', 'Viewed dashboard', '::1', '2025-03-22 15:27:41'),
(625, 3, 'view', 'Viewed orders page', '::1', '2025-03-22 15:27:44'),
(626, 3, 'view', 'Viewed products list', '::1', '2025-03-22 15:27:45'),
(627, 3, 'view', 'Viewed users list', '::1', '2025-03-22 15:27:47'),
(628, 3, 'view', 'Viewed ratings page', '::1', '2025-03-22 15:27:49'),
(629, 3, 'view', 'Viewed ratings page', '::1', '2025-03-22 15:27:57'),
(630, 3, 'view', 'Viewed users list', '::1', '2025-03-22 15:28:01'),
(631, 3, 'view', 'Viewed orders page', '::1', '2025-03-22 15:28:04'),
(632, 3, 'view', 'Viewed products list', '::1', '2025-03-22 15:28:05'),
(633, 3, 'view', 'Viewed products list', '::1', '2025-03-22 15:28:08'),
(634, 3, 'view', 'Viewed dashboard', '::1', '2025-03-22 15:28:09'),
(635, 3, 'view', 'Viewed edit footwear product page for product ID: 32', '::1', '2025-03-22 15:31:16'),
(636, 3, 'view', 'Viewed products list', '::1', '2025-03-22 15:31:27'),
(637, 3, 'login', 'Admin logged in', '::1', '2025-03-22 22:09:36'),
(638, 3, 'view', 'Viewed dashboard', '::1', '2025-03-22 22:09:36'),
(639, 3, 'view', 'Viewed products list', '::1', '2025-03-22 22:09:38'),
(640, 3, 'delete', 'Deleted product ID: 32', '::1', '2025-03-22 22:09:42'),
(641, 3, 'view', 'Viewed products list', '::1', '2025-03-22 22:09:42'),
(642, 3, 'delete', 'Deleted product ID: 31', '::1', '2025-03-22 22:09:45'),
(643, 3, 'view', 'Viewed products list', '::1', '2025-03-22 22:09:45'),
(644, 3, 'delete', 'Deleted product ID: 30', '::1', '2025-03-22 22:09:48'),
(645, 3, 'view', 'Viewed products list', '::1', '2025-03-22 22:09:48'),
(646, 3, 'view', 'Viewed add footwear product page', '::1', '2025-03-22 22:09:50'),
(647, 3, 'create', 'Added new footwear product: Nike Vomero 18 (ID: 33)', '::1', '2025-03-22 22:13:04'),
(648, 3, 'view', 'Viewed products list', '::1', '2025-03-22 22:13:04'),
(649, 3, 'view', 'Viewed add footwear product page', '::1', '2025-03-22 22:20:52'),
(650, 3, 'create', 'Added new footwear product: Nike Pegasus Premium (ID: 34)', '::1', '2025-03-22 22:23:55'),
(651, 3, 'view', 'Viewed products list', '::1', '2025-03-22 22:23:55'),
(652, 3, 'login', 'Admin logged in', '::1', '2025-03-22 22:41:52'),
(653, 3, 'view', 'Viewed dashboard', '::1', '2025-03-22 22:41:52'),
(654, 3, 'view', 'Viewed products list', '::1', '2025-03-22 22:41:54'),
(655, 3, 'view', 'Viewed add footwear product page', '::1', '2025-03-22 22:41:56'),
(656, 3, 'create', 'Added new footwear product: Nike Air Max Dn8 (ID: 35)', '::1', '2025-03-22 22:44:22'),
(657, 3, 'view', 'Viewed products list', '::1', '2025-03-22 22:44:22'),
(658, 3, 'view', 'Viewed orders page', '::1', '2025-03-22 23:14:41'),
(659, 3, 'view', 'Viewed order #13 details', '::1', '2025-03-22 23:14:48'),
(660, 3, 'view', 'Viewed orders page', '::1', '2025-03-22 23:14:48'),
(661, 3, 'view', 'Viewed products list', '::1', '2025-03-22 23:15:11'),
(662, 3, 'view', 'Viewed users list', '::1', '2025-03-22 23:15:12'),
(663, 3, 'view', 'Viewed ratings page', '::1', '2025-03-22 23:15:13'),
(664, 3, 'view', 'Viewed ratings page', '::1', '2025-03-22 23:15:16'),
(665, 3, 'view', 'Viewed users list', '::1', '2025-03-22 23:15:18'),
(666, 3, 'view', 'Viewed orders page', '::1', '2025-03-22 23:15:19'),
(667, 3, 'view', 'Viewed products list', '::1', '2025-03-22 23:15:20'),
(668, 3, 'view', 'Viewed dashboard', '::1', '2025-03-22 23:15:21'),
(669, 3, 'view', 'Viewed dashboard', '::1', '2025-03-22 23:22:23'),
(670, 3, 'view', 'Viewed dashboard', '::1', '2025-03-23 05:52:18'),
(671, 3, 'view', 'Viewed orders page', '::1', '2025-03-23 05:52:20'),
(672, 3, 'login', 'Admin logged in', '::1', '2025-03-23 06:49:33'),
(673, 3, 'view', 'Viewed dashboard', '::1', '2025-03-23 06:49:33'),
(674, 3, 'login', 'Admin logged in', '::1', '2025-03-23 07:30:48'),
(675, 3, 'view', 'Viewed dashboard', '::1', '2025-03-23 07:30:48'),
(676, 3, 'view', 'Viewed products list', '::1', '2025-03-23 07:31:36'),
(677, 3, 'view', 'Viewed add footwear product page', '::1', '2025-03-23 07:31:39'),
(678, 3, 'view', 'Viewed add footwear product page', '::1', '2025-03-23 07:33:37'),
(679, 3, 'create', 'Added new footwear product: Galaxis Pro Men\'s Performance Boost Running Shoes (ID: 36)', '::1', '2025-03-23 07:34:20'),
(680, 3, 'view', 'Viewed products list', '::1', '2025-03-23 07:34:20'),
(681, 3, 'view', 'Viewed add footwear product page', '::1', '2025-03-23 07:34:24'),
(682, 3, 'create', 'Added new footwear product: ST Trainer Evo Slip-On II Sneakers (ID: 37)', '::1', '2025-03-23 07:37:37'),
(683, 3, 'view', 'Viewed products list', '::1', '2025-03-23 07:37:37'),
(684, 3, 'view', 'Viewed add footwear product page', '::1', '2025-03-23 07:39:53'),
(685, 3, 'create', 'Added new footwear product: Puma Men\'s Coarse Running Shoe (ID: 38)', '::1', '2025-03-23 07:41:50'),
(686, 3, 'view', 'Viewed products list', '::1', '2025-03-23 07:41:50'),
(687, 3, 'view', 'Viewed edit footwear product page for product ID: 37', '::1', '2025-03-23 07:43:08'),
(688, 3, 'update', 'Updated footwear product: ST Trainer Evo Slip-On II Sneakers (ID: 37)', '::1', '2025-03-23 07:43:48'),
(689, 3, 'view', 'Viewed products list', '::1', '2025-03-23 07:43:48'),
(690, 3, 'view', 'Viewed edit footwear product page for product ID: 36', '::1', '2025-03-23 07:44:24'),
(691, 3, 'update', 'Updated footwear product: Galaxis Pro Men\'s Performance Boost Running Shoes (ID: 36)', '::1', '2025-03-23 07:44:58'),
(692, 3, 'view', 'Viewed products list', '::1', '2025-03-23 07:44:58'),
(693, 3, 'view', 'Viewed add footwear product page', '::1', '2025-03-23 08:04:57'),
(694, 3, 'create', 'Added new footwear product: Nike Vomero 20 (ID: 39)', '::1', '2025-03-23 08:06:00'),
(695, 3, 'view', 'Viewed products list', '::1', '2025-03-23 08:06:00'),
(696, 3, 'login', 'Admin logged in', '::1', '2025-03-23 08:48:56'),
(697, 3, 'view', 'Viewed dashboard', '::1', '2025-03-23 08:48:56'),
(698, 3, 'view', 'Viewed products list', '::1', '2025-03-23 08:48:59'),
(699, 3, 'view', 'Viewed orders page', '::1', '2025-03-23 08:49:02'),
(700, 3, 'view', 'Viewed users list', '::1', '2025-03-23 08:49:04'),
(701, 3, 'view', 'Viewed ratings page', '::1', '2025-03-23 08:49:06'),
(702, 3, 'view', 'Viewed dashboard', '::1', '2025-03-23 08:49:11'),
(703, 3, 'view', 'Viewed ratings page', '::1', '2025-03-23 08:49:17'),
(704, 3, 'view', 'Viewed products list', '::1', '2025-03-23 08:51:58');

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','product_manager','order_manager','customer_support') NOT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `email`, `password`, `role`, `last_login`, `created_at`) VALUES
(3, 'admin', 'admin@gmail.com', '$2y$10$TfA4GE63sHNuEDE7XmVUKODrAjTOi2FI4I75GpeBn/oz98/zAq6VS', 'super_admin', '2025-03-23 08:48:56', '2025-03-01 17:43:59'),
(4, 'root', 'root@gmail.com', '$2y$10$C01Ne04nXOOfUV7Zr5aWxusrKMtvE9zfzFPa1AJZaINeSxJekhuG2', 'product_manager', '2025-03-02 11:14:02', '2025-03-02 07:22:40');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'pending',
  `color` varchar(50) DEFAULT NULL,
  `size` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `product_id`, `quantity`, `added_at`, `status`, `color`, `size`) VALUES
(51, 10, 34, 1, '2025-03-22 16:06:07', 'processed', 'Blue', '8'),
(52, 10, 35, 1, '2025-03-22 16:06:07', 'processed', 'Blue', '8'),
(54, 10, 35, 2, '2025-03-22 16:06:07', 'processed', 'Blue', '8'),
(55, 10, 33, 1, '2025-03-22 16:06:07', 'processed', 'Multicolor', '8-9'),
(56, 10, 34, 1, '2025-03-22 16:06:07', 'processed', 'Blue', '7'),
(57, 10, 35, 2, '2025-03-22 16:06:07', 'processed', 'White', '7'),
(60, 10, 34, 1, '2025-03-22 16:06:07', 'processed', 'Brown', '8');

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(9, 'root', 'root@gmail.com', 'Your message', '2025-03-04 12:11:45');

-- --------------------------------------------------------

--
-- Table structure for table `email_logs`
--

CREATE TABLE `email_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_address` text NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `order_status` enum('processing','shipped','delivered','cancelled') DEFAULT 'processing',
  `tracking_number` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `shipping_address`, `payment_method`, `payment_status`, `order_status`, `tracking_number`, `notes`, `created_at`, `updated_at`) VALUES
(15, 10, 42627.64, 'at pune, pune, Arunachal Pradesh - 411045', 'cod', 'pending', 'processing', NULL, NULL, '2025-03-23 00:20:58', '2025-03-23 00:20:58'),
(16, 10, 15842.82, 'at pune, Mumbai, Meghalaya - 410001', 'cod', 'pending', 'processing', NULL, NULL, '2025-03-23 05:51:47', '2025-03-23 05:51:47'),
(17, 10, 22806.00, 'at pune, pune, Maharashtra - 431206', 'upi', 'completed', 'processing', NULL, NULL, '2025-03-23 06:02:32', '2025-03-23 06:02:32'),
(18, 10, 65283.64, 'at pune, pune, Maharashtra - 245323', 'upi', 'completed', 'processing', NULL, NULL, '2025-03-23 08:51:40', '2025-03-23 08:51:40');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_per_unit` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `size` varchar(100) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price_per_unit`, `subtotal`, `size`, `color`) VALUES
(21, 15, 35, 2, 17999.00, 35998.00, NULL, NULL),
(22, 16, 33, 1, 13299.00, 13299.00, NULL, NULL),
(23, 17, 34, 1, 19200.00, 19200.00, NULL, NULL),
(24, 18, 35, 2, 17999.00, 35998.00, NULL, NULL),
(25, 18, 34, 1, 19200.00, 19200.00, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image1` varchar(255) NOT NULL,
  `image2` varchar(255) DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `for_who` varchar(50) NOT NULL,
  `size` varchar(50) NOT NULL,
  `color` varchar(50) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `material` varchar(100) NOT NULL,
  `stock` int(11) NOT NULL,
  `added_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `description`, `price`, `image1`, `image2`, `category`, `for_who`, `size`, `color`, `brand`, `material`, `stock`, `added_by`, `created_at`) VALUES
(33, 'Nike Vomero 18', 'Maximum cushioning in the Vomero provides a comfortable ride for everyday runs. Our softest, most cushioned ride has lightweight ZoomX foam stacked on top of responsive ReactX foam in the midsole. Plus, a redesigned traction pattern offers a smooth heel-to-toe transition.\r\n\r\nEngineered mesh upper\r\nThe upper is made from engineered mesh for soft breathability.\r\n\r\nDual-density midsole\r\nOur dual-density midsole has ZoomX foam stacked on top of ReactX foam—13% more responsive than previous React technology—for a comfortable ride.\r\n\r\nPods around the outsole\r\nWe placed pods around the outsole to help enhance agility and smoother heel-to-toe transitions.\r\n\r\nPlush padding\r\nA plush tongue and lining give you a comfortably snug feel.\r\n\r\nWhat\'s new?\r\nThe newly stacked foam midsole with premium ZoomX on top of ReactX foam amplifies cushioned comfort.\r\n\r\nProduct details\r\nWeight: 325g approx. (men\'s size 9)\r\nHeel-to-toe drop: 10mm\r\nMR-10 Last—Our best, most consistent fit\r\nNot intended for use as personal protective equipment (PPE)\r\nColour Shown: Summit White/Dusty Cactus/Geode Teal/Black\r\nStyle: HM6803-103\r\nCountry/Region of Origin: Vietnam\r\nCrafted for performance and planet\r\nThe ReactX foam is engineered to reduce its carbon footprint by at least 43% in a pair of midsoles due to reduced manufacturing process energy compared with prior React foam. The carbon footprint of ReactX is based on cradle-to-gate assessment reviewed by PRé Sustainability B.V. and Intertek China. Other midsole components such as airbags, plates or other foam formulations were not considered.\r\n\r\nSkip to main content\r\nFind a Store\r\n\r\n', 13299.00, 'assets/uploads/1742681584_1_NIKE+.png', 'assets/uploads/1742681584_2_NIKE+1.png', 'Sports', 'Unisex', '8-9', 'Multicolor', 'Nike', 'Rubber', 5, 3, '2025-03-22 22:13:04'),
(34, 'Nike Pegasus Premium', 'The Pegasus Premium supercharges responsive cushioning with a triple stack of our most powerful running technologies: ZoomX foam, a sculpted Air Zoom unit and ReactX foam. It\'s the most responsive Pegasus ever, providing high energy return unlike any other. With a lighter-than-air upper, it decreases weight and increases breathability so you can fly faster.\r\n\r\n\r\nColour Shown: Black/Bright Crimson/Metallic Silver\r\nStyle: HQ2592-003\r\nCountry/Region of Origin: Vietnam', 19200.00, 'assets/uploads/1742682235_1_NIKE+PEGASUS+PREMIUM.png', 'assets/uploads/1742682235_2_NIKE+PEGASUS+PREMIUM (1).png', 'Sports Shoes', 'Unisex', '5, 6, 7, 8, 9', 'Brown, Blue, Red', 'Nike', 'Fabric', 4, 3, '2025-03-22 22:23:55'),
(35, 'Nike Air Max Dn8', 'More Air, less bulk. The Dn8 takes our Dynamic Air system and condenses it into a sleek, low-profile package. Powered by eight pressurised Air tubes, it gives you a responsive sensation with every step. Enter an unreal experience of movement.\r\n\r\n\r\nColour Shown: Black/Light Smoke Grey/Black/Volt\r\nStyle: FQ7860-003\r\nCountry/Region of Origin: Vietnam', 17999.00, 'assets/uploads/1742683462_1_AIR+MAX+DN8 (1).png', 'assets/uploads/1742683462_2_AIR+MAX+DN8 (2).png', 'Sports Shoes', 'Unisex', '5, 6, 7, 8, 9', 'Brown, White, Blue, Red', 'Nike', 'Rubber', 6, 3, '2025-03-22 22:44:22'),
(36, 'Galaxis Pro Men\'s Performance Boost Running Shoes', 'Description\r\nThese running shoes are the perfect blend of comfort and support. Crafted from superior textile for a lightweight feel, these shoes are built for the long haul. The rounded toe and flat heel offer a seamless stride on the track while the lace-up fastening provides a secure fit. Striking the perfect balance between functionality and style, these shoes keep you in vogue even when you\'re on the move.\r\nPROFOAM: Offers superior cushioning for all out comfort, supporting you throughout your running journey.\r\nPROTREAD Outsole: Boasts a durable outsole rubber and tread pattern, providing multi-surface traction to keep you steady on your feet.\r\nRUN PUMA Last: A performance fit designed to adapt to your foot\'s natural movement for a smooth and efficient run.\r\nFabrics Type: Textile\r\nShoe Width: Regular\r\nHeel Type: Flat\r\nSurface: Road\r\nToe Type: Rounded\r\nBuilt for running distance of up to 500km\r\nStyle: 311767_03\r\nColor: PUMA White-PUMA Black-Active Red\r\n\r\nRead more\r\nShipping and Returns\r\nFree return on all qualifying orders within 14 days of your order delivery date. Visit our Return Policy for more information.', 3499.00, 'assets/uploads/1742715260_1_Galaxis-Pro-Men\'s-Performance-Boost-Running-Shoes.jpg', 'assets/uploads/1742715260_2_Galaxis-Pro-Men\'s-Performance-Boost-Running-Shoes (1).jpg', 'Sports Shoes', 'Men', '11', 'Brown, White, Blue, Red', 'Puma', '0', 5, 3, '2025-03-23 07:34:20'),
(37, 'ST Trainer Evo Slip-On II Sneakers', 'Slip into style and comfort with the ST Trainer Evo Slip-On II Unisex Sneakers. The slip-on design makes them easy to wear, while the PUMA Formstrip detail adds a touch of sophistication, making these shoes perfect for any casual or athletic occasion.\r\nDetails\r\nMesh upper\r\nRubber outsole\r\nHeel type: Flat\r\nShoe width: Regular fit\r\nHeel-to-toe-drop: 0 mm\r\nPUMA Wordmark on webbing strap\r\nPUMA Cat logo on pull tab\r\nManufacturer\'s address\r\nMOCHIKO SHOES PRIVATE LIMITED\r\nMochiko Shoes - A Unit of\r\nFINMK\r\nUttarakhand\r\nKhasra no. 3914\r\nLal Tappar Industrial Area\r\nDoiwala\r\n248140 Dehradun', 2599.00, 'assets/uploads/1742715457_1_ST-Trainer-Evo-Slip-On-II-Sneakers.jpg', 'assets/uploads/1742715457_2_ST-Trainer-Evo-Slip-On-II-Sneakers1.jpg', 'Women', 'Women', '9', 'Blue, Red', 'Puma', '0', 1, 3, '2025-03-23 07:37:37'),
(38, 'Puma Men\'s Coarse Running Shoe', 'Product details\r\nMaterial typeTextile\r\nClosure typeLace-Up\r\nHeel typeFlat\r\nWater resistance levelNot Water Resistant\r\nSole materialRubber\r\nStyleRunning\r\nCountry of OriginIndia\r\nAbout this item\r\nStyle Name:-Running Shoe\r\nModel Name:-Coarse\r\nBrand Color:-Black-White\r\nMaterial:-Textile\r\nCare Instructions:-Wipe with a clean dry cloth', 1749.00, 'assets/uploads/1742715710_1_51ahWntzXWL._SY695_.jpg', 'assets/uploads/1742715710_2_51Cs+xUnFNL._SY695_.jpg', 'Sports Shoes', 'Men', '7, 8', 'Black, Brown', 'Puma', 'Synthetic', 3, 3, '2025-03-23 07:41:50'),
(39, 'Nike Vomero 20', 'nothing', 2000.00, 'assets/uploads/1742717160_1_Screenshot_20250321_175726.jpg', NULL, 'Kids', 'Kids', '5', 'Blue', 'Nike', 'Canvas', 1, 3, '2025-03-23 08:06:00');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `comments` text DEFAULT NULL,
  `products_purchased` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `user_id`, `name`, `email`, `rating`, `comments`, `products_purchased`, `created_at`) VALUES
(8, 10, 'root', 'root@gmail.com', 4, 'its good', 'amul', '2025-03-04 12:27:43'),
(9, 10, 'root', 'root@gmail.com', 4, 'nothing', 'nothing', '2025-03-22 13:32:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `phone`, `password`, `created_at`, `location`) VALUES
(10, 'root_admin', 'root@gmail.com', '1234567890', '$2y$10$pFXdXOvNzh1.ZoDpKnG3D.pmnmR/N/JpVnX3QKOhmtce6zWnYP7xC', '2025-03-02 11:18:12', 'at pune');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_activity_log`
--
ALTER TABLE `admin_activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_logs`
--
ALTER TABLE `email_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `added_by` (`added_by`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ratings_user` (`user_id`),
  ADD KEY `idx_ratings_rating` (`rating`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_activity_log`
--
ALTER TABLE `admin_activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=705;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `email_logs`
--
ALTER TABLE `email_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_activity_log`
--
ALTER TABLE `admin_activity_log`
  ADD CONSTRAINT `admin_activity_log_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `email_logs`
--
ALTER TABLE `email_logs`
  ADD CONSTRAINT `email_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `email_logs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
