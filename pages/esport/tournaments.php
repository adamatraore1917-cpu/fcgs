<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
trackVisit('esport/tournaments');

$stmt = db()->prepare('SELECT * FROM tournois WHERE statut IN ("a_venir","en_cours") ORDER BY date_tournoi ASC');
$stmt->execute();
$tournois = $stmt->fetchAll();

// Editable content
$mensuel  = getContent('tournoi_mensuel',   'chaque dernier samedi du mois');
$commente = getContent('tournoi_commente',  'par un animateur spécialisé');
$diffuse  = getContent('tournoi_diffuse',   'sur écran géant dans le club house');
$lotsFeat = getContent('tournoi_lots_feat', '(maillots, goodies, dotations)');
$lot1     = getContent('tournoi_lot_1',     'Maillot FC Grande-Synthe + 50€ de chèque cadeau.');
$lot2     = getContent('tournoi_lot_2',     'Casque gaming');
$lot3     = getContent('tournoi_lot_3',     'Goodies du club');
$reglem   = getContent('tournoi_reglement', "Matchs en 1 contre 1, durée 6min, équipe au hasard.\n\nRèglement complet disponible sur place.");

$pageTitle        = 'Tournois Esport';
$breadcrumbCenter = 'BIENVENUE DANS NOTRE PROJET E-SPORT';
$currentPage      = 'esport';
$theme            = 'esport';
require_once __DIR__ . '/../../partials/header.php';

?>

<div class="tournois-hero-container">
    <img src="<?= ASSETS_URL ?>/images/illustrations/tournois_fc2026.jpg" alt="Tournois EA Sports FC 26" class="tournois-hero" loading="eager">
</div>




<section class="tournois-section" aria-labelledby="tournois-title">
    <div style="background:var(--rose-pale);padding:20px;border-radius:4px;text-align:center;margin-bottom:30px;">
        <h1 class="tournois-title" id="tournois-title">DES COMPÉTITIONS RÉGULIÈRES SUR EA SPORTS FC 26</h1>
        <p class="tournois-subtitle">Le club organise des tournois tout au long de l'année, ouverts à tous les membres, du débutant au confirmé.</p>
    </div>

    <div class="tournois-grid">
        <div class="tournois-features">
            <div class="tournois-feature">
                <img src="<?= ASSETS_URL ?>/images/icons/football.png" alt="">
                <span><strong>Tournois mensuels :</strong> <?= h($mensuel) ?></span>
            </div>
            <div class="tournois-feature">
                <img src="<?= ASSETS_URL ?>/images/icons/translate.png" alt="">
                <span><strong>Commenté en anglais :</strong> <?= h($commente) ?></span>
            </div>
            <div class="tournois-feature">
                <img src="<?= ASSETS_URL ?>/images/icons/broadcast.png" alt="">
                <span><strong>Matchs diffusés</strong> <?= h($diffuse) ?></span>
            </div>
            <div class="tournois-feature">
                <img src="<?= ASSETS_URL ?>/images/icons/reward.png" alt="">
                <span><strong>Lots à gagner</strong> <?= h($lotsFeat) ?></span>
            </div>
        </div>

        <div class="dates-card">
            <div class="dates-card-header">
                <img src="<?= ASSETS_URL ?>/images/icons/calendar.png" alt="" style="height:20px;">
                Dates
            </div>
            <table class="dates-table" aria-label="Dates des prochains tournois">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th style="color:var(--rouge-esport)">Tournois à venir :<br>Heure</th>
                        <th>Jeu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tournois)): ?>
                        <tr><td colspan="3" style="text-align:center;color:#999;padding:16px;">Aucun tournoi annoncé.</td></tr>
                    <?php else: ?>
                        <?php foreach ($tournois as $t): ?>
                            <tr>
                                <td><?= formatDateShortFr($t['date_tournoi']) ?></td>
                                <td><?= h(substr($t['heure'],0,5)) ?></td>
                                <td><?= h($t['jeu']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<div class="tournois-bottom-grid">
    <div class="lots-card">
        <h3><img src="<?= ASSETS_URL ?>/images/icons/reward.png" alt="" style="height:24px;"> Lots du prochain tournois</h3>
        <ul>
            <li>1er : <?= h($lot1) ?></li>
            <li>2e : <?= h($lot2) ?></li>
            <li>3e : <?= h($lot3) ?></li>
        </ul>
    </div>
    <div class="reglement-card">
        <h3><img src="<?= ASSETS_URL ?>/images/icons/matchs.png" alt="" style="height:24px;"> Règlement rapide</h3>
        <p style="font-size:13px;color:#555;line-height:1.6;"><?= nl2br(h($reglem)) ?></p>
    </div>
</div>

<div style="text-align:center;padding:20px 20px 40px;">
    <a href="<?= SITE_URL ?>/esport" class="btn-primary" style="display:inline-block;text-decoration:none;margin-right:10px;">← Retour Esport</a>
    <a href="<?= SITE_URL ?>/inscription" class="btn-danger" style="display:inline-block;text-decoration:none;">S'inscrire pour participer</a>
</div>

<?php require_once __DIR__ . '/../../partials/footer.php'; ?>
