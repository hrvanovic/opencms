-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 19, 2021 at 05:03 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `opencms`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `u_id` int(11) NOT NULL,
  `u_email` tinytext NOT NULL,
  `u_phone` tinytext DEFAULT NULL,
  `u_lastname` tinytext DEFAULT NULL,
  `u_password` tinytext NOT NULL,
  `u_regdate` varchar(255) NOT NULL,
  `u_firstname` tinytext DEFAULT NULL,
  `u_avatar` int(11) DEFAULT NULL,
  `u_thumbnail` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`u_id`, `u_email`, `u_phone`, `u_lastname`, `u_password`, `u_regdate`, `u_firstname`, `u_avatar`, `u_thumbnail`) VALUES
(1, 'mirza@blixmark.comma', '0603065634', 'Hrvanovicj', '$2y$10$W.pZ7a1BQiimDQs19zPE0us..RAflwnUkuQh/kRr10D0jqmCYfKKa', '1612882731', 'Mirza ', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_con`
--

CREATE TABLE `users_con` (
  `c_id` int(11) NOT NULL,
  `c_user` int(11) NOT NULL,
  `c_type` tinyint(4) NOT NULL,
  `c_value` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users_fa`
--

CREATE TABLE `users_fa` (
  `fa_id` int(11) NOT NULL,
  `fa_user` int(11) NOT NULL,
  `fa_code` text NOT NULL,
  `fa_reccode` text NOT NULL,
  `fa_status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_fa`
--

INSERT INTO `users_fa` (`fa_id`, `fa_user`, `fa_code`, `fa_reccode`, `fa_status`) VALUES
(1, 1, 'F7WEITK24H3UE4CU', 'a,a,a,a,a,a', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_files`
--

CREATE TABLE `users_files` (
  `uf_id` int(11) NOT NULL,
  `uf_user` int(11) NOT NULL,
  `uf_type` tinyint(4) NOT NULL,
  `uf_path` text NOT NULL,
  `uf_ext` tinytext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_files`
--

INSERT INTO `users_files` (`uf_id`, `uf_user`, `uf_type`, `uf_path`, `uf_ext`) VALUES
(1, 1, 0, 'asd', 'jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users_ses`
--

CREATE TABLE `users_ses` (
  `s_id` int(11) NOT NULL,
  `s_user` int(11) NOT NULL,
  `s_key` mediumtext NOT NULL,
  `s_agent` mediumtext DEFAULT NULL,
  `s_expired` int(11) DEFAULT NULL,
  `s_status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_ses`
--

INSERT INTO `users_ses` (`s_id`, `s_user`, `s_key`, `s_agent`, `s_expired`, `s_status`) VALUES
(1, 1, 'd0f6809b91cb1b306eb05e78340ca101', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:85.0) Gecko/20100101 Firefox/85.0', 1644418902, 1),
(2, 1, 'b397c34b62f538089d89e0d5adab9fa7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36', 1644419984, 0),
(3, 1, '7653118c2c27ca5bea91c005fb33e699', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36', 1644441490, 0),
(4, 1, '863c7c27261bc1ffe056d1d876442231', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36', 1644441545, 0),
(5, 1, 'ffae80a655f9c8819be433d384725a03', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36', 1644496789, 0),
(6, 1, '51f4c0858568ac909ec70d57ff39643e', 'Mozilla/5.0 (Linux; Android 9; LG-H870) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.152 Mobile Safari/537.36', 1644506095, 1),
(7, 1, '6d5747e30604a547baab4f160e8d7778', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36', 1644614455, 0),
(8, 1, 'faf98dc23658e8c92e6782c1faf87418', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36', 1644614561, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`u_id`);

--
-- Indexes for table `users_con`
--
ALTER TABLE `users_con`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `users_fa`
--
ALTER TABLE `users_fa`
  ADD PRIMARY KEY (`fa_id`);

--
-- Indexes for table `users_files`
--
ALTER TABLE `users_files`
  ADD PRIMARY KEY (`uf_id`);

--
-- Indexes for table `users_ses`
--
ALTER TABLE `users_ses`
  ADD PRIMARY KEY (`s_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users_con`
--
ALTER TABLE `users_con`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_fa`
--
ALTER TABLE `users_fa`
  MODIFY `fa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users_files`
--
ALTER TABLE `users_files`
  MODIFY `uf_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users_ses`
--
ALTER TABLE `users_ses`
  MODIFY `s_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
