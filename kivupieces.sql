-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 03, 2026 at 11:38 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kivupieces`
--

-- --------------------------------------------------------

--
-- Table structure for table `adresses`
--

CREATE TABLE `adresses` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `adresse_ligne1` varchar(255) NOT NULL,
  `adresse_ligne2` varchar(255) DEFAULT NULL,
  `ville` varchar(100) NOT NULL,
  `code_postal` varchar(20) NOT NULL,
  `pays` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `avis`
--

CREATE TABLE `avis` (
  `id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `note` int(11) NOT NULL CHECK (`note` between 1 and 5),
  `commentaire` text DEFAULT NULL,
  `statut` enum('en_attente','approuve','rejete') DEFAULT 'en_attente',
  `date_avis` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `nom`, `slug`, `created_at`) VALUES
(1, 'Carrosserie ', 'carrosserie-', '2025-04-26 18:50:39'),
(2, 'Carrosserie ', 'carrosserie-', '2025-04-26 19:43:51'),
(3, 'Carrosserie ', 'carrosserie-', '2025-05-03 12:26:59'),
(4, 'Suspension', 'suspension', '2025-05-07 07:46:09'),
(5, 'Pneus', 'pneus', '2025-05-07 07:53:49'),
(6, 'Intérieur ', 'intérieur-', '2025-05-07 08:02:02'),
(7, 'Freinage', 'freinage', '2025-05-07 08:06:41'),
(8, 'Éclairage', 'Éclairage', '2025-05-07 08:11:20'),
(9, 'Moteur', 'moteur', '2025-05-07 08:13:31'),
(10, 'Échappement ', 'Échappement-', '2025-05-07 08:16:14'),
(11, 'Carrosserie ', 'carrosserie-', '2025-05-07 08:18:26'),
(12, 'Électricité ', 'Électricité-', '2025-05-07 08:23:36'),
(13, 'Éclairage', 'Éclairage', '2025-05-07 08:32:40');

-- --------------------------------------------------------

--
-- Table structure for table `commandes`
--

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `prenom` varchar(100) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `adresse_ligne1` varchar(255) NOT NULL,
  `adresse_ligne2` varchar(255) DEFAULT NULL,
  `ville` varchar(100) NOT NULL,
  `etat` varchar(100) DEFAULT NULL,
  `code_postal` varchar(20) NOT NULL,
  `pays` varchar(100) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `statut` enum('en_attente','traitee','expediee','livree','annulee') DEFAULT 'en_attente',
  `date_commande` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `commande_produits`
--

