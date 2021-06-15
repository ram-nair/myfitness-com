-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2021 at 05:17 AM
-- Server version: 10.1.33-MariaDB
-- PHP Version: 7.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `myfitness_db`
--

DELIMITER $$
--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `GetAncestry` (`GivenID` INT) RETURNS VARCHAR(1024) CHARSET latin1 BEGIN
            DECLARE rv VARCHAR(1024);
            DECLARE cm CHAR(1);
            DECLARE ch INT;

            SET rv = '';
            SET cm = '';
            SET ch = GivenID;
            WHILE ch > 0 DO
                SELECT IFNULL(parent_cat_id,-1) INTO ch FROM
                (SELECT parent_cat_id FROM categories WHERE id = ch) A;
                IF ch > 0 THEN
                    SET rv = CONCAT(rv,cm,ch);
                    SET cm = ',';
                END IF;
            END WHILE;
            RETURN rv;
        END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `log_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `properties` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`) VALUES
('087df310-c617-11eb-b0c1-09f19736bc06', 'product', 'updated', '1c12c8d0-bd41-11eb-85d3-07d68830e9f3', 'App\\Product', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', '{\"attributes\":{\"quantity\":\"100\"},\"old\":{\"quantity\":null}}', '2021-06-05 14:29:39', '2021-06-05 14:29:39'),
('104571c0-be98-11eb-b31c-993255e05d1b', 'product', 'updated', '1c12c8d0-bd41-11eb-85d3-07d68830e9f3', 'App\\Product', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', '{\"attributes\":{\"discount_price\":50,\"discount_start_date\":\"0000-00-00\",\"discount_end_date\":\"0000-00-00\"},\"old\":{\"discount_price\":null,\"discount_start_date\":null,\"discount_end_date\":null}}', '2021-05-27 01:33:08', '2021-05-27 01:33:08'),
('1c8377a0-bd41-11eb-994e-43b5e55bdbe4', 'product', 'created', '1c12c8d0-bd41-11eb-85d3-07d68830e9f3', 'App\\Product', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', '{\"attributes\":{\"brand_id\":null,\"sku\":\"trtr\",\"user_id\":null,\"category_id\":1,\"subcategory_id\":null,\"child_category_id\":43,\"name\":\"styerty\",\"size\":null,\"size_qty\":null,\"size_price\":null,\"color\":', '2021-05-25 08:38:11', '2021-05-25 08:38:11'),
('581f9000-c993-11eb-ac63-176a0cbdce20', 'store', 'updated', '517e8990-b9dc-11eb-a247-8926cbd82353', 'App\\Store', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', '{\"attributes\":{\"name\":\"my fitness\",\"mobile\":\"76857689478\",\"description\":\"<p>sfs<\\/p>\",\"location\":\"sfsfdg\",\"min_order_amount\":\"56565\",\"vendor_id\":\"1\",\"by_user_id\":\"a2a20280-b49a-11eb-ac5c-cdc1', '2021-06-10 00:57:04', '2021-06-10 00:57:04'),
('5990f110-bdd7-11eb-9b8b-47f6e0c3caf8', 'product', 'updated', 'fa475ca0-bc3d-11eb-bc0c-5f7d95cfdf6d', 'App\\Product', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', '{\"attributes\":{\"category_id\":1,\"sub_category_id\":45,\"child_category_id\":43,\"name\":\"test-yup\",\"unit_price\":500,\"in_stock\":1,\"hot_deal\":1,\"hot_sale\":1,\"meta_tag\":\"test-test-u\",\"meta_description', '2021-05-26 02:33:38', '2021-05-26 02:33:38'),
('5ad93fe0-bdf0-11eb-b2ca-6b224d3ca0a9', 'product', 'created', '5a64eb80-bdf0-11eb-aacd-f167ac54f25d', 'App\\Product', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', '{\"attributes\":{\"brand_id\":null,\"sku\":\"jk\",\"user_id\":null,\"category_id\":1,\"sub_category_id\":45,\"child_category_id\":null,\"quantity\":\"22\",\"name\":\"test\",\"size\":null,\"size_qty\":null,\"size_price\":n', '2021-05-26 05:32:37', '2021-05-26 05:32:37'),
('66d38ab0-c1cb-11eb-94b5-2fcd9274664a', 'product', 'updated', '1c12c8d0-bd41-11eb-85d3-07d68830e9f3', 'App\\Product', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', '{\"attributes\":{\"hot_sale\":0},\"old\":{\"hot_sale\":1}}', '2021-05-31 03:18:11', '2021-05-31 03:18:11'),
('82737a80-c1cb-11eb-b031-17d4b98a2a8f', 'product', 'updated', '1c12c8d0-bd41-11eb-85d3-07d68830e9f3', 'App\\Product', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', '{\"attributes\":{\"hot_sale\":1},\"old\":{\"hot_sale\":0}}', '2021-05-31 03:18:57', '2021-05-31 03:18:57'),
('845d4ea0-c1cb-11eb-8d3a-9be39250c567', 'product', 'updated', '5a64eb80-bdf0-11eb-aacd-f167ac54f25d', 'App\\Product', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', '{\"attributes\":{\"hot_sale\":1},\"old\":{\"hot_sale\":null}}', '2021-05-31 03:19:00', '2021-05-31 03:19:00'),
('86309500-c1cb-11eb-84ec-2550c6080349', 'product', 'updated', 'fa475ca0-bc3d-11eb-bc0c-5f7d95cfdf6d', 'App\\Product', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', '{\"attributes\":{\"hot_sale\":0},\"old\":{\"hot_sale\":1}}', '2021-05-31 03:19:03', '2021-05-31 03:19:03'),
('89b28960-c1df-11eb-84f3-c5e1c5d64f32', 'product', 'updated', '1c12c8d0-bd41-11eb-85d3-07d68830e9f3', 'App\\Product', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', '{\"attributes\":{\"hot_sale\":0},\"old\":{\"hot_sale\":1}}', '2021-05-31 05:42:19', '2021-05-31 05:42:19'),
('90012760-c1df-11eb-a277-4703b73d7f60', 'product', 'updated', '1c12c8d0-bd41-11eb-85d3-07d68830e9f3', 'App\\Product', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', '{\"attributes\":{\"hot_sale\":1},\"old\":{\"hot_sale\":0}}', '2021-05-31 05:42:30', '2021-05-31 05:42:30'),
('9b032dc0-c1cb-11eb-8d6e-67342d0b9080', 'product', 'updated', 'fa475ca0-bc3d-11eb-bc0c-5f7d95cfdf6d', 'App\\Product', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', '{\"attributes\":{\"hot_sale\":1},\"old\":{\"hot_sale\":0}}', '2021-05-31 03:19:38', '2021-05-31 03:19:38'),
('9f7acee0-c617-11eb-bdeb-bd3664b7b35b', 'product', 'updated', '5a64eb80-bdf0-11eb-aacd-f167ac54f25d', 'App\\Product', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', '{\"attributes\":{\"quantity\":\"23\"},\"old\":{\"quantity\":\"22\"}}', '2021-06-05 14:33:52', '2021-06-05 14:33:52'),
('bd5009a0-c617-11eb-b555-e568fa5d7f3c', 'product', 'updated', '5a64eb80-bdf0-11eb-aacd-f167ac54f25d', 'App\\Product', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', '{\"attributes\":{\"quantity\":\"24\"},\"old\":{\"quantity\":\"23\"}}', '2021-06-05 14:34:42', '2021-06-05 14:34:42'),
('c2cb3af0-ccb9-11eb-938c-9b3ab848050c', 'store', 'updated', '517e8990-b9dc-11eb-a247-8926cbd82353', 'App\\Store', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', '{\"attributes\":{\"description\":\"test\"},\"old\":{\"description\":\"<p>sfs<\\/p>\"}}', '2021-06-14 01:09:37', '2021-06-14 01:09:37'),
('c6edee00-c1d4-11eb-9231-59f02ba145af', 'product', 'updated', 'fa475ca0-bc3d-11eb-bc0c-5f7d95cfdf6d', 'App\\Product', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', '{\"attributes\":{\"hot_sale\":0},\"old\":{\"hot_sale\":1}}', '2021-05-31 04:25:17', '2021-05-31 04:25:17'),
('c7e38140-bdd6-11eb-aadf-c99f4a856265', 'product', 'updated', '1c12c8d0-bd41-11eb-85d3-07d68830e9f3', 'App\\Product', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', '{\"attributes\":{\"category_id\":2,\"sub_category_id\":46,\"child_category_id\":null,\"unit_price\":100},\"old\":{\"category_id\":1,\"sub_category_id\":0,\"child_category_id\":43,\"unit_price\":0}}', '2021-05-26 02:29:33', '2021-05-26 02:29:33'),
('d6c16ed0-c371-11eb-a4db-9b915953f06e', 'product', 'updated', 'fa475ca0-bc3d-11eb-bc0c-5f7d95cfdf6d', 'App\\Product', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', '{\"attributes\":{\"hot_sale\":1},\"old\":{\"hot_sale\":0}}', '2021-06-02 05:42:06', '2021-06-02 05:42:06'),
('d99f22f0-c371-11eb-a115-7fa069abc6e0', 'product', 'updated', '1c12c8d0-bd41-11eb-85d3-07d68830e9f3', 'App\\Product', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', '{\"attributes\":{\"hot_sale\":0},\"old\":{\"hot_sale\":1}}', '2021-06-02 05:42:11', '2021-06-02 05:42:11'),
('e7eb37e0-be9d-11eb-a54f-f7371ac0f50c', 'product', 'updated', '1c12c8d0-bd41-11eb-85d3-07d68830e9f3', 'App\\Product', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', '{\"attributes\":{\"discount_price\":55,\"discount_start_date\":\"2021-05-26\",\"discount_end_date\":\"2021-05-31\"},\"old\":{\"discount_price\":50,\"discount_start_date\":\"0000-00-00\",\"discount_end_date\":\"0000', '2021-05-27 02:14:57', '2021-05-27 02:14:57'),
('e91c3600-bdda-11eb-8269-69f16f36782d', 'product', 'updated', 'fa475ca0-bc3d-11eb-bc0c-5f7d95cfdf6d', 'App\\Product', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', '{\"attributes\":{\"quantity\":\"657\"},\"old\":{\"quantity\":null}}', '2021-05-26 02:59:07', '2021-05-26 02:59:07'),
('fa91d8e0-bc3d-11eb-b8f2-dd79eeb6504c', 'product', 'created', 'fa475ca0-bc3d-11eb-bc0c-5f7d95cfdf6d', 'App\\Product', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', '{\"attributes\":{\"brand_id\":null,\"sku\":\"j\",\"user_id\":null,\"category_id\":2,\"subcategory_id\":null,\"child_category_id\":null,\"name\":\"test\",\"photo\":null,\"size\":null,\"size_qty\":null,\"size_price\":null', '2021-05-24 01:43:14', '2021-05-24 01:43:14');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `admin_id`, `name`, `email`, `password`, `type`, `status`, `last_login`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
('17451660-c9f8-11eb-9a5f-affbf34add13', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'tester', 'tester@gmail.com', '$2y$10$He78nu6DFEmlwAXUxalGYu7VWRE4Y6XGuCb4MVyRs5STwteiHI1Be', 'admin', 1, NULL, NULL, '2021-06-10 12:58:14', '2021-06-10 12:58:14', NULL),
('a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'My Family Fitness', 'admin@myfamilyfitness.com', '$2y$10$HiwuJ51Zezh9vBVk/POvs..z04fqQEAcdpfwJ8O7uou0PDvhyHvi6', 'admin', 1, '2021-06-15 07:01:12', 'n8rIwbRAxXaoz6Sq3JLYmgTIFeso5KiFpQkcXcEe2DnfORkL6NxRObpprz0E', '2021-05-14 08:26:21', '2021-06-15 01:31:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `audits`
--

CREATE TABLE `audits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_id` bigint(20) UNSIGNED NOT NULL,
  `old_values` text COLLATE utf8mb4_unicode_ci,
  `new_values` text COLLATE utf8mb4_unicode_ci,
  `url` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(1023) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audits`
--

INSERT INTO `audits` (`id`, `user_type`, `user_id`, `admin_id`, `admin_type`, `event`, `auditable_type`, `auditable_id`, `old_values`, `new_values`, `url`, `ip_address`, `user_agent`, `tags`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-14 08:34:31', '2021-05-14 08:34:31'),
(2, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged Out', 'Logged Out', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-14 08:36:21', '2021-05-14 08:36:21'),
(3, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-14 08:38:47', '2021-05-14 08:38:47'),
(4, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged Out', 'Logged Out', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-14 08:39:16', '2021-05-14 08:39:16'),
(5, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-14 08:40:25', '2021-05-14 08:40:25'),
(6, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-15 02:02:11', '2021-05-15 02:02:11'),
(7, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', 'created', 'App\\Category', 1, '[]', '{\"name\":\"Active Outdoor\",\"description\":\"<p>Test<\\/p>\",\"parent_cat_id\":\"0\",\"image\":null,\"id\":1}', 'http://127.0.0.1:8000/admin/categories', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-15 02:57:27', '2021-05-15 02:57:27'),
(8, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', 'created', 'App\\Category', 2, '[]', '{\"name\":\"Indoor Fitness\",\"description\":\"<p>test<\\/p>\",\"parent_cat_id\":\"0\",\"image\":null,\"id\":2}', 'http://127.0.0.1:8000/admin/categories', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-15 02:57:53', '2021-05-15 02:57:53'),
(9, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', 'created', 'App\\Category', 3, '[]', '{\"name\":\"Active Kids\",\"description\":\"<p>test<\\/p>\",\"parent_cat_id\":\"0\",\"image\":null,\"id\":3}', 'http://127.0.0.1:8000/admin/categories', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-15 02:58:18', '2021-05-15 02:58:18'),
(10, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', 'created', 'App\\Category', 4, '[]', '{\"name\":\"Camping\",\"description\":\"<p>ddf<\\/p>\",\"parent_cat_id\":\"1\",\"image\":null,\"id\":4}', 'http://127.0.0.1:8000/admin/categories', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-15 03:16:33', '2021-05-15 03:16:33'),
(11, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-17 01:11:19', '2021-05-17 01:11:19'),
(12, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged Out', 'Logged Out', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-17 01:24:35', '2021-05-17 01:24:35'),
(13, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-17 01:25:15', '2021-05-17 01:25:15'),
(14, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-18 01:06:16', '2021-05-18 01:06:16'),
(15, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-18 05:05:59', '2021-05-18 05:05:59'),
(16, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-18 10:08:45', '2021-05-18 10:08:45'),
(17, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-19 00:29:51', '2021-05-19 00:29:51'),
(18, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', 'created', 'App\\Brand', 1, '[]', '{\"name\":\"puma\",\"description\":\"aa\",\"image\":null,\"id\":1}', 'http://127.0.0.1:8000/admin/brands', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-19 01:02:18', '2021-05-19 01:02:18'),
(19, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-19 12:18:55', '2021-05-19 12:18:55'),
(20, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-20 00:46:47', '2021-05-20 00:46:47'),
(21, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'App\\Admin', 'updated', 'App\\Brand', 1, '{\"description\":\"aa\"}', '{\"description\":\"online brands\"}', 'http://127.0.0.1:8000/admin/brands/1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-20 05:25:27', '2021-05-20 05:25:27'),
(22, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-20 11:55:02', '2021-05-20 11:55:02'),
(23, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-20 11:56:28', '2021-05-20 11:56:28'),
(24, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-21 00:47:09', '2021-05-21 00:47:09'),
(25, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-21 07:00:41', '2021-05-21 07:00:41'),
(26, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-22 11:34:13', '2021-05-22 11:34:13'),
(27, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged Out', 'Logged Out', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-22 11:37:32', '2021-05-22 11:37:32'),
(28, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-22 11:38:29', '2021-05-22 11:38:29'),
(29, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged Out', 'Logged Out', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-22 12:06:20', '2021-05-22 12:06:20'),
(30, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-22 12:06:53', '2021-05-22 12:06:53'),
(31, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-22 12:17:54', '2021-05-22 12:17:54'),
(32, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-23 12:23:25', '2021-05-23 12:23:25'),
(33, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-24 00:43:44', '2021-05-24 00:43:44'),
(34, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-25 00:47:30', '2021-05-25 00:47:30'),
(35, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-25 03:17:25', '2021-05-25 03:17:25'),
(36, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-25 08:08:45', '2021-05-25 08:08:45'),
(37, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-26 02:10:36', '2021-05-26 02:10:36'),
(38, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-26 09:08:40', '2021-05-26 09:08:40'),
(39, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-27 01:02:18', '2021-05-27 01:02:18'),
(40, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-27 06:01:01', '2021-05-27 06:01:01'),
(41, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36', NULL, '2021-05-28 01:09:27', '2021-05-28 01:09:27'),
(42, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36', NULL, '2021-05-28 07:27:04', '2021-05-28 07:27:04'),
(43, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36', NULL, '2021-05-29 07:38:00', '2021-05-29 07:38:00'),
(44, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36', NULL, '2021-05-31 01:33:52', '2021-05-31 01:33:52'),
(45, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36', NULL, '2021-06-01 01:17:30', '2021-06-01 01:17:30'),
(46, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36', NULL, '2021-06-01 05:40:03', '2021-06-01 05:40:03'),
(47, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36', NULL, '2021-06-01 16:16:41', '2021-06-01 16:16:41'),
(48, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36', NULL, '2021-06-02 03:26:43', '2021-06-02 03:26:43'),
(49, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36', NULL, '2021-06-04 09:39:07', '2021-06-04 09:39:07'),
(50, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36', NULL, '2021-06-05 12:38:22', '2021-06-05 12:38:22'),
(51, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36', NULL, '2021-06-07 00:23:00', '2021-06-07 00:23:00'),
(52, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36', NULL, '2021-06-08 00:07:49', '2021-06-08 00:07:49'),
(53, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36', NULL, '2021-06-09 00:33:34', '2021-06-09 00:33:34'),
(54, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36', NULL, '2021-06-09 13:02:33', '2021-06-09 13:02:33'),
(55, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36', NULL, '2021-06-10 00:27:04', '2021-06-10 00:27:04'),
(56, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36', NULL, '2021-06-10 12:57:16', '2021-06-10 12:57:16'),
(57, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36', NULL, '2021-06-11 00:31:32', '2021-06-11 00:31:32'),
(58, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged Out', 'Logged Out', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36', NULL, '2021-06-11 01:10:53', '2021-06-11 01:10:53'),
(59, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36', NULL, '2021-06-14 01:06:26', '2021-06-14 01:06:26'),
(60, NULL, NULL, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, 'Logged In', 'App\\Admin', 0, NULL, NULL, 'http://127.0.0.1:8000/admin/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36', NULL, '2021-06-15 01:31:13', '2021-06-15 01:31:13');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` enum('store','external') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'store',
  `url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `by_user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `name`, `image`, `description`, `type`, `url`, `status`, `by_user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
('517e8990-b9dc-11eb-a247-8926cbd82350', 'Top Banner', 'banner1074346726.png', '<p>top Banner</p>', 'store', NULL, 1, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', '2021-05-21 00:59:07', '2021-05-21 00:59:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `banner_stores`
--

CREATE TABLE `banner_stores` (
  `banner_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `description`, `image`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'puma', 'online brands', NULL, NULL, '2021-05-19 01:02:18', '2021-05-20 05:25:27');

-- --------------------------------------------------------

--
-- Table structure for table `business_type_categories`
--

CREATE TABLE `business_type_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_type_id` bigint(20) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `show_disclaimer` tinyint(1) DEFAULT '0',
  `disclaimer` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `store_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `parent_cat_id` int(10) UNSIGNED DEFAULT '0',
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `parent_cat_id`, `image`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Active Outdoor', '<p>Test</p>', 0, NULL, NULL, '2021-05-15 02:57:27', '2021-05-15 02:57:27'),
(2, 'Indoor Fitness', '<p>test</p>', 0, 'category871390871.jpg', NULL, '2021-05-15 02:57:53', '2021-06-07 00:26:52'),
(3, 'Active Kids', '<p>test</p>', 0, 'category1008804862.png', NULL, '2021-05-15 02:58:18', '2021-06-10 13:59:33'),
(4, 'Camping', '<p>ddf</p>', 1, NULL, NULL, '2021-05-15 03:16:33', '2021-05-15 03:16:33');

-- --------------------------------------------------------

--
-- Table structure for table `childcategories`
--

CREATE TABLE `childcategories` (
  `id` int(191) NOT NULL,
  `subcategory_id` int(191) NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `childcategories`
--

INSERT INTO `childcategories` (`id`, `subcategory_id`, `name`, `slug`, `deleted_at`, `created_at`, `updated_at`, `status`) VALUES
(43, 45, 'Home use Treadmill', '', NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `communities`
--

CREATE TABLE `communities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_community` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `banner_url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ecommerce_banner`
--

CREATE TABLE `ecommerce_banner` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `in_category` tinyint(4) NOT NULL DEFAULT '0',
  `in_product` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `by_user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gifts`
--

CREATE TABLE `gifts` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `balance_amt` double(8,2) DEFAULT NULL,
  `expire_at` date DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `is_redeem` tinyint(4) NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gifts`
--

INSERT INTO `gifts` (`id`, `code`, `balance_amt`, `expire_at`, `status`, `is_redeem`, `deleted_at`, `created_at`, `updated_at`) VALUES
('1b483c50-bfa4-11eb-8efd-65c3d4ad74fb', 'MFO0RACBCQ', 550.00, '2021-06-25', 1, 0, NULL, '2021-05-28 09:31:51', '2021-05-28 09:34:27');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2020_02_18_000000_create_admins_table', 1),
(3, '2020_02_18_100000_create_users_table', 1),
(4, '2020_02_18_200000_create_password_resets_table', 1),
(5, '2020_02_18_300000_create_failed_jobs_table', 1),
(6, '2020_02_18_400000_create_user_active_tokens_table', 1),
(7, '2020_02_18_500000_create_permission_tables', 1),
(8, '2020_07_21_105801_create_business_types_table', 1),
(9, '2020_07_21_112635_create_business_type_categories_table', 1),
(10, '2020_07_22_054655_create_vendors_table', 1),
(11, '2020_07_22_054811_create_stores_table', 1),
(12, '2020_07_22_094454_create_brands_table', 1),
(13, '2020_07_22_181500_create_categories_table', 1),
(14, '2020_07_23_030554_create_slots_table', 1),
(15, '2020_07_23_124526_create_products_table', 1),
(16, '2020_07_23_131253_create_product_images_table', 1),
(17, '2020_07_23_132104_create_product_categories_table', 1),
(18, '2020_07_23_234137_create_settings_table', 1),
(19, '2020_07_24_050617_create_user_communities_table', 1),
(20, '2020_07_24_071939_create_communities_table', 1),
(21, '2020_07_24_075408_create_product_stores_table', 1),
(22, '2020_07_25_183720_create_carts_table', 1),
(23, '2020_07_26_005308_create_user_addresses_table', 1),
(24, '2020_07_28_125148_create_orders_table', 1),
(25, '2020_07_28_164832_creta_order_items_table', 1),
(26, '2020_07_30_074813_create_store_favourites_table', 1),
(27, '2020_08_02_123305_alter_stores_table', 1),
(28, '2020_08_05_084330_create_banners_table', 1),
(29, '2020_08_05_162952_create_ecommerce_banner_table', 1),
(30, '2020_08_06_122232_add_twilio_id_to_users_table', 1),
(31, '2020_08_06_133524_create_user_notifications_table', 1),
(32, '2020_08_06_144517_alter_ecommerce_banner_table', 1),
(33, '2020_08_07_065406_create_report_problem_table', 1),
(34, '2020_08_08_081447_alter_store_table_add_sap_fields', 1),
(35, '2020_08_09_080749_create_user_payment_methods_table', 1),
(36, '2020_08_10_172151_alter_order_table', 1),
(37, '2020_08_11_171312_alter_order_table_add_card_id', 1),
(38, '2020_08_11_190208_create_order_payments_table', 1),
(39, '2020_08_13_181558_alter_banners_table_ecommer_field', 1),
(40, '2020_08_14_065847_create_order_status_table', 1),
(41, '2020_08_15_064510_add_stored_procedure_get_parent_category', 1),
(42, '2020_08_16_062827_alter_order_payments_table_amount_field', 1),
(43, '2020_08_25_225701_alter_stores_table_additional_name_fields', 1),
(44, '2020_08_26_142717_create_audits_table', 1),
(45, '2020_09_04_162401_create_vlog_blog_category_table', 1),
(46, '2020_09_04_162408_create_vlog_blog_author_table', 1),
(47, '2020_09_04_162420_create_vlog_blog_table', 1),
(48, '2020_09_04_164629_create_vlog_blog_author_folowers_table', 1),
(49, '2020_09_04_165133_create_vlog_blog_images_table', 1),
(50, '2020_09_06_113544_alter_stores_table_gender_preference_field', 1),
(51, '2020_09_07_122815_create_activity_log_table', 1),
(52, '2020_09_08_105502_create_offer_category_table', 1),
(53, '2020_09_08_113202_create_offer_brand_table', 1),
(54, '2020_09_09_145254_create_offer_table', 1),
(55, '2020_09_09_153638_add_category_id_into_offer_brand_table', 1),
(56, '2020_09_09_184628_change_coupon_code_into_offer_table', 1),
(57, '2020_09_11_090250_change_columns_into_vlog_blog_author_table', 1),
(58, '2020_09_11_095135_add_email_into_offer_brand_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_uuid`) VALUES
(1, 'App\\Admin', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0'),
(2, 'App\\Admin', '17451660-c9f8-11eb-9a5f-affbf34add13');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) DEFAULT '0' COMMENT '0=send 1=not send',
  `userId` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `data` text COLLATE utf8mb4_unicode_ci,
  `categoryId` int(11) DEFAULT NULL,
  `messageType` int(11) DEFAULT NULL COMMENT '1=Order,2=general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offer`
--

CREATE TABLE `offer` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redeem_text` longtext COLLATE utf8mb4_unicode_ci,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `purchase_validity` int(11) NOT NULL,
  `coupon_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `by_user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offer_brand`
--

CREATE TABLE `offer_brand` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `working_start_hour` time DEFAULT NULL,
  `working_end_hour` time DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `location` text COLLATE utf8mb4_unicode_ci,
  `latitude` double(15,6) DEFAULT NULL,
  `longitude` double(15,6) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `by_user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offer_category`
--

CREATE TABLE `offer_category` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `by_user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivery_address` text COLLATE utf8mb4_unicode_ci,
  `amount_exclusive_vat` decimal(8,2) NOT NULL,
  `vat_amount` decimal(8,2) NOT NULL,
  `service_charge` double(8,2) DEFAULT NULL,
  `vat_percentage` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` double(8,2) NOT NULL,
  `payment_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `card_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scheduled_notes` text COLLATE utf8mb4_unicode_ci,
  `payment_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 Pending, 1 Completed, 2 Cancelled, 3 Failed',
  `order_status` enum('Pending','Processing','Completed','Declined','Cancel','Delivered','Cancellation Requested','Cancellation Confirmed','Return completed','Return Request') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `rating` decimal(8,2) DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_price` double(8,2) NOT NULL,
  `quantity` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_payments`
--

CREATE TABLE `order_payments` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double(8,2) DEFAULT NULL,
  `si_charge_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '0 for success, 1 for failure',
  `si_charge_txn_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '0 for success, 1 for failure',
  `reference_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_response` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_status`
--

CREATE TABLE `order_status` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('submitted','assigned','out_for_delivery','delivered','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'submitted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'settings', 'admin', '2021-05-14 08:26:21', '2021-05-14 08:26:21'),
(2, 'user_create', 'admin', '2021-05-14 08:26:21', '2021-05-14 08:26:21'),
(3, 'user_read', 'admin', '2021-05-14 08:26:21', '2021-05-14 08:26:21'),
(4, 'user_update', 'admin', '2021-05-14 08:26:21', '2021-05-14 08:26:21'),
(5, 'user_delete', 'admin', '2021-05-14 08:26:21', '2021-05-14 08:26:21'),
(6, 'role_create', 'admin', '2021-05-14 08:26:21', '2021-05-14 08:26:21'),
(7, 'role_read', 'admin', '2021-05-14 08:26:22', '2021-05-14 08:26:22'),
(8, 'role_update', 'admin', '2021-05-14 08:26:22', '2021-05-14 08:26:22'),
(9, 'role_delete', 'admin', '2021-05-14 08:26:22', '2021-05-14 08:26:22'),
(10, 'permission_create', 'admin', '2021-05-14 08:26:22', '2021-05-14 08:26:22'),
(11, 'permission_read', 'admin', '2021-05-14 08:26:22', '2021-05-14 08:26:22'),
(12, 'permission_update', 'admin', '2021-05-14 08:26:22', '2021-05-14 08:26:22'),
(13, 'permission_delete', 'admin', '2021-05-14 08:26:22', '2021-05-14 08:26:22'),
(14, 'store_create', 'admin', '2021-05-14 08:26:22', '2021-05-14 08:26:22'),
(15, 'store_read', 'admin', '2021-05-14 08:26:22', '2021-05-14 08:26:22'),
(16, 'store_update', 'admin', '2021-05-14 08:26:22', '2021-05-14 08:26:22'),
(17, 'store_delete', 'admin', '2021-05-14 08:26:22', '2021-05-14 08:26:22'),
(22, 'store_create', 'vendor', '2021-05-14 08:26:22', '2021-05-14 08:26:22'),
(23, 'store_read', 'vendor', '2021-05-14 08:26:22', '2021-05-14 08:26:22'),
(24, 'store_update', 'vendor', '2021-05-14 08:26:23', '2021-05-14 08:26:23'),
(25, 'store_delete', 'vendor', '2021-05-14 08:26:23', '2021-05-14 08:26:23'),
(26, 'brand_create', 'admin', '2021-05-14 08:26:23', '2021-05-14 08:26:23'),
(27, 'brand_read', 'admin', '2021-05-14 08:26:23', '2021-05-14 08:26:23'),
(28, 'brand_update', 'admin', '2021-05-14 08:26:23', '2021-05-14 08:26:23'),
(29, 'brand_delete', 'admin', '2021-05-14 08:26:23', '2021-05-14 08:26:23'),
(30, 'setting_create', 'admin', '2021-05-14 08:26:26', '2021-05-14 08:26:26'),
(31, 'setting_read', 'admin', '2021-05-14 08:26:26', '2021-05-14 08:26:26'),
(32, 'setting_update', 'admin', '2021-05-14 08:26:26', '2021-05-14 08:26:26'),
(33, 'setting_delete', 'admin', '2021-05-14 08:26:26', '2021-05-14 08:26:26'),
(34, 'category_create', 'admin', '2021-05-14 08:26:27', '2021-05-14 08:26:27'),
(35, 'category_update', 'admin', '2021-05-14 08:26:27', '2021-05-14 08:26:27'),
(36, 'category_delete', 'admin', '2021-05-14 08:26:27', '2021-05-14 08:26:27'),
(37, 'banner_create', 'admin', '2021-05-14 08:26:27', '2021-05-14 08:26:27'),
(38, 'banner_read', 'admin', '2021-05-14 08:26:27', '2021-05-14 08:26:27'),
(39, 'banner_update', 'admin', '2021-05-14 08:26:27', '2021-05-14 08:26:27'),
(40, 'banner_delete', 'admin', '2021-05-14 08:26:27', '2021-05-14 08:26:27'),
(55, 'ecomproduct_create', 'admin', '2021-05-14 08:26:28', '2021-05-14 08:26:28'),
(56, 'ecomproduct_read', 'admin', '2021-05-14 08:26:28', '2021-05-14 08:26:28'),
(57, 'ecomproduct_update', 'admin', '2021-05-14 08:26:28', '2021-05-14 08:26:28'),
(58, 'ecomproduct_delete', 'admin', '2021-05-14 08:26:28', '2021-05-14 08:26:28'),
(59, 'ecomproduct_create', 'store', '2021-05-14 08:26:28', '2021-05-14 08:26:28'),
(60, 'ecomproduct_read', 'store', '2021-05-14 08:26:28', '2021-05-14 08:26:28'),
(61, 'ecomproduct_update', 'store', '2021-05-14 08:26:28', '2021-05-14 08:26:28'),
(62, 'ecomproduct_delete', 'store', '2021-05-14 08:26:28', '2021-05-14 08:26:28'),
(75, 'storeproduct_create', 'admin', '2021-05-14 08:26:29', '2021-05-14 08:26:29'),
(76, 'storeproduct_read', 'admin', '2021-05-14 08:26:29', '2021-05-14 08:26:29'),
(81, 'storeproduct_update', 'store', '2021-05-14 08:26:29', '2021-05-14 08:26:29'),
(82, 'storeproduct_delete', 'store', '2021-05-14 08:26:29', '2021-05-14 08:26:29'),
(83, 'categorybanner_create', 'admin', '2021-05-14 08:26:29', '2021-05-14 08:26:29'),
(84, 'categorybanner_read', 'admin', '2021-05-14 08:26:29', '2021-05-14 08:26:29'),
(85, 'categorybanner_update', 'admin', '2021-05-14 08:26:30', '2021-05-14 08:26:30'),
(86, 'categorybanner_delete', 'admin', '2021-05-14 08:26:30', '2021-05-14 08:26:30'),
(123, 'store_create', 'store', '2021-05-14 08:26:31', '2021-05-14 08:26:31'),
(124, 'store_read', 'store', '2021-05-14 08:26:31', '2021-05-14 08:26:31'),
(125, 'store_update', 'store', '2021-05-14 08:26:31', '2021-05-14 08:26:31'),
(126, 'store_delete', 'store', '2021-05-14 08:26:31', '2021-05-14 08:26:31'),
(127, 'order_read', 'admin', '2021-05-14 08:26:31', '2021-05-14 08:26:31'),
(128, 'order_read', 'store', '2021-05-14 08:26:31', '2021-05-14 08:26:31'),
(141, 'report_read', 'admin', '2021-05-14 08:26:32', '2021-05-14 08:26:32'),
(142, 'blogcategory_create', 'admin', '2021-05-14 08:26:32', '2021-05-14 08:26:32'),
(143, 'blogcategory_read', 'admin', '2021-05-14 08:26:32', '2021-05-14 08:26:32'),
(144, 'blogcategory_update', 'admin', '2021-05-14 08:26:32', '2021-05-14 08:26:32'),
(145, 'blogcategory_delete', 'admin', '2021-05-14 08:26:32', '2021-05-14 08:26:32'),
(149, 'blogauthor_delete', 'admin', '2021-05-14 08:26:32', '2021-05-14 08:26:32'),
(150, 'vlogblog_create', 'admin', '2021-05-14 08:26:32', '2021-05-14 08:26:32'),
(151, 'vlogblog_read', 'admin', '2021-05-14 08:26:32', '2021-05-14 08:26:32'),
(152, 'vlogblog_update', 'admin', '2021-05-14 08:26:32', '2021-05-14 08:26:32'),
(153, 'vlogblog_delete', 'admin', '2021-05-14 08:26:32', '2021-05-14 08:26:32'),
(154, 'offercategory_create', 'admin', '2021-05-14 08:26:32', '2021-05-14 08:26:32'),
(155, 'offercategory_read', 'admin', '2021-05-14 08:26:32', '2021-05-14 08:26:32'),
(156, 'offercategory_update', 'admin', '2021-05-14 08:26:32', '2021-05-14 08:26:32'),
(157, 'offercategory_delete', 'admin', '2021-05-14 08:26:32', '2021-05-14 08:26:32'),
(158, 'offerbrand_create', 'admin', '2021-05-14 08:26:32', '2021-05-14 08:26:32'),
(159, 'offerbrand_read', 'admin', '2021-05-14 08:26:33', '2021-05-14 08:26:33'),
(160, 'offerbrand_update', 'admin', '2021-05-14 08:26:33', '2021-05-14 08:26:33'),
(161, 'offerbrand_delete', 'admin', '2021-05-14 08:26:33', '2021-05-14 08:26:33'),
(162, 'offer_create', 'admin', '2021-05-14 08:26:33', '2021-05-14 08:26:33'),
(163, 'offer_read', 'admin', '2021-05-14 08:26:33', '2021-05-14 08:26:33'),
(164, 'offer_update', 'admin', '2021-05-14 08:26:33', '2021-05-14 08:26:33'),
(165, 'offer_delete', 'admin', '2021-05-14 08:26:33', '2021-05-14 08:26:33'),
(166, 'catalogmanagement_class', 'admin', '2021-05-14 08:26:33', '2021-05-14 08:26:33'),
(167, 'catalogmanagement_services', 'admin', '2021-05-14 08:26:33', '2021-05-14 08:26:33'),
(168, 'catalogmanagement_ecommerce', 'admin', '2021-05-14 08:26:33', '2021-05-14 08:26:33'),
(169, 'offerproduct_create', 'admin', '2021-05-14 08:26:33', '2021-05-14 08:26:33'),
(170, 'offerproduct_read', 'admin', '2021-05-14 08:26:33', '2021-05-14 08:26:33'),
(171, 'offerproduct_update', 'admin', '2021-05-14 08:26:33', '2021-05-14 08:26:33'),
(172, 'offerproduct_delete', 'admin', '2021-05-14 08:26:33', '2021-05-14 08:26:33');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `sub_category_id` bigint(20) UNSIGNED NOT NULL,
  `child_category_id` int(11) DEFAULT NULL,
  `unit_price` double(8,2) NOT NULL,
  `quantity` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_price` double(8,2) DEFAULT NULL,
  `discount_start_date` date DEFAULT NULL,
  `discount_end_date` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `in_stock` tinyint(4) DEFAULT NULL,
  `hot_sale` tinyint(4) DEFAULT '0',
  `hot_deal` tinyint(4) DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_tag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size_qty` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size_price` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_condition` tinyint(4) NOT NULL DEFAULT '1',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `by_user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `brand_id`, `name`, `sku`, `description`, `category_id`, `sub_category_id`, `child_category_id`, `unit_price`, `quantity`, `discount_price`, `discount_start_date`, `discount_end_date`, `status`, `in_stock`, `hot_sale`, `hot_deal`, `meta_title`, `meta_tag`, `meta_description`, `size`, `size_qty`, `size_price`, `color`, `tax`, `product_condition`, `featured`, `by_user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
('1c12c8d0-bd41-11eb-85d3-07d68830e9f3', NULL, 'Andriod', 'trtr', 'testing-2', 2, 46, NULL, 100.00, '100', 55.00, '2021-05-26', '2021-05-31', 0, 0, 0, NULL, NULL, 'sgfsdgf', '<p>hdsjfhsdjh</p>', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, '2021-05-25 08:38:10', '2021-06-05 14:29:38', NULL),
('5a64eb80-bdf0-11eb-aacd-f167ac54f25d', NULL, 'test', 'jk', NULL, 1, 45, NULL, 200.00, '24', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'etsting', '<p>sff</p>', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, '2021-05-26 05:32:37', '2021-06-05 14:34:42', NULL),
('fa475ca0-bc3d-11eb-bc0c-5f7d95cfdf6d', 1, 'test-yup', 'j', 'testing', 1, 45, 43, 500.00, '657', NULL, NULL, NULL, 1, 1, 1, 1, NULL, 'test-test-u', '<p>test-test</p>', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, '2021-05-24 01:43:14', '2021-06-02 05:42:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `full`, `created_at`, `updated_at`, `deleted_at`) VALUES
('e2d0c2c0-bdf0-11eb-894c-11413796c260', '5a64eb80-bdf0-11eb-aacd-f167ac54f25d', 'product1429229546.png', '2021-05-26 05:36:26', '2021-05-26 05:36:26', NULL),
('e3315040-bdf0-11eb-8f68-414fa8c0fe9f', '5a64eb80-bdf0-11eb-aacd-f167ac54f25d', 'product1579349981.png', '2021-05-26 05:36:26', '2021-05-26 06:31:45', '2021-05-26 06:31:45');

-- --------------------------------------------------------

--
-- Table structure for table `product_stores`
--

CREATE TABLE `product_stores` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price` double(8,2) NOT NULL,
  `ask_price` double(8,2) DEFAULT NULL,
  `stock` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_approved` tinyint(1) NOT NULL DEFAULT '0',
  `out_of_stock` tinyint(4) NOT NULL DEFAULT '0',
  `quantity_per_person` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_problem`
--

CREATE TABLE `report_problem` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `order_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super-admin', 'admin', '2021-05-14 08:26:23', '2021-05-14 08:26:23'),
(2, 'admin', 'admin', '2021-05-14 08:26:23', '2021-05-14 08:26:23'),
(3, 'store', 'store', '2021-05-14 08:26:23', '2021-05-14 08:26:23'),
(4, 'vendor', 'vendor', '2021-05-14 08:26:23', '2021-05-14 08:26:23'),
(5, 'customer', 'customer', '2021-05-14 08:26:23', '2021-05-14 08:26:23');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(3, 1),
(3, 2),
(4, 1),
(4, 2),
(5, 1),
(5, 2),
(6, 1),
(6, 2),
(7, 1),
(7, 2),
(8, 1),
(8, 2),
(9, 1),
(9, 2),
(10, 1),
(10, 2),
(11, 1),
(11, 2),
(12, 1),
(12, 2),
(13, 1),
(13, 2),
(14, 1),
(14, 2),
(15, 1),
(15, 2),
(16, 1),
(16, 2),
(17, 1),
(17, 2),
(22, 4),
(23, 4),
(24, 4),
(25, 4),
(26, 1),
(26, 2),
(27, 1),
(27, 2),
(28, 1),
(28, 2),
(29, 1),
(29, 2),
(30, 1),
(30, 2),
(31, 1),
(31, 2),
(32, 1),
(32, 2),
(33, 1),
(33, 2),
(34, 1),
(34, 2),
(35, 1),
(35, 2),
(36, 1),
(36, 2),
(37, 1),
(37, 2),
(38, 1),
(38, 2),
(39, 1),
(39, 2),
(40, 1),
(40, 2),
(55, 1),
(55, 2),
(56, 1),
(56, 2),
(57, 1),
(57, 2),
(58, 1),
(58, 2),
(59, 3),
(60, 3),
(61, 3),
(62, 3),
(75, 1),
(75, 2),
(76, 1),
(76, 2),
(81, 3),
(82, 3),
(83, 1),
(83, 2),
(84, 1),
(84, 2),
(85, 1),
(85, 2),
(86, 1),
(86, 2),
(123, 3),
(124, 3),
(125, 3),
(126, 3),
(127, 1),
(127, 2),
(128, 3),
(141, 1),
(141, 2),
(142, 1),
(142, 2),
(143, 1),
(143, 2),
(144, 1),
(144, 2),
(145, 1),
(145, 2),
(149, 1),
(149, 2),
(150, 1),
(150, 2),
(151, 1),
(151, 2),
(152, 1),
(152, 2),
(153, 1),
(153, 2),
(154, 1),
(154, 2),
(155, 1),
(155, 2),
(156, 1),
(156, 2),
(157, 1),
(157, 2),
(158, 1),
(158, 2),
(159, 1),
(159, 2),
(160, 1),
(160, 2),
(161, 1),
(161, 2),
(162, 1),
(162, 2),
(163, 1),
(163, 2),
(164, 1),
(164, 2),
(165, 1),
(165, 2),
(166, 1),
(166, 2),
(167, 1),
(167, 2),
(168, 1),
(168, 2),
(169, 1),
(169, 2),
(170, 1),
(170, 2),
(171, 1),
(171, 2),
(172, 1),
(172, 2);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'vat', '5', '2021-05-14 08:26:26', '2021-05-14 08:26:26'),
(2, 'currency', 'AED', '2021-05-14 08:26:26', '2021-05-20 12:06:14'),
(4, 'google_api_key', 'AIzaSyD8U8tkj8m2Qv4lLX2O34ufsFbF4pHQkPI', '2021-05-14 08:26:27', '2021-05-14 08:26:27'),
(5, 'min_stock', '25', '2021-05-14 08:26:27', '2021-05-14 08:26:27'),
(6, 'order_cancel_duration', '10', '2021-05-14 08:26:27', '2021-05-14 08:26:27'),
(7, 'amenity_booking_token', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF91c2VyIjoiMTA5MiIsInVzZXJuYW1lIjoic2VudGhpbC5tQGV4YWxvZ2ljLmNvIiwiaWF0IjoxNTk4MDg4NTQ1LCJleHAiOjE1OTgxMDY1NDV9.8PIjHKydoShcJxfwrcg1InUZJGITubVeiDHCfxJELOY', '2021-05-14 08:26:27', '2021-05-14 08:26:27'),
(8, 'amenity_booking_url', 'http://stage.realcube.estate/api/AmenityV2/bookAmenity', '2021-05-14 08:26:27', '2021-05-14 08:26:27'),
(9, 'contact_email', 'admin@myfitness.com', '2021-05-14 08:26:27', '2021-05-14 08:26:27'),
(10, 'site_logo', 'img/6EjxBfDtQlVfJPpGz25XgumXp.png', '2021-05-14 08:26:27', '2021-06-10 13:38:36');

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_fullname` text COLLATE utf8mb4_unicode_ci,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `store_timing` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `credit_card` tinyint(4) NOT NULL DEFAULT '0',
  `cash_accept` tinyint(4) NOT NULL DEFAULT '0',
  `featured` tinyint(4) NOT NULL DEFAULT '0',
  `speed` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accuracy` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time_to_deliver` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_order_amount` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` double(15,6) NOT NULL,
  `longitude` double(15,6) NOT NULL,
  `location_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `in_store` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 Enabled',
  `any` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 Enabled',
  `service_charge` double(8,2) DEFAULT NULL,
  `payment_charge` double(8,2) DEFAULT NULL,
  `payment_charge_store_dividend` double(8,2) DEFAULT NULL,
  `payment_charge_provis_dividend` double(8,2) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `vendor_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `by_user_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `by_user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `name`, `store_fullname`, `email`, `mobile`, `image`, `description`, `store_timing`, `location`, `credit_card`, `cash_accept`, `featured`, `speed`, `accuracy`, `time_to_deliver`, `min_order_amount`, `latitude`, `longitude`, `location_type`, `in_store`, `any`, `service_charge`, `payment_charge`, `payment_charge_store_dividend`, `payment_charge_provis_dividend`, `active`, `email_verified_at`, `vendor_id`, `by_user_type`, `by_user_id`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
('517e8990-b9dc-11eb-a247-8926cbd82353', 'my fitness', 'my fitness  Store', 's@s.com', '76857689478', NULL, 'test', NULL, 'sfsfdg', 0, 0, 0, NULL, NULL, NULL, '56565', 0.000000, 0.000000, NULL, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '1', 'admin', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, NULL, '2021-06-14 01:09:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `store_favourites`
--

CREATE TABLE `store_favourites` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int(191) NOT NULL,
  `category_id` int(191) NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `category_id`, `name`, `slug`, `deleted_at`, `created_at`, `updated_at`, `status`) VALUES
(45, 1, 'camping', '', NULL, NULL, NULL, 1),
(46, 2, 'Cardio', '', NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deviceId` text COLLATE utf8mb4_unicode_ci,
  `deviceType` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twilioIdentity` text COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `phone`, `photo`, `type`, `gender`, `email_verified_at`, `image`, `deviceId`, `deviceType`, `twilioIdentity`, `remember_token`, `created_at`, `updated_at`) VALUES
('3b0b6fa0-b8ae-11eb-8509-77f7feb2848d', 'ram@gmail.com', '', 'Ramachandran', 'Nair', '875566', NULL, NULL, 'M', NULL, NULL, NULL, NULL, NULL, NULL, '2021-05-19 12:56:41', '2021-05-19 13:02:57'),
('a2b8d500-b49a-11eb-bb47-7fff44346a1b', 'customer@myfitness.com', '123456', 'Customer', NULL, NULL, NULL, NULL, 'M', NULL, NULL, NULL, NULL, NULL, NULL, '2021-05-14 08:26:21', '2021-05-21 01:08:01');

-- --------------------------------------------------------

--
-- Table structure for table `user_active_tokens`
--

CREATE TABLE `user_active_tokens` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` double(15,6) DEFAULT NULL,
  `longitude` double(15,6) DEFAULT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apartment` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `building_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instruction` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_communities`
--

CREATE TABLE `user_communities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `community_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_payment_methods`
--

CREATE TABLE `user_payment_methods` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=CCavenue',
  `si_reference` text COLLATE utf8mb4_unicode_ci COMMENT 'Card Unique Reference',
  `response_data` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=Enabled,2=Disabled',
  `default_card` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1=Default,0=Other',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `by_user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vlog_blog`
--

CREATE TABLE `vlog_blog` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `by_user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vlog_blog`
--

INSERT INTO `vlog_blog` (`id`, `category_id`, `author_id`, `title`, `description`, `image`, `status`, `by_user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
('797d5b10-c301-11eb-ae58-013046157e97', 'c4133a00-c2a9-11eb-a3e6-b971ea035a7a', 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', 'tech blog', '<p>cxcxx</p>', NULL, 1, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', '2021-06-01 16:17:46', '2021-06-01 16:17:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vlog_blog_author`
--

CREATE TABLE `vlog_blog_author` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `by_user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vlog_blog_author_followers`
--

CREATE TABLE `vlog_blog_author_followers` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vlog_blog_category`
--

CREATE TABLE `vlog_blog_category` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `by_user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vlog_blog_category`
--

INSERT INTO `vlog_blog_category` (`id`, `name`, `status`, `by_user_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
('c4133a00-c2a9-11eb-a3e6-b971ea035a7a', 'tech-blog', 1, 'a2a20280-b49a-11eb-ac5c-cdc1918f2bd0', NULL, '2021-06-01 05:49:55', '2021-06-01 05:49:55');

-- --------------------------------------------------------

--
-- Table structure for table `vlog_blog_images`
--

CREATE TABLE `vlog_blog_images` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vb_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `upload_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'image',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vlog_blog_images`
--

INSERT INTO `vlog_blog_images` (`id`, `vb_id`, `image`, `cover_image`, `upload_type`, `created_at`, `updated_at`, `deleted_at`) VALUES
('38cf0f90-c608-11eb-99af-c1b96dc9f0f0', '797d5b10-c301-11eb-ae58-013046157e97', 'vlogBlog1354616234.jpg', '', 'image', '2021-06-05 12:43:38', '2021-06-05 12:43:38', NULL),
('63f63ee0-c608-11eb-b54d-81f8a4cafa6b', '797d5b10-c301-11eb-ae58-013046157e97', 'http://127.0.0.1:8000/admin/blog/manage-images/797d5b10-c301-11eb-ae58-013046157e97', 'vlogBlog1187883417.jpg', 'video_url', '2021-06-05 12:44:50', '2021-06-05 12:44:50', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_log_log_name_index` (`log_name`),
  ADD KEY `subject` (`subject_id`,`subject_type`),
  ADD KEY `causer` (`causer_id`,`causer_type`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`),
  ADD KEY `admins_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `audits`
--
ALTER TABLE `audits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audits_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  ADD KEY `audits_user_id_user_type_index` (`user_id`,`user_type`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banner_stores`
--
ALTER TABLE `banner_stores`
  ADD KEY `banner_stores_banner_id_index` (`banner_id`),
  ADD KEY `banner_stores_store_id_index` (`store_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `business_type_categories`
--
ALTER TABLE `business_type_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_product_id_index` (`product_id`),
  ADD KEY `carts_store_id_index` (`store_id`),
  ADD KEY `carts_user_id_index` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `childcategories`
--
ALTER TABLE `childcategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `communities`
--
ALTER TABLE `communities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ecommerce_banner`
--
ALTER TABLE `ecommerce_banner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gifts`
--
ALTER TABLE `gifts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_uuid`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_uuid`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_uuid`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_uuid`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_userid_foreign` (`userId`);

--
-- Indexes for table `offer`
--
ALTER TABLE `offer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offer_brand_id_index` (`brand_id`);

--
-- Indexes for table `offer_brand`
--
ALTER TABLE `offer_brand`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offer_brand_category_id_index` (`category_id`);

--
-- Indexes for table `offer_category`
--
ALTER TABLE `offer_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_id_unique` (`order_id`),
  ADD KEY `orders_store_id_index` (`store_id`),
  ADD KEY `orders_user_id_index` (`user_id`),
  ADD KEY `orders_address_id_index` (`address_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_index` (`order_id`),
  ADD KEY `order_items_product_id_index` (`product_id`);

--
-- Indexes for table `order_payments`
--
ALTER TABLE `order_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_payments_order_id_foreign` (`order_id`),
  ADD KEY `order_payments_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_status_order_id_index` (`order_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD KEY `products_by_user_id_foreign` (`by_user_id`),
  ADD KEY `products_brand_id_foreign` (`brand_id`),
  ADD KEY `products_category_id_index` (`category_id`),
  ADD KEY `products_sub_category_id_index` (`sub_category_id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_categories_category_id_index` (`category_id`),
  ADD KEY `product_categories_product_id_index` (`product_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_index` (`product_id`);

--
-- Indexes for table `product_stores`
--
ALTER TABLE `product_stores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_stores_product_id_index` (`product_id`),
  ADD KEY `product_stores_store_id_index` (`store_id`);

--
-- Indexes for table `report_problem`
--
ALTER TABLE `report_problem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_problem_order_id_index` (`order_id`),
  ADD KEY `report_problem_store_id_index` (`store_id`),
  ADD KEY `report_problem_user_id_index` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stores_email_unique` (`email`),
  ADD KEY `stores_by_user_type_by_user_id_index` (`by_user_type`,`by_user_id`),
  ADD KEY `stores_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `store_favourites`
--
ALTER TABLE `store_favourites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `store_favourites_store_id_index` (`store_id`),
  ADD KEY `store_favourites_user_id_index` (`user_id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_active_tokens`
--
ALTER TABLE `user_active_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_active_tokens_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_addresses_user_id_index` (`user_id`);

--
-- Indexes for table `user_communities`
--
ALTER TABLE `user_communities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_communities_user_id_index` (`user_id`);

--
-- Indexes for table `user_payment_methods`
--
ALTER TABLE `user_payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_payment_methods_user_id_foreign` (`user_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vendors_email_unique` (`email`),
  ADD KEY `vendors_by_user_id_foreign` (`by_user_id`);

--
-- Indexes for table `vlog_blog`
--
ALTER TABLE `vlog_blog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vlog_blog_category_id_index` (`category_id`),
  ADD KEY `vlog_blog_author_id_index` (`author_id`);

--
-- Indexes for table `vlog_blog_author`
--
ALTER TABLE `vlog_blog_author`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vlog_blog_author_followers`
--
ALTER TABLE `vlog_blog_author_followers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vlog_blog_author_followers_author_id_index` (`author_id`),
  ADD KEY `vlog_blog_author_followers_user_id_index` (`user_id`);

--
-- Indexes for table `vlog_blog_category`
--
ALTER TABLE `vlog_blog_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vlog_blog_images`
--
ALTER TABLE `vlog_blog_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vlog_blog_images_vb_id_index` (`vb_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audits`
--
ALTER TABLE `audits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `business_type_categories`
--
ALTER TABLE `business_type_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `childcategories`
--
ALTER TABLE `childcategories`
  MODIFY `id` int(191) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `communities`
--
ALTER TABLE `communities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int(191) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `user_communities`
--
ALTER TABLE `user_communities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `banner_stores`
--
ALTER TABLE `banner_stores`
  ADD CONSTRAINT `banner_stores_banner_id_foreign` FOREIGN KEY (`banner_id`) REFERENCES `ecommerce_banner` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `banner_stores_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `product_stores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_userid_foreign` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `offer`
--
ALTER TABLE `offer`
  ADD CONSTRAINT `offer_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `offer_brand` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `offer_brand`
--
ALTER TABLE `offer_brand`
  ADD CONSTRAINT `offer_brand_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `offer_category` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `user_addresses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `product_stores` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_payments`
--
ALTER TABLE `order_payments`
  ADD CONSTRAINT `order_payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_status`
--
ALTER TABLE `order_status`
  ADD CONSTRAINT `order_status_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_by_user_id_foreign` FOREIGN KEY (`by_user_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `product_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_categories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_stores`
--
ALTER TABLE `product_stores`
  ADD CONSTRAINT `product_stores_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_stores_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `report_problem`
--
ALTER TABLE `report_problem`
  ADD CONSTRAINT `report_problem_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `report_problem_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `store_favourites`
--
ALTER TABLE `store_favourites`
  ADD CONSTRAINT `store_favourites_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `store_favourites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_active_tokens`
--
ALTER TABLE `user_active_tokens`
  ADD CONSTRAINT `user_active_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_communities`
--
ALTER TABLE `user_communities`
  ADD CONSTRAINT `user_communities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_payment_methods`
--
ALTER TABLE `user_payment_methods`
  ADD CONSTRAINT `user_payment_methods_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendors`
--
ALTER TABLE `vendors`
  ADD CONSTRAINT `vendors_by_user_id_foreign` FOREIGN KEY (`by_user_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vlog_blog`
--
ALTER TABLE `vlog_blog`
  ADD CONSTRAINT `vlog_blog_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `vlog_blog_category` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vlog_blog_author_followers`
--
ALTER TABLE `vlog_blog_author_followers`
  ADD CONSTRAINT `vlog_blog_author_followers_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `vlog_blog_author` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vlog_blog_author_followers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vlog_blog_images`
--
ALTER TABLE `vlog_blog_images`
  ADD CONSTRAINT `vlog_blog_images_vb_id_foreign` FOREIGN KEY (`vb_id`) REFERENCES `vlog_blog` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
