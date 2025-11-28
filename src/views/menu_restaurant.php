<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte du Restaurant</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* --- 1. Variables & Base --- */
        :root {
            --bg-body: #f8f9fa;
            --text-main: #2c3e50;
            --text-muted: #6c757d;
            --primary-color: #1a1a1a;
            --accent-color: #e67e22;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --border-light: #e5e7eb;
            --card-shadow: 0 2px 8px rgba(0,0,0,0.04);
            --hover-shadow: 0 8px 16px rgba(0,0,0,0.1);
            --font-family: 'Inter', system-ui, sans-serif;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--bg-body);
            color: var(--text-main);
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* --- 2. HEADER GLOBAL (UNIFIÉ) --- */
        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px 30px;
            background: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            flex-wrap: wrap;
            gap: 15px;
        }

        .user-info { font-size: 1.1rem; display: flex; align-items: center; }
        
        .header-actions { display: flex; align-items: center; gap: 20px; }
        .header-actions a {
            text-decoration: none;
            font-weight: 600;
            color: var(--text-main);
            transition: color 0.2s;
            font-size: 0.95rem;
        }
        .header-actions a:hover { color: var(--accent-color); }

        .btn-logout { color: var(--danger-color) !important; }
        .btn-logout:hover { text-decoration: underline; }

        .badge-guest {
            background-color: #fff3cd; color: #856404; padding: 4px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; margin-left: 10px; border: 1px solid #ffeeba;
        }

        /* --- 3. Header du Restaurant --- */
        .restaurant-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .restaurant-header h1 { margin: 0 0 5px 0; font-size: 2.2rem; font-weight: 800; letter-spacing: -1px; }

        /* --- 4. Barre d'actions Secondaire (Retour / Formules) --- */
        .sub-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .btn-retour {
            text-decoration: none; color: var(--text-muted); font-size: 0.95rem; font-weight: 600; display: flex; align-items: center; transition: color 0.2s;
        }
        .btn-retour:hover { color: var(--primary-color); }

        .btn-formules {
            background-color: #d35400; /* Orange plus foncé */
            color: white; text-decoration: none; padding: 10px 24px; border-radius: 50px; font-weight: 600; font-size: 0.95rem; box-shadow: 0 4px 6px rgba(211, 84, 0, 0.2); transition: all 0.2s;
        }
        .btn-formules:hover { transform: translateY(-2px); box-shadow: 0 6px 12px rgba(211, 84, 0, 0.3); }

        /* --- 5. LISTE DES PLATS (DESIGN AMÉLIORÉ) --- */
        
        /* Sticky Header Catégorie */
        .category-header {
            background-color: var(--bg-body); /* Fond identique au body pour transparence visuelle */
            padding: 15px 0;
            margin-top: 20px;
            margin-bottom: 10px;
            position: sticky; top: 0; z-index: 10;
            color: var(--primary-color);
            font-size: 1.2rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 800;
            border-bottom: 2px solid var(--border-light);
        }

        /* ITEM CARD (La clé de la distinction) */
        .plat-item {
            background-color: white;
            border-radius: 16px;
            padding: 20px 25px;
            margin-bottom: 15px; /* Espace entre chaque item */
            border: 1px solid var(--border-light);
            box-shadow: var(--card-shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.2s ease;
        }

        .plat-item:hover {
            transform: translateY(-3px);
            box-shadow: var(--hover-shadow);
            border-color: #d1d5db;
        }

        /* Infos Plat (Gauche) */
        .plat-info { flex: 1; padding-right: 20px; }

        .plat-name { margin: 0 0 5px 0; font-size: 1.15rem; font-weight: 700; color: var(--text-main); }

        .badge-prop {
            background-color: #f3f4f6; color: #4b5563; font-size: 0.75rem; padding: 4px 10px; border-radius: 6px; font-weight: 600; margin-left: 8px; vertical-align: middle;
        }

        .lien-ingredients {
            font-size: 0.85rem; color: #9ca3af; text-decoration: none; border-bottom: 1px dotted #9ca3af; transition: color 0.2s;
        }
        .lien-ingredients:hover { color: var(--accent-color); border-color: var(--accent-color); }

        /* Actions Plat (Droite) */
        .plat-actions { display: flex; align-items: center; gap: 20px; flex-shrink: 0; }

        .prix { font-weight: 800; color: var(--text-main); font-size: 1.25rem; }

        /* Bouton "+" */
        .btn-add {
            background-color: white;
            color: var(--accent-color);
            border: 2px solid #e5e7eb;
            width: 44px; height: 44px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 1.5rem; line-height: 1;
            transition: all 0.2s ease; padding: 0;
        }
        .btn-add:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
            transform: scale(1.1);
        }
        .btn-add:disabled { border-color: #eee; color: #ddd; background-color: #f9f9f9; cursor: not-allowed; transform: none; }

        .not-connected-msg { font-size: 0.7rem; color: var(--danger-color); display: block; text-align: right; margin-top: 5px; }
        
        /* Empty State */
        .empty-menu { text-align: center; padding: 50px; color: var(--text-muted); font-style: italic; }

    </style>
</head>
<body>

<div class="container">

    <div class="header-bar">
        <?php if (isset($_SESSION['client_id'])): ?>
            <div class="user-info">
                Bonjour, <strong>&nbsp;<?= htmlspecialchars($_SESSION['client_nom']) ?></strong> !
                <?php if (isset($_SESSION['is_guest']) && $_SESSION['is_guest']): ?>
                    <span class="badge-guest">Mode Invité</span>
                <?php endif; ?>
            </div>

            <div class="header-actions">
                <a href="index.php">Restaurants</a>
                <a href="commande.php?client_id=<?= $_SESSION['client_id'] ?>">Panier</a>
                <a href="suivi.php">Suivi</a>
                
                <?php if (!isset($_SESSION['is_guest']) || !$_SESSION['is_guest']): ?>
                    <a href="historique.php">Historique</a>
                <?php endif; ?>

                <a href="logout.php" class="btn-logout">Se déconnecter</a>
            </div>
        <?php else: ?>
            <div class="header-actions">
                <a href="login.php">Se connecter</a>
                <a href="create_account.php" style="background:var(--primary-color); color:white; padding:8px 15px; border-radius:8px;">Créer un compte</a>
            </div>
        <?php endif; ?>
    </div>

    <?php
    // Logique récupération ID
    if (isset($_SESSION['client_id'])) {
        $client_id = $_SESSION['client_id'];
    } else {
        $client_id = null;
    }
    $current_resto_id = isset($restaurant_id) ? $restaurant_id : (isset($_GET['id']) ? $_GET['id'] : 0);
    ?>

    <div class="restaurant-header">
        <?php if ($restaurant_info): ?>
            <h1><?= htmlspecialchars($restaurant_info['nom']) ?></h1>
            <span style="color: #666; font-size: 1.1rem;">Découvrez notre carte gourmande</span>
        <?php else: ?>
            <h1>Restaurant introuvable</h1>
        <?php endif; ?>
    </div>

    <div class="sub-nav">
        <a href="index.php" class="btn-retour">← Retour aux restaurants</a>
        
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
                        'nom' => $row['nom'], 'prix' => $row['prix'], 'item_id' => $row['item_id'], 'proprietes' => [] 
                    ];
                }
                if (!empty($row['nom_propriete'])) {
                    $menu_organise[$cat][$id_plat]['proprietes'][] = $row['nom_propriete'];
                }
            }

            // --- AFFICHAGE ---
            foreach ($menu_organise as $nom_categorie => $les_plats) {
                // Titre Catégorie Sticky
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
                            echo "</div>";

                            echo "<a class='lien-ingredients' href='composition.php?item_id={$plat['item_id']}'>Voir ingrédients</a>";
                        echo "</div>";

                        // Droite : Prix + Action
                        echo "<div class='plat-actions'>";
                            echo "<span class='prix'>" . number_format($plat['prix'], 2, ',', ' ') . " €</span>";

                            echo "<form action='ajouter_item.php' method='POST' style='margin:0;'>";
                                echo "<input type='hidden' name='item_id' value='" . $plat['item_id'] . "'>";
                                echo "<input type='hidden' name='restaurant_id' value='" . (isset($restaurant_id) ? $restaurant_id : '') . "'>";

                                if ($client_id) {
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
            echo "<div class='empty-menu'>Aucun plat disponible pour le moment.</div>";
        }
        ?>
    </div>
</div>

</body>
</html>