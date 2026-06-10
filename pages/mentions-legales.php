<?php
require_once __DIR__ . '/../includes/bootstrap.php';
trackVisit('mentions-legales');
$pageTitle        = 'Mentions Légales';
$breadcrumbCenter = 'BIENVENUE DANS NOTRE PROJET E-SPORT';
$currentPage      = '';
$theme            = 'esport';
require_once __DIR__ . '/../partials/header.php';
?>
<div class="legal-page">
    <div class="legal-section">
        <div class="legal-label">Editeur du site :</div>
        <div class="legal-content">
            FC Grande-Synthe<br>
            Ligue de Football des Hauts-de-France — District des Flandres
        </div>
    </div>
    <div class="legal-section">
        <div class="legal-label">Adresse :</div>
        <div class="legal-content">
            Club House FC Grande-Synthe<br>
            Boulevard des Fédérés<br>
            59760 Grande-Synthe
        </div>
    </div>
    <div class="legal-section">
        <div class="legal-label">Email :</div>
        <div class="legal-content">
            <a href="mailto:contact@fcgrandesynthe.fr" style="color:var(--rouge-esport);">contact@fcgrandesynthe.fr</a>
        </div>
    </div>
    <div class="legal-section">
        <div class="legal-label">Téléphone :</div>
        <div class="legal-content">06 86 40 84 26</div>
    </div>
    <div class="legal-section">
        <div class="legal-label">Site officiel :</div>
        <div class="legal-content">
            <a href="http://www.fcgrandesynthe.fr" style="color:var(--rouge-esport);">www.fcgrandesynthe.fr</a>
        </div>
    </div>
    <div class="legal-section">
        <div class="legal-label">Directeur de la publication :</div>
        <div class="legal-content">FC Grande-Synthe</div>
    </div>
    <div class="legal-section">
        <div class="legal-label">Responsable du site :</div>
        <div class="legal-content">Service de communication FC Grande-Synthe</div>
    </div>
    <div class="legal-section">
        <div class="legal-label">Hébergement :</div>
        <div class="legal-content" style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:10px;">
            <span>OVHcloud<br>2 rue Kellermann</span>
            <span>59100 Roubaix<br>France</span>
        </div>
    </div>
    <div class="legal-section">
        <div class="legal-label">Propriété intellectuelle :</div>
        <div class="legal-content">
            L'ensemble des contenus présents sur le site (textes, images, vidéos, logos, éléments graphiques, design, icônes, documents et contenus esport) est la propriété exclusive du FC Grande-Synthe ainsi que de son créateur original Robin V...Y sauf mentions contraires.<br><br>
            Toute reproduction, modification ou diffusion sans autorisation écrite est strictement interdite.
        </div>
    </div>
    <div class="legal-section">
        <div class="legal-label">Responsabilité :</div>
        <div class="legal-content">
            Le FC Grande-Synthe s'efforce de fournir des informations fiables et régulièrement mises à jour. Toutefois, le club ne saurait être tenu responsable des erreurs, omissions ou interruptions du site.<br><br>
            Les liens externes présents sur le site peuvent rediriger vers des contenus indépendants du FC Grande-Synthe.
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
