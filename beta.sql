-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 26, 2025 at 08:30 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `beta`
--

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int NOT NULL,
  `date_menu` date DEFAULT NULL,
  `entree` varchar(150) NOT NULL,
  `plat` varchar(150) NOT NULL,
  `garniture` varchar(150) NOT NULL,
  `produit_laitier` varchar(150) NOT NULL,
  `dessert` varchar(150) NOT NULL,
  `divers` varchar(150) NOT NULL,
  `nom_menu` varchar(20) DEFAULT NULL,
  `valeur_element` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `date_menu`, `entree`, `plat`, `garniture`, `produit_laitier`, `dessert`, `divers`, `nom_menu`, `valeur_element`) VALUES
(7, '2025-03-17', 'Macédoine', 'Nuggets Frites', 'qsbgvj,k;l:', 'Yaourt', 'Tarte au chocolat', 'dfvdfv', 'menu_17032025', 'Macédoine'),
(8, '2025-03-24', 'Concombre', 'Entrecôte de boeuf', 'Brocoli sauce béchamel', 'Yaourt', 'Crêpes au nutella', 'Pain', 'menu_24032025', 'Brocoli sauce béchamel'),
(9, '2025-03-15', 'Feuilleté aux fromages', 'Poisson', 'Epinards', 'Camembert', 'Tarte au chocolat', 'Pain à l&#039;ail', 'menu_25032025', 'Tarte au chocolat'),
(10, '2025-03-26', 'Salade César', 'Nuggets Frites', 'frites', 'Yaourt', 'Tarte au chocolat', 'pain dominos', 'menu_26032025', 'Salade César'),
(11, '2025-03-25', 'Macédoine', 'poisson', 'Haricots verts', 'Yaourt', 'Crumble à la pomme', 'Pain', 'menu_25032025', 'Haricots verts'),
(13, '2025-03-27', 'Macédoine', 'dfg', 'sdfghjkl', 'Yaourt', 'Tarte au chocolat', 'pain', 'menu_27032025', 'sdfghjkl'),
(15, '2025-03-28', 'Macédoine', 'Nuggets Frites', 'Aucun', 'Yaourt', 'Tarte au chocolat', 'Pain', 'menu_28032025', 'Aucun'),
(16, '2025-03-31', 'Salade', 'Pommes de Terre, Poulet Rôti', 'Epinards', 'Yaourt', 'Tarte au chocolat', 'Pain', 'menu_31032025', 'Pommes de Terre, Poulet Rôti');

-- --------------------------------------------------------

--
-- Table structure for table `pesee`
--

CREATE TABLE `pesee` (
  `id` int NOT NULL,
  `pesee_restes` float DEFAULT '0',
  `pesee_pain` float DEFAULT '0',
  `nb_repasprevus` int DEFAULT '0',
  `nb_repasconsommes` int DEFAULT '0',
  `nb_repasconsommesadultes` int DEFAULT '0',
  `date_menu` date DEFAULT NULL,
  `identifiant` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pesee`
--

INSERT INTO `pesee` (`id`, `pesee_restes`, `pesee_pain`, `nb_repasprevus`, `nb_repasconsommes`, `nb_repasconsommesadultes`, `date_menu`, `identifiant`) VALUES
(21, 2, 7, 15, 11, 8, '2025-03-25', 'test'),
(22, 45, 7, 450, 450, 45, '2025-03-26', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nom` varchar(150) NOT NULL,
  `adresse` varchar(150) NOT NULL,
  `identifiant` varchar(50) NOT NULL,
  `mdp` text,
  `role_profil` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nom`, `adresse`, `identifiant`, `mdp`, `role_profil`) VALUES
(7, 'test', 'test', 'test', 'test', 'User'),
(8, 'Mairie de Clichy', '47 bd de Pesaro', 'admin', 'admin', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `vote`
--

CREATE TABLE `vote` (
  `id` int NOT NULL,
  `grande_faim` int NOT NULL,
  `petite_faim` int NOT NULL,
  `aime` int NOT NULL,
  `aime_moyen` int NOT NULL,
  `aime_pas` int NOT NULL,
  `valeur_element` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `date_menu` date DEFAULT NULL,
  `identifiant` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `vote`
--

INSERT INTO `vote` (`id`, `grande_faim`, `petite_faim`, `aime`, `aime_moyen`, `aime_pas`, `valeur_element`, `date_menu`, `identifiant`) VALUES
(2, 4, 5, 1, 0, 0, 'Brocoli sauce béchamel', '2025-03-24', 'test'),
(3, 23, 20, 23, 15, 26, 'Haricots verts', '2025-03-25', 'test'),
(4, 8, 3, 5, 4, 1, 'Salade César', '2025-03-26', 'test');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_valeur_element` (`valeur_element`),
  ADD UNIQUE KEY `unique_date_menu` (`date_menu`);

--
-- Indexes for table `pesee`
--
ALTER TABLE `pesee`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pesee_users` (`identifiant`),
  ADD KEY `fk_pesee_menu_date` (`date_menu`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_identifiant` (`identifiant`);

--
-- Indexes for table `vote`
--
ALTER TABLE `vote`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_vote_menu` (`valeur_element`),
  ADD KEY `fk_vote_users` (`identifiant`),
  ADD KEY `fk_vote_menu_date` (`date_menu`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `pesee`
--
ALTER TABLE `pesee`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `vote`
--
ALTER TABLE `vote`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pesee`
--
ALTER TABLE `pesee`
  ADD CONSTRAINT `fk_pesee_menu_date` FOREIGN KEY (`date_menu`) REFERENCES `menu` (`date_menu`),
  ADD CONSTRAINT `fk_pesee_users` FOREIGN KEY (`identifiant`) REFERENCES `users` (`identifiant`);

--
-- Constraints for table `vote`
--
ALTER TABLE `vote`
  ADD CONSTRAINT `fk_vote_menu` FOREIGN KEY (`valeur_element`) REFERENCES `menu` (`valeur_element`),
  ADD CONSTRAINT `fk_vote_menu_date` FOREIGN KEY (`date_menu`) REFERENCES `menu` (`date_menu`),
  ADD CONSTRAINT `fk_vote_users` FOREIGN KEY (`identifiant`) REFERENCES `users` (`identifiant`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
