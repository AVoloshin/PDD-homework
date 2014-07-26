CREATE DATABASE  IF NOT EXISTS `vdn_forum` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `vdn_forum`;
-- MySQL dump 10.13  Distrib 5.6.17, for Win32 (x86)
--
-- Host: localhost    Database: vdn_forum
-- ------------------------------------------------------
-- Server version	5.6.19

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
-- Table structure for table `vdn_messages`
--

DROP TABLE IF EXISTS `vdn_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vdn_messages` (
  `id_message` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id сообщения',
  `id_theme` int(11) NOT NULL COMMENT 'id темы',
  `id_user` int(11) NOT NULL COMMENT 'id пользователя',
  `message` text NOT NULL COMMENT 'сообщение',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'дата отправленного сообщения',
  PRIMARY KEY (`id_message`),
  KEY `fk_vdn_messeges_2_vdn_themes_idx` (`id_theme`),
  KEY `fk_vdn_messeges_2_vdn_users_idx` (`id_user`),
  CONSTRAINT `fk_vdn_messeges_2_vdn_themes` FOREIGN KEY (`id_theme`) REFERENCES `vdn_themes` (`id_theme`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_vdn_messeges_2_vdn_users` FOREIGN KEY (`id_user`) REFERENCES `vdn_users` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='сообщения пользователей';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vdn_messages`
--

LOCK TABLES `vdn_messages` WRITE;
/*!40000 ALTER TABLE `vdn_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `vdn_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vdn_profiles`
--

DROP TABLE IF EXISTS `vdn_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vdn_profiles` (
  `id_profile` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id профиля',
  `id_user` int(11) NOT NULL COMMENT 'id пользователя',
  `id_status` int(11) NOT NULL COMMENT 'статус пользователя',
  `last_name` varchar(45) DEFAULT NULL COMMENT 'фамилия',
  `first_name` varchar(45) DEFAULT NULL COMMENT 'имя',
  `gender` varchar(45) DEFAULT NULL COMMENT 'пол',
  `date_birth` varchar(45) DEFAULT NULL COMMENT 'дата рождения',
  `photo` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_profile`),
  UNIQUE KEY `id_profile_UNIQUE` (`id_profile`),
  UNIQUE KEY `id_user_UNIQUE` (`id_user`),
  KEY `fk_vdn_profiles_2_vdn_status_idx` (`id_status`),
  CONSTRAINT `fk_vdn_profiles_2_vdn_status` FOREIGN KEY (`id_status`) REFERENCES `vdn_status` (`id_status`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_vdn_profiles_vdn_users1` FOREIGN KEY (`id_user`) REFERENCES `vdn_users` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='профили пользователей';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vdn_profiles`
--

LOCK TABLES `vdn_profiles` WRITE;
/*!40000 ALTER TABLE `vdn_profiles` DISABLE KEYS */;
INSERT INTO `vdn_profiles` VALUES (1,1,1,'Vechorko','Dima','M','22/04/1977','belarus.png'),(2,2,2,'Ivanivisch','Ivan','M','03/08/1986',NULL);
/*!40000 ALTER TABLE `vdn_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vdn_status`
--

DROP TABLE IF EXISTS `vdn_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vdn_status` (
  `id_status` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id статуса',
  `status_name` varchar(45) NOT NULL COMMENT 'статус пользователя',
  PRIMARY KEY (`id_status`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='статус пользователей';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vdn_status`
--

LOCK TABLES `vdn_status` WRITE;
/*!40000 ALTER TABLE `vdn_status` DISABLE KEYS */;
INSERT INTO `vdn_status` VALUES (1,'admin'),(2,'user'),(3,'banned');
/*!40000 ALTER TABLE `vdn_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vdn_themes`
--

DROP TABLE IF EXISTS `vdn_themes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vdn_themes` (
  `id_theme` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id темы',
  `id_user` int(11) NOT NULL COMMENT 'id пользователя создавшего тему',
  `name_theme` varchar(45) DEFAULT NULL COMMENT 'название темы',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'дата создания темы',
  PRIMARY KEY (`id_theme`),
  UNIQUE KEY `id_theme_UNIQUE` (`id_theme`),
  KEY `fk_vdn_themes_2_vdn_users_idx` (`id_user`),
  CONSTRAINT `fk_vdn_themes_2_vdn_users` FOREIGN KEY (`id_user`) REFERENCES `vdn_users` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='темы созданные пользователем';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vdn_themes`
--

LOCK TABLES `vdn_themes` WRITE;
/*!40000 ALTER TABLE `vdn_themes` DISABLE KEYS */;
/*!40000 ALTER TABLE `vdn_themes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vdn_users`
--

DROP TABLE IF EXISTS `vdn_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vdn_users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) DEFAULT NULL COMMENT 'логин пользователя',
  `password` varchar(255) NOT NULL COMMENT 'пароль пользователя',
  `date_registration` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'дата регистрации',
  `id_status` int(11) NOT NULL COMMENT 'статус пользователя',
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `id_user_UNIQUE` (`id_user`),
  KEY `fk_vdn_users_2_vdn_status_idx` (`id_status`),
  CONSTRAINT `fk_vdn_users_2_vdn_status` FOREIGN KEY (`id_status`) REFERENCES `vdn_status` (`id_status`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='зарегестрированные пользователи';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vdn_users`
--

LOCK TABLES `vdn_users` WRITE;
/*!40000 ALTER TABLE `vdn_users` DISABLE KEYS */;
INSERT INTO `vdn_users` VALUES (1,'dima','40bd001563085fc35165329ea1ff5c5ecbdbbeef','2014-07-17 12:38:50',1),(2,'ivan','11111111','2014-07-14 20:42:18',2),(3,'sveta','111111','2014-07-14 20:42:18',2),(4,'pety','11111','2014-07-14 20:42:18',3),(23,'Dinis','2ea6201a068c5fa0eea5d81a3863321a87f8d533','2014-07-15 08:07:24',2),(24,'Dini','2ea6201a068c5fa0eea5d81a3863321a87f8d533','2014-07-15 08:42:15',2),(29,'name','8cb2237d0679ca88db6464eac60da96345513964','2014-07-15 17:40:44',2),(30,'diam','40bd001563085fc35165329ea1ff5c5ecbdbbeef','2014-07-16 09:31:58',2),(31,'qwe','40bd001563085fc35165329ea1ff5c5ecbdbbeef','2014-07-16 09:46:21',2),(32,'qwerty','40bd001563085fc35165329ea1ff5c5ecbdbbeef','2014-07-16 10:55:48',2);
/*!40000 ALTER TABLE `vdn_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-07-17 20:28:09
