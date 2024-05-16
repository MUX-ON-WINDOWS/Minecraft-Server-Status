-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 16 mei 2024 om 12:29
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbs12796139`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mc_server`
--

CREATE TABLE `mc_server` (
  `server_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `server_name` varchar(250) NOT NULL,
  `server_ip` text DEFAULT NULL,
  `server_port` text DEFAULT NULL,
  `url` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `minecraftloginserver`
--

CREATE TABLE `minecraftloginserver` (
  `server_id_user` int(250) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `mc_server`
--
ALTER TABLE `mc_server`
  ADD PRIMARY KEY (`server_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexen voor tabel `minecraftloginserver`
--
ALTER TABLE `minecraftloginserver`
  ADD PRIMARY KEY (`server_id_user`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `mc_server`
--
ALTER TABLE `mc_server`
  MODIFY `server_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `minecraftloginserver`
--
ALTER TABLE `minecraftloginserver`
  MODIFY `server_id_user` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `mc_server`
--
ALTER TABLE `mc_server`
  ADD CONSTRAINT `mc_server_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `minecraftloginserver` (`server_id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
