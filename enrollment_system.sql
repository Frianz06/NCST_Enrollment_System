-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 27, 2025 at 08:27 PM
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
-- Database: `enrollment_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `enrolled_subjects`
--

CREATE TABLE `enrolled_subjects` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `section_id` int(11) NOT NULL,
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `student_type` enum('college','senior_high') NOT NULL,
  `type` enum('college','shs') NOT NULL DEFAULT 'college'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `name`, `description`, `student_type`, `type`) VALUES
(1, 'BSIT', NULL, 'college', 'college'),
(2, 'BSCS', NULL, 'college', 'college'),
(3, 'BSBA', NULL, 'college', 'college'),
(4, 'BSHM', NULL, 'college', 'college'),
(5, 'BSTM', NULL, 'college', 'college'),
(6, 'BSPSYCH', NULL, 'college', 'college'),
(7, 'BSCRIM', NULL, 'college', 'college');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `day` varchar(20) NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  `room` varchar(50) DEFAULT NULL,
  `instructor` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `program_id` int(11) DEFAULT NULL,
  `section_name` varchar(20) DEFAULT NULL,
  `year_level` varchar(20) DEFAULT NULL,
  `semester` varchar(10) DEFAULT NULL,
  `shift` enum('M','A','E') NOT NULL,
  `section_number` int(11) DEFAULT NULL,
  `max_students` int(11) DEFAULT 40,
  `student_type` enum('college','senior_high') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `program_id`, `section_name`, `year_level`, `semester`, `shift`, `section_number`, `max_students`, `student_type`) VALUES
(1, 1, 'BSIT - 11M1', '1', '1', 'M', 1, 40, 'college'),
(2, 1, 'BSIT - 11M2', '1', '1', 'M', 2, 40, 'college'),
(3, 1, 'BSIT - 11M3', '1', '1', 'M', 3, 40, 'college'),
(4, 1, 'BSIT - 11A1', '1', '1', 'A', 1, 40, 'college'),
(5, 1, 'BSIT - 11A2', '1', '1', 'A', 2, 40, 'college'),
(6, 1, 'BSIT - 11A3', '1', '1', 'A', 3, 40, 'college'),
(7, 1, 'BSIT - 11E1', '1', '1', 'E', 1, 40, 'college'),
(8, 1, 'BSIT - 11E2', '1', '1', 'E', 2, 40, 'college'),
(9, 1, 'BSIT - 11E3', '1', '1', 'E', 3, 40, 'college'),
(10, 1, 'BSIT - 21M1', '2', '1', 'M', 1, 40, 'college'),
(11, 1, 'BSIT - 21M2', '2', '1', 'M', 2, 40, 'college'),
(12, 1, 'BSIT - 21M3', '2', '1', 'M', 3, 40, 'college'),
(13, 1, 'BSIT - 21A1', '2', '1', 'A', 1, 40, 'college'),
(14, 1, 'BSIT - 21A2', '2', '1', 'A', 2, 40, 'college'),
(15, 1, 'BSIT - 21A3', '2', '1', 'A', 3, 40, 'college'),
(16, 1, 'BSIT - 21E1', '2', '1', 'E', 1, 40, 'college'),
(17, 1, 'BSIT - 21E2', '2', '1', 'E', 2, 40, 'college'),
(18, 1, 'BSIT - 21E3', '2', '1', 'E', 3, 40, 'college'),
(19, 1, 'BSIT - 31M1', '3', '1', 'M', 1, 40, 'college'),
(20, 1, 'BSIT - 31M2', '3', '1', 'M', 2, 40, 'college'),
(21, 1, 'BSIT - 31M3', '3', '1', 'M', 3, 40, 'college'),
(22, 1, 'BSIT - 31A1', '3', '1', 'A', 1, 40, 'college'),
(23, 1, 'BSIT - 31A2', '3', '1', 'A', 2, 40, 'college'),
(24, 1, 'BSIT - 31A3', '3', '1', 'A', 3, 40, 'college'),
(25, 1, 'BSIT - 31E1', '3', '1', 'E', 1, 40, 'college'),
(26, 1, 'BSIT - 31E2', '3', '1', 'E', 2, 40, 'college'),
(27, 1, 'BSIT - 31E3', '3', '1', 'E', 3, 40, 'college'),
(28, 1, 'BSIT - 41M1', '4', '1', 'M', 1, 40, 'college'),
(29, 1, 'BSIT - 41M2', '4', '1', 'M', 2, 40, 'college'),
(30, 1, 'BSIT - 41M3', '4', '1', 'M', 3, 40, 'college'),
(31, 1, 'BSIT - 41A1', '4', '1', 'A', 1, 40, 'college'),
(32, 1, 'BSIT - 41A2', '4', '1', 'A', 2, 40, 'college'),
(33, 1, 'BSIT - 41A3', '4', '1', 'A', 3, 40, 'college'),
(34, 1, 'BSIT - 41E1', '4', '1', 'E', 1, 40, 'college'),
(35, 1, 'BSIT - 41E2', '4', '1', 'E', 2, 40, 'college'),
(36, 1, 'BSIT - 41E3', '4', '1', 'E', 3, 40, 'college'),
(37, 2, 'BSCS - 11M1', '1', '1', 'M', 1, 40, 'college'),
(38, 2, 'BSCS - 11M2', '1', '1', 'M', 2, 40, 'college'),
(39, 2, 'BSCS - 11M3', '1', '1', 'M', 3, 40, 'college'),
(40, 2, 'BSCS - 11A1', '1', '1', 'A', 1, 40, 'college'),
(41, 2, 'BSCS - 11A2', '1', '1', 'A', 2, 40, 'college'),
(42, 2, 'BSCS - 11A3', '1', '1', 'A', 3, 40, 'college'),
(43, 2, 'BSCS - 11E1', '1', '1', 'E', 1, 40, 'college'),
(44, 2, 'BSCS - 11E2', '1', '1', 'E', 2, 40, 'college'),
(45, 2, 'BSCS - 11E3', '1', '1', 'E', 3, 40, 'college'),
(46, 2, 'BSCS - 21M1', '2', '1', 'M', 1, 40, 'college'),
(47, 2, 'BSCS - 21M2', '2', '1', 'M', 2, 40, 'college'),
(48, 2, 'BSCS - 21M3', '2', '1', 'M', 3, 40, 'college'),
(49, 2, 'BSCS - 21A1', '2', '1', 'A', 1, 40, 'college'),
(50, 2, 'BSCS - 21A2', '2', '1', 'A', 2, 40, 'college'),
(51, 2, 'BSCS - 21A3', '2', '1', 'A', 3, 40, 'college'),
(52, 2, 'BSCS - 21E1', '2', '1', 'E', 1, 40, 'college'),
(53, 2, 'BSCS - 21E2', '2', '1', 'E', 2, 40, 'college'),
(54, 2, 'BSCS - 21E3', '2', '1', 'E', 3, 40, 'college'),
(55, 2, 'BSCS - 31M1', '3', '1', 'M', 1, 40, 'college'),
(56, 2, 'BSCS - 31M2', '3', '1', 'M', 2, 40, 'college'),
(57, 2, 'BSCS - 31M3', '3', '1', 'M', 3, 40, 'college'),
(58, 2, 'BSCS - 31A1', '3', '1', 'A', 1, 40, 'college'),
(59, 2, 'BSCS - 31A2', '3', '1', 'A', 2, 40, 'college'),
(60, 2, 'BSCS - 31A3', '3', '1', 'A', 3, 40, 'college'),
(61, 2, 'BSCS - 31E1', '3', '1', 'E', 1, 40, 'college'),
(62, 2, 'BSCS - 31E2', '3', '1', 'E', 2, 40, 'college'),
(63, 2, 'BSCS - 31E3', '3', '1', 'E', 3, 40, 'college'),
(64, 2, 'BSCS - 41M1', '4', '1', 'M', 1, 40, 'college'),
(65, 2, 'BSCS - 41M2', '4', '1', 'M', 2, 40, 'college'),
(66, 2, 'BSCS - 41M3', '4', '1', 'M', 3, 40, 'college'),
(67, 2, 'BSCS - 41A1', '4', '1', 'A', 1, 40, 'college'),
(68, 2, 'BSCS - 41A2', '4', '1', 'A', 2, 40, 'college'),
(69, 2, 'BSCS - 41A3', '4', '1', 'A', 3, 40, 'college'),
(70, 2, 'BSCS - 41E1', '4', '1', 'E', 1, 40, 'college'),
(71, 2, 'BSCS - 41E2', '4', '1', 'E', 2, 40, 'college'),
(72, 2, 'BSCS - 41E3', '4', '1', 'E', 3, 40, 'college'),
(73, 3, 'BSBA - 11M1', '1', '1', 'M', 1, 40, 'college'),
(74, 3, 'BSBA - 11M2', '1', '1', 'M', 2, 40, 'college'),
(75, 3, 'BSBA - 11M3', '1', '1', 'M', 3, 40, 'college'),
(76, 3, 'BSBA - 11A1', '1', '1', 'A', 1, 40, 'college'),
(77, 3, 'BSBA - 11A2', '1', '1', 'A', 2, 40, 'college'),
(78, 3, 'BSBA - 11A3', '1', '1', 'A', 3, 40, 'college'),
(79, 3, 'BSBA - 11E1', '1', '1', 'E', 1, 40, 'college'),
(80, 3, 'BSBA - 11E2', '1', '1', 'E', 2, 40, 'college'),
(81, 3, 'BSBA - 11E3', '1', '1', 'E', 3, 40, 'college'),
(82, 3, 'BSBA - 21M1', '2', '1', 'M', 1, 40, 'college'),
(83, 3, 'BSBA - 21M2', '2', '1', 'M', 2, 40, 'college'),
(84, 3, 'BSBA - 21M3', '2', '1', 'M', 3, 40, 'college'),
(85, 3, 'BSBA - 21A1', '2', '1', 'A', 1, 40, 'college'),
(86, 3, 'BSBA - 21A2', '2', '1', 'A', 2, 40, 'college'),
(87, 3, 'BSBA - 21A3', '2', '1', 'A', 3, 40, 'college'),
(88, 3, 'BSBA - 21E1', '2', '1', 'E', 1, 40, 'college'),
(89, 3, 'BSBA - 21E2', '2', '1', 'E', 2, 40, 'college'),
(90, 3, 'BSBA - 21E3', '2', '1', 'E', 3, 40, 'college'),
(91, 3, 'BSBA - 31M1', '3', '1', 'M', 1, 40, 'college'),
(92, 3, 'BSBA - 31M2', '3', '1', 'M', 2, 40, 'college'),
(93, 3, 'BSBA - 31M3', '3', '1', 'M', 3, 40, 'college'),
(94, 3, 'BSBA - 31A1', '3', '1', 'A', 1, 40, 'college'),
(95, 3, 'BSBA - 31A2', '3', '1', 'A', 2, 40, 'college'),
(96, 3, 'BSBA - 31A3', '3', '1', 'A', 3, 40, 'college'),
(97, 3, 'BSBA - 31E1', '3', '1', 'E', 1, 40, 'college'),
(98, 3, 'BSBA - 31E2', '3', '1', 'E', 2, 40, 'college'),
(99, 3, 'BSBA - 31E3', '3', '1', 'E', 3, 40, 'college'),
(100, 3, 'BSBA - 41M1', '4', '1', 'M', 1, 40, 'college'),
(101, 3, 'BSBA - 41M2', '4', '1', 'M', 2, 40, 'college'),
(102, 3, 'BSBA - 41M3', '4', '1', 'M', 3, 40, 'college'),
(103, 3, 'BSBA - 41A1', '4', '1', 'A', 1, 40, 'college'),
(104, 3, 'BSBA - 41A2', '4', '1', 'A', 2, 40, 'college'),
(105, 3, 'BSBA - 41A3', '4', '1', 'A', 3, 40, 'college'),
(106, 3, 'BSBA - 41E1', '4', '1', 'E', 1, 40, 'college'),
(107, 3, 'BSBA - 41E2', '4', '1', 'E', 2, 40, 'college'),
(108, 3, 'BSBA - 41E3', '4', '1', 'E', 3, 40, 'college'),
(109, 4, 'BSHM - 11M1', '1', '1', 'M', 1, 40, 'college'),
(110, 4, 'BSHM - 11M2', '1', '1', 'M', 2, 40, 'college'),
(111, 4, 'BSHM - 11M3', '1', '1', 'M', 3, 40, 'college'),
(112, 4, 'BSHM - 11A1', '1', '1', 'A', 1, 40, 'college'),
(113, 4, 'BSHM - 11A2', '1', '1', 'A', 2, 40, 'college'),
(114, 4, 'BSHM - 11A3', '1', '1', 'A', 3, 40, 'college'),
(115, 4, 'BSHM - 11E1', '1', '1', 'E', 1, 40, 'college'),
(116, 4, 'BSHM - 11E2', '1', '1', 'E', 2, 40, 'college'),
(117, 4, 'BSHM - 11E3', '1', '1', 'E', 3, 40, 'college'),
(118, 4, 'BSHM - 21M1', '2', '1', 'M', 1, 40, 'college'),
(119, 4, 'BSHM - 21M2', '2', '1', 'M', 2, 40, 'college'),
(120, 4, 'BSHM - 21M3', '2', '1', 'M', 3, 40, 'college'),
(121, 4, 'BSHM - 21A1', '2', '1', 'A', 1, 40, 'college'),
(122, 4, 'BSHM - 21A2', '2', '1', 'A', 2, 40, 'college'),
(123, 4, 'BSHM - 21A3', '2', '1', 'A', 3, 40, 'college'),
(124, 4, 'BSHM - 21E1', '2', '1', 'E', 1, 40, 'college'),
(125, 4, 'BSHM - 21E2', '2', '1', 'E', 2, 40, 'college'),
(126, 4, 'BSHM - 21E3', '2', '1', 'E', 3, 40, 'college'),
(127, 4, 'BSHM - 31M1', '3', '1', 'M', 1, 40, 'college'),
(128, 4, 'BSHM - 31M2', '3', '1', 'M', 2, 40, 'college'),
(129, 4, 'BSHM - 31M3', '3', '1', 'M', 3, 40, 'college'),
(130, 4, 'BSHM - 31A1', '3', '1', 'A', 1, 40, 'college'),
(131, 4, 'BSHM - 31A2', '3', '1', 'A', 2, 40, 'college'),
(132, 4, 'BSHM - 31A3', '3', '1', 'A', 3, 40, 'college'),
(133, 4, 'BSHM - 31E1', '3', '1', 'E', 1, 40, 'college'),
(134, 4, 'BSHM - 31E2', '3', '1', 'E', 2, 40, 'college'),
(135, 4, 'BSHM - 31E3', '3', '1', 'E', 3, 40, 'college'),
(136, 4, 'BSHM - 41M1', '4', '1', 'M', 1, 40, 'college'),
(137, 4, 'BSHM - 41M2', '4', '1', 'M', 2, 40, 'college'),
(138, 4, 'BSHM - 41M3', '4', '1', 'M', 3, 40, 'college'),
(139, 4, 'BSHM - 41A1', '4', '1', 'A', 1, 40, 'college'),
(140, 4, 'BSHM - 41A2', '4', '1', 'A', 2, 40, 'college'),
(141, 4, 'BSHM - 41A3', '4', '1', 'A', 3, 40, 'college'),
(142, 4, 'BSHM - 41E1', '4', '1', 'E', 1, 40, 'college'),
(143, 4, 'BSHM - 41E2', '4', '1', 'E', 2, 40, 'college'),
(144, 4, 'BSHM - 41E3', '4', '1', 'E', 3, 40, 'college'),
(145, 5, 'BSTM - 11M1', '1', '1', 'M', 1, 40, 'college'),
(146, 5, 'BSTM - 11M2', '1', '1', 'M', 2, 40, 'college'),
(147, 5, 'BSTM - 11M3', '1', '1', 'M', 3, 40, 'college'),
(148, 5, 'BSTM - 11A1', '1', '1', 'A', 1, 40, 'college'),
(149, 5, 'BSTM - 11A2', '1', '1', 'A', 2, 40, 'college'),
(150, 5, 'BSTM - 11A3', '1', '1', 'A', 3, 40, 'college'),
(151, 5, 'BSTM - 11E1', '1', '1', 'E', 1, 40, 'college'),
(152, 5, 'BSTM - 11E2', '1', '1', 'E', 2, 40, 'college'),
(153, 5, 'BSTM - 11E3', '1', '1', 'E', 3, 40, 'college'),
(154, 5, 'BSTM - 21M1', '2', '1', 'M', 1, 40, 'college'),
(155, 5, 'BSTM - 21M2', '2', '1', 'M', 2, 40, 'college'),
(156, 5, 'BSTM - 21M3', '2', '1', 'M', 3, 40, 'college'),
(157, 5, 'BSTM - 21A1', '2', '1', 'A', 1, 40, 'college'),
(158, 5, 'BSTM - 21A2', '2', '1', 'A', 2, 40, 'college'),
(159, 5, 'BSTM - 21A3', '2', '1', 'A', 3, 40, 'college'),
(160, 5, 'BSTM - 21E1', '2', '1', 'E', 1, 40, 'college'),
(161, 5, 'BSTM - 21E2', '2', '1', 'E', 2, 40, 'college'),
(162, 5, 'BSTM - 21E3', '2', '1', 'E', 3, 40, 'college'),
(163, 5, 'BSTM - 31M1', '3', '1', 'M', 1, 40, 'college'),
(164, 5, 'BSTM - 31M2', '3', '1', 'M', 2, 40, 'college'),
(165, 5, 'BSTM - 31M3', '3', '1', 'M', 3, 40, 'college'),
(166, 5, 'BSTM - 31A1', '3', '1', 'A', 1, 40, 'college'),
(167, 5, 'BSTM - 31A2', '3', '1', 'A', 2, 40, 'college'),
(168, 5, 'BSTM - 31A3', '3', '1', 'A', 3, 40, 'college'),
(169, 5, 'BSTM - 31E1', '3', '1', 'E', 1, 40, 'college'),
(170, 5, 'BSTM - 31E2', '3', '1', 'E', 2, 40, 'college'),
(171, 5, 'BSTM - 31E3', '3', '1', 'E', 3, 40, 'college'),
(172, 5, 'BSTM - 41M1', '4', '1', 'M', 1, 40, 'college'),
(173, 5, 'BSTM - 41M2', '4', '1', 'M', 2, 40, 'college'),
(174, 5, 'BSTM - 41M3', '4', '1', 'M', 3, 40, 'college'),
(175, 5, 'BSTM - 41A1', '4', '1', 'A', 1, 40, 'college'),
(176, 5, 'BSTM - 41A2', '4', '1', 'A', 2, 40, 'college'),
(177, 5, 'BSTM - 41A3', '4', '1', 'A', 3, 40, 'college'),
(178, 5, 'BSTM - 41E1', '4', '1', 'E', 1, 40, 'college'),
(179, 5, 'BSTM - 41E2', '4', '1', 'E', 2, 40, 'college'),
(180, 5, 'BSTM - 41E3', '4', '1', 'E', 3, 40, 'college'),
(181, 6, 'BSPSYCH - 11M1', '1', '1', 'M', 1, 40, 'college'),
(182, 6, 'BSPSYCH - 11M2', '1', '1', 'M', 2, 40, 'college'),
(183, 6, 'BSPSYCH - 11M3', '1', '1', 'M', 3, 40, 'college'),
(184, 6, 'BSPSYCH - 11A1', '1', '1', 'A', 1, 40, 'college'),
(185, 6, 'BSPSYCH - 11A2', '1', '1', 'A', 2, 40, 'college'),
(186, 6, 'BSPSYCH - 11A3', '1', '1', 'A', 3, 40, 'college'),
(187, 6, 'BSPSYCH - 11E1', '1', '1', 'E', 1, 40, 'college'),
(188, 6, 'BSPSYCH - 11E2', '1', '1', 'E', 2, 40, 'college'),
(189, 6, 'BSPSYCH - 11E3', '1', '1', 'E', 3, 40, 'college'),
(190, 6, 'BSPSYCH - 21M1', '2', '1', 'M', 1, 40, 'college'),
(191, 6, 'BSPSYCH - 21M2', '2', '1', 'M', 2, 40, 'college'),
(192, 6, 'BSPSYCH - 21M3', '2', '1', 'M', 3, 40, 'college'),
(193, 6, 'BSPSYCH - 21A1', '2', '1', 'A', 1, 40, 'college'),
(194, 6, 'BSPSYCH - 21A2', '2', '1', 'A', 2, 40, 'college'),
(195, 6, 'BSPSYCH - 21A3', '2', '1', 'A', 3, 40, 'college'),
(196, 6, 'BSPSYCH - 21E1', '2', '1', 'E', 1, 40, 'college'),
(197, 6, 'BSPSYCH - 21E2', '2', '1', 'E', 2, 40, 'college'),
(198, 6, 'BSPSYCH - 21E3', '2', '1', 'E', 3, 40, 'college'),
(199, 6, 'BSPSYCH - 31M1', '3', '1', 'M', 1, 40, 'college'),
(200, 6, 'BSPSYCH - 31M2', '3', '1', 'M', 2, 40, 'college'),
(201, 6, 'BSPSYCH - 31M3', '3', '1', 'M', 3, 40, 'college'),
(202, 6, 'BSPSYCH - 31A1', '3', '1', 'A', 1, 40, 'college'),
(203, 6, 'BSPSYCH - 31A2', '3', '1', 'A', 2, 40, 'college'),
(204, 6, 'BSPSYCH - 31A3', '3', '1', 'A', 3, 40, 'college'),
(205, 6, 'BSPSYCH - 31E1', '3', '1', 'E', 1, 40, 'college'),
(206, 6, 'BSPSYCH - 31E2', '3', '1', 'E', 2, 40, 'college'),
(207, 6, 'BSPSYCH - 31E3', '3', '1', 'E', 3, 40, 'college'),
(208, 6, 'BSPSYCH - 41M1', '4', '1', 'M', 1, 40, 'college'),
(209, 6, 'BSPSYCH - 41M2', '4', '1', 'M', 2, 40, 'college'),
(210, 6, 'BSPSYCH - 41M3', '4', '1', 'M', 3, 40, 'college'),
(211, 6, 'BSPSYCH - 41A1', '4', '1', 'A', 1, 40, 'college'),
(212, 6, 'BSPSYCH - 41A2', '4', '1', 'A', 2, 40, 'college'),
(213, 6, 'BSPSYCH - 41A3', '4', '1', 'A', 3, 40, 'college'),
(214, 6, 'BSPSYCH - 41E1', '4', '1', 'E', 1, 40, 'college'),
(215, 6, 'BSPSYCH - 41E2', '4', '1', 'E', 2, 40, 'college'),
(216, 6, 'BSPSYCH - 41E3', '4', '1', 'E', 3, 40, 'college'),
(217, 7, 'BSCRIM - 11M1', '1', '1', 'M', 1, 40, 'college'),
(218, 7, 'BSCRIM - 11M2', '1', '1', 'M', 2, 40, 'college'),
(219, 7, 'BSCRIM - 11M3', '1', '1', 'M', 3, 40, 'college'),
(220, 7, 'BSCRIM - 11A1', '1', '1', 'A', 1, 40, 'college'),
(221, 7, 'BSCRIM - 11A2', '1', '1', 'A', 2, 40, 'college'),
(222, 7, 'BSCRIM - 11A3', '1', '1', 'A', 3, 40, 'college'),
(223, 7, 'BSCRIM - 11E1', '1', '1', 'E', 1, 40, 'college'),
(224, 7, 'BSCRIM - 11E2', '1', '1', 'E', 2, 40, 'college'),
(225, 7, 'BSCRIM - 11E3', '1', '1', 'E', 3, 40, 'college'),
(226, 7, 'BSCRIM - 21M1', '2', '1', 'M', 1, 40, 'college'),
(227, 7, 'BSCRIM - 21M2', '2', '1', 'M', 2, 40, 'college'),
(228, 7, 'BSCRIM - 21M3', '2', '1', 'M', 3, 40, 'college'),
(229, 7, 'BSCRIM - 21A1', '2', '1', 'A', 1, 40, 'college'),
(230, 7, 'BSCRIM - 21A2', '2', '1', 'A', 2, 40, 'college'),
(231, 7, 'BSCRIM - 21A3', '2', '1', 'A', 3, 40, 'college'),
(232, 7, 'BSCRIM - 21E1', '2', '1', 'E', 1, 40, 'college'),
(233, 7, 'BSCRIM - 21E2', '2', '1', 'E', 2, 40, 'college'),
(234, 7, 'BSCRIM - 21E3', '2', '1', 'E', 3, 40, 'college'),
(235, 7, 'BSCRIM - 31M1', '3', '1', 'M', 1, 40, 'college'),
(236, 7, 'BSCRIM - 31M2', '3', '1', 'M', 2, 40, 'college'),
(237, 7, 'BSCRIM - 31M3', '3', '1', 'M', 3, 40, 'college'),
(238, 7, 'BSCRIM - 31A1', '3', '1', 'A', 1, 40, 'college'),
(239, 7, 'BSCRIM - 31A2', '3', '1', 'A', 2, 40, 'college'),
(240, 7, 'BSCRIM - 31A3', '3', '1', 'A', 3, 40, 'college'),
(241, 7, 'BSCRIM - 31E1', '3', '1', 'E', 1, 40, 'college'),
(242, 7, 'BSCRIM - 31E2', '3', '1', 'E', 2, 40, 'college'),
(243, 7, 'BSCRIM - 31E3', '3', '1', 'E', 3, 40, 'college'),
(244, 7, 'BSCRIM - 41M1', '4', '1', 'M', 1, 40, 'college'),
(245, 7, 'BSCRIM - 41M2', '4', '1', 'M', 2, 40, 'college'),
(246, 7, 'BSCRIM - 41M3', '4', '1', 'M', 3, 40, 'college'),
(247, 7, 'BSCRIM - 41A1', '4', '1', 'A', 1, 40, 'college'),
(248, 7, 'BSCRIM - 41A2', '4', '1', 'A', 2, 40, 'college'),
(249, 7, 'BSCRIM - 41A3', '4', '1', 'A', 3, 40, 'college'),
(250, 7, 'BSCRIM - 41E1', '4', '1', 'E', 1, 40, 'college'),
(251, 7, 'BSCRIM - 41E2', '4', '1', 'E', 2, 40, 'college'),
(252, 7, 'BSCRIM - 41E3', '4', '1', 'E', 3, 40, 'college');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `student_number` char(10) DEFAULT NULL,
  `student_type` enum('college','senior_high') NOT NULL,
  `program_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `year_level` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_applications`
--

CREATE TABLE `student_applications` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `course_or_track` varchar(100) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `civil_status` varchar(50) NOT NULL,
  `type` varchar(20) NOT NULL,
  `admission_type` varchar(30) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `date_submitted` datetime DEFAULT current_timestamp(),
  `nationality` varchar(100) DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `region` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `suffix` varchar(20) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `mobile` varchar(30) NOT NULL,
  `landline` varchar(30) DEFAULT NULL,
  `dob` date NOT NULL,
  `pob` varchar(100) NOT NULL,
  `dialect` varchar(100) DEFAULT NULL,
  `elementary_school` varchar(100) NOT NULL,
  `elementary_year_grad` varchar(10) NOT NULL,
  `high_school` varchar(100) NOT NULL,
  `high_year_grad` varchar(10) NOT NULL,
  `grade10_section` varchar(50) NOT NULL,
  `father_family_name` varchar(100) DEFAULT NULL,
  `father_given_name` varchar(100) DEFAULT NULL,
  `father_middle_name` varchar(100) DEFAULT NULL,
  `father_deceased` tinyint(1) DEFAULT NULL,
  `father_address` varchar(255) DEFAULT NULL,
  `father_mobile` varchar(30) DEFAULT NULL,
  `father_landline` varchar(30) DEFAULT NULL,
  `father_occupation` varchar(100) DEFAULT NULL,
  `mother_family_name` varchar(100) DEFAULT NULL,
  `mother_given_name` varchar(100) DEFAULT NULL,
  `mother_middle_name` varchar(100) DEFAULT NULL,
  `mother_deceased` tinyint(1) DEFAULT NULL,
  `mother_maiden_family_name` varchar(100) DEFAULT NULL,
  `mother_maiden_given_name` varchar(100) DEFAULT NULL,
  `mother_maiden_middle_name` varchar(100) DEFAULT NULL,
  `mother_address` varchar(255) DEFAULT NULL,
  `mother_mobile` varchar(30) DEFAULT NULL,
  `mother_landline` varchar(30) DEFAULT NULL,
  `mother_occupation` varchar(100) DEFAULT NULL,
  `guardian_family_name` varchar(100) DEFAULT NULL,
  `guardian_given_name` varchar(100) DEFAULT NULL,
  `guardian_middle_name` varchar(100) DEFAULT NULL,
  `guardian_relationship` varchar(50) DEFAULT NULL,
  `guardian_address` varchar(255) DEFAULT NULL,
  `guardian_mobile` varchar(30) DEFAULT NULL,
  `guardian_landline` varchar(30) DEFAULT NULL,
  `guardian_occupation` varchar(100) DEFAULT NULL,
  `requirements_status` text DEFAULT NULL,
  `tracking_number` varchar(32) DEFAULT NULL,
  `requirements_checklist` text DEFAULT NULL,
  `student_type` varchar(50) NOT NULL,
  `tertiary_school` varchar(100) DEFAULT NULL,
  `tertiary_year_grad` varchar(10) DEFAULT NULL,
  `course_graduated` varchar(100) DEFAULT NULL,
  `educational_plan` varchar(100) NOT NULL,
  `academic_achievement` varchar(255) DEFAULT NULL,
  `is_working` tinyint(1) NOT NULL DEFAULT 0,
  `employer` varchar(100) DEFAULT NULL,
  `work_in_shifts` tinyint(1) DEFAULT NULL,
  `work_position` varchar(100) DEFAULT NULL,
  `family_connected_ncst` tinyint(1) DEFAULT NULL,
  `ncst_relationship` varchar(100) DEFAULT NULL,
  `no_of_siblings` int(11) NOT NULL DEFAULT 0,
  `how_did_you_know_ncst` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_applications`
