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
            --green-success: #27ae60;
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

        /* --- HEADER MODERNE --- */
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

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .header-actions a {
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
        }

        /* BOUTON GPS */
        .btn-geo {
            cursor: pointer;
            padding: 8px 16px;
            border-radius: 50px;
            border: 1px solid #ddd;
            background: white;
            color: var(--text-main);
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .btn-geo:hover {
            border-color: var(--accent-color);
            color: var(--accent-color);
            background-color: #fff8f0;
        }

        /* FILTRES */
        .filtres-wrapper {
            margin-bottom: 40px;
            overflow-x: auto;
            white-space: nowrap;
            padding-bottom: 10px;
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

        /* GRILLE RESTAURANTS */
        .restaurant-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 40px;
            padding-bottom: 50px;
            align-items: start;
        }

        .restaurant-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 25px;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            min-height: 180px;
            
            /* --- MODIFICATION ICI --- */
            text-decoration: none; /* Enlève le soulignement du lien global */
            color: inherit;        /* Garde la couleur noire */
        }

        .restaurant-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .card-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text-main);
            text-decoration: none;
            margin-bottom: 10px;
            display: block;
            line-height: 1.3;
        }
        
        /* On change la couleur du titre au survol de la CARTE entière */
        .restaurant-card:hover .card-title { 
            color: var(--accent-color); 
        }

        .card-address {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-top: auto; 
            margin-bottom: 0;
        }

        .distance-badge {
            display: inline-block;
            color: var(--green-success);
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 10px;
            background-color: #e8f8f0;
            padding: 4px 8px;
            border-radius: 4px;
            width: fit-content;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="header-bar">
        <?php if ($est_connecte): ?>
            <div>
                Bonjour, <strong><?= htmlspecialchars($nom_client) ?></strong> ! 👋
                
                <?php if (isset($_SESSION['is_guest']) && $_SESSION['is_guest']): ?>
                    <span style="background-color: #f39c12; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.8em; margin-left: 5px;">
                        Mode Invité
                    </span>
                <?php endif; ?>
            </div>
            
            <div class="header-actions">
                <button onclick="getLocation()" class="btn-geo">
                    📍 Trouver les restos autour de moi 
                </button>
            
                <a href="commande.php" style="color:var(--primary-color);">🛒 Mon Panier</a>
                <a href="suivi.php" style="color:var(--primary-color);">📦 Suivi</a>
                
                <?php if (!isset($_SESSION['is_guest']) || !$_SESSION['is_guest']): ?>
                    <a href="historique.php" style="color:var(--primary-color);">📋 Historique</a>
                <?php endif; ?>

                <a href="logout.php" style="color: #e74c3c;">Se déconnecter</a>
            </div>

        <?php else: ?>

            <div class="header-actions">
                <a href="login_invite.php" style="background-color: #27ae60; color: white; padding: 8px 12px; border-radius: 6px;">
                    👤 Invité
                </a>
                <a href="login.php">Se connecter</a>
                <a href="create_account.php" style="background:var(--primary-color); color:white; padding:8px 15px; border-radius:8px; text-decoration:none;">
                    Créer un compte
                </a>
            </div>
        <?php endif; ?>
    </div>

    <div class="filtres-wrapper">
        <a href="index.php" class="btn-filtre <?= ($current_cat === null && $lat === null) ? 'actif' : '' ?>">
            Tout voir
        </a>

        <?php foreach ($categories as $cat): ?>
            <a href="index.php?cat_id=<?= $cat['categorie_restaurant_id'] ?>" 
               class="btn-filtre <?= $current_cat == $cat['categorie_restaurant_id'] ? 'actif' : '' ?>">
                <?= htmlspecialchars($cat['nom']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <h2 class="section-title">
        <?php
        if (isset($titre_special)) {
            echo htmlspecialchars($titre_special);
        } 
        elseif ($stmt_cat && $row_cat = $stmt_cat->fetch(PDO::FETCH_ASSOC)) {
            echo "Restaurants : " . htmlspecialchars($row_cat['nom']);
        } 
        else {
            echo "Nos Restaurants Partenaires 🍽️";
        }
        ?>
    </h2>

    <div class="restaurant-grid">
        <?php
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['restaurant_id'];
                $nom = htmlspecialchars($row['nom']);
                $adresse = htmlspecialchars($row['adresse']);
                
                // --- CHANGEMENT ICI : On ouvre le lien sur toute la carte ---
                echo "<a href='menu.php?id={$id}' class='restaurant-card'>";
                
                    // Le titre n'est plus un lien, mais un simple span/div
                    echo "<span class='card-title'>{$nom}</span>";
                    
                    // Affichage distance (Le code GPS de votre collègue)
                    if (isset($row['distance_km'])) {
                        $dist = number_format($row['distance_km'], 2); 
                        echo "<span class='distance-badge'>🏃 à {$dist} km</span>";
                    }

                    echo "<p class='card-address'>📍 {$adresse}</p>";
                    
                echo "</a>"; // On ferme le lien
            }
        } else {
            echo "<div style='grid-column: 1 / -1; padding: 40px; background:white; border-radius:12px; text-align:center;'>";
            echo "<p style='font-size:1.2rem; color:#7f8c8d;'>Aucun restaurant trouvé pour cette recherche. 😔</p>";
            echo "<a href='index.php' style='color:var(--accent-color); font-weight:bold; text-decoration:none;'>Retourner à la liste complète</a>";
            echo "</div>";
        }
        ?>
    </div>

</div>

<script>
    // Le script GPS reste inchangé
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            alert("La géolocalisation n'est pas supportée par ce navigateur.");
        }
    }

    function showPosition(position) {
        const lat = position.coords.latitude;
        const lon = position.coords.longitude;
        window.location.href = "index.php?action=geo&lat=" + lat + "&lon=" + lon;
    }

    function showError(error) {
        switch(error.code) {
            case error.PERMISSION_DENIED:
                alert("Vous avez refusé la géolocalisation.");
                break;
            case error.POSITION_UNAVAILABLE:
                alert("Position indisponible.");
                break;
            case error.TIMEOUT:
                alert("Délai d'attente dépassé.");
                break;
            default:
                alert("Erreur inconnue.");
        }
    }
</script>

</body>
</html>