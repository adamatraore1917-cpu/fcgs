<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
trackVisit('recrutement');

$offres = db()->query('SELECT * FROM recrutement WHERE actif=1 ORDER BY created_at DESC')->fetchAll();

$errors  = [];
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['postuler'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Token invalide.';
    } else {
        $nom     = trim($_POST['nom']     ?? '');
        $email   = trim($_POST['email']   ?? '');
        $poste   = trim($_POST['poste']   ?? '');
        $message = trim($_POST['message'] ?? '');
        if (!$nom || !isValidEmail($email) || !$message)
            $errors[] = 'Merci de remplir tous les champs obligatoires.';
        if (empty($errors)) {
            $stmt = db()->prepare('INSERT INTO contacts (nom, prenom, email, type_contact, message) VALUES (?,?,?,?,?)');
            $stmt->execute([$nom, '', $email, 'club', '[CANDIDATURE : '.$poste.'] '.$message]);
            flashMessage('success', 'Candidature envoyée ! Nous vous recontacterons rapidement.');
            redirect(SITE_URL . '/recrutement');
        }
    }
}

$pageTitle        = 'Recrutement';
$breadcrumbCenter = 'REJOINDRE LE CLUB';
$currentPage      = 'club';
require_once __DIR__ . '/../../partials/header.php';
?>

<div style="max-width:1000px;margin:0 auto;padding:40px 20px;">

    <!-- HERO -->
    <div style="background:var(--orange);border-radius:12px;padding:36px;text-align:center;color:white;margin-bottom:40px;">
        <h1 style="font-size:28px;font-weight:900;text-transform:uppercase;margin-bottom:12px;">RECRUTEMENT</h1>
        <p style="font-size:15px;opacity:0.9;max-width:600px;margin:0 auto;">
            Nous recrutons des éducateurs, coachs et bénévoles pour la saison 2026/2027. Rejoignez l'aventure FC Grande-Synthe !
        </p>
    </div>

    <!-- OFFRES -->
    <?php if (!empty($offres)): ?>
        <h2 style="font-size:20px;font-weight:800;color:var(--bleu-fc);text-transform:uppercase;margin-bottom:20px;">Postes à pourvoir</h2>
        <div style="display:flex;flex-direction:column;gap:16px;margin-bottom:40px;">
            <?php foreach ($offres as $o): ?>
                <div style="border:1px solid #ddd;border-radius:8px;padding:20px;background:white;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:10px;margin-bottom:10px;">
                        <h3 style="font-size:17px;font-weight:700;color:var(--bleu-fc);"><?= h($o['poste']) ?></h3>
                        <span style="background:var(--orange);color:white;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;"><?= h($o['type_contrat']) ?></span>
                    </div>
                    <p style="font-size:14px;color:#555;line-height:1.6;"><?= h($o['description']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- FORMULAIRE CANDIDATURE -->
    <div style="background:#f0f4ff;border-radius:12px;padding:30px;">
        <h2 style="font-size:20px;font-weight:800;color:var(--bleu-fc);text-transform:uppercase;margin-bottom:20px;">Postuler</h2>

        <?php if (!empty($errors)): ?>
            <div class="flash-message flash-error" role="alert">
                <?php foreach ($errors as $e): ?><div><?= h($e) ?></div><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="postuler" value="1">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div class="form-group">
                    <label class="form-label">Nom complet *</label>
                    <input class="form-control" type="text" name="nom" required value="<?= h($_POST['nom'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input class="form-control" type="email" name="email" required value="<?= h($_POST['email'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group" style="margin-bottom:16px;">
                <label class="form-label">Poste souhaité</label>
                <select class="form-control" name="poste">
                    <option value="">— Choisir un poste —</option>
                    <?php foreach ($offres as $o): ?>
                        <option value="<?= h($o['poste']) ?>"><?= h($o['poste']) ?></option>
                    <?php endforeach; ?>
                    <option value="Autre">Autre</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:20px;">
                <label class="form-label">Motivation / Message *</label>
                <textarea class="form-control" name="message" rows="5" required><?= h($_POST['message'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="btn-primary">Envoyer ma candidature</button>
        </form>
    </div>

    <div style="text-align:center;margin-top:30px;">
        <a href="<?= SITE_URL ?>/club" class="btn-secondary" style="text-decoration:none;">← Retour Club</a>
    </div>
</div>

<?php require_once __DIR__ . '/../../partials/footer.php'; ?>
