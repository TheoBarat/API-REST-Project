-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 31 mars 2023 à 16:23
-- Version du serveur : 10.4.27-MariaDB
-- Version de PHP : 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `blog`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `Id_Article` int(11) NOT NULL,
  `Contenu` text DEFAULT NULL,
  `Date_Publication` date DEFAULT NULL,
  `Id_User` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`Id_Article`, `Contenu`, `Date_Publication`, `Id_User`) VALUES
(24, 'Retour Vers Le Futur - Dans la première ébauche de scénario, ce qui devait faire office de machine à remonter le temps était ... un réfrigérateur. Si l\'idée fut abandonnée, c\'est car Spielberg et Zemeckis avaient peur que des enfants ne grimpent dans leur réfrigérateur pour imiter le film et ne se piègent à l\'intérieur. ', '2023-03-29', 2),
(25, 'Retour Vers le futur - Le prénom du personnage de Emmett \'Doc\' Brown correspond en fait au mot anglais \'time\' (temps), mis à l\'envers et décomposé en deux syllabes (Em-It). Son deuxième prénom étant Lathrop, Emmet Lathrop devient Time Portal.', '2023-03-29', 2),
(26, 'Sherlock Holmes - Avant d\'être la maison de Sherlock Holmes dans le film, le décor avait servi pour celle de Sirius Black dans Harry Potter et l\'Ordre du Phénix.', '2023-03-29', 2),
(27, 'Rebelle - Rebelle est le premier film de Pixar à avoir une femme en personnage principal.', '2023-03-29', 2),
(28, 'The Amazing Spider-Man - Caméo très drôle de Stan Lee (créateur original du comic book). Il joue le bibliothécaire qui, à cause de son casque sur les oreilles, n\'entends pas le combat qui se déroule dans l\'enceinte même de sa bibliothèque', '2023-03-29', 3),
(29, 'The Amazing Spider-Man - Pour incarner au mieux le personnage du Dr. Curt Connors, l\'acteur Rhys Ifans s\'est habitué pendant plusieurs semaines à ne vivre qu\'avec un seul bras, comme son personnage. Il a ainsi fixé son bras droit dans son dos, et s\'est entrainé à nouer sa cravate et à se faire à manger dans ces conditions.', '2023-03-29', 3),
(30, 'Thor - Tom Hiddleston avait auditionné pour le rôle de Thor, avant de finalement revêtir le costume de Loki.', '2023-03-29', 3),
(31, 'Thor - Chris Hemsworth a du faire un travail de préparation physique de quatre mois pour incarner le musculeux Thor. Mais l\'acteur, par excès de zèle, était tellement sculpté par son entrainement qu\'il a ensuite dû perdre un peu de poids pendant les deux dernières semaines précédant le tournage.', '2023-03-29', 3),
(32, 'Shutter Island - Le personnage joué par Leonardo DiCaprio a un pansement sur la tête tout au long de son \'enquête\' et ne le retire qu\'une fois la vérité dévoilée. Le pansement symbolise la blessure du personnage et le fait qu\'il ne l\'ait plus à la fin symbolise la guérison.', '2023-03-29', 3),
(33, 'Zodiac - Le réalisateur David Fincher a été, enfant, marqué par cette affaire. Habitant la région, il utilisait le bus scolaires qu\'un suspect se revendiquant comme étant le tueur du Zodiaque avait menacé de faire sauter. Sa voisine était une des policières enquêtant sur l\'affaire et il avait pique-niqué en famille au Lac Berryessa, juste après qu\'un des meurtres du Zodiac y fut commis. ', '2023-03-29', 3),
(34, 'Zodiac - En cours de tournage, \'Zodiac\' adoptait le titre de travail de \'Chronicles\' en référence au journal \'San Francisco Chronicles\' que le tueur du Zodiaque utilisa comme moyen de communication pour narguer les autorités américaines.', '2023-03-29', 3),
(35, 'Kill Bill 1 -  Le réalisateur, Quentin Tarantino, s\'est lui aussi soumis à l\'entraînement qu\'il imposa à ses comédiens, d\'une part pour subir ce que les acteurs ont pu endurer, et d\'autre part pour effectuer les mouvements précis qu\'il attendait de ses interprètes lors des prises. ', '2023-03-29', 4),
(36, 'Kill Bill 1 - Le combat de sabre de Black Mamba (Uma Thurman) contre 88 assaillants a nécessité quelque huit semaines de tournage. ', '2023-03-29', 4),
(37, 'Kill Bill 1 - Kill Bill est sorti au Japon dans une version différente de la version réservée au reste du monde. Plus gores (certains plans été rallongés), les scènes violente de la version japonaise sont présentées en couleur, alors qu\'elles sont en noir et blanc dans le montage international.  ', '2023-03-29', 4),
(38, 'Les Dents de la mer - Dans le film, le requin-tueur se prénomme Bruce. En effet, c\'était le surnom des 3 requins mécaniques utilisés sur le tournage, surnom donné par le réalisateur en référence au prénom de son avocat, Bruce Ramer. ', '2023-03-29', 4),
(39, 'Les Dents de la mer - Le fragment d\'un thème de la musique du film semble apparaître dans le générique du journal télévisé de TF1. La démonstration fait état d\'un passage dont le tempo aurait été ralenti. ', '2023-03-29', 4);

-- --------------------------------------------------------

--
-- Structure de la table `interagir`
--

CREATE TABLE `interagir` (
  `Id_User` int(11) NOT NULL,
  `Id_Article` int(11) NOT NULL,
  `A_Like` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `Id_User` int(11) NOT NULL,
  `Login` varchar(50) DEFAULT NULL,
  `Password` varchar(50) DEFAULT NULL,
  `Role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`Id_User`, `Login`, `Password`, `Role`) VALUES
(1, 'youngway', 'iutinfo', 'moderator'),
(2, 'theo', 'iutinfo', 'publisher'),
(3, 'titouan', 'iutinfo', 'publisher'),
(4, 'baptiste', 'iutinfo', 'publisher'),
(6, 'administrator', 'iutinfo', 'moderator');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`Id_Article`),
  ADD KEY `Id_User` (`Id_User`);

--
-- Index pour la table `interagir`
--
ALTER TABLE `interagir`
  ADD PRIMARY KEY (`Id_User`,`Id_Article`),
  ADD KEY `Id_Article` (`Id_Article`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id_User`),
  ADD UNIQUE KEY `UC_users_login` (`Login`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `Id_Article` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `Id_User` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`Id_User`) REFERENCES `users` (`Id_User`);

--
-- Contraintes pour la table `interagir`
--
ALTER TABLE `interagir`
  ADD CONSTRAINT `interagir_ibfk_1` FOREIGN KEY (`Id_User`) REFERENCES `users` (`Id_User`),
  ADD CONSTRAINT `interagir_ibfk_2` FOREIGN KEY (`Id_Article`) REFERENCES `article` (`Id_Article`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
