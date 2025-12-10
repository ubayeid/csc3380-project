-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2025 at 06:33 AM
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
-- Database: `acm72178`
--

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `MemberNo` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Deposit` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`MemberNo`, `FirstName`, `LastName`, `Deposit`) VALUES
(10000, 'Ray', 'Kemp', 500.00),
(10001, 'John', 'Doe', 750.00),
(10002, 'Selva', 'Kumar', 1000.00);

-- --------------------------------------------------------

--
-- Table structure for table `memberspw`
--

CREATE TABLE `memberspw` (
  `MemberNo` int(11) NOT NULL,
  `UserName` varchar(50) NOT NULL,
  `PassWord` varchar(40) NOT NULL,
  `eMail` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `memberspw`
--

INSERT INTO `memberspw` (`MemberNo`, `UserName`, `PassWord`, `eMail`) VALUES
(10000, 'Ray', '248510136410798c784ba702df249756ad286be4', 'ray@example.com'),
(10001, 'John', 'cbfdac6008f9cab4083784cbd1874f76618d2a97', 'john@example.com'),
(10002, 'Selva', 'c60266a8adad2f8ee67d793b4fd3fd0ffd73cc61', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`MemberNo`);

--
-- Indexes for table `memberspw`
--
ALTER TABLE `memberspw`
  ADD PRIMARY KEY (`MemberNo`),
  ADD UNIQUE KEY `UserName` (`UserName`),
  ADD KEY `UserName_2` (`UserName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `memberspw`
--
ALTER TABLE `memberspw`
  MODIFY `MemberNo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10003;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `members_ibfk_1` FOREIGN KEY (`MemberNo`) REFERENCES `memberspw` (`MemberNo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
