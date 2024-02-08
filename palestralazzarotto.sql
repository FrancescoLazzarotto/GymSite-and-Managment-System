-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Feb 08, 2024 alle 17:11
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
-- Database: `palestralazzarotto`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `assistenza`
--

CREATE TABLE `assistenza` (
  `id_assistenza` int(11) NOT NULL,
  `username_ass` varchar(20) NOT NULL,
  `mail_ass` varchar(30) NOT NULL,
  `assistenza_richiesta` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `assistenza`
--

INSERT INTO `assistenza` (`id_assistenza`, `username_ass`, `mail_ass`, `assistenza_richiesta`) VALUES
(1, 'c', 'checco@gmail.com', 'Nonostante io sia autenticato non mi fa vedere i corsi correttamente.                   \r\n                    '),
(2, 'c', 'sadasd@gmail.com', 'Come funziona per iscriversi ai corsi? Posso iscrivermi la prima volta che vengo in palestra? '),
(3, 'c', 'checco@gmail.com', 'Problema di gestione dati nel mio account, non mi permette di cambiare password o di cambiare username (errore di connessione, ma connessione funzionante)   '),
(4, 'utente1', 'mario@example.com', 'Ho problemi a trovare  corsi sul sito. Quando tento di ricerca di  un corso, ricevo un errore che dice \"Errore di connessione con il server\". Ho già provato a cancellare i cookie del mio browser, ma il problema persiste.'),
(5, 'utente2', 'laura@example.com', 'Nel mio profilo utente, non vedo l\'opzione per cambiare la mia password. Come posso aggiornarla?'),
(6, 'utente3', 'carlo@example.com', 'Mi piacerebbe ricevere informazioni aggiuntive sui corsi di bodybuilding offerti. Vorrei sapere gli orari disponibili e se ci sono istruttori specializzati.'),
(7, 'utente4', 'sofia@example.com', 'Ho dimenticato la mia password per accedere al sito. C\'è un modo per reimpostarla o recuperarla? Ho dovuto fare un altro account.'),
(14, 'c', 'checcolazza@gmail.com', 'Non riesco a cambiare la mia password, la sessione area privata rimane in caricamento e non so come fare.                    \r\n                    ');

-- --------------------------------------------------------

--
-- Struttura della tabella `corso`
--

CREATE TABLE `corso` (
  `id_corso` int(10) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `tipologia` varchar(255) NOT NULL,
  `descrizione` varchar(500) DEFAULT NULL,
  `partecipanti` int(10) NOT NULL,
  `orario` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `corso`
--

INSERT INTO `corso` (`id_corso`, `nome`, `tipologia`, `descrizione`, `partecipanti`, `orario`) VALUES
(19, 'Body Pump', 'Bodybuilding', 'Body Pump è un allenamento con i pesi che ti aiuterà a tonificare e rafforzare tutti i principali gruppi muscolari. Le sessioni includono esercizi con bilancieri e pesi liberi. Unisciti a noi ogni mercoledì e venerdì alle 17:30 per un allenamento completo del corpo.', 15, '2023-10-18 17:30:00'),
(20, 'Yoga Vinyasa', 'Yoga', 'Il corso di Yoga Vinyasa è una pratica dinamica che combina movimenti fluidi con la respirazione consapevole. Questo corso è progettato per migliorare la flessibilità, la forza e la calma mentale. Le lezioni si tengono ogni martedì e giovedì alle 10:30 per aiutarti a trovare l\'equilibrio tra corpo e mente.', 18, '2023-10-17 10:30:00'),
(21, 'Aerobica Ad Alto Impatto', 'Anaerobico', 'L\'aerobica ad alto impatto è un allenamento energico che coinvolge il movimento coordinato e coreografie. Questo corso ti farà sudare e bruciare calorie, migliorando la tua resistenza cardiovascolare. Unisciti a noi ogni martedì e giovedì alle 19:00 per un\'esperienza di fitness coinvolgente.', 25, '2023-10-17 19:00:00'),
(23, 'Pilates Matwork', 'Pilates', 'Il Pilates Matwork è una forma di allenamento che si concentra sulla forza del nucleo e sulla flessibilità. In questo corso, imparerai esercizi che puoi eseguire senza attrezzi speciali. Le lezioni si tengono ogni lunedì e mercoledì alle 16:30 per migliorare la postura e la forza.', 12, '2023-10-16 16:30:00'),
(24, 'Zumba Fitness', 'Dancing Fitness', 'Il corso di Zumba Fitness è un mix di balli e fitness che ti farà divertire mentre bruci calorie. Senti la musica e segui il ritmo. Le sessioni si tengono ogni mercoledì e venerdì alle 20:00 per una sessione di fitness danzante.', 20, '2023-10-18 20:00:00'),
(25, 'CrossFit Challenge', 'CrossFit', 'Unisciti alla sfida del CrossFit per migliorare la tua forza, resistenza e agilità. Questo corso combina esercizi funzionali ad alta intensità per darti una sfida completa. Lezioni disponibili ogni lunedì e gioveddì alle 17:00.', 18, '2023-10-16 17:00:00'),
(26, 'Hatha Yoga', 'Yoga', 'Il corso di Hatha Yoga è una pratica più lenta e riflessiva che si concentra su posture statiche e allungamento. Questo corso è ideale per rilassarsi e trovare la calma interiore. Partecipa ogni martedì e giovedì alle 16:00 per una pratica yoga tranquilla.', 15, '2023-10-17 16:00:00'),
(27, 'Piloxing Fusion', 'Fitness', 'Il Piloxing Fusion combina il Pilates con il pugilato per un allenamento completo del corpo. Rafforza il tuo nucleo e brucia calorie con il ritmo della musica. Unisciti a noi ogni mercoledì e venerdì alle 18:30 per questa esperienza unica.', 20, '2023-10-18 18:30:00'),
(28, 'Spinning Intenso', 'Fitness', 'Il corso di Spinning Intenso è un allenamento cardiovascolare ad alta intensità. Pedala e brucia calorie con musica motivante. Le sessioni si tengono ogni lunedì e gioveddì alle 19:30 per sfidare il tuo cardio.', 22, '2023-10-16 19:30:00'),
(29, 'Ginnastica per Anziani', 'Fitness', 'Questo corso di Ginnastica è progettato per gli anziani e si concentra su movimenti leggeri per mantenere la mobilità. Partecipa ogni martedì e giovedì alle 11:00 per rimanere attivo e in salute.', 10, '2023-10-17 11:00:00'),
(30, 'Danza Hip-Hop', 'Dancing Fitness', 'Il corso di Danza Hip-Hop è un\'esperienza di danza urbana. Impara i movimenti e balla al ritmo della musica hip-hop. Lezioni disponibili ogni mercoledì e venerdì alle 21:00.', 15, '2023-10-18 21:00:00'),
(31, 'Allenamento in Acqua', 'Fitness', 'L\'Allenamento in Acqua è un modo leggero e rinfrescante per fare esercizio. Questo corso è perfetto per l\'estate e si tiene ogni lunedì e gioveddì alle 14:00.', 12, '2023-10-16 14:00:00'),
(32, 'Tai Chi', 'Arti Marziali', 'Il Tai Chi è un\'antica pratica cinese che combina movimenti lenti e respirazione profonda. Questo corso promuove la calma e l\'equilibrio. Unisciti a noi ogni martedì e gioveddì alle 15:30 per migliorare la tua salute mentale e fisica.', 14, '2023-10-17 15:30:00'),
(33, 'Kickboxing Fitness', 'Arti Marziali', 'Il Kickboxing Fitness è un mix di kickboxing e fitness. Brucia calorie e sfoga lo stress mentre impari le tecniche di combattimento. Lezioni disponibili ogni mercoledì e venerdì alle 19:30.', 20, '2023-10-18 19:30:00'),
(51, 'Funzionale Over 60 ', 'Funzionale', 'Allenamento completo, riabilitazione e movimento ', 21, '2024-02-05 12:30:00'),
(52, 'Funzionale Over 80', 'Funzionale', 'Allenamento completo, riabilitazione e movimento ', 12, '2024-04-02 09:30:00'),
(56, 'Karate Do', 'Arti Marziali', 'Arte Marziale Giapponese, combattimento di difesa.', 10, '2024-04-02 09:30:00'),
(57, 'Funzionale Over 90 ', 'Funzionale', 'Allenamento completo, riabilitazione e movimento ', 24, '2024-03-06 10:30:00');

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id_utente` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(30) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `cognome` varchar(30) NOT NULL,
  `mail` varchar(40) NOT NULL,
  `data_nascita` date NOT NULL,
  `amministratore` tinyint(1) NOT NULL COMMENT '1 = admin, 0 = no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id_utente`, `username`, `password`, `nome`, `cognome`, `mail`, `data_nascita`, `amministratore`) VALUES
(13, 'Amministratore', 'Amministratore1', 'Francesco', 'Lazzarotto', 'admin@gmail.com', '2023-10-11', 1),
(17, 'c', 'c', 'c', 'c', 'checco@gmail.com', '2023-10-19', 0),
(19, 'utente1', 'password1', 'Mario', 'Rossi', 'mario@example.com', '1990-01-01', 0),
(20, 'utente2', 'password2', 'Laura', 'Bianchi', 'laura@example.com', '1985-03-15', 0),
(21, 'utente3', 'password3', 'Carlo', 'Verdi', 'carlo@example.com', '1988-07-22', 0),
(22, 'utente4', 'password4', 'Sofia', 'Neri', 'sofia@example.com', '1995-11-10', 0),
(23, 'utente5', 'password5', 'Giulio', 'Gialli', 'giulio@example.com', '1980-05-05', 0),
(24, 'FraLazza', 'basket01', 'Francesco ', 'Lazzarotto', 'fralazza@gmail.com', '1998-05-23', 0),
(25, 'FrancescoLazzarotto', 'Basket01', 'Francesco', 'Lazzarotto', 'france@gmail.com', '2001-05-15', 0);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `assistenza`
--
ALTER TABLE `assistenza`
  ADD PRIMARY KEY (`id_assistenza`);

--
-- Indici per le tabelle `corso`
--
ALTER TABLE `corso`
  ADD PRIMARY KEY (`id_corso`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id_utente`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `assistenza`
--
ALTER TABLE `assistenza`
  MODIFY `id_assistenza` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT per la tabella `corso`
--
ALTER TABLE `corso`
  MODIFY `id_corso` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id_utente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
