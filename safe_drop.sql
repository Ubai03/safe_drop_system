-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 30, 2025 at 01:39 AM
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
-- Database: `safe_drop`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_log`
--

CREATE TABLE `access_log` (
  `access_id` int(11) NOT NULL,
  `recipient_id` int(11) DEFAULT NULL,
  `attempted_at` datetime DEFAULT current_timestamp(),
  `attempt_remaining` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `access_log`
--

INSERT INTO `access_log` (`access_id`, `recipient_id`, `attempted_at`, `attempt_remaining`) VALUES
(22, 23, '2025-12-05 12:45:27', 0),
(23, 23, '2025-12-05 12:45:34', 2),
(24, 23, '2025-12-05 12:45:34', 0),
(25, 23, '2025-12-05 12:45:46', 1),
(26, 23, '2025-12-05 12:46:01', 0),
(27, 24, '2025-12-05 14:38:30', 0),
(28, 24, '2025-12-05 14:49:26', 2),
(29, 24, '2025-12-05 14:49:26', 0),
(30, 24, '2025-12-05 14:49:40', 1),
(31, 24, '2025-12-05 14:49:50', 0),
(32, 25, '2025-12-05 16:38:10', 0),
(33, 25, '2025-12-05 16:38:26', 2),
(34, 25, '2025-12-05 16:38:26', 0),
(35, 25, '2025-12-05 16:38:35', 1),
(36, 25, '2025-12-05 16:38:47', 0),
(37, 26, '2025-12-11 09:31:08', 0),
(38, 27, '2025-12-22 08:24:37', 0),
(39, 28, '2025-12-22 11:09:04', 0),
(40, 25, '2025-12-22 11:13:56', 0),
(41, 29, '2025-12-22 11:45:10', 0),
(42, 29, '2025-12-22 11:45:23', 2),
(43, 29, '2025-12-22 11:45:23', 0),
(44, 29, '2025-12-22 11:45:29', 1),
(45, 29, '2025-12-22 11:45:51', 0),
(46, 30, '2025-12-22 11:50:12', 0),
(47, 30, '2025-12-22 11:50:18', 2),
(48, 30, '2025-12-22 11:50:18', 0),
(49, 30, '2025-12-22 11:50:32', 0),
(50, 30, '2025-12-22 11:50:42', 2),
(51, 30, '2025-12-22 11:50:42', 0),
(52, 30, '2025-12-22 11:50:47', 1),
(53, 30, '2025-12-22 11:50:59', 0),
(54, 29, '2025-12-22 11:52:00', 0),
(55, 31, '2025-12-22 17:02:45', 0),
(56, 32, '2025-12-23 14:29:54', 0),
(57, 32, '2025-12-23 14:46:16', 0),
(58, 34, '2025-12-29 12:42:54', 0);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `noti_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `content` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`noti_id`, `recipient_id`, `content`, `created_at`, `status`) VALUES
(6, 23, '⚠️ Recipient ID 23 entered incorrect password. Awaiting admin approval for retry.', '2025-12-05 12:45:27', ''),
(7, 24, '⚠️ Recipient ID 24 entered incorrect password. Awaiting admin approval for retry.', '2025-12-05 14:38:30', ''),
(8, 25, '⚠️ Recipient ID 25 entered incorrect password. Awaiting admin approval for retry.', '2025-12-05 16:38:10', ''),
(9, 29, '⚠️ Recipient ID 29 entered incorrect password. Awaiting admin approval for retry.', '2025-12-22 11:45:10', ''),
(10, 30, '⚠️ Recipient ID 30 entered incorrect password. Awaiting admin approval for retry.', '2025-12-22 11:50:12', ''),
(11, 30, '⚠️ Recipient ID 30 has used all 2 attempts. Admin approval required again.', '2025-12-22 11:50:32', '');

-- --------------------------------------------------------

--
-- Table structure for table `parcel_log`
--

