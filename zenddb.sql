-- phpMyAdmin SQL Dump
-- version 4.4.13.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 20, 2016 at 01:02 PM
-- Server version: 5.6.28-0ubuntu0.15.10.1
-- PHP Version: 5.6.11-1ubuntu3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zenddb`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL,
  `name` varchar(75) NOT NULL,
  `id_country` int(11) NOT NULL,
  `note` text,
  `e_mail` varchar(50) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `skype` varchar(40) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `id_country`, `note`, `e_mail`, `phone`, `skype`) VALUES
(1, 'John Smith', 1, 'Some info', 'john_smith@mail.com', '954-851-45', 'john_smith'),
(2, 'Tomas Winmil', 3, '', 'tomas_wm@gmail.com', '57656451', ''),
(4, 'Will Smith', 1, '', 'example@gmail.com', '1231231', '');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`) VALUES
(1, 'Canada'),
(2, 'UK'),
(3, 'USA'),
(4, 'Ukraine');

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE IF NOT EXISTS `currency` (
  `id` int(11) NOT NULL,
  `ISO_code` varchar(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`id`, `ISO_code`) VALUES
(1, 'USD'),
(2, 'CAD'),
(3, 'EUR'),
(4, 'UAH');

-- --------------------------------------------------------

--
-- Table structure for table `currencyex`
--

CREATE TABLE IF NOT EXISTS `currencyex` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `id_currency_first` int(11) NOT NULL,
  `id_currency_second` int(11) NOT NULL,
  `current_rate` decimal(10,4) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `currencyex`
--

INSERT INTO `currencyex` (`id`, `date`, `id_currency_first`, `id_currency_second`, `current_rate`) VALUES
(6, '2016-04-05', 1, 2, 1.5000),
(7, '2016-04-05', 1, 4, 25.0000),
(8, '2016-04-05', 1, 3, 0.9000),
(10, '2016-04-07', 1, 2, 12.0000),
(11, '2016-04-06', 1, 2, 45.0000),
(12, '2016-04-05', 1, 3, 0.4800),
(13, '2016-04-08', 3, 1, 0.6660),
(15, '2016-04-06', 3, 1, 0.9500);

-- --------------------------------------------------------

--
-- Table structure for table `inprogress`
--

CREATE TABLE IF NOT EXISTS `inprogress` (
  `id` int(11) NOT NULL,
  `id_task` int(11) NOT NULL,
  `id_programmist` int(11) NOT NULL,
  `date` date NOT NULL,
  `hours` decimal(10,2) NOT NULL,
  `note` text CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `id_task` int(11) NOT NULL,
  `summ` decimal(15,2) DEFAULT NULL,
  `summ_curr` int(11) NOT NULL,
  `commisions` decimal(15,2) DEFAULT NULL,
  `comm_curr` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `date`, `id_task`, `summ`, `summ_curr`, `commisions`, `comm_curr`) VALUES
(1, '2016-03-28', 2, 1000.00, 1, 10.00, 1),
(2, '2016-04-01', 5, 255.00, 2, 12.00, 2),
(3, '2016-04-06', 6, 125.00, 1, 15.00, 1),
(4, '2016-04-06', 2, 125.00, 1, 12.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `programmists`
--

CREATE TABLE IF NOT EXISTS `programmists` (
  `id` int(11) NOT NULL,
  `firstName` varchar(40) NOT NULL,
  `lastName` varchar(40) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `price_curr` int(11) NOT NULL,
  `workHrs` int(11) NOT NULL,
  `effective_rate` decimal(15,2) NOT NULL,
  `ts_curr` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `programmists`
--

INSERT INTO `programmists` (`id`, `firstName`, `lastName`, `price`, `price_curr`, `workHrs`, `effective_rate`, `ts_curr`) VALUES
(1, 'Alex', 'Dorman', 500.00, 1, 160, 15.00, 1),
(2, 'John', 'Smith', 500.00, 1, 160, 20.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `code` varchar(40) NOT NULL,
  `id_client` int(11) NOT NULL,
  `note` text
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `code`, `id_client`, `note`) VALUES
(1, 'Project One', 'PO-1', 1, 'Some useful info'),
(6, 'BAS', 'Basilisk', 4, '');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'Sales'),
(3, 'PM');

-- --------------------------------------------------------

--
-- Table structure for table `soldtasks`
--

CREATE TABLE IF NOT EXISTS `soldtasks` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `id_task` int(11) NOT NULL,
  `rate` int(11) NOT NULL,
  `rate_curr` int(11) NOT NULL,
  `hours` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `soldtasks`
--

INSERT INTO `soldtasks` (`id`, `date`, `id_task`, `rate`, `rate_curr`, `hours`) VALUES
(1, '2017-03-20', 5, 30, 1, 10),
(2, '2016-04-07', 6, 25, 2, 125),
(3, '2016-04-13', 2, 15, 1, 50);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `name`) VALUES
(1, 'Pending'),
(2, 'in Progress'),
(3, 'Testing'),
(4, 'Waiting for payment'),
(5, 'Done');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `id_status` int(11) NOT NULL,
  `id_project` int(11) NOT NULL,
  `complete_date` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `name`, `id_status`, `id_project`, `complete_date`) VALUES
