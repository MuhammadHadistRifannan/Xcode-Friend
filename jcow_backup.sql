-- MySQL dump 10.13  Distrib 5.7.44, for Linux (x86_64)
--
-- Host: localhost    Database: jcow
-- ------------------------------------------------------
-- Server version	5.7.44

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `jcow_accounts`
--

DROP TABLE IF EXISTS `jcow_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fbid` bigint(20) NOT NULL,
  `email` varchar(120) NOT NULL DEFAULT '',
  `lastact` int(11) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL DEFAULT '0',
  `username` varchar(25) NOT NULL,
  `fullname` varchar(30) NOT NULL,
  `password` varchar(32) NOT NULL,
  `level` tinyint(4) NOT NULL DEFAULT '0',
  `points` int(11) NOT NULL,
  `avatar` varchar(50) NOT NULL,
  `signature` tinytext NOT NULL,
  `blurbs` text NOT NULL,
  `profile_permission` tinyint(4) NOT NULL DEFAULT '0',
  `location` varchar(100) NOT NULL,
  `lastlogin` int(11) NOT NULL,
  `ipaddress` varchar(30) NOT NULL,
  `chpass` varchar(10) NOT NULL,
  `disabled` tinyint(4) NOT NULL,
  `intr` text NOT NULL,
  `gender` tinyint(4) NOT NULL,
  `about_me` text NOT NULL,
  `birthyear` int(4) NOT NULL,
  `birthmonth` tinyint(2) NOT NULL,
  `birthday` tinyint(2) NOT NULL,
  `hide_age` tinyint(1) NOT NULL,
  `reg_code` varchar(8) NOT NULL,
  `forum_posts` int(11) NOT NULL,
  `featured` tinyint(1) NOT NULL,
  `roles` varchar(255) NOT NULL,
  `country` varchar(50) NOT NULL,
  `locale` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `jcowsess` char(12) NOT NULL,
  `token` varchar(32) NOT NULL,
  `wall_id` int(11) NOT NULL,
  `followers` int(11) NOT NULL,
  `settings` text NOT NULL,
  `var1` varchar(255) NOT NULL,
  `var2` varchar(255) NOT NULL,
  `var3` varchar(255) NOT NULL,
  `var4` varchar(255) NOT NULL,
  `var5` varchar(255) NOT NULL,
  `var6` varchar(255) NOT NULL,
  `var7` varchar(255) NOT NULL,
  `pass` varchar(32) NOT NULL,
  `hide_me` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `lastlogin` (`lastlogin`),
  KEY `email` (`email`),
  KEY `fbid` (`fbid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_accounts`
--

LOCK TABLES `jcow_accounts` WRITE;
/*!40000 ALTER TABLE `jcow_accounts` DISABLE KEYS */;
INSERT INTO `jcow_accounts` VALUES (1,0,'muhammadhadistrifannan@gmail.com',0,1786515889,'admin','admin','c312261f1f26d88501e1b1e98229edd5',0,0,'','','',0,'',1786543899,'','',0,'',1,'',1990,1,1,1,'',0,0,'3','','','','','',0,1,'','','','','','','','','',0),(2,0,'bambang@gmail.com',0,1786542956,'bima','bambang','65c760dc675a81d1d431fe5ab968d1a6',0,0,'','','',0,'Brazil',1786543969,'104.23.176.11','',0,'',1,'',2006,1,1,0,'',0,0,'','','','','on92rycdw5iz','',0,1,'','','','','','','','','',0),(3,0,'giskasaputra123@gmail.com',0,1786543359,'giska','Giska Saputra','1184a970c9b8d65264f8e17c739e01a1',0,0,'','','',0,'Indonesia',1786543756,'172.71.124.18','',0,'',1,'cowo anti rokok',2009,1,1,0,'',0,0,'','','','','rmtyjuqmvn2w','',0,2,'','','','','','','','','',0);
/*!40000 ALTER TABLE `jcow_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_banned`
--

DROP TABLE IF EXISTS `jcow_banned`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_banned` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `ip1` varchar(3) NOT NULL,
  `ip2` varchar(3) NOT NULL,
  `ip3` varchar(3) NOT NULL,
  `ip4` varchar(3) NOT NULL,
  `created` int(11) NOT NULL,
  `expired` int(11) NOT NULL,
  `operator` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_banned`
--

LOCK TABLES `jcow_banned` WRITE;
/*!40000 ALTER TABLE `jcow_banned` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_banned` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_blacks`
--

DROP TABLE IF EXISTS `jcow_blacks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_blacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `bid` int(11) NOT NULL DEFAULT '0',
  `bname` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`,`bid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_blacks`
--

LOCK TABLES `jcow_blacks` WRITE;
/*!40000 ALTER TABLE `jcow_blacks` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_blacks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_cache`
--

DROP TABLE IF EXISTS `jcow_cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_cache` (
  `ckey` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `expired` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_cache`
--

LOCK TABLES `jcow_cache` WRITE;
/*!40000 ALTER TABLE `jcow_cache` DISABLE KEYS */;
INSERT INTO `jcow_cache` VALUES ('a9711cbb2e3c','<h1>bambang</h1><h2>Members</h2><ul class=\"small_avatars\"><li><a href=\"https://xcode-friends.bennedistus.web.id/index.php?p=u/bima\">bima</a><br /><a href=\"https://xcode-friends.bennedistus.web.id/index.php?p=u/bima\">\r\n	<img  src=\"https://xcode-friends.bennedistus.web.id/uploads/avatars/s_undefined.jpg\" class=\"avatar\" /></a><br />bambang</li></ul><h2>Stories</h2>\r\n					<p>Searching for <strong>\"bambang\"</strong></p><p>no story matched</p>',1786716197);
/*!40000 ALTER TABLE `jcow_cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_chatrooms`
--

DROP TABLE IF EXISTS `jcow_chatrooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_chatrooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `content` text NOT NULL,
  `updated` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`,`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_chatrooms`
--

LOCK TABLES `jcow_chatrooms` WRITE;
/*!40000 ALTER TABLE `jcow_chatrooms` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_chatrooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_comments`
--

DROP TABLE IF EXISTS `jcow_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `target_id` varchar(20) NOT NULL,
  `uid` int(11) NOT NULL,
  `message` text NOT NULL,
  `created` int(11) NOT NULL,
  `stream_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `target_id` (`target_id`),
  KEY `stream_id` (`stream_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_comments`
--

LOCK TABLES `jcow_comments` WRITE;
/*!40000 ALTER TABLE `jcow_comments` DISABLE KEYS */;
INSERT INTO `jcow_comments` VALUES (1,'',1,'suyah',1786542991,1),(2,'',2,'yayayayayyayaa',1786543294,3),(3,'',1,'suuu',1786543359,3),(4,'',3,'suwwww',1786543407,3),(5,'',3,'asww',1786543422,3),(6,'',1,'apa iya',1786543675,7),(7,'',3,'apa apa',1786543756,9);
/*!40000 ALTER TABLE `jcow_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_favorites`
--

DROP TABLE IF EXISTS `jcow_favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `fuid` int(11) NOT NULL,
  `fapp` varchar(100) NOT NULL,
  `fsid` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`,`fuid`,`fsid`,`created`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_favorites`
--

LOCK TABLES `jcow_favorites` WRITE;
/*!40000 ALTER TABLE `jcow_favorites` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_followers`
--

DROP TABLE IF EXISTS `jcow_followers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_followers` (
  `uid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  KEY `uid` (`uid`,`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_followers`
--

LOCK TABLES `jcow_followers` WRITE;
/*!40000 ALTER TABLE `jcow_followers` DISABLE KEYS */;
INSERT INTO `jcow_followers` VALUES (1,3),(2,3),(3,1),(3,2);
/*!40000 ALTER TABLE `jcow_followers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_forum_attachments`
--

DROP TABLE IF EXISTS `jcow_forum_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_forum_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `uri` varchar(100) NOT NULL,
  `des` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `orginal_name` varchar(255) NOT NULL,
  `downloads` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `tid` (`tid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_forum_attachments`
--

LOCK TABLES `jcow_forum_attachments` WRITE;
/*!40000 ALTER TABLE `jcow_forum_attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_forum_attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_forum_polls`
--

DROP TABLE IF EXISTS `jcow_forum_polls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_forum_polls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL DEFAULT '0',
  `question` varchar(100) NOT NULL DEFAULT '',
  `created` int(11) NOT NULL DEFAULT '0',
  `options` text NOT NULL,
  `timeout` int(11) NOT NULL DEFAULT '0',
  `options_per_user` tinyint(4) NOT NULL DEFAULT '0',
  `voters` text NOT NULL,
  `total` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_forum_polls`
--

LOCK TABLES `jcow_forum_polls` WRITE;
/*!40000 ALTER TABLE `jcow_forum_polls` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_forum_polls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_forum_posts`
--

DROP TABLE IF EXISTS `jcow_forum_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_forum_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` bigint(11) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created` int(10) NOT NULL DEFAULT '0',
  `ip` varchar(30) NOT NULL DEFAULT '',
  `is_first` tinyint(4) NOT NULL DEFAULT '0',
  `attach` int(11) NOT NULL DEFAULT '0',
  `bbcode_off` tinyint(4) NOT NULL DEFAULT '0',
  `emote_off` tinyint(4) NOT NULL DEFAULT '0',
  `got_attach` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`),
  KEY `author_id` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_forum_posts`
--

LOCK TABLES `jcow_forum_posts` WRITE;
/*!40000 ALTER TABLE `jcow_forum_posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_forum_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_forum_subscribes`
--

DROP TABLE IF EXISTS `jcow_forum_subscribes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_forum_subscribes` (
  `uid` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  KEY `uid` (`uid`,`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_forum_subscribes`
--

LOCK TABLES `jcow_forum_subscribes` WRITE;
/*!40000 ALTER TABLE `jcow_forum_subscribes` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_forum_subscribes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_forum_threads`
--

DROP TABLE IF EXISTS `jcow_forum_threads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_forum_threads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL DEFAULT '0',
  `old_fid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `userid` int(11) NOT NULL DEFAULT '0',
  `username` varchar(50) NOT NULL,
  `topic` varchar(255) NOT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `posts` int(11) NOT NULL DEFAULT '0',
  `closed` smallint(1) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL DEFAULT '0',
  `lastpostusername` varchar(255) NOT NULL DEFAULT '0',
  `lastpostcreated` int(10) NOT NULL DEFAULT '0',
  `icon` tinyint(4) NOT NULL DEFAULT '0',
  `thread_type` tinyint(1) NOT NULL DEFAULT '0',
  `thread_lock` tinyint(1) NOT NULL DEFAULT '0',
  `got_poll` tinyint(11) NOT NULL DEFAULT '0',
  `got_attach` tinyint(4) NOT NULL,
  `stressed` tinyint(4) NOT NULL DEFAULT '0',
  `digg` int(11) NOT NULL DEFAULT '0',
  `dugg` int(11) NOT NULL DEFAULT '0',
  `votes` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fid` (`fid`),
  KEY `thread_type` (`thread_type`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_forum_threads`
--

LOCK TABLES `jcow_forum_threads` WRITE;
/*!40000 ALTER TABLE `jcow_forum_threads` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_forum_threads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_forums`
--

DROP TABLE IF EXISTS `jcow_forums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_forums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weight` int(11) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `type_pic` varchar(255) NOT NULL DEFAULT '',
  `description` tinytext NOT NULL,
  `rules` text NOT NULL,
  `forum_type` varchar(50) NOT NULL DEFAULT '0',
  `threads` int(11) NOT NULL DEFAULT '0',
  `posts` int(11) NOT NULL DEFAULT '0',
  `lastpostname` varchar(32) NOT NULL,
  `lastposttopicid` int(11) NOT NULL DEFAULT '0',
  `lastposttopic` varchar(70) NOT NULL,
  `lastpostcreated` int(11) NOT NULL DEFAULT '0',
  `moderator` varchar(255) NOT NULL DEFAULT '',
  `settings` text NOT NULL,
  `fmembers` int(11) NOT NULL DEFAULT '0',
  `image` varchar(250) NOT NULL,
  `read_roles` varchar(255) NOT NULL,
  `upload_roles` varchar(255) NOT NULL,
  `thread_roles` varchar(255) NOT NULL,
  `reply_roles` varchar(255) NOT NULL,
  `moderators` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `belong_id` (`parent_id`),
  KEY `order_num` (`weight`),
  KEY `type_class` (`forum_type`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_forums`
--

LOCK TABLES `jcow_forums` WRITE;
/*!40000 ALTER TABLE `jcow_forums` DISABLE KEYS */;
INSERT INTO `jcow_forums` VALUES (7,1,0,'General Category','','','','category',0,0,'',0,'',0,'','',0,'','','','','',''),(8,1,7,'General Forum','','This is a general forum','','forum',0,0,'',0,'',0,'','',0,'','1|2','2','2','2','');
/*!40000 ALTER TABLE `jcow_forums` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_friend_reqs`
--

DROP TABLE IF EXISTS `jcow_friend_reqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_friend_reqs` (
  `uid` int(11) NOT NULL DEFAULT '0',
  `fid` int(11) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL DEFAULT '0',
  `msg` varchar(200) NOT NULL,
  KEY `uid` (`uid`,`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_friend_reqs`
--

LOCK TABLES `jcow_friend_reqs` WRITE;
/*!40000 ALTER TABLE `jcow_friend_reqs` DISABLE KEYS */;
INSERT INTO `jcow_friend_reqs` VALUES (2,1,1786543954,'acc suwee');
/*!40000 ALTER TABLE `jcow_friend_reqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_friends`
--

DROP TABLE IF EXISTS `jcow_friends`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_friends` (
  `uid` int(11) NOT NULL DEFAULT '0',
  `fid` int(11) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL DEFAULT '0',
  KEY `uid` (`uid`,`fid`),
  KEY `fid` (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_friends`
--

LOCK TABLES `jcow_friends` WRITE;
/*!40000 ALTER TABLE `jcow_friends` DISABLE KEYS */;
INSERT INTO `jcow_friends` VALUES (1,3,1786543633),(3,1,1786543633),(3,2,1786543706),(2,3,1786543706);
/*!40000 ALTER TABLE `jcow_friends` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_group_categories`
--

DROP TABLE IF EXISTS `jcow_group_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_group_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `groups` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_group_categories`
--

LOCK TABLES `jcow_group_categories` WRITE;
/*!40000 ALTER TABLE `jcow_group_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_group_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_group_members`
--

DROP TABLE IF EXISTS `jcow_group_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_group_members` (
  `gid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `about_me` text NOT NULL,
  `hide_profile` tinyint(1) NOT NULL,
  KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_group_members`
--

LOCK TABLES `jcow_group_members` WRITE;
/*!40000 ALTER TABLE `jcow_group_members` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_group_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_group_members_pending`
--

DROP TABLE IF EXISTS `jcow_group_members_pending`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_group_members_pending` (
  `uid` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `ignored` tinyint(4) NOT NULL,
  KEY `uid` (`uid`,`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_group_members_pending`
--

LOCK TABLES `jcow_group_members_pending` WRITE;
/*!40000 ALTER TABLE `jcow_group_members_pending` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_group_members_pending` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_group_polls`
--

DROP TABLE IF EXISTS `jcow_group_polls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_group_polls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL DEFAULT '0',
  `question` varchar(100) NOT NULL DEFAULT '',
  `created` int(11) NOT NULL DEFAULT '0',
  `options` text NOT NULL,
  `timeout` int(11) NOT NULL DEFAULT '0',
  `options_per_user` tinyint(4) NOT NULL DEFAULT '0',
  `voters` text NOT NULL,
  `total` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_group_polls`
--

LOCK TABLES `jcow_group_polls` WRITE;
/*!40000 ALTER TABLE `jcow_group_polls` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_group_polls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_group_postcats`
--

DROP TABLE IF EXISTS `jcow_group_postcats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_group_postcats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_group_postcats`
--

LOCK TABLES `jcow_group_postcats` WRITE;
/*!40000 ALTER TABLE `jcow_group_postcats` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_group_postcats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_group_posts`
--

DROP TABLE IF EXISTS `jcow_group_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_group_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL,
  `tid` bigint(11) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `username` varchar(50) NOT NULL,
  `rtid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `rname` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created` int(10) NOT NULL DEFAULT '0',
  `ip` varchar(30) NOT NULL DEFAULT '',
  `attach` int(11) NOT NULL DEFAULT '0',
  `bbcode_off` tinyint(4) NOT NULL DEFAULT '0',
  `emote_off` tinyint(4) NOT NULL DEFAULT '0',
  `got_attach` tinyint(4) NOT NULL,
  `topic` varchar(100) NOT NULL,
  `is_first` tinyint(4) NOT NULL,
  `replies` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`),
  KEY `uid` (`uid`),
  KEY `gid` (`gid`),
  KEY `rtid` (`rtid`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_group_posts`
--

LOCK TABLES `jcow_group_posts` WRITE;
/*!40000 ALTER TABLE `jcow_group_posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_group_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_group_topics`
--

DROP TABLE IF EXISTS `jcow_group_topics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_group_topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL DEFAULT '0',
  `old_fid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0',
  `username` varchar(50) NOT NULL,
  `topic` varchar(255) NOT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `posts` int(11) NOT NULL DEFAULT '0',
  `closed` smallint(1) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL DEFAULT '0',
  `lastpostusername` varchar(255) NOT NULL DEFAULT '0',
  `lastpostcreated` int(11) NOT NULL DEFAULT '0',
  `icon` tinyint(4) NOT NULL DEFAULT '0',
  `thread_type` tinyint(1) NOT NULL DEFAULT '0',
  `thread_lock` tinyint(1) NOT NULL DEFAULT '0',
  `got_poll` tinyint(11) NOT NULL DEFAULT '0',
  `got_attach` tinyint(4) NOT NULL,
  `stressed` tinyint(4) NOT NULL DEFAULT '0',
  `digg` int(11) NOT NULL DEFAULT '0',
  `dugg` int(11) NOT NULL DEFAULT '0',
  `votes` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_group_topics`
--

LOCK TABLES `jcow_group_topics` WRITE;
/*!40000 ALTER TABLE `jcow_group_topics` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_group_topics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_groups`
--

DROP TABLE IF EXISTS `jcow_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uri` varchar(30) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slogan` varchar(200) NOT NULL,
  `creatorid` int(11) NOT NULL,
  `creator` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `members` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `private` tinyint(4) NOT NULL,
  `needapproval` tinyint(4) NOT NULL,
  `posts` int(11) NOT NULL,
  `topics` int(11) NOT NULL,
  `lastptime` int(11) NOT NULL,
  `lastpname` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `custom_css` text NOT NULL,
  `style_ids` varchar(50) NOT NULL,
  `category` char(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `creatorid` (`creatorid`),
  KEY `uri` (`uri`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_groups`
--

LOCK TABLES `jcow_groups` WRITE;
/*!40000 ALTER TABLE `jcow_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_gvars`
--

DROP TABLE IF EXISTS `jcow_gvars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_gvars` (
  `gkey` varchar(50) NOT NULL,
  `gvalue` text NOT NULL,
  KEY `gkey` (`gkey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_gvars`
--

LOCK TABLES `jcow_gvars` WRITE;
/*!40000 ALTER TABLE `jcow_gvars` DISABLE KEYS */;
INSERT INTO `jcow_gvars` VALUES ('theme_folder','themes/default'),('cf_var1','disabled'),('cf_var2','disabled'),('cf_var3','disabled'),('cf_var4','disabled'),('cf_var5','disabled'),('cf_var6','disabled'),('cf_var7','disabled'),('jcow_version','4.0'),('app_music_disable','0'),('story_access','all'),('profile_access','all'),('site_slogan','This is a Social Network'),('ad_block_content_top',''),('ad_block_content_bottom',''),('site_name','My Jcow Network'),('site_email','name@domain.com'),('block_top',''),('block_bottom',''),('only_invited','0'),('session_lived','1267784005'),('permission_etheme','2'),('permission_atheme','2|11'),('private_network','0'),('groupsenabled','1'),('forumsenabled','1'),('theme_tpl','default'),('theme_css','1.css'),('hide_ad_roles','3'),('permission_upload','2'),('permission_comment','2'),('permission_add','2'),('permission_browse','1|2'),('permission_feed','1|2'),('theme_block_adsbar','Go to \"Admin CP\" - \"Themes\" - \"Manage Blocks\" to edit this message.'),('limit_posting_num','5'),('app_music','0');
/*!40000 ALTER TABLE `jcow_gvars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_invites`
--

DROP TABLE IF EXISTS `jcow_invites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_invites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_invites`
--

LOCK TABLES `jcow_invites` WRITE;
/*!40000 ALTER TABLE `jcow_invites` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_invites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_langs`
--

DROP TABLE IF EXISTS `jcow_langs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_langs` (
  `lang_from` varchar(255) NOT NULL DEFAULT '',
  `lang_to` text NOT NULL,
  `lang` varchar(20) NOT NULL DEFAULT '',
  KEY `lang_from` (`lang_from`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_langs`
--

LOCK TABLES `jcow_langs` WRITE;
/*!40000 ALTER TABLE `jcow_langs` DISABLE KEYS */;
INSERT INTO `jcow_langs` VALUES ('Menu','','en'),('Guest','','en'),('Login/ SignUp','','en'),('Friends','','en'),('Login','','en'),('Sign up','','en'),('Home','','en'),('Browse','','en'),('News feed','','en'),('Photos','','en'),('Blogs','','en'),('Videos','','en'),('Username or Email','','en'),('Password','','en'),('Forgot password?','','en'),('Remember me','','en'),('New to our Network?','','en'),('Join Now!','','en'),('Recent Logins','','en'),('Browse more people','','en'),('Network Statistics','','en'),('Activities','','en'),('Members','','en'),('Friendships','','en'),('Comments','','en'),('Community activities','','en'),('You need to login to do this','','en'),('Update','','en'),('Gender','','en'),('Male','','en'),('Female','','en'),('Both','','en'),('Age','','en'),('Location','','en'),('All','','en'),('Order by','','en'),('Last Login','','en'),('Registration','','en'),('Top Followed','','en'),('Dashboard','','en'),('Administration Panel','','en'),('Avatar picture','','en'),('You haven\'t finished editing your profile','','en'),('Your profile was viewed {1} times.','','en'),('You have {1} friends and {2} followers.','','en'),('My Profile','','en'),('My Followers','','en'),('My Following','','en'),('Preference','','en'),('Account','','en'),('Friends birthday coming up','','en'),('Quick share','','en'),('What\'s happening...','','en'),('Blog','','en'),('Upload','','en'),('Video','','en'),('Share','','en'),('Logout','','en'),('Inbox','','en'),('Notifications','','en'),('Profile','','en'),('Invite','','en'),('Admin CP','','en'),('Admin Panel','','en'),('Site configuration','','en'),('Modules','','en'),('Themes','','en'),('Profile Questions','','en'),('User Roles','','en'),('Texts','','en'),('Translate','','en'),('Username','','en'),('Search','','en'),('Register an account','','en'),('Log in','','en'),('Wrong account or password','','en'),('Passport','','en'),('Email Address','','en'),('We won\'t display your Email Address.','','en'),('Nickname','','en'),('4 to 18 characters, made up of 0-9,a-z','','en'),('Personal info','','en'),('Full Name','','en'),('Birth','','en'),('Hide my age','','en'),('Hide','','en'),('Come from','','en'),('About me','','en'),('Rules & Conditions','','en'),('I have read, and agree to abide by the Rules & Conditions.','','en'),('Signup Now','','en'),('Blocks','','en'),('Please fill in all the required blanks','','en'),('Errors','','en'),('Signed Up','','en'),('Congratulations! You have successfully signed up. You can now login with your account','','en'),('Click here to go on','','en'),('Like','','en'),('Comment','','en'),('Content','','en'),('Title','','en'),('Submit','','en'),('Save changes','','en'),('Categories','','en'),('added a photo album','','en'),('Album name','','en'),('Next step','','en'),('{1}\'s profile','','en'),('Follow','','en'),('Message','','en'),('Add friend','','en'),('Following','','en'),('See all','','en'),('Groups','','en'),('Pages','','en'),('Birthday','','en'),('Hidden','','en'),('Registered','','en'),('Details','','en'),('Wall','','en'),('Liked','','en'),('Get back my password','','en'),('Email','','en'),('The email address you registered with','','en'),('{1} commented on your stream','','en'),('{1} people like this','','en'),('Friend requests','','en'),('My friends','','en'),('Outbox','','en'),('Check/ Uncheck all','','en'),('Delete','','en'),('Are you sure to delete?','','en'),('Unlike','','en'),('Related posts','','en'),('New Post','','en'),('Rating','','en'),('added a blog post','','en'),('blog entries','','en'),('Tags','','en'),('Multiple tags should be Separated with commas(,)','','en'),('Privacy','','en'),('Everyone','','en'),('Friends of friends','','en'),('Friends only','','en'),('Posted by {1}','','en'),('Views','','en'),('Edit','','en'),('Feature this','','en'),('Bookmark & Share','','en'),('Report spam, advertising, and problematic.','','en'),('Block','','en'),('Post something...','','en'),('{1}\'s {2}','','en'),('My Blogs','','en'),('No entry','','en'),('Edit profile','','en'),('Description','','en'),('Blog Title','','en'),('Separated with commas','','en'),('Login to comment','','en'),('added a blog','','en'),('Blog Added!','','en'),('View','','en'),('Stories','','en'),('Im Following','','en'),('Unfollow','','en'),('Operation success','','en'),('Compose a message','','en'),('Send to','','en'),('Subject','','en'),('Optional','','en'),('Send','','en'),('You have {1} pending requests','','en'),('To others','','en'),('To you','','en'),('Adding {1} {2} as friend','','en'),('Request message','','en'),('Say something to help your request be accepted','','en'),('Send request','','en'),('{1} wants to be friends with you','','en'),('Your request has been sent successfully','','en'),('Approve','','en'),('Reject','','en'),('became a friend of {1}','','en'),('{1} confirmed your friend request','','en'),('Remove','','en'),('Add Video','','en'),('added a video','','en');
/*!40000 ALTER TABLE `jcow_langs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_liked`
--

DROP TABLE IF EXISTS `jcow_liked`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_liked` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `stream_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `stream_id` (`stream_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_liked`
--

LOCK TABLES `jcow_liked` WRITE;
/*!40000 ALTER TABLE `jcow_liked` DISABLE KEYS */;
INSERT INTO `jcow_liked` VALUES (1,1,1),(2,1,5),(3,1,7);
/*!40000 ALTER TABLE `jcow_liked` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_menu`
--

DROP TABLE IF EXISTS `jcow_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `tab_name` varchar(50) NOT NULL,
  `weight` int(11) NOT NULL DEFAULT '0',
  `path` varchar(255) NOT NULL DEFAULT '',
  `app` varchar(50) NOT NULL DEFAULT '',
  `actived` tinyint(1) NOT NULL DEFAULT '0',
  `type` varchar(25) NOT NULL,
  `protected` tinyint(1) NOT NULL,
  `allowed_roles` text NOT NULL,
  `icon` varchar(255) NOT NULL,
  `parent` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_menu`
--

LOCK TABLES `jcow_menu` WRITE;
/*!40000 ALTER TABLE `jcow_menu` DISABLE KEYS */;
INSERT INTO `jcow_menu` VALUES (1,'Blogs','',4,'blogs','blogs',1,'community',0,'','',''),(2,'Blogs','My Blogs',4,'blogs/mine','blogs',1,'personal',0,'','',''),(3,'Following','',3,'blogs/following','blogs',1,'tab',0,'','','blogs/mine'),(4,'Friends','',4,'blogs/friends','blogs',1,'tab',0,'','','blogs/mine'),(5,'Photos','',3,'photos','photos',1,'community',0,'','',''),(6,'Photos','My photos',3,'photos/mine','photos',1,'personal',0,'','',''),(7,'Following','',19,'photos/following','photos',1,'tab',0,'','','photos/mine'),(8,'Friends','',20,'photos/friends','photos',1,'tab',0,'','','photos/mine'),(9,'Videos','',21,'videos','videos',1,'community',0,'','',''),(10,'Videos','My videos',5,'videos/mine','videos',1,'personal',0,'','',''),(11,'Following','',23,'videos/following','videos',1,'tab',0,'','','videos/mine'),(12,'Friends','',24,'videos/friends','videos',1,'tab',0,'','','videos/mine'),(13,'Browse','',1,'browse','browse',1,'community',1,'1','',''),(14,'News feed','',2,'feed','feed',1,'community',1,'1','',''),(17,'Dashboard','',0,'dashboard','dashboard',1,'personal',0,'','',''),(18,'My account','My information',20,'account','account',1,'personal',0,'','',''),(19,'Avatar','',34,'account/avatar','account',1,'tab',0,'','','account'),(20,'Notifications','',35,'account/notifications','account',1,'tab',0,'','','account'),(21,'Privacy','',36,'account/privacy','account',1,'tab',0,'','','account'),(22,'Password','',37,'account/cpassword','account',1,'tab',0,'','','account'),(23,'Invite','Invite',20,'invite','invite',1,'personal',0,'','',''),(24,'Histories','Following',40,'invite/histories','invite',1,'tab',0,'','','invite');
/*!40000 ALTER TABLE `jcow_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_messages`
--

DROP TABLE IF EXISTS `jcow_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(100) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  `from_id` int(11) NOT NULL DEFAULT '0',
  `to_id` int(11) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL DEFAULT '0',
  `hasread` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `from_id` (`from_id`,`to_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_messages`
--

LOCK TABLES `jcow_messages` WRITE;
/*!40000 ALTER TABLE `jcow_messages` DISABLE KEYS */;
INSERT INTO `jcow_messages` VALUES (25,'','<a href=\"https://xcode-friends.bennedistus.web.id/index.php?p=u/admin\">admin</a> commented on your stream: <a href=\"https://xcode-friends.bennedistus.web.id/index.php?p=u/bima/status/1\">suyah</a>',0,2,1786542991,1),(26,'','<a href=\"https://xcode-friends.bennedistus.web.id/index.php?p=u/admin\">admin</a> commented on your stream: <a href=\"https://xcode-friends.bennedistus.web.id/index.php?p=u/bima/status/3\">suuu</a>',0,2,1786543359,1),(27,'','<a href=\"https://xcode-friends.bennedistus.web.id/index.php?p=u/giska\">giska</a> commented on your stream: <a href=\"https://xcode-friends.bennedistus.web.id/index.php?p=u/bima/status/3\">suwwww</a>',0,2,1786543407,1),(28,'','<a href=\"https://xcode-friends.bennedistus.web.id/index.php?p=u/giska\">giska</a> commented on your stream: <a href=\"https://xcode-friends.bennedistus.web.id/index.php?p=u/bima/status/3\">asww</a>',0,2,1786543422,1),(29,'','<a href=\"https://xcode-friends.bennedistus.web.id/index.php?p=u/admin\">admin</a> commented on your stream: <a href=\"https://xcode-friends.bennedistus.web.id/index.php?p=u/bima/status/7\">apa iya</a>',0,2,1786543675,0);
/*!40000 ALTER TABLE `jcow_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_messages_sent`
--

DROP TABLE IF EXISTS `jcow_messages_sent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_messages_sent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(100) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  `from_id` int(11) NOT NULL DEFAULT '0',
  `to_id` int(11) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL DEFAULT '0',
  `hasread` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `from_id` (`from_id`,`to_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_messages_sent`
--

LOCK TABLES `jcow_messages_sent` WRITE;
/*!40000 ALTER TABLE `jcow_messages_sent` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_messages_sent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_modules`
--

DROP TABLE IF EXISTS `jcow_modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_modules` (
  `name` varchar(50) NOT NULL DEFAULT '',
  `actived` tinyint(1) NOT NULL DEFAULT '0',
  `hooking` tinyint(4) NOT NULL DEFAULT '0',
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_modules`
--

LOCK TABLES `jcow_modules` WRITE;
/*!40000 ALTER TABLE `jcow_modules` DISABLE KEYS */;
INSERT INTO `jcow_modules` VALUES ('blogs',1,1),('photos',1,1),('videos',1,1),('browse',1,0),('feed',1,0),('dashboard',1,0),('account',1,0),('admin',1,0),('u',1,0),('member',1,0),('follow',1,0),('forumadmin',1,0),('friends',1,0),('jquery',1,0),('language',1,0),('message',1,0),('notifications',1,0),('preference',1,0),('report',1,0),('rss',1,0),('search',1,0),('invite',1,0);
/*!40000 ALTER TABLE `jcow_modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_page_users`
--

DROP TABLE IF EXISTS `jcow_page_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_page_users` (
  `pid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  KEY `pid` (`pid`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_page_users`
--

LOCK TABLES `jcow_page_users` WRITE;
/*!40000 ALTER TABLE `jcow_page_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_page_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_pages`
--

DROP TABLE IF EXISTS `jcow_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uri` varchar(30) NOT NULL,
  `uid` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `style_ids` text NOT NULL,
  `custom_css` text NOT NULL,
  `background` varchar(100) NOT NULL,
  `type` varchar(25) NOT NULL,
  `description` text NOT NULL,
  `users` int(11) NOT NULL,
  `updated` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `uri` (`uri`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_pages`
--

LOCK TABLES `jcow_pages` WRITE;
/*!40000 ALTER TABLE `jcow_pages` DISABLE KEYS */;
INSERT INTO `jcow_pages` VALUES (1,'admin',1,3,'','','','','','u','',0,1786543135),(2,'bima',2,4,'','','','','','u','',0,1786543706),(3,'giska',3,3,'','','','','','u','',0,1786543667);
/*!40000 ALTER TABLE `jcow_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_profile_comments`
--

DROP TABLE IF EXISTS `jcow_profile_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_profile_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `target_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created` int(11) NOT NULL,
  `stream_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `stream_id` (`stream_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_profile_comments`
--

LOCK TABLES `jcow_profile_comments` WRITE;
/*!40000 ALTER TABLE `jcow_profile_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_profile_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_profiles`
--

DROP TABLE IF EXISTS `jcow_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_profiles` (
  `id` int(11) NOT NULL,
  `style_ids` varchar(255) NOT NULL,
  `custom_css` text NOT NULL,
  `background` varchar(100) NOT NULL,
  `videoid` int(11) NOT NULL,
  `favorites` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_profiles`
--

LOCK TABLES `jcow_profiles` WRITE;
/*!40000 ALTER TABLE `jcow_profiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_reports`
--

DROP TABLE IF EXISTS `jcow_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `hasread` tinyint(1) NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_reports`
--

LOCK TABLES `jcow_reports` WRITE;
/*!40000 ALTER TABLE `jcow_reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_roles`
--

DROP TABLE IF EXISTS `jcow_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_roles` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_roles`
--

LOCK TABLES `jcow_roles` WRITE;
/*!40000 ALTER TABLE `jcow_roles` DISABLE KEYS */;
INSERT INTO `jcow_roles` VALUES (1,'Guest'),(2,'General member'),(3,'Administrator');
/*!40000 ALTER TABLE `jcow_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_stories`
--

DROP TABLE IF EXISTS `jcow_stories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_stories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0',
  `sticky` tinyint(4) NOT NULL,
  `closed` tinyint(4) NOT NULL,
  `title` varchar(120) NOT NULL DEFAULT '',
  `thumbnail` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL DEFAULT '0',
  `lastreply` int(11) NOT NULL DEFAULT '0',
  `lastreplyuname` varchar(50) NOT NULL,
  `lastreplyuid` int(11) NOT NULL,
  `updated` int(11) NOT NULL DEFAULT '0',
  `views` int(11) NOT NULL,
  `comments` int(11) NOT NULL,
  `stream_id` int(11) NOT NULL,
  `app` varchar(50) NOT NULL DEFAULT '',
  `digg` int(11) NOT NULL,
  `dugg` int(11) NOT NULL,
  `photos` int(11) NOT NULL,
  `tags` varchar(255) NOT NULL,
  `featured` tinyint(4) NOT NULL,
  `var1` varchar(255) NOT NULL DEFAULT '',
  `var2` varchar(255) NOT NULL DEFAULT '',
  `var3` varchar(255) NOT NULL DEFAULT '',
  `var4` varchar(255) NOT NULL DEFAULT '',
  `var5` varchar(255) NOT NULL DEFAULT '',
  `text1` text NOT NULL,
  `text2` text NOT NULL,
  `blob1` blob NOT NULL,
  `rating` text NOT NULL,
  `page_id` int(11) NOT NULL,
  `page_type` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `app` (`app`),
  KEY `uid` (`uid`),
  KEY `page_id` (`page_id`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=83 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_stories`
--

LOCK TABLES `jcow_stories` WRITE;
/*!40000 ALTER TABLE `jcow_stories` DISABLE KEYS */;
INSERT INTO `jcow_stories` VALUES (79,0,0,0,'lembong','','test',1,1786543135,0,'',0,0,3,0,2,'blogs',0,0,0,'',0,'','','','','0','','','','a:1:{s:6:\"rating\";a:2:{s:5:\"score\";i:0;s:5:\"users\";i:0;}}',1,'u'),(80,0,0,0,'ppp','','bambang',2,1786543251,0,'',0,0,1,0,3,'blogs',0,0,0,'',0,'','','','','0','','','','a:1:{s:6:\"rating\";a:2:{s:5:\"score\";i:0;s:5:\"users\";i:0;}}',2,'u'),(81,0,0,0,'halo','','hshshahahshs',2,1786543628,0,'',0,0,5,0,7,'blogs',0,0,0,'',0,'','','','','0','','','','a:1:{s:6:\"rating\";a:2:{s:5:\"score\";i:0;s:5:\"users\";i:0;}}',2,'u'),(82,0,0,0,'pppp','','cooo',3,1786543667,0,'',0,0,3,0,9,'blogs',0,0,0,'#r',0,'','','','','0','','','','a:1:{s:6:\"rating\";a:2:{s:5:\"score\";i:0;s:5:\"users\";i:0;}}',3,'u');
/*!40000 ALTER TABLE `jcow_stories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_story_cat_groups`
--

DROP TABLE IF EXISTS `jcow_story_cat_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_story_cat_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `app` varchar(50) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_story_cat_groups`
--

LOCK TABLES `jcow_story_cat_groups` WRITE;
/*!40000 ALTER TABLE `jcow_story_cat_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_story_cat_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_story_categories`
--

DROP TABLE IF EXISTS `jcow_story_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_story_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL,
  `name` varchar(150) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `weight` int(11) NOT NULL DEFAULT '0',
  `app` varchar(50) NOT NULL DEFAULT '',
  `var1` varchar(255) NOT NULL,
  `var2` varchar(255) NOT NULL,
  `var3` varchar(255) NOT NULL,
  `var4` varchar(255) NOT NULL,
  `var5` varchar(255) NOT NULL,
  `uri` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `app` (`app`),
  KEY `weight` (`weight`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_story_categories`
--

LOCK TABLES `jcow_story_categories` WRITE;
/*!40000 ALTER TABLE `jcow_story_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_story_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_story_photos`
--

DROP TABLE IF EXISTS `jcow_story_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_story_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL,
  `uri` varchar(100) NOT NULL,
  `des` varchar(255) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `size` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_story_photos`
--

LOCK TABLES `jcow_story_photos` WRITE;
/*!40000 ALTER TABLE `jcow_story_photos` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_story_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_streams`
--

DROP TABLE IF EXISTS `jcow_streams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_streams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `wall_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `attachment` text NOT NULL,
  `created` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `app` varchar(20) NOT NULL,
  `aid` int(11) NOT NULL,
  `hide` tinyint(1) NOT NULL,
  `likes` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `app` (`app`),
  KEY `aid` (`aid`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_streams`
--

LOCK TABLES `jcow_streams` WRITE;
/*!40000 ALTER TABLE `jcow_streams` DISABLE KEYS */;
INSERT INTO `jcow_streams` VALUES (1,'Signed Up',2,2,'',1786542956,0,'',0,0,1),(2,'added a blog post',1,1,'a:4:{s:3:\"uri\";s:18:\"blogs/viewstory/79\";s:4:\"name\";s:7:\"lembong\";s:5:\"thumb\";a:1:{i:0;s:0:\"\";}s:3:\"des\";N;}',1786543135,0,'blogs',0,0,0),(3,'added a blog',2,2,'a:3:{s:8:\"cwall_id\";s:7:\"blogs80\";s:3:\"uri\";s:18:\"blogs/viewstory/80\";s:4:\"name\";s:3:\"ppp\";}',1786543251,0,'blogs',80,0,0),(4,'Signed Up',3,3,'',1786543359,0,'',0,0,0),(5,'halo warga ',3,3,'',1786543388,0,'',0,0,1),(6,'woyyyyy ',2,2,'',1786543600,0,'',0,0,0),(7,'added a blog',2,2,'a:3:{s:8:\"cwall_id\";s:7:\"blogs81\";s:3:\"uri\";s:18:\"blogs/viewstory/81\";s:4:\"name\";s:4:\"halo\";}',1786543628,0,'blogs',81,0,1),(8,'became a friend of <a href=\"https://xcode-friends.bennedistus.web.id/index.php?p=u/admin\">admin</a>',3,3,'',1786543633,0,'',0,0,0),(9,'added a blog post',3,3,'a:4:{s:3:\"uri\";s:18:\"blogs/viewstory/82\";s:4:\"name\";s:4:\"pppp\";s:5:\"thumb\";a:1:{i:0;s:0:\"\";}s:3:\"des\";N;}',1786543667,0,'blogs',0,0,0),(10,'became a friend of <a href=\"https://xcode-friends.bennedistus.web.id/index.php?p=u/giska\">giska</a>',2,2,'',1786543706,0,'',0,0,0);
/*!40000 ALTER TABLE `jcow_streams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_subscr`
--

DROP TABLE IF EXISTS `jcow_subscr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_subscr` (
  `id` varchar(32) NOT NULL,
  `item_number` varchar(32) NOT NULL,
  `status` varchar(25) NOT NULL,
  `uid` int(11) NOT NULL,
  `timeline` int(11) NOT NULL,
  KEY `id` (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_subscr`
--

LOCK TABLES `jcow_subscr` WRITE;
/*!40000 ALTER TABLE `jcow_subscr` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_subscr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_tag_ids`
--

DROP TABLE IF EXISTS `jcow_tag_ids`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_tag_ids` (
  `tid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  KEY `tid` (`tid`,`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_tag_ids`
--

LOCK TABLES `jcow_tag_ids` WRITE;
/*!40000 ALTER TABLE `jcow_tag_ids` DISABLE KEYS */;
INSERT INTO `jcow_tag_ids` VALUES (17,82);
/*!40000 ALTER TABLE `jcow_tag_ids` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_tags`
--

DROP TABLE IF EXISTS `jcow_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `app` varchar(25) NOT NULL,
  `num` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_tags`
--

LOCK TABLES `jcow_tags` WRITE;
/*!40000 ALTER TABLE `jcow_tags` DISABLE KEYS */;
INSERT INTO `jcow_tags` VALUES (17,'#r','blogs',1);
/*!40000 ALTER TABLE `jcow_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_texts`
--

DROP TABLE IF EXISTS `jcow_texts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_texts` (
  `tkey` varchar(50) NOT NULL,
  `tvalue` text NOT NULL,
  KEY `tkey` (`tkey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_texts`
--

LOCK TABLES `jcow_texts` WRITE;
/*!40000 ALTER TABLE `jcow_texts` DISABLE KEYS */;
INSERT INTO `jcow_texts` VALUES ('welcome_pm','Hello %username%!\r\nThank you for your registeration!\r\nPlease invite your friends to join our community.'),('welcome_email','Dear %username%,\r\nWelcome to %sitelink%!\r\nYour login email is: %email%\r\nOur URL is:\r\n%sitelink%'),('welcome_msg','Welcome to our Community!'),('rules_conditions','none'),('footermsg','Your footer here..'),('locations','Afghanistan  \r\nAlbania  \r\nAlgeria  \r\nAmerican Samoa  \r\nAndorra  \r\nAngola  \r\nAnguilla  \r\nAntarctica  \r\nAntigua and Barbuda  \r\nArgentina  \r\nArmenia  \r\nAruba  \r\nAustralia  \r\nAustria  \r\nAzerbaidjan  \r\nBahamas  \r\nBahrain  \r\nBangladesh  \r\nBarbados  \r\nBelarus  \r\nBelgium  \r\nBelize  \r\nBenin  \r\nBermuda  \r\nBhutan  \r\nBolivia  \r\nBosnia-Herzegovina  \r\nBotswana  \r\nBouvet Island  \r\nBrazil  \r\nBrunei Darussalam  \r\nBulgaria  \r\nBurkina Faso  \r\nBurundi  \r\nCambodia  \r\nCameroon  \r\nCanada  \r\nCape Verde  \r\nCayman Islands  \r\nCentral African Republic  \r\nChad  \r\nChile  \r\nChina  \r\nChristmas Island  \r\nCocos Islands  \r\nColombia  \r\nComoros  \r\nCongo  \r\nCook Islands  \r\nCosta Rica  \r\nCroatia  \r\nCuba  \r\nCyprus  \r\nCzech Republic  \r\nDenmark  \r\nDjibouti  \r\nDominica  \r\nDominican Republic  \r\nEast Timor  \r\nEcuador  \r\nEgypt  \r\nEl Salvador  \r\nEquatorial Guinea  \r\nEstonia  \r\nEthiopia  \r\nFalkland Islands  \r\nFaroe Islands  \r\nFiji  \r\nFinland  \r\nFormer Czechoslovakia  \r\nFrance  \r\nFrench Guyana  \r\nGabon  \r\nGambia  \r\nGeorgia  \r\nGermany  \r\nGhana  \r\nGibraltar  \r\nGreat Britain  \r\nGreece  \r\nGreenland  \r\nGrenada  \r\nGuadeloupe  \r\nGuam  \r\nGuatemala  \r\nGuinea  \r\nGuinea Bissau  \r\nGuyana  \r\nHaiti  \r\nHonduras  \r\nHong Kong  \r\nHungary  \r\nIceland  \r\nIndia  \r\nIndonesia  \r\nIran  \r\nIraq  \r\nIreland  \r\nIsrael  \r\nItaly  \r\nIvory Coast  \r\nJamaica  \r\nJapan  \r\nJordan  \r\nKazakhstan  \r\nKenya  \r\nKiribati  \r\nKuwait  \r\nKyrgyzstan  \r\nLaos  \r\nLatvia  \r\nLebanon  \r\nLesotho  \r\nLiberia  \r\nLibya  \r\nLiechtenstein  \r\nLithuania  \r\nLuxembourg  \r\nMacau  \r\nMacedonia  \r\nMadagascar  \r\nMalawi  \r\nMalaysia  \r\nMaldives  \r\nMali  \r\nMalta  \r\nMarshall Islands  \r\nMartinique  \r\nMauritania  \r\nMauritius  \r\nMayotte  \r\nMexico  \r\nMicronesia  \r\nMoldavia  \r\nMonaco  \r\nMongolia  \r\nMontserrat  \r\nMorocco  \r\nMozambique  \r\nMyanmar  \r\nNamibia  \r\nNauru  \r\nNepal  \r\nNetherlands  \r\nNetherlands Antilles  \r\nNeutral Zone  \r\nNew Caledonia  \r\nNew Zealand  \r\nNicaragua  \r\nNiger  \r\nNigeria  \r\nNiue  \r\nNorfolk Island  \r\nNorth Korea  \r\nNorway  \r\nOman  \r\nPakistan  \r\nPalau  \r\nPanama  \r\nPapua New Guinea  \r\nParaguay  \r\nPeru  \r\nPhilippines  \r\nPitcairn Island  \r\nPoland  \r\nPolynesia  \r\nPortugal  \r\nPuerto Rico  \r\nQatar  \r\nReunion  \r\nRomania  \r\nRussian Federation  \r\nRwanda  \r\nSaint Helena  \r\nSaint Lucia  \r\nSaint Vincent and Grenadines  \r\nSamoa  \r\nSan Marino  \r\nSaudi Arabia  \r\nSenegal  \r\nSeychelles  \r\nSierra Leone  \r\nSingapore  \r\nSlovak Republic  \r\nSlovenia  \r\nSolomon Islands  \r\nSomalia  \r\nSouth Africa  \r\nSouth Korea  \r\nSpain  \r\nSri Lanka  \r\nSudan  \r\nSuriname  \r\nSwaziland  \r\nSweden  \r\nSwitzerland  \r\nSyria  \r\nTadjikistan  \r\nTaiwan  \r\nTanzania  \r\nThailand  \r\nTogo  \r\nTokelau  \r\nTonga  \r\nTrinidad and Tobago  \r\nTunisia  \r\nTurkey  \r\nTurkmenistan  \r\nTuvalu  \r\nUganda  \r\nUkraine  \r\nUnited Arab Emirates  \r\nUnited Kingdom  \r\nUnited States  \r\nUruguay  \r\nUzbekistan  \r\nVanuatu  \r\nVatican City State  \r\nVenezuela  \r\nVietnam  \r\nVirgin Islands (British)  \r\nVirgin Islands (USA)  \r\nWallis and Futuna Islands  \r\nWestern Sahara  \r\nYemen  \r\nYugoslavia  \r\nZaire  \r\nZambia  \r\nZimbabwe');
/*!40000 ALTER TABLE `jcow_texts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_tmp`
--

DROP TABLE IF EXISTS `jcow_tmp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_tmp` (
  `tkey` varchar(70) NOT NULL,
  `tcontent` text NOT NULL,
  KEY `tkey` (`tkey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_tmp`
--

LOCK TABLES `jcow_tmp` WRITE;
/*!40000 ALTER TABLE `jcow_tmp` DISABLE KEYS */;
INSERT INTO `jcow_tmp` VALUES ('login172.71.82.150','1');
/*!40000 ALTER TABLE `jcow_tmp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_user_crafts`
--

DROP TABLE IF EXISTS `jcow_user_crafts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_user_crafts` (
  `uid` int(11) NOT NULL,
  `hash` varchar(5) NOT NULL,
  `created` int(11) NOT NULL,
  KEY `uid` (`uid`,`created`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_user_crafts`
--

LOCK TABLES `jcow_user_crafts` WRITE;
/*!40000 ALTER TABLE `jcow_user_crafts` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_user_crafts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_var_cache`
--

DROP TABLE IF EXISTS `jcow_var_cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_var_cache` (
  `name` varchar(60) NOT NULL,
  `content` varchar(255) NOT NULL,
  `created` int(11) NOT NULL,
  KEY `name` (`name`,`created`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_var_cache`
--

LOCK TABLES `jcow_var_cache` WRITE;
/*!40000 ALTER TABLE `jcow_var_cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_var_cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jcow_votes`
--

DROP TABLE IF EXISTS `jcow_votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jcow_votes` (
  `sid` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `rate` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  KEY `sid` (`sid`,`uid`),
  KEY `created` (`created`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jcow_votes`
--

LOCK TABLES `jcow_votes` WRITE;
/*!40000 ALTER TABLE `jcow_votes` DISABLE KEYS */;
/*!40000 ALTER TABLE `jcow_votes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-13  1:25:10
