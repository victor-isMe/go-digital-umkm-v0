-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 20 Bulan Mei 2026 pada 04.56
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
  `id_kategori` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id_produk`, `nama`, `kategori`, `harga`, `stok`, `deskripsi`, `foto`, `id_kategori`) VALUES
(1, 'Rendang', 'makanan', 85000, 10, 'Deskripsi Produk', 'img/rendang.jpg', '1'),
(2, 'Cilok', 'makanan', 10000, 10, 'Deskripsi Produk', 'img/cilok.jpg', '1'),
(3, 'Somay', 'makanan', 15000, 10, 'Deskripsi Produk', 'img/somay.jpg', '1'),
(4, 'Mie Ayam', 'makanan', 12000, 10, 'Deskripsi Produk', 'img/mieayam.jpg', '1'),
(5, 'Bakso', 'makanan', 15000, 10, 'Deskripsi Produk', 'img/bakso.jpg', '1'),
(6, 'Dimsum', 'makanan', 20000, 10, 'Deskripsi Produk', 'img/dimsum.jpg', '1'),
(7, 'Pecel', 'makanan', 12000, 10, 'Deskripsi Produk', 'img/pecel.jpg', '1'),
(8, 'Gudeg', 'makanan', 18000, 10, 'Deskripsi Produk', 'img/gudeg.jpg', '1'),
(9, 'Serabi', 'makanan', 8000, 10, 'Deskripsi Produk', 'img/serabi.jpg', '1'),
(10, 'Sate', 'makanan', 20000, 10, 'Deskripsi Produk', 'img/sate.jpg', '1'),
(11, 'Ayam Bakar', 'makanan', 25000, 10, 'Deskripsi Produk', 'img/ayambakar.jpg', '1'),
(12, 'Es Jeruk', 'makanan', 8000, 10, 'Deskripsi Produk', 'img/esjeruk.jpg', '1'),
(13, 'Es Kopi', 'makanan', 12000, 10, 'Deskripsi Produk', 'img/eskopi.jpg', '1'),
(14, 'Kopi Hitam', 'makanan', 7000, 10, 'Deskripsi Produk', 'img/kopi_hitam.jpg', '1'),
(15, 'Es Buah', 'makanan', 15000, 10, 'Deskripsi Produk', 'img/esbuah.jpg', '1'),
(16, 'Es Teler', 'makanan', 17000, 10, 'Deskripsi Produk', 'img/esteler.jpg', '1'),
(17, 'Jus Buah', 'makanan', 10000, 10, 'Deskripsi Produk', 'img/jus.jpg', '1'),
(18, 'Dawet', 'makanan', 8000, 10, 'Deskripsi Produk', 'img/dawet.jpg', '1'),
(19, 'Jamu', 'makanan', 10000, 10, 'Deskripsi Produk', 'img/jamu.jpg', '1'),
(20, 'Es Teh', 'makanan', 5000, 10, 'Deskripsi Produk', 'img/esteh.jpg', '1'),
(21, 'Air Putih', 'makanan', 3000, 10, 'Deskripsi Produk', 'img/air.jpg', '1'),
(22, 'Tas Anyaman', 'kerajinan', 75000, 10, 'Deskripsi Produk', 'img/tasanyaman.jpg', '2'),
(23, 'Caping', 'kerajinan', 30000, 10, 'Deskripsi Produk', 'img/caping.jpg', '2'),
(24, 'Pot Bunga', 'kerajinan', 25000, 10, 'Deskripsi Produk', 'img/pot_.jpg', '2'),
(25, 'Keranjang', 'kerajinan', 40000, 10, 'Deskripsi Produk', 'img/keranjang.jpg', '2'),
(26, 'Bunga Hias', 'kerajinan', 20000, 10, 'Deskripsi Produk', 'img/bunga_hias.jpg', '2'),
(27, 'Aksesoris Handmade', 'kerajinan', 15000, 10, 'Deskripsi Produk', 'img/aksesoris.jpg', '2'),
(28, 'Tas Rotan', 'kerajinan', 90000, 10, 'Deskripsi Produk', 'img/tasrotan.jpg', '2'),
(29, 'Tas Kain Perca', 'kerajinan', 50000, 10, 'Deskripsi Produk', 'img/tasperca.jpg', '2'),
(30, 'Hiasan Dinding', 'kerajinan', 35000, 10, 'Deskripsi Produk', 'img/dinding.jpg', '2'),
(31, 'Baju', 'fashion', 100000, 10, 'Deskripsi Produk', 'img/baju.jpg', '3'),
(32, 'Celana', 'fashion', 90000, 10, 'Deskripsi Produk', 'img/celana.jpg', '3'),
(33, 'Baju Batik', 'fashion', 120000, 10, 'Deskripsi Produk', 'img/batik.jpg', '3'),
(34, 'Rok Batik', 'fashion', 110000, 10, 'Deskripsi Produk', 'img/rok.jpg', '3'),
(35, 'Gamis', 'fashion', 150000, 10, 'Deskripsi Produk', 'img/gamis.jpg', '3'),
(36, 'Baju Pria', 'fashion', 130000, 10, 'Deskripsi Produk', 'img/pria.jpg', '3'),
(37, 'One Set Anak', 'fashion', 95000, 10, 'Deskripsi Produk', 'img/oneset.jpg', '3'),
(38, 'Seragam Keluarga', 'fashion', 200000, 10, 'Deskripsi Produk', 'img/keluarga.jpg', '3'),
(39, 'Kerudung', 'fashion', 50000, 10, 'Deskripsi Produk', 'img/kerudung.jpg', '3'),
(40, 'Peci', 'fashion', 30000, 10, 'Deskripsi Produk', 'img/peci.jpg', '3'),
(41, 'Sarung', 'fashion', 60000, 10, 'Deskripsi Produk', 'img/sarung.jpg', '3'),
(42, 'Sandal Wanita', 'fashion', 70000, 10, 'Deskripsi Produk', 'img/sandalw.jpg', '3'),
(43, 'Sandal Pria', 'fashion', 75000, 10, 'Deskripsi Produk', 'img/sandalp.jpg', '3'),
(44, 'Sandal Anak', 'fashion', 50000, 10, 'Deskripsi Produk', 'img/sandala.jpg', '3'),
(45, 'Sepatu', 'fashion', 180000, 10, 'Deskripsi Produk', 'img/sepatu.jpg', '3');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `FK_Kategori` (`id_kategori`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `FK_Kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
