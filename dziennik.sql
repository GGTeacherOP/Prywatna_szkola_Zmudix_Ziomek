-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Maj 11, 2025 at 09:39 PM
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
-- Database: `dziennik`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `klasy`
--

CREATE TABLE `klasy` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(10) NOT NULL,
  `rocznik` int(11) NOT NULL,
  `typ_szkoly` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `klasy`
--

INSERT INTO `klasy` (`id`, `nazwa`, `rocznik`, `typ_szkoly`) VALUES
(1, '1A', 2009, 'Liceum'),
(2, '2A', 2008, 'Liceum'),
(3, '3A', 2007, 'Liceum'),
(4, '4A', 2006, 'Liceum'),
(5, '1T', 2009, 'Technikum'),
(6, '2T', 2008, 'Technikum'),
(7, '3T', 2007, 'Technikum'),
(8, '4T', 2006, 'Technikum'),
(9, '5T', 2005, 'Technikum'),
(10, '1a', 2017, 'Szkoła podstawowa'),
(11, '2a', 2016, 'Szkoła podstawowa'),
(12, '3a', 2015, 'Szkoła podstawowa'),
(13, '4a', 2014, 'Szkoła podstawowa'),
(14, '5a', 2013, 'Szkoła podstawowa'),
(15, '6a', 2012, 'Szkoła podstawowa'),
(16, '7a', 2011, 'Szkoła podstawowa'),
(17, '8a', 2010, 'Szkoła podstawowa');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `nauczyciele`
--

