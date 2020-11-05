-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  jeu. 20 août 2020 à 08:55
-- Version du serveur :  10.1.37-MariaDB
-- Version de PHP :  7.3.0

SET SQL_MODE
= "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT
= 0;
START TRANSACTION;
SET time_zone
= "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  classicmodels
--

-- --------------------------------------------------------

--
-- Structure de la table pays
--

CREATE TABLE pays
(
    id int(11) NOT NULL,
    code varchar(2) NOT NULL,
    nom varchar(50) NOT NULL
)
ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table pays
--

INSERT INTO pays
    (id, code, nom)
VALUES
    (1, 'CA', 'Canada'),
    (2, 'US', 'États-Unis'),
    (3, 'MX', 'Mexique'),
    (4, 'FR', 'France'),
    (5, 'AU', 'Autre');

-- --------------------------------------------------------

--
-- Structure de la table provinces
--

CREATE TABLE provinces
(
    id int(11) NOT NULL,
    code varchar(2) NOT NULL,
    nom varchar(50) NOT NULL
)
ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table provinces
--

INSERT INTO provinces
    (id, code, nom)
VALUES
    (1, 'QC', 'Québec'),
    (2, 'ON', 'Ontario'),
    (3, 'AB', 'Alberta'),
    (4, 'MB', 'Manitoba'),
    (6, 'SK', 'Saskatchewan'),
    (7, 'NB', 'Nouveau-Brunswick'),
    (8, 'NS', 'Nouvelle-Écosse'),
    (9, 'NL', 'Terre-Neuve et Labrador'),
    (10, 'PE', 'Île-de-Prince-Edward'),
    (11, 'YT', 'Yukon'),
    (12, 'BC', 'Colombie-Britannique'),
    (13, 'NU', 'Nunavut'),
    (14, 'NT', 'Territoires-du-Nord-Ouest');

-- --------------------------------------------------------

--
-- Structure de la table users
--

CREATE TABLE users
(
    id int(11) NOT NULL,
    fullname varchar(50) NOT NULL,
    adresse varchar(255) DEFAULT NULL,
    ville varchar(50) DEFAULT NULL,
    province varchar(2) DEFAULT NULL,
    pays varchar(2) DEFAULT NULL,
    code_postal varchar(7) DEFAULT NULL,
    langue varchar(2) NOT NULL,
    autre_langue varchar(25) DEFAULT NULL,
    email varchar(126) NOT NULL,
    pw varchar(8) NOT NULL,
    spam_ok tinyint(1) NOT NULL DEFAULT '1'
)
ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table users
--

INSERT INTO users
    (id, fullname, adresse, ville, province, pays, code_postal, langue, autre_langue, email, pw, spam_ok)
VALUES
    (1, 'Luc Lavoie', '123 rue Principale', 'Saint-Jean', 'QC', 'CA', 'H8Y-1G7', 'fr', '', 'lavoie@gmail.com', '12345678', 1),
    (2, 'Yannick Lebreux', '3456 St-Denis', 'Montréal', 'QC', 'CA', 'H1T-2R6', 'an', '', 'Yannick@gmail.com', '12345678', 0),
    (3, 'Victor Boucher', '', '', 'QC', 'CA', '', 'au', 'espagnol', 'Victor@test.com', '11111111', 1),
    (4, 'Christian Ross', '', '', 'QC', 'CA', '', 'fr', '', 'Christian@victoire.ca', '22222222', 1),
    (5, 'Angéline Bernier', '867 2ième rang', 'Sainte-Catherine-de-la-Jacques-Cartier', 'ON', 'CA', 'a1b-2l5', 'fr', '', 'angel@gmail.com', 'abcdef', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table pays
--
ALTER TABLE pays
  ADD PRIMARY KEY (id)
,
ADD UNIQUE KEY code
(code),
ADD UNIQUE KEY nom
(nom);

--
-- Index pour la table provinces
--
ALTER TABLE provinces
  ADD PRIMARY KEY (id)
,
ADD UNIQUE KEY code
(code),
ADD UNIQUE KEY nom
(nom);

--
-- Index pour la table users
--
ALTER TABLE users
  ADD PRIMARY KEY (id)
,
ADD UNIQUE KEY email
(email);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table pays
--
ALTER TABLE pays
  MODIFY id int
(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table provinces
--
ALTER TABLE provinces
  MODIFY id int
(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table users
--
ALTER TABLE users
  MODIFY id int
(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
