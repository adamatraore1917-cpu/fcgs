<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
trackVisit('devenir-partenaire');

$errors  = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_partner'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Token invalide.';
    } else {
        $nom     = trim($_POST['nom']      ?? '');
        $societe = trim($_POST['societe']  ?? '');
        $email   = trim($_POST['email']    ?? '');
        $tel     = trim($_POST['tel']      ?? '');
        $message = trim($_POST['message']  ?? '');
        if (!$nom || !isValidEmail($email) || !$message)
            $errors[] = 'Merci de remplir les champs obligatoires.';
        if (empty($errors)) {
            $stmt = db()->prepare('INSERT INTO contacts (nom, prenom, email, telephone, type_contact, message) VALUES (?,?,?,?,?,?)');
            $stmt->execute([$nom, '', $email, $tel, 'professionnel', '[PARTENARIAT - '.$societe.'] '.$message]);
            flashMessage('success', 'Votre demande de partenariat a été envoyée ! Nous vous recontacterons sous 48h.');
            redirect(SITE_URL . '/devenir-partenaire');
        }
    }
}

$pageTitle        = 'Devenir Partenaire';
$breadcrumbCenter = 'NOUS REJOINDRE';
$currentPage      = 'club';
require_once __DIR__ . '/../../partials/header.php';
?>

<div style="max-width:900px;margin:0 auto;padding:40px 20px;">

    <div style="background:var(--bleu-fc);border-radius:12px;padding:40px;text-align:center;color:white;margin-bottom:40px;">
        <h1 style="font-size:28px;font-weight:900;text-transform:uppercase;margin-bottom:14px;">Devenez Partenaire</h1>
        <p style="font-size:15px;opacity:0.85;max-width:560px;margin:0 auto;">
            Associez votre image à un club dynamique, engagé dans le sport, l'éducation et le numérique au service de la jeunesse de Grande-Synthe.
        </p>
    </div>

    <!-- AVANTAGES -->
    <h2 style="font-size:20px;font-weight:800;color:var(--bleu-fc);text-transform:uppercase;margin-bottom:20px;">Pourquoi nous rejoindre ?</h2>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:40px;">
        <?php
        $avantages = [
            ['icon'=>'matchs.png',       'titre'=>'Visibilité locale',      'texte'=>'Logo sur les équipements, banderoles, site web et réseaux sociaux.'],
            ['icon'=>'team.png',          'titre'=>'Image positive',         'texte'=>'Associez-vous à un projet sportif et éducatif reconnu.'],
            ['icon'=>'internationnal.png','titre'=>'Réseau',                 'texte'=>'Intégrez un réseau de partenaires locaux et institutionnels.'],
        ];
        foreach ($avantages as $a):
        ?>
            <div style="background:#f0f4ff;border-radius:8px;padding:20px;text-align:center;">
                <img src="<?= ASSETS_URL ?>/images/icons/<?= h($a['icon']) ?>" alt="" style="height:36px;margin:0 auto 12px;">
                <h3 style="font-size:14px;font-weight:700;color:var(--bleu-fc);margin-bottom:8px;"><?= h($a['titre']) ?></h3>
                <p style="font-size:13px;color:#666;"><?= h($a['texte']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- FORMULAIRE -->
    <div style="background:#f9f9f9;border-radius:12px;padding:30px;border:1px solid #ddd;">
        <h2 style="font-size:18px;font-weight:800;color:var(--bleu-fc);margin-bottom:20px;">Formulaire de contact partenariat</h2>

        <?php if (!empty($errors)): ?>
            <div class="flash-message flash-error" role="alert">
                <?php foreach ($errors as $e): ?><div><?= h($e) ?></div><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="send_partner" value="1">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div class="form-group">
                    <label class="form-label">Nom complet *</label>
                    <input class="form-control" type="text" name="nom" required value="<?= h($_POST['nom'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Société / Organisation</label>
                    <input class="form-control" type="text" name="societe" value="<?= h($_POST['societe'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input class="form-control" type="email" name="email" required value="<?= h($_POST['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Téléphone</label>
                    <input class="form-control" type="tel" name="tel" value="<?= h($_POST['tel'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group" style="margin-bottom:20px;">
                <label class="form-label">Votre projet de partenariat *</label>
                <textarea class="form-control" name="message" rows="5" required><?= h($_POST['message'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="btn-primary">Envoyer ma demande</button>
        </form>
    </div>

    <div style="text-align:center;margin-top:30px;">
        <a href="<?= SITE_URL ?>/partenaires" class="btn-secondary" style="text-decoration:none;margin-right:10px;">Voir nos partenaires</a>
        <a href="<?= SITE_URL ?>/club" class="btn-secondary" style="text-decoration:none;">← Retour Club</a>
    </div>
</div>

<?php require_once __DIR__ . '/../../partials/footer.php'; ?>