CREATE TABLE `nauczyciele` (
  `id` int(11) NOT NULL,
  `imie` varchar(50) NOT NULL,
  `nazwisko` varchar(50) NOT NULL,
  `id_przedmiotu` int(11) DEFAULT NULL,
  `id_klasy_wychowawca` int(11) DEFAULT NULL,
  `login` varchar(50) NOT NULL,
  `haslo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nauczyciele`
--

INSERT INTO `nauczyciele` (`id`, `imie`, `nazwisko`, `id_przedmiotu`, `id_klasy_wychowawca`, `login`, `haslo`) VALUES
(1, 'Paweł', 'Woźniak', 1, 1, 'paweł_woźsdfssdsddniak', 'pawełwoźniak123'),
(2, 'Anna', 'Mazur', 1, 15, 'anna_mazufdsfr', 'annamazur123'),
(3, 'Marek', 'Dąbrowski', 1, 2, 'marek_dąbrowsfffki', 'marekdąbrowski123'),
(4, 'Joanna', 'Jankowski', 2, 14, 'joanna_janfffkowski', 'joannajankowski123'),
(5, 'Zofia', 'Krawczyk', 2, 3, 'zofia_krawcffzyk', 'zofiakrawczyk123'),
(6, 'Paweł', 'Zieliński', 2, 13, 'paweł_zifffeliński', 'pawełzieliński123'),
(7, 'Paweł', 'Woźniak', 3, 5, 'pawełffffff_woźniak', 'pawełwoźniak123'),
(8, 'Joanna', 'Jankowski', 3, NULL, 'joanna_jankfffowski', 'joannajankowski123'),
(9, 'Krzysztof', 'Nowak', 3, 4, 'krzysztofffff_nowak', 'krzysztofnowak123'),
(10, 'Magdalena', 'Woźniak', 4, 12, 'magdalena_wfffoźniak', 'magdalenawoźniak123'),
(11, 'Ewa', 'Wiśniewski', 4, 11, 'ewa_wiśniewsfffki', 'ewawiśniewski123'),
(12, 'Jan', 'Kozłowski', 4, NULL, 'jan_kozłowffffffski', 'jankozłowski123'),
(13, 'Maria', 'Kowalski', 5, NULL, 'maria_fffkowalski', 'mariakowalski123'),
(14, 'Tomasz', 'Jankowski', 5, 6, 'tfffomasz_jankowski', 'tomaszjankowski123'),
(15, 'Paweł', 'Zieliński', 5, NULL, 'pfaweł_zieliński', 'pawełzieliński123'),
(16, 'Ewa', 'Mazur', 6, 10, 'efffwa_mazur', 'ewamazur123'),
(17, 'Tomasz', 'Szymański', 6, 17, 'tomasz_ssdfzymański', 'tomaszszymański123'),
(18, 'Magdalena', 'Wiśniewski', 6, 16, 'magdalena_wsdfsfsdiśniewski', 'magdalenawiśniewski123'),
(19, 'Anna', 'Szymański', 7, 7, 'anna_szymańsdfdfsdski', 'annaszymański123'),
(20, 'Michał', 'Lewandowski', 7, 9, 'michał_sfdsdflewandowski', 'michałlewandowski123'),
(21, 'Piotr', 'Wójcik', 7, NULL, 'piotr_wósdfsdfjcik', 'piotrwójcik123'),
(22, 'Zofia', 'Kowalczyk', 8, NULL, 'zofia_ksdfsdfsdfowalczyk', 'zofiakowalczyk123'),
(23, 'Maria', 'Kowalski', 8, 8, 'mariasdf_kowalsski', 'mariakowalski123'),
(24, 'Jan', 'Wójcik', 8, NULL, 'jan_wójcisk', 'janwójcik123'),
(25, 'Katarzyna', 'Wójcik', 9, NULL, 'katarzynas_wójcik', 'katarzynawójcik123'),
(26, 'Krzysztof', 'Kowalczyk', 9, NULL, 'krzyssssssssztof_kowalczyk', 'krzysztofkowalczyk123'),
(27, 'Katarzyna', 'Krawczyk', 9, NULL, 'katarszyna_krawczyk', 'katarzynakrawczyk123'),
(28, 'Ewa', 'Kozłowski', 10, NULL, 'ewa_kozłsowski', 'ewakozłowski123'),
(29, 'Magdalena', 'Mazur', 10, NULL, 'magdalena_mazur', 'magdalenamazur123'),
(30, 'Michał', 'Szymański', 10, NULL, 'michał_szymańsski', 'michałszymański123'),
(31, 'Anna', 'Mazur', 11, NULL, 'anna_maszur', 'annasmazur123'),
(32, 'Paweł', 'Kowalski', 11, NULL, 'pawesł_kowalsksi', 'pawełkowalski123'),
(33, 'Magdalena', 'Mazur', 11, NULL, 'magdsalena_msazur', 'magdalenamazur123'),
(34, 'Ewa', 'Lewandowski', 12, NULL, 'ewa_lsewandsowski', 'ewalewandowski123'),
(35, 'Piotr', 'Lewandowski', 12, NULL, 'piotsr_sslewandowski', 'piotrlewandowski123'),
(36, 'Michał', 'Nowak', 12, NULL, 'michał_nowsak', 'michałnowak123'),
(37, 'Jan', 'Krawczyk', 13, NULL, 'jan_krawcszyk', 'jankrawczyk123'),
(38, 'Anna', 'Kozłowski', 13, NULL, 'anna_ksozłowski', 'annakozłowski123'),
(39, 'Ewa', 'Wójcik', 13, NULL, 'ewa_wójcisk', 'ewawójcik123'),
(40, 'Zofia', 'Wójcik', 14, NULL, 'zofia_swójcik', 'zofiawójcik123'),
(41, 'Maria', 'Kowalski', 14, NULL, 'marsia_kowalski', 'mariakowalski123'),
(42, 'Maria', 'Mazur', 14, NULL, 'marias_mazur', 'mariamazur123'),
(43, 'Paweł', 'Dąbrowski', 15, NULL, 'paweł_dąbrowasdski', 'pawełdąbrowski123'),
(44, 'Anna', 'Krawczyk', 15, NULL, 'anna_krawczsdayk', 'annakrawczyk123'),
(45, 'Joanna', 'Kowalski', 15, NULL, 'joannasad_kowalski', 'joannakowalski123');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `nowe_konta`
--

CREATE TABLE `nowe_konta` (
  `id` int(11) NOT NULL,
  `imie` varchar(50) DEFAULT NULL,
  `nazwisko` varchar(50) DEFAULT NULL,
  `numer_telefonu` varchar(15) DEFAULT NULL,
  `mail` varchar(100) DEFAULT NULL,
  `stanowisko` enum('nauczyciel','uczen') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `oceny`
--

CREATE TABLE `oceny` (
  `id` int(11) NOT NULL,
  `id_ucznia` int(11) NOT NULL,
  `id_przedmiotu` int(11) NOT NULL,
  `id_nauczyciela` int(11) NOT NULL,
  `ocena` decimal(3,1) NOT NULL,
  `data_dodania` date NOT NULL DEFAULT curdate(),
  `opis` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `przedmioty`
--

CREATE TABLE `przedmioty` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `przedmioty`
--

INSERT INTO `przedmioty` (`id`, `nazwa`) VALUES
(1, 'Edukacja wczesnoszkolna'),
(2, 'Religia'),
(3, 'Język polski'),
(4, 'Matematyka'),
(5, 'Język angielski'),
(6, 'Przyroda'),
(7, 'Technika'),
(8, 'Informatyka'),
(9, 'Wychowanie fizyczne'),
(10, 'Biologia'),
(11, 'Chemia'),
(12, 'Fizyka'),
(13, 'Geografia'),
(14, 'Historia'),
(15, 'Wiedza o społeczeństwie');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uczniowie`
--

CREATE TABLE `uczniowie` (
  `id` int(11) NOT NULL,
  `imie` varchar(50) NOT NULL,
  `nazwisko` varchar(50) NOT NULL,
  `id_klasy` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `haslo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uczniowie`
--

INSERT INTO `uczniowie` (`id`, `imie`, `nazwisko`, `id_klasy`, `login`, `haslo`) VALUES
(1, 'Tomasz', 'Nowak', 1, 'tomasz_nosdfwak3123', 'tomasznowak123'),
(2, 'Joanna', 'Kowalczyk', 1, 'joannasfd_kowalczyk1233', 'joannakowalczyk123'),
(3, 'Zofia', 'Dąbrowski', 1, 'zofia_dąbrowski1323', 'zofiadąbrowski123'),
(4, 'Jan', 'Dąbrowski', 1, 'jan_dąbrowski13231', 'jandąbrowski123'),
(5, 'Jan', 'Mazur', 1, 'jan_mazufsdfsdr4433', 'jsdfanmazur123'),
(6, 'Tomasz', 'Lewandowski', 1, 'tomassdfsz_lewandowski23', 'tomaszlewandowski123'),
(7, 'Zofia', 'Woźniak', 1, 'zofia_wdfoźdrfsdniak432', 'zofiawoźniak123'),
(8, 'Joanna', 'Krawczyk', 1, 'joanna_krawczyk1234', 'joannakrawczyk123'),
(9, 'Jan', 'Jankowski', 1, 'jan_janksdfowsqwerki111', 'janjankowski123'),
(10, 'Maria', 'Wiśniewski', 1, 'maria_wiśniewski222', 'mariawiśniewski123'),
(11, 'Maria', 'Jankowski', 1, 'maria_janwqerkowski555', 'mariajankowski123'),
(12, 'Maria', 'Mazur', 1, 'maria_mazur55werq55', 'mariamazur123'),
(13, 'Krzysztof', 'Kozłowski', 1, 'krzysztof_kowrqezłowskiq3', 'krzysztofkozłowski123'),
(14, 'Michał', 'Jankowski', 1, 'michał_jankowsrqwekiddd', 'michałjankowski123'),
(15, 'Jan', 'Krawczyk', 1, 'jan_krawczykffff', 'jankrawczyk123'),
(16, 'Marek', 'Jankowski', 2, 'marek_jankowski', 'marekjankowski123'),
(17, 'Krzysztof', 'Dąbrowski', 2, 'krzysztof_dąqwerweqbrowski', 'krzysztofdąbrowski123'),
(18, 'Katarzyna', 'Kozłowski', 2, 'katarzqwerqeryna_kozłowski', 'katarzynakozłowski123'),
(19, 'Tomasz', 'Nowak', 2, 'tomaszqwerqwer_nowak1', 'tomasznowak123'),
(20, 'Magdalena', 'Lewandowski', 2, 'magdaqwerqwerlena_lewandowski', 'magdalenalewandowski123'),
(21, 'Anna', 'Dąbrowski', 2, 'anna_dweqrwqerqwąbrowski', 'annadąbrowski123'),
(22, 'Tomasz', 'Wiśniewski', 2, 'tomasz_wrqwerqrwiśniewski', 'tomaszwiśniewski123'),
(23, 'Michał', 'Nowak', 2, 'michałfasdfasd_nowak', 'michałnowak123'),
(24, 'Magdalena', 'Wójcik', 2, 'magdaleasdfasna_wójcik', 'magdalenawójcik123'),
(25, 'Ewa', 'Dąbrowski', 2, 'ewa_dąbrowsqwefwfqeqwefki', 'ewadąbrowski123'),
(26, 'Magdalena', 'Nowak', 2, 'magdalfweena_nowak', 'magdalenanowak123'),
(27, 'Agnieszka', 'Szymański', 2, 'agniewefwfeszka_szymański', 'agnieszkaszymański123'),
(28, 'Paweł', 'Szymański', 2, 'pawweffqweeł_szymański', 'pawełszymański123'),
(29, 'Anna', 'Woźniak', 2, 'anna_woźnefwqefiak', 'annawoźniak123'),
(30, 'Maria', 'Lewandowski', 2, 'maria_lefqwefwewandowski', 'marialewandowski123'),
(31, 'Magdalena', 'Nowak', 3, 'mfewwefefqwagdalena_nowak1', 'magdalenanowak123'),
(32, 'Piotr', 'Lewandowski', 3, 'pioqwefqwefeftr_lewandowski', 'piotrlewandowski123'),
(33, 'Michał', 'Kamiński', 3, 'michał_fqwefqwfwqekamiński', 'michałkamiński123'),
(34, 'Tomasz', 'Dąbrowski', 3, 'tomasz_asdfasfasdfadąbrowski', 'tomaszdąbrowski123'),
(35, 'Katarzyna', 'Krawczyk', 3, 'katarzynaasdfsadf_krawczyk', 'katarzynakrawczyk123'),
(36, 'Maria', 'Krawczyk', 3, 'maria_kraasdfswczyk', 'mariakrawczyk123'),
(37, 'Joanna', 'Wójcik', 3, 'joanasdfasdna_wójcik', 'joannawójcik123'),
(38, 'Krzysztof', 'Zieliński', 3, 'krzysasdfaztof_zieliński', 'krzysztofzieliński123'),
(39, 'Krzysztof', 'Jankowski', 3, 'krzysasdfztof_jankowski', 'krzysztofjankowski123'),
(40, 'Tomasz', 'Kozłowski', 3, 'tomasz_kozsdfasdłowski', 'tomaszkozłowski123'),
(41, 'Zofia', 'Szymański', 3, 'zofisadfasdfa_szymański', 'zofiaszymański123'),
(42, 'Joanna', 'Kowalczyk', 3, 'joanna_kowasdffasdfalczyk1', 'joannakowalczyk123'),
(43, 'Paweł', 'Kamiński', 3, 'paweł_kfasdfaamiński', 'pawełkamiński123'),
(44, 'Michał', 'Kozłowski', 3, 'michał_qwefqwefkozłowski', 'michałkozłowski123'),
(45, 'Magdalena', 'Dąbrowski', 3, 'magdqwefqwfefalena_dąbrowski', 'magdalenadąbrowski123'),
(46, 'Maria', 'Mazur', 4, 'maria_mafqwefzur1', 'mariamazur123'),
(47, 'Anna', 'Wójcik', 4, 'annafewfq_wójcik', 'annawójcik123'),
(48, 'Katarzyna', 'Nowak', 4, 'katawefqrzyna_nowak', 'katarzynanowak123'),
(49, 'Magdalena', 'Jankowski', 4, 'magdalwefena_jankowski', 'magdalenajankowski123'),
(50, 'Joanna', 'Nowak', 4, 'jofqwefqwefqweanna_nowak', 'joannanowak123'),
(51, 'Piotr', 'Kamiński', 4, 'piotr_kamfqwefwefiński', 'piotrkamiński123'),
(52, 'Tomasz', 'Kozłowski', 4, 'tomasz_kozłowfqwfqwski1', 'tomaszkozłowski123'),
(53, 'Tomasz', 'Kowalski', 4, 'tomasz_kqwefeqwowalski', 'tomaszkowalski123'),
(54, 'Katarzyna', 'Wójcik', 4, 'katarfqwefwqfzyna_wójcik', 'katarzynawójcik123'),
(55, 'Jan', 'Zieliński', 4, 'jan_ziefqwefqwefliński', 'janzieliński123'),
(56, 'Agnieszka', 'Dąbrowski', 4, 'agnieszkaqwefwef_dąbrowski', 'agnieszkadąbrowski123'),
(57, 'Katarzyna', 'Zieliński', 4, 'katarzyna_qwefwqefzieliński', 'katarzynazieliński123'),
(58, 'Zofia', 'Woźniak', 4, 'zofia_wqwfefqwefewfoźniak1', 'zofiawoźniak123'),
(59, 'Jan', 'Kowalczyk', 4, 'jan_kcasdfwefqweowalczyk', 'jankowalczyk123'),
(60, 'Jan', 'Zieliński', 4, 'jan_zielińrqweski1', 'janzieliński123'),
(61, 'Ewa', 'Woźniak', 5, 'ewa_wqwerwerqoźniak', 'ewawoźniak123'),
(62, 'Katarzyna', 'Woźniak', 5, 'katarzqwerrqweyna_woźniak', 'katarzynawoźniak123'),
(63, 'Maria', 'Kowalczyk', 5, 'mariqwerwerqwerqwa_kowalczyk', 'mariakowalczyk123'),
(64, 'Agnieszka', 'Nowak', 5, 'agniesfasdfasdasdfsaszka_nowak', 'agnieszkanowak123'),
(65, 'Marek', 'Zieliński', 5, 'marek_zasdffdieliński', 'marekzieliński123'),
(66, 'Joanna', 'Zieliński', 5, 'joannaasdfasfafasd_zieliński', 'joannazieliński123'),
(67, 'Tomasz', 'Mazur', 5, 'tomaasdfadsz_mazur', 'tomaszmazur123'),
(68, 'Magdalena', 'Kowalski', 5, 'maasdfgdalena_kowalski', 'magdalenakowalski123'),
(69, 'Piotr', 'Nowak', 5, 'piotr_asdfnowak', 'piotrnowak123'),
(70, 'Michał', 'Dąbrowski', 5, 'michał_asdfdąbrowski', 'michałdąbrowski123'),
(71, 'Agnieszka', 'Zieliński', 5, 'agnieszasdfka_zieliński', 'agnieszkazieliński123'),
(72, 'Anna', 'Wiśniewski', 5, 'anna_wiśfasdniewski', 'annawiśniewski123'),
(73, 'Anna', 'Kowalczyk', 5, 'anna_kasdfowalczyk', 'annakowalczyk123'),
(74, 'Krzysztof', 'Mazur', 5, 'krzysztof_mazasdfur', 'krzysztofmazur123'),
(75, 'Krzysztof', 'Kamiński', 5, 'krzyszasdftof_kamiński', 'krzysztofkamiński123'),
(76, 'Maria', 'Zieliński', 6, 'mariaasdf_zieliński', 'mariazieliński123'),
(77, 'Maria', 'Krawczyk', 6, 'maria_krafasdfasdwczyk1', 'mariakrawczyk123'),
(78, 'Jan', 'Jankowski', 6, 'jan_janasdfkowski1', 'janjankowski123'),
(79, 'Anna', 'Zieliński', 6, 'anna_zielidfvsński', 'annazieliński123'),
(80, 'Zofia', 'Dąbrowski', 6, 'zofia_dąbrosxcvwski1', 'zofiadąbrowski123'),
(81, 'Tomasz', 'Szymański', 6, 'tomasz_vwerszymański', 'tomaszszymański123'),
(82, 'Krzysztof', 'Wójcik', 6, 'krzysztofvewwrvvr_wójcik', 'krzysztofwójcik123'),
(83, 'Piotr', 'Kowalczyk', 6, 'piotfvevefwevevr_kowalczyk', 'piotrkowalczyk123'),
(84, 'Jan', 'Dąbrowski', 6, 'jan_dąbroczxczczxcwski1', 'jandąbrowski123'),
(85, 'Anna', 'Mazur', 6, 'annzxczcxxczca_mazur', 'annamazur123'),
(86, 'Katarzyna', 'Zieliński', 6, 'katarzccccccyna_zieliński1', 'katarzynazieliński123'),
(87, 'Krzysztof', 'Nowak', 6, 'krzysztof_nccccccccowak', 'krzysztofnowak123'),
(88, 'Piotr', 'Woźniak', 6, 'piotcccccccccr_woźniak', 'piotrwoźniak123'),
(89, 'Agnieszka', 'Kamiński', 6, 'agnieszkacccccc_kamiński', 'agnieszkakamiński123'),
(90, 'Tomasz', 'Krawczyk', 6, 'tomaszccccccc_krawczyk', 'tomaszkrawczyk123'),
(91, 'Michał', 'Szymański', 7, 'michał_szcccccymański', 'michałszymański123'),
(92, 'Piotr', 'Woźniak', 7, 'piotr_woźncccciak1', 'piotrwoźniak123'),
(93, 'Krzysztof', 'Mazur', 7, 'krzyccccsztof_mazur1', 'krzysztofmazur123'),
(94, 'Zofia', 'Kozłowski', 7, 'zofia_kozccccłowski', 'zofiakozłowski123'),
(95, 'Anna', 'Zieliński', 7, 'anna_ccccczieliński1', 'annazieliński123'),
(96, 'Zofia', 'Wójcik', 7, 'zofia_wójccccik', 'zofiawójcik123'),
(97, 'Ewa', 'Krawczyk', 7, 'ewa_krawcccczyk', 'ewakrawczyk123'),
(98, 'Paweł', 'Krawczyk', 7, 'paweł_krccawczyk', 'pawełkrawczyk123'),
(99, 'Joanna', 'Mazur', 7, 'joannacccc_mazur', 'joannamazur123'),
(100, 'Agnieszka', 'Kamiński', 7, 'agniesczka_kamiński1', 'agnieszkakamiński123'),
(101, 'Krzysztof', 'Kowalski', 7, 'krzyscztof_kowalski', 'krzysztofkowalski123'),
(102, 'Tomasz', 'Wiśniewski', 7, 'tomascz_wiśniewski1', 'tomaszwiśniewski123'),
(103, 'Joanna', 'Lewandowski', 7, 'joccanna_lewandowski', 'joannalewandowski123'),
(104, 'Anna', 'Zieliński', 7, 'annccca_zieliński2', 'annazieliński123'),
(105, 'Maria', 'Kowalski', 7, 'mariacccccccccc_kowalski', 'mariakowalski123'),
(106, 'Joanna', 'Szymański', 8, 'joanna_szycccmański', 'joannaszymański123'),
(107, 'Anna', 'Jankowski', 8, 'anna_jankcccowski', 'annajankowski123'),
(108, 'Tomasz', 'Szymański', 8, 'tomasccz_szymańskicccc1', 'tomaszszymański123'),
(109, 'Anna', 'Jankowski', 8, 'anna_jankowski1ccc', 'annajankowski123'),
(110, 'Michał', 'Kowalski', 8, 'michał_kowaccclski', 'michałkowalski123'),
(111, 'Joanna', 'Nowak', 8, 'joanna_nocccccwak1', 'joannanowak123'),
(112, 'Agnieszka', 'Woźniak', 8, 'acccgnieszka_woźniak', 'agnieszkawoźniak123'),
(113, 'Joanna', 'Wiśniewski', 8, 'cjoanna_wiśniewski', 'joannawiśniewski123'),
(114, 'Tomasz', 'Nowak', 8, 'tocccmasz_nowak2', 'tomasznowak123'),
(115, 'Agnieszka', 'Lewandowski', 8, 'agnieszdfsdfsdka_lewandowski', 'agnieszkalewandowski123'),
(116, 'Jan', 'Zieliński', 8, 'jan_zielińssdfski2', 'janzieliński123'),
(117, 'Piotr', 'Szymański', 8, 'piofsdfsdtr_szymański', 'piotrszymański123'),
(118, 'Joanna', 'Jankowski', 8, 'joannsdfsdfa_jankowski', 'joannajankowski123'),
(119, 'Agnieszka', 'Szymański', 8, 'agnieszksdfsfdfa_szymański1', 'agnieszkaszymański123'),
(120, 'Zofia', 'Kowalczyk', 8, 'zofia_sdffsdfkowalczyk', 'zofiakowalczyk123'),
(121, 'Anna', 'Kozłowski', 9, 'anna_kozłofsdfsfwski', 'annakozłowski123'),
(122, 'Maria', 'Kowalczyk', 9, 'mariasdfsdf_kowalczyk1', 'mariakowalczyk123'),
(123, 'Krzysztof', 'Kowalczyk', 9, 'krzysztof_ksdfsdfowalczyk', 'krzysztofkowalczyk123'),
(124, 'Katarzyna', 'Jankowski', 9, 'katarfsdfsdfzyna_jankowski', 'katarzynajankowski123'),
(125, 'Ewa', 'Zieliński', 9, 'ewa_zisdfsdeliński', 'ewazieliński123'),
(126, 'Magdalena', 'Lewandowski', 9, 'magdfsdfsalena_lewandowski1', 'magdalenalewandowski123'),
(127, 'Maria', 'Zieliński', 9, 'maria_zieliński1sfd', 'mariazieliński123'),
(128, 'Joanna', 'Wiśniewski', 9, 'joanna_wiśnsdfiewski1', 'joannawiśniewski123'),
(129, 'Magdalena', 'Wójcik', 9, 'magdalena_wsójcddik1', 'magdalenawójcik123'),
(130, 'Anna', 'Kowalski', 9, 'anna_kodfsdfsdwalski', 'annakowalski123'),
(131, 'Paweł', 'Wiśniewski', 9, 'paweł_wiśnieddwski', 'pawełwiśniewski123'),
(132, 'Tomasz', 'Wójcik', 9, 'tomasz_wóddddddjcik', 'tomaszwójcik123'),
(133, 'Tomasz', 'Woźniak', 9, 'tomasz_woźniak', 'tomaszwoźniak123'),
(134, 'Agnieszka', 'Kowalczyk', 9, 'agdnieszdddddka_kowalczyk', 'agnieszkakowalczyk123'),
(135, 'Joanna', 'Kowalczyk', 9, 'joanna_ddddkowalczyk2', 'joannakowalczyk123'),
(136, 'Piotr', 'Wójcik', 10, 'piotddddddddddr_wójcik', 'pdiotrwójcik123'),
(137, 'Anna', 'Szymański', 10, 'anna_szymańsdddkid', 'annaszymański123'),
(138, 'Marek', 'Krawczyk', 10, 'marek_krawczydk', 'marekkrawczyk123'),
(139, 'Paweł', 'Jankowski', 10, 'paweł_jankodwski', 'pawełjankowski123'),
(140, 'Krzysztof', 'Dąbrowski', 10, 'krzyszdtof_dąbrowski1', 'krzysztofdąbrowski123'),
(141, 'Paweł', 'Szymański', 10, 'paweł_szymański1', 'pawełszymański123'),
(142, 'Marek', 'Wójcik', 10, 'marek_wdddddddójcik', 'marekwójcik123'),
(143, 'Katarzyna', 'Dąbrowski', 10, 'katddddddarzyna_dąbrowski', 'katarzynadąbrowski123'),
(144, 'Marek', 'Dąbrowski', 10, 'maredddk_dąbrowski', 'marekdąbrowski123'),
(145, 'Michał', 'Kowalski', 10, 'michał_ddkowaldski1', 'michałkowalski123'),
(146, 'Jan', 'Mazur', 10, 'jan_mazuddddr1', 'jandmazur123'),
(147, 'Piotr', 'Krawczyk', 10, 'piotr_kddddrawcdzyk', 'piotrkrawczyk123'),
(148, 'Agnieszka', 'Szymański', 10, 'agniedddszka_szymański2', 'agnieszkaszymański123'),
(149, 'Jan', 'Kowalczyk', 10, 'jan_kowalcdzyk1', 'jankowalczyk123'),
(150, 'Maria', 'Wiśniewski', 10, 'maria_wddiśniewski1', 'mariawiśniewski123'),
(151, 'Piotr', 'Wiśniewski', 11, 'piotr_wiśdniewski', 'piotrwiśniewski123'),
(152, 'Ewa', 'Krawczyk', 11, 'ewa_krawczddyk1', 'ewakrawczyk123'),
(153, 'Zofia', 'Kowalczyk', 11, 'zofia_kowalczykd1', 'zofiakowalczyk123'),
(154, 'Katarzyna', 'Dąbrowski', 11, 'katarzyna_ddąbrowski1', 'katarzynadąbrowski123'),
(155, 'Krzysztof', 'Lewandowski', 11, 'krzysztdof_lewandowski', 'krzysztoflewandowski123'),
(156, 'Michał', 'Woźniak', 11, 'michał_woźniadk', 'michałwoźniak123'),
(157, 'Paweł', 'Kowalczyk', 11, 'paweł_kowaldczyk', 'pawełkowalczyk123'),
(158, 'Katarzyna', 'Kowalski', 11, 'katarzydna_kowalski', 'katarzynakowalski123'),
(159, 'Michał', 'Krawczyk', 11, 'michał_krdawczyk', 'michałkrawczyk123'),
(160, 'Jan', 'Jankowski', 11, 'jan_jankowski2', 'janjankowski123'),
(161, 'Michał', 'Lewandowski', 11, 'michadł_lewandowski', 'michałlewandowski123'),
(162, 'Piotr', 'Dąbrowski', 11, 'piotr_asdfdąbrowski', 'piotrdąbrowski123'),
(163, 'Anna', 'Kowalczyk', 11, 'anna_kowasdfalczyk1', 'annakowalczyk123'),
(164, 'Agnieszka', 'Lewandowski', 11, 'agnasdfasieszka_lewandowski1', 'agnieszkalewandowski123'),
(165, 'Tomasz', 'Kozłowski', 11, 'tomasz_kfdsafdozłowski2', 'tomaszkozłowski123'),
(166, 'Michał', 'Wiśniewski', 12, 'michfdsfał_wiśniewski', 'michałwiśniewski123'),
(167, 'Krzysztof', 'Dąbrowski', 12, 'krzasdfqweysztof_dąbrowski2', 'krzysztofdąbrowski123'),
(168, 'Zofia', 'Jankowski', 12, 'zofia_jasdfankowski', 'zofiajankowski123'),
(169, 'Ewa', 'Mazur', 12, 'ewa_asfdmazur', 'ewamazur123'),
(170, 'Krzysztof', 'Jankowski', 12, 'krzysasdfztof_jankowski1', 'krzysztofjankowski123'),
(171, 'Agnieszka', 'Nowak', 12, 'agniessfadzka_nowak1', 'agnieszkanowak123'),
(172, 'Piotr', 'Kamiński', 12, 'piotrasdf_kamiński1', 'piotrkamiński123'),
(173, 'Tomasz', 'Lewandowski', 12, 'tomaasdfsz_lewandowski1', 'tomaszlewandowski123'),
(174, 'Tomasz', 'Mazur', 12, 'tomaszasdf_mazur1', 'tomaszmazur123'),
(175, 'Ewa', 'Wiśniewski', 12, 'ewa_asdfwiśniewski', 'ewawiśniewski123'),
(176, 'Jan', 'Woźniak', 12, 'jan_woasdfźniak', 'janwoźniak123'),
(177, 'Anna', 'Dąbrowski', 12, 'annasdfa_dąbrowski1', 'annadąbrowski123'),
(178, 'Ewa', 'Mazur', 12, 'ewa_masdfazur1', 'ewamazur123'),
(179, 'Piotr', 'Zieliński', 12, 'piotfasdfr_zieliński', 'piotrzieliński123'),
(180, 'Marek', 'Kowalczyk', 12, 'marekfasdf_kowalczyk', 'marekkowalczyk123'),
(181, 'Magdalena', 'Krawczyk', 13, 'magdfasdfalena_krawczyk', 'magdalenakrawczyk123'),
(182, 'Marek', 'Mazur', 13, 'marek_masdfazur', 'marekmazur123'),
(183, 'Agnieszka', 'Krawczyk', 13, 'agniasdfaedszka_krawczyk', 'agnieszkakrawczyk123'),
(184, 'Ewa', 'Krawczyk', 13, 'ewa_krawcasdfasdazdyk2', 'ewakrawczyk123'),
(185, 'Agnieszka', 'Kozłowski', 13, 'agndieszkadd_kozłowski', 'agnieszkakozłowski123'),
(186, 'Ewa', 'Mazur', 13, 'ewa_mazuasdaasdadsdar2', 'ewamazur123'),
(187, 'Paweł', 'Kowalski', 13, 'paweł_kowalskid', 'pawełkowalski123'),
(188, 'Piotr', 'Woźniak', 13, 'piotr_woźniak2d', 'piotrwoźniak123'),
(189, 'Maria', 'Kozłowski', 13, 'maria_kozłodwski', 'mariakozłowski123'),
(190, 'Maria', 'Wiśniewski', 13, 'maria_wiśdniewski2', 'mariawiśniewski123'),
(191, 'Marek', 'Krawczyk', 13, 'marek_krawdczyk1', 'marekkrawczyk123'),
(192, 'Tomasz', 'Kozłowski', 13, 'tomasz_dkozłowski3', 'tomaszkozłowski123'),
(193, 'Paweł', 'Dąbrowski', 13, 'paweł_dddąbrowski', 'pawełdąbrowski123'),
(194, 'Maria', 'Lewandowski', 13, 'maddria_lewandowski1', 'marialewandowski123'),
(195, 'Ewa', 'Wiśniewski', 13, 'edwa_wiśniewski1', 'ewawiśniewski123'),
(196, 'Ewa', 'Szymański', 14, 'ewa_szydddmański', 'ewaszymański123'),
(197, 'Michał', 'Nowak', 14, 'michał_nddowak1', 'dmichałnowak123'),
(198, 'Maria', 'Zieliński', 14, 'maria_zielińsdki2', 'mariazieliński123'),
(199, 'Krzysztof', 'Kozłowski', 14, 'krzysztodf_kozłowski1', 'krzysztofkozłowski123'),
(200, 'Paweł', 'Lewandowski', 14, 'paweł_lewdandowski', 'pawełlewandowski123'),
(201, 'Piotr', 'Kamiński', 14, 'piotr_kamińdski2', 'piotrkamiński123'),
(202, 'Katarzyna', 'Kowalski', 14, 'katarzdyna_kowalski1', 'katarzynakowalski123'),
(203, 'Zofia', 'Kowalczyk', 14, 'zofia_kowalczyk2', 'zofiakowalczyk123'),
(204, 'Jan', 'Woźniak', 14, 'jan_woźnidddddak1', 'janwoźniak123'),
(205, 'Magdalena', 'Wójcik', 14, 'maddgdalena_wójcik2', 'magdalenawójcik123'),
(206, 'Piotr', 'Woźniak', 14, 'piotdr_woźnidak3', 'piotrwoźniak123'),
(207, 'Piotr', 'Kowalski', 14, 'pidotr_kowalski', 'piotrkowalski123'),
(208, 'Agnieszka', 'Mazur', 14, 'agnieszka_mazudr', 'agnieszkamazur123'),
(209, 'Katarzyna', 'Kamiński', 14, 'katarzyna_kamiński', 'katarzynakamiński123'),
(210, 'Maria', 'Kowalski', 14, 'masria_kowalski1', 'mariakowalski123'),
(211, 'Anna', 'Mazur', 15, 'anna_mazur1', 'annamazur123'),
(212, 'Anna', 'Kowalski', 15, 'anna_kowalskssssi1', 'annakowalski123'),
(213, 'Zofia', 'Wójcik', 15, 'zofia_wsójcik1', 'zofiawójcik123'),
(214, 'Agnieszka', 'Jankowski', 15, 'agnieszka_jankowski', 'agnieszkajankowski123'),
(215, 'Paweł', 'Dąbrowski', 15, 'paweł_dąbrowski1', 'pawełdąbrowski123'),
(216, 'Joanna', 'Mazur', 15, 'joanna_asdfasfmazur1', 'joandasfnamazur123'),
(217, 'Krzysztof', 'Kamiński', 15, 'krzysztodff_kafasmiński1', 'krzysztofkamiński123'),
(218, 'Maria', 'Kozłowski', 15, 'maria_kozłofaasdfsdwski1', 'mariakozłowski123'),
(219, 'Zofia', 'Lewandowski', 15, 'zoasdfasdfia_leafwandowski', 'zofialewandowski123'),
(220, 'Michał', 'Mazur', 15, 'michsdfał_mazur', 'michałmazur123'),
(221, 'Zofia', 'Krawczyk', 15, 'dfzofia_krawczyk', 'zofiakrawczyk123'),
(222, 'Ewa', 'Nowak', 15, 'ewaas_nowak', 'ewanowak123'),
(223, 'Paweł', 'Kowalski', 15, 'pawełfasdsfa_kowalski1', 'pawełkowalski123'),
(224, 'Piotr', 'Jankowski', 15, 'piotr_jsanksowskai', 'piotrjankowski123'),
(225, 'Maria', 'Lewandowski', 15, 'maria_lewsandowski2', 'marialewandowski123'),
(226, 'Anna', 'Wójcik', 16, 'anna_wójcik1', 'sasaannawójcik123'),
(227, 'Agnieszka', 'Lewandowski', 16, 'agnieassszka_lewandowski2', 'agnieszkalewandowski123'),
(228, 'Krzysztof', 'Krawczyk', 16, 'krzysztofs_krawczyk', 'krzysztofkrawczyk123'),
(229, 'Agnieszka', 'Kozłowski', 16, 'agniesazka_kozłowski1', 'agnieszkakozłowski123'),
(230, 'Paweł', 'Lewandowski', 16, 'paweł_laewandowski1', 'pawełlewandowski123'),
(231, 'Michał', 'Szymański', 16, 'michał_aszymański1', 'michałszymański123'),
(232, 'Marek', 'Kowalski', 16, 'marek_koawalski', 'marekkowalski123'),
(233, 'Paweł', 'Nowak', 16, 'paweł_nowaaak', 'pawełnowak123'),
(234, 'Katarzyna', 'Woźniak', 16, 'kaatarzyna_woźniak1', 'katarzynawoźniak123'),
(235, 'Anna', 'Kowalski', 16, 'anna_akowalski2', 'annakowalski123'),
(236, 'Anna', 'Lewandowski', 16, 'aanna_lewandowski', 'annalewandowski123'),
(237, 'Joanna', 'Woźniak', 16, 'joaanna_woźniak', 'joannawoźniak123'),
(238, 'Anna', 'Krawczyk', 16, 'anana_krawczyk', 'annakrawczyk123'),
(239, 'Piotr', 'Wiśniewski', 16, 'piotr_wiśniewski1', 'piotrwiśniewski123'),
(240, 'Marek', 'Woźniak', 16, 'marek_woźniaaak', 'marekwoźniak123'),
(241, 'Tomasz', 'Szymański', 17, 'tomasz_szymaaaański2', 'tomaszszymański123'),
(242, 'Magdalena', 'Lewandowski', 17, 'maaaqaagdalena_lewandowski2', 'magdalenalewandowski123'),
(243, 'Ewa', 'Zieliński', 17, 'ewa_zielaiński1', 'ewazieliński123'),
(244, 'Paweł', 'Kamiński', 17, 'paweł_kaamińsaaki1', 'pawełkamiński123'),
(245, 'Michał', 'Mazur', 17, 'michał_mazuar1a', 'michałmazur123'),
(246, 'Magdalena', 'Mazur', 17, 'magdalenaa_mazur', 'magdalenamazur123'),
(247, 'Katarzyna', 'Lewandowski', 17, 'kaaaatarzyna_lewandowski', 'katarzynalewandowski123'),
(248, 'Marek', 'Kowalczyk', 17, 'marek_kowalaczyk1', 'marekkowalczyk123'),
(249, 'Maria', 'Kowalski', 17, 'maria_koawalski2', 'mariakowalski123'),
(250, 'Ewa', 'Kozłowski', 17, 'ewa_kozaałowski', 'ewakozłowski123'),
(251, 'Maria', 'Lewandowski', 17, 'maaria_lewandowski3', 'marialewandowski123'),
(252, 'Marek', 'Zieliński', 17, 'maraek_zieliński1', 'marekzieliński123'),
(253, 'Jan', 'Kamiński', 17, 'jan_kaamiński', 'jankamiński123'),
(254, 'Zofia', 'Kamiński', 17, 'zoafia_kamiński', 'zofiakamiński123'),
(255, 'Tomasz', 'Kamiński', 17, 'tomasz_kamiński', 'tomaszkamiński123');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `klasy`
--
ALTER TABLE `klasy`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `nauczyciele`
--
ALTER TABLE `nauczyciele`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `id_przedmiotu` (`id_przedmiotu`),
  ADD KEY `id_klasy_wychowawca` (`id_klasy_wychowawca`);

--
-- Indeksy dla tabeli `nowe_konta`
--
ALTER TABLE `nowe_konta`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `oceny`
--
ALTER TABLE `oceny`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ucznia` (`id_ucznia`),
  ADD KEY `id_przedmiotu` (`id_przedmiotu`),
  ADD KEY `id_nauczyciela` (`id_nauczyciela`);

--
-- Indeksy dla tabeli `przedmioty`
--
ALTER TABLE `przedmioty`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `uczniowie`
--
ALTER TABLE `uczniowie`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `id_klasy` (`id_klasy`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `nauczyciele`
--
ALTER TABLE `nauczyciele`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `nowe_konta`
--
ALTER TABLE `nowe_konta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oceny`
--
ALTER TABLE `oceny`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `przedmioty`
--
ALTER TABLE `przedmioty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `uczniowie`
--
ALTER TABLE `uczniowie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=256;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `nauczyciele`
--
ALTER TABLE `nauczyciele`
  ADD CONSTRAINT `nauczyciele_ibfk_1` FOREIGN KEY (`id_przedmiotu`) REFERENCES `przedmioty` (`id`),
  ADD CONSTRAINT `nauczyciele_ibfk_2` FOREIGN KEY (`id_klasy_wychowawca`) REFERENCES `klasy` (`id`);

--
-- Constraints for table `oceny`
--
ALTER TABLE `oceny`
  ADD CONSTRAINT `oceny_ibfk_1` FOREIGN KEY (`id_ucznia`) REFERENCES `uczniowie` (`id`),
  ADD CONSTRAINT `oceny_ibfk_2` FOREIGN KEY (`id_przedmiotu`) REFERENCES `przedmioty` (`id`),
  ADD CONSTRAINT `oceny_ibfk_3` FOREIGN KEY (`id_nauczyciela`) REFERENCES `nauczyciele` (`id`);

--
-- Constraints for table `uczniowie`
--
ALTER TABLE `uczniowie`
  ADD CONSTRAINT `uczniowie_ibfk_1` FOREIGN KEY (`id_klasy`) REFERENCES `klasy` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
