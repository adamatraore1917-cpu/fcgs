<?php
require_once __DIR__ . '/../includes/bootstrap.php';

// Detect which legal page to show from URL
$page = basename($_SERVER['PHP_SELF'], '.php');
// Will be called via router with $legalPage set
$legalPage = $legalPage ?? $page;

$configs = [
    'cgu' => [
        'title' => "Conditions Générales d'Utilisation",
        'sections' => [
            'Objet' => "Les présentes Conditions Générales d'Utilisation (CGU) régissent l'utilisation du site internet du FC Grande-Synthe. En accédant au site, l'utilisateur accepte sans réserve les présentes CGU.",
            'Accès au site' => "Le site est accessible gratuitement à tout utilisateur disposant d'un accès à internet. Les frais d'accès à internet sont à la charge de l'utilisateur. Le FC Grande-Synthe ne peut être tenu responsable de l'indisponibilité temporaire du site.",
            'Comptes utilisateurs' => "L'inscription sur le site permet la création d'un espace personnel. L'utilisateur s'engage à fournir des informations exactes et à maintenir la confidentialité de ses identifiants. Tout usage frauduleux du compte est strictement interdit.",
            'Contenu' => "L'ensemble des contenus publiés sur le site est protégé par les droits de propriété intellectuelle. Toute reproduction non autorisée est interdite.",
            'Responsabilité' => "Le FC Grande-Synthe décline toute responsabilité pour les dommages directs ou indirects liés à l'utilisation du site.",
            'Droit applicable' => "Les présentes CGU sont soumises au droit français. En cas de litige, les tribunaux compétents sont ceux de Dunkerque.",
        ]
    ],
    'cookies' => [
        'title' => 'Gestion des Cookies',
        'sections' => [
            'Qu\'est-ce qu\'un cookie ?' => "Un cookie est un petit fichier texte déposé sur votre terminal (ordinateur, tablette, smartphone) lors de la visite d'un site internet. Il permet au site de mémoriser des informations sur votre visite.",
            'Cookies utilisés' => "Notre site utilise :\n- Cookies techniques (nécessaires au fonctionnement)\n- Cookies de session (connexion utilisateur)\n- Cookies analytiques (mesure d'audience anonyme)",
            'Gestion de vos préférences' => "Vous pouvez à tout moment accepter ou refuser les cookies non essentiels via la bannière de consentement qui s'affiche lors de votre première visite, ou en modifiant les paramètres de votre navigateur.",
            'Durée de conservation' => "Les cookies de session sont supprimés à la fermeture du navigateur. Les cookies persistants ont une durée de vie maximale de 13 mois.",
            'Contact' => "Pour toute question relative aux cookies : contact@fcgrandesynthe.fr",
        ]
    ],
    'accessibilite' => [
        'title' => 'Accessibilité (RGAA)',
        'sections' => [
            'Engagement d\'accessibilité' => "Le FC Grande-Synthe s'engage à rendre son site internet accessible conformément aux recommandations du Référentiel Général d'Amélioration de l'Accessibilité (RGAA 4.1).",
            'Navigation au clavier' => "L'ensemble des fonctionnalités du site est accessible au clavier. L'ordre de tabulation est logique et permet une navigation cohérente.",
            'Textes alternatifs' => "Toutes les images informatives disposent d'un texte alternatif décrivant leur contenu. Les images décoratives sont ignorées par les technologies d'assistance.",
            'Structure sémantique' => "Le site utilise des balises HTML sémantiques correctes (header, nav, main, footer, section, article). La hiérarchie des titres est respectée.",
            'Contrastes' => "Les contrastes de couleurs respectent les ratios minimaux définis par le RGAA (4.5:1 pour le texte normal, 3:1 pour les grands textes).",
            'Formulaires' => "Tous les champs de formulaire sont associés à des labels explicites. Les messages d'erreur sont accessibles aux lecteurs d'écran.",
            'Contact accessibilité' => "Si vous rencontrez un obstacle à l'accessibilité, contactez-nous :\n- Email : contact@fcgrandesynthe.fr\n- Téléphone : 06 86 40 84 26",
            'Déclaration de conformité' => "Ce site est partiellement conforme aux critères du RGAA 4.1. Une amélioration continue est en cours.",
        ]
    ],
    'droit-image' => [
        'title' => "Droit à l'Image",
        'sections' => [
            'Principe général' => "Le droit à l'image est un droit fondamental qui protège toute personne contre la diffusion de son image sans son consentement.",
            'Photographies et vidéos' => "Le FC Grande-Synthe peut être amené à réaliser des photographies et vidéos lors des entraînements, matchs et événements du club.",
            'Consentement' => "La diffusion de l'image d'une personne nécessite son consentement préalable. Pour les mineurs, le consentement des deux parents ou tuteurs légaux est requis.",
            'Droit de retrait' => "Toute personne peut à tout moment retirer son consentement à la diffusion de son image en contactant le club à : contact@fcgrandesynthe.fr",
            'Utilisation des images' => "Les images collectées sont utilisées exclusivement dans le cadre des activités du club (site internet, réseaux sociaux, supports de communication du club).",
        ]
    ],
    'protection-mineurs' => [
        'title' => 'Politique de Protection des Mineurs',
        'sections' => [
            'Engagement du club' => "Le FC Grande-Synthe place la protection des mineurs au cœur de ses valeurs. Le club s'engage à assurer la sécurité physique, psychologique et numérique de tous les jeunes licenciés.",
            'Encadrement' => "Tous les éducateurs et bénévoles travaillant avec des mineurs sont titulaires d'un BAFA ou d'un diplôme sportif reconnu. Un contrôle du casier judiciaire est systématiquement effectué.",
            'Protection numérique' => "Aucune image de mineur n'est publiée sans le consentement écrit des deux parents ou tuteurs légaux. Les informations personnelles des mineurs sont traitées avec une vigilance renforcée.",
            'Signalement' => "Tout comportement inapproprié ou toute situation préoccupante concernant un mineur doit être immédiatement signalé au responsable du club ou aux autorités compétentes.",
            'Contact' => "Référent protection des mineurs :\nLOZANO PIERRE — contact@fcgrandesynthe.fr — 06 86 40 84 26",
        ]
    ],
];

$cfg = $configs[$legalPage] ?? $configs['cgu'];

trackVisit($legalPage);
$pageTitle        = $cfg['title'];
$breadcrumbCenter = strtoupper($cfg['title']);
$currentPage      = '';
$theme            = 'esport';
require_once __DIR__ . '/../partials/header.php';
?>
<div class="legal-page">
    <h1 style="font-size:22px;font-weight:800;color:var(--rouge-esport);margin-bottom:30px;text-align:center;"><?= h($cfg['title']) ?></h1>
    <?php foreach ($cfg['sections'] as $title => $content): ?>
        <div class="legal-section">
            <div class="legal-label"><?= h($title) ?></div>
            <div class="legal-content"><?= nl2br(h($content)) ?></div>
        </div>
    <?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
