-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 02 oct. 2023 à 10:12
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_conge`
--

-- --------------------------------------------------------

--
-- Structure de la table `agent`
--

CREATE TABLE `agent` (
  `id_agent` int(50) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `statut` tinyint(1) NOT NULL,
  `acquis` float NOT NULL,
  `solde` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `agent`
--

INSERT INTO `agent` (`id_agent`, `nom`, `mdp`, `statut`, `acquis`, `solde`) VALUES
(1, 'Antsonantenaina', '$2y$10$0x9i4EBCr7OaG7VjS67JKeqANV06jl./fH.FZbvkuLNkrjvaJCkF2', 1, 90, 73),
(2, 'Anarana', '$2y$10$Mz1TcaMKFXTLlsVIqFtBaeo0LcYHZjIeoPRzQFPd5VS3hCZph1bVO', 0, 5, 3),
(3, 'Anarana2', '$2y$10$3QcMojBlWPvxN152R43.Xe7jxVICrQWbK/NFmQczugjX90dThaLQ.', 0, 0, 0),
(4, 'Madara', '$2y$10$ssrgB3URLE3Y8MhFDcfKI.Aa6MmNEtuFo140j2tHlmUjotgqya26.', 0, 0, 0),
(5, 'Zatovo', '$2y$10$pRqvY5EohGQiD6pQgN20ROk/Bv9Qb9Hu.EPQ7JcR4m0osUsVslfw6', 0, 0, 0),
(6, 'Grimmjow', '$2y$10$8qgSdhPeh/lAblyFInxSHOHcubyj.tdZklAmGHbaXE9k1UxuT6.82', 0, 0, 0),
(7, 'Yahiko', '$2y$10$.lYNAZr0EeViqpl/mRIdvepnz8msnaJDrconf4BkmMmpiQXySKB9u', 0, 0, 0),
(8, 'Hallibel', '$2y$10$UDX71dE3K/rZlEZZx3fF8.JNxxfoL2P9FImqtji5i0yF/Yfv243ca', 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `appartenance`
--

CREATE TABLE `appartenance` (
  `id_appartenance` int(11) NOT NULL,
  `id_groupe` int(11) NOT NULL,
  `id_agent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `appartenance`
--

INSERT INTO `appartenance` (`id_appartenance`, `id_groupe`, `id_agent`) VALUES
(1, 1, 4),
(2, 2, 6),
(3, 1, 7),
(4, 2, 8),
(6, 2, 1),
(7, 1, 5),
(18, 1, 2),
(23, 2, 3),
(25, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `demande`
--

CREATE TABLE `demande` (
  `id_demande` int(11) NOT NULL,
  `date_demande` datetime NOT NULL DEFAULT current_timestamp(),
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `duree` float NOT NULL,
  `etat` varchar(20) NOT NULL,
  `type_absence` varchar(20) NOT NULL,
  `motif` varchar(255) NOT NULL,
  `chevauchement` int(11) NOT NULL DEFAULT 0,
  `motif_rejet` text DEFAULT NULL,
  `id_agent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `demande`
--

INSERT INTO `demande` (`id_demande`, `date_demande`, `date_debut`, `date_fin`, `duree`, `etat`, `type_absence`, `motif`, `chevauchement`, `motif_rejet`, `id_agent`) VALUES
(1, '2023-10-02 10:32:57', '2023-10-09', '2023-10-13', 5, 'acceptée', 'permission', 'Demande principale ', 0, NULL, 1),
(2, '2023-10-02 10:33:43', '2023-10-10', '2023-10-10', 1, 'en attente', 'permission', 'test', 1, NULL, 2),
(3, '2023-10-02 10:35:05', '2023-10-03', '2023-10-03', 1, 'en attente', 'permission', 'test 2', 0, NULL, 2);

-- --------------------------------------------------------

--
-- Structure de la table `groupe`
--

CREATE TABLE `groupe` (
  `id_groupe` int(255) NOT NULL,
  `nom_groupe` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `groupe`
--

INSERT INTO `groupe` (`id_groupe`, `nom_groupe`) VALUES
(1, 'Akatsuki'),
(2, 'Espadas');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `agent`
--
ALTER TABLE `agent`
  ADD PRIMARY KEY (`id_agent`);

--
-- Index pour la table `appartenance`
--
ALTER TABLE `appartenance`
  ADD PRIMARY KEY (`id_appartenance`),
  ADD KEY `id_agent` (`id_agent`),
  ADD KEY `id_groupe` (`id_groupe`);

--
-- Index pour la table `demande`
--
ALTER TABLE `demande`
  ADD PRIMARY KEY (`id_demande`),
  ADD KEY `id_agent` (`id_agent`);

--
-- Index pour la table `groupe`
--
ALTER TABLE `groupe`
  ADD PRIMARY KEY (`id_groupe`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `appartenance`
--
ALTER TABLE `appartenance`
  MODIFY `id_appartenance` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `demande`
--
ALTER TABLE `demande`
  MODIFY `id_demande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `appartenance`
--
ALTER TABLE `appartenance`
  ADD CONSTRAINT `appartenance_ibfk_1` FOREIGN KEY (`id_agent`) REFERENCES `agent` (`id_agent`),
  ADD CONSTRAINT `appartenance_ibfk_2` FOREIGN KEY (`id_groupe`) REFERENCES `groupe` (`id_groupe`);

--
-- Contraintes pour la table `demande`
--
ALTER TABLE `demande`
  ADD CONSTRAINT `demande_ibfk_1` FOREIGN KEY (`id_agent`) REFERENCES `agent` (`id_agent`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
