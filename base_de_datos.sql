CREATE DATABASE  IF NOT EXISTS `CyberStock_WMS` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `CyberStock_WMS`;
-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: CyberStock_WMS
-- ------------------------------------------------------
-- Server version	8.0.42

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `alerta_stock`
--

DROP TABLE IF EXISTS `alerta_stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `alerta_stock` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `producto_id` bigint unsigned NOT NULL,
  `stock_actual` int NOT NULL,
  `fecha_alerta` timestamp NOT NULL DEFAULT '2025-05-17 13:18:52',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `alerta_stock_producto_id_foreign` (`producto_id`),
  CONSTRAINT `alerta_stock_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alerta_stock`
--

LOCK TABLES `alerta_stock` WRITE;
/*!40000 ALTER TABLE `alerta_stock` DISABLE KEYS */;
INSERT INTO `alerta_stock` VALUES (1,9,44,'2025-05-17 13:19:11','2025-05-17 13:19:11','2025-05-17 13:19:11'),(2,10,16,'2025-05-17 13:19:11','2025-05-17 13:19:11','2025-05-17 13:19:11'),(6,13,0,'2025-05-17 13:47:57','2025-05-17 13:47:57','2025-05-17 13:47:57'),(15,14,0,'2025-05-17 22:34:51','2025-05-17 22:34:51','2025-05-17 22:34:51'),(16,15,0,'2025-05-18 13:03:35','2025-05-18 13:03:35','2025-05-18 13:03:35'),(19,4,0,'2025-05-18 14:47:52','2025-05-18 14:47:52','2025-05-18 14:48:00'),(25,3,0,'2025-05-19 19:48:32','2025-05-19 19:48:32','2025-05-19 19:48:32');
/*!40000 ALTER TABLE `alerta_stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('356a192b7913b04c54574d18c28d46e6395428ab','i:1;',1747685157),('356a192b7913b04c54574d18c28d46e6395428ab:timer','i:1747685157;',1747685157),('b7ad7f2b04bd98f199a2b8c016e37e66c831b866','i:1;',1747608285),('b7ad7f2b04bd98f199a2b8c016e37e66c831b866:timer','i:1747608285;',1747608285);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categorias_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (1,'Electrónica','2025-05-17 13:19:10','2025-05-17 13:19:10'),(2,'Ropa','2025-05-17 13:19:10','2025-05-17 13:19:10'),(3,'Hogar','2025-05-17 13:19:10','2025-05-17 13:19:10'),(4,'Juguetes','2025-05-17 13:19:10','2025-05-17 13:19:10'),(5,'Deportes','2025-05-17 13:19:10','2025-05-17 13:19:10'),(6,'Libros','2025-05-17 13:19:10','2025-05-17 13:19:10'),(7,'Informatica','2025-05-17 13:25:28','2025-05-17 13:25:28'),(8,'CaraJaula','2025-05-17 22:34:16','2025-05-17 22:34:16'),(9,'IA','2025-05-18 12:48:47','2025-05-18 12:48:47'),(10,'TIA','2025-05-18 12:52:18','2025-05-18 12:52:18'),(11,'TIO','2025-05-18 12:55:42','2025-05-18 12:55:42'),(12,'Comida','2025-05-19 00:13:56','2025-05-19 00:13:56'),(13,'Gnnochi','2025-05-19 00:36:07','2025-05-19 00:36:07');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estanterias`
--

DROP TABLE IF EXISTS `estanterias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estanterias` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zona_id` bigint unsigned NOT NULL,
  `capacidad_maxima` int NOT NULL,
  `capacidad_libre` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `estanterias_zona_id_foreign` (`zona_id`),
  CONSTRAINT `estanterias_zona_id_foreign` FOREIGN KEY (`zona_id`) REFERENCES `zonas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estanterias`
--

