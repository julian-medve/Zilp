/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : zilpapi

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2021-02-09 15:26:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for zilp_activities
-- ----------------------------
DROP TABLE IF EXISTS `zilp_activities`;
CREATE TABLE `zilp_activities` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for zilp_chat_sessions
-- ----------------------------
DROP TABLE IF EXISTS `zilp_chat_sessions`;
CREATE TABLE `zilp_chat_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node_one_user_id` int(11) NOT NULL,
  `node_two_user_id` int(11) NOT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for zilp_comments
-- ----------------------------
DROP TABLE IF EXISTS `zilp_comments`;
CREATE TABLE `zilp_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_content` text NOT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for zilp_driver_documentations
-- ----------------------------
DROP TABLE IF EXISTS `zilp_driver_documentations`;
CREATE TABLE `zilp_driver_documentations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `status` enum('unchecked','rejected','accepted') NOT NULL,
  `driver_license` text NOT NULL,
  `vehicle_registration` text NOT NULL,
  `insurance_card` text NOT NULL,
  `vehicle_picture` text NOT NULL,
  `driver_headshot` text NOT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for zilp_driver_services
-- ----------------------------
DROP TABLE IF EXISTS `zilp_driver_services`;
CREATE TABLE `zilp_driver_services` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `state` enum('busy','free') DEFAULT 'free',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for zilp_friends
-- ----------------------------
DROP TABLE IF EXISTS `zilp_friends`;
CREATE TABLE `zilp_friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node_one_id` int(11) NOT NULL,
  `node_two_id` int(11) NOT NULL,
  `accepted` tinyint(1) NOT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for zilp_likes
-- ----------------------------
DROP TABLE IF EXISTS `zilp_likes`;
CREATE TABLE `zilp_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for zilp_messages
-- ----------------------------
DROP TABLE IF EXISTS `zilp_messages`;
CREATE TABLE `zilp_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chat_session_id` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `message_type` enum('TEXT','IMAGE','VIDEO','VOICE','FILE','LOCATION') NOT NULL,
  `message_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `read_at` datetime DEFAULT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=205 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for zilp_migrations
-- ----------------------------
DROP TABLE IF EXISTS `zilp_migrations`;
CREATE TABLE `zilp_migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for zilp_notifications
-- ----------------------------
DROP TABLE IF EXISTS `zilp_notifications`;
CREATE TABLE `zilp_notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender_id` bigint(20) DEFAULT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `hidden` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `zilp_notifications_notifiable_type_notifiable_id_index` (`notifiable_type`(191),`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for zilp_oauth_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `zilp_oauth_access_tokens`;
CREATE TABLE `zilp_oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `zilp_oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for zilp_oauth_auth_codes
-- ----------------------------
DROP TABLE IF EXISTS `zilp_oauth_auth_codes`;
CREATE TABLE `zilp_oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `zilp_oauth_auth_codes_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for zilp_oauth_clients
-- ----------------------------
DROP TABLE IF EXISTS `zilp_oauth_clients`;
CREATE TABLE `zilp_oauth_clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `zilp_oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for zilp_oauth_personal_access_clients
-- ----------------------------
DROP TABLE IF EXISTS `zilp_oauth_personal_access_clients`;
CREATE TABLE `zilp_oauth_personal_access_clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for zilp_oauth_refresh_tokens
-- ----------------------------
DROP TABLE IF EXISTS `zilp_oauth_refresh_tokens`;
CREATE TABLE `zilp_oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for zilp_parking_services
-- ----------------------------
DROP TABLE IF EXISTS `zilp_parking_services`;
CREATE TABLE `zilp_parking_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `parking_photo` text NOT NULL,
  `description` text NOT NULL,
  `hourly_rate` int(11) NOT NULL,
  `daily_rate` int(11) NOT NULL,
  `location` text NOT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for zilp_posts
-- ----------------------------
DROP TABLE IF EXISTS `zilp_posts`;
CREATE TABLE `zilp_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `post_content` text NOT NULL,
  `post_meta_type` enum('NONE','IMAGE','VIDEO','LOCATION') NOT NULL,
  `post_meta_content` text,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for zilp_registered_plates
-- ----------------------------
DROP TABLE IF EXISTS `zilp_registered_plates`;
CREATE TABLE `zilp_registered_plates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `plate_number` varchar(40) NOT NULL,
  `is_owner` tinyint(1) NOT NULL,
  `access_code` int(11) DEFAULT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for zilp_subscriptions
-- ----------------------------
DROP TABLE IF EXISTS `zilp_subscriptions`;
CREATE TABLE `zilp_subscriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_plan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `zilp_subscriptions_user_id_stripe_status_index` (`user_id`,`stripe_status`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for zilp_subscription_items
-- ----------------------------
DROP TABLE IF EXISTS `zilp_subscription_items`;
CREATE TABLE `zilp_subscription_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `subscription_id` bigint(20) unsigned NOT NULL,
  `stripe_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_plan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `zilp_subscription_items_stripe_id_index` (`stripe_id`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for zilp_tasks
-- ----------------------------
DROP TABLE IF EXISTS `zilp_tasks`;
CREATE TABLE `zilp_tasks` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `activity_id` bigint(20) DEFAULT NULL,
  `creator_id` bigint(20) DEFAULT NULL,
  `assigned_for` bigint(20) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for zilp_transactions
-- ----------------------------
DROP TABLE IF EXISTS `zilp_transactions`;
CREATE TABLE `zilp_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_uid` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` bigint(20) NOT NULL,
  `description` text NOT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for zilp_transportation_booking_deals
-- ----------------------------
DROP TABLE IF EXISTS `zilp_transportation_booking_deals`;
CREATE TABLE `zilp_transportation_booking_deals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `available_monday` tinyint(1) NOT NULL,
  `available_tuesday` tinyint(1) NOT NULL,
  `available_wednesday` tinyint(1) NOT NULL,
  `available_thursday` tinyint(1) NOT NULL,
  `available_friday` tinyint(1) NOT NULL,
  `available_saturday` tinyint(1) NOT NULL,
  `available_sunday` tinyint(1) NOT NULL,
  `CREATED_AT` datetime(1) NOT NULL,
  `UPDATED_AT` datetime(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for zilp_transportation_renting_dates
-- ----------------------------
DROP TABLE IF EXISTS `zilp_transportation_renting_dates`;
CREATE TABLE `zilp_transportation_renting_dates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rent_id` int(11) NOT NULL,
  `available_date` date NOT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for zilp_transportation_renting_deals
-- ----------------------------
DROP TABLE IF EXISTS `zilp_transportation_renting_deals`;
CREATE TABLE `zilp_transportation_renting_deals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `vehicle_photo` text NOT NULL,
  `make` varchar(500) NOT NULL,
  `model` varchar(500) NOT NULL,
  `year` varchar(10) NOT NULL,
  `color` varchar(500) NOT NULL,
  `condition_description` text NOT NULL,
  `accessories` text NOT NULL,
  `description` text NOT NULL,
  `rental_price` int(11) NOT NULL,
  `UPDATED_AT` datetime NOT NULL,
  `CREATED_AT` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for zilp_transportation_selling_deals
-- ----------------------------
DROP TABLE IF EXISTS `zilp_transportation_selling_deals`;
CREATE TABLE `zilp_transportation_selling_deals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `vehicle_photo` text NOT NULL,
  `make` varchar(500) NOT NULL,
  `model` varchar(500) NOT NULL,
  `year` varchar(10) NOT NULL,
  `color` varchar(500) NOT NULL,
  `accessories` text NOT NULL,
  `description` text NOT NULL,
  `price` int(11) NOT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for zilp_users
-- ----------------------------
DROP TABLE IF EXISTS `zilp_users`;
CREATE TABLE `zilp_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plate_number` varchar(40) NOT NULL,
  `email` text NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `password` text NOT NULL,
  `phone` text NOT NULL,
  `profile_pic` text NOT NULL,
  `profile_visibility` enum('PUBLIC','FRIENDS_ONLY') NOT NULL,
  `guest_plate` varchar(40) DEFAULT NULL,
  `balance` bigint(20) NOT NULL,
  `verified` tinyint(1) NOT NULL,
  `verified_driver` enum('no','pending','yes') NOT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL,
  `stripe_id` varchar(255) DEFAULT NULL,
  `card_brand` varchar(255) DEFAULT NULL,
  `card_last_four` varchar(4) DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `plate_number` (`plate_number`),
  KEY `zilp_users_stripe_id_index` (`stripe_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
