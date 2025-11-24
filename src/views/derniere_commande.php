<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma Dernière Commande</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background-color: #f4f4f9; }
        .commande-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 20px;
            max-width: 500px;
            margin: 0 auto;
            border-left: 5px solid #2c3e50;
            position: relative; /* Pour le positionnement */
        }
        .commande-header {
            border-bottom: 1px solid #eee;
            margin-bottom: 15px;
            padding-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .commande-header h2 { margin: 0; color: #333; }
        .commande-info p { margin: 8px 0; font-size: 1.1em; }
        .label { font-weight: bold; color: #555; }
        .prix-total {
            font-size: 1.4em;
            font-weight: bold;
            color: #27ae60;
            margin-top: 15px;
            text-align: right;
        }
        .alert { color: #c0392b; font-weight: bold; }
        .btn-retour { display: inline-block; margin-top: 20px; text-decoration: none; color: #333;}

        /* NOUVEAU STYLE POUR LE BOUTON ANNULER */
        .btn-annuler {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 15px;
            width: 100%;
            transition: background 0.3s;
        }
        .btn-annuler:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

    <a href="index.php" class="btn-retour">← Retour</a>

    <?php
    if ($stmt->rowCount() > 0) {

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row); // Cela crée les variables $date_commande, $prix_total_remise, $commande_id, etc.
            
            $dateCmd = new DateTime($date_commande);
            $dateAffichee = $dateCmd->format('d/m/Y à H:i');

            $prixAffiche = number_format($prix_total_remise, 2, ',', ' ');

            echo "<div class='commande-card'>";
                echo "<div class='commande-header'>";
                    echo "<h2>Commande #" . htmlspecialchars($commande_id) . "</h2>";
                echo "</div>";

                echo "<div class='commande-info'>";
                    echo "<p><span class='label'>Date de commande :</span> " . $dateAffichee . "</p>";
                    
                    if (!empty($heure_retrait)) {
                        $retraitCmd = new DateTime($heure_retrait);
                        $heureRetrait = $retraitCmd->format('H:i');
                        echo "<p><span class='label'>Heure de retrait prévue :</span> " . $heureRetrait . "</p>";
                    } else {
                        echo "<p><span class='label'>Retrait :</span> <span style='color:#777; font-style:italic;'>Non spécifié</span></p>";
                    }
                    echo "<h2>📦 Articles de la commande</h2>";
                    
                    // Récupérer les articles
                    $commande_model = new Commande($db);
                    $stmt_items = $commande_model->afficherItemCommande($commande_id);
                    
                    if ($stmt_items->rowCount() > 0) {
                        $total = 0;
                        
                        echo "<table style='width: 100%; border-collapse: collapse; margin-top: 15px;'>";
                        echo "<thead>";
                        echo "<tr style='background-color: #34495e; color: white;'>";
                        echo "<th style='padding: 10px; text-align: left;'>Article</th>";
                        echo "<th style='padding: 10px; text-align: left;'>Prix unitaire</th>";
                        echo "<th style='padding: 10px; text-align: center;'>Quantité</th>";
                        echo "<th style='padding: 10px; text-align: right;'>Sous-total</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        
                        while ($item = $stmt_items->fetch(PDO::FETCH_ASSOC)) {
                            $nom = htmlspecialchars($item['nom']);
                            $prix = $item['prix'];
                            $quantite = $item['quantite'];
                            $sousTotal = $prix * $quantite;
                            $total += $sousTotal;
                            
                            echo "<tr style='border-bottom: 1px solid #ecf0f1;'>";
                            echo "<td style='padding: 10px;'><strong>{$nom}</strong></td>";
                            echo "<td style='padding: 10px;'>" . number_format($prix, 2, ',', ' ') . " €</td>";
                            echo "<td style='padding: 10px; text-align: center;'>{$quantite}</td>";
                            echo "<td style='padding: 10px; text-align: right; font-weight: bold; color: #e74c3c;'>" . number_format($sousTotal, 2, ',', ' ') . " €</td>";
                            echo "</tr>";
                        }
                        
                        echo "<tr style='background-color: #ecf0f1; font-weight: bold; font-size: 1.1em;'>";
                        echo "<td colspan='3' style='padding: 10px;'>TOTAL</td>";
                        echo "<td style='padding: 10px; text-align: right; color: #27ae60; font-size: 1.2em;'>" . number_format($total, 2, ',', ' ') . " €</td>";
                        echo "</tr>";
                        echo "</tbody>";
                        echo "</table>";
                    } else {
                        echo "<p style='color: #7f8c8d; font-style: italic;'>Aucun article trouvé pour cette commande.</p>";
                    }
                    // --- AJOUT DU FORMULAIRE D'ANNULATION ---
                    // Ce formulaire envoie l'ID à 'annuler_commande.php' via POST
                    echo "<form action='annuler_commande.php' method='POST' onsubmit=\"return confirm('Êtes-vous sûr de vouloir annuler cette commande ? Cette action est irréversible.');\">";
                        echo "<input type='hidden' name='commande_id' value='" . $commande_id . "'>";
                        echo "<button type='submit' class='btn-annuler'>🗑️ Annuler la commande</button>";
                    echo "</form>";
                    // ----------------------------------------

                echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<div class='commande-card'>";
        echo "<p class='alert'>Aucune commande trouvée pour ce client.</p>";
        echo "</div>";
    }
    ?>

</body>
</html>