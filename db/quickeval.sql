-- phpMyAdmin SQL Dump
-- version 3.1.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 01, 2009 at 11:46 AM
-- Server version: 5.0.77
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `quickeval`
--

-- --------------------------------------------------------

--
-- Table structure for table `Comments`
--

CREATE TABLE IF NOT EXISTS `Comments` (
  `id` int(11) NOT NULL auto_increment,
  `response_id` int(11) NOT NULL,
  `detail` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=656 ;

-- --------------------------------------------------------

--
-- Table structure for table `Courses`
--

CREATE TABLE IF NOT EXISTS `Courses` (
  `id` int(11) NOT NULL auto_increment,
  `owner_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1123 ;

-- --------------------------------------------------------

--
-- Table structure for table `ProjectStudents`
--

CREATE TABLE IF NOT EXISTS `ProjectStudents` (
  `id` int(11) NOT NULL auto_increment,
  `projectteam_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=529 ;

-- --------------------------------------------------------

--
-- Table structure for table `ProjectTeams`
--

CREATE TABLE IF NOT EXISTS `ProjectTeams` (
  `id` int(11) NOT NULL auto_increment,
  `owner_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=887 ;

-- --------------------------------------------------------

--
-- Table structure for table `Questions`
--

CREATE TABLE IF NOT EXISTS `Questions` (
  `id` int(11) NOT NULL auto_increment,
  `owner_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `weight` float NOT NULL,
  `data` text NOT NULL,
  `list_order` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=460 ;

-- --------------------------------------------------------

--
-- Table structure for table `Responses`
--

CREATE TABLE IF NOT EXISTS `Responses` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `survey_instance_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `user_for` int(11) NOT NULL,
  `value` text NOT NULL,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1177 ;

-- --------------------------------------------------------

--
-- Table structure for table `SurveyInstances`
--

CREATE TABLE IF NOT EXISTS `SurveyInstances` (
  `id` int(11) NOT NULL auto_increment,
  `owner_id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `projectteam_id` int(11) NOT NULL,
  `date_given` datetime NOT NULL,
  `closing_date` datetime NOT NULL,
  `reminder_sent` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=750 ;

-- --------------------------------------------------------

--
-- Table structure for table `Surveys`
--

CREATE TABLE IF NOT EXISTS `Surveys` (
  `id` int(11) NOT NULL auto_increment,
  `owner_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `modified_at` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `active` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1229 ;

-- --------------------------------------------------------

--
-- Table structure for table `Universities`
--

CREATE TABLE IF NOT EXISTS `Universities` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=696 ;

-- --------------------------------------------------------

--
-- Table structure for table `UserAssociations`
--

CREATE TABLE IF NOT EXISTS `UserAssociations` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=523 ;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `id` int(11) NOT NULL auto_increment,
  `university_id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `level` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `invite_code` varchar(40) NOT NULL,
  `password` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `last_login_ip` varchar(20) NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=876 ;
