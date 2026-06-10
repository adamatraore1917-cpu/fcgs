<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireAdmin();

// Add staff member
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_staff'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) { flashMessage('error','Token invalide.'); }
    else {
        $nom    = trim($_POST['nom']    ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $poste  = trim($_POST['poste']  ?? '');
        $bio    = trim($_POST['bio']    ?? '');
        $ordre  = (int)($_POST['ordre'] ?? 0);
        $photo  = null;

        if ($nom && $prenom && $poste) {
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                    $filename = 'staff_' . uniqid() . '.' . $ext;
                    move_uploaded_file($_FILES['photo']['tmp_name'], UPLOAD_DIR . $filename);
                    $photo = $filename;
                }
            }
            db()->prepare('INSERT INTO staff (nom,prenom,poste,bio,photo,ordre) VALUES (?,?,?,?,?,?)')
               ->execute([$nom,$prenom,$poste,$bio,$photo,$ordre]);
            flashMessage('success','Membre du staff ajouté.');
        } else {
            flashMessage('error','Nom, prénom et poste requis.');
        }
        redirect(SITE_URL . '/admin/staff');
    }
}

// Delete staff member
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $row = db()->prepare('SELECT photo FROM staff WHERE id=?');
    $row->execute([$id]);
    $old = $row->fetchColumn();
    if ($old && file_exists(UPLOAD_DIR . $old)) unlink(UPLOAD_DIR . $old);
    db()->prepare('DELETE FROM staff WHERE id=?')->execute([$id]);
    flashMessage('success','Membre supprimé.');
    redirect(SITE_URL . '/admin/staff');
}

$staffList = db()->query('SELECT * FROM staff ORDER BY ordre ASC, id ASC')->fetchAll();

$pageTitle = 'Gérer le Staff'; $breadcrumbCenter = 'ACCÈS ADMIN'; $currentPage = '';
require_once __DIR__ . '/../partials/header.php';
?>
<div style="padding:20px;max-width:900px;margin:0 auto;">
    <h1 style="color:white;text-align:center;font-size:20px;font-weight:800;text-transform:uppercase;letter-spacing:2px;margin-bottom:24px;">
        Staff &amp; Direction
    </h1>

    <!-- ADD FORM -->
    <div style="background:#222;border-radius:8px;padding:24px;margin-bottom:24px;">
        <h2 style="color:#ccc;font-size:15px;font-weight:700;text-transform:uppercase;margin-bottom:16px;">Ajouter un membre</h2>
        <form method="POST" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="add_staff" value="1">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:12px;">
                <div>
                    <label style="color:#aaa;font-size:12px;display:block;margin-bottom:4px;">Prénom *</label>
                    <input type="text" name="prenom" class="form-control" required>
                </div>
                <div>
                    <label style="color:#aaa;font-size:12px;display:block;margin-bottom:4px;">Nom *</label>
                    <input type="text" name="nom" class="form-control" required>
                </div>
                <div>
                    <label style="color:#aaa;font-size:12px;display:block;margin-bottom:4px;">Poste *</label>
                    <input type="text" name="poste" class="form-control" required placeholder="Éducateur U13…">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:2fr 1fr;gap:12px;margin-bottom:12px;">
                <div>
                    <label style="color:#aaa;font-size:12px;display:block;margin-bottom:4px;">Bio (optionnel)</label>
                    <textarea name="bio" class="form-control" rows="2"></textarea>
                </div>
                <div>
                    <label style="color:#aaa;font-size:12px;display:block;margin-bottom:4px;">Ordre d'affichage</label>
                    <input type="number" name="ordre" class="form-control" value="0" min="0">
                </div>
            </div>
            <div style="margin-bottom:16px;">
                <label style="color:#aaa;font-size:12px;display:block;margin-bottom:4px;">Photo (jpg, png, webp)</label>
                <input type="file" name="photo" accept="image/*" class="form-control" style="background:#333;color:#ccc;border-color:#555;">
            </div>
            <button type="submit" class="btn-primary">Ajouter</button>
        </form>
    </div>

    <!-- LIST -->
    <div style="background:#1a1a1a;border-radius:8px;overflow:hidden;">
        <div style="background:#2a2a2a;padding:14px 20px;">
            <h2 style="color:white;font-size:15px;font-weight:700;text-transform:uppercase;">Membres (<?= count($staffList) ?>)</h2>
        </div>
        <?php if (empty($staffList)): ?>
            <p style="color:#888;text-align:center;padding:30px;">Aucun membre ajouté.</p>
        <?php else: ?>
            <table class="admin-table">
                <thead><tr><th>Photo</th><th>Nom</th><th>Poste</th><th>Ordre</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach ($staffList as $s): ?>
                    <tr>
                        <td>
                            <?php if ($s['photo']): ?>
                                <img src="<?= UPLOAD_URL.h($s['photo']) ?>" style="height:48px;width:48px;object-fit:cover;border-radius:50%;" alt="">
                            <?php else: ?>
                                <span style="color:#555;">—</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-weight:600;"><?= h($s['prenom'].' '.$s['nom']) ?></td>
                        <td><?= h($s['poste']) ?></td>
                        <td><?= h($s['ordre']) ?></td>
                        <td>
                            <a href="?delete=<?= h($s['id']) ?>" onclick="return confirm('Supprimer ?')"
                               style="background:var(--rouge-vif);color:white;padding:4px 12px;border-radius:3px;font-size:12px;text-decoration:none;font-weight:700;">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div style="text-align:center;margin-top:20px;">
        <a href="<?= SITE_URL ?>/admin-acces" class="btn-secondary" style="text-decoration:none;">← Retour Admin</a>
        <a href="<?= SITE_URL ?>/staff-direction" target="_blank" class="btn-secondary" style="text-decoration:none;margin-left:10px;">👁 Voir la page</a>
    </div>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
