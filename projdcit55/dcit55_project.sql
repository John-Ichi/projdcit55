-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2025 at 02:24 AM
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
(24, 'Aira Dominique Mananquil', 'Female', '21 Diosomito Subd., Ibayo Silangan, Naic, Cavite', '00000000', '2025-06-24', '2025-06-24', '2030-06-24', 'Valid'),
(25, 'John Ichiro Mananquil', 'Male', 'Tanza, Cavite', '00000001', '2025-06-25', NULL, '2030-06-25', 'Valid'),
(26, 'Ceejay Cervantes', 'Male', 'Sabang, Naic, Cavite', '00000002', '2025-06-25', NULL, '2030-06-25', 'Valid'),
(28, 'Test Overflow', 'Female', 'Maragondon, Cavite', 'TEST0001', '2025-06-25', NULL, '2030-06-25', 'Valid');

-- --------------------------------------------------------

--
-- Table structure for table `tblogininfo`
--

CREATE TABLE `tblogininfo` (
  `userId` int(11) NOT NULL,
  `licenseNumber` varchar(8) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblogininfo`
--

INSERT INTO `tblogininfo` (`userId`, `licenseNumber`, `password`) VALUES
(13, '00000000', '$2y$10$1vmrcP/052tu9Js.yPL3Ve96YWNSgmCkrLbbiJtd7nslXFgEBuFnW');

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
-- Dumping data for table `tbviolations`
--

INSERT INTO `tbviolations` (`violationId`, `licenseSN`, `licenseNumber`, `violationCommitted`, `penaltyAlloted`, `settlementDeadline`, `resolved`) VALUES
(41, 24, '00000000', 'Moving Violation', 'Monetary Fine', '2025-07-02', 1),
(42, 24, '00000000', 'Non-moving Violation', 'Monetary Fine', '2025-07-02', 1),
(43, 24, '00000000', 'Moving Violation', 'Monetary Fine', '2025-07-02', 1),
(44, 24, '00000000', 'Non-moving Violation', 'License Suspension', '2025-06-25', 1),
(46, 24, '00000000', 'Non-moving Violation', 'License Revocation', '2025-07-09', 0),
(47, 24, '00000000', 'Moving Violation', 'License Suspension', '2025-07-02', 0),
(48, 24, '00000000', 'Moving Violation', 'Monetary Fine', '2025-07-02', 0),
(49, 24, '00000000', 'Non-moving Violation', 'Monetary Fine', '2025-07-02', 0);

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
  MODIFY `serialNumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tblogininfo`
--
ALTER TABLE `tblogininfo`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbviolations`
--
ALTER TABLE `tbviolations`
  MODIFY `violationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

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
