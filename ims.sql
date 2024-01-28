-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2024 at 01:28 PM
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
(5, 1, 1, '2024-01-10 02:23:36', '2024-01-10 10:23:36'),
(5, 2, 1, '2024-01-07 04:39:48', '2024-01-07 12:39:48'),
(5, 2, 1, '2024-01-07 04:39:48', '2024-01-07 12:39:48'),
(5, 4, 1, '2024-01-28 11:54:00', '2024-01-28 19:54:00'),
(5, 2, 1, '2024-01-07 04:39:48', '2024-01-07 12:39:48'),
(5, 4, 1, '2024-01-28 11:54:00', '2024-01-28 19:54:00'),
(5, 10, 1, '2023-12-27 11:28:25', '2023-12-27 19:28:25'),
(5, 4, 1, '2024-01-28 11:54:00', '2024-01-28 19:54:00'),
(5, 5, 1, '2024-01-28 02:22:22', '2024-01-28 10:22:22'),
(5, 4, 1, '2024-01-28 11:54:00', '2024-01-28 19:54:00'),
(5, 1, 1, '2024-01-10 02:23:36', '2024-01-10 10:23:36'),
(5, 4, 1, '2024-01-28 11:54:00', '2024-01-28 19:54:00'),
(5, 5, 1, '2024-01-28 02:22:22', '2024-01-28 10:22:22'),
(5, 1, 1, '2024-01-10 02:23:36', '2024-01-10 10:23:36'),
(5, 1, 1, '2024-01-10 02:23:36', '2024-01-10 10:23:36'),
(5, 1, 1, '2024-01-10 02:23:36', '2024-01-10 10:23:36'),
(5, 4, 1, '2024-01-28 11:54:00', '2024-01-28 19:54:00'),
(5, 5, 1, '2024-01-28 02:22:22', '2024-01-28 10:22:22'),
(5, 4, 1, '2024-01-28 11:54:00', '2024-01-28 19:54:00'),
(5, 4, 1, '2024-01-28 11:54:00', '2024-01-28 19:54:00'),
(4, 19, 1, '2024-01-28 01:49:42', '2024-01-28 09:49:42'),
(5, 17, 1, '2024-01-20 06:06:07', '2024-01-20 14:06:07'),
(5, 4, 1, '2024-01-28 11:54:00', '2024-01-28 19:54:00'),
(4, 4, 1, '2024-01-27 19:58:32', '2024-01-28 03:58:32'),
(1, 11, 1, '2024-01-23 06:47:52', '2024-01-23 14:47:52'),
(5, 24, 1, '2024-01-28 02:27:13', '2024-01-28 10:27:13'),
(5, 4, 1, '2024-01-28 11:54:00', '2024-01-28 19:54:00'),
(1, 29, 1, '2024-01-28 12:18:47', '2024-01-28 20:18:47');

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
-- Table structure for table `ctochistory`
--