CREATE TABLE `parcel_log` (
  `log_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `status` enum('Delivery','Delivered') NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parcel_log`
--

INSERT INTO `parcel_log` (`log_id`, `recipient_id`, `status`, `updated_at`) VALUES
(178, 23, 'Delivered', '2025-12-05 12:46:01'),
(179, 24, 'Delivered', '2025-12-05 14:49:50'),
(180, 25, 'Delivered', '2025-12-05 16:38:47'),
(181, 26, 'Delivered', '2025-12-22 11:51:00'),
(182, 27, 'Delivered', '2025-12-22 08:24:37'),
(183, 28, 'Delivered', '2025-12-22 11:09:04'),
(184, 29, 'Delivered', '2025-12-29 12:42:55'),
(185, 30, 'Delivered', '2025-12-22 11:50:59'),
(186, 31, 'Delivered', '2025-12-22 17:02:45'),
(189, 32, 'Delivered', '2025-12-23 14:46:16'),
(190, 34, 'Delivered', '2025-12-29 12:42:54');

-- --------------------------------------------------------

--
-- Table structure for table `qr_code`
--

CREATE TABLE `qr_code` (
  `qr_id` int(11) NOT NULL,
  `recipient_id` int(11) DEFAULT NULL,
  `qr_value` varchar(255) NOT NULL,
  `generated_at` datetime DEFAULT current_timestamp(),
  `duplicate_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `qr_code`
--

INSERT INTO `qr_code` (`qr_id`, `recipient_id`, `qr_value`, `generated_at`, `duplicate_at`) VALUES
(20, 23, 'uploads/qr_codes/qr_23.png', '2025-12-29 12:30:28', NULL),
(21, 24, 'uploads/qr_codes/qr_24.png', '2025-12-29 12:30:28', NULL),
(22, 25, 'uploads/qr_codes/qr_25.png', '2025-12-29 12:30:28', NULL),
(23, 26, 'uploads/qr_codes/qr_26.png', '2025-12-29 12:30:28', NULL),
(24, 27, 'uploads/qr_codes/qr_27.png', '2025-12-29 12:30:28', NULL),
(25, 28, 'uploads/qr_codes/qr_28.png', '2025-12-29 12:30:28', NULL),
(26, 29, 'uploads/qr_codes/qr_29.png', '2025-12-29 12:30:28', NULL),
(27, 30, 'uploads/qr_codes/qr_30.png', '2025-12-29 12:30:28', NULL),
(28, 31, 'uploads/qr_codes/qr_31.png', '2025-12-29 12:30:28', NULL),
(29, 32, 'uploads/qr_codes/qr_32.png', '2025-12-29 12:30:28', NULL),
(30, 33, 'uploads/qr_codes/qr_33.png', '2025-12-29 12:30:29', NULL),
(31, 34, 'uploads/qr_codes/qr_34.png', '2025-12-29 12:31:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `recipient`
--

CREATE TABLE `recipient` (
  `recipient_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_tel` varchar(20) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `attempt_left` int(11) DEFAULT 3
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipient`
--

INSERT INTO `recipient` (`recipient_id`, `name`, `email`, `no_tel`, `location`, `longitude`, `latitude`, `attempt_left`) VALUES
(23, 'SITI', 'siti@gmail.com', '0123456789', 'Kampung Kanchong, 71200 Rantau, Negeri Sembilan', 101.9808201, 2.5967937, 3),
(24, 'Daud', 'daud123@gmail.com', '0185496233', '41, Jalan Limau Besar, Bangsar, 59000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur', 101.6747130, 3.1382630, 3),
(25, 'Iwani izzati', 'iwani@gmail.com', '01165507052', '78-84, Jalan Burhanuddin Helmi 2, Taman Tun Dr Ismail, 60000 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur', 101.6205767, 3.1459448, 3),
(26, 'ALI BIN ABU', 'ali@gmail.com', '0178456123', '448b, Jalan Ddp 1/3a1, Kampung Gaing, 71400 Pedas, Negeri Sembilan', 102.0585545, 2.6201935, 3),
(27, 'Hassan Bin Mohd Abu', 'hassan@gmail.com', '0192822340', 'Jalan Senawang Perdana, Taman Senawang Perdana, 71450 Seremban, Negeri Sembilan', 102.0038398, 2.6543939, 3),
(28, 'Salleh Bin Yacqub', 'salleh@gmail.com', '01125405762', 'Kampung Ayer Hitam, 71400 Pedas, Negeri Sembilan', 102.0585545, 2.6201935, 3),
(29, 'Ismail', 'mail@gmail.com', '01125476587', '27-63, Jalan PS 5/4 Taman Pinggiran Senawang, 71450 Seremban, Negeri Sembila', 101.9614591, 2.5932885, 3),
(30, 'Ramlah', 'ramlah@gmail.com', '01386998522', '2441, Jln KSNS 8, Kampung Gaing, 71400 Pedas, Negeri Sembilan', 102.0585545, 2.6201935, 3),
(31, 'Rokiah ', 'rokiah@gmail.com', '0126447842', '424, Jalan Sri Mawar 4, Taman Sri Mawar, 70450 Seremban, Negeri Sembilan', 101.9948053, 2.6971931, 3),
(32, 'nik', 'nik11@gmail.com', '0178452169', 'Taman Pinggiran Senawang, 71450 Seremban, Negeri Sembilan', 102.0031410, 2.6557617, 3),
(33, 'Samad', 'samad@gmail.com', '0127405762', 'SK, Taman Seremban Jaya, 70450 Seremban, Negeri Sembilan', 101.9450430, 2.6776463, 3),
(34, 'aidil', 'aidil@gmail.com', '0112822344', '27, Jalan PS 5/4 Taman Pinggiran Senawang, 71450 Seremban, Negeri Sembila', 101.9614591, 2.5932885, 3);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_controller`
--

CREATE TABLE `tbl_controller` (
  `parcel_long` decimal(10,7) NOT NULL,
  `parcel_lat` decimal(10,7) NOT NULL,
  `user_verify` tinyint(1) DEFAULT 0,
  `geofence_status` tinyint(1) DEFAULT 0,
  `status` varchar(50) DEFAULT NULL,
  `status_vibrate` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_controller`
--

INSERT INTO `tbl_controller` (`parcel_long`, `parcel_lat`, `user_verify`, `geofence_status`, `status`, `status_vibrate`) VALUES
(0.0000000, 0.0000000, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tracking_links`
--

CREATE TABLE `tracking_links` (
  `track_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `token` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tracking_links`
--

INSERT INTO `tracking_links` (`track_id`, `recipient_id`, `token`, `created_at`) VALUES
(22, 23, '22a9c6e171e5ae71ffc4c3cb17149a29', '2025-12-05 12:44:15'),
(23, 24, '86fc5eed8769c0eda77794e43852b10a', '2025-12-05 14:37:07'),
(24, 25, '54c65870340b4c08413e2e91bd20e7af', '2025-12-05 16:34:56'),
(25, 26, 'dc1f2eb78bdb3256d6f67199a25d66c2', '2025-12-11 09:24:34'),
(26, 27, 'a2344b1f8c90c3b6bf44623b1d9b52bc', '2025-12-19 15:43:19'),
(27, 27, '5a3f042e7a934c98283346eee1bfe56f', '2025-12-22 08:22:17'),
(28, 28, '0d00e95a9a042d7734e0308d2c1762b6', '2025-12-22 11:05:59'),
(29, 26, '79f886d5e2b20f2325a19751223b6863', '2025-12-22 11:39:25'),
(30, 29, '8f2b38e1a8e442992512678cec2152cb', '2025-12-22 11:43:28'),
(31, 30, '214697ea0424d5fbef10ef25400d7d41', '2025-12-22 11:47:05'),
(32, 29, 'd63c341a155fe09e4aa22c0490d1e943', '2025-12-22 11:47:53'),
(33, 29, '54cb09be18f0babe615e39d9a8c7acfd', '2025-12-22 11:51:25'),
(34, 31, '1219fa745c5fa9cbd4df1e80001b29d1', '2025-12-22 17:00:33'),
(35, 32, '0deac366b5e67863c7fccb2b41205dab', '2025-12-23 14:27:59'),
(36, 33, 'ab4313f092e1f252d18897d5915aa651', '2025-12-23 14:42:36'),
(37, 32, 'e4f63af9045f4bcc3e25d1ac27f6beb7', '2025-12-23 14:45:00'),
(38, 32, '7466101e59803f87fd42225d4d8414d1', '2025-12-23 14:45:28'),
(39, 34, 'e4e1cc6f958b9b7b8c19d7de641d6297', '2025-12-29 12:31:28');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `name`, `password`) VALUES
(1, 'admin', 'ADMIN', '$2y$10$4XGddYowIR1XRoMsND6HBuu3hk8SmiPSqhjWFQVUG7czWZ1bV9tVi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_log`
--
ALTER TABLE `access_log`
  ADD PRIMARY KEY (`access_id`),
  ADD KEY `recipient_id` (`recipient_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`noti_id`),
  ADD KEY `fk_notification_recipient` (`recipient_id`);

--
-- Indexes for table `parcel_log`
--
ALTER TABLE `parcel_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `recipient_id` (`recipient_id`);

--
-- Indexes for table `qr_code`
--
ALTER TABLE `qr_code`
  ADD PRIMARY KEY (`qr_id`),
  ADD KEY `fk_qr_recipient` (`recipient_id`);

--
-- Indexes for table `recipient`
--
ALTER TABLE `recipient`
  ADD PRIMARY KEY (`recipient_id`);

--
-- Indexes for table `tbl_controller`
--
ALTER TABLE `tbl_controller`
  ADD PRIMARY KEY (`parcel_long`);

--
-- Indexes for table `tracking_links`
--
ALTER TABLE `tracking_links`
  ADD PRIMARY KEY (`track_id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `recipient_id` (`recipient_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_log`
--
ALTER TABLE `access_log`
  MODIFY `access_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `noti_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `parcel_log`
--
ALTER TABLE `parcel_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191;

--
-- AUTO_INCREMENT for table `qr_code`
--
ALTER TABLE `qr_code`
  MODIFY `qr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `recipient`
--
ALTER TABLE `recipient`
  MODIFY `recipient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `tracking_links`
--
ALTER TABLE `tracking_links`
  MODIFY `track_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `access_log`
--
ALTER TABLE `access_log`
  ADD CONSTRAINT `access_log_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `recipient` (`recipient_id`);

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `fk_notification_recipient` FOREIGN KEY (`recipient_id`) REFERENCES `recipient` (`recipient_id`) ON DELETE CASCADE;

--
-- Constraints for table `qr_code`
--
ALTER TABLE `qr_code`
  ADD CONSTRAINT `fk_qr_recipient` FOREIGN KEY (`recipient_id`) REFERENCES `recipient` (`recipient_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tracking_links`
--
ALTER TABLE `tracking_links`
  ADD CONSTRAINT `tracking_links_ibfk_1` FOREIGN KEY (`recipient_id`) REFERENCES `recipient` (`recipient_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
