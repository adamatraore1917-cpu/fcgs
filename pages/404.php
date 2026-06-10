<?php
require_once __DIR__ . '/../includes/bootstrap.php';
$pageTitle        = 'Page introuvable';
$breadcrumbCenter = 'PAGE INTROUVABLE';
$currentPage      = '';
require_once __DIR__ . '/../partials/header.php';
?>
<div style="text-align:center;padding:80px 20px;">
    <h1 style="font-size:80px;font-weight:900;color:var(--bleu-fc);margin-bottom:10px;">404</h1>
    <p style="font-size:22px;color:#555;margin-bottom:30px;">Cette page n'existe pas ou a été déplacée.</p>
    <a href="<?= SITE_URL ?>/" class="btn-primary" style="text-decoration:none;display:inline-block;">
        ← Retour à l'accueil
    </a>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
