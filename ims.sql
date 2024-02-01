-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 01, 2024 at 01:24 PM
-- Server version: 10.6.16-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u981678995_ims`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`user_id`, `item_id`, `inCart`, `date_added`, `date_out`) VALUES
(4, 32, 1, '2024-02-01 10:15:40', '2024-02-01 10:15:40');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ctochistory`
--

INSERT INTO `ctochistory` (`history_id`, `history_item_id`, `history_quantity`, `history_user_id`, `history_status`, `history_date`, `history_isReturn`, `history_date_return`, `history_due_date`, `from_chapter`, `to_chapter`) VALUES
(1, 4, 1, 1, 'delivered', '2024-01-31 14:33:54', 0, NULL, '2024-02-07', '1', '4'),
(2, 4, 1, 1, 'process', '2024-01-31 14:32:51', 0, NULL, '2024-02-07', '1', '4'),
(3, 4, 1, 1, 'process', '2024-01-31 14:32:54', 0, NULL, '2024-02-07', '1', '4');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`history_id`, `history_item_id`, `history_quantity`, `history_user_id`, `history_status`, `history_date`, `isReturned`, `history_date_return`, `history_due_date`) VALUES
(1, 32, 1, 4, 'approved', '2024-01-30 12:48:47', 1, '2024-01-30 8:48:47', '2024-02-02'),
(2, 32, 1, 4, 'approved', '2024-01-30 12:48:50', 1, '2024-01-30 8:48:50', '2024-02-02'),
(3, 35, 1, 1, 'approved', '2024-02-01 13:05:54', 1, '2024-01-30 8:48:52', '2024-02-02'),
(4, 31, 1, 6, 'approved', '2024-02-01 13:06:08', 1, '2024-01-30 8:48:54', '2024-02-02'),
(5, 32, 1, 4, 'approved', '2024-01-30 12:48:56', 1, '2024-01-30 8:48:56', '2024-02-02'),
(6, 32, 1, 4, 'approved', '2024-01-30 12:48:58', 1, '2024-01-30 8:48:58', '2024-02-02'),
(7, 27, 1, 5, 'approved', '2024-02-01 13:06:19', 1, '2024-01-30 8:49:00', '2024-02-02'),
(8, 32, 1, 4, 'approved', '2024-01-30 12:49:02', 1, '2024-01-30 8:49:02', '2024-02-02'),
(9, 32, 1, 4, 'approved', '2024-01-30 12:49:04', 1, '2024-01-30 8:49:04', '2024-02-02'),
(10, 32, 1, 4, 'approved', '2024-01-30 12:49:06', 1, '2024-01-30 8:49:06', '2024-02-02'),
(11, 32, 1, 4, 'approved', '2024-01-31 01:44:07', 1, '2024-01-31 9:44:07', '2024-02-02'),
(12, 32, 1, 4, 'approved', '2024-01-31 01:45:39', 1, '2024-01-31 9:45:39', '2024-02-02'),
(13, 32, 1, 4, 'approved', '2024-01-30 12:49:49', 0, '', '2024-02-02'),
(14, 36, 1, 2, 'approved', '2024-02-01 13:06:43', 0, '', '2024-02-02'),
(15, 28, 1, 6, 'approved', '2024-02-01 13:06:52', 0, '', '2024-02-02'),
(16, 33, 1, 4, 'approved', '2024-02-01 13:06:58', 0, '', '2024-02-02'),
(17, 4, 1, 4, 'approved', '2024-01-30 12:58:59', 0, '', '2024-02-02'),
(18, 4, 1, 4, 'approved', '2024-01-30 12:59:58', 0, '', '2024-02-02'),
(19, 4, 1, 1, 'approved', '2024-02-01 13:07:02', 0, '', '2024-02-02'),
(20, 32, 1, 4, 'approved', '2024-01-30 16:44:06', 0, '', '2024-02-03'),
(21, 32, 1, 4, 'approved', '2024-01-30 17:20:29', 1, '2024-01-31 1:20:29', '2024-02-03'),
(22, 32, 1, 4, 'approved', '2024-01-30 17:20:26', 1, '2024-01-31 1:20:26', '2024-02-03'),
(23, 32, 1, 4, 'approved', '2024-01-30 17:20:24', 1, '2024-01-31 1:20:24', '2024-02-03'),
(24, 32, 1, 4, 'approved', '2024-01-30 17:20:21', 1, '2024-01-31 1:20:21', '2024-02-03'),
(25, 32, 1, 4, 'approved', '2024-01-30 17:40:29', 1, '2024-01-31 1:40:29', '2024-02-03'),
(26, 32, 1, 4, 'approved', '2024-01-30 17:40:26', 1, '2024-01-31 1:40:26', '2024-02-03'),
(27, 32, 1, 4, 'approved', '2024-01-30 17:40:22', 1, '2024-01-31 1:40:22', '2024-02-03'),
(28, 32, 1, 4, 'approved', '2024-01-30 17:40:20', 1, '2024-01-31 1:40:20', '2024-02-03'),
(29, 32, 1, 4, 'approved', '2024-01-31 01:44:00', 1, '2024-01-31 9:44:00', '2024-02-03'),
(30, 32, 1, 4, 'approved', '2024-01-31 01:45:32', 1, '2024-01-31 9:45:32', '2024-02-03'),
(31, 32, 1, 4, 'approved', '2024-01-31 01:38:16', 1, '2024-01-31 9:38:16', '2024-02-03'),
(32, 5, 1, 4, 'approved', '2024-02-01 10:17:02', 1, '2024-02-01 6:17:02', '2024-02-03'),
(33, 32, 1, 4, 'approved', '2024-02-01 10:17:23', 1, '2024-02-01 6:17:23', '2024-02-05'),
(34, 39, 1, 1, 'approved', '2024-02-01 13:18:24', 0, '', '2024-02-04');

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
  `barcode_img` varchar(60) NOT NULL,
  `date_acquired` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `unique_item_id`, `item_name`, `item_category`, `item_measure`, `item_quantity`, `item_chapter`, `item_status`, `item_description`, `item_image`, `item_condition`, `item_location`, `item_cost`, `barcode_img`, `date_acquired`) VALUES
