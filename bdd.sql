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
  PRIMARY KEY (`id`),
  KEY `IDX_23A0E66D823E37A` (`section_id`),
  CONSTRAINT `FK_23A0E66D823E37A` FOREIGN KEY (`section_id`) REFERENCES `section` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `article`
--

LOCK TABLES `article` WRITE;
/*!40000 ALTER TABLE `article` DISABLE KEYS */;
INSERT INTO `article` VALUES (5,2,38778264,'10.1186/s12876-024-03263-2','Morning light treatment for inflammatory bowel disease: a clinical trial.','Cohen-Mekelburg S','Cohen-Mekelburg S, Goldstein CA, Rizvydeen M, Fayyaz Z, Patel PJ, Berinstein JA, Bishu S, Cushing-Damm KC, Kim HM, Burgess HJ','science','al-tp4-65f99d43ea0d3-664f06bd8629b.pdf',3),(6,4,38777133,'10.1053/j.gastro.2024.05.010','Effect of Brain-gut Behavioral Treatments on Abdominal Pain in Irritable Bowel Syndrome: Systematic Review and Network Meta-analysis.','Goodoory VC','Goodoory VC, Khasawneh M, Thakur ER, Everitt HA, Gudleski GD, Lackner JM, Moss-Morris R, Simren M, Vasant DH, Moayyedi P, Black CJ, Ford AC','science','al-tp6-65f99c907510e-664f071979773.pdf',2.7666666666667);
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
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evaluation`
--

LOCK TABLES `evaluation` WRITE;
/*!40000 ALTER TABLE `evaluation` DISABLE KEYS */;
INSERT INTO `evaluation` VALUES (41,6,23,1,'a:7:{i:0;s:1:\"3\";i:1;s:1:\"2\";i:2;s:1:\"5\";i:3;s:1:\"2\";i:4;s:1:\"2\";i:5;s:1:\"2\";i:6;s:9:\"Maybe Yes\";}',2.7666666666667,'good',1),(42,5,27,1,'a:7:{i:0;s:1:\"4\";i:1;s:1:\"3\";i:2;s:1:\"1\";i:3;s:1:\"5\";i:4;s:1:\"2\";i:5;s:1:\"2\";i:6;s:3:\"YES\";}',3,'ver good',1);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
  `section_editor_id` int DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `senior_editor_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_2D737AEFBD8DCC9` (`section_editor_id`),
  KEY `IDX_2D737AEF9E87C0A5` (`senior_editor_id`),
  CONSTRAINT `FK_2D737AEF9E87C0A5` FOREIGN KEY (`senior_editor_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_2D737AEFBD8DCC9` FOREIGN KEY (`section_editor_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `section`
--

