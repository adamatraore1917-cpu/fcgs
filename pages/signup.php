<?php
require_once __DIR__ . '/../includes/bootstrap.php';

if (isLoggedIn()) redirect(SITE_URL . '/profil');

$errors  = [];
$data    = $_POST;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Token de sécurité invalide.';
    } else {
        $nom      = trim($data['nom']           ?? '');
        $prenom   = trim($data['prenom']         ?? '');
        $email    = trim($data['email']          ?? '');
        $dob      = trim($data['date_naissance'] ?? '');
        $ville    = trim($data['ville']          ?? '');
        $cp       = trim($data['code_postal']    ?? '');
        $frais    = trim($data['frais_annexes']  ?? '');
        $cotis    = trim($data['cotisations']    ?? '');
        $license  = $data['type_license']        ?? '';
        $password = $data['password']            ?? '';
        $passconf = $data['password_confirm']    ?? '';

        $validLicenses = ['seniors','enfants','dirigeant','arbitre','educateur_federal'];

        // ---- VALIDATIONS ----
        // Nom : lettres, espaces, tirets, apostrophes seulement
        if (!$nom)
            $errors[] = 'Le nom est requis.';
        elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s\-\']+$/u', $nom))
            $errors[] = 'Le nom ne doit contenir que des lettres (pas de chiffres).';

        // Prénom : lettres, espaces, tirets, apostrophes seulement
        if (!$prenom)
            $errors[] = 'Le prénom est requis.';
        elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s\-\']+$/u', $prenom))
            $errors[] = 'Le prénom ne doit contenir que des lettres (pas de chiffres).';

        // Email
        if (!isValidEmail($email))
            $errors[] = 'Adresse email invalide.';

        // Date de naissance : format YYYY-MM-DD, obligatoire
        if (!$dob)
            $errors[] = 'La date de naissance est requise.';
        elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob) || !strtotime($dob))
            $errors[] = 'Date de naissance invalide.';

        // Ville : lettres seulement
        if ($ville && !preg_match('/^[a-zA-ZÀ-ÿ\s\-\']+$/u', $ville))
            $errors[] = 'La ville ne doit contenir que des lettres (pas de chiffres).';

        // Code postal : chiffres seulement
        if ($cp && !preg_match('/^\d{1,10}$/', $cp))
            $errors[] = 'Le code postal ne doit contenir que des chiffres.';

        // Frais annexes : nombre décimal
        if ($frais !== '' && !is_numeric($frais))
            $errors[] = 'Les frais annexes doivent être un nombre.';

        // Cotisations : nombre décimal
        if ($cotis !== '' && !is_numeric($cotis))
            $errors[] = 'Les cotisations doivent être un nombre.';

        // Licence
        if (!in_array($license, $validLicenses))
            $errors[] = 'Veuillez choisir un type de licence.';

        // Mot de passe
        if (strlen($password) < 8)
            $errors[] = 'Mot de passe : 8 caractères minimum.';
        if ($password !== $passconf)
            $errors[] = 'Les mots de passe ne correspondent pas.';

        // Email unique
        if (empty($errors)) {
            $stmt = db()->prepare('SELECT id FROM users WHERE email = ?');
            $stmt->execute([$email]);
            if ($stmt->fetch()) $errors[] = 'Cette adresse email est déjà utilisée.';
        }

        // Uploads carte identité
        $photoRecto = null;
        $photoVerso = null;
        if (empty($errors)) {
            foreach (['photo_recto' => &$photoRecto, 'photo_verso' => &$photoVerso] as $field => &$dest) {
                if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                    $ext     = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
                    $allowed = ['jpg','jpeg','png','gif','webp'];
                    if (!in_array($ext, $allowed)) {
                        $errors[] = 'Format non supporté pour ' . $field . ' (jpg, png, webp).';
                    } else {
                        $filename = sanitizeFilename(uniqid('id_') . '.' . $ext);
                        move_uploaded_file($_FILES[$field]['tmp_name'], UPLOAD_DIR . $filename);
                        $dest = $filename;
                    }
                }
            }
        }

        if (empty($errors)) {
            $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            $stmt = db()->prepare(
                'INSERT INTO users (nom, prenom, email, password, role, date_naissance, ville, code_postal,
                                    type_license, frais_annexes, cotisations, photo_recto, photo_verso)
                 VALUES (?,?,?,?,\'user\',?,?,?,?,?,?,?,?)'
            );
            $stmt->execute([
                $nom, $prenom, $email, $hash,
                $dob, $ville, $cp,
                $license,
                $frais !== '' ? (float)$frais : null,
                $cotis !== '' ? (float)$cotis : null,
                $photoRecto, $photoVerso
            ]);

            $userId = db()->lastInsertId();
            session_regenerate_id(true);
            $_SESSION['user_id']     = $userId;
            $_SESSION['user_email']  = $email;
            $_SESSION['user_role']   = 'user';
            $_SESSION['user_nom']    = $nom;
            $_SESSION['user_prenom'] = $prenom;

            flashMessage('success', 'Inscription réussie ! Bienvenue ' . $prenom . ' !');
            redirect(SITE_URL . '/profil');
        }
    }
}

