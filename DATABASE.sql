-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 13, 2020 lúc 04:48 AM
-- Phiên bản máy phục vụ: 10.4.14-MariaDB
-- Phiên bản PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `cake_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_tiet_don_hang`
--

CREATE TABLE `chi_tiet_don_hang` (
  `ma_don_hang` int(11) NOT NULL,
  `ma_san_pham` int(11) NOT NULL,
  `so_luong_ban` int(11) NOT NULL,
  `don_gia` int(11) NOT NULL,
  `thanh_tien` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_muc`
--

CREATE TABLE `danh_muc` (
  `ma_danh_muc` int(11) NOT NULL,
  `ten_danh_muc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `mo_ta` text NOT NULL,
  `thoi_gian_tao` datetime NOT NULL DEFAULT current_timestamp(),
  `thoi_gian_cap_nhat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_hang`
--

CREATE TABLE `don_hang` (
  `ma_don_hang` int(11) NOT NULL,
  `ten_khach_hang` varchar(255) NOT NULL,
  `dien_thoai_khach_hang` varchar(255) NOT NULL,
  `dia_chi_giao_hang` text NOT NULL,
  `thoi_gian_giao_hang` timestamp NULL DEFAULT NULL,
  `ghi_chu_khach_hang` text NOT NULL,
  `tong_tien` int(15) NOT NULL,
  `tinh_trang` int(11) NOT NULL COMMENT '0: đã hủy. 1: chờ xác nhận | 2: đã xác nhận | 3: thành công\r\n',
  `ghi_chu_nhan_vien` text NOT NULL,
  `thoi_gian_tao` datetime NOT NULL DEFAULT current_timestamp(),
  `lich_su` text NOT NULL DEFAULT '""',
  `nhan_vien_cap_nhat` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `ma_nguoi_dung` int(11) NOT NULL,
  `ten_nguoi_dung` varchar(255) NOT NULL,
  `dien_thoai` varchar(255) DEFAULT NULL,
  `loai` int(11) NOT NULL COMMENT '1: nhân viên . 2: Admin',
  `email` varchar(255) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `anh_nguoi_dung` varchar(255) NOT NULL,
  `dang_nhap_gan_nhat` datetime DEFAULT NULL,
  `thoi_gian_tao` datetime NOT NULL DEFAULT current_timestamp(),
  `thoi_gian_cap_nhat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `san_pham`
--

CREATE TABLE `san_pham` (
  `ma_san_pham` int(11) NOT NULL,
  `ten_san_pham` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `mo_ta_san_pham` text NOT NULL COMMENT 'mô tả tóm tắt',
  `gia` int(15) NOT NULL,
  `tinh_trang` int(11) NOT NULL COMMENT '0: ngưng bán, 1: mở bán',
  `phan_loai` int(11) NOT NULL COMMENT '0: sản phẩm thường. 1: sản phẩm nổi bật',
  `anh_san_pham` varchar(255) NOT NULL COMMENT 'đường dẫn ảnh',
  `ma_danh_muc` int(11) NOT NULL COMMENT 'Khóa ngoại bảng danh_muc',
  `thoi_gian_tao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD PRIMARY KEY (`ma_don_hang`,`ma_san_pham`);

--
-- Chỉ mục cho bảng `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD PRIMARY KEY (`ma_danh_muc`);

--
-- Chỉ mục cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD PRIMARY KEY (`ma_don_hang`);

--
-- Chỉ mục cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`ma_nguoi_dung`);

--
-- Chỉ mục cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  ADD PRIMARY KEY (`ma_san_pham`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `danh_muc`
--
ALTER TABLE `danh_muc`
  MODIFY `ma_danh_muc` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  MODIFY `ma_don_hang` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `ma_nguoi_dung` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  MODIFY `ma_san_pham` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
