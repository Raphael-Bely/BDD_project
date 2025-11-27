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
            /* Pour que le header ne dépasse pas */
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

        /* Orange */
        h3.titre-article {
            border-color: #3b82f6;
            color: #2563eb;
        }

        /* Bleu */

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

        /* Colonnes spécifiques */
        .col-right {
            text-align: right;
        }

        .col-center {
            text-align: center;
        }

        .font-bold {
            font-weight: 600;
            color: #111827;
        }

        /* Composition des formules */
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
            /* Vert success */
        }

        /* --- BOUTON ANNULER --- */
        .actions-footer {
            margin-top: 20px;
            text-align: right;
        }

        .btn-annuler {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .btn-annuler:hover {
            background-color: #fca5a5;
            color: #7f1d1d;
            border-color: #fca5a5;
        }

        /* --- EMPTY STATE --- */
        .alert-empty {
            text-align: center;
            padding: 40px;
            color: #6b7280;
            background: white;
            border-radius: 16px;
            border: 1px solid #e5e7eb;

            /* Ton CSS d'origine... */
            .sub-table-header {
                background-color: #e67e22;
                color: white;
            }

            /* Orange pour distinguer */
            .composition-text {
                font-size: 0.9em;
                color: #666;
                font-style: italic;
            }

            .header-nav {
                margin-bottom: 20px;
                padding: 10px;
                background-color: #f8f9fa;
                border-radius: 5px;
            }

            .header-nav a {
                margin-right: 15px;
                text-decoration: none;
                color: #3498db;
            }

            .header-nav a:hover {
                text-decoration: underline;
            }
    </style>
</head>

<body>

    <div class="header-nav">
        <a href="index.php" class="btn-retour">← Retour</a>
        <?php if (isset($_SESSION['client_id'])): ?>
            <a href="historique.php">Historique</a>
        <?php endif; ?>
    </div>

    <?php
    if (!empty($historiqueCommandes)) {

        foreach ($historiqueCommandes as $commande) {

            $commande_id = $commande['commande_id'];
            $dateCmd = new DateTime($commande['date_commande']);
            $dateAffichee = $dateCmd->format('d/m/Y à H:i');

            // --- DÉBUT CARTE ---
            echo "<div class='commande-card'>";

            // --- HEADER ---
            echo "<div class='commande-header'>";
            echo "<div>";
            echo "<h2>Commande #" . htmlspecialchars($commande_id) . "</h2>";
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
                        // Petit style badge pour la composition
                        echo "<span class='composition-tag'>" . htmlspecialchars(implode(' + ', $formule['items'])) . "</span>";
                    }
                    echo "</td>";
                    echo "<td class='col-right font-bold'>" . number_format($formule['prix'], 2, ',', ' ') . " €</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            }
            echo "</td>";

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

            // 3. TOTAL
            echo "<div class='total-section'>";
            echo "<span class='total-label'>Total payé :</span>";
            echo "<span class='total-price'>" . number_format($commande['prix_total_remise'], 2, ',', ' ') . " €</span>";
            echo "</div>";

            // 4. ACTION (Annuler)
            echo "<div class='actions-footer'>";
            echo "<form action='annuler_commande.php' method='POST' onsubmit=\"return confirm('Êtes-vous sûr de vouloir annuler cette commande ? Cette action est irréversible.');\">";
            echo "<input type='hidden' name='commande_id' value='" . $commande_id . "'>";
            echo "<button type='submit' class='btn-annuler'>🗑️ Annuler cette commande</button>";
            echo "</form>";
            echo "</div>";

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