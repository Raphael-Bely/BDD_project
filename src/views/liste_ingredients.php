<?php
// Contrôleur utilisé : composition.php
// Informations transmises (Vue -> Contrôleur via GET) :
// - item_id (depuis l'URL) : L'identifiant du plat dont on veut voir la composition.

// Informations importées (Contrôleur -> Vue) :
// - stmt : Objet PDOStatement contenant la liste des ingrédients (nom, quantité, kcal, protéines) récupérée via le modèle Ingredient.
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingrédients du Plat</title>
    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: #f8f9fa;
            color: #2c3e50;
            margin: 0;
            padding: 40px 20px;
            line-height: 1.6;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            gap: 15px;
        }

        .btn-retour {
            text-decoration: none;
            color: #6b7280;
            font-weight: 600;
            transition: color 0.2s;
        }

        .btn-retour:hover {
            color: #111827;
        }

        h1 {
            margin: 0;
            font-size: 1.8rem;
            color: #111827;
        }

        .ingredients-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            padding: 30px;
            border: 1px solid #e5e7eb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th {
            background-color: #f9fafb;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #e5e7eb;
            color: #374151;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        table tr:hover {
            background-color: #f9fafb;
        }

        .no-items {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
            font-style: italic;
        }

        .info-box {
            background-color: #e0f2fe;
            border-left: 4px solid #0284c7;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            color: #0c4a6e;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="header">
        <a href="javascript:history.back()" class="btn-retour">← Retour</a>
        <h1>🥘 Ingrédients du plat</h1>
    </div>

    <div class="ingredients-card">
        
        <div class="info-box">
            ℹ️ Voici la composition détaillée de ce plat avec les informations nutritionnelles.
        </div>

        <?php
        if ($stmt && $stmt->rowCount() > 0) {
            echo "<table>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Ingrédient</th>";
            echo "<th style='text-align: center;'>Quantité (g)</th>";
            echo "<th style='text-align: center;'>Kcal/100g</th>";
            echo "<th style='text-align: center;'>Protéines/100g</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $nom = htmlspecialchars($row['nom']);
                $quantite = $row['quantite_g'] ?? 0;
                $kcal = $row['kcal_pour_100g'] ?? 0;
                $proteines = $row['proteines_pour_100g'] ?? 0;
                
                echo "<tr>";
                echo "<td><strong>{$nom}</strong></td>";
                echo "<td style='text-align: center;'>{$quantite}g</td>";
                echo "<td style='text-align: center;'>{$kcal} kcal</td>";
                echo "<td style='text-align: center;'>{$proteines}g</td>";
                echo "</tr>";
            }
            
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<div class='no-items'>Aucun ingrédient trouvé pour ce plat.</div>";
        }
        ?>

    </div>

</div>

</body>
</html>