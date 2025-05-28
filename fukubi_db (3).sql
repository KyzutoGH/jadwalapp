-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 28 Bulan Mei 2025 pada 07.54
-- Versi server: 8.0.30
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fukubi_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang_jadi`
--

CREATE TABLE `barang_jadi` (
  `id_barang` int NOT NULL,
  `id_jaket` int NOT NULL,
  `id_sticker` int NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `gambar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `stock` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `barang_jadi`
--

INSERT INTO `barang_jadi` (`id_barang`, `id_jaket`, `id_sticker`, `nama_produk`, `gambar`, `stock`) VALUES
(13, 11, 13, 'JAKET RRQ VOL 1', '1739341965_rrq_vol_1.jpg', 1),
(14, 11, 14, 'JAKET RRQ VOL 1', '1739341965_rrq_vol_1.jpg', 0),
(15, 11, 15, 'JAKET RRQ VOL 1', '1739341965_rrq_vol_1.jpg', 0),
(16, 12, 13, 'JAKET RRQ VOL 1', '1739342051_rrq_vol_1.jpg', 0),
(17, 12, 14, 'JAKET RRQ VOL 1', '1739342051_rrq_vol_1.jpg', 0),
(18, 12, 15, 'JAKET RRQ VOL 1', '1739342051_rrq_vol_1.jpg', 0),
(19, 13, 13, 'JAKET RRQ VOL 1', '1739342271_rrq_vol_1.jpg', 0),
(20, 13, 14, 'JAKET RRQ VOL 1', '1739342271_rrq_vol_1.jpg', 0),
(21, 13, 15, 'JAKET RRQ VOL 1', '1739342271_rrq_vol_1.jpg', 0),
(22, 14, 13, 'JAKET RRQ VOL 1', '1739342334_rrq_vol_1.jpg', 0),
(23, 14, 14, 'JAKET RRQ VOL 1', '1739342334_rrq_vol_1.jpg', 0),
(24, 14, 15, 'JAKET RRQ VOL 1', '1739342334_rrq_vol_1.jpg', 0),
(25, 15, 13, 'JAKET RRQ VOL 1', '1739342399_rrq_vol_1.jpg', 0),
(26, 15, 14, 'JAKET RRQ VOL 1', '1739342399_rrq_vol_1.jpg', 0),
(27, 15, 15, 'JAKET RRQ VOL 1', '1739342399_rrq_vol_1.jpg', 0),
(28, 16, 13, 'JAKET RRQ VOL 1', '1739342570_rrq_vol_1.jpg', 0),
(29, 16, 14, 'JAKET RRQ VOL 1', '1739342570_rrq_vol_1.jpg', 0),
(30, 16, 15, 'JAKET RRQ VOL 1', '1739342570_rrq_vol_1.jpg', 0),
(31, 17, 13, 'JAKET RRQ VOL 1', '1739342665_rrq_vol_1.jpg', 0),
(32, 17, 14, 'JAKET RRQ VOL 1', '1739342665_rrq_vol_1.jpg', 0),
(33, 17, 15, 'JAKET RRQ VOL 1', '1739342665_rrq_vol_1.jpg', 0),
(34, 18, 13, 'JAKET RRQ VOL 1', '1739342701_rrq_vol_1.jpg', 0),
(35, 18, 14, 'JAKET RRQ VOL 1', '1739342701_rrq_vol_1.jpg', 0),
(36, 18, 15, 'JAKET RRQ VOL 1', '1739342701_rrq_vol_1.jpg', 0),
(37, 19, 16, 'JAKET RRQ VOL 2', '1739342807_rrq_vol_2.jpg', 0),
(38, 19, 17, 'JAKET RRQ VOL 2', '1739342807_rrq_vol_2.jpg', 0),
(39, 19, 18, 'JAKET RRQ VOL 2', '1739342807_rrq_vol_2.jpg', 0),
(40, 20, 16, 'JAKET RRQ VOL 2', '1739342859_rrq_vol_2.jpg', 0),
(41, 20, 17, 'JAKET RRQ VOL 2', '1739342859_rrq_vol_2.jpg', 0),
(42, 20, 18, 'JAKET RRQ VOL 2', '1739342859_rrq_vol_2.jpg', 0),
(43, 21, 16, 'JAKET RRQ VOL 2', '1739342890_rrq_vol_2.jpg', 0),
(44, 21, 17, 'JAKET RRQ VOL 2', '1739342890_rrq_vol_2.jpg', 0),
(45, 21, 18, 'JAKET RRQ VOL 2', '1739342890_rrq_vol_2.jpg', 0),
(46, 22, 16, 'JAKET RRQ VOL 2', '1739342936_rrq_vol_2.jpg', 0),
(47, 22, 17, 'JAKET RRQ VOL 2', '1739342936_rrq_vol_2.jpg', 0),
(48, 22, 18, 'JAKET RRQ VOL 2', '1739342936_rrq_vol_2.jpg', 0),
(49, 23, 16, 'JAKET RRQ VOL 2', '1739342983_rrq_vol_2.jpg', 0),
(50, 23, 17, 'JAKET RRQ VOL 2', '1739342983_rrq_vol_2.jpg', 0),
(51, 23, 18, 'JAKET RRQ VOL 2', '1739342983_rrq_vol_2.jpg', 0),
(52, 24, 16, 'JAKET RRQ VOL 2', '1739343084_rrq_vol_2.jpg', 0),
(53, 24, 17, 'JAKET RRQ VOL 2', '1739343084_rrq_vol_2.jpg', 0),
(54, 24, 18, 'JAKET RRQ VOL 2', '1739343084_rrq_vol_2.jpg', 0),
(55, 25, 16, 'JAKET RRQ VOL 2', '1739343116_rrq_vol_2.jpg', 0),
(56, 25, 17, 'JAKET RRQ VOL 2', '1739343116_rrq_vol_2.jpg', 0),
(57, 25, 18, 'JAKET RRQ VOL 2', '1739343116_rrq_vol_2.jpg', 0),
(58, 26, 16, 'JAKET RRQ VOL 2', '1739343157_rrq_vol_2.jpg', 0),
(59, 26, 17, 'JAKET RRQ VOL 2', '1739343157_rrq_vol_2.jpg', 0),
(60, 26, 18, 'JAKET RRQ VOL 2', '1739343157_rrq_vol_2.jpg', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `datadn`
--

CREATE TABLE `datadn` (
  `id` int NOT NULL,
  `nama_sekolah` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `nomor` varchar(255) NOT NULL,
  `pemilik_kontak` varchar(255) NOT NULL,
  `jabatan` varchar(255) NOT NULL,
  `tanggal_dn` date NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `datadn`
--

INSERT INTO `datadn` (`id`, `nama_sekolah`, `alamat`, `nomor`, `pemilik_kontak`, `jabatan`, `tanggal_dn`, `status`) VALUES
(842331548, 'SD Negeri 6 Kepanjen', 'Jalan Mawar', '081234567890', 'Galvalum Setiawan', 'TU', '2025-05-26', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jaket`
--

CREATE TABLE `jaket` (
  `id_jaket` int NOT NULL,
  `jenis` varchar(255) NOT NULL,
  `namabarang` varchar(255) NOT NULL,
  `ukuran` varchar(255) NOT NULL,
  `harga` varchar(255) NOT NULL,
  `stock` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `jaket`
--

INSERT INTO `jaket` (`id_jaket`, `jenis`, `namabarang`, `ukuran`, `harga`, `stock`) VALUES
(11, 'Jaket', 'RRQ Vol 1', 'S', '125000', 109),
(12, 'Jaket', 'RRQ Vol 1', 'M', '125000', 0),
(13, 'Jaket', 'RRQ Vol 1', 'L', '125000', 0),
(14, 'Jaket', 'RRQ Vol 1', 'XL', '130000', 0),
(15, 'Jaket', 'RRQ Vol 1', 'XXL', '135000', 0),
(16, 'Jaket', 'RRQ Vol 1', '3XL', '135000', 0),
(17, 'Jaket', 'RRQ Vol 1', '4XL', '140000', 0),
(18, 'Jaket', 'RRQ Vol 1', '5XL', '145000', 0),
(19, 'Jaket', 'RRQ Vol 2', 'S', '140000', 0),
(20, 'Jaket', 'RRQ Vol 2', 'M', '140000', 0),
(21, 'Jaket', 'RRQ Vol 2', 'L', '140000', 0),
(22, 'Jaket', 'RRQ Vol 2', 'XL', '140000', 0),
(23, 'Jaket', 'RRQ Vol 2', 'XXL', '150000', 0),
(24, 'Jaket', 'RRQ Vol 2', '3XL', '155000', 0),
(25, 'Jaket', 'RRQ Vol 2', '4XL', '160000', 0),
(26, 'Jaket', 'RRQ Vol 2', '5XL', '160000', 0),
(27, 'Jaket', 'Ecentio', 'S', '50000', 1),
(28, 'Jaket', 'Testing', 'L', '100000', -1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_barang`
--

CREATE TABLE `log_barang` (
  `id_log` int NOT NULL,
  `id_jaket` int DEFAULT NULL,
  `id_sticker` int DEFAULT NULL,
  `id_barang` int DEFAULT NULL,
  `jenis_log` varchar(255) NOT NULL,
  `jumlah` varchar(255) NOT NULL,
  `tanggal` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `log_barang`
--

INSERT INTO `log_barang` (`id_log`, `id_jaket`, `id_sticker`, `id_barang`, `jenis_log`, `jumlah`, `tanggal`) VALUES
(33, NULL, 13, NULL, 'Tambah', '1', '2025-02-12 06:26:18'),
(34, NULL, 15, NULL, 'Tambah', '60', '2025-02-12 06:26:32'),
(35, NULL, 16, NULL, 'Tambah', '1', '2025-02-12 06:30:08'),
(36, NULL, 17, NULL, 'Tambah', '4', '2025-02-12 06:30:24'),
(37, NULL, 18, NULL, 'Tambah', '10', '2025-02-12 06:30:39'),
(38, 11, NULL, NULL, 'Tambah', '1', '2025-02-12 13:34:46'),
(39, 11, NULL, NULL, 'Kurangi', '1', '2025-02-12 13:35:02'),
(40, 25, NULL, NULL, 'Tambah', '1', '2025-02-12 13:53:27'),
(41, 25, 16, 55, 'Tambah', '1', '2025-02-12 13:54:32'),
(42, NULL, 13, NULL, 'Kurangi', '1', '2025-02-12 09:00:30'),
(43, 16, NULL, NULL, 'Kurangi', '1', '2025-02-12 16:06:04'),
(44, 11, NULL, NULL, 'Tambah', '10', '2025-04-23 15:23:03'),
(45, 11, NULL, NULL, 'Kurangi', '1', '2025-04-23 15:23:18'),
(46, 11, NULL, NULL, 'Tambah', '12', '2025-04-29 13:15:40'),
(47, 11, NULL, NULL, 'Update Stok jaket', 'Mengurangi stok RRQ Vol 1 sebanyak 1', '2025-05-04 01:25:50'),
(48, 11, NULL, NULL, 'Update Stok jaket', 'Mengurangi stok RRQ Vol 1 sebanyak 1', '2025-05-04 01:27:37'),
(49, NULL, 13, NULL, 'Tambah', '100', '2025-05-07 08:49:17'),
(50, 11, NULL, NULL, 'kurangi', '10', '2025-05-13 14:09:39'),
(51, 11, NULL, NULL, 'kurangi', '6', '2025-05-13 14:10:23'),
(52, NULL, 13, NULL, 'Tambah', '15', '2025-05-13 07:16:25'),
(53, NULL, 13, NULL, 'Kurangi', '5', '2025-05-13 07:16:42'),
(54, 28, NULL, NULL, 'kurangi', '90', '2025-05-15 09:41:37'),
(55, 28, NULL, NULL, 'kurangi', '11', '2025-05-15 09:56:07'),
(56, NULL, 13, NULL, 'kurangi', '10', '2025-05-15 11:19:33'),
(57, 27, NULL, NULL, 'kurangi', '1', '2025-05-15 11:38:14'),
(58, NULL, 13, NULL, 'kurangi', '5', '2025-05-15 11:38:14'),
(59, 11, NULL, NULL, 'kurangi', '100', '2025-05-17 13:44:24'),
(60, 27, NULL, NULL, 'tambah', '1 (Pengembalian dari pembatalan pesanan)', '2025-05-28 14:21:36'),
(61, NULL, 13, NULL, 'tambah', '5 (Pengembalian dari pembatalan pesanan)', '2025-05-28 14:21:36');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran_history`
--

CREATE TABLE `pembayaran_history` (
  `id` int NOT NULL,
  `penagihan_id` int NOT NULL,
  `cicilan_ke` int NOT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `metode` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `pembayaran_history`
--

INSERT INTO `pembayaran_history` (`id`, `penagihan_id`, `cicilan_ke`, `nominal`, `metode`, `tanggal`, `keterangan`) VALUES
(5, 19, 1, 25000.00, '', '2025-05-03', ''),
(6, 20, 1, 5000.00, '', '2025-05-03', ''),
(7, 19, 2, 100000.00, '', '2025-05-03', ''),
(8, 19, 3, 20000.00, '', '2025-05-03', ''),
(9, 20, 2, 125000.00, '', '2025-05-15', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penagihan`
--

CREATE TABLE `penagihan` (
  `id` int NOT NULL,
  `tanggal` date DEFAULT NULL,
  `customer` varchar(255) DEFAULT NULL,
  `kontak` varchar(15) NOT NULL,
  `total` decimal(15,2) DEFAULT NULL,
  `jumlah_dp` int DEFAULT NULL,
  `tanggal_pengambilan` date DEFAULT NULL,
  `dp1_tenggat` date DEFAULT NULL,
  `dp1_nominal` decimal(15,2) DEFAULT NULL,
  `dp2_tenggat` date DEFAULT NULL,
  `dp2_nominal` decimal(15,2) DEFAULT NULL,
  `dp3_tenggat` date DEFAULT NULL,
  `dp3_nominal` decimal(15,2) DEFAULT NULL,
  `pelunasan` decimal(15,2) DEFAULT NULL,
  `tgllunas` date DEFAULT NULL,
  `status` enum('1','2','3','4','5') DEFAULT NULL,
  `alasan_batal` text,
  `tgl_batal` datetime DEFAULT NULL,
  `dp1_status` tinyint(1) DEFAULT '0',
  `dp2_status` tinyint(1) DEFAULT '0',
  `dp3_status` tinyint(1) DEFAULT '0',
  `keterangan` text NOT NULL,
  `dp1_metode` varchar(50) DEFAULT NULL,
  `dp2_metode` varchar(50) DEFAULT NULL,
  `dp3_metode` varchar(50) DEFAULT NULL,
  `dp1_keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `dp2_keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `dp3_keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `penagihan`
--

INSERT INTO `penagihan` (`id`, `tanggal`, `customer`, `kontak`, `total`, `jumlah_dp`, `tanggal_pengambilan`, `dp1_tenggat`, `dp1_nominal`, `dp2_tenggat`, `dp2_nominal`, `dp3_tenggat`, `dp3_nominal`, `pelunasan`, `tgllunas`, `status`, `alasan_batal`, `tgl_batal`, `dp1_status`, `dp2_status`, `dp3_status`, `keterangan`, `dp1_metode`, `dp2_metode`, `dp3_metode`, `dp1_keterangan`, `dp2_keterangan`, `dp3_keterangan`) VALUES
(19, '2025-05-04', 'WE WOK DE TOK', '911', 125000.00, 3, '2025-08-08', '2025-05-03', 5000.00, '2025-05-03', 100000.00, '2025-05-03', 20000.00, NULL, '2025-05-03', '4', NULL, NULL, 1, 1, 1, 'Mantap', '', '', '', NULL, NULL, NULL),
(20, '2025-05-04', 'WE WOK DE TOK', '911', 125000.00, 3, '2025-08-08', '2025-05-03', 0.00, '2025-05-15', 125000.00, '2025-08-08', 0.00, NULL, '2025-05-15', '4', NULL, NULL, 1, 1, 0, 'Mantap', '', '', 'cash', NULL, NULL, NULL),
(21, '2025-05-15', 'Muhammad Limbad', '82214124123', 20000.00, 1, '2025-05-31', '2025-05-31', 0.00, NULL, 0.00, NULL, 0.00, NULL, NULL, '5', 'kebanyaan order', '2025-05-15 11:40:58', 0, 0, 0, '', 'cash', 'cash', 'cash', NULL, NULL, NULL),
(22, '2025-05-15', 'SMKN 4 Kepanjen (PIC: Rudi Tabudi)', '8123456789', 60000.00, 3, '2025-05-22', '2025-05-17', 0.00, '2025-05-19', 0.00, '2025-05-21', 0.00, NULL, NULL, '5', 'malas', '2025-05-28 14:21:36', 0, 0, 0, '', 'cash', 'cash', 'cash', NULL, NULL, NULL),
(23, '2025-05-17', 'SMK Brantas Karangkates (PIC: Ikhsan)', '82338424339', 12500000.00, 3, '2025-06-18', '2025-05-15', 0.00, '2025-06-06', 0.00, '2025-06-16', 0.00, NULL, NULL, '1', NULL, NULL, 0, 0, 0, 'Tambahkan nama SMK Brantas', 'cash', 'cash', 'cash', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penagihan_detail`
--

CREATE TABLE `penagihan_detail` (
  `id` int NOT NULL,
  `penagihan_id` int DEFAULT NULL,
  `jenis_barang` enum('jaket','stiker','barang_jadi') DEFAULT NULL,
  `produk_id` int DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `harga_satuan` decimal(12,2) DEFAULT NULL,
  `subtotal` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `penagihan_detail`
--

INSERT INTO `penagihan_detail` (`id`, `penagihan_id`, `jenis_barang`, `produk_id`, `qty`, `harga_satuan`, `subtotal`) VALUES
(6, 19, 'jaket', 11, 1, 125000.00, 125000.00),
(7, 20, 'jaket', 11, 1, 125000.00, 125000.00),
(8, 21, 'stiker', 13, 10, 2000.00, 20000.00),
(9, 22, 'jaket', 27, 1, 50000.00, 50000.00),
(10, 22, 'stiker', 13, 5, 2000.00, 10000.00),
(11, 23, 'jaket', 11, 100, 125000.00, 12500000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `stiker`
--

CREATE TABLE `stiker` (
  `id_sticker` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `bagian` varchar(255) NOT NULL,
  `harga` int NOT NULL,
  `stock` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `stiker`
--

INSERT INTO `stiker` (`id_sticker`, `nama`, `bagian`, `harga`, `stock`) VALUES
(13, 'RRQ Vol 1', 'Belakang', 2000, 100),
(14, 'RRQ Vol 1', 'Lengan Team RRQ', 2000, 0),
(15, 'RRQ Vol 1', 'Logo Rex Regum', 2000, 60),
(16, 'RRQ Vol 2', 'RRQ Vol 1 Projek 2', 2000, 0),
(17, 'RRQ Vol 2', 'Logo RRQ Garis', 2000, 4),
(18, 'RRQ Vol 2', 'Logo Fukubi Projek 2', 2000, 10);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('owner','marketing','stock') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(5, 'owner_test', 'owner@fukubi.com', 'owner123', 'owner', '2025-02-27 02:11:04', '2025-02-27 02:12:22'),
(6, 'marketing_test', 'marketing@fukubi.com', 'marketing123', 'marketing', '2025-02-27 02:11:04', '2025-02-27 02:12:54'),
(7, 'stock_test', 'stock@fukubi.com', 'stock123', 'stock', '2025-02-27 02:11:04', '2025-02-27 02:12:57');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang_jadi`
--
ALTER TABLE `barang_jadi`
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `Jaket` (`id_jaket`),
  ADD KEY `Stiker` (`id_sticker`);

--
-- Indeks untuk tabel `datadn`
--
ALTER TABLE `datadn`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jaket`
--
ALTER TABLE `jaket`
  ADD PRIMARY KEY (`id_jaket`);

--
-- Indeks untuk tabel `log_barang`
--
ALTER TABLE `log_barang`
  ADD PRIMARY KEY (`id_log`);

--
-- Indeks untuk tabel `pembayaran_history`
--
ALTER TABLE `pembayaran_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penagihan_id` (`penagihan_id`);

--
-- Indeks untuk tabel `penagihan`
--
ALTER TABLE `penagihan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `penagihan_detail`
--
ALTER TABLE `penagihan_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penagihan_id` (`penagihan_id`);

--
-- Indeks untuk tabel `stiker`
--
ALTER TABLE `stiker`
  ADD PRIMARY KEY (`id_sticker`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `barang_jadi`
--
ALTER TABLE `barang_jadi`
  MODIFY `id_barang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT untuk tabel `datadn`
--
ALTER TABLE `datadn`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=842331549;

--
-- AUTO_INCREMENT untuk tabel `jaket`
--
ALTER TABLE `jaket`
  MODIFY `id_jaket` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `log_barang`
--
ALTER TABLE `log_barang`
  MODIFY `id_log` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT untuk tabel `pembayaran_history`
--
ALTER TABLE `pembayaran_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `penagihan`
--
ALTER TABLE `penagihan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `penagihan_detail`
--
ALTER TABLE `penagihan_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `stiker`
--
ALTER TABLE `stiker`
  MODIFY `id_sticker` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `barang_jadi`
--
ALTER TABLE `barang_jadi`
  ADD CONSTRAINT `Jaket` FOREIGN KEY (`id_jaket`) REFERENCES `jaket` (`id_jaket`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `Stiker` FOREIGN KEY (`id_sticker`) REFERENCES `stiker` (`id_sticker`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `pembayaran_history`
--
ALTER TABLE `pembayaran_history`
  ADD CONSTRAINT `pembayaran_history_ibfk_1` FOREIGN KEY (`penagihan_id`) REFERENCES `penagihan` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penagihan_detail`
--
ALTER TABLE `penagihan_detail`
  ADD CONSTRAINT `penagihan_detail_ibfk_1` FOREIGN KEY (`penagihan_id`) REFERENCES `penagihan` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
