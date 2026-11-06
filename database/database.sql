

-- 1. Táº¡o báº£ng Vai trÃ²
CREATE TABLE `vai_tro` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ten` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Táº¡o báº£ng NgÆ°á»i dÃ¹ng 
CREATE TABLE `nguoi_dung` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_vai_tro` INT DEFAULT NULL,
  `ho_ten` VARCHAR(100) DEFAULT NULL,
  `gioi_tinh` VARCHAR(10) DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `ten_dang_nhap` VARCHAR(50) DEFAULT NULL,
  `mat_khau` VARCHAR(255) DEFAULT NULL,
  `so_lan_sai` INT DEFAULT 0,
  `thoi_gian_khoa` DATETIME DEFAULT NULL,
  `ma_otp` VARCHAR(10) DEFAULT NULL,
  `het_han_otp` DATETIME DEFAULT NULL,
  `trang_thai` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `fk_nguoi_dung_vai_tro` (`id_vai_tro`),
  CONSTRAINT `fk_nguoi_dung_vai_tro` FOREIGN KEY (`id_vai_tro`) REFERENCES `vai_tro` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Táº¡o báº£ng Sáº£n pháº©m
CREATE TABLE `san_pham` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `ten` VARCHAR(100) DEFAULT NULL,
  `loai` VARCHAR(50) DEFAULT NULL,
  `don_vi` VARCHAR(50) DEFAULT NULL,
  `so_luong` INT DEFAULT 0,
  `gia_nhap` DECIMAL(15,2) DEFAULT 0.00,
  `gia` DECIMAL(15,2) DEFAULT 0,
  `anh` VARCHAR(255) DEFAULT NULL,
  `trang_thai` TINYINT(1) DEFAULT '1',
  `mo_ta` TEXT DEFAULT NULL,
  `ngay_het_han` DATE DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Táº¡o báº£ng ÄÆ¡n hÃ ng 
CREATE TABLE `don_hang` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_nguoi_dung` BIGINT DEFAULT NULL,
  `ten` VARCHAR(255) DEFAULT NULL,
  `sdt` VARCHAR(20) DEFAULT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  `dia_chi` VARCHAR(255) DEFAULT NULL,
  `latitude` DECIMAL(10, 8) DEFAULT NULL,
  `longitude` DECIMAL(11, 8) DEFAULT NULL,
  `tong_tien` DECIMAL(15,2) DEFAULT 0,
  `phi_van_chuyen` DECIMAL(12, 2) DEFAULT 0,
  `ma_khuyen_mai` VARCHAR(50) DEFAULT NULL,
  `tien_giam` DECIMAL(15,2) DEFAULT 0,
  `ly_do_tra_hang` TEXT DEFAULT NULL,
  `hinh_anh_tra` VARCHAR(255) DEFAULT NULL,
  `het_han_tra_hang` DATETIME DEFAULT NULL,
  `ly_do_tu_choi_tra` TEXT DEFAULT NULL,
  `het_han_thanh_toan` DATETIME DEFAULT NULL,
  `trang_thai` VARCHAR(100) DEFAULT NULL,
  `trang_thai_thanh_toan` VARCHAR(50) DEFAULT 'ChÆ°a thanh toÃ¡n',
  `ma_chuyen_khoan` VARCHAR(100) DEFAULT NULL,
  `pt_thanh_toan` VARCHAR(255) DEFAULT NULL,
  `ngay_dat` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_don_hang_nguoi_dung` (`id_nguoi_dung`),
  CONSTRAINT `fk_don_hang_nguoi_dung` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoi_dung` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Táº¡o báº£ng Giá» hÃ ng
CREATE TABLE `gio_hang` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_nguoi_dung` BIGINT DEFAULT NULL,
  `so_luong_sp` INT DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_gio_hang_nguoi_dung` (`id_nguoi_dung`),
  CONSTRAINT `fk_gio_hang_nguoi_dung` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoi_dung` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Táº¡o báº£ng Chi tiáº¿t giá» hÃ ng
