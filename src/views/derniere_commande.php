<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Commandes</title>
    <style>
       /* Ton CSS d'origine... */
       .sub-table-header { background-color: #e67e22; color: white; } /* Orange pour distinguer */
       .composition-text { font-size: 0.9em; color: #666; font-style: italic; }
    </style>
</head>
<body>

    <a href="index.php" class="btn-retour">← Retour</a>

    <?php
    if (!empty($historiqueCommandes)) {

        foreach ($historiqueCommandes as $commande) {
            
            $commande_id = $commande['commande_id'];
            // Note: Assure-toi que ton SQL principal récupère bien 'date_commande' et 'prix_total_remise'
            $dateCmd = new DateTime($commande['date_commande']); 
            $dateAffichee = $dateCmd->format('d/m/Y à H:i');

            echo "<div class='commande-card'>";
                
                echo "<div class='commande-header'>";
                    echo "<h2>Commande #" . htmlspecialchars($commande_id) . "</h2>";
                echo "</div>";

                echo "<div class='commande-info'>";
                    echo "<p><span class='label'>Date :</span> " . $dateAffichee . "</p>";
                    
                    if (!empty($commande['heure_retrait'])) {
                        $retraitCmd = new DateTime($commande['heure_retrait']);
                        echo "<p><span class='label'>Retrait :</span> " . $retraitCmd->format('H:i') . "</p>";
                    }

                    // ==========================================
                    // 1. AFFICHAGE DES FORMULES
                    // ==========================================
                    if (!empty($commande['liste_formules'])) {
                        echo "<h3>🍱 Formules </h3>";
                        
                        echo "<table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>";
                        echo "<thead><tr class='sub-table-header'>
                                <th style='padding: 8px; text-align: left;'>Menu</th>
                                <th style='padding: 8px; text-align: left;'>Composition</th>
                                <th style='padding: 8px; text-align: right;'>Prix</th>
                              </tr></thead><tbody>";

                        foreach ($commande['liste_formules'] as $formule) {
                            echo "<tr style='border-bottom: 1px solid #ecf0f1;'>";
                            
                            // Nom de la formule
                            echo "<td style='padding: 10px; vertical-align: top;'><strong>" . htmlspecialchars($formule['nom']) . "</strong></td>";
                            
                            // Liste des items (Entrée, Plat...) regroupés
                            echo "<td style='padding: 10px;'>";
                            if (!empty($formule['items'])) {
                                echo "<span class='composition-text'>" . htmlspecialchars(implode(', ', $formule['items'])) . "</span>";
                            }
                            echo "</td>";

                            // Prix
                            echo "<td style='padding: 10px; text-align: right; vertical-align: top; font-weight: bold;'>" . number_format($formule['prix'], 2, ',', ' ') . " €</td>";
                            
                            echo "</tr>";
                        }
                        echo "</tbody></table>";
                    }

                    // ==========================================
                    // 2. AFFICHAGE DES ARTICLES À LA CARTE
                    // ==========================================
                    if (!empty($commande['liste_articles'])) {
                        echo "<h3>📦 Articles à la carte</h3>";
                        
                        echo "<table style='width: 100%; border-collapse: collapse; margin-top: 5px;'>";
                        echo "<thead><tr style='background-color: #34495e; color: white;'>
                                <th style='padding: 10px; text-align: left;'>Article</th>
                                <th style='padding: 10px; text-align: left;'>Prix unitaire</th>
                                <th style='padding: 10px; text-align: center;'>Qté</th>
                                <th style='padding: 10px; text-align: right;'>Sous-total</th>
                              </tr></thead><tbody>";
                        
                        foreach ($commande['liste_articles'] as $item) {
                            $nom = htmlspecialchars($item['nom']);
                            $prix = $item['prix'];
                            $quantite = $item['quantite'];
                            $sousTotal = $prix * $quantite;
                            
                            echo "<tr style='border-bottom: 1px solid #ecf0f1;'>";
                            echo "<td style='padding: 10px;'>{$nom}</td>";
                            echo "<td style='padding: 10px;'>" . number_format($prix, 2, ',', ' ') . " €</td>";
                            echo "<td style='padding: 10px; text-align: center;'>{$quantite}</td>";
                            echo "<td style='padding: 10px; text-align: right;'>" . number_format($sousTotal, 2, ',', ' ') . " €</td>";
                            echo "</tr>";
                        }
                        echo "</tbody></table>";
                    }

                    // TOTAL GÉNÉRAL (Issu de la table commande)
                    echo "<div class='prix-total' style='margin-top:20px; text-align:right; font-size:1.4em; color:#27ae60; font-weight:bold;'>";
                    echo "TOTAL COMMANDE : " . number_format($commande['prix_total_remise'], 2, ',', ' ') . " €";
                    echo "</div>";

                    // Formulaire Annulation
                    echo "<form action='annuler_commande.php' method='POST' style='margin-top:20px;' onsubmit=\"return confirm('Êtes-vous sûr ?');\">";
                        echo "<input type='hidden' name='commande_id' value='" . $commande_id . "'>";
                        echo "<button type='submit' class='btn-annuler'>🗑️ Annuler la commande</button>";
                    echo "</form>";

                echo "</div>"; 
            echo "</div>"; 
        }
    } else {
        echo "<div class='commande-card'><p class='alert'>Aucune commande trouvée.</p></div>";
    }
    ?>

</body>
</html>