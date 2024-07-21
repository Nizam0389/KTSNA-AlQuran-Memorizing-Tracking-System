-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 21, 2024 at 11:45 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ktsna_quran`
--

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `class_id` varchar(5) NOT NULL,
  `class_name` varchar(30) DEFAULT NULL,
  `year` int DEFAULT NULL,
  `class_type` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`class_id`, `class_name`, `year`, `class_type`) VALUES
('C001', 'Ibnu Sina', 1, 'Regular'),
('C002', 'Ibnu Sina', 2, 'Regular'),
('C003', 'Ibnu Sina', 3, 'Regular'),
('C004', 'Ibnu Sina', 4, 'Regular'),
('C005', 'Ibnu Sina', 5, 'Regular'),
('C006', 'Ibnu Khaldun', 1, 'Regular'),
('C007', 'Ibnu Khaldun', 2, 'Regular'),
('C008', 'Ibnu Khaldun', 3, 'Regular'),
('C009', 'Ibnu Khaldun', 4, 'Regular'),
('C010', 'Ibnu Khaldun', 5, 'Regular'),
('C011', 'Ibnu Batutah', 1, 'Regular'),
('C012', 'Ibnu Batutah', 2, 'Regular'),
('C013', 'Ibnu Batutah', 3, 'Regular'),
('C014', 'Ibnu Batutah', 4, 'Regular'),
('C015', 'Ibnu Batutah', 5, 'Regular'),
('C016', 'Ibnu Rusydi', 1, 'Regular'),
('C017', 'Ibnu Rusydi', 2, 'Regular'),
('C018', 'Ibnu Rusydi', 3, 'Regular'),
('C019', 'Ibnu Rusydi', 4, 'Regular'),
('C020', 'Ibnu Rusydi', 5, 'Regular');

-- --------------------------------------------------------

--
-- Table structure for table `memorizing_history`
--

CREATE TABLE `memorizing_history` (
  `memoHistory_id` int NOT NULL,
  `memo_id` varchar(5) DEFAULT NULL,
  `page` int DEFAULT NULL,
  `juzu` int DEFAULT NULL,
  `surah` int DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `session` char(1) DEFAULT NULL,
  `status` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `memorizing_history`
--

INSERT INTO `memorizing_history` (`memoHistory_id`, `memo_id`, `page`, `juzu`, `surah`, `date`, `time`, `session`, `status`) VALUES
(1, 'M001', 75, 4, 5, '2023-07-01', '10:00:00', 'd', 'f'),
(2, 'M002', 90, 5, 6, '2023-07-02', '20:00:00', 'n', 'f'),
(3, 'M003', 100, 5, 6, '2023-07-03', '10:00:00', 'd', 'f'),
(4, 'M004', 110, 6, 7, '2023-07-04', '20:00:00', 'n', 'p'),
(5, 'M005', 150, 8, 10, '2023-07-05', '10:00:00', 'd', 'p'),
(6, 'M006', 220, 13, 15, '2023-07-06', '10:00:00', 'd', 'f'),
(7, 'M007', 200, 11, 13, '2023-07-07', '20:00:00', 'n', 'f'),
(8, 'M008', 260, 15, 17, '2023-07-08', '10:00:00', 'd', 'p'),
(9, 'M009', 242, 14, 16, '2023-07-09', '20:00:00', 'n', 'p'),
(10, 'M010', 270, 16, 18, '2023-07-10', '10:00:00', 'd', 'p'),
(11, 'M011', 320, 18, 20, '2023-07-11', '10:00:00', 'd', 'f'),
(12, 'M012', 350, 20, 22, '2023-07-12', '20:00:00', 'n', 'f'),
(13, 'M013', 400, 23, 25, '2023-07-13', '10:00:00', 'd', 'p'),
(14, 'M014', 370, 21, 23, '2023-07-14', '20:00:00', 'n', 'f'),
(15, 'M015', 390, 22, 24, '2023-07-15', '10:00:00', 'd', 'p'),
(16, 'M016', 450, 26, 28, '2023-07-16', '10:00:00', 'd', 'f'),
(17, 'M017', 480, 27, 29, '2023-07-17', '20:00:00', 'n', 'f'),
(18, 'M018', 500, 29, 30, '2023-07-18', '10:00:00', 'd', 'p'),
(19, 'M019', 520, 30, 41, '2023-07-19', '20:00:00', 'n', 'p'),
(20, 'M020', 502, 29, 30, '2023-07-20', '10:00:00', 'd', 'p'),
(21, 'M021', 550, 30, 39, '2023-07-21', '10:00:00', 'd', 'f'),
(22, 'M022', 570, 30, 40, '2023-07-22', '20:00:00', 'n', 'f'),
(23, 'M023', 600, 30, 41, '2023-07-23', '10:00:00', 'd', 'f'),
(24, 'M024', 604, 30, 41, '2023-07-24', '20:00:00', 'n', 'p'),
(25, 'M025', 604, 30, 41, '2023-07-25', '10:00:00', 'd', 'p'),
(26, 'M026', 123, 6, 9, '2023-07-26', '10:00:00', 'd', 'f'),
(27, 'M027', 150, 8, 10, '2023-07-27', '20:00:00', 'n', 'p'),
(28, 'M028', 180, 10, 12, '2023-07-28', '10:00:00', 'd', 'p'),
(29, 'M029', 200, 11, 13, '2023-07-29', '20:00:00', 'n', 'p'),
(30, 'M030', 220, 13, 15, '2023-07-30', '10:00:00', 'd', 'p'),
(31, 'M031', 50, 3, 4, '2023-07-31', '10:00:00', 'd', 'f'),
(32, 'M032', 90, 5, 6, '2023-08-01', '20:00:00', 'n', 'f'),
(33, 'M033', 130, 6, 8, '2023-08-02', '10:00:00', 'd', 'p'),
(34, 'M034', 170, 9, 11, '2023-08-03', '20:00:00', 'n', 'p'),
(35, 'M035', 210, 12, 14, '2023-08-04', '10:00:00', 'd', 'p'),
(36, 'M036', 100, 5, 6, '2023-08-05', '10:00:00', 'd', 'f'),
(37, 'M037', 150, 8, 10, '2023-08-06', '20:00:00', 'n', 'p'),
(38, 'M038', 200, 11, 13, '2023-08-07', '10:00:00', 'd', 'p'),
(39, 'M039', 250, 14, 16, '2023-08-08', '20:00:00', 'n', 'p'),
(40, 'M040', 300, 17, 19, '2023-08-09', '10:00:00', 'd', 'p'),
(41, 'M041', 320, 18, 20, '2023-08-10', '10:00:00', 'd', 'p'),
(42, 'M042', 350, 20, 22, '2023-08-11', '20:00:00', 'n', 'f'),
(43, 'M043', 400, 23, 25, '2023-08-12', '10:00:00', 'd', 'p'),
(44, 'M044', 370, 21, 23, '2023-08-13', '20:00:00', 'n', 'f'),
(45, 'M045', 390, 22, 24, '2023-08-14', '10:00:00', 'd', 'p'),
(46, 'M046', 450, 26, 28, '2023-08-15', '10:00:00', 'd', 'f'),
(47, 'M047', 480, 27, 29, '2023-08-16', '20:00:00', 'n', 'f'),
(48, 'M048', 500, 29, 30, '2023-08-17', '10:00:00', 'd', 'p'),
(49, 'M049', 520, 30, 41, '2023-08-18', '20:00:00', 'n', 'p'),
(50, 'M050', 502, 29, 30, '2023-08-19', '10:00:00', 'd', 'p'),
(51, 'M051', 550, 30, 39, '2023-08-20', '10:00:00', 'd', 'f'),
(52, 'M052', 570, 30, 40, '2023-08-21', '20:00:00', 'n', 'f'),
(53, 'M053', 600, 30, 41, '2023-08-22', '10:00:00', 'd', 'f'),
(54, 'M054', 604, 30, 41, '2023-08-23', '20:00:00', 'n', 'p'),
(55, 'M055', 604, 30, 41, '2023-08-24', '10:00:00', 'd', 'p'),
(56, 'M056', 123, 6, 9, '2023-08-25', '10:00:00', 'd', 'f'),
(57, 'M057', 150, 8, 10, '2023-08-26', '20:00:00', 'n', 'p'),
(58, 'M058', 180, 10, 12, '2023-08-27', '10:00:00', 'd', 'p'),
(59, 'M059', 200, 11, 13, '2023-08-28', '20:00:00', 'n', 'p'),
(60, 'M060', 220, 13, 15, '2023-08-29', '10:00:00', 'd', 'p'),
(61, 'M061', 50, 3, 4, '2023-08-30', '10:00:00', 'd', 'f'),
(62, 'M062', 90, 5, 6, '2023-08-31', '20:00:00', 'n', 'f'),
(63, 'M063', 130, 6, 8, '2023-09-01', '10:00:00', 'd', 'p'),
(64, 'M064', 170, 9, 11, '2023-09-02', '20:00:00', 'n', 'p'),
(65, 'M065', 210, 12, 14, '2023-09-03', '10:00:00', 'd', 'p'),
(66, 'M066', 100, 5, 6, '2023-09-04', '10:00:00', 'd', 'f'),
(67, 'M067', 150, 8, 10, '2023-09-05', '20:00:00', 'n', 'p'),
(68, 'M068', 200, 11, 13, '2023-09-06', '10:00:00', 'd', 'p'),
(69, 'M069', 250, 14, 16, '2023-09-07', '20:00:00', 'n', 'p'),
(70, 'M070', 300, 17, 19, '2023-09-08', '10:00:00', 'd', 'p'),
(71, 'M071', 320, 18, 20, '2023-09-09', '10:00:00', 'd', 'p'),
(72, 'M072', 350, 20, 22, '2023-09-10', '20:00:00', 'n', 'f'),
(73, 'M073', 400, 23, 25, '2023-09-11', '10:00:00', 'd', 'p'),
(74, 'M074', 370, 21, 23, '2023-09-12', '20:00:00', 'n', 'f'),
(75, 'M075', 390, 22, 24, '2023-09-13', '10:00:00', 'd', 'p'),
(76, 'M076', 450, 26, 28, '2023-09-14', '10:00:00', 'd', 'f'),
(77, 'M077', 480, 27, 29, '2023-09-15', '20:00:00', 'n', 'f'),
(78, 'M078', 500, 29, 30, '2023-09-16', '10:00:00', 'd', 'p'),
(79, 'M079', 520, 30, 41, '2023-09-17', '20:00:00', 'n', 'p'),
(80, 'M080', 502, 29, 30, '2023-09-18', '10:00:00', 'd', 'p'),
(81, 'M081', 550, 30, 39, '2023-09-19', '10:00:00', 'd', 'f'),
(82, 'M082', 570, 30, 40, '2023-09-20', '20:00:00', 'n', 'f'),
(83, 'M083', 600, 30, 41, '2023-09-21', '10:00:00', 'd', 'f'),
(84, 'M084', 604, 30, 41, '2023-09-22', '20:00:00', 'n', 'p'),
(85, 'M085', 604, 30, 41, '2023-09-23', '10:00:00', 'd', 'p'),
(86, 'M086', 123, 6, 9, '2023-09-24', '10:00:00', 'd', 'f'),
(87, 'M087', 150, 8, 10, '2023-09-25', '20:00:00', 'n', 'p'),
(88, 'M088', 180, 10, 12, '2023-09-26', '10:00:00', 'd', 'p'),
(89, 'M089', 200, 11, 13, '2023-09-27', '20:00:00', 'n', 'p'),
(90, 'M090', 220, 13, 15, '2023-09-28', '10:00:00', 'd', 'p'),
(91, 'M091', 50, 3, 4, '2023-09-29', '10:00:00', 'd', 'f'),
(92, 'M092', 90, 5, 6, '2023-09-30', '20:00:00', 'n', 'f'),
(93, 'M093', 130, 6, 8, '2023-10-01', '10:00:00', 'd', 'p'),
(94, 'M094', 170, 9, 11, '2023-10-02', '20:00:00', 'n', 'p'),
(95, 'M095', 210, 12, 14, '2023-10-03', '10:00:00', 'd', 'p'),
(96, 'M096', 100, 5, 6, '2023-10-04', '10:00:00', 'd', 'f'),
(97, 'M097', 150, 8, 10, '2023-10-05', '20:00:00', 'n', 'p'),
(98, 'M098', 200, 11, 13, '2023-10-06', '10:00:00', 'd', 'p'),
(99, 'M099', 250, 14, 16, '2023-10-07', '20:00:00', 'n', 'p'),
(100, 'M100', 300, 17, 19, '2023-10-08', '10:00:00', 'd', 'p'),
(101, 'M001', 100, 5, 4, '2024-07-19', '16:30:22', 'd', 'p'),
(102, 'M001', 150, 8, 6, '2024-07-19', '16:49:44', 'd', 'p'),
(103, 'M001', 200, 10, 9, '2024-07-19', '17:03:28', 'd', 'p'),
(104, 'M001', 210, 11, 10, '2024-07-19', '17:03:53', 'd', 'p'),
(105, 'M001', 250, 13, 13, '2024-07-19', '17:05:04', 'd', 'p'),
(106, 'M001', 300, 15, 18, '2024-07-19', '23:39:03', 'n', 'p'),
(107, 'M001', 350, 18, 24, '2024-07-20', '07:47:32', 'd', 'f'),
(108, 'M002', 120, 6, 5, '2024-07-20', '13:15:31', 'd', 'p'),
(109, 'M002', 200, 10, 9, '2024-07-22', '07:33:05', 'd', 'p'),
(110, 'M003', 111, 6, 5, '2024-07-22', '07:35:47', 'd', 'f'),
(111, 'M003', 129, 7, 6, '2024-07-22', '07:36:02', 'd', 'f'),
(112, 'M003', 170, 9, 7, '2024-07-22', '07:36:09', 'd', 'p'),
(113, 'M004', 120, 6, 5, '2024-07-22', '07:36:36', 'd', 'p'),
(114, 'M004', 130, 7, 6, '2024-07-22', '07:36:43', 'd', 'p');

-- --------------------------------------------------------

--
-- Table structure for table `memorizing_record`
--

CREATE TABLE `memorizing_record` (
  `memo_id` varchar(5) NOT NULL,
  `student_id` varchar(5) DEFAULT NULL,
  `staff_id` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `memorizing_record`
--

INSERT INTO `memorizing_record` (`memo_id`, `student_id`, `staff_id`) VALUES
('M001', 'ST001', 'S002'),
('M002', 'ST002', 'S002'),
('M003', 'ST003', 'S002'),
('M004', 'ST004', 'S002'),
('M005', 'ST005', 'S002'),
('M006', 'ST006', 'S003'),
('M007', 'ST007', 'S003'),
('M008', 'ST008', 'S003'),
('M009', 'ST009', 'S003'),
('M010', 'ST010', 'S003'),
('M011', 'ST011', 'S004'),
('M012', 'ST012', 'S004'),
('M013', 'ST013', 'S004'),
('M014', 'ST014', 'S004'),
('M015', 'ST015', 'S004'),
('M016', 'ST016', 'S005'),
('M017', 'ST017', 'S005'),
('M018', 'ST018', 'S005'),
('M019', 'ST019', 'S005'),
('M020', 'ST020', 'S005'),
('M021', 'ST021', 'S006'),
('M022', 'ST022', 'S006'),
('M023', 'ST023', 'S006'),
('M024', 'ST024', 'S006'),
('M025', 'ST025', 'S006'),
('M026', 'ST026', 'S007'),
('M027', 'ST027', 'S007'),
('M028', 'ST028', 'S007'),
('M029', 'ST029', 'S007'),
('M030', 'ST030', 'S007'),
('M031', 'ST031', 'S008'),
('M032', 'ST032', 'S008'),
('M033', 'ST033', 'S008'),
('M034', 'ST034', 'S008'),
('M035', 'ST035', 'S008'),
('M036', 'ST036', 'S009'),
('M037', 'ST037', 'S009'),
('M038', 'ST038', 'S009'),
('M039', 'ST039', 'S009'),
('M040', 'ST040', 'S009'),
('M041', 'ST041', 'S010'),
('M042', 'ST042', 'S010'),
('M043', 'ST043', 'S010'),
('M044', 'ST044', 'S010'),
('M045', 'ST045', 'S010'),
('M046', 'ST046', 'S011'),
('M047', 'ST047', 'S011'),
('M048', 'ST048', 'S011'),
('M049', 'ST049', 'S011'),
('M050', 'ST050', 'S011'),
('M051', 'ST051', 'S012'),
('M052', 'ST052', 'S012'),
('M053', 'ST053', 'S012'),
('M054', 'ST054', 'S012'),
('M055', 'ST055', 'S012'),
('M056', 'ST056', 'S013'),
('M057', 'ST057', 'S013'),
('M058', 'ST058', 'S013'),
('M059', 'ST059', 'S013'),
('M060', 'ST060', 'S013'),
('M061', 'ST061', 'S014'),
('M062', 'ST062', 'S014'),
('M063', 'ST063', 'S014'),
('M064', 'ST064', 'S014'),
('M065', 'ST065', 'S014'),
('M066', 'ST066', 'S015'),
('M067', 'ST067', 'S015'),
('M068', 'ST068', 'S015'),
('M069', 'ST069', 'S015'),
('M070', 'ST070', 'S015'),
('M071', 'ST071', 'S016'),
('M072', 'ST072', 'S016'),
('M073', 'ST073', 'S016'),
('M074', 'ST074', 'S016'),
('M075', 'ST075', 'S016'),
('M076', 'ST076', 'S017'),
('M077', 'ST077', 'S017'),
('M078', 'ST078', 'S017'),
('M079', 'ST079', 'S017'),
('M080', 'ST080', 'S017'),
('M081', 'ST081', 'S018'),
('M082', 'ST082', 'S018'),
('M083', 'ST083', 'S018'),
('M084', 'ST084', 'S018'),
('M085', 'ST085', 'S018'),
('M086', 'ST086', 'S019'),
('M087', 'ST087', 'S019'),
('M088', 'ST088', 'S019'),
('M089', 'ST089', 'S019'),
('M090', 'ST090', 'S019'),
('M091', 'ST091', 'S020'),
('M092', 'ST092', 'S020'),
('M093', 'ST093', 'S020'),
('M094', 'ST094', 'S020'),
('M095', 'ST095', 'S020'),
('M096', 'ST096', 'S021'),
('M097', 'ST097', 'S021'),
('M098', 'ST098', 'S021'),
('M099', 'ST099', 'S021'),
('M100', 'ST100', 'S021');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` varchar(5) NOT NULL,
  `staff_name` varchar(50) DEFAULT NULL,
  `staff_username` varchar(20) DEFAULT NULL,
  `staff_pass` varchar(32) DEFAULT NULL,
  `staff_type` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `staff_name`, `staff_username`, `staff_pass`, `staff_type`) VALUES
