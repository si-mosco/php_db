-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Feb 14, 2024 alle 10:48
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
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `clienti`
--

INSERT INTO `clienti` (`id`, `nome`, `cognome`, `email`) VALUES
(1, 'Simone', 'Mosconi', 'simonemosconi@gmail.com'),
(2, 'Marco', 'Borelli', 'marcoborelli@gmail.com'),
(3, 'Nicola', 'Ghilardi', 'nicolaghilardi@gmail.com'),
(4, 'Pietro', 'Patelli', 'pietropatelli@gmail.com'),
(5, 'Andrea', 'Crotti', 'andreacrotti@gmail.com'),
(6, 'Jacopo', 'Ferrari', 'jacopoferrari@gmail.com'),
(7, 'Matteo', 'Verzeri', 'matteoverzeri@gmail.com'),
(8, 'Alessandro', 'Colombi', 'alessandrocolombi@gmail.com'),
(9, 'Tommaso', 'Todeschini', 'tommasotodeschini@gmail.com'),
(10, 'Tomas', 'Cutinella', 'tomascutinella@gmail.com');

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
  `cliente_id` int(11) NOT NULL,
  `data_ordine` date NOT NULL,
  `oggetto_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `ordini`
--

INSERT INTO `ordini` (`id`, `cliente_id`, `data_ordine`, `oggetto_id`) VALUES
(1, 1, '2024-01-01', 1),
(2, 2, '2024-01-02', 2),
(3, 3, '2024-01-03', 3),
(4, 4, '2024-01-06', 3),
(5, 5, '2024-01-02', 2),
(6, 6, '2024-01-03', 1),
(7, 7, '2024-01-07', 3),
(8, 8, '2024-01-01', 1),
(9, 9, '2024-01-03', 2),
(10, 10, '2024-01-08', 3),
(11, 1, '2024-01-02', 2),
(12, 2, '2024-01-07', 1),
(13, 3, '2024-01-04', 3),
(14, 4, '2024-01-01', 2),
(15, 5, '2024-01-03', 2),
(16, 6, '2024-01-04', 1),
(17, 7, '2024-01-03', 3),
(18, 8, '2024-01-03', 1),
(19, 9, '2024-01-09', 2),
(20, 10, '2024-01-07', 3),
(21, 5, '2024-01-10', 5);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `clienti`
--
ALTER TABLE `clienti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

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
  ADD KEY `cliente` (`cliente_id`),
  ADD KEY `oggetto` (`oggetto_id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `clienti`
--
ALTER TABLE `clienti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `ordini`
--
ALTER TABLE `ordini`
  ADD CONSTRAINT `cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clienti` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `oggetto` FOREIGN KEY (`oggetto_id`) REFERENCES `oggetti` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
