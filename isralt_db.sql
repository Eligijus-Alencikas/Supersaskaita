-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 18, 2024 at 05:31 PM
-- Server version: 10.6.15-MariaDB-log
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `isralt_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bill_1`
--

CREATE TABLE `bill_1` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `doc_name` varchar(255) NOT NULL,
  `doc_num` varchar(255) NOT NULL,
  `customer` varchar(255) NOT NULL,
  `sender` varchar(255) NOT NULL,
  `date_of_issue` date NOT NULL,
  `PVM` int(11) NOT NULL,
  `doc_img` longblob NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goods_and_services`
--

CREATE TABLE `goods_and_services` (
  `id` int(11) NOT NULL,
  `bill_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL,
  `price` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `listings`
--

CREATE TABLE `listings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `val_1` varchar(255) NOT NULL,
  `val_2` varchar(255) NOT NULL,
  `val_3` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='listings';

--
-- Dumping data for table `listings`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `confirmation_code` varchar(255) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `creation_time` datetime NOT NULL DEFAULT current_timestamp(),
  `change_password_code` varchar(255) DEFAULT NULL,
  `password_code_creation_time` datetime DEFAULT NULL,
  `password_code_used` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--


--
-- Indexes for dumped tables
--

--
-- Indexes for table `bill_1`
--
ALTER TABLE `bill_1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `goods_and_services`
--
ALTER TABLE `goods_and_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `listings`
--
ALTER TABLE `listings`
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
-- AUTO_INCREMENT for table `bill_1`
--
ALTER TABLE `bill_1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `goods_and_services`
--
ALTER TABLE `goods_and_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `listings`
--
ALTER TABLE `listings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