CREATE TABLE `commande_produits` (
  `id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `images_produits`
--

CREATE TABLE `images_produits` (
  `id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `ordre` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images_produits`
--

INSERT INTO `images_produits` (`id`, `produit_id`, `image_url`, `ordre`, `created_at`) VALUES
(4, 4, '681b0fc184ad6_71V8uFVk9LL._AC_UF1000,1000_QL80_.jpg', 0, '2025-05-07 07:46:09'),
(5, 4, '681b0fc186a33_images (8).jpg', 1, '2025-05-07 07:46:09'),
(6, 4, '681b0fc188a4d_images (11).jpg', 2, '2025-05-07 07:46:09'),
(7, 5, '681b118ddad88_4w-238_3528700093414_tire_michelin_pilot-sport-4_245-slash-40-zr18-97y-xl_a_main_4-90_nopad.webp', 0, '2025-05-07 07:53:49'),
(8, 5, '681b118ddcf1f_4w-482_3528709023726_tire_michelin_pilot-sport-ev_255-slash-40-r20-101w-xl_t0_acoustic_a_main_2-55_nopad.webp', 1, '2025-05-07 07:53:49'),
(9, 5, '681b118ddf233_11000.png', 2, '2025-05-07 07:53:49'),
(10, 6, '681b137a791f2_images (20).jpg', 0, '2025-05-07 08:02:02'),
(11, 6, '681b137a7ca8c_images (21).jpg', 1, '2025-05-07 08:02:02'),
(12, 7, '681b1491b3753_10462738_742874435777045_8775274200871750804_n.jpg', 0, '2025-05-07 08:06:41'),
(13, 7, '681b1491bbe9e_images (31).jpg', 1, '2025-05-07 08:06:41'),
(14, 8, '681b15a843871_130456.jpg', 0, '2025-05-07 08:11:20'),
(15, 9, '681b162b64a3c_images (6).jpg', 0, '2025-05-07 08:13:31'),
(16, 9, '681b162b65d8e_images (7).jpg', 1, '2025-05-07 08:13:31'),
(17, 10, '681b16ce0c992_images (12).jpg', 0, '2025-05-07 08:16:14'),
(18, 10, '681b16ce0e1d2_images (13).jpg', 1, '2025-05-07 08:16:14'),
(19, 11, '681b175206181_images (18).jpg', 0, '2025-05-07 08:18:26'),
(20, 11, '681b1752072e4_images (19).jpg', 1, '2025-05-07 08:18:26'),
(21, 12, '681b17fb25b56_front.jpg', 0, '2025-05-07 08:21:15'),
(22, 12, '681b17fb2a07b_images (7).jpg', 1, '2025-05-07 08:21:15'),
(23, 12, '681b17fb2d02d_images (28).jpg', 2, '2025-05-07 08:21:15'),
(24, 12, '681b17fb2e37e_images (29).jpg', 3, '2025-05-07 08:21:15'),
(25, 13, '681b1888962ff_images (22).jpg', 0, '2025-05-07 08:23:36'),
(26, 13, '681b1888973b4_images (23).jpg', 1, '2025-05-07 08:23:36'),
(27, 14, '681b1a0402e03_images (25).jpg', 0, '2025-05-07 08:29:56'),
(28, 14, '681b1a0404d29_images (26).jpg', 1, '2025-05-07 08:29:56'),
(29, 15, '681b1aa8b52cc_téléchargement (2).jpg', 0, '2025-05-07 08:32:40'),
(30, 15, '681b1aa8b8645_téléchargement (3).jpg', 1, '2025-05-07 08:32:40'),
(33, 17, '681b288d24e1b_images (16).jpg', 0, '2025-05-07 09:31:57'),
(34, 17, '681b288d272a9_images (17).jpg', 1, '2025-05-07 09:31:57');

-- --------------------------------------------------------

--
-- Table structure for table `liste_souhaits`
--

CREATE TABLE `liste_souhaits` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marques`
--

CREATE TABLE `marques` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `marques`
--

INSERT INTO `marques` (`id`, `nom`, `slug`, `created_at`) VALUES
(1, 'Toyota', 'toyota', '2025-04-26 18:50:39'),
(2, 'Toyota', 'toyota', '2025-04-26 19:43:51'),
(3, 'KYB', 'kyb', '2025-05-07 07:46:09'),
(4, 'Michelin ', 'michelin-', '2025-05-07 07:53:49'),
(5, 'Momo', 'momo', '2025-05-07 08:02:02'),
(6, 'Brembo', 'brembo', '2025-05-07 08:06:41'),
(7, 'Osram', 'osram', '2025-05-07 08:11:20'),
(8, 'Ford', 'ford', '2025-05-07 08:13:31'),
(9, 'Akrapovic', 'akrapovic', '2025-05-07 08:16:14'),
(10, 'Bosch', 'bosch', '2025-05-07 08:18:26'),
(11, 'OEM', 'oem', '2025-05-07 08:21:15'),
(12, 'Varta', 'varta', '2025-05-07 08:23:36'),
(13, 'Saint-Gobain', 'saint-gobain', '2025-05-07 08:29:56'),
(14, 'Philips ', 'philips-', '2025-05-07 08:32:40'),
(15, 'BMW', 'bmw', '2025-05-07 08:38:14'),
(16, 'BMW', 'bmw', '2025-05-07 09:31:57');

-- --------------------------------------------------------

--
-- Table structure for table `modeles`
--

CREATE TABLE `modeles` (
  `id` int(11) NOT NULL,
  `marque_id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `annee_debut` int(11) DEFAULT NULL,
  `annee_fin` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modeles`
--

INSERT INTO `modeles` (`id`, `marque_id`, `nom`, `annee_debut`, `annee_fin`, `created_at`) VALUES
(1, 1, 'Rav4', 2024, 2025, '2025-04-26 18:50:39'),
(2, 2, 'Rav4', NULL, NULL, '2025-04-26 19:43:51');

-- --------------------------------------------------------

--
-- Table structure for table `produits`
--

CREATE TABLE `produits` (
  `id` int(11) NOT NULL,
  `reference` varchar(50) NOT NULL,
  `categorie_id` int(11) NOT NULL,
  `marque_id` int(11) NOT NULL,
  `modele_id` int(11) DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `description_courte` text NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `prix_promotion` decimal(10,2) DEFAULT 0.00,
  `quantite` int(11) NOT NULL,
  `compatibilite` varchar(255) DEFAULT NULL,
  `garantie` int(11) NOT NULL,
  `image_principale` varchar(255) DEFAULT NULL,
  `statut` enum('disponible','rupture','bientot') NOT NULL,
  `date_ajout` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produits`
--

INSERT INTO `produits` (`id`, `reference`, `categorie_id`, `marque_id`, `modele_id`, `nom`, `description`, `description_courte`, `prix`, `prix_promotion`, `quantite`, `compatibilite`, `garantie`, `image_principale`, `statut`, `date_ajout`) VALUES
(4, 'AMO-KYB-001', 4, 3, NULL, 'Amortisseurs', ' Les amortisseurs KYB Excel-G offrent un excellent équilibre entre confort et performance. Conçus avec une technologie à double tube et un gaz sous pression, ils réduisent les vibrations et améliorent la tenue de route. Idéal pour les véhicules de tourisme et les SUV. Installation facile et durabilité accrue.\\r\\n\\r\\n', 'Amortisseurs à gaz pour une conduite souple et stable.\\r\\n\\r\\n', 120.00, 99.00, 20, 'Volkswagen Golf (2010-2020), Audi A3 (2012-2022), Seat Leon (2013-2021)', 2, '681b0fc182015_images (10).jpg', 'disponible', '2025-05-07 07:46:09'),
(5, 'PNE-MIC-2055516', 5, 4, NULL, 'Pneu Michelin Pilot Sport 4 205/55 R16', 'Le Michelin Pilot Sport 4 est conçu pour les conducteurs exigeants. Avec une technologie inspirée de la Formule E, il offre une adhérence exceptionnelle sur routes sèches et mouillées, une durabilité accrue et une précision de conduite. Idéal pour les voitures sportives et compactes.\\r\\n\\r\\n', 'Pneu haute performance pour une adhérence optimale.\\r\\n\\r\\n', 130.00, 119.00, 24, 'BMW Série 3 (2015-2023), Mercedes Classe C (2014-2022), Ford Focus (2018-2023)', 12, '681b118dd6f1c_4w-482_3528709023726_tire_michelin_pilot-sport-ev_255-slash-40-r20-101w-xl_t0_acoustic_a_main_2-55_nopad.webp', 'disponible', '2025-05-07 07:53:49'),
(6, 'VOL-MOMO-001', 6, 5, NULL, 'Volant Momo Racing 350 mm', 'Le volant Momo Racing de 350 mm combine style et ergonomie. Fabriqué en cuir de haute qualité avec des coutures contrastées, il offre une prise en main ferme et un look racing. Compatible avec la plupart des moyeux standards. Parfait pour les passionnés de tuning.\\r\\n\\r\\n', 'Volant sport en cuir pour une meilleure prise en main.\\r\\n\\r\\n', 250.00, 203.50, 12, 'Universel (nécessite un moyeu compatible, ex. Honda Civic 2006-2015, Subaru Impreza 2008-2017)', 6, '681b137a77ac3_615SW6w+UVL.jpg', 'disponible', '2025-05-07 08:02:02'),
(7, 'FRE-BRE-001', 7, 6, NULL, ' Freins Brembo GT Avant 4 Pistons', 'Le kit Brembo GT Avant avec étriers à 4 pistons offre une puissance de freinage exceptionnelle pour les voitures sportives. Les disques ventilés et les plaquettes haute performance garantissent une dissipation thermique optimale et une durabilité accrue, même en conduite intensive.\\r\\n\\r\\n', 'Freins haute performance pour un freinage puissant.\\r\\n\\r\\n', 2500.00, 1999.00, 5, 'Audi S3 (2016-2023), Volkswagen Golf R (2015-2022), BMW M3 (2014-2020)', 6, '681b1491ac50d_10423300_742874315777057_8373732884425033845_n.jpg', 'disponible', '2025-05-07 08:06:41'),
(8, 'CLI-OSR-001', 8, 7, NULL, 'Clignotant LED Osram Dynamic', 'Les clignotants LED Osram Dynamic offrent un effet de balayage élégant pour moderniser l’esthétique de votre véhicule. Résistants aux vibrations et à l’eau, ils consomment moins d’énergie et ont une durée de vie prolongée par rapport aux ampoules classiques.\\r\\n\\r\\n', 'Clignotants LED dynamiques pour un look moderne.\\r\\n\\r\\n', 80.00, 69.50, 50, 'BMW Série 5 (2017-2023), Mercedes Classe E (2016-2022), Audi A4 (2015-2023)', 36, '681b15a84145d_s-l1200.jpg', 'disponible', '2025-05-07 08:11:20'),
(9, ' MOT-FOR-001', 9, 8, NULL, 'Moteur V8 Ford Mustang 5.0L Coyote', 'Le moteur Ford Coyote V8 5.0L délivre une puissance impressionnante pour les projets de performance ou les restaurations. Avec une conception moderne, il inclut une injection directe et une distribution variable pour une efficacité optimale. Idéal pour les muscle cars et les swaps.\\r\\n\\r\\n', 'Moteur V8 5.0L pour performances extrêmes.', 10000.00, 8500.00, 5, 'Ford Mustang (2015-2023), projets custom (nécessite adaptations)', 12, '681b162b63170_images (5).jpg', 'disponible', '2025-05-07 08:13:31'),
(10, 'ECH-AKR-001', 10, 9, NULL, 'Échappement Sport Akrapovic Inox', 'L’échappement Akrapovic en acier inoxydable offre un son profond et une amélioration des performances grâce à une réduction du poids et un meilleur flux des gaz. Fabriqué avec des matériaux de haute qualité, il résiste à la corrosion et ajoute une touche esthétique.\\r\\n\\r\\n', 'Échappement sport pour un son agressif.\\r\\n\\r\\n', 3500.00, 2900.00, 12, 'Porsche 911 (2016-2023), BMW M4 (2017-2023), Audi RS5 (2018-2023)', 12, '681b16ce0a09d_images (13).jpg', 'disponible', '2025-05-07 08:16:14'),
(11, 'RET-BOS-001', 11, 10, NULL, 'Rétroviseur Électrique Bosch Gauche', 'Le rétroviseur électrique Bosch pour côté gauche est équipé d’un système de dégivrage et d’un réglage électrique. Finition robuste et compatible avec les systèmes OEM pour une installation plug-and-play. Améliore la sécurité et le confort.\\r\\n\\r\\n', 'Rétroviseur électrique avec dégivrage.\\r\\n\\r\\n', 150.00, 127.00, 30, 'Renault Clio (2012-2019), Peugeot 308 (2013-2021), Citroën C4 (2015-2022)', 12, '681b175204fb7_images (18).jpg', 'disponible', '2025-05-07 08:18:26'),
(12, 'PAR-OEM-001', 1, 11, NULL, 'Pare-chocs Avant OEM VW Golf 7', 'Ce pare-chocs avant OEM est conçu spécifiquement pour la Volkswagen Golf 7. Fabriqué en plastique ABS de haute qualité, il offre une finition précise et une compatibilité parfaite avec les fixations d’origine. Idéal pour remplacer un pare-chocs endommagé.\\r\\n\\r\\n', 'Pare-chocs avant pour VW Golf 7\\r\\n\\r\\n', 400.00, 365.00, 15, 'Volkswagen Golf 7 (2012-2020)', 12, '681b17fb1d53b_front.jpg', 'disponible', '2025-05-07 08:21:15'),
(13, 'BAT-VAR-001', 12, 12, NULL, 'Batterie Varta Blue Dynamic 70Ah', 'La batterie Varta Blue Dynamic 70Ah offre une puissance de démarrage exceptionnelle et une longue durée de vie. Conçue pour les véhicules modernes avec des besoins électriques élevés, elle est sans entretien et résistante aux vibrations.\\r\\n\\r\\n', 'Batterie 70Ah pour démarrage fiable.\\r\\n\\r\\n', 100.00, 78.50, 20, 'Fiat 500 (2015-2023), Opel Corsa (2014-2022), Hyundai i30 (2017-2023)', 36, '681b188895213_images (24).jpg', 'disponible', '2025-05-07 08:23:36'),
(14, 'PAR-SGC-001', 3, 13, NULL, 'Pare-brise Saint-Gobain Securit', 'Le pare-brise Saint-Gobain Securit est conçu pour une compatibilité parfaite avec les véhicules équipés de capteurs ADAS (caméra, pluie). Fabriqué en verre feuilleté de haute qualité, il offre une clarté optimale et une résistance aux impacts.\\r\\n\\r\\n', 'Pare-brise de qualité OEM avec capteur.\\r\\n\\r\\n', 300.00, 270.00, 25, 'Toyota Corolla (2018-2023), Honda Civic (2016-2022), Ford Fiesta (2017-2023)', 12, '681b1a0401d85_images (25).jpg', 'disponible', '2025-05-07 08:29:56'),
(15, 'VOY-PHI-001', 13, 14, NULL, 'Ampoule LED Philips X-tremeUltinon', 'Les ampoules LED Philips X-tremeUltinon offrent un éclairage blanc éclatant pour les voyants du tableau de bord et les feux de position. Avec une durée de vie prolongée et une faible consommation, elles améliorent la visibilité et l’esthétique.\\r\\n\\r\\n', 'Ampoules LED pour voyants lumineux.\\r\\n\\r\\n', 50.00, 39.90, 50, 'Universel (ex. Renault Megane 2016-2023, Nissan Qashqai 2014-2022)', 12, '681b1aa8b3c7e_images (27).jpg', 'disponible', '2025-05-07 08:32:40'),
(17, 'EMB-SAC-001', 11, 16, NULL, 'Gentes pour BMW', 'Le kit d’embrayage Sachs Performance est conçu pour les véhicules modifiés ou à haute puissance. Il inclut un disque renforcé, un mécanisme robuste et une butée de qualité pour une transmission fiable et une pédale douce.\\r\\n\\r\\n', 'Kit des gentes pour Relooké votre Véhicule et pour une conduite performante.\\r\\n\\r\\n', 50.00, 41.00, 10, 'Ford Fiesta ST (2013-2023), Honda Civic Type R (2017-2022), Hyundai i20 N (2021-2023)', 12, '681b288d23822_images (15).jpg', 'disponible', '2025-05-07 09:31:57');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `created_at`) VALUES
(2, 'best', '', 'mwangugermain33@gmail.com', '$2y$10$gfRST8s2x0gyPhELwox8qudwUSIQRrZoNJSSJ6MLoPTdsuIDbzdVe', '2025-05-03 06:40:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adresses`
--
ALTER TABLE `adresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Indexes for table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `idx_avis_produit_id` (`produit_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_commandes_utilisateur_id` (`utilisateur_id`);

--
-- Indexes for table `commande_produits`
--
ALTER TABLE `commande_produits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commande_id` (`commande_id`),
  ADD KEY `produit_id` (`produit_id`);

--
-- Indexes for table `images_produits`
--
ALTER TABLE `images_produits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produit_id` (`produit_id`);

--
-- Indexes for table `liste_souhaits`
--
ALTER TABLE `liste_souhaits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `utilisateur_id` (`utilisateur_id`,`produit_id`),
  ADD KEY `produit_id` (`produit_id`);

--
-- Indexes for table `marques`
--
ALTER TABLE `marques`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modeles`
--
ALTER TABLE `modeles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `marque_id` (`marque_id`);

--
-- Indexes for table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `modele_id` (`modele_id`),
  ADD KEY `idx_produits_categorie_id` (`categorie_id`),
  ADD KEY `idx_produits_marque_id` (`marque_id`),
  ADD KEY `idx_produits_statut` (`statut`);

--
-- Indexes for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adresses`
--
ALTER TABLE `adresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `avis`
--
ALTER TABLE `avis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commande_produits`
--
ALTER TABLE `commande_produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images_produits`
--
ALTER TABLE `images_produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `liste_souhaits`
--
ALTER TABLE `liste_souhaits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `marques`
--
ALTER TABLE `marques`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `modeles`
--
ALTER TABLE `modeles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adresses`
--
ALTER TABLE `adresses`
  ADD CONSTRAINT `adresses_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `commande_produits`
--
ALTER TABLE `commande_produits`
  ADD CONSTRAINT `commande_produits_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commande_produits_ibfk_2` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`);

--
-- Constraints for table `images_produits`
--
ALTER TABLE `images_produits`
  ADD CONSTRAINT `images_produits_ibfk_1` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `liste_souhaits`
--
ALTER TABLE `liste_souhaits`
  ADD CONSTRAINT `liste_souhaits_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `liste_souhaits_ibfk_2` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`);

--
-- Constraints for table `modeles`
--
ALTER TABLE `modeles`
  ADD CONSTRAINT `modeles_ibfk_1` FOREIGN KEY (`marque_id`) REFERENCES `marques` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `produits_ibfk_1` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `produits_ibfk_2` FOREIGN KEY (`marque_id`) REFERENCES `marques` (`id`),
  ADD CONSTRAINT `produits_ibfk_3` FOREIGN KEY (`modele_id`) REFERENCES `modeles` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
