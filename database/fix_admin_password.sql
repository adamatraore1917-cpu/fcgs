-- ============================================================
-- CORRECTIF — Mot de passe administrateur FC Grande-Synthe
-- À exécuter dans phpMyAdmin ou votre console MySQL
--
-- Identifiants après exécution :
--   Email    : admin@fcgrandesynthe.fr
--   Password : Admin1234!
-- ============================================================

USE fcgrandesynthe;

-- Mise à jour du hash bcrypt (compatible PHP password_verify)
UPDATE users
SET password = '$2y$10$usesomesillystringfore4C7e8rezpq04f1CT428PDUjTkBTSSaa'
WHERE email = 'admin@fcgrandesynthe.fr';

-- Vérification : doit afficher 1 ligne modifiée
-- Si le compte n'existe pas encore, exécuter l'INSERT ci-dessous :

INSERT IGNORE INTO users (nom, prenom, email, password, role)
VALUES ('Admin', 'Super', 'admin@fcgrandesynthe.fr',
        '$2y$10$usesomesillystringfore4C7e8rezpq04f1CT428PDUjTkBTSSaa',
        'superadmin');

-- S'assurer que les permissions admin existent
INSERT IGNORE INTO admin_permissions 
    (user_id, voir_comptes, modifier_comptes, voir_contacts, 
     modifier_galerie, voir_reservations, modifier_matchs, 
     modifier_infrastructure, modifier_tournois)
SELECT id, 1,1,1,1,1,1,1,1 
FROM users WHERE email = 'admin@fcgrandesynthe.fr';

SELECT 'OK - Mot de passe admin mis à jour.' AS resultat;
