-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 07 Jun 2026 pada 05.09
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_marketplace`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(2) NOT NULL,
  `nama_admin` varchar(100) DEFAULT NULL,
  `email_admin` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `nama_admin`, `email_admin`, `password`) VALUES
(1, 'ADMIN', 'admin@admin.id', '$2y$10$NEKXrDEh7dLvBSpspruZGe9E704g1ZMq4EQ1YYnQELM0V/RlEbSFO');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id_detail` int(255) NOT NULL,
  `id_pesanan` int(255) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `jumlah_produk` int(11) DEFAULT NULL,
  `sub_total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` varchar(5) NOT NULL,
  `nama_kategori` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
('1', 'makanan'),
('2', 'kerajinan'),
('3', 'fashion');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembeli`
--

CREATE TABLE `pembeli` (
  `id_pembeli` int(255) NOT NULL,
  `nama_pembeli` varchar(100) DEFAULT NULL,
  `alamat_pembeli` varchar(100) DEFAULT NULL,
  `email_pembeli` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembeli`
--

INSERT INTO `pembeli` (`id_pembeli`, `nama_pembeli`, `alamat_pembeli`, `email_pembeli`, `password`, `phone`) VALUES
(1, 'Joe', 'Indonesia', 'pembeli@gmail.com', '$2y$10$/4SP2G3pj/fCoJxgOQYOXenV8xARhBvvE0I7MR8wQA.gtzqnHMwxO', '089');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjual`
--

CREATE TABLE `penjual` (
  `id_penjual` int(100) NOT NULL,
  `nama_penjual` varchar(100) DEFAULT NULL,
  `alamat_penjual` varchar(100) DEFAULT NULL,
  `email_penjual` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `nama_toko` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penjual`
--

