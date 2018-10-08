-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Lug 12, 2018 alle 14:17
-- Versione del server: 10.1.32-MariaDB
-- Versione PHP: 7.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `game`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `blocks`
--

DROP TABLE IF EXISTS `blocks`;
CREATE TABLE `blocks` (
  `user` varchar(80) NOT NULL,
  `rowstart` int(11) NOT NULL,
  `colstart` int(11) NOT NULL,
  `rowend` int(11) NOT NULL,
  `colend` int(11) NOT NULL,
  `number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dump dei dati per la tabella `blocks`
--

INSERT INTO `blocks` (`user`, `rowstart`, `colstart`, `rowend`, `colend`, `number`) VALUES
('u1@p.it', 3, 0, 6, 0, 0),
('u2@p.it', 1, 3, 1, 6, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `username` varchar(80) NOT NULL,
  `password` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`username`, `password`) VALUES
('u1@p.it', '$2y$10$RiavQeOeEQhH2i7vGnBtwuyg1Q2i/wnKN5evdUnlO4hxCjqEJctYy'),
('u2@p.it', '$2y$10$Ppm6qS1xxHmYpmX1IrLJGudaO6wiDAK0CGTfywf74X16r4iVIXnP2');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `blocks`
--
ALTER TABLE `blocks`
  ADD PRIMARY KEY (`user`,`rowstart`,`colstart`,`rowend`,`colend`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
