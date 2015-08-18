-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2014 at 03:35 AM
-- Server version: 5.6.11
-- PHP Version: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `wildbook`
--
CREATE DATABASE IF NOT EXISTS `wildbook` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `wildbook`;

DELIMITER $$
--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `checkFriend`(`uname` VARCHAR(30), `ouname` VARCHAR(30)) RETURNS int(1)
    DETERMINISTIC
BEGIN
        DECLARE fc int;
        
		select count(*) into fc from user_friend where (user_name='uname' and user_friend='ouname') or (user_name='oname' and user_friend='uname');
		
        RETURN fc;
    END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `checkFriendOfFriends`(`uname` VARCHAR(30), `ouname` VARCHAR(30)) RETURNS int(1)
    NO SQL
    DETERMINISTIC
BEGIN
	DECLARE fofc int;
	select count(*) into fofc from user_friend, (select user_friend as ufl from user_friend as uf where uf.user_name='uname' or uf.user_friend='uname') as friendList where (user_name IN (friendList.ufl) and user_friend='otheruname') or  (user_name='otheruname' and user_friend IN (friendList.ufl));                                                                                                                                                                                                                                     
	RETURN fofc;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `userProfilePermission`(`uname` VARCHAR(30), `ouname` VARCHAR(30)) RETURNS int(1)
    NO SQL
BEGIN
        DECLARE fc,fofc,per int;
		set fc = checkFriend(uname,ouname);
		set fofc = checkFriendOfFriends(uname,ouname);
		
		if (fc > 0) then
			set per = 1;
		elseif (fofc > 0) then
			set per = 2;
		else
			set per = 3;
		end if;

        RETURN per;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE IF NOT EXISTS `activity` (
  `activity_name` varchar(255) NOT NULL,
  PRIMARY KEY (`activity_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`activity_name`) VALUES
('Biking'),
('Fishing'),
('Hiking'),
('Surfing');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `location_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_name` varchar(255) NOT NULL,
  `longitude` decimal(8,4) DEFAULT NULL,
  `latitude` decimal(8,4) DEFAULT NULL,
  PRIMARY KEY (`location_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`location_id`, `location_name`, `longitude`, `latitude`) VALUES
(1, 'New York City', '-74.0041', '40.7155'),
(2, 'Miami beach', '-80.1285', '25.7900'),
(3, 'Manhattan Beach Park', '-73.9426', '40.5769'),
(4, 'Los Angeles', '-118.2442', '34.0552'),
(5, 'Phoenix Arizona', '-112.0660', '33.4446');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_name` varchar(30) NOT NULL,
  `password` varchar(64) NOT NULL,
  `permission` int(1) NOT NULL DEFAULT '1',
  `email` varchar(50) DEFAULT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `photo` varchar(15) DEFAULT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `city` varchar(30) NOT NULL,
  PRIMARY KEY (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_name`, `password`, `permission`, `email`, `first_name`, `last_name`, `date_of_birth`, `photo`, `last_login`, `city`) VALUES
