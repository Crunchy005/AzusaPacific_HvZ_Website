
-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- Host: 68.178.143.144
-- Generation Time: Oct 30, 2014 at 03:30 PM
-- Server version: 5.5.33
-- PHP Version: 5.1.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `APUHvZ00219`
--

-- --------------------------------------------------------

--
-- Table structure for table `Death_timer`
--

CREATE TABLE `Death_timer` (
  `pid` int(6) NOT NULL,
  `last_feed` int(10) DEFAULT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Death_timer`
--


-- --------------------------------------------------------

--
-- Table structure for table `Game`
--

CREATE TABLE `Game` (
  `game_id` int(1) NOT NULL,
  `registration` varchar(5) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `death_time_length` int(2) DEFAULT NULL,
  PRIMARY KEY (`game_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Game`
--

INSERT INTO `Game` VALUES(1, 'OPEN', '2014-07-02', NULL, 48);

-- --------------------------------------------------------

--
-- Table structure for table `map`
--

CREATE TABLE `map` (
  `location` text,
  `id` int(5) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `map`
--

INSERT INTO `map` VALUES('(34.130713, -117.889227)', 8);
INSERT INTO `map` VALUES('(34.13494044729937, -117.89458337140275)', 18);
INSERT INTO `map` VALUES('(34.13549635979703, -117.89429905724717)', 19);
INSERT INTO `map` VALUES('(34.130215668248916, -117.88802537036133)', 17);
INSERT INTO `map` VALUES('(34.13525989747461, -117.89497765612794)', 15);
INSERT INTO `map` VALUES('(34.13518885408271, -117.8938189418335)', 20);
INSERT INTO `map` VALUES('(34.12989595343519, -117.88879784655762)', 21);
INSERT INTO `map` VALUES('(34.129811852047425, -117.88904192757798)', 22);

-- --------------------------------------------------------

--
-- Table structure for table `Mission`
--

CREATE TABLE `Mission` (
  `mission_id` int(2) NOT NULL,
  `mission_name` varchar(30) NOT NULL,
  `mission_email` text,
  PRIMARY KEY (`mission_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Mission`
--

INSERT INTO `Mission` VALUES(1, 'mission 1', 'Testing mission email text');
INSERT INTO `Mission` VALUES(2, 'mission 2', 'Testing mission 2');
INSERT INTO `Mission` VALUES(3, 'mission 3', 'testing mission 3 now');
INSERT INTO `Mission` VALUES(4, 'mission 4', 'This is for mission 4, need to make a create mission button as well.');

-- --------------------------------------------------------

--
-- Table structure for table `Mission_att`
--

CREATE TABLE `Mission_att` (
  `mission_id` int(11) NOT NULL,
  `pid` char(6) NOT NULL DEFAULT '',
  PRIMARY KEY (`mission_id`,`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Mission_att`
--


-- --------------------------------------------------------

--
-- Table structure for table `Player`
--

CREATE TABLE `Player` (
  `f_name` varchar(30) NOT NULL,
  `l_name` varchar(30) NOT NULL,
  `pid` char(6) NOT NULL,
  `p_email` varchar(40) NOT NULL,
  `p_password` char(60) NOT NULL DEFAULT '',
  `living_area` varchar(25) DEFAULT NULL,
  `alpha` tinyint(1) DEFAULT NULL,
  `picture_location` varchar(200) NOT NULL,
  `kill_code` varchar(6) DEFAULT NULL,
  `status` int(1) unsigned NOT NULL,
  `paid` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `admin` tinyint(1) DEFAULT NULL,
  `vaccinated` int(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Player`
--

INSERT INTO `Player` VALUES('APU', 'HVZ', '1', '1234@apu.edu', '81dc9bdb52d04dc20036dbd8313ed055', 'Cougar Dome', 0, 'player_pics/APUHVZ.jpg', '111111', 3, 1, 1, 1, 0);
INSERT INTO `Player` VALUES('Brandon', 'Corn', '386661', 'bcorn11@apu.edu', 'c29db653230d6eeadf57b53296d0cbd4', 'University Village', 1, 'player_pics/BrandonCorn.jpg', 'ARMCCW', 5, 0, 1, 1, 0);
INSERT INTO `Player` VALUES('Test', 'Tests', '130028', '12345@apu.edu', '81dc9bdb52d04dc20036dbd8313ed055', 'University Village', 1, 'player_pics/TestTests.jpg', 'CZCCAA', 5, 0, 0, 0, 0);
INSERT INTO `Player` VALUES('David', 'Lee', '960726', 'eunjiklee11@apu.edu', '17e79cd498b420fd80d5a4ef1ad674e8', 'University Village', 0, 'player_pics/DavidLee.jpg', 'FV3MWP', 1, 0, 0, 0, 0);
INSERT INTO `Player` VALUES('Dream', 'Crusher', '620009', 'bkinsey12@apu.edu', '587a76df359fe6dcf26f6b9458d3deb3', 'University Park', 1, 'player_pics/DreamCrusher.png', 'EUU7TB', 5, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Tagged`
--

CREATE TABLE `Tagged` (
  `pid` char(6) NOT NULL,
  `taggedby_pid` char(6) DEFAULT NULL,
  `tagged_date` datetime DEFAULT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Tagged`
--

INSERT INTO `Tagged` VALUES('130028', '386661', '2014-10-13 11:10:08');

-- --------------------------------------------------------

--
-- Table structure for table `Vaccination`
--

CREATE TABLE `Vaccination` (
  `pid` char(6) NOT NULL DEFAULT '',
  `vaccination_date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`pid`,`vaccination_date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Vaccination`
--

