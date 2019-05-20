-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 25, 2019 at 04:58 PM
-- Server version: 5.6.41-84.1
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jdeas6_cpt262`
--

-- --------------------------------------------------------

--
-- Table structure for table `cartinfo`
--

CREATE TABLE `cartinfo` (
  `dbcartid` int(11) NOT NULL,
  `dbcartuser` int(11) NOT NULL,
  `dbcartdate` datetime NOT NULL,
  `dbcartcomplete` int(11) NOT NULL,
  `dbcartpickup` int(11) NOT NULL,
  `dbcartmade` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cartinfo`
--

INSERT INTO `cartinfo` (`dbcartid`, `dbcartuser`, `dbcartdate`, `dbcartcomplete`, `dbcartpickup`, `dbcartmade`) VALUES
(6, 42, '2018-02-13 00:00:00', 0, 2, 0),
(7, 42, '2018-02-13 00:00:00', 0, 1, 0),
(8, 43, '2018-02-15 00:00:00', 0, 2, 0),
(9, 11, '2019-03-02 20:14:24', 0, 1, 0),
(10, 10, '2019-03-14 12:01:10', 0, 1, 0),
(11, 10, '2019-03-14 12:11:42', 0, 1, 0),
(12, 10, '2019-03-14 12:54:23', 0, 1, 0),
(13, 10, '2019-03-14 13:15:34', 0, 1, 0),
(14, 10, '2019-04-22 19:50:34', 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cartitems`
--

CREATE TABLE `cartitems` (
  `dbcartitemid` int(11) NOT NULL,
  `dbcartid` int(11) NOT NULL,
  `dbourproductid` int(11) NOT NULL,
  `dbcartitemprice` decimal(8,2) NOT NULL,
  `dbcartitemnotes` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cartitems`
--

INSERT INTO `cartitems` (`dbcartitemid`, `dbcartid`, `dbourproductid`, `dbcartitemprice`, `dbcartitemnotes`) VALUES
(21, 6, 2, '14.95', ''),
(36, 8, 8, '1.95', ''),
(35, 8, 1, '12.99', ''),
(34, 8, 3, '1.25', ''),
(33, 8, 6, '5.55', 'aaa'),
(32, 7, 7, '6.50', ''),
(31, 7, 6, '5.55', ''),
(30, 7, 4, '2.36', ''),
(29, 7, 2, '14.95', ''),
(41, 9, 1, '12.99', '');

-- --------------------------------------------------------

--
-- Table structure for table `ourproducts`
--

CREATE TABLE `ourproducts` (
  `dbourproductid` int(11) NOT NULL,
  `dbourproductname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `dbourproductdescr` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `dbourproductcost` decimal(8,2) NOT NULL,
  `dbourproductcat` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ourproducts`
--

INSERT INTO `ourproducts` (`dbourproductid`, `dbourproductname`, `dbourproductdescr`, `dbourproductcost`, `dbourproductcat`) VALUES
(1, 'Fries', 'with ketchup', '2.99', 1),
(2, 'Fried Pickles', 'With Ranch', '4.95', 1),
(3, 'Bacon cheeseburger', 'with lettuce and tomato', '1.25', 2),
(4, 'Cheeseburger', 'Plain', '2.36', 2),
(5, 'Ham', 'Just Ham', '4.22', 3),
(6, 'Ham and Swiss', 'Ham with Swiss', '5.55', 3),
(7, 'Turkey', 'Gobble', '6.50', 3),
(8, 'YumYum', 'Yummy', '1.95', 4),
(9, 'blt', 'very good!', '3.00', 3),
(10, 'gummy worms', 'popular with kids', '2.25', 4),
(11, 'Chocolate Cake', 'Yummy chocolate!', '4.95', 5),
(12, 'Cheese cake', 'New York\'s Finest!', '5.55', 5),
(13, 'Cheese cake', 'New York\'s Finest!', '5.55', 5),
(14, 'Sweet Tea', 'Southern Sweet Tea', '2.09', 6),
(15, 'Sprite', 'Yum!', '2.69', 6);

-- --------------------------------------------------------

--
-- Table structure for table `prodcats`
--

CREATE TABLE `prodcats` (
  `dbprodcatid` int(11) NOT NULL,
  `dbprodcatname` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `prodcats`
--

INSERT INTO `prodcats` (`dbprodcatid`, `dbprodcatname`) VALUES
(1, 'Appetizers'),
(2, 'Burgers'),
(3, 'Sandwiches'),
(4, 'Snack'),
(5, 'Dessert'),
(6, 'Beverages');

-- --------------------------------------------------------

--
-- Table structure for table `ticketItems`
--

CREATE TABLE `ticketItems` (
  `ticketid` int(11) NOT NULL,
  `ticketitemid` int(11) NOT NULL,
  `ticketprodid` int(11) NOT NULL,
  `ticketitemprice` decimal(8,2) NOT NULL,
  `ticketcomments` char(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ticketItems`
--

INSERT INTO `ticketItems` (`ticketid`, `ticketitemid`, `ticketprodid`, `ticketitemprice`, `ticketcomments`) VALUES
(14, 0, 8, '1.95', ''),
(13, 0, 7, '1.95', ''),
(13, 0, 14, '1.95', ''),
(13, 0, 2, '1.95', ''),
(13, 0, 1, '1.95', ''),
(0, 0, 14, '1.95', ''),
(0, 0, 10, '1.95', ''),
(0, 0, 7, '1.95', ''),
(12, 0, 11, '1.95', ''),
(0, 0, 6, '1.95', ''),
(0, 0, 7, '1.95', ''),
(14, 0, 7, '1.95', ''),
(14, 0, 7, '1.95', ''),
(0, 0, 8, '1.95', ''),
(0, 0, 12, '1.95', ''),
(15, 0, 8, '1.95', ''),
(15, 0, 3, '1.95', ''),
(16, 0, 3, '1.95', ''),
(16, 0, 6, '1.95', ''),
(16, 0, 7, '1.95', ''),
(16, 0, 2, '1.95', ''),
(17, 0, 7, '1.95', ''),
(17, 0, 6, '1.95', '');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `ticketNum` int(11) NOT NULL,
  `ticketEmp` int(11) NOT NULL,
  `ticketUser` char(55) COLLATE utf8_unicode_ci NOT NULL,
  `ticketDate` datetime NOT NULL,
  `ticketOption` char(20) COLLATE utf8_unicode_ci NOT NULL,
  `ticketComplete` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`ticketNum`, `ticketEmp`, `ticketUser`, `ticketDate`, `ticketOption`, `ticketComplete`) VALUES
(1, 10, '2', '2019-03-14 14:09:26', '1', 0),
(2, 10, '2', '2019-03-14 14:10:25', '1', 1),
(3, 10, '3', '2019-03-14 14:13:02', '1', 1),
(4, 10, '1', '2019-03-14 14:24:44', '2', 1),
(5, 10, '7', '2019-03-14 16:01:17', '1', 1),
(6, 10, '2', '2019-03-14 18:21:56', '1', 1),
(7, 10, '2', '2019-03-16 03:39:50', '1', 0),
(8, 10, '6', '2019-03-16 03:45:08', '2', 0),
(9, 0, '11', '2019-03-16 22:06:15', '2', 0),
(10, 0, '11', '2019-03-16 22:08:19', '2', 1),
(11, 0, '11', '2019-03-16 22:11:33', '2', 1),
(12, 0, '11', '2019-03-16 22:37:47', '1', 1),
(13, 10, '1', '2019-03-16 22:45:26', '2', 1),
(14, 10, '4', '2019-03-16 22:48:16', '2', 1),
(15, 10, '2', '2019-03-16 22:50:27', '2', 1),
(16, 0, '13', '2019-03-19 17:18:59', '2', 1),
(17, 0, '13', '2019-03-21 16:15:44', '1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `userinfo`
--

CREATE TABLE `userinfo` (
  `this` int(11) NOT NULL,
  `dbfullname` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `dbemail` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `dbpassword` char(100) COLLATE utf8_unicode_ci NOT NULL,
  `dbclassgrade` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `dbcolorpicked` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `dbdate` date NOT NULL,
  `dbcomments` text COLLATE utf8_unicode_ci NOT NULL,
  `dbsalt` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `dbuserpermit` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `userinfo`
--

INSERT INTO `userinfo` (`this`, `dbfullname`, `dbemail`, `dbpassword`, `dbclassgrade`, `dbcolorpicked`, `dbdate`, `dbcomments`, `dbsalt`, `dbuserpermit`) VALUES
(1, 'Joe', 'joe@gmail.com', '1234', 'FR', 'green', '0000-00-00', 'fda', '', 0),
(2, 'Sue', 'sue@gmail.com', '12234', 'JR', 'green', '0000-00-00', 'girl', '', 0),
(3, 'Jill', 'jill@yahoo.com', '123', 'SO', 'green', '0000-00-00', 'hunts', '', 0),
(4, 'John', 'j@gmail.com', '1234', 'SO', 'blue', '0000-00-00', 'ddsdd', '', 0),
(6, 'Tim', 'timmy@gmail.com', '$2y$12$A62tc5qj7s4hH8EE0zj70Ob8CgDa/jQl1zSp9WL3VMRX.fQV.Rf7G', 'SR', 'blue', '2019-02-09', 'hey', '', 0),
(7, 'q', 'q@q.com', '$2y$12$TFAO4gG3EuphHPodCktffuwB5.NlOuRV6MZIpCxdOJLHJibXfCR0O', 'FR', 'green', '2019-12-31', 'q', '', 2),
(10, 'z', 'z@z.com', '$2y$12$iZKCYZ8vFBNJcS4EhjzhpuMSGiVXNCTJjClkx3PWk760jnMcvlk.C', 'SO', 'green', '2019-03-01', 'z', '', 1),
(11, 'b', 'b@b.com', '$2y$12$qZYMS7MmfblAu4KrPrVUBOqFRQDrs0maUAdBpSV5ALRc6DtfTlZna', 'SO', 'blue', '2019-02-06', 'b', '', 5),
(12, 't', 't@t.com', '$2y$12$7CgTVI7n4zNEeYLHOVciQO9ZZt2indM8W2oqfh6JEgiZMkHO8hP7C', 'FR', 'green', '2019-03-05', 't', '', 0),
(13, 'f', 'f@f.com', '$2y$12$Mi90ARpczRcLqUjkTYxh1usTEMTl7zpkJlN8qLcYI/azB0oNs1ZxK', 'SO', 'blue', '2019-03-20', 'f', '', 5);

-- --------------------------------------------------------

--
-- Table structure for table `userpermit`
--

CREATE TABLE `userpermit` (
  `dbpermitid` int(11) NOT NULL,
  `dbpermitlevel` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `userpermit`
--

INSERT INTO `userpermit` (`dbpermitid`, `dbpermitlevel`) VALUES
(1, 'Insert'),
(2, 'Select'),
(3, 'Update'),
(5, 'Buyer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cartinfo`
--
ALTER TABLE `cartinfo`
  ADD PRIMARY KEY (`dbcartid`);

--
-- Indexes for table `cartitems`
--
ALTER TABLE `cartitems`
  ADD PRIMARY KEY (`dbcartitemid`);

--
-- Indexes for table `ourproducts`
--
ALTER TABLE `ourproducts`
  ADD PRIMARY KEY (`dbourproductid`);

--
-- Indexes for table `prodcats`
--
ALTER TABLE `prodcats`
  ADD PRIMARY KEY (`dbprodcatid`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticketNum`);

--
-- Indexes for table `userinfo`
--
ALTER TABLE `userinfo`
  ADD PRIMARY KEY (`this`);

--
-- Indexes for table `userpermit`
--
ALTER TABLE `userpermit`
  ADD PRIMARY KEY (`dbpermitid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cartinfo`
--
ALTER TABLE `cartinfo`
  MODIFY `dbcartid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `cartitems`
--
ALTER TABLE `cartitems`
  MODIFY `dbcartitemid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `ourproducts`
--
ALTER TABLE `ourproducts`
  MODIFY `dbourproductid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `prodcats`
--
ALTER TABLE `prodcats`
  MODIFY `dbprodcatid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticketNum` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `userinfo`
--
ALTER TABLE `userinfo`
  MODIFY `this` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `userpermit`
--
ALTER TABLE `userpermit`
  MODIFY `dbpermitid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
