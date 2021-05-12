-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 12, 2021 at 03:32 PM
-- Server version: 5.7.26
-- PHP Version: 7.3.9

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
(4, 1, 13, '2021-04-11 17:00:00', 60, NULL, 1, '2021-04-24 12:44:28', '2021-04-10 20:41:30'),
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
  `id` bigint(20) UNSIGNED NOT NULL,
  `birthday` date DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target` decimal(15,2) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `birthday`, `address`, `phone`, `photo`, `target`, `user_id`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, NULL, NULL, '1000.00', 2, '2021-02-13 02:38:22', '2021-02-12 23:38:22'),
(3, NULL, NULL, NULL, NULL, '800.00', 8, '2021-04-19 17:18:22', '2021-04-19 20:18:22'),
(4, NULL, NULL, NULL, NULL, '1200.00', 6, '2021-02-22 00:45:56', '2021-05-11 00:09:20');

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
-- Table structure for table `help_indexes`
--

CREATE TABLE `help_indexes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `index` int(11) NOT NULL,
  `type` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `index_date` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `help_indexes`
--

INSERT INTO `help_indexes` (`id`, `index`, `type`, `index_date`, `created_at`, `updated_at`) VALUES
(1, 10, 'INVOICE', '2021-05-10 15:29:38', '2021-05-10 15:29:38', '2021-05-12 03:28:30');

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
(1, '1618074703181', 8, 1, 1, 17, '2021-05-07 00:03:34', '2021-04-10 18:15:33'),
(2, '1618082566581', 2, 1, 0, 350, '2021-04-10 20:23:06', '2021-04-10 20:23:06'),
(3, '1619274723', 3, 1, 0, 0, '2021-04-24 17:32:03', '2021-04-24 17:32:03'),
(4, '1619274916', 3, 1, 0, 0, '2021-04-24 17:35:16', '2021-04-24 17:35:16'),
(5, '1619275619', 3, 1, 0, 0, '2021-04-24 17:46:59', '2021-04-24 17:46:59'),
(6, '1619275627', 3, 1, 0, 0, '2021-04-24 17:47:07', '2021-04-24 17:47:07'),
(7, '1619275705', 3, 1, 0, 0, '2021-04-24 17:48:25', '2021-04-24 17:48:25'),
(8, '1619279568', 3, 3, 0, 0, '2021-04-24 18:52:48', '2021-04-24 18:52:48'),
(9, '1619279573', 3, 3, 0, 0, '2021-04-24 18:52:53', '2021-04-24 18:52:53'),
(10, '1620233930', 3, 3, 0, 0, '2021-05-05 19:58:50', '2021-05-05 19:58:50'),
(11, '1620345206', 2, 2, 1, 0, '2021-05-06 23:54:11', '2021-05-07 02:53:26'),
(12, '1620345302', 3, 2, 0, 0, '2021-05-07 02:55:02', '2021-05-07 02:55:02'),
(13, '1620345653', 3, 1, 0, 0, '2021-05-07 03:00:53', '2021-05-07 03:00:53'),
(14, '1620345781', 3, 1, 0, 0, '2021-05-07 03:03:01', '2021-05-07 03:03:01'),
(15, '1620345950', 3, 1, 0, 0, '2021-05-07 03:05:50', '2021-05-07 03:05:50'),
(16, '1620347473', 2, 1, 0, 150, '2021-05-07 00:31:13', '2021-05-07 03:31:13');

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
(4, 2, '1618082566581', 0, 'aaa', '150.00', '2021-04-10 20:23:06', '2021-04-10 20:23:06'),
(5, 16, '1620347473', 17, 'BBB', '150.00', '2021-05-07 03:31:13', '2021-05-07 03:31:13');

-- --------------------------------------------------------

--
-- Table structure for table `inv_invoices`
--

