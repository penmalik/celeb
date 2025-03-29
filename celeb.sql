-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 29, 2025 at 10:22 AM
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
-- Database: `celeb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `admin_id` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `admin_id`, `email`, `password`, `reg_date`) VALUES
(1, 'adm_67e56e3a20427', 'admin@gmail.com', '$2y$10$p0C.ohSV7UxxcFFNs0yB5u4LlGHtGGiZwvB9IVCEQ2NDWrPSr52Fy', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `car_bookings`
--

CREATE TABLE `car_bookings` (
  `id` int(11) NOT NULL,
  `booking_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `car_name` varchar(255) NOT NULL,
  `car_image` text NOT NULL,
  `state` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `price` int(10) NOT NULL,
  `book_from` date NOT NULL,
  `book_until` date NOT NULL,
  `date_booked` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car_bookings`
--

INSERT INTO `car_bookings` (`id`, `booking_id`, `name`, `email`, `car_name`, `car_image`, `state`, `address`, `price`, `book_from`, `book_until`, `date_booked`) VALUES
(5, 'book_67e7b229808d0', 'Tiwa', 'tiwa@gmail.com', 'Range Rover Evoque', '1743237715.jpg', 'Lagos', 'Island Avenue 77', 4000000, '2025-03-28', '2025-04-03', '2025-03-29 09:41:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_id_unique` (`admin_id`);

--
-- Indexes for table `car_bookings`
--
ALTER TABLE `car_bookings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `car_bookings`
--
ALTER TABLE `car_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
