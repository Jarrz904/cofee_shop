-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 12, 2025 at 08:53 AM
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
-- Database: `coffe_shop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Coffee', 'coffee', '2025-07-08 23:25:32', '2025-07-08 23:25:32'),
(2, 'Non-Coffee', 'non-coffee', '2025-07-08 23:25:32', '2025-07-08 23:25:32'),
(3, 'Makanan', 'makanan', '2025-07-08 23:25:32', '2025-07-08 23:25:32'),
(4, 'Snack', 'snack', '2025-07-08 23:25:32', '2025-07-08 23:25:32');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_07_09_031003_create_products_table', 1),
(5, '2025_07_09_031017_create_orders_table', 1),
(6, '2025_07_09_031031_create_order_details_table', 1),
(7, '2025_07_09_040849_add_rejection_reason_to_orders_table', 1),
(8, '2025_07_09_055748_create_categories_table', 1),
(9, '2025_07_09_062013_add_category_id_to_products_table', 1),
(10, '2025_07_11_111705_add_snap_token_to_orders_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `snap_token` varchar(255) DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `status`, `snap_token`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(67, 3, 30000.00, 'unpaid', '9306d916-c023-47ae-82a2-38f3e0399246', NULL, '2025-07-11 08:15:19', '2025-07-11 08:15:19'),
(68, 3, 30000.00, 'unpaid', '33b384bf-b81a-4302-8c89-731f3a6d5c40', NULL, '2025-07-11 08:15:53', '2025-07-11 08:15:54'),
(69, 3, 30000.00, 'unpaid', '14c44c58-1271-44dc-b89f-7bae300263fb', NULL, '2025-07-11 08:16:59', '2025-07-11 08:17:00'),
(70, 3, 30000.00, 'unpaid', 'e17376e6-190e-4822-83d8-76f5806eea50', NULL, '2025-07-11 08:27:52', '2025-07-11 08:27:53'),
(71, 3, 30000.00, 'unpaid', 'fc04cbc0-63ea-4a1d-b3f4-0d4181d6727c', NULL, '2025-07-11 08:51:28', '2025-07-11 08:51:28'),
(72, 3, 42000.00, 'unpaid', '7559f249-c41e-475f-af52-022cce6e476d', NULL, '2025-07-11 13:30:05', '2025-07-11 13:30:06'),
(73, 3, 30000.00, 'unpaid', '61f9d2c4-050c-411a-9cc7-bed500771f32', NULL, '2025-07-11 20:56:40', '2025-07-11 20:56:41'),
(74, 3, 42000.00, 'unpaid', '7ef1c4d4-eb1b-435d-887b-53f12a673f66', NULL, '2025-07-11 21:49:05', '2025-07-11 21:49:06'),
(75, 3, 15000.00, 'unpaid', 'f7ec3e4e-a1a3-457a-ba49-140ce7faf359', NULL, '2025-07-11 21:56:25', '2025-07-11 21:56:26'),
(76, 3, 12000.00, 'unpaid', 'ee00a1be-76d4-4b95-9ecd-13fe08f532f6', NULL, '2025-07-11 22:00:45', '2025-07-11 22:00:45'),
(77, 3, 15000.00, 'unpaid', 'c4e4e3f3-da47-41fd-93fd-9b7ee58b4d71', NULL, '2025-07-11 22:03:37', '2025-07-11 22:03:37');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(81, 67, 25, 2, 15000.00, '2025-07-11 08:15:19', '2025-07-11 08:15:19'),
(82, 68, 25, 2, 15000.00, '2025-07-11 08:15:53', '2025-07-11 08:15:53'),
(83, 69, 25, 2, 15000.00, '2025-07-11 08:16:59', '2025-07-11 08:16:59'),
(84, 70, 25, 2, 15000.00, '2025-07-11 08:27:52', '2025-07-11 08:27:52'),
(85, 71, 25, 2, 15000.00, '2025-07-11 08:51:28', '2025-07-11 08:51:28'),
(86, 72, 25, 2, 15000.00, '2025-07-11 13:30:05', '2025-07-11 13:30:05'),
(87, 72, 24, 1, 12000.00, '2025-07-11 13:30:05', '2025-07-11 13:30:05'),
(88, 73, 25, 2, 15000.00, '2025-07-11 20:56:40', '2025-07-11 20:56:40'),
(89, 74, 25, 2, 15000.00, '2025-07-11 21:49:05', '2025-07-11 21:49:05'),
(90, 74, 24, 1, 12000.00, '2025-07-11 21:49:05', '2025-07-11 21:49:05'),
(91, 75, 25, 1, 15000.00, '2025-07-11 21:56:25', '2025-07-11 21:56:25'),
(92, 76, 24, 1, 12000.00, '2025-07-11 22:00:45', '2025-07-11 22:00:45'),
(93, 77, 25, 1, 15000.00, '2025-07-11 22:03:37', '2025-07-11 22:03:37');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `created_at`, `updated_at`, `category_id`) VALUES
(7, 'Kentang Goreng', 'Makanan pengganjal perut pas laper', 10000.00, 'products/Y9gztEs8wpG3HdluKvBVTlSbq3Nz6BWBJOIrRAS3.jpg', '2025-07-09 00:08:32', '2025-07-09 00:08:32', 4),
(8, 'Lemineral', 'Air putih ada manis manisnya', 8000.00, 'products/jNDZ6whUAntlq9ynne02zhtjbnCFvNNwYASOgfla.png', '2025-07-09 04:27:10', '2025-07-09 04:27:10', 2),
(9, 'Teh Pucuk', 'Teh dingin seger', 10000.00, 'products/mPyZXVqIVBXkf4b2OP4z2c6IypYb3NA3CiB8PiDq.jpg', '2025-07-09 04:28:22', '2025-07-09 04:28:22', 2),
(10, 'Senpol Ayam', 'makanan pengganjal perut', 12000.00, 'products/Hn6aazBozp7N8VWFBluBURYwwyRV4dsUWqagIOHz.jpg', '2025-07-09 04:30:22', '2025-07-09 04:30:22', NULL),
(11, 'Banana Choco Chese', 'Makanan yang bikin anda lupa maslalu kelam', 15000.00, 'products/zFrRQ9oqsItZeO4fELNuYFBfOfXwZghKhgRN1THo.jpg', '2025-07-09 04:33:58', '2025-07-09 04:33:58', 4),
(12, 'Roti Bakar Italy', 'Makanan pembangkit mood kalian', 20000.00, 'products/4CoTXvpdg1LqsdY1x1r7GM3fuXiVcouFBGBMegEt.jpg', '2025-07-09 04:35:58', '2025-07-09 04:35:58', 4),
(13, 'Roti Bakar Lumer', 'Perpaduan keju yang lumer', 15000.00, 'products/1S06s76naT8DdV0KZwMT9r5uQy1lufeEAO9N2VKn.png', '2025-07-09 04:37:35', '2025-07-09 04:37:35', 4),
(14, 'BBQ Sausages with ketchup', 'perpaduan Sosis dengan saous BBQ', 23000.00, 'products/fZDgm93z24tAWABOlp9ZDssgPxsS6IplCP5XBlRP.png', '2025-07-09 04:41:18', '2025-07-09 04:41:18', 4),
(15, 'Japanese Omeletee', 'Makanan telur dadar Jepang', 18000.00, 'products/rWHKF1dDMoFeeuuD4sTtUkeQEVUEXw9FAFb24kQm.png', '2025-07-09 04:42:31', '2025-07-09 04:42:31', 4),
(16, 'Jus Alpukat', 'Jus buah alpukat seger', 8500.00, 'products/bDIhTLjjtwTHBCTXkMrHY5FgKIilk6cWDw4ydzpE.jpg', '2025-07-09 04:46:29', '2025-07-09 04:46:29', 2),
(17, 'Jus Jeruk', 'Minuman dengan rasa Jeruk', 8500.00, 'products/M5YZzloCoBosrjyOOMfjtSozb4ls6fxphaZ7pbdN.png', '2025-07-09 04:52:26', '2025-07-09 04:52:26', 2),
(18, 'Jus Manggo', 'Dengan rasa mangganya yang bikin mood anda kembali', 8500.00, 'products/lHSfPVlBxRRmKpMlKiMKOt0kpA8ouyddDpui2B8q.jpg', '2025-07-09 04:55:06', '2025-07-09 04:55:06', 2),
(19, 'Jus DragonFruit', 'Dengan rasa buah naga yang asli', 12000.00, 'products/zpsYNm0f4aL3oEdCwr5Wur1tLyDCLhQHTkL3vJqG.jpg', '2025-07-09 04:57:02', '2025-07-09 04:57:02', 2),
(20, 'Mie Goreng Korea', 'Mie goreng dengan telur mata sapi ga mungkin ga kenyang haha', 20000.00, 'products/Ev86q09c9nwN35gaElJj1hoaAIMbRw244fSZgZsS.jpg', '2025-07-09 05:00:11', '2025-07-09 05:00:11', NULL),
(21, 'Ramen Thailand', 'Mie asli Thailand', 19000.00, 'products/YaodnGpNRUna21cxJPC2yZzZSOqGXTZpmAMhqjOK.jpg', '2025-07-09 05:05:13', '2025-07-09 05:05:13', 3),
(22, 'Sup Iga', 'Soal rasa jangan di tanya perpaduan kuah yang gurih dan lezat', 19000.00, 'products/0p5hFjpbvdGFugz8f1xzHc869V0SlCNW7Gk7XLzm.jpg', '2025-07-09 05:08:02', '2025-07-09 05:08:02', 3),
(23, 'Vitnam Drip Coffe', 'Minuman Coffe dari vietnam dengan penyajian yang sedkiti berbeda dengan bisasnya', 23000.00, 'products/bM7h5TiynLNZNsmSrlnLocOGNBn4zhfMa11Agi4X.jpg', '2025-07-09 05:13:35', '2025-07-09 05:13:35', 1),
(24, 'Mochacino', 'Cofee yang membuat hidup kamu jauh lebih indah', 12000.00, 'products/xMyFWgRrjInCeK4NHQ7Aaje8xSnDfDkGeXIgwrNK.jpg', '2025-07-09 05:18:20', '2025-07-09 05:18:20', 1),
(25, 'Cappuccino', 'Kombinasi sempurna antara eksspreso susu dan busa', 15000.00, 'products/hZIA2Yv5gTNyA14VFWLaV4GGCp38UK68rl8gPxwe.jpg', '2025-07-10 09:21:13', '2025-07-10 09:21:13', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('7w5ntcpjN0dtMqdgB099U2wUHDnfhpgw7PMqCIKR', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMTFzMVlPZE5SNXcyQUVqZktJeThsd3hMZ0pId0JvbTZScnRQeThsaSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly85Zjc5OWY2NTM1NTEubmdyb2stZnJlZS5hcHAvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1752295514),
('8vngR6ScVwkMpBxiut934tXL9xxUo3UHJXJR06R5', NULL, '127.0.0.1', 'Veritrans', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiZFlHUlZHdkFpRzB5d29IeFd1eUs5dVBZeTZ2UUtTU0pZUVY0cWtmbCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1752296464),
('9iBkan0INBZTNe7dOLedO4llQTyJUDIYJqxxsfdl', NULL, '127.0.0.1', 'Veritrans', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiMzdXRnhFeml6UXZlbkVVQ3JER09zWGw4SnU1RVE4emFRbjM4TEFwbyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1752296204),
('CODCfhF3Do5ABvZzGpZA6tXauXlW9Jnsy7vZrNFX', NULL, '127.0.0.1', 'Veritrans', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoia3EyenZnaFFaU3J5NUZJSHI0QmhpZFdadFBLdFBJSnU1bXpxdERQUyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1752296521),
('IiMz1CQMCObisANpvp39EH4C6Bs1nr2v4xbovst3', NULL, '127.0.0.1', 'Veritrans', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoienFnbUtWamFyZVZqaGkwWDJYcDI4b2h0UlJzNEhrb3BLTktwVTJHMyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1752296189),
('oXNkzsbwfScKCwRboUMBtoUvf3RpYPVJOx002vgo', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoid2dJdlBDNFN6aDRMQVNHR29IU0Z6ZVZNeFFiR0tUZkF2RnVxNXdnQSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9teS1vcmRlcnMiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1752296642),
('QYzCZldKNkGy61uGHnyHpZRCTJPmnqG4xwa3d4ib', NULL, '127.0.0.1', 'Veritrans', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiMHZ3MDB6NTdsVktXdktQelFwUG9ON3lwOGd4d3htRkFkUWl6djBGbiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1752296514),
('RmiEbOhoRMOqH2vSuaVxpNcYwrVvt00Yo3HZcbOR', NULL, '127.0.0.1', 'Veritrans', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiUjNZMzlTRWVNSk5ScWphR1Q4WG83Wk9LbFJXRUoxUk51bllxUU5ybyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1752296251),
('WOEqn3n44RUVApeBotebDBh3q4ch74jPipyh7RNX', NULL, '127.0.0.1', 'Veritrans', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiWERUQlh6Q1E5MEVJczkwTkhpSjFHQnVGQWFWaG83YURwTm9qZktXbCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1752296345),
('xYJ68gE4Jg83VuWzI2T1K9zMYzNPTAaT9uLqbZI3', NULL, '127.0.0.1', 'Veritrans', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiRW9NOTh5NnY0UFB3MVFkdVhvTjR4NkNOZFQ2TXRNM3dMZzNCZ3Y3NSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1752296447),
('zZVWeWis2rTyM0Pv22lUSOE77Eq58tJWC7hiUhHf', NULL, '127.0.0.1', 'Veritrans', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiYjRXUVEyQXpUNkV5Y2Q3MmlDdENMMDMzc0JaaUNYVndIU1k1dmlXRCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1752296600);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@coffe.com', NULL, '$2y$12$m3gwNMF7Z2FPbJttBbBz3eT8f/L5PX8SngTckS1eKBP9kJyGKDp2m', 'admin', NULL, '2025-07-08 23:25:32', '2025-07-08 23:25:32'),
(2, 'User Biasa', 'user@coffe.com', NULL, '$2y$12$kyK2hnEIvpYx4cbupJfhV.lv0MvvjRJCQdvoursrcVHlqligxAwaK', 'user', NULL, '2025-07-08 23:25:32', '2025-07-08 23:25:32'),
(3, 'fajar', 'mfajarsidik64@gmail.com', NULL, '$2y$12$T0cJ9wglCjSC5fz4zu1RN.J4R1.cTbJNuVUaU8z.MOQosSYBgAOoC', 'user', NULL, '2025-07-08 23:53:42', '2025-07-08 23:53:42'),
(4, 'menu admin', 'akunnobar10@gmail.com', NULL, '$2y$12$nkk1kv.it5/63vxjjp.7YOY9wBSbqQ7KJMNzO52csfs1nV.vCTO5i', 'admin', NULL, '2025-07-08 23:55:34', '2025-07-08 23:55:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_details_order_id_foreign` (`order_id`),
  ADD KEY `order_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
