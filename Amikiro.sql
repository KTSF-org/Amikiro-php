-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : mar. 07 avr. 2026 à 10:07
-- Version du serveur : 8.4.7
-- Version de PHP : 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `Amikiro`
--

-- --------------------------------------------------------

--
-- Structure de la table `Bat`
--

CREATE TABLE `Bat` (
  `id` int NOT NULL,
  `name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `birthDate` datetime NOT NULL,
  `sex` int NOT NULL,
  `weight` int NOT NULL,
  `note` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `Bat`
--

INSERT INTO `Bat` (`id`, `name`, `birthDate`, `sex`, `weight`, `note`) VALUES
(1, 'Batman', '2026-03-19 11:42:29', 1, 250, 'Note oui oui baguette');

-- --------------------------------------------------------

--
-- Structure de la table `Category`
--

CREATE TABLE `Category` (
  `id` int NOT NULL,
  `name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `Category`
--

INSERT INTO `Category` (`id`, `name`) VALUES
(1, 'Départ chasse');

-- --------------------------------------------------------

--
-- Structure de la table `ColonySection`
--

CREATE TABLE `ColonySection` (
  `idSection` int NOT NULL,
  `idCategory` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Logs`
--

CREATE TABLE `Logs` (
  `id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `Logs`
--

INSERT INTO `Logs` (`id`) VALUES
(1);

-- --------------------------------------------------------

--
-- Structure de la table `Section`
--

CREATE TABLE `Section` (
  `id` int NOT NULL,
  `title` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `content` varchar(4096) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `creationDate` datetime NOT NULL,
  `idUser` int NOT NULL,
  `IdLogs` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `SpecimenSection`
--

CREATE TABLE `SpecimenSection` (
  `idSection` int NOT NULL,
  `idBat` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `User`
--

CREATE TABLE `User` (
  `id` int NOT NULL,
  `codeRole` int NOT NULL,
  `mail` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `uptime` int NOT NULL,
  `password` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `surname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `countConnect` int NOT NULL,
  `memberNum` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `User`
--

INSERT INTO `User` (`id`, `codeRole`, `mail`, `uptime`, `password`, `name`, `surname`, `countConnect`, `memberNum`) VALUES
(7, 0, 'kevin@gmail.com', 45, '$2y$12$Hpnzv/jsBxu2OkDuwOrVie6WacUxAJA.mhSjXoTOXXrPkyoe4ls.m', 'Kevin', 'Bidoof', 16, 26),
(8, 1, 'simon@gmail.com', 45, '$2y$12$iRJ8aapSwIs43BB4ufvwQuZF.Xzy/tTHArdCkwP3oMBHUBhd9ZlXa', 'Simon', 'GPT', 20, 29),
(9, 2, 'tani@gmail.com', 45, '$2y$12$GWJ1rMAS91C5wUhOjmO9iuci1/eRK0RhYuvenK8CeUUfBRPPAo//a', 'Tani', 'OUI', 40, 6),
(10, 3, 'florian@gmail.com', 20, '$2y$12$a9EFeMArbCkmku5q3D6Keuh2286jCl9EgFxG0yrGj.dsX2ttotnZO', 'Florian', 'CS2', 41, 15);

-- --------------------------------------------------------

--
-- Structure de la table `Video`
--

CREATE TABLE `Video` (
  `id` int NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `openDate` datetime NOT NULL,
  `closeDate` datetime NOT NULL,
  `url` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `Video`
--

INSERT INTO `Video` (`id`, `title`, `openDate`, `closeDate`, `url`) VALUES
(1, 'Titre', '2026-03-24 11:46:00', '2026-04-30 11:46:00', 'hjugegauiogfyo');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Bat`
--
ALTER TABLE `Bat`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Category`
--
ALTER TABLE `Category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ColonySection`
--
ALTER TABLE `ColonySection`
  ADD KEY `SectionConstraintColony` (`idSection`),
  ADD KEY `CategoryConstraint` (`idCategory`);

--
-- Index pour la table `Logs`
--
ALTER TABLE `Logs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Section`
--
ALTER TABLE `Section`
  ADD PRIMARY KEY (`id`),
  ADD KEY `UserConstraint` (`idUser`),
  ADD KEY `LogsConstraint` (`IdLogs`);

--
-- Index pour la table `SpecimenSection`
--
ALTER TABLE `SpecimenSection`
  ADD KEY `BatConstraint` (`idBat`),
  ADD KEY `SectionConstraint` (`idSection`);

--
-- Index pour la table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Video`
--
ALTER TABLE `Video`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Bat`
--
ALTER TABLE `Bat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `Category`
--
ALTER TABLE `Category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `Logs`
--
ALTER TABLE `Logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `Section`
--
ALTER TABLE `Section`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `User`
--
ALTER TABLE `User`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `Video`
--
ALTER TABLE `Video`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `ColonySection`
--
ALTER TABLE `ColonySection`
  ADD CONSTRAINT `CategoryConstraint` FOREIGN KEY (`idCategory`) REFERENCES `Category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `SectionConstraintColony` FOREIGN KEY (`idSection`) REFERENCES `Section` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `Section`
--
ALTER TABLE `Section`
  ADD CONSTRAINT `LogsConstraint` FOREIGN KEY (`IdLogs`) REFERENCES `Logs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `UserConstraint` FOREIGN KEY (`idUser`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `SpecimenSection`
--
ALTER TABLE `SpecimenSection`
  ADD CONSTRAINT `BatConstraint` FOREIGN KEY (`idBat`) REFERENCES `Bat` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `SectionConstraint` FOREIGN KEY (`idSection`) REFERENCES `Section` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
