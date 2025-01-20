-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8005
-- Generation Time: Jan 19, 2025 at 08:49 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `storedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `OrderID` int NOT NULL,
  `UserID` int NOT NULL,
  `DateOrder` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `TotalPrice` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `OrderItemID` int NOT NULL,
  `OrderID` int NOT NULL,
  `ProductID` int NOT NULL,
  `Quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ProductID` int NOT NULL,
  `ProductName` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `ProductImage` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `productDescription` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'gjdgdjggjfhj'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ProductID`, `ProductName`, `Price`, `ProductImage`, `productDescription`) VALUES
(5, 'Regina Patel', '933.00', '1737229862_لقطة شاشة 2024-11-21 232235.png', 'In aliquip nihil inc'),
(6, 'Hu Atkins', '893.00', '1737229871_لقطة شاشة 2024-11-21 232556.png', 'Iusto nihil occaecat'),
(7, 'Ivan Lester', '719.00', '1737229878_لقطة شاشة 2024-11-21 232646.png', 'Aliqua Qui maxime q'),
(8, 'Elton Clark', '953.00', '1737234389_لقطة شاشة 2024-11-21 202553.png', 'Ad et assumenda aute'),
(9, 'Dawn Nieves', '477.00', '1737234409_لقطة شاشة 2024-11-20 012241.png', 'Omnis aspernatur qua'),
(10, 'Dora Fields', '521.00', '1737302751_لقطة شاشة 2024-11-21 232646.png', 'Aliquam et quis omni'),
(11, 'Rae Marsh', '946.00', '1737302759_لقطة شاشة 2024-11-21 212700.png', 'Rerum pariatur Odio');

--
-- Triggers `products`
--
DELIMITER $$
CREATE TRIGGER `check_price_before_insert` BEFORE INSERT ON `products` FOR EACH ROW BEGIN
    IF NEW.Price <= 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Price must be greater than 0';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserName` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `UserID` int NOT NULL,
  `Password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `RoomNumber` int NOT NULL,
  `ProfileImage` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('user','admin') COLLATE utf8mb4_general_ci DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserName`, `UserID`, `Password`, `RoomNumber`, `ProfileImage`, `Email`, `role`) VALUES
('Walker Becker', 8, 'Pa$$w0rd!', 453, '1737304656_لقطة شاشة 2024-11-21 212700.png', 'cekukujygy@mailinator.com', 'user'),
('Lance Lawson', 9, 'Pa$$w0rd!', 519, '1737305334_لقطة شاشة 2024-11-21 232435.png', 'pelicowil@mailinator.com', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `fk_user_order` (`UserID`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`OrderItemID`),
  ADD KEY `OrderID` (`OrderID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ProductID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `OrderID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `OrderItemID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk_order_user` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `order` (`OrderID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
