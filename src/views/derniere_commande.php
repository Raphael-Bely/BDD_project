<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier</title>
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
            --info-bg: #e3f2fd;
            --info-text: #0d47a1;
            --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --hover-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
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

        /* --- 2. HEADER UNIFIÉ (Comme sur les autres pages) --- */
        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
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
        .header-actions a:hover { color: var(--accent-color); }
        .header-actions a.active { color: var(--accent-color); }

        .btn-logout { color: var(--danger-color) !important; }
        .btn-logout:hover { text-decoration: underline; }

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

        /* --- 3. Titre --- */
        .section-title {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 30px;
            color: var(--text-main);
            border-left: 5px solid var(--accent-color);
            padding-left: 15px;
        }

        /* --- 4. Carte Panier --- */
        .commande-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: var(--card-shadow);
            margin-bottom: 25px;
            border: 1px solid rgba(0,0,0,0.02);
        }

        /* Header de la carte */
        .commande-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #f1f1f1;
            padding-bottom: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .resto-name {
            color: var(--accent-color);
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .commande-header h2 {
            margin: 0;
            font-size: 1.4rem;
            color: var(--primary-color);
        }

        .commande-meta {
            font-size: 0.9rem;
            color: var(--text-muted);
            background: #f8f9fa;
            padding: 6px 14px;
            border-radius: 20px;
        }

        /* Sections internes */
        h3 {
            font-size: 1.1rem;
            color: var(--text-main);
            margin-top: 25px;
            margin-bottom: 15px;
            border-left: 4px solid;
            padding-left: 10px;
        }
        h3.titre-formule { border-color: #f59e0b; color: #d97706; }
        h3.titre-article { border-color: #3b82f6; color: #2563eb; }

        /* Tableaux */
        .modern-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }
        .modern-table th {
            text-align: left;
            padding: 12px;
            font-size: 0.85rem;
            text-transform: uppercase;
            color: var(--text-muted);
            border-bottom: 2px solid #f1f1f1;
        }
        .modern-table td {
            padding: 12px;
            border-bottom: 1px solid #f1f1f1;
            vertical-align: top;
        }
        .col-right { text-align: right; }
        .col-center { text-align: center; }
        .font-bold { font-weight: 600; }

        .composition-tag {
            display: inline-block;
            font-size: 0.8rem;
            color: var(--text-muted);
            background-color: #f1f1f1;
            padding: 2px 8px;
            border-radius: 4px;
            margin-top: 4px;
        }

        /* Total */
        .total-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px dashed #e5e7eb;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }
        .total-price {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--success-color);
            margin-left: 15px;
        }

        /* Actions */
        .actions-group {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
        }

        .btn-annuler {
            background-color: white;
            color: var(--danger-color);
            border: 1px solid #fee2e2;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-annuler:hover { background-color: #fef2f2; border-color: #fca5a5; }

        .btn-confirmer {
            background-color: var(--success-color);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 6px rgba(39, 174, 96, 0.2);
        }
        .btn-confirmer:hover {
            background-color: #219150;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(39, 174, 96, 0.3);
        }

        /* Empty State */
        .alert-empty {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            color: var(--text-muted);
        }
        
        .btn-retour-simple {
            display: inline-block;
            margin-top: 30px;
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 600;
        }
        .btn-retour-simple:hover { color: var(--primary-color); }

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
                <a href="commande.php?client_id=<?= $_SESSION['client_id'] ?>" class="active">Panier</a>
                <a href="suivi.php">Suivi</a>
                
                <?php if (!isset($_SESSION['is_guest']) || !$_SESSION['is_guest']): ?>
                    <a href="historique.php">Historique</a>
                <?php endif; ?>

                <a href="logout.php" class="btn-logout">Se déconnecter</a>
            </div>
        <?php else: ?>
            <div class="user-info">UberMiam 🍔</div>
            <div class="header-actions">
                <a href="login.php">Se connecter</a>
            </div>
        <?php endif; ?>
    </div>

    <h2 class="section-title">🛒 Valider mon panier</h2>

    <?php if (isset($_SESSION['is_guest']) && $_SESSION['is_guest']): ?>
        <div style="background-color:#fff3cd; color:#856404; padding:15px; border-radius:12px; margin-bottom:30px; border:1px solid #ffeeba;">
            <strong>ℹ️ Mode Invité :</strong> Ce panier est temporaire. Une fois la commande validée, vous devrez créer un compte pour la retrouver dans votre historique.
        </div>
    <?php endif; ?>

    <?php
    if (!empty($historiqueCommandes)) {

        foreach ($historiqueCommandes as $commande) {

            $commande_id = $commande['commande_id'];
            $dateCmd = new DateTime($commande['date_commande']);
            $dateAffichee = $dateCmd->format('d/m/Y à H:i');

            echo "<div class='commande-card'>";
                
                // HEADER COMMANDE
                echo "<div class='commande-header'>";
                    echo "<div>";
                        echo "<div class='resto-name'>" . htmlspecialchars($restaurant_commande) . "</div>";
                        echo "<h2>Commande #" . htmlspecialchars($commande_id) . "</h2>";
                    echo "</div>";

                    echo "<div class='commande-meta'>";
                        echo "📅 " . $dateAffichee;
                        if (!empty($commande['heure_retrait'])) {
                            $retraitCmd = new DateTime($commande['heure_retrait']);
                            echo " &bull; ⏱️ " . $retraitCmd->format('H:i');
                        }
                    echo "</div>";
                echo "</div>";

                // BODY
                echo "<div class='commande-body'>";
                    
                    // 1. FORMULES
                    if (!empty($commande['liste_formules'])) {
                        echo "<h3 class='titre-formule'>🍱 Formules</h3>";
                        echo "<table class='modern-table'><thead><tr><th>Menu</th><th>Composition</th><th class='col-right'>Prix</th></tr></thead><tbody>";
                        foreach ($commande['liste_formules'] as $formule) {
                            echo "<tr>";
                            echo "<td class='font-bold'>" . htmlspecialchars($formule['nom']) . "</td>";
                            echo "<td>";
                            if (!empty($formule['items'])) {
                                echo "<span class='composition-tag'>" . htmlspecialchars(implode(' + ', $formule['items'])) . "</span>";
                            }
                            echo "</td>";
                            echo "<td class='col-right font-bold'>" . number_format($formule['prix'], 2, ',', ' ') . " €</td>";
                            echo "</tr>";
                        }
                        echo "</tbody></table>";
                    }

                    // 2. ARTICLES
                    if (!empty($commande['liste_articles'])) {
                        echo "<h3 class='titre-article'>📦 Articles à la carte</h3>";
                        echo "<table class='modern-table'><thead><tr><th>Article</th><th class='col-right'>P.U.</th><th class='col-center'>Qté</th><th class='col-right'>Total</th></tr></thead><tbody>";
                        foreach ($commande['liste_articles'] as $item) {
                            $sousTotal = $item['prix'] * $item['quantite'];
                            echo "<tr>";
                            echo "<td class='font-bold'>" . htmlspecialchars($item['nom']) . "</td>";
                            echo "<td class='col-right'>" . number_format($item['prix'], 2, ',', ' ') . " €</td>";
                            echo "<td class='col-center'>x" . $item['quantite'] . "</td>";
                            echo "<td class='col-right font-bold'>" . number_format($sousTotal, 2, ',', ' ') . " €</td>";
                            echo "</tr>";
                        }
                        echo "</tbody></table>";
                    }

                    // 3. TOTAL
                    echo "<div class='total-section'>";
                        echo "<span style='font-size:1.1rem; color:#6c757d;'>Total à payer :</span>";
                        echo "<span class='total-price'>" . number_format($commande['prix_total_remise'], 2, ',', ' ') . " €</span>";
                    echo "</div>";

                    // 4. ACTIONS
                    echo "<div class='actions-group'>";
                        
                        // Annuler
                        echo "<form action='annuler_commande.php' method='POST' onsubmit=\"return confirm('Êtes-vous sûr de vouloir vider votre panier ?');\">";
                        echo "<input type='hidden' name='commande_id' value='" . $commande_id . "'>";
                        echo "<button type='submit' class='btn-annuler'>🗑️ Annuler</button>";
                        echo "</form>";

                        // Confirmer
                        echo "<form action='confirmer_commande.php' method='POST'>";
                        echo "<input type='hidden' name='commande_id' value='" . $commande_id . "'>";
                        echo "<button type='submit' class='btn-confirmer'>✅ Valider et Payer</button>";
                        echo "</form>";
                    
                    echo "</div>"; // Fin actions

                echo "</div>"; // Fin body
            echo "</div>"; // Fin card
        }

    } else {
        echo "<div class='alert-empty'>";
            echo "<h3>Votre panier est vide 🛒</h3>";
            echo "<p>Vous n'avez pas encore sélectionné d'articles.</p>";
            echo "<a href='index.php' class='btn-confirmer' style='text-decoration:none; display:inline-block; margin-top:15px;'>Choisir un restaurant</a>";
        echo "</div>";
    }
    ?>

    <a href="index.php" class="btn-retour-simple">← Retour aux restaurants</a>

</div>

</body>
</html>