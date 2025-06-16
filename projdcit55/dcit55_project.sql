-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2025 at 12:34 PM
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
-- Database: `dcit55_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbadmin`
--

CREATE TABLE `tbadmin` (
  `id` int(11) NOT NULL,
  `admin` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbadmin`
--

INSERT INTO `tbadmin` (`id`, `admin`, `password`) VALUES
(1, 'admin', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `tblicense`
--

CREATE TABLE `tblicense` (
  `serialNumber` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `sex` enum('Male','Female') NOT NULL,
  `address` varchar(100) NOT NULL,
  `licenseNumber` varchar(8) NOT NULL,
  `dateRegistered` date NOT NULL DEFAULT current_timestamp(),
  `dateRenewed` date DEFAULT NULL,
  `expirationDate` date NOT NULL,
  `status` enum('Valid','Expired','Suspended','Revoked') NOT NULL DEFAULT 'Valid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblicense`
--

INSERT INTO `tblicense` (`serialNumber`, `name`, `sex`, `address`, `licenseNumber`, `dateRegistered`, `dateRenewed`, `expirationDate`, `status`) VALUES
(11, 'John Ichiro Mananquil', 'Female', 'Naic, Cavite', '00000001', '2025-06-16', '2025-06-16', '2035-06-16', 'Valid'),
(12, 'Ceejay Cervantes', 'Male', 'Silang, Cavite', '00000002', '2025-06-16', NULL, '2030-06-16', 'Suspended'),
(13, 'Dummy License', 'Female', 'Tanza, Cavite', '00000003', '2025-06-16', NULL, '2030-06-16', 'Revoked');

-- --------------------------------------------------------

--
-- Table structure for table `tblogininfo`
--

CREATE TABLE `tblogininfo` (
  `userId` int(11) NOT NULL,
  `licenseNumber` varchar(8) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbviolations`
--

CREATE TABLE `tbviolations` (
  `violationId` int(11) NOT NULL,
  `licenseSN` int(11) NOT NULL,
  `licenseNumber` varchar(8) NOT NULL,
  `violationCommitted` enum('Moving Violation','Non-moving Violation') NOT NULL,
  `penaltyAlloted` enum('Monetary Fine','License Suspension','License Revocation') NOT NULL,
  `settlementDeadline` date NOT NULL,
  `resolved` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbadmin`
--
ALTER TABLE `tbadmin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblicense`
--
ALTER TABLE `tblicense`
  ADD PRIMARY KEY (`serialNumber`),
  ADD UNIQUE KEY `licenseNumber` (`licenseNumber`);

--
-- Indexes for table `tblogininfo`
--
ALTER TABLE `tblogininfo`
  ADD PRIMARY KEY (`userId`),
  ADD KEY `licenseNumber` (`licenseNumber`);

--
-- Indexes for table `tbviolations`
--
ALTER TABLE `tbviolations`
  ADD PRIMARY KEY (`violationId`),
  ADD KEY `licenseSerialNumber` (`licenseSN`),
  ADD KEY `licenseNumber` (`licenseNumber`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbadmin`
--
ALTER TABLE `tbadmin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblicense`
--
ALTER TABLE `tblicense`
  MODIFY `serialNumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tblogininfo`
--
ALTER TABLE `tblogininfo`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbviolations`
--
ALTER TABLE `tbviolations`
  MODIFY `violationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblogininfo`
--
ALTER TABLE `tblogininfo`
  ADD CONSTRAINT `tblogininfo_ibfk_1` FOREIGN KEY (`licenseNumber`) REFERENCES `tblicense` (`licenseNumber`);

--
-- Constraints for table `tbviolations`
--
ALTER TABLE `tbviolations`
  ADD CONSTRAINT `tbviolations_ibfk_1` FOREIGN KEY (`licenseSN`) REFERENCES `tblicense` (`serialNumber`),
  ADD CONSTRAINT `tbviolations_ibfk_2` FOREIGN KEY (`licenseNumber`) REFERENCES `tblicense` (`licenseNumber`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
