-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: 10.169.0.121
-- Generation Time: Aug 11, 2017 at 11:09 AM
-- Server version: 5.7.14
-- PHP Version: 5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shinyide2_lottery`
--

-- --------------------------------------------------------

--
-- Table structure for table `access`
--

CREATE TABLE IF NOT EXISTS `access` (
  `ident` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` char(36) NOT NULL,
  `created_when` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end_dated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggers `access`
--
DELIMITER $$
CREATE TRIGGER `insert_access` BEFORE INSERT ON `access`
 FOR EACH ROW BEGIN
        SET new.token = uuid();
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `draw_history`
--

CREATE TABLE IF NOT EXISTS `draw_history` (
  `ident` int(11) NOT NULL,
  `draw` int(11) NOT NULL,
  `draw_date` date NOT NULL,
  `last_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `logger`
--

CREATE TABLE IF NOT EXISTS `logger` (
  `ident` int(11) NOT NULL,
  `seqnum` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lottery_draws`
--

CREATE TABLE IF NOT EXISTS `lottery_draws` (
  `ident` int(11) NOT NULL,
  `description` varchar(64) NOT NULL,
  `draw` int(11) NOT NULL,
  `numbers` int(11) NOT NULL,
  `upper_number` int(11) NOT NULL,
  `numbers_tag` varchar(32) NOT NULL,
  `specials` int(11) NOT NULL,
  `upper_special` int(11) NOT NULL,
  `specials_tag` varchar(32) NOT NULL,
  `is_bonus` tinyint(1) NOT NULL,
  `base_url` varchar(512) NOT NULL,
  `last_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `number_usage`
--

CREATE TABLE IF NOT EXISTS `number_usage` (
  `ident` int(11) NOT NULL,
  `draw` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `is_special` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `request_history`
--

CREATE TABLE IF NOT EXISTS `request_history` (
  `accessed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `remote` varchar(256) NOT NULL,
  `access_ident` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access`
--
ALTER TABLE `access`
  ADD PRIMARY KEY (`ident`) USING BTREE;

--
-- Indexes for table `draw_history`
--
ALTER TABLE `draw_history`
  ADD PRIMARY KEY (`ident`,`draw`) USING BTREE;

--
-- Indexes for table `logger`
--
ALTER TABLE `logger`
  ADD UNIQUE KEY `seqnum` (`seqnum`),
  ADD UNIQUE KEY `ident_seqnum` (`ident`,`seqnum`) USING BTREE;

--
-- Indexes for table `lottery_draws`
--
ALTER TABLE `lottery_draws`
  ADD PRIMARY KEY (`ident`);

--
-- Indexes for table `number_usage`
--
ALTER TABLE `number_usage`
  ADD PRIMARY KEY (`ident`,`draw`,`number`,`is_special`) USING BTREE;

--
-- Indexes for table `request_history`
--
ALTER TABLE `request_history`
  ADD KEY `request_history_idx1` (`access_ident`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access`
--
ALTER TABLE `access`
  MODIFY `ident` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `logger`
--
ALTER TABLE `logger`
  MODIFY `seqnum` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lottery_draws`
--
ALTER TABLE `lottery_draws`
  MODIFY `ident` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
