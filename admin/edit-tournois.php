<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireAdmin();
trackVisit('admin/edit-tournois');

// Save text content
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_content'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        flashMessage('error', 'Token invalide.');
    } else {
        $fields = ['tournoi_mensuel','tournoi_commente','tournoi_diffuse','tournoi_lots_feat',
                   'tournoi_lot_1','tournoi_lot_2','tournoi_lot_3','tournoi_reglement'];
        foreach ($fields as $f) {
            if (isset($_POST[$f])) setContent($f, trim($_POST[$f]));
        }
        flashMessage('success', 'Contenu des tournois mis à jour !');
        redirect(SITE_URL . '/admin/edit-tournois');
    }
}

// Add tournament date
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_tournoi'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        flashMessage('error', 'Token invalide.');
    } else {
        $date  = $_POST['date_tournoi'] ?? '';
        $heure = $_POST['heure']        ?? '';
        $jeu   = trim($_POST['jeu']     ?? 'EA Sports FC26');
        if ($date && $heure) {
            db()->prepare('INSERT INTO tournois (date_tournoi, heure, jeu) VALUES (?,?,?)')
               ->execute([$date, $heure, $jeu ?: 'EA Sports FC26']);
            flashMessage('success', 'Date de tournoi ajoutée.');
        } else {
            flashMessage('error', 'Date et heure obligatoires.');
        }
        redirect(SITE_URL . '/admin/edit-tournois');
    }
}

// Delete tournament date
if (isset($_GET['delete_tournoi'])) {
    db()->prepare('DELETE FROM tournois WHERE id=?')->execute([(int)$_GET['delete_tournoi']]);
    flashMessage('success', 'Tournoi supprimé.');
    redirect(SITE_URL . '/admin/edit-tournois');
}

$tournois = db()->query('SELECT * FROM tournois ORDER BY date_tournoi ASC')->fetchAll();

$pageTitle        = 'Modifier Tournois';
$breadcrumbCenter = 'ACCÈS ADMIN';
$currentPage      = '';
require_once __DIR__ . '/../partials/header.php';
?>

