-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 31, 2025 at 10:26 PM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ceil`
--

-- --------------------------------------------------------

--
-- Table structure for table `enseignement`
--

DROP TABLE IF EXISTS `enseignement`;
CREATE TABLE IF NOT EXISTS `enseignement` (
  `num_ens` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `nom_ens` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `pre_ens` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `id` int NOT NULL,
  PRIMARY KEY (`num_ens`),
  KEY `fk_user_id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `enseignement`
--

INSERT INTO `enseignement` (`num_ens`, `nom_ens`, `pre_ens`, `email`, `id`) VALUES
('001', 'teacher', 'one', 'teacher1@gmail.com', 43),
('002', 'teacher', 'two', 'teacher2@gmail.com', 44);

-- --------------------------------------------------------

--
-- Table structure for table `etudiant`
--

DROP TABLE IF EXISTS `etudiant`;
CREATE TABLE IF NOT EXISTS `etudiant` (
  `num_etud` int NOT NULL,
  `date_naisc` date NOT NULL,
  `lieu_naisc` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `nom_etud` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `prenom_etud` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `statut_etud` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `tel` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `num_group` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `num_niv` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `num_lang` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`num_etud`),
  KEY `group_code` (`num_group`),
  KEY `fk_etudiant_niveau` (`num_niv`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `etudiant`
--

INSERT INTO `etudiant` (`num_etud`, `date_naisc`, `lieu_naisc`, `nom_etud`, `prenom_etud`, `statut_etud`, `tel`, `email`, `num_group`, `num_niv`, `num_lang`) VALUES
(2, '2005-01-06', 'jijel', 'student', 'two', 'Not a Univ', '0777012244', 'student2@gmail.com', 'g2-eng', 'A2', 'eng'),
(1, '2005-01-05', 'jijel', 'student', 'one', 'University', '0777012233', 'student1@gmail.com', 'g1-eng', 'A1', 'eng');

-- --------------------------------------------------------

--
-- Table structure for table `groupe`
--

DROP TABLE IF EXISTS `groupe`;
CREATE TABLE IF NOT EXISTS `groupe` (
  `num_group` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `nom_group` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `prg_group` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
  `num_lang` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `num_niv` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `num_ens` varchar(25) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`num_group`),
  KEY `num_niv` (`num_niv`),
  KEY `fk_groupe_langue` (`num_lang`),
  KEY `fk_num_ens` (`num_ens`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `groupe`
--

INSERT INTO `groupe` (`num_group`, `nom_group`, `prg_group`, `num_lang`, `num_niv`, `num_ens`) VALUES
('g1-eng', 'group 1 english', 'uploads/prg-A1.jpg', 'eng', 'A1', '001'),
('g2-eng', 'group 2 english', 'uploads/prg1.jpg', 'eng', 'A2', '002');

-- --------------------------------------------------------

--
-- Table structure for table `langue`
--

DROP TABLE IF EXISTS `langue`;
CREATE TABLE IF NOT EXISTS `langue` (
  `num_lang` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `nom_lang` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`num_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `langue`
--

INSERT INTO `langue` (`num_lang`, `nom_lang`) VALUES
('dut', 'allemand'),
('eng', 'english'),
('fr', 'français'),
('ita', 'italien'),
('por', 'portuges'),
('spa', 'spanish'),
('turk', 'turkish');

-- --------------------------------------------------------

--
-- Table structure for table `niveau`
--

DROP TABLE IF EXISTS `niveau`;
CREATE TABLE IF NOT EXISTS `niveau` (
  `num_niv` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `nom_niv` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `num_lang` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`num_niv`),
  KEY `num_lang` (`num_lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `niveau`
--

INSERT INTO `niveau` (`num_niv`, `nom_niv`, `num_lang`) VALUES
('B2', 'B2', 'eng'),
('B1', 'B1', 'eng'),
('A2', 'A2', 'eng'),
('A1', 'A1', 'eng'),
('C1', 'C1', 'eng'),
('C2', 'C2', 'eng');

-- --------------------------------------------------------

--
-- Table structure for table `note`
--

DROP TABLE IF EXISTS `note`;
CREATE TABLE IF NOT EXISTS `note` (
  `num_note` int NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `note` float DEFAULT NULL,
  `num_etud` int DEFAULT NULL,
  `num_lang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`num_note`),
  KEY `num_etud` (`num_etud`),
  KEY `num_lang` (`num_lang`(250))
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `note`
--

INSERT INTO `note` (`num_note`, `date`, `note`, `num_etud`, `num_lang`) VALUES
(25, '2025-03-31', 13, 1, 'eng');

-- --------------------------------------------------------

--
-- Table structure for table `salle`
--

DROP TABLE IF EXISTS `salle`;
CREATE TABLE IF NOT EXISTS `salle` (
  `num_salle` int NOT NULL,
  `nom_salle` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`num_salle`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(191) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','admin','teacher') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'student',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(28, 'admin@gmail.com', '$2y$10$3ScGKAyX35.FBhGwXwv2AeqM8YcRrYq3U51kbwx0DqTUOKQmR0q/G', 'admin', '2025-01-20 23:15:53', '2025-03-31 01:03:26'),
(43, 'teacher1@gmail.com', '$2y$10$JM5ELMXdhwirbFPVGwjCiudF0lhVRiBLFdbnZgijcnQYuBv2TnBq2', 'teacher', '2025-03-31 04:34:27', '2025-03-31 04:34:27'),
(44, 'teacher2@gmail.com', '$2y$10$/9vJw8siurFekxEOKYXgHOaodX.0R/f92iSb5OUrYMDy76QPpQbv.', 'teacher', '2025-03-31 04:35:44', '2025-03-31 04:35:44'),
(45, 'student1@gmail.com', '$2y$10$hwX16kuOrnt32vcD1GyzquTSGY69LG.JOS.n8/NMVSHbiMDNN0Sia', 'student', '2025-03-31 04:37:50', '2025-03-31 04:37:50'),
(46, 'student2@gmail.com', '$2y$10$QufbuJPRdedI1Yod3hzcDOSL3TqhXej.vsLF9AW/q7Thst7PfgESi', 'student', '2025-03-31 04:38:42', '2025-03-31 04:38:42');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `enseignement`
--
ALTER TABLE `enseignement`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `groupe`
--
ALTER TABLE `groupe`
  ADD CONSTRAINT `fk_groupe_langue` FOREIGN KEY (`num_lang`) REFERENCES `langue` (`num_lang`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
