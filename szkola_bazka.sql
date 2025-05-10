-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Maj 10, 2025 at 10:23 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `szkola`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `przedmioty`
--

CREATE TABLE `przedmioty` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `przedmioty`
--

INSERT INTO `przedmioty` (`id`, `nazwa`) VALUES
(1, 'Język polski'),
(2, 'Język angielski'),
(3, 'Język niemiecki'),
(4, 'Matematyka'),
(5, 'Fizyka'),
(6, 'Chemia'),
(7, 'Biologia'),
(8, 'Geografia'),
(9, 'Historia'),
(10, 'Wiedza o społeczeństwie'),
(11, 'Informatyka'),
(12, 'Wychowanie fizyczne'),
(13, 'Edukacja dla bezpieczeństwa'),
(14, 'Religia'),
(15, 'Godzina wychowawcza'),
(16, 'Przedmioty zawodowe'),
(17, 'Praktyki zawodowe'),
(18, 'Edukacja wczesnoszkolna'),
(19, 'Przyroda'),
(20, 'Historia'),
(21, 'Geografia'),
(22, 'Biologia'),
(23, 'Informatyka'),
(24, 'Plastyka'),
(25, 'Muzyka'),
(26, 'Technika'),
(27, 'Zajęcia przedszkolne'),
(28, 'Logopedia'),
(29, 'Rytmika');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `przedmioty`
--
ALTER TABLE `przedmioty`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `przedmioty`
--
ALTER TABLE `przedmioty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
