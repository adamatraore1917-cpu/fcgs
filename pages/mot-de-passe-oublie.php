<?php
require_once __DIR__ . '/../includes/bootstrap.php';

$errors  = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Token invalide.';
    } else {
        $email = trim($_POST['email'] ?? '');
        if (!isValidEmail($email)) {
            $errors[] = 'Adresse email invalide.';
        } else {
            // Check if user exists
            $stmt = db()->prepare('SELECT id FROM users WHERE email=?');
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                // In production: send reset email with token
                // For demo: just show success message
                $success = true;
            } else {
                // Don't reveal if email exists — security best practice
                $success = true;
            }
        }
    }
}

$pageTitle        = 'Mot de passe oublié';
$breadcrumbCenter = 'RÉINITIALISER LE MOT DE PASSE';
$currentPage      = '';
require_once __DIR__ . '/../partials/header.php';
?>

<div class="auth-page" style="padding:40px 20px;">
    <div style="max-width:480px;width:100%;background:white;border-radius:8px;padding:40px;box-shadow:0 4px 20px rgba(0,0,0,0.1);">
        <h1 style="font-size:22px;font-weight:800;color:#333;text-align:center;margin-bottom:24px;">
            Mot de passe oublié
        </h1>

        <?php if ($success): ?>
            <div class="flash-message flash-success" role="alert">
                Si cette adresse email est enregistrée, vous recevrez un lien de réinitialisation sous peu.
            </div>
            <div style="text-align:center;margin-top:20px;">
                <a href="<?= SITE_URL ?>/inscription" class="btn-primary" style="text-decoration:none;">Retour à la connexion</a>
            </div>
        <?php else: ?>
            <?php if (!empty($errors)): ?>
                <div class="flash-message flash-error" role="alert">
                    <?php foreach ($errors as $e): ?><div><?= h($e) ?></div><?php endforeach; ?>
                </div>
            <?php endif; ?>

            <p style="color:#666;font-size:14px;text-align:center;margin-bottom:24px;">
                Entrez votre adresse email pour recevoir un lien de réinitialisation.
            </p>

            <form method="POST" novalidate>
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <div class="form-group">
                    <label class="form-label" for="email">Adresse E-mail :</label>
                    <input class="form-control" type="email" id="email" name="email" required autocomplete="email" value="<?= h($_POST['email'] ?? '') ?>">
                </div>
                <button type="submit" class="btn-primary" style="width:100%;">Envoyer le lien</button>
            </form>
            <div style="text-align:center;margin-top:16px;">
                <a href="<?= SITE_URL ?>/inscription" class="link-forgot">Retour à la connexion</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
