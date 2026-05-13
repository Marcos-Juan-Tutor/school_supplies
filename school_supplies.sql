-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
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
-- Database: `school_supplies`
--

-- --------------------------------------------------------

--
-- Table structure for table `supplies`
--

CREATE TABLE `supplies` (
  `id`          INT(11)          NOT NULL,
  `item`        VARCHAR(100)     DEFAULT NULL,
  `category`    VARCHAR(100)     DEFAULT NULL,
  `quantity`    INT(11)          DEFAULT NULL,
  `price`       DECIMAL(10, 2)   DEFAULT NULL,   -- fixed: was INT, now supports decimals
  `amount_sold` INT(11)          DEFAULT NULL,
  `revenue`     DECIMAL(10, 2)   DEFAULT NULL    -- fixed: was INT, now supports decimals
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplies`
-- Revenue corrected to: price × amount_sold
--

INSERT INTO `supplies` (`id`, `item`, `category`, `quantity`, `price`, `amount_sold`, `revenue`) VALUES
(3,  'pencil',      'pencil',      10, 5.00,  1,  5.00),   -- 5 × 1   = 5
(5,  'color paper', 'color paper', 20, 5.00,  10, 50.00),  -- 5 × 10  = 50
(6,  'bondpaper',   'bondpaper',   20, 25.00, 10, 250.00), -- 25 × 10 = 250
(7,  'eraser',      'eraser',      10, 4.00,  4,  16.00),  -- 4 × 4   = 16
(10, 'ballpen',     'ballpen',     20, 25.00, 1,  25.00);  -- 25 × 1  = 25

--
-- Indexes for table `supplies`
--

ALTER TABLE `supplies`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `supplies`

ALTER TABLE `supplies`
  ADD COLUMN `image` VARCHAR(255) DEFAULT NULL AFTER `revenue`;
--

ALTER TABLE `supplies`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
