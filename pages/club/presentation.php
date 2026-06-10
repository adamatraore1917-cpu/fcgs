<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
trackVisit('presentation');
$pageTitle        = 'Présentation du Club';
$breadcrumbCenter = 'NOTRE CLUB';
$currentPage      = 'club';
require_once __DIR__ . '/../../partials/header.php';
?>

<div style="max-width:1000px;margin:0 auto;padding:40px 20px;">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:30px;margin-bottom:40px;align-items:center;">
        <div>
            <h1 style="font-size:28px;font-weight:900;color:var(--bleu-fc);text-transform:uppercase;margin-bottom:16px;">FC Grande-Synthe</h1>
            <p style="font-size:15px;color:#444;line-height:1.8;margin-bottom:14px;">
                Fondé dans la commune de Grande-Synthe (Nord, 59), le FC Grande-Synthe est un club de football amateur affilié à la Ligue des Hauts-de-France et au District des Flandres.
            </p>
            <p style="font-size:15px;color:#444;line-height:1.8;margin-bottom:14px;">
                Le club accueille joueurs de tous âges — du baby-foot aux vétérans — dans un esprit de convivialité, de progression et de respect. Fort de nombreuses équipes, le FCGS s'est également distingué en lançant un projet esport pionnier dans la région.
            </p>
            <p style="font-size:15px;color:#444;line-height:1.8;">
                Notre ambition : allier football traditionnel et pratiques numériques pour offrir à nos licenciés une expérience sportive complète, inclusive et tournée vers l'avenir.
            </p>
        </div>
        <div>
            <img src="<?= ASSETS_URL ?>/images/logos/logo-foot.jpg" alt="Logo FC Grande-Synthe"
                 style="width:220px;height:220px;object-fit:contain;margin:0 auto;display:block;border-radius:50%;box-shadow:0 8px 30px rgba(0,0,0,0.15);">
        </div>
    </div>

    <!-- CHIFFRES CLÉS -->
    <div style="background:var(--bleu-fc);border-radius:12px;padding:30px;margin-bottom:40px;">
        <h2 style="color:white;text-align:center;font-size:20px;font-weight:800;text-transform:uppercase;margin-bottom:24px;">Le Club en Chiffres</h2>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;text-align:center;">
            <?php
            $chiffres = [
                ['valeur'=>'300+','label'=>'Licenciés'],
                ['valeur'=>'10','label'=>'Équipes'],
                ['valeur'=>'2','label'=>'Complexes sportifs'],
                ['valeur'=>'1','label'=>'Salle Esport'],
            ];
            foreach ($chiffres as $c):
            ?>
                <div>
                    <div style="font-size:36px;font-weight:900;color:#f0c030;"><?= h($c['valeur']) ?></div>
                    <div style="font-size:13px;color:#b0c8f0;text-transform:uppercase;letter-spacing:1px;"><?= h($c['label']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- VALEURS -->
    <h2 style="font-size:22px;font-weight:800;color:var(--bleu-fc);text-transform:uppercase;margin-bottom:20px;text-align:center;">Nos Valeurs</h2>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:40px;">
        <?php
        $valeurs = [
            ['icon'=>'team.png',   'titre'=>'Esprit d\'équipe',   'texte'=>'La cohésion et la solidarité sont au cœur de notre projet sportif.'],
            ['icon'=>'football.png','titre'=>'Passion du foot',   'texte'=>'Un amour sincère pour le football, du plus jeune au plus expérimenté.'],
            ['icon'=>'reward.png', 'titre'=>'Ambition',           'texte'=>'Se dépasser, progresser et viser l\'excellence à chaque entraînement.'],
        ];
        foreach ($valeurs as $v):
        ?>
            <div style="background:var(--gris-clair);border-radius:8px;padding:20px;text-align:center;">
                <img src="<?= ASSETS_URL ?>/images/icons/<?= h($v['icon']) ?>" alt="" style="height:40px;margin:0 auto 12px;">
                <h3 style="font-size:15px;font-weight:700;color:var(--bleu-fc);margin-bottom:8px;"><?= h($v['titre']) ?></h3>
                <p style="font-size:13px;color:#666;line-height:1.6;"><?= h($v['texte']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- INFOS PRATIQUES -->
    <div style="background:#f0f4ff;border-radius:8px;padding:24px;">
        <h2 style="font-size:18px;font-weight:800;color:var(--bleu-fc);margin-bottom:16px;">Informations pratiques</h2>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;font-size:14px;">
            <div><strong>Adresse :</strong><br>Club House - Boulevard des Fédérés<br>59760 Grande-Synthe</div>
            <div><strong>Contact :</strong><br>📧 contact@fcgrandesynthe.fr<br>📞 06 86 40 84 26</div>
            <div><strong>Site FFF :</strong><br><a href="https://epreuves.fff.fr" target="_blank" style="color:var(--bleu-fc);">epreuves.fff.fr</a></div>
            <div><strong>Rejoindre le club :</strong><br><a href="<?= SITE_URL ?>/signup" style="color:var(--bleu-fc);font-weight:700;">→ S'inscrire au club</a></div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../partials/footer.php'; ?>
