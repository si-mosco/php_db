-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Feb 16, 2024 alle 11:23
-- Versione del server: 10.4.28-MariaDB
-- Versione PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ordini`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `clienti`
--

CREATE TABLE `clienti` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cognome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `id_luogo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `clienti`
--

INSERT INTO `clienti` (`id`, `nome`, `cognome`, `email`, `id_luogo`) VALUES
(1, 'Simone', 'Mosconi', 'simonemosconi@gmail.com', 1),
(2, 'Marco', 'Borelli', 'marcoborelli@gmail.com', 2),
(3, 'Nicola', 'Ghilardi', 'nicolaghilardi@gmail.com', 3),
(4, 'Pietro', 'Patelli', 'pietropatelli@gmail.com', 4),
(5, 'Andrea', 'Crotti', 'andreacrotti@gmail.com', 5),
(6, 'Jacopo', 'Ferrari', 'jacopoferrari@gmail.com', 6),
(7, 'Matteo', 'Verzeri', 'matteoverzeri@gmail.com', 7),
(8, 'Alessandro', 'Colombi', 'alessandrocolombi@gmail.com', 8),
(9, 'Tommaso', 'Todeschini', 'tommasotodeschini@gmail.com', 9),
(10, 'Tomas', 'Cutinella', 'tomascutinella@gmail.com', 10);

-- --------------------------------------------------------

--
-- Struttura della tabella `credenziali`
--

CREATE TABLE `credenziali` (
  `username` varchar(30) NOT NULL,
  `password` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `credenziali`
--

INSERT INTO `credenziali` (`username`, `password`) VALUES
('admin', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918'),
('Simone', '19a0098e641d4bee278bb5d470d06679ffc5fdc818c3a1c52bfb7f8cde3752d3');

-- --------------------------------------------------------

--
-- Struttura della tabella `luoghi_consegna`
--

CREATE TABLE `luoghi_consegna` (
  `id` int(11) NOT NULL,
  `citta` varchar(30) NOT NULL,
  `nazione` varchar(30) NOT NULL,
  `cap` varchar(30) NOT NULL,
  `via` varchar(50) NOT NULL,
  `num_civico` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `luoghi_consegna`
--

INSERT INTO `luoghi_consegna` (`id`, `citta`, `nazione`, `cap`, `via`, `num_civico`) VALUES
(1, 'Roma', 'Italia', 186, 'Via dei Fori Imperiali', 123),
(2, 'Sydney', 'Australia', 2000, 'George Street', 456),
(3, 'New York City', 'Stati Uniti', 10001, 'Broadway', 789),
(4, 'Tokyo', 'Giappone', 1508010, 'Shibuya Crossing', 101),
(5, 'Parigi', 'Francia', 75008, 'Avenue des Champs-Élysées', 234),
(6, 'Città del Messico', 'Messico', 6500, 'Paseo de la Reforma', 567),
(7, 'Londra', 'Regno Unito', 310143, 'Oxford Street', 890),
(8, 'Berlino', 'Germania', 10117, 'Unter den Linden', 1234),
(9, 'Buenos Aires', 'Argentina', 31073448, 'Avenida 9 de Julio', 9012),
(10, 'Mosca', 'Russia', 125009, 'Tverskaya Street', 5678);

-- --------------------------------------------------------

--
-- Struttura della tabella `oggetti`
--

CREATE TABLE `oggetti` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `costo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dump dei dati per la tabella `oggetti`
--

INSERT INTO `oggetti` (`id`, `nome`, `costo`) VALUES
(1, 'Cover', 10),
(2, 'Carica Batteria', 20),
(3, 'Cuffie', 30),
(4, 'Maglietta', 15),
(5, 'Calzini', 3),
(6, 'Berretta', 10),
(7, 'SetTazze', 15);

-- --------------------------------------------------------

--
-- Struttura della tabella `ordini`
--

CREATE TABLE `ordini` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `data_ordine` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `ordini`
--

INSERT INTO `ordini` (`id`, `cliente_id`, `data_ordine`) VALUES
(1, 1, '2024-01-01'),
(2, 2, '2024-01-02'),
(3, 3, '2024-01-03'),
(4, 4, '2024-01-06'),
(5, 5, '2024-01-02'),
(6, 6, '2024-01-03'),
(7, 7, '2024-01-07'),
(8, 8, '2024-01-01'),
(9, 9, '2024-01-03'),
(10, 10, '2024-01-08'),
(11, 1, '2024-01-02'),
(12, 2, '2024-01-07'),
(13, 3, '2024-01-04'),
(14, 4, '2024-01-01'),
(15, 5, '2024-01-03'),
(16, 6, '2024-01-04'),
(17, 7, '2024-01-03'),
(18, 8, '2024-01-03'),
(19, 9, '2024-01-09'),
(20, 10, '2024-01-07');

-- --------------------------------------------------------

--
-- Struttura della tabella `ordini_oggetti`
--

CREATE TABLE `ordini_oggetti` (
  `id` int(11) NOT NULL,
  `id_ordini` int(11) DEFAULT NULL,
  `id_oggetti` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `ordini_oggetti`
--

INSERT INTO `ordini_oggetti` (`id`, `id_ordini`, `id_oggetti`) VALUES
(1, 1, 1),
(2, 1, 7),
(3, 2, 2),
(4, 2, 3),
(5, 3, 3),
(6, 3, 4),
(7, 4, 4),
(8, 4, 5),
(9, 5, 5),
(10, 5, 6),
(11, 6, 6),
(12, 6, 7),
(13, 7, 7),
(14, 7, 1),
(15, 8, 1),
(16, 8, 2),
(17, 9, 2),
(18, 9, 3),
(19, 10, 3),
(20, 10, 4),
(21, 11, 4),
(22, 12, 5),
(23, 13, 6),
(24, 14, 7),
(25, 15, 1),
(26, 16, 2),
(27, 17, 3),
(28, 18, 4),
(29, 19, 5),
(30, 20, 6);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `clienti`
--
ALTER TABLE `clienti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pippococa` (`id_luogo`);

--
-- Indici per le tabelle `luoghi_consegna`
--
ALTER TABLE `luoghi_consegna`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `oggetti`
--
ALTER TABLE `oggetti`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `ordini`
--
ALTER TABLE `ordini`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente` (`cliente_id`);

--
-- Indici per le tabelle `ordini_oggetti`
--
ALTER TABLE `ordini_oggetti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rif_oggetti1` (`id_oggetti`),
  ADD KEY `rif_ordini` (`id_ordini`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `clienti`
--
ALTER TABLE `clienti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT per la tabella `luoghi_consegna`
--
ALTER TABLE `luoghi_consegna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `oggetti`
--
ALTER TABLE `oggetti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `ordini`
--
ALTER TABLE `ordini`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT per la tabella `ordini_oggetti`
--
ALTER TABLE `ordini_oggetti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `clienti`
--
ALTER TABLE `clienti`
  ADD CONSTRAINT `pippococa` FOREIGN KEY (`id_luogo`) REFERENCES `luoghi_consegna` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limiti per la tabella `ordini`
--
ALTER TABLE `ordini`
  ADD CONSTRAINT `clientiporca` FOREIGN KEY (`cliente_id`) REFERENCES `clienti` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limiti per la tabella `ordini_oggetti`
--
ALTER TABLE `ordini_oggetti`
  ADD CONSTRAINT `rif_oggetti1` FOREIGN KEY (`id_oggetti`) REFERENCES `oggetti` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `rif_ordini` FOREIGN KEY (`id_ordini`) REFERENCES `ordini` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
