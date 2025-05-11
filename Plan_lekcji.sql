-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Maj 11, 2025 at 07:33 PM
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
-- Struktura tabeli dla tabeli `klasy`
--

CREATE TABLE `klasy` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(10) NOT NULL,
  `rocznik` int(11) NOT NULL,
  `typ_szkoly_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `klasy`
--

INSERT INTO `klasy` (`id`, `nazwa`, `rocznik`, `typ_szkoly_id`) VALUES
(1, '1A', 2009, 1),
(2, '2A', 2008, 1),
(3, '3A', 2007, 1),
(4, '4A', 2006, 1),
(5, '1T', 2009, 2),
(6, '2T', 2008, 2),
(7, '3T', 2007, 2),
(8, '4T', 2006, 2),
(9, '5T', 2005, 2),
(10, '1a', 2017, 3),
(11, '2a', 2016, 3),
(12, '3a', 2015, 3),
(13, '4a', 2014, 3),
(14, '5a', 2013, 3),
(15, '6a', 2012, 3),
(16, '7a', 2011, 3),
(17, '8a', 2010, 3),
(18, 'Muchomorki', 2018, 4);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `plan_lekcji`
--

CREATE TABLE `plan_lekcji` (
  `id` int(11) NOT NULL,
  `klasa_id` int(11) DEFAULT NULL,
  `przedmiot_id` int(11) DEFAULT NULL,
  `sala_id` int(11) DEFAULT NULL,
  `dzien_tygodnia` enum('Poniedzialek','Wtorek','Sroda','Czwartek','Piatek') NOT NULL,
  `godzina_start` time NOT NULL,
  `godzina_koniec` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plan_lekcji`
--

