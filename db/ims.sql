-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2023 at 02:15 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ims`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(0, 'user'),
(1, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `chapters`
--

CREATE TABLE `chapters` (
  `chapter_id` int(11) NOT NULL,
  `chapter_name` varchar(255) NOT NULL,
  `chapter_address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chapters`
--

INSERT INTO `chapters` (`chapter_id`, `chapter_name`, `chapter_address`) VALUES
(1, 'Manila', 'Blablabla, Manila, Philippines'),
(2, 'Cagayan De Oro', 'blablabla, Cagayan De Oro, Philippines'),
(3, 'Cebu', 'Cebu, Philippines'),
(4, 'Davao', 'Davao, Philippines'),
(5, 'Iligan', 'Iligan, Philippines'),
(6, 'Los Angeles', 'Los Angeles, USA');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_category` int(11) NOT NULL,
  `item_measure` int(11) NOT NULL,
  `item_quantity` int(11) NOT NULL,
  `item_chapter` int(11) NOT NULL,
  `item_description` varchar(255) NOT NULL,
  `item_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_name`, `item_category`, `item_measure`, `item_quantity`, `item_chapter`, `item_description`, `item_image`) VALUES
(1, 'Galaxy Tab A Tablet', 1, 3, 3, 1, '4gb 256gb color black', ''),
(2, 'A4 Bond Paper', 3, 2, 10, 2, 'A4 Hardcopy', ''),
(3, 'Bottled Water', 2, 3, 20, 1, 'Nature Spring 500ml', ''),
(4, 'ID Lace version 5', 3, 3, 80, 3, 'Version 5, Black & Orange', ''),
(5, 'Jumping wires long', 3, 1, 30, 4, 'Rainbow colors', ''),
(6, 'Gel Ink Pen', 3, 1, 24, 5, 'Black', ''),
(7, 'Gel Ink Pen', 3, 1, 24, 5, 'Black', ''),
(8, 'Jungle Juice', 2, 2, 13, 2, '350ml Mango, Apple, Grapes, & Orange', 'IMG_650896c28445b9.58710873.jpg'),
(9, 'Sunglasses', 3, 3, 20, 1, 'Rayban, Black Frame', 'IMG_6508975f56a452.42876042.jpg'),
(10, 'Sunglasses', 3, 3, 13, 1, 'Rayband, Black frame', 'IMG_650898af7e3799.65129257.jpg'),
(11, 'Hotdog', 2, 1, 22, 1, 'Jumbo, Tender Juicy, Pure Foods, 12pcs per pack', 'IMG_650899879ba091.62117593.png'),
(12, 'wireless mouse', 1, 3, 10, 1, 'Wireless, Hp, Color Black', 'IMG_65098bc3db97d8.82832936.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `items_category`
--

CREATE TABLE `items_category` (
  `item_category_id` int(11) NOT NULL,
  `item_category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items_category`
--

INSERT INTO `items_category` (`item_category_id`, `item_category_name`) VALUES
(1, 'Technology'),
(2, 'Consumable'),
(3, 'Supply');

-- --------------------------------------------------------

--
-- Table structure for table `items_unit_of_measure`
--

CREATE TABLE `items_unit_of_measure` (
  `item_uom_id` int(11) NOT NULL,
  `item_uom_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items_unit_of_measure`
--

INSERT INTO `items_unit_of_measure` (`item_uom_id`, `item_uom_name`) VALUES
(1, 'Pack'),
(2, 'Box'),
(3, 'Piece(s)');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_firstname` varchar(255) NOT NULL,
  `user_lastname` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_category` int(11) NOT NULL,
  `user_chapter` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_firstname`, `user_lastname`, `user_email`, `user_password`, `user_category`, `user_chapter`) VALUES
(1, 'jake', 'napay', 'jakemantesnapay@gmail.com', '$2y$10$RIQWqDTz88hS713D1XbPIOAiicKGkmTpUr4FJnMSquvh4SbuGUj5S', 1, 1),
(2, 'john moren', 'dinela', 'jmdinela@gmail.com', '$2y$10$1o24hhJ6ibvCFNhaoD9jaeQW9PS59s7lDV3f2MtS3Uv3bLNhLqtJm', 0, 2),
(4, 'Jay Ar', 'De Guzman', 'jdeguzman@gmail.com', '$2y$10$wk28/l2eJqickXsyQR6tZO43Dj279xxm.8otU9pPbeE7Bk/stzjxq', 1, 4),
(5, 'Lee Angelo', 'Mollo', 'lamollo@gmail.com', '$2y$10$pzhfiX1L7fC723q5n6/xReHRBUrVLbdy2yuiOUsNG6Ay9Wjvr21Ey', 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `chapters`
--
ALTER TABLE `chapters`
  ADD PRIMARY KEY (`chapter_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `item_category` (`item_category`),
  ADD KEY `item_measure` (`item_measure`),
  ADD KEY `item_chapter` (`item_chapter`);

--
-- Indexes for table `items_category`
--
ALTER TABLE `items_category`
  ADD PRIMARY KEY (`item_category_id`);

--
-- Indexes for table `items_unit_of_measure`
--
ALTER TABLE `items_unit_of_measure`
  ADD PRIMARY KEY (`item_uom_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_category` (`user_category`),
  ADD KEY `user_chapter` (`user_chapter`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `chapters`
--
ALTER TABLE `chapters`
  MODIFY `chapter_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `items_category`
--
ALTER TABLE `items_category`
  MODIFY `item_category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `items_unit_of_measure`
--
ALTER TABLE `items_unit_of_measure`
  MODIFY `item_uom_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`item_category`) REFERENCES `items_category` (`item_category_id`),
  ADD CONSTRAINT `items_ibfk_2` FOREIGN KEY (`item_measure`) REFERENCES `items_unit_of_measure` (`item_uom_id`),
  ADD CONSTRAINT `items_ibfk_3` FOREIGN KEY (`item_chapter`) REFERENCES `chapters` (`chapter_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `user_category` FOREIGN KEY (`user_category`) REFERENCES `category` (`category_id`),
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`user_chapter`) REFERENCES `chapters` (`chapter_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
