# FC Grande-Synthe — Site Web Officiel

Site web complet du FC Grande-Synthe : football & esport.
Développé en PHP 8+ / MySQL / HTML5 / CSS3 / JavaScript natif.

---

## 📁 Structure du projet

```
fcgs/
├── .htaccess               ← Réécriture d'URL (mod_rewrite)
├── router.php              ← Routeur principal
├── index.php               ← Page d'accueil
│
├── config/
│   ├── config.php          ← Configuration (BDD, URLs, constantes)
│   └── database.php        ← Connexion PDO centralisée
│
├── includes/
│   ├── bootstrap.php       ← Chargement général + session
│   └── functions.php       ← Fonctions utilitaires communes
│
├── partials/
│   ├── header.php          ← Navbar + breadcrumb + <head>
│   └── footer.php          ← Footer + newsletter + cookies
│
├── pages/
│   ├── club.php            ← Galerie du club
│   ├── equipes.php         ← Équipes avec navigation
│   ├── esport.php          ← Page Esport + réservation
│   ├── contact.php         ← Page contact (2 modals)
│   ├── login.php           ← Connexion
│   ├── signup.php          ← Inscription + licence
│   ├── profil.php          ← Espace membre
│   ├── mentions-legales.php
│   ├── politique-confidentialite.php
│   ├── legal.php           ← CGU / Cookies / Accessibilité / etc.
│   ├── newsletter.php      ← Traitement newsletter
│   ├── mot-de-passe-oublie.php
│   ├── 404.php
│   └── esport/
│       ├── infrastructure.php
│       ├── tournaments.php
│       └── projet-educatif.php
│
├── admin/
│   ├── index.php           ← Dashboard admin (stats + permissions)
│   ├── accounts.php        ← Liste des comptes
│   ├── edit-accounts.php   ← Modification d'un compte
│   ├── gallery.php         ← Gestion photos galerie
│   ├── matchs.php          ← Gestion des matchs
│   ├── contacts.php        ← Demandes de contact
│   ├── reservations.php    ← Réservations esport
│   └── tournois.php        ← Gestion des tournois
│
├── assets/
│   ├── css/style.css       ← Feuille de styles principale
│   ├── js/main.js          ← JavaScript
│   └── images/
│       ├── icons/          ← Icônes PNG
│       ├── illustrations/  ← Photos et illustrations
│       └── logos/          ← Logos du club
│
├── uploads/                ← Photos uploadées par les utilisateurs
│   └── .htaccess           ← Sécurité : blocage PHP
│
└── database/
    └── schema.sql          ← Schéma SQL complet
```

---

## 🚀 Installation

### Prérequis
- PHP 8.0+
- MySQL 5.7+ ou MariaDB 10.3+
- Apache avec `mod_rewrite` activé
- Extension PHP : `pdo_mysql`, `fileinfo`, `mbstring`

### Étapes

**1. Copier les fichiers**
```bash
# Déposer le dossier fcgs/ dans votre répertoire web
# Exemple Apache : /var/www/html/fcgs/
# Exemple WAMP/XAMPP : C:/wamp64/www/fcgs/
```

**2. Créer la base de données**
```bash
mysql -u root -p < database/schema.sql
```
Ou via phpMyAdmin : importer le fichier `database/schema.sql`.

**3. Configurer la connexion**
Éditer `config/config.php` :
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'fcgrandesynthe');
define('DB_USER', 'votre_user');
define('DB_PASS', 'votre_mdp');
define('SITE_URL', 'http://localhost/fcgs');  // Adapter à votre URL
```

**4. Activer mod_rewrite (Apache)**
Vérifier dans `httpd.conf` ou `.conf` du VirtualHost :
```apache
AllowOverride All
```

**5. Droits sur le dossier uploads/**
```bash
chmod 755 uploads/
```

**6. Accéder au site**
```
http://localhost/fcgs/
```

---

## 🔐 Compte administrateur par défaut

| Email | Mot de passe |
|-------|-------------|
| admin@fcgrandesynthe.fr | Admin1234! |

> ⚠️ **Changer immédiatement ce mot de passe en production !**

---

## 📄 Pages disponibles

| URL | Description |
|-----|-------------|
| `/` | Accueil — calendrier matchs |
| `/club` | Galerie photos du club |
| `/equipes` | Équipes (navigation) |
| `/esport` | Esport + réservation salle |
| `/esport/tournaments` | Tournois FC 26 |
| `/esport/infrastructure` | Infrastructure gaming |
| `/esport/projet-educatif` | Projet éducatif |
| `/contact` | Contact professionnel / club |
| `/inscription` | Connexion |
| `/signup` | Inscription + licence |
| `/profil` | Espace membre |
| `/admin-acces` | Dashboard admin (admin requis) |
| `/admin/accounts` | Liste comptes |
| `/admin/gallery` | Gestion galerie |
| `/admin/matchs` | Gestion matchs |
| `/admin/contacts` | Demandes de contact |
| `/admin/reservations` | Réservations esport |
| `/admin/tournois` | Tournois esport |
| `/mentions-legales` | Mentions légales |
| `/politique-confidentialite` | RGPD |
| `/cgu` | CGU |
| `/cookies` | Cookies |
| `/accessibilite` | Accessibilité RGAA |
| `/droit-image` | Droit à l'image |
| `/protection-mineurs` | Protection des mineurs |

---

## 🛡️ Sécurité implémentée

- ✅ Requêtes PDO préparées (anti-injection SQL)
- ✅ Échappement XSS avec `htmlspecialchars()` sur toutes les sorties
- ✅ Tokens CSRF sur tous les formulaires
- ✅ Sessions sécurisées (httponly, samesite=strict)
- ✅ Hachage bcrypt (cost 12) pour les mots de passe
- ✅ Contrôle des rôles (user / admin / superadmin)
- ✅ Blocage PHP dans `/uploads/`
- ✅ Validation front-end + back-end
- ✅ Régénération d'ID de session à la connexion

---

## 🌐 Responsive

Breakpoints CSS :
- Desktop : > 1024px
- Tablette : 768px — 1024px
- Mobile : < 768px
- Mobile S : < 480px

---

## 📝 Notes de production

1. Désactiver l'affichage des erreurs dans `config/config.php` :
   ```php
   ini_set('display_errors', 0);
   ```
2. Configurer `SITE_URL` avec le vrai domaine.
3. Activer HTTPS et changer `'secure' => true` dans `bootstrap.php`.
4. Implémenter l'envoi d'emails (PHPMailer) pour le formulaire de reset de mot de passe.
5. Sauvegardes régulières de la base de données.