('S001', 'Ahmad', 'ahmad', '200820e3227815ed1756a6b531e7e0d2', 'mudir'),
('S002', 'Bakar', 'bakar', '200820e3227815ed1756a6b531e7e0d2', 'ustaz'),
('S003', 'Zakaria', 'zakaria', '200820e3227815ed1756a6b531e7e0d2', 'ustaz'),
('S004', 'Daud', 'daud', '200820e3227815ed1756a6b531e7e0d2', 'ustaz'),
('S005', 'Shahrul', 'shahrul', '200820e3227815ed1756a6b531e7e0d2', 'ustaz'),
('S006', 'Fadil', 'fadil', '200820e3227815ed1756a6b531e7e0d2', 'ustaz'),
('S007', 'Ghazi', 'ghazi', '200820e3227815ed1756a6b531e7e0d2', 'ustaz'),
('S008', 'Hadi', 'hadi', '200820e3227815ed1756a6b531e7e0d2', 'ustaz'),
('S009', 'Imran', 'imran', '200820e3227815ed1756a6b531e7e0d2', 'ustaz'),
('S010', 'Jamil', 'jamil', '200820e3227815ed1756a6b531e7e0d2', 'ustaz'),
('S011', 'Kadir', 'kadir', '200820e3227815ed1756a6b531e7e0d2', 'ustaz'),
('S012', 'Latif', 'latif', '200820e3227815ed1756a6b531e7e0d2', 'ustaz'),
('S013', 'Musa', 'musa', '200820e3227815ed1756a6b531e7e0d2', 'ustaz'),
('S014', 'Nasir', 'nasir', '200820e3227815ed1756a6b531e7e0d2', 'ustaz'),
('S015', 'Osman', 'osman', '200820e3227815ed1756a6b531e7e0d2', 'ustaz'),
('S016', 'Zamri', 'zamri', '200820e3227815ed1756a6b531e7e0d2', 'ustaz'),
('S017', 'Qadir', 'qadir', '200820e3227815ed1756a6b531e7e0d2', 'ustaz'),
('S018', 'Rahim', 'rahim', '200820e3227815ed1756a6b531e7e0d2', 'ustaz'),
('S019', 'Syed', 'syed', '200820e3227815ed1756a6b531e7e0d2', 'ustaz'),
('S020', 'Tariq', 'tariq', '200820e3227815ed1756a6b531e7e0d2', 'ustaz'),
('S021', 'Anuar', 'anuar', '200820e3227815ed1756a6b531e7e0d2', 'ustaz');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` varchar(5) NOT NULL,
  `student_name` varchar(50) DEFAULT NULL,
  `student_username` varchar(20) DEFAULT NULL,
  `student_pass` varchar(32) DEFAULT NULL,
  `class_id` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `student_name`, `student_username`, `student_pass`, `class_id`) VALUES
('ST001', 'Ahmad Firdaus', 'ahmadfirdaus', '200820e3227815ed1756a6b531e7e0d2', 'C001'),
('ST002', 'Mohd Haziq', 'mohdhaziq', '200820e3227815ed1756a6b531e7e0d2', 'C001'),
('ST003', 'Hafiz Azman', 'hafizazman', '200820e3227815ed1756a6b531e7e0d2', 'C001'),
('ST004', 'Ali Rahman', 'alirahman', '200820e3227815ed1756a6b531e7e0d2', 'C001'),
('ST005', 'Ismail Hakim', 'ismailhakim', '200820e3227815ed1756a6b531e7e0d2', 'C001'),
('ST006', 'Salim Anuar', 'salimanuar', '200820e3227815ed1756a6b531e7e0d2', 'C002'),
('ST007', 'Zaid Nordin', 'zaidnordin', '200820e3227815ed1756a6b531e7e0d2', 'C002'),
('ST008', 'Farid Rashid', 'faridrashid', '200820e3227815ed1756a6b531e7e0d2', 'C002'),
('ST009', 'Malik Hassan', 'malikhassan', '200820e3227815ed1756a6b531e7e0d2', 'C002'),
('ST010', 'Rafi Bakar', 'rafibakar', '200820e3227815ed1756a6b531e7e0d2', 'C002'),
('ST011', 'Jamal Nasir', 'jamalnasir', '200820e3227815ed1756a6b531e7e0d2', 'C003'),
('ST012', 'Nabil Fikri', 'nabilfikri', '200820e3227815ed1756a6b531e7e0d2', 'C003'),
('ST013', 'Omar Shafie', 'omarshafie', '200820e3227815ed1756a6b531e7e0d2', 'C003'),
('ST014', 'Qasim Iqbal', 'qasimiqbal', '200820e3227815ed1756a6b531e7e0d2', 'C003'),
('ST015', 'Rashid Faisal', 'rashidfaisal', '200820e3227815ed1756a6b531e7e0d2', 'C003'),
('ST016', 'Sameer Rahman', 'sameerrahman', '200820e3227815ed1756a6b531e7e0d2', 'C004'),
('ST017', 'Talib Ali', 'talibali', '200820e3227815ed1756a6b531e7e0d2', 'C004'),
('ST018', 'Usman Syed', 'usmansyed', '200820e3227815ed1756a6b531e7e0d2', 'C004'),
('ST019', 'Vahid Karim', 'vahidkarim', '200820e3227815ed1756a6b531e7e0d2', 'C004'),
('ST020', 'Waleed Ahmad', 'waleedahmad', '200820e3227815ed1756a6b531e7e0d2', 'C004'),
('ST021', 'Yasir Arif', 'yasirarif', '200820e3227815ed1756a6b531e7e0d2', 'C005'),
('ST022', 'Zahir Zaman', 'zahirzaman', '200820e3227815ed1756a6b531e7e0d2', 'C005'),
('ST023', 'Adnan Hakim', 'adnanhakim', '200820e3227815ed1756a6b531e7e0d2', 'C005'),
('ST024', 'Bashir Faiz', 'bashirfaiz', '200820e3227815ed1756a6b531e7e0d2', 'C005'),
('ST025', 'Fahad Jamal', 'fahadjamal', '200820e3227815ed1756a6b531e7e0d2', 'C005'),
('ST026', 'Ghassan Tariq', 'ghassantariq', '200820e3227815ed1756a6b531e7e0d2', 'C006'),
('ST027', 'Hamza Razi', 'hamzarazi', '200820e3227815ed1756a6b531e7e0d2', 'C006'),
('ST028', 'Ibrahim Yusof', 'ibrahimyusof', '200820e3227815ed1756a6b531e7e0d2', 'C006'),
('ST029', 'Jabir Aiman', 'jabiraiman', '200820e3227815ed1756a6b531e7e0d2', 'C006'),
('ST030', 'Karim Aiman', 'karimaiman', '200820e3227815ed1756a6b531e7e0d2', 'C006'),
('ST031', 'Latif Malik', 'latifmalik', '200820e3227815ed1756a6b531e7e0d2', 'C007'),
('ST032', 'Mansur Idris', 'mansuridris', '200820e3227815ed1756a6b531e7e0d2', 'C007'),
('ST033', 'Nawfal Omar', 'nawfalomar', '200820e3227815ed1756a6b531e7e0d2', 'C007'),
('ST034', 'Othman Rafi', 'othmanrafi', '200820e3227815ed1756a6b531e7e0d2', 'C007'),
('ST035', 'Qamar Ahmad', 'qamarahmad', '200820e3227815ed1756a6b531e7e0d2', 'C007'),
('ST036', 'Razi Imran', 'raziimran', '200820e3227815ed1756a6b531e7e0d2', 'C008'),
('ST037', 'Sadiq Latif', 'sadiqlatif', '200820e3227815ed1756a6b531e7e0d2', 'C008'),
('ST038', 'Talal Musa', 'talalmusa', '200820e3227815ed1756a6b531e7e0d2', 'C008'),
('ST039', 'Umar Syed', 'umarsyed', '200820e3227815ed1756a6b531e7e0d2', 'C008'),
('ST040', 'Wahid Anwar', 'wahidanwar', '200820e3227815ed1756a6b531e7e0d2', 'C008'),
('ST041', 'Yusuf Jamal', 'yusufjamal', '200820e3227815ed1756a6b531e7e0d2', 'C009'),
('ST042', 'Zubair Rahim', 'zubairrahim', '200820e3227815ed1756a6b531e7e0d2', 'C009'),
('ST043', 'Amir Fikri', 'amirfikri', '200820e3227815ed1756a6b531e7e0d2', 'C009'),
('ST044', 'Basim Khalid', 'basimkhalid', '200820e3227815ed1756a6b531e7e0d2', 'C009'),
('ST045', 'Daoud Aziz', 'daoudaziz', '200820e3227815ed1756a6b531e7e0d2', 'C009'),
('ST046', 'Faisal Bakar', 'faisalbakar', '200820e3227815ed1756a6b531e7e0d2', 'C010'),
('ST047', 'Ghani Nasir', 'ghaninasir', '200820e3227815ed1756a6b531e7e0d2', 'C010'),
('ST048', 'Hassan Adib', 'hassanadib', '200820e3227815ed1756a6b531e7e0d2', 'C010'),
('ST049', 'Idris Karim', 'idriskarim', '200820e3227815ed1756a6b531e7e0d2', 'C010'),
('ST050', 'Khalid Razi', 'khalidrazi', '200820e3227815ed1756a6b531e7e0d2', 'C010'),
('ST051', 'Lutfi Qasim', 'lutfiqasim', '200820e3227815ed1756a6b531e7e0d2', 'C011'),
('ST052', 'Mahdi Latif', 'mahdilatif', '200820e3227815ed1756a6b531e7e0d2', 'C011'),
('ST053', 'Naim Rahman', 'naimrahman', '200820e3227815ed1756a6b531e7e0d2', 'C011'),
('ST054', 'Osman Hakim', 'osmanhakim', '200820e3227815ed1756a6b531e7e0d2', 'C011'),
('ST055', 'Qasim Arif', 'qasimarif', '200820e3227815ed1756a6b531e7e0d2', 'C011'),
('ST056', 'Rafiq Jamil', 'rafiqjamil', '200820e3227815ed1756a6b531e7e0d2', 'C012'),
('ST057', 'Sulaiman Bashir', 'sulaimanbashir', '200820e3227815ed1756a6b531e7e0d2', 'C012'),
('ST058', 'Tamer Fahad', 'tamerfahad', '200820e3227815ed1756a6b531e7e0d2', 'C012'),
('ST059', 'Usayd Tariq', 'usaydtariq', '200820e3227815ed1756a6b531e7e0d2', 'C012'),
('ST060', 'Waleed Ghani', 'waleedghani', '200820e3227815ed1756a6b531e7e0d2', 'C012'),
('ST061', 'Yahya Hassan', 'yahyahassan', '200820e3227815ed1756a6b531e7e0d2', 'C013'),
('ST062', 'Zaid Idris', 'zaididris', '200820e3227815ed1756a6b531e7e0d2', 'C013'),
('ST063', 'Ayman Mahdi', 'aymanmahdi', '200820e3227815ed1756a6b531e7e0d2', 'C013'),
('ST064', 'Bilal Faisal', 'bilalfaisal', '200820e3227815ed1756a6b531e7e0d2', 'C013'),
('ST065', 'Dawud Karim', 'dawudkarim', '200820e3227815ed1756a6b531e7e0d2', 'C013'),
('ST066', 'Emad Ahmad', 'emadahmad', '200820e3227815ed1756a6b531e7e0d2', 'C014'),
('ST067', 'Fahim Latif', 'fahimlatif', '200820e3227815ed1756a6b531e7e0d2', 'C014'),
('ST068', 'Ghufran Syed', 'ghufransyed', '200820e3227815ed1756a6b531e7e0d2', 'C014'),
('ST069', 'Hisham Arif', 'hishamarif', '200820e3227815ed1756a6b531e7e0d2', 'C014'),
('ST070', 'Imad Razi', 'imadrazi', '200820e3227815ed1756a6b531e7e0d2', 'C014'),
('ST071', 'Jafar Naim', 'jafarnaim', '200820e3227815ed1756a6b531e7e0d2', 'C015'),
('ST072', 'Kamran Iqbal', 'kamraniqbal', '200820e3227815ed1756a6b531e7e0d2', 'C015'),
('ST073', 'Luqman Rahman', 'luqmanrahman', '200820e3227815ed1756a6b531e7e0d2', 'C015'),
('ST074', 'Mustafa Omar', 'mustafaomar', '200820e3227815ed1756a6b531e7e0d2', 'C015'),
('ST075', 'Najeeb Ghazi', 'najeebghazi', '200820e3227815ed1756a6b531e7e0d2', 'C015'),
('ST076', 'Omar Bashir', 'omarbashir', '200820e3227815ed1756a6b531e7e0d2', 'C016'),
('ST077', 'Qadir Faris', 'qadirfaris', '200820e3227815ed1756a6b531e7e0d2', 'C016'),
('ST078', 'Rashid Hadi', 'rashidhadi', '200820e3227815ed1756a6b531e7e0d2', 'C016'),
('ST079', 'Saqib Ibrahim', 'saqibibrahim', '200820e3227815ed1756a6b531e7e0d2', 'C016'),
('ST080', 'Tahir Jamal', 'tahirjamal', '200820e3227815ed1756a6b531e7e0d2', 'C016'),
('ST081', 'Usman Kamil', 'usmankamil', '200820e3227815ed1756a6b531e7e0d2', 'C017'),
('ST082', 'Waseem Naeem', 'waseemnaeem', '200820e3227815ed1756a6b531e7e0d2', 'C017'),
('ST083', 'Yasir Osman', 'yasirosman', '200820e3227815ed1756a6b531e7e0d2', 'C017'),
('ST084', 'Zafar Qasim', 'zafarqasim', '200820e3227815ed1756a6b531e7e0d2', 'C017'),
('ST085', 'Amir Rafiq', 'amirrafiq', '200820e3227815ed1756a6b531e7e0d2', 'C017'),
('ST086', 'Bashir Sami', 'bashirsami', '200820e3227815ed1756a6b531e7e0d2', 'C018'),
('ST087', 'Faris Tariq', 'faristariq', '200820e3227815ed1756a6b531e7e0d2', 'C018'),
('ST088', 'Ghazi Waleed', 'ghaziwaleed', '200820e3227815ed1756a6b531e7e0d2', 'C018'),
('ST089', 'Hadi Usayd', 'hadiusayd', '200820e3227815ed1756a6b531e7e0d2', 'C018'),
('ST090', 'Ibrahim Yahya', 'ibrahimyahya', '200820e3227815ed1756a6b531e7e0d2', 'C018'),
('ST091', 'Jamal Zaid', 'jamalzaid', '200820e3227815ed1756a6b531e7e0d2', 'C019'),
('ST092', 'Kamil Ayman', 'kamilayman', '200820e3227815ed1756a6b531e7e0d2', 'C019'),
('ST093', 'Latif Bilal', 'latifbilal', '200820e3227815ed1756a6b531e7e0d2', 'C019'),
('ST094', 'Mahmoud Dawud', 'mahmouddawud', '200820e3227815ed1756a6b531e7e0d2', 'C019'),
('ST095', 'Naeem Emad', 'naeememad', '200820e3227815ed1756a6b531e7e0d2', 'C019'),
('ST096', 'Osman Fahim', 'osmanfahim', '200820e3227815ed1756a6b531e7e0d2', 'C020'),
('ST097', 'Qasim Ghufran', 'qasimghufran', '200820e3227815ed1756a6b531e7e0d2', 'C020'),
('ST098', 'Rafiq Hisham', 'rafiqhisham', '200820e3227815ed1756a6b531e7e0d2', 'C020'),
('ST099', 'Sami Imad', 'samiimad', '200820e3227815ed1756a6b531e7e0d2', 'C020'),
('ST100', 'Tariq Jafar', 'tariqjafar', '200820e3227815ed1756a6b531e7e0d2', 'C020');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `memorizing_history`
--
ALTER TABLE `memorizing_history`
  ADD PRIMARY KEY (`memoHistory_id`),
  ADD KEY `memo_id` (`memo_id`);

--
-- Indexes for table `memorizing_record`
--
ALTER TABLE `memorizing_record`
  ADD PRIMARY KEY (`memo_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `class_id` (`class_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `memorizing_history`
--
ALTER TABLE `memorizing_history`
  MODIFY `memoHistory_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `memorizing_history`
--
ALTER TABLE `memorizing_history`
  ADD CONSTRAINT `memorizing_history_ibfk_1` FOREIGN KEY (`memo_id`) REFERENCES `memorizing_record` (`memo_id`);

--
-- Constraints for table `memorizing_record`
--
ALTER TABLE `memorizing_record`
  ADD CONSTRAINT `memorizing_record_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`),
  ADD CONSTRAINT `memorizing_record_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
