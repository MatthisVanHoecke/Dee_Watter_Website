-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2021 at 02:07 AM
-- Server version: 5.7.17
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webshopphp`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblarticles`
--

CREATE TABLE `tblarticles` (
  `ArticleID` int(22) NOT NULL,
  `ArticleName` varchar(50) DEFAULT NULL,
  `ArticlePrice` decimal(11,0) DEFAULT NULL,
  `Detailed` decimal(10,0) DEFAULT NULL,
  `ExtraCharacter` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblarticles`
--

INSERT INTO `tblarticles` (`ArticleID`, `ArticleName`, `ArticlePrice`, `Detailed`, `ExtraCharacter`) VALUES
(1, 'Head', '14', '4', '5'),
(2, 'HalfBody', '16', '6', '7'),
(3, 'FullBody', '22', '10', '10');

-- --------------------------------------------------------

--
-- Table structure for table `tblcustomers`
--

CREATE TABLE `tblcustomers` (
  `CustomerID` int(22) NOT NULL,
  `Username` varchar(50) DEFAULT NULL,
  `Street` varchar(50) DEFAULT NULL,
  `PostID` int(4) DEFAULT NULL,
  `Email` varchar(30) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Isadmin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcustomers`
--

INSERT INTO `tblcustomers` (`CustomerID`, `Username`, `Street`, `PostID`, `Email`, `Password`, `Isadmin`) VALUES
(3, '3', NULL, NULL, '3@gmail.com', '33333333ab', 0),
(1, '5', NULL, NULL, '1@gmail.com', 'aaaaaaaa15', 0),
(8, '8', NULL, NULL, '8@gmail.com', '8', 0),
(9, '9', NULL, NULL, '9@gmail.com', '9', 0),
(2, '2', NULL, NULL, '2@gmail.com', '22222222ab', 0),
(18, 'br', NULL, NULL, 'br@gmail.com', '$2y$10$.QMheSa92oSe1EfZrm8NXOAXA7ZWKDHTVi86VSHL7qKM9OBWW8T6O', 0),
(20, 'a', NULL, NULL, 'a@a.com', '$2y$10$ckOJWKXXWBRUgRCQJbGmS./yxEZ5uE/z6DYtJuiFVrksKCViX9JKa', 1),
(4, '4', NULL, NULL, '4@gmail.com', '44444444ab', 0),
(10, '10', NULL, NULL, '10@gmail.com', '10101010ab', 0),
(21, 'Matthis', NULL, NULL, 'Matthis.vanhoecke@gmail.com', '$2y$10$xH9CmDOOcomrJc4MuKlgFuDmENCNW8rzCXovgmo3sCUUZQUqv7y92', 1),
(22, 'b', NULL, NULL, 'b@gmail.com', '$2y$10$9HwF8Lh7/scGm47T1/9T8ejhzaXNgtuxVKzTFQiKDAx1AStPAatVS', 0),
(23, 'am', NULL, NULL, 'brr@', '$2y$10$0eVy7JiHAqkKxlbuR3Dg8OaI1OgV/Q6ghLmzl9NRAkqgIJQN8Gce.', 0),
(24, 'brrr', NULL, NULL, 'brrr@', '$2y$10$fFsLX5d/vScQ9Qx6r2N6ROmOKqZP35Yfj3Bna08wp7hK3pN394WdG', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblorderlines`
--

CREATE TABLE `tblorderlines` (
  `OrderID` int(11) NOT NULL,
  `ArticleID` int(11) NOT NULL,
  `Description` text,
  `File` varchar(255) DEFAULT NULL,
  `Detailed` tinyint(4) NOT NULL DEFAULT '0',
  `ExtraCharacter` tinyint(4) NOT NULL DEFAULT '0',
  `ExtraCharacterAmount` int(11) NOT NULL DEFAULT '0',
  `Discount` decimal(11,0) DEFAULT NULL,
  `PriceByOrder` decimal(11,0) DEFAULT NULL,
  `Status` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblorderlines`
--

INSERT INTO `tblorderlines` (`OrderID`, `ArticleID`, `Description`, `File`, `Detailed`, `ExtraCharacter`, `ExtraCharacterAmount`, `Discount`, `PriceByOrder`, `Status`) VALUES
(74, 2, 'hi', '', 0, 1, 2, NULL, '28', 'In Progress');

-- --------------------------------------------------------

--
-- Table structure for table `tblorders`
--

CREATE TABLE `tblorders` (
  `OrderID` int(55) NOT NULL,
  `CustomerID` int(22) DEFAULT NULL,
  `Date` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblorders`
--

INSERT INTO `tblorders` (`OrderID`, `CustomerID`, `Date`) VALUES
(74, 20, '2021-02-24');

-- --------------------------------------------------------

--
-- Table structure for table `tblpost`
--

CREATE TABLE `tblpost` (
  `PostID` int(22) NOT NULL,
  `Postcode` int(4) DEFAULT NULL,
  `Town` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblarticles`
--
ALTER TABLE `tblarticles`
  ADD PRIMARY KEY (`ArticleID`);

--
-- Indexes for table `tblcustomers`
--
ALTER TABLE `tblcustomers`
  ADD PRIMARY KEY (`CustomerID`);

--
-- Indexes for table `tblorderlines`
--
ALTER TABLE `tblorderlines`
  ADD PRIMARY KEY (`OrderID`,`ArticleID`);

--
-- Indexes for table `tblorders`
--
ALTER TABLE `tblorders`
  ADD PRIMARY KEY (`OrderID`);

--
-- Indexes for table `tblpost`
--
ALTER TABLE `tblpost`
  ADD PRIMARY KEY (`PostID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblarticles`
--
ALTER TABLE `tblarticles`
  MODIFY `ArticleID` int(22) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tblcustomers`
--
ALTER TABLE `tblcustomers`
  MODIFY `CustomerID` int(22) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `tblorders`
--
ALTER TABLE `tblorders`
  MODIFY `OrderID` int(55) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;
--
-- AUTO_INCREMENT for table `tblpost`
--
ALTER TABLE `tblpost`
  MODIFY `PostID` int(22) NOT NULL AUTO_INCREMENT;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
