-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : lun. 04 mai 2026 à 15:52
-- Version du serveur :  10.3.29-MariaDB
-- Version de PHP : 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `KTSF`
--

-- --------------------------------------------------------

--
-- Structure de la table `Abonnement`
--

CREATE TABLE `Abonnement` (
  `id` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `Abonnement`
--

INSERT INTO `Abonnement` (`id`, `idUser`, `startDate`, `endDate`) VALUES
(1, 9, '2026-05-04', '2026-05-05'),
(6, 8, '2026-05-04', '2026-05-05'),
(7, 8, '2026-05-04', '2026-05-31');

-- --------------------------------------------------------

--
-- Structure de la table `Bat`
--

CREATE TABLE `Bat` (
  `id` int(11) NOT NULL,
  `name` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `idSpecies` int(11) NOT NULL,
  `birthDate` datetime NOT NULL,
  `sex` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `note` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `Bat`
--

INSERT INTO `Bat` (`id`, `name`, `idSpecies`, `birthDate`, `sex`, `weight`, `note`) VALUES
(2, 'Batman', 1, '2026-04-01 12:00:00', 2, 250, 'ATTENTION VOILA BATMAN??????'),
(6, 'BatGirl', 1, '2026-04-01 18:00:00', 1, 300, 'Batgirl is in the place');

-- --------------------------------------------------------

--
-- Structure de la table `Category`
--

CREATE TABLE `Category` (
  `id` int(11) NOT NULL,
  `name` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `id` int(11) NOT NULL,
  `idSection` int(11) NOT NULL,
  `idCategory` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ColonySection`
--

INSERT INTO `ColonySection` (`id`, `idSection`, `idCategory`) VALUES
(2, 3, 1),
(3, 12, 1);

-- --------------------------------------------------------

--
-- Structure de la table `Config`
--

CREATE TABLE `Config` (
  `id` int(11) NOT NULL,
  `streamUrl` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sessionDuration` int(11) NOT NULL DEFAULT 3600,
  `viewerLimit` int(11) NOT NULL DEFAULT 10,
  `viewerCount` int(11) NOT NULL DEFAULT 0,
  `guestDefaultAccessDays` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `Config`
--

INSERT INTO `Config` (`id`, `streamUrl`, `sessionDuration`, `viewerLimit`, `viewerCount`, `guestDefaultAccessDays`) VALUES
(1, 'https://s40.ipcamlive.com/streams/28gra6mohj2ruybvy/stream.m3u8', 3600, 10, 0, 7);

-- --------------------------------------------------------

--
-- Structure de la table `Logs`
--

CREATE TABLE `Logs` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `id` int(11) NOT NULL,
  `title` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` varchar(4096) COLLATE utf8mb4_unicode_ci NOT NULL,
  `creationDate` datetime NOT NULL,
  `idUser` int(11) NOT NULL,
  `idLogs` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `Section`
--

INSERT INTO `Section` (`id`, `title`, `content`, `creationDate`, `idUser`, `idLogs`) VALUES
(3, 'baguettee', 'frzerzere', '2026-04-08 15:34:41', 7, 1),
(4, 'bidoof', 'bfqhgipugouydystsresr', '2026-04-15 14:22:00', 9, 1),
(7, 'SALUT', 'bite', '2026-04-04 14:25:00', 9, 1),
(8, 'SALUT', 'bite', '2026-04-04 14:25:00', 9, 1),
(9, 'SALUT', 'bite', '2026-04-04 14:25:00', 9, 1),
(11, 'BATGIRL LA FOLLASSE', 'elle a tapé batman', '2026-04-30 15:36:00', 9, 1),
(12, 'Flo la déglingue', 'miaou', '2026-04-30 15:52:00', 9, 1),
(13, 'BATGIRL OUAIS', 'elle a tapé batman', '2026-04-30 15:36:00', 9, 1),
(17, 'TEST 0000006', 'hgjfhgurdhgsmjkvhreuhvdsithc gh riehfsjd lo', '2026-05-20 17:09:00', 9, 1),
(18, 'toi tu vire', 'adieu', '2026-05-07 14:16:00', 9, 1);

-- --------------------------------------------------------

--
-- Structure de la table `Species`
--

CREATE TABLE `Species` (
  `id` int(11) NOT NULL,
  `scientificName` varchar(256) NOT NULL,
  `commonName` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Species`
--

INSERT INTO `Species` (`id`, `scientificName`, `commonName`) VALUES
(1, 'Chiroptères', 'Chauve-souris'),
(4, 'TEST01', 'test un');

-- --------------------------------------------------------

--
-- Structure de la table `SpecimenSection`
--

CREATE TABLE `SpecimenSection` (
  `id` int(11) NOT NULL,
  `idSection` int(11) NOT NULL,
  `idBat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `SpecimenSection`
--

INSERT INTO `SpecimenSection` (`id`, `idSection`, `idBat`) VALUES
(2, 4, 2),
(5, 11, 6),
(6, 13, 6),
(7, 17, 2);

-- --------------------------------------------------------

--
-- Structure de la table `User`
--

CREATE TABLE `User` (
  `id` int(11) NOT NULL,
  `codeRole` int(11) NOT NULL,
  `mail` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uptime` int(11) NOT NULL,
  `password` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `countConnect` int(11) NOT NULL,
  `memberNum` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `User`
--

INSERT INTO `User` (`id`, `codeRole`, `mail`, `uptime`, `password`, `name`, `surname`, `countConnect`, `memberNum`) VALUES
(7, 0, 'kevin@gmail.com', 45, '$2y$12$/79CKTFEyeNJPalcHbpOK.056ZH3KE6xjxvNkRiNI8dPho6j88Ubu', 'Kevin', 'Bidoof', 16, 'AMI-2026-001'),
(8, 1, 'simon@gmail.com', 45, '$2y$12$slf5Z6Z6WZzy6msircaMLOakfhDT6W6kHnCg9Au1hrzUbeeOv4/M6', 'Simon', 'Gemini', 20, 'AMI-2026-002'),
(9, 2, 'tani@gmail.com', 45, '$2y$12$lylhaRrLUjnu9lSZsZ.hxOiXm8T4KGfgXMpk62pkQMXTrLdKwrrRe', 'Tani', 'OUI', 44, 'AMI-2026-003'),
(10, 3, 'florian@gmail.com', 20, '$2y$12$AjQtWIQ4tUBZztIJ8OOLOe0WK8dCto2CAm829TueyQj/N4SdeKabO', 'Florian', 'CS2', 41, 'AMI-2026-004');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Abonnement`
--
ALTER TABLE `Abonnement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `AbonnementUserConstraint` (`idUser`);

--
-- Index pour la table `Bat`
--
ALTER TABLE `Bat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `constraintSpecies` (`idSpecies`);

--
-- Index pour la table `Category`
--
ALTER TABLE `Category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ColonySection`
--
ALTER TABLE `ColonySection`
  ADD PRIMARY KEY (`id`),
  ADD KEY `SectionConstraintColony` (`idSection`),
  ADD KEY `CategoryConstraint` (`idCategory`);

--
-- Index pour la table `Config`
--
ALTER TABLE `Config`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `LogsConstraint` (`idLogs`);

--
-- Index pour la table `Species`
--
ALTER TABLE `Species`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `SpecimenSection`
--
ALTER TABLE `SpecimenSection`
  ADD PRIMARY KEY (`id`),
  ADD KEY `BatConstraint` (`idBat`),
  ADD KEY `SectionConstraint` (`idSection`);

--
-- Index pour la table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Abonnement`
--
ALTER TABLE `Abonnement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `Bat`
--
ALTER TABLE `Bat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT pour la table `Category`
--
ALTER TABLE `Category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `ColonySection`
--
ALTER TABLE `ColonySection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `Config`
--
ALTER TABLE `Config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `Logs`
--
ALTER TABLE `Logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `Section`
--
ALTER TABLE `Section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `Species`
--
ALTER TABLE `Species`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `SpecimenSection`
--
ALTER TABLE `SpecimenSection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `User`
--
ALTER TABLE `User`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Abonnement`
--
ALTER TABLE `Abonnement`
  ADD CONSTRAINT `AbonnementUserConstraint` FOREIGN KEY (`idUser`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `Bat`
--
ALTER TABLE `Bat`
  ADD CONSTRAINT `constraintSpecies` FOREIGN KEY (`idSpecies`) REFERENCES `Species` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
