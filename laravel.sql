-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2026 at 09:24 PM
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
-- Database: `laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE `alerts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `parent_id` bigint(20) UNSIGNED NOT NULL,
  `child_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('screen_limit_reached','downtime_violation','new_app_installed','content_blocked','location_alert','pairing_success','crying_detected','unknown_contact','threat_blocked','device_offline') NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `notification_sent` tinyint(1) NOT NULL DEFAULT 0,
  `notification_type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_requests`
--

CREATE TABLE `app_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `child_id` bigint(20) UNSIGNED NOT NULL,
  `app_name` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `package_name` varchar(255) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `parent_response` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_usages`
--

CREATE TABLE `app_usages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `child_id` bigint(20) UNSIGNED NOT NULL,
  `app_name` varchar(255) NOT NULL,
  `package_name` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `duration` int(11) NOT NULL DEFAULT 0,
  `usage_date` date NOT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `app_usages`
--

INSERT INTO `app_usages` (`id`, `uuid`, `child_id`, `app_name`, `package_name`, `category`, `duration`, `usage_date`, `start_time`, `end_time`, `created_at`, `updated_at`) VALUES
(19, '143a082f-9a02-4fa5-9319-099314374c76', 100, 'Unknown', 'unknown.package', 'General', 1, '2026-04-11', NULL, NULL, '2026-04-11 18:20:30', '2026-04-11 18:32:30'),
(20, '3281a42a-5c97-4897-ba2a-536a17e124d9', 100, 'Camera', 'com.android.camera2', 'General', 0, '2026-04-11', NULL, NULL, '2026-04-11 18:57:58', '2026-04-11 20:32:27'),
(21, '6d5a19b7-4acd-4f5e-9ff5-e4591d8901c5', 100, 'Chrome', 'com.android.chrome', 'General', 7, '2026-04-11', NULL, NULL, '2026-04-11 18:57:58', '2026-04-11 20:32:27'),
(22, '222b964e-785d-41d1-afca-38fd85d640fe', 100, 'Settings', 'com.android.settings', 'General', 4, '2026-04-11', NULL, NULL, '2026-04-11 18:57:58', '2026-04-11 20:32:27'),
(23, 'aa606037-7e55-4f68-8957-b02427d98880', 100, 'Drive', 'com.google.android.apps.docs', 'General', 0, '2026-04-11', NULL, NULL, '2026-04-11 18:57:58', '2026-04-11 20:32:27'),
(24, '0f40dc5f-657c-4a9c-a1ab-61015a224dc0', 100, 'Maps', 'com.google.android.apps.maps', 'General', 0, '2026-04-11', NULL, NULL, '2026-04-11 18:57:58', '2026-04-11 20:32:27'),
(25, '13e96b5b-8908-45d2-9054-ed81f49a3d5f', 100, 'Messages', 'com.google.android.apps.messaging', 'General', 0, '2026-04-11', NULL, NULL, '2026-04-11 18:57:58', '2026-04-11 20:32:28'),
(26, 'ae467cf8-d028-4111-9580-cd0f37b0d1be', 100, 'Photos', 'com.google.android.apps.photos', 'General', 0, '2026-04-11', NULL, NULL, '2026-04-11 18:57:58', '2026-04-11 20:32:28'),
(27, '3ceb37ad-995d-4dd6-89a9-06d5228f3a22', 100, 'YT Music', 'com.google.android.apps.youtube.music', 'General', 0, '2026-04-11', NULL, NULL, '2026-04-11 18:57:58', '2026-04-11 20:32:28'),
(28, 'cc1107e8-ee05-4e14-b03a-ec309448d5d5', 100, 'Calendar', 'com.google.android.calendar', 'General', 4, '2026-04-11', NULL, NULL, '2026-04-11 18:57:58', '2026-04-11 20:32:28'),
(29, 'd1d938ef-c871-476b-affd-e4f7d951119b', 100, 'Contacts', 'com.google.android.contacts', 'General', 0, '2026-04-11', NULL, NULL, '2026-04-11 18:57:58', '2026-04-11 20:32:28'),
(30, 'f1c7a7ae-16b8-43ef-aad1-fa6bdccf3da8', 100, 'Clock', 'com.google.android.deskclock', 'General', 4, '2026-04-11', NULL, NULL, '2026-04-11 18:57:58', '2026-04-11 20:32:28'),
(31, '5e4a14d5-2a6c-4be8-8a67-57073fc1820b', 100, 'Phone', 'com.google.android.dialer', 'General', 0, '2026-04-11', NULL, NULL, '2026-04-11 18:57:58', '2026-04-11 20:32:28'),
(32, '507dd1cb-cf42-4607-acdf-f5a23b507750', 100, 'Gmail', 'com.google.android.gm', 'General', 0, '2026-04-11', NULL, NULL, '2026-04-11 18:57:58', '2026-04-11 20:32:28'),
(33, '73b4f712-6547-4739-8e32-3daae246287c', 100, 'YouTube', 'com.google.android.youtube', 'General', 0, '2026-04-11', NULL, NULL, '2026-04-11 18:57:58', '2026-04-11 20:32:28'),
(34, '227d3867-8f83-40cb-a216-c91adf2e2158', 100, 'Expo Go', 'host.exp.exponent', 'General', 0, '2026-04-11', NULL, NULL, '2026-04-11 18:57:58', '2026-04-11 20:32:28'),
(35, 'ff7e8acd-4a84-4298-a08b-0a55c2b542f6', 100, 'TMoble', 'com.android.stk', 'General', 0, '2026-04-11', NULL, NULL, '2026-04-11 18:57:58', '2026-04-11 20:32:28'),
(36, 'cb649358-cff5-4eeb-b88a-05ac288bdce9', 100, 'Files', 'com.google.android.documentsui', 'General', 14, '2026-04-11', NULL, NULL, '2026-04-11 18:57:58', '2026-04-11 20:32:28'),
(37, '8d2595ac-b255-49a7-b72c-939f535c9a95', 100, 'Google', 'com.google.android.googlequicksearchbox', 'General', 0, '2026-04-11', NULL, NULL, '2026-04-11 18:57:58', '2026-04-11 20:32:28'),
(38, '3a064b4e-6d4f-4302-93de-3713b18dbbcc', 100, 'SecureSproutExpo', 'com.securesprout.app', 'General', 251, '2026-04-11', NULL, NULL, '2026-04-11 18:57:58', '2026-04-11 20:32:28'),
(39, 'd57cdd8a-47fd-44d0-96c0-939ef0ed30e2', 100, 'DNSNet', 'dev.clombardo.dnsnet', 'General', 0, '2026-04-11', NULL, NULL, '2026-04-11 18:57:58', '2026-04-11 20:32:28'),
(40, '12f69636-b405-4251-87ef-827ffa588b83', 100, 'Camera', 'com.android.camera2', 'General', 0, '2026-04-13', NULL, NULL, '2026-04-13 06:42:05', '2026-04-13 08:49:28'),
(41, '5ced4686-f74b-4c3b-a1e5-4e75897a4934', 100, 'Chrome', 'com.android.chrome', 'General', 0, '2026-04-13', NULL, NULL, '2026-04-13 06:42:05', '2026-04-13 08:49:28'),
(42, 'c18e2fd8-d73e-415a-a3bd-16c69764f07d', 100, 'Settings', 'com.android.settings', 'General', 29, '2026-04-13', NULL, NULL, '2026-04-13 06:42:05', '2026-04-13 08:49:28'),
(43, '13523393-882e-4c90-9d63-22f76df1b24c', 100, 'Drive', 'com.google.android.apps.docs', 'General', 0, '2026-04-13', NULL, NULL, '2026-04-13 06:42:05', '2026-04-13 08:49:28'),
(44, 'de98540a-94d9-4107-ab62-7bc1e3a6e3d5', 100, 'Maps', 'com.google.android.apps.maps', 'General', 0, '2026-04-13', NULL, NULL, '2026-04-13 06:42:05', '2026-04-13 08:49:28'),
(45, 'd724cfb9-7a94-4fde-a2b4-0d9a4f32ef67', 100, 'Messages', 'com.google.android.apps.messaging', 'General', 0, '2026-04-13', NULL, NULL, '2026-04-13 06:42:05', '2026-04-13 08:49:28'),
(46, '61e4b004-e8e4-4182-abe1-3ce208072683', 100, 'Photos', 'com.google.android.apps.photos', 'General', 0, '2026-04-13', NULL, NULL, '2026-04-13 06:42:05', '2026-04-13 08:49:28'),
(47, '30d1636e-572a-43fb-ab49-ba7f770f6ac8', 100, 'YT Music', 'com.google.android.apps.youtube.music', 'General', 0, '2026-04-13', NULL, NULL, '2026-04-13 06:42:05', '2026-04-13 08:49:28'),
(48, '7ca0895e-fbcf-4f5e-9f82-0c06ad3d9f7e', 100, 'Calendar', 'com.google.android.calendar', 'General', 0, '2026-04-13', NULL, NULL, '2026-04-13 06:42:05', '2026-04-13 08:49:28'),
(49, '101704c5-95ba-4c16-afc9-4534ffe6f776', 100, 'Contacts', 'com.google.android.contacts', 'General', 0, '2026-04-13', NULL, NULL, '2026-04-13 06:42:05', '2026-04-13 08:49:28'),
(50, 'dd928bb6-e2d7-4b49-a4ea-44a2348bfc94', 100, 'Clock', 'com.google.android.deskclock', 'General', 2, '2026-04-13', NULL, NULL, '2026-04-13 06:42:05', '2026-04-13 08:49:28'),
(51, '0c6fc271-dd6d-48fa-b1de-c1f81dd15d68', 100, 'Phone', 'com.google.android.dialer', 'General', 0, '2026-04-13', NULL, NULL, '2026-04-13 06:42:05', '2026-04-13 08:49:28'),
(52, 'c78ad8b4-4d2b-4931-bdb1-4762f3a58b8d', 100, 'Gmail', 'com.google.android.gm', 'General', 0, '2026-04-13', NULL, NULL, '2026-04-13 06:42:05', '2026-04-13 08:49:28'),
(53, 'bd556750-0675-45a9-a92f-8da7daace3e9', 100, 'YouTube', 'com.google.android.youtube', 'General', 0, '2026-04-13', NULL, NULL, '2026-04-13 06:42:05', '2026-04-13 08:49:28'),
(54, '297c8622-f724-4139-a144-20507794312c', 100, 'Expo Go', 'host.exp.exponent', 'General', 0, '2026-04-13', NULL, NULL, '2026-04-13 06:42:05', '2026-04-13 08:49:28'),
(55, 'b975c551-5d57-4763-bda2-ba299987e375', 100, 'TMoble', 'com.android.stk', 'General', 0, '2026-04-13', NULL, NULL, '2026-04-13 06:42:05', '2026-04-13 08:49:28'),
(56, '848b6a22-cb7e-4f54-b7df-9169ad426c78', 100, 'Files', 'com.google.android.documentsui', 'General', 0, '2026-04-13', NULL, NULL, '2026-04-13 06:42:05', '2026-04-13 08:49:28'),
(57, '9252ee76-4d8a-49c7-851d-95cf8a247162', 100, 'Google', 'com.google.android.googlequicksearchbox', 'General', 0, '2026-04-13', NULL, NULL, '2026-04-13 06:42:05', '2026-04-13 08:49:28'),
(58, 'e1ef26e1-4341-4dde-8425-e54ec5d2f094', 100, 'SecureSproutExpo', 'com.securesprout.app', 'General', 23, '2026-04-13', NULL, NULL, '2026-04-13 06:42:05', '2026-04-13 08:49:28'),
(59, 'b0e5a1dc-4ebe-42b3-946b-78085b470729', 100, 'DNSNet', 'dev.clombardo.dnsnet', 'General', 0, '2026-04-13', NULL, NULL, '2026-04-13 06:42:05', '2026-04-13 08:49:28'),
(60, '79775d98-b731-437f-995a-76988c6ead43', 100, 'Camera', 'com.android.camera2', 'General', 0, '2026-04-14', NULL, NULL, '2026-04-14 14:07:20', '2026-04-14 17:34:37'),
(61, 'c3438548-743c-4ce3-8b9e-880b4f72cab5', 100, 'Chrome', 'com.android.chrome', 'General', 0, '2026-04-14', NULL, NULL, '2026-04-14 14:07:20', '2026-04-14 17:34:37'),
(62, '3ed92a12-be52-448a-8684-ceac119967cc', 100, 'Settings', 'com.android.settings', 'General', 29, '2026-04-14', NULL, NULL, '2026-04-14 14:07:20', '2026-04-14 17:34:37'),
(63, '516d945d-58a2-4249-aa4b-56ccf1473343', 100, 'Drive', 'com.google.android.apps.docs', 'General', 0, '2026-04-14', NULL, NULL, '2026-04-14 14:07:20', '2026-04-14 17:34:37'),
(64, 'c7ea90ac-6506-426a-b801-b24d7455e06e', 100, 'Maps', 'com.google.android.apps.maps', 'General', 0, '2026-04-14', NULL, NULL, '2026-04-14 14:07:20', '2026-04-14 17:34:37'),
(65, '2cad604f-b8d9-457a-a33b-7a2d7fc0f661', 100, 'Messages', 'com.google.android.apps.messaging', 'General', 0, '2026-04-14', NULL, NULL, '2026-04-14 14:07:20', '2026-04-14 17:34:37'),
(66, 'e203561d-22fe-4d63-89ae-72db6114984a', 100, 'Photos', 'com.google.android.apps.photos', 'General', 0, '2026-04-14', NULL, NULL, '2026-04-14 14:07:20', '2026-04-14 17:34:37'),
(67, '33744c71-8541-4e76-8cea-d97f394478d9', 100, 'YT Music', 'com.google.android.apps.youtube.music', 'General', 0, '2026-04-14', NULL, NULL, '2026-04-14 14:07:20', '2026-04-14 17:34:37'),
(68, '4e12eab7-e534-4748-8c08-69413ec2d233', 100, 'Calendar', 'com.google.android.calendar', 'General', 0, '2026-04-14', NULL, NULL, '2026-04-14 14:07:20', '2026-04-14 17:34:37'),
(69, 'e1e79120-183e-4280-ae58-61225b7f0bf5', 100, 'Contacts', 'com.google.android.contacts', 'General', 0, '2026-04-14', NULL, NULL, '2026-04-14 14:07:20', '2026-04-14 17:34:37'),
(70, '8adb1de1-48e7-44e3-8a67-ea7fc7102b8f', 100, 'Clock', 'com.google.android.deskclock', 'General', 2, '2026-04-14', NULL, NULL, '2026-04-14 14:07:20', '2026-04-14 17:34:37'),
(71, '98253f14-9a28-48b0-affb-bc721b9760e5', 100, 'Phone', 'com.google.android.dialer', 'General', 0, '2026-04-14', NULL, NULL, '2026-04-14 14:07:20', '2026-04-14 17:34:37'),
(72, 'fcc7bb35-50cd-4c43-9f37-b7794efb376f', 100, 'Gmail', 'com.google.android.gm', 'General', 0, '2026-04-14', NULL, NULL, '2026-04-14 14:07:20', '2026-04-14 17:34:37'),
(73, '1e85add8-d511-4eaa-9b24-044c10339256', 100, 'YouTube', 'com.google.android.youtube', 'General', 0, '2026-04-14', NULL, NULL, '2026-04-14 14:07:20', '2026-04-14 17:34:37'),
(74, '36946e49-14c3-4d45-b352-41578022cbeb', 100, 'Expo Go', 'host.exp.exponent', 'General', 0, '2026-04-14', NULL, NULL, '2026-04-14 14:07:20', '2026-04-14 17:34:37'),
(75, 'd86abc98-c5a8-4e46-8af0-8f7b75066b35', 100, 'TMoble', 'com.android.stk', 'General', 0, '2026-04-14', NULL, NULL, '2026-04-14 14:07:20', '2026-04-14 17:34:37'),
(76, '93fa7692-f277-408b-8ce6-a8e7a96bba16', 100, 'Files', 'com.google.android.documentsui', 'General', 0, '2026-04-14', NULL, NULL, '2026-04-14 14:07:20', '2026-04-14 17:34:37'),
(77, '54e8a997-651f-4a0c-b3fd-aa9f3e0af03f', 100, 'Google', 'com.google.android.googlequicksearchbox', 'General', 0, '2026-04-14', NULL, NULL, '2026-04-14 14:07:20', '2026-04-14 17:34:37'),
(78, 'a9e01367-b27e-4bfa-8534-132fab2ec122', 100, 'SecureSproutExpo', 'com.securesprout.app', 'General', 108, '2026-04-14', NULL, NULL, '2026-04-14 14:07:20', '2026-04-14 17:34:37'),
(79, '70215620-4431-4b80-aa05-90bd27d1b3a0', 100, 'DNSNet', 'dev.clombardo.dnsnet', 'General', 0, '2026-04-14', NULL, NULL, '2026-04-14 14:07:20', '2026-04-14 17:34:37'),
(80, 'e2fa7539-da73-4449-91e1-990f45cc287c', 100, 'Camera', 'com.android.camera2', 'General', 0, '2026-04-16', NULL, NULL, '2026-04-16 19:56:45', '2026-04-16 21:08:57'),
(81, 'f28fc68d-bdd1-4de4-ac68-8147458c7bdf', 100, 'Chrome', 'com.android.chrome', 'General', 36, '2026-04-16', NULL, NULL, '2026-04-16 19:56:45', '2026-04-16 21:08:57'),
(82, '547d1c3e-eda9-49cc-b5f5-461b30965fe3', 100, 'Settings', 'com.android.settings', 'General', 0, '2026-04-16', NULL, NULL, '2026-04-16 19:56:45', '2026-04-16 21:08:57'),
(83, '3e699566-3219-4158-9769-f94651040f8a', 100, 'Drive', 'com.google.android.apps.docs', 'General', 0, '2026-04-16', NULL, NULL, '2026-04-16 19:56:45', '2026-04-16 21:08:57'),
(84, '1b500bed-91cf-4974-b4e9-af79c4bcb5c6', 100, 'Maps', 'com.google.android.apps.maps', 'General', 0, '2026-04-16', NULL, NULL, '2026-04-16 19:56:45', '2026-04-16 21:08:57'),
(85, 'da9d0353-4f82-450d-8775-26743c1bee97', 100, 'Messages', 'com.google.android.apps.messaging', 'General', 0, '2026-04-16', NULL, NULL, '2026-04-16 19:56:45', '2026-04-16 21:08:58'),
(86, '0f7d5386-7dcd-4fd0-86b7-092896b3fdf1', 100, 'Photos', 'com.google.android.apps.photos', 'General', 0, '2026-04-16', NULL, NULL, '2026-04-16 19:56:45', '2026-04-16 21:08:58'),
(87, '8388961c-d155-45ff-85be-089b0ca590c9', 100, 'YT Music', 'com.google.android.apps.youtube.music', 'General', 0, '2026-04-16', NULL, NULL, '2026-04-16 19:56:45', '2026-04-16 21:08:58'),
(88, 'c85c989f-e919-4fc4-8140-8b38e4e62bd3', 100, 'Calendar', 'com.google.android.calendar', 'General', 0, '2026-04-16', NULL, NULL, '2026-04-16 19:56:45', '2026-04-16 21:08:58'),
(89, 'a76b4443-7b2d-400f-b4b6-51b3039d7ea3', 100, 'Contacts', 'com.google.android.contacts', 'General', 0, '2026-04-16', NULL, NULL, '2026-04-16 19:56:45', '2026-04-16 21:08:58'),
(90, 'a223bdbf-1265-42f9-8eae-d35ec1fc51e3', 100, 'Clock', 'com.google.android.deskclock', 'General', 0, '2026-04-16', NULL, NULL, '2026-04-16 19:56:45', '2026-04-16 21:08:58'),
(91, '07705070-98b3-47ec-bb5d-5c8c576a8ced', 100, 'Phone', 'com.google.android.dialer', 'General', 0, '2026-04-16', NULL, NULL, '2026-04-16 19:56:45', '2026-04-16 21:08:58'),
(92, '4c228a4f-3a44-48ab-a5bd-f91b55eb3ac9', 100, 'Gmail', 'com.google.android.gm', 'General', 0, '2026-04-16', NULL, NULL, '2026-04-16 19:56:45', '2026-04-16 21:08:58'),
(93, 'c77b4a7d-2b0a-4961-9610-c7720f9e6749', 100, 'YouTube', 'com.google.android.youtube', 'General', 0, '2026-04-16', NULL, NULL, '2026-04-16 19:56:45', '2026-04-16 21:08:58'),
(94, 'b0c1b000-3355-4d7e-8609-fa16c7f038cb', 100, 'Expo Go', 'host.exp.exponent', 'General', 0, '2026-04-16', NULL, NULL, '2026-04-16 19:56:45', '2026-04-16 21:08:58'),
(95, 'a69cd935-d45c-4512-a463-693678533f04', 100, 'TMoble', 'com.android.stk', 'General', 0, '2026-04-16', NULL, NULL, '2026-04-16 19:56:45', '2026-04-16 21:08:58'),
(96, '78df9ca7-48ba-4b43-9dc2-6d266de023c3', 100, 'Files', 'com.google.android.documentsui', 'General', 0, '2026-04-16', NULL, NULL, '2026-04-16 19:56:45', '2026-04-16 21:08:58'),
(97, '353a8167-8d8e-4b03-bf43-cc13b2871b99', 100, 'Google', 'com.google.android.googlequicksearchbox', 'General', 0, '2026-04-16', NULL, NULL, '2026-04-16 19:56:45', '2026-04-16 21:08:58'),
(98, 'a6f9c64f-6028-4159-af5d-a72c73c0dcb2', 100, 'SecureSproutExpo', 'com.securesprout.app', 'General', 19, '2026-04-16', NULL, NULL, '2026-04-16 19:56:45', '2026-04-16 21:08:58'),
(99, 'f366c13b-74f3-4578-a0fe-c2e440e38612', 100, 'DNSNet', 'dev.clombardo.dnsnet', 'General', 0, '2026-04-16', NULL, NULL, '2026-04-16 19:56:45', '2026-04-16 21:08:58'),
(100, 'df264c2e-a91c-4182-9ef9-b18cd2ed7b24', 100, 'Camera', 'com.android.camera2', 'General', 0, '2026-04-27', NULL, NULL, '2026-04-27 18:23:27', '2026-04-27 18:23:27'),
(101, 'ded6268d-a330-4458-af0f-501e05a64989', 100, 'Chrome', 'com.android.chrome', 'General', 36, '2026-04-27', NULL, NULL, '2026-04-27 18:23:27', '2026-04-27 18:23:27'),
(102, '3d1269f7-2947-4969-a684-2fc5e061c1db', 100, 'Settings', 'com.android.settings', 'General', 3, '2026-04-27', NULL, NULL, '2026-04-27 18:23:27', '2026-04-27 18:23:27'),
(103, 'e71f8f24-7e41-4915-9697-3e6e51cc5a6f', 100, 'Drive', 'com.google.android.apps.docs', 'General', 0, '2026-04-27', NULL, NULL, '2026-04-27 18:23:27', '2026-04-27 18:23:27'),
(104, '3ba0019a-bc62-44ac-88df-56182f76226d', 100, 'Maps', 'com.google.android.apps.maps', 'General', 0, '2026-04-27', NULL, NULL, '2026-04-27 18:23:27', '2026-04-27 18:23:27'),
(105, '7b4dd973-c414-4e6e-9f76-5da1f718db2e', 100, 'Messages', 'com.google.android.apps.messaging', 'General', 0, '2026-04-27', NULL, NULL, '2026-04-27 18:23:27', '2026-04-27 18:23:27'),
(106, '255621ec-4365-4c98-96ec-4250e9d751f0', 100, 'Photos', 'com.google.android.apps.photos', 'General', 0, '2026-04-27', NULL, NULL, '2026-04-27 18:23:27', '2026-04-27 18:23:27'),
(107, '0ea03ab8-8c79-489a-bd9c-44c8c5c0b86b', 100, 'YT Music', 'com.google.android.apps.youtube.music', 'General', 0, '2026-04-27', NULL, NULL, '2026-04-27 18:23:27', '2026-04-27 18:23:27'),
(108, 'e0af78ca-0549-4b49-84b2-de8b965d1ece', 100, 'Calendar', 'com.google.android.calendar', 'General', 0, '2026-04-27', NULL, NULL, '2026-04-27 18:23:27', '2026-04-27 18:23:27'),
(109, '9afd37c5-843f-4f0c-a510-e39882c1429f', 100, 'Contacts', 'com.google.android.contacts', 'General', 0, '2026-04-27', NULL, NULL, '2026-04-27 18:23:27', '2026-04-27 18:23:27'),
(110, 'e0bc6e47-f212-4ac7-9d20-d5888749912a', 100, 'Clock', 'com.google.android.deskclock', 'General', 0, '2026-04-27', NULL, NULL, '2026-04-27 18:23:27', '2026-04-27 18:23:27'),
(111, '2b72b1dd-8a39-49e6-8d8c-8170e9e63413', 100, 'Phone', 'com.google.android.dialer', 'General', 0, '2026-04-27', NULL, NULL, '2026-04-27 18:23:27', '2026-04-27 18:23:27'),
(112, '3db2ca81-49d5-4284-8bac-59eab6037ae2', 100, 'Gmail', 'com.google.android.gm', 'General', 0, '2026-04-27', NULL, NULL, '2026-04-27 18:23:27', '2026-04-27 18:23:27'),
(113, '9abb67c7-0d68-46f3-b151-0a486174e132', 100, 'YouTube', 'com.google.android.youtube', 'General', 0, '2026-04-27', NULL, NULL, '2026-04-27 18:23:27', '2026-04-27 18:23:27'),
(114, 'f59eb912-ae0e-45ef-9f79-94810a214048', 100, 'SecureSproutExpo', 'com.securesprout.app', 'General', 93, '2026-04-28', NULL, NULL, '2026-04-27 18:23:27', '2026-04-27 18:23:27'),
(115, 'e8762ed1-6f7b-4fa1-84fd-b3e84a757e77', 100, 'Expo Go', 'host.exp.exponent', 'General', 0, '2026-04-27', NULL, NULL, '2026-04-27 18:23:27', '2026-04-27 18:23:27'),
(116, 'b0a32faf-df09-49d7-a9c0-6120d37a9557', 100, 'TMoble', 'com.android.stk', 'General', 0, '2026-04-27', NULL, NULL, '2026-04-27 18:23:27', '2026-04-27 18:23:27'),
(117, '418de009-0337-4390-8ae0-590097ff6818', 100, 'Files', 'com.google.android.documentsui', 'General', 0, '2026-04-27', NULL, NULL, '2026-04-27 18:23:27', '2026-04-27 18:23:27'),
(118, '8ba3e46f-0152-4607-abaa-34799664adcb', 100, 'Google', 'com.google.android.googlequicksearchbox', 'General', 0, '2026-04-27', NULL, NULL, '2026-04-27 18:23:27', '2026-04-27 18:23:27'),
(119, 'f71337e9-9dfa-41e5-b901-59fe07139036', 100, 'DNSNet', 'dev.clombardo.dnsnet', 'General', 0, '2026-04-27', NULL, NULL, '2026-04-27 18:23:27', '2026-04-27 18:23:27');

