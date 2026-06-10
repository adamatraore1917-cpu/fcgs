<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireAdmin();

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];
    if ($action === 'publier')  db()->prepare('UPDATE avis SET statut="publie"  WHERE id=?')->execute([$id]);
    if ($action === 'refuser')  db()->prepare('UPDATE avis SET statut="refuse"  WHERE id=?')->execute([$id]);
    if ($action === 'supprimer') db()->prepare('DELETE FROM avis WHERE id=?')->execute([$id]);
    flashMessage('success','Avis mis à jour.');
    redirect(SITE_URL . '/admin/avis');
}

$filter = in_array($_GET['filter'] ?? '', ['en_attente','publie','refuse']) ? $_GET['filter'] : 'en_attente';
$stmt   = db()->prepare('SELECT * FROM avis WHERE statut=? ORDER BY created_at DESC');
$stmt->execute([$filter]);
$avisList = $stmt->fetchAll();

$pageTitle = 'Modération des Avis'; $breadcrumbCenter = 'ACCÈS ADMIN'; $currentPage = '';
require_once __DIR__ . '/../partials/header.php';
?>
<div style="padding:20px;max-width:900px;margin:0 auto;">
    <h1 style="color:white;text-align:center;font-size:20px;font-weight:800;text-transform:uppercase;letter-spacing:2px;margin-bottom:20px;">
        Modération des Avis
    </h1>

    <div style="display:flex;gap:10px;margin-bottom:20px;justify-content:center;">
        <?php foreach(['en_attente'=>'En attente','publie'=>'Publiés','refuse'=>'Refusés'] as $v=>$l): ?>
            <a href="?filter=<?= $v ?>" style="padding:8px 18px;border-radius:4px;font-size:13px;font-weight:600;text-decoration:none;background:<?= $filter===$v?'var(--bleu-fc)':'#333' ?>;color:white;"><?= $l ?></a>
        <?php endforeach; ?>
    </div>

    <?php if (empty($avisList)): ?>
        <p style="color:#888;text-align:center;padding:30px;">Aucun avis dans cette catégorie.</p>
    <?php else: ?>
        <div style="display:flex;flex-direction:column;gap:12px;">
            <?php foreach ($avisList as $a): ?>
                <div style="background:#222;border-radius:8px;padding:18px;">
                    <div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:8px;margin-bottom:8px;">
                        <div>
                            <strong style="color:white;"><?= h($a['nom']) ?></strong>
                            <span style="color:#f0c030;margin-left:8px;"><?= str_repeat('★',$a['note']).str_repeat('☆',5-$a['note']) ?></span>
                            <?php if ($a['email']): ?><span style="color:#888;font-size:12px;margin-left:8px;"><?= h($a['email']) ?></span><?php endif; ?>
                        </div>
                        <span style="color:#888;font-size:12px;"><?= h(date('d/m/Y H:i',strtotime($a['created_at']))) ?></span>
                    </div>
                    <p style="color:#ccc;font-size:14px;line-height:1.5;margin-bottom:12px;"><?= h($a['commentaire']) ?></p>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        <?php if ($a['statut']==='en_attente'): ?>
                            <a href="?action=publier&id=<?= h($a['id']) ?>&filter=<?= $filter ?>" style="background:#2ecc71;color:white;padding:5px 14px;border-radius:3px;font-size:12px;font-weight:700;text-decoration:none;">✓ Publier</a>
                            <a href="?action=refuser&id=<?= h($a['id']) ?>&filter=<?= $filter ?>" style="background:#e74c3c;color:white;padding:5px 14px;border-radius:3px;font-size:12px;font-weight:700;text-decoration:none;">✕ Refuser</a>
                        <?php endif; ?>
                        <a href="?action=supprimer&id=<?= h($a['id']) ?>&filter=<?= $filter ?>" onclick="return confirm('Supprimer définitivement ?')" style="background:#555;color:white;padding:5px 14px;border-radius:3px;font-size:12px;font-weight:700;text-decoration:none;">🗑 Supprimer</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div style="text-align:center;margin-top:24px;">
        <a href="<?= SITE_URL ?>/admin-acces" class="btn-secondary" style="text-decoration:none;">← Retour Admin</a>
    </div>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
