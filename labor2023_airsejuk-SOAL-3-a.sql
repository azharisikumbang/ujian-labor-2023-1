-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Jul 2023 pada 18.11
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

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`nobrg`, `namabrg`, `harga`) VALUES
(1, 'Air Galon', 6000),
(2, 'Air Botol 1500ml', 4000),
(3, 'Air Dalam Kemasan 240ml', 17000),
(4, 'Mie Goreng', 25000),
(5, 'Mie Sedap', 3000),
(6, 'Pepsodent', 6500);

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

--
-- Dumping data untuk tabel `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`nofakt`, `nobrg`, `harga`, `jumlah`, `subtotal`, `diskon`, `bayar`) VALUES
(1, 1, 6000, 5, 30000, 3000, 27000),
(1, 2, 4000, 50, 200000, 20000, 180000),
(2, 3, 17000, 10, 170000, 17000, 153000),
(3, 3, 17000, 84, 1428000, 142800, 1285200),
(4, 1, 6000, 3, 18000, 1800, 16200);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelg` int(11) NOT NULL,
  `nama_pelg` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelg`, `nama_pelg`) VALUES
(1, 'Alex'),
(2, 'Alex'),
(3, 'Joni'),
(4, 'Rahman'),
(5, 'Rahman'),
(6, 'Rahman'),
(7, 'Siraj'),
(8, 'Sapiente minima plac'),
(9, 'Joni');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$Iz8G4l0JQqRBwImvk.mAhuyVTjrE.NN5xsbqueihHyPGZbyg.4LNK');

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

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`nofakt`, `id_pelg`, `tglpenjualan`, `total_diskon`, `total_bayar`) VALUES
(1, 6, '2023-05-29', 23000, 207000),
(2, 7, '2023-05-29', 17000, 153000),
(3, 8, '2023-05-29', 142800, 1285200),
(4, 9, '2023-06-03', 1800, 16200);

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
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

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
  MODIFY `nobrg` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelg` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `nofakt` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
