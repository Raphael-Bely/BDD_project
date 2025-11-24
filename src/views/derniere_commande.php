<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Commandes</title>
    <style>
       /* Ton CSS d'origine... */
    </style>
</head>
<body>

    <a href="index.php" class="btn-retour">← Retour</a>

    <?php
    // 1. On vérifie simplement si le tableau est rempli
    if (!empty($historiqueCommandes)) {

        // 2. On boucle sur chaque commande du tableau
        foreach ($historiqueCommandes as $commande) {
            
            // On récupère les variables pour l'affichage général
            // (Comme tu faisais avec extract, mais depuis le tableau)
            $commande_id = $commande['commande_id'];
            $prix_total = $commande['prix_total_remise'];
            $dateCmd = new DateTime($commande['date_commande']);
            $dateAffichee = $dateCmd->format('d/m/Y à H:i');

            echo "<div class='commande-card'>";
                
                // --- HEADER DE LA CARTE ---
                echo "<div class='commande-header'>";
                    echo "<h2>Commande #" . htmlspecialchars($commande_id) . "</h2>";
                echo "</div>";

                echo "<div class='commande-info'>";
                    echo "<p><span class='label'>Date de commande :</span> " . $dateAffichee . "</p>";
                    
                    // Gestion de l'heure de retrait
                    if (!empty($commande['heure_retrait'])) {
                        $retraitCmd = new DateTime($commande['heure_retrait']);
                        echo "<p><span class='label'>Heure de retrait prévue :</span> " . $retraitCmd->format('H:i') . "</p>";
                    } else {
                        echo "<p><span class='label'>Retrait :</span> <span style='color:#777; font-style:italic;'>Non spécifié</span></p>";
                    }

                    echo "<h2>📦 Articles de la commande</h2>";
                    
                    // --- LISTE DES ARTICLES ---
                    // Ici, au lieu de refaire une requête SQL, on lit le sous-tableau 'liste_articles'
                    // que le contrôleur a déjà préparé pour nous.
                    
                    if (!empty($commande['liste_articles'])) {
                        $totalCalcul = 0;
                        
                        // Tableau HTML (Copie conforme de ton code)
                        echo "<table style='width: 100%; border-collapse: collapse; margin-top: 15px;'>";
                        echo "<thead><tr style='background-color: #34495e; color: white;'>
                                <th style='padding: 10px; text-align: left;'>Article</th>
                                <th style='padding: 10px; text-align: left;'>Prix unitaire</th>
                                <th style='padding: 10px; text-align: center;'>Quantité</th>
                                <th style='padding: 10px; text-align: right;'>Sous-total</th>
                              </tr></thead><tbody>";
                        
                        // Boucle sur les articles
                        foreach ($commande['liste_articles'] as $item) {
                            $nom = htmlspecialchars($item['nom']);
                            $prix = $item['prix'];
                            $quantite = $item['quantite']; // Attention: vérifie si c'est 'quantite' ou 'c.quantite' selon ton SQL
                            $sousTotal = $prix * $quantite;
                            $totalCalcul += $sousTotal;
                            
                            echo "<tr style='border-bottom: 1px solid #ecf0f1;'>";
                            echo "<td style='padding: 10px;'><strong>{$nom}</strong></td>";
                            echo "<td style='padding: 10px;'>" . number_format($prix, 2, ',', ' ') . " €</td>";
                            echo "<td style='padding: 10px; text-align: center;'>{$quantite}</td>";
                            echo "<td style='padding: 10px; text-align: right; font-weight: bold; color: #e74c3c;'>" . number_format($sousTotal, 2, ',', ' ') . " €</td>";
                            echo "</tr>";
                        }
                        
                        echo "<tr style='background-color: #ecf0f1; font-weight: bold; font-size: 1.1em;'>";
                        echo "<td colspan='3' style='padding: 10px;'>TOTAL</td>";
                        echo "<td style='padding: 10px; text-align: right; color: #27ae60; font-size: 1.2em;'>" . number_format($totalCalcul, 2, ',', ' ') . " €</td>";
                        echo "</tr></tbody></table>";

                    } else {
                        echo "<p style='color: #7f8c8d; font-style: italic;'>Aucun article trouvé pour cette commande.</p>";
                    }

                    // --- BOUTON ANNULER ---
                    echo "<form action='annuler_commande.php' method='POST' onsubmit=\"return confirm('Êtes-vous sûr ?');\">";
                        echo "<input type='hidden' name='commande_id' value='" . $commande_id . "'>";
                        echo "<button type='submit' class='btn-annuler'>🗑️ Annuler la commande</button>";
                    echo "</form>";

                echo "</div>"; // Fin commande-info
            echo "</div>"; // Fin commande-card
        }
    } else {
        echo "<div class='commande-card'>";
        echo "<p class='alert'>Aucune commande trouvée pour ce client.</p>";
        echo "</div>";
    }
    ?>

</body>
</html>