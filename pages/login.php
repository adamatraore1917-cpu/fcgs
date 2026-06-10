<?php
require_once __DIR__ . '/../includes/bootstrap.php';

// Déjà connecté ?
if (isLoggedIn()) {
    redirect(SITE_URL . '/profil');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Token de sécurité invalide.';
    } else {
        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');
        $remember = isset($_POST['remember']);

        if (!isValidEmail($email)) $errors[] = 'Adresse e-mail invalide.';
        if (!$password)            $errors[] = 'Mot de passe requis.';

        if (empty($errors)) {
            $stmt = db()->prepare('SELECT * FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id']     = $user['id'];
                $_SESSION['user_email']  = $user['email'];
                $_SESSION['user_role']   = $user['role'];
                $_SESSION['user_nom']    = $user['nom'];
                $_SESSION['user_prenom'] = $user['prenom'];

                db()->prepare('UPDATE users SET derniere_connexion = NOW() WHERE id = ?')
                   ->execute([$user['id']]);

                if ($remember) {
                    $token = bin2hex(random_bytes(32));
                    db()->prepare('UPDATE users SET remember_token = ? WHERE id = ?')
                       ->execute([$token, $user['id']]);
                    setcookie('remember_token', $token, time() + REMEMBER_TOKEN_EXPIRY, '/', '', false, true);
                }

                flashMessage('success', 'Bienvenue ' . $user['prenom'] . ' !');
                redirect(isAdmin() ? SITE_URL . '/admin-acces' : SITE_URL . '/profil');
            } else {
                $errors[] = 'Email ou mot de passe incorrect.';
            }
        }
    }
}

$pageTitle        = 'Connexion';
$breadcrumbCenter = 'CONNECTER VOUS ICI';
$currentPage      = '';
require_once __DIR__ . '/../partials/header.php';
?>

<div class="auth-page" style="padding:40px 20px;">
    <div class="login-box">
        <div class="login-visual" aria-hidden="true"></div>

        <div class="login-form-side">
            <h1 class="login-form-title">Connectez vous à notre site</h1>

            <?php if (!empty($errors)): ?>
                <div class="flash-message flash-error" role="alert">
                    <?php foreach ($errors as $e): ?><div><?= h($e) ?></div><?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" id="login-form" novalidate>
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <input type="hidden" name="login" value="1">

                <div class="form-group">
                    <label class="form-label" for="email">Adresse E-mail :</label>
                    <input
                        class="form-control"
                        type="email"
                        id="email"
                        name="email"
                        value="<?= h($_POST['email'] ?? '') ?>"
                        required
                        autocomplete="email"
                        aria-required="true"
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Mot de passe :</label>
                    <input
                        class="form-control"
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        aria-required="true"
                    >
                </div>

                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="remember" id="remember">
                        Se souvenir de moi !
                    </label>
                </div>

                <button type="submit" class="btn-primary">SE CONNECTER</button>
            </form>

            <a href="<?= SITE_URL ?>/mot-de-passe-oublie" class="link-forgot" style="display:block;margin-top:12px;">
                Vous avez oublié votre mot de passe ?
            </a>

            <!-- Lien vers inscription CLUB -->
            <a href="<?= SITE_URL ?>/signup" class="link-register">
                Vous n'avez pas de compte sur le site ? Créez en un maintenant !
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
