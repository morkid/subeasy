-- MySQL dump 10.13  Distrib 5.5.25a, for Linux (i686)
--
-- Host: localhost    Database: subeasy
-- ------------------------------------------------------
-- Server version	5.5.25a

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
-- Table structure for table `se_collection`
--

DROP TABLE IF EXISTS `se_collection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `se_collection` (
  `collection_id` int(20) NOT NULL AUTO_INCREMENT,
  `collection_movie` varchar(100) DEFAULT NULL,
  `collection_language` varchar(50) DEFAULT NULL,
  `collection_filename` varchar(100) DEFAULT NULL,
  `collection_length` int(12) DEFAULT NULL,
  `collection_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`collection_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `se_collection`
--

LOCK TABLES `se_collection` WRITE;
/*!40000 ALTER TABLE `se_collection` DISABLE KEYS */;
/*!40000 ALTER TABLE `se_collection` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `se_subtitle`
--

DROP TABLE IF EXISTS `se_subtitle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `se_subtitle` (
  `subtitle_id` int(20) NOT NULL AUTO_INCREMENT,
  `subtitle_index` int(8) DEFAULT NULL,
  `subtitle_color` varchar(7) DEFAULT NULL,
  `subtitle_text` text,
  `subtitle_start` int(12) DEFAULT NULL,
  `subtitle_end` int(12) DEFAULT NULL,
  `collection_id` int(20) DEFAULT NULL,
  PRIMARY KEY (`subtitle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `se_subtitle`
--

LOCK TABLES `se_subtitle` WRITE;
/*!40000 ALTER TABLE `se_subtitle` DISABLE KEYS */;
/*!40000 ALTER TABLE `se_subtitle` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-05-10  1:49:56