INSERT INTO `plan_lekcji` (`id`, `klasa_id`, `przedmiot_id`, `sala_id`, `dzien_tygodnia`, `godzina_start`, `godzina_koniec`) VALUES
(126, 1, 2, 1, 'Poniedzialek', '10:50:00', '11:35:00'),
(127, 1, 4, 37, 'Poniedzialek', '07:10:00', '07:55:00'),
(128, 1, 10, 31, 'Poniedzialek', '13:35:00', '14:20:00'),
(129, 1, 1, 21, 'Poniedzialek', '08:05:00', '08:50:00'),
(130, 1, 6, 29, 'Poniedzialek', '09:55:00', '10:40:00'),
(131, 1, 6, 31, 'Wtorek', '08:05:00', '08:50:00'),
(132, 1, 12, 53, 'Wtorek', '11:45:00', '12:30:00'),
(133, 1, 12, 5, 'Wtorek', '09:55:00', '10:40:00'),
(134, 1, 6, 35, 'Wtorek', '10:50:00', '11:35:00'),
(135, 1, 9, 40, 'Wtorek', '12:40:00', '13:25:00'),
(136, 1, 1, 37, 'Sroda', '07:10:00', '07:55:00'),
(137, 1, 10, 31, 'Sroda', '13:35:00', '14:20:00'),
(138, 1, 6, 43, 'Sroda', '12:40:00', '13:25:00'),
(139, 1, 1, 49, 'Sroda', '09:00:00', '09:45:00'),
(140, 1, 2, 13, 'Sroda', '15:25:00', '16:10:00'),
(141, 1, 2, 21, 'Czwartek', '09:55:00', '10:40:00'),
(142, 1, 5, 38, 'Czwartek', '11:45:00', '12:30:00'),
(143, 1, 9, 44, 'Czwartek', '13:35:00', '14:20:00'),
(144, 1, 6, 17, 'Czwartek', '09:00:00', '09:45:00'),
(145, 1, 9, 41, 'Czwartek', '15:25:00', '16:10:00'),
(146, 1, 9, 25, 'Piatek', '13:35:00', '14:20:00'),
(147, 1, 5, 34, 'Piatek', '14:30:00', '15:15:00'),
(148, 1, 12, 53, 'Piatek', '08:05:00', '08:50:00'),
(149, 1, 6, 44, 'Piatek', '11:45:00', '12:30:00'),
(150, 1, 6, 25, 'Piatek', '09:00:00', '09:45:00'),
(151, 2, 11, 40, 'Poniedzialek', '09:55:00', '10:40:00'),
(152, 2, 12, 52, 'Poniedzialek', '15:25:00', '16:10:00'),
(153, 2, 9, 16, 'Poniedzialek', '07:10:00', '07:55:00'),
(154, 2, 1, 3, 'Poniedzialek', '13:35:00', '14:20:00'),
(155, 2, 1, 2, 'Poniedzialek', '08:05:00', '08:50:00'),
(156, 2, 9, 7, 'Wtorek', '15:25:00', '16:10:00'),
(157, 2, 9, 16, 'Wtorek', '09:00:00', '09:45:00'),
(158, 2, 9, 35, 'Wtorek', '13:35:00', '14:20:00'),
(159, 2, 9, 11, 'Wtorek', '09:55:00', '10:40:00'),
(160, 2, 2, 15, 'Wtorek', '07:10:00', '07:55:00'),
(161, 2, 1, 20, 'Sroda', '10:50:00', '11:35:00'),
(162, 2, 12, 51, 'Sroda', '09:00:00', '09:45:00'),
(163, 2, 16, 15, 'Sroda', '09:55:00', '10:40:00'),
(164, 2, 16, 10, 'Sroda', '11:45:00', '12:30:00'),
(165, 2, 2, 32, 'Sroda', '14:30:00', '15:15:00'),
(166, 2, 4, 7, 'Czwartek', '14:30:00', '15:15:00'),
(167, 2, 16, 18, 'Czwartek', '10:50:00', '11:35:00'),
(168, 2, 2, 16, 'Czwartek', '15:25:00', '16:10:00'),
(169, 2, 16, 39, 'Czwartek', '09:00:00', '09:45:00'),
(170, 2, 4, 27, 'Czwartek', '07:10:00', '07:55:00'),
(171, 2, 16, 47, 'Piatek', '15:25:00', '16:10:00'),
(172, 2, 9, 12, 'Piatek', '09:55:00', '10:40:00'),
(173, 2, 12, 53, 'Piatek', '14:30:00', '15:15:00'),
(174, 2, 2, 12, 'Piatek', '13:35:00', '14:20:00'),
(175, 2, 4, 31, 'Piatek', '10:50:00', '11:35:00'),
(176, 3, 4, 9, 'Poniedzialek', '15:25:00', '16:10:00'),
(177, 3, 28, 19, 'Poniedzialek', '12:40:00', '13:25:00'),
(178, 3, 12, 52, 'Poniedzialek', '08:05:00', '08:50:00'),
(179, 3, 2, 19, 'Poniedzialek', '14:30:00', '15:15:00'),
(180, 3, 12, 51, 'Poniedzialek', '11:45:00', '12:30:00'),
(181, 3, 28, 47, 'Wtorek', '09:55:00', '10:40:00'),
(182, 3, 19, 27, 'Wtorek', '09:00:00', '09:45:00'),
(183, 3, 9, 1, 'Wtorek', '07:10:00', '07:55:00'),
(184, 3, 1, 14, 'Wtorek', '13:35:00', '14:20:00'),
(185, 3, 1, 17, 'Wtorek', '08:05:00', '08:50:00'),
(186, 3, 4, 8, 'Sroda', '13:35:00', '14:20:00'),
(187, 3, 9, 30, 'Sroda', '15:25:00', '16:10:00'),
(188, 3, 12, 54, 'Sroda', '09:55:00', '10:40:00'),
(189, 3, 4, 10, 'Sroda', '11:45:00', '12:30:00'),
(190, 3, 1, 27, 'Sroda', '08:05:00', '08:50:00'),
(191, 3, 28, 12, 'Czwartek', '09:00:00', '09:45:00'),
(192, 3, 28, 41, 'Czwartek', '08:05:00', '08:50:00'),
(193, 3, 1, 35, 'Czwartek', '15:25:00', '16:10:00'),
(194, 3, 19, 3, 'Czwartek', '07:10:00', '07:55:00'),
(195, 3, 4, 50, 'Czwartek', '12:40:00', '13:25:00'),
(196, 3, 9, 22, 'Piatek', '11:45:00', '12:30:00'),
(197, 3, 2, 3, 'Piatek', '08:05:00', '08:50:00'),
(198, 3, 4, 4, 'Piatek', '12:40:00', '13:25:00'),
(199, 3, 12, 52, 'Piatek', '13:35:00', '14:20:00'),
(200, 3, 2, 46, 'Piatek', '09:55:00', '10:40:00'),
(201, 4, 27, 28, 'Poniedzialek', '07:10:00', '07:55:00'),
(202, 4, 26, 35, 'Poniedzialek', '09:00:00', '09:45:00'),
(203, 4, 27, 42, 'Poniedzialek', '15:25:00', '16:10:00'),
(204, 4, 27, 9, 'Poniedzialek', '08:05:00', '08:50:00'),
(205, 4, 27, 25, 'Poniedzialek', '11:45:00', '12:30:00'),
(206, 4, 27, 29, 'Wtorek', '15:25:00', '16:10:00'),
(207, 4, 25, 37, 'Wtorek', '12:40:00', '13:25:00'),
(208, 4, 26, 2, 'Wtorek', '13:35:00', '14:20:00'),
(209, 4, 27, 17, 'Wtorek', '09:55:00', '10:40:00'),
(210, 4, 25, 6, 'Wtorek', '08:05:00', '08:50:00'),
(211, 4, 25, 34, 'Sroda', '10:50:00', '11:35:00'),
(212, 4, 27, 17, 'Sroda', '12:40:00', '13:25:00'),
(213, 4, 27, 23, 'Sroda', '13:35:00', '14:20:00'),
(214, 4, 27, 42, 'Sroda', '14:30:00', '15:15:00'),
(215, 4, 25, 28, 'Sroda', '09:55:00', '10:40:00'),
(216, 4, 25, 34, 'Czwartek', '09:00:00', '09:45:00'),
(217, 4, 27, 38, 'Czwartek', '12:40:00', '13:25:00'),
(218, 4, 25, 18, 'Czwartek', '11:45:00', '12:30:00'),
(219, 4, 27, 45, 'Czwartek', '15:25:00', '16:10:00'),
(220, 4, 27, 7, 'Czwartek', '07:10:00', '07:55:00'),
(221, 4, 25, 7, 'Piatek', '08:05:00', '08:50:00'),
(222, 4, 26, 13, 'Piatek', '15:25:00', '16:10:00'),
(223, 4, 25, 19, 'Piatek', '09:00:00', '09:45:00'),
(224, 4, 25, 16, 'Piatek', '09:55:00', '10:40:00'),
(225, 4, 27, 25, 'Piatek', '13:35:00', '14:20:00'),
(226, 5, 1, 22, 'Poniedzialek', '14:30:00', '15:15:00'),
(227, 5, 2, 29, 'Poniedzialek', '07:10:00', '07:55:00'),
(228, 5, 2, 44, 'Poniedzialek', '10:50:00', '11:35:00'),
(229, 5, 16, 6, 'Poniedzialek', '15:25:00', '16:10:00'),
(230, 5, 9, 8, 'Poniedzialek', '12:40:00', '13:25:00'),
(231, 5, 2, 4, 'Wtorek', '15:25:00', '16:10:00'),
(232, 5, 2, 21, 'Wtorek', '12:40:00', '13:25:00'),
(233, 5, 12, 52, 'Wtorek', '13:35:00', '14:20:00'),
(234, 5, 2, 10, 'Wtorek', '11:45:00', '12:30:00'),
(235, 5, 12, 52, 'Wtorek', '14:30:00', '15:15:00'),
(236, 5, 2, 42, 'Sroda', '07:10:00', '07:55:00'),
(237, 5, 4, 4, 'Sroda', '15:25:00', '16:10:00'),
(238, 5, 9, 14, 'Sroda', '12:40:00', '13:25:00'),
(239, 5, 1, 36, 'Sroda', '14:30:00', '15:15:00'),
(240, 5, 2, 22, 'Sroda', '08:05:00', '08:50:00'),
(241, 5, 1, 31, 'Czwartek', '09:55:00', '10:40:00'),
(242, 5, 1, 20, 'Czwartek', '11:45:00', '12:30:00'),
(243, 5, 4, 9, 'Czwartek', '15:25:00', '16:10:00'),
(244, 5, 12, 53, 'Czwartek', '09:00:00', '09:45:00'),
(245, 5, 1, 18, 'Czwartek', '14:30:00', '15:15:00'),
(246, 5, 11, 9, 'Piatek', '10:50:00', '11:35:00'),
(247, 5, 12, 5, 'Piatek', '15:25:00', '16:10:00'),
(248, 5, 11, 42, 'Piatek', '14:30:00', '15:15:00'),
(249, 5, 4, 31, 'Piatek', '08:05:00', '08:50:00'),
(250, 5, 1, 48, 'Piatek', '09:55:00', '10:40:00');

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

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `sale`
--