$pageTitle        = 'Inscription au Club';
$breadcrumbCenter = 'INSCRIVEZ VOUS ICI';
$currentPage      = '';
require_once __DIR__ . '/../partials/header.php';
?>

<div class="inscription-page">

    <?php if (!empty($errors)): ?>
        <div class="flash-message flash-error" style="max-width:1000px;margin:0 auto 20px;" role="alert" aria-live="assertive">
            <?php foreach ($errors as $e): ?><div>⚠ <?= h($e) ?></div><?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="" id="signup-form" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
        <input type="hidden" name="signup" value="1">
        <input type="hidden" name="type_license" id="type_license" value="<?= h($data['type_license'] ?? '') ?>">

        <div class="inscription-grid">

            <!-- COLONNE GAUCHE : Carte d'identité -->
            <div>
                <div
                    class="id-card-area"
                    id="drop-recto"
                    data-input="photo_recto_input"
                    role="button"
                    tabindex="0"
                    aria-label="Cliquer pour ajouter la photo recto de la CNI"
                    style="margin-bottom:16px;position:relative;"
                >
                    <img id="preview-recto"
                         src="<?= ASSETS_URL ?>/images/icons/inscription.png"
                         alt="Carte d'identité recto"
                         class="placeholder"
                         style="height:80px;opacity:0.4;">
                    <span style="font-size:12px;color:#aaa;margin-top:6px;">CNI / Photo (optionnel)</span>
                </div>
                <input type="file" id="photo_recto_input" name="photo_recto" accept="image/*" style="display:none;" data-preview="preview-recto">

                <div class="id-card-label" id="label-recto" tabindex="0" role="button" aria-label="Ajouter recto">RECTO</div>

                <div
                    class="id-card-area"
                    id="drop-verso"
                    data-input="photo_verso_input"
                    role="button"
                    tabindex="0"
                    aria-label="Cliquer pour ajouter la photo verso de la CNI"
                    style="margin-top:16px;position:relative;"
                >
                    <img id="preview-verso"
                         src="<?= ASSETS_URL ?>/images/icons/inscription.png"
                         alt="Carte d'identité verso"
                         class="placeholder"
                         style="height:80px;opacity:0.4;">
                    <span style="font-size:12px;color:#aaa;margin-top:6px;">Verso CNI (optionnel)</span>
                </div>
                <input type="file" id="photo_verso_input" name="photo_verso" accept="image/*" style="display:none;" data-preview="preview-verso">

                <div class="id-card-label" id="label-verso" tabindex="0" role="button" aria-label="Ajouter verso" style="margin-top:16px;">VERSO</div>
            </div>

            <!-- COLONNE DROITE : Formulaire -->
            <div>
                <!-- NOM -->
                <div class="form-group">
                    <label class="form-label" for="nom">NOM *</label>
                    <input
                        class="form-control"
                        type="text"
                        id="nom"
                        name="nom"
                        value="<?= h($data['nom'] ?? '') ?>"
                        required
                        autocomplete="family-name"
                        pattern="[a-zA-ZÀ-ÿ\s\-']+"
                        title="Lettres uniquement, pas de chiffres"
                        inputmode="text"
                    >
                    <span class="field-hint">Lettres uniquement</span>
                </div>

                <!-- PRÉNOM -->
                <div class="form-group">
                    <label class="form-label" for="prenom">PRÉNOM *</label>
                    <input
                        class="form-control"
                        type="text"
                        id="prenom"
                        name="prenom"
                        value="<?= h($data['prenom'] ?? '') ?>"
                        required
                        autocomplete="given-name"
                        pattern="[a-zA-ZÀ-ÿ\s\-']+"
                        title="Lettres uniquement, pas de chiffres"
                        inputmode="text"
                    >
                    <span class="field-hint">Lettres uniquement</span>
                </div>

                <!-- EMAIL -->
                <div class="form-group">
                    <label class="form-label" for="email">Email *</label>
                    <input
                        class="form-control"
                        type="email"
                        id="email"
                        name="email"
                        value="<?= h($data['email'] ?? '') ?>"
                        required
                        autocomplete="email"
                    >
                </div>

                <!-- DATE DE NAISSANCE -->
                <div class="form-group">
                    <label class="form-label" for="date_naissance">Date de naissance *</label>
                    <input
                        class="form-control"
                        type="date"
                        id="date_naissance"
                        name="date_naissance"
                        value="<?= h($data['date_naissance'] ?? '') ?>"
                        required
                        max="<?= date('Y-m-d') ?>"
                        min="1920-01-01"
                        aria-required="true"
                    >
                    <span class="field-hint">Sélectionnez une date (aucun texte libre)</span>
                </div>

                <!-- VILLE + CP -->
                <div class="inline-fields">
                    <div class="form-group">
                        <label class="form-label" for="ville">Ville</label>
                        <input
                            class="form-control"
                            type="text"
                            id="ville"
                            name="ville"
                            value="<?= h($data['ville'] ?? '') ?>"
                            autocomplete="address-level2"
                            pattern="[a-zA-ZÀ-ÿ\s\-']+"
                            title="Lettres uniquement, pas de chiffres"
                            inputmode="text"
                        >
                        <span class="field-hint">Lettres uniquement</span>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="code_postal">Code postal</label>
                        <input
                            class="form-control"
                            type="text"
                            id="code_postal"
                            name="code_postal"
                            value="<?= h($data['code_postal'] ?? '') ?>"
                            autocomplete="postal-code"
                            pattern="\d{1,10}"
                            title="Chiffres uniquement"
                            inputmode="numeric"
                            maxlength="10"
                        >
                        <span class="field-hint">Chiffres uniquement</span>
                    </div>
                </div>

                <!-- FRAIS + COTISATIONS -->
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                    <div class="form-group euro-wrapper">
                        <label class="form-label">Frais annexes (en €)</label>
                        <input
                            class="form-control"
                            type="number"
                            name="frais_annexes"
                            id="frais_annexes"
                            value="<?= h($data['frais_annexes'] ?? '') ?>"
                            min="0"
                            step="0.01"
                            placeholder="0.00"
                            inputmode="decimal"
                            title="Montant en euros (chiffres uniquement)"
                            style="padding-right:28px;"
                        >
                        <span class="euro-sign" aria-hidden="true">€</span>
                    </div>
                    <div class="form-group euro-wrapper">
                        <label class="form-label">Cotisations (en €)</label>
                        <input
                            class="form-control"
                            type="number"
                            name="cotisations"
                            id="cotisations"
                            value="<?= h($data['cotisations'] ?? '') ?>"
                            min="0"
                            step="0.01"
                            placeholder="0.00"
                            inputmode="decimal"
                            title="Montant en euros (chiffres uniquement)"
                            style="padding-right:28px;"
                        >
                        <span class="euro-sign" aria-hidden="true">€</span>
                    </div>
                </div>

                <!-- MOT DE PASSE -->
                <div class="form-group">
                    <label class="form-label" for="password">Mot de passe * <small style="color:#888;font-weight:400;">(8 caractères min.)</small></label>
                    <input class="form-control" type="password" id="password" name="password" required autocomplete="new-password" minlength="8">
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirm">Confirmer le mot de passe *</label>
                    <input class="form-control" type="password" id="password_confirm" name="password_confirm" required autocomplete="new-password">
                </div>

                <!-- LICENCES -->
                <div class="license-options" role="group" aria-label="Type de licence">
                    <p style="font-size:12px;color:#888;margin-bottom:8px;">Choisissez votre type de licence * :</p>
                    <?php
                    $licenseLabels = [
                        'seniors'           => 'License seniors :',
                        'enfants'           => 'License enfants :',
                        'dirigeant'         => 'License dirigeant :',
                        'arbitre'           => 'License arbitre :',
                        'educateur_federal' => 'License éducateur fédéral :',
                    ];
                    $selectedLicense = $data['type_license'] ?? '';
                    foreach ($licenseLabels as $val => $label):
                        $isSelected = $selectedLicense === $val;
                    ?>
                        <div class="license-row <?= $isSelected ? 'selected' : '' ?>" data-license="<?= h($val) ?>">
                            <span><?= h($label) ?></span>
                            <button type="button" class="btn-choisir" aria-pressed="<?= $isSelected ? 'true' : 'false' ?>">
                                <?= $isSelected ? 'CHOISI ✓' : 'CHOISIR' ?>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- ALREADY HAVE ACCOUNT -->
                <p style="font-size:13px;color:#555;margin-top:16px;margin-bottom:16px;">
                    Déjà un compte ?
                    <a href="<?= SITE_URL ?>/inscription" style="color:var(--bleu-fc);text-decoration:underline;font-weight:600;">Se connecter</a>
                </p>

                <!-- SUBMIT -->
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <button type="submit" class="btn-primary">S'INSCRIRE AU CLUB</button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
