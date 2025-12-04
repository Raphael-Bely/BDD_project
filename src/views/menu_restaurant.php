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
            --card-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            --hover-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
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
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* --- 2. HEADER GLOBAL --- */
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

        .user-info {
            font-size: 1.1rem;
            display: flex;
            align-items: center;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-actions a {
            text-decoration: none;
            font-weight: 600;
            color: var(--text-main);
            transition: color 0.2s;
            font-size: 0.95rem;
        }

        .header-actions a:hover {
            color: var(--accent-color);
        }

        .btn-logout {
            color: var(--danger-color) !important;
        }

        .btn-logout:hover {
            text-decoration: underline;
        }

        .badge-guest {
            background-color: #fff3cd;
            color: #856404;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            margin-left: 10px;
            border: 1px solid #ffeeba;
        }

        /* --- 3. LAYOUT GRID --- */
        .layout-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 30px;
            align-items: start;
        }

        @media (max-width: 900px) {
            .layout-grid {
                grid-template-columns: 1fr;
            }
        }

        /* --- 4. INFO RESTAURANT --- */
        .restaurant-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .restaurant-header h1 {
            margin: 0 0 5px 0;
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: -1px;
        }

        /* --- 5. Navigation Secondaire --- */
        .sub-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .btn-retour {
            text-decoration: none;
            color: var(--text-muted);
            font-size: 0.95rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            transition: color 0.2s;
        }

        .btn-retour:hover {
            color: var(--primary-color);
        }

        .btn-formules {
            background-color: #d35400;
            color: white;
            text-decoration: none;
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 4px 6px rgba(211, 84, 0, 0.2);
            transition: all 0.2s;
        }

        .btn-formules:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(211, 84, 0, 0.3);
        }

        /* --- 6. LISTE DES PLATS --- */
        .category-header {
            background-color: var(--bg-body);
            padding: 15px 0;
            margin-top: 20px;
            margin-bottom: 10px;
            position: sticky;
            top: 0;
            z-index: 10;
            color: var(--primary-color);
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 800;
            border-bottom: 2px solid var(--border-light);
        }

        .plat-item {
            background-color: white;
            border-radius: 16px;
            padding: 20px 25px;
            margin-bottom: 15px;
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

        .plat-info {
            flex: 1;
            padding-right: 20px;
        }

        .plat-name {
            margin: 0 0 5px 0;
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .badge-prop {
            background-color: #f3f4f6;
            color: #4b5563;
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 600;
            margin-left: 8px;
            vertical-align: middle;
        }

        .lien-ingredients {
            font-size: 0.85rem;
            color: #9ca3af;
            text-decoration: none;
            border-bottom: 1px dotted #9ca3af;
            transition: color 0.2s;
        }

        .lien-ingredients:hover {
            color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .plat-actions {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-shrink: 0;
        }

        .prix {
            font-weight: 800;
            color: var(--text-main);
            font-size: 1.25rem;
        }

        /* BOUTONS D'ACTION (+ et -) */
        .btn-action-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            line-height: 1;
            cursor: pointer;
            transition: all 0.2s ease;
            padding: 0;
            background-color: white;
        }

        .btn-add {
            border: 2px solid var(--border-light);
            color: var(--accent-color);
        }

        .btn-add:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
            transform: scale(1.1);
        }

        .btn-remove {
            border: 2px solid #fecaca;
            color: var(--danger-color);
        }

        .btn-remove:hover {
            background-color: #fee2e2;
            transform: scale(1.1);
        }

        .btn-disabled {
            border-color: #eee;
            color: #ddd;
            background-color: #f9f9f9;
            cursor: not-allowed;
            transform: none;
        }

        /* --- 7. SIDEBAR PANIER --- */
        .cart-sidebar {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            padding: 25px;
            border: 1px solid #f1f1f1;
            position: sticky;
            top: 20px;
        }

        .cart-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 15px;
            border-bottom: 2px solid #f1f1f1;
            padding-bottom: 10px;
        }

        .cart-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 0.95rem;
            color: var(--text-muted);
        }

        .cart-total-price {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--accent-color);
            text-align: right;
            margin: 15px 0;
        }

        .btn-checkout {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 10px;
            transition: background 0.2s;
        }

        .btn-checkout:hover {
            background-color: #000;
        }

        .btn-cancel-order {
            width: 100%;
            padding: 10px;
            background: white;
            border: 1px solid #fee2e2;
            color: #e74c3c;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-cancel-order:hover {
            background: #fef2f2;
            border-color: #e74c3c;
        }

        .empty-cart {
            text-align: center;
            color: #bdc3c7;
            font-style: italic;
            padding: 20px 0;
        }

        .empty-menu {
            text-align: center;
            padding: 50px;
            color: var(--text-muted);
            font-style: italic;
        }
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
                    <a href="login_invite.php" class="btn-invite">👤 Invité</a>
                    <a href="login.php">Se connecter</a>
                    <a href="create_account.php"
                        style="background:var(--primary-color); color:white; padding:8px 15px; border-radius:8px;">Créer un
                        compte</a>
                </div>
            <?php endif; ?>
        </div>

        <?php
        if (isset($_SESSION['client_id'])) {
            $client_id = $_SESSION['client_id'];
        } else {
            $client_id = null;
        }
        $current_resto_id = isset($restaurant_id) ? $restaurant_id : (isset($_GET['id']) ? $_GET['id'] : 0);

        // --- RÉCUPÉRATION DE LA COMMANDE EN COURS (UNE FOIS POUR TOUTE LA PAGE) ---
        $ma_commande = null;
        if ($client_id && $stmt_commande_by_restau) {
            $ma_commande = $stmt_commande_by_restau->fetch(PDO::FETCH_ASSOC);
        }
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
            <a href="formules.php?id=<?= $current_resto_id ?>" class="btn-formules">Voir les Formules 🍱</a>
        </div>

        <div class="layout-grid">

            <div class="menu-column">
                <div class="menu-list">
                    <?php
                    if ($stmt_plats->rowCount() > 0) {

                        $menu_organise = [];
                        while ($row = $stmt_plats->fetch(PDO::FETCH_ASSOC)) {
                            $cat = $row['nom_categorie'];
                            $id_plat = $row['item_id'];

                            if (!isset($menu_organise[$cat]))
                                $menu_organise[$cat] = [];
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

                        foreach ($menu_organise as $nom_categorie => $les_plats) {
                            echo "<div class='category-header'>" . htmlspecialchars($nom_categorie) . "</div>";

                            foreach ($les_plats as $plat) {
                                echo "<div class='plat-item'>";

                                // Infos
                                echo "<div class='plat-info'>";
                                echo "<div class='plat-name'>" . htmlspecialchars($plat['nom']);
                                if (!empty($plat['proprietes'])) {
                                    foreach ($plat['proprietes'] as $prop) {
                                        echo "<span class='badge-prop'>" . htmlspecialchars($prop) . "</span>";
                                    }
                                }
                                echo "</div>";
                                echo "<a class='lien-ingredients' href='composition.php?item_id={$plat['item_id']}'>Voir ingrédients</a>";
                                echo "</div>";

                                // Actions (PRIX + BOUTONS)
                                echo "<div class='plat-actions'>";
                                echo "<span class='prix'>" . number_format($plat['prix'], 2, ',', ' ') . " €</span>";

                                echo "<div class='btn-action-group'>";

                                // 1. Bouton MOINS (-) : Affiché seulement si commande active
                                if ($ma_commande) {
                                    echo "<form action='supprimerItemCommande.php' method='POST'>";
                                    echo "<input type='hidden' name='commande_id' value='" . $ma_commande['commande_id'] . "'>";
                                    echo "<input type='hidden' name='item_id' value='" . $plat['item_id'] . "'>";
                                    echo "<input type='hidden' name='restaurant_id' value='" . $current_resto_id . "'>";
                                    echo "<button type='submit' class='btn-circle btn-remove' title='Retirer'>-</button>";
                                    echo "</form>";
                                } else {
                                    // Désactivé si pas de commande
                                    echo "<button class='btn-circle btn-disabled' disabled>-</button>";
                                }

                                // 2. Bouton PLUS (+)
                                echo "<form action='ajouter_item.php' method='POST'>";
                                echo "<input type='hidden' name='item_id' value='" . $plat['item_id'] . "'>";
                                echo "<input type='hidden' name='restaurant_id' value='" . $current_resto_id . "'>";
                                if ($client_id) {
                                    echo "<button type='submit' class='btn-circle btn-add' title='Ajouter'>+</button>";
                                } else {
                                    echo "<button type='button' class='btn-circle btn-disabled' disabled>+</button>";
                                }
                                echo "</form>";

                                echo "</div>"; // Fin btn-action-group
                                echo "</div>"; // Fin plat-actions
                    
                                // Complements section
                                echo "<div style='margin-top: 10px; font-size: 0.85rem;'>";
                                echo "<button class='complement-toggle' data-item-id='" . $plat['item_id'] . "' style='background: none; border: none; color: #666; cursor: pointer; text-decoration: underline; padding: 0; font-size: inherit;'>Voir compléments (sauces, accompagnements)</button>";
                                echo "</div>";

                                echo "</div>"; // Fin plat-item
                            }
                        }

                    } else {
                        echo "<div class='empty-menu'>Aucun plat disponible pour le moment.</div>";
                    }
                    ?>
                </div>
            </div>

            <div class="cart-sidebar">
                <div class="cart-title">🛒 Votre commande</div>

                <?php
                if ($client_id) {
                    if ($ma_commande) {
                        // Commande EXISTE
                        echo "<div class='cart-info-row'>";
                        echo "<span>Commande #</span>";
                        echo "<strong>" . htmlspecialchars($ma_commande['commande_id']) . "</strong>";
                        echo "</div>";

                        echo "<div class='cart-info-row'>";
                        echo "<span>Statut</span>";
                        echo "<span style='color:#27ae60; font-weight:bold;'>En cours</span>";
                        echo "</div>";

                        echo "<div class='cart-total-price'>";
                        echo number_format($ma_commande['prix_total_remise'], 2, ',', ' ') . " €";
                        echo "</div>";

                        echo "<a href='commande.php?client_id={$client_id}' class='btn-checkout'>Voir le détail & Payer</a>";

                        echo "<form action='annuler_commande.php' method='POST' onsubmit=\"return confirm('Voulez-vous vraiment annuler cette commande ?');\">";
                        echo "<input type='hidden' name='commande_id' value='" . $ma_commande['commande_id'] . "'>";
                        echo "<button type='submit' class='btn-cancel-order'>🗑️ Annuler la commande</button>";
                        echo "</form>";

                    } else {
                        // Commande INEXISTANTE
                        echo "<div class='empty-cart'>";
                        echo "<p>Votre panier est vide ici.</p>";
                        echo "<span style='font-size:2rem;'>🍽️</span>";
                        echo "</div>";
                    }
                } else {
                    // PAS CONNECTÉ
                    echo "<div class='empty-cart'>";
                    echo "<a href='login.php' style='color:#2c3e50; font-weight:bold;'>Connectez-vous</a> pour commander.";
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <script>
            document.querySelectorAll('.complement-toggle').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const itemId = this.dataset.itemId;
                    const platItem = this.closest('.plat-item');

                    // Remove existing complement list if visible
                    const existingList = platItem.querySelector('.complement-list');
                    if (existingList) {
                        existingList.remove();
                        return;
                    }

                    // Fetch complements
                    fetch('getComplements.php?item_id=' + itemId)
                        .then(response => response.json())
                        .then(data => {
                            const list = document.createElement('div');
                            list.className = 'complement-list';
                            list.style.cssText = 'margin-top: 10px; padding: 10px; background: #f5f5f5; border-radius: 8px; font-size: 0.9rem;';

                            if (data.length === 0) {
                                list.innerHTML = '<em style="color: #999;">Pas de compléments disponibles</em>';
                            } else {
                                let html = '<div style="font-weight: 600; margin-bottom: 8px;">Compléments :</div>';
                                data.forEach(complement => {
                                    html += '<div style="padding: 5px 0; border-bottom: 1px solid #ddd;">' +
                                        htmlEscape(complement.nom) + ' <strong style="float:right;">+' +
                                        parseFloat(complement.prix).toFixed(2) + '€</strong></div>';
                                });
                                list.innerHTML = html;
                            }

                            platItem.appendChild(list);
                        })
                        .catch(err => console.error('Erreur:', err));
                });
            });

            function htmlEscape(str) {
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;');
            }
        </script>

    </div>
    </div>

</body>

</html>