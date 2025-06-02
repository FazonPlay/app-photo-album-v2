-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 26, 2025 at 02:20 AM
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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `albums`
--

INSERT INTO `albums` (`album_id`, `user_id`, `title`, `description`, `creation_date`, `updated_at`, `visibility`, `cover_photo_id`) VALUES
(1, 2, 'Summer Vacation 2024', 'Photos from our family trip to Hawaii', '2025-05-12 02:31:36', '2025-05-26 02:31:36', 'private', 1),
(2, 2, 'My Pets', 'Photos of my cats and dogs', '2025-04-26 02:31:36', '2025-05-26 02:31:36', 'restricted', 4),
(3, 3, 'Nature Photography', 'Beautiful landscapes and wildlife', '2025-05-19 02:31:36', '2025-05-26 02:31:36', 'public', 6),
(4, 3, 'Urban Explorations', 'City architecture and street life', '2025-03-27 02:31:36', '2025-05-26 02:31:36', 'private', 10),
(5, 4, 'Paris Trip', 'Exploring the city of lights', '2025-02-25 02:31:36', '2025-05-26 02:31:36', 'restricted', 11),
(6, 4, 'Food Photography', 'Delicious meals from around the world', '2025-05-21 02:31:36', '2025-05-26 02:31:36', 'public', 13);

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
(6, 3, 'contribute', '2025-05-22 02:31:36', 4);

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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `album_invitations`
--

INSERT INTO `album_invitations` (`invitation_id`, `album_id`, `sender_id`, `recipient_email`, `token`, `permission_level`, `message`, `created_at`, `expires_at`, `is_accepted`) VALUES
(1, 1, 2, 'jane@example.com', 'invite_token_1', 'view', 'Check out my vacation photos!', '2025-05-16 02:31:36', '2025-06-15 02:31:36', NULL),
(2, 1, 2, 'mike@example.com', 'invite_token_2', 'comment', 'Hey Mike, thought you might like these Hawaii pics.', '2025-05-16 02:31:36', '2025-06-15 02:31:36', NULL),
(3, 4, 3, 'john@example.com', 'invite_token_3', 'contribute', 'John, you can add your city photos too!', '2025-04-16 02:31:36', '2025-05-16 02:31:36', 'expired'),
(4, 6, 4, 'john@example.com', 'invite_token_4', 'comment', 'Would love your feedback on these recipes!', '2025-05-23 02:31:36', '2025-06-22 02:31:36', NULL);

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
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `photos`
--

