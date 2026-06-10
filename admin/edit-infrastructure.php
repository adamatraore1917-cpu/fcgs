<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireAdmin();
trackVisit('admin/edit-infrastructure');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_infra'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        flashMessage('error', 'Token invalide.');
    } else {
        $fields = [
            'infra_titre','infra_sous_titre',
            'infra_feature_1','infra_feature_2','infra_feature_3','infra_feature_4','infra_feature_5',
            'infra_horaire_mf','infra_horaire_sam','infra_jours_feries'
        ];
        foreach ($fields as $f) {
            if (isset($_POST[$f])) setContent($f, trim($_POST[$f]));
        }
        flashMessage('success', 'Page Infrastructure mise à jour !');
        redirect(SITE_URL . '/admin/edit-infrastructure');
    }
}

$pageTitle        = 'Modifier Infrastructure';
$breadcrumbCenter = 'ACCÈS ADMIN';
$currentPage      = '';
require_once __DIR__ . '/../partials/header.php';
?>

<div style="padding:20px;max-width:900px;margin:0 auto;">
    <h1 style="color:white;text-align:center;font-size:20px;font-weight:800;text-transform:uppercase;letter-spacing:2px;margin-bottom:24px;">
        Modifier la Page Infrastructure
    </h1>

    <form method="POST" novalidate>
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
        <input type="hidden" name="save_infra" value="1">

        <!-- TITRE & SOUS-TITRE -->
        <div style="background:#222;border-radius:8px;padding:24px;margin-bottom:20px;">
            <h2 style="color:#f0c030;font-size:14px;font-weight:700;text-transform:uppercase;margin-bottom:16px;">🏆 Titre & Présentation</h2>
            <div class="form-group" style="margin-bottom:14px;">
                <label class="form-label" style="color:#aaa;">Titre principal</label>
                <input class="form-control" type="text" name="infra_titre" value="<?= h(getContent('infra_titre')) ?>">
            </div>
            <div class="form-group">
                <label class="form-label" style="color:#aaa;">Sous-titre / Description</label>
                <textarea class="form-control" name="infra_sous_titre" rows="3"><?= h(getContent('infra_sous_titre')) ?></textarea>
            </div>
        </div>

        <!-- ÉQUIPEMENTS -->
        <div style="background:#222;border-radius:8px;padding:24px;margin-bottom:20px;">
            <h2 style="color:#f0c030;font-size:14px;font-weight:700;text-transform:uppercase;margin-bottom:16px;">🎮 Équipements</h2>
            <?php
            $featureLabels = [
                'infra_feature_1' => '🎮 PlayStation 5',
                'infra_feature_2' => '🖥️ Écran 4K',
                'infra_feature_3' => '🎧 Casque gaming',
                'infra_feature_4' => '🪑 Siège gaming',
                'infra_feature_5' => '📶 Connexion fibre',
            ];
            foreach ($featureLabels as $key => $label): ?>
                <div class="form-group" style="margin-bottom:12px;">
                    <label class="form-label" style="color:#aaa;"><?= $label ?></label>
                    <input class="form-control" type="text" name="<?= $key ?>" value="<?= h(getContent($key)) ?>">
                </div>
            <?php endforeach; ?>
        </div>

        <!-- HORAIRES -->
        <div style="background:#222;border-radius:8px;padding:24px;margin-bottom:20px;">
            <h2 style="color:#f0c030;font-size:14px;font-weight:700;text-transform:uppercase;margin-bottom:16px;">🕐 Horaires d'accès</h2>
            <p style="color:#888;font-size:12px;margin-bottom:14px;">Format : <code style="color:#aaa;">17h - 21h</code> (séparer par un tiret)</p>
            <div class="form-group" style="margin-bottom:14px;">
                <label class="form-label" style="color:#aaa;">Mercredi &amp; Vendredi</label>
                <input class="form-control" type="text" name="infra_horaire_mf" value="<?= h(getContent('infra_horaire_mf')) ?>" placeholder="17h - 21h">
            </div>
            <div class="form-group" style="margin-bottom:14px;">
                <label class="form-label" style="color:#aaa;">Samedi</label>
                <input class="form-control" type="text" name="infra_horaire_sam" value="<?= h(getContent('infra_horaire_sam')) ?>" placeholder="14h - 18h">
            </div>
            <div class="form-group">
                <label class="form-label" style="color:#aaa;">Jours fériés (une ligne par jour)</label>
                <textarea class="form-control" name="infra_jours_feries" rows="5"><?= h(getContent('infra_jours_feries')) ?></textarea>
            </div>
        </div>

        <div style="display:flex;gap:12px;justify-content:center;padding-bottom:30px;">
            <button type="submit" class="btn-primary" style="font-size:15px;padding:14px 36px;">
                ✅ Actualiser la page
            </button>
            <a href="<?= SITE_URL ?>/esport/infrastructure" target="_blank" class="btn-secondary" style="text-decoration:none;padding:14px 24px;">
                👁 Voir la page
            </a>
            <a href="<?= SITE_URL ?>/admin-acces" class="btn-secondary" style="text-decoration:none;padding:14px 24px;">← Admin</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
