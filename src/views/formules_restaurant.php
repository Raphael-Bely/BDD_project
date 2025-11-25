<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formules du Restaurant</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; padding: 20px; background-color: #f4f4f9; }
        
        .header-bar { margin-bottom: 20px; }
        .btn-retour { text-decoration: none; color: #333; font-weight: bold; }
        
        h2 { color: #2c3e50; border-bottom: 2px solid #ddd; padding-bottom: 10px; }

        .formules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .formule-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 20px;
            border-left: 5px solid #e67e22; /* Orange pour les formules */
            transition: transform 0.2s;
        }
        .formule-card:hover { transform: translateY(-5px); }

        .formule-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .formule-title { font-size: 1.4em; margin: 0; color: #333; }
        .formule-prix { 
            font-size: 1.5em; 
            font-weight: bold; 
            color: #e67e22; 
            background: #fff3e0;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .composition-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .composition-item {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
            color: #555;
            display: flex;
            align-items: center;
        }
        /* Petite puce stylisée */
        .composition-item::before {
            content: "✔";
            color: #27ae60;
            margin-right: 10px;
            font-weight: bold;
        }
        .composition-item:last-child { border-bottom: none; }

        .empty-msg { color: #7f8c8d; font-style: italic; }
    </style>
</head>
<body>

    <div class="header-bar">
        <a href="menu.php?id=<?= htmlspecialchars($restaurant_id) ?>" class="btn-retour">← Retour à la carte des plats</a>
    </div>

    <h2>Formules 🍱</h2>

    <div class="formules-grid">
        <?php
        // --- ÉTAPE 1 : REGROUPEMENT DES DONNÉES ---
        // La requête SQL renvoie une ligne par composant.
        // On doit regrouper tout ça par "Nom de formule".
        
        $formules_organisees = [];

        if ($info_formules->rowCount() > 0) {
            while ($row = $info_formules->fetch(PDO::FETCH_ASSOC)) {
                $nom = $row['nom'];
                
                // Si cette formule n'est pas encore dans notre liste, on l'initialise
                if (!isset($formules_organisees[$nom])) {
                    $formules_organisees[$nom] = [
                        'prix' => $row['prix'],
                        'composition' => [] // Liste des IDs de catégories
                    ];
                }

                // On ajoute la catégorie (composant) à cette formule
                $formules_organisees[$nom]['composition'][] = $row['nom_categorie'];
            }

            // --- ÉTAPE 2 : AFFICHAGE ---
            foreach ($formules_organisees as $nom_formule => $details) {
                ?>
                <div class="formule-card">
                    <div class="formule-header">
                        <h3 class="formule-title"><?= htmlspecialchars($nom_formule) ?></h3>
                        <span class="formule-prix"><?= htmlspecialchars($details['prix']) ?> €</span>
                    </div>
                    
                    <p><strong>Composition :</strong></p>
                    <ul class="composition-list">
                        <?php foreach ($details['composition'] as $nom_categorie): ?>
                            <li class="composition-item">
                                <?= htmlspecialchars($nom_categorie) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php
            }
        } else {
            echo "<p class='empty-msg'>Aucune formule disponible pour ce restaurant.</p>";
        }
        ?>
    </div>

</body>
</html>