INSERT INTO `photos` (`photo_id`, `album_id`, `user_id`, `file_path`, `thumbnail_path`, `title`, `description`, `upload_date`, `capture_date`, `is_favorite`) VALUES
(1, 1, 2, 'uploads/photos/hawaii_beach.jpg', 'uploads/thumbnails/hawaii_beach_thumb.jpg', 'Waikiki Beach', 'Sunset at Waikiki Beach', '2025-05-12 02:31:36', NULL, 1),
(2, 1, 2, 'uploads/photos/hawaii_hike.jpg', 'uploads/thumbnails/hawaii_hike_thumb.jpg', 'Diamond Head Hike', 'View from the top of Diamond Head', '2025-05-13 02:31:36', NULL, 0),
(3, 1, 2, 'uploads/photos/hawaii_waterfall.jpg', 'uploads/thumbnails/hawaii_waterfall_thumb.jpg', 'Hidden Waterfall', 'Found this waterfall during our hike', '2025-05-14 02:31:36', NULL, 1),
(4, 2, 2, 'uploads/photos/cat_playing.jpg', 'uploads/thumbnails/cat_playing_thumb.jpg', 'Mr. Whiskers', 'My cat playing with his favorite toy', '2025-04-27 02:31:36', NULL, 1),
(5, 2, 2, 'uploads/photos/dog_beach.jpg', 'uploads/thumbnails/dog_beach_thumb.jpg', 'Max at the Beach', 'My dog enjoying the ocean', '2025-04-28 02:31:36', NULL, 0),
(6, 3, 3, 'uploads/photos/mountain_sunrise.jpg', 'uploads/thumbnails/mountain_sunrise_thumb.jpg', 'Mountain Sunrise', 'Sunrise over the mountains', '2025-05-19 02:31:36', NULL, 1),
(7, 3, 3, 'uploads/photos/forest_path.jpg', 'uploads/thumbnails/forest_path_thumb.jpg', 'Forest Path', 'Walking through the enchanted forest', '2025-05-20 02:31:36', NULL, 0),
(8, 3, 3, 'uploads/photos/lake_reflection.jpg', 'uploads/thumbnails/lake_reflection_thumb.jpg', 'Lake Reflection', 'Perfect reflection in the still lake', '2025-05-21 02:31:36', NULL, 1),
(9, 4, 3, 'uploads/photos/skyscraper.jpg', 'uploads/thumbnails/skyscraper_thumb.jpg', 'Modern Architecture', 'Looking up at city skyscrapers', '2025-03-28 02:31:36', NULL, 0),
(10, 4, 3, 'uploads/photos/street_art.jpg', 'uploads/thumbnails/street_art_thumb.jpg', 'Street Art', 'Amazing graffiti found in the city', '2025-03-29 02:31:36', NULL, 1),
(11, 5, 4, 'uploads/photos/eiffel_tower.jpg', 'uploads/thumbnails/eiffel_tower_thumb.jpg', 'Eiffel Tower', 'Iconic view of the Eiffel Tower', '2025-02-26 02:31:36', NULL, 1),
(12, 5, 4, 'uploads/photos/louvre.jpg', 'uploads/thumbnails/louvre_thumb.jpg', 'The Louvre', 'The famous glass pyramid', '2025-02-27 02:31:36', NULL, 0),
(13, 6, 4, 'uploads/photos/pasta.jpg', 'uploads/thumbnails/pasta_thumb.jpg', 'Homemade Pasta', 'Fresh pasta with tomato sauce', '2025-05-21 02:31:36', NULL, 1),
(14, 6, 4, 'uploads/photos/dessert.jpg', 'uploads/thumbnails/dessert_thumb.jpg', 'Chocolate Dessert', 'Decadent chocolate cake', '2025-05-22 02:31:36', NULL, 1);

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
(14, 5, 4, '2025-05-22 02:31:36');

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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(8, 'travel', '2025-05-26 02:31:36');

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
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `registration_date`, `last_login`, `is_active`, `roles`) VALUES
(1, 'admin', 'a@a.com', '$2y$10$u40HOF1Ug1SaxYhZUUHa7e4N2/gD1naemVeKK68YNLZ5gei8qkvQ2', '2025-05-12 18:31:53', '2025-05-26 02:02:43', 1, 'admin'),
(2, 'user1', 'u@u.com', '$2y$10$bLWApyD2FGf51rGSA9JKv.lnrFWb0c0lC37sWyRW037w8nZCaBwNy', '2025-05-15 13:11:00', '2025-05-15 13:11:27', 1, 'admin'),
(3, 'urmommaybe', 'm@m.com', '$2y$10$Oe/MOHaG0yFv/w6NpSHV2eF3/2wyiYUeui6nq9LvLm9c89ulGDUYO', '2025-05-15 13:17:53', NULL, 1, 'user'),
(4, 'test1', 'test@test.com', '$2y$10$RLQqktci1iZt33f7.Yzen.w7mRZL3JV4zzl/67qiosw5zAfEbCdb.', '2025-05-15 13:18:29', NULL, 1, 'user'),
(5, 'testuser', 't@t.com', '$2y$10$DMcpcoUBF4eQhYqZI2sNPO4ru/b6Oi10f9Zlb/IcWl4Lwn06BiuDm', '2025-05-15 14:01:35', NULL, 1, 'user'),
(6, 'testinguser1', 'umm@u.com', '$2y$10$QQaihTOI9Q28tsp.Qq6N8OZoW02u73qO/V3ulMSTKgfZTlj7KiY.q', '2025-05-15 14:03:34', NULL, 1, 'user'),
(7, 'ilikepoop', 'ilikepoop@poop.com', '$2y$10$T9wklpIZ.LoTesenvnLxZuaF8Ql42O/NtG43udVZSyWLOwL2vk1rO', '2025-05-15 14:16:37', '2025-05-15 14:16:48', 1, 'user'),
(8, 'ilikenoodles', 'ilikenoodles@u.com', '$2y$10$rzvz1kGyfmBmE6Z68qUsj.HvDpytP1n14LeK98xiftyWJFEi.pVwS', '2025-05-15 14:23:06', NULL, 1, 'user'),
(9, 'sigh', 'sigh@s.com', '$2y$10$u4qD5Y6wBkvY1hD2kAbANeARXKhsSk16xqH2J73XGM83vgbkIQrIW', '2025-05-15 14:24:19', NULL, 1, 'user'),
(10, 'bangkok', 'bangkok@b.com', '$2y$10$C8CEVskGOVf7ppy18ZAM0uU8pHGUbFOwOfe5NCMajayghebJSe0by', '2025-05-15 14:24:46', NULL, 1, 'user'),
(11, 'testingagain', 'testing@testing.testing', '$2y$10$CgMFHwYAChslVNVrRNMcZeqBwUBDmbuIwaJ/ttYfDtggSt4fIUBsG', '2025-05-15 14:37:40', NULL, 1, 'user'),
(12, 'damnit', 'damnit@d.com', '$2y$10$uvae99yodWpHfmj/CbYoveLxmRsTU7ivuEOwvwVoL2M8VDWoST/6K', '2025-05-15 14:44:38', '2025-05-15 14:56:12', 1, 'user'),
(13, 'whatever', 'whatever@gmail.com', '$2y$10$CK29yYH6tHXRAVl9hY.RrOhTk9YMUW42TocvY/Wufh.K4xfFMH6pa', '2025-05-18 19:53:11', NULL, 1, 'user');

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
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(10, 13, NULL, NULL, NULL, NULL, '2025-05-18 19:53:11', '2025-05-18 19:53:11');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
