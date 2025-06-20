-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 20, 2025 at 09:45 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `photo-album`
--

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

DROP TABLE IF EXISTS `albums`;
CREATE TABLE IF NOT EXISTS `albums` (
  `album_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text,
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `visibility` enum('private','public','restricted') NOT NULL DEFAULT 'private',
  `cover_photo_id` int DEFAULT NULL,
  PRIMARY KEY (`album_id`),
  KEY `user_id` (`user_id`),
  KEY `fk_cover_photo` (`cover_photo_id`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `albums`
--

INSERT INTO `albums` (`album_id`, `user_id`, `title`, `description`, `creation_date`, `updated_at`, `visibility`, `cover_photo_id`) VALUES
(1, 2, 'Summer Vacation 2024', 'Photos from our family trip to Hawaii', '2025-05-12 02:31:36', '2025-06-11 16:32:27', 'private', 24),
(2, 2, 'My Pets', 'Photos of my cats and dogs', '2025-04-26 02:31:36', '2025-06-16 18:23:07', 'private', 49),
(3, 3, 'Nature Photography', 'Beautiful landscapes and wildlife', '2025-05-19 02:31:36', '2025-05-26 02:31:36', 'public', 6),
(4, 3, 'Urban Explorations', 'City architecture and street life', '2025-03-27 02:31:36', '2025-05-26 02:31:36', 'private', 10),
(10, 0, 'well hello, testing again', 'uhh does the cover photo worjk???', '2025-06-08 21:46:03', '2025-06-08 21:46:03', 'private', NULL),
(6, 4, 'Food Photography', 'Delicious meals from around the world', '2025-05-21 02:31:36', '2025-05-26 02:31:36', 'public', 13),
(7, 1, 'Admin Showcase', 'Featured photos from the site administrator', '2025-05-26 04:22:35', '2025-05-26 04:22:35', 'public', 15),
(11, 0, 'testcoverpic', 'um', '2025-06-08 21:59:47', '2025-06-08 21:59:47', 'private', 1),
(12, 0, 'TESTAGAON', 'ugh', '2025-06-08 22:03:38', '2025-06-08 22:03:38', 'private', 1),
(13, 0, 'titlephotorestasdfasdsafsdfs', 'asdfasdf', '2025-06-08 22:04:37', '2025-06-08 22:04:37', 'private', 27),
(16, 0, 'onelasttime', 'efasd', '2025-06-08 22:11:41', '2025-06-08 22:11:41', 'private', 1),
(17, 0, 'uploadrest', 'asd', '2025-06-08 22:15:20', '2025-06-13 10:09:08', 'private', 42),
(18, 0, 'bruh', 'bruh', '2025-06-08 22:15:52', '2025-06-11 16:32:18', 'private', 38),
(19, 0, 'well hello again lil cro', 'vano 14 yr old pics', '2025-06-08 22:19:08', '2025-06-08 22:19:08', 'private', 28),
(22, 0, 'testing album>', 'umm hi', '2025-06-11 16:11:48', '2025-06-11 16:20:39', 'private', 38),
(30, 0, 'USER album', 'hi bro', '2025-06-15 22:30:04', '2025-06-15 22:30:04', 'restricted', 47),
(23, 0, 'testPublicVisibility', 'hiii', '2025-06-11 16:13:14', '2025-06-11 16:19:44', 'private', 38),
(27, 0, 'bonhoiur', 'oui', '2025-06-13 10:12:44', '2025-06-14 00:24:26', 'private', 42),
(29, 0, 'testusercreateshare', 'umhi', '2025-06-15 22:07:01', '2025-06-15 22:07:28', 'private', 47),
(26, 0, 'logicFavTest', 'kys', '2025-06-11 16:46:17', '2025-06-12 16:07:27', 'restricted', 38),
(28, 0, 'Sizon\'s Plumber Force', 'a bunch of fellas.', '2025-06-13 10:27:39', '2025-06-13 10:27:39', 'private', 46),
(31, 1, 'ADMIN album', 'uhh', '2025-06-15 22:31:17', '2025-06-15 22:31:17', 'private', 45),
(32, 0, 'admin album 1', 'asdfasdfafsd', '2025-06-15 22:40:52', '2025-06-15 22:41:02', 'private', 48),
(33, 1, 'testupdatealbum', 'wallahi', '2025-06-15 22:41:33', '2025-06-20 11:32:19', 'private', 62),
(34, 2, 'newuseralbum', 'sdfoa', '2025-06-16 16:53:25', '2025-06-16 16:53:25', 'private', 47),
(43, 23, 'usertest', 'ausdnaosd', '2025-06-20 14:32:44', '2025-06-20 18:52:14', 'private', 62),
(44, 23, 'testcssnewgrid', 'asd', '2025-06-20 16:04:23', '2025-06-20 16:06:08', 'private', 63),
(37, 1, 'album for user (update log test)', 'hihiaw', '2025-06-19 09:49:43', '2025-06-20 11:35:08', 'restricted', 62),
(41, 23, 'awdf', 'awf', '2025-06-20 13:48:00', '2025-06-20 22:30:40', 'private', 65),
(42, 1, 'admincreatetest', 'fuck', '2025-06-20 14:32:05', '2025-06-20 14:32:05', 'private', 54),
(50, 1, 'stillworks??', 'agf;iuabfiawh;df', '2025-06-20 18:54:55', '2025-06-20 18:54:55', 'private', 21),
(53, 1, 'sdfsadf', 'sadfasdfasdf', '2025-06-20 21:49:25', '2025-06-20 21:49:25', 'private', 61),
(56, 1, 'testuserright', 'asdfasdf', '2025-06-20 22:05:51', '2025-06-20 22:21:48', 'public', 66),
(57, 1, 'sedparffi[ugfbfgu[eadfsisgb', 'asedfd', '2025-06-20 23:07:21', '2025-06-20 23:07:21', 'public', 26),
(58, 1, 'testlogupdate', 'umhi', '2025-06-20 23:27:25', '2025-06-20 23:27:44', 'public', 25);

-- --------------------------------------------------------

--
-- Table structure for table `album_access`
--

DROP TABLE IF EXISTS `album_access`;
CREATE TABLE IF NOT EXISTS `album_access` (
  `album_id` int NOT NULL,
  `user_id` int NOT NULL,
  `permission_level` enum('view','comment','contribute') NOT NULL DEFAULT 'view',
  `granted_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `granted_by` int NOT NULL,
  PRIMARY KEY (`album_id`,`user_id`),
  KEY `user_id` (`user_id`),
  KEY `granted_by` (`granted_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `album_access`
--

INSERT INTO `album_access` (`album_id`, `user_id`, `permission_level`, `granted_at`, `granted_by`) VALUES
(2, 3, 'view', '2025-05-01 02:31:36', 2),
(3, 2, 'comment', '2025-05-20 02:31:36', 3),
(5, 2, 'view', '2025-03-02 02:31:36', 4),
(5, 3, 'comment', '2025-03-02 02:31:36', 4),
(6, 3, 'contribute', '2025-05-22 02:31:36', 4),
(35, 2, 'view', '2025-06-16 18:16:48', 1),
(2, 2, 'view', '2025-06-16 18:17:31', 1),
(2, 23, 'view', '2025-06-16 18:21:27', 1),
(3, 23, 'contribute', '2025-06-16 18:21:58', 1),
(28, 23, 'view', '2025-06-19 09:32:13', 1),
(29, 23, 'view', '2025-06-19 09:32:22', 1),
(27, 23, 'view', '2025-06-19 09:45:58', 1),
(4, 23, 'contribute', '2025-06-19 09:46:08', 1),
(22, 23, 'view', '2025-06-19 09:49:08', 1),
(37, 23, 'comment', '2025-06-19 09:55:25', 1),
(38, 23, 'view', '2025-06-19 10:09:07', 1),
(44, 23, 'view', '2025-06-20 16:23:42', 23),
(55, 23, 'view', '2025-06-20 21:56:43', 1);

-- --------------------------------------------------------

--
-- Table structure for table `album_invitations`
--

DROP TABLE IF EXISTS `album_invitations`;
CREATE TABLE IF NOT EXISTS `album_invitations` (
  `invitation_id` int NOT NULL AUTO_INCREMENT,
  `album_id` int NOT NULL,
  `sender_id` int NOT NULL,
  `recipient_email` varchar(100) NOT NULL,
  `token` varchar(100) NOT NULL,
  `permission_level` enum('view','comment','contribute') NOT NULL DEFAULT 'view',
  `message` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` datetime NOT NULL,
  `is_accepted` enum('accepted','refused','expired') DEFAULT NULL,
  PRIMARY KEY (`invitation_id`),
  UNIQUE KEY `token` (`token`),
  KEY `album_id` (`album_id`),
  KEY `sender_id` (`sender_id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `album_invitations`
--

INSERT INTO `album_invitations` (`invitation_id`, `album_id`, `sender_id`, `recipient_email`, `token`, `permission_level`, `message`, `created_at`, `expires_at`, `is_accepted`) VALUES
(1, 1, 2, 'jane@example.com', 'invite_token_1', 'view', 'Check out my vacation photos!', '2025-05-16 02:31:36', '2025-06-15 02:31:36', NULL),
(2, 1, 2, 'mike@example.com', 'invite_token_2', 'comment', 'Hey Mike, thought you might like these Hawaii pics.', '2025-05-16 02:31:36', '2025-06-15 02:31:36', NULL),
(3, 4, 3, 'john@example.com', 'invite_token_3', 'contribute', 'John, you can add your city photos too!', '2025-04-16 02:31:36', '2025-05-16 02:31:36', 'expired'),
(4, 6, 4, 'john@example.com', 'invite_token_4', 'comment', 'Would love your feedback on these recipes!', '2025-05-23 02:31:36', '2025-06-22 02:31:36', NULL),
(5, 4, 1, 'u@u.com', '92e92b1bbf5f8d37ed278b7a23bce474', 'contribute', 'hi', '2025-06-13 21:00:41', '2025-06-20 19:00:41', 'accepted'),
(6, 28, 1, 'asdasd@gmail.com', '01529a29a34c584ea892b851997b4b38', 'view', 'asd', '2025-06-13 23:44:47', '2025-06-20 21:44:47', NULL),
(7, 27, 1, 'u@u.com', '1c4e46015204afe96019ed80aa34edbc', 'view', 'yo bro, end it all', '2025-06-14 00:24:19', '2025-06-20 22:24:19', 'accepted'),
(8, 29, 1, 'u@u.com', 'b860192c1ca8a55a1cd7c06a1f0adb15', 'view', 'asdasdasd', '2025-06-15 22:16:29', '2025-06-22 20:16:29', 'accepted'),
(9, 28, 1, 'u@u.com', '6afe3ef174959723da9c93798ccbe1d8', 'view', 'userjhik', '2025-06-15 22:24:04', '2025-06-22 20:24:04', 'accepted'),
(10, 0, 1, 'a@a.com', 'f9dc8e23e79f9750a347ac9a13204ac3', 'view', 'hi admin', '2025-06-15 22:36:10', '2025-06-22 20:36:10', NULL),
(11, 0, 1, 'u@u.com', 'd5238f86b35616fdf167e1d9b8da3f84', 'view', 'hi bro', '2025-06-15 22:36:53', '2025-06-22 20:36:53', NULL),
(12, 0, 1, 'u@u.com', '569e01dfe6a3dd52f196a7f09e93b1ab', 'view', 'whynotwork', '2025-06-15 22:53:02', '2025-06-22 20:53:02', NULL),
(13, 0, 1, 'u@u.com', '85b9eaba26b1425924f37e91b3702b94', 'view', 'whynotwork', '2025-06-15 22:53:07', '2025-06-22 20:53:07', NULL),
(14, 0, 1, 'u@u.com', '51bbf63b3eca3d5583aed5d0e4799815', 'view', 'whynowaedasd', '2025-06-15 22:53:20', '2025-06-22 20:53:20', NULL),
(15, 33, 1, 'u@u.com', '3bd8403a1a6556e97002b8e74304f058', 'view', 'userasd=ouhnasd', '2025-06-15 22:54:45', '2025-06-22 20:54:45', 'refused'),
(16, 34, 2, 'a@a.com', '2e7cce5a8ef22b6acaeac4697437edfc', 'view', 'invite admin with user account', '2025-06-16 16:53:47', '2025-06-23 14:53:47', NULL),
(17, 22, 1, 'u@u.com', '0af594209f513d8b34ad64baa5a7495b', 'view', 'hi bro', '2025-06-19 09:48:50', '2025-06-26 07:48:50', 'accepted'),
(18, 37, 1, 'u@u.com', 'fabed08d94f3109fa0b102311f7af2b9', 'view', 'wtf', '2025-06-19 09:54:33', '2025-06-26 07:54:33', 'accepted'),
(19, 38, 1, 'u@u.com', '3e6a5134d99e9ac1709cef15f45775bd', 'view', 'hi', '2025-06-19 10:08:58', '2025-06-26 08:08:58', 'accepted'),
(20, 38, 1, 'u@u.com', 'dbfaaf1ca0cc48fdd44d53c5474b4142', 'view', 'hi', '2025-06-19 10:09:11', '2025-06-26 08:09:11', 'accepted'),
(21, 38, 1, 'u@u.com', '970298272be4bda3130da629ef7a6414', 'view', 'bonjour user', '2025-06-19 13:05:43', '2025-06-26 11:05:43', 'accepted'),
(22, 37, 1, 'u@u.com', 'f44594b3e1ed7b9c6cd68519bc9a1442', 'comment', 'paeiuffoajd', '2025-06-19 14:45:12', '2025-06-26 12:45:12', 'accepted'),
(23, 44, 23, 'u@u.com', '532ce759689ccbd398130843eb1e4fa9', 'view', 'heoifhd', '2025-06-20 16:23:18', '2025-06-27 14:23:18', 'accepted'),
(24, 51, 1, 'u@u.com', 'f82ba269241cc25a09795b9e9d8458ce', 'view', 'ads', '2025-06-20 21:48:11', '2025-06-27 19:48:11', 'refused'),
(25, 55, 1, 'u@u.com', 'c2fc2f56bab3b3a1dfbdb1c8a4612ab4', 'view', 'asdasd', '2025-06-20 21:56:27', '2025-06-27 19:56:27', 'accepted'),
(26, 57, 1, 'u@u.com', '8379086150dfe1b66e9f5ff7ec0b0c3a', 'comment', 'asd', '2025-06-20 23:13:26', '2025-06-27 21:13:26', NULL),
(27, 58, 1, 'u@u.com', 'c3c8b9010b8d2671241f63bc47cd0e45', 'comment', 'asd', '2025-06-20 23:30:39', '2025-06-27 21:30:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int NOT NULL AUTO_INCREMENT,
  `photo_id` int NOT NULL,
  `user_id` int NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`comment_id`),
  KEY `photo_id` (`photo_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `photo_id`, `user_id`, `content`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'Gorgeous sunset! Where exactly on Waikiki was this taken?', '2025-05-14 02:31:36', '2025-05-26 02:31:36'),
(2, 1, 2, 'Thanks! This was near the Royal Hawaiian Hotel.', '2025-05-14 02:31:36', '2025-05-26 02:31:36'),
(3, 6, 2, 'Breathtaking! What time did you have to wake up for this shot?', '2025-05-21 02:31:36', '2025-05-26 02:31:36'),
(4, 6, 3, 'I was up at 4:30 AM to hike to this spot!', '2025-05-21 02:31:36', '2025-05-26 02:31:36'),
(5, 11, 3, 'Classic shot! Was it crowded when you visited?', '2025-03-07 02:31:36', '2025-05-26 02:31:36'),
(6, 11, 4, 'Not too bad, I went very early in the morning.', '2025-03-07 02:31:36', '2025-05-26 02:31:36'),
(7, 13, 3, 'That pasta looks amazing! Is that a homemade sauce?', '2025-05-23 02:31:36', '2025-05-26 02:31:36'),
(8, 13, 4, 'Yes! Made with tomatoes from my garden.', '2025-05-23 02:31:36', '2025-05-26 02:31:36');

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

DROP TABLE IF EXISTS `photos`;
CREATE TABLE IF NOT EXISTS `photos` (
  `photo_id` int NOT NULL AUTO_INCREMENT,
  `album_id` int NOT NULL,
  `user_id` int NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text,
  `upload_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `capture_date` date DEFAULT NULL,
  `is_favorite` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`photo_id`),
  KEY `album_id` (`album_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `photos`
--

INSERT INTO `photos` (`photo_id`, `album_id`, `user_id`, `file_path`, `thumbnail_path`, `title`, `description`, `upload_date`, `capture_date`, `is_favorite`) VALUES
(1, 1, 2, 'uploads/photos/hawaii_beach.jpg', 'uploads/thumbnails/hawaii_beach_thumb.jpg', 'Waikiki Beach', 'Sunset at Waikiki Beach', '2025-05-12 02:31:36', NULL, 0),
(2, 1, 2, 'uploads/photos/hawaii_hike.jpg', 'uploads/thumbnails/hawaii_hike_thumb.jpg', 'Diamond Head Hike', 'View from the top of Diamond Head', '2025-05-13 02:31:36', NULL, 0),
(3, 1, 2, 'uploads/photos/hawaii_waterfall.jpg', 'uploads/thumbnails/hawaii_waterfall_thumb.jpg', 'uhhh', 'Found this waterfall during our hike', '2025-05-14 02:31:36', NULL, 0),
(4, 8, 2, 'uploads/photos/cat_playing.jpg', 'uploads/thumbnails/cat_playing_thumb.jpg', 'Mr. Whiskers', 'My cat playing with his favorite toy', '2025-04-27 02:31:36', NULL, 0),
(5, 2, 2, 'uploads/photos/dog_beach.jpg', 'uploads/thumbnails/dog_beach_thumb.jpg', 'Max at the Beach', 'My dog enjoying the ocean', '2025-04-28 02:31:36', NULL, 0),
(6, 3, 3, 'uploads/photos/mountain_sunrise.jpg', 'uploads/thumbnails/mountain_sunrise_thumb.jpg', 'Mountain Sunrise', 'Sunrise over the mountains', '2025-05-19 02:31:36', NULL, 0),
(7, 3, 3, 'uploads/photos/forest_path.jpg', 'uploads/thumbnails/forest_path_thumb.jpg', 'Forest Path', 'Walking through the enchanted forest', '2025-05-20 02:31:36', NULL, 0),
(54, 0, 1, 'uploads/photos/68551c3ce2149_carrot_ready.png', NULL, 'testlogaddpghoto', NULL, '2025-06-20 10:30:52', NULL, 0),
(55, 0, 1, 'uploads/photos/68551c44e374d_carrot_ready.png', NULL, 'testlogaddpghoto', NULL, '2025-06-20 10:31:00', NULL, 0),
(9, 10, 3, 'uploads/photos/skyscraper.jpg', 'uploads/thumbnails/skyscraper_thumb.jpg', 'Modern Architecture', 'Looking up at city skyscrapers', '2025-03-28 02:31:36', NULL, 0),
(10, 10, 3, 'uploads/photos/street_art.jpg', 'uploads/thumbnails/street_art_thumb.jpg', 'Street Art', 'Amazing graffiti found in the city', '2025-03-29 02:31:36', NULL, 0),
(25, 58, 1, 'uploads/photos/683d9c84da6f3_WIN_20250313_15_06_13_Pro.jpg', NULL, 'gay', NULL, '2025-06-02 14:43:48', NULL, 0),
(26, 57, 1, 'uploads/photos/683db8cd32270_WIN_20250317_13_59_21_Pro.jpg', NULL, 'adminwork?', NULL, '2025-06-02 16:44:29', NULL, 0),
(24, 0, 2, 'uploads/photos/683d65f5adbe1_WIN_20250317_13_59_23_Pro.jpg', NULL, 'boinjour', NULL, '2025-06-02 10:51:01', NULL, 0),
(13, 6, 4, 'uploads/photos/pasta.jpg', 'uploads/thumbnails/pasta_thumb.jpg', 'Homemade Pasta', 'Fresh pasta with tomato sauce', '2025-05-21 02:31:36', NULL, 0),
(21, 50, 1, 'uploads/photos/683783c17cabf_1000017373-removebg-preview.png', NULL, 'adminadding test', NULL, '2025-05-28 23:44:33', NULL, 0),
(53, 0, 1, 'uploads/photos/68551c2f13a63_carrot_ready.png', NULL, 'testlogaddpghoto', NULL, '2025-06-20 10:30:39', NULL, 0),
(17, 7, 1, 'uploads/photos/admin_cityscape.jpg', 'uploads/thumbnails/admin_cityscape_thumb.jpg', 'City Lights', 'Downtown at night', '2025-05-26 04:22:35', NULL, 0),
(38, 27, 2, 'uploads/photos/68498c362eb87_WIN_20250313_15_06_37_Pro.jpg', NULL, 'addphotoworк??', '', '2025-06-11 16:01:26', NULL, 0),
(28, 19, 1, 'uploads/photos/683dbdde8d338_WIN_20250313_15_06_13_Pro.jpg', NULL, 'adminwork?', NULL, '2025-06-02 17:06:06', NULL, 0),
(37, 0, 2, 'uploads/photos/684618c4a9998_WIN_20250317_13_40_26_Pro.jpg', NULL, 'useraddpictest?', NULL, '2025-06-09 01:12:04', NULL, 0),
(30, 18, 1, 'uploads/photos/68432686315fe_WIN_20250317_13_40_29_Pro.jpg', NULL, 'testingfavphoto', NULL, '2025-06-06 19:33:58', NULL, 0),
(31, 15, 1, 'uploads/photos/6845ebb329062_WIN_20250317_13_41_18_Pro.jpg', NULL, 'Cover Photo', NULL, '2025-06-08 21:59:47', NULL, 0),
(32, 26, 1, 'uploads/photos/6845ec9a987ce_WIN_20250317_13_59_24_Pro (2).jpg', NULL, 'Cover Photo', NULL, '2025-06-08 22:03:38', NULL, 0),
(39, 27, 1, 'uploads/photos/6849926572f44_Screenshot 2025-06-11 162417.png', NULL, 'блять', NULL, '2025-06-11 16:27:49', NULL, 1),
(42, 27, 1, 'uploads/photos/684bd94eb21c1_Screenshot 2025-06-11 162359.png', NULL, 'testwithoutphoto', NULL, '2025-06-13 09:54:54', NULL, 1),
(44, 28, 1, 'uploads/photos/684be0b948da2_Screenshot 2025-05-30 025916.png', NULL, 'bitler', NULL, '2025-06-13 10:26:33', NULL, 1),
(45, 0, 1, 'uploads/photos/684be0ca6fdaf_Screenshot 2025-05-30 025944.png', NULL, 'yougay', NULL, '2025-06-13 10:26:50', NULL, 0),
(43, 28, 1, 'uploads/photos/684bdd5362507_Screenshot 2025-05-26 052627.png', NULL, 'bonjour', NULL, '2025-06-13 10:12:03', NULL, 0),
(46, 28, 1, 'uploads/photos/684be0e03811c_Screenshot 2025-05-30 014017.png', NULL, 'uhm actuchually', NULL, '2025-06-13 10:27:12', NULL, 1),
(47, 0, 2, 'uploads/photos/684f27f898880_Screenshot 2025-04-19 034639.png', NULL, 'testuseraddshare', NULL, '2025-06-15 22:07:20', NULL, 0),
(48, 0, 1, 'uploads/photos/684f2fb8430f0_Screenshot 2025-05-26 050927.png', NULL, 'admin photo 1', NULL, '2025-06-15 22:40:24', NULL, 0),
(52, 0, 1, 'uploads/photos/68551c2883978_carrot_ready.png', NULL, 'testlogaddpghoto', NULL, '2025-06-20 10:30:32', NULL, 0),
(57, 41, 1, 'uploads/photos/68551c6e35ea5_Screenshot 2025-06-14 010054.png', NULL, 'whynotworkj', NULL, '2025-06-20 10:31:42', NULL, 0),
(56, 41, 1, 'uploads/photos/68551c5359f97_carrot_ready.png', NULL, 'testlogaddpghotoasdas', NULL, '2025-06-20 10:31:15', NULL, 0),
(58, 49, 1, 'uploads/photos/68551c7f711a5_Screenshot 2025-06-19 100537.png', NULL, 'adasd', NULL, '2025-06-20 10:31:59', NULL, 0),
(59, 49, 1, 'uploads/photos/68551ca15280d_Screenshot 2025-06-14 010706.png', NULL, 'assf', NULL, '2025-06-20 10:32:33', NULL, 0),
(60, 41, 1, 'uploads/photos/68551ef08ab55_Screenshot 2025-06-14 010054.png', NULL, 'teryagaibn', NULL, '2025-06-20 10:42:24', NULL, 0),
(62, 55, 23, 'uploads/photos/68552974d878e_Screenshot 2025-06-19 100537.png', NULL, 'bodybuilder plays minecraft', NULL, '2025-06-20 11:27:16', NULL, 0),
(63, 49, 23, 'uploads/photos/6855550f3ec78_Screenshot 2025-06-20 130128.png', NULL, 'can i add?', 'HI', '2025-06-20 14:33:19', NULL, 1),
(69, 0, 1, 'uploads/photos/6855d53ed3eb2_Screenshot 2025-06-20 205430.png', NULL, 'umm', NULL, '2025-06-20 23:40:14', NULL, 0),
(67, 0, 1, 'uploads/photos/6855d5287b4ae_Screenshot 2025-06-20 224144.png', NULL, 'testlogadagin', NULL, '2025-06-20 23:39:52', NULL, 0),
(66, 56, 1, 'uploads/photos/6855b80a19d00_Screenshot 2025-06-20 112840.png', NULL, 'asdasd', NULL, '2025-06-20 21:35:38', NULL, 0),
(68, 0, 1, 'uploads/photos/6855d52ad03fd_Screenshot 2025-06-20 224144.png', NULL, 'testlogadagin', NULL, '2025-06-20 23:39:54', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `photo_tags`
--

DROP TABLE IF EXISTS `photo_tags`;
CREATE TABLE IF NOT EXISTS `photo_tags` (
  `photo_id` int NOT NULL,
  `tag_id` int NOT NULL,
  `added_by` int NOT NULL,
  `added_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`photo_id`,`tag_id`),
  KEY `tag_id` (`tag_id`),
  KEY `added_by` (`added_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `photo_tags`
--

INSERT INTO `photo_tags` (`photo_id`, `tag_id`, `added_by`, `added_at`) VALUES
(1, 1, 2, '2025-05-12 02:31:36'),
(1, 7, 2, '2025-05-12 02:31:36'),
(2, 1, 2, '2025-05-13 02:31:36'),
(2, 2, 2, '2025-05-13 02:31:36'),
(3, 1, 2, '2025-05-14 02:31:36'),
(3, 2, 2, '2025-05-14 02:31:36'),
(4, 3, 2, '2025-04-27 02:31:36'),
(5, 3, 2, '2025-04-28 02:31:36'),
(6, 2, 3, '2025-05-19 02:31:36'),
(6, 7, 3, '2025-05-19 02:31:36'),
(7, 2, 3, '2025-05-20 02:31:36'),
(8, 2, 3, '2025-05-21 02:31:36'),
(9, 4, 3, '2025-03-28 02:31:36'),
(9, 6, 3, '2025-03-28 02:31:36'),
(10, 4, 3, '2025-03-29 02:31:36'),
(11, 6, 4, '2025-02-26 02:31:36'),
(11, 8, 4, '2025-02-26 02:31:36'),
(12, 6, 4, '2025-02-27 02:31:36'),
(12, 8, 4, '2025-02-27 02:31:36'),
(13, 5, 4, '2025-05-21 02:31:36'),
(14, 5, 4, '2025-05-22 02:31:36'),
(15, 9, 1, '2025-05-26 04:22:35'),
(16, 9, 1, '2025-05-26 04:22:35'),
(17, 9, 1, '2025-05-26 04:22:35'),
(15, 10, 1, '2025-05-26 04:22:35'),
(16, 10, 1, '2025-05-26 04:22:35');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
  `tag_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`tag_id`, `name`, `created_at`) VALUES
(1, 'vacation', '2025-05-26 02:31:36'),
(2, 'nature', '2025-05-26 02:31:36'),
(3, 'animals', '2025-05-26 02:31:36'),
(4, 'urban', '2025-05-26 02:31:36'),
(5, 'food', '2025-05-26 02:31:36'),
(6, 'architecture', '2025-05-26 02:31:36'),
(7, 'sunset', '2025-05-26 02:31:36'),
(8, 'travel', '2025-05-26 02:31:36'),
(9, 'admin', '2025-05-26 04:22:35'),
(10, 'featured', '2025-05-26 04:22:35'),
(11, 'teoo', '2025-06-19 14:19:17'),
(12, 'boo', '2025-06-19 14:26:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `registration_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `roles` enum('admin','user','premium','lifetime') NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `registration_date`, `last_login`, `is_active`, `roles`) VALUES
(1, 'admin', 'a@a.com', '$2y$10$6BthYT.NTIGXR1oJ0mCFUeQrB/7AZWeK4itamdhs/kjlz5kjZ7vDy', '2025-05-12 18:31:53', '2025-05-27 17:43:46', 1, 'admin'),
(23, 'user', 'u@u.com', '$2y$10$8yx.qDEclR.ZRrlUQlvcne/5dCYpuPjIG0wN8KzRbsKLLf8WG8sZG', '2025-06-16 18:20:10', NULL, 1, 'user'),
(29, 'hellobrohow', 'testmail@t.com', '$2y$10$K9dSLXYhNHHSru5DOGzfdOzyslXG6oxKja9noV/UbHEIEJLnmVkze', '2025-06-20 23:06:02', NULL, 1, 'premium'),
(25, 'ummmm', 'hi@hi.com', '$2y$10$HrQfk5eNo5TtDrUcx5C5f.TREF8dgXbbuVkfOnzHY76wyVsCHdA1i', '2025-06-20 09:01:35', NULL, 1, 'premium'),
(19, 'wawawii', 'somethingworthurmom@gmail.com', '$2y$10$HX5GxHEKMzJOlZjMunZL0eS3xXZwWxSHJn5In1Pcxyo4hZ0TP4P7u', '2025-06-05 18:10:29', NULL, 1, 'admin'),
(28, 'umhello', 'tryagain@gmail.com', '$2y$10$XBsOwBdVWoJBstCXfSmrG.JN.aBhCMC6..CTqR/g5p2yS.vk4tmIu', '2025-06-20 14:39:05', NULL, 1, 'user'),
(20, 'hi', 'hi@hi.hi', '$2y$10$ZQ9/gyyckjkxrQ7rKGj9gerTU0BpDNe2B1fEHXfVv2clfWy.ZhWtS', '2025-06-06 17:40:00', NULL, 1, 'user'),
(7, 'ilikepoop', 'ilikepoop@poop.com', '$2y$10$T9wklpIZ.LoTesenvnLxZuaF8Ql42O/NtG43udVZSyWLOwL2vk1rO', '2025-05-15 14:16:37', '2025-05-15 14:16:48', 1, 'user'),
(26, 'hisas', 'hi@i.com', '$2y$10$hxNGxM1mX/XCZGEZkZAfaeZdlbBPUwMhdqW2ruRnEu.uusGfu5odO', '2025-06-20 09:01:42', NULL, 1, 'user'),
(27, 'testingcreateuser', 'ummmmm@u.com', '$2y$10$/ivSadFfSQtXj7.aR.3c1OdeLrFufftCk2PIIBTvpR6gGD.B5rlNu', '2025-06-20 10:00:49', NULL, 1, 'user'),
(30, 'hellobrohowd', 'testmail@t.comd', '$2y$10$q8Y3/uPJF/XDKqWbsQooMeRdCxkWF0RrNgpx777D417OsKIqND6ue', '2025-06-20 23:06:41', NULL, 1, 'premium'),
(31, 'testlogbri', 'i@i.i', '$2y$10$DYzJtVvdOeefVMlRTNG8.exZQ3Gusc4gJuXUMisQY1VTwG4vL5IiS', '2025-06-20 23:34:12', NULL, 1, 'user'),
(14, 'ilikegaysizon', 'gayman@gayman.coke', '$2y$10$rdj9dz2EClEfHoKUDAySPezebuEF1rNDXJTLuFs7OiI9h4v9cZy72', '2025-05-26 05:03:34', NULL, 1, 'user'),
(15, 'testuser1234', 'test@testing.cum', '$2y$10$KDy/BzAWYeL.cXv9QXFTCOWy6pA6Yl/s.0OVmEfe0FsV.KSN7LgKK', '2025-05-27 17:21:51', NULL, 1, 'user'),
(21, 'new', 'new@new.new', '$2y$10$8b1hHSri/Gfst5jSWMufDOlflGi2AcN6oKo.7dRtAksBg5jUVMWhq', '2025-06-13 10:09:33', NULL, 1, 'user'),
(17, 'sizonXalbert', 'sizonlikes@gmail.com', '$2y$10$81WiGs8u9EEO6xg6IMbzx.W0FbUsCd.e1K5LUyNJlvRaFeX7WPCS2', '2025-06-02 16:16:13', NULL, 1, 'admin'),
(32, 'testlog', 'log@l.com', '$2y$10$4h9YP1yLfX8by6ZNyn4/6.4k7lYceRKzsOIZOnEDfkZLuvTvVDAVG', '2025-06-20 23:43:26', NULL, 1, 'lifetime');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

DROP TABLE IF EXISTS `user_profiles`;
CREATE TABLE IF NOT EXISTS `user_profiles` (
  `profile_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `bio` text,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`profile_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`profile_id`, `user_id`, `first_name`, `last_name`, `bio`, `profile_picture`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, NULL, NULL, NULL, '2025-05-15 13:11:00', '2025-05-15 13:11:00'),
(2, 3, NULL, NULL, NULL, NULL, '2025-05-15 13:17:53', '2025-05-15 13:17:53'),
(3, 4, NULL, NULL, NULL, NULL, '2025-05-15 13:18:29', '2025-05-15 13:18:29'),
(4, 7, NULL, NULL, NULL, NULL, '2025-05-15 14:16:37', '2025-05-15 14:16:37'),
(5, 8, NULL, NULL, NULL, NULL, '2025-05-15 14:23:06', '2025-05-15 14:23:06'),
(6, 9, NULL, NULL, NULL, NULL, '2025-05-15 14:24:19', '2025-05-15 14:24:19'),
(7, 10, NULL, NULL, NULL, NULL, '2025-05-15 14:24:46', '2025-05-15 14:24:46'),
(8, 11, NULL, NULL, NULL, NULL, '2025-05-15 14:37:40', '2025-05-15 14:37:40'),
(9, 12, NULL, NULL, NULL, NULL, '2025-05-15 14:44:38', '2025-05-15 14:44:38'),
(10, 13, NULL, NULL, NULL, NULL, '2025-05-18 19:53:11', '2025-05-18 19:53:11'),
(11, 1, 'uh', 'Administrator', 'System administrator and photo pmo', 'uploads/profile_pictures/profile_6855d5da0e916_Screenshot 2025-06-20 224144.png', '2025-05-26 04:22:35', '2025-06-20 23:42:50'),
(12, 23, '', '', '', 'uploads/profile_pictures/profile_6855b87a40ea8_Screenshot 2025-06-19 100537.png', '2025-06-20 21:37:30', '2025-06-20 21:37:30');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
