<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte du Restaurant</title>
    <style>
        /* --- 1. Variables & Base --- */
        :root {
            --bg-body: #f8f9fa;
            --bg-card: #ffffff;
            --text-main: #1a1a1a;
            --text-muted: #6c757d;
            --primary-color: #2c3e50;
            --accent-color: #27ae60;  /* Vert sophistiqué */
            --formula-color: #d35400; /* Orange brûlé */
            --border-light: #f1f1f1;
            --shadow-soft: 0 4px 15px rgba(0,0,0,0.03);
            --font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--bg-body);
            color: var(--text-main);
            margin: 0;
            padding: 40px 20px;
            line-height: 1.5;
        }

        /* --- 2. Conteneur Principal --- */
        .menu-wrapper {
            max-width: 800px;
            margin: 0 auto;
            background: var(--bg-card);
            border-radius: 16px;
            box-shadow: var(--shadow-soft);
            overflow: hidden; /* Important pour les coins arrondis */
        }

        /* --- 3. Header du Restaurant --- */
        .restaurant-header {
            padding: 40px 40px 20px 40px;
            text-align: center;
            border-bottom: 1px solid var(--border-light);
        }

        .restaurant-header h1 {
            margin: 0 0 10px 0;
            font-size: 2rem;
            letter-spacing: -1px;
        }

        /* --- 4. Barre d'actions --- */
        .actions-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background-color: #fafafa;
            border-bottom: 1px solid var(--border-light);
            flex-wrap: wrap;
            gap: 15px;
        }

        .btn-retour {
            text-decoration: none;
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            transition: color 0.2s;
        }
        .btn-retour:hover { color: var(--primary-color); }

        .btn-panier {
            margin-left: 15px;
            text-decoration: none;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .btn-formules {
            background-color: var(--formula-color);
            color: white;
            text-decoration: none;
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 4px 6px rgba(211, 84, 0, 0.2);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-formules:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(211, 84, 0, 0.3);
        }

        /* --- 5. Liste des Items --- */
        .menu-list { padding: 0; }

        /* Titre de catégorie collant (Sticky Header) */
        .category-header {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(5px);
            padding: 15px 40px;
            margin: 0;
            position: sticky; /* C'est ça qui fait l'effet magique */
            top: 0;
            z-index: 10;
            border-bottom: 1px solid var(--border-light);
            color: var(--primary-color);
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 800;
        }

        .plat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 25px 40px;
            border-bottom: 1px solid var(--border-light);
            transition: background-color 0.2s;
        }

        .plat-item:last-child { border-bottom: none; }
        .plat-item:hover { background-color: #fcfcfc; }

        /* Infos Plat (Gauche) */
        .plat-info {
            flex: 1;
            padding-right: 20px;
        }

        .plat-name {
            margin: 0 0 6px 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .badge-prop {
            background-color: #f0f2f5;
            color: #57606a;
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 600;
            margin-left: 8px;
            vertical-align: middle;
        }

        .lien-ingredients {
            font-size: 0.85rem;
            color: #b0b8c1;
            text-decoration: none;
            border-bottom: 1px dotted #b0b8c1;
        }
        .lien-ingredients:hover { color: var(--text-main); border-color: var(--text-main); }

        /* Actions Plat (Droite) */
        .plat-actions {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-shrink: 0;
        }

        .prix {
            font-weight: 700;
            color: var(--text-main);
            font-size: 1.2rem;
        }

        /* Bouton "+" Rond */
        .btn-add {
            background-color: var(--bg-card);
            color: var(--accent-color);
            border: 2px solid var(--border-light);
            width: 40px;
            height: 40px;
            border-radius: 50%; /* Cercle parfait */
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.4rem; /* Taille du + */
            line-height: 1;
            transition: all 0.2s ease;
            padding: 0; /* Important pour centrer le + */
        }

        .btn-add:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
            transform: scale(1.1);
        }

        .btn-add:disabled {
            border-color: #eee;
            color: #ccc;
            background-color: #f9f9f9;
            cursor: not-allowed;
            transform: none;
        }

        .not-connected-msg {
            font-size: 0.7rem;
            color: #e74c3c;
            display: block;
            text-align: right;
            margin-top: 5px;
        }
    </style>
</head>
<body>

    <?php
    // Logique PHP inchangée
    if (isset($_SESSION['client_id'])) {
        $client_id = $_SESSION['client_id'];
    } else {
        $client_id = null;
    }

    $current_resto_id = isset($restaurant_id) ? $restaurant_id : (isset($_GET['id']) ? $_GET['id'] : 0);
    ?>

<div class="menu-wrapper">
    
    <div class="restaurant-header">
        <?php if ($restaurant_info): ?>
            <h1><?= htmlspecialchars($restaurant_info['nom']) ?></h1>
            <span style="color: #999;">Découvrez notre carte</span>
        <?php else: ?>
            <h1>Restaurant introuvable</h1>
        <?php endif; ?>
    </div>

    <div class="actions-bar">
        <div>
            <a href="index.php" class="btn-retour">← Retour à la liste des restaurants</a>
            <?php if (isset($_SESSION['client_id'])): ?>
                <div style="margin-top:10px;">
                    <a href="commande.php?client_id=<?= $client_id ?>" class="btn-panier">🛒 Panier</a>
                    <a href="suivi.php">📦 Suivi</a>
                    <?php if (!isset($_SESSION['is_guest']) || !$_SESSION['is_guest']): ?>
                        <a href="historique.php" class="btn-panier">📋 Historique</a>
                    <?php endif; ?>    
                </div>
            <?php endif; ?>
        </div>
        
        <a href="formules.php?id=<?= $current_resto_id ?>" class="btn-formules">
            Voir les Formules 🍱
        </a>
    </div>

    <div class="menu-list">
        <?php
        if ($stmt_plats->rowCount() > 0) {
            
            // --- TRI DES DONNÉES ---
            $menu_organise = [];
            while ($row = $stmt_plats->fetch(PDO::FETCH_ASSOC)) {
                $cat = $row['nom_categorie'];
                $id_plat = $row['item_id'];
                
                if (!isset($menu_organise[$cat])) $menu_organise[$cat] = [];
                if (!isset($menu_organise[$cat][$id_plat])) {
                    $menu_organise[$cat][$id_plat] = [
                        'nom' => $row['nom'],
                        'prix' => $row['prix'],
                        'item_id' => $row['item_id'],
                        'proprietes' => [] 
                    ];
                }
                if (!empty($row['nom_propriete'])) {
                    $menu_organise[$cat][$id_plat]['proprietes'][] = $row['nom_propriete'];
                }
            }

            // --- AFFICHAGE ---
            foreach ($menu_organise as $nom_categorie => $les_plats) {
                // Titre Catégorie (Sticky) - J'ai mis la bonne classe CSS ici
                echo "<div class='category-header'>" . htmlspecialchars($nom_categorie) . "</div>";

                foreach ($les_plats as $plat) {
                    echo "<div class='plat-item'>";
                        
                        // Gauche : Infos
                        echo "<div class='plat-info'>";
                            echo "<div class='plat-name'>" . htmlspecialchars($plat['nom']);
                            
                            // Badges propriétés
                            if (!empty($plat['proprietes'])) {
                                foreach ($plat['proprietes'] as $prop) {
                                    echo "<span class='badge-prop'>" . htmlspecialchars($prop) . "</span>";
                                }
                            }
                            echo "</div>"; // Fin plat-name

                            echo "<a class='lien-ingredients' href='composition.php?item_id={$plat['item_id']}'>Voir ingrédients</a>";
                        echo "</div>";

                        // Droite : Prix + Action
                        echo "<div class='plat-actions'>";
                            echo "<span class='prix'>" . number_format($plat['prix'], 2, ',', ' ') . " €</span>";

                            echo "<form action='ajouter_item.php' method='POST' style='margin:0;'>";
                                echo "<input type='hidden' name='item_id' value='" . $plat['item_id'] . "'>";
                                echo "<input type='hidden' name='restaurant_id' value='" . (isset($restaurant_id) ? $restaurant_id : '') . "'>";

                                if ($client_id) {
                                    // Bouton rond avec juste un +
                                    echo "<button type='submit' class='btn-add' title='Ajouter'>+</button>";
                                } else {
                                    echo "<button type='button' class='btn-add' disabled>+</button>";
                                }
                            echo "</form>";
                        echo "</div>";

                    echo "</div>"; // fin plat-item
                }
            }

        } else {
            echo "<div style='padding:40px; text-align:center; color:#999;'>Aucun plat disponible pour le moment.</div>";
        }
        ?>
    </div>
</div>

</body>
</html>