('chai', 'ca978112ca1bbdcafac231b39a23dc4da786eff8147c4e72b9807785afee48bb', 1, NULL, 'Chaitanya', 'Bhorade', '1990-08-16', '0', '2014-04-30 17:34:38', 'NewYork'),
('sanmeet', 'ca978112ca1bbdcafac231b39a23dc4da786eff8147c4e72b9807785afee48bb', 1, 'ss7631@nyu.edu', 'Sanmeet', 'Shinde', '1992-03-13', 'jpg', '2014-05-05 07:55:51', 'New York'),
('saumil', '18ac3e7343f016890c510e93f935261169d9e3f565436429830faf0934f4f8e4', 1, 'scm457@nyu.edu', 'Saumil', 'Modi', '1990-08-01', '0', '2014-05-01 01:15:45', ''),
('savan', '2e7d2c03a9507ae265ecf5b5356885a53393a2029d241394997265a1a25aefc6', 1, 'spr297@nyu.edu', 'Savan', 'Rupani', '1990-10-16', 'jpg', '2014-05-01 01:11:25', ''),
('uttara', '3e23e8160039594a33894f6564e1b1348bbd7a0088d42c4acb73eeaed59c009d', 1, 'uc252@nyu.edu', 'Uttara', 'Chavan', '1990-04-05', 'jpg', '2014-05-01 01:10:23', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE IF NOT EXISTS `user_activity` (
  `user_name` varchar(30) NOT NULL,
  `activity` varchar(255) NOT NULL,
  PRIMARY KEY (`user_name`,`activity`),
  KEY `user_activity_ibfk_2` (`activity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_activity`
--

INSERT INTO `user_activity` (`user_name`, `activity`) VALUES
('sanmeet', 'Fishing'),
('saumil', 'Fishing'),
('savan', 'Fishing'),
('sanmeet', 'Surfing'),
('saumil', 'Surfing'),
('uttara', 'Surfing');

-- --------------------------------------------------------

--
-- Table structure for table `user_activity_location`
--

CREATE TABLE IF NOT EXISTS `user_activity_location` (
  `user_name` varchar(30) NOT NULL,
  `location_id` int(11) NOT NULL,
  `activity_name` varchar(255) NOT NULL,
  PRIMARY KEY (`user_name`,`location_id`,`activity_name`),
  KEY `user_activity_location_fbk2` (`location_id`),
  KEY `user_activity_location_fbk3` (`activity_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_activity_location`
--

INSERT INTO `user_activity_location` (`user_name`, `location_id`, `activity_name`) VALUES
('sanmeet', 1, 'Biking'),
('uttara', 1, 'Fishing'),
('sanmeet', 2, 'Fishing'),
('saumil', 3, 'Hiking'),
('savan', 4, 'Surfing');

-- --------------------------------------------------------

--
-- Table structure for table `user_comment`
--

CREATE TABLE IF NOT EXISTS `user_comment` (
  `user_name` varchar(30) NOT NULL DEFAULT '',
  `post_id` int(11) NOT NULL DEFAULT '0',
  `comment` text,
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `location_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_name`,`post_id`,`time_stamp`),
  KEY `user_comment_ibfk_2` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_comment`
--

INSERT INTO `user_comment` (`user_name`, `post_id`, `comment`, `time_stamp`, `location_id`) VALUES
('sanmeet', 1, 'hjbqjf nw fjwbf jmafsdaf', '2014-04-21 17:08:37', NULL),
('sanmeet', 2, 'afwj fasjfd asd cajs sjc sj sacj,sad sd,csa nc sac, ascasn c,as casnca s,cs c shshs bhiuwufwibefkw eqf wqfjhwfhk kf asdf sf s fas fs kfkfsdf  fas  hsfh as f', '2014-04-21 17:08:37', NULL),
('saumil', 6, 'fsd jfs fka fkjasdfsaf', '2014-04-21 17:09:01', 1),
('saumil', 9, 'sc jhakch adlc lascascavd', '2014-04-21 17:09:48', NULL),
('saumil', 12, ' jgsj,aakf ajf alksfd asfasfd', '2014-04-21 17:09:01', NULL),
('savan', 11, 'asfjaf akhfawlf wfagg dgfg', '2014-04-21 17:09:26', NULL),
('savan', 17, 'szvjav, akvh wvliwbvwv jsvas', '2014-04-21 17:09:48', NULL),
('uttara', 11, 'se begik bgibrgiegbes gkg sdfjgdsfg', '2014-04-21 17:09:26', 2);

-- --------------------------------------------------------

--
-- Table structure for table `user_friend`
--

CREATE TABLE IF NOT EXISTS `user_friend` (
  `user_name` varchar(30) NOT NULL,
  `user_friend` varchar(30) NOT NULL,
  `permission1` int(2) DEFAULT NULL,
  `permisiion2` int(2) DEFAULT NULL,
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_name`,`user_friend`),
  KEY `userfriend` (`user_friend`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_friend`
--

INSERT INTO `user_friend` (`user_name`, `user_friend`, `permission1`, `permisiion2`, `time_stamp`) VALUES
('sanmeet', 'saumil', 3, 2, '2014-01-15 10:00:00'),
('sanmeet', 'savan', 1, 3, '2014-02-22 10:00:00'),
('uttara', 'sanmeet', 1, 1, '2013-11-20 10:00:00'),
('uttara', 'saumil', 2, 1, '2014-01-22 10:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_friend_request`
--

CREATE TABLE IF NOT EXISTS `user_friend_request` (
  `user_name` varchar(30) NOT NULL DEFAULT '',
  `user_friend` varchar(30) NOT NULL DEFAULT '',
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_name`,`user_friend`),
  KEY `user_friend_request_fbk2` (`user_friend`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_friend_request`
--

INSERT INTO `user_friend_request` (`user_name`, `user_friend`, `time_stamp`) VALUES
('saumil', 'savan', '2014-04-21 20:28:29'),
('savan', 'uttara', '2014-03-26 08:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_like`
--

CREATE TABLE IF NOT EXISTS `user_like` (
  `user_name` varchar(30) NOT NULL DEFAULT '',
  `post_id` int(11) NOT NULL DEFAULT '0',
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_name`,`post_id`),
  KEY `user_like_ibfk_2` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_like`
--

INSERT INTO `user_like` (`user_name`, `post_id`, `time_stamp`) VALUES
('sanmeet', 1, '2014-04-21 17:07:18'),
('sanmeet', 2, '2014-04-21 17:07:18'),
('sanmeet', 9, '2014-04-21 17:07:59'),
('saumil', 5, '2014-04-21 17:07:31'),
('saumil', 6, '2014-04-21 17:07:31'),
('saumil', 10, '2014-04-21 17:07:59'),
('savan', 8, '2014-04-21 17:07:39'),
('savan', 14, '2014-04-21 17:07:39'),
('uttara', 9, '2014-04-21 17:07:50'),
('uttara', 10, '2014-04-21 17:07:50');

-- --------------------------------------------------------

--
-- Table structure for table `user_post`
--

CREATE TABLE IF NOT EXISTS `user_post` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_profile` varchar(30) DEFAULT NULL,
  `user_name` varchar(30) DEFAULT NULL,
  `activity_title` varchar(255) DEFAULT NULL,
  `activity_name` varchar(255) DEFAULT NULL,
  `activity_description` text,
  `location_id` int(11) DEFAULT NULL,
  `permission` int(1) NOT NULL DEFAULT '1',
  `multimedia` varchar(255) DEFAULT NULL,
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`post_id`),
  KEY `user_post_ifbk_3_idx` (`activity_name`),
  KEY `user_post_ifbk_4_idx` (`location_id`),
  KEY `user_post_ibfk_1` (`user_profile`),
  KEY `user_post_ibfk_2` (`user_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

--
-- Dumping data for table `user_post`
--

INSERT INTO `user_post` (`post_id`, `user_profile`, `user_name`, `activity_title`, `activity_name`, `activity_description`, `location_id`, `permission`, `multimedia`, `time_stamp`) VALUES
(1, 'sanmeet', 'sanmeet', 'Went Biking', 'Biking', 'Great event!', 1, 3, NULL, '2014-05-06 01:08:26'),
(2, 'sanmeet', 'sanmeet', 'Fishing with friends', 'Fishing', 'Nice activity', 2, 2, NULL, '2014-05-06 01:08:58'),
(3, 'sanmeet', 'sanmeet', 'Fishing again', 'Fishing', 'Enjoyed a lot!', 3, 1, NULL, '2014-05-06 01:09:15'),
(4, 'sanmeet', 'sanmeet', 'Finally did surfing', 'Surfing', 'Surfing is the best thing ever!', 4, 3, NULL, '2014-05-06 01:10:11'),
(5, 'sanmeet', 'sanmeet', 'Biking it is!', 'Biking', 'Had an accident, got hurt :(', 1, 0, NULL, '2014-05-06 01:11:45'),
(6, 'saumil', 'saumil', ' sjkadfwajf jwqf wjfn asndf ', 'Biking', 'oijo asd cajs sjc sj sacj,sad sd,csa nc sac, ascasn c,as casnca s,cs c shshs bhiuwufwibefkw eqf wqfjhwfhk kf asdf sf s fas fs kfkfsdf  fas  hsfh as f', 1, 1, NULL, '2014-04-21 17:02:24'),
(7, 'saumil', 'saumil', 'ojinv fjna vj savd,', 'Fishing', ' ppovsv asd cajs sjc sj sacj,sad sd,csa nc sac, ascasn c,as casnca s,cs c shshs bhiuwufwibefkw eqf wqfjhwfhk kf asdf sf s fas fs kfkfsdf  fas  hsfh as f', 2, 3, NULL, '2014-04-21 17:02:24'),
(8, 'sanmeet', 'saumil', 'jabv kja j dbsfvn', 'Hiking', 'iow23j  asd cajs sjc sj sacj,sad sd,csa nc sac, ascasn c,as casnca s,cs c shshs bhiuwufwibefkw eqf wqfjhwfhk kf asdf sf s fas fs kfkfsdf  fas  hsfh as f', 3, 2, NULL, '2014-04-21 17:02:24'),
(9, 'saumil', 'saumil', 'mnbx ncbc xjvi', 'Surfing', '9878b jhasd cajs sjc sj sacj,sad sd,csa nc sac, ascasn c,as casnca s,cs c shshs bhiuwufwibefkw eqf wqfjhwfhk kf asdf sf s fas fs kfkfsdf  fas  hsfh as f', 4, 0, NULL, '2014-04-21 17:02:24'),
(10, 'savan', 'savan', 'j j md fvnbasbd a,sfdf', 'Biking', ' ajs vjasjaf an masd cajs sjc sj sacj,sad sd,csa nc sac, ascasn c,as casnca s,cs c shshs bhiuwufwibefkw eqf wqfjhwfhk kf asdf sf s fas fs kfkfsdf  fas  hsfh as f', 1, 3, NULL, '2014-04-21 17:02:24'),
(11, 'saumil', 'savan', 'oiubave mjba sdnbamsv', 'Fishing', 'a jvba smnv asjdv hajv asbvdvdsadv', NULL, 1, NULL, '2014-04-21 17:03:29'),
(12, 'savan', 'savan', 'iuik jmfnab fdasf', 'Hiking', 'khb ,safaskfkl ,m mn asd cajs sjc sj sacj,sad sd,csa nc sac, ascasn c,as casnca s,cs c shshs bhiuwufwibefkw eqf wqfjhwfhk kf asdf sf s fas fs kfkfsdf  fas  hsfh as f', NULL, 3, NULL, '2014-04-21 17:03:29'),
(13, 'uttara', 'uttara', 'fj sfsd vmnv dk,vds', 'Biking', 'asd cajs sjc sj sacj,sad sd,csa nc sac, ascasn c,as casnca s,cs c shshs bhiuwufwibefkw eqf wqfjhwfhk kf asdf sf s fas fs kfkfsdf  fas  hsfh as f', NULL, 3, NULL, '2014-04-21 17:06:20'),
(14, 'uttara', 'uttara', 'iub jsbd', 'Fishing', 'j  n basd cajs sjc sj sacj,sad sd,csa nc sac, ascasn c,as casnca s,cs c shshs bhiuwufwibefkw eqf wqfjhwfhk kf asdf sf s fas fs kfkfsdf  fas  hsfh as f', 2, 1, NULL, '2014-04-21 17:06:20'),
(15, 'savan', 'uttara', 'iuebv m ,m ,', 'Hiking', 'iub asd cajs sjc sj sacj,sad sd,csa nc sac, ascasn c,as casnca s,cs c shshs bhiuwufwibefkw eqf wqfjhwfhk kf asdf sf s fas fs kfkfsdf  fas  hsfh as f', NULL, 2, NULL, '2014-04-21 17:06:20'),
(16, 'sanmeet', 'uttara', 'oihubkf jm j', 'Surfing', 'uyb asd cajs sjc sj sacj,sad sd,csa nc sac, ascasn c,as casnca s,cs c shshs bhiuwufwibefkw eqf wqfjhwfhk kf asdf sf s fas fs kfkfsdf  fas  hsfh as f', 2, 0, NULL, '2014-04-21 17:06:20'),
(17, 'uttara', 'uttara', 'iubk bj aj j,asdf', 'Hiking', 'asd cajs sjc sj sacj,sad sd,csa nc sac, ascasn c,as casnca s,cs c shshs bhiuwufwibefkw eqf wqfjhwfhk kf asdf sf s fas fs kfkfsdf  fas  hsfh as f', 3, 2, NULL, '2014-04-21 17:06:20'),
(34, 'sanmeet', 'sanmeet', 'Six Flags', 'Biking', 'Had Fun!', 1, 1, 'superman01_300dpi_8x10.jpg', '2014-05-06 00:21:56');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD CONSTRAINT `user_activity_ibfk_1` FOREIGN KEY (`user_name`) REFERENCES `user` (`user_name`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_activity_ibfk_2` FOREIGN KEY (`activity`) REFERENCES `activity` (`activity_name`);

--
-- Constraints for table `user_activity_location`
--
ALTER TABLE `user_activity_location`
  ADD CONSTRAINT `user_activity_location_fbk1` FOREIGN KEY (`user_name`) REFERENCES `user` (`user_name`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_activity_location_fbk2` FOREIGN KEY (`location_id`) REFERENCES `location` (`location_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_activity_location_fbk3` FOREIGN KEY (`activity_name`) REFERENCES `activity` (`activity_name`);

--
-- Constraints for table `user_comment`
--
ALTER TABLE `user_comment`
  ADD CONSTRAINT `user_comment_ibfk_1` FOREIGN KEY (`user_name`) REFERENCES `user` (`user_name`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_comment_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `user_post` (`post_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_friend`
--
ALTER TABLE `user_friend`
  ADD CONSTRAINT `user_friend_ibfk_1` FOREIGN KEY (`user_name`) REFERENCES `user` (`user_name`),
  ADD CONSTRAINT `user_friend_ibfk_2` FOREIGN KEY (`user_friend`) REFERENCES `user` (`user_name`);

--
-- Constraints for table `user_friend_request`
--
ALTER TABLE `user_friend_request`
  ADD CONSTRAINT `user_friend_request_fbk1` FOREIGN KEY (`user_name`) REFERENCES `user` (`user_name`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_friend_request_fbk2` FOREIGN KEY (`user_friend`) REFERENCES `user` (`user_name`) ON DELETE CASCADE;

--
-- Constraints for table `user_like`
--
ALTER TABLE `user_like`
  ADD CONSTRAINT `user_like_ibfk_1` FOREIGN KEY (`user_name`) REFERENCES `user` (`user_name`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_like_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `user_post` (`post_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_post`
--
ALTER TABLE `user_post`
  ADD CONSTRAINT `user_post_ibfk_1` FOREIGN KEY (`user_profile`) REFERENCES `user` (`user_name`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_post_ibfk_2` FOREIGN KEY (`user_name`) REFERENCES `user` (`user_name`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_post_ifbk_3` FOREIGN KEY (`activity_name`) REFERENCES `activity` (`activity_name`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `user_post_ifbk_4` FOREIGN KEY (`location_id`) REFERENCES `location` (`location_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
