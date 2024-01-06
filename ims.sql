-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2024 at 03:37 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

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
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `user_id` int(225) NOT NULL,
  `item_id` int(225) NOT NULL,
  `inCart` tinyint(1) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date_out` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`user_id`, `item_id`, `inCart`, `date_added`, `date_out`) VALUES
(5, 1, 1, '2024-01-04 10:03:12', '2024-01-04 18:03:12'),
(5, 2, 1, '2023-12-26 11:42:42', '2023-12-26 19:42:42'),
(5, 2, 1, '2023-12-26 11:42:42', '2023-12-26 19:42:42'),
(5, 4, 1, '2024-01-06 14:36:15', '2024-01-06 22:36:15'),
(5, 2, 1, '2023-12-26 11:42:42', '2023-12-26 19:42:42'),
(5, 4, 1, '2024-01-06 14:36:15', '2024-01-06 22:36:15'),
(5, 10, 1, '2023-12-27 11:28:25', '2023-12-27 19:28:25'),
(5, 4, 1, '2024-01-06 14:36:15', '2024-01-06 22:36:15'),
(5, 5, 0, '2023-12-28 14:23:13', NULL),
(5, 4, 1, '2024-01-06 14:36:15', '2024-01-06 22:36:15'),
(5, 1, 1, '2024-01-04 10:03:12', '2024-01-04 18:03:12'),
(5, 4, 1, '2024-01-06 14:36:15', '2024-01-06 22:36:15'),
(5, 5, 0, '2024-01-04 10:38:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `history_id` int(11) NOT NULL,
  `history_item_id` int(11) NOT NULL,
  `history_quantity` int(11) NOT NULL,
  `history_user_id` int(11) NOT NULL,
  `history_status` enum('approved','declined','pending','') NOT NULL,
  `history_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `isReturned` int(1) NOT NULL,
  `history_date_return` varchar(30) NOT NULL,
  `history_due_date` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`history_id`, `history_item_id`, `history_quantity`, `history_user_id`, `history_status`, `history_date`, `isReturned`, `history_date_return`, `history_due_date`) VALUES
(1, 1, 1, 5, 'declined', '2023-12-27 14:46:10', 1, '1/1/1', '1/1/1'),
(2, 2, 1, 5, 'approved', '2023-12-27 14:47:52', 1, '', ''),
(3, 2, 2, 5, 'approved', '2023-12-27 14:53:39', 1, '2023-12-27 22:53:39', ''),
(4, 4, 1, 5, 'approved', '2023-12-27 14:55:40', 1, '2023-12-27 10:55:40', ''),
(5, 2, 2, 5, 'approved', '2023-12-27 14:58:39', 1, '2023-12-27 10:58:39', ''),
(6, 1, 1, 5, 'approved', '2023-12-27 14:58:58', 1, '2023-12-27 10:58:58', ''),
(7, 1, 1, 5, 'approved', '2024-01-04 10:53:52', 1, '2024-01-04 6:53:52', ''),
(8, 1, 1, 5, 'approved', '2024-01-04 10:54:17', 1, '2024-01-04 6:54:17', ''),
(9, 1, 5, 5, 'approved', '2024-01-04 10:54:27', 1, '2024-01-04 6:54:27', ''),
(10, 1, 7, 5, 'approved', '2024-01-04 11:30:56', 1, '2024-01-04 7:30:56', ''),
(11, 1, 5, 5, 'approved', '2024-01-04 11:31:36', 1, '2024-01-04 7:31:36', ''),
(12, 2, 9, 5, 'approved', '2023-12-26 11:42:42', 0, '', ''),
(13, 4, 1, 5, 'approved', '2024-01-06 14:33:09', 1, '2024-01-06 10:33:09', ''),
(14, 4, 2, 5, 'approved', '2023-12-26 12:42:45', 0, '', ''),
(15, 4, 4, 5, 'approved', '2023-12-26 12:43:06', 0, '', ''),
(16, 4, 5, 5, 'approved', '2023-12-26 12:45:23', 0, '', ''),
(17, 5, 1, 5, 'approved', '2024-01-06 14:33:55', 1, '2024-01-06 10:33:55', ''),
(18, 10, 1, 5, 'approved', '2023-12-27 11:27:28', 0, '', ''),
(19, 10, 1, 5, 'approved', '2023-12-27 11:27:35', 0, '', ''),
(20, 10, 1, 5, 'approved', '2023-12-27 11:28:25', 0, '', ''),
(21, 1, 100, 5, 'approved', '2023-12-27 15:00:06', 1, '2023-12-27 11:00:06', ''),
(22, 1, 50, 5, 'approved', '2023-12-27 15:02:06', 1, '2023-12-27 11:02:06', ''),
(23, 1, 50, 5, 'approved', '2023-12-27 15:03:19', 1, '2023-12-27 11:03:19', ''),
(24, 4, 5, 5, 'approved', '2023-12-28 13:17:08', 0, '', ''),
(25, 4, 4, 5, 'approved', '2023-12-28 13:17:17', 0, '', ''),
(26, 1, 5, 5, 'approved', '2024-01-04 11:31:56', 1, '2024-01-04 7:31:56', ''),
(27, 4, 1, 5, 'approved', '2024-01-06 14:36:15', 0, '', '');

