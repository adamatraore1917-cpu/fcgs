<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
trackVisit('partenaires');

$partenaires = db()->query('SELECT * FROM partenaires WHERE actif=1 ORDER BY ordre ASC, id ASC')->fetchAll();

$pageTitle        = 'Nos Partenaires';
$breadcrumbCenter = 'NOS PARTENAIRES';
$currentPage      = 'club';
require_once __DIR__ . '/../../partials/header.php';
?>

<div style="max-width:1100px;margin:0 auto;padding:40px 20px;">

    <h1 style="font-size:28px;font-weight:900;color:var(--bleu-fc);text-transform:uppercase;text-align:center;margin-bottom:10px;">
        Nos Partenaires
    </h1>
    <p style="text-align:center;color:#888;margin-bottom:40px;">
        Ils soutiennent le FC Grande-Synthe et font vivre le football local.
    </p>

    <?php if (!empty($partenaires)): ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:24px;margin-bottom:50px;">
            <?php foreach ($partenaires as $p): ?>
                <div style="border:1px solid #ddd;border-radius:10px;overflow:hidden;background:white;box-shadow:0 2px 12px rgba(0,0,0,0.07);transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform=''">
                    <div style="height:160px;background:#f8f9ff;display:flex;align-items:center;justify-content:center;padding:20px;">
                        <?php if ($p['logo']): ?>
                            <img src="<?= UPLOAD_URL . h($p['logo']) ?>" alt="<?= h($p['nom']) ?>"
                                 style="max-height:120px;max-width:100%;object-fit:contain;">
                        <?php else: ?>
                            <div style="font-size:22px;font-weight:800;color:var(--bleu-fc);text-align:center;"><?= h($p['nom']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div style="padding:18px;">
                        <h3 style="font-size:16px;font-weight:700;color:var(--bleu-fc);margin-bottom:8px;"><?= h($p['nom']) ?></h3>
                        <?php if ($p['description']): ?>
                            <p style="font-size:13px;color:#555;line-height:1.5;margin-bottom:10px;"><?= h($p['description']) ?></p>
                        <?php endif; ?>
                        <?php if ($p['site_web']): ?>
                            <a href="<?= h($p['site_web']) ?>" target="_blank" rel="noopener"
                               style="font-size:12px;color:var(--bleu-fc);text-decoration:underline;">
                                🌐 Visiter le site
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div style="text-align:center;padding:60px 20px;color:#aaa;">
            <p style="font-size:18px;margin-bottom:16px;">Aucun partenaire affiché pour le moment.</p>
            <p style="font-size:14px;">Vous souhaitez soutenir le club ?</p>
        </div>
    <?php endif; ?>

    <!-- DEVENIR PARTENAIRE CTA -->
    <div style="background:var(--bleu-fc);border-radius:12px;padding:36px;text-align:center;color:white;">
        <h2 style="font-size:22px;font-weight:800;text-transform:uppercase;margin-bottom:12px;">Devenez Partenaire</h2>
        <p style="color:#b0c8f0;font-size:14px;max-width:500px;margin:0 auto 20px;line-height:1.6;">
            Rejoignez les partenaires du FC Grande-Synthe et associez votre image à un projet sportif et éducatif engagé.
        </p>
        <a href="<?= SITE_URL ?>/devenir-partenaire" class="btn-danger" style="display:inline-block;text-decoration:none;font-size:15px;padding:14px 32px;">
            Nous contacter
        </a>
    </div>

    <div style="text-align:center;margin-top:30px;">
        <a href="<?= SITE_URL ?>/club" class="btn-secondary" style="text-decoration:none;">← Retour Club</a>
    </div>
</div>

<?php require_once __DIR__ . '/../../partials/footer.php'; ?>
