-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 21 Bulan Mei 2026 pada 15.15
-- Versi server: 10.5.29-MariaDB-cll-lve-log
-- Versi PHP: 8.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jaslitco_sicebun`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `m_bangsa`
--

CREATE TABLE `m_bangsa` (
  `id_bangsa` int(11) NOT NULL,
  `nama_bangsa` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `m_bangsa`
--

INSERT INTO `m_bangsa` (`id_bangsa`, `nama_bangsa`) VALUES
(1, 'PO'),
(2, 'Bali'),
(3, 'Madura'),
(4, 'Limousin'),
(5, 'Simental'),
(6, 'Lainnya');

-- --------------------------------------------------------

--
-- Struktur dari tabel `m_direktori`
--

CREATE TABLE `m_direktori` (
  `id_direktori` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `informasi` text DEFAULT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `m_direktori`
--

INSERT INTO `m_direktori` (`id_direktori`, `nama`, `informasi`, `cover`, `created_date`) VALUES
(1, 'Kelahiran', '<ol>\n<li>AMBING TURUN</li>\n<li>AIR SUSU DIPERAH KELUAR</li>\n<li>BUKAAN TIGA JARI</li>\n<li>AIR KETUBAN</li>\n<li>VULVA BENGKAK</li>\n<li>SUHU VULVA PANAS</li>\n<li>GELISAH</li>\n<li>GALAK</li>\n<li>SERING KENCING</li>\n</ol>', '0f6acaa6a7a15889e604dab115ab2963.png', '2021-03-23 05:47:15'),
(2, 'Pen. Gangguan Reproduksi', '<ol>\n<li>25 – 40 HARI SETELAH MELAHIRKAN</li>\n<li>BIRAHI TAPI TIDAK JELAS</li>\n<li>LENDIR SEDIKIT</li>\n<li>NAIK-NAIK</li>\n</ol>', '2ea7c1d72fd04e34eb205c8f6491c6d7.png', '2021-03-23 05:50:15'),
(3, 'Birahi', '<ol>\n<li>25 – 40 HARI SETELAH MELAHIRKAN</li>\n<li>40 - 82 HARI SETELAH MELAHIRKAN</li>\n<li>3 A : ABANG, ABUH DAN ANGET</li>\n<li>NAIK-NAIK</li>\n<li>GELISAH</li>\n<li>KELUAR LENDIR</li>\n<li>VULVA BENGKAK</li>\n<li>SUHU VULVA HANGAT</li>\n<li>WAKTU TERBAIK IB 6 – 12 JAM</li>\n<li>KELUAR AIR MATA</li>\n<li>DIPEGANG VULVANYA DIAM</li>\n<li>DINAIKI DIAM</li>\n</ol>', '576bb841cc95c202c714dac26e8d9599.png', '2021-03-23 05:51:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `m_galeri`
--

CREATE TABLE `m_galeri` (
  `id_galeri` int(11) NOT NULL,
  `id_direktori` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `m_galeri`
--

INSERT INTO `m_galeri` (`id_galeri`, `id_direktori`, `type`, `file`, `created_date`) VALUES
(32, 1, 'image/png', '28d8261120a6490d35b3f07af4d9880b.png', '2021-03-30 06:22:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `m_konsultan`
--

CREATE TABLE `m_konsultan` (
  `id_konsultan` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `telp` varchar(255) DEFAULT NULL,
  `asal` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `profesi` varchar(255) DEFAULT NULL,
  `keahlian` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `link` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `m_konsultan`
--

INSERT INTO `m_konsultan` (`id_konsultan`, `nama`, `telp`, `asal`, `alamat`, `profesi`, `keahlian`, `foto`, `link`, `is_active`) VALUES
(1, 'Konsultan A', '081111111111', 'Malang', 'Jl. soekarno hatta', 'Dokter Hewan', '- Analisa penyakit', '92972a524e99762124a105d271fde927.png', '-', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `m_sapi`
--

CREATE TABLE `m_sapi` (
  `id_sapi` int(11) NOT NULL,
  `id_inseminator` int(11) DEFAULT NULL,
  `id_bangsa` int(11) DEFAULT NULL,
  `no_sapi` varchar(255) DEFAULT NULL,
  `peternak` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `provinsi` enum('aceh','bali','banten','bengkulu','di_yogjakarta','dki_jakarta','gorontalo','jambi','jawa_barat','jawa_tengah','jawa_timur','kalimantan_barat','kalimantan_tengah','kalimantan_timur','kalimantan_utara','kepulauan_bangka_belitung','kepulauan_riau','lampung','maluku','maluku_utara','nusa_tenggara_barat','nusa_tenggara_timur','papua','papua_barat','provinsi_kalimantan_selatan','provinsi_sulawesi_selatan','riau','sulawesi_barat','sulawesi_tengah','sulawesi_tenggara','sulawesi_utara','sumatera_barat','sumatera_selatan','sumatera_utara') DEFAULT NULL,
  `kota` enum('aceh','bali','banten','bengkulu','di_yogjakarta','dki_jakarta','gorontalo','jambi','jawa_barat','jawa_tengah','jawa_timur','kalimantan_barat','kalimantan_tengah','kalimantan_timur','kalimantan_utara','kepulauan_bangka_belitung','kepulauan_riau','lampung','maluku','maluku_utara','nusa_tenggara_barat','nusa_tenggara_timur','papua','papua_barat','provinsi_kalimantan_selatan','provinsi_sulawesi_selatan','riau','sulawesi_barat','sulawesi_tengah','sulawesi_tenggara','sulawesi_utara','sumatera_barat','sumatera_selatan','sumatera_utara') DEFAULT NULL,
  `kecamatan` enum('aceh','bali','banten','bengkulu','di_yogjakarta','dki_jakarta','gorontalo','jambi','jawa_barat','jawa_tengah','jawa_timur','kalimantan_barat','kalimantan_tengah','kalimantan_timur','kalimantan_utara','kepulauan_bangka_belitung','kepulauan_riau','lampung','maluku','maluku_utara','nusa_tenggara_barat','nusa_tenggara_timur','papua','papua_barat','provinsi_kalimantan_selatan','provinsi_sulawesi_selatan','riau','sulawesi_barat','sulawesi_tengah','sulawesi_tenggara','sulawesi_utara','sumatera_barat','sumatera_selatan','sumatera_utara') DEFAULT NULL,
  `kelurahan` enum('aceh','bali','banten','bengkulu','di_yogjakarta','dki_jakarta','gorontalo','jambi','jawa_barat','jawa_tengah','jawa_timur','kalimantan_barat','kalimantan_tengah','kalimantan_timur','kalimantan_utara','kepulauan_bangka_belitung','kepulauan_riau','lampung','maluku','maluku_utara','nusa_tenggara_barat','nusa_tenggara_timur','papua','papua_barat','provinsi_kalimantan_selatan','provinsi_sulawesi_selatan','riau','sulawesi_barat','sulawesi_tengah','sulawesi_tenggara','sulawesi_utara','sumatera_barat','sumatera_selatan','sumatera_utara') DEFAULT NULL,
  `tgl_lahir` datetime DEFAULT NULL,
  `tgl_melahirkan` datetime DEFAULT NULL,
  `tgl_bunting` date DEFAULT NULL,
  `birahi_1` datetime DEFAULT NULL,
  `birahi_2` datetime DEFAULT NULL,
  `birahi_3` datetime DEFAULT NULL,
  `ib_1` datetime DEFAULT NULL,
  `ib_2` datetime DEFAULT NULL,
  `ib_3` datetime DEFAULT NULL,
  `straw_1` varchar(255) DEFAULT NULL,
  `straw_2` varchar(255) DEFAULT NULL,
  `straw_3` varchar(255) DEFAULT NULL,
  `ket_1` text DEFAULT NULL,
  `ket_2` text DEFAULT NULL,
  `ket_3` text DEFAULT NULL,
  `foto_pemilik` varchar(255) DEFAULT NULL,
  `foto_sapi` varchar(255) DEFAULT NULL,
  `is_bunting` tinyint(1) DEFAULT 0,
  `is_melahirkan` tinyint(1) DEFAULT 0,
  `bunting` text DEFAULT NULL,
  `tidak_bunting` text DEFAULT NULL,
  `kelahiran` text DEFAULT NULL,
  `gangrep` text DEFAULT NULL,
  `status` enum('ib1','ib2','ib3','bunting','tidak_bunting','kelahiran','gangrep') DEFAULT NULL,
  `created_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `m_sapi`
