<?php
// FC Grande-Synthe — Configuration principale
define('DB_HOST', 'localhost');
define('DB_NAME', 'fcgrandesynthe');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

define('SITE_NAME', 'FC Grande-Synthe');
define('SITE_URL', 'http://localhost/fcgs');
define('SITE_EMAIL', 'contact@fcgrandesynthe.fr');

define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');
define('ASSETS_URL', SITE_URL . '/assets');

// Session
define('SESSION_NAME', 'fcgs_session');
define('REMEMBER_TOKEN_EXPIRY', 30 * 24 * 60 * 60); // 30 jours

// Timezone
date_default_timezone_set('Europe/Paris');

// Error reporting (désactiver en production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