CREATE TABLE `inv_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `number` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `bill_date` date NOT NULL,
  `due_date` date NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `tax_percentage` double(8,2) DEFAULT NULL,
  `discount_amount` double(8,2) DEFAULT NULL,
  `discount_amount_type` enum('percentage','fixed_amount') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_type` enum('before_tax','after_tax') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'before_tax',
  `status` enum('draft','not_paid','partial_paid','paid','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancelled_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inv_invoices`
--

INSERT INTO `inv_invoices` (`id`, `number`, `doctor_id`, `patient_id`, `bill_date`, `due_date`, `note`, `tax_percentage`, `discount_amount`, `discount_amount_type`, `discount_type`, `status`, `cancelled_at`, `cancelled_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '2021050008', 2, 3, '2021-05-10', '2021-06-10', 'this is a test', 20.00, 20.00, 'percentage', 'before_tax', 'partial_paid', NULL, NULL, NULL, '2021-05-10 19:01:03', '2021-05-11 20:47:39'),
(2, '2021050009', 2, 3, '2021-05-11', '2021-06-11', 'this is a test for doctor kevin', 5.00, 10.00, 'percentage', 'before_tax', 'paid', NULL, NULL, NULL, '2021-05-11 03:24:03', '2021-05-11 20:33:00'),
(3, '2021050010', 2, 3, '2021-05-13', '2021-06-10', 'note ....', 10.00, 100.00, 'fixed_amount', 'before_tax', 'paid', NULL, NULL, NULL, '2021-05-12 03:28:30', '2021-05-12 03:39:28');

-- --------------------------------------------------------

--
-- Table structure for table `inv_invoice_payments`
--

CREATE TABLE `inv_invoice_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `amount` double(8,2) NOT NULL,
  `payment_date` timestamp NULL DEFAULT NULL,
  `payment_method` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inv_invoice_payments`
--

INSERT INTO `inv_invoice_payments` (`id`, `amount`, `payment_date`, `payment_method`, `note`, `invoice_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(2, 30.00, '2021-05-11 20:27:36', 'Cash', NULL, 2, NULL, '2021-05-11 20:27:36', '2021-05-11 20:27:36'),
(3, 159.00, '2021-05-11 20:33:00', 'Cash', 'total pay', 2, NULL, '2021-05-11 20:33:00', '2021-05-11 20:33:00'),
(4, 500.30, '2021-05-11 20:46:39', 'Cash', NULL, 1, NULL, '2021-05-11 20:46:39', '2021-05-11 20:46:39'),
(5, 35.70, '2021-05-11 20:47:39', 'Cash', NULL, 1, NULL, '2021-05-11 20:47:39', '2021-05-11 20:47:39'),
(6, 500.00, '2021-05-12 03:36:50', 'Cash', 'part 1', 3, NULL, '2021-05-12 03:36:50', '2021-05-12 03:36:50'),
(7, 200.00, '2021-05-12 03:37:48', 'Cash', 'part 2', 3, NULL, '2021-05-12 03:37:48', '2021-05-12 03:37:48'),
(8, 235.00, '2021-05-12 03:39:27', 'Cash', 'last part', 3, NULL, '2021-05-12 03:39:27', '2021-05-12 03:39:27');

-- --------------------------------------------------------

--
-- Table structure for table `inv_invoice_refunds`
--

CREATE TABLE `inv_invoice_refunds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `refund_code` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double(8,2) NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `refund_date` date DEFAULT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inv_invoice_refunds`
--

INSERT INTO `inv_invoice_refunds` (`id`, `refund_code`, `amount`, `reason`, `refund_date`, `invoice_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'RF0001', 200.00, 'test refund ...', '2021-05-12', 1, NULL, '2021-05-12 01:35:46', '2021-05-12 01:35:46'),
(2, 'RF0002', 135.00, 'litige client ..', '2021-05-12', 3, NULL, '2021-05-12 03:41:02', '2021-05-12 03:41:02');

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
(3, '2021_04_15_081244_create_doctor_schedules_table', 1),
(4, '2021_05_07_193312_create_service_categories_table', 2),
(5, '2021_05_07_193325_create_services_table', 2),
(44, '2021_05_10_182728_create_help_indexes_table', 6),
(45, '2021_05_07_213246_create_inv_invoices_table', 7),
(46, '2021_05_08_195857_create_procedure_service_items_table', 7),
(47, '2021_05_08_213315_create_inv_invoice_payments_table', 7),
(48, '2021_05_08_213333_create_inv_invoice_refunds_table', 7),
(49, '2021_05_11_030144_create_doctors_table', 8),
(50, '2021_05_09_185255_create_tooths_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `note` longtext NOT NULL,
  `audio_file` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `user_id`, `patient_id`, `note`, `audio_file`, `created_at`, `updated_at`) VALUES
(11, 3, 1, 'test Mouhssin', 'dXBsb2Fkcy8=', '2021-05-01 20:45:26', '2021-05-01 20:45:26'),
(12, 3, 1, 'this is a test from scratch', NULL, '2021-05-01 21:08:52', '2021-05-01 21:08:52');

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
(3, 6, 1, 12, 15, '2021-03-08 17:30:35', '2021-04-19 20:31:45'),
(4, 2, 2, 13, 17, '2021-03-08 17:30:59', '2021-03-08 17:30:59'),
(6, 6, 2, 8, 13, '2021-03-08 17:32:41', '2021-03-09 03:20:55'),
(7, 8, 2, 9, 15, '2021-03-09 04:03:49', '2021-03-09 04:03:49'),
(8, 5, 2, 17, 23, '2021-03-09 13:26:35', '2021-03-09 13:26:35'),
(9, 2, 4, 5, 12, '2021-03-11 01:02:43', '2021-03-11 01:02:43'),
(10, 8, 4, 7, 13, '2021-03-11 01:09:41', '2021-04-19 20:27:58'),
(11, 5, 6, 11, 22, '2021-03-12 15:24:01', '2021-03-12 15:24:01'),
(12, 5, 2, 1, 2, '2021-04-19 20:29:30', '2021-04-19 20:29:30'),
(13, 2, 1, 5, 10, '2021-04-19 20:30:11', '2021-04-19 20:30:11'),
(14, 2, 0, 2, 6, '2021-04-19 20:31:24', '2021-04-19 20:31:24');

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
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `ar_name` varchar(255) DEFAULT NULL,
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

INSERT INTO `patients` (`id`, `name`, `ar_name`, `email`, `birthday`, `address`, `phone`, `state`, `created_at`, `updated_at`) VALUES
(1, 'Patient 111', NULL, 'patient111@gmail.com', '2021-04-27', 'test address1', '1234567890', 0, '2021-03-08 22:40:16', '2021-04-24 18:52:26'),
(2, 'Patient 222', NULL, 'patient222@gmail.com', '2021-03-08', 'test address2', '123456987', 0, '2021-03-08 22:41:27', '2021-03-08 22:41:27'),
(3, 'Mouhssin hidaoui', 'محسن', 'hidaoui.mouhssin@gmail.com', '2019-01-01', 'gjhvjvnhvhv hhvhjvh', '06666666666', 0, '2021-04-24 18:18:07', '2021-04-24 18:51:51');

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
(5, 1, 2, 5, 45, 'nmbm', 'completed', 1, '2021-03-18 01:49:47', '2021-03-18 01:49:47'),
(6, 1, 2, 2, 17, 'test Mouhssin', 'existing', 1, '2021-05-07 03:23:17', '2021-05-07 03:23:17'),
(7, 1, 2, 2, 46, 'ok test', 'existing', 0, '2021-05-07 03:26:40', '2021-05-07 03:26:40');

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

--
-- Dumping data for table `patient_storage`
--

INSERT INTO `patient_storage` (`id`, `patient_id`, `title`, `description`, `url`, `created_at`, `updated_at`) VALUES
(1, 3, 'Dental Filling', 'test', 'ZmlsZXMvZG9jcy8vMTYyMDU4MzcwNF9kaWFnLnN2Zw==', '2021-05-09 21:08:24', '2021-05-09 21:08:24');

-- --------------------------------------------------------

--
-- Table structure for table `pr_procedure_service_items`
--

CREATE TABLE `pr_procedure_service_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quantity` double(8,2) NOT NULL DEFAULT '1.00',
  `rate` double(8,2) NOT NULL,
  `total` double(8,2) NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `teeth_id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('existing','planned','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'existing',
  `invoice_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pr_procedure_service_items`
--

INSERT INTO `pr_procedure_service_items` (`id`, `quantity`, `rate`, `total`, `note`, `service_id`, `teeth_id`, `doctor_id`, `patient_id`, `type`, `invoice_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1.00, 200.00, 200.00, 'this is a test', 1, 17, 2, 3, 'completed', 1, NULL, '2021-05-10 20:49:19', '2021-05-10 20:49:49'),
(2, 1.00, 200.00, 200.00, 'another test', 1, 15, 2, 3, 'completed', 1, NULL, '2021-05-10 20:49:33', '2021-05-10 20:49:42'),
(3, 1.00, 200.00, 200.00, 'test', 1, 15, 2, 3, 'completed', 2, NULL, '2021-05-11 01:08:30', '2021-05-11 03:24:48'),
(4, 3.00, 200.00, 600.00, 'qsqsqs', 1, 21, 2, 3, 'planned', 1, NULL, '2021-05-11 01:08:42', '2021-05-11 01:20:01'),
(5, 3.00, 200.00, 600.00, 'nice', 1, 27, 2, 3, 'existing', 1, NULL, '2021-05-11 01:08:54', '2021-05-11 01:20:01'),
(8, 1.00, 480.00, 480.00, 'test', 121, 16, 6, 3, 'existing', NULL, NULL, '2021-05-11 03:12:44', '2021-05-11 03:12:44'),
(9, 1.00, 200.00, 200.00, NULL, 69, 16, 8, 3, 'existing', NULL, NULL, '2021-05-11 03:15:54', '2021-05-11 03:15:54'),
(10, 3.00, 200.00, 600.00, 'this is test', 1, 13, 2, 3, 'completed', 3, NULL, '2021-05-12 03:17:38', '2021-05-12 03:30:45'),
(11, 1.00, 100.00, 100.00, NULL, 23, 21, 2, 3, 'completed', 3, NULL, '2021-05-12 03:24:53', '2021-05-12 03:30:45'),
(12, 1.00, 250.00, 250.00, NULL, 26, 14, 2, 3, 'completed', 3, NULL, '2021-05-12 03:25:42', '2021-05-12 03:30:45');

-- --------------------------------------------------------

--
-- Table structure for table `pr_tooths`
--

CREATE TABLE `pr_tooths` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `number` int(11) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '1',
  `image` text COLLATE utf8mb4_unicode_ci,
  `type` enum('adult','child') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'adult',
  `row_number` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pr_tooths`
--

INSERT INTO `pr_tooths` (`id`, `number`, `sort`, `image`, `type`, `row_number`, `created_at`, `updated_at`) VALUES
(1, 18, 1, 'assets/tooth_images/18.svg', 'adult', 1, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(2, 17, 2, 'assets/tooth_images/17.svg', 'adult', 1, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(3, 16, 3, 'assets/tooth_images/16.svg', 'adult', 1, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(4, 15, 4, 'assets/tooth_images/15.svg', 'adult', 1, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(5, 13, 6, 'assets/tooth_images/13.svg', 'adult', 1, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(6, 12, 7, 'assets/tooth_images/12.svg', 'adult', 1, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(7, 11, 8, 'assets/tooth_images/11.svg', 'adult', 1, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(8, 21, 9, 'assets/tooth_images/11.svg', 'adult', 1, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(9, 22, 10, 'assets/tooth_images/12.svg', 'adult', 1, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(10, 23, 11, 'assets/tooth_images/13.svg', 'adult', 1, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(11, 24, 12, 'assets/tooth_images/14.svg', 'adult', 1, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(12, 25, 13, 'assets/tooth_images/15.svg', 'adult', 1, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(13, 26, 14, 'assets/tooth_images/13.svg', 'adult', 1, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(14, 27, 15, 'assets/tooth_images/14.svg', 'adult', 1, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(15, 28, 16, 'assets/tooth_images/15.svg', 'adult', 1, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(16, 14, 5, 'assets/tooth_images/14.svg', 'adult', 1, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(17, 48, 1, 'assets/tooth_images/48.svg', 'adult', 2, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(18, 47, 2, 'assets/tooth_images/47.svg', 'adult', 2, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(19, 46, 3, 'assets/tooth_images/46.svg', 'adult', 2, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(20, 45, 4, 'assets/tooth_images/45.svg', 'adult', 2, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(21, 44, 5, 'assets/tooth_images/44.svg', 'adult', 2, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(22, 43, 6, 'assets/tooth_images/43.svg', 'adult', 2, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(23, 42, 7, 'assets/tooth_images/42.svg', 'adult', 2, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(24, 41, 8, 'assets/tooth_images/41.svg', 'adult', 2, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(25, 31, 9, 'assets/tooth_images/41.svg', 'adult', 2, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(26, 32, 10, 'assets/tooth_images/32.svg', 'adult', 2, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(27, 33, 11, 'assets/tooth_images/33.svg', 'adult', 2, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(28, 34, 12, 'assets/tooth_images/34.svg', 'adult', 2, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(29, 35, 13, 'assets/tooth_images/35.svg', 'adult', 2, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(30, 36, 14, 'assets/tooth_images/36.svg', 'adult', 2, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(31, 37, 15, 'assets/tooth_images/37.svg', 'adult', 2, '2021-05-09 16:00:03', '2021-05-09 16:00:03'),
(32, 38, 16, 'assets/tooth_images/38.svg', 'adult', 2, '2021-05-09 16:00:03', '2021-05-09 16:00:03');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_name_ar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` double(15,2) NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `code`, `service_name`, `service_name_ar`, `price`, `note`, `category_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'DN1001', 'Clinical Examination   ', 'الفحص ألسريري ', 200.00, '', 1, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(2, 'DN1002', 'Emergency Exam ', 'فحص الطوارئ ', 300.00, '', 1, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(3, 'DN1003', 'Recall Examination ', 'فحص المتابعة', 180.00, '', 1, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(4, 'DN1004', 'Consultant Consultation ', 'استشارة الاستشاري', 300.00, '', 1, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(5, 'DN1005', 'Treatment Planning ', 'خطة العلاج', 250.00, '', 1, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(6, 'DN1006', 'Full Mouth X-Ray FMX ', 'أشعه لكامل الفم ', 320.00, '', 1, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(7, 'DN1007', 'Single PA or Bitewing ', 'أشعه جذريه أو جانبيه', 50.00, '', 1, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(8, 'DN1008', '2 PA or Bitewing ', 'عدد 2 أشعه جذريه أو جانبيه', 100.00, '', 1, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(9, 'DN1009', '3 PA or Bitewing ', 'عدد 3 أشعه جذريه أو جانبيه ', 150.00, '', 1, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(10, 'DN1010', '4 PA or Bitewing ', 'عدد 4 أشعه جذريه أو جانبيه ', 200.00, '', 1, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(11, 'DN1011', 'Occlusal Film ', 'أشعة طبقيه (سطحيه) ', 50.00, '', 1, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(12, 'DN1012', 'Panoramic X-Ray ', 'بانوراما ', 250.00, '', 1, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(13, 'DN1013', 'Cephalometric X-Ray ', 'أشعة قياسات الرأس (سيفالوميترك) ', 340.00, '', 1, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(14, 'DN1014', 'Diagnostic Casts ', 'قوالب الجبس التشخيصية', 200.00, '', 1, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(15, 'DN1015', 'Diagnostic photograph ', 'صور فوتوغرافيه تشخيصية ', 200.00, '', 1, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(16, 'DN1016', 'Diagnostic Wax-up ', 'النموذج الشمعي تشخيصي للأسنان', 550.00, '', 1, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(17, 'DN1017', 'Periodontal Charting ', 'قياس جيوب اللثة', 200.00, '', 1, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(18, 'DN1018', 'Periodontal deep scaling per quadrant ', 'تنظيف عميق للثة لنصف الفك ', 450.00, '', 1, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(19, 'DN1019', 'Dental Prophylaxis for Adult', 'تنظيف و تلميع الأسنان ', 300.00, '', 1, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(20, 'DN1020', 'Treat Sensitive Cementum or Dentine ', 'علاج حساسية العاج أو السمنت ', 150.00, '', 1, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(21, 'DN1021', 'Night Guard ', 'تركيبة الواقي الليلي للأسنان ', 900.00, '', 1, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(22, 'DN1022', 'Blood Sugar Test ', 'اختبار السكر في الدم ', 50.00, '', 1, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(23, 'DN1023', 'Fissure sealant per tooth ', 'سد تشققات السن ', 100.00, '', 2, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(24, 'DN1024', 'Excavation of caries & temp. restoration per tooth ', 'إزالة التسوس و وضع حشوه مؤقتة  ', 140.00, '', 2, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(25, 'DN1025', 'Refix or Recement (Inlay) ', 'إعادة وضع أو إعادة تثبيت (إنلاي) ', 180.00, '', 2, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(26, 'DN1026', 'Amalgam permanent one surface ', 'حشوه فضيه دائمة سطح واحد ', 250.00, '', 2, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(27, 'DN1027', 'Amalgam permanent two surface ', 'حشوه فضيه دائمة سطحين ', 300.00, '', 2, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(28, 'DN1028', 'Amalgam permanent three surfaces ', 'حشوه فضيه دائمة ثلاثة أسطح ', 350.00, '', 2, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(29, 'DN1029', 'Amalgam permanent four surfaces ', 'حشوه فضيه دائمة أربعة أسطح ', 400.00, '', 2, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(30, 'DN1030', 'Composite permanent one surface ', 'حشوه تجمليه بيضاء دائمة سطح واحد ', 300.00, '', 2, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(31, 'DN1031', 'Composite permanent two surface ', 'حشوه تجمليه بيضاء دائمة سطحين ', 400.00, '', 2, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(32, 'DN1032', 'Composite permanent three surface ', 'حشوه تجمليه بيضاء دائمة ثلاثة أسطح ', 500.00, '', 2, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(33, 'DN1033', 'Composite permanent four surfaces ', 'حشوه تجمليه بيضاء أربعة أسطح دائمة ', 750.00, '', 2, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(34, 'DN1034', 'Ceramic Inlay or Onlay', 'إعادة بناء للسن بالسيراميك ( انلاي او اونلاي )', 2500.00, '', 2, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(35, 'DN1035', 'Composite Inlay or Onlay', 'إعادة بناء للسن بالحشوة التجميليه البيضاء الدائمة', 1500.00, '', 2, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(36, 'DN1036', 'Esthetic Recontouring of Tooth ', 'إعادة تشكيل جمالي للأسنان ', 200.00, '', 2, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(37, 'DN1037', 'Direct pulp capping ', 'غطاء اللب المباشر  ', 200.00, '', 2, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(38, 'DN1038', 'Indirect pulp capping ', 'غطاء اللب غير المباشر ', 220.00, '', 2, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(39, 'DN1039', 'Glass Ionomer filling ', 'حشوه بيضاء طويلة الأمد ', 200.00, '', 2, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(40, 'DN1040', 'Core Build Up ', 'بناء السن ', 550.00, '', 2, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(41, 'DN1041', 'Office Bleaching both arches/session', 'تبييض الأسنان في العيادة  كل دورة ', 600.00, '', 2, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(42, 'DN1042', 'Home bleaching both arches ', 'أدوات تبييض الأسنان المنزلية', 1100.00, '', 2, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(43, 'DN1043', 'Internal Bleaching per tooth', 'تبييض الأسنان الداخلي الواحدة ', 950.00, '', 2, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(44, 'DN1044', 'Bleaching tray upper or lower', 'علبة مقاسات التبييض علوي أو سفلي', 300.00, '', 2, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(45, 'DN1045', 'Endodontic Tooth Assessment ', 'تقييم عصب الأسنان ', 50.00, '', 3, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(46, 'DN1046', 'Apexogenesis With MTA ', 'ترميم أطراف القناة العصبية باستخدام ثلاثي أكسيد المعادن المجمعة', 500.00, '', 3, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(47, 'DN1047', 'RCT(1 Canal) ', 'علاج جذور الأسنان (قناة واحده) ', 800.00, '', 3, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(48, 'DN1048', 'RCT(1 Canal)- complicated case ', 'علاج جذور الأسنان (قناة واحده) - حالة معقدة ', 1000.00, '', 3, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(49, 'DN1049', 'RCT(2 Canals) ', 'علاج جذور الأسنان (2 قناة) ', 1000.00, '', 3, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(50, 'DN1050', 'RCT( 2 Canals) complicated case ', 'علاج جذور الأسنان (2 قناة) حالة معقدة ', 1200.00, '', 3, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(51, 'DN1051', 'RCT ( 3 canals) ', 'علاج جذور الأسنان (3 قنوات) ', 1200.00, '', 3, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(52, 'DN1052', 'RCT(3 Canals)- complicated case ', 'علاج جذور الأسنان (3 قنوات) – حاله معقده ', 1400.00, '', 3, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(53, 'DN1053', 'RCT (4 Canals) ', 'علاج جذور الأسنان (4 قنوات) ', 1400.00, '', 3, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(54, 'DN1054', 'RCT(4Canals)- complicated case ', 'علاج جذور الأسنان (4 قنوات) – حاله معقده ', 1600.00, '', 3, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(55, 'DN1055', 'Retreatment(1 Canal) ', 'إعادة علاج للجذور (1 قناة) ', 1150.00, '', 3, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(56, 'DN1056', 'Retreatment(2 Canals) ', 'إعادة علاج للجذور (2 قناة)', 1350.00, '', 3, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(57, 'DN1057', 'Retreatment (3 Canals) ', 'إعادة علاج للجذور (3 قناة)', 1550.00, '', 3, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(58, 'DN1058', 'Retreatment (4 Canals) ', 'إعادة علاج للجذور (4 قناة)', 1750.00, '', 3, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(59, 'DN1059', 'Apexification(Initial Visit) ', 'معالجة تكوين القنوات الجذرية مفتوحة القمة  (الزيارة الأولية) ', 400.00, '', 3, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(60, 'DN1060', 'Apexification(Interim Visit) ', 'معالجة تكوين القنوات الجذرية مفتوحة القمة  (الزيارة المؤقتة) ', 250.00, '', 3, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(61, 'DN1061', 'Apexification With MTA ', 'معالجة تكوين القنوات الجذرية مفتوحة القمة  مع ثلاثي أكسيد المعادن المجمعة', 650.00, '', 3, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(62, 'DN1062', 'Apicectomy w/ (anterior) Retrograde filling ', 'أزاله طرف الجذر مع ملئ الفراغ الأمامي ', 2000.00, '', 3, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(63, 'DN1063', 'Apicectomy w/ (postrior-upper) Retrograde filling ', 'أزاله طرف الجذر مع ملئ الفراغ الخلفي العلوي ', 2600.00, '', 3, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(64, 'DN1064', 'Apicectomy w/(postrior-lower) Retrograde filling ', 'أزاله طرف الجذر مع ملئ الفراغ الخلفي السفلي ', 2600.00, '', 3, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(65, 'DN1065', 'Post removal for each canal ', 'إزالة دعامة من كل قناة ', 200.00, '', 3, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(66, 'DN1066', 'Abscess drainage ', 'إزالة الصديد ', 350.00, '', 3, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(67, 'DN1067', 'Pulpotomy ', 'تنظيف الجزء العلوي من ألقناه العصبية', 450.00, '', 3, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(68, 'DN1068', 'Pulpectomy ', 'تنظيف ألقناه العصبية', 550.00, '', 3, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(69, 'DN1211', 'Dental Prophylaxis for Children by Hygienist ', 'تنظيف أسنان الأطفال وازالة الجير ', 200.00, '', 4, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(70, 'DN1213', 'Dental Prophylaxis for Children by Periodontist ', 'تنظيف أسنان و إزالة جير للاطفال من قبل أخصائي لثة', 350.00, '', 4, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(71, 'DN1069', 'Topical Application of Fluoride (Excluding Prophy) ', 'تطبيق للفلورايد المركز على الاسنان ', 100.00, '', 4, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(72, 'DN1070', 'Amalgam primary one surface ', 'حشوه فضيه سطح واحد لسن لبني ', 180.00, '', 4, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(73, 'DN1071', 'Amalgam primary two surfaces ', 'حشوه فضيه على سطحين لاسنان لبنيه ', 220.00, '', 4, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(74, 'DN1072', 'Amalgam primary three surfaces ', 'حشوه فضيه على ثلاث اسطح لاسنان لبنيه ', 260.00, '', 4, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(75, 'DN1073', 'Primary Anterior Composite Resin Crown ', 'تركيب تاج التجميلي للسن اللبني الامامي  ', 400.00, '', 4, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(76, 'DN1074', 'Composite primary one surface ', 'حشوه تجميليه بيضاء دائمه سطح واحد ', 250.00, '', 4, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(77, 'DN1075', 'Composite primary two surfaces ', 'حشوه تجميليه بيضاء دائمه سطحين ', 300.00, '', 4, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(78, 'DN1076', 'Composite primary three surfaces ', 'حشوة تجميليه بيضاء دائمه ثلاثة اسطح ', 350.00, '', 4, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(79, 'DN1077', 'Composite primary four surfaces ', 'حشوة تجميليه بيضاء دائمه اربعه اسطح ', 400.00, '', 4, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(80, 'DN1078', 'Pedo Stainless Steel Crown ', 'تركيب تاج معدني للاطفال', 500.00, '', 4, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(81, 'DN1079', 'Refix or Recement (Crown) ', 'اعادة تثبيت تاج ', 175.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(82, 'DN1080', 'Porcelain full cover crown & Lab fees ', 'تاج بورسلان للسن الواحد  ', 2500.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(83, 'DN1081', 'Zirconia Crown ', 'تاج زيركون للسن الواحد ', 2500.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(84, 'DN1082', 'Empress or E.Max Crown ', 'تاج أيماكس او امبرس للسن الواحد ', 2500.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(85, 'DN1083', 'PFM   crown ', 'تاج معدن من الداخل مغطى بالبورسلان  ', 2500.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(86, 'DN1084', 'Cast Post and Core ', 'دعامه و وتد معدن نفيس  ', 1000.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(87, 'DN1085', 'Prefabricated post & core ', 'دعامه و وتد جاهزه', 850.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(88, 'DN1086', 'Temporary acrylic laminate or composite ', 'تاج مؤقت كامل ', 180.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(89, 'DN1087', 'Labial Veneer direct ( Resin Laminate) - /Tooth ', 'عدسه تجميليه من الحشوات البيضاء التجميليه', 1400.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(90, 'DN1088', 'Labial Veneer indirect(Porcelain)Lab/tooth ', 'عدسه تجميليه من السيراميك  ', 2500.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(91, 'DN1089', 'Complete denture upper ', 'طقم كامل علوي ', 2500.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(92, 'DN1090', 'Complete denture Lower ', 'طقم كامل سفلي  ', 2500.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(93, 'DN1091', 'Chrome Cobalt  Denture Upper', 'طقم كروم كوبالت العلوي ', 2800.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(94, 'DN1092', 'Chrome Cobalt  Denture Lower ', 'طقم كروم كوبالت سفلي   ', 2800.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(95, 'DN1093', 'Acrylic partial denture upper ', 'طقم جزئي علوي  ', 1400.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(96, 'DN1094', 'Acrylic partial denture lower ', 'طقم جزئي سفلي ', 1400.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(97, 'DN1095', 'Addition of Tooth ', 'إضافة السن اكريل للطقم  ', 200.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(98, 'DN1096', 'tooth recontouring ', 'تعديل شكل سن  ', 150.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(99, 'DN1097', 'Repair Crack or Fracture (Upper) ', 'إصلاح الشرخ في طقم اسنان علوي ', 350.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(100, 'DN1098', 'Repair Crack or Fracture (Lower) ', 'إصلاح الشرخ في طقم اسنان سفلي  ', 350.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(101, 'DN1099', 'Refix Clasp (Upper) ', 'تعديل دعامة طقم علوي ', 350.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(102, 'DN1100', 'Refix Clasp (Lower) ', 'تعديل دعامة طقم سفلي  ', 350.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(103, 'DN1101', 'Repair of broken tooth  ', 'إصلاح الأسنان المكسورة ', 350.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(104, 'DN1102', 'Reline or Rebase of Upper (Lab) ', 'اعادة تبطين او اعادة قاعده علويه (معمل) ', 550.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(105, 'DN1103', 'Reline or Rebase of Lower (Lab) ', 'اعادة تبطين او اعادة قاعده سفليه (معمل) ', 550.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(106, 'DN1104', 'Reline with Addition of Flange (Upper) ', 'اعادة تبطين مع اضافة حافه (العلوي) ', 550.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(107, 'DN1105', 'Reline with Addition of Flange (Lower) ', 'اعادة تبطين مع اضافة حافه (السفلى) ', 550.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(108, 'DN1106', 'Reline with Soft Lining (Upper) ', 'اعادة تبطين ببطانه ناعمه (العلوي) ', 450.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(109, 'DN1107', 'Reline with Soft Lining (Lower) ', 'اعادة تبطين ببطانه ناعمه (سفلي) ', 450.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(110, 'DN1108', 'Overdenture upper ', 'دعامه تحت تركيبه علويه  ', 2250.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(111, 'DN1109', 'Overdenture lower ', 'دعامه تحت تركيبه سفليه  ', 2250.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(112, 'DN1110', 'Special Tray Upper ', 'مقسات خاصه للفك العلوي ', 120.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(113, 'DN1111', 'Special Tray Lower ', 'مقاسات خاصه للفك السفلي ', 120.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(114, 'DN1112', 'Occlusal Splint Therapy ', 'جبيره علاجيه سطحيه ', 1150.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(115, 'DN1113', 'Chrome Cobalt Partial Denture Upper ', 'تركيبه متحركه جزئيه للفك العلوي كروم كوبالت ', 2550.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(116, 'DN1114', 'Chrome Cobalt Partial Denture Lower ', 'تركيبه متحركه جزئيه للفك السفلي كروم كوبالت ', 2550.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(117, 'DN1115', 'Remove Crown per tooth ', 'إزالة تاج لكل سن ', 200.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(118, 'DN1116', 'Remove Bridge ', 'إزالة جسر ', 400.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(119, 'DN1117', 'Prosthetic part of implant / tooth ', 'الزرع المؤقتة للتاج  ', 3500.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(120, 'DN1118', 'Temporary Implant Crown ', 'الجزء الاصطناعي للسن من زرع / الأسنان ', 800.00, '', 5, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(121, 'DN1119', 'Extraction of single permanent tooth(non surgical) ', 'خلع سن دائم ( مباشر ) ', 480.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(122, 'DN1120', 'Extraction of erupted tooth(surgical)', 'خلع سن دائم ( بتدخل جراحي مع خياطة ) ', 1250.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(123, 'DN1121', 'Extraction of impacted tooth-soft tissue ', 'خلع سن دائم على مستوى او داخل اللثه', 1800.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(124, 'DN1122', 'Extraction of impacted tooth part-bony ', 'خلع سن دائم على مستوى او داخل العظم جزئيا  ', 1900.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(125, 'DN1123', 'Extraction of impacted tooth bony ', 'خلع سن دائم من داخل العظم ', 2200.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(126, 'DN1124', 'Extraction of remaining roots ', 'خلع الجذور المتبقية ', 700.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(127, 'DN1125', 'Surgical exposure of impacted tooth', 'كشف جراحي للاسنان الغائره داخل الفم ', 1750.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(128, 'DN1126', 'Extraction of primary tooth ', 'ازالة الأسنان اللبنية ', 160.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(129, 'DN1127', 'Biopsy hard tissue ', 'خزعة الأنسجة الصلبة ', 1700.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(130, 'DN1128', 'Biopsy soft tissue ', 'خزعة الأنسجة اللينة ', 1300.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(131, 'DN1129', 'Alveoloplasty with extraction per quadrant ', 'جراحة ازالة اسنان متحركة مع تشكيل عظمي ( نصف الفك ) ', 3000.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(132, 'DN1130', 'Alveloplasty per unit', 'جراحة تشكيل العظام الحادة لكل وحدة ', 900.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(133, 'DN1131', 'Re-implantation of Luxated Tooth ', 'اعادة زراعه و تثبيت لسن مخلوع ', 550.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(134, 'DN1132', 'External sinus lift (including 1 bottle)', 'رفع الجيب جانبيا  (متضمنا علبة ترميم واحده ) ', 4800.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(135, 'DN1133', 'Internal sinus lift  (including 1 bottle) ', 'رفع الجيب الانفي داخليا  (متضمنا علبة ترميم واحده) ', 4400.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(136, 'DN1134', 'Guided Bone Regeneration (1- 3 teeth per bottle)', 'ترميم جزء من عظام الفك 1-3 اسنان ( متضمنا علبة ترميم واحده ) ', 2800.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(137, 'DN1135', 'Frenectomy ', 'استئصال اطراف ليفيه ( الفرينوم ) ', 1300.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(138, 'DN1136', 'Gingivectomy 3-6 teeth ', 'استئصال جزء من اللثة لجزء من الفك 1-5 اسنان  ', 1500.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(139, 'DN1137', 'Gingivectomy/ gingivoplasty per unit ', 'استئصال اللثة لكل وحدة ', 300.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(140, 'DN1138', 'Excision of lip Mucocele / mass (1-2cm', 'استئصال انتفاخ من الشفه 1-2 سم  ', 1600.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(141, 'DN1139', 'Odontogenic Cyst in the Jaw 1-2cm', 'استئصال كيس من الفك 1-2 سم  ', 2100.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(142, 'DN1140', 'Large cyst removal 2-3cm ', 'استئصال كيس من الفك 2-3 سم ', 5000.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(143, 'DN1141', 'Small Benign Tumors 1-2 cm', 'استئصال أورام نسيجيه 1-3 سم  ', 3000.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(144, 'DN1142', 'Large benign tumor 2-4cm', 'استئصال ورم نسيجي 3-4 سم  ', 7000.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(145, 'DN1143', 'Tunnel Technique for Bone Graft ', 'الترميم العظمي بتقنية النفق (متضمنا علبة ترميم واحده)  ', 4000.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(146, 'DN1144', 'T.M.J Injection Therapy per visit', 'علاج مفصل الفك بالحقن للجلسة الواحده', 1300.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(147, 'DN1145', 'Treatment of Mandible Dislocation per visit ', 'رد انزلاق مفصل الفك الصدغي (الجلسه الواحده)', 1000.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(148, 'DN1146', 'TMJ Lavage 4100', 'غسيل المفصل الصدغي ', 4100.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(149, 'DN1147', 'Botox A ', 'بوتوكس  أ ', 2400.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(150, 'DN1148', 'Botox AA ', 'البوتوكس أأ', 1600.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(151, 'DN1149', 'Botox AAA (Retouch) ', 'بوتوكس أأأ', 550.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(152, 'DN1150', 'Botox injection for pain per side  ', 'حقن البوتوكس لعلاج الآلام   (الجلسه الواحده)', 3500.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(153, 'DN1151', 'Flap procedure ', 'جراحة كشف غطاء الأسنان ', 2550.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(154, 'DN1152', 'Osseous Crown Lengthening per tooth  ', 'جراحة استئصال عظمي للسن الواحد  ', 1150.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(155, 'DN1153', 'Osseous Crown lengthening 1-3 teeth ', 'جراحة استئصال عظمي (إطالة التاج 1-3 أسنان ) ', 2500.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(156, 'DN1154', 'Osseous Crown lengthening 3-6 teeth ', 'جراحة استئصال عظمي (أطالة التاج 3-6 أسنان )  ', 2800.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(157, 'DN1155', 'Oro-Antral Fistula Closure ', 'جراحة اغلاق تواصل الفم و الجيب الانفي ', 2700.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(158, 'DN1156', 'Sof t tissue  Excision 1-3cm per visit ', 'استئصال نسيجي رخوي 1-3 سم ', 1350.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(159, 'DN1157', 'Sof t tissue  Excision bi injection per visit', 'استئصال نسيجي رخوي 3-6 سم ', 6000.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(160, 'DN1158', 'Oral Lesion Treatment  1', 'علاج تقرحات بالفم (الجلسه الواحده) ', 420.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(161, 'DN1159', 'Oral Lesion Treatment  11', 'علاج تقرحات  بالفم (بالحقن الجلسه الواحده)  ) ', 1420.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(162, 'DN1160', 'Extra-oral Abscess Drainage ', 'فتح جراحي لصديد من خارج الفم  ', 850.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(163, 'DN1161', 'Intra - Oral Abscess Drainage ', 'فتح جراحي لصديد من داخل الفم ', 400.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(164, 'DN1162', 'Bone graft material per bottle ', 'الترميم العظمي للعلبه الواحده', 850.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(165, 'DN1163', 'Alloderm for root coverage including material ', 'ترميم نسيجي خارجي (الوديرم للعلبه الواحده ) ', 4900.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(166, 'DN1164', 'Socket preservation per tooth, including material ', 'إزالة سن مع ترميم عظمي داخلي  ', 3000.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(167, 'DN1165', 'Membrane application ', 'ترميم غشائي نسيجي للعلبه الواحده', 980.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(168, 'DN1166', 'Pericoronitis topical  Treatment  per visit', 'علاج التهاب اللثوي لضرس منطمر للجلسة الواحدة', 350.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(169, 'DN1167', 'Local Delivery of Antibiotic per tooth ', 'تنظيف ووضع مضاد حيوي موضعي (1-3 أسنان )  ', 300.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(170, 'DN1168', 'Surgical placement of implant  ', 'زراعه جراحيه للسن ', 3500.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(171, 'DN1169', 'Surgical stent ', 'جهاز تحديد موقع زراعة الأسنان ', 450.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(172, 'DN1170', 'Surgical Implant removal ', 'إزالة ألزرعه الجراحية', 2600.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(173, 'DN1171', '2’nd Stage Healing Abutment ', 'المرحلة الثانية للزراعة الجراحية', 450.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(174, 'DN1172', 'Maxillary osteotomy surgery', 'تحريك جراحي للفك العلوي ', 20000.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(175, 'DN1173', 'Mandible osteotomy surgery', 'تحريك جراحي للفك السفلي ', 20000.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(176, 'DN1174', 'Chin correction surgery', 'تحريك جراحي لعظمة الذقن ', 10000.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(177, 'DN1175', 'Facial augmentation ', 'ترميم جراحي لعظام الوجه ', 10000.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(178, 'DN1176', 'Fixation plates per unit   500', 'استخدام الصفائح المعدنيه للوحده', 500.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(179, 'DN1177', 'Fixation screws 400', 'استخدام المسامير للوحده', 400.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(180, 'DN1178', 'Fixation plate and screws 4 holes 2500', 'صفيحه معدنيه رباعيه مع مسامير ', 2500.00, '', 6, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(181, 'DN1179', 'Orthodontic Records ', 'تقويم الأسنان السجلات ', 800.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(182, 'DN1180', 'Comprehensive Orthodontic Treatment ', 'علاج تقويم الأسنان الشامل ', 4000.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(183, 'DN1181', 'Limited Orthodontic Treatment ', 'علاج تقويم الأسنان المحدود ', 2500.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(184, 'DN1182', 'Ceramic/Self Ligating Brackets ', 'السيراميك / سيلف ليقيتنجبراكت', 800.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(185, 'DN1183', 'Additional Orthodontic Appliance (Mini-Implant,Hyrax., tc.)', 'أجهزة تقويم الأسنان إضافية (ميني-إمبلانت،هيركس)  ', 800.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(186, 'DN1184', 'Invisalign Treatment (Records) ', 'علاج التقويم الغير مرئي (السجلات) ', 8500.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(187, 'DN1185', 'Invisalign Treatment (Delivery) ', 'علاج التقويم الغير مرئي (التسليم) ', 4200.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(188, 'DN1186', 'Orthodontic Activation Follow-up Visit ', 'شد  التقويم زيارة المتابعه', 500.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(189, 'DN1187', 'Routine Follow-up Visit ', 'زيارة المتابعة الروتينية لحالات التقويم  ', 250.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(190, 'DN1188', 'Deband/Debond and Records ', 'ديباند / ديبوند والسجلات ', 800.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(191, 'DN1189', 'Fixed Retention ', 'ريتينر ثابت ', 500.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(192, 'DN1190', 'Removable Retainer (Hawley or Wrap around) ', 'قابل للإزالة ريتاينر (هاولي أو راب اروند) ', 600.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(193, 'DN1191', 'Essix Retainer ', 'إسيكسريتينر', 550.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(194, 'DN1192', 'Lingual Arch ', 'القوس السفلي  ', 600.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(195, 'DN1193', 'Transpalatal Arch (TPA) ', 'القوس العلوي لسقف الحلق  ', 600.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(196, 'DN1194', 'Nance Appliance ', 'جهاز نانس ', 600.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(197, 'DN1195', 'Tongue Crib ', 'مانع اللسان ', 600.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(198, 'DN1196', 'Lip Bumber', 'مانع الشفاه  ', 600.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(199, 'DN1197', 'Quadhelix / W-Arch ', 'كوادهليكس / W-قوس ', 850.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(200, 'DN1198', 'Rapid Palatal Expander (Hyrax/Haas/Bonded) ', 'الموسع السريع لسقف الحلق  (هيراكس / هاس / بونديد) ', 1300.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(201, 'DN1199', 'Acrylic Cervical Occipital Appliance (ACCO) / Z-spring  ', 'جهاز اكريلسيرفايكالاوكسيبيتال / زنبرك زي ', 800.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(202, 'DN1200', 'Pendulum / Pendex', 'بندوليوم / بندكس', 1300.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(203, 'DN1201', 'Spring Aligner Removable ', 'زنبرك اليجنر المتحرك ', 1300.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(204, 'DN1202', 'Bite Plane ', 'مسواة الإطباق ', 600.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(205, 'DN1203', 'Hard Occlusal Appliance ', 'جهاز سطحي صلب ', 1300.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(206, 'DN1204', 'Soft Occlusal Appliance ', 'جهاز سطحي ناعم ', 500.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(207, 'DN1205', 'Head Gear / Face Mask ', 'غطاء الرأس / قناع الوجه ', 800.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(208, 'DN1206', 'Band & Loop ', 'الرباط و الحلقه', 500.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(209, 'DN1207', 'Deband/Debond& Retention ', 'ازالة الربطه و الربط و الثبات ', 2300.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(210, 'DN1208', 'Limited Occlusal Adjustment ', 'تعديل لمنطقه سنيه سطحيه معينه ', 150.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(211, 'DN1209', 'Complete occlusal Adjustment ', 'التعديل الشامل للسطح السني ', 300.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(212, 'DN1210', 'Occlusal analysis ', 'تحليل السطحي ', 200.00, '', 7, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(213, 'SV001', 'Carboxy of Acne Scar 01 Session', 'حقن ثاني اكسيد الكربون للندبات للجلسة ', 400.00, '', 8, NULL, '2021-05-08 17:13:57', '2021-05-08 17:13:57'),
(214, 'SV002', 'Carboxy of Acne Scar 08 Sessions', 'حقن ثاني اكسيد الكربون للندبات 5 جلسات ', 2800.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(215, 'SV003', 'Carboxy of Fine Wrinkles 01 Session', 'حقن ثاني اكسيد الكربون للتجاعيد للجلسة', 400.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(216, 'SV004', 'Carboxy of Fine Wrinkles 08 Sessions', 'حقن ثاني اكسيد الكربون للتجاعيد 8 جلسات ', 2800.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(217, 'SV005', 'Carboxy of Stretch Marks Large Area 01 Session', 'حقن ثاني اكسيد الكربون للتشققات (للمنطقة الكبيرة) للجلسة', 600.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(218, 'SV006', 'Carboxy of Stretch Marks Large Area 08 Sessions', 'حقن ثاني اكسيد الكربون للمنطقة الوسطى للجلسة ', 4200.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(219, 'SV007', 'Carboxy of Stretch Marks Medium Area 01 Session', 'حقن ثاني اكسيد الكربون للمنطقة الوسطى للجلسة', 400.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(220, 'SV008', 'Carboxy of Stretch Marks Medium Area 08 Sessions', 'حقن ثاني اكسيد الكربون للمنطقة الوسطى 8 جلسات', 2800.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(221, 'SV009', 'Carboxy of Dark Circles 01 Session', 'حقن ثاني أكسيد الكربون للهلات السوداء للجلسة', 400.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(222, 'SV010', 'Carboxy of Dark Circles 08 Sessions', 'حقن ثاني أكسيد الكربون للهلات السوداء 8 جلسات ', 2800.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(223, 'SV011', 'Carboxy of Dark Circles 01 Session', 'حقن ثاني اكسيد الكربون للسلولايات للجلسة', 750.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(224, 'SV012', 'Carboxy of Dark Circles 08 Sessions', 'حقن ثاني اكسيد الكربون للسلولايات 8 جلسات', 5250.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(225, 'SV051', 'Crystal Peel of Face and Neck 01 session', 'التقشير الكريستالي (للوجه والرقبة) للجلسة ', 500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(226, 'SV052', 'Crystal Peel of Face and Neck 03 sessions ', 'التقشير الكريستالي (للوجه والرقبة) 3 جلسات', 1200.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(227, 'SV053', 'Crystal Peel of Knees 01 session', 'التقشير الكريستالي (للركب) للجلسة ', 300.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(228, 'SV054', 'Crystal Peel of Knees 06 sessions', 'التقشير الكريستالي (للركب) 6 جلسات', 1300.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(229, 'SV055', 'Crystal Peel of Elbows 01 session', 'التقشير الكريستالي (للأكواع) للجلسة', 300.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(230, 'SV056', 'Crystal Peel of Elbows 06 sessions', 'التقشير الكريستالي (للأكواع) 6 جلسات', 1300.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(231, 'SV057', 'Crystal Peel of Hands 01 session', 'التقشير الكريستالي (لليد) للجلسة', 400.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(232, 'SV058', 'Crystal Peel of Hands 03 sessions', 'التقشير الكريستالي (لليد) 3 جلسات ', 800.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(233, 'SV091', 'Glycolic Peel', 'التقشير بحمض الفواكة', 400.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(234, 'SV092', 'TCA Peel', 'التقشير الكيميائي', 500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(235, 'SV093', 'Whitening Peel Small Area', 'تفتيح منطقة صغيرة ', 600.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(236, 'SV094', 'Whitening Peel Face', 'تفتيح الوجه', 800.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(237, 'SV095', 'Peel whole body', ' توحيد لون الجسم', 3300.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(238, 'SV096', 'Mesotherapy  Rejuvination', 'ميزوثيرابي  للنضارة  للجلسه', 500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(239, 'SV097', 'Mesotherapy  Hair', 'ميزوثيرابي  للشعر للجلسه', 700.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(240, 'SV098', 'Mesotherapy Fat Desolving    ', 'ميزوثيرابي لحرق الدهون للجلسة', 700.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(241, 'SV242', 'Mesotherapy  (5 sessions)', 'ميزوثيرابي 5 جلسات ', 2000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(242, 'PLA020', 'Orange  peel small area  / session', 'التقشير البرتقالي منطقه صغيره / للجلسه', 500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(243, 'PLA021', 'Orange  peel large  area  / session', 'التقشير البرتقالي منطقه كبيره  / للجلسه', 1500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(244, 'PLA022', 'Lips peel', 'تقشير الشفايف', 200.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(245, 'PLA023', 'wound Dressing', 'لتنظيف الجروح ', 150.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(246, 'PLA012', 'Intradermal Filler 1 Kit', 'تعبئة المنطقة السفلى من الوجه', 1500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(247, 'SV151', 'Botox 1 area', 'بوتكس (للجلديه) منطقه 1 ', 1500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(248, 'SV087', 'Botox 2 area', 'بوتكس (للجلديه)منطقتين', 1800.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(249, 'PLA013', 'Botox Vial Cosmetic', 'بوتكس (للتجميل)', 3000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(250, 'SV088', 'Botox  hydronephrosis', 'بوتكس التعرق', 2500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(251, 'SV089', 'Filler Small 1cc', 'تعبئة للمنطقة صغيرة', 1500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(252, 'SV090', 'Filler Large 2cc', 'تعبئة للمنطقة كبيرة ', 2500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(253, 'SV109', 'Laser of Hair Removal Other Small Area with retouch  ', 'ليزر لإزالة الشعر لمنطقة صغيرة مع رتوش', 400.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(254, 'SV061', 'Laser of Hair Removal Small Zone (Bikini) 01 Session', 'ليزر لإزالة الشعر لمنطقة صغيرة (المنطقة الحساسة) للجلسة', 500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(255, 'SV062', 'Laser of Hair Removal Small Zone (Bikini) 0 5 Sessions', 'ليزر لإزالة الشعر لمنطقة صغيرة (المنطقة الحساسة) 5 جلسات', 1500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(256, 'SV063', 'Laser of Hair Removal Small Zone (Underarm) 01 Session', 'ليزر لإزالة الشعر لمنطقة صغيرة (المنطقة الإبط)   للجلسه', 400.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(257, 'SV064', 'Laser of Hair Removal Small Zone (Underarm) 05Session', 'ليزر لإزالة الشعر لمنطقة صغيرة (المنطقة الأبط) 5جلسات', 1500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(258, 'SV100', 'Laser of Hair Removal Small Zone (Bikini, Underarm) 05 Sessions', 'ليزر لإزالة الشعر لمنطقة صغيرة (لمنطقة إبط ومنطقة الحساسة) 5 جلسات ', 3000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(259, 'SV143', 'Laser of Hair Removal (Face) 01 Session', 'ليزر لإزالة الشعر (للوجه) للجلسة', 500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(260, 'SV081', 'Laser of Hair Removal (Face) 05 Sessions', 'ليزر لإزالة الشعر (للوجه) 5 جلسات', 1500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(261, 'SV027', 'Laser of Hair Removal (Lip) 01 Sessions', 'ليزر لإزالة الشعر لمنطقة صغيرة (للشفاه) ', 150.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(262, 'SV028', 'Laser of Hair Removal lip  05 Session', 'ليزر لإزالة الشعر لمنطقة صغيرة (للشفاه) 5جلسات ', 550.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(263, 'SV 101', 'Laser of Hair Removal (Chin) 01 Sessions', 'ليزر لإزالة الشعر لمنطقة صغيرة (للذقن) للجلسة', 200.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(264, 'SV102', 'Laser of Hair Removal chin 05 Session', 'ليزر لإزالة الشعر لمنطقة صغيرة (للذقن) 5, جلسات ', 700.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(265, 'SV103', 'Laser of Hair Removal Small Zone (Sied Burns) 01 Sessions', 'ليزر لإزالة الشعر لمنطقة صغيرة (سوالف) للجلسة', 200.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(266, 'SV104', 'Laser of Hair Removal Mid Zone (Arm) 05 Session', 'ليزر لإزالة الشعر لمنطقة صغيرة (سوالف) 5 جلسات ', 800.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(267, 'SV029', 'Laser of Hair Removal Mid Zone (Arm / leg) 01 Sessions', 'ليزر لإزالة الشعر لمنطقة متوسطة (اليد / الأرجل  ) للجلسة ', 700.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(268, 'SV030', 'Laser of Hair Removal Mid Zone (Half Arm/leg) 05 Session', 'ليزر لإزالة الشعر لمنطقة متوسطة (اليد/ الأرجل( 5 جلسات ', 2500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(269, 'SV 065', 'Laser of Hair Removal Mid Zone', 'ليزر لإزالة الشعر لمنطقة متوسطة (نصف اليد/ الأرجل( للجلسة', 500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(270, 'SV066', 'Laser of Hair Removal Mid Zone (Half arm/leg) 05 Session ', 'ليزر لإزالة الشعر لمنطقة متوسطة (نصف اليد/ الأرجل  5(جلسة', 2000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(271, 'SV106', 'Laser of Hair Removal Small Zone (Bikini, Underarm) 06 Sessions', 'ليزر لإزالة الشعر لمنطقة متوسطة (نصف اليد/ الأرجل  5(جلسة', 1800.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(272, 'SV107', 'Laser of Hair Removal Mid Zone (Male Beard) 01 Sessions', 'ليزر لإزالة الشعر لمنطقة متوسطة (ذقن الرجل) للجلسة ', 500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(273, 'SV108', 'Laser of Hair Removal Mid Zone (Male Beard) 05Sessions', 'ليزر لإزالة الشعر لمنطقة متوسطة (ذقن الرجل)5 جلسات', 3000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(274, 'SV032', 'Laser of Hair Removal Large Zone (Legs) 01 Sessions', 'ليزر لإزالة الشعر لمنطقة كبرى (الأرجل) للجلسة', 900.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(275, 'SV033', 'Laser of Hair Removal Large Zone (Legs) 05 Sessions', 'ليزر لإزالة الشعر لمنطقة كبرى (الأرجل)5جلسات ', 4000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(276, 'SV144', 'Laser of Laser of Hair Removal Full Back', 'ليزر لإزالة الشعر (للظهر كامل) للجلسة', 800.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(277, 'SV037', 'Laser of Total Body Hair Removal 01 Sessions', 'ليزر لإزالة الشعر (للجسم كامل) للجلسة', 1500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(278, 'SV038', 'Laser of Total Body Hair Removal 05 Sessions', 'ليزر لإزالة الشعر (للجسم كامل) 5 جلسات ', 6500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(279, 'SV036', 'Laser of Vascular/Vein Removal / Region (Both Cheek, Leg Area, etc) 01 Session', 'ليزر لإزالة الشعيرات الدموية في الخدود للجلسة', 750.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(280, 'SV039', 'Laser of Photo Rejuvenation ND-YAG 01 Session', 'ليزر لإعادة النظارة للجلسة', 500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(281, 'SV040', 'Laser of Photo Rejuvenation ND-YAG 06 Sessions', 'ليزر لإعادة النظارة 6 جلسات ', 2500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(282, 'SV110', 'Fractional Laser (Small Area)', 'فراكشنل ليزر لمنطقة صغيرة (لإزالة البقع والتجاعيد)', 500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(283, 'SV111', 'Fractional Laser (Medium Area)', 'فراكشنل ليزر لمنطقة متوسطة (لإزال البقع والتجاعيد)', 1000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(284, 'SV112', 'Fractional Laser (Large Area)', 'فراكشنل ليزر لمنطقة كبرى (لإزالة البقع والتجاعيد)', 1500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(285, 'SV113', 'Telangectasia (Small Area)', 'ليزر لمنطقة صغيرة (لإزالة الشعيرات الدموية)', 500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(286, 'SV114', 'Telangectasia (Medium Area)', 'ليزر لمنطقة متوسطة (لإزالة الشعيرات الدموية)', 1000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(287, 'SV115', 'Telangectasia (Large Area)', 'ليزر لمنطقة كبرى (لإزالة الشعيرات الدموية)', 1500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(288, 'SV159', 'Body Rapping (Body Treatment) 01 Session', 'تكسير الدهون (بالكريمات الطبية) للجلسة ', 400.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(289, 'SV257', 'Body Rapping (Body Treatment) 06 Sessions', 'تكسير الدهون (بالكريمات الطبية) 6 جلسات', 2000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(290, 'SV127', 'Intralesional Injection Less Than 5', 'حقن داخل الاجراح المرتفعة مقاس (5)', 400.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(291, 'SV128', 'Intralesional Injection Between (5-10)', 'حقن داخل الاجراح المرتفعة من (5إلى10)', 800.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(292, 'SV129', 'Intralesional Injection Between (more than 10)', 'حقن داخل الاجراح المرتفعة أكبر من قياس (10)', 1500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(293, 'SV130', 'Foot  corn', 'مسمار اللحم', 1000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(294, 'SV131', 'Nail avalsion', 'قلع الأظافر ', 1000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(295, 'SV0001', 'Electrocautery 1', ' كي كهربائي  1', 500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(296, 'SV0002', 'Electrocautery 2', 'كي كهربائي  1', 800.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(297, 'SV0003', 'Electrocautery 3', 'كي كهربائي  1', 1000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(298, 'SV0004', 'Electrocautery 4', 'كي كهربائي  1', 1500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(299, 'SV0005', 'Electrocautery 5', 'كي كهربائي  1', 2000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(300, 'SV0006', 'Doctor Injection', 'حقن طبي', 1000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(301, 'SV0007', 'Steroid Injection 1', ' كورتوزون   1', 500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(302, 'SV0008', 'Steroid Injection 2', 'كورتوزون   1', 1000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(303, 'SV0009', 'Hyalourinidase Injection', 'اذابه ', 1000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(304, 'SV0010', 'Skin Biopsy 1', 'عينه جلد 1', 1000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(305, 'SV0011', 'Skin Biopsy 2', 'عينه جلد 2', 1500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(306, 'SV0012', 'Excision 1', 'استئصال 1', 500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(307, 'SV0013', 'Excision 2', 'استئصال 2', 800.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(308, 'SV0014', 'Excision 3', 'استئصال 3', 1000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(309, 'SV0015', 'Liquid Nitrogen 1', 'كي بالتبريد   1', 300.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(310, 'SV0016', 'Liquid Nitrogen 2', 'كي بالتبريد   1', 500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(311, 'SV0017', 'PRP', 'بلازما (الخلايا الجذعية)     ', 1500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(312, 'SV0018', 'PRP 2', 'بلازما (الخلايا الجذعية)   2     ', 2000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(313, 'SV0019 ', 'PRP 3', 'بلازما (الخلايا الجذعية)    3', 2500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(314, 'SV0020', 'PRP 4', 'بلازما (الخلايا الجذعية)    4', 3000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(315, 'SV0021', 'Chemical Peel 1', '  التقشير الكيميائي1     ', 500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(316, 'SV0022', 'Chemical Peel 2', 'التقشير الكيميائي2       ', 750.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(317, 'SV0023', 'Chemical Peel 3', 'التقشير الكيميائي      3', 1000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(318, 'SV0024', 'Chemical Peel 4', 'التقشير الكيميائي       4', 1500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(319, 'SV0025', 'Chemical Peel 5', 'التقشير الكيميائي       5', 2000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(320, 'SV0026', 'Chemical Peel 6', 'التقشير الكيميائي       6', 2500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(321, 'SV0027', 'Chemical Peel 7', 'التقشير الكيميائي       7', 3000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(322, 'SV0028', 'Med Lite 1', ' ميدلايت    1', 700.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(323, 'SV0029', 'Med Lite 2', 'ميدلايت    2', 1000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(324, 'SV0030', 'Med Lite 3', 'ميدلايت    3', 1500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(325, 'SV0031', 'Med Lite 4', 'ميدلايت    4', 2000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(326, 'SV0032', 'Med Lite 5', 'ميدلايت    5', 3000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(327, 'SV0033', 'Scarlet 1', ' سكارليت   1', 1500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(328, 'SV0034', 'Scarlet 2', 'سكارليت   1', 2000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(329, 'SV0035', 'Scarlet 3', 'سكارليت   1', 2500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(330, 'SV0036', 'Scarlet 4', 'سكارليت   1', 3000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(331, 'SV0037', 'Sciton 1', 'سايتون    1', 500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(332, 'SV0038', 'Sciton 2', 'سايتون    1', 1000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(333, 'SV0039', 'Sciton 3', 'سايتون    1', 1500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(334, 'SV0040', 'Sciton 4', 'سايتون    1', 2000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(335, 'SV0041', 'Sciton 5', 'سايتون    1', 2500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(336, 'SV0042', 'Sciton 6', 'سايتون    1', 3000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(337, 'SV0043', 'Amelan 1', 'اميلان   1', 1500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(338, 'SV0044', 'Amelan 2', 'اميلان   1', 2000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(339, 'SV0045', 'Amelan 3', 'اميلان   1', 2500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(340, 'SV0046', 'Amelan 4', 'اميلان   1', 3000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58');
INSERT INTO `services` (`id`, `code`, `service_name`, `service_name_ar`, `price`, `note`, `category_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(341, 'SV0047', 'Facial 1', ' تنظيف بشره 1', 500.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(342, 'SV0048', 'Facial 2', 'تنظيف بشره 1', 750.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(343, 'SV0049', 'Facial 3', 'تنظيف بشره 1', 1000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(344, 'SV0050', 'Eye Treatment 1', ' جلسه معالجه للعين', 250.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(345, 'SV0051', 'Eye Treatment 2', ' جلستين معالجه للعين', 450.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(346, 'SV0052', 'Facial Firming', 'شد الوجه', 1000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(347, 'SV0053', 'Oxygen session', 'جلسه اكسجين', 750.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(348, 'SV0054', 'Collagen  session', 'جلسه كولاجين', 1000.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(349, 'SV0055', 'Vitamin C  session', 'جلسه فيتامين سي', 750.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(350, 'SV0056', 'Neutrition    consultation', 'استشاره تغذيه', 250.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(351, 'SV0057', 'Neutrition   4 ', 'تغذيه   4', 950.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(352, 'SV0058', 'Neutrition    6', 'تغذيه   6', 1450.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(353, 'SV0059', 'Neutrition    8', 'تغذيه   8', 1950.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(354, 'SV0060', 'Neutrition    10', 'تغذيه   10', 2450.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(355, 'SV0061', 'Neutrition    12', 'تغذيه   12', 2950.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(356, 'SV0062', 'Consulation ( consultant )', 'كشفيه الاستشاري', 400.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(357, 'SV0063', 'Consultation senior registrar ', 'كشفيه النائب الاول', 300.00, '', 8, NULL, '2021-05-08 17:13:58', '2021-05-08 17:13:58'),
(358, 'HD001', 'this is test', 'الفحص ألسريري', 1.00, 'test', 9, '2021-05-12 03:10:05', '2021-05-12 03:09:37', '2021-05-12 03:10:05');

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path_icon` text COLLATE utf8mb4_unicode_ci,
  `order_show` int(11) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`id`, `name`, `name_ar`, `path_icon`, `order_show`, `is_active`, `parent_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Diagnosis and oral care', 'التشخيص والرعاية عن طريق الفم', 'dXBsb2Fkcy9maWxlcy9zdmcvLzE2MjA0ODgxNzVfZGlhZy5zdmc=', 1, 1, NULL, NULL, '2021-05-08 17:13:06', '2021-05-10 17:17:40'),
(2, 'Restorative Treatment (Operative)', 'العلاج التحفظي', 'dXBsb2Fkcy9maWxlcy9zdmcvLzE2MjA0ODgxODdfd2F0Y2guc3Zn', 1, 1, NULL, NULL, '2021-05-08 17:13:06', '2021-05-10 17:07:15'),
(3, 'Restorative Treatment (Endodontic)', 'علاج الجذور', 'dXBsb2Fkcy9maWxlcy9zdmcvLzE2MjA1ODM5NTJfcmN0LnN2Zw==', 1, 1, NULL, NULL, '2021-05-08 17:13:06', '2021-05-10 17:07:15'),
(4, 'Pedodontics', 'طب أسنان الأطفال', 'dXBsb2Fkcy9maWxlcy9zdmcvLzE2MjA1ODM5NjJfYW5hc3Quc3Zn', 1, 1, NULL, NULL, '2021-05-08 17:13:06', '2021-05-10 17:07:15'),
(5, 'Prosthodontics', 'التركيبات الثابته و المتحركة', 'dXBsb2Fkcy9maWxlcy9zdmcvLzE2MjA1ODQwMTFfaHlnLnN2Zw==', 1, 1, NULL, NULL, '2021-05-08 17:13:06', '2021-05-10 17:07:15'),
(6, 'Oral Surgery ', 'جراحة الفم', 'dXBsb2Fkcy9maWxlcy9zdmcvLzE2MjA0ODg2NzBfZXh0LnN2Zw==', 1, 1, NULL, NULL, '2021-05-08 17:13:06', '2021-05-10 17:07:15'),
(7, 'Orthodontics', 'تقويم الأسنان', 'dXBsb2Fkcy9maWxlcy9zdmcvLzE2MjA0ODg3MDhfb3J0aG8uc3Zn', 1, 1, NULL, NULL, '2021-05-08 17:13:06', '2021-05-10 17:07:15'),
(8, 'Derma', 'جلديه', 'dXBsb2Fkcy9maWxlcy9zdmcvLzE2MjA1ODQwMjRfaW1wLnN2Zw==', 1, 1, NULL, NULL, '2021-05-08 17:13:06', '2021-05-10 17:07:15'),
(9, 'category 1', 'العلاج التحفظي', 'dXBsb2Fkcy9maWxlcy9zdmcvLzE2MjA3Nzc4OTBfZGlhZy5zdmc=', 1, 1, NULL, '2021-05-12 03:10:28', '2021-05-12 03:04:50', '2021-05-12 03:10:28');

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
(1, 'Admin', 'admin', 'admin@gmail.com', NULL, '$2y$10$A5rTPLgqAAg9bQeCeSZh5uuwI32fuPMmoMj3wfnJKsEdZ2Mm7hlh.', 'admin', 1, 'jvanN2E3tVGBmbptXofySF2XDivhSfN7fcobKiISMSdZKaepYGVgZ72QvlZY', '2021-02-11 02:46:17', '2021-04-19 20:44:58'),
(2, 'Doctor Kevin', 'admin1', 'doctor1@gmail.com', NULL, '$2y$10$0EXHkaz0gHtck1ajmzPsyOWJoaSyh5WITfT39eq6gl3/Cdlh1wD.e', 'doctor', 1, NULL, '2021-02-11 03:29:29', '2021-02-14 07:42:28'),
(3, 'Reception Dol', 'admin2', 'reception1@gmail.com', NULL, '$2y$10$0EXHkaz0gHtck1ajmzPsyOWJoaSyh5WITfT39eq6gl3/Cdlh1wD.e', 'reception', 1, NULL, '2021-02-11 03:55:47', '2021-02-12 10:31:03'),
(6, 'Other2 Dol', 'admin4', 'other2@gmail.com', NULL, '$2y$10$0EXHkaz0gHtck1ajmzPsyOWJoaSyh5WITfT39eq6gl3/Cdlh1wD.e', 'doctor', 1, NULL, '2021-02-12 10:34:58', '2021-02-22 01:45:29'),
(7, 'Other3 De', 'admin5', 'other3@gmail.com', NULL, '$2y$10$0EXHkaz0gHtck1ajmzPsyOWJoaSyh5WITfT39eq6gl3/Cdlh1wD.e', 'reception', 0, NULL, '2021-02-12 10:41:49', '2021-02-12 11:11:41'),
(8, 'Doctor Dol', 'admin6', 'doctor2@gmail.com', NULL, '$2y$10$0EXHkaz0gHtck1ajmzPsyOWJoaSyh5WITfT39eq6gl3/Cdlh1wD.e', 'doctor', 1, NULL, '2021-02-15 07:08:44', '2021-02-22 01:45:20'),
(9, 'mohamed25', 'admin7', 'mohamedabdelateef25@gmail.com', NULL, '$2y$10$0EXHkaz0gHtck1ajmzPsyOWJoaSyh5WITfT39eq6gl3/Cdlh1wD.e', 'admin', 1, NULL, '2021-03-13 09:39:45', '2021-03-13 09:39:45'),
(10, 'mohamed25', 'admin25', NULL, NULL, '$2y$10$FKVcrbMnRWkDPwmceqflx.OgWMSdpDNufyOAiJBJswys75lczVg56', 'admin', 1, NULL, '2021-03-15 03:26:08', '2021-03-15 03:26:08'),
(11, 'hidaoui', 'hidaoui.mouhssin@gmail.com', NULL, NULL, '$2y$10$4p5I5D3IZYo3pXze6A2pr.I7qMC0cCzwFllIpZdtBeWLp8aEZQvca', 'none', 1, NULL, '2021-04-18 17:25:44', '2021-04-19 20:48:13'),
(12, 'mouhssin', 'hidaoui1', NULL, NULL, '$2y$10$60VMvvqvDJaRSK2AMrkRYutzQtvUvOS424Snyfxma3xrc0Cl0mqjm', 'none', 0, NULL, '2021-04-18 17:41:12', '2021-04-18 17:41:12'),
(13, 'Doctor John', 'admin', 'doctorjohn@gmail.com', NULL, '$2y$10$0EXHkaz0gHtck1ajmzPsyOWJoaSyh5WITfT39eq6gl3/Cdlh1wD.e', 'doctor', 1, NULL, '2021-02-11 03:29:29', '2021-02-14 07:42:28');

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctors_user_id_foreign` (`user_id`);

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
-- Indexes for table `help_indexes`
--
ALTER TABLE `help_indexes`
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
-- Indexes for table `inv_invoices`
--
ALTER TABLE `inv_invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inv_invoices_doctor_id_foreign` (`doctor_id`),
  ADD KEY `inv_invoices_patient_id_foreign` (`patient_id`);

--
-- Indexes for table `inv_invoice_payments`
--
ALTER TABLE `inv_invoice_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inv_invoice_payments_invoice_id_foreign` (`invoice_id`);

--
-- Indexes for table `inv_invoice_refunds`
--
ALTER TABLE `inv_invoice_refunds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inv_invoice_refunds_invoice_id_foreign` (`invoice_id`);

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
-- Indexes for table `pr_procedure_service_items`
--
ALTER TABLE `pr_procedure_service_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pr_procedure_service_items_doctor_id_foreign` (`doctor_id`),
  ADD KEY `pr_procedure_service_items_patient_id_foreign` (`patient_id`),
  ADD KEY `pr_procedure_service_items_service_id_foreign` (`service_id`),
  ADD KEY `pr_procedure_service_items_invoice_id_foreign` (`invoice_id`);

--
-- Indexes for table `pr_tooths`
--
ALTER TABLE `pr_tooths`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `services_code_unique` (`code`),
  ADD KEY `services_category_id_foreign` (`category_id`);

--
-- Indexes for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_categories_parent_id_foreign` (`parent_id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- AUTO_INCREMENT for table `help_indexes`
--
ALTER TABLE `help_indexes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoicelists`
--
ALTER TABLE `invoicelists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `invoice_lists`
--
ALTER TABLE `invoice_lists`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `inv_invoices`
--
ALTER TABLE `inv_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inv_invoice_payments`
--
ALTER TABLE `inv_invoice_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `inv_invoice_refunds`
--
ALTER TABLE `inv_invoice_refunds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `officetimes`
--
ALTER TABLE `officetimes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `patientprofiles`
--
ALTER TABLE `patientprofiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `patient_notes`
--
ALTER TABLE `patient_notes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `patient_storage`
--
ALTER TABLE `patient_storage`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pr_procedure_service_items`
--
ALTER TABLE `pr_procedure_service_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pr_tooths`
--
ALTER TABLE `pr_tooths`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=359;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `storages`
--
ALTER TABLE `storages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `inv_invoices`
--
ALTER TABLE `inv_invoices`
  ADD CONSTRAINT `inv_invoices_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `inv_invoices_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `inv_invoice_payments`
--
ALTER TABLE `inv_invoice_payments`
  ADD CONSTRAINT `inv_invoice_payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `inv_invoices` (`id`);

--
-- Constraints for table `inv_invoice_refunds`
--
ALTER TABLE `inv_invoice_refunds`
  ADD CONSTRAINT `inv_invoice_refunds_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `inv_invoices` (`id`);

--
-- Constraints for table `pr_procedure_service_items`
--
ALTER TABLE `pr_procedure_service_items`
  ADD CONSTRAINT `pr_procedure_service_items_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `pr_procedure_service_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `inv_invoices` (`id`),
  ADD CONSTRAINT `pr_procedure_service_items_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  ADD CONSTRAINT `pr_procedure_service_items_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`);

--
-- Constraints for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD CONSTRAINT `service_categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `service_categories` (`id`);
