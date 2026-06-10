<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
trackVisit('esport/projet-educatif');

$pageTitle        = 'Projet Éducatif Esport';
$breadcrumbCenter = 'BIENVENUE DANS NOTRE PROJET E-SPORT';
$currentPage      = 'esport';
$theme            = 'esport';
require_once __DIR__ . '/../../partials/header.php';
?>

<!-- IMAGES HEADER -->
<div class="educ-images-row">
    <img src="<?= ASSETS_URL ?>/images/illustrations/educational_project_01.jpg" alt="Joueurs de football FC Grande-Synthe" loading="eager">
    <img src="<?= ASSETS_URL ?>/images/illustrations/educational_project_02.jpg" alt="Entraîneur avec les jeunes joueurs" loading="eager">  
</div>



<!-- BANNER TITRE -->
<section class="educ-banner" aria-labelledby="educ-title">
    <h1 class="educ-banner-title" id="educ-title">JOUER INTELLIGEMMENT, BOUGER AUTREMENT</h1>
    <p class="educ-banner-sub">Notre projet éducatif a un objectif clair : éviter l'isolement et la sédentarité</p>

    <div class="educ-cards-grid">
        <div class="educ-feature-card">
            <h4>
                <img src="<?= ASSETS_URL ?>/images/icons/football.png" alt="">
                <strong>1 heure de foot</strong> suplémentaire
            </h4>
            <p>Avant chaque session eSport, vous participez à 1 heure de pratique physique sur le terrain.</p>
        </div>

        <div class="educ-feature-card">
            <h4>
                <img src="<?= ASSETS_URL ?>/images/icons/internationnal.png" alt="">
                Les bénéfices
            </h4>
            <p>Cette pratique permet d'améliorer la concentration, les réflexes, l'esprit d'équipe et la condition physique des joueurs.</p>
        </div>

        <div class="educ-feature-card">
            <h4>
                <img src="<?= ASSETS_URL ?>/images/icons/translate.png" alt="">
                <strong>Une dimension internationale</strong>
            </h4>
            <p>Tout se déroule en anglais : les consignes, les commentaires des tournois, les échanges. Une façon ludique de progresser en langues.</p>
        </div>

        <div class="educ-feature-card">
            <h4>
                <img src="<?= ASSETS_URL ?>/images/icons/inscription.png" alt="">
                Inscriptions
            </h4>
            <p>Les inscriptions sont ouvertes aux 12 - 18 ans, licenciés au club.</p>
        </div>
    </div>
</section>

<div style="text-align:center;padding:30px 20px;">
    <a href="<?= SITE_URL ?>/inscription" class="btn-danger" style="display:inline-block;text-decoration:none;margin-right:10px;">
        S'inscrire
    </a>
    <a href="<?= SITE_URL ?>/esport" class="btn-primary" style="display:inline-block;text-decoration:none;">
        ← Retour Esport
    </a>
</div>

<?php require_once __DIR__ . '/../../partials/footer.php'; ?>
