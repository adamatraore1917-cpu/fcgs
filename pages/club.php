<?php
require_once __DIR__ . '/../includes/bootstrap.php';
trackVisit('club');

// Load gallery photos
$stmt = db()->prepare('SELECT * FROM galerie ORDER BY slot_number');
$stmt->execute();
$photos = $stmt->fetchAll();

$pageTitle        = 'Notre Club';
$breadcrumbCenter = 'NOTRE CLUB';
$currentPage      = 'club';
require_once __DIR__ . '/../partials/header.php';
?>

<section class="gallery-grid" aria-label="Galerie photos du club">
    <?php foreach ($photos as $photo): ?>
        <?php if (!empty($photo['filename'])): ?>
            <div class="gallery-item">
                <img
                    src="<?= UPLOAD_URL . h($photo['filename']) ?>"
                    alt="<?= h($photo['alt_text'] ?: 'Photo FC Grande-Synthe') ?>"
                    loading="lazy"
                >
            </div>
        <?php else: ?>
            <div class="gallery-item" style="background:#0a0a0a;display:flex;align-items:center;justify-content:center;">
                <span style="color:#333;font-size:40px;">+</span>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</section>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
