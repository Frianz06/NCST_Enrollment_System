-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 04, 2025 at 06:00 PM
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
  `student_id` varchar(20) NOT NULL,
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
-- Table structure for table `queue_system`
--

CREATE TABLE `queue_system` (
  `id` int(11) NOT NULL,
  `queue_number` varchar(20) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `status` enum('waiting','processing','completed','cancelled') DEFAULT 'waiting',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `processed_at` timestamp NULL DEFAULT NULL,
  `queue_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(10, 1, 'BSIT - 12M1', '1', '2', 'M', 1, 40, 'college'),
(11, 1, 'BSIT - 12M2', '1', '2', 'M', 2, 40, 'college'),
(12, 1, 'BSIT - 12M3', '1', '2', 'M', 3, 40, 'college'),
(13, 1, 'BSIT - 12A1', '1', '2', 'A', 1, 40, 'college'),
(14, 1, 'BSIT - 12A2', '1', '2', 'A', 2, 40, 'college'),
(15, 1, 'BSIT - 12A3', '1', '2', 'A', 3, 40, 'college'),
(16, 1, 'BSIT - 12E1', '1', '2', 'E', 1, 40, 'college'),
(17, 1, 'BSIT - 12E2', '1', '2', 'E', 2, 40, 'college'),
(18, 1, 'BSIT - 12E3', '1', '2', 'E', 3, 40, 'college'),
(19, 1, 'BSIT - 21M1', '2', '1', 'M', 1, 40, 'college'),
(20, 1, 'BSIT - 21M2', '2', '1', 'M', 2, 40, 'college'),
(21, 1, 'BSIT - 21M3', '2', '1', 'M', 3, 40, 'college'),
(22, 1, 'BSIT - 21A1', '2', '1', 'A', 1, 40, 'college'),
(23, 1, 'BSIT - 21A2', '2', '1', 'A', 2, 40, 'college'),
(24, 1, 'BSIT - 21A3', '2', '1', 'A', 3, 40, 'college'),
(25, 1, 'BSIT - 21E1', '2', '1', 'E', 1, 40, 'college'),
(26, 1, 'BSIT - 21E2', '2', '1', 'E', 2, 40, 'college'),
(27, 1, 'BSIT - 21E3', '2', '1', 'E', 3, 40, 'college'),
(28, 1, 'BSIT - 22M1', '2', '2', 'M', 1, 40, 'college'),
(29, 1, 'BSIT - 22M2', '2', '2', 'M', 2, 40, 'college'),
(30, 1, 'BSIT - 22M3', '2', '2', 'M', 3, 40, 'college'),
(31, 1, 'BSIT - 22A1', '2', '2', 'A', 1, 40, 'college'),
(32, 1, 'BSIT - 22A2', '2', '2', 'A', 2, 40, 'college'),
(33, 1, 'BSIT - 22A3', '2', '2', 'A', 3, 40, 'college'),
(34, 1, 'BSIT - 22E1', '2', '2', 'E', 1, 40, 'college'),
(35, 1, 'BSIT - 22E2', '2', '2', 'E', 2, 40, 'college'),
(36, 1, 'BSIT - 22E3', '2', '2', 'E', 3, 40, 'college'),
(37, 1, 'BSIT - 31M1', '3', '1', 'M', 1, 40, 'college'),
(38, 1, 'BSIT - 31M2', '3', '1', 'M', 2, 40, 'college'),
(39, 1, 'BSIT - 31M3', '3', '1', 'M', 3, 40, 'college'),
(40, 1, 'BSIT - 31A1', '3', '1', 'A', 1, 40, 'college'),
(41, 1, 'BSIT - 31A2', '3', '1', 'A', 2, 40, 'college'),
(42, 1, 'BSIT - 31A3', '3', '1', 'A', 3, 40, 'college'),
(43, 1, 'BSIT - 31E1', '3', '1', 'E', 1, 40, 'college'),
(44, 1, 'BSIT - 31E2', '3', '1', 'E', 2, 40, 'college'),
(45, 1, 'BSIT - 31E3', '3', '1', 'E', 3, 40, 'college'),
(46, 1, 'BSIT - 32M1', '3', '2', 'M', 1, 40, 'college'),
(47, 1, 'BSIT - 32M2', '3', '2', 'M', 2, 40, 'college'),
(48, 1, 'BSIT - 32M3', '3', '2', 'M', 3, 40, 'college'),
(49, 1, 'BSIT - 32A1', '3', '2', 'A', 1, 40, 'college'),
(50, 1, 'BSIT - 32A2', '3', '2', 'A', 2, 40, 'college'),
(51, 1, 'BSIT - 32A3', '3', '2', 'A', 3, 40, 'college'),
(52, 1, 'BSIT - 32E1', '3', '2', 'E', 1, 40, 'college'),
(53, 1, 'BSIT - 32E2', '3', '2', 'E', 2, 40, 'college'),
(54, 1, 'BSIT - 32E3', '3', '2', 'E', 3, 40, 'college'),
(55, 1, 'BSIT - 41M1', '4', '1', 'M', 1, 40, 'college'),
(56, 1, 'BSIT - 41M2', '4', '1', 'M', 2, 40, 'college'),
(57, 1, 'BSIT - 41M3', '4', '1', 'M', 3, 40, 'college'),
(58, 1, 'BSIT - 41A1', '4', '1', 'A', 1, 40, 'college'),
(59, 1, 'BSIT - 41A2', '4', '1', 'A', 2, 40, 'college'),
(60, 1, 'BSIT - 41A3', '4', '1', 'A', 3, 40, 'college'),
(61, 1, 'BSIT - 41E1', '4', '1', 'E', 1, 40, 'college'),
(62, 1, 'BSIT - 41E2', '4', '1', 'E', 2, 40, 'college'),
(63, 1, 'BSIT - 41E3', '4', '1', 'E', 3, 40, 'college'),
(64, 1, 'BSIT - 42M1', '4', '2', 'M', 1, 40, 'college'),
(65, 1, 'BSIT - 42M2', '4', '2', 'M', 2, 40, 'college'),
(66, 1, 'BSIT - 42M3', '4', '2', 'M', 3, 40, 'college'),
(67, 1, 'BSIT - 42A1', '4', '2', 'A', 1, 40, 'college'),
(68, 1, 'BSIT - 42A2', '4', '2', 'A', 2, 40, 'college'),
(69, 1, 'BSIT - 42A3', '4', '2', 'A', 3, 40, 'college'),
(70, 1, 'BSIT - 42E1', '4', '2', 'E', 1, 40, 'college'),
(71, 1, 'BSIT - 42E2', '4', '2', 'E', 2, 40, 'college'),
(72, 1, 'BSIT - 42E3', '4', '2', 'E', 3, 40, 'college'),
(73, 2, 'BSCS - 11M1', '1', '1', 'M', 1, 40, 'college'),
(74, 2, 'BSCS - 11M2', '1', '1', 'M', 2, 40, 'college'),
(75, 2, 'BSCS - 11A1', '1', '1', 'A', 1, 40, 'college'),
(76, 2, 'BSCS - 11A2', '1', '1', 'A', 2, 40, 'college'),
(77, 2, 'BSCS - 21M1', '2', '1', 'M', 1, 40, 'college'),
(78, 2, 'BSCS - 21M2', '2', '1', 'M', 2, 40, 'college'),
(79, 2, 'BSCS - 31M1', '3', '1', 'M', 1, 40, 'college'),
(80, 2, 'BSCS - 41M1', '4', '1', 'M', 1, 40, 'college'),
(81, 3, 'BSBA - 11M1', '1', '1', 'M', 1, 40, 'college'),
(82, 3, 'BSBA - 11M2', '1', '1', 'M', 2, 40, 'college'),
(83, 3, 'BSBA - 11A1', '1', '1', 'A', 1, 40, 'college'),
(84, 3, 'BSBA - 21M1', '2', '1', 'M', 1, 40, 'college'),
(85, 3, 'BSBA - 31M1', '3', '1', 'M', 1, 40, 'college'),
(86, 3, 'BSBA - 41M1', '4', '1', 'M', 1, 40, 'college'),
(87, 4, 'BSHM - 11M1', '1', '1', 'M', 1, 40, 'college'),
(88, 4, 'BSHM - 11A1', '1', '1', 'A', 1, 40, 'college'),
(89, 4, 'BSHM - 21M1', '2', '1', 'M', 1, 40, 'college'),
(90, 4, 'BSHM - 31M1', '3', '1', 'M', 1, 40, 'college'),
(91, 4, 'BSHM - 41M1', '4', '1', 'M', 1, 40, 'college'),
(92, 5, 'BSTM - 11M1', '1', '1', 'M', 1, 40, 'college'),
(93, 5, 'BSTM - 11A1', '1', '1', 'A', 1, 40, 'college'),
(94, 5, 'BSTM - 21M1', '2', '1', 'M', 1, 40, 'college'),
(95, 5, 'BSTM - 31M1', '3', '1', 'M', 1, 40, 'college'),
(96, 5, 'BSTM - 41M1', '4', '1', 'M', 1, 40, 'college'),
(97, 6, 'BSPSYCH - 11M1', '1', '1', 'M', 1, 40, 'college'),
(98, 6, 'BSPSYCH - 11A1', '1', '1', 'A', 1, 40, 'college'),
(99, 6, 'BSPSYCH - 21M1', '2', '1', 'M', 1, 40, 'college'),
(100, 6, 'BSPSYCH - 31M1', '3', '1', 'M', 1, 40, 'college'),
(101, 6, 'BSPSYCH - 41M1', '4', '1', 'M', 1, 40, 'college'),
(102, 7, 'BSCRIM - 11M1', '1', '1', 'M', 1, 40, 'college'),
(103, 7, 'BSCRIM - 11A1', '1', '1', 'A', 1, 40, 'college'),
(104, 7, 'BSCRIM - 21M1', '2', '1', 'M', 1, 40, 'college'),
(105, 7, 'BSCRIM - 31M1', '3', '1', 'M', 1, 40, 'college'),
(106, 7, 'BSCRIM - 41M1', '4', '1', 'M', 1, 40, 'college');

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `section_id`, `subject`, `day`, `time_start`, `time_end`, `room`, `instructor`) VALUES
-- BSIT 1st Year 1st Semester Schedules
(1, 1, 'GE 003C', 'Monday', '08:00:00', '09:00:00', 'Room 101', 'Prof. Santos'),
(2, 1, 'GE 003C', 'Wednesday', '08:00:00', '09:00:00', 'Room 101', 'Prof. Santos'),
(3, 1, 'GE 005', 'Monday', '09:00:00', '10:00:00', 'Room 102', 'Prof. Garcia'),
(4, 1, 'GE 005', 'Wednesday', '09:00:00', '10:00:00', 'Room 102', 'Prof. Garcia'),
(5, 1, 'GE 007', 'Monday', '10:00:00', '11:00:00', 'Room 103', 'Prof. Lopez'),
(6, 1, 'GE 007', 'Wednesday', '10:00:00', '11:00:00', 'Room 103', 'Prof. Lopez'),
(7, 1, 'GELEC 004-IT', 'Monday', '11:00:00', '12:00:00', 'Room 104', 'Prof. Cruz'),
(8, 1, 'GELEC 004-IT', 'Wednesday', '11:00:00', '12:00:00', 'Room 104', 'Prof. Cruz'),
(9, 1, 'IT 101', 'Tuesday', '08:00:00', '10:00:00', 'Computer Lab 1', 'Prof. Martinez'),
(10, 1, 'IT 101', 'Thursday', '08:00:00', '10:00:00', 'Computer Lab 1', 'Prof. Martinez'),
(11, 1, 'IT 102', 'Tuesday', '10:00:00', '12:00:00', 'Computer Lab 2', 'Prof. Rodriguez'),
(12, 1, 'IT 102', 'Thursday', '10:00:00', '12:00:00', 'Computer Lab 2', 'Prof. Rodriguez'),
(13, 1, 'NCST 001', 'Tuesday', '13:00:00', '15:00:00', 'Room 105', 'Prof. Torres'),
(14, 1, 'NCST 001', 'Thursday', '13:00:00', '15:00:00', 'Room 105', 'Prof. Torres'),
(15, 1, 'NSTP 001', 'Friday', '08:00:00', '10:00:00', 'Room 201', 'Prof. Gonzales'),
(16, 1, 'NSTP 001', 'Friday', '10:00:00', '12:00:00', 'Room 201', 'Prof. Gonzales'),
(17, 1, 'PATHFIT 1', 'Friday', '13:00:00', '15:00:00', 'Gymnasium', 'Prof. Hernandez'),
(18, 1, 'PATHFIT 1', 'Friday', '15:00:00', '17:00:00', 'Gymnasium', 'Prof. Hernandez'),

-- BSIT 11M2 Schedule
(21, 2, 'GE 003C', 'Monday', '08:00:00', '09:00:00', 'Room 106', 'Prof. Santos'),
(22, 2, 'GE 003C', 'Wednesday', '08:00:00', '09:00:00', 'Room 106', 'Prof. Santos'),
(23, 2, 'GE 005', 'Monday', '09:00:00', '10:00:00', 'Room 107', 'Prof. Garcia'),
(24, 2, 'GE 005', 'Wednesday', '09:00:00', '10:00:00', 'Room 107', 'Prof. Garcia'),
(25, 2, 'GE 007', 'Monday', '10:00:00', '11:00:00', 'Room 108', 'Prof. Lopez'),
(26, 2, 'GE 007', 'Wednesday', '10:00:00', '11:00:00', 'Room 108', 'Prof. Lopez'),
(27, 2, 'GELEC 004-IT', 'Monday', '11:00:00', '12:00:00', 'Room 109', 'Prof. Cruz'),
(28, 2, 'GELEC 004-IT', 'Wednesday', '11:00:00', '12:00:00', 'Room 109', 'Prof. Cruz'),
(29, 2, 'IT 101', 'Tuesday', '08:00:00', '10:00:00', 'Computer Lab 5', 'Prof. Martinez'),
(30, 2, 'IT 101', 'Thursday', '08:00:00', '10:00:00', 'Computer Lab 5', 'Prof. Martinez'),
(31, 2, 'IT 102', 'Tuesday', '10:00:00', '12:00:00', 'Computer Lab 6', 'Prof. Rodriguez'),
(32, 2, 'IT 102', 'Thursday', '10:00:00', '12:00:00', 'Computer Lab 6', 'Prof. Rodriguez'),
(33, 2, 'NCST 001', 'Tuesday', '13:00:00', '15:00:00', 'Room 110', 'Prof. Torres'),
(34, 2, 'NCST 001', 'Thursday', '13:00:00', '15:00:00', 'Room 110', 'Prof. Torres'),
(35, 2, 'NSTP 001', 'Friday', '08:00:00', '10:00:00', 'Room 202', 'Prof. Gonzales'),
(36, 2, 'NSTP 001', 'Friday', '10:00:00', '12:00:00', 'Room 202', 'Prof. Gonzales'),
(37, 2, 'PATHFIT 1', 'Friday', '13:00:00', '15:00:00', 'Gymnasium', 'Prof. Hernandez'),
(38, 2, 'PATHFIT 1', 'Friday', '15:00:00', '17:00:00', 'Gymnasium', 'Prof. Hernandez'),

-- BSIT 11A1 Schedule (Afternoon)
(41, 4, 'GE 003C', 'Monday', '13:00:00', '14:00:00', 'Room 101', 'Prof. Santos'),
(42, 4, 'GE 003C', 'Wednesday', '13:00:00', '14:00:00', 'Room 101', 'Prof. Santos'),
(43, 4, 'GE 005', 'Monday', '14:00:00', '15:00:00', 'Room 102', 'Prof. Garcia'),
(44, 4, 'GE 005', 'Wednesday', '14:00:00', '15:00:00', 'Room 102', 'Prof. Garcia'),
(45, 4, 'GE 007', 'Monday', '15:00:00', '16:00:00', 'Room 103', 'Prof. Lopez'),
(46, 4, 'GE 007', 'Wednesday', '15:00:00', '16:00:00', 'Room 103', 'Prof. Lopez'),
(47, 4, 'GELEC 004-IT', 'Monday', '16:00:00', '17:00:00', 'Room 104', 'Prof. Cruz'),
(48, 4, 'GELEC 004-IT', 'Wednesday', '16:00:00', '17:00:00', 'Room 104', 'Prof. Cruz'),
(49, 4, 'IT 101', 'Tuesday', '13:00:00', '15:00:00', 'Computer Lab 1', 'Prof. Martinez'),
(50, 4, 'IT 101', 'Thursday', '13:00:00', '15:00:00', 'Computer Lab 1', 'Prof. Martinez'),
(51, 4, 'IT 102', 'Tuesday', '15:00:00', '17:00:00', 'Computer Lab 2', 'Prof. Rodriguez'),
(52, 4, 'IT 102', 'Thursday', '15:00:00', '17:00:00', 'Computer Lab 2', 'Prof. Rodriguez'),
(53, 4, 'NCST 001', 'Tuesday', '17:00:00', '19:00:00', 'Room 105', 'Prof. Torres'),
(54, 4, 'NCST 001', 'Thursday', '17:00:00', '19:00:00', 'Room 105', 'Prof. Torres'),
(55, 4, 'NSTP 001', 'Friday', '13:00:00', '15:00:00', 'Room 201', 'Prof. Gonzales'),
(56, 4, 'NSTP 001', 'Friday', '15:00:00', '17:00:00', 'Room 201', 'Prof. Gonzales'),
(57, 4, 'PATHFIT 1', 'Friday', '17:00:00', '19:00:00', 'Gymnasium', 'Prof. Hernandez'),
(58, 4, 'PATHFIT 1', 'Friday', '19:00:00', '21:00:00', 'Gymnasium', 'Prof. Hernandez'),

-- BSCS 11M1 Schedule
(61, 73, 'GE 003C', 'Monday', '08:00:00', '09:00:00', 'Room 301', 'Prof. Santos'),
(62, 73, 'GE 003C', 'Wednesday', '08:00:00', '09:00:00', 'Room 301', 'Prof. Santos'),
(63, 73, 'GE 005', 'Monday', '09:00:00', '10:00:00', 'Room 302', 'Prof. Garcia'),
(64, 73, 'GE 005', 'Wednesday', '09:00:00', '10:00:00', 'Room 302', 'Prof. Garcia'),
(65, 73, 'GE 007', 'Monday', '10:00:00', '11:00:00', 'Room 303', 'Prof. Lopez'),
(66, 73, 'GE 007', 'Wednesday', '10:00:00', '11:00:00', 'Room 303', 'Prof. Lopez'),
(67, 73, 'GELEC 004-IT', 'Monday', '11:00:00', '12:00:00', 'Room 304', 'Prof. Cruz'),
(68, 73, 'GELEC 004-IT', 'Wednesday', '11:00:00', '12:00:00', 'Room 304', 'Prof. Cruz'),
(69, 73, 'CS101', 'Tuesday', '08:00:00', '10:00:00', 'Computer Lab 9', 'Prof. Martinez'),
(70, 73, 'CS101', 'Thursday', '08:00:00', '10:00:00', 'Computer Lab 9', 'Prof. Martinez'),
(71, 73, 'CS102', 'Tuesday', '10:00:00', '12:00:00', 'Computer Lab 10', 'Prof. Rodriguez'),
(72, 73, 'CS102', 'Thursday', '10:00:00', '12:00:00', 'Computer Lab 10', 'Prof. Rodriguez'),
(73, 73, 'NCST 001', 'Tuesday', '13:00:00', '15:00:00', 'Room 305', 'Prof. Torres'),
(74, 73, 'NCST 001', 'Thursday', '13:00:00', '15:00:00', 'Room 305', 'Prof. Torres'),
(75, 73, 'NSTP 001', 'Friday', '08:00:00', '10:00:00', 'Room 301', 'Prof. Gonzales'),
(76, 73, 'NSTP 001', 'Friday', '10:00:00', '12:00:00', 'Room 301', 'Prof. Gonzales'),
(77, 73, 'PATHFIT 1', 'Friday', '13:00:00', '15:00:00', 'Gymnasium', 'Prof. Hernandez'),
(78, 73, 'PATHFIT 1', 'Friday', '15:00:00', '17:00:00', 'Gymnasium', 'Prof. Hernandez'),

-- BSBA 11M1 Schedule
(81, 81, 'GE 003C', 'Monday', '08:00:00', '09:00:00', 'Room 401', 'Prof. Santos'),
(82, 81, 'GE 003C', 'Wednesday', '08:00:00', '09:00:00', 'Room 401', 'Prof. Santos'),
(83, 81, 'GE 005', 'Monday', '09:00:00', '10:00:00', 'Room 402', 'Prof. Garcia'),
(84, 81, 'GE 005', 'Wednesday', '09:00:00', '10:00:00', 'Room 402', 'Prof. Garcia'),
(85, 81, 'GE 007', 'Monday', '10:00:00', '11:00:00', 'Room 403', 'Prof. Lopez'),
(86, 81, 'GE 007', 'Wednesday', '10:00:00', '11:00:00', 'Room 403', 'Prof. Lopez'),
(87, 81, 'GELEC 004-IT', 'Monday', '11:00:00', '12:00:00', 'Room 404', 'Prof. Cruz'),
(88, 81, 'GELEC 004-IT', 'Wednesday', '11:00:00', '12:00:00', 'Room 404', 'Prof. Cruz'),
(89, 81, 'BA101', 'Tuesday', '08:00:00', '10:00:00', 'Room 406', 'Prof. Martinez'),
(90, 81, 'BA101', 'Thursday', '08:00:00', '10:00:00', 'Room 406', 'Prof. Martinez'),
(91, 81, 'BA102', 'Tuesday', '10:00:00', '12:00:00', 'Room 407', 'Prof. Rodriguez'),
(92, 81, 'BA102', 'Thursday', '10:00:00', '12:00:00', 'Room 407', 'Prof. Rodriguez'),
(93, 81, 'NCST 001', 'Tuesday', '13:00:00', '15:00:00', 'Room 408', 'Prof. Torres'),
(94, 81, 'NCST 001', 'Thursday', '13:00:00', '15:00:00', 'Room 408', 'Prof. Torres'),
(95, 81, 'NSTP 001', 'Friday', '08:00:00', '10:00:00', 'Room 401', 'Prof. Gonzales'),
(96, 81, 'NSTP 001', 'Friday', '10:00:00', '12:00:00', 'Room 401', 'Prof. Gonzales'),
(97, 81, 'PATHFIT 1', 'Friday', '13:00:00', '15:00:00', 'Gymnasium', 'Prof. Hernandez'),
(98, 81, 'PATHFIT 1', 'Friday', '15:00:00', '17:00:00', 'Gymnasium', 'Prof. Hernandez');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `course` varchar(100) NOT NULL,
  `student_type` enum('college','senior_high') NOT NULL DEFAULT 'college',
  `program_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `year_level` varchar(20) DEFAULT '1',
  `tracking_number` varchar(32) DEFAULT NULL,
  `application_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive','graduated') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_id`, `password`, `name`, `email`, `course`, `student_type`, `program_id`, `section_id`, `year_level`, `tracking_number`, `application_id`, `created_at`, `status`) VALUES
