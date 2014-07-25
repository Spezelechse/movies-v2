-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 22. Jul 2014 um 19:05
-- Server Version: 5.5.31-1~dotdeb.0
-- PHP-Version: 5.4.24-1~alfa.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `actor`
--

CREATE TABLE IF NOT EXISTS `actor` (
  `aid` int(3) NOT NULL AUTO_INCREMENT,
  `act_name` text NOT NULL,
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3384 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `blacklist`
--

CREATE TABLE IF NOT EXISTS `blacklist` (
  `blid` int(3) NOT NULL AUTO_INCREMENT,
  `starttime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(15) NOT NULL,
  `try` varchar(100) NOT NULL,
  PRIMARY KEY (`blid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `borrower`
--

CREATE TABLE IF NOT EXISTS `borrower` (
  `bid` int(3) NOT NULL AUTO_INCREMENT,
  `bor_name` varchar(30) NOT NULL,
  PRIMARY KEY (`bid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `director`
--

CREATE TABLE IF NOT EXISTS `director` (
  `did` int(3) NOT NULL AUTO_INCREMENT,
  `dir_name` text NOT NULL,
  PRIMARY KEY (`did`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=335 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dvds`
--

CREATE TABLE IF NOT EXISTS `dvds` (
  `mid` int(4) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `original_or_copy` tinyint(1) NOT NULL,
  `genre` text NOT NULL,
  `publisher` text NOT NULL,
  `director` text NOT NULL,
  `length` int(4) NOT NULL,
  `fsk` int(2) NOT NULL,
  `premiere` date NOT NULL,
  `num_disk` int(2) NOT NULL,
  `content` text NOT NULL,
  `cover` text NOT NULL,
  `actors` text NOT NULL,
  `roles` text NOT NULL,
  `borrowed` tinyint(1) NOT NULL,
  `borrower` int(3) NOT NULL,
  `owner` int(3) NOT NULL,
  `type` int(1) NOT NULL,
  `dvd_or_bluray` tinyint(1) NOT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=588 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `genre`
--

CREATE TABLE IF NOT EXISTS `genre` (
  `gid` int(3) NOT NULL AUTO_INCREMENT,
  `genre` text NOT NULL,
  PRIMARY KEY (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `owner`
--

CREATE TABLE IF NOT EXISTS `owner` (
  `oid` int(3) NOT NULL AUTO_INCREMENT,
  `owner` text NOT NULL,
  PRIMARY KEY (`oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `publisher`
--

CREATE TABLE IF NOT EXISTS `publisher` (
  `pid` int(3) NOT NULL AUTO_INCREMENT,
  `pub_name` text NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=73 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `type`
--

CREATE TABLE IF NOT EXISTS `type` (
  `tid` int(1) NOT NULL AUTO_INCREMENT,
  `type` varchar(30) NOT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `username` varchar(20) NOT NULL,
  `pword` varchar(32) NOT NULL,
  `uid` int(2) NOT NULL AUTO_INCREMENT,
  `surname` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `rights` bit(7) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`username`, `pword`, `uid`, `surname`, `name`, `rights`) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3', 1, 'Mustermann', 'Max', b'1111111'),
