-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 13 Nov 2025 pada 11.52
-- Versi server: 9.3.0
-- Versi PHP: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `travel`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `armadas`
--

CREATE TABLE `armadas` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plate_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` int NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `armadas`
--

INSERT INTO `armadas` (`id`, `category_id`, `name`, `vehicle_type`, `plate_number`, `capacity`, `description`, `image`, `is_available`, `created_at`, `updated_at`) VALUES
(1, 1, 'Mercedes-Benz OH 1526', 'Bus', 'B 1234 ABC', 35, '<p>Bus eksekutif dengan AC, reclining seat, dan fasilitas premium</p>', 'armadas/01K9XGVMFCQ2RWH0G9SPYE3TAP.png', 1, '2025-11-12 04:21:13', '2025-11-12 19:31:34'),
(2, 1, 'Scania K410', 'Bus', 'B 2345 DEF', 40, '<p>Bus eksekutif mewah dengan toilet, TV, dan WiFi</p>', 'armadas/01K9XGV8SB6510R0EFR88Y1VY8.png', 1, '2025-11-12 04:21:13', '2025-11-12 19:31:22'),
(3, 1, 'Hino RK8', 'Bus', 'B 3456 GHI', 35, '<p>Bus eksekutif dengan seat 2-2, AC, dan charging port</p>', 'armadas/01K9XGTXA17ZW5H5XBX6YN1BP6.png', 1, '2025-11-12 04:21:13', '2025-11-12 19:31:10'),
(4, 2, 'Mitsubishi Colt Diesel', 'Minibus', 'B 4567 JKL', 20, '<p>Minibus ekonomi dengan AC dan seat nyaman</p>', 'armadas/01K9XGTFX4CTT62MMGX7MKW9P1.png', 1, '2025-11-12 04:21:13', '2025-11-12 19:30:57'),
(5, 2, 'Isuzu Elf', 'Minibus', 'B 5678 MNO', 18, '<p>Minibus ekonomi untuk perjalanan jarak menengah</p>', 'armadas/01K9XGSDBBN43PK95KF3RSFW6S.png', 1, '2025-11-12 04:21:13', '2025-11-12 19:30:21'),
(6, 2, 'Toyota HiAce', 'Minibus', 'B 6789 PQR', 15, '<p>Minibus ekonomi compact dengan AC</p>', 'armadas/01K9XGRZWM08ZQMGKK1K4AQGV0.png', 1, '2025-11-12 04:21:13', '2025-11-12 19:30:08'),
(7, 2, 'Hino Dutro', 'Minibus', 'B 7890 STU', 25, '<p>Minibus ekonomi kapasitas besar</p>', 'armadas/01K9XGR6S9PWZ2NPDFY63BJ4XK.png', 1, '2025-11-12 04:21:13', '2025-11-12 19:29:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `route_id` bigint UNSIGNED NOT NULL,
  `armada_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `travel_date` date NOT NULL,
  `travel_time` time DEFAULT NULL,
  `total_passengers` int NOT NULL,
  `price_per_person` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `pickup_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','confirmed','cancelled','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `whatsapp_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `bookings`
--

INSERT INTO `bookings` (`id`, `booking_code`, `customer_id`, `route_id`, `armada_id`, `category_id`, `travel_date`, `travel_time`, `total_passengers`, `price_per_person`, `total_price`, `pickup_location`, `notes`, `status`, `whatsapp_url`, `created_at`, `updated_at`) VALUES
(1, 'BK-MVHZVPOE', 13, 1, 5, 2, '2025-11-16', '10:51:00', 1, 225000.00, 225000.00, 'jakarta', NULL, 'confirmed', NULL, '2025-11-12 20:26:50', '2025-11-12 20:51:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Eksekutif', 'eksekutif', '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(2, 'Ekonomi', 'ekonomi', '2025-11-12 04:21:13', '2025-11-12 04:21:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `customers`
--

CREATE TABLE `customers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `id_card_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `address`, `id_card_number`, `created_at`, `updated_at`) VALUES
(1, 'Budi Santoso', 'budi.santoso@email.com', '081234567890', 'Jl. Sudirman No. 45, Jakarta Pusat', '3171011234567890', '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(2, 'Siti Nurhaliza', 'siti.nurhaliza@email.com', '082345678901', 'Jl. Gatot Subroto No. 12, Jakarta Selatan', '3174022345678901', '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(3, 'Ahmad Dhani', 'ahmad.dhani@email.com', '083456789012', 'Jl. Thamrin No. 89, Jakarta Pusat', '3171033456789012', '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(4, 'Dewi Lestari', 'dewi.lestari@email.com', '084567890123', 'Jl. Dago No. 56, Bandung', '3273044567890123', '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(5, 'Rudi Hartono', 'rudi.hartono@email.com', '085678901234', 'Jl. Malioboro No. 23, Yogyakarta', '3471055678901234', '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(6, 'Maya Sari', 'maya.sari@email.com', '086789012345', 'Jl. Pemuda No. 78, Semarang', '3374066789012345', '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(7, 'Andi Wijaya', 'andi.wijaya@email.com', '087890123456', 'Jl. Basuki Rahmat No. 34, Surabaya', '3578077890123456', '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(8, 'Rina Wulandari', 'rina.wulandari@email.com', '088901234567', 'Jl. Asia Afrika No. 90, Bandung', '3273088901234567', '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(9, 'Bambang Pamungkas', 'bambang.pamungkas@email.com', '089012345678', 'Jl. HR Rasuna Said No. 67, Jakarta Selatan', '3174099012345678', '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(10, 'Fitri Handayani', 'fitri.handayani@email.com', '081123456789', 'Jl. Diponegoro No. 45, Solo', '3372101123456789', '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(11, 'Hendra Gunawan', 'hendra.gunawan@email.com', '082234567890', 'Jl. Ijen No. 12, Malang', '3573112234567890', '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(12, 'Linda Wijayanti', 'linda.wijayanti@email.com', '083345678901', 'Jl. Pahlawan No. 56, Surabaya', '3578123345678901', '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(13, 'Muhamad febriansyah', 'muhammadfebrian121@gmail.com', '081295916567', NULL, NULL, '2025-11-12 20:24:51', '2025-11-12 20:24:51');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_08_26_100418_add_two_factor_columns_to_users_table', 1),
(5, '2025_11_12_000001_create_categories_table', 1),
(6, '2025_11_12_000002_create_armadas_table', 1),
(7, '2025_11_12_000003_create_routes_table', 1),
(8, '2025_11_12_000004_create_prices_table', 1),
(9, '2025_11_12_000005_create_customers_table', 1),
(10, '2025_11_12_000006_create_bookings_table', 1),
(11, '2025_11_12_105719_create_settings_table', 2),
(12, '2025_11_12_140148_add_landing_page_settings_to_settings_table', 3),
(13, '2025_11_12_140646_add_more_social_media_to_settings_table', 4),
(14, '2025_11_12_144607_add_hero_badge_to_settings_table', 5),
(15, '2025_11_12_153441_add_google_maps_embed_to_settings_table', 6);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `prices`
--

CREATE TABLE `prices` (
  `id` bigint UNSIGNED NOT NULL,
  `route_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `valid_from` date DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `prices`
--

INSERT INTO `prices` (`id`, `route_id`, `category_id`, `price`, `discount`, `valid_from`, `valid_until`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 225000.00, 0.00, '2025-11-12', '2026-11-12', 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(2, 1, 1, 405000.00, 0.00, '2025-11-12', '2026-11-12', 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(3, 2, 2, 675000.00, 0.00, '2025-11-12', '2026-11-12', 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(4, 2, 1, 1215000.00, 0.00, '2025-11-12', '2026-11-12', 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(5, 3, 2, 1170000.00, 0.00, '2025-11-12', '2026-11-12', 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(6, 3, 1, 2106000.00, 0.00, '2025-11-12', '2026-11-12', 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(7, 4, 2, 840000.00, 0.00, '2025-11-12', '2026-11-12', 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(8, 4, 1, 1512000.00, 0.00, '2025-11-12', '2026-11-12', 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(9, 5, 2, 1020000.00, 0.00, '2025-11-12', '2026-11-12', 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(10, 5, 1, 1836000.00, 0.00, '2025-11-12', '2026-11-12', 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(11, 6, 2, 630000.00, 10000.00, '2025-11-12', '2026-11-12', 1, '2025-11-12 04:21:13', '2025-11-12 20:17:43'),
(12, 6, 1, 1134000.00, 0.00, '2025-11-12', '2026-11-12', 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(13, 7, 2, 495000.00, 0.00, '2025-11-12', '2026-11-12', 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(14, 7, 1, 891000.00, 0.00, '2025-11-12', '2026-11-12', 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(15, 8, 2, 480000.00, 0.00, '2025-11-12', '2026-11-12', 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(16, 8, 1, 864000.00, 0.00, '2025-11-12', '2026-11-12', 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(17, 9, 2, 1230000.00, 0.00, '2025-11-12', '2026-11-12', 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(18, 9, 1, 2214000.00, 0.00, '2025-11-12', '2026-11-12', 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(19, 10, 2, 900000.00, 0.00, '2025-11-12', '2026-11-12', 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(20, 10, 1, 1620000.00, 0.00, '2025-11-12', '2026-11-12', 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `routes`
--

CREATE TABLE `routes` (
  `id` bigint UNSIGNED NOT NULL,
  `origin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `distance` decimal(8,2) DEFAULT NULL,
  `estimated_duration` int DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `routes`
--

INSERT INTO `routes` (`id`, `origin`, `destination`, `route_code`, `distance`, `estimated_duration`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Jakarta', 'Bandung', 'JKT-BDG', 150.00, 180, NULL, 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(2, 'Jakarta', 'Semarang', 'JKT-SMG', 450.00, 480, NULL, 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(3, 'Jakarta', 'Surabaya', 'JKT-SBY', 780.00, 720, NULL, 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(4, 'Jakarta', 'Yogyakarta', 'JKT-YOG', 560.00, 540, NULL, 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(5, 'Bandung', 'Surabaya', 'BDG-SBY', 680.00, 660, NULL, 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(6, 'Bandung', 'Yogyakarta', 'BDG-YOG', 420.00, 420, NULL, 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(7, 'Semarang', 'Surabaya', 'SMG-SBY', 330.00, 300, NULL, 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(8, 'Yogyakarta', 'Surabaya', 'YOG-SBY', 320.00, 300, NULL, 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(9, 'Jakarta', 'Malang', 'JKT-MLG', 820.00, 780, NULL, 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13'),
(10, 'Jakarta', 'Solo', 'JKT-SLO', 600.00, 570, NULL, 1, '2025-11-12 04:21:13', '2025-11-12 04:21:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('736MijHPZR53B66WVDZz12CUvahchgBnhSxurLH2', 1, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiY3Zsc25CMFMxOU45MnRkWUlpOGhmeWJ1Z2RSRTFxRUV5UElKYlJ5aCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJG5jbmo3YkxpbEt6U1UvRnpaMW9RNE9TcXpVc3RUWkd1UEVUTE15YUZPUnBvMlFFQmtYT3RpIjtzOjY6InRhYmxlcyI7YTo3OntzOjQwOiJhYmY1NjI0MmUwNWIzYTNmNTVhZmM3YjIwOTk1YTFkN19jb2x1bW5zIjthOjU6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoicm91dGVfY29kZSI7czo1OiJsYWJlbCI7czo5OiJLb2RlIFJ1dGUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjY6Im9yaWdpbiI7czo1OiJsYWJlbCI7czo0OiJBc2FsIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMToiZGVzdGluYXRpb24iO3M6NToibGFiZWwiO3M6NjoiVHVqdWFuIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo4OiJkaXN0YW5jZSI7czo1OiJsYWJlbCI7czo1OiJKYXJhayI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTQ6ImJvb2tpbmdzX2NvdW50IjtzOjU6ImxhYmVsIjtzOjEzOiJUb3RhbCBCb29raW5nIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fX1zOjQwOiIxY2JhOGM0ZTA5MTI4Y2IzM2ZiMTgxZmRjNDQ5MzcxZl9jb2x1bW5zIjthOjc6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMjoiYm9va2luZ19jb2RlIjtzOjU6ImxhYmVsIjtzOjEyOiJLb2RlIEJvb2tpbmciO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEzOiJjdXN0b21lci5uYW1lIjtzOjU6ImxhYmVsIjtzOjk6IlBlbGFuZ2dhbiI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjI7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTY6InJvdXRlLnJvdXRlX2NvZGUiO3M6NToibGFiZWwiO3M6NDoiUnV0ZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjM7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTE6InRyYXZlbF9kYXRlIjtzOjU6ImxhYmVsIjtzOjc6IlRhbmdnYWwiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo0O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjE2OiJ0b3RhbF9wYXNzZW5nZXJzIjtzOjU6ImxhYmVsIjtzOjk6IlBlbnVtcGFuZyI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjU7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTE6InRvdGFsX3ByaWNlIjtzOjU6ImxhYmVsIjtzOjU6IlRvdGFsIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo2OiJzdGF0dXMiO3M6NToibGFiZWwiO3M6NjoiU3RhdHVzIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fX1zOjQwOiIyMDU4ZTFhMjlmYTA5ZDA1NDIxNDBlNmE2ZjY4YzRiMF9jb2x1bW5zIjthOjk6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoyOiJObyI7czo1OiJsYWJlbCI7czoyOiJObyI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NToiaW1hZ2UiO3M6NToibGFiZWwiO3M6NDoiRm90byI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjI7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoibmFtZSI7czo1OiJsYWJlbCI7czoxMToiTmFtYSBBcm1hZGEiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEyOiJwbGF0ZV9udW1iZXIiO3M6NToibGFiZWwiO3M6MTA6Ik5vbW9yIFBsYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo0O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEzOiJjYXRlZ29yeS5uYW1lIjtzOjU6ImxhYmVsIjtzOjg6IkthdGVnb3JpIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo4OiJjYXBhY2l0eSI7czo1OiJsYWJlbCI7czo5OiJLYXBhc2l0YXMiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo2O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEyOiJpc19hdmFpbGFibGUiO3M6NToibGFiZWwiO3M6ODoiVGVyc2VkaWEiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo3O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjE0OiJib29raW5nc19jb3VudCI7czo1OiJsYWJlbCI7czoxMzoiVG90YWwgQm9va2luZyI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjA7fWk6ODthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoiY3JlYXRlZF9hdCI7czo1OiJsYWJlbCI7czo2OiJEaWJ1YXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO319czo0MDoiZWM4M2UyOWFhMDM3ZjZkZDc0YTY1Y2RjMzg2YzIxMGRfY29sdW1ucyI7YToxMDp7aTowO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEyOiJib29raW5nX2NvZGUiO3M6NToibGFiZWwiO3M6MTI6IktvZGUgQm9va2luZyI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTM6ImN1c3RvbWVyLm5hbWUiO3M6NToibGFiZWwiO3M6OToiUGVsYW5nZ2FuIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxNjoicm91dGUucm91dGVfY29kZSI7czo1OiJsYWJlbCI7czo0OiJSdXRlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMzoiY2F0ZWdvcnkubmFtZSI7czo1OiJsYWJlbCI7czo4OiJLYXRlZ29yaSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTE6ImFybWFkYS5uYW1lIjtzOjU6ImxhYmVsIjtzOjY6IkFybWFkYSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjA7fWk6NTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMToidHJhdmVsX2RhdGUiO3M6NToibGFiZWwiO3M6NzoiVGFuZ2dhbCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjY7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTY6InRvdGFsX3Bhc3NlbmdlcnMiO3M6NToibGFiZWwiO3M6OToiUGVudW1wYW5nIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMToidG90YWxfcHJpY2UiO3M6NToibGFiZWwiO3M6NToiVG90YWwiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo4O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjY6InN0YXR1cyI7czo1OiJsYWJlbCI7czo2OiJTdGF0dXMiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo5O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjY6IkRpYnVhdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fX1zOjQwOiJlYWMwOTNiOGNkZjQzMGRlZGQwZWFmYzdhNzAwYzVmYV9jb2x1bW5zIjthOjc6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoyOiJObyI7czo1OiJsYWJlbCI7czoyOiJObyI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoibmFtZSI7czo1OiJsYWJlbCI7czo0OiJOYW1hIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo1OiJwaG9uZSI7czo1OiJsYWJlbCI7czo3OiJUZWxlcG9uIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxNDoiaWRfY2FyZF9udW1iZXIiO3M6NToibGFiZWwiO3M6NzoiTm8uIEtUUCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjA7fWk6NDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo3OiJhZGRyZXNzIjtzOjU6ImxhYmVsIjtzOjY6IkFsYW1hdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjA7fWk6NTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxNDoiYm9va2luZ3NfY291bnQiO3M6NToibGFiZWwiO3M6MTM6IlRvdGFsIEJvb2tpbmciO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo2O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjk6IlRlcmRhZnRhciI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fX1zOjQwOiI5M2E0YzhjOTk4MDA5ODljYjRlNTRmZmExMGQ5ZjA0ZF9jb2x1bW5zIjthOjEyOntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MjoiTm8iO3M6NToibGFiZWwiO3M6MjoiTm8iO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjE2OiJyb3V0ZS5yb3V0ZV9jb2RlIjtzOjU6ImxhYmVsIjtzOjk6IktvZGUgUnV0ZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjI7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTI6InJvdXRlLm9yaWdpbiI7czo1OiJsYWJlbCI7czo0OiJBc2FsIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxNzoicm91dGUuZGVzdGluYXRpb24iO3M6NToibGFiZWwiO3M6NjoiVHVqdWFuIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMzoiY2F0ZWdvcnkubmFtZSI7czo1OiJsYWJlbCI7czo4OiJLYXRlZ29yaSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjU7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NToicHJpY2UiO3M6NToibGFiZWwiO3M6NToiSGFyZ2EiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo2O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjg6ImRpc2NvdW50IjtzOjU6ImxhYmVsIjtzOjY6IkRpc2tvbiI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjA7fWk6NzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMToiZmluYWxfcHJpY2UiO3M6NToibGFiZWwiO3M6MTE6IkhhcmdhIEZpbmFsIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6ODthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoidmFsaWRfZnJvbSI7czo1OiJsYWJlbCI7czoxMjoiQmVybGFrdSBEYXJpIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9aTo5O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjExOiJ2YWxpZF91bnRpbCI7czo1OiJsYWJlbCI7czoxNDoiQmVybGFrdSBTYW1wYWkiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO31pOjEwO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjk6ImlzX2FjdGl2ZSI7czo1OiJsYWJlbCI7czo2OiJTdGF0dXMiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxMTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoiY3JlYXRlZF9hdCI7czo1OiJsYWJlbCI7czo2OiJEaWJ1YXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO319czo0MDoiNTc5ZWUzMGIxZGE4NTNlZWZmMDM3MTYzZGUxNzAwODhfY29sdW1ucyI7YTo1OntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MjoiTm8iO3M6NToibGFiZWwiO3M6MjoiTm8iO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjQ6Im5hbWUiO3M6NToibGFiZWwiO3M6OToiIEthdGVnb3JpIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo0OiJzbHVnIjtzOjU6ImxhYmVsIjtzOjQ6IlNsdWciO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEzOiJhcm1hZGFzX2NvdW50IjtzOjU6ImxhYmVsIjtzOjEzOiJKdW1sYWggQXJtYWRhIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoiY3JlYXRlZF9hdCI7czo1OiJsYWJlbCI7czo2OiJEaWJ1YXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO319fXM6ODoiZmlsYW1lbnQiO2E6MDp7fX0=', 1763009446);

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `site_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_maps_embed` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `hero_badge` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_subtitle` text COLLATE utf8mb4_unicode_ci,
  `hero_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_stats` json DEFAULT NULL,
  `features` json DEFAULT NULL,
  `facebook_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `youtube_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tiktok_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `settings`
--

INSERT INTO `settings` (`id`, `site_name`, `keyword`, `email`, `address`, `google_maps_embed`, `phone`, `description`, `hero_badge`, `hero_title`, `hero_subtitle`, `hero_image`, `hero_stats`, `features`, `facebook_url`, `instagram_url`, `twitter_url`, `whatsapp_number`, `youtube_url`, `tiktok_url`, `linkedin_url`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'Travel Bisnis', 'travel, bus, sewa bus, rental bus, travel antar kota', 'info@travelbisnis.com', 'Jl. Raya No. 123, Jakarta', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.666426556994!2d106.82493197499016!3d-6.175392193803947!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5d2e764b12d%3A0x3d2ad6e1e0e9bcc8!2sMonumen%20Nasional!5e0!3m2!1sid!2sid!4v1699999999999!5m2!1sid!2sid', '081234567890', 'Layanan travel dan rental bus terpercaya dengan armada berkualitas dan pelayanan terbaik', '#1 Layanan Travel Terpercaya', 'Perjalanan Nyaman, Aman & Terpercaya', 'Layanan travel dan rental bus dengan armada modern, driver profesional, dan harga terjangkau. Nikmati perjalanan terbaik bersama kami untuk pengalaman yang tak terlupakan.', 'hero/01K9XGZX3HXE40X92XR67HYH6H.png', '[{\"label\": \"Penumpang\", \"number\": \"10000\", \"suffix\": \"+\"}, {\"label\": \"Armada\", \"number\": \"50\", \"suffix\": \"+\"}, {\"label\": \"Rute\", \"number\": \"15\", \"suffix\": \"+\"}]', '[{\"icon\": \"Shield\", \"title\": \"Keamanan Terjamin\", \"rating\": 4.9, \"description\": \"Armada dilengkapi dengan asuransi perjalanan dan sistem keamanan modern untuk kenyamanan Anda.\"}, {\"icon\": \"Clock\", \"title\": \"Tepat Waktu\", \"rating\": 4.8, \"description\": \"Kami berkomitmen untuk selalu on-time dengan jadwal keberangkatan yang teratur dan terpercaya.\"}, {\"icon\": \"DollarSign\", \"title\": \"Harga Terjangkau\", \"rating\": 4.7, \"description\": \"Dapatkan harga terbaik dengan berbagai pilihan paket yang sesuai dengan budget Anda.\"}, {\"icon\": \"Users\", \"title\": \"Driver Profesional\", \"rating\": 4.9, \"description\": \"Driver berpengalaman dan terlatih untuk memastikan perjalanan Anda aman dan nyaman.\"}, {\"icon\": \"Headphones\", \"title\": \"Layanan 24/7\", \"rating\": 4.8, \"description\": \"Customer service kami siap membantu Anda kapan saja untuk kebutuhan perjalanan Anda.\"}, {\"icon\": \"Star\", \"title\": \"Armada Terawat\", \"rating\": 4.9, \"description\": \"Kendaraan selalu dalam kondisi prima dengan perawatan rutin dan fasilitas lengkap.\"}]', 'https://facebook.com/travelbisnis', 'https://instagram.com/travelbisnis', 'https://twitter.com/travelbisnis', '6281234567890', 'https://youtube.com/@travelbisnis', 'https://tiktok.com/@travelbisnis', 'https://linkedin.com/company/travelbisnis', 'settings/01K9VWJSRQ8WVK41YT3EFDQYGN.png', '2025-11-12 04:00:25', '2025-11-12 19:33:54');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@admin.com', NULL, '$2y$12$ncnj7bLilKzSU/FzZ1oQ4OSqzUstTZGuPETLMyaFORpo2QEBkXOti', NULL, NULL, NULL, NULL, '2025-11-12 02:55:09', '2025-11-12 02:55:09'),
(2, 'Test User', 'test@example.com', '2025-11-12 04:20:57', '$2y$12$J6ROzvm3VFFbOnMzH91Mi.0oOhb8tOtjOyKa8xF8HAsWyY2bReW7O', NULL, NULL, NULL, NULL, '2025-11-12 04:20:57', '2025-11-12 04:20:57');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `armadas`
--
ALTER TABLE `armadas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `armadas_plate_number_unique` (`plate_number`),
  ADD KEY `armadas_category_id_foreign` (`category_id`);

--
-- Indeks untuk tabel `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookings_booking_code_unique` (`booking_code`),
  ADD KEY `bookings_customer_id_foreign` (`customer_id`),
  ADD KEY `bookings_route_id_foreign` (`route_id`),
  ADD KEY `bookings_armada_id_foreign` (`armada_id`),
  ADD KEY `bookings_category_id_foreign` (`category_id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indeks untuk tabel `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `prices`
--
ALTER TABLE `prices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prices_route_id_category_id_valid_from_unique` (`route_id`,`category_id`,`valid_from`),
  ADD KEY `prices_category_id_foreign` (`category_id`);

--
-- Indeks untuk tabel `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `routes_route_code_unique` (`route_code`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `armadas`
--
ALTER TABLE `armadas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `prices`
--
ALTER TABLE `prices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `routes`
--
ALTER TABLE `routes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `armadas`
--
ALTER TABLE `armadas`
  ADD CONSTRAINT `armadas_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_armada_id_foreign` FOREIGN KEY (`armada_id`) REFERENCES `armadas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_route_id_foreign` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `prices`
--
ALTER TABLE `prices`
  ADD CONSTRAINT `prices_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prices_route_id_foreign` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
