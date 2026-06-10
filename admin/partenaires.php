<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_partenaire'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) { flashMessage('error','Token invalide.'); }
    else {
        $nom  = trim($_POST['nom'] ?? '');
        $site = trim($_POST['site_web'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        $logo = null;
        if ($nom) {
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg','jpeg','png','webp','svg','gif'])) {
                    $filename = 'partner_' . uniqid() . '.' . $ext;
                    move_uploaded_file($_FILES['logo']['tmp_name'], UPLOAD_DIR . $filename);
                    $logo = $filename;
                }
            }
            db()->prepare('INSERT INTO partenaires (nom,logo,site_web,description) VALUES (?,?,?,?)')
               ->execute([$nom,$logo,$site,$desc]);
            flashMessage('success','Partenaire ajouté.');
        }
        redirect(SITE_URL . '/admin/partenaires');
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $row = db()->prepare('SELECT logo FROM partenaires WHERE id=?');
    $row->execute([$id]);
    $old = $row->fetchColumn();
    if ($old && file_exists(UPLOAD_DIR . $old)) unlink(UPLOAD_DIR . $old);
    db()->prepare('DELETE FROM partenaires WHERE id=?')->execute([$id]);
    flashMessage('success','Partenaire supprimé.');
    redirect(SITE_URL . '/admin/partenaires');
}

if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    db()->prepare('UPDATE partenaires SET actif = 1 - actif WHERE id=?')->execute([$id]);
    redirect(SITE_URL . '/admin/partenaires');
}

$liste = db()->query('SELECT * FROM partenaires ORDER BY ordre ASC, id ASC')->fetchAll();

$pageTitle = 'Gérer les Partenaires'; $breadcrumbCenter = 'ACCÈS ADMIN'; $currentPage = '';
require_once __DIR__ . '/../partials/header.php';
?>
<div style="padding:20px;max-width:900px;margin:0 auto;">
    <h1 style="color:white;text-align:center;font-size:20px;font-weight:800;text-transform:uppercase;letter-spacing:2px;margin-bottom:24px;">
        Gérer les Partenaires
    </h1>

    <div style="background:#222;border-radius:8px;padding:24px;margin-bottom:24px;">
        <h2 style="color:#ccc;font-size:15px;font-weight:700;text-transform:uppercase;margin-bottom:16px;">Ajouter un partenaire</h2>
        <form method="POST" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="add_partenaire" value="1">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                <div>
                    <label style="color:#aaa;font-size:12px;display:block;margin-bottom:4px;">Nom *</label>
                    <input type="text" name="nom" class="form-control" required>
                </div>
                <div>
                    <label style="color:#aaa;font-size:12px;display:block;margin-bottom:4px;">Site web</label>
                    <input type="url" name="site_web" class="form-control" placeholder="https://…">
                </div>
            </div>
            <div style="margin-bottom:12px;">
                <label style="color:#aaa;font-size:12px;display:block;margin-bottom:4px;">Description</label>
                <textarea name="description" class="form-control" rows="2"></textarea>
            </div>
            <div style="margin-bottom:16px;">
                <label style="color:#aaa;font-size:12px;display:block;margin-bottom:4px;">Logo (jpg, png, svg)</label>
                <input type="file" name="logo" accept="image/*" class="form-control" style="background:#333;color:#ccc;border-color:#555;">
            </div>
            <button type="submit" class="btn-primary">Ajouter</button>
        </form>
    </div>

    <div style="background:#1a1a1a;border-radius:8px;overflow:hidden;">
        <div style="background:#2a2a2a;padding:14px 20px;">
            <h2 style="color:white;font-size:15px;font-weight:700;text-transform:uppercase;">Partenaires (<?= count($liste) ?>)</h2>
        </div>
        <?php if (empty($liste)): ?>
            <p style="color:#888;text-align:center;padding:30px;">Aucun partenaire.</p>
        <?php else: ?>
            <table class="admin-table">
                <thead><tr><th>Logo</th><th>Nom</th><th>Site</th><th>Actif</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach ($liste as $p): ?>
                    <tr>
                        <td><?php if ($p['logo']): ?><img src="<?= UPLOAD_URL.h($p['logo']) ?>" style="height:40px;max-width:80px;object-fit:contain;" alt=""><?php else: ?>—<?php endif; ?></td>
                        <td style="font-weight:600;"><?= h($p['nom']) ?></td>
                        <td><?php if ($p['site_web']): ?><a href="<?= h($p['site_web']) ?>" target="_blank" style="color:var(--bleu-med);">🔗</a><?php else: ?>—<?php endif; ?></td>
                        <td>
                            <a href="?toggle=<?= h($p['id']) ?>" style="background:<?= $p['actif']?'#2ecc71':'#888' ?>;color:white;padding:3px 10px;border-radius:3px;font-size:11px;text-decoration:none;font-weight:700;">
                                <?= $p['actif']?'Visible':'Masqué' ?>
                            </a>
                        </td>
                        <td>
                            <a href="?delete=<?= h($p['id']) ?>" onclick="return confirm('Supprimer ?')"
                               style="background:var(--rouge-vif);color:white;padding:3px 10px;border-radius:3px;font-size:11px;text-decoration:none;font-weight:700;">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div style="text-align:center;margin-top:20px;">
        <a href="<?= SITE_URL ?>/admin-acces" class="btn-secondary" style="text-decoration:none;">← Retour Admin</a>
        <a href="<?= SITE_URL ?>/partenaires" target="_blank" class="btn-secondary" style="text-decoration:none;margin-left:10px;">👁 Voir la page</a>
    </div>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
