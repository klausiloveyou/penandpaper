-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 10, 2018 at 10:21 PM
-- Server version: 5.7.24-0ubuntu0.16.04.1
-- PHP Version: 7.1.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `penandpaper`
--

-- --------------------------------------------------------

--
-- Table structure for table `USER`
--

CREATE TABLE `USER` (
  `ID` int(16) NOT NULL,
  `NAME` varchar(64) NOT NULL,
  `PASSWORD` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `USER`
-- DEV admin PW is `pnpdevpass`
--

INSERT INTO `USER` (`ID`, `NAME`, `PASSWORD`) VALUES
(1, 'admin', '$2y$10$Iuuk3RgYh5JzMPa2rbA9mOofL1Dl1ginTjTEvJkGh7ThcZlDMtnEC');

-- --------------------------------------------------------

--
-- Table structure for table `USERCHAR`
--

CREATE TABLE `USERCHAR` (
  `UUID` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `USER` int(16) NOT NULL,
  `NAME` varchar(64) NOT NULL,
  `RACE` varchar(64) NOT NULL,
  `CAREERPATH` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `USERCHARDETAILS`
--

CREATE TABLE `USERCHARDETAILS` (
  `UUID` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `USERCHAR` timestamp(6) NOT NULL DEFAULT '0000-00-00 00:00:00.000000',
  `GENDER` varchar(64) NOT NULL,
  `BIRTHDATE` date NOT NULL,
  `NATIONALITY` varchar(64) NOT NULL,
  `HEIGHT` varchar(64) NOT NULL,
  `EYES` varchar(64) NOT NULL,
  `AGE` int(4) NOT NULL,
  `BIRTHPLACE` varchar(128) NOT NULL,
  `RELIGION` varchar(128) NOT NULL,
  `WEIGHT` varchar(64) NOT NULL,
  `HAIR` varchar(64) NOT NULL,
  `DISTMARKS` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `USER`
--
ALTER TABLE `USER`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `name` (`NAME`),
  ADD UNIQUE KEY `id` (`ID`);

--
-- Indexes for table `USERCHAR`
--
ALTER TABLE `USERCHAR`
  ADD PRIMARY KEY (`UUID`),
  ADD UNIQUE KEY `user` (`USER`);

--
-- Indexes for table `USERCHARDETAILS`
--
ALTER TABLE `USERCHARDETAILS`
  ADD PRIMARY KEY (`UUID`),
  ADD UNIQUE KEY `UUID` (`UUID`),
  ADD KEY `FK_PersonalDetails` (`USERCHAR`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `USER`
--
ALTER TABLE `USER`
  MODIFY `ID` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `USERCHAR`
--
ALTER TABLE `USERCHAR`
  ADD CONSTRAINT `FK_UserCharacter` FOREIGN KEY (`USER`) REFERENCES `USER` (`ID`);

--
-- Constraints for table `USERCHARDETAILS`
--
ALTER TABLE `USERCHARDETAILS`
  ADD CONSTRAINT `FK_PersonalDetails` FOREIGN KEY (`USERCHAR`) REFERENCES `USERCHAR` (`UUID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
