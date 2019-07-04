-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 03, 2019 at 09:00 PM
-- Server version: 5.7.26-0ubuntu0.16.04.1
-- PHP Version: 5.6.37-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_csmgr`
--

-- --------------------------------------------------------

--
-- Table structure for table `db_configure_shortcut`
--

CREATE TABLE `db_configure_shortcut` (
  `ID` int(11) NOT NULL,
  `sortid` int(11) NOT NULL,
  `icon` char(30) COLLATE utf8_unicode_ci NOT NULL,
  `style` char(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'btn-default',
  `ruleid` mediumint(9) NOT NULL,
  `description` char(128) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `db_configure_shortcut`
--

INSERT INTO `db_configure_shortcut` (`ID`, `sortid`, `icon`, `style`, `ruleid`, `description`) VALUES
(1, 0, 'fa fa-info-circle', 'btn-default', 1000, ''),
(2, 1, 'fa fa-info-circle', 'btn-default', 1001, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `db_configure_shortcut`
--
ALTER TABLE `db_configure_shortcut`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `SORTID` (`sortid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `db_configure_shortcut`
--
ALTER TABLE `db_configure_shortcut`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
