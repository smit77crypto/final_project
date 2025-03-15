-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 07, 2025 at 06:45 PM
-- Server version: 10.11.8-MariaDB-0ubuntu0.24.04.1
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `getinplay`
--

-- --------------------------------------------------------

--
-- Table structure for table `book_game`
--

CREATE TABLE `book_game` (
  `id` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `phone_no` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `game_name` varchar(100) NOT NULL,
  `slot` text NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 1,
  `book_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_game`
--

INSERT INTO `book_game` (`id`, `username`, `phone_no`, `email`, `game_name`, `slot`, `deleted`, `book_date`) VALUES
(1, 'bob123', '4768345448', 'bob12@gmail.com', '8 Ball Pool', '10:00-10:30AM', 1, '2025-03-06'),
(2, 'alex123', '2437562435', 'alex12@gmail.com', '8 ball pool', '3:00-4:00PM', 1, '2025-03-06'),
(3, 'abc', '1212121212', 'abc@gmail.com', '8 ball pool', '7:00-7:30PM', 1, '2025-03-06'),
(4, 'saw', '2354765423', 'saw@gmail.com', '8 ball pool', '9:00-10:00PM', 1, '2025-03-06'),
(5, 'dadAD', '5443562354', 'dadAD@gmail.com', '8 ball pool', '10:30-11:00PM', 1, '2025-03-08'),
(6, 'dadAD', '5443562354', 'dadAD@gmail.com', '8 ball pool', '5:00-6:00PM', 1, '2025-03-08'),
(7, 'dadA', '5443562354', 'dadAD@gmail.com', '8 ball pool', '10:30-11:00PM', 1, '2025-03-08'),
(8, 'daD', '5443562354', 'dadAD@gmail.com', '8 ball pool', '5:00-6:00PM', 1, '2025-03-08'),
(9, 'sham123', '1231231231', 'sohan@gmail.com', 'snooker', '12:00-1:00PM', 1, '2025-03-06'),
(28, 'sham123', '1231231231', 'sohan@gmail.com', '8 Ball Pool', '3:30-4:00PM', 1, '2025-03-08'),
(29, 'sham123', '1231231231', 'sohan@gmail.com', '8 Ball Pool', '2:30-3:00PM', 1, '2025-03-09'),
(44, 'getinplay123', '7990153071', 'sohan.21beitg119@gmail.com', '8 Ball Pool', '10:00-10:30AM', 1, '2025-03-07'),
(45, 'sham123', '1231231231', 'sohan@gmail.com', '8 Ball Pool', '3:00-4:00PM', 1, '2025-03-07'),
(46, 'getinplay123', '7990153071', 'sohan.21beitg119@gmail.com', '8 Ball Pool', '9:00-10:00PM', 1, '2025-03-07'),
(47, 'getinplay123', '7990153071', 'sohan.21beitg119@gmail.com', '8 Ball Pool', '10:00-11:00AM', 1, '2025-03-17'),
(48, 'getinplay123', '7990153071', 'sohan.21beitg119@gmail.com', '8 Ball Pool', '11:30-12:00PM', 1, '2025-03-07'),
(49, 'sham123', '1231231231', 'sohan@gmail.com', 'Snooker', '12:30-1:00PM', 1, '2025-03-07'),
(50, 'sham123', '1231231231', 'sohan@gmail.com', 'Snooker', '4:00-4:30PM', 1, '2025-03-07'),
(51, 'abc_123', '1212121212', 'sohan.21beitg119@gmail.com', 'Bowling', '11:00-11:30AM', 1, '2025-03-07'),
(52, 'swati123', '9345678901', 'swati4@gmail.com', 'Badminton', '10:00-10:30AM', 1, '2025-03-09'),
(53, 'getinplay123', '7990153071', 'sohan.21beitg119@gmail.com', '8 Ball Pool', '1:30-2:00PM', 1, '2025-03-07'),
(54, 'getinplay123', '7990153071', 'sohan.21beitg119@gmail.com', '8 Ball Pool', '9:00-10:00PM', 1, '2025-03-09'),
(55, 'abc_123', '1212121212', 'sohan.21beitg119@gmail.com', 'Badminton', '10:00-10:30AM', 1, '2025-03-07'),
(56, 'sham123', '1231231231', 'sohan@gmail.com', '8 Ball Pool', '11:30-12:00PM', 1, '2025-03-08'),
(57, 'getinplay123', '7990153071', 'sohan.21beitg119@gmail.com', 'Bowling', '4:00-4:30PM', 1, '2025-03-07'),
(58, 'getinplay123', '7990153071', 'sohan.21beitg119@gmail.com', 'Bowling', '5:30-6:00PM', 1, '2025-03-07'),
(59, 'sham123', '1231231231', 'sohan@gmail.com', '8 Ball Pool', '4:00-4:30PM', 1, '2025-03-07'),
(60, 'sham123', '1231231231', 'sohan@gmail.com', '8 Ball Pool', '10:30-11:00PM', 1, '2025-03-07'),
(61, 'sham123', '1231231231', 'sohan@gmail.com', 'Snooker', '10:00-11:00PM', 1, '2025-03-07'),
(62, 'sham123', '1231231231', 'sohan@gmail.com', 'Snooker', '10:00-11:00AM', 1, '2025-03-08'),
(63, 'sham123', '1231231231', 'sohan@gmail.com', 'Snooker', '12:30-1:00PM', 1, '2025-03-08'),
(64, 'sham123', '1231231231', 'sohan@gmail.com', 'Snooker', '5:30-6:00PM', 1, '2025-03-08'),
(65, 'sham123', '1231231231', 'sohan@gmail.com', 'Snooker', '7:30-8:00PM', 1, '2025-03-08'),
(66, 'sohan_1123', '9664691917', 'smitbarot2004@gmail.com', 'Bowling', '3:00-3:30PM', 1, '2025-03-07'),
(67, 'sohan_1123', '9664691917', 'smitbarot2004@gmail.com', 'Bowling', '8:00-8:30PM', 1, '2025-03-07'),
(68, 'sohan_1123', '9664691917', 'smitbarot2004@gmail.com', 'Bowling', '7:00-8:00PM', 1, '2025-03-07'),
(69, 'sohan_1123', '9664691917', 'smitbarot2004@gmail.com', 'Bowling', '7:30-8:00PM', 1, '2025-03-07'),
(70, 'sohan_1123', '9664691917', 'smitbarot2004@gmail.com', 'Bowling', '10:00-11:00PM', 1, '2025-03-07'),
(71, 'sohan_1123', '9664691917', 'smitbarot2004@gmail.com', 'Bowling', '8:00-9:00PM', 1, '2025-03-07'),
(72, 'sohan_1123', '9664691917', 'smitbarot2004@gmail.com', 'Snooker', '7:00-8:00PM', 1, '2025-03-07'),
(73, 'sohan_1123', '9664691917', 'smitbarot2004@gmail.com', 'Snooker', '8:00-8:30PM', 1, '2025-03-07'),
(74, 'getinplay123', '7990153071', 'sohan.21beitg119@gmail.com', '8 Ball Pool', '10:00-10:30AM', 1, '2025-03-08'),
(75, 'getinplay123', '7990153071', 'sohan.21beitg119@gmail.com', '8 Ball Pool', '7:00-7:30PM', 1, '2025-03-07'),
(76, 'getinplay123', '7990153071', 'sohan.21beitg119@gmail.com', '8 Ball Pool', '10:00-11:00AM', 1, '2025-03-07'),
(77, 'getinplay123', '7990153071', 'sohan.21beitg119@gmail.com', '8 Ball Pool', '10:30-11:00PM', 1, '2025-03-09'),
(78, 'getinplay123', '7990153071', 'sohan.21beitg119@gmail.com', '8 Ball Pool', '10:00-10:30AM', 1, '2025-03-09'),
(79, 'getinplay123', '7990153071', 'sohan.21beitg119@gmail.com', '8 Ball Pool', '4:00-4:30PM', 1, '2025-03-08'),
(80, 'getinplay123', '7990153071', 'sohan.21beitg119@gmail.com', '8 Ball Pool', '1:30-2:00PM', 1, '2025-03-08'),
(81, 'sohan_1123', '9664691917', 'smitbarot2004@gmail.com', 'Chess', '7:30-8:00PM', 1, '2025-03-09'),
(82, 'sham123', '1231231231', 'sohan@gmail.com', 'Snooker', '4:00-4:30PM', 1, '2025-03-08'),
(83, 'sohan_1123', '9664691917', 'smitbarot2004@gmail.com', 'Bowling', '9:00-9:30PM', 1, '2025-03-07'),
(84, 'sohan_1123', '9664691917', 'smitbarot2004@gmail.com', 'Bowling', '11:00-11:30PM', 1, '2025-03-07'),
(85, 'swati123', '9345678901', 'swati4@gmail.com', 'Bowling', '2:00-3:00PM', 1, '2025-03-09');

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `hour` decimal(10,2) NOT NULL,
  `half_hour` decimal(10,2) NOT NULL,
  `card_image` varchar(100) NOT NULL,
  `slider_image` varchar(100) NOT NULL,
  `slots` text NOT NULL,
  `filter_value` text NOT NULL,
  `deleteval` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `name`, `hour`, `half_hour`, `card_image`, `slider_image`, `slots`, `filter_value`, `deleteval`) VALUES
