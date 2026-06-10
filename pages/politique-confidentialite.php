<?php
require_once __DIR__ . '/../includes/bootstrap.php';
trackVisit('politique-confidentialite');
$pageTitle        = 'Politique de Confidentialité';
$breadcrumbCenter = 'BIENVENUE DANS NOTRE PROJET E-SPORT';
$currentPage      = '';
$theme            = 'esport';
require_once __DIR__ . '/../partials/header.php';
?>
<div class="legal-page">
    <h1 style="font-size:22px;font-weight:800;color:var(--rouge-esport);margin-bottom:30px;text-align:center;">Politique de confidentialité (RGPD)</h1>

    <?php
    $sections = [
        '1. Collectes des données personnelles' => "Le FC Grande-Synthe peut collecter certaines données personnelles lors de :
- l'inscription sur le site
- la prise de contact
- l'inscription aux activités du club
- la réservation d'activités esportives

Les données collectées peuvent inclure :
- nom
- prénom
- adresse email
- numéro de téléphone
- date de naissance
- informations liées aux licences sportives",
        '2. Utilisation des données' => "Les données personnelles sont utilisées uniquement dans le cadre :
- de la gestion des membres
- de l'organisation des activités sportives et esportives
- de la communication du club
- du suivi des inscriptions et réservations
- de l'amélioration des services proposés

Les données ne sont ni revendues ni transmises à des tiers sans consentement.",
        '3. Conservation des données' => "Les données personnelles sont conservées uniquement pendant la durée nécessaire au fonctionnement du club et conformément aux obligations légales.",
        '4. Sécurité des données' => "Le FC Grande-Synthe met en œuvre des mesures de sécurité afin de protéger les données personnelles contre :
- l'accès non autorisé
- la perte
- la modification
- la divulgation",
        '5. Droits des utilisateurs' => "Conformément au Règlement Général sur la Protection des Données (RGPD), chaque utilisateur dispose :
- d'un droit d'accès
- d'un droit de rectification
- d'un droit de suppression
- d'un droit d'opposition
- d'un droit à la limitation du traitement

Toute demande peut être adressée à :

contact@fcgrandesynthe.fr",
        '6. Cookies' => "Le site peut utiliser des cookies afin :
- d'améliorer l'expérience utilisateur
- de mesurer l'audience
- d'assurer certaines fonctionnalités du site

Les préférences liées aux cookies peuvent être modifiées à tout moment depuis le gestionnaire de cookies.",
        '7. Modification de la politique de confidentialité' => "Le FC Grande-Synthe se réserve le droit de modifier la présente politique de confidentialité à tout moment afin de respecter les évolutions légales ou techniques.",
        '8. Contact' => "Pour toute question concernant la protection des données personnelles :
contact@fcgrandesynthe.fr",
    ];
    foreach ($sections as $title => $content):
    ?>
        <div class="legal-section">
            <div class="legal-label"><?= h($title) ?></div>
            <div class="legal-content"><?= nl2br(h($content)) ?></div>
        </div>
    <?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
