<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireAdmin();

$userId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$errors = [];

// Load user
$stmt = db()->prepare('SELECT * FROM users WHERE id=?');
$stmt->execute([$userId]);
$editUser = $stmt->fetch();
if (!$editUser) {
    flashMessage('error', 'Utilisateur introuvable.');
    redirect(SITE_URL . '/admin/accounts');
}

// Promote to admin
if (isset($_GET['promote']) && isSuperAdmin()) {
    $newRole = ($editUser['role'] === 'user') ? 'admin' : 'user';
    db()->prepare('UPDATE users SET role=? WHERE id=?')->execute([$newRole, $userId]);
    // Ensure admin_permissions row
    if ($newRole === 'admin') {
        $check = db()->prepare('SELECT id FROM admin_permissions WHERE user_id=?');
        $check->execute([$userId]);
        if (!$check->fetch()) {
            db()->prepare('INSERT INTO admin_permissions (user_id) VALUES (?)')->execute([$userId]);
        }
    }
    flashMessage('success', 'Rôle mis à jour.');
    redirect(SITE_URL . '/admin/edit-accounts?id=' . $userId);
}

// Update user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Token invalide.';
    } else {
        $nom     = trim($_POST['nom']    ?? '');
        $prenom  = trim($_POST['prenom'] ?? '');
        $email   = trim($_POST['email']  ?? '');
        $ville   = trim($_POST['ville']  ?? '');
        $cp      = trim($_POST['code_postal'] ?? '');
        $license = $_POST['type_license'] ?? '';
        $validL  = ['seniors','enfants','dirigeant','arbitre','educateur_federal',''];

        if (!$nom || !$prenom)     $errors[] = 'Nom et prénom requis.';
        if (!isValidEmail($email)) $errors[] = 'Email invalide.';
        if (!in_array($license, $validL)) $license = '';

        // New password
        $newPass = trim($_POST['new_password'] ?? '');
        $hash    = null;
        if ($newPass) {
            if (strlen($newPass) < 8) $errors[] = 'Mot de passe : 8 caractères minimum.';
            else $hash = password_hash($newPass, PASSWORD_BCRYPT, ['cost' => 12]);
        }

        if (empty($errors)) {
            if ($hash) {
                db()->prepare('UPDATE users SET nom=?,prenom=?,email=?,ville=?,code_postal=?,type_license=?,password=? WHERE id=?')
                   ->execute([$nom,$prenom,$email,$ville,$cp,$license ?: null,$hash,$userId]);
            } else {
                db()->prepare('UPDATE users SET nom=?,prenom=?,email=?,ville=?,code_postal=?,type_license=? WHERE id=?')
                   ->execute([$nom,$prenom,$email,$ville,$cp,$license ?: null,$userId]);
            }
            flashMessage('success', 'Compte mis à jour.');
            redirect(SITE_URL . '/admin/edit-accounts?id=' . $userId);
        }
    }
}

// Delete user
if (isset($_GET['delete_user']) && isSuperAdmin()) {
    if ($userId !== (int)$_SESSION['user_id']) {
        db()->prepare('DELETE FROM users WHERE id=?')->execute([$userId]);
        flashMessage('success', 'Compte supprimé.');
        redirect(SITE_URL . '/admin/accounts');
    }
}

$licenseLabels = [
    'seniors'=>'Séniors','enfants'=>'Enfants','dirigeant'=>'Dirigeant',
    'arbitre'=>'Arbitre','educateur_federal'=>'Éducateur fédéral',
];

$pageTitle        = 'Modifier un Compte';
$breadcrumbCenter = 'ACCÈS ADMIN';
$currentPage      = '';
require_once __DIR__ . '/../partials/header.php';
?>