LOCK TABLES `estanterias` WRITE;
/*!40000 ALTER TABLE `estanterias` DISABLE KEYS */;
INSERT INTO `estanterias` VALUES (1,'A',1,200,121,'2025-05-17 13:19:11','2025-05-28 16:24:53'),(2,'B',1,65,39,'2025-05-17 13:19:11','2025-05-19 19:48:32'),(3,'C',1,73,73,'2025-05-17 13:19:11','2025-05-19 19:48:32'),(4,'A',2,93,0,'2025-05-17 13:19:11','2025-05-18 17:43:22'),(5,'B',2,80,80,'2025-05-17 13:19:11','2025-05-19 19:48:32'),(6,'C',2,99,97,'2025-05-17 13:19:11','2025-05-19 19:48:32'),(7,'A',3,58,58,'2025-05-17 13:19:11','2025-05-19 00:41:15'),(8,'B',3,51,51,'2025-05-17 13:19:11','2025-05-19 00:41:15'),(9,'C',3,76,76,'2025-05-17 13:19:11','2025-05-19 19:47:11'),(10,'A',4,63,63,'2025-05-17 13:19:11','2025-05-19 19:47:11'),(11,'B',4,99,99,'2025-05-17 13:19:11','2025-05-18 14:47:52'),(12,'C',4,96,96,'2025-05-17 13:19:11','2025-05-18 14:47:44');
/*!40000 ALTER TABLE `estanterias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_04_02_182044_create_categorias_table',1),(5,'2025_04_02_182240_create_productos_table',1),(6,'2025_04_04_191829_create_zonas_table',1),(7,'2025_04_04_192137_create_estanterias_table',1),(8,'2025_04_04_192757_create_producto_estanteria_table',1),(9,'2025_04_04_192848_create_movimientos_table',1),(10,'2025_05_03_144525_create_alerta_stock_table',1),(11,'2025_05_18_202539_add_approved_to_users_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movimientos`
--

DROP TABLE IF EXISTS `movimientos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movimientos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `producto_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `tipo` enum('entrada','salida','traslado') COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` int NOT NULL,
  `origen_tipo` enum('estanteria','proveedor') COLLATE utf8mb4_unicode_ci NOT NULL,
  `ubicacion_origen_id` bigint unsigned DEFAULT NULL,
  `destino_tipo` enum('estanteria','cliente') COLLATE utf8mb4_unicode_ci NOT NULL,
  `ubicacion_destino_id` bigint unsigned DEFAULT NULL,
  `estado` enum('pendiente','confirmado','cancelado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `fecha_movimiento` timestamp NOT NULL DEFAULT '2025-05-17 13:18:52',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `movimientos_producto_id_foreign` (`producto_id`),
  KEY `movimientos_user_id_foreign` (`user_id`),
  KEY `movimientos_ubicacion_origen_id_foreign` (`ubicacion_origen_id`),
  KEY `movimientos_ubicacion_destino_id_foreign` (`ubicacion_destino_id`),
  CONSTRAINT `movimientos_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `movimientos_ubicacion_destino_id_foreign` FOREIGN KEY (`ubicacion_destino_id`) REFERENCES `estanterias` (`id`) ON DELETE SET NULL,
  CONSTRAINT `movimientos_ubicacion_origen_id_foreign` FOREIGN KEY (`ubicacion_origen_id`) REFERENCES `estanterias` (`id`) ON DELETE SET NULL,
  CONSTRAINT `movimientos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=168 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movimientos`
--

LOCK TABLES `movimientos` WRITE;
/*!40000 ALTER TABLE `movimientos` DISABLE KEYS */;
INSERT INTO `movimientos` VALUES (112,2,1,'entrada',79,'proveedor',NULL,'estanteria',1,'confirmado','2025-05-18 16:52:02','2025-05-18 16:52:02','2025-05-18 16:52:02'),(113,2,1,'entrada',65,'proveedor',NULL,'estanteria',2,'confirmado','2025-05-18 16:52:02','2025-05-18 16:52:02','2025-05-18 16:52:02'),(114,2,1,'entrada',56,'proveedor',NULL,'estanteria',3,'confirmado','2025-05-18 16:52:02','2025-05-18 16:52:02','2025-05-18 16:52:02'),(130,2,1,'traslado',79,'estanteria',1,'estanteria',4,'confirmado','2025-05-18 17:26:46','2025-05-18 17:26:46','2025-05-18 17:26:46'),(131,2,1,'traslado',14,'estanteria',2,'estanteria',4,'confirmado','2025-05-18 17:26:46','2025-05-18 17:26:46','2025-05-18 17:26:46'),(132,2,1,'traslado',51,'estanteria',2,'estanteria',5,'confirmado','2025-05-18 17:26:46','2025-05-18 17:26:46','2025-05-18 17:26:46'),(133,2,1,'traslado',29,'estanteria',3,'estanteria',5,'confirmado','2025-05-18 17:26:46','2025-05-18 17:26:46','2025-05-18 17:26:46'),(134,2,1,'traslado',27,'estanteria',3,'estanteria',6,'confirmado','2025-05-18 17:26:46','2025-05-18 17:26:46','2025-05-18 17:26:46'),(140,2,1,'salida',80,'estanteria',5,'cliente',NULL,'confirmado','2025-05-18 17:44:05','2025-05-18 17:44:05','2025-05-18 17:44:05'),(141,2,1,'salida',25,'estanteria',6,'cliente',NULL,'confirmado','2025-05-18 17:44:05','2025-05-18 17:44:05','2025-05-18 17:44:05'),(142,2,1,'entrada',79,'proveedor',NULL,'estanteria',1,'confirmado','2025-05-18 17:44:55','2025-05-18 17:44:55','2025-05-18 17:44:55'),(143,2,1,'entrada',26,'proveedor',NULL,'estanteria',2,'confirmado','2025-05-18 17:44:55','2025-05-18 17:44:55','2025-05-18 17:44:55'),(158,3,1,'traslado',39,'estanteria',9,'estanteria',2,'confirmado','2025-05-19 19:47:11','2025-05-19 19:47:11','2025-05-19 19:47:11'),(159,3,1,'traslado',21,'estanteria',9,'estanteria',3,'confirmado','2025-05-19 19:47:11','2025-05-19 19:47:11','2025-05-19 19:47:11'),(160,3,1,'traslado',15,'estanteria',10,'estanteria',3,'confirmado','2025-05-19 19:47:11','2025-05-19 19:47:11','2025-05-19 19:47:11'),(161,3,1,'entrada',37,'proveedor',NULL,'estanteria',3,'confirmado','2025-05-19 19:48:16','2025-05-19 19:48:16','2025-05-19 19:48:16'),(162,3,1,'entrada',80,'proveedor',NULL,'estanteria',5,'confirmado','2025-05-19 19:48:16','2025-05-19 19:48:16','2025-05-19 19:48:16'),(163,3,1,'entrada',8,'proveedor',NULL,'estanteria',6,'confirmado','2025-05-19 19:48:16','2025-05-19 19:48:16','2025-05-19 19:48:16'),(164,3,1,'salida',39,'estanteria',2,'cliente',NULL,'confirmado','2025-05-19 19:48:32','2025-05-19 19:48:32','2025-05-19 19:48:32'),(165,3,1,'salida',73,'estanteria',3,'cliente',NULL,'confirmado','2025-05-19 19:48:32','2025-05-19 19:48:32','2025-05-19 19:48:32'),(166,3,1,'salida',80,'estanteria',5,'cliente',NULL,'confirmado','2025-05-19 19:48:32','2025-05-19 19:48:32','2025-05-19 19:48:32'),(167,3,1,'salida',8,'estanteria',6,'cliente',NULL,'confirmado','2025-05-19 19:48:32','2025-05-19 19:48:32','2025-05-19 19:48:32');
/*!40000 ALTER TABLE `movimientos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producto_estanteria`
--

DROP TABLE IF EXISTS `producto_estanteria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `producto_estanteria` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `producto_id` bigint unsigned NOT NULL,
  `estanteria_id` bigint unsigned NOT NULL,
  `cantidad` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `producto_estanteria_producto_id_foreign` (`producto_id`),
  KEY `producto_estanteria_estanteria_id_foreign` (`estanteria_id`),
  CONSTRAINT `producto_estanteria_estanteria_id_foreign` FOREIGN KEY (`estanteria_id`) REFERENCES `estanterias` (`id`) ON DELETE CASCADE,
  CONSTRAINT `producto_estanteria_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto_estanteria`
--

LOCK TABLES `producto_estanteria` WRITE;
/*!40000 ALTER TABLE `producto_estanteria` DISABLE KEYS */;
INSERT INTO `producto_estanteria` VALUES (94,2,6,2,NULL,NULL),(95,2,4,93,NULL,NULL),(96,2,1,79,'2025-05-18 17:44:55','2025-05-18 17:44:55'),(97,2,2,26,'2025-05-18 17:44:55','2025-05-18 17:44:55');
/*!40000 ALTER TABLE `producto_estanteria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo_producto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` enum('materia_prima','producto_terminado') COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria_id` bigint unsigned NOT NULL,
  `stock_total` int NOT NULL DEFAULT '0',
  `stock_minimo_alerta` int NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `productos_codigo_producto_unique` (`codigo_producto`),
  KEY `productos_categoria_id_foreign` (`categoria_id`),
  CONSTRAINT `productos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (1,'MFXXC4IB','Producto 1','Descripción del producto 1','productos/uAbpqsNxsQGprsGXxOj0vZUdadt0KNRSq5536gGk.png','producto_terminado',5,0,76,'2025-05-17 17:16:36','2025-05-17 13:19:10','2025-05-17 17:16:36'),(2,'SM3UBDJJ','Producto 2','Descripción del producto 2',NULL,'materia_prima',2,200,34,NULL,'2025-05-17 13:19:11','2025-05-18 17:44:55'),(3,'NOANRW7T','Producto 3','Descripción del producto 3',NULL,'producto_terminado',4,0,70,NULL,'2025-05-17 13:19:11','2025-05-19 19:48:32'),(4,'NFJZLR4L','Producto 4','Descripción del producto 4',NULL,'materia_prima',5,0,68,NULL,'2025-05-17 13:19:11','2025-05-18 14:48:00'),(5,'MHDI44QU','Producto 5','Descripción del producto 5',NULL,'producto_terminado',1,0,62,NULL,'2025-05-17 13:19:11','2025-05-17 13:19:11'),(6,'EOJ3VRNZ','Producto 6','Descripción del producto 6',NULL,'materia_prima',1,0,99,NULL,'2025-05-17 13:19:11','2025-05-17 13:19:11'),(7,'DU9XZEBP','Producto 7','Descripción del producto 7',NULL,'producto_terminado',1,0,41,NULL,'2025-05-17 13:19:11','2025-05-17 13:19:11'),(8,'3B7IKI2O','Producto 8','Descripción del producto 8',NULL,'materia_prima',3,0,69,NULL,'2025-05-17 13:19:11','2025-05-17 13:19:11'),(9,'H3LKTPRQ','Producto 9','Descripción del producto 9',NULL,'producto_terminado',3,0,95,NULL,'2025-05-17 13:19:11','2025-05-17 13:19:11'),(10,'SU71ONEJ','Producto 10','Descripción del producto 10',NULL,'materia_prima',1,0,24,NULL,'2025-05-17 13:19:11','2025-05-17 13:19:11'),(12,'9783453HJKGWSED','CaraJaula',NULL,'productos/x6tVAGzhwh8ePpsK0Obg5ri7h8BghCHsE1v0Nfh4.png','producto_terminado',7,0,20,'2025-05-17 13:30:22','2025-05-17 13:30:22','2025-05-17 14:06:44'),(13,'435987634KJHLDSFSF','CARA JAULITA',NULL,'imagenes/productos/hqGC2Thj872dwCoRjZyQsGrrp3Gthfw0i83ydaOv.png','producto_terminado',6,0,30,NULL,'2025-05-17 13:47:57','2025-05-19 20:51:42'),(14,'KEJDFHLD389047','rolu',NULL,'imagenes/productos/xM05kt0D7Jr2yte7M2bXXKGEadPysrCAybISjRXJ.png','producto_terminado',8,0,10,NULL,'2025-05-17 22:34:51','2025-05-19 20:58:33'),(15,'KJDHGS978654','CARA JAULITA',NULL,'imagenes/productos/Mis3ddz6TGGtrFgRUtyHDuqtJNw7CkzvaJ5Jz2fN.png','producto_terminado',11,0,5,NULL,'2025-05-18 13:03:35','2025-05-19 20:58:15'),(16,'4379863525KSLJHG','Gnnochi',NULL,'imagenes/productos/SMvLTzTD2bSKyGb99lMpeqb20VlqwGGv8fXD1bLn.jpg','producto_terminado',5,0,5,'2025-05-26 18:06:43','2025-05-19 00:15:06','2025-05-26 18:06:43');
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('3zzd1P3Hr0Js9kHABhhLqDWy4aapVIMC46ZE8V6M',NULL,'172.18.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNDFEZnNPMG1kWE9zODBLZ0o5V3JlMEZHSFM4SVRLMFVaSzA2VGVsWiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovL2xvY2FsaG9zdDo4MDAwIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fX0=',1748470775);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dni` varchar(9) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(9) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo_postal` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `localidad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provincia` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` enum('Administrador','Usuario') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Usuario',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_nacimiento` date NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `approved_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_dni_unique` (`dni`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'20621290M','Mariano Jesus','Roldan','Luque','642160405','Calle Fresno 69A','14960','Rute','Cordoba','Administrador','marianojesusroldanluque1@gmail.com',NULL,'$2y$12$W2doQXZKGIAJ.MCxxHTHdOi8faRpek7PmbyJ94TMDgwGfM468.QPK','imagenes/perfiles/Hv7X7UPENcw22Ha2eEuRU2paNXjvtcT5BSIHyh7G.png','2005-07-04',NULL,'2025-05-17 13:19:10','2025-05-27 17:35:12',1,'2025-05-26 23:10:31'),(3,'20623090N','Mariano','Roldan','Luque','642160405','Calle Fresno','14960','Rute','Córdoba','Usuario','mrolluq04@iesmarquesdecomares.org',NULL,'$2y$12$JE9XisnOvwV9yUFGT7rwNuq89b/fsNB/qF623YYi/UpKpHfAjqF4.','imagenes/perfiles/waUogVy1J3dHEuc1AumS4nVz3BFBZCwC7vtD10yk.png','2005-07-04',NULL,'2025-05-19 00:43:56','2025-05-27 17:32:01',1,'2025-05-19 00:45:54'),(4,'20621290X','Mariano Jesus','Roldan','Luque','642160405','Calle Fresno 69A','14960','Rute','Cordoba','Administrador','mrolluq04@iesmarquesdecomares.com',NULL,'$2y$12$IofxIPA9tkFJh/PfgrCX9.CxagkNlAhOm7ZvF.w8epOYO1QFUfWjy','imagenes/perfiles/Hmuo3p7jtuAhCT7Xq8eLfHRWHIs17sk69KcGPg9j.png','2005-07-04',NULL,'2025-05-26 23:19:39','2025-05-27 17:34:48',1,'2025-05-27 17:27:29'),(5,'20623090O','Mariano','Roldan','Luque','642160405','Calle Fresno','14960','Rute','Cordoba','Usuario','mrolluq04@iesmarquesdecomares.es',NULL,'$2y$12$toeWOVsbWUXnmwjP6n2pdOqQm3Ll8RqjNOSVvLa6pHV6DkAobCyGi','imagenes/perfiles/yhQ4ZndmQf34EORkW5ShwliVWIkAvGoNEIXZKxhR.png','2005-07-04',NULL,'2025-05-27 17:34:27','2025-05-27 17:34:27',1,'2025-05-27 17:34:27');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zonas`
--

DROP TABLE IF EXISTS `zonas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zonas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zonas`
--

LOCK TABLES `zonas` WRITE;
/*!40000 ALTER TABLE `zonas` DISABLE KEYS */;
INSERT INTO `zonas` VALUES (1,'Zona A','Área principal de almacenamiento','2025-05-17 13:19:11','2025-05-17 13:19:11'),(2,'Zona B','Zona de productos terminados','2025-05-17 13:19:11','2025-05-17 13:19:11'),(3,'Zona C','Zona de materias primas','2025-05-17 13:19:11','2025-05-17 13:19:11'),(4,'Zona D','Zona de despacho y carga','2025-05-17 13:19:11','2025-05-17 13:19:11');
/*!40000 ALTER TABLE `zonas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'CyberStock_WMS'
--

--
-- Dumping routines for database 'CyberStock_WMS'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-29  0:21:46
