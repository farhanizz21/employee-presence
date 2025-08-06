-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 06, 2025 at 02:09 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `employee_absence`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensis`
--

CREATE TABLE `absensis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `pegawai_uuid` char(36) NOT NULL,
  `grup_uuid` char(36) DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `tgl_absen` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `absensis`
--

INSERT INTO `absensis` (`id`, `uuid`, `pegawai_uuid`, `grup_uuid`, `status`, `tgl_absen`, `created_at`, `updated_at`, `deleted_at`) VALUES
(61, 'ba4924fe-dbfc-4846-9479-15daa6de7c38', '1b4415ea-72a3-4548-b285-3a86e782d7e0', '011686d0-b6be-4f53-a701-222f5ff499f8', 1, '2025-07-28', '2025-08-02 07:23:50', '2025-08-02 07:23:50', NULL),
(62, 'e00b7ba3-ab05-46f4-82dd-159d477bc8e7', '2e40fcb9-a0e8-4522-9c21-fdd74ec11d06', 'f52191e9-ef52-4893-838c-6547cb1f308f', 1, '2025-07-28', '2025-08-02 07:23:57', '2025-08-02 07:23:57', NULL),
(63, 'dcfb508f-9b35-4534-9965-4870844949c0', 'd0984936-0f90-48fd-b0c9-ae1ffaa13e08', 'f52191e9-ef52-4893-838c-6547cb1f308f', 1, '2025-07-28', '2025-08-02 07:23:57', '2025-08-02 07:23:57', NULL),
(64, 'c5a1cca1-86ad-4582-af88-60d3320ccdf6', '8c7472b3-92b0-48f7-a967-9024a5bba9cb', 'f52191e9-ef52-4893-838c-6547cb1f308f', 4, '2025-07-28', '2025-08-02 07:24:00', '2025-08-02 07:24:00', NULL),
(65, 'd5b21c53-8139-4605-8c43-68b75afe3762', '1b4415ea-72a3-4548-b285-3a86e782d7e0', '011686d0-b6be-4f53-a701-222f5ff499f8', 4, '2025-07-29', '2025-08-02 07:24:12', '2025-08-02 07:24:12', NULL),
(66, '1660dd41-454b-444a-b77b-9d159dc7fdd4', '8c7472b3-92b0-48f7-a967-9024a5bba9cb', '011686d0-b6be-4f53-a701-222f5ff499f8', 1, '2025-07-29', '2025-08-02 07:24:13', '2025-08-02 07:24:13', NULL),
(67, '26bd2d2a-ae57-48d1-9673-62afb10ca8c8', '2e40fcb9-a0e8-4522-9c21-fdd74ec11d06', 'f52191e9-ef52-4893-838c-6547cb1f308f', 3, '2025-07-29', '2025-08-02 07:24:24', '2025-08-02 07:24:24', NULL),
(68, '45f89fb4-4e96-47d5-a1eb-a9c52331786d', 'd0984936-0f90-48fd-b0c9-ae1ffaa13e08', 'f52191e9-ef52-4893-838c-6547cb1f308f', 3, '2025-07-29', '2025-08-02 07:24:24', '2025-08-02 07:24:24', NULL),
(69, 'e0fac4de-5b52-4db3-a5d6-4eeb879ebad0', '1b4415ea-72a3-4548-b285-3a86e782d7e0', '011686d0-b6be-4f53-a701-222f5ff499f8', 1, '2025-07-30', '2025-08-02 07:24:39', '2025-08-02 07:24:39', NULL),
(70, 'b0aa973a-27f4-4253-a619-60974bb50330', '2e40fcb9-a0e8-4522-9c21-fdd74ec11d06', 'f52191e9-ef52-4893-838c-6547cb1f308f', 4, '2025-07-30', '2025-08-02 07:24:46', '2025-08-02 07:24:46', NULL),
(71, 'f465d52d-4a26-4ae7-86dc-dd6d853f59e3', '8c7472b3-92b0-48f7-a967-9024a5bba9cb', 'f52191e9-ef52-4893-838c-6547cb1f308f', 4, '2025-07-30', '2025-08-02 07:24:46', '2025-08-02 07:24:46', NULL),
(72, 'ce6c404d-1344-450a-86d2-a1f6cc437176', 'd0984936-0f90-48fd-b0c9-ae1ffaa13e08', 'f52191e9-ef52-4893-838c-6547cb1f308f', 4, '2025-07-30', '2025-08-02 07:24:46', '2025-08-02 07:24:46', NULL),
(73, '6dcce037-4dfd-4847-b59c-3435ac9eb547', '1b4415ea-72a3-4548-b285-3a86e782d7e0', '011686d0-b6be-4f53-a701-222f5ff499f8', 4, '2025-07-31', '2025-08-02 07:24:54', '2025-08-02 07:24:54', NULL),
(74, '5706a84d-fae3-4e33-bb39-dd4c77bc3839', '1b4415ea-72a3-4548-b285-3a86e782d7e0', '011686d0-b6be-4f53-a701-222f5ff499f8', 3, '2025-08-01', '2025-08-02 07:25:02', '2025-08-02 07:25:02', NULL),
(75, '9f0d4b9f-a69e-4c6e-8dbe-15b2aafb79dc', '1b4415ea-72a3-4548-b285-3a86e782d7e0', '011686d0-b6be-4f53-a701-222f5ff499f8', 1, '2025-08-02', '2025-08-02 07:25:12', '2025-08-02 07:25:12', NULL),
(76, '97162eb3-1446-47c0-b639-f264dab3938a', '2e40fcb9-a0e8-4522-9c21-fdd74ec11d06', 'f52191e9-ef52-4893-838c-6547cb1f308f', 1, '2025-08-02', '2025-08-02 07:25:16', '2025-08-02 07:25:16', NULL),
(77, 'ee313932-1d97-4c44-829a-e734387095d9', '8c7472b3-92b0-48f7-a967-9024a5bba9cb', 'f52191e9-ef52-4893-838c-6547cb1f308f', 1, '2025-08-02', '2025-08-02 07:25:16', '2025-08-02 07:25:16', NULL),
(78, 'c5471ad6-d758-4402-8503-ba16b372fab3', 'd0984936-0f90-48fd-b0c9-ae1ffaa13e08', 'f52191e9-ef52-4893-838c-6547cb1f308f', 1, '2025-08-02', '2025-08-02 07:25:16', '2025-08-02 07:25:16', NULL),
(79, '2bf34319-7a4d-42a9-8937-f13f711d1520', '1b4415ea-72a3-4548-b285-3a86e782d7e0', '011686d0-b6be-4f53-a701-222f5ff499f8', 2, '2025-08-03', '2025-08-02 21:30:15', '2025-08-02 21:30:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bonus_potongans`
--

CREATE TABLE `bonus_potongans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kode` varchar(255) DEFAULT NULL,
  `jenis` tinyint(3) UNSIGNED NOT NULL,
  `nominal` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `jabatan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`jabatan`)),
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bonus_potongans`
--

INSERT INTO `bonus_potongans` (`id`, `uuid`, `nama`, `kode`, `jenis`, `nominal`, `keterangan`, `status`, `jabatan`, `is_system`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '45a2c2f4-229f-433b-8392-b19ed93f4e78', 'Bonus Kehadiran', 'bonus_kehadiran', 1, 100000, 'Bonus untuk pegawai hadir penuh', 1, NULL, 1, '2025-08-02 07:09:23', '2025-08-02 07:09:23', NULL),
(2, '7e75c6eb-d729-4969-97ff-376107f660dc', 'Bonus Lembur', 'bonus_lembur', 1, 100000, 'Bonus lembur', 1, NULL, 1, '2025-08-02 07:09:23', '2025-08-02 07:09:23', NULL),
(3, 'c5b34d4c-22af-4d56-9e49-a92ba55d6b1f', 'Potongan Terlambat', 'potongan_terlambat', 2, 50000, '-', 1, NULL, 1, '2025-08-02 07:09:23', '2025-08-02 07:09:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gajians`
--

CREATE TABLE `gajians` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `pegawai_uuid` char(36) NOT NULL,
  `jabatan_uuid` char(36) NOT NULL,
  `gaji_pokok` int(11) NOT NULL,
  `bonus_kehadiran` int(11) NOT NULL,
  `bonus_lembur` int(11) NOT NULL,
  `total_potongan` int(11) NOT NULL,
  `total_gaji` int(11) NOT NULL,
  `jumlah_hadir` int(11) NOT NULL,
  `jumlah_lembur` int(11) NOT NULL,
  `jumlah_telat` int(11) NOT NULL,
  `jumlah_alpha` int(11) NOT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grups`
--

CREATE TABLE `grups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `grup` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `grups`
--

INSERT INTO `grups` (`id`, `uuid`, `grup`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '011686d0-b6be-4f53-a701-222f5ff499f8', 'Shift 1', '2025-07-20 03:34:05', '2025-07-20 03:34:05', NULL),
(2, 'f52191e9-ef52-4893-838c-6547cb1f308f', 'Shift 2', '2025-07-21 04:19:15', '2025-07-21 04:19:15', NULL),
(3, '05c8f804-eb00-4d13-a243-760d2a4c5d6d', 'Shift 3', '2025-07-23 14:17:54', '2025-07-23 14:17:54', NULL),
(4, 'a31a0dc3-03d2-422f-9c9a-ade5e86c116f', 'Shift 4', '2025-07-23 14:18:02', '2025-07-23 14:18:02', NULL),
(5, 'c0d1fc2a-5f60-498c-a698-10b39ac9f45a', 'Shift 5', '2025-07-23 14:18:32', '2025-07-23 14:18:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jabatans`
--

CREATE TABLE `jabatans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `gaji` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jabatans`
--

INSERT INTO `jabatans` (`id`, `uuid`, `jabatan`, `gaji`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '6c6b8c22-47a1-471c-8edd-81aa71f05514', 'SPV', 500000, '2025-07-20 01:17:16', '2025-07-20 01:17:16', NULL),
(2, 'd632e1cf-5ff3-4831-afa9-29a91758d376', 'Staff', 100000, '2025-07-23 12:21:33', '2025-07-23 12:21:33', NULL),
(3, 'affd0ace-e0d1-41bd-ac60-1005c021e343', 'Manager', 800000, '2025-08-02 05:44:01', '2025-08-02 05:44:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(59, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(60, '2019_08_19_000000_create_failed_jobs_table', 1),
(61, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(62, '2025_07_11_122702_create_pegawais_table', 1),
(63, '2025_07_11_124111_create_users_table', 1),
(64, '2025_07_17_170858_create_jabatans_table', 1),
(66, '2025_07_20_082318_create_grups_table', 2),
(68, '2025_07_26_083752_create_absensis_table', 3),
(74, '2025_08_02_122812_add_gaji_to_jabatans_table', 5),
(79, '2025_07_29_161803_create_bonus_potongans_table', 6),
(80, '2025_08_02_124108_add_gaji_to_jabatans_table', 7),
(83, '2025_07_31_133051_create_gajians_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pegawais`
--

CREATE TABLE `pegawais` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `telepon` varchar(255) NOT NULL,
  `grup_uuid` char(36) NOT NULL,
  `jabatan_uuid` char(36) NOT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pegawais`
--

INSERT INTO `pegawais` (`id`, `uuid`, `nama`, `telepon`, `grup_uuid`, `jabatan_uuid`, `alamat`, `keterangan`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, '1b4415ea-72a3-4548-b285-3a86e782d7e0', 'farhan', '3234', '011686d0-b6be-4f53-a701-222f5ff499f8', '6c6b8c22-47a1-471c-8edd-81aa71f05514', 'fsgsf', 'rgg', '2025-07-21 04:37:41', '2025-07-21 04:37:41', NULL),
(3, '2e40fcb9-a0e8-4522-9c21-fdd74ec11d06', 'Bayu', '081', 'f52191e9-ef52-4893-838c-6547cb1f308f', '6c6b8c22-47a1-471c-8edd-81aa71f05514', 'Jl', '-', '2025-07-21 04:49:43', '2025-07-23 12:21:18', NULL),
(4, '8c7472b3-92b0-48f7-a967-9024a5bba9cb', 'Niam', '08213', 'f52191e9-ef52-4893-838c-6547cb1f308f', 'd632e1cf-5ff3-4831-afa9-29a91758d376', 'Sukolilo', 'Anak 1', '2025-07-23 12:22:45', '2025-07-23 12:22:45', NULL),
(5, 'd0984936-0f90-48fd-b0c9-ae1ffaa13e08', 'Bayi', '0', 'f52191e9-ef52-4893-838c-6547cb1f308f', 'd632e1cf-5ff3-4831-afa9-29a91758d376', NULL, NULL, '2025-07-23 14:39:10', '2025-07-23 14:39:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `pegawai_uuid` char(36) DEFAULT NULL,
  `role` tinyint(3) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uuid`, `username`, `email`, `password`, `pegawai_uuid`, `role`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '2be43870-a590-4599-82fd-d3febda9b6b8', 'admin', 'admin@example.com', '$2y$12$DN7BR4m8ojkTfTkiCUbIo.CGkF6EHBn0ODaGPppeQ/Ko07W6BD.XG', NULL, 1, '2025-07-30 07:44:10', '2025-08-02 07:09:23', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensis`
--
ALTER TABLE `absensis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `absensis_uuid_unique` (`uuid`);

--
-- Indexes for table `bonus_potongans`
--
ALTER TABLE `bonus_potongans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bonus_potongans_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `bonus_potongans_kode_unique` (`kode`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `gajians`
--
ALTER TABLE `gajians`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gajians_uuid_unique` (`uuid`);

--
-- Indexes for table `grups`
--
ALTER TABLE `grups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `grups_uuid_unique` (`uuid`);

--
-- Indexes for table `jabatans`
--
ALTER TABLE `jabatans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jabatans_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pegawais`
--
ALTER TABLE `pegawais`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pegawais_uuid_unique` (`uuid`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_uuid_unique` (`uuid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensis`
--
ALTER TABLE `absensis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `bonus_potongans`
--
ALTER TABLE `bonus_potongans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gajians`
--
ALTER TABLE `gajians`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grups`
--
ALTER TABLE `grups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jabatans`
--
ALTER TABLE `jabatans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `pegawais`
--
ALTER TABLE `pegawais`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