<div style="padding:20px;max-width:900px;margin:0 auto;">
    <h1 style="color:white;text-align:center;font-size:20px;font-weight:800;text-transform:uppercase;letter-spacing:2px;margin-bottom:24px;">
        Modifier la Page Tournois
    </h1>

    <form method="POST" novalidate>
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
        <input type="hidden" name="save_content" value="1">

        <!-- FEATURES -->
        <div style="background:#222;border-radius:8px;padding:24px;margin-bottom:20px;">
            <h2 style="color:#f0c030;font-size:14px;font-weight:700;text-transform:uppercase;margin-bottom:16px;">🏆 Caractéristiques des tournois</h2>
            <div class="form-group" style="margin-bottom:12px;">
                <label class="form-label" style="color:#aaa;">⚽ Tournois mensuels : (texte après le label)</label>
                <input class="form-control" type="text" name="tournoi_mensuel" value="<?= h(getContent('tournoi_mensuel')) ?>">
            </div>
            <div class="form-group" style="margin-bottom:12px;">
                <label class="form-label" style="color:#aaa;">🎙️ Commenté en anglais : (texte après le label)</label>
                <input class="form-control" type="text" name="tournoi_commente" value="<?= h(getContent('tournoi_commente')) ?>">
            </div>
            <div class="form-group" style="margin-bottom:12px;">
                <label class="form-label" style="color:#aaa;">📺 Matchs diffusés (texte après "Matchs diffusés")</label>
                <input class="form-control" type="text" name="tournoi_diffuse" value="<?= h(getContent('tournoi_diffuse')) ?>">
            </div>
            <div class="form-group">
                <label class="form-label" style="color:#aaa;">🎁 Lots à gagner (texte entre parenthèses)</label>
                <input class="form-control" type="text" name="tournoi_lots_feat" value="<?= h(getContent('tournoi_lots_feat')) ?>">
            </div>
        </div>

        <!-- LOTS DU PROCHAIN TOURNOIS -->
        <div style="background:#222;border-radius:8px;padding:24px;margin-bottom:20px;">
            <h2 style="color:#f0c030;font-size:14px;font-weight:700;text-transform:uppercase;margin-bottom:16px;">🥇 Lots du prochain tournois</h2>
            <div class="form-group" style="margin-bottom:12px;">
                <label class="form-label" style="color:#aaa;">1er prix</label>
                <input class="form-control" type="text" name="tournoi_lot_1" value="<?= h(getContent('tournoi_lot_1')) ?>">
            </div>
            <div class="form-group" style="margin-bottom:12px;">
                <label class="form-label" style="color:#aaa;">2e prix</label>
                <input class="form-control" type="text" name="tournoi_lot_2" value="<?= h(getContent('tournoi_lot_2')) ?>">
            </div>
            <div class="form-group">
                <label class="form-label" style="color:#aaa;">3e prix</label>
                <input class="form-control" type="text" name="tournoi_lot_3" value="<?= h(getContent('tournoi_lot_3')) ?>">
            </div>
        </div>

        <!-- RÈGLEMENT -->
        <div style="background:#222;border-radius:8px;padding:24px;margin-bottom:20px;">
            <h2 style="color:#f0c030;font-size:14px;font-weight:700;text-transform:uppercase;margin-bottom:16px;">📋 Règlement rapide</h2>
            <div class="form-group">
                <textarea class="form-control" name="tournoi_reglement" rows="5"><?= h(getContent('tournoi_reglement')) ?></textarea>
            </div>
        </div>

        <div style="display:flex;gap:12px;justify-content:center;margin-bottom:30px;">
            <button type="submit" class="btn-primary" style="font-size:15px;padding:14px 36px;">✅ Actualiser</button>
            <a href="<?= SITE_URL ?>/esport/tournaments" target="_blank" class="btn-secondary" style="text-decoration:none;padding:14px 24px;">👁 Voir la page</a>
            <a href="<?= SITE_URL ?>/admin-acces" class="btn-secondary" style="text-decoration:none;padding:14px 24px;">← Admin</a>
        </div>
    </form>

    <!-- DATES DES TOURNOIS -->
    <div style="background:#1a1a1a;border-radius:8px;padding:24px;margin-bottom:20px;">
        <h2 style="color:white;font-size:16px;font-weight:700;text-transform:uppercase;margin-bottom:20px;border-bottom:1px solid #333;padding-bottom:10px;">
            📅 Dates des tournois
        </h2>

        <!-- Ajouter une date -->
        <form method="POST" style="margin-bottom:20px;">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="add_tournoi" value="1">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:10px;align-items:end;">
                <div>
                    <label style="color:#aaa;font-size:12px;display:block;margin-bottom:4px;">Date *</label>
                    <input type="date" name="date_tournoi" class="form-control" required>
                </div>
                <div>
                    <label style="color:#aaa;font-size:12px;display:block;margin-bottom:4px;">Heure *</label>
                    <input type="time" name="heure" class="form-control" value="14:00" required>
                </div>
                <div>
                    <label style="color:#aaa;font-size:12px;display:block;margin-bottom:4px;">Jeu</label>
                    <input type="text" name="jeu" class="form-control" value="EA Sports FC26">
                </div>
                <button type="submit" class="btn-primary" style="height:42px;white-space:nowrap;">+ Ajouter</button>
            </div>
        </form>

        <!-- Liste des dates -->
        <?php if (empty($tournois)): ?>
            <p style="color:#888;text-align:center;padding:20px;">Aucune date de tournoi.</p>
        <?php else: ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Jeu</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tournois as $t): ?>
                        <tr>
                            <td><?= h(date('d/m/Y', strtotime($t['date_tournoi']))) ?></td>
                            <td><?= h(substr($t['heure'],0,5)) ?></td>
                            <td><?= h($t['jeu']) ?></td>
                            <td>
                                <select onchange="window.location='?change_status=<?= h($t['id']) ?>&status='+this.value"
                                        style="background:#333;color:white;border:1px solid #555;padding:4px 8px;border-radius:3px;font-size:12px;">
                                    <?php foreach(['a_venir'=>'À venir','en_cours'=>'En cours','termine'=>'Terminé'] as $v=>$l): ?>
                                        <option value="<?= $v ?>" <?= $t['statut']===$v?'selected':'' ?>><?= $l ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <a href="?delete_tournoi=<?= h($t['id']) ?>"
                                   onclick="return confirm('Supprimer cette date ?')"
                                   style="background:var(--rouge-vif);color:white;padding:4px 12px;border-radius:3px;font-size:12px;text-decoration:none;font-weight:700;">
                                    Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
