-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2024 at 05:52 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `netflix_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(20) NOT NULL,
  `video_id` int(20) NOT NULL,
  `user_id` int(20) NOT NULL,
  `Comment` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `video_id`, `user_id`, `Comment`) VALUES
(9, 79, 8, 'This is awesome movie\r\n'),
(10, 79, 8, 'new one added'),
(11, 131, 8, 'dskfbkjsdjdks'),
(12, 131, 10, 'efjkbdsjkbfdsfd'),
(13, 79, 8, 'this is omkar do great job by salman'),
(14, 79, 8, 'yash panchal nice movie');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `like` tinyint(1) NOT NULL,
  `dislike` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `video_id`, `user_id`, `like`, `dislike`) VALUES
(17, 79, 8, 0, 1),
(20, 130, 8, 0, 1),
(21, 131, 8, 0, 1),
(22, 131, 10, 0, 1),
(23, 79, 10, 1, 0),
(24, 79, 7, 0, 1),
(25, 131, 7, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `language` varchar(50) DEFAULT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `year` varchar(4) DEFAULT NULL,
  `video_link` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `Comment` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `title`, `language`, `genre`, `year`, `video_link`, `thumbnail`, `duration`, `Comment`) VALUES
(79, 'tiger 3', 'hindi', 'action', '2023', 'https://www.youtube.com/embed/Mba8qPq9jcs?si=oJMd9NWELVwKeOO3', 'tra.jpg', 200, ''),
(130, 'race', 'hindi', 'Action', '2022', 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4', 'toystory.jpg', 1234, ''),
(131, 'skyfall', 'English', 'Action', '2023', 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/TearsOfSteel.mp4', 'd9.jpg', 1251, '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(128) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`) VALUES
(1, 'Abhishek', 'kapadne-as@ulster.ac.uk', '$2y$10$oRQyP81Rp1Ti1jWjzPmrVOULtl5h3o.T4DdmYlWqh6qokfM15UiTq'),
(2, 'Vandan', 'vandan123@gmail.com', '$2y$10$7eQJeptdRZgWAlCGUkTTn.Y7bhk3dLUzsSuz49/cEwBgRTcyBo/Pm'),
(3, 'Abhishek Kapadne', 'abhishek.kapadnee@gmail.com', '$2y$10$9LuGF3dV63xjz9LzXj1S5eSUNFrZWxUwLWrklHjU3KtQlmFeLUtGi'),
(4, 'Avadh Soni ', 'avadhsoni67@gmail.com', '$2y$10$zkXd1rc9tF2xi6pb4lAaUO/HajrbXFeMvuEXoLEPEXMVOHrGqWc1y'),
(5, 'Abhishek', 'avadhsoni67q@gmail.com', '$2y$10$Mvyr.u879bdW9BErsf9QreueEG0fW8uhrrdlDcUDHM.NtcMSTt55i'),
(6, 'pranav', 'pranav@gmail.com', '$2y$10$tF2i5mRS0ssYF8WaPgaXLeELLKtutB3fLH8PB0b0Hw1ejFlR/qeFa'),
(7, 'pranav', 'pranav12@gmail.com', '$2y$10$gS4WwG077uNFcGHbNKlVJuIX0lhUymhqFXYZzSvK4UZqvoz6jGk8.'),
(8, 'yash panchal', 'yash12@gmail.com', '$2y$10$tFIJtDu4iO0dtJU/MHeKtOgwVIYjT70hHkfjqYFqjObCAReIX4lJq'),
(9, 'dharmik', 'dharmik123@gmail.com', '$2y$10$gOBCmX1leNVePRgFiHvoXOiyLhNSbFFmMiVB.fCaasqFO7U3iGwA.'),
(10, 'omkar', 'omkar123@gmial.com', '$2y$10$1CpjhP/f9pgOn/Amv5idMuiByGLgggwlBh4A1sfj8sQa0e/VxkAyq');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `video_link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `video_id` (`video_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`video_id`) REFERENCES `movies` (`id`),
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
