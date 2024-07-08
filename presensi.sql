-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 08, 2024 at 08:01 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `presensi`
--

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

CREATE TABLE `jabatan` (
  `id` int(11) NOT NULL,
  `jabatan` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`id`, `jabatan`) VALUES
(7, 'IT Support 1'),
(8, 'bendahara'),
(9, 'Project Owner');

-- --------------------------------------------------------

--
-- Table structure for table `ketidakhadiran`
--

CREATE TABLE `ketidakhadiran` (
  `id` int(11) NOT NULL,
  `id_pegawai` int(11) NOT NULL,
  `keterangan` varchar(225) NOT NULL,
  `tanggal` date NOT NULL,
  `deskripsi` varchar(225) NOT NULL,
  `file` varchar(225) NOT NULL,
  `status_pengajuan` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ketidakhadiran`
--

INSERT INTO `ketidakhadiran` (`id`, `id_pegawai`, `keterangan`, `tanggal`, `deskripsi`, `file`, `status_pengajuan`) VALUES
(4, 23, 'Izin', '2024-07-05', 'kucing saia yang sakit', '_8a16ad18-3d25-4b0a-8a10-1342382094ac.jpeg', 'DISETUJUI'),
(5, 23, 'Izin', '2024-07-05', 'kucing saia yang sakit', '_8a16ad18-3d25-4b0a-8a10-1342382094ac.jpeg', 'DISETUJUI');

-- --------------------------------------------------------

--
-- Table structure for table `lokasi_presensi`
--

CREATE TABLE `lokasi_presensi` (
  `id` int(11) NOT NULL,
  `nama_lokasi` varchar(255) NOT NULL,
  `alamat_lokasi` varchar(255) NOT NULL,
  `tipe_lokasi` varchar(255) NOT NULL,
  `latitude` varchar(50) NOT NULL,
  `longitude` varchar(50) NOT NULL,
  `radius` int(11) NOT NULL,
  `zona_waktu` varchar(4) NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_pulang` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lokasi_presensi`
--

INSERT INTO `lokasi_presensi` (`id`, `nama_lokasi`, `alamat_lokasi`, `tipe_lokasi`, `latitude`, `longitude`, `radius`, `zona_waktu`, `jam_masuk`, `jam_pulang`) VALUES
(1, 'Kantor Pusat', 'Jl. KKB pusat', 'Kantor Pusat', '121.23422424', '232.24214124', 100, 'WIB', '07:30:00', '17:30:00'),
(2, 'Kantor Pusat', 'jl. terus jadian kaga', 'Pusat', '-6.0983937', '123.03837389', 100, 'WIB', '08:00:00', '17:30:00'),
(3, 'rumah', 'jl. terus jadian kaga', 'Pusat', '0.9294297', '104.5208679', 100, 'WIB', '23:25:00', '23:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id` int(11) NOT NULL,
  `nip` varchar(50) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jenis_kelamin` varchar(50) NOT NULL,
  `alamat` varchar(225) NOT NULL,
  `no_handphone` varchar(20) NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  `lokasi_presensi` varchar(225) NOT NULL,
  `foto` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`id`, `nip`, `nama`, `jenis_kelamin`, `alamat`, `no_handphone`, `jabatan`, `lokasi_presensi`, `foto`) VALUES
(1, 'PEG-0001', 'elsa', 'perempuan', 'jalan. kemana ya', '0812121212', 'admin', 'kantor pusat', '_ac3f27e2-18a7-4397-9f75-1ca14998b448.jpeg'),
(2, 'PEG-0002', 'budi', 'laki-laki', 'jalan. in aja dulu', '080989999', 'kasat', 'kantor pusat', 'budi.jpg'),
(21, 'PEG-0003', 'Yopy Tri Buana', 'Laki-laki', 'jl.situ aja', '12313413', 'bendahara', 'Kantor Pusat', '_cf5a053d-d5ff-44d8-a245-f245493566ea.jpeg'),
(22, 'PEG-0004', 'asda', 'Laki-laki', 'qdqwd', '09028208', 'Project Owner', 'Kantor Pusat', '_cf5a053d-d5ff-44d8-a245-f245493566ea.jpeg'),
(23, 'PEG-0005', 'ani', 'Perempuan', 'jl.situ aja', '1235245', 'Project Owner', 'rumah', '_c0c09915-d6ba-432b-bcee-9288c75f3cac.jpeg'),
(24, 'PEG-0006', 'yopeh', 'Laki-laki', 'jl.situ aja', '09028208', 'Project Owner', 'rumah', '_c0c09915-d6ba-432b-bcee-9288c75f3cac.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `presensi`
--

CREATE TABLE `presensi` (
  `id` int(11) NOT NULL,
  `id_pegawai` int(11) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `jam_masuk` time NOT NULL,
  `foto_masuk` varchar(225) NOT NULL,
  `tanggal_keluar` date NOT NULL,
  `jam_keluar` time NOT NULL,
  `foto_keluar` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `presensi`
--

INSERT INTO `presensi` (`id`, `id_pegawai`, `tanggal_masuk`, `jam_masuk`, `foto_masuk`, `tanggal_keluar`, `jam_keluar`, `foto_keluar`) VALUES
(2, 23, '2024-06-13', '23:06:25', 'masuk_2024-06-13.jpeg', '2024-06-13', '23:59:25', 'masuk_2024-06-13.jpeg'),
(3, 23, '2024-06-13', '23:26:20', 'masuk_2024-06-13.jpeg', '2024-06-13', '23:59:25', 'masuk_2024-06-13.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `id_pegawai` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `id_pegawai`, `username`, `password`, `status`, `role`) VALUES
(1, 1, 'elsa', '$2y$10$ftITlbJbNCyen7Lsl19oW.di5tvzt/EBUg4bSAwC0k22GrOrriIh2', 'Aktif', 'admin'),
(2, 2, 'budi', '$2y$10$ftITlbJbNCyen7Lsl19oW.di5tvzt/EBUg4bSAwC0k22GrOrriIh2', 'Aktif', 'pegawai'),
(21, 21, 'qwe', '$2y$10$nNO4fGSARD3g7mx0QAZ8h.wmneDzza2zaK9fa6c/kLPEYdWsWeuSi', 'aktif', 'admin'),
(22, 22, 'admin', '$2y$10$AQLdQ9LoNMzPH9VuPIRP5OSbH.D.jfTNF8E9SKnnDBxq4wl4eu8ou', 'aktif', 'admin'),
(23, 23, 'ani', '$2y$10$bANoGnId4XnXPq2Xfub2xODp2Nk8P/YXEXDvAKcc28Wxv1daoM1hq', 'Aktif', 'pegawai'),
(24, 24, 'yopeh', '$2y$10$RMzk8e3BQa61MrirzuB9huxrFA/94DqE4gBUZRyF7fbzJHD7N26Ii', 'Aktif', 'pegawai');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ketidakhadiran`
--
ALTER TABLE `ketidakhadiran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- Indexes for table `lokasi_presensi`
--
ALTER TABLE `lokasi_presensi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `ketidakhadiran`
--
ALTER TABLE `ketidakhadiran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `lokasi_presensi`
--
ALTER TABLE `lokasi_presensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ketidakhadiran`
--
ALTER TABLE `ketidakhadiran`
  ADD CONSTRAINT `ketidakhadiran_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `presensi`
--
ALTER TABLE `presensi`
  ADD CONSTRAINT `presensi_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
