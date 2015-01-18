-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 20, 2012 at 07:49 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mailbox`
--
CREATE DATABASE `mailbox` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `mailbox`;

-- --------------------------------------------------------

--
-- Table structure for table `tblattachements`
--

CREATE TABLE IF NOT EXISTS `tblattachements` (
  `attachID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `location` text NOT NULL,
  `filename` varchar(205) NOT NULL,
  `mailID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`attachID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tblattachements`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblmails`
--

CREATE TABLE IF NOT EXISTS `tblmails` (
  `mailID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sender` varchar(45) NOT NULL,
  `subject` text NOT NULL,
  `msg` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`mailID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tblmails`
--


-- --------------------------------------------------------

--
-- Table structure for table `tblsettings`
--

CREATE TABLE IF NOT EXISTS `tblsettings` (
  `setID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mailserver` varchar(50) NOT NULL,
  `port` int(10) unsigned NOT NULL,
  `server_type` varchar(45) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`setID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tblsettings`
--

