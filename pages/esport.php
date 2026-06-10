<?php
require_once __DIR__ . '/../includes/bootstrap.php';
trackVisit('esport');

// Gestion réservation
$errors   = [];
$success  = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Token de sécurité invalide.';
    } else {
        $dateDebut = trim($_POST['date_debut'] ?? '');
        $dateFin   = trim($_POST['date_fin']   ?? '');
        $heure     = trim($_POST['heure']      ?? '');
        $contenu   = trim($_POST['contenu']    ?? '');

        if (!$dateDebut || !$dateFin || !$heure) $errors[] = 'Veuillez renseigner les dates et l\'heure.';
        if (strlen($contenu) < 5)                $errors[] = 'Le contenu de la réservation est trop court.';

        if (empty($errors)) {
            $userId = isLoggedIn() ? $_SESSION['user_id'] : null;
            $stmt = db()->prepare('INSERT INTO reservations (user_id, date_debut, date_fin, heure, contenu) VALUES (?,?,?,?,?)');
            $stmt->execute([$userId, $dateDebut, $dateFin, $heure, $contenu]);
            flashMessage('success', 'Votre réservation a bien été envoyée. Nous vous confirmerons par email.');
            header('Location: ' . SITE_URL . '/esport');
            exit;
        }
    }
}

// Charger les réservations du mois courant pour le calendrier
$calMonth = (int)date('m');
$calYear  = (int)date('Y');
$start    = sprintf('%04d-%02d-01', $calYear, $calMonth);
$end      = date('Y-m-t', strtotime($start));
$stmt     = db()->prepare('SELECT date_debut FROM reservations WHERE date_debut BETWEEN ? AND ? AND statut != "refuse"');
$stmt->execute([$start, $end]);
$reservedDates = array_column($stmt->fetchAll(), 'date_debut');

$pageTitle        = 'Esport';
$breadcrumbCenter = 'BIENVENUE DANS NOTRE PROJET E-SPORT';
$currentPage      = 'esport';
$theme            = 'esport';
require_once __DIR__ . '/../partials/header.php';
?>

<!-- TROIS CARTES ESPORT -->
<div class="esport-cards-grid">
    <div class="esport-card">
        <img src="<?= ASSETS_URL ?>/images/illustrations/esport_picture_01.jpg" alt="Infrastructure Esport" loading="lazy">
        <a href="<?= SITE_URL ?>/esport/infrastructure" class="esport-card-btn">S'INFORMER</a>
    </div>
    <div class="esport-card">
        <img src="<?= ASSETS_URL ?>/images/illustrations/tournois_fc2026.jpg" alt="Tournois FC 26" loading="lazy">
        <a href="<?= SITE_URL ?>/esport/tournaments" class="esport-card-btn">S'INFORMER</a>
    </div>
    <div class="esport-card">
        <img src="<?= ASSETS_URL ?>/images/illustrations/esport_picture_03.jpg" alt="Projet Éducatif" loading="lazy">
        <a href="<?= SITE_URL ?>/esport/projet-educatif" class="esport-card-btn">S'INFORMER</a>
    </div>
</div>

<!-- RÉSERVATION -->
<div class="reservation-section" aria-label="Section réservation">
    <h2 class="reservation-title">RESERVATION</h2>
</div>

<?php if (!empty($errors)): ?>
    <div class="flash-message flash-error" style="max-width:800px;margin:16px auto;" role="alert">
        <?php foreach ($errors as $e): ?><div><?= h($e) ?></div><?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- CALENDRIER -->
<div class="reservation-calendar" style="max-width:800px;margin:20px auto;">
    <div class="reservation-calendar-header" style="padding:14px 20px;font-size:16px;font-weight:700;">
        <?php
        $moisFr = ['','Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
        echo strtoupper($moisFr[$calMonth]) . ' ' . $calYear;
        ?>
    </div>
    <div style="padding:10px;">
        <div class="reservation-grid">
            <?php $jours = ['Lun.','Mar.','Mer.','Jeu.','Ven.','Sam.','Dim.']; ?>
            <?php foreach ($jours as $j): ?>
                <div class="reservation-day-header"><?= $j ?></div>
            <?php endforeach; ?>

            <?php
            $firstDay = (int)date('N', strtotime($start)); // 1=Lun
            $daysInMonth = (int)date('t', strtotime($start));
            // Cellules vides avant
            for ($i = 1; $i < $firstDay; $i++): ?>
                <div class="reservation-day-cell" style="background:transparent;"></div>
            <?php endfor; ?>

            <?php for ($d = 1; $d <= $daysInMonth; $d++):
                $dateStr = sprintf('%04d-%02d-%02d', $calYear, $calMonth, $d);
                $hasEvent = in_array($dateStr, $reservedDates);
            ?>
                <div
                    class="reservation-day-cell <?= $hasEvent ? 'has-event' : '' ?>"
                    data-date="<?= $dateStr ?>"
                    role="button"
                    tabindex="0"
                    aria-label="<?= sprintf('%02d %s %d', $d, $moisFr[$calMonth], $calYear) ?>"
                    aria-pressed="false"
                ><?= sprintf('%02d', $d) ?></div>
            <?php endfor; ?>
        </div>
    </div>
</div>

<!-- FORMULAIRE RÉSERVATION -->
<form class="reservation-form" method="POST" action="" novalidate>
    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
    <input type="hidden" name="book" value="1">

    <div class="form-row">
        <div class="form-field with-icon">
            <img src="<?= ASSETS_URL ?>/images/icons/calendar.png" alt="" class="field-icon">
            <input type="date" id="date_debut" name="date_debut" value="<?= h($_POST['date_debut'] ?? date('Y-m-d')) ?>" required aria-label="Date de début">
        </div>
        <div class="form-field with-icon">
            <img src="<?= ASSETS_URL ?>/images/icons/calendar.png" alt="" class="field-icon">
            <input type="date" id="date_fin" name="date_fin" value="<?= h($_POST['date_fin'] ?? date('Y-m-d')) ?>" required aria-label="Date de fin">
        </div>
        <div class="form-field with-icon">
            <img src="<?= ASSETS_URL ?>/images/icons/time.png" alt="" class="field-icon">
            <input type="time" name="heure" value="<?= h($_POST['heure'] ?? '00:00') ?>" required aria-label="Heure">
        </div>
    </div>

    <div>
        <label for="contenu" style="display:block;margin-bottom:6px;font-size:13px;color:#555;">Contenue de la réservation :</label>
        <textarea
            id="contenu"
            name="contenu"
            class="reservation-textarea"
            maxlength="200"
            placeholder="Décrivez votre réservation..."
            aria-label="Contenu de la réservation"
        ><?= h($_POST['contenu'] ?? '') ?></textarea>
        <div class="char-counter">0/200</div>
    </div>

    <button type="submit" class="btn-book">BOOK</button>
</form>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