(1, '335733', 'Galaxy Tab A Tablet', 1, 3, 138, 3, 'enabled', '4gb 256gb color black', 'IMG_65b6ef174c0736.96179317.jpg', '', 'Container 1', '', '', '2024-02-01 10:13:28'),
(2, '2792718', 'A4 Bond Paper', 3, 2, 2, 2, 'enabled', 'A4 Hardcopy', 'IMG_65b6ef565740b1.02912169.jpg', '', 'Container 2', '', '', '2024-01-31 12:21:43'),
(4, '976760', 'ID Lace version 5', 3, 3, 27, 4, 'enabled', 'Version 5, Black & Orange', 'IMG_65b6f234396e25.48633346.jpg', '', 'Container 3', '', '', '2024-02-01 13:07:02'),
(5, '7542994', 'Jumping wires long', 3, 1, 30, 4, 'enabled', 'Rainbow colors', 'IMG_65b6ef8adbd544.13403523.jpg', '', 'Container 4', '', '', '2024-02-01 10:17:02'),
(7, '275921', 'Gel Ink Pen ', 3, 1, 29, 5, 'enabled', 'Black', 'IMG_65b6f037ad2397.33467649.jpg', '', 'Container 1', '', '', '2024-01-31 12:21:58'),
(26, '6769580', 'yahboom', 1, 3, 5, 4, 'enabled', 'Yahboom', 'IMG_65b6efa698ae38.87036472.jpg', '', 'Container 2', '', '', '2024-01-31 12:22:01'),
(27, '2485134', 'laptop with charger', 1, 3, 6, 4, 'enabled', 'laptop with charger', 'IMG_65b5cc928ee556.54555165.jpg', 'good', 'Container 1', '43,000', '', '2024-02-01 13:06:19'),
(28, '266957', 'pull up banner', 3, 3, 11, 1, 'enabled', 'dev kids - Pull Up Banner', 'IMG_65b6187b83ef60.26505028.jpg', 'good', 'Container 2', '3000', '', '2024-02-01 13:06:52'),
(29, '648385', 'glue gun', 3, 3, 5, 1, 'enabled', 'Glue Gun', 'IMG_65b6217c798772.96700470.jpg', 'good', 'Container 1', '120', './images/barcode/item1706434940.png', '2024-01-31 12:22:10'),
(31, '607550', 'papers ', 2, 1, 17, 1, 'enabled', 'Papers ', 'IMG_65b62479317950.06778313.jpg', 'good', 'Container 3', '50', 'item1706435705.png', '2024-02-01 13:06:08'),
(32, '088981', 'Memory Card', 1, 3, 13, 4, 'enabled', '128gb . SanDisk', 'IMG_65b62cd616d248.80556829.jpg', 'good', 'Container 3', '1000', 'item1706437846.png', '2024-02-01 13:06:47'),
(33, '792957', 'camera', 1, 3, 6, 1, 'enabled', 'SONY', 'IMG_65b62db46270a0.80610743.jpg', 'good', 'Container 4', '40000', 'item1706438068.png', '2024-02-01 13:06:58'),
(34, '172154', 'ballpen', 3, 3, 12, 4, 'enabled', 'Panda Ballpen Black', 'IMG_65b9c8e2d17a45.91293172.jpg', 'good', 'container 1', '10', 'item1706674402.png', '2024-01-31 04:15:49'),
(35, '152909', 'Mobile phone', 1, 3, 8, 4, 'enabled', 'Realme Phones', 'IMG_65b9c9e7931e66.39339155.png', 'Good', 'container 4', '3000', 'item1706674663.png', '2024-02-01 13:05:54'),
(36, '564921', 'Gel Ink Pen ', 3, 3, 12, 4, 'enabled', 'Gell Ink Pen - Black', 'IMG_65b9ccb183c296.66663381.jpg', 'good', 'container 4', '10', 'item1706675377.png', '2024-02-01 13:06:43'),
(37, '9310182', 'Ipad ', 1, 3, 2, 4, 'enabled', 'IPAD - 128gb', 'IMG_65b9d3e6e3c0d9.72434804.jpg', 'good', 'container 1', '40000', 'item1706677222.png', '2024-02-01 10:13:28'),
(38, '4035754', 'Cup', 2, 1, 1, 1, 'enabled', 'Paper Cup', 'IMG_65bb991cc0ea69.70912256.jpeg', 'Good', 'Cabinet 1', '80', 'item1706793244.png', '2024-02-01 13:14:04'),
(39, '4035754', 'Notebook', 3, 3, 0, 1, 'enabled', 'Notebook for notes', 'IMG_65bb99fd6da422.04554679.jpeg', 'Good', 'Cabinet 1', '80', 'item1706793245.png', '2024-02-01 13:18:24');

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
-- Table structure for table `item_feedback`
--

