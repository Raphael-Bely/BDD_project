<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurants</title>
    <style>
        /* --- CSS --- */
        :root {
            --bg-body: #f8f9fa;
            --text-main: #2c3e50;
            --primary-color: #1a1a1a;
            --accent-color: #e67e22;
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
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* HEADER */
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

        .header-actions a {
            text-decoration: none;
            margin-left: 15px;
            font-weight: 600;
        }

        /* FILTRES */
        .filtres-wrapper {
            margin-bottom: 40px;
            overflow-x: auto;
            white-space: nowrap;
            padding-bottom: 10px;
            /* Masquer scrollbar */
            scrollbar-width: none; 
            -ms-overflow-style: none;
        }
        .filtres-wrapper::-webkit-scrollbar { display: none; }

        .btn-filtre {
            display: inline-block;
            padding: 10px 24px;
            margin-right: 12px;
            border-radius: 50px;
            text-decoration: none;
            color: #7f8c8d;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .btn-filtre:hover, .btn-filtre.actif {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        /* TITRE */
        .section-title {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 30px;
            color: var(--text-main);
            border-left: 5px solid var(--accent-color);
            padding-left: 15px;
        }

        /* GRILLE RESTAURANTS (CORRECTION DU CHEVAUCHEMENT) */
        .restaurant-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 40px; /* Espace large entre les cartes */
            padding-bottom: 50px;
            align-items: start; /* Empêche l'étirement vertical */
        }

        .restaurant-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 25px;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            position: relative;
            min-height: 180px; /* Hauteur min pour le look */
        }

        .restaurant-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
            z-index: 5;
        }

        .card-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text-main);
            text-decoration: none;
            margin-bottom: 15px;
            display: block;
            line-height: 1.3;
        }
        
        .card-address {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-top: auto; 
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="header-bar">
        <?php if ($est_connecte): ?>
            <div>Bonjour, <strong><?= htmlspecialchars($nom_client) ?></strong> ! 👋</div>
            <div class="header-actions">
                <a href="commande.php" style="color:var(--primary-color);">Ma dernière commande</a>
                <a href="logout.php" style="color: #e74c3c;">Se déconnecter</a>
            </div>
        <?php else: ?>
            <div class="header-actions">
                <a href="login.php">Se connecter</a>
                <a href="create_account.php" style="background:var(--primary-color); color:white; padding:8px 15px; border-radius:8px;">Créer un compte</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="filtres-wrapper">
        <a href="index.php" class="btn-filtre <?= $cat_id === null ? 'actif' : '' ?>">
            Tout voir
        </a>

        <?php foreach ($categories as $cat): ?>
            <a href="index.php?cat_id=<?= $cat['categorie_restaurant_id'] ?>" 
               class="btn-filtre <?= $cat_id == $cat['categorie_restaurant_id'] ? 'actif' : '' ?>">
                <?= htmlspecialchars($cat['nom']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <h2 class="section-title">
    <?= ($current_cat && $stmt_cat) 
        ? "Restaurants sélectionnés : " . htmlspecialchars($stmt_cat->fetch(PDO::FETCH_ASSOC)['nom']) 
        : "Nos Restaurants Partenaires 🍽️" 
    ?>
</h2>

    <div class="restaurant-grid">
        <?php
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Extraction sécurisée
                $id = $row['restaurant_id'];
                $nom = htmlspecialchars($row['nom']);
                $adresse = htmlspecialchars($row['adresse']);
                
                echo "<div class='restaurant-card'>";
                    echo "<a href='menu.php?id={$id}' class='card-title'>{$nom}</a>";
                    echo "<p class='card-address'>📍 {$adresse}</p>";
                echo "</div>";
            }
        } else {
            // Affichage si vide
            echo "<div style='grid-column: 1 / -1; padding: 40px; background:white; border-radius:12px; text-align:center;'>";
            echo "<p style='font-size:1.2rem; color:#7f8c8d;'>Aucun restaurant trouvé pour cette catégorie. 😔</p>";
            echo "<a href='index.php' style='color:var(--accent-color); font-weight:bold; text-decoration:none;'>Retourner à la liste complète</a>";
            echo "</div>";
        }
        ?>
    </div>

</div>

</body>
</html>
