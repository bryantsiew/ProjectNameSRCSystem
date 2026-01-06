-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2026 at 06:51 PM
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
-- Database: `cnbs_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `content` text DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `attachment_id` int(11) NOT NULL,
  `notice_id` int(11) NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `filepath` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`log_id`, `user_id`, `action`, `details`, `ip_address`, `timestamp`) VALUES
(1, NULL, 'UPDATE_BANNER', 'Updated system banner', '::1', '2026-01-05 22:37:49'),
(2, NULL, 'UPDATE_BANNER', 'Updated system banner', '::1', '2026-01-05 22:37:52'),
(3, 7, 'LOGIN', 'User logged in', '::1', '2026-01-05 22:49:56'),
(4, 7, 'LOGIN', 'User logged in', '::1', '2026-01-05 22:55:15'),
(5, 6, 'LOGIN', 'User logged in', '::1', '2026-01-05 22:55:21'),
(6, 6, 'LOGIN', 'User logged in', '::1', '2026-01-05 22:55:29'),
(7, NULL, 'UPDATE_BANNER', 'Updated system banner', '::1', '2026-01-06 00:04:25'),
(8, NULL, 'UPDATE_BANNER', 'Updated system banner', '::1', '2026-01-06 00:04:42'),
(9, NULL, 'UPDATE_BANNER', 'Updated system banner', '::1', '2026-01-06 00:07:59'),
(10, NULL, 'UPDATE_BANNER', 'Updated system banner', '::1', '2026-01-06 00:08:05'),
(11, NULL, 'UPDATE_BANNER', 'Updated system banner', '::1', '2026-01-06 00:11:45');

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE `bookmarks` (
  `bookmark_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `notice_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `color_code` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `notice_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_text` text DEFAULT NULL,
  `status` enum('approved','pending','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `notice_id`, `user_id`, `comment_text`, `status`, `created_at`) VALUES
(18, 13, 6, 'gafgafa', 'pending', '2026-01-06 01:19:01'),
(19, 13, 6, 'adadada', 'pending', '2026-01-06 01:19:11');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `notice_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `priority` enum('High','Medium','Low') DEFAULT 'Low',
  `user_id` int(11) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `details` text DEFAULT NULL,
  `notice_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`notice_id`, `title`, `content`, `category_id`, `priority`, `user_id`, `expiry_date`, `views`, `created_at`, `category`, `date`, `details`, `notice_date`) VALUES
(13, 'NO WATER', 'NO WATER FOR UP TO 3 DAYS STARTING TOMORROW ', NULL, 'High', 6, NULL, 7, '2026-01-05 18:24:21', 'Maintenance', NULL, NULL, '2026-01-05'),
(21, 'REPAIRS', 'POWER WILL BE OUT FOR A BIT', NULL, 'High', NULL, NULL, 0, '2026-01-06 14:08:17', 'Maintenance', NULL, NULL, '2026-01-06');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_id` int(11) NOT NULL,
  `setting_key` varchar(50) DEFAULT NULL,
  `setting_name` varchar(100) DEFAULT NULL,
  `setting_value` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_id`, `setting_key`, `setting_name`, `setting_value`, `description`) VALUES
(1, 'banner_text', NULL, 'beepboop', NULL),
(2, 'banner_status', NULL, 'active', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `ticket_id` int(11) NOT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `issue_text` text NOT NULL,
  `submitted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('resident','manager','admin') NOT NULL DEFAULT 'resident',
  `status` enum('active','disabled') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `unit_block` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role`, `status`, `created_at`, `unit_block`) VALUES
(1, 'testAdmin', 'D09240003@student.unimy.edu.my', '$2y$10$4Yvf8EyQYpsGfBnMgTKK7u7UfUHqZXR1Ncxnq8SIbF/LyFRQkacVC', 'resident', 'active', '2026-01-04 19:40:05', 'A'),
(2, 'tt1mmy', 'ongt5335@gmail.com', '$2y$10$dWw5yZ0C37VbcVst0mkzLOCoybAj1jrVyC5j21DuU2Bd.IDouKonK', 'resident', 'active', '2026-01-04 19:40:48', 'B'),
(6, 'TestAdmin1', 'shafiq@gmail.com', '$2y$10$wJsCPf0Qq0X58KMBxNOZ4OjvgUyD8KIp2ieGapIOTLR6xTPHqnBuW', 'admin', 'active', '2026-01-04 20:04:04', 'A'),
(7, 'TestGuest1', 'timongxj@gmail.com', '$2y$10$zDWaau8kAgjrM2KE30wu5O85..7b1DoG24JZnB5dKDSVsjjwEdV3e', 'resident', 'active', '2026-01-04 20:31:07', 'A'),
(8, 'TestGuest2', 'ongt5334@gmail.com', '$2y$10$IrOWtnQ7jqv9wWt.gXD77u.R0XFStCwaUpQ2wQqh1KaH3wmWXcTra', 'resident', 'active', '2026-01-05 16:49:05', 'A'),
(9, 'admin', '', '$2y$10$86pXv.9YwU.eQp2p.kG7u.zY7GqQG3g8/6K9J0X0yY7X0yY7X0yY7', 'admin', 'active', '2026-01-06 14:30:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_notice_views`
--

CREATE TABLE `user_notice_views` (
  `view_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `notice_id` int(11) DEFAULT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`attachment_id`),
  ADD KEY `notice_id` (`notice_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`bookmark_id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`notice_id`),
  ADD KEY `notice_id` (`notice_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `notice_id` (`notice_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`notice_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD UNIQUE KEY `setting_name` (`setting_name`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`ticket_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_notice_views`
--
ALTER TABLE `user_notice_views`
  ADD PRIMARY KEY (`view_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `notice_id` (`notice_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `attachment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `bookmark_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `notice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_notice_views`
--
ALTER TABLE `user_notice_views`
  MODIFY `view_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attachments`
--
ALTER TABLE `attachments`
  ADD CONSTRAINT `attachments_ibfk_1` FOREIGN KEY (`notice_id`) REFERENCES `notices` (`notice_id`) ON DELETE CASCADE;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `bookmarks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookmarks_ibfk_2` FOREIGN KEY (`notice_id`) REFERENCES `notices` (`notice_id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`notice_id`) REFERENCES `notices` (`notice_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `notices`
--
ALTER TABLE `notices`
  ADD CONSTRAINT `notices_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `notices_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_notice_views`
--
ALTER TABLE `user_notice_views`
  ADD CONSTRAINT `user_notice_views_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_notice_views_ibfk_2` FOREIGN KEY (`notice_id`) REFERENCES `notices` (`notice_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