(1, '8 Ball Pool', 150.00, 90.00, 'uploads/pool.png', 'uploads/sliderpool.jpg', '10:00-10:30AM,11:30-12:00PM,10:00-11:00AM,12:00-12:30PM,1:30-2:00PM,2:30-3:00PM,3:30-4:00PM,1:00-2:00PM,3:00-4:00PM,4:00-4:30PM,5:30-6:00PM,7:00-7:30PM,5:00-6:00PM,8:00-8:30PM,10:30-11:00PM,9:00-10:00PM', '30min,30min,1hr,30min,30min,30min,30min,1hr,1hr,30min,30min,30min,1hr,30min,30min,1hr', 1),
(2, 'Snooker', 180.00, 100.00, 'uploads/snooker.jpeg', 'uploads/slidersnooker.jpeg', '10:30-11:00AM,10:00-11:00AM,12:30-1:00PM,1:00-1:30PM,3:00-3:30PM,2:00-3:00PM,3:00-4:00PM,4:00-4:30PM,5:30-6:00PM,7:30-8:00PM,7:00-8:00PM,8:00-8:30PM,9:00-9:30PM,11:00-11:30PM,8:00-9:00PM,10:00-11:00PM', '30min,1hr,30min,30min,30min,1hr,1hr,30min,30min,30min,1hr,30min,30min,30min,1hr,1hr', 1),
(3, 'Badminton', 120.00, 70.00, 'uploads/badminton.jpeg', 'uploads/sliderbadminton.jpg', '10:00-10:30AM,3:00-3:30PM,2:00-3:00PM,3:00-4:00PM,4:00-4:30PM,5:30-6:00PM,7:30-8:00PM,7:00-8:00PM,8:00-8:30PM,9:00-9:30PM,11:00-11:30PM,8:00-9:00PM,10:00-11:00PM', '30min,30min,1hr,1hr,30min,30min,30min,1hr,30min,30min,30min,1hr,1hr', 1),
(4, 'Chess', 100.00, 60.00, 'uploads/chess.jpeg', 'uploads/sliderchess.jpeg', '10:00-10:30AM,3:00-3:30PM,2:00-3:00PM,3:00-4:00PM,4:00-4:30PM,5:30-6:00PM,7:30-8:00PM,7:00-8:00PM,8:00-8:30PM,9:00-9:30PM,11:00-11:30PM,8:00-9:00PM,10:00-11:00PM', '30min,30min,1hr,1hr,30min,30min,30min,1hr,30min,30min,30min,1hr,1hr', 1),
(5, 'Bowling', 180.00, 100.00, 'uploads/bowling.jpeg', 'uploads/sliderbowling.jpg', '11:00-11:30AM,3:00-3:30PM,2:00-3:00PM,3:00-4:00PM,4:00-4:30PM,5:30-6:00PM,7:30-8:00PM,7:00-8:00PM,8:00-8:30PM,9:00-9:30PM,11:00-11:30PM,8:00-9:00PM,10:00-11:00PM', '30min,30min,1hr,1hr,30min,30min,30min,1hr,30min,30min,30min,1hr,1hr', 1),
(6, '00000', 100.00, 50.00, 'uploads/rick.jpg', 'uploads/rick.jpg', '10:00-10:30AM,11:00-11:30AM', '', 0),
(7, 'dip', 120.00, 60.00, 'uploads/Screenshot from 2025-03-05 17-49-01.png', 'uploads/Screenshot from 2025-03-05 17-46-34.png', '10:00-10:30AM', '30min', 0);

