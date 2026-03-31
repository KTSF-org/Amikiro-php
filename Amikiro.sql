-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : lun. 30 mars 2026 à 09:46
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
  `IdBat` int NOT NULL,
  `Name` varchar(256) NOT NULL,
  `BirthDate` datetime NOT NULL,
  `Sex` int NOT NULL,
  `Weight` int NOT NULL,
  `Note` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `Bat`
--

INSERT INTO `Bat` (`IdBat`, `Name`, `BirthDate`, `Sex`, `Weight`, `Note`) VALUES
(1, 'Batman', '2026-03-19 11:42:29', 1, 250, 'Note oui oui baguette');

-- --------------------------------------------------------

--
-- Structure de la table `Category`
--

CREATE TABLE `Category` (
  `IdCategory` int NOT NULL,
  `Name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `Category`
--

INSERT INTO `Category` (`IdCategory`, `Name`) VALUES
(1, 'Départ chasse');

-- --------------------------------------------------------

--
-- Structure de la table `ColonySection`
--

CREATE TABLE `ColonySection` (
  `IdSection` int NOT NULL,
  `IdCategory` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ColonySection`
--

INSERT INTO `ColonySection` (`IdSection`, `IdCategory`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `Logs`
--

CREATE TABLE `Logs` (
  `IdLogs` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `Logs`
--

INSERT INTO `Logs` (`IdLogs`) VALUES
(1);

-- --------------------------------------------------------

--
-- Structure de la table `Section`
--

CREATE TABLE `Section` (
  `IdSection` int NOT NULL,
  `Title` varchar(256) NOT NULL,
  `Content` varchar(4096) NOT NULL,
  `CreationDate` datetime NOT NULL,
  `IdUser` int NOT NULL,
  `IdLogs` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `Section`
--

INSERT INTO `Section` (`IdSection`, `Title`, `Content`, `CreationDate`, `IdUser`, `IdLogs`) VALUES
(1, 'rUBRIQUE TEST', 'ghyuodyt', '2026-03-30 09:45:07', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `SpecimenSection`
--

CREATE TABLE `SpecimenSection` (
  `IdSection` int NOT NULL,
  `IdBat` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `SpecimenSection`
--

INSERT INTO `SpecimenSection` (`IdSection`, `IdBat`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `User`
--

CREATE TABLE `User` (
  `IdUser` int NOT NULL,
  `IdRole` int NOT NULL,
  `Mail` varchar(50) NOT NULL,
  `Uptime` int NOT NULL,
  `Password` varchar(256) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Surname` varchar(100) NOT NULL,
  `CountConnect` int NOT NULL,
  `MemberId` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `User`
--

INSERT INTO `User` (`IdUser`, `IdRole`, `Mail`, `Uptime`, `Password`, `Name`, `Surname`, `CountConnect`, `MemberId`) VALUES
(1, 1, 'test@mail.fr', 0, 'oui', 'Jean', 'Test', 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `Video`
--

CREATE TABLE `Video` (
  `IdVideo` int NOT NULL,
  `Title` varchar(100) NOT NULL,
  `OpenDate` datetime NOT NULL,
  `CloseDate` datetime NOT NULL,
  `URL` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `Video`
--

INSERT INTO `Video` (`IdVideo`, `Title`, `OpenDate`, `CloseDate`, `URL`) VALUES
(1, 'Titre', '2026-03-24 11:46:00', '2026-04-30 11:46:00', 'hjugegauiogfyo');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Bat`
--
ALTER TABLE `Bat`
  ADD PRIMARY KEY (`IdBat`);

--
-- Index pour la table `Category`
--
ALTER TABLE `Category`
  ADD PRIMARY KEY (`IdCategory`);

--
-- Index pour la table `ColonySection`
--
ALTER TABLE `ColonySection`
  ADD KEY `SectionConstraintColony` (`IdSection`),
  ADD KEY `CategoryConstraint` (`IdCategory`);

--
-- Index pour la table `Logs`
--
ALTER TABLE `Logs`
  ADD PRIMARY KEY (`IdLogs`);

--
-- Index pour la table `Section`
--
ALTER TABLE `Section`
  ADD PRIMARY KEY (`IdSection`),
  ADD KEY `UserConstraint` (`IdUser`),
  ADD KEY `LogsConstraint` (`IdLogs`);

--
-- Index pour la table `SpecimenSection`
--
ALTER TABLE `SpecimenSection`
  ADD KEY `BatConstraint` (`IdBat`),
  ADD KEY `SectionConstraint` (`IdSection`);

--
-- Index pour la table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`IdUser`);

--
-- Index pour la table `Video`
--
ALTER TABLE `Video`
  ADD PRIMARY KEY (`IdVideo`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Bat`
--
ALTER TABLE `Bat`
  MODIFY `IdBat` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `Category`
--
ALTER TABLE `Category`
  MODIFY `IdCategory` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `Logs`
--
ALTER TABLE `Logs`
  MODIFY `IdLogs` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `Section`
--
ALTER TABLE `Section`
  MODIFY `IdSection` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `User`
--
ALTER TABLE `User`
  MODIFY `IdUser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `Video`
--
ALTER TABLE `Video`
  MODIFY `IdVideo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `ColonySection`
--
ALTER TABLE `ColonySection`
  ADD CONSTRAINT `CategoryConstraint` FOREIGN KEY (`IdCategory`) REFERENCES `Category` (`IdCategory`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `SectionConstraintColony` FOREIGN KEY (`IdSection`) REFERENCES `Section` (`IdSection`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `Section`
--
ALTER TABLE `Section`
  ADD CONSTRAINT `LogsConstraint` FOREIGN KEY (`IdLogs`) REFERENCES `Logs` (`IdLogs`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `UserConstraint` FOREIGN KEY (`IdUser`) REFERENCES `User` (`IdUser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `SpecimenSection`
--
ALTER TABLE `SpecimenSection`
  ADD CONSTRAINT `BatConstraint` FOREIGN KEY (`IdBat`) REFERENCES `Bat` (`IdBat`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `SectionConstraint` FOREIGN KEY (`IdSection`) REFERENCES `Section` (`IdSection`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
