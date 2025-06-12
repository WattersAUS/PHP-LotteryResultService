/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.11-MariaDB, for debian-linux-gnu (aarch64)
--
-- Host: localhost    Database: shinyide2_lottery
-- ------------------------------------------------------
-- Server version	10.11.11-MariaDB-0+deb12u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `access`
--

DROP TABLE IF EXISTS `access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `access` (
  `ident` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `token` char(36) NOT NULL,
  `requests_per_period` int(11) NOT NULL DEFAULT 0,
  `time_period` int(11) NOT NULL DEFAULT 0,
  `created_when` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `end_dated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ident`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`shinyide2_user`@`localhost`*/ /*!50003 TRIGGER `insert_access` BEFORE INSERT ON `access` FOR EACH ROW BEGIN
        SET new.token = uuid();
    END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `check_draws`
--

DROP TABLE IF EXISTS `check_draws`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `check_draws` (
  `ident` int(11) NOT NULL AUTO_INCREMENT,
  `user_ident` int(11) NOT NULL,
  `lottery_ident` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `last_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`ident`) USING BTREE,
  KEY `check_draws_ibfk_1` (`user_ident`),
  KEY `check_draws_ibfk_2` (`lottery_ident`),
  CONSTRAINT `check_draws_ibfk_1` FOREIGN KEY (`user_ident`) REFERENCES `check_user` (`ident`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `check_draws_ibfk_2` FOREIGN KEY (`lottery_ident`) REFERENCES `lottery_draws` (`ident`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `check_numbers`
--

DROP TABLE IF EXISTS `check_numbers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `check_numbers` (
  `check_ident` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `is_special` tinyint(1) NOT NULL,
  PRIMARY KEY (`check_ident`,`number`,`is_special`) USING BTREE,
  KEY `check_numbers_ibfk_1` (`check_ident`),
  CONSTRAINT `check_numbers_ibfk_1` FOREIGN KEY (`check_ident`) REFERENCES `check_draws` (`ident`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `check_user`
--

DROP TABLE IF EXISTS `check_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `check_user` (
  `ident` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `last_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`ident`) USING BTREE,
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `draw_history`
--

DROP TABLE IF EXISTS `draw_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `draw_history` (
  `ident` int(11) NOT NULL,
  `draw` int(11) NOT NULL,
  `draw_date` date NOT NULL,
  `checked` tinyint(1) NOT NULL DEFAULT 0,
  `last_modified` datetime NOT NULL,
  PRIMARY KEY (`ident`,`draw`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `logger`
--

DROP TABLE IF EXISTS `logger`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `logger` (
  `ident` int(11) NOT NULL,
  `seqnum` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  UNIQUE KEY `seqnum` (`seqnum`),
  UNIQUE KEY `ident_seqnum` (`ident`,`seqnum`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=58179 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lottery_draws`
--

DROP TABLE IF EXISTS `lottery_draws`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `lottery_draws` (
  `ident` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(64) NOT NULL,
  `draw` int(11) NOT NULL,
  `numbers` int(11) NOT NULL,
  `upper_number` int(11) NOT NULL,
  `numbers_tag` varchar(32) NOT NULL,
  `specials` int(11) NOT NULL,
  `upper_special` int(11) NOT NULL,
  `specials_tag` varchar(32) NOT NULL,
  `is_bonus` tinyint(1) NOT NULL,
  `base_url` varchar(512) NOT NULL,
  `last_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`ident`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `number_usage`
--

DROP TABLE IF EXISTS `number_usage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `number_usage` (
  `ident` int(11) NOT NULL,
  `draw` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `is_special` tinyint(1) NOT NULL,
  PRIMARY KEY (`ident`,`draw`,`number`,`is_special`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `request_history`
--

DROP TABLE IF EXISTS `request_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `request_history` (
  `accessed` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `remote` varchar(256) NOT NULL,
  `access_ident` int(11) DEFAULT NULL,
  KEY `request_history_idx1` (`access_ident`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping routines for database 'shinyide2_lottery'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-12  9:28:54
