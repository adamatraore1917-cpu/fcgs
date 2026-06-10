<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireAdmin();
trackVisit('admin');

// Statistiques
$stats = [];
try {
    $stats['visites']      = db()->query('SELECT COUNT(*) FROM visites')->fetchColumn();
    $stats['inscrits']     = db()->query('SELECT COUNT(*) FROM users WHERE role="user"')->fetchColumn();
    $stats['inscriptions'] = db()->query('SELECT COUNT(*) FROM users')->fetchColumn();
    $stats['reservations'] = db()->query('SELECT COUNT(*) FROM reservations')->fetchColumn();
    $stats['galerie']      = db()->query('SELECT COUNT(*) FROM galerie WHERE filename IS NOT NULL')->fetchColumn();
    $stats['matchs']       = db()->query('SELECT COUNT(*) FROM matchs')->fetchColumn();
    $stats['matchs_avenir']= db()->query('SELECT COUNT(*) FROM matchs WHERE date_match >= CURDATE()')->fetchColumn();
    $stats['contacts']     = db()->query('SELECT COUNT(*) FROM contacts WHERE statut="en_attente"')->fetchColumn();
} catch (PDOException $e) {
    $stats = array_fill_keys(['visites','inscrits','inscriptions','reservations','galerie','matchs','matchs_avenir','contacts'], 0);
}

// Admins list
$admins = db()->query(
    'SELECT u.id, u.nom, u.prenom, u.email, u.derniere_connexion, u.role,
            ap.voir_comptes, ap.modifier_comptes, ap.voir_contacts, ap.modifier_galerie,
            ap.voir_reservations, ap.modifier_matchs
     FROM users u
     LEFT JOIN admin_permissions ap ON ap.user_id = u.id
     WHERE u.role IN ("admin","superadmin")
     ORDER BY u.id'
)->fetchAll();

// Toggle permission AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggle_perm') {
    if (!verifyCsrfToken($_POST['csrf'] ?? '')) { http_response_code(403); exit; }
    $userId = (int)$_POST['user_id'];
    $perm   = preg_replace('/[^a-z_]/', '', $_POST['perm'] ?? '');
    $val    = (int)$_POST['val'];
    $allowed = ['voir_comptes','modifier_comptes','voir_contacts','modifier_galerie','voir_reservations','modifier_matchs'];
    if (in_array($perm, $allowed)) {
        db()->prepare("UPDATE admin_permissions SET $perm=? WHERE user_id=?")->execute([$val,$userId]);
    }
    exit;
}

$pageTitle        = 'Administration';
$breadcrumbCenter = 'ACCÈS ADMIN';
$currentPage      = '';
$theme            = 'football';
require_once __DIR__ . '/../partials/header.php';
?>

<input type="hidden" id="csrf-token" value="<?= generateCsrfToken() ?>">

<!-- ACTIONS GRID -->
<div class="admin-actions-grid">
    <a href="<?= SITE_URL ?>/admin/accounts"     class="admin-action-btn">VOIR LES COMPTES DU SITES</a>
    <a href="<?= SITE_URL ?>/admin/edit-accounts" class="admin-action-btn">MODIFIER LES COMPTES DU SITE</a>

    <a href="<?= SITE_URL ?>/admin/contacts"            class="admin-action-btn">VOIR LES DEMANDES DE CONTACTS</a>
    <a href="<?= SITE_URL ?>/admin/gallery"             class="admin-action-btn">MODIFIER LES PHOTOS DE LA GALLERIE</a>

    <a href="<?= SITE_URL ?>/admin/edit-infrastructure" class="admin-action-btn">MODIFIER PAGE INFRASTRUCTURES</a>
    <a href="<?= SITE_URL ?>/admin/edit-tournois"       class="admin-action-btn">MODIFIER PAGE TOURNOIS</a>

    <a href="<?= SITE_URL ?>/admin/staff"               class="admin-action-btn">GÉRER LE STAFF</a>
    <a href="<?= SITE_URL ?>/admin/partenaires"         class="admin-action-btn">GÉRER LES PARTENAIRES</a>

    <a href="<?= SITE_URL ?>/admin/avis"                class="admin-action-btn">MODÉRER LES AVIS</a>
    <a href="<?= SITE_URL ?>/admin/reservations"        class="admin-action-btn">VOIR LES RESERVATIONS</a>

    <a href="<?= SITE_URL ?>/admin/matchs"              class="admin-action-btn">MODIFIER LES MATCHS</a>
    <a href="<?= SITE_URL ?>/admin/tournois"            class="admin-action-btn">GÉRER LES DATES TOURNOIS</a>
</div>

