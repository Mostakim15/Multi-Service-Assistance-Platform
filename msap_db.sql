-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2025 at 05:43 AM
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
-- Database: `msap_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `manager_activity_log`
--

CREATE TABLE `manager_activity_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `manager_id` int(10) UNSIGNED NOT NULL,
  `activity_type` varchar(120) NOT NULL,
  `target_table` varchar(60) DEFAULT NULL,
  `target_id` int(10) UNSIGNED DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(10) UNSIGNED NOT NULL,
  `provider_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `service_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price_range` varchar(80) DEFAULT NULL,
  `lat` decimal(10,7) DEFAULT NULL,
  `lng` decimal(10,7) DEFAULT NULL,
  `status` enum('pending','approved','rejected','inactive') NOT NULL DEFAULT 'pending',
  `approved_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `provider_id`, `category_id`, `service_name`, `description`, `image`, `price_range`, `lat`, `lng`, `status`, `approved_by`, `created_at`) VALUES
(1, 3, 6, 'Household Electrician', 'Wiring, repair, installation services for home', NULL, NULL, 23.7808875, 90.2792371, 'pending', NULL, '2025-11-12 11:23:39');

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_name` varchar(120) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`id`, `category_name`, `icon`, `created_at`) VALUES
(1, 'Medical & Health', 'mdi-hospital-box', '2025-11-12 11:23:39'),
(2, 'Ambulance Service', 'mdi-ambulance', '2025-11-12 11:23:39'),
(3, 'Police Assistance', 'mdi-police-badge', '2025-11-12 11:23:39'),
(4, 'Fire Service', 'mdi-fire', '2025-11-12 11:23:39'),
(5, 'Blood Donation', 'mdi-blood-bag', '2025-11-12 11:23:39'),
(6, 'Electric Solutions', 'mdi-flash', '2025-11-12 11:23:39'),
(7, 'Plumbing & Construction', 'mdi-hammer-wrench', '2025-11-12 11:23:39'),
(8, 'Home Cleaning', 'mdi-home-floor-organization', '2025-11-12 11:23:39'),
(9, 'Food & Restaurant', 'mdi-silverware-fork-knife', '2025-11-12 11:23:39'),
(10, 'Vehicle & Transport', 'mdi-car', '2025-11-12 11:23:39'),
(11, 'Courier & Delivery', 'mdi-truck-fast', '2025-11-12 11:23:39'),
(12, 'Education & Tutoring', 'mdi-school', '2025-11-12 11:23:39'),
(13, 'Government Service', 'mdi-office-building', '2025-11-12 11:23:39'),
(14, 'Digital & Tech Support', 'mdi-laptop', '2025-11-12 11:23:39'),
(15, 'Beauty & Personal Care', 'mdi-face-woman', '2025-11-12 11:23:39'),
(16, 'Real Estate & Rent', 'mdi-home-city', '2025-11-12 11:23:39'),
(17, 'Pet & Animal Care', 'mdi-paw', '2025-11-12 11:23:39');

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `service_id` int(10) UNSIGNED NOT NULL,
  `message` text DEFAULT NULL,
  `status` enum('pending','accepted','on_route','completed','cancelled') NOT NULL DEFAULT 'pending',
  `location_lat` decimal(10,7) DEFAULT NULL,
  `location_lng` decimal(10,7) DEFAULT NULL,
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_reviews`
--

CREATE TABLE `service_reviews` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `service_id` int(10) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','service_admin','manager','owner') NOT NULL DEFAULT 'user',
  `phone` varchar(30) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `nid_number` varchar(100) DEFAULT NULL,
  `profile_img` varchar(255) DEFAULT NULL,
  `lat` decimal(10,7) DEFAULT NULL,
  `lng` decimal(10,7) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `is_email_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `role`, `phone`, `address`, `nid_number`, `profile_img`, `lat`, `lng`, `bio`, `is_email_verified`, `created_at`) VALUES
(1, 'System Owner', 'owner@msap.local', '04aeeb2222dcd4c8026fc3ae138bf0d072a18bf9b832ad93fffdbe18140d973d', 'owner', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-11-12 11:23:39'),
(2, 'Default Manager', 'manager@msap.local', '7b7686ab7dddaa1566a4922a9f8f964eb2d0b2fd193648e4fd7c0a81a708164b', 'manager', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-11-12 11:23:39'),
(3, 'Sample Provider', 'provider1@msap.local', '01d1f6f92732246be2ce4bb39cda142e0866e72bfb8974251ffb44cf542cf05e', 'service_admin', '01700000001', 'Dhaka, Bangladesh', 'NID123456', NULL, NULL, NULL, 'Sample service provider', 1, '2025-11-12 11:23:39'),
(4, 'Md Mostakim', 'mostakim@gmail.com', 'bb0cb0142862555c57effeb7a85f0cdf391dd11ba30ecda5a4faac8a4dbec70e', 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-11-12 19:28:08'),
(5, 'Mostakim', 'mdmostakimhossen0176@gmail.com', 'bb0cb0142862555c57effeb7a85f0cdf391dd11ba30ecda5a4faac8a4dbec70e', 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-11-13 07:18:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `manager_activity_log`
--
ALTER TABLE `manager_activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `manager_id` (`manager_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_id` (`provider_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `fk_services_approvedby` (`approved_by`),
  ADD KEY `idx_services_lat_lng` (`lat`,`lng`);

--
-- Indexes for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_category_name` (`category_name`);

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `service_reviews`
--
ALTER TABLE `service_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_id` (`service_id`);

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
-- AUTO_INCREMENT for table `manager_activity_log`
--
ALTER TABLE `manager_activity_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_reviews`
--
ALTER TABLE `service_reviews`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `manager_activity_log`
--
ALTER TABLE `manager_activity_log`
  ADD CONSTRAINT `fk_manager_log_manager` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `fk_services_approvedby` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_services_category` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_services_provider` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD CONSTRAINT `fk_requests_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_requests_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `service_reviews`
--
ALTER TABLE `service_reviews`
  ADD CONSTRAINT `fk_reviews_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
