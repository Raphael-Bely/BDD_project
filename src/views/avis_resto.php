<?php
// Utilise le contrôleur avis_restaurant.php
// Informations transmises : id du restaurant (pour savoir quels avis récupérer)
// Informations importés : nom du restaurant, tableau de commentaires, id du restaurant (pour le lien de retour)
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Avis - <?= htmlspecialchars($restoInfos['nom']) ?></title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8f9fa; padding: 30px; max-width: 800px; margin: 0 auto; }
        .header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px; }
        .btn-retour { text-decoration: none; color: #555; font-weight: bold; border: 1px solid #ddd; padding: 8px 15px; border-radius: 20px; background: white; }
        .btn-retour:hover { background: #eee; }
        
        .avis-card { background: white; padding: 20px; border-radius: 10px; margin-bottom: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .avis-header { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 0.9em; color: #777; }
        .client-name { font-weight: bold; color: #2c3e50; }
        .stars { color: #f1c40f; letter-spacing: 2px; }
        .contenu { line-height: 1.5; color: #333; font-style: italic; }
        .empty-msg { text-align: center; color: #888; margin-top: 50px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Avis : <?= htmlspecialchars($restoInfos['nom']) ?></h1>
        <a href="index.php" class="btn-retour">← Retour aux restaurants</a>
    </div>

    <?php if (empty($avis_list)): ?>
        <div class="empty-msg">
            <p>Aucun commentaire pour le moment.</p>
            <p>Soyez le premier à donner votre avis !</p>
        </div>
    <?php else: ?>
        <?php foreach ($avis_list as $avis): ?>
            <div class="avis-card">
                <div class="avis-header">
                    <span class="client-name"><?= htmlspecialchars($avis['nom_client']) ?></span>
                    <span>
                        <?php 
                        $date = new DateTime($avis['date_commentaire']);
                        echo $date->format('d/m/Y'); 
                        ?>
                    </span>
                </div>
                
                <div class="stars">
                    <?php
                    for($i=1; $i<=5; $i++) {
                        echo ($i <= $avis['note']) ? "★" : "<span style='color:#ddd'>☆</span>";
                    }
                    ?>
                    <span style="color:#333; font-size:0.8em; font-weight:bold; margin-left:5px;">
                        (<?= $avis['note'] ?>/5)
                    </span>
                </div>

                <p class="contenu">"<?= nl2br(htmlspecialchars($avis['contenu'])) ?>"</p>
            </div>
        <?php endforeach; ?>

    <?php endif; ?>

</body>
</html>