CREATE TABLE `ct_gio_hang` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_gio_hang` BIGINT DEFAULT NULL,
  `id_san_pham` BIGINT DEFAULT NULL,
  `so_luong` INT DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `fk_ct_gio_hang_san_pham` (`id_san_pham`),
  KEY `fk_ct_gio_hang_gio_hang` (`id_gio_hang`),
  CONSTRAINT `fk_ct_gio_hang_gio_hang` FOREIGN KEY (`id_gio_hang`) REFERENCES `gio_hang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ct_gio_hang_san_pham` FOREIGN KEY (`id_san_pham`) REFERENCES `san_pham` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Táº¡o báº£ng Chi tiáº¿t Ä‘Æ¡n hÃ ng
CREATE TABLE `ct_don_hang` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_don_hang` BIGINT DEFAULT NULL,
  `id_san_pham` BIGINT DEFAULT NULL,
  `so_luong` INT DEFAULT 1,
  `gia` DECIMAL(15,2) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_ct_don_hang_don_hang` (`id_don_hang`),
  KEY `fk_ct_don_hang_san_pham` (`id_san_pham`),
  CONSTRAINT `fk_ct_don_hang_don_hang` FOREIGN KEY (`id_don_hang`) REFERENCES `don_hang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ct_don_hang_san_pham` FOREIGN KEY (`id_san_pham`) REFERENCES `san_pham` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Tao bang Lo hang (Batch/Lot management)
DROP TABLE IF EXISTS `lo_hang`;

CREATE TABLE `lo_hang` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_san_pham` BIGINT NOT NULL,
  `ma_lo` VARCHAR(50) NOT NULL,
  `so_luong_nhap` INT NOT NULL,
  `so_luong_con_lai` INT NOT NULL DEFAULT 0,
  `gia_nhap` DECIMAL(15,2) DEFAULT 0.00,
  `ngay_het_han` DATE NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_lo_hang_san_pham` (`id_san_pham`),
  CONSTRAINT `fk_lo_hang_san_pham` FOREIGN KEY (`id_san_pham`) REFERENCES `san_pham` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `khuyen_mai` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ma_khuyen_mai` VARCHAR(50) NOT NULL UNIQUE,       
    `ten_chuong_trinh` VARCHAR(255) NOT NULL,          
    `loai_giam` ENUM('phan_tram', 'tien_mat') NOT NULL, 
    `gia_tri_giam` DECIMAL(10,2) NOT NULL,             
    `giam_toi_da` DECIMAL(10,2) NULL,                  
    `don_hang_toi_thieu` DECIMAL(10,2) DEFAULT 0,      
    `so_luong` INT DEFAULT 100,                        
    `da_dung` INT DEFAULT 0,                           
    `ngay_bat_dau` DATETIME NOT NULL,                  
    `ngay_ket_thuc` DATETIME NOT NULL,                 
    `trang_thai` TINYINT(1) DEFAULT 1                  
);
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` VARCHAR(255) NOT NULL,
  `user_id` BIGINT UNSIGNED NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` TEXT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `sessions_user_id_index` (`user_id`),
  INDEX `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `vai_tro` VALUES (1,'USER'),(2,'ADMIN'),(3,'STAFF');

INSERT INTO `nguoi_dung` 
(`id`,`id_vai_tro`, `ho_ten`, `gioi_tinh`, `email`, `ten_dang_nhap`, `mat_khau`, `ma_otp`, `het_han_otp`, `so_lan_sai`, `thoi_gian_khoa`) 
VALUES 
(1, 2, 'Nguyá»…n VÄƒn Amdmin', 'NAM', 'phuc40965@gmail.com', 'admin', '12345678', NULL, NULL, 0, NULL);


INSERT INTO `san_pham` (`id`, `ten`, `loai`, `don_vi`, `so_luong`, `gia`, `anh`, `trang_thai`) VALUES
(1, 'Lá»±u', 'TrÃ¡i cÃ¢y Viá»‡t Nam', 'Kg', 1, 30000, '1745559354_Lá»±u.jpg', 1),
(2, 'Nho', 'TrÃ¡i cÃ¢y Viá»‡t Nam', 'Kg', 1, 50000, '1745559541_Nho.jpg', 1),
(3, 'ChÃ´m chÃ´m', 'TrÃ¡i cÃ¢y Viá»‡t Nam', 'Kg', 1, 40000, '1745561394_ChÃ´mchÃ´m.jpg', 1),
(4, 'BÆ¡', 'TrÃ¡i cÃ¢y Viá»‡t Nam', 'Kg', 1, 45000, '1745561438_BÆ¡.jpg', 1),
(5, 'DÃ¢u tÃ¢y', 'TrÃ¡i cÃ¢y Viá»‡t Nam', 'Kg', 1, 40000, '1745561466_DÃ¢utÃ¢y.jpg', 1),
(6, 'DÆ°a háº¥u', 'TrÃ¡i cÃ¢y Viá»‡t Nam', 'Kg', 1, 20000, '1745561526_DÆ°aHáº¥u.jpg', 1),
(7, 'Viá»‡t quáº¥t', 'TrÃ¡i cÃ¢y nháº­p kháº©u', 'Kg', 1, 70000, '1745562262_Viá»‡tQuáº¥t.jpg', 1),
(8, 'TÃ¡o Má»¹', 'TrÃ¡i cÃ¢y nháº­p kháº©u', 'Kg', 1, 50000, '1745562301_TÃ¡oMá»¹.jpg', 1),
(9, 'Nho Má»¹', 'TrÃ¡i cÃ¢y nháº­p kháº©u', 'Kg', 1, 70000, '1745562325_NhoMá»¹.jpg', 1),
(10, 'Cherry', 'TrÃ¡i cÃ¢y nháº­p kháº©u', 'Kg', 1, 60000, '1745562345_Cherry.jpg', 1),
(11, 'Kiwi', 'TrÃ¡i cÃ¢y nháº­p kháº©u', 'Kg', 1, 50000, '1745562367_Kiwi.jpg', 1),
(12, 'Máº­n sáº¥y dáº»o', 'Quáº£ sáº¥y khÃ´', 'Kg', 1, 130000, '1745588924_Máº­n sáº¥y dáº»o.jpg', 1),
(13, 'Kiwi sáº¥y dáº»o', 'Quáº£ sáº¥y khÃ´', 'Kg', 1, 120000, '1745589052_Kiwi sáº¥y dáº»o.webp', 1),
(14, 'MÃ­t sáº¥y khÃ´', 'Quáº£ sáº¥y khÃ´', 'Kg', 1, 100000, '1745589104_MÃ­t sáº¥y.jpg', 1),
(15, 'Váº£i thiá»u sáº¥y', 'Quáº£ sáº¥y khÃ´', 'Kg', 1, 100000, '1745589217_Váº£i thiá»u sáº¥y.jpg', 1),
(16, 'Quáº£ sáº¥y khÃ´ tá»•ng há»£p', 'Quáº£ sáº¥y khÃ´', 'Kg', 1, 120000, '1745589258_Quáº£ sáº¥y khÃ´ tá»•ng há»£p.webp', 1),
(17, 'NÆ°á»›c Ã©p kiwi', 'Äá»“ uá»‘ng trÃ¡i cÃ¢y', 'Ly', 1, 40000, '1745675302_NÆ°á»›c Ã©p kiwi.jpg', 1),
(18, 'NÆ°á»›c Ã©p dÃ¢u', 'Äá»“ uá»‘ng trÃ¡i cÃ¢y', 'Ly', 1, 50000, '1745675427_NÆ°á»›c Ã©p dÃ¢u.jpg', 1),
(19, 'TrÃ  chanh báº¡c hÃ ', 'Äá»“ uá»‘ng trÃ¡i cÃ¢y', 'Ly', 1, 40000, '1745675505_TrÃ  chanh báº¡c hÃ .jpg', 1),
(20, 'NÆ°á»›c Ã©p chanh dÃ¢y', 'Äá»“ uá»‘ng trÃ¡i cÃ¢y', 'Ly', 1, 45000, '1745675540_NÆ°á»›c Ã©p chanh dÃ¢y.jpg', 1),
(21, 'NÆ°á»›c Ã©p dÆ°a háº¥u', 'Äá»“ uá»‘ng trÃ¡i cÃ¢y', 'Ly', 1, 40000, '1745675573_NÆ°á»›c Ã©p dÆ°a háº¥u.jpg', 1),
(22, 'Giá» trÃ¡i cÃ¢y 1', 'Giá» trÃ¡i cÃ¢y', 'Giá»', 1, 400000, '1745675804_Giá» quáº£ trÃ¡i cÃ¢y 1.jpg', 1),
(23, 'Giá» trÃ¡i cÃ¢y 2', 'Giá» trÃ¡i cÃ¢y', 'Giá»', 1, 500000, '1745676134_Giá» quáº£ trÃ¡i cÃ¢y 2.webp', 1),
(24, 'GIá» trÃ¡i cÃ¢y 3', 'Giá» trÃ¡i cÃ¢y', 'Giá»', 2, 600000, '1745676174_Giá» quáº£ trÃ¡i cÃ¢y 3.jpg', 1),
(25, 'Giá» trÃ¡i cÃ¢y 4', 'Giá» trÃ¡i cÃ¢y', 'Giá»', 1, 700000, '1745676210_Giá» quáº£ trÃ¡i cÃ¢y 4.jpg', 1),
(26, 'Giá» trÃ¡i cÃ¢y 5', 'Giá» trÃ¡i cÃ¢y', 'Giá»', 1, 800000, '1745676240_Giá» quáº£ trÃ¡i cÃ¢y 5.jpg', 1);

-- Khá»Ÿi táº¡o lÃ´ hÃ ng ban Ä‘áº§u cho cÃ¡c sáº£n pháº©m
INSERT INTO `lo_hang` (`id_san_pham`, `ma_lo`, `so_luong_nhap`, `so_luong_con_lai`, `gia_nhap`, `ngay_san_xuat`, `ngay_het_han`, `ngay_nhap`, `trang_thai`)
SELECT 
    `id`, 
    CONCAT('LH-', LPAD(`id`, 4, '0'), '-01'), 
    `so_luong`, 
    `so_luong`, 
    `gia_nhap`, 
    CURRENT_DATE, 
    IFNULL(`ngay_het_han`, DATE_ADD(CURRENT_DATE, INTERVAL 30 DAY)), 
    CURRENT_DATE, 
    1
FROM `san_pham`;