(0, '2025-00001', '$2y$10$sSkinQegcRXct/8N.vGP9O0w.7sddL8oJcWQ8Q5pKKRS3sGe6r0VC', 'Ychicko Frian T. Legaspi', 'yckolegaspi@gmail.com', 'BSIT', 'college', 1, NULL, '1', 'NCST-2025-00000', 0, '2025-08-03 17:23:01', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `student_applications`
--

CREATE TABLE `student_applications` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `course_or_track` varchar(100) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `civil_status` varchar(50) NOT NULL,
  `type` varchar(20) NOT NULL,
  `admission_type` varchar(30) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'approved',
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

INSERT INTO `student_applications` (`id`, `student_id`, `email`, `course_or_track`, `gender`, `civil_status`, `type`, `admission_type`, `status`, `date_submitted`, `nationality`, `religion`, `region`, `province`, `city`, `barangay`, `last_name`, `first_name`, `middle_name`, `suffix`, `address`, `zip_code`, `mobile`, `landline`, `dob`, `pob`, `dialect`, `elementary_school`, `elementary_year_grad`, `high_school`, `high_year_grad`, `grade10_section`, `father_family_name`, `father_given_name`, `father_middle_name`, `father_deceased`, `father_address`, `father_mobile`, `father_landline`, `father_occupation`, `mother_family_name`, `mother_given_name`, `mother_middle_name`, `mother_deceased`, `mother_maiden_family_name`, `mother_maiden_given_name`, `mother_maiden_middle_name`, `mother_address`, `mother_mobile`, `mother_landline`, `mother_occupation`, `guardian_family_name`, `guardian_given_name`, `guardian_middle_name`, `guardian_relationship`, `guardian_address`, `guardian_mobile`, `guardian_landline`, `guardian_occupation`, `requirements_status`, `tracking_number`, `requirements_checklist`, `student_type`, `tertiary_school`, `tertiary_year_grad`, `course_graduated`, `educational_plan`, `academic_achievement`, `is_working`, `employer`, `work_in_shifts`, `work_position`, `family_connected_ncst`, `ncst_relationship`, `no_of_siblings`, `how_did_you_know_ncst`) VALUES
(0, '2025-00001', 'yckolegaspi@gmail.com', 'BSIT', 'Male', 'Single', 'College', 'College', 'approved', '2025-08-04 01:23:01', 'Filipino', 'h', 'Region IV-A', 'Cavite', 'Dasmariñas', 'Zone II', 'Legaspi', 'Ychicko', 'Frian T.', NULL, 'Pallas Athena Classique Valenza Str. Anabu 2-D', '4103', '232', 'Philippines', '2025-08-04', 'Not Specified', NULL, 'Dasmariñas Elementary School', '23', 'Dasmariñas National High School', '32', 'Not Applicable', 'Legaspi', 'Ychicko', 'Frian T.', 0, 'Pallas Athena Classique Valenza Str. Anabu 2-D', NULL, 'Philippines', NULL, 'Legaspi', 'Ychicko', 'Frian T.', 0, 'Legaspi', 'Ychicko', 'Frian T.', 'Pallas Athena Classique Valenza Str. Anabu 2-D', NULL, 'Philippines', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'NCST-2025-00000', NULL, 'New', 'Not Specified', 'Not Specif', 'Not Specified', 'Not Specified', 'Not Specified', 0, 'Not Specified', 0, 'Not Specified', 0, 'Not Specified', 0, 'Not Specified');

-- --------------------------------------------------------

--
-- Table structure for table `student_grades`
--

CREATE TABLE `student_grades` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `grade` decimal(5,2) DEFAULT NULL,
  `semester` varchar(20) NOT NULL,
  `year_level` varchar(20) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `status` enum('PASSED','FAILED','INCOMPLETE') DEFAULT 'INCOMPLETE',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_subjects`
--

CREATE TABLE `student_subjects` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `enrollment_status` varchar(20) DEFAULT 'enrolled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `subject_name` varchar(200) NOT NULL,
  `units` int(11) NOT NULL DEFAULT 3,
  `semester` varchar(20) NOT NULL,
  `year_level` varchar(20) NOT NULL,
  `program_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `prerequisite` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_code`, `subject_name`, `units`, `semester`, `year_level`, `program_id`, `description`, `prerequisite`) VALUES
(1, 'GE 003C', 'Mathematics in the Modern World', 3, '1st', '1st Year', 1, 'Mathematics in the Modern World', NULL),
(2, 'GE 005', 'Science, Technology & Society', 3, '1st', '1st Year', 1, 'Science, Technology & Society', NULL),
(3, 'GE 007', 'Contemporary World', 3, '1st', '1st Year', 1, 'Contemporary World', NULL),
(4, 'GELEC 004-IT', 'Living in the IT Era', 3, '1st', '1st Year', 1, 'Living in the IT Era', NULL),
(5, 'IT 101', 'Introduction to Computing', 3, '1st', '1st Year', 1, 'Introduction to Computing', NULL),
(6, 'IT 102', 'Computer Programming 1', 3, '1st', '1st Year', 1, 'Computer Programming 1', NULL),
(7, 'NCST 001', 'Nation Builders (NCST Culture 1)', 3, '1st', '1st Year', 1, 'Nation Builders (NCST Culture 1)', NULL),
(8, 'NSTP 001', 'National Service Training Program 1', 0, '1st', '1st Year', 1, 'National Service Training Program 1', NULL),
(9, 'PATHFIT 1', 'Physical Activities Towards Health & Fitness 1', 2, '1st', '1st Year', 1, 'Physical Activities Towards Health & Fitness 1', NULL),
(10, 'GE 004', 'Understanding the Self', 3, '2nd', '1st Year', 1, 'Understanding the Self', NULL),
(11, 'GE 008', 'Art Appreciation', 3, '2nd', '1st Year', 1, 'Art Appreciation', NULL),
(12, 'GELEC 012-IT', 'Reading Visual Art', 3, '2nd', '1st Year', 1, 'Reading Visual Art', NULL),
(13, 'IT 103', 'Computer Programming 2', 3, '2nd', '1st Year', 1, 'Computer Programming 2', 'IT 102'),
(14, 'IT 104', 'Web System Technologies 1', 3, '2nd', '1st Year', 1, 'Web System Technologies 1', 'IT 101'),
(15, 'IT 105', 'Discrete Structures 1', 3, '2nd', '1st Year', 1, 'Discrete Structures 1', NULL),
(16, 'IT 106', 'Networking 1', 3, '2nd', '1st Year', 1, 'Networking 1', 'IT 101'),
(17, 'NSTP 002', 'National Service Training Program 2', 0, '2nd', '1st Year', 1, 'National Service Training Program 2', 'NSTP 001'),
(18, 'PATHFIT 2', 'Physical Activities Towards Health & Fitness 2', 2, '2nd', '1st Year', 1, 'Physical Activities Towards Health & Fitness 2', NULL),
(19, 'GE 002', 'Readings in Philippine History', 3, '1st', '2nd Year', 1, 'Readings in Philippine History', NULL),
(20, 'GE 006', 'Ethics', 3, '1st', '2nd Year', 1, 'Ethics', NULL),
(21, 'IT 201', 'Information Management 1', 3, '1st', '2nd Year', 1, 'Information Management 1', 'IT 103'),
(22, 'IT 202', 'Interactive Media Design', 3, '1st', '2nd Year', 1, 'Interactive Media Design', 'GELEC 012-IT'),
(23, 'IT 203', 'Integrative Programming Technologies 1', 3, '1st', '2nd Year', 1, 'Integrative Programming Technologies 1', 'IT 103'),
(24, 'IT 204', 'Discrete Structures 2', 3, '1st', '2nd Year', 1, 'Discrete Structures 2', 'IT 105'),
(25, 'IT 205', 'Data Structures and Algorithms', 3, '1st', '2nd Year', 1, 'Data Structures and Algorithms', 'IT 103'),
(26, 'IT 206', 'Web System Technologies 2', 3, '1st', '2nd Year', 1, 'Web System Technologies 2', 'IT 104'),
(27, 'PATHFIT 3', 'Physical Activities Towards Health & Fitness 3', 2, '1st', '2nd Year', 1, 'Physical Activities Towards Health & Fitness 3', NULL),
(28, 'GE 001D', 'Purposive Communication', 3, '2nd', '2nd Year', 1, 'Purposive Communication', NULL),
(29, 'GELEC 009-IT', 'The Entrepreneurial Mind', 3, '2nd', '2nd Year', 1, 'The Entrepreneurial Mind', NULL),
(30, 'IT 207', 'Application Development and Emerging Technologies 1', 3, '2nd', '2nd Year', 1, 'Application Development and Emerging Technologies 1', 'IT 203'),
(31, 'IT 208', 'Object Oriented Programming', 3, '2nd', '2nd Year', 1, 'Object Oriented Programming', 'IT 203'),
(32, 'IT 209', 'Platform Technologies', 3, '2nd', '2nd Year', 1, 'Platform Technologies', 'IT 101'),
(33, 'IT 210', 'Human Computer Interaction 1', 3, '2nd', '2nd Year', 1, 'Human Computer Interaction 1', 'IT 205'),
(34, 'IT 211', 'Information Management 2', 3, '2nd', '2nd Year', 1, 'Information Management 2', 'IT 201'),
(35, 'NCST 002', 'Nation Builders (NCST Culture 2)', 3, '2nd', '2nd Year', 1, 'Nation Builders (NCST Culture 2)', 'NCST 001'),
(36, 'PATHFIT 4', 'Physical Activities Towards Health & Fitness 4', 2, '2nd', '2nd Year', 1, 'Physical Activities Towards Health & Fitness 4', NULL),
(37, 'IT 301', 'Human Computer Interaction 2', 3, '1st', '3rd Year', 1, 'Human Computer Interaction 2', 'IT 210'),
(38, 'IT 302', 'Systems Integration and Architecture 1', 3, '1st', '3rd Year', 1, 'Systems Integration and Architecture 1', 'IT 209'),
(39, 'IT 303', 'Networking 2', 3, '1st', '3rd Year', 1, 'Networking 2', 'IT 106'),
(40, 'IT 304', 'Quantitative Method', 3, '1st', '3rd Year', 1, 'Quantitative Method', 'IT 204'),
(41, 'IT 305', 'Social Issues and Professional Practice', 3, '1st', '3rd Year', 1, 'Social Issues and Professional Practice', 'IT 101'),
(42, 'IT 306', 'Integrative Programming Technologies 2', 3, '1st', '3rd Year', 1, 'Integrative Programming Technologies 2', 'IT 203'),
(43, 'IT 307', 'Networking 3', 3, '2nd', '3rd Year', 1, 'Networking 3', 'IT 303'),
(44, 'IT 308', 'Systems Integration and Architecture 2', 3, '2nd', '3rd Year', 1, 'Systems Integration and Architecture 2', 'IT 302'),
(45, 'IT 309', 'Mobile Systems and Application', 3, '2nd', '3rd Year', 1, 'Mobile Systems and Application', 'IT 306'),
(46, 'IT 310', 'Information Assurance and Security 1', 3, '2nd', '3rd Year', 1, 'Information Assurance and Security 1', 'IT 303'),
(47, 'IT 311', 'IT Capstone Project 1', 3, '2nd', '3rd Year', 1, 'IT Capstone Project 1', 'IT 302'),
(48, 'NCST 003', 'Nation Builders (NCST Culture 3)', 1, '2nd', '3rd Year', 1, 'Nation Builders (NCST Culture 3)', 'NCST 002'),
(49, 'GE 009', 'Rizal\'s Life and Works', 3, '1st', '4th Year', 1, 'Rizal\'s Life and Works', NULL),
(50, 'IT 401', 'Information Assurance and Security 2', 3, '1st', '4th Year', 1, 'Information Assurance and Security 2', 'IT 310'),
(51, 'IT 402', 'Technopreneurship', 3, '1st', '4th Year', 1, 'Technopreneurship', 'GELEC 009-IT'),
(52, 'IT 403', 'System Administration and Maintenance', 3, '1st', '4th Year', 1, 'System Administration and Maintenance', 'IT 310'),
(53, 'IT 404', 'IT Capstone Project 2', 3, '1st', '4th Year', 1, 'IT Capstone Project 2', 'IT 311'),
(54, 'NCST 004', 'Nation Builders (NCST Culture 4)', 1, '1st', '4th Year', 1, 'Nation Builders (NCST Culture 4)', 'NCST 003'),
(55, 'IT 405', 'IT Practicum (486 Hours)', 6, '2nd', '4th Year', 1, 'IT Practicum (486 Hours)', 'IT 404'),
(56, 'CS101', 'Introduction to Computer Science', 3, '1st', '1st Year', 2, 'Fundamentals of computer science', NULL),
(57, 'CS102', 'Computer Programming 1', 3, '2nd', '1st Year', 2, 'Programming fundamentals', 'CS101'),
(58, 'CS201', 'Data Structures', 3, '1st', '2nd Year', 2, 'Data organization and algorithms', 'CS102'),
(59, 'CS202', 'Computer Architecture', 3, '2nd', '2nd Year', 2, 'Computer hardware design', 'CS201'),
(60, 'CS301', 'Software Engineering', 3, '1st', '3rd Year', 2, 'Software development process', 'CS202'),
(61, 'CS302', 'Database Systems', 3, '2nd', '3rd Year', 2, 'Database design and management', 'CS301'),
(62, 'CS401', 'Capstone Project', 6, '1st', '4th Year', 2, 'Final project implementation', 'CS302'),
(63, 'CS402', 'OJT/Internship', 6, '2nd', '4th Year', 2, 'On-the-job training', 'CS401'),
(64, 'BA101', 'Introduction to Business', 3, '1st', '1st Year', 3, 'Business fundamentals', NULL),
(65, 'BA102', 'Business Mathematics', 3, '2nd', '1st Year', 3, 'Mathematical applications in business', 'BA101'),
(66, 'BA201', 'Principles of Management', 3, '1st', '2nd Year', 3, 'Management concepts and practices', 'BA102'),
(67, 'BA202', 'Business Statistics', 3, '2nd', '2nd Year', 3, 'Statistical analysis for business', 'BA201'),
(68, 'BA301', 'Marketing Management', 3, '1st', '3rd Year', 3, 'Marketing principles and strategies', 'BA202'),
(69, 'BA302', 'Financial Management', 3, '2nd', '3rd Year', 3, 'Financial planning and control', 'BA301'),
(70, 'BA401', 'Strategic Management', 3, '1st', '4th Year', 3, 'Business strategy formulation', 'BA302'),
(71, 'BA402', 'Business Internship', 6, '2nd', '4th Year', 3, 'On-the-job training', 'BA401'),
(72, 'HM101', 'Introduction to Hospitality', 3, '1st', '1st Year', 4, 'Hospitality industry overview', NULL),
(73, 'HM102', 'Food and Beverage Service', 3, '2nd', '1st Year', 4, 'Food service operations', 'HM101'),
(74, 'HM201', 'Hotel Operations', 3, '1st', '2nd Year', 4, 'Hotel management principles', 'HM102'),
(75, 'HM202', 'Culinary Arts', 3, '2nd', '2nd Year', 4, 'Cooking techniques and methods', 'HM201'),
(76, 'HM301', 'Event Management', 3, '1st', '3rd Year', 4, 'Event planning and coordination', 'HM202'),
(77, 'HM302', 'Tourism Management', 3, '2nd', '3rd Year', 4, 'Tourism industry management', 'HM301'),
(78, 'HM401', 'Hospitality Internship', 6, '1st', '4th Year', 4, 'Industry training', 'HM302'),
(79, 'HM402', 'Capstone Project', 3, '2nd', '4th Year', 4, 'Final project', 'HM401'),
(80, 'TM101', 'Introduction to Tourism', 3, '1st', '1st Year', 5, 'Tourism industry fundamentals', NULL),
(81, 'TM102', 'Tourism Geography', 3, '2nd', '1st Year', 5, 'Geographic aspects of tourism', 'TM101'),
(82, 'TM201', 'Tourism Marketing', 3, '1st', '2nd Year', 5, 'Marketing in tourism industry', 'TM102'),
(83, 'TM202', 'Travel Agency Operations', 3, '2nd', '2nd Year', 5, 'Travel agency management', 'TM201'),
(84, 'TM301', 'Tourism Planning', 3, '1st', '3rd Year', 5, 'Tourism development planning', 'TM202'),
(85, 'TM302', 'Sustainable Tourism', 3, '2nd', '3rd Year', 5, 'Environmental tourism practices', 'TM301'),
(86, 'TM401', 'Tourism Internship', 6, '1st', '4th Year', 5, 'Industry training', 'TM302'),
(87, 'TM402', 'Tourism Capstone', 3, '2nd', '4th Year', 5, 'Final project', 'TM401'),
(88, 'PSY101', 'Introduction to Psychology', 3, '1st', '1st Year', 6, 'Basic psychology concepts', NULL),
(89, 'PSY102', 'General Psychology', 3, '2nd', '1st Year', 6, 'Psychology fundamentals', 'PSY101'),
(90, 'PSY201', 'Developmental Psychology', 3, '1st', '2nd Year', 6, 'Human development stages', 'PSY102'),
(91, 'PSY202', 'Social Psychology', 3, '2nd', '2nd Year', 6, 'Social behavior and interaction', 'PSY201'),
(92, 'PSY301', 'Abnormal Psychology', 3, '1st', '3rd Year', 6, 'Psychological disorders', 'PSY202'),
(93, 'PSY302', 'Clinical Psychology', 3, '2nd', '3rd Year', 6, 'Clinical assessment and treatment', 'PSY301'),
(94, 'PSY401', 'Psychology Internship', 6, '1st', '4th Year', 6, 'Clinical training', 'PSY302'),
(95, 'PSY402', 'Psychology Thesis', 3, '2nd', '4th Year', 6, 'Research project', 'PSY401'),
(96, 'CRIM101', 'Introduction to Criminology', 3, '1st', '1st Year', 7, 'Criminology fundamentals', NULL),
(97, 'CRIM102', 'Criminal Law', 3, '2nd', '1st Year', 7, 'Basic criminal law principles', 'CRIM101'),
(98, 'CRIM201', 'Criminal Investigation', 3, '1st', '2nd Year', 7, 'Investigation techniques', 'CRIM102'),
(99, 'CRIM202', 'Forensic Science', 3, '2nd', '2nd Year', 7, 'Forensic analysis methods', 'CRIM201'),
(100, 'CRIM301', 'Criminal Psychology', 3, '1st', '3rd Year', 7, 'Psychology in criminal behavior', 'CRIM202'),
(101, 'CRIM302', 'Correctional Administration', 3, '2nd', '3rd Year', 7, 'Prison and correctional systems', 'CRIM301'),
(102, 'CRIM401', 'Criminology Internship', 6, '1st', '4th Year', 7, 'Field training', 'CRIM302'),
(103, 'CRIM402', 'Criminology Thesis', 3, '2nd', '4th Year', 7, 'Research project', 'CRIM401');

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
(1, 'admin', '$2y$10$u8sgmmsDifgTQu6NgROQCuzAfkUcGS1aJGrvhrxDyv.X1/yXOPdly', 'Admin', 'admin', '2025-08-03 14:15:01'),
(2, 'admission', '$2y$10$RaYXXsUt82FEJBGQvs2uXetcElrA.PCoy.sj6phCbjYNNJ60rX4oi', 'Admission Officer', 'admission', '2025-08-03 14:15:30'),
(3, 'registration', '$2y$10$KZ/v3o5MyHdABOu1qy/CyeAp1RugQOoJUNRno2eYBY0iAI4VgydLG', 'Registration Officer', 'registration', '2025-08-03 14:16:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `enrolled_subjects`
--
ALTER TABLE `enrolled_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `queue_system`
--
ALTER TABLE `queue_system`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `queue_number` (`queue_number`),
  ADD KEY `student_id` (`student_id`);

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
-- Indexes for table `student_grades`
--
ALTER TABLE `student_grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_code` (`subject_code`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subject_code` (`subject_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `queue_system`
--
ALTER TABLE `queue_system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `student_grades`
--
ALTER TABLE `student_grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