--

INSERT INTO `m_sapi` (`id_sapi`, `id_inseminator`, `id_bangsa`, `no_sapi`, `peternak`, `alamat`, `provinsi`, `kota`, `kecamatan`, `kelurahan`, `tgl_lahir`, `tgl_melahirkan`, `tgl_bunting`, `birahi_1`, `birahi_2`, `birahi_3`, `ib_1`, `ib_2`, `ib_3`, `straw_1`, `straw_2`, `straw_3`, `ket_1`, `ket_2`, `ket_3`, `foto_pemilik`, `foto_sapi`, `is_bunting`, `is_melahirkan`, `bunting`, `tidak_bunting`, `kelahiran`, `gangrep`, `status`, `created_date`) VALUES
(1, 1, 1, 'NO SAPI 1', 'Peternak A', 'Pasuruan', NULL, NULL, NULL, NULL, '2020-12-01 12:45:00', NULL, NULL, '2021-04-01 03:00:00', '2021-04-06 12:27:00', NULL, '2021-04-30 06:00:00', '2021-04-06 12:15:00', NULL, 'ppp', 'wqe', NULL, NULL, NULL, NULL, 'ba381ca51d3258cb0089fee4a0435ee2.png', '4f69f098fc13e71971b668e2baf95f96.png', 0, 0, NULL, NULL, NULL, '', 'ib2', '2021-03-28 09:08:42'),
(3, 4, NULL, 'NO SAPI 3', 'Peternak A', 'Pasuruan', NULL, NULL, NULL, NULL, '2020-12-01 00:00:00', NULL, NULL, NULL, NULL, NULL, '2021-03-23 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ba381ca51d3258cb0089fee4a0435ee2.png', '4f69f098fc13e71971b668e2baf95f96.png', 0, 0, NULL, NULL, NULL, NULL, 'ib2', NULL),
(4, 4, NULL, 'NO SAPI 4', 'Peternak A', 'Pasuruan', NULL, NULL, NULL, NULL, '2020-12-01 00:00:00', NULL, NULL, NULL, NULL, NULL, '2021-03-23 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ba381ca51d3258cb0089fee4a0435ee2.png', '4f69f098fc13e71971b668e2baf95f96.png', 0, 0, NULL, NULL, NULL, NULL, 'bunting', NULL),
(5, 1, NULL, 'NO SAPI 3', 'Peternak B', 'Malang', NULL, NULL, NULL, NULL, '2020-12-01 00:00:00', '2024-08-06 20:19:00', NULL, NULL, NULL, NULL, '2021-01-01 00:00:00', NULL, '2021-04-15 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'ba381ca51d3258cb0089fee4a0435ee2.png', '4f69f098fc13e71971b668e2baf95f96.png', 1, 1, NULL, NULL, 'ok', NULL, 'kelahiran', '2021-03-23 07:58:19'),
(6, 1, 2, 'NO SAPI 4', 'Peternak C', 'Sby', 'kepulauan_bangka_belitung', NULL, NULL, NULL, '2021-03-01 00:00:00', '2021-03-31 00:00:00', NULL, NULL, NULL, NULL, '2021-03-03 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ba381ca51d3258cb0089fee4a0435ee2.png', '4f69f098fc13e71971b668e2baf95f96.png', 0, 1, NULL, NULL, NULL, '', 'kelahiran', '2021-04-20 02:03:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `m_users`
--

CREATE TABLE `m_users` (
  `id_user` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `peran` enum('inseminator','peneliti','admin') DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `m_users`
--

INSERT INTO `m_users` (`id_user`, `email`, `password`, `name`, `address`, `phone`, `photo`, `peran`, `is_active`, `created_date`) VALUES
(1, 'admin@mail.com', '21232f297a57a5a743894a0e4a801fc3', 'Inseminator', 'Malang', '089696624512', 'ff3d7bb4bb673f4364bf4123e6c9cd37.png', 'admin', 1, '2021-03-18 21:49:46'),
(4, 'admin@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'shob', 'malang', '081234567890', 'default.png', 'inseminator', 1, '2021-03-23 05:21:20'),
(5, 'aaa@aaa', '47bce5c74f589f4867dbd57e9ca9f808', 'aaa', 'aaa', 'aaa', 'default.png', 'peneliti', 1, '2024-08-06 20:29:28'),
(16, 'achmad.muzakin@gmail.com', '8a1f0b042c9a0be9bbfed8335811361c', 'achmad.muzakin@gmail.com', 'achmad.muzakin@gmail.com', 'achmad.muzakin@gmail.com', 'd516af48928467da6082df5b1037ff1c.png', 'inseminator', 1, '2023-05-17 17:13:10'),
(17, 'theforcex71@gmail.com', 'f2f6cfc27def4de081d6ec8b8603a6fc', 'Ahmad Fatih Izzuddin Sultoni', 'Perum Jatiajajar Blok A5 No.6', '081292219760', '0fec6694625c325968a85e1a91f78240.png', 'inseminator', 1, '2023-10-19 18:13:11'),
(18, 'woroabidah@gmail.com', 'e6fd4dcc9a22f2bcfbff58f34a6d14e5', 'woro abidah', 'kepanjen', '081233068094', '502a251081eaf35bd381a07bca88336d.png', 'inseminator', 1, '2024-02-07 09:28:19'),
(19, 'musriadi35@yahoo.com', 'e10adc3949ba59abbe56e057f20f883e', 'Musriadi', 'Baru', '082259128090', '93cb5722286ca7c15b55d5fab17b6474.png', 'inseminator', 1, '2024-03-06 08:31:31'),
(20, 'musriadi0306@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Musriadi', 'Baru', '082259128090', '529678950e17586d058569b9aed6a64a.png', 'inseminator', 1, '2024-03-06 09:02:24'),
(21, 'musriadi0386@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Musriadi', 'Baru', '082259128090', 'c80634a49585a84655acb93a619e6f94.png', 'inseminator', 1, '2024-03-06 14:59:59'),
(22, 'dayurizkicandra@gmail.com', 'b3907171c6f2fe3919fbd2236560793c', 'Candra Dayu Rizki', 'Desa Raksa Budi, Kab. Musi Rawas', '082246309909', '5dae3c4234a4bb998c852a5ab1b965c7.png', 'inseminator', 1, '2024-05-29 22:58:11'),
(23, 'ahmadnurdiyanto45@gmail.com', '21232f297a57a5a743894a0e4a801fc3', 'ahmadnurdiyanto45@gmail.com', 'ahmadnurdiyanto45@gmail.com', 'ahmadnurdiyanto45@gmail.com', '8d93a90d19024d1e6e60c646a04ac8d0.png', 'inseminator', 1, '2024-06-25 23:43:11'),
(24, 'nugrohosusanto20@guru.smk.belajar.id', 'a947d77f6c9d8c622c89c6825986643d', 'NUGROHO ADI SUSANTO', 'pacitran rt 02 rw 03 baledu kandangan ', '085729357594', '58e37b481cccca7c40dbc6966e5f227f.png', 'inseminator', 1, '2024-09-23 10:26:07'),
(25, 'permanaputra237@gmail.com', 'f6a2d80947772375fbc5d6a0dbdff82e', 'Permana putra', 'Anjani', '085955153250', '0e15bf2895b6a435746a999e58af3ca1.png', 'inseminator', 1, '2025-03-19 19:30:34'),
(26, 'syahrulromadhon8@gmail.com', 'cbcdb4d9b5292dd28e83bb962cb40516', 'syahrul bashor romadhon', 'jl.pahlawan no. 02', '087866956464', '33ffae66325fc740bbfb2f8c09bd5a86.png', 'inseminator', 1, '2025-07-09 16:02:11'),
(27, 'fendiaseko@gmail.com', 'fdea123de55d1534948591aa313966ca', 'Fendias', 'Banyumas', '085640077069', 'f862c80c2c52224080d696a276a5b794.png', 'inseminator', 1, '2025-09-02 12:35:56'),
(28, 'marjiatulmaghfiroh.009@gmail.com', '256b5480cefe497ae07cd8ddc4613369', 'MARJIATUL MAGFIROH', 'TREWUNG - GRATI - PASURUAN', '085806404130', '1ff3988aa55759f0734b61b9580188cd.png', 'inseminator', 1, '2025-09-18 13:44:50'),
(29, 'nurayurmdn@gmail.com', '25f9e794323b453885f5181f1b624d0b', 'SAPI BALI', 'BRMP RB', '085857252045', '0aab42d8583f39bcb6da8e54ae496250.png', 'inseminator', 1, '2025-09-18 13:46:18'),
(30, 'hikmah110208@gmail.com', '6fd80a29ede4ee36f9b41dd47f7e22a9', 'Pogasi', 'brmp rb', '0895702585578', '887f44cb95a0e4de2c96efa59551b68c.png', 'inseminator', 1, '2025-09-18 13:46:22'),
(31, 'maghfirohmarjiatul0@gmail.com', '256b5480cefe497ae07cd8ddc4613369', 'MARJIATUL MAGFIROH', 'TREWUNG - GRATI - PASURUAN', '085806404130', '05c458945082710fbe6cab9e0f18e549.png', 'inseminator', 1, '2025-09-18 13:49:49'),
(32, 'fjarbk09@gmail.com', '6fac3ab603bb3fb46e4277786393194c', 'fjarbk', 'f', '088', 'd94d2af9247acce4269c4bec0ab7383b.png', 'inseminator', 1, '2025-09-24 11:18:52');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `m_bangsa`
--
ALTER TABLE `m_bangsa`
  ADD PRIMARY KEY (`id_bangsa`);

--
-- Indeks untuk tabel `m_direktori`
--
ALTER TABLE `m_direktori`
  ADD PRIMARY KEY (`id_direktori`);

--
-- Indeks untuk tabel `m_galeri`
--
ALTER TABLE `m_galeri`
  ADD PRIMARY KEY (`id_galeri`);

--
-- Indeks untuk tabel `m_konsultan`
--
ALTER TABLE `m_konsultan`
  ADD PRIMARY KEY (`id_konsultan`);

--
-- Indeks untuk tabel `m_sapi`
--
ALTER TABLE `m_sapi`
  ADD PRIMARY KEY (`id_sapi`);

--
-- Indeks untuk tabel `m_users`
--
ALTER TABLE `m_users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `m_bangsa`
--
ALTER TABLE `m_bangsa`
  MODIFY `id_bangsa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `m_direktori`
--
ALTER TABLE `m_direktori`
  MODIFY `id_direktori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `m_galeri`
--
ALTER TABLE `m_galeri`
  MODIFY `id_galeri` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `m_konsultan`
--
ALTER TABLE `m_konsultan`
  MODIFY `id_konsultan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `m_sapi`
--
ALTER TABLE `m_sapi`
  MODIFY `id_sapi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `m_users`
--
ALTER TABLE `m_users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
