<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Commandes</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

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

        /* --- BOUTON RETOUR --- */
        .btn-retour {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #6b7280;
            font-weight: 600;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .btn-retour:hover {
            color: #111827;
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
        }

        .commande-meta {
            font-size: 0.9rem;
            color: #6b7280;
        }

        .commande-body {
            padding: 30px;
        }

        /* --- SECTIONS (TITRES H3) --- */
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

        /* --- TABLEAUX MODERNES --- */
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

        .modern-table tr:last-child td {
            border-bottom: none;
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

        /* --- SECTION FIDELITE --- */
        .loyalty-section {
            background-color: #f0fdf4; /* Vert très clair */
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 15px 20px;
            margin-top: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .loyalty-info h4 { margin: 0; color: #15803d; font-size: 1.1rem; }
        .loyalty-info p { margin: 5px 0 0 0; color: #166534; font-size: 0.9rem; }
        .loyalty-badge {
            background-color: #16a34a;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 1.2rem;
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
        .actions-footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }

        .btn-annuler {
            background-color: white;
            color: #ef4444;
            border: 1px solid #ef4444;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .btn-annuler:hover {
            background-color: #fee2e2;
        }

        .btn-confirmer {
            background-color: #10b981;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.2s;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.4);
        }

        .btn-confirmer:hover {
            background-color: #059669;
            transform: translateY(-1px);
        }

        /* --- EMPTY STATE --- */
        .alert-empty {
            text-align: center;
            padding: 40px;
            color: #6b7280;
            background: white;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
        }
        .header-nav {
            margin-bottom: 20px;
            padding: 10px;
        }
    </style>
</head>

<body>

    <div class="header-nav">
        <a href="index.php" class="btn-retour">← Retour aux restaurants</a>
        <?php if (isset($_SESSION['client_id'])): ?>
<<<<<<< HEAD
            <a href="suivi.php">📦 Suivi</a>
            <?php if (!isset($_SESSION['is_guest']) || !$_SESSION['is_guest']): ?>
                <a href="historique.php">📋 Historique</a>
            <?php endif; ?>
=======
            <a href="historique.php" style="margin-left: 20px; text-decoration:none; color:#3b82f6; font-weight:600;">📋 Historique</a>
>>>>>>> 72459e48c9811f3f6682609e8a8d73888d171707
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

            $points_gagnes = isset($commande['points_gagnes_commande']) ? intval($commande['points_gagnes_commande']) : 0;
            $solde_actuel = isset($commande['solde_points_actuel']) ? intval($commande['solde_points_actuel']) : 0;
    
            echo "<div class='commande-card'>";

          
            echo "<div class='commande-header'>";
            echo "<div>";
    
            if (isset($commande['restaurant_nom'])) {
                echo "<div style='color: #e67e22; font-weight: 600; font-size: 0.9rem; text-transform: uppercase; margin-bottom: 4px;'>" . htmlspecialchars($commande['restaurant_nom']) . "</div>";
            }
            echo "<h2 style='margin: 0;'>Commande #" . htmlspecialchars($commande_id) . "</h2>";
            echo "</div>";

            echo "<div class='commande-meta'>";
            echo "📅 " . $dateAffichee;
            if (!empty($commande['heure_retrait'])) {
                $retraitCmd = new DateTime($commande['heure_retrait']);
                echo " &nbsp;|&nbsp; ⏱️ Retrait : " . $retraitCmd->format('H:i');
            }
            echo "</div>";
            echo "</div>";

            // --- CORPS ---
            echo "<div class='commande-body'>";

            // 1. FORMULES
            if (!empty($commande['liste_formules'])) {
                echo "<h3 class='titre-formule'>🍱 Menus & Formules</h3>";

                echo "<table class='modern-table'>";
                echo "<thead>
                        <tr>
                            <th>Menu</th>
                            <th>Détail de la composition</th>
                            <th class='col-right'>Prix</th>
                        </tr>
                      </thead>
                      <tbody>";

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

            // 2. ARTICLES À LA CARTE
            if (!empty($commande['liste_articles'])) {
                echo "<h3 class='titre-article'>📦 Articles à la carte</h3>";

                echo "<table class='modern-table'>";
                echo "<thead>
                        <tr>
                            <th>Article</th>
                            <th class='col-right'>Prix Unit.</th>
                            <th class='col-center'>Qté</th>
                            <th class='col-right'>Total</th>
                        </tr>
                      </thead>
                      <tbody>";

                foreach ($commande['liste_articles'] as $item) {
                    $nom = htmlspecialchars($item['nom']);
                    $prix = $item['prix'];
                    $quantite = $item['quantite'];
                    $sousTotal = $prix * $quantite;

                    echo "<tr>";
                    echo "<td class='font-bold'>{$nom}</td>";
                    echo "<td class='col-right'>" . number_format($prix, 2, ',', ' ') . " €</td>";
                    echo "<td class='col-center'>x{$quantite}</td>";
                    echo "<td class='col-right font-bold' style='color:#374151;'>" . number_format($sousTotal, 2, ',', ' ') . " €</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            }

            // 3. FIDÉLITÉ (AFFICHAGE SÉCURISÉ)
            echo "<div class='loyalty-section'>";
            echo "<div class='loyalty-info'>";
            echo "<h4>🎁 Programme Fidélité</h4>";
            echo "<p>Solde actuel : <strong>{$solde_actuel} pts</strong></p>";
            echo "<p style='font-size:0.85em; color:#666;'>Nouveau solde après validation : " . ($solde_actuel + $points_gagnes) . " pts</p>";
            echo "</div>";
            echo "<div class='loyalty-badge'>+{$points_gagnes} pts</div>";
            echo "</div>";

            // 4. TOTAL
            echo "<div class='total-section'>";
            echo "<span class='total-label'>Total payé :</span>";
            echo "<span class='total-price'>" . number_format($commande['prix_total_remise'], 2, ',', ' ') . " €</span>";
            echo "</div>";

            // 5. ACTIONS (FOOTER)
            echo "<div class='actions-footer'>";
            
                // BOUTON ANNULER
                echo "<form action='annuler_commande.php' method='POST' onsubmit=\"return confirm('Êtes-vous sûr de vouloir annuler cette commande ? Cette action est irréversible.');\">";
                    echo "<input type='hidden' name='commande_id' value='" . $commande_id . "'>";
                    echo "<button type='submit' class='btn-annuler'>🗑️ Annuler</button>";
                echo "</form>";

                // BOUTON VALIDER (Redirige vers valider_panier.php avec les infos cachées)
                echo "<form action='valider_panier.php' method='POST' onsubmit=\"return confirm('Êtes-vous sûr de vouloir confirmer et payer cette commande ?');\">";
                    echo "<input type='hidden' name='commande_id' value='" . $commande_id . "'>";
                    // Champs cachés importants pour la fidélité
                    echo "<input type='hidden' name='restaurant_id' value='" . (isset($commande['restaurant_id']) ? $commande['restaurant_id'] : '') . "'>";
                    echo "<input type='hidden' name='total' value='" . $commande['prix_total_remise'] . "'>";
                    
                    echo "<button type='submit' class='btn-confirmer'>Valider et Payer</button>";
                echo "</form>";

            echo "</div>"; // Fin actions-footer

            echo "</div>"; // Fin commande-body
            echo "</div>"; // Fin commande-card
        }
    } else {
        echo "<div class='alert-empty'>";
        echo "<h3>Aucune commande en cours 🍽️</h3>";
        echo "<p>Vous n'avez pas encore passé de commande, ou votre historique est vide.</p>";
        echo "<a href='index.php' style='color:#2563eb; font-weight:bold; text-decoration:none;'>Aller choisir un restaurant</a>";
        echo "</div>";
    }
    ?>

</body>

</html>