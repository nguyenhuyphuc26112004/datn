-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2026 at 03:55 PM
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
-- Database: `do_an_tn`
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
(1, 1, 1, 'LH-0001-01', 4, 30000.00),
(2, 2, 2, 'LH-0002-01', 3, 50000.00),
(3, 3, 1, 'LH-0001-01', 3, 30000.00),
(4, 3, 2, 'LH-0002-01', 2, 50000.00),
(5, 4, 25, 'LH-0025-01', 1, 700000.00),
(6, 5, 7, 'LH-0007-01', 1, 70000.00),
(7, 6, 26, 'LH-0026-01', 1, 800000.00),
(9, 8, 2, 'LH-0002-01', 3, 50000.00),
(10, 8, 32, 'LH-0032-01', 1, 55000.00),
(11, 9, 29, 'LH-0029-01', 4, 79000.00),
(12, 10, 5, 'LH-0005-01', 5, 40000.00),
(13, 11, 6, 'LH-0006-05', 2, 25000.00),
(14, 12, 6, 'LH-0006-05', 1, 25000.00),
(15, 13, 1, 'LH-0001-01', 2, 35000.00),
(16, 15, 6, 'LH-0006-05', 1, 25000.00),
(17, 15, 6, 'LH-0006-05', 1, 25000.00),
(18, 16, 1, 'LH-0001-02', 1, 35000.00),
(19, 17, 1, 'LH-0001-01', 1, 35000.00),
(20, 18, 29, 'LH-0029-01', 5, 150000.00);

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

--
-- Dumping data for table `ct_gio_hang`
--

INSERT INTO `ct_gio_hang` (`id`, `id_gio_hang`, `id_san_pham`, `so_luong`) VALUES
(9, 1, 1, 1),
(11, 1, 3, 2);

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
  `het_han_thanh_toan` datetime DEFAULT NULL,
  `trang_thai` varchar(100) DEFAULT NULL,
  `trang_thai_thanh_toan` varchar(50) DEFAULT 'Chưa thanh toán',
  `ma_chuyen_khoan` varchar(100) DEFAULT NULL,
  `pt_thanh_toan` varchar(20) DEFAULT NULL,
  `ngay_dat` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `don_hang`
--

