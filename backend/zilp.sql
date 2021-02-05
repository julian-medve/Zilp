-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2020 at 10:15 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zilp`
--

-- --------------------------------------------------------

--
-- Table structure for table `zilp_chat_sessions`
--

CREATE TABLE `zilp_chat_sessions` (
  `id` int(11) NOT NULL,
  `node_one_user_id` int(11) NOT NULL,
  `node_two_user_id` int(11) NOT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `zilp_comments`
--

CREATE TABLE `zilp_comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_content` text NOT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `zilp_driver_documentations`
--

CREATE TABLE `zilp_driver_documentations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('unchecked','rejected','accepted') NOT NULL,
  `driver_license` text NOT NULL,
  `vehicle_registration` text NOT NULL,
  `insurance_card` text NOT NULL,
  `vehicle_picture` text NOT NULL,
  `driver_headshot` text NOT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `zilp_friends`
--

CREATE TABLE `zilp_friends` (
  `id` int(11) NOT NULL,
  `node_one_id` int(11) NOT NULL,
  `node_two_id` int(11) NOT NULL,
  `accepted` tinyint(1) NOT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `zilp_friends`
--

INSERT INTO `zilp_friends` (`id`, `node_one_id`, `node_two_id`, `accepted`, `CREATED_AT`, `UPDATED_AT`) VALUES
(74, 13, 15, 1, '2020-06-07 19:38:56', '2020-06-07 19:38:56'),
(76, 14, 13, 1, '2020-08-07 21:36:50', '2020-08-07 21:43:37');

-- --------------------------------------------------------

--
-- Table structure for table `zilp_likes`
--

CREATE TABLE `zilp_likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `zilp_likes`
--

INSERT INTO `zilp_likes` (`id`, `user_id`, `post_id`, `CREATED_AT`, `UPDATED_AT`) VALUES
(12, 13, 26, '2020-05-19 16:44:59', '2020-05-19 16:44:59'),
(16, 15, 26, '2020-05-23 18:13:59', '2020-05-23 18:13:59'),
(18, 15, 25, '2020-05-23 19:02:55', '2020-05-23 19:02:55'),
(19, 15, 24, '2020-05-23 19:06:53', '2020-05-23 19:06:53'),
(20, 13, 27, '2020-05-23 19:24:06', '2020-05-23 19:24:06'),
(22, 15, 23, '2020-05-24 22:01:30', '2020-05-24 22:01:30'),
(23, 15, 27, '2020-05-24 22:01:46', '2020-05-24 22:01:46');

-- --------------------------------------------------------

--
-- Table structure for table `zilp_messages`
--

CREATE TABLE `zilp_messages` (
  `id` int(11) NOT NULL,
  `chat_session_id` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `message_type` enum('TEXT','IMAGE','VIDEO','VOICE','FILE','LOCATION') NOT NULL,
  `message_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `read_at` datetime DEFAULT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `zilp_migrations`
--

CREATE TABLE `zilp_migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `zilp_migrations`
--

INSERT INTO `zilp_migrations` (`id`, `migration`, `batch`) VALUES
(1, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(2, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(3, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(4, '2016_06_01_000004_create_oauth_clients_table', 1),
(5, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(6, '2020_05_20_183822_create_notifications_table', 2),
(7, '2019_05_03_000001_create_customer_columns', 3),
(8, '2019_05_03_000002_create_subscriptions_table', 3),
(9, '2019_05_03_000003_create_subscription_items_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `zilp_notifications`
--

CREATE TABLE `zilp_notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `hidden` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `zilp_notifications`
--

INSERT INTO `zilp_notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `hidden`, `created_at`, `updated_at`) VALUES
('55445d0d-2efc-4d8d-a3c7-d1314e52d2ea', 'App\\Notifications\\FriendRequestAccepted', 'App\\User', 14, '{\"message\":\"Adnan Babakan (abc123) has accepted your friend request.\",\"user_id\":13}', '2020-08-04 14:18:14', NULL, '2020-08-04 14:14:55', '2020-08-04 14:18:14'),
('91483a5d-453e-48a1-9e3f-6ba68df2bb56', 'App\\Notifications\\FriendRequest', 'App\\User', 15, '{\"message\":\"Adnan Babakan (abc123) has requested to be your friend.\",\"user_id\":13}', NULL, NULL, '2020-06-07 15:08:56', '2020-06-07 15:08:56'),
('9e31a704-535b-454a-8b2a-45346326dd11', 'App\\Notifications\\FriendRequest', 'App\\User', 13, '{\"message\":\"Tarlan Babakan (DEF123) has requested to be your friend.\",\"user_id\":14}', '2020-08-07 17:07:08', 1, '2020-08-07 17:06:51', '2020-08-07 17:13:37'),
('c933a75e-59f6-489a-bdba-dbc7f9489fc8', 'App\\Notifications\\FriendRequestAccepted', 'App\\User', 14, '{\"message\":\"Adnan Babakan (abc123) has accepted your friend request.\",\"user_id\":13}', NULL, NULL, '2020-08-07 17:13:37', '2020-08-07 17:13:37');

-- --------------------------------------------------------

--
-- Table structure for table `zilp_oauth_access_tokens`
--

CREATE TABLE `zilp_oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zilp_oauth_auth_codes`
--

CREATE TABLE `zilp_oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zilp_oauth_clients`
--

CREATE TABLE `zilp_oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zilp_oauth_personal_access_clients`
--

CREATE TABLE `zilp_oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zilp_oauth_refresh_tokens`
--

CREATE TABLE `zilp_oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zilp_parking_services`
--

CREATE TABLE `zilp_parking_services` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `parking_photo` text NOT NULL,
  `description` text NOT NULL,
  `hourly_rate` int(11) NOT NULL,
  `daily_rate` int(11) NOT NULL,
  `location` text NOT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `zilp_posts`
--

CREATE TABLE `zilp_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_content` text NOT NULL,
  `post_meta_type` enum('NONE','IMAGE','VIDEO','LOCATION') NOT NULL,
  `post_meta_content` text DEFAULT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `zilp_registered_plates`
--

CREATE TABLE `zilp_registered_plates` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `plate_number` varchar(40) NOT NULL,
  `is_owner` tinyint(1) NOT NULL,
  `access_code` int(11) DEFAULT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `zilp_registered_plates`
--

INSERT INTO `zilp_registered_plates` (`id`, `user_id`, `plate_number`, `is_owner`, `access_code`, `CREATED_AT`, `UPDATED_AT`) VALUES
(1, 17, 'ABC123', 1, NULL, '2020-09-29 10:10:29', '2020-09-29 10:10:29'),
(5, 18, 'TEST123', 1, NULL, '2020-10-03 12:15:21', '2020-10-03 12:58:41'),
(8, 17, 'DEF456', 1, NULL, '2020-10-03 12:41:37', '2020-10-03 12:41:37');

-- --------------------------------------------------------

--
-- Table structure for table `zilp_subscriptions`
--

CREATE TABLE `zilp_subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_plan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zilp_subscription_items`
--

CREATE TABLE `zilp_subscription_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subscription_id` bigint(20) UNSIGNED NOT NULL,
  `stripe_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_plan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zilp_transactions`
--

CREATE TABLE `zilp_transactions` (
  `id` int(11) NOT NULL,
  `transaction_uid` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` bigint(20) NOT NULL,
  `description` text NOT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `zilp_transactions`
--

INSERT INTO `zilp_transactions` (`id`, `transaction_uid`, `user_id`, `amount`, `description`, `CREATED_AT`, `UPDATED_AT`) VALUES
(1, '17202010081', 17, 500, 'Stripe balance charge.', '2020-10-08 20:15:21', '2020-10-08 20:15:21');

-- --------------------------------------------------------

--
-- Table structure for table `zilp_transportation_booking_deals`
--

CREATE TABLE `zilp_transportation_booking_deals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `available_monday` tinyint(1) NOT NULL,
  `available_tuesday` tinyint(1) NOT NULL,
  `available_wednesday` tinyint(1) NOT NULL,
  `available_thursday` tinyint(1) NOT NULL,
  `available_friday` tinyint(1) NOT NULL,
  `available_saturday` tinyint(1) NOT NULL,
  `available_sunday` tinyint(1) NOT NULL,
  `CREATED_AT` datetime(1) NOT NULL,
  `UPDATED_AT` datetime(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `zilp_transportation_renting_dates`
--

CREATE TABLE `zilp_transportation_renting_dates` (
  `id` int(11) NOT NULL,
  `rent_id` int(11) NOT NULL,
  `available_date` date NOT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `zilp_transportation_renting_dates`
--

INSERT INTO `zilp_transportation_renting_dates` (`id`, `rent_id`, `available_date`, `CREATED_AT`, `UPDATED_AT`) VALUES
(6, 4, '2020-08-18', '2020-07-31 20:02:47', '2020-07-31 20:02:47'),
(7, 4, '2020-08-19', '2020-07-31 20:02:47', '2020-07-31 20:02:47'),
(8, 4, '2020-08-20', '2020-07-31 20:02:47', '2020-07-31 20:02:47');

-- --------------------------------------------------------

--
-- Table structure for table `zilp_transportation_renting_deals`
--

CREATE TABLE `zilp_transportation_renting_deals` (
  `id` int(11) NOT NULL,
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
  `CREATED_AT` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `zilp_transportation_renting_deals`
--

INSERT INTO `zilp_transportation_renting_deals` (`id`, `user_id`, `vehicle_photo`, `make`, `model`, `year`, `color`, `condition_description`, `accessories`, `description`, `rental_price`, `UPDATED_AT`, `CREATED_AT`) VALUES
(4, 13, 'KIRCBwiSjHs6f5LczWWmrp7O4RJ9z0icossXBkVQ.jpeg', '222', '222', '124', 'Red', 'Good', 'Hello', 'Hi', 20, '2020-07-31 20:02:47', '2020-07-31 20:02:47');

-- --------------------------------------------------------

--
-- Table structure for table `zilp_transportation_selling_deals`
--

CREATE TABLE `zilp_transportation_selling_deals` (
  `id` int(11) NOT NULL,
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
  `UPDATED_AT` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `zilp_users`
--

CREATE TABLE `zilp_users` (
  `id` int(11) NOT NULL,
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
  `trial_ends_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `zilp_users`
--

INSERT INTO `zilp_users` (`id`, `plate_number`, `email`, `first_name`, `last_name`, `password`, `phone`, `profile_pic`, `profile_visibility`, `guest_plate`, `balance`, `verified`, `verified_driver`, `CREATED_AT`, `UPDATED_AT`, `stripe_id`, `card_brand`, `card_last_four`, `trial_ends_at`) VALUES
(17, 'ABC123', 'adnanbabakan.personal@hotmail.com', 'adnan', 'babakan', '$2y$10$8N5JHRrdEIZ3H4elWgWbcuzvwlDXMqtJHshM0jdjkvKqSuurvDUMG', '00989393138160', 'default.png', 'PUBLIC', NULL, 500, 1, 'no', '2020-09-29 10:10:29', '2020-10-08 20:15:21', NULL, NULL, NULL, NULL),
(18, 'TEST123', 'test@test.com', 'Test', 'Test', '$2y$10$eSVnIRhxk9pXHUsxl3zRCerR2dKr04u22P7iUdXeeKEl0XpRtycVa', '00989147342451', 'default.png', 'PUBLIC', NULL, 0, 1, 'no', '2020-10-03 12:15:21', '2020-10-03 12:15:21', NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `zilp_chat_sessions`
--
ALTER TABLE `zilp_chat_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zilp_comments`
--
ALTER TABLE `zilp_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zilp_driver_documentations`
--
ALTER TABLE `zilp_driver_documentations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zilp_friends`
--
ALTER TABLE `zilp_friends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zilp_likes`
--
ALTER TABLE `zilp_likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zilp_messages`
--
ALTER TABLE `zilp_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zilp_migrations`
--
ALTER TABLE `zilp_migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zilp_notifications`
--
ALTER TABLE `zilp_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zilp_notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `zilp_oauth_access_tokens`
--
ALTER TABLE `zilp_oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zilp_oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `zilp_oauth_auth_codes`
--
ALTER TABLE `zilp_oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zilp_oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `zilp_oauth_clients`
--
ALTER TABLE `zilp_oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zilp_oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `zilp_oauth_personal_access_clients`
--
ALTER TABLE `zilp_oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zilp_oauth_refresh_tokens`
--
ALTER TABLE `zilp_oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zilp_parking_services`
--
ALTER TABLE `zilp_parking_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zilp_posts`
--
ALTER TABLE `zilp_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zilp_registered_plates`
--
ALTER TABLE `zilp_registered_plates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zilp_subscriptions`
--
ALTER TABLE `zilp_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zilp_subscriptions_user_id_stripe_status_index` (`user_id`,`stripe_status`);

--
-- Indexes for table `zilp_subscription_items`
--
ALTER TABLE `zilp_subscription_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `zilp_subscription_items_subscription_id_stripe_plan_unique` (`subscription_id`,`stripe_plan`),
  ADD KEY `zilp_subscription_items_stripe_id_index` (`stripe_id`);

--
-- Indexes for table `zilp_transactions`
--
ALTER TABLE `zilp_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zilp_transportation_booking_deals`
--
ALTER TABLE `zilp_transportation_booking_deals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zilp_transportation_renting_dates`
--
ALTER TABLE `zilp_transportation_renting_dates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zilp_transportation_renting_deals`
--
ALTER TABLE `zilp_transportation_renting_deals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zilp_transportation_selling_deals`
--
ALTER TABLE `zilp_transportation_selling_deals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zilp_users`
--
ALTER TABLE `zilp_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plate_number` (`plate_number`),
  ADD KEY `zilp_users_stripe_id_index` (`stripe_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `zilp_chat_sessions`
--
ALTER TABLE `zilp_chat_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `zilp_comments`
--
ALTER TABLE `zilp_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `zilp_driver_documentations`
--
ALTER TABLE `zilp_driver_documentations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `zilp_friends`
--
ALTER TABLE `zilp_friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `zilp_likes`
--
ALTER TABLE `zilp_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `zilp_messages`
--
ALTER TABLE `zilp_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `zilp_migrations`
--
ALTER TABLE `zilp_migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `zilp_oauth_clients`
--
ALTER TABLE `zilp_oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `zilp_oauth_personal_access_clients`
--
ALTER TABLE `zilp_oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `zilp_parking_services`
--
ALTER TABLE `zilp_parking_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `zilp_posts`
--
ALTER TABLE `zilp_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `zilp_registered_plates`
--
ALTER TABLE `zilp_registered_plates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `zilp_subscriptions`
--
ALTER TABLE `zilp_subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `zilp_subscription_items`
--
ALTER TABLE `zilp_subscription_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `zilp_transactions`
--
ALTER TABLE `zilp_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `zilp_transportation_booking_deals`
--
ALTER TABLE `zilp_transportation_booking_deals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `zilp_transportation_renting_dates`
--
ALTER TABLE `zilp_transportation_renting_dates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `zilp_transportation_renting_deals`
--
ALTER TABLE `zilp_transportation_renting_deals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `zilp_transportation_selling_deals`
--
ALTER TABLE `zilp_transportation_selling_deals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `zilp_users`
--
ALTER TABLE `zilp_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
