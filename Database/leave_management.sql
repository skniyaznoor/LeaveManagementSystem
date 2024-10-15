-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 15, 2024 at 09:44 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `leave_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `leave_request_id` int(11) NOT NULL,
  `comment_text` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `leave_request_id`, `comment_text`) VALUES
(11, 22, 'Enjoy the Wedding. Give my regards'),
(21, 30, 'You got to work'),
(23, 28, 'Get well soon'),
(25, 29, 'Get well soon'),
(27, 31, 'No. Can\'t give you'),
(29, 32, 'Ok. Go can go'),
(30, 34, 'No.  I won\'t give you .... OK'),
(31, 35, 'What meeting. Need to talk'),
(32, 37, 'Enjoy'),
(33, 39, 'Visit what??'),
(34, 40, 'Hope, you are fine');

-- --------------------------------------------------------

--
-- Table structure for table `leave_balances`
--

CREATE TABLE `leave_balances` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vacation_balance` int(11) DEFAULT 10,
  `sick_balance` int(11) DEFAULT 10,
  `personal_balance` int(11) DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `leave_balances`
--

INSERT INTO `leave_balances` (`id`, `user_id`, `vacation_balance`, `sick_balance`, `personal_balance`) VALUES
(1, 1, 5, 9, 9),
(2, 2, 10, 8, 1),
(3, 3, 2, 9, 10),
(4, 4, 10, 10, 10),
(5, 5, 10, 10, 10),
(6, 6, 10, 10, 10),
(7, 7, 10, 9, 10),
(8, 8, 10, 10, 10),
(9, 9, 10, 10, 6),
(10, 10, 10, 9, 10);

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `leave_type` enum('vacation','sick','personal') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `manager_comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `user_id`, `leave_type`, `start_date`, `end_date`, `reason`, `status`, `manager_comment`) VALUES
(22, 1, 'vacation', '2024-10-28', '2024-11-01', 'Going to Sikkim to attained a friend\'s wedding', 'approved', NULL),
(28, 2, 'sick', '2024-10-14', '2024-10-14', 'Eye Infection', 'approved', NULL),
(29, 2, 'sick', '2024-10-15', '2024-10-15', 'Appointment', 'approved', NULL),
(30, 1, 'sick', '2024-10-14', '2024-10-14', 'Cold', 'rejected', NULL),
(31, 2, 'vacation', '2024-10-19', '2024-10-26', 'Himalaya ', 'rejected', NULL),
(32, 1, 'sick', '2024-10-25', '2024-10-25', 'Appointment', 'approved', NULL),
(33, 2, 'personal', '2024-11-01', '2024-11-04', 'qqqqqqq', 'approved', NULL),
(34, 2, 'personal', '2024-11-25', '2024-11-29', 'aaaaaaa', 'approved', NULL),
(35, 1, 'personal', '2024-12-21', '2024-12-21', 'Meeting', 'approved', NULL),
(36, 1, 'personal', '2024-12-25', '2024-12-26', 'Friend\'s Wedding', 'pending', NULL),
(37, 3, 'vacation', '2024-12-27', '2025-01-03', 'Family Vacation', 'approved', NULL),
(38, 8, 'sick', '2024-10-23', '2024-10-23', 'Fever', 'pending', NULL),
(39, 9, 'personal', '2024-11-15', '2024-11-18', 'Visit', 'approved', NULL),
(40, 10, 'sick', '2024-11-01', '2024-11-01', 'Cold', 'approved', NULL),
(41, 6, 'vacation', '2024-11-04', '2024-11-08', 'Goa Trip', 'pending', NULL),
(42, 7, 'sick', '2024-10-14', '2024-10-14', 'Fever', 'approved', NULL),
(43, 7, 'personal', '2024-10-25', '2024-10-25', 'Appointment', 'pending', NULL),
(44, 9, 'sick', '2024-11-01', '2024-11-01', 'Cold & Fever', 'pending', NULL),
(45, 6, 'personal', '2024-11-07', '2024-11-07', 'Going to attend my friend\'s wedding ', 'pending', NULL),
(46, 3, 'sick', '2024-10-14', '2024-10-14', 'Cold and Fever', 'approved', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'employee'),
(2, 'manager');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `leave_balance` int(11) DEFAULT 30
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role_id`, `leave_balance`) VALUES
(1, 'skniyaznoor', '$2b$12$fdf3UNUdqyjgF50ztesJ/OTi.QXb/aFWIlHWTB/fD4J8pjbdCu/Fa', 'skniyaznoor23@gmail.com', 1, 23),
(2, 'jyotiprakash', '$2b$12$fdf3UNUdqyjgF50ztesJ/OTi.QXb/aFWIlHWTB/fD4J8pjbdCu/Fa', 'jyotiprakash@gmail.com', 1, 24),
(3, 'ayusman', '$2b$12$fdf3UNUdqyjgF50ztesJ/OTi.QXb/aFWIlHWTB/fD4J8pjbdCu/Fa', 'ayusmanparida@gmail.com', 1, 24),
(4, 'subhampradhan', '$2b$12$fdf3UNUdqyjgF50ztesJ/OTi.QXb/aFWIlHWTB/fD4J8pjbdCu/Fa', 'subhampradhan@bourntec.com', 2, 30),
(5, 'vampire', '$2b$12$K3W8yLYykIf5N8TaNOiMAudTIILjIvKrGO.QyJY79emWGkdgJg2PW', 'vampire@gmail.com', 2, NULL),
(6, 'manish', '$2b$12$fdf3UNUdqyjgF50ztesJ/OTi.QXb/aFWIlHWTB/fD4J8pjbdCu/Fa', 'manish@gmail.com', 1, 30),
(7, 'rashmi', '$2b$12$fdf3UNUdqyjgF50ztesJ/OTi.QXb/aFWIlHWTB/fD4J8pjbdCu/Fa', 'rashmi@gmail.com', 1, 30),
(8, 'rohit', '$2b$12$fdf3UNUdqyjgF50ztesJ/OTi.QXb/aFWIlHWTB/fD4J8pjbdCu/Fa', 'rohit@gmail.com', 1, 30),
(9, 'rishav', '$2b$12$fdf3UNUdqyjgF50ztesJ/OTi.QXb/aFWIlHWTB/fD4J8pjbdCu/Fa', 'rishav@gmail.com', 1, 30),
(10, 'gaurav', '$2b$12$fdf3UNUdqyjgF50ztesJ/OTi.QXb/aFWIlHWTB/fD4J8pjbdCu/Fa', 'gaurav@gmail.com', 1, 30);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_request_id` (`leave_request_id`);

--
-- Indexes for table `leave_balances`
--
ALTER TABLE `leave_balances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `leave_balances`
--
ALTER TABLE `leave_balances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`leave_request_id`) REFERENCES `leave_requests` (`id`);

--
-- Constraints for table `leave_balances`
--
ALTER TABLE `leave_balances`
  ADD CONSTRAINT `leave_balances_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