CREATE TABLE `sale` (
  `id` int(11) NOT NULL,
  `numer` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale`
--

INSERT INTO `sale` (`id`, `numer`) VALUES
(1, '1'),
(2, '2'),
(3, '3'),
(4, '4'),
(5, '5'),
(6, '6'),
(7, '7'),
(8, '8'),
(9, '9'),
(10, '10'),
(11, '11'),
(12, '12'),
(13, '13'),
(14, '14'),
(15, '15'),
(16, '16'),
(17, '17'),
(18, '18'),
(19, '19'),
(20, '20'),
(21, '21'),
(22, '22'),
(23, '23'),
(24, '24'),
(25, '25'),
(26, '26'),
(27, '27'),
(28, '28'),
(29, '29'),
(30, '30'),
(31, '31'),
(32, '32'),
(33, '33'),
(34, '34'),
(35, '35'),
(36, '36'),
(37, '37'),
(38, '38'),
(39, '39'),
(40, '40'),
(41, '41'),
(42, '42'),
(43, '43'),
(44, '44'),
(45, '45'),
(46, '46'),
(47, '47'),
(48, '48'),
(49, '49'),
(50, '50'),
(51, '1wf'),
(52, '2wf'),
(53, '3wf'),
(54, '4wf'),
(55, '5wf');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `typy_szkol`
--

CREATE TABLE `typy_szkol` (
  `id` int(11) NOT NULL,
  `nazwa` enum('Liceum','Technikum','Szkola Podstawowa','Przedszkole') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `typy_szkol`
--

INSERT INTO `typy_szkol` (`id`, `nazwa`) VALUES
(1, 'Liceum'),
(2, 'Technikum'),
(3, 'Szkola Podstawowa'),
(4, 'Przedszkole');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `klasy`
--
ALTER TABLE `klasy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `typ_szkoly_id` (`typ_szkoly_id`);

--
-- Indeksy dla tabeli `plan_lekcji`
--
ALTER TABLE `plan_lekcji`
  ADD PRIMARY KEY (`id`),
  ADD KEY `klasa_id` (`klasa_id`),
  ADD KEY `przedmiot_id` (`przedmiot_id`),
  ADD KEY `sala_id` (`sala_id`);

--
-- Indeksy dla tabeli `przedmioty`
--
ALTER TABLE `przedmioty`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `sale`
--
ALTER TABLE `sale`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `typy_szkol`
--
ALTER TABLE `typy_szkol`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `klasy`
--
ALTER TABLE `klasy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `plan_lekcji`
--
ALTER TABLE `plan_lekcji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

--
-- AUTO_INCREMENT for table `przedmioty`
--
ALTER TABLE `przedmioty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `sale`
--
ALTER TABLE `sale`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `typy_szkol`
--
ALTER TABLE `typy_szkol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `klasy`
--
ALTER TABLE `klasy`
  ADD CONSTRAINT `klasy_ibfk_1` FOREIGN KEY (`typ_szkoly_id`) REFERENCES `typy_szkol` (`id`);

--
-- Constraints for table `plan_lekcji`
--
ALTER TABLE `plan_lekcji`
  ADD CONSTRAINT `plan_lekcji_ibfk_1` FOREIGN KEY (`klasa_id`) REFERENCES `klasy` (`id`),
  ADD CONSTRAINT `plan_lekcji_ibfk_2` FOREIGN KEY (`przedmiot_id`) REFERENCES `przedmioty` (`id`),
  ADD CONSTRAINT `plan_lekcji_ibfk_3` FOREIGN KEY (`sala_id`) REFERENCES `sale` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
