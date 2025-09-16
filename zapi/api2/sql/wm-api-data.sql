-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: com-linweb464.srv.combell-ops.net:3306
-- Gegenereerd op: 16 sep 2024 om 21:29
-- Serverversie: 5.7.44-49-log
-- PHP-versie: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ID328267_stevenop`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `categorieen`
--

CREATE TABLE `categorieen` (
  `CT_ID` int(11) NOT NULL,
  `CT_OM` varchar(20) CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `categorieen`
--

INSERT INTO `categorieen` (`CT_ID`, `CT_OM`) VALUES
(1, 'Fruit'),
(2, 'Groenten'),
(3, 'Vervoermiddel'),
(4, 'Electronica'),
(5, 'Boeken');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `gebruikers`
--

CREATE TABLE `gebruikers` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(20) NOT NULL,
  `PW` varchar(20) NOT NULL COMMENT 'Gebruik geen VARCHAR om paswoorden bij te houden in cleartext! '
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `gebruikers`
--

INSERT INTO `gebruikers` (`ID`, `NAME`, `PW`) VALUES
(1, 'test', 'test'),
(2, 'ik', 'ik'),
(3, '2TI', 'FTW');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `producten`
--

CREATE TABLE `producten` (
  `PR_ID` int(11) NOT NULL,
  `PR_CT_ID` int(11) NOT NULL,
  `PR_naam` varchar(50) CHARACTER SET utf8 NOT NULL,
  `PR_prijs` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `producten`
--

INSERT INTO `producten` (`PR_ID`, `PR_CT_ID`, `PR_naam`, `PR_prijs`) VALUES
(112, 1, 'Appel', 10),
(113, 2, 'Granaatappel', 20),
(114, 2, 'Boemkool', 15),
(133, 1, 'Banaanaanwagen', 10),
(131, 2, 'Andrijvie', 1.5),
(123, 1, 'Bananasplit', 5),
(122, 1, 'Hanananas', 5),
(121, 1, 'Zuurpruim', 10000),
(124, 2, 'Ui', 2),
(130, 1, 'Skiwi', 2),
(126, 1, 'Spruitjes', 5),
(127, 1, 'GestoofdePeren', 5),
(128, 1, 'Gebakken peren', 10),
(135, 2, 'Spinazie', 1.23),
(136, 2, 'Spruiten', 3),
(137, 2, 'Schorseneren', 5),
(145, 1, 'Pampelmous', 2),
(214, 1, 'Boterbloem', 3),
(215, 1, 'Oliebloem', 4),
(216, 1, 'Boterhammenbloem', 4),
(217, 1, 'Cementbloem', 2),
(231, 5, 'Appelflap', 200);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `categorieen`
--
ALTER TABLE `categorieen`
  ADD PRIMARY KEY (`CT_ID`);

--
-- Indexen voor tabel `gebruikers`
--
ALTER TABLE `gebruikers`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `NAME` (`NAME`);

--
-- Indexen voor tabel `producten`
--
ALTER TABLE `producten`
  ADD PRIMARY KEY (`PR_ID`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `categorieen`
--
ALTER TABLE `categorieen`
  MODIFY `CT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT voor een tabel `gebruikers`
--
ALTER TABLE `gebruikers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT voor een tabel `producten`
--
ALTER TABLE `producten`
  MODIFY `PR_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
