-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2024 at 08:37 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `open_house`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_checkopen`
--

CREATE TABLE `tb_checkopen` (
  `preorder_id` int(11) NOT NULL,
  `school` varchar(100) DEFAULT NULL,
  `member_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone_number` varchar(10) DEFAULT NULL,
  `date_id` int(4) DEFAULT NULL,
  `quandity` int(4) DEFAULT NULL,
  `car_service` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_date`
--

CREATE TABLE `tb_date` (
  `date_id` int(11) NOT NULL,
  `date_open` date DEFAULT NULL,
  `max_value` int(4) DEFAULT NULL,
  `date_round` enum('เช้า','บ่าย') DEFAULT NULL,
  `start_time` varchar(100) DEFAULT NULL,
  `end_time` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `member_id` int(11) NOT NULL,
  `member_fname` varchar(100) DEFAULT NULL,
  `member_lname` varchar(100) DEFAULT NULL,
  `member_allow` enum('1','2') DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `member_code` varchar(100) DEFAULT NULL,
  `type` enum('sp_admin','admin') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_checkopen`
--
ALTER TABLE `tb_checkopen`
  ADD PRIMARY KEY (`preorder_id`),
  ADD KEY `date_id` (`date_id`);

--
-- Indexes for table `tb_date`
--
ALTER TABLE `tb_date`
  ADD PRIMARY KEY (`date_id`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`member_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_checkopen`
--
ALTER TABLE `tb_checkopen`
  MODIFY `preorder_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_date`
--
ALTER TABLE `tb_date`
  MODIFY `date_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_checkopen`
--
ALTER TABLE `tb_checkopen`
  ADD CONSTRAINT `tb_checkopen_ibfk_1` FOREIGN KEY (`date_id`) REFERENCES `tb_date` (`date_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
