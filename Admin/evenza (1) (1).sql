-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 07, 2026 at 03:15 PM
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
(8, 'Literary Events', 'inactive'),
(9, 'Workshops & Seminars', 'inactive'),
(10, 'Social & Fun Events', 'inactive');

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
(100, 3, 'Stanford College of Engineering', 'stanford-engineering', 'engineering@stanford.edu', '123456', '+1 555 111 001', 'Stanford, CA', 'c_52568d6e97c7f3ed.jpg', 'active', '2026-08-06 07:17:04', '2026-08-06 11:58:24'),
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
  `status` enum('draft','published','completed','cancelled') DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `college_id`, `category_id`, `title`, `description`, `event_type`, `min_team_size`, `max_team_size`, `fee_type`, `registration_fee`, `registration_deadline`, `event_date`, `start_time`, `end_time`, `venue`, `dress_code`, `status`, `created_at`, `updated_at`) VALUES
(1, 100, 1, 'Code Odyssey 2026', 'An intensive 12-hour hackathon where coding enthusiasts build innovative software solutions from scratch.', 'team', 2, 4, 'per_person', 150.00, '2026-08-14 00:00:00', '2026-08-15', '09:00:00', '21:00:00', 'Main Computer Lab, Block A', NULL, 'draft', '2026-08-07 06:09:23', '2026-08-07 07:04:10'),
(2, 101, 1, 'AI & Machine Learning Workshop', 'Learn the fundamentals of building neural networks and deploying modern machine learning pipelines using Python.', 'solo', 1, 1, 'per_person', 200.00, '2026-08-17 00:00:00', '2026-08-18', '10:00:00', '16:00:00', 'Seminar Hall 2', NULL, 'draft', '2026-08-07 06:09:23', '2026-08-07 06:09:23'),
(3, 102, 2, 'Nrityanjali Classical Dance', 'A grand classical and semi-classical group dance competition celebrating traditional Indian performing arts.', 'team', 3, 8, 'per_person', 100.00, '2026-08-19 00:00:00', '2026-08-20', '17:00:00', '21:00:00', 'University Open Air Theatre', NULL, 'draft', '2026-08-07 06:09:23', '2026-08-07 07:04:10'),
(4, 103, 2, 'Battle of the Bands', 'Rock, pop, and fusion music bands clash on stage for the ultimate musical supremacy title.', 'team', 3, 6, 'per_person', 300.00, '2026-08-21 00:00:00', '2026-08-22', '18:30:00', '22:30:00', 'Main Auditorium', NULL, 'draft', '2026-08-07 06:09:23', '2026-08-07 07:04:10'),
(5, 104, 3, 'Inter-College Box Cricket League', 'Fast-paced tennis ball cricket tournament designed for fierce team rivalries and high scores.', 'team', 6, 8, 'per_person', 500.00, '2026-08-24 00:00:00', '2026-08-25', '08:00:00', '18:00:00', 'University Sports Ground', NULL, 'draft', '2026-08-07 06:09:23', '2026-08-07 07:04:10'),
(6, 105, 3, 'Badminton Smash Championship', 'Singles and doubles badminton tournament showcasing agility, speed, and precision strokes.', 'solo', 1, 2, 'per_person', 150.00, '2026-08-27 00:00:00', '2026-08-28', '09:30:00', '17:00:00', 'Indoor Sports Complex', NULL, 'draft', '2026-08-07 06:09:23', '2026-08-07 06:09:23'),
(7, 106, 5, 'Corporate Shark Tank', 'Pitch your innovative startup ideas and business models in front of seasoned industry investors.', 'team', 2, 4, 'per_person', 250.00, '2026-09-01 00:00:00', '2026-09-02', '11:00:00', '16:00:00', 'Management Block Conference Hall', NULL, 'draft', '2026-08-07 06:09:23', '2026-08-07 07:04:10'),
(8, 107, 6, 'Valorant E-Sports Showdown', 'Tactical 5v5 shooter esports tournament with live broadcasting and massive prize pools.', 'team', 5, 5, 'per_person', 200.00, '2026-09-04 00:00:00', '2026-09-05', '13:00:00', '20:00:00', 'Digital Gaming Arena, Lab 4', NULL, 'draft', '2026-08-07 06:09:23', '2026-08-07 07:04:10'),
(9, 108, 7, 'Canvas Painting & Sketching', 'Express your creativity on canvas through fine arts, oil painting, and expressive sketching.', 'solo', 1, 1, 'per_person', 100.00, '2026-09-07 00:00:00', '2026-09-08', '10:00:00', '14:00:00', 'Fine Arts Studio Room 3', NULL, 'draft', '2026-08-07 06:09:23', '2026-08-07 06:09:23'),
(10, 109, 8, 'Extempore & Parliamentary Debate', 'Test your eloquence, vocabulary, and sharp critical thinking through structured debates.', 'solo', 1, 1, 'per_person', 50.00, '2026-09-09 00:00:00', '2026-09-10', '14:00:00', '17:30:00', 'Library Seminar Room', NULL, 'draft', '2026-08-07 06:09:23', '2026-08-07 12:20:47');

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
(8, 2, 8, NULL, 'solo', 'cancelled', '2026-08-07 07:13:53');

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
(1, 100, 'STU-100-1', 'Rahul Verma', 'rahul.v100@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000001', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:54', 'pending', 'active'),
(2, 100, 'STU-100-2', 'Priya Sharma', 'priya.s100@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000002', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(3, 100, 'STU-100-3', 'Amit Patel', 'amit.p100@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000003', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(4, 101, 'STU-101-1', 'Sneha Iyer', 'sneha.i101@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000004', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(5, 101, 'STU-101-2', 'Vikram Singh', 'vikram.s101@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000005', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(6, 101, 'STU-101-3', 'Anjali Roy', 'anjali.r101@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000006', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(7, 102, 'STU-102-1', 'Rohan Gupta', 'rohan.g102@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000007', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(8, 102, 'STU-102-2', 'Megha Desai', 'megha.d102@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000008', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(9, 102, 'STU-102-3', 'Kunal Shah', 'kunal.s102@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000009', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(10, 103, 'STU-103-1', 'Deepika Rao', 'deepika.r103@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000010', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(11, 103, 'STU-103-2', 'Suresh Kumar', 'suresh.k103@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000011', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(12, 103, 'STU-103-3', 'Pooja Nair', 'pooja.n103@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000012', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(13, 104, 'STU-104-1', 'Arjun Reddy', 'arjun.r104@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000013', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(14, 104, 'STU-104-2', 'Divya Joshi', 'divya.j104@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000014', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(15, 104, 'STU-104-3', 'Manoj Yadav', 'manoj.y104@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000015', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(16, 105, 'STU-105-1', 'Shweta Mane', 'shweta.m105@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000016', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(17, 105, 'STU-105-2', 'Harish Nair', 'harish.n105@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000017', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(18, 105, 'STU-105-3', 'Anita Das', 'anita.d105@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000018', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(19, 106, 'STU-106-1', 'Vijay Malya', 'vijay.m106@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000019', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(20, 106, 'STU-106-2', 'Kavita Singh', 'kavita.s106@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000020', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(21, 107, 'STU-107-1', 'Sanjay Dutt', 'sanjay.d107@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000021', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(22, 107, 'STU-107-2', 'Neha Kakar', 'neha.k107@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000022', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(23, 107, 'STU-107-3', 'Manish Malhotra', 'manish.m107@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000023', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(24, 108, 'STU-108-1', 'Sonakshi Sinha', 'sonakshi.s108@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000024', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(25, 108, 'STU-108-2', 'Varun Dhawan', 'varun.d108@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000025', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(26, 108, 'STU-108-3', 'Alia Bhatt', 'alia.b108@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000026', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(27, 109, 'STU-109-1', 'Ranbir Kapoor', 'ranbir.k109@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000027', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(28, 109, 'STU-109-2', 'Shraddha Kapoor', 'shraddha.k109@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000028', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(29, 109, 'STU-109-3', 'Shahid Kapoor', 'shahid.k109@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000029', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(30, 110, 'STU-110-1', 'Kiara Advani', 'kiara.a110@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000030', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(31, 110, 'STU-110-2', 'Sidharth Malhotra', 'sidharth.m110@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000031', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(32, 110, 'STU-110-3', 'Katrina Kaif', 'katrina.k110@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000032', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(33, 111, 'STU-111-1', 'Vicky Kaushal', 'vicky.k111@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000033', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(34, 111, 'STU-111-2', 'Kriti Sanon', 'kriti.s111@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000034', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(35, 111, 'STU-111-3', 'Kartik Aaryan', 'kartik.a111@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000035', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(36, 112, 'STU-112-1', 'Ananya Panday', 'ananya.p112@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000036', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(37, 112, 'STU-112-2', 'Ishaan Khatter', 'ishaan.k112@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000037', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(38, 112, 'STU-112-3', 'Janhvi Kapoor', 'janhvi.k112@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000038', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(39, 113, 'STU-113-1', 'Tiger Shroff', 'tiger.s113@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000039', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(40, 113, 'STU-113-2', 'Disha Patani', 'disha.p113@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000040', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(41, 114, 'STU-114-1', 'Ayushmann Khurrana', 'ayushmann.k114@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000041', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:46', 'pending', 'active'),
(42, 114, 'STU-114-2', 'Bhumi Pednekar', 'bhumi.p114@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000042', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(43, 114, 'STU-114-3', 'Rajkummar Rao', 'rajkummar.r114@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000043', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(44, 115, 'STU-115-1', 'Patralekha Paul', 'patralekha.p115@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000044', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(45, 115, 'STU-115-2', 'Nawazuddin Siddiqui', 'nawazuddin.s115@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000045', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(46, 115, 'STU-115-3', 'Pankaj Tripathi', 'pankaj.t115@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000046', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(47, 116, 'STU-116-1', 'Manoj Bajpayee', 'manoj.b116@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000047', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:41', 'pending', 'active'),
(48, 116, 'STU-116-2', 'Radhika Apte', 'radhika.a116@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000048', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(49, 116, 'STU-116-3', 'Taapsee Pannu', 'taapsee.p116@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000049', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(50, 117, 'STU-117-1', 'Swara Bhaskar', 'swara.b117@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000050', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(51, 117, 'STU-117-2', 'Richa Chadha', 'richa.c117@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000051', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(52, 117, 'STU-117-3', 'Ali Fazal', 'ali.f117@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000052', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(53, 118, 'STU-118-1', 'Pratik Gandhi', 'pratik.g118@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000053', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(54, 118, 'STU-118-2', 'Shreya Dhanwanthary', 'shreya.d118@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000054', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(55, 118, 'STU-118-3', 'Divyenndu Sharma', 'divyenndu.s118@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000055', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(56, 119, 'STU-119-1', 'Vikrant Massey', 'vikrant.m119@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000056', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(57, 119, 'STU-119-2', 'Sheetal Thakur', 'sheetal.t119@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000057', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(58, 119, 'STU-119-3', 'Abhishek Banerjee', 'abhishek.b119@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000058', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(59, 120, 'STU-120-1', 'Jaideep Ahlawat', 'jaideep.a120@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000059', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(60, 120, 'STU-120-2', 'Ishwak Singh', 'ishwaksingh120@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000060', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(61, 121, 'STU-121-1', 'Jitendra Kumar', 'jitendra.k121@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000061', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(62, 121, 'STU-121-2', 'Neena Gupta', 'neena.g121@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000062', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(63, 121, 'STU-121-3', 'Gajraj Rao', 'gajraj.r121@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000063', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(64, 122, 'STU-122-1', 'Shefali Shah', 'shefali.s122@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000064', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(65, 122, 'STU-122-2', 'Rasika Dugal', 'rasika.d122@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000065', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(66, 122, 'STU-122-3', 'Rajesh Tailang', 'rajesh.t122@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000066', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(67, 123, 'STU-123-1', 'Kulbhushan Kharbanda', 'kulbhushan.k123@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000067', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(68, 123, 'STU-123-2', 'Pooja Bhatt', 'pooja.b123@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000068', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(69, 123, 'STU-123-3', 'Rahul Bose', 'rahul.b123@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000069', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(70, 124, 'STU-124-1', 'Rimi Sen', 'rimi.s124@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000070', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(71, 124, 'STU-124-2', 'Zohra Sehgal', 'zohra.s124@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000071', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(72, 124, 'STU-124-3', 'Paresh Rawal', 'paresh.r124@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000072', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(73, 125, 'STU-125-1', 'Akshay Kumar', 'akshay.k125@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000073', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(74, 125, 'STU-125-2', 'Suniel Shetty', 'suniel.s125@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000074', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(75, 125, 'STU-125-3', 'Bobby Deol', 'bobby.d125@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000075', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(76, 126, 'STU-126-1', 'Sunny Deol', 'sunny.d126@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000076', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(77, 126, 'STU-126-2', 'Sanjay Kapoor', 'sanjay.k126@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000077', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(78, 126, 'STU-126-3', 'Anil Kapoor', 'anil.k126@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000078', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(79, 127, 'STU-127-1', 'Jackie Shroff', 'jackie.s127@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000079', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(80, 127, 'STU-127-2', 'Govinda Ahuja', 'govinda.a127@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000080', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(81, 128, 'STU-128-1', 'Madhuri Dixit', 'madhuri.d128@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000081', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(82, 128, 'STU-128-2', 'Juhi Chawla', 'juhi.c128@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000082', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(83, 128, 'STU-128-3', 'Raveena Tandon', 'raveena.t128@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000083', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(84, 129, 'STU-129-1', 'Karisma Kapoor', 'karisma.k129@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000084', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(85, 129, 'STU-129-2', 'Tabu Hashmi', 'tabu.h129@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000085', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(86, 129, 'STU-129-3', 'Urmila Matondkar', 'urmila.m129@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000086', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(87, 130, 'STU-130-1', 'Shilpa Shetty', 'shilpa.s130@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000087', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(88, 130, 'STU-130-2', 'Sonali Bendre', 'sonali.b130@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000088', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(89, 130, 'STU-130-3', 'Pooja Batra', 'pooja.b130@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000089', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(90, 131, 'STU-131-1', 'Mahima Chaudhry', 'mahima.c131@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000090', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(91, 131, 'STU-131-2', 'Gayatri Joshi', 'gayatri.j131@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000091', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(92, 131, 'STU-131-3', 'Gracy Singh', 'gracy.s131@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000092', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(93, 132, 'STU-132-1', 'Amrita Rao', 'amrita.r132@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000093', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(94, 132, 'STU-132-2', 'Esha Deol', 'esha.d132@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000094', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(95, 132, 'STU-132-3', 'Soha Ali Khan', 'soha.k132@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000095', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(96, 133, 'STU-133-1', 'Dia Mirza', 'dia.m133@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000096', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(97, 133, 'STU-133-2', 'Rimi Sen', 'rimi.s133@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000097', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(98, 133, 'STU-133-3', 'Sameera Reddy', 'sameera.r133@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000098', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(99, 134, 'STU-134-1', 'Tanishaa Mukerji', 'tanishaa.m134@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000099', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(100, 134, 'STU-134-2', 'Kim Sharma', 'kim.s134@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000100', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(101, 135, 'STU-135-1', 'Preity Zinta', 'preity.z135@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000101', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(102, 135, 'STU-135-2', 'Gracy Singh', 'gracy.s135@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000102', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(103, 135, 'STU-135-3', 'Rani Mukerji', 'rani.m135@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000103', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(104, 136, 'STU-136-1', 'Ameesha Patel', 'ameesha.p136@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000104', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(105, 136, 'STU-136-2', 'Bipasha Basu', 'bipasha.b136@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000105', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(106, 136, 'STU-136-3', 'Lara Dutta', 'lara.d136@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000106', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(107, 137, 'STU-137-1', 'Priyanka Chopra', 'priyanka.c137@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000107', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(108, 137, 'STU-137-2', 'Kareena Kapoor', 'kareena.k137@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000108', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(109, 137, 'STU-137-3', 'Amrita Arora', 'amrita.a137@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000109', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(110, 138, 'STU-138-1', 'Malaika Arora', 'malaika.a138@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000110', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(111, 138, 'STU-138-2', 'Zayed Khan', 'zayed.k138@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000111', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(112, 138, 'STU-138-3', 'Fardeen Khan', 'fardeen.k138@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000112', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(113, 139, 'STU-139-1', 'Aftab Shivdasani', 'aftab.s139@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000113', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(114, 139, 'STU-139-2', 'Riteish Deshmukh', 'riteish.d139@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000114', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(115, 139, 'STU-139-3', 'Genelia Dsouza', 'genelia.d139@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000115', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(116, 140, 'STU-140-1', 'John Abraham', 'john.a140@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000116', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(117, 140, 'STU-140-2', 'Dino Morea', 'dino.m140@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000117', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(118, 140, 'STU-140-3', 'Rahul Khanna', 'rahul.k140@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000118', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(119, 141, 'STU-141-1', 'Sanjay Suri', 'sanjay.s141@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000119', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(120, 141, 'STU-141-2', 'Purab Kohli', 'purab.k141@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000120', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(121, 142, 'STU-142-1', 'Sikander Kher', 'sikander.k142@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000121', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(122, 142, 'STU-142-2', 'Kunal Kapoor', 'kunal.k142@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000122', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(123, 142, 'STU-142-3', 'Abhay Deol', 'abhay.d142@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000123', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(124, 143, 'STU-143-1', 'Emraan Hashmi', 'emraan.h143@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000124', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(125, 143, 'STU-143-2', 'Randeep Hooda', 'randeep.h143@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000125', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(126, 143, 'STU-143-3', 'Shreyas Talpade', 'shreyas.t143@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000126', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(127, 144, 'STU-144-1', 'Sonu Sood', 'sonu.s144@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000127', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(128, 144, 'STU-144-2', 'Sharman Joshi', 'sharman.j144@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000128', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(129, 144, 'STU-144-3', 'R Madhavan', 'madhavan.r144@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000129', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(130, 145, 'STU-145-1', 'Siddharth Narayan', 'siddharth.n145@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000130', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(131, 145, 'STU-145-2', 'Neil Nitin Mukesh', 'neil.m145@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000131', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(132, 145, 'STU-145-3', 'Prateik Babbar', 'prateik.b145@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000132', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(133, 146, 'STU-146-1', 'Ranveer Singh', 'ranveer.s146@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000133', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(134, 146, 'STU-146-2', 'Sushant Singh', 'sushant.s146@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000134', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(135, 146, 'STU-146-3', 'Arjun Kapoor', 'arjun.k146@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000135', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(136, 147, 'STU-147-1', 'Varun Sharma', 'varun.s147@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000136', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(137, 147, 'STU-147-2', 'Pulkit Samrat', 'pulkit.s147@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000137', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(138, 147, 'STU-147-3', 'Ali Zafar', 'ali.z147@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000138', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(139, 148, 'STU-148-1', 'Aditya Roy Kapur', 'aditya.r148@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000139', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(140, 148, 'STU-148-2', 'Siddharth Malhotra', 'siddharth.m148@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000140', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(141, 149, 'STU-149-1', 'Siddhant Chaturvedi', 'siddhant.c149@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000141', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(142, 149, 'STU-149-2', 'Ishaan Khatter', 'ishaan.k149@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000142', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(143, 149, 'STU-149-3', 'Meezaan Jafri', 'meezaan.j149@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000143', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(144, 150, 'STU-150-1', 'Sunny Kaushal', 'sunny.k150@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000144', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(145, 150, 'STU-150-2', 'Ahan Shetty', 'ahan.s150@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000145', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(146, 150, 'STU-150-3', 'Abhimanyu Dassani', 'abhimanyu.d150@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000146', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(147, 151, 'STU-151-1', 'Lakshya Lalwani', 'lakshya.l151@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000147', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(148, 151, 'STU-151-2', 'Sharvari Wagh', 'sharvari.w151@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000148', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(149, 151, 'STU-151-3', 'Alaya Furniturewala', 'alaya.f151@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000149', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(150, 152, 'STU-152-1', 'Tara Sutaria', 'tara.s152@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000150', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(151, 152, 'STU-152-2', 'Ananya Panday', 'ananya.p152@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000151', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(152, 152, 'STU-152-3', 'Janhvi Kapoor', 'janhvi.k152@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000152', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(153, 153, 'STU-153-1', 'Sara Ali Khan', 'sara.k153@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000153', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(154, 153, 'STU-153-2', 'Manushi Chhillar', 'manushi.c153@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000154', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(155, 153, 'STU-153-3', 'Kriti Sanon', 'kriti.s153@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000155', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(156, 154, 'STU-154-1', 'Nushrat Bharuccha', 'nushrat.b154@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000156', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(157, 154, 'STU-154-2', 'Yami Gautam', 'yami.g154@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000157', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(158, 154, 'STU-154-3', 'Huma Qureshi', 'huma.q154@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000158', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(159, 155, 'STU-155-1', 'Richa Chadha', 'richa.c155@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000159', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(160, 155, 'STU-155-2', 'Swara Bhaskar', 'swara.b155@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000160', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(161, 156, 'STU-156-1', 'Radhika Madan', 'radhika.m156@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000161', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(162, 156, 'STU-156-2', 'Sanya Malhotra', 'sanya.m156@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000162', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(163, 156, 'STU-156-3', 'Fatima Sana Shaikh', 'fatima.s156@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000163', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(164, 157, 'STU-157-1', 'Zaira Wasim', 'zaira.w157@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000164', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(165, 157, 'STU-157-2', 'Meher Vij', 'meher.v157@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000165', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(166, 157, 'STU-157-3', 'Rajshri Deshpande', 'rajshri.d157@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000166', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(167, 158, 'STU-158-1', 'Shefali Shah', 'shefali.s158@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000167', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(168, 158, 'STU-158-2', 'Tisca Chopra', 'tisca.c158@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000168', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(169, 158, 'STU-158-3', 'Divya Dutta', 'divya.d158@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000169', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(170, 159, 'STU-159-1', 'Sandhya Mridul', 'sandhya.m159@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000170', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(171, 159, 'STU-159-2', 'Sonali Kulkarni', 'sonali.k159@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000171', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(172, 159, 'STU-159-3', 'Kiran Kher', 'kiran.k159@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000172', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(173, 160, 'STU-160-1', 'Supriya Pathak', 'supriya.p160@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000173', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(174, 160, 'STU-160-2', 'Ratna Pathak', 'ratna.p160@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000174', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(175, 160, 'STU-160-3', 'Ila Arun', 'ila.a160@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000175', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(176, 161, 'STU-161-1', 'Seema Pahwa', 'seema.p161@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000176', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(177, 161, 'STU-161-2', 'Himani Shivpuri', 'himani.s161@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000177', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(178, 161, 'STU-161-3', 'Reema Lagoo', 'reema.l161@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000178', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(179, 162, 'STU-162-1', 'Farida Jalal', 'farida.j162@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000179', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(180, 162, 'STU-162-2', 'Rohini Hattangadi', 'rohini.h162@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000180', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(181, 163, 'STU-163-1', 'Rita Bhaduri', 'rita.b163@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000181', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(182, 163, 'STU-163-2', 'Bindu Desai', 'bindu.d163@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000182', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(183, 163, 'STU-163-3', 'Shashikala Joshi', 'shashikala.j163@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000183', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active');
INSERT INTO `students` (`student_id`, `college_id`, `enrollment_no`, `name`, `email`, `password`, `phone`, `gender`, `semester`, `id_card_image`, `profile_photo`, `status`, `created_at`, `updated_at`, `verification_status`, `account_status`) VALUES
(184, 164, 'STU-164-1', 'Nadira Begum', 'nadira.b164@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000184', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(185, 164, 'STU-164-2', 'Lalita Pawar', 'lalita.p164@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000185', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(186, 164, 'STU-164-3', 'Durga Khote', 'durga.k164@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000186', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(187, 165, 'STU-165-1', 'Achala Sachdev', 'achala.s165@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000187', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(188, 165, 'STU-165-2', 'Leela Chitnis', 'leela.c165@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000188', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(189, 165, 'STU-165-3', 'Sulochana Latkar', 'sulochana.l165@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000189', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(190, 166, 'STU-166-1', 'Nirupa Roy', 'nirupa.r166@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000190', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(191, 166, 'STU-166-2', 'Kamini Kaushal', 'kamini.k166@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000191', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(192, 166, 'STU-166-3', 'Suraiya Akhtar', 'suraiya.a166@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000192', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(193, 167, 'STU-167-1', 'Noor Jehan', 'noor.j167@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000193', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(194, 167, 'STU-167-2', 'Shamshad Begum', 'shamshad.b167@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000194', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(195, 167, 'STU-167-3', 'Zohra Bai', 'zohra.b167@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000195', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(196, 168, 'STU-168-1', 'Geeta Dutt', 'geeta.d168@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000196', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(197, 168, 'STU-168-2', 'Lata Mangeshkar', 'lata.m168@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000197', 'female', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(198, 168, 'STU-168-3', 'Asha Bhosle', 'asha.b168@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000198', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(199, 169, 'STU-169-1', 'Usha Mangeshkar', 'usha.m169@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000199', 'female', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(200, 169, 'STU-169-2', 'Suman Kalyanpur', 'suman.k169@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000200', 'female', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(201, 170, 'STU-170-1', 'Hemant Kumar', 'hemant.k170@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000201', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(202, 170, 'STU-170-2', 'Manna Dey', 'manna.d170@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000202', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(203, 170, 'STU-170-3', 'Talat Mahmood', 'talat.m170@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000203', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(204, 171, 'STU-171-1', 'Mukesh Chand', 'mukesh.c171@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000204', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(205, 171, 'STU-171-2', 'Mohammed Rafi', 'mohammed.r171@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000205', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(206, 171, 'STU-171-3', 'Kishore Kumar', 'kishore.k171@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000206', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(207, 172, 'STU-172-1', 'Mahendra Kapoor', 'mahendra.k172@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000207', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(208, 172, 'STU-172-2', 'Bhupen Hazarika', 'bhupen.h172@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000208', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(209, 172, 'STU-172-3', 'Pankaj Udhas', 'pankaj.u172@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000209', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(210, 173, 'STU-173-1', 'Jagjit Singh', 'jagjit.s173@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000210', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(211, 173, 'STU-173-2', 'Anup Jalota', 'anup.j173@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000211', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(212, 173, 'STU-173-3', 'Hariharan Ramaswamy', 'hariharan.r173@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000212', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(213, 174, 'STU-174-1', 'Suresh Wadkar', 'suresh.w174@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000213', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(214, 174, 'STU-174-2', 'Shabbir Kumar', 'shabbir.k174@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000214', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(215, 174, 'STU-174-3', 'Mohammed Aziz', 'mohammed.a174@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000215', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(216, 175, 'STU-175-1', 'Vinod Rathod', 'vinod.r175@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000216', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(217, 175, 'STU-175-2', 'Kumar Sanu', 'kumar.s175@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000217', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(218, 175, 'STU-175-3', 'Udit Narayan', 'udit.n175@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000218', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(219, 176, 'STU-176-1', 'Abhijeet Bhattacharya', 'abhijeet.b176@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000219', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(220, 176, 'STU-176-2', 'Sonu Nigam', 'sonu.n176@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000220', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(221, 176, 'STU-176-3', 'Shaan Mukherjee', 'shaan.m176@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000221', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(222, 177, 'STU-177-1', 'KK Krishnakumar', 'kk.k177@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000222', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(223, 177, 'STU-177-2', ' Sukhwinder Singh', 'sukhwinder.s177@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000223', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(224, 177, 'STU-177-3', 'Kailash Kher', 'kailash.k177@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000224', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(225, 178, 'STU-178-1', 'Mohit Chauhan', 'mohit.c178@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000225', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(226, 178, 'STU-178-2', 'Arijit Singh', 'arijit.s178@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000226', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(227, 178, 'STU-178-3', 'Atif Aslam', 'atif.a178@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000227', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(228, 179, 'STU-179-1', 'Rahat Fateh Ali', 'rahat.f179@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000228', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(229, 179, 'STU-179-2', 'Armaan Malik', 'armaan.m179@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000229', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(230, 179, 'STU-179-3', 'Darshan Raval', 'darshan.r179@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000230', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(231, 100, 'STU-100-4', 'Guru Randhawa', 'guru.r100@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000231', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(232, 101, 'STU-101-4', 'Badshah Singh', 'badshah.s101@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000232', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(233, 102, 'STU-102-4', 'Yo Yo Honey Singh', 'honey.s102@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000233', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(234, 103, 'STU-103-4', 'Raftaar Singh', 'raftaar.s103@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000234', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(235, 104, 'STU-104-4', 'Tony Kakkar', 'tony.k104@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000235', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(236, 105, 'STU-105-4', 'Akhil Sachdeva', 'akhil.s105@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000236', 'male', 'Semester 6', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(237, 106, 'STU-106-4', 'Jubin Nautiyal', 'jubin.n106@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000237', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(238, 107, 'STU-107-4', 'B Praak', 'bpraak.107@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000238', 'male', 'Semester 2', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(239, 108, 'STU-108-4', 'Stebin Ben', 'stebin.b108@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000239', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active'),
(240, 109, 'STU-109-4', 'Akhil Pasreja', 'akhil.p109@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9800000240', 'male', 'Semester 4', 'card.png', 'avatar.png', 'pending', '2026-08-07 05:46:16', '2026-08-07 05:46:16', 'pending', 'active');

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
(3, 4, 7, 'Apex Innovators', 'TEAM-104', '2026-08-07 07:30:25');

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
(9, 2, 223);

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
(26, 'University of Delhi', 'DU', 'info@du.ac.in', '+91 11 2766 7795', 'New Delhi', 'u_61206dee07fd3ad1.jpg', 'inactive', '2026-07-25 16:03:12', '2026-08-07 12:33:37'),
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
(42, 'Manipal Academy of Higher Education', 'MAHE', 'info@manipal.edu', '+91 820 292 2400', 'Manipal, Karnataka', 'u_339a6fa0f7fca032.jpg', 'active', '2026-07-25 16:03:12', '2026-07-26 11:00:07'),
(43, 'Dream Class', 'DC', 'devashishasthana7@gmail.com', '+91 281 257 8501', 'surat', 'u_06b1f6f4325a2aab.jpg', 'active', '2026-08-07 12:55:29', '2026-08-07 12:55:48');

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
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `registration_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `team_member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `universities`
--
ALTER TABLE `universities`
  MODIFY `university_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
