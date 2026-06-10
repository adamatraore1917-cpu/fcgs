<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireAdmin();
trackVisit('admin/accounts');

// Delete account
if (isset($_GET['delete']) && isSuperAdmin()) {
    $id = (int)$_GET['delete'];
    if ($id !== $_SESSION['user_id']) {
        db()->prepare('DELETE FROM users WHERE id=?')->execute([$id]);
        flashMessage('success', 'Compte supprimé.');
        redirect(SITE_URL . '/admin/accounts');
    }
}

// Search
$search = trim($_GET['q'] ?? '');
if ($search) {
    $stmt = db()->prepare(
        'SELECT * FROM users WHERE role="user" AND (email LIKE ? OR nom LIKE ? OR prenom LIKE ?) ORDER BY created_at DESC'
    );
    $like = '%' . $search . '%';
    $stmt->execute([$like, $like, $like]);
} else {
    $stmt = db()->query('SELECT * FROM users WHERE role="user" ORDER BY created_at DESC');
}
$users = $stmt->fetchAll();

$licenseLabels = [
    'seniors'           => 'Sénior',
    'enfants'           => 'Enfants',
    'dirigeant'         => 'Dirigeant',
    'arbitre'           => 'Arbitre',
    'educateur_federal' => 'Éducateur fédéral',
];

$pageTitle        = 'Liste des comptes';
$breadcrumbCenter = 'ACCÈS ADMIN';
$currentPage      = '';
require_once __DIR__ . '/../partials/header.php';
?>

<div class="accounts-list-card">
    <h1 class="accounts-title">LISTE DES COMPTES</h1>

    <div class="accounts-header">
        <span>COMPTE UTILISATEUR</span>
        <span>INSCRIPTION : Type de license</span>
        <span>Dernière connexion</span>
    </div>

    <?php if (empty($users)): ?>
        <p style="color:#aaa;text-align:center;padding:24px;">Aucun compte trouvé.</p>
    <?php else: ?>
        <?php foreach ($users as $u): ?>
            <div class="account-row">
                <span class="account-email">
                    <a href="<?= SITE_URL ?>/admin/edit-accounts?id=<?= h($u['id']) ?>" style="color:var(--bleu-med);text-decoration:underline;">
                        <?= h($u['email']) ?>
                    </a>
                </span>
                <span class="account-license">
                    <?= h($licenseLabels[$u['type_license'] ?? ''] ?? '—') ?>
                </span>
                <span class="account-date">
                    <?= $u['derniere_connexion']
                        ? h(date("Aujourd'hui H\hi", strtotime($u['derniere_connexion'])))
                        : 'Jamais' ?>
                </span>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <input
        type="search"
        id="search-admin"
        class="search-bar-admin"
        placeholder="Effectuer une recherche..."
        value="<?= h($search) ?>"
        aria-label="Rechercher un compte"
    >
</div>

<div style="text-align:center;padding:20px;">
    <a href="<?= SITE_URL ?>/admin-acces" class="btn-secondary" style="text-decoration:none;">← Retour Admin</a>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
