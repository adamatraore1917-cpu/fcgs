<?php
// FC Grande-Synthe — Footer partiel
$flash = getFlashMessage();
?>
</main><!-- /main -->

<?php if ($flash): ?>
<div class="flash-message flash-<?= h($flash['type']) ?>" role="alert" aria-live="polite" style="position:fixed;bottom:80px;right:20px;z-index:8000;max-width:360px;">
    <?= h($flash['message']) ?>
</div>
<?php endif; ?>

<!-- FOOTER BAR -->
<div class="footer-bar" role="contentinfo">
    <span class="footer-bar-left">FC GRANDE-SYNTHE DISTRICT FRANCE</span>
    <nav class="footer-social" aria-label="Réseaux sociaux">
        <a href="https://youtube.com" target="_blank" rel="noopener" aria-label="YouTube">
            <img src="<?= ASSETS_URL ?>/images/icons/youtube.png" alt="YouTube" width="28" height="28">
        </a>
        <a href="https://twitter.com" target="_blank" rel="noopener" aria-label="Twitter / X">
            <img src="<?= ASSETS_URL ?>/images/icons/twitter.png" alt="Twitter" width="28" height="28">
        </a>
        <a href="https://instagram.com" target="_blank" rel="noopener" aria-label="Instagram">
            <img src="<?= ASSETS_URL ?>/images/icons/instagram.png" alt="Instagram" width="28" height="28">
        </a>
        <a href="https://facebook.com" target="_blank" rel="noopener" aria-label="Facebook">
            <img src="<?= ASSETS_URL ?>/images/icons/facebook.png" alt="Facebook" width="28" height="28">
        </a>
    </nav>
    <span class="footer-bar-right">UNIVERSITE FFF | LIGUE HAUTS DE FRANCE</span>
</div>

<!-- FOOTER PRINCIPAL -->
<footer class="footer-main">
    <div class="footer-grid container">
        <!-- CLUB -->
        <div class="footer-col">
            <h3 class="footer-col-title">CLUB</h3>
            <ul>
                <li><a href="<?= SITE_URL ?>/presentation">Présentation</a></li>
                <li><a href="<?= SITE_URL ?>/staff-direction">Staff &amp; Direction</a></li>
                <li><a href="<?= SITE_URL ?>/partenaires">Partenaires</a></li>
                <li><a href="<?= SITE_URL ?>/contact">Nous contacter</a></li>
                <li><a href="<?= SITE_URL ?>/recrutement">Recrutement</a></li>
                <li><a href="<?= SITE_URL ?>/devenir-partenaire">Devenir partenaire</a></li>
                <li><a href="<?= SITE_URL ?>/emettre-un-avis">Emettre un avis</a></li>
            </ul>
        </div>

        <!-- LÉGALES -->
        <div class="footer-col">
            <h3 class="footer-col-title">LEGALES</h3>
            <ul>
                <li><a href="<?= SITE_URL ?>/mentions-legales">Mention légales</a></li>
                <li><a href="<?= SITE_URL ?>/cgu">Conditions Générales d'Utilisation (CGU)</a></li>
                <li><a href="<?= SITE_URL ?>/politique-confidentialite">Politique de confidentialité (RGPD)</a></li>
                <li><a href="<?= SITE_URL ?>/cookies">Gestion des cookies</a></li>
                <li><a href="<?= SITE_URL ?>/droit-image">Droit à l'image</a></li>
                <li><a href="<?= SITE_URL ?>/protection-mineurs">Politique de protection des mineurs</a></li>
                <li><a href="<?= SITE_URL ?>/accessibilite">Accessibilité (RGAA)</a></li>
            </ul>
        </div>

        <!-- NEWSLETTER -->
        <div class="footer-col">
            <h3 class="footer-col-title">NEWSLETTER</h3>
            <form class="newsletter-form" method="POST" action="<?= SITE_URL ?>/newsletter" novalidate>
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <input
                    type="email"
                    name="newsletter_email"
                    class="newsletter-input"
                    placeholder="Adresse Email"
                    aria-label="Adresse Email newsletter"
                    required
                >
                <input
                    type="tel"
                    name="newsletter_tel"
                    class="newsletter-input"
                    placeholder="Téléphone mobile"
                    aria-label="Téléphone mobile newsletter"
                >
                <div class="newsletter-buttons">
                    <button type="submit" name="action" value="subscribe" class="btn-newsletter-submit">SOUMETTRE</button>
                    <button type="submit" name="action" value="unsubscribe" class="btn-newsletter-delete">SUPPRIMER</button>
                </div>
                <div class="newsletter-legal">
                    <input type="checkbox" id="newsletter-consent" name="consent" required>
                    <label for="newsletter-consent">
                        <small>NEWSLETTER — En envoyant ce formulaire, vous acceptez de recevoir des messages marketing récurrents par e-mail ou SMS. Vous pouvez vous désabonner à tout moment. Consultez les CGU et la Politique de confidentialité pour plus d'informations.</small>
                    </label>
                </div>
            </form>
        </div>
    </div>
</footer>

