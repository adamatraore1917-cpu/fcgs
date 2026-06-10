<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireAdmin();
trackVisit('admin/gallery');

// Upload photo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_gallery'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        flashMessage('error', 'Token invalide.');
    } else {
        $slot = (int)$_POST['slot_number'];
        if ($slot >= 1 && $slot <= 8 && isset($_FILES['gallery_file']) && $_FILES['gallery_file']['error'] === UPLOAD_ERR_OK) {
            $ext     = strtolower(pathinfo($_FILES['gallery_file']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','gif','webp'];
            if (in_array($ext, $allowed)) {
                // Delete old file
                $old = db()->prepare('SELECT filename FROM galerie WHERE slot_number=?');
                $old->execute([$slot]);
                $oldFile = $old->fetchColumn();
                if ($oldFile && file_exists(UPLOAD_DIR . $oldFile)) {
                    unlink(UPLOAD_DIR . $oldFile);
                }
                $filename = 'gallery_' . $slot . '_' . uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['gallery_file']['tmp_name'], UPLOAD_DIR . $filename);
                db()->prepare('UPDATE galerie SET filename=?,alt_text=? WHERE slot_number=?')
                   ->execute([$filename, 'Photo ' . sprintf('%02d', $slot) . ' FC Grande-Synthe', $slot]);
                flashMessage('success', 'Photo ' . $slot . ' mise à jour.');
            } else {
                flashMessage('error', 'Format non supporté.');
            }
        }
    }
    redirect(SITE_URL . '/admin/gallery');
}

// Delete photo
if (isset($_GET['delete_slot'])) {
    $slot = (int)$_GET['delete_slot'];
    $old  = db()->prepare('SELECT filename FROM galerie WHERE slot_number=?');
    $old->execute([$slot]);
    $oldFile = $old->fetchColumn();
    if ($oldFile && file_exists(UPLOAD_DIR . $oldFile)) unlink(UPLOAD_DIR . $oldFile);
    db()->prepare('UPDATE galerie SET filename=NULL, alt_text=NULL WHERE slot_number=?')->execute([$slot]);
    flashMessage('success', 'Photo supprimée.');
    redirect(SITE_URL . '/admin/gallery');
}

$stmt = db()->prepare('SELECT * FROM galerie ORDER BY slot_number');
$stmt->execute();
$photos = $stmt->fetchAll();

$pageTitle        = 'Modifier la Galerie';
$breadcrumbCenter = 'ACCÈS ADMIN';
$currentPage      = '';
require_once __DIR__ . '/../partials/header.php';
?>

<!-- Upload form (hidden, triggered by slot click) -->
<form id="gallery-upload-form" method="POST" enctype="multipart/form-data" style="display:none;">
    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
    <input type="hidden" name="upload_gallery" value="1">
    <input type="hidden" name="slot_number" id="gallery-slot-number">
    <input type="file" name="gallery_file" id="gallery-file" accept="image/*">
</form>

<div style="padding:20px;">
    <h1 style="color:white;text-align:center;font-size:20px;font-weight:800;text-transform:uppercase;letter-spacing:2px;margin-bottom:24px;">
        Modifier les Photos de la Galerie
    </h1>

    <div class="admin-gallery-grid">
        <?php foreach ($photos as $photo): ?>
            <div class="admin-gallery-slot" data-slot="<?= h($photo['slot_number']) ?>" title="Photo <?= h(sprintf('%02d', $photo['slot_number'])) ?>">
                <?php if (!empty($photo['filename'])): ?>
                    <img
                        src="<?= UPLOAD_URL . h($photo['filename']) ?>"
                        alt="Photo <?= h($photo['slot_number']) ?>"
                        class="slot-preview"
                        loading="lazy"
                    >
                    <div style="position:absolute;top:4px;right:4px;">
                        <a
                            href="?delete_slot=<?= h($photo['slot_number']) ?>"
                            onclick="return confirm('Supprimer cette photo ?')"
                            style="background:rgba(200,0,0,0.8);color:white;padding:2px 6px;font-size:11px;border-radius:2px;text-decoration:none;"
                            aria-label="Supprimer la photo <?= h($photo['slot_number']) ?>"
                        >✕</a>
                    </div>
                <?php else: ?>
                    <span class="slot-add-icon" aria-hidden="true">+</span>
                <?php endif; ?>
                <span class="slot-name">Photo<?= sprintf('%02d', $photo['slot_number']) ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <p style="text-align:center;color:#888;font-size:12px;margin-top:16px;">
        Cliquez sur un emplacement pour ajouter ou remplacer une photo.
    </p>

    <div style="text-align:center;margin-top:20px;">
        <a href="<?= SITE_URL ?>/admin-acces" class="btn-secondary" style="text-decoration:none;">← Retour Admin</a>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
