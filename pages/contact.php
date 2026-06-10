<?php
require_once __DIR__ . '/../includes/bootstrap.php';
trackVisit('contact');

$errors  = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_contact'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Token de sécurité invalide.';
    } else {
        $nom     = trim($_POST['nom']          ?? '');
        $prenom  = trim($_POST['prenom']        ?? '');
        $email   = trim($_POST['email']         ?? '');
        $tel     = trim($_POST['telephone']     ?? '');
        $type    = in_array($_POST['type_contact'] ?? '', ['professionnel','club']) ? $_POST['type_contact'] : 'club';
        $message = trim($_POST['message']       ?? '');

        if (!$nom)                         $errors[] = 'Le nom est requis.';
        if (!$prenom)                      $errors[] = 'Le prénom est requis.';
        if (!isValidEmail($email))         $errors[] = 'Email invalide.';
        if (strlen($message) < 10)         $errors[] = 'Le message est trop court (minimum 10 caractères).';

        if (empty($errors)) {
            $stmt = db()->prepare(
                'INSERT INTO contacts (nom, prenom, email, telephone, type_contact, message) VALUES (?,?,?,?,?,?)'
            );
            $stmt->execute([$nom, $prenom, $email, $tel, $type, $message]);
            flashMessage('success', 'Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.');
            header('Location: ' . SITE_URL . '/contact');
            exit;
        }
    }
}

$pageTitle        = 'Contact';
$breadcrumbCenter = 'NOUS CONTACTER ICI';
$currentPage      = 'contact';
require_once __DIR__ . '/../partials/header.php';
?>

<!-- IMAGES -->
<div class="contacts-images-row">
    <img src="<?= ASSETS_URL ?>/images/illustrations/contact01.jpg"  alt="Contact professionnel FC Grande-Synthe" loading="eager">
    <img src="<?= ASSETS_URL ?>/images/illustrations/contact02.jpg" alt="Contact club FC Grande-Synthe" loading="eager">
</div>

<!-- BOUTONS -->
<div class="contacts-buttons-row">
    <button class="btn-contact-type" id="btn-contact-pro" aria-haspopup="dialog">PROFESSIONNEL</button>
    <button class="btn-contact-type" id="btn-contact-club" aria-haspopup="dialog">CLUB</button>
</div>

<!-- MODAL PROFESSIONNEL -->
<div class="modal-overlay" id="modal-contact-pro" role="dialog" aria-modal="true" aria-labelledby="modal-pro-title">
    <div class="modal-box">
        <button class="modal-close" aria-label="Fermer">&times;</button>
        <h2 class="modal-title" id="modal-pro-title">Contact Professionnel</h2>

        <?php if (!empty($errors) && ($_POST['type_contact'] ?? '') === 'professionnel'): ?>
            <div class="flash-message flash-error" role="alert">
                <?php foreach ($errors as $e): ?><div><?= h($e) ?></div><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= SITE_URL ?>/contact" novalidate>
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="send_contact" value="1">
            <input type="hidden" name="type_contact" value="professionnel">

            <div class="form-group">
                <label class="form-label" for="nom-pro">Nom *</label>
                <input class="form-control" type="text" id="nom-pro" name="nom" value="<?= h($_POST['nom'] ?? '') ?>" required autocomplete="family-name">
            </div>
            <div class="form-group">
                <label class="form-label" for="prenom-pro">Prénom *</label>
                <input class="form-control" type="text" id="prenom-pro" name="prenom" value="<?= h($_POST['prenom'] ?? '') ?>" required autocomplete="given-name">
            </div>
            <div class="form-group">
                <label class="form-label" for="email-pro">Email *</label>
                <input class="form-control" type="email" id="email-pro" name="email" value="<?= h($_POST['email'] ?? '') ?>" required autocomplete="email">
            </div>
            <div class="form-group">
                <label class="form-label" for="tel-pro">Téléphone</label>
                <input class="form-control" type="tel" id="tel-pro" name="telephone" value="<?= h($_POST['telephone'] ?? '') ?>" autocomplete="tel">
            </div>
            <div class="form-group">
                <label class="form-label" for="msg-pro">Message *</label>
                <textarea class="form-control" id="msg-pro" name="message" rows="5" required maxlength="2000"><?= h($_POST['message'] ?? '') ?></textarea>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" class="btn-secondary modal-close">Annuler</button>
                <button type="submit" class="btn-primary">Envoyer</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL CLUB -->
<div class="modal-overlay" id="modal-contact-club" role="dialog" aria-modal="true" aria-labelledby="modal-club-title">
    <div class="modal-box">
        <button class="modal-close" aria-label="Fermer">&times;</button>
        <h2 class="modal-title" id="modal-club-title">Contact Club</h2>

        <?php if (!empty($errors) && ($_POST['type_contact'] ?? '') === 'club'): ?>
            <div class="flash-message flash-error" role="alert">
                <?php foreach ($errors as $e): ?><div><?= h($e) ?></div><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div style="background:var(--gris-clair);border-radius:6px;padding:16px;margin-bottom:16px;">
            <p style="font-size:13px;color:#555;margin-bottom:6px;"><strong>Adresse :</strong> Club house - boulevard des Fédérés, 59760 Grande-Synthe</p>
            <p style="font-size:13px;color:#555;margin-bottom:6px;"><strong>Téléphone :</strong> 06 86 40 84 26</p>
            <p style="font-size:13px;color:#555;"><strong>Email :</strong> <a href="mailto:contact@fcgrandesynthe.fr" style="color:var(--bleu-fc);">contact@fcgrandesynthe.fr</a></p>
        </div>

        <form method="POST" action="<?= SITE_URL ?>/contact" novalidate>
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="send_contact" value="1">
            <input type="hidden" name="type_contact" value="club">

            <div class="form-group">
                <label class="form-label" for="nom-club">Nom *</label>
                <input class="form-control" type="text" id="nom-club" name="nom" value="<?= h($_POST['nom'] ?? '') ?>" required autocomplete="family-name">
            </div>
            <div class="form-group">
                <label class="form-label" for="prenom-club">Prénom *</label>
                <input class="form-control" type="text" id="prenom-club" name="prenom" value="<?= h($_POST['prenom'] ?? '') ?>" required autocomplete="given-name">
            </div>
            <div class="form-group">
                <label class="form-label" for="email-club">Email *</label>
                <input class="form-control" type="email" id="email-club" name="email" value="<?= h($_POST['email'] ?? '') ?>" required autocomplete="email">
            </div>
            <div class="form-group">
                <label class="form-label" for="msg-club">Message *</label>
                <textarea class="form-control" id="msg-club" name="message" rows="5" required maxlength="2000"><?= h($_POST['message'] ?? '') ?></textarea>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" class="btn-secondary modal-close">Annuler</button>
                <button type="submit" class="btn-primary">Envoyer</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
