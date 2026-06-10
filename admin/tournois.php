<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireAdmin();
trackVisit('admin/tournois');

$errors = [];

// Add tournament
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_tournoi'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Token invalide.';
    } else {
        $date  = $_POST['date_tournoi'] ?? '';
        $heure = $_POST['heure']        ?? '';
        $jeu   = trim($_POST['jeu']     ?? 'EA Sports FC26');
        if (!$date || !$heure) {
            $errors[] = 'Date et heure obligatoires.';
        } else {
            db()->prepare('INSERT INTO tournois (date_tournoi, heure, jeu) VALUES (?,?,?)')
               ->execute([$date, $heure, $jeu ?: 'EA Sports FC26']);
            flashMessage('success', 'Tournoi ajouté.');
            redirect(SITE_URL . '/admin/tournois');
        }
    }
}

// Delete tournament
if (isset($_GET['delete'])) {
    db()->prepare('DELETE FROM tournois WHERE id=?')->execute([(int)$_GET['delete']]);
    flashMessage('success', 'Tournoi supprimé.');
    redirect(SITE_URL . '/admin/tournois');
}

// Toggle status
if (isset($_GET['status']) && isset($_GET['id'])) {
    $statuts = ['a_venir','en_cours','termine'];
    if (in_array($_GET['status'], $statuts)) {
        db()->prepare('UPDATE tournois SET statut=? WHERE id=?')->execute([$_GET['status'], (int)$_GET['id']]);
        flashMessage('success', 'Statut mis à jour.');
        redirect(SITE_URL . '/admin/tournois');
    }
}

$tournois = db()->query('SELECT * FROM tournois ORDER BY date_tournoi DESC')->fetchAll();

$pageTitle        = 'Gérer les Tournois';
$breadcrumbCenter = 'ACCÈS ADMIN';
$currentPage      = '';
require_once __DIR__ . '/../partials/header.php';
?>

<div style="padding:20px;max-width:900px;margin:0 auto;">
    <h1 style="color:white;text-align:center;font-size:20px;font-weight:800;text-transform:uppercase;letter-spacing:2px;margin-bottom:24px;">
        Gérer les Tournois Esport
    </h1>

    <?php if (!empty($errors)): ?>
        <div class="flash-message flash-error" role="alert">
            <?php foreach ($errors as $e): ?><div><?= h($e) ?></div><?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- ADD FORM -->
    <div style="background:#222;border-radius:8px;padding:24px;margin-bottom:24px;">
        <h2 style="color:#ccc;font-size:16px;font-weight:700;text-transform:uppercase;margin-bottom:18px;">Ajouter un Tournoi</h2>
        <form method="POST" novalidate>
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="add_tournoi" value="1">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:16px;">
                <div>
                    <label style="color:#aaa;font-size:12px;display:block;margin-bottom:4px;">Date *</label>
                    <input type="date" name="date_tournoi" class="form-control" required>
                </div>
                <div>
                    <label style="color:#aaa;font-size:12px;display:block;margin-bottom:4px;">Heure *</label>
                    <input type="time" name="heure" class="form-control" required value="14:00">
                </div>
                <div>
                    <label style="color:#aaa;font-size:12px;display:block;margin-bottom:4px;">Jeu</label>
                    <input type="text" name="jeu" class="form-control" value="EA Sports FC26">
                </div>
            </div>
            <button type="submit" class="btn-primary">Ajouter</button>
        </form>
    </div>

    <!-- LIST -->
    <div style="background:#1a1a1a;border-radius:8px;overflow:hidden;">
        <div style="background:#2a2a2a;padding:14px 20px;">
            <h2 style="color:white;font-size:16px;font-weight:700;text-transform:uppercase;">Tous les Tournois</h2>
        </div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Jeu</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tournois)): ?>
                    <tr><td colspan="5" style="text-align:center;color:#888;padding:20px;">Aucun tournoi.</td></tr>
                <?php else: ?>
                    <?php foreach ($tournois as $t): ?>
                        <tr>
                            <td><?= h(date('d/m/Y', strtotime($t['date_tournoi']))) ?></td>
                            <td><?= h(substr($t['heure'],0,5)) ?></td>
                            <td><?= h($t['jeu']) ?></td>
                            <td>
                                <select onchange="window.location='?id=<?= h($t['id']) ?>&status='+this.value" style="background:#333;color:white;border:1px solid #555;padding:4px 8px;border-radius:3px;font-size:12px;">
                                    <?php foreach(['a_venir'=>'À venir','en_cours'=>'En cours','termine'=>'Terminé'] as $v=>$l): ?>
                                        <option value="<?= $v ?>" <?= $t['statut']===$v?'selected':'' ?>><?= $l ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <a href="?delete=<?= h($t['id']) ?>" onclick="return confirm('Supprimer ce tournoi ?')" class="btn-icon delete" aria-label="Supprimer">
                                    <img src="<?= ASSETS_URL ?>/images/icons/delete.png" alt="Supprimer">
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="text-align:center;margin-top:20px;">
        <a href="<?= SITE_URL ?>/admin-acces" class="btn-secondary" style="text-decoration:none;">← Retour Admin</a>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
