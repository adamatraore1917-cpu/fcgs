<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
trackVisit('emettre-un-avis');

$errors  = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_avis'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Token invalide.';
    } else {
        $nom        = trim($_POST['nom']         ?? '');
        $email      = trim($_POST['email']       ?? '');
        $note       = (int)($_POST['note']       ?? 5);
        $commentaire= trim($_POST['commentaire'] ?? '');
        if (!$nom)                       $errors[] = 'Le nom est requis.';
        if ($email && !isValidEmail($email)) $errors[] = 'Email invalide.';
        if (strlen($commentaire) < 10)   $errors[] = 'L\'avis doit faire au moins 10 caractères.';
        if ($note < 1 || $note > 5)      $note = 5;

        if (empty($errors)) {
            db()->prepare('INSERT INTO avis (nom, email, note, commentaire) VALUES (?,?,?,?)')
               ->execute([$nom, $email ?: null, $note, $commentaire]);
            flashMessage('success', 'Merci pour votre avis ! Il sera publié après modération.');
            redirect(SITE_URL . '/emettre-un-avis');
        }
    }
}

// Avis publiés
$avisPublies = db()->query("SELECT * FROM avis WHERE statut='publie' ORDER BY created_at DESC LIMIT 20")->fetchAll();

$pageTitle        = 'Émettre un Avis';
$breadcrumbCenter = 'VOTRE AVIS COMPTE';
$currentPage      = 'club';
require_once __DIR__ . '/../../partials/header.php';
?>

<style>
.stars-input { display:flex; gap:4px; flex-direction:row-reverse; justify-content:flex-end; }
.stars-input input { display:none; }
.stars-input label { font-size:32px; color:#ddd; cursor:pointer; transition:color 0.15s; }
.stars-input label:hover,
.stars-input label:hover ~ label,
.stars-input input:checked ~ label { color:#f0c030; }
.star-display { color:#f0c030; font-size:16px; }
</style>

<div style="max-width:900px;margin:0 auto;padding:40px 20px;">

    <h1 style="font-size:28px;font-weight:900;color:var(--bleu-fc);text-transform:uppercase;text-align:center;margin-bottom:10px;">Votre Avis</h1>
    <p style="text-align:center;color:#888;margin-bottom:40px;">Partagez votre expérience au FC Grande-Synthe</p>

    <!-- FORMULAIRE -->
    <div style="background:#f0f4ff;border-radius:12px;padding:30px;margin-bottom:40px;border:1px solid #dce4f0;">

        <?php if (!empty($errors)): ?>
            <div class="flash-message flash-error" role="alert">
                <?php foreach ($errors as $e): ?><div><?= h($e) ?></div><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="send_avis" value="1">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div class="form-group">
                    <label class="form-label">Nom *</label>
                    <input class="form-control" type="text" name="nom" required value="<?= h($_POST['nom'] ?? '') ?>" placeholder="Votre prénom ou nom">
                </div>
                <div class="form-group">
                    <label class="form-label">Email <small style="color:#aaa;">(optionnel)</small></label>
                    <input class="form-control" type="email" name="email" value="<?= h($_POST['email'] ?? '') ?>" placeholder="Non publié">
                </div>
            </div>

            <!-- NOTE ÉTOILES -->
            <div class="form-group" style="margin-bottom:16px;">
                <label class="form-label">Note *</label>
                <div class="stars-input" role="radiogroup" aria-label="Note de 1 à 5 étoiles">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" id="star<?= $i ?>" name="note" value="<?= $i ?>"
                               <?= (($_POST['note'] ?? 5) == $i) ? 'checked' : '' ?>>
                        <label for="star<?= $i ?>" title="<?= $i ?> étoile<?= $i>1?'s':'' ?>">★</label>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="form-group" style="margin-bottom:20px;">
                <label class="form-label">Votre avis *</label>
                <textarea class="form-control" name="commentaire" rows="5" required
                          placeholder="Décrivez votre expérience au club..."><?= h($_POST['commentaire'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn-primary">Publier mon avis</button>
        </form>
    </div>

    <!-- AVIS PUBLIÉS -->
    <?php if (!empty($avisPublies)): ?>
        <h2 style="font-size:20px;font-weight:800;color:var(--bleu-fc);text-transform:uppercase;margin-bottom:20px;">Avis des membres</h2>
        <div style="display:flex;flex-direction:column;gap:16px;">
            <?php foreach ($avisPublies as $a): ?>
                <div style="background:white;border-radius:8px;padding:20px;border:1px solid #eee;box-shadow:0 2px 8px rgba(0,0,0,0.04);">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                        <div>
                            <strong style="font-size:15px;color:#333;"><?= h($a['nom']) ?></strong>
                            <span class="star-display" style="margin-left:10px;">
                                <?= str_repeat('★', $a['note']) . str_repeat('☆', 5 - $a['note']) ?>
                            </span>
                        </div>
                        <span style="font-size:12px;color:#aaa;"><?= h(date('d/m/Y', strtotime($a['created_at']))) ?></span>
                    </div>
                    <p style="font-size:14px;color:#555;line-height:1.6;"><?= h($a['commentaire']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div style="text-align:center;margin-top:30px;">
        <a href="<?= SITE_URL ?>/club" class="btn-secondary" style="text-decoration:none;">← Retour Club</a>
    </div>
</div>

<?php require_once __DIR__ . '/../../partials/footer.php'; ?>