-- --------------------------------------------------------

--
-- Table structure for table `membership`
--

CREATE TABLE `membership` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `membership`
--

INSERT INTO `membership` (`id`, `name`) VALUES
(1, 'Normal'),
(2, 'Silver'),
(3, 'Gold');

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` text NOT NULL,
  `phone_no` text NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `username` varchar(100) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  `membership_id` int(11) NOT NULL,
  `deleteval` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`id`, `full_name`, `email`, `phone_no`, `gender`, `username`, `user_password`, `membership_id`, `deleteval`) VALUES
(1, 'sohan prajapati', 'sohan1@gmail.com', '9876543210', 'Male', 'hello', '12341234', 1, 1),
(2, 'smit barot', 'smit2@gmail.com', '9123456789', 'Male', 'xyz123', '$2y$10$aO2wKmuBvXYLZd6ZE0eYW.0oou9p..r2jLd4f70ZWrX8dp7B7VZPu', 2, 1),
(3, 'rudra', 'rudra3@ac.in', '9234567890', 'Female', 'xyzabc', '$2y$10$O92G4gonuWMBKJ75SQrHxeB5GBrvX3nx95BjeWO8RKIxJlYiAf3NG', 1, 0),
(4, 'swati', 'swati4@gmail.com', '9345678901', 'Male', 'swati123', '12121212', 3, 1),
(5, 'abc es', 'abc5@gmail.com', '9456789012', 'Male', 'bob1234', '12121212', 2, 0),
(6, 'bob1', 'bob6@gmail.com', '9567890123', 'Male', 'root', '12121212', 3, 0),
(7, 'bob3', 'bob7@gmail.com', '9678901234', 'Male', 'bob_123', '12121212', 3, 1),
(8, 'bob', 'bob8@gmail.com', '9789012345', 'Male', 'bob123', '12121212', 2, 1),
(9, 'omen', 'omen9@gmail.com', '9890123456', 'Male', 'omen12', '$2y$10$YtUcZ3dcH9qEEmSYlYd5heYIe92RupiXeVITsjdAQ0mjqlCwmWtFa', 1, 1),
(10, 'sohan aeswf', 'sohan10@gmail.com', '9901234567', 'Male', 'bob123', '$2y$10$kNtCLPr/AdjYW.IX9LyHVumlHdlHfEropk5rd1FrjhBYSXW8JPwa2', 1, 1),
(11, 'sohan', 'sohan11@gmail.com', '9912345678', 'Male', 'bob123', '$2y$10$ZIjzrhFAlADV99lI6Zs9/etEjD0QQal4MWYq5m0JrOGV.iV8Ljy86', 1, 0),
(12, 'sohan', 'sohan12@gmail.com', '9923456789', 'Male', 'bob123', '$2y$10$sounaLpdAh/aFP6tyAH5m.5UCzzafOrucQlVgGvYy8sa1POzDL5EO', 1, 1),
(13, 'aaafg', 'aaafg13@ac.in', '9934567890', 'Female', 'bob1234', '$2y$10$1VdxlZ.RZExS4CEbRSW.9ONy95W7EGPeXG/P9.7kriEFE3F6FE/Xa', 3, 1),
(14, 'awew', 'awew14@gmail.com', '9945678901', 'Male', 'bob12345dff', '$2y$10$QhpdzRMi1M8kV7vZWNPuoONDleYEwjhWObr3umn6ocpcfxpBijL/u', 2, 1),
(15, 'hngnn', 'hngnn15@gmail.com', '9956789012', 'Female', 'bob123', '$2y$10$wRkXjyIfn47PlKnpGwSmLu8HOdFB.T2bjM2V8JXFVMHZB6MLjsBwy', 1, 1),
(16, 'hello', 'hello16@gmail.com', '9967890123', 'Male', 'bob123455', '$2y$10$eC4c6njuz6HSFmCPqrVATeuXaXiHiycsUgyy3HVC8C55fzZqZJwq2', 1, 1),
(17, 'svit', 'svit17@gmail.com', '9978901234', 'Male', 'svit124323', '$2y$10$nDHH72Gz3GoF4TnzBX5CPunrDBc2rSX.7JsbxrYxfjphIs3dMDZHy', 3, 1),
(18, 'swdsws', 'swdsws18@gmail.com', '9989012345', 'Male', 'gfbhdh', '$2y$10$0Sgo85z839m9rdgYtIoNUuGgP6Eb5ZPyu40WpRr.W/pjA1INfEqFK', 1, 1),
(20, 'sohan', 'sohan20@gmail.com', '9990123456', 'Male', 'sohan_11903', '$2y$10$FyIlHc/lyQ5zdIuWxFSlq.NCmzUY8Y0ODl/RQOmITTTU9nR1gIBKC', 3, 1),
(21, 'svit', 'svit21@gmail.com', '9001234567', 'Male', 'svit_123', '$2y$10$9ydE1JE/XJUOy4v1bV3xxO2igSFifBFeW4Xrwv52Yh7ovTU7O6WiW', 1, 1),
(22, 'cdsb', 'cdsb22@gmail.com', '9012345678', 'Male', 'cdsdscssc', '$2y$10$ByhuDcDkZgaSOd8HCjeNHe65XuSuJ8Bhtctl8vPx6XqA.P9iVsewi', 1, 1),
(23, 'awe', 'awe14@gmail.com', '9023456789', 'Male', 'fjjtgyjjtj', '$2y$10$MaZvve7myOhVRtfjXKhkNeIV6yIiNBrRs2clkeZONMEwZlTFpmIHq', 1, 1),
(24, 'swati kumari', 'swati24@gmail.com', '9034567890', 'Female', 'swati_123', '$2y$10$UodaoXKVSJn/VNDaRramjuZmBVLA3Wk6Pxvg3xnVC0YIfjjpDY6bS', 3, 1),
(25, 'sohan prajapati', 'sohan25@gmail.com', '9045678901', 'Male', 'sohan_2003', '$2y$10$5AZjPcde7DAwGmry0mjY6.0glnE0ZRyoC7PBXLW2z/5JLY6N31No6', 2, 1),
(26, 'smit', 'smit26@gmail.com', '9056789012', 'Male', 'smit_123', '$2y$10$QpEtJGDeWg20zpGb44YccOdnZTUEnJJD01yg/uqfZAbn0HX4aEAG.', 2, 1),
(28, 'abc', 'sohan.21beitg119@gmail.com', '1212121212', 'Male', 'abc_123', '$2y$10$QjdDFjkAWN3KxiHVpPYjQewMDVDcXlOI8tnyaPGRWB9IYXD3BWceG', 1, 1),
(29, 'daDAdwd', 'dsds@gmail.com', '4356789043', 'Female', 'asd', 'asdasdasd', 1, 1),
(35, 'sham', 'sohan@gmail.com', '1231231231', 'Male', 'sham123', '$2y$10$UuTY6BJ1H97hHOHFrvdfpuvzLX8PUbQJyXaU8ngqL7sAfv/1pg5DW', 1, 1),
(39, 'getinplay', 'sohan.21beitg119@gmail.com', '7990153071', 'Male', 'getinplay123', '$2y$10$PcPkWCQhOYyRvtMxMqtwgOZWal9m5y25IykBF.H925LpRANxeRn.u', 2, 1),
(40, 'sohan', 'smitbarot2004@gmail.com', '9664691917', 'Male', 'sohan_1123', '$2y$10$fb1BQNDpoz9EMlCLbcy0jO1T1v5zUVXvCAy.4/lcXplM0eI7iyeb6', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

CREATE TABLE `terms` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `deleteval` int(11) NOT NULL DEFAULT 1,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `terms`
--

INSERT INTO `terms` (`id`, `content`, `deleteval`, `updated_at`) VALUES
(1, '<ul>\n<li><strong>Acceptance of Terms</strong> &ndash; Entering the game zone means you agree to follow all rules and regulations.</li>\n<li><strong>Age Restrictions</strong> &ndash; Players under 12 must be accompanied by a guardian. Age-restricted games may require ID verification.</li>\n<li><strong>Booking &amp; Payments</strong> &ndash; Advance booking and full payment are required. No refunds except in special cases approved by management.</li>\n<li><strong>Game Rules &amp; Fair Play</strong> &ndash; Cheating, hacking, or any unfair play is strictly prohibited and may lead to suspension.</li>\n<li><strong>Safety &amp; Conduct</strong> &ndash; Running, pushing, or disruptive behavior is not allowed. Management reserves the right to remove violators.</li>\n<li><strong>Equipment Handling</strong> &ndash; Handle gaming equipment with care. Any damage due to negligence will result in penalties or repair charges.</li>\n<li><strong>Food &amp; Drinks</strong> &ndash; Not allowed inside the gaming area to prevent equipment damage. Use the designated refreshment area.</li>\n<li><strong>Personal Belongings</strong> &ndash; The game zone is not responsible for lost, stolen, or damaged belongings. Keep valuables secure.</li>\n</ul>', 1, '2025-03-07 15:28:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book_game`
--
ALTER TABLE `book_game`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `membership`
--
ALTER TABLE `membership`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terms`
--
ALTER TABLE `terms`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `book_game`
--
ALTER TABLE `book_game`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `membership`
--
ALTER TABLE `membership`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `terms`
--
ALTER TABLE `terms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