-- --------------------------------------------------------

--
-- Table structure for table `childrens`
--

CREATE TABLE `childrens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `parent_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` enum('child','teen') NOT NULL DEFAULT 'child',
  `date_of_birth` date NOT NULL,
  `age` int(11) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `childrens`
--

INSERT INTO `childrens` (`id`, `uuid`, `parent_id`, `name`, `email`, `password`, `type`, `date_of_birth`, `age`, `profile_image`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, '', 3, 'koky\r\n', 'ko@ko.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'child', '0000-00-00', NULL, NULL, 0, '2026-04-04 02:03:18', '2026-05-02 16:21:02', '2026-05-02 16:21:02'),
(8, '6d140c09-8adf-4a2f-8e4c-f9b9e4fecbff', 3, 'Soso', 'so@so.com', '$2y$12$m5vYV3nZe4WYQv72TLSAwu8RU0wcNtPj9/2Vk9jaQDHWoPpH8UYHa', 'child', '2025-01-01', NULL, NULL, 1, '2026-03-30 19:12:58', '2026-05-01 17:13:21', '2026-05-01 17:13:21'),
(100, 'f4e6e49d-9130-4770-93c0-02ba5c3ab877', 3, 'toty', 'to@to.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'child', '2018-04-05', 8, NULL, 1, '2026-04-10 20:01:21', '2026-04-30 20:14:48', NULL),
(101, '7a51d9f6-81f7-43af-8539-2dd215e79ca0', 3, 'Yason', 'y@y.com', '$2y$12$BOGb7LQpXyQQ6MT5hkAdNekH/B6gGTttbV.sJiN6K9mDQYaKhCHa6', 'child', '2025-01-01', NULL, NULL, 1, '2026-04-11 17:13:48', '2026-04-11 17:16:35', NULL),
(102, '11849341-5445-4f78-b649-f817f5e10797', 3, 'anas', 'an@an.com', '$2y$12$L6f/UxYbwNovvlcP/voQ.OveRPiS5Gpk56/6nX/rAQ1BRScYGy4gO', 'child', '2025-01-01', NULL, NULL, 1, '2026-05-02 07:35:21', '2026-05-02 07:37:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `child_apps`
--

CREATE TABLE `child_apps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `child_id` bigint(20) UNSIGNED NOT NULL,
  `app_name` varchar(255) NOT NULL,
  `package_name` varchar(255) NOT NULL,
  `is_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `time_limit` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `child_apps`
--

INSERT INTO `child_apps` (`id`, `child_id`, `app_name`, `package_name`, `is_blocked`, `time_limit`, `created_at`, `updated_at`) VALUES
(255, 100, 'Camera', 'com.android.camera2', 0, 0, '2026-05-07 17:06:37', '2026-05-07 17:06:37'),
(256, 100, 'Chrome', 'com.android.chrome', 0, 0, '2026-05-07 17:06:37', '2026-05-07 17:06:37'),
(257, 100, 'Settings', 'com.android.settings', 0, 0, '2026-05-07 17:06:37', '2026-05-07 17:06:37'),
(258, 100, 'Drive', 'com.google.android.apps.docs', 0, 0, '2026-05-07 17:06:37', '2026-05-07 17:06:37'),
(259, 100, 'Maps', 'com.google.android.apps.maps', 0, 0, '2026-05-07 17:06:37', '2026-05-07 17:06:37'),
(260, 100, 'Messages', 'com.google.android.apps.messaging', 0, 0, '2026-05-07 17:06:37', '2026-05-07 17:06:37'),
(261, 100, 'Photos', 'com.google.android.apps.photos', 0, 0, '2026-05-07 17:06:37', '2026-05-07 17:06:37'),
(262, 100, 'YT Music', 'com.google.android.apps.youtube.music', 0, 0, '2026-05-07 17:06:37', '2026-05-07 17:06:37'),
(263, 100, 'Calendar', 'com.google.android.calendar', 0, 0, '2026-05-07 17:06:37', '2026-05-07 17:06:37'),
(264, 100, 'Contacts', 'com.google.android.contacts', 0, 0, '2026-05-07 17:06:37', '2026-05-07 17:06:37'),
(265, 100, 'Clock', 'com.google.android.deskclock', 0, 0, '2026-05-07 17:06:37', '2026-05-07 17:06:37'),
(266, 100, 'Phone', 'com.google.android.dialer', 0, 0, '2026-05-07 17:06:37', '2026-05-07 17:06:37'),
(267, 100, 'Gmail', 'com.google.android.gm', 0, 0, '2026-05-07 17:06:37', '2026-05-07 17:06:37'),
(268, 100, 'YouTube', 'com.google.android.youtube', 0, 0, '2026-05-07 17:06:37', '2026-05-07 17:06:37'),
(269, 100, 'Expo Go', 'host.exp.exponent', 0, 0, '2026-05-07 17:06:37', '2026-05-07 17:06:37'),
(270, 100, 'TMoble', 'com.android.stk', 0, 0, '2026-05-07 17:06:37', '2026-05-07 17:06:37'),
(271, 100, 'Files', 'com.google.android.documentsui', 0, 0, '2026-05-07 17:06:37', '2026-05-07 17:06:37'),
(272, 100, 'Google', 'com.google.android.googlequicksearchbox', 0, 0, '2026-05-07 17:06:37', '2026-05-07 19:21:07'),
(274, 100, 'DNSNet', 'dev.clombardo.dnsnet', 0, 0, '2026-05-07 17:06:37', '2026-05-07 17:06:37'),
(275, 100, 'Hello world app', 'com.fluffycat.gtacheatslist.lcs', 0, 0, '2026-05-07 19:13:32', '2026-05-07 19:13:32'),
(276, 100, 'Notes', 'com.simplemobiletools.notes', 0, 0, '2026-05-07 19:13:32', '2026-05-07 19:13:32');

-- --------------------------------------------------------

--
-- Table structure for table `crying_logs`
--

CREATE TABLE `crying_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `child_id` bigint(20) UNSIGNED NOT NULL,
  `duration_seconds` int(11) NOT NULL,
  `intensity` decimal(3,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `child_id` bigint(20) UNSIGNED DEFAULT NULL,
  `device_name` varchar(255) DEFAULT NULL,
  `device_model` varchar(255) DEFAULT NULL,
  `os` varchar(255) DEFAULT NULL,
  `device_token` varchar(255) DEFAULT NULL,
  `fcm_token` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','pending') NOT NULL DEFAULT 'pending',
  `last_active_at` timestamp NULL DEFAULT NULL,
  `app_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`app_info`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `downtimes`
--

CREATE TABLE `downtimes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `child_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `days` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`days`)),
  `block_all` tinyint(1) NOT NULL DEFAULT 1,
  `allowed_apps` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`allowed_apps`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `downtimes`
--

INSERT INTO `downtimes` (`id`, `uuid`, `child_id`, `name`, `start_time`, `end_time`, `days`, `block_all`, `allowed_apps`, `created_at`, `updated_at`) VALUES
(1, '29932283-d404-48e1-ae84-3230e355d6f1', 100, 'Downtime Schedule', '00:24:00', '01:26:00', '[0,1,2,3,4,5,6]', 1, '[]', '2026-04-27 19:24:35', '2026-04-27 19:24:35'),
(2, '24b59231-deda-4220-893a-9c8c917e6bac', 101, 'Downtime Schedule', '11:45:00', '11:47:00', '[0,1,2,3,4,5,6]', 1, '[]', '2026-04-28 05:46:19', '2026-04-28 05:46:19'),
(3, 'f32d012f-7325-4d8a-a9b1-6f91e89ef3e5', 100, 'Downtime Schedule', '11:45:00', '11:47:00', '[0,1,2,3,4,5,6]', 1, '[]', '2026-04-28 05:46:20', '2026-04-28 05:46:20');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `child_id` bigint(20) UNSIGNED NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `is_latest` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `uuid`, `child_id`, `latitude`, `longitude`, `address`, `is_latest`, `created_at`, `updated_at`) VALUES
(6, 'da95c650-3bca-42ea-af80-34e114e62dac', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 13:55:34', '2026-05-02 13:56:33'),
(7, '520b8def-e404-487a-868b-5cd65cdab538', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 13:56:33', '2026-05-02 13:57:32'),
(8, 'c08b4740-aba5-4e5c-ad10-fa812318a851', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 13:57:32', '2026-05-02 13:58:32'),
(9, '23446edc-10bc-421f-ba30-d6c56fb6fe34', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 13:58:32', '2026-05-02 13:59:32'),
(10, 'faaea65f-9741-4970-8f68-9045909e43e8', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 13:59:32', '2026-05-02 14:00:32'),
(11, '8060f887-a904-4c2a-837a-ebe1bdcdcd77', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:00:32', '2026-05-02 14:01:32'),
(12, 'f16af9f2-603a-46d0-b641-6a6fc9c8c80f', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:01:32', '2026-05-02 14:02:32'),
(13, '5527d764-db0f-45d8-8c87-2258892b2667', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:02:32', '2026-05-02 14:03:32'),
(14, 'f82aa941-2944-4fb5-9646-22b17d1ffd7d', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:03:32', '2026-05-02 14:04:32'),
(15, '07fc30d2-12ff-434f-a1c9-a9dd7895d804', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:04:32', '2026-05-02 14:05:32'),
(16, 'a7d48769-618a-4aab-a928-f0e6498da1f2', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:05:32', '2026-05-02 14:06:32'),
(17, '7f8cf452-9021-4220-8fe6-862bf3c59dc0', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:06:32', '2026-05-02 14:07:32'),
(18, 'd1aacffa-a698-4bf9-a885-a877901b4450', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:07:32', '2026-05-02 14:08:32'),
(19, '5b45b861-4322-4ec1-8d74-5d33374cfe0d', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:08:32', '2026-05-02 14:09:32'),
(20, 'd95b0fff-cd47-4730-ab1c-26622db5f923', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:09:32', '2026-05-02 14:10:32'),
(21, '549099a7-8554-4c9a-ac6e-be496a0259d0', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:10:32', '2026-05-02 14:11:32'),
(22, 'c02665aa-c77e-4a3d-9520-9f23223644a3', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:11:32', '2026-05-02 14:12:32'),
(23, 'bc242962-b4fe-427a-b37b-840134e02777', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:12:32', '2026-05-02 14:13:32'),
(24, 'bbde8e9a-076e-4eec-b506-ce74e37148bb', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:13:32', '2026-05-02 14:14:32'),
(25, 'd8f65b64-0732-4592-bf28-7ad14871de19', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:14:32', '2026-05-02 14:15:32'),
(26, 'fca62d00-c378-4d6d-9638-a24bc565834e', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:15:32', '2026-05-02 14:16:33'),
(27, '13d9b286-9fd9-43d2-8201-78f6f95cfbb1', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:16:33', '2026-05-02 14:17:33'),
(28, '144d1abd-67f5-4d5a-8727-e4772b44033c', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:17:33', '2026-05-02 14:18:33'),
(29, 'f61c81dd-0eec-4111-922b-c510c2a1cf3b', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:18:33', '2026-05-02 14:19:33'),
(30, '2f4c645d-8839-4ea2-ad92-cd7da06748fa', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:19:33', '2026-05-02 14:20:33'),
(31, '1e7684f4-7078-49e7-82b2-d80e1c18064b', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:20:33', '2026-05-02 14:21:33'),
(32, '362d2ea5-8a4e-41b2-a361-e995fd726194', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:21:33', '2026-05-02 14:22:33'),
(33, '34a56da1-604c-4373-9cf1-4c2a78b67a08', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:22:33', '2026-05-02 14:23:33'),
(34, '042e570b-dc00-4ef2-a337-415e4f44f6da', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:23:33', '2026-05-02 14:24:33'),
(35, '5c452f3d-c5f6-44a2-b566-4d3af222e508', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:24:33', '2026-05-02 14:25:33'),
(36, 'b9da14dd-9436-400e-b04e-b0ba6571bbe7', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:25:33', '2026-05-02 14:26:33'),
(37, 'd4a731c9-9441-4556-8f73-998f83f40696', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:26:33', '2026-05-02 14:27:33'),
(38, '7151dce6-0529-4f3e-acc0-b492f563b176', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:27:33', '2026-05-02 14:28:33'),
(39, '985e61ae-65e8-4b0c-98f9-026135e49459', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:28:33', '2026-05-02 14:29:33'),
(40, '7c8c8078-f734-4c3d-9809-d4811c7b8e39', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:29:33', '2026-05-02 14:30:33'),
(41, '34304ecd-dd11-44f2-bee6-be84ec1c97b5', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:30:33', '2026-05-02 14:31:33'),
(42, '27d922fd-e56a-4c39-9a79-2a68ac8af051', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:31:33', '2026-05-02 14:32:33'),
(43, 'a9575a50-46f1-40a5-99b7-03ca6238aea7', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:32:33', '2026-05-02 14:33:33'),
(44, '1b781037-5e57-4df7-b24f-2a610b2dea04', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:33:33', '2026-05-02 14:34:33'),
(45, '696165fb-66e1-49fe-9796-1db5759c716f', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:34:33', '2026-05-02 14:35:32'),
(46, '6ff7366b-c5ed-48ae-8ca1-68eed0c9267b', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:35:32', '2026-05-02 14:36:33'),
(47, 'f8ef3789-e8dc-4891-b2a9-929cd66c20c5', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:36:33', '2026-05-02 14:37:33'),
(48, 'e711ea0d-2d23-413a-b171-97fafa0608b7', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:37:33', '2026-05-02 14:38:33'),
(49, '920d2002-aa1a-4426-a88c-d37a09fa2c08', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:38:33', '2026-05-02 14:39:33'),
(50, '26e65891-f399-49da-895f-496d3eb743a2', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:39:33', '2026-05-02 14:40:33'),
(51, '042142ac-997f-4615-8bd5-218cb753d2bd', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:40:33', '2026-05-02 14:41:32'),
(52, '3800872a-bb88-4b08-8b27-d29a52be4868', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-02 14:41:32', '2026-05-05 06:09:16'),
(53, '8428fcae-cd55-4717-806b-237c863ff79d', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-05 06:09:18', '2026-05-05 06:10:16'),
(54, 'f81dc76e-0f18-432e-ad2b-73fe608d0e54', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-05 06:10:16', '2026-05-05 06:12:19'),
(55, '0b3ab286-8c3a-4130-8e3f-b747f01fd56b', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-05 06:12:19', '2026-05-05 06:13:45'),
(56, '12ba9009-b092-4d58-b914-91f7d8e8ba1b', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-05 06:13:45', '2026-05-05 06:15:02'),
(57, 'cb5d5be6-df7a-4b2d-ae02-323d4dc431ee', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-05 06:15:02', '2026-05-05 06:25:02'),
(58, '47ffc29f-4354-493d-a079-6086ded41ffa', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-05 06:25:02', '2026-05-05 06:26:01'),
(59, '99f41452-5b5b-4bd8-b17f-2e25953d33d4', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-05 06:26:01', '2026-05-05 06:26:59'),
(60, 'b39072ce-026f-4d48-94d3-1776dd323239', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-05 06:26:59', '2026-05-07 09:48:08'),
(61, 'a9fa74fd-af61-40ca-adba-d331caf075a3', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 09:48:10', '2026-05-07 10:02:32'),
(62, 'f7e97c58-b94c-4e80-a4b5-a102b265013d', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 10:02:32', '2026-05-07 10:19:01'),
(63, 'aa90efb8-3df1-471f-a0a8-72716a4fbf55', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 10:19:01', '2026-05-07 10:22:00'),
(64, 'ac0baaca-8801-4418-b7b6-70b8098be03c', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 10:22:00', '2026-05-07 10:29:29'),
(65, 'e084fef5-fb7a-4f7a-afef-e1466c71a7d2', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 10:29:29', '2026-05-07 11:15:59'),
(66, '1f3a7281-aadc-4e34-9e8f-c46d825fe7da', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 11:16:00', '2026-05-07 11:21:48'),
(67, 'b9c4d275-43c3-4c2c-8155-f2a1cb8cace7', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 11:21:48', '2026-05-07 12:21:04'),
(68, 'df7150d8-349b-48b8-a165-2d981bf8a937', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 12:21:04', '2026-05-07 12:28:49'),
(69, '86d19864-a53d-4dfe-bb79-88521f383c33', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 12:28:49', '2026-05-07 12:37:08'),
(70, '4fa55c25-c294-4314-96f8-6a14eb78a49c', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 12:37:08', '2026-05-07 12:38:06'),
(71, '8da92b2c-5b61-49d1-aa9c-9598876c4c08', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 12:38:06', '2026-05-07 12:39:06'),
(72, '5609a5f9-90af-4cb9-8573-93f872b9aa6a', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 12:39:06', '2026-05-07 12:40:06'),
(73, 'ace248fd-d537-428e-9459-060d0285830e', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 12:40:06', '2026-05-07 12:41:06'),
(74, '5d0b3fd5-be6a-4f53-b5fd-25afe655f544', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 12:41:06', '2026-05-07 12:42:06'),
(75, '5ebb4a78-0eb6-482c-bb69-5906222763df', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 12:42:06', '2026-05-07 12:44:13'),
(76, 'c7d58366-074e-4fa8-bafc-af42e01e915a', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 12:44:13', '2026-05-07 13:03:05'),
(77, '4ce9e267-cfd2-460b-a62a-3365e6693700', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 13:03:07', '2026-05-07 13:33:48'),
(78, '6c32c3d8-8d9a-40e1-bced-75b51b2e834a', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 13:33:50', '2026-05-07 13:55:56'),
(79, 'df5f02e5-1eba-4660-8c46-990075fb9535', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 13:55:56', '2026-05-07 14:13:18'),
(80, '14a3e525-d404-441a-942b-21b8bede9a36', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 14:13:18', '2026-05-07 14:42:25'),
(81, 'c1c3d72a-edf9-4cc4-87e6-1484eab79922', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 14:42:26', '2026-05-07 15:15:24'),
(82, '5d7f976e-09be-482d-aea4-802c04d0fa1e', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 15:15:24', '2026-05-07 16:02:50'),
(83, '95b5850d-78e6-4274-be78-cd528204a8b0', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 16:02:50', '2026-05-07 16:15:33'),
(84, 'b246854f-66c4-48c9-a3a4-b06b5e3b4f3e', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 16:15:33', '2026-05-07 16:25:21'),
(85, '08af5c07-f543-42f6-8adb-86c8735e6d4b', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 16:25:21', '2026-05-07 16:48:43'),
(86, '34eb424f-a2d8-45e6-9eff-594b4fb25137', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 16:48:43', '2026-05-07 17:06:41'),
(87, '0354469d-79e9-4169-bc2c-81ed3cbf3053', 100, 37.42199830, -122.08400000, 'Device Live Location', 0, '2026-05-07 17:06:41', '2026-05-07 19:21:11'),
(88, '8f65ed20-46f8-4a90-a646-9a5defa09e86', 100, 37.42199830, -122.08400000, 'Device Live Location', 1, '2026-05-07 19:21:11', '2026-05-07 19:21:11');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_01_28_164931_create_parents_table', 1),
(6, '2026_01_28_165209_create_childrens_table', 1),
(7, '2026_01_28_165323_create_pairing_sessions_table', 1),
(8, '2026_01_28_165417_create_devices_table', 1),
(9, '2026_01_28_165521_create_rules_table', 1),
(10, '2026_01_28_165727_create_downtimes_table', 1),
(11, '2026_01_28_165822_create_app_usages_table', 1),
(12, '2026_01_28_165927_create_alerts_table', 1),
(13, '2026_01_28_170034_create_locations_table', 1),
(14, '2026_01_28_170116_create_app_requests_table', 1),
(15, '2026_01_28_170215_create_notification_settings_table', 1),
(16, '2026_01_28_170303_create_web_histories_table', 1),
(17, '2026_01_28_170416_create_crying_logs_table', 1),
(18, '2026_04_04_170741_create_child_apps_table', 2),
(19, '2026_05_02_190531_add_category_to_app_requests_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `notification_settings`
--

CREATE TABLE `notification_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `parent_id` bigint(20) UNSIGNED NOT NULL,
  `push_screen_time` tinyint(1) NOT NULL DEFAULT 1,
  `push_new_app` tinyint(1) NOT NULL DEFAULT 1,
  `push_content_blocked` tinyint(1) NOT NULL DEFAULT 1,
  `push_location_alerts` tinyint(1) NOT NULL DEFAULT 1,
  `push_pairing_success` tinyint(1) NOT NULL DEFAULT 1,
  `push_crying_detected` tinyint(1) NOT NULL DEFAULT 1,
  `push_threat_blocked` tinyint(1) NOT NULL DEFAULT 1,
  `push_critical_alerts` tinyint(1) NOT NULL DEFAULT 1,
  `push_report_frequency` enum('daily','weekly','monthly') NOT NULL DEFAULT 'weekly',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pairing_sessions`
--

CREATE TABLE `pairing_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `parent_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('pending','completed','expired') NOT NULL DEFAULT 'pending',
  `child_id` bigint(20) UNSIGNED DEFAULT NULL,
  `device_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`device_info`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pairing_sessions`
--

INSERT INTO `pairing_sessions` (`id`, `uuid`, `parent_id`, `code`, `expires_at`, `status`, `child_id`, `device_info`, `created_at`, `updated_at`) VALUES
(15, '82e32d1a-4098-406c-8f1e-9174d2e7a438', 3, '224129', '2026-04-04 20:04:12', 'completed', 8, NULL, '2026-04-04 20:01:21', '2026-04-04 18:04:12'),
(16, '731e484e-3a86-4812-8d47-57849b4e3d9e', 3, '747716', '2026-03-30 21:24:51', 'completed', 8, NULL, '2026-03-30 19:24:18', '2026-03-30 19:24:51'),
(17, '8b95b6ce-7e31-4bd7-9237-decd1fc7b829', 3, '711515', '2026-05-07 22:21:06', 'completed', 100, NULL, '2026-04-16 21:20:46', '2026-05-07 19:21:06'),
(18, '0e07c048-071b-44b0-a6a5-04702121cfdb', 3, '996546', '2026-04-04 15:35:21', 'pending', 4, NULL, '2026-04-04 15:25:23', '2026-04-04 15:25:23'),
(19, 'c2ba7732-4448-4ce5-99d4-14820b4569ed', 3, '971836', '2026-04-11 19:16:35', 'completed', 101, NULL, '2026-04-11 17:13:48', '2026-04-11 17:16:35'),
(20, '7360eb5c-03cb-4399-806e-3ef05228d888', 3, '538024', '2026-04-27 18:34:42', 'pending', 100, NULL, '2026-04-27 18:24:42', '2026-04-27 18:24:42'),
(21, 'ef606e0f-0262-48f8-8a16-f6049fe7cabd', 3, '810147', '2026-05-02 10:37:31', 'completed', 102, NULL, '2026-05-02 07:35:21', '2026-05-02 07:37:31');

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`id`, `uuid`, `name`, `email`, `password`, `phone`, `profile_image`, `is_active`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, '5557c887-036c-4470-afcd-0946f0512b65', 'amira', 'amira@amira.com', '$2y$12$BLGFBFhduNwhPOVeTAKHxuiJNE/oRBovIylL77erXAjnYLdH7B8b6', NULL, NULL, 1, NULL, '2026-03-30 18:08:09', '2026-03-30 18:08:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rules`
--

CREATE TABLE `rules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `child_id` bigint(20) UNSIGNED NOT NULL,
  `daily_screen_limit` int(11) NOT NULL DEFAULT 120,
  `bedtime_start` time NOT NULL DEFAULT '21:00:00',
  `bedtime_end` time NOT NULL DEFAULT '07:00:00',
  `blocked_apps` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`blocked_apps`)),
  `blocked_websites` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`blocked_websites`)),
  `location_tracking` tinyint(1) NOT NULL DEFAULT 1,
  `location_update_interval` int(11) NOT NULL DEFAULT 5,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uuid`, `name`, `email`, `password`, `phone`, `is_active`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(100, 'f4e6e49d-9130-4770-93c0-02ba5c3ab877', 'toty', 'to@to.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 0, NULL, 'jHafYPP65cPab2MDwbUlH7svi7iofB8phiotq1bp5mldXi1se6h6W3WEmvoE', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `web_histories`
--

CREATE TABLE `web_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `child_id` bigint(20) UNSIGNED NOT NULL,
  `url` varchar(255) NOT NULL,
  `domain` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `category` enum('social','educational','entertainment','shopping','unknown') NOT NULL DEFAULT 'unknown',
  `is_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `visited_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alerts_uuid_unique` (`uuid`),
  ADD KEY `alerts_parent_id_foreign` (`parent_id`),
  ADD KEY `alerts_child_id_foreign` (`child_id`);

--
-- Indexes for table `app_requests`
--
ALTER TABLE `app_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `app_requests_uuid_unique` (`uuid`),
  ADD KEY `app_requests_child_id_foreign` (`child_id`);

--
-- Indexes for table `app_usages`
--
ALTER TABLE `app_usages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `app_usages_uuid_unique` (`uuid`),
  ADD KEY `app_usages_child_id_usage_date_index` (`child_id`,`usage_date`);

--
-- Indexes for table `childrens`
--
ALTER TABLE `childrens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `childrens_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `childrens_email_unique` (`email`),
  ADD KEY `childrens_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `child_apps`
--
ALTER TABLE `child_apps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crying_logs`
--
ALTER TABLE `crying_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `crying_logs_uuid_unique` (`uuid`),
  ADD KEY `crying_logs_child_id_foreign` (`child_id`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `devices_uuid_unique` (`uuid`),
  ADD KEY `devices_parent_id_foreign` (`parent_id`),
  ADD KEY `devices_child_id_foreign` (`child_id`);

--
-- Indexes for table `downtimes`
--
ALTER TABLE `downtimes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `downtimes_uuid_unique` (`uuid`),
  ADD KEY `downtimes_rule_id_foreign` (`child_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `locations_uuid_unique` (`uuid`),
  ADD KEY `locations_child_id_foreign` (`child_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_settings`
--
ALTER TABLE `notification_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `notification_settings_uuid_unique` (`uuid`),
  ADD KEY `notification_settings_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `pairing_sessions`
--
ALTER TABLE `pairing_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pairing_sessions_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `pairing_sessions_code_unique` (`code`),
  ADD KEY `pairing_sessions_parent_id_foreign` (`parent_id`),
  ADD KEY `pairing_sessions_child_id_foreign` (`child_id`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `parents_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `parents_email_unique` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `rules`
--
ALTER TABLE `rules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rules_uuid_unique` (`uuid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `web_histories`
--
ALTER TABLE `web_histories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `web_histories_uuid_unique` (`uuid`),
  ADD KEY `web_histories_child_id_foreign` (`child_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `app_requests`
--
ALTER TABLE `app_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `app_usages`
--
ALTER TABLE `app_usages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `childrens`
--
ALTER TABLE `childrens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `child_apps`
--
ALTER TABLE `child_apps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=278;

--
-- AUTO_INCREMENT for table `crying_logs`
--
ALTER TABLE `crying_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `downtimes`
--
ALTER TABLE `downtimes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `notification_settings`
--
ALTER TABLE `notification_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pairing_sessions`
--
ALTER TABLE `pairing_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rules`
--
ALTER TABLE `rules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `web_histories`
--
ALTER TABLE `web_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alerts`
--
ALTER TABLE `alerts`
  ADD CONSTRAINT `alerts_child_id_foreign` FOREIGN KEY (`child_id`) REFERENCES `childrens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `alerts_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `app_requests`
--
ALTER TABLE `app_requests`
  ADD CONSTRAINT `app_requests_child_id_foreign` FOREIGN KEY (`child_id`) REFERENCES `childrens` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `app_usages`
--
ALTER TABLE `app_usages`
  ADD CONSTRAINT `app_usages_child_id_foreign` FOREIGN KEY (`child_id`) REFERENCES `childrens` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `childrens`
--
ALTER TABLE `childrens`
  ADD CONSTRAINT `childrens_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `crying_logs`
--
ALTER TABLE `crying_logs`
  ADD CONSTRAINT `crying_logs_child_id_foreign` FOREIGN KEY (`child_id`) REFERENCES `childrens` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `devices`
--
ALTER TABLE `devices`
  ADD CONSTRAINT `devices_child_id_foreign` FOREIGN KEY (`child_id`) REFERENCES `childrens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `devices_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `downtimes`
--
ALTER TABLE `downtimes`
  ADD CONSTRAINT `downtimes_rule_id_foreign` FOREIGN KEY (`child_id`) REFERENCES `childrens` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `locations`
--
ALTER TABLE `locations`
  ADD CONSTRAINT `locations_child_id_foreign` FOREIGN KEY (`child_id`) REFERENCES `childrens` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notification_settings`
--
ALTER TABLE `notification_settings`
  ADD CONSTRAINT `notification_settings_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pairing_sessions`
--
ALTER TABLE `pairing_sessions`
  ADD CONSTRAINT `pairing_sessions_child_id_foreign` FOREIGN KEY (`child_id`) REFERENCES `childrens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pairing_sessions_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rules`
--
ALTER TABLE `rules`
  ADD CONSTRAINT `rules_child_id_foreign` FOREIGN KEY (`child_id`) REFERENCES `childrens` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `web_histories`
--
ALTER TABLE `web_histories`
  ADD CONSTRAINT `web_histories_child_id_foreign` FOREIGN KEY (`child_id`) REFERENCES `childrens` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
