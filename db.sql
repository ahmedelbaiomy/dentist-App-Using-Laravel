-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 17, 2021 at 01:47 AM
-- Server version: 5.7.26
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `db_dentiziner`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) UNSIGNED NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `duration` int(11) NOT NULL,
  `comments` longtext,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '1:booked, 2:confirmed, 3:canceled, 4:attended',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `start_time`, `duration`, `comments`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 8, '2021-04-11 09:00:00', 60, 'nnn', 1, '2021-04-10 20:39:35', '2021-04-10 20:39:35'),
(2, 2, 2, '2021-04-20 10:00:00', 60, NULL, 1, '2021-04-16 23:30:33', '2021-04-10 20:40:25'),
(3, 1, 2, '2021-04-12 09:00:00', 60, NULL, 1, '2021-04-10 20:41:02', '2021-04-10 20:41:02'),
(4, 1, 5, '2021-04-11 17:00:00', 60, NULL, 1, '2021-04-10 20:41:30', '2021-04-10 20:41:30'),
(5, 2, 2, '2021-04-20 11:30:00', 60, NULL, 1, '2021-04-16 23:31:33', '2021-04-10 20:40:25'),
(6, 2, 2, '2021-04-20 12:30:00', 60, NULL, 1, '2021-04-16 23:32:00', '2021-04-10 20:40:25'),
(7, 1, 2, '2021-04-20 13:00:00', 15, 'this is a test', 2, '2021-04-17 01:39:56', '2021-04-17 04:39:56');

-- --------------------------------------------------------

--
-- Table structure for table `clinics`
--

