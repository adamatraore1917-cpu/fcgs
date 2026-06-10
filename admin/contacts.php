<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireAdmin();
trackVisit('admin/contacts');

// Marquer traité
if (isset($_GET['traiter'])) {
    db()->prepare('UPDATE contacts SET statut="traite" WHERE id=?')->execute([(int)$_GET['traiter']]);
    flashMessage('success', 'Contact marqué comme traité.');
    redirect(SITE_URL . '/admin/contacts');
}

// Archiver
if (isset($_GET['archiver'])) {
    db()->prepare('UPDATE contacts SET statut="archive" WHERE id=?')->execute([(int)$_GET['archiver']]);
    flashMessage('success', 'Contact archivé.');
    redirect(SITE_URL . '/admin/contacts');
}

$filter  = in_array($_GET['filter'] ?? '', ['en_attente','traite','archive']) ? $_GET['filter'] : 'en_attente';
$stmt    = db()->prepare('SELECT * FROM contacts WHERE statut=? ORDER BY created_at DESC');
$stmt->execute([$filter]);
$contacts = $stmt->fetchAll();

$pageTitle        = 'Demandes de Contact';
$breadcrumbCenter = 'ACCÈS ADMIN';
$currentPage      = '';
require_once __DIR__ . '/../partials/header.php';
?>

<div style="padding:20px;max-width:1000px;margin:0 auto;">
    <h1 style="color:white;text-align:center;font-size:20px;font-weight:800;text-transform:uppercase;letter-spacing:2px;margin-bottom:20px;">
        Demandes de Contacts
    </h1>

    <!-- FILTRES -->
    <div style="display:flex;gap:10px;margin-bottom:20px;justify-content:center;">
        <?php foreach (['en_attente'=>'En attente','traite'=>'Traités','archive'=>'Archivés'] as $val=>$label): ?>
            <a href="?filter=<?= $val ?>"
               style="padding:8px 18px;border-radius:4px;font-size:13px;font-weight:600;text-decoration:none;
                      background:<?= $filter===$val ? 'var(--bleu-fc)' : '#333' ?>;
                      color:<?= $filter===$val ? 'white' : '#ccc' ?>;">
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if (empty($contacts)): ?>
        <p style="color:#888;text-align:center;padding:30px;">Aucune demande dans cette catégorie.</p>
    <?php else: ?>
        <div style="display:flex;flex-direction:column;gap:12px;">
            <?php foreach ($contacts as $c): ?>
                <div style="background:#222;border-radius:6px;padding:18px;border-left:4px solid <?= $c['type_contact']==='professionnel' ? 'var(--orange)' : 'var(--bleu-med)' ?>;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px;">
                        <div>
                            <strong style="color:white;font-size:15px;"><?= h($c['nom']) ?> <?= h($c['prenom']) ?></strong>
                            <span style="background:<?= $c['type_contact']==='professionnel' ? 'var(--orange)' : '#3a5da0' ?>;color:white;padding:2px 8px;border-radius:3px;font-size:11px;margin-left:8px;text-transform:uppercase;"><?= h($c['type_contact']) ?></span>
                        </div>
                        <span style="color:#888;font-size:12px;"><?= h(date('d/m/Y H:i', strtotime($c['created_at']))) ?></span>
                    </div>
                    <div style="color:#aaa;font-size:13px;margin-bottom:6px;">
                        <a href="mailto:<?= h($c['email']) ?>" style="color:var(--bleu-med);"><?= h($c['email']) ?></a>
                        <?php if ($c['telephone']): ?> · <?= h($c['telephone']) ?><?php endif; ?>
                    </div>
                    <p style="color:#ccc;font-size:14px;line-height:1.5;margin-bottom:12px;"><?= h($c['message']) ?></p>
                    <div style="display:flex;gap:8px;">
                        <?php if ($c['statut'] === 'en_attente'): ?>
                            <a href="?traiter=<?= h($c['id']) ?>&filter=<?= $filter ?>" style="background:var(--vert);color:white;padding:6px 14px;border-radius:3px;font-size:12px;font-weight:700;text-decoration:none;">✓ Marquer traité</a>
                        <?php endif; ?>
                        <?php if ($c['statut'] !== 'archive'): ?>
                            <a href="?archiver=<?= h($c['id']) ?>&filter=<?= $filter ?>" style="background:#555;color:white;padding:6px 14px;border-radius:3px;font-size:12px;font-weight:700;text-decoration:none;">Archiver</a>
                        <?php endif; ?>
                        <a href="mailto:<?= h($c['email']) ?>" style="background:var(--bleu-fc);color:white;padding:6px 14px;border-radius:3px;font-size:12px;font-weight:700;text-decoration:none;">Répondre</a>
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
