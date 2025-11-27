<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Nutritionnels</title>
    <style>
        /* --- 1. Variables & Base (Cohérence avec le reste du site) --- */
        :root {
            --bg-body: #f8f9fa;
            --bg-card: #ffffff;
            --text-main: #2c3e50;
            --text-muted: #636e72;
            --accent-color: #27ae60; /* Vert Nutrition */
            --energy-color: #e67e22; /* Orange pour les calories */
            --protein-color: #3498db; /* Bleu pour les protéines */
            --shadow-soft: 0 10px 30px rgba(0,0,0,0.05);
            --radius-main: 16px;
            --font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--bg-body);
            color: var(--text-main);
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }

        .wrapper {
            width: 100%;
            max-width: 600px; /* Plus étroit pour un effet "fiche" élégant */
        }

        /* --- 2. Bouton Retour --- */
        .btn-retour {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 20px;
            transition: color 0.2s, transform 0.2s;
        }
        
        .btn-retour:hover {
            color: var(--text-main);
            transform: translateX(-5px);
        }

        /* --- 3. Carte Principale --- */
        .nutrition-card {
            background-color: var(--bg-card);
            border-radius: var(--radius-main);
            box-shadow: var(--shadow-soft);
            padding: 40px;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.03);
        }

        h2 {
            margin-top: 0;
            font-weight: 800;
            font-size: 1.8rem;
            text-align: center;
            margin-bottom: 30px;
            letter-spacing: -0.5px;
        }

        h3 {
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            margin-top: 30px;
            margin-bottom: 15px;
            font-weight: 700;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        /* --- 4. Section Ingrédients --- */
        .ingredient-box {
            background-color: #fcfcfc;
            border: 1px dashed #dfe6e9;
            border-radius: 12px;
            padding: 20px;
        }

        .ingredient-list {
            font-style: normal;
            color: var(--text-main);
            line-height: 1.8;
            margin: 0;
            font-size: 0.95rem;
            text-align: justify;
        }

        /* --- 5. Grille des Valeurs Nutritionnelles (Style App) --- */
        .macros-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }

        .macro-card {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .macro-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border-color: transparent;
        }

        .macro-label {
            display: block;
            font-size: 0.85rem;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .macro-value {
            display: block;
            font-size: 1.8rem;
            font-weight: 800;
        }

        .unit {
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-muted);
        }

        .portion-info {
            text-align: center;
            font-size: 0.85rem;
            color: #b2bec3;
            margin-top: 15px;
            font-style: italic;
        }

        /* --- États vides --- */
        .empty-state {
            text-align: center;
            color: var(--text-muted);
            padding: 20px;
        }

    </style>
</head>
<body>

<div class="wrapper">
    <a href="javascript:history.back()" class="btn-retour">
        <span>←</span> &nbsp; Retour au plat
    </a>

    <div class="nutrition-card">
        <h2>Composition & Nutrition</h2>

        <?php
        if (isset($stmt) && $stmt->rowCount() > 0) {
            
            // --- 1. CALCULS (Logique PHP inchangée mais isolée) ---
            $liste_ingredients = [];
            $masse_totale_plat = 0;
            $total_kcal_absolu = 0;
            $total_proteines_absolu = 0;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $nom = $row['nom'];
                $qte = $row['quantite_g']; 
                $kcal_100g_ing = $row['kcal_pour_100g'];
                $prot_100g_ing = $row['proteines_pour_100g'];

                $liste_ingredients[] = htmlspecialchars($nom);

                $masse_totale_plat += $qte;

                $total_kcal_absolu += ($kcal_100g_ing / 100) * $qte;
                $total_proteines_absolu += ($prot_100g_ing / 100) * $qte;
            }

            if ($masse_totale_plat > 0) {
                $kcal_final_100g = ($total_kcal_absolu / $masse_totale_plat) * 100; 
                $prot_final_100g = ($total_proteines_absolu / $masse_totale_plat) * 100;
            } else {
                $kcal_final_100g = 0;
                $prot_final_100g = 0;
            }
            ?>

            <h3>🌱 Ingrédients</h3>
            <div class="ingredient-box">
                <p class="ingredient-list">
                    <?= implode(', ', $liste_ingredients) ?>.
                </p>
            </div>

            <h3>⚡ Valeurs Moyennes</h3>
            
            <div class="macros-grid">
                <div class="macro-card" style="border-bottom: 4px solid var(--energy-color);">
                    <span class="macro-label">Énergie</span>
                    <span class="macro-value" style="color: var(--energy-color);">
                        <?= number_format($kcal_final_100g, 0) ?>
                        <span class="unit">kcal</span>
                    </span>
                </div>

                <div class="macro-card" style="border-bottom: 4px solid var(--protein-color);">
                    <span class="macro-label">Protéines</span>
                    <span class="macro-value" style="color: var(--protein-color);">
                        <?= number_format($prot_final_100g, 1) ?>
                        <span class="unit">g</span>
                    </span>
                </div>
            </div>

            <p class="portion-info">Calculé pour 100 g de produit fini</p>

        <?php
        } else {
            echo "<div class='empty-state'><p>Les informations nutritionnelles pour ce plat ne sont pas disponibles actuellement.</p></div>";
        }
        ?>
    </div>
</div>

</body>
</html>