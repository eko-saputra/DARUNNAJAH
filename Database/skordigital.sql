-- phpMyAdmin SQL Dump
-- version 5.2.2deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 22, 2026 at 04:21 PM
-- Server version: 11.8.3-MariaDB-1build1 from Ubuntu
-- PHP Version: 8.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skordigital`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `userID` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`userID`, `username`, `password`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_tanding`
--

CREATE TABLE `jadwal_tanding` (
  `id_partai` int(11) NOT NULL,
  `tgl` date NOT NULL,
  `kelas` varchar(55) NOT NULL,
  `gelanggang` varchar(2) NOT NULL,
  `partai` varchar(4) NOT NULL,
  `nm_biru` varchar(55) NOT NULL,
  `kontingen_biru` varchar(55) NOT NULL,
  `nm_merah` varchar(55) NOT NULL,
  `kontingen_merah` varchar(55) NOT NULL,
  `status` varchar(55) NOT NULL DEFAULT '-',
  `skor_biru` int(11) DEFAULT NULL,
  `skor_merah` int(11) DEFAULT NULL,
  `pemenang` varchar(150) NOT NULL DEFAULT '-',
  `babak` varchar(55) DEFAULT NULL,
  `id_bagan` int(11) DEFAULT NULL,
  `bagan` varchar(255) DEFAULT NULL,
  `medali` varchar(2) NOT NULL DEFAULT '0',
  `aktif` varchar(2) NOT NULL DEFAULT '0',
  `grup` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_tgr`
--

CREATE TABLE `jadwal_tgr` (
  `id_partai` int(11) NOT NULL,
  `tgl` date NOT NULL,
  `partai` varchar(4) NOT NULL,
  `kategori` varchar(25) NOT NULL,
  `golongan` varchar(255) NOT NULL,
  `nm_biru` varchar(55) NOT NULL,
  `kontingen_biru` varchar(55) NOT NULL,
  `nm_merah` varchar(55) NOT NULL,
  `kontingen_merah` varchar(55) NOT NULL,
  `status` varchar(55) NOT NULL DEFAULT '-',
  `pemenang` varchar(150) NOT NULL DEFAULT '-',
  `babak` varchar(55) DEFAULT NULL,
  `medali` varchar(2) NOT NULL DEFAULT '0',
  `aktif` varchar(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kelastanding`
--

CREATE TABLE `kelastanding` (
  `ID_kelastanding` int(11) NOT NULL,
  `nm_kelastanding` varchar(21) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `kelastanding`
--

INSERT INTO `kelastanding` (`ID_kelastanding`, `nm_kelastanding`) VALUES
(1, 'UNDER'),
(2, 'KELAS A'),
(3, 'KELAS B'),
(4, 'KELAS C'),
(5, 'KELAS D'),
(6, 'KELAS E'),
(7, 'KELAS F'),
(8, 'KELAS G'),
(9, 'KELAS H'),
(10, 'KELAS I'),
(11, 'KELAS J'),
(12, 'KELAS K'),
(13, 'KELAS L'),
(14, 'KELAS M'),
(15, 'KELAS N'),
(16, 'KELAS O'),
(17, 'KELAS P'),
(18, 'KELAS Q'),
(19, 'KELAS R');

-- --------------------------------------------------------

--
-- Table structure for table `konfirmasi`
--

CREATE TABLE `konfirmasi` (
  `ID_konfirmasi` int(11) NOT NULL,
  `bank_tujuan` varchar(55) NOT NULL,
  `bank_pengirim` varchar(55) NOT NULL,
  `norek_pengirim` varchar(35) NOT NULL,
  `nm_pengirim` varchar(35) NOT NULL,
  `kontak` varchar(35) NOT NULL,
  `tgl_transfer` varchar(15) NOT NULL,
  `jumlah` varchar(35) NOT NULL,
  `bukti` varchar(128) NOT NULL,
  `catatan` text NOT NULL,
  `datetime` varchar(35) NOT NULL,
  `status` varchar(15) NOT NULL DEFAULT 'OPEN'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medali`
--

CREATE TABLE `medali` (
  `id_medali` int(11) NOT NULL,
  `nama` varchar(55) DEFAULT NULL,
  `kontingen` varchar(55) DEFAULT NULL,
  `kelas` varchar(255) DEFAULT NULL,
  `medali` varchar(25) DEFAULT NULL,
  `id_partai_FK` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nilai_atlet`
--

CREATE TABLE `nilai_atlet` (
  `id_nilaiatlet` int(11) NOT NULL,
  `no_partai` varchar(5) NOT NULL,
  `nama` varchar(75) NOT NULL,
  `kontingen` varchar(100) NOT NULL,
  `hukuman` varchar(5) NOT NULL DEFAULT '0',
  `nilai` varchar(5) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nilai_dewan`
--

CREATE TABLE `nilai_dewan` (
  `id_nilai` int(11) NOT NULL,
  `id_jadwal` varchar(5) NOT NULL,
  `id_juri` int(11) NOT NULL,
  `button` varchar(55) NOT NULL,
  `nilai` int(11) DEFAULT NULL,
  `sudut` varchar(55) NOT NULL,
  `babak` int(11) NOT NULL,
  `bbk` varchar(60) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nilai_dewan_seni_tunggal`
--

CREATE TABLE `nilai_dewan_seni_tunggal` (
  `id_nilai` int(11) NOT NULL,
  `id_jadwal` int(11) NOT NULL,
  `sudut` varchar(256) NOT NULL,
  `hukum_1` float(10,2) NOT NULL,
  `hukum_2` float(10,2) NOT NULL,
  `hukum_3` float(10,2) NOT NULL,
  `hukum_4` float(10,2) NOT NULL,
  `hukum_5` float(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nilai_seni_tunggal`
--

CREATE TABLE `nilai_seni_tunggal` (
  `id_nilai` int(11) NOT NULL,
  `id_jadwal` int(11) NOT NULL,
  `id_juri` int(11) NOT NULL,
  `sudut` varchar(255) NOT NULL,
  `wrong` decimal(4,2) NOT NULL,
  `stamina` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nilai_tanding`
--

CREATE TABLE `nilai_tanding` (
  `id_nilai` int(11) NOT NULL,
  `id_jadwal` int(11) NOT NULL,
  `button` int(11) NOT NULL,
  `nilai` int(11) NOT NULL,
  `sudut` varchar(55) NOT NULL,
  `babak` varchar(5) NOT NULL,
  `bbk` varchar(60) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nilai_tanding_log`
--

CREATE TABLE `nilai_tanding_log` (
  `id_nilai` int(11) NOT NULL,
  `id_jadwal` int(11) NOT NULL,
  `id_juri` int(11) NOT NULL,
  `button` int(11) NOT NULL,
  `nilai` int(11) NOT NULL,
  `sudut` varchar(55) NOT NULL,
  `babak` varchar(5) NOT NULL,
  `bbk` varchar(60) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_sah` enum('pending','sah') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peserta`
--

CREATE TABLE `peserta` (
  `ID_peserta` int(11) NOT NULL,
  `nm_lengkap` varchar(35) NOT NULL,
  `jenis_kelamin` varchar(15) NOT NULL,
  `tpt_lahir` varchar(55) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `tb` int(11) DEFAULT NULL,
  `bb` int(11) DEFAULT NULL,
  `kelas` varchar(21) DEFAULT NULL,
  `asal_sekolah` varchar(55) NOT NULL,
  `kategori_tanding` varchar(10) NOT NULL,
  `golongan` varchar(15) NOT NULL,
  `kode_gr` varchar(32) NOT NULL,
  `kelas_tanding_FK` varchar(25) NOT NULL,
  `kontingen` varchar(100) NOT NULL,
  `foto` varchar(128) DEFAULT NULL,
  `ktp` varchar(128) DEFAULT NULL,
  `akta_lahir` varchar(128) DEFAULT NULL,
  `ijazah` varchar(128) DEFAULT NULL,
  `status` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wasit_juri`
--

CREATE TABLE `wasit_juri` (
  `id_juri` int(11) NOT NULL,
  `nm_juri` varchar(55) NOT NULL,
  `pass_juri` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `wasit_juri`
--

INSERT INTO `wasit_juri` (`id_juri`, `nm_juri`, `pass_juri`) VALUES
(1, 'JURI 1', '2f40bde8529e99bf8648a0a5718d0650'),
(2, 'JURI 2', '2f40bde8529e99bf8648a0a5718d0650'),
(3, 'JURI 3', '2f40bde8529e99bf8648a0a5718d0650'),
(4, 'JURI 4', '2f40bde8529e99bf8648a0a5718d0650'),
(11, 'DEWAN', 'b6dc0042c499c4dbc051fb74485fd8bb'),
(12, 'OPERATOR', '4b583376b2767b923c3e1da60d10de59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `jadwal_tanding`
--
ALTER TABLE `jadwal_tanding`
  ADD PRIMARY KEY (`id_partai`),
  ADD UNIQUE KEY `uniq_partai` (`kelas`,`tgl`,`id_bagan`,`bagan`,`babak`,`nm_biru`,`nm_merah`);

--
-- Indexes for table `jadwal_tgr`
--
ALTER TABLE `jadwal_tgr`
  ADD PRIMARY KEY (`id_partai`);

--
-- Indexes for table `kelastanding`
--
ALTER TABLE `kelastanding`
  ADD PRIMARY KEY (`ID_kelastanding`);

--
-- Indexes for table `konfirmasi`
--
ALTER TABLE `konfirmasi`
  ADD PRIMARY KEY (`ID_konfirmasi`);

--
-- Indexes for table `medali`
--
ALTER TABLE `medali`
  ADD PRIMARY KEY (`id_medali`);

--
-- Indexes for table `nilai_atlet`
--
ALTER TABLE `nilai_atlet`
  ADD PRIMARY KEY (`id_nilaiatlet`);

--
-- Indexes for table `nilai_dewan`
--
ALTER TABLE `nilai_dewan`
  ADD PRIMARY KEY (`id_nilai`);

--
-- Indexes for table `nilai_dewan_seni_tunggal`
--
ALTER TABLE `nilai_dewan_seni_tunggal`
  ADD PRIMARY KEY (`id_nilai`);

--
-- Indexes for table `nilai_seni_tunggal`
--
ALTER TABLE `nilai_seni_tunggal`
  ADD PRIMARY KEY (`id_nilai`);

--
-- Indexes for table `nilai_tanding`
--
ALTER TABLE `nilai_tanding`
  ADD PRIMARY KEY (`id_nilai`);

--
-- Indexes for table `nilai_tanding_log`
--
ALTER TABLE `nilai_tanding_log`
  ADD PRIMARY KEY (`id_nilai`);

--
-- Indexes for table `peserta`
--
ALTER TABLE `peserta`
  ADD PRIMARY KEY (`ID_peserta`);

--
-- Indexes for table `wasit_juri`
--
ALTER TABLE `wasit_juri`
  ADD PRIMARY KEY (`id_juri`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jadwal_tanding`
--
ALTER TABLE `jadwal_tanding`
  MODIFY `id_partai` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal_tgr`
--
ALTER TABLE `jadwal_tgr`
  MODIFY `id_partai` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelastanding`
--
ALTER TABLE `kelastanding`
  MODIFY `ID_kelastanding` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `konfirmasi`
--
ALTER TABLE `konfirmasi`
  MODIFY `ID_konfirmasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medali`
--
ALTER TABLE `medali`
  MODIFY `id_medali` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nilai_atlet`
--
ALTER TABLE `nilai_atlet`
  MODIFY `id_nilaiatlet` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nilai_dewan`
--
ALTER TABLE `nilai_dewan`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nilai_dewan_seni_tunggal`
--
ALTER TABLE `nilai_dewan_seni_tunggal`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nilai_seni_tunggal`
--
ALTER TABLE `nilai_seni_tunggal`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nilai_tanding`
--
ALTER TABLE `nilai_tanding`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nilai_tanding_log`
--
ALTER TABLE `nilai_tanding_log`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `peserta`
--
ALTER TABLE `peserta`
  MODIFY `ID_peserta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wasit_juri`
--
ALTER TABLE `wasit_juri`
  MODIFY `id_juri` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
