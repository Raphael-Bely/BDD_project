<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Commandes</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- RESET & GLOBAL --- */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            color: #374151;
            margin: 0;
            padding: 40px 20px;
            line-height: 1.6;
        }

        /* --- HEADER NAV (Nouveau) --- */
        .header-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-retour {
            text-decoration: none;
            color: #6b7280;
            font-weight: 600;
            font-size: 0.95rem;
            transition: color 0.2s;
            display: flex;
            align-items: center;
        }

        .btn-retour:hover {
            color: #111827;
        }

        /* --- STYLE DU BOUTON HISTORIQUE (Style Pilule) --- */
        .btn-history {
            background-color: white;
            color: #374151;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 50px; /* Forme arrondie */
            font-weight: 600;
            font-size: 0.9rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-history:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            color: #000;
            border-color: #d1d5db;
        }

        /* --- CARTE COMMANDE --- */
        .commande-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            max-width: 800px;
            margin: 0 auto 30px auto;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        /* --- EN-TÊTE DE LA CARTE --- */
        .commande-header {
            background-color: #f9fafb;
            padding: 20px 30px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .commande-header h2 {
            margin: 0;
            font-size: 1.25rem;
            color: #111827;
            font-weight: 700;
        }

        .commande-meta {
            font-size: 0.9rem;
            color: #6b7280;
            background: #fff;
            padding: 5px 12px;
            border-radius: 20px;
            border: 1px solid #e5e7eb;
        }

        .commande-body {
            padding: 30px;
        }

        /* --- SECTIONS --- */
        h3 {
            font-size: 1.1rem;
            color: #374151;
            margin-top: 25px;
            margin-bottom: 15px;
            border-left: 4px solid;
            padding-left: 10px;
        }

        h3.titre-formule {
            border-color: #f59e0b;
            color: #d97706;
        }

        h3.titre-article {
            border-color: #3b82f6;
            color: #2563eb;
        }

        /* --- TABLEAUX --- */
        .modern-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        .modern-table th {
            text-align: left;
            padding: 12px 16px;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            border-bottom: 2px solid #f3f4f6;
        }

        .modern-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: top;
        }

        .col-right { text-align: right; }
        .col-center { text-align: center; }
        .font-bold { font-weight: 600; color: #111827; }

        .composition-tag {
            display: inline-block;
            font-size: 0.85rem;
            color: #6b7280;
            background-color: #f3f4f6;
            padding: 2px 8px;
            border-radius: 4px;
            margin-top: 4px;
        }

        /* --- TOTAL --- */
        .total-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px dashed #e5e7eb;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .total-label {
            font-size: 1.1rem;
            color: #6b7280;
            margin-right: 15px;
        }

        .total-price {
            font-size: 1.8rem;
            font-weight: 700;
            color: #10b981;
        }

        /* --- FOOTER ACTIONS --- */
        .actions-group {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
            gap: 15px; /* Espace entre les boutons */
        }

        .btn-annuler {
            background-color: white;
            color: #ef4444;
            border: 1px solid #fee2e2;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.95rem;
        }

        .btn-annuler:hover {
            background-color: #fef2f2;
            border-color: #fca5a5;
        }

        .btn-confirmer {
            background-color: #10b981; /* Vert moderne */
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.95rem;
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2);
        }

        .btn-confirmer:hover {
            background-color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(16, 185, 129, 0.3);
        }

        /* --- EMPTY STATE --- */
        .alert-empty {
            text-align: center;
            padding: 60px;
            color: #6b7280;
            background: white;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
</head>

<body>

    <div class="header-nav">
        <a href="index.php" class="btn-retour">← Retour</a>
        
        <?php if (isset($_SESSION['client_id'])): ?>
            <a href="suivi.php">📦 Suivi</a>
            <?php if (!isset($_SESSION['is_guest']) || !$_SESSION['is_guest']): ?>
                <a href="historique.php">📋 Historique</a>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php if (isset($_SESSION['is_guest']) && $_SESSION['is_guest']): ?>
        <div
            style="max-width: 800px; margin: 0 auto 20px; background-color: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; padding: 15px;">
            <strong>ℹ️ Mode Invité</strong>
            <p style="margin: 10px 0 0 0; color: #856404;">Vous commandez en tant qu'invité. Une fois votre commande
                confirmée, vous pourrez la suivre dans l'onglet "Suivi".
                <a href="create_account.php" style="color: #0056b3; text-decoration: underline; font-weight: bold;">Créer un
                    compte</a> pour
                conserver l'historique de vos commandes (votre panier actuel sera préservé).
            </p>
        </div>
    <?php endif; ?>

    <?php
    if (!empty($historiqueCommandes)) {

        foreach ($historiqueCommandes as $commande) {

            $commande_id = $commande['commande_id'];
            $dateCmd = new DateTime($commande['date_commande']);
            $dateAffichee = $dateCmd->format('d/m/Y à H:i');

            echo "<div class='commande-card'>";
                
                // HEADER
                echo "<div class='commande-header'>";
                    echo "<div>";
                        echo "<div style='color: #e67e22; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 5px; letter-spacing:0.5px;'>" . htmlspecialchars($restaurant_commande) . "</div>";
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
                    
                    // FORMULES
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

                    // ARTICLES
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

                    // TOTAL
                    echo "<div class='total-section'>";
                        echo "<span class='total-label'>Total à payer :</span>";
                        echo "<span class='total-price'>" . number_format($commande['prix_total_remise'], 2, ',', ' ') . " €</span>";
                    echo "</div>";

                    // ACTIONS (Regroupées)
                    echo "<div class='actions-group'>";
                        
                        // Bouton Annuler
                        echo "<form action='annuler_commande.php' method='POST' onsubmit=\"return confirm('Annuler cette commande ?');\">";
                        echo "<input type='hidden' name='commande_id' value='" . $commande_id . "'>";
                        echo "<button type='submit' class='btn-annuler'>🗑️ Annuler</button>";
                        echo "</form>";

                        // Bouton Confirmer
                        echo "<form action='confirmer_commande.php' method='POST' onsubmit=\"return confirm('Confirmer et payer la commande ?');\">";
                        echo "<input type='hidden' name='commande_id' value='" . $commande_id . "'>";
                        echo "<button type='submit' class='btn-confirmer'>✅ Valider la commande</button>";
                        echo "</form>";
                    
                    echo "</div>"; // Fin actions-group

                echo "</div>"; // Fin commande-body
            echo "</div>"; // Fin commande-card
        }

    } else {
        echo "<div class='alert-empty'>";
            echo "<h3>Votre panier est vide 🛒</h3>";
            echo "<p>Vous n'avez pas encore sélectionné d'articles.</p>";
            echo "<br>";
            echo "<a href='index.php' class='btn-confirmer' style='text-decoration:none;'>Aller choisir un restaurant</a>";
        echo "</div>";
    }
    ?>

</body>
</html>