CREATE TABLE `ctochistory` (
  `history_id` int(11) NOT NULL,
  `history_item_id` int(11) DEFAULT NULL,
  `history_quantity` int(11) DEFAULT NULL,
  `history_user_id` int(11) DEFAULT NULL,
  `history_status` varchar(255) DEFAULT NULL,
  `history_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `history_isReturn` tinyint(1) DEFAULT NULL,
  `history_date_return` date DEFAULT NULL,
  `history_due_date` date DEFAULT NULL,
  `from_chapter` varchar(255) DEFAULT NULL,
  `to_chapter` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ctochistory`
--

INSERT INTO `ctochistory` (`history_id`, `history_item_id`, `history_quantity`, `history_user_id`, `history_status`, `history_date`, `history_isReturn`, `history_date_return`, `history_due_date`, `from_chapter`, `to_chapter`) VALUES
(1, 2, 1, 4, 'process', '2024-01-27 20:33:17', 0, NULL, '2024-02-04', '4', '2'),
(2, 2, 1, 4, 'process', '2024-01-27 20:33:17', 0, NULL, '2024-02-04', '4', '2'),
(3, 2, 1, 4, 'process', '2024-01-27 20:33:50', 0, NULL, '2024-02-04', '4', '2'),
(4, 10, 1, 4, 'process', '2024-01-27 22:08:51', 0, NULL, '2024-02-04', '4', '1'),
(5, 10, 1, 4, 'process', '2024-01-27 22:35:40', 0, NULL, '2024-02-04', '4', '1'),
(6, 17, 1, 1, 'process', '2024-01-27 23:00:41', 0, NULL, '2024-02-04', '1', '4');

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
(1, 17, 1, 5, 'approved', '2024-01-23 06:24:56', 1, '2024-01-23 2:24:56', '2024-01-27'),
(2, 5, 5, 5, 'approved', '2024-01-23 06:25:00', 1, '2024-01-23 2:25:00', ''),
(3, 17, 1, 5, 'approved', '2024-01-23 06:25:03', 1, '2024-01-23 2:25:03', '2024-01-27'),
(4, 20, 1, 5, 'approved', '2024-01-20 06:09:10', 1, '2024-01-20 2:09:10', '2024-01-27'),
(5, 4, 3, 4, 'approved', '2024-01-21 08:38:50', 1, '2024-01-21 4:38:50', '2024-01-28'),
(6, 11, 1, 1, 'approved', '2024-01-21 08:32:41', 1, '2024-01-21 4:32:41', '2024-01-28'),
(7, 5, 1, 4, 'approved', '2024-01-21 08:38:30', 1, '2024-01-21 4:38:30', '2024-01-28'),
(8, 10, 1, 1, 'approved', '2024-01-23 06:40:26', 1, '2024-01-23 2:40:26', '2024-01-30'),
(9, 10, 1, 1, 'approved', '2024-01-23 06:41:45', 1, '2024-01-23 2:41:45', '2024-01-30'),
(10, 11, 1, 1, 'approved', '2024-01-23 06:48:00', 1, '2024-01-23 2:48:00', '2024-01-30'),
(11, 20, 1, 5, 'approved', '2024-01-24 15:51:17', 0, '', '2024-01-31'),
(12, 4, 1, 4, 'approved', '2024-01-27 19:58:32', 0, '', '2024-02-04'),
(13, 1, 1, 4, 'approved', '2024-01-27 21:28:07', 0, '', '2024-02-04'),
(14, 19, 4, 4, 'approved', '2024-01-28 01:49:42', 0, '', '2024-02-04'),
(15, 25, 1, 5, 'approved', '2024-01-28 02:06:08', 1, '2024-01-28 10:06:08', '2024-02-04'),
(16, 25, 1, 5, 'approved', '2024-01-28 02:05:54', 1, '2024-01-28 10:05:54', '2024-02-04'),
(17, 4, 3, 5, 'approved', '2024-01-28 02:12:32', 0, '', '2024-02-04'),
(18, 5, 1, 5, 'approved', '2024-01-28 02:22:22', 0, '', '2024-02-04'),
(19, 24, 2, 5, 'approved', '2024-01-28 02:27:13', 0, '', '2024-02-04'),
(20, 4, 5, 5, 'approved', '2024-01-28 11:35:06', 0, '', '2024-02-01'),
(21, 4, 1, 5, 'approved', '2024-01-28 11:39:32', 0, '', '2024-01-31'),
(22, 4, 1, 5, 'approved', '2024-01-28 11:39:42', 0, '', '2024-01-31'),
(23, 4, 1, 5, 'approved', '2024-01-28 11:54:00', 0, '', '2024-01-31'),
(24, 29, 1, 1, 'approved', '2024-01-28 12:20:43', 1, '2024-01-28 8:20:43', '2024-02-01');

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
CREATE TRIGGER `updateQuantityItem` AFTER INSERT ON `history` FOR EACH ROW BEGIN
    UPDATE items 
    SET items.item_quantity = items.item_quantity -   NEW.history_quantity
    WHERE items.item_id = NEW.history_item_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `updateQuantityItemRequest` AFTER UPDATE ON `history` FOR EACH ROW BEGIN
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
  `unique_item_id` varchar(225) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_category` int(11) NOT NULL,
  `item_measure` int(11) NOT NULL,
  `item_quantity` int(11) NOT NULL,
  `item_chapter` int(11) NOT NULL,
  `item_status` enum('enabled','disabled','','') NOT NULL,
  `item_description` varchar(255) NOT NULL,
  `item_image` varchar(255) NOT NULL,
  `item_condition` varchar(30) NOT NULL,
  `item_location` varchar(30) NOT NULL,
  `item_cost` varchar(30) NOT NULL,
  `barcode_img` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `unique_item_id`, `item_name`, `item_category`, `item_measure`, `item_quantity`, `item_chapter`, `item_status`, `item_description`, `item_image`, `item_condition`, `item_location`, `item_cost`, `barcode_img`) VALUES
(1, '335733', 'Galaxy Tab A Tablet', 1, 3, 137, 3, 'enabled', '4gb 256gb color black', 'IMG_655c634aa37f87.97220155.jpg', '', '', '', ''),
(2, '2792718', 'A4 Bond Paper', 3, 2, 2, 2, 'enabled', 'A4 Hardcopy', 'IMG_655c63420ed111.72246669.png', '', '', '', ''),
(4, '976760', 'ID Lace version 5', 3, 3, 31, 4, 'enabled', 'Version 5, Black & Orange', 'IMG_65b54f67295394.14973149.jpg', '', '', '', ''),
(5, '7542994', 'Jumping wires long', 3, 1, 30, 4, 'enabled', 'Rainbow colors', '', '', '', '', ''),
(7, '275921', 'Gel Ink Pen about to delete', 3, 1, 29, 5, 'disabled', 'Black', 'IMG_65350cc0dbc2f1.96755226.', '', '', '', ''),
(8, '111111', 'Jungle Juice', 2, 2, 19, 2, 'disabled', '350ml Mango, Apple, Grapes, & Orange', 'IMG_650896c28445b9.58710873.jpg', '', '', '', ''),
(10, '22222', 'Sunglasses', 3, 3, 22, 1, 'enabled', 'Rayband, Black frame', 'IMG_650898af7e3799.65129257.jpg', '', '', '', ''),
(11, '33333', 'Hotdog', 2, 1, 30, 1, 'enabled', 'Jumbo, Tender Juicy, Pure Foods, 12pcs per pack', 'IMG_650899879ba091.62117593.png', '', '', '', ''),
(14, '4444', 'yoon', 1, 1, 3, 2, 'enabled', 'grdg', 'IMG_655c5d66a35232.43841368.png', '', '', '', ''),
(15, '555555', 'wa ', 1, 1, 2322, 3, 'enabled', 'dasdasda', 'IMG_655b09bf1c7bc7.78434158.png', '', '', '', ''),
(17, '1611297', 'test', 1, 3, 2, 4, 'enabled', 'test1', 'IMG_659ff7beabd170.57412449.jpg', '', '', '', ''),
(18, '7481585', 'test2', 1, 1, 0, 4, 'enabled', '123456', 'IMG_659ff9f9239e03.99338571.jpg', '', '', '', ''),
(19, '282344', 'test3', 2, 3, 0, 4, 'enabled', 'test3', 'IMG_659ffa70106748.76927790.jpg', '', '', '', ''),
(20, '297191', 'apple', 1, 3, 3, 4, 'enabled', 'Apple Tablet 128gb', 'IMG_659fff00ab4ad6.04338973.jpg', '', '', '', ''),
(21, '502418', 'apple', 1, 3, 4, 4, 'enabled', 'apple', 'IMG_659fff19cc8683.32328815.jpg', '', '', '', ''),
(22, '198574', 'Ipad ', 1, 1, 6, 4, 'enabled', '128gb', 'IMG_65a0002e93cda7.54501938.jpg', '', '', '', ''),
(23, '437405', 'test123', 1, 2, 5, 4, 'enabled', 'asdqwe', 'IMG_65a0048c38da78.15335833.png', '', '', '', ''),
(24, '636402', 'test2135', 3, 3, 6, 4, 'enabled', 'asesafasfasfaf', 'IMG_65a00521112126.48306863.jpg', '', '', '', ''),
(25, '556645', 'gTpcwq', 1, 1, 5, 4, 'enabled', 'gTpcwq', 'IMG_65a0079dd76291.06280406.jpg', '', '', '', ''),
(26, '6769580', 'yahboom', 1, 3, 5, 4, 'enabled', 'Yahboom', 'IMG_65b51c6e55b933.21830352.jpg', '', '', '', ''),
(27, '2485134', 'laptop with charger', 1, 3, 5, 4, 'enabled', 'laptop with charger', 'IMG_65b5cc928ee556.54555165.jpg', 'good', 'container 1', '43,000', ''),
(28, '266957', 'pull up banner', 3, 3, 10, 1, 'enabled', 'dev kids - Pull Up Banner', 'IMG_65b6187b83ef60.26505028.jpg', 'good', 'container 2', '3000', ''),
(29, '648385', 'glue gun', 3, 3, 5, 1, 'enabled', 'Glue Gun', 'IMG_65b6217c798772.96700470.jpg', 'good', 'container 1', '120', './images/barcode/item1706434940.png'),
(31, '607550', 'papers ', 2, 1, 10, 1, 'enabled', 'Papers ', 'IMG_65b62479317950.06778313.jpg', 'good', 'container 3', '50', 'item1706435705.png'),
(32, '088981', 'Memory Card', 1, 3, 10, 4, 'enabled', '128gb . SanDisk', 'IMG_65b62cd616d248.80556829.jpg', 'good', 'container 3', '1000', 'item1706437846.png'),
(33, '792957', 'camera', 1, 3, 2, 1, 'enabled', 'SONY', 'IMG_65b62db46270a0.80610743.jpg', 'good', 'container 4', '40000', 'item1706438068.png');

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

--
-- Dumping data for table `item_feedback`
--

INSERT INTO `item_feedback` (`item_id`, `user_id`, `feedback`, `date_of_feedback`) VALUES
(20, 5, 'check', '2024-01-24 16:03:21'),
(20, 5, 'fast charging', '2024-01-24 16:04:16'),
(20, 4, 'recommend to borrow', '2024-01-24 16:27:59'),
(17, 5, 'test check check', '2024-01-26 03:01:20'),
(20, 5, 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Accusamus numquam assumenda hic aliquam vero sequi velit molestias doloremque molestiae dicta?', '2024-01-26 03:37:58'),
(4, 4, 'nice lace', '2024-01-27 15:09:49'),
(24, 4, 'lupet ni jake\n', '2024-01-27 18:47:28'),
(5, 5, 'check comment', '2024-01-28 02:22:17'),
(4, 5, 'nice lace', '2024-01-28 11:34:35');

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
(4, 'jay Ar', 'De Guzman', 'jdeguzman@gmail.com', '$2y$10$wk28/l2eJqickXsyQR6tZO43Dj279xxm.8otU9pPbeE7Bk/stzjxq', 1, 4, 'IMG_65b53e80615ca2.06937730.jpg', 'active'),
(5, 'Lee Angelo', 'Mollo', 'lamollo@gmail.com', '$2y$10$pzhfiX1L7fC723q5n6/xReHRBUrVLbdy2yuiOUsNG6Ay9Wjvr21Ey', 0, 4, 'IMG_6562d4d53d4b06.24348986.jpg', 'active'),
(6, 'John Moren', 'Dinela', 'jmdnl@gmail.com', '$2y$10$BJxuBj104cTfQrnc1qZXFO/Qq6ZcjfKoZ24pZLYFOIQkLAI8s0hP.', 0, 1, 'defaultProfile.jpg', 'inactive'),
(7, 'Jay Ar', 'De Guzman', 'jdg@gmail.com', '$2y$10$6EzlUhJvm1W3ZhSHd7BVqueSlkDXd56SVDZ1LKrDOhvlvcDS4V0EC', 0, 1, 'defaultProfile.jpg', 'active');

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
-- Indexes for table `ctochistory`
--
ALTER TABLE `ctochistory`
  ADD PRIMARY KEY (`history_id`);

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
-- AUTO_INCREMENT for table `ctochistory`
--
ALTER TABLE `ctochistory`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

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
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
