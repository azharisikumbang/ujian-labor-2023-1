-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Bulan Mei 2023 pada 16.47
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `labor2023_airsejuk`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `nobrg` int(11) NOT NULL,
  `namabrg` varchar(255) NOT NULL,
  `harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `nofakt` int(10) NOT NULL,
  `nobrg` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `jumlah` int(15) NOT NULL,
  `subtotal` int(15) NOT NULL,
  `diskon` int(11) NOT NULL,
  `bayar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelg` int(11) NOT NULL,
  `nama_pelg` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `nofakt` int(10) NOT NULL,
  `id_pelg` int(11) NOT NULL,
  `tglpenjualan` date NOT NULL,
  `total_diskon` int(11) NOT NULL,
  `total_bayar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_laporan`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_laporan` (
`NO. FAKT` int(10)
,`TGL PENJUALAN` date
,`ID PELG` int(11)
,`PELANGGAN` varchar(255)
,`NO BRG` int(11)
,`NAMA BARANG` varchar(255)
,`HARGA Rp` int(11)
,`JUMLAH` int(15)
,`SUB TOTAL` int(15)
,`DISKON Rp` int(11)
,`BAYAR Rp` int(11)
);

-- --------------------------------------------------------

--
-- Struktur untuk view `v_laporan`
--
DROP TABLE IF EXISTS `v_laporan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_laporan`  AS SELECT `t`.`nofakt` AS `NO. FAKT`, `t`.`tglpenjualan` AS `TGL PENJUALAN`, `t`.`id_pelg` AS `ID PELG`, `p`.`nama_pelg` AS `PELANGGAN`, `b`.`nobrg` AS `NO BRG`, `b`.`namabrg` AS `NAMA BARANG`, `dt`.`harga` AS `HARGA Rp`, `dt`.`jumlah` AS `JUMLAH`, `dt`.`subtotal` AS `SUB TOTAL`, `dt`.`diskon` AS `DISKON Rp`, `dt`.`bayar` AS `BAYAR Rp` FROM (((`transaksi` `t` join `pelanggan` `p` on(`p`.`id_pelg` = `t`.`id_pelg`)) join `detail_transaksi` `dt` on(`dt`.`nofakt` = `t`.`nofakt`)) join `barang` `b` on(`b`.`nobrg` = `dt`.`nobrg`)) ORDER BY `t`.`nofakt` ASC ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`nobrg`);

--
-- Indeks untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD KEY `nofakt` (`nofakt`,`nobrg`),
  ADD KEY `nobrg` (`nobrg`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelg`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`nofakt`),
  ADD KEY `id_pelg` (`id_pelg`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `nobrg` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelg` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `nofakt` int(10) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`nobrg`) REFERENCES `barang` (`nobrg`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_transaksi_ibfk_3` FOREIGN KEY (`nofakt`) REFERENCES `transaksi` (`nofakt`);

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_pelg`) REFERENCES `pelanggan` (`id_pelg`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
