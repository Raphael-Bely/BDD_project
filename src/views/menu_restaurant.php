<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Menu du Restaurant</title>
    <style>
        .categorie-titre {
            background-color: #f8f9fa;
            padding: 10px;
            border-left: 5px solid #2c3e50;
            margin-top: 20px;
            color: #2c3e50;
        }
        .plat-item { 
            border-bottom: 1px solid #eee; 
            padding: 15px 0; 
        }
        .plat-item h4 { margin: 0; display: inline-block; }
        .plat-item .prix { font-weight: bold; float: right; color: #e67e22; }
        
        .badge-prop {
            background-color: #e1f5fe;
            color: #0277bd;
            font-size: 0.8em;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 5px;
            vertical-align: middle;
            font-weight: normal;
        }
    </style>
</head>
<body>
    <?php
        if ($restaurant_info) {
            echo "<h1> Menu de " . htmlspecialchars($restaurant_info['nom']) ."</h1>"; 
        } else {
            echo "<h1> Restaurant non trouvé </h1>";
        }
    ?>
    <a href="index.php">Retour à la liste des restaurants</a>

    <div class="menu-container">
        <?php
        if ($stmt_plats->rowCount() > 0) {
            
            $menu_organise = [];

            while ($row = $stmt_plats->fetch(PDO::FETCH_ASSOC)) {
                $cat = $row['nom_categorie'];
                $id_plat = $row['item_id'];
                
                if (!isset($menu_organise[$cat])) {
                    $menu_organise[$cat] = [];
                }

                if (!isset($menu_organise[$cat][$id_plat])) {
                    $menu_organise[$cat][$id_plat] = [
                        'nom' => $row['nom'],
                        'prix' => $row['prix'],
                        'item_id' => $row['item_id'],
                        'proprietes' => [] // Liste vide pour commencer
                    ];
                }

                if (!empty($row['nom_propriete'])) {
                    $menu_organise[$cat][$id_plat]['proprietes'][] = $row['nom_propriete'];
                }
            }

            foreach ($menu_organise as $nom_categorie => $les_plats) {
                echo "<h2 class='categorie-titre'>" . htmlspecialchars($nom_categorie) . "</h2>";

                foreach ($les_plats as $plat) {
                    echo "<div class='plat-item'>";
                        echo "<span class='prix'>{$plat['prix']} €</span>";
                        
                        echo "<h4>";
                            echo "<p>" . htmlspecialchars($plat['nom']) . "</p>";
                            echo "<a href='composition.php?item_id={$plat['item_id']}'> ingrédients </a>";
                            
                            if (!empty($plat['proprietes'])) {
                                foreach ($plat['proprietes'] as $prop) {
                                    echo "<span class='badge-prop'>" . htmlspecialchars($prop) . "</span>";
                                }
                            }
                        echo "</h4>";
                    echo "</div>";
                }
            }

        } else {
            echo "<p>Aucun plat n'a été trouvé pour ce restaurant.</p>";
        }
        ?>
    </div>

</body>
</html>