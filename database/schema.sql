-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 03, 2026 at 10:21 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;
--
-- Database: `crud_app`
--

-- --------------------------------------------------------
--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
    `admin_id` int(11) NOT NULL,
    `admin_name` varchar(50) NOT NULL,
    `admin_email` varchar(50) NOT NULL,
    `admin_pass` varchar(255) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;
-- --------------------------------------------------------
--
-- Table structure for table `areas`
--

CREATE TABLE `areas` (
    `area_id` int(11) NOT NULL,
    `area_name` varchar(50) NOT NULL,
    `region_id` int(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;
-- --------------------------------------------------------
--
-- Table structure for table `auth_tokens`
--

CREATE TABLE `auth_tokens` (
    `auth_token_id` int(11) NOT NULL,
    `auth_token_jti` varchar(32) NOT NULL,
    `auth_token_created_at` datetime NOT NULL DEFAULT current_timestamp(),
    `auth_token_expires_at` datetime NOT NULL,
    `auth_token_revoked_at` datetime DEFAULT NULL,
    `admin_id` int(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;
-- --------------------------------------------------------
--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
    `branch_id` int(11) NOT NULL,
    `branch_name` varchar(50) NOT NULL,
    `area_id` int(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;
-- --------------------------------------------------------
--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
    `region_id` int(11) NOT NULL,
    `region_name` varchar(50) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;
-- --------------------------------------------------------
--
-- Table structure for table `users`
--

CREATE TABLE `users` (
    `user_id` int(11) NOT NULL,
    `user_email` varchar(50) NOT NULL,
    `user_name` varchar(50) DEFAULT NULL,
    `user_isactive` tinyint(4) DEFAULT 1,
    `branch_id` int(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;
--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
ADD PRIMARY KEY (`admin_id`),
    ADD UNIQUE KEY `admin_email` (`admin_email`);
--
-- Indexes for table `areas`
--
ALTER TABLE `areas`
ADD PRIMARY KEY (`area_id`),
    ADD KEY `fk_region_id` (`region_id`);
--
-- Indexes for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
ADD PRIMARY KEY (`auth_token_id`),
    ADD UNIQUE KEY `auth_token_jti` (`auth_token_jti`),
    ADD KEY `fk_admin_id` (`admin_id`);
--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
ADD PRIMARY KEY (`branch_id`),
    ADD KEY `fk_area_id` (`area_id`);
--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
ADD PRIMARY KEY (`region_id`);
--
-- Indexes for table `users`
--
ALTER TABLE `users`
ADD PRIMARY KEY (`user_id`),
    ADD UNIQUE KEY `user_email` (`user_email`),
    ADD KEY `fk_branch_id` (`branch_id`);
--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 2;
--
-- AUTO_INCREMENT for table `areas`
--
ALTER TABLE `areas`
MODIFY `area_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
MODIFY `auth_token_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
MODIFY `region_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `areas`
--
ALTER TABLE `areas`
ADD CONSTRAINT `fk_region_id` FOREIGN KEY (`region_id`) REFERENCES `regions` (`region_id`);
--
-- Constraints for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
ADD CONSTRAINT `fk_admin_id` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`);
--
-- Constraints for table `branches`
--
ALTER TABLE `branches`
ADD CONSTRAINT `fk_area_id` FOREIGN KEY (`area_id`) REFERENCES `areas` (`area_id`);
--
-- Constraints for table `users`
--
ALTER TABLE `users`
ADD CONSTRAINT `fk_branch_id` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`);
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;
