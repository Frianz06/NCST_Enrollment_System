-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2025 at 04:20 PM
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
(1, 1, 'BSIT-1A', '1st Year', '1st', 'M', 1, 40, 'college'),
(2, 1, 'BSIT-1B', '1st Year', '1st', 'A', 1, 40, 'college'),
(3, 1, 'BSIT-1C', '1st Year', '1st', 'E', 1, 40, 'college'),
(4, 1, 'BSIT-2A', '2nd Year', '1st', 'M', 2, 40, 'college'),
(5, 1, 'BSIT-2B', '2nd Year', '1st', 'A', 2, 40, 'college'),
(6, 1, 'BSIT-2C', '2nd Year', '1st', 'E', 2, 40, 'college'),
(7, 1, 'BSIT-3A', '3rd Year', '1st', 'M', 3, 40, 'college'),
(8, 1, 'BSIT-3B', '3rd Year', '1st', 'A', 3, 40, 'college'),
(9, 1, 'BSIT-3C', '3rd Year', '1st', 'E', 3, 40, 'college'),
(10, 1, 'BSIT-4A', '4th Year', '1st', 'M', 4, 40, 'college'),
(11, 1, 'BSIT-4B', '4th Year', '1st', 'A', 4, 40, 'college'),
(12, 1, 'BSIT-4C', '4th Year', '1st', 'E', 4, 40, 'college'),
(13, 2, 'BSCS-1A', '1st Year', '1st', 'M', 1, 40, 'college'),
(14, 2, 'BSCS-1B', '1st Year', '1st', 'A', 1, 40, 'college'),
(15, 2, 'BSCS-1C', '1st Year', '1st', 'E', 1, 40, 'college'),
(16, 2, 'BSCS-2A', '2nd Year', '1st', 'M', 2, 40, 'college'),
(17, 2, 'BSCS-2B', '2nd Year', '1st', 'A', 2, 40, 'college'),
(18, 2, 'BSCS-2C', '2nd Year', '1st', 'E', 2, 40, 'college'),
(19, 2, 'BSCS-3A', '3rd Year', '1st', 'M', 3, 40, 'college'),
(20, 2, 'BSCS-3B', '3rd Year', '1st', 'A', 3, 40, 'college'),
(21, 2, 'BSCS-3C', '3rd Year', '1st', 'E', 3, 40, 'college'),
(22, 2, 'BSCS-4A', '4th Year', '1st', 'M', 4, 40, 'college'),
(23, 2, 'BSCS-4B', '4th Year', '1st', 'A', 4, 40, 'college'),
(24, 2, 'BSCS-4C', '4th Year', '1st', 'E', 4, 40, 'college'),
(25, 3, 'BSBA-1A', '1st Year', '1st', 'M', 1, 40, 'college'),
(26, 3, 'BSBA-1B', '1st Year', '1st', 'A', 1, 40, 'college'),
(27, 3, 'BSBA-1C', '1st Year', '1st', 'E', 1, 40, 'college'),
(28, 3, 'BSBA-2A', '2nd Year', '1st', 'M', 2, 40, 'college'),
(29, 3, 'BSBA-2B', '2nd Year', '1st', 'A', 2, 40, 'college'),
(30, 3, 'BSBA-2C', '2nd Year', '1st', 'E', 2, 40, 'college'),
(31, 3, 'BSBA-3A', '3rd Year', '1st', 'M', 3, 40, 'college'),
(32, 3, 'BSBA-3B', '3rd Year', '1st', 'A', 3, 40, 'college'),
(33, 3, 'BSBA-3C', '3rd Year', '1st', 'E', 3, 40, 'college'),
(34, 3, 'BSBA-4A', '4th Year', '1st', 'M', 4, 40, 'college'),
(35, 3, 'BSBA-4B', '4th Year', '1st', 'A', 4, 40, 'college'),
(36, 3, 'BSBA-4C', '4th Year', '1st', 'E', 4, 40, 'college'),
(37, 4, 'BSHM-1A', '1st Year', '1st', 'M', 1, 40, 'college'),
(38, 4, 'BSHM-1B', '1st Year', '1st', 'A', 1, 40, 'college'),
(39, 4, 'BSHM-1C', '1st Year', '1st', 'E', 1, 40, 'college'),
(40, 4, 'BSHM-2A', '2nd Year', '1st', 'M', 2, 40, 'college'),
(41, 4, 'BSHM-2B', '2nd Year', '1st', 'A', 2, 40, 'college'),
(42, 4, 'BSHM-2C', '2nd Year', '1st', 'E', 2, 40, 'college'),
(43, 4, 'BSHM-3A', '3rd Year', '1st', 'M', 3, 40, 'college'),
(44, 4, 'BSHM-3B', '3rd Year', '1st', 'A', 3, 40, 'college'),
(45, 4, 'BSHM-3C', '3rd Year', '1st', 'E', 3, 40, 'college'),
(46, 4, 'BSHM-4A', '4th Year', '1st', 'M', 4, 40, 'college'),
(47, 4, 'BSHM-4B', '4th Year', '1st', 'A', 4, 40, 'college'),
(48, 4, 'BSHM-4C', '4th Year', '1st', 'E', 4, 40, 'college'),
(49, 5, 'BSTM-1A', '1st Year', '1st', 'M', 1, 40, 'college'),
(50, 5, 'BSTM-1B', '1st Year', '1st', 'A', 1, 40, 'college'),
(51, 5, 'BSTM-1C', '1st Year', '1st', 'E', 1, 40, 'college'),
(52, 5, 'BSTM-2A', '2nd Year', '1st', 'M', 2, 40, 'college'),
(53, 5, 'BSTM-2B', '2nd Year', '1st', 'A', 2, 40, 'college'),
(54, 5, 'BSTM-2C', '2nd Year', '1st', 'E', 2, 40, 'college'),
(55, 5, 'BSTM-3A', '3rd Year', '1st', 'M', 3, 40, 'college'),
(56, 5, 'BSTM-3B', '3rd Year', '1st', 'A', 3, 40, 'college'),
(57, 5, 'BSTM-3C', '3rd Year', '1st', 'E', 3, 40, 'college'),
(58, 5, 'BSTM-4A', '4th Year', '1st', 'M', 4, 40, 'college'),
(59, 5, 'BSTM-4B', '4th Year', '1st', 'A', 4, 40, 'college'),
(60, 5, 'BSTM-4C', '4th Year', '1st', 'E', 4, 40, 'college'),
(61, 6, 'BSPSYCH-1A', '1st Year', '1st', 'M', 1, 40, 'college'),
(62, 6, 'BSPSYCH-1B', '1st Year', '1st', 'A', 1, 40, 'college'),
(63, 6, 'BSPSYCH-1C', '1st Year', '1st', 'E', 1, 40, 'college'),
(64, 6, 'BSPSYCH-2A', '2nd Year', '1st', 'M', 2, 40, 'college'),
(65, 6, 'BSPSYCH-2B', '2nd Year', '1st', 'A', 2, 40, 'college'),
(66, 6, 'BSPSYCH-2C', '2nd Year', '1st', 'E', 2, 40, 'college'),
(67, 6, 'BSPSYCH-3A', '3rd Year', '1st', 'M', 3, 40, 'college'),
(68, 6, 'BSPSYCH-3B', '3rd Year', '1st', 'A', 3, 40, 'college'),
(69, 6, 'BSPSYCH-3C', '3rd Year', '1st', 'E', 3, 40, 'college'),
(70, 6, 'BSPSYCH-4A', '4th Year', '1st', 'M', 4, 40, 'college'),
(71, 6, 'BSPSYCH-4B', '4th Year', '1st', 'A', 4, 40, 'college'),
(72, 6, 'BSPSYCH-4C', '4th Year', '1st', 'E', 4, 40, 'college'),
(73, 7, 'BSCRIM-1A', '1st Year', '1st', 'M', 1, 40, 'college'),
(74, 7, 'BSCRIM-1B', '1st Year', '1st', 'A', 1, 40, 'college'),
(75, 7, 'BSCRIM-1C', '1st Year', '1st', 'E', 1, 40, 'college'),
(76, 7, 'BSCRIM-2A', '2nd Year', '1st', 'M', 2, 40, 'college'),
(77, 7, 'BSCRIM-2B', '2nd Year', '1st', 'A', 2, 40, 'college'),
(78, 7, 'BSCRIM-2C', '2nd Year', '1st', 'E', 2, 40, 'college'),
(79, 7, 'BSCRIM-3A', '3rd Year', '1st', 'M', 3, 40, 'college'),
(80, 7, 'BSCRIM-3B', '3rd Year', '1st', 'A', 3, 40, 'college'),
(81, 7, 'BSCRIM-3C', '3rd Year', '1st', 'E', 3, 40, 'college'),
(82, 7, 'BSCRIM-4A', '4th Year', '1st', 'M', 4, 40, 'college'),
(83, 7, 'BSCRIM-4B', '4th Year', '1st', 'A', 4, 40, 'college'),
(84, 7, 'BSCRIM-4C', '4th Year', '1st', 'E', 4, 40, 'college');

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
(1, 'IT101', 'Introduction to Computing', 3, '1st', '1st Year', 1, 'Basic computer concepts and applications', NULL),
(2, 'MATH101', 'College Algebra', 3, '1st', '1st Year', 1, 'Fundamental algebraic concepts', NULL),
(3, 'ENG101', 'English Communication', 3, '1st', '1st Year', 1, 'Basic English communication skills', NULL),
(4, 'PE101', 'Physical Education 1', 2, '1st', '1st Year', 1, 'Physical fitness and wellness', NULL),
(5, 'NSTP101', 'National Service Training Program 1', 3, '1st', '1st Year', 1, 'Civic welfare training service', NULL),
(6, 'IT102', 'Computer Programming 1', 3, '2nd', '1st Year', 1, 'Introduction to programming concepts', 'IT101'),
(7, 'MATH102', 'Discrete Mathematics', 3, '2nd', '1st Year', 1, 'Mathematical structures for computer science', 'MATH101'),
(8, 'ENG102', 'Technical Writing', 3, '2nd', '1st Year', 1, 'Technical communication skills', 'ENG101'),
(9, 'PE102', 'Physical Education 2', 2, '2nd', '1st Year', 1, 'Advanced physical fitness', 'PE101'),
(10, 'NSTP102', 'National Service Training Program 2', 3, '2nd', '1st Year', 1, 'Advanced civic training', 'NSTP101'),
(11, 'CS101', 'Introduction to Computer Science', 3, '1st', '1st Year', 2, 'Fundamentals of computer science', NULL),
(12, 'MATH201', 'Calculus 1', 3, '1st', '1st Year', 2, 'Differential calculus', 'MATH101'),
(13, 'ENG101', 'English Communication', 3, '1st', '1st Year', 2, 'Basic English communication skills', NULL),
(14, 'PE101', 'Physical Education 1', 2, '1st', '1st Year', 2, 'Physical fitness and wellness', NULL),
(15, 'NSTP101', 'National Service Training Program 1', 3, '1st', '1st Year', 2, 'Civic welfare training service', NULL),
(16, 'CS102', 'Computer Programming 1', 3, '2nd', '1st Year', 2, 'Programming fundamentals', 'CS101'),
(17, 'MATH202', 'Calculus 2', 3, '2nd', '1st Year', 2, 'Integral calculus', 'MATH201'),
(18, 'ENG102', 'Technical Writing', 3, '2nd', '1st Year', 2, 'Technical communication skills', 'ENG101'),
(19, 'PE102', 'Physical Education 2', 2, '2nd', '1st Year', 2, 'Advanced physical fitness', 'PE101'),
(20, 'NSTP102', 'National Service Training Program 2', 3, '2nd', '1st Year', 2, 'Advanced civic training', 'NSTP101');

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
  `year_level` varchar(20) DEFAULT '1st Year',
  `tracking_number` varchar(32) DEFAULT NULL,
  `application_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive','graduated') NOT NULL DEFAULT 'active'
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
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subject_code` (`subject_code`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD UNIQUE KEY `tracking_number` (`tracking_number`),
  ADD KEY `program_id` (`program_id`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `student_applications`
--
ALTER TABLE `student_applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tracking_number` (`tracking_number`);

--
-- Indexes for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_enrollment` (`student_id`,`subject_id`,`schedule_id`),
  ADD KEY `student_id` (`student_id`);

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
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_applications`
--
ALTER TABLE `student_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_subjects`
--
ALTER TABLE `student_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`),
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`),
  ADD CONSTRAINT `students_ibfk_3` FOREIGN KEY (`application_id`) REFERENCES `student_applications` (`id`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
