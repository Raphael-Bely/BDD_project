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

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-actions a {
            text-decoration: none;
            font-weight: 600;
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
<style>
    /* --- CSS Existant --- */
    .restaurant-card {
        border: 1px solid #ddd;
        padding: 20px;
        margin-bottom: 15px;
        border-radius: 8px;
        background-color: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s;
    }

    .restaurant-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .restaurant-card a {
        font-weight: bold;
        text-decoration: none;
        color: #2c3e50;
        font-size: 1.2em;
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
    /* --- NOUVEAU CSS POUR LES FILTRES --- */
    .filtres-container {
        margin: 20px 0;
        overflow-x: auto;
        /* Permet de scroller si trop de catégories */
        white-space: nowrap;
        padding-bottom: 10px;
    }

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
            gap: 30px;
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
            text-decoration: none; /* Le lien englobe potentiellement le titre */
            color: inherit;
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
        
        .card-title:hover { color: var(--accent-color); }

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
    .btn-filtre {
        display: inline-block;
        padding: 8px 16px;
        margin-right: 10px;
        border-radius: 20px;
        text-decoration: none;
        color: #555;
        background-color: #f1f1f1;
        font-size: 0.9em;
        transition: all 0.3s ease;
        border: 1px solid #ddd;
    }

    .btn-filtre:hover {
        background-color: #e2e6ea;
    }

    /* Style du bouton actif */
    .btn-filtre.actif {
        background-color: #e67e22;
        /* Orange UberMiam */
        color: white;
        border-color: #d35400;
    }

    .header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f1f1f1;
    }

    .header-bar {
        background-color: #3498db;
        padding: 15px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-radius: 5px;
    }

    .header-bar a {
        color: white;
        margin: 0 10px;
        text-decoration: none;
        font-weight: 500;
    }

    .header-bar a:hover {
        text-decoration: underline;
    }
</style>

<div class="container">

    <div class="header-bar">
        <?php if ($est_connecte): ?>
            <div>Bonjour, <strong><?= htmlspecialchars($nom_client) ?></strong> ! 👋</div>
            
            <div class="header-actions">
                <button onclick="getLocation()" class="btn-geo">
                    📍 Trouver les restos autour de moi 
                </button>
            
                <a href="commande.php" style="color:var(--primary-color);">Ma dernière commande</a>
                <a href="logout.php" style="color: #e74c3c;">Se déconnecter</a>
            </div>
        <?php else: ?>
            <div class="header-actions">
                <a href="login.php">Se connecter</a>
                <a href="create_account.php" style="background:var(--primary-color); color:white; padding:8px 15px; border-radius:8px; text-decoration:none;">Créer un compte</a>
            </div>
        <?php endif; ?>
    </div>
<!-- HEADER (Inchangé) -->
<div class="header-bar">
    <?php if (isset($_SESSION['client_id'])): ?>
        <p>Bonjour, <strong><?= htmlspecialchars($_SESSION['client_nom']) ?></strong>
            <?php if (isset($_SESSION['is_guest']) && $_SESSION['is_guest']): ?>
                <span
                    style="background-color: #f39c12; padding: 2px 8px; border-radius: 3px; font-size: 0.8em; margin-left: 5px;">Mode
                    Invité</span>
            <?php endif; ?>
            !
        </p>

        <div>
            <a href="commande.php?client_id=<?= $_SESSION['client_id'] ?>">🛒 Mon panier</a>
            <a href="suivi.php">📦 Suivi</a>
            <?php if (!isset($_SESSION['is_guest']) || !$_SESSION['is_guest']): ?>
                <a href="historique.php">📋 Historique</a>
            <?php endif; ?>
            <a href="logout.php" style="color: red;">Se déconnecter</a>
        </div>
    <?php else: ?>
        <div>
            <a href="login_invite.php"
                style="background-color: #27ae60; padding: 8px 15px; border-radius: 5px; margin-right: 10px;">👤 Commander
                en tant qu'invité</a>
            <a href="login.php">Se connecter</a> |
            <a href="create_account.php">Créer un compte</a>
        </div>
    <?php endif; ?>
</div>

    <div class="filtres-wrapper">
        <a href="index.php" class="btn-filtre <?= ($current_cat === null && $lat === null) ? 'actif' : '' ?>">
            Tout voir
        </a>
<h2>Liste des Restaurants Disponibles 🍽️</h2>

<?php
// --- 1. RÉCUPÉRATION DES CATÉGORIES POUR LES BOUTONS ---
// On suppose que $db est déjà connecté via require 'config/Database.php' plus haut dans le contrôleur
$sql_cats = "SELECT * FROM categories_restaurants ORDER BY nom";
$stmt_cats = $db->query($sql_cats);
$categories = $stmt_cats->fetchAll(PDO::FETCH_ASSOC);

// Récupération de l'ID catégorie actuel (si cliqué)
$current_cat = isset($_GET['cat_id']) ? $_GET['cat_id'] : null;
?>

<!-- AFFICHAGE DES FILTRES -->
<div class="filtres-container">
    <!-- Bouton "Tous" -->
    <a href="index.php" class="btn-filtre <?= $current_cat === null ? 'actif' : '' ?>">
        Tout voir
    </a>

        <?php foreach ($categories as $cat): ?>
            <a href="index.php?cat_id=<?= $cat['categorie_restaurant_id'] ?>" 
               class="btn-filtre <?= $current_cat == $cat['categorie_restaurant_id'] ? 'actif' : '' ?>">
                <?= htmlspecialchars($cat['nom']) ?>
            </a>
        <?php endforeach; ?>
    </div>
    <!-- Boucle sur les catégories -->
    <?php foreach ($categories as $cat): ?>
        <a href="index.php?cat_id=<?= $cat['categorie_restaurant_id'] ?>"
            class="btn-filtre <?= $current_cat == $cat['categorie_restaurant_id'] ? 'actif' : '' ?>">
            <?= htmlspecialchars($cat['nom']) ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- LOGIQUE DE RÉCUPÉRATION DES RESTAURANTS -->
<?php
// Si une catégorie est sélectionnée, on filtre
if ($current_cat) {
    // Lire le fichier SQL de filtre
    $sql_filter = file_get_contents(__DIR__ . '/../../sql_requests/getRestaurantsByCategory.sql');
    $stmt = $db->prepare($sql_filter);
    $stmt->execute(['id_cat' => $current_cat]);
} else {
    // Sinon, on affiche tout (votre logique actuelle)
    // Assurez-vous que getAllRestaurants.sql est bien chargé avant
    $sql_all = file_get_contents(__DIR__ . '/../../sql_requests/getAllRestaurants.sql');
    $stmt = $db->query($sql_all);
}
?>

<!-- LISTE DES CARTES -->
<?php
if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        echo "<div class='restaurant-card'>";
        echo "<a href='menu.php?id={$restaurant_id}'>{$nom}</a>";
        echo "<p style='color:#666; margin-top:5px;'>📍 {$adresse}</p>";

        // Petit bonus : Afficher la catégorie sur la carte aussi si disponible
        // (Nécessite que votre requête SQL getAllRestaurants récupère aussi la catégorie)
        echo "</div>";
    }
} else {
    echo "<div style='text-align:center; padding: 40px; color: #777;'>";
    echo "<p>Aucun restaurant trouvé pour cette catégorie. 😔</p>";
    echo "<a href='index.php'>Voir tous les restaurants</a>";
    echo "</div>";
}
?>