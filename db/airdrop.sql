-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Jan 2025 pada 21.06
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `airdrop`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `airdrops_list`
--

CREATE TABLE `airdrops_list` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `link` varchar(512) NOT NULL,
  `token` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('active','pending','ended') NOT NULL DEFAULT 'active',
  `account_google` varchar(255) DEFAULT NULL,
  `account_discord` varchar(255) DEFAULT NULL,
  `account_twitter` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `airdrops_list`
--

INSERT INTO `airdrops_list` (`id`, `name`, `link`, `token`, `start_date`, `end_date`, `status`, `account_google`, `account_discord`, `account_twitter`, `created_at`, `updated_at`) VALUES
(1, 'Tryanda Anggita Suwito', 'http://localhost/airdrops/modules/dashboard.com', 'JUP', '2025-01-11', '2025-01-10', 'active', 'tryandaasu@gmail.com', 'git', 'git', '2025-01-18 13:02:36', '2025-01-18 13:05:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `airdrop_participation`
--

CREATE TABLE `airdrop_participation` (
  `participation_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `airdrop_id` int(11) DEFAULT NULL,
  `status` enum('pending','completed','claimed') DEFAULT 'pending',
  `date_joined` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `airdrop_tasks`
--

CREATE TABLE `airdrop_tasks` (
  `task_id` int(11) NOT NULL,
  `airdrop_id` int(11) DEFAULT NULL,
  `task_name` varchar(255) NOT NULL,
  `task_description` text DEFAULT NULL,
  `reward_amount` decimal(18,8) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_joined` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `airdrops_list`
--
ALTER TABLE `airdrops_list`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `airdrop_participation`
--
ALTER TABLE `airdrop_participation`
  ADD PRIMARY KEY (`participation_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `airdrop_id` (`airdrop_id`);

--
-- Indeks untuk tabel `airdrop_tasks`
--
ALTER TABLE `airdrop_tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `airdrop_id` (`airdrop_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `airdrops_list`
--
ALTER TABLE `airdrops_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `airdrop_participation`
--
ALTER TABLE `airdrop_participation`
  MODIFY `participation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `airdrop_tasks`
--
ALTER TABLE `airdrop_tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `airdrop_participation`
--
ALTER TABLE `airdrop_participation`
  ADD CONSTRAINT `airdrop_participation_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `airdrop_participation_ibfk_2` FOREIGN KEY (`airdrop_id`) REFERENCES `airdrops` (`airdrop_id`);

--
-- Ketidakleluasaan untuk tabel `airdrop_tasks`
--
ALTER TABLE `airdrop_tasks`
  ADD CONSTRAINT `airdrop_tasks_ibfk_1` FOREIGN KEY (`airdrop_id`) REFERENCES `airdrops` (`airdrop_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
