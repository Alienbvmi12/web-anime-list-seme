-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2024 at 02:34 PM
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
-- Database: `anime_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `anime`
--

CREATE TABLE `anime` (
  `id` int(10) UNSIGNED NOT NULL,
  `source_id` int(10) UNSIGNED NOT NULL,
  `original_data_id` int(10) UNSIGNED NOT NULL,
  `title` text NOT NULL,
  `title_english` text NOT NULL,
  `title_japanese` text NOT NULL,
  `image_url` text NOT NULL,
  `synopsis` text NOT NULL,
  `episodes` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `anime_genre_relation`
--

CREATE TABLE `anime_genre_relation` (
  `id` int(10) UNSIGNED NOT NULL,
  `anime_id` int(10) UNSIGNED NOT NULL,
  `genre_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `data_source`
--

CREATE TABLE `data_source` (
  `id` int(10) UNSIGNED NOT NULL,
  `source` varchar(255) NOT NULL,
  `api_base_url` varchar(255) NOT NULL,
  `api_parameters` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`api_parameters`)),
  `icon` text DEFAULT NULL COMMENT 'url or blob'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_source`
--

INSERT INTO `data_source` (`id`, `source`, `api_base_url`, `api_parameters`, `icon`) VALUES
(1, 'My Anime List', 'https://api.jikan.moe/v4/anime', '{}', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `genre`
--

CREATE TABLE `genre` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `source` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genre`
--