<!-- STATISTIQUES -->
<div class="stats-card">
    <h2 class="stats-title">STATISTIQUE DU SITE</h2>
    <div class="stats-grid">
        <div>
            <div class="stat-row">
                <div class="stat-label">NOMBRE DE VISITE :</div>
                <div class="stat-value"><?= h($stats['visites']) ?></div>
            </div>
            <div class="stat-row">
                <div class="stat-label">NOMBRE D'INSCRIPTION :</div>
                <div class="stat-value"><?= h($stats['inscriptions']) ?></div>
            </div>
            <div class="stat-row">
                <div class="stat-label">NOMBRE DE RESERVATIONS :</div>
                <div class="stat-value"><?= h($stats['reservations']) ?></div>
            </div>
            <div class="stat-row">
                <div class="stat-label">NOMBRE DE PHOTOS GALLERIE :</div>
                <div class="stat-value"><?= h($stats['galerie']) ?></div>
            </div>
            <div class="stat-row">
                <div class="stat-label">NOMBRE DE MATCHS :</div>
                <div class="stat-value"><?= h($stats['matchs']) ?></div>
            </div>
            <div class="stat-row">
                <div class="stat-label">NOMBRE DE MATCHS A VENIR :</div>
                <div class="stat-value"><?= h($stats['matchs_avenir']) ?></div>
            </div>
        </div>
        <div>
            <div class="stat-row">
                <div class="stat-label">NOMBRE D'INSCRIT :</div>
                <div class="stat-value"><?= h($stats['inscrits']) ?> <small style="font-size:12px;color:#aaa;">/par licenses</small></div>
            </div>
            <div class="stat-row" style="margin-top:80px;">
                <div class="stat-label">NOMBRE DE CONTACT EN ATTENTE :</div>
                <div class="stat-value pending"><?= h($stats['contacts']) ?></div>
            </div>
        </div>
    </div>
</div>

<!-- GESTION ADMINISTRATEURS -->
<?php if (isSuperAdmin()): ?>
<div class="admin-table-section">
    <h2 class="admin-table-title">GESTION DES ADMINISTRATEURS :</h2>
    <div style="overflow-x:auto;">
        <table class="admin-table" aria-label="Liste des administrateurs">
            <thead>
                <tr>
                    <th>COMPTE ADMIN</th>
                    <th colspan="3">AUTORISATIONS</th>
                    <th>Dernière connexion</th>
                    <th>Actes</th>
                </tr>
                <tr>
                    <th></th>
                    <th style="font-size:10px;">Voir comptes</th>
                    <th style="font-size:10px;">Voir matchs</th>
                    <th style="font-size:10px;">Modifier galerie</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td><?= h($admin['email']) ?></td>
                        <td>
                            <input type="checkbox" class="perm-check perm-toggle"
                                <?= $admin['voir_comptes'] ? 'checked' : '' ?>
                                data-user="<?= h($admin['id']) ?>"
                                data-perm="voir_comptes"
                                aria-label="Permission voir comptes"
                            >
                        </td>
                        <td>
                            <input type="checkbox" class="perm-check perm-toggle"
                                <?= $admin['modifier_matchs'] ? 'checked' : '' ?>
                                data-user="<?= h($admin['id']) ?>"
                                data-perm="modifier_matchs"
                                aria-label="Permission modifier matchs"
                            >
                        </td>
                        <td>
                            <input type="checkbox" class="perm-check perm-toggle"
                                <?= $admin['modifier_galerie'] ? 'checked' : '' ?>
                                data-user="<?= h($admin['id']) ?>"
                                data-perm="modifier_galerie"
                                aria-label="Permission modifier galerie"
                            >
                        </td>
                        <td><?= $admin['derniere_connexion'] ? h(date('d/m/Y H\hi', strtotime($admin['derniere_connexion']))) : 'Jamais' ?></td>
                        <td>
                            <?php if ($admin['id'] != $_SESSION['user_id']): ?>
                                <a href="?delete_admin=<?= h($admin['id']) ?>&csrf=<?= generateCsrfToken() ?>"
                                   class="btn-icon delete"
                                   onclick="return confirm('Supprimer cet administrateur ?')"
                                   aria-label="Supprimer">
                                    <img src="<?= ASSETS_URL ?>/images/icons/delete.png" alt="Supprimer">
                                </a>
                            <?php endif; ?>
                            <a href="<?= SITE_URL ?>/admin/edit-accounts?id=<?= h($admin['id']) ?>"
                               class="btn-icon"
                               aria-label="Modifier">
                                <img src="<?= ASSETS_URL ?>/images/icons/edit.png" alt="Modifier">
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
