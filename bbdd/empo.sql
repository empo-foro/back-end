CREATE DATABASE  IF NOT EXISTS `empo` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `empo`;
-- MySQL dump 10.13  Distrib 8.0.13, for macos10.14 (x86_64)
--
-- Host: 127.0.0.1    Database: empo
-- ------------------------------------------------------
-- Server version	5.5.5-10.1.36-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `alumno`
--

DROP TABLE IF EXISTS `alumno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `alumno` (
  `id_alumno` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  PRIMARY KEY (`id_alumno`),
  KEY `fk_alumno_usuario` (`id_usuario`),
  KEY `fk_alumno_curso` (`id_curso`),
  CONSTRAINT `fk_alumno_curso` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`),
  CONSTRAINT `fk_alumno_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alumno`
--

LOCK TABLES `alumno` WRITE;
/*!40000 ALTER TABLE `alumno` DISABLE KEYS */;
/*!40000 ALTER TABLE `alumno` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asignatura`
--

DROP TABLE IF EXISTS `asignatura`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `asignatura` (
  `id_asignatura` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `id_curso` int(11) NOT NULL,
  PRIMARY KEY (`id_asignatura`),
  KEY `fk_asignatura_curso` (`id_curso`),
  CONSTRAINT `fk_asignatura_curso` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asignatura`
--

LOCK TABLES `asignatura` WRITE;
/*!40000 ALTER TABLE `asignatura` DISABLE KEYS */;
/*!40000 ALTER TABLE `asignatura` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `centro`
--

DROP TABLE IF EXISTS `centro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `centro` (
  `id_centro` int(11) NOT NULL,
  `nif` varchar(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `biografia` text,
  `descripcion` text,
  `imagen_personal` varchar(255) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_centro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `centro`
--

LOCK TABLES `centro` WRITE;
/*!40000 ALTER TABLE `centro` DISABLE KEYS */;
INSERT INTO `centro` VALUES (0,'49900414M','STUCOM Centre d\'Estuids','stucom',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `centro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `curso`
--

DROP TABLE IF EXISTS `curso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `curso` (
  `id_curso` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `id_centro` int(11) NOT NULL,
  PRIMARY KEY (`id_curso`),
  KEY `fk_curso_centro` (`id_centro`),
  CONSTRAINT `fk_curso_centro` FOREIGN KEY (`id_centro`) REFERENCES `centro` (`id_centro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `curso`
--

LOCK TABLES `curso` WRITE;
/*!40000 ALTER TABLE `curso` DISABLE KEYS */;
/*!40000 ALTER TABLE `curso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `post` (
  `id_post` int(11) NOT NULL,
  `titulo` varchar(50) NOT NULL,
  `cuerpo` text NOT NULL,
  `fecha` date NOT NULL,
  `cerrado` tinyint(1) NOT NULL,
  `id_alumno` int(11) NOT NULL,
  PRIMARY KEY (`id_post`),
  KEY `fk_post_alumno` (`id_alumno`),
  CONSTRAINT `fk_post_alumno` FOREIGN KEY (`id_alumno`) REFERENCES `alumno` (`id_alumno`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post`
--

LOCK TABLES `post` WRITE;
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
/*!40000 ALTER TABLE `post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_vs_tema`
--

DROP TABLE IF EXISTS `post_vs_tema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `post_vs_tema` (
  `id_post` int(11) NOT NULL,
  `id_tema` int(11) NOT NULL,
  KEY `fk_post_vs_tema_post` (`id_post`),
  KEY `fk_post_vs_tema_tema` (`id_tema`),
  CONSTRAINT `fk_post_vs_tema_post` FOREIGN KEY (`id_post`) REFERENCES `post` (`id_post`),
  CONSTRAINT `fk_post_vs_tema_tema` FOREIGN KEY (`id_tema`) REFERENCES `tema` (`id_tema`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_vs_tema`
--

LOCK TABLES `post_vs_tema` WRITE;
/*!40000 ALTER TABLE `post_vs_tema` DISABLE KEYS */;
/*!40000 ALTER TABLE `post_vs_tema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profesor`
--

DROP TABLE IF EXISTS `profesor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `profesor` (
  `id_profesor` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_profesor`),
  KEY `fk_profesor_usuario` (`id_usuario`),
  CONSTRAINT `fk_profesor_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profesor`
--

LOCK TABLES `profesor` WRITE;
/*!40000 ALTER TABLE `profesor` DISABLE KEYS */;
/*!40000 ALTER TABLE `profesor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profesor_vs_asignatura`
--

DROP TABLE IF EXISTS `profesor_vs_asignatura`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `profesor_vs_asignatura` (
  `id_profesor` int(11) NOT NULL,
  `id_asignatura` int(11) NOT NULL,
  KEY `fk_profesor_vs_asignatura_profesor` (`id_profesor`),
  KEY `fk_profesor_vs_asignatura_asignatura` (`id_asignatura`),
  CONSTRAINT `fk_profesor_vs_asignatura_asignatura` FOREIGN KEY (`id_asignatura`) REFERENCES `asignatura` (`id_asignatura`),
  CONSTRAINT `fk_profesor_vs_asignatura_profesor` FOREIGN KEY (`id_profesor`) REFERENCES `profesor` (`id_profesor`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profesor_vs_asignatura`
--

LOCK TABLES `profesor_vs_asignatura` WRITE;
/*!40000 ALTER TABLE `profesor_vs_asignatura` DISABLE KEYS */;
/*!40000 ALTER TABLE `profesor_vs_asignatura` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `respuesta`
--

DROP TABLE IF EXISTS `respuesta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `respuesta` (
  `id_respuesta` int(11) NOT NULL,
  `asunto` varchar(50) NOT NULL,
  `texto` text NOT NULL,
  `fecha` date NOT NULL,
  `id_post` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_respuesta_padre` int(11) NOT NULL,
  PRIMARY KEY (`id_respuesta`),
  KEY `fk_respuesta_post` (`id_post`),
  KEY `fk_respuesta_usuario` (`id_usuario`),
  KEY `fk_respuesta_padre` (`id_respuesta_padre`),
  CONSTRAINT `fk_respuesta_padre` FOREIGN KEY (`id_respuesta_padre`) REFERENCES `respuesta` (`id_respuesta`),
  CONSTRAINT `fk_respuesta_post` FOREIGN KEY (`id_post`) REFERENCES `post` (`id_post`),
  CONSTRAINT `fk_respuesta_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `respuesta`
--

LOCK TABLES `respuesta` WRITE;
/*!40000 ALTER TABLE `respuesta` DISABLE KEYS */;
/*!40000 ALTER TABLE `respuesta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `respuesta_guardada`
--

DROP TABLE IF EXISTS `respuesta_guardada`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `respuesta_guardada` (
  `id_respuesta` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  KEY `fk_respuesta_guardada_respuesta` (`id_respuesta`),
  KEY `fk_respuesta_guardada_usuario` (`id_usuario`),
  CONSTRAINT `fk_respuesta_guardada_respuesta` FOREIGN KEY (`id_respuesta`) REFERENCES `respuesta` (`id_respuesta`),
  CONSTRAINT `fk_respuesta_guardada_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `respuesta_guardada`
--

LOCK TABLES `respuesta_guardada` WRITE;
/*!40000 ALTER TABLE `respuesta_guardada` DISABLE KEYS */;
/*!40000 ALTER TABLE `respuesta_guardada` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `respuesta_reportada`
--

DROP TABLE IF EXISTS `respuesta_reportada`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `respuesta_reportada` (
  `id_reporte` int(11) NOT NULL,
  `id_respuesta` int(11) NOT NULL,
  `tipo_reporte` varchar(50) NOT NULL,
  `descripcion` text,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_reporte`),
  KEY `fk_respuesta_reportada_respuesta` (`id_respuesta`),
  KEY `fk_respuesta_reportada_usuario` (`id_usuario`),
  CONSTRAINT `fk_respuesta_reportada_respuesta` FOREIGN KEY (`id_respuesta`) REFERENCES `respuesta` (`id_respuesta`),
  CONSTRAINT `fk_respuesta_reportada_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `respuesta_reportada`
--

LOCK TABLES `respuesta_reportada` WRITE;
/*!40000 ALTER TABLE `respuesta_reportada` DISABLE KEYS */;
/*!40000 ALTER TABLE `respuesta_reportada` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tema`
--

DROP TABLE IF EXISTS `tema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `tema` (
  `id_tema` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id_tema`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tema`
--

LOCK TABLES `tema` WRITE;
/*!40000 ALTER TABLE `tema` DISABLE KEYS */;
/*!40000 ALTER TABLE `tema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nif` varchar(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `imagen_personal` varchar(255) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `biografia` text,
  `id_centro` int(11) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `fk_usuario_centro` (`id_centro`),
  CONSTRAINT `fk_usuario_centro` FOREIGN KEY (`id_centro`) REFERENCES `centro` (`id_centro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-11-30 12:02:26
