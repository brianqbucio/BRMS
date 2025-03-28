-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 27, 2024 at 06:01 AM
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
-- Database: `brms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(20) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`, `created_at`, `role`) VALUES
(1, 'admin123', '$2y$10$zBpl7Z88ebo81M2fjGREQu043XbFWyK0J2E9D57g7jKvGR5fpYlH.', '2024-03-21 15:31:51', 'head_admin'),
(4, 'admin12345', '123123123', '2024-04-11 08:40:14', 'admin'),
(5, 'admin', 'admin123456', '2024-04-11 08:45:24', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `cat_id` int(8) NOT NULL,
  `cat_name` varchar(255) NOT NULL,
  `cat_description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cat_id`, `cat_name`, `cat_description`) VALUES
(1, 'Retail', 'Involves the sale of goods directly to consumers through various channels.'),
(2, 'Technology', 'Focuses on the development, implementation, and maintenance of technological products and services.'),
(3, 'Finance', 'Encompasses activities related to managing money, investments, and financial transactions.'),
(5, 'Hospitality', 'Covers industries that provide lodging, food, and entertainment services to travelers and guests.'),
(6, 'Manufacturing', 'Involves the production of goods through various processes, from raw materials to finished products.'),
(7, 'Real Estate', 'Involves the buying, selling, renting, and management of properties, including land and buildings.'),
(9, 'Transportation', 'Encompasses the movement of people and goods from one place to another through various modes of transport.'),
(10, 'Entertainment', 'Involves activities that provide amusement, enjoyment, and relaxation to people, such as movies, music, and sports.'),
(12, 'Healthcare', 'Includes services related to the maintenance or improvement of health, such as medical treatment and wellness programs.');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment_content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `comment_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `post_id`, `user_id`, `comment_content`, `created_at`, `comment_image`) VALUES
(20, 56, 34, 'hi', '2024-05-11 06:43:27', NULL),
(21, 60, 41, 'yes', '2024-05-24 09:50:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `conversation`
--

CREATE TABLE `conversation` (
  `id` int(11) NOT NULL,
  `user_one` int(11) NOT NULL,
  `user_two` int(11) NOT NULL,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `conversation`
--

INSERT INTO `conversation` (`id`, `user_one`, `user_two`, `last_activity`) VALUES
(66, 30, 19, '2024-04-11 08:00:03'),
(67, 9, 19, '2024-04-14 08:13:37'),
(68, 19, 32, '2024-05-11 06:48:44'),
(69, 19, 34, '2024-05-11 06:49:03');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `event_by` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `event_image` varchar(255) DEFAULT NULL,
  `event_endtime` time DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `accepted` tinyint(1) DEFAULT 0,
  `status` varchar(10) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_by`, `title`, `date_created`, `event_date`, `event_time`, `event_image`, `event_endtime`, `location`, `accepted`, `status`) VALUES