(2, 'TS-1', 2, 1, NULL),
(4, 'TS-2', 4, 1, NULL),
(5, 'Basilisk design', 2, 6, NULL),
(6, 'Basilisk (back-end)', 2, 6, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` char(128) COLLATE utf8_unicode_ci NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role_id`) VALUES
(1, 'admin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 1),
(2, 'pm', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_country` (`id_country`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencyex`
--
ALTER TABLE `currencyex`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_currency_first` (`id_currency_first`),
  ADD KEY `id_currency_second` (`id_currency_second`);

--
-- Indexes for table `inprogress`
--
ALTER TABLE `inprogress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_task` (`id_task`),
  ADD KEY `id_programmist` (`id_programmist`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `summ_curr` (`summ_curr`),
  ADD KEY `comm_curr` (`comm_curr`),
  ADD KEY `id_task` (`id_task`);

--
-- Indexes for table `programmists`
--
ALTER TABLE `programmists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `price_curr` (`price_curr`),
  ADD KEY `ts_curr` (`ts_curr`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_client` (`id_client`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `soldtasks`
--
ALTER TABLE `soldtasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_task` (`id_task`),
  ADD KEY `rate_curr` (`rate_curr`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_status` (`id_status`),
  ADD KEY `id_project` (`id_project`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `currency`
--
ALTER TABLE `currency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `currencyex`
--
ALTER TABLE `currencyex`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `inprogress`
--
ALTER TABLE `inprogress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `programmists`
--
ALTER TABLE `programmists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `soldtasks`
--
ALTER TABLE `soldtasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`id_country`) REFERENCES `countries` (`id`);

--
-- Constraints for table `currencyex`
--
ALTER TABLE `currencyex`
  ADD CONSTRAINT `currencyex_ibfk_1` FOREIGN KEY (`id_currency_first`) REFERENCES `currency` (`id`),
  ADD CONSTRAINT `currencyex_ibfk_2` FOREIGN KEY (`id_currency_second`) REFERENCES `currency` (`id`);

--
-- Constraints for table `inprogress`
--
ALTER TABLE `inprogress`
  ADD CONSTRAINT `inProgress_ibfk_programmists` FOREIGN KEY (`id_programmist`) REFERENCES `programmists` (`id`),
  ADD CONSTRAINT `inProgress_ibfk_tasks` FOREIGN KEY (`id_task`) REFERENCES `tasks` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`summ_curr`) REFERENCES `currency` (`id`),
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`comm_curr`) REFERENCES `currency` (`id`),
  ADD CONSTRAINT `payments_ibfk_4` FOREIGN KEY (`id_task`) REFERENCES `tasks` (`id`);

--
-- Constraints for table `programmists`
--
ALTER TABLE `programmists`
  ADD CONSTRAINT `programmists_ibfk_1` FOREIGN KEY (`price_curr`) REFERENCES `currency` (`id`),
  ADD CONSTRAINT `programmists_ibfk_2` FOREIGN KEY (`ts_curr`) REFERENCES `currency` (`id`);

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id`);

--
-- Constraints for table `soldtasks`
--
ALTER TABLE `soldtasks`
  ADD CONSTRAINT `soldtasks_ibfk_1` FOREIGN KEY (`id_task`) REFERENCES `tasks` (`id`),
  ADD CONSTRAINT `soldtasks_ibfk_2` FOREIGN KEY (`rate_curr`) REFERENCES `currency` (`id`);

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`id_status`) REFERENCES `status` (`id`),
  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`id_project`) REFERENCES `projects` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
