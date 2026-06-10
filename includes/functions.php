<?php
// FC Grande-Synthe — Fonctions communes

/**
 * Échappe les données pour l'affichage HTML (anti-XSS)
 */
function h(mixed $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Redirige vers une URL
 */
function redirect(string $url): never {
    header('Location: ' . $url);
    exit;
}

/**
 * Vérifie si l'utilisateur est connecté
 */
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Vérifie si l'utilisateur est admin ou superadmin
 */
function isAdmin(): bool {
    return isLoggedIn() && in_array($_SESSION['user_role'] ?? '', ['admin', 'superadmin']);
}

/**
 * Vérifie si l'utilisateur est superadmin
 */
function isSuperAdmin(): bool {
    return isLoggedIn() && ($_SESSION['user_role'] ?? '') === 'superadmin';
}

/**
 * Requiert connexion, redirige sinon
 */
function requireLogin(): void {
    if (!isLoggedIn()) {
        redirect(SITE_URL . '/inscription');
    }
}

/**
 * Requiert rôle admin
 */
function requireAdmin(): void {
    if (!isAdmin()) {
        redirect(SITE_URL . '/');
    }
}

/**
 * Génère un token CSRF
 */
function generateCsrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifie le token CSRF
 */
function verifyCsrfToken(string $token): bool {
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

/**
 * Formate une date en français
 */
function formatDateFr(string $date): string {
    $jours = ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'];
    $mois  = ['','Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
    $ts = strtotime($date);
    return $jours[date('w', $ts)] . ' ' . date('d', $ts) . ' ' . $mois[(int)date('m', $ts)] . ' ' . date('Y', $ts);
}

/**
 * Formate une date courte en français (ex: 14 Jun 2026)
 */
function formatDateShortFr(string $date): string {
    $mois = ['','Jan','Fév','Mar','Avr','Mai','Jun','Jul','Août','Sep','Oct','Nov','Déc'];
    $ts = strtotime($date);
    return date('d', $ts) . ' ' . $mois[(int)date('m', $ts)] . ' ' . date('Y', $ts);
}

/**
 * Enregistre une visite en base
 */
function trackVisit(string $page): void {
    try {
        $stmt = db()->prepare('INSERT INTO visites (ip, page) VALUES (?, ?)');
        $stmt->execute([$_SERVER['REMOTE_ADDR'] ?? 'unknown', $page]);
    } catch (PDOException $e) {
        // Non bloquant
    }
}

/**
 * Récupère l'utilisateur connecté
 */
function getCurrentUser(): ?array {
    if (!isLoggedIn()) return null;
    $stmt = db()->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch() ?: null;
}

/**
 * Retourne un message flash (stocké en session)
 */
function flashMessage(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlashMessage(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Validation email
 */
function isValidEmail(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Sécurise un nom de fichier uploadé
 */
function sanitizeFilename(string $name): string {
    $name = preg_replace('/[^a-zA-Z0-9._-]/', '_', $name);
    return strtolower(trim($name, '._-'));
}

/**
 * Récupère un contenu éditable depuis site_content
 */
function getContent(string $section, string $default = ''): string {
    static $cache = [];
    if (!isset($cache[$section])) {
        try {
            $stmt = db()->prepare('SELECT contenu FROM site_content WHERE section = ?');
            $stmt->execute([$section]);
            $row = $stmt->fetch();
            $cache[$section] = $row ? $row['contenu'] : $default;
        } catch (PDOException $e) {
            $cache[$section] = $default;
        }
    }
    return $cache[$section];
}

/**
 * Met à jour un contenu éditable
 */
function setContent(string $section, string $contenu): void {
    db()->prepare('INSERT INTO site_content (section, contenu) VALUES (?,?) ON DUPLICATE KEY UPDATE contenu=?')
       ->execute([$section, $contenu, $contenu]);
}

function getMatchsByMonth(int $year, int $month): array {
    $start = sprintf('%04d-%02d-01', $year, $month);
    $end   = date('Y-m-t', strtotime($start));
    $stmt  = db()->prepare('SELECT * FROM matchs WHERE date_match BETWEEN ? AND ? ORDER BY date_match, heure');
    $stmt->execute([$start, $end]);
    return $stmt->fetchAll();
}
