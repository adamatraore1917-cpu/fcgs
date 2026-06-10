<?php
// FC Grande-Synthe — Routeur principal
require_once __DIR__ . '/includes/bootstrap.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Strip base path /fcgs/
$uri = preg_replace('#^/fcgs#', '', $uri);
$uri = rtrim($uri, '/');
if ($uri === '') $uri = '/';

// ============================================================
// ROUTES
// ============================================================
switch ($uri) {

    // ----- HOME -----
    case '/':
    case '/accueil':
        require __DIR__ . '/index.php';
        break;

    // ----- CLUB -----
    case '/club':
        require __DIR__ . '/pages/club.php';
        break;

    // ----- EQUIPES -----
    case '/equipes':
        require __DIR__ . '/pages/equipes.php';
        break;

    // ----- ESPORT -----
    case '/esport':
        require __DIR__ . '/pages/esport.php';
        break;

    case '/esport/infrastructure':
        require __DIR__ . '/pages/esport/infrastructure.php';
        break;

    case '/esport/tournaments':
        require __DIR__ . '/pages/esport/tournaments.php';
        break;

    case '/esport/projet-educatif':
        require __DIR__ . '/pages/esport/projet-educatif.php';
        break;

    // ----- CONTACT -----
    case '/contact':
        require __DIR__ . '/pages/contact.php';
        break;

    // ----- AUTH -----
    case '/inscription':
        require __DIR__ . '/pages/login.php';
        break;

    case '/signup':
        require __DIR__ . '/pages/signup.php';
        break;

    case '/profil':
        require __DIR__ . '/pages/profil.php';
        break;

    case '/mot-de-passe-oublie':
        require __DIR__ . '/pages/mot-de-passe-oublie.php';
        break;

    case '/deconnexion':
        session_destroy();
        setcookie('remember_token', '', time() - 3600, '/');
        redirect(SITE_URL . '/');
        break;

    // ----- NEWSLETTER -----
    case '/newsletter':
        require __DIR__ . '/pages/newsletter.php';
        break;

    // ----- ADMIN -----
    case '/admin-acces':
        require __DIR__ . '/admin/index.php';
        break;

    case '/admin/accounts':
        require __DIR__ . '/admin/accounts.php';
        break;

    case '/admin/edit-accounts':
        require __DIR__ . '/admin/edit-accounts.php';
        break;

    case '/admin/gallery':
        require __DIR__ . '/admin/gallery.php';
        break;

    case '/admin/matchs':
        require __DIR__ . '/admin/matchs.php';
        break;

    case '/admin/contacts':
        require __DIR__ . '/admin/contacts.php';
        break;

    case '/admin/reservations':
        require __DIR__ . '/admin/reservations.php';
        break;

    case '/admin/edit-infrastructure':
        require __DIR__ . '/admin/edit-infrastructure.php';
        break;

    case '/admin/edit-tournois':
        require __DIR__ . '/admin/edit-tournois.php';
        break;

    case '/admin/tournois':
        require __DIR__ . '/admin/tournois.php';
        break;

    // ----- CLUB PAGES -----
    case '/presentation':
        require __DIR__ . '/pages/club/presentation.php';
        break;

    case '/staff-direction':
        require __DIR__ . '/pages/club/staff-direction.php';
        break;

    case '/partenaires':
        require __DIR__ . '/pages/club/partenaires.php';
        break;

    case '/recrutement':
        require __DIR__ . '/pages/club/recrutement.php';
        break;

    case '/devenir-partenaire':
        require __DIR__ . '/pages/club/devenir-partenaire.php';
        break;

    case '/emettre-un-avis':
        require __DIR__ . '/pages/club/emettre-un-avis.php';
        break;

    // ----- PAGES LÉGALES -----
    case '/mentions-legales':
        require __DIR__ . '/pages/mentions-legales.php';
        break;

    case '/politique-confidentialite':
    case '/rgpd':
        require __DIR__ . '/pages/politique-confidentialite.php';
        break;

    case '/cgu':
        $legalPage = 'cgu';
        require __DIR__ . '/pages/legal.php';
        break;

    case '/cookies':
        $legalPage = 'cookies';
        require __DIR__ . '/pages/legal.php';
        break;

    case '/accessibilite':
        $legalPage = 'accessibilite';
        require __DIR__ . '/pages/legal.php';
        break;

    case '/droit-image':
        $legalPage = 'droit-image';
        require __DIR__ . '/pages/legal.php';
        break;

    case '/protection-mineurs':
        $legalPage = 'protection-mineurs';
        require __DIR__ . '/pages/legal.php';
        break;

    case '/admin/staff':
        require __DIR__ . '/admin/staff.php';
        break;

    case '/admin/partenaires':
        require __DIR__ . '/admin/partenaires.php';
        break;

    case '/admin/avis':
        require __DIR__ . '/admin/avis.php';
        break;

    // ----- 404 -----
    default:
        http_response_code(404);
        require __DIR__ . '/pages/404.php';
        break;
}
