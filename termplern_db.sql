-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 31, 2025 at 02:14 PM
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
-- Database: `termplern_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_pic` varchar(255) DEFAULT 'icon_default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `profile_pic`) VALUES
(10, 'jame_24k', 'test2@example.com', '$2y$10$TeJhBINM36dwg2FY7yFm6uHW9Ib2kxPYsKDAR6sW4ObyWlVm/htem', '1738327362_297593980_996760891723007_1687441400993631697_n.png'),
(11, 'test3', 'test3@example.com', '$2y$10$6LXmP5AjYkOOzRVfnHD41erxcKfT9bIkzwJFcsSHNq/5GjVF3uV4O', 'icon_default.jpg'),
(12, 'test6', 'test6@example.com', '$2y$10$w6qcg/rOHwV4M9sLwhTUmuj/AaKoovdcvhah7fQf670ZklLebhwIG', 'icon_default.jpg'),
(13, 'test5', 'test5@example.com', '$2y$10$5vKyHx.pcTrzhgMkWrMg4u.mOoCUiDgL60xVonkguv7ucfCU7DCJq', 'icon_default.jpg'),
(14, 'test2', 'test4@example.com', '$2y$10$L/1ZDGGVlE70mQVYve87rOpkOsQ7HzVWPOGLmwvLs96.KyqAQLSge', 'icon_default.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
