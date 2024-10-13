-- MySQL dump 10.13  Distrib 8.0.36, for Linux (x86_64)
--
-- Host: localhost    Database: app
-- ------------------------------------------------------
-- Server version	8.0.36-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `article`
--

DROP TABLE IF EXISTS `article`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `article` (
  `id` int NOT NULL AUTO_INCREMENT,
  `section_id` int DEFAULT NULL,
  `pmid` int DEFAULT NULL,
  `doi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auteur_principale` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auteurs` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `affiliation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pdf_file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `moyenne` double DEFAULT NULL,
  `publication_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `journal_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_23A0E66D823E37A` (`section_id`),
  CONSTRAINT `FK_23A0E66D823E37A` FOREIGN KEY (`section_id`) REFERENCES `section` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `article`
--

LOCK TABLES `article` WRITE;
/*!40000 ALTER TABLE `article` DISABLE KEYS */;
INSERT INTO `article` VALUES (2,2,5786906,'478999U0','Titre','Ahmed ','Koubaa Ahmed','Médecine','dummy-65e1b23cb4ca2.pdf',1.1666666666667,NULL,NULL),(3,3,4567890,'sdhvsflkdml','kjsdfsjdfbk','skdjfksdjf','fkjdsqfhksdjfk','kjsdfksjdfbs','dummy-65f6e503eeb8c.pdf',4.4615384615385,NULL,NULL),(4,3,23456789,'qkusgfiqfgb','uqhskfjsdkfjg','ufqskufgskdufg','iugkqdugfisufg','iqugdfisdugf','dummy-65e1a90e173a6-65f6e51415efd.pdf',5,NULL,NULL);
/*!40000 ALTER TABLE `article` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `criteres`
--

DROP TABLE IF EXISTS `criteres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `criteres` (
  `id` int NOT NULL AUTO_INCREMENT,
  `intitule` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `coefficient` int NOT NULL,
  `grille_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E913A5C5985C2966` (`grille_id`),
  CONSTRAINT `FK_E913A5C5985C2966` FOREIGN KEY (`grille_id`) REFERENCES `grille` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `criteres`
--

LOCK TABLES `criteres` WRITE;
/*!40000 ALTER TABLE `criteres` DISABLE KEYS */;
INSERT INTO `criteres` VALUES (1,'Topic\'s importance to Medical and Health Informatics',5,1),(2,'Scientific and/or practical impact of the paper to the topic',5,1),(3,'Quality of scientific and/or technical content',5,1),(4,'Originality and innovativeness',5,1),(5,'Coverage of related literature',5,1),(6,'Organisation and clarity of presentation',5,1),(7,'Include in Yearbook',4,1);
/*!40000 ALTER TABLE `criteres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evaluation`
--

DROP TABLE IF EXISTS `evaluation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `evaluation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `article_id` int NOT NULL,
  `user_id` int NOT NULL,
  `grille_id` int NOT NULL,
  `notes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `moyenne` double NOT NULL,
  `commentaire` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `submite` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1323A5757294869C` (`article_id`),
  KEY `IDX_1323A575A76ED395` (`user_id`),
  KEY `IDX_1323A575985C2966` (`grille_id`),
  CONSTRAINT `FK_1323A5757294869C` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`),
  CONSTRAINT `FK_1323A575985C2966` FOREIGN KEY (`grille_id`) REFERENCES `grille` (`id`),
  CONSTRAINT `FK_1323A575A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evaluation`
--

LOCK TABLES `evaluation` WRITE;
/*!40000 ALTER TABLE `evaluation` DISABLE KEYS */;
INSERT INTO `evaluation` VALUES (1,3,16,1,'a:7:{i:0;s:1:\"5\";i:1;s:1:\"3\";i:2;s:1:\"5\";i:3;s:1:\"5\";i:4;s:1:\"5\";i:5;s:1:\"1\";i:6;s:3:\"YES\";}',4,'yes',1),(2,3,16,1,'a:7:{i:0;s:1:\"2\";i:1;s:1:\"3\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";i:5;s:1:\"1\";i:6;s:3:\"YES\";}',4,'yes',1),(3,3,16,1,'a:7:{i:0;s:1:\"2\";i:1;s:1:\"3\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"3\";i:5;s:1:\"1\";i:6;s:3:\"YES\";}',4,'yes',1),(4,3,16,1,'a:7:{i:0;s:1:\"4\";i:1;s:1:\"2\";i:2;s:1:\"2\";i:3;s:1:\"1\";i:4;s:1:\"1\";i:5;s:1:\"2\";i:6;s:9:\"Maybe Yes\";}',4,'hch',1),(5,3,16,1,'a:7:{i:0;s:1:\"4\";i:1;s:1:\"2\";i:2;s:1:\"2\";i:3;s:1:\"1\";i:4;s:1:\"1\";i:5;s:1:\"2\";i:6;s:9:\"Maybe Yes\";}',4,'hch',1),(6,3,16,1,'a:7:{i:0;s:1:\"1\";i:1;s:1:\"1\";i:2;s:1:\"1\";i:3;s:1:\"1\";i:4;s:1:\"1\";i:5;s:1:\"1\";i:6;s:3:\"YES\";}',4,'dfkjh',1),(7,3,22,1,'a:7:{i:0;s:1:\"1\";i:1;s:1:\"1\";i:2;s:1:\"1\";i:3;s:1:\"1\";i:4;s:1:\"1\";i:5;s:1:\"1\";i:6;s:3:\"YES\";}',4,'fhfkj',1),(8,3,22,1,'a:7:{i:0;s:1:\"1\";i:1;s:1:\"1\";i:2;s:1:\"1\";i:3;s:1:\"1\";i:4;s:1:\"1\";i:5;s:1:\"1\";i:6;s:3:\"YES\";}',6,'fhfkj',1),(9,3,22,1,'a:7:{i:0;s:1:\"1\";i:1;s:1:\"1\";i:2;s:1:\"1\";i:3;s:1:\"1\";i:4;s:1:\"1\";i:5;s:1:\"1\";i:6;s:3:\"YES\";}',4,'fhfkj',1),(12,3,25,1,'a:7:{i:0;s:1:\"1\";i:1;s:1:\"1\";i:2;s:1:\"1\";i:3;s:1:\"1\";i:4;s:1:\"1\";i:5;s:1:\"1\";i:6;s:9:\"Maybe Yes\";}',5,'hgsdh',1),(13,3,25,1,'a:7:{i:0;s:1:\"1\";i:1;s:1:\"1\";i:2;s:1:\"1\";i:3;s:1:\"1\";i:4;s:1:\"1\";i:5;s:1:\"1\";i:6;s:9:\"Maybe Yes\";}',5,'hgsdh',1),(14,3,25,1,'a:7:{i:0;s:1:\"1\";i:1;s:1:\"1\";i:2;s:1:\"1\";i:3;s:1:\"1\";i:4;s:1:\"1\";i:5;s:1:\"1\";i:6;s:9:\"Maybe Yes\";}',5,'hgsdh',1),(15,3,25,1,'a:7:{i:0;s:1:\"1\";i:1;s:1:\"1\";i:2;s:1:\"1\";i:3;s:1:\"1\";i:4;s:1:\"1\";i:5;s:1:\"1\";i:6;s:9:\"Maybe Yes\";}',5,'hgsdh',1),(16,4,25,1,'a:7:{i:0;s:1:\"1\";i:1;s:1:\"1\";i:2;s:1:\"1\";i:3;s:1:\"1\";i:4;s:1:\"1\";i:5;s:1:\"1\";i:6;s:3:\"YES\";}',5,'hdsgj',1),(17,4,25,1,'a:7:{i:0;s:1:\"1\";i:1;s:1:\"1\";i:2;s:1:\"1\";i:3;s:1:\"1\";i:4;s:1:\"1\";i:5;s:1:\"1\";i:6;s:3:\"YES\";}',5,'hdsgj',1),(38,2,24,1,'a:7:{i:0;s:1:\"1\";i:1;s:1:\"1\";i:2;s:1:\"1\";i:3;s:1:\"1\";i:4;s:1:\"1\";i:5;s:1:\"1\";i:6;s:3:\"YES\";}',1.1666666666667,'kenza',0);
/*!40000 ALTER TABLE `evaluation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grille`
--

DROP TABLE IF EXISTS `grille`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grille` (
  `id` int NOT NULL AUTO_INCREMENT,
  `annee` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grille`
--

LOCK TABLES `grille` WRITE;
/*!40000 ALTER TABLE `grille` DISABLE KEYS */;
INSERT INTO `grille` VALUES (1,2024);
/*!40000 ALTER TABLE `grille` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notification`
--

DROP TABLE IF EXISTS `notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notification` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seen` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BF5476CAA76ED395` (`user_id`),
  CONSTRAINT `FK_BF5476CAA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notification`
--

LOCK TABLES `notification` WRITE;
/*!40000 ALTER TABLE `notification` DISABLE KEYS */;
INSERT INTO `notification` VALUES (1,24,'You have been invited to review the following articles: kjsdfsjdfbk.',0);
/*!40000 ALTER TABLE `notification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reset_password_request`
--

DROP TABLE IF EXISTS `reset_password_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reset_password_request` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `selector` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_7CE748AA76ED395` (`user_id`),
  CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reset_password_request`
--

LOCK TABLES `reset_password_request` WRITE;
/*!40000 ALTER TABLE `reset_password_request` DISABLE KEYS */;
/*!40000 ALTER TABLE `reset_password_request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `section`
--

DROP TABLE IF EXISTS `section`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `section` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `senior_editor_id` int DEFAULT NULL,
  `acronyme` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2D737AEF9E87C0A5` (`senior_editor_id`),
  CONSTRAINT `FK_2D737AEF9E87C0A5` FOREIGN KEY (`senior_editor_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `section`
--

LOCK TABLES `section` WRITE;
/*!40000 ALTER TABLE `section` DISABLE KEYS */;
INSERT INTO `section` VALUES (2,'Bioinformatics and Translational Informatics','Study of biological data using computational methods for translational research',21,'BTI'),(3,'Cancer Informatics','Application of informatics in cancer research and treatment.',NULL,'CI'),(4,'Clinical Information Systems','Management of information in clinical healthcare settings',20,'CIS'),(5,'Clinical Research Informatics','Use of informatics in conducting and managing clinical research.',NULL,'CRI'),(6,'Consumer Health Informatics','Utilization of technology for consumer healthcare management.',20,'CHI'),(7,'Decision Support','Tools and systems aiding healthcare decision-making processes',20,'DS'),(8,'Health Information Exchange','Electronic sharing of health-related information among organizations.',NULL,'HIE'),(9,'Human Factors and Organizational Issues','Study of how humans interact with technology in healthcare and organizational aspects',21,'HFOI'),(10,'Knowledge Representation and Management','Structuring and organizing information for efficient use in healthcare',21,'KRM'),(11,'Natural Language Processing','Processing and understanding human language by computers.',NULL,'NLP'),(12,'Public Health and Epidemiology Informatics','Application of informatics in public health studies and epidemiology.',NULL,'PHEI'),(13,'Signals Sensors and Imaging Informatics','Use of technology for signal, sensor, and imaging data analysis in healthcare.',NULL,'SSI'),(14,'topic',NULL,NULL,'ST');
/*!40000 ALTER TABLE `section` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `num_tel` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `laboratoire` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `etat` tinyint(1) NOT NULL,
  `confirmation_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `competences` json DEFAULT NULL,
  `section_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  KEY `IDX_8D93D649D823E37A` (`section_id`),
  CONSTRAINT `FK_8D93D649D823E37A` FOREIGN KEY (`section_id`) REFERENCES `section` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (9,'chabanaliza99@gmail.com','{\"1\": \"ROLE_USER\"}','$2y$13$kOHtmNd7JiVWrtRfMdSGT.2.9P0AyU855ZNUf5bILdvFfUOhQgExS','Liza','Ch','0766533522','qsugd',0,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjo5LCJpYXQiOjE3MDkxMjg3NTEsImV4cCI6MTcwOTEzOTU1MX0.iw6myxsdiLCSJVqiqdal7VXYKkF--BxclwQatmglRX4',NULL,NULL),(16,'mamoulounas@gmail.com','[\"ROLE_ADMIN\", \"ROLE_USER\"]','$2y$13$ANgW8CTE89Qgrpy77L7yyuAx/AhArcTi/JSNGvP9PdULUadmZnqZ2','Mamou','Lounas','0766533522','URNA',1,NULL,'{\"1\": \"Domaine 3\"}',NULL),(17,'lounas.mamou@univ-rouen.fr','[\"ROLE_REVIEWER\", \"ROLE_USER\"]','$2y$13$57.7OQnVmZVujoWV5E/3yeW/dS/A.ewmvnFrt0hox0v4tz7fZXne6','izane','ouzagour','0766533522','URN',1,NULL,NULL,NULL),(19,'elias@mail.com','[\"ROLE_REVIEWER\", \"ROLE_USER\"]','$2y$13$3FecsC7AeQ7bBbQOjp6mKO8mYE4shh0u98f5DERKfkYRN2U9Mxwp2','Elias','Larbi','567890°','URN',0,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoxOSwiaWF0IjoxNzEwNjg4ODA0LCJleHAiOjE3MTA2OTk2MDR9.O3X1LytrLDHtX8Ib6Oaa4wVHOvaZ6U6mlGmcLjgMWIk',NULL,NULL),(20,'SeniorEd1@exemlple.fr','[\"ROLE_SENIOR_EDITOR\", \"ROLE_REVIEWER\"]','$2y$13$Y.yXZtwuBi9jKeCNLM2DuudrKrSmIRwo1YfNQevUT6sVjuDS9h4oq','First','SeniorEditor','NumTel','URN',0,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoyMCwiaWF0IjoxNzExMTA5OTY5LCJleHAiOjE3MTExMjA3Njl9.ZSCW2R91x7EsrobjXrCc9o6RVdaR2RblclYyElb5Xcw',NULL,NULL),(21,'SenEd2@exemple.fr','[\"ROLE_SENIOR_EDITOR\", \"ROLE_REVIEWER\"]','$2y$13$376a3avX5Ch8I15ZSH8FkOUVpcZQQ8.CKIprPJeK8soIVa7PrVWUu','SenEd2','SenEd2','numttel','URN',0,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoyMSwiaWF0IjoxNzExMTEwMDg0LCJleHAiOjE3MTExMjA4ODR9.R7xVD67wjZVTru-W26UPMB1Ls5zP37QdcdMQ9gWjDlw',NULL,NULL),(22,'kenza.djellaoui369@gmail.com','{\"1\": \"ROLE_REVIEWER\", \"2\": \"ROLE_USER\"}','$2y$13$B2314hu.lPH6rOpIBDAbsurJIE/eOuwv5Ft/PvgTDloCKwVwVMqoS','kenza','kenza','07 45 62 45 99','univ rouen',1,NULL,NULL,NULL),(24,'user69@gmail.com','[\"ROLE_REVIEWER\", \"ROLE_USER\", \"ROLE_SECTION_EDITOR\"]','$2y$13$NUI3KLMRQPySmNPmJwzO9uf5xmr3Po4j9lBnzY5H4PRvghWGZQ52W','k','k','054625892','rouen',1,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoyNCwiaWF0IjoxNzE2Mjg2MjAyLCJleHAiOjE3MTYyODYyNjJ9.fpJhB25Mwzx5j116JSStk5r_iyHlVH59YGMWrRWfBVU','[\"Cancer Informatics\", \"Clinical Information Systems\", \"Consumer Health Informatics\"]',2),(25,'fk@mail.com','[\"ROLE_REVIEWER\"]','$2y$13$UMmCfGR35/jvF3xW.eXVQO6VfhlBjUEJkcDcc8vKK159Nc2t4EkZm','f','f','056255622455','univ rouen',1,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoyNSwiaWF0IjoxNzE2MjkzMzQ5LCJleHAiOjE3MTYyOTM0MDl9.Sznng4-ax80ImOVoF7Kx6UHuLJz6VXwkslIe_SIfT-Y',NULL,NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_article`
--

DROP TABLE IF EXISTS `user_article`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_article` (
  `user_id` int NOT NULL,
  `article_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`article_id`),
  KEY `IDX_5A37106CA76ED395` (`user_id`),
  KEY `IDX_5A37106C7294869C` (`article_id`),
  CONSTRAINT `FK_5A37106C7294869C` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_5A37106CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_article`
--

LOCK TABLES `user_article` WRITE;
/*!40000 ALTER TABLE `user_article` DISABLE KEYS */;
INSERT INTO `user_article` VALUES (24,3);
/*!40000 ALTER TABLE `user_article` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-28 12:20:55