CREATE TABLE `item_feedback` (
  `item_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `feedback` varchar(225) NOT NULL,
  `date_of_feedback` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(4, 5, 'nice lace', '2024-01-28 11:34:35'),
(32, 4, 'memo ', '2024-01-30 14:13:34');

-- --------------------------------------------------------

--
-- Table structure for table `item_location`
--

CREATE TABLE `item_location` (
  `location_id` int(11) NOT NULL,
  `location_name` varchar(50) NOT NULL,
  `container_name` varchar(30) NOT NULL,
  `chapter` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_location`
--

INSERT INTO `item_location` (`location_id`, `location_name`, `container_name`, `chapter`) VALUES
(1, 'Room 1', 'Container 1', 4),
(2, 'Room 1', 'Container 2', 4),
(3, 'Room 1', 'Container 3', 4),
(4, 'Room 1', 'Container 4', 4),
(5, 'Room 1', 'Container 5', 4),
(6, 'Room 1', 'Container 6', 4),
(7, 'Room 1', 'Container 7', 3),
(8, 'Room 1', 'Container 8', 2),
(9, 'Room 2', 'Container 1', 1),
(10, 'room2', 'Container 2', 1);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `log_id` int(11) NOT NULL,
  `log_user` int(11) NOT NULL,
  `log_type` enum('modify','return','request','add','') NOT NULL,
  `log_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `user_status` enum('active','inactive') NOT NULL,
  `user_position` varchar(255) NOT NULL,
  `user_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_firstname`, `user_lastname`, `user_email`, `user_password`, `user_category`, `user_chapter`, `user_image`, `user_status`, `user_position`, `user_code`) VALUES
(1, 'Jake', 'Maangas', 'jakemantesnapay@gmail.com', '$2y$10$RIQWqDTz88hS713D1XbPIOAiicKGkmTpUr4FJnMSquvh4SbuGUj5S', 1, 1, 'IMG_65bb96ef251dc0.19518553.jpg', 'active', 'DEVCON Manila Main Admin', 0),
(2, 'john moren', 'dinela', 'jmdinela@gmail.com', '$2y$10$1o24hhJ6ibvCFNhaoD9jaeQW9PS59s7lDV3f2MtS3Uv3bLNhLqtJm', 0, 2, 'IMG_6562cc23d0aa35.57042786.jpg', 'active', '', 0),
(4, 'jay Ar', 'De Guzman', 'deguzmanjayar9@gmail.com', '$2y$10$j7dAlO.jJHJN4oScGaEZYeiP/TGC2.qOZoW/70ERoDWCYB7qugtw.', 1, 4, 'IMG_65b900f5db5930.59217230.png', 'active', '', 3293),
(5, 'Lee Angelo', 'Mollo', 'lamollo@gmail.com', '$2y$10$pzhfiX1L7fC723q5n6/xReHRBUrVLbdy2yuiOUsNG6Ay9Wjvr21Ey', 0, 4, 'IMG_6562d4d53d4b06.24348986.jpg', 'active', '', 0),
(6, 'John Moren', 'Dinela', 'jmdnl@gmail.com', '$2y$10$BJxuBj104cTfQrnc1qZXFO/Qq6ZcjfKoZ24pZLYFOIQkLAI8s0hP.', 0, 1, 'defaultProfile.jpg', 'inactive', '', 0),
(7, 'Jay Ar', 'De Guzman', 'jdg@gmail.com', '$2y$10$6EzlUhJvm1W3ZhSHd7BVqueSlkDXd56SVDZ1LKrDOhvlvcDS4V0EC', 0, 1, 'defaultProfile.jpg', 'active', '', 0);

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
-- Indexes for table `item_location`
--
ALTER TABLE `item_location`
  ADD PRIMARY KEY (`location_id`);

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
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

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
-- AUTO_INCREMENT for table `item_location`
--
ALTER TABLE `item_location`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
