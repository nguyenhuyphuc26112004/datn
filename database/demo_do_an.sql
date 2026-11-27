-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2026 at 01:37 PM
-- Server version: 10.11.16-MariaDB
-- PHP Version: 8.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `demo_do_an`
--

-- --------------------------------------------------------

--
-- Table structure for table `ct_don_hang`
--

CREATE TABLE `ct_don_hang` (
  `id` bigint(20) NOT NULL,
  `id_don_hang` bigint(20) DEFAULT NULL,
  `id_san_pham` bigint(20) DEFAULT NULL,
  `ma_lo` varchar(50) DEFAULT NULL,
  `so_luong` int(11) DEFAULT 1,
  `gia` decimal(15,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ct_don_hang`
--

INSERT INTO `ct_don_hang` (`id`, `id_don_hang`, `id_san_pham`, `ma_lo`, `so_luong`, `gia`) VALUES
(1, 1, 9, 'LH-0009-01', 4, 70000.00),
(2, 1, 10, 'LH-0010-01', 5, 60000.00),
(3, 2, 11, 'LH-0011-01', 2, 50000.00),
(4, 3, 29, 'LH-0029-01', 2, 150000.00),
(5, 3, 9, 'LH-0009-01', 3, 70000.00),
(6, 4, 9, 'LH-0009-01', 3, 70000.00),
(7, 4, 10, 'LH-0010-01', 2, 60000.00),
(8, 4, 27, 'LH-0027-01', 1, 50000.00),
(9, 5, 31, 'LH-0031-01', 1, 45000.00),
(10, 6, 8, 'LH-0008-01', 4, 50000.00),
(11, 6, 2, 'LH-0002-03', 3, 50000.00),
(12, 7, 23, 'LH-0023-01', 5, 600000.00),
(13, 7, 23, 'LH-0023-02', 1, 600000.00),
(14, 8, 11, 'LH-0011-01', 3, 50000.00),
(15, 8, 9, 'LH-0009-01', 2, 70000.00),
(16, 9, 34, 'LH-0034-01', 4, 119000.00),
(17, 9, 1, 'LH-0001-03', 3, 35000.00),
(18, 10, 3, 'LH-0003-01', 3, 40000.00),
(19, 10, 10, 'LH-0010-01', 2, 60000.00),
(20, 10, 29, 'LH-0029-01', 1, 150000.00),
(21, 11, 1, 'LH-0001-03', 4, 35000.00),
(22, 12, 8, 'LH-0008-01', 1, 50000.00),
(23, 12, 34, 'LH-0034-01', 2, 119000.00),
(24, 12, 10, 'LH-0010-01', 1, 60000.00),
(25, 13, 5, 'LH-0005-04', 3, 40000.00),
(26, 13, 33, 'LH-0033-01', 3, 45000.00),
(27, 13, 32, 'LH-0032-01', 1, 55000.00),
(28, 14, 16, 'LH-0016-01', 1, 120000.00),
(29, 14, 32, 'LH-0032-01', 4, 55000.00),
(30, 14, 27, 'LH-0027-01', 2, 50000.00),
(31, 14, 29, 'LH-0029-01', 2, 150000.00),
(32, 14, 10, 'LH-0010-01', 2, 60000.00),
(33, 15, 9, 'LH-0009-01', 1, 70000.00),
(34, 15, 31, 'LH-0031-01', 3, 45000.00),
(35, 16, 2, 'LH-0002-03', 1, 50000.00);

-- --------------------------------------------------------

--
-- Table structure for table `ct_gio_hang`
--

CREATE TABLE `ct_gio_hang` (
  `id` bigint(20) NOT NULL,
  `id_gio_hang` bigint(20) DEFAULT NULL,
  `id_san_pham` bigint(20) DEFAULT NULL,
  `so_luong` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `don_hang`
--

CREATE TABLE `don_hang` (
  `id` bigint(20) NOT NULL,
  `id_nguoi_dung` bigint(20) DEFAULT NULL,
  `ten` varchar(255) DEFAULT NULL,
  `sdt` varchar(11) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `tong_tien` decimal(15,2) DEFAULT 0.00,
  `phi_van_chuyen` int(11) DEFAULT NULL,
  `ma_khuyen_mai` varchar(50) DEFAULT NULL,
  `tien_giam` decimal(15,2) NOT NULL,
  `ly_do_tra_hang` text DEFAULT NULL,
  `hinh_anh_tra` varchar(255) DEFAULT NULL,
  `het_han_tra_hang` datetime DEFAULT NULL,
  `ly_do_tu_choi_tra` text DEFAULT NULL,
  `ngay_dat` datetime DEFAULT current_timestamp(),
  `trang_thai` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `don_hang`
--

INSERT INTO `don_hang` (`id`, `id_nguoi_dung`, `ten`, `sdt`, `email`, `dia_chi`, `latitude`, `longitude`, `tong_tien`, `phi_van_chuyen`, `ma_khuyen_mai`, `tien_giam`, `ly_do_tra_hang`, `hinh_anh_tra`, `het_han_tra_hang`, `ly_do_tu_choi_tra`, `ngay_dat`, `trang_thai`) VALUES
(1, 2, 'Nguyễn Văn A', '0773370395', 'a@gmail.com', '20 láng hạ', 21.01613770, 105.81393860, 580000.00, 0, '', 0.00, NULL, NULL, '2026-11-20 17:48:39', NULL, '2026-11-19 17:48:13', 'Đã nhận hàng'),
(2, 2, 'Nguyễn Văn A', '0773370395', 'a@gmail.com', '20 láng hạ', 21.01613770, 105.81393860, 112000.00, 12000, '', 0.00, NULL, NULL, NULL, NULL, '2026-11-19 17:49:17', 'Đã hủy'),
(3, 1, 'Nguyễn Văn Amdmin', '0368334112', 'phuc40965@gmail.com', 'Tăng Thiết Giáp, Dong Ngac Ward, Hanoi, Vietnam', 21.05808060, 105.78014980, 484500.00, 0, 'KM_00002', 25500.00, NULL, NULL, '2026-11-25 14:32:12', NULL, '2026-11-24 14:30:05', 'Đã nhận hàng'),
(4, 1, 'Nguyễn Văn Amdmin', '0368334112', 'phuc40965@gmail.com', '20 láng hạ', 21.01613770, 105.81393860, 360000.00, 0, 'KM_00001', 20000.00, NULL, NULL, '2026-11-25 14:35:11', NULL, '2026-11-24 14:34:56', 'Đã nhận hàng'),
(5, 1, 'Nguyễn Văn Amdmin', '0368334112', 'phuc40965@gmail.com', 'tăng thiết giáp', 21.05808060, 105.78014980, 30000.00, 0, 'KM_00006', 15000.00, NULL, NULL, '2026-11-25 14:58:39', NULL, '2026-11-24 14:38:00', 'Đã nhận hàng'),
(6, 2, 'Nguyễn Văn A', '0773370395', 'a@gmail.com', '20 láng hạ', 21.01613770, 105.81393860, 330000.00, 0, 'KM_00007', 20000.00, NULL, NULL, '2026-11-25 14:58:21', NULL, '2026-11-24 14:49:18', 'Đã nhận hàng'),
(7, 2, 'Nguyễn Văn A', '0773370395', 'a@gmail.com', '20 láng hạ', 21.01613770, 105.81393860, 3420000.00, 0, 'KM_00002', 180000.00, NULL, NULL, '2026-11-25 14:57:47', NULL, '2026-11-24 14:55:41', 'Đã nhận hàng'),
(8, 2, 'Nguyễn Văn A', '0773370395', 'a@gmail.com', '20 láng hạ', 21.01613770, 105.81393860, 282000.00, 12000, 'KM_00001', 20000.00, NULL, NULL, '2026-11-25 15:18:24', NULL, '2026-11-24 14:59:41', 'Đã nhận hàng'),
(9, 2, 'Nguyễn Văn A', '0773370395', 'a@gmail.com', '20 láng hạ', 21.01613770, 105.81393860, 581000.00, 0, '', 0.00, NULL, NULL, '2026-11-25 15:18:20', NULL, '2026-11-24 15:00:37', 'Đã nhận hàng'),
(10, 3, 'Nguyễn Văn B', '0773370395', 'b@gmail.com', 'Bến xe Nước Ngầm, Ngoc Hoi Road, Yen So Ward, Hanoi, Vietnam', 20.96382920, 105.84214320, 370000.00, 0, 'KM_00001', 20000.00, NULL, NULL, '2026-11-25 15:18:14', NULL, '2026-11-24 15:06:29', 'Đã nhận hàng'),
(11, 3, 'Nguyễn Văn B', '0123456789', 'b@gmail.com', 'Bến xe Nước Ngầm, Ngoc Hoi Road, Yen So Ward, Hanoi, Vietnam', 20.96382920, 105.84214320, 153000.00, 20000, 'KM_00002', 7000.00, NULL, NULL, '2026-11-25 15:18:08', NULL, '2026-11-24 15:08:26', 'Đã nhận hàng'),
(12, 3, 'Nguyễn Văn B', '0123456789', 'b@gmail.com', '20 láng hạ', 21.01613770, 105.81393860, 348000.00, 0, '', 0.00, NULL, NULL, '2026-11-25 15:32:52', NULL, '2026-11-24 15:26:02', 'Đã nhận hàng'),
(13, 3, 'Nguyễn Văn B', '0123456789', 'b@gmail.com', '20 láng hạ', 21.01613770, 105.81393860, 310000.00, 0, '', 0.00, NULL, NULL, '2026-11-25 15:32:47', NULL, '2026-11-24 15:27:12', 'Đã nhận hàng'),
(14, 4, 'Trần Văn C', '0824770547', 'c@gmail.com', '20 láng hạ', 21.01613770, 105.81393860, 860000.00, 0, '', 0.00, NULL, NULL, '2026-11-25 15:34:28', NULL, '2026-11-24 15:34:13', 'Đã nhận hàng'),
(15, 4, 'Trần Văn C', '0824770547', 'c@gmail.com', '20 láng hạ', 21.01613770, 105.81393860, 197000.00, 12000, 'KM_00001', 20000.00, NULL, NULL, NULL, NULL, '2026-11-25 09:55:11', 'Đang vận chuyển'),
(16, 4, 'Trần Văn C', '0123456789', 'c@gmail.com', 'bến xe nước ngầm', 20.96382920, 105.84214320, 70000.00, 20000, '', 0.00, NULL, NULL, NULL, NULL, '2026-11-25 16:18:52', 'Đang chờ duyệt');

-- --------------------------------------------------------

--
-- Table structure for table `gio_hang`
--

CREATE TABLE `gio_hang` (
  `id` bigint(20) NOT NULL,
  `id_nguoi_dung` bigint(20) DEFAULT NULL,
  `so_luong_sp` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gio_hang`
--

INSERT INTO `gio_hang` (`id`, `id_nguoi_dung`, `so_luong_sp`) VALUES
(1, 2, 0),
(2, 1, 0),
(3, 3, 0),
(4, 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `khuyen_mai`
--

CREATE TABLE `khuyen_mai` (
  `id` int(11) NOT NULL,
  `ma_khuyen_mai` varchar(50) NOT NULL,
  `ten_chuong_trinh` varchar(255) NOT NULL,
  `loai_giam` enum('phan_tram','tien_mat') NOT NULL,
  `gia_tri_giam` decimal(15,2) NOT NULL,
  `giam_toi_da` decimal(15,2) DEFAULT NULL,
  `don_hang_toi_thieu` decimal(15,2) NOT NULL,
  `so_luong` int(11) DEFAULT 100,
  `da_dung` int(11) DEFAULT 0,
  `ngay_bat_dau` datetime NOT NULL,
  `ngay_ket_thuc` datetime NOT NULL,
  `trang_thai` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `khuyen_mai`
--

INSERT INTO `khuyen_mai` (`id`, `ma_khuyen_mai`, `ten_chuong_trinh`, `loai_giam`, `gia_tri_giam`, `giam_toi_da`, `don_hang_toi_thieu`, `so_luong`, `da_dung`, `ngay_bat_dau`, `ngay_ket_thuc`, `trang_thai`) VALUES
(1, 'KM_00001', 'Khuyến mãi 26/10/2026', 'tien_mat', 20000.00, NULL, 0.00, 100, 6, '2026-10-26 19:03:00', '2026-11-25 19:03:00', 1),
(2, 'KM_00002', 'Khuyến mãi 28/10/2026', 'phan_tram', 5.00, NULL, 0.00, 10, 4, '2026-10-26 19:04:00', '2026-11-25 19:04:00', 1),
(3, 'KM_00003', 'Khuyến mãi hè', 'tien_mat', 15000.00, NULL, 0.00, 100, 0, '2026-07-26 19:09:00', '2026-08-25 19:09:00', 1),
(4, 'KM_00004', 'Khuyến mãi tháng 11', 'phan_tram', 10.00, NULL, 0.00, 100, 1, '2026-11-01 09:42:00', '2026-11-29 09:42:00', 1),
(5, 'KM_00005', 'Khuyến mãi tháng 11,1', 'phan_tram', 10.00, 30000.00, 200000.00, 100, 1, '2026-11-04 22:13:00', '2026-12-04 22:13:00', 1),
(6, 'KM_00006', 'Giảm 15K', 'tien_mat', 15000.00, NULL, 0.00, 100, 2, '2026-11-08 16:40:00', '2026-12-08 16:40:00', 1),
(7, 'KM_00007', 'Giảm 20k', 'tien_mat', 20000.00, NULL, 200000.00, 100, 1, '2026-11-08 19:04:00', '2026-12-08 19:04:00', 1),
(8, 'KM_00008', 'Giảm15K', 'tien_mat', 15000.00, NULL, 220000.00, 100, 0, '2026-11-16 22:24:00', '2026-12-16 22:24:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lich_su_thanh_toan`
--

CREATE TABLE `lich_su_thanh_toan` (
  `id` bigint(20) NOT NULL,
  `id_don_hang` bigint(20) NOT NULL,
  `ma_chuyen_khoan` varchar(100) DEFAULT NULL,
  `pt_thanh_toan` varchar(20) NOT NULL,
  `so_tien` decimal(15,2) NOT NULL,
  `trang_thai_thanh_toan` varchar(30) NOT NULL DEFAULT 'Chờ thanh toán',
  `thoi_gian_thanh_toan` datetime DEFAULT NULL,
  `thoi_gian_het_han` datetime DEFAULT NULL,
  `du_lieu_phan_hoi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lich_su_thanh_toan`
--

INSERT INTO `lich_su_thanh_toan` (`id`, `id_don_hang`, `ma_chuyen_khoan`, `pt_thanh_toan`, `so_tien`, `trang_thai_thanh_toan`, `thoi_gian_thanh_toan`, `thoi_gian_het_han`, `du_lieu_phan_hoi`) VALUES
(1, 1, NULL, 'COD', 580000.00, 'Đã thanh toán', '2026-11-19 17:48:39', NULL, NULL),
(2, 2, 'DH1795085340', 'ONLINE', 112000.00, 'Quá hạn thanh toán', NULL, '2026-11-19 17:50:17', NULL),
(3, 3, NULL, 'COD', 484500.00, 'Đã thanh toán', '2026-11-24 14:32:12', NULL, NULL),
(4, 4, NULL, 'COD', 360000.00, 'Đã thanh toán', '2026-11-24 14:35:11', NULL, NULL),
(5, 5, 'DH1795505757', 'ONLINE', 30000.00, 'Đã thanh toán', '2026-11-24 14:38:30', '2026-11-24 15:08:00', '{\"gateway\":\"MBBank\",\"transactionDate\":\"2026-11-24 14:38:00\",\"accountNumber\":\"0368334112\",\"subAccount\":null,\"code\":\"DH1795505757\",\"content\":\"MBVCB.17198052708.352823.DH1795505757.CT tu 1030668672 NGUYEN HUY PHUC toi 0368334112 NGUYEN HUY PHUC tai MB- Ma GD ACSP\\/ xo352823\",\"transferType\":\"in\",\"description\":\"BankAPINotify MBVCB.17198052708.352823.DH1795505757.CT tu 1030668672 NGUYEN HUY PHUC toi 0368334112 NGUYEN HUY PHUC tai MB- Ma GD ACSP\\/ xo352823\",\"transferAmount\":30000,\"referenceCode\":\"FT26328077727759\",\"accumulated\":0,\"id\":102190755}'),
(6, 6, NULL, 'COD', 330000.00, 'Đã thanh toán', '2026-11-24 14:58:21', NULL, NULL),
(7, 7, NULL, 'COD', 3420000.00, 'Đã thanh toán', '2026-11-24 14:57:47', NULL, NULL),
(8, 8, NULL, 'COD', 282000.00, 'Đã thanh toán', '2026-11-24 15:18:24', NULL, NULL),
(9, 9, NULL, 'COD', 581000.00, 'Đã thanh toán', '2026-11-24 15:18:20', NULL, NULL),
(10, 10, NULL, 'COD', 370000.00, 'Đã thanh toán', '2026-11-24 15:18:14', NULL, NULL),
(11, 11, NULL, 'COD', 153000.00, 'Đã thanh toán', '2026-11-24 15:18:08', NULL, NULL),
(12, 12, NULL, 'COD', 348000.00, 'Đã thanh toán', '2026-11-24 15:32:52', NULL, NULL),
(13, 13, NULL, 'COD', 310000.00, 'Đã thanh toán', '2026-11-24 15:32:47', NULL, NULL),
(14, 14, NULL, 'COD', 860000.00, 'Đã thanh toán', '2026-11-24 15:34:28', NULL, NULL),
(15, 15, NULL, 'COD', 197000.00, 'Chưa thanh toán', NULL, NULL, NULL),
(16, 16, NULL, 'COD', 70000.00, 'Chưa thanh toán', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lo_hang`
--

CREATE TABLE `lo_hang` (
  `id` bigint(20) NOT NULL,
  `id_san_pham` bigint(20) NOT NULL,
  `ma_lo` varchar(50) NOT NULL,
  `so_luong_nhap` int(11) NOT NULL,
  `so_luong_con_lai` int(11) NOT NULL DEFAULT 0,
  `gia_nhap` decimal(15,2) DEFAULT 0.00,
  `ngay_nhap` date DEFAULT NULL,
  `ngay_het_han` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lo_hang`
--

INSERT INTO `lo_hang` (`id`, `id_san_pham`, `ma_lo`, `so_luong_nhap`, `so_luong_con_lai`, `gia_nhap`, `ngay_nhap`, `ngay_het_han`) VALUES
(1, 5, 'LH-0005-01', 30, 25, 30000.00, '2026-11-01', '2026-11-03'),
(2, 5, 'LH-0005-02', 10, 10, 40000.00, '2026-11-01', '2026-11-05'),
(3, 5, 'LH-0005-03', 10, 10, 20000.00, '2026-11-01', '2026-11-08'),
(4, 1, 'LH-0001-01', 30, 13, 25000.00, '2026-11-01', '2026-11-15'),
(5, 2, 'LH-0002-01', 30, 30, 30000.00, '2026-11-04', '2026-11-15'),
(6, 1, 'LH-0001-02', 30, 31, 30000.00, '2026-11-01', '2026-11-15'),
(7, 4, 'LH-0004-01', 10, 10, 30000.00, '2026-11-01', '2026-11-15'),
(8, 6, 'LH-0006-01', 1, 0, 20000.00, '2026-11-01', '2026-11-03'),
(9, 6, 'LH-0006-02', 2, 0, 17000.00, '2026-11-01', '2026-11-03'),
(10, 6, 'LH-0006-03', 1, 0, 17000.00, '2026-11-01', '2026-11-03'),
(11, 6, 'LH-0006-04', 1, 0, 20000.00, '2026-10-30', '2026-11-03'),
(14, 8, 'LH-0008-01', 20, 14, 40000.00, '2026-11-01', '2026-12-06'),
(15, 9, 'LH-0009-01', 30, 16, 60000.00, '2026-11-01', '2026-12-06'),
(16, 10, 'LH-0010-01', 20, 8, 50000.00, '2026-11-01', '2026-12-06'),
(17, 11, 'LH-0011-01', 20, 17, 40000.00, '2026-11-01', '2026-12-06'),
(18, 12, 'LH-0012-01', 10, 10, 120000.00, '2026-11-01', '2026-12-06'),
(19, 13, 'LH-0013-01', 10, 10, 100000.00, '2026-11-01', '2026-12-06'),
(20, 14, 'LH-0014-01', 10, 10, 90000.00, '2026-11-01', '2026-12-06'),
(21, 15, 'LH-0015-01', 10, 10, 90000.00, '2026-11-01', '2026-12-06'),
(22, 16, 'LH-0016-01', 10, 9, 110000.00, '2026-11-01', '2026-12-06'),
(23, 26, 'LH-0026-01', 5, 5, 700000.00, '2026-11-01', '2026-12-06'),
(24, 23, 'LH-0023-01', 5, 0, 400000.00, '2026-11-01', '2026-12-06'),
(25, 24, 'LH-0024-01', 5, 5, 500000.00, '2026-11-01', '2026-12-06'),
(26, 25, 'LH-0025-01', 5, 5, 600000.00, '2026-11-01', '2026-12-06'),
(27, 27, 'LH-0027-01', 30, 20, 40000.00, '2026-11-01', '2026-12-06'),
(28, 28, 'LH-0028-01', 30, 30, 30000.00, '2026-11-01', '2026-12-06'),
(29, 29, 'LH-0029-01', 20, 6, 130000.00, '2026-11-01', '2026-12-06'),
(30, 30, 'LH-0030-01', 20, 20, 25000.00, '2026-11-01', '2026-12-06'),
(31, 31, 'LH-0031-01', 30, 26, 38000.00, '2026-11-01', '2026-12-06'),
(32, 32, 'LH-0032-01', 20, 15, 48000.00, '2026-11-01', '2026-12-06'),
(33, 33, 'LH-0033-01', 20, 17, 39000.00, '2026-11-01', '2026-12-06'),
(34, 34, 'LH-0034-01', 20, 14, 90000.00, '2026-11-01', '2026-12-06'),
(35, 22, 'LH-0022-01', 5, 5, 400000.00, '2026-11-01', '2026-11-15'),
(36, 6, 'LH-0006-05', 20, 20, 20000.00, '2026-11-01', '2026-11-15'),
(37, 5, 'LH-0005-04', 20, 17, 30000.00, '2026-11-10', '2026-12-06'),
(38, 1, 'LH-0001-03', 10, 0, 32000.00, '2026-11-16', '2026-12-16'),
(39, 2, 'LH-0002-02', 1, 1, 50000.00, '2026-11-16', '2026-12-31'),
(40, 3, 'LH-0003-01', 10, 7, 33000.00, '2026-11-21', '2026-12-21'),
(41, 2, 'LH-0002-03', 10, 6, 40000.00, '2026-11-24', '2026-12-24'),
(42, 23, 'LH-0023-02', 2, 1, 550000.00, '2026-11-24', '2026-12-24');

-- --------------------------------------------------------

--
-- Table structure for table `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `id` bigint(20) NOT NULL,
  `id_vai_tro` int(11) DEFAULT NULL,
  `ho_ten` varchar(100) DEFAULT NULL,
  `gioi_tinh` varchar(10) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `ten_dang_nhap` varchar(50) DEFAULT NULL,
  `mat_khau` varchar(255) DEFAULT NULL,
  `so_lan_sai` int(11) DEFAULT 0,
  `thoi_gian_khoa` datetime DEFAULT NULL,
  `ma_otp` varchar(6) DEFAULT NULL,
  `het_han_otp` datetime DEFAULT NULL,
  `trang_thai` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`id`, `id_vai_tro`, `ho_ten`, `gioi_tinh`, `email`, `ten_dang_nhap`, `mat_khau`, `so_lan_sai`, `thoi_gian_khoa`, `ma_otp`, `het_han_otp`, `trang_thai`) VALUES
(1, 2, 'Nguyễn Văn Amdmin', 'NAM', 'phuc40965@gmail.com', 'admin', '$2y$12$dafQpEdvibSegLIdy2Ql5.3l7dAo/tdQXoU.oiMuIh3y23eBFfW8W', 0, NULL, NULL, NULL, 1),
(2, 1, 'Nguyễn Văn A', 'Nam', 'a@gmail.com', 'văn a', '$2y$12$gop3.IO.PnsrhympBinlvekxyUCtvVpNPZh.BnBs76nusoHX7.vm2', 0, NULL, NULL, NULL, 1),
(3, 3, 'Nguyễn Văn B', 'Nam', 'b@gmail.com', 'văn b', '$2y$12$PvyaLlDzB..YSocjvQCyxOHuHphaX5lpiG.Y0aW//ZApyWsZq.IZi', 0, NULL, NULL, NULL, 1),
(4, 3, 'Trần Văn C', 'Nam', 'c@gmail.com', 'văn c', '$2y$12$HVO9gBfv6N9gvszSU5ixfeQxQjhOfhiOIKhq.y9tz.ys1Y7GAn6cK', 0, NULL, NULL, NULL, 1),
(5, 1, 'Nguyễn Thị D', 'Nữ', 'd@gmail.com', 'thị d', '$2y$12$xCXNV3iUZogE6QbzWsE9UuzUqvT38ucJYo/xwkurphIAznNFZn2t6', 0, NULL, NULL, NULL, 0),
(6, 3, 'trần thị e', 'Nữ', 'e@gmail.com', 'thị e', '$2y$12$Q17VCc/tHylCvD2xJB9XOOI3p3.201E5d2zVoJkPN7pDtVTH3BIC6', 0, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `san_pham`
--

CREATE TABLE `san_pham` (
  `id` bigint(20) NOT NULL,
  `ten` varchar(100) DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `loai` varchar(50) DEFAULT NULL,
  `don_vi` varchar(50) DEFAULT NULL,
  `so_luong` int(11) DEFAULT 0,
  `gia` decimal(15,2) DEFAULT 0.00,
  `ngay_het_han` date DEFAULT NULL,
  `anh` varchar(255) DEFAULT NULL,
  `trang_thai` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `san_pham`
--

INSERT INTO `san_pham` (`id`, `ten`, `mo_ta`, `loai`, `don_vi`, `so_luong`, `gia`, `ngay_het_han`, `anh`, `trang_thai`) VALUES
(1, 'Lựu', 'Hạt mọng nước như hồng ngọc, vị ngọt thanh mát, giàu vitamin C và chất xơ. Thích hợp ăn tươi, ép nước hoặc làm topping. Bảo quản ngăn mát tủ lạnh dùng trong 1-2 tuần.', 'Trái cây Việt Nam', 'Kg', 0, 35000.00, NULL, '1745559354_Lựu.jpg', 0),
(2, 'Nho', 'Nho trồng tại vườn Ninh Thuận trái nhỏ vừa, chùm khít, vị ngọt thanh tự nhiên xen lẫn vị chua nhẹ đặc trưng, giá cả bình dân', 'Trái cây Việt Nam', 'Kg', 7, 50000.00, '2026-12-24', '1745559541_Nho.jpg', 1),
(3, 'Chôm chôm', 'Chôm chôm tươi ngọt lịm, cùi dày giòn, hạt tróc, mọng nước. Được tuyển chọn từ những nhà vườn đạt chuẩn, cam kết an toàn, tươi ngon mỗi ngày.', 'Trái cây Việt Nam', 'Kg', 7, 40000.00, '2026-12-21', '1745561394_Chômchôm.jpg', 1),
(4, 'Bơ', 'Có xuất xứ từ vùng đất cao nguyên Lâm Đồng (Bảo Lộc), được nhân rộng thành đặc sản trứ danh của Tây Nguyên. Quả thon dài, hạt nhỏ, cơm vàng đậm, dẻo quánh, vị béo ngậy và thơm đặc trưng.', 'Trái cây Việt Nam', 'Kg', 10, 40000.00, '2026-11-15', '1745561438_Bơ.jpg', 1),
(5, 'Dâu tây', 'Nguồn gốc từ Đà Lạt (Lâm Đồng); quả đỏ tươi căng mọng, hình trái tim, vị chua ngọt thanh mát và thơm dịu.', 'Trái cây Việt Nam', 'Kg', 17, 40000.00, '2026-12-06', '1745561466_Dâutây.jpg', 1),
(6, 'Dưa hấu', 'Nguồn gốc chủ yếu từ Long An và Tiền Giang, sở hữu vỏ xanh sọc đặc trưng cùng phần ruột đỏ tươi mọng nước, mang đến vị ngọt thanh giải nhiệt cực kỳ hiệu quả.', 'Trái cây Việt Nam', 'Kg', 20, 25000.00, '2026-11-15', '1745561526_DưaHấu.jpg', 1),
(7, 'Việt quất', 'Nguồn gốc nhập khẩu từ Mỹ, Úc và New Zealand; quả tròn nhỏ màu xanh tím phủ lớp phấn trắng tự nhiên, thịt mọng nước với vị ngọt thanh xen lẫn chua nhẹ và chứa hàm lượng chống oxy hóa cao.', 'Trái cây nhập khẩu', 'Kg', 0, 70000.00, NULL, '1745562262_ViệtQuất.jpg', 0),
(8, 'Táo Mỹ', 'Nguồn gốc nhập khẩu từ tiểu bang Washington (Mỹ); quả tròn căng với vỏ màu đỏ sẫm đặc trưng, thịt táo giòn ngọt, mọng nước và đậm đà hương thơm tự nhiên.', 'Trái cây nhập khẩu', 'Kg', 14, 50000.00, '2026-12-06', '1745562301_TáoMỹ.jpg', 1),
(9, 'Nho Mỹ', 'Nguồn gốc nhập khẩu từ bang California (Mỹ); trái thon dài hoặc tròn mọng, vỏ mỏng mịn, thịt giòn ngọt đậm đà, không hạt và thơm tự nhiên.', 'Trái cây nhập khẩu', 'Kg', 16, 70000.00, '2026-12-06', '1745562325_NhoMỹ.jpg', 1),
(10, 'Cherry', 'Nguồn gốc nhập khẩu từ Mỹ, Chile và New Zealand; quả tròn mọng vỏ đỏ thẫm bóng mượt, thịt quả mọng nước, giòn sần sật với vị ngọt đậm thanh mát đặc trưng.', 'Trái cây nhập khẩu', 'Kg', 8, 60000.00, '2026-12-06', '1745562345_Cherry.jpg', 1),
(11, 'Kiwi', 'Nguồn gốc nhập khẩu từ New Zealand và Chile; quả dạng hình trứng vỏ nâu phủ lông tơ, ruột xanh hoặc vàng óng ánh điểm hạt đen nhỏ, vị chua ngọt hài hòa và giàu vitamin C.', 'Trái cây nhập khẩu', 'Kg', 17, 50000.00, '2026-12-06', '1745562367_Kiwi.jpg', 1),
(12, 'Mận sấy dẻo', 'Nguồn gốc từ vùng ôn đới như Lâm Đồng hoặc hàng nhập khẩu chất lượng cao; quả sấy giữ nguyên dạng tròn đều, vỏ sậm màu, thịt dẻo quánh, vị chua ngọt hài hòa và đậm đà hương thơm tự nhiên.', 'Quả sấy khô', 'Kg', 10, 130000.00, '2026-12-06', '1745588924_Mận sấy dẻo.jpg', 1),
(13, 'Kiwi sấy dẻo', 'Nguồn gốc từ Kiwi tươi chọn lọc chất lượng cao; lát kiwi giữ nguyên màu xanh tươi mát, dẻo mềm tự nhiên, vị chua nhẹ hài hòa cùng độ ngọt thanh đậm đà cực kỳ đưa miệng.', 'Quả sấy khô', 'Kg', 10, 115000.00, '2026-12-06', '1745589052_Kiwi sấy dẻo.webp', 1),
(14, 'Mít sấy khô', 'Nguồn gốc từ các vùng trồng mít nổi tiếng ở miền Tây Nam Bộ; múi mít có màu vàng tươi giòn rụm, giữ trọn vị ngọt đậm tự nhiên và hương thơm ngào ngạt đặc trưng.', 'Quả sấy khô', 'Kg', 10, 100000.00, '2026-12-06', '1745589104_Mít sấy.jpg', 1),
(15, 'Vải thiều sấy', 'Nguồn gốc từ Bắc Giang (Lục Ngạn); vỏ ngoài màu sậm khô róc, cùi vải dẻo quánh ngả màu nâu cánh gián, vị ngọt đậm đà và hương thơm nồng nàn đặc trưng.', 'Quả sấy khô', 'Kg', 10, 100000.00, '2026-12-06', '1745589217_Vải thiều sấy.jpg', 1),
(16, 'Quả sấy khô tổng hợp', 'Nguồn gốc từ nhiều loại trái cây tươi chọn lọc (chuối, mít, khoai lang, khổ qua, cà rốt...); miếng sấy giòn rụm giữ trọn màu sắc tự nhiên, vị ngọt thanh đa dạng và giàu chất xơ dinh dưỡng.', 'Quả sấy khô', 'Kg', 9, 120000.00, '2026-12-06', '1745589258_Quả sấy khô tổng hợp.webp', 1),
(22, 'Giỏ trái cây 1', 'Thiết kế sang trọng kết hợp từ các loại trái cây nhập khẩu tươi ngon tuyển chọn (cherry, táo Envy, nho xanh không hạt); trang trí tỉ mỉ cùng nơ và phụ kiện lịch sự, là món quà hoàn hảo để biếu tặng, thăm hỏi hoặc chúc mừng trong các dịp đặc biệt.', 'Giỏ trái cây', 'Giỏ', 5, 450000.00, '2026-11-15', '1745675804_Giỏ quả trái cây 1.jpg', 1),
(23, 'Giỏ trái cây 2', 'Thiết kế sang trọng kết hợp từ các loại trái cây nhập khẩu tươi ngon tuyển chọn (cherry, táo Envy, nho xanh không hạt); trang trí tỉ mỉ cùng nơ và phụ kiện lịch sự, là món quà hoàn hảo để biếu tặng, thăm hỏi hoặc chúc mừng trong các dịp đặc biệt.', 'Giỏ trái cây', 'Giỏ', 1, 600000.00, '2026-12-24', '1792907621_Giỏ quả trái cây 2.jpg', 1),
(24, 'GIỏ trái cây 3', 'Thiết kế sang trọng kết hợp từ các loại trái cây nhập khẩu tươi ngon tuyển chọn (cherry, táo Envy, nho xanh không hạt); trang trí tỉ mỉ cùng nơ và phụ kiện lịch sự, là món quà hoàn hảo để biếu tặng, thăm hỏi hoặc chúc mừng trong các dịp đặc biệt.', 'Giỏ trái cây', 'Giỏ', 5, 600000.00, '2026-12-06', '1745676174_Giỏ quả trái cây 3.jpg', 1),
(25, 'Giỏ trái cây 4', 'Thiết kế sang trọng kết hợp từ các loại trái cây nhập khẩu tươi ngon tuyển chọn (cherry, táo Envy, nho xanh không hạt); trang trí tỉ mỉ cùng nơ và phụ kiện lịch sự, là món quà hoàn hảo để biếu tặng, thăm hỏi hoặc chúc mừng trong các dịp đặc biệt.', 'Giỏ trái cây', 'Giỏ', 5, 700000.00, '2026-12-06', '1745676210_Giỏ quả trái cây 4.jpg', 1),
(26, 'Giỏ trái cây 5', 'Thiết kế sang trọng kết hợp từ các loại trái cây nhập khẩu tươi ngon tuyển chọn (cherry, táo Envy, nho xanh không hạt); trang trí tỉ mỉ cùng nơ và phụ kiện lịch sự, là món quà hoàn hảo để biếu tặng, thăm hỏi hoặc chúc mừng trong các dịp đặc biệt.', 'Giỏ trái cây', 'Giỏ', 5, 800000.00, '2026-12-06', '1745676240_Giỏ quả trái cây 5.jpg', 1),
(27, 'Bưởi da xanh', 'Giống bưởi đặc sản nổi tiếng với lớp vỏ xanh tươi, tép bưởi hồng đỏ căng mọng nước, dễ bóc, múi róc không dính tôm, vị ngọt thanh đậm đà tự nhiên và không hề bị đắng. Từ 1,2kg - 1,4kg / quả', 'Trái cây Việt Nam', 'Quả', 20, 50000.00, '2026-12-06', '1792906627_bưởi_da_xanh.jpg', 1),
(28, 'Xoài tứ quý', 'Giống xoài quả \"khủng\" đặc sản với vỏ xanh đậm, thịt quả dày, hạt mỏng, khi chín hay xanh đều có độ giòn hoặc dẻo ngọt đặc trưng, vị thơm đậm đà và ngọt lịm không bị chua gắt.', 'Trái cây Việt Nam', 'Kg', 30, 35000.00, '2026-12-06', '1792906718_xoai_tu_quy.jpg', 1),
(29, 'Sầu riêng', 'Múi sầu đầy đặn, vàng ươm kích thích thị giác, lớp vỏ mỏng gai đều, phần cơm dày vàng óng, vị béo ngậy đậm đà thơm phức đặc trưng tan chảy ngay trong miệng. Trọng lượng từ 1,8kg - 2kg / quả', 'Trái cây Việt Nam', 'Quả', 6, 150000.00, '2026-12-06', '1792906825_Sầu riêng.jpg', 1),
(30, 'Thanh long ruột đỏ', 'Quả vỏ đỏ tươi bắt mắt, ruột bên trong đỏ hồng đậm đà, kết cấu thịt mọng nước kết hợp vị ngọt thanh mát đậm vị cùng hàng nghìn hạt nhỏ li ti giòn sần sật, giàu chất chống oxy hóa và vitamin giúp giải nhiệt cực tốt.', 'Trái cây Việt Nam', 'Kg', 20, 30000.00, '2026-12-06', '1792906925_Thanh long ruột đỏ.jpg', 1),
(31, 'Mãng cầu', 'Vỏ có gai mềm hoặc lớp vảy đặc trưng, phần thịt bên trong trắng ngà mềm mịn, kết cấu múi mọng nước, vị chua ngọt hài hòa đậm đà và hương thơm nức mũi cực kỳ hấp dẫn.', 'Trái cây Việt Nam', 'Kg', 26, 45000.00, '2026-12-06', '1792907050_Mãng cầu.jpg', 1),
(32, 'Măng cụt', 'Nguồn gốc từ nhà vườn tuyển chọn; vỏ dày màu tím sẫm đặc trưng, bên trong là những múi trắng ngà mọng nước như tép tỏi, vị ngọt thanh kết hợp chút chua nhẹ tự nhiên, thơm mát và cực kỳ giàu chất dinh dưỡng.', 'Trái cây Việt Nam', 'Kg', 15, 55000.00, '2026-12-06', '1792907160_Măng cụt.jpg', 1),
(33, 'Na', 'Lớp vỏ mỏng với các mắt na to nở đều, khi chín tỏa hương thơm dịu nhẹ, bên trong là phần thịt trắng ngà mềm mịn, từng múi dày thịt, hạt nhỏ, vị ngọt lịm đậm đà tự nhiên tan chảy trong miệng.', 'Trái cây Việt Nam', 'Kg', 17, 45000.00, '2026-12-06', '1792907255_Na.jpg', 1),
(34, 'Lê Hàn Quốc', 'Quả to tròn đều, vỏ màu vàng sáng mịn màng đẹp mắt; phần thịt bên trong trắng ngà, cấu trúc giòn sần sật, nhiều nước, vị ngọt đậm thanh mát tự nhiên mang lại cảm giác giải khát tuyệt vời.', 'Trái cây nhập khẩu', 'Kg', 14, 119000.00, '2026-12-06', '1792907371_Lê hàn quốc.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `vai_tro`
--

CREATE TABLE `vai_tro` (
  `id` int(11) NOT NULL,
  `ten` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vai_tro`
--

INSERT INTO `vai_tro` (`id`, `ten`) VALUES
(1, 'USER'),
(2, 'ADMIN'),
(3, 'STAFF');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ct_don_hang`
--
ALTER TABLE `ct_don_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ct_don_hang_don_hang` (`id_don_hang`),
  ADD KEY `fk_ct_don_hang_san_pham` (`id_san_pham`);

--
-- Indexes for table `ct_gio_hang`
--
ALTER TABLE `ct_gio_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ct_gio_hang_san_pham` (`id_san_pham`),
  ADD KEY `fk_ct_gio_hang_gio_hang` (`id_gio_hang`);

--
-- Indexes for table `don_hang`
--
ALTER TABLE `don_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_don_hang_nguoi_dung` (`id_nguoi_dung`);

--
-- Indexes for table `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_gio_hang_nguoi_dung` (`id_nguoi_dung`);

--
-- Indexes for table `khuyen_mai`
--
ALTER TABLE `khuyen_mai`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ma_khuyen_mai` (`ma_khuyen_mai`);

--
-- Indexes for table `lich_su_thanh_toan`
--
ALTER TABLE `lich_su_thanh_toan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lich_su_thanh_toan_don_hang` (`id_don_hang`);

--
-- Indexes for table `lo_hang`
--
ALTER TABLE `lo_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lo_hang_san_pham` (`id_san_pham`);

--
-- Indexes for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_nguoi_dung_vai_tro` (`id_vai_tro`);

--
-- Indexes for table `san_pham`
--
ALTER TABLE `san_pham`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vai_tro`
--
ALTER TABLE `vai_tro`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ct_don_hang`
--
ALTER TABLE `ct_don_hang`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `ct_gio_hang`
--
ALTER TABLE `ct_gio_hang`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `don_hang`
--
ALTER TABLE `don_hang`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `gio_hang`
--
ALTER TABLE `gio_hang`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `khuyen_mai`
--
ALTER TABLE `khuyen_mai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `lich_su_thanh_toan`
--
ALTER TABLE `lich_su_thanh_toan`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `lo_hang`
--
ALTER TABLE `lo_hang`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `san_pham`
--
ALTER TABLE `san_pham`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `vai_tro`
--
ALTER TABLE `vai_tro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ct_don_hang`
--
ALTER TABLE `ct_don_hang`
  ADD CONSTRAINT `fk_ct_don_hang_don_hang` FOREIGN KEY (`id_don_hang`) REFERENCES `don_hang` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ct_don_hang_san_pham` FOREIGN KEY (`id_san_pham`) REFERENCES `san_pham` (`id`);

--
-- Constraints for table `ct_gio_hang`
--
ALTER TABLE `ct_gio_hang`
  ADD CONSTRAINT `fk_ct_gio_hang_gio_hang` FOREIGN KEY (`id_gio_hang`) REFERENCES `gio_hang` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ct_gio_hang_san_pham` FOREIGN KEY (`id_san_pham`) REFERENCES `san_pham` (`id`);

--
-- Constraints for table `don_hang`
--
ALTER TABLE `don_hang`
  ADD CONSTRAINT `fk_don_hang_nguoi_dung` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoi_dung` (`id`);

--
-- Constraints for table `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD CONSTRAINT `fk_gio_hang_nguoi_dung` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoi_dung` (`id`);

--
-- Constraints for table `lich_su_thanh_toan`
--
ALTER TABLE `lich_su_thanh_toan`
  ADD CONSTRAINT `fk_lich_su_thanh_toan_don_hang` FOREIGN KEY (`id_don_hang`) REFERENCES `don_hang` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lo_hang`
--
ALTER TABLE `lo_hang`
  ADD CONSTRAINT `fk_lo_hang_san_pham` FOREIGN KEY (`id_san_pham`) REFERENCES `san_pham` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD CONSTRAINT `fk_nguoi_dung_vai_tro` FOREIGN KEY (`id_vai_tro`) REFERENCES `vai_tro` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