INSERT INTO `genre` (`id`, `name`, `source`) VALUES
(1, 'Action', 'https://myanimelist.net/anime/genre/1/Action'),
(2, 'Adventure', 'https://myanimelist.net/anime/genre/2/Adventure'),
(3, 'Racing', 'https://myanimelist.net/anime/genre/3/Racing'),
(4, 'Comedy', 'https://myanimelist.net/anime/genre/4/Comedy'),
(5, 'Avant Garde', 'https://myanimelist.net/anime/genre/5/Avant_Garde'),
(6, 'Mythology', 'https://myanimelist.net/anime/genre/6/Mythology'),
(7, 'Mystery', 'https://myanimelist.net/anime/genre/7/Mystery'),
(8, 'Drama', 'https://myanimelist.net/anime/genre/8/Drama'),
(9, 'Ecchi', 'https://myanimelist.net/anime/genre/9/Ecchi'),
(10, 'Fantasy', 'https://myanimelist.net/anime/genre/10/Fantasy'),
(11, 'Strategy Game', 'https://myanimelist.net/anime/genre/11/Strategy_Game'),
(12, 'Hentai', 'https://myanimelist.net/anime/genre/12/Hentai'),
(13, 'Historical', 'https://myanimelist.net/anime/genre/13/Historical'),
(14, 'Horror', 'https://myanimelist.net/anime/genre/14/Horror'),
(15, 'Kids', 'https://myanimelist.net/anime/genre/15/Kids'),
(17, 'Martial Arts', 'https://myanimelist.net/anime/genre/17/Martial_Arts'),
(18, 'Mecha', 'https://myanimelist.net/anime/genre/18/Mecha'),
(19, 'Music', 'https://myanimelist.net/anime/genre/19/Music'),
(20, 'Parody', 'https://myanimelist.net/anime/genre/20/Parody'),
(21, 'Samurai', 'https://myanimelist.net/anime/genre/21/Samurai'),
(22, 'Romance', 'https://myanimelist.net/anime/genre/22/Romance'),
(23, 'School', 'https://myanimelist.net/anime/genre/23/School'),
(24, 'Sci-Fi', 'https://myanimelist.net/anime/genre/24/Sci-Fi'),
(25, 'Shoujo', 'https://myanimelist.net/anime/genre/25/Shoujo'),
(26, 'Girls Love', 'https://myanimelist.net/anime/genre/26/Girls_Love'),
(27, 'Shounen', 'https://myanimelist.net/anime/genre/27/Shounen'),
(28, 'Boys Love', 'https://myanimelist.net/anime/genre/28/Boys_Love'),
(29, 'Space', 'https://myanimelist.net/anime/genre/29/Space'),
(30, 'Sports', 'https://myanimelist.net/anime/genre/30/Sports'),
(31, 'Super Power', 'https://myanimelist.net/anime/genre/31/Super_Power'),
(32, 'Vampire', 'https://myanimelist.net/anime/genre/32/Vampire'),
(35, 'Harem', 'https://myanimelist.net/anime/genre/35/Harem'),
(36, 'Slice of Life', 'https://myanimelist.net/anime/genre/36/Slice_of_Life'),
(37, 'Supernatural', 'https://myanimelist.net/anime/genre/37/Supernatural'),
(38, 'Military', 'https://myanimelist.net/anime/genre/38/Military'),
(39, 'Detective', 'https://myanimelist.net/anime/genre/39/Detective'),
(40, 'Psychological', 'https://myanimelist.net/anime/genre/40/Psychological'),
(41, 'Suspense', 'https://myanimelist.net/anime/genre/41/Suspense'),
(42, 'Seinen', 'https://myanimelist.net/anime/genre/42/Seinen'),
(43, 'Josei', 'https://myanimelist.net/anime/genre/43/Josei'),
(46, 'Award Winning', 'https://myanimelist.net/anime/genre/46/Award_Winning'),
(47, 'Gourmet', 'https://myanimelist.net/anime/genre/47/Gourmet'),
(48, 'Workplace', 'https://myanimelist.net/anime/genre/48/Workplace'),
(49, 'Erotica', 'https://myanimelist.net/anime/genre/49/Erotica'),
(50, 'Adult Cast', 'https://myanimelist.net/anime/genre/50/Adult_Cast'),
(51, 'Anthropomorphic', 'https://myanimelist.net/anime/genre/51/Anthropomorphic'),
(52, 'CGDCT', 'https://myanimelist.net/anime/genre/52/CGDCT'),
(53, 'Childcare', 'https://myanimelist.net/anime/genre/53/Childcare'),
(54, 'Combat Sports', 'https://myanimelist.net/anime/genre/54/Combat_Sports'),
(55, 'Delinquents', 'https://myanimelist.net/anime/genre/55/Delinquents'),
(56, 'Educational', 'https://myanimelist.net/anime/genre/56/Educational'),
(57, 'Gag Humor', 'https://myanimelist.net/anime/genre/57/Gag_Humor'),
(58, 'Gore', 'https://myanimelist.net/anime/genre/58/Gore'),
(59, 'High Stakes Game', 'https://myanimelist.net/anime/genre/59/High_Stakes_Game'),
(60, 'Idols (Female)', 'https://myanimelist.net/anime/genre/60/Idols_Female'),
(61, 'Idols (Male)', 'https://myanimelist.net/anime/genre/61/Idols_Male'),
(62, 'Isekai', 'https://myanimelist.net/anime/genre/62/Isekai'),
(63, 'Iyashikei', 'https://myanimelist.net/anime/genre/63/Iyashikei'),
(64, 'Love Polygon', 'https://myanimelist.net/anime/genre/64/Love_Polygon'),
(65, 'Magical Sex Shift', 'https://myanimelist.net/anime/genre/65/Magical_Sex_Shift'),
(66, 'Mahou Shoujo', 'https://myanimelist.net/anime/genre/66/Mahou_Shoujo'),
(67, 'Medical', 'https://myanimelist.net/anime/genre/67/Medical'),
(68, 'Organized Crime', 'https://myanimelist.net/anime/genre/68/Organized_Crime'),
(69, 'Otaku Culture', 'https://myanimelist.net/anime/genre/69/Otaku_Culture'),
(70, 'Performing Arts', 'https://myanimelist.net/anime/genre/70/Performing_Arts'),
(71, 'Pets', 'https://myanimelist.net/anime/genre/71/Pets'),
(72, 'Reincarnation', 'https://myanimelist.net/anime/genre/72/Reincarnation'),
(73, 'Reverse Harem', 'https://myanimelist.net/anime/genre/73/Reverse_Harem'),
(74, 'Romantic Subtext', 'https://myanimelist.net/anime/genre/74/Romantic_Subtext'),
(75, 'Showbiz', 'https://myanimelist.net/anime/genre/75/Showbiz'),
(76, 'Survival', 'https://myanimelist.net/anime/genre/76/Survival'),
(77, 'Team Sports', 'https://myanimelist.net/anime/genre/77/Team_Sports'),
(78, 'Time Travel', 'https://myanimelist.net/anime/genre/78/Time_Travel'),
(79, 'Video Game', 'https://myanimelist.net/anime/genre/79/Video_Game'),
(80, 'Visual Arts', 'https://myanimelist.net/anime/genre/80/Visual_Arts'),
(81, 'Crossdressing', 'https://myanimelist.net/anime/genre/81/Crossdressing');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `photo` text DEFAULT NULL,
  `reset_password_token` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `deactivated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `username`, `name`, `password`, `photo`, `reset_password_token`, `is_active`, `deactivated_at`, `created_at`, `updated_at`) VALUES
(1, 'email@example.com', 'user', 'Hatsune Miku', '$2y$10$tOcKSaHlKAGGqf0FYeve8.6sCiWoyAoOLb3gpPWW.PQpKgNsrfI0i', '66ead31d8607b045c74ae1b52635dc43.png', NULL, 1, NULL, '2024-09-05 06:37:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_anime_list`
--

CREATE TABLE `users_anime_list` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `anime_id` int(10) UNSIGNED NOT NULL,
  `status` enum('completed','watching','on-hold','dropped','plan to watch') NOT NULL,
  `episodes_watched` int(11) NOT NULL DEFAULT 0,
  `start_date` date DEFAULT NULL,
  `finish_date` date DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anime`
--
ALTER TABLE `anime`
  ADD PRIMARY KEY (`id`),
  ADD KEY `source_id` (`source_id`);

--
-- Indexes for table `anime_genre_relation`
--
ALTER TABLE `anime_genre_relation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_source`
--
ALTER TABLE `data_source`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `users_anime_list`
--
ALTER TABLE `users_anime_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `anime_id` (`anime_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anime`
--
ALTER TABLE `anime`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `anime_genre_relation`
--
ALTER TABLE `anime_genre_relation`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `data_source`
--
ALTER TABLE `data_source`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users_anime_list`
--
ALTER TABLE `users_anime_list`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `anime`
--
ALTER TABLE `anime`
  ADD CONSTRAINT `anime_ibfk_1` FOREIGN KEY (`source_id`) REFERENCES `data_source` (`id`);

--
-- Constraints for table `users_anime_list`
--
ALTER TABLE `users_anime_list`
  ADD CONSTRAINT `users_anime_list_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `users_anime_list_ibfk_2` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
