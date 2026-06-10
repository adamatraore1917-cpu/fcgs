<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireLogin();

$user = getCurrentUser();
if (!$user) { flashMessage('error','Session expirée.'); redirect(SITE_URL . '/inscription'); }

// Gestion déconnexion
if (isset($_GET['logout'])) {
    setcookie('remember_token', '', time() - 3600, '/');
    session_destroy();
    redirect(SITE_URL . '/');
}

// Mise à jour profil
$errors  = [];
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Token invalide.';
    } else {
        $nom    = trim($_POST['nom']    ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $ville  = trim($_POST['ville']  ?? '');
        $cp     = trim($_POST['code_postal'] ?? '');
        if (!$nom || !$prenom) { $errors[] = 'Nom et prénom requis.'; }
        if (empty($errors)) {
            db()->prepare('UPDATE users SET nom=?,prenom=?,ville=?,code_postal=? WHERE id=?')
               ->execute([$nom,$prenom,$ville,$cp,$user['id']]);
            $_SESSION['user_nom']    = $nom;
            $_SESSION['user_prenom'] = $prenom;
            flashMessage('success','Profil mis à jour.');
            redirect(SITE_URL . '/profil');
        }
    }
}

$licenseLabels = [
    'seniors'           => 'Séniors',
    'enfants'           => 'Enfants',
    'dirigeant'         => 'Dirigeant',
    'arbitre'           => 'Arbitre',
    'educateur_federal' => 'Éducateur fédéral',
];

$pageTitle        = 'Mon Profil';
$breadcrumbCenter = 'MON ESPACE';
$currentPage      = '';
require_once __DIR__ . '/../partials/header.php';
?>

<div class="container" style="padding:30px 20px;">

    <?php if (!empty($errors)): ?>
        <div class="flash-message flash-error" role="alert">
            <?php foreach ($errors as $e): ?><div><?= h($e) ?></div><?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="profile-card">
        <h1 class="profile-title">Mon Profil</h1>

        <!-- INFOS EN LECTURE -->
        <div style="margin-bottom:24px;">
            <div class="profile-info-row">
                <span class="profile-info-label">Email</span>
                <span class="profile-info-value"><?= h($user['email']) ?></span>
            </div>
            <div class="profile-info-row">
                <span class="profile-info-label">Licence</span>
                <span class="profile-info-value"><?= h($licenseLabels[$user['type_license'] ?? ''] ?? '—') ?></span>
            </div>
            <div class="profile-info-row">
                <span class="profile-info-label">Rôle</span>
                <span class="profile-info-value" style="text-transform:capitalize;"><?= h($user['role']) ?></span>
            </div>
            <div class="profile-info-row">
                <span class="profile-info-label">Membre depuis</span>
                <span class="profile-info-value"><?= h(date('d/m/Y', strtotime($user['created_at']))) ?></span>
            </div>
        </div>

        <!-- FORMULAIRE MODIFICATION -->
        <form method="POST" action="" novalidate>
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="update_profile" value="1">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="form-group">
                    <label class="form-label" for="nom">Nom</label>
                    <input class="form-control" type="text" id="nom" name="nom" value="<?= h($_POST['nom'] ?? $user['nom']) ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="prenom">Prénom</label>
                    <input class="form-control" type="text" id="prenom" name="prenom" value="<?= h($_POST['prenom'] ?? $user['prenom']) ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="ville">Ville</label>
                    <input class="form-control" type="text" id="ville" name="ville" value="<?= h($_POST['ville'] ?? $user['ville']) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" for="code_postal">Code postal</label>
                    <input class="form-control" type="text" id="code_postal" name="code_postal" value="<?= h($_POST['code_postal'] ?? $user['code_postal']) ?>">
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:10px;flex-wrap:wrap;">
                <button type="submit" class="btn-primary">Mettre à jour</button>
                <a href="<?= SITE_URL ?>/profil?logout=1" class="btn-danger" style="text-decoration:none;">Se déconnecter</a>
                <?php if (isAdmin()): ?>
                    <a href="<?= SITE_URL ?>/admin-acces" class="btn-secondary" style="text-decoration:none;">Accès Admin</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- MES RÉSERVATIONS -->
    <?php
    $stmtRes = db()->prepare('SELECT * FROM reservations WHERE user_id = ? ORDER BY created_at DESC LIMIT 10');
    $stmtRes->execute([$user['id']]);
    $mesReservations = $stmtRes->fetchAll();
    ?>
    <?php if (!empty($mesReservations)): ?>
        <div class="profile-card" style="margin-top:20px;">
            <h2 class="profile-title" style="font-size:18px;">Mes Réservations Esport</h2>
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#f0f4ff;">
                        <th style="padding:10px;text-align:left;font-size:12px;color:#666;text-transform:uppercase;">Date</th>
                        <th style="padding:10px;text-align:left;font-size:12px;color:#666;text-transform:uppercase;">Heure</th>
                        <th style="padding:10px;text-align:left;font-size:12px;color:#666;text-transform:uppercase;">Contenu</th>
                        <th style="padding:10px;text-align:left;font-size:12px;color:#666;text-transform:uppercase;">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mesReservations as $r): ?>
                        <tr style="border-bottom:1px solid #eee;">
                            <td style="padding:10px;font-size:13px;"><?= h($r['date_debut']) ?></td>
                            <td style="padding:10px;font-size:13px;"><?= h(substr($r['heure'],0,5)) ?></td>
                            <td style="padding:10px;font-size:13px;"><?= h(mb_strimwidth($r['contenu'],0,50,'…')) ?></td>
                            <td style="padding:10px;">
                                <span style="
                                    padding:3px 8px;border-radius:3px;font-size:11px;font-weight:700;text-transform:uppercase;
                                    background:<?= ['en_attente'=>'#f0ad4e','confirme'=>'#5cb85c','refuse'=>'#d9534f','annule'=>'#aaa'][$r['statut']] ?? '#aaa' ?>;
                                    color:white;
                                "><?= h($r['statut']) ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
