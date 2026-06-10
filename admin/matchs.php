<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireAdmin();
trackVisit('admin/matchs');

$errors = [];

// Add match
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_match'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Token invalide.';
    } else {
        $date   = $_POST['date_match']        ?? '';
        $heure  = $_POST['heure']             ?? '';
        $cat    = trim($_POST['categorie']    ?? '');
        $dom    = trim($_POST['equipe_dom']   ?? '');
        $ext    = trim($_POST['equipe_ext']   ?? '');
        $scorD  = $_POST['score_dom'] !== '' ? (int)$_POST['score_dom'] : null;
        $scorE  = $_POST['score_ext'] !== '' ? (int)$_POST['score_ext'] : null;
        $lieu   = in_array($_POST['lieu'] ?? '', ['Domicile','Exterieur','Neutre']) ? $_POST['lieu'] : 'Domicile';
        $comp   = trim($_POST['competition'] ?? '');

        if (!$date || !$heure || !$cat || !$dom || !$ext) {
            $errors[] = 'Veuillez remplir tous les champs obligatoires.';
        } else {
            db()->prepare(
                'INSERT INTO matchs (date_match,heure,categorie,equipe_domicile,equipe_exterieur,score_domicile,score_exterieur,lieu,competition)
                 VALUES (?,?,?,?,?,?,?,?,?)'
            )->execute([$date,$heure,$cat,$dom,$ext,$scorD,$scorE,$lieu,$comp]);
            flashMessage('success', 'Match ajouté.');
            redirect(SITE_URL . '/admin/matchs');
        }
    }
}

// Delete match
if (isset($_GET['delete'])) {
    db()->prepare('DELETE FROM matchs WHERE id=?')->execute([(int)$_GET['delete']]);
    flashMessage('success', 'Match supprimé.');
    redirect(SITE_URL . '/admin/matchs');
}

$matchs = db()->query('SELECT * FROM matchs ORDER BY date_match DESC, heure DESC')->fetchAll();

$pageTitle        = 'Gérer les Matchs';
$breadcrumbCenter = 'ACCÈS ADMIN';
$currentPage      = '';
require_once __DIR__ . '/../partials/header.php';
?>

<div style="padding:20px;max-width:1000px;margin:0 auto;">
    <h1 style="color:white;text-align:center;font-size:20px;font-weight:800;text-transform:uppercase;letter-spacing:2px;margin-bottom:24px;">
        Modifier les Matchs
    </h1>

    <?php if (!empty($errors)): ?>
        <div class="flash-message flash-error" role="alert">
            <?php foreach ($errors as $e): ?><div><?= h($e) ?></div><?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- FORM ADD MATCH -->
    <div style="background:#222;border-radius:8px;padding:24px;margin-bottom:24px;">
        <h2 style="color:#ccc;font-size:16px;font-weight:700;text-transform:uppercase;margin-bottom:18px;">Ajouter un Match</h2>
        <form method="POST" novalidate>
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="add_match" value="1">
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:12px;">
                <div>
                    <label style="color:#aaa;font-size:12px;display:block;margin-bottom:4px;">Date *</label>
                    <input type="date" name="date_match" class="form-control" required>
                </div>
                <div>
                    <label style="color:#aaa;font-size:12px;display:block;margin-bottom:4px;">Heure *</label>
                    <input type="time" name="heure" class="form-control" required>
                </div>
                <div>
                    <label style="color:#aaa;font-size:12px;display:block;margin-bottom:4px;">Catégorie *</label>
                    <input type="text" name="categorie" class="form-control" placeholder="Senior - Coupe" required>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                <div>
                    <label style="color:#aaa;font-size:12px;display:block;margin-bottom:4px;">Équipe domicile *</label>
                    <input type="text" name="equipe_dom" class="form-control" placeholder="GRANDE SYNTHE FC" required>
                </div>
                <div>
                    <label style="color:#aaa;font-size:12px;display:block;margin-bottom:4px;">Équipe extérieur *</label>
                    <input type="text" name="equipe_ext" class="form-control" placeholder="Équipe adverse" required>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:12px;margin-bottom:16px;">
                <div>
                    <label style="color:#aaa;font-size:12px;display:block;margin-bottom:4px;">Score Dom.</label>
                    <input type="number" name="score_dom" class="form-control" min="0" max="99">
                </div>
                <div>
                    <label style="color:#aaa;font-size:12px;display:block;margin-bottom:4px;">Score Ext.</label>
                    <input type="number" name="score_ext" class="form-control" min="0" max="99">
                </div>
                <div>
                    <label style="color:#aaa;font-size:12px;display:block;margin-bottom:4px;">Lieu</label>
                    <select name="lieu" class="form-control">
                        <option value="Domicile">Domicile</option>
                        <option value="Exterieur">Extérieur</option>
                        <option value="Neutre">Neutre</option>
                    </select>
                </div>
                <div>
                    <label style="color:#aaa;font-size:12px;display:block;margin-bottom:4px;">Compétition</label>
                    <input type="text" name="competition" class="form-control" placeholder="Championnat…">
                </div>
            </div>
            <button type="submit" class="btn-primary">Ajouter le Match</button>
        </form>
    </div>

    <!-- LISTE MATCHS -->
    <div style="background:#1a1a1a;border-radius:8px;overflow:hidden;">
        <div style="background:#2a2a2a;padding:14px 20px;">
            <h2 style="color:white;font-size:16px;font-weight:700;text-transform:uppercase;">Tous les Matchs</h2>
        </div>
        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Catégorie</th>
                        <th>Domicile</th>
                        <th>Extérieur</th>
                        <th>Score</th>
                        <th>Lieu</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($matchs)): ?>
                        <tr><td colspan="8" style="text-align:center;color:#888;padding:20px;">Aucun match enregistré.</td></tr>
                    <?php else: ?>
                        <?php foreach ($matchs as $m): ?>
                            <tr>
                                <td><?= h(date('d/m/Y', strtotime($m['date_match']))) ?></td>
                                <td><?= h(substr($m['heure'],0,5)) ?></td>
                                <td><?= h($m['categorie']) ?></td>
                                <td><?= h($m['equipe_domicile']) ?></td>
                                <td><?= h($m['equipe_exterieur']) ?></td>
                                <td>
                                    <?php if ($m['score_domicile'] !== null): ?>
                                        <?= h($m['score_domicile']) ?> - <?= h($m['score_exterieur']) ?>
                                    <?php else: ?>
                                        <span style="color:#888;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= h($m['lieu']) ?></td>
                                <td>
                                    <a
                                        href="?delete=<?= h($m['id']) ?>"
                                        onclick="return confirm('Supprimer ce match ?')"
                                        class="btn-icon delete"
                                        aria-label="Supprimer"
                                    >
                                        <img src="<?= ASSETS_URL ?>/images/icons/delete.png" alt="Supprimer">
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div style="text-align:center;margin-top:20px;">
        <a href="<?= SITE_URL ?>/admin-acces" class="btn-secondary" style="text-decoration:none;">← Retour Admin</a>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
