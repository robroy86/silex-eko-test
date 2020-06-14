-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Czas generowania: 12 Cze 2020, 13:47
-- Wersja serwera: 8.0.16
-- Wersja PHP: 7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `myDb`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `eko_przystanki`
--

CREATE TABLE `eko_przystanki` (
  `id` int(6) UNSIGNED NOT NULL,
  `nazwa` varchar(255) CHARACTER SET utf8 COLLATE utf8_polish_ci DEFAULT NULL,
  `adres` varchar(255) CHARACTER SET utf8 COLLATE utf8_polish_ci DEFAULT NULL,
  `opis` text CHARACTER SET utf8 COLLATE utf8_polish_ci,
  `zdj1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `zdj2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `zdj3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `reviewed` tinyint(1) NOT NULL DEFAULT '0',
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `browser` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `eko_przystanki`
--

INSERT INTO `eko_przystanki` (`id`, `nazwa`, `adres`, `opis`, `zdj1`, `zdj2`, `zdj3`, `reviewed`, `ip`, `browser`) VALUES
(1, 'Nazwa', 'Adres', 'asdasdas', '', '', '', 1, 'sadasd', 'dsadasd'),
(3, 'Nazwa1', 'Adres', 'asdasdas', '', '', '', 0, 'sadAAasd', '1dasd'),
(4, 'Nazwa2', 'Adres', 'asdasdas', '', '', '', 0, 'sadCCasd', 'dsa3asd'),
(5, 'Nazwa3', 'Adres', 'asdasdas', '', '', '', 0, 'sadFFasd', 'ds4dasd'),
(6, 'Nazwa4', 'Adres', 'asdasdas', '', '', '', 0, 'sadaYYsd', 'dsa5asd');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `eko_przystanki`
--
ALTER TABLE `eko_przystanki`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `index_przystanki_unique_nazwa` (`nazwa`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `eko_przystanki`
--
ALTER TABLE `eko_przystanki`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
