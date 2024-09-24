-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 13, 2019 at 01:30 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shareous`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `password_reset_code` mediumtext,
  `contact` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `profile_image` varchar(100) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `user_name`, `first_name`, `last_name`, `email`, `password`, `password_reset_code`, `contact`, `remember_token`, `profile_image`, `address`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Admin', 'Sagar', 'Pawar', 'admin@webwing.com', '$2y$10$wch1AIJw3LRZVqS0UO66WOboWCDzHK7Fur0..Ofc/pSwfNuG347fy', '', '+91-9011223011', 'Hbpqm5ZUghCYki7HkvXXzp9TT0Yae5CmfFHmTIuzG8llXLte7ykm1ODj2ZUe', 'f555c3fe0b56751ed9250b84841d476abad785af.jpg', 'webwing nashik', '2017-07-18 03:00:00', '2019-02-08 06:17:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_password_resets`
--

CREATE TABLE `admin_password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin_password_resets`
--

INSERT INTO `admin_password_resets` (`email`, `token`, `created_at`) VALUES
('admin@webwing.com', '5975b695b2e6827a03c207599ae70c2c8c417e2777db80b870b0ca7136a10ef4', '2018-09-11 05:30:59');

-- --------------------------------------------------------

--
-- Table structure for table `admin_report`
--

CREATE TABLE `admin_report` (
  `id` int(11) NOT NULL,
  `report_id` varchar(250) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(250) NOT NULL,
  `report_type` varchar(200) NOT NULL,
  `fromdate` date NOT NULL,
  `todate` date NOT NULL,
  `report_user_type` enum('host','admin') NOT NULL,
  `total_amount` float NOT NULL,
  `total_commission` double NOT NULL,
  `report_invoice` varchar(250) NOT NULL,
  `status` enum('unpaid','paid') NOT NULL,
  `report_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_report`
--

INSERT INTO `admin_report` (`id`, `report_id`, `user_id`, `username`, `report_type`, `fromdate`, `todate`, `report_user_type`, `total_amount`, `total_commission`, `report_invoice`, `status`, `report_date`, `created_at`, `updated_at`) VALUES
(1, 'R000001', 52, 'Tarun Sagar', '', '2018-10-03', '2018-10-04', 'host', 251500, 12.15, 'User_Report1.pdf', 'unpaid', '2018-10-06 09:48:27', '2018-10-06 09:48:27', '2018-10-06 09:48:27');

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
--

CREATE TABLE `amenities` (
  `id` int(11) NOT NULL,
  `propertytype_id` int(11) NOT NULL,
  `aminity_name` varchar(255) NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `status` enum('0','1') CHARACTER SET utf8 NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `amenities`
--

INSERT INTO `amenities` (`id`, `propertytype_id`, `aminity_name`, `slug`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(8, 5, 'kitchen', 'kitchen', '1', '2018-02-19 00:16:56', '2018-09-06 14:40:43', NULL),
(11, 4, 'breakfast', 'breakfast', '1', '2018-02-19 00:17:24', '2018-09-06 14:40:42', NULL),
(13, 3, 'free parking on premise', 'free-parking-on-premise', '1', '2018-02-19 00:17:43', '2018-09-06 14:40:42', NULL),
(14, 3, 'swimming pool', 'swimming-pool', '1', '2018-02-21 00:25:09', '2018-09-06 14:37:39', NULL),
(16, 5, 'washer', 'washer', '1', '2018-02-22 01:32:12', '2018-09-06 14:37:39', NULL),
(17, 1, 'car parking', 'car-parking', '1', '2018-04-23 06:02:02', '2018-09-06 14:37:22', NULL),
(18, 5, 'balcony', 'balcony', '1', '2018-04-26 13:36:23', '2018-09-06 14:37:39', NULL),
(19, 1, 'in-unit washer and dryer', 'in-unit-washer-and-dryer', '1', '2018-05-04 04:36:28', '2018-09-06 14:40:43', NULL),
(20, 1, 'air conditioning', 'air-conditioning', '1', '2018-05-04 04:36:43', '2018-09-06 14:40:43', NULL),
(21, 1, 'pets allowed', 'pets-allowed', '1', '2018-05-04 04:36:58', '2018-09-06 14:40:43', NULL),
(22, 1, 'furnished apartments', 'furnished-apartments', '1', '2018-05-04 04:37:10', '2018-09-06 14:40:43', NULL),
(23, 1, 'dishwasher', 'dishwasher', '1', '2018-05-04 04:37:19', '2018-09-06 14:40:43', NULL),
(24, 1, 'washer and dryer connections', 'washer-and-dryer-connections', '1', '2018-05-04 04:37:32', '2018-09-06 14:40:43', NULL),
(25, 1, 'some utilities included', 'some-utilities-included', '1', '2018-05-04 04:37:45', '2018-09-06 14:40:43', NULL),
(26, 1, 'balcony', 'balcony', '1', '2018-05-04 04:50:59', '2018-09-06 14:40:43', NULL),
(27, 1, 'cable ready', 'cable-ready', '1', '2018-05-04 04:51:09', '2018-09-06 14:40:43', NULL),
(28, 1, 'all utilities included', 'all-utilities-included', '1', '2018-05-04 04:51:23', '2018-09-06 14:40:43', NULL),
(29, 1, 'pet friendly', 'pet-friendly', '1', '2018-05-04 04:51:41', '2018-09-06 14:40:43', NULL),
(30, 1, 'garages', 'garages', '1', '2018-05-04 04:51:53', '2018-09-06 14:40:43', NULL),
(31, 1, 'swimming pool', 'swimming-pool', '1', '2018-05-04 04:52:03', '2018-09-06 14:40:43', NULL),
(32, 10, 'balcony', 'balcony', '1', '2018-07-20 13:18:13', '2018-09-06 14:40:42', NULL),
(33, 10, 'exit cleaning', 'exit-cleaning', '1', '2018-07-20 13:18:24', '2018-09-06 14:40:42', NULL),
(34, 10, 'grill', 'grill', '1', '2018-07-20 13:18:36', '2018-09-06 14:40:42', NULL),
(35, 10, 'internet', 'internet', '1', '2018-07-20 13:18:47', '2018-09-06 14:40:42', NULL),
(36, 10, 'fridge', 'fridge', '1', '2018-07-20 13:18:58', '2018-09-06 14:40:42', NULL),
(37, 10, 'air conditioner', 'air-conditioner', '1', '2018-07-20 13:19:11', '2018-09-06 14:40:42', NULL),
(38, 10, 'cable tv', 'cable-tv', '1', '2018-07-20 13:19:25', '2018-09-06 14:40:42', NULL),
(39, 10, 'heating', 'heating', '1', '2018-07-20 13:19:43', '2018-09-06 14:40:42', NULL),
(40, 11, 'security', 'lift', '1', '2018-07-20 13:19:54', '2018-09-06 14:40:42', NULL),
(41, 11, 'cctv', 'cctv', '1', '2018-08-05 09:10:03', '2018-09-06 14:40:43', NULL),
(42, 11, 'water', 'water', '1', '2018-08-05 09:10:24', '2018-09-06 14:40:43', NULL),
(43, 11, 'access control', 'access-control', '1', '2018-08-05 09:10:57', '2018-09-06 14:40:43', NULL),
(44, 11, 'power', 'power', '1', '2018-08-21 10:42:13', '2018-09-06 14:40:42', NULL),
(45, 11, 'toilet', 'toilet', '1', '2018-08-21 10:42:38', '2018-09-06 14:40:42', NULL),
(46, 11, 'lift', 'lift', '1', '2018-08-21 10:42:49', '2018-09-06 14:40:42', NULL),
(47, 11, 'crane', 'crane', '1', '2018-08-21 10:43:02', '2018-09-06 14:40:42', NULL),
(48, 11, 'parking', 'parking', '1', '2018-08-21 10:43:15', '2018-09-06 14:40:42', NULL),
(49, 11, 'nearest railway station', 'nearest-railway-station', '1', '2018-08-21 10:43:31', '2018-09-06 14:40:42', NULL),
(50, 11, 'nearest national highway', 'nearest-nh', '1', '2018-08-21 10:43:49', '2018-09-06 14:40:42', NULL),
(51, 11, 'working hours', 'working-hours', '1', '2018-08-21 10:44:02', '2018-09-06 14:40:42', NULL),
(52, 11, 'working days', 'working-days', '1', '2018-08-21 10:44:13', '2018-09-06 14:40:42', NULL),
(53, 12, 'power', 'power', '1', '2018-08-21 10:44:28', '2018-09-06 14:40:42', NULL),
(54, 12, 'water', 'water', '1', '2018-08-21 10:44:42', '2018-09-06 14:40:42', NULL),
(55, 12, 'toilet', 'toilet', '1', '2018-08-21 10:44:57', '2018-09-06 14:40:42', NULL),
(56, 12, 'lift', 'lift', '1', '2018-08-21 10:45:11', '2018-09-06 14:40:42', NULL),
(57, 12, 'on-site staff', 'on-site-staff', '1', '2018-08-21 10:45:24', '2018-09-06 14:40:42', NULL),
(58, 12, 'parking', 'parking', '1', '2018-08-21 10:45:51', '2018-09-06 14:40:41', NULL),
(59, 12, 'nearest railway station', 'nearest-railway-station', '1', '2018-08-21 10:46:03', '2018-09-06 14:40:41', NULL),
(60, 12, 'nearest bus stop', 'nearest-bus-stop', '1', '2018-08-21 10:46:16', '2018-09-06 14:40:41', NULL),
(61, 12, 'working hours', 'working-hours', '1', '2018-08-21 10:46:29', '2018-09-06 14:40:41', NULL),
(62, 12, 'working days', 'working-days', '1', '2018-08-21 10:46:41', '2018-09-06 14:40:41', NULL),
(63, 12, 'cleaning services', 'cleaning-services', '1', '2018-08-21 10:46:53', '2018-09-06 14:40:41', NULL),
(64, 12, 'conference room', 'conference-room', '1', '2018-08-21 10:47:06', '2018-09-06 14:40:41', NULL),
(65, 12, 'printing', 'printing', '1', '2018-08-21 10:47:19', '2018-09-06 14:37:40', NULL),
(66, 12, 'high speed internet', 'high-speed-internet', '1', '2018-08-21 10:47:37', '2018-09-06 14:37:40', NULL),
(67, 12, 'common areas', 'common-areas', '1', '2018-08-21 10:47:49', '2018-09-06 14:37:39', NULL),
(68, 12, 'refreshments', 'refreshments', '1', '2018-08-21 10:48:00', '2018-09-06 14:37:39', NULL),
(69, 12, 'cctv', 'cctv', '1', '2018-08-21 10:48:10', '2018-09-06 14:37:39', NULL),
(70, 12, 'security', 'security', '1', '2018-08-21 10:48:21', '2018-09-06 14:37:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `api_details`
--

CREATE TABLE `api_details` (
  `id` int(50) NOT NULL,
  `razorpay_id` varchar(255) DEFAULT NULL,
  `razorpay_secret` varchar(255) DEFAULT NULL,
  `razorpay_sandbox_id` text NOT NULL,
  `razorpay_sandbox_secret` text NOT NULL,
  `payment_mode` enum('1','2') NOT NULL DEFAULT '2' COMMENT '1= live mode, 2=sandbox mode',
  `onesignal_api_key` varchar(255) DEFAULT NULL,
  `onesignal_app_id` varchar(255) DEFAULT NULL,
  `onesignal_api_mode` enum('1','2') NOT NULL DEFAULT '2' COMMENT '1= live mode, 2=sandbox mode',
  `onesignal_sandbox_api_key` varchar(255) NOT NULL,
  `onesignal_sandbox_app_id` varchar(255) NOT NULL,
  `mailchimp_api_key` varchar(200) DEFAULT NULL,
  `mailchimp_list_id` varchar(100) DEFAULT NULL,
  `twilio_test_service_sid` varchar(255) DEFAULT NULL,
  `twilio_test_sid` varchar(255) NOT NULL,
  `twilio_test_token` varchar(255) NOT NULL,
  `twilio_service_sid` varchar(255) DEFAULT NULL,
  `twilio_sid` varchar(255) NOT NULL,
  `twilio_token` varchar(255) NOT NULL,
  `twilio_mode` enum('1','2') NOT NULL DEFAULT '2' COMMENT '1= live 2=sandbox',
  `from_user_mobile` varchar(255) NOT NULL,
  `test_from_user_mobile` varchar(255) NOT NULL,
  `freshchat_api_mode` enum('1','2') NOT NULL DEFAULT '2' COMMENT '1= live 2=sandbox',
  `freshchat_api_test_token` varchar(255) NOT NULL,
  `freshchat_api_test_app_id` varchar(255) DEFAULT NULL,
  `freshchat_api_test_app_key` varchar(255) DEFAULT NULL,
  `freshchat_api_token` varchar(255) NOT NULL,
  `freshchat_api_app_id` varchar(255) DEFAULT NULL,
  `freshchat_api_app_key` varchar(255) DEFAULT NULL,
  `facebook_client_id` varchar(255) NOT NULL,
  `facebook_client_secret` varchar(255) NOT NULL,
  `facebook_sandbox_client_id` varchar(255) NOT NULL,
  `facebook_sandbox_client_secret` varchar(255) NOT NULL,
  `facebook_api_mode` enum('1','2') NOT NULL DEFAULT '2' COMMENT '1= live mode, 2=sandbox mode ',
  `twitter_client_id` varchar(255) NOT NULL,
  `twitter_client_secret` varchar(255) NOT NULL,
  `twitter_sandbox_client_id` varchar(255) NOT NULL,
  `twitter_sandbox_client_secret` varchar(255) NOT NULL,
  `twitter_api_mode` enum('1','2') NOT NULL DEFAULT '2' COMMENT '1= live mode, 2=sandbox mode ',
  `google_client_id` varchar(255) NOT NULL,
  `google_client_secret` varchar(255) NOT NULL,
  `google_sandbox_client_id` varchar(255) NOT NULL,
  `google_sandbox_client_secret` varchar(255) NOT NULL,
  `google_api_mode` enum('1','2') NOT NULL DEFAULT '2' COMMENT '1= live mode, 2=sandbox mode ',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `api_details`
--

INSERT INTO `api_details` (`id`, `razorpay_id`, `razorpay_secret`, `razorpay_sandbox_id`, `razorpay_sandbox_secret`, `payment_mode`, `onesignal_api_key`, `onesignal_app_id`, `onesignal_api_mode`, `onesignal_sandbox_api_key`, `onesignal_sandbox_app_id`, `mailchimp_api_key`, `mailchimp_list_id`, `twilio_test_service_sid`, `twilio_test_sid`, `twilio_test_token`, `twilio_service_sid`, `twilio_sid`, `twilio_token`, `twilio_mode`, `from_user_mobile`, `test_from_user_mobile`, `freshchat_api_mode`, `freshchat_api_test_token`, `freshchat_api_test_app_id`, `freshchat_api_test_app_key`, `freshchat_api_token`, `freshchat_api_app_id`, `freshchat_api_app_key`, `facebook_client_id`, `facebook_client_secret`, `facebook_sandbox_client_id`, `facebook_sandbox_client_secret`, `facebook_api_mode`, `twitter_client_id`, `twitter_client_secret`, `twitter_sandbox_client_id`, `twitter_sandbox_client_secret`, `twitter_api_mode`, `google_client_id`, `google_client_secret`, `google_sandbox_client_id`, `google_sandbox_client_secret`, `google_api_mode`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'rzp_test_W85s6c0MFqrZiY', 'pvRdRT0M7LH9ggrW6uEKRpTE', 'rzp_test_iWflLBq6sG91M6', 'dQJ16b505VT2tvIPyCUbdVgw', '1', 'ZTM3ZDY0MDEtYmNhOS00ODcwLWEyN2EtYmQ0ZjA3NzI3ZmI5', '77aeea8a-6e90-448b-b9cb-3f479c2bb145', '1', 'MWEyYzIwYjQtYzNmYi00Nzk1LWIwYjEtZDc0NTg0MGE1OWRi', 'cc10b31b-8b3a-4882-b628-c749799d1933', 'live_974bbfda724d79970492828cd03754b3-us17', 'live_d4d21a5f44', 'MGa42d1e242626f44d9069defea69499c2', 'AC0880dfc90827c6854d8c3818fa867dfa', 'a93d8f7ef3fced164604fb87fd8353f7', 'MG34742f8d1c714941ab84e9b6a85af5cb', 'AC0d72ef821c09037a2040a27be37f3c11', '0959a320906bdcd6d635a399fa052013', '2', '+18646574787', '+18324301796', '2', 'live_6363a232-89d0-48a3-9e0e-574ae2175036', '34cfcd3b-4e21-4c53-a8e6-8758bffd395a', 'THtMUE6rq7RkgSsZ3yCE', 'live_6363a232-89d0-48a3-9e0e-574ae2175036', '34cfcd3b-4e21-4c53-a8e6-8758bffd395a', 'THtMUE6rq7RkgSsZ3yCE', '2020743008178955', '74356934f6e38df0be2414a1f2d1ae85', '2020743008178955', '74356934f6e38df0be2414a1f2d1ae85', '1', 'IwN4Vrpezo7smDBq5ND8Hbbnk', 'k5wnhnUf2SSsmNrFHplwvmoopXbFVPvHP73HEQIpFXyABc6kXz', 'iOErSwtUjQgtZQLUf1cqPRKrk', 'QeV2b3mqp0cQYXfASdihX2qJiLyHVbI3HHmCrR1zCC9ZhPyid9', '1', '86177917358-0c88osmbqsee8rcpqb351a1gglcn2vk8.apps.googleusercontent.com', 'E7arpsuWBsNp_SDYlohlS4vG', '729094495229-o1irhighrr4h7g0ahl2t7n48ivesn71a.apps.googleusercontent.com', 'FbXTfztRwS26N67iYz-JrKRA', '1', '0000-00-00 00:00:00', '2019-02-08 05:17:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(50) NOT NULL,
  `blog_category_id` int(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `blog_image` varchar(255) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0=block 1=unblock',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `blog_category_id`, `title`, `description`, `blog_image`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Spaceship worse', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '15192101305a8d4e92e8cf3.png', '1', '2018-02-21 03:48:50', '2018-03-07 22:49:00', NULL),
(2, 2, 'Tree at Kelwa', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate, tenetur in. Eligendi, deserunt, blanditiis est quisquam doloribus. Lorem ipsum dolor sit amet, consectetur adipisicing elit. CupiditateLorem ipsum', '15192158955a8d651711a98.jpg', '1', '2018-02-21 03:50:38', '2018-03-07 22:48:50', NULL),
(3, 4, 'The Miracle of Singapore', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate, tenetur in. Eligendi, deserunt, blanditiis est quisquam doloribus. Lorem ipsum dolor sit amet, consectetur adipisicing elit. CupiditateLorem ipsum', '15193868065a9000b616096.png', '1', '2018-02-23 04:35:35', '2018-03-07 22:48:53', NULL),
(4, 4, 'The Amazing tokyo', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate, tenetur in. Eligendi, deserunt, blanditiis est quisquam doloribus. Lorem ipsum dolor sit amet, consectetur adipisicing elit.', '15193868195a9000c380219.png', '0', '2018-02-23 04:36:46', '2018-03-07 22:49:58', NULL),
(5, 4, 'test', 'test   ', '15204872025aa0cb22852db.jpg', '1', '2018-03-07 22:33:23', '2018-03-07 22:48:57', NULL),
(6, 1, 'Natural Places', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate, tenetur in. Eligendi, deserunt, blanditiis est quisquam doloribus. Lorem ipsum dolor sit amet, consectetur adipisicing elit. CupiditateLorem ipsum</p>\r\n', '15204890205aa0d23ce4c69.jpg', '1', '2018-03-07 23:03:41', '2018-09-18 10:09:58', NULL),
(7, 3, 'Food In Kerala', '<p><img alt=\"wink\" src=\"http://cdn.ckeditor.com/4.10.0/full/plugins/smiley/images/wink_smile.png\" style=\"height:23px; width:23px\" title=\"wink\" />Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate, tenetur in. Eligendi, deserunt, blanditiis est quisquam doloribus. Lorem ipsum dolor sit amet, consectetur adipisicing elit. CupiditateLorem ipsum</p>\r\n', '15204891615aa0d2c993094.jpg', '1', '2018-03-07 23:06:01', '2018-09-18 10:05:17', NULL),
(8, 3, 'Pasta salad', '   \r\nPasta salad (Pasta fredda)\r\nPasta salad closeup.JPG\r\nA pasta fredda with fusili pasta, tomato and vegetables\r\nType	Salad\r\nCourse	Appetizer, side dish or main course\r\nServing temperature	Chilled\r\nMain ingredients	Pasta, vinegar or oil or mayonnaise\r\n Cookbook: Pasta salad (Pasta fredda)  Media: Pasta salad (Pasta fredda)\r\nPasta salad (Pasta fredda) is a salad dish prepared with one or more types of pasta, usually chilled, and most often tossed in a vinegar, oil, or mayonnaise-based dressing. It is typically served as an appetizer, side dish or a main course. Pasta salad is often regarded as a spring or summertime meal, but it can be served year-round.\r\nThe ingredients used vary widely by region, restaurant, seasonal availability, and/or preference of the preparer. The salad can be as simple as cold macaroni mixed with mayonnaise (a macaroni salad), or as elaborate as several pastas tossed together with a vinaigrette and a variety of fresh, preserved or cooked ingredients. Additional types of pasta may be used, such as ditalini.[1] These can include vegetables, legumes, cheeses, nuts, herbs, spices, meats, poultry, or seafood.[2] Broccoli, carrots, baby corn, cucumbers, olives, onions, beans, chick peas, peppers, and parmesan or feta cheeses are all popular ingredients in versions typically found at North American salad bars.', '15284547145b1a5e3ad474f.jpg', '1', '2018-06-08 05:15:15', '2018-06-08 05:15:15', NULL),
(9, 4, 'Test Desert', '<p>Test DesertTest Desert&nbsp;<img alt=\"frown\" src=\"http://cdn.ckeditor.com/4.10.0/full/plugins/smiley/images/confused_smile.png\" style=\"height:23px; width:23px\" title=\"frown\" />Test Desertv<img alt=\"blush\" src=\"http://cdn.ckeditor.com/4.10.0/full/plugins/smiley/images/embarrassed_smile.png\" style=\"height:23px; width:23px\" title=\"blush\" /></p>\r\n', '15349309445b7d30008ec55.jpg', '1', '2018-08-22 04:12:25', '2018-08-22 04:12:25', NULL),
(10, 4, 'Asassa', '<p>asasas</p>\r\n', '15372676125ba0d79cc42b8.png', '1', '2018-09-18 10:46:53', '2018-09-18 10:46:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blog_category`
--

CREATE TABLE `blog_category` (
  `id` int(50) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0=inactive 1=active	',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blog_category`
--

INSERT INTO `blog_category` (`id`, `category_name`, `slug`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'hot drink', 'hot-drink', '1', '2018-02-21 01:47:35', '2018-03-07 22:47:48', NULL),
(2, 'soft food', 'soft-food', '1', '2018-02-21 02:25:24', '2018-03-07 22:47:48', NULL),
(3, 'new dishes', 'new-dishes', '1', '2018-02-21 02:26:28', '2018-03-07 22:47:48', NULL),
(4, 'desserts', 'desserts', '1', '2018-02-21 02:26:41', '2018-08-22 04:44:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blog_comments`
--

CREATE TABLE `blog_comments` (
  `id` int(50) NOT NULL,
  `blog_id` int(50) NOT NULL,
  `user_id` int(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `status` enum('0','1','','') NOT NULL DEFAULT '1' COMMENT '0=block 1=unblock	',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blog_comments`
--

INSERT INTO `blog_comments` (`id`, `blog_id`, `user_id`, `title`, `comment`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 4, 1, 'Awesome Sunset', 'His is difference from all variations', '1', '2018-02-14 17:00:00', '2018-03-07 22:47:32', NULL),
(2, 4, 1, 'Unclickable', 'No word for that its very changing', '1', '2018-02-09 17:00:00', '2018-03-07 22:47:32', NULL),
(3, 3, 1, 'Nice Display', 'Lorem Ipsum dollar data change', '1', '2018-01-14 17:00:00', '2018-03-07 22:47:32', NULL),
(4, 1, 1, 'Nice Commenting', 'Lorem Ipsum dollar data change', '1', '2018-02-14 02:03:17', '2018-03-07 22:47:32', NULL),
(5, 1, 1, 'BSNL India', 'Lorem Ipsum dollar data change', '1', '2018-01-14 17:00:00', '2018-03-07 22:47:32', NULL),
(7, 2, 1, 'New Area', 'This is the varitions', '0', '2018-02-26 05:10:11', '2018-03-05 04:30:52', NULL),
(8, 2, 1, 'Very Nice ', 'This is something difference', '1', '2018-02-26 23:17:09', '2018-02-26 23:17:09', NULL),
(9, 3, 1, 'it s nice', 'it s nice', '1', '2018-03-07 22:24:34', '2018-03-07 22:24:34', NULL),
(11, 3, 103, 'SCEditor', '[b]Tesxt Test[/b]', '1', '2018-09-05 04:46:16', '2018-09-05 04:46:16', NULL),
(12, 3, 103, 'dfgdfg', '<p>\r\n	<strong>Testinf SCe</strong>\r\n</p>', '1', '2018-09-05 04:50:38', '2018-09-05 04:50:38', NULL),
(13, 3, 103, 'Testting', '<p>\r\n	<strong><span style=\"text-decoration: underline;\">tEST sce</span></strong>\r\n</p>', '1', '2018-09-05 04:51:22', '2018-09-05 04:51:22', NULL),
(14, 3, 103, 'Review From SCEditor', '<p>\r\n	<strong>Review&nbsp;</strong>\r\n</p>', '1', '2018-09-05 05:02:02', '2018-09-05 05:02:02', NULL),
(15, 3, 103, 'Testing', '<p>\r\n	Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate, tenetur in. Eligendi, deserunt, blanditiis est quisquam doloribus. Lorem ipsum dolor sit amet, consectetur adipisicing elit. CupiditateLorem ipsum Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate, tenetur in. Eligendi, deserunt, blanditiis est quisquam doloribus. Lorem ipsum dolor sit amet, consectetur adipisicing elit. CupiditateLorem ipsum\r\n</p>', '1', '2018-09-05 05:07:06', '2018-09-05 05:07:06', NULL),
(16, 3, 103, 'Test', '<p style=\"text-align: right;\">\r\n	<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" /><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" /><span style=\"color: rgb(51, 51, 51); font-family: heebolight; font-size: 14px; font-variant-ligatures: normal; font-variant-caps: normal; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; float: none; display: inline !important;\"><strong style=\"\"><i><span style=\"text-decoration: underline;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate, tenetur in. Eligendi, deserunt, blanditiis est quisquam doloribus. Lorem ipsum dolor sit amet, consectetur adipisicing elit. CupiditateLorem ipsum Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate, tenetur in. Eligendi, deserunt, blanditiis est quisquam doloribus. Lorem ipsum dolor sit amet, consectetur adipisicing elit. CupiditateLorem ipsum</span></i></strong></span><br />\r\n</p>', '1', '2018-09-05 05:11:41', '2018-09-05 05:11:41', NULL),
(17, 3, 103, 'Test', '<p style=\"direction: rtl;\"><font color=\"#44b8ff\" size=\"7\">asdsx scsxcscscasczc zszczx&nbsp; sdcvzxvsdcv zxcvzcsdvc x zczsczx zzccxzxc2018-09-0616:35:02</font></p><p class=\"sceditor-nlf\"><br></p>', '1', '2018-09-06 11:05:14', '2018-09-06 11:05:14', NULL);
INSERT INTO `blog_comments` (`id`, `blog_id`, `user_id`, `title`, `comment`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(18, 3, 103, 'Test fxvxcmxcvmxl', '<p><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\">sdsx scsxcscscasczc zszczx&nbsp; sdcvzxvsdcv zxcvzcsdvc x zczsczx zzccxzxc2018-09-0616:35:0</span><br></p><p><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><br></span></p><p><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\">sdsx scsxcscscasczc zszczx&nbsp; sdcvzxvsdcv zxcvzcsdvc x zczsczx zzccxzxc2018-09-0616:35:0</span><br></span></p><p><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><br></span></span></p><p><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><br></span></span></p><p><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\">sdsx scsxcscscasczc zszczx&nbsp; sdcvzxvsdcv zxcvzcsdvc x zczsczx zzccxzxc2018-09-0616:35:0</span><br></span></span></p><p><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><br></span></span></span></p><p><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><br></span></span></span></p><p><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\">sdsx scsxcscscasczc zszczx&nbsp; sdcvzxvsdcv zxcvzcsdvc x zczsczx zzccxzxc2018-09-0616:35:0</span><br></span></span></span></p><p><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><br></span></span></span></span></p><p><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\">sdsx scsxcscscasczc zszczx&nbsp; sdcvzxvsdcv zxcvzcsdvc x zczsczx zzccxzxc2018-09-0616:35:0</span><br></span></span></span></span></p><p><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><br></span></span></span></span></span></p><p><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\">sdsx scsxcscscasczc zszczx&nbsp; sdcvzxvsdcv zxcvzcsdvc x zczsczx zzccxzxc2018-09-0616:35:0</span><br></span></span></span></span></span></p><p><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><br></span></span></span></span></span></span></p><p><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\">sdsx scsxcscscasczc zszczx&nbsp; sdcvzxvsdcv zxcvzcsdvc x zczsczx zzccxzxc2018-09-0616:35:0</span><br></span></span></span></span></span></span></p><p><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><br></span></span></span></span></span></span></span></p><p><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\">sdsx scsxcscscasczc zszczx&nbsp; sdcvzxvsdcv zxcvzcsdvc x zczsczx zzccxzxc2018-09-0616:35:0</span><br></span></span></span></span></span></span></span></p><p><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><br></span></span></span></span></span></span></span></span></p><p><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><br></span></span></span></span></span></span></span></span></p><p><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\"><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\"><span style=\"color: rgb(68, 184, 255); font-family: heebolight; font-size: -webkit-xxx-large; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.5px; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; display: inline !important; float: none;\">sdsx scsxcscscasczc zszczx&nbsp; sdcvzxvsdcv zxcvzcsdvc x zczsczx zzccxzxc2018-09-0616:35:0</span><br></span></span></span></span></span></span></span></span></p>', '1', '2018-09-06 11:12:34', '2018-09-06 11:12:34', NULL);
INSERT INTO `blog_comments` (`id`, `blog_id`, `user_id`, `title`, `comment`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(19, 1, 52, 'Good', '<p>\r\n	<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	<br /><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, <g data-gr-id=\"11\" id=\"11\" class=\"gr_ gr_11 gr-alert gr_spell gr_inline_cards gr_disable_anim_appear ContextualSpelling\">consectetur</g>, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the <g data-gr-id=\"12\" id=\"12\" class=\"gr_ gr_12 gr-alert gr_spell gr_inline_cards gr_disable_anim_appear ContextualSpelling\">undoubtable</g> source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32.\r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.<br /><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, <g data-gr-id=\"53\" id=\"53\" class=\"gr_ gr_53 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling\">consectetur</g>, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the <g data-gr-id=\"55\" id=\"55\" class=\"gr_ gr_55 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling\">undoubtable</g> source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32.\r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.\r\n</p>\r\n<p>\r\n	<br /> Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, <g data-gr-id=\"25\" id=\"25\" class=\"gr_ gr_25 gr-alert gr_spell gr_inline_cards gr_disable_anim_appear ContextualSpelling\">consectetur</g>, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the <g data-gr-id=\"27\" id=\"27\" class=\"gr_ gr_27 gr-alert gr_spell gr_inline_cards gr_disable_anim_appear ContextualSpelling\">undoubtable</g> source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32.\r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	 The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.<br /><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, <g data-gr-id=\"39\" id=\"39\" class=\"gr_ gr_39 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling\">consectetur</g>, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the <g data-gr-id=\"41\" id=\"41\" class=\"gr_ gr_41 gr-alert gr_spell gr_inline_cards gr_run_anim ContextualSpelling\">undoubtable</g> source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32.\r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.\r\n</p>\r\n<p>\r\n	<br /><br />\r\n</p>', '1', '2018-09-12 05:11:35', '2018-09-12 05:11:35', NULL),
(20, 1, 52, 'Good', '<p>\r\n	<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham. <br /><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham. <br /><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham. <br /><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham. <br /><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham. <br /><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham. <br /><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham. <br /><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham. <meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham. <meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham. <meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial;\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham. \r\n</p>\r\n<p>\r\n	<br /><br /><br /><br /><br /><br /><br /><br />\r\n</p>', '1', '2018-09-12 05:12:11', '2018-09-12 05:12:11', NULL);
INSERT INTO `blog_comments` (`id`, `blog_id`, `user_id`, `title`, `comment`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(21, 1, 52, 'Abcd', '<p>\r\n	<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.<span>&nbsp;</span><br style=\"box-sizing: border-box;\" /> \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.<span>&nbsp;</span><br style=\"box-sizing: border-box;\" /> \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.<span>&nbsp;</span><br style=\"box-sizing: border-box;\" /> \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.<span>&nbsp;</span><br style=\"box-sizing: border-box;\" /> \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.<span>&nbsp;</span><br style=\"box-sizing: border-box;\" /> \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.<span>&nbsp;</span><br style=\"box-sizing: border-box;\" /> \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.<span>&nbsp;</span><br style=\"box-sizing: border-box;\" /> \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham <br /><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.<span>&nbsp;</span><br style=\"box-sizing: border-box;\" /> \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.<span>&nbsp;</span><br style=\"box-sizing: border-box;\" /> \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.<span>&nbsp;</span><br style=\"box-sizing: border-box;\" /> \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.<span>&nbsp;</span><br style=\"box-sizing: border-box;\" /> \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.<span>&nbsp;</span><br style=\"box-sizing: border-box;\" /> \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.<span>&nbsp;</span><br style=\"box-sizing: border-box;\" /> \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.<span>&nbsp;</span><br style=\"box-sizing: border-box;\" /> \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-style: initial; text-decoration-color: initial; padding: 0px; text-align: justify; color: rgb(0, 0, 0);\">\r\n	The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham. \r\n</p>\r\n<p open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" font-size:=\"\" 14px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" white-space:=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-style:=\"\" initial;=\"\" text-decoration-color:=\"\" initial;\"=\"\" style=\"box-sizing: border-box; margin: 0px 0px 15px; font-family: heebolight; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: 0.5px; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb', '1', '2018-09-12 08:36:39', '2018-09-12 08:36:39', NULL);
INSERT INTO `blog_comments` (`id`, `blog_id`, `user_id`, `title`, `comment`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(22, 2, 103, 'Test', '<p>\r\n	This is testing...<img title=\":)\" alt=\":)\" data-sceditor-emoticon=\":)\" src=\"http://192.168.1.71/shareous/front/sceditor/emoticons/smile.png\" /><br />\r\n</p>', '1', '2018-09-14 10:10:15', '2018-09-14 10:10:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blog_ip_address`
--

CREATE TABLE `blog_ip_address` (
  `id` int(50) NOT NULL,
  `blog_id` int(50) NOT NULL,
  `ip_address` varchar(250) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blog_ip_address`
--

INSERT INTO `blog_ip_address` (`id`, `blog_id`, `ip_address`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 0, '192.168.1.5', '2018-03-04 23:13:31', '2018-03-05 04:43:51', NULL),
(4, 1, '192.168.1.5', '2018-03-04 23:13:57', '2018-03-04 23:13:57', NULL),
(5, 2, '192.168.1.5', '2018-03-04 23:22:52', '2018-03-04 23:22:52', NULL),
(6, 1, '::1', '2018-03-04 23:29:27', '2018-03-04 23:29:27', NULL),
(7, 3, '192.168.1.5', '2018-03-05 00:14:28', '2018-03-05 00:14:28', NULL),
(8, 4, '192.168.1.5', '2018-03-05 00:14:42', '2018-03-05 00:14:42', NULL),
(9, 3, '192.168.1.70', '2018-03-07 22:23:12', '2018-03-07 22:23:12', NULL),
(10, 6, '192.168.1.5', '2018-03-09 03:52:35', '2018-03-09 03:52:35', NULL),
(11, 7, '192.168.1.5', '2018-03-09 04:08:06', '2018-03-09 04:08:06', NULL),
(12, 1, '192.168.1.66', '2018-03-12 04:55:43', '2018-03-12 04:55:43', NULL),
(13, 2, '192.168.1.66', '2018-03-12 05:40:19', '2018-03-12 05:40:19', NULL),
(14, 3, '192.168.1.66', '2018-03-12 05:40:25', '2018-03-12 05:40:25', NULL),
(15, 7, '192.168.1.66', '2018-03-12 05:40:53', '2018-03-12 05:40:53', NULL),
(16, 2, '103.69.226.62', '2018-04-26 14:46:57', '2018-04-26 14:46:57', NULL),
(17, 3, '103.69.226.62', '2018-05-31 03:13:10', '2018-05-31 03:13:10', NULL),
(18, 8, '103.69.226.62', '2018-06-08 05:15:49', '2018-06-08 05:15:49', NULL),
(19, 6, '103.69.226.62', '2018-06-08 05:24:14', '2018-06-08 05:24:14', NULL),
(20, 1, '103.69.226.62', '2018-06-08 07:52:55', '2018-06-08 07:52:55', NULL),
(21, 6, '1.186.237.179', '2018-06-10 22:27:51', '2018-06-10 22:27:51', NULL),
(22, 3, '1.186.237.179', '2018-06-11 06:01:22', '2018-06-11 06:01:22', NULL),
(23, 1, '1.186.237.179', '2018-06-11 06:38:57', '2018-06-11 06:38:57', NULL),
(24, 5, '103.69.226.62', '2018-06-18 00:53:24', '2018-06-18 00:53:24', NULL),
(25, 1, '103.69.225.247', '2018-07-24 08:49:44', '2018-07-24 08:49:44', NULL),
(26, 8, '103.69.225.247', '2018-07-24 08:50:28', '2018-07-24 08:50:28', NULL),
(27, 1, '43.224.130.153', '2018-08-12 05:00:39', '2018-08-12 05:00:39', NULL),
(28, 9, '192.168.1.118', '2018-08-22 04:13:20', '2018-08-22 04:13:20', NULL),
(29, 3, '192.168.1.118', '2018-08-22 04:29:12', '2018-08-22 04:29:12', NULL),
(30, 7, '192.168.1.118', '2018-08-22 04:36:18', '2018-08-22 04:36:18', NULL),
(31, 1, '192.168.1.118', '2018-09-03 13:53:53', '2018-09-03 13:53:53', NULL),
(32, 2, '192.168.1.7', '2018-09-04 15:44:28', '2018-09-04 15:44:28', NULL),
(33, 3, '192.168.1.7', '2018-09-05 04:11:17', '2018-09-05 04:11:17', NULL),
(34, 3, '192.168.1.154', '2018-09-05 04:13:31', '2018-09-05 04:13:31', NULL),
(35, 1, '192.168.1.7', '2018-09-05 04:34:10', '2018-09-05 04:34:10', NULL),
(36, 2, '::1', '2018-09-06 09:00:14', '2018-09-06 09:00:14', NULL),
(37, 3, '::1', '2018-09-06 11:04:23', '2018-09-06 11:04:23', NULL),
(38, 1, '192.168.1.86', '2018-09-07 04:31:16', '2018-09-07 04:31:16', NULL),
(39, 5, '192.168.1.86', '2018-09-07 07:08:27', '2018-09-07 07:08:27', NULL),
(40, 1, '192.168.1.6', '2018-09-12 05:10:43', '2018-09-12 05:10:43', NULL),
(41, 3, '192.168.1.6', '2018-09-12 08:55:16', '2018-09-12 08:55:16', NULL),
(42, 1, '192.168.1.71', '2018-09-14 09:07:45', '2018-09-14 09:07:45', NULL),
(43, 2, '192.168.1.71', '2018-09-14 09:08:04', '2018-09-14 09:08:04', NULL),
(44, 3, '192.168.1.71', '2018-09-14 09:53:07', '2018-09-14 09:53:07', NULL),
(45, 1, '192.168.1.50', '2018-09-18 10:34:23', '2018-09-18 10:34:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `booking_id` varchar(255) NOT NULL,
  `property_id` int(11) NOT NULL,
  `property_owner_id` int(11) NOT NULL,
  `property_booked_by` int(11) NOT NULL,
  `payment_type` enum('booking','wallet') DEFAULT NULL,
  `coupon_code_id` int(11) NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `no_of_guest` int(11) NOT NULL,
  `no_of_days` int(11) NOT NULL,
  `property_amount` float NOT NULL,
  `service_fee` decimal(12,2) NOT NULL,
  `service_fee_percentage` decimal(12,2) DEFAULT NULL,
  `service_fee_gst_percentage` decimal(12,2) DEFAULT NULL,
  `service_fee_gst_amount` decimal(12,2) DEFAULT NULL,
  `gst_amount` decimal(12,2) NOT NULL,
  `gst_percentage` decimal(12,2) DEFAULT NULL,
  `total_night_price` decimal(12,2) NOT NULL,
  `host_accepted_date` date NOT NULL,
  `host_rejected_date` date NOT NULL,
  `coupen_code_amount` float NOT NULL,
  `admin_commission` decimal(12,2) NOT NULL,
  `refund_amount` decimal(12,2) NOT NULL,
  `total_amount` double NOT NULL,
  `booking_status` enum('1','2','3','4','5','6','7') NOT NULL COMMENT '1-accepted,2-confirmed,3-awaiting,4-rejected,5-completed,6-cancelled,7-process_cancel',
  `cancelled_by` enum('1','4') DEFAULT NULL,
  `cancelled_reason` text,
  `cancelled_date` date NOT NULL DEFAULT '0000-00-00',
  `reject_reason` text NOT NULL,
  `status_by` int(11) NOT NULL,
  `property_type_slug` text,
  `selected_no_of_slots` float DEFAULT NULL,
  `selected_of_employee` float DEFAULT NULL,
  `selected_of_room` decimal(10,0) NOT NULL,
  `selected_of_desk` float NOT NULL,
  `selected_of_cubicles` float NOT NULL,
  `room_amount` float DEFAULT NULL,
  `desk_amount` float DEFAULT NULL,
  `cubicles_amount` float DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `booking_id`, `property_id`, `property_owner_id`, `property_booked_by`, `payment_type`, `coupon_code_id`, `check_in_date`, `check_out_date`, `no_of_guest`, `no_of_days`, `property_amount`, `service_fee`, `service_fee_percentage`, `service_fee_gst_percentage`, `service_fee_gst_amount`, `gst_amount`, `gst_percentage`, `total_night_price`, `host_accepted_date`, `host_rejected_date`, `coupen_code_amount`, `admin_commission`, `refund_amount`, `total_amount`, `booking_status`, `cancelled_by`, `cancelled_reason`, `cancelled_date`, `reject_reason`, `status_by`, `property_type_slug`, `selected_no_of_slots`, `selected_of_employee`, `selected_of_room`, `selected_of_desk`, `selected_of_cubicles`, `room_amount`, `desk_amount`, `cubicles_amount`, `created_at`, `updated_at`) VALUES
(1, 'B000001', 189, 52, 103, 'booking', 0, '2018-10-04', '2018-10-05', 0, 1, 50, '0.00', NULL, NULL, NULL, '0.00', NULL, '12500.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 12500, '5', NULL, NULL, '0000-00-00', '', 0, 'warehouse', 5, 0, '0', 0, 0, NULL, NULL, NULL, '2018-10-03 13:14:41', '2018-10-03 13:15:13'),
(2, 'B000002', 189, 52, 103, 'booking', 0, '2018-10-04', '2018-10-05', 0, 1, 50, '0.00', NULL, NULL, NULL, '0.00', NULL, '12500.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 12500, '5', NULL, NULL, '0000-00-00', '', 0, 'warehouse', 5, 0, '0', 0, 0, NULL, NULL, NULL, '2018-10-03 13:15:56', '2018-10-03 13:16:20'),
(4, 'B000004', 189, 52, 103, 'booking', 0, '2018-10-21', '2018-10-27', 0, 6, 50, '0.00', NULL, NULL, NULL, '0.00', NULL, '150000.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 150000, '6', '1', NULL, '0000-00-00', '', 103, 'warehouse', 10, 0, '0', 0, 0, NULL, NULL, NULL, '2018-10-03 13:20:43', '2018-10-20 09:45:27'),
(5, 'B000005', 189, 52, 103, 'booking', 0, '2018-10-16', '2018-10-19', 0, 3, 50, '0.00', NULL, NULL, NULL, '0.00', NULL, '37500.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 37500, '5', NULL, NULL, '0000-00-00', '', 0, 'warehouse', 5, 0, '0', 0, 0, NULL, NULL, NULL, '2018-10-03 13:21:38', '2018-10-03 13:23:04'),
(6, 'B000006', 189, 52, 103, 'booking', 0, '2018-10-16', '2018-10-18', 0, 2, 50, '0.00', NULL, NULL, NULL, '0.00', NULL, '25000.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 25000, '5', NULL, NULL, '0000-00-00', '', 0, 'warehouse', 5, 0, '0', 0, 0, NULL, NULL, NULL, '2018-10-03 13:23:49', '2018-10-03 13:24:12'),
(7, 'B000007', 124, 52, 103, 'booking', 0, '2018-10-10', '2018-10-11', 1, 1, 3500, '0.00', NULL, NULL, NULL, '0.00', NULL, '3500.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 3500, '7', '1', NULL, '0000-00-00', '', 103, 'banglow', 0, 0, '0', 0, 0, NULL, NULL, NULL, '2018-10-04 13:31:42', '2018-10-05 10:11:03'),
(8, 'B000008', 124, 52, 103, 'booking', 0, '2018-10-12', '2018-10-13', 1, 1, 3500, '0.00', NULL, NULL, NULL, '0.00', NULL, '3500.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 3500, '5', NULL, NULL, '0000-00-00', '', 0, 'banglow', 0, 0, '0', 0, 0, NULL, NULL, NULL, '2018-10-04 13:41:19', '2018-10-04 13:42:55'),
(9, 'B000009', 124, 52, 103, 'booking', 0, '2018-10-14', '2018-10-15', 1, 1, 3500, '0.00', NULL, NULL, NULL, '0.00', NULL, '3500.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 3500, '5', NULL, NULL, '0000-00-00', '', 0, 'banglow', 0, 0, '0', 0, 0, NULL, NULL, NULL, '2018-10-04 13:48:01', '2018-10-04 13:48:23'),
(10, 'B000010', 124, 52, 103, 'booking', 0, '2018-10-14', '2018-10-15', 2, 1, 3500, '0.00', NULL, NULL, NULL, '0.00', NULL, '7000.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 7000, '5', NULL, NULL, '0000-00-00', '', 0, 'banglow', 0, 0, '0', 0, 0, NULL, NULL, NULL, '2018-10-04 13:48:43', '2018-10-04 13:49:06'),
(11, 'B000011', 136, 52, 103, NULL, 0, '2018-10-23', '2018-10-24', 0, 1, 400.92, '0.00', NULL, NULL, NULL, '0.00', NULL, '40092.15', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 40092.15, '3', NULL, 'Auto cancelled for payment not completed', '2018-10-17', '', 0, 'warehouse', 10, 0, '0', 0, 0, NULL, NULL, NULL, '2018-10-17 07:11:19', '2018-10-17 11:40:57'),
(12, 'B000012', 136, 52, 103, NULL, 0, '2018-10-31', '2018-11-01', 0, 1, 400.92, '0.00', NULL, NULL, NULL, '0.00', NULL, '4009.22', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 4009.22, '3', NULL, 'Auto cancelled for payment not completed', '2018-10-17', '', 0, 'warehouse', 1, 0, '0', 0, 0, NULL, NULL, NULL, '2018-10-17 07:12:33', '2018-10-17 11:40:53'),
(13, 'B000013', 136, 52, 103, NULL, 0, '2018-10-17', '2018-10-18', 0, 1, 400.92, '0.00', NULL, NULL, NULL, '0.00', NULL, '4009.22', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 4009.22, '3', NULL, 'Auto cancelled for payment not completed', '2018-10-17', '', 0, 'warehouse', 1, 0, '0', 0, 0, NULL, NULL, NULL, '2018-10-17 08:30:15', '2018-10-17 11:37:13'),
(14, 'B000014', 124, 52, 103, NULL, 0, '2018-10-22', '2018-10-23', 1, 1, 3500, '0.00', NULL, NULL, NULL, '0.00', NULL, '3500.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 3500, '3', NULL, NULL, '0000-00-00', '', 0, 'banglow', 0, 0, '0', 0, 0, NULL, NULL, NULL, '2018-10-22 05:59:29', '2018-10-22 06:19:31'),
(15, 'B000015', 189, 52, 103, 'booking', 0, '2018-10-28', '2018-10-29', 0, 1, 50, '0.00', NULL, NULL, NULL, '0.00', NULL, '2500.00', '0000-00-00', '0000-00-00', 0, '12.15', '1500.00', 2500, '6', '1', NULL, '2018-10-22', '', 103, 'warehouse', 1, 0, '0', 0, 0, NULL, NULL, NULL, '2018-10-22 09:48:14', '2018-10-22 10:03:11'),
(16, 'B000016', 136, 52, 103, 'booking', 0, '2018-10-25', '2018-10-26', 0, 1, 400.92, '0.00', NULL, NULL, NULL, '0.00', NULL, '4009.22', '0000-00-00', '0000-00-00', 0, '12.15', '1059.22', 4009.22, '6', '1', NULL, '2018-10-22', '', 103, 'warehouse', 1, 0, '0', 0, 0, NULL, NULL, NULL, '2018-10-22 10:09:06', '2018-10-22 10:36:43'),
(17, 'B000017', 136, 52, 103, 'wallet', 0, '2018-11-01', '2018-11-02', 0, 1, 400.92, '0.00', NULL, NULL, NULL, '0.00', NULL, '4009.22', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 4009.22, '5', NULL, NULL, '0000-00-00', '', 0, 'warehouse', 1, 0, '0', 0, 0, NULL, NULL, NULL, '2018-10-22 11:34:03', '2018-10-22 11:36:14'),
(18, 'B000018', 189, 52, 103, NULL, 0, '2018-10-28', '2018-10-29', 0, 1, 50, '0.00', NULL, NULL, NULL, '0.00', NULL, '2500.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 2500, '3', NULL, NULL, '0000-00-00', '', 0, 'warehouse', 1, 0, '0', 0, 0, NULL, NULL, NULL, '2018-10-22 12:13:41', '2018-10-22 12:19:50'),
(19, 'B000019', 201, 52, 103, 'booking', 0, '2018-10-24', '2018-10-25', 0, 1, 25, '0.00', NULL, NULL, NULL, '0.00', NULL, '2525.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 2525, '6', '1', NULL, '0000-00-00', '', 103, 'office-space', 0, 8, '20', 31, 42, NULL, NULL, NULL, '2018-10-24 04:48:23', '2018-10-24 12:54:47'),
(20, 'B000020', 201, 52, 103, 'booking', 0, '2018-10-24', '2018-10-25', 0, 1, 25, '0.00', NULL, NULL, NULL, '0.00', NULL, '275.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 275, '5', NULL, NULL, '0000-00-00', '', 0, 'office-space', 0, 2, '0', 0, 0, NULL, NULL, NULL, '2018-10-24 10:06:42', '2018-10-24 10:07:01'),
(21, 'B000021', 201, 52, 103, 'booking', 0, '2018-10-24', '2018-10-25', 0, 1, 25, '0.00', NULL, NULL, NULL, '0.00', NULL, '125.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 125, '5', NULL, NULL, '0000-00-00', '', 0, 'office-space', 0, 2, '0', 0, 0, NULL, NULL, NULL, '2018-10-24 10:08:28', '2018-10-24 10:08:49'),
(22, 'B000022', 201, 52, 103, 'booking', 0, '2018-10-26', '2018-10-27', 0, 1, 25, '0.00', NULL, NULL, NULL, '0.00', NULL, '2700.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 2700, '5', NULL, NULL, '0000-00-00', '', 0, 'office-space', 0, 9, '0', 0, 0, NULL, NULL, NULL, '2018-10-24 10:09:41', '2018-10-24 10:10:09'),
(23, 'B000023', 201, 52, 103, 'booking', 0, '2018-10-28', '2018-10-29', 0, 1, 25, '0.00', NULL, NULL, NULL, '0.00', NULL, '2700.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 2700, '5', NULL, NULL, '0000-00-00', '', 0, 'office-space', 0, 9, '0', 0, 0, NULL, NULL, NULL, '2018-10-24 10:12:28', '2018-10-24 10:12:49'),
(24, 'B000024', 202, 52, 103, 'booking', 0, '2018-10-24', '2018-10-25', 0, 1, 40, '0.00', NULL, NULL, NULL, '0.00', NULL, '1840.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 1840, '5', NULL, NULL, '0000-00-00', '', 0, 'office-space', 0, 45, '0', 0, 0, NULL, NULL, NULL, '2018-10-24 10:14:16', '2018-10-24 10:14:39'),
(25, 'B000025', 202, 52, 103, 'booking', 0, '2018-10-24', '2018-10-25', 0, 1, 40, '0.00', NULL, NULL, NULL, '0.00', NULL, '80.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 80, '5', NULL, NULL, '0000-00-00', '', 0, 'office-space', 0, 1, '0', 0, 0, NULL, NULL, NULL, '2018-10-24 10:26:39', '2018-10-24 10:26:59'),
(26, 'B000026', 202, 52, 103, 'booking', 0, '2018-10-24', '2018-10-25', 0, 1, 40, '0.00', NULL, NULL, NULL, '0.00', NULL, '80.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 80, '5', NULL, NULL, '0000-00-00', '', 0, 'office-space', 0, 1, '0', 1, 0, NULL, NULL, NULL, '2018-10-24 11:15:37', '2018-10-24 11:21:59'),
(27, 'B000027', 202, 52, 103, 'booking', 0, '2018-10-24', '2018-10-25', 0, 1, 40, '0.00', NULL, NULL, NULL, '0.00', NULL, '80.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 80, '5', NULL, NULL, '0000-00-00', '', 0, 'office-space', 0, 1, '0', 1, 0, NULL, NULL, NULL, '2018-10-24 11:22:36', '2018-10-24 11:22:54'),
(28, 'B000028', 202, 52, 103, NULL, 0, '2018-10-24', '2018-10-25', 0, 1, 40, '0.00', NULL, NULL, NULL, '0.00', NULL, '80.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 80, '3', NULL, NULL, '0000-00-00', '', 0, 'office-space', 0, 2, '0', 0, 0, NULL, NULL, NULL, '2018-10-24 11:23:20', '2018-10-24 11:23:28'),
(29, 'B000029', 202, 52, 103, 'booking', 0, '2018-10-26', '2018-10-27', 0, 1, 40, '0.00', NULL, NULL, NULL, '0.00', NULL, '1960.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 1960, '5', NULL, NULL, '0000-00-00', '', 0, 'office-space', 0, 47, '0', 2, 0, NULL, NULL, NULL, '2018-10-24 11:24:13', '2018-10-24 11:24:36'),
(30, 'B000030', 202, 52, 103, NULL, 0, '2018-10-26', '2018-10-27', 0, 1, 40, '0.00', NULL, NULL, NULL, '0.00', NULL, '120.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 120, '3', NULL, NULL, '0000-00-00', '', 0, 'office-space', 0, 3, '0', 0, 0, NULL, NULL, NULL, '2018-10-24 11:25:07', '2018-10-24 11:25:20'),
(31, 'B000031', 202, 52, 103, 'booking', 0, '2018-10-28', '2018-10-29', 0, 1, 40, '0.00', NULL, NULL, NULL, '0.00', NULL, '1920.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 1920, '5', NULL, NULL, '0000-00-00', '', 0, 'office-space', 0, 46, '0', 2, 0, NULL, NULL, NULL, '2018-10-24 11:35:00', '2018-10-24 11:35:21'),
(32, 'B000032', 202, 52, 103, 'booking', 0, '2018-10-28', '2018-10-29', 0, 1, 40, '0.00', NULL, NULL, NULL, '0.00', NULL, '160.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 160, '5', NULL, NULL, '0000-00-00', '', 0, 'office-space', 0, 4, '0', 0, 0, NULL, NULL, NULL, '2018-10-24 11:36:26', '2018-10-24 11:36:50'),
(33, 'B000033', 136, 52, 103, NULL, 0, '2018-10-25', '2018-10-26', 0, 1, 400.92, '0.00', NULL, NULL, NULL, '0.00', NULL, '4009.22', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 4009.22, '3', NULL, NULL, '0000-00-00', '', 0, 'warehouse', 1, 0, '0', 0, 0, NULL, NULL, NULL, '2018-10-25 04:26:30', '2018-10-25 04:26:42'),
(34, 'B000034', 201, 52, 103, NULL, 0, '2018-11-01', '2018-11-13', 0, 12, 25, '0.00', NULL, NULL, NULL, '0.00', NULL, '2700.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 2700, '3', NULL, NULL, '0000-00-00', '', 0, 'office-space', 0, 1, '1', 3, 4, NULL, NULL, NULL, '2018-10-25 06:59:43', '2018-10-25 07:00:53'),
(35, 'B000035', 201, 52, 103, 'wallet', 0, '2018-11-01', '2018-11-02', 0, 1, 25, '0.00', NULL, NULL, NULL, '0.00', NULL, '350.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 350, '5', NULL, NULL, '0000-00-00', '', 0, 'office-space', 0, 2, '3', 4, 5, NULL, NULL, NULL, '2018-10-25 07:06:32', '2018-10-25 09:25:09'),
(36, 'B000036', 201, 52, 103, NULL, 0, '2018-11-01', '2018-11-02', 0, 1, 25, '0.00', NULL, NULL, NULL, '0.00', NULL, '450.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 450, '3', NULL, NULL, '0000-00-00', '', 0, 'office-space', 0, 3, '4', 5, 6, NULL, NULL, NULL, '2018-10-25 09:25:45', '2018-10-25 09:26:03'),
(37, 'B000037', 136, 52, 103, NULL, 0, '2018-11-01', '2018-11-02', 0, 1, 400.92, '0.00', NULL, NULL, NULL, '0.00', NULL, '8018.43', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 8018.43, '3', NULL, NULL, '0000-00-00', '', 0, 'warehouse', 2, 0, '0', 0, 0, NULL, NULL, NULL, '2018-10-25 09:43:38', '2018-10-25 09:43:38'),
(38, 'B000038', 27, 5, 103, 'wallet', 0, '2018-11-01', '2018-11-02', 5, 1, 68492.5, '0.00', NULL, NULL, NULL, '0.00', NULL, '342462.50', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 342462.5, '5', NULL, NULL, '0000-00-00', '', 0, 'home', 0, 0, '0', 0, 0, NULL, NULL, NULL, '2018-10-25 10:00:12', '2018-10-25 10:00:52'),
(39, 'B000039', 205, 52, 103, 'booking', 0, '2018-11-01', '2018-11-02', 0, 1, 0, '0.00', NULL, NULL, NULL, '0.00', NULL, '75.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 75, '5', NULL, NULL, '0000-00-00', '', 0, 'office-space', 0, 0, '1', 1, 1, NULL, NULL, NULL, '2018-10-30 07:11:24', '2018-10-30 07:11:53'),
(40, 'B000040', 205, 52, 103, 'booking', 0, '2018-11-22', '2018-11-23', 0, 1, 0, '0.00', NULL, NULL, NULL, '0.00', NULL, '75.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 75, '7', '1', NULL, '0000-00-00', '', 103, 'office-space', 0, 0, '1', 1, 1, 20, 25, 30, '2018-10-30 08:46:36', '2018-11-22 05:07:03'),
(41, 'B000041', 136, 52, 103, NULL, 0, '2018-11-21', '2018-11-22', 0, 1, 400.92, '0.00', NULL, NULL, NULL, '0.00', NULL, '4009.22', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 4009.22, '3', NULL, NULL, '0000-00-00', '', 0, 'warehouse', 1, 0, '0', 0, 0, 0, 0, 0, '2018-11-21 10:14:18', '2018-11-21 10:14:18'),
(42, 'B000042', 136, 52, 103, 'wallet', 0, '2018-11-22', '2018-11-23', 0, 1, 400.92, '0.00', NULL, NULL, NULL, '0.00', NULL, '4009.22', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 4009.22, '5', NULL, NULL, '0000-00-00', '', 0, 'warehouse', 1, 0, '0', 0, 0, 0, 0, 0, '2018-11-21 10:36:55', '2018-11-21 11:07:51'),
(43, 'B000043', 192, 52, 103, 'booking', 0, '2018-12-04', '2018-12-05', 1, 1, 500, '60.75', NULL, NULL, NULL, '0.00', NULL, '500.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 500, '5', NULL, NULL, '0000-00-00', '', 0, 'banglow', 0, 0, '0', 0, 0, 0, 0, 0, '2018-12-04 10:27:28', '2018-12-04 10:27:57'),
(44, 'B000044', 192, 52, 103, 'booking', 0, '2018-12-06', '2018-12-07', 1, 1, 500.35, '0.01', NULL, NULL, NULL, '0.00', NULL, '500.35', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 500.35, '5', NULL, NULL, '0000-00-00', '', 0, 'banglow', 0, 0, '0', 0, 0, 0, 0, 0, '2018-12-04 10:37:50', '2018-12-04 10:38:13'),
(45, 'B000045', 150, 27, 103, 'booking', 0, '2018-12-04', '2018-12-05', 1, 1, 41095.5, '6391.17', NULL, NULL, NULL, '11506.74', NULL, '52602.24', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 52602.24, '5', NULL, NULL, '0000-00-00', '', 0, 'banglow', 0, 0, '0', 0, 0, 0, 0, 0, '2018-12-04 10:53:25', '2018-12-04 10:53:56'),
(46, 'B000046', 192, 52, 103, NULL, 0, '2018-12-10', '2018-12-11', 1, 1, 500, '60.75', NULL, NULL, NULL, '0.00', NULL, '500.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 500, '3', NULL, NULL, '0000-00-00', '', 0, 'banglow', 0, 0, '0', 0, 0, 0, 0, 0, '2018-12-10 10:19:27', '2018-12-10 10:19:41'),
(47, 'B000047', 192, 52, 103, 'booking', 0, '2018-12-12', '2018-12-13', 1, 1, 500, '60.75', NULL, NULL, NULL, '0.00', NULL, '500.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 500, '5', NULL, NULL, '0000-00-00', '', 0, 'banglow', 0, 0, '0', 0, 0, 0, 0, 0, '2018-12-10 10:20:32', '2018-12-10 10:20:54'),
(48, 'B000048', 150, 27, 103, 'booking', 0, '2018-12-10', '2018-12-11', 1, 1, 41095.5, '6391.17', NULL, NULL, NULL, '11506.74', NULL, '52602.24', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 52602.24, '5', NULL, NULL, '0000-00-00', '', 0, 'banglow', 0, 0, '0', 0, 0, 0, 0, 0, '2018-12-10 10:25:45', '2018-12-10 10:26:09'),
(49, 'B000049', 124, 52, 103, 'booking', 0, '2018-12-10', '2018-12-11', 1, 1, 3500, '501.80', NULL, NULL, NULL, '630.00', NULL, '4130.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 4130, '5', NULL, NULL, '0000-00-00', '', 0, 'banglow', 0, 0, '0', 0, 0, 0, 0, 0, '2018-12-10 10:35:34', '2018-12-10 10:35:58'),
(50, 'B000050', 205, 52, 115, NULL, 0, '2018-12-11', '2018-12-12', 0, 1, 0, '2.43', NULL, NULL, NULL, '0.00', NULL, '20.00', '0000-00-00', '0000-00-00', 0, '12.15', '0.00', 20, '3', NULL, NULL, '0000-00-00', '', 0, 'office-space', 0, 0, '1', 0, 0, 20, 25, 30, '2018-12-11 06:54:19', '2018-12-11 06:54:44'),
(51, 'B000051', 124, 52, 103, 'booking', 0, '2018-12-14', '2018-12-15', 1, 1, 3500, '411.47', NULL, NULL, NULL, '630.00', NULL, '4130.00', '0000-00-00', '0000-00-00', 0, '0.00', '0.00', 4130, '5', NULL, NULL, '0000-00-00', '', 0, 'banglow', 0, 0, '0', 0, 0, 0, 0, 0, '2018-12-14 08:46:06', '2018-12-14 08:46:38'),
(52, 'B000052', 123, 52, 103, 'booking', 0, '2018-12-14', '2018-12-15', 1, 1, 4500, '529.04', '12.15', '18.00', '116.13', '810.00', NULL, '5310.00', '0000-00-00', '0000-00-00', 0, '0.00', '0.00', 5310, '5', NULL, NULL, '0000-00-00', '', 0, 'banglow', 0, 0, '0', 0, 0, 0, 0, 0, '2018-12-14 08:52:19', '2018-12-14 08:52:45'),
(53, 'B000053', 113, 52, 103, NULL, 0, '2018-12-14', '2018-12-15', 1, 1, 3500, '411.47', '12.15', '18.00', '90.32', '630.00', '18.00', '4631.79', '0000-00-00', '0000-00-00', 0, '0.00', '0.00', 4631.79, '3', NULL, NULL, '0000-00-00', '', 0, 'banglow', 0, 0, '0', 0, 0, 0, 0, 0, '2018-12-14 09:45:43', '2018-12-14 09:45:53'),
(54, 'B000054', 111, 52, 103, 'booking', 0, '2018-12-14', '2018-12-15', 1, 1, 6500, '764.16', '12.15', '18.00', '167.74', '1170.00', '18.00', '8601.90', '0000-00-00', '0000-00-00', 0, '0.00', '0.00', 8601.9, '5', NULL, NULL, '0000-00-00', '', 0, 'banglow', 0, 0, '0', 0, 0, 0, 0, 0, '2018-12-14 09:46:55', '2018-12-14 09:47:48'),
(55, 'B000055', 100, 52, 103, 'booking', 0, '2018-12-14', '2018-12-15', 1, 1, 1800, '200.85', '12.15', '18.00', '44.09', '216.00', '12.00', '2260.94', '0000-00-00', '0000-00-00', 0, '0.00', '0.00', 2260.94, '5', NULL, NULL, '0000-00-00', '', 0, 'banglow', 0, 0, '0', 0, 0, 0, 0, 0, '2018-12-14 09:51:58', '2018-12-14 09:52:49');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8 NOT NULL,
  `status` enum('0','1') CHARACTER SET utf8 NOT NULL DEFAULT '1' COMMENT '0=inactive 1=active',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `slug`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'home', 'home', '0', '2018-02-19 00:21:03', '2018-04-19 04:12:11', NULL),
(3, 'farm house', 'farm-house', '1', '2018-02-19 00:21:21', '2018-02-19 00:21:21', NULL),
(4, 'private villa', 'private-villa', '0', '2018-02-19 00:21:40', '2018-03-02 03:25:39', NULL),
(5, 'banglow', 'banglow', '1', '2018-02-19 00:21:55', '2018-02-19 00:21:55', NULL),
(6, 'aaa', 'aaa', '1', '2018-03-22 21:14:37', '2018-03-22 21:14:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contact_enquiry`
--

CREATE TABLE `contact_enquiry` (
  `id` int(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email_id` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` varchar(500) NOT NULL,
  `is_read_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT '1-view,0-not view',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contact_enquiry`
--

INSERT INTO `contact_enquiry` (`id`, `name`, `email_id`, `contact`, `subject`, `message`, `is_read_status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'test', 'jait@gmail.com', '7896541230', 'test', 'Experts provide consultation and on-site POS installation, account setup, hardware configuration, support and ongoing training for you and your staff.', '1', '2018-05-29 10:23:03', '2018-09-12 06:01:21', NULL),
(2, 'Harish kale', 'harish@geronra.com', '96325874125', 'skdfjk', 'ksdjf', '0', '2018-06-11 03:17:53', '2018-06-11 03:17:53', NULL),
(3, 'Deepak Salunke', 'deepak@gmail.com', '9876543210', 'Subject', 'Qwerty', '0', '2018-10-16 12:41:55', '2018-10-16 12:41:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(50) NOT NULL,
  `coupon_code` varchar(250) NOT NULL,
  `coupon_type` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1=regular 2=special',
  `descriptions` text NOT NULL,
  `discount_type` enum('1','2','','') NOT NULL DEFAULT '1' COMMENT '1=fix amount 2=percentage',
  `discount` float(9,2) NOT NULL COMMENT '1=discount in percentage 2=discount in fix amount',
  `global_expiry` datetime NOT NULL,
  `auto_expiry` time NOT NULL,
  `coupon_use` enum('1','2','3','') NOT NULL DEFAULT '1' COMMENT '1=min amount 2=user first time 3=both',
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0=inactive 1=active',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `coupon_code`, `coupon_type`, `descriptions`, `discount_type`, `discount`, `global_expiry`, `auto_expiry`, `coupon_use`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, '155AXNJIU4', '2', '  Mayur Chaudhair   ', '2', 15.00, '2019-03-20 00:00:00', '00:00:00', '1', '1', '2018-03-06 02:43:51', '2018-09-17 12:24:41', NULL),
(3, 'HEIUU5455', '2', '  This dmoos', '1', 15.00, '2019-03-20 00:00:00', '15:40:15', '1', '1', '2018-03-06 04:10:05', '2018-09-17 12:23:57', NULL),
(4, 'LOBF44554', '', 'Thss achec   ', '2', 22.13, '2019-03-20 00:00:00', '00:00:54', '1', '1', '2018-03-06 04:11:51', '2018-09-17 12:23:57', NULL),
(5, 'MAS877FSS', '1', '  Testing   ', '2', 54.00, '2019-03-20 00:00:00', '00:00:54', '2', '1', '2018-03-06 04:18:37', '2018-09-17 12:23:57', NULL),
(6, 'HASFSSAAS', '1', '     THis shisad', '1', 15.00, '2019-03-20 00:00:00', '00:00:15', '2', '1', '2018-03-06 05:24:19', '2018-09-17 12:23:57', NULL),
(7, 'hasd11325', '1', '   fasdfs   ', '2', 12.00, '2019-03-20 00:00:00', '23:59:00', '3', '1', '2018-03-06 05:29:04', '2018-09-17 12:23:57', NULL),
(8, 'PDFV256985', '1', '   Hooby club Android Apps and Ios App', '1', 2500.00, '2019-03-20 00:00:00', '23:53:00', '1', '1', '2018-06-13 22:50:23', '2018-09-17 12:23:57', NULL),
(9, 'BPV256985', '1', '   should display coupon code  when added at time of booking ', '2', 10.00, '2019-03-20 00:00:00', '23:00:00', '2', '1', '2018-06-13 23:13:07', '2018-09-17 12:23:57', NULL),
(10, 'BPVP256985', '1', 'should display coupon code  when added at time of booking    ', '2', 10.00, '2019-03-20 00:00:00', '23:00:00', '3', '1', '2018-06-13 23:14:05', '2018-09-17 12:23:57', NULL),
(11, 'PDFV256986', '1', 'should display coupon code  when added at time of booking ', '1', 1500.00, '2019-03-20 00:00:00', '20:00:00', '3', '1', '2018-06-13 23:15:25', '2018-09-17 12:23:57', NULL),
(12, 'PDFV256989', '1', 'it should display the time picker or date picker of admin side', '2', 10.00, '2019-03-20 00:00:00', '23:00:00', '3', '1', '2018-06-13 23:29:08', '2018-09-17 12:23:57', NULL),
(13, 'PDFV256988', '1', 'Hooby club Android Apps and Ios Apps\r\n\r\n', '1', 2500.00, '2019-03-20 00:00:00', '22:00:00', '3', '1', '2018-06-14 02:00:27', '2018-09-17 12:23:57', NULL),
(14, 'BPVP256989', '1', 'Hooby club Android Apps and Ios Apps\r\n\r\n   ', '1', 2500.00, '2019-03-20 00:00:00', '20:00:00', '1', '1', '2018-06-14 02:01:46', '2018-09-17 12:23:57', NULL),
(15, 'GPFC123654', '1', 'When Enter the long coupon code then should not clash', '2', 15.00, '2019-03-20 00:00:00', '23:00:00', '2', '1', '2018-06-14 03:20:05', '2018-09-17 12:23:57', NULL),
(16, 'PDFV256990', '1', 'Percentage', '2', 10.00, '2019-03-20 00:00:00', '10:00:00', '3', '1', '2018-06-14 03:37:32', '2018-09-17 12:23:57', NULL),
(17, 'PDFV256996', '1', 'Percentage', '2', 15.00, '2019-03-20 00:00:00', '23:00:00', '1', '1', '2018-06-14 03:38:32', '2018-09-17 12:23:57', NULL),
(18, 'kbp23dsdwe', '1', '   xczxczczczczxc', '2', 20.00, '2019-03-20 00:00:00', '20:58:00', '3', '1', '2018-07-05 03:03:56', '2018-09-17 12:23:57', NULL),
(19, 'sdet23222', '1', '    s, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2', 20.00, '2019-03-20 00:00:00', '01:07:00', '3', '1', '2018-07-05 05:21:42', '2018-09-17 12:23:57', NULL),
(20, 'NACB123456', '1', 'this is   ', '1', 500.00, '2019-03-20 00:00:00', '06:00:00', '3', '1', '2018-07-12 00:01:35', '2018-09-17 12:23:57', NULL),
(21, 'BACD123456', '1', '   Transactions design should display the same (IOS)', '1', 600.00, '2019-03-20 00:00:00', '09:00:00', '3', '1', '2018-07-12 00:19:29', '2018-09-17 12:23:57', NULL),
(22, 'HACF123456', '1', '    Shareous Admin.', '1', 800.00, '2019-03-20 00:00:00', '06:00:00', '3', '1', '2018-07-12 00:30:11', '2018-09-17 12:23:57', NULL),
(23, 'SABC123456', '1', '   salsad', '1', 600.00, '2019-03-20 00:00:00', '09:00:00', '3', '1', '2018-07-12 01:28:05', '2018-09-17 12:23:57', NULL),
(24, 'NAPC123456', '1', ' should not display the pay/wallet options   ', '1', 1000.00, '2019-03-20 00:00:00', '06:00:00', '3', '1', '2018-07-13 10:26:35', '2018-09-17 12:23:57', NULL),
(25, 'ABCD123456', '1', 'should not display the pay/wallet options   ', '1', 600.00, '2019-03-20 00:00:00', '09:00:00', '3', '1', '2018-07-13 10:27:26', '2018-09-17 12:23:57', NULL),
(26, 'SAG123456', '1', ' Fetra projects\r\nLocations filter not working properly', '1', 1000.00, '2019-03-20 00:00:00', '06:00:00', '1', '1', '2018-07-17 03:00:15', '2018-09-17 12:23:57', NULL),
(27, 'HGPV123456', '1', 'Background colors should display the grey colors   ', '1', 300.00, '2019-03-20 00:00:00', '06:00:00', '3', '1', '2018-07-18 11:02:29', '2018-09-17 12:23:43', NULL),
(28, 'SDVP123456', '1', 'it should diplay the amounts\r\nCurrently, does not display the amount   ', '1', 500.00, '2019-03-20 00:00:00', '06:00:00', '3', '1', '2018-07-18 11:15:48', '2018-09-17 12:23:57', NULL),
(29, 'GHPV252525', '1', '   Hooby club Android Apps and Ios Apps', '1', 250.00, '2019-03-20 00:00:00', '09:00:00', '3', '1', '2018-07-18 12:35:42', '2018-09-17 12:23:57', NULL),
(30, 'GHDF123456', '1', '   Field name should display same websites', '1', 400.00, '2019-03-20 00:00:00', '06:00:00', '1', '1', '2018-07-19 11:05:20', '2018-09-17 12:23:57', NULL),
(31, 'ASDF123456', '1', '   Field name should display same websites', '1', 500.00, '2019-03-20 00:00:00', '06:00:00', '1', '1', '2018-07-19 11:06:24', '2018-09-17 12:23:57', NULL),
(32, 'ASDF12345', '1', 'Fetra projects \r\nLocations filter not working properly ', '1', 500.00, '2019-03-20 00:00:00', '23:00:00', '1', '1', '2018-07-20 08:48:51', '2018-09-17 12:23:57', NULL),
(33, 'NVPS123456', '1', ' \r\nLocations filter not working properly   ', '1', 500.00, '2019-03-20 00:00:00', '09:00:00', '1', '1', '2018-07-20 08:49:38', '2018-09-17 12:23:57', NULL),
(34, 'MSDF252525', '1', ' Fetra projects\r\nLocations filter not working properly   ', '1', 200.00, '2019-03-20 00:00:00', '04:00:00', '1', '1', '2018-07-20 09:37:29', '2018-09-17 12:23:57', NULL),
(35, 'BCDF25985', '1', '    Fetra projects\r\nLocations filter not working properly', '1', 500.00, '2019-03-20 00:00:00', '01:00:00', '1', '1', '2018-07-20 09:39:02', '2018-09-17 12:23:57', NULL),
(36, 'SADC25845', '1', '   View more banglows button  redirect to details pages should display ascending orders', '1', 500.00, '2019-03-20 00:00:00', '23:00:00', '1', '1', '2018-07-23 15:13:32', '2018-09-17 12:23:57', NULL),
(37, 'JETRA12345', '1', 'Latitude and Longitude', '1', 500.00, '2019-03-20 00:00:00', '23:00:00', '1', '1', '2018-07-24 11:06:34', '2018-09-17 14:13:49', NULL),
(38, '445555555', '1', '   zfsfsdf', '1', 0.00, '2019-03-20 00:00:00', '04:02:00', '1', '1', '2018-09-14 07:13:13', '2018-09-17 12:23:57', NULL),
(39, '455645664', '1', ' asdsdasd', '1', 1000.00, '2019-03-20 00:00:00', '20:00:00', '1', '1', '2018-09-14 07:14:26', '2018-09-17 14:13:54', NULL),
(40, 'asdasdasd', '1', '        asdasdsd', '2', 7.00, '2019-03-20 00:00:00', '02:00:00', '1', '1', '2018-09-14 07:16:07', '2018-09-17 14:13:42', NULL),
(41, 'test123345', '1', '   123123', '1', 123123.00, '2018-09-28 00:00:00', '00:00:00', '1', '1', '2018-09-21 12:23:56', '2018-09-21 12:23:56', NULL),
(42, 'tes1233456', '1', '   asd', '2', 12.00, '2018-09-30 00:00:00', '00:00:00', '1', '1', '2018-09-21 12:24:28', '2018-09-21 12:24:28', NULL),
(43, 'DEEPAK123', '1', 'discount code   ', '1', 50.00, '2019-03-28 00:00:00', '23:55:00', '1', '1', '2018-12-25 10:13:42', '2018-12-25 10:13:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `coupons_used`
--

CREATE TABLE `coupons_used` (
  `id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE `currency` (
  `id` int(11) NOT NULL,
  `currency` varchar(20) NOT NULL,
  `currency_code` varchar(20) NOT NULL,
  `html_code` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`id`, `currency`, `currency_code`, `html_code`, `created_at`, `updated_at`) VALUES
(1, '$', 'USD', '<i class=\"fa fa-usd\" aria-hidden=\"true\"></i>', '0000-00-00 00:00:00', '2018-09-24 11:24:28'),
(2, '€', 'EUR', '<i class=\"fa fa-eur\" aria-hidden=\"true\"></i>', '0000-00-00 00:00:00', '2018-09-24 11:24:11'),
(3, '₹', 'INR', '<i class=\"fa fa-inr\" aria-hidden=\"true\"></i>', '0000-00-00 00:00:00', '2018-09-25 12:41:47');

-- --------------------------------------------------------

--
-- Table structure for table `currency_conversion`
--

CREATE TABLE `currency_conversion` (
  `id` int(11) NOT NULL,
  `from_currency_id` int(11) NOT NULL,
  `to_currency_id` int(11) NOT NULL,
  `conversion_rate` float NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `currency_conversion`
--

INSERT INTO `currency_conversion` (`id`, `from_currency_id`, `to_currency_id`, `conversion_rate`, `created_at`, `updated_at`) VALUES
(7, 1, 2, 0.854225, '2018-07-26 19:05:09', '2018-09-03 18:17:17'),
(8, 1, 3, 68.4925, '2018-07-26 19:05:09', '2018-07-31 19:35:05'),
(9, 2, 1, 1.17062, '2018-07-26 19:05:09', '2018-07-31 19:35:05'),
(10, 2, 3, 80.1843, '2018-07-26 19:05:09', '2018-07-31 19:35:06'),
(11, 3, 1, 0.014599, '2018-07-26 19:05:09', '2018-07-31 19:35:06'),
(12, 3, 2, 0.012471, '2018-07-26 19:05:09', '2018-07-31 19:35:06');

-- --------------------------------------------------------

--
-- Table structure for table `email_template`
--

CREATE TABLE `email_template` (
  `id` int(11) NOT NULL,
  `template_name` varchar(100) NOT NULL,
  `template_from` varchar(200) CHARACTER SET utf8 NOT NULL,
  `template_subject` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `template_variables` varchar(500) CHARACTER SET utf8 NOT NULL,
  `template_from_mail` varchar(255) CHARACTER SET utf8 NOT NULL,
  `template_html` text CHARACTER SET utf8 NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `email_template`
--

INSERT INTO `email_template` (`id`, `template_name`, `template_from`, `template_subject`, `template_variables`, `template_from_mail`, `template_html`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'Support : Account Details', 'Shereous - Admin', 'Your Login Details', '##SITE_URL##~##USER_NAME##~##USER_EMAIL##~##PHONE##~##SUBJECT##', 'admin@shareous.com', '<table style=\"margin-bottom: 0;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n<tbody>\n         <tr> \n             <td style=\"color: #333333;font-size: 15px;padding-top: 3px;text-align: center;\">Support Account Details</td>\n         </tr>\n          \n          <tr>\n             <td height=\"40\"></td>\n          </tr>\n          <tr>\n            \n             <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\n             Hello <span style=\"color:#f50001;font-family: \'Latomedium\',sans-serif;\">##USER_NAME##,</span>\n             </td>\n          </tr>\n         <tr>\n             <td style=\"color: #545454;font-size: 15px;padding: 12px 30px;\">\n            Your account has been created successfully, here are the login details of your account.\n             </td>\n          </tr>\n\n          <tr>\n             <td style=\"color: #545454;font-size: 15px;padding: 12px 30px;\">\n            Email : ##EMAIL##\n             </td>\n              \n          </tr>\n          <tr>\n             <td style=\"color: #545454;font-size: 15px;padding: 12px 30px;\">\n            Password : ##PASSWORD##\n             </td>\n          </tr>\n         \n         \n         <tr>\n             <td height=\"20\"></td>\n          </tr>\n            \n            <tr>##SITE_URL##</tr> \n         \n          <tr>\n             <td height=\"40\"></td>\n          </tr>\n         <tr>\n             <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\n             Thanks &amp; Regards,\n             </td>\n          </tr>\n          \n         <tr>\n            <td style=\"color: #f50001;  font-size: 15px; padding: 0 30px;\">\n               ##PROJECT_NAME##\n            </td>\n         </tr>\n</tbody>\n</table>', '2017-12-19 18:51:39', '2018-04-26 07:15:44', NULL),
(3, 'Account Verification', 'Shereous - Admin', 'Verify Your Account', '##SITE_URL##~##USER_NAME##~##USER_EMAIL##~##PHONE##~##SUBJECT##', 'admin@shareous.com', '<table style=\"margin-bottom: 0;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n<tbody>\n<tr><td style=\"color: #333333;font-size: 15px;padding-top: 3px;text-align: center;\">Account Verification</td></tr>\n                  \n                  <tr>\n                     <td height=\"40\"></td>\n                  </tr>\n                  <tr>\n                    \n                     <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\n						 Hello <span style=\"color:#f50001;font-family: \'Latomedium\',sans-serif;\">##USER_NAME##,</span>\n                     </td>\n                  </tr>\n				   <tr>\n                     <td style=\"color: #545454;font-size: 15px;padding: 12px 30px;\">\n						Thank you for creating an account with us, You can activate your account by the following activate button.\n                     </td>\n                  </tr>\n				   \n				   <tr>\n                     <td height=\"20\"></td>\n                  </tr>\n                  \n                  <tr>##ACTIVATION_URL##</tr>\n                  <tr>\n                     <td height=\"40\"></td>\n                  </tr>\n				   <tr>\n                     <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\n						 Thanks &amp; Regards,\n                     </td>\n                  </tr>\n                  \n				   <tr>\n					   <td style=\"color: #f50001;  font-size: 15px; padding: 0 30px;\">\n						   ##PROJECT_NAME##\n					   </td>\n				   </tr>\n				   </tbody>\n				   </table>', '2017-12-19 18:51:39', '2018-04-21 03:27:34', NULL),
(4, 'Forgot Password', 'Shereous - Admin', 'Forgot Password', '##SITE_URL##~##USER_NAME##~##USER_EMAIL##~##PHONE##~##SUBJECT##', 'admin@shareous.com', ' <table style=\"margin-bottom: 0;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n<tbody>\n<tr><td style=\"color: #333333;font-size: 15px;padding-top: 3px;text-align: center;\">Reset Password</td></tr>\n                  \n                  <tr>\n                     <td height=\"40\"></td>\n                  </tr>\n                  <tr>\n                    \n                     <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\n						 Hello <span style=\"color:#f50001;font-family: \'Latomedium\',sans-serif;\">##USER_NAME##,</span>\n                     </td>\n                  </tr>\n				   <tr>\n                     <td style=\"color: #545454;font-size: 15px;padding: 12px 30px;\">\n						You are recently requested a password reset,Please click on below link to reset your account password,\n                     </td>\n                  </tr>\n				   \n				   <tr>\n                     <td height=\"20\"></td>\n                  </tr>\n                  \n                  <tr>##RESET_LINK##</tr>\n                  <tr>\n                     <td height=\"40\"></td>\n                  </tr>\n				   <tr>\n                     <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\n						 Thanks &amp; Regards,\n                     </td>\n                  </tr>\n                  \n				   <tr>\n					   <td style=\"color: #f50001;  font-size: 15px; padding: 0 30px;\">\n						   ##PROJECT_NAME##\n					   </td>\n				   </tr>\n				   </tbody>\n				   </table>\n\n', '2017-12-19 18:51:39', '2018-04-21 04:47:58', NULL),
(5, 'Support : Document Verification', 'Shereous - Support', 'Document Approved', '##SITE_URL##~##USER_NAME##~##SUBJECT##', 'support@shareous.com', '<table style=\"margin-bottom: 0;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n<tbody>\n         <tr> \n             <td style=\"color: #333333;font-size: 15px;padding-top: 3px;text-align: center;\">Document Verification</td>\n         </tr>\n          \n          <tr>\n             <td height=\"40\"></td>\n          </tr>\n          <tr>\n	         <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\n				 Hello <span style=\"color:#f50001;font-family: \'Latomedium\',sans-serif;\">##USER_NAME##,</span>\n	         </td>\n          </tr>\n		   <tr>\n             <td style=\"color: #545454;font-size: 15px;padding: 12px 30px;\">\n				Congratulations! Your document has been successfully approved. Thank you for choosing Shareous.\n             </td>\n          </tr>\n\n		   <tr>\n             <td height=\"20\"></td>\n          </tr>\n          \n        \n          <tr>\n             <td height=\"40\"></td>\n          </tr>\n		   <tr>\n             <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\n				 Thanks &amp; Regards,\n             </td>\n          </tr>\n          \n		   <tr>\n			   <td style=\"color: #f50001;  font-size: 15px; padding: 0 30px;\">\n				   ##PROJECT_NAME##\n			   </td>\n		   </tr>\n</tbody>\n</table>', '2018-03-01 18:51:39', '2018-04-23 00:11:14', '2018-03-01 13:00:00'),
(6, 'Support : Document Verification', 'Shereous - Support', 'Document Rejection', '##SITE_URL##~##USER_NAME##~##SUBJECT##', 'support@shareous.com', '<table style=\"margin-bottom: 0;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n<tbody>\n         <tr> \n             <td style=\"color: #333333;font-size: 15px;padding-top: 3px;text-align: center;\">Document Rejection</td>\n         </tr>\n          \n          <tr>\n             <td height=\"40\"></td>\n          </tr>\n          <tr>\n	         <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\n				 Hello <span style=\"color:#f50001;font-family: \'Latomedium\',sans-serif;\">##USER_NAME##,</span>\n	         </td>\n          </tr>\n		   <tr>\n             <td style=\"color: #545454;font-size: 15px;padding: 12px 30px;\">\n				Sorry! Your document was not match to your profile, Please upload valid document. Thank you for choosing Shareous.\n             </td>\n          </tr>\n\n		   <tr>\n             <td height=\"20\"></td>\n          </tr>\n          <tr>\n             <td height=\"40\"></td>\n          </tr>\n		   <tr>\n             <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\n				 Thanks &amp; Regards,\n             </td>\n          </tr>\n          \n		   <tr>\n			   <td style=\"color: #f50001;  font-size: 15px; padding: 0 30px;\">\n				   ##PROJECT_NAME##\n			   </td>\n		   </tr>\n</tbody>\n</table>', '2018-03-01 18:51:39', '2018-04-23 00:19:25', '2018-03-01 13:00:00'),
(7, 'Ticket Generation', 'Shereous - Support', 'Ticket Generate [Ticket_id: ##TICKET_ID## ]', '##SITE_URL##~##USER_NAME##~##SUBJECT##~~##TICKET_ID##~##QUERY_SUBJECT##', 'support@shareous.com', '<table style=\"margin-bottom: 0;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n<tbody>\n         <tr> \n             <td style=\"color: #333333;font-size: 15px;padding-top: 3px;text-align: center;\">Ticket Generation</td>\n         </tr>\n          \n          <tr>\n             <td height=\"40\"></td>\n          </tr>\n          <tr>\n            \n             <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\n				 Hello <span style=\"color:#f50001;font-family: \'Latomedium\',sans-serif;\">##USER_NAME##,</span>\n             </td>\n          </tr>\n		   <tr>\n             <td style=\"color: #545454;font-size: 15px;padding: 12px 30px;\">\n				Thank you for contacting us, Your ticket id is -<strong> ##TICKET_ID##</strong> for the subject - \"<strong>##QUERY_SUBJECT##</strong>\". You will get reply shortly.\n             </td>\n          </tr>\n		   \n		   <tr>\n             <td height=\"20\"></td>\n          </tr>\n          \n        \n          <tr>\n             <td height=\"40\"></td>\n          </tr>\n		   <tr>\n             <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\n				 Thanks &amp; Regards,\n             </td>\n          </tr>\n          \n		   <tr>\n			   <td style=\"color: #f50001;  font-size: 15px; padding: 0 30px;\">\n				   ##PROJECT_NAME##\n			   </td>\n		   </tr>\n</tbody>\n</table>', '2018-03-04 18:51:39', '2018-04-21 06:57:18', '2018-03-01 13:00:00'),
(8, 'Ticket Cancellation', 'Shereous - Support', 'Ticket Cancel [Ticket_id: ##TICKET_ID## ]', '##SITE_URL##~##USER_NAME##~##SUBJECT##~~##TICKET_ID##~##QUERY_SUBJECT##', 'support@shareous.com', '<p style=\"color: #333333; font-size: 17px; padding-top: 5px; text-align: center;\">##SUBJECT##</p>\r\n<div style=\"height: 10px;\">&nbsp;</div>\r\n<p style=\"color: #333333; font-size: 18px; padding: 0 40px;\">Hello <span style=\"color: #f50001; font-family: \'Latomedium\',sans-serif;\">##USER_NAME##,</span></p>\r\n<p style=\"color: #545454; font-size: 17px; padding: 15px 40px;\">Your query with Ticket_id -&nbsp;<strong>##TICKET_ID##</strong> and subject -<strong>\"##QUERY_SUBJECT##\"</strong> closed successfully. Thank you for using shareous.</p>', '2018-03-04 18:51:39', '2018-03-07 23:45:51', '2018-03-01 13:00:00'),
(9, 'Property Status', 'Shereous - Admin', 'Property status', '##SITE_URL##~##USER_NAME##~##SUBJECT##~~##STATUS##~~##COMMENT##', 'admin@shareous.com', '<table style=\"margin-bottom: 0px; height: 219px; width: 100%;\" border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr style=\"height: 19px;\">\r\n<td style=\"color: #333333; font-size: 15px; padding-top: 3px; text-align: center; height: 19px;\">Property Status</td>\r\n</tr>\r\n<tr style=\"height: 40px;\">\r\n<td style=\"height: 40px;\" height=\"40\">&nbsp;</td>\r\n</tr>\r\n<tr style=\"height: 22px;\">\r\n<td style=\"color: #333333; font-size: 16px; padding: 0px 30px; height: 22px;\">Hello <span style=\"color: #f50001; font-family: \'Latomedium\',sans-serif;\">##USER_NAME##,</span></td>\r\n</tr>\r\n<tr style=\"height: 39px;\">\r\n<td style=\"color: #545454; font-size: 15px; padding: 12px 30px; height: 39px;\">Your Property <strong>##PROPERTY_NAME##</strong> has been &nbsp;<strong>##STATUS##</strong> by admin. If you have any query please contact to admin. Thank you for using shareous.</td>\r\n</tr>\r\n<tr style=\"height: 20px;\">\r\n<td style=\"height: 20px;\" height=\"20\">&nbsp;</td>\r\n</tr>\r\n<tr style=\"height: 40px;\">\r\n<td style=\"height: 40px;\" height=\"40\">&nbsp;</td>\r\n</tr>\r\n<tr style=\"height: 20px;\">\r\n<td style=\"color: #333333; font-size: 16px; padding: 0px 30px; height: 20px;\">Thanks &amp; Regards,</td>\r\n</tr>\r\n<tr style=\"height: 19px;\">\r\n<td style=\"color: #f50001; font-size: 15px; padding: 0px 30px; height: 19px;\">##PROJECT_NAME##</td>\r\n</tr>\r\n</tbody>\r\n</table>', '2018-03-04 18:51:39', '2018-09-11 13:31:08', '2018-03-01 13:00:00'),
(10, 'Property rejected', 'Shereous - Admin', 'Property status', '##SITE_URL##~##USER_NAME##~##SUBJECT##~~##STATUS##~~##COMMENT##', 'admin@shareous.com', '<table style=\"margin-bottom: 0;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n<tbody>\n         <tr> \n             <td style=\"color: #333333;font-size: 15px;padding-top: 3px;text-align: center;\">Property Status</td>\n         </tr>\n          \n          <tr>\n             <td height=\"40\"></td>\n          </tr>\n          <tr>\n            \n             <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\n				 Hello <span style=\"color:#f50001;font-family: \'Latomedium\',sans-serif;\">##USER_NAME##,</span>\n             </td>\n          </tr>\n		   <tr>\n             <td style=\"color: #545454;font-size: 15px;padding: 12px 30px;\">\n				Your Property has been &nbsp;<strong>##STATUS##</strong> by admin because of following reason <strong> ##COMMENT## </strong>. If you have any query please contact to admin. Thank you for using shareous.\n             </td>\n          </tr>\n		   \n		   <tr>\n             <td height=\"20\"></td>\n          </tr>\n          \n        \n          <tr>\n             <td height=\"40\"></td>\n          </tr>\n		   <tr>\n             <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\n				 Thanks &amp; Regards,\n             </td>\n          </tr>\n          \n		   <tr>\n			   <td style=\"color: #f50001;  font-size: 15px; padding: 0 30px;\">\n				   ##PROJECT_NAME##\n			   </td>\n		   </tr>\n</tbody>\n</table>', '2018-03-04 18:51:39', '2018-04-23 03:52:43', '2018-03-01 13:00:00'),
(11, 'Ticket Reply', 'Shereous - Support', 'Ticket Reply [Ticket_id: ##TICKET_ID## ]', '##SITE_URL##~##USER_NAME##~##SUBJECT##~~##TICKET_ID##~##QUERY_SUBJECT##~##QUERY_REPLY##', 'support@shareous.com', '<table style=\"margin-bottom: 0;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n<tbody>\r\n         <tr> \r\n             <td style=\"color: #333333;font-size: 15px;padding-top: 3px;text-align: center;\">Ticket Reply</td>\r\n         </tr>\r\n          \r\n          <tr>\r\n             <td height=\"40\"></td>\r\n          </tr>\r\n          <tr>\r\n	         <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\r\n				 Hello <span style=\"color:#f50001;font-family: \'Latomedium\',sans-serif;\">##USER_NAME##,</span>\r\n	         </td>\r\n          </tr>\r\n		   <tr>\r\n             <td style=\"color: #545454;font-size: 15px;padding: 12px 30px;\">\r\n				Thank you for contacting us, Your ticket id is -<strong> ##TICKET_ID##</strong> for the subject - \"<strong>##QUERY_SUBJECT##\"</strong>, answer for this query is - <strong>##QUERY_REPLY##</strong>. Thank you for choosing Shareous.\r\n             </td>\r\n          </tr>\r\n\r\n		   <tr>\r\n             <td height=\"20\"></td>\r\n          </tr>\r\n          \r\n        \r\n          <tr>\r\n             <td height=\"40\"></td>\r\n          </tr>\r\n		   <tr>\r\n             <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\r\n				 Thanks &amp; Regards,\r\n             </td>\r\n          </tr>\r\n          \r\n		   <tr>\r\n			   <td style=\"color: #f50001;  font-size: 15px; padding: 0 30px;\">\r\n				   ##SITE_NAME##\r\n			   </td>\r\n		   </tr>\r\n</tbody>\r\n</table>', '2018-03-04 18:51:39', '2018-08-17 00:38:55', '2018-03-01 13:00:00'),
(12, 'Ticket Replydfg', 'Shereous - Supportsdfg', 'Ticket Reply [Ticket_id: ##TICKET_ID## ]', '##SITE_URL##~##USER_NAME##~##SUBJECT##~~##TICKET_ID##~##QUERY_SUBJECT##~##QUERY_REPLY##', 'support@shareous.com', '<p style=\"color: #333333; font-size: 17px; padding-top: 5px; text-align: center;\">##SUBJECT##</p>\r\n<div style=\"height: 10px;\">&nbsp;</div>\r\n<p style=\"color: #333333; font-size: 18px; padding: 0 40px;\">Hello <span style=\"color: #f50001; font-family: \'Latomedium\',sans-serif;\">##USER_NAME##,</span></p>\r\n<p style=\"color: #545454; font-size: 17px; padding: 15px 40px;\">Thank you for contacting us, Your ticket id is -<strong> ##TICKET_ID##</strong> for the subject - \"<strong>##QUERY_SUBJECT##\"</strong>, answer for this query is - <strong>##QUERY_REPLY##</strong>. Thank you for choosing Shareous.</p>\r\n<p style=\"color: #545454; font-size: 17px; padding: 15px 40px;\">&nbsp;</p>', '2018-03-04 18:51:39', '2018-03-26 19:46:00', '2018-03-01 13:00:00'),
(13, 'Notification', 'Shereous - Admin', 'Notification', '##SITE_URL##~##USER_NAME##~##NOTIFICATION_SUBJECT##~##MESSAGE##', 'admin@shareous.com', '<table style=\"margin-bottom: 0;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n<tbody>\n         <tr> \n             <td style=\"color: #333333;font-size: 15px;padding-top: 3px;text-align: center;\">##NOTIFICATION_SUBJECT##</td>\n         </tr>\n          \n          <tr>\n             <td height=\"40\"></td>\n          </tr>\n          <tr>\n            \n             <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\n				 Hello <span style=\"color:#f50001;font-family: \'Latomedium\',sans-serif;\">##USER_NAME##,</span>\n             </td>\n          </tr>\n		   <tr>\n             <td style=\"color: #545454;font-size: 15px;padding: 12px 30px;\">\n				      ##MESSAGE##\n             </td>\n          </tr>\n		   \n		   <tr>\n             <td height=\"20\"></td>\n          </tr>\n          \n        \n          <tr>\n             <td height=\"40\"></td>\n          </tr>\n		   <tr>\n             <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\n				 Thanks &amp; Regards,\n             </td>\n          </tr>\n          \n		   <tr>\n			   <td style=\"color: #f50001;  font-size: 15px; padding: 0 30px;\">\n				   ##PROJECT_NAME##\n			   </td>\n		   </tr>\n</tbody>\n</table>', '2018-03-04 18:51:39', '2018-05-17 01:23:27', '2018-03-01 13:00:00'),
(14, 'Booking Request', 'Shereous - Admin', 'Booking Request', '##SITE_URL##~##USER_NAME##~##SUBJECT##~~##STATUS##~~##COMMENT##', 'admin@shareous.com', '<table style=\"margin-bottom: 0;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n<tbody>\r\n         <tr> \r\n             <td style=\"color: #333333;font-size: 15px;padding-top: 3px;text-align: center;\">Booking Status</td>\r\n         </tr>\r\n          \r\n          <tr>\r\n             <td height=\"40\"></td>\r\n          </tr>\r\n          <tr>\r\n            \r\n             <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\r\n				 Hello <span style=\"color:#f50001;font-family: \'Latomedium\',sans-serif;\">##USER_NAME##,</span>\r\n             </td>\r\n          </tr>\r\n		   <tr>\r\n             <td style=\"color: #545454;font-size: 15px;padding: 12px 30px;\">\r\n				You have a new booking &nbsp;<strong>##STATUS##</strong> by the guest. If you have any query please contact to admin. Thank you for using shareous.\r\n             </td>\r\n          </tr>\r\n		   \r\n		   <tr>\r\n             <td height=\"20\"></td>\r\n          </tr>\r\n          \r\n        \r\n          <tr>\r\n             <td height=\"40\"></td>\r\n          </tr>\r\n		   <tr>\r\n             <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\r\n				 Thanks &amp; Regards,\r\n             </td>\r\n          </tr>\r\n          \r\n		   <tr>\r\n			   <td style=\"color: #f50001;  font-size: 15px; padding: 0 30px;\">\r\n				   ##PROJECT_NAME##\r\n			   </td>\r\n		   </tr>\r\n</tbody>\r\n</table>', '2018-03-04 18:51:39', '2018-05-03 05:28:43', '2018-03-01 13:00:00'),
(15, 'Booking Status', 'Shereous - Admin', 'Booking status', '##SITE_URL##~##USER_NAME##~##SUBJECT##~~##STATUS##~~##COMMENT##', 'admin@shareous.com', '<table style=\"margin-bottom: 0;\" border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"color: #333333; font-size: 15px; padding-top: 3px; text-align: center;\">Booking Status</td>\r\n</tr>\r\n<tr>\r\n<td height=\"40\">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">Hello <span style=\"color: #f50001; font-family: \'Latomedium\',sans-serif;\">##USER_NAME##,</span></td>\r\n</tr>\r\n<tr>\r\n<td style=\"color: #545454; font-size: 15px; padding: 12px 30px;\">Your Booking has been <strong> ##STATUS## </strong> by host. If you have any query please contact to admin. Thank you for using shareous.</td>\r\n</tr>\r\n<tr>\r\n<td height=\"20\">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td height=\"40\">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">Thanks &amp; Regards,</td>\r\n</tr>\r\n<tr>\r\n<td style=\"color: #f50001; font-size: 15px; padding: 0 30px;\">##PROJECT_NAME##</td>\r\n</tr>\r\n</tbody>\r\n</table>', '2018-03-04 18:51:39', '2018-07-17 11:38:06', '2018-03-01 13:00:00'),
(16, 'Add Review Request', 'Shereous - Admin', 'Add Review Rating Request', '##SITE_URL##~##USER_NAME##~##SUBJECT##PROPERTY_NAME##PROJECT_NAME', 'admin@shareous.com', '<table style=\"margin-bottom: 0;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n<tbody>\n<tr><td style=\"color: #333333;font-size: 15px;padding-top: 3px;text-align: center;\">Add Review & Rating Request</td></tr>\n                  \n                  <tr>\n                     <td height=\"40\"></td>\n                  </tr>\n                  <tr>\n                    \n                     <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\n						 Hello <span style=\"color:#f50001;font-family: \'Latomedium\',sans-serif;\">##USER_NAME##,</span>\n                     </td>\n                  </tr>\n				   <tr>\n                     <td style=\"color: #545454;font-size: 15px;padding: 12px 30px;\">\n					        Please Add Review & Ratings Against ##PROPERTY_NAME## Property Which You Recently Booked.\n                     </td>\n                  </tr>\n				   \n				   <tr>\n                     <td height=\"20\"></td>\n                  </tr>\n                  \n                  <tr><center><a href=\"##ADD_REVIEW_URL##\"><button class=\"btn-cancel\">Add Review & Rating</button></a></center></tr>\n                  <tr>\n                     <td height=\"40\"></td>\n                  </tr>\n				   <tr>\n                     <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\n						 Thanks &amp; Regards,\n                     </td>\n                  </tr>\n                  \n				   <tr>\n					   <td style=\"color: #f50001;  font-size: 15px; padding: 0 30px;\">\n						   ##PROJECT_NAME##\n					   </td>\n				   </tr>\n				   </tbody>\n				   </table>', '0000-00-00 00:00:00', '2018-05-11 03:40:02', NULL),
(17, 'Refund Amount', 'Shereous - Admin', 'Refund Amount', '##SITE_URL##~##USER_NAME##~##SUBJECT##PROJECT_NAME', 'admin@shareous.com', '<table style=\"margin-bottom: 0;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n<tbody>\n<tr><td style=\"color: #333333;font-size: 15px;padding-top: 3px;text-align: center;\">Refund Amount Successfully</td></tr>\n                  \n                  <tr>\n                     <td height=\"40\"></td>\n                  </tr>\n                  <tr>\n                    \n                     <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\n						 Hello <span style=\"color:#f50001;font-family: \'Latomedium\',sans-serif;\">##USER_NAME##,</span>\n                     </td>\n                  </tr>\n				   <tr>\n                     <td style=\"color: #545454;font-size: 15px;padding: 12px 30px;\">\n					          Amount Is Refund successfully In Your Account.\n                     </td>\n                  </tr>\n				   \n				      <tr>\n                     <td height=\"20\"></td>\n                  </tr>\n                  \n                 \n                  <tr>\n                     <td height=\"40\"></td>\n                  </tr>\n				      <tr>\n                     <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\n						 Thanks &amp; Regards,\n                     </td>\n                  </tr>\n                  \n				   <tr>\n					   <td style=\"color: #f50001;  font-size: 15px; padding: 0 30px;\">\n						   ##PROJECT_NAME##\n					   </td>\n				   </tr>\n				   </tbody>\n				   </table>', '0000-00-00 00:00:00', '2018-05-11 07:55:12', NULL),
(18, 'OTP Email', 'Shereous - Admin', 'Your OTP for Shareous', '##USER_NAME##~##OTP##~##PROJECT_NAME##', 'admin@shareous.com', '<table style=\"margin-bottom: 0;\" border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"color: #333333; font-size: 15px; padding-top: 3px; text-align: center;\">Account Verification</td>\r\n</tr>\r\n<tr>\r\n<td height=\"40\">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">Hello <span style=\"color: #f50001; font-family: \'Latomedium\',sans-serif;\">##USER_NAME##,</span></td>\r\n</tr>\r\n<tr>\r\n<td style=\"color: #545454; font-size: 15px; padding: 12px 30px;\">Thank you for your interest in ##PROJECT_NAME##!</td>\r\n</tr>\r\n<tr>\r\n<td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">Your OTP : <span style=\"color: #0050a0; font-family: \'ubuntumedium\',sans-serif;\">##OTP##</span></td>\r\n</tr>\r\n<tr>\r\n<td height=\"20\">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td height=\"40\">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">Thanks &amp; Regards,</td>\r\n</tr>\r\n<tr>\r\n<td style=\"color: #f50001; font-size: 15px; padding: 0 30px;\">##PROJECT_NAME##</td>\r\n</tr>\r\n</tbody>\r\n</table>', '2017-12-19 18:51:39', '2018-05-24 19:07:14', NULL),
(19, 'Password Email', 'Shereous - Admin', 'Your Password for Shareous', '##USER_NAME##~##PASSWORD##~##PROJECT_NAME##', 'admin@shareous.com', '<table style=\"margin-bottom: 0;\" border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"color: #333333; font-size: 15px; padding-top: 3px; text-align: center;\">Account Activation</td>\r\n</tr>\r\n<tr>\r\n<td height=\"40\">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">Hello <span style=\"color: #f50001; font-family: \'Latomedium\',sans-serif;\">##USER_NAME##,</span></td>\r\n</tr>\r\n<tr>\r\n<td style=\"color: #545454; font-size: 15px; padding: 12px 30px;\">Your password recovery request for ##PROJECT_NAME## account has been submitted successfully.</td>\r\n</tr>\r\n<tr>\r\n<td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">Your Password : <span style=\"color: #0050a0; font-family: \'ubuntumedium\',sans-serif;\">##PASSWORD##</span></td>\r\n</tr>\r\n<tr>\r\n<td height=\"20\">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td height=\"40\">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">Thanks &amp; Regards,</td>\r\n</tr>\r\n<tr>\r\n<td style=\"color: #f50001; font-size: 15px; padding: 0 30px;\">##PROJECT_NAME##</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n', '2017-12-19 18:51:39', '2018-05-24 19:07:14', NULL),
(20, 'Email Verification', 'Shereous - Admin', 'Verify Your Email', '##SITE_URL##~##USER_NAME##~##USER_EMAIL##~##PHONE##~##SUBJECT##', 'admin@shareous.com', '<table style=\"margin-bottom: 0;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n<tbody>\r\n<tr><td style=\"color: #333333;font-size: 15px;padding-top: 3px;text-align: center;\">Email Verification</td></tr>\r\n<tr>\r\n   <td height=\"40\"></td>\r\n</tr>\r\n<tr>\r\n  \r\n   <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\r\n Hello <span style=\"color:#f50001;font-family: \'Latomedium\',sans-serif;\">##USER_NAME##,</span>\r\n   </td>\r\n</tr>\r\n<tr>\r\n   <td style=\"color: #545454;font-size: 15px;padding: 12px 30px;\">\r\n    Your email has changed successfully, You can activate your email by the following activate button.\r\n   </td>\r\n</tr>\r\n\r\n<tr>\r\n   <td height=\"20\"></td>\r\n</tr>\r\n\r\n<tr>##ACTIVATION_URL##</tr>\r\n<tr>\r\n   <td height=\"40\"></td>\r\n</tr>\r\n<tr>\r\n   <td style=\"color: #333333; font-size: 16px; padding: 0 30px;\">\r\n Thanks &amp; Regards,\r\n   </td>\r\n</tr>\r\n\r\n<tr>\r\n<td style=\"color: #f50001;  font-size: 15px; padding: 0 30px;\">\r\n   ##PROJECT_NAME##\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>', '2017-12-19 18:51:39', '2018-06-08 05:23:03', NULL),
(21, 'Check-in Reminder', 'Shereous - Admin', 'Reminder: Your upcoming check-in!', '##CHECKIN_DATE##~##CHECKOUT_DATE##~##PROPERTY_DETAILS##~##OWNER_DETAILS##', 'admin@shareous.com', '<table style=\"margin-bottom: 0px; height: 226px; width: 100%; color: #333333; font-family: \'Latomedium\',sans-serif;\" border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"font-size: 16px; padding: 0px 30px; height: 20px;\">Hello <span style=\"color: #f50001;\">##USER_NAME##,</span></td>\r\n</tr>\r\n<tr>\r\n<td style=\"font-size: 16px; padding: 12px 30px; height: 20px;\">Only 1 day left for your check-in! Are you all set??</td>\r\n</tr>\r\n<tr>\r\n<td style=\"font-size: 15px; padding: 5px 30px;\">Your Booking Details as below:</td>\r\n</tr>\r\n<tr>\r\n<td style=\"font-size: 15px; padding: 5px 30px;\">Check-in Date: <strong>##CHECKIN_DATE##</strong> | Check-out Date: <strong>##CHECKOUT_DATE##</strong></td>\r\n</tr>\r\n<tr>\r\n<td style=\"font-size: 15px; padding: 5px 30px; height: 20px;\" height=\"20\">##PROPERTY_DETAILS##</td>\r\n</tr>\r\n<tr>\r\n<td style=\"font-size: 15px; padding: 5px 30px; height: 20px;\" height=\"20\"><strong>Host Details: </strong><br />##OWNER_DETAILS##</td>\r\n</tr>\r\n<tr style=\"height: 40px;\">\r\n<td style=\"height: 40px;\" height=\"40\">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td style=\"color: #333333; font-size: 16px; padding: 0px 30px; height: 20px;\">Thanks &amp; Regards,</td>\r\n</tr>\r\n<tr>\r\n<td style=\"color: #f50001; font-size: 15px; padding: 0px 30px; height: 19px;\">##PROJECT_NAME##</td>\r\n</tr>\r\n</tbody>\r\n</table>', '0000-00-00 00:00:00', '2018-08-16 12:39:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1' COMMENT '0-Inactive,1-Active',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`id`, `question`, `answer`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'How to contact you?', 'Please use contact us page.', '1', '2018-02-21 17:00:00', '2018-02-22 03:48:06', NULL),
(2, 'How to change profile image?', 'Please go to update profile', '1', '2018-02-21 17:00:00', '2018-02-22 21:19:27', NULL),
(3, 'What is Vacational Rental Registry?', 'Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moonofficia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven\'t heard of them accusamus labore sustainable VHS.', '1', '2018-02-01 01:19:00', '2018-03-01 00:10:37', NULL),
(4, 'Why should I use Shareous?', 'Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moonofficia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven\'t heard of them accusamus labore sustainable VHS.', '1', '2018-01-01 01:19:03', '2018-03-01 00:10:12', NULL),
(5, 'What is the pricing for Shareous ECR?', 'Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moonofficia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven\'t heard of them accusamus labore sustainable VHS.', '1', '2018-02-01 01:19:00', '2018-02-22 10:41:42', NULL),
(6, 'Is Shreous ECR a global service?', 'Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moonofficia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven\'t heard of them accusamus labore sustainable VHS.', '1', '2018-02-01 00:30:52', '2018-02-22 10:47:16', NULL),
(7, 'What compliance capabilities can I enable on Shareous ECR?', 'Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moonofficia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven\'t heard of them accusamus labore sustainable VHS.', '1', '2018-02-01 00:30:52', '2018-02-22 10:47:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `front_pages`
--

CREATE TABLE `front_pages` (
  `id` int(11) NOT NULL,
  `page_title` varchar(255) NOT NULL,
  `page_description` text NOT NULL,
  `page_slug` varchar(300) NOT NULL,
  `meta_keyword` varchar(255) NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` varchar(500) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `front_pages`
--

INSERT INTO `front_pages` (`id`, `page_title`, `page_description`, `page_slug`, `meta_keyword`, `meta_title`, `meta_description`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 'Terms & conditions', '<div class=\"change-pass-bady\">\r\n<div class=\"terms-content-block\">\r\n<div class=\"terms-sub-head\">Welcome to 911 Express</div>\r\n<div class=\"terms-pharagra\">These terms and conditions outline the rules and regulations for Accommodation Website. Accommodation is located at:</div>\r\n<div class=\"terms-pharagra-address-main\">\r\n<div class=\"terms-pharagra-address\">2708 Burwell Heights Road,</div>\r\n<div class=\"terms-pharagra-address\">Warren, TX 77664</div>\r\n<div class=\"terms-pharagra-address\">United States</div>\r\n</div>\r\n<div class=\"terms-pharagra margin-top\">By accessing this website we assume you accept these terms and conditions in full. Do not continue to use Accommodation Portal\'s website if you do not accept all of the terms and conditions stated on this page.</div>\r\n<div class=\"terms-pharagra margin-top\">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum</div>\r\n</div>\r\n<div class=\"terms-content-block\">\r\n<div class=\"terms-sub-head\">Cookies</div>\r\n<div class=\"terms-pharagra\">Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32</div>\r\n</div>\r\n<div class=\"terms-content-block\">\r\n<div class=\"terms-sub-head\">License</div>\r\n<div class=\"terms-pharagra\">Unless otherwise stated, Accommodation Portal and/or it\'s licensors own the intellectual property rights for all material on Accommodation Portal All intellectual property rights are reserved. You may view and/or print pages from http://www.example.com for your own personal use subject to restrictions set in these terms and conditions.</div>\r\n</div>\r\n<div class=\"boolets-main\">\r\n<div class=\"terms-pharagra\">You must not:</div>\r\n<div class=\"terms-bulets-main margi-top\">\r\n<div class=\"terms-content-bulets\">&nbsp;</div>\r\n<div class=\"terms-bulets-text\">Republish material from http://www.example.com</div>\r\n</div>\r\n<div class=\"terms-bulets-main\">\r\n<div class=\"terms-content-bulets\">&nbsp;</div>\r\n<div class=\"terms-bulets-text\">Sell, rent or sub-license material from http://www.example.com</div>\r\n</div>\r\n<div class=\"terms-bulets-main margi-bottom\">\r\n<div class=\"terms-content-bulets\">&nbsp;</div>\r\n<div class=\"terms-bulets-text\">Reproduce, duplicate or copy material from http://www.example.com</div>\r\n</div>\r\n<!--<ul>\r\n                     <li><span class=\"terms-content-bulets\"><i class=\"fa fa-circle\"></i></span> <span>Sed ut perspiciatis unde omnis iste natus error sit voluptatemlaudantium.</span></li>\r\n                     <li><span class=\"terms-content-bulets\"><i class=\"fa fa-circle\"></i></span> <span>Sed ut perspiciatis error sit voluptatem accusantium doloremque totam rem aperiam .</span></li>\r\n                     <li><span class=\"terms-content-bulets\"><i class=\"fa fa-circle\"></i></span> <span>Unde  sit voluptatem accusantium doloremque laudantium, totam rem aperiam</span></li>\r\n                     <li><span class=\"terms-content-bulets\"><i class=\"fa fa-circle\"></i></span> <span>Natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam</span></li>\r\n                 </ul>-->\r\n<div class=\"terms-pharagra two\">Redistribute content from Accommodation Portal (unless content is specifically made for redistribution).</div>\r\n</div>\r\n<div class=\"terms-content-block\">\r\n<div class=\"terms-sub-head\">User Comments</div>\r\n<div class=\"terms-pharagra\">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</div>\r\n</div>\r\n<div class=\"terms-content-block no-margin\">\r\n<div class=\"terms-sub-head\">Reservation of Rights</div>\r\n<div class=\"terms-pharagra\">We reserve the right at any time and in its sole discretion to request that you remove all links or any particular link to our Web site. You agree to immediately remove all links to our Web site upon such request. We also reserve the right to amend these terms and conditions and its linking policy at any time. By continuing to link to our Web site, you agree to be bound to and abide by these linking terms and conditions</div>\r\n</div>\r\n</div>', 'terms-conditions', 'Terms & Conditions', 'Terms & Conditions', 'Terms & Conditions', '1', '2018-02-22 03:06:36', '2018-08-17 11:41:28', NULL),
(4, 'How it works', '<div class=\"how-it-work-wrapper\">\r\n<div class=\"how-it-block row\">\r\n<div class=\"img-block swap-right col-lg-6 col-md-6 col-sm-12 col-xs-12\"><img class=\"img-responsive\" src=\"front/images/how-it-work-img1.png\" alt=\"\" /></div>\r\n<div class=\"content col-lg-6 col-md-6 col-sm-12 col-xs-12 border-left\">\r\n<h1>Step 1</h1>\r\n<h4>It\'s Time To Vacation Planning</h4>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>\r\n<div class=\"border-vertical\">&nbsp;</div>\r\n</div>\r\n<div class=\"clearfix\">&nbsp;</div>\r\n</div>\r\n<div class=\"how-it-block row\">\r\n<div class=\"img-block col-lg-6 col-md-6 col-sm-12 col-xs-12\"><img class=\"img-responsive\" src=\"front/images/how-it-work-img2.png\" alt=\"\" /></div>\r\n<div class=\"content text-left col-lg-6 col-md-6 col-sm-12 col-xs-12 \">\r\n<h1>Step 2</h1>\r\n<h4>Search Your Favourite Place</h4>\r\n<p class=\"cnt-right-hw\">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>\r\n</div>\r\n<div class=\"clearfix\">&nbsp;</div>\r\n</div>\r\n<div class=\"how-it-block row\">\r\n<div class=\"img-block swap-right col-lg-6 col-md-6 col-sm-12 col-xs-12\"><img class=\"img-responsive\" src=\"front/images/how-it-work-img3.png\" alt=\"\" /></div>\r\n<div class=\"content col-lg-6 col-md-6 col-sm-12 col-xs-12 cnt-right-hw hw-ti-wks\">\r\n<h1>Step 3</h1>\r\n<h4>Book Your Home</h4>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>\r\n</div>\r\n<div class=\"clearfix\">&nbsp;</div>\r\n</div>\r\n<div class=\"how-it-block row\">\r\n<div class=\"img-block col-lg-6 col-md-6 col-sm-12 col-xs-12 cnt-right-hw hw-ti-wks\"><img class=\"img-responsive\" src=\"front/images/how-it-work-img4.png\" alt=\"\" /></div>\r\n<div class=\"content text-left col-lg-6 col-md-6 col-sm-12 col-xs-12\">\r\n<h1>Step 4</h1>\r\n<h4>Go To Your Favourite Place</h4>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>\r\n</div>\r\n<div class=\"clearfix\">&nbsp;</div>\r\n</div>\r\n<div class=\"how-it-block row\">\r\n<div class=\"img-block swap-right col-lg-6 col-md-6 col-sm-12 col-xs-12\"><img class=\"img-responsive\" src=\"front/images/how-it-work-img5.png\" alt=\"\" /></div>\r\n<div class=\"content col-lg-6 col-md-6 col-sm-12 col-xs-12 cnt-right-hw hw-ti-wks\">\r\n<h1>Step 5</h1>\r\n<h4>Enjoy Your Vacation</h4>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>\r\n</div>\r\n<div class=\"clearfix\">&nbsp;</div>\r\n</div>\r\n</div>', 'how-it-works', 'Metakeyword', 'Metatitle', 'Metadescription', '1', '2018-03-05 02:15:15', '2018-08-17 10:58:32', NULL),
(5, 'About us', '<div class=\"about-who-we-are-main\">\r\n<div class=\"row\">\r\n<div class=\"col-sm-6 col-md-7 col-lg-7 right\">\r\n<div class=\"heading-titel-min\">\r\n<div class=\"heading-titel\">Deserunt mollitia suscipit accusamus</div>\r\n</div>\r\n<div class=\"who-we-phara\">Lonimi illo perspiciatis, at ea dicta asperiores libero nihil iste tenetur placeat architecto hic expedita natus volupta temsit asperiores labore consequatur quidem temporibus nemo voluptatum quasi.</div>\r\n<div class=\"who-we-phara\">Eveniet quaerat temporibus earum quam quisquam, nihil iusto ab dolores culpa ducimus laborum, porro quis hic quia! Deserunt mollitia suscipit deleniti accusamus maiores. Voluptatum animi illo perspiciatis libero nihil iste tenetur.Lonimi illo perspiciatis, at ea dicta asperiores libero nihil iste tenetur placeat architecto hic expedita natus volupta temsit asperiores labore consequatur quidem temporibus nemo voluptatum quasi.</div>\r\n</div>\r\n<div class=\"col-sm-6 col-md-5 col-lg-5\">\r\n<div class=\"about-who-we-img-blo\"><img src=\"front/images/about-us-content-img-1.jpg\" alt=\"\" /></div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"about-who-we-are-main\">\r\n<div class=\"row\">\r\n<div class=\"col-sm-6 col-md-7 col-lg-7\">\r\n<div class=\"heading-titel-min\">\r\n<div class=\"heading-titel\">Eveniet quaerat temporibus</div>\r\n</div>\r\n<div class=\"who-we-phara\">Lonimi illo perspiciatis, at ea dicta asperiores libero nihil iste tenetur placeat architecto hic expedita natus volupta temsit asperiores labore consequatur quidem temporibus nemo voluptatum quasi. Eveniet quaerat temporibus earum quam quisquam, nihil iusto ab dolores culpa ducimus laborum, porro quis hic quia! Deserunt mollitia suscipit deleniti accusamus maiores. Voluptatum animi illo perspiciatis libero nihil iste tenetur.Lonimi illo perspiciatis, at ea dicta asperiores libero nihil iste tenetur.</div>\r\n</div>\r\n<div class=\"col-sm-6 col-md-5 col-lg-5\">\r\n<div class=\"about-who-we-img-blo\"><img src=\"front/images/about-us-content-img-2.jpg\" alt=\"\" /></div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"about-who-we-are-main no-mrgin\">\r\n<div class=\"row\">\r\n<div class=\"col-sm-6 col-md-7 col-lg-7 right\">\r\n<div class=\"heading-titel-min\">\r\n<div class=\"heading-titel\">Deserunt mollitia suscipit accusamus</div>\r\n</div>\r\n<div class=\"who-we-phara\">Lonimi illo perspiciatis, at ea dicta asperiores libero nihil iste tenetur placeat architecto hic expedita natus volupta temsit asperiores labore consequatur quidem temporibus nemo voluptatum quasi. Eveniet quaerat temporibus earum quam quisquam, nihil iusto ab dolores culpa ducimus laborum, porro quis hic quia! Deserunt mollitia suscipit deleniti accusamus maiores.</div>\r\n<div class=\"who-we-phara\">Eveniet quaerat temporibus earum quam quisquam, nihil iusto ab dolores culpa ducimus laborum, porro quis hic quia! Deserunt mollitia suscipit deleniti accusamus maiores. Voluptatum animi illo perspiciatis.</div>\r\n</div>\r\n<div class=\"col-sm-6 col-md-5 col-lg-5\">\r\n<div class=\"about-who-we-img-blo\"><img src=\"front/images/about-us-content-img-3.jpg\" alt=\"\" /></div>\r\n</div>\r\n</div>\r\n</div>', 'about-us', 'Meta-title', 'About shareous', 'Page-description', '1', '2018-03-05 03:07:26', '2018-08-17 11:03:17', NULL),
(7, 'privacy policy', '<div>\r\n<h2>What is Lorem Ipsum?</h2>\r\n<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n</div>\r\n<div>\r\n<h2>Why do we use it?</h2>\r\n<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>\r\n</div>\r\n<p>&nbsp;</p>\r\n<div>\r\n<h2>Where does it come from?</h2>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>\r\n</div>\r\n<div>\r\n<h2>Where can I get some?</h2>\r\n<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>\r\n</div>', 'privacy-policy', 'privacy-policy', 'privacy-policy', 'privacy-policy', '1', '2018-03-05 03:12:07', '2018-03-05 03:12:07', NULL),
(8, 'refund policy', '<p>Refund Policy</p>', 'refund-policy', 'Refund Policy', 'Refund Policy', 'Refund Policy', '1', '2018-07-06 03:56:49', '2018-07-06 03:56:49', NULL),
(9, 'host terms and conditions', '<p>Host terms and conditions</p>', 'host-terms-and-conditions', 'Host terms and conditions', 'Host terms and conditions', 'Host terms and conditions', '1', '2018-08-13 10:54:43', '2018-08-13 10:54:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `gst_data`
--

CREATE TABLE `gst_data` (
  `id` int(11) NOT NULL,
  `property_type` varchar(255) DEFAULT NULL,
  `min_price` int(11) NOT NULL,
  `max_price` int(11) NOT NULL,
  `gst` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gst_data`
--

INSERT INTO `gst_data` (`id`, `property_type`, `min_price`, `max_price`, `gst`, `created_at`, `updated_at`) VALUES
(1, 'other', 0, 1000, 0, '2018-12-04 04:56:16', '2018-12-14 05:24:50'),
(2, 'other', 1000, 2499, 12, '2018-12-04 04:56:16', '2018-12-14 05:24:54'),
(3, 'other', 2500, 7499, 18, '2018-12-04 04:57:19', '2018-12-14 05:24:56'),
(5, 'other', 7500, 999999999, 28, '2018-12-04 05:00:27', '2018-12-14 05:24:58'),
(6, 'warehouse', 0, 0, 18, '2018-12-14 05:27:05', '0000-00-00 00:00:00'),
(7, 'office-space', 0, 0, 18, '2018-12-14 05:27:05', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `host_verification_request`
--

CREATE TABLE `host_verification_request` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `support_user_id` int(11) NOT NULL,
  `request_id` varchar(255) NOT NULL,
  `id_proof` text NOT NULL,
  `id_proof_name` varchar(255) NOT NULL,
  `photo` text NOT NULL,
  `photo_name` varchar(255) NOT NULL,
  `status` enum('0','1','2','3') NOT NULL COMMENT '0-pending 1-approved 2-rejected 3-process',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `host_verification_request`
--

INSERT INTO `host_verification_request` (`id`, `user_id`, `support_user_id`, `request_id`, `id_proof`, `id_proof_name`, `photo`, `photo_name`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 5, 2, 'S_47d75d32c2926678', '554899f668f37af4b92e4c91217f7b3e5939e30c.jpg', 'e6aeb8834e10d152c07977469098f74ccb63b280.jpg', '849a771e2ce43f810db082c0389931e4288c57af.jpg', '3763_minions.jpg', '1', '2018-05-29 11:10:05', '2018-05-29 11:21:46', NULL),
(2, 3, 14, 'S_5f5a635821218fde', '8acd706d652f5735fcbc25a1d16454489b9cf51e.jpg', '360x495.jpg', 'a9981319eff153b5b477eb454d66aabbb4f66e1d.jpg', '576966cde615b.jpg', '1', '2018-05-29 11:47:00', '2018-08-02 12:25:38', NULL),
(3, 2, 2, 'S_c76438e05012921d', '3a837408dc89e79f786cc4b5759aabd2a5dbe44e.JPG', '702575b0863f194b87.JPG', '42720bbfd2c6e4e9a583460c022e9dfc08805538.jpg', 'e6aeb8834e10d152c07977469098f74ccb63b280.jpg', '1', '2018-05-29 12:40:52', '2018-05-29 12:44:24', NULL),
(4, 4, 2, 'S_48cf127e2078128a', 'e1f313e5b597420055714fa44e9c38ddb4f79ced.png', 'gallery-img3.png', 'f3a3ed87688d34041b2855e3d3427891a7846c6b.png', 'home-img-tops.png', '1', '2018-06-01 02:34:39', '2018-06-01 02:36:45', NULL),
(5, 14, 14, 'S_74e463bc1db5bc28', '0d59a65590b74e25af865309a412efa63db12f60.jpg', '602471_442255832490650_983906953_n.jpg', 'a5eb1864cf1d304e36f40c12cbc6d89f886828ce.jpg', '602471_442255832490650_983906953_n.jpg', '1', '2018-06-02 11:38:47', '2018-06-02 12:20:17', NULL),
(6, 1, 14, 'S_adddb094f8afca0e', '06245ef8ec3f90a5242e229cbff4233f79037515.png', '3mb1.png', '2910cadb03bb5723c7669925ffc89d743da05a8b.png', '3mb1.png', '1', '2018-06-08 00:34:03', '2018-08-02 12:25:25', NULL),
(7, 22, 14, 'S_a8823d765a9330c2', '9645eab5701dfa8c6033329f41a65c8663f8902f.jpg', '87a35a218427d1646f05d4b178f67800.jpg', '4269f478821131e5a7e93dbe5981f7660acbaa8f.jpg', '57df848d3c471.jpg', '1', '2018-06-10 23:56:16', '2018-06-11 00:03:50', NULL),
(8, 27, 14, 'S_9d122ce7970b2320', '1ad1132eb017ed7378f251204495c198b729c0f9.jpg', 'googleinc.jpg', 'cc532894ec382dd5d259fe155ee173f61bbb4330.jpg', 'googleinc.jpg', '1', '2018-06-15 04:14:56', '2018-06-15 04:21:38', NULL),
(9, 35, 10, 'S_894cd5c1048a9b94', '499349f4a787f1d59f0a6eb1303607736268634e.pdf', 'WebmasterChecklist.pdf', '200ad60c5bc8ffb5792428d1a12def9e08d035ae.png', 'tutsplus-profile.png', '1', '2018-07-08 23:27:37', '2018-07-08 23:33:40', NULL),
(10, 45, 14, 'S_a868993e95f22fbd', 'da565920162a74127f3cb8fa8bc3de2031a82350.PNG', 'asset.PNG', 'e762af4b41f41540103e870bb435271f38419690.PNG', 'asset.PNG', '1', '2018-07-11 07:15:02', '2018-07-12 03:04:57', NULL),
(11, 44, 14, 'S_eaaa11bb0daf5776', '01526533a380672b7423b8aef611568c1a35416a.jpg', 'a866b5aef10a02b4a6275e71ac0d175b87911d88.jpg', 'ecbad1e4b07f5eb1e3f6028d2613aa3f6d467598.jpg', 'a866b5aef10a02b4a6275e71ac0d175b87911d88.jpg', '1', '2018-07-11 07:20:11', '2018-07-11 07:28:50', NULL),
(12, 15, 10, 'S_baa232439c0f1ce1', 'c47ec8c9a48788545fd0211a019fddc8a121d00e.jpg', '119_103153.jpg', 'a29e686deb508f1567311bf14daf558eca2556dd.png', '121_105116.png', '1', '2018-07-13 11:26:45', '2018-07-13 11:29:48', NULL),
(13, 50, 14, 'S_c89722773208a1c7', 'e9a4ffdc3590e0943409dc420f6a8c77b790a40b.jpg', '2013_despicable_me_2_minions-wide.jpg', 'a1228c09587e3593052975b06d8dde4261607fc3.jpg', 'TakeBackPCbots.jpg', '1', '2018-07-17 05:52:22', '2018-08-02 12:25:14', NULL),
(14, 49, 14, 'S_8d4aca3dbfb4d966', 'ebec4ed174f2d1c729def2a449ac1e8f2b6b054a.jpg', 'truckimage.jpg', '8d60e428443f3291d372d5ac9ae5943c890256f4.jpg', 'truckimage.jpg', '1', '2018-07-17 07:32:48', '2018-08-02 12:25:00', NULL),
(15, 51, 14, 'S_a829cfa54e614a92', 'd3f5f168010f534708e4e549e754bfdc0780d522.jpg', 'red-bg-new.jpg', 'd3b595c41cdbec906be19868f5e3d2925e03f9a9.jpg', 'red-bg-new.jpg', '1', '2018-07-17 12:42:11', '2018-07-18 09:30:42', NULL),
(16, 52, 14, 'S_b60c6433d823f267', '3ab7d653ceaa4a163918029551cf18ec114f9344.pdf', 'constructioncenterdd099ce50ec18a4a24ef3baf0c5f85bfe555493f.pdf', '3daf9e64b835f5a4bc30af27fc3f8b54ec5ef120.pdf', 'constructioncenterdd099ce50ec18a4a24ef3baf0c5f85bfe555493f.pdf', '1', '2018-07-17 12:46:56', '2018-07-18 09:35:16', NULL),
(17, 68, 14, 'S_258eaa9c96867a2e', '2285cad3e4034bf358bb1efae4543784d8d6bc1b.pdf', 'constructioncenter6baf530b60cad6d73c3832e387b77a159895d7b6.pdf', 'f43b7bb036fea8972d5c58a5d1f3b417e8120bc1.pdf', 'constructioncenter6baf530b60cad6d73c3832e387b77a159895d7b6.pdf', '1', '2018-07-24 13:56:36', '2018-07-24 14:01:07', NULL),
(18, 90, 10, 'S_bbe9aa7f53e7d751', '38ed3d399a17f4ef2fd2234ca2c8d4f431b39204.png', 'device-2016-10-21-143010.png', '00358ba37d2540eabd1df00051d5ffd0a1654273.png', 'device-2016-10-21-143010.png', '1', '2018-08-05 08:43:06', '2018-08-05 08:48:30', NULL),
(19, 103, 0, 'S_2769e530e2d8e36d', 'b47cda8b83baafb3ff48715cda0061cda0b49e8a.jpg', 'Hotel3.jpg', '4b885b91b6eb553dc7669b35cac2b3682739abb4.jpeg', 'food4.jpeg', '0', '2018-08-28 00:11:26', '2018-08-28 00:11:26', NULL),
(20, 106, 2, 'S_ecd7e878642cc532', '7fe4a514c2559886f6d2a9f817257f94b40d04a7.jpg', 'DragonBridge_EN-US11956700156_1920x1200.jpg', '46de07e5f9f7244fc4541676838cd84e12243281.jpg', 'SevenMagicMountains_EN-US9207394593_1920x1080.jpg', '1', '2018-09-04 15:01:00', '2018-09-04 15:09:21', NULL),
(21, 107, 2, 'S_4315bc4e3210a99c', '107-iron_man_artwork_2-1920x1080.jpg', 'iron_man_artwork_2-1920x1080.jpg', '107-iron_man_hong_kong_disneyland_4k_8k-1366x768 (copy).jpg', 'iron_man_hong_kong_disneyland_4k_8k-1366x768 (copy).jpg', '2', '2018-09-05 09:25:56', '2018-09-11 11:59:48', NULL),
(22, 110, 0, 'S_7203b13cc9e59e07', 'b3da2202126eebd8c21d0f4c79e182f5e03a391a.jpeg', 'pexels-photo-439853.jpeg', 'f8d8a59fd1334067cad91c08b63a4b98d7344c05.jpeg', 'images.jpeg', '0', '2018-09-14 13:06:43', '2018-09-14 13:06:43', NULL),
(23, 12, 2, 'S_c51cc829ee618d4c', '9299e46651f74b940388bbaa74747d63251e487d.pdf', 'Invoice77712.pdf', 'eb4032ac6820fbe3328f8bed8d16888b753299ae.pdf', 'Invoice77112.pdf', '2', '2018-09-20 13:50:33', '2018-09-24 10:49:36', NULL),
(24, 12, 2, 'S_1b5ad0f720e8fe18', '67977584b9e8e23965354cef74a7e3fd17c29bdd.jpg', 'neon_sports_car-2560x1440.jpg', 'f4ca80a14b3787780501ddabb2bd8d569c373471.png', 'Deepak.png', '3', '2018-09-24 10:51:06', '2018-10-20 09:54:16', NULL),
(25, 118, 2, 'S_4eaa6e1f74e7eb32', '5da18bfa148c739e1281bfaf76e86c64072215f5.jpg', 'MonumentFountain_EN-US10536043652_1920x1200.jpg', '652d1450ed6da95bd05be1e15789fd830a78dd5f.jpg', 'Honeycomb_EN-US7568111738_1920x1200.jpg', '3', '2018-10-05 10:41:04', '2018-10-05 10:41:24', NULL),
(26, 114, 2, 'S_0a53701d1bb190be', '9221e30ea4132bce1aa7a978422b8e85d778a564.jpg', 'Cute-Whatsapp-DP.jpg', '4a94c8cc570195fc88d718a5228d5cb9ac8567b7.jpg', 'aadhaar_front_1507626471.jpg', '3', '2018-11-26 04:26:49', '2018-11-26 04:27:00', NULL),
(27, 115, 2, 'S_8712662d4a143949', 'cd2f1cc98e6db6662b94655cc85d16c49b42155b.png', '3938d38d451bc731d2371cb3fda194a2.png', '1112207d07ea81636fbec7b7ac154f1063c4f37b.PNG', 'Price 2.PNG', '1', '2018-12-11 06:50:02', '2018-12-11 07:00:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mobile_country_code`
--

CREATE TABLE `mobile_country_code` (
  `id` int(11) NOT NULL,
  `iso` char(2) NOT NULL,
  `name` varchar(80) NOT NULL,
  `nicename` varchar(80) NOT NULL,
  `iso3` char(3) DEFAULT NULL,
  `numcode` smallint(6) DEFAULT NULL,
  `phonecode` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mobile_country_code`
--

INSERT INTO `mobile_country_code` (`id`, `iso`, `name`, `nicename`, `iso3`, `numcode`, `phonecode`) VALUES
(1, 'AF', 'AFGHANISTAN', 'Afghanistan', 'AFG', 4, 93),
(2, 'AL', 'ALBANIA', 'Albania', 'ALB', 8, 355),
(3, 'DZ', 'ALGERIA', 'Algeria', 'DZA', 12, 213),
(4, 'AS', 'AMERICAN SAMOA', 'American Samoa', 'ASM', 16, 1684),
(5, 'AD', 'ANDORRA', 'Andorra', 'AND', 20, 376),
(6, 'AO', 'ANGOLA', 'Angola', 'AGO', 24, 244),
(7, 'AI', 'ANGUILLA', 'Anguilla', 'AIA', 660, 1264),
(9, 'AG', 'ANTIGUA AND BARBUDA', 'Antigua and Barbuda', 'ATG', 28, 1268),
(10, 'AR', 'ARGENTINA', 'Argentina', 'ARG', 32, 54),
(11, 'AM', 'ARMENIA', 'Armenia', 'ARM', 51, 374),
(12, 'AW', 'ARUBA', 'Aruba', 'ABW', 533, 297),
(13, 'AU', 'AUSTRALIA', 'Australia', 'AUS', 36, 61),
(14, 'AT', 'AUSTRIA', 'Austria', 'AUT', 40, 43),
(15, 'AZ', 'AZERBAIJAN', 'Azerbaijan', 'AZE', 31, 994),
(16, 'BS', 'BAHAMAS', 'Bahamas', 'BHS', 44, 1242),
(17, 'BH', 'BAHRAIN', 'Bahrain', 'BHR', 48, 973),
(18, 'BD', 'BANGLADESH', 'Bangladesh', 'BGD', 50, 880),
(19, 'BB', 'BARBADOS', 'Barbados', 'BRB', 52, 1246),
(20, 'BY', 'BELARUS', 'Belarus', 'BLR', 112, 375),
(21, 'BE', 'BELGIUM', 'Belgium', 'BEL', 56, 32),
(22, 'BZ', 'BELIZE', 'Belize', 'BLZ', 84, 501),
(23, 'BJ', 'BENIN', 'Benin', 'BEN', 204, 229),
(24, 'BM', 'BERMUDA', 'Bermuda', 'BMU', 60, 1441),
(25, 'BT', 'BHUTAN', 'Bhutan', 'BTN', 64, 975),
(26, 'BO', 'BOLIVIA', 'Bolivia', 'BOL', 68, 591),
(27, 'BA', 'BOSNIA AND HERZEGOVINA', 'Bosnia and Herzegovina', 'BIH', 70, 387),
(28, 'BW', 'BOTSWANA', 'Botswana', 'BWA', 72, 267),
(30, 'BR', 'BRAZIL', 'Brazil', 'BRA', 76, 55),
(32, 'BN', 'BRUNEI DARUSSALAM', 'Brunei Darussalam', 'BRN', 96, 673),
(33, 'BG', 'BULGARIA', 'Bulgaria', 'BGR', 100, 359),
(34, 'BF', 'BURKINA FASO', 'Burkina Faso', 'BFA', 854, 226),
(35, 'BI', 'BURUNDI', 'Burundi', 'BDI', 108, 257),
(36, 'KH', 'CAMBODIA', 'Cambodia', 'KHM', 116, 855),
(37, 'CM', 'CAMEROON', 'Cameroon', 'CMR', 120, 237),
(38, 'CA', 'CANADA', 'Canada', 'CAN', 124, 1),
(39, 'CV', 'CAPE VERDE', 'Cape Verde', 'CPV', 132, 238),
(40, 'KY', 'CAYMAN ISLANDS', 'Cayman Islands', 'CYM', 136, 1345),
(41, 'CF', 'CENTRAL AFRICAN REPUBLIC', 'Central African Republic', 'CAF', 140, 236),
(42, 'TD', 'CHAD', 'Chad', 'TCD', 148, 235),
(43, 'CL', 'CHILE', 'Chile', 'CHL', 152, 56),
(44, 'CN', 'CHINA', 'China', 'CHN', 156, 86),
(47, 'CO', 'COLOMBIA', 'Colombia', 'COL', 170, 57),
(48, 'KM', 'COMOROS', 'Comoros', 'COM', 174, 269),
(49, 'CG', 'CONGO', 'Congo', 'COG', 178, 242),
(50, 'CD', 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'Congo, the Democratic Republic of the', 'COD', 180, 242),
(51, 'CK', 'COOK ISLANDS', 'Cook Islands', 'COK', 184, 682),
(52, 'CR', 'COSTA RICA', 'Costa Rica', 'CRI', 188, 506),
(53, 'CI', 'COTE D\'IVOIRE', 'Cote D\'Ivoire', 'CIV', 384, 225),
(54, 'HR', 'CROATIA', 'Croatia', 'HRV', 191, 385),
(55, 'CU', 'CUBA', 'Cuba', 'CUB', 192, 53),
(56, 'CY', 'CYPRUS', 'Cyprus', 'CYP', 196, 357),
(57, 'CZ', 'CZECH REPUBLIC', 'Czech Republic', 'CZE', 203, 420),
(58, 'DK', 'DENMARK', 'Denmark', 'DNK', 208, 45),
(59, 'DJ', 'DJIBOUTI', 'Djibouti', 'DJI', 262, 253),
(60, 'DM', 'DOMINICA', 'Dominica', 'DMA', 212, 1767),
(61, 'DO', 'DOMINICAN REPUBLIC', 'Dominican Republic', 'DOM', 214, 1809),
(62, 'EC', 'ECUADOR', 'Ecuador', 'ECU', 218, 593),
(63, 'EG', 'EGYPT', 'Egypt', 'EGY', 818, 20),
(64, 'SV', 'EL SALVADOR', 'El Salvador', 'SLV', 222, 503),
(65, 'GQ', 'EQUATORIAL GUINEA', 'Equatorial Guinea', 'GNQ', 226, 240),
(66, 'ER', 'ERITREA', 'Eritrea', 'ERI', 232, 291),
(67, 'EE', 'ESTONIA', 'Estonia', 'EST', 233, 372),
(68, 'ET', 'ETHIOPIA', 'Ethiopia', 'ETH', 231, 251),
(69, 'FK', 'FALKLAND ISLANDS (MALVINAS)', 'Falkland Islands (Malvinas)', 'FLK', 238, 500),
(70, 'FO', 'FAROE ISLANDS', 'Faroe Islands', 'FRO', 234, 298),
(71, 'FJ', 'FIJI', 'Fiji', 'FJI', 242, 679),
(72, 'FI', 'FINLAND', 'Finland', 'FIN', 246, 358),
(73, 'FR', 'FRANCE', 'France', 'FRA', 250, 33),
(74, 'GF', 'FRENCH GUIANA', 'French Guiana', 'GUF', 254, 594),
(75, 'PF', 'FRENCH POLYNESIA', 'French Polynesia', 'PYF', 258, 689),
(77, 'GA', 'GABON', 'Gabon', 'GAB', 266, 241),
(78, 'GM', 'GAMBIA', 'Gambia', 'GMB', 270, 220),
(79, 'GE', 'GEORGIA', 'Georgia', 'GEO', 268, 995),
(80, 'DE', 'GERMANY', 'Germany', 'DEU', 276, 49),
(81, 'GH', 'GHANA', 'Ghana', 'GHA', 288, 233),
(82, 'GI', 'GIBRALTAR', 'Gibraltar', 'GIB', 292, 350),
(83, 'GR', 'GREECE', 'Greece', 'GRC', 300, 30),
(84, 'GL', 'GREENLAND', 'Greenland', 'GRL', 304, 299),
(85, 'GD', 'GRENADA', 'Grenada', 'GRD', 308, 1473),
(86, 'GP', 'GUADELOUPE', 'Guadeloupe', 'GLP', 312, 590),
(87, 'GU', 'GUAM', 'Guam', 'GUM', 316, 1671),
(88, 'GT', 'GUATEMALA', 'Guatemala', 'GTM', 320, 502),
(89, 'GN', 'GUINEA', 'Guinea', 'GIN', 324, 224),
(90, 'GW', 'GUINEA-BISSAU', 'Guinea-Bissau', 'GNB', 624, 245),
(91, 'GY', 'GUYANA', 'Guyana', 'GUY', 328, 592),
(92, 'HT', 'HAITI', 'Haiti', 'HTI', 332, 509),
(94, 'VA', 'HOLY SEE (VATICAN CITY STATE)', 'Holy See (Vatican City State)', 'VAT', 336, 39),
(95, 'HN', 'HONDURAS', 'Honduras', 'HND', 340, 504),
(96, 'HK', 'HONG KONG', 'Hong Kong', 'HKG', 344, 852),
(97, 'HU', 'HUNGARY', 'Hungary', 'HUN', 348, 36),
(98, 'IS', 'ICELAND', 'Iceland', 'ISL', 352, 354),
(99, 'IN', 'INDIA', 'India', 'IND', 356, 91),
(100, 'ID', 'INDONESIA', 'Indonesia', 'IDN', 360, 62),
(101, 'IR', 'IRAN, ISLAMIC REPUBLIC OF', 'Iran, Islamic Republic of', 'IRN', 364, 98),
(102, 'IQ', 'IRAQ', 'Iraq', 'IRQ', 368, 964),
(103, 'IE', 'IRELAND', 'Ireland', 'IRL', 372, 353),
(104, 'IL', 'ISRAEL', 'Israel', 'ISR', 376, 972),
(105, 'IT', 'ITALY', 'Italy', 'ITA', 380, 39),
(106, 'JM', 'JAMAICA', 'Jamaica', 'JAM', 388, 1876),
(107, 'JP', 'JAPAN', 'Japan', 'JPN', 392, 81),
(108, 'JO', 'JORDAN', 'Jordan', 'JOR', 400, 962),
(109, 'KZ', 'KAZAKHSTAN', 'Kazakhstan', 'KAZ', 398, 7),
(110, 'KE', 'KENYA', 'Kenya', 'KEN', 404, 254),
(111, 'KI', 'KIRIBATI', 'Kiribati', 'KIR', 296, 686),
(112, 'KP', 'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF', 'Korea, Democratic People\'s Republic of', 'PRK', 408, 850),
(113, 'KR', 'KOREA, REPUBLIC OF', 'Korea, Republic of', 'KOR', 410, 82),
(114, 'KW', 'KUWAIT', 'Kuwait', 'KWT', 414, 965),
(115, 'KG', 'KYRGYZSTAN', 'Kyrgyzstan', 'KGZ', 417, 996),
(116, 'LA', 'LAO PEOPLE\'S DEMOCRATIC REPUBLIC', 'Lao People\'s Democratic Republic', 'LAO', 418, 856),
(117, 'LV', 'LATVIA', 'Latvia', 'LVA', 428, 371),
(118, 'LB', 'LEBANON', 'Lebanon', 'LBN', 422, 961),
(119, 'LS', 'LESOTHO', 'Lesotho', 'LSO', 426, 266),
(120, 'LR', 'LIBERIA', 'Liberia', 'LBR', 430, 231),
(121, 'LY', 'LIBYAN ARAB JAMAHIRIYA', 'Libyan Arab Jamahiriya', 'LBY', 434, 218),
(122, 'LI', 'LIECHTENSTEIN', 'Liechtenstein', 'LIE', 438, 423),
(123, 'LT', 'LITHUANIA', 'Lithuania', 'LTU', 440, 370),
(124, 'LU', 'LUXEMBOURG', 'Luxembourg', 'LUX', 442, 352),
(125, 'MO', 'MACAO', 'Macao', 'MAC', 446, 853),
(126, 'MK', 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'Macedonia, the Former Yugoslav Republic of', 'MKD', 807, 389),
(127, 'MG', 'MADAGASCAR', 'Madagascar', 'MDG', 450, 261),
(128, 'MW', 'MALAWI', 'Malawi', 'MWI', 454, 265),
(129, 'MY', 'MALAYSIA', 'Malaysia', 'MYS', 458, 60),
(130, 'MV', 'MALDIVES', 'Maldives', 'MDV', 462, 960),
(131, 'ML', 'MALI', 'Mali', 'MLI', 466, 223),
(132, 'MT', 'MALTA', 'Malta', 'MLT', 470, 356),
(133, 'MH', 'MARSHALL ISLANDS', 'Marshall Islands', 'MHL', 584, 692),
(134, 'MQ', 'MARTINIQUE', 'Martinique', 'MTQ', 474, 596),
(135, 'MR', 'MAURITANIA', 'Mauritania', 'MRT', 478, 222),
(136, 'MU', 'MAURITIUS', 'Mauritius', 'MUS', 480, 230),
(138, 'MX', 'MEXICO', 'Mexico', 'MEX', 484, 52),
(139, 'FM', 'MICRONESIA, FEDERATED STATES OF', 'Micronesia, Federated States of', 'FSM', 583, 691),
(140, 'MD', 'MOLDOVA, REPUBLIC OF', 'Moldova, Republic of', 'MDA', 498, 373),
(141, 'MC', 'MONACO', 'Monaco', 'MCO', 492, 377),
(142, 'MN', 'MONGOLIA', 'Mongolia', 'MNG', 496, 976),
(143, 'MS', 'MONTSERRAT', 'Montserrat', 'MSR', 500, 1664),
(144, 'MA', 'MOROCCO', 'Morocco', 'MAR', 504, 212),
(145, 'MZ', 'MOZAMBIQUE', 'Mozambique', 'MOZ', 508, 258),
(146, 'MM', 'MYANMAR', 'Myanmar', 'MMR', 104, 95),
(147, 'NA', 'NAMIBIA', 'Namibia', 'NAM', 516, 264),
(148, 'NR', 'NAURU', 'Nauru', 'NRU', 520, 674),
(149, 'NP', 'NEPAL', 'Nepal', 'NPL', 524, 977),
(150, 'NL', 'NETHERLANDS', 'Netherlands', 'NLD', 528, 31),
(151, 'AN', 'NETHERLANDS ANTILLES', 'Netherlands Antilles', 'ANT', 530, 599),
(152, 'NC', 'NEW CALEDONIA', 'New Caledonia', 'NCL', 540, 687),
(153, 'NZ', 'NEW ZEALAND', 'New Zealand', 'NZL', 554, 64),
(154, 'NI', 'NICARAGUA', 'Nicaragua', 'NIC', 558, 505),
(155, 'NE', 'NIGER', 'Niger', 'NER', 562, 227),
(156, 'NG', 'NIGERIA', 'Nigeria', 'NGA', 566, 234),
(157, 'NU', 'NIUE', 'Niue', 'NIU', 570, 683),
(158, 'NF', 'NORFOLK ISLAND', 'Norfolk Island', 'NFK', 574, 672),
(159, 'MP', 'NORTHERN MARIANA ISLANDS', 'Northern Mariana Islands', 'MNP', 580, 1670),
(160, 'NO', 'NORWAY', 'Norway', 'NOR', 578, 47),
(161, 'OM', 'OMAN', 'Oman', 'OMN', 512, 968),
(162, 'PK', 'PAKISTAN', 'Pakistan', 'PAK', 586, 92),
(163, 'PW', 'PALAU', 'Palau', 'PLW', 585, 680),
(165, 'PA', 'PANAMA', 'Panama', 'PAN', 591, 507),
(166, 'PG', 'PAPUA NEW GUINEA', 'Papua New Guinea', 'PNG', 598, 675),
(167, 'PY', 'PARAGUAY', 'Paraguay', 'PRY', 600, 595),
(168, 'PE', 'PERU', 'Peru', 'PER', 604, 51),
(169, 'PH', 'PHILIPPINES', 'Philippines', 'PHL', 608, 63),
(170, 'PN', 'PITCAIRN', 'Pitcairn', 'PCN', 612, 0),
(171, 'PL', 'POLAND', 'Poland', 'POL', 616, 48),
(172, 'PT', 'PORTUGAL', 'Portugal', 'PRT', 620, 351),
(173, 'PR', 'PUERTO RICO', 'Puerto Rico', 'PRI', 630, 1787),
(174, 'QA', 'QATAR', 'Qatar', 'QAT', 634, 974),
(175, 'RE', 'REUNION', 'Reunion', 'REU', 638, 262),
(176, 'RO', 'ROMANIA', 'Romania', 'ROM', 642, 40),
(177, 'RU', 'RUSSIAN FEDERATION', 'Russian Federation', 'RUS', 643, 70),
(178, 'RW', 'RWANDA', 'Rwanda', 'RWA', 646, 250),
(179, 'SH', 'SAINT HELENA', 'Saint Helena', 'SHN', 654, 290),
(180, 'KN', 'SAINT KITTS AND NEVIS', 'Saint Kitts and Nevis', 'KNA', 659, 1869),
(181, 'LC', 'SAINT LUCIA', 'Saint Lucia', 'LCA', 662, 1758),
(182, 'PM', 'SAINT PIERRE AND MIQUELON', 'Saint Pierre and Miquelon', 'SPM', 666, 508),
(183, 'VC', 'SAINT VINCENT AND THE GRENADINES', 'Saint Vincent and the Grenadines', 'VCT', 670, 1784),
(184, 'WS', 'SAMOA', 'Samoa', 'WSM', 882, 684),
(185, 'SM', 'SAN MARINO', 'San Marino', 'SMR', 674, 378),
(186, 'ST', 'SAO TOME AND PRINCIPE', 'Sao Tome and Principe', 'STP', 678, 239),
(187, 'SA', 'SAUDI ARABIA', 'Saudi Arabia', 'SAU', 682, 966),
(188, 'SN', 'SENEGAL', 'Senegal', 'SEN', 686, 221),
(190, 'SC', 'SEYCHELLES', 'Seychelles', 'SYC', 690, 248),
(191, 'SL', 'SIERRA LEONE', 'Sierra Leone', 'SLE', 694, 232),
(192, 'SG', 'SINGAPORE', 'Singapore', 'SGP', 702, 65),
(193, 'SK', 'SLOVAKIA', 'Slovakia', 'SVK', 703, 421),
(194, 'SI', 'SLOVENIA', 'Slovenia', 'SVN', 705, 386),
(195, 'SB', 'SOLOMON ISLANDS', 'Solomon Islands', 'SLB', 90, 677),
(196, 'SO', 'SOMALIA', 'Somalia', 'SOM', 706, 252),
(197, 'ZA', 'SOUTH AFRICA', 'South Africa', 'ZAF', 710, 27),
(199, 'ES', 'SPAIN', 'Spain', 'ESP', 724, 34),
(200, 'LK', 'SRI LANKA', 'Sri Lanka', 'LKA', 144, 94),
(201, 'SD', 'SUDAN', 'Sudan', 'SDN', 736, 249),
(202, 'SR', 'SURINAME', 'Suriname', 'SUR', 740, 597),
(203, 'SJ', 'SVALBARD AND JAN MAYEN', 'Svalbard and Jan Mayen', 'SJM', 744, 47),
(204, 'SZ', 'SWAZILAND', 'Swaziland', 'SWZ', 748, 268),
(205, 'SE', 'SWEDEN', 'Sweden', 'SWE', 752, 46),
(206, 'CH', 'SWITZERLAND', 'Switzerland', 'CHE', 756, 41),
(207, 'SY', 'SYRIAN ARAB REPUBLIC', 'Syrian Arab Republic', 'SYR', 760, 963),
(208, 'TW', 'TAIWAN, PROVINCE OF CHINA', 'Taiwan, Province of China', 'TWN', 158, 886),
(209, 'TJ', 'TAJIKISTAN', 'Tajikistan', 'TJK', 762, 992),
(210, 'TZ', 'TANZANIA, UNITED REPUBLIC OF', 'Tanzania, United Republic of', 'TZA', 834, 255),
(211, 'TH', 'THAILAND', 'Thailand', 'THA', 764, 66),
(213, 'TG', 'TOGO', 'Togo', 'TGO', 768, 228),
(214, 'TK', 'TOKELAU', 'Tokelau', 'TKL', 772, 690),
(215, 'TO', 'TONGA', 'Tonga', 'TON', 776, 676),
(216, 'TT', 'TRINIDAD AND TOBAGO', 'Trinidad and Tobago', 'TTO', 780, 1868),
(217, 'TN', 'TUNISIA', 'Tunisia', 'TUN', 788, 216),
(218, 'TR', 'TURKEY', 'Turkey', 'TUR', 792, 90),
(219, 'TM', 'TURKMENISTAN', 'Turkmenistan', 'TKM', 795, 7370),
(220, 'TC', 'TURKS AND CAICOS ISLANDS', 'Turks and Caicos Islands', 'TCA', 796, 1649),
(221, 'TV', 'TUVALU', 'Tuvalu', 'TUV', 798, 688),
(222, 'UG', 'UGANDA', 'Uganda', 'UGA', 800, 256),
(223, 'UA', 'UKRAINE', 'Ukraine', 'UKR', 804, 380),
(224, 'AE', 'UNITED ARAB EMIRATES', 'United Arab Emirates', 'ARE', 784, 971),
(225, 'GB', 'UNITED KINGDOM', 'United Kingdom', 'GBR', 826, 44),
(226, 'US', 'UNITED STATES', 'United States', 'USA', 840, 1),
(228, 'UY', 'URUGUAY', 'Uruguay', 'URY', 858, 598),
(229, 'UZ', 'UZBEKISTAN', 'Uzbekistan', 'UZB', 860, 998),
(230, 'VU', 'VANUATU', 'Vanuatu', 'VUT', 548, 678),
(231, 'VE', 'VENEZUELA', 'Venezuela', 'VEN', 862, 58),
(232, 'VN', 'VIET NAM', 'Viet Nam', 'VNM', 704, 84),
(233, 'VG', 'VIRGIN ISLANDS, BRITISH', 'Virgin Islands, British', 'VGB', 92, 1284),
(234, 'VI', 'VIRGIN ISLANDS, U.S.', 'Virgin Islands, U.s.', 'VIR', 850, 1340),
(235, 'WF', 'WALLIS AND FUTUNA', 'Wallis and Futuna', 'WLF', 876, 681),
(236, 'EH', 'WESTERN SAHARA', 'Western Sahara', 'ESH', 732, 212),
(237, 'YE', 'YEMEN', 'Yemen', 'YEM', 887, 967),
(238, 'ZM', 'ZAMBIA', 'Zambia', 'ZMB', 894, 260),
(239, 'ZW', 'ZIMBABWE', 'Zimbabwe', 'ZWE', 716, 263),
(240, 'RS', 'SERBIA', 'Serbia', 'SRB', 688, 381),
(242, 'ME', 'MONTENEGRO', 'Montenegro', 'MNE', 499, 382),
(243, 'AX', 'ALAND ISLANDS', 'Aland Islands', 'ALA', 248, 358),
(244, 'BQ', 'BONAIRE, SINT EUSTATIUS AND SABA', 'Bonaire, Sint Eustatius and Saba', 'BES', 535, 599),
(245, 'CW', 'CURACAO', 'Curacao', 'CUW', 531, 599),
(246, 'GG', 'GUERNSEY', 'Guernsey', 'GGY', 831, 44),
(247, 'IM', 'ISLE OF MAN', 'Isle of Man', 'IMN', 833, 44),
(248, 'JE', 'JERSEY', 'Jersey', 'JEY', 832, 44),
(250, 'BL', 'SAINT BARTHELEMY', 'Saint Barthelemy', 'BLM', 652, 590),
(251, 'MF', 'SAINT MARTIN', 'Saint Martin', 'MAF', 663, 590),
(252, 'SX', 'SINT MAARTEN', 'Sint Maarten', 'SXM', 534, 1),
(253, 'SS', 'SOUTH SUDAN', 'South Sudan', 'SSD', 728, 211);

-- --------------------------------------------------------

--
-- Table structure for table `my_favourite`
--

CREATE TABLE `my_favourite` (
  `id` int(50) NOT NULL,
  `user_id` int(50) NOT NULL,
  `property_id` int(50) NOT NULL,
  `status` enum('1','2') NOT NULL COMMENT '1=active 2=inactive',
  `favourite_from` varchar(256) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `my_favourite`
--

INSERT INTO `my_favourite` (`id`, `user_id`, `property_id`, `status`, `favourite_from`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 103, 204, '1', 'property listing', '2018-10-30 05:50:34', '2018-10-30 05:50:34', NULL),
(3, 103, 205, '1', 'property listing', '2018-11-14 12:42:15', '2018-11-14 12:42:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(50) NOT NULL,
  `sender_id` int(50) NOT NULL,
  `receiver_id` int(50) NOT NULL,
  `notification_text` varchar(500) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `is_read` enum('0','1','','') NOT NULL COMMENT '0=unread 1=read',
  `sender_type` enum('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1=guest, 2=admin, 3=support,4=host',
  `receiver_type` enum('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1=guest, 2=admin, 3=support,4=host',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `sender_id`, `receiver_id`, `notification_text`, `url`, `is_read`, `sender_type`, `receiver_type`, `created_at`, `updated_at`, `deleted_date`) VALUES
(1, 1, 103, 'Payment successfully paid for the Tushar WareHouse ', '/my-booking/confirmed', '1', '2', '1', '2018-10-03 12:18:58', '2018-12-05 06:26:35', NULL),
(2, 1, 52, 'Payment successfully received for the Tushar WareHouse by the Umesh ', '/my-booking/confirmed', '1', '2', '4', '2018-10-03 12:19:04', '2018-10-06 10:06:34', NULL),
(3, 1, 103, 'Payment successfully paid for the Tushar WareHouse ', '/my-booking/confirmed', '1', '2', '1', '2018-10-03 13:02:24', '2018-12-05 06:26:35', NULL),
(4, 1, 52, 'Payment successfully received for the Tushar WareHouse by the Umesh ', '/my-booking/confirmed', '1', '2', '4', '2018-10-03 13:02:31', '2018-10-06 10:06:34', NULL),
(5, 1, 103, 'Payment successfully paid for the Tushar WareHouse ', '/my-booking/confirmed', '1', '2', '1', '2018-10-03 13:03:30', '2018-12-05 06:26:35', NULL),
(6, 1, 52, 'Payment successfully received for the Tushar WareHouse by the Umesh ', '/my-booking/confirmed', '1', '2', '4', '2018-10-03 13:03:36', '2018-10-06 10:06:34', NULL),
(7, 1, 103, 'Payment successfully paid for the Tushar WareHouse ', '/my-booking/confirmed', '1', '2', '1', '2018-10-03 13:14:55', '2018-12-05 06:26:35', NULL),
(8, 1, 52, 'Payment successfully received for the Tushar WareHouse by the Umesh ', '/my-booking/confirmed', '1', '2', '4', '2018-10-03 13:15:04', '2018-10-06 10:06:34', NULL),
(9, 1, 103, 'Payment successfully paid for the Tushar WareHouse ', '/my-booking/confirmed', '1', '2', '1', '2018-10-03 13:16:06', '2018-12-05 06:26:35', NULL),
(10, 1, 52, 'Payment successfully received for the Tushar WareHouse by the Umesh ', '/my-booking/confirmed', '1', '2', '4', '2018-10-03 13:16:14', '2018-10-06 10:06:34', NULL),
(11, 1, 103, 'Payment successfully paid for the Tushar WareHouse ', '/my-booking/confirmed', '1', '2', '1', '2018-10-03 13:20:57', '2018-12-05 06:26:35', NULL),
(12, 1, 52, 'Payment successfully received for the Tushar WareHouse by the Umesh ', '/my-booking/confirmed', '1', '2', '4', '2018-10-03 13:21:09', '2018-10-06 10:06:34', NULL),
(13, 1, 103, 'Payment successfully paid for the Tushar WareHouse ', '/my-booking/confirmed', '1', '2', '1', '2018-10-03 13:21:46', '2018-12-05 06:26:35', NULL),
(14, 1, 52, 'Payment successfully received for the Tushar WareHouse by the Umesh ', '/my-booking/confirmed', '1', '2', '4', '2018-10-03 13:22:58', '2018-10-06 10:06:34', NULL),
(15, 1, 103, 'Payment successfully paid for the Tushar WareHouse ', '/my-booking/confirmed', '1', '2', '1', '2018-10-03 13:24:00', '2018-12-05 06:26:35', NULL),
(16, 1, 52, 'Payment successfully received for the Tushar WareHouse by the Umesh ', '/my-booking/confirmed', '1', '2', '4', '2018-10-03 13:24:06', '2018-10-06 10:06:34', NULL),
(17, 52, 1, 'New property has ##STATUS##.    ', '', '1', '4', '2', '2018-10-04 10:24:58', '2018-11-01 07:14:55', NULL),
(18, 52, 1, 'New property has ##STATUS##.    ', '', '1', '4', '2', '2018-10-04 10:27:02', '2018-11-01 07:14:55', NULL),
(19, 1, 103, 'Payment successfully paid for the Harish banglow ', '/my-booking/confirmed', '1', '2', '1', '2018-10-04 13:31:52', '2018-12-05 06:26:35', NULL),
(20, 1, 52, 'Payment successfully received for the Harish banglow by the Umesh ', '/my-booking/confirmed', '1', '2', '4', '2018-10-04 13:32:00', '2018-10-06 10:06:34', NULL),
(21, 1, 103, 'Payment successfully paid for the Harish banglow ', '/my-booking/confirmed', '1', '2', '1', '2018-10-04 13:42:42', '2018-12-05 06:26:35', NULL),
(22, 1, 52, 'Payment successfully received for the Harish banglow by the Umesh ', '/my-booking/confirmed', '1', '2', '4', '2018-10-04 13:42:49', '2018-10-06 10:06:34', NULL),
(23, 1, 103, 'Payment successfully paid for the Harish banglow ', '/my-booking/confirmed', '1', '2', '1', '2018-10-04 13:48:10', '2018-12-05 06:26:35', NULL),
(24, 1, 52, 'Payment successfully received for the Harish banglow by the Umesh ', '/my-booking/confirmed', '1', '2', '4', '2018-10-04 13:48:17', '2018-10-06 10:06:34', NULL),
(25, 1, 103, 'Payment successfully paid for the Harish banglow ', '/my-booking/confirmed', '1', '2', '1', '2018-10-04 13:48:52', '2018-12-05 06:26:35', NULL),
(26, 1, 52, 'Payment successfully received for the Harish banglow by the Umesh ', '/my-booking/confirmed', '1', '2', '4', '2018-10-04 13:48:59', '2018-10-06 10:06:34', NULL),
(27, 116, 1, 'New Registration has been recieved from Deepak', '', '1', '1', '2', '2018-10-05 05:19:52', '2018-11-01 07:14:55', NULL),
(28, 117, 1, 'New Registration has been recieved from Deepak', '', '1', '1', '2', '2018-10-05 05:25:48', '2018-11-01 07:14:55', NULL),
(29, 118, 1, 'New Registration has been recieved from Deepak', '', '1', '1', '2', '2018-10-05 05:38:13', '2018-11-01 07:14:55', NULL),
(30, 103, 3, 'New ticket has been generated by Umesh with subject Cancel Booking.', '/ticket', '1', '1', '3', '2018-10-05 10:10:19', '2018-12-05 06:26:28', NULL),
(31, 103, 9, 'New ticket has been generated by Umesh with subject Cancel Booking.', '/ticket', '1', '1', '3', '2018-10-05 10:10:19', '2018-12-05 06:26:28', NULL),
(32, 103, 15, 'New ticket has been generated by Umesh with subject Cancel Booking.', '/ticket', '1', '1', '3', '2018-10-05 10:10:19', '2018-12-05 06:26:28', NULL),
(33, 103, 17, 'New ticket has been generated by Umesh with subject Cancel Booking.', '/ticket', '1', '1', '3', '2018-10-05 10:10:19', '2018-12-05 06:26:28', NULL),
(34, 1, 103, 'Your cancellation request is received and will process for the booking of Harish banglow ', '/my-booking/cancelled', '1', '2', '1', '2018-10-05 10:10:19', '2018-12-05 06:26:35', NULL),
(35, 103, 1, 'User cancellation request is send to support for the booking of Harish banglow ', '/my-booking/cancelled', '1', '1', '2', '2018-10-05 10:10:19', '2018-11-01 07:14:55', NULL),
(36, 52, 1, 'New property has ##STATUS##.    ', '', '1', '4', '2', '2018-10-06 09:24:27', '2018-11-01 07:14:55', NULL),
(37, 52, 1, 'New property has ##STATUS##.    ', '', '1', '4', '2', '2018-10-06 09:25:59', '2018-11-01 07:14:55', NULL),
(38, 52, 1, 'New property has ##STATUS##.    ', '', '1', '4', '2', '2018-10-06 09:35:10', '2018-11-01 07:14:55', NULL),
(39, 52, 1, 'New property has ##STATUS##.    ', '', '1', '4', '2', '2018-10-06 09:36:34', '2018-11-01 07:14:55', NULL),
(40, 52, 1, 'New property has ##STATUS##.    ', '', '1', '4', '2', '2018-10-06 09:45:02', '2018-11-01 07:14:55', NULL),
(41, 52, 1, 'New property has ##STATUS##.    ', '', '1', '4', '2', '2018-10-06 09:46:24', '2018-11-01 07:14:55', NULL),
(42, 52, 1, 'New property has ##STATUS##.    ', '', '1', '4', '2', '2018-10-06 09:48:27', '2018-11-01 07:14:55', NULL),
(43, 103, 3, 'New ticket has been generated by Umesh with subject Change/Cancellation of Itinerary.', '/ticket', '1', '1', '3', '2018-10-17 07:04:07', '2018-12-05 06:26:28', NULL),
(44, 103, 9, 'New ticket has been generated by Umesh with subject Change/Cancellation of Itinerary.', '/ticket', '1', '1', '3', '2018-10-17 07:04:07', '2018-12-05 06:26:28', NULL),
(45, 103, 15, 'New ticket has been generated by Umesh with subject Change/Cancellation of Itinerary.', '/ticket', '1', '1', '3', '2018-10-17 07:04:07', '2018-12-05 06:26:28', NULL),
(46, 103, 17, 'New ticket has been generated by Umesh with subject Change/Cancellation of Itinerary.', '/ticket', '1', '1', '3', '2018-10-17 07:04:07', '2018-12-05 06:26:28', NULL),
(47, 1, 103, 'Your cancellation request is received and will process for the booking of Tushar WareHouse ', '/my-booking/cancelled', '1', '2', '1', '2018-10-17 07:04:07', '2018-12-05 06:26:35', NULL),
(48, 103, 1, 'User cancellation request is send to support for the booking of Tushar WareHouse ', '/my-booking/cancelled', '1', '1', '2', '2018-10-17 07:04:07', '2018-11-01 07:14:55', NULL),
(49, 103, 1, 'New ticket has been generated by Umesh with subject Testing support.', '/ticket', '1', '1', '3', '2018-10-18 06:29:46', '2018-12-05 06:26:28', NULL),
(50, 103, 4, 'New ticket has been generated by Umesh with subject Testing support.', '/ticket', '1', '1', '3', '2018-10-18 06:29:46', '2018-12-05 06:26:28', NULL),
(51, 103, 7, 'New ticket has been generated by Umesh with subject Testing support.', '/ticket', '1', '1', '3', '2018-10-18 06:29:46', '2018-12-05 06:26:28', NULL),
(52, 103, 8, 'New ticket has been generated by Umesh with subject Testing support.', '/ticket', '1', '1', '3', '2018-10-18 06:29:46', '2018-12-05 06:26:28', NULL),
(53, 103, 16, 'New ticket has been generated by Umesh with subject Testing support.', '/ticket', '1', '1', '3', '2018-10-18 06:29:46', '2018-12-05 06:26:28', NULL),
(54, 3, 103, 'Hello NA,\nSupport team send you reply for your query having Ticket_id - \"2\" and subject - \"Change/Cancellation of Itinerary\" ', '/ticket-listing', '1', '3', '4', '2018-10-18 11:26:30', '2018-12-05 06:26:35', NULL),
(55, 3, 103, 'Hello NA,\nSupport team send you reply for your query having Ticket_id - \"2\" and subject - \"Change/Cancellation of Itinerary\" ', '/ticket-listing', '1', '3', '4', '2018-10-18 11:38:08', '2018-12-05 06:26:35', NULL),
(56, 1, 103, 'Amount added in wallet successfully', '/wallet?amount_needed=3909.44', '1', '2', '1', '2018-10-20 08:49:32', '2018-12-05 06:26:35', NULL),
(57, 1, 103, 'Amount added in wallet successfully', '/wallet?amount_needed=1.22', '1', '2', '1', '2018-10-20 08:50:25', '2018-12-05 06:26:35', NULL),
(58, 1, 103, 'Amount added in wallet successfully', '/wallet?amount_needed=1', '1', '2', '1', '2018-10-20 08:52:02', '2018-12-05 06:26:35', NULL),
(59, 1, 103, 'Amount added in wallet successfully', '/wallet?amount_needed=1', '1', '2', '1', '2018-10-20 08:54:24', '2018-12-05 06:26:35', NULL),
(60, 3, 103, 'Hello NA,\nSupport team send you reply for your query having Ticket_id - \"2\" and subject - \"Change/Cancellation of Itinerary\" ', '/ticket-listing', '1', '3', '4', '2018-10-20 09:06:50', '2018-12-05 06:26:35', NULL),
(61, 3, 103, 'Hello NA,\nSupport team send you reply for your query having Ticket_id - \"2\" and subject - \"Change/Cancellation of Itinerary\" ', '/ticket-listing', '1', '3', '4', '2018-10-20 09:08:19', '2018-12-05 06:26:35', NULL),
(62, 3, 103, 'Hello umesh,\nSupport team send you reply for your query having Ticket_id - \"2\" and subject - \"Change/Cancellation of Itinerary\" ', '/ticket-listing', '1', '3', '1', '2018-10-20 09:12:51', '2018-12-05 06:26:35', NULL),
(63, 3, 103, 'Hello umesh,\nSupport team send you reply for your query having Ticket_id - \"2\" and subject - \"Change/Cancellation of Itinerary\" ', '/ticket-listing', '1', '3', '1', '2018-10-20 09:13:40', '2018-12-05 06:26:35', NULL),
(64, 3, 103, 'Hello umesh,\nSupport team send you reply for your query having Ticket_id - \"2\" and subject - \"Change/Cancellation of Itinerary\" ', '/ticket-listing', '1', '3', '1', '2018-10-20 09:23:52', '2018-12-05 06:26:35', NULL),
(65, 3, 103, 'Hello umesh,\nSupport team send you reply for your query having Ticket_id - \"2\" and subject - \"Change/Cancellation of Itinerary\" ', '/ticket-listing', '1', '3', '1', '2018-10-20 09:26:23', '2018-12-05 06:26:35', NULL),
(66, 3, 103, 'Hello NA, Your query with Ticket_id - 2 and subject - \"Change/Cancellation of Itinerary\" closed successfully. \nThanks, Team Shareous', '/ticket-listing', '1', '3', '4', '2018-10-20 09:26:28', '2018-12-05 06:26:35', NULL),
(67, 3, 103, 'Hello umesh,\nSupport team send you reply for your query having Ticket_id - \"2\" and subject - \"Change/Cancellation of Itinerary\" ', '/ticket-listing', '1', '3', '1', '2018-10-20 09:35:43', '2018-12-05 06:26:35', NULL),
(68, 3, 103, 'Hello umesh, Your query with Ticket_id - 2 and subject - \"Change/Cancellation of Itinerary\" closed successfully. \nThanks, Team Shareous', '/ticket-listing', '1', '3', '1', '2018-10-20 09:35:50', '2018-12-05 06:26:35', NULL),
(69, 3, 103, 'Hello umesh,\nSupport team send you reply for your query having Ticket_id - \"2\" and subject - \"Change/Cancellation of Itinerary\" ', '/ticket-listing', '1', '3', '1', '2018-10-20 09:40:33', '2018-12-05 06:26:35', NULL),
(70, 3, 103, 'Hello umesh, Your query with Ticket_id - 2 and subject - \"Change/Cancellation of Itinerary\" closed successfully. \nThanks, Team Shareous', '/ticket-listing', '1', '3', '1', '2018-10-20 09:40:39', '2018-12-05 06:26:35', NULL),
(71, 3, 103, 'Hello umesh,\nSupport team send you reply for your query having Ticket_id - \"2\" and subject - \"Change/Cancellation of Itinerary\" ', '/ticket-listing', '1', '3', '1', '2018-10-20 09:41:37', '2018-12-05 06:26:35', NULL),
(72, 3, 103, 'Hello umesh, Your query with Ticket_id - 2 and subject - \"Change/Cancellation of Itinerary\" closed successfully. \nThanks, Team Shareous', '/ticket-listing', '1', '3', '1', '2018-10-20 09:41:43', '2018-12-05 06:26:35', NULL),
(73, 3, 103, 'Hello umesh,\nSupport team send you reply for your query having Ticket_id - \"2\" and subject - \"Change/Cancellation of Itinerary\" ', '/ticket-listing', '1', '3', '1', '2018-10-20 09:44:06', '2018-12-05 06:26:35', NULL),
(74, 3, 103, 'Hello umesh, Your query with Ticket_id - 2 and subject - \"Change/Cancellation of Itinerary\" closed successfully. \nThanks, Team Shareous', '/ticket-listing', '1', '3', '1', '2018-10-20 09:44:14', '2018-12-05 06:26:35', NULL),
(75, 3, 103, 'Hello umesh,\nSupport team send you reply for your query having Ticket_id - \"2\" and subject - \"Change/Cancellation of Itinerary\" ', '/ticket-listing', '1', '3', '1', '2018-10-20 09:45:22', '2018-12-05 06:26:35', NULL),
(76, 3, 103, 'Hello umesh, Your query with Ticket_id - 2 and subject - \"Change/Cancellation of Itinerary\" closed successfully. \nThanks, Team Shareous', '/ticket-listing', '1', '3', '1', '2018-10-20 09:45:27', '2018-12-05 06:26:35', NULL),
(77, 1, 103, 'Payment successfully paid for the Tushar WareHouse ', '/my-booking/confirmed', '1', '2', '1', '2018-10-22 09:48:31', '2018-12-05 06:26:35', NULL),
(78, 1, 52, 'Payment successfully received for the Tushar WareHouse by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-10-22 09:48:38', '2018-10-22 09:48:38', NULL),
(79, 103, 3, 'New ticket has been generated by Umesh with subject Unsatisfactory Reviews.', '/ticket', '1', '1', '3', '2018-10-22 09:49:11', '2018-12-05 06:26:28', NULL),
(80, 103, 9, 'New ticket has been generated by Umesh with subject Unsatisfactory Reviews.', '/ticket', '1', '1', '3', '2018-10-22 09:49:11', '2018-12-05 06:26:28', NULL),
(81, 103, 15, 'New ticket has been generated by Umesh with subject Unsatisfactory Reviews.', '/ticket', '1', '1', '3', '2018-10-22 09:49:11', '2018-12-05 06:26:28', NULL),
(82, 103, 17, 'New ticket has been generated by Umesh with subject Unsatisfactory Reviews.', '/ticket', '1', '1', '3', '2018-10-22 09:49:11', '2018-12-05 06:26:28', NULL),
(83, 1, 103, 'Your cancellation request is received and will process for the booking of Tushar WareHouse ', '/my-booking/cancelled', '1', '2', '1', '2018-10-22 09:49:12', '2018-12-05 06:26:35', NULL),
(84, 103, 1, 'User cancellation request is send to support for the booking of Tushar WareHouse ', '/my-booking/cancelled', '1', '1', '2', '2018-10-22 09:49:12', '2018-11-01 07:14:55', NULL),
(85, 3, 1, '1500 INR amount refund successfully to this booking id - B000015 ', '', '1', '3', '2', '2018-10-22 10:03:11', '2018-11-01 07:14:55', NULL),
(86, 1, 103, 'Payment successfully paid for the Book Store Room ', '/my-booking/confirmed', '1', '2', '1', '2018-10-22 10:09:15', '2018-12-05 06:26:35', NULL),
(87, 1, 52, 'Payment successfully received for the Book Store Room by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-10-22 10:09:21', '2018-10-22 10:09:21', NULL),
(88, 103, 3, 'New ticket has been generated by Umesh with subject Other Reason.', '/ticket', '1', '1', '3', '2018-10-22 10:09:54', '2018-12-05 06:26:28', NULL),
(89, 103, 9, 'New ticket has been generated by Umesh with subject Other Reason.', '/ticket', '1', '1', '3', '2018-10-22 10:09:54', '2018-12-05 06:26:28', NULL),
(90, 103, 15, 'New ticket has been generated by Umesh with subject Other Reason.', '/ticket', '1', '1', '3', '2018-10-22 10:09:54', '2018-12-05 06:26:28', NULL),
(91, 103, 17, 'New ticket has been generated by Umesh with subject Other Reason.', '/ticket', '1', '1', '3', '2018-10-22 10:09:54', '2018-12-05 06:26:28', NULL),
(92, 1, 103, 'Your cancellation request is received and will process for the booking of Book Store Room ', '/my-booking/cancelled', '1', '2', '1', '2018-10-22 10:09:54', '2018-12-05 06:26:35', NULL),
(93, 103, 1, 'User cancellation request is send to support for the booking of Book Store Room ', '/my-booking/cancelled', '1', '1', '2', '2018-10-22 10:09:54', '2018-11-01 07:14:55', NULL),
(94, 3, 1, '50 INR amount refund successfully to this booking id - B000016 ', '', '1', '3', '2', '2018-10-22 10:10:30', '2018-11-01 07:14:55', NULL),
(95, 3, 1, '50 INR amount refund successfully to this booking id - B000016 ', '', '1', '3', '2', '2018-10-22 10:15:48', '2018-11-01 07:14:55', NULL),
(96, 3, 1, '50 INR amount refund successfully to this booking id - B000016 ', '', '1', '3', '2', '2018-10-22 10:17:41', '2018-11-01 07:14:55', NULL),
(97, 3, 1, '50 INR amount refund successfully to this booking id - B000016 ', '', '1', '3', '2', '2018-10-22 10:21:20', '2018-11-01 07:14:55', NULL),
(98, 3, 1, '50 INR amount refund successfully to this booking id - B000016 ', '', '1', '3', '2', '2018-10-22 10:22:12', '2018-11-01 07:14:55', NULL),
(99, 3, 1, '50 INR amount refund successfully to this booking id - B000016 ', '', '1', '3', '2', '2018-10-22 10:22:50', '2018-11-01 07:14:55', NULL),
(100, 3, 1, '50 INR amount refund successfully to this booking id - B000016 ', '', '1', '3', '2', '2018-10-22 10:23:16', '2018-11-01 07:14:55', NULL),
(101, 3, 1, '350 INR amount refund successfully to this booking id - B000016 ', '', '1', '3', '2', '2018-10-22 10:34:34', '2018-11-01 07:14:55', NULL),
(102, 3, 1, '309.22 INR amount refund successfully to this booking id - B000016 ', '', '1', '3', '2', '2018-10-22 10:36:25', '2018-11-01 07:14:55', NULL),
(103, 3, 1, '50 INR amount refund successfully to this booking id - B000016 ', '', '1', '3', '2', '2018-10-22 10:36:44', '2018-11-01 07:14:55', NULL),
(104, 1, 103, 'Amount added in wallet successfully', '/wallet?amount_needed=3905.22', '1', '2', '1', '2018-10-22 11:35:12', '2018-12-05 06:26:35', NULL),
(105, 1, 103, 'Payment successfully paid for the Book Store Room ', '/my-booking/confirmed', '1', '2', '1', '2018-10-22 11:36:04', '2018-12-05 06:26:35', NULL),
(106, 1, 52, 'Payment successfully received for the Book Store Room by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-10-22 11:36:09', '2018-10-22 11:36:09', NULL),
(107, 52, 1, 'New property successfully posted by tarun', '', '1', '1', '2', '2018-10-23 09:03:10', '2018-11-01 07:14:55', NULL),
(108, 52, 1, 'New property successfully posted by tarun', '', '1', '1', '2', '2018-10-23 10:06:40', '2018-11-01 07:14:55', NULL),
(109, 1, 52, 'Your property has been Confirm by admin.    ', '/property/listing', '0', '2', '4', '2018-10-23 11:48:15', '2018-10-23 11:48:15', NULL),
(110, 1, 52, 'Your property has been Confirm by admin.    ', '/property/listing', '0', '2', '4', '2018-10-23 11:48:32', '2018-10-23 11:48:32', NULL),
(111, 1, 103, 'Payment successfully paid for the New office space with multiple selections options ', '/my-booking/confirmed', '1', '2', '1', '2018-10-24 10:05:21', '2018-12-05 06:26:35', NULL),
(112, 1, 52, 'Payment successfully received for the New office space with multiple selections options by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-10-24 10:05:27', '2018-10-24 10:05:27', NULL),
(113, 1, 103, 'Payment successfully paid for the New office space with multiple selections options ', '/my-booking/confirmed', '1', '2', '1', '2018-10-24 10:06:50', '2018-12-05 06:26:35', NULL),
(114, 1, 52, 'Payment successfully received for the New office space with multiple selections options by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-10-24 10:06:56', '2018-10-24 10:06:56', NULL),
(115, 1, 103, 'Payment successfully paid for the New office space with multiple selections options ', '/my-booking/confirmed', '1', '2', '1', '2018-10-24 10:08:37', '2018-12-05 06:26:35', NULL),
(116, 1, 52, 'Payment successfully received for the New office space with multiple selections options by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-10-24 10:08:43', '2018-10-24 10:08:43', NULL),
(117, 1, 103, 'Payment successfully paid for the New office space with multiple selections options ', '/my-booking/confirmed', '1', '2', '1', '2018-10-24 10:09:54', '2018-12-05 06:26:35', NULL),
(118, 1, 52, 'Payment successfully received for the New office space with multiple selections options by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-10-24 10:10:04', '2018-10-24 10:10:04', NULL),
(119, 1, 103, 'Payment successfully paid for the New office space with multiple selections options ', '/my-booking/confirmed', '1', '2', '1', '2018-10-24 10:12:38', '2018-12-05 06:26:35', NULL),
(120, 1, 52, 'Payment successfully received for the New office space with multiple selections options by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-10-24 10:12:44', '2018-10-24 10:12:44', NULL),
(121, 1, 103, 'Payment successfully paid for the New office space with multiple selections options 1 ', '/my-booking/confirmed', '1', '2', '1', '2018-10-24 10:14:28', '2018-12-05 06:26:35', NULL),
(122, 1, 52, 'Payment successfully received for the New office space with multiple selections options 1 by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-10-24 10:14:34', '2018-10-24 10:14:34', NULL),
(123, 1, 103, 'Payment successfully paid for the New office space with multiple selections options 1 ', '/my-booking/confirmed', '1', '2', '1', '2018-10-24 10:26:47', '2018-12-05 06:26:35', NULL),
(124, 1, 52, 'Payment successfully received for the New office space with multiple selections options 1 by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-10-24 10:26:54', '2018-10-24 10:26:54', NULL),
(125, 1, 103, 'Payment successfully paid for the New office space with multiple selections options 1 ', '/my-booking/confirmed', '1', '2', '1', '2018-10-24 11:21:47', '2018-12-05 06:26:35', NULL),
(126, 1, 52, 'Payment successfully received for the New office space with multiple selections options 1 by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-10-24 11:21:53', '2018-10-24 11:21:53', NULL),
(127, 1, 103, 'Payment successfully paid for the New office space with multiple selections options 1 ', '/my-booking/confirmed', '1', '2', '1', '2018-10-24 11:22:44', '2018-12-05 06:26:35', NULL),
(128, 1, 52, 'Payment successfully received for the New office space with multiple selections options 1 by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-10-24 11:22:49', '2018-10-24 11:22:49', NULL),
(129, 1, 103, 'Payment successfully paid for the New office space with multiple selections options 1 ', '/my-booking/confirmed', '1', '2', '1', '2018-10-24 11:24:24', '2018-12-05 06:26:35', NULL),
(130, 1, 52, 'Payment successfully received for the New office space with multiple selections options 1 by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-10-24 11:24:30', '2018-10-24 11:24:30', NULL),
(131, 1, 103, 'Payment successfully paid for the New office space with multiple selections options 1 ', '/my-booking/confirmed', '1', '2', '1', '2018-10-24 11:35:10', '2018-12-05 06:26:35', NULL),
(132, 1, 52, 'Payment successfully received for the New office space with multiple selections options 1 by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-10-24 11:35:16', '2018-10-24 11:35:16', NULL),
(133, 1, 103, 'Payment successfully paid for the New office space with multiple selections options 1 ', '/my-booking/confirmed', '1', '2', '1', '2018-10-24 11:36:39', '2018-12-05 06:26:35', NULL),
(134, 1, 52, 'Payment successfully received for the New office space with multiple selections options 1 by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-10-24 11:36:45', '2018-10-24 11:36:45', NULL),
(135, 103, 3, 'New ticket has been generated by Umesh with subject Unsatisfactory Reviews.', '/ticket', '1', '1', '3', '2018-10-24 12:49:51', '2018-12-05 06:26:28', NULL),
(136, 103, 9, 'New ticket has been generated by Umesh with subject Unsatisfactory Reviews.', '/ticket', '1', '1', '3', '2018-10-24 12:49:51', '2018-12-05 06:26:28', NULL),
(137, 103, 15, 'New ticket has been generated by Umesh with subject Unsatisfactory Reviews.', '/ticket', '1', '1', '3', '2018-10-24 12:49:51', '2018-12-05 06:26:28', NULL),
(138, 103, 17, 'New ticket has been generated by Umesh with subject Unsatisfactory Reviews.', '/ticket', '1', '1', '3', '2018-10-24 12:49:51', '2018-12-05 06:26:28', NULL),
(139, 1, 103, 'Your cancellation request is received and will process for the booking of New office space with multiple selections options ', '/my-booking/cancelled', '1', '2', '1', '2018-10-24 12:49:51', '2018-12-05 06:26:35', NULL),
(140, 103, 1, 'User cancellation request is send to support for the booking of New office space with multiple selections options ', '/my-booking/cancelled', '1', '1', '2', '2018-10-24 12:49:51', '2018-11-01 07:14:55', NULL),
(141, 3, 103, 'Hello umesh, Your query with Ticket_id - 6 and subject - \"Unsatisfactory Reviews\" closed successfully. \nThanks, Team Shareous', '/ticket-listing', '1', '3', '1', '2018-10-24 12:54:47', '2018-12-05 06:26:35', NULL),
(142, 52, 1, 'New property successfully posted by tarun', '', '1', '1', '2', '2018-10-25 04:50:56', '2018-11-01 07:14:55', NULL),
(143, 1, 103, 'Amount added in wallet successfully', '/wallet?amount_needed=350&property_name_slug=bmV3LW9mZmljZS1zcGFjZS13aXRoLW11bHRpcGxlLXNlbGVjdGlvbnMtb3B0aW9ucw==', '1', '2', '1', '2018-10-25 09:13:06', '2018-12-05 06:26:35', NULL),
(144, 1, 103, 'Amount added in wallet successfully', '/wallet?amount_needed=350&property_name_slug=bmV3LW9mZmljZS1zcGFjZS13aXRoLW11bHRpcGxlLXNlbGVjdGlvbnMtb3B0aW9ucw==', '1', '2', '1', '2018-10-25 09:13:57', '2018-12-05 06:26:35', NULL),
(145, 1, 103, 'Payment successfully paid for the New office space with multiple selections options ', '/my-booking/confirmed', '1', '2', '1', '2018-10-25 09:24:57', '2018-12-05 06:26:35', NULL),
(146, 1, 52, 'Payment successfully received for the New office space with multiple selections options by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-10-25 09:25:04', '2018-10-25 09:25:04', NULL),
(147, 1, 103, 'Amount added in wallet successfully', '/wallet?amount_needed=100&property_name_slug=bmV3LW9mZmljZS1zcGFjZS13aXRoLW11bHRpcGxlLXNlbGVjdGlvbnMtb3B0aW9ucw==', '1', '2', '1', '2018-10-25 09:26:15', '2018-12-05 06:26:35', NULL),
(148, 1, 103, 'Amount added in wallet successfully', '/wallet?amount_needed=7568.43&property_name_slug=Ym9vay1zdG9yZS1yb29t', '1', '2', '1', '2018-10-25 09:43:51', '2018-12-05 06:26:35', NULL),
(149, 1, 103, 'Amount added in wallet successfully', '/wallet?amount_needed=334444.07&property_name_slug=d2hpdGUtbGlseQ==', '1', '2', '1', '2018-10-25 10:00:27', '2018-12-05 06:26:35', NULL),
(150, 1, 103, 'Payment successfully paid for the white lily ', '/my-booking/confirmed', '1', '2', '1', '2018-10-25 10:00:45', '2018-12-05 06:26:35', NULL),
(151, 1, 5, 'Payment successfully received for the white lily by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-10-25 10:00:52', '2018-10-25 10:00:52', NULL),
(152, 52, 1, 'New property successfully posted by tarun', '', '1', '1', '2', '2018-10-25 13:35:59', '2018-11-01 07:14:55', NULL),
(153, 52, 1, 'New property successfully posted by tarun', '', '1', '1', '2', '2018-10-30 04:13:24', '2018-11-01 07:14:55', NULL),
(154, 1, 52, 'Your property has been Confirm by admin.    ', '/property/listing', '0', '2', '4', '2018-10-30 05:30:45', '2018-10-30 05:30:45', NULL),
(155, 1, 52, 'Your property has been Confirm by admin.    ', '/property/listing', '0', '2', '4', '2018-10-30 05:30:59', '2018-10-30 05:30:59', NULL),
(156, 1, 103, 'Payment successfully paid for the Deepak Office space with different prices  ', '/my-booking/confirmed', '1', '2', '1', '2018-10-30 07:11:39', '2018-12-05 06:26:35', NULL),
(157, 1, 52, 'Payment successfully received for the Deepak Office space with different prices  by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-10-30 07:11:47', '2018-10-30 07:11:47', NULL),
(158, 1, 103, 'Payment successfully paid for the Deepak Office space with different prices  ', '/my-booking/confirmed', '1', '2', '1', '2018-10-30 08:46:48', '2018-12-05 06:26:35', NULL),
(159, 1, 52, 'Payment successfully received for the Deepak Office space with different prices  by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-10-30 08:46:57', '2018-10-30 08:46:57', NULL),
(160, 103, 3, 'New ticket has been generated by Umesh with subject Unsatisfactory Reviews.', '/ticket', '1', '1', '3', '2018-10-30 09:56:43', '2018-12-05 06:26:28', NULL),
(161, 103, 9, 'New ticket has been generated by Umesh with subject Unsatisfactory Reviews.', '/ticket', '1', '1', '3', '2018-10-30 09:56:43', '2018-12-05 06:26:28', NULL),
(162, 103, 15, 'New ticket has been generated by Umesh with subject Unsatisfactory Reviews.', '/ticket', '1', '1', '3', '2018-10-30 09:56:43', '2018-12-05 06:26:28', NULL),
(163, 103, 17, 'New ticket has been generated by Umesh with subject Unsatisfactory Reviews.', '/ticket', '1', '1', '3', '2018-10-30 09:56:43', '2018-12-05 06:26:28', NULL),
(164, 1, 103, 'Your cancellation request is received and will process for the booking of Deepak Office space with different prices  ', '/my-booking/cancelled', '1', '2', '1', '2018-10-30 09:56:43', '2018-12-05 06:26:35', NULL),
(165, 103, 1, 'User cancellation request is send to support for the booking of Deepak Office space with different prices  ', '/my-booking/cancelled', '1', '1', '2', '2018-10-30 09:56:43', '2018-11-01 07:14:55', NULL),
(166, 3, 103, 'Hello umesh, Your query with Ticket_id - 7 and subject - \"Unsatisfactory Reviews\" closed successfully. \nThanks, Team Shareous', '/ticket-listing', '1', '3', '1', '2018-10-30 10:13:43', '2018-12-05 06:26:35', NULL),
(167, 103, 1, '##MESSAGE## ', 'https://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-11-21 10:14:18', '2018-11-21 10:14:18', NULL),
(168, 103, 1, '##MESSAGE## ', 'https://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-11-21 10:36:55', '2018-11-21 10:36:55', NULL),
(169, 103, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-11-21 10:37:58', '2018-11-21 10:37:58', NULL),
(170, 1, 103, 'Amount added in wallet successfully', '/wallet?amount_needed=4009.22&property_name_slug=Ym9vay1zdG9yZS1yb29t', '1', '2', '1', '2018-11-21 10:38:13', '2018-12-05 06:26:35', NULL),
(171, 103, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-11-21 10:38:36', '2018-11-21 10:38:36', NULL),
(172, 103, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-11-21 10:39:23', '2018-11-21 10:39:23', NULL),
(173, 1, 103, 'Amount added in wallet successfully', '/wallet?amount_needed=4009.22&property_name_slug=Ym9vay1zdG9yZS1yb29t', '1', '2', '1', '2018-11-21 10:39:38', '2018-12-05 06:26:35', NULL),
(174, 103, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-11-21 10:40:50', '2018-11-21 10:40:50', NULL),
(175, 1, 103, 'Amount added in wallet successfully', '/wallet?amount_needed=4009.22&property_name_slug=Ym9vay1zdG9yZS1yb29t', '1', '2', '1', '2018-11-21 10:41:13', '2018-12-05 06:26:35', NULL),
(176, 103, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-11-21 10:52:42', '2018-11-21 10:52:42', NULL),
(177, 1, 103, 'Amount added in wallet successfully', '/wallet?amount_needed=4009.22&property_name_slug=Ym9vay1zdG9yZS1yb29t', '1', '2', '1', '2018-11-21 10:52:57', '2018-12-05 06:26:35', NULL),
(178, 103, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-11-21 11:01:52', '2018-11-21 11:01:52', NULL),
(179, 103, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-11-21 11:01:58', '2018-11-21 11:01:58', NULL),
(180, 103, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-11-21 11:03:01', '2018-11-21 11:03:01', NULL),
(181, 103, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-11-21 11:03:05', '2018-11-21 11:03:05', NULL),
(182, 103, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-11-21 11:04:39', '2018-11-21 11:04:39', NULL),
(183, 103, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-11-21 11:07:04', '2018-11-21 11:07:04', NULL),
(184, 1, 103, 'Amount added in wallet successfully', '/wallet?amount_needed=4009.22&property_name_slug=Ym9vay1zdG9yZS1yb29t', '1', '2', '1', '2018-11-21 11:07:17', '2018-12-05 06:26:35', NULL),
(185, 103, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-11-21 11:07:32', '2018-11-21 11:07:32', NULL),
(186, 1, 103, 'Payment successfully paid for the Book Store Room ', '/my-booking/confirmed', '1', '2', '1', '2018-11-21 11:07:39', '2018-12-05 06:26:35', NULL),
(187, 1, 52, 'Payment successfully received for the Book Store Room by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-11-21 11:07:45', '2018-11-21 11:07:45', NULL),
(188, 103, 3, 'New ticket has been generated by Umesh with subject Change/Cancellation of Itinerary.', '/ticket', '1', '1', '3', '2018-11-22 05:07:02', '2018-12-05 06:26:28', NULL),
(189, 103, 9, 'New ticket has been generated by Umesh with subject Change/Cancellation of Itinerary.', '/ticket', '1', '1', '3', '2018-11-22 05:07:03', '2018-12-05 06:26:28', NULL),
(190, 103, 15, 'New ticket has been generated by Umesh with subject Change/Cancellation of Itinerary.', '/ticket', '1', '1', '3', '2018-11-22 05:07:03', '2018-12-05 06:26:28', NULL),
(191, 103, 17, 'New ticket has been generated by Umesh with subject Change/Cancellation of Itinerary.', '/ticket', '1', '1', '3', '2018-11-22 05:07:03', '2018-12-05 06:26:28', NULL),
(192, 1, 103, 'Your cancellation request is received and will process for the booking of Deepak Office space with different prices  ', '/my-booking/cancelled', '1', '2', '1', '2018-11-22 05:07:03', '2018-12-05 06:26:35', NULL),
(193, 103, 1, 'User cancellation request is send to support for the booking of Deepak Office space with different prices  ', '/my-booking/cancelled', '0', '1', '2', '2018-11-22 05:07:03', '2018-11-22 05:07:03', NULL),
(194, 103, 1, 'New ticket has been generated by Umesh with subject Demo testing.', '/ticket', '1', '1', '3', '2018-11-22 05:44:19', '2018-12-05 06:26:28', NULL),
(195, 103, 4, 'New ticket has been generated by Umesh with subject Demo testing.', '/ticket', '1', '1', '3', '2018-11-22 05:44:19', '2018-12-05 06:26:28', NULL),
(196, 103, 7, 'New ticket has been generated by Umesh with subject Demo testing.', '/ticket', '1', '1', '3', '2018-11-22 05:44:19', '2018-12-05 06:26:28', NULL),
(197, 103, 8, 'New ticket has been generated by Umesh with subject Demo testing.', '/ticket', '1', '1', '3', '2018-11-22 05:44:19', '2018-12-05 06:26:28', NULL),
(198, 103, 16, 'New ticket has been generated by Umesh with subject Demo testing.', '/ticket', '1', '1', '3', '2018-11-22 05:44:19', '2018-12-05 06:26:28', NULL),
(199, 114, 1, 'New Registration has been recieved from Tony', '', '0', '1', '2', '2018-11-26 04:24:25', '2018-11-26 04:24:25', NULL),
(200, 3, 103, 'Support has replied on your ticket. ', '/ticket-listing', '1', '3', '1', '2018-11-26 13:36:09', '2018-12-05 06:26:35', NULL),
(201, 103, 3, 'User has replied on your message. ', '/ticket', '0', '3', '1', '2018-11-26 13:38:56', '2018-11-26 13:38:56', NULL),
(202, 3, 103, 'Support has replied on your ticket. ', '/ticket-listing', '1', '3', '1', '2018-12-03 09:12:01', '2018-12-05 06:26:35', NULL),
(203, 103, 3, 'User has replied on your message. ', '/ticket', '0', '3', '1', '2018-12-03 09:12:49', '2018-12-03 09:12:49', NULL),
(204, 3, 103, 'Support has replied on your ticket. ', '/ticket-listing', '1', '3', '1', '2018-12-03 09:12:57', '2018-12-05 06:26:35', NULL),
(205, 3, 103, 'Support has replied on your ticket. ', '/ticket-listing', '1', '3', '1', '2018-12-03 09:20:22', '2018-12-05 06:26:35', NULL),
(206, 103, 3, 'User has replied on your message. ', '/ticket', '1', '1', '3', '2018-12-03 09:20:31', '2018-12-05 06:26:28', NULL),
(207, 103, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-12-04 10:27:28', '2018-12-04 10:27:28', NULL),
(208, 1, 103, 'Payment successfully paid for the PPPr ', '/my-booking/confirmed', '1', '2', '1', '2018-12-04 10:27:43', '2018-12-05 06:26:35', NULL),
(209, 1, 52, 'Payment successfully received for the PPPr by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-12-04 10:27:51', '2018-12-04 10:27:51', NULL),
(210, 103, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-12-04 10:37:50', '2018-12-04 10:37:50', NULL),
(211, 1, 103, 'Payment successfully paid for the PPPr ', '/my-booking/confirmed', '1', '2', '1', '2018-12-04 10:37:59', '2018-12-05 06:26:35', NULL),
(212, 1, 52, 'Payment successfully received for the PPPr by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-12-04 10:38:07', '2018-12-04 10:38:07', NULL),
(213, 103, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-12-04 10:53:25', '2018-12-04 10:53:25', NULL),
(214, 1, 103, 'Payment successfully paid for the Office space ', '/my-booking/confirmed', '1', '2', '1', '2018-12-04 10:53:43', '2018-12-05 06:26:35', NULL),
(215, 1, 27, 'Payment successfully received for the Office space by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-12-04 10:53:52', '2018-12-04 10:53:52', NULL),
(216, 3, 103, 'Support has replied on your ticket. ', '/ticket-listing', '1', '3', '1', '2018-12-05 06:24:16', '2018-12-05 06:26:35', NULL),
(217, 103, 3, 'User has replied on your message. ', '/ticket', '1', '1', '3', '2018-12-05 06:24:23', '2018-12-05 06:26:28', NULL),
(218, 103, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-12-10 10:19:27', '2018-12-10 10:19:27', NULL),
(219, 1, 103, 'Payment successfully paid for the PPPr ', '/my-booking/confirmed', '0', '2', '1', '2018-12-10 10:19:42', '2018-12-10 10:19:42', NULL),
(220, 103, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-12-10 10:20:32', '2018-12-10 10:20:32', NULL),
(221, 1, 103, 'Payment successfully paid for the PPPr ', '/my-booking/confirmed', '0', '2', '1', '2018-12-10 10:20:40', '2018-12-10 10:20:40', NULL),
(222, 1, 52, 'Payment successfully received for the PPPr by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-12-10 10:20:47', '2018-12-10 10:20:47', NULL),
(223, 103, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-12-10 10:25:45', '2018-12-10 10:25:45', NULL),
(224, 1, 103, 'Payment successfully paid for the Office space ', '/my-booking/confirmed', '0', '2', '1', '2018-12-10 10:25:56', '2018-12-10 10:25:56', NULL),
(225, 1, 27, 'Payment successfully received for the Office space by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-12-10 10:26:04', '2018-12-10 10:26:04', NULL),
(226, 103, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-12-10 10:35:34', '2018-12-10 10:35:34', NULL),
(227, 1, 103, 'Payment successfully paid for the Harish banglow ', '/my-booking/confirmed', '0', '2', '1', '2018-12-10 10:35:42', '2018-12-10 10:35:42', NULL),
(228, 1, 52, 'Payment successfully received for the Harish banglow by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-12-10 10:35:51', '2018-12-10 10:35:51', NULL),
(229, 115, 1, 'New Registration has been recieved from Pooja', '', '0', '1', '2', '2018-12-11 06:48:20', '2018-12-11 06:48:20', NULL),
(230, 115, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-12-11 06:54:19', '2018-12-11 06:54:19', NULL),
(231, 1, 115, 'Amount added in wallet successfully', '/wallet', '1', '2', '1', '2018-12-11 06:56:17', '2018-12-11 07:00:14', NULL),
(232, 2, 115, '  Hello Pooja , Your account verified successfully, You can now post your property to Shareous. Thanks , Team Shareous ', '', '1', '3', '4', '2018-12-11 07:00:10', '2018-12-11 07:00:14', NULL),
(233, 2, 115, '  Hello Pooja , Your account verified successfully, You can now post your property to Shareous. Thanks , Team Shareous ', '', '1', '3', '1', '2018-12-11 07:00:10', '2018-12-11 07:00:14', NULL),
(234, 52, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/property/all', '0', '1', '2', '2018-12-12 12:02:40', '2018-12-12 12:02:40', NULL),
(235, 52, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/property/all', '0', '1', '2', '2018-12-12 12:03:27', '2018-12-12 12:03:27', NULL),
(236, 52, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/property/all', '0', '1', '2', '2018-12-12 12:05:36', '2018-12-12 12:05:36', NULL),
(237, 52, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/property/all', '0', '1', '2', '2018-12-12 12:08:21', '2018-12-12 12:08:21', NULL),
(238, 52, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/property/all', '0', '1', '2', '2018-12-12 12:19:34', '2018-12-12 12:19:34', NULL),
(239, 52, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/property/all', '0', '1', '2', '2018-12-12 13:18:23', '2018-12-12 13:18:23', NULL),
(240, 52, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/property/all', '0', '1', '2', '2018-12-12 13:18:53', '2018-12-12 13:18:53', NULL),
(241, 52, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/property/all', '0', '1', '2', '2018-12-12 13:19:12', '2018-12-12 13:19:12', NULL),
(242, 52, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/property/all', '0', '1', '2', '2018-12-12 13:22:18', '2018-12-12 13:22:18', NULL),
(243, 103, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-12-14 08:46:06', '2018-12-14 08:46:06', NULL),
(244, 1, 103, 'Payment successfully paid for the Harish banglow ', '/my-booking/confirmed', '0', '2', '1', '2018-12-14 08:46:22', '2018-12-14 08:46:22', NULL),
(245, 1, 52, 'Payment successfully received for the Harish banglow by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-12-14 08:46:30', '2018-12-14 08:46:30', NULL),
(246, 103, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-12-14 08:52:19', '2018-12-14 08:52:19', NULL),
(247, 1, 103, 'Payment successfully paid for the uday apartments ', '/my-booking/confirmed', '0', '2', '1', '2018-12-14 08:52:31', '2018-12-14 08:52:31', NULL),
(248, 1, 52, 'Payment successfully received for the uday apartments by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-12-14 08:52:38', '2018-12-14 08:52:38', NULL),
(249, 103, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-12-14 09:45:44', '2018-12-14 09:45:44', NULL),
(250, 103, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-12-14 09:46:55', '2018-12-14 09:46:55', NULL),
(251, 1, 103, 'Payment successfully paid for the this message was delivered  ', '/my-booking/confirmed', '0', '2', '1', '2018-12-14 09:47:09', '2018-12-14 09:47:09', NULL),
(252, 1, 52, 'Payment successfully received for the this message was delivered  by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-12-14 09:47:16', '2018-12-14 09:47:16', NULL),
(253, 103, 1, '##MESSAGE## ', 'http://192.168.1.7/shareous/admin/booking/all', '0', '1', '2', '2018-12-14 09:51:58', '2018-12-14 09:51:58', NULL),
(254, 1, 103, 'Payment successfully paid for the Green Villa Pune ', '/my-booking/confirmed', '0', '2', '1', '2018-12-14 09:52:34', '2018-12-14 09:52:34', NULL),
(255, 1, 52, 'Payment successfully received for the Green Villa Pune by the Umesh ', '/my-booking/confirmed', '0', '2', '4', '2018-12-14 09:52:42', '2018-12-14 09:52:42', NULL),
(256, 1, 103, 'Amount added in wallet successfully', '/wallet', '0', '2', '1', '2018-12-21 12:32:35', '2018-12-21 12:32:35', NULL),
(257, 1, 52, 'Your property has been Confirm by admin.    ', '/property/listing', '0', '2', '4', '2018-12-25 06:34:37', '2018-12-25 06:34:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notification_template`
--

CREATE TABLE `notification_template` (
  `id` int(50) NOT NULL,
  `template_name` varchar(255) NOT NULL,
  `template_subject` varchar(255) NOT NULL,
  `template_variables` varchar(500) NOT NULL,
  `template_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `notification_template`
--

INSERT INTO `notification_template` (`id`, `template_name`, `template_subject`, `template_variables`, `template_text`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'host verification replay', 'Shareous-Admin', '##SITE_URL##~##USER_NAME##~##SUBJECT##', '  Hello ##USER_NAME## , Your account verified successfully, You can now post your property to ##SITE_NAME##. Thanks , Team ##SITE_NAME## ', '2018-02-06 02:13:16', '2018-04-23 00:47:42', NULL),
(2, 'New Registration', 'Shareous-Admin', '##SITE_URL##~##USER_NAME##~##SUBJECT##', 'New Registration has been recieved from ##USER_NAME##', '2018-02-21 22:56:38', '2018-04-23 06:13:21', NULL),
(3, 'host verification rejection replay', 'Shareous-Admin', '##SITE_URL##~##USER_NAME##~##SUBJECT##', 'Hello ##USER_NAME## , Your account verification request is rejected due to improper details. Thanks , Team ##SITE_NAME## ', '2018-02-06 02:13:16', '2018-04-23 05:55:28', NULL),
(4, 'ticket generation', 'Shareous-Support', '##SITE_URL##~##USER_NAME##~##SUBJECT##', 'New ticket has been generated by ##USER_NAME## with subject ##SUBJECT##.', '2018-03-05 18:54:23', '2018-05-07 06:16:00', NULL),
(5, 'ticket cancellation', 'Shareous-Support', '##SITE_URL##~##USER_NAME##~##TICKET_ID##~##QUERY_SUBJECT##', 'Hello ##USER_NAME##, Your query with Ticket_id - ##TICKET_ID## and subject - \"##QUERY_SUBJECT##\" closed successfully. \nThanks, Team ##SITE_NAME##', '2018-03-07 22:46:28', '2018-05-07 06:16:24', NULL),
(6, 'property status', 'Shareous-Admin', '##USER_NAME~##STATUS##', 'Your property has been ##STATUS## by admin.    ', '2018-03-08 03:14:28', '2018-03-08 08:56:13', NULL),
(7, 'ticket reply', 'Shareous-Support', '##SITE_URL##~##USER_NAME##~##TICKET_ID##~##QUERY_SUBJECT##', 'Hello ##USER_NAME##,\nSupport team send you reply for your query having Ticket_id - \"##TICKET_ID##\" and subject - \"##QUERY_SUBJECT##\" ', '2018-03-12 20:29:20', '2018-04-23 01:17:55', NULL),
(8, 'wallet amount', 'Shareous-Admin', '##SITE_URL##~##USER_NAME##~##SUBJECT##', 'Amount added in wallet successfully', '2018-03-12 20:29:20', '2018-04-24 07:04:00', NULL),
(9, 'Notification', 'Shereous - Admin', '##SITE_URL##~##USER_NAME##~##NOTIFICATION_SUBJECT##~##MESSAGE##', '##MESSAGE## ', '2018-03-04 18:51:39', '2018-04-27 00:47:48', NULL),
(10, 'booking status', 'Shareous-Admin', '##USER_NAME~##STATUS##', 'New booking request for your property.', '2018-03-08 03:14:28', '2018-03-08 08:56:13', NULL),
(11, 'booking status', 'Shareous-Admin', '##USER_NAME~##STATUS##', 'Your booking has been ##STATUS## by host.    ', '2018-03-08 03:14:28', '2018-03-08 08:56:13', NULL),
(12, 'Property Post', 'Shareous-Admin', '##USER_NAME~##STATUS##', 'New property has ##STATUS##.    ', '2018-03-08 03:14:28', '2018-03-08 08:56:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `property`
--

CREATE TABLE `property` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `property_type_id` int(11) NOT NULL,
  `property_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `address` varchar(300) NOT NULL,
  `city` varchar(200) NOT NULL,
  `state` varchar(250) NOT NULL,
  `country` varchar(200) NOT NULL,
  `postal_code` int(10) NOT NULL,
  `property_latitude` varchar(255) NOT NULL,
  `property_longitude` varchar(255) NOT NULL,
  `number_of_guest` int(11) NOT NULL,
  `number_of_bedrooms` int(11) NOT NULL,
  `number_of_bathrooms` int(11) NOT NULL,
  `number_of_beds` int(11) NOT NULL,
  `price_per_night` int(11) NOT NULL,
  `service_charge` int(11) NOT NULL,
  `admin_status` enum('1','2','3','4') NOT NULL COMMENT '1-Pending,2-Confirmed,3-Rejected,4-permanant_rejected',
  `admin_comment` varchar(255) NOT NULL,
  `currency` varchar(20) NOT NULL,
  `currency_code` varchar(20) NOT NULL,
  `property_status` enum('1','2') NOT NULL COMMENT '1-completed,2-not completed',
  `property_working_status` enum('open','closed') NOT NULL,
  `property_area` double NOT NULL,
  `total_plot_area` double NOT NULL,
  `total_build_area` double NOT NULL,
  `custom_type` enum('bonded','non-bonded') NOT NULL,
  `management` enum('yes','no') NOT NULL,
  `good_storage` varchar(250) NOT NULL,
  `admin_area` double NOT NULL,
  `no_of_slots` float NOT NULL,
  `available_no_of_slots` float DEFAULT NULL,
  `build_type` varchar(200) NOT NULL,
  `property_remark` text NOT NULL,
  `price_per_sqft` int(11) NOT NULL,
  `price_per` varchar(200) NOT NULL,
  `employee` enum('on','off') NOT NULL DEFAULT 'off',
  `no_of_employee` int(11) NOT NULL,
  `room` enum('on','off') NOT NULL DEFAULT 'off',
  `no_of_room` int(11) DEFAULT NULL,
  `room_price` int(11) DEFAULT NULL,
  `desk` enum('on','off') NOT NULL DEFAULT 'off',
  `no_of_desk` int(11) DEFAULT NULL,
  `desk_price` int(11) DEFAULT NULL,
  `cubicles` enum('on','off') NOT NULL DEFAULT 'off',
  `no_of_cubicles` int(11) DEFAULT NULL,
  `cubicles_price` int(11) DEFAULT NULL,
  `available_no_of_employee` int(11) DEFAULT NULL,
  `price_per_office` int(11) NOT NULL,
  `nearest_railway_station` varchar(250) DEFAULT NULL,
  `nearest_national_highway` varchar(200) CHARACTER SET utf8 COLLATE utf8_estonian_ci DEFAULT NULL,
  `nearest_bus_stop` varchar(200) DEFAULT NULL,
  `working_hours` varchar(200) DEFAULT NULL,
  `working_days` varchar(200) DEFAULT NULL,
  `is_featured` enum('no','yes') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `meta_keyword` varchar(255) NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `property_name_slug` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `property`
--

INSERT INTO `property` (`id`, `user_id`, `category_id`, `property_type_id`, `property_name`, `description`, `address`, `city`, `state`, `country`, `postal_code`, `property_latitude`, `property_longitude`, `number_of_guest`, `number_of_bedrooms`, `number_of_bathrooms`, `number_of_beds`, `price_per_night`, `service_charge`, `admin_status`, `admin_comment`, `currency`, `currency_code`, `property_status`, `property_working_status`, `property_area`, `total_plot_area`, `total_build_area`, `custom_type`, `management`, `good_storage`, `admin_area`, `no_of_slots`, `available_no_of_slots`, `build_type`, `property_remark`, `price_per_sqft`, `price_per`, `employee`, `no_of_employee`, `room`, `no_of_room`, `room_price`, `desk`, `no_of_desk`, `desk_price`, `cubicles`, `no_of_cubicles`, `cubicles_price`, `available_no_of_employee`, `price_per_office`, `nearest_railway_station`, `nearest_national_highway`, `nearest_bus_stop`, `working_hours`, `working_days`, `is_featured`, `created_at`, `updated_at`, `meta_keyword`, `meta_title`, `meta_description`, `property_name_slug`) VALUES
(1, 5, 0, 1, 'Villa in canada', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more ', 'Canada Way, Burnaby, BC, Canada', 'Burnaby', 'British Columbia', 'Canada', 0, '49.2421346', '-122.9692543', 5, 5, 5, 5, 1000, 0, '2', '', '$', 'USD', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'yes', '2018-05-29 11:28:44', '2018-05-30 05:43:50', '', '', '', 'villa-in-canada'),
(2, 5, 0, 1, 'Sai Villa', 'Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa Sai Villa ', 'Canada Way, Burnaby, BC, Canada', 'Burnaby', 'British Columbia', 'Canada', 0, '49.2421346', '-122.9692543', 2, 1, 1, 1, 200, 0, '2', '', '$', 'USD', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-05-29 11:53:48', '2018-07-12 00:09:33', '', '', '', 'sai-villa'),
(4, 5, 0, 3, 'Shanti Home', 'Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home Shanti Home ', 'Kalika Mandir Bus Stop, Old Agra Road, Renuka Nagar, Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422011, '19.9904095', '73.78980049999996', 1, 1, 1, 1, 1000, 0, '2', '', '€', 'EUR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'yes', '2018-05-29 13:04:50', '2018-05-31 22:37:39', '', '', '', 'shanti-home'),
(5, 5, 0, 3, 'Mannat house', 'Mannat house Mannat house Mannat house Mannat house Mannat house Mannat house Mannat house Mannat house Mannat house Mannat house Mannat house Mannat house Mannat house Mannat house Mannat house Mannat house Mannat house Mannat house Mannat house Mannat house Mannat house ', 'Pune, Maharashtra, India', 'Pune', 'Maharashtra', 'India', 0, '18.5204303', '73.85674369999992', 1, 1, 1, 1, 200, 0, '2', '', '$', 'USD', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'yes', '2018-05-29 13:12:15', '2018-05-30 05:43:40', '', '', '', 'mannat-house'),
(6, 4, 0, 2, 'Aashiyaanaa Villa GRAND', 'Aashiyanaa Villa \'GRAND\' is a luxurious 4bhk Bungalow with a huge private garden of 10000sqft, private swimming pool, surrounded by lush greenery, scenic mountain view, enchanting waterfalls, chirping birds, scenting flowers, mountain trekking, altogether a perfect holiday in laps of mother nature making it one of the best locations in lonavala along with the best hospitality that makes your trip memorable. ', 'Lonavala, Pune, Maharashtra, India', 'Lonavala', 'Maharashtra', 'India', 123432, '18.7546171', '73.40623419999997', 3, 3, 4, 2, 18219, 0, '2', '', '$', 'USD', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-06-01 02:40:29', '2018-06-01 02:47:23', '', '', '', 'aashiyaanaa-villa-grand'),
(7, 4, 0, 6, 'The Madpackers Hostel', 'The Madpackers Hostel of New Delhi was recently voted the best hostel in India by the review website Hostelworld. Its USPs include a library and a rooftop terrace with real grass. It offers a cosy stay to all its occupants, complete with a complimentary breakfast. The prices range from around INR 600 and over for dorm rooms, and INR 2000 for a private room.', 'Chennai - Theni Highway, Bathra Kaliyamman, Konduraja Line, Theni, Tamil Nadu, India', 'Theni', 'Tamil Nadu', 'India', 625531, '10.0151409', '77.47892189999993', 4, 4, 4, 4, 12000, 0, '2', '', '€', 'EUR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-06-01 02:52:35', '2018-06-01 02:57:36', '', '', '', 'the-madpackers-hostel'),
(8, 4, 0, 4, 'Gopis Farm Hassan', 'HolidayIQ Traveller Raveesh Mugali from Bangalore shares, “Its a very nice resort in the middle of farm,if you go after reading the\" Simplify \"book one can connect well each and everything you see there.It will be inspiring experience,Food is good,Campfire place is awesome,living rooms are excellent and Banana trees inside bathrooms makes excellent experience of bathing.ambience is good,Raju\'s hospitaliy and happy smile makes you comfortable.”', 'Indiranagar, Bengaluru, Karnataka, India', 'Bengaluru', 'Karnataka', 'India', 231212, '12.9718915', '77.64115449999997', 5, 3, 3, 4, 1200, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-06-01 03:01:41', '2018-06-01 03:03:56', '', '', '', 'gopis-farm-hassan'),
(9, 4, 0, 4, ' Rudra Farms', 'HolidayIQ Traveller Vishal Patil from Nasik shares, “If you wish to be relaxed then this is the best place where you get relaxation. Rudra Farms is not far from Nasik. Lots of greenery around the hotel is there looks very pleasant to your eyes. I enjoyed the stay here and you have to pay small amount for it. Food I ate here is very delicious and hygienic.”', 'Nasik Airport Building, Viman Nagar, Ojhar, Maharashtra, India', 'Ojhar', 'Maharashtra', 'India', 422207, '20.1028892', '73.92597030000002', 1, 1, 1, 1, 1500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-06-01 03:07:02', '2018-06-01 03:09:09', '', '', '', 'rudra-farms'),
(10, 4, 0, 2, 'Rosenheime ', 'Rosenheime is a self-catering property situated in Ooty near The Lawrence School Lovedale​. The property features mountain views and is 2.7 km from Ooty Lake and 1.9 km from Ooty Rose Garden.\r\nThe villa features 5 bedrooms, a living room, a dining area and a well-appointed kitchen.\r\n\r\nOoty Bus Station is 2 km from the villa, while Hebron School Ooty is 3.3 km away. The nearest airport is Coimbatore International Airport, 54 km from the property. ', 'Tamil Nadu Agricultural University, PN Pudur, Coimbatore, Tamil Nadu, India', 'Coimbatore', 'Tamil Nadu', 'India', 345234, '11.0151861', '76.93261669999993', 2, 2, 2, 2, 2000, 0, '2', '', '$', 'USD', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-06-01 03:13:13', '2018-06-01 03:15:14', '', '', '', 'rosenheime'),
(11, 5, 0, 1, 'Banglow for vacations', 'Banglow for vacationsBanglow for vacationsBanglow for vacationsBanglow for vacationsBanglow for vacationsBanglow for vacations', 'Nashik Road, Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 0, '19.9702929', '73.8301437', 100, 2, 2, 2, 100, 0, '2', '', '€', 'EUR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-06-02 04:37:34', '2018-06-02 04:46:56', '', '', '', 'banglow-for-vacations'),
(12, 14, 0, 1, 'Vosiv BR Studio Suites By Magnus', 'Either it be your Business Travel or you coming for Leisure.Experience the Lavishly Home like Studio Premium Suites.Its situated in heart of Kalyani nagar.You will get Malls, Restaurants & Lounge Bars on walking Distance.', 'Pune International Airport Area, Lohgaon, Pune, Maharashtra, India', 'Pune', 'Maharashtra', 'India', 0, '18.5775893', '73.9418603', 2, 2, 2, 2, 1500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-06-04 03:57:46', '2018-06-04 07:19:41', '', '', '', 'vosiv-br-studio-suites-by-magnus'),
(14, 14, 0, 1, 'Deluxe  bedrooms in Koregaon Parks', 'One of the most of prime area of the city, moreover considered as heart of the city\r\n\r\nApartments located in the Lane D, Lane 5, Lane 6 of Koregaon park\r\n\r\nVery close from OSHO communication, Hospitals, major restaurant, shopping malls etc\r\n\r\nVariety of apartments like standard, deluxe, royal, 1/2/3/4 BHK Apartment are available', 'Pune Station, HH Prince Aga Khan Road, Agarkar Nagar, Pune, Maharashtra, India', 'Pune', 'Maharashtra', 'India', 411001, '18.5285554', '73.8746067', 1, 1, 1, 1, 40, 0, '2', 'Lorem Ipsum is simply dummy text of the printing and', '€', 'EUR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-06-04 05:18:35', '2018-08-02 12:30:14', '', '', '', 'deluxe-bedrooms-in-koregaon-parks'),
(16, 14, 0, 4, 'Satras Eastern Heights', 'When Enter the long value then system should not display the Internal server error When Enter the long value then system should not display the Internal server error', 'Nashik Road, Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422008, '19.9702929', '73.83014370000001', 5, 5, 5, 5, 50000, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-06-04 08:31:15', '2018-06-07 09:35:17', '', '', '', 'satras-eastern-heights'),
(17, 14, 0, 1, 'Ecstatic stay with us at Ecstasy', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a ', 'Nashik - Pune Road, Bodhale Nagar, RTO Colony, Ganesh Baba Nagar, Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422214, '19.9814485', '73.8057434', 1, 9, 5, 5, 1500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'yes', '2018-06-07 09:10:27', '2018-06-07 23:54:49', '', '', '', 'ecstatic-stay-with-us-at-ecstasy'),
(18, 14, 0, 2, 'Deluxe  bedrooms in Koregaon Parkk', 'galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with ', 'Nashik - Pune Road, Bodhale Nagar, RTO Colony, Ganesh Baba Nagar, Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422214, '19.9814485', '73.8057434', 1, 3, 3, 1, 3000, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'yes', '2018-06-07 09:42:40', '2018-06-07 23:54:45', '', '', '', 'deluxe-bedrooms-in-koregaon-parkk'),
(19, 5, 0, 1, 'Banglow test', 'Banglow testBanglow testBanglow testBanglow testBanglow testBanglow test', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422008, '19.9974533', '73.7898023', 1, 1, 1, 1, 1000, 0, '2', '', '€', 'EUR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-06-08 00:37:19', '2018-08-02 12:30:14', '', '', '', 'banglow-test'),
(20, 22, 0, 1, 'Deluxe  bedrooms in Koregaon Park', 'galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap ', 'Nashik Railway Track Road, Nawle Colony, Government Colony, Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422214, '19.9533453', '73.8425063', 1, 3, 1, 1, 1500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-06-11 00:13:07', '2018-07-11 23:57:11', '', '', '', 'deluxe-bedrooms-in-koregaon-park1'),
(21, 22, 0, 1, ' BHK Two Private AC Rooms in an Apartment in Central Pune', 'When system should not generate the more than 10 bedrooms', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422001, '19.9974533', '73.7898023', 1, 4, 1, 1, 1500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-06-11 00:34:16', '2018-08-02 12:30:08', '', '', '', 'bhk-two-private-ac-rooms-in-an-apartment-in-central-pune2'),
(22, 22, 0, 1, ' BHK Two Private AC Rooms in an Apartment in Central Pune', 'When system should not generate the more than 10 bedrooms', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422008, '19.9974533', '73.78980230000002', 1, 2, 1, 1, 1500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'yes', '2018-06-11 00:41:33', '2018-06-11 00:46:11', '', '', '', 'bhk-two-private-ac-rooms-in-an-apartment-in-central-pune2'),
(23, 22, 0, 1, 'Deluxe  bedrooms in Koregaon Parkk', 'Remove the pdf,Doc types because the this images display the silders then it sholud not neccessary', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 40028, '19.9974533', '73.78980230000002', 1, 1, 1, 1, 1500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-06-11 01:27:31', '2018-08-02 12:30:03', '', '', '', 'deluxe-bedrooms-in-koregaon-parkk1'),
(24, 22, 0, 5, ' Adam C Powell Blvd', 'Hooby club Android Apps and Ios Apps', 'Nashik Railway Track Road, Nawle Colony, Government Colony, Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422214, '19.953349', '73.84250629999997', 1, 1, 1, 1, 1500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-06-11 02:56:40', '2018-08-02 12:29:57', '', '', '', 'adam-c-powell-blvd'),
(25, 22, 0, 4, ' Adam C Powell Blvd', 'Hooby club Android Apps and Ios Apps', 'Nashik Road, Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422009, '19.9702929', '73.83014370000001', 1, 1, 1, 1, 1500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-06-11 02:58:06', '2018-08-02 12:29:52', '', '', '', 'adam-c-powell-blvd1'),
(26, 22, 0, 6, ' BHK Two Private AC Rooms in an Apartment in Central Punee', 'Hooby club Android Apps and Ios Apps', 'Nashik Road, Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422008, '19.9702929', '73.8301437', 1, 1, 1, 1, 2500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-06-11 03:02:51', '2018-08-02 12:29:47', '', '', '', 'bhk-two-private-ac-rooms-in-an-apartment-in-central-punee'),
(27, 5, 0, 2, 'white lily', 'white lily white lily white lily white lily white lily white lily white lily white lily white lily white lily white lily white lily white lily white lily white lily white lily white lily ', 'Nasik Road, P&T Colony, Rajwada Nagar, Deolali Gaon, Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422214, '19.9476732', '73.84127690000003', 100, 1, 1, 1, 1000, 0, '2', '', '$', 'USD', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-06-11 03:11:35', '2018-08-02 12:29:47', '', '', '', 'white-lily'),
(28, 5, 0, 1, 'test', 'tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  tyest  ', 'H.G. 22, Muktanand Nagar Road, Near Jain Temple, Krishna Nagar Society - 2, Choksi Wadi, Narotam Nagar, Surat, Gujarat, India', 'Surat', 'Gujarat', 'India', 395009, '21.196765', '72.80245100000002', 1, 1, 1, 1, 200, 0, '1', '', '€', 'EUR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-06-11 04:06:02', '2018-07-13 04:36:12', '', '', '', 'test'),
(29, 14, 0, 1, 'When we enter the count then it should display the sleeping arrangement responsive views', 'When we enter the count then it should display the sleeping arrangement responsive views\r\n', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422008, '0.0', '0.0', 1, 4, 1, 1, 1500, 0, '1', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-06-11 23:35:45', '2018-07-16 11:23:52', '', '', '', 'when-we-enter-the-count-then-it-should-display-the-sleeping-arrangement-responsive-views'),
(30, 14, 0, 1, 'Ecstatic stay with us at Ecstasy', 'User scroll very time to add unavailiabiltiy time ', 'Nashik Road, Sahdev Nagar, Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422008, '20.0163943', '73.7668213', 1, 5, 2, 2, 1500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-06-12 01:01:14', '2018-08-02 12:29:41', '', '', '', 'ecstatic-stay-with-us-at-ecstasy1'),
(33, 14, 0, 1, ' BHK Two Private AC Rooms in an Apartment in Central Punee', 'Back button should display right side', 'Nashik Road, Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422008, '19.9702929', '73.8301437', 1, 3, 1, 1, 10, 0, '2', '', '$', 'USD', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-06-12 07:48:49', '2018-08-02 12:29:36', '', '', '', 'bhk-two-private-ac-rooms-in-an-apartment-in-central-punee2'),
(35, 14, 0, 1, ' BHK Two Private AC Rooms in an Apartment in Central Puneee', ' BHK Two Private AC Rooms in an Apartment in Central Puneee', 'Nashik Madhyawarti Bus Sthanak, CBS Road, Police Staff Colony, Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422002, '20.001165', '73.782228', 3, 4, 1, 1, 15, 0, '2', '', '€', 'EUR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-06-12 08:15:56', '2018-08-02 12:29:31', '', '', '', 'bhk-two-private-ac-rooms-in-an-apartment-in-central-puneee'),
(36, 14, 0, 1, 'Hooby club Android Apps and Ios Apps', 'Hooby club Android Apps and Ios Apps\r\n', 'Nashik Gymkhana, Shivaji Road, Shalimar, Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422001, '20.0016754', '73.7851084', 1, 1, 1, 1, 50, 0, '1', '', '€', 'EUR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-06-12 08:53:23', '2018-07-17 05:13:48', '', '', '', 'hooby-club-android-apps-and-ios-apps'),
(41, 27, 0, 1, 'asd', 'asd', 'Asda Patchway Supercentre, Lysander Road, Patchway, Bristol, UK', 'Patchway', 'England', 'United Kingdom', 400086, '51.5305396', '-2.5968486', 10, 1, 1, 1, 1002, 0, '1', '', '$', 'USD', '1', '', 0, 0, 0, '', '', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-06-16 03:32:18', '2018-08-27 23:17:12', '', '', '', 'asd'),
(45, 27, 0, 5, 'Test', 'Test', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 400086, '19.9974533', '73.7898023', 3, 3, 3, 3, 345, 0, '1', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-06 22:57:05', '2018-07-07 07:32:04', '', '', '', 'test1'),
(47, 27, 0, 5, 'Sha Test Property', 'Good Design and facilities', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 0, '19.9974533', '73.7898023', 2, 2, 2, 2, 2000, 0, '1', '', '$', 'USD', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-06 23:35:27', '2018-07-08 22:41:53', '', '', '', 'sha-test-property'),
(48, 27, 0, 5, 'Sha Test Property here', 'Good Design and facilities', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 0, '19.9974533', '73.7898023', 2, 2, 2, 2, 2000, 0, '1', '', '$', 'USD', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-07 06:13:51', '2018-07-08 22:42:12', '', '', '', 'sha-test-property-here'),
(50, 27, 0, 5, 'Sha Test', 'Good Design and facilities', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 456747, '19.9974533', '73.7898023', 2, 2, 2, 2, 2000, 0, '1', '', '$', 'USD', '1', '', 0, 0, 0, '', '', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-07 07:03:06', '2018-08-28 23:44:02', '', '', '', 'sha-test'),
(55, 35, 0, 1, 'Sha pro', 'shahaahahahhshsjs', 'Pratap Nagar, Jalgaon, Maharashtra 425001, India', 'Jalgaon', 'Maharashtra', 'India', 400000, '21.0116488', '75.5582376', 2, 2, 2, 2, 200, 0, '1', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-09 00:13:12', '2018-07-10 06:32:26', '', '', '', 'sha-pro'),
(59, 35, 0, 2, 'Sha restro', 'ndesvs skdjd smjdd mdkddb dkdid dmdjdd  dkddbd mddb dmdbdd mdnd ff mcn', 'Kasarwadi, Pimpri-Chinchwad, Maharashtra 411026, India', 'Pimpri-Chinchwad', 'Maharashtra', 'India', 424242, '18.6060873', '73.8227917', 110, 2, 5, 5, 500, 0, '1', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-09 05:26:32', '2018-07-10 06:46:34', '', '', '', 'sha-restro'),
(61, 35, 0, 4, 'test m\'a demandé', 'sdf. GB. nb tout le temps de la société qui est en train d\'organiser des visites des sites utiles', 'C-71-C 24th Commercial St, Phase 2 Commercial Area Defence Housing Authority, Karachi, Karachi City, Sindh 75500, Pakistan', 'Karachi', 'Maharashtra', 'India', 422021, '24.8316245', '67.0715484', 5, 3, 3, 3, 3, 0, '2', '', '$', 'USD', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-11 00:31:54', '2018-08-02 13:16:52', '', '', '', 'test-ma-demande'),
(64, 45, 0, 1, 'the way the best time toh of my favorite things that need them and they said that he would like this email and delete your message was automatically generated automatically generated automatically generated automatically generated automatically generated ', 'the way the best time toh of my favorite things that need them and they said that he would like this email and delete your message was automatically generated automatically generated automatically generated automatically generated automatically generated automatically generated email address from you soon and delete this message was delivered by email address and contact with you and your thoughts about this email at this moment but if we do the work for a long shot in your company that would help and guidance counselor this the way the best time toh of my favorite things that need them and they said that he would like this email and delete your message was automatically generated automatically generated automatically generated automatically generated automatically generated automatically generated email address from you soon and delete this message was delivered by email address and contact with you and your thoughts about this email at this moment but if we do the work for a long shot in your company that would help and guidance counselor this the way the best time toh of my favorite things that need them and they said that he would like this email and delete your message was automatically generated automatically generated automatically generated automatically generated automatically generated automatically generated email address from you soon and delete this message was delivered by email address and contact with you and your thoughts about this email at this moment but if we do the work for a long shot in your company that would help and guidance counselor this ', 'Beside Kalika Mandir, Mumbai Naka Highway Bus Stand, Mumbai Naka, Matoshree Nagar, Nashik, Maharashtra 422001, India', 'Nashik', 'Maharashtra', 'India', 422008, '19.991083', '73.782829', 1, 2, 2, 2, 75000, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'yes', '2018-07-12 08:33:41', '2018-07-13 05:56:14', '', '', '', 'the-way-the-best-time-toh-of-my-favorite-things-that-need-them-and-they-said-that-he-would-like-this-email-and-delete-your-message-was-automatically-generated-automatically-generated-automatically-generated-automatically-generated-automatically-generated'),
(65, 45, 0, 1, 'Ecstatic stay with us at Ecstasy', 'When upload document should display the propersWhen upload document should display the propersWhen upload document should display the propersWhen upload document should display the propersWhen upload document should display the propersWhen upload document should display the propersWhen upload document should display the propersWhen upload ', 'Nashik Road, Sahdev Nagar, Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422008, '20.0163943', '73.76682129999995', 1, 2, 1, 4, 9500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'yes', '2018-07-12 08:34:47', '2018-07-13 05:56:09', '', '', '', 'ecstatic-stay-with-us-at-ecstasy2'),
(66, 45, 0, 4, 'kale farm house', 'this is is very nice weekend but not as bad news about your experience', 'Nashik Railway Track Rd, Nawle Colony, Government Colony, Nashik, Maharashtra 422214, India', 'Nashik', 'Maharashtra', 'India', 422008, '19.9533365', '73.8425063', 2, 3, 1, 1, 5000, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'yes', '2018-07-13 04:40:00', '2018-08-01 00:53:36', '', '', '', 'kale-farm-house'),
(68, 45, 0, 2, 'goa house', 'house house', 'Pune, Maharashtra, India', 'Pune', 'Maharashtra', 'India', 411011, '18.5204303', '73.8567437', 2, 2, 2, 1, 1200, 0, '2', '', '€', 'EUR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'yes', '2018-07-13 04:54:37', '2018-07-13 05:55:54', '', '', '', 'goa-house'),
(77, 45, 0, 1, 'this is very nice', 'this is verynice and itsvery good ', 'Nashik Railway Track Rd, Nawle Colony, Government Colony, Nashik, Maharashtra 422214, India', 'Nashik', 'Maharashtra', 'India', 422214, '19.9533741', '73.8425063', 1, 1, 1, 1, 2500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'yes', '2018-07-13 08:53:42', '2018-08-01 00:53:43', '', '', '', 'this-is-very-nice'),
(78, 15, 0, 3, 'Mannat House', 'Mannat House Mannat House Mannat House Mannat House Mannat House Mannat House Mannat House Mannat House Mannat House Mannat House Mannat House Mannat House Mannat House Mannat House Mannat House Mannat House ', 'Gujranwala Town, Delhi, India', 'Delhi', 'Delhi', 'India', 123456, '28.7005219', '77.1889476', 3, 3, 3, 3, 2000, 0, '2', '', '$', 'USD', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-13 11:34:15', '2018-07-13 11:37:19', '', '', '', 'mannat-house1'),
(79, 45, 0, 1, 'think that would work well', 'their website that the intended solely those', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422008, '19.9974533', '73.78980229999999', 1, 2, 2, 2, 2500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'yes', '2018-07-16 07:15:01', '2018-07-16 07:19:26', '', '', '', 'think-that-would-work-well'),
(80, 14, 0, 1, 'salsad', 'thus the other day', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422008, '19.9974533', '73.7898023', 1, 2, 5, 5, 2500, 0, '1', 'this is invalid property', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-16 11:29:35', '2018-07-17 08:56:34', '', '', '', 'salsad'),
(81, 52, 0, 1, 'this message was automatically', 'think that would make', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422008, '19.9974533', '73.7898023', 4, 2, 2, 2, 3500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-18 12:57:13', '2018-07-18 16:15:00', '', '', '', 'this-message-was-automatically'),
(82, 52, 0, 1, 'you should receive this', 'their website with your message', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422008, '0', '0', 5, 3, 2, 3, 4500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-18 13:30:54', '2018-07-18 16:15:01', '', '', '', 'you-should-receive-this'),
(83, 52, 0, 1, 'this is very nice and cool', 'this is very  so good', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422001, '19.9974533', '73.7898023', 2, 2, 2, 2, 2700, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-18 13:46:43', '2018-07-18 16:15:01', '', '', '', 'this-is-very-nice-and-cool'),
(85, 52, 0, 1, 'this is very nice and very powerfull', 'this is very nice', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422001, '19.9974533', '73.7898023', 1, 1, 1, 1, 3500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-19 09:09:41', '2018-08-02 12:27:08', '', '', '', 'this-is-very-nice-and-very-powerfull'),
(87, 52, 0, 1, 'this is very nice property', 'this is very nice property', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422001, '19.9974533', '73.7898023', 1, 1, 1, 1, 3500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-19 12:30:33', '2018-08-02 12:27:03', '', '', '', 'this-is-very-nice-property'),
(88, 52, 0, 1, 'yoooo', 'yooo', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422001, '19.9974533', '73.7898023', 1, 1, 1, 1, 4500, 0, '1', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-19 15:02:56', '2018-07-19 15:05:59', '', '', '', 'yoooo'),
(90, 52, 0, 1, 'this good opportunity and', 'the Facebook platform and the the', 'Nashik, Maharashtra, India', 'Nashik', 'Uttar Pradesh', 'India', 422008, '0.0', '0.0', 1, 1, 1, 1, 3500, 0, '1', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-19 15:17:34', '2018-07-20 14:40:43', '', '', '', 'this-good-opportunity-and'),
(92, 4, 0, 10, 'Niramaya Villa  Private Luxury Resort Home', 'North Dillard Street is named after the founder of the company Ferruccio Lamborghini (1916-1993). Ferruccio Lamborghini founded the company in 1963 when he was 47 years old. The factory is located in a small Italian village called Sant\'Agata Bolognese near Bologna Lamborghini is named after the founder of the company Ferruccio Lamborghini (1916-1993). ', 'Kaesong, North Hwanghae, North Korea', 'Kaesŏng', 'North Hwanghae', 'North Korea', 50041, '37.8537764', '126.56619079999996', 5, 3, 2, 6, 10000, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-20 13:04:33', '2018-08-02 12:27:03', '', '', '', 'niramaya-villa-private-luxury-resort-home'),
(99, 52, 0, 1, ' BHK Two Private AC Rooms in an Apartment in Central. Nashik', 'Fetra projects Locations filter not working properly\r', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422008, '19.9974533', '73.7898023', 1, 1, 1, 1, 5000, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-21 14:58:24', '2018-07-25 10:50:16', '', '', '', 'bhk-two-private-ac-rooms-in-an-apartment-in-central-nashik'),
(100, 52, 0, 1, 'Green Villa Pune', 'galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the ', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422009, '19.9974533', '73.78980230000002', 3, 2, 2, 2, 1800, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-21 17:21:55', '2018-07-25 10:50:12', '', '', '', 'green-villa-pune'),
(102, 52, 0, 1, 'this functionality of this functionality', 'the Facebook platform and the Facebook platform and', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 42208, '19.9974533', '73.78980229999999', 1, 1, 1, 1, 9500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-23 08:27:51', '2018-07-25 10:50:08', '', '', '', 'this-functionality-of-this-functionality'),
(104, 52, 0, 1, ' BHK Two Private AC Rooms in an Apartment in Central Pune', ' BHK Two Private AC Rooms in an Apartment in Central Pune BHK Two Private AC Rooms in an Apartment in Central Pune', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422009, '19.9974533', '73.7898023', 1, 1, 1, 1, 3500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-23 09:11:42', '2018-07-25 10:50:03', '', '', '', 'bhk-two-private-ac-rooms-in-an-apartment-in-central-pune2'),
(105, 52, 0, 1, ' BHK Two Private AC Rooms in an Apartment in Central Punee', 'When Edit the property then it should not display the empty from date and To Date', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422008, '19.9974533', '73.7898023', 1, 1, 1, 1, 3500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-23 11:16:25', '2018-07-25 10:49:59', '', '', '', 'bhk-two-private-ac-rooms-in-an-apartment-in-central-punee3'),
(109, 52, 0, 6, 'sdfwer werwer ', 'sdf fsd sdfdfewrew rewrerewr', 'Nashik Gymkhana, Shivaji Road, Shalimar, Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422001, '20.0022558', '73.78648720000001', 2, 1, 1, 1, 500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-23 16:28:48', '2018-07-25 10:49:16', '', '', '', 'sdfwer-werwer'),
(111, 52, 0, 1, 'this message was delivered ', 'the Facebook platform and the Facebook platform', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422001, '0', '0', 1, 1, 1, 1, 6500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-24 11:14:50', '2018-07-25 10:49:13', '', '', '', 'this-message-was-delivered'),
(112, 52, 0, 1, 'this very nice', 'this is very', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422001, '19.9974533', '73.7898023', 1, 1, 1, 1, 3500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-24 16:04:33', '2018-07-25 10:49:08', '', '', '', 'this-very-nice'),
(113, 52, 0, 1, 'the Facebook platform y', 'the Facebook platform and the Facebook platform', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422001, '19.9974533', '73.7898023', 1, 1, 1, 1, 3500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-24 16:08:25', '2018-07-25 10:49:04', '', '', '', 'the-facebook-platform-y'),
(114, 52, 0, 1, 'this is very nice produts', 'this is very nice produts', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422001, '19.9974533', '73.7898023', 1, 1, 1, 1, 4500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-25 13:47:47', '2018-07-25 10:49:00', '', '', '', 'this-is-very-nice-produts'),
(115, 52, 0, 1, 'Green Villa Nashik', 'When cancel the booking then it should not display the Android and iOS apps ticket listing pages', 'College Rd, Madhu Vijay Colony, D\'souza Colony, Nashik, Maharashtra 422005, India', 'Nashik', 'Maharashtra', 'India', 422009, '20.0081113', '73.7567371', 4, 1, 2, 3, 4500, 0, '2', 'this is not proper information about the property', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-25 13:22:53', '2018-08-02 12:26:58', '', '', '', 'green-villa-nashik'),
(116, 52, 0, 1, 'this functionality and Facebook platform', 'this functionality and Facebook', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422009, '19.9974533', '73.7898023', 2, 2, 2, 2, 10, 0, '2', '', '$', 'USD', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'yes', '2018-07-30 09:35:46', '2018-07-30 09:51:00', '', '', '', 'this-functionality-and-facebook-platform'),
(117, 52, 0, 1, 'this message was sent from windows', 'this functionality and Facebook page', 'HH Prince Aga Khan Rd, Agarkar Nagar, Pune, Maharashtra 411001, India', 'Pune', 'Maharashtra', 'India', 422008, '18.528555399999995', '73.8746067', 1, 2, 2, 2, 4300, 0, '1', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-30 10:30:05', '2018-08-02 09:01:28', '', '', '', 'this-message-was-sent-from-windows'),
(118, 52, 0, 1, 'this message was sent from Samsung', 'this functionality and the the other', '25, Ring Rd, Hareshwar Nagar, Pratap Nagar, Jalgaon, Maharashtra 425001, India', 'Jalgaon', 'Maharashtra', 'India', 422214, '21.0110118', '75.5560606', 1, 1, 1, 1, 15, 0, '2', '', '€', 'EUR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-30 10:35:23', '2018-08-27 04:31:53', '', '', '', 'this-message-was-sent-from-samsung'),
(123, 52, 0, 1, 'uday apartments', 'this message was automatically generated and', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422008, '19.9974533', '73.7898023', 4, 2, 2, 3, 4500, 0, '2', 'This is not property information so please fill the right information', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-31 11:41:56', '2018-08-27 04:31:47', '', '', '', 'uday-apartments'),
(124, 52, 0, 1, 'Harish banglow', 'this functionality and', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422008, '19.9974533', '73.7898023', 3, 3, 3, 3, 3500, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-07-31 11:46:25', '2018-07-31 11:48:11', '', '', '', 'harish-banglow'),
(125, 45, 0, 2, 'Adhit', 'hshdb', 'Adambakkam, 3rd St, Parthasarathy Nagar, Madipakkam, Chennai, Tamil Nadu 600088, India', 'Chennai', 'Tamil Nadu', 'India', 600088, '12.9893075', '80.2010858', 10, 3, 3, 5, 200, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-08-01 09:41:22', '2018-08-02 12:26:52', '', '', '', 'adhit'),
(126, 45, 0, 8, 'Luxurious flats available in variety of  two BHK  three BHK', 'This townhome is the best the beach has to offer - Gulf-front White Sands townhomes! This 4 bedroom/3 bath which sleeps 12 is true beachfront living, and you\'re just steps from the edge of the water. Many sites claim to have gulf front but only have a glimpse of the ocean - this is the real deal with full beach frontage. Minimum Age to rent - 30 years old.', 'Navi Mumbai, Maharashtra, India', 'Navi Mumbai', 'Maharashtra', 'India', 50002, '19.0330488', '73.02966249999997', 4, 2, 2, 6, 50, 0, '2', '', '$', 'USD', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-08-02 12:21:00', '2018-08-02 12:26:22', '', '', '', 'luxurious-flats-available-in-variety-of-two-bhk-three-bhk'),
(127, 52, 0, 1, 'this is very', 'this is very life ', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422001, '19.9974533', '73.7898023', 1, 1, 1, 1, 100, 0, '2', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, '', '', '', '', '', 'no', '2018-08-04 07:29:16', '2018-08-27 04:31:41', '', '', '', 'this-is-very'),
(129, 1, 0, 11, 'Test Warehous', 'Test Warehous Test WarehousTest WarehousTest WarehousTest WarehousTest WarehousTest WarehousTest WarehousTest WarehousTest WarehousTest Warehous', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 400067, '19.9974533', '73.7898023', 0, 0, 0, 0, 0, 0, '2', '', '₹', 'INR', '1', 'open', 400, 800, 50, 'bonded', 'yes', 'Test', 3333, 7, 6, 'PEB', '', 500, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-08-21 05:10:51', '2018-09-05 12:34:53', '', '', '', 'test-warehous'),
(130, 1, 0, 12, 'Test Office Space', 'Test Office SpaceTest Office SpaceTest Office SpaceTest Office SpaceTest Office SpaceTest Office SpaceTest Office Space', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 23463, '', '', 0, 0, 0, 0, 0, 0, '2', '', '$', 'USD', '1', 'open', 2000, 10000, 2000, '', '', '', 1000, 0, NULL, 'closed', '', 0, 'private-room', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 200, NULL, NULL, NULL, NULL, NULL, 'no', '2018-08-21 05:27:15', '2018-08-22 00:01:55', '', '', '', 'test-office-space'),
(131, 1, 0, 4, 'Test Farm house', 'Test Farm houseTest Farm houseTest Farm houseTest Farm houseTest Farm houseTest Farm houseTest Farm house', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 420001, '19.9974533', '73.7898023', 1, 1, 1, 1, 2000, 0, '2', '', '€', 'EUR', '1', '', 0, 0, 0, '', '', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-08-21 06:10:43', '2018-08-27 04:31:35', '', '', '', 'test-farm-house');
INSERT INTO `property` (`id`, `user_id`, `category_id`, `property_type_id`, `property_name`, `description`, `address`, `city`, `state`, `country`, `postal_code`, `property_latitude`, `property_longitude`, `number_of_guest`, `number_of_bedrooms`, `number_of_bathrooms`, `number_of_beds`, `price_per_night`, `service_charge`, `admin_status`, `admin_comment`, `currency`, `currency_code`, `property_status`, `property_working_status`, `property_area`, `total_plot_area`, `total_build_area`, `custom_type`, `management`, `good_storage`, `admin_area`, `no_of_slots`, `available_no_of_slots`, `build_type`, `property_remark`, `price_per_sqft`, `price_per`, `employee`, `no_of_employee`, `room`, `no_of_room`, `room_price`, `desk`, `no_of_desk`, `desk_price`, `cubicles`, `no_of_cubicles`, `cubicles_price`, `available_no_of_employee`, `price_per_office`, `nearest_railway_station`, `nearest_national_highway`, `nearest_bus_stop`, `working_hours`, `working_days`, `is_featured`, `created_at`, `updated_at`, `meta_keyword`, `meta_title`, `meta_description`, `property_name_slug`) VALUES
(132, 52, 0, 11, 'Their is Warehouse', 'Their is Warehouse Their is Warehouse Their is Warehouse Their is Warehouse Their is Warehouse Their is Warehouse Their is Warehouse Their is Warehouse', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 42001, '19.9974533', '73.7898023', 0, 0, 0, 0, 0, 0, '2', '', '$', 'USD', '1', 'open', 2000, 1500, 1000, 'non-bonded', 'no', 'No', 500, 5, NULL, 'open', '', 3000, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-08-23 02:02:16', '2018-08-27 04:31:28', '', '', '', 'their-is-warehouse'),
(133, 52, 0, 11, 'Grenied Warehouse', 'Grenied Warehouse  Grenied Warehouse Grenied Warehouse Grenied Warehouse Grenied Warehouse Grenied Warehouse Grenied Warehouse Grenied Warehouse Grenied Warehouse Grenied Warehouse Grenied Warehouse Grenied Warehouse Grenied Warehouse ', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 420001, '', '', 0, 0, 0, 0, 0, 0, '2', '', '$', 'USD', '1', 'closed', 50000, 2000, 2000, 'non-bonded', 'yes', 'test', 500, 12, NULL, 'RCC', '', 2000, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, 'Nashi road', 'Mumbai-AGRA', '', '8', '6', 'no', '2018-08-23 06:19:34', '2018-08-27 04:31:22', '', '', '', 'grenied-warehouse'),
(134, 52, 0, 12, 'Their is office space', 'Their is office spaceTheir is office spaceTheir is office spaceTheir is office spaceTheir is office spaceTheir is office spaceTheir is office spaceTheir is office spaceTheir is office spaceTheir is office spaceTheir is office space', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 420001, '19.9974533', '73.7898023', 0, 0, 0, 0, 0, 0, '2', '', '€', 'EUR', '1', 'closed', 2000, 2000, 1500, '', '', '', 200, 0, NULL, 'PEB', '', 0, 'cubicles', '', 10, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 2000, 'Nashi road', '', 'Nimani', '6', '5', 'no', '2018-08-23 23:28:54', '2018-08-27 04:31:17', '', '', '', 'their-is-office-space'),
(135, 52, 0, 12, 'Shri Krupa', 'Test', 'Matoshree Nagar, Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422202, '19.9929464', '73.7786739', 0, 0, 0, 0, 0, 0, '2', '', '₹', 'INR', '1', 'open', 12, 12, 12, '', '', '', 2, 0, NULL, 'RCC', '', 0, 'person', '', 12, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, -2, 1200, '', '', '', '7', '5', 'no', '2018-08-24 00:41:48', '2018-09-10 09:31:40', '', '', '', 'shri-krupa'),
(136, 52, 0, 11, 'Book Store Room', 'Book Store RoomBook Store RoomBook Store RoomBook Store RoomBook Store RoomBook Store RoomBook Store RoomBook Store RoomBook Store RoomBook Store RoomBook Store RoomBook Store Room', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 420001, '', '', 0, 0, 0, 0, 0, 0, '2', '', '€', 'EUR', '1', 'open', 100, 2000, 3000, 'bonded', 'yes', 'test', 500, 10, -40, 'PEB', '', 5, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '6', '5', 'no', '2018-08-24 03:50:49', '2018-11-21 11:07:51', '', '', '', 'book-store-room'),
(138, 52, 0, 11, 'ghghgfhfgh', 'fghfghfghfghfgh', 'NSW, Australia', '', 'New South Wales', 'Australia', 600040, '-31.2532183', '146.921099', 0, 0, 0, 0, 0, 0, '2', '', '$', 'USD', '1', 'closed', 65765, 65, 6565, 'bonded', 'yes', 'gghjhgj', 5665, 66, NULL, 'PEB', '', 6556, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, '', '', '', '5', '5', 'no', '2018-08-24 04:56:02', '2018-08-27 04:30:59', '', '', '', 'ghghgfhfgh'),
(139, 52, 0, 8, 'gergergergreg r gfregreg rgregregreg', 'rgfregreg gregrgrg ergrgrg', 'Agarkar Nagar, Pune, Maharashtra 411001, India', 'Pune', 'Maharashtra', 'India', 411001, '18.52808', '73.8720171', 2, 1, 2, 1, 12222, 0, '2', '', '$', 'USD', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, '', '', '', '', '', 'no', '2018-08-24 10:27:06', '2018-08-27 04:30:43', '', '', '', 'gergergergreg-r-gfregreg-rgregregreg'),
(140, 27, 0, 11, 'UPS warehouse', 'warehouse property', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 110058, '19.9974533', '73.78980229999999', 0, 0, 0, 0, 5000, 0, '2', '', '€', 'EUR', '1', 'open', 1200, 3000, 1000, '', 'yes', 'food, vegetables', 200, 0, NULL, 'RCC', '', 5000, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, '', '', '', '', '', 'no', '2018-08-28 00:08:33', '2018-08-30 06:30:24', '', '', '', 'ups-warehouse'),
(141, 27, 0, 11, 'Cars24', 'Warehouse for cars', 'Mumbai Central Railway Station Building, Mumbai Central, Mumbai, Maharashtra 400008, India', 'Mumbai', 'Maharashtra', 'India', 200949, '18.969538999999997', '72.819329', 0, 0, 0, 0, 36900, 0, '1', '', '₹', 'INR', '1', 'open', 6800, 1900, 6978, '', 'yes', 'Cars', 4666, 0, NULL, 'Shed', '', 36900, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, '', '', '', '', '', 'no', '2018-08-28 00:24:08', '2018-08-28 00:26:11', '', '', '', 'cars24'),
(142, 27, 0, 11, 'ware house sharable property', 'u can share this property', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422001, '19.9974533', '73.7898023', 0, 0, 0, 0, 0, 0, '1', '', '₹', 'INR', '1', 'open', 4000, 4000, 4000, '', 'yes', 'any type', 200, 0, NULL, 'RCC', '', 210, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, '', '', '', '', '', 'no', '2018-08-28 02:58:33', '2018-08-28 03:45:33', '', '', '', 'ware-house-sharable-property'),
(143, 27, 0, 11, 'Wk', 'property dealer', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 236559, '19.9974533', '73.78980229999999', 0, 0, 0, 0, 369, 0, '1', '', '₹', 'INR', '1', 'open', 23, 288, 999, '', 'yes', 'Goods', 586, 0, NULL, 'RCC', '', 369, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, '', '', '', '', '', 'no', '2018-08-28 03:56:29', '2018-08-28 04:39:54', '', '', '', 'wk'),
(144, 27, 0, 12, 'Office for rent', 'Rented office space', 'Mumbai, Maharashtra, India', 'Mumbai', 'Maharashtra', 'India', 369369, '19.0759837', '72.8776559', 0, 0, 0, 0, 369, 0, '1', '', '$', 'USD', '1', 'open', 358, 2569, 2566, '', '', '', 2556, 0, NULL, 'Closed', '', 0, 'Cubicles', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 369, '', '', '', '', '', 'no', '2018-08-28 04:47:41', '2018-08-28 05:15:36', '', '', '', 'office-for-rent'),
(145, 27, 0, 12, 'office', 'Office office', 'Mumbai, Maharashtra, India', 'Mumbai', 'Maharashtra', 'India', 429693, '19.0759837', '72.8776559', 0, 0, 0, 0, 369, 0, '1', '', '₹', 'INR', '1', 'open', 369, 25, 699, '', '', '', 559, 0, NULL, 'Closed', '', 0, 'Cubicles', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 369, '', '', '', '', '', 'no', '2018-08-28 05:16:47', '2018-08-28 05:24:26', '', '', '', 'office'),
(146, 27, 0, 11, 'warehouse', 'warehousing', 'Mumbai, Maharashtra, India', 'Mumbai', 'Maharashtra', 'India', 3655, '19.0759837', '72.8776559', 0, 0, 0, 0, 3655, 0, '1', '', '€', 'EUR', '1', '', 256, 256, 666, '', 'no', 'Goods', 898, 0, NULL, 'Shed', '', 3655, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, '', '', '', '', '', 'no', '2018-08-28 05:27:31', '2018-08-28 05:33:13', '', '', '', 'warehouse'),
(147, 27, 0, 11, 'Watermark', 'property', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422003, '19.9974533', '73.78980229999999', 0, 0, 0, 0, 258, 0, '1', '', '€', 'EUR', '1', 'open', 365, 256, 366, 'bonded', 'no', 'good', 258, 0, NULL, 'Open', '', 258, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, '', '', '', '', '', 'no', '2018-08-28 05:34:28', '2018-08-28 05:40:23', '', '', '', 'watermark'),
(148, 27, 0, 11, 'yk', 'APi test Property1APi test Property1APi test Property1APi test Property1', 'Mumbai, Maharashtra, India', 'Mumbai', 'Maharashtra', 'India', 420001, '19.0759837', '72.8776559', 0, 0, 0, 0, 0, 0, '1', '', '€', 'EUR', '1', 'closed', 365, 4545, 4545, 'bonded', 'no', 'Rack', 111, 3, NULL, 'shed', '', 2599, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, '', '', '', '9', '5', 'no', '2018-08-28 05:44:46', '2018-08-28 23:58:57', '', '', '', 'yk'),
(149, 52, 0, 11, 'Test Warehous', 'Test WarehousTest WarehousTest WarehousTest WarehousTest Warehous', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 420001, '19.9974533', '73.7898023', 0, 0, 0, 0, 0, 0, '1', '', '€', 'EUR', '1', 'closed', 4545, 546, 0, 'bonded', 'yes', 'ffghg', 54654, 12, NULL, 'shed', '', 455, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '', '', 'no', '2018-08-28 23:01:58', '2018-08-29 04:32:31', '', '', '', 'test-warehous2'),
(150, 27, 0, 1, 'Office space', 'Office space for IT company', 'Nashik Road, Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 423569, '19.9702929', '73.8301437', 1, 2, 2, 2, 600, 0, '2', '', '$', 'USD', '1', '', 0, 0, 0, '', '', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, '', '', '', '', '', 'no', '2018-08-28 23:49:36', '2018-08-30 06:29:18', '', '', '', 'office-space'),
(151, 52, 0, 11, 'DS Group Ware House', 'DS Group Ware House description', 'Mumbai Central Railway Station Building, Mumbai Central, Mumbai, Maharashtra, India', 'Mumbai', 'Maharashtra', 'India', 400008, '18.969539', '72.819329', 0, 0, 0, 0, 0, 0, '1', '', '₹', 'INR', '1', 'open', 250, 500, 0, 'bonded', 'yes', 'All types of goods', 50, 11, NULL, 'closed', 'DS Group Ware House remarks', 10, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, 'Nashik Road Railway Station', 'Nashik-Mumbai NH', '', '9:00 to 5:00', 'Weekdays', 'no', '2018-08-29 05:28:49', '2018-08-29 04:41:28', '', '', '', 'ds-group-ware-house'),
(152, 52, 0, 11, 'Test Warehous', 'Test WarehousTest WarehousTest WarehousTest WarehousTest WarehousTest Warehous', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 10001, '', '', 0, 0, 0, 0, 0, 0, '3', 'sadsad', '$', 'USD', '1', 'open', 546, 54, 566, 'bonded', 'yes', 'cfgvfg', 45, 545, 545, 'open', 'sdsdafsdfsdfsdfsdfsdfsdfdsf', 54, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '', '', 'no', '2018-08-29 00:09:02', '2018-09-03 13:10:37', '', '', '', 'test-warehous2'),
(153, 52, 0, 12, 'retretrt', 'retretret', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 400067, '19.9974533', '73.7898023', 0, 0, 0, 0, 0, 0, '1', '', '$', 'USD', '1', 'closed', 456, 233, 43, '', '', '', 46, 0, 0, 'PEB', 'ryryrtyrtyrtyrtyrtyrtyrty', 0, 'private-room', '', 55, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 55, 45, 'fdh', '', 'dfg', 'dsdsdsfdsf', 'dfg', 'no', '2018-08-29 00:16:33', '2018-09-03 13:49:21', '', '', '', 'retretrt'),
(154, 27, 0, 12, 'ofiice for transport', 'transport office space', 'Agarkar Nagar, Pune, Maharashtra 411001, India', 'Pune', 'Maharashtra', 'India', 411001, '18.52808', '73.8720171', 0, 0, 0, 0, 28, 0, '2', '', '₹', 'INR', '1', 'open', 3001, 3002, 3002, '', '', '', 102, 0, NULL, 'RCC', 'railway station available', 0, 'Private Room', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 28, '', '', '', '', '', 'no', '2018-08-29 01:25:02', '2018-09-03 12:39:54', '', '', '', 'ofiice-for-transport'),
(155, 27, 0, 11, 'Warehousing for cars', 'Warehouse by one property', 'Mumbai Central Railway Station Building, Mumbai Central, Mumbai, Maharashtra 400008, India', 'Mumbai', 'Maharashtra', 'India', 235669, '18.969539', '72.819329', 0, 0, 0, 0, 23000, 0, '4', 'ffffg', '₹', 'INR', '1', 'open', 2500, 3000, 120, 'bonded', 'yes', 'Cars', 1500, 2, 2, 'shed', 'Best place for cars', 23000, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '', '', 'no', '2018-08-29 03:19:22', '2018-09-03 13:22:40', '', '', '', 'warehousing-for-cars'),
(157, 27, 0, 11, 'best', 'warehouse goods', 'Mumbai, Maharashtra, India', 'Mumbai', 'Maharashtra', 'India', 23, '', '', 0, 0, 0, 0, 2500, 0, '2', '', '₹', 'INR', '1', 'open', 2500, 3000, 1500, 'bonded', 'yes', 'fruits', 2000, 23, NULL, 'RCC', 'warehouse for farm', 2500, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, 'nsk', 'nh4', '', '8', '4', 'yes', '2018-08-29 04:21:20', '2018-12-12 05:45:21', '', '', '', 'best'),
(158, 27, 0, 11, 'My farm ware', 'My farm house', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 423664, '19.9974533', '73.7898023', 14, 1, 1, 2, 58, 0, '4', 'rtgret', '€', 'EUR', '1', 'open', 100, 1200, 2355, 'bonded', 'yes', 'goods', 100, 23, NULL, 'Open', 'very good', 58, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '', '', 'no', '2018-08-29 04:27:55', '2018-09-03 13:18:10', '', '', '', 'my-farm-ware'),
(159, 27, 0, 11, 'abc', 'defg', 'Mumbai, Maharashtra, India', 'Mumbai', 'Maharashtra', 'India', 235688, '19.0759837', '72.8776559', 0, 0, 0, 0, 2300, 0, '4', 'sadsad', '€', 'EUR', '1', 'open', 100, 25, 26, '', 'no', 'hood', 27, 25, NULL, 'PEB', 'good', 2300, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '8', '5', 'yes', '2018-08-29 04:41:46', '2018-12-12 05:45:17', '', '', '', 'abc'),
(160, 27, 0, 12, 'bhishan warehouse', 'ware hose for food', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422001, '19.9974533', '73.7898023', 0, 0, 0, 0, 6900, 0, '4', 'sdfdsfdsf', '₹', 'INR', '1', 'open', 10000, 10000, 0, '', '', '', 500, 0, NULL, 'RCC', 'no remark', 0, 'Dedicated Desk', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 6900, '', '', 'nsk', '', '', 'no', '2018-08-30 04:06:38', '2018-09-03 13:20:32', '', '', '', 'bhishan-warehouse'),
(161, 52, 0, 12, 'tarun office', 'office for soft company', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422001, '', '', 0, 0, 0, 0, 0, 0, '2', '', '$', 'USD', '1', 'open', 3000, 3000, 3000, '', '', '', 100, 0, 0, 'RCC', 'no remark', 0, 'person', '', 55, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 50, 120, '', '', '', '', '6', 'no', '2018-08-30 04:12:05', '2018-09-04 14:43:02', '', '', '', 'tarun-office'),
(162, 27, 0, 12, 'abc', 'sdf', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422003, '19.9974533', '73.7898023', 0, 0, 0, 0, 300, 0, '3', 'sdsadsad', '₹', 'INR', '1', 'open', 200, 100, 105, '', '', '', 100, 0, NULL, 'RCC', 'good', 0, 'Dedicated Desk', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 300, '', '', '', '', '', 'no', '2018-08-30 04:20:31', '2018-09-03 13:13:38', '', '', '', 'abc1'),
(163, 52, 0, 12, 'new office', 'office space', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422001, '19.9974533', '73.7898023', 0, 0, 0, 0, 0, 0, '2', '', '₹', 'INR', '1', 'open', 4333333333, 5000, 5000, '', '', '', 200, 0, 0, 'RCC', 'Remark', 0, 'private-room', '', 20, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 20, 12000, '', '', '', '', '', 'no', '2018-08-30 04:24:55', '2018-09-03 12:57:00', '', '', '', 'new-office'),
(164, 27, 0, 11, 'Cold storage', 'Cold storage ', 'Nashik Road, Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422009, '19.9702929', '73.8301437', 0, 0, 0, 0, 25000, 0, '2', '', '₹', 'INR', '1', 'open', 3000, 3200, 2800, 'bonded', 'yes', 'Grapes', 1500, 2, NULL, 'Closed', 'Very good space for goods', 25000, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '', '', 'no', '2018-08-30 05:28:08', '2018-09-03 12:45:34', '', '', '', 'cold-storage'),
(165, 52, 0, 4, 'asd', 'asd', 'Asdale Road, Wakefield, UK', '', 'England', 'United Kingdom', 122, '53.6520939', '-1.5092544', 333333, 1, 1, 1, 3333, 0, '3', 'sadsadsad', '$', 'USD', '1', '', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '', '', 'no', '2018-09-03 04:56:17', '2018-09-03 13:09:22', '', '', '', 'asd1'),
(166, 27, 0, 12, 'dxsfs', 'dsf', 'Navi Mumbai, Maharashtra, India', 'Navi Mumbai', 'Maharashtra', 'India', 435, '19.0330488', '73.0296625', 0, 0, 0, 0, 0, 0, '2', '', '€', 'EUR', '1', 'closed', 435, 344, 43, '', '', '', 45, 0, 0, 'RCC', '435', 0, 'person', '', 44, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 44, 4343, '', '', '', '', '', 'yes', '2018-09-03 10:35:35', '2018-12-12 05:45:13', '', '', '', 'dxsfs'),
(167, 52, 0, 8, 'sadsadsad', 'asdsadasdsad', 'Nasr City, Al Manteqah Al Oula, Nasr City, Egypt', 'Nasr City', 'Cairo Governorate', 'Egypt', 23463, '30.0168939', '31.377033600000004', 1, 1, 1, 1, 12, 0, '2', '', '€', 'EUR', '1', '', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '', '', 'no', '2018-09-04 12:42:21', '2018-09-04 13:25:07', '', '', '', 'sadsadsad'),
(168, 52, 0, 6, 'dsfdsfsdf', 'sdfsdfdsfsdfsdf', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 411034, '', '', 1, 1, 1, 1, 13, 0, '1', '', '€', 'EUR', '1', '', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '', '', 'no', '2018-09-04 12:55:40', '2018-09-07 04:09:12', '', '', '', 'dsfdsfsdf'),
(169, 103, 0, 11, 'Manmad Warehouse', 'This is sample Sagar Warehouse', 'FCI Road, Bardiya Nagar, Manmad, Maharashtra, India', 'Manmad', 'Maharashtra', 'India', 423104, '20.2559075', '74.44427769999993', 0, 0, 0, 0, 0, 0, '2', '', '₹', 'INR', '1', 'open', 3000, 200, 1500, 'bonded', 'yes', 'AS', 0, 20, 13, 'RCC', 'This is sample remark', 50, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '', '', 'no', '2018-09-04 15:23:12', '2018-09-06 09:20:44', '', '', '', 'manmad-warehouse'),
(170, 103, 0, 2, 'sdsdsd', 'sadasdasd', 'Navi Mumbai, Maharashtra, India', 'Navi Mumbai', 'Maharashtra', 'India', 422012, '', '', 12, 2, 2, 2, 120, 0, '1', '', '$', 'USD', '1', '', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '', '', 'yes', '2018-09-05 04:08:06', '2018-12-12 05:45:10', '', '', '', 'sdsdsd'),
(171, 103, 0, 12, 'Testing', 'Test', 'Kalika Mandir Bus Stop, Old Agra Road, Renuka Nagar, Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422011, '19.9913418', '73.7830317', 200, 10, 30, 20, 0, 0, '1', '', '₹', 'INR', '1', 'open', 4000, 1000, 800, '', '', '', 20, 0, 0, 'PEB', 'Test', 0, 'person', '', 30, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 30, 3000, '', '', '', '', '', 'no', '2018-09-05 13:20:03', '2018-09-18 13:24:00', '', '', '', 'testing'),
(172, 52, 0, 1, 'dddd', 'dddd', 'Delhi, India', 'Delhi', 'Delhi', 'India', 12344, '28.7040592', '77.1024902', 4, 4, 4, 4, 33, 0, '1', '', '$', 'USD', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-09-06 14:34:12', '2018-09-07 05:29:08', '', '', '', 'dddd'),
(173, 52, 0, 10, 'TA1', 'Ta property', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 12365, '19.9974533', '73.7898023', 5, 5, 5, 5, 20, 0, '1', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-09-07 05:00:38', '2018-09-07 05:28:15', '', '', '', 'ta1'),
(174, 103, 0, 4, 'TA FarmaHouse', 'TA FarmaHouse', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 1236547, '', '', 5, 2, 3, 1, 8000, 0, '1', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, 0, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '', '', 'no', '2018-09-07 05:04:15', '2018-09-18 13:20:08', '', '', '', 'ta-farmahouse'),
(175, 52, 0, 7, 'TA Plots', 'sdasdasdasd', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 123456, '19.9974533', '73.7898023', 1, 1, 1, 1, 500, 0, '1', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-09-07 05:48:22', '2018-09-07 06:40:14', '', '', '', 'ta-plots'),
(176, 52, 0, 4, 'ffff', 'ffff', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 12323455, '19.9974533', '73.7898023', 2, 2, 2, 2, 33, 0, '1', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-09-07 06:41:06', '2018-09-07 06:41:40', '', '', '', 'ffff'),
(177, 52, 0, 11, 'AT W', 'sadasdasd', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 123456, '19.9974533', '73.7898023', 2, 2, 2, 2, 3, 0, '1', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-09-07 06:56:00', '2018-09-07 07:00:23', '', '', '', 'at-w'),
(178, 52, 0, 1, 'qwdddd', 'asdasd', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 123456, '19.9974533', '73.7898023', 1, 1, 1, 1, 44, 0, '1', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-09-07 07:08:20', '2018-09-07 07:11:49', '', '', '', 'qwdddd'),
(179, 52, 0, 2, 'gggg', 'gggg', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 111, '19.9974533', '73.7898023', 1, 1, 1, 1, 44, 0, '1', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-09-07 07:13:55', '2018-09-07 07:15:24', '', '', '', 'gggg'),
(180, 52, 0, 4, 'sss', 'sss', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 122222, '19.9974533', '73.7898023', 1, 1, 1, 1, 20, 0, '1', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-09-07 07:17:58', '2018-09-07 07:29:22', '', '', '', 'sss'),
(181, 52, 0, 1, 'ffffsssss', 'ffffff', 'Mexico SSSSN, Antonio Rosales, Culiacán, Sinaloa, Mexico', 'Culiacán Rosales', 'Sinaloa', 'Mexico', 80230, '24.8105404', '-107.3909309', 1, 1, 1, 1, 2, 0, '1', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-09-07 07:30:29', '2018-09-07 09:04:02', '', '', '', 'ffffsssss'),
(182, 52, 0, 2, 'nnnn', 'sdfsdf', 'Houston, TX, USA', 'Houston', 'Texas', 'United States', 1111, '', '', 1, 1, 1, 1, 55, 0, '1', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-09-07 09:04:36', '2018-09-07 09:06:09', '', '', '', 'nnnn'),
(183, 52, 0, 2, 'TTTT', 'asdasdasd', 'Nashik Road, Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 1111, '19.9702929', '73.8301437', 1, 1, 1, 1, 55, 0, '1', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-09-07 09:11:47', '2018-09-07 10:35:15', '', '', '', 'tttt'),
(184, 52, 0, 6, 'Tushar Villa', 'Tushar Villa', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 111111, '', '', 1, 1, 1, 1, 23, 0, '1', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-09-07 10:37:52', '2018-09-07 10:44:13', '', '', '', 'tushar-villa'),
(185, 52, 0, 4, 'Ahire Farm House', 'Ahire Farm House', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 1233455, '', '', 1, 1, 1, 1, 35, 0, '1', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, NULL, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'no', '2018-09-07 10:45:55', '2018-09-07 10:49:58', '', '', '', 'ahire-farm-house'),
(186, 52, 0, 1, 'B Property', 'B Property', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 11111, '', '', 1, 1, 1, 1, 111, 0, '1', '', '₹', 'INR', '1', 'open', 0, 0, 0, 'bonded', 'yes', '', 0, 0, 0, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '', '', 'no', '2018-09-07 10:58:25', '2018-09-11 10:18:00', '', '', '', 'b-property'),
(187, 52, 0, 11, 'TA WareHouse', 'asdasd', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 80230, '19.9974533', '73.7898023', 0, 0, 0, 0, 0, 0, '1', '', '₹', 'INR', '1', 'open', 555, 12, 12, 'bonded', 'yes', '50', 10, 12, 12, 'RCC', 'asdasd', 20, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '', '', 'no', '2018-09-07 11:08:14', '2018-09-11 10:02:49', '', '', '', 'ta-warehouse'),
(188, 103, 0, 11, 'Ta Property', 'Test', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 11111, '19.9974533', '73.7898023', 0, 0, 0, 0, 0, 0, '1', '', '₹', 'INR', '1', 'open', 2222, 22, 22, 'bonded', 'yes', '222', 22, 22, 22, 'RCC', 'asdas', 20, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '', '', 'no', '2018-09-07 12:11:51', '2018-09-18 13:18:50', '', '', '', 'ta-property'),
(189, 52, 0, 11, 'Tushar WareHouse', 'Qwerty', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 21201, '19.9974533', '73.78980230000002', 0, 0, 0, 0, 0, 0, '2', '', '₹', 'INR', '1', 'open', 500, 100, 200, 'bonded', 'yes', '50', 100, 10, -75, 'PEB', 'AbCd', 50, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '', '10', 'no', '2018-09-11 04:35:03', '2018-10-22 09:48:43', '', '', '', 'tushar-warehouse'),
(190, 52, 0, 1, 'TYest', 'asdas', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 12333, '19.9974533', '73.7898023', 1, 1, 1, 1, 50, 0, '3', 'asdasdas', '₹', 'INR', '1', '', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '', '', 'no', '2018-09-11 12:22:43', '2018-09-21 06:53:15', '', '', '', 'tyest'),
(191, 52, 0, 1, 'sssss', 'asdasd', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 111, '', '', 1, 1, 1, 1, 22, 0, '2', '', '₹', 'INR', '1', '', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '', '', 'no', '2018-09-11 12:53:26', '2018-09-11 13:33:14', '', '', '', 'sssss'),
(192, 52, 0, 1, 'PPPr', 'asdaddas', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 11111, '', '', 1, 1, 2, 2, 500, 0, '2', '', '₹', 'INR', '1', '', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '', '', 'yes', '2018-09-12 06:37:43', '2018-10-02 07:00:37', '', '', '', 'pppr'),
(193, 52, 0, 1, 'Exceptional house in Garches with luxury services  minutes from Paris', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'maharastra, Link Road, Adarsh Nagar, Andheri West, Mumbai, Maharashtra, India', 'Mumbai', 'Maharashtra', 'India', 400058, '', '', 1, 1, 1, 1, 1, 0, '2', '', '$', 'USD', '1', '', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '', '', 'yes', '2018-09-14 13:15:42', '2018-12-12 05:45:07', '', '', '', 'exceptional-house-in-garches-with-luxury-services-minutes-from-paris'),
(194, 52, 0, 1, 'Test Rahul Property', 'Test Rahul Property Test Rahul Property\r\nTest Rahul Property\r\nTest Rahul Property\r\nTest Rahul Property\r\nTest Rahul Property', 'Nashik Road, Sinnarkar, Ojhar, Maharashtra, India', 'Ojhar', 'Maharashtra', 'India', 234234, '20.0861022', '73.92022969999994', 5, 1, 5, 5, 1000, 0, '1', '', '₹', 'INR', '1', '', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '', '', 'no', '2018-09-21 08:52:46', '2018-09-21 08:53:35', '', '', '', 'test-rahul-property'),
(195, 52, 0, 1, 'Test Rahul Property without dates', 'Test Rahul Property without dates', 'Nashik Road, Gandhi Nagar Airport Area, Deolali Gaon, Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422501, '', '', 2, 2, 1, 1, 12312, 0, '1', '', '₹', 'INR', '1', '', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '', '', 'no', '2018-09-21 08:55:10', '2018-09-26 10:17:42', '', '', '', 'test-rahul-property-without-dates'),
(196, 52, 0, 11, 'Test Rahul Property without dates warehouse', 'Test Rahul Property without dates warehouse', 'Nashik Road, Gandhi Nagar Airport Area, Deolali Gaon, Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422501, '19.9629289', '73.80813190000003', 0, 0, 0, 0, 0, 0, '1', '', '₹', 'INR', '1', 'open', 1233, 123, 123, 'bonded', 'yes', '123', 23, 123, 123, 'RCC', 'Test Rahul Property without dates warehouse', 5000, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '', '', 'no', '2018-09-21 08:58:12', '2018-09-21 08:58:41', '', '', '', 'test-rahul-property-without-dates-warehouse'),
(197, 52, 0, 12, 'Test Rahul Property without dates office space', 'Test Rahul Property without dates office space', 'P u n e Nashik Road, Gandhi Nagar Airport Area, Deolali Gaon, Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422501, '', '', 0, 0, 0, 0, 0, 0, '4', 'Not goood', '₹', 'INR', '1', 'open', 123123, 123, 123, '', '', '', 123, 0, 0, 'RCC', 'Test Rahul Property without dates office space', 0, 'person', '', 123, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 123, 500, '', '', '', '', '', 'no', '2018-09-21 09:00:04', '2018-09-26 04:46:24', '', '', '', 'test-rahul-property-without-dates-office-space'),
(198, 52, 0, 2, 'Test Lat lng', 'Test Lat lng', 'Nashik Road, Gandhi Nagar Airport Area, Deolali Gaon, Nashik, Maharashtra, India', '', '', '', 422501, '', '', 1, 1, 1, 1, 123, 0, '1', '', '₹', 'INR', '1', '', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '', '', 'no', '2018-09-21 11:35:14', '2018-09-27 14:00:52', '', '', '', 'test-lat-lng'),
(199, 52, 0, 1, 'realtevo', 'Realtevo is a clever, distinctive name with a clear message - it’s about the evolution of realty/real estate. The Latin-style adds a note of sophistication to a name that would be perfect for an aspirational, forward-thinking business in the property sector', 'The Mallards, Frimley, Camberley, Surrey GU16 8PB, United Kingdom', 'Frimley', 'England', 'United Kingdom', 0, '', '', 2, 2, 2, 2, 1, 0, '1', '', '$', 'USD', '1', '', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '', '', 'no', '2018-09-27 14:05:43', '2018-09-29 05:27:55', '', '', '', 'realtevo'),
(200, 52, 0, 11, 'My warehouse', 'Qwerty qwerty qwertyu', 'Nashik Phata Road, Mithila Nagari, Pimple Saudagar, Pimpri-Chinchwad, Maharashtra, India', 'Pimpri-Chinchwad', 'Maharashtra', 'India', 411027, '', '', 0, 0, 0, 0, 0, 0, '1', '', '₹', 'INR', '1', 'open', 5000, 0, 2050, 'bonded', 'yes', 'goods', 150, 4, 4, 'RCC', 'Qwerty', 10, '', '', 0, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, 0, 0, '', '', '', '', '', 'no', '2018-10-20 05:35:27', '2018-10-23 04:55:34', '', '', '', 'my-warehouse'),
(201, 52, 0, 12, 'New office space with multiple selections options', 'New office space with multiple selections options New office space with multiple selections options New office space with multiple selections options New office space with multiple selections options New office space with multiple selections options New office space with multiple selections options New office space with multiple selections options New office space with multiple selections options New office space with multiple selections options New office space with multiple selections options ', 'Marathahalli Bridge, Varthur Road, Aswath Nagar, Marathahalli, Bengaluru, Karnataka, India', 'Bengaluru', 'Karnataka', 'India', 560037, '', '', 0, 0, 0, 0, 0, 0, '2', '', '₹', 'INR', '1', 'open', 5000, 0, 2950, '', '', '', 1000, 0, 0, 'RCC', 'New office space with multiple selections options New office space with multiple selections options New office space with multiple selections options New office space with multiple selections options New office space with multiple selections options New office space with multiple selections options New office space with multiple selections options New office space with multiple selections options New office space with multiple selections options New office space with multiple selections options ', 0, '', 'on', 12, 'on', 23, NULL, 'on', 34, NULL, 'on', 45, NULL, -20, 25, '', '', '', '', '', 'no', '2018-10-23 09:02:25', '2018-10-25 09:25:09', '', '', '', 'new-office-space-with-multiple-selections-options'),
(202, 52, 0, 12, 'New office space with multiple selections options 1', 'New office space with multiple selections options 1 New office space with multiple selections options 1 New office space with multiple selections options 1 New office space with multiple selections options 1 New office space with multiple selections options 1 New office space with multiple selections options 1 New office space with multiple selections options 1 ', 'Kadugodi Bus Station, Kadugodi Fly Over, Gopalkrishna Nagar, Maithri Layout, Kadugodi, Bengaluru, Karnataka, India', 'Bengaluru', 'Karnataka', 'India', 560067, '', '', 0, 0, 0, 0, 0, 0, '2', '', '₹', 'INR', '1', 'open', 450, 0, 400, '', '', '', 50, 0, 0, 'Closed', 'New office space with multiple selections options 1 New office space with multiple selections options 1 New office space with multiple selections options 1 ', 0, '', 'on', 50, 'off', NULL, NULL, 'on', 2, NULL, 'off', NULL, NULL, -94, 40, '', '', '', '', '', 'no', '2018-10-23 10:06:22', '2018-10-24 11:36:50', '', '', '', 'new-office-space-with-multiple-selections-options-1'),
(203, 52, 0, 12, 'demo one for office space', 'demo one for office space demo one for office space demo one for office space demo one for office space demo one for office space demo one for office space ', 'Nashik Railway Track Road, Nawle Colony, Government Colony, Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422214, '19.9535298', '73.84285890000001', 0, 0, 0, 0, 0, 0, '1', '', '₹', 'INR', '1', 'open', 254, 0, 200, '', '', '', 50, 0, 0, 'Shed', 'demo one for office space demo one for office space demo one for office space demo one for office space ', 0, '', 'off', 0, 'off', 0, NULL, 'off', 0, NULL, 'on', 12, NULL, 0, 10, '', '', '', '', '', 'no', '2018-10-25 04:46:17', '2018-10-25 04:50:56', '', '', '', 'demo-one-for-office-space'),
(204, 52, 0, 12, 'New pricing for the office space', 'New pricing for the office space New pricing for the office space New pricing for the office space New pricing for the office space New pricing for the office space New pricing for the office space New pricing for the office space New pricing for the office space New pricing for the office space New pricing for the office space New pricing for the office space ', 'Pune International Airport Area, Lohgaon, Pune, Maharashtra, India', 'Pune', 'Maharashtra', 'India', 422012, '18.5775893', '73.94186030000003', 0, 0, 0, 0, 0, 0, '2', '', '₹', 'INR', '1', 'closed', 352, 0, 300, '', '', '', 50, 0, 0, 'RCC', 'New pricing for the office space New pricing for the office space New pricing for the office space New pricing for the office space New pricing for the office space New pricing for the office space New pricing for the office space New pricing for the office space New pricing for the office space ', 0, '', 'off', 0, 'on', 5, 80, 'on', 10, 50, 'on', 6, 60, 0, 0, '', '', '', '', '', 'no', '2018-10-25 13:34:18', '2018-10-30 05:30:59', '', '', '', 'new-pricing-for-the-office-space'),
(205, 52, 0, 12, 'Deepak Office space with different prices ', 'Deepak Office space with different prices Deepak Office space with different prices Deepak Office space with different prices Deepak Office space with different prices Deepak Office space with different prices Deepak Office space with different prices Deepak Office space with different prices Deepak Office space with different prices Deepak Office space with different prices ', 'Pune International Airport Area, Lohgaon, Pune, Maharashtra, India', 'Pune', 'Maharashtra', 'India', 422012, '', '', 0, 0, 0, 0, 0, 0, '2', '', '₹', 'INR', '1', 'open', 200, 0, 150, '', '', '', 50, 0, 0, 'RCC', 'Deepak Office space with different prices Deepak Office space with different prices Deepak Office space with different prices Deepak Office space with different prices Deepak Office space with different prices Deepak Office space with different prices Deepak Office space with different prices ', 0, '', 'off', 0, 'on', 5, 20, 'on', 10, 25, 'on', 15, 30, 0, 0, '', '', '', '', '', 'no', '2018-10-30 04:12:54', '2018-10-30 08:47:03', '', '', '', 'deepak-office-space-with-different-prices'),
(206, 52, 0, 1, 'Demo Property ', 'Demo Property Demo Property Demo Property Demo Property Demo Property Demo Property Demo Property Demo Property Demo Property Demo Property Demo Property Demo Property Demo Property Demo Property Demo Property ', '12th Avenue, New York, NY, USA', 'New York', 'New York', 'United States', 422012, '', '', 5, 1, 1, 1, 50, 0, '1', '', '₹', 'INR', '1', '', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, '', 'off', 0, 'off', NULL, 0, 'off', NULL, 0, 'off', NULL, 0, 0, 0, '', '', '', '', '', 'no', '2018-12-12 06:58:50', '2018-12-12 13:19:12', '', '', '', 'demo-property'),
(207, 52, 0, 1, 'demo', 'dertyuiop', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', 422012, '19.9974533', '73.78980230000002', 1, 1, 1, 1, 1, 0, '2', '', '₹', 'INR', '1', '', 0, 0, 0, '', '', '', 0, 0, 0, '', '', 0, '', 'off', 0, 'off', 0, 0, 'off', 0, 0, 'off', 0, 0, 0, 0, '', '', '', '', '', 'no', '2018-12-12 13:20:09', '2018-12-25 06:34:37', '', '', '', 'demo');

-- --------------------------------------------------------

--
-- Table structure for table `property_aminities`
--

CREATE TABLE `property_aminities` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `aminities_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `property_aminities`
--

INSERT INTO `property_aminities` (`id`, `property_id`, `aminities_id`, `created_at`, `updated_at`) VALUES
(17, 1, 17, '2018-05-29 11:35:19', '2018-05-29 11:35:19'),
(18, 1, 20, '2018-05-29 11:35:19', '2018-05-29 11:35:19'),
(19, 1, 21, '2018-05-29 11:35:19', '2018-05-29 11:35:19'),
(20, 1, 22, '2018-05-29 11:35:19', '2018-05-29 11:35:19'),
(21, 1, 24, '2018-05-29 11:35:19', '2018-05-29 11:35:19'),
(22, 1, 25, '2018-05-29 11:35:19', '2018-05-29 11:35:19'),
(23, 1, 29, '2018-05-29 11:35:19', '2018-05-29 11:35:19'),
(24, 1, 31, '2018-05-29 11:35:19', '2018-05-29 11:35:19'),
(25, 2, 17, '2018-05-29 11:59:15', '2018-05-29 11:59:15'),
(26, 2, 20, '2018-05-29 11:59:15', '2018-05-29 11:59:15'),
(27, 2, 22, '2018-05-29 11:59:15', '2018-05-29 11:59:15'),
(28, 2, 24, '2018-05-29 11:59:15', '2018-05-29 11:59:15'),
(29, 2, 25, '2018-05-29 11:59:15', '2018-05-29 11:59:15'),
(30, 2, 26, '2018-05-29 11:59:15', '2018-05-29 11:59:15'),
(31, 2, 27, '2018-05-29 11:59:15', '2018-05-29 11:59:15'),
(32, 2, 29, '2018-05-29 11:59:15', '2018-05-29 11:59:15'),
(34, 4, 13, '2018-05-29 13:05:12', '2018-05-29 13:05:12'),
(35, 4, 14, '2018-05-29 13:05:12', '2018-05-29 13:05:12'),
(36, 5, 13, '2018-05-29 13:13:15', '2018-05-29 13:13:15'),
(37, 5, 14, '2018-05-29 13:13:15', '2018-05-29 13:13:15'),
(38, 8, 11, '2018-06-01 03:03:45', '2018-06-01 03:03:45'),
(39, 9, 11, '2018-06-01 03:08:54', '2018-06-01 03:08:54'),
(40, 11, 17, '2018-06-02 04:40:43', '2018-06-02 04:40:43'),
(41, 11, 20, '2018-06-02 04:40:43', '2018-06-02 04:40:43'),
(42, 11, 22, '2018-06-02 04:40:43', '2018-06-02 04:40:43'),
(43, 11, 24, '2018-06-02 04:40:43', '2018-06-02 04:40:43'),
(44, 11, 26, '2018-06-02 04:40:43', '2018-06-02 04:40:43'),
(45, 11, 28, '2018-06-02 04:40:43', '2018-06-02 04:40:43'),
(46, 11, 30, '2018-06-02 04:40:43', '2018-06-02 04:40:43'),
(75, 13, 17, '2018-06-04 07:03:42', '2018-06-04 07:03:42'),
(76, 13, 23, '2018-06-04 07:03:42', '2018-06-04 07:03:42'),
(77, 13, 28, '2018-06-04 07:03:42', '2018-06-04 07:03:42'),
(78, 13, 30, '2018-06-04 07:03:42', '2018-06-04 07:03:42'),
(79, 13, 31, '2018-06-04 07:03:42', '2018-06-04 07:03:42'),
(80, 12, 17, '2018-06-04 07:04:48', '2018-06-04 07:04:48'),
(81, 12, 21, '2018-06-04 07:04:48', '2018-06-04 07:04:48'),
(82, 12, 24, '2018-06-04 07:04:48', '2018-06-04 07:04:48'),
(83, 12, 27, '2018-06-04 07:04:48', '2018-06-04 07:04:48'),
(84, 12, 30, '2018-06-04 07:04:48', '2018-06-04 07:04:48'),
(85, 12, 31, '2018-06-04 07:04:48', '2018-06-04 07:04:48'),
(89, 15, 11, '2018-06-04 07:30:00', '2018-06-04 07:30:00'),
(90, 16, 11, '2018-06-04 08:49:02', '2018-06-04 08:49:02'),
(91, 17, 17, '2018-06-07 09:33:54', '2018-06-07 09:33:54'),
(92, 17, 26, '2018-06-07 09:33:54', '2018-06-07 09:33:54'),
(93, 17, 27, '2018-06-07 09:33:54', '2018-06-07 09:33:54'),
(94, 19, 17, '2018-06-08 00:40:45', '2018-06-08 00:40:45'),
(95, 19, 19, '2018-06-08 00:40:45', '2018-06-08 00:40:45'),
(96, 19, 20, '2018-06-08 00:40:45', '2018-06-08 00:40:45'),
(97, 19, 21, '2018-06-08 00:40:45', '2018-06-08 00:40:45'),
(98, 20, 17, '2018-06-11 00:26:47', '2018-06-11 00:26:47'),
(99, 20, 29, '2018-06-11 00:26:47', '2018-06-11 00:26:47'),
(100, 20, 30, '2018-06-11 00:26:47', '2018-06-11 00:26:47'),
(101, 22, 17, '2018-06-11 00:42:30', '2018-06-11 00:42:30'),
(102, 22, 26, '2018-06-11 00:42:30', '2018-06-11 00:42:30'),
(103, 22, 29, '2018-06-11 00:42:30', '2018-06-11 00:42:30'),
(104, 21, 17, '2018-06-11 00:57:22', '2018-06-11 00:57:22'),
(105, 21, 20, '2018-06-11 00:57:22', '2018-06-11 00:57:22'),
(106, 21, 21, '2018-06-11 00:57:22', '2018-06-11 00:57:22'),
(107, 21, 26, '2018-06-11 00:57:22', '2018-06-11 00:57:22'),
(108, 21, 27, '2018-06-11 00:57:22', '2018-06-11 00:57:22'),
(109, 21, 30, '2018-06-11 00:57:22', '2018-06-11 00:57:22'),
(110, 21, 31, '2018-06-11 00:57:22', '2018-06-11 00:57:22'),
(111, 21, 17, '2018-06-11 01:06:06', '2018-06-11 01:06:06'),
(112, 21, 21, '2018-06-11 01:06:06', '2018-06-11 01:06:06'),
(113, 21, 24, '2018-06-11 01:06:06', '2018-06-11 01:06:06'),
(114, 21, 30, '2018-06-11 01:06:06', '2018-06-11 01:06:06'),
(115, 21, 31, '2018-06-11 01:06:06', '2018-06-11 01:06:06'),
(116, 23, 26, '2018-06-11 02:55:18', '2018-06-11 02:55:18'),
(117, 23, 27, '2018-06-11 02:55:18', '2018-06-11 02:55:18'),
(118, 23, 30, '2018-06-11 02:55:18', '2018-06-11 02:55:18'),
(119, 24, 8, '2018-06-11 03:00:51', '2018-06-11 03:00:51'),
(120, 24, 16, '2018-06-11 03:00:51', '2018-06-11 03:00:51'),
(121, 24, 18, '2018-06-11 03:00:51', '2018-06-11 03:00:51'),
(122, 25, 11, '2018-06-11 03:01:38', '2018-06-11 03:01:38'),
(123, 29, 17, '2018-06-12 00:56:28', '2018-06-12 00:56:28'),
(124, 29, 30, '2018-06-12 00:56:28', '2018-06-12 00:56:28'),
(125, 30, 17, '2018-06-12 01:07:49', '2018-06-12 01:07:49'),
(126, 30, 20, '2018-06-12 01:07:49', '2018-06-12 01:07:49'),
(127, 30, 23, '2018-06-12 01:07:49', '2018-06-12 01:07:49'),
(128, 30, 26, '2018-06-12 01:07:49', '2018-06-12 01:07:49'),
(129, 30, 29, '2018-06-12 01:07:49', '2018-06-12 01:07:49'),
(130, 30, 31, '2018-06-12 01:07:50', '2018-06-12 01:07:50'),
(131, 31, 17, '2018-06-12 01:10:08', '2018-06-12 01:10:08'),
(132, 31, 26, '2018-06-12 01:10:08', '2018-06-12 01:10:08'),
(133, 31, 30, '2018-06-12 01:10:08', '2018-06-12 01:10:08'),
(136, 33, 17, '2018-06-12 08:09:05', '2018-06-12 08:09:05'),
(137, 32, 17, '2018-06-12 08:11:07', '2018-06-12 08:11:07'),
(138, 34, 8, '2018-06-12 08:11:22', '2018-06-12 08:11:22'),
(139, 34, 16, '2018-06-12 08:11:22', '2018-06-12 08:11:22'),
(140, 34, 18, '2018-06-12 08:11:22', '2018-06-12 08:11:22'),
(144, 35, 17, '2018-06-12 08:17:37', '2018-06-12 08:17:37'),
(145, 35, 23, '2018-06-12 08:17:37', '2018-06-12 08:17:37'),
(146, 35, 30, '2018-06-12 08:17:37', '2018-06-12 08:17:37'),
(162, 43, 8, '2018-07-06 01:30:30', '2018-07-06 01:30:30'),
(163, 43, 18, '2018-07-06 01:30:30', '2018-07-06 01:30:30'),
(164, 43, 16, '2018-07-06 01:30:30', '2018-07-06 01:30:30'),
(165, 48, 17, '2018-07-07 06:19:45', '2018-07-07 06:19:45'),
(166, 48, 19, '2018-07-07 06:19:45', '2018-07-07 06:19:45'),
(172, 47, 8, '2018-07-07 07:20:30', '2018-07-07 07:20:30'),
(173, 47, 18, '2018-07-07 07:20:30', '2018-07-07 07:20:30'),
(174, 47, 16, '2018-07-07 07:20:30', '2018-07-07 07:20:30'),
(176, 47, 8, '2018-07-07 07:23:14', '2018-07-07 07:23:14'),
(178, 45, 8, '2018-07-07 07:32:04', '2018-07-07 07:32:04'),
(179, 45, 16, '2018-07-07 07:32:04', '2018-07-07 07:32:04'),
(180, 45, 18, '2018-07-07 07:32:04', '2018-07-07 07:32:04'),
(182, 49, 8, '2018-07-07 07:34:25', '2018-07-07 07:34:25'),
(192, 55, 17, '2018-07-09 00:20:13', '2018-07-09 00:20:13'),
(193, 55, 19, '2018-07-09 00:20:13', '2018-07-09 00:20:13'),
(194, 55, 21, '2018-07-09 00:20:13', '2018-07-09 00:20:13'),
(195, 55, 25, '2018-07-09 00:20:13', '2018-07-09 00:20:13'),
(196, 55, 29, '2018-07-09 00:20:13', '2018-07-09 00:20:13'),
(279, 57, 24, '2018-07-09 07:49:40', '2018-07-09 07:49:40'),
(280, 57, 26, '2018-07-09 07:49:40', '2018-07-09 07:49:40'),
(287, 58, 21, '2018-07-09 23:40:07', '2018-07-09 23:40:07'),
(288, 58, 23, '2018-07-09 23:40:07', '2018-07-09 23:40:07'),
(289, 58, 25, '2018-07-09 23:40:07', '2018-07-09 23:40:07'),
(290, 58, 27, '2018-07-09 23:40:07', '2018-07-09 23:40:07'),
(294, 60, 17, '2018-07-10 01:19:13', '2018-07-10 01:19:13'),
(295, 60, 20, '2018-07-10 01:19:13', '2018-07-10 01:19:13'),
(296, 60, 22, '2018-07-10 01:19:13', '2018-07-10 01:19:13'),
(300, 59, 14, '2018-07-10 05:01:27', '2018-07-10 05:01:27'),
(314, 28, 17, '2018-07-13 04:36:12', '2018-07-13 04:36:12'),
(315, 28, 31, '2018-07-13 04:36:12', '2018-07-13 04:36:12'),
(316, 65, 17, '2018-07-13 04:37:07', '2018-07-13 04:37:07'),
(317, 65, 27, '2018-07-13 04:37:07', '2018-07-13 04:37:07'),
(318, 65, 29, '2018-07-13 04:37:07', '2018-07-13 04:37:07'),
(319, 65, 30, '2018-07-13 04:37:07', '2018-07-13 04:37:07'),
(321, 66, 11, '2018-07-13 04:41:03', '2018-07-13 04:41:03'),
(322, 71, 17, '2018-07-13 05:49:44', '2018-07-13 05:49:44'),
(323, 71, 30, '2018-07-13 05:49:44', '2018-07-13 05:49:44'),
(324, 71, 31, '2018-07-13 05:49:44', '2018-07-13 05:49:44'),
(325, 64, 17, '2018-07-13 05:52:34', '2018-07-13 05:52:34'),
(326, 64, 21, '2018-07-13 05:52:34', '2018-07-13 05:52:34'),
(327, 64, 22, '2018-07-13 05:52:34', '2018-07-13 05:52:34'),
(328, 64, 23, '2018-07-13 05:52:34', '2018-07-13 05:52:34'),
(329, 64, 27, '2018-07-13 05:52:34', '2018-07-13 05:52:34'),
(330, 64, 30, '2018-07-13 05:52:34', '2018-07-13 05:52:34'),
(354, 75, 11, '2018-07-13 08:51:31', '2018-07-13 08:51:31'),
(355, 77, 17, '2018-07-13 08:57:56', '2018-07-13 08:57:56'),
(356, 77, 19, '2018-07-13 08:57:56', '2018-07-13 08:57:56'),
(357, 77, 26, '2018-07-13 08:57:56', '2018-07-13 08:57:56'),
(358, 77, 31, '2018-07-13 08:57:56', '2018-07-13 08:57:56'),
(359, 78, 13, '2018-07-13 11:34:59', '2018-07-13 11:34:59'),
(360, 78, 14, '2018-07-13 11:34:59', '2018-07-13 11:34:59'),
(361, 79, 17, '2018-07-16 07:16:26', '2018-07-16 07:16:26'),
(362, 79, 26, '2018-07-16 07:16:26', '2018-07-16 07:16:26'),
(363, 79, 30, '2018-07-16 07:16:26', '2018-07-16 07:16:26'),
(364, 79, 31, '2018-07-16 07:16:26', '2018-07-16 07:16:26'),
(374, 80, 17, '2018-07-16 11:31:15', '2018-07-16 11:31:15'),
(375, 80, 21, '2018-07-16 11:31:15', '2018-07-16 11:31:15'),
(376, 80, 30, '2018-07-16 11:31:15', '2018-07-16 11:31:15'),
(377, 14, 17, '2018-07-16 11:31:46', '2018-07-16 11:31:46'),
(378, 14, 19, '2018-07-16 11:31:46', '2018-07-16 11:31:46'),
(379, 14, 22, '2018-07-16 11:31:46', '2018-07-16 11:31:46'),
(380, 14, 23, '2018-07-16 11:31:46', '2018-07-16 11:31:46'),
(381, 14, 30, '2018-07-16 11:31:46', '2018-07-16 11:31:46'),
(382, 14, 31, '2018-07-16 11:31:46', '2018-07-16 11:31:46'),
(396, 36, 19, '2018-07-17 04:44:53', '2018-07-17 04:44:53'),
(397, 36, 20, '2018-07-17 04:44:53', '2018-07-17 04:44:53'),
(398, 36, 22, '2018-07-17 04:44:53', '2018-07-17 04:44:53'),
(399, 36, 27, '2018-07-17 04:44:53', '2018-07-17 04:44:53'),
(400, 36, 28, '2018-07-17 04:44:53', '2018-07-17 04:44:53'),
(454, 81, 17, '2018-07-18 13:36:08', '2018-07-18 13:36:08'),
(455, 81, 29, '2018-07-18 13:36:08', '2018-07-18 13:36:08'),
(456, 81, 31, '2018-07-18 13:36:08', '2018-07-18 13:36:08'),
(481, 82, 17, '2018-07-18 13:45:04', '2018-07-18 13:45:04'),
(482, 82, 30, '2018-07-18 13:45:04', '2018-07-18 13:45:04'),
(483, 82, 31, '2018-07-18 13:45:04', '2018-07-18 13:45:04'),
(484, 83, 17, '2018-07-18 13:48:04', '2018-07-18 13:48:04'),
(485, 83, 30, '2018-07-18 13:48:04', '2018-07-18 13:48:04'),
(493, 85, 20, '2018-07-19 09:37:08', '2018-07-19 09:37:08'),
(494, 85, 21, '2018-07-19 09:37:08', '2018-07-19 09:37:08'),
(495, 85, 31, '2018-07-19 09:37:08', '2018-07-19 09:37:08'),
(498, 87, 17, '2018-07-19 12:31:59', '2018-07-19 12:31:59'),
(499, 87, 30, '2018-07-19 12:31:59', '2018-07-19 12:31:59'),
(500, 88, 17, '2018-07-19 15:05:59', '2018-07-19 15:05:59'),
(501, 88, 31, '2018-07-19 15:05:59', '2018-07-19 15:05:59'),
(510, 92, 32, '2018-07-20 14:12:52', '2018-07-20 14:12:52'),
(511, 92, 33, '2018-07-20 14:12:52', '2018-07-20 14:12:52'),
(512, 92, 34, '2018-07-20 14:12:52', '2018-07-20 14:12:52'),
(513, 92, 35, '2018-07-20 14:12:52', '2018-07-20 14:12:52'),
(514, 92, 36, '2018-07-20 14:12:52', '2018-07-20 14:12:52'),
(515, 92, 37, '2018-07-20 14:12:52', '2018-07-20 14:12:52'),
(516, 92, 38, '2018-07-20 14:12:52', '2018-07-20 14:12:52'),
(517, 92, 39, '2018-07-20 14:12:52', '2018-07-20 14:12:52'),
(523, 90, 17, '2018-07-20 14:40:43', '2018-07-20 14:40:43'),
(524, 90, 30, '2018-07-20 14:40:43', '2018-07-20 14:40:43'),
(525, 90, 31, '2018-07-20 14:40:43', '2018-07-20 14:40:43'),
(583, 99, 17, '2018-07-21 15:22:12', '2018-07-21 15:22:12'),
(584, 99, 30, '2018-07-21 15:22:12', '2018-07-21 15:22:12'),
(585, 99, 31, '2018-07-21 15:22:12', '2018-07-21 15:22:12'),
(590, 100, 17, '2018-07-21 17:25:04', '2018-07-21 17:25:04'),
(591, 100, 23, '2018-07-21 17:25:04', '2018-07-21 17:25:04'),
(592, 100, 30, '2018-07-21 17:25:04', '2018-07-21 17:25:04'),
(593, 100, 31, '2018-07-21 17:25:04', '2018-07-21 17:25:04'),
(594, 102, 17, '2018-07-23 08:28:41', '2018-07-23 08:28:41'),
(595, 102, 27, '2018-07-23 08:28:41', '2018-07-23 08:28:41'),
(596, 102, 30, '2018-07-23 08:28:41', '2018-07-23 08:28:41'),
(597, 102, 31, '2018-07-23 08:28:41', '2018-07-23 08:28:41'),
(606, 104, 17, '2018-07-23 09:56:28', '2018-07-23 09:56:28'),
(607, 104, 27, '2018-07-23 09:56:28', '2018-07-23 09:56:28'),
(608, 104, 30, '2018-07-23 09:56:28', '2018-07-23 09:56:28'),
(609, 104, 31, '2018-07-23 09:56:28', '2018-07-23 09:56:28'),
(625, 105, 17, '2018-07-23 16:30:15', '2018-07-23 16:30:15'),
(626, 105, 27, '2018-07-23 16:30:15', '2018-07-23 16:30:15'),
(627, 105, 30, '2018-07-23 16:30:15', '2018-07-23 16:30:15'),
(660, 111, 17, '2018-07-24 16:13:37', '2018-07-24 16:13:37'),
(661, 111, 29, '2018-07-24 16:13:37', '2018-07-24 16:13:37'),
(662, 111, 30, '2018-07-24 16:13:37', '2018-07-24 16:13:37'),
(672, 112, 17, '2018-07-24 16:36:21', '2018-07-24 16:36:21'),
(673, 112, 23, '2018-07-24 16:36:21', '2018-07-24 16:36:21'),
(674, 112, 30, '2018-07-24 16:36:21', '2018-07-24 16:36:21'),
(678, 61, 11, '2018-07-24 16:41:46', '2018-07-24 16:41:46'),
(685, 113, 17, '2018-07-24 16:52:42', '2018-07-24 16:52:42'),
(686, 113, 30, '2018-07-24 16:52:42', '2018-07-24 16:52:42'),
(687, 113, 31, '2018-07-24 16:52:42', '2018-07-24 16:52:42'),
(688, 114, 17, '2018-07-25 13:52:40', '2018-07-25 13:52:40'),
(689, 114, 30, '2018-07-25 13:52:40', '2018-07-25 13:52:40'),
(690, 114, 26, '2018-07-25 13:52:40', '2018-07-25 13:52:40'),
(699, 116, 17, '2018-07-30 09:42:35', '2018-07-30 09:42:35'),
(700, 116, 26, '2018-07-30 09:42:35', '2018-07-30 09:42:35'),
(772, 123, 17, '2018-07-31 11:43:41', '2018-07-31 11:43:41'),
(773, 123, 26, '2018-07-31 11:43:41', '2018-07-31 11:43:41'),
(774, 123, 27, '2018-07-31 11:43:41', '2018-07-31 11:43:41'),
(779, 124, 17, '2018-07-31 11:47:46', '2018-07-31 11:47:46'),
(780, 124, 26, '2018-07-31 11:47:46', '2018-07-31 11:47:46'),
(781, 124, 27, '2018-07-31 11:47:46', '2018-07-31 11:47:46'),
(782, 124, 30, '2018-07-31 11:47:46', '2018-07-31 11:47:46'),
(800, 118, 17, '2018-08-02 09:01:06', '2018-08-02 09:01:06'),
(801, 118, 26, '2018-08-02 09:01:06', '2018-08-02 09:01:06'),
(802, 118, 27, '2018-08-02 09:01:06', '2018-08-02 09:01:06'),
(803, 118, 29, '2018-08-02 09:01:06', '2018-08-02 09:01:06'),
(804, 118, 30, '2018-08-02 09:01:06', '2018-08-02 09:01:06'),
(805, 117, 17, '2018-08-02 09:01:28', '2018-08-02 09:01:28'),
(806, 117, 26, '2018-08-02 09:01:28', '2018-08-02 09:01:28'),
(807, 117, 27, '2018-08-02 09:01:28', '2018-08-02 09:01:28'),
(808, 117, 29, '2018-08-02 09:01:28', '2018-08-02 09:01:28'),
(809, 115, 17, '2018-08-02 09:02:14', '2018-08-02 09:02:14'),
(810, 115, 27, '2018-08-02 09:02:14', '2018-08-02 09:02:14'),
(811, 115, 29, '2018-08-02 09:02:14', '2018-08-02 09:02:14'),
(812, 115, 31, '2018-08-02 09:02:14', '2018-08-02 09:02:14'),
(844, 129, 40, '2018-08-21 06:09:32', '2018-08-21 06:09:32'),
(845, 129, 41, '2018-08-21 06:09:32', '2018-08-21 06:09:32'),
(846, 129, 43, '2018-08-21 06:09:32', '2018-08-21 06:09:32'),
(847, 129, 50, '2018-08-21 06:09:32', '2018-08-21 06:09:32'),
(848, 129, 52, '2018-08-21 06:09:32', '2018-08-21 06:09:32'),
(850, 131, 11, '2018-08-21 06:11:39', '2018-08-21 06:11:39'),
(861, 130, 53, '2018-08-21 07:14:50', '2018-08-21 07:14:50'),
(862, 130, 54, '2018-08-21 07:14:50', '2018-08-21 07:14:50'),
(863, 130, 55, '2018-08-21 07:14:50', '2018-08-21 07:14:50'),
(864, 130, 56, '2018-08-21 07:14:50', '2018-08-21 07:14:50'),
(865, 130, 57, '2018-08-21 07:14:50', '2018-08-21 07:14:50'),
(866, 130, 58, '2018-08-21 07:14:50', '2018-08-21 07:14:50'),
(867, 130, 59, '2018-08-21 07:14:50', '2018-08-21 07:14:50'),
(868, 130, 60, '2018-08-21 07:14:50', '2018-08-21 07:14:50'),
(869, 130, 61, '2018-08-21 07:14:50', '2018-08-21 07:14:50'),
(870, 130, 63, '2018-08-21 07:14:50', '2018-08-21 07:14:50'),
(871, 132, 48, '2018-08-24 00:07:06', '2018-08-24 00:07:06'),
(872, 132, 49, '2018-08-24 00:07:06', '2018-08-24 00:07:06'),
(873, 132, 50, '2018-08-24 00:07:06', '2018-08-24 00:07:06'),
(874, 132, 51, '2018-08-24 00:07:06', '2018-08-24 00:07:06'),
(875, 132, 52, '2018-08-24 00:07:07', '2018-08-24 00:07:07'),
(952, 133, 43, '2018-08-24 04:40:58', '2018-08-24 04:40:58'),
(953, 133, 45, '2018-08-24 04:40:58', '2018-08-24 04:40:58'),
(954, 133, 49, '2018-08-24 04:40:58', '2018-08-24 04:40:58'),
(955, 133, 50, '2018-08-24 04:40:58', '2018-08-24 04:40:58'),
(956, 133, 51, '2018-08-24 04:40:58', '2018-08-24 04:40:58'),
(957, 133, 52, '2018-08-24 04:40:58', '2018-08-24 04:40:58'),
(962, 136, 40, '2018-08-24 05:01:35', '2018-08-24 05:01:35'),
(963, 136, 42, '2018-08-24 05:01:35', '2018-08-24 05:01:35'),
(964, 136, 43, '2018-08-24 05:01:35', '2018-08-24 05:01:35'),
(965, 136, 44, '2018-08-24 05:01:35', '2018-08-24 05:01:35'),
(966, 136, 45, '2018-08-24 05:01:35', '2018-08-24 05:01:35'),
(967, 136, 51, '2018-08-24 05:01:35', '2018-08-24 05:01:35'),
(968, 136, 52, '2018-08-24 05:01:36', '2018-08-24 05:01:36'),
(972, 138, 40, '2018-08-24 05:05:42', '2018-08-24 05:05:42'),
(973, 138, 51, '2018-08-24 05:05:42', '2018-08-24 05:05:42'),
(974, 138, 52, '2018-08-24 05:05:42', '2018-08-24 05:05:42'),
(975, 134, 59, '2018-08-24 05:06:03', '2018-08-24 05:06:03'),
(976, 134, 60, '2018-08-24 05:06:03', '2018-08-24 05:06:03'),
(977, 134, 61, '2018-08-24 05:06:03', '2018-08-24 05:06:03'),
(978, 134, 62, '2018-08-24 05:06:03', '2018-08-24 05:06:03'),
(979, 134, 63, '2018-08-24 05:06:03', '2018-08-24 05:06:03'),
(980, 134, 64, '2018-08-24 05:06:03', '2018-08-24 05:06:03'),
(981, 134, 66, '2018-08-24 05:06:03', '2018-08-24 05:06:03'),
(982, 134, 70, '2018-08-24 05:06:03', '2018-08-24 05:06:03'),
(986, 135, 61, '2018-08-24 05:08:30', '2018-08-24 05:08:30'),
(987, 135, 62, '2018-08-24 05:08:30', '2018-08-24 05:08:30'),
(988, 135, 64, '2018-08-24 05:08:30', '2018-08-24 05:08:30'),
(989, 140, 49, '2018-08-28 00:11:07', '2018-08-28 00:11:07'),
(990, 140, 50, '2018-08-28 00:11:07', '2018-08-28 00:11:07'),
(991, 141, 40, '2018-08-28 00:26:11', '2018-08-28 00:26:11'),
(992, 141, 42, '2018-08-28 00:26:11', '2018-08-28 00:26:11'),
(993, 141, 49, '2018-08-28 00:26:11', '2018-08-28 00:26:11'),
(994, 141, 50, '2018-08-28 00:26:11', '2018-08-28 00:26:11'),
(995, 141, 51, '2018-08-28 00:26:11', '2018-08-28 00:26:11'),
(996, 142, 41, '2018-08-28 03:45:33', '2018-08-28 03:45:33'),
(997, 143, 40, '2018-08-28 04:39:54', '2018-08-28 04:39:54'),
(998, 143, 41, '2018-08-28 04:39:54', '2018-08-28 04:39:54'),
(999, 143, 47, '2018-08-28 04:39:54', '2018-08-28 04:39:54'),
(1000, 143, 49, '2018-08-28 04:39:54', '2018-08-28 04:39:54'),
(1001, 143, 50, '2018-08-28 04:39:54', '2018-08-28 04:39:54'),
(1002, 144, 53, '2018-08-28 05:15:36', '2018-08-28 05:15:36'),
(1003, 144, 54, '2018-08-28 05:15:36', '2018-08-28 05:15:36'),
(1004, 144, 55, '2018-08-28 05:15:36', '2018-08-28 05:15:36'),
(1005, 144, 56, '2018-08-28 05:15:36', '2018-08-28 05:15:36'),
(1006, 144, 57, '2018-08-28 05:15:36', '2018-08-28 05:15:36'),
(1007, 144, 58, '2018-08-28 05:15:36', '2018-08-28 05:15:36'),
(1008, 144, 59, '2018-08-28 05:15:36', '2018-08-28 05:15:36'),
(1009, 144, 60, '2018-08-28 05:15:36', '2018-08-28 05:15:36'),
(1010, 144, 61, '2018-08-28 05:15:36', '2018-08-28 05:15:36'),
(1011, 144, 62, '2018-08-28 05:15:36', '2018-08-28 05:15:36'),
(1012, 144, 63, '2018-08-28 05:15:36', '2018-08-28 05:15:36'),
(1013, 144, 64, '2018-08-28 05:15:36', '2018-08-28 05:15:36'),
(1014, 144, 65, '2018-08-28 05:15:36', '2018-08-28 05:15:36'),
(1015, 144, 66, '2018-08-28 05:15:36', '2018-08-28 05:15:36'),
(1016, 144, 67, '2018-08-28 05:15:36', '2018-08-28 05:15:36'),
(1017, 144, 68, '2018-08-28 05:15:36', '2018-08-28 05:15:36'),
(1018, 144, 69, '2018-08-28 05:15:36', '2018-08-28 05:15:36'),
(1019, 144, 70, '2018-08-28 05:15:36', '2018-08-28 05:15:36'),
(1020, 145, 53, '2018-08-28 05:24:26', '2018-08-28 05:24:26'),
(1021, 145, 54, '2018-08-28 05:24:26', '2018-08-28 05:24:26'),
(1022, 146, 40, '2018-08-28 05:33:13', '2018-08-28 05:33:13'),
(1023, 146, 41, '2018-08-28 05:33:13', '2018-08-28 05:33:13'),
(1024, 147, 42, '2018-08-28 05:40:23', '2018-08-28 05:40:23'),
(1025, 147, 43, '2018-08-28 05:40:23', '2018-08-28 05:40:23'),
(1026, 148, 43, '2018-08-28 23:06:46', '2018-08-28 23:06:46'),
(1027, 148, 44, '2018-08-28 23:06:46', '2018-08-28 23:06:46'),
(1028, 148, 46, '2018-08-28 23:06:47', '2018-08-28 23:06:47'),
(1029, 148, 49, '2018-08-28 23:06:47', '2018-08-28 23:06:47'),
(1030, 149, 40, '2018-08-29 05:17:06', '2018-08-29 05:17:06'),
(1031, 149, 41, '2018-08-29 05:17:06', '2018-08-29 05:17:06'),
(1032, 149, 42, '2018-08-29 05:17:06', '2018-08-29 05:17:06'),
(1033, 149, 43, '2018-08-29 05:17:06', '2018-08-29 05:17:06'),
(1034, 149, 45, '2018-08-29 05:17:06', '2018-08-29 05:17:06'),
(1035, 149, 47, '2018-08-29 05:17:06', '2018-08-29 05:17:06'),
(1036, 149, 48, '2018-08-29 05:17:06', '2018-08-29 05:17:06'),
(1037, 149, 49, '2018-08-29 05:17:06', '2018-08-29 05:17:06'),
(1038, 149, 50, '2018-08-29 05:17:07', '2018-08-29 05:17:07'),
(1039, 149, 51, '2018-08-29 05:17:07', '2018-08-29 05:17:07'),
(1040, 149, 52, '2018-08-29 05:17:07', '2018-08-29 05:17:07'),
(1068, 151, 44, '2018-08-29 05:45:35', '2018-08-29 05:45:35'),
(1069, 151, 45, '2018-08-29 05:45:35', '2018-08-29 05:45:35'),
(1070, 151, 46, '2018-08-29 05:45:35', '2018-08-29 05:45:35'),
(1071, 151, 47, '2018-08-29 05:45:35', '2018-08-29 05:45:35'),
(1072, 151, 48, '2018-08-29 05:45:35', '2018-08-29 05:45:35'),
(1073, 151, 49, '2018-08-29 05:45:35', '2018-08-29 05:45:35'),
(1074, 151, 50, '2018-08-29 05:45:35', '2018-08-29 05:45:35'),
(1075, 151, 51, '2018-08-29 05:45:35', '2018-08-29 05:45:35'),
(1076, 151, 52, '2018-08-29 05:45:35', '2018-08-29 05:45:35'),
(1082, 152, 40, '2018-08-29 00:15:51', '2018-08-29 00:15:51'),
(1083, 152, 41, '2018-08-29 00:15:51', '2018-08-29 00:15:51'),
(1084, 152, 43, '2018-08-29 00:15:51', '2018-08-29 00:15:51'),
(1085, 152, 44, '2018-08-29 00:15:51', '2018-08-29 00:15:51'),
(1086, 152, 45, '2018-08-29 00:15:51', '2018-08-29 00:15:51'),
(1087, 150, 31, '2018-08-29 01:22:03', '2018-08-29 01:22:03'),
(1088, 150, 29, '2018-08-29 01:22:03', '2018-08-29 01:22:03'),
(1089, 150, 27, '2018-08-29 01:22:03', '2018-08-29 01:22:03'),
(1090, 150, 25, '2018-08-29 01:22:03', '2018-08-29 01:22:03'),
(1091, 150, 23, '2018-08-29 01:22:03', '2018-08-29 01:22:03'),
(1092, 150, 24, '2018-08-29 01:22:03', '2018-08-29 01:22:03'),
(1107, 155, 48, '2018-08-29 03:28:39', '2018-08-29 03:28:39'),
(1108, 155, 49, '2018-08-29 03:28:39', '2018-08-29 03:28:39'),
(1109, 155, 50, '2018-08-29 03:28:39', '2018-08-29 03:28:39'),
(1110, 155, 51, '2018-08-29 03:28:39', '2018-08-29 03:28:39'),
(1111, 155, 52, '2018-08-29 03:28:39', '2018-08-29 03:28:39'),
(1121, 157, 40, '2018-08-29 04:22:01', '2018-08-29 04:22:01'),
(1122, 157, 41, '2018-08-29 04:22:01', '2018-08-29 04:22:01'),
(1123, 157, 43, '2018-08-29 04:22:01', '2018-08-29 04:22:01'),
(1124, 157, 45, '2018-08-29 04:22:01', '2018-08-29 04:22:01'),
(1125, 157, 49, '2018-08-29 04:22:01', '2018-08-29 04:22:01'),
(1126, 157, 50, '2018-08-29 04:22:01', '2018-08-29 04:22:01'),
(1127, 157, 51, '2018-08-29 04:22:01', '2018-08-29 04:22:01'),
(1128, 157, 52, '2018-08-29 04:22:01', '2018-08-29 04:22:01'),
(1130, 158, 11, '2018-08-29 04:32:19', '2018-08-29 04:32:19'),
(1135, 154, 55, '2018-08-30 01:28:19', '2018-08-30 01:28:19'),
(1136, 154, 60, '2018-08-30 01:28:19', '2018-08-30 01:28:19'),
(1137, 154, 61, '2018-08-30 01:28:19', '2018-08-30 01:28:19'),
(1138, 154, 62, '2018-08-30 01:28:19', '2018-08-30 01:28:19'),
(1139, 154, 66, '2018-08-30 01:28:19', '2018-08-30 01:28:19'),
(1154, 159, 45, '2018-08-30 04:15:01', '2018-08-30 04:15:01'),
(1155, 159, 47, '2018-08-30 04:15:01', '2018-08-30 04:15:01'),
(1156, 159, 51, '2018-08-30 04:15:01', '2018-08-30 04:15:01'),
(1157, 159, 52, '2018-08-30 04:15:01', '2018-08-30 04:15:01'),
(1171, 161, 62, '2018-08-30 06:33:17', '2018-08-30 06:33:17'),
(1172, 161, 63, '2018-08-30 06:33:17', '2018-08-30 06:33:17'),
(1173, 161, 65, '2018-08-30 06:33:17', '2018-08-30 06:33:17'),
(1174, 162, 61, '2018-08-30 07:35:42', '2018-08-30 07:35:42'),
(1175, 162, 62, '2018-08-30 07:35:42', '2018-08-30 07:35:42'),
(1181, 160, 54, '2018-08-30 07:39:50', '2018-08-30 07:39:50'),
(1182, 160, 56, '2018-08-30 07:39:50', '2018-08-30 07:39:50'),
(1183, 160, 60, '2018-08-30 07:39:50', '2018-08-30 07:39:50'),
(1184, 160, 61, '2018-08-30 07:39:50', '2018-08-30 07:39:50'),
(1185, 160, 62, '2018-08-30 07:39:50', '2018-08-30 07:39:50'),
(1192, 165, 11, '2018-09-03 05:37:37', '2018-09-03 05:37:37'),
(1193, 163, 55, '2018-09-03 06:28:16', '2018-09-03 06:28:16'),
(1194, 163, 56, '2018-09-03 06:28:16', '2018-09-03 06:28:16'),
(1195, 163, 57, '2018-09-03 06:28:16', '2018-09-03 06:28:16'),
(1196, 163, 58, '2018-09-03 06:28:16', '2018-09-03 06:28:16'),
(1197, 164, 40, '2018-09-03 10:07:38', '2018-09-03 10:07:38'),
(1198, 164, 41, '2018-09-03 10:07:38', '2018-09-03 10:07:38'),
(1199, 164, 42, '2018-09-03 10:07:38', '2018-09-03 10:07:38'),
(1200, 164, 43, '2018-09-03 10:07:38', '2018-09-03 10:07:38'),
(1211, 166, 55, '2018-09-03 10:37:41', '2018-09-03 10:37:41'),
(1212, 166, 56, '2018-09-03 10:37:41', '2018-09-03 10:37:41'),
(1213, 166, 58, '2018-09-03 10:37:41', '2018-09-03 10:37:41'),
(1214, 166, 62, '2018-09-03 10:37:41', '2018-09-03 10:37:41'),
(1215, 166, 64, '2018-09-03 10:37:41', '2018-09-03 10:37:41'),
(1224, 153, 53, '2018-09-03 13:49:21', '2018-09-03 13:49:21'),
(1225, 153, 59, '2018-09-03 13:49:21', '2018-09-03 13:49:21'),
(1226, 153, 60, '2018-09-03 13:49:21', '2018-09-03 13:49:21'),
(1227, 153, 61, '2018-09-03 13:49:21', '2018-09-03 13:49:21'),
(1228, 153, 62, '2018-09-03 13:49:21', '2018-09-03 13:49:21'),
(1229, 153, 63, '2018-09-03 13:49:21', '2018-09-03 13:49:21'),
(1230, 153, 66, '2018-09-03 13:49:21', '2018-09-03 13:49:21'),
(1231, 153, 70, '2018-09-03 13:49:21', '2018-09-03 13:49:21'),
(1232, 169, 40, '2018-09-04 15:24:20', '2018-09-04 15:24:20'),
(1233, 169, 41, '2018-09-04 15:24:20', '2018-09-04 15:24:20'),
(1234, 169, 42, '2018-09-04 15:24:20', '2018-09-04 15:24:20'),
(1235, 169, 43, '2018-09-04 15:24:20', '2018-09-04 15:24:20'),
(1236, 169, 44, '2018-09-04 15:24:20', '2018-09-04 15:24:20'),
(1237, 169, 45, '2018-09-04 15:24:20', '2018-09-04 15:24:20'),
(1238, 169, 46, '2018-09-04 15:24:20', '2018-09-04 15:24:20'),
(1239, 169, 47, '2018-09-04 15:24:20', '2018-09-04 15:24:20'),
(1240, 169, 48, '2018-09-04 15:24:20', '2018-09-04 15:24:20'),
(1241, 169, 49, '2018-09-04 15:24:20', '2018-09-04 15:24:20'),
(1242, 169, 50, '2018-09-04 15:24:20', '2018-09-04 15:24:20'),
(1243, 169, 51, '2018-09-04 15:24:20', '2018-09-04 15:24:20'),
(1244, 169, 52, '2018-09-04 15:24:20', '2018-09-04 15:24:20'),
(1249, 172, 17, '2018-09-07 04:53:07', '2018-09-07 04:53:07'),
(1250, 172, 20, '2018-09-07 04:53:07', '2018-09-07 04:53:07'),
(1251, 172, 25, '2018-09-07 04:53:07', '2018-09-07 04:53:07'),
(1252, 172, 27, '2018-09-07 04:53:07', '2018-09-07 04:53:07'),
(1253, 172, 28, '2018-09-07 04:53:07', '2018-09-07 04:53:07'),
(1258, 173, 32, '2018-09-07 05:28:15', '2018-09-07 05:28:15'),
(1259, 173, 34, '2018-09-07 05:28:15', '2018-09-07 05:28:15'),
(1260, 173, 35, '2018-09-07 05:28:15', '2018-09-07 05:28:15'),
(1261, 176, 11, '2018-09-07 06:41:40', '2018-09-07 06:41:40'),
(1262, 177, 40, '2018-09-07 07:00:23', '2018-09-07 07:00:23'),
(1263, 177, 43, '2018-09-07 07:00:23', '2018-09-07 07:00:23'),
(1264, 178, 17, '2018-09-07 07:11:23', '2018-09-07 07:11:23'),
(1265, 178, 19, '2018-09-07 07:11:23', '2018-09-07 07:11:23'),
(1266, 178, 23, '2018-09-07 07:11:23', '2018-09-07 07:11:23'),
(1269, 181, 22, '2018-09-07 08:59:48', '2018-09-07 08:59:48'),
(1270, 181, 23, '2018-09-07 08:59:48', '2018-09-07 08:59:48'),
(1272, 185, 11, '2018-09-07 10:49:40', '2018-09-07 10:49:40'),
(1273, 185, 11, '2018-09-07 10:49:40', '2018-09-07 10:49:40'),
(1298, 187, 40, '2018-09-11 04:33:37', '2018-09-11 04:33:37'),
(1299, 187, 43, '2018-09-11 04:33:37', '2018-09-11 04:33:37'),
(1300, 187, 44, '2018-09-11 04:33:37', '2018-09-11 04:33:37'),
(1301, 187, 48, '2018-09-11 04:33:37', '2018-09-11 04:33:37'),
(1302, 189, 40, '2018-09-11 04:35:21', '2018-09-11 04:35:21'),
(1303, 189, 47, '2018-09-11 04:35:21', '2018-09-11 04:35:21'),
(1304, 189, 52, '2018-09-11 04:35:21', '2018-09-11 04:35:21'),
(1311, 186, 17, '2018-09-11 10:17:50', '2018-09-11 10:17:50'),
(1312, 186, 21, '2018-09-11 10:17:50', '2018-09-11 10:17:50'),
(1313, 186, 22, '2018-09-11 10:17:50', '2018-09-11 10:17:50'),
(1314, 186, 25, '2018-09-11 10:17:50', '2018-09-11 10:17:50'),
(1315, 186, 26, '2018-09-11 10:17:50', '2018-09-11 10:17:50'),
(1316, 186, 29, '2018-09-11 10:17:50', '2018-09-11 10:17:50'),
(1367, 191, 17, '2018-09-11 12:54:36', '2018-09-11 12:54:36'),
(1368, 191, 21, '2018-09-11 12:54:36', '2018-09-11 12:54:36'),
(1369, 191, 22, '2018-09-11 12:54:36', '2018-09-11 12:54:36'),
(1370, 191, 26, '2018-09-11 12:54:36', '2018-09-11 12:54:36'),
(1371, 190, 17, '2018-09-12 06:01:48', '2018-09-12 06:01:48'),
(1372, 190, 19, '2018-09-12 06:01:48', '2018-09-12 06:01:48'),
(1373, 190, 20, '2018-09-12 06:01:48', '2018-09-12 06:01:48'),
(1374, 190, 21, '2018-09-12 06:01:48', '2018-09-12 06:01:48'),
(1375, 190, 22, '2018-09-12 06:01:48', '2018-09-12 06:01:48'),
(1376, 190, 23, '2018-09-12 06:01:48', '2018-09-12 06:01:48'),
(1377, 190, 24, '2018-09-12 06:01:48', '2018-09-12 06:01:48'),
(1378, 190, 25, '2018-09-12 06:01:48', '2018-09-12 06:01:48'),
(1379, 192, 17, '2018-09-12 10:51:15', '2018-09-12 10:51:15'),
(1380, 192, 21, '2018-09-12 10:51:15', '2018-09-12 10:51:15'),
(1385, 188, 40, '2018-09-17 11:08:58', '2018-09-17 11:08:58'),
(1386, 188, 42, '2018-09-17 11:08:58', '2018-09-17 11:08:58'),
(1387, 193, 17, '2018-09-18 09:14:29', '2018-09-18 09:14:29'),
(1388, 193, 20, '2018-09-18 09:14:30', '2018-09-18 09:14:30'),
(1389, 174, 11, '2018-09-18 13:19:58', '2018-09-18 13:19:58'),
(1390, 171, 53, '2018-09-18 13:24:00', '2018-09-18 13:24:00'),
(1391, 171, 54, '2018-09-18 13:24:00', '2018-09-18 13:24:00'),
(1392, 171, 55, '2018-09-18 13:24:00', '2018-09-18 13:24:00'),
(1393, 171, 56, '2018-09-18 13:24:00', '2018-09-18 13:24:00'),
(1394, 194, 17, '2018-09-21 08:53:34', '2018-09-21 08:53:34'),
(1395, 194, 19, '2018-09-21 08:53:34', '2018-09-21 08:53:34'),
(1396, 194, 20, '2018-09-21 08:53:34', '2018-09-21 08:53:34'),
(1397, 194, 21, '2018-09-21 08:53:34', '2018-09-21 08:53:34'),
(1398, 194, 22, '2018-09-21 08:53:34', '2018-09-21 08:53:34'),
(1399, 194, 23, '2018-09-21 08:53:34', '2018-09-21 08:53:34'),
(1400, 194, 24, '2018-09-21 08:53:34', '2018-09-21 08:53:34'),
(1401, 194, 25, '2018-09-21 08:53:35', '2018-09-21 08:53:35'),
(1402, 194, 26, '2018-09-21 08:53:35', '2018-09-21 08:53:35'),
(1403, 194, 27, '2018-09-21 08:53:35', '2018-09-21 08:53:35'),
(1404, 194, 28, '2018-09-21 08:53:35', '2018-09-21 08:53:35'),
(1405, 194, 29, '2018-09-21 08:53:35', '2018-09-21 08:53:35'),
(1406, 194, 30, '2018-09-21 08:53:35', '2018-09-21 08:53:35'),
(1407, 194, 31, '2018-09-21 08:53:35', '2018-09-21 08:53:35'),
(1408, 195, 17, '2018-09-21 08:55:55', '2018-09-21 08:55:55'),
(1409, 195, 19, '2018-09-21 08:55:55', '2018-09-21 08:55:55'),
(1410, 196, 40, '2018-09-21 08:58:41', '2018-09-21 08:58:41'),
(1411, 196, 41, '2018-09-21 08:58:41', '2018-09-21 08:58:41'),
(1412, 196, 42, '2018-09-21 08:58:41', '2018-09-21 08:58:41'),
(1413, 196, 43, '2018-09-21 08:58:41', '2018-09-21 08:58:41'),
(1414, 197, 53, '2018-09-21 09:00:32', '2018-09-21 09:00:32'),
(1415, 197, 55, '2018-09-21 09:00:32', '2018-09-21 09:00:32'),
(1416, 197, 57, '2018-09-21 09:00:32', '2018-09-21 09:00:32'),
(1417, 197, 58, '2018-09-21 09:00:33', '2018-09-21 09:00:33'),
(1418, 197, 59, '2018-09-21 09:00:33', '2018-09-21 09:00:33'),
(1419, 197, 60, '2018-09-21 09:00:33', '2018-09-21 09:00:33'),
(1420, 199, 17, '2018-09-29 05:27:55', '2018-09-29 05:27:55'),
(1421, 199, 20, '2018-09-29 05:27:55', '2018-09-29 05:27:55'),
(1422, 200, 40, '2018-10-22 13:08:56', '2018-10-22 13:08:56'),
(1423, 201, 53, '2018-10-23 09:03:10', '2018-10-23 09:03:10'),
(1424, 201, 54, '2018-10-23 09:03:10', '2018-10-23 09:03:10'),
(1425, 201, 55, '2018-10-23 09:03:10', '2018-10-23 09:03:10'),
(1426, 201, 56, '2018-10-23 09:03:10', '2018-10-23 09:03:10'),
(1427, 201, 60, '2018-10-23 09:03:10', '2018-10-23 09:03:10'),
(1428, 201, 61, '2018-10-23 09:03:10', '2018-10-23 09:03:10'),
(1429, 201, 66, '2018-10-23 09:03:10', '2018-10-23 09:03:10'),
(1430, 201, 69, '2018-10-23 09:03:10', '2018-10-23 09:03:10'),
(1431, 201, 70, '2018-10-23 09:03:10', '2018-10-23 09:03:10'),
(1435, 202, 53, '2018-10-23 10:07:45', '2018-10-23 10:07:45'),
(1436, 202, 55, '2018-10-23 10:07:45', '2018-10-23 10:07:45'),
(1437, 202, 57, '2018-10-23 10:07:45', '2018-10-23 10:07:45'),
(1438, 203, 61, '2018-10-25 04:50:56', '2018-10-25 04:50:56'),
(1439, 203, 62, '2018-10-25 04:50:56', '2018-10-25 04:50:56'),
(1440, 203, 64, '2018-10-25 04:50:56', '2018-10-25 04:50:56'),
(1441, 203, 66, '2018-10-25 04:50:56', '2018-10-25 04:50:56'),
(1442, 203, 68, '2018-10-25 04:50:56', '2018-10-25 04:50:56'),
(1443, 204, 53, '2018-10-25 13:35:59', '2018-10-25 13:35:59'),
(1444, 204, 54, '2018-10-25 13:35:59', '2018-10-25 13:35:59'),
(1445, 204, 55, '2018-10-25 13:35:59', '2018-10-25 13:35:59'),
(1446, 204, 56, '2018-10-25 13:35:59', '2018-10-25 13:35:59'),
(1453, 205, 53, '2018-10-30 04:38:48', '2018-10-30 04:38:48'),
(1454, 205, 54, '2018-10-30 04:38:48', '2018-10-30 04:38:48'),
(1455, 205, 55, '2018-10-30 04:38:48', '2018-10-30 04:38:48'),
(1456, 205, 56, '2018-10-30 04:38:48', '2018-10-30 04:38:48'),
(1457, 205, 57, '2018-10-30 04:38:48', '2018-10-30 04:38:48'),
(1458, 205, 58, '2018-10-30 04:38:48', '2018-10-30 04:38:48'),
(1460, 206, 22, '2018-12-12 13:19:12', '2018-12-12 13:19:12');

-- --------------------------------------------------------

--
-- Table structure for table `property_beds_arrangment`
--

CREATE TABLE `property_beds_arrangment` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `no_of_bedrooms` int(11) NOT NULL,
  `double_bed` int(11) NOT NULL,
  `single_bed` int(11) NOT NULL,
  `queen_bed` int(11) NOT NULL,
  `sofa_bed` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `property_beds_arrangment`
--

INSERT INTO `property_beds_arrangment` (`id`, `property_id`, `no_of_bedrooms`, `double_bed`, `single_bed`, `queen_bed`, `sofa_bed`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, 1, '2018-05-29 11:28:44', '2018-05-29 13:01:40'),
(2, 1, 2, 1, 1, 1, 1, '2018-05-29 11:28:44', '2018-05-29 13:01:40'),
(3, 1, 3, 1, 1, 1, 1, '2018-05-29 11:28:44', '2018-05-29 13:01:40'),
(4, 1, 4, 1, 1, 1, 1, '2018-05-29 11:28:44', '2018-05-29 13:01:40'),
(5, 1, 5, 1, 1, 1, 1, '2018-05-29 11:28:44', '2018-05-29 13:01:40'),
(6, 2, 1, 1, 1, 2, 2, '2018-05-29 11:53:48', '2018-05-29 11:59:31'),
(12, 4, 1, 2, 1, 2, 1, '2018-05-29 13:04:50', '2018-05-29 13:04:50'),
(13, 5, 1, 1, 2, 2, 1, '2018-05-29 13:12:15', '2018-05-29 13:12:15'),
(14, 6, 1, 2, 2, 1, 1, '2018-06-01 02:40:29', '2018-06-01 02:40:29'),
(15, 6, 2, 2, 3, 2, 3, '2018-06-01 02:40:29', '2018-06-01 02:40:29'),
(16, 6, 3, 3, 1, 3, 2, '2018-06-01 02:40:29', '2018-06-01 02:40:29'),
(17, 7, 1, 1, 3, 2, 2, '2018-06-01 02:52:35', '2018-06-01 02:52:35'),
(18, 7, 2, 3, 3, 3, 3, '2018-06-01 02:52:35', '2018-06-01 02:52:35'),
(19, 7, 3, 1, 1, 3, 3, '2018-06-01 02:52:35', '2018-06-01 02:52:35'),
(20, 7, 4, 3, 3, 1, 3, '2018-06-01 02:52:35', '2018-06-01 02:52:35'),
(21, 8, 1, 2, 2, 1, 3, '2018-06-01 03:01:41', '2018-06-01 03:01:41'),
(22, 8, 2, 2, 3, 1, 3, '2018-06-01 03:01:41', '2018-06-01 03:01:41'),
(23, 8, 3, 3, 2, 1, 3, '2018-06-01 03:01:41', '2018-06-01 03:01:41'),
(24, 9, 1, 3, 1, 2, 3, '2018-06-01 03:07:02', '2018-06-01 03:07:02'),
(25, 10, 1, 3, 2, 3, 3, '2018-06-01 03:13:13', '2018-06-01 03:13:13'),
(26, 10, 2, 1, 2, 2, 2, '2018-06-01 03:13:13', '2018-06-01 03:13:13'),
(27, 11, 1, 1, 1, 1, 1, '2018-06-02 04:37:34', '2018-06-02 04:41:06'),
(28, 11, 2, 1, 1, 1, 1, '2018-06-02 04:37:34', '2018-06-02 04:41:06'),
(29, 12, 1, 1, 1, 1, 1, '2018-06-04 03:57:46', '2018-06-04 07:04:41'),
(30, 12, 2, 2, 1, 1, 1, '2018-06-04 03:57:46', '2018-06-04 07:04:41'),
(31, 13, 1, 1, 1, 2, 3, '2018-06-04 05:07:12', '2018-06-04 07:01:14'),
(32, 13, 2, 1, 1, 3, 1, '2018-06-04 05:07:12', '2018-06-04 07:01:14'),
(33, 13, 3, 1, 3, 1, 1, '2018-06-04 05:07:12', '2018-06-04 07:01:14'),
(35, 15, 1, 1, 1, 2, 3, '2018-06-04 07:06:35', '2018-06-04 07:29:48'),
(36, 15, 2, 1, 1, 1, 1, '2018-06-04 07:06:35', '2018-06-04 07:29:48'),
(37, 15, 3, 2, 2, 2, 2, '2018-06-04 07:06:35', '2018-06-04 07:29:48'),
(38, 15, 4, 1, 3, 1, 1, '2018-06-04 07:06:35', '2018-06-04 07:29:48'),
(39, 16, 1, 1, 1, 2, 1, '2018-06-04 08:31:15', '2018-06-04 08:31:15'),
(40, 16, 2, 1, 1, 1, 1, '2018-06-04 08:31:15', '2018-06-04 08:31:15'),
(41, 16, 3, 3, 3, 3, 1, '2018-06-04 08:31:15', '2018-06-04 08:31:15'),
(42, 16, 4, 1, 1, 1, 1, '2018-06-04 08:31:15', '2018-06-04 08:31:15'),
(43, 16, 5, 3, 2, 3, 1, '2018-06-04 08:31:15', '2018-06-04 08:31:15'),
(45, 20, 1, 2, 1, 1, 1, '2018-06-11 00:30:24', '2018-06-11 00:30:24'),
(46, 20, 2, 1, 1, 1, 1, '2018-06-11 00:30:24', '2018-06-11 00:30:24'),
(47, 20, 3, 3, 3, 3, 3, '2018-06-11 00:30:24', '2018-06-11 00:30:24'),
(48, 22, 1, 1, 1, 1, 1, '2018-06-11 00:41:33', '2018-06-11 00:41:33'),
(49, 22, 2, 1, 1, 1, 1, '2018-06-11 00:41:33', '2018-06-11 00:41:33'),
(50, 23, 1, 1, 1, 2, 3, '2018-06-11 01:27:31', '2018-06-11 01:27:31'),
(51, 24, 1, 1, 3, 1, 3, '2018-06-11 02:56:40', '2018-06-11 02:56:40'),
(53, 27, 1, 2, 2, 1, 1, '2018-06-11 03:11:35', '2018-06-11 03:11:35'),
(54, 28, 1, 2, 2, 2, 3, '2018-06-11 04:06:02', '2018-06-11 04:06:02'),
(103, 49, 1, 2, 2, 1, 2, '2018-07-07 06:42:09', '2018-07-07 06:42:09'),
(104, 49, 2, 3, 1, 3, 1, '2018-07-07 06:42:09', '2018-07-07 06:42:09'),
(114, 51, 1, 2, 2, 1, 2, '2018-07-07 07:26:26', '2018-07-07 07:26:26'),
(115, 51, 2, 3, 1, 3, 1, '2018-07-07 07:26:26', '2018-07-07 07:26:26'),
(126, 47, 1, 2, 2, 1, 2, '2018-07-08 22:41:53', '2018-07-08 22:41:53'),
(127, 47, 2, 3, 1, 3, 1, '2018-07-08 22:41:53', '2018-07-08 22:41:53'),
(128, 48, 1, 2, 2, 1, 2, '2018-07-08 22:42:13', '2018-07-08 22:42:13'),
(129, 48, 2, 3, 1, 3, 1, '2018-07-08 22:42:13', '2018-07-08 22:42:13'),
(275, 58, 1, 1, 3, 3, 1, '2018-07-09 23:40:14', '2018-07-09 23:40:14'),
(336, 55, 1, 1, 1, 3, 1, '2018-07-10 06:32:26', '2018-07-10 06:32:26'),
(337, 55, 2, 1, 1, 1, 1, '2018-07-10 06:32:26', '2018-07-10 06:32:26'),
(344, 63, 1, 3, 2, 3, 2, '2018-07-12 00:13:19', '2018-07-12 00:13:19'),
(347, 65, 1, 1, 1, 2, 2, '2018-07-12 08:34:47', '2018-07-12 08:34:47'),
(348, 65, 2, 3, 3, 2, 3, '2018-07-12 08:34:47', '2018-07-12 08:34:47'),
(354, 66, 1, 1, 3, 3, 1, '2018-07-13 04:40:59', '2018-07-13 04:40:59'),
(355, 66, 2, 2, 2, 2, 2, '2018-07-13 04:40:59', '2018-07-13 04:40:59'),
(356, 66, 3, 3, 1, 1, 3, '2018-07-13 04:40:59', '2018-07-13 04:40:59'),
(377, 67, 1, 2, 2, 1, 1, '2018-07-13 04:51:07', '2018-07-13 04:51:07'),
(378, 67, 2, 1, 3, 1, 3, '2018-07-13 04:51:07', '2018-07-13 04:51:07'),
(379, 67, 3, 1, 1, 2, 1, '2018-07-13 04:51:07', '2018-07-13 04:51:07'),
(380, 67, 4, 2, 2, 3, 3, '2018-07-13 04:51:07', '2018-07-13 04:51:07'),
(386, 68, 1, 0, 0, 0, 0, '2018-07-13 05:08:45', '2018-07-13 05:08:45'),
(387, 68, 2, 0, 0, 0, 0, '2018-07-13 05:08:45', '2018-07-13 05:08:45'),
(393, 71, 1, 1, 1, 1, 3, '2018-07-13 05:48:12', '2018-07-13 05:48:12'),
(394, 71, 2, 2, 3, 3, 2, '2018-07-13 05:48:12', '2018-07-13 05:48:12'),
(395, 71, 3, 3, 2, 2, 1, '2018-07-13 05:48:12', '2018-07-13 05:48:12'),
(396, 64, 1, 3, 1, 1, 1, '2018-07-13 05:52:24', '2018-07-13 05:52:24'),
(397, 64, 2, 2, 3, 1, 1, '2018-07-13 05:52:24', '2018-07-13 05:52:24'),
(413, 77, 1, 0, 0, 0, 0, '2018-07-13 08:57:36', '2018-07-13 08:57:36'),
(414, 70, 1, 0, 0, 0, 0, '2018-07-13 09:00:11', '2018-07-13 09:00:11'),
(415, 78, 1, 2, 2, 1, 1, '2018-07-13 11:34:15', '2018-07-13 11:34:15'),
(416, 78, 2, 2, 1, 1, 2, '2018-07-13 11:34:15', '2018-07-13 11:34:15'),
(417, 78, 3, 1, 2, 2, 1, '2018-07-13 11:34:15', '2018-07-13 11:34:15'),
(418, 79, 1, 1, 1, 2, 3, '2018-07-16 07:15:01', '2018-07-16 07:15:01'),
(419, 79, 2, 2, 3, 1, 1, '2018-07-16 07:15:01', '2018-07-16 07:15:01'),
(424, 35, 1, 0, 0, 0, 0, '2018-07-16 11:22:11', '2018-07-16 11:22:11'),
(425, 35, 2, 0, 0, 0, 0, '2018-07-16 11:22:11', '2018-07-16 11:22:11'),
(428, 29, 1, 0, 0, 0, 0, '2018-07-16 11:23:52', '2018-07-16 11:23:52'),
(429, 29, 2, 0, 0, 0, 0, '2018-07-16 11:23:52', '2018-07-16 11:23:52'),
(436, 14, 1, 1, 2, 2, 1, '2018-07-16 11:31:37', '2018-07-16 11:31:37'),
(447, 36, 1, 2, 3, 1, 2, '2018-07-17 05:13:48', '2018-07-17 05:13:48'),
(450, 80, 1, 0, 0, 0, 0, '2018-07-17 08:56:34', '2018-07-17 08:56:34'),
(451, 80, 2, 0, 0, 0, 0, '2018-07-17 08:56:34', '2018-07-17 08:56:34'),
(496, 81, 1, 2, 1, 2, 1, '2018-07-18 13:33:02', '2018-07-18 13:33:02'),
(497, 81, 2, 1, 1, 3, 1, '2018-07-18 13:33:02', '2018-07-18 13:33:02'),
(537, 82, 1, 2, 2, 1, 1, '2018-07-18 13:44:04', '2018-07-18 13:44:04'),
(538, 82, 2, 1, 3, 1, 3, '2018-07-18 13:44:04', '2018-07-18 13:44:04'),
(539, 82, 3, 1, 1, 1, 1, '2018-07-18 13:44:04', '2018-07-18 13:44:04'),
(540, 83, 1, 1, 1, 3, 2, '2018-07-18 13:46:43', '2018-07-18 13:46:43'),
(541, 83, 2, 3, 2, 1, 1, '2018-07-18 13:46:43', '2018-07-18 13:46:43'),
(568, 85, 1, 1, 2, 1, 2, '2018-07-19 09:36:10', '2018-07-19 09:36:10'),
(569, 87, 1, 2, 1, 2, 1, '2018-07-19 12:30:33', '2018-07-19 12:30:33'),
(570, 88, 1, 1, 1, 2, 3, '2018-07-19 15:02:56', '2018-07-19 15:02:56'),
(571, 89, 1, 0, 0, 0, 0, '2018-07-19 15:10:00', '2018-07-19 15:10:00'),
(574, 92, 1, 1, 2, 2, 2, '2018-07-20 13:04:33', '2018-07-20 13:04:33'),
(579, 90, 1, 1, 2, 3, 1, '2018-07-20 14:40:34', '2018-07-20 14:40:34'),
(604, 99, 1, 0, 0, 0, 0, '2018-07-21 15:20:52', '2018-07-21 15:20:52'),
(607, 100, 1, 3, 1, 2, 3, '2018-07-21 17:21:55', '2018-07-21 17:21:55'),
(608, 100, 2, 2, 1, 3, 2, '2018-07-21 17:21:55', '2018-07-21 17:21:55'),
(610, 102, 1, 1, 2, 1, 2, '2018-07-23 08:27:51', '2018-07-23 08:27:51'),
(611, 103, 1, 2, 2, 3, 2, '2018-07-23 09:05:13', '2018-07-23 09:05:13'),
(614, 106, 1, 2, 1, 3, 1, '2018-07-23 14:02:14', '2018-07-23 14:02:14'),
(628, 109, 1, 2, 1, 1, 1, '2018-07-23 16:28:48', '2018-07-23 16:28:48'),
(641, 111, 1, 1, 3, 2, 3, '2018-07-24 16:11:21', '2018-07-24 16:11:21'),
(645, 112, 1, 1, 2, 1, 3, '2018-07-24 16:35:21', '2018-07-24 16:35:21'),
(652, 61, 1, 0, 0, 0, 0, '2018-07-24 16:50:10', '2018-07-24 16:50:10'),
(653, 61, 2, 0, 0, 0, 0, '2018-07-24 16:50:10', '2018-07-24 16:50:10'),
(654, 61, 3, 0, 0, 0, 0, '2018-07-24 16:50:10', '2018-07-24 16:50:10'),
(656, 113, 1, 0, 0, 0, 0, '2018-07-24 17:02:35', '2018-07-24 17:02:35'),
(657, 114, 1, 1, 1, 3, 2, '2018-07-25 13:47:47', '2018-07-25 13:47:47'),
(665, 116, 1, 1, 1, 1, 1, '2018-07-30 09:42:29', '2018-07-30 09:42:29'),
(666, 116, 2, 3, 3, 2, 1, '2018-07-30 09:42:29', '2018-07-30 09:42:29'),
(676, 122, 1, 1, 1, 1, 1, '2018-07-31 07:09:42', '2018-07-31 07:09:42'),
(677, 122, 2, 3, 3, 0, 0, '2018-07-31 07:09:42', '2018-07-31 07:09:42'),
(678, 122, 3, 0, 0, 0, 0, '2018-07-31 07:09:42', '2018-07-31 07:09:42'),
(679, 122, 4, 0, 0, 0, 0, '2018-07-31 07:09:42', '2018-07-31 07:09:42'),
(680, 122, 5, 0, 0, 0, 0, '2018-07-31 07:09:42', '2018-07-31 07:09:42'),
(681, 122, 6, 0, 0, 0, 0, '2018-07-31 07:09:42', '2018-07-31 07:09:42'),
(682, 122, 7, 0, 0, 0, 0, '2018-07-31 07:09:42', '2018-07-31 07:09:42'),
(683, 122, 8, 0, 0, 0, 0, '2018-07-31 07:09:42', '2018-07-31 07:09:42'),
(684, 122, 9, 0, 0, 0, 0, '2018-07-31 07:09:42', '2018-07-31 07:09:42'),
(685, 122, 10, 0, 0, 0, 0, '2018-07-31 07:09:42', '2018-07-31 07:09:42'),
(699, 124, 1, 3, 2, 1, 2, '2018-07-31 11:47:18', '2018-07-31 11:47:18'),
(700, 124, 2, 2, 1, 2, 1, '2018-07-31 11:47:18', '2018-07-31 11:47:18'),
(701, 124, 3, 1, 2, 1, 3, '2018-07-31 11:47:18', '2018-07-31 11:47:18'),
(704, 125, 1, 1, 0, 0, 0, '2018-08-01 09:41:22', '2018-08-01 09:41:22'),
(705, 125, 2, 0, 0, 1, 0, '2018-08-01 09:41:22', '2018-08-01 09:41:22'),
(706, 125, 3, 0, 0, 1, 0, '2018-08-01 09:41:22', '2018-08-01 09:41:22'),
(707, 118, 1, 0, 0, 0, 0, '2018-08-02 09:00:51', '2018-08-02 09:00:51'),
(708, 117, 1, 1, 1, 2, 3, '2018-08-02 09:01:24', '2018-08-02 09:01:24'),
(709, 117, 2, 1, 2, 3, 1, '2018-08-02 09:01:24', '2018-08-02 09:01:24'),
(710, 115, 1, 1, 2, 3, 1, '2018-08-02 09:02:06', '2018-08-02 09:02:06'),
(714, 139, 1, 2, 1, 3, 2, '2018-08-24 10:27:47', '2018-08-24 10:27:47'),
(728, 127, 1, 0, 1, 2, 3, '2018-08-24 12:19:46', '2018-08-24 12:19:46'),
(741, 41, 1, 0, 0, 0, 0, '2018-08-27 23:17:12', '2018-08-27 23:17:12'),
(742, 50, 1, 2, 3, 3, 3, '2018-08-28 23:44:02', '2018-08-28 23:44:02'),
(743, 50, 2, 3, 2, 2, 1, '2018-08-28 23:44:02', '2018-08-28 23:44:02'),
(746, 150, 1, 1, 2, 3, 1, '2018-08-29 01:20:37', '2018-08-29 01:20:37'),
(747, 150, 2, 1, 1, 2, 3, '2018-08-29 01:20:37', '2018-08-29 01:20:37'),
(751, 158, 1, 1, 2, 1, 1, '2018-08-30 00:54:52', '2018-08-30 00:54:52'),
(752, 172, 1, 1, 1, 1, 1, '2018-09-06 14:34:12', '2018-09-07 05:29:09'),
(753, 172, 2, 1, 1, 1, 1, '2018-09-06 14:34:13', '2018-09-07 05:29:09'),
(754, 172, 3, 1, 1, 1, 1, '2018-09-06 14:34:13', '2018-09-07 05:29:09'),
(755, 172, 4, 1, 1, 1, 1, '2018-09-06 14:34:13', '2018-09-07 05:29:09'),
(756, 173, 1, 1, 1, 1, 1, '2018-09-07 05:00:38', '2018-09-07 05:09:32'),
(757, 173, 2, 1, 1, 1, 1, '2018-09-07 05:00:38', '2018-09-07 05:09:32'),
(758, 173, 3, 1, 1, 1, 1, '2018-09-07 05:00:38', '2018-09-07 05:09:32'),
(759, 173, 4, 1, 1, 1, 1, '2018-09-07 05:00:38', '2018-09-07 05:09:32'),
(760, 173, 5, 1, 1, 1, 1, '2018-09-07 05:00:38', '2018-09-07 05:09:33'),
(763, 175, 1, 1, 1, 2, 1, '2018-09-07 05:48:22', '2018-09-07 05:48:22'),
(764, 176, 1, 1, 0, 2, 3, '2018-09-07 06:41:06', '2018-09-07 06:41:06'),
(765, 176, 2, 1, 2, 2, 0, '2018-09-07 06:41:06', '2018-09-07 06:41:06'),
(766, 177, 1, 1, 2, 3, 1, '2018-09-07 06:56:00', '2018-09-07 06:58:42'),
(767, 177, 2, 1, 1, 1, 1, '2018-09-07 06:56:00', '2018-09-07 06:58:42'),
(768, 178, 1, 1, 2, 1, 2, '2018-09-07 07:08:20', '2018-09-07 07:11:49'),
(769, 179, 1, 1, 2, 3, 1, '2018-09-07 07:13:55', '2018-09-07 07:15:24'),
(770, 180, 1, 1, 2, 1, 1, '2018-09-07 07:17:58', '2018-09-07 07:22:57'),
(771, 181, 1, 1, 1, 1, 1, '2018-09-07 07:30:29', '2018-09-07 09:00:03'),
(772, 182, 1, 1, 2, 2, 1, '2018-09-07 09:04:36', '2018-09-07 09:06:09'),
(773, 183, 1, 1, 2, 1, 1, '2018-09-07 09:11:47', '2018-09-07 10:03:28'),
(774, 184, 1, 2, 1, 1, 1, '2018-09-07 10:37:52', '2018-09-07 10:44:13'),
(775, 185, 1, 1, 2, 1, 3, '2018-09-07 10:45:56', '2018-09-07 10:49:58'),
(777, 190, 1, 1, 1, 2, 1, '2018-09-12 06:01:44', '2018-09-12 06:01:44'),
(778, 194, 1, 1, 1, 1, 1, '2018-09-21 08:52:46', '2018-09-21 08:52:46'),
(780, 198, 1, 1, 1, 1, 1, '2018-09-21 11:35:14', '2018-09-21 11:35:14'),
(782, 207, 1, 1, 1, 1, 1, '2018-12-12 13:20:09', '2018-12-12 13:20:09');

-- --------------------------------------------------------

--
-- Table structure for table `property_images`
--

CREATE TABLE `property_images` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `image` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `property_images`
--

INSERT INTO `property_images` (`id`, `property_id`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, 'e2ac57da37a340975a3f6d96600bec86ddd2652b.jpeg', '2018-05-29 11:33:52', '2018-05-29 11:33:52'),
(2, 1, '4912a7162bfd10cf0b95fe28d4a7710171457c91.jpeg', '2018-05-29 11:33:52', '2018-05-29 11:33:52'),
(4, 1, '1b4661f53f2f1b80c61e17ce0693dfd78c5ca08d.jpg', '2018-05-29 11:34:55', '2018-05-29 11:34:55'),
(5, 1, 'af10f75c13f1c358d72528ffaa2300adf210ee86.jpeg', '2018-05-29 11:34:55', '2018-05-29 11:34:55'),
(6, 1, '3ba8eee4f5127f396a52bf6b80f15ff69578df52.jpeg', '2018-05-29 11:34:55', '2018-05-29 11:34:55'),
(7, 2, '164d7b1b52c87fc9ad76bc9a6dace7856385d1e9.jpg', '2018-05-29 11:59:15', '2018-05-29 11:59:15'),
(8, 2, '4db7c33f12a940f5eaccf92291bd1bb8663c8c81.jpg', '2018-05-29 11:59:15', '2018-05-29 11:59:15'),
(9, 2, '568557acc1bbdbf2ab44ad48dea45147c88a7b59.jpg', '2018-05-29 11:59:15', '2018-05-29 11:59:15'),
(13, 4, 'f5a8686d9f5f93190e2e47e8b97a18037a43b84c.png', '2018-05-29 13:05:12', '2018-05-29 13:05:12'),
(14, 4, 'f9a8b57c5fa4b3236d96f83830ae17917ba0fde4.png', '2018-05-29 13:05:12', '2018-05-29 13:05:12'),
(15, 4, '0530ceebb22d2fa3c2bd2bf174b51ddbf414770d.png', '2018-05-29 13:05:12', '2018-05-29 13:05:12'),
(16, 5, '92cadae443b72e01b0bb171147a401446893c247.jpg', '2018-05-29 13:13:15', '2018-05-29 13:13:15'),
(17, 5, '192db856601387a6e20bafaef89cf118bb134582.jpg', '2018-05-29 13:13:15', '2018-05-29 13:13:15'),
(18, 5, 'a725cfa4f291cd710bf3e8e285085a180540abdc.jpg', '2018-05-29 13:13:15', '2018-05-29 13:13:15'),
(19, 5, 'ac71ad1d4008907ec4d6027c2229edfba352c6cb.jpg', '2018-05-29 13:13:15', '2018-05-29 13:13:15'),
(20, 6, '1ea002d9fef42e47ee8907db99593dc8015be928.jpeg', '2018-06-01 02:43:04', '2018-06-01 02:43:04'),
(21, 6, '31b1c0bc3dd330dbb86c59352d6797718ea5557e.jpeg', '2018-06-01 02:43:04', '2018-06-01 02:43:04'),
(22, 6, '304ca7ca637b20643b61b7db2097989a464378e7.jpeg', '2018-06-01 02:43:04', '2018-06-01 02:43:04'),
(23, 7, '03747ccc8edb0a4b11832befbb05affa85665d75.jpeg', '2018-06-01 02:57:27', '2018-06-01 02:57:27'),
(24, 7, '18a70a1f42107ea3875698b8e8c1910439e5f36a.jpeg', '2018-06-01 02:57:27', '2018-06-01 02:57:27'),
(25, 7, '19d4eba570a3825f8d6cfc1863dd51acfc7737d1.jpeg', '2018-06-01 02:57:27', '2018-06-01 02:57:27'),
(26, 7, '0719bfcf12d1e2639b4334898747dc571de5e7bb.jpeg', '2018-06-01 02:57:27', '2018-06-01 02:57:27'),
(27, 8, '59aac6afed06ab9a8b3f0a0853af6bdcaf3eda5c.jpeg', '2018-06-01 03:03:45', '2018-06-01 03:03:45'),
(28, 8, '6aa8210a47e5b1706d6bd96d8ebe1f7e25724cf8.jpeg', '2018-06-01 03:03:45', '2018-06-01 03:03:45'),
(29, 8, '271049a16e456ecf9b9bf6870b3f49f9adc75ad7.jpeg', '2018-06-01 03:03:45', '2018-06-01 03:03:45'),
(30, 9, '87857855febb3bbecb51111638c8cb7ad0a6ded0.jpeg', '2018-06-01 03:08:54', '2018-06-01 03:08:54'),
(31, 9, 'd185378a4f087424f44fccd85a99f0f80dd5185c.jpeg', '2018-06-01 03:08:54', '2018-06-01 03:08:54'),
(32, 9, '924effacd6b80a8b75bb959ec3e33a8ac516b720.jpeg', '2018-06-01 03:08:54', '2018-06-01 03:08:54'),
(33, 10, '4cfa224797fae744f6231cb6ea7efed94cf7ee61.jpeg', '2018-06-01 03:15:03', '2018-06-01 03:15:03'),
(34, 10, '02fd17eb55e734053a7dcb0e4622ac49f2a7ccf1.jpeg', '2018-06-01 03:15:03', '2018-06-01 03:15:03'),
(35, 10, 'edcfcb693a5f8905361a4b26e8ef9de34f3b9adb.jpeg', '2018-06-01 03:15:03', '2018-06-01 03:15:03'),
(36, 11, 'b7c277e3f877ab6f52472473913e09e56d84db48.jpg', '2018-06-02 04:40:43', '2018-06-02 04:40:43'),
(37, 11, 'e41c33172b3ec56ee7c4e9059581506e82ac3bd9.jpg', '2018-06-02 04:40:43', '2018-06-02 04:40:43'),
(38, 11, 'c75b2c6f9ae5c6eb36d452107bac0465d75ad66c.jpg', '2018-06-02 04:40:43', '2018-06-02 04:40:43'),
(39, 11, '577ab1464c80f3a94c806ab7d5858b5c61d1b696.jpg', '2018-06-02 04:40:43', '2018-06-02 04:40:43'),
(40, 12, '66ac894c69f3eddf1e32f2875fa3ff0cd376512d.jpg', '2018-06-04 04:59:13', '2018-06-04 04:59:13'),
(41, 12, '2ac6b57ead1620cb66c6d96a9859e9792bda4ee2.jpg', '2018-06-04 04:59:13', '2018-06-04 04:59:13'),
(42, 12, 'c50011b9a093d553228d19ec0e923697a173c7bf.jpg', '2018-06-04 04:59:13', '2018-06-04 04:59:13'),
(43, 13, '0e22d820d2e46b43c00286cee4debb00538245d5.jpg', '2018-06-04 05:09:01', '2018-06-04 05:09:01'),
(44, 13, 'fc7a16a7f5eb7cd429247e432231c012c7fd88c9.jpg', '2018-06-04 05:09:01', '2018-06-04 05:09:01'),
(45, 13, '1048b7050e19628fcc8bfe539ce5ce1b171d532c.jpg', '2018-06-04 05:09:01', '2018-06-04 05:09:01'),
(46, 14, '802426cadcbc41c7cd95a5bd480de7867638ecd3.jpg', '2018-06-04 05:50:56', '2018-06-04 05:50:56'),
(47, 14, '9d154a1374ce38942e22c97c70abffb5d8829b7b.jpg', '2018-06-04 05:50:56', '2018-06-04 05:50:56'),
(48, 14, '46bd678334b0dfad391ebd35380e5f865c42a5c0.jpg', '2018-06-04 05:50:56', '2018-06-04 05:50:56'),
(49, 15, '71fe228b4881d574cb88b10bc405ee073c009bbb.jpg', '2018-06-04 07:08:14', '2018-06-04 07:08:14'),
(50, 15, 'ec9443a4f9dc018897df664037d5a68f32e908c5.jpg', '2018-06-04 07:08:14', '2018-06-04 07:08:14'),
(51, 15, '703e2ee12b8e9011c25f094af4d2283748f1bb99.jpg', '2018-06-04 07:08:14', '2018-06-04 07:08:14'),
(52, 16, 'a8fa8d8c25f25514c28e864e9825d28df64871fc.jpg', '2018-06-04 08:49:02', '2018-06-04 08:49:02'),
(53, 16, 'acbb8d8b3877b71654084a4d8ba574e7d0334337.jpg', '2018-06-04 08:49:02', '2018-06-04 08:49:02'),
(54, 16, 'd3c7a62dd3ee0e78dab51bc54a39a1e5c51f7d14.jpg', '2018-06-04 08:49:02', '2018-06-04 08:49:02'),
(55, 17, '4e8de50cabbbcc11a5cc285abe856483536fb378.jpg', '2018-06-07 09:14:06', '2018-06-07 09:14:06'),
(56, 17, '015dcaef48d57c47bc0570918ba9ea705149c219.jpg', '2018-06-07 09:14:06', '2018-06-07 09:14:06'),
(57, 17, '24120317ac5d273811a13d13aab091127c317dbe.jpg', '2018-06-07 09:14:06', '2018-06-07 09:14:06'),
(58, 17, '8c39b9b6ebfe9cfd949a46356c4ea106e4045095.jpg', '2018-06-07 09:14:06', '2018-06-07 09:14:06'),
(59, 17, '3e2b61aa978203df3a92c74fa46a85b322b39b5f.jpg', '2018-06-07 09:17:24', '2018-06-07 09:17:24'),
(60, 17, 'b0034f84e31bec3a6cbf53ec6fb43823edfab52a.jpg', '2018-06-07 09:17:24', '2018-06-07 09:17:24'),
(61, 17, '5cde46f76d6b8c5e7bc2748da88ea88c13d1effd.jpg', '2018-06-07 09:17:24', '2018-06-07 09:17:24'),
(62, 17, '4651fffe9b1e2e17779230ebc766ea9733da25b8.jpg', '2018-06-07 09:24:23', '2018-06-07 09:24:23'),
(63, 17, '573530d989c0c562a5bac29fbaec4456a476b1f7.jpg', '2018-06-07 09:24:23', '2018-06-07 09:24:23'),
(64, 17, 'c7e4e13773459480c000e253f3d43654bdacc6ea.jpg', '2018-06-07 09:24:23', '2018-06-07 09:24:23'),
(65, 17, '44e43dd1299eb0a159a682c18ea519f3b0974118.jpg', '2018-06-07 09:24:23', '2018-06-07 09:24:23'),
(66, 17, 'ca369c22e9b688f1be91682eada5a9a6570c98f1.jpg', '2018-06-07 09:25:40', '2018-06-07 09:25:40'),
(67, 17, '4dccda334757526c06aaf767ea017f88590ecad3.jpg', '2018-06-07 09:25:40', '2018-06-07 09:25:40'),
(68, 17, '8d7756e7aab3e05a7a84094779aa4c4ed892d6b1.jpg', '2018-06-07 09:25:40', '2018-06-07 09:25:40'),
(69, 17, '6e3d409076b986ecd7712a53203dc62876e8388b.jpg', '2018-06-07 09:25:40', '2018-06-07 09:25:40'),
(70, 17, 'fc282231ccf461c169ec07d74e933756abf1d37a.jpg', '2018-06-07 09:33:54', '2018-06-07 09:33:54'),
(71, 17, '967062d716fd55192417464a77364cf95fc5455b.jpg', '2018-06-07 09:33:54', '2018-06-07 09:33:54'),
(72, 17, '96e7d395a96aa74d35076374fe9fbcfd79626445.jpg', '2018-06-07 09:33:54', '2018-06-07 09:33:54'),
(73, 17, '2203f674853e83ebebff526f266c1f97bf4c1da2.jpg', '2018-06-07 09:33:54', '2018-06-07 09:33:54'),
(74, 18, 'ae653809f6344179d3e669ea800d6604821f31e0.jpg', '2018-06-07 09:48:00', '2018-06-07 09:48:00'),
(75, 18, '9e06b9c31cde57178becd671f5ce45e9d4d02e42.jpg', '2018-06-07 09:48:00', '2018-06-07 09:48:00'),
(76, 18, '1366cd6ab86377daba2b5e8d8ad549ad2c431051.jpg', '2018-06-07 09:48:00', '2018-06-07 09:48:00'),
(77, 18, 'e42d5fa2bd7ef746c4e92b4c1518a202a0eca4b0.jpg', '2018-06-07 09:49:15', '2018-06-07 09:49:15'),
(78, 18, '0a16713cf44cc400a6cf7bed6ee542481849f7b5.jpg', '2018-06-07 09:49:15', '2018-06-07 09:49:15'),
(79, 18, 'bbaf76be751fc2e2f3af002ecdf93d96742ea98e.jpg', '2018-06-07 09:49:15', '2018-06-07 09:49:15'),
(80, 18, 'a8348175ddbc9d7f94087e4d3c95e0ab2560acf4.jpg', '2018-06-07 09:49:15', '2018-06-07 09:49:15'),
(81, 18, '885abeeab6bdb4fd206404a8ad48cee6f9b47f43.jpg', '2018-06-07 09:58:25', '2018-06-07 09:58:25'),
(82, 18, 'b75e47d518f268a99537441082beb7836f2817a1.jpg', '2018-06-07 09:58:25', '2018-06-07 09:58:25'),
(83, 18, '30871a86e99c3d77c17541e0bbe472026a1e7b20.jpg', '2018-06-07 09:58:25', '2018-06-07 09:58:25'),
(84, 18, '06d9c045d2b760d11a0b15f3e1e277b21b7d929b.jpg', '2018-06-07 09:59:49', '2018-06-07 09:59:49'),
(85, 18, '8e87df5a02f5a1b8beb39a9cb747ed576bfd4907.jpg', '2018-06-07 09:59:49', '2018-06-07 09:59:49'),
(86, 18, '354191e5a0cc7bac6f9543169db8eb1410882604.jpg', '2018-06-07 09:59:49', '2018-06-07 09:59:49'),
(93, 20, '8465ac35bb5f33618007165d64726fcbbff2e20e.jpg', '2018-06-11 00:26:47', '2018-06-11 00:26:47'),
(94, 20, '532253a16759625beb8092126c532460ec7d546d.jpg', '2018-06-11 00:26:47', '2018-06-11 00:26:47'),
(95, 20, '875271b1b62922f1109a3dabdb4d6fdf38408a7a.jpg', '2018-06-11 00:26:47', '2018-06-11 00:26:47'),
(96, 20, '8960b4032bac9242c731b5341c06e301163e2096.png', '2018-06-11 00:32:24', '2018-06-11 00:32:24'),
(97, 20, 'b3c04711d9a2c36f096e4af243fb687dfd7d9b0f.png', '2018-06-11 00:32:24', '2018-06-11 00:32:24'),
(98, 20, '8d5d15f2bf7e7da6a5fe9c5b770820e89ab946c5.png', '2018-06-11 00:32:24', '2018-06-11 00:32:24'),
(99, 22, 'fb8498239de33be12996d2fa353e2f2e1b37e602.jpg', '2018-06-11 00:42:30', '2018-06-11 00:42:30'),
(100, 22, 'b9e0c34d4a64caa78968b2e8830fd83ede0dfb97.jpg', '2018-06-11 00:42:30', '2018-06-11 00:42:30'),
(101, 22, 'a2d3f839412aaef2c91a4f09d1e5604917848a52.jpg', '2018-06-11 00:42:30', '2018-06-11 00:42:30'),
(102, 22, '81e8c31d25c16de8025575de1c63fcc99bef0ff6.jpg', '2018-06-11 00:42:30', '2018-06-11 00:42:30'),
(103, 22, '2df44c74c3fd0d7fb5e8cf95c23711f5804603fb.jpg', '2018-06-11 00:42:30', '2018-06-11 00:42:30'),
(104, 21, '1a340a3e68cfb5e41cb0849539931fccdae6b8fc.jpg', '2018-06-11 00:57:22', '2018-06-11 00:57:22'),
(105, 21, '42159a4e2d03a1584fdbf256df4ab78a281e19c8.jpg', '2018-06-11 00:57:22', '2018-06-11 00:57:22'),
(106, 21, '74104ff53817fe6c98fa52e7794bf5719912894a.jpg', '2018-06-11 00:57:22', '2018-06-11 00:57:22'),
(107, 21, '0657fce477c4adfd772526ba14e3fa2705b2c44f.jpg', '2018-06-11 00:57:22', '2018-06-11 00:57:22'),
(108, 23, '9e470469d9e4aa1e0efb8b6c2a10bcce9f8f9d7d.jpg', '2018-06-11 02:55:18', '2018-06-11 02:55:18'),
(109, 23, 'e04b9029206c2d9912a5d445573bcc948b48a74f.jpg', '2018-06-11 02:55:18', '2018-06-11 02:55:18'),
(110, 23, 'a5ee67e6f7301707ecb15b0d6cf05e0a09b6e7a0.jpg', '2018-06-11 02:55:18', '2018-06-11 02:55:18'),
(111, 19, '24f1914cc6a2422569d71683ecb1614c862a2995.jpg', '2018-06-11 02:58:49', '2018-06-11 02:58:49'),
(112, 19, '533d4cffd40260922e7bc4b12ff2b1c9196af25f.jpg', '2018-06-11 02:58:49', '2018-06-11 02:58:49'),
(113, 19, '8c2c5f7ebb74eb232ba17201d8177eef12173a71.jpg', '2018-06-11 02:58:49', '2018-06-11 02:58:49'),
(114, 24, '583ae782937e55420075cf7c0cff1dac1b58b5b0.jpg', '2018-06-11 03:00:51', '2018-06-11 03:00:51'),
(115, 24, '07e5a7ada08cd4010c261f010b751e19619b773b.jpg', '2018-06-11 03:00:51', '2018-06-11 03:00:51'),
(116, 24, 'f7b775f074c5bfecc933d773409f76540a2160b0.jpg', '2018-06-11 03:00:51', '2018-06-11 03:00:51'),
(117, 25, '8b835c4b92615d1f21d7eb2e7014fc199fa2269f.jpg', '2018-06-11 03:01:38', '2018-06-11 03:01:38'),
(118, 25, '67f40f3af76162b8b559335b80209f83ad5c043e.jpg', '2018-06-11 03:01:38', '2018-06-11 03:01:38'),
(119, 25, '3639558eb21b9f704b99a777e20f2abd7f95a748.jpg', '2018-06-11 03:01:38', '2018-06-11 03:01:38'),
(120, 19, 'c91674ffd13ace97979d964b256bf96cf8c3ae3f.jpg', '2018-06-11 03:09:42', '2018-06-11 03:09:42'),
(121, 19, 'dbf605b49d604e0419a30248f0bf5c943ddac20b.jpg', '2018-06-11 03:09:42', '2018-06-11 03:09:42'),
(122, 19, 'bcc7375a2906cb46ae0b693cc8de104ddc7b11d2.jpg', '2018-06-11 03:09:42', '2018-06-11 03:09:42'),
(123, 27, '9161c1b29486fb3d55daa50d34076eea2bb2668c.jpg', '2018-06-11 03:13:30', '2018-06-11 03:13:30'),
(124, 27, '8d6327f68bb9d1dbc62313ec74e4c61468b85db6.jpg', '2018-06-11 03:13:30', '2018-06-11 03:13:30'),
(125, 27, 'fc950762fc7dedde84a568c5e3c760cdd1d8d896.jpg', '2018-06-11 03:13:30', '2018-06-11 03:13:30'),
(126, 27, '9e33f903a13cc30649f3f1c5513e68ad6fb112a5.jpg', '2018-06-11 03:13:30', '2018-06-11 03:13:30'),
(127, 27, 'c35da0862f9b5ea9d28f6fb9eccd51c168201bed.jpg', '2018-06-11 03:13:30', '2018-06-11 03:13:30'),
(128, 27, '1ff0198a93c7f41d35ba790f81d540c1a373192f.jpg', '2018-06-11 03:13:30', '2018-06-11 03:13:30'),
(129, 29, '9e01f43fa8d4010d80a05f535dc7a59ffd8ba0a8.jpg', '2018-06-12 00:56:28', '2018-06-12 00:56:28'),
(130, 29, 'f2e6e3ba0405409017aa0245683484c36d6aa9e8.jpg', '2018-06-12 00:56:28', '2018-06-12 00:56:28'),
(131, 29, 'c04a72b9b04b4f1ddab98df5cdc10f7dfe6b1d10.jpg', '2018-06-12 00:56:28', '2018-06-12 00:56:28'),
(132, 29, 'cda9ce4ad140e11c5ad17a1abfb9692c4b5da3dc.jpg', '2018-06-12 00:56:28', '2018-06-12 00:56:28'),
(133, 29, 'dfcc8de91720e3f80624a3fff1c6f71e5df4b414.jpg', '2018-06-12 00:56:28', '2018-06-12 00:56:28'),
(134, 30, 'b0e8655a332f9fd3d38e88fbb2c8be81b2dd1d04.jpg', '2018-06-12 01:07:49', '2018-06-12 01:07:49'),
(135, 30, '1ab678bad135f53983bf14a70e5f107c7e1ae97e.jpg', '2018-06-12 01:07:49', '2018-06-12 01:07:49'),
(136, 30, '6cd99a644ed74bb8e545f70e1e3caf7bf7e48957.jpg', '2018-06-12 01:07:49', '2018-06-12 01:07:49'),
(137, 30, 'e44d400a0ae202c5e7ffc3ac7660987310b52c77.jpg', '2018-06-12 01:07:49', '2018-06-12 01:07:49'),
(138, 31, '6b1507fdb42e6535e43309afd9c0097099a85a6f.jpg', '2018-06-12 01:10:08', '2018-06-12 01:10:08'),
(139, 31, 'c53bdd1452e0f0d94bf5ca38ab4d3ecbbb9ed5a9.jpg', '2018-06-12 01:10:08', '2018-06-12 01:10:08'),
(140, 31, 'be29bdc67da22e8a8693b4afa0d0438e7ac38ed2.jpg', '2018-06-12 01:10:08', '2018-06-12 01:10:08'),
(141, 31, '474092f1efe29bf4509623a715651333e3cb4e91.jpg', '2018-06-12 01:10:08', '2018-06-12 01:10:08'),
(142, 32, 'dd02d423dc39c25d81a79e34531b3edd1248699a.jpg', '2018-06-12 01:40:26', '2018-06-12 01:40:26'),
(143, 32, '0af86e5e442f2bcd347615c30c5ffdbce0f9c86a.jpg', '2018-06-12 01:40:26', '2018-06-12 01:40:26'),
(144, 32, 'a02d02afcfcb7a3e8ef1abb5bb32be75e9874694.jpg', '2018-06-12 01:40:26', '2018-06-12 01:40:26'),
(145, 32, 'aa3233703dc6fa6bd91a6049a51c012976bb67f6.jpg', '2018-06-12 01:40:26', '2018-06-12 01:40:26'),
(146, 33, '1c42767a8bfb4e87e291bebac3a373118b6390e3.jpg', '2018-06-12 07:56:14', '2018-06-12 07:56:14'),
(147, 33, '737b4b5bb076e73d42933e66121fd89eeeaab459.jpg', '2018-06-12 07:56:14', '2018-06-12 07:56:14'),
(148, 33, 'b26e3cacea2b93ee8696008b8b221780bfae4de3.jpg', '2018-06-12 07:56:14', '2018-06-12 07:56:14'),
(149, 33, '73a0b46ef5419bdcd617755097495238375b53cc.jpg', '2018-06-12 07:56:14', '2018-06-12 07:56:14'),
(150, 33, '49c2622d4a2d6cf1470f84fdc3fc6d92635007c8.jpg', '2018-06-12 07:56:14', '2018-06-12 07:56:14'),
(151, 34, '29aefbe1ab02cb55cfb3c1817be690b715b6bfcd.jpg', '2018-06-12 08:11:22', '2018-06-12 08:11:22'),
(152, 34, '581966c8730f85db703e1d74883768e8157cde8e.jpg', '2018-06-12 08:11:22', '2018-06-12 08:11:22'),
(153, 34, 'ca0e87d88af54e06541bb63c39232156c3cd3bbc.jpg', '2018-06-12 08:11:22', '2018-06-12 08:11:22'),
(154, 34, '61c2cf5607af6a0d5d3ec8621b0fe5c0be332384.jpg', '2018-06-12 08:11:22', '2018-06-12 08:11:22'),
(155, 34, '9cb05606a20f686553560fb2a3534f9ce0f1d7ce.jpg', '2018-06-12 08:11:22', '2018-06-12 08:11:22'),
(156, 34, '3e7912e9d8167eb3814db307d4633eddde0c9b4c.jpg', '2018-06-12 08:11:22', '2018-06-12 08:11:22'),
(157, 35, '8d72eb3d83fe078bcfa868da3072271e1e535301.jpg', '2018-06-12 08:17:04', '2018-06-12 08:17:04'),
(158, 35, '1b187c3e7b02685cdf1078f1fb432f09c9b7579c.jpg', '2018-06-12 08:17:04', '2018-06-12 08:17:04'),
(159, 35, '86704020016e98620e7333da984dadb4dc165369.jpg', '2018-06-12 08:17:04', '2018-06-12 08:17:04'),
(160, 35, 'c330d5e664e774b146d9910851dd5e4ea5dadda5.jpg', '2018-06-12 08:17:04', '2018-06-12 08:17:04'),
(161, 35, '5301fc5f4a074d77d2543d77c318d754c189c3b4.jpg', '2018-06-12 08:17:04', '2018-06-12 08:17:04'),
(162, 35, 'c5820e8392a865f603696cd9157dbc4639003738.jpg', '2018-06-12 08:17:04', '2018-06-12 08:17:04'),
(165, 36, '40be7e0231b764152a583d5c70f62d2de3fe56df.jpg', '2018-06-12 08:57:37', '2018-06-12 08:57:37'),
(185, 39, 'e9f11b51aa5b10d2cd6aebfd5b6c0a62a1c30b6f.jpg', '2018-06-15 03:46:27', '2018-06-15 03:46:27'),
(186, 39, '519bbc8d85b74e8f0131c551fa2c25a401ccf760.png', '2018-06-15 03:46:27', '2018-06-15 03:46:27'),
(187, 39, '3f9fcacb5713f21aded440fceae6832b60693f57.png', '2018-06-15 03:46:27', '2018-06-15 03:46:27'),
(191, 41, 'e7283e5a8e9d200aa69f4e06b81c85f8842a4673.jpg', '2018-06-24 15:43:35', '2018-06-24 15:43:35'),
(192, 41, '15677dda40e5d5b0691ee2e590570ea570cb2a3f.jpg', '2018-06-24 15:43:35', '2018-06-24 15:43:35'),
(193, 41, '35678e58abe53f3afe7f22a81fcdb32550d35591.jpg', '2018-06-24 15:43:35', '2018-06-24 15:43:35'),
(251, 45, 'ebac5bfd5cf4504660f421a8f6672c1f0a169643.png', '2018-07-07 07:02:12', '2018-07-07 07:02:12'),
(252, 45, 'b22fddeb5ac15d91956f79251cc7d8189b08031c.png', '2018-07-07 07:02:12', '2018-07-07 07:02:12'),
(253, 45, '90a33e1a3273ab83771bcdd96faff8fbfde754c8.png', '2018-07-07 07:02:29', '2018-07-07 07:02:29'),
(254, 45, '0468d791624e58c35496812d13f3ba2f6091e294.png', '2018-07-07 07:02:29', '2018-07-07 07:02:29'),
(255, 50, '9baea3df038dbe3c7871110a299cdc8f5b13c5d5.png', '2018-07-07 07:03:24', '2018-07-07 07:03:24'),
(256, 50, 'a2689789260433bf9e90367bc265abd0d2c542bb.png', '2018-07-07 07:03:24', '2018-07-07 07:03:24'),
(262, 50, '199fd2a30fcb7512b14d2c0dfd509af97dba8cf9.png', '2018-07-07 07:07:08', '2018-07-07 07:07:08'),
(266, 50, 'c3e08ea8e6d672439d9f657605877b537396bb38.png', '2018-07-07 07:08:18', '2018-07-07 07:08:18'),
(267, 50, '31ed5aea0108eec1abb93dac0d085b7ebd7af947.png', '2018-07-07 07:08:18', '2018-07-07 07:08:18'),
(280, 49, '2b69eb0d28dc9d94c93627556742077838fc9cd2.jpg', '2018-07-07 07:34:25', '2018-07-07 07:34:25'),
(281, 49, '0d1cc5fb50b477aff35e5a077ebd468a07926323.jpg', '2018-07-07 07:34:25', '2018-07-07 07:34:25'),
(282, 49, '983bf6db38ce0e344152d94b9628eaffa1e364ca.jpg', '2018-07-07 07:34:25', '2018-07-07 07:34:25'),
(336, 55, '8cff58bb5b4d81a8fd01c6843261fbd8276b031b.jpg', '2018-07-09 00:20:13', '2018-07-09 00:20:13'),
(337, 55, '1c610fec05ffe9ed96bd4709f3e0ca48c885a316.jpg', '2018-07-09 00:20:13', '2018-07-09 00:20:13'),
(338, 55, 'be3da11f2a02ab9600ae507c0fa7c935653a1ed7.jpg', '2018-07-09 00:20:13', '2018-07-09 00:20:13'),
(347, 58, 'a7a3fa96512d110a14277686d5e2b1a22b05731c.png', '2018-07-09 04:16:09', '2018-07-09 04:16:09'),
(348, 58, 'ff61b10595e5f782a9dd07935891febce1468dd8.jpg', '2018-07-09 04:16:09', '2018-07-09 04:16:09'),
(382, 57, 'tutsplus-profile.png', '2018-07-09 07:49:39', '2018-07-09 07:49:39'),
(383, 57, 'minions_4k_8k-HD.jpg', '2018-07-09 07:49:40', '2018-07-09 07:49:40'),
(387, 60, 'ac7d136c9c1ae5538f605878ab900415401be8e3.jpeg', '2018-07-10 01:19:13', '2018-07-10 01:19:13'),
(388, 60, 'd4d3c670eb82be8c37444b2b7366a671cd5913e9.jpeg', '2018-07-10 01:19:13', '2018-07-10 01:19:13'),
(389, 60, '4e8c3e193fe2078ed4d39cfcfa84771d661dbd25.jpg', '2018-07-10 01:19:13', '2018-07-10 01:19:13'),
(394, 59, '15312186875b448aff7bc02.jpg', '2018-07-10 05:01:27', '2018-07-10 05:01:27'),
(395, 59, '15312186875b448aff7ccee.jpg', '2018-07-10 05:01:27', '2018-07-10 05:01:27'),
(396, 59, '15312186875b448aff7d36b.jpg', '2018-07-10 05:01:27', '2018-07-10 05:01:27'),
(397, 61, '75a612fca3cc12fcb36a594fad9775ff7b32da6e.jpg', '2018-07-11 00:32:54', '2018-07-11 00:32:54'),
(398, 61, '0ad19484a5fc603d9d8d8d4de8c60d412b879a6c.jpg', '2018-07-11 00:32:54', '2018-07-11 00:32:54'),
(399, 61, '68e2509ec1e68b7e96bf911bd2a3edcdf618531b.jpg', '2018-07-11 00:32:54', '2018-07-11 00:32:54'),
(400, 61, '70318092db8fffe30b509821403f4ddbe78b73c3.jpg', '2018-07-11 00:32:54', '2018-07-11 00:32:54'),
(404, 63, '58f2b07373a805c0fb1bd07b219533495d3115e6.png', '2018-07-12 00:14:20', '2018-07-12 00:14:20'),
(405, 63, '87a11ddfb4e9e5937d02e4016dcc6cbda65b1449.png', '2018-07-12 00:14:20', '2018-07-12 00:14:20'),
(406, 63, 'f8d0de05c79c624ce3234345506823ec159c3f4e.png', '2018-07-12 00:14:20', '2018-07-12 00:14:20'),
(407, 63, 'e732f47d73c44064810afd3f5cefa76d449dc305.png', '2018-07-12 00:14:20', '2018-07-12 00:14:20'),
(408, 64, '3fbb3405f79b084ce91e59e1931c59390cbdd511.png', '2018-07-13 04:24:28', '2018-07-13 04:24:28'),
(409, 64, 'aa396e17145292fbd011d8e209139fbbf5529179.png', '2018-07-13 04:24:28', '2018-07-13 04:24:28'),
(410, 64, 'e3fdd80c121a9b581915ee88f8975c7cd4caa7b5.png', '2018-07-13 04:24:28', '2018-07-13 04:24:28'),
(411, 64, '06d424fb75b58443d8174adb12edda69b8a30a49.png', '2018-07-13 04:24:28', '2018-07-13 04:24:28'),
(412, 64, '2f7e9f17169f519402e82c8be4529b07e45d9054.png', '2018-07-13 04:24:28', '2018-07-13 04:24:28'),
(413, 64, 'cbe867fb0a1f92656d143ac3cfbdbb4e98695c0f.png', '2018-07-13 04:24:28', '2018-07-13 04:24:28'),
(414, 28, '5e85115965f5f85c948a82f08ddcda977ba65438.jpg', '2018-07-13 04:36:12', '2018-07-13 04:36:12'),
(415, 28, 'df85be31df0473e0e0b1bd42b2752d5e9cec680b.jpg', '2018-07-13 04:36:12', '2018-07-13 04:36:12'),
(416, 28, 'b31f616fe9ee52984262c8377c848895840bc167.jpg', '2018-07-13 04:36:12', '2018-07-13 04:36:12'),
(417, 65, '740447a85a38546b284b2544f470d1a48054bda6.jpg', '2018-07-13 04:37:07', '2018-07-13 04:37:07'),
(418, 65, '76a2eec8bb4b383212f970f0615065c42f0b75b4.jpeg', '2018-07-13 04:37:07', '2018-07-13 04:37:07'),
(419, 65, 'f3b9a771500a1bc53b3ccc65488a7543e30e5806.jpeg', '2018-07-13 04:37:07', '2018-07-13 04:37:07'),
(420, 65, 'a6e72196b279a8e3dec84d07790299dd5abeb559.jpg', '2018-07-13 04:37:07', '2018-07-13 04:37:07'),
(421, 65, '37bbc882be1b46f1075ca333ad1df891e703702e.jpeg', '2018-07-13 04:37:07', '2018-07-13 04:37:07'),
(422, 65, '8948f37182b2c463f1a290d9225378330e75ed07.jpeg', '2018-07-13 04:37:07', '2018-07-13 04:37:07'),
(423, 66, 'ce5fd548e7a4b6cffb6609ef49fbd5bacb9f7b84.jpg', '2018-07-13 04:40:38', '2018-07-13 04:40:38'),
(424, 66, 'dbb3a65c21f4c5b754004277d2be227a2b55bab1.jpeg', '2018-07-13 04:40:38', '2018-07-13 04:40:38'),
(425, 66, '001c99b66efa067efc2dfeb75d99ad298900375a.jpeg', '2018-07-13 04:40:38', '2018-07-13 04:40:38'),
(426, 66, 'ed1df3076f7e9dd108583bb50866cc0adc481035.jpg', '2018-07-13 04:40:38', '2018-07-13 04:40:38'),
(427, 66, '7fc861315c223fdf858d588dee2a6fbecde7de3e.jpeg', '2018-07-13 04:40:38', '2018-07-13 04:40:38'),
(428, 66, 'dde90d5a63938e68ccc4a518c30b5c57349e2719.jpeg', '2018-07-13 04:40:38', '2018-07-13 04:40:38'),
(429, 67, '2d87406f0ce1a0e487cbe7ffc7f2d2cd97e57020.jpg', '2018-07-13 04:44:47', '2018-07-13 04:44:47'),
(430, 67, '3b803919f05a8918477e057a48e854a06fd5e547.jpeg', '2018-07-13 04:44:47', '2018-07-13 04:44:47'),
(431, 67, '2f7f967ec58f41f35cc76985104de6f8b60392be.jpeg', '2018-07-13 04:44:47', '2018-07-13 04:44:47'),
(432, 67, '08ccf7c0e6f42a02870f22a093a1e4dd7ed606e2.jpg', '2018-07-13 04:44:47', '2018-07-13 04:44:47'),
(433, 67, '2a07515c9c5ff970716ba53ce373c05a7e8dbc1b.jpeg', '2018-07-13 04:44:47', '2018-07-13 04:44:47'),
(434, 67, '361e839edcaaf2dba304cbaf7cc929b9bb34a139.jpeg', '2018-07-13 04:44:47', '2018-07-13 04:44:47'),
(435, 68, '15314635135b484759aa087.jpg', '2018-07-13 05:01:53', '2018-07-13 05:01:53'),
(436, 68, '15314635135b484759aaaaa.jpeg', '2018-07-13 05:01:53', '2018-07-13 05:01:53'),
(437, 68, '15314635135b484759aaf75.jpeg', '2018-07-13 05:01:53', '2018-07-13 05:01:53'),
(438, 68, '15314635135b484759ab416.jpg', '2018-07-13 05:01:53', '2018-07-13 05:01:53'),
(439, 68, '15314635135b484759ab94b.jpeg', '2018-07-13 05:01:53', '2018-07-13 05:01:53'),
(440, 68, '15314635135b484759abe1b.jpeg', '2018-07-13 05:01:53', '2018-07-13 05:01:53'),
(444, 71, '4f4482f1c4dbce370017f898aa663107ae345d93.jpg', '2018-07-13 05:49:44', '2018-07-13 05:49:44'),
(445, 71, '743c410b6a4a6809fd06bf690752241f2db022fc.jpeg', '2018-07-13 05:49:44', '2018-07-13 05:49:44'),
(446, 71, '1c71d02cb005b639ad7f6ef2831299cd0896bd40.jpeg', '2018-07-13 05:49:44', '2018-07-13 05:49:44'),
(447, 71, 'a43ad03b3cee8de9a6ebc3782cb294e4c114dafc.jpg', '2018-07-13 05:49:44', '2018-07-13 05:49:44'),
(448, 71, '6f4121306fbaf8c5e93b7b4fe60945de1ff1e4a2.jpeg', '2018-07-13 05:49:44', '2018-07-13 05:49:44'),
(449, 71, '1b66dea3d51ef3eaf7f76095164d6fd091defc7c.jpeg', '2018-07-13 05:49:44', '2018-07-13 05:49:44'),
(509, 77, '7c9ec3ac20ba2a48e43130251f9812908b26d02f.png', '2018-07-13 08:54:35', '2018-07-13 08:54:35'),
(510, 77, '9197618928a504dbf6fcf619ecfbcd70da649402.png', '2018-07-13 08:54:35', '2018-07-13 08:54:35'),
(511, 77, '785f8ec43793b306b84d830359af673978507c5b.png', '2018-07-13 08:54:35', '2018-07-13 08:54:35'),
(512, 77, '78dbebfd32d2da1443834e4d33487cc6dad5fbfe.png', '2018-07-13 08:54:35', '2018-07-13 08:54:35'),
(513, 77, 'fa5804e8972fd4409bdfb9b7a93ff524c8ee1507.png', '2018-07-13 08:54:35', '2018-07-13 08:54:35'),
(514, 77, '25cc40e0ad44c0bfe1bd248f0760653285a0c6eb.png', '2018-07-13 08:54:35', '2018-07-13 08:54:35'),
(515, 78, '8fb47797a7c4399f191f5c5c9d8fb88292f8a625.jpg', '2018-07-13 11:34:59', '2018-07-13 11:34:59'),
(516, 78, '629ba0877ae5a6a4e63b4bec32d2297f036f9b81.jpg', '2018-07-13 11:34:59', '2018-07-13 11:34:59'),
(517, 78, '91ee80c7ca53a0b50516b4b5ba0ec089ba350edc.jpg', '2018-07-13 11:34:59', '2018-07-13 11:34:59'),
(518, 79, '981d6b551e37de6bf64027d2708378130c7cf1cb.jpg', '2018-07-16 07:16:26', '2018-07-16 07:16:26'),
(519, 79, 'b75aec1d84186d508dc92143d8c0f6d5e173d854.jpeg', '2018-07-16 07:16:26', '2018-07-16 07:16:26'),
(520, 79, 'f4bcefd88560013891336d6cc7e74fa908a686f3.jpeg', '2018-07-16 07:16:26', '2018-07-16 07:16:26'),
(521, 79, '8273cb0b6a6d6ce4c86084164b4084677ed62741.jpeg', '2018-07-16 07:16:26', '2018-07-16 07:16:26'),
(522, 79, '46ef73f5b0779006cead6cd711f0cc5ba7988a5f.jpeg', '2018-07-16 07:16:26', '2018-07-16 07:16:26'),
(523, 79, '13145acdd11407d4bc712d0d5d30cd71f4186d9e.jpg', '2018-07-16 07:16:26', '2018-07-16 07:16:26'),
(524, 80, '56556b09a630349565a48295cc8e0f92c5acd76c.jpg', '2018-07-16 11:30:07', '2018-07-16 11:30:07'),
(525, 80, 'dbbe986b00b01f9662461a492962cff2e91644ae.jpeg', '2018-07-16 11:30:07', '2018-07-16 11:30:07'),
(526, 80, 'd4d75d8b636bb3aa70360585977ddc7f13796d7c.jpeg', '2018-07-16 11:30:07', '2018-07-16 11:30:07'),
(527, 80, '38ff652199395e7a69e879e0d1d03fbf69879ea3.jpeg', '2018-07-16 11:30:07', '2018-07-16 11:30:07'),
(528, 80, 'edbbd2a9c3b037a6b09c729cdfa41abea569e640.jpeg', '2018-07-16 11:30:07', '2018-07-16 11:30:07'),
(529, 80, '904911dfc337f08e4af7242b20184cae1f1d643a.jpg', '2018-07-16 11:30:07', '2018-07-16 11:30:07'),
(531, 36, '15317503185b4ca7ae2868e.png', '2018-07-16 12:41:58', '2018-07-16 12:41:58'),
(534, 36, '15317503185b4ca7ae29437.png', '2018-07-16 12:41:58', '2018-07-16 12:41:58'),
(555, 81, '15319057435b4f06cf897ab.jpeg', '2018-07-18 13:22:23', '2018-07-18 13:22:23'),
(566, 81, '15319063705b4f094254422.jpeg', '2018-07-18 13:32:50', '2018-07-18 13:32:50'),
(569, 81, '15319063705b4f094255b96.jpg', '2018-07-18 13:32:50', '2018-07-18 13:32:50'),
(582, 83, 'dfe140b8a4b54d89edb8716bd774b4f6f422074d.png', '2018-07-18 13:48:04', '2018-07-18 13:48:04'),
(583, 83, 'b0fd20cbfb2a44906830f7ffc24f6b4aea8b9264.png', '2018-07-18 13:48:04', '2018-07-18 13:48:04'),
(584, 83, '4410ba2c7a4b401473db336d03ab362fc19ee14a.png', '2018-07-18 13:48:04', '2018-07-18 13:48:04'),
(585, 83, '67d651dd3df333afb1058780572f07f42d187e97.png', '2018-07-18 13:48:04', '2018-07-18 13:48:04'),
(586, 83, 'aec9df85035fbe6831d205a548ec4840155587d0.png', '2018-07-18 13:48:04', '2018-07-18 13:48:04'),
(587, 83, '8605fe661fd4a80add6e66325c5a3777f033090f.png', '2018-07-18 13:48:04', '2018-07-18 13:48:04'),
(591, 84, '4e83d778eac3256005f67eb1826f4f605fb134b3.jpg', '2018-07-18 15:16:48', '2018-07-18 15:16:48'),
(592, 84, 'd3ead9d4a7ff7716da9f12ef829d92b71d1590e5.jpg', '2018-07-18 15:16:48', '2018-07-18 15:16:48'),
(593, 84, '0c5ec649de8a177ecd1252625bf678c21206ec78.jpg', '2018-07-18 15:16:48', '2018-07-18 15:16:48'),
(594, 26, '6467162528a333c501931b1d456f58e656e0aa59.jpg', '2018-07-18 17:50:23', '2018-07-18 17:50:23'),
(595, 26, '96f811010027fd8baebe2d083f4b80d779d41a97.jpg', '2018-07-18 17:50:23', '2018-07-18 17:50:23'),
(596, 26, '7a480fa94ef26e01690a42f39d9630ce727d6351.jpg', '2018-07-18 17:50:23', '2018-07-18 17:50:23'),
(597, 26, 'aa3c0c316b10e738aef2a329879c466d24b821af.jpg', '2018-07-18 17:50:23', '2018-07-18 17:50:23'),
(598, 26, 'd922421767070b4fa003f59a770d25e097a7058b.jpg', '2018-07-18 17:50:23', '2018-07-18 17:50:23'),
(599, 26, '4478bf60c72175679144af8263ebf5d06e4be650.jpg', '2018-07-18 17:50:23', '2018-07-18 17:50:23'),
(606, 85, '1d56b9c44ff6d9aa3337c2fadca00f3d963ba027.png', '2018-07-19 09:35:07', '2018-07-19 09:35:07'),
(607, 85, 'a6555a2215a8a674f1787f86e2c4f15cc0a4eea4.png', '2018-07-19 09:35:07', '2018-07-19 09:35:07'),
(608, 85, 'be09c822c0c0a09ab5e196ebab576c1d6be79e9f.png', '2018-07-19 09:35:07', '2018-07-19 09:35:07'),
(609, 85, 'c35552251afe50a2fdd3c8a0452fb2c71956538f.png', '2018-07-19 09:35:07', '2018-07-19 09:35:07'),
(610, 85, '18dd7370a4d86fccaa52239c1f21fa885eeb7813.png', '2018-07-19 09:35:07', '2018-07-19 09:35:07'),
(611, 85, 'f953fe84123ca8ffb8d9a8f1d74fa819bf87f0cb.png', '2018-07-19 09:35:07', '2018-07-19 09:35:07'),
(618, 87, 'aa8251e602705ede6ae0e1359bc67b115c9a515a.png', '2018-07-19 12:31:59', '2018-07-19 12:31:59'),
(619, 87, '1aa59a8f48f12fd88458e321d334b71be6304c65.png', '2018-07-19 12:31:59', '2018-07-19 12:31:59'),
(620, 87, 'cbb369b4882b25638a29600de6abeae6e185d18e.png', '2018-07-19 12:31:59', '2018-07-19 12:31:59'),
(621, 87, '2a3f2bdaa697f383bcb8d78a0cbe28c6c9c3d73b.png', '2018-07-19 12:31:59', '2018-07-19 12:31:59'),
(622, 87, '012e2f7050ccce99d42bc954d01343c443b4c8e1.png', '2018-07-19 12:31:59', '2018-07-19 12:31:59'),
(623, 87, '66360c91a3fa3b9495bece84879f9b1e6a88a4a3.png', '2018-07-19 12:31:59', '2018-07-19 12:31:59'),
(624, 88, '849d26bbd9619cfebaf3bf2d10c71073ff774990.png', '2018-07-19 15:05:59', '2018-07-19 15:05:59'),
(625, 88, '439c0c2bc2514f7dcdae4bcedcf6a88606b1db87.png', '2018-07-19 15:05:59', '2018-07-19 15:05:59'),
(626, 88, '455c30fae348b601c9de9b9733fcc759521b53ce.png', '2018-07-19 15:05:59', '2018-07-19 15:05:59'),
(627, 88, 'c98ef0f4d1edd261e9481012af3959c5eb0fde37.png', '2018-07-19 15:05:59', '2018-07-19 15:05:59'),
(628, 89, 'c10f4dd92281bedd454ac88fe7d9e79c85df3f20.png', '2018-07-19 15:12:37', '2018-07-19 15:12:37'),
(629, 89, '587e851d17e53514693c748639039d61fca89c7f.png', '2018-07-19 15:12:37', '2018-07-19 15:12:37'),
(630, 89, '927e7de671502362bf7e8e4709f49f8bfb961ced.png', '2018-07-19 15:12:37', '2018-07-19 15:12:37'),
(631, 89, 'd4c794f510e5cf3c7957f1d370a191e87071f42d.png', '2018-07-19 15:12:37', '2018-07-19 15:12:37'),
(632, 89, '4a8d9f04b2ad51e447d221eede5e6f6af636fa34.png', '2018-07-19 15:12:37', '2018-07-19 15:12:37'),
(633, 90, '91ff4b0224fc2bff3fada6b96792cd17f01eefe0.jpg', '2018-07-19 15:18:04', '2018-07-19 15:18:04'),
(634, 90, '10ce65d2560cd0dfe86264801183e38f1a70efc4.jpeg', '2018-07-19 15:18:04', '2018-07-19 15:18:04'),
(635, 90, '4e6fdcf4a17298f838589bdc86a7242efc881f0f.jpeg', '2018-07-19 15:18:04', '2018-07-19 15:18:04'),
(636, 90, 'bae6a5ceb65b2696cd2bc611792e8a349580a222.jpg', '2018-07-19 15:18:04', '2018-07-19 15:18:04'),
(637, 90, 'edfcdaa5af909b0b2ffde81922c20f854e2315d7.jpeg', '2018-07-19 15:18:04', '2018-07-19 15:18:04'),
(638, 90, '6f5bb1ea5c8f7fc7ebe9ab7d563b73efbad04939.jpeg', '2018-07-19 15:18:04', '2018-07-19 15:18:04'),
(643, 92, 'fb7088363e7340f490a5738d93b2de1fa8bd67a9.png', '2018-07-20 14:12:52', '2018-07-20 14:12:52'),
(644, 92, 'b2a3b2dfb1fef5f5789fa6e8cac2abb6237be361.png', '2018-07-20 14:12:52', '2018-07-20 14:12:52'),
(645, 92, '88efb46bb7b75057d846cf9c3f9aa80f7c14eb39.png', '2018-07-20 14:12:52', '2018-07-20 14:12:52'),
(699, 99, '15321721325b531764d531d.jpg', '2018-07-21 15:22:12', '2018-07-21 15:22:12'),
(700, 99, '15321721325b531764d6558.jpeg', '2018-07-21 15:22:12', '2018-07-21 15:22:12'),
(701, 99, '15321721325b531764d6bc3.jpeg', '2018-07-21 15:22:12', '2018-07-21 15:22:12'),
(702, 99, '15321721325b531764d85f0.jpg', '2018-07-21 15:22:12', '2018-07-21 15:22:12'),
(703, 99, '15321721325b531764da571.jpeg', '2018-07-21 15:22:12', '2018-07-21 15:22:12'),
(704, 99, '15321721325b531764dbf26.jpeg', '2018-07-21 15:22:12', '2018-07-21 15:22:12'),
(707, 100, 'c3b375aa49f09fe6fda878f0194cc95619b5c751.jpg', '2018-07-21 17:25:04', '2018-07-21 17:25:04'),
(708, 100, '25ed2f34088612ff45da2c21a700e8ac0c4a847e.jpg', '2018-07-21 17:25:04', '2018-07-21 17:25:04'),
(709, 100, 'f4acf0da1082b9eab57da4c67b1f5aae663cd708.jpg', '2018-07-21 17:25:04', '2018-07-21 17:25:04'),
(710, 100, 'cf8ebae85c24513c9ceb4ce8864c113c2e7c77a6.jpg', '2018-07-21 17:25:04', '2018-07-21 17:25:04'),
(711, 100, '7957cefa36bc90790ab300928bf127c320f3223f.jpg', '2018-07-21 17:25:04', '2018-07-21 17:25:04'),
(712, 100, '234262bfcbcbd57c5e49dbb59fb7c8db6f5928dd.jpg', '2018-07-21 17:25:04', '2018-07-21 17:25:04'),
(713, 102, 'b73b2ebd964a5ab247776a02c8ae0dfe139d41cf.jpg', '2018-07-23 08:28:41', '2018-07-23 08:28:41'),
(714, 102, '50a10c47bd5d27c35a411c3088ebe06540506030.jpeg', '2018-07-23 08:28:41', '2018-07-23 08:28:41'),
(715, 102, 'c3ffa229884486e3efc2839da7e10d1882bf0ccb.jpeg', '2018-07-23 08:28:41', '2018-07-23 08:28:41'),
(716, 102, '56cde0c8d112741cf8527db85fd0f8d18f19a16f.jpg', '2018-07-23 08:28:41', '2018-07-23 08:28:41'),
(717, 102, '46f4dd61e615e40e29233d523c94116d97617239.jpeg', '2018-07-23 08:28:41', '2018-07-23 08:28:41'),
(718, 102, '5407b4eb96f450977eb46a3331d3f7bed2482786.jpeg', '2018-07-23 08:28:41', '2018-07-23 08:28:41'),
(719, 104, 'c9eb02dbeb3486b720eb68f613b0d38700f40c6a.jpg', '2018-07-23 09:37:55', '2018-07-23 09:37:55'),
(720, 104, '7498fe7eb00169c555362882d5135c1b2269adba.jpg', '2018-07-23 09:37:55', '2018-07-23 09:37:55'),
(721, 104, '0ceaa1372a310d00d85a22d895c110ffd223ab99.jpg', '2018-07-23 09:37:55', '2018-07-23 09:37:55'),
(722, 104, 'ee38f907290cacca27bfeb2bba44634d02aab7e4.jpg', '2018-07-23 09:37:55', '2018-07-23 09:37:55'),
(723, 104, '2e6769c9060ba0a4068379d81a4bbc1d13a4b307.jpg', '2018-07-23 09:37:55', '2018-07-23 09:37:55'),
(724, 104, '8c471ebf304cda2d10d455bd0c5cb1340982c434.jpg', '2018-07-23 09:37:55', '2018-07-23 09:37:55'),
(725, 105, '482409f78284c2e21dfa0e6bd5bef8e162a5ccaf.jpg', '2018-07-23 13:11:56', '2018-07-23 13:11:56'),
(726, 105, 'c5054aa5473c6d5049ee2bdadc47a1014a13bb33.jpg', '2018-07-23 13:11:56', '2018-07-23 13:11:56'),
(727, 105, '939923aa76011c28f43e6a1de412ed7c9926809e.jpg', '2018-07-23 13:11:56', '2018-07-23 13:11:56'),
(728, 105, '9bc08f7f91c5f1c6df86191f3d123280873edebe.jpg', '2018-07-23 13:11:56', '2018-07-23 13:11:56'),
(729, 105, '66e9fa97b74715d899462f233366a58a7bf4dfa0.jpg', '2018-07-23 13:11:56', '2018-07-23 13:11:56'),
(730, 105, 'fda2d5110b80b3a0b761d737927d099c65b54b06.jpg', '2018-07-23 13:11:56', '2018-07-23 13:11:56'),
(731, 106, 'd020c7fac089d174b4c04c6c8fc87425805c14b0.jpg', '2018-07-23 14:08:53', '2018-07-23 14:08:53'),
(732, 106, '62f44d7fa7a389a0afaff293cfcae067d5a45d95.jpg', '2018-07-23 14:08:53', '2018-07-23 14:08:53'),
(733, 106, '6e67d51b33c7a4ea797e11e252e2c1e7b3118765.jpg', '2018-07-23 14:08:53', '2018-07-23 14:08:53'),
(734, 106, 'fdd1e7ac760563ada43b616f3302d04baf5d50b5.jpg', '2018-07-23 14:08:53', '2018-07-23 14:08:53'),
(735, 106, 'c4d45a4640ed5f6261706b80074724248d13cbad.jpg', '2018-07-23 14:08:53', '2018-07-23 14:08:53'),
(736, 106, '1881ab4b383a79000a087422c668c2129388f0cc.jpg', '2018-07-23 14:08:53', '2018-07-23 14:08:53'),
(737, 106, '40f0d26b4d8c4baf00b425efae3f777664ccd052.jpg', '2018-07-23 14:10:22', '2018-07-23 14:10:22'),
(738, 106, 'c45d29d950bafc0b81c5af06a1c47111983731c9.jpeg', '2018-07-23 14:10:22', '2018-07-23 14:10:22'),
(739, 106, '92be6a5de2f77ec0c15a9a29b7bf11f71b00015b.jpeg', '2018-07-23 14:10:22', '2018-07-23 14:10:22'),
(740, 106, '217916af2105d6ee0d946dc8e777e476ec918afd.jpg', '2018-07-23 14:10:23', '2018-07-23 14:10:23'),
(741, 106, 'bc866fa9903593c63a8d8cc4ca854f97795af0f9.jpeg', '2018-07-23 14:10:23', '2018-07-23 14:10:23'),
(742, 106, 'bb6b6aaa5e0c919dd94ee8e3f1a0cb87f8677520.jpeg', '2018-07-23 14:10:23', '2018-07-23 14:10:23'),
(762, 107, '3fe2a6cf05fb560e03649745c7a6e73daeb24775.png', '2018-07-23 16:33:52', '2018-07-23 16:33:52'),
(763, 107, '44df85fc522fa02f2c6943a571ed84d7388b2a16.png', '2018-07-23 16:33:52', '2018-07-23 16:33:52'),
(764, 107, '1af755a34517ef9f7ae53c74dad59b5aeb390de3.png', '2018-07-23 16:33:52', '2018-07-23 16:33:52'),
(771, 109, '11fb93cb71147e4880f70a21fb3d149bdfb83fd8.png', '2018-07-24 11:13:44', '2018-07-24 11:13:44'),
(772, 109, 'c4f67ab76802d2776d8a7834286b4576ac8f7431.png', '2018-07-24 11:13:44', '2018-07-24 11:13:44'),
(773, 109, 'e26f2751e71ab897039655c990362cc3a3145dde.png', '2018-07-24 11:13:44', '2018-07-24 11:13:44'),
(774, 109, '7c05ff8da7bad97b66cbe89dab0291aea0e87e57.png', '2018-07-24 11:13:44', '2018-07-24 11:13:44'),
(775, 109, '3e868a1685759628375d5737fba1dce5fcac5d9e.png', '2018-07-24 11:13:44', '2018-07-24 11:13:44'),
(776, 109, '77612f25eab151fefde8f2e53ac45b88e6b48a38.png', '2018-07-24 11:13:44', '2018-07-24 11:13:44'),
(777, 111, '7b7ebc1430bda62a5b66aa6769bd1926392cbe31.jpg', '2018-07-24 11:17:21', '2018-07-24 11:17:21'),
(778, 111, '5b3b1791cc7c4c178c8336bd9825865cdf4d8a59.jpg', '2018-07-24 11:17:21', '2018-07-24 11:17:21'),
(779, 111, 'b4077a4d95eccca4ece292743c4742595d05e068.jpg', '2018-07-24 11:17:21', '2018-07-24 11:17:21'),
(780, 111, 'c53c62a838176d690927ea82695dd8fd5aeb1ea6.jpg', '2018-07-24 11:17:21', '2018-07-24 11:17:21'),
(781, 111, '2e3a751442a8d4e23f4c281ea80baee47e0eb82b.jpg', '2018-07-24 11:17:21', '2018-07-24 11:17:21'),
(782, 111, 'f7bc5fc9357e3b4d742983a1dd23d5b31916ff46.jpg', '2018-07-24 11:17:21', '2018-07-24 11:17:21'),
(783, 112, 'ed38c62e82e30d6aca9fd003d84fb6754d20a0c4.png', '2018-07-24 16:07:20', '2018-07-24 16:07:20'),
(784, 112, '5e6f860fa54221ce0e8c0142ecb221d00a08a6e4.png', '2018-07-24 16:07:20', '2018-07-24 16:07:20'),
(785, 112, '279ee2de4689a3b0ed82d697cbd9933b6dc2ed40.png', '2018-07-24 16:07:20', '2018-07-24 16:07:20'),
(786, 112, '3b6ab262c07b94dc6c2b0212dde0cd9e124c3da2.png', '2018-07-24 16:07:20', '2018-07-24 16:07:20'),
(787, 112, 'b590226836bb6bb100dc2edc10288fbf952bfddf.png', '2018-07-24 16:07:20', '2018-07-24 16:07:20'),
(788, 112, 'f4a9b48fefe69003f67b0bbbb8993bc71da0eb68.png', '2018-07-24 16:07:20', '2018-07-24 16:07:20'),
(789, 113, 'c7bdfaccdd444fc3e1d33140f8b869b4b347a507.jpeg', '2018-07-24 16:08:51', '2018-07-24 16:08:51'),
(790, 113, 'a0852f8df7d4a46d77484df99ab2580308d3e19e.jpeg', '2018-07-24 16:08:51', '2018-07-24 16:08:51'),
(791, 113, '272508f3db4b7e28683cef692d73728f1bce203c.jpeg', '2018-07-24 16:08:51', '2018-07-24 16:08:51'),
(792, 113, '2c5072e80f3d291bace9783d80726494079aa56e.jpeg', '2018-07-24 16:08:51', '2018-07-24 16:08:51'),
(793, 113, 'dbdd6d7255a4f413c2a75b13d84b4b67ff627e83.jpg', '2018-07-24 16:08:51', '2018-07-24 16:08:51'),
(794, 113, '36ec851e5cd4420ecd4572f9d5455df83fc1a3a7.jpg', '2018-07-24 16:08:51', '2018-07-24 16:08:51'),
(795, 114, '677ce074740a3f2ec252b9602da49ef5ee89258e.png', '2018-07-25 13:52:40', '2018-07-25 13:52:40'),
(796, 114, 'a214b325711aef392326d29cdb33d02e517ae435.png', '2018-07-25 13:52:40', '2018-07-25 13:52:40'),
(797, 114, '93c888147b209e8d55fbd258798a2f076150f1ae.png', '2018-07-25 13:52:40', '2018-07-25 13:52:40'),
(798, 114, '2cee475d62a35d4aa1e28cc82558afc6929ec12f.png', '2018-07-25 13:52:40', '2018-07-25 13:52:40'),
(799, 114, '151e208d80e20279fe141b848e8d898930b825c1.png', '2018-07-25 13:52:40', '2018-07-25 13:52:40'),
(800, 114, '0e6b3022128d70322947f34d9f8e1d3d2178faeb.png', '2018-07-25 13:52:40', '2018-07-25 13:52:40'),
(801, 115, '29a730c51b6b49d579625c88375b756d6700400f.jpg', '2018-07-25 13:24:32', '2018-07-25 13:24:32'),
(802, 115, 'd85f68cffe1325d21946c5dd3a60f7c200cbc191.jpg', '2018-07-25 13:24:32', '2018-07-25 13:24:32'),
(803, 115, '12be17e60c954c1b84cf70b71bf2f3bafa382372.jpg', '2018-07-25 13:24:32', '2018-07-25 13:24:32'),
(804, 115, 'bfee56654efdc055efd078f98a554545106e312d.jpg', '2018-07-25 13:24:32', '2018-07-25 13:24:32'),
(805, 115, '32901532d4247f3493714794caa8a1b8686f6492.jpg', '2018-07-25 13:24:32', '2018-07-25 13:24:32'),
(806, 115, '0d793a161a8985da60689e63432eb8cd40b28a2a.jpg', '2018-07-25 13:24:32', '2018-07-25 13:24:32'),
(813, 116, '15329437195b5edd6794774.jpg', '2018-07-30 09:41:59', '2018-07-30 09:41:59'),
(814, 116, '15329437195b5edd6796222.jpeg', '2018-07-30 09:41:59', '2018-07-30 09:41:59'),
(815, 116, '15329437195b5edd6798a33.jpg', '2018-07-30 09:41:59', '2018-07-30 09:41:59'),
(816, 116, '15329437195b5edd67995f3.jpeg', '2018-07-30 09:41:59', '2018-07-30 09:41:59'),
(817, 116, '15329437195b5edd679a971.jpeg', '2018-07-30 09:41:59', '2018-07-30 09:41:59'),
(818, 117, '15329467865b5ee962cfbf5.jpg', '2018-07-30 10:33:06', '2018-07-30 10:33:06'),
(819, 117, '15329467865b5ee962d2407.jpeg', '2018-07-30 10:33:06', '2018-07-30 10:33:06'),
(820, 117, '15329467865b5ee962d2aab.jpeg', '2018-07-30 10:33:06', '2018-07-30 10:33:06'),
(821, 117, '15329467865b5ee962d3680.jpg', '2018-07-30 10:33:06', '2018-07-30 10:33:06'),
(822, 117, '15329467865b5ee962d3e15.jpeg', '2018-07-30 10:33:06', '2018-07-30 10:33:06'),
(823, 117, '15329467865b5ee962d432e.jpeg', '2018-07-30 10:33:06', '2018-07-30 10:33:06'),
(824, 118, '4277ec0c5b8c67f1a791908140b08a3966dcf909.jpg', '2018-07-30 10:35:57', '2018-07-30 10:35:57'),
(825, 118, '0ff425e73872f9676d19b5ff57ddf8a3205b5b70.jpeg', '2018-07-30 10:35:57', '2018-07-30 10:35:57'),
(826, 118, '6431bdf702cddec6852b3bcb2795c94f6af0614b.jpeg', '2018-07-30 10:35:57', '2018-07-30 10:35:57'),
(827, 118, 'a0b796af61ff83879f9a509a6e22bcdfd54a5444.jpeg', '2018-07-30 10:35:57', '2018-07-30 10:35:57'),
(828, 118, '36b10c66b8641a0c6047dff2ee0230fff6afa1fd.jpeg', '2018-07-30 10:35:57', '2018-07-30 10:35:57'),
(829, 118, '599c9e93c190d66c2942e8ec2a66235eb2fe24ff.jpg', '2018-07-30 10:35:57', '2018-07-30 10:35:57'),
(857, 123, '15330374215b604b6d14d1f.jpg', '2018-07-31 11:43:41', '2018-07-31 11:43:41'),
(858, 123, '15330374215b604b6d1593c.jpeg', '2018-07-31 11:43:41', '2018-07-31 11:43:41'),
(859, 123, '15330374215b604b6d15e9f.jpeg', '2018-07-31 11:43:41', '2018-07-31 11:43:41'),
(860, 123, '15330374215b604b6d163a6.jpg', '2018-07-31 11:43:41', '2018-07-31 11:43:41'),
(861, 123, '15330374215b604b6d166ea.jpeg', '2018-07-31 11:43:41', '2018-07-31 11:43:41'),
(862, 123, '15330374215b604b6d16ca4.jpeg', '2018-07-31 11:43:41', '2018-07-31 11:43:41'),
(863, 124, '760fbde5a1e5b984829cfa92eaa88fa2019042fe.jpg', '2018-07-31 11:47:08', '2018-07-31 11:47:08'),
(864, 124, 'db71c18e3c250e2857541178f1fa520975746a8d.jpeg', '2018-07-31 11:47:08', '2018-07-31 11:47:08'),
(865, 124, 'a24271b648b638b5095d7eeaf35ec9d73659fac4.jpeg', '2018-07-31 11:47:08', '2018-07-31 11:47:08'),
(866, 124, '3b7cbb1625dd98c93e3dbd5d7363c5df22a0b23b.jpeg', '2018-07-31 11:47:08', '2018-07-31 11:47:08'),
(867, 124, '1707dd8a7aa3c93ce7bf9e6ffc4348a3b284a489.jpg', '2018-07-31 11:47:08', '2018-07-31 11:47:08'),
(868, 124, '416b2dee0992bb6428313e2714587cd4349e87d1.jpeg', '2018-07-31 11:47:08', '2018-07-31 11:47:08'),
(875, 125, '5c6c9bba4edd24427af456ec83e15d727daf97cf.png', '2018-08-02 12:13:45', '2018-08-02 12:13:45'),
(876, 125, 'b6cfb3cb62734a3c02e28da27e090b6435cc6ac6.png', '2018-08-02 12:13:45', '2018-08-02 12:13:45'),
(877, 125, '5cb956159eb113074b7a888e3c68d1422e7f3db2.png', '2018-08-02 12:13:45', '2018-08-02 12:13:45'),
(878, 126, 'e7b78c0ebe3d3ce8cab309bce7d30f2d0972e0f4.png', '2018-08-02 12:21:22', '2018-08-02 12:21:22'),
(879, 126, 'a9693f37171629c775efd3b171364efddf3484db.png', '2018-08-02 12:21:22', '2018-08-02 12:21:22'),
(880, 126, 'e4e8e60aacb39e5993d584328745b8729270faf8.png', '2018-08-02 12:21:22', '2018-08-02 12:21:22'),
(881, 126, 'ee48d22547deeebf32d0229e4983dfaea4c42a46.png', '2018-08-02 12:21:22', '2018-08-02 12:21:22'),
(885, 127, '6e45f36bc6ff6c8343202a46e79fc02aa53f0674.jpg', '2018-08-16 12:22:26', '2018-08-16 12:22:26'),
(886, 127, 'ac72b9c8b7d86e935ca49a40a8e16065bc572e05.jpg', '2018-08-16 12:22:26', '2018-08-16 12:22:26'),
(887, 127, '13585b7009a46bddade355d2d2493832c2cb3e3e.jpg', '2018-08-16 12:22:26', '2018-08-16 12:22:26'),
(888, 127, '2db72ea34df9ac42ef03f35be19785eeef5fa109.jpg', '2018-08-16 12:22:26', '2018-08-16 12:22:26'),
(889, 127, 'fe76ec7730d9c0ea2b78ef820fcaf762cf78ed86.jpg', '2018-08-16 12:22:26', '2018-08-16 12:22:26'),
(893, 129, 'd81ead2a29776f21a4b62ef31822f4e274757770.jpg', '2018-08-21 05:11:15', '2018-08-21 05:11:15'),
(894, 129, '6c064675c101dd4dd178705a5554dbb033a2e3a3.jpeg', '2018-08-21 05:11:15', '2018-08-21 05:11:15'),
(895, 129, 'd228e676af133f677b1b57890223a6c12045130a.jpg', '2018-08-21 05:11:15', '2018-08-21 05:11:15'),
(896, 129, '6283e4c6a46528089aa9631261f56dad81295768.jpg', '2018-08-21 05:12:17', '2018-08-21 05:12:17'),
(897, 129, 'de28ad87358f0bffc9e74138828d0689ee0e63a0.jpeg', '2018-08-21 05:12:17', '2018-08-21 05:12:17'),
(898, 129, '9698cdb3c5c97024e9a180fb71c4c8a346025087.jpg', '2018-08-21 05:12:17', '2018-08-21 05:12:17'),
(899, 130, '0aeaeea01ced9efded214c8fe8b38a67ca6cd069.jpg', '2018-08-21 05:27:45', '2018-08-21 05:27:45'),
(900, 130, 'b34e1abed83a8e01a87957ae73460cfa79858876.jpg', '2018-08-21 05:27:45', '2018-08-21 05:27:45'),
(901, 130, '031c7f0da4574b51377386bae725480b7e1a911b.jpg', '2018-08-21 05:27:45', '2018-08-21 05:27:45'),
(902, 131, 'bbc6dc6741f4d2fecda46596c5fed5d48482c782.jpeg', '2018-08-21 06:11:14', '2018-08-21 06:11:14'),
(903, 131, '3e3da10aaccbad5aaa0f5138e4cef4e600d7b552.jpeg', '2018-08-21 06:11:14', '2018-08-21 06:11:14'),
(904, 131, 'bf2960bf34a36b8b7fa3cadb4f03c4b2dfefed20.jpeg', '2018-08-21 06:11:14', '2018-08-21 06:11:14'),
(905, 132, '4ec25b090b2b6dff48bab784c04c585e770cea1f.jpg', '2018-08-23 05:32:39', '2018-08-23 05:32:39'),
(906, 132, '76c60d17f5d4112cc2367726268e497284810afa.jpeg', '2018-08-23 05:32:39', '2018-08-23 05:32:39'),
(907, 132, '76cb2c2dd5a7492947d71b3776d237c9bbc8952c.jpg', '2018-08-23 05:32:39', '2018-08-23 05:32:39'),
(908, 133, '79fd5729c3be931dbf210e440bbafc2dab0fe7c3.jpg', '2018-08-24 00:33:27', '2018-08-24 00:33:27'),
(909, 133, 'b913f47fb316e1566c21f15a7958901870e047c5.jpg', '2018-08-24 00:33:27', '2018-08-24 00:33:27'),
(910, 133, '96da9dee03f5b2ab92df5a090834909f941408d3.jpg', '2018-08-24 00:33:27', '2018-08-24 00:33:27'),
(911, 134, 'ed7dede6875a5ff9ccca312ab01ad1c57b51952e.jpg', '2018-08-24 01:28:22', '2018-08-24 01:28:22'),
(912, 134, '39ad0c51da67b535b05e0d75a84db5f7f13adee6.jpg', '2018-08-24 01:28:22', '2018-08-24 01:28:22'),
(913, 134, '5bb71c1f4d8adb7a375a910f7ca38e62e84e66c5.jpg', '2018-08-24 01:28:22', '2018-08-24 01:28:22'),
(914, 135, 'e9f612499f187c5b728e491d7bbdc4fed1e4e381.jpg', '2018-08-24 01:31:39', '2018-08-24 01:31:39'),
(915, 135, 'f3a35e43e49416bdbbe9f524528cb8a66d848ec9.jpg', '2018-08-24 01:31:39', '2018-08-24 01:31:39'),
(916, 135, '9b2207b5f70ea8a7bfdf6e4eb81179b166e6cf4a.jpeg', '2018-08-24 01:31:39', '2018-08-24 01:31:39'),
(917, 136, '37f7d90a1364f296badabacbe64fdaa8793cd3e8.jpg', '2018-08-24 03:51:24', '2018-08-24 03:51:24'),
(918, 136, '8519d23c9e2bf342e3218a61c4da18e4dc7ccad2.png', '2018-08-24 03:51:24', '2018-08-24 03:51:24'),
(919, 136, '19c1316be99211b14bd19c87d4b40465745f4cd4.jpg', '2018-08-24 03:51:24', '2018-08-24 03:51:24'),
(923, 138, 'f5b64605d16993cf13d623a998db751fbdc0b1e3.jpg', '2018-08-24 04:56:18', '2018-08-24 04:56:18'),
(924, 138, '7098173f818c61f0823d3d20603fef488a891396.jpg', '2018-08-24 04:56:18', '2018-08-24 04:56:18'),
(925, 138, '8aef183e848f8e4ef59fcdd6957fb01d244db346.jpg', '2018-08-24 04:56:18', '2018-08-24 04:56:18'),
(926, 139, 'dddc23d67494947164bddfd90dda4658c3a9ef71.png', '2018-08-24 06:44:39', '2018-08-24 06:44:39'),
(927, 139, '1ac5dcedccecfd8339ee085e72ddd9ac192a8404.jpg', '2018-08-24 06:44:39', '2018-08-24 06:44:39'),
(928, 139, '397d3ca84d614876ad1abd3a5acfc9db9f671325.jpg', '2018-08-24 06:44:39', '2018-08-24 06:44:39'),
(929, 140, '0ac888e65064e69f21b0b8d015357e1e22794927.jpg', '2018-08-28 00:11:07', '2018-08-28 00:11:07'),
(930, 140, '20c5b5fd513db3f57e0db3162c49394d84a20dff.jpg', '2018-08-28 00:11:07', '2018-08-28 00:11:07'),
(931, 140, '3efa245cacc0d487881b96c77d84b16a1eaded64.jpg', '2018-08-28 00:11:07', '2018-08-28 00:11:07'),
(932, 141, 'f7bf0f53cc25024bb812c433115f77dd6c0ab4a6.jpg', '2018-08-28 00:26:11', '2018-08-28 00:26:11'),
(933, 141, '97ba5158fe1ba73b87081c08cb7c50d9c41f0e54.jpg', '2018-08-28 00:26:11', '2018-08-28 00:26:11'),
(934, 141, '56c6e4282ccee49d26c382cee38df01d9fc11e48.jpg', '2018-08-28 00:26:11', '2018-08-28 00:26:11'),
(935, 142, 'cf1f7415fb084110c16eeb6a0cb7cd759b809b98.jpg', '2018-08-28 03:45:33', '2018-08-28 03:45:33'),
(936, 142, 'ce82b0752e26df02477ec7dc50c4a44f5240eec1.jpg', '2018-08-28 03:45:33', '2018-08-28 03:45:33'),
(937, 142, '071d3c9573bdccc9712a12237c94a24d68e6ae09.jpg', '2018-08-28 03:45:33', '2018-08-28 03:45:33'),
(938, 143, '721e488c4ab6657a880bf386f5c3bac10743eaed.png', '2018-08-28 04:39:54', '2018-08-28 04:39:54'),
(939, 143, '583f7c4c156cb9ccbf759605050fbac3d271e1c0.png', '2018-08-28 04:39:54', '2018-08-28 04:39:54'),
(940, 143, '9271c141cf42628aae23be8cc12342699782b80b.png', '2018-08-28 04:39:54', '2018-08-28 04:39:54'),
(941, 143, 'fdb73dae2e05de2f667dc0a0342ae546f4e97376.png', '2018-08-28 04:39:54', '2018-08-28 04:39:54'),
(942, 143, '3b9d9fdac56f39de3db7dcb06078317f95af144b.png', '2018-08-28 04:39:54', '2018-08-28 04:39:54'),
(943, 143, '76c19735b79edb67590b778d7795ce9440db381f.jpg', '2018-08-28 04:39:54', '2018-08-28 04:39:54'),
(944, 144, 'f10e4eef49725bd614b6139f427a05996a662334.png', '2018-08-28 05:15:36', '2018-08-28 05:15:36'),
(945, 144, '453df40b94d80cea2ad6de9eb932caa7e281fc39.png', '2018-08-28 05:15:36', '2018-08-28 05:15:36'),
(946, 144, '94974c04a37d203927e01fbdec6cb2316ed39a40.png', '2018-08-28 05:15:36', '2018-08-28 05:15:36'),
(947, 145, '8792ad7249bdbb7d9bb3037c61e901731127e465.jpg', '2018-08-28 05:24:26', '2018-08-28 05:24:26'),
(948, 145, '475ca741295b8488a007d8a65a2062d4508b5153.jpg', '2018-08-28 05:24:26', '2018-08-28 05:24:26'),
(949, 145, 'f586940c4c71b7b0e3d439f2b757a250cd9014c2.jpg', '2018-08-28 05:24:26', '2018-08-28 05:24:26'),
(950, 146, '5dc84b6369583e39d9bdbdb239353f520b26aa9a.png', '2018-08-28 05:33:13', '2018-08-28 05:33:13'),
(951, 146, '16333b7b3dfd436a307824e5d0718bb75bdbf17e.png', '2018-08-28 05:33:13', '2018-08-28 05:33:13'),
(952, 146, '7407c31927f66e3d838ed6d5ef7bd2a12968a429.jpg', '2018-08-28 05:33:13', '2018-08-28 05:33:13'),
(953, 146, '36915f692501c731d432c244441439b10d6b7b23.png', '2018-08-28 05:33:13', '2018-08-28 05:33:13'),
(954, 147, 'dcbd70990c6a0a2917a68626f5e025111edb3224.jpg', '2018-08-28 05:40:23', '2018-08-28 05:40:23'),
(955, 147, '1f6fd75b3bde47d4ae0ff53787557562ca2349fe.jpg', '2018-08-28 05:40:23', '2018-08-28 05:40:23'),
(956, 147, '873e32106977805191fa46fc28d654f76fa3e2d5.jpg', '2018-08-28 05:40:23', '2018-08-28 05:40:23'),
(957, 148, 'b39f4649dad771029d310caeaaac48f3f9cb6f4d.png', '2018-08-28 23:06:29', '2018-08-28 23:06:29'),
(958, 148, 'a17985406ed29f2fdd5ac2ba25de97c9b0d7dbe9.png', '2018-08-28 23:06:45', '2018-08-28 23:06:45'),
(959, 148, '78f33a42ce089ca680f2db7dd88e1af0e46aab90.png', '2018-08-28 23:06:46', '2018-08-28 23:06:46'),
(960, 149, '504d20bf8995d6183a496ff1d77cf9931209f230.jpg', '2018-08-29 05:17:06', '2018-08-29 05:17:06');
INSERT INTO `property_images` (`id`, `property_id`, `image`, `created_at`, `updated_at`) VALUES
(961, 149, '8090df13ac2f77536299d529454ea2a2a04b514d.jpeg', '2018-08-29 05:17:06', '2018-08-29 05:17:06'),
(962, 149, '90547045bbfb7e584036d6be0e18055836b411f5.jpg', '2018-08-29 05:17:06', '2018-08-29 05:17:06'),
(963, 151, '4edc2cb0f286fa6cc83b1d69e7883d9c7e4be8df.jpg', '2018-08-29 05:34:54', '2018-08-29 05:34:54'),
(965, 151, '18df30aaa7c51384e20be2979d904b7314c3a4b9.jpg', '2018-08-29 05:34:54', '2018-08-29 05:34:54'),
(966, 151, 'a43230541e6690d9da28b90f52374b0e851612c5.jpg', '2018-08-29 05:34:54', '2018-08-29 05:34:54'),
(967, 151, 'f0cbccc95e64575e3106db5324f4bee4bea429d1.jpg', '2018-08-29 05:34:54', '2018-08-29 05:34:54'),
(969, 151, '6529da6644642a1ddf5e6fba3f40f63414e4a3b1.jpg', '2018-08-29 05:45:35', '2018-08-29 05:45:35'),
(970, 151, '237d2b378ead505fd5a05128f832a1eb5366aff1.jpg', '2018-08-29 05:45:35', '2018-08-29 05:45:35'),
(971, 152, 'd5d3b0ed0066686d110f5d8d18a217b8746de74a.jpg', '2018-08-29 00:15:51', '2018-08-29 00:15:51'),
(972, 152, 'e3dddb35a22d510cf67092ccc41aca8b21a92f51.jpeg', '2018-08-29 00:15:51', '2018-08-29 00:15:51'),
(973, 152, '3fe426ee829f217eed1e4f185610358573673b1d.jpg', '2018-08-29 00:15:51', '2018-08-29 00:15:51'),
(974, 152, 'da508e8d846ca415d713dab84b91f3651fa62a17.jpg', '2018-08-29 00:15:51', '2018-08-29 00:15:51'),
(975, 152, '71aa4c9ee90cecc7b055f048ebbf6bcd5f669451.jpeg', '2018-08-29 00:15:51', '2018-08-29 00:15:51'),
(976, 152, '28d83f8c87e3f58fc76e93e684cfaa6a51be8376.jpg', '2018-08-29 00:15:51', '2018-08-29 00:15:51'),
(977, 150, '80c350538399992186110235f50125cc1cb8b473.png', '2018-08-29 01:22:03', '2018-08-29 01:22:03'),
(978, 150, '31e9b1c2e8bf93bb13a64b3a1c106d6b7c799934.png', '2018-08-29 01:22:03', '2018-08-29 01:22:03'),
(979, 150, '768e476ea6fd6b2a0e69c9aadf2df30e92421e1d.png', '2018-08-29 01:22:03', '2018-08-29 01:22:03'),
(980, 154, 'e2775598572ce4346dcfee517db371b13f3d53c5.png', '2018-08-29 01:56:12', '2018-08-29 01:56:12'),
(981, 154, '6210a8b80bbc85338e26127f44b05047cb624ab1.png', '2018-08-29 01:56:12', '2018-08-29 01:56:12'),
(982, 154, 'aa278cf99f406e69be50eac6b078bc3e6ff793c3.png', '2018-08-29 01:56:12', '2018-08-29 01:56:12'),
(983, 154, 'a9dd88709751bd05fc20b0a5def918510e264423.png', '2018-08-29 01:56:12', '2018-08-29 01:56:12'),
(984, 155, '31cf6c4695f6c11c5834f0047d65f10472f0d910.jpg', '2018-08-29 03:19:46', '2018-08-29 03:19:46'),
(985, 155, 'c005689c568e2dc78d9925e605af4f1501195ac7.jpg', '2018-08-29 03:19:46', '2018-08-29 03:19:46'),
(986, 155, '127aa16f06271b164c15ec7c0a9fb1d90c35646d.jpg', '2018-08-29 03:19:46', '2018-08-29 03:19:46'),
(987, 154, '15355341245b86642cc9017.png', '2018-08-29 03:45:24', '2018-08-29 03:45:24'),
(991, 157, '91b8bb8812f5ccd097b6d7bb7317e04d0f2269bc.jpg', '2018-08-29 04:22:01', '2018-08-29 04:22:01'),
(992, 157, '9de56c954c636f7c50384aaf585ecf9db0f91872.jpg', '2018-08-29 04:22:01', '2018-08-29 04:22:01'),
(993, 157, '86d956f70cfd47111278c8e32be49952eec0f200.jpg', '2018-08-29 04:22:01', '2018-08-29 04:22:01'),
(994, 158, '5ba419271d1096b902d28c22f8c083b70e8fcc57.jpg', '2018-08-29 04:28:29', '2018-08-29 04:28:29'),
(995, 158, 'd68db534c863fb7243198266109975ecab9cd2f2.jpg', '2018-08-29 04:28:29', '2018-08-29 04:28:29'),
(996, 158, 'b7d91c7b863a98516dce0d3ffb295e0c88d57097.jpg', '2018-08-29 04:28:29', '2018-08-29 04:28:29'),
(997, 159, '15355375785b8671aaa6e74.jpg', '2018-08-29 04:42:58', '2018-08-29 04:42:58'),
(998, 159, '15355375785b8671aab0e4e.jpg', '2018-08-29 04:42:58', '2018-08-29 04:42:58'),
(999, 159, '15355375785b8671aab5b24.jpg', '2018-08-29 04:42:58', '2018-08-29 04:42:58'),
(1000, 153, '82788063a36ac8161ae482439d48cc1ff72d7a88.jpg', '2018-08-30 03:30:40', '2018-08-30 03:30:40'),
(1001, 153, '31dba242b9109517076e3a8de62edd5f58a908f9.jpg', '2018-08-30 03:30:40', '2018-08-30 03:30:40'),
(1002, 153, '908cde86d654b46ecd56a02168e5c0a680c6e952.jpg', '2018-08-30 03:30:40', '2018-08-30 03:30:40'),
(1003, 160, 'fa0819dc7aa9a2c353653c202fe824956c19a5d7.png', '2018-08-30 04:08:15', '2018-08-30 04:08:15'),
(1004, 160, '0bca5047109157846ec804a07f039b6ffc59b9a1.png', '2018-08-30 04:08:15', '2018-08-30 04:08:15'),
(1005, 160, '35356ec458d86d9ab0d3d0af29efb6b1fd7ead0f.png', '2018-08-30 04:08:15', '2018-08-30 04:08:15'),
(1006, 160, '4bef9f0b6f280bd8f73709d1136dd986ed0f37bf.png', '2018-08-30 04:08:15', '2018-08-30 04:08:15'),
(1007, 161, '8695961468a952320b20db4f10b0a032ad7daf5f.png', '2018-08-30 04:23:32', '2018-08-30 04:23:32'),
(1008, 161, '9f603d1cd9c4b932c0ab060eeddc3f1540613ba4.png', '2018-08-30 04:23:32', '2018-08-30 04:23:32'),
(1009, 161, 'b36bcfbf5aedd455b912d8d1b53e2734f6fe19e0.png', '2018-08-30 04:23:32', '2018-08-30 04:23:32'),
(1010, 161, 'a5982fcc13137a9411081f63c4fc925032c1213f.png', '2018-08-30 04:23:32', '2018-08-30 04:23:32'),
(1011, 161, 'cda0e9ca783ca068f5ae5ea72a9f7e629b44d4e2.png', '2018-08-30 04:23:32', '2018-08-30 04:23:32'),
(1012, 162, '15356238615b87c2b5a2eba.png', '2018-08-30 04:41:01', '2018-08-30 04:41:01'),
(1013, 162, '15356238615b87c2b5addb7.png', '2018-08-30 04:41:01', '2018-08-30 04:41:01'),
(1014, 162, '15356238615b87c2b5b0388.png', '2018-08-30 04:41:01', '2018-08-30 04:41:01'),
(1015, 162, '15356238615b87c2b5b2f26.png', '2018-08-30 04:41:01', '2018-08-30 04:41:01'),
(1016, 162, '15356238615b87c2b5b7d79.png', '2018-08-30 04:41:01', '2018-08-30 04:41:01'),
(1017, 162, '15356238615b87c2b5ba701.jpg', '2018-08-30 04:41:01', '2018-08-30 04:41:01'),
(1018, 163, '815576141d3407fb6f76b5bcd92b830a56523b3f.jpeg', '2018-08-30 23:00:18', '2018-08-30 23:00:18'),
(1019, 163, '6eea9a741d2653487263cd9bd2997324d2faafac.jpg', '2018-08-30 23:00:18', '2018-08-30 23:00:18'),
(1020, 163, 'b29d25a57fdcaaba034d31f4f9e8b8c4f9199197.jpg', '2018-08-30 23:00:18', '2018-08-30 23:00:18'),
(1021, 165, '7467110850db0a8866ee6c8d6772a52e8831cda5.jpg', '2018-09-03 04:56:46', '2018-09-03 04:56:46'),
(1022, 165, 'e169dd03680b4a4dc798ca1da53546fbadcb56ea.jpg', '2018-09-03 04:56:46', '2018-09-03 04:56:46'),
(1023, 165, 'b1d38857e13c6577f9a00f572a2b47e9f44445c9.jpg', '2018-09-03 04:56:46', '2018-09-03 04:56:46'),
(1024, 164, '94d969e373e844a758ce94597022e3a5a0052fdc.jpg', '2018-09-03 10:07:38', '2018-09-03 10:07:38'),
(1025, 164, 'b95415fadfc0c7ab2afbb587887848d42011c69a.jpg', '2018-09-03 10:07:38', '2018-09-03 10:07:38'),
(1026, 164, 'a5dc63ebcf046a7082c252c26377666a5436d868.jpg', '2018-09-03 10:07:38', '2018-09-03 10:07:38'),
(1027, 166, 'fbb3740fa548f75ee202d912aaa3b86e7088eaaf.jpg', '2018-09-03 10:36:07', '2018-09-03 10:36:07'),
(1028, 166, 'a8988dc538ed93d1bd4d94a2c7a5e92c29a50e8f.jpg', '2018-09-03 10:36:07', '2018-09-03 10:36:07'),
(1029, 166, 'c28aaff520a3cfca4c10e60a280dca390a803531.jpg', '2018-09-03 10:36:07', '2018-09-03 10:36:07'),
(1030, 167, '0db906f258f14702080d879c6bba393d63346ff4.jpg', '2018-09-04 12:43:21', '2018-09-04 12:43:21'),
(1031, 167, '64332951b7444d083672a47b97de1940c16002c2.jpg', '2018-09-04 12:43:21', '2018-09-04 12:43:21'),
(1032, 167, '809da3b617e0aac366fe6a51fb867b177b55f0a3.jpeg', '2018-09-04 12:43:21', '2018-09-04 12:43:21'),
(1033, 167, '3d5372884044c9427bfd2f0045c980359e4f89a1.jpg', '2018-09-04 12:44:46', '2018-09-04 12:44:46'),
(1034, 167, 'e4924f6b0782c0c4db795ba12299df38cacfe8f8.jpg', '2018-09-04 12:44:46', '2018-09-04 12:44:46'),
(1035, 167, 'e3132d2765708c6ff91c8dd4eee7179fbf7046f4.jpeg', '2018-09-04 12:44:46', '2018-09-04 12:44:46'),
(1036, 167, 'e0e87f20b642eb398bf628d0716ff83f8ef42f0b.jpg', '2018-09-04 12:45:23', '2018-09-04 12:45:23'),
(1037, 167, '15424bb5ea905967a305c9b3048f4468fc44d438.jpg', '2018-09-04 12:45:23', '2018-09-04 12:45:23'),
(1038, 167, '290954ad1e80e7f981e521029c95e51eefa910b9.jpeg', '2018-09-04 12:45:23', '2018-09-04 12:45:23'),
(1040, 168, '5d3a6207bdb42fc72cdff2928f9ac576e0dd9580.jpeg', '2018-09-04 12:56:07', '2018-09-04 12:56:07'),
(1041, 168, '507d2c0bf6d6a51a1ed950cd3ef7625c9732eff5.jpg', '2018-09-04 12:56:07', '2018-09-04 12:56:07'),
(1042, 169, '960e7293a51bbfb66504a0ee2305c4556e900003.jpg', '2018-09-04 15:24:20', '2018-09-04 15:24:20'),
(1043, 169, 'c3d10d3da9310f291de44c85e0c871806f9c36f7.jpg', '2018-09-04 15:24:20', '2018-09-04 15:24:20'),
(1044, 169, 'eb52da93a8ad1ff3ee6feb9beb11d5691c5873b7.jpg', '2018-09-04 15:24:20', '2018-09-04 15:24:20'),
(1045, 169, '6210fa2090d3ff3aa8ca6601f25c39937a6f3706.jpg', '2018-09-04 15:24:20', '2018-09-04 15:24:20'),
(1046, 169, '0d571ac0e79b21899293fcd64169e8b398a8ca98.jpg', '2018-09-04 15:24:20', '2018-09-04 15:24:20'),
(1048, 170, 'c4e026ba4251c9ea0f18804e8bacf9b58e73553a.jpg', '2018-09-05 13:12:40', '2018-09-05 13:12:40'),
(1049, 170, 'cd939fa247772084fecf04d20d4b7f195ce347bf.jpg', '2018-09-05 13:12:40', '2018-09-05 13:12:40'),
(1050, 171, 'a311a833bcda4ed0becefc3fff266215b7c55c7d.jpg', '2018-09-05 13:20:33', '2018-09-05 13:20:33'),
(1051, 171, '5336a471873aa956c049169236024c08281ea39e.jpg', '2018-09-05 13:20:34', '2018-09-05 13:20:34'),
(1052, 171, '351a0c9de3922119cb0dab2e48cc6d1fab38f123.jpg', '2018-09-05 13:20:34', '2018-09-05 13:20:34'),
(1055, 170, '16acad6acfbdb354236ddc80cbde6c3fc12c1bab.jpg', '2018-09-06 05:49:52', '2018-09-06 05:49:52'),
(1060, 170, '02a0d927ee2571f39ebe7a066a9f8a94be5d9af7.jpg', '2018-09-06 06:08:35', '2018-09-06 06:08:35'),
(1061, 170, '6112fedb2b75e64cd213b92c3000819394903e2a.jpg', '2018-09-06 06:08:57', '2018-09-06 06:08:57'),
(1062, 175, '3ffbe654cdb23a8fb4e2c1e5470911f115e956df.gif', '2018-09-07 06:38:07', '2018-09-07 06:38:07'),
(1063, 175, '501b4c71421116d1d1bffb0755167edcd3514c85.png', '2018-09-07 06:39:48', '2018-09-07 06:39:48'),
(1064, 175, '7deba4a40c1cda1de441c4aaf6f8a60583da5cde.jpg', '2018-09-07 06:39:57', '2018-09-07 06:39:57'),
(1065, 175, '7caf9168561d08cd8faaeacf3934f14587eb4349.jpg', '2018-09-07 06:39:59', '2018-09-07 06:39:59'),
(1066, 177, 'f9e84dcffc65a8f34780a5c39b45283506613326.jpg', '2018-09-07 06:56:12', '2018-09-07 06:56:12'),
(1070, 177, 'f656f02df7ef62830ee3b9af98b6822e11aea37e.jpg', '2018-09-07 06:59:37', '2018-09-07 06:59:37'),
(1071, 177, '0cc52ecfc45d804e293d4cde4596b49da17a14ba.jpg', '2018-09-07 06:59:39', '2018-09-07 06:59:39'),
(1072, 177, 'c03684bfc8158b212b6a74449f4a3c7924cacd9e.jpg', '2018-09-07 06:59:42', '2018-09-07 06:59:42'),
(1073, 177, '35ff57245752e7164362d37f522c24d3fa9ae3d7.jpg', '2018-09-07 06:59:46', '2018-09-07 06:59:46'),
(1074, 178, '609598e677e1133a29fb1824b49e1306be9ee3bc.jpg', '2018-09-07 07:09:49', '2018-09-07 07:09:49'),
(1075, 178, 'a39b6c3ade3579d5ddbde604d7fb577ead6a1336.jpg', '2018-09-07 07:09:52', '2018-09-07 07:09:52'),
(1076, 178, 'e67f09a4d2af6f882b04e359da8d5535b36daee0.jpg', '2018-09-07 07:09:57', '2018-09-07 07:09:57'),
(1077, 178, '0e65b28ec463963095de7970471e49ab31a777f2.jpg', '2018-09-07 07:10:23', '2018-09-07 07:10:23'),
(1078, 178, '9c4101b65e550550aafdc3d09f50278bb7747cac.jpg', '2018-09-07 07:10:27', '2018-09-07 07:10:27'),
(1079, 178, 'a0eb2cb315243975387b1e0417fd04f173c59207.jpg', '2018-09-07 07:10:33', '2018-09-07 07:10:33'),
(1080, 178, 'cd318a9251ba5fd46323ee5330487ea0e0a07860.jpg', '2018-09-07 07:10:37', '2018-09-07 07:10:37'),
(1081, 178, '67da435baf5c0d24f51c5b24e87bc14091eab9c8.jpg', '2018-09-07 07:10:42', '2018-09-07 07:10:42'),
(1082, 178, '7b070b815c06a48bf4c7f631c44fa5d9513487e0.jpg', '2018-09-07 07:10:50', '2018-09-07 07:10:50'),
(1083, 179, '50da55cfc936c01fd14f0e6f8d25b4042ed71141.jpg', '2018-09-07 07:14:03', '2018-09-07 07:14:03'),
(1084, 179, '1b279fe066ab9736d46eafac5175fb37c81a1344.jpg', '2018-09-07 07:14:07', '2018-09-07 07:14:07'),
(1085, 179, '8116667ee61f1edf43dfbffe7a20b56655ad7988.jpg', '2018-09-07 07:14:16', '2018-09-07 07:14:16'),
(1086, 179, '1c4ca721d937a5e9cb46d5d7efe8755ed6ef5b22.jpg', '2018-09-07 07:14:22', '2018-09-07 07:14:22'),
(1087, 179, '3ce5fdfdff8dc845d2d0b26dcb8e91951cfb682a.jpg', '2018-09-07 07:14:51', '2018-09-07 07:14:51'),
(1088, 179, '97e81e62d0c1cc2b7deadac67633fb0ac0dfa467.jpg', '2018-09-07 07:14:55', '2018-09-07 07:14:55'),
(1089, 179, '74f2bf96555326de35c3f1ceabc37ab3de5a3240.jpg', '2018-09-07 07:15:06', '2018-09-07 07:15:06'),
(1091, 180, '99761057e9c65694005ccee1098eac4039dbbe85.jpg', '2018-09-07 07:21:14', '2018-09-07 07:21:14'),
(1092, 180, '5308552d379526c7e845430854b3424555f4b76f.png', '2018-09-07 07:21:16', '2018-09-07 07:21:16'),
(1093, 180, '62034dcca75ae0119816c552d4a6d3942fbf1c1d.jpg', '2018-09-07 07:21:18', '2018-09-07 07:21:18'),
(1094, 180, '7201a94937ca270c3a0894137aea75d06583d54b.jpg', '2018-09-07 07:21:19', '2018-09-07 07:21:19'),
(1095, 180, 'c80c624a713acb85542c384a9f6bcda262d6ba56.jpg', '2018-09-07 07:22:25', '2018-09-07 07:22:25'),
(1098, 180, '4c43f43704c19c2dbc1a6d1799cbad23f441b4a5.jpg', '2018-09-07 07:25:58', '2018-09-07 07:25:58'),
(1106, 181, 'd233d4da1b2a7e301bcd4e124943844e3b6cc6f9.jpg', '2018-09-07 08:43:38', '2018-09-07 08:43:38'),
(1111, 181, '9c95fc316cd370f799726007f64528999297719d.jpg', '2018-09-07 08:49:03', '2018-09-07 08:49:03'),
(1124, 181, 'cb4461d2ae34ab1358ede75a38e04b24012ee2e9.jpg', '2018-09-07 08:59:45', '2018-09-07 08:59:45'),
(1127, 182, 'e6dcf72ed8255a2f3f474050166f035ce5b3f567.jpg', '2018-09-07 09:04:58', '2018-09-07 09:04:58'),
(1128, 182, '1dd1e53670dea5345cff03d527ff6ed1cbed67f7.jpg', '2018-09-07 09:04:59', '2018-09-07 09:04:59'),
(1129, 182, '8d80935a19edd0008610df77d2237d092f34b3aa.jpg', '2018-09-07 09:05:01', '2018-09-07 09:05:01'),
(1130, 183, '469e7fc8dde7df4405289104ca188ef674e7c555.jpg', '2018-09-07 09:11:52', '2018-09-07 09:11:52'),
(1131, 183, '39e295134b93bbe5832c3846932d00047dda5f4a.jpg', '2018-09-07 09:11:54', '2018-09-07 09:11:54'),
(1132, 183, '341216db805d53d87f7b18ab58eb02eb44b80f62.jpg', '2018-09-07 09:11:56', '2018-09-07 09:11:56'),
(1137, 184, '745236d43bf3b11031078cfa8fbf084fbf0e671f.jpg', '2018-09-07 10:38:07', '2018-09-07 10:38:07'),
(1139, 184, '823dbdec02a5877cd289677c43ab4afee794d5b7.jpg', '2018-09-07 10:38:16', '2018-09-07 10:38:16'),
(1140, 184, 'a5bb7c1cdf1ae3883ebb7b3b605ad3c1619132ed.jpg', '2018-09-07 10:39:47', '2018-09-07 10:39:47'),
(1141, 185, '1d4a3f1186069f1fcd1aa9d8748fc9c916f4ec93.jpg', '2018-09-07 10:46:03', '2018-09-07 10:46:03'),
(1142, 185, '5e7e7f83889fc0906af362a8441fc1affc050c80.jpg', '2018-09-07 10:46:05', '2018-09-07 10:46:05'),
(1143, 185, '55dcaa06b8d73646eb9eef80ffc775f64d2a6094.jpg', '2018-09-07 10:46:07', '2018-09-07 10:46:07'),
(1145, 185, '135268480587d82076adadb1e33ddd0056d172a9.jpg', '2018-09-07 10:48:05', '2018-09-07 10:48:05'),
(1146, 185, '00520ef82b334fe1c154c05672382483b37007ae.jpeg', '2018-09-07 10:48:07', '2018-09-07 10:48:07'),
(1147, 185, 'f6e0665368fe695bacb15e3017b6530f6c1df1e9.jpg', '2018-09-07 10:48:13', '2018-09-07 10:48:13'),
(1149, 186, '4c29b7f1029c6fe1adb0a1bd68910f7927a00d55.jpeg', '2018-09-07 10:58:46', '2018-09-07 10:58:46'),
(1150, 186, '2fea961baf1dcf7a4056f3bb1e860503caaf7fe3.jpg', '2018-09-07 10:58:47', '2018-09-07 10:58:47'),
(1153, 187, '04a17b245a19c8f1fc8919899d034a6df8f96074.jpg', '2018-09-11 04:33:29', '2018-09-11 04:33:29'),
(1154, 187, 'd68c3007fc85a12834ae9ca027f6eef45483eb85.jpg', '2018-09-11 04:33:31', '2018-09-11 04:33:31'),
(1155, 187, '745a88a143e1e5db4634fbb8d961b2ace83617a4.jpg', '2018-09-11 04:33:33', '2018-09-11 04:33:33'),
(1156, 187, '80e76065198c77d8840b2e7cb414a39c8960b64c.jpg', '2018-09-11 04:33:36', '2018-09-11 04:33:36'),
(1157, 187, '312a7d16309ef575e854086f6a58c5cf66577a86.jpg', '2018-09-11 04:33:36', '2018-09-11 04:33:36'),
(1158, 187, 'be09e997db24553a45ef0460f8e374f6a8f58f1a.jpg', '2018-09-11 04:33:37', '2018-09-11 04:33:37'),
(1159, 187, 'f147247bd762c5807cc72abf0036f2542e0c1f15.jpg', '2018-09-11 04:33:37', '2018-09-11 04:33:37'),
(1160, 187, '200d689910e6d9bd359f1428cc7d85acdcea18f0.jpg', '2018-09-11 04:33:37', '2018-09-11 04:33:37'),
(1161, 189, 'd4aefc03493731265808576deef51c43593a0104.jpeg', '2018-09-11 04:35:09', '2018-09-11 04:35:09'),
(1162, 189, '81368a0396bfb512a8a42aa86fa77ac2e7e6165c.jpg', '2018-09-11 04:35:11', '2018-09-11 04:35:11'),
(1163, 189, 'e3ca3502b76fbfa61c0af72f79b6f5f1be6a4cd6.jpg', '2018-09-11 04:35:13', '2018-09-11 04:35:13'),
(1164, 189, 'ab7824a5af532e05cdd6fe0d40001677d8b9d82a.jpeg', '2018-09-11 04:35:21', '2018-09-11 04:35:21'),
(1165, 189, '3cc8f08210d6521c3b7065381c1ef3694552bcc0.jpg', '2018-09-11 04:35:21', '2018-09-11 04:35:21'),
(1166, 189, '210be370b02f8372ea61290e03a4ba0173466f26.jpg', '2018-09-11 04:35:21', '2018-09-11 04:35:21'),
(1175, 190, '170a64128dc7946860636865c8c4f4a1172b93c0.jpg', '2018-09-11 12:49:44', '2018-09-11 12:49:44'),
(1176, 190, '656af506b2e707bf23bd5ae74d37f6481f44b0d1.jpg', '2018-09-11 12:49:46', '2018-09-11 12:49:46'),
(1177, 190, 'a7feac9af956225e9343c80b6a484cdeb3c89007.jpg', '2018-09-11 12:49:48', '2018-09-11 12:49:48'),
(1178, 191, 'd9cb80ac419266320c0b0eaeaefcf53e5626aa93.jpg', '2018-09-11 12:53:46', '2018-09-11 12:53:46'),
(1179, 191, '1ce931bb600dbdb4ed031d3232b914ae885b9a01.jpg', '2018-09-11 12:53:48', '2018-09-11 12:53:48'),
(1180, 191, '243c3b711ae44b51641cadb0012df9fe6a7492f4.jpg', '2018-09-11 12:53:50', '2018-09-11 12:53:50'),
(1181, 192, 'c2f0c231f7613ec7014bf892f79b30cab113352a.jpg', '2018-09-12 06:37:49', '2018-09-12 06:37:49'),
(1182, 192, 'ccc198ab960a1305fd4520ea6b3f897048e41191.jpg', '2018-09-12 06:37:51', '2018-09-12 06:37:51'),
(1183, 192, '1b3694f45dc9494be7982a596ae2f7f57a974e8b.jpeg', '2018-09-12 06:37:53', '2018-09-12 06:37:53'),
(1184, 188, '2b5d0c7fcfeddcb88b0d5030974bf9b9a31fbb46.jpg', '2018-09-14 06:45:50', '2018-09-14 06:45:50'),
(1191, 188, '490ce4a9528019db5f664bc159c8b90985a2fb59.jpg', '2018-09-17 11:05:06', '2018-09-17 11:05:06'),
(1192, 188, '1a1a4e031fb7fd65ee3b45c11fef7e472a3d28df.jpg', '2018-09-17 11:05:14', '2018-09-17 11:05:14'),
(1193, 188, 'f32c17065acadb1e987476136ab659b1a9c8774e.jpg', '2018-09-17 11:05:20', '2018-09-17 11:05:20'),
(1194, 193, '25e15bc1f4c147c5796a8c2123fee8d4797eae0f.jpg', '2018-09-18 09:13:54', '2018-09-18 09:13:54'),
(1196, 193, '3510691a98faf6d630af1dfccd00a01cdb4406d4.jpg', '2018-09-18 09:14:03', '2018-09-18 09:14:03'),
(1197, 193, '2a19320025f1dc855dcb96d20533e204b1ef70b9.jpg', '2018-09-18 09:14:05', '2018-09-18 09:14:05'),
(1198, 174, '8f41e027c39034f9a5f0f59561005e6254ff9949.jpg', '2018-09-18 13:19:42', '2018-09-18 13:19:42'),
(1200, 174, 'aeaedfd557f3773c18eb2b45abc51976a3171594.jpg', '2018-09-18 13:19:52', '2018-09-18 13:19:52'),
(1201, 174, 'da8c3f84baed8d7e65e3bfd9d1230bdd46c1e2bd.jpg', '2018-09-18 13:19:56', '2018-09-18 13:19:56'),
(1202, 194, '148d02834b29befbd0dcb4ed74c24d6e85fb1da0.png', '2018-09-21 08:52:52', '2018-09-21 08:52:52'),
(1203, 194, '71f8e0002aa21fc6d3d8499129ca493a4b8d688c.jpg', '2018-09-21 08:52:55', '2018-09-21 08:52:55'),
(1204, 194, 'ab49a5a9785fc05d21da3c649a16ac23dc2faf8f.jpg', '2018-09-21 08:53:01', '2018-09-21 08:53:01'),
(1205, 195, '0f921644caa64581187127bc70240b3c3ef3cd03.jpg', '2018-09-21 08:55:15', '2018-09-21 08:55:15'),
(1206, 195, '0699ca5be82bd926774b670b996fa9aba91c6fb4.jpg', '2018-09-21 08:55:33', '2018-09-21 08:55:33'),
(1207, 195, '14fea94b6b03e215ebc230468f2ccc2669de0fd6.jpg', '2018-09-21 08:55:51', '2018-09-21 08:55:51'),
(1208, 196, '1a4e150824e4d96c8b08befae869b6418c04e617.jpg', '2018-09-21 08:58:19', '2018-09-21 08:58:19'),
(1209, 196, 'c62a8261eb679174efbc9fd976b8f675d1fec5b6.jpg', '2018-09-21 08:58:30', '2018-09-21 08:58:30'),
(1210, 196, '9544366e512e61ebd2bcb22a5fad23f0bf853268.jpg', '2018-09-21 08:58:33', '2018-09-21 08:58:33'),
(1211, 197, 'eea5a3dcde22296775f74ea3c91e8d27be98085e.jpg', '2018-09-21 09:00:09', '2018-09-21 09:00:09'),
(1212, 197, '53f373cc87ec1821ced5e22ebc4cba5f4f55a755.jpg', '2018-09-21 09:00:15', '2018-09-21 09:00:15'),
(1213, 197, '756075dea265135efe564573904fefa77e27a6c2.jpg', '2018-09-21 09:00:19', '2018-09-21 09:00:19'),
(1214, 198, '582c15cade94405f54dc3c56b0fe7879d8ea94ee.jpg', '2018-09-27 14:00:23', '2018-09-27 14:00:23'),
(1215, 198, 'a4eb7b02044de32e2226d8e5d6f04dd496d6a692.png', '2018-09-27 14:00:25', '2018-09-27 14:00:25'),
(1216, 198, '8f8189c2b457d5cae3e331bf89ebe07f63276506.jpg', '2018-09-27 14:00:30', '2018-09-27 14:00:30'),
(1218, 199, 'b5d19b4f79d3dbb8c3993a6a91e2242782bf1b94.jpg', '2018-09-29 05:27:37', '2018-09-29 05:27:37'),
(1219, 199, '22c4ca6695eb2f2643c38c4717d991c741344ebf.jpg', '2018-09-29 05:27:40', '2018-09-29 05:27:40'),
(1220, 199, '494ca042bfe4136754bdb15dc53f1300cc494d62.jpg', '2018-09-29 05:27:43', '2018-09-29 05:27:43'),
(1221, 200, '01f57263f98a5e728f6dfb9ab1e0a507c0b152e6.jpg', '2018-10-22 13:08:44', '2018-10-22 13:08:44'),
(1222, 200, 'cbfa804c2e6ec70819af45f705f29407b53b2c28.jpg', '2018-10-22 13:08:49', '2018-10-22 13:08:49'),
(1223, 200, 'f587e1979e4d25446936bb72e831edf526bd7d17.jpg', '2018-10-22 13:08:54', '2018-10-22 13:08:54'),
(1224, 201, 'ad3e968947a0e7aedcdd1c695aca294c0b36ad5b.jpg', '2018-10-23 09:02:37', '2018-10-23 09:02:37'),
(1225, 201, 'd02623153a50f8d30e3c8c52497861768a10d4df.jpg', '2018-10-23 09:02:50', '2018-10-23 09:02:50'),
(1226, 201, '2287f40e1ce8c6d8036d94a38222a068c66633f3.jpg', '2018-10-23 09:02:54', '2018-10-23 09:02:54'),
(1227, 202, 'adadbec99318af8b34437134f83634431e926ac3.jpg', '2018-10-23 10:06:27', '2018-10-23 10:06:27'),
(1228, 202, '86bcf5716d22eeb952a5398214ade1bbadd147f9.jpg', '2018-10-23 10:06:32', '2018-10-23 10:06:32'),
(1229, 202, '075606e7e3ddda3df9f0a4edb83e1a77fdd4dee2.jpg', '2018-10-23 10:06:36', '2018-10-23 10:06:36'),
(1230, 203, '08210a810b064f76c57291e06518c9d0696f0035.jpg', '2018-10-25 04:50:42', '2018-10-25 04:50:42'),
(1231, 203, '8cdb95496dba70def46b3df1d55266ea224527d7.jpg', '2018-10-25 04:50:45', '2018-10-25 04:50:45'),
(1232, 203, '300dc2e6ba14d0999cd6e5fca6abb84cc4957bc6.jpg', '2018-10-25 04:50:49', '2018-10-25 04:50:49'),
(1233, 204, '3b6f1477796bd2c79ac6b8507258508fa236fea5.jpg', '2018-10-25 13:35:41', '2018-10-25 13:35:41'),
(1234, 204, '0fd691f85c1a566c83b88350dae825f836568b32.jpg', '2018-10-25 13:35:51', '2018-10-25 13:35:51'),
(1235, 204, '3188ead8d47f06cd57d1a3789f7806a57924df2d.jpg', '2018-10-25 13:35:54', '2018-10-25 13:35:54'),
(1236, 205, 'c26d0d4dbd6577eefc5753b82113f449b83b3b4f.jpg', '2018-10-30 04:13:03', '2018-10-30 04:13:03'),
(1237, 205, '6f8f1141bf97d03285869e164e3823491a4b7598.jpg', '2018-10-30 04:13:11', '2018-10-30 04:13:11'),
(1238, 205, '4b879f7fbacf3fbc8e365f4f11ba9a64e723d10f.jpg', '2018-10-30 04:13:17', '2018-10-30 04:13:17'),
(1239, 206, '0a0eece82246cc356e0ec7624976fddfc7c0aed9.png', '2018-12-12 09:38:18', '2018-12-12 09:38:18'),
(1240, 206, 'b529eb13aec2cb2333e3853e7433326528636b67.jpg', '2018-12-12 09:38:21', '2018-12-12 09:38:21'),
(1241, 206, '0eac5c55f044e3782d0de1ead2787fe2bb8e2879.jpg', '2018-12-12 09:38:27', '2018-12-12 09:38:27'),
(1248, 207, '09ded5e3860f2a2ec20a6ca97d0e0ab0782c5195.jpg', '2018-12-12 13:20:15', '2018-12-12 13:20:15'),
(1249, 207, '07efa3d43fe29063e297067f7e39966b69afca14.jpg', '2018-12-12 13:20:17', '2018-12-12 13:20:17'),
(1250, 207, '1d94fe873d89ee119e9515dc335a9c4dcfc0f601.jpg', '2018-12-12 13:20:20', '2018-12-12 13:20:20'),
(1251, 207, '356adb4bf7c0805414a42c1fba077944fa276600.jpg', '2018-12-12 13:22:04', '2018-12-12 13:22:04'),
(1252, 207, '0b2bc962a0b66a906b631179efcc4d334076cc6a.jpg', '2018-12-12 13:22:07', '2018-12-12 13:22:07'),
(1253, 207, 'e152e0d633eafd829169d8e22d723a0fcce6ed28.jpg', '2018-12-12 13:22:09', '2018-12-12 13:22:09');

-- --------------------------------------------------------

--
-- Table structure for table `property_rules`
--

CREATE TABLE `property_rules` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `rules` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `property_rules`
--

INSERT INTO `property_rules` (`id`, `property_id`, `rules`, `created_at`, `updated_at`) VALUES
(1, 1, 'no parking', '2018-05-29 11:30:19', '2018-05-29 11:32:19'),
(2, 1, 'no pets', '2018-05-29 11:31:30', '2018-05-29 11:31:30'),
(3, 1, 'no wifi', '2018-05-29 11:31:36', '2018-05-29 11:31:36'),
(4, 2, 'sdsd', '2018-05-29 11:59:05', '2018-05-29 11:59:05'),
(8, 4, 'sda', '2018-05-29 13:05:09', '2018-05-29 13:05:09'),
(9, 4, 'asd', '2018-05-29 13:05:11', '2018-05-29 13:05:11'),
(10, 5, 'sadsad', '2018-05-29 13:13:13', '2018-05-29 13:13:13'),
(11, 6, 'Test', '2018-06-01 02:43:00', '2018-06-01 02:43:00'),
(12, 7, 'Boys are not allowed', '2018-06-01 02:57:22', '2018-06-01 02:57:22'),
(13, 8, 'Stay Happy Always', '2018-06-01 03:03:43', '2018-06-01 03:03:43'),
(14, 9, 'Nothing', '2018-06-01 03:08:52', '2018-06-01 03:08:52'),
(15, 10, 'No Rules', '2018-06-01 03:15:02', '2018-06-01 03:15:02'),
(16, 11, 'Free wifi', '2018-06-02 04:40:34', '2018-06-02 04:40:34'),
(17, 11, 'Microwave', '2018-06-02 04:40:41', '2018-06-02 04:40:41'),
(18, 12, '', '2018-06-04 04:05:11', '2018-06-04 04:35:21'),
(19, 12, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-06-04 04:20:44', '2018-06-04 04:50:58'),
(20, 12, 'eveloped by Kuber Constructions, this project offers 1BHK and 2BHK ', '2018-06-04 04:23:26', '2018-06-04 04:23:26'),
(21, 12, 'eveloped by Kuber Constructions, this project offers 1BHK and 2BHK ', '2018-06-04 04:23:39', '2018-06-04 04:23:39'),
(22, 12, 'No rules are added yet.sdssdsadfd', '2018-06-04 04:51:20', '2018-06-04 04:51:31'),
(24, 13, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-06-04 05:08:35', '2018-06-04 05:08:35'),
(25, 13, 'eveloped by Kuber Constructions, this project offers 1BHK and 2BHK ', '2018-06-04 05:08:40', '2018-06-04 05:08:40'),
(26, 13, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-06-04 05:08:53', '2018-06-04 05:08:53'),
(27, 14, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-06-04 05:48:51', '2018-06-04 05:48:51'),
(28, 14, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-06-04 05:49:31', '2018-06-04 05:49:31'),
(29, 14, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-06-04 05:49:34', '2018-06-04 05:49:34'),
(30, 14, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-06-04 05:49:38', '2018-06-04 05:49:38'),
(31, 14, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-06-04 05:49:52', '2018-06-04 05:49:52'),
(32, 15, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-06-04 07:07:58', '2018-06-04 07:07:58'),
(33, 15, 'eveloped by Kuber Constructions, this project offers 1BHK and 2BHK ', '2018-06-04 07:08:03', '2018-06-04 07:08:03'),
(34, 15, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-06-04 07:08:10', '2018-06-04 07:08:10'),
(35, 16, 'No rules are added yet.No rules are added yet.No rules are added yet.', '2018-06-04 08:44:47', '2018-06-04 08:48:58'),
(36, 16, 'No rules are added yet.No rules are added yet.No rules are added yet.', '2018-06-04 08:44:54', '2018-06-04 08:44:54'),
(37, 17, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-06-07 09:31:44', '2018-06-07 09:33:16'),
(38, 17, 'External door frame of teakwood and internal door frames of concrete of secction 4’’ * 2.5’’.', '2018-06-07 09:33:34', '2018-06-07 09:33:34'),
(39, 18, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-06-07 09:57:33', '2018-06-07 09:57:33'),
(40, 19, 'test', '2018-06-08 00:40:43', '2018-06-08 00:40:43'),
(41, 20, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-06-11 00:25:34', '2018-06-11 00:25:34'),
(42, 22, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-06-11 00:42:27', '2018-06-11 00:42:27'),
(43, 21, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-06-11 00:57:19', '2018-06-11 00:57:19'),
(44, 21, 'eveloped by Kuber Constructions, this project offers 1BHK and 2BHK ', '2018-06-11 01:02:50', '2018-06-11 01:02:50'),
(45, 23, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-06-11 01:27:48', '2018-06-11 01:27:48'),
(46, 23, 'eveloped by Kuber Constructions, this project offers 1BHK and 2BHK ', '2018-06-11 01:31:59', '2018-06-11 01:31:59'),
(47, 24, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-06-11 03:00:33', '2018-06-11 03:00:33'),
(48, 25, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-06-11 03:01:32', '2018-06-11 03:01:32'),
(49, 29, 'User scroll very time to add unavailiabiltiy time ', '2018-06-12 00:56:03', '2018-06-12 00:56:03'),
(50, 30, 'User scroll very time to add unavailiabiltiy time ', '2018-06-12 01:07:46', '2018-06-12 01:07:46'),
(51, 31, 'User scroll very time to add unavailiabiltiy time ', '2018-06-12 01:10:04', '2018-06-12 01:10:04'),
(52, 32, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-06-12 01:40:23', '2018-06-12 01:40:23'),
(53, 33, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-06-12 07:49:29', '2018-06-12 07:49:29'),
(54, 33, 'eveloped by Kuber Constructions, this project offers 1BHK and 2BHK ', '2018-06-12 07:49:32', '2018-06-12 07:49:32'),
(55, 34, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-06-12 08:10:52', '2018-06-12 08:10:52'),
(56, 34, 'eveloped by Kuber Constructions, this project offers 1BHK and 2BHK ', '2018-06-12 08:10:56', '2018-06-12 08:10:56'),
(57, 35, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-06-12 08:16:57', '2018-06-12 08:16:57'),
(58, 35, 'eveloped by Kuber Constructions, this project offers 1BHK and 2BHK ', '2018-06-12 08:17:01', '2018-06-12 08:17:01'),
(59, 36, 'back button should display the proper ', '2018-06-12 08:57:27', '2018-07-17 04:15:41'),
(68, 43, 'no drinksbb', '2018-07-06 01:29:19', '2018-07-06 01:30:24'),
(70, 45, 'yujtyuyui', '2018-07-07 00:20:50', '2018-07-07 03:28:59'),
(71, 45, '78i9789', '2018-07-07 03:29:04', '2018-07-07 03:29:04'),
(72, 41, 'rule 1', '2018-07-07 03:46:52', '2018-07-07 03:46:52'),
(73, 36, 'thrtyhrty', '2018-07-07 04:29:29', '2018-07-07 04:29:29'),
(99, 47, 'zbjzzj', '2018-07-07 05:04:50', '2018-07-07 05:04:50'),
(104, 58, 'add', '2018-07-09 03:35:44', '2018-07-09 03:35:44'),
(105, 59, 'test rule', '2018-07-09 07:53:07', '2018-07-09 07:53:07'),
(106, 60, 'test rule by sha', '2018-07-09 23:26:52', '2018-07-09 23:26:52'),
(107, 60, 'test rule by sha', '2018-07-09 23:26:57', '2018-07-09 23:26:57'),
(108, 60, 'new rule', '2018-07-09 23:27:07', '2018-07-09 23:27:07'),
(109, 55, 'new rule by me', '2018-07-10 05:44:32', '2018-07-10 05:44:39'),
(110, 63, 'house rule abcd', '2018-07-12 00:13:58', '2018-07-12 00:13:58'),
(111, 64, 'their website and wanted you have received your message to be able a good day please find attached my resume and cover letter', '2018-07-13 04:00:37', '2018-07-13 04:00:37'),
(112, 64, 'this is very good property', '2018-07-13 04:16:22', '2018-07-13 04:16:22'),
(113, 64, 'how are you', '2018-07-13 04:16:52', '2018-07-13 04:16:52'),
(114, 64, 'this is very nice', '2018-07-13 04:19:50', '2018-07-13 04:19:50'),
(115, 64, 'their website and wanted you have received your message to be able a good day please find attached my resume and cover letter', '2018-07-13 04:21:51', '2018-07-13 04:21:51'),
(116, 65, 'hdhjdd', '2018-07-13 04:27:02', '2018-07-13 04:27:02'),
(117, 65, 'hxxxhxc', '2018-07-13 04:27:23', '2018-07-13 04:27:23'),
(118, 65, 'chggnbb', '2018-07-13 04:27:34', '2018-07-13 04:27:34'),
(119, 65, 'hxxxhxc', '2018-07-13 04:27:55', '2018-07-13 04:27:55'),
(120, 65, 'hxxxhxc', '2018-07-13 04:28:04', '2018-07-13 04:28:04'),
(121, 28, 'sadasdas', '2018-07-13 04:28:29', '2018-07-13 04:28:29'),
(122, 65, 'hxxxhxc', '2018-07-13 04:32:11', '2018-07-13 04:32:11'),
(123, 28, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-07-13 04:36:09', '2018-07-13 04:36:09'),
(124, 57, '1', '2018-07-13 04:39:42', '2018-07-13 04:39:42'),
(125, 57, 'tyutyutyuty', '2018-07-13 04:39:49', '2018-07-13 04:39:49'),
(126, 66, 'their website and wanted me know and we', '2018-07-13 04:40:31', '2018-07-13 04:40:31'),
(127, 67, 'think the same problem', '2018-07-13 04:44:42', '2018-07-13 04:44:42'),
(128, 67, 'this is good and nice property', '2018-07-13 04:49:58', '2018-07-13 04:49:58'),
(129, 68, 'thos is not working properly', '2018-07-13 05:01:43', '2018-07-13 05:01:43'),
(133, 71, 'that would make sense', '2018-07-13 05:49:02', '2018-07-13 05:49:11'),
(134, 71, 'ha of your email address from', '2018-07-13 05:49:26', '2018-07-13 05:49:36'),
(143, 75, 'this is very', '2018-07-13 08:51:26', '2018-07-13 08:51:26'),
(144, 77, 'this is very nice and so that ', '2018-07-13 08:54:19', '2018-07-13 08:54:19'),
(145, 79, 'this message was automatically generated', '2018-07-16 07:15:56', '2018-07-16 07:16:17'),
(146, 80, 'think you should not have', '2018-07-16 11:29:59', '2018-07-16 11:29:59'),
(147, 36, 'hdhdjjdjjfjfjjfjfjfjjfjfjfj', '2018-07-17 04:15:25', '2018-07-17 04:15:25'),
(151, 36, 'gshshs', '2018-07-17 05:14:03', '2018-07-17 05:14:03'),
(155, 81, 'that would make sense that would make and', '2018-07-18 12:58:04', '2018-07-18 13:13:25'),
(157, 81, 'hello are you going', '2018-07-18 12:59:45', '2018-07-18 13:13:40'),
(158, 82, 'their website with you have received', '2018-07-18 13:31:23', '2018-07-18 13:31:23'),
(159, 83, 'thisvery', '2018-07-18 13:47:33', '2018-07-18 13:47:33'),
(169, 26, 'sadsdas', '2018-07-18 17:50:18', '2018-07-18 17:50:18'),
(188, 85, 'ttttt', '2018-07-19 09:22:34', '2018-07-19 09:22:34'),
(189, 85, 'hhhh', '2018-07-19 09:22:51', '2018-07-19 09:22:51'),
(194, 87, 'this', '2018-07-19 12:31:34', '2018-07-19 12:31:34'),
(195, 88, 'this is very nice', '2018-07-19 15:03:18', '2018-07-19 15:03:18'),
(196, 89, 'this is very', '2018-07-19 15:12:03', '2018-07-19 15:12:03'),
(197, 90, 'thus the Facebook', '2018-07-19 15:17:59', '2018-07-19 15:17:59'),
(199, 92, 'No Smoking', '2018-07-20 13:06:38', '2018-07-20 13:06:38'),
(200, 92, 'Check in time is flexible', '2018-07-20 13:06:48', '2018-07-20 13:06:48'),
(201, 92, 'Check out by 11 AM', '2018-07-20 13:06:59', '2018-07-20 13:06:59'),
(211, 100, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-07-21 17:24:28', '2018-07-21 17:24:28'),
(212, 100, 'eveloped by Kuber Constructions, this project offers 1BHK and 2BHK ', '2018-07-21 17:24:56', '2018-07-21 17:24:56'),
(213, 102, 'this message was automatically generated', '2018-07-23 08:28:36', '2018-07-23 08:28:36'),
(214, 104, 'this is very nice and very powerful', '2018-07-23 09:37:52', '2018-07-23 09:37:52'),
(215, 105, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-07-23 13:11:47', '2018-07-23 13:11:47'),
(216, 106, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-07-23 14:08:44', '2018-07-23 14:08:44'),
(217, 106, 'hello', '2018-07-23 14:09:22', '2018-07-23 14:09:22'),
(218, 107, 'cvv', '2018-07-23 14:09:33', '2018-07-23 14:09:33'),
(219, 109, 'this', '2018-07-24 11:13:21', '2018-07-24 11:13:21'),
(220, 111, 'the other hand the', '2018-07-24 11:22:13', '2018-07-24 11:22:13'),
(221, 111, 'External door frame of teakwood and internal door frames of concrete of section 4’’ * 2.5’’.', '2018-07-24 12:25:25', '2018-07-24 12:25:25'),
(222, 115, 'eveloped by Kuber Constructions, this project offers 1BHK and 2BHK ', '2018-07-25 13:24:10', '2018-07-25 13:24:10'),
(223, 116, 'this functionality to prakash the Facebook', '2018-07-30 09:36:58', '2018-07-30 09:36:58'),
(224, 116, 'the other hand the other day', '2018-07-30 09:37:14', '2018-07-30 09:37:14'),
(225, 117, 'this message is ready', '2018-07-30 10:31:52', '2018-07-30 10:31:52'),
(226, 118, 'this', '2018-07-30 10:35:52', '2018-07-30 10:35:52'),
(230, 123, 'this message was', '2018-07-31 11:43:11', '2018-07-31 11:43:11'),
(231, 41, 'rule 2', '2018-08-27 06:36:32', '2018-08-27 06:36:32'),
(232, 140, 'No parking', '2018-08-28 00:10:45', '2018-08-28 00:10:45'),
(233, 141, 'no rules ', '2018-08-28 00:26:03', '2018-08-28 00:26:03'),
(234, 158, 'Nice one ', '2018-08-29 04:28:19', '2018-08-29 04:28:19'),
(235, 158, 'good prop', '2018-08-29 04:28:26', '2018-08-29 04:28:26'),
(236, 184, '1 rule', '2018-09-07 10:39:48', '2018-09-07 10:39:48'),
(237, 192, 'aaaa', '2018-09-12 06:38:04', '2018-09-12 06:38:04'),
(238, 192, 'aaaaa', '2018-09-12 06:38:07', '2018-09-12 06:38:07'),
(239, 193, 'Rule 1', '2018-09-18 09:14:20', '2018-09-18 09:14:20'),
(240, 193, 'Rule 2', '2018-09-18 09:14:24', '2018-09-18 09:14:24'),
(241, 193, 'Rule 3', '2018-09-18 09:14:27', '2018-09-18 09:14:27'),
(242, 195, 'Test Rahul Property without dates', '2018-09-21 08:55:22', '2018-09-21 08:55:22');

-- --------------------------------------------------------

--
-- Table structure for table `property_type`
--

CREATE TABLE `property_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf16 NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `property_type`
--

INSERT INTO `property_type` (`id`, `name`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Banglow', '1', '2018-03-15 01:33:04', '2018-03-15 01:33:04', NULL),
(2, 'Home', '1', '2018-04-19 10:57:56', '2018-04-19 10:58:44', NULL),
(4, 'Farm house', '1', '2018-04-19 10:58:03', '2018-04-19 10:58:03', NULL),
(5, 'Restaurant', '1', '2018-04-21 09:36:22', '2018-09-12 11:48:34', NULL),
(6, 'Hostel', '1', '2018-04-21 09:36:29', '2018-09-24 06:31:27', NULL),
(7, 'Plots', '1', '2018-07-20 10:41:26', '2018-07-20 10:41:26', NULL),
(8, 'Flats', '1', '2018-07-20 10:41:39', '2018-07-20 10:41:39', NULL),
(9, 'Apartments', '1', '2018-07-20 10:41:46', '2018-09-12 11:49:34', NULL),
(10, 'Row houses', '1', '2018-07-20 10:42:00', '2018-09-12 11:48:37', NULL),
(11, 'Warehouse', '1', '2018-08-05 08:51:44', '2018-08-05 08:51:44', NULL),
(12, 'Office Space', '1', '2018-08-05 09:08:15', '2018-08-05 09:08:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `property_unavailability`
--

CREATE TABLE `property_unavailability` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `type` enum('MONTHLY','WEEKLY') NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `property_unavailability`
--

INSERT INTO `property_unavailability` (`id`, `property_id`, `booking_id`, `type`, `from_date`, `to_date`, `created_at`, `updated_at`) VALUES
(1, 189, 2, 'MONTHLY', '2018-10-04', '2018-10-05', '2018-10-03 13:16:06', '2018-10-03 13:16:06'),
(2, 189, 4, 'MONTHLY', '2018-10-21', '2018-10-27', '2018-10-03 13:20:57', '2018-10-03 13:20:57'),
(3, 189, 6, 'MONTHLY', '2018-10-16', '2018-10-18', '2018-10-03 13:24:00', '2018-10-03 13:24:00'),
(4, 124, NULL, 'MONTHLY', '2018-10-10', '2018-10-11', '2018-10-04 13:32:07', '2018-10-04 13:32:07'),
(5, 124, NULL, 'MONTHLY', '2018-10-12', '2018-10-13', '2018-10-04 13:42:56', '2018-10-04 13:42:56'),
(6, 124, 10, 'MONTHLY', '2018-10-14', '2018-10-15', '2018-10-04 13:48:52', '2018-10-04 13:48:52'),
(7, 201, 21, 'MONTHLY', '2018-10-24', '2018-10-25', '2018-10-24 10:08:37', '2018-10-24 10:08:37'),
(8, 202, 32, 'MONTHLY', '2018-10-28', '2018-10-29', '2018-10-24 11:36:39', '2018-10-24 11:36:39'),
(9, 192, 43, 'MONTHLY', '2018-12-04', '2018-12-05', '2018-12-04 10:27:43', '2018-12-04 10:27:43'),
(10, 192, 44, 'MONTHLY', '2018-12-06', '2018-12-07', '2018-12-04 10:37:59', '2018-12-04 10:37:59'),
(11, 150, 45, 'MONTHLY', '2018-12-04', '2018-12-05', '2018-12-04 10:53:43', '2018-12-04 10:53:43'),
(12, 192, 46, 'MONTHLY', '2018-12-10', '2018-12-11', '2018-12-10 10:19:42', '2018-12-10 10:19:42'),
(13, 192, 47, 'MONTHLY', '2018-12-12', '2018-12-13', '2018-12-10 10:20:40', '2018-12-10 10:20:40'),
(14, 150, 48, 'MONTHLY', '2018-12-10', '2018-12-11', '2018-12-10 10:25:56', '2018-12-10 10:25:56'),
(15, 111, 54, 'MONTHLY', '2018-12-14', '2018-12-15', '2018-12-14 09:47:09', '2018-12-14 09:47:09');

-- --------------------------------------------------------

--
-- Table structure for table `query_type`
--

CREATE TABLE `query_type` (
  `id` int(50) NOT NULL,
  `query_type` varchar(255) NOT NULL,
  `status` enum('0','1') NOT NULL COMMENT '0=inactive 1=active',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `query_type`
--

INSERT INTO `query_type` (`id`, `query_type`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'I have issue with my host', '1', '2018-02-19 01:34:57', '2018-02-21 01:37:28', NULL),
(2, 'I have issue with my Guest', '1', '2018-02-19 01:35:05', '2018-02-21 01:37:28', NULL),
(3, 'Refund Policy', '1', '2018-02-19 01:35:12', '2018-05-17 05:10:22', NULL),
(4, 'Gender', '1', '2018-02-19 01:35:17', '2018-02-21 01:37:28', NULL),
(5, 'Birth Date', '1', '2018-02-19 01:35:27', '2018-02-23 00:13:39', NULL),
(6, 'Our Terms and Conditions', '1', '2018-02-23 00:13:20', '2018-02-23 00:13:20', NULL),
(7, 'asddasd', '1', '2018-02-26 05:08:31', '2018-02-26 05:08:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `review_rating`
--

CREATE TABLE `review_rating` (
  `id` int(50) NOT NULL,
  `rating_user_id` int(50) NOT NULL,
  `property_id` int(50) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `rating` double NOT NULL,
  `message` text NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0 = Not Active, 1 = Active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `review_rating`
--

INSERT INTO `review_rating` (`id`, `rating_user_id`, `property_id`, `booking_id`, `rating`, `message`, `status`, `created_at`, `updated_at`) VALUES
(4, 103, 201, 23, 2.5, 'Qwertyuiop asdfghjkl zxcvbnm Qwertyuiop asdfghjkl zxcvbnm Qwertyuiop asdfghjkl zxcvbnm Qwertyuiop asdfghjkl zxcvbnm ', '1', '2018-11-01 05:34:20', '2018-11-01 05:34:20');

-- --------------------------------------------------------

--
-- Table structure for table `sac_data`
--

CREATE TABLE `sac_data` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sac` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sac_data`
--

INSERT INTO `sac_data` (`id`, `name`, `sac`, `created_at`, `updated_at`) VALUES
(1, 'shareous', '997221', '2018-12-07 08:38:41', '0000-00-00 00:00:00'),
(2, 'warehouse', '997212', '2018-12-07 08:38:41', '0000-00-00 00:00:00'),
(3, 'office-space', '997212', '2018-12-07 08:40:35', '2018-12-07 08:47:52'),
(4, 'home', '996311', '2018-12-07 08:40:35', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0=inactive 1=active',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Demo', '1', '2018-02-18 23:40:29', '2018-02-18 23:45:18', NULL),
(2, 'Test', '0', '2018-02-18 23:45:24', '2018-02-21 23:09:18', NULL),
(3, 'check', '0', '2018-02-18 23:47:58', '2018-02-20 05:21:31', NULL),
(4, 'Alrosa', '1', '2018-06-12 06:42:04', '2018-07-27 07:08:15', NULL),
(7, 'TempS', '1', '2018-08-02 11:28:50', '2018-08-02 11:28:50', NULL),
(8, 'webt', '1', '2018-08-02 12:10:33', '2018-08-02 12:10:33', NULL),
(9, 'Driver', '1', '2018-08-05 09:13:29', '2018-08-05 09:14:09', NULL),
(10, 'Guide', '1', '2018-08-05 09:13:37', '2018-08-31 23:34:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `site_name` varchar(255) NOT NULL,
  `site_address` varchar(255) NOT NULL,
  `site_contact_number` varchar(255) NOT NULL,
  `site_status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0=offline, 1=online',
  `site_logo` varchar(255) NOT NULL,
  `meta_desc` text NOT NULL,
  `meta_keyword` varchar(500) NOT NULL,
  `site_email_address` varchar(255) NOT NULL,
  `admin_commission` decimal(12,2) NOT NULL,
  `gst` decimal(12,2) NOT NULL,
  `fb_url` varchar(255) NOT NULL,
  `twitter_url` varchar(255) NOT NULL,
  `google_plus_url` varchar(500) NOT NULL,
  `youtube_url` varchar(255) NOT NULL,
  `rss_feed_url` varchar(255) NOT NULL,
  `instagram_url` varchar(255) NOT NULL,
  `linkedin_url` varchar(255) NOT NULL,
  `play_store_url` varchar(255) NOT NULL,
  `app_store_url` varchar(255) NOT NULL,
  `fb_client_id` varchar(255) NOT NULL,
  `fb_client_secret` varchar(255) NOT NULL,
  `fb_status` enum('1','0') NOT NULL COMMENT '1-On,0-Off',
  `google_client_id` varchar(255) NOT NULL,
  `google_client_secret` varchar(255) NOT NULL,
  `google_api_credential` varchar(255) NOT NULL,
  `google_status` enum('1','0') NOT NULL COMMENT '1-On,0-Off',
  `twitter_client_id` varchar(255) NOT NULL,
  `twitter_client_secret` varchar(255) NOT NULL,
  `twitter_status` enum('1','0') NOT NULL COMMENT '1-On,0-Off',
  `lat` text NOT NULL,
  `lon` text NOT NULL,
  `gstin` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `site_name`, `site_address`, `site_contact_number`, `site_status`, `site_logo`, `meta_desc`, `meta_keyword`, `site_email_address`, `admin_commission`, `gst`, `fb_url`, `twitter_url`, `google_plus_url`, `youtube_url`, `rss_feed_url`, `instagram_url`, `linkedin_url`, `play_store_url`, `app_store_url`, `fb_client_id`, `fb_client_secret`, `fb_status`, `google_client_id`, `google_client_secret`, `google_api_credential`, `google_status`, `twitter_client_id`, `twitter_client_secret`, `twitter_status`, `lat`, `lon`, `gstin`, `created_at`, `updated_at`) VALUES
(1, 'Shareous', '12/7, Main Road, Kesari Nagar, Surendranagar, Adambakkam, Chennai, Tamil Nadu 600088', '+919923266699', '1', '0d289c4f2721d7df88924b977f8cc60bb5713295.png', 'test_keyword', 'test_keyword', 'info@shareous.com', '12.15', '18.00', 'https://facebook.com', 'https://twitter.com', 'https://www.plus.google.com', 'https://youtube.com/', 'https://www.rss.com', 'https://www.instagram.com', 'https://in.linkedin.com', 'https://play.google.com/store?hl=en', 'https://www.apple.com/in/ios/app-store/', 'f_id12', 'f_serete212', '1', 'g_id123', 'g_serete11', 'api_12312', '1', 't_id21', 't_serete1112', '1', '', '', '33asoldkfg7447k1zp', '2017-07-24 03:10:00', '2018-12-10 06:21:29');

-- --------------------------------------------------------

--
-- Table structure for table `sleeping_arrangement`
--

CREATE TABLE `sleeping_arrangement` (
  `id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `is_active` enum('1','0') NOT NULL COMMENT '0 is block and 1 is active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sleeping_arrangement`
--

INSERT INTO `sleeping_arrangement` (`id`, `value`, `is_active`) VALUES
(1, 'yes', '1'),
(2, 'no', '1'),
(3, 'multiple', '1');

-- --------------------------------------------------------

--
-- Table structure for table `support_log`
--

CREATE TABLE `support_log` (
  `id` int(11) NOT NULL,
  `query_id` int(11) NOT NULL,
  `support_user_id` int(11) NOT NULL,
  `support_level` enum('L1','L2','L3') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `support_log`
--

INSERT INTO `support_log` (`id`, `query_id`, `support_user_id`, `support_level`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 3, 'L2', '2018-10-15 09:45:06', '2018-10-15 09:45:06', NULL),
(2, 2, 3, 'L2', '2018-10-17 07:04:48', '2018-10-17 07:04:48', NULL),
(3, 3, 3, 'L2', '2018-10-18 06:30:31', '2018-10-18 06:30:31', NULL),
(4, 4, 3, 'L2', '2018-10-22 09:49:54', '2018-10-22 09:49:54', NULL),
(5, 5, 3, 'L2', '2018-10-22 10:10:16', '2018-10-22 10:10:16', NULL),
(6, 6, 3, 'L2', '2018-10-24 12:50:10', '2018-10-24 12:50:10', NULL),
(7, 7, 3, 'L2', '2018-10-30 09:57:21', '2018-10-30 09:57:21', NULL),
(8, 8, 3, 'L2', '2018-11-22 05:16:35', '2018-11-22 05:16:35', NULL),
(9, 8, 3, 'L1', '2018-11-23 10:51:41', '2018-11-23 10:51:41', NULL),
(10, 8, 3, 'L1', '2018-11-23 10:54:03', '2018-11-23 10:58:06', NULL),
(11, 8, 3, 'L1', '2018-11-23 10:55:52', '2018-11-23 10:55:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `support_password_resets`
--

CREATE TABLE `support_password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `support_password_resets`
--

INSERT INTO `support_password_resets` (`email`, `token`, `created_at`) VALUES
('guest@zippiex.com', '56778064b8822e316b211fc3c0e2abc6456ac513f069dfc5335f2643254ac447', '2018-09-10 04:38:53'),
('webwingt@gmail.com', '823591fabf58399286fd9f594af120d1bf4f283b6a59f10081475e82616de629', '2018-09-11 05:28:58');

-- --------------------------------------------------------

--
-- Table structure for table `support_query`
--

CREATE TABLE `support_query` (
  `id` int(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` enum('1','4') NOT NULL COMMENT '1= User, 4= Host',
  `support_user_id` int(11) NOT NULL,
  `query_type_id` int(11) NOT NULL,
  `support_level` enum('L1','L2','L3') NOT NULL DEFAULT 'L3' COMMENT 'L1- Level 1,L2-Level 2,L3-Level 3',
  `booking_id` int(11) NOT NULL,
  `query_subject` varchar(255) NOT NULL,
  `query_description` varchar(500) NOT NULL,
  `attachment_file` varchar(255) NOT NULL,
  `attachment_file_name` varchar(255) NOT NULL,
  `status` enum('1','2','3') NOT NULL COMMENT '1-open 2-assigned 3-closed',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `support_query`
--

INSERT INTO `support_query` (`id`, `user_id`, `user_type`, `support_user_id`, `query_type_id`, `support_level`, `booking_id`, `query_subject`, `query_description`, `attachment_file`, `attachment_file_name`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 103, '1', 3, 0, 'L2', 7, 'Cancel Booking', 'I want to cancel my booking', '', '', '2', '2018-10-05 10:10:19', '2018-10-15 09:45:06', NULL),
(2, 103, '1', 3, 0, 'L2', 4, 'Change/Cancellation of Itinerary', 'cancel booking', '', '', '3', '2018-10-17 07:04:07', '2018-10-20 09:45:27', NULL),
(3, 103, '1', 3, 6, 'L2', 0, 'Testing support', 'Lorem Ipsum is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'af958054422d4946dcee434b1f66af47476ef0c0.jpg', 'fringefireworks_en-us11044516483_1920x1200.jpg', '2', '2018-10-18 06:29:45', '2018-10-18 06:30:31', NULL),
(4, 103, '1', 3, 0, 'L2', 15, 'Unsatisfactory Reviews', 'Don\'t want to stay there', '', '', '3', '2018-10-22 09:49:11', '2018-10-22 10:03:11', NULL),
(5, 103, '1', 3, 0, 'L2', 16, 'Other Reason', 'Feeling bad about that place...', '', '', '2', '2018-10-22 10:09:54', '2018-10-22 10:10:16', NULL),
(6, 103, '1', 3, 0, 'L2', 19, 'Unsatisfactory Reviews', 'trying new office space booking test at support panel', '', '', '3', '2018-10-24 12:49:50', '2018-10-24 12:54:47', NULL),
(7, 103, '1', 3, 0, 'L2', 40, 'Unsatisfactory Reviews', 'Need to check support panel. For the new changes done in office space.', '', '', '3', '2018-10-30 09:56:43', '2018-10-30 10:13:42', NULL),
(8, 103, '1', 3, 0, 'L2', 40, 'Change/Cancellation of Itinerary', 'I have to cancel this booking. I need to chat with the support for this.', '', '', '2', '2018-11-22 05:07:02', '2018-11-23 10:56:47', NULL),
(9, 103, '1', 0, 2, 'L3', 0, 'Demo testing', 'Demo testing Demo testing Demo testing Demo testing Demo testing Demo testing Demo testing Demo testing Demo testing', '5fcc4248e74b94f96ab8ff24e54a44c68a2a6076.png', 'corneroffice.png', '1', '2018-11-22 05:44:18', '2018-11-22 05:44:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `support_query_comments`
--

CREATE TABLE `support_query_comments` (
  `id` int(11) NOT NULL,
  `query_id` int(11) NOT NULL,
  `comment_by` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` enum('1','4') NOT NULL COMMENT '1= User, 4= Host',
  `support_user_id` int(11) NOT NULL,
  `is_read` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 - unread, 1 - read',
  `comment` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `support_query_comments`
--

INSERT INTO `support_query_comments` (`id`, `query_id`, `comment_by`, `user_id`, `user_type`, `support_user_id`, `is_read`, `comment`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 8, 3, 103, '1', 3, '1', 'Lorem Ipsum is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book.', '2018-10-18 06:29:45', '2018-12-05 13:39:05', NULL),
(2, 8, 103, 103, '1', 3, '0', 'Ok sure', '2018-10-20 09:16:32', '2018-11-26 07:25:56', NULL),
(3, 8, 3, 103, '1', 3, '1', 'when to delete it???', '2018-10-20 09:17:00', '2018-12-05 13:39:05', NULL),
(4, 8, 103, 103, '1', 3, '0', 'Demo testing Demo testing Demo testing Demo testing Demo testing Demo testing Demo testing Demo testing Demo testing', '2018-11-22 05:44:18', '2018-11-26 07:26:02', NULL),
(5, 8, 3, 103, '1', 3, '1', 'Hello', '2018-12-03 09:12:01', '2018-12-05 13:39:05', NULL),
(6, 8, 103, 103, '1', 3, '0', 'Hello', '2018-12-03 09:12:49', '2018-12-03 09:12:49', NULL),
(7, 8, 3, 103, '1', 3, '1', 'hello', '2018-12-03 09:12:57', '2018-12-05 13:39:05', NULL),
(8, 8, 103, 103, '1', 3, '0', 'Hello', '2018-12-03 09:18:12', '2018-12-03 09:18:12', NULL),
(9, 8, 3, 103, '1', 3, '1', 'Hello', '2018-12-03 09:20:22', '2018-12-05 13:39:05', NULL),
(10, 8, 103, 103, '1', 3, '0', 'Hello', '2018-12-03 09:20:31', '2018-12-03 09:20:31', NULL),
(11, 8, 3, 103, '1', 3, '1', 'Hello', '2018-12-05 06:24:16', '2018-12-05 13:39:05', NULL),
(12, 8, 103, 103, '1', 3, '0', 'Hello', '2018-12-05 06:24:23', '2018-12-05 06:24:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `support_team`
--

CREATE TABLE `support_team` (
  `id` int(50) NOT NULL,
  `user_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 NOT NULL,
  `password_reset_code` mediumtext CHARACTER SET utf8,
  `remember_token` varchar(255) NOT NULL,
  `support_level` enum('L1','L2','L3') CHARACTER SET utf8 NOT NULL DEFAULT 'L1' COMMENT '1=level1 2=level2 3=level3',
  `contact` varchar(255) CHARACTER SET utf8 NOT NULL,
  `profile_image` varchar(255) CHARACTER SET utf8 NOT NULL,
  `address` varchar(255) CHARACTER SET utf8 NOT NULL,
  `city` varchar(255) CHARACTER SET utf8 NOT NULL,
  `gender` enum('0','1') CHARACTER SET utf8 NOT NULL DEFAULT '0' COMMENT '0=male 1=female',
  `status` enum('0','1') CHARACTER SET utf8 NOT NULL DEFAULT '1' COMMENT '0=block 1=unblock',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `support_team`
--

INSERT INTO `support_team` (`id`, `user_name`, `first_name`, `last_name`, `email`, `password`, `password_reset_code`, `remember_token`, `support_level`, `contact`, `profile_image`, `address`, `city`, `gender`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'tanvichaudhari', 'Tanvi', 'Chaudhari', 'tanvic@webwingtechnologies.com', '$2y$10$n.BAaiS/Zbf/iuP5SIggqeAo21PkieCxkQ81j9erkvloeL6sriWM2', NULL, 'OXO0UophMHeil4poLTUa3KKgm9VI6GwhPe6CdOQDwbWlqxdQXV0qFwhYu4gD', 'L3', '+91-9011223311', 'a5581ac0892ec6d6fceeec67ba0e53805871fcb3.jpg', 'Delhi, India', 'Delhi', '1', '1', '2018-02-21 22:57:28', '2018-09-05 09:43:58', NULL),
(2, 'deepaksalunke', 'Deepak', 'Salunke', 'deepaks@webwingtechnologies.com', '$2y$10$n.BAaiS/Zbf/iuP5SIggqeAo21PkieCxkQ81j9erkvloeL6sriWM2', NULL, 'ediK5L4olidoIZWEVgfjTmH6HwdjYLwsnBBQNs8gCxD46hxhIFkcKTf4HotP', 'L1', '9876543210', '91fd81889190cf513c398b39c6a07e9be2e75f58.png', 'Nashik', 'Nashik', '0', '1', '2018-02-22 21:47:07', '2018-11-26 04:33:12', NULL),
(3, 'deepakbari', 'Deepak', 'Bari', 'deepak@webwing.com', '$2y$10$n.BAaiS/Zbf/iuP5SIggqeAo21PkieCxkQ81j9erkvloeL6sriWM2', NULL, 'WuZNqNWkZSfjPmyE9a4M7qzixRDpvgyk325BoN05V2P7qPhni2qH1CRYJzwD', 'L2', '632587410', 'ef4edefc2effcec7ed6702df2e4332d7840abf08.jpg', 'Nashik, Maharashtra, India', 'Nashik', '0', '1', '2018-02-27 22:19:11', '2018-12-05 09:22:08', NULL),
(4, 'sagarsainkar', 'Sagar', 'Sainkar', 'sagars@webwingtechnologies.com', '$2y$10$wzQIpLryj/xCstM8iyp1MueZPKCCxo/CgSghsc7bKF/Od.KPwXCJa', NULL, 'FtodBpBhZa8qeWoRi0BSYT0HqoWz4vrpigzI9qAGDdijp6HSLCC1Oaprr4YY', 'L3', '', '', '', '', '0', '1', '2018-03-01 22:06:48', '2018-05-19 03:26:34', NULL),
(6, 'sagarpawar', 'Sagar', 'Pawar', 'sagarp@webwingtechnologies.com', '$2y$10$wZtvEUPcRW6w9LlZWSLThOuYUbPW5oYLtbHnP0P6pyswFFdD5j.DO', NULL, 'xl1Yl28y4T2QU05ViadHRelH7uko4Stj8v4zeSMQWd13TxKbeISid4qM90Vr', 'L1', '', '', '', '', '0', '1', '2018-03-09 03:07:20', '2018-03-19 03:26:52', NULL),
(7, 'sagarpawar', 'Sagar', 'Pawar', 'adolf.rebelo@gmail.com', '$2y$10$J7eTXfvIgKh16AkE28fwTeC4M7t/KDGnYr5k/A1yZC9AIT5VXLWla', NULL, 'EpdaSy6BYWKPcE5CJSOod3D4KMCmJAkTN6ZNOrgQig70R3mexu8CrBKH5D4t', 'L3', '', '', '', '', '0', '1', '2018-03-09 03:07:30', '2018-03-14 06:39:13', NULL),
(8, 'sachinkale', 'Sachin', 'Kale', 'pavan@mailtrix.net', '$2y$10$ZABR4FjMsWjxd7iOprK6mes3nYYZHO198xp.wdHsXVIvG3K4Szvji', NULL, '9LnSqvvbIXrOG9GpJILEWMSX2SszrMhfh6cBDEZoAGIM6Sy3I8dJF3tIlN2S', 'L3', '', '', '', '', '0', '1', '2018-04-26 11:48:27', '2018-06-19 23:03:24', NULL),
(9, 'tusharkale', 'tushar', 'kale', 'Tushark@mailtrix.net', '$2y$10$n.BAaiS/Zbf/iuP5SIggqeAo21PkieCxkQ81j9erkvloeL6sriWM2', NULL, 'U6bmtEPeO5lWcoTTFCFNRH6YfK0Mlej4qq6pIUaIPEvkFlqlQDfWtQ2jnDm9', 'L2', '98563258745', '', 'Nashik, Maharashtra, India', 'Nashik', '0', '1', '2018-04-26 11:56:16', '2018-05-19 10:40:06', NULL),
(10, 'maheshkale', 'mahesh', 'kale', 'maheshk@mailtrix.net', '$2y$10$n.BAaiS/Zbf/iuP5SIggqeAo21PkieCxkQ81j9erkvloeL6sriWM2', NULL, 'FBwCMB1n9c4qbienJ9ROuFOpunC4ufE2cXqDv98UglRuvBcJzOeh4xdkJGBw', 'L1', '', '', '', '', '0', '1', '2018-04-26 12:01:09', '2018-05-19 10:59:05', NULL),
(11, 'mayuripardeshi', 'mayuri', 'pardeshi', 'mayurip@webwing.com', '$2y$10$OPs2TBhAa0J5GQfS1gw2.eIQttopPuMwmn5IL4sGUF0ZXlwLEMaNC', NULL, '', 'L1', '', '', '', '', '0', '1', '2018-04-26 15:18:42', '2018-04-26 11:21:49', NULL),
(12, 'mayuripande', 'mayuri', 'pande', 'mayurip@webwingtechnologies.com', '$2y$10$U8E3UbvfRm2REm3dudkg9.yY8x0uxNwI8i0aUZf6.1fQlJoAS6Naq', NULL, '', 'L1', '', '', '', '', '0', '1', '2018-04-26 15:22:54', '2018-04-26 15:22:54', NULL),
(13, 'tusharkale', 'tushar', 'kale', 'tushar@storiqax.com', '$2y$10$IyGNGgaLOJScXJSegsCV/.oq.uRcSXDqkJPdfiKDo9p7EymNRGSnO', NULL, 'KXD3Go3CsfBUF9LeJyM9xHjkEEQipAZK3r6JCWpOxkBMWS7bjFofrB4WzAba', 'L1', '', '', '', '', '0', '1', '2018-05-15 04:55:19', '2018-05-15 04:56:24', NULL),
(14, 'yanikluis', 'Yanik', 'luis', 'yanikluis5@gmail.com', '$2y$10$KRBCl6MrbXT52xh.2LAAFuJKKnKX0LdudEZP7qCm97EucxqNGR1TW', NULL, '71ff2VO60f1h3T8SKuOoxSVyThuL5XT4pixogW4fMQmBtjAVpjQotOJBNaxL', 'L1', '9921840141', '265d243197574f850fe9c12d0d04751f45c0556b.jpg', 'Nashik, Maharashtra, India', 'Nashik', '0', '1', '2018-05-19 10:54:05', '2018-06-11 00:49:09', NULL),
(15, 'jackhens', 'jack', 'hens', 'jackhens123@gmail.com', '$2y$10$MsVMaOGXQCQQWKLwThSNnOkqzf6o2qwOJ0v0p8B3cfksJztsj5lZW', NULL, 'DpqJgakU1uyyPV3udwFCxCTggCg6J7kRRz3JAju1R4Ra2WOkviE3XzvvSoSC', 'L2', '9632587412', 'a980030a638d2a72c61bfe224baf678ba3f11492.jpg', 'Nashik Road, Nashik, Maharashtra, India', 'Nashik', '0', '1', '2018-05-19 10:55:10', '2018-06-12 09:27:01', NULL),
(16, 'adolfrebelo', 'Adolf', 'Rebelo', 'rp7254653@gmail.com', '$2y$10$fREeCHXJ2Hynn0OQoISSTe8LzcTy9i.XA0OU49gjJFpwms09BraD2', NULL, 'CfNAtjNPmCZCBnYaEmb0BAH4UXvIBTqziViH6NUvF96F19CUnnXET7aA58lI', 'L3', '9921845111', '7c841b9433986e972262bdffc653eb57084e54b0.jpg', 'Nashik, Maharashtra, India', 'Nashik', '0', '1', '2018-05-19 10:55:51', '2018-06-12 09:25:59', NULL),
(17, 'blrtestingfebruary', 'BlrTesting', 'February', 'webwingt@gmail.com', '$2y$10$dhfsrypbvWRvomqcnImbXeHW0ky83tvvWJu5ZMoBVcRDqZpT.dW2m', NULL, 'niKg8QkgyRbNY4rqnF0v5ck9uYqabiWerRkty3PpdmL3WVu29Fe4fcI7HPYt', 'L2', '', '', '', '', '0', '1', '2018-05-29 11:12:13', '2018-06-11 08:48:34', NULL),
(18, 'rupeshkale', 'Rupesh', 'Kale', 'guest@zippiex.com', '$2y$10$5gvqvsCWn5FENOU0FhBEWOH6wh3ADxnnlTck1mnzTU8ktmaWUxBli', NULL, '', 'L1', '', '', '', '', '0', '1', '2018-06-02 11:54:11', '2018-09-10 04:31:20', NULL),
(19, 'maheshkale', 'Mahesh', 'Kale', 'mahesh@storiqax.com', '$2y$10$s60f88CtlQWfBEjBoOGXzO1MdGqPoIB3ThtuF1BoAZ2QC0cxLxn6e', NULL, '', 'L1', '', '', '', '', '0', '1', '2018-06-02 12:00:30', '2018-06-02 12:00:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` varchar(5000) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` enum('1','0') DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `title`, `message`, `image`, `status`, `created_at`, `updated_at`) VALUES
(24, 'Testing', '<p>This place is so beautiful and it was a wonderful experience staying at their thatched house. We were so overwhelmed with Hideo and Ukako hospitality. They are AWESOME . We planned this visit during the most cold season to experience snow , am sure this place will be even better</p>', '5b298af28b31f10901547af7ebc9aa2b3d26c532.jpg', '1', '2018-04-23 04:32:29', '2018-06-15 00:57:18'),
(38, 'Nilesh vibhute', '<p>c typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets con taining Lorem Ipsum passages, and more recently with desktop publishing software like Aldus Pagtaining Lorem Ipsum passages, and more recently with desktop publishing soft</p>', '17ed006f6c3cb92fab366b22a199d28bdacda427.png', '0', '2018-05-04 03:33:32', '2018-06-15 00:56:38'),
(40, 'Nilesh Vibhute', '<p><strong>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</strong></p>', '882cbd6eb55d5f2a9cd2f52d47e065a49d01304e.png', '0', '2018-05-05 11:37:57', '2018-06-15 00:56:38'),
(41, 'test', '<p>c typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus Pag cvxxvxcv xgvdfgdfgdfgdfgdfg gdgdfg dfgdfgdfgfdg dfsdfdsf s dfsdfsdf sdfsdfsfsdfsdf</p>', '58163cb8c1628db14a649492079410c609ab2208.jpg', '1', '2018-06-08 05:51:41', '2018-06-15 00:57:18'),
(42, 'sadsd', '<p>it should display the default images box in testimonial sectionsit should display the default images box in testimonial sectionsit should display the default images box in testimonialit should display the default images box in testimonial sections &nbsp;sections &nbsp;sections &nbsp;sections &nbsp;sections &nbsp;sections &nbsp;</p>', 'ab6b335d0d67824ebe365a818f694707c3bf2cdc.jpg', '0', '2018-06-11 03:34:58', '2018-06-15 00:56:38'),
(43, 'sadsd', '<p>it should display the default images box in testimonial sectionsit should display the default images box in testimonial sectionsit should display the default images box in testimonialit should display the default images box in testimonial sections &nbsp;sections &nbsp;sections &nbsp;sections &nbsp;sections &nbsp;sections &nbsp;</p>', '185af7925fbc5a36a42a89294c323d0c4fbcb69e.jpg', '0', '2018-06-11 03:34:58', '2018-06-15 00:56:38'),
(44, 'Umesh kale', '<p>galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. Itelectronic typesetting, remaining essentially unchanged. Itgalley of type and scrambled scrambled&nbsp;scrambled</p>', 'edf259dc153d961984737f85dc01e28b708b5296.jpg', '1', '2018-06-12 07:53:16', '2018-06-15 00:57:18');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `payment_type` enum('wallet','booking','refund') NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` enum('1','4') NOT NULL DEFAULT '1' COMMENT '1- guest 4-host',
  `amount` double NOT NULL,
  `booking_id` int(11) NOT NULL,
  `transaction_for` varchar(255) NOT NULL,
  `invoice` varchar(250) NOT NULL,
  `transaction_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `payment_type`, `transaction_id`, `user_id`, `user_type`, `amount`, `booking_id`, `transaction_for`, `invoice`, `transaction_date`, `created_at`, `updated_at`) VALUES
(1, 'booking', 'pay_B5AujuagprNxyh', 103, '1', 12500, 1, 'Payment successfully paid for the Tushar WareHouse', 'Invoice1103.pdf', '2018-10-03', '2018-10-03 13:14:55', '2018-10-03 13:14:55'),
(2, 'booking', 'pay_B5AujuagprNxyh', 52, '4', 12500, 1, 'Payment successfully received for the Tushar WareHouse by the Umesh', 'Invoice252.pdf', '2018-10-03', '2018-10-03 13:15:04', '2018-10-03 13:15:04'),
(3, 'booking', 'pay_B5Aw0T2m0Ei4Uv', 103, '1', 12500, 2, 'Payment successfully paid for the Tushar WareHouse', 'Invoice3103.pdf', '2018-10-03', '2018-10-03 13:16:06', '2018-10-03 13:16:06'),
(4, 'booking', 'pay_B5Aw0T2m0Ei4Uv', 52, '4', 12500, 2, 'Payment successfully received for the Tushar WareHouse by the Umesh', 'Invoice452.pdf', '2018-10-03', '2018-10-03 13:16:14', '2018-10-03 13:16:14'),
(5, 'booking', 'pay_B5B17q4Ph84PlA', 103, '1', 150000, 4, 'Payment successfully paid for the Tushar WareHouse', 'Invoice5103.pdf', '2018-10-03', '2018-10-03 13:20:57', '2018-10-03 13:20:57'),
(6, 'booking', 'pay_B5B17q4Ph84PlA', 52, '4', 150000, 4, 'Payment successfully received for the Tushar WareHouse by the Umesh', 'Invoice652.pdf', '2018-10-03', '2018-10-03 13:21:09', '2018-10-03 13:21:09'),
(7, 'booking', 'pay_B5B1ztjo1TWku7', 103, '1', 37500, 5, 'Payment successfully paid for the Tushar WareHouse', 'Invoice7103.pdf', '2018-10-03', '2018-10-03 13:21:46', '2018-10-03 13:21:46'),
(8, 'booking', 'pay_B5B1ztjo1TWku7', 52, '4', 37500, 5, 'Payment successfully received for the Tushar WareHouse by the Umesh', 'Invoice852.pdf', '2018-10-03', '2018-10-03 13:22:58', '2018-10-03 13:22:58'),
(9, 'booking', 'pay_B5B4LQLbLxQ6Dm', 103, '1', 25000, 6, 'Payment successfully paid for the Tushar WareHouse', 'Invoice9103.pdf', '2018-10-03', '2018-10-03 13:24:00', '2018-10-03 13:24:00'),
(10, 'booking', 'pay_B5B4LQLbLxQ6Dm', 52, '4', 25000, 6, 'Payment successfully received for the Tushar WareHouse by the Umesh', 'Invoice1052.pdf', '2018-10-03', '2018-10-03 13:24:05', '2018-10-03 13:24:06'),
(11, 'booking', 'pay_B5Zjl6p8eBlSX0', 103, '1', 3500, 7, 'Payment successfully paid for the Harish banglow', 'Invoice11103.pdf', '2018-10-04', '2018-10-04 13:31:51', '2018-10-04 13:31:51'),
(12, 'booking', 'pay_B5Zjl6p8eBlSX0', 52, '4', 3500, 7, 'Payment successfully received for the Harish banglow by the Umesh', 'Invoice1252.pdf', '2018-10-04', '2018-10-04 13:32:00', '2018-10-04 13:32:00'),
(13, 'booking', 'pay_B5ZtvKahviW1jY', 103, '1', 3500, 8, 'Payment successfully paid for the Harish banglow', 'Invoice13103.pdf', '2018-10-04', '2018-10-04 13:41:28', '2018-10-04 13:41:28'),
(14, 'booking', 'pay_B5ZvDxGMSCd4O8', 103, '1', 3500, 8, 'Payment successfully paid for the Harish banglow', 'Invoice14103.pdf', '2018-10-04', '2018-10-04 13:42:42', '2018-10-04 13:42:42'),
(15, 'booking', 'pay_B5ZvDxGMSCd4O8', 52, '4', 3500, 8, 'Payment successfully received for the Harish banglow by the Umesh', 'Invoice1552.pdf', '2018-10-04', '2018-10-04 13:42:49', '2018-10-04 13:42:49'),
(16, 'booking', 'pay_B5a0znfVVfvTAd', 103, '1', 3500, 9, 'Payment successfully paid for the Harish banglow', 'Invoice16103.pdf', '2018-10-04', '2018-10-04 13:48:10', '2018-10-04 13:48:10'),
(17, 'booking', 'pay_B5a0znfVVfvTAd', 52, '4', 3500, 9, 'Payment successfully received for the Harish banglow by the Umesh', 'Invoice1752.pdf', '2018-10-04', '2018-10-04 13:48:17', '2018-10-04 13:48:17'),
(18, 'booking', 'pay_B5a1judXnlChYR', 103, '1', 7000, 10, 'Payment successfully paid for the Harish banglow', 'Invoice18103.pdf', '2018-10-04', '2018-10-04 13:48:52', '2018-10-04 13:48:52'),
(19, 'booking', 'pay_B5a1judXnlChYR', 52, '4', 7000, 10, 'Payment successfully received for the Harish banglow by the Umesh', 'Invoice1952.pdf', '2018-10-04', '2018-10-04 13:48:59', '2018-10-04 13:48:59'),
(20, 'wallet', 'pay_BBpTSQOw2AhNnj', 103, '1', 1, 0, '1 INR amount added in wallet', 'Invoice20.pdf', '2018-10-20', '2018-10-20 08:49:31', '2018-10-20 08:49:32'),
(21, 'wallet', 'pay_BBpUPK211uhjzW', 103, '1', 1.22, 0, '1.22 INR amount added in wallet', 'Invoice21.pdf', '2018-10-20', '2018-10-20 08:50:25', '2018-10-20 08:50:25'),
(22, 'wallet', 'pay_BBpW6qiAe1FQzE', 103, '1', 1, 0, '1 INR amount added in wallet', 'Invoice22.pdf', '2018-10-20', '2018-10-20 08:52:01', '2018-10-20 08:52:02'),
(23, 'wallet', 'pay_BBpYcUh3af0feR', 103, '1', 1, 0, '1 INR amount added in wallet', 'Invoice23.pdf', '2018-10-20', '2018-10-20 08:54:24', '2018-10-20 08:54:24'),
(24, 'booking', 'pay_BCZydfTO8U2Zte', 103, '1', 3500, 14, 'Payment successfully paid for the Harish banglow', '', '2018-10-22', '2018-10-22 06:18:58', '2018-10-22 06:18:58'),
(25, 'booking', 'pay_BCZzEsCcnLgk1X', 103, '1', 3500, 14, 'Payment successfully paid for the Harish banglow', '', '2018-10-22', '2018-10-22 06:19:31', '2018-10-22 06:19:31'),
(26, 'booking', 'pay_BCdXzRWjLolEXs', 103, '1', 2500, 15, 'Payment successfully paid for the Tushar WareHouse', 'Invoice26103.pdf', '2018-10-22', '2018-10-22 09:48:31', '2018-10-22 09:48:31'),
(27, 'booking', 'pay_BCdXzRWjLolEXs', 52, '4', 2500, 15, 'Payment successfully received for the Tushar WareHouse by the Umesh', 'Invoice2752.pdf', '2018-10-22', '2018-10-22 09:48:38', '2018-10-22 09:48:38'),
(28, 'refund', 'pay_BCdXzRWjLolEXs', 103, '1', 1500, 15, 'Refund for cancel booking', 'RefundInvoice28103.pdf', '2018-10-22', '2018-10-22 10:03:11', '2018-10-22 10:03:11'),
(29, 'booking', 'pay_BCdtup9O561iIZ', 103, '1', 4009.22, 16, 'Payment successfully paid for the Book Store Room', 'Invoice29103.pdf', '2018-10-22', '2018-10-22 10:09:15', '2018-10-22 10:09:15'),
(30, 'booking', 'pay_BCdtup9O561iIZ', 52, '4', 4009.22, 16, 'Payment successfully received for the Book Store Room by the Umesh', 'Invoice3052.pdf', '2018-10-22', '2018-10-22 10:09:21', '2018-10-22 10:09:21'),
(31, 'refund', 'pay_BCdtup9O561iIZ', 103, '1', 50, 16, 'Refund for cancel booking', 'RefundInvoice31103.pdf', '2018-10-22', '2018-10-22 10:10:30', '2018-10-22 10:10:30'),
(32, 'refund', 'pay_BCdtup9O561iIZ', 103, '1', 50, 16, 'Refund for cancel booking', 'RefundInvoice32103.pdf', '2018-10-22', '2018-10-22 10:15:47', '2018-10-22 10:15:48'),
(33, 'refund', 'pay_BCdtup9O561iIZ', 103, '1', 50, 16, 'Refund for cancel booking', 'RefundInvoice33103.pdf', '2018-10-22', '2018-10-22 10:17:41', '2018-10-22 10:17:41'),
(34, 'refund', 'pay_BCdtup9O561iIZ', 103, '1', 50, 16, 'Refund for cancel booking', 'RefundInvoice34103.pdf', '2018-10-22', '2018-10-22 10:21:20', '2018-10-22 10:21:20'),
(35, 'refund', 'pay_BCdtup9O561iIZ', 103, '1', 50, 16, 'Refund for cancel booking', 'RefundInvoice35103.pdf', '2018-10-22', '2018-10-22 10:22:12', '2018-10-22 10:22:12'),
(36, 'refund', 'pay_BCdtup9O561iIZ', 103, '1', 50, 16, 'Refund for cancel booking', 'RefundInvoice36103.pdf', '2018-10-22', '2018-10-22 10:22:50', '2018-10-22 10:22:50'),
(37, 'refund', 'pay_BCdtup9O561iIZ', 103, '1', 50, 16, 'Refund for cancel booking', 'RefundInvoice37103.pdf', '2018-10-22', '2018-10-22 10:23:16', '2018-10-22 10:23:16'),
(38, 'refund', 'pay_BCdtup9O561iIZ', 103, '1', 350, 16, 'Refund for cancel booking', 'RefundInvoice38103.pdf', '2018-10-22', '2018-10-22 10:34:34', '2018-10-22 10:34:34'),
(39, 'refund', 'pay_BCdtup9O561iIZ', 103, '1', 309.22, 16, 'Refund for cancel booking', 'RefundInvoice39103.pdf', '2018-10-22', '2018-10-22 10:36:25', '2018-10-22 10:36:25'),
(40, 'refund', 'pay_BCdtup9O561iIZ', 103, '1', 50, 16, 'Refund for cancel booking', 'RefundInvoice40103.pdf', '2018-10-22', '2018-10-22 10:36:43', '2018-10-22 10:36:44'),
(41, 'wallet', 'pay_BCfMfy2oAuJyMY', 103, '1', 3905.22, 0, '3905.22 INR amount added in wallet', 'Invoice41.pdf', '2018-10-22', '2018-10-22 11:35:12', '2018-10-22 11:35:12'),
(42, 'booking', 'pay_Al4mL0zk779wx', 103, '1', 4009.22, 17, 'Payment successfully paid for the Book Store Room using wallet', 'Invoice42103.pdf', '2018-10-22', '2018-10-22 11:36:04', '2018-10-22 11:36:04'),
(43, 'wallet', 'pay_Al4mL0zk779wx', 52, '4', 4009.22, 17, 'Payment successfully received for the Book Store Room by the Umesh', 'Invoice4352.pdf', '2018-10-22', '2018-10-22 11:36:09', '2018-10-22 11:36:09'),
(44, 'booking', 'pay_BDQtxXlo6H51yA', 103, '1', 2525, 19, 'Payment successfully paid for the New office space with multiple selections options', 'Invoice44103.pdf', '2018-10-24', '2018-10-24 10:05:20', '2018-10-24 10:05:20'),
(45, 'booking', 'pay_BDQtxXlo6H51yA', 52, '4', 2525, 19, 'Payment successfully received for the New office space with multiple selections options by the Umesh', 'Invoice4552.pdf', '2018-10-24', '2018-10-24 10:05:27', '2018-10-24 10:05:27'),
(46, 'booking', 'pay_BDQvbqqvlQ1Eeq', 103, '1', 275, 20, 'Payment successfully paid for the New office space with multiple selections options', 'Invoice46103.pdf', '2018-10-24', '2018-10-24 10:06:50', '2018-10-24 10:06:50'),
(47, 'booking', 'pay_BDQvbqqvlQ1Eeq', 52, '4', 275, 20, 'Payment successfully received for the New office space with multiple selections options by the Umesh', 'Invoice4752.pdf', '2018-10-24', '2018-10-24 10:06:56', '2018-10-24 10:06:56'),
(48, 'booking', 'pay_BDQxTv7YXmkJsa', 103, '1', 125, 21, 'Payment successfully paid for the New office space with multiple selections options', 'Invoice48103.pdf', '2018-10-24', '2018-10-24 10:08:37', '2018-10-24 10:08:37'),
(49, 'booking', 'pay_BDQxTv7YXmkJsa', 52, '4', 125, 21, 'Payment successfully received for the New office space with multiple selections options by the Umesh', 'Invoice4952.pdf', '2018-10-24', '2018-10-24 10:08:43', '2018-10-24 10:08:43'),
(50, 'booking', 'pay_BDQyqFLzhtQcSo', 103, '1', 2700, 22, 'Payment successfully paid for the New office space with multiple selections options', 'Invoice50103.pdf', '2018-10-24', '2018-10-24 10:09:53', '2018-10-24 10:09:54'),
(51, 'booking', 'pay_BDQyqFLzhtQcSo', 52, '4', 2700, 22, 'Payment successfully received for the New office space with multiple selections options by the Umesh', 'Invoice5152.pdf', '2018-10-24', '2018-10-24 10:10:03', '2018-10-24 10:10:04'),
(52, 'booking', 'pay_BDR1ic4TXM4Ok8', 103, '1', 2700, 23, 'Payment successfully paid for the New office space with multiple selections options', 'Invoice52103.pdf', '2018-10-24', '2018-10-24 10:12:38', '2018-10-24 10:12:38'),
(53, 'booking', 'pay_BDR1ic4TXM4Ok8', 52, '4', 2700, 23, 'Payment successfully received for the New office space with multiple selections options by the Umesh', 'Invoice5352.pdf', '2018-10-24', '2018-10-24 10:12:43', '2018-10-24 10:12:43'),
(54, 'booking', 'pay_BDR3fKIG9xtD7W', 103, '1', 1840, 24, 'Payment successfully paid for the New office space with multiple selections options 1', 'Invoice54103.pdf', '2018-10-24', '2018-10-24 10:14:28', '2018-10-24 10:14:28'),
(55, 'booking', 'pay_BDR3fKIG9xtD7W', 52, '4', 1840, 24, 'Payment successfully received for the New office space with multiple selections options 1 by the Umesh', 'Invoice5552.pdf', '2018-10-24', '2018-10-24 10:14:33', '2018-10-24 10:14:33'),
(56, 'booking', 'pay_BDRGgTRwTKcvWw', 103, '1', 80, 25, 'Payment successfully paid for the New office space with multiple selections options 1', 'Invoice56103.pdf', '2018-10-24', '2018-10-24 10:26:47', '2018-10-24 10:26:47'),
(57, 'booking', 'pay_BDRGgTRwTKcvWw', 52, '4', 80, 25, 'Payment successfully received for the New office space with multiple selections options 1 by the Umesh', 'Invoice5752.pdf', '2018-10-24', '2018-10-24 10:26:54', '2018-10-24 10:26:54'),
(58, 'booking', 'pay_BDSCm8igBf7bSm', 103, '1', 80, 26, 'Payment successfully paid for the New office space with multiple selections options 1', 'Invoice58103.pdf', '2018-10-24', '2018-10-24 11:21:47', '2018-10-24 11:21:47'),
(59, 'booking', 'pay_BDSCm8igBf7bSm', 52, '4', 80, 26, 'Payment successfully received for the New office space with multiple selections options 1 by the Umesh', 'Invoice5952.pdf', '2018-10-24', '2018-10-24 11:21:53', '2018-10-24 11:21:53'),
(60, 'booking', 'pay_BDSDmHE9b2sWCD', 103, '1', 80, 27, 'Payment successfully paid for the New office space with multiple selections options 1', 'Invoice60103.pdf', '2018-10-24', '2018-10-24 11:22:43', '2018-10-24 11:22:44'),
(61, 'booking', 'pay_BDSDmHE9b2sWCD', 52, '4', 80, 27, 'Payment successfully received for the New office space with multiple selections options 1 by the Umesh', 'Invoice6152.pdf', '2018-10-24', '2018-10-24 11:22:49', '2018-10-24 11:22:49'),
(62, 'booking', 'pay_BDSEYtElQi5xcf', 103, '1', 80, 28, 'Payment successfully paid for the New office space with multiple selections options 1', 'Invoice62103.pdf', '2018-10-24', '2018-10-24 11:23:28', '2018-10-24 11:23:28'),
(63, 'booking', 'pay_BDSFXcaMB1Ujr4', 103, '1', 1960, 29, 'Payment successfully paid for the New office space with multiple selections options 1', 'Invoice63103.pdf', '2018-10-24', '2018-10-24 11:24:24', '2018-10-24 11:24:24'),
(64, 'booking', 'pay_BDSFXcaMB1Ujr4', 52, '4', 1960, 29, 'Payment successfully received for the New office space with multiple selections options 1 by the Umesh', 'Invoice6452.pdf', '2018-10-24', '2018-10-24 11:24:30', '2018-10-24 11:24:30'),
(65, 'booking', 'pay_BDSGWcZ97ydNAh', 103, '1', 120, 30, 'Payment successfully paid for the New office space with multiple selections options 1', 'Invoice65103.pdf', '2018-10-24', '2018-10-24 11:25:20', '2018-10-24 11:25:20'),
(66, 'booking', 'pay_BDSQtvOj8lDeZC', 103, '1', 1920, 31, 'Payment successfully paid for the New office space with multiple selections options 1', 'Invoice66103.pdf', '2018-10-24', '2018-10-24 11:35:10', '2018-10-24 11:35:10'),
(67, 'booking', 'pay_BDSQtvOj8lDeZC', 52, '4', 1920, 31, 'Payment successfully received for the New office space with multiple selections options 1 by the Umesh', 'Invoice6752.pdf', '2018-10-24', '2018-10-24 11:35:16', '2018-10-24 11:35:16'),
(68, 'booking', 'pay_BDSSTwCExYjgZU', 103, '1', 160, 32, 'Payment successfully paid for the New office space with multiple selections options 1', 'Invoice68103.pdf', '2018-10-24', '2018-10-24 11:36:39', '2018-10-24 11:36:39'),
(69, 'booking', 'pay_BDSSTwCExYjgZU', 52, '4', 160, 32, 'Payment successfully received for the New office space with multiple selections options 1 by the Umesh', 'Invoice6952.pdf', '2018-10-24', '2018-10-24 11:36:45', '2018-10-24 11:36:45'),
(70, 'wallet', 'pay_BDoXyUJU3B6wgt', 103, '1', 350, 0, '350 INR amount added in wallet', 'Invoice70.pdf', '2018-10-25', '2018-10-25 09:13:06', '2018-10-25 09:13:06'),
(71, 'wallet', 'pay_BDoYsRgrCFaKl6', 103, '1', 350, 0, '350 INR amount added in wallet', 'Invoice71.pdf', '2018-10-25', '2018-10-25 09:13:57', '2018-10-25 09:13:57'),
(72, 'booking', 'pay_ACMOIs3QR9iRS', 103, '1', 350, 35, 'Payment successfully paid for the New office space with multiple selections options using wallet', 'Invoice72103.pdf', '2018-10-25', '2018-10-25 09:24:57', '2018-10-25 09:24:57'),
(73, 'wallet', 'pay_ACMOIs3QR9iRS', 52, '4', 350, 35, 'Payment successfully received for the New office space with multiple selections options by the Umesh', 'Invoice7352.pdf', '2018-10-25', '2018-10-25 09:25:03', '2018-10-25 09:25:04'),
(74, 'wallet', 'pay_BDolsFCQgNOZiC', 103, '1', 100, 0, '100 INR amount added in wallet', 'Invoice74.pdf', '2018-10-25', '2018-10-25 09:26:15', '2018-10-25 09:26:15'),
(75, 'wallet', 'pay_BDp4SePN1nYx5g', 103, '1', 7568.43, 0, '7568.43 INR amount added in wallet', 'Invoice75.pdf', '2018-10-25', '2018-10-25 09:43:51', '2018-10-25 09:43:51'),
(76, 'wallet', 'pay_BDpLzpwIWvVZIH', 103, '1', 334444.07, 0, '334444.07 INR amount added in wallet', 'Invoice76.pdf', '2018-10-25', '2018-10-25 10:00:27', '2018-10-25 10:00:27'),
(77, 'booking', 'pay_AXWVlFo6wlJjZ', 103, '1', 342462.5, 38, 'Payment successfully paid for the white lily using wallet', 'Invoice77103.pdf', '2018-10-25', '2018-10-25 10:00:45', '2018-10-25 10:00:45'),
(78, 'wallet', 'pay_AXWVlFo6wlJjZ', 5, '4', 342462.5, 38, 'Payment successfully received for the white lily by the Umesh', 'Invoice785.pdf', '2018-10-25', '2018-10-25 10:00:52', '2018-10-25 10:00:52'),
(79, 'booking', 'pay_BFl9GIskRkML8G', 103, '1', 75, 39, 'Payment successfully paid for the Deepak Office space with different prices ', 'Invoice79103.pdf', '2018-10-30', '2018-10-30 07:11:38', '2018-10-30 07:11:39'),
(80, 'booking', 'pay_BFl9GIskRkML8G', 52, '4', 75, 39, 'Payment successfully received for the Deepak Office space with different prices  by the Umesh', 'Invoice8052.pdf', '2018-10-30', '2018-10-30 07:11:46', '2018-10-30 07:11:47'),
(81, 'booking', 'pay_BFmlm05FewUQ57', 103, '1', 75, 40, 'Payment successfully paid for the Deepak Office space with different prices ', 'Invoice81103.pdf', '2018-10-30', '2018-10-30 08:46:47', '2018-10-30 08:46:48'),
(82, 'booking', 'pay_BFmlm05FewUQ57', 52, '4', 75, 40, 'Payment successfully received for the Deepak Office space with different prices  by the Umesh', 'Invoice8252.pdf', '2018-10-30', '2018-10-30 08:46:57', '2018-10-30 08:46:57'),
(83, 'wallet', 'pay_BOW1H7MJ47xXTy', 103, '1', 4009.22, 0, '4009.22 INR amount added in wallet', '', '2018-11-21', '2018-11-21 10:14:41', '2018-11-21 10:14:41'),
(84, 'wallet', 'pay_BOWP7Bvvyu7Gr6', 103, '1', 4009.22, 0, '4009.22 INR amount added in wallet', '', '2018-11-21', '2018-11-21 10:37:15', '2018-11-21 10:37:15'),
(85, 'wallet', 'pay_BOWQ85FDiuE3DW', 103, '1', 4009.22, 0, '4009.22 INR amount added in wallet', 'Invoice85.pdf', '2018-11-21', '2018-11-21 10:38:12', '2018-11-21 10:38:13'),
(86, 'wallet', 'pay_BOWRcvBHWiVanw', 103, '1', 4009.22, 0, '4009.22 INR amount added in wallet', 'Invoice86.pdf', '2018-11-21', '2018-11-21 10:39:38', '2018-11-21 10:39:38'),
(87, 'wallet', 'pay_BOWTHUmfA9reuH', 103, '1', 4009.22, 0, '4009.22 INR amount added in wallet', 'Invoice87.pdf', '2018-11-21', '2018-11-21 10:41:13', '2018-11-21 10:41:13'),
(88, 'wallet', 'pay_BOWfh638eOA4CJ', 103, '1', 4009.22, 0, '4009.22 INR amount added in wallet', 'Invoice88.pdf', '2018-11-21', '2018-11-21 10:52:56', '2018-11-21 10:52:56'),
(89, 'wallet', 'pay_BOWuqUOb0PaQnM', 103, '1', 4009.22, 0, '4009.22 INR amount added in wallet', 'Invoice89.pdf', '2018-11-21', '2018-11-21 11:07:17', '2018-11-21 11:07:17'),
(90, 'booking', 'pay_A5p9yjfnNXg5g', 103, '1', 4009.22, 42, 'Payment successfully paid for the Book Store Room using wallet', 'Invoice90103.pdf', '2018-11-21', '2018-11-21 11:07:38', '2018-11-21 11:07:39'),
(91, 'wallet', 'pay_A5p9yjfnNXg5g', 52, '4', 4009.22, 42, 'Payment successfully received for the Book Store Room by the Umesh', 'Invoice9152.pdf', '2018-11-21', '2018-11-21 11:07:45', '2018-11-21 11:07:45'),
(92, 'booking', 'pay_BTfBZtvjKGTfaf', 103, '1', 500, 43, 'Payment successfully paid for the PPPr', 'Invoice92103.pdf', '2018-12-04', '2018-12-04 10:27:42', '2018-12-04 10:27:43'),
(93, 'booking', 'pay_BTfBZtvjKGTfaf', 52, '4', 500, 43, 'Payment successfully received for the PPPr by the Umesh', 'Invoice9352.pdf', '2018-12-04', '2018-12-04 10:27:51', '2018-12-04 10:27:51'),
(94, 'booking', 'pay_BTfMRxxb6IQEAB', 103, '1', 500.35, 44, 'Payment successfully paid for the PPPr', 'Invoice94103.pdf', '2018-12-04', '2018-12-04 10:37:59', '2018-12-04 10:37:59'),
(95, 'booking', 'pay_BTfMRxxb6IQEAB', 52, '4', 500.35, 44, 'Payment successfully received for the PPPr by the Umesh', 'Invoice9552.pdf', '2018-12-04', '2018-12-04 10:38:07', '2018-12-04 10:38:07'),
(96, 'booking', 'pay_BTfcx4dCia9OCX', 103, '1', 52602.24, 45, 'Payment successfully paid for the Office space', 'Invoice96103.pdf', '2018-12-04', '2018-12-04 10:53:43', '2018-12-04 10:53:43'),
(97, 'booking', 'pay_BTfcx4dCia9OCX', 27, '4', 52602.24, 45, 'Payment successfully received for the Office space by the Umesh', 'Invoice9727.pdf', '2018-12-04', '2018-12-04 10:53:52', '2018-12-04 10:53:52'),
(98, 'booking', 'pay_BW2FqBe6IKuFna', 103, '1', 500, 46, 'Payment successfully paid for the PPPr', '', '2018-12-10', '2018-12-10 10:19:41', '2018-12-10 10:19:42'),
(99, 'booking', 'pay_BW2FqBe6IKuFna', 52, '4', 500, 46, 'Payment successfully received for the PPPr by the Umesh', '', '2018-12-10', '2018-12-10 10:19:49', '2018-12-10 10:19:49'),
(100, 'booking', 'pay_BW2Gt1tu0kPgL6', 103, '1', 500, 47, 'Payment successfully paid for the PPPr', 'Invoice100103.pdf', '2018-12-10', '2018-12-10 10:20:40', '2018-12-10 10:20:40'),
(101, 'booking', 'pay_BW2Gt1tu0kPgL6', 52, '4', 500, 47, 'Payment successfully received for the PPPr by the Umesh', 'Invoice101103.pdf', '2018-12-10', '2018-12-10 10:20:47', '2018-12-10 10:20:47'),
(102, 'booking', 'pay_BW2MRj4MqZ7LAc', 103, '1', 52602.24, 48, 'Payment successfully paid for the Office space', 'Invoice102103.pdf', '2018-12-10', '2018-12-10 10:25:56', '2018-12-10 10:25:56'),
(103, 'booking', 'pay_BW2MRj4MqZ7LAc', 27, '4', 52602.24, 48, 'Payment successfully received for the Office space by the Umesh', 'Invoice103103.pdf', '2018-12-10', '2018-12-10 10:26:04', '2018-12-10 10:26:04'),
(104, 'booking', 'pay_BW2WlbcQC7AwsP', 103, '1', 4130, 49, 'Payment successfully paid for the Harish banglow', 'Invoice104103.pdf', '2018-12-10', '2018-12-10 10:35:42', '2018-12-10 10:35:42'),
(105, 'booking', 'pay_BW2WlbcQC7AwsP', 52, '4', 4130, 49, 'Payment successfully received for the Harish banglow by the Umesh', 'Invoice105103.pdf', '2018-12-10', '2018-12-10 10:35:50', '2018-12-10 10:35:51'),
(106, 'booking', 'pay_BWNIMfVndwiLmK', 115, '1', 20, 50, 'Payment successfully paid for the Deepak Office space with different prices ', '', '2018-12-11', '2018-12-11 06:54:44', '2018-12-11 06:54:44'),
(107, 'wallet', 'pay_BWNK7pWhynCXni', 115, '1', 500, 0, '500 INR amount added in wallet', 'Invoice107.pdf', '2018-12-11', '2018-12-11 06:56:17', '2018-12-11 06:56:17'),
(108, 'booking', 'pay_BXanjG2jHJxPc0', 103, '1', 4130, 51, 'Payment successfully paid for the Harish banglow', 'Invoice108103.pdf', '2018-12-14', '2018-12-14 08:46:22', '2018-12-14 08:46:22'),
(109, 'booking', 'pay_BXanjG2jHJxPc0', 52, '4', 4130, 51, 'Payment successfully received for the Harish banglow by the Umesh', 'Invoice109103.pdf', '2018-12-14', '2018-12-14 08:46:30', '2018-12-14 08:46:30'),
(110, 'booking', 'pay_BXauEkvYGjVa4X', 103, '1', 5310, 52, 'Payment successfully paid for the uday apartments', 'Invoice110103.pdf', '2018-12-14', '2018-12-14 08:52:30', '2018-12-14 08:52:31'),
(111, 'booking', 'pay_BXauEkvYGjVa4X', 52, '4', 5310, 52, 'Payment successfully received for the uday apartments by the Umesh', 'Invoice111103.pdf', '2018-12-14', '2018-12-14 08:52:38', '2018-12-14 08:52:38'),
(112, 'booking', 'pay_BXbobnxVMZewQO', 103, '1', 4631.79, 53, 'Payment successfully paid for the the Facebook platform y', '', '2018-12-14', '2018-12-14 09:45:53', '2018-12-14 09:45:53'),
(113, 'booking', 'pay_BXbps2E3uSi6Kk', 103, '1', 8601.9, 54, 'Payment successfully paid for the this message was delivered ', 'Invoice113103.pdf', '2018-12-14', '2018-12-14 09:47:08', '2018-12-14 09:47:09'),
(114, 'booking', 'pay_BXbps2E3uSi6Kk', 52, '4', 8601.9, 54, 'Payment successfully received for the this message was delivered  by the Umesh', 'Invoice114103.pdf', '2018-12-14', '2018-12-14 09:47:16', '2018-12-14 09:47:16'),
(115, 'booking', 'pay_BXbvf6QokyPqar', 103, '1', 2260.94, 55, 'Payment successfully paid for the Green Villa Pune', 'Invoice115103.pdf', '2018-12-14', '2018-12-14 09:52:33', '2018-12-14 09:52:34'),
(116, 'booking', 'pay_BXbvf6QokyPqar', 52, '4', 2260.94, 55, 'Payment successfully received for the Green Villa Pune by the Umesh', 'Invoice116103.pdf', '2018-12-14', '2018-12-14 09:52:42', '2018-12-14 09:52:42'),
(117, 'wallet', 'pay_BaQOYP0GXHs2Q9', 103, '1', 50, 0, '50 INR amount added in wallet', 'Invoice117.pdf', '2018-12-21', '2018-12-21 12:32:34', '2018-12-21 12:32:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(50) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_type` enum('1','4') NOT NULL DEFAULT '1' COMMENT '1- guest 4-host',
  `otp` varchar(10) NOT NULL DEFAULT '0',
  `mobile_otp` varchar(10) NOT NULL,
  `resend_otp_count` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `otp_expired_time` datetime NOT NULL,
  `mobile_otp_expired_time` datetime DEFAULT NULL,
  `verification_token` text,
  `remember_token` text,
  `display_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `country_code` varchar(11) DEFAULT NULL,
  `mobile_number` varchar(255) NOT NULL,
  `new_mobile_number` varchar(100) NOT NULL,
  `gender` enum('0','1') DEFAULT NULL COMMENT '0=female 1=male',
  `birth_date` date NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `profile_image` text,
  `wallet_amount` double NOT NULL,
  `social_login` enum('yes','no') NOT NULL DEFAULT 'no',
  `login_via` varchar(30) NOT NULL,
  `status` enum('0','1') DEFAULT '1' COMMENT '1- active 0-inactive',
  `notification_type` enum('1','2','3','') NOT NULL COMMENT '1=sms 2=email 3=both',
  `notification_by_email` enum('on','off') NOT NULL DEFAULT 'off',
  `notification_by_sms` enum('on','off') NOT NULL DEFAULT 'off',
  `notification_by_push` enum('on','off') NOT NULL DEFAULT 'off',
  `is_email_verified` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0- unverified 1-verified',
  `is_mobile_verified` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0-unverified 1-verified',
  `is_coupon_used` enum('yes','no') NOT NULL DEFAULT 'no',
  `gstin` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_name`, `user_type`, `otp`, `mobile_otp`, `resend_otp_count`, `email`, `password`, `otp_expired_time`, `mobile_otp_expired_time`, `verification_token`, `remember_token`, `display_name`, `first_name`, `last_name`, `country_code`, `mobile_number`, `new_mobile_number`, `gender`, `birth_date`, `address`, `city`, `state`, `country`, `profile_image`, `wallet_amount`, `social_login`, `login_via`, `status`, `notification_type`, `notification_by_email`, `notification_by_sms`, `notification_by_push`, `is_email_verified`, `is_mobile_verified`, `is_coupon_used`, `gstin`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'pankajt', '4', '', '0614', NULL, 'snehal@pay-mon.com', '$2y$10$n3K.hWofkHsfm0BTMIj51eOzIyFhoKgLleEK7/ji9F1xijzyCUVqi', '2018-07-21 00:00:00', '2018-07-23 11:35:57', NULL, 'JA8uaDgefZCXAy8avbH9VH7pUvZnECBRCHUGkcYAkrwmSLfDmTxSm2bGALw7', 'pankajt-t', 'Pankaj', 'liu', NULL, '+919921840141', '', '', '1970-01-31', 'Satara, Maharashtra, India', 'Nashik', 'Nashik', 'country', '4242a11b6c9f82f600120a1895209f82e21758c5.jpg', -693597.54, 'no', '', '1', '1', 'on', 'on', 'off', '1', '1', 'no', '22AAAAA0000A1Z5', '2018-05-29 10:26:21', '2018-12-04 04:31:41', NULL),
(2, 'webwing', '4', '', '', NULL, 'webwingt@gmail.com', '$2y$10$kIjzbEFZd.U.ufdwkfpxQeSFZuPR9eawjA6PkCLpWPfiYjfTBv866', '2018-07-21 00:00:00', '2018-07-21 00:00:00', NULL, 'ZkN1XswggF5iDTQqZ8t0lpvlhSB4JsQ1JnT5OMs3GyrBLGfwbLFa4T6fn7Mx', 'webwing-t', 'Webwing', 'T', NULL, '9789545632', '', '1', '2018-05-16', 'Navi Mumbai, Maharashtra, India', 'Navi Mumbai', '', '', '7694da78286aa8f93ee045cb3a954ed7ab59cebe.jpg', 20490.1, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-05-29 10:33:27', '2018-12-04 04:34:33', NULL),
(3, 'kavita', '4', '', '', NULL, 'kavita@aditus.info', '$2y$10$L07EMLRhdgK0/vrXGfL34u12nR8dLT.fmchYmFym9AVaAaj5CkaYm', '2018-07-21 00:00:00', '2018-07-21 00:00:00', NULL, '6sQkxYStGIcwvELRzmtMgyk2LYpbeFap06tNDevvTKnKj6EmtuS5gzqYd4kd', 'kavita-gavhane', 'Kavita', 'Gavhane', NULL, '8275698568', '', '1', '2005-07-21', 'Nashik, Maharashtra, India', 'Nashik', '', '', '10ea0fd25d7ac5641884d8284ba9f9d456e5fdac.jpg', 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', '22AAAAA0000A1Z5', '2018-05-29 10:37:06', '2018-12-04 04:31:41', NULL),
(4, 'khush', '4', '', '', NULL, 'adolf.rebelo@gmail.com', '$2y$10$gJfLsspR.UqqDrHgL67vP.0USrtrIoYrTeY0BGa4XuJVqFZ9L/84.', '2018-07-21 00:00:00', '2018-07-21 00:00:00', NULL, 'kcjpzWRjpCdAc4F2CjBKQE4tbbXGMRnYCjqbAfqnY4JDn7ASWozUCuWKeG7V', 'khush-jain', 'Khushi', 'Jain', NULL, '87664568734', '', '1', '2016-10-12', 'Denver, CO, USA', 'Denver', '', '', 'ea755f58cc8cf15ab4379daefe12e5b933c81e8d.jpg', 0, 'no', '', '1', '1', '', '', '', '1', '1', 'no', NULL, '2018-05-29 10:39:25', '2018-12-04 04:34:36', NULL),
(5, 'mayuri', '4', '', '', NULL, 'mayurip@webwingtechnologies.com', '$2y$10$ouQl6bBrrPfXvjM.xuaOpuaDP3i.bUlwIZo3JwldkibL1mk152r46', '2018-07-21 00:00:00', '2018-07-21 00:00:00', NULL, 'zNJb2O8aGpZCi1TbpPwrSIFuLqGqH04XCj2l49eugAinG5VwQVcWufNXhKB7', 'mayuri-pardeshi', 'Mayuri', 'Pardeshi', NULL, '7894561230', '', '1', '2000-07-16', 'Nashik Railway Track Road, Nawle Colony, Government Colony, Nashik, Maharashtra, India', 'Nashik', '', '', '0137efa8844d261f9bacc7c335da42fc82b22f65.jpg', 0, 'no', '', '1', '1', '', '', '', '1', '1', 'no', '22AAAAA0000A1Z5', '2018-05-29 11:05:29', '2018-12-04 04:31:41', NULL),
(6, 'webwingt@gmail.com', '1', '', '', NULL, 'guest@rupayamail.com', '$2y$10$4Oy8epQTfL0AjYhK7z/v4ugVq5u2.xMJfNHb/dCluONQWvoDdoL3i', '2018-07-21 00:00:00', '0000-00-00 00:00:00', NULL, 'QNFgf4yn1QTuaJLh31p9CMD1sDZK81pJ1uyrLj6FdxFn1vaMMhmQF3WFldvV', '', 'John', ' Smith', '+91', '14253698717', '', '1', '0000-00-00', NULL, '', '', '', NULL, 0, 'yes', '', '1', '1', 'off', 'off', 'off', '0', '1', 'no', NULL, '2018-05-29 13:14:41', '2018-10-15 08:34:57', NULL),
(7, 'webwingt@gmail.com', '1', '', '', NULL, 'webwingt1@gmail.com', '$2y$10$P9Os3KltHpdGDpfU6Spgped9cFkqpk1oQi4O2artweLiRJitUoMGO', '2018-07-21 00:00:00', '2018-07-21 00:00:00', NULL, '9I7wMuGT99MPubevNDFiAsAVejbjdMM4k0KTGTkeRFp4QSr147lBj4uSfm0L', '', 'Webwing', 'Webwing', NULL, '', '', '1', '0000-00-00', NULL, '', '', '', NULL, 0, 'yes', '', '1', '1', 'off', 'off', 'off', '0', '1', 'no', NULL, '2018-05-29 13:15:26', '2018-06-08 07:43:54', NULL),
(8, 'gdfgdf', '1', '', '', NULL, 'fdgdfgdfgdf@ss.jm', '$2y$10$XPJDO1ajE9xnicDZG5cxeeUFVgWyBc57h5P7qZ/qPQZq0XpHcIJbK', '2018-07-21 00:00:00', '2018-07-21 00:00:00', '71aaa6425090a456d1546f73298d2a60', NULL, 'gdfgdf-dfgdfgdfgdfg', 'gsgsdfg', 'dfgdfgdfgdfg', NULL, '321645312', '', '0', '2000-05-11', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '0', '1', 'no', NULL, '2018-05-30 03:34:49', '2018-05-30 03:40:36', NULL),
(10, 'rohit', '1', '', '', NULL, 'kilmagutru@etoic.com', '$2y$10$lEX3gXDtIK2mFZTABpgWqeEb6nyga8Ds1rmyoewysCbrTRdsx/yLK', '2018-07-21 00:00:00', '2018-07-21 00:00:00', NULL, 'd5UixrdHSqxgYcEqJwQJrZx1TnZbHdUziT8IgSHqxRe4ESMmdP8h1NRw9Slm', 'rohit-h', 'Rohit', 'H', NULL, '8956326523', '', '', '2000-05-24', 'Nashik, Maharashtra, India', 'Nashik', '', '', 'eab8d1413d0334843f3522b6afc1a70b8ef6a7de.jpg', 0, 'no', '', '1', '1', '', 'on', 'on', '1', '1', 'no', NULL, '2018-05-31 04:24:40', '2018-06-08 05:24:46', NULL),
(11, 'kupih', '1', '', '', NULL, 'kupih@dumoac.net', '$2y$10$t/zk7qJi2IE/CS2rhvDkl.ZOqnLM80lHKg6.IjImfAmjJV1eqi29.', '2018-07-21 00:00:00', '2018-07-21 00:00:00', '32566eed730eb69acc480f6ef832d119', NULL, 'kupih-demo', 'kupih', 'demo', NULL, '9876543210', '', '1', '2000-05-17', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '0', '1', 'no', NULL, '2018-06-02 05:14:13', '2018-06-02 05:14:13', NULL),
(12, 'deepak', '1', '', '', 1, 'deepak@o3enzyme.com', '$2y$10$y57sYYpuBRPiNFLhNmasSutff82OBlSLTVHO5WgkcoAED1igSUj.u', '2018-07-21 00:00:00', '0000-00-00 00:00:00', 'f74ab6c6b0805ff402cb8fcd0006a75a', '5pYfR0pSONBuZfFalaNGlgWKYZmDXWFkPDT64YDN47SlafKM6rztqOH3iUW1', 'deepak-demo', 'Deepak', 'Demo', '+91', '9638527415', '', '1', '2000-10-10', 'Nashik, Maharashtra, India', 'Nashik', '', '', 'f93237625b9dbeb11c227d7b2894c8f24267afd2.jpg', 50, 'no', '', '1', '1', 'on', 'on', 'on', '1', '1', 'no', NULL, '2018-06-02 05:21:19', '2018-10-20 09:56:47', NULL),
(14, 'salsad', '4', '', '', NULL, 'salsa.derrick@yahoo.in', '$2y$10$gxGGaO3A6dhXwBc0sRPVdeccjEl34b4lVuCKVWvaaawXjBoxqwkDe', '2018-07-21 00:00:00', '2018-07-21 00:00:00', 'f82b1b71dea6188ea5316cdbc9cfe2bd', 'jZqZc2V4pJkrpGoLXXTA3GsSJhaDnO91VMJVFTs0GGeieHo2ad5pX9yxTjhw', 'Salsa-derrick', 'Salsa', 'Derrickk', NULL, '1321231321132311', '', '1', '1985-11-10', 'Nashik, Maharashtra, India', 'Nashik', 'state', 'country', '0f94892e3d9a304ceacc2ea7538cd7dc55a516bd.jpg', 22640, 'no', '', '1', '1', 'on', 'on', 'on', '0', '1', 'no', NULL, '2018-06-02 09:50:32', '2018-12-04 04:34:40', NULL),
(15, 'jackhens123@gmail.com', '4', '', '', NULL, 'jackhens123@gmail.com', '$2y$10$cfqCzaPAoPbQbHlmq72oGuoZ0ZhcAoAg.ZPlIJud3rPORI9z5CBwm', '2018-07-21 00:00:00', '2018-07-21 00:00:00', NULL, 'rIpqcl9gTt0zWalZqR26YWrb7VhJAtGGPXmH3ZV6qEMM6u5xgBRhDuLEfCCy', 'jack', 'Jackhens', 'Lewins', NULL, '8378988', '', '1', '2000-02-09', 'Kalika Mandir, Pocket 8, Sector 20, Rohini, Delhi, India', 'Delhi', '', '', 'f3df68d679d885e7610f5a4bc28310d04f42edec.jpg', 0, 'yes', '', '1', '1', '', '', '', '0', '1', 'no', '22AAAAA0000A1Z5', '2018-06-04 03:15:46', '2018-12-04 04:31:41', NULL),
(16, 'sdfsdfsdfsdf', '1', '', '', NULL, 'nartenodro@etoic.com', '$2y$10$fvxBtZqiECTtVVtE4rW5huWuJx/8EvU/KHgCXkmqYkdWkqmT2ZUkW', '2018-07-21 00:00:00', '2018-07-21 00:00:00', NULL, 'WiNj1IqQF99fQjYJsZKyIraenSkHz31SUJowyjxLbq2JYBGF9Rq5IUHfYzqa', 'sdfsdfsdfsdf-dsfsdf', 'fsdfsdf', 'dsfsdf', NULL, '76767868', '', '1', '2000-05-30', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-06-08 01:13:59', '2018-06-08 03:00:48', NULL),
(18, 'komalkk', '1', '', '', NULL, 'komalkk@o3enzyme.com', '$2y$10$E3QtocwPJguRQQjqV2nF1.9etpTymrWfGXGDqYWpUkwFwfdiYfc5O', '2018-07-21 00:00:00', '2018-07-21 00:00:00', NULL, 'xdMne3U9mlcq5hDYc34hhHXk148kIN3X5dUlcFm7XmVY6kJ5IYylAkz052q2', 'komalkk-kk', 'komal', 'kk', NULL, '77834234383', '', '0', '2000-06-08', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-06-08 07:05:06', '2018-06-08 07:51:56', NULL),
(22, 'sachinp888', '4', '', '', NULL, 'sachinpp@o3enzyme.com', '$2y$10$2wjxNU2.K.5hRRziAWy7Cuy7LScYU484BuuKmAfacpm8w8PyCOqxO', '2018-07-21 00:00:00', '2018-07-21 00:00:00', NULL, 'tJ5XBs5LgWozJV53mDLGq3PzGIetlGaxWzt5bIgDFz7rJKMycav1ex2hgiSq', 'sachinp888-patil', 'Sachin', 'Patil', NULL, '8888203299', '', '1', '1985-11-14', 'Pune, Maharashtra, India', 'Pune', '', '', '9ab7ab1438fc14d8655159f0c5bc5c31b264ac74.jpg', 524, 'no', '', '1', '1', 'on', 'on', 'on', '1', '1', 'no', NULL, '2018-06-10 23:12:14', '2018-12-04 04:34:43', NULL),
(25, 'tusharkk88', '1', '', '', NULL, 'tushark@o3enzyme.com', '$2y$10$hlaYVKlVzRutUpcFQAn04eePLotjjmN3jUucGvjtM8TAyJa1f9yUa', '2018-07-21 00:00:00', '2018-07-21 00:00:00', NULL, '1tw3WbMF5ocdoNfl1bSQbUtHZmeDYEwfrupqZvsgGe7mCEac5ri0NRFlEzbg', 'tusharkk88-kale', 'tushar', 'kale', NULL, '9999999999', '', '1', '1994-11-23', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-06-13 00:27:26', '2018-06-13 00:33:01', NULL),
(27, 'bhushan123', '4', '', '6325', NULL, 'test@gmail.com', '$2y$10$6caIjGDN8FXzhfKp8W7.UOzNT6i9D19lJaFSCLfn8zEFyMi820L32', '2018-07-21 00:00:00', '2018-09-07 18:06:44', '197a40952784210201bd4dc00e80cb64', 'v4erA1MgUbSK1ktfaEOpVQyy83WD2mqQBzBpFdhcdQXg37JmDSzEHm8JVun1', 'bhushan123', 'Bhushan', 'Tester', NULL, '84120223645', '+9184120223645', '', '1995-03-22', 'Mumbai, Maharashtra, India', 'Mumbai', 'Maharashtra', 'India', 'f63c9ff54cf8b59d7eae26aa2159aeffeaced695.jpg', 99757.71, 'no', '', '1', '1', 'on', 'off', 'on', '1', '1', 'no', '22AAAAA0000A1Z5', '2018-06-15 01:31:00', '2018-12-04 04:31:41', NULL),
(28, 'tejas123', '1', '', '', NULL, 'tejuss@webwingtechnologies.com', '$2y$10$w0xKi3j7hVZZ5B/J7FY9BeGCekCaEHz81Rl0BlLF0doeafl8VvxD2', '2018-07-21 00:00:00', '2018-07-21 00:00:00', NULL, 'T1vymgCCogm91ezO86u4E9HV2mHVm8n3ZX5cLPPXdvCXHUO4yvM1kmXy9FWh', 'tejas123-sonar', 'tejas', 'sonar', NULL, '8888456789', '', '1', '1999-03-22', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-06-15 01:53:04', '2018-06-15 01:54:20', NULL),
(29, 'leo.john', '1', '', '', NULL, 'leo@gmail.com', '$2y$10$h/eEgMsq29oSJpkTTU9jV.zIFumNiY.uMB50Qm.8B0a53pVd96VBy', '2018-07-21 04:44:10', '2018-07-21 00:00:00', '1e4e141e84aaa9f59ba2b528e5c98171', NULL, 'leojohn-john', 'leo', 'john', NULL, '6878898990', '', '1', '1983-04-04', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '0', '1', 'no', NULL, '2018-07-05 22:44:10', '2018-07-05 22:44:10', NULL),
(30, 'user.shareus', '1', '', '', NULL, 'user@shareus.com', '$2y$10$Ov5uxV5hHq0ERJtgXVrdIeFfFE6mimsKfc35UkC/8Zj6GO7d86IHe', '2018-07-21 05:03:18', '2018-07-21 00:00:00', '1c2db7810dcf12e28276e1727a16be20', NULL, 'usershareus-shareus', 'user', 'shareus', NULL, '8767677887687', '', '1', '2018-07-06', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '0', '1', 'no', NULL, '2018-07-05 23:03:19', '2018-07-05 23:03:19', NULL),
(31, 'kavita1234', '1', '', '', NULL, 'kavita@aditus.infoo', '$2y$10$EH.y07xIzyT7zrUfcOEauOSX.1uiu7s8IUiGuV62Y2JoxYSdGgkZe', '2018-07-21 05:04:03', '2018-07-21 00:00:00', '55d43224dcec396b0bf090acdbefb174', NULL, 'kavita1234-gavhane', 'kavita', 'gavhane', NULL, '69852365985', '', '0', '2016-11-27', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '0', '1', 'no', NULL, '2018-07-05 23:04:03', '2018-07-05 23:04:03', NULL),
(32, 'kavita12341', '1', '', '', NULL, 'kavita@aditus.inf', '$2y$10$3dzV7Iux0JrSlvs7KzQqyegEiXT7UTzmkvqIp9/EvgjfIH.YP7fDu', '2018-07-21 05:28:05', '2018-07-21 00:00:00', '3f9bfdc770507ccacfc5e46074fa0020', NULL, 'kavita12341-gavhane', 'kavita1', 'gavhane', NULL, '698523659851', '', '0', '2016-11-27', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '0', '1', 'no', NULL, '2018-07-05 23:28:05', '2018-07-05 23:28:05', NULL),
(33, 'kavita123418', '1', '', '', NULL, 'kavita@aditus.infi', '$2y$10$LgXyVmZ/8b/zU5C4/7v8POiS1yZ5tVjXIRA.ujKoG7er/.wMRUGz6', '2018-07-21 05:30:27', '2018-07-21 00:00:00', 'f6d962f8e9a2f0f03a2482288fd82df9', NULL, 'kavita123418-gavhane', 'kavita1', 'gavhane', NULL, '6985236598513', '', '0', '2016-11-27', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '0', '1', 'no', NULL, '2018-07-05 23:30:27', '2018-07-05 23:30:27', NULL),
(35, 'arina', '4', '', '', NULL, 'arina@mailinator.com', '$2y$10$VYosjOCcd4Dv8ZebLZ5IGup3CL9SRy1.WFoD1F2nJMsdx2ucbs7k.', '2018-07-21 00:00:00', '2018-07-21 00:00:00', '215d19c2c73731d42406dc10b4d51ecb', '8nIsVvEU1InGY7irbalxToucCWZ38HTDit0h2lqR1IODUxpZ9PaMXwAyNb2N', 'arina-sha', 'arina', 'sha', NULL, '776894427', '', '0', '2000-07-02', NULL, '', '', '', NULL, -2548.8, 'no', '', '1', '1', 'off', 'on', 'off', '1', '1', 'no', NULL, '2018-07-08 23:09:38', '2018-12-04 04:34:47', NULL),
(36, 'sagarp@8888', '1', '', '', NULL, 'sagarp@o3enzyme.com', '$2y$10$XQqmtDvsbi9f32MaErncVuauVyXyG5tuYBqLE6Kql2TNfC4grlfWe', '2018-07-21 04:51:45', '2018-07-21 00:00:00', '082794e889708fc9b5b86a4a89498e42', NULL, 'sagarpat8888-pagar', 'sagar', 'pagar', NULL, '9921840141', '', '1', '1985-11-08', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '0', '1', 'no', NULL, '2018-07-09 22:51:45', '2018-07-09 22:51:45', NULL),
(37, 'sagark@8888', '1', '', '', NULL, 'sagark@o3enzyme.com', '$2y$10$NSRgxvuSQzhm3XIfRg8J9.j7gJCSlH0or7OFht6LRyA/5T.QIe26O', '2018-07-21 04:54:31', '2018-07-21 00:00:00', '6aede5a2588636534644137d897baa59', NULL, 'sagarkat8888-pagar', 'sagar', 'pagar', NULL, '8208400949', '', '1', '1985-11-08', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '0', '1', 'no', NULL, '2018-07-09 22:54:31', '2018-07-10 05:04:18', NULL),
(38, 't@8888', '1', '', '', NULL, 'g@gmail.com', '$2y$10$FE6fEFjY0a/Kayhi52g2q.YMmSlV1EHM7f9GyTPP32RSDMGogvzGC', '2018-07-21 05:03:06', '2018-07-21 00:00:00', 'b47f86609d269c9f3f01d3796471cb96', NULL, 'tat8888-t', 't', 't', NULL, '9632589658', '', '1', '2000-07-02', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '0', '1', 'no', NULL, '2018-07-09 23:03:07', '2018-07-09 23:03:07', NULL),
(39, 'ram@888', '1', '0641', '', NULL, 'ram@enzyme.com', '$2y$10$vPE0E/pmp0OL2RC8cGh7W.qcgMptFQe9lLcVfBrAlb7s/4D1P6Y7q', '2018-07-21 05:45:33', '2018-07-21 00:00:00', '1d8c6b197f0703b48a3aa2dc7b0a09da', NULL, 'ramat888-pawar', 'ram', 'pawar', NULL, '9632587489', '', '1', '2000-07-09', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '0', '1', 'no', NULL, '2018-07-09 23:45:34', '2018-07-09 23:45:37', NULL),
(40, 'ram@123', '1', '6043', '', NULL, 'ram@o3enzyme.com', '$2y$10$j8UejHj5khuYY9BxEsfpRuKNqTJ2RgiPUB6QK5Mosv3ZNT2JCeOrm', '2018-07-21 05:48:15', '2018-07-21 00:00:00', '59eb831bfdc1a6ac6bb1303c3f0fd538', 'AIxmD0OhBVfWpoZrKuXfqvXwzJIW95kRrfC9qBsK9Q8hDAXr81LV0iNw3hr0', 'ramat123-kale', 'ram', 'kale', NULL, '9632587498', '', '1', '2000-07-10', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-07-09 23:48:16', '2018-07-10 00:10:32', NULL),
(41, 'maheshk', '1', '6102', '', NULL, 'maheshk@o3enzyme.com', '$2y$10$3gywXjpH2rFVZx8S95B7c.VGRb4eAUggeK6c3AVhXeyPu/IiwewFq', '2018-07-21 05:58:05', '2018-07-21 00:00:00', '2a68d7caf6a017719ede91c6bc0766e0', NULL, 'maheshk-kale', 'mahesh', 'kale', NULL, '9637235814', '', '1', '2000-11-08', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '0', '1', 'no', NULL, '2018-07-09 23:58:09', '2018-07-10 00:31:45', NULL),
(42, 'subhashk', '1', '6435', '', NULL, 'subhashk@o3enzyme.com', '$2y$10$ZYQ97hz6ijVvJKgrpyCl6eJQjLo9KhsgOxRqyAjDksr9X5h9EapW.', '2018-07-21 06:06:45', '2018-07-21 00:00:00', 'd5e08d92f167dc9114b18a0d8b74e2e2', NULL, 'subhashk-kale', 'subhash', 'kale', NULL, '9552480677', '', '1', '2000-07-10', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '0', '1', 'no', NULL, '2018-07-10 00:06:45', '2018-07-10 00:32:34', NULL),
(43, 'yogeshk@88', '1', '', '', NULL, 'yogesh@o3enzyme.com', '$2y$10$JIv5.E13FF513iHv.AvKs.T1P0/5qKoYUoBNfbgkG.E/JHRCO6YVK', '2018-07-21 00:00:00', '2018-07-21 00:00:00', NULL, NULL, 'yogeshkat88-kale', 'yogesh', 'kale', NULL, '8585858585', '', '1', '2000-06-26', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-07-10 00:28:41', '2018-07-10 00:29:21', NULL),
(44, 'ganeshp', '4', '5126', '5230', NULL, 'ganeshp@o3enzyme.com', '$2y$10$SzRtcugUxK1yYKeHtSfBGuZsoz24WKIepNBBP55n/dgKXBHg7e/p2', '2018-07-21 06:39:10', '2018-07-25 15:37:55', '7aee852eb7bb54bcfe9bf687dfe3a371', 'Ezp8t19RlwffV1H8IvJ2dSscKqJ4iZptE5BXNYKjm0hDDXsa5eFc86nUT9yw', 'ganeshp-patil', 'ganesh', 'patil', NULL, '88882302999', '', '', '2000-07-02', 'Nashik, Maharashtra, India', 'Nashik', 'Nashik', 'India', '', 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', '22AAAAA0000A1Z5', '2018-07-10 00:39:10', '2018-12-04 04:31:41', NULL),
(45, 'ganeshj@8', '4', '6253', '4126', NULL, 'nilesh@mailinator.com', '$2y$10$uIyZIhBfm.co5bA8.CV3BO1vpz3VVaztAfmtGQFgq0HQenGNyzd1e', '2018-07-21 06:50:51', '2018-08-02 11:30:53', 'ccc265bbf37fe25ae72b47f826913307', 'AEivaSTK3jPsOnI2ElJVQK0pPAzyH0LdZHQxmMEU9KPziBVZVXqq4P59KMIW', 'ganeshjat8-kale', 'Ganesh', 'Datir', NULL, '9632585488', '', '1', '1981-01-01', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', '664e67518a6a0b060c3c7900104b4f1930597c73.jpg', 3168, 'no', '', '1', '1', 'on', 'on', 'on', '1', '1', 'no', NULL, '2018-07-10 00:50:51', '2018-12-04 04:34:50', NULL),
(46, 'masher', '1', '3261', '', NULL, 'maheshkk@o3enzyme.com', '$2y$10$l0tKyyIwDbflO.9V9mbj9e5tN3kxe8fie7yn2b98A48UkVaNWYnJ6', '2018-07-21 07:08:59', '2018-07-21 00:00:00', '0904347818aecf42d1d9f2eb5a2fbf66', NULL, 'masher-kale', 'mahesh', 'kale', NULL, '3232323232', '', '1', '2000-07-02', NULL, '', '', '', NULL, 0, 'no', '', '0', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-07-10 01:08:59', '2018-07-10 01:40:43', NULL),
(47, 'jsj', '1', '4530', '', NULL, 'gsh@gsj.sjj', '$2y$10$0Opk8O6RBXNcYwg.RSDJYeO7Fa5UwI6k2bGnAtffJ/.giTREIkQsy', '2018-07-21 12:57:20', '2018-07-21 00:00:00', 'c9ba077f01b99337a2e626191b03748b', NULL, 'jsj-ah', 'gs', 'ah', NULL, '8766494994', '', '1', '2000-07-29', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '0', '1', 'no', NULL, '2018-07-10 06:57:20', '2018-07-10 06:57:23', NULL),
(48, 'test12', '1', '2534', '', NULL, 'test12@gmail.com', '$2y$10$jC3ldjbkRmYYg3jactXHdus/1bHMeVkmsI4rs1V.3VQCT1zSOLVae', '2018-07-21 05:31:46', '2018-07-21 00:00:00', '548c12e4debb8067298789e37c14e1c8', NULL, 'test12-acc', 'test', 'acc', NULL, '91123654789', '', '0', '1989-11-03', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '0', '1', 'no', NULL, '2018-07-10 23:31:46', '2018-07-10 23:31:50', NULL),
(49, 'adolf', '4', '', '', NULL, 'mahi@mailinator.com', '$2y$10$z4vfrAMuNGkMNFn4XxFvsexlmf1RgPjcgf9jqBEb0lrufZ098YZ.q', '2018-07-21 00:00:00', '2018-07-21 00:00:00', 'ad79eaf61cc380f3e8ff367a65d91560', '739Ontjq4tmotQQAvkoODLvpxFibqxMtaUZXLYLdupZ19NgyZ9AY1NQVIaKO', 'adolf-rebelo', 'adolf', 'rebelo', NULL, '9834848343', '', '1', '2000-07-06', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'on', 'on', '1', '1', 'no', '22AAAAA0000A1Z5', '2018-07-17 03:07:52', '2018-12-04 04:31:41', NULL),
(50, 'priyanka', '4', '', '', NULL, 'priyanka@mailinator.com', '$2y$10$rGZT1yubZVcwbdHzVwEgxObsa2j5UXnhOSmL/retNVcC.M3qodKj2', '2018-07-21 00:00:00', '2018-07-21 00:00:00', NULL, 'UtILEyWnBqi5q11Hmp1sOfT60GJTzVjt3ADON6XiwuCtFbsFBH0wU7qk2fyC', 'priyanka-test', 'test', 'test', NULL, '854789632', '', '0', '2000-07-01', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-07-17 05:50:39', '2018-12-04 04:34:54', NULL),
(51, 'sagart888', '4', '6103', '', NULL, 'sagart@o3enzyme.com', '$2y$10$QQ9ce1Yrb5eHS5639zHXtOws63mz8DJRLsNn8VuYUg1q.vDW3XWyy', '2018-07-21 11:55:59', '2018-07-21 00:00:00', 'fa1d831d1f48916af1402d9b3b06806f', 'cboWVOkE8bXlwwSkI8nCcHmgTxaxxhhBuQhIGYTkqwFdmMHq2VQW5xZthIwB', 'sagart888-tarun', 'sagar', 'tarun', NULL, '9895656896', '', '', '2000-07-17', 'Nashik, Maharashtra, India', 'Nashik', 'Nashik', 'India', 'e7cfbd0ffee490d7d5f0c3d30a7d05858c6f7814.jpg', 0, 'no', '', '1', '1', 'on', 'on', 'on', '1', '1', 'no', '22AAAAA0000A1Z5', '2018-07-17 09:56:00', '2018-12-04 04:31:41', NULL),
(52, 'taruns888', '4', '4205', '4035', NULL, 'mayurk@mailinator.com', '$2y$10$bKKAPIb9XMNhVPDXLq/ghu7OGdC3/NEtSmxWbl7Qd9L059Yis5x26', '2018-07-21 11:56:13', '2018-09-11 18:20:53', NULL, 'FKSiWCqHJ1gJIAAYDb4P8ZpXBP6jhey3OQ1xOd8RLT8e3FoikFhjqgWsBsnf', 'taruns888-sagar', 'Tarun', 'Sagar', NULL, '+917775039390', '+917775039395', '1', '1993-12-12', 'Nashik, Maharashtra, India', 'Nashik', 'Nashik', 'country', '6ca1be6838a104e892d68fde36bed4132e138b88.jpg', 1403761.2, 'no', '', '1', '1', 'on', 'on', 'on', '1', '1', 'no', '22AAAAA0000A1Z5', '2018-07-17 09:56:14', '2018-12-04 04:35:31', NULL),
(53, 'mahesh', '1', '6102', '', NULL, 'maheshk@gmail.com', '$2y$10$6qFWP2XJkkTmIFwOYyk4ZOqHBvsIpN3bDNFE.WXJ7gzO94h2OlXDy', '2018-07-21 12:34:02', '2018-07-21 00:00:00', '069b023a67a6bb4bcea90717a879eca2', NULL, 'mahesh-kale', 'mahesh', 'kale', NULL, '9666589635', '', '1', '2000-07-18', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '0', '1', 'no', NULL, '2018-07-17 10:34:02', '2018-07-17 10:37:02', NULL),
(54, 'maheshd', '1', '2143', '', NULL, 'mahesd@mailinator.com', '$2y$10$ojFTp3Y1p.3lesqTEMvHL.qQBcIc8D2FzxVFP5kLSJ.czNFm6CtK2', '2018-07-21 05:31:12', '2018-07-21 00:00:00', '0236da64950a4c15e0db34a6b5448de9', NULL, 'maheshd-deore', 'mahesh', 'deore', NULL, '9632587452', '', '1', '2018-07-18', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '0', '1', 'no', NULL, '2018-07-18 03:31:12', '2018-07-18 03:31:16', NULL),
(55, 'maheshh', '1', '0651', '', NULL, 'maheshd@mailinator.com', '$2y$10$H8arR9sfP2g8oh78wOYKxuqCAV774GTtwOShzh8PbEJjY2N/oICw.', '2018-07-21 11:04:21', '2018-07-21 00:00:00', '7350e5994aa3d59fdd83be219cf3ee8f', NULL, 'maheshh-deore', 'mahesh', 'deore', NULL, '9632587455', '', '1', '2018-07-18', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-07-18 09:04:21', '2018-07-18 09:04:50', NULL),
(56, 'jayeshp', '1', '6014', '', NULL, 'jayeshp@mailinator.com', '$2y$10$9W7vtz4PY1IqT4/Pr9wL..aKQ1JDVATFsMpMnouGVq9XRzZSUd0H2', '2018-07-21 11:43:35', '2018-07-21 00:00:00', 'ec1be19574f9b9572e5c28cf2a4f9ba2', 'rJS9MA09IbN35QjUFbSWKHwGO7s3roBU5RwkQ3aW0sdgbeVmUIzfxUniRkEM', 'jayeshp-kale', 'jayesh', 'kale', NULL, '9632587458', '', '0', '2018-07-18', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-07-18 09:43:35', '2018-07-18 09:47:28', NULL),
(57, 'up', '1', '1452', '', NULL, 'up@mailinator.com', '$2y$10$KcNPS4k/G9jNJULEL7J8deHtc33Vp1hDPdpRz/.g3TcdCfLNET942', '2018-07-21 11:46:07', '2018-07-21 00:00:00', 'f3bf7f7fb14a53619d30c866178a9630', '9NmVJG7xHMHOgXeVnSsf8M6DBKel3tU9kkduZU8C4dBo1qhWoBwyAukWgXDY', 'up-ko', 'opkk', 'kokkkk', NULL, '9876543218', '', '1', '2000-07-05', 'Meerut, Uttar Pradesh, India', 'Meerut', 'Uttar Pradesh', 'India', '', 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-07-18 09:46:07', '2018-07-19 15:53:15', NULL),
(58, 'john', '1', '4502', '', NULL, 'pankaj@mailinator.com', '$2y$10$SlafrOndmSO5vXO473RE6uqKESkFe2Dy8So/RP6NXmGkK4IujXP0K', '2018-07-21 11:50:19', '2018-07-21 00:00:00', NULL, NULL, 'john-peterson', 'john', 'peterson', NULL, '9899889282', '', '', '2000-07-18', 'Mumbai Central Railway Station Building, Mumbai Central, Mumbai, Maharashtra 400008, India', 'Mumbai', 'Mumbai', 'India', '', 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-07-18 09:50:19', '2018-07-18 12:35:34', NULL),
(59, 'test', '1', '6204', '', NULL, 'test@mailinator.com', '$2y$10$ZO2kaRGwKfLtSwB3Q7Ts.uMRu5ZKPdWJJCYENVhdOq12DeO/ylE9i', '2018-07-21 10:33:21', '2018-07-21 00:00:00', '6d233ca7114f7204eda7686573d4af4e', NULL, 'test-user', 'Test', 'User', NULL, '7776894427', '', '0', '2000-07-07', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'on', 'on', 'on', '1', '1', 'no', NULL, '2018-07-19 08:33:21', '2018-07-19 08:41:18', NULL),
(60, 'harishp', '1', '2053', '', NULL, 'tarunl@mailinator.com', '$2y$10$pr.NqU3jCPCpUNFDZkzUH.gyAzdSSKQXkqUJ8zOZPAGao4Ji4juUq', '2018-07-21 11:12:59', '2018-07-21 00:00:00', '0b2987856c31c879a0fe30cfb6a19b9c', NULL, 'harishp-pawar', 'harish', 'pawar', NULL, '9674852387', '', '1', '1985-07-26', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', '', 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-07-20 09:13:00', '2018-07-20 09:23:26', NULL),
(61, 'yogeshk', '1', '5043', '', NULL, 'yogeshk@mailinator.com', '$2y$10$Z/cyTlD1Kv7IG.Xaqiq.AOp28hOTswtdx.wZGutOVFnrWjxYUXeZa', '2018-07-21 12:36:12', '2018-07-21 00:00:00', '61813ad59fde4c899467b04c960a07fe', NULL, 'yogeshk-kumar', 'yogesh', 'kumar', NULL, '8569743585', '', '1', '1980-07-20', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-07-20 10:36:12', '2018-07-20 10:37:25', NULL),
(62, 'mm8888', '1', '6215', '', NULL, 'mm@mailinator.com', '$2y$10$WRqCWd0QsStJRglOOUbh.evOwWC63N66fJDFZrDBCtvrLrn5U5KdC', '2018-07-21 13:04:58', '2018-07-21 00:00:00', '6af80c31731e940f9663f9215b597daf', NULL, 'mm8888-mule', 'mahesh', 'mule', NULL, '9632587856', '', '1', '2000-07-21', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-07-21 11:04:58', '2018-07-21 11:06:22', NULL),
(63, 'sagars', '1', '0', '6154', NULL, 'san@mailinatr.com', '$2y$10$71zrQehVpwgny.ooxiT7q.U/HzGci4cY76g7wzucVBzWG15ZfdeuK', '2018-07-21 18:48:39', '2018-08-01 14:51:11', '602afd30d2c1cee5429a93a49ee0f66c', 'VtVlm5DwdD9CmkeYqCSkhjLAq3X69ijWiR7n59eRyn6bQKtXnWBnJ7WUkQr4', 'sagars-s', 'sagar', 'sang', NULL, '8023232323', '', '', '1999-12-06', 'Nashik Road, Nashik, Maharashtra, India', 'Nashik', 'Nashik', 'India', '', 22593, 'no', '', '1', '1', 'off', 'off', 'off', '0', '1', 'no', NULL, '2018-07-21 16:48:39', '2018-08-01 08:51:23', NULL),
(64, 'mack.john', '1', '0', '0', NULL, 'mack.john@gmail.com', '$2y$10$h0YFMmIz0uEYHCJynBRXbuXG/0eeBwZIm.FVqT11ChZjtSU7Tavj.', '2018-07-23 10:34:58', '2018-07-23 10:34:58', 'fce0f4055b7d295e89793f823cd3a116', NULL, 'mackjohn-mack', 'john', 'mack', NULL, '98797954545', '', '1', '1993-07-23', 'Pune, Maharashtra, India', 'Pune', 'Maharashtra', 'India', '', 0, 'no', '', '1', '1', 'off', 'off', 'off', '0', '1', 'no', NULL, '2018-07-23 08:34:58', '2018-07-24 16:53:49', NULL),
(65, 'dev@webwingtechnologies.com', '1', '3541', '0453', NULL, 'dev@webwingtechnologies.com', '$2y$10$g8cOXLdTV5hpItKrdOVKBuvwyhoUWLZ1aGCejmg7lfj37KrPyXtF6', '2018-07-23 11:53:06', '2018-07-23 11:53:06', '3eaaa8f1d21d463062320f80fa64e8bd', NULL, 'devatwebwingtechnologiescom-sonawane', 'Vaibhav', 'Sonawane', NULL, '9511684952', '', '1', '2000-07-23', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '0', '0', 'no', NULL, '2018-07-23 09:53:06', '2018-07-23 09:53:11', NULL),
(66, 'ragav.raj', '1', '0', '0', NULL, 'ragav@gmail.com', '$2y$10$4CN3mKPIndqPP/ErOdo5peg4dUPj.eFDGUsZbUp52ZKIj5cd9m4Q2', '2018-07-23 13:16:58', '2018-07-23 13:16:58', 'c259c133bbdcb6df9c02b1b5dc8f39f1', NULL, 'ragavraj-raj', 'ragav', 'raj', NULL, '756957987544', '', '1', '1998-07-23', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-07-23 11:16:58', '2018-07-23 11:18:45', NULL),
(67, 'sagark', '1', '0', '0', NULL, 'sagark@mailinator.com', '$2y$10$MwGSGuK33TU6AiF/Bfnsf.SxPkxNxVcEh88UA5dZHni9fQdOhMRsO', '2018-07-24 15:03:00', '2018-07-24 15:03:00', 'c8c37d17c18c49950a18bd67db867bc9', 'pRjDIxiIDhE1CMxOOvOxUDzX7FO1NV3TQnGujcRkq426HDvszzmH5eTdeXQT', 'sagark-kale', 'sagar', 'kale', NULL, '8888230297', '', '1', '2000-07-24', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', '', 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-07-24 13:03:00', '2018-07-24 13:28:34', NULL),
(69, 'ghjkj', '1', '1604', '2410', NULL, 'jhh@gmail.com', '$2y$10$336lAjMoarDUJOudS5Hrnejo.G0HwNVB0hQRfoDhel3TiTDeC6/O6', '2018-07-24 15:31:14', '2018-07-24 15:31:14', '81d2e1c7401b92891266d4f173a1b5a6', NULL, 'ghjkj-hjjjj', 'fhjkhgg', 'hjjjj', NULL, '555566545555', '', '1', '2018-07-24', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '0', '0', 'no', NULL, '2018-07-24 13:31:15', '2018-07-24 13:31:19', NULL),
(70, 'tushar@888', '1', '0', '0', NULL, 'tusharm@mailinator.com', '$2y$10$LEm9A8m2hWQhQ/kkiN7td.3VkFdfBE/ypLADW3BLepB1WXZIx.ZJe', '2018-07-26 10:28:39', '2018-07-26 10:28:39', 'd9e3d9b90b674e0e9261d7decdb89ced', NULL, 'tusharat888-kale', 'tushar', 'kale', NULL, '447465511385', '', '1', '2000-07-26', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-07-26 04:28:40', '2018-07-26 04:31:07', NULL),
(71, 'pawan', '1', '0', '0', NULL, 'pawan@mailinator.com', '$2y$10$AHgDZmLJc3MFvvWPR/5fH.1dvMRUOIPDmorDSfXL2G0B7KoTQ.NE2', '2018-07-30 19:11:39', '2018-07-30 19:11:39', '0bcd6bbd16a1406c3c2288e5017e07f8', NULL, 'pawan-kumar', 'Pavan', 'kumar', NULL, '9852698548', '', '', '2000-07-30', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', '3ec5b61352204e51b5a461657474b2cde659ccb8.jpg', 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-07-30 13:11:39', '2018-07-30 13:24:18', NULL),
(75, 'smstest', '1', '2635', '3021', NULL, 'smstest@mailinator.com', '$2y$10$HRPzmbV8q5YFRj8ZcfZKq.P.HA6zFdzXCe05oidrtp.MkWAGXp21q', '2018-08-01 13:25:11', '2018-08-01 13:25:11', '32d2e9d5ac254ac8479c75611811fe4b', NULL, 'smstest-k', 'Priyanka', 'K', NULL, '+919767366699', '', '0', '2016-11-27', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '0', '0', 'no', NULL, '2018-08-01 07:25:11', '2018-08-01 07:25:16', NULL),
(76, 'sagar', '1', '0', '6504', NULL, 'sangs@mailinator.com', '$2y$10$L2/OvKcMIfsLO12XIokOwO7FnYPkrSD4HqtY8odxv8H4hM2Iz5RCm', '2018-08-01 14:32:00', '2018-08-01 14:55:07', NULL, NULL, 'sagar-s', 'sagar', 'shshhs', NULL, '+917887900961', '', '', '2000-08-01', 'Mumbai Naka, Renuka Nagar, Nashik, Maharashtra 422011, India', 'Nashik', 'Nashik', 'India', 'f207ad07b799343309a655f80df6fe5abc61735a.jpg', 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-08-01 08:32:00', '2018-08-01 11:07:32', NULL),
(77, 'adhi', '1', '0635', '1245', NULL, 'adhi.pr16@gmail.com', '$2y$10$KxedCTVcFUiVxuT3CEgg1eoVUo8xehCZeeDf2Sd.TIueJJ6PYYYnS', '2018-08-01 15:17:32', '2018-08-01 15:17:32', 'ed2878e9856987f554bc1710de9c0a95', NULL, 'adhi-prasad', 'Adhithiya', 'Prasad', NULL, '9841062356', '', '1', '1978-08-01', NULL, '', '', '', 'https://lh4.googleusercontent.com/-ge2ISra6ki8/AAAAAAAAAAI/AAAAAAAAAAA/AAnnY7pZ2IFLyTQU_e3dOmZsk_2TXOhQyQ/mo/photo.jpg', 0, 'no', 'GPlus', '0', '1', 'off', 'off', 'off', '0', '0', 'no', NULL, '2018-08-01 09:17:33', '2018-09-05 09:26:11', NULL),
(78, 'c.jitendra', '1', '3056', '1036', NULL, 'jotendra@mailinator.com', '$2y$10$Gc8LMeYz/WBJvCb6877woupGe07Vl.8j2BcQp84cmp8/OrDhW.3KW', '2018-08-01 18:26:10', '2018-08-01 18:26:10', '48d41e23a1a3de138a1b5cfc03853195', NULL, 'cjitendra-j', 'jitendra', 'j', NULL, '8956585668', '', '1', '1996-08-01', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '0', '0', 'no', NULL, '2018-08-01 12:26:12', '2018-08-01 12:26:20', NULL),
(79, 'poorva', '1', '', '', NULL, 'poorvak@webwing.com', '$2y$10$H9QFdoqvSasBol2D5DLc4..dddqSdOXX1lOer4Sly84FlFAIgEUQC', '0000-00-00 00:00:00', NULL, '348e17c4a38ba4d7c67782e23cac30ca', NULL, 'poorva-kulkarni', 'poorva', 'kulkarni', NULL, '999999999', '', '0', '1992-08-01', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '0', 'no', NULL, '2018-08-01 12:27:37', '2018-08-02 11:16:43', NULL),
(80, 'poorvaweb', '1', '', '', NULL, 'webwing.testing@gmail.com', '$2y$10$pGzVGuSn95BlGXVdbJb/hez0ZDKBhsnKhpA3NMgNDNHRe49KxuHIm', '0000-00-00 00:00:00', NULL, NULL, 'ubcMdWojngtoN7vSjMG1gqQFVLMCp0RmvKvmG7R283ChsEv6ZS6hXbJ4GoD1', 'poorvaweb-k', 'poorva', 'k', NULL, '8378942132', '', '0', '1992-07-08', 'Pune, Maharashtra, India', 'Pune', 'Maharashtra', 'India', '3582f50e64100ea65cee1040d73ef45935b437ca.jpg', 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-08-01 12:43:18', '2018-08-07 04:59:53', NULL),
(81, 'webwing', '1', '', '', NULL, '1725482972asdsad', '$2y$10$yCpB.iwPzHRK5cAP4OsBLeeZBp6zoD5hMt4nY3F1dxBZzTQ13PL1y', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, 'sTjmt70aQVMzD0rEu2pY9TpJ5qe2sdUpjv9xUJBak05VXiWotu00qbGM1M0m', 'webwing-twitter', 'Webwing', 'last', NULL, '6547874152', '', '1', '1992-02-04', 'Argentia Road, Mississauga, ON, Canada', 'Mississauga', '', '', '6b67cc1dcfecb3fddbd0d8890f89f6f8f63350a0.jpg', 0, 'yes', '', '1', '1', 'on', 'on', '', '1', '1', 'no', NULL, '2018-08-02 05:59:31', '2018-08-06 03:31:44', NULL),
(82, 'konark', '1', '0', '0', NULL, 'konark@mailinator.com', '$2y$10$poVfIiodV4TsN1TSBYeUPu4/O.LyPxuaUVkOFg6pqUZ5YLxFl1rPi', '2018-08-02 19:12:55', '2018-08-02 19:12:55', '5ef99f527ccd4d13c7377e201c25cdd8', NULL, 'konark-kalo', 'konark', 'kalo', NULL, '9879879789', '', '1', '2000-08-02', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-08-02 13:12:55', '2018-08-02 13:17:09', NULL),
(83, 'johnkl', '1', '0', '0', NULL, 'john@mail.com', '$2y$10$BNsiRZnzlqh/Gh0ADOH5m.LfsfixoGy1w/D5LjE622X96JUAQ8nXO', '2018-08-02 19:25:55', '2018-08-02 19:25:55', '1a9be4f9cad9cc69649ced73f4e30a16', NULL, 'johnkl-janny', 'john', 'janny', NULL, '9875463128', '', '1', '2000-08-02', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-08-02 13:25:55', '2018-08-02 13:26:27', NULL),
(84, 'karlos', '1', '0', '0', NULL, 'karlos@mail.com', '$2y$10$UNH4qWj/swp145cKE1AepuSlhBD5u7geC9InydwynkZKl1rZoMZzG', '2018-08-02 19:38:23', '2018-08-02 19:38:23', '0e9e9418aefadb73105473c13855a20e', NULL, 'karlos-klop', 'karlos', 'klop', NULL, '+919787646497', '', '1', '2000-08-02', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '0', 'no', NULL, '2018-08-02 13:38:23', '2018-09-21 11:46:14', NULL),
(85, 'karon', '1', '0', '0', NULL, 'karon@gmail.com', '$2y$10$L.OU6Zo5W9lnb1yQwQa2OuZymufpKt46NRfPGkr7WnS1vMTQv2rvG', '2018-08-03 10:31:06', '2018-08-03 10:31:06', 'da7a7a01d774f7072bfe17942ebbb854', NULL, 'karon-polard', 'Karon', 'polard', NULL, '9879898989', '', '1', '2000-08-03', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-08-03 04:31:07', '2018-08-03 04:34:49', NULL),
(86, 'mindit', '1', '0', '0', NULL, 'delhi@gmail.com', '$2y$10$e9lHFaFx7VlyEDQNKa/.KeZsUGeqQ23bvLL0Qwfa7UdlMHYpioXpa', '2018-08-03 10:41:34', '2018-08-03 10:41:34', '22e86fc3bf0956dd2cd3b76434f92561', NULL, 'mindit-ddllj', 'ddllj', 'ddllj', NULL, '9879976497', '', '1', '2000-08-03', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-08-03 04:41:34', '2018-08-03 04:46:52', NULL),
(87, 'mathew', '1', '0', '0', NULL, 'mathew@gmail.com', '$2y$10$ucsciwl1aMRd0e.RBbE3W.ttEVkrcSNhd/asrTzrz4xdSvjFaGgue', '2018-08-03 11:42:14', '2018-08-03 11:42:14', 'a55f48c9730504c616f4192ff1a8daa1', NULL, 'mathew-headon', 'mathew', 'headon', NULL, '9879997676', '', '', '2000-08-03', 'Mumbai, Maharashtra, India', 'Mumbai', 'Maharashtra', 'India', '', 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-08-03 05:42:15', '2018-08-03 06:07:53', NULL),
(88, 'johnc', '1', '0', '0', NULL, 'johnc@mail.com', '$2y$10$/nmCag2uktpizJY1J950I.skG2ISltLooPZFxIgJcjEuCm7kvcUKi', '2018-08-03 12:38:22', '2018-08-03 12:38:22', 'c386581ca6071f4fffd0ab3a3f690fb1', NULL, 'johnc-cena', 'johnc', 'cena', NULL, '9879769797', '', '1', '2000-08-03', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-08-03 06:38:22', '2018-08-03 06:39:08', NULL),
(89, 'peterson', '1', '0', '0', NULL, 'peters@gmail.com', '$2y$10$DicGUVtHVVRSFIjk23EoGuiYu77tZX5Bznt.mFJgcJNEKSryRPgoi', '2018-08-03 12:41:27', '2018-08-03 12:41:27', 'ef7925eb4e7f222fbb0dbf459239b43c', NULL, 'peterson-son', 'peter', 'son', NULL, '9879797973', '', '', '2000-08-03', 'Mumbai Central, Mumbai, Maharashtra, India', 'Mumbai', 'Maharashtra', 'India', '', 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '0', 'no', NULL, '2018-08-03 06:41:27', '2018-09-04 14:28:41', NULL),
(91, 'jitendra.c', '1', '0', '0', NULL, 'jitendra@mailinator.com', '$2y$10$BxcarglSy1FIuKrHOHTFsuzgeDuGiozXoxxiR6v6MEVz5f3/jZ6g6', '2018-08-03 14:49:27', '2018-08-03 14:49:27', '7f0f238dbbf08a35e742a5af64d986a9', NULL, 'jitendrac-c', 'jitendra', 'c', NULL, '5965865455', '', '1', '1998-08-03', 'Pune, Maharashtra, India', 'Pune', 'Maharashtra', 'India', '', 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-08-03 08:49:27', '2018-08-03 10:05:04', NULL),
(92, 'rp7254653@gmail.com', '1', '', '', NULL, 'rp7254653@gmail.com', '$2y$10$hdMdNSxMx.JuuL4./izhZOE1IAV5D9dFDELbS/ie/vzS9dHEYNqs6', '0000-00-00 00:00:00', NULL, NULL, NULL, '', 'rp7254653@gmail.com', '', NULL, '', '', NULL, '0000-00-00', NULL, '', '', '', NULL, 0, 'yes', 'gmail', '1', '1', 'off', 'off', 'off', '0', '0', 'no', NULL, '2018-08-03 08:56:52', '2018-08-03 08:56:52', NULL),
(93, 'webwingt@gmail.com', '1', '', '', NULL, 'webwingt@gmail.com', '$2y$10$a2SbgokcvrclfMnB9GtbOOGkvjkgtX8Xoq4u3hW.cxsEZ0x0IcUnC', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, 'J7ni4MQ4pQ0Bid4RwVW3r1314LiM2tNhSXikRC8jKhKM0kQ3jzeipdjOhg0h', '', 'John', 'Smith', NULL, '+918793944112', '', NULL, '0000-00-00', NULL, '', '', '', 'https://lh5.googleusercontent.com/-77Z6qzZiNYc/AAAAAAAAAAI/AAAAAAAAAQA/wegISf6cn-c/s100/photo.jpg', 0, 'yes', 'gmail', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-08-09 04:36:13', '2018-08-13 11:59:36', NULL),
(94, 'webwing', '1', '', '', NULL, '1725482972', '$2y$10$BHo33ZZULJYvFuTy.S5/GeiReFblTWPN8uOm4Ta4F9GMpgDMdKNKy', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, 'yqrfvZvbVvh9wtjyGoxbRvYG2ULTrtibX5v6nvQ80wLzFXOjwKZTMowNodEf', '', 'webwing', '', NULL, '+918793944112', '', NULL, '0000-00-00', NULL, '', '', '', 'http://pbs.twimg.com/profile_images/1026033342926413825/HAjGlilL.jpg', 0, 'yes', 'twitter', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-08-09 04:37:20', '2018-08-14 07:21:25', NULL),
(96, 'philipe-samba', '1', '', '', NULL, 'philipe-samba@trash-me.com', '$2y$10$KAJO1kGQhtP.neNzeCGR3.vB6GLqlBKhwVXyNO1c/MvSPwt9sZFYu', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, 'AEKd2UfKwxJhHBm1vnb0l1KaHfm94ZJ4T3HQhkAc7zFEa1fbnm28Tzlqbvwg', 'philipe-samba-datir', 'philipe', 'datir', NULL, '+9125874596325', '', '1', '1999-03-03', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-08-10 05:55:16', '2018-08-10 05:56:46', NULL),
(97, 'seona.frank', '1', '', '', NULL, 'seona.frank@opentrash.com', '$2y$10$I0HFR0APpgnZhme8wJ.fPeGX3nYR8D5a9mOFai3N6r9ACPDQlCFeu', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, 'wMd2wCUKSBYQIKUfxKz4SQHHkkX4qCOK6wIRwBhJTRbXSxkOPle83QZTkrsA', 'seonafrank-frank', 'seona', 'frank', NULL, '+96584175289541', '', '0', '1999-02-01', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-08-10 06:00:50', '2018-08-10 06:02:13', NULL),
(98, 'scallum710@gmail.com', '1', '', '0', NULL, 'scallum710@gmail.com', '$2y$10$5SPS6KjKRjtSoacfcVcC6Om6kekm0FE8ccI1yQjgIx0zdkmPbKFj2', '0000-00-00 00:00:00', '2018-08-14 11:16:25', NULL, NULL, 'jk', 'Callum', 'Smith', NULL, '6979784949', '6979784949', '', '0002-11-30', 'Mumbai, Maharashtra, India', 'Mumbai', 'Maharashtra', 'India', '', 0, 'yes', 'gmail', '1', '1', 'off', 'off', 'off', '0', '1', 'no', NULL, '2018-08-13 06:11:14', '2018-08-14 06:34:49', NULL),
(100, 'yanikluis5@gmail.com', '1', '', '0', NULL, 'yanikluis5@gmail.com', '$2y$10$hi2V6r4POtev9jfTujMat.Ei8MLugLtIdXCGiIfHJcPF/DS4ulAD6', '0000-00-00 00:00:00', '2018-08-14 11:14:55', NULL, NULL, 'kk', 'Yanik', 'Luis', NULL, '69978469948', '69978469948', '', '0002-11-30', 'Nashik, Maharashtra, India', 'Nashik', 'Nashik', 'India', 'https://graph.facebook.com/v3.1/2027842747530788/picture?height=400&width=400&migration_overrides=%7Boctober_2012%3Atrue%7D', 0, 'yes', 'facebook', '1', '1', 'off', 'off', 'off', '0', '1', 'no', NULL, '2018-08-13 06:43:45', '2018-08-14 13:19:07', NULL),
(101, 'johnk', '1', '0', '0', NULL, 'john@k.com', '$2y$10$KEWVKTP3WijqsfHNJsyZ9elSY6QQa8UWGb9CCXcjFjJlu69g.HUda', '2018-08-14 11:19:10', '2018-08-14 11:19:10', 'b6dd2652760f931e11cc7013667c6404', NULL, 'johnk-k', 'john', 'kkk', NULL, '9788646497', '', '', '2000-08-14', 'Nashik, Maharashtra, India', 'Nashik', 'Maharashtra', 'India', '', 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-08-14 05:19:11', '2018-08-14 05:23:05', NULL),
(102, 'jait', '1', '', '', NULL, 'jait@webwing.com', '$2y$10$BA3Eu.tENQV/VWP8zQ1XxOrWopAbP737l2rXndr0IM3AE0eX5j44S', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 'IAYFAb6K4aulGXJOvlvx4XzUZwu7CAeGdDs85pmgLn7kyuP3WFOnxlins5io', 'jait-t', 'jai', 't', NULL, '+918547896587', '', '0', '2000-04-02', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-08-14 05:29:53', '2018-09-05 10:32:43', NULL),
(103, 'umesh', '4', '', '5461', 9, 'demo@patonce.com', '$2y$10$bKKAPIb9XMNhVPDXLq/ghu7OGdC3/NEtSmxWbl7Qd9L059Yis5x26', '0000-00-00 00:00:00', '2018-10-10 18:01:11', '6c955b470f1403015f3660b1b0ec1465', 'VmOxtx9dg0k0OS0AD0hMcflgEJRIazavcKqK4S6PxT7FcPp2xlflQYwDZLII', 'Umesh Bhau', 'Umesh', 'Kale', '+91', '8888230299', '8149905936', '1', '2000-08-18', 'Mumbai Central Railway Station Building, Mumbai Central, Mumbai, Maharashtra, India', 'Mumbai', '', '', '8fd11f057d276f4aeeac6aab200df5d6d05a6602.png', 50, 'no', '', '1', '1', 'on', 'on', 'on', '1', '1', 'yes', 'Awertyuiop', '2018-08-18 10:49:36', '2019-02-08 05:08:18', NULL),
(105, 'deepak_temp', '1', '0', '', NULL, 'deepak@mailinator.com', '$2y$10$0JDn1rdliB7VnnmLi9.Doe6t2nPDJxkLXekxIkQgezBWirtx577sy', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, 'deepak-temp-salunke', 'deepak', 'salunke', NULL, '+918149905936', '', '0', '2000-07-11', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-09-03 05:13:51', '2018-09-04 14:28:56', NULL),
(106, 'sacjac', '4', '0', '5031', NULL, 'sachin@webwingtechnologies.com', '$2y$10$iB8Nmf1iY0HHb4oO.1lMqOzqK88HCTWu4vf1DdJzQpvHwxhERm3wm', '0000-00-00 00:00:00', '2018-09-04 20:53:36', '84801f1cba7f471c8219813920de8487', 'UdktEwvSjRHsYCNgP99zNUgfgXBf9K8iOWGdUhQPJPrGrsmpy9aezAoopGkt', 'sachindada', 'Sac', 'Jac', NULL, '+919423538067', '', '1', '1990-10-26', 'FCI Road, Bardiya Nagar, Manmad, Maharashtra, India', 'Manmad', '', '', 'a4719e233afe7faf57b63c9b110a4a9e113258d7.jpg', 0, 'no', '', '1', '1', 'on', 'on', 'on', '0', '1', 'no', NULL, '2018-09-04 14:53:36', '2018-12-04 04:35:02', NULL),
(108, 'sagar.sainkar', '1', '0', '', NULL, 'sagars@webwingtechnologies.com', '$2y$10$FuKlKIgLt5jX.3P3Au9QBeN0vllkyLGT9GQvoWhztVOigL4ciFuAG', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, 'liGyRmL5SFp8WwjMlG7NJHJktHqrNArYw4CUpx5RYGZ3ggkm9atn7wOP76V5', 'sagar-sainkar', 'Sagar', 'Sainkar', NULL, '+912233445566', '', '1', '1991-01-01', 'CBS Bus Stop Nashik, Thakkar Bazar, Police Staff Colony, Nashik, Maharashtra, India', 'Nashik', '', '', '', 0, 'no', '', '1', '1', 'on', 'on', 'on', '1', '1', 'no', NULL, '2018-09-10 06:04:46', '2018-09-10 07:25:32', NULL),
(113, 'dasdeepaksalunke', '1', '0', '6142', NULL, 'dasdeepaksalunke@gmail.com', '$2y$10$8aewTfNU8JlwzU0dawhSreTDsBVK/JunCmy0JbD1C5s8OHq5LLsX.', '0000-00-00 00:00:00', '2018-10-06 11:35:38', '880e1b8294f0950d6c403a52f01044e3', NULL, 'deepak-salunke', 'Deepak', 'Salunke', '+91', '4563210987', '', '1', '2000-07-12', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '0', '0', 'no', NULL, '2018-10-01 12:31:32', '2018-10-06 05:42:15', NULL),
(114, 'ironman', '1', '0', '', NULL, 'tonystark@rsvhr.com', '$2y$10$kZPj9IAaYL.UWewPhkJu7.6sbwpfxoZPxmBxG.be.8i4G/nBQ9n0i', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, 'tony-stark', 'Tony', 'Stark', '+1', '4848698646', '', '1', '1984-06-20', NULL, '', '', '', NULL, 0, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-11-26 04:24:18', '2018-11-26 04:25:34', NULL),
(115, 'Pooja123', '4', '0', '', NULL, 'poojad@webwingtechology.com', '$2y$10$/9taxkV/NH74XYodYYnn5eESkdhjtZw2qyyUyhgnp2BSPnJV1Kbm2', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '583cf77e1cfcbbe3d51a5817f4abfbd3', NULL, 'pooja-deshmukh', 'Pooja', 'Deshmukh', '+91', '9767566699', '', '0', '2000-12-06', NULL, '', '', '', NULL, 500, 'no', '', '1', '1', 'off', 'off', 'off', '1', '1', 'no', NULL, '2018-12-11 06:48:10', '2018-12-11 07:00:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_password_resets`
--

CREATE TABLE `users_password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_password_resets`
--

INSERT INTO `users_password_resets` (`email`, `token`, `created_at`) VALUES
('sagars@webwingtechnologies.com', 'b41adb5d28bdb3ca8acfc6f7b9a5adfe14cee89fa186efcc33cd6332742cb93a', '2018-09-10 06:21:10'),
('guest@zippiex.com', '1897e6bfff6ea87ed2114b99f2bd40a646edba9080c0bd1d080686c40956659c', '2018-09-10 06:34:40'),
('mayurip@webwingtechnologies.com', 'ec8dff93a4b047710b5cd7ca9e213054f00b72e0f70e91efebae9c380d3848ee', '2018-09-11 10:55:41'),
('guest@rupayamail.com', '836772896e8315d83c9e91a17f0e14d25a09215cc77b43e66b10572c95fa5a01', '2018-10-15 08:35:20'),
('webwingt@gmail.com', 'fa33b87c630dfde0ba39ba62fa46683a7aea18d616afb4067541fa43384cc457', '2018-12-10 09:22:06');

-- --------------------------------------------------------

--
-- Table structure for table `user_bank_account`
--

CREATE TABLE `user_bank_account` (
  `id` int(50) NOT NULL,
  `user_id` int(50) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `ifsc_code` varchar(255) NOT NULL,
  `account_type` enum('1','2','3','4','5') NOT NULL DEFAULT '1' COMMENT '1=saving 2=current 3=recurring 4=demat 5=nri',
  `selected` enum('0','1') NOT NULL DEFAULT '0' COMMENT '1=selected 0=not selected',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_bank_account`
--

INSERT INTO `user_bank_account` (`id`, `user_id`, `bank_name`, `account_number`, `ifsc_code`, `account_type`, `selected`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 14, 'HDFC Bank PVT LTD.', '8523697452125874', 'NKF8563257452', '1', '0', '2018-06-11 06:49:12', '2018-07-17 04:22:07', '0000-00-00 00:00:00'),
(3, 14, 'Dena Bank', '8523697452125874', 'NK000006', '3', '0', '2018-06-11 07:14:34', '2018-07-17 04:22:07', '0000-00-00 00:00:00'),
(4, 14, 'IOB Bank', '8523697452125874', 'NK0258452', '4', '1', '2018-06-11 07:14:48', '2018-07-17 04:22:07', '0000-00-00 00:00:00'),
(5, 2, 'HDFC', '789456123123', '7894561230', '1', '1', '2018-06-12 00:04:34', '2018-06-12 22:42:39', '0000-00-00 00:00:00'),
(6, 22, 'india bank', '458525151247', 'desade', '2', '1', '2018-06-12 01:54:12', '2018-06-12 02:59:30', '0000-00-00 00:00:00'),
(7, 22, 'dessst kshix', '148575953512', 'xxxdcdcsdc', '2', '0', '2018-06-12 01:54:42', '2018-06-12 01:54:42', '0000-00-00 00:00:00'),
(8, 22, 'desswed', '452125632569', 'ddadsda', '2', '0', '2018-06-12 02:01:33', '2018-06-12 02:01:33', '0000-00-00 00:00:00'),
(9, 5, 'test', '7896541230123', '123456', '1', '1', '2018-06-12 22:39:53', '2018-06-12 22:39:53', '0000-00-00 00:00:00'),
(10, 2, 'HDFC', '7894561230788', '789456123012365', '1', '0', '2018-06-12 22:40:19', '2018-06-12 22:42:39', '0000-00-00 00:00:00'),
(17, 1, 'ICICI', '1547845612345678452', 'IC1000456', '1', '1', '2018-07-08 22:52:42', '2018-07-08 22:52:42', '0000-00-00 00:00:00'),
(22, 1, 'ICICI', '1547845611231231235678452', 'IC1000456', '1', '0', '2018-07-09 06:19:34', '2018-07-09 06:19:34', '0000-00-00 00:00:00'),
(23, 1, 'AMERICAN EXPRESS', '1547841111231231235678452', 'IC1000456', '1', '0', '2018-07-09 06:19:47', '2018-07-09 06:19:47', '0000-00-00 00:00:00'),
(24, 1, 'DELOITTE', '1547841211231231235678452', 'IC1000456', '1', '0', '2018-07-09 06:19:56', '2018-07-09 06:19:56', '0000-00-00 00:00:00'),
(25, 1, 'HSBC', '1541241211231231235678452', 'IC1000456', '1', '0', '2018-07-09 06:20:08', '2018-07-09 06:20:08', '0000-00-00 00:00:00'),
(26, 1, 'SWISS BANK', '1541241211131231235678452', 'IC1000456', '1', '0', '2018-07-09 06:20:20', '2018-07-09 06:20:20', '0000-00-00 00:00:00'),
(34, 27, 'Globant bank ewrfdcsdfvbbgh', '637838387373', 'Haj33883231', '3', '1', '2018-07-09 07:20:50', '2018-08-03 13:38:41', '0000-00-00 00:00:00'),
(37, 27, 'Apna bank', '7875555567899', '356788ghj', '4', '0', '2018-07-10 00:09:28', '2018-08-03 13:38:41', '0000-00-00 00:00:00'),
(48, 27, 'svhhs shjshs hsjb', '979799797979', 'gshh4444', '4', '0', '2018-07-18 15:43:16', '2018-08-03 13:38:41', '0000-00-00 00:00:00'),
(49, 52, 'hdxjdhdu', '7447474744747447', 'hccjhcch', '1', '1', '2018-07-20 17:14:48', '2018-08-01 07:09:33', '0000-00-00 00:00:00'),
(50, 45, 'abc banck', '5454354543545454', 'ABCO0000', '3', '1', '2018-07-21 16:26:05', '2018-07-21 16:31:43', '0000-00-00 00:00:00'),
(51, 45, 'BANK OF INDIA', '5676868987998788', 'BOB538457049', '1', '0', '2018-07-21 16:26:47', '2018-07-21 16:31:43', '0000-00-00 00:00:00'),
(52, 52, 'HDFC Bank Pvt Ltd', '3216549874523568', 'NK000006', '1', '0', '2018-07-25 13:42:09', '2018-08-01 07:09:33', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_report`
--
ALTER TABLE `admin_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `api_details`
--
ALTER TABLE `api_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog_category`
--
ALTER TABLE `blog_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog_comments`
--
ALTER TABLE `blog_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog_ip_address`
--
ALTER TABLE `blog_ip_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_enquiry`
--
ALTER TABLE `contact_enquiry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons_used`
--
ALTER TABLE `coupons_used`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currency_conversion`
--
ALTER TABLE `currency_conversion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_template`
--
ALTER TABLE `email_template`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `front_pages`
--
ALTER TABLE `front_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gst_data`
--
ALTER TABLE `gst_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `host_verification_request`
--
ALTER TABLE `host_verification_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mobile_country_code`
--
ALTER TABLE `mobile_country_code`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `my_favourite`
--
ALTER TABLE `my_favourite`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_template`
--
ALTER TABLE `notification_template`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `property`
--
ALTER TABLE `property`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `property_aminities`
--
ALTER TABLE `property_aminities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `property_beds_arrangment`
--
ALTER TABLE `property_beds_arrangment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `property_images`
--
ALTER TABLE `property_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `property_rules`
--
ALTER TABLE `property_rules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `property_type`
--
ALTER TABLE `property_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `property_unavailability`
--
ALTER TABLE `property_unavailability`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `query_type`
--
ALTER TABLE `query_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `review_rating`
--
ALTER TABLE `review_rating`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sac_data`
--
ALTER TABLE `sac_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sleeping_arrangement`
--
ALTER TABLE `sleeping_arrangement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_log`
--
ALTER TABLE `support_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_query`
--
ALTER TABLE `support_query`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_query_comments`
--
ALTER TABLE `support_query_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_team`
--
ALTER TABLE `support_team`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_bank_account`
--
ALTER TABLE `user_bank_account`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_report`
--
ALTER TABLE `admin_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `amenities`
--
ALTER TABLE `amenities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `api_details`
--
ALTER TABLE `api_details`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `blog_category`
--
ALTER TABLE `blog_category`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `blog_comments`
--
ALTER TABLE `blog_comments`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `blog_ip_address`
--
ALTER TABLE `blog_ip_address`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `contact_enquiry`
--
ALTER TABLE `contact_enquiry`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `coupons_used`
--
ALTER TABLE `coupons_used`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currency`
--
ALTER TABLE `currency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `currency_conversion`
--
ALTER TABLE `currency_conversion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `email_template`
--
ALTER TABLE `email_template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `front_pages`
--
ALTER TABLE `front_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `gst_data`
--
ALTER TABLE `gst_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `host_verification_request`
--
ALTER TABLE `host_verification_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `mobile_country_code`
--
ALTER TABLE `mobile_country_code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=254;

--
-- AUTO_INCREMENT for table `my_favourite`
--
ALTER TABLE `my_favourite`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=258;

--
-- AUTO_INCREMENT for table `notification_template`
--
ALTER TABLE `notification_template`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `property`
--
ALTER TABLE `property`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

--
-- AUTO_INCREMENT for table `property_aminities`
--
ALTER TABLE `property_aminities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1461;

--
-- AUTO_INCREMENT for table `property_beds_arrangment`
--
ALTER TABLE `property_beds_arrangment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=783;

--
-- AUTO_INCREMENT for table `property_images`
--
ALTER TABLE `property_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1254;

--
-- AUTO_INCREMENT for table `property_rules`
--
ALTER TABLE `property_rules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=243;

--
-- AUTO_INCREMENT for table `property_type`
--
ALTER TABLE `property_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `property_unavailability`
--
ALTER TABLE `property_unavailability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `query_type`
--
ALTER TABLE `query_type`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `review_rating`
--
ALTER TABLE `review_rating`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sac_data`
--
ALTER TABLE `sac_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sleeping_arrangement`
--
ALTER TABLE `sleeping_arrangement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `support_log`
--
ALTER TABLE `support_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `support_query`
--
ALTER TABLE `support_query`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `support_query_comments`
--
ALTER TABLE `support_query_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `support_team`
--
ALTER TABLE `support_team`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `user_bank_account`
--
ALTER TABLE `user_bank_account`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
