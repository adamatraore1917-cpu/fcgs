<?php
// FC Grande-Synthe — Bootstrap
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// Démarrage de la session sécurisée
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => false, // true en HTTPS production
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}

// Régénération sécurisée de session si nécessaire
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

// Connexion auto via cookie "remember me"
if (!isLoggedIn() && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $stmt  = db()->prepare('SELECT * FROM users WHERE remember_token = ?');
    $stmt->execute([$token]);
    $user = $stmt->fetch();
    if ($user) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_email']= $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_nom']  = $user['nom'];
        $_SESSION['user_prenom'] = $user['prenom'];
    }
}