--

INSERT INTO `student_applications` (`id`, `student_id`, `name`, `email`, `course_or_track`, `gender`, `civil_status`, `type`, `admission_type`, `status`, `date_submitted`, `nationality`, `religion`, `region`, `province`, `city`, `barangay`, `last_name`, `first_name`, `middle_name`, `suffix`, `address`, `zip_code`, `mobile`, `landline`, `dob`, `pob`, `dialect`, `elementary_school`, `elementary_year_grad`, `high_school`, `high_year_grad`, `grade10_section`, `father_family_name`, `father_given_name`, `father_middle_name`, `father_deceased`, `father_address`, `father_mobile`, `father_landline`, `father_occupation`, `mother_family_name`, `mother_given_name`, `mother_middle_name`, `mother_deceased`, `mother_maiden_family_name`, `mother_maiden_given_name`, `mother_maiden_middle_name`, `mother_address`, `mother_mobile`, `mother_landline`, `mother_occupation`, `guardian_family_name`, `guardian_given_name`, `guardian_middle_name`, `guardian_relationship`, `guardian_address`, `guardian_mobile`, `guardian_landline`, `guardian_occupation`, `requirements_status`, `tracking_number`, `requirements_checklist`, `student_type`, `tertiary_school`, `tertiary_year_grad`, `course_graduated`, `educational_plan`, `academic_achievement`, `is_working`, `employer`, `work_in_shifts`, `work_position`, `family_connected_ncst`, `ncst_relationship`, `no_of_siblings`, `how_did_you_know_ncst`) VALUES
(7, NULL, 'Villanueva, Cristal Cachola', 'villanueva.cristal@email.com', 'STEM', 'Female', 'Single', 'College', NULL, 'for registration', '2025-07-17 20:14:12', 'Filipino', 'Catholic', 'Region IV-A - CALABARZON', 'Cavite', 'Dasmariñas', 'Salitran', '', '', NULL, NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09778674536', '', '2000-09-27', 'Cavite', 'Tagalog', 'Dasmariñas Elementary School', '2012', 'Dasmariñas National High School', '2016', 'Venus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[]', NULL, NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(8, '25-00001', 'Villanueva, Cristal Cachola', 'villanueva.cristal@email.com', 'STEM', 'Female', 'Single', 'College', NULL, 'enrolled', '2025-07-17 20:28:30', 'Filipino', 'Catholic', 'Region IV-A - CALABARZON', 'Cavite', 'Dasmariñas', 'Salitran', '', '', NULL, NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09778674536', '', '2000-09-27', 'Cavite', 'Tagalog', 'Dasmariñas Elementary School', '2012', 'Dasmariñas National High School', '2016', 'Venus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"Properly accomplished admission form\",\"Four (4) 2x2 recent, identical color pictures in white background with name tag\",\"Five (5) 1x1 recent, identical color pictures in white background with name tag\",\"Submit original and photocopied Form 138 \\/ Report Card\",\"Submit original Good Moral Character certificate with dry seal and Photocopied\",\"If married, two (2) photocopies of marriage certificate duly signed by a priest \\/ minister\",\"1pc. Long Brown Envelope\"]', NULL, NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(9, NULL, 'Villanueva, Cristal Cachola', 'villanueva.cristal@email.com', 'BSIT', 'Male', 'Single', 'College', NULL, 'for registration', '2025-07-18 11:40:37', 'Filipino', 'Catholic', 'Region IV-A - CALABARZON', 'Cavite', 'Dasmariñas', 'Salitran', '', '', NULL, NULL, 'Blk 155 ChalpLot Phase 4', '4114', '09778674536', '', '2000-09-27', 'Cavite', '', 'N/A', '', 'N/A', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[]', NULL, NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(10, NULL, 'Villanueva, Cristal Cachola', 'villanueva.cristal@email.com', 'BSCS', 'Female', 'Single', 'College', NULL, 'for registration', '2025-07-18 11:48:26', 'Filipino', 'Catholic', 'Region IV-A - CALABARZON', 'Batangas', 'Lipa', 'Sabang', '', '', NULL, NULL, 'Blk 155 ChalpLot Phase 4', '4114', '09778674536', '', '2000-09-27', 'Cavite', '', 'N/A', '', 'N/A', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[]', NULL, NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(11, NULL, 'Villanueva, Rey Beato', 'villanueva.reyl@email.com', 'GAS', 'Male', 'Single', 'College', NULL, 'for registration', '2025-07-18 11:59:13', 'Indian', 'Hindu', 'Region III - Central Luzon', 'Pampanga', 'Angeles', 'Balibago', '', '', NULL, NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09778674536', '', '2000-09-06', 'Cavite', 'Tagalog', 'Imus Central School', '2012', 'Imus National High School', '2016', 'Venus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[]', NULL, NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(12, NULL, 'Villanueva, Cristal Cachola', 'villanueva.cristal@email.com', 'BSBA', 'Female', 'Married', 'College', NULL, 'for registration', '2025-07-18 12:04:36', 'Filipino', 'Catholic', 'Region IV-A - CALABARZON', 'Laguna', 'San Pablo', 'San Rafael', '', '', NULL, NULL, 'Blk 155 ChalpLot Phase 4', '4114', '09778674536', '', '2000-09-27', 'Cavite', '', 'N/A', '', 'N/A', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"Properly accomplished admission form\",\"Four (4) 2x2 recent, identical color pictures in white background with name tag\",\"Five (5) 1x1 recent, identical color pictures in white background with name tag\",\"Submit original and photocopied Form 138 \\/ Report Card\",\"Submit original Good Moral Character certificate with dry seal and Photocopied\"]', NULL, NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(13, NULL, 'Villanueva, Rey Beato', 'villanueva.reyl@email.com', 'GAS', 'Female', 'Single', 'College', NULL, 'pending', '2025-07-18 14:07:36', 'Filipino', 'Catholic', 'Region III - Central Luzon', 'Pampanga', 'San Fernando', 'Sto. Rosario', '', '', NULL, NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09778674536', '', '2000-09-06', 'Cavite', 'Tagalog', 'Bacoor Elementary School', '2012', 'Bacoor National High School', '2016', 'Venus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'NCST-2025-13960', NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(14, NULL, 'Villanueva, Cristal Cachola', 'villanueva.cristal@email.com', 'BSCS', 'Female', 'Single', 'College', NULL, 'pending', '2025-07-18 14:18:28', 'Filipino', 'Christian', 'Region IV-A - CALABARZON', 'Laguna', 'Calamba', 'Real', '', '', NULL, NULL, 'Blk 155 ChalpLot Phase 4', '4114', '09778674536', '', '2000-09-27', 'Cavite', '', 'N/A', '', 'N/A', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'NCST-2025-95594', NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(15, NULL, 'Villanueva, Rey Beato', 'villanueva.reyl@email.com', 'HUMSS', 'Male', 'Married', 'College', NULL, 'pending', '2025-07-18 14:25:00', 'American', 'Catholic', 'Region III - Central Luzon', 'Pampanga', 'Angeles', 'Pulung Maragul', '', '', NULL, NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09778674536', '', '2000-09-06', 'Cavite', 'Tagalog', 'Dasmariñas Elementary School', '2012', 'Bacoor National High School', '2016', 'Venus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'NCST-2025-41398', '{\"0\":0,\"1\":0,\"2\":0,\"3\":0,\"4\":0,\"5\":0,\"6\":0}', '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(16, NULL, 'Villanueva, Cristal Cachola', 'villanueva.cristal@email.com', 'BSCRIM', 'Male', 'Married', 'College', NULL, 'for registration', '2025-07-18 14:29:15', 'Italian', 'Christian', 'Region IV-A - CALABARZON', 'Cavite', 'Bacoor', 'Mambog', '', '', NULL, NULL, 'Blk 155 ChalpLot Phase 4', '4114', '09778674536', '', '2000-09-27', 'Cavite', '', 'N/A', '', 'N/A', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"Properly accomplished admission form\"]', 'NCST-2025-22752', NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(17, NULL, 'Villanueva, Cristal Cachola', 'villanueva.cristal@email.com', 'BSHM', 'Male', 'Married', 'College', NULL, 'pending', '2025-07-18 16:27:42', 'Singaporean', 'Christian', 'Region IV-A - CALABARZON', 'Cavite', 'Dasmariñas', 'Salitran', '', '', NULL, NULL, 'Blk 155 ChalpLot Phase 4', '4114', '09778674536', '', '2000-09-27', 'Cavite', '', 'N/A', '', 'N/A', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'NCST-2025-33214', NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(18, NULL, 'Villanueva, Cristal Cachola', 'villanueva.cristal@email.com', 'BSHM', 'Male', 'Married', 'College', NULL, 'pending', '2025-07-18 16:32:16', 'Singaporean', 'Christian', 'Region IV-A - CALABARZON', 'Laguna', 'San Pablo', 'San Rafael', '', '', NULL, NULL, 'Blk 155 ChalpLot Phase 4', '4114', '09778674536', '', '2000-09-27', 'Cavite', '', 'N/A', '', 'N/A', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'NCST-2025-40188', '{\"0\":0,\"1\":0,\"2\":0,\"3\":0,\"4\":0,\"5\":0,\"6\":0}', '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(19, NULL, 'Villanueva, Rey Beato', 'villanueva.reyl@email.com', 'ABM', 'N/A', 'N/A', 'College', NULL, 'for registration', '2025-07-18 16:58:39', 'N/A', 'N/A', 'Region III - Central Luzon', 'N/A', 'N/A', 'N/A', '', '', NULL, NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09778674536', '', '2000-09-06', 'Cavite', 'Tagalog', 'N/A', '2012', 'N/A', '2016', 'Venus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'NCST-2025-67746', '{\"0\":0,\"1\":0,\"2\":0,\"3\":0,\"4\":0,\"5\":0,\"6\":0}', '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(20, NULL, 'Villanueva, Rey Beato', 'villanueva.reyl@email.com', 'ABM', 'N/A', 'N/A', 'College', NULL, 'for registration', '2025-07-18 17:11:12', 'N/A', 'N/A', 'Region III - Central Luzon', 'N/A', 'N/A', 'N/A', '', '', NULL, NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09778674536', '', '2000-09-06', 'Cavite', 'Tagalog', 'N/A', '2012', 'N/A', '2016', 'Venus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'NCST-2025-98177', '{\"0\":0,\"1\":0,\"2\":0,\"3\":0,\"4\":0,\"5\":0,\"6\":0}', '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(21, NULL, 'Villanueva, Rey Beato', 'villanueva.reyl@email.com', 'HUMSS', 'N/A', 'N/A', 'College', NULL, 'for registration', '2025-07-18 17:14:06', 'N/A', 'N/A', 'Region III - Central Luzon', 'N/A', 'N/A', 'N/A', '', '', NULL, NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09778674536', '', '2000-09-06', 'Cavite', 'Tagalog', 'N/A', '2012', 'N/A', '2016', 'Venus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'NCST-2025-95676', '{\"0\":0,\"1\":0,\"2\":0,\"3\":0,\"4\":0,\"5\":0,\"6\":0}', '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(22, NULL, 'Villanueva, Cristal Cachola', 'villanueva.cristal@email.com', 'BSIT', 'Male', 'Married', 'College', NULL, 'new', '2025-07-18 17:30:12', 'Filipino', 'Catholic', 'Region IV-A - CALABARZON', 'Laguna', 'Calamba', 'Canlubang', '', '', NULL, NULL, 'Blk 155 ChalpLot Phase 4', '4114', '09778674536', '', '2000-09-27', 'Cavite', '', 'N/A', '', 'N/A', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'NCST-2025-22288', NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(23, NULL, 'Legaaspi, Ychicko Beato', 'ochickik@email.com', 'BSIT', 'Male', 'Single', 'College', NULL, 'pending', '2025-07-18 18:34:35', 'N/A', 'N/A', 'Region III - Central Luzon', 'Pampanga', 'Angeles', 'Balibago', '', '', NULL, NULL, '', '', '09778674536', 'United States', '2003-08-09', 'Cavite', '', 'N/A', '', 'N/A', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'NCST-2025-32016', '{\"0\":0,\"1\":0,\"2\":0,\"3\":0,\"4\":0,\"5\":0,\"6\":0}', '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(24, NULL, 'laureles, aleyah villafuerte', 'laulau@email.com', 'BSPSYCH', 'Female', 'Single', 'College', NULL, 'rejected', '2025-07-18 18:45:44', 'N/A', 'N/A', 'NCR - National Capital Region', 'Metro Manila', 'Quezon City', 'Commonwealth', '', '', NULL, NULL, '', '', '09778674536', '', '2003-12-09', 'Cavite', '', 'N/A', '', 'N/A', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'NCST-2025-87336', '{}', '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(25, NULL, 'laureles, aleyah villafuerte', 'laulau@email.com', 'BSBA', 'Female', 'Single', 'College', NULL, 'pending', '2025-07-18 18:59:11', 'N/A', 'N/A', 'NCR - National Capital Region', 'Metro Manila', 'Quezon City', 'Batasan Hills', '', '', NULL, NULL, '', '', '09778674536', '', '2003-12-09', 'Cavite', '', 'N/A', '', 'N/A', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Villanueva', 'Rey', NULL, NULL, NULL, '09778756632', NULL, NULL, NULL, 'NCST-2025-31269', '{\"0\":0,\"1\":0,\"2\":0,\"3\":0,\"4\":0,\"5\":0,\"6\":0}', 'Transferee', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(26, NULL, 'Legaaspi, Ychicko Beato', 'ochickik@email.com', 'ABM', 'Male', 'Single', 'College', NULL, 'new', '2025-07-19 20:12:46', 'Filipino', 'Catholic', 'Region IV-A - CALABARZON', 'Cavite', 'Dasmariñas', 'San Agustin', '', '', NULL, NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09778674536', '', '2003-09-27', 'Cavite', '', 'Imus Central School', '', 'Imus National High School', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, 'NCST-2025-33737', NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(27, NULL, 'Legaaspi, Ychicko Beato', 'ochickik@email.com', 'ABM', 'Male', 'Single', 'College', NULL, 'new', '2025-07-19 20:13:33', 'Filipino', 'Catholic', 'Region IV-A - CALABARZON', 'Cavite', 'Imus', 'Buhay na Tubig', '', '', NULL, NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09778674536', '', '2003-09-27', 'Cavite', '', 'Imus Central School', '', 'Dasmariñas National High School', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, 'NCST-2025-38413', NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(28, NULL, 'Legaaspi, Ychicko Beato', 'ochickik@email.com', 'ABM', 'Male', 'Single', 'College', NULL, 'new', '2025-07-19 20:16:15', 'Filipino', 'Catholic', 'Region IV-A - CALABARZON', 'Cavite', 'Dasmariñas', 'Salitran', '', '', NULL, NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09778674536', '', '2003-09-27', 'Cavite', '', 'Imus Central School', '', 'Imus National High School', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, 'NCST-2025-17711', NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(29, NULL, 'Legaaspi, Ychicko Beato', 'ochickik@email.com', 'ABM', 'Male', 'Single', 'College', NULL, 'new', '2025-07-19 20:18:36', 'American', 'Catholic', 'Region IV-A - CALABARZON', 'Cavite', 'Dasmariñas', 'Salitran', '', '', NULL, NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09778674536', '', '2003-09-27', 'Cavite', '', 'Dasmariñas Elementary School', '', 'Bacoor National High School', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, 'NCST-2025-68035', NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(30, NULL, 'Legaaspi, Ychicko Beato', 'ochickik@email.com', 'ABM', 'Male', 'Single', 'College', NULL, 'new', '2025-07-19 20:29:04', 'American', 'Catholic', 'Region IV-A - CALABARZON', 'Cavite', 'Dasmariñas', 'Salitran', '', '', NULL, NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09778674536', '', '2003-09-27', 'Cavite', '', 'Dasmariñas Elementary School', '', 'Bacoor National High School', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, 'NCST-2025-26233', NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(31, NULL, 'Legaaspi, Ychicko Beato', 'ochickik@email.com', 'STEM', 'Male', 'Single', 'College', NULL, 'new', '2025-07-19 20:29:41', 'Filipino', 'Catholic', 'Region IV-A - CALABARZON', 'Cavite', 'Dasmariñas', 'San Agustin', '', '', NULL, NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09778674536', '', '2003-09-27', 'Cavite', '', 'Imus Central School', '', 'Dasmariñas National High School', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, 'NCST-2025-60260', NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(32, NULL, 'Legaaspi, Ychicko Beato', 'ochickik@email.com', 'STEM', 'Male', 'Single', 'College', NULL, 'new', '2025-07-19 20:30:44', 'Filipino', 'Catholic', 'Region IV-A - CALABARZON', 'Cavite', 'Dasmariñas', 'San Agustin', '', '', NULL, NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09778674536', '', '2003-09-27', 'Cavite', '', 'Imus Central School', '', 'Dasmariñas National High School', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, 'NCST-2025-87464', NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(33, NULL, 'Legaaspi, Ychicko Beato', 'ochickik@email.com', 'STEM', 'Male', 'Single', 'College', NULL, 'new', '2025-07-19 20:32:03', 'Filipino', 'Catholic', 'Region IV-A - CALABARZON', 'Cavite', 'Dasmariñas', 'San Agustin', '', '', NULL, NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09778674536', '', '2003-09-27', 'Cavite', '', 'Imus Central School', '', 'Dasmariñas National High School', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, 'NCST-2025-00901', NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(34, NULL, 'Legaaspi, Ychicko Beato', 'ochickik@email.com', 'STEM', 'Male', 'Single', 'College', NULL, 'new', '2025-07-19 20:36:31', 'Filipino', 'Catholic', 'Region IV-A - CALABARZON', 'Cavite', 'Dasmariñas', 'Salitran', '', '', NULL, NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09778674536', '', '2003-09-27', 'Cavite', '', 'Dasmariñas Elementary School', '', 'Dasmariñas National High School', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, 'NCST-2025-00034', NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(35, NULL, 'Legaaspi, Ychicko Beato', 'ochickik@email.com', 'STEM', 'Male', 'Single', 'College', NULL, 'new', '2025-07-19 20:37:59', 'Filipino', 'Catholic', 'Region IV-A - CALABARZON', 'Cavite', 'Dasmariñas', 'Salitran', '', '', NULL, NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09778674536', '', '2003-09-27', 'Cavite', '', 'Dasmariñas Elementary School', '', 'Dasmariñas National High School', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, 'NCST-2025-00035', NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(36, NULL, 'Legaaspi, Ychicko Beato', 'ochickik@email.com', 'STEM', 'Male', 'Single', 'College', NULL, 'new', '2025-07-19 20:38:07', 'Filipino', 'Catholic', 'Region IV-A - CALABARZON', 'Cavite', 'Dasmariñas', 'Salitran', '', '', NULL, NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09778674536', '', '2003-09-27', 'Cavite', '', 'Dasmariñas Elementary School', '', 'Dasmariñas National High School', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, 'NCST-2025-00036', NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(37, NULL, 'Legaaspi, Ychicko Beato', 'ochickik@email.com', 'BSCS', 'Male', 'Married', 'College', NULL, 'new', '2025-07-19 20:39:55', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', '', NULL, NULL, '', '', '09778674536', 'United States', '2004-02-09', 'Cavite', '', 'N/A', '', 'N/A', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Legaaspi', 'Ychicko', NULL, NULL, NULL, '09778756632', NULL, NULL, NULL, 'NCST-2025-00037', NULL, 'Transferee', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(38, NULL, 'Legaaspi, Ychicko Beato', 'ochickik@email.com', 'BSCS', 'Male', 'Married', 'College', NULL, 'approved', '2025-07-19 20:40:43', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', '', NULL, NULL, '', '', '09778674536', 'United States', '2004-02-09', 'Cavite', '', 'N/A', '', 'N/A', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Legaaspi', 'Ychicko', NULL, NULL, NULL, '09778756632', NULL, NULL, NULL, 'NCST-2025-00038', '{}', 'Transferee', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(39, NULL, 'Legaaspi, Ychicko Beato', 'ochickik@email.com', 'HUMSS', 'Male', 'Married', 'College', NULL, 'new', '2025-07-19 20:41:37', 'Filipino', 'Catholic', 'Region IV-A - CALABARZON', 'Cavite', 'Imus', 'Buhay na Tubig', '', '', NULL, NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09778674536', '', '2003-09-27', 'Cavite', '', 'Imus Central School', '', 'Imus National High School', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, 'NCST-2025-00039', NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(40, NULL, 'Legaaspi, Ychicko Beato', 'ochickik@email.com', 'HUMSS', 'Male', 'Married', 'College', NULL, 'new', '2025-07-19 20:48:44', 'Filipino', 'Catholic', 'Region IV-A - CALABARZON', 'Cavite', 'Imus', 'Buhay na Tubig', '', '', NULL, NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09778674536', '', '2003-09-27', 'Cavite', '', 'Imus Central School', '', 'Imus National High School', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, 'NCST-2025-00040', NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(41, NULL, 'Villanueva, Rhiane Cachola', 'riri@email.com', 'HUMSS', 'Male', 'Separated', 'College', NULL, 'new', '2025-07-19 22:10:27', 'Filipino', 'Catholic', 'Region IV-A - CALABARZON', 'Laguna', 'Calamba', 'Canlubang', '', '', NULL, NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09747558512', '', '2009-10-22', 'Bahay lang', 'Tagalog', 'Dasmariñas Elementary School', '2012', 'Dasmariñas National High School', '2016', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, 'NCST-2025-00041', NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(42, NULL, 'Villanueva, Rhiane Cachola', 'riri@email.com', 'HUMSS', 'Male', 'Separated', 'College', NULL, 'pending', '2025-07-19 22:16:01', 'Filipino', 'Catholic', 'Region IV-A - CALABARZON', 'Laguna', 'Calamba', 'Canlubang', '', '', NULL, NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09747558512', '', '2009-10-22', 'Bahay lang', 'Tagalog', 'Dasmariñas Elementary School', '2012', 'Dasmariñas National High School', '2016', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, 'NCST-2025-00042', '{\"Properly accomplished admission form\":1,\"Four (4) 2x2 recent, identical color pictures in white background with name tag\":1,\"Five (5) 1x1 recent, identical color pictures in white background with name tag\":1,\"Submit original and photocopied Form 138 / Report Card\":1,\"Submit original Good Moral Character certificate with dry seal and Photocopied\":1,\"1pc. Long Brown Envelope\":1,\"married\":0,\"If married, two (2) photocopies of marriage certificate duly signed by a priest / minister\":0}', '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(43, NULL, 'Villanueva, Rhiane Cachola', 'riri@email.com', 'HUMSS', 'Male', 'Separated', 'College', NULL, 'new', '2025-07-19 22:16:38', 'Filipino', 'Catholic', 'Region IV-A - CALABARZON', 'Laguna', 'Calamba', 'Canlubang', '', '', NULL, NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09747558512', '', '2009-10-22', 'Bahay lang', 'Tagalog', 'Dasmariñas Elementary School', '2012', 'Dasmariñas National High School', '2016', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, 'NCST-2025-00043', NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(44, NULL, 'Villanueva, Rhiane Cachola', 'riri@email.com', 'STEM', 'Female', 'Single', 'College', NULL, 'approved', '2025-07-19 22:19:52', 'Filipino', 'Christian', 'NCR - National Capital Region', 'Metro Manila', 'Manila', 'Barangay 1', '', '', NULL, NULL, 'Blk 155 Chalplot Phase 4 ', '4114', '09747558512', '', '2006-10-22', 'Cavite', 'Tagalog', 'Imus Central School', '2012', 'Imus National High School', '2016', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, 'NCST-2025-00044', '{}', '', NULL, NULL, NULL, '', NULL, 0, NULL, NULL, NULL, NULL, NULL, 0, ''),
(45, NULL, 'Legaaspi, Rey Beato', 'ochickik@email.com', 'ABM', 'Female', 'Single', 'SHS', NULL, 'pending', '2025-07-20 10:17:55', 'Filipino', 'Iglesia ni Cristo', 'Region IV-A - CALABARZON', 'Laguna', 'Calamba', 'Canlubang', 'Legaaspi', 'Rey', 'Beato', NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09857575641', NULL, '2000-08-09', 'Cavite', 'Tagalog', 'Dasmariñas Elementary School', '2012', 'Dasmariñas National High School', '2016', 'Venus', 'Villanueva', 'Rey', 'Beato', 0, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', NULL, NULL, 'Carpentry', 'Villanueva', 'Rey', 'Beato', 0, 'Villanueva', 'Rey', 'Beato', 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '09778756632', NULL, 'Domestic Helper', 'Villanueva', 'Rey', 'Beato', 'Father', 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', NULL, NULL, 'Carpentry', NULL, 'NCST-2025-00045', '{\"Properly accomplished admission form\":1,\"Four (4) 2x2 recent, identical color pictures in white background with name tag\":1,\"Five (5) 1x1 recent, identical color pictures in white background with name tag\":1,\"Submit original and photocopied Form 138 / Report Card\":1,\"Submit original Good Moral Character certificate with dry seal and Photocopied\":1,\"1pc. Long Brown Envelope\":1,\"married\":0,\"If married, two (2) photocopies of marriage certificate duly signed by a priest / minister\":0}', 'Transferee', NULL, NULL, NULL, 'Academic eXentials', NULL, 0, NULL, 0, NULL, 0, NULL, 2, 'CareerTalk'),
(46, NULL, 'Villanueva, Ychicko Beato', 'ochickik@email.com', 'BSBA', 'Male', 'Single', 'College', NULL, 'approved', '2025-07-20 11:21:53', NULL, NULL, 'Region IV-A - CALABARZON', 'Cavite', 'Dasmariñas', 'Salitran', 'Villanueva', 'Ychicko', 'Beato', NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09747558512', 'United States', '2000-09-09', 'Cavite', NULL, 'Bacoor Elementary School', '2012', 'Bacoor National High School', '2016', '', 'Villanueva', 'Ychicko', 'Beato', 0, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '09857575641', 'United States', 'Carpentry', 'Legaaspi', 'Ychicko', 'Beato', 0, 'Legaaspi', 'Ychicko', 'Beato', 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '09778756632', 'United States', 'Domestic Helper', 'Legaaspi', 'Ychicko', 'Beato', 'Mother', 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '09778756632', NULL, 'Carpentry', NULL, 'NCST-2025-00046', '{}', 'New', NULL, NULL, NULL, '', NULL, 0, NULL, 0, NULL, 0, NULL, 0, 'Friends/Relatives'),
(47, NULL, 'Legaaspi, Rey Beato', 'ochickik@email.com', 'ABM', 'Male', 'Married', 'SHS', NULL, 'new', '2025-07-20 11:23:21', 'Filipino', 'Catholic', 'Region IV-A - CALABARZON', 'Cavite', 'Dasmariñas', 'Salitran', 'Legaaspi', 'Rey', 'Beato', NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09857575641', NULL, '2000-08-09', 'Cavite', 'Tagalog', 'Imus Central School', '2012', 'Dasmariñas National High School', '2016', 'Venus', 'Villanueva', 'Rey', 'Beato', 0, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '09234657781', NULL, 'Carpentry', 'Villanueva', 'Rey', 'Beato', 0, 'Villanueva', 'Rey', 'Beato', 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '09778756632', NULL, 'Domestic Helper', 'Villanueva', 'Rey', 'Beato', 'Father', 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '09778756632', NULL, 'Carpentry', NULL, 'NCST-2025-00047', NULL, '', NULL, NULL, NULL, '', NULL, 0, NULL, 0, NULL, 0, NULL, 0, ''),
(48, NULL, 'Legaaspi, Rey Beato', 'ochickik@email.com', 'STEM', 'Male', 'Married', 'SHS', NULL, 'pending', '2025-07-20 11:26:18', 'Filipino', 'Catholic', 'Region IV-A - CALABARZON', 'Cavite', 'Dasmariñas', 'Salitran', 'Legaaspi', 'Rey', 'Beato', NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09857575641', NULL, '2000-08-09', 'Cavite', 'Tagalog', 'Imus Central School', '2012', 'Dasmariñas National High School', '2016', 'Venus', 'Villanueva', 'Rey', 'Beato', 0, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '09234657781', NULL, 'Carpentry', 'Villanueva', 'Rey', 'Beato', 0, 'Villanueva', 'Rey', 'Beato', 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '09778756632', NULL, 'Domestic Helper', 'Villanueva', 'Rey', 'Beato', 'Father', 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '09778756632', NULL, 'Carpentry', NULL, 'NCST-2025-00048', '{\"Properly accomplished admission form\":1,\"Four (4) 2x2 recent, identical color pictures in white background with name tag\":1,\"Five (5) 1x1 recent, identical color pictures in white background with name tag\":1,\"Submit original and photocopied Form 138 / Report Card\":1,\"Submit original Good Moral Character certificate with dry seal and Photocopied\":1,\"If married, two (2) photocopies of marriage certificate duly signed by a priest / minister\":1,\"1pc. Long Brown Envelope\":1}', '', NULL, NULL, NULL, '', NULL, 0, NULL, 0, NULL, 0, NULL, 0, ''),
(49, NULL, 'Legaaspi, Rey Beato', 'ochickik@email.com', 'STEM', 'Male', 'Single', 'SHS', NULL, 'rejected', '2025-07-20 11:28:38', 'Italian', 'Muslim', 'Region IV-A - CALABARZON', 'Batangas', 'Batangas City', 'Poblacion', 'Legaaspi', 'Rey', 'Beato', NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09857575641', NULL, '2000-08-09', 'Cavite', 'Tagalog', 'Dasmariñas Elementary School', '2012', 'Dasmariñas National High School', '2016', 'Venus', 'Villanueva', 'Rey', 'Beato', 0, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', NULL, NULL, 'Carpentry', 'Villanueva', 'Rey', 'Beato', 0, 'Villanueva', 'Rey', 'Beato', 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '09778756632', NULL, 'Domestic Helper', 'Villanueva', 'Rey', 'Beato', 'Father', 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '09778756632', NULL, 'Carpentry', NULL, 'NCST-2025-00049', '{}', '', NULL, NULL, NULL, '', NULL, 0, NULL, 0, NULL, 0, NULL, 0, ''),
(50, NULL, 'Villanueva, Ychicko Beato', 'ochickik@email.com', 'BSHM', 'Female', 'Single', 'College', NULL, 'approved', '2025-07-20 11:31:03', NULL, NULL, 'Region IV-A - CALABARZON', 'Cavite', 'Imus', 'Buhay na Tubig', 'Villanueva', 'Ychicko', 'Beato', NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09747558512', 'United States', '2000-09-09', 'Cavite', NULL, 'Dasmariñas Elementary School', '2012', 'Dasmariñas National High School', '2016', '', 'Villanueva', 'Ychicko', 'Beato', 0, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '09857575641', 'United States', 'Carpentry', 'Legaaspi', 'Ychicko', 'Beato', 0, 'Legaaspi', 'Ychicko', 'Beato', 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '09778756632', 'United States', 'Domestic Helper', 'Legaaspi', 'Ychicko', 'Beato', 'Father', 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '09778756632', NULL, 'Carpentry', NULL, 'NCST-2025-00050', '{}', 'Transferee', 'NCST', NULL, NULL, '', NULL, 0, NULL, 0, NULL, 0, NULL, 0, 'Posters'),
(51, NULL, 'Villanueva, Rey Beato', 'ochickik@email.com', 'STEM', 'Male', 'Married', 'SHS', NULL, 'approved', '2025-07-20 11:41:36', 'Filipino', 'Christian', 'Region IV-A - CALABARZON', 'Cavite', 'Dasmariñas', 'Salitran', 'Villanueva', 'Rey', 'Beato', NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09857575641', NULL, '2000-07-11', 'Cavite', 'Tagalog', 'Dasmariñas Elementary School', '2012', 'Imus National High School', '2016', 'Venus', 'Villanueva', 'Rey', 'Beato', 0, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '09857575641', NULL, 'Carpentry', 'Villanueva', 'Rey', 'Beato', 0, 'Villanueva', 'Rey', 'Beato', 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '09778756632', NULL, 'Domestic Helper', 'Villanueva', 'Rey', 'Beato', 'Father', 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '09778756632', NULL, 'Carpentry', NULL, 'NCST-2025-00051', '{}', '', NULL, NULL, NULL, '', NULL, 0, NULL, 0, NULL, 0, NULL, 0, ''),
(52, NULL, 'Villanueva, Rey Beato', 'ochickik@email.com', 'ABM', 'Male', 'Widowed', 'SHS', NULL, 'approved', '2025-07-20 11:47:04', 'American', 'Catholic', 'Region IV-A - CALABARZON', 'Cavite', 'Imus', 'Buhay na Tubig', 'Villanueva', 'Rey', 'Beato', NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09857575641', NULL, '2000-07-11', 'Cavite', 'Tagalog', 'Bacoor Elementary School', '2012', 'Dasmariñas National High School', '2016', 'Venus', 'Villanueva', 'Rey', 'Beato', 0, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', NULL, NULL, 'Carpentry', 'Villanueva', 'Rey', 'Beato', 0, 'Villanueva', 'Rey', 'Beato', 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '09778756632', NULL, 'Domestic Helper', 'Villanueva', 'Rey', 'Beato', 'Mother', 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '09778756632', NULL, 'Carpentry', NULL, 'NCST-2025-00052', '{}', '', NULL, NULL, NULL, '', NULL, 0, NULL, 0, NULL, 0, NULL, 0, ''),
(53, NULL, 'Legaaspi, Rey Beato', 'ochickik@email.com', 'HUMSS', 'Male', 'Single', 'SHS', NULL, 'rejected', '2025-07-20 12:04:26', 'Filipino', 'Catholic', 'Region IV-A - CALABARZON', 'Cavite', 'Dasmariñas', 'San Agustin', 'Legaaspi', 'Rey', 'Beato', NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09778674536', NULL, '2000-08-09', 'Cavite', 'Tagalog', 'Imus Central School', '2012', 'Imus National High School', '2016', 'Venus', 'Villanueva', 'Rey', 'Beato', 0, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '09857575641', NULL, 'Carpentry', 'Villanueva', 'Rey', 'Beato', 0, 'Villanueva', 'Rey', 'Beato', 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', NULL, NULL, 'Domestic Helper', 'Villanueva', 'Rey', 'Beato', 'Father', 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '09778756632', NULL, 'Carpentry', NULL, 'NCST-2025-00053', '{}', '', NULL, NULL, NULL, '', NULL, 0, NULL, 0, NULL, 0, NULL, 0, ''),
(54, NULL, 'Legaaspi, Rey Beato', 'ochickik@email.com', 'ABM', 'Female', 'Single', 'SHS', NULL, 'rejected', '2025-07-20 14:50:20', 'Chinese', 'Muslim', 'Region IV-A - CALABARZON', 'Laguna', 'Calamba', 'Canlubang', 'Legaaspi', 'Rey', 'Beato', NULL, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '4114', '09778674536', NULL, '2000-08-09', 'Cavite', 'Tagalog', 'Bacoor Elementary School', '2012', 'Imus National High School', '2016', 'Venus', 'Villanueva', 'Rey', 'Beato', 0, 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '09857575641', NULL, 'Carpentry', 'Villanueva', 'Rey', 'Beato', 0, 'Villanueva', 'Rey', 'Beato', 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', NULL, NULL, 'Domestic Helper', 'Villanueva', 'Rey', 'Beato', 'Father', 'Blk 155 Chalplot Phase 4 Paliparan 3 Dasmarinas Cavite', '09778756632', NULL, 'Carpentry', NULL, 'NCST-2025-00054', '{}', '', NULL, NULL, NULL, '', NULL, 0, NULL, 0, NULL, 0, NULL, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `student_subjects`
--

CREATE TABLE `student_subjects` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `enrollment_status` varchar(20) DEFAULT 'enrolled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` enum('admin','admission','registration','student') NOT NULL DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `role`, `created_at`) VALUES
(11, 'admission2', '$2y$10$qiwrLB36vhgOWSnTnTXiZeeLXFQMn6e9NjM2KFRa4EHQqjOvi072K', 'Admission Officer', 'admission', '2025-07-17 16:29:20'),
(12, 'admin2', '$2y$10$FzcLL8/pqIwAsQq68di18.adnh9rMweolcJWmyB.oh4TpMiklq.RK', 'Admin', 'admin', '2025-07-17 16:29:20'),
(13, 'registration2', '$2y$10$vfFoBMErpDFUS9hbVq0pKuqV066Wi3KtzTxBeQJRR0Z8PAwjE228O', 'Registration Officer', 'registration', '2025-07-17 16:29:20'),
(14, 'registration3', '$2y$10$ZUzUPe7dVGI2Iep0rKybIu0mRZhJJZ/K8l/KE7OIPwU/VRxqMZnda', 'Grace Gagalac', 'registration', '2025-07-20 12:37:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `enrolled_subjects`
--
ALTER TABLE `enrolled_subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `section_name` (`section_name`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_number` (`student_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `program_id` (`program_id`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `student_applications`
--
ALTER TABLE `student_applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tracking_number` (`tracking_number`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- Indexes for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_enrollment` (`student_id`,`subject_id`,`schedule_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `enrolled_subjects`
--
ALTER TABLE `enrolled_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=253;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_applications`
--
ALTER TABLE `student_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `student_subjects`
--
ALTER TABLE `student_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`);

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`),
  ADD CONSTRAINT `students_ibfk_3` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
