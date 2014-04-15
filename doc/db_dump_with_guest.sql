-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 15. Apr 2014 um 19:37
-- Server Version: 5.6.16
-- PHP-Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `eaproject`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `departments`
--

CREATE TABLE IF NOT EXISTS `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `min_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(40) NOT NULL,
  `comment` text NOT NULL,
  `begin` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf16 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `event_departments`
--

CREATE TABLE IF NOT EXISTS `event_departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `department_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `services`
--

CREATE TABLE IF NOT EXISTS `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `location` varchar(40) NOT NULL,
  `standby` bit(1) NOT NULL,
  `erreichbar` bit(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `service_event`
--

CREATE TABLE IF NOT EXISTS `service_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` int(1) NOT NULL,
  `company_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(55) DEFAULT NULL,
  `first_name` varchar(55) DEFAULT NULL,
  `username` varchar(90) DEFAULT NULL COMMENT 'username as email',
  `password` varchar(256) DEFAULT NULL COMMENT 'SHA512 http://www.hashgenerator.de',
  `email` varchar(90) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `salt` varchar(16) DEFAULT NULL COMMENT 'SaltHash fürs Passwort',
  `no` varchar(30) DEFAULT NULL COMMENT 'Interne Nummer des Benutzers',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf16 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `role`, `company_name`, `last_name`, `first_name`, `username`, `password`, `email`, `telephone`, `salt`, `no`) VALUES
(1, 2, 'Tanyll AG', 'Treptow', 'Daniel', 'tanyll', 'a870b5b0f29e558592a4162ac17cb2c9133f810c9b1f63bf0f4e8db26cb124b96bd97615b474fe502680b3fc76ee6d658205021552953dd34ac2056e1d9cefdb', 'emoafrob@googlemail.com', '017623950824', '', 'ASDF0070'),
(2, 2, 'Gast AG', 'Mustermann', 'Peter', 'gast', '1faa951fd653e0710706fdfc1d652e4732d06e8d458326bb1ed7d3b34da51dc5e6f89a08183c063bc72e6300883903fd6f74fd52cc2b29b1a5efd8442c039601', 'gast@gast.de', '01234 5678910', NULL, 'MFG0815');
(3, 2, 'Jonas AG', 'Arndt', 'Jonas', 'jonas', '1faa951fd653e0710706fdfc1d652e4732d06e8d458326bb1ed7d3b34da51dc5e6f89a08183c063bc72e6300883903fd6f74fd52cc2b29b1a5efd8442c039601', 'jonas@internet.de', '01234 5678910', NULL, 'ASDF0071');
(4, 2, 'Emre AG', 'Konar', 'Emre', 'emre', '1faa951fd653e0710706fdfc1d652e4732d06e8d458326bb1ed7d3b34da51dc5e6f89a08183c063bc72e6300883903fd6f74fd52cc2b29b1a5efd8442c039601', 'emre@internet.de', '01234 5678910', NULL, 'ASDF0072');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_service`
--

CREATE TABLE IF NOT EXISTS `user_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `begin` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
