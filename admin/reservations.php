<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireAdmin();
trackVisit('admin/reservations');

// Change status
if (isset($_GET['statut']) && isset($_GET['id'])) {
    $statuts = ['confirme','refuse','annule','en_attente'];
    $newStat = in_array($_GET['statut'], $statuts) ? $_GET['statut'] : null;
    if ($newStat) {
        db()->prepare('UPDATE reservations SET statut=? WHERE id=?')->execute([$newStat,(int)$_GET['id']]);
        flashMessage('success', 'Statut mis à jour.');
        redirect(SITE_URL . '/admin/reservations');
    }
}

$filter = in_array($_GET['filter'] ?? '', ['en_attente','confirme','refuse','annule']) ? $_GET['filter'] : 'en_attente';
$stmt   = db()->prepare(
    'SELECT r.*, u.email, u.nom, u.prenom FROM reservations r
     LEFT JOIN users u ON u.id = r.user_id
     WHERE r.statut=? ORDER BY r.created_at DESC'
);
$stmt->execute([$filter]);
$reservations = $stmt->fetchAll();

$pageTitle        = 'Réservations';
$breadcrumbCenter = 'ACCÈS ADMIN';
$currentPage      = '';
require_once __DIR__ . '/../partials/header.php';
?>

<div style="padding:20px;max-width:1000px;margin:0 auto;">
    <h1 style="color:white;text-align:center;font-size:20px;font-weight:800;text-transform:uppercase;letter-spacing:2px;margin-bottom:20px;">
        Gestion des Réservations
    </h1>

    <div style="display:flex;gap:10px;margin-bottom:20px;justify-content:center;flex-wrap:wrap;">
        <?php foreach (['en_attente'=>'En attente','confirme'=>'Confirmées','refuse'=>'Refusées','annule'=>'Annulées'] as $val=>$label): ?>
            <a href="?filter=<?= $val ?>"
               style="padding:8px 18px;border-radius:4px;font-size:13px;font-weight:600;text-decoration:none;
                      background:<?= $filter===$val ? '#8B1A1A' : '#333' ?>;color:white;">
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if (empty($reservations)): ?>
        <p style="color:#888;text-align:center;padding:30px;">Aucune réservation dans cette catégorie.</p>
    <?php else: ?>
        <div style="display:flex;flex-direction:column;gap:12px;">
            <?php foreach ($reservations as $r): ?>
                <div style="background:#222;border-radius:6px;padding:18px;">
                    <div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:10px;">
                        <div>
                            <strong style="color:white;">
                                <?= $r['nom'] ? h($r['nom'].' '.$r['prenom']) : 'Visiteur non connecté' ?>
                            </strong>
                            <?php if ($r['email']): ?>
                                <span style="color:#888;font-size:13px;margin-left:8px;"><?= h($r['email']) ?></span>
                            <?php endif; ?>
                        </div>
                        <span style="color:#888;font-size:12px;"><?= h(date('d/m/Y H:i', strtotime($r['created_at']))) ?></span>
                    </div>
                    <div style="color:#ccc;font-size:13px;margin-bottom:8px;">
                        <strong style="color:#aaa;">Du</strong> <?= h($r['date_debut']) ?>
                        <strong style="color:#aaa;margin:0 4px;">au</strong> <?= h($r['date_fin']) ?>
                        <strong style="color:#aaa;margin-left:8px;">à</strong> <?= h(substr($r['heure'],0,5)) ?>
                    </div>
                    <p style="color:#bbb;font-size:14px;line-height:1.5;margin-bottom:12px;"><?= h($r['contenu']) ?></p>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        <?php if ($r['statut'] === 'en_attente'): ?>
                            <a href="?id=<?= h($r['id']) ?>&statut=confirme&filter=<?= $filter ?>" style="background:var(--vert);color:white;padding:6px 14px;border-radius:3px;font-size:12px;font-weight:700;text-decoration:none;">✓ Confirmer</a>
                            <a href="?id=<?= h($r['id']) ?>&statut=refuse&filter=<?= $filter ?>" style="background:var(--rouge-vif);color:white;padding:6px 14px;border-radius:3px;font-size:12px;font-weight:700;text-decoration:none;">✕ Refuser</a>
                        <?php elseif ($r['statut'] === 'confirme'): ?>
                            <a href="?id=<?= h($r['id']) ?>&statut=annule&filter=<?= $filter ?>" style="background:#888;color:white;padding:6px 14px;border-radius:3px;font-size:12px;font-weight:700;text-decoration:none;">Annuler</a>
                        <?php endif; ?>
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
