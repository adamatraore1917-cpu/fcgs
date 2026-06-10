<?php
// FC Grande-Synthe — Header partiel
// Variables attendues : $pageTitle (string), $breadcrumbCenter (string), $theme ('football'|'esport')
$theme = $theme ?? 'football';
// Use esport logo when on esport section, football logo otherwise
$logoSrc = ($theme === 'esport')
    ? ASSETS_URL . '/images/logos/logo-esport.jpg'
    : ASSETS_URL . '/images/logos/logo-foot.jpg';
// Fallback: both logos exist, so this is safe
$breadcrumbCenter = $breadcrumbCenter ?? 'BIENVENUE SUR NOTRE SITE INTERNET';
$pageTitle = $pageTitle ?? 'FC Grande-Synthe';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($pageTitle) ?> — FC Grande-Synthe</title>
    <meta name="description" content="Site officiel du FC Grande-Synthe, club de football et esport des Hauts-de-France.">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/style.css">
    <link rel="icon" type="image/jpg" href="<?= ASSETS_URL ?>/images/logos/logo-foot.jpg">
</head>
<body>

<!-- NAVBAR -->
<header>
    <nav class="navbar" role="navigation" aria-label="Navigation principale">
        <div class="navbar-logo">
            <a href="<?= SITE_URL ?>/" aria-label="Accueil FC Grande-Synthe">
                <img src="<?= $logoSrc ?>" alt="Logo FC Grande-Synthe" width="44" height="44">
            </a>
        </div>

        <button class="nav-hamburger" id="nav-hamburger" aria-label="Menu" aria-expanded="false" aria-controls="nav-menu">
            <span></span><span></span><span></span>
        </button>

        <ul class="navbar-nav" id="nav-menu" role="menubar">
            <li role="none">
                <a href="<?= SITE_URL ?>/"           class="nav-link <?= ($currentPage ?? '') === 'accueil' ? 'active' : '' ?>" role="menuitem">Accueil</a>
            </li>
            <li role="none">
                <a href="<?= SITE_URL ?>/club"       class="nav-link <?= ($currentPage ?? '') === 'club' ? 'active' : '' ?>" role="menuitem">Club</a>
            </li>
            <li role="none">
                <a href="<?= SITE_URL ?>/equipes"    class="nav-link <?= ($currentPage ?? '') === 'equipes' ? 'active' : '' ?>" role="menuitem">Equipes</a>
            </li>
            <li role="none">
                <a href="<?= SITE_URL ?>/esport"     class="nav-link <?= ($currentPage ?? '') === 'esport' ? 'active' : '' ?>" role="menuitem">Esport</a>
            </li>
            <li role="none">
                <a href="<?= SITE_URL ?>/contact"    class="nav-link <?= ($currentPage ?? '') === 'contact' ? 'active' : '' ?>" role="menuitem">Contact</a>
            </li>
        </ul>

        <div class="navbar-right">
            <?php if (isAdmin()): ?>
                <a href="<?= SITE_URL ?>/admin-acces" class="nav-admin-link" aria-label="Accès administration">accès admin</a>
            <?php endif; ?>

            <div class="nav-user-icon">
                <?php if (isLoggedIn()): ?>
                    <a href="<?= SITE_URL ?>/profil" aria-label="Mon profil">
                        <img src="<?= ASSETS_URL ?>/images/icons/staff.png" alt="Mon profil" width="32" height="32">
                    </a>
                <?php else: ?>
                    <a href="<?= SITE_URL ?>/inscription" aria-label="Se connecter">
                        <img src="<?= ASSETS_URL ?>/images/icons/staff.png" alt="Connexion" width="32" height="32">
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- BREADCRUMB BAR -->
    <div class="breadcrumb-bar" role="navigation" aria-label="Fil d'Ariane">
        <span class="breadcrumb-left">FC GRANDE-SYNTHE</span>
        <span class="breadcrumb-center"><?= h($breadcrumbCenter) ?></span>
        <span class="breadcrumb-right">
            <?php if (isLoggedIn()): ?>
                <a href="<?= SITE_URL ?>/profil">MON PROFIL</a>
            <?php else: ?>
                <a href="<?= SITE_URL ?>/signup">S'INSCRIRE</a>
            <?php endif; ?>
        </span>
    </div>
</header>

<main id="main-content" role="main">