LOCK TABLES `section` WRITE;
/*!40000 ALTER TABLE `section` DISABLE KEYS */;
INSERT INTO `section` VALUES (2,'Bioinformatics and Translational Informatics',16,'Study of biological data using computational methods for translational research',21),(4,'Clinical Information Systems',NULL,'Management of information in clinical healthcare settings',20),(5,'Clinical Research Informatics',NULL,'Use of informatics in conducting and managing clinical research.',NULL),(6,'Consumer Health Informatics',NULL,'Utilization of technology for consumer healthcare management.',20),(7,'Decision Support',NULL,'Tools and systems aiding healthcare decision-making processes',20),(8,'Health Information Exchange',NULL,'Electronic sharing of health-related information among organizations.',NULL),(9,'Human Factors and Organizational Issues',NULL,'Study of how humans interact with technology in healthcare and organizational aspects',21),(10,'Knowledge Representation and Management',NULL,'Structuring and organizing information for efficient use in healthcare',21),(11,'Natural Language Processing',NULL,'Processing and understanding human language by computers.',NULL),(12,'Public Health and Epidemiology Informatics',NULL,'Application of informatics in public health studies and epidemiology.',NULL),(13,'Signals Sensors and Imaging Informatics',NULL,'Use of technology for signal, sensor, and imaging data analysis in healthcare.',NULL);
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (16,'mamoulounas@gmail.com','{\"0\": \"ROLE_ADMIN\", \"2\": \"ROLE_SECTION_EDITOR\"}','$2y$13$kge2h5xaXcIumXT3Wd4Am.sPemgoDDJ2LFv1yO87QxkZmmpHYo8bi','Mamou','Lounas','0766533522','URNA',1,NULL,'[]'),(20,'martin@exemlple.fr','[\"ROLE_SENIOR_EDITOR\", \"ROLE_REVIEWER\"]','$2y$13$OOmgaJViWbiR9Z4Gb8mdme/1I33vdLojRUUPMelADCxbfsj07/Mzq','Martin','Pierre','0258963147','URN',1,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoyMCwiaWF0IjoxNzExMTA5OTY5LCJleHAiOjE3MTExMjA3Njl9.ZSCW2R91x7EsrobjXrCc9o6RVdaR2RblclYyElb5Xcw',NULL),(21,'dubois@exemple.fr','[\"ROLE_SENIOR_EDITOR\", \"ROLE_REVIEWER\"]','$2y$13$dILyOCw78F8spmonQik9YeLz1rTPUn1Ct.cgTelkSs..P3.7yEF2G','Dubois','Élise','0258963258','URN',1,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoyMSwiaWF0IjoxNzExMTEwMDg0LCJleHAiOjE3MTExMjA4ODR9.R7xVD67wjZVTru-W26UPMB1Ls5zP37QdcdMQ9gWjDlw',NULL),(22,'richard@gmail.com','[\"ROLE_SENIOR_EDITOR\"]','$2y$13$LBYrwUDvnnQM3pJsdYQlo.UoODUBwgiW3qxpgLgtrQJ.I4NzSEfo2','Richard','Manon','258963147850','Rouen',1,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoyMiwiaWF0IjoxNzEzNDQ3NDE3LCJleHAiOjE3MTM0NTgyMTd9.1isvGslTBDoR4-UP8zMbf-7mwwfK4uoKTNfuadWuh0A',NULL),(23,'ahmed.koubaa@univ-rouen.fr','[\"ROLE_SENIOR_EDITOR\", \"ROLE_REVIEWER\"]','$2y$13$ibYYGYCu6yQm5ix7E0m0IOHyV7K.exMDEKlqKHQa1lXTnC0SLdt2u','koubaa','Ahmed','258963147850','Université de rouen',1,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoyMywiaWF0IjoxNzE0MzQ0NzQ5LCJleHAiOjE3MTQzNDQ4MDl9.ZQTC-NJh7bHWHCMYrejYpM0-2uyW4HxQ8hjFKQwXsrU',NULL),(26,'delarue@gmail.com','[\"ROLE_CHIEF_EDITOR\"]','$2y$13$Mxi4GHY5hy9HdUdcKP9ICughiWh.TPs7dtTYR4ed6Lv.0hK4qiL02','Delarue','Anais','1472589630','Rouen',1,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoyNiwiaWF0IjoxNzE2MzgyNTA2LCJleHAiOjE3MTYzODI1NjZ9.ZA5aa7jWDhPCLHJFQk5bEIKAYJiKA5g-JRz8op-l-8Q',NULL),(27,'robert@gmail.com','[\"ROLE_REVIEWER\"]','$2y$13$Y6P5/cJTUoZznD16uKvqKOGzprEuxlSjvWNXYl3Fa8E1ubIkmsR4m','Robert','Jules','0254136987','Université de rouen',0,'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoyNywiaWF0IjoxNzE2NDU0OTA2LCJleHAiOjE3MTY0NTQ5NjZ9.BReDRSzMxcgh5318fxvjk67H1lIaC_afLISt855ua6Y',NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-23 11:33:59
