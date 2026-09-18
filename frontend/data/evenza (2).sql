-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2026 at 06:33 AM
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
-- Database: `evenza`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `email`, `password`, `status`, `created_at`) VALUES
(1, 'darshan@gmail.com', 'darshan123', 'active', '2026-07-11 07:06:07'),
(2, 'krish@gmail.com', 'krish123', 'active', '2026-07-11 08:56:25'),
(3, 'karan@gmail.com', 'karan123', 'active', '2026-07-11 08:56:25'),
(4, 'devashish@gmail.com', 'devashish123', 'active', '2026-07-11 08:56:25');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `status`) VALUES
(1, 'Technical Events', 'active'),
(2, 'Cultural Events', 'active'),
(3, 'Sports Events', 'active'),
(5, 'Management Events', 'active'),
(6, 'E-Sports & Gaming', 'active'),
(7, 'Art & Design', 'active'),
(8, 'Literary Events', 'active'),
(9, 'Workshops & Seminars', 'active'),
(10, 'Social & Fun Events', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `colleges`
--

CREATE TABLE `colleges` (
  `college_id` int(11) NOT NULL,
  `university_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `colleges`
--

INSERT INTO `colleges` (`college_id`, `university_id`, `name`, `slug`, `email`, `password`, `phone`, `address`, `logo`, `status`, `created_at`, `updated_at`) VALUES
(100, 3, 'Stanford College of Engineering', 'stanford-engineering', 'engineering@stanford.edu', '123456', '+1 555 111 001', 'Stanford, CA', 'c_52568d6e97c7f3ed.jpg', 'active', '2026-08-06 07:17:04', '2026-09-18 02:24:04'),
(101, 3, 'Stanford Graduate School of Business', 'stanford-business', 'business@stanford.edu', '123456', '+1 555 111 002', 'Stanford, CA', 'c_d543e94e37210067.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 11:59:27'),
(102, 4, 'MIT School of Engineering', 'mit-engineering', 'engg@mit.edu', '123456', '+1 777 222 001', 'Cambridge, MA', 'c_f6e8b67f12cbb769.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 12:36:18'),
(103, 4, 'MIT Sloan School of Management', 'mit-sloan', 'sloan@mit.edu', '123456', '+1 777 222 002', 'Cambridge, MA', 'c_6d76ff122a3d3b3c.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:01:05'),
(104, 5, 'Cambridge Faculty of Science', 'cambridge-science', 'science@cam.ac.uk', '123456', '+44 222 333 001', 'Cambridge, UK', 'c_8b1452ac97b3c0dc.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:01:36'),
(105, 5, 'Cambridge Faculty of Arts', 'cambridge-arts', 'arts@cam.ac.uk', '123456', '+44 222 333 002', 'Cambridge, UK', 'c_ec8e0435c6144bfb.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:02:10'),
(106, 6, 'VNSGU Department of Computer Science', 'vnsgu-cs', 'cs@vnsgu.ac.in', '123456', '+91 261 222 7101', 'Surat, Gujarat', 'c_0173c106f4c50442.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:02:52'),
(107, 6, 'VNSGU Department of Commerce', 'vnsgu-commerce', 'commerce@vnsgu.ac.in', '123456', '+91 261 222 7102', 'Surat, Gujarat', 'c_54090cd65474c0d3.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:03:56'),
(108, 7, 'SVNIT Computer Engineering', 'svnit-ce', 'ce@svnit.ac.in', '123456', '+91 261 225 9501', 'Surat, Gujarat', 'c_0099f3c2f8ad7611.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:05:14'),
(109, 7, 'SVNIT Mechanical Engineering', 'svnit-me', 'me@svnit.ac.in', '123456', '+91 261 225 9502', 'Surat, Gujarat', 'c_31dbfdbf68ed7ece.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:05:38'),
(110, 8, 'Gujarat University Science College', 'gu-science', 'science@gujaratuniversity.ac.in', '123456', '+91 79 2630 1301', 'Ahmedabad, Gujarat', 'c_9682911016eb8693.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:06:57'),
(111, 8, 'Gujarat University Arts College', 'gu-arts', 'arts@gujaratuniversity.ac.in', '123456', '+91 79 2630 1302', 'Ahmedabad, Gujarat', 'c_e6fad03568145131.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:09:22'),
(112, 9, 'MSU Faculty of Technology', 'msu-tech', 'tech@msubaroda.ac.in', '123456', '+91 265 279 5501', 'Vadodara, Gujarat', 'c_a0d987fd91e16bf7.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:11:48'),
(113, 9, 'MSU Faculty of Fine Arts', 'msu-finearts', 'finearts@msubaroda.ac.in', '123456', '+91 265 279 5502', 'Vadodara, Gujarat', 'c_61140f536f0664ea.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:12:55'),
(114, 10, 'Institute of Technology, Nirma University', 'nirma-tech', 'it@nirmauni.ac.in', '123456', '+91 79 7165 2001', 'Ahmedabad, Gujarat', 'c_6c0c6081598c4375.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:14:27'),
(115, 10, 'Institute of Management, Nirma University', 'nirma-mgmt', 'im@nirmauni.ac.in', '123456', '+91 79 7165 2002', 'Ahmedabad, Gujarat', 'c_356cf2fb560b3d77.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:15:02'),
(116, 11, 'GTU School of Engineering', 'gtu-engineering', 'engg@gtu.ac.in', '123456', '+91 79 2326 7501', 'Ahmedabad, Gujarat', 'c_323b9924afa032e4.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:16:19'),
(117, 11, 'GTU School of Pharmacy', 'gtu-pharmacy', 'pharma@gtu.ac.in', '123456', '+91 79 2326 7502', 'Ahmedabad, Gujarat', 'c_58ec841bbf42acaa.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:18:51'),
(118, 12, 'DDU Faculty of Engineering', 'ddu-engineering', 'foe@ddu.ac.in', '123456', '+91 268 252 0501', 'Nadiad, Gujarat', 'c_11fafc04bf4cb9fb.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:20:00'),
(119, 12, 'DDU Faculty of Management', 'ddu-management', 'fom@ddu.ac.in', '123456', '+91 268 252 0502', 'Nadiad, Gujarat', 'c_cbe58ded43ad78e1.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:20:59'),
(120, 13, 'PDEU School of Technology', 'pdeu-tech', 'sot@pdpu.ac.in', '123456', '+91 79 2327 5001', 'Gandhinagar, Gujarat', 'c_9dacbe2baec409eb.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:21:59'),
(121, 13, 'PDEU School of Petroleum Management', 'pdeu-spm', 'spm@pdpu.ac.in', '123456', '+91 79 2327 5002', 'Gandhinagar, Gujarat', 'c_18bd9211558df730.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:23:30'),
(122, 14, 'IIMA Post Graduate Programme', 'iima-pgp', 'pgp@iima.ac.in', '123456', '+91 79 2630 8301', 'Ahmedabad, Gujarat', 'c_a31aab91b535ae11.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:26:24'),
(123, 14, 'IIMA Executive Education', 'iima-exec', 'exec@iima.ac.in', '123456', '+91 79 2630 8302', 'Ahmedabad, Gujarat', 'c_c7277b0794ce261f.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:30:15'),
(124, 15, 'IITGN Computer Science', 'iitgn-cs', 'cs@iitgn.ac.in', '123456', '+91 79 2395 2001', 'Gandhinagar, Gujarat', 'c_9dc79f0d5eb90d5f.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:31:31'),
(125, 15, 'IITGN Electrical Engineering', 'iitgn-ee', 'ee@iitgn.ac.in', '123456', '+91 79 2395 2002', 'Gandhinagar, Gujarat', 'c_04f9a33ba0af2ad8.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:33:24'),
(126, 16, 'DA-IICT B.Tech Program', 'daiict-btech', 'btech@daiict.ac.in', '123456', '+91 79 3051 0501', 'Gandhinagar, Gujarat', 'c_3cce72f7d7e8a0f1.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:35:59'),
(127, 16, 'DA-IICT M.Tech Program', 'daiict-mtech', 'mtech@daiict.ac.in', '123456', '+91 79 3051 0502', 'Gandhinagar, Gujarat', 'c_53c7620eb7b32b9c.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:37:51'),
(128, 17, 'Saurashtra University Bioscience College', 'su-bioscience', 'bio@sauuni.ac.in', '123456', '+91 281 257 8502', 'Rajkot, Gujarat', 'c_3ba71ea3f91241d5.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:39:36'),
(129, 17, 'Saurashtra University Law College', 'su-law', 'law@sauuni.ac.in', '123456', '+91 281 257 8503', 'Rajkot, Gujarat', 'c_66c8d35d0aac3dd6.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:41:28'),
(130, 18, 'N.M. College of Agriculture', 'nau-agriculture', 'agri@nau.in', '123456', '+91 263 728 2701', 'Navsari, Gujarat', 'c_02d79b70b18981bd.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 13:13:30'),
(131, 18, 'ASPEE College of Horticulture', 'nau-horticulture', 'horti@nau.in', '123456', '+91 263 728 2702', 'Navsari, Gujarat', 'c_e2e344eb8d9e8404.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 13:15:09'),
(132, 19, 'B.A. College of Agriculture', 'aau-agriculture', 'agri@aau.in', '123456', '+91 269 226 1301', 'Anand, Gujarat', 'c_98f7fd4528d9e02b.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 02:57:18'),
(133, 19, 'College of Dairy Science', 'aau-dairy', 'dairy@aau.in', '123456', '+91 269 226 1302', 'Anand, Gujarat', 'c_0fa79eacd2269596.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 03:11:54'),
(134, 20, 'College of Agriculture, JAU', 'jau-agriculture', 'agri@jau.in', '123456', '+91 285 267 2001', 'Junagadh, Gujarat', 'c_686b8906d01f6ad5.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 03:13:22'),
(135, 20, 'College of Agricultural Engineering, JAU', 'jau-engineering', 'engg@jau.in', '123456', '+91 285 267 2002', 'Junagadh, Gujarat', 'c_e650bb7ba3e64321.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 03:15:57'),
(136, 21, 'Chhotubhai Gopalbhai Patel Institute of Tech', 'utu-cgpit', 'cgpit@utu.ac.in', '123456', '+91 262 229 0101', 'Bardoli, Gujarat', 'c_dd698a204ed39172.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 03:16:28'),
(137, 21, 'Maliba Pharmacy College', 'utu-maliba', 'maliba@utu.ac.in', '123456', '+91 262 229 0102', 'Bardoli, Gujarat', 'c_2bd90c9c09ddb503.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 03:17:01'),
(138, 22, 'Parul Institute of Engineering', 'parul-engineering', 'pie@paruluniversity.ac.in', '123456', '+91 266 826 0301', 'Vadodara, Gujarat', 'c_92687cc05e53c40e.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 03:18:18'),
(139, 22, 'Parul Institute of Medical Sciences', 'parul-medical', 'pims@paruluniversity.ac.in', '123456', '+91 266 826 0302', 'Vadodara, Gujarat', 'c_6b23c85bb37d5e19.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 03:19:30'),
(140, 23, 'Marwadi Engineering College', 'marwadi-engineering', 'engg@marwadiuniversity.ac.in', '123456', '+91 281 292 4101', 'Rajkot, Gujarat', 'c_d8e2ebfe87298ff8.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 03:20:20'),
(141, 23, 'Marwadi Science College', 'marwadi-science', 'science@marwadiuniversity.ac.in', '123456', '+91 281 292 4102', 'Rajkot, Gujarat', 'c_87d9b6c76f462b91.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 03:24:54'),
(142, 24, 'U.V. Patel College of Engineering', 'ganpat-uvpce', 'uvpce@ganpatuniversity.ac.in', '123456', '+91 276 228 6001', 'Mehsana, Gujarat', 'c_1f05187a3628e297.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 03:25:58'),
(143, 24, 'V.M. Patel College of Management', 'ganpat-vmpcm', 'vmpcm@ganpatuniversity.ac.in', '123456', '+91 276 228 6002', 'Mehsana, Gujarat', 'c_792b1c54be18de8e.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 03:26:28'),
(144, 25, 'Unitedworld Institute of Design', 'karnavati-uid', 'uid@karnavatiuniversity.edu.in', '123456', '+91 79 3053 5001', 'Gandhinagar, Gujarat', 'c_c58820c5df107b8a.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 03:27:15'),
(145, 25, 'Unitedworld School of Law', 'karnavati-law', 'law@karnavatiuniversity.edu.in', '123456', '+91 79 3053 5002', 'Gandhinagar, Gujarat', 'c_446484a7b9bb0289.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 03:30:55'),
(146, 26, 'St. Stephen’s College', 'du-stephens', 'admin@stephens.du.ac.in', '123456', '+91 11 2766 7701', 'New Delhi', 'c_1a31889869f675a0.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 03:32:03'),
(147, 26, 'Hindu College', 'du-hindu', 'admin@hindu.du.ac.in', '123456', '+91 11 2766 7702', 'New Delhi', 'c_a2f128ca5bebe302.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 03:32:45'),
(148, 27, 'School of International Studies', 'jnu-sis', 'sis@jnu.ac.in', '123456', '+91 11 2670 4001', 'New Delhi', 'c_e00ba6715de473fc.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 03:37:00'),
(149, 27, 'School of Language, Literature', 'jnu-sllcs', 'sllcs@jnu.ac.in', '123456', '+91 11 2670 4002', 'New Delhi', 'c_00eb1be1dd8a1ece.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 03:36:27'),
(150, 28, 'BHU Institute of Science', 'bhu-science', 'science@bhu.ac.in', '123456', '+91 542 236 8501', 'Varanasi, UP', 'c_a5ab1053b8e4fa20.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 03:39:45'),
(151, 28, 'BHU Institute of Medical Sciences', 'bhu-ims', 'ims@bhu.ac.in', '123456', '+91 542 236 8502', 'Varanasi, UP', 'c_f7eaadc7bc2b0fa0.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 03:40:13'),
(152, 29, 'Zakir Husain College of Engineering', 'amu-engineering', 'zhcet@amu.ac.in', '123456', '+91 571 270 0901', 'Aligarh, UP', 'c_1f1edb3edbb240f1.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 13:12:36'),
(153, 29, 'Jawaharlal Nehru Medical College', 'amu-medical', 'jnmc@amu.ac.in', '123456', '+91 571 270 0902', 'Aligarh, UP', 'c_d5acec3e680387c2.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 13:10:50'),
(154, 30, 'JMI Faculty of Engineering', 'jmi-engineering', 'engg@jmi.ac.in', '123456', '+91 11 2698 1701', 'New Delhi', 'c_890930649e9fbb06.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 13:06:10'),
(155, 30, 'AJK Mass Communication Research Centre', 'jmi-mcrc', 'mcrc@jmi.ac.in', '123456', '+91 11 2698 1702', 'New Delhi', 'c_177eb5909e6b6c65.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 13:08:13'),
(156, 31, 'UoH School of Physics', 'uoh-physics', 'physics@uohyd.ac.in', '123456', '+91 40 2313 2101', 'Hyderabad, Telangana', 'c_faef9082b9405f90.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 13:02:06'),
(157, 31, 'UoH School of Humanities', 'uoh-humanities', 'humanities@uohyd.ac.in', '123456', '+91 40 2313 2102', 'Hyderabad, Telangana', 'c_5bfb70409cb23608.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 13:04:21'),
(158, 32, 'College of Engineering Guindy', 'anna-ceg', 'ceg@annauniv.edu', '123456', '+91 44 2235 7001', 'Chennai, Tamil Nadu', 'c_53f0200bacc247ee.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 13:01:15'),
(159, 32, 'Madras Institute of Technology', 'anna-mit', 'mit@annauniv.edu', '123456', '+91 44 2235 7002', 'Chennai, Tamil Nadu', 'c_bd39e1b260861428.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:57:36'),
(160, 33, 'VIT School of Computer Science', 'vit-scope', 'scope@vit.ac.in', '123456', '+91 416 220 2001', 'Vellore, Tamil Nadu', 'c_7ed9d562554d909f.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:49:14'),
(161, 33, 'VIT Business School', 'vit-business', 'business@vit.ac.in', '123456', '+91 416 220 2002', 'Vellore, Tamil Nadu', 'c_12ca0fa666fb923f.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:49:53'),
(162, 34, 'SRM College of Engineering', 'srm-engineering', 'engg@srmist.edu.in', '123456', '+91 44 2745 5501', 'Chennai, Tamil Nadu', 'c_9d4e3513eb2f7622.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:54:57'),
(163, 34, 'SRM Medical College', 'srm-medical', 'medical@srmist.edu.in', '123456', '+91 44 2745 5502', 'Chennai, Tamil Nadu', 'c_39a36dcb0b1045e9.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:56:49'),
(164, 35, 'LPU School of Computer Application', 'lpu-sca', 'sca@lpu.co.in', '123456', '+91 182 451 7001', 'Phagwara, Punjab', 'c_92792e7928dc5ba5.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:47:07'),
(165, 35, 'LPU Mittal School of Business', 'lpu-business', 'business@lpu.co.in', '123456', '+91 182 451 7002', 'Phagwara, Punjab', 'c_26dbbbff4668c36c.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:48:02'),
(166, 36, 'CU Institute of Engineering', 'chd-engineering', 'engg@cumail.in', '123456', '+91 160 305 1001', 'Mohali, Punjab', 'c_ac62796162b00d13.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 03:40:54'),
(167, 36, 'CU Institute of Management', 'chd-management', 'mgmt@cumail.in', '123456', '+91 160 305 1002', 'Mohali, Punjab', 'c_57714dff30729784.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 03:41:26'),
(168, 37, 'KIIT School of Technology', 'kiit-tech', 'tech@kiit.ac.in', '123456', '+91 674 272 5101', 'Bhubaneswar, Odisha', 'c_bdc39d12a4e4c386.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:44:51'),
(169, 37, 'KIIT School of Management', 'kiit-ksom', 'ksom@kiit.ac.in', '123456', '+91 674 272 5102', 'Bhubaneswar, Odisha', 'c_ae63455fb62365cb.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:45:34'),
(170, 38, 'CU Faculty of Science', 'calu-science', 'science@caluniv.ac.in', '123456', '+91 33 2241 0001', 'Kolkata, West Bengal', 'c_f23bb90f258beb91.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 03:42:02'),
(171, 38, 'CU Faculty of Law', 'calu-law', 'law@caluniv.ac.in', '123456', '+91 33 2241 0002', 'Kolkata, West Bengal', 'c_1df9cbe1deb89c65.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 03:42:40'),
(172, 39, 'OU College of Engineering', 'ou-engineering', 'engg@osmania.ac.in', '123456', '+91 40 2709 8001', 'Hyderabad, Telangana', 'c_63c8342154c1706b.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:42:51'),
(173, 39, 'OU College of Science', 'ou-science', 'science@osmania.ac.in', '123456', '+91 40 2709 8002', 'Hyderabad, Telangana', 'c_553c77bd7a973296.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 12:42:21'),
(174, 40, 'SPPU Department of Technology', 'sppu-tech', 'tech@unipune.ac.in', '123456', '+91 20 2569 6001', 'Pune, Maharashtra', 'c_71ba26149049461d.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 03:43:19'),
(175, 40, 'SPPU Department of Management', 'sppu-mgmt', 'mgmt@unipune.ac.in', '123456', '+91 20 2569 6002', 'Pune, Maharashtra', 'c_ac926016735c052a.jpg', 'active', '2026-08-06 07:17:04', '2026-08-07 03:43:46'),
(176, 41, 'Christ School of Commerce', 'christ-commerce', 'commerce@christuniversity.in', '123456', '+91 80 4012 9101', 'Bengaluru, Karnataka', 'c_46c7c03bd8eabb42.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 07:36:26'),
(177, 41, 'Christ School of Law', 'christ-law', 'law@christuniversity.in', '123456', '+91 80 4012 9102', 'Bengaluru, Karnataka', 'c_f78f2add77fa79b1.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 07:35:32'),
(178, 42, 'Manipal Institute of Technology', 'mahe-mit', 'mit@manipal.edu', '123456', '+91 820 292 2401', 'Manipal, Karnataka', 'c_ee79d003fea89761.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 07:27:29'),
(179, 42, 'Kasturba Medical College', 'mahe-kmc', 'kmc@manipal.edu', '123456', '+91 820 292 2402', 'Manipal, Karnataka', 'c_e0416ba44b425b20.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 07:22:45');

-- --------------------------------------------------------

--
-- Table structure for table `entry_passes`
--

CREATE TABLE `entry_passes` (
  `pass_id` int(11) NOT NULL,
  `registration_id` int(11) NOT NULL,
  `pass_code` varchar(50) NOT NULL,
  `issued_at` timestamp NULL DEFAULT current_timestamp(),
  `expires_at` datetime DEFAULT NULL,
  `is_used` tinyint(1) DEFAULT 0,
  `used_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `college_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `event_type` enum('solo','team') NOT NULL,
  `min_team_size` int(11) DEFAULT 1,
  `max_team_size` int(11) DEFAULT 1,
  `fee_type` enum('per_person','per_team') NOT NULL,
  `registration_fee` decimal(10,2) DEFAULT 0.00,
  `registration_deadline` datetime DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `venue` varchar(200) DEFAULT NULL,
  `dress_code` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','cancelled') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `college_id`, `category_id`, `title`, `description`, `event_type`, `min_team_size`, `max_team_size`, `fee_type`, `registration_fee`, `registration_deadline`, `event_date`, `start_time`, `end_time`, `venue`, `dress_code`, `status`, `created_at`, `updated_at`) VALUES
(1, 100, 1, 'Code Odyssey 2026', 'An intensive 12-hour hackathon where coding enthusiasts build innovative software solutions from scratch.', 'team', 2, 4, 'per_person', 150.00, '2026-10-10 23:59:59', '2026-10-15', '09:00:00', '21:00:00', 'Main Computer Lab, Block A', NULL, 'active', '2026-08-07 06:09:23', '2026-09-18 01:53:30'),
(2, 101, 1, 'AI & Machine Learning Workshop', 'Learn the fundamentals of building neural networks and deploying modern machine learning pipelines using Python.', 'solo', 1, 1, 'per_person', 200.00, '2026-10-17 23:59:59', '2026-10-22', '10:00:00', '16:00:00', 'Seminar Hall 2', NULL, 'active', '2026-08-07 06:09:23', '2026-09-18 01:53:32'),
(3, 102, 2, 'Nrityanjali Classical Dance', 'A grand classical and semi-classical group dance competition celebrating traditional Indian performing arts.', 'team', 3, 8, 'per_person', 100.00, '2026-10-31 23:59:59', '2026-11-05', '17:00:00', '21:00:00', 'University Open Air Theatre', NULL, 'active', '2026-08-07 06:09:23', '2026-09-18 01:53:32'),
(4, 103, 2, 'Battle of the Bands', 'Rock, pop, and fusion music bands clash on stage for the ultimate musical supremacy title.', 'team', 3, 6, 'per_person', 300.00, '2026-11-12 23:59:59', '2026-11-18', '18:30:00', '22:30:00', 'Main Auditorium', NULL, 'active', '2026-08-07 06:09:23', '2026-09-18 01:53:34'),
(5, 104, 3, 'Inter-College Box Cricket League', 'Fast-paced tennis ball cricket tournament designed for fierce team rivalries and high scores.', 'team', 6, 8, 'per_person', 500.00, '2026-11-25 23:59:59', '2026-12-02', '08:00:00', '18:00:00', 'University Sports Ground', NULL, 'active', '2026-08-07 06:09:23', '2026-09-18 01:53:34'),
(6, 105, 3, 'Badminton Smash Championship', 'Singles and doubles badminton tournament showcasing agility, speed, and precision strokes.', 'solo', 1, 2, 'per_person', 150.00, '2026-10-10 23:59:59', '2026-10-15', '09:30:00', '17:00:00', 'Indoor Sports Complex', NULL, 'active', '2026-08-07 06:09:23', '2026-09-18 01:53:36'),
(7, 106, 5, 'Corporate Shark Tank', 'Pitch your innovative startup ideas and business models in front of seasoned industry investors.', 'team', 2, 4, 'per_person', 250.00, '2026-10-17 23:59:59', '2026-10-22', '11:00:00', '16:00:00', 'Management Block Conference Hall', NULL, 'active', '2026-08-07 06:09:23', '2026-09-18 01:53:37'),
(8, 107, 6, 'Valorant E-Sports Showdown', 'Tactical 5v5 shooter esports tournament with live broadcasting and massive prize pools.', 'team', 5, 5, 'per_person', 200.00, '2026-10-31 23:59:59', '2026-11-05', '13:00:00', '20:00:00', 'Digital Gaming Arena, Lab 4', NULL, 'active', '2026-08-07 06:09:23', '2026-09-18 01:53:38'),
(9, 108, 7, 'Canvas Painting & Sketching', 'Express your creativity on canvas through fine arts, oil painting, and expressive sketching.', 'solo', 1, 1, 'per_person', 100.00, '2026-11-12 23:59:59', '2026-11-18', '10:00:00', '14:00:00', 'Fine Arts Studio Room 3', NULL, 'active', '2026-08-07 06:09:23', '2026-09-18 01:53:40'),
(10, 109, 8, 'Extempore & Parliamentary Debate', 'Test your eloquence, vocabulary, and sharp critical thinking through structured debates.', 'solo', 1, 1, 'per_person', 50.00, '2026-11-25 23:59:59', '2026-12-02', '14:00:00', '17:30:00', 'Library Seminar Room', NULL, 'active', '2026-08-07 06:09:23', '2026-09-18 01:53:41'),
(11, 100, 1, 'Winter AI Challenge 2026', 'A premier 48-hour AI and Machine Learning hackathon happening this winter. Collaborate to build next-gen models and solve real-world problems.', 'team', 2, 4, 'per_team', 500.00, '2026-10-10 23:59:00', '2026-10-15', '09:10:00', '18:00:00', 'Online', NULL, 'active', '2026-09-17 08:58:06', '2026-09-18 02:21:26'),
(12, 138, 3, 'Reverse Run', 'Run Backwards to win', 'solo', 1, 1, 'per_person', 100.00, '2026-09-30 00:00:00', '2026-12-25', '10:40:00', '13:50:00', 'Parul Sports Ground', NULL, 'active', '2026-09-17 15:46:39', '2026-09-18 02:21:43');

-- --------------------------------------------------------

--
-- Table structure for table `event_contacts`
--

CREATE TABLE `event_contacts` (
  `contact_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `person_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_prizes`
--

CREATE TABLE `event_prizes` (
  `prize_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `position` enum('winner','runner_up','second_runner_up') DEFAULT NULL,
  `prize` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_rounds`
--

CREATE TABLE `event_rounds` (
  `round_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `round_name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `duration_minutes` int(11) DEFAULT NULL,
  `sequence_no` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_rules`
--

CREATE TABLE `event_rules` (
  `rule_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `rule` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `inquiry_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `college` varchar(150) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`inquiry_id`, `full_name`, `email`, `college`, `subject`, `message`, `submitted_at`) VALUES
(1, 'User2', 'user@gmail.com', '138', 'event_registration', 'Can we apply in multiple events?', '2026-09-18 00:13:09');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `registration_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','upi','card','bank_transfer') DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `payment_date` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `registration_id`, `amount`, `payment_method`, `transaction_id`, `payment_status`, `payment_date`) VALUES
(1, 10, 500.00, 'upi', 'TXN_SANDBOX_6AABEB3937478', 'paid', '2026-09-17 13:29:29'),
(2, 10, 500.00, 'upi', 'TXN_SANDBOX_6AABEB40F09AA', 'paid', '2026-09-17 13:29:36'),
(3, 11, 500.00, 'upi', 'TXN_EVN_6AABEDA9305F0', 'paid', '2026-09-17 13:39:53'),
(4, 12, 100.00, 'upi', 'TXN_EVN_6AAC10DA761AB', 'paid', '2026-09-17 16:10:02'),
(5, 13, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(6, 14, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(7, 15, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(8, 16, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(9, 17, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(10, 18, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(11, 19, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(12, 20, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(13, 21, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(14, 22, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(15, 23, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(16, 24, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(17, 25, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(18, 26, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(19, 27, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(20, 28, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(21, 29, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(22, 30, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(23, 31, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(24, 32, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(25, 33, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(26, 34, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(27, 35, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(28, 36, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(29, 37, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(30, 38, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(31, 39, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(32, 40, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(33, 41, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(34, 42, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(35, 43, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(36, 44, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(37, 45, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(38, 46, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(39, 47, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(40, 48, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(41, 49, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(42, 50, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(43, 51, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(44, 52, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(45, 53, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(46, 54, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(47, 55, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(48, 56, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(49, 57, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(50, 58, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(51, 59, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(52, 60, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(53, 61, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(54, 62, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(55, 63, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(56, 64, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(57, 65, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(58, 66, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(59, 67, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(60, 68, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(61, 69, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(62, 70, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(63, 71, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(64, 72, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(65, 73, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(66, 74, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(67, 75, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(68, 76, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(69, 77, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(70, 78, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(71, 79, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(72, 80, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(73, 81, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(74, 82, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(75, 83, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(76, 84, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(77, 85, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(78, 86, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(79, 87, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(80, 88, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(81, 89, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(82, 90, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(83, 91, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(84, 92, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(85, 93, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(86, 94, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(87, 95, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(88, 96, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(89, 97, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(90, 98, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(91, 99, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(92, 100, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(93, 101, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(94, 102, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(95, 103, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(96, 104, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(97, 105, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(98, 106, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(99, 107, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(100, 108, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(101, 109, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(102, 110, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(103, 111, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(104, 112, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(105, 113, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(106, 114, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(107, 115, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(108, 116, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(109, 117, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(110, 118, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(111, 119, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(112, 120, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(113, 121, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(114, 122, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(115, 123, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(116, 124, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(117, 125, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(118, 126, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(119, 127, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(120, 128, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(121, 129, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(122, 130, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(123, 131, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(124, 132, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(125, 133, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(126, 134, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(127, 135, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(128, 136, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(129, 137, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(130, 138, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(131, 139, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(132, 140, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(133, 141, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(134, 142, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(135, 143, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(136, 144, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(137, 145, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(138, 146, 300.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(139, 147, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(140, 148, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(141, 149, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(142, 150, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(143, 151, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(144, 152, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(145, 153, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(146, 154, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(147, 155, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(148, 156, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(149, 157, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(150, 158, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(151, 159, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(152, 160, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(153, 161, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(154, 162, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(155, 163, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(156, 164, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(157, 165, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(158, 166, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(159, 167, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(160, 168, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(161, 169, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(162, 170, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(163, 171, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(164, 172, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(165, 173, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(166, 174, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(167, 175, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(168, 176, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(169, 177, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(170, 178, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(171, 179, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(172, 180, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(173, 181, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(174, 182, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(175, 183, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(176, 184, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(177, 185, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(178, 186, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(179, 187, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(180, 188, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(181, 189, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(182, 190, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(183, 191, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(184, 192, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(185, 193, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(186, 194, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(187, 195, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(188, 196, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(189, 197, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(190, 198, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(191, 199, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(192, 200, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(193, 201, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(194, 202, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(195, 203, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:33'),
(196, 204, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(197, 205, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(198, 206, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(199, 207, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(200, 208, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(201, 209, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(202, 210, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(203, 211, 150.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(204, 212, 250.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(205, 213, 250.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(206, 214, 250.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(207, 215, 250.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(208, 216, 250.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(209, 217, 250.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(210, 218, 250.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(211, 219, 250.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(212, 220, 250.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(213, 221, 250.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(214, 222, 250.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(215, 223, 250.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(216, 224, 250.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(217, 225, 250.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(218, 226, 250.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(219, 227, 250.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(220, 228, 250.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(221, 229, 250.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(222, 230, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(223, 231, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(224, 232, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(225, 233, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(226, 234, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(227, 235, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(228, 236, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(229, 237, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(230, 238, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(231, 239, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(232, 240, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(233, 241, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(234, 242, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(235, 243, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(236, 244, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(237, 245, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(238, 246, 200.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(239, 247, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(240, 248, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(241, 249, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(242, 250, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(243, 251, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(244, 252, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(245, 253, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(246, 254, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(247, 255, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(248, 256, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(249, 257, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(250, 258, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(251, 259, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(252, 260, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(253, 261, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(254, 262, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(255, 263, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(256, 264, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(257, 265, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(258, 266, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(259, 267, 50.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(260, 268, 50.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(261, 269, 50.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(262, 270, 50.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(263, 271, 50.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(264, 272, 50.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(265, 273, 50.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(266, 274, 50.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(267, 275, 50.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(268, 276, 50.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(269, 277, 50.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(270, 278, 50.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(271, 279, 50.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(272, 280, 50.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(273, 281, 50.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(274, 282, 50.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(275, 283, 50.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(276, 284, 50.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(277, 285, 50.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(278, 286, 50.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(279, 287, 50.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(280, 288, 50.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(281, 289, 50.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(282, 290, 50.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(283, 291, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(284, 292, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(285, 293, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(286, 294, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(287, 295, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(288, 296, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(289, 297, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(290, 298, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(291, 299, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(292, 300, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(293, 301, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(294, 302, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(295, 303, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(296, 304, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(297, 305, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(298, 306, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(299, 307, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(300, 308, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(301, 309, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(302, 310, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(303, 311, 500.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(304, 312, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(305, 313, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(306, 314, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(307, 315, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(308, 316, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(309, 317, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(310, 318, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(311, 319, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(312, 320, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(313, 321, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(314, 322, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(315, 323, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(316, 324, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(317, 325, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(318, 326, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(319, 327, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(320, 328, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(321, 329, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(322, 330, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(323, 331, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(324, 332, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(325, 333, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(326, 334, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(327, 335, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(328, 336, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(329, 337, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(330, 338, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(331, 339, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(332, 340, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(333, 341, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(334, 342, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(335, 343, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(336, 344, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34'),
(337, 345, 100.00, NULL, NULL, 'paid', '2026-09-17 16:23:34');

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `registration_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `registration_type` enum('solo','team') DEFAULT NULL,
  `status` enum('pending','approved','cancelled') DEFAULT 'pending',
  `registered_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`registration_id`, `event_id`, `student_id`, `team_id`, `registration_type`, `status`, `registered_at`) VALUES
(1, 10, 1, NULL, 'solo', 'approved', '2026-08-07 07:13:53'),
(2, 9, 2, NULL, 'solo', 'approved', '2026-08-07 07:13:53'),
(3, 8, 3, NULL, 'solo', 'pending', '2026-08-07 07:13:53'),
(4, 7, 4, NULL, 'solo', 'approved', '2026-08-07 07:13:53'),
(5, 1, 5, 1, 'team', 'approved', '2026-08-07 07:13:53'),
(6, 3, 6, 2, 'team', 'pending', '2026-08-07 07:13:53'),
(7, 4, 7, 3, 'team', 'approved', '2026-08-07 07:13:53'),
(8, 2, 8, NULL, 'solo', 'cancelled', '2026-08-07 07:13:53'),
(9, 11, 241, NULL, NULL, 'cancelled', '2026-09-17 11:48:10'),
(10, 11, 242, 4, 'team', 'approved', '2026-09-17 13:29:29'),
(11, 11, 236, NULL, NULL, 'approved', '2026-09-17 13:39:53'),
(12, 12, 242, NULL, NULL, 'approved', '2026-09-17 16:10:02'),
(13, 1, 141, 5, 'team', 'approved', '2026-09-12 16:23:33'),
(14, 1, 215, 5, 'team', 'approved', '2026-09-15 16:23:33'),
(15, 1, 26, 5, 'team', 'approved', '2026-09-13 16:23:33'),
(16, 1, 122, 6, 'team', 'approved', '2026-09-16 16:23:33'),
(17, 1, 227, 6, 'team', 'approved', '2026-09-16 16:23:33'),
(18, 1, 187, 6, 'team', 'approved', '2026-09-15 16:23:33'),
(19, 1, 25, 7, 'team', 'approved', '2026-09-11 16:23:33'),
(20, 1, 198, 7, 'team', 'approved', '2026-09-15 16:23:33'),
(21, 1, 114, 7, 'team', 'approved', '2026-09-14 16:23:33'),
(22, 1, 211, 8, 'team', 'approved', '2026-09-15 16:23:33'),
(23, 1, 51, 8, 'team', 'approved', '2026-09-17 16:23:33'),
(24, 1, 31, 8, 'team', 'approved', '2026-09-17 16:23:33'),
(25, 1, 82, 9, 'team', 'approved', '2026-09-15 16:23:33'),
(26, 1, 65, 9, 'team', 'approved', '2026-09-11 16:23:33'),
(27, 1, 93, 9, 'team', 'approved', '2026-09-17 16:23:33'),
(28, 1, 3, 10, 'team', 'approved', '2026-09-13 16:23:33'),
(29, 1, 104, 10, 'team', 'approved', '2026-09-17 16:23:33'),
(30, 1, 36, 10, 'team', 'approved', '2026-09-13 16:23:33'),
(31, 1, 78, 11, 'team', 'approved', '2026-09-12 16:23:33'),
(32, 1, 138, 11, 'team', 'approved', '2026-09-11 16:23:33'),
(33, 1, 2, 11, 'team', 'approved', '2026-09-15 16:23:33'),
(34, 1, 179, 12, 'team', 'approved', '2026-09-11 16:23:33'),
(35, 1, 224, 12, 'team', 'approved', '2026-09-11 16:23:33'),
(36, 1, 242, 12, 'team', 'approved', '2026-09-13 16:23:33'),
(37, 1, 183, 13, 'team', 'approved', '2026-09-15 16:23:33'),
(38, 1, 221, 13, 'team', 'approved', '2026-09-13 16:23:33'),
(39, 1, 144, 13, 'team', 'approved', '2026-09-14 16:23:33'),
(40, 1, 177, 14, 'team', 'approved', '2026-09-12 16:23:33'),
(41, 1, 201, 14, 'team', 'approved', '2026-09-11 16:23:33'),
(42, 1, 11, 14, 'team', 'approved', '2026-09-12 16:23:33'),
(43, 1, 218, 15, 'team', 'approved', '2026-09-12 16:23:33'),
(44, 1, 89, 15, 'team', 'approved', '2026-09-15 16:23:33'),
(45, 1, 127, 15, 'team', 'approved', '2026-09-11 16:23:33'),
(46, 1, 86, 16, 'team', 'approved', '2026-09-16 16:23:33'),
(47, 1, 113, 16, 'team', 'approved', '2026-09-15 16:23:33'),
(48, 2, 28, NULL, 'solo', 'approved', '2026-09-17 16:23:33'),
(49, 2, 145, NULL, 'solo', 'approved', '2026-09-13 16:23:33'),
(50, 2, 27, NULL, 'solo', 'approved', '2026-09-13 16:23:33'),
(51, 2, 63, NULL, 'solo', 'approved', '2026-09-17 16:23:33'),
(52, 2, 240, NULL, 'solo', 'approved', '2026-09-12 16:23:33'),
(53, 2, 23, NULL, 'solo', 'approved', '2026-09-12 16:23:33'),
(54, 2, 197, NULL, 'solo', 'approved', '2026-09-15 16:23:33'),
(55, 2, 10, NULL, 'solo', 'approved', '2026-09-16 16:23:33'),
(56, 2, 29, NULL, 'solo', 'approved', '2026-09-15 16:23:33'),
(57, 2, 234, NULL, 'solo', 'approved', '2026-09-12 16:23:33'),
(58, 2, 160, NULL, 'solo', 'approved', '2026-09-15 16:23:33'),
(59, 2, 162, NULL, 'solo', 'approved', '2026-09-17 16:23:33'),
(60, 2, 225, NULL, 'solo', 'approved', '2026-09-17 16:23:33'),
(61, 2, 154, NULL, 'solo', 'approved', '2026-09-15 16:23:33'),
(62, 2, 65, NULL, 'solo', 'approved', '2026-09-12 16:23:33'),
(63, 2, 209, NULL, 'solo', 'approved', '2026-09-13 16:23:33'),
(64, 2, 201, NULL, 'solo', 'approved', '2026-09-17 16:23:33'),
(65, 2, 51, NULL, 'solo', 'approved', '2026-09-14 16:23:33'),
(66, 2, 39, NULL, 'solo', 'approved', '2026-09-16 16:23:33'),
(67, 2, 190, NULL, 'solo', 'approved', '2026-09-15 16:23:33'),
(68, 2, 222, NULL, 'solo', 'approved', '2026-09-15 16:23:33'),
(69, 2, 76, NULL, 'solo', 'approved', '2026-09-14 16:23:33'),
(70, 2, 83, NULL, 'solo', 'approved', '2026-09-15 16:23:33'),
(71, 2, 71, NULL, 'solo', 'approved', '2026-09-13 16:23:33'),
(72, 2, 103, NULL, 'solo', 'approved', '2026-09-17 16:23:33'),
(73, 2, 217, NULL, 'solo', 'approved', '2026-09-14 16:23:33'),
(74, 2, 16, NULL, 'solo', 'approved', '2026-09-16 16:23:33'),
(75, 2, 78, NULL, 'solo', 'approved', '2026-09-15 16:23:33'),
(76, 2, 34, NULL, 'solo', 'approved', '2026-09-14 16:23:33'),
(77, 2, 43, NULL, 'solo', 'approved', '2026-09-14 16:23:33'),
(78, 2, 130, NULL, 'solo', 'approved', '2026-09-13 16:23:33'),
(79, 2, 12, NULL, 'solo', 'approved', '2026-09-17 16:23:33'),
(80, 2, 96, NULL, 'solo', 'approved', '2026-09-17 16:23:33'),
(81, 2, 172, NULL, 'solo', 'approved', '2026-09-14 16:23:33'),
(82, 2, 72, NULL, 'solo', 'approved', '2026-09-16 16:23:33'),
(83, 3, 64, 17, 'team', 'approved', '2026-09-15 16:23:33'),
(84, 3, 86, 17, 'team', 'approved', '2026-09-15 16:23:33'),
(85, 3, 110, 17, 'team', 'approved', '2026-09-12 16:23:33'),
(86, 3, 141, 18, 'team', 'approved', '2026-09-17 16:23:33'),
(87, 3, 9, 18, 'team', 'approved', '2026-09-13 16:23:33'),
(88, 3, 28, 18, 'team', 'approved', '2026-09-15 16:23:33'),
(89, 3, 173, 19, 'team', 'approved', '2026-09-14 16:23:33'),
(90, 3, 88, 19, 'team', 'approved', '2026-09-16 16:23:33'),
(91, 3, 136, 19, 'team', 'approved', '2026-09-11 16:23:33'),
(92, 3, 187, 20, 'team', 'approved', '2026-09-16 16:23:33'),
(93, 3, 190, 20, 'team', 'approved', '2026-09-11 16:23:33'),
(94, 3, 228, 20, 'team', 'approved', '2026-09-17 16:23:33'),
(95, 3, 159, 21, 'team', 'approved', '2026-09-14 16:23:33'),
(96, 3, 99, 21, 'team', 'approved', '2026-09-15 16:23:33'),
(97, 3, 210, 21, 'team', 'approved', '2026-09-16 16:23:33'),
(98, 3, 106, 22, 'team', 'approved', '2026-09-16 16:23:33'),
(99, 3, 175, 22, 'team', 'approved', '2026-09-16 16:23:33'),
(100, 3, 16, 22, 'team', 'approved', '2026-09-16 16:23:33'),
(101, 3, 103, 23, 'team', 'approved', '2026-09-12 16:23:33'),
(102, 3, 92, 23, 'team', 'approved', '2026-09-11 16:23:33'),
(103, 3, 151, 23, 'team', 'approved', '2026-09-14 16:23:33'),
(104, 3, 21, 24, 'team', 'approved', '2026-09-14 16:23:33'),
(105, 3, 236, 24, 'team', 'approved', '2026-09-16 16:23:33'),
(106, 3, 43, 24, 'team', 'approved', '2026-09-13 16:23:33'),
(107, 3, 208, 25, 'team', 'approved', '2026-09-16 16:23:33'),
(108, 3, 114, 25, 'team', 'approved', '2026-09-15 16:23:33'),
(109, 3, 1, 25, 'team', 'approved', '2026-09-11 16:23:33'),
(110, 3, 97, 26, 'team', 'approved', '2026-09-11 16:23:33'),
(111, 3, 240, 26, 'team', 'approved', '2026-09-17 16:23:33'),
(112, 4, 68, 27, 'team', 'approved', '2026-09-17 16:23:33'),
(113, 4, 183, 27, 'team', 'approved', '2026-09-17 16:23:33'),
(114, 4, 184, 27, 'team', 'approved', '2026-09-16 16:23:33'),
(115, 4, 170, 28, 'team', 'approved', '2026-09-12 16:23:33'),
(116, 4, 32, 28, 'team', 'approved', '2026-09-13 16:23:33'),
(117, 4, 29, 28, 'team', 'approved', '2026-09-12 16:23:33'),
(118, 4, 216, 29, 'team', 'approved', '2026-09-13 16:23:33'),
(119, 4, 224, 29, 'team', 'approved', '2026-09-16 16:23:33'),
(120, 4, 145, 29, 'team', 'approved', '2026-09-11 16:23:33'),
(121, 4, 86, 30, 'team', 'approved', '2026-09-12 16:23:33'),
(122, 4, 91, 30, 'team', 'approved', '2026-09-14 16:23:33'),
(123, 4, 198, 30, 'team', 'approved', '2026-09-15 16:23:33'),
(124, 4, 180, 31, 'team', 'approved', '2026-09-11 16:23:33'),
(125, 4, 104, 31, 'team', 'approved', '2026-09-14 16:23:33'),
(126, 4, 24, 31, 'team', 'approved', '2026-09-17 16:23:33'),
(127, 4, 62, 32, 'team', 'approved', '2026-09-14 16:23:33'),
(128, 4, 119, 32, 'team', 'approved', '2026-09-14 16:23:33'),
(129, 4, 130, 32, 'team', 'approved', '2026-09-12 16:23:33'),
(130, 4, 43, 33, 'team', 'approved', '2026-09-15 16:23:33'),
(131, 4, 113, 33, 'team', 'approved', '2026-09-13 16:23:33'),
(132, 4, 159, 33, 'team', 'approved', '2026-09-12 16:23:33'),
(133, 4, 202, 34, 'team', 'approved', '2026-09-15 16:23:33'),
(134, 4, 90, 34, 'team', 'approved', '2026-09-16 16:23:33'),
(135, 4, 38, 34, 'team', 'approved', '2026-09-15 16:23:33'),
(136, 4, 114, 35, 'team', 'approved', '2026-09-17 16:23:33'),
(137, 4, 134, 35, 'team', 'approved', '2026-09-11 16:23:33'),
(138, 4, 46, 35, 'team', 'approved', '2026-09-13 16:23:33'),
(139, 4, 8, 36, 'team', 'approved', '2026-09-13 16:23:33'),
(140, 4, 67, 36, 'team', 'approved', '2026-09-11 16:23:33'),
(141, 4, 36, 36, 'team', 'approved', '2026-09-12 16:23:33'),
(142, 4, 232, 37, 'team', 'approved', '2026-09-17 16:23:33'),
(143, 4, 80, 37, 'team', 'approved', '2026-09-11 16:23:33'),
(144, 4, 137, 37, 'team', 'approved', '2026-09-13 16:23:33'),
(145, 4, 157, 38, 'team', 'approved', '2026-09-16 16:23:33'),
(146, 4, 166, 38, 'team', 'approved', '2026-09-16 16:23:33'),
(147, 5, 43, 39, 'team', 'approved', '2026-09-15 16:23:33'),
(148, 5, 59, 39, 'team', 'approved', '2026-09-16 16:23:33'),
(149, 5, 196, 39, 'team', 'approved', '2026-09-11 16:23:33'),
(150, 5, 62, 40, 'team', 'approved', '2026-09-16 16:23:33'),
(151, 5, 110, 40, 'team', 'approved', '2026-09-17 16:23:33'),
(152, 5, 211, 40, 'team', 'approved', '2026-09-11 16:23:33'),
(153, 5, 155, 41, 'team', 'approved', '2026-09-13 16:23:33'),
(154, 5, 161, 41, 'team', 'approved', '2026-09-17 16:23:33'),
(155, 5, 229, 41, 'team', 'approved', '2026-09-13 16:23:33'),
(156, 5, 182, 42, 'team', 'approved', '2026-09-11 16:23:33'),
(157, 5, 19, 42, 'team', 'approved', '2026-09-13 16:23:33'),
(158, 5, 191, 42, 'team', 'approved', '2026-09-14 16:23:33'),
(159, 5, 33, 43, 'team', 'approved', '2026-09-15 16:23:33'),
(160, 5, 173, 43, 'team', 'approved', '2026-09-14 16:23:33'),
(161, 5, 241, 43, 'team', 'approved', '2026-09-15 16:23:33'),
(162, 5, 70, 44, 'team', 'approved', '2026-09-16 16:23:33'),
(163, 5, 150, 44, 'team', 'approved', '2026-09-16 16:23:33'),
(164, 5, 186, 44, 'team', 'approved', '2026-09-16 16:23:33'),
(165, 5, 7, 45, 'team', 'approved', '2026-09-13 16:23:33'),
(166, 5, 133, 45, 'team', 'approved', '2026-09-15 16:23:33'),
(167, 5, 45, 45, 'team', 'approved', '2026-09-17 16:23:33'),
(168, 5, 165, 46, 'team', 'approved', '2026-09-15 16:23:33'),
(169, 5, 169, 46, 'team', 'approved', '2026-09-15 16:23:33'),
(170, 5, 239, 46, 'team', 'approved', '2026-09-14 16:23:33'),
(171, 5, 76, 47, 'team', 'approved', '2026-09-12 16:23:33'),
(172, 5, 171, 47, 'team', 'approved', '2026-09-14 16:23:33'),
(173, 5, 237, 47, 'team', 'approved', '2026-09-12 16:23:33'),
(174, 5, 132, 48, 'team', 'approved', '2026-09-12 16:23:33'),
(175, 5, 78, 48, 'team', 'approved', '2026-09-13 16:23:33'),
(176, 5, 67, 48, 'team', 'approved', '2026-09-14 16:23:33'),
(177, 5, 77, 49, 'team', 'approved', '2026-09-12 16:23:33'),
(178, 5, 159, 49, 'team', 'approved', '2026-09-15 16:23:33'),
(179, 5, 215, 49, 'team', 'approved', '2026-09-13 16:23:33'),
(180, 6, 118, NULL, 'solo', 'approved', '2026-09-12 16:23:33'),
(181, 6, 40, NULL, 'solo', 'approved', '2026-09-17 16:23:33'),
(182, 6, 209, NULL, 'solo', 'approved', '2026-09-15 16:23:33'),
(183, 6, 210, NULL, 'solo', 'approved', '2026-09-16 16:23:33'),
(184, 6, 6, NULL, 'solo', 'approved', '2026-09-16 16:23:33'),
(185, 6, 172, NULL, 'solo', 'approved', '2026-09-14 16:23:33'),
(186, 6, 96, NULL, 'solo', 'approved', '2026-09-13 16:23:33'),
(187, 6, 85, NULL, 'solo', 'approved', '2026-09-11 16:23:33'),
(188, 6, 109, NULL, 'solo', 'approved', '2026-09-15 16:23:33'),
(189, 6, 48, NULL, 'solo', 'approved', '2026-09-11 16:23:33'),
(190, 6, 79, NULL, 'solo', 'approved', '2026-09-17 16:23:33'),
(191, 6, 203, NULL, 'solo', 'approved', '2026-09-16 16:23:33'),
(192, 6, 76, NULL, 'solo', 'approved', '2026-09-16 16:23:33'),
(193, 6, 53, NULL, 'solo', 'approved', '2026-09-11 16:23:33'),
(194, 6, 134, NULL, 'solo', 'approved', '2026-09-15 16:23:33'),
(195, 6, 72, NULL, 'solo', 'approved', '2026-09-12 16:23:33'),
(196, 6, 115, NULL, 'solo', 'approved', '2026-09-12 16:23:33'),
(197, 6, 73, NULL, 'solo', 'approved', '2026-09-14 16:23:33'),
(198, 6, 36, NULL, 'solo', 'approved', '2026-09-15 16:23:33'),
(199, 6, 146, NULL, 'solo', 'approved', '2026-09-14 16:23:33'),
(200, 6, 227, NULL, 'solo', 'approved', '2026-09-11 16:23:33'),
(201, 6, 133, NULL, 'solo', 'approved', '2026-09-14 16:23:33'),
(202, 6, 65, NULL, 'solo', 'approved', '2026-09-14 16:23:33'),
(203, 6, 196, NULL, 'solo', 'approved', '2026-09-12 16:23:33'),
(204, 6, 131, NULL, 'solo', 'approved', '2026-09-11 16:23:34'),
(205, 6, 220, NULL, 'solo', 'approved', '2026-09-17 16:23:34'),
(206, 6, 175, NULL, 'solo', 'approved', '2026-09-14 16:23:34'),
(207, 6, 121, NULL, 'solo', 'approved', '2026-09-15 16:23:34'),
(208, 6, 235, NULL, 'solo', 'approved', '2026-09-14 16:23:34'),
(209, 6, 35, NULL, 'solo', 'approved', '2026-09-11 16:23:34'),
(210, 6, 21, NULL, 'solo', 'approved', '2026-09-13 16:23:34'),
(211, 6, 165, NULL, 'solo', 'approved', '2026-09-16 16:23:34'),
(212, 7, 96, 50, 'team', 'approved', '2026-09-16 16:23:34'),
(213, 7, 130, 50, 'team', 'approved', '2026-09-16 16:23:34'),
(214, 7, 84, 50, 'team', 'approved', '2026-09-14 16:23:34'),
(215, 7, 193, 51, 'team', 'approved', '2026-09-12 16:23:34'),
(216, 7, 86, 51, 'team', 'approved', '2026-09-13 16:23:34'),
(217, 7, 33, 51, 'team', 'approved', '2026-09-12 16:23:34'),
(218, 7, 216, 52, 'team', 'approved', '2026-09-11 16:23:34'),
(219, 7, 220, 52, 'team', 'approved', '2026-09-15 16:23:34'),
(220, 7, 179, 52, 'team', 'approved', '2026-09-16 16:23:34'),
(221, 7, 145, 53, 'team', 'approved', '2026-09-17 16:23:34'),
(222, 7, 119, 53, 'team', 'approved', '2026-09-15 16:23:34'),
(223, 7, 135, 53, 'team', 'approved', '2026-09-13 16:23:34'),
(224, 7, 75, 54, 'team', 'approved', '2026-09-11 16:23:34'),
(225, 7, 47, 54, 'team', 'approved', '2026-09-16 16:23:34'),
(226, 7, 207, 54, 'team', 'approved', '2026-09-11 16:23:34'),
(227, 7, 76, 55, 'team', 'approved', '2026-09-11 16:23:34'),
(228, 7, 213, 55, 'team', 'approved', '2026-09-11 16:23:34'),
(229, 7, 204, 55, 'team', 'approved', '2026-09-17 16:23:34'),
(230, 8, 23, 56, 'team', 'approved', '2026-09-16 16:23:34'),
(231, 8, 223, 56, 'team', 'approved', '2026-09-13 16:23:34'),
(232, 8, 199, 56, 'team', 'approved', '2026-09-17 16:23:34'),
(233, 8, 210, 57, 'team', 'approved', '2026-09-14 16:23:34'),
(234, 8, 36, 57, 'team', 'approved', '2026-09-16 16:23:34'),
(235, 8, 1, 57, 'team', 'approved', '2026-09-14 16:23:34'),
(236, 8, 148, 58, 'team', 'approved', '2026-09-12 16:23:34'),
(237, 8, 177, 58, 'team', 'approved', '2026-09-16 16:23:34'),
(238, 8, 238, 58, 'team', 'approved', '2026-09-12 16:23:34'),
(239, 8, 121, 59, 'team', 'approved', '2026-09-14 16:23:34'),
(240, 8, 144, 59, 'team', 'approved', '2026-09-17 16:23:34'),
(241, 8, 22, 59, 'team', 'approved', '2026-09-12 16:23:34'),
(242, 8, 167, 60, 'team', 'approved', '2026-09-12 16:23:34'),
(243, 8, 42, 60, 'team', 'approved', '2026-09-13 16:23:34'),
(244, 8, 156, 60, 'team', 'approved', '2026-09-14 16:23:34'),
(245, 8, 193, 61, 'team', 'approved', '2026-09-12 16:23:34'),
(246, 8, 224, 61, 'team', 'approved', '2026-09-13 16:23:34'),
(247, 9, 164, NULL, 'solo', 'approved', '2026-09-11 16:23:34'),
(248, 9, 92, NULL, 'solo', 'approved', '2026-09-12 16:23:34'),
(249, 9, 106, NULL, 'solo', 'approved', '2026-09-12 16:23:34'),
(250, 9, 79, NULL, 'solo', 'approved', '2026-09-12 16:23:34'),
(251, 9, 182, NULL, 'solo', 'approved', '2026-09-12 16:23:34'),
(252, 9, 237, NULL, 'solo', 'approved', '2026-09-16 16:23:34'),
(253, 9, 95, NULL, 'solo', 'approved', '2026-09-13 16:23:34'),
(254, 9, 129, NULL, 'solo', 'approved', '2026-09-11 16:23:34'),
(255, 9, 127, NULL, 'solo', 'approved', '2026-09-14 16:23:34'),
(256, 9, 23, NULL, 'solo', 'approved', '2026-09-11 16:23:34'),
(257, 9, 194, NULL, 'solo', 'approved', '2026-09-12 16:23:34'),
(258, 9, 121, NULL, 'solo', 'approved', '2026-09-14 16:23:34'),
(259, 9, 178, NULL, 'solo', 'approved', '2026-09-11 16:23:34'),
(260, 9, 217, NULL, 'solo', 'approved', '2026-09-17 16:23:34'),
(261, 9, 28, NULL, 'solo', 'approved', '2026-09-14 16:23:34'),
(262, 9, 184, NULL, 'solo', 'approved', '2026-09-16 16:23:34'),
(263, 9, 242, NULL, 'solo', 'approved', '2026-09-12 16:23:34'),
(264, 9, 50, NULL, 'solo', 'approved', '2026-09-16 16:23:34'),
(265, 9, 103, NULL, 'solo', 'approved', '2026-09-14 16:23:34'),
(266, 9, 9, NULL, 'solo', 'approved', '2026-09-16 16:23:34'),
(267, 10, 192, NULL, 'solo', 'approved', '2026-09-16 16:23:34'),
(268, 10, 97, NULL, 'solo', 'approved', '2026-09-15 16:23:34'),
(269, 10, 162, NULL, 'solo', 'approved', '2026-09-17 16:23:34'),
(270, 10, 67, NULL, 'solo', 'approved', '2026-09-13 16:23:34'),
(271, 10, 180, NULL, 'solo', 'approved', '2026-09-12 16:23:34'),
(272, 10, 38, NULL, 'solo', 'approved', '2026-09-11 16:23:34'),
(273, 10, 156, NULL, 'solo', 'approved', '2026-09-15 16:23:34'),
(274, 10, 234, NULL, 'solo', 'approved', '2026-09-11 16:23:34'),
(275, 10, 111, NULL, 'solo', 'approved', '2026-09-15 16:23:34'),
(276, 10, 30, NULL, 'solo', 'approved', '2026-09-16 16:23:34'),
(277, 10, 166, NULL, 'solo', 'approved', '2026-09-12 16:23:34'),
(278, 10, 182, NULL, 'solo', 'approved', '2026-09-16 16:23:34'),
(279, 10, 202, NULL, 'solo', 'approved', '2026-09-16 16:23:34'),
(280, 10, 189, NULL, 'solo', 'approved', '2026-09-11 16:23:34'),
(281, 10, 47, NULL, 'solo', 'approved', '2026-09-16 16:23:34'),
(282, 10, 206, NULL, 'solo', 'approved', '2026-09-17 16:23:34'),
(283, 10, 227, NULL, 'solo', 'approved', '2026-09-11 16:23:34'),
(284, 10, 13, NULL, 'solo', 'approved', '2026-09-17 16:23:34'),
(285, 10, 82, NULL, 'solo', 'approved', '2026-09-11 16:23:34'),
(286, 10, 26, NULL, 'solo', 'approved', '2026-09-15 16:23:34'),
(287, 10, 164, NULL, 'solo', 'approved', '2026-09-17 16:23:34'),
(288, 10, 151, NULL, 'solo', 'approved', '2026-09-17 16:23:34'),
(289, 10, 33, NULL, 'solo', 'approved', '2026-09-15 16:23:34'),
(290, 10, 125, NULL, 'solo', 'approved', '2026-09-12 16:23:34'),
(291, 11, 45, 62, 'team', 'approved', '2026-09-15 16:23:34'),
(292, 11, 157, 62, 'team', 'approved', '2026-09-13 16:23:34'),
(293, 11, 134, 62, 'team', 'approved', '2026-09-16 16:23:34'),
(294, 11, 177, 63, 'team', 'approved', '2026-09-11 16:23:34'),
(295, 11, 180, 63, 'team', 'approved', '2026-09-12 16:23:34'),
(296, 11, 112, 63, 'team', 'approved', '2026-09-13 16:23:34'),
(297, 11, 138, 64, 'team', 'approved', '2026-09-13 16:23:34'),
(298, 11, 90, 64, 'team', 'approved', '2026-09-17 16:23:34'),
(299, 11, 50, 64, 'team', 'approved', '2026-09-11 16:23:34'),
(300, 11, 226, 65, 'team', 'approved', '2026-09-11 16:23:34'),
(301, 11, 38, 65, 'team', 'approved', '2026-09-17 16:23:34'),
(302, 11, 210, 65, 'team', 'approved', '2026-09-13 16:23:34'),
(303, 11, 216, 66, 'team', 'approved', '2026-09-11 16:23:34'),
(304, 11, 218, 66, 'team', 'approved', '2026-09-12 16:23:34'),
(305, 11, 151, 66, 'team', 'approved', '2026-09-16 16:23:34'),
(306, 11, 15, 67, 'team', 'approved', '2026-09-12 16:23:34'),
(307, 11, 44, 67, 'team', 'approved', '2026-09-16 16:23:34'),
(308, 11, 239, 67, 'team', 'approved', '2026-09-12 16:23:34'),
(309, 11, 113, 68, 'team', 'approved', '2026-09-15 16:23:34'),
(310, 11, 33, 68, 'team', 'approved', '2026-09-17 16:23:34'),
(311, 11, 17, 68, 'team', 'approved', '2026-09-13 16:23:34'),
(312, 12, 190, NULL, 'solo', 'approved', '2026-09-17 16:23:34'),
(313, 12, 139, NULL, 'solo', 'approved', '2026-09-14 16:23:34'),
(314, 12, 36, NULL, 'solo', 'approved', '2026-09-15 16:23:34'),
(315, 12, 230, NULL, 'solo', 'approved', '2026-09-16 16:23:34'),
(316, 12, 112, NULL, 'solo', 'approved', '2026-09-11 16:23:34'),
(317, 12, 18, NULL, 'solo', 'approved', '2026-09-15 16:23:34'),
(318, 12, 23, NULL, 'solo', 'approved', '2026-09-14 16:23:34'),
(319, 12, 24, NULL, 'solo', 'approved', '2026-09-12 16:23:34'),
(320, 12, 32, NULL, 'solo', 'approved', '2026-09-16 16:23:34'),
(321, 12, 143, NULL, 'solo', 'approved', '2026-09-13 16:23:34'),
(322, 12, 11, NULL, 'solo', 'approved', '2026-09-15 16:23:34'),
(323, 12, 46, NULL, 'solo', 'approved', '2026-09-17 16:23:34'),
(324, 12, 8, NULL, 'solo', 'approved', '2026-09-15 16:23:34'),
(325, 12, 205, NULL, 'solo', 'approved', '2026-09-12 16:23:34'),
(326, 12, 212, NULL, 'solo', 'approved', '2026-09-14 16:23:34'),
(327, 12, 93, NULL, 'solo', 'approved', '2026-09-17 16:23:34'),
(328, 12, 146, NULL, 'solo', 'approved', '2026-09-11 16:23:34'),
(329, 12, 120, NULL, 'solo', 'approved', '2026-09-16 16:23:34'),
(330, 12, 1, NULL, 'solo', 'approved', '2026-09-14 16:23:34'),
(331, 12, 189, NULL, 'solo', 'approved', '2026-09-17 16:23:34'),
(332, 12, 222, NULL, 'solo', 'approved', '2026-09-12 16:23:34'),
(333, 12, 227, NULL, 'solo', 'approved', '2026-09-12 16:23:34'),
(334, 12, 94, NULL, 'solo', 'approved', '2026-09-15 16:23:34'),
(335, 12, 136, NULL, 'solo', 'approved', '2026-09-14 16:23:34'),
(336, 12, 74, NULL, 'solo', 'approved', '2026-09-12 16:23:34'),
(337, 12, 67, NULL, 'solo', 'approved', '2026-09-11 16:23:34'),
(338, 12, 17, NULL, 'solo', 'approved', '2026-09-16 16:23:34'),
(339, 12, 53, NULL, 'solo', 'approved', '2026-09-12 16:23:34'),
(340, 12, 226, NULL, 'solo', 'approved', '2026-09-11 16:23:34'),
(341, 12, 167, NULL, 'solo', 'approved', '2026-09-14 16:23:34'),
(342, 12, 19, NULL, 'solo', 'approved', '2026-09-11 16:23:34'),
(343, 12, 58, NULL, 'solo', 'approved', '2026-09-13 16:23:34'),
(344, 12, 224, NULL, 'solo', 'approved', '2026-09-12 16:23:34'),
(345, 12, 26, NULL, 'solo', 'approved', '2026-09-13 16:23:34');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `college_id` int(11) NOT NULL,
  `enrollment_no` varchar(50) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `semester` varchar(20) DEFAULT NULL,
  `id_card_image` varchar(255) NOT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `status` enum('pending','verified','rejected','active','inactive','blocked') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `verification_status` enum('pending','verified','rejected') NOT NULL DEFAULT 'pending',
  `account_status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `college_id`, `enrollment_no`, `name`, `email`, `password`, `phone`, `gender`, `semester`, `id_card_image`, `profile_photo`, `status`, `created_at`, `updated_at`, `verification_status`, `account_status`) VALUES
(1, 100, 'STU-100-1', 'Rahul Verma', 'rahul.v100@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000001', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'verified', 'active'),
(2, 100, 'STU-100-2', 'Priya Sharma', 'priya.s100@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000002', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(3, 100, 'STU-100-3', 'Amit Patel', 'amit.p100@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000003', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(4, 101, 'STU-101-1', 'Sneha Iyer', 'sneha.i101@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000004', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(5, 101, 'STU-101-2', 'Vikram Singh', 'vikram.s101@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000005', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(6, 101, 'STU-101-3', 'Anjali Roy', 'anjali.r101@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000006', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(7, 102, 'STU-102-1', 'Rohan Gupta', 'rohan.g102@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000007', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(8, 102, 'STU-102-2', 'Megha Desai', 'megha.d102@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000008', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(9, 102, 'STU-102-3', 'Kunal Shah', 'kunal.s102@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000009', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(10, 103, 'STU-103-1', 'Deepika Rao', 'deepika.r103@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000010', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(11, 103, 'STU-103-2', 'Suresh Kumar', 'suresh.k103@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000011', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(12, 103, 'STU-103-3', 'Pooja Nair', 'pooja.n103@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000012', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(13, 104, 'STU-104-1', 'Arjun Reddy', 'arjun.r104@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000013', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(14, 104, 'STU-104-2', 'Divya Joshi', 'divya.j104@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000014', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(15, 104, 'STU-104-3', 'Manoj Yadav', 'manoj.y104@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000015', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(16, 105, 'STU-105-1', 'Shweta Mane', 'shweta.m105@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000016', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(17, 105, 'STU-105-2', 'Harish Nair', 'harish.n105@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000017', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(18, 105, 'STU-105-3', 'Anita Das', 'anita.d105@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000018', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(19, 106, 'STU-106-1', 'Vijay Malya', 'vijay.m106@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000019', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(20, 106, 'STU-106-2', 'Kavita Singh', 'kavita.s106@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000020', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(21, 107, 'STU-107-1', 'Sanjay Dutt', 'sanjay.d107@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000021', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(22, 107, 'STU-107-2', 'Neha Kakar', 'neha.k107@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000022', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(23, 107, 'STU-107-3', 'Manish Malhotra', 'manish.m107@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000023', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(24, 108, 'STU-108-1', 'Sonakshi Sinha', 'sonakshi.s108@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000024', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(25, 108, 'STU-108-2', 'Varun Dhawan', 'varun.d108@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000025', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(26, 108, 'STU-108-3', 'Alia Bhatt', 'alia.b108@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000026', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(27, 109, 'STU-109-1', 'Ranbir Kapoor', 'ranbir.k109@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000027', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(28, 109, 'STU-109-2', 'Shraddha Kapoor', 'shraddha.k109@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000028', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(29, 109, 'STU-109-3', 'Shahid Kapoor', 'shahid.k109@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000029', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(30, 110, 'STU-110-1', 'Kiara Advani', 'kiara.a110@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000030', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(31, 110, 'STU-110-2', 'Sidharth Malhotra', 'sidharth.m110@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000031', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(32, 110, 'STU-110-3', 'Katrina Kaif', 'katrina.k110@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000032', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(33, 111, 'STU-111-1', 'Vicky Kaushal', 'vicky.k111@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000033', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(34, 111, 'STU-111-2', 'Kriti Sanon', 'kriti.s111@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000034', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(35, 111, 'STU-111-3', 'Kartik Aaryan', 'kartik.a111@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000035', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(36, 112, 'STU-112-1', 'Ananya Panday', 'ananya.p112@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000036', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(37, 112, 'STU-112-2', 'Ishaan Khatter', 'ishaan.k112@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000037', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(38, 112, 'STU-112-3', 'Janhvi Kapoor', 'janhvi.k112@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000038', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(39, 113, 'STU-113-1', 'Tiger Shroff', 'tiger.s113@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000039', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(40, 113, 'STU-113-2', 'Disha Patani', 'disha.p113@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000040', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(41, 114, 'STU-114-1', 'Ayushmann Khurrana', 'ayushmann.k114@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000041', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(42, 114, 'STU-114-2', 'Bhumi Pednekar', 'bhumi.p114@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000042', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(43, 114, 'STU-114-3', 'Rajkummar Rao', 'rajkummar.r114@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000043', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(44, 115, 'STU-115-1', 'Patralekha Paul', 'patralekha.p115@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000044', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(45, 115, 'STU-115-2', 'Nawazuddin Siddiqui', 'nawazuddin.s115@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000045', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(46, 115, 'STU-115-3', 'Pankaj Tripathi', 'pankaj.t115@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000046', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(47, 116, 'STU-116-1', 'Manoj Bajpayee', 'manoj.b116@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000047', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(48, 116, 'STU-116-2', 'Radhika Apte', 'radhika.a116@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000048', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(49, 116, 'STU-116-3', 'Taapsee Pannu', 'taapsee.p116@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000049', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(50, 117, 'STU-117-1', 'Swara Bhaskar', 'swara.b117@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000050', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(51, 117, 'STU-117-2', 'Richa Chadha', 'richa.c117@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000051', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(52, 117, 'STU-117-3', 'Ali Fazal', 'ali.f117@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000052', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(53, 118, 'STU-118-1', 'Pratik Gandhi', 'pratik.g118@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000053', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(54, 118, 'STU-118-2', 'Shreya Dhanwanthary', 'shreya.d118@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000054', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(55, 118, 'STU-118-3', 'Divyenndu Sharma', 'divyenndu.s118@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000055', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(56, 119, 'STU-119-1', 'Vikrant Massey', 'vikrant.m119@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000056', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(57, 119, 'STU-119-2', 'Sheetal Thakur', 'sheetal.t119@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000057', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(58, 119, 'STU-119-3', 'Abhishek Banerjee', 'abhishek.b119@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000058', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(59, 120, 'STU-120-1', 'Jaideep Ahlawat', 'jaideep.a120@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000059', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(60, 120, 'STU-120-2', 'Ishwak Singh', 'ishwaksingh120@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000060', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(61, 121, 'STU-121-1', 'Jitendra Kumar', 'jitendra.k121@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000061', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(62, 121, 'STU-121-2', 'Neena Gupta', 'neena.g121@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000062', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(63, 121, 'STU-121-3', 'Gajraj Rao', 'gajraj.r121@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000063', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(64, 122, 'STU-122-1', 'Shefali Shah', 'shefali.s122@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000064', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(65, 122, 'STU-122-2', 'Rasika Dugal', 'rasika.d122@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000065', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(66, 122, 'STU-122-3', 'Rajesh Tailang', 'rajesh.t122@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000066', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(67, 123, 'STU-123-1', 'Kulbhushan Kharbanda', 'kulbhushan.k123@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000067', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(68, 123, 'STU-123-2', 'Pooja Bhatt', 'pooja.b123@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000068', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(69, 123, 'STU-123-3', 'Rahul Bose', 'rahul.b123@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000069', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(70, 124, 'STU-124-1', 'Rimi Sen', 'rimi.s124@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000070', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(71, 124, 'STU-124-2', 'Zohra Sehgal', 'zohra.s124@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000071', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(72, 124, 'STU-124-3', 'Paresh Rawal', 'paresh.r124@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000072', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(73, 125, 'STU-125-1', 'Akshay Kumar', 'akshay.k125@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000073', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(74, 125, 'STU-125-2', 'Suniel Shetty', 'suniel.s125@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000074', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(75, 125, 'STU-125-3', 'Bobby Deol', 'bobby.d125@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000075', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(76, 126, 'STU-126-1', 'Sunny Deol', 'sunny.d126@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000076', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(77, 126, 'STU-126-2', 'Sanjay Kapoor', 'sanjay.k126@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000077', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(78, 126, 'STU-126-3', 'Anil Kapoor', 'anil.k126@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000078', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(79, 127, 'STU-127-1', 'Jackie Shroff', 'jackie.s127@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000079', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(80, 127, 'STU-127-2', 'Govinda Ahuja', 'govinda.a127@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000080', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(81, 128, 'STU-128-1', 'Madhuri Dixit', 'madhuri.d128@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000081', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(82, 128, 'STU-128-2', 'Juhi Chawla', 'juhi.c128@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000082', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(83, 128, 'STU-128-3', 'Raveena Tandon', 'raveena.t128@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000083', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(84, 129, 'STU-129-1', 'Karisma Kapoor', 'karisma.k129@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000084', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(85, 129, 'STU-129-2', 'Tabu Hashmi', 'tabu.h129@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000085', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(86, 129, 'STU-129-3', 'Urmila Matondkar', 'urmila.m129@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000086', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(87, 130, 'STU-130-1', 'Shilpa Shetty', 'shilpa.s130@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000087', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(88, 130, 'STU-130-2', 'Sonali Bendre', 'sonali.b130@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000088', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(89, 130, 'STU-130-3', 'Pooja Batra', 'pooja.b130@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000089', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(90, 131, 'STU-131-1', 'Mahima Chaudhry', 'mahima.c131@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000090', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(91, 131, 'STU-131-2', 'Gayatri Joshi', 'gayatri.j131@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000091', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(92, 131, 'STU-131-3', 'Gracy Singh', 'gracy.s131@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000092', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(93, 132, 'STU-132-1', 'Amrita Rao', 'amrita.r132@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000093', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(94, 132, 'STU-132-2', 'Esha Deol', 'esha.d132@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000094', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(95, 132, 'STU-132-3', 'Soha Ali Khan', 'soha.k132@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000095', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(96, 133, 'STU-133-1', 'Dia Mirza', 'dia.m133@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000096', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(97, 133, 'STU-133-2', 'Rimi Sen', 'rimi.s133@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000097', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(98, 133, 'STU-133-3', 'Sameera Reddy', 'sameera.r133@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000098', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(99, 134, 'STU-134-1', 'Tanishaa Mukerji', 'tanishaa.m134@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000099', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(100, 134, 'STU-134-2', 'Kim Sharma', 'kim.s134@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000100', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(101, 135, 'STU-135-1', 'Preity Zinta', 'preity.z135@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000101', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(102, 135, 'STU-135-2', 'Gracy Singh', 'gracy.s135@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000102', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(103, 135, 'STU-135-3', 'Rani Mukerji', 'rani.m135@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000103', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(104, 136, 'STU-136-1', 'Ameesha Patel', 'ameesha.p136@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000104', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(105, 136, 'STU-136-2', 'Bipasha Basu', 'bipasha.b136@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000105', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(106, 136, 'STU-136-3', 'Lara Dutta', 'lara.d136@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000106', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(107, 137, 'STU-137-1', 'Priyanka Chopra', 'priyanka.c137@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000107', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(108, 137, 'STU-137-2', 'Kareena Kapoor', 'kareena.k137@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000108', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(109, 137, 'STU-137-3', 'Amrita Arora', 'amrita.a137@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000109', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(110, 138, 'STU-138-1', 'Malaika Arora', 'malaika.a138@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000110', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(111, 138, 'STU-138-2', 'Zayed Khan', 'zayed.k138@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000111', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(112, 138, 'STU-138-3', 'Fardeen Khan', 'fardeen.k138@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000112', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(113, 139, 'STU-139-1', 'Aftab Shivdasani', 'aftab.s139@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000113', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(114, 139, 'STU-139-2', 'Riteish Deshmukh', 'riteish.d139@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000114', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(115, 139, 'STU-139-3', 'Genelia Dsouza', 'genelia.d139@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000115', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(116, 140, 'STU-140-1', 'John Abraham', 'john.a140@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000116', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(117, 140, 'STU-140-2', 'Dino Morea', 'dino.m140@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000117', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(118, 140, 'STU-140-3', 'Rahul Khanna', 'rahul.k140@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000118', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(119, 141, 'STU-141-1', 'Sanjay Suri', 'sanjay.s141@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000119', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(120, 141, 'STU-141-2', 'Purab Kohli', 'purab.k141@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000120', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(121, 142, 'STU-142-1', 'Sikander Kher', 'sikander.k142@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000121', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(122, 142, 'STU-142-2', 'Kunal Kapoor', 'kunal.k142@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000122', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(123, 142, 'STU-142-3', 'Abhay Deol', 'abhay.d142@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000123', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(124, 143, 'STU-143-1', 'Emraan Hashmi', 'emraan.h143@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000124', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(125, 143, 'STU-143-2', 'Randeep Hooda', 'randeep.h143@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000125', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(126, 143, 'STU-143-3', 'Shreyas Talpade', 'shreyas.t143@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000126', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(127, 144, 'STU-144-1', 'Sonu Sood', 'sonu.s144@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000127', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(128, 144, 'STU-144-2', 'Sharman Joshi', 'sharman.j144@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000128', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(129, 144, 'STU-144-3', 'R Madhavan', 'madhavan.r144@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000129', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(130, 145, 'STU-145-1', 'Siddharth Narayan', 'siddharth.n145@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000130', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(131, 145, 'STU-145-2', 'Neil Nitin Mukesh', 'neil.m145@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000131', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(132, 145, 'STU-145-3', 'Prateik Babbar', 'prateik.b145@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000132', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(133, 146, 'STU-146-1', 'Ranveer Singh', 'ranveer.s146@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000133', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(134, 146, 'STU-146-2', 'Sushant Singh', 'sushant.s146@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000134', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(135, 146, 'STU-146-3', 'Arjun Kapoor', 'arjun.k146@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000135', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(136, 147, 'STU-147-1', 'Varun Sharma', 'varun.s147@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000136', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(137, 147, 'STU-147-2', 'Pulkit Samrat', 'pulkit.s147@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000137', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(138, 147, 'STU-147-3', 'Ali Zafar', 'ali.z147@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000138', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(139, 148, 'STU-148-1', 'Aditya Roy Kapur', 'aditya.r148@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000139', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(140, 148, 'STU-148-2', 'Siddharth Malhotra', 'siddharth.m148@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000140', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(141, 149, 'STU-149-1', 'Siddhant Chaturvedi', 'siddhant.c149@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000141', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(142, 149, 'STU-149-2', 'Ishaan Khatter', 'ishaan.k149@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000142', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(143, 149, 'STU-149-3', 'Meezaan Jafri', 'meezaan.j149@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000143', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(144, 150, 'STU-150-1', 'Sunny Kaushal', 'sunny.k150@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000144', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(145, 150, 'STU-150-2', 'Ahan Shetty', 'ahan.s150@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000145', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(146, 150, 'STU-150-3', 'Abhimanyu Dassani', 'abhimanyu.d150@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000146', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(147, 151, 'STU-151-1', 'Lakshya Lalwani', 'lakshya.l151@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000147', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(148, 151, 'STU-151-2', 'Sharvari Wagh', 'sharvari.w151@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000148', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(149, 151, 'STU-151-3', 'Alaya Furniturewala', 'alaya.f151@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000149', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(150, 152, 'STU-152-1', 'Tara Sutaria', 'tara.s152@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000150', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(151, 152, 'STU-152-2', 'Ananya Panday', 'ananya.p152@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000151', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(152, 152, 'STU-152-3', 'Janhvi Kapoor', 'janhvi.k152@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000152', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(153, 153, 'STU-153-1', 'Sara Ali Khan', 'sara.k153@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000153', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(154, 153, 'STU-153-2', 'Manushi Chhillar', 'manushi.c153@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000154', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(155, 153, 'STU-153-3', 'Kriti Sanon', 'kriti.s153@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000155', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(156, 154, 'STU-154-1', 'Nushrat Bharuccha', 'nushrat.b154@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000156', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(157, 154, 'STU-154-2', 'Yami Gautam', 'yami.g154@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000157', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(158, 154, 'STU-154-3', 'Huma Qureshi', 'huma.q154@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000158', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(159, 155, 'STU-155-1', 'Richa Chadha', 'richa.c155@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000159', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(160, 155, 'STU-155-2', 'Swara Bhaskar', 'swara.b155@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000160', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(161, 156, 'STU-156-1', 'Radhika Madan', 'radhika.m156@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000161', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(162, 156, 'STU-156-2', 'Sanya Malhotra', 'sanya.m156@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000162', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(163, 156, 'STU-156-3', 'Fatima Sana Shaikh', 'fatima.s156@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000163', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(164, 157, 'STU-157-1', 'Zaira Wasim', 'zaira.w157@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000164', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(165, 157, 'STU-157-2', 'Meher Vij', 'meher.v157@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000165', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(166, 157, 'STU-157-3', 'Rajshri Deshpande', 'rajshri.d157@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000166', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(167, 158, 'STU-158-1', 'Shefali Shah', 'shefali.s158@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000167', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(168, 158, 'STU-158-2', 'Tisca Chopra', 'tisca.c158@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000168', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(169, 158, 'STU-158-3', 'Divya Dutta', 'divya.d158@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000169', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(170, 159, 'STU-159-1', 'Sandhya Mridul', 'sandhya.m159@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000170', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(171, 159, 'STU-159-2', 'Sonali Kulkarni', 'sonali.k159@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000171', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(172, 159, 'STU-159-3', 'Kiran Kher', 'kiran.k159@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000172', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(173, 160, 'STU-160-1', 'Supriya Pathak', 'supriya.p160@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000173', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(174, 160, 'STU-160-2', 'Ratna Pathak', 'ratna.p160@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000174', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(175, 160, 'STU-160-3', 'Ila Arun', 'ila.a160@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000175', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(176, 161, 'STU-161-1', 'Seema Pahwa', 'seema.p161@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000176', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(177, 161, 'STU-161-2', 'Himani Shivpuri', 'himani.s161@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000177', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(178, 161, 'STU-161-3', 'Reema Lagoo', 'reema.l161@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000178', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(179, 162, 'STU-162-1', 'Farida Jalal', 'farida.j162@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000179', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(180, 162, 'STU-162-2', 'Rohini Hattangadi', 'rohini.h162@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000180', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(181, 163, 'STU-163-1', 'Rita Bhaduri', 'rita.b163@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000181', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(182, 163, 'STU-163-2', 'Bindu Desai', 'bindu.d163@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000182', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(183, 163, 'STU-163-3', 'Shashikala Joshi', 'shashikala.j163@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000183', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active');
INSERT INTO `students` (`student_id`, `college_id`, `enrollment_no`, `name`, `email`, `password`, `phone`, `gender`, `semester`, `id_card_image`, `profile_photo`, `status`, `created_at`, `updated_at`, `verification_status`, `account_status`) VALUES
(184, 164, 'STU-164-1', 'Nadira Begum', 'nadira.b164@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000184', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(185, 164, 'STU-164-2', 'Lalita Pawar', 'lalita.p164@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000185', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(186, 164, 'STU-164-3', 'Durga Khote', 'durga.k164@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000186', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(187, 165, 'STU-165-1', 'Achala Sachdev', 'achala.s165@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000187', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(188, 165, 'STU-165-2', 'Leela Chitnis', 'leela.c165@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000188', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(189, 165, 'STU-165-3', 'Sulochana Latkar', 'sulochana.l165@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000189', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(190, 166, 'STU-166-1', 'Nirupa Roy', 'nirupa.r166@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000190', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(191, 166, 'STU-166-2', 'Kamini Kaushal', 'kamini.k166@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000191', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(192, 166, 'STU-166-3', 'Suraiya Akhtar', 'suraiya.a166@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000192', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(193, 167, 'STU-167-1', 'Noor Jehan', 'noor.j167@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000193', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(194, 167, 'STU-167-2', 'Shamshad Begum', 'shamshad.b167@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000194', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(195, 167, 'STU-167-3', 'Zohra Bai', 'zohra.b167@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000195', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(196, 168, 'STU-168-1', 'Geeta Dutt', 'geeta.d168@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000196', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(197, 168, 'STU-168-2', 'Lata Mangeshkar', 'lata.m168@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000197', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(198, 168, 'STU-168-3', 'Asha Bhosle', 'asha.b168@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000198', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(199, 169, 'STU-169-1', 'Usha Mangeshkar', 'usha.m169@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000199', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(200, 169, 'STU-169-2', 'Suman Kalyanpur', 'suman.k169@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000200', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(201, 170, 'STU-170-1', 'Hemant Kumar', 'hemant.k170@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000201', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(202, 170, 'STU-170-2', 'Manna Dey', 'manna.d170@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000202', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(203, 170, 'STU-170-3', 'Talat Mahmood', 'talat.m170@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000203', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(204, 171, 'STU-171-1', 'Mukesh Chand', 'mukesh.c171@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000204', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(205, 171, 'STU-171-2', 'Mohammed Rafi', 'mohammed.r171@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000205', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(206, 171, 'STU-171-3', 'Kishore Kumar', 'kishore.k171@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000206', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(207, 172, 'STU-172-1', 'Mahendra Kapoor', 'mahendra.k172@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000207', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(208, 172, 'STU-172-2', 'Bhupen Hazarika', 'bhupen.h172@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000208', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(209, 172, 'STU-172-3', 'Pankaj Udhas', 'pankaj.u172@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000209', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(210, 173, 'STU-173-1', 'Jagjit Singh', 'jagjit.s173@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000210', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(211, 173, 'STU-173-2', 'Anup Jalota', 'anup.j173@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000211', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(212, 173, 'STU-173-3', 'Hariharan Ramaswamy', 'hariharan.r173@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000212', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(213, 174, 'STU-174-1', 'Suresh Wadkar', 'suresh.w174@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000213', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(214, 174, 'STU-174-2', 'Shabbir Kumar', 'shabbir.k174@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000214', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(215, 174, 'STU-174-3', 'Mohammed Aziz', 'mohammed.a174@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000215', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(216, 175, 'STU-175-1', 'Vinod Rathod', 'vinod.r175@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000216', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(217, 175, 'STU-175-2', 'Kumar Sanu', 'kumar.s175@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000217', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(218, 175, 'STU-175-3', 'Udit Narayan', 'udit.n175@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000218', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(219, 176, 'STU-176-1', 'Abhijeet Bhattacharya', 'abhijeet.b176@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000219', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(220, 176, 'STU-176-2', 'Sonu Nigam', 'sonu.n176@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000220', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(221, 176, 'STU-176-3', 'Shaan Mukherjee', 'shaan.m176@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000221', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(222, 177, 'STU-177-1', 'KK Krishnakumar', 'kk.k177@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000222', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(223, 177, 'STU-177-2', ' Sukhwinder Singh', 'sukhwinder.s177@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000223', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(224, 177, 'STU-177-3', 'Kailash Kher', 'kailash.k177@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000224', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(225, 178, 'STU-178-1', 'Mohit Chauhan', 'mohit.c178@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000225', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(226, 178, 'STU-178-2', 'Arijit Singh', 'arijit.s178@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000226', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(227, 178, 'STU-178-3', 'Atif Aslam', 'atif.a178@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000227', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(228, 179, 'STU-179-1', 'Rahat Fateh Ali', 'rahat.f179@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000228', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(229, 179, 'STU-179-2', 'Armaan Malik', 'armaan.m179@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000229', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(230, 179, 'STU-179-3', 'Darshan Raval', 'darshan.r179@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000230', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(231, 100, 'STU-100-4', 'Guru Randhawa', 'guru.r100@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000231', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(232, 101, 'STU-101-4', 'Badshah Singh', 'badshah.s101@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000232', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(233, 102, 'STU-102-4', 'Yo Yo Honey Singh', 'honey.s102@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000233', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(234, 103, 'STU-103-4', 'Raftaar Singh', 'raftaar.s103@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000234', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(235, 104, 'STU-104-4', 'Tony Kakkar', 'tony.k104@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000235', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(236, 105, 'STU-105-4', 'Akhil Sachdeva', 'akhil.s105@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000236', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(237, 106, 'STU-106-4', 'Jubin Nautiyal', 'jubin.n106@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000237', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(238, 107, 'STU-107-4', 'B Praak', 'bpraak.107@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000238', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(239, 108, 'STU-108-4', 'Stebin Ben', 'stebin.b108@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000239', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(240, 109, 'STU-109-4', 'Akhil Pasreja', 'akhil.p109@example.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '9800000240', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-09-17 12:55:02', 'pending', 'active'),
(241, 138, 'STU-400-1', 'User', 'user@gmail.com', '$2y$10$GCRbcjfh6ukjTk0T3TRbs.Um3G/v9fyWzXXRMw4.U4oapBQR0yHuK', '1234567891', 'male', 'Semester 5', 'pending_id.png', 'student_6aabd8ad01cd0.png', 'pending', '2026-09-17 10:18:19', '2026-09-17 12:55:02', 'pending', 'active'),
(242, 138, 'STU-400-2', 'User2', 'user2@gmail.com', '$2y$10$9yiXarAaOWGCCiU7vQH7yu9pAqm1i0S319UbhGhriUo9A2NbxKE.6', '1234567892', 'female', NULL, 'pending_id.png', 'student_6aabe952efc6a.jpg', 'pending', '2026-09-17 13:21:23', '2026-09-17 13:21:23', 'pending', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `team_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `leader_id` int(11) NOT NULL,
  `team_name` varchar(100) NOT NULL,
  `team_code` varchar(30) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`team_id`, `event_id`, `leader_id`, `team_name`, `team_code`, `created_at`) VALUES
(1, 1, 5, 'Code Warriors', 'TEAM-101', '2026-08-07 07:30:25'),
(2, 3, 6, 'Rhythm Riot', 'TEAM-103', '2026-08-07 07:30:25'),
(3, 4, 7, 'Apex Innovators', 'TEAM-104', '2026-08-07 07:30:25'),
(4, 11, 242, 'Parul AI', 'TEAM-77FE76', '2026-09-17 15:33:17'),
(5, 1, 141, 'Cyber Knights 44', 'TM-E7446', '2026-09-17 16:23:33'),
(6, 1, 122, 'Runtime Terror 15', 'TM-2F7EF', '2026-09-17 16:23:33'),
(7, 1, 25, 'Tech Titans 83', 'TM-2E740', '2026-09-17 16:23:33'),
(8, 1, 211, 'Data Pirates 87', 'TM-0CE7A', '2026-09-17 16:23:33'),
(9, 1, 82, 'Tech Titans 43', 'TM-5BDFD', '2026-09-17 16:23:33'),
(10, 1, 3, 'Data Pirates 99', 'TM-FE4E5', '2026-09-17 16:23:33'),
(11, 1, 78, 'The Innovators 76', 'TM-56F06', '2026-09-17 16:23:33'),
(12, 1, 179, 'Runtime Terror 61', 'TM-2AC6D', '2026-09-17 16:23:33'),
(13, 1, 183, 'Quantum Squad 60', 'TM-C38E7', '2026-09-17 16:23:33'),
(14, 1, 177, 'Byte Me 16', 'TM-BDCBF', '2026-09-17 16:23:33'),
(15, 1, 218, 'Syntax Error 87', 'TM-6B4CF', '2026-09-17 16:23:33'),
(16, 1, 86, 'Quantum Squad 79', 'TM-5C97E', '2026-09-17 16:23:33'),
(17, 3, 64, 'Apex Legends 50', 'TM-36BAD', '2026-09-17 16:23:33'),
(18, 3, 141, 'Byte Me 50', 'TM-E2753', '2026-09-17 16:23:33'),
(19, 3, 173, 'Cyber Knights 39', 'TM-09B29', '2026-09-17 16:23:33'),
(20, 3, 187, 'Quantum Squad 23', 'TM-84F56', '2026-09-17 16:23:33'),
(21, 3, 159, 'The Innovators 26', 'TM-B3CBD', '2026-09-17 16:23:33'),
(22, 3, 106, 'Syntax Error 77', 'TM-97755', '2026-09-17 16:23:33'),
(23, 3, 103, 'Tech Titans 64', 'TM-C0F1B', '2026-09-17 16:23:33'),
(24, 3, 21, 'Byte Me 10', 'TM-FDB51', '2026-09-17 16:23:33'),
(25, 3, 208, 'Neon Coders 43', 'TM-529E2', '2026-09-17 16:23:33'),
(26, 3, 97, 'The Innovators 68', 'TM-FD50F', '2026-09-17 16:23:33'),
(27, 4, 68, 'Data Pirates 11', 'TM-3F926', '2026-09-17 16:23:33'),
(28, 4, 170, 'Apex Legends 17', 'TM-E06C0', '2026-09-17 16:23:33'),
(29, 4, 216, 'Tech Titans 15', 'TM-1CEAF', '2026-09-17 16:23:33'),
(30, 4, 86, 'Tech Titans 15', 'TM-A68AE', '2026-09-17 16:23:33'),
(31, 4, 180, 'Runtime Terror 32', 'TM-05E96', '2026-09-17 16:23:33'),
(32, 4, 62, 'Tech Titans 16', 'TM-92683', '2026-09-17 16:23:33'),
(33, 4, 43, 'Data Pirates 30', 'TM-2B47A', '2026-09-17 16:23:33'),
(34, 4, 202, 'Runtime Terror 34', 'TM-48513', '2026-09-17 16:23:33'),
(35, 4, 114, 'Apex Legends 34', 'TM-48CDB', '2026-09-17 16:23:33'),
(36, 4, 8, 'Cyber Knights 42', 'TM-7B52D', '2026-09-17 16:23:33'),
(37, 4, 232, 'Cyber Knights 43', 'TM-08CDC', '2026-09-17 16:23:33'),
(38, 4, 157, 'The Innovators 21', 'TM-5518F', '2026-09-17 16:23:33'),
(39, 5, 43, 'Tech Titans 88', 'TM-D3554', '2026-09-17 16:23:33'),
(40, 5, 62, 'Runtime Terror 83', 'TM-A67E6', '2026-09-17 16:23:33'),
(41, 5, 155, 'Neon Coders 53', 'TM-AC052', '2026-09-17 16:23:33'),
(42, 5, 182, 'Byte Me 68', 'TM-A1443', '2026-09-17 16:23:33'),
(43, 5, 33, 'Runtime Terror 58', 'TM-774B6', '2026-09-17 16:23:33'),
(44, 5, 70, 'Quantum Squad 88', 'TM-61ABD', '2026-09-17 16:23:33'),
(45, 5, 7, 'Syntax Error 86', 'TM-786BC', '2026-09-17 16:23:33'),
(46, 5, 165, 'Cyber Knights 86', 'TM-EDC62', '2026-09-17 16:23:33'),
(47, 5, 76, 'Data Pirates 57', 'TM-E04F8', '2026-09-17 16:23:33'),
(48, 5, 132, 'Runtime Terror 57', 'TM-0EBF9', '2026-09-17 16:23:33'),
(49, 5, 77, 'The Innovators 71', 'TM-9BB72', '2026-09-17 16:23:33'),
(50, 7, 96, 'Runtime Terror 71', 'TM-C48AB', '2026-09-17 16:23:34'),
(51, 7, 193, 'Tech Titans 58', 'TM-0C456', '2026-09-17 16:23:34'),
(52, 7, 216, 'Syntax Error 79', 'TM-D7D0F', '2026-09-17 16:23:34'),
(53, 7, 145, 'Quantum Squad 81', 'TM-FB058', '2026-09-17 16:23:34'),
(54, 7, 75, 'Runtime Terror 84', 'TM-74104', '2026-09-17 16:23:34'),
(55, 7, 76, 'Tech Titans 33', 'TM-EF66F', '2026-09-17 16:23:34'),
(56, 8, 23, 'Quantum Squad 97', 'TM-CD0C3', '2026-09-17 16:23:34'),
(57, 8, 210, 'Apex Legends 84', 'TM-54856', '2026-09-17 16:23:34'),
(58, 8, 148, 'Runtime Terror 15', 'TM-67BBD', '2026-09-17 16:23:34'),
(59, 8, 121, 'Neon Coders 88', 'TM-8B166', '2026-09-17 16:23:34'),
(60, 8, 167, 'Byte Me 36', 'TM-4D482', '2026-09-17 16:23:34'),
(61, 8, 193, 'Cyber Knights 67', 'TM-10B37', '2026-09-17 16:23:34'),
(62, 11, 45, 'Byte Me 33', 'TM-8622E', '2026-09-17 16:23:34'),
(63, 11, 177, 'Byte Me 77', 'TM-D8FD1', '2026-09-17 16:23:34'),
(64, 11, 138, 'Apex Legends 94', 'TM-97223', '2026-09-17 16:23:34'),
(65, 11, 226, 'Runtime Terror 47', 'TM-F2DE6', '2026-09-17 16:23:34'),
(66, 11, 216, 'Cyber Knights 32', 'TM-E724D', '2026-09-17 16:23:34'),
(67, 11, 15, 'Byte Me 80', 'TM-1BFF4', '2026-09-17 16:23:34'),
(68, 11, 113, 'Quantum Squad 91', 'TM-0FA4F', '2026-09-17 16:23:34');

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `team_member_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`team_member_id`, `team_id`, `student_id`) VALUES
(1, 1, 5),
(2, 1, 10),
(3, 1, 15),
(4, 2, 6),
(5, 2, 12),
(6, 3, 7),
(7, 3, 14),
(8, 3, 21),
(9, 2, 223),
(10, 4, 242),
(11, 5, 141),
(12, 5, 215),
(13, 5, 26),
(14, 6, 122),
(15, 6, 227),
(16, 6, 187),
(17, 7, 25),
(18, 7, 198),
(19, 7, 114),
(20, 8, 211),
(21, 8, 51),
(22, 8, 31),
(23, 9, 82),
(24, 9, 65),
(25, 9, 93),
(26, 10, 3),
(27, 10, 104),
(28, 10, 36),
(29, 11, 78),
(30, 11, 138),
(31, 11, 2),
(32, 12, 179),
(33, 12, 224),
(34, 12, 242),
(35, 13, 183),
(36, 13, 221),
(37, 13, 144),
(38, 14, 177),
(39, 14, 201),
(40, 14, 11),
(41, 15, 218),
(42, 15, 89),
(43, 15, 127),
(44, 16, 86),
(45, 16, 113),
(46, 17, 64),
(47, 17, 86),
(48, 17, 110),
(49, 18, 141),
(50, 18, 9),
(51, 18, 28),
(52, 19, 173),
(53, 19, 88),
(54, 19, 136),
(55, 20, 187),
(56, 20, 190),
(57, 20, 228),
(58, 21, 159),
(59, 21, 99),
(60, 21, 210),
(61, 22, 106),
(62, 22, 175),
(63, 22, 16),
(64, 23, 103),
(65, 23, 92),
(66, 23, 151),
(67, 24, 21),
(68, 24, 236),
(69, 24, 43),
(70, 25, 208),
(71, 25, 114),
(72, 25, 1),
(73, 26, 97),
(74, 26, 240),
(75, 27, 68),
(76, 27, 183),
(77, 27, 184),
(78, 28, 170),
(79, 28, 32),
(80, 28, 29),
(81, 29, 216),
(82, 29, 224),
(83, 29, 145),
(84, 30, 86),
(85, 30, 91),
(86, 30, 198),
(87, 31, 180),
(88, 31, 104),
(89, 31, 24),
(90, 32, 62),
(91, 32, 119),
(92, 32, 130),
(93, 33, 43),
(94, 33, 113),
(95, 33, 159),
(96, 34, 202),
(97, 34, 90),
(98, 34, 38),
(99, 35, 114),
(100, 35, 134),
(101, 35, 46),
(102, 36, 8),
(103, 36, 67),
(104, 36, 36),
(105, 37, 232),
(106, 37, 80),
(107, 37, 137),
(108, 38, 157),
(109, 38, 166),
(110, 39, 43),
(111, 39, 59),
(112, 39, 196),
(113, 40, 62),
(114, 40, 110),
(115, 40, 211),
(116, 41, 155),
(117, 41, 161),
(118, 41, 229),
(119, 42, 182),
(120, 42, 19),
(121, 42, 191),
(122, 43, 33),
(123, 43, 173),
(124, 43, 241),
(125, 44, 70),
(126, 44, 150),
(127, 44, 186),
(128, 45, 7),
(129, 45, 133),
(130, 45, 45),
(131, 46, 165),
(132, 46, 169),
(133, 46, 239),
(134, 47, 76),
(135, 47, 171),
(136, 47, 237),
(137, 48, 132),
(138, 48, 78),
(139, 48, 67),
(140, 49, 77),
(141, 49, 159),
(142, 49, 215),
(143, 50, 96),
(144, 50, 130),
(145, 50, 84),
(146, 51, 193),
(147, 51, 86),
(148, 51, 33),
(149, 52, 216),
(150, 52, 220),
(151, 52, 179),
(152, 53, 145),
(153, 53, 119),
(154, 53, 135),
(155, 54, 75),
(156, 54, 47),
(157, 54, 207),
(158, 55, 76),
(159, 55, 213),
(160, 55, 204),
(161, 56, 23),
(162, 56, 223),
(163, 56, 199),
(164, 57, 210),
(165, 57, 36),
(166, 57, 1),
(167, 58, 148),
(168, 58, 177),
(169, 58, 238),
(170, 59, 121),
(171, 59, 144),
(172, 59, 22),
(173, 60, 167),
(174, 60, 42),
(175, 60, 156),
(176, 61, 193),
(177, 61, 224),
(178, 62, 45),
(179, 62, 157),
(180, 62, 134),
(181, 63, 177),
(182, 63, 180),
(183, 63, 112),
(184, 64, 138),
(185, 64, 90),
(186, 64, 50),
(187, 65, 226),
(188, 65, 38),
(189, 65, 210),
(190, 66, 216),
(191, 66, 218),
(192, 66, 151),
(193, 67, 15),
(194, 67, 44),
(195, 67, 239),
(196, 68, 113),
(197, 68, 33),
(198, 68, 17);

-- --------------------------------------------------------

--
-- Table structure for table `universities`
--

CREATE TABLE `universities` (
  `university_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `short_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `universities`
--

INSERT INTO `universities` (`university_id`, `name`, `short_name`, `email`, `phone`, `address`, `logo`, `status`, `created_at`, `updated_at`) VALUES
(6, 'Veer Narmad South Gujarat University', 'VNSGU', 'info@vnsgu.ac.in', '+91 261 222 7141', 'Surat, Gujarat', 'u_641f730cdfae6135.png', 'active', '2026-07-25 09:29:13', '2026-07-25 15:54:35'),
(3, 'Stanford University', 'SU', 'hello@stanford.edu', '+1 555 888 222', 'Stanford, CA, USA', 'u3.png', 'active', '2026-07-15 15:11:51', '2026-07-21 06:31:58'),
(4, 'MIT', 'MIT', 'info@mit.edu', '+1 777 999 000', 'Cambridge, MA, USA', 'u_e864371dcb2eb695.jpg', 'active', '2026-07-15 15:11:51', '2026-07-25 15:54:32'),
(5, 'Cambridge University', 'CU', 'admin@cam.ac.uk', '+44 222 333 444', 'Cambridge, UK', 'u_84639847c9792eb1.jpg', 'active', '2026-07-15 15:11:51', '2026-07-25 15:54:33'),
(7, 'Sardar Vallabhbhai National Institute of Technology', 'SVNIT', 'director@svnit.ac.in', '+91 261 225 9571', 'Surat, Gujarat', 'u_c078ad01722e2935.png', 'active', '2026-07-25 09:29:13', '2026-07-25 15:54:38'),
(8, 'Gujarat University', 'GU', 'info@gujaratuniversity.ac.in', '+91 79 2630 1341', 'Ahmedabad, Gujarat', 'u_f5e61fb1658d4987.jpg', 'active', '2026-07-25 09:29:13', '2026-07-25 11:43:19'),
(9, 'Maharaja Sayajirao University of Baroda', 'MSU', 'info@msubaroda.ac.in', '+91 265 279 5555', 'Vadodara, Gujarat', 'u_505c36c17fb8e724.jpg', 'active', '2026-07-25 09:29:13', '2026-07-25 11:46:09'),
(10, 'Nirma University', 'NU', 'info@nirmauni.ac.in', '+91 79 7165 2000', 'Ahmedabad, Gujarat', 'u_aa328ab816885462.jpg', 'active', '2026-07-25 09:29:13', '2026-07-25 11:47:56'),
(11, 'Gujarat Technological University', 'GTU', 'info@gtu.ac.in', '+91 79 2326 7521', 'Ahmedabad, Gujarat', 'u_91f4c1265edf6b06.jpg', 'active', '2026-07-25 09:29:13', '2026-07-25 13:10:13'),
(12, 'Dharmsinh Desai University', 'DDU', 'info@ddu.ac.in', '+91 268 252 0502', 'Nadiad, Gujarat', 'u_7059990772171c52.jpg', 'active', '2026-07-25 09:29:13', '2026-07-25 13:11:49'),
(13, 'Pandit Deendayal Energy University', 'PDEU', 'info@pdpu.ac.in', '+91 79 2327 5060', 'Gandhinagar, Gujarat', 'u_a8f2ed9c0e3b23f4.jpg', 'active', '2026-07-25 09:29:13', '2026-07-25 13:13:20'),
(14, 'Indian Institute of Management Ahmedabad', 'IIMA', 'info@iima.ac.in', '+91 79 2630 8357', 'Ahmedabad, Gujarat', 'u_7f6f5d51de557a98.jpg', 'active', '2026-07-25 09:29:13', '2026-07-25 13:18:51'),
(15, 'Indian Institute of Technology Gandhinagar', 'IITGN', 'info@iitgn.ac.in', '+91 79 2395 2000', 'Gandhinagar, Gujarat', 'u_fb4c2cb3bb118c5b.jpg', 'active', '2026-07-25 09:29:13', '2026-07-26 15:55:52'),
(16, 'Dhirubhai Ambani Institute of ICT', 'DA-IICT', 'info@daiict.ac.in', '+91 79 3051 0555', 'Gandhinagar, Gujarat', 'u_2a71f4f939df7269.jpg', 'active', '2026-07-25 09:29:13', '2026-07-25 13:16:36'),
(17, 'Saurashtra University', 'SU', 'info@sauuni.ac.in', '+91 281 257 8501', 'Rajkot, Gujarat', 'su_logo.png', 'active', '2026-07-25 09:29:13', '2026-07-25 09:29:13'),
(18, 'Navsari Agricultural University', 'NAU', 'info@nau.in', '+91 263 728 2771', 'Navsari, Gujarat', 'u_34d9a661afe09dfb.jpg', 'active', '2026-07-25 09:29:13', '2026-07-26 16:04:08'),
(19, 'Anand Agricultural University', 'AAU', 'info@aau.in', '+91 269 226 1310', 'Anand, Gujarat', 'u_12751825d579b10c.jpg', 'active', '2026-07-25 09:29:13', '2026-07-25 13:45:26'),
(20, 'Junagadh Agricultural University', 'JAU', 'info@jau.in', '+91 285 267 2080', 'Junagadh, Gujarat', 'u_b36cc04a7dc041b9.jpg', 'active', '2026-07-25 09:29:13', '2026-07-25 13:47:50'),
(21, 'Uka Tarsadia University', 'UTU', 'info@utu.ac.in', '+91 262 229 0158', 'Bardoli, Gujarat', 'u_43474b9b96ccecfb.jpg', 'active', '2026-07-25 09:29:13', '2026-07-25 14:00:55'),
(22, 'Parul University', 'PU', 'info@paruluniversity.ac.in', '+91 266 826 0300', 'Vadodara, Gujarat', 'u_043cd297866e4ec6.jpg', 'active', '2026-07-25 09:29:13', '2026-07-25 14:02:04'),
(23, 'Marwadi University', 'MU', 'info@marwadiuniversity.ac.in', '+91 281 292 4155', 'Rajkot, Gujarat', 'u_780c01214354f564.jpg', 'active', '2026-07-25 09:29:13', '2026-07-26 16:05:28'),
(24, 'Ganpat University', 'GUNI', 'info@ganpatuniversity.ac.in', '+91 276 228 6080', 'Mehsana, Gujarat', 'u_3b0cd21c82ab4785.jpg', 'active', '2026-07-25 09:29:13', '2026-07-25 14:13:16'),
(25, 'Karnavati University', 'KU', 'info@karnavatiuniversity.edu.in', '+91 79 3053 5084', 'Gandhinagar, Gujarat', 'u_a40cb44d95c0dad7.jpg', 'active', '2026-07-25 09:29:13', '2026-07-25 14:12:06'),
(26, 'University of Delhi', 'DU', 'info@du.ac.in', '+91 11 2766 7795', 'New Delhi', 'u_61206dee07fd3ad1.jpg', 'active', '2026-07-25 16:03:12', '2026-09-18 02:23:35'),
(27, 'Jawaharlal Nehru University', 'JNU', 'info@jnu.ac.in', '+91 11 2670 4090', 'New Delhi', 'u_1de6c7f4034ec46e.jpg', 'active', '2026-07-25 16:03:12', '2026-07-26 10:41:59'),
(28, 'Banaras Hindu University', 'BHU', 'info@bhu.ac.in', '+91 542 236 8558', 'Varanasi, Uttar Pradesh', 'u_7f8bda2123ad4fae.jpg', 'active', '2026-07-25 16:03:12', '2026-07-26 10:43:05'),
(29, 'Aligarh Muslim University', 'AMU', 'registrar@amu.ac.in', '+91 571 270 0920', 'Aligarh, Uttar Pradesh', 'u_675d57c851681180.jpg', 'active', '2026-07-25 16:03:12', '2026-07-26 10:43:43'),
(30, 'Jamia Millia Islamia', 'JMI', 'registrar@jmi.ac.in', '+91 11 2698 1717', 'New Delhi', 'u_db53ff66c6b15ac0.jpg', 'active', '2026-07-25 16:03:12', '2026-07-26 10:44:25'),
(31, 'University of Hyderabad', 'UOH', 'registrar@uohyd.ac.in', '+91 40 2313 2102', 'Hyderabad, Telangana', 'u_d8ac7dbc63e34ec8.jpg', 'active', '2026-07-25 16:03:12', '2026-07-26 10:45:08'),
(32, 'Anna University', 'ANNA', 'registrar@annauniv.edu', '+91 44 2235 7080', 'Chennai, Tamil Nadu', 'u_7d37299b64591de2.jpg', 'active', '2026-07-25 16:03:12', '2026-07-26 10:46:11'),
(33, 'Vellore Institute of Technology', 'VIT', 'admissions@vit.ac.in', '+91 416 220 2020', 'Vellore, Tamil Nadu', 'u_36e78c36e8f6c79e.jpg', 'active', '2026-07-25 16:03:12', '2026-07-26 10:47:03'),
(34, 'SRM Institute of Science and Technology', 'SRM', 'admissions@srmist.edu.in', '+91 44 2745 5510', 'Chennai, Tamil Nadu', 'u_7df794d19f302259.jpg', 'active', '2026-07-25 16:03:12', '2026-07-26 10:48:29'),
(35, 'Lovely Professional University', 'LPU', 'admissions@lpu.co.in', '+91 182 451 7000', 'Phagwara, Punjab', 'u_55a0d2d628dae5b8.jpg', 'active', '2026-07-25 16:03:12', '2026-07-26 10:49:19'),
(36, 'Chandigarh University', 'CHD', 'admissions@cumail.in', '+91 160 305 1003', 'Mohali, Punjab', 'u_3e449c3392b17d2a.jpg', 'active', '2026-07-25 16:03:12', '2026-07-26 10:50:40'),
(37, 'KIIT University', 'KIIT', 'kiit@kiit.ac.in', '+91 674 272 5113', 'Bhubaneswar, Odisha', 'u_0bafaf12c80192e2.jpg', 'active', '2026-07-25 16:03:12', '2026-07-26 10:52:40'),
(38, 'Calcutta University', 'CALU', 'registrar@caluniv.ac.in', '+91 33 2241 0071', 'Kolkata, West Bengal', 'u_e08ade3b28cf7023.jpg', 'active', '2026-07-25 16:03:12', '2026-08-07 04:00:29'),
(39, 'Osmania University', 'OU', 'registrar@osmania.ac.in', '+91 40 2709 8000', 'Hyderabad, Telangana', 'u_0a6c7232078a256f.jpg', 'active', '2026-07-25 16:03:12', '2026-07-26 10:54:42'),
(40, 'Savitribai Phule Pune University', 'SPPU', 'registrar@unipune.ac.in', '+91 20 2569 6061', 'Pune, Maharashtra', 'u_1f0420bb01e1c384.jpg', 'active', '2026-07-25 16:03:12', '2026-07-26 10:58:20'),
(41, 'Christ University', 'CHRIST', 'admissions@christuniversity.in', '+91 80 4012 9100', 'Bengaluru, Karnataka', 'u_c4e7e3ed5d4ad1ce.jpg', 'active', '2026-07-25 16:03:12', '2026-07-26 10:59:25'),
(42, 'Manipal Academy of Higher Education', 'MAHE', 'info@manipal.edu', '+91 820 292 2400', 'Manipal, Karnataka', 'u_339a6fa0f7fca032.jpg', 'active', '2026-07-25 16:03:12', '2026-07-26 11:00:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `colleges`
--
ALTER TABLE `colleges`
  ADD PRIMARY KEY (`college_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `university_id` (`university_id`);

--
-- Indexes for table `entry_passes`
--
ALTER TABLE `entry_passes`
  ADD PRIMARY KEY (`pass_id`),
  ADD UNIQUE KEY `pass_code` (`pass_code`),
  ADD KEY `idx_pass_registration` (`registration_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `idx_events_college` (`college_id`),
  ADD KEY `idx_events_category` (`category_id`);

--
-- Indexes for table `event_contacts`
--
ALTER TABLE `event_contacts`
  ADD PRIMARY KEY (`contact_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `event_prizes`
--
ALTER TABLE `event_prizes`
  ADD PRIMARY KEY (`prize_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `event_rounds`
--
ALTER TABLE `event_rounds`
  ADD PRIMARY KEY (`round_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `event_rules`
--
ALTER TABLE `event_rules`
  ADD PRIMARY KEY (`rule_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`inquiry_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`),
  ADD KEY `idx_payment_registration` (`registration_id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`registration_id`),
  ADD KEY `idx_registration_event` (`event_id`),
  ADD KEY `idx_registration_student` (`student_id`),
  ADD KEY `idx_registration_team` (`team_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `enrollment_no` (`enrollment_no`),
  ADD KEY `idx_students_college` (`college_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`team_id`),
  ADD UNIQUE KEY `team_code` (`team_code`),
  ADD KEY `leader_id` (`leader_id`),
  ADD KEY `idx_team_event` (`event_id`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`team_member_id`),
  ADD UNIQUE KEY `team_id` (`team_id`,`student_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `universities`
--
ALTER TABLE `universities`
  ADD PRIMARY KEY (`university_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `colleges`
--
ALTER TABLE `colleges`
  MODIFY `college_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;

--
-- AUTO_INCREMENT for table `entry_passes`
--
ALTER TABLE `entry_passes`
  MODIFY `pass_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `event_contacts`
--
ALTER TABLE `event_contacts`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_prizes`
--
ALTER TABLE `event_prizes`
  MODIFY `prize_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_rounds`
--
ALTER TABLE `event_rounds`
  MODIFY `round_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_rules`
--
ALTER TABLE `event_rules`
  MODIFY `rule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `inquiry_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=338;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `registration_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=346;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=243;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `team_member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=199;

--
-- AUTO_INCREMENT for table `universities`
--
ALTER TABLE `universities`
  MODIFY `university_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
