<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
trackVisit('esport/infrastructure');

$pageTitle        = 'Infrastructure Esport';
$breadcrumbCenter = 'BIENVENUE DANS NOTRE PROJET E-SPORT';
$currentPage      = 'esport';
$theme            = 'esport';
require_once __DIR__ . '/../../partials/header.php';

// Load editable content
$titre      = getContent('infra_titre',      'UN CADRE HAUT DE GAMME POUR UNE QUALITÉ OPTIMALE.');
$sousTitre  = getContent('infra_sous_titre', 'Le FC Grande-Synthe met à disposition de ses licenciés des salles dédiées à la pratique compétitive du jeu vidéo.');
$features   = [
    ['icon'=>'playstation5.png', 'label'=>'10 Postes PS5',      'text'=> getContent('infra_feature_1','10 Postes PS5 dernière génération')],
    ['icon'=>'4k-screen.png',    'label'=>'Écran 4K',           'text'=> getContent('infra_feature_2','Ecran 4k 144Hz pour une réactivité maximale')],
    ['icon'=>'gaming-headset.png','label'=>'Casque gaming',     'text'=> getContent('infra_feature_3','Casque gaming avec réduction de bruit')],
    ['icon'=>'gaming-chair.png', 'label'=>'Siège gaming',       'text'=> getContent('infra_feature_4','Siège gaming ergonomique réglable')],
    ['icon'=>'fiber.png',        'label'=>'Fibre optique',      'text'=> getContent('infra_feature_5','Connexion fibre 1Gb/s (faible latence)')],
];
$horaireMF  = getContent('infra_horaire_mf',  '17h - 21h');
$horaireSam = getContent('infra_horaire_sam', '14h - 18h');
$joursFeries= getContent('infra_jours_feries',"01/01 - Nouvel An\n01/05 - Fête du travail\n14/07 - Fête Nationale\n25/12 - Noël");

// Parse horaires
$mfTimes  = explode('-', $horaireMF);
$samTimes = explode('-', $horaireSam);
?>

<!-- HERO -->
<img src="<?= ASSETS_URL ?>/images/illustrations/esport_picture_01.jpg" alt="FC Grande-Synthe Esport" class="infra-hero" loading="eager">

<section class="infra-section">
    <div style="background:var(--rose-pale);padding:20px;border-radius:4px;margin-bottom:30px;">
        <h1 class="infra-title"><?= h($titre) ?></h1>
        <p class="infra-subtitle"><?= h($sousTitre) ?></p>
    </div>

    <div class="infra-grid">
        <!-- ÉQUIPEMENTS -->
        <div class="infra-features">
            <?php foreach ($features as $f): ?>
            <div class="infra-feature">
                <img src="<?= ASSETS_URL ?>/images/icons/<?= h($f['icon']) ?>" alt="<?= h($f['label']) ?>">
                <span><?= h($f['text']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- HORAIRES -->
        <div class="horaires-card">
            <div class="horaires-header">
                <img src="<?= ASSETS_URL ?>/images/icons/calendar.png" alt="" style="height:20px;">
                Horaire d'accès
            </div>
            <div class="horaires-body">
                <div class="horaire-row">
                    <div class="horaire-day">Mercredi &amp; Vendredi :</div>
                    <div class="horaire-times">
                        <?php foreach ($mfTimes as $t): ?>
                            <span class="time-badge"><?= h(trim($t)) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="horaire-row">
                    <div class="horaire-day">Samedi :</div>
                    <div class="horaire-times">
                        <?php foreach ($samTimes as $t): ?>
                            <span class="time-badge"><?= h(trim($t)) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <p class="closed-note">Notice : Les jours fériés suivants génèrent par heure couvrant :</p>
                <div class="closed-dates"><?= nl2br(h($joursFeries)) ?></div>
            </div>
        </div>
    </div>
</section>

<!-- PHOTOS -->
<div class="infra-photos-grid" style="padding:0 20px;">
    <img src="<?= ASSETS_URL ?>/images/illustrations/sony-ps5.jpg"        alt="PlayStation 5" loading="lazy">
    <img src="<?= ASSETS_URL ?>/images/illustrations/CRUA-monitor-4k.jpg" alt="Écran 4K 144Hz" loading="lazy">
    <img src="<?= ASSETS_URL ?>/images/illustrations/gaming-chair.jpg"    alt="Siège gaming" loading="lazy">
    <img src="<?= ASSETS_URL ?>/images/illustrations/hyperx-headset.jpg"  alt="Casque gaming" loading="lazy">
</div>
<img src="<?= ASSETS_URL ?>/images/illustrations/fiber-connexion.jpg" alt="Connexion fibre" class="infra-photo-full" loading="lazy">

<div style="text-align:center;padding:20px 20px 40px;">
    <a href="<?= SITE_URL ?>/esport" class="btn-primary" style="display:inline-block;text-decoration:none;">← Retour Esport</a>
</div>

<?php require_once __DIR__ . '/../../partials/footer.php'; ?>
