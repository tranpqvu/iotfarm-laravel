-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th9 20, 2022 lúc 07:38 PM
-- Phiên bản máy phục vụ: 10.4.17-MariaDB
-- Phiên bản PHP: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `nong_nghiep`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `areas`
--

CREATE TABLE `areas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `devicecode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `farmid` bigint(20) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `areas`
--

INSERT INTO `areas` (`id`, `name`, `devicecode`, `farmid`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Kv2', 'dv2', 3, 1, '2022-03-08 23:38:37', NULL),
(5, 'Kv3', '', 30, 1, NULL, NULL),
(6, 'Kv4', '', 30, 1, NULL, NULL),
(7, 'Kv5', '', 2, 1, NULL, NULL),
(8, '5656565', NULL, 4, 1, '2022-09-20 17:08:07', '2022-09-20 17:09:18');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `devices`
--

CREATE TABLE `devices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `mac` varchar(17) NOT NULL,
  `farm_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `mode_run` tinyint(1) NOT NULL DEFAULT 0,
  `mode_inject` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `devices`
--

INSERT INTO `devices` (`id`, `name`, `mac`, `farm_id`, `user_id`, `status`, `mode_run`, `mode_inject`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'Thiết Bị 1', '00:11:22:33:44:55', 2, 1, 1, 0, 0, 1, '2022-09-01 00:36:37', NULL, NULL),
(3, 'ddđ', 'sssss', 0, 13, 0, 0, 0, 1, '2022-09-06 00:36:43', NULL, NULL),
(4, 'ádsadasd', 'đâsd', 0, 1, 0, 0, 0, 1, '2022-09-02 00:36:46', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `farms`
--

CREATE TABLE `farms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `farms`
--

INSERT INTO `farms` (`id`, `name`, `description`, `phone`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'Nông Trại Hương Thuỷ', 'Nông Trại Hương Thuỷ', '', 0, 0, '2022-03-08 03:08:44', NULL, NULL),
(2, 'Nông Trại Nông Lâm', 'Nông Trại Nông Lâm', '123456789', 1, 0, NULL, NULL, NULL),
(3, 'Nông Trại Đà Lạt', 'Nông Trại Đà Lạt', '123456789', 1, 0, NULL, NULL, NULL),
(4, 'Nông Trại Tứ Hạ', 'Nông Trại Tứ Hạ', '8888', 1, 0, NULL, NULL, NULL),
(30, 'Nông Trại Gia Lai', '999', '999', 1, 0, '2022-08-16 15:38:29', NULL, '2022-08-29 11:06:20'),
(32, 'abvv555555', 'fsgs', 'fsdfsdf', 1, 0, '2022-08-29 12:57:57', NULL, '2022-08-29 12:58:09'),
(33, 'Sdfsdfvsvvvv', 'VvvvvSdfsdfsdf', 'sdfsdfvvvvv', 0, 1, '2022-09-20 23:25:13', 1, '2022-09-20 23:25:43');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `log_login_logout`
--

CREATE TABLE `log_login_logout` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `time_login` datetime NOT NULL,
  `time_logout` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `log_login_logout`
--

INSERT INTO `log_login_logout` (`id`, `user_id`, `email`, `time_login`, `time_logout`) VALUES
(1, NULL, 'admin@gmail.com', '2021-09-04 17:44:11', NULL),
(14, 13, 'ldanh@gmail.com', '2021-09-04 17:49:49', NULL),
(15, 1, 'admin@gmail.com', '2021-09-05 22:24:58', NULL),
(16, 30, 'hoangtantruong@gmail.com', '2021-09-05 22:26:00', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `mode_ratio_auto`
--

CREATE TABLE `mode_ratio_auto` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mac` varchar(17) COLLATE utf8_unicode_ci NOT NULL,
  `idx` int(11) NOT NULL,
  `hour` int(11) DEFAULT 0,
  `minute` int(11) NOT NULL DEFAULT 0,
  `time_inject` int(11) NOT NULL DEFAULT 0,
  `area_index` int(11) NOT NULL DEFAULT 0,
  `channel_1` float NOT NULL DEFAULT 0,
  `channel_2` float NOT NULL DEFAULT 0,
  `channel_3` float NOT NULL DEFAULT 0,
  `channel_4` float NOT NULL DEFAULT 0,
  `channel_5` float NOT NULL DEFAULT 0,
  `channel_6` float NOT NULL DEFAULT 0,
  `channel_7` float NOT NULL DEFAULT 0,
  `channel_8` float NOT NULL DEFAULT 0,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `mode_ratio_auto`
--

INSERT INTO `mode_ratio_auto` (`id`, `mac`, `idx`, `hour`, `minute`, `time_inject`, `area_index`, `channel_1`, `channel_2`, `channel_3`, `channel_4`, `channel_5`, `channel_6`, `channel_7`, `channel_8`, `updated_at`) VALUES
(17, '00:11:22:33:44:55', 1, 10, 20, 11, 1, 1, 2, 3, 4, 5, 6, 7, 8, '2022-09-12 15:00:25'),
(18, '00:11:22:33:44:55', 2, 5, 13, 3, 2, 11, 21, 31, 41, 51, 61, 71, 18, '2022-09-12 15:00:25');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `mode_ratio_manual`
--

CREATE TABLE `mode_ratio_manual` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mac` varchar(17) COLLATE utf8_unicode_ci NOT NULL,
  `turn_off_time` int(11) NOT NULL DEFAULT 0,
  `area_index` int(11) NOT NULL DEFAULT 0,
  `channel_1` float NOT NULL DEFAULT 0,
  `channel_2` float NOT NULL DEFAULT 0,
  `channel_3` float NOT NULL DEFAULT 0,
  `channel_4` float NOT NULL DEFAULT 0,
  `channel_5` float NOT NULL DEFAULT 0,
  `channel_6` float NOT NULL DEFAULT 0,
  `channel_7` float NOT NULL DEFAULT 0,
  `channel_8` float NOT NULL DEFAULT 0,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `mode_ratio_manual`
--

INSERT INTO `mode_ratio_manual` (`id`, `mac`, `turn_off_time`, `area_index`, `channel_1`, `channel_2`, `channel_3`, `channel_4`, `channel_5`, `channel_6`, `channel_7`, `channel_8`, `updated_at`) VALUES
(2, '00:11:22:33:44:55', 13, 1, 1, 2, 3, 4, 5, 6, 7, 8, '2022-09-12 15:00:25');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `mode_tds_auto`
--

CREATE TABLE `mode_tds_auto` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mac` varchar(17) COLLATE utf8_unicode_ci NOT NULL,
  `tds_value` float NOT NULL,
  `idx` int(11) NOT NULL,
  `hour` time DEFAULT '00:00:00',
  `minute` int(2) NOT NULL DEFAULT 0,
  `time_inject` int(11) NOT NULL DEFAULT 0,
  `area_index` int(11) NOT NULL DEFAULT 0,
  `channel_1` tinyint(1) NOT NULL DEFAULT 0,
  `channel_2` tinyint(1) NOT NULL DEFAULT 0,
  `channel_3` tinyint(1) NOT NULL DEFAULT 0,
  `channel_4` tinyint(1) NOT NULL DEFAULT 0,
  `channel_5` tinyint(1) NOT NULL DEFAULT 0,
  `channel_6` tinyint(1) NOT NULL DEFAULT 0,
  `channel_7` tinyint(1) NOT NULL DEFAULT 0,
  `channel_8` tinyint(1) NOT NULL DEFAULT 0,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `mode_tds_auto`
--

INSERT INTO `mode_tds_auto` (`id`, `mac`, `tds_value`, `idx`, `hour`, `minute`, `time_inject`, `area_index`, `channel_1`, `channel_2`, `channel_3`, `channel_4`, `channel_5`, `channel_6`, `channel_7`, `channel_8`, `updated_at`) VALUES
(13, '00:11:22:33:44:55', 87.7, 1, '00:00:10', 20, 11, 1, 1, 0, 1, 0, 1, 1, 1, 1, '2022-09-12 15:09:41'),
(14, '00:11:22:33:44:55', 56.8, 2, '00:00:05', 13, 3, 2, 1, 1, 1, 1, 0, 0, 0, 0, '2022-09-12 15:09:41');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `mode_tds_manual`
--

CREATE TABLE `mode_tds_manual` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mac` varchar(17) COLLATE utf8_unicode_ci NOT NULL,
  `tds_value` float NOT NULL,
  `turn_off_time` int(11) NOT NULL DEFAULT 0,
  `area_index` int(11) NOT NULL DEFAULT 0,
  `channel_1` tinyint(1) NOT NULL DEFAULT 0,
  `channel_2` tinyint(1) NOT NULL DEFAULT 0,
  `channel_3` tinyint(1) NOT NULL DEFAULT 0,
  `channel_4` tinyint(1) NOT NULL DEFAULT 0,
  `channel_5` tinyint(1) NOT NULL DEFAULT 0,
  `channel_6` tinyint(1) NOT NULL DEFAULT 0,
  `channel_7` tinyint(1) NOT NULL DEFAULT 0,
  `channel_8` tinyint(1) NOT NULL DEFAULT 0,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `mode_tds_manual`
--

INSERT INTO `mode_tds_manual` (`id`, `mac`, `tds_value`, `turn_off_time`, `area_index`, `channel_1`, `channel_2`, `channel_3`, `channel_4`, `channel_5`, `channel_6`, `channel_7`, `channel_8`, `updated_at`) VALUES
(2, '00:11:22:33:44:55', 95.5, 13, 1, 1, 0, 1, 0, 1, 0, 1, 0, '2022-09-12 15:00:23');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sex` tinyint(4) NOT NULL DEFAULT 0,
  `date_of_birth` date DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `refresh_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` int(1) UNSIGNED NOT NULL DEFAULT 2,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `createdAt` timestamp NULL DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `sex`, `date_of_birth`, `email`, `phone_number`, `address`, `email_verified_at`, `password`, `remember_token`, `refresh_token`, `level`, `status`, `createdAt`, `updatedAt`) VALUES
(1, 'Admin', NULL, 0, '2021-07-27', 'admin@gmail.com', '123456789', 'Trung tâm GDNN-GDTX Hương Trà', NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOjEsIm5hbWUiOiJBZG1pbiBudWxsIiwiZW1haWwiOiJhZG1pbkBnbWFpbC5jb20iLCJpYXQiOjE2NjM2ODUwNzcsImV4cCI6MTY2Mzc3MTQ3N30.M_-P9hXeQQCJIEj6J3tJIKMxZnbmuBUrTsPD2LzPxO4', 0, 1, '2019-04-24 01:44:43', '2022-09-20 14:44:37'),
(2, 'Chi', 'Đoàn Thị Mai', 1, '1988-03-24', 'teacher@gmail.com', '0949196567', 'Tu Ha - Hương Trà - TT Huế', NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 1, 1, '2019-04-28 00:33:42', '2021-07-28 19:02:01'),
(13, 'Anh', 'Lê Đình', 1, '2021-07-28', 'ldanh@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOjEzLCJuYW1lIjoiQW5oIEzDqiDEkMOsbmgiLCJlbWFpbCI6ImxkYW5oQGdtYWlsLmNvbSIsImlhdCI6MTY2MTg3ODkzMCwiZXhwIjoxNjYxOTY1MzMwfQ.2dfVi8PXkTFmGy8zT2UkVdFCwztkQOpHm9oTgXvgf-c', 2, 1, '2021-07-28 18:47:29', '2022-08-30 17:02:10'),
(14, 'Anh', 'Trần Hoàng Nhất', 1, '2021-07-28', 'thnanh@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-07-28 18:48:52', NULL),
(15, 'Bảo', 'Hoàng Gia', 1, '2021-07-28', 'hgbao@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-07-28 18:49:33', NULL),
(16, 'Bin', 'Nguyễn Văn', 1, '2021-07-28', 'nvbin@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-07-28 18:50:35', NULL),
(17, 'Bình', 'Nguyễn Công', 1, '2021-07-28', 'ncbinh@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-07-28 18:51:27', NULL),
(18, 'Bình', 'Trần Hưng', 1, '2021-07-28', 'thbinh@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-07-28 18:52:09', NULL),
(19, 'Cương', 'Châu Văn Kim', 1, '2021-07-28', 'cvkcuong@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-07-28 18:52:57', '2021-07-28 18:56:27'),
(20, 'Cường', 'Lê Đình', 1, '2021-07-28', 'ldcuong@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-07-28 18:53:47', NULL),
(21, 'Duy', 'Lê Văn', 1, '2021-07-28', 'lvduy@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-07-28 18:54:37', NULL),
(22, 'Dương', 'Phan Vũ Phúc', 1, '2021-07-28', 'pvpduong@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-07-28 18:55:27', NULL),
(23, 'Đông', 'Trương Quang', 1, '2021-07-28', 'tqdong@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-07-28 18:56:03', NULL),
(24, 'Đức', 'Phạm Hồng', 1, '2021-07-28', 'phduc@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-07-28 18:58:18', NULL),
(25, 'Fini', 'Chế Trần', 1, '2021-07-28', 'ctfini@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-07-28 18:58:58', NULL),
(26, 'Hằng', 'Hà Thị', 1, '2021-07-28', 'hthang@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-07-28 18:59:42', NULL),
(27, 'Hoàng', 'Trần Văn', 1, '2021-07-28', 'Tvhoang@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-07-28 19:00:39', NULL),
(28, 'Hùng', 'Nguyễn Bá Việt', 1, '2021-07-28', 'nbvhung@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-07-28 19:01:30', NULL),
(29, 'Phương', 'Phạm Thị Ái', 1, '1987-06-24', 'ptaphuong@gmail.com', '0943633435', NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 1, 1, '2021-07-28 19:03:35', '2021-08-24 21:47:54'),
(30, 'Truong', 'Hoàng Tấn', 1, '2021-08-24', 'hoangtantruong@gmail.com', '0987070392', NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 1, 1, '2021-08-24 21:28:36', NULL),
(31, 'Thiện', 'Nguyễn Thị Nhật', 1, '2004-07-21', 'ntnthien@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 12:42:41', NULL),
(32, 'Hiền', 'Nguyễn Thị Thu', 1, '2004-11-01', 'ntthien@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 12:43:44', NULL),
(33, 'Hiệp', 'Nguyễn Xuân', 1, '2021-09-03', 'nxhiep@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 12:44:59', NULL),
(34, 'Nhi', 'Nguyễn Lê Yến', 1, '2004-12-31', 'nlynhi@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 12:45:54', NULL),
(35, 'Hiếu', 'Đỗ Chánh', 1, '2004-06-16', 'dchieu@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 12:46:55', NULL),
(36, 'Trung', 'Hoàng', 1, '2004-02-16', 'htrung@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 12:48:14', NULL),
(37, 'Huy', 'Lê Văn Quốc', 1, '2004-08-18', 'lvqhuy@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 12:49:08', NULL),
(38, 'Cường', 'Nguyễn Duy', 1, '2004-10-24', 'ndcuong@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 12:50:01', NULL),
(39, 'Thảo', 'Nguyễn Thị Ngọc', 1, '2004-03-24', 'ntnthao@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 12:50:52', NULL),
(40, 'Sang', 'Hồ Đăng', 1, '2004-02-25', 'hdsang@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 12:51:57', NULL),
(41, 'Nhân', 'Nguyễn Văn', 1, '2004-01-22', 'nvnhan@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 12:52:44', NULL),
(42, 'Thiên', 'Hoàng Kim', 1, '2004-03-16', 'hkthien013@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 12:53:31', NULL),
(43, 'Trung', 'Đặng Hữu', 1, '2004-12-06', 'dhtrung@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 12:54:24', NULL),
(44, 'Ý', 'Trương Nguyễn Như', 1, '2004-01-06', 'tnny@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 12:55:13', NULL),
(45, 'Quỳnh', 'Hồ Thị Như', 1, '2004-09-28', 'dtnquynh@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 12:57:55', NULL),
(46, 'Linh', 'Lê Thị Thùy', 1, '2004-11-08', 'lttlinh@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 12:58:43', NULL),
(47, 'Hiền', 'Đỗ Thị Thu', 1, '2004-05-18', 'dtthien@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 12:59:57', NULL),
(48, 'Hiền', 'Trần Thị Ngọc', 1, '2021-09-03', 'ttnhien@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 13:01:09', NULL),
(49, 'Nghĩa', 'Nguyễn Ích', 1, '2004-06-09', 'ninghia@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 13:02:56', NULL),
(50, 'Lời', 'Lê Đình', 1, '2004-01-18', 'ldloi@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 13:04:33', NULL),
(51, 'Anh', 'Thân Đào Lộc', 1, '2004-04-13', 'tdlanh@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 13:05:31', NULL),
(52, 'Lộc', 'Trương Tấn', 1, '2004-03-09', 'ttloc@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 13:06:20', NULL),
(53, 'Hiển', 'Trương Minh', 1, '2004-03-10', 'tmhien@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 13:07:11', NULL),
(54, 'Tuệ', 'Nguyễn Hồ Minh', 1, '2004-06-30', 'nhmtue@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 13:07:50', NULL),
(55, 'Nhật', 'Trịnh Anh', 1, '2004-08-15', 'tanhat@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 13:08:35', NULL),
(56, 'Vy', 'Hồ Thị Khánh', 1, '2004-08-26', 'htkvy@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 13:09:23', NULL),
(57, 'Nguyên', 'Nguyễn Thị Hạnh', 1, '2004-06-15', 'nthnguyen@gmail.com', NULL, NULL, NULL, '$2b$10$XP3zZ7.BiH5x6jbbUZJd4OfqIm/oTBSFipPS5xF9JAxes67T7fT1W', NULL, NULL, 2, 1, '2021-09-03 13:10:15', NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `farms`
--
ALTER TABLE `farms`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `log_login_logout`
--
ALTER TABLE `log_login_logout`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `mode_ratio_auto`
--
ALTER TABLE `mode_ratio_auto`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `mode_ratio_manual`
--
ALTER TABLE `mode_ratio_manual`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `mode_tds_auto`
--
ALTER TABLE `mode_tds_auto`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `mode_tds_manual`
--
ALTER TABLE `mode_tds_manual`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `areas`
--
ALTER TABLE `areas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `devices`
--
ALTER TABLE `devices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `farms`
--
ALTER TABLE `farms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT cho bảng `log_login_logout`
--
ALTER TABLE `log_login_logout`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `mode_ratio_auto`
--
ALTER TABLE `mode_ratio_auto`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `mode_ratio_manual`
--
ALTER TABLE `mode_ratio_manual`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `mode_tds_auto`
--
ALTER TABLE `mode_tds_auto`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `mode_tds_manual`
--
ALTER TABLE `mode_tds_manual`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