<!-- ========== COOKIE BANNER ========== -->
<div id="cookie-banner" class="cookie-banner-new" role="dialog" aria-modal="true" aria-label="Gestion des cookies" style="display:none;">
    <div class="cookie-banner-inner">
        <div class="cookie-banner-left">
            <div class="cookie-title">C<img src="<?= ASSETS_URL ?>/images/icons/web_cookie.png" alt="🍪" style="height:28px;vertical-align:middle;margin:0 2px;">KIES</div>
            <p class="cookie-desc">Nous utilisons des cookies pour améliorer votre expérience, mesurer l'audience du site et assurer certaines fonctionnalités.</p>
        </div>
        <div class="cookie-banner-buttons">
            <button class="btn-cookie-ok"   id="cookie-ok"      onclick="cookieAcceptAll()">Ok pour moi</button>
            <button class="btn-cookie-choose" id="cookie-choose" onclick="cookieOpenPanel()">JE CHOISIS</button>
            <button class="btn-cookie-no"   id="cookie-no"      onclick="cookieRefuseAll()">Non merci</button>
        </div>
    </div>
</div>

<!-- ========== COOKIE PANEL ========== -->
<div id="cookie-panel" class="cookie-panel-overlay" role="dialog" aria-modal="true" aria-label="Préférences cookies" style="display:none;">
    <div class="cookie-panel-box">
        <div class="cookie-title" style="margin-bottom:10px;">C<img src="<?= ASSETS_URL ?>/images/icons/web_cookie.png" alt="🍪" style="height:28px;vertical-align:middle;margin:0 2px;">KIES</div>
        <p class="cookie-desc" style="margin-bottom:6px;">Nous utilisons des cookies pour améliorer votre expérience, mesurer l'audience du site et assurer certaines fonctionnalités.</p>

        <p class="cookie-panel-subtitle">Personnalisez vos préférences concernant les cookies utilisés sur notre site.</p>

        <div class="cookie-panel-links">
            <a href="<?= SITE_URL ?>/politique-confidentialite">Politique de confidentialité</a> –
            <a href="<?= SITE_URL ?>/mentions-legales">Mentions légales</a> –
            <a href="<?= SITE_URL ?>/cgu">CGU</a>
        </div>

        <hr style="border:none;border-top:1px solid #e0d8c8;margin:20px 0;">

        <!-- NÉCESSAIRES -->
        <div class="cookie-row">
            <div>
                <h3 class="cookie-row-title">Cookies nécessaires</h3>
                <p class="cookie-row-desc">Ces cookies sont indispensables au fonctionnement du site.</p>
            </div>
            <span style="color:#2ecc71;font-weight:600;font-size:13px;white-space:nowrap;">Les cookies sont toujours actifs.</span>
        </div>

        <!-- STATISTIQUES -->
        <div class="cookie-row">
            <div>
                <h3 class="cookie-row-title">Cookies statistiques</h3>
                <p class="cookie-row-desc">Nous permettent de mesurer l'audience et améliorer le site.</p>
            </div>
            <label class="cookie-toggle" aria-label="Cookies statistiques">
                <input type="checkbox" id="ck-stats" checked>
                <span class="cookie-toggle-slider"></span>
            </label>
        </div>

        <!-- RÉSEAUX SOCIAUX -->
        <div class="cookie-row">
            <div>
                <h3 class="cookie-row-title">Cookies réseaux sociaux</h3>
                <p class="cookie-row-desc">Permettent l'intégration de contenus Discord, Twitch ou YouTube.</p>
            </div>
            <label class="cookie-toggle" aria-label="Cookies réseaux sociaux">
                <input type="checkbox" id="ck-social" checked>
                <span class="cookie-toggle-slider"></span>
            </label>
        </div>

        <!-- MARKETING -->
        <div class="cookie-row">
            <div>
                <h3 class="cookie-row-title">Cookies marketing</h3>
                <p class="cookie-row-desc">Utilisés pour proposer des contenus personnalisés.</p>
            </div>
            <label class="cookie-toggle" aria-label="Cookies marketing">
                <input type="checkbox" id="ck-marketing">
                <span class="cookie-toggle-slider cookie-toggle-red"></span>
            </label>
        </div>

        <div class="cookie-panel-actions">
            <button class="btn-cookie-ok"     onclick="cookieSaveChoices()">Ok pour moi</button>
            <button class="btn-cookie-choose" onclick="cookieSaveChoices()">JE CHOISIS</button>
            <button class="btn-cookie-no"     onclick="cookieRefuseAll()">Non merci</button>
        </div>
    </div>
</div>

<!-- Bouton cookie flottant (toujours visible) -->
<button id="cookie-float-btn" onclick="cookieOpenPanel()" aria-label="Gérer mes cookies"
        style="position:fixed;bottom:16px;right:16px;background:#f3efe5;border:1px solid #ddd;border-radius:50%;width:40px;height:40px;font-size:20px;cursor:pointer;z-index:9998;box-shadow:0 2px 8px rgba(0,0,0,0.15);display:flex;align-items:center;justify-content:center;">
    🍪
</button>

<script src="<?= ASSETS_URL ?>/js/main.js"></script>
</body>
</html>
