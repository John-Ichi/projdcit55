-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2025 at 08:43 AM
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
(7, 'John Ichiro Mananquil', 'Male', 'Naic, Cavite', '00000001', '2025-06-12', '2025-06-13', '2035-06-13', 'Valid'),
(8, 'Ceejay Cervantes', 'Male', 'Naic, Cavite', '00000002', '2025-06-13', NULL, '2030-06-13', 'Valid');

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
(1, '00000001', '$2y$10$lY99966tmV/mpmCxbUSuF.pBCUm668sYw.y9ZYNZFK8CdnK.fKMkW');

-- --------------------------------------------------------

--
-- Table structure for table `tbviolations`
--

CREATE TABLE `tbviolations` (
  `violationId` int(11) NOT NULL,
  `licenseSN` int(11) NOT NULL,
  `violationCommitted` enum('Moving Violation','Non-moving Violation') NOT NULL,
  `penaltyAlloted` enum('Monetary Fine','License Suspension','License Revocation') NOT NULL,
  `settlementDeadline` date NOT NULL,
  `resolved` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

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
  ADD KEY `licenseSerialNumber` (`licenseSN`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblicense`
--
ALTER TABLE `tblicense`
  MODIFY `serialNumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tblogininfo`
--
ALTER TABLE `tblogininfo`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbviolations`
--
ALTER TABLE `tbviolations`
  MODIFY `violationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  ADD CONSTRAINT `tbviolations_ibfk_1` FOREIGN KEY (`licenseSN`) REFERENCES `tblicense` (`serialNumber`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
