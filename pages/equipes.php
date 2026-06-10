<?php
require_once __DIR__ . '/../includes/bootstrap.php';
trackVisit('equipes');

$teams = [
    ['id' => 'babyfoot',  'label' => 'BABYFOOT',  'icon' => 'team.png',
     'description' => 'L\'équipe Babyfoot accueille les enfants de 5 à 7 ans pour une initiation au football dans la bonne humeur. Les matchs se jouent sur de petits terrains adaptés avec des règles simplifiées.'],
    ['id' => 'u9',        'label' => 'U9',         'icon' => 'team.png',
     'description' => 'La catégorie U9 regroupe les joueurs de 7 à 9 ans. Les entraînements se concentrent sur les fondamentaux techniques : conduite de balle, passes courtes et coordination.'],
    ['id' => 'u11',       'label' => 'U11',        'icon' => 'team.png',
     'description' => 'Les U11 sont les joueurs de 9 à 11 ans. Ils participent aux championnats de district avec des règles adaptées à 8 contre 8.'],
    ['id' => 'u13',       'label' => 'U13',        'icon' => 'team.png',
     'description' => 'La catégorie U13 (11-13 ans) évolue sur terrain réduit à 11 contre 11. L\'accent est mis sur le jeu collectif et la compréhension tactique.'],
    ['id' => 'u15',       'label' => 'U15',        'icon' => 'team.png',
     'description' => 'Les U15 (13-15 ans) disputent le championnat départemental. Les joueurs développent leur puissance physique et leur sens du jeu.'],
    ['id' => 'u17',       'label' => 'U17',        'icon' => 'team.png',
     'description' => 'La catégorie U17 (15-17 ans) est une passerelle vers le football adulte. Les joueurs sont préparés physiquement et tactiquement pour intégrer les équipes seniors.'],
    ['id' => 'u19',       'label' => 'U19',        'icon' => 'team.png',
     'description' => 'Les U19 (17-19 ans) évoluent en championnat régional. Cette équipe est la dernière étape avant les catégories seniors.'],
    ['id' => 'seniors',   'label' => 'SENIORS',    'icon' => 'team.png',
     'description' => 'L\'équipe Seniors 1 évolue en Division Honneur de la Ligue des Hauts-de-France. Elle est composée de joueurs confirmés encadrés par un staff professionnel.'],
    ['id' => 'veterans',  'label' => 'VÉTÉRANS',   'icon' => 'team.png',
     'description' => 'L\'équipe des Vétérans accueille les joueurs de plus de 35 ans qui souhaitent continuer à pratiquer le football dans une ambiance conviviale et sportive.'],
];

$activeTeam = isset($_GET['team']) ? $_GET['team'] : 'babyfoot';
$currentTeam = null;
foreach ($teams as $t) { if ($t['id'] === $activeTeam) { $currentTeam = $t; break; } }
if (!$currentTeam) $currentTeam = $teams[0];

$pageTitle        = 'Nos Équipes';
$breadcrumbCenter = 'NOS ÉQUIPES';
$currentPage      = 'equipes';
require_once __DIR__ . '/../partials/header.php';
?>

<div style="padding: 30px 20px; max-width: 1000px; margin: 0 auto;">

    <!-- TEAM CARD -->
    <div class="sidebar-card" style="max-width:900px;margin:0 auto 30px;">
        <div class="sidebar-card-header" style="display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:10px;">
                <img src="<?= ASSETS_URL ?>/images/icons/<?= h($currentTeam['icon']) ?>" alt="" style="height:28px;filter:invert(1);">
                <span style="font-size:18px;font-weight:800;letter-spacing:1px;"><?= h($currentTeam['label']) ?></span>
            </div>
            <div style="display:flex;gap:6px;">
                <?php
                $currentIndex = array_search($currentTeam, $teams);
                $prevTeam = $currentIndex > 0 ? $teams[$currentIndex - 1] : $teams[count($teams) - 1];
                $nextTeam = $currentIndex < count($teams) - 1 ? $teams[$currentIndex + 1] : $teams[0];
                ?>
                <a href="?team=<?= h($prevTeam['id']) ?>" style="background:var(--bleu-fc);color:white;width:28px;height:28px;display:flex;align-items:center;justify-content:center;border-radius:2px;text-decoration:none;" aria-label="Équipe précédente">&#9664;</a>
                <a href="?team=<?= h($nextTeam['id']) ?>" style="background:var(--bleu-fc);color:white;width:28px;height:28px;display:flex;align-items:center;justify-content:center;border-radius:2px;text-decoration:none;" aria-label="Équipe suivante">&#9654;</a>
            </div>
        </div>
        <div style="padding:24px;">
            <p style="font-weight:700;font-size:15px;margin-bottom:14px;">Ici la zone paragraphié afin d'expliquer en détails les équipes</p>
            <p style="color:#555;font-size:14px;line-height:1.7;"><?= h($currentTeam['description']) ?></p>
        </div>
    </div>

    <!-- TEAMS NAV -->
    <nav aria-label="Navigation équipes" style="display:flex;flex-wrap:wrap;gap:8px;justify-content:center;">
        <?php foreach ($teams as $t): ?>
            <a
                href="?team=<?= h($t['id']) ?>"
                style="padding:8px 16px;border-radius:4px;font-size:13px;font-weight:600;
                       background:<?= $t['id'] === $activeTeam ? 'var(--bleu-fc)' : '#e8ecf5' ?>;
                       color:<?= $t['id'] === $activeTeam ? 'white' : 'var(--bleu-fc)' ?>;
                       text-decoration:none;transition:all 0.2s;"
                aria-current="<?= $t['id'] === $activeTeam ? 'page' : 'false' ?>"
            ><?= h($t['label']) ?></a>
        <?php endforeach; ?>
    </nav>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