--
-- Triggers `history`
--
DELIMITER $$
CREATE TRIGGER `updateCart` AFTER INSERT ON `history` FOR EACH ROW BEGIN
UPDATE cart 
SET inCart = 1,
date_out = NOW()
WHERE item_id = NEW.history_item_id AND user_id = NEW.history_user_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `updateQuantityItem` BEFORE INSERT ON `history` FOR EACH ROW BEGIN
    UPDATE items 
    SET items.item_quantity = items.item_quantity -   NEW.history_quantity
    WHERE items.item_id = NEW.history_item_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `updateQuantityItemRequest` BEFORE UPDATE ON `history` FOR EACH ROW BEGIN
    UPDATE items 
    SET items.item_quantity = items.item_quantity +   NEW.history_quantity
    WHERE items.item_id = NEW.history_item_id;
END
$$
DELIMITER ;

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
  `item_status` enum('enabled','disabled','','') NOT NULL,
  `item_description` varchar(255) NOT NULL,
  `item_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_name`, `item_category`, `item_measure`, `item_quantity`, `item_chapter`, `item_status`, `item_description`, `item_image`) VALUES
(1, 'Galaxy Tab A Tablet', 1, 3, 140, 3, 'enabled', '4gb 256gb color black', 'IMG_655c634aa37f87.97220155.jpg'),
(2, 'A4 Bond Paper', 3, 2, 2, 2, 'enabled', 'A4 Hardcopy', 'IMG_655c63420ed111.72246669.png'),
(4, 'ID Lace version 5', 3, 3, 60, 4, 'enabled', 'Version 5, Black & Orange', 'IMG_6543bb326ab0b1.55477099.'),
(5, 'Jumping wires long', 3, 1, 31, 4, 'enabled', 'Rainbow colors', ''),
(7, 'Gel Ink Pen about to delete', 3, 1, 25, 5, 'disabled', 'Black', 'IMG_65350cc0dbc2f1.96755226.'),
(8, 'Jungle Juice', 2, 2, 13, 2, 'disabled', '350ml Mango, Apple, Grapes, & Orange', 'IMG_650896c28445b9.58710873.jpg'),
(10, 'Sunglasses', 3, 3, 10, 1, 'enabled', 'Rayband, Black frame', 'IMG_650898af7e3799.65129257.jpg'),
(11, 'Hotdog', 2, 1, 22, 1, 'enabled', 'Jumbo, Tender Juicy, Pure Foods, 12pcs per pack', 'IMG_650899879ba091.62117593.png'),
(14, 'yoon', 1, 1, 2, 2, 'enabled', 'grdg', 'IMG_655c5d66a35232.43841368.png'),
(15, 'wa ', 1, 1, 2321, 3, 'enabled', 'dasdasda', 'IMG_655b09bf1c7bc7.78434158.png');

-- --------------------------------------------------------

--
-- Table structure for table `items_category`
--

CREATE TABLE `items_category` (
  `item_category_id` int(11) NOT NULL,
  `item_category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `items_unit_of_measure`
--

INSERT INTO `items_unit_of_measure` (`item_uom_id`, `item_uom_name`) VALUES
(1, 'Pack'),
(2, 'Box'),
(3, 'Piece(s)');

-- --------------------------------------------------------

--
-- Table structure for table `item_feedback`
--

CREATE TABLE `item_feedback` (
  `item_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `feedback` varchar(225) NOT NULL,
  `date_of_feedback` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `log_id` int(11) NOT NULL,
  `log_user` int(11) NOT NULL,
  `log_type` enum('modify','return','request','add','') NOT NULL,
  `log_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `user_chapter` int(11) NOT NULL,
  `user_image` varchar(255) NOT NULL,
  `user_status` enum('active','inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_firstname`, `user_lastname`, `user_email`, `user_password`, `user_category`, `user_chapter`, `user_image`, `user_status`) VALUES
(1, 'Jake', 'Maangas', 'jakemantesnapay@gmail.com', '$2y$10$RIQWqDTz88hS713D1XbPIOAiicKGkmTpUr4FJnMSquvh4SbuGUj5S', 1, 1, 'IMG_6561f388e0cba6.26612895.png', 'active'),
(2, 'john moren', 'dinela', 'jmdinela@gmail.com', '$2y$10$1o24hhJ6ibvCFNhaoD9jaeQW9PS59s7lDV3f2MtS3Uv3bLNhLqtJm', 0, 2, 'IMG_6562cc23d0aa35.57042786.jpg', 'active'),
(4, 'jay Ar', 'De Guzman', 'jdeguzman@gmail.com', '$2y$10$wk28/l2eJqickXsyQR6tZO43Dj279xxm.8otU9pPbeE7Bk/stzjxq', 1, 4, 'IMG_65620bf1516301.89054242.png', 'active'),
(5, 'Lee Angelo', 'Mollo', 'lamollo@gmail.com', '$2y$10$pzhfiX1L7fC723q5n6/xReHRBUrVLbdy2yuiOUsNG6Ay9Wjvr21Ey', 0, 4, 'IMG_6562d4d53d4b06.24348986.jpg', 'active');

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
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `history_user_id` (`history_user_id`),
  ADD KEY `history_ibfk_1` (`history_item_id`);

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
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `log_user` (`log_user`);

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
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `history_ibfk_1` FOREIGN KEY (`history_item_id`) REFERENCES `items` (`item_id`),
  ADD CONSTRAINT `history_ibfk_2` FOREIGN KEY (`history_user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`item_category`) REFERENCES `items_category` (`item_category_id`),
  ADD CONSTRAINT `items_ibfk_2` FOREIGN KEY (`item_measure`) REFERENCES `items_unit_of_measure` (`item_uom_id`),
  ADD CONSTRAINT `items_ibfk_3` FOREIGN KEY (`item_chapter`) REFERENCES `chapters` (`chapter_id`);

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`log_user`) REFERENCES `users` (`user_id`);

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