CREATE TABLE `clinics` (
  `id` int(11) UNSIGNED NOT NULL,
  `year` int(20) NOT NULL,
  `jan` decimal(15,2) NOT NULL,
  `feb` decimal(15,2) NOT NULL,
  `mar` decimal(15,2) NOT NULL,
  `apr` decimal(15,2) NOT NULL,
  `may` decimal(15,2) NOT NULL,
  `jun` decimal(15,2) NOT NULL,
  `jul` decimal(15,2) NOT NULL,
  `aug` decimal(15,2) NOT NULL,
  `sep` decimal(15,2) NOT NULL,
  `oct` decimal(15,2) NOT NULL,
  `nov` decimal(15,2) NOT NULL,
  `dec` decimal(15,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `clinics`
--

INSERT INTO `clinics` (`id`, `year`, `jan`, `feb`, `mar`, `apr`, `may`, `jun`, `jul`, `aug`, `sep`, `oct`, `nov`, `dec`, `created_at`, `updated_at`) VALUES
(2, 2021, '100.00', '110.00', '120.00', '130.00', '140.00', '150.00', '160.00', '170.00', '180.00', '190.00', '200.00', '300.00', '2021-02-23 10:05:40', '2021-02-23 10:05:40'),
(5, 2022, '200.00', '210.00', '220.00', '230.00', '240.00', '250.00', '260.00', '270.00', '280.00', '290.00', '300.00', '310.00', '2021-02-22 08:16:45', '2021-02-22 08:16:45'),
(7, 2023, '310.00', '320.00', '110.00', '120.00', '120.00', '140.00', '150.00', '130.00', '160.00', '170.00', '123.00', '222.00', '2021-02-22 11:53:28', '2021-02-22 11:53:28');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `birthday` date DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `photo` blob,
  `target` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `user_id`, `birthday`, `address`, `phone`, `photo`, `target`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, NULL, NULL, NULL, '1000.00', '2021-02-13 03:38:22', '2021-02-13 00:38:22'),
(2, 5, NULL, NULL, NULL, NULL, '800.00', '2021-02-13 03:39:10', NULL),
(3, 8, NULL, NULL, NULL, NULL, '900.00', '2021-02-22 01:45:48', NULL),
(4, 6, NULL, NULL, NULL, NULL, '1200.00', '2021-02-22 01:45:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `doctor_schedules`
--

CREATE TABLE `doctor_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `day` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slot` timestamp NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctor_schedules`
--

INSERT INTO `doctor_schedules` (`id`, `day`, `slot`, `doctor_id`, `created_at`, `updated_at`) VALUES
(10, 'MONDAY', '2021-04-16 11:15:00', 2, '2021-04-16 20:42:22', '2021-04-16 20:42:22'),
(11, 'MONDAY', '2021-04-16 11:30:00', 2, '2021-04-16 20:42:22', '2021-04-16 20:42:22'),
(12, 'MONDAY', '2021-04-16 11:45:00', 2, '2021-04-16 20:42:22', '2021-04-16 20:42:22'),
(13, 'MONDAY', '2021-04-16 12:00:00', 2, '2021-04-16 20:42:22', '2021-04-16 20:42:22'),
(14, 'MONDAY', '2021-04-16 12:15:00', 2, '2021-04-16 20:42:22', '2021-04-16 20:42:22'),
(15, 'MONDAY', '2021-04-16 12:30:00', 2, '2021-04-16 20:42:22', '2021-04-16 20:42:22'),
(16, 'MONDAY', '2021-04-16 12:45:00', 2, '2021-04-16 20:42:22', '2021-04-16 20:42:22'),
(17, 'MONDAY', '2021-04-16 13:00:00', 2, '2021-04-16 20:42:22', '2021-04-16 20:42:22'),
(18, 'MONDAY', '2021-04-16 13:15:00', 2, '2021-04-16 20:42:22', '2021-04-16 20:42:22'),
(19, 'MONDAY', '2021-04-16 13:30:00', 2, '2021-04-16 20:42:22', '2021-04-16 20:42:22'),
(20, 'MONDAY', '2021-04-16 13:45:00', 2, '2021-04-16 20:42:22', '2021-04-16 20:42:22'),
(21, 'MONDAY', '2021-04-16 14:00:00', 2, '2021-04-16 20:42:22', '2021-04-16 20:42:22'),
(22, 'TUESDAY', '2021-04-16 09:00:00', 2, '2021-04-16 21:28:12', '2021-04-16 21:28:12'),
(23, 'TUESDAY', '2021-04-16 09:30:00', 2, '2021-04-16 21:28:12', '2021-04-16 21:28:12'),
(24, 'TUESDAY', '2021-04-16 10:00:00', 2, '2021-04-16 21:28:12', '2021-04-16 21:28:12'),
(25, 'TUESDAY', '2021-04-16 10:30:00', 2, '2021-04-16 21:28:12', '2021-04-16 21:28:12'),
(26, 'TUESDAY', '2021-04-16 11:00:00', 2, '2021-04-16 21:28:12', '2021-04-16 21:28:12'),
(27, 'TUESDAY', '2021-04-16 11:30:00', 2, '2021-04-16 21:28:12', '2021-04-16 21:28:12'),
(28, 'TUESDAY', '2021-04-16 12:00:00', 2, '2021-04-16 21:28:12', '2021-04-16 21:28:12'),
(29, 'TUESDAY', '2021-04-16 12:30:00', 2, '2021-04-16 21:28:12', '2021-04-16 21:28:12'),
(30, 'TUESDAY', '2021-04-16 13:00:00', 2, '2021-04-16 21:28:12', '2021-04-16 21:28:12');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoicelists`
--

CREATE TABLE `invoicelists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `paid` tinyint(4) NOT NULL DEFAULT '0',
  `total` float DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `code`, `from`, `to`, `paid`, `total`, `created_at`, `updated_at`) VALUES
(1, '1618074703181', 8, 1, 0, 17, '2021-04-10 18:15:33', '2021-04-10 18:15:33'),
(2, '1618082566581', 2, 1, 0, 350, '2021-04-10 20:23:06', '2021-04-10 20:23:06');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_lists`
--

CREATE TABLE `invoice_lists` (
  `id` int(11) UNSIGNED NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `invoice_code` varchar(255) NOT NULL,
  `teeth_id` int(11) NOT NULL,
  `service` varchar(100) NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `invoice_lists`
--

INSERT INTO `invoice_lists` (`id`, `invoice_id`, `invoice_code`, `teeth_id`, `service`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, '1618074703181', 0, 'asdfdf', '123.00', '2021-04-10 18:15:33', '2021-04-10 18:15:33'),
(2, 1, '1618074703181', 0, 'sdfsdf', '123.00', '2021-04-10 18:15:33', '2021-04-10 18:15:33'),
(3, 2, '1618082566581', 0, 'AAA', '200.00', '2021-04-10 20:23:06', '2021-04-10 20:23:06'),
(4, 2, '1618082566581', 0, 'aaa', '150.00', '2021-04-10 20:23:06', '2021-04-10 20:23:06');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(0, '2021_04_15_081244_create_doctor_schedules_table', 2),
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `note` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `user_id`, `patient_id`, `note`, `created_at`, `updated_at`) VALUES
(8, 2, 1, 'mzbczcm', '2021-04-10 19:45:53', '2021-04-10 19:45:53');

-- --------------------------------------------------------

--
-- Table structure for table `officetimes`
--

CREATE TABLE `officetimes` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `day` int(11) NOT NULL COMMENT '0:sunday, 6:saturday',
  `from` int(11) NOT NULL,
  `to` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `officetimes`
--

INSERT INTO `officetimes` (`id`, `user_id`, `day`, `from`, `to`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 9, 12, '2021-03-08 17:14:34', '2021-03-08 17:30:16'),
(2, 8, 0, 9, 17, '2021-03-08 17:18:52', '2021-03-08 17:29:51'),
(3, 6, 1, 12, 17, '2021-03-08 17:30:35', '2021-03-08 17:30:35'),
(4, 2, 2, 13, 17, '2021-03-08 17:30:59', '2021-03-08 17:30:59'),
(6, 6, 2, 8, 13, '2021-03-08 17:32:41', '2021-03-09 03:20:55'),
(7, 8, 2, 9, 15, '2021-03-09 04:03:49', '2021-03-09 04:03:49'),
(8, 5, 2, 17, 23, '2021-03-09 13:26:35', '2021-03-09 13:26:35'),
(9, 2, 4, 5, 12, '2021-03-11 01:02:43', '2021-03-11 01:02:43'),
(10, 8, 4, 5, 13, '2021-03-11 01:09:41', '2021-03-11 01:09:41'),
(11, 5, 6, 11, 22, '2021-03-12 15:24:01', '2021-03-12 15:24:01');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patientprofiles`
--

CREATE TABLE `patientprofiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `birthday` date DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `state` tinyint(4) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `name`, `email`, `birthday`, `address`, `phone`, `state`, `created_at`, `updated_at`) VALUES
(1, 'Patient 111', 'patient111@gmail.com', '2020-02-04', 'test address1', '1234567890', 0, '2021-03-08 22:40:16', '2021-03-15 07:13:31'),
(2, 'Patient 222', 'patient222@gmail.com', '2021-03-08', 'test address2', '123456987', 0, '2021-03-08 22:41:27', '2021-03-08 22:41:27');

-- --------------------------------------------------------

--
-- Table structure for table `patient_notes`
--

CREATE TABLE `patient_notes` (
  `id` int(11) UNSIGNED NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `teeth_id` int(11) NOT NULL,
  `note` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'existing',
  `invoiced` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patient_notes`
--

INSERT INTO `patient_notes` (`id`, `patient_id`, `doctor_id`, `category_id`, `teeth_id`, `note`, `type`, `invoiced`, `created_at`, `updated_at`) VALUES
(1, 1, 8, 12, 22, 'AAAA', 'completed', 1, '2021-03-11 06:37:25', '2021-03-11 06:37:25'),
(2, 1, 2, 10, 16, 'm,nv nvfn', 'completed', 1, '2021-03-16 00:40:47', '2021-03-16 00:40:47'),
(3, 1, 2, 2, 16, 'nnnn00666', 'completed', 1, '2021-03-16 00:48:10', '2021-03-16 01:03:27'),
(4, 1, 2, 3, 14, ',n,n0000', 'completed', 1, '2021-03-16 01:02:27', '2021-03-16 01:03:11'),
(5, 1, 2, 5, 45, 'nmbm', 'completed', 1, '2021-03-18 01:49:47', '2021-03-18 01:49:47');

-- --------------------------------------------------------

--
-- Table structure for table `patient_storage`
--

CREATE TABLE `patient_storage` (
  `id` int(11) UNSIGNED NOT NULL,
  `patient_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `url` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `scategories`
--

CREATE TABLE `scategories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) UNSIGNED NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `note` longtext,
  `category_id` int(11) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service_name`, `price`, `note`, `category_id`, `created_at`, `updated_at`) VALUES
(3, 'AAA', '200.00', 'Hello', 10, '2021-02-23 10:12:48', '2021-02-23 10:12:48'),
(4, 'aaa', '150.00', 'sadf', 2, '2021-02-23 10:47:11', '2021-02-23 10:47:11'),
(5, 'safsdf', '123.00', 'sdf', 3, '2021-02-23 10:47:23', '2021-02-23 10:47:23'),
(6, 'sadfsdf', '545.00', '12312312312', 6, '2021-02-23 10:47:36', '2021-02-23 10:47:36'),
(7, 'asdfdf', '123.00', '123', 10, '2021-02-23 10:54:11', '2021-02-23 10:54:11'),
(10, 'sdfsdf', '123.00', '23432', 12, '2021-02-26 10:06:19', '2021-02-23 10:57:04'),
(13, 'newjjj', '10.00', ',m ,m ,m', 2, '2021-03-15 02:50:59', '2021-03-15 02:50:59'),
(14, 'mmm', '10.00', 'm,n ,', 15, '2021-03-15 02:51:44', '2021-03-15 02:51:44');

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `parent_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`id`, `parent_id`, `name`, `created_at`, `updated_at`) VALUES
(2, '#', 'BBB', '2021-02-21 14:32:56', NULL),
(3, '#', 'New node', '2021-02-21 15:02:15', NULL),
(4, '#', 'DDD', '2021-02-21 14:32:58', NULL),
(5, '3', 'New node', '2021-02-21 14:33:51', NULL),
(6, '3', 'New node', '2021-02-21 14:38:22', NULL),
(7, '3', 'rrr', '2021-02-21 14:38:52', NULL),
(8, '4', 'New node', '2021-02-21 14:40:40', NULL),
(9, '4', 'New node', '2021-02-21 14:41:04', NULL),
(10, '2', 'JJJ', '2021-02-21 14:45:51', NULL),
(11, '3', 'New node', '2021-02-21 14:53:06', NULL),
(12, '#', 'KKK', '2021-02-23 10:29:09', NULL),
(13, '#', 'KKK', '2021-02-23 10:29:17', NULL),
(15, '2', 'newjjj', '2021-03-15 02:51:00', '2021-03-15 02:51:00'),
(16, '15', 'mmm', '2021-03-15 02:51:44', '2021-03-15 02:51:44');

-- --------------------------------------------------------

--
-- Table structure for table `storages`
--

CREATE TABLE `storages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'none',
  `state` int(11) DEFAULT '0' COMMENT '0:pending,1:verified,2:suspend',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `user_type`, `state`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', 'admin@gmail.com', NULL, '$2y$10$0EXHkaz0gHtck1ajmzPsyOWJoaSyh5WITfT39eq6gl3/Cdlh1wD.e', 'admin', 1, NULL, '2021-02-11 02:46:17', '2021-03-13 11:10:51'),
(2, 'Doctor Kevin', 'admin1', 'doctor1@gmail.com', NULL, '$2y$10$0EXHkaz0gHtck1ajmzPsyOWJoaSyh5WITfT39eq6gl3/Cdlh1wD.e', 'doctor', 1, NULL, '2021-02-11 03:29:29', '2021-02-14 07:42:28'),
(3, 'Reception Dol', 'admin2', 'reception1@gmail.com', NULL, '$2y$10$0EXHkaz0gHtck1ajmzPsyOWJoaSyh5WITfT39eq6gl3/Cdlh1wD.e', 'reception', 1, NULL, '2021-02-11 03:55:47', '2021-02-12 10:31:03'),
(5, 'Other Dol', 'admin3', 'other@gmail.com', NULL, '$2y$10$0EXHkaz0gHtck1ajmzPsyOWJoaSyh5WITfT39eq6gl3/Cdlh1wD.e', 'doctor', 1, NULL, '2021-02-12 10:32:25', '2021-02-14 07:42:34'),
(6, 'Other2 Dol', 'admin4', 'other2@gmail.com', NULL, '$2y$10$0EXHkaz0gHtck1ajmzPsyOWJoaSyh5WITfT39eq6gl3/Cdlh1wD.e', 'doctor', 1, NULL, '2021-02-12 10:34:58', '2021-02-22 01:45:29'),
(7, 'Other3 De', 'admin5', 'other3@gmail.com', NULL, '$2y$10$0EXHkaz0gHtck1ajmzPsyOWJoaSyh5WITfT39eq6gl3/Cdlh1wD.e', 'reception', 0, NULL, '2021-02-12 10:41:49', '2021-02-12 11:11:41'),
(8, 'Doctor Dol', 'admin6', 'doctor2@gmail.com', NULL, '$2y$10$0EXHkaz0gHtck1ajmzPsyOWJoaSyh5WITfT39eq6gl3/Cdlh1wD.e', 'doctor', 1, NULL, '2021-02-15 07:08:44', '2021-02-22 01:45:20'),
(9, 'mohamed25', 'admin7', 'mohamedabdelateef25@gmail.com', NULL, '$2y$10$0EXHkaz0gHtck1ajmzPsyOWJoaSyh5WITfT39eq6gl3/Cdlh1wD.e', 'admin', 1, NULL, '2021-03-13 09:39:45', '2021-03-13 09:39:45'),
(10, 'mohamed25', 'admin25', NULL, NULL, '$2y$10$FKVcrbMnRWkDPwmceqflx.OgWMSdpDNufyOAiJBJswys75lczVg56', 'admin', 1, NULL, '2021-03-15 03:26:08', '2021-03-15 03:26:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clinics`
--
ALTER TABLE `clinics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `year` (`year`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoicelists`
--
ALTER TABLE `invoicelists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_lists`
--
ALTER TABLE `invoice_lists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `officetimes`
--
ALTER TABLE `officetimes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`(191));

--
-- Indexes for table `patientprofiles`
--
ALTER TABLE `patientprofiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient_notes`
--
ALTER TABLE `patient_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient_storage`
--
ALTER TABLE `patient_storage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scategories`
--
ALTER TABLE `scategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `storages`
--
ALTER TABLE `storages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `clinics`
--
ALTER TABLE `clinics`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoicelists`
--
ALTER TABLE `invoicelists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `invoice_lists`
--
ALTER TABLE `invoice_lists`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `officetimes`
--
ALTER TABLE `officetimes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `patientprofiles`
--
ALTER TABLE `patientprofiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `patient_notes`
--
ALTER TABLE `patient_notes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `patient_storage`
--
ALTER TABLE `patient_storage`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scategories`
--
ALTER TABLE `scategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `storages`
--
ALTER TABLE `storages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