(75, 19, 'Working Together for Success: Building Strong Business Partnerships', '2024-04-10 16:34:45', '2024-05-27', '08:00:00', 'event-cover.png', '21:00:00', 'Manila Marriott Hotel  Add: Fourth District, 2 Resorts Drive Pasay City, Manila, 1309 Metro Manila, Philippines', 1, 'Accepted'),
(76, 19, 'Teamwork in Innovation: A Workshop for Entrepreneurs', '2024-04-10 16:35:52', '2024-05-29', '08:30:00', 'event-cover.png', '19:30:00', 'NUSTAR Resort and Casino, Cebu City - Add: Kawit Island, South Road Properties, Cebu City, 6000 Cebu, Philippines. ', 1, 'Accepted'),
(90, 19, 'Cooking up Success: Cultivating Strong Partnerships in the Restaurant Industry', '2024-05-11 06:48:02', '2024-06-13', '16:47:00', 'event-cover.png', '08:47:00', '45 Coral Street, Makati City, Metro Manila, Philippines', 0, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `event_info`
--

CREATE TABLE `event_info` (
  `event_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `headline` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_info`
--

INSERT INTO `event_info` (`event_id`, `title`, `headline`, `description`) VALUES
(75, 'Working Together for Success: Building Strong Business Partnerships', 'Join us for a half-day of workshops and networking focused on the power of collaboration in business.', 'Join us for an afternoon of workshops and networking focused on the power of collaboration in business. This event is designed for professionals who are interested in building strong business partnerships and want to learn practical strategies for collaboration. Don\'t miss this opportunity to connect with like-minded professionals and gain the tools you need to succeed.'),
(76, 'Teamwork in Innovation: A Workshop for Entrepreneurs', 'Join us for a full-day of workshops and networking focused on collaborative innovation in entrepreneurship.', 'Join us for a full-day of workshops and networking focused on collaborative innovation in entrepreneurship. This event is designed for entrepreneurs who are interested in exploring new ways of collaborating to drive innovation and growth. Don\'t miss this opportunity to connect with like-minded professionals and gain the tools you need to succeed.'),
(90, 'Cooking up Success: Cultivating Strong Partnerships in the Restaurant Industry', 'Join us for an afternoon of culinary insights and networking tailored for small restaurant owners.', 'Savor an afternoon of culinary inspiration and networking designed exclusively for small restaurant owners. Discover the art of cultivating robust partnerships in the dynamic restaurant industry through practical workshops and engaging discussions. Don\'t miss this chance to mingle with fellow restaurateurs, exchange insights, and equip yourself with the essential ingredients for success.');

-- --------------------------------------------------------

--
-- Stand-in structure for view `event_view`
-- (See below for the actual view)
--
CREATE TABLE `event_view` (
`event_id` int(11)
,`event_by` varchar(50)
,`title` varchar(255)
,`headline` varchar(255)
,`description` text
,`date_created` timestamp
,`event_date` date
,`status` varchar(10)
);

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `favorite_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `poll_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`favorite_id`, `user_id`, `post_id`, `poll_id`) VALUES
(23, 9, 17, NULL),
(32, 30, 17, NULL),
(57, 41, 60, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `user_from` int(11) NOT NULL,
  `user_to` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `message_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0,
  `file_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `conversation_id`, `user_from`, `user_to`, `message`, `message_timestamp`, `is_read`, `file_name`) VALUES
(274, 67, 19, 9, 'hi', '2024-05-05 02:59:12', 1, NULL),
(275, 67, 19, 9, 'interested in knowing my secret to become successful?', '2024-05-05 02:59:34', 1, NULL),
(276, 67, 9, 19, 'yes, I am.', '2024-05-05 02:59:59', 0, NULL),
(277, 67, 9, 19, 'is it for free?', '2024-05-05 03:00:07', 0, NULL),
(278, 69, 19, 34, 'hi', '2024-05-11 06:49:06', 1, NULL),
(279, 69, 34, 19, '', '2024-05-11 06:56:58', 0, NULL),
(280, 69, 34, 19, NULL, '2024-05-11 06:56:58', 0, 'qwqw.png');

-- --------------------------------------------------------

--
-- Table structure for table `polls`
--

CREATE TABLE `polls` (
  `id` int(11) NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `status` enum('1','0') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `poll_desc` varchar(5000) NOT NULL,
  `locked` int(1) NOT NULL DEFAULT 0,
  `accepted` tinyint(1) DEFAULT 0,
  `statusss` varchar(255) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `polls`
--

INSERT INTO `polls` (`id`, `subject`, `created`, `modified`, `status`, `created_by`, `poll_desc`, `locked`, `accepted`, `statusss`) VALUES
(12, 'Marketing Channels Preference', '2024-05-05 11:13:24', '2024-05-05 11:13:24', '1', 19, 'Which marketing channel has generated the highest ROI for your business?\r\n', 0, 1, 'Accepted'),
(13, 'Employee Strategies', '2024-05-05 11:14:10', '2024-05-05 11:14:10', '1', 19, 'Which employee engagement strategy has been most effective in your organization?\r\n', 1, 1, 'Accepted'),
(14, 'Financial Planning', '2024-05-05 11:14:41', '2024-05-05 11:14:41', '1', 19, 'What is your top financial planning priority for the upcoming year?\r\n', 0, 0, 'Pending'),
(16, 'Financial Planning2', '2024-05-11 14:45:55', '2024-05-11 14:45:55', '1', 34, 'What is your top financial planning priority for the upcoming year', 1, 1, 'Accepted');

-- --------------------------------------------------------

--
-- Table structure for table `poll_options`
--

CREATE TABLE `poll_options` (
  `id` int(11) NOT NULL,
  `poll_id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `status` enum('1','0') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `poll_options`
--

INSERT INTO `poll_options` (`id`, `poll_id`, `name`, `created`, `modified`, `status`) VALUES
(97, 12, '1. Social Media Marketing', '2024-05-05 11:13:24', '2024-05-05 11:13:24', '1'),
(98, 12, '2. Email Marketing', '2024-05-05 11:13:24', '2024-05-05 11:13:24', '1'),
(99, 12, '3. Content Marketing', '2024-05-05 11:13:24', '2024-05-05 11:13:24', '1'),
(100, 12, '4. Search Engine Optimization (SEO)', '2024-05-05 11:13:24', '2024-05-05 11:13:24', '1'),
(101, 13, '1. Flexible work arrangements (remote work, flexible hours)', '2024-05-05 11:14:10', '2024-05-05 11:14:10', '1'),
(102, 13, '2. Regular feedback and recognition programs', '2024-05-05 11:14:10', '2024-05-05 11:14:10', '1'),
(103, 13, '3. Opportunities for professional development and growth', '2024-05-05 11:14:10', '2024-05-05 11:14:10', '1'),
(104, 13, '4. Employee wellness programs and initiatives', '2024-05-05 11:14:10', '2024-05-05 11:14:10', '1'),
(105, 14, '1. Increasing Revenue', '2024-05-05 11:14:41', '2024-05-05 11:14:41', '1'),
(106, 14, '2. Cost Reduction Strategies', '2024-05-05 11:14:41', '2024-05-05 11:14:41', '1'),
(107, 14, '3. Investment in Technology', '2024-05-05 11:14:41', '2024-05-05 11:14:41', '1'),
(108, 14, '4. Debt Management', '2024-05-05 11:14:41', '2024-05-05 11:14:41', '1'),
(109, 15, 'asdasd', '2024-05-10 21:32:39', '2024-05-10 21:32:39', '1'),
(110, 15, 'asdasd', '2024-05-10 21:32:39', '2024-05-10 21:32:39', '1'),
(111, 16, '1. Increasing Revenue', '2024-05-11 14:45:55', '2024-05-11 14:45:55', '1'),
(112, 16, '2. Cost Reduction Strategies', '2024-05-11 14:45:55', '2024-05-11 14:45:55', '1'),
(113, 16, '3. Investment in Technology', '2024-05-11 14:45:55', '2024-05-11 14:45:55', '1'),
(114, 16, '4. Debt Management', '2024-05-11 14:45:55', '2024-05-11 14:45:55', '1');

-- --------------------------------------------------------

--
-- Stand-in structure for view `poll_view`
-- (See below for the actual view)
--
CREATE TABLE `poll_view` (
`poll_id` int(11)
,`subject` varchar(255)
,`poll_desc` varchar(5000)
,`poll_created` datetime
,`created_by` varchar(50)
,`statusss` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `poll_votes`
--

CREATE TABLE `poll_votes` (
  `id` int(11) NOT NULL,
  `poll_id` int(11) NOT NULL,
  `poll_option_id` int(11) NOT NULL,
  `vote_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `poll_votes`
--

INSERT INTO `poll_votes` (`id`, `poll_id`, `poll_option_id`, `vote_by`) VALUES
(19, 12, 97, 34),
(20, 16, 112, 19),
(21, 12, 100, 19);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(8) NOT NULL,
  `post_content` text NOT NULL,
  `post_date` datetime NOT NULL,
  `post_topic` int(8) NOT NULL,
  `post_by` int(8) NOT NULL,
  `post_votes` int(11) NOT NULL DEFAULT 0,
  `accepted` tinyint(1) DEFAULT 0,
  `status` varchar(10) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `post_content`, `post_date`, `post_topic`, `post_by`, `post_votes`, `accepted`, `status`) VALUES
(10, 'How is technology reshaping the retail landscape?', '2024-03-15 19:05:26', 73, 19, 1, 0, 'pending'),
(16, 'How is transportation evolving in the age of smart mobility?', '2024-03-27 19:43:51', 78, 19, 0, 0, 'pending'),
(17, 'How is technology revolutionizing healthcare delivery?', '2024-03-27 19:44:16', 79, 19, 0, 1, 'pending'),
(56, 'What emerging technologies are disrupting industries today?', '2024-05-11 14:42:43', 96, 34, 1, 1, 'Accepted'),
(60, 'What emerging technologies are disrupting industries today?', '2024-05-24 17:49:29', 100, 41, 0, 1, 'pending');

-- --------------------------------------------------------

--
-- Stand-in structure for view `posts_with_ids_and_username`
-- (See below for the actual view)
--
CREATE TABLE `posts_with_ids_and_username` (
`post_id` int(8)
,`post_by` varchar(50)
,`post_content` text
,`post_date` datetime
,`topic_id` int(8)
,`topic_subject` varchar(255)
,`cat_name` varchar(255)
,`status` varchar(8)
);

-- --------------------------------------------------------

--
-- Table structure for table `postvotes`
--

CREATE TABLE `postvotes` (
  `voteId` int(11) NOT NULL,
  `votePost` int(11) NOT NULL,
  `voteBy` int(11) NOT NULL,
  `voteDate` date NOT NULL,
  `vote` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `postvotes`
--

INSERT INTO `postvotes` (`voteId`, `votePost`, `voteBy`, `voteDate`, `vote`) VALUES
(20, 17, 19, '2024-05-10', 1),
(21, 17, 9, '2024-05-05', -1),
(22, 10, 9, '2024-05-05', 1),
(23, 56, 34, '2024-05-11', 1);

--
-- Triggers `postvotes`
--
DELIMITER $$
CREATE TRIGGER `calc_forum_votes_after_delete` AFTER DELETE ON `postvotes` FOR EACH ROW BEGIN

		update posts
        set posts.post_votes = posts.post_votes - old.vote
        where posts.post_id = old.votePost;	

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `calc_forum_votes_after_insert` AFTER INSERT ON `postvotes` FOR EACH ROW BEGIN
	
	update posts
        set posts.post_votes = posts.post_votes + new.vote
        where posts.post_id = new.votePost;	
		
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `calc_forum_votes_after_update` AFTER UPDATE ON `postvotes` FOR EACH ROW BEGIN
	
		update posts
        set posts.post_votes = posts.post_votes + (new.vote * 2)
        where posts.post_id = new.votePost;	
		
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `reminder_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reminders`
--

INSERT INTO `reminders` (`reminder_id`, `user_id`, `event_id`, `created_at`) VALUES
(60, 9, 75, '2024-05-05 03:35:05'),
(61, 9, 76, '2024-05-05 03:35:10');

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE `topics` (
  `topic_id` int(8) NOT NULL,
  `topic_subject` varchar(255) NOT NULL,
  `topic_description` varchar(255) DEFAULT NULL,
  `topic_date` datetime NOT NULL,
  `topic_cat` int(8) NOT NULL,
  `topic_by` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`topic_id`, `topic_subject`, `topic_description`, `topic_date`, `topic_cat`, `topic_by`) VALUES
(73, 'Retail Revolution Hub', 'Dive into the transformative power of technology in the retail sector. Explore how innovations are reshaping consumer experiences, supply chains, and marketing strategies.', '2024-03-15 19:05:26', 9, 19),
(78, 'Transport Trends', 'Discover the transformative impact of smart mobility on transportation trends, revolutionizing how people and goods move in urban environments. Explore the latest innovations driving this evolution and shaping the future of transportation.', '2024-03-27 19:43:51', 9, 19),
(79, 'HealthTech Insights', 'Discover the latest breakthroughs in technology that are reshaping the way healthcare services are delivered, improving patient outcomes and accessibility.\r\n\r\n\r\n\r\n\r\n\r\n', '2024-03-27 19:44:16', 12, 19),
(96, ' Talk Central', 'Let\'s Dive Into The Latest Disruptive Technologies Revolutionizing Industries Today.', '2024-05-11 14:42:43', 2, 34),
(100, 'Talk Central', 'Let\'s Dive Into The Latest Disruptive Technologies Revolutionizing Industries Today.', '2024-05-24 17:49:29', 5, 41);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `suffix` varchar(255) DEFAULT NULL,
  `birthdate` date NOT NULL,
  `age` int(25) NOT NULL,
  `contactnumber` varchar(20) NOT NULL,
  `business_name` varchar(255) NOT NULL,
  `business_type` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `status` enum('online','offline') DEFAULT 'offline',
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `verification_code` int(6) NOT NULL,
  `userImg` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `middlename`, `lastname`, `suffix`, `birthdate`, `age`, `contactnumber`, `business_name`, `business_type`, `address`, `status`, `username`, `email`, `password_hash`, `verification_code`, `userImg`, `reset_token`) VALUES
(9, 'Jb', 'S', 'Locsin', NULL, '2003-02-10', 21, '63678768123', 'Business On the GO!', 'Finance', 'Dyan sa Tabi', 'offline', 'user123', 'genoagraviador23@gmail.com', '$2y$10$dBraDRD4i5gS4vbwqXTWz.w9y2Lm.lt7gV3yI59zaFhm5n6bA.z6S', 990126, '12.jpg', '$2y$10$27CM.p9ljcTTJS0brHaI5el1UyXPCQQxZTrTqNe4kROYNxcA3zwWC'),
(12, 'Joyce', NULL, 'Calvez', NULL, '2003-02-10', 21, '6312378123', 'Business On the GO!', 'Business On the GO!', 'Dyan sa tabi', 'offline', 'Joyce123', 'jbreylocsin1@gmail.com', '$2y$10$2bcGL2SOLTuFAyVpmlXVv.i6kER8F0LDVSjnUgNrXgehViDlCkNFe', 380323, 'default.jpg', '$2y$10$bjA6cvrEPsD732SkkGa.2usoWwGjuaG.rpANXfeTzul9vlt82M1By'),
(19, 'dave', NULL, 'bernal', NULL, '2001-09-11', 22, '639358706908', 'Business On the GO!', 'Business On the GO!', 'Dyan sa tabi', 'online', 'dave3', 'cstd09@gmail.com', '$2y$10$YeBOPBFVMZQ21iBdgVy/TuoRaHtgU7vScmRVw/RFQeJUqL4FqDztq', 991397, 'pexels-kaique-rocha-321500.jpg', NULL),
(28, 'JB Rey', NULL, 'Locsin', NULL, '2003-06-20', 20, '63678768123', 'Business On the GO!', 'Business On the GO!', 'Dyan sa tabi', 'offline', 'jbrey123123', 'jbreylocsin1@gmail.com', '$2y$10$mchaR/jEbyB/JuVJgy9haubjSaftHzIgKRXg0qRbSuc0MmOl.brsu', 573677, NULL, NULL),
(30, 'JB', 'S', 'Locsin', NULL, '2003-02-10', 21, '636787681232', 'Business On the GO!', 'FInance', 'Camarin', 'offline', 'jb12345', 'jbreylocsin1@gmail.com', '$2y$10$k2bXXKasWnIG1kgsrDRqoOH8rQSP5z8PlwqyykKKwIIe2Kaa8dM7O', 818306, NULL, NULL),
(31, 'JB', 'S', 'Locsin', NULL, '2003-02-10', 21, '636787681232', 'Business On the GO!', 'Business On the GO!', 'Dyan sa tabi', 'offline', 'qweqeewq', 'jbreylocsin1@gmail.com', '$2y$10$px1bRsK6KVpaPNmcMr57PO7tUg8GXYjbRvrAPeNwWTrzatpNdyQ/G', 136684, NULL, NULL),
(32, 'Mary Jane', 'S', 'Lopez', NULL, '2000-11-16', 23, '63678768123', 'Business On the GO!', 'Business On the GO!', 'Dyan sa tabi', 'offline', 'mary123', 'jbreylocsin1@gmail.com', '$2y$10$ogAtiGkIiZvmF2wjK1pTkOIPObYJ5FnKlM4q2c1wxgkMeYEeTsMXy', 652831, 'group-solid-24.png', NULL),
(34, 'melvin', NULL, 'custodio', NULL, '2001-09-11', 22, '639358706908', 'sari sari store', 'grocery', 'caloocan city', 'offline', 'Melvin', 'jbreylocsin1@gmail.com', '$2y$10$PRYQr2YIKD7RthyrGbYsE.bhF/PhZfNzcjT3M5CFXvb5WeyLKklgq', 969356, NULL, NULL),
(40, 'melvin', NULL, 'custodio', NULL, '2001-09-11', 22, '639358706908', 'diwata pares', 'Restaurant', 'caloocan city', 'offline', 'Melvincustodio@2', 'custodio.melvinbsis2021@gmail.com', '$2y$10$/5I4g/IGVVkak.Ngv4q.o.6zClMzC9qiV1v2MrzHnRVtAIhpujgq2', 649808, NULL, NULL),
(41, 'jayson', 'S', 'joble', NULL, '2003-02-10', 21, '636787681232', 'Ecommerse', 'Real Estate', 'maligaya saglit', 'online', 'jayson1231', 'businessresource3b@gmail.com', '$2y$10$TVSmY3VQBLyx/8AEEqTOJeLsM2VFxps/.pn9C1Yv6pq2aF0Tk04qu', 566589, NULL, NULL);

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `delete_empty_users_trigger_insert` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
    IF NEW.firstname = '' OR 
       NEW.middlename = '' OR 
       NEW.lastname = '' OR
       NEW.suffix = '' OR
       NEW.birthdate = '' OR 
       NEW.age = '' OR 
       NEW.contactnumber = '' OR
       NEW.address = '' OR 
       NEW.username = '' OR 
       NEW.email = '' OR 
       NEW.password_hash = '' THEN
       
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Cannot insert row with empty values in specified columns';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure for view `event_view`
--
DROP TABLE IF EXISTS `event_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`` SQL SECURITY DEFINER VIEW `event_view`  AS SELECT `e`.`event_id` AS `event_id`, `u`.`username` AS `event_by`, `e`.`title` AS `title`, `ei`.`headline` AS `headline`, `ei`.`description` AS `description`, `e`.`date_created` AS `date_created`, `e`.`event_date` AS `event_date`, `e`.`status` AS `status` FROM ((`events` `e` join `users` `u` on(`e`.`event_by` = `u`.`id`)) join `event_info` `ei` on(`e`.`event_id` = `ei`.`event_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `poll_view`
--
DROP TABLE IF EXISTS `poll_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`` SQL SECURITY DEFINER VIEW `poll_view`  AS SELECT `p`.`id` AS `poll_id`, `p`.`subject` AS `subject`, `p`.`poll_desc` AS `poll_desc`, `p`.`created` AS `poll_created`, `u`.`username` AS `created_by`, `p`.`statusss` AS `statusss` FROM (`polls` `p` join `users` `u` on(`p`.`created_by` = `u`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `posts_with_ids_and_username`
--
DROP TABLE IF EXISTS `posts_with_ids_and_username`;

CREATE ALGORITHM=UNDEFINED DEFINER=`` SQL SECURITY DEFINER VIEW `posts_with_ids_and_username`  AS SELECT `p`.`post_id` AS `post_id`, `u`.`username` AS `post_by`, `p`.`post_content` AS `post_content`, `p`.`post_date` AS `post_date`, `t`.`topic_id` AS `topic_id`, `t`.`topic_subject` AS `topic_subject`, `c`.`cat_name` AS `cat_name`, CASE WHEN `p`.`accepted` = 1 THEN 'Accepted' ELSE 'Pending' END AS `status` FROM (((`posts` `p` join `users` `u` on(`p`.`post_by` = `u`.`id`)) join `topics` `t` on(`p`.`post_topic` = `t`.`topic_id`)) join `categories` `c` on(`t`.`topic_cat` = `c`.`cat_id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`),
  ADD UNIQUE KEY `cat_name_unique` (`cat_name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `conversation`
--
ALTER TABLE `conversation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_one` (`user_one`),
  ADD KEY `user_two` (`user_two`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `event_info`
--
ALTER TABLE `event_info`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`favorite_id`),
  ADD UNIQUE KEY `unique_favorite` (`user_id`,`post_id`,`poll_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `poll_id` (`poll_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `polls`
--
ALTER TABLE `polls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `poll_options`
--
ALTER TABLE `poll_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `poll_votes`
--
ALTER TABLE `poll_votes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `postvotes`
--
ALTER TABLE `postvotes`
  ADD PRIMARY KEY (`voteId`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`reminder_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`topic_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `conversation`
--
ALTER TABLE `conversation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `event_info`
--
ALTER TABLE `event_info`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=281;

--
-- AUTO_INCREMENT for table `polls`
--
ALTER TABLE `polls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `poll_options`
--
ALTER TABLE `poll_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `poll_votes`
--
ALTER TABLE `poll_votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `postvotes`
--
ALTER TABLE `postvotes`
  MODIFY `voteId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `reminders`
--
ALTER TABLE `reminders`
  MODIFY `reminder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `topics`
--
ALTER TABLE `topics`
  MODIFY `topic_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`),
  ADD CONSTRAINT `favorites_ibfk_3` FOREIGN KEY (`poll_id`) REFERENCES `polls` (`id`);

--
-- Constraints for table `reminders`
--
ALTER TABLE `reminders`
  ADD CONSTRAINT `reminders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reminders_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