INSERT INTO `penjual` (`id_penjual`, `nama_penjual`, `alamat_penjual`, `email_penjual`, `password`, `phone`, `nama_toko`) VALUES
(1, 'Taslim', 'Indonesia', 'penjual@gmail.com', '$2y$10$izPbQkwX.qILIb3cdD1q1utc8u8o7SMcY4sVhDPy787Z9TjLy8Gyq', '0899', 'JKL');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int(255) NOT NULL,
  `nama_pemesan` varchar(100) DEFAULT NULL,
  `alamat_pemesan` varchar(100) DEFAULT NULL,
  `metode_bayar` varchar(20) DEFAULT NULL,
  `id_pembeli` int(255) DEFAULT NULL,
  `total_harga` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `kategori` varchar(20) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `id_kategori` varchar(5) DEFAULT NULL,
  `id_penjual` int(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id_produk`, `nama`, `kategori`, `harga`, `stok`, `deskripsi`, `foto`, `id_kategori`, `id_penjual`) VALUES
(1, 'Rendang', 'makanan', 85000, 10, 'Deskripsi Produk', 'img/rendang.jpg', '1', NULL),
(2, 'Cilok', 'makanan', 10000, 10, 'Deskripsi Produk', 'img/cilok.jpg', '1', NULL),
(3, 'Somay', 'makanan', 15000, 10, 'Deskripsi Produk', 'img/somay.jpg', '1', NULL),
(4, 'Mie Ayam', 'makanan', 12000, 10, 'Deskripsi Produk', 'img/mieayam.jpg', '1', NULL),
(5, 'Bakso', 'makanan', 15000, 10, 'Deskripsi Produk', 'img/bakso.jpg', '1', NULL),
(6, 'Dimsum', 'makanan', 20000, 10, 'Deskripsi Produk', 'img/dimsum.jpg', '1', NULL),
(7, 'Pecel', 'makanan', 12000, 10, 'Deskripsi Produk', 'img/pecel.jpg', '1', NULL),
(8, 'Gudeg', 'makanan', 18000, 10, 'Deskripsi Produk', 'img/gudeg.jpg', '1', NULL),
(9, 'Serabi', 'makanan', 8000, 10, 'Deskripsi Produk', 'img/serabi.jpg', '1', NULL),
(10, 'Sate', 'makanan', 20000, 10, 'Deskripsi Produk', 'img/sate.jpg', '1', NULL),
(11, 'Ayam Bakar', 'makanan', 25000, 10, 'Deskripsi Produk', 'img/ayambakar.jpg', '1', NULL),
(12, 'Es Jeruk', 'makanan', 8000, 10, 'Deskripsi Produk', 'img/esjeruk.jpg', '1', NULL),
(13, 'Es Kopi', 'makanan', 12000, 10, 'Deskripsi Produk', 'img/eskopi.jpg', '1', NULL),
(14, 'Kopi Hitam', 'makanan', 7000, 10, 'Deskripsi Produk', 'img/kopi_hitam.jpg', '1', NULL),
(15, 'Es Buah', 'makanan', 15000, 10, 'Deskripsi Produk', 'img/esbuah.jpg', '1', NULL),
(16, 'Es Teler', 'makanan', 17000, 10, 'Deskripsi Produk', 'img/esteler.jpg', '1', NULL),
(17, 'Jus Buah', 'makanan', 10000, 10, 'Deskripsi Produk', 'img/jus.jpg', '1', NULL),
(18, 'Dawet', 'makanan', 8000, 10, 'Deskripsi Produk', 'img/dawet.jpg', '1', NULL),
(19, 'Jamu', 'makanan', 10000, 10, 'Deskripsi Produk', 'img/jamu.jpg', '1', NULL),
(20, 'Es Teh', 'makanan', 5000, 10, 'Deskripsi Produk', 'img/esteh.jpg', '1', NULL),
(21, 'Air Putih', 'makanan', 3000, 10, 'Deskripsi Produk', 'img/air.jpg', '1', NULL),
(22, 'Tas Anyaman', 'kerajinan', 75000, 10, 'Deskripsi Produk', 'img/tasanyaman.jpg', '2', NULL),
(23, 'Caping', 'kerajinan', 30000, 10, 'Deskripsi Produk', 'img/caping.jpg', '2', NULL),
(24, 'Pot Bunga', 'kerajinan', 25000, 10, 'Deskripsi Produk', 'img/pot_.jpg', '2', NULL),
(25, 'Keranjang', 'kerajinan', 40000, 10, 'Deskripsi Produk', 'img/keranjang.jpg', '2', NULL),
(26, 'Bunga Hias', 'kerajinan', 20000, 10, 'Deskripsi Produk', 'img/bunga_hias.jpg', '2', NULL),
(27, 'Aksesoris Handmade', 'kerajinan', 15000, 10, 'Deskripsi Produk', 'img/aksesoris.jpg', '2', NULL),
(28, 'Tas Rotan', 'kerajinan', 90000, 10, 'Deskripsi Produk', 'img/tasrotan.jpg', '2', NULL),
(29, 'Tas Kain Perca', 'kerajinan', 50000, 10, 'Deskripsi Produk', 'img/tasperca.jpg', '2', NULL),
(30, 'Hiasan Dinding', 'kerajinan', 35000, 10, 'Deskripsi Produk', 'img/dinding.jpg', '2', NULL),
(31, 'Baju', 'fashion', 100000, 10, 'Deskripsi Produk', 'img/baju.jpg', '3', NULL),
(32, 'Celana', 'fashion', 90000, 10, 'Deskripsi Produk', 'img/celana.jpg', '3', NULL),
(33, 'Baju Batik', 'fashion', 120000, 10, 'Deskripsi Produk', 'img/batik.jpg', '3', NULL),
(34, 'Rok Batik', 'fashion', 110000, 10, 'Deskripsi Produk', 'img/rok.jpg', '3', NULL),
(35, 'Gamis', 'fashion', 150000, 10, 'Deskripsi Produk', 'img/gamis.jpg', '3', NULL),
(36, 'Baju Pria', 'fashion', 130000, 10, 'Deskripsi Produk', 'img/pria.jpg', '3', NULL),
(37, 'One Set Anak', 'fashion', 95000, 10, 'Deskripsi Produk', 'img/oneset.jpg', '3', NULL),
(38, 'Seragam Keluarga', 'fashion', 200000, 10, 'Deskripsi Produk', 'img/keluarga.jpg', '3', NULL),
(39, 'Kerudung', 'fashion', 50000, 10, 'Deskripsi Produk', 'img/kerudung.jpg', '3', NULL),
(40, 'Peci', 'fashion', 30000, 10, 'Deskripsi Produk', 'img/peci.jpg', '3', NULL),
(41, 'Sarung', 'fashion', 60000, 10, 'Deskripsi Produk', 'img/sarung.jpg', '3', NULL),
(42, 'Sandal Wanita', 'fashion', 70000, 10, 'Deskripsi Produk', 'img/sandalw.jpg', '3', NULL),
(43, 'Sandal Pria', 'fashion', 75000, 10, 'Deskripsi Produk', 'img/sandalp.jpg', '3', NULL),
(44, 'Sandal Anak', 'fashion', 50000, 10, 'Deskripsi Produk', 'img/sandala.jpg', '3', NULL),
(45, 'Sepatu', 'fashion', 180000, 10, 'Deskripsi Produk', 'img/sepatu.jpg', '3', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indeks untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `fk_pesanan` (`id_pesanan`),
  ADD KEY `fk_produk` (`id_produk`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `pembeli`
--
ALTER TABLE `pembeli`
  ADD PRIMARY KEY (`id_pembeli`);

--
-- Indeks untuk tabel `penjual`
--
ALTER TABLE `penjual`
  ADD PRIMARY KEY (`id_penjual`);

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `fk_pembeli` (`id_pembeli`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `FK_Kategori` (`id_kategori`),
  ADD KEY `fk_penjual` (`id_penjual`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id_detail` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pembeli`
--
ALTER TABLE `pembeli`
  MODIFY `id_pembeli` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `penjual`
--
ALTER TABLE `penjual`
  MODIFY `id_penjual` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `fk_pesanan` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`),
  ADD CONSTRAINT `fk_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`);

--
-- Ketidakleluasaan untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `fk_pembeli` FOREIGN KEY (`id_pembeli`) REFERENCES `pembeli` (`id_pembeli`);

--
-- Ketidakleluasaan untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `FK_Kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`),
  ADD CONSTRAINT `fk_penjual` FOREIGN KEY (`id_penjual`) REFERENCES `penjual` (`id_penjual`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
