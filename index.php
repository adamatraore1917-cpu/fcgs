<?php
require_once __DIR__ . '/includes/bootstrap.php';
trackVisit('accueil');

// Navigation mois/année
$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
$year  = isset($_GET['year'])  ? (int)$_GET['year']  : (int)date('Y');
if ($month < 1 || $month > 12) { $month = (int)date('m'); }
if ($year < 2000 || $year > 2100) { $year = (int)date('Y'); }

$matchs = getMatchsByMonth($year, $month);

// Regrouper par date
$matchsByDate = [];
foreach ($matchs as $m) {
    $matchsByDate[$m['date_match']][] = $m;
}

$moisFr = ['','JANVIER','FÉVRIER','MARS','AVRIL','MAI','JUIN',
           'JUILLET','AOÛT','SEPTEMBRE','OCTOBRE','NOVEMBRE','DÉCEMBRE'];

$pageTitle = 'Accueil';
$breadcrumbCenter = 'BIENVENUE SUR NOTRE SITE INTERNET';
$currentPage = 'accueil';
require_once __DIR__ . '/partials/header.php';
?>

<div class="home-layout">
    <!-- COLONNE PRINCIPALE (GAUCHE) -->
    <div>
        <!-- BANDEAU DE PRÉSENTATION -->
        <div class="hero-welcome">
            <div class="hero-text">
                <h1 class="hero-title">BIENVENUE AU FC GRANDE-SYNTHE</h1>
                <p class="hero-subtitle">Football & Esport – Passion, Éducation, Ambition</p>
                <p class="hero-description">
                    Le FC Grande-Synthe, c’est plus qu’un club : une famille engagée pour le sport,
                    la jeunesse et l’innovation numérique dans les Hauts-de-France.
                </p>
                <div class="hero-buttons">
                    <a href="<?= SITE_URL ?>/club" class="btn-hero">DÉCOUVRIR LE CLUB</a>
                    <a href="<?= SITE_URL ?>/esport" class="btn-hero btn-hero-esport">L'ESPACE E-SPORT</a>
                </div>
            </div>
        </div>

        <!-- CALENDRIER DES MATCHS -->
        <div class="calendar-widget">
            <div class="calendar-header">
                <div class="calendar-title">
                    <img src="<?= ASSETS_URL ?>/images/icons/calendar.png" alt="Calendrier">
                    <?= $moisFr[$month] ?> <?= $year ?>
                </div>
                <div class="calendar-nav">
                    <button id="cal-prev" aria-label="Mois précédent">&#9664;</button>
                    <button id="cal-next" aria-label="Mois suivant">&#9654;</button>
                </div>
            </div>
            <div class="calendar-body">
                <?php if (empty($matchsByDate)): ?>
                    <p class="no-match">Aucun match ce mois-ci.</p>
                <?php else: ?>
                    <?php foreach ($matchsByDate as $date => $dayMatchs): ?>
                        <div class="match-day-header"><?= strtoupper(formatDateFr($date)) ?></div>
                        <?php foreach ($dayMatchs as $match): ?>
                            <div class="match-row">
                                <span class="match-time"><?= h(substr($match['heure'], 0, 5)) ?></span>
                                <span class="match-cat"><?= h($match['categorie']) ?></span>
                                <span class="match-teams"><?= h($match['equipe_domicile']) ?> vs <?= h($match['equipe_exterieur']) ?></span>
                                <?php if ($match['score_domicile'] !== null): ?>
                                    <span class="match-score"><?= h($match['score_domicile']) ?> - <?= h($match['score_exterieur']) ?></span>
                                <?php endif; ?>
                                <span class="match-lieu <?= strtolower($match['lieu']) === 'exterieur' ? 'exterieur' : '' ?>">
                                    <?= h($match['lieu']) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- SIDEBAR (DROITE) -->
    <aside class="home-sidebar">
        <!-- INFOS CLUB -->
        <div class="sidebar-card">
            <div class="sidebar-card-header">
                <img src="<?= ASSETS_URL ?>/images/icons/information.png" alt="">
                INFOS CLUB
            </div>
            <div class="sidebar-card-body">
                <div class="sidebar-info-row">
                    <strong>Adresse :</strong>
                    Club house - boulevard des Fédérés<br>59760 GRANDE-SYNTHE
                </div>
                <div class="sidebar-info-row">
                    <strong>Tél :</strong>
                    06 86 40 84 26
                </div>
                <div class="sidebar-info-row">
                    <strong>Email :</strong>
                    <a href="mailto:contact@fcgrandesynthe.fr">contact@fcgrandesynthe.fr</a>
                </div>
                <div class="sidebar-info-row">
                    <strong>Site FFF :</strong>
                    <a href="https://epreuves.fff.fr" target="_blank" rel="noopener">epreuves.fff.fr</a>
                </div>
            </div>
        </div>

        <!-- STAFF -->
        <div class="sidebar-card">
            <div class="sidebar-card-header">
                <img src="<?= ASSETS_URL ?>/images/icons/staff.png" alt="">
                STAFF
            </div>
            <div class="sidebar-card-body">
                <div class="staff-title">Président :</div>
                <div class="staff-name">MAKHLOUFI ABDOULLAH</div>

                <div class="staff-title">Correspondant :</div>
                <div class="staff-name">OMER RACHÉ</div>
                <div class="staff-phone">08 22 09 62 71</div>

                <div class="staff-title">Secrétaire générale :</div>
                <div class="staff-name">KENOU BRAHIM</div>
                <div class="staff-phone">08 86 40 84 26</div>

                <div class="staff-title">Réf. projet club :</div>
                <div class="staff-name">LOZANO PIERRE</div>
            </div>
        </div>

        <!-- RECRUTEMENT -->
        <div class="recrutement-card">
            <div class="card-title">RECRUTEMENT</div>
            <p>Nous recrutons des éducateurs, coachs et bénévoles pour la saison 2026/2027</p>
        </div>

        <!-- WORLDCUP 2026 -->
        <div class="worldcup-card">
            <div class="wc-title">WORLDCUP<br>2026</div>
            <div class="wc-subtitle">
                <a href="https://www.fifa.com/fifaplus/fr/tournaments/mens/worldcup/canadamexicousa2026"
                   target="_blank"
                   rel="noopener noreferrer"
                   style="color: yellow; text-decoration: none; font-weight: bold;">
                    FIFA.COM
                </a>
            </div>
            <img src="<?= ASSETS_URL ?>/images/illustrations/cup26.jpg" alt="FIFA World Cup 2026">
        </div>
    </aside>
</div>

<!-- SPONSORS -->
<section class="sponsors-section" aria-labelledby="sponsors-title">
    <h2 class="sponsors-title" id="sponsors-title">LES SPONSORS :</h2>
    <div class="sponsors-grid">
        <p style="color:#aaa;font-size:13px;">Contactez-nous pour devenir partenaire.</p>
    </div>
</section>

<?php require_once __DIR__ . '/partials/footer.php'; ?>