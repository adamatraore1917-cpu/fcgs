-- FC Grande-Synthe Database Schema
-- Compatible MySQL/MariaDB

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

CREATE DATABASE IF NOT EXISTS fcgrandesynthe CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fcgrandesynthe;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','admin','superadmin') NOT NULL DEFAULT 'user',
    date_naissance DATE DEFAULT NULL,
    ville VARCHAR(100),
    code_postal VARCHAR(10),
    type_license ENUM('seniors','enfants','dirigeant','arbitre','educateur_federal') DEFAULT NULL,
    frais_annexes DECIMAL(8,2) DEFAULT 0.00,
    cotisations DECIMAL(8,2) DEFAULT 0.00,
    photo_recto VARCHAR(255) DEFAULT NULL,
    photo_verso VARCHAR(255) DEFAULT NULL,
    remember_token VARCHAR(64) DEFAULT NULL,
    derniere_connexion DATETIME DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admin permissions table
CREATE TABLE IF NOT EXISTS admin_permissions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    voir_comptes TINYINT(1) DEFAULT 0,
    modifier_comptes TINYINT(1) DEFAULT 0,
    voir_contacts TINYINT(1) DEFAULT 0,
    modifier_galerie TINYINT(1) DEFAULT 0,
    voir_reservations TINYINT(1) DEFAULT 0,
    modifier_matchs TINYINT(1) DEFAULT 0,
    modifier_infrastructure TINYINT(1) DEFAULT 0,
    modifier_tournois TINYINT(1) DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Matchs table
CREATE TABLE IF NOT EXISTS matchs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    date_match DATE NOT NULL,
    heure TIME NOT NULL,
    categorie VARCHAR(100) NOT NULL,
    equipe_domicile VARCHAR(150) NOT NULL,
    equipe_exterieur VARCHAR(150) NOT NULL,
    score_domicile INT DEFAULT NULL,
    score_exterieur INT DEFAULT NULL,
    lieu ENUM('Domicile','Exterieur','Neutre') DEFAULT 'Domicile',
    competition VARCHAR(100) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Gallery table (club photos)
CREATE TABLE IF NOT EXISTS galerie (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slot_number INT NOT NULL UNIQUE,
    filename VARCHAR(255) DEFAULT NULL,
    alt_text VARCHAR(255) DEFAULT NULL,
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Initialize 8 gallery slots
INSERT INTO galerie (slot_number) VALUES (1),(2),(3),(4),(5),(6),(7),(8)
ON DUPLICATE KEY UPDATE slot_number=slot_number;

-- Contacts table
CREATE TABLE IF NOT EXISTS contacts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telephone VARCHAR(20),
    type_contact ENUM('professionnel','club') DEFAULT 'club',
    message TEXT NOT NULL,
    statut ENUM('en_attente','traite','archive') DEFAULT 'en_attente',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Reservations (esport room bookings)
CREATE TABLE IF NOT EXISTS reservations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    heure TIME NOT NULL,
    contenu TEXT NOT NULL,
    statut ENUM('en_attente','confirme','refuse','annule') DEFAULT 'en_attente',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Newsletter subscribers
CREATE TABLE IF NOT EXISTS newsletter (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    telephone VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Site visits tracking
CREATE TABLE IF NOT EXISTS visites (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ip VARCHAR(45),
    page VARCHAR(255),
    visited_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tournaments table
CREATE TABLE IF NOT EXISTS tournois (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    date_tournoi DATE NOT NULL,
    heure TIME NOT NULL,
    jeu VARCHAR(100) DEFAULT 'EA Sports FC26',
    statut ENUM('a_venir','en_cours','termine') DEFAULT 'a_venir',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample tournament dates
INSERT INTO tournois (date_tournoi, heure, jeu) VALUES
('2026-06-14', '14:00:00', 'EA Sports FC26'),
('2026-07-26', '14:00:00', 'EA Sports FC26'),
('2026-08-30', '14:00:00', 'EA Sports FC26');

-- Insert sample match data
INSERT INTO matchs (date_match, heure, categorie, equipe_domicile, equipe_exterieur, score_domicile, score_exterieur, lieu, competition) VALUES
('2026-05-20', '15:00:00', 'Senior - Coupe', 'GRANDE SYNTHE FC', 'BERSEE AS 2', 4, 2, 'Domicile', 'Coupe');

-- Insert default superadmin (password: Admin1234!)
INSERT INTO users (nom, prenom, email, password, role) VALUES
('Admin', 'Super', 'admin@fcgrandesynthe.fr', '$2y$10$usesomesillystringfore4C7e8rezpq04f1CT428PDUjTkBTSSaa', 'superadmin');

INSERT INTO admin_permissions (user_id, voir_comptes, modifier_comptes, voir_contacts, modifier_galerie, voir_reservations, modifier_matchs, modifier_infrastructure, modifier_tournois)
VALUES (1, 1, 1, 1, 1, 1, 1, 1, 1);

-- ============================================================
-- CONTENU EDITABLE : Infrastructure & Tournois
-- ============================================================

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

-- Staff members table
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

-- Partners table
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

-- Avis table
CREATE TABLE IF NOT EXISTS avis (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(255),
    note TINYINT(1) DEFAULT 5,
    commentaire TEXT NOT NULL,
    statut ENUM('en_attente','publie','refuse') DEFAULT 'en_attente',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Recrutement offres
CREATE TABLE IF NOT EXISTS recrutement (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    poste VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    type_contrat VARCHAR(100) DEFAULT 'Bénévolat',
    actif TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO recrutement (poste, description, type_contrat) VALUES
('Éducateur football', 'Nous recherchons des éducateurs passionnés pour encadrer nos équipes jeunes (U9 à U17). Diplôme BAFA ou BEPF apprécié.', 'Bénévolat'),
('Coach esport', 'Animateur pour les sessions esport, maîtrise de EA Sports FC26 requise. Disponible mercredi, vendredi et samedi.', 'Bénévolat'),
('Bénévole organisation', 'Aide à l\'organisation des matchs, tournois et événements du club.', 'Bénévolat')
ON DUPLICATE KEY UPDATE poste=poste;
