mysqldump: [Warning] Using a password on the command line interface can be insecure.
-- MySQL dump 10.13  Distrib 5.7.33, for Linux (x86_64)
--
-- Host: localhost    Database: Dead
-- ------------------------------------------------------
-- Server version	5.7.33-0ubuntu0.16.04.1

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
-- Table structure for table `GoalHistory`
--

DROP TABLE IF EXISTS `GoalHistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `GoalHistory` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goalID` int(11) unsigned NOT NULL,
  `kind` enum('comment','auto') DEFAULT NULL,
  `userID` int(11) unsigned DEFAULT NULL,
  `text` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `goalID` (`goalID`),
  KEY `userID` (`userID`),
  CONSTRAINT `goalhistory_ibfk_1` FOREIGN KEY (`goalID`) REFERENCES `Goals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `goalhistory_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `Users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `GoalHistory`
--

LOCK TABLES `GoalHistory` WRITE;
/*!40000 ALTER TABLE `GoalHistory` DISABLE KEYS */;
INSERT INTO `GoalHistory` VALUES (1,1,'auto',1,'goal created','2022-01-30 10:18:25'),(2,2,'auto',1,'goal created','2022-01-30 10:18:40'),(3,3,'auto',1,'goal created','2022-01-30 10:19:33'),(7,3,'auto',1,'goal updated','2022-01-30 10:20:56'),(8,5,'auto',1,'goal created','2022-01-30 10:21:15'),(13,8,'auto',1,'goal created','2022-01-30 10:30:31'),(14,9,'auto',1,'goal created','2022-01-30 10:31:00'),(15,10,'auto',1,'goal created','2022-01-30 10:31:32'),(16,11,'auto',1,'goal created','2022-01-30 10:35:13'),(17,11,'auto',1,'goal updated','2022-01-30 10:35:18'),(18,8,'auto',1,'goal updated','2022-01-30 10:35:30'),(19,12,'auto',1,'goal created','2022-01-30 16:38:22'),(21,10,'auto',1,'goal updated','2022-01-30 18:17:28');
/*!40000 ALTER TABLE `GoalHistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Goals`
--

DROP TABLE IF EXISTS `Goals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Goals` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) unsigned NOT NULL,
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `priority` int(8) unsigned NOT NULL DEFAULT '4',
  `brokenDown` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`),
  CONSTRAINT `goals_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `Users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Goals`
--

LOCK TABLES `Goals` WRITE;
/*!40000 ALTER TABLE `Goals` DISABLE KEYS */;
INSERT INTO `Goals` VALUES (1,1,'Make geta with Audrey',4,0),(2,1,'Design a videogame with Ethan',4,0),(3,1,'Go to TN for Spring Break 2022',3,0),(5,1,'Make a website',3,0),(8,1,'Learn Wing Chun',2,0),(9,1,'Study at a Yeshiva',1,0),(10,1,'Weekend hermetic monk!',1,0),(11,1,'Dates with Amber',3,0),(12,1,'Take a walk and feed ducks',4,0);
/*!40000 ALTER TABLE `Goals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `GoalsVisibleToUser`
--

DROP TABLE IF EXISTS `GoalsVisibleToUser`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `GoalsVisibleToUser` (
  `looker` int(11) unsigned NOT NULL,
  `lookee` int(11) unsigned NOT NULL,
  PRIMARY KEY (`looker`,`lookee`),
  KEY `lookee` (`lookee`),
  CONSTRAINT `goalsvisibletouser_ibfk_1` FOREIGN KEY (`looker`) REFERENCES `Users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `goalsvisibletouser_ibfk_2` FOREIGN KEY (`lookee`) REFERENCES `Users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `GoalsVisibleToUser`
--

LOCK TABLES `GoalsVisibleToUser` WRITE;
/*!40000 ALTER TABLE `GoalsVisibleToUser` DISABLE KEYS */;
/*!40000 ALTER TABLE `GoalsVisibleToUser` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Migration`
--

DROP TABLE IF EXISTS `Migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Migration` (
  `version` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Migration`
--

LOCK TABLES `Migration` WRITE;
/*!40000 ALTER TABLE `Migration` DISABLE KEYS */;
INSERT INTO `Migration` VALUES (2,'test'),(7,'busted');
/*!40000 ALTER TABLE `Migration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Steps`
--

DROP TABLE IF EXISTS `Steps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Steps` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goalID` int(11) unsigned NOT NULL,
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `priority` int(8) unsigned NOT NULL DEFAULT '4',
  `state` enum('inwork','ready','blocked','complete') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `goalID` (`goalID`),
  CONSTRAINT `steps_ibfk_1` FOREIGN KEY (`goalID`) REFERENCES `Goals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Steps`
--

LOCK TABLES `Steps` WRITE;
/*!40000 ALTER TABLE `Steps` DISABLE KEYS */;
INSERT INTO `Steps` VALUES (1,5,'Handle htmlspecialchars',3,'ready'),(10,8,'Search near me for schools',1,'ready'),(11,12,'Rouse the troops',1,'complete'),(12,12,'Do it',1,'ready'),(13,12,'Bring bread',1,'ready');
/*!40000 ALTER TABLE `Steps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userName` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `superuser` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Users`
--

LOCK TABLES `Users` WRITE;
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
INSERT INTO `Users` VALUES (1,'McDaddy','$2y$10$r7fb3j4cOBtCmYKTU2Kwk.kc1Sl5KqK6XrBprKUN4bOZ2Br0YkqFS',1);
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Version`
--

DROP TABLE IF EXISTS `Version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Version` (
  `version` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Version`
--

LOCK TABLES `Version` WRITE;
/*!40000 ALTER TABLE `Version` DISABLE KEYS */;
INSERT INTO `Version` VALUES (7);
/*!40000 ALTER TABLE `Version` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-01-30 23:58:11
