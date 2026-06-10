<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
trackVisit('staff-direction');

// Load staff
$staffList = db()->query('SELECT * FROM staff ORDER BY ordre ASC, id ASC')->fetchAll();

$pageTitle        = 'Staff & Direction';
$breadcrumbCenter = 'NOTRE CLUB';
$currentPage      = 'club';
require_once __DIR__ . '/../../partials/header.php';
?>

<div style="max-width:1100px;margin:0 auto;padding:40px 20px;">
    <h1 style="font-size:28px;font-weight:900;color:var(--bleu-fc);text-transform:uppercase;text-align:center;margin-bottom:10px;">Staff &amp; Direction</h1>
    <p style="text-align:center;color:#888;margin-bottom:40px;">L'équipe qui fait vivre le FC Grande-Synthe</p>

    <!-- DIRECTION -->
    <h2 style="font-size:18px;font-weight:800;color:var(--bleu-fc);text-transform:uppercase;border-left:4px solid var(--bleu-fc);padding-left:12px;margin-bottom:24px;">Direction</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:20px;margin-bottom:40px;">
        <?php
        $direction = [
            ['nom'=>'MAKHLOUFI', 'prenom'=>'Abdoullah', 'poste'=>'Président'],
            ['nom'=>'OMER',      'prenom'=>'Raché',      'poste'=>'Correspondant', 'tel'=>'08 22 09 62 71'],
            ['nom'=>'KENOU',     'prenom'=>'Brahim',     'poste'=>'Secrétaire Générale', 'tel'=>'08 86 40 84 26'],
            ['nom'=>'LOZANO',    'prenom'=>'Pierre',     'poste'=>'Réf. Projet Club'],
        ];
        foreach ($direction as $d):
        ?>
            <div style="background:var(--bleu-fc);color:white;border-radius:8px;padding:20px;text-align:center;">
                <div style="width:70px;height:70px;background:rgba(255,255,255,0.15);border-radius:50%;margin:0 auto 12px;display:flex;align-items:center;justify-content:center;">
                    <img src="<?= ASSETS_URL ?>/images/icons/staff.png" alt="" style="height:36px;filter:invert(1);">
                </div>
                <div style="font-size:15px;font-weight:700;"><?= h($d['prenom']) ?> <?= h($d['nom']) ?></div>
                <div style="font-size:12px;color:#a8c4f0;text-transform:uppercase;letter-spacing:0.5px;margin-top:4px;"><?= h($d['poste']) ?></div>
                <?php if (!empty($d['tel'])): ?>
                    <div style="font-size:12px;color:#c0d8f0;margin-top:6px;"><?= h($d['tel']) ?></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- STAFF DB -->
    <?php if (!empty($staffList)): ?>
        <h2 style="font-size:18px;font-weight:800;color:var(--bleu-fc);text-transform:uppercase;border-left:4px solid var(--bleu-fc);padding-left:12px;margin-bottom:24px;">Éducateurs &amp; Staff technique</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:20px;margin-bottom:40px;">
            <?php foreach ($staffList as $s): ?>
                <div style="border:1px solid #ddd;border-radius:8px;overflow:hidden;background:white;box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                    <?php if ($s['photo']): ?>
                        <img src="<?= UPLOAD_URL . h($s['photo']) ?>" alt="<?= h($s['prenom'].' '.$s['nom']) ?>"
                             style="width:100%;height:180px;object-fit:cover;">
                    <?php else: ?>
                        <div style="width:100%;height:180px;background:#f0f4ff;display:flex;align-items:center;justify-content:center;">
                            <img src="<?= ASSETS_URL ?>/images/icons/staff.png" alt="" style="height:60px;opacity:0.3;">
                        </div>
                    <?php endif; ?>
                    <div style="padding:14px;">
                        <div style="font-size:15px;font-weight:700;color:var(--bleu-fc);"><?= h($s['prenom']) ?> <?= h($s['nom']) ?></div>
                        <div style="font-size:12px;color:#888;text-transform:uppercase;letter-spacing:0.5px;margin-top:4px;"><?= h($s['poste']) ?></div>
                        <?php if ($s['bio']): ?>
                            <p style="font-size:12px;color:#555;margin-top:8px;line-height:1.5;"><?= h(mb_strimwidth($s['bio'],0,120,'…')) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (isAdmin()): ?>
        <div style="text-align:center;margin-top:10px;padding:16px;background:#f0f4ff;border-radius:8px;">
            <a href="<?= SITE_URL ?>/admin/staff" class="btn-primary" style="text-decoration:none;">⚙️ Gérer le staff (admin)</a>
        </div>
    <?php endif; ?>

    <div style="text-align:center;margin-top:30px;">
        <a href="<?= SITE_URL ?>/club" class="btn-secondary" style="text-decoration:none;">← Retour Club</a>
    </div>
</div>

<?php require_once __DIR__ . '/../../partials/footer.php'; ?>
