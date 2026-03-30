-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `apptest`
--

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id_client` int(11) NOT NULL,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  `email` varchar(70) NOT NULL,
  `dateNaissance` date NOT NULL,
  `ville` varchar(30) NOT NULL,
  `enfants` varchar(30) NOT NULL,
  `tel` varchar(30) NOT NULL,
  `avatar` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id_client`, `nom`, `prenom`, `email`, `dateNaissance`, `ville`, `enfants`, `tel`, `avatar`) VALUES
(1, 'Léponge', 'Bob', 'robert@sponge.us', '1999-10-20', 'Los Angeles', '0', '0607080910', 'd3b8a1e9dc7e3d904ddb9f4434283602'),
(2, 'Curry', 'Marie', 'marie@spice.in', '1978-12-15', 'Calcutta', '1', '0203040506','b190f20c3e873e62a5d46e6f621932c3'),
(3, 'Paulo', 'Marco', 'paulo@discover.it', '1986-03-25', 'Palerme', '3', '0708091011', '0b9717d93f90b7e687cb394e6a9ee2e4'),
(4, 'Dark', 'Jane', 'jd@war.co.uk', '1648-09-09', 'Rio', '0', '0669707172', '3e7cf31bfb933363dfbf181c78a4c353');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id_client`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