<div style="padding:20px;max-width:700px;margin:0 auto;">
    <h1 style="color:white;text-align:center;font-size:20px;font-weight:800;text-transform:uppercase;letter-spacing:2px;margin-bottom:24px;">
        Modifier le Compte
    </h1>

    <?php if (!empty($errors)): ?>
        <div class="flash-message flash-error" role="alert">
            <?php foreach ($errors as $e): ?><div><?= h($e) ?></div><?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div style="background:#222;border-radius:8px;padding:28px;margin-bottom:20px;">
        <!-- USER INFO -->
        <div style="margin-bottom:20px;padding:14px;background:#333;border-radius:6px;">
            <div style="color:#aaa;font-size:12px;text-transform:uppercase;margin-bottom:4px;">Email actuel</div>
            <div style="color:white;font-size:15px;font-weight:600;"><?= h($editUser['email']) ?></div>
            <div style="color:#888;font-size:12px;margin-top:6px;">
                Rôle : <span style="color:<?= $editUser['role']==='admin'||$editUser['role']==='superadmin' ? 'var(--orange)' : '#aaa' ?>;font-weight:600;text-transform:capitalize;"><?= h($editUser['role']) ?></span>
                &nbsp;·&nbsp;
                Inscrit le : <?= h(date('d/m/Y', strtotime($editUser['created_at']))) ?>
            </div>
        </div>

        <form method="POST" novalidate>
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="update_user" value="1">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
                <div class="form-group">
                    <label class="form-label" style="color:#aaa;">Nom *</label>
                    <input class="form-control" type="text" name="nom" value="<?= h($_POST['nom'] ?? $editUser['nom']) ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label" style="color:#aaa;">Prénom *</label>
                    <input class="form-control" type="text" name="prenom" value="<?= h($_POST['prenom'] ?? $editUser['prenom']) ?>" required>
                </div>
            </div>
            <div class="form-group" style="margin-bottom:14px;">
                <label class="form-label" style="color:#aaa;">Email *</label>
                <input class="form-control" type="email" name="email" value="<?= h($_POST['email'] ?? $editUser['email']) ?>" required>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
                <div class="form-group">
                    <label class="form-label" style="color:#aaa;">Ville</label>
                    <input class="form-control" type="text" name="ville" value="<?= h($_POST['ville'] ?? $editUser['ville']) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label" style="color:#aaa;">Code postal</label>
                    <input class="form-control" type="text" name="code_postal" value="<?= h($_POST['code_postal'] ?? $editUser['code_postal']) ?>">
                </div>
            </div>
            <div class="form-group" style="margin-bottom:14px;">
                <label class="form-label" style="color:#aaa;">Type de licence</label>
                <select class="form-control" name="type_license">
                    <option value="">— Aucune —</option>
                    <?php foreach ($licenseLabels as $val => $lbl): ?>
                        <option value="<?= h($val) ?>" <?= ($editUser['type_license']===$val)?'selected':'' ?>><?= h($lbl) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:20px;">
                <label class="form-label" style="color:#aaa;">Nouveau mot de passe <small style="color:#666;">(laisser vide pour ne pas changer)</small></label>
                <input class="form-control" type="password" name="new_password" autocomplete="new-password">
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <?php if (isSuperAdmin() && $userId !== (int)$_SESSION['user_id']): ?>
                    <a href="?id=<?= $userId ?>&promote=1"
                       class="btn-secondary"
                       style="text-decoration:none;"
                       onclick="return confirm('Changer le rôle de cet utilisateur ?')">
                        <?= $editUser['role']==='user' ? 'Promouvoir Admin' : 'Rétrograder Utilisateur' ?>
                    </a>
                    <a href="?id=<?= $userId ?>&delete_user=1"
                       class="btn-danger"
                       style="text-decoration:none;"
                       onclick="return confirm('Supprimer définitivement ce compte ?')">
                        Supprimer
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div style="text-align:center;">
        <a href="<?= SITE_URL ?>/admin/accounts" class="btn-secondary" style="text-decoration:none;">← Retour à la liste</a>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
