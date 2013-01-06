SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) DEFAULT NULL,
  `url` varchar(300) DEFAULT NULL,
  `elapsedtime` int(11) DEFAULT NULL,
  `resolution` varchar(9) DEFAULT NULL,
  `browser` varchar(45) DEFAULT NULL,
  `browserVersion` varchar(45) DEFAULT NULL,
  `OS` varchar(45) DEFAULT NULL,
  `type` int(2) DEFAULT NULL,
  `state` varchar(20) DEFAULT NULL,
  `time` int(10) DEFAULT NULL,
  `text` varchar(500) DEFAULT NULL,
  `line` int(11) DEFAULT NULL,
  `file` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

CREATE TABLE IF NOT EXISTS `performance` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `time` int(11) DEFAULT NULL,
  `url` varchar(300) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `resolution` varchar(9) DEFAULT NULL,
  `browser` varchar(45) DEFAULT NULL,
  `browserVersion` varchar(45) DEFAULT NULL,
  `OS` varchar(45) DEFAULT NULL,
  `redirectCount` int(2) DEFAULT NULL,
  `redirectTime` int(9) DEFAULT NULL,
  `requestTime` int(9) DEFAULT NULL,
  `responseTime` int(9) DEFAULT NULL,
  `domProcessingTime` int(9) DEFAULT NULL,
  `domLoadingTime` int(9) DEFAULT NULL,
  `loadEventTime` int(9) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=57 ;

CREATE TABLE IF NOT EXISTS `types` (
  `type` int(11) NOT NULL,
  `typename` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `username` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `passwordHash` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
