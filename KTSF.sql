-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : lun. 11 mai 2026 à 16:25
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
(7, 8, '2026-05-04', '2026-05-31'),
(8, 9, '2026-05-11', '2026-05-12'),
(9, 7, '2026-05-11', '2026-05-12'),
(10, 17, '2026-05-11', '2026-05-18'),
(12, 23, '2026-05-11', '2026-05-17'),
(13, 16, '2026-05-11', '2026-05-12'),
(14, 25, '2026-05-01', '2026-05-05'),
(15, 26, '2026-04-15', '2026-04-22'),
(16, 27, '2026-04-01', '2026-04-08');

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
(1, 'Départ chasse'),
(2, 'testajoutcat'),
(3, 'pause');

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
(3, 12, 1),
(4, 19, 1),
(6, 21, 2),
(7, 22, 3);

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
  `guestDefaultAccessDays` int(11) NOT NULL DEFAULT 7,
  `naturalisteDefaultAccessDays` int(11) NOT NULL DEFAULT 30
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `Config`
--

INSERT INTO `Config` (`id`, `streamUrl`, `sessionDuration`, `viewerLimit`, `viewerCount`, `guestDefaultAccessDays`, `naturalisteDefaultAccessDays`) VALUES
(1, 'https://ds1-cache.quanteec.com/contents/encodings/live/112fd325-c7cf-42fa-3032-3730-6d61-63-9dcb-e991fc0db4a4d/master.m3u8', 60, 3, 0, 7, 30);

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
  `eventDate` datetime NOT NULL,
  `modifDate` datetime NOT NULL,
  `idUser` int(11) NOT NULL,
  `idLogs` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `Section`
--

INSERT INTO `Section` (`id`, `title`, `content`, `eventDate`, `modifDate`, `idUser`, `idLogs`) VALUES
(11, 'BATGIRL LA FOLLASSE', 'elle a tapé batman', '2026-04-30 15:36:00', '0000-00-00 00:00:00', 9, 1),
(12, 'Flo la déglingue', 'miaou', '2026-04-30 15:52:00', '0000-00-00 00:00:00', 9, 1),
(13, 'BATGIRL OUAIS', 'elle a fait un calin à batman', '2026-04-30 15:36:00', '2026-05-11 15:19:38', 9, 1),
(17, 'TEST 0000006', 'hgjfhgurdhgsmjkvhreuhvdsithc gh riehfsjd lo', '2026-05-20 17:09:00', '2026-05-11 13:17:52', 9, 1),
(19, 'test', 'test', '2026-05-11 11:18:00', '0000-00-00 00:00:00', 9, 1),
(20, 'test', 'test', '2026-05-11 11:18:00', '0000-00-00 00:00:00', 9, 1),
(21, 'test', 'test', '2026-05-11 11:18:00', '0000-00-00 00:00:00', 9, 1),
(22, 'test', 'w', '2025-04-10 10:42:00', '0000-00-00 00:00:00', 9, 1);

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
  `memberNum` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `User`
--

INSERT INTO `User` (`id`, `codeRole`, `mail`, `uptime`, `password`, `name`, `surname`, `countConnect`, `memberNum`) VALUES
(7, 1, 'kevin@gmail.com', 45, '$2y$12$/79CKTFEyeNJPalcHbpOK.056ZH3KE6xjxvNkRiNI8dPho6j88Ubu', 'Kevin', 'Bidoof', 17, 'AMI-2026-001'),
(8, 1, 'simon@gmail.com', 45, '$2y$12$SeIcT4vxBC834zm/2npQ7.jPcCgWc/.rM5zS3TfEyQMTj/QeYxTYW', 'Simon', 'Gemini', 23, 'AMI-2026-002'),
(9, 2, 'tani@gmail.com', 45, '$2y$12$lylhaRrLUjnu9lSZsZ.hxOiXm8T4KGfgXMpk62pkQMXTrLdKwrrRe', 'Tani', 'OUI', 51, 'AMI-2026-003'),
(10, 3, 'florian@gmail.com', 20, '$2y$12$TCupomw.4bsf0rdF3GUIk.UhduLByjRzyfTzTVtVSnZNhoshs8iFe', 'Florian', 'CS2', 41, 'AMI-2026-004'),
(16, 2, 'natu@gmail.com', 0, '$2y$12$g.PQywz.oNXYpYClqjm7H.sX1TmcPpApBQq8GMeLgD0rRufLAVv3O', 'Naturaliste', 'Nat', 3, NULL),
(17, 0, 'Inv@gmail.com', 0, '$2y$12$lCoPOMe34D3fy9jiIuJTNOuR5JHyG6U3A5HmkdNM8yT644UAt.6zm', 'Jean', 'De la Mime', 2, NULL),
(22, 2, 'herehiaus@gmail.com', 0, '$2y$12$k46l7WFdNPtUXYOAE88kiO5m69CXBW9B7k5W8JHcGoTSS92DwV5ZW', 'Herehia', 'US', 0, NULL),
(23, 1, 'florian.dev56@gmail.com', 0, '$2y$12$hsDS.9lavkpszkTE0jAF6umkkaW8Ii1yrjLG4Bx97AdtYWS47x4Va', 'Florian', 'Edouard', 1, 'AMI-2026-0005'),
(24, 2, 'opuutanihiarii@gmail.com', 0, '$2y$12$Wi/yxj6JzwtjakRyDlaUEupaXbU062rAtMtrIHU7AmyOwfw/GR9Ga', 'Tanihiarii', 'OPUU', 1, NULL),
(25, 0, 'marie.dupont@gmail.com', 0, '$2y$12$lylhaRrLUjnu9lSZsZ.hxOiXm8T4KGfgXMpk62pkQMXTrLdKwrrRe', 'Marie', 'Dupont', 1, NULL),
(26, 0, 'paul.martin@gmail.com', 0, '$2y$12$lylhaRrLUjnu9lSZsZ.hxOiXm8T4KGfgXMpk62pkQMXTrLdKwrrRe', 'Paul', 'Martin', 2, NULL),
(27, 0, 'clara.petit@gmail.com', 0, '$2y$12$lylhaRrLUjnu9lSZsZ.hxOiXm8T4KGfgXMpk62pkQMXTrLdKwrrRe', 'Clara', 'Petit', 0, NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `Bat`
--
ALTER TABLE `Bat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT pour la table `Category`
--
ALTER TABLE `Category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `ColonySection`
--
ALTER TABLE `ColonySection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

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
  ADD CONSTRAINT `LogsConstraint` FOREIGN KEY (`idLogs`) REFERENCES `Logs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
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
