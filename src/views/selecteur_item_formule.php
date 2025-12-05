<?php
// Contrôleur utilisé : configurer_formule.php
// Informations transmises (Vue -> Contrôleur via GET - Initialisation) :
// - action='init', formule_id : Déclencheurs pour démarrer une nouvelle configuration de formule depuis le menu.

// Informations transmises (Vue -> Contrôleur via POST - Navigation) :
// - item_id : L'identifiant de l'item choisi par le client pour l'étape en cours (ex: l'ID de la 'Salade César' pour l'étape 'Entrée').

// Informations importées (Contrôleur -> Vue) :
// - formule_info (Session) : Tableau contenant l'état global du wizard (étapes restantes, étape courante, choix déjà effectués).
// - current_categorie : Les détails de la catégorie à afficher pour l'étape actuelle (ex: "Boisson").
// - items_disponibles : La liste des plats éligibles pour cette étape (ex: liste des boissons du restaurant).
// - client_id (Session) : Utilisé lors de la finalisation pour lier la commande au client.
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Composer votre Menu</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }

        .wizard-container {
            background: white;
            width: 100%;
            max-width: 700px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            text-align: center;
        }

        .progress-container {
            background-color: #eee;
            border-radius: 10px;
            height: 10px;
            width: 100%;
            margin-bottom: 25px;
            overflow: hidden;
        }

        .progress-bar {
            background-color: #e67e22; 
            height: 100%;
            transition: width 0.4s ease;
        }

        h2 { color: #2c3e50; margin-bottom: 5px; }
        .instruction { font-size: 1.2em; color: #7f8c8d; margin-bottom: 30px; }
        .categorie-highlight { color: #e67e22; font-weight: bold; }

        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
        }

        .item-card-btn {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            width: 100%; 
        }

        .item-card-btn:hover {
            border-color: #e67e22;
            background-color: #fff8f0;
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .item-nom {
            font-weight: bold;
            color: #333;
            font-size: 1.1em;
            margin-bottom: 5px;
        }

        .btn-cancel {
            display: inline-block;
            margin-top: 30px;
            color: #999;
            text-decoration: none;
            font-size: 0.9em;
            border-bottom: 1px dashed #999;
        }
        .btn-cancel:hover { color: #c0392b; border-color: #c0392b; }

    </style>
</head>
<body>

<div class="wizard-container">
    
    <?php 
        $step_index = $formule_info['current_step'];

        $total_etapes = count($formule_info['etapes']);
        
        $current_display = $step_index + 1;
        
        $percent = ($total_etapes > 0) ? ($step_index / $total_etapes) * 100 : 0;
    ?>

    <div class="progress-container">
        <div class="progress-bar" style="width: <?= $percent ?>%;"></div>
    </div>

    <h2>Étape <?= $current_display ?> / <?= $total_etapes ?></h2>
    <p class="instruction">
        Veuillez choisir votre : 
        <span class="categorie-highlight"><?= htmlspecialchars($current_categorie['nom_categorie']) ?></span>
    </p>

    <div class="items-grid">
        <?php if(empty($items_disponibles)): ?>
            
            <div style="grid-column: 1 / -1; color: #c0392b;">
                <p>⚠️ Oups ! Aucun item n'est disponible pour cette catégorie dans ce restaurant.</p>
                <p>Veuillez contacter le restaurant ou annuler.</p>
            </div>

        <?php else: ?>
            
            <?php foreach($items_disponibles as $item): ?>
                <form method="POST" action="configurer_formule.php">
                    <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>">
                    
                    <button type="submit" class="item-card-btn">
                        <span class="item-nom"><?= htmlspecialchars($item['nom']) ?></span>
                    </button>
                </form>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>

    <a href="menu.php?id=<?= $formule_info['restaurant_id'] ?>" class="btn-cancel">
        Annuler et retourner au menu
    </a>

</div>

</body>
</html>