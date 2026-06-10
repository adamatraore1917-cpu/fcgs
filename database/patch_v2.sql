-- ============================================================
-- PATCH v2 — Nouvelles tables FC Grande-Synthe
-- À exécuter dans phpMyAdmin (onglet SQL)
-- ============================================================

USE fcgrandesynthe;

-- Colonne date_naissance dans users
ALTER TABLE users ADD COLUMN IF NOT EXISTS date_naissance DATE DEFAULT NULL AFTER password;

-- Contenu éditable
CREATE TABLE IF NOT EXISTS site_content (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    section VARCHAR(100) NOT NULL UNIQUE,
    contenu TEXT NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO site_content (section, contenu) VALUES
('infra_titre',      'UN CADRE HAUT DE GAMME POUR UNE QUALITÉ OPTIMALE.'),
('infra_sous_titre', 'Le FC Grande-Synthe met à disposition de ses licenciés des salles dédiées à la pratique compétitive du jeu vidéo.'),
('infra_feature_1',  '10 Postes PS5 dernière génération'),
('infra_feature_2',  'Ecran 4k 144Hz pour une réactivité maximale'),
('infra_feature_3',  'Casque gaming avec réduction de bruit'),
('infra_feature_4',  'Siège gaming ergonomique réglable'),
('infra_feature_5',  'Connexion fibre 1Gb/s (faible latence)'),
('infra_horaire_mf', '17h - 21h'),
('infra_horaire_sam','14h - 18h'),
('infra_jours_feries','01/01 - Nouvel An\n01/05 - Fête du travail\n14/07 - Fête Nationale\n25/12 - Noël'),
('tournoi_mensuel',  'chaque dernier samedi du mois'),
('tournoi_commente', 'par un animateur spécialisé'),
('tournoi_diffuse',  'sur écran géant dans le club house'),
('tournoi_lots_feat','(maillots, goodies, dotations)'),
('tournoi_lot_1',    'Maillot FC Grande-Synthe + 50€ de chèque cadeau.'),
('tournoi_lot_2',    'Casque gaming'),
('tournoi_lot_3',    'Goodies du club'),
('tournoi_reglement','Matchs en 1 contre 1, durée 6min, équipe au hasard.\n\nRèglement complet disponible sur place.')
ON DUPLICATE KEY UPDATE section=section;

-- Staff
CREATE TABLE IF NOT EXISTS staff (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    poste VARCHAR(150) NOT NULL,
    bio TEXT,
    photo VARCHAR(255),
    ordre INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Partenaires
CREATE TABLE IF NOT EXISTS partenaires (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    logo VARCHAR(255),
    site_web VARCHAR(255),
    description TEXT,
    ordre INT DEFAULT 0,
    actif TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Avis
CREATE TABLE IF NOT EXISTS avis (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(255),
    note TINYINT(1) DEFAULT 5,
    commentaire TEXT NOT NULL,
    statut ENUM('en_attente','publie','refuse') DEFAULT 'en_attente',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Recrutement
CREATE TABLE IF NOT EXISTS recrutement (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    poste VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    type_contrat VARCHAR(100) DEFAULT 'Bénévolat',
    actif TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO recrutement (poste, description, type_contrat) VALUES
('Éducateur football', 'Nous recherchons des éducateurs passionnés pour encadrer nos équipes jeunes (U9 à U17). Diplôme BAFA ou BEPF apprécié.', 'Bénévolat'),
('Coach esport', 'Animateur pour les sessions esport, maîtrise de EA Sports FC26 requise.', 'Bénévolat'),
('Bénévole organisation', 'Aide à l\'organisation des matchs, tournois et événements du club.', 'Bénévolat');

SELECT 'Patch v2 appliqué avec succès !' AS resultat;
