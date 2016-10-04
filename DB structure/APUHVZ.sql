-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 05, 2016 at 01:28 PM
-- Server version: 5.5.47-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `APUHVZ`
--
CREATE DATABASE IF NOT EXISTS `APUHVZ` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `APUHVZ`;

-- --------------------------------------------------------

--
-- Table structure for table `Death_timer`
--

DROP TABLE IF EXISTS `Death_timer`;
CREATE TABLE IF NOT EXISTS `Death_timer` (
  `pid` int(6) NOT NULL,
  `last_feed` int(10) DEFAULT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Game`
--

DROP TABLE IF EXISTS `Game`;
CREATE TABLE IF NOT EXISTS `Game` (
  `game_id` int(1) NOT NULL,
  `registration` varchar(5) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `death_time_length` int(2) DEFAULT NULL,
  `pause_time` int(10) DEFAULT NULL,
  `shares` int(2) DEFAULT NULL,
  PRIMARY KEY (`game_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `map`
--

DROP TABLE IF EXISTS `map`;
CREATE TABLE IF NOT EXISTS `map` (
  `location` text,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=92 ;

-- --------------------------------------------------------

--
-- Table structure for table `Mission`
--

DROP TABLE IF EXISTS `Mission`;
CREATE TABLE IF NOT EXISTS `Mission` (
  `mission_id` int(11) NOT NULL AUTO_INCREMENT,
  `mission_name` varchar(30) NOT NULL,
  `mission_email` text,
  PRIMARY KEY (`mission_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Mission_att`
--

DROP TABLE IF EXISTS `Mission_att`;
CREATE TABLE IF NOT EXISTS `Mission_att` (
  `mission_id` int(11) NOT NULL,
  `pid` char(6) NOT NULL DEFAULT '',
  PRIMARY KEY (`mission_id`,`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Player`
--

DROP TABLE IF EXISTS `Player`;
CREATE TABLE IF NOT EXISTS `Player` (
  `f_name` varchar(30) NOT NULL,
  `l_name` varchar(30) NOT NULL,
  `pid` char(6) NOT NULL,
  `p_email` varchar(40) NOT NULL,
  `p_password` varchar(255) NOT NULL DEFAULT '',
  `living_area` varchar(25) DEFAULT NULL,
  `alpha` tinyint(1) DEFAULT NULL,
  `picture_location` varchar(200) NOT NULL,
  `kill_code` varchar(6) DEFAULT NULL,
  `status` int(1) unsigned NOT NULL,
  `paid` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `admin` tinyint(1) DEFAULT NULL,
  `vaccinated` int(1) unsigned DEFAULT NULL,
  `team` int(1) NOT NULL,
  PRIMARY KEY (`pid`),
  KEY `team` (`team`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Tagged`
--

DROP TABLE IF EXISTS `Tagged`;
CREATE TABLE IF NOT EXISTS `Tagged` (
  `pid` char(6) NOT NULL,
  `taggedby_pid` char(6) DEFAULT NULL,
  `tagged_date` varchar(30) DEFAULT '',
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Team_Name`
--

DROP TABLE IF EXISTS `Team_Name`;
CREATE TABLE IF NOT EXISTS `Team_Name` (
  `team_id` int(1) NOT NULL,
  `team_name` text,
  PRIMARY KEY (`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Vaccination`
--

DROP TABLE IF EXISTS `Vaccination`;
CREATE TABLE IF NOT EXISTS `Vaccination` (
  `pid` char(6) NOT NULL DEFAULT '',
  `vaccination_date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`pid`,`vaccination_date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