INSERT INTO `don_hang` (`id`, `id_nguoi_dung`, `ten`, `sdt`, `email`, `dia_chi`, `latitude`, `longitude`, `tong_tien`, `phi_van_chuyen`, `ma_khuyen_mai`, `tien_giam`, `ly_do_tra_hang`, `hinh_anh_tra`, `het_han_tra_hang`, `ly_do_tu_choi_tra`, `het_han_thanh_toan`, `trang_thai`, `trang_thai_thanh_toan`, `ma_chuyen_khoan`, `pt_thanh_toan`, `ngay_dat`) VALUES
(1, 1, 'Nguyễn Văn Amdmin', '0368334112', 'phuc40965@gmail.com', 'hà tĩnh', 18.37287530, 99.99999999, 170000.00, 50000, NULL, 0.00, NULL, NULL, '2026-11-01 15:27:52', NULL, NULL, 'Đã nhận hàng', 'Đã thanh toán', NULL, 'COD', '2026-10-24 23:45:38'),
(2, 2, 'Nguyễn Văn A', '0123456789', 'a@gmail.com', 'Đường số 5 Phạm Hùng, Hà Nội, Vietnam', 21.01885420, 99.99999999, 150000.00, 0, NULL, 0.00, NULL, NULL, NULL, NULL, '2026-10-25 00:28:03', 'Đã hủy', 'Quá hạn thanh toán', 'DH1792886218', 'ONLINE', '2026-10-24 23:58:03'),
(3, 2, 'Nguyễn Văn A', '0123456789', 'a@gmail.com', '20 láng hạ, đống đa', 21.01024010, 99.99999999, 202000.00, 12000, NULL, 0.00, 'sản phẩm ko đúng mô tả', 'uploads/tra-hang/1793287026_ERD_ĐATN.png', NULL, 'Không đúng số lượng', NULL, 'Từ chối trả hàng', 'Đã thanh toán', NULL, 'COD', '2026-10-25 17:17:12'),
(4, 2, 'Nguyễn Văn A', '0123456789', 'a@gmail.com', '30 giải phóng, hoàng mai, hà nội', 20.97006910, 99.99999999, 700000.00, 0, NULL, 0.00, NULL, NULL, '2026-10-27 16:18:31', NULL, NULL, 'Đã nhận hàng', 'Đã thanh toán', NULL, 'COD', '2026-10-26 00:39:15'),
(5, 2, 'Nguyễn Văn A', '0123456789', 'a@gmail.com', 'Ngõ 20 Phố Láng Hạ, Lang Ward, Hanoi, Vietnam', 21.01613770, 99.99999999, 82000.00, 12000, NULL, 0.00, 'Không đúng mô tả', NULL, '2026-11-02 15:28:22', NULL, NULL, 'Đã trả hàng', 'Đã thanh toán', NULL, 'COD', '2026-10-25 10:44:24'),
(6, 2, 'Nguyễn Văn A', '0123456789', 'a@gmail.com', 'Ngõ 20 Phố Nghĩa Đô, Nghĩa Đô, Hanoi, Vietnam', 21.04832670, 99.99999999, 800000.00, 0, NULL, 0.00, NULL, NULL, '2026-11-03 15:28:32', NULL, '2026-10-25 12:15:36', 'Đã nhận hàng', 'Đã thanh toán', 'DH1792903493', 'ONLINE', '2026-10-25 11:45:36'),
(8, 1, 'Nguyễn Văn Amdmin', '0368334112', 'phuc40965@gmail.com', 'cổ nhuế', 21.06298100, 99.99999999, 205000.00, 0, NULL, 0.00, NULL, NULL, NULL, NULL, '2026-10-26 19:22:04', 'Đã hủy', 'Quá hạn thanh toán', 'DH1793015491', 'ONLINE', '2026-10-26 18:52:04'),
(9, 2, 'Nguyễn Văn A', '0368334112', 'a@gmail.com', 'cổ nhuế', 21.06298100, 99.99999999, 296000.00, 0, 'GIAM20000K', 20000.00, NULL, NULL, '2026-11-02 15:29:09', NULL, NULL, 'Đã nhận hàng', 'Đã thanh toán', NULL, 'COD', '2026-10-26 19:13:50'),
(10, 2, 'Nguyễn Văn A', '0123456789', 'a@gmail.com', '20 láng hạ', 21.01613770, 105.81393860, 212000.00, 12000, '', 0.00, NULL, NULL, '2026-11-03 05:18:19', NULL, NULL, 'Đã nhận hàng', 'Đã thanh toán', NULL, 'COD', '2026-11-02 01:40:02'),
(11, 2, 'Nguyễn Văn A', '0123456789', 'a@gmail.com', '20 láng hạ', 21.01613770, 105.81393860, 62000.00, 12000, '', 0.00, NULL, NULL, '2026-11-03 05:18:13', NULL, NULL, 'Đã nhận hàng', 'Đã thanh toán', NULL, 'COD', '2026-11-02 02:50:08'),
(12, 3, 'Nguyễn Văn B', '0773370395', 'b@gmail.com', '30 đội cấn, ba đình', 21.03438200, 105.83110570, 37000.00, 12000, '', 0.00, NULL, NULL, '2026-11-03 05:18:07', NULL, NULL, 'Đã nhận hàng', 'Đã thanh toán', NULL, 'COD', '2026-11-02 02:53:54'),
(13, 3, 'Nguyễn Văn B', '0773370395', 'b@gmail.com', '30 đội cấn, ba đình, hà nội', 21.03438200, 105.83110570, 82000.00, 12000, '', 0.00, NULL, NULL, '2026-11-03 05:18:00', NULL, NULL, 'Đã nhận hàng', 'Đã thanh toán', NULL, 'COD', '2026-11-02 02:55:47'),
(15, 3, 'Nguyễn Văn B', '0773370395', 'b@gmail.com', '30 đội cấn, ba đình', 21.03438200, 105.83110570, 62000.00, 12000, '', 0.00, NULL, NULL, NULL, NULL, NULL, 'Đã hủy', 'Chưa thanh toán', NULL, 'COD', '2026-11-02 05:21:53'),
(16, 3, 'Nguyễn Văn B', '0773370395', 'b@gmail.com', 'Ngách 102/20 Phố Pháo Đài Láng, Lang Ward, Hanoi, Vietnam', 21.01949310, 105.80421710, 35000.00, 0, '', 0.00, NULL, NULL, NULL, NULL, NULL, 'Đã hủy', 'Chưa thanh toán', NULL, 'COD', '2026-11-02 05:27:56'),
(17, 3, 'Nguyễn Văn B', '0773370395', 'b@gmail.com', '20 láng hạ', 21.01613770, 105.81393860, 47000.00, 12000, '', 0.00, NULL, NULL, NULL, NULL, NULL, 'Đã hủy', 'Chưa thanh toán', NULL, 'COD', '2026-11-02 05:33:10'),
(18, 4, 'Trần Văn C', '0824770547', 'c@gmail.com', 'Ngõ 208 Đường Giải Phóng, Phương Liệt, Hanoi, Vietnam', 21.00585860, 105.84109060, 720000.00, 0, 'GIAM10PHANTRAM1', 30000.00, NULL, NULL, '2026-11-06 21:49:07', NULL, NULL, 'Đã nhận hàng', 'Đã thanh toán', NULL, 'COD', '2026-11-05 21:48:29');

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
(1, 2, 2),
(2, 1, 0);

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
  `giam_toi_da` decimal(15,2) NOT NULL,
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
(1, 'GIAM20000K', 'Khuyến mãi 26/10/2026', 'tien_mat', 20000.00, null, 0.00, 100, 1, '2026-10-26 19:03:00', '2026-11-25 19:03:00', 1),
(2, 'GIAM5PHANTRAM', 'Khuyến mãi 28/10/2026', 'phan_tram', 5.00, null, 0.00, 10, 0, '2026-10-26 19:04:00', '2026-11-25 19:04:00', 1),
(3, 'GIAM15K', 'Khuyến mãi hè', 'tien_mat', 15000.00, null, 0.00, 100, 0, '2026-07-26 19:09:00', '2026-08-25 19:09:00', 1),
(4, 'GIAM10PHANTRAM', 'Khuyến mãi tháng 11', 'phan_tram', 10.00, null, 0.00, 100, 0, '2026-11-01 09:42:00', '2026-11-29 09:42:00', 1),
(5, 'GIAM10PHANTRAM1', 'Khuyến mãi tháng 11,1', 'phan_tram', 10.00, 30000.00, 200000.00, 100, 1, '2026-11-04 22:13:00', '2026-12-04 22:13:00', 1);

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
  `ngay_het_han` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lo_hang`
--

INSERT INTO `lo_hang` (`id`, `id_san_pham`, `ma_lo`, `so_luong_nhap`, `so_luong_con_lai`, `gia_nhap`, `ngay_het_han`) VALUES
(1, 5, 'LH-0005-01', 30, 25, 30000.00, '2026-11-03'),
(2, 5, 'LH-0005-02', 10, 10, 40000.00, '2026-11-05'),
(3, 5, 'LH-0005-03', 10, 10, 20000.00, '2026-11-08'),
(4, 1, 'LH-0001-01', 30, 30, 25000.00, '2026-11-15'),
(5, 2, 'LH-0002-01', 30, 30, 30000.00, '2026-11-15'),
(6, 1, 'LH-0001-02', 30, 31, 30000.00, '2026-11-15'),
(7, 4, 'LH-0004-01', 10, 10, 30000.00, '2026-11-15'),
(8, 6, 'LH-0006-01', 1, 0, 20000.00, '2026-11-03'),
(9, 6, 'LH-0006-02', 2, 0, 17000.00, '2026-11-03'),
(10, 6, 'LH-0006-03', 1, 0, 17000.00, '2026-11-03'),
(11, 6, 'LH-0006-04', 1, 0, 20000.00, '2026-11-03'),
(12, 3, 'LH-0003-01', 30, 30, 30000.00, '2026-11-15'),
(13, 7, 'LH-0007-01', 20, 21, 60000.00, '2026-11-15'),
(14, 8, 'LH-0008-01', 20, 20, 40000.00, '2026-12-06'),
(15, 9, 'LH-0009-01', 30, 30, 60000.00, '2026-12-06'),
(16, 10, 'LH-0010-01', 20, 20, 50000.00, '2026-12-06'),
(17, 11, 'LH-0011-01', 20, 20, 40000.00, '2026-12-06'),
(18, 12, 'LH-0012-01', 10, 10, 120000.00, '2026-12-06'),
(19, 13, 'LH-0013-01', 10, 10, 100000.00, '2026-12-06'),
(20, 14, 'LH-0014-01', 10, 10, 90000.00, '2026-12-06'),
(21, 15, 'LH-0015-01', 10, 10, 90000.00, '2026-12-06'),
(22, 16, 'LH-0016-01', 10, 10, 110000.00, '2026-12-06'),
(23, 26, 'LH-0026-01', 5, 5, 700000.00, '2026-12-06'),
(24, 23, 'LH-0023-01', 5, 5, 400000.00, '2026-12-06'),
(25, 24, 'LH-0024-01', 5, 5, 500000.00, '2026-12-06'),
(26, 25, 'LH-0025-01', 5, 5, 600000.00, '2026-12-06'),
(27, 27, 'LH-0027-01', 30, 30, 40000.00, '2026-12-06'),
(28, 28, 'LH-0028-01', 30, 30, 30000.00, '2026-12-06'),
(29, 29, 'LH-0029-01', 20, 15, 130000.00, '2026-12-06'),
(30, 30, 'LH-0030-01', 20, 20, 25000.00, '2026-12-06'),
(31, 31, 'LH-0031-01', 30, 30, 38000.00, '2026-12-06'),
(32, 32, 'LH-0032-01', 20, 20, 48000.00, '2026-12-06'),
(33, 33, 'LH-0033-01', 20, 20, 39000.00, '2026-12-06'),
(34, 34, 'LH-0034-01', 20, 20, 90000.00, '2026-12-06'),
(35, 22, 'LH-0022-01', 5, 5, 400000.00, '2026-11-15'),
(36, 6, 'LH-0006-05', 20, 20, 20000.00, '2026-11-15');

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
(1, 'Lựu', 'Hạt mọng nước như hồng ngọc, vị ngọt thanh mát, giàu vitamin C và chất xơ. Thích hợp ăn tươi, ép nước hoặc làm topping. Bảo quản ngăn mát tủ lạnh dùng trong 1-2 tuần.', 'Trái cây Việt Nam', 'Kg', 61, 35000.00, '2026-11-15', '1745559354_Lựu.jpg', 1),
(2, 'Nho', 'Nho trồng tại vườn Ninh Thuận trái nhỏ vừa, chùm khít, vị ngọt thanh tự nhiên xen lẫn vị chua nhẹ đặc trưng, giá cả bình dân', 'Trái cây Việt Nam', 'Kg', 30, 50000.00, '2026-11-15', '1745559541_Nho.jpg', 1),
(3, 'Chôm chôm', 'Chôm chôm tươi ngọt lịm, cùi dày giòn, hạt tróc, mọng nước. Được tuyển chọn từ những nhà vườn đạt chuẩn, cam kết an toàn, tươi ngon mỗi ngày.', 'Trái cây Việt Nam', 'Kg', 30, 40000.00, '2026-11-15', '1745561394_Chômchôm.jpg', 1),
(4, 'Bơ', 'Có xuất xứ từ vùng đất cao nguyên Lâm Đồng (Bảo Lộc), được nhân rộng thành đặc sản trứ danh của Tây Nguyên. Quả thon dài, hạt nhỏ, cơm vàng đậm, dẻo quánh, vị béo ngậy và thơm đặc trưng.', 'Trái cây Việt Nam', 'Kg', 10, 40000.00, '2026-11-15', '1745561438_Bơ.jpg', 1),
(5, 'Dâu tây', 'Nguồn gốc từ Đà Lạt (Lâm Đồng); quả đỏ tươi căng mọng, hình trái tim, vị chua ngọt thanh mát và thơm dịu.', 'Trái cây Việt Nam', 'Kg', 45, 40000.00, '2026-11-03', '1745561466_Dâutây.jpg', 1),
(6, 'Dưa hấu', 'Nguồn gốc chủ yếu từ Long An và Tiền Giang, sở hữu vỏ xanh sọc đặc trưng cùng phần ruột đỏ tươi mọng nước, mang đến vị ngọt thanh giải nhiệt cực kỳ hiệu quả.', 'Trái cây Việt Nam', 'Kg', 20, 25000.00, '2026-11-15', '1745561526_DưaHấu.jpg', 1),
(7, 'Việt quất', 'Nguồn gốc nhập khẩu từ Mỹ, Úc và New Zealand; quả tròn nhỏ màu xanh tím phủ lớp phấn trắng tự nhiên, thịt mọng nước với vị ngọt thanh xen lẫn chua nhẹ và chứa hàm lượng chống oxy hóa cao.', 'Trái cây nhập khẩu', 'Kg', 21, 70000.00, '2026-11-15', '1745562262_ViệtQuất.jpg', 1),
(8, 'Táo Mỹ', 'Nguồn gốc nhập khẩu từ tiểu bang Washington (Mỹ); quả tròn căng với vỏ màu đỏ sẫm đặc trưng, thịt táo giòn ngọt, mọng nước và đậm đà hương thơm tự nhiên.', 'Trái cây nhập khẩu', 'Kg', 20, 50000.00, '2026-12-06', '1745562301_TáoMỹ.jpg', 1),
(9, 'Nho Mỹ', 'Nguồn gốc nhập khẩu từ bang California (Mỹ); trái thon dài hoặc tròn mọng, vỏ mỏng mịn, thịt giòn ngọt đậm đà, không hạt và thơm tự nhiên.', 'Trái cây nhập khẩu', 'Kg', 30, 70000.00, '2026-12-06', '1745562325_NhoMỹ.jpg', 1),
(10, 'Cherry', 'Nguồn gốc nhập khẩu từ Mỹ, Chile và New Zealand; quả tròn mọng vỏ đỏ thẫm bóng mượt, thịt quả mọng nước, giòn sần sật với vị ngọt đậm thanh mát đặc trưng.', 'Trái cây nhập khẩu', 'Kg', 20, 60000.00, '2026-12-06', '1745562345_Cherry.jpg', 1),
(11, 'Kiwi', 'Nguồn gốc nhập khẩu từ New Zealand và Chile; quả dạng hình trứng vỏ nâu phủ lông tơ, ruột xanh hoặc vàng óng ánh điểm hạt đen nhỏ, vị chua ngọt hài hòa và giàu vitamin C.', 'Trái cây nhập khẩu', 'Kg', 20, 50000.00, '2026-12-06', '1745562367_Kiwi.jpg', 1),
(12, 'Mận sấy dẻo', 'Nguồn gốc từ vùng ôn đới như Lâm Đồng hoặc hàng nhập khẩu chất lượng cao; quả sấy giữ nguyên dạng tròn đều, vỏ sậm màu, thịt dẻo quánh, vị chua ngọt hài hòa và đậm đà hương thơm tự nhiên.', 'Quả sấy khô', 'Kg', 10, 130000.00, '2026-12-06', '1745588924_Mận sấy dẻo.jpg', 1),
(13, 'Kiwi sấy dẻo', 'Nguồn gốc từ Kiwi tươi chọn lọc chất lượng cao; lát kiwi giữ nguyên màu xanh tươi mát, dẻo mềm tự nhiên, vị chua nhẹ hài hòa cùng độ ngọt thanh đậm đà cực kỳ đưa miệng.', 'Quả sấy khô', 'Kg', 10, 120000.00, '2026-12-06', '1745589052_Kiwi sấy dẻo.webp', 1),
(14, 'Mít sấy khô', 'Nguồn gốc từ các vùng trồng mít nổi tiếng ở miền Tây Nam Bộ; múi mít có màu vàng tươi giòn rụm, giữ trọn vị ngọt đậm tự nhiên và hương thơm ngào ngạt đặc trưng.', 'Quả sấy khô', 'Kg', 10, 100000.00, '2026-12-06', '1745589104_Mít sấy.jpg', 1),
(15, 'Vải thiều sấy', 'Nguồn gốc từ Bắc Giang (Lục Ngạn); vỏ ngoài màu sậm khô róc, cùi vải dẻo quánh ngả màu nâu cánh gián, vị ngọt đậm đà và hương thơm nồng nàn đặc trưng.', 'Quả sấy khô', 'Kg', 10, 100000.00, '2026-12-06', '1745589217_Vải thiều sấy.jpg', 1),
(16, 'Quả sấy khô tổng hợp', 'Nguồn gốc từ nhiều loại trái cây tươi chọn lọc (chuối, mít, khoai lang, khổ qua, cà rốt...); miếng sấy giòn rụm giữ trọn màu sắc tự nhiên, vị ngọt thanh đa dạng và giàu chất xơ dinh dưỡng.', 'Quả sấy khô', 'Kg', 10, 120000.00, '2026-12-06', '1745589258_Quả sấy khô tổng hợp.webp', 1),
(22, 'Giỏ trái cây 1', 'Thiết kế sang trọng kết hợp từ các loại trái cây nhập khẩu tươi ngon tuyển chọn (cherry, táo Envy, nho xanh không hạt); trang trí tỉ mỉ cùng nơ và phụ kiện lịch sự, là món quà hoàn hảo để biếu tặng, thăm hỏi hoặc chúc mừng trong các dịp đặc biệt.', 'Giỏ trái cây', 'Giỏ', 5, 450000.00, '2026-11-15', '1745675804_Giỏ quả trái cây 1.jpg', 1),
(23, 'Giỏ trái cây 2', 'Thiết kế sang trọng kết hợp từ các loại trái cây nhập khẩu tươi ngon tuyển chọn (cherry, táo Envy, nho xanh không hạt); trang trí tỉ mỉ cùng nơ và phụ kiện lịch sự, là món quà hoàn hảo để biếu tặng, thăm hỏi hoặc chúc mừng trong các dịp đặc biệt.', 'Giỏ trái cây', 'Giỏ', 5, 500000.00, '2026-12-06', '1792907621_Giỏ quả trái cây 2.jpg', 1),
(24, 'GIỏ trái cây 3', 'Thiết kế sang trọng kết hợp từ các loại trái cây nhập khẩu tươi ngon tuyển chọn (cherry, táo Envy, nho xanh không hạt); trang trí tỉ mỉ cùng nơ và phụ kiện lịch sự, là món quà hoàn hảo để biếu tặng, thăm hỏi hoặc chúc mừng trong các dịp đặc biệt.', 'Giỏ trái cây', 'Giỏ', 5, 600000.00, '2026-12-06', '1745676174_Giỏ quả trái cây 3.jpg', 1),
(25, 'Giỏ trái cây 4', 'Thiết kế sang trọng kết hợp từ các loại trái cây nhập khẩu tươi ngon tuyển chọn (cherry, táo Envy, nho xanh không hạt); trang trí tỉ mỉ cùng nơ và phụ kiện lịch sự, là món quà hoàn hảo để biếu tặng, thăm hỏi hoặc chúc mừng trong các dịp đặc biệt.', 'Giỏ trái cây', 'Giỏ', 5, 700000.00, '2026-12-06', '1745676210_Giỏ quả trái cây 4.jpg', 1),
(26, 'Giỏ trái cây 5', 'Thiết kế sang trọng kết hợp từ các loại trái cây nhập khẩu tươi ngon tuyển chọn (cherry, táo Envy, nho xanh không hạt); trang trí tỉ mỉ cùng nơ và phụ kiện lịch sự, là món quà hoàn hảo để biếu tặng, thăm hỏi hoặc chúc mừng trong các dịp đặc biệt.', 'Giỏ trái cây', 'Giỏ', 5, 800000.00, '2026-12-06', '1745676240_Giỏ quả trái cây 5.jpg', 1),
(27, 'Bưởi da xanh', 'Giống bưởi đặc sản nổi tiếng với lớp vỏ xanh tươi, tép bưởi hồng đỏ căng mọng nước, dễ bóc, múi róc không dính tôm, vị ngọt thanh đậm đà tự nhiên và không hề bị đắng. Từ 1,2kg - 1,4kg / quả', 'Trái cây Việt Nam', 'Quả', 30, 50000.00, '2026-12-06', '1792906627_bưởi_da_xanh.jpg', 1),
(28, 'Xoài tứ quý', 'Giống xoài quả \"khủng\" đặc sản với vỏ xanh đậm, thịt quả dày, hạt mỏng, khi chín hay xanh đều có độ giòn hoặc dẻo ngọt đặc trưng, vị thơm đậm đà và ngọt lịm không bị chua gắt.', 'Trái cây Việt Nam', 'Kg', 30, 35000.00, '2026-12-06', '1792906718_xoai_tu_quy.jpg', 1),
(29, 'Sầu riêng', 'Múi sầu đầy đặn, vàng ươm kích thích thị giác, lớp vỏ mỏng gai đều, phần cơm dày vàng óng, vị béo ngậy đậm đà thơm phức đặc trưng tan chảy ngay trong miệng. Trọng lượng từ 1,8kg - 2kg / quả', 'Trái cây Việt Nam', 'Quả', 15, 150000.00, '2026-12-06', '1792906825_Sầu riêng.jpg', 1),
(30, 'Thanh long ruột đỏ', 'Quả vỏ đỏ tươi bắt mắt, ruột bên trong đỏ hồng đậm đà, kết cấu thịt mọng nước kết hợp vị ngọt thanh mát đậm vị cùng hàng nghìn hạt nhỏ li ti giòn sần sật, giàu chất chống oxy hóa và vitamin giúp giải nhiệt cực tốt.', 'Trái cây Việt Nam', 'Kg', 20, 30000.00, '2026-12-06', '1792906925_Thanh long ruột đỏ.jpg', 1),
(31, 'Mãng cầu', 'Vỏ có gai mềm hoặc lớp vảy đặc trưng, phần thịt bên trong trắng ngà mềm mịn, kết cấu múi mọng nước, vị chua ngọt hài hòa đậm đà và hương thơm nức mũi cực kỳ hấp dẫn.', 'Trái cây Việt Nam', 'Kg', 30, 45000.00, '2026-12-06', '1792907050_Mãng cầu.jpg', 1),
(32, 'Măng cụt', 'Nguồn gốc từ nhà vườn tuyển chọn; vỏ dày màu tím sẫm đặc trưng, bên trong là những múi trắng ngà mọng nước như tép tỏi, vị ngọt thanh kết hợp chút chua nhẹ tự nhiên, thơm mát và cực kỳ giàu chất dinh dưỡng.', 'Trái cây Việt Nam', 'Kg', 20, 55000.00, '2026-12-06', '1792907160_Măng cụt.jpg', 1),
(33, 'Na', 'Lớp vỏ mỏng với các mắt na to nở đều, khi chín tỏa hương thơm dịu nhẹ, bên trong là phần thịt trắng ngà mềm mịn, từng múi dày thịt, hạt nhỏ, vị ngọt lịm đậm đà tự nhiên tan chảy trong miệng.', 'Trái cây Việt Nam', 'Kg', 20, 45000.00, '2026-12-06', '1792907255_Na.jpg', 1),
(34, 'Lê Hàn Quốc', 'Quả to tròn đều, vỏ màu vàng sáng mịn màng đẹp mắt; phần thịt bên trong trắng ngà, cấu trúc giòn sần sật, nhiều nước, vị ngọt đậm thanh mát tự nhiên mang lại cảm giác giải khát tuyệt vời.', 'Trái cây nhập khẩu', 'Kg', 20, 119000.00, '2026-12-06', '1792907371_Lê hàn quốc.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

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
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `ct_gio_hang`
--
ALTER TABLE `ct_gio_hang`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `don_hang`
--
ALTER TABLE `don_hang`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `gio_hang`
--
ALTER TABLE `gio_hang`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `khuyen_mai`
--
ALTER TABLE `khuyen_mai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `lo_hang`
--
ALTER TABLE `lo_hang`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

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
