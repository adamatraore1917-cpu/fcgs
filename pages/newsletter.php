<?php
require_once __DIR__ . '/../includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        flashMessage('error', 'Token invalide.');
        redirect(SITE_URL . '/');
    }

    $email   = trim($_POST['newsletter_email'] ?? '');
    $tel     = trim($_POST['newsletter_tel']   ?? '');
    $action  = $_POST['action'] ?? 'subscribe';
    $consent = isset($_POST['consent']);
    $referer = $_SERVER['HTTP_REFERER'] ?? SITE_URL . '/';

    if (!isValidEmail($email)) {
        flashMessage('error', 'Adresse email invalide.');
        redirect($referer);
    }

    if ($action === 'subscribe') {
        if (!$consent) {
            flashMessage('error', 'Veuillez accepter les conditions pour vous inscrire à la newsletter.');
            redirect($referer);
        }
        try {
            $stmt = db()->prepare('INSERT INTO newsletter (email, telephone) VALUES (?,?)');
            $stmt->execute([$email, $tel ?: null]);
            flashMessage('success', 'Votre inscription à la newsletter a été enregistrée.');
        } catch (PDOException $e) {
            flashMessage('info', 'Cette adresse email est déjà inscrite à notre newsletter.');
        }
    } elseif ($action === 'unsubscribe') {
        db()->prepare('DELETE FROM newsletter WHERE email=?')->execute([$email]);
        flashMessage('success', 'Vous avez bien été désinscrit de la newsletter.');
    }

    redirect($referer);
}

redirect(SITE_URL . '/');
