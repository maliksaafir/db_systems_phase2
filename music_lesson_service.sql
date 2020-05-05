-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2020 at 04:02 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `music_lesson_service`
--

-- --------------------------------------------------------

--
-- Table structure for table `instruments`
--

CREATE TABLE `instruments` (
  `instrument_id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `instruments`
--

INSERT INTO `instruments` (`instrument_id`, `name`) VALUES
(1, 'Trumpet'),
(2, 'Guitar'),
(3, 'Piano'),
(4, 'Drums'),
(5, 'Bass'),
(6, 'Flute'),
(7, 'DIDGERIDOO');

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `lesson_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `instrument_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`lesson_id`, `date`, `start_time`, `end_time`, `teacher_id`, `student_id`, `instrument_id`) VALUES
(28, '2020-05-23', '05:30:00', '06:30:00', 1, 15, 2),
(29, '2020-05-08', '04:00:00', '05:00:00', 2, 7, 3),
(30, '2020-05-13', '11:00:00', '12:30:00', 3, NULL, 5),
(31, '2020-05-06', '05:15:00', '06:30:00', 1, NULL, 4),
(32, '2020-05-22', '04:30:00', '05:20:00', 1, NULL, 1),
(33, '2020-05-07', '09:30:00', '10:50:00', 4, NULL, 4),
(34, '2020-05-16', '05:45:00', '07:00:00', 2, 9, 2),
(37, '2020-05-23', '09:00:00', '11:00:00', 13, NULL, 4);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `rating_score` decimal(10,0) NOT NULL,
  `review_body` longtext NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `teaches_r`
--

CREATE TABLE `teaches_r` (
  `teacher_id` int(11) NOT NULL,
  `instrument_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `teaches_r`
--

INSERT INTO `teaches_r` (`teacher_id`, `instrument_id`) VALUES
(2, 4),
(2, 3),
(1, 6),
(4, 5),
(4, 2),
(1, 2),
(1, 3),
(3, 3),
(3, 4),
(3, 1),
(11, 6),
(11, 2),
(12, 1),
(12, 4),
(12, 5),
(13, 1),
(13, 3),
(13, 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `fname` varchar(45) NOT NULL,
  `lname` varchar(45) NOT NULL,
  `email_address` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `is_teacher` tinyint(4) NOT NULL,
  `is_student` tinyint(4) NOT NULL,
  `charge_rate` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `fname`, `lname`, `email_address`, `password`, `is_teacher`, `is_student`, `charge_rate`) VALUES
(1, 'Derrick', 'Smith', 'dsmith123@gmail.com', 'password', 1, 0, '30'),
(2, 'Sierra', 'Hernandez', 'hernandezmusic88@gmail.com', 'password', 1, 0, '35'),
(3, 'Jason', 'Black', 'jblack284@gmail.com', 'password', 1, 0, '25'),
(4, 'Lisa', 'Byrd', 'lisab874@gmail.com', 'password', 1, 0, '32'),
(5, 'Vicki', 'Holt', 'h_vicky829@gmail.com', 'password', 0, 1, NULL),
(6, 'Pablo', 'Soto', 'psoto55@gmail.com', 'password', 0, 1, NULL),
(7, 'Lamar', 'Patterson', 'l_patterson@gmail.com', 'password', 0, 1, NULL),
(8, 'Jamie', 'Greene', 'james_g583@gmail.com', 'password', 0, 1, NULL),
(9, 'John', 'Doe', 'jdoe@yahoo.com', 'password', 0, 1, NULL),
(11, 'John', 'Doe', 'jdoe2@gmail.com', 'password', 1, 0, '200'),
(12, 'John', 'Doe', 'jdoe@gmail.com', 'password', 1, 0, '150'),
(13, 'Ricky', 'Bobby', 'shakenbake@gmail.com', 'password', 1, 0, '150'),
(15, 'Firstname', 'Lastname', 'email@college.edu', 'password', 0, 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `instruments`
--
ALTER TABLE `instruments`
  ADD PRIMARY KEY (`instrument_id`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`lesson_id`),
  ADD KEY `Teacher 2_idx` (`teacher_id`),
  ADD KEY `Student 2_idx` (`student_id`),
  ADD KEY `Instrument 2_idx` (`instrument_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `Teacher 3_idx` (`teacher_id`),
  ADD KEY `Student 3_idx` (`student_id`);

--
-- Indexes for table `teaches_r`
--
ALTER TABLE `teaches_r`
  ADD KEY `Teacher_idx` (`teacher_id`),
  ADD KEY `Instrument_idx` (`instrument_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email_address` (`email_address`),
  ADD UNIQUE KEY `user_id_UNIQUE` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `instruments`
--
ALTER TABLE `instruments`
  MODIFY `instrument_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `lesson_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `Student 2` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `Teacher 2` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `Student 3` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `Teacher 3` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `teaches_r`
--
ALTER TABLE `teaches_r`
  ADD CONSTRAINT `Instrument` FOREIGN KEY (`instrument_id`) REFERENCES `instruments` (`instrument_id`),
  ADD CONSTRAINT `Teacher